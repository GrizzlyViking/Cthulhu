<?php

use App\Models\Character;
use App\Models\User;

beforeEach(function () {
    $this->seed();
});

test('create character using /create', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/create/step/first', [
        'name'       => ($name = fake()->name),
        'user_id'    => $user->id,
        'occupation' => fake()->word,
        'age'        => 100,
        'gender'     => 'Male',
        'residence'  => fake()->word,
        'birthplace' => fake()->word,
    ]);

    $response->assertStatus(302);

    $character = \App\Models\Character::where('name', $name)->firstOrFail();
    expect($character)->toBeInstanceOf(Character::class)
        ->and($character->name)->toBe($name)
        ->and($character->user_id)->toBe($user->id)
        ->and($character->skills)->toHaveCount(\App\Models\Skill::all()->count());
});
