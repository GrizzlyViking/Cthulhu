<?php

use App\Models\Character;
use App\Models\EquipmentItem;
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
        'user_id'   => $this->owner->id,
        'group_id'  => $this->owner->group_id,
        'backstory' => ['gear' => "Lantern; Revolver\nLucky stone"],
    ]);
});

function gearEntry(string $name, string $type = 'equipment', ?int $id = null): array
{
    return ['name' => $name, 'type' => $type, 'catalogue_id' => $id, 'quantity' => 2];
}

test('a mixed transfer reuses catalogue entries and shares new possessions without spending money', function () {
    $lantern = EquipmentItem::factory()->create(['name' => 'Zzq Lantern']);
    $weapon  = Weapon::factory()->create();
    $cash    = $this->character->cash;
    $assets  = $this->character->assets;
    $items   = [gearEntry('zzq lantern'), gearEntry('My revolver', 'weapon', $weapon->id), gearEntry('Zzq Lucky stone')];

    $this->actingAs($this->owner)->post(route('gear.transfer', $this->character), compact('items'))
        ->assertRedirect()->assertSessionHasNoErrors();

    $sheet = $this->character->fresh();
    expect($sheet->equipment)->toHaveCount(2)
        ->and($sheet->weapons)->toHaveCount(1)
        ->and($sheet->weapons->first()->id)->toBe($weapon->id)
        ->and($sheet->equipment->firstWhere('id', $lantern->id)->pivot->quantity)->toBe(2)
        ->and($sheet->cash)->toBe($cash)
        ->and($sheet->assets)->toBe($assets)
        ->and($sheet->backstory['gear'])->toBe("Lantern; Revolver\nLucky stone");
    $custom = EquipmentItem::where('name', 'Zzq Lucky stone')->firstOrFail();
    expect($custom->is_custom)->toBeTrue()->and($custom->created_by)->toBe($this->owner->id);

    $this->post(route('gear.transfer', $this->character), compact('items'))->assertSessionHasNoErrors();
    expect($this->character->fresh()->equipment)->toHaveCount(2)
        ->and($this->character->fresh()->weapons)->toHaveCount(1);
});

test('players can contribute an unspecified weapon and another player can reuse it', function () {
    $this->actingAs($this->owner)->post(route('gear.transfer', $this->character), [
        'items' => [gearEntry('Zzq Strange weapon', 'weapon')],
    ])->assertSessionHasNoErrors();
    $weapon = Weapon::where('name', 'Zzq Strange weapon')->firstOrFail();
    expect($weapon->is_custom)->toBeTrue()->and($weapon->created_by)->toBe($this->owner->id)
        ->and($weapon->damage)->toBe('—')->and($weapon->skill)->toBe('Unspecified');

    $other = User::factory()->inGroup()->create();
    $other->assignRole('player');
    $sheet = Character::factory()->create(['user_id' => $other->id, 'group_id' => $other->group_id]);
    $this->actingAs($other)->getJson(route('gear.catalogue', $sheet))
        ->assertOk()->assertJsonFragment(['id' => $weapon->id, 'name' => $weapon->name]);
    $this->post(route('gear.transfer', $sheet), ['items' => [gearEntry('zzq strange weapon', 'weapon')]])
        ->assertSessionHasNoErrors();
    expect($sheet->fresh()->weapons->first()->id)->toBe($weapon->id);
});

test('an invalid or retired selection refuses the entire batch', function () {
    $weapon = Weapon::factory()->create();
    $weapon->delete();
    $this->actingAs($this->owner)->post(route('gear.transfer', $this->character), [
        'items' => [gearEntry('Zzq Must not be created'), gearEntry('Retired', 'weapon', $weapon->id)],
    ])->assertSessionHasErrors('items.1.catalogue_id');
    expect(EquipmentItem::where('name', 'Zzq Must not be created')->exists())->toBeFalse();
    expect($this->character->fresh()->equipment)->toHaveCount(0);
});

test('another player cannot read the transfer catalogue for or transfer gear onto this sheet', function () {
    $other = User::factory()->inGroup()->create();
    $other->assignRole('player');
    $this->actingAs($other)->getJson(route('gear.catalogue', $this->character))->assertForbidden();
    $this->post(route('gear.transfer', $this->character), ['items' => [gearEntry('Zzq Intrusion')]])->assertForbidden();
});

test('blank names and invalid quantities cannot create possessions', function () {
    $this->actingAs($this->owner)->post(route('gear.transfer', $this->character), [
        'items' => [['name' => '  ', 'type' => 'equipment', 'quantity' => 0]],
    ])->assertSessionHasErrors(['items.0.name', 'items.0.quantity']);
});

test('transferring part of an entry saves the remainder and keeps other backstory fields', function () {
    $source = "Worn tweed suit and travelling coat; Lucky stone\nRevolver";
    $this->character->update(['backstory' => ['gear' => $source, 'my_story' => 'My history']]);
    $item = [...gearEntry('traveling coat'), 'source_index' => 0, 'remaining' => 'Worn tweed suit'];
    $this->actingAs($this->owner)->post(route('gear.transfer', $this->character), [
        'gear' => $source, 'saved_gear' => $source, 'items' => [$item],
    ])->assertSessionHasNoErrors();
    expect($this->character->fresh()->backstory)->toBe([
        'gear' => "Worn tweed suit\nLucky stone\nRevolver", 'my_story' => 'My history',
    ])->and($this->character->fresh()->equipment->first()->name)->toBe('traveling coat');
});

test('only added entries are removed and unsaved gear edits are included', function () {
    $existing = EquipmentItem::factory()->create(['name' => 'Zzq Existing']);
    $this->character->equipment()->attach($existing);
    $this->actingAs($this->owner)->post(route('gear.transfer', $this->character), [
        'saved_gear' => $this->character->backstory['gear'],
        'gear'       => 'Zzq Existing; Zzq New; Zzq Unchecked',
        'items'      => [
            [...gearEntry('Zzq Existing'), 'source_index' => 0, 'remaining' => ''],
            [...gearEntry('Zzq New'), 'source_index' => 1, 'remaining' => ''],
        ],
    ])->assertSessionHasNoErrors();
    expect($this->character->fresh()->backstory['gear'])->toBe("Zzq Existing\nZzq Unchecked");
});

test('a stale gear review cannot overwrite new prose or add any items', function () {
    $this->actingAs($this->owner)->post(route('gear.transfer', $this->character), [
        'saved_gear' => 'Old gear', 'gear' => 'Zzq New',
        'items'      => [[...gearEntry('Zzq New'), 'source_index' => 0, 'remaining' => '']],
    ])->assertSessionHasErrors('gear');
    expect($this->character->fresh()->backstory['gear'])->toBe("Lantern; Revolver\nLucky stone")
        ->and(EquipmentItem::where('name', 'Zzq New')->exists())->toBeFalse();
});

test('a transferred weapon is one possession shared by combat and storage without an equipment copy', function () {
    $name = 'Zzq Personal revolver';
    $this->character->update(['backstory' => ['gear' => $name]]);
    $this->actingAs($this->owner)->post(route('gear.transfer', $this->character), [
        'gear'       => $name,
        'saved_gear' => $name,
        'items'      => [[...gearEntry($name, 'weapon'), 'quantity' => 1, 'source_index' => 0, 'remaining' => '']],
    ])->assertSessionHasNoErrors();

    $weapon = $this->character->fresh()->weapons->sole();
    expect($this->character->fresh()->equipment)->toHaveCount(0)
        ->and(EquipmentItem::where('name', $name)->exists())->toBeFalse()
        ->and(\Illuminate\Support\Facades\DB::table('equipables')->where('character_id', $this->character->id)->count())->toBe(1)
        ->and($this->character->fresh()->backstory['gear'])->toBe('');

    $location = \App\Models\StorageLocation::where('slug', 'travel-chest')->firstOrFail();
    $this->put(route('equipment.update', ['character' => $this->character, 'equipable' => $weapon->pivot->id]), [
        'storage_location_id' => $location->id, 'quantity' => 1,
    ])->assertSessionHasNoErrors();

    $storedWeapon = $this->character->fresh()->weapons->sole();
    expect($storedWeapon->pivot->id)->toBe($weapon->pivot->id)
        ->and($storedWeapon->pivot->storage_location_id)->toBe($location->id)
        ->and($this->character->fresh()->equipment)->toHaveCount(0);
});
