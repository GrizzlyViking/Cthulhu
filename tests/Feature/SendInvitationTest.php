<?php

use App\Actions\SendInvitation;
use App\Exceptions\UserAlreadyExistsException;
use App\Mail\InvitationMail;
use App\Models\Group;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

test('creates a 7-day invitation and sends the mail synchronously', function () {
    Mail::fake();

    $group   = Group::factory()->create();
    $inviter = User::factory()->create();

    $invitation = app(SendInvitation::class)->send('fresh@example.com', $group, $inviter);

    expect($invitation->email)->toBe('fresh@example.com')
        ->and($invitation->group_id)->toBe($group->id)
        ->and($invitation->invited_by)->toBe($inviter->id)
        ->and(mb_strlen($invitation->token))->toBe(64)
        ->and($invitation->expires_at->timestamp)->toBe(now()->addDays(7)->timestamp);

    Mail::assertSent(InvitationMail::class, function (InvitationMail $mail) use ($invitation): bool {
        return $mail->invitation->is($invitation) && $mail->hasTo('fresh@example.com');
    });
    Mail::assertNotQueued(InvitationMail::class);
});

test('the mail links to the accept page for the invitation token', function () {
    $invitation = Invitation::factory()->create();

    $html = (new InvitationMail($invitation))->render();

    expect($html)->toContain(route('invitation.show', $invitation->token))
        ->toContain($invitation->group->name);
});

test('replaces a previous unaccepted invitation for the same email', function () {
    Mail::fake();

    $group   = Group::factory()->create();
    $inviter = User::factory()->create();

    $stale    = Invitation::factory()->create(['email' => 'fresh@example.com']);
    $accepted = Invitation::factory()->accepted()->create(['email' => 'fresh@example.com']);

    $invitation = app(SendInvitation::class)->send('fresh@example.com', $group, $inviter);

    expect(Invitation::find($stale->id))->toBeNull()
        ->and(Invitation::find($accepted->id))->not->toBeNull()
        ->and(Invitation::find($invitation->id))->not->toBeNull();
});

test('refuses to invite an email that already belongs to a user', function () {
    Mail::fake();

    $group   = Group::factory()->create();
    $inviter = User::factory()->create();
    User::factory()->create(['email' => 'existing@example.com']);

    expect(fn () => app(SendInvitation::class)->send('existing@example.com', $group, $inviter))
        ->toThrow(UserAlreadyExistsException::class);

    expect(Invitation::where('email', 'existing@example.com')->exists())->toBeFalse();
    Mail::assertNothingSent();
});

test('refuses to invite an email of a soft-deleted user', function () {
    Mail::fake();

    $group   = Group::factory()->create();
    $inviter = User::factory()->create();

    $trashed = User::factory()->create(['email' => 'ghost@example.com']);
    $trashed->delete();

    expect(fn () => app(SendInvitation::class)->send('ghost@example.com', $group, $inviter))
        ->toThrow(UserAlreadyExistsException::class);

    Mail::assertNothingSent();
});
