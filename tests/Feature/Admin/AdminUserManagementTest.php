<?php

use App\Enums\RoleEnum;
use App\Models\Group;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Collection;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $this->group = Group::factory()->create();
    $this->admin = User::factory()->inGroup($this->group)->create();
    $this->admin->assignRole(RoleEnum::ADMIN->value);
});

test('the list holds the admins own group and nobody else', function () {
    $groupmate = User::factory()->inGroup($this->group)->create(['name' => 'Harvey Walters']);
    $groupmate->assignRole(RoleEnum::PLAYER->value);

    User::factory()->inGroup()->create(['name' => 'Someone Elsewhere']);

    $this->actingAs($this->admin)
        ->get(route('admin.users.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Users')
            ->has('users', 2)
            ->where('users', fn (Collection $users) => $users->pluck('name')->sort()->values()->all()
                === collect([$this->admin->name, 'Harvey Walters'])->sort()->values()->all()));
});

test('an admin can give a user several roles at once', function () {
    $user = User::factory()->inGroup($this->group)->create();
    $user->assignRole(RoleEnum::PLAYER->value);

    $this->actingAs($this->admin)
        ->put(route('admin.users.roles.update', $user), [
            'roles' => [RoleEnum::PLAYER->value, RoleEnum::KEEPER->value, RoleEnum::ADMIN->value],
        ])
        ->assertRedirect();

    expect($user->fresh()->roleNames())
        ->toContain(RoleEnum::PLAYER->value, RoleEnum::KEEPER->value, RoleEnum::ADMIN->value)
        ->toHaveCount(3);
});

test('updating roles replaces the whole set rather than adding to it', function () {
    $user = User::factory()->inGroup($this->group)->create();
    $user->assignRole([RoleEnum::PLAYER->value, RoleEnum::KEEPER->value]);

    $this->actingAs($this->admin)
        ->put(route('admin.users.roles.update', $user), ['roles' => [RoleEnum::PLAYER->value]])
        ->assertRedirect();

    expect($user->fresh()->roleNames())->toBe([RoleEnum::PLAYER->value]);
});

test('unknown roles are rejected', function () {
    $user = User::factory()->inGroup($this->group)->create();
    $user->assignRole(RoleEnum::PLAYER->value);

    $this->actingAs($this->admin)
        ->put(route('admin.users.roles.update', $user), ['roles' => ['keeper of arcane lore']])
        ->assertSessionHasErrors('roles.0');

    expect($user->fresh()->roleNames())->toBe([RoleEnum::PLAYER->value]);
});

test('an empty role set is rejected', function () {
    $user = User::factory()->inGroup($this->group)->create();
    $user->assignRole(RoleEnum::PLAYER->value);

    $this->actingAs($this->admin)
        ->put(route('admin.users.roles.update', $user), ['roles' => []])
        ->assertSessionHasErrors('roles');
});

test('an admin cannot drop their own admin role', function () {
    $this->actingAs($this->admin)
        ->put(route('admin.users.roles.update', $this->admin), ['roles' => [RoleEnum::PLAYER->value]])
        ->assertSessionHas('error');

    expect($this->admin->fresh()->isAdmin())->toBeTrue();
});

test('another admin can take the admin role away', function () {
    $other = User::factory()->inGroup($this->group)->create();
    $other->assignRole([RoleEnum::PLAYER->value, RoleEnum::ADMIN->value]);

    $this->actingAs($this->admin)
        ->put(route('admin.users.roles.update', $other), ['roles' => [RoleEnum::PLAYER->value]])
        ->assertRedirect();

    expect($other->fresh()->isAdmin())->toBeFalse();
});

test('a user in another group is invisible, not merely forbidden', function () {
    $stranger = User::factory()->inGroup()->create();
    $stranger->assignRole(RoleEnum::PLAYER->value);

    $this->actingAs($this->admin)
        ->put(route('admin.users.roles.update', $stranger), ['roles' => [RoleEnum::ADMIN->value]])
        ->assertNotFound();

    $this->actingAs($this->admin)->delete(route('admin.users.destroy', $stranger))->assertNotFound();
    $this->actingAs($this->admin)->put(route('admin.users.block', $stranger))->assertNotFound();

    expect($stranger->fresh()->isAdmin())->toBeFalse()
        ->and($stranger->fresh()->trashed())->toBeFalse()
        ->and($stranger->fresh()->isBlocked())->toBeFalse();
});

test('an admin can block and unblock a groupmate', function () {
    $user = User::factory()->inGroup($this->group)->create();
    $user->assignRole(RoleEnum::PLAYER->value);

    $this->actingAs($this->admin)->put(route('admin.users.block', $user))->assertRedirect();
    expect($user->fresh()->isBlocked())->toBeTrue();

    $this->actingAs($this->admin)->delete(route('admin.users.unblock', $user))->assertRedirect();
    expect($user->fresh()->isBlocked())->toBeFalse();
});

test('an admin cannot block or remove themselves', function () {
    $this->actingAs($this->admin)->put(route('admin.users.block', $this->admin))->assertSessionHas('error');
    $this->actingAs($this->admin)->delete(route('admin.users.destroy', $this->admin))->assertSessionHas('error');

    expect($this->admin->fresh()->isBlocked())->toBeFalse()
        ->and($this->admin->fresh()->trashed())->toBeFalse();
});

test('removing a groupmate soft deletes them and leaves their characters alone', function () {
    $user = User::factory()->inGroup($this->group)->create();
    $user->assignRole(RoleEnum::PLAYER->value);
    $character = \App\Models\Character::factory()->create(['user_id' => $user->id]);

    $this->actingAs($this->admin)->delete(route('admin.users.destroy', $user))->assertRedirect();

    expect($user->fresh()->trashed())->toBeTrue()
        ->and($character->fresh())->not->toBeNull();
});

test('a keeper can no longer manage users — that moved to the admin section', function () {
    $keeper = User::factory()->inGroup($this->group)->create();
    $keeper->assignRole(RoleEnum::KEEPER->value);
    $player = User::factory()->inGroup($this->group)->create();

    $this->actingAs($keeper)->delete(route('admin.users.destroy', $player))->assertForbidden();
    $this->actingAs($keeper)
        ->put(route('admin.users.roles.update', $player), ['roles' => [RoleEnum::ADMIN->value]])
        ->assertForbidden();

    expect($player->fresh()->trashed())->toBeFalse()
        ->and($player->fresh()->isAdmin())->toBeFalse();
});
