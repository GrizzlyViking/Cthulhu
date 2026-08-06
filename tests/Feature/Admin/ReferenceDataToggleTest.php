<?php

use App\Enums\RoleEnum;
use App\Models\Skill;
use App\Models\User;
use App\Models\Weapon;
use Database\Seeders\RolesAndPermissionsSeeder;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $this->admin = User::factory()->inGroup()->create();
    $this->admin->assignRole(RoleEnum::ADMIN->value);

    $this->skill  = Skill::factory()->create(['slug' => 'dream-lore', 'display_name' => 'Dream Lore']);
    $this->weapon = Weapon::factory()->create(['name' => 'Whaling Harpoon', 'skill' => 'dream-lore']);
});

test('the toggle defaults to on', function () {
    expect(config('cthulhu.admin.edit_reference_data'))->toBeTrue();
});

test('with the toggle off every write is refused', function () {
    config()->set('cthulhu.admin.edit_reference_data', false);

    $writes = [
        fn () => $this->post(route('admin.skills.store'), []),
        fn () => $this->put(route('admin.skills.update', $this->skill), []),
        fn () => $this->delete(route('admin.skills.destroy', $this->skill)),
        fn () => $this->put(route('admin.skills.restore', ['slug' => 'dream-lore'])),
        fn () => $this->post(route('admin.weapons.store'), []),
        fn () => $this->put(route('admin.weapons.update', $this->weapon), []),
        fn () => $this->delete(route('admin.weapons.destroy', $this->weapon)),
        fn () => $this->put(route('admin.weapons.restore', ['id' => $this->weapon->id])),
    ];

    foreach ($writes as $write) {
        $this->actingAs($this->admin);
        $write()->assertForbidden();
    }

    expect($this->skill->fresh()->trashed())->toBeFalse()
        ->and($this->weapon->fresh()->trashed())->toBeFalse();
});

test('with the toggle off the lists are still readable', function () {
    config()->set('cthulhu.admin.edit_reference_data', false);

    $this->actingAs($this->admin)
        ->get(route('admin.skills.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->where('editable', false)->has('skills.data'));

    $this->actingAs($this->admin)
        ->get(route('admin.weapons.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->where('editable', false)->has('weapons.data'));
});

test('the toggle does not open the section to non-admins', function () {
    config()->set('cthulhu.admin.edit_reference_data', true);

    $player = User::factory()->inGroup()->create();
    $player->assignRole(RoleEnum::PLAYER->value);

    $this->actingAs($player)->post(route('admin.skills.store'), [])->assertForbidden();
    $this->actingAs($player)->post(route('admin.weapons.store'), [])->assertForbidden();
});

test('the admin overview reports the toggle so the page can explain itself', function () {
    config()->set('cthulhu.admin.edit_reference_data', false);

    $this->actingAs($this->admin)
        ->get(route('admin.index'))
        ->assertInertia(fn (Assert $page) => $page->where('referenceDataEditable', false));
});
