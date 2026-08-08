<?php

use App\Models\Character;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);

    $this->user = User::factory()->create();
    $this->user->assignRole('player');

    $this->character = Character::factory()->create(['user_id' => $this->user->id]);

    $this->skill = Skill::create([
        'slug'           => 'language-gaelic',
        'display_name'   => 'Language (Gaelic)',
        'starting_value' => 1,
    ]);

    $this->character->skills()->attach($this->skill, ['value' => 21, 'order' => 1, 'show' => true]);
});

/** @param  'increment'|'decrement'|'reset'  $action */
function check(string $action): Illuminate\Testing\TestResponse
{
    return test()->actingAs(test()->user)->post(route("experience.{$action}", [
        'character' => test()->character->slug,
        'skill'     => test()->skill->slug,
    ]));
}

test('a successful roll adds a mark and the sheet is told the new figure', function () {
    check('increment')->assertOk()->assertJson(['experience' => 1]);
    check('increment')->assertOk()->assertJson(['experience' => 2]);

    $this->assertDatabaseHas('character_skill', [
        'character_id' => $this->character->id,
        'skill_id'     => $this->skill->id,
        'experience'   => 2,
    ]);
});

test('a mark can be taken back', function () {
    check('increment');

    check('decrement')->assertOk()->assertJson(['experience' => 0]);
});

test('marks never fall below zero', function () {
    check('decrement')->assertOk()->assertJson(['experience' => 0]);

    $this->assertDatabaseHas('character_skill', [
        'character_id' => $this->character->id,
        'skill_id'     => $this->skill->id,
        'experience'   => 0,
    ]);
});

test('rolling the improvement clears the marks', function () {
    check('increment');
    check('increment');

    check('reset')->assertOk()->assertJson(['experience' => 0]);
});

test('a skill that is not on the sheet has no marks to count', function () {
    $other = Skill::create(['slug' => 'occult', 'display_name' => 'Occult', 'starting_value' => 5]);

    $this->actingAs($this->user)->post(route('experience.increment', [
        'character' => $this->character->slug,
        'skill'     => $other->slug,
    ]))->assertNotFound();
});

test('another player cannot mark up a sheet that is not theirs', function () {
    $intruder = User::factory()->create();
    $intruder->assignRole('player');

    $this->actingAs($intruder)->post(route('experience.increment', [
        'character' => $this->character->slug,
        'skill'     => $this->skill->slug,
    ]))->assertForbidden();

    $this->assertDatabaseHas('character_skill', [
        'character_id' => $this->character->id,
        'skill_id'     => $this->skill->id,
        'experience'   => 0,
    ]);
});
