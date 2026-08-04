<?php

use App\Models\Group;
use App\Models\User;

it('belongs to a group', function () {
    $group = Group::factory()->create();
    $user  = User::factory()->inGroup($group)->create();

    expect($user->group)->toBeInstanceOf(Group::class)
        ->and($user->group->id)->toBe($group->id);
});

it('is ungrouped by default', function () {
    $user = User::factory()->create();

    expect($user->group_id)->toBeNull()
        ->and($user->group)->toBeNull();
});

it('creates a fresh group with the inGroup factory state when none is given', function () {
    $user = User::factory()->inGroup()->create();

    expect($user->group)->toBeInstanceOf(Group::class);
});

it('reports blocked status via isBlocked', function () {
    $blocked = User::factory()->blocked()->create();
    $active  = User::factory()->create();

    expect($blocked->isBlocked())->toBeTrue()
        ->and($blocked->blocked_at)->not->toBeNull()
        ->and($active->isBlocked())->toBeFalse();
});
