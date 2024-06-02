<?php

use App\Models\Character;
use App\Models\Weapon;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->seed();
});

test('attach a weapon to a character', function () {
    $character = Character::factory()->create();
    $weapon = Weapon::find(27);

    $character->weapons()->attach($weapon);

    expect($character->weapons)->toHaveCount(1)
        ->and($character->weapons->first())->toBeInstanceOf(Weapon::class);

    $weapon2 = Weapon::find(26);

    $character->weapons()->attach($weapon2);
    expect($character->weapons()->get())->toHaveCount(2);
});

test('reload weapon', function () {
    $user = \App\Models\User::factory()->create();
    $character = Character::factory()->create(['user_id' => $user->id]);
    $weapon = Weapon::find(27);

    $character->weapons()->attach($weapon);

    actingAs($user)->post(route('reload.weapon', ['character' => $character->slug]), [
        'pivot_id' => $character->weapons()->first()->pivot->id,
        'ammo' => $weapon->bullet_in_mag
    ]);

    expect($character->weapons()->get())->toHaveCount(1);
    expect($character->weapons()->first()->pivot->ammo)->toEqual($weapon->bullet_in_mag);
});
