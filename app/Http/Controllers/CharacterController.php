<?php

namespace App\Http\Controllers;

use App\Enums\Era;
use App\Enums\NotesVisibility;
use App\Http\Requests\CharacterAttributeUpdateRequest;
use App\Http\Requests\CharacterBackstoryUpdateRequest;
use App\Http\Requests\CharacterSkillUpdateRequest;
use App\Http\Requests\CharacterStoreRequest;
use App\Http\Requests\CharacterUpdateRequest;
use App\Misc\CharacterImage;
use App\Misc\CharacterSheet;
use App\Models\Character;
use App\Models\Game;
use App\Models\Skill;
use App\Models\StorageLocation;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class CharacterController extends Controller
{
    use AuthorizesRequests;

    public function show(Character $character, Request $request): Response
    {
        $this->authorize('view', $character);

        $availableSkills = Skill::whereNotIn('id', $character->skills->pluck('id'))
            ->orderBy('display_name')
            ->get();

        $character->load('equipment');

        return Inertia::render('Character', [
            ...compact('character', 'availableSkills'),
            'notepad' => [
                'content'          => $request->user()->can('viewNotes', $character) ? $character->notes : null,
                'visibility'       => ($character->notes_visibility ?? NotesVisibility::Everyone)->value,
                'canView'          => $request->user()->can('viewNotes', $character),
                'canEdit'          => $request->user()->can('updateNotes', $character),
                'canSetVisibility' => ! $character->trashed() && $request->user()->can('manageNotesVisibility', $character),
            ],
            'storageLocations'     => StorageLocation::query()->orderBy('order_by')->orderBy('name')->get(['id', 'name']),
            'alwaysRelevantSkills' => config('cthulhu.sheet.always_relevant_skills'),
            // The era of the game being played. The sheet leads with what
            // belongs to it and keeps the rest one click away rather than
            // hiding it: a Keeper running a 1920s game may still hand
            // somebody a Garand.
            'era'  => $character->era()->value,
            'eras' => Era::options(),
            // Every campaign the group has, so the player can move this
            // investigator between them.
            'games' => $this->gameOptions($character),
        ]);
    }

    /**
     * The group's campaigns, marking the one it is playing. Empty while the
     * character has no group — there is nothing to join yet.
     *
     * @return array<int, array{id: int, name: string, era: string, active: bool}>
     */
    private function gameOptions(Character $character): array
    {
        if ($character->group_id === null) {
            return [];
        }

        $activeGameId = $character->group?->active_game_id;

        return Game::query()
            ->where('group_id', $character->group_id)
            ->orderByDesc('id')
            ->get()
            ->map(fn (Game $game): array => [
                'id'     => $game->id,
                'name'   => $game->name,
                'era'    => $game->era->value,
                'active' => $game->id === $activeGameId,
            ])
            ->all();
    }

    /**
     * Which of the group's campaigns this investigator is played in.
     *
     * A player moves their own sheet between games; a Keeper may move any in
     * their group, on the same terms as every other edit to a sheet. Games
     * belonging to another group are refused rather than silently dropped.
     */
    public function updateGames(Character $character, Request $request): RedirectResponse
    {
        $this->authorize('update', $character);

        $allowed = $character->group_id === null
            ? []
            : Game::query()->where('group_id', $character->group_id)->pluck('id')->all();

        $validated = $request->validate([
            'games'   => ['present', 'array'],
            'games.*' => ['integer', Rule::in($allowed)],
        ], [
            'games.*.in' => 'That game is not one of your group’s.',
        ]);

        $character->games()->sync($validated['games']);

        return back()->with('success', 'Games updated.');
    }

    /**
     * Set what the investigator is carrying and what they are worth.
     *
     * Both figures are typed straight over: a player is trusted with their own
     * money, and half of what happens to it at a table happens off the sheet —
     * a wallet lifted, a fee collected, a horse sold. Writing either settles
     * both columns, so the numbers stop following the Credit Rating band; see
     * `Character::wealth`.
     */
    public function updateWealth(Character $character, Request $request): RedirectResponse
    {
        $this->authorize('update', $character);

        $validated = $request->validate([
            'cash'   => ['nullable', 'numeric', 'min:-99999999', 'max:99999999'],
            'assets' => ['nullable', 'numeric', 'min:-99999999', 'max:99999999'],
        ]);

        $wealth = $character->wealth;

        $character->update([
            'cash'   => round((float) ($validated['cash'] ?? $wealth['cash']), 2),
            'assets' => round((float) ($validated['assets'] ?? $wealth['assets']), 2),
        ]);

        return back()->with('success', 'Wealth updated.');
    }

    /**
     * The printable sheet.
     *
     * This is the one page in the player-facing app that is plain Blade rather
     * than Inertia: it has to be a self-contained document the browser can send
     * straight to a printer or "Save as PDF" without the app chrome around it.
     */
    public function sheet(Character $character): View
    {
        $this->authorize('view', $character);

        $character->loadMissing('skills', 'weapons', 'equipment', 'player');

        return view('character.sheet', [
            'character' => $character,
            // Printing a broken image on a document somebody is about to take to
            // a table is worse than printing the empty frame, so the file behind
            // the path is checked rather than assumed — see CharacterImage::url().
            'portrait'        => CharacterImage::url($character->avatar),
            'characteristics' => CharacterSheet::characteristics($character),
            'skillColumns'    => CharacterSheet::skillColumns($character),
            'wealth'          => $character->wealth,
            'possessions'     => CharacterSheet::possessions($character),
            'fellows'         => $character->group_id === null
                ? new EloquentCollection()
                : Character::with('player')
                    ->investigators()
                    ->where('id', '!=', $character->id)
                    ->where('group_id', $character->group_id)
                    ->whereNull('deleted_at')
                    ->orderBy('name')
                    ->take(6)
                    ->get(),
        ]);
    }

    public function store(CharacterStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $character           = Character::make($validated);
        $character->slug     = Character::uniqueSlug($validated['name']);
        $character->group_id = $request->user()->group_id;
        $character->save();
        $character->refresh();
        $character->addAllSkills();

        // A new investigator joins the campaign the group is playing, so they
        // show up under Characters rather than in limbo.
        $activeGameId = $request->user()->group?->active_game_id;

        if ($activeGameId !== null) {
            $character->games()->syncWithoutDetaching([$activeGameId]);
        }

        return to_route('character.show', $character->slug);
    }

    public function updateNotes(Character $character, Request $request): RedirectResponse
    {
        $this->authorize('updateNotes', $character);

        $validated = $request->validate([
            'notes'            => ['present', 'nullable', 'string', 'max:200000'],
            'notes_visibility' => ['sometimes', 'required', Rule::enum(NotesVisibility::class)],
        ]);

        if (array_key_exists('notes_visibility', $validated)) {
            $this->authorize('manageNotesVisibility', $character);
        }

        $character->update($validated);

        return back()->with('success', 'Notes saved.');
    }

    public function aptitude(Character $character, Skill $skill): int
    {
        $this->authorize('view', $character);

        return (int) $character->skills()->where('skill_id', $skill->id)->first()?->pivot->value ?? 0;
    }

    public function update(Character $character, CharacterUpdateRequest $request): RedirectResponse
    {
        // Older open tabs still save through the general sheet endpoint.
        if ($request->exists('notes')) {
            $this->authorize('updateNotes', $character);
        }

        $character->update($request->validated());

        return to_route('character.show', $character->slug);
    }

    /**
     * Merge the submitted backstory keys into the stored backstory array,
     * only overwriting the keys present in the request.
     */
    public function updateBackstory(Character $character, CharacterBackstoryUpdateRequest $request): RedirectResponse
    {
        $character->update([
            'backstory' => array_merge($character->backstory ?? [], $request->validated()),
        ]);

        return to_route('character.show', $character->slug);
    }

    public function updateSkill(Character $character, Skill $skill, CharacterSkillUpdateRequest $request): RedirectResponse
    {
        $character->skills()->updateExistingPivot($skill->id, [
            'value' => $request->validated('value'),
            'show'  => $request->validated('show'),
        ]);

        return to_route('character.show', $character->slug);
    }

    public function attachSkill(Character $character, Skill $skill, Request $request): RedirectResponse
    {
        $this->authorize('update', $character);

        $validated = $request->validate([
            'value' => ['required', 'integer'],
        ]);

        $character->skills()->syncWithoutDetaching([$skill->id => ['value' => $validated['value']]]);

        return to_route('character.show', $character->slug);
    }

    public function removeSkill(Character $character, Skill $skill): RedirectResponse
    {
        $this->authorize('update', $character);

        $character->skills()->detach($skill->id);

        return to_route('character.show', $character->slug);
    }

    public function updateAttribute(Character $character, CharacterAttributeUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $character->update([
            $validated['attribute'] => $validated['value'],
        ]);

        if (strtolower($validated['attribute']) === 'name') {
            $character->update(['slug' => Character::uniqueSlug($validated['value'], $character->id)]);
        }

        return to_route('character.show', $character->slug);
    }

    public function renameCharacter(Character $character, Request $request): RedirectResponse
    {
        $this->authorize('update', $character);

        $validated = $request->validate([
            'value' => ['required', 'string'],
        ]);

        $character->update([
            'name' => $validated['value'],
            'slug' => Character::uniqueSlug($validated['value'], $character->id),
        ]);

        return to_route('character.show', $character->slug);
    }

    /**
     * Put a picture on the sheet — the likeness, or the scene behind the name.
     *
     * One action for both, because they differ only in the box the picture is
     * scaled down to fit; {@see CharacterImage} holds that and everything else
     * about taking an upload in. Nothing is refused for being large: it is made
     * smaller. What can still be refused says why, and the sheet shows it.
     */
    public function image(Character $character, Request $request, string $shape): RedirectResponse
    {
        $this->authorize('update', $character);

        abort_unless(in_array($shape, CharacterImage::shapes(), true), 404);

        $request->validate(CharacterImage::rules(), CharacterImage::messages());

        try {
            $path = CharacterImage::store($request->file('image'), $character, $shape);
        } catch (Throwable) {
            // GD could not make sense of the file, whatever its extension said.
            throw ValidationException::withMessages([
                'image' => 'That picture could not be read. Save it again as a JPEG or a PNG and try once more.',
            ]);
        }

        $character->update([CharacterImage::column($shape) => $path]);

        return to_route('character.show', $character->slug);
    }

    /**
     * Take a picture back off the sheet.
     *
     * The likeness simply goes; the backdrop goes back to the house picture,
     * because a sheet is never without one.
     */
    public function destroyImage(Character $character, string $shape): RedirectResponse
    {
        $this->authorize('update', $character);

        abort_unless(in_array($shape, CharacterImage::shapes(), true), 404);

        CharacterImage::clear($character, $shape);

        return to_route('character.show', $character->slug);
    }

    /**
     * Delete a sheet, but keep it.
     *
     * An investigator is a season's worth of play, and a player deleting one at
     * midnight is not always sure. So this is a soft delete: the sheet stays in
     * the list with its name struck through, and opening it shows the stamp and
     * the two ways out — {@see restore()} and {@see forceDestroy()}. Nothing is
     * detached, so a restored investigator comes back with their skills, their
     * belongings and the campaigns they were played in.
     */
    public function destroy(Character $character): RedirectResponse
    {
        $this->authorize('delete', $character);

        $character->delete();

        return to_route('dashboard')
            ->with('success', $character->name.' was deleted. The sheet is kept — open it from the list to restore it.');
    }

    /**
     * Bring a deleted sheet back, exactly as it was.
     */
    public function restore(Character $character): RedirectResponse
    {
        $this->authorize('restore', $character);

        $character->restore();

        return to_route('character.show', $character->slug)
            ->with('success', $character->name.' is back.');
    }

    /**
     * Finish off a sheet that was already deleted — this is the one that cannot
     * be undone. {@see \App\Models\Character::purge()} takes the skills, the
     * belongings and the campaigns with it.
     */
    public function forceDestroy(Character $character): RedirectResponse
    {
        $this->authorize('forceDelete', $character);

        $name = $character->name;

        $character->purge();

        return to_route('dashboard')->with('success', $name.' is gone for good.');
    }
}
