<?php

use App\Models\Character;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RoleSeeder::class);
    $this->user = User::factory()->create();
});

test('users without edit permission cannot update character', function () {
    $this->user->assignRole('player');
    $otherUser = User::factory()->create();
    $character = Character::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($this->user)
        ->put(route('character.update', $character), [
            'name'         => 'New Name',
            'slug'         => 'new-name',
            'strength'     => 50,
            'dexterity'    => 50,
            'intelligence' => 50,
            'constitution' => 50,
            'appearance'   => 50,
            'power'        => 50,
            'size'         => 50,
            'education'    => 50,
        ]);

    $response->assertForbidden();
});

test('users with edit permission can update character', function () {
    $this->user->assignRole('keeper');
    $character = Character::factory()->create(['user_id' => $this->user->id]);

    $response = $this->actingAs($this->user)
        ->put(route('character.update', $character), [
            'name'         => 'New Name',
            'slug'         => 'new-name',
            'strength'     => 50,
            'dexterity'    => 50,
            'intelligence' => 50,
            'constitution' => 50,
            'appearance'   => 50,
            'power'        => 50,
            'size'         => 50,
            'education'    => 50,
        ]);

    $response->assertRedirect();
    expect($character->fresh()->name)->toBe('New Name');
});

test('users can update character notes', function () {
    $this->user->assignRole('player');
    $character = Character::factory()->create(['user_id' => $this->user->id]);

    $response = $this->actingAs($this->user)
        ->patch(route('character.patch', $character->slug), [
            'notes' => '<p>Some notes</p>',
        ]);

    $response->assertRedirect();
    expect($character->fresh()->notes)->toBe('<p>Some notes</p>');
});

test('move rate is calculated correctly', function () {
    $character = Character::factory()->create([
        'strength'  => 40,
        'dexterity' => 40,
        'size'      => 50,
        'age'       => 25,
    ]);

    // STR < SIZ and DEX < SIZ => Move 7
    expect($character->move_rate)->toBe(7);

    $character->update(['strength' => 60, 'dexterity' => 60]);
    // STR > SIZ and DEX > SIZ => Move 9
    expect($character->fresh()->move_rate)->toBe(9);

    $character->update(['age' => 45]);
    // Age 45 => Move - 1 => 8
    expect($character->fresh()->move_rate)->toBe(8);
});
