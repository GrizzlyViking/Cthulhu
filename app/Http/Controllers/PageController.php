<?php

namespace App\Http\Controllers;

use App\Enums\CharacterStatus;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    public function dashboard(Request $request): Response
    {
        $users = User::query()
            ->inGroupOf($request->user())
            // Drafts are only ever visible to their owner.
            ->with(['characters' => function (HasMany $query) use ($request) {
                $query->where('status', CharacterStatus::Complete)
                    ->orWhere('user_id', $request->user()->id);
            }])
            ->get();

        $skills = Skill::all();

        return Inertia::render('Dashboard', compact('users', 'skills'));
    }

    public function welcome(): Response
    {
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
        ]);
    }

    public function faq(): Response
    {
        return Inertia::render('FAQ');
    }
}
