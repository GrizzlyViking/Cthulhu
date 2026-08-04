<?php

use App\Models\Group;
use App\Models\Invitation;
use App\Models\User;

it('creates an invitation via factory', function () {
    $invitation = Invitation::factory()->create();

    expect($invitation->group)->toBeInstanceOf(Group::class)
        ->and($invitation->inviter)->toBeInstanceOf(User::class)
        ->and(strlen($invitation->token))->toBe(64);
});

it('generates a 64 character token', function () {
    expect(strlen(Invitation::generateToken()))->toBe(64);
});

it('only includes unaccepted, unexpired invitations in the pending scope', function () {
    $pending  = Invitation::factory()->create();
    $expired  = Invitation::factory()->expired()->create();
    $accepted = Invitation::factory()->accepted()->create();

    $pendingIds = Invitation::pending()->pluck('id');

    expect($pendingIds->all())->toBe([$pending->id])
        ->and($pendingIds)->not->toContain($expired->id)
        ->and($pendingIds)->not->toContain($accepted->id);
});

it('reports expiry via isExpired', function () {
    expect(Invitation::factory()->expired()->create()->isExpired())->toBeTrue()
        ->and(Invitation::factory()->create()->isExpired())->toBeFalse();
});

it('reports acceptance via isAccepted', function () {
    expect(Invitation::factory()->accepted()->create()->isAccepted())->toBeTrue()
        ->and(Invitation::factory()->create()->isAccepted())->toBeFalse();
});

it('nulls invited_by when the inviter is deleted', function () {
    $invitation = Invitation::factory()->create();

    $invitation->inviter->forceDelete();

    expect($invitation->fresh()->invited_by)->toBeNull();
});

it('is deleted when its group is deleted', function () {
    $invitation = Invitation::factory()->create();

    $invitation->group->delete();

    expect(Invitation::query()->find($invitation->id))->toBeNull();
});
