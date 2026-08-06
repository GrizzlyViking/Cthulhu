<?php

use App\Misc\CharacterSheet;
use App\Models\Character;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
    $this->user = User::factory()->create();
    $this->user->assignRole('player');
});

test('guests cannot reach the printable sheet', function () {
    $character = Character::factory()->create(['user_id' => $this->user->id]);

    $this->get(route('character.sheet', $character))->assertRedirect(route('login'));
});

test('the printable sheet renders the investigator and their skills', function () {
    $character = Character::factory()->create([
        'user_id'      => $this->user->id,
        'name'         => 'Henrietta Vance',
        'occupation'   => 'Antiquarian',
        'strength'     => 70,
        'dexterity'    => 65,
        'constitution' => 75,
        'size'         => 70,
    ]);

    $skill = Skill::create(['slug' => 'spot_hidden', 'display_name' => 'Spot_hidden', 'starting_value' => 25]);
    $character->skills()->attach($skill, ['value' => 48, 'order' => 1, 'show' => true]);

    $response = $this->actingAs($this->user)->get(route('character.sheet', $character));

    $response->assertOk()
        ->assertViewIs('character.sheet')
        ->assertSee('Henrietta Vance')
        ->assertSee('Antiquarian')
        ->assertSee('Spot Hidden')   // underscores tidied for print
        ->assertSee('>48<', false)   // regular value
        ->assertSee('>24<', false)   // half
        ->assertSee('>9<', false);   // fifth
});

test('skills hidden on the web sheet stay off the printed sheet', function () {
    $character = Character::factory()->create(['user_id' => $this->user->id]);
    $hidden    = Skill::create(['slug' => 'cthulhu_mythos', 'display_name' => 'Cthulhu_mythos', 'starting_value' => 0]);
    $character->skills()->attach($hidden, ['value' => 12, 'order' => 1, 'show' => false]);

    $this->actingAs($this->user)
        ->get(route('character.sheet', $character))
        ->assertOk()
        ->assertDontSee('Cthulhu Mythos');
});

test('dodge falls back to half DEX when the skill was never bought', function () {
    $character = Character::factory()->create(['dexterity' => 65]);

    expect(CharacterSheet::dodge($character))->toBe(32);
});

test('credit rating maps onto the 1920s wealth bands', function (int $creditRating, string $level, string $spending) {
    $wealth = CharacterSheet::wealth($creditRating);

    expect($wealth['level'])->toBe($level)
        ->and($wealth['spending'])->toBe($spending);
})->with([
    [0, 'Penniless', '$0.50'],
    [5, 'Poor', '$2'],
    [30, 'Average', '$10'],
    [70, 'Wealthy', '$50'],
    [95, 'Rich', '$250'],
    [99, 'Super Rich', '$5,000'],
]);
