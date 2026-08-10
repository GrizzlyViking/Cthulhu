<?php

namespace App\Http\Controllers;

use App\Enums\CharacterStatus;
use App\Models\Character;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    /**
     * Where a signed-in user lands.
     *
     * A player wants their own investigator, not a list, so this hands back
     * the character they touched most recently. An unfinished draft has no
     * sheet to show yet, so it goes to the wizard, which resumes it.
     *
     * With nothing to land on it depends who is asking: a player is sent to
     * the wizard to make their first investigator, while a Keeper or admin —
     * who need never have one — gets the dashboard.
     */
    public function home(Request $request): RedirectResponse
    {
        $user = $request->user();

        $character = Character::query()
            ->where('user_id', $user->id)
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->first();

        if ($character === null) {
            return $user->isKeeper() || $user->isAdmin()
                ? to_route('dashboard')
                : to_route('character.create');
        }

        if ($character->status === CharacterStatus::Draft) {
            return to_route('character.create');
        }

        return to_route('character.show', $character->slug);
    }

    public function dashboard(Request $request): Response
    {
        $users = User::query()
            ->inGroupOf($request->user())
            ->with('roles')
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
