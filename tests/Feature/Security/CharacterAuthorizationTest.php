<?php

use App\Models\Character;
use App\Models\Group;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

test('a player cannot edit another players character via patch', function () {
    $player1 = User::factory()->create();
    $player1->assignRole('player');

    $player2 = User::factory()->create();
    $player2->assignRole('player');

    $characterOfPlayer2 = Character::factory()->create(['user_id' => $player2->id]);

    // Player 1 tries to patch Player 2's character
    $response = $this->actingAs($player1)
        ->put(route('character.update', $characterOfPlayer2), [
            'name' => 'Hacked Name',
        ]);

    $response->assertForbidden();
});

test('a player can edit their own character via patch', function () {
    $player = User::factory()->create();
    $player->assignRole('player');

    $character = Character::factory()->create(['user_id' => $player->id]);

    $response = $this->actingAs($player)
        ->put(route('character.update', $character), [
            'name' => 'New Valid Name',
        ]);

    $response->assertRedirect();
    expect($character->fresh()->name)->toBe('New Valid Name');
});

test('a keeper can edit any character of their own group via patch', function () {
    $group = Group::factory()->create();

    $keeper = User::factory()->inGroup($group)->create();
    $keeper->assignRole('keeper');

    $player    = User::factory()->inGroup($group)->create();
    $character = Character::factory()->create(['user_id' => $player->id, 'group_id' => $group->id]);

    $response = $this->actingAs($keeper)
        ->put(route('character.update', $character), [
            'name' => 'Keeper Edited Name',
        ]);

    $response->assertRedirect();
    expect($character->fresh()->name)->toBe('Keeper Edited Name');
});

test('a keeper cannot edit a character of another group via patch', function () {
    $keeper = User::factory()->inGroup()->create();
    $keeper->assignRole('keeper');

    $player    = User::factory()->inGroup()->create();
    $character = Character::factory()->create(['user_id' => $player->id, 'group_id' => $player->group_id]);

    $response = $this->actingAs($keeper)
        ->put(route('character.update', $character), [
            'name' => 'Hacked Name',
        ]);

    $response->assertForbidden();
});
