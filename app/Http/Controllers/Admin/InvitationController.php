<?php

namespace App\Http\Controllers\Admin;

use App\Actions\SendInvitation;
use App\Enums\RoleEnum;
use App\Exceptions\UserAlreadyExistsException;
use App\Models\Invitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InvitationController extends AdminController
{
    /**
     * Invite an email address into the admin's own group. The group is taken
     * from the admin, never from the request, so an admin cannot invite into
     * somebody else's table.
     */
    public function store(Request $request, SendInvitation $sendInvitation): RedirectResponse
    {
        $group = $this->requireGroup($request);

        $validated = $request->validate([
            'email'   => ['required', 'email', 'max:255'],
            'roles'   => ['sometimes', 'array', 'min:1'],
            'roles.*' => ['string', Rule::in(RoleEnum::values())],
        ]);

        $roles = array_values(array_unique($validated['roles'] ?? [RoleEnum::PLAYER->value]));

        try {
            $invitation = $sendInvitation->send($validated['email'], $group, $request->user(), $roles);
        } catch (UserAlreadyExistsException) {
            return back()->withErrors([
                'email' => 'That email already belongs to an account. Move them with the player:assign command instead.',
            ]);
        }

        return back()->with('success', "Invitation sent to {$invitation->email}.");
    }

    /**
     * Revoke a pending invitation.
     */
    public function destroy(Request $request, Invitation $invitation): RedirectResponse
    {
        $group = $this->requireGroup($request);

        abort_unless($invitation->group_id === $group->id, 404);

        $invitation->delete();

        return back()->with('success', "Invitation to {$invitation->email} revoked.");
    }
}
