<?php

use App\Enums\Era;
use App\Misc\CharacterSheet;
use App\Misc\Wealth;
use App\Models\Character;
use App\Models\EquipmentItem;
use App\Models\Skill;
use App\Models\StorageLocation;
use App\Models\User;
use App\Models\Weapon;
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

test('the damage bonus is worked into the damage rather than left as “DB”', function (string $printed, string $bonus, string $expected) {
    expect(CharacterSheet::damage($printed, $bonus))->toBe($expected);
})->with([
    ['1D3+DB', '+1D4', '1D3 + 1D4'],
    ['1D3+half DB', '+1D4', '1D3 + half 1D4'],
    ['1D8+DB', '-1', '1D8 -1'],
    // Nothing to add takes the clause away rather than printing a bare plus.
    ['1D3+DB', 'none', '1D3'],
    ['1D3+half DB', 'none', '1D3'],
    // A weapon whose damage never mentioned the bonus is left alone.
    ['1D10+2', '+1D6', '1D10+2'],
]);

test('the built-in unarmed row gives way to the weapon of the same name', function () {
    $character = Character::factory()->create(['user_id' => $this->user->id]);
    $brawl     = Weapon::factory()->create(['name' => 'Brawl (Unarmed)', 'skill' => 'fighting-brawl', 'damage' => '1D3+DB']);

    expect(CharacterSheet::carriesUnarmed($character))->toBeFalse();

    $character->weapons()->attach($brawl->id, ['ammo' => 0, 'ammo_reserve' => 0]);

    expect(CharacterSheet::carriesUnarmed($character->fresh()))->toBeTrue();

    $this->actingAs($this->user)
        ->get(route('character.sheet', $character->fresh()))
        ->assertOk()
        ->assertSee('Brawl (Unarmed)')
        // The fallback row above it is gone, rather than saying the same thing
        // twice in the same table.
        ->assertDontSee('Unarmed (brawl)');
});

test('an avatar whose file has gone prints the empty frame, not a broken image', function () {
    $character = Character::factory()->create([
        'user_id' => $this->user->id,
        'avatar'  => 'avatars/nobody/missing.jpg',
    ]);

    $this->actingAs($this->user)
        ->get(route('character.sheet', $character))
        ->assertOk()
        ->assertDontSee('missing.jpg')
        ->assertSee('Portrait');
});

test('the printed sheet lists what is owned under where it is kept', function () {
    $character = Character::factory()->create(['user_id' => $this->user->id]);

    // The four starting places are laid down by migration, not by this test.
    $pocket = StorageLocation::where('slug', 'on-person')->firstOrFail();
    $chest  = StorageLocation::where('slug', 'travel-chest')->firstOrFail();

    $lantern = EquipmentItem::create(['slug' => 'lantern', 'name' => 'Bullseye Lantern', 'section' => 'Tools', 'cost' => '$1.98']);
    $rope    = EquipmentItem::create(['slug' => 'rope', 'name' => 'Rope, 50 feet', 'section' => 'Tools', 'cost' => '$2.25']);

    $character->equipment()->attach($lantern->id, ['storage_location_id' => $pocket->id, 'quantity' => 1]);
    $character->equipment()->attach($rope->id, ['storage_location_id' => $chest->id, 'quantity' => 2]);

    $this->actingAs($this->user)
        ->get(route('character.sheet', $character))
        ->assertOk()
        ->assertSee('On person')
        ->assertSee('Bullseye Lantern')
        ->assertSee('Travel chest')
        ->assertSee('Rope, 50 feet')
        ->assertSee('×2');
});

test('the printed sheet shows what the investigator has to spend', function () {
    $character = Character::factory()->create(['user_id' => $this->user->id, 'cash' => 42.5, 'assets' => 1200]);

    $this->actingAs($this->user)
        ->get(route('character.sheet', $character))
        ->assertOk()
        ->assertSee('$42.50')
        ->assertSee('$1,200');
});

test('credit rating maps onto the 1920s wealth bands', function (int $creditRating, string $level, float $spending) {
    $wealth = Wealth::for($creditRating, Era::Twenties);

    expect($wealth['living_standard'])->toBe($level)
        ->and($wealth['spending_level'])->toBe($spending);
})->with([
    [0, 'Penniless', 0.5],
    [5, 'Poor', 2.0],
    [30, 'Average', 10.0],
    [70, 'Wealthy', 50.0],
    [95, 'Rich', 250.0],
    [99, 'Super Rich', 5000.0],
]);
