<?php

use App\Models\Character;
use App\Models\Characteristic;
use App\Models\Skill;
use App\Models\Weapon;

beforeEach(function () {
    $this->seed();
});

test('create character', function () {
    $character = Character::factory()->create();

    expect($character)->toBeInstanceOf(Character::class);
});

test('create character with empty name', function () {
    $character = Character::factory()->create(['name' => '']);

    expect($character)->toBeInstanceOf(Character::class);
});

test('attach skills to a character', function () {
    $character = Character::factory()->create();
    $skills = Skill::all();
    expect($character->skills->first())->toBeInstanceOf(Skill::class)
        ->and($character->skills->count())->toEqual($skills->count())
        ->and($skills->reject(fn(Skill $skill) => $character->whereRelation('skills', 'slug', '=', $skill->slug)->exists()))->isEmpty();
});

test('attach weapon to a character', function () {
    $character = Character::factory()->create();
    $weapon = Weapon::find(1);

    $character->weapons()->attach($weapon);

    expect($character->weapons->count())->toEqual(1)
        ->and($character->weapons->first())->toBeInstanceOf(Weapon::class);
});

test('append skills to a character', function () {
    $character = Character::factory()->create();
    $before = $character->skills->count();
    Skill::create([
        'slug' => 'test',
        'display_name' => 'test',
        'description' => 'test',
        'starting_value' => 2
    ]);

    $character->appendSkills();
    $character->refresh();

    $after = $character->skills->count();
    expect($after)->toEqual($before + 1);
});
