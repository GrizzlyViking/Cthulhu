<?php

use App\Misc\WeaponTable;
use App\Models\Character;
use App\Models\User;
use App\Models\Weapon;
use Illuminate\Support\Facades\DB;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->seed();

    $this->user      = User::factory()->create();
    $this->character = Character::factory()->create(['user_id' => $this->user->id]);
});

/**
 * Equip a weapon by name and hand back the pivot id.
 */
function equip(Character $character, string $name): array
{
    $weapon = Weapon::where('name', $name)->firstOrFail();

    $character->weapons()->attach($weapon->id, [
        'ammo'         => $weapon->magazine_capacity ?? 0,
        'ammo_reserve' => 0,
    ]);

    return [$weapon, DB::table('equipables')->latest('id')->first()->id];
}

test('attach a weapon to a character', function () {
    [$revolver] = equip($this->character, '.45 Revolver');

    expect($this->character->weapons)->toHaveCount(1)
        ->and($this->character->weapons->first())->toBeInstanceOf(Weapon::class)
        ->and($this->character->weapons->first()->name)->toBe($revolver->name);

    equip($this->character, '12-gauge Shotgun (2B)');

    expect($this->character->weapons()->get())->toHaveCount(2);
});

test('equipping a firearm loads a full magazine', function () {
    $weapon = Weapon::where('name', '.45 Automatic')->firstOrFail();

    actingAs($this->user)
        ->post(route('equip.weapon', ['character' => $this->character->slug]), ['weapon_id' => $weapon->id])
        ->assertRedirect();

    $equipped = $this->character->weapons()->first();

    expect($equipped->pivot->ammo)->toBe(7)
        ->and($equipped->pivot->ammo_reserve)->toBe(0);
});

test('firing spends a round from the magazine', function () {
    [, $pivotId] = equip($this->character, '.45 Automatic');

    actingAs($this->user)
        ->post(route('fire.weapon', ['character' => $this->character->slug, 'equipable' => $pivotId]))
        ->assertOk()
        ->assertJson(['ammo' => 6, 'ammo_reserve' => 0]);

    expect(DB::table('equipables')->find($pivotId)->ammo)->toBe(6);
});

test('firing an empty magazine is refused', function () {
    [, $pivotId] = equip($this->character, '.45 Automatic');

    DB::table('equipables')->where('id', $pivotId)->update(['ammo' => 0]);

    actingAs($this->user)
        ->post(route('fire.weapon', ['character' => $this->character->slug, 'equipable' => $pivotId]))
        ->assertStatus(422);

    expect(DB::table('equipables')->find($pivotId)->ammo)->toBe(0);
});

test('reloading moves carried rounds into the magazine', function () {
    [, $pivotId] = equip($this->character, '.45 Automatic');

    DB::table('equipables')->where('id', $pivotId)->update(['ammo' => 2, 'ammo_reserve' => 20]);

    actingAs($this->user)
        ->post(route('reload.weapon', ['character' => $this->character->slug, 'equipable' => $pivotId]))
        ->assertOk()
        // Five rounds fill the seven-round magazine, leaving fifteen carried.
        ->assertJson(['ammo' => 7, 'ammo_reserve' => 15]);
});

test('reloading takes only what is carried', function () {
    [, $pivotId] = equip($this->character, '.45 Automatic');

    DB::table('equipables')->where('id', $pivotId)->update(['ammo' => 0, 'ammo_reserve' => 3]);

    actingAs($this->user)
        ->post(route('reload.weapon', ['character' => $this->character->slug, 'equipable' => $pivotId]))
        ->assertJson(['ammo' => 3, 'ammo_reserve' => 0]);
});

test('reloading with nothing carried is refused', function () {
    [, $pivotId] = equip($this->character, '.45 Automatic');

    DB::table('equipables')->where('id', $pivotId)->update(['ammo' => 0, 'ammo_reserve' => 0]);

    actingAs($this->user)
        ->post(route('reload.weapon', ['character' => $this->character->slug, 'equipable' => $pivotId]))
        ->assertStatus(422);
});

test('the rounds carried can be set by hand', function () {
    [, $pivotId] = equip($this->character, '.45 Automatic');

    actingAs($this->user)
        ->put(route('weapon.ammo.update', ['character' => $this->character->slug, 'equipable' => $pivotId]), [
            'ammo_reserve' => 48,
        ])
        ->assertOk()
        ->assertJson(['ammo' => 7, 'ammo_reserve' => 48]);
});

test('the magazine cannot be set above its capacity', function () {
    [, $pivotId] = equip($this->character, '.45 Automatic');

    actingAs($this->user)
        ->put(route('weapon.ammo.update', ['character' => $this->character->slug, 'equipable' => $pivotId]), [
            'ammo' => 99,
        ])
        ->assertJson(['ammo' => 7]);
});

test('a weapon on someone else sheet cannot be fired', function () {
    [, $pivotId] = equip($this->character, '.45 Automatic');

    $intruder = User::factory()->create();

    actingAs($intruder)
        ->post(route('fire.weapon', ['character' => $this->character->slug, 'equipable' => $pivotId]))
        ->assertForbidden();

    expect(DB::table('equipables')->find($pivotId)->ammo)->toBe(7);
});

test('removing a weapon takes it off the sheet', function () {
    [, $pivotId] = equip($this->character, '.45 Automatic');

    actingAs($this->user)
        ->delete(route('remove.weapon', ['character' => $this->character->slug, 'equipable' => $pivotId]))
        ->assertRedirect();

    expect($this->character->weapons()->count())->toBe(0);
});

test('the whole handbook weapons table is seeded', function () {
    expect(Weapon::count())->toBe(count(WeaponTable::all()) + 1);

    foreach (WeaponTable::all() as $weapon) {
        expect(Weapon::where('name', $weapon['name'])->exists())
            ->toBeTrue("{$weapon['name']} is missing from the weapons table");
    }

    // Every weapon points at a skill that actually exists.
    expect(Weapon::whereNotIn('skill', DB::table('skills')->pluck('slug'))->pluck('name')->all())
        ->toBeEmpty();
});

test('magazine capacity is read out of the printed ammunition column', function () {
    expect(Weapon::where('name', '.45 Automatic')->first()->magazine_capacity)->toBe(7)
        // A choice of magazines takes the first one printed.
        ->and(Weapon::where('name', 'Bergmann MP18I/MP28II')->first()->magazine_capacity)->toBe(20)
        ->and(Weapon::where('name', 'Mace Spray')->first()->magazine_capacity)->toBe(25)
        ->and(Weapon::where('name', 'Shuriken')->first()->magazine_capacity)->toBe(1)
        // Nothing countable: melee weapons and crew-served guns.
        ->and(Weapon::where('name', 'Brass Knuckles')->first()->magazine_capacity)->toBeNull()
        ->and(Weapon::where('name', '81mm Mortar')->first()->magazine_capacity)->toBeNull();
});
