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
});

test('a player creates a skill and it lands on their sheet', function () {
    $response = $this->actingAs($this->user)->post(route('skill.store'), [
        'display_name'   => 'Lore (Deep Ones)',
        'description'    => 'What the village will not say aloud.',
        'starting_value' => 1,
        'value_obtained' => 35,
        'character_id'   => $this->character->id,
    ]);

    $response->assertRedirect(route('character.show', $this->character->slug));

    $skill = Skill::where('display_name', 'Lore (Deep Ones)')->sole();

    expect($skill->slug)->toBe('lore-deep-ones')
        ->and($skill->starting_value)->toBe(1)
        ->and($skill->order_by)->toBeGreaterThan(0);

    $this->assertDatabaseHas('character_skill', [
        'character_id' => $this->character->id,
        'skill_id'     => $skill->id,
        'value'        => 35,
    ]);
});

test('a skill without a value starts at its base value', function () {
    $this->actingAs($this->user)->post(route('skill.store'), [
        'display_name'   => 'Dreaming',
        'starting_value' => 12,
        'character_id'   => $this->character->id,
    ]);

    $this->assertDatabaseHas('character_skill', [
        'character_id' => $this->character->id,
        'skill_id'     => Skill::where('slug', 'dreaming')->sole()->id,
        'value'        => 12,
    ]);
});

test('a retired skill still blocks its name', function () {
    $skill = Skill::factory()->create(['display_name' => 'Dreaming', 'slug' => 'dreaming']);
    $skill->delete();

    $response = $this->actingAs($this->user)->post(route('skill.store'), [
        'display_name'   => 'Dreaming',
        'starting_value' => 5,
        'character_id'   => $this->character->id,
    ]);

    $response->assertSessionHasErrors('display_name');
    expect(Skill::withTrashed()->where('slug', 'dreaming')->count())->toBe(1);
});

test('a player cannot create a skill on somebody else\'s sheet', function () {
    $other = Character::factory()->create(['user_id' => User::factory()->create()->id]);

    $response = $this->actingAs($this->user)->post(route('skill.store'), [
        'display_name'   => 'Dreaming',
        'starting_value' => 5,
        'character_id'   => $other->id,
    ]);

    $response->assertForbidden();
    $this->assertDatabaseMissing('skills', ['slug' => 'dreaming']);
});
