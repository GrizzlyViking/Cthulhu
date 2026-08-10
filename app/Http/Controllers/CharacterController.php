<?php

namespace App\Http\Controllers;

use App\Enums\Era;
use App\Http\Requests\CharacterAttributeUpdateRequest;
use App\Http\Requests\CharacterBackstoryUpdateRequest;
use App\Http\Requests\CharacterSkillUpdateRequest;
use App\Http\Requests\CharacterStoreRequest;
use App\Http\Requests\CharacterUpdateRequest;
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
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class CharacterController extends Controller
{
    use AuthorizesRequests;

    public function show(Character $character): Response
    {
        $this->authorize('view', $character);

        $availableSkills = Skill::whereNotIn('id', $character->skills->pluck('id'))
            ->orderBy('display_name')
            ->get();

        $character->load('equipment');

        return Inertia::render('Character', [
            ...compact('character', 'availableSkills'),
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
     * The printable sheet.
     *
     * This is the one page in the player-facing app that is plain Blade rather
     * than Inertia: it has to be a self-contained document the browser can send
     * straight to a printer or "Save as PDF" without the app chrome around it.
     */
    public function sheet(Character $character): View
    {
        $this->authorize('view', $character);

        $character->loadMissing('skills', 'weapons', 'player');

        return view('character.sheet', [
            'character'       => $character,
            'characteristics' => CharacterSheet::characteristics($character),
            'skillColumns'    => CharacterSheet::skillColumns($character),
            'wealth'          => CharacterSheet::wealth($character->creditRating()),
            'fellows'         => $character->group_id === null
                ? new EloquentCollection()
                : Character::with('player')
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
        $character->slug     = Str::slug($validated['name']);
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

    public function aptitude(Character $character, Skill $skill): int
    {
        $this->authorize('view', $character);

        return (int) $character->skills()->where('skill_id', $skill->id)->first()?->pivot->value ?? 0;
    }

    public function update(Character $character, CharacterUpdateRequest $request): RedirectResponse
    {
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
            $character->update(['slug' => Str::slug($validated['value'])]);
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
            'slug' => Str::slug($validated['value']),
        ]);

        return to_route('character.show', $character->slug);
    }

    public function avatar(Character $character, Request $request): RedirectResponse
    {
        $this->authorize('update', $character);

        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
        ]);

        $path = $request->file('avatar')->store('avatars/'.$character->slug, 'public');

        $character->update(['avatar' => $path]);

        return to_route('character.show', $character->slug);
    }

    public function destroy(Character $character): RedirectResponse
    {
        $this->authorize('delete', $character);

        $character->delete();

        return to_route('dashboard')->with('success', 'Character deleted.');
    }
}
