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

/*
 * The Background panel edits in place through the same route as the vitals, but
 * none of its five fields were on the allow-list, so every one of them answered
 * 422 and the sheet — which never looks at the response — showed nothing.
 */
test('the background fields save', function (string $attribute, mixed $value) {
    $character = Character::factory()->create(['user_id' => $this->user->id]);

    $this->put(route('attribute.update', $character->slug), [
        'attribute' => $attribute,
        'value'     => $value,
    ])->assertRedirect(route('character.show', $character->slug));

    expect($character->refresh()->{$attribute})->toBe($value);
})->with([
    'age'        => ['age', 42],
    'gender'     => ['gender', 'Other'],
    'occupation' => ['occupation', 'Antiquarian'],
    'residence'  => ['residence', 'Arkham, Massachusetts'],
    'birthplace' => ['birthplace', 'Boston, Massachusetts'],
]);

test('an age outside the range the book plays in is refused', function () {
    $character = Character::factory()->create(['user_id' => $this->user->id]);

    $this->put(route('attribute.update', $character->slug), [
        'attribute' => 'age',
        'value'     => 7,
    ])->assertSessionHasErrors('value');
});

/*
 * The column is an enum with a check constraint behind it, so anything but a
 * case name has to be turned away here — the database would refuse it anyway,
 * but as a 500 rather than a validation error.
 */
test('a gender the enum does not have is refused', function () {
    $character = Character::factory()->create(['user_id' => $this->user->id]);

    $this->put(route('attribute.update', $character->slug), [
        'attribute' => 'gender',
        'value'     => 'Wensleydale',
    ])->assertSessionHasErrors('value');
});

test('an attribute nobody may set this way is still refused', function () {
    $character = Character::factory()->create(['user_id' => $this->user->id]);

    $this->put(route('attribute.update', $character->slug), [
        'attribute' => 'user_id',
        'value'     => 999,
    ])->assertSessionHasErrors('attribute');
});
