<?php

namespace App\Actions;

use App\Enums\RoleEnum;
use App\Exceptions\UserAlreadyExistsException;
use App\Mail\InvitationMail;
use App\Models\Group;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Invite an email address to a group: replaces any previous unaccepted
 * invitation for that address, creates a fresh 7-day invitation and emails
 * the accept link synchronously.
 */
class SendInvitation
{
    /**
     * @throws UserAlreadyExistsException when the email already belongs to a user
     */
    public function send(string $email, Group $group, ?User $inviter, ?array $roles = null): Invitation
    {
        $email = Str::lower($email);

        if (User::withTrashed()->where('email', $email)->exists()) {
            throw new UserAlreadyExistsException("A user with the email [{$email}] already exists.");
        }

        Invitation::query()
            ->where('email', $email)
            ->whereNull('accepted_at')
            ->delete();

        $invitation = Invitation::create([
            'email'      => $email,
            'token'      => Invitation::generateToken(),
            'group_id'   => $group->id,
            'invited_by' => $inviter?->id,
            'roles'      => array_values(array_unique($roles ?? [RoleEnum::PLAYER->value])),
            'expires_at' => now()->addDays(7),
        ]);

        Mail::to($email)->send(new InvitationMail($invitation));

        return $invitation;
    }
}
