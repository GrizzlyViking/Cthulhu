<?php

use App\Enums\Era;
use App\Enums\RoleEnum;
use App\Models\Group;
use App\Models\Invitation;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $this->group = Group::factory()->create(['name' => 'Dunwich Circle', 'era' => Era::Twenties]);
    $this->admin = User::factory()->inGroup($this->group)->create();
    $this->admin->assignRole(RoleEnum::ADMIN->value);
});

test('the page shows the admins own group with its roster and invitations', function () {
    User::factory()->inGroup($this->group)->create()->assignRole(RoleEnum::PLAYER->value);
    Invitation::factory()->create(['group_id' => $this->group->id]);
    Invitation::factory()->create();

    $this->actingAs($this->admin)
        ->get(route('admin.group.edit'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Group')
            ->where('group.name', 'Dunwich Circle')
            ->has('roles', 3)
            ->has('members', 2)
            ->has('invitations', 1)
            ->where('invitations.0.roles', ['player']));
});

test('an admin can rename their group', function () {
    $this->actingAs($this->admin)
        ->put(route('admin.group.update'), ['name' => 'Arkham Irregulars', 'era' => Era::Twenties->value])
        ->assertRedirect();

    expect($this->group->fresh()->name)->toBe('Arkham Irregulars');
});

test('an admin can change the era', function () {
    $this->actingAs($this->admin)
        ->put(route('admin.group.update'), ['name' => 'Dunwich Circle', 'era' => Era::Modern->value])
        ->assertRedirect();

    expect($this->group->fresh()->era)->toBe(Era::Modern);
});

test('the name must be given and must not collide with another group', function () {
    Group::factory()->create(['name' => 'Arkham Irregulars']);

    $this->actingAs($this->admin)
        ->put(route('admin.group.update'), ['name' => '', 'era' => Era::Twenties->value])
        ->assertSessionHasErrors('name');

    $this->actingAs($this->admin)
        ->put(route('admin.group.update'), ['name' => 'Arkham Irregulars', 'era' => Era::Twenties->value])
        ->assertSessionHasErrors('name');

    expect($this->group->fresh()->name)->toBe('Dunwich Circle');
});

test('an unknown era is rejected', function () {
    $this->actingAs($this->admin)
        ->put(route('admin.group.update'), ['name' => 'Dunwich Circle', 'era' => 'victorian'])
        ->assertSessionHasErrors('era');
});

test('the update only ever touches the admins own group', function () {
    $otherGroup = Group::factory()->create(['name' => 'Innsmouth Locals']);
    $otherAdmin = User::factory()->inGroup($otherGroup)->create();
    $otherAdmin->assignRole(RoleEnum::ADMIN->value);

    $this->actingAs($otherAdmin)
        ->put(route('admin.group.update'), ['name' => 'Renamed', 'era' => Era::Modern->value])
        ->assertRedirect();

    expect($otherGroup->fresh()->name)->toBe('Renamed')
        ->and($this->group->fresh()->name)->toBe('Dunwich Circle');
});

test('an admin invites into their own group, never another', function () {
    Mail::fake();

    $this->actingAs($this->admin)
        ->post(route('admin.invitations.store'), ['email' => 'newcomer@example.com'])
        ->assertRedirect();

    $invitation = Invitation::where('email', 'newcomer@example.com')->firstOrFail();

    expect($invitation->group_id)->toBe($this->group->id)
        ->and($invitation->invited_by)->toBe($this->admin->id)
        ->and($invitation->roles)->toBe(['player']);
});

test('an admin may choose several roles for an invitation', function () {
    Mail::fake();

    $this->actingAs($this->admin)
        ->post(route('admin.invitations.store'), [
            'email' => 'keeper@example.com',
            'roles' => ['player', 'keeper', 'admin'],
        ])
        ->assertRedirect();

    expect(Invitation::where('email', 'keeper@example.com')->firstOrFail()->roles)
        ->toBe(['player', 'keeper', 'admin']);
});

test('an admin invitation rejects an empty or unknown role set', function () {
    Mail::fake();

    $this->actingAs($this->admin)
        ->post(route('admin.invitations.store'), [
            'email' => 'empty@example.com',
            'roles' => [],
        ])
        ->assertSessionHasErrors('roles');

    $this->actingAs($this->admin)
        ->post(route('admin.invitations.store'), [
            'email' => 'unknown@example.com',
            'roles' => ['cultist'],
        ])
        ->assertSessionHasErrors('roles.0');

    expect(Invitation::whereIn('email', ['empty@example.com', 'unknown@example.com'])->exists())->toBeFalse();
});

test('inviting an address that already has an account is refused', function () {
    Mail::fake();

    User::factory()->create(['email' => 'taken@example.com']);

    $this->actingAs($this->admin)
        ->post(route('admin.invitations.store'), ['email' => 'taken@example.com'])
        ->assertSessionHasErrors('email');

    expect(Invitation::where('email', 'taken@example.com')->exists())->toBeFalse();
});

test('an admin can revoke a pending invitation of their own group only', function () {
    $mine     = Invitation::factory()->create(['group_id' => $this->group->id]);
    $stranger = Invitation::factory()->create();

    $this->actingAs($this->admin)
        ->delete(route('admin.invitations.destroy', $mine))
        ->assertRedirect();

    $this->actingAs($this->admin)
        ->delete(route('admin.invitations.destroy', $stranger))
        ->assertNotFound();

    expect(Invitation::find($mine->id))->toBeNull()
        ->and(Invitation::find($stranger->id))->not->toBeNull();
});
