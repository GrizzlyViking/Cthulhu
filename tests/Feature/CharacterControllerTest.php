<?php

use App\Models\Character;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\SkillSeeder::class);
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('can view character show page', function () {
    $character = Character::factory()->create(['user_id' => $this->user->id]);

    $response = $this->get(route('character.show', $character->slug));

    $response->assertStatus(200);
});

test('can update character skill', function () {
    $character = Character::factory()->create(['user_id' => $this->user->id]);
    $skill     = Skill::first();

    $response = $this->put(route('character.skill.update', [
        'character' => $character->slug,
        'skill'     => $skill->slug,
    ]), [
        'value' => 50,
        'show'  => false,
    ]);

    $response->assertRedirect(route('character.show', $character->slug));
    $pivot = $character->refresh()->skills()->where('skill_id', $skill->id)->first()->pivot;
    expect($pivot->value)->toBe(50);
    expect($pivot->show)->toBeFalsy();
});

test('can update character attribute', function () {
    $character = Character::factory()->create(['user_id' => $this->user->id]);

    $response = $this->put(route('attribute.update', $character->slug), [
        'attribute' => 'hit_points',
        'value'     => 10,
    ]);

    $response->assertRedirect(route('character.show', $character->slug));
    expect($character->refresh()->hit_points)->toBe(10);
});

test('cannot update someone elses character', function () {
    $otherUser = User::factory()->create();
    $character = Character::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->put(route('attribute.update', $character->slug), [
        'attribute' => 'hit_points',
        'value'     => 10,
    ]);

    $response->assertForbidden();
});

test('can delete own character', function () {
    $character = Character::factory()->create(['user_id' => $this->user->id]);

    $response = $this->delete(route('character.destroy', $character->slug));

    $response->assertRedirect(route('dashboard'));
    $this->assertSoftDeleted($character);
});
