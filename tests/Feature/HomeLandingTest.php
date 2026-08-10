<?php

use App\Enums\CharacterStatus;
use App\Models\Character;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * The factory saves the character again to fill in its derived stats, so
 * `updated_at` has to be forced afterwards rather than passed in.
 *
 * @param array<string, mixed> $attributes
 */
function characterEditedAt(User $user, string $when, array $attributes = []): Character
{
    $character = Character::factory()->create([...$attributes, 'user_id' => $user->id]);

    DB::table('characters')->where('id', $character->id)->update(['updated_at' => $when]);

    return $character;
}

beforeEach(function () {
    $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
});

test('a player with no character lands in the creation wizard', function () {
    $user = User::factory()->create();
    $user->assignRole('player');

    $this->actingAs($user)
        ->get(route('home'))
        ->assertRedirect(route('character.create'));
});

test('a keeper or admin with no character lands on the dashboard', function (string $role) {
    $user = User::factory()->inGroup()->create();
    $user->assignRole($role);

    $this->actingAs($user)
        ->get(route('home'))
        ->assertRedirect(route('dashboard'));
})->with(['keeper', 'admin']);

test('a keeper with a character still lands on it', function () {
    $user = User::factory()->inGroup()->create();
    $user->assignRole('keeper');

    $character = characterEditedAt($user, '2026-01-01 00:00:00');

    $this->actingAs($user)
        ->get(route('home'))
        ->assertRedirect(route('character.show', $character->slug));
});

test('a keeper part-way through the wizard is sent back to it', function () {
    $user = User::factory()->inGroup()->create();
    $user->assignRole('keeper');

    characterEditedAt($user, '2026-01-01 00:00:00', ['status' => CharacterStatus::Draft]);

    $this->actingAs($user)
        ->get(route('home'))
        ->assertRedirect(route('character.create'));
});

test('a user lands on the character they edited most recently', function () {
    $user = User::factory()->create();

    characterEditedAt($user, '2026-01-01 00:00:00');
    $latest = characterEditedAt($user, '2026-03-01 00:00:00');
    characterEditedAt($user, '2026-02-01 00:00:00');

    $this->actingAs($user)
        ->get(route('home'))
        ->assertRedirect(route('character.show', $latest->slug));
});

test('another player\'s character is never landed on', function () {
    $user  = User::factory()->create();
    $mine  = characterEditedAt($user, '2026-01-01 00:00:00');
    $other = User::factory()->create();

    characterEditedAt($other, '2026-06-01 00:00:00');

    $this->actingAs($user)
        ->get(route('home'))
        ->assertRedirect(route('character.show', $mine->slug));
});

test('an unfinished draft sends the user back to the wizard rather than a sheet', function () {
    $user = User::factory()->create();

    characterEditedAt($user, '2026-01-01 00:00:00');
    characterEditedAt($user, '2026-06-01 00:00:00', ['status' => CharacterStatus::Draft]);

    $this->actingAs($user)
        ->get(route('home'))
        ->assertRedirect(route('character.create'));
});

test('a deleted character is not landed on', function () {
    $user = User::factory()->create();
    $kept = characterEditedAt($user, '2026-01-01 00:00:00');

    characterEditedAt($user, '2026-06-01 00:00:00')->delete();

    $this->actingAs($user)
        ->get(route('home'))
        ->assertRedirect(route('character.show', $kept->slug));
});

test('guests are sent to the login screen', function () {
    $this->get(route('home'))->assertRedirect(route('login'));
});
