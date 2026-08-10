<?php

use App\Enums\CharacterStatus;
use App\Models\Character;
use App\Models\Game;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * A character belonging to the given games, edited at the given time.
 *
 * The factory saves the character again to fill in its derived stats, so
 * `updated_at` has to be forced afterwards rather than passed in.
 *
 * @param array<int, Game>     $games
 * @param array<string, mixed> $attributes
 */
function characterInGames(User $user, string $editedAt, array $games, array $attributes = []): Character
{
    $character = Character::factory()->create([
        ...$attributes,
        'user_id'  => $user->id,
        'group_id' => $user->group_id,
    ]);

    $character->games()->sync(collect($games)->pluck('id')->all());

    DB::table('characters')->where('id', $character->id)->update(['updated_at' => $editedAt]);

    return $character;
}

beforeEach(function () {
    $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);

    $this->group  = Group::factory()->create();
    $this->active = $this->group->startGame('The Haunting');
    $this->past   = $this->group->startGame('The Lightless Beacon');

    $this->player = User::factory()->create(['group_id' => $this->group->id]);
    $this->player->assignRole('player');
});

test('a player lands on the character they last edited in the active game', function () {
    characterInGames($this->player, '2026-03-01 00:00:00', [$this->past]);
    $latest = characterInGames($this->player, '2026-02-01 00:00:00', [$this->active]);
    characterInGames($this->player, '2026-01-01 00:00:00', [$this->active]);

    $this->actingAs($this->player)
        ->get(route('home'))
        ->assertRedirect(route('character.show', $latest->slug));
});

test('a player whose characters are all in past games is sent to the wizard', function () {
    characterInGames($this->player, '2026-03-01 00:00:00', [$this->past]);
    characterInGames($this->player, '2026-02-01 00:00:00', [$this->past]);

    $this->actingAs($this->player)
        ->get(route('home'))
        ->assertRedirect(route('character.create'));
});

test('a player with a character in no game at all is sent to the wizard', function () {
    characterInGames($this->player, '2026-03-01 00:00:00', []);

    $this->actingAs($this->player)
        ->get(route('home'))
        ->assertRedirect(route('character.create'));
});

test('a player with no character at all is sent to the wizard', function () {
    $this->actingAs($this->player)
        ->get(route('home'))
        ->assertRedirect(route('character.create'));
});

test('a character in both a past and the active game still counts as active', function () {
    $both = characterInGames($this->player, '2026-01-01 00:00:00', [$this->past, $this->active]);

    $this->actingAs($this->player)
        ->get(route('home'))
        ->assertRedirect(route('character.show', $both->slug));
});

test('an unfinished draft in the active game goes back to the wizard, not a sheet', function () {
    characterInGames($this->player, '2026-01-01 00:00:00', [$this->active]);
    characterInGames($this->player, '2026-06-01 00:00:00', [$this->active], [
        'status' => CharacterStatus::Draft,
    ]);

    $this->actingAs($this->player)
        ->get(route('home'))
        ->assertRedirect(route('character.create'));
});

test('a draft left in a past game is skipped for a live sheet', function () {
    $live = characterInGames($this->player, '2026-01-01 00:00:00', [$this->active]);
    characterInGames($this->player, '2026-06-01 00:00:00', [$this->past], [
        'status' => CharacterStatus::Draft,
    ]);

    $this->actingAs($this->player)
        ->get(route('home'))
        ->assertRedirect(route('character.show', $live->slug));
});

test('switching the active game moves where the player lands', function () {
    $forHaunting = characterInGames($this->player, '2026-01-01 00:00:00', [$this->active]);
    $forBeacon   = characterInGames($this->player, '2026-02-01 00:00:00', [$this->past]);

    $this->actingAs($this->player)
        ->get(route('home'))
        ->assertRedirect(route('character.show', $forHaunting->slug));

    $this->past->activate();

    $this->actingAs($this->player)
        ->get(route('home'))
        ->assertRedirect(route('character.show', $forBeacon->slug));
});

test('another player\'s character in the active game is never landed on', function () {
    $mine = characterInGames($this->player, '2026-01-01 00:00:00', [$this->active]);

    $other = User::factory()->create(['group_id' => $this->group->id]);
    $other->assignRole('player');
    characterInGames($other, '2026-06-01 00:00:00', [$this->active]);

    $this->actingAs($this->player)
        ->get(route('home'))
        ->assertRedirect(route('character.show', $mine->slug));
});

test('a deleted character is not landed on', function () {
    $kept = characterInGames($this->player, '2026-01-01 00:00:00', [$this->active]);

    characterInGames($this->player, '2026-06-01 00:00:00', [$this->active])->delete();

    $this->actingAs($this->player)
        ->get(route('home'))
        ->assertRedirect(route('character.show', $kept->slug));
});

test('a keeper or admin who does not play lands on the dashboard', function (string $role) {
    $user = User::factory()->create(['group_id' => $this->group->id]);
    $user->assignRole($role);

    $this->actingAs($user)
        ->get(route('home'))
        ->assertRedirect(route('dashboard'));
})->with(['keeper', 'admin']);

test('a keeper who also plays is sent to the wizard, not the dashboard', function () {
    $keeper = User::factory()->create(['group_id' => $this->group->id]);
    $keeper->assignRole('keeper');
    $keeper->assignRole('player');

    characterInGames($keeper, '2026-01-01 00:00:00', [$this->past]);

    $this->actingAs($keeper)
        ->get(route('home'))
        ->assertRedirect(route('character.create'));
});

test('a keeper who plays still lands on their own live sheet', function () {
    $keeper = User::factory()->create(['group_id' => $this->group->id]);
    $keeper->assignRole('keeper');
    $keeper->assignRole('player');

    $sheet = characterInGames($keeper, '2026-01-01 00:00:00', [$this->active]);

    $this->actingAs($keeper)
        ->get(route('home'))
        ->assertRedirect(route('character.show', $sheet->slug));
});

test('a group with no active game at all sends its players to the wizard', function () {
    characterInGames($this->player, '2026-01-01 00:00:00', [$this->active]);

    $this->group->update(['active_game_id' => null]);

    $this->actingAs($this->player)
        ->get(route('home'))
        ->assertRedirect(route('character.create'));
});

test('guests are sent to the login screen', function () {
    $this->get(route('home'))->assertRedirect(route('login'));
});
