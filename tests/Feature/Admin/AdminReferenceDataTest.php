<?php

use App\Enums\Era;
use App\Enums\RoleEnum;
use App\Misc\WeaponTable;
use App\Models\Skill;
use App\Models\User;
use App\Models\Weapon;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Collection;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $this->admin = User::factory()->inGroup()->create();
    $this->admin->assignRole(RoleEnum::ADMIN->value);
});

// The handbook's skills and weapons are installed by migration, so these
// tables are never empty — the assertions are relative to what is already there.

test('the skill list is paginated', function () {
    Skill::factory()->count(30)->create();

    $total = Skill::query()->count();

    $this->actingAs($this->admin)
        ->get(route('admin.skills.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Skills')
            ->has('skills.data', 25)
            ->where('skills.total', $total)
            ->where('skills.current_page', 1));
});

test('skills can be searched regardless of case', function () {
    Skill::factory()->create(['display_name' => 'Spot Hidden', 'slug' => 'spot-hidden']);
    Skill::factory()->create(['display_name' => 'Library Use', 'slug' => 'library-use']);

    $this->actingAs($this->admin)
        ->get(route('admin.skills.index', ['search' => 'SPOT']))
        ->assertInertia(fn (Assert $page) => $page
            ->has('skills.data', 1)
            ->where('skills.data.0.display_name', 'Spot Hidden'));
});

test('a search term with wildcards is treated as literal text', function () {
    Skill::factory()->create(['display_name' => 'Spot Hidden', 'slug' => 'spot-hidden']);

    $this->actingAs($this->admin)
        ->get(route('admin.skills.index', ['search' => '%']))
        ->assertInertia(fn (Assert $page) => $page->has('skills.data', 0));
});

test('the weapon list can be filtered by category', function () {
    $handguns = Weapon::query()->where('category', WeaponTable::HANDGUNS)->count();

    expect($handguns)->toBeGreaterThan(0);

    $this->actingAs($this->admin)
        ->get(route('admin.weapons.index', ['category' => WeaponTable::HANDGUNS]))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Weapons')
            ->where('weapons.total', $handguns)
            ->where('filters.category', WeaponTable::HANDGUNS));

    // Nothing outside the filtered category leaks onto the page.
    $this->actingAs($this->admin)
        ->get(route('admin.weapons.index', ['category' => WeaponTable::HANDGUNS]))
        ->assertInertia(fn (Assert $page) => $page->where(
            'weapons.data',
            fn (Collection $weapons) => $weapons->every(fn (array $weapon) => $weapon['category'] === WeaponTable::HANDGUNS)
        ));
});

test('an unknown category or era filter is ignored rather than obeyed', function () {
    $total = Weapon::query()->count();

    $this->actingAs($this->admin)
        ->get(route('admin.weapons.index', ['category' => 'Ray Guns', 'era' => 'victorian']))
        ->assertInertia(fn (Assert $page) => $page
            ->where('weapons.total', $total)
            ->where('filters.category', '')
            ->where('filters.era', ''));
});

test('the era filter goes through the eras list, so a weapon in both shows under either', function () {
    // `era` is the book's verbatim cell and is deliberately not matched on;
    // `eras` is the list the filter reads. Here the two disagree on purpose.
    Weapon::factory()->create(['name' => 'Zzq Both Eras', 'era' => '1920s, Modern', 'eras' => Era::all()]);
    Weapon::factory()->create(['name' => 'Zzq Twenties Only', 'era' => '1920s, Rare', 'eras' => [Era::Twenties->value]]);
    Weapon::factory()->create(['name' => 'Zzq Modern Only', 'era' => 'Rare', 'eras' => [Era::Modern->value]]);

    $listed = fn (Era $era) => $this->actingAs($this->admin)
        ->get(route('admin.weapons.index', ['era' => $era->value, 'search' => 'Zzq']))
        ->viewData('page')['props']['weapons']['data'];

    expect(collect($listed(Era::Twenties))->pluck('name')->sort()->values()->all())
        ->toBe(['Zzq Both Eras', 'Zzq Twenties Only'])
        ->and(collect($listed(Era::Modern))->pluck('name')->sort()->values()->all())
        ->toBe(['Zzq Both Eras', 'Zzq Modern Only']);
});

test('weapons can be searched by name', function () {
    Weapon::factory()->create(['name' => 'Zzyzx Prototype', 'category' => WeaponTable::HANDGUNS]);

    $this->actingAs($this->admin)
        ->get(route('admin.weapons.index', ['search' => 'zzyzx']))
        ->assertInertia(fn (Assert $page) => $page
            ->where('weapons.total', 1)
            ->where('weapons.data.0.name', 'Zzyzx Prototype'));
});

test('the index reports whether editing is switched on', function () {
    config()->set('cthulhu.admin.edit_reference_data', false);

    $this->actingAs($this->admin)
        ->get(route('admin.skills.index'))
        ->assertInertia(fn (Assert $page) => $page->where('editable', false));

    config()->set('cthulhu.admin.edit_reference_data', true);

    $this->actingAs($this->admin)
        ->get(route('admin.weapons.index'))
        ->assertInertia(fn (Assert $page) => $page->where('editable', true));
});
