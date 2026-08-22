<?php

namespace App\Http\Controllers;

use App\Enums\CharacterStatus;
use App\Models\Character;
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
     * A player wants the investigator they are playing, so this hands back the
     * one they touched most recently **in the game their group is running**. A
     * sheet left behind in a finished campaign is not somewhere to land: its
     * player has to make someone new for the game that is actually on, which is
     * what the wizard is for. An unfinished draft has no sheet to show yet, so
     * it goes to the wizard too — which resumes it rather than starting over.
     *
     * With nothing to land on it depends who is asking. The wizard is only for
     * people who play, and roles are cumulative, so this asks for the player's
     * hat specifically: a Keeper who also runs an investigator holds it and is
     * sent to make one, while a Keeper or admin who only runs the game gets the
     * dashboard.
     */
    public function home(Request $request): RedirectResponse
    {
        $user = $request->user();

        $character = Character::query()
            ->where('user_id', $user->id)
            // `in_active_game` reads both of these, and filtering happens in
            // PHP because it is an appended attribute rather than a column.
            ->with(['group', 'games'])
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->get()
            ->first(fn (Character $character): bool => $character->in_active_game);

        if ($character === null) {
            return $user->isPlayer()
                ? to_route('character.create')
                : to_route('dashboard');
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

        return Inertia::render('Dashboard', [
            'users'     => $users,
            'canInvite' => $request->user()->group_id !== null,
        ]);
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
