<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
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
        return DB::table('sessions')->whereNotNull('user_id', )->get('user_id')->toArray();
    }

    public function playerManagement()
    {
        $users = User::with('characters')->get()->map(function (User $user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
                'sentRelative' => $user->role,
                'online' => $user->is_online,
                'content' => ''
            ];
        });
        return Inertia::render('Players', compact('users'));
    }

    public function role(User $user, Request $request)
    {
        $request->validate([
            'role' => [
                'required',
            ]
        ]);

        $user->update(['role' => $request->input('role')]);

        return redirect()->back();
    }
}
