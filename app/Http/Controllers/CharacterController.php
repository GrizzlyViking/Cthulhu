<?php

namespace App\Http\Controllers;

use App\Http\Requests\CharacterAttributeUpdateRequest;
use App\Http\Requests\CharacterSkillUpdateRequest;
use App\Http\Requests\CharacterStoreRequest;
use App\Http\Requests\CharacterUpdateRequest;
use App\Models\Character;
use App\Models\Skill;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class CharacterController extends Controller
{
    use AuthorizesRequests;

    public function show(Character $character): Response
    {
        $this->authorize('view', $character);

        return Inertia::render('Character', compact('character'));
    }

    public function create(): Response
    {
        $this->authorize('create', Character::class);

        return Inertia::render('Character/Create');
    }

    public function store(CharacterStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $character       = Character::make($validated);
        $character->slug = Str::slug($validated['name']);
        $character->save();
        $character->refresh();
        $character->addAllSkills();

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
