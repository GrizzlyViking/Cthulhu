<?php

use App\Enums\CharacterStatus;
use App\Enums\RoleEnum;
use App\Misc\SkillCheck;
use App\Models\Character;
use App\Models\Game;
use App\Models\Group;
use App\Models\Skill;
use App\Models\User;
use App\Models\Weapon;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\SkillSeeder;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->seed(SkillSeeder::class);
    $this->withoutMiddleware(VerifyCsrfToken::class);

    $this->group = Group::factory()->create(['name' => 'Dunwich Circle']);
    $this->game  = $this->group->startGame('The Haunting');

    $this->keeper = User::factory()->inGroup($this->group)->create();
    $this->keeper->assignRole(RoleEnum::KEEPER->value);
});

/**
 * An investigator in the given game, owned by a player of its group.
 */
function investigator(Game $game, array $attributes = []): Character
{
    $player = User::factory()->inGroup(Group::findOrFail($game->group_id))->create();
    $player->assignRole(RoleEnum::PLAYER->value);

    $character = Character::factory()->create([
        'user_id'  => $player->id,
        'group_id' => $game->group_id,
        ...$attributes,
    ]);

    $character->games()->attach($game);

    return $character;
}

// ---- Who may look ---------------------------------------------------------

describe('access', function () {
    test('a guest is sent to the login page', function () {
        $this->get(route('keeper.index'))->assertRedirect(route('login'));
    });

    test('a player cannot reach the keeper screen', function () {
        $player = User::factory()->inGroup($this->group)->create();
        $player->assignRole(RoleEnum::PLAYER->value);

        $this->actingAs($player)->get(route('keeper.index'))->assertForbidden();
    });

    test('an admin who does not run the game is refused too', function () {
        $admin = User::factory()->inGroup($this->group)->create();
        $admin->assignRole(RoleEnum::ADMIN->value);

        $this->actingAs($admin)->get(route('keeper.index'))->assertForbidden();
    });

    test('a keeper gets the screen', function () {
        $this->actingAs($this->keeper)
            ->get(route('keeper.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('Keeper'));
    });
});

// ---- The party ------------------------------------------------------------

describe('the party', function () {
    test('it holds the investigators of the campaign being played, in name order', function () {
        investigator($this->game, ['name' => 'Zachary Bell']);
        investigator($this->game, ['name' => 'Abigail Crane']);

        $this->actingAs($this->keeper)
            ->get(route('keeper.index'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('Keeper')
                ->where('game.name', 'The Haunting')
                ->has('party', 2)
                ->where('party.0.name', 'Abigail Crane')
                ->where('party.1.name', 'Zachary Bell'));
    });

    test('an investigator from a finished campaign is not at the table', function () {
        $old = $this->group->startGame('An Earlier Case');

        investigator($this->game, ['name' => 'Present']);
        investigator($old, ['name' => 'Retired']);

        $this->actingAs($this->keeper)
            ->get(route('keeper.index'))
            ->assertInertia(fn (Assert $page) => $page
                ->has('party', 1)
                ->where('party.0.name', 'Present'));
    });

    test('another groups investigators are nowhere to be seen', function () {
        $foreign = Group::factory()->create();
        investigator($foreign->startGame('Not Yours'), ['name' => 'Stranger']);

        investigator($this->game, ['name' => 'Ours']);

        $this->actingAs($this->keeper)
            ->get(route('keeper.index'))
            ->assertInertia(fn (Assert $page) => $page
                ->has('party', 1)
                ->where('party.0.name', 'Ours'));
    });

    test('an unfinished draft has no figures worth reading and stays off', function () {
        investigator($this->game, ['name' => 'Half Made', 'status' => CharacterStatus::Draft]);
        investigator($this->game, ['name' => 'Finished']);

        $this->actingAs($this->keeper)
            ->get(route('keeper.index'))
            ->assertInertia(fn (Assert $page) => $page
                ->has('party', 1)
                ->where('party.0.name', 'Finished'));
    });

    test('it shows current figures, not maxima', function () {
        // The factory recomputes the derived stats after creating, so the
        // wounded figures have to be written afterwards.
        investigator($this->game, ['name' => 'Harvey Walters'])->update([
            'hit_points'   => 4,
            'sanity'       => 31,
            'magic_points' => 9,
            'luck'         => 55,
        ]);

        $this->actingAs($this->keeper)
            ->get(route('keeper.index'))
            ->assertInertia(fn (Assert $page) => $page
                ->where('party.0.hitPoints', 4)
                ->where('party.0.sanity', 31)
                ->where('party.0.magicPoints', 9)
                ->where('party.0.luck', 55));
    });

    test('whatever is currently wrong is listed worst first', function () {
        investigator($this->game, [
            'major_wound'        => true,
            'dying'              => true,
            'temporary_insanity' => true,
        ]);

        $this->actingAs($this->keeper)
            ->get(route('keeper.index'))
            ->assertInertia(fn (Assert $page) => $page
                ->where('party.0.conditions', ['Dying', 'Major wound', 'Temporary insanity']));
    });

    test('the passive skills are columns, so a glance often beats a roll', function () {
        $character = investigator($this->game);

        $spotHidden = Skill::where('slug', 'spot-hidden')->firstOrFail();
        $character->skills()->updateExistingPivot($spotHidden->id, ['value' => 71]);

        $this->actingAs($this->keeper)
            ->get(route('keeper.index'))
            ->assertInertia(fn (Assert $page) => $page
                ->has('passiveSkills', 3)
                ->where('passiveSkills.0.slug', 'spot-hidden')
                ->where('party.0.skills.spot-hidden', 71));
    });

    test('only weapons that take ammunition are worth a column', function () {
        $character = investigator($this->game);

        // The printed magazine cell is what says whether rounds are a thing
        // this weapon has — a knife's is a dash.
        $revolver = Weapon::factory()->create(['name' => 'Colt .45 Automatic', 'bullets_in_mag' => '7']);
        $knife    = Weapon::factory()->create(['name' => 'Trench Knife', 'bullets_in_mag' => '-']);

        $character->weapons()->attach($revolver->id, ['ammo' => 3, 'ammo_reserve' => 14]);
        $character->weapons()->attach($knife->id, ['ammo' => 0, 'ammo_reserve' => 0]);

        $this->actingAs($this->keeper)
            ->get(route('keeper.index'))
            ->assertInertia(fn (Assert $page) => $page
                ->has('party.0.firearms', 1)
                ->where('party.0.firearms.0.ammo', 3)
                ->where('party.0.firearms.0.reserve', 14));
    });

    test('a group between campaigns gets an empty screen rather than an error', function () {
        $group  = Group::factory()->create();
        $keeper = User::factory()->inGroup($group)->create();
        $keeper->assignRole(RoleEnum::KEEPER->value);

        $this->actingAs($keeper)
            ->get(route('keeper.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('game', null)
                ->has('party', 0));
    });
});

// ---- The secret roll ------------------------------------------------------

describe('the secret roll', function () {
    test('it answers with one outcome per investigator asked for', function () {
        $first  = investigator($this->game, ['name' => 'Abigail Crane']);
        $second = investigator($this->game, ['name' => 'Zachary Bell']);

        $response = $this->actingAs($this->keeper)->postJson(route('keeper.roll'), [
            'skill_slug' => 'spot-hidden',
            'characters' => [$first->id, $second->id],
        ]);

        $response->assertOk()
            ->assertJsonPath('skill', 'Spot Hidden')
            ->assertJsonCount(2, 'results')
            ->assertJsonStructure(['skill', 'results' => [['character_id', 'name', 'roll', 'value', 'outcome', 'success']]]);
    });

    test('it rolls against what is on the sheet', function () {
        $character  = investigator($this->game);
        $spotHidden = Skill::where('slug', 'spot-hidden')->firstOrFail();
        $character->skills()->updateExistingPivot($spotHidden->id, ['value' => 63]);

        $this->actingAs($this->keeper)
            ->postJson(route('keeper.roll'), ['skill_slug' => 'spot-hidden', 'characters' => [$character->id]])
            ->assertJsonPath('results.0.value', 63);
    });

    test('only the investigators asked for are rolled for', function () {
        $rolled = investigator($this->game);
        investigator($this->game);

        $this->actingAs($this->keeper)
            ->postJson(route('keeper.roll'), ['skill_slug' => 'listen', 'characters' => [$rolled->id]])
            ->assertJsonCount(1, 'results')
            ->assertJsonPath('results.0.character_id', $rolled->id);
    });

    test('an investigator outside the campaign being played is silently dropped', function () {
        $foreign = investigator(Group::factory()->create()->startGame('Not Yours'));
        $ours    = investigator($this->game);

        $this->actingAs($this->keeper)
            ->postJson(route('keeper.roll'), [
                'skill_slug' => 'spot-hidden',
                'characters' => [$foreign->id, $ours->id],
            ])
            ->assertJsonCount(1, 'results')
            ->assertJsonPath('results.0.character_id', $ours->id);
    });

    test('a skill that does not exist is refused', function () {
        $this->actingAs($this->keeper)
            ->postJson(route('keeper.roll'), ['skill_slug' => 'second-sight', 'characters' => []])
            ->assertStatus(422);
    });

    test('a player cannot roll in secret', function () {
        $player = User::factory()->inGroup($this->group)->create();
        $player->assignRole(RoleEnum::PLAYER->value);

        $this->actingAs($player)
            ->postJson(route('keeper.roll'), ['skill_slug' => 'spot-hidden', 'characters' => []])
            ->assertForbidden();
    });
});

// ---- The ladder -----------------------------------------------------------

test('the outcome always follows from the roll and the value', function () {
    foreach (range(1, 300) as $ignored) {
        ['roll' => $roll, 'value' => $value, 'outcome' => $outcome, 'success' => $success] = SkillCheck::against(60);

        expect($roll)->toBeGreaterThanOrEqual(1)->toBeLessThanOrEqual(100)
            ->and($value)->toBe(60);

        $expected = match (true) {
            $roll >= 99 => 'Critical Failure',
            $roll === 1 => 'Critical Success',
            $roll <= 12 => 'Extreme Success',
            $roll <= 30 => 'Hard Success',
            $roll <= 60 => 'Success',
            default     => 'Failure',
        };

        expect($outcome)->toBe($expected)
            ->and($success)->toBe(! in_array($outcome, ['Failure', 'Critical Failure'], true));
    }
});

test('a hopeless skill still criticals on a natural one, as it always has', function () {
    // The ladder checks the fumble range and then the natural 1 before it looks
    // at the value at all — house rule, kept deliberately.
    $outcomes = collect(range(1, 400))->map(fn (): string => SkillCheck::against(0)['outcome'])->unique();

    expect($outcomes->all())->toEqualCanonicalizing(['Critical Success', 'Critical Failure', 'Failure']);
});
