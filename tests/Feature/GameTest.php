<?php

use App\Enums\Era;
use App\Enums\RoleEnum;
use App\Models\Character;
use App\Models\Game;
use App\Models\Group;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $this->group = Group::factory()->create(['name' => 'Dunwich Circle', 'era' => Era::Twenties]);
    $this->admin = User::factory()->inGroup($this->group)->create();
    $this->admin->assignRole(RoleEnum::ADMIN->value);
});

// ---- One campaign at a time ---------------------------------------------

describe('the active game', function () {
    test('the first game a group starts becomes the one it plays', function () {
        $game = $this->group->startGame('The Haunting');

        expect($this->group->fresh()->active_game_id)->toBe($game->id)
            // Born in the group's era unless told otherwise.
            ->and($game->era)->toBe(Era::Twenties);
    });

    test('a later game waits its turn rather than taking over', function () {
        $first  = $this->group->startGame('The Haunting');
        $second = $this->group->startGame('Masks of Nyarlathotep');

        expect($this->group->fresh()->active_game_id)->toBe($first->id)
            ->and($second->fresh()->isActive())->toBeFalse();
    });

    test('activating a game stands the previous one down', function () {
        $first  = $this->group->startGame('The Haunting');
        $second = $this->group->startGame('Masks of Nyarlathotep');

        $second->activate();

        expect($first->fresh()->isActive())->toBeFalse()
            ->and($second->fresh()->isActive())->toBeTrue()
            ->and($this->group->fresh()->active_game_id)->toBe($second->id);
    });

    test('deleting the active game leaves the group playing nothing rather than two things', function () {
        $game = $this->group->startGame('The Haunting');

        $game->delete();

        expect($this->group->fresh()->active_game_id)->toBeNull();
    });
});

// ---- The era follows the campaign ---------------------------------------

describe('era resolution', function () {
    test('an investigator plays in their game era, not their group default', function () {
        $game = $this->group->startGame('Modern Times', Era::Modern);

        $character = Character::factory()->create(['group_id' => $this->group->id]);
        $character->games()->attach($game);

        expect($character->fresh()->era())->toBe(Era::Modern);
    });

    test('an investigator in no game falls back to the group default', function () {
        $group     = Group::factory()->create(['era' => Era::Modern]);
        $character = Character::factory()->create(['group_id' => $group->id]);

        expect($character->fresh()->era())->toBe(Era::Modern);
    });

    test('an ungrouped investigator plays in the twenties', function () {
        $character = Character::factory()->create(['group_id' => null]);

        expect($character->fresh()->era())->toBe(Era::Twenties);
    });

    test('a sheet left behind in a finished campaign still reads as that campaign', function () {
        $old = $this->group->startGame('The Twenties Job', Era::Twenties);
        $new = $this->group->startGame('Modern Times', Era::Modern);
        $new->activate();

        $character = Character::factory()->create(['group_id' => $this->group->id]);
        $character->games()->attach($old);

        expect($character->fresh()->era())->toBe(Era::Twenties)
            ->and($character->fresh()->in_active_game)->toBeFalse();
    });

    test('an investigator in both the old and the current campaign reads as the current one', function () {
        $old = $this->group->startGame('The Twenties Job', Era::Twenties);
        $new = $this->group->startGame('Modern Times', Era::Modern);
        $new->activate();

        $character = Character::factory()->create(['group_id' => $this->group->id]);
        $character->games()->attach([$old->id, $new->id]);

        expect($character->fresh()->era())->toBe(Era::Modern)
            ->and($character->fresh()->in_active_game)->toBeTrue();
    });

    test('the sheet is served the era of the game being played', function () {
        $game = $this->group->startGame('Modern Times', Era::Modern);

        $player = User::factory()->inGroup($this->group)->create();
        $player->assignRole(RoleEnum::PLAYER->value);

        $character = Character::factory()->create(['user_id' => $player->id, 'group_id' => $this->group->id]);
        $character->games()->attach($game);

        $this->actingAs($player)
            ->get(route('character.show', $character->slug))
            ->assertInertia(fn (Assert $page) => $page
                ->component('Character')
                ->where('era', Era::Modern->value)
                ->has('games', 1)
                ->where('games.0.active', true));
    });
});

// ---- Managing games in the admin section --------------------------------

describe('admin', function () {
    test('an admin starts a campaign for their own group', function () {
        $this->actingAs($this->admin)
            ->post(route('admin.games.store'), ['name' => 'The Haunting', 'era' => Era::Modern->value])
            ->assertRedirect();

        $game = Game::where('name', 'The Haunting')->firstOrFail();

        expect($game->group_id)->toBe($this->group->id)
            ->and($game->era)->toBe(Era::Modern)
            ->and($this->group->fresh()->active_game_id)->toBe($game->id);
    });

    test('the group page lists its games, marking the one being played', function () {
        $this->group->startGame('The Haunting');
        $this->group->startGame('Masks of Nyarlathotep');

        $this->actingAs($this->admin)
            ->get(route('admin.group.edit'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Group')
                ->has('games', 2)
                ->where('games', fn ($games) => collect($games)->where('active', true)->count() === 1));
    });

    test('a group cannot have two campaigns of the same name', function () {
        $this->group->startGame('The Haunting');

        $this->actingAs($this->admin)
            ->post(route('admin.games.store'), ['name' => 'The Haunting', 'era' => Era::Twenties->value])
            ->assertSessionHasErrors('name');

        expect($this->group->fresh()->games)->toHaveCount(1);
    });

    test('two groups may each run a campaign of the same name', function () {
        Group::factory()->create()->startGame('The Haunting');

        $this->actingAs($this->admin)
            ->post(route('admin.games.store'), ['name' => 'The Haunting', 'era' => Era::Twenties->value])
            ->assertSessionHasNoErrors();

        expect(Game::where('name', 'The Haunting')->count())->toBe(2);
    });

    test('an admin renames a campaign and moves it to another era', function () {
        $game = $this->group->startGame('The Haunting');

        $this->actingAs($this->admin)
            ->put(route('admin.games.update', $game), ['name' => 'Masks of Nyarlathotep', 'era' => Era::Modern->value])
            ->assertRedirect();

        expect($game->fresh()->name)->toBe('Masks of Nyarlathotep')
            ->and($game->fresh()->era)->toBe(Era::Modern);
    });

    test('the campaign being played cannot be deleted, only replaced', function () {
        $game = $this->group->startGame('The Haunting');

        $this->actingAs($this->admin)
            ->delete(route('admin.games.destroy', $game))
            ->assertRedirect();

        expect($game->fresh())->not->toBeNull()
            ->and($this->group->fresh()->active_game_id)->toBe($game->id);
    });

    test('deleting a finished campaign takes its investigators out of it but leaves their sheets', function () {
        $old = $this->group->startGame('The Haunting');
        $new = $this->group->startGame('Masks of Nyarlathotep');
        $new->activate();

        $character = Character::factory()->create(['group_id' => $this->group->id]);
        $character->games()->attach($old);

        $this->actingAs($this->admin)
            ->delete(route('admin.games.destroy', $old))
            ->assertRedirect();

        expect(Game::find($old->id))->toBeNull()
            ->and($character->fresh())->not->toBeNull()
            ->and($character->fresh()->games)->toBeEmpty();
    });

    test('another groups campaign is not there to be found', function () {
        $foreign = Group::factory()->create()->startGame('Not Yours');

        $this->actingAs($this->admin)
            ->put(route('admin.games.update', $foreign), ['name' => 'Mine Now', 'era' => Era::Twenties->value])
            ->assertNotFound();

        $this->actingAs($this->admin)
            ->put(route('admin.games.activate', $foreign))
            ->assertNotFound();

        $this->actingAs($this->admin)
            ->delete(route('admin.games.destroy', $foreign))
            ->assertNotFound();

        expect($foreign->fresh()->name)->toBe('Not Yours');
    });

    test('a player cannot start a campaign', function () {
        $player = User::factory()->inGroup($this->group)->create();
        $player->assignRole(RoleEnum::PLAYER->value);

        $this->actingAs($player)
            ->post(route('admin.games.store'), ['name' => 'The Haunting', 'era' => Era::Twenties->value])
            ->assertForbidden();

        expect(Game::count())->toBe(0);
    });
});

// ---- Players moving their investigators between campaigns ---------------

describe('choosing a game', function () {
    beforeEach(function () {
        $this->first  = $this->group->startGame('The Haunting');
        $this->second = $this->group->startGame('Masks of Nyarlathotep');

        $this->player = User::factory()->inGroup($this->group)->create();
        $this->player->assignRole(RoleEnum::PLAYER->value);

        $this->character = Character::factory()->create([
            'user_id'  => $this->player->id,
            'group_id' => $this->group->id,
        ]);
        $this->character->games()->attach($this->first);
    });

    test('a player moves their investigator between the groups campaigns', function () {
        $this->actingAs($this->player)
            ->put(route('character.games.update', $this->character->slug), ['games' => [$this->second->id]])
            ->assertRedirect();

        expect($this->character->fresh()->games->pluck('id')->all())->toBe([$this->second->id]);
    });

    test('an investigator may be played in two campaigns at once', function () {
        $this->actingAs($this->player)
            ->put(route('character.games.update', $this->character->slug), [
                'games' => [$this->first->id, $this->second->id],
            ])
            ->assertRedirect();

        expect($this->character->fresh()->games)->toHaveCount(2);
    });

    test('an investigator can be taken out of every campaign', function () {
        $this->actingAs($this->player)
            ->put(route('character.games.update', $this->character->slug), ['games' => []])
            ->assertRedirect();

        expect($this->character->fresh()->games)->toBeEmpty();
    });

    test('another groups campaign is refused rather than quietly dropped', function () {
        $foreign = Group::factory()->create()->startGame('Not Yours');

        $this->actingAs($this->player)
            ->put(route('character.games.update', $this->character->slug), ['games' => [$foreign->id]])
            ->assertSessionHasErrors('games.0');

        expect($this->character->fresh()->games->pluck('id')->all())->toBe([$this->first->id]);
    });

    test('a player cannot move somebody elses investigator', function () {
        $other = User::factory()->inGroup($this->group)->create();
        $other->assignRole(RoleEnum::PLAYER->value);

        $this->actingAs($other)
            ->put(route('character.games.update', $this->character->slug), ['games' => [$this->second->id]])
            ->assertForbidden();

        expect($this->character->fresh()->games->pluck('id')->all())->toBe([$this->first->id]);
    });

    test('a keeper may move a groupmates investigator between campaigns', function () {
        $keeper = User::factory()->inGroup($this->group)->create();
        $keeper->assignRole(RoleEnum::KEEPER->value);

        $this->actingAs($keeper)
            ->put(route('character.games.update', $this->character->slug), ['games' => [$this->second->id]])
            ->assertRedirect();

        expect($this->character->fresh()->games->pluck('id')->all())->toBe([$this->second->id]);
    });
});

// ---- New investigators join the campaign being played -------------------

describe('joining on creation', function () {
    test('a character made in the wizard joins the campaign the group is playing', function () {
        $game = $this->group->startGame('The Haunting');

        $player = User::factory()->inGroup($this->group)->create();
        $player->assignRole(RoleEnum::PLAYER->value);

        $this->actingAs($player)->post(route('character.wizard.store'), [
            'name'       => 'Harvey Walters',
            'gender'     => 'Male',
            'age'        => 42,
            'residence'  => 'Arkham',
            'birthplace' => 'Boston',
        ])->assertRedirect(route('character.create'));

        $draft = Character::where('name', 'Harvey Walters')->firstOrFail();

        expect($draft->games->pluck('id')->all())->toBe([$game->id]);
    });

    test('with no campaign to join the investigator is simply in none', function () {
        $player = User::factory()->inGroup($this->group)->create();
        $player->assignRole(RoleEnum::PLAYER->value);

        $this->actingAs($player)->post(route('character.wizard.store'), [
            'name'       => 'Harvey Walters',
            'gender'     => 'Male',
            'age'        => 42,
            'residence'  => 'Arkham',
            'birthplace' => 'Boston',
        ])->assertRedirect(route('character.create'));

        expect(Character::where('name', 'Harvey Walters')->firstOrFail()->games)->toBeEmpty();
    });
});

// ---- What the nav sorts on ----------------------------------------------

test('the shared props say which investigators are in the campaign being played', function () {
    $old = $this->group->startGame('The Haunting');
    $new = $this->group->startGame('Masks of Nyarlathotep');
    $new->activate();

    $player = User::factory()->inGroup($this->group)->create();
    $player->assignRole(RoleEnum::PLAYER->value);

    $inPlay = Character::factory()->create(['user_id' => $player->id, 'group_id' => $this->group->id]);
    $inPlay->games()->attach($new);

    $retired = Character::factory()->create(['user_id' => $player->id, 'group_id' => $this->group->id]);
    $retired->games()->attach($old);

    $this->actingAs($player)
        ->get(route('dashboard'))
        ->assertInertia(fn (Assert $page) => $page
            ->where('auth.characters.own', function ($own) use ($inPlay, $retired) {
                $byId = collect($own)->keyBy('id');

                return $byId[$inPlay->id]['in_active_game'] === true
                    && $byId[$retired->id]['in_active_game'] === false;
            }));
});

test('a groups games are gone when the group is, and its investigators are not', function () {
    $game      = $this->group->startGame('The Haunting');
    $character = Character::factory()->create(['group_id' => $this->group->id]);
    $character->games()->attach($game);

    $this->group->delete();

    expect(Game::find($game->id))->toBeNull()
        ->and($character->fresh())->not->toBeNull()
        ->and($character->fresh()->group_id)->toBeNull();
});
