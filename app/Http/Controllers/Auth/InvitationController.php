<?php

namespace App\Http\Controllers\Auth;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class InvitationController extends Controller
{
    /**
     * Show the accept-invitation page, or a friendly invalid state when the
     * token is unknown, expired, already accepted, or the email is taken.
     */
    public function show(string $token): Response
    {
        $invitation = $this->usableInvitation($token);

        if ($invitation === null) {
            return Inertia::render('Auth/AcceptInvitation', ['invalid' => true]);
        }

        return Inertia::render('Auth/AcceptInvitation', [
            'invalid'   => false,
            'token'     => $invitation->token,
            'email'     => $invitation->email,
            'groupName' => $invitation->group->name,
        ]);
    }

    /**
     * Accept the invitation: create the account in the invitation's group,
     * grant the roles chosen by its admin, mark it accepted and log the user in.
     */
    public function store(Request $request, string $token): RedirectResponse
    {
        $invitation = $this->usableInvitation($token);

        if ($invitation === null) {
            return redirect()->route('invitation.show', $token);
        }

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $invitation->email,
            'password' => $validated['password'],
            'group_id' => $invitation->group_id,
        ]);
        $user->forceFill(['email_verified_at' => now()])->save();
        $user->assignRole($invitation->roles ?? [RoleEnum::PLAYER->value]);

        $invitation->update(['accepted_at' => now()]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect(route('home', absolute: false));
    }

    /**
     * The pending invitation for the token, or null when it cannot be used.
     */
    private function usableInvitation(string $token): ?Invitation
    {
        $invitation = Invitation::query()->pending()->where('token', $token)->first();

        if ($invitation === null) {
            return null;
        }

        // The account may have been created since the invitation was sent;
        // the generic invalid state avoids leaking that the email exists.
        if (User::withTrashed()->where('email', $invitation->email)->exists()) {
            return null;
        }

        return $invitation;
    }
}
