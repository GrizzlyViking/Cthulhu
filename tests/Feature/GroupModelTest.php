<?php

use App\Models\Character;
use App\Models\Group;
use App\Models\User;

it('creates a group via factory', function () {
    $group = Group::factory()->create();

    expect($group->id)->not->toBeNull()
        ->and($group->name)->toBeString()->not->toBe('');
});

it('allows mass assignment of name', function () {
    $group = Group::create(['name' => 'Investigators']);

    expect($group->exists)->toBeTrue()
        ->and($group->fresh()->name)->toBe('Investigators');
});

it('has many users via users.group_id', function () {
    $group = Group::factory()->create();
    $users = User::factory()->count(2)->inGroup($group)->create();

    expect($group->users()->count())->toBe(2)
        ->and($group->users->pluck('id')->sort()->values()->all())
        ->toBe($users->pluck('id')->sort()->values()->all());
});

it('has many characters via characters.group_id', function () {
    $group      = Group::factory()->create();
    $characters = Character::factory()->count(2)->create(['group_id' => $group->id]);

    expect($group->characters()->count())->toBe(2)
        ->and($group->characters->pluck('id')->sort()->values()->all())
        ->toBe($characters->pluck('id')->sort()->values()->all());
});

it('nulls users.group_id when the group is deleted', function () {
    $group = Group::factory()->create();
    $user  = User::factory()->inGroup($group)->create();

    $group->delete();

    expect($user->fresh()->group_id)->toBeNull();
});

it('orphans characters instead of deleting them when the group is deleted', function () {
    $group     = Group::factory()->create();
    $character = Character::factory()->create(['group_id' => $group->id]);

    $group->delete();

    $character = $character->fresh();
    expect($character)->not->toBeNull()
        ->and($character->trashed())->toBeFalse()
        ->and($character->group_id)->toBeNull();
});

it('counts pending invitations only', function () {
    $group = Group::factory()->create();
    \App\Models\Invitation::factory()->for($group)->create();
    \App\Models\Invitation::factory()->for($group)->accepted()->create();
    \App\Models\Invitation::factory()->for($group)->expired()->create();

    expect($group->pendingInvitations()->count())->toBe(1);
});
