<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('characters')->get();

        return Inertia::render('Players', compact('users'));
    }

    public function destroy(User $user)
    {
        // this is soft delete
        $user->delete();

        return back();
    }

    public function online(): array
    {
        return DB::table('sessions')->whereNotNull('user_id')->get('user_id')->toArray();
    }

    public function playerManagement()
    {
        $users = User::with('characters')->get()->map(function (User $user) {
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

    public function role(User $user, Request $request)
    {
        $validated = $request->validate([
            'role' => ['required', 'string'],
        ]);

        if (DB::table('roles')->where('name', $validated['role'])->exists()) {
            $user->syncRoles([$validated['role']]);
        }

        $user->update(['role' => $validated['role']]);

        return redirect()->back();
    }
}
