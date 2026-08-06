<?php

use App\Models\Character;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
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
        ->put(route('character.update', $character->slug), [
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

test('the sheet carries the always-relevant skill slugs and each skill its starting value', function () {
    config()->set('cthulhu.sheet.always_relevant_skills', ['dodge', 'spot-hidden']);

    $this->user->assignRole('player');
    $character = Character::factory()->create(['user_id' => $this->user->id]);
    $skill     = Skill::factory()->create(['slug' => 'dodge', 'starting_value' => 30]);
    $character->skills()->attach($skill, ['value' => 30, 'order' => 1]);

    $this->actingAs($this->user)
        ->get(route('character.show', $character))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Character')
            ->where('alwaysRelevantSkills', ['dodge', 'spot-hidden'])
            // The sheet filter compares each pivot value against the book's
            // starting value, so that has to travel with the skill.
            ->where('character.skills', fn (Collection $skills) => $skills
                ->firstWhere('slug', 'dodge')['starting_value'] === 30
            )
        );
});
