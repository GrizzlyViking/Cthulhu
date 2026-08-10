<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Era;
use App\Models\Game;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * The group's campaigns.
 *
 * Games are the group's own data, not the shared skill and weapon lists, so
 * these are not behind the reference-data toggle — they are simply scoped to
 * the admin's own group like the rest of the section.
 */
class GameController extends AdminController
{
    public function store(Request $request): RedirectResponse
    {
        $group = $this->requireGroup($request);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Game::class, 'name')->where('group_id', $group->id),
            ],
            'era' => ['required', Rule::enum(Era::class)],
        ], [
            'name.unique' => 'This group already has a game by that name.',
        ]);

        $game = $group->startGame($validated['name'], Era::from($validated['era']));

        return back()->with('success', "Game “{$game->name}” created.");
    }

    public function update(Request $request, Game $game): RedirectResponse
    {
        $game  = $this->gameOfCurrentGroup($request, $game);
        $group = $this->requireGroup($request);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Game::class, 'name')->where('group_id', $group->id)->ignore($game->id),
            ],
            'era' => ['required', Rule::enum(Era::class)],
        ], [
            'name.unique' => 'This group already has a game by that name.',
        ]);

        $game->update($validated);

        return back()->with('success', "Game “{$game->name}” updated.");
    }

    /**
     * Make this the campaign the group is playing. Whichever was active simply
     * stops being it — the pointer lives on the group, so there is nothing to
     * stand down.
     */
    public function activate(Request $request, Game $game): RedirectResponse
    {
        $game = $this->gameOfCurrentGroup($request, $game);

        $game->activate();

        return back()->with('success', "The group is now playing “{$game->name}”.");
    }

    /**
     * Deleting a game only takes the investigators out of it — their sheets
     * are untouched. The active game cannot go, or the group would be left
     * playing nothing: make another one active first, or rename this one.
     */
    public function destroy(Request $request, Game $game): RedirectResponse
    {
        $game = $this->gameOfCurrentGroup($request, $game);

        if ($game->isActive()) {
            return back()->with('error', 'This is the game the group is playing. Make another one active before deleting it.');
        }

        $name = $game->name;

        $game->delete();

        return back()->with('success', "Game “{$name}” deleted. Its investigators keep their sheets.");
    }
}
