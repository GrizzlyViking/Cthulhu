<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends AdminController
{
    /**
     * Everyone in the admin's own group. Other groups' members are not listed,
     * not reachable, and not countable from here.
     */
    public function index(Request $request): Response
    {
        $group = $this->requireGroup($request);

        return Inertia::render('Admin/Users', [
            'users' => User::query()
                ->where('group_id', $group->id)
                ->with('roles')
                ->withCount('characters')
                ->orderBy('name')
                ->get()
                ->map(fn (User $user): array => [
                    'id'              => $user->id,
                    'name'            => $user->name,
                    'email'           => $user->email,
                    'roles'           => $user->roleNames(),
                    'blocked'         => $user->isBlocked(),
                    'online'          => $user->is_online,
                    'charactersCount' => $user->characters_count,
                    'isSelf'          => $user->is($request->user()),
                ]),
            'roles' => RoleEnum::options(),
            'group' => ['id' => $group->id, 'name' => $group->name],
        ]);
    }

    /**
     * Replace a member's roles wholesale. Roles are cumulative, so this takes
     * the full set the admin wants the user to end up with.
     */
    public function updateRoles(Request $request, User $user): RedirectResponse
    {
        $user = $this->memberOfCurrentGroup($request, $user);

        $validated = $request->validate([
            'roles'   => ['required', 'array', 'min:1'],
            'roles.*' => ['string', Rule::in(RoleEnum::values())],
        ]);

        $roles = array_values(array_unique($validated['roles']));

        // Nobody may drop their own admin role — that is how a group ends up
        // with no one able to administer it.
        if ($user->is($request->user()) && ! in_array(RoleEnum::ADMIN->value, $roles, true)) {
            return back()->with('error', 'You cannot remove your own admin role. Ask another admin to do it.');
        }

        $user->syncRoles($roles);

        return back()->with('success', "Roles updated for {$user->name}.");
    }

    public function block(Request $request, User $user): RedirectResponse
    {
        $user = $this->memberOfCurrentGroup($request, $user);

        if ($user->is($request->user())) {
            return back()->with('error', 'You cannot block yourself.');
        }

        $user->update(['blocked_at' => now()]);

        return back()->with('success', "{$user->name} is blocked.");
    }

    public function unblock(Request $request, User $user): RedirectResponse
    {
        $user = $this->memberOfCurrentGroup($request, $user);

        $user->update(['blocked_at' => null]);

        return back()->with('success', "{$user->name} may sign in again.");
    }

    /**
     * Soft-delete a member. Their characters are left alone so a mistaken
     * removal can be undone from the console.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        $user = $this->memberOfCurrentGroup($request, $user);

        if ($user->is($request->user())) {
            return back()->with('error', 'You cannot remove yourself from the group.');
        }

        $user->delete();

        return back()->with('success', "{$user->name} removed from the group.");
    }
}
