<?php

use App\Enums\RoleEnum;
use App\Models\Group;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

/**
 * @return array<int, string>
 */
function adminPages(): array
{
    return [
        route('admin.index'),
        route('admin.group.edit'),
        route('admin.users.index'),
        route('admin.skills.index'),
        route('admin.weapons.index'),
    ];
}

test('a guest is sent to the login page', function () {
    foreach (adminPages() as $page) {
        $this->get($page)->assertRedirect(route('login'));
    }
});

test('a player cannot reach any admin page', function () {
    $player = User::factory()->inGroup()->create();
    $player->assignRole(RoleEnum::PLAYER->value);

    foreach (adminPages() as $page) {
        $this->actingAs($player)->get($page)->assertForbidden();
    }
});

test('a keeper cannot reach any admin page', function () {
    $keeper = User::factory()->inGroup()->create();
    $keeper->assignRole(RoleEnum::KEEPER->value);

    foreach (adminPages() as $page) {
        $this->actingAs($keeper)->get($page)->assertForbidden();
    }
});

test('an admin reaches every admin page', function () {
    $admin = User::factory()->inGroup()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    foreach (adminPages() as $page) {
        $this->actingAs($admin)->get($page)->assertOk();
    }
});

test('a user who is player, keeper and admin at once still reaches the admin pages', function () {
    $user = User::factory()->inGroup()->create();
    $user->assignRole([RoleEnum::PLAYER->value, RoleEnum::KEEPER->value, RoleEnum::ADMIN->value]);

    expect($user->roleNames())->toHaveCount(3);

    $this->actingAs($user)->get(route('admin.index'))->assertOk();
});

test('an ungrouped admin sees the overview but not the group-scoped pages', function () {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::ADMIN->value);

    $this->actingAs($admin)->get(route('admin.index'))->assertOk();

    $this->actingAs($admin)->get(route('admin.group.edit'))->assertForbidden();
    $this->actingAs($admin)->get(route('admin.users.index'))->assertForbidden();
});

test('the role picker is what grants access, not the legacy column', function () {
    $user = User::factory()->inGroup(Group::factory()->create())->create();

    $this->actingAs($user)->get(route('admin.index'))->assertForbidden();

    $user->assignRole(RoleEnum::ADMIN->value);

    $this->actingAs($user)->get(route('admin.index'))->assertOk();
});
