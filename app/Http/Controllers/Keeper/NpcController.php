<?php

namespace App\Http\Controllers\Keeper;

use App\Enums\Archetype;
use App\Http\Controllers\Controller;
use App\Misc\NpcGenerator;
use App\Models\Character;
use App\Models\Occupation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * The Keeper's cast: characters conjured up whole, played by nobody.
 *
 * Two actions and no page of its own — both answer back to the Keeper's screen,
 * which is where the cast is listed. Creating is one press and a dropdown;
 * deleting takes the sheet and everything on it away for good, because a cultist
 * who was needed for one scene should not be in next month's list.
 *
 * Behind the `keeper` middleware, so the Keeper's hat is required rather than
 * merely an admin's. Whose cast is whose is the {@see \App\Policies\CharacterPolicy}'s
 * business: not even another Keeper of the same group may look.
 */
class NpcController extends Controller
{
    /**
     * Roll one up and put them in the game being played.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'archetype'     => ['required', Rule::enum(Archetype::class)],
            'occupation_id' => ['nullable', 'integer', Rule::exists(Occupation::class, 'id')],
        ]);

        $game = $request->user()->group?->activeGame;

        if ($game === null) {
            return back()->with('error', 'There is no game being played to put anybody in.');
        }

        $archetype  = Archetype::from($validated['archetype']);
        $occupation = ($validated['occupation_id'] ?? null) === null
            ? null
            : Occupation::find($validated['occupation_id']);

        $character = NpcGenerator::conjure($archetype, $game, $request->user(), $occupation);

        return back()->with(
            'success',
            "{$character->name} — {$archetype->label()}, {$character->occupation} — is at your side.",
        );
    }

    /**
     * Away for good: the sheet, the skills, the knife and the game membership.
     *
     * A sheet belonging to another Keeper — or to a player — answers 404 rather
     * than 403: the cast is meant to be invisible, and a refusal would confirm
     * that somebody's cultist exists.
     */
    public function destroy(Request $request, Character $character): RedirectResponse
    {
        if (! $character->isNpc() || $request->user()->cannot('delete', $character)) {
            abort(404);
        }

        $name = $character->name;

        $character->purge();

        return back()->with('success', "{$name} is gone.");
    }
}
