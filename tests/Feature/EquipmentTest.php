<?php

use App\Misc\EquipmentTable;
use App\Models\Character;
use App\Models\EquipmentItem;
use App\Models\StorageLocation;
use App\Models\User;
use App\Models\Weapon;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\DB;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->withoutMiddleware(VerifyCsrfToken::class);

    $this->owner = User::factory()->inGroup()->create();
    $this->owner->assignRole('player');

    $this->character = Character::factory()->create([
        'user_id'  => $this->owner->id,
        'group_id' => $this->owner->group_id,
    ]);

    // The four starting places are laid down by migration, not by this test.
    $this->onPerson = StorageLocation::where('slug', 'on-person')->firstOrFail();
    $this->chest    = StorageLocation::where('slug', 'travel-chest')->firstOrFail();
});

// ---- The catalogue ------------------------------------------------------

test('the transcription holds only what an investigator would carry', function () {
    $names = array_column(EquipmentTable::all(), 'name');

    // The excluded categories, each represented by something the book prints.
    expect($names)
        ->not->toContain('Bacon, sugar cured')      // food
        ->not->toContain('Average Hotel')           // lodging
        ->not->toContain('Bungalow (4 Rooms)')      // real estate
        ->not->toContain('Desk')                    // furniture
        ->not->toContain('Washing machine')         // household goods
        ->not->toContain('Ford Model T')            // transport
        ->not->toContain('Tire Repair Kit')         // vehicle accessories
        ->not->toContain('Steel Row Boat (Seats 4)')// watercraft
        ->not->toContain('Floor Safe (3-Foot high, 950 Lbs.)')
        ->not->toContain('Player Piano');
});

test('the melee weapons stay in the weapons table rather than being duplicated here', function () {
    $names = array_column(EquipmentTable::all(), 'name');

    expect($names)
        ->not->toContain('Rapier')
        ->not->toContain('Dagger')
        ->not->toContain('Brass Knuckles')
        ->not->toContain('16-foot Bullwhip');
});

test('the transcription carries what it should, with the book price verbatim', function () {
    $items = collect(EquipmentTable::all());

    expect($items->firstWhere('name', 'Electric Torch')['cost'])->toBe('$1.35-$2.25')
        ->and($items->firstWhere('name', 'Ouija Board')['cost'])->toBe('98¢')
        ->and($items->firstWhere('name', 'Suitcase (15 Lbs.)')['section'])->toBe(EquipmentTable::LUGGAGE)
        ->and($items->firstWhere('name', '.45 Automatic (Box of 100)')['section'])->toBe(EquipmentTable::AMMUNITION);
});

test('the ligature damage in the pdf text did not survive transcription', function () {
    $names = implode(' ', array_column(EquipmentTable::all(), 'name'));

    expect($names)
        ->not->toContain('ﬁ')->not->toContain('ﬂ')->not->toContain('ﬀ')->not->toContain('ﬄ')
        ->and($names)->toContain('Clinical Thermometer')
        ->and($names)->toContain('Fifteen Hour Candles (Dozen)')
        ->and($names)->toContain('Cufflinks');
});

test('every slug is unique and every section is a known one', function () {
    $items = EquipmentTable::all();

    expect(array_unique(array_column($items, 'slug')))->toHaveCount(count($items));

    foreach ($items as $item) {
        expect(EquipmentTable::sections())->toContain($item['section']);
    }
});

// ---- Adding and keeping -------------------------------------------------

test('an investigator can be given something from the catalogue', function () {
    $lantern = EquipmentItem::factory()->create(['name' => 'Zzq Storm Lantern']);

    $this->actingAs($this->owner)
        ->post(route('equipment.store', $this->character), [
            'equipment_item_id'   => $lantern->id,
            'storage_location_id' => $this->chest->id,
            'quantity'            => 2,
        ])
        ->assertRedirect();

    $owned = $this->character->fresh()->equipment;

    expect($owned)->toHaveCount(1)
        ->and($owned->first()->name)->toBe('Zzq Storm Lantern')
        ->and($owned->first()->pivot->quantity)->toBe(2)
        ->and($owned->first()->pivot->storage_location_id)->toBe($this->chest->id);
});

test('something with no place given goes on the person', function () {
    $item = EquipmentItem::factory()->create();

    $this->actingAs($this->owner)
        ->post(route('equipment.store', $this->character), ['equipment_item_id' => $item->id]);

    expect($this->character->fresh()->equipment->first()->pivot->storage_location_id)->toBe($this->onPerson->id);
});

test('a name the catalogue does not have joins it, flagged as custom', function () {
    $this->actingAs($this->owner)
        ->post(route('equipment.store', $this->character), ['name' => "Grandfather's lucky lighter"])
        ->assertRedirect();

    $item = EquipmentItem::where('name', "Grandfather's lucky lighter")->firstOrFail();

    expect($item->is_custom)->toBeTrue()
        ->and($item->created_by)->toBe($this->owner->id)
        ->and($item->cost)->toBeNull()
        ->and($item->slug)->toStartWith('custom--')
        ->and($this->character->fresh()->equipment->pluck('id'))->toContain($item->id);
});

test('typing the name of something already catalogued picks it rather than making a twin', function () {
    $lantern = EquipmentItem::factory()->create(['name' => 'Zzq Storm Lantern']);

    $this->actingAs($this->owner)
        ->post(route('equipment.store', $this->character), ['name' => 'zzq storm lantern']);

    expect(EquipmentItem::where('name', 'Zzq Storm Lantern')->count())->toBe(1)
        ->and(EquipmentItem::where('is_custom', true)->count())->toBe(0)
        ->and($this->character->fresh()->equipment->first()->id)->toBe($lantern->id);
});

test('adding nothing at all is refused', function () {
    $this->actingAs($this->owner)
        ->post(route('equipment.store', $this->character), ['name' => '  '])
        ->assertSessionHasErrors('name');

    expect($this->character->fresh()->equipment)->toHaveCount(0);
});

test('a thing can be moved, counted and annotated', function () {
    $item = EquipmentItem::factory()->create();
    $this->character->equipment()->attach($item->id, ['storage_location_id' => $this->onPerson->id, 'quantity' => 1]);
    $pivotId = $this->character->fresh()->equipment->first()->pivot->id;

    $this->actingAs($this->owner)
        ->put(route('equipment.update', ['character' => $this->character, 'equipable' => $pivotId]), [
            'storage_location_id' => $this->chest->id,
            'quantity'            => 5,
            'notes'               => 'wrapped in oilcloth',
        ])
        ->assertRedirect();

    $moved = $this->character->fresh()->equipment->first();

    expect($moved->pivot->storage_location_id)->toBe($this->chest->id)
        ->and($moved->pivot->quantity)->toBe(5)
        ->and($moved->pivot->notes)->toBe('wrapped in oilcloth');
});

test('a thing can be dropped', function () {
    $item = EquipmentItem::factory()->create();
    $this->character->equipment()->attach($item->id, ['storage_location_id' => $this->onPerson->id]);
    $pivotId = $this->character->fresh()->equipment->first()->pivot->id;

    $this->actingAs($this->owner)
        ->delete(route('equipment.destroy', ['character' => $this->character, 'equipable' => $pivotId]))
        ->assertRedirect();

    expect($this->character->fresh()->equipment)->toHaveCount(0)
        // Dropping it does not take it out of the catalogue.
        ->and(EquipmentItem::find($item->id))->not->toBeNull();
});

// ---- Weapons in the same list -------------------------------------------

test('a weapon knows where it is kept, like everything else', function () {
    $weapon = Weapon::factory()->create(['name' => 'Colt Revolver']);
    $this->character->weapons()->attach($weapon->id, ['ammo' => 6, 'ammo_reserve' => 12]);
    $pivotId = $this->character->fresh()->weapons->first()->pivot->id;

    $this->actingAs($this->owner)
        ->put(route('equipment.update', ['character' => $this->character, 'equipable' => $pivotId]), [
            'storage_location_id' => $this->chest->id,
            'quantity'            => 1,
        ])
        ->assertRedirect();

    $carried = $this->character->fresh()->weapons->first();

    expect($carried->pivot->storage_location_id)->toBe($this->chest->id)
        // Moving it must not disturb the ammunition.
        ->and($carried->pivot->ammo)->toBe(6)
        ->and($carried->pivot->ammo_reserve)->toBe(12);
});

// ---- The picker ---------------------------------------------------------

test('the typeahead searches the catalogue by name and section', function () {
    EquipmentItem::factory()->create(['name' => 'Zzq Storm Lantern', 'section' => EquipmentTable::OUTDOOR]);
    EquipmentItem::factory()->create(['name' => 'Zzq Silk Handbag', 'section' => EquipmentTable::WOMENS_CLOTHING]);

    $this->actingAs($this->owner)
        ->getJson(route('equipment.search', ['search' => 'ZZQ STORM']))
        ->assertOk()
        ->assertJsonPath('items.0.name', 'Zzq Storm Lantern')
        ->assertJsonCount(1, 'items');

    // Searching a section name returns that whole section, the seeded rows
    // included — so this asserts membership rather than position.
    $bySection = $this->actingAs($this->owner)
        ->getJson(route('equipment.search', ['search' => "Women's"]))
        ->assertOk()
        ->json('items');

    expect(collect($bySection)->pluck('name'))->toContain('Zzq Silk Handbag')
        // Every hit earns its place through its name or its section — the
        // women's toilet set comes back on its name, from Personal Care.
        ->and(collect($bySection)->every(
            fn (array $item) => str_contains(mb_strtolower($item['name'].' '.($item['section'] ?? '')), "women's")
        ))->toBeTrue();
});

test('the handbook is offered before anything players have added', function () {
    EquipmentItem::factory()->custom()->create(['name' => 'Zzq Lantern of the Deep']);
    EquipmentItem::factory()->create(['name' => 'Zzq Aaa Lantern', 'section' => EquipmentTable::OUTDOOR]);

    $this->actingAs($this->owner)
        ->getJson(route('equipment.search', ['search' => 'zzq']))
        ->assertJsonPath('items.0.name', 'Zzq Aaa Lantern')
        ->assertJsonPath('items.1.name', 'Zzq Lantern of the Deep');
});

test('a retired catalogue item drops out of the picker and off the sheets', function () {
    $item = EquipmentItem::factory()->create(['name' => 'Zzq Storm Lantern']);
    $this->character->equipment()->attach($item->id, ['storage_location_id' => $this->onPerson->id]);

    $item->delete();

    expect($this->character->fresh()->equipment)->toHaveCount(0)
        // The pivot survives, so restoring puts it back where it was kept.
        ->and(DB::table('equipables')->where('equipable_id', $item->id)->count())->toBe(1);

    $this->actingAs($this->owner)
        ->getJson(route('equipment.search', ['search' => 'zzq storm']))
        ->assertJsonCount(0, 'items');

    $item->restore();

    expect($this->character->fresh()->equipment->first()->pivot->storage_location_id)->toBe($this->onPerson->id);
});

// ---- Places -------------------------------------------------------------

test('a player can add a place to keep things', function () {
    $this->actingAs($this->owner)
        ->post(route('storage.location.store', $this->character), ['name' => 'Saddlebag'])
        ->assertRedirect();

    expect(StorageLocation::where('name', 'Saddlebag')->exists())->toBeTrue();
});

test('a place that already exists is refused rather than duplicated', function () {
    $this->actingAs($this->owner)
        ->post(route('storage.location.store', $this->character), ['name' => 'Travel chest'])
        ->assertSessionHasErrors('name');

    expect(StorageLocation::where('name', 'Travel chest')->count())->toBe(1);
});

// ---- Who may do this ----------------------------------------------------

test('somebody else cannot equip your investigator', function () {
    $stranger = User::factory()->inGroup()->create();
    $stranger->assignRole('player');
    $item = EquipmentItem::factory()->create();

    $this->actingAs($stranger)
        ->post(route('equipment.store', $this->character), ['equipment_item_id' => $item->id])
        ->assertForbidden();

    expect($this->character->fresh()->equipment)->toHaveCount(0);
});

test('one sheet cannot move another sheet\'s belongings', function () {
    $item = EquipmentItem::factory()->create();
    $this->character->equipment()->attach($item->id, ['storage_location_id' => $this->onPerson->id]);
    $pivotId = $this->character->fresh()->equipment->first()->pivot->id;

    $other = Character::factory()->create(['user_id' => $this->owner->id, 'group_id' => $this->owner->group_id]);

    $this->actingAs($this->owner)
        ->put(route('equipment.update', ['character' => $other, 'equipable' => $pivotId]), [
            'storage_location_id' => $this->chest->id,
        ])
        ->assertNotFound();

    $this->actingAs($this->owner)
        ->delete(route('equipment.destroy', ['character' => $other, 'equipable' => $pivotId]))
        ->assertNotFound();

    expect($this->character->fresh()->equipment->first()->pivot->storage_location_id)->toBe($this->onPerson->id);
});

// ---- The sheet ----------------------------------------------------------

test('the character page carries the equipment and the places to keep it', function () {
    $item = EquipmentItem::factory()->create(['name' => 'Zzq Storm Lantern']);
    $this->character->equipment()->attach($item->id, ['storage_location_id' => $this->chest->id]);

    $this->actingAs($this->owner)
        ->get(route('character.show', $this->character))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Character')
            ->has('character.equipment', 1)
            ->where('character.equipment.0.name', 'Zzq Storm Lantern')
            ->has('storageLocations', count(StorageLocation::STARTING_LOCATIONS)));
});
