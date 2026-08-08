<?php

use App\Enums\Era;
use App\Enums\RoleEnum;
use App\Misc\WeaponTable;
use App\Models\Character;
use App\Models\Skill;
use App\Models\User;
use App\Models\Weapon;
use Database\Seeders\RolesAndPermissionsSeeder;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    config()->set('cthulhu.admin.edit_reference_data', true);

    $this->admin = User::factory()->inGroup()->create();
    $this->admin->assignRole(RoleEnum::ADMIN->value);

    $this->skill = Skill::factory()->create(['slug' => 'fighting-harpoon', 'display_name' => 'Fighting (Harpoon)']);
});

/**
 * @param  array<string, mixed> $overrides
 * @return array<string, mixed>
 */
function weaponPayload(array $overrides = []): array
{
    return [
        'name'           => 'Whaling Harpoon',
        'category'       => WeaponTable::HAND_TO_HAND,
        'skill'          => 'fighting-harpoon',
        'damage'         => '1D8+DB',
        'base_range'     => 'touch',
        'uses_per_round' => '1',
        'bullets_in_mag' => '',
        'cost'           => '$15/$120',
        'malfunction'    => '',
        'era'            => '1920s, Modern',
        'eras'           => Era::all(),
        'impale'         => true,
        ...$overrides,
    ];
}

test('an admin can add a weapon to the armoury', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.weapons.store'), weaponPayload())
        ->assertRedirect();

    $weapon = Weapon::where('name', 'Whaling Harpoon')->firstOrFail();

    expect($weapon->skill)->toBe('fighting-harpoon')
        ->and($weapon->impale)->toBeTrue()
        ->and($weapon->category)->toBe(WeaponTable::HAND_TO_HAND);
});

test('an admin can edit a weapon', function () {
    $weapon = Weapon::factory()->create(['name' => 'Whaling Harpoon', 'skill' => 'fighting-harpoon']);

    $this->actingAs($this->admin)
        ->put(route('admin.weapons.update', $weapon), weaponPayload(['damage' => '2D6', 'impale' => false]))
        ->assertRedirect();

    expect($weapon->fresh()->damage)->toBe('2D6')
        ->and($weapon->fresh()->impale)->toBeFalse();
});

test('a duplicate name is refused, including against a retired weapon', function () {
    Weapon::factory()->create(['name' => 'Whaling Harpoon'])->delete();

    $this->actingAs($this->admin)
        ->post(route('admin.weapons.store'), weaponPayload())
        ->assertSessionHasErrors('name');

    expect(session('errors')->first('name'))->toContain('retired');
});

test('an unknown category is refused', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.weapons.store'), weaponPayload(['category' => 'Ray Guns']))
        ->assertSessionHasErrors('category');
});

test('a weapon cannot be pointed at a skill that does not exist, or at a retired one', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.weapons.store'), weaponPayload(['skill' => 'no-such-skill']))
        ->assertSessionHasErrors('skill');

    $this->skill->delete();

    $this->actingAs($this->admin)
        ->post(route('admin.weapons.store'), weaponPayload())
        ->assertSessionHasErrors('skill');

    expect(Weapon::where('name', 'Whaling Harpoon')->exists())->toBeFalse();
});

test('retiring a weapon takes it off the sheets carrying it and out of the armoury', function () {
    $weapon    = Weapon::factory()->create(['name' => 'Whaling Harpoon', 'skill' => 'fighting-harpoon']);
    $character = Character::factory()->create();
    $character->weapons()->attach($weapon->id, ['ammo' => 0, 'ammo_reserve' => 0]);

    $this->actingAs($this->admin)
        ->delete(route('admin.weapons.destroy', $weapon))
        ->assertRedirect();

    expect($weapon->fresh()->trashed())->toBeTrue()
        ->and($character->fresh()->weapons->pluck('name'))->not->toContain('Whaling Harpoon');

    // The armoury the weapon picker reads is the same table, so it drops out too.
    $this->actingAs($this->admin)
        ->get(route('dashboard'))
        ->assertInertia(fn (Assert $page) => $page->where(
            'auth.equipment',
            fn ($equipment) => collect($equipment)->pluck('name')->doesntContain('Whaling Harpoon')
        ));
});

test('restoring a weapon brings it back with the ammunition it had', function () {
    $weapon    = Weapon::factory()->create(['name' => 'Whaling Harpoon', 'skill' => 'fighting-harpoon']);
    $character = Character::factory()->create();
    $character->weapons()->attach($weapon->id, ['ammo' => 4, 'ammo_reserve' => 12]);

    $this->actingAs($this->admin)->delete(route('admin.weapons.destroy', $weapon));

    $this->actingAs($this->admin)
        ->put(route('admin.weapons.restore', ['id' => $weapon->id]))
        ->assertRedirect();

    $restored = $character->fresh()->weapons->firstWhere('name', 'Whaling Harpoon');

    expect($weapon->fresh()->trashed())->toBeFalse()
        ->and($restored)->not->toBeNull()
        ->and($restored->pivot->ammo)->toBe(4)
        ->and($restored->pivot->ammo_reserve)->toBe(12);
});

test('the retired list shows retired weapons and the live list does not', function () {
    Weapon::factory()->create(['name' => 'Whaling Harpoon'])->delete();

    $this->actingAs($this->admin)
        ->get(route('admin.weapons.index', ['search' => 'Harpoon']))
        ->assertInertia(fn (Assert $page) => $page->where('weapons.total', 0));

    $this->actingAs($this->admin)
        ->get(route('admin.weapons.index', ['search' => 'Harpoon', 'trashed' => 1]))
        ->assertInertia(fn (Assert $page) => $page
            ->where('weapons.total', 1)
            ->where('filters.trashed', true));
});

test('a keeper cannot write to the armoury', function () {
    $keeper = User::factory()->inGroup()->create();
    $keeper->assignRole(RoleEnum::KEEPER->value);

    $this->actingAs($keeper)->post(route('admin.weapons.store'), weaponPayload())->assertForbidden();

    expect(Weapon::where('name', 'Whaling Harpoon')->exists())->toBeFalse();
});
