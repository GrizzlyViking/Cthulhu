<?php

use App\Enums\RoleEnum;
use App\Models\Group;
use App\Models\Invitation;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

test('a player may invite another player into their own group', function () {
    Mail::fake();

    $group  = Group::factory()->create();
    $player = User::factory()->inGroup($group)->create();
    $player->assignRole(RoleEnum::PLAYER->value);

    $this->actingAs($player)
        ->post(route('invitations.store'), ['email' => 'new.player@example.com'])
        ->assertRedirect();

    $invitation = Invitation::where('email', 'new.player@example.com')->firstOrFail();

    expect($invitation->group_id)->toBe($group->id)
        ->and($invitation->invited_by)->toBe($player->id)
        ->and($invitation->roles)->toBe(['player']);
});

test('the dashboard offers invitations only when the user belongs to a group', function () {
    $grouped   = User::factory()->inGroup()->create();
    $ungrouped = User::factory()->create();

    $this->actingAs($grouped)
        ->get(route('dashboard'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')
            ->where('canInvite', true));

    $this->actingAs($ungrouped)
        ->get(route('dashboard'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')
            ->where('canInvite', false));
});

test('the player invitation endpoint cannot grant elevated roles', function () {
    Mail::fake();

    $player = User::factory()->inGroup()->create();
    $player->assignRole(RoleEnum::PLAYER->value);

    $this->actingAs($player)
        ->post(route('invitations.store'), [
            'email' => 'new.keeper@example.com',
            'roles' => ['player', 'keeper', 'admin'],
        ])
        ->assertRedirect();

    expect(Invitation::where('email', 'new.keeper@example.com')->firstOrFail()->roles)
        ->toBe(['player']);
});

test('a player cannot replace an admins pending invitation and its roles', function () {
    Mail::fake();

    $player = User::factory()->inGroup()->create();
    $player->assignRole(RoleEnum::PLAYER->value);

    $adminInvitation = Invitation::factory()->create([
        'email' => 'invited.keeper@example.com',
        'roles' => ['keeper', 'admin'],
    ]);

    $this->actingAs($player)
        ->post(route('invitations.store'), [
            'email' => 'Invited.Keeper@example.com',
        ])
        ->assertSessionHasErrors('email');

    expect($adminInvitation->fresh()->roles)->toBe(['keeper', 'admin'])
        ->and(Invitation::where('email', 'invited.keeper@example.com')->count())->toBe(1);
    Mail::assertNothingSent();
});

test('an ungrouped user cannot invite anyone', function () {
    Mail::fake();

    $user = User::factory()->create();
    $user->assignRole(RoleEnum::PLAYER->value);

    $this->actingAs($user)
        ->post(route('invitations.store'), ['email' => 'stranger@example.com'])
        ->assertNotFound();

    expect(Invitation::where('email', 'stranger@example.com')->exists())->toBeFalse();
    Mail::assertNothingSent();
});

test('a guest cannot send an invitation', function () {
    $this->post(route('invitations.store'), ['email' => 'stranger@example.com'])
        ->assertRedirect(route('login'));
});
