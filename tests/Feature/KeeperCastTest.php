<?php

use App\Enums\Archetype;
use App\Enums\CharacterKind;
use App\Enums\CharacterStatus;
use App\Enums\Era;
use App\Enums\RoleEnum;
use App\Misc\ArchetypeTable;
use App\Misc\NpcGenerator;
use App\Models\Character;
use App\Models\Game;
use App\Models\Group;
use App\Models\Occupation;
use App\Models\Skill;
use App\Models\User;
use App\Models\Weapon;
use Database\Seeders\OccupationSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\SkillSeeder;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\DB;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->seed(SkillSeeder::class);
    $this->seed(OccupationSeeder::class);
    $this->withoutMiddleware(VerifyCsrfToken::class);

    // Weapons, the equipment catalogue and the four storage locations all come
    // from migrations, so a conjured character has something to hold.
    $this->group = Group::factory()->create(['name' => 'Dunwich Circle', 'era' => Era::Twenties]);
    $this->game  = $this->group->startGame('The Haunting');

    $this->keeper = User::factory()->inGroup($this->group)->create();
    $this->keeper->assignRole(RoleEnum::KEEPER->value);
});

/**
 * A player of a group, with an investigator in the given game.
 */
function playerWithInvestigator(Game $game, array $attributes = []): Character
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

/**
 * One of the Keeper's own, made the way the screen makes them.
 */
function conjured(User $keeper, Game $game, Archetype $archetype = Archetype::Cultist, ?Occupation $occupation = null): Character
{
    return NpcGenerator::conjure($archetype, $game, $keeper, $occupation);
}

// ---- Who may conjure ------------------------------------------------------

describe('access', function () {
    test('a guest is sent to the login page', function () {
        $this->post(route('keeper.npcs.store'), ['archetype' => 'cultist'])->assertRedirect(route('login'));
    });

    test('a player cannot conjure anybody up', function () {
        $player = User::factory()->inGroup($this->group)->create();
        $player->assignRole(RoleEnum::PLAYER->value);

        $this->actingAs($player)
            ->post(route('keeper.npcs.store'), ['archetype' => 'cultist'])
            ->assertForbidden();

        expect(Character::query()->where('kind', CharacterKind::NonPlayer)->count())->toBe(0);
    });

    test('an admin who does not run the game cannot either', function () {
        $admin = User::factory()->inGroup($this->group)->create();
        $admin->assignRole(RoleEnum::ADMIN->value);

        $this->actingAs($admin)
            ->post(route('keeper.npcs.store'), ['archetype' => 'cultist'])
            ->assertForbidden();
    });

    test('an archetype nobody has heard of is refused', function () {
        $this->actingAs($this->keeper)
            ->post(route('keeper.npcs.store'), ['archetype' => 'shoggoth'])
            ->assertSessionHasErrors('archetype');
    });

    test('a group between campaigns is told there is nowhere to put anybody', function () {
        $group  = Group::factory()->create();
        $keeper = User::factory()->inGroup($group)->create();
        $keeper->assignRole(RoleEnum::KEEPER->value);

        $this->actingAs($keeper)
            ->post(route('keeper.npcs.store'), ['archetype' => 'cultist'])
            ->assertSessionHas('error');

        expect(Character::query()->where('kind', CharacterKind::NonPlayer)->count())->toBe(0);
    });
});

// ---- What comes out -------------------------------------------------------

describe('conjuring', function () {
    test('one press gives a playable sheet in the game being played', function () {
        $this->actingAs($this->keeper)
            ->post(route('keeper.npcs.store'), ['archetype' => 'cultist'])
            ->assertRedirect()
            ->assertSessionHas('success');

        $npc = Character::query()->where('kind', CharacterKind::NonPlayer)->sole();

        expect($npc->keeper_id)->toBe($this->keeper->id)
            ->and($npc->user_id)->toBeNull()
            ->and($npc->group_id)->toBe($this->group->id)
            ->and($npc->archetype)->toBe(Archetype::Cultist)
            ->and($npc->status)->toBe(CharacterStatus::Complete)
            ->and($npc->name)->not->toBeEmpty()
            ->and($npc->occupation)->not->toBeEmpty()
            ->and($npc->games->pluck('id')->all())->toBe([$this->game->id]);
    });

    test('the characteristics are rolled and the rest derived from them', function () {
        $npc = conjured($this->keeper, $this->game);

        foreach (['strength', 'constitution', 'dexterity', 'appearance', 'power'] as $characteristic) {
            expect($npc->{$characteristic})->toBeGreaterThanOrEqual(15)->toBeLessThanOrEqual(90);
        }

        foreach (['size', 'intelligence', 'education'] as $characteristic) {
            expect($npc->{$characteristic})->toBeGreaterThanOrEqual(40)->toBeLessThanOrEqual(90);
        }

        expect($npc->hit_points)->toBe(intdiv($npc->constitution + $npc->size, 10))
            ->and($npc->magic_points)->toBe(intdiv($npc->power, 5))
            ->and($npc->dodge)->toBe(intdiv($npc->dexterity, 2))
            ->and($npc->luck)->toBeGreaterThanOrEqual(15);
    });

    test('the skills are spent, not left at the book values', function () {
        $npc = conjured($this->keeper, $this->game);

        $improved = $npc->skills->filter(fn (Skill $skill): bool => $skill->pivot->value > $skill->starting_value);

        // The ceiling is on points spent. Own language follows EDU and Credit
        // Rating follows the occupation's printed band, so both may sit above it.
        $spent = $npc->skills
            ->reject(fn (Skill $skill): bool => in_array($skill->slug, ['language_own', 'credit_rating'], true))
            ->max(fn (Skill $skill): int => (int) $skill->pivot->value);

        expect($improved)->not->toBeEmpty()
            ->and((int) $npc->skills->firstWhere('slug', 'language_own')->pivot->value)->toBe($npc->education)
            ->and($spent)->toBeLessThanOrEqual(75);
    });

    test('the occupation the keeper chose is the one they get', function () {
        $doctor = Occupation::where('name', 'Doctor of Medicine')->firstOrFail();

        $this->actingAs($this->keeper)->post(route('keeper.npcs.store'), [
            'archetype'     => 'ally',
            'occupation_id' => $doctor->id,
        ]);

        $npc = Character::query()->where('kind', CharacterKind::NonPlayer)->sole();

        expect($npc->occupation)->toBe('Doctor of Medicine')
            ->and($npc->occupation_id)->toBe($doctor->id);
    });

    test('without a choice the archetype picks something typical of itself', function () {
        $npc = conjured($this->keeper, $this->game, Archetype::Thug);

        expect(ArchetypeTable::for(Archetype::Thug)['occupations'])->toContain($npc->occupation);
    });

    test('a cultist knows something they should not, and it costs them sanity', function () {
        $npc = conjured($this->keeper, $this->game, Archetype::Cultist);

        $mythos = (int) $npc->skills->firstWhere('slug', 'cthulhu_mythos')->pivot->value;

        expect($mythos)->toBeGreaterThan(0)
            ->and($npc->sanity)->toBeLessThanOrEqual(99 - $mythos);
    });

    test('a fighter can fight, whatever the points said', function () {
        $npc    = conjured($this->keeper, $this->game, Archetype::Thug);
        $weapon = $npc->weapons->first();

        expect($weapon)->not->toBeNull();

        $skill = $npc->skills->firstWhere('slug', $weapon->skill);

        expect((int) $skill->pivot->value)
            ->toBeGreaterThanOrEqual(ArchetypeTable::for(Archetype::Thug)['combat_floor']);
    });

    test('a firearm arrives loaded with a spare magazine in a pocket', function () {
        // Rolled until an armed one turns up: the thug's candidates include a
        // cosh as well as a revolver, and only one of the two takes rounds.
        $loaded = collect(range(1, 12))
            ->map(fn (): Character => conjured($this->keeper, $this->game, Archetype::Thug))
            ->flatMap(fn (Character $npc) => $npc->weapons)
            ->first(fn (Weapon $weapon): bool => $weapon->magazine_capacity !== null);

        expect($loaded)->not->toBeNull()
            ->and((int) $loaded->pivot->ammo)->toBe($loaded->magazine_capacity)
            ->and((int) $loaded->pivot->ammo_reserve)->toBe($loaded->magazine_capacity);
    });

    test('an ordinary bystander is unarmed', function () {
        $npc = conjured($this->keeper, $this->game, Archetype::Bystander);

        expect($npc->weapons)->toBeEmpty();
    });

    test('they carry the essentials and nothing more', function () {
        $npc = conjured($this->keeper, $this->game, Archetype::Ally);

        expect($npc->equipment)->not->toBeEmpty()
            ->and($npc->equipment->count())->toBeLessThanOrEqual(count(ArchetypeTable::for(Archetype::Ally)['gear']))
            // Everything is somewhere, so the Equipment tab can group it.
            ->and($npc->equipment->every(fn ($item): bool => $item->pivot->storage_location_id !== null))->toBeTrue();
    });

    test('nothing anachronistic: a 1920s cult gets no Glock', function () {
        collect(range(1, 8))
            ->map(fn (): Character => conjured($this->keeper, $this->game, Archetype::Cultist))
            ->each(function (Character $npc): void {
                $npc->weapons->each(function (Weapon $weapon): void {
                    expect($weapon->eras)->toContain(Era::Twenties->value);
                });
            });
    });

    test('a modern game arms its cast from its own era', function () {
        $modern = $this->group->startGame('Night at the Museum', Era::Modern);
        $modern->activate();

        collect(range(1, 8))
            ->map(fn (): Character => conjured($this->keeper, $modern->refresh(), Archetype::Thug))
            ->each(function (Character $npc): void {
                $npc->weapons->each(function (Weapon $weapon): void {
                    expect($weapon->eras)->toContain(Era::Modern->value);
                });
            });
    });

    test('two of the same archetype are not the same person', function () {
        $first  = conjured($this->keeper, $this->game);
        $second = conjured($this->keeper, $this->game);

        $figures = fn (Character $npc): array => [
            $npc->strength, $npc->dexterity, $npc->power, $npc->education,
            ...$npc->skills->map(fn (Skill $skill): int => (int) $skill->pivot->value)->all(),
        ];

        expect($figures($first))->not->toBe($figures($second))
            ->and($first->slug)->not->toBe($second->slug);
    });
});

// ---- Whose they are -------------------------------------------------------

describe('visibility', function () {
    test('they sit under the party on the keeper screen, not in it', function () {
        playerWithInvestigator($this->game, ['name' => 'Harvey Walters']);
        conjured($this->keeper, $this->game);

        $this->actingAs($this->keeper)
            ->get(route('keeper.index'))
            ->assertInertia(fn (Assert $page) => $page
                ->has('party', 1)
                ->where('party.0.name', 'Harvey Walters')
                ->has('cast', 1)
                ->where('cast.0.archetype', 'Cultist')
                ->has('cast.0.hitPoints')
                ->has('archetypes', 4)
                ->has('occupations'));
    });

    test('another keeper of the same group sees their own cast and not this one', function () {
        $other = User::factory()->inGroup($this->group)->create();
        $other->assignRole(RoleEnum::KEEPER->value);

        conjured($this->keeper, $this->game);

        $this->actingAs($other)
            ->get(route('keeper.index'))
            ->assertInertia(fn (Assert $page) => $page->has('cast', 0));
    });

    test('no player is ever handed one in the shared props', function () {
        $investigator = playerWithInvestigator($this->game);

        conjured($this->keeper, $this->game);
        conjured($this->keeper, $this->game, Archetype::Thug);

        // Their own investigator is the only sheet in the group, whatever the
        // Keeper has been conjuring up beside it.
        $this->actingAs($investigator->player)
            ->get(route('character.show', $investigator->slug))
            ->assertInertia(fn (Assert $page) => $page
                ->has('auth.characters.all', 1)
                ->has('auth.characters.own', 1)
                ->has('auth.characters.others', 0));
    });

    test('a player cannot open one, even in their own group', function () {
        $investigator = playerWithInvestigator($this->game);
        $npc          = conjured($this->keeper, $this->game);

        $this->actingAs($investigator->player)
            ->get(route('character.show', $npc->slug))
            ->assertForbidden();
    });

    test('another keeper cannot open one either', function () {
        $other = User::factory()->inGroup($this->group)->create();
        $other->assignRole(RoleEnum::KEEPER->value);

        $npc = conjured($this->keeper, $this->game);

        $this->actingAs($other)->get(route('character.show', $npc->slug))->assertForbidden();
    });

    test('the keeper who made one can open and edit the sheet', function () {
        $npc = conjured($this->keeper, $this->game);

        $this->actingAs($this->keeper)->get(route('character.show', $npc->slug))->assertOk();

        $this->actingAs($this->keeper)
            ->put(route('attribute.update', $npc->slug), ['attribute' => 'hit_points', 'value' => 3])
            ->assertRedirect();

        expect($npc->refresh()->hit_points)->toBe(3);
    });
});

// ---- Getting rid of them --------------------------------------------------

describe('deleting', function () {
    test('it takes the sheet and everything on it away for good', function () {
        $npc = conjured($this->keeper, $this->game, Archetype::Thug);

        expect(DB::table('character_skill')->where('character_id', $npc->id)->count())->toBeGreaterThan(0)
            ->and(DB::table('equipables')->where('character_id', $npc->id)->count())->toBeGreaterThan(0);

        $this->actingAs($this->keeper)
            ->delete(route('keeper.npcs.destroy', $npc->slug))
            ->assertRedirect();

        expect(Character::withTrashed()->find($npc->id))->toBeNull()
            ->and(DB::table('character_skill')->where('character_id', $npc->id)->count())->toBe(0)
            ->and(DB::table('equipables')->where('character_id', $npc->id)->count())->toBe(0)
            ->and(DB::table('character_game')->where('character_id', $npc->id)->count())->toBe(0);
    });

    test('another keeper is told there is no such thing, and it survives', function () {
        $other = User::factory()->inGroup($this->group)->create();
        $other->assignRole(RoleEnum::KEEPER->value);

        $npc = conjured($this->keeper, $this->game);

        $this->actingAs($other)
            ->delete(route('keeper.npcs.destroy', $npc->slug))
            ->assertNotFound();

        expect(Character::find($npc->id))->not->toBeNull();
    });

    test('an investigator cannot be deleted through this door', function () {
        $investigator = playerWithInvestigator($this->game);

        $this->actingAs($this->keeper)
            ->delete(route('keeper.npcs.destroy', $investigator->slug))
            ->assertNotFound();

        expect(Character::find($investigator->id))->not->toBeNull();
    });

    test('a player cannot delete anybodys cast', function () {
        $player = User::factory()->inGroup($this->group)->create();
        $player->assignRole(RoleEnum::PLAYER->value);

        $npc = conjured($this->keeper, $this->game);

        $this->actingAs($player)
            ->delete(route('keeper.npcs.destroy', $npc->slug))
            ->assertForbidden();

        expect(Character::find($npc->id))->not->toBeNull();
    });

    test('a deleted game takes its cast with it and leaves the investigators alone', function () {
        $admin = User::factory()->inGroup($this->group)->create();
        $admin->assignRole(RoleEnum::ADMIN->value);

        $old = $this->group->startGame('An Earlier Case');

        $investigator = playerWithInvestigator($old);
        $npc          = conjured($this->keeper, $old);

        $this->actingAs($admin)
            ->delete(route('admin.games.destroy', $old->id))
            ->assertRedirect();

        expect(Character::withTrashed()->find($npc->id))->toBeNull()
            ->and(Character::find($investigator->id))->not->toBeNull();
    });
});
