<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * The group roster, readable by anyone in the group. Changing roles,
     * blocking and removing members live in the admin section.
     */
    public function index(Request $request): Response
    {
        $users = User::query()->inGroupOf($request->user())->with(['characters', 'roles'])->get();

        return Inertia::render('Players', compact('users'));
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
}
