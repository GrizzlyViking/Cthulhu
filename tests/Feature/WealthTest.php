<?php

use App\Enums\Era;
use App\Enums\Purse;
use App\Misc\Money;
use App\Models\Character;
use App\Models\EquipmentItem;
use App\Models\Skill;
use App\Models\User;
use App\Models\Weapon;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->withoutMiddleware(VerifyCsrfToken::class);

    $this->owner = User::factory()->inGroup()->create();
    $this->owner->assignRole('player');

    $this->character = Character::factory()->create([
        'user_id'  => $this->owner->id,
        'group_id' => $this->owner->group_id,
    ]);

    // Credit Rating 30 is the Average band: $60 in hand, $1,500 in assets.
    $creditRating = Skill::firstOrCreate(
        ['slug' => 'credit_rating'],
        ['display_name' => 'Credit Rating', 'starting_value' => 0],
    );

    $this->character->skills()->syncWithoutDetaching([
        $creditRating->id => ['value' => 30, 'order' => 1, 'show' => true],
    ]);

    $this->character->refresh();
});

// ---- Reading the book's price cells ------------------------------------

test('a price cell is read as the cheap end of what it names', function (?string $cell, ?float $expected) {
    expect(Money::fromCostCell($cell))->toBe($expected);
})->with([
    ['$18.50', 18.5],
    ['$1,200.00', 1200.0],
    ['5¢-20¢', 0.05],           // cents, and the cheap end of a range
    ['79¢ -$1.25', 0.79],
    ['9¢/lb.', 0.09],           // the slash is a unit here, not an era
    ['$4.25-$28.00', 4.25],
    ['-', null],
    [null, null],
    ['Varies', null],
]);

test('a weapon price cell is the 1920s price and the modern one, split by era', function () {
    expect(Money::fromCostCell('$7/$75', Era::Twenties))->toBe(7.0)
        ->and(Money::fromCostCell('$7/$75', Era::Modern))->toBe(75.0)
        // A dash on one side is a weapon that did not exist then.
        ->and(Money::fromCostCell('-/$300', Era::Twenties))->toBeNull()
        ->and(Money::fromCostCell('-/$300', Era::Modern))->toBe(300.0);
});

// ---- What an investigator has ------------------------------------------

test('wealth follows the credit rating band until something is spent', function () {
    $wealth = $this->character->wealth;

    expect($wealth['living_standard'])->toBe('Average')
        ->and($wealth['cash'])->toBe(60.0)
        ->and($wealth['assets'])->toBe(1500.0)
        ->and($wealth['settled'])->toBeFalse();
});

test('paying settles both purses, so the untouched one stops following the band', function () {
    $this->character->pay(10.0, Purse::Cash);

    $wealth = $this->character->fresh()->wealth;

    expect($wealth['cash'])->toBe(50.0)
        ->and($wealth['assets'])->toBe(1500.0)
        ->and($wealth['settled'])->toBeTrue();
});

test('a purse is allowed to go past zero, because overspending is a fact of play', function () {
    $this->character->pay(100.0, Purse::Cash);

    expect($this->character->fresh()->wealth['cash'])->toBe(-40.0);
});

test('paying out of nothing costs nothing', function () {
    $this->character->pay(100.0, Purse::Nothing);

    expect($this->character->fresh()->wealth['settled'])->toBeFalse();
});

test('a player writes their own figures over the band', function () {
    $this->actingAs($this->owner)
        ->put(route('character.wealth.update', $this->character), ['cash' => 12.75, 'assets' => 0])
        ->assertRedirect();

    $wealth = $this->character->fresh()->wealth;

    expect($wealth['cash'])->toBe(12.75)
        ->and($wealth['assets'])->toBe(0.0);
});

test('nobody else may touch another investigator’s money', function () {
    $stranger = User::factory()->inGroup()->create();
    $stranger->assignRole('player');

    $this->actingAs($stranger)
        ->put(route('character.wealth.update', $this->character), ['cash' => 9999])
        ->assertForbidden();

    expect($this->character->fresh()->cash)->toBeNull();
});

// ---- Buying things ------------------------------------------------------

test('buying equipment takes the price out of the purse that was named', function () {
    $item = EquipmentItem::create(['slug' => 'lantern', 'name' => 'Bullseye Lantern', 'cost' => '$1.98']);

    $this->actingAs($this->owner)
        ->post(route('equipment.store', $this->character), [
            'equipment_item_id' => $item->id,
            'quantity'          => 2,
            'price'             => 3.96,
            'paid_from'         => 'cash',
        ])
        ->assertRedirect();

    expect($this->character->fresh()->wealth['cash'])->toBe(56.04);
});

test('a haggled price is the one that is paid', function () {
    $item = EquipmentItem::create(['slug' => 'lantern', 'name' => 'Bullseye Lantern', 'cost' => '$1.98']);

    $this->actingAs($this->owner)
        ->post(route('equipment.store', $this->character), [
            'equipment_item_id' => $item->id,
            'price'             => 0.5,
            'paid_from'         => 'assets',
        ]);

    $wealth = $this->character->fresh()->wealth;

    expect($wealth['assets'])->toBe(1499.5)
        ->and($wealth['cash'])->toBe(60.0);
});

test('something found on the ground costs nothing at all', function () {
    $item = EquipmentItem::create(['slug' => 'lantern', 'name' => 'Bullseye Lantern', 'cost' => '$1.98']);

    $this->actingAs($this->owner)
        ->post(route('equipment.store', $this->character), [
            'equipment_item_id' => $item->id,
            'price'             => 1.98,
            'paid_from'         => 'nothing',
        ]);

    expect($this->character->fresh()->wealth['settled'])->toBeFalse();
});

test('buying a weapon spends money the same way', function () {
    $weapon = Weapon::factory()->create(['name' => 'Colt 1911A1', 'cost' => '$40/$650']);

    $this->actingAs($this->owner)
        ->post(route('equip.weapon', $this->character), [
            'weapon_id' => $weapon->id,
            'price'     => 40,
            'paid_from' => 'cash',
        ])
        ->assertRedirect();

    expect($this->character->fresh()->wealth['cash'])->toBe(20.0)
        ->and($this->character->fresh()->weapons)->toHaveCount(1);
});

test('a weapon carries the book price for each era', function () {
    $weapon = Weapon::factory()->create(['cost' => '$40/$650']);

    expect($weapon->prices)->toBe(['1920s' => 40.0, 'modern' => 650.0]);
});
