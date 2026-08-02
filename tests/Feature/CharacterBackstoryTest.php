<?php

use App\Enums\CharacterStatus;
use App\Models\Character;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

function completedCharacter(User $user, array $attributes = []): Character
{
    return Character::factory()->create([
        'user_id' => $user->id,
        'status'  => CharacterStatus::Complete,
        ...$attributes,
    ]);
}

test('the owner can update the backstory of a completed character', function () {
    $character = completedCharacter($this->user);

    expect($character->status)->toBe(CharacterStatus::Complete);

    $this->put(route('character.backstory.update', $character->slug), [
        'personal_description' => 'A wiry, chain-smoking reporter.',
        'key_connection'       => 'Significant People — My beloved wife Annabel.',
    ])->assertRedirect(route('character.show', $character->slug));

    expect($character->refresh()->backstory)->toBe([
        'personal_description' => 'A wiry, chain-smoking reporter.',
        'key_connection'       => 'Significant People — My beloved wife Annabel.',
    ]);
});

test('updating one key preserves the other backstory entries', function () {
    $character = completedCharacter($this->user, [
        'backstory' => [
            'ideology' => 'The truth must out.',
            'traits'   => 'Relentless',
            'gear'     => 'Notebook, camera',
        ],
    ]);

    $this->put(route('character.backstory.update', $character->slug), [
        'injuries_scars' => 'Three claw marks across the left shoulder.',
        'traits'         => 'Relentless, now paranoid',
    ])->assertRedirect(route('character.show', $character->slug));

    expect($character->refresh()->backstory)->toBe([
        'ideology'       => 'The truth must out.',
        'traits'         => 'Relentless, now paranoid',
        'gear'           => 'Notebook, camera',
        'injuries_scars' => 'Three claw marks across the left shoulder.',
    ]);
});

test('a submitted null clears a single entry without touching the rest', function () {
    $character = completedCharacter($this->user, [
        'backstory' => [
            'ideology' => 'The truth must out.',
            'gear'     => 'Notebook, camera',
        ],
    ]);

    $this->put(route('character.backstory.update', $character->slug), [
        'gear' => null,
    ])->assertRedirect(route('character.show', $character->slug));

    expect($character->refresh()->backstory)->toBe([
        'ideology' => 'The truth must out.',
        'gear'     => null,
    ]);
});

test('another player cannot update someone elses backstory', function () {
    $character = completedCharacter($this->user, [
        'backstory' => ['ideology' => 'The truth must out.'],
    ]);

    $this->actingAs(User::factory()->create())
        ->put(route('character.backstory.update', $character->slug), [
            'ideology' => 'Vandalised.',
        ])->assertForbidden();

    expect($character->refresh()->backstory)->toBe(['ideology' => 'The truth must out.']);
});

test('guests are redirected to login', function () {
    $character = completedCharacter($this->user);

    auth()->logout();

    $this->put(route('character.backstory.update', $character->slug), [
        'ideology' => 'The truth must out.',
    ])->assertRedirect(route('login'));
});

test('entries longer than 2000 characters are rejected', function (string $field) {
    $character = completedCharacter($this->user);

    $this->put(route('character.backstory.update', $character->slug), [
        $field => str_repeat('a', 2001),
    ])->assertSessionHasErrors($field);

    expect($character->refresh()->backstory)->toBeNull();
})->with(['personal_description', 'key_connection', 'gear']);
