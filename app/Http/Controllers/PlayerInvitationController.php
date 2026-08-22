<?php

namespace App\Http\Controllers;

use App\Actions\SendInvitation;
use App\Enums\RoleEnum;
use App\Exceptions\UserAlreadyExistsException;
use App\Models\Invitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlayerInvitationController extends Controller
{
    /**
     * Anyone already at a table may invite another player into it. Roles do
     * not come from the request: only an admin may invite a Keeper or admin.
     */
    public function store(Request $request, SendInvitation $sendInvitation): RedirectResponse
    {
        $group = $request->user()->group;

        abort_if($group === null, 404);

        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $email = Str::lower($validated['email']);

        if (Invitation::query()->pending()->where('email', $email)->exists()) {
            return back()->withErrors([
                'email' => 'That email already has an invitation waiting.',
            ]);
        }

        try {
            $invitation = $sendInvitation->send(
                $email,
                $group,
                $request->user(),
                [RoleEnum::PLAYER->value],
            );
        } catch (UserAlreadyExistsException) {
            return back()->withErrors([
                'email' => 'That email already belongs to an account.',
            ]);
        }

        return back()->with('success', "Invitation sent to {$invitation->email}.");
    }
}
