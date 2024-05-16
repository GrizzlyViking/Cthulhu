<?php

use App\Models\Character;
use App\Models\Weapon;

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
