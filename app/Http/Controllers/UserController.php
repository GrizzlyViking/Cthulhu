<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        $users = User::query()->inGroupOf($request->user())->with('characters')->get();

        return Inertia::render('Players', compact('users'));
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        abort_unless($this->isKeeperOrAdmin($request->user()), 403);
        abort_unless($this->inSameGroup($request->user(), $user), 403);

        // this is soft delete
        $user->delete();

        return back();
    }

    /**
     * @return array<int, object>
     */
    public function online(Request $request): array
    {
        return DB::table('sessions')
            ->whereNotNull('user_id')
            ->whereIn('user_id', User::query()->inGroupOf($request->user())->pluck('id'))
            ->get('user_id')
            ->toArray();
    }

    public function playerManagement(Request $request): Response
    {
        $users = User::query()->inGroupOf($request->user())->with('characters')->get()->map(function (User $user) {
            return [
                'id'           => $user->id,
                'name'         => $user->name,
                'role'         => $user->role,
                'sentRelative' => $user->role,
                'online'       => $user->is_online,
                'content'      => '',
            ];
        });

        return Inertia::render('Players', compact('users'));
    }

    public function role(User $user, Request $request): RedirectResponse
    {
        abort_unless($this->isKeeperOrAdmin($request->user()), 403);
        abort_unless($this->inSameGroup($request->user(), $user), 403);

        $validated = $request->validate([
            'role' => ['required', 'string'],
        ]);

        if (DB::table('roles')->where('name', $validated['role'])->exists()) {
            $user->syncRoles([$validated['role']]);
        }

        $user->update(['role' => $validated['role']]);

        return redirect()->back();
    }

    /**
     * A user may act on themselves or on a groupmate — never across groups,
     * and an ungrouped user only on themselves.
     */
    private function inSameGroup(User $actor, User $target): bool
    {
        return $actor->is($target)
            || ($actor->group_id !== null && $actor->group_id === $target->group_id);
    }

    /**
     * Player management (deleting users, changing roles) is reserved for
     * keepers and admins.
     */
    private function isKeeperOrAdmin(User $actor): bool
    {
        return $actor->hasAnyRole([RoleEnum::KEEPER->value, RoleEnum::ADMIN->value]);
    }
}
