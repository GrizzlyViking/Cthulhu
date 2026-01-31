<?php

namespace App\Http\Controllers;

use App\Http\Requests\CharacterUpdateRequest;
use App\Models\Character;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class CharacterController extends Controller
{
    use AuthorizesRequests;

    protected function user(): User
    {
        return auth()->user();
    }

    public function show(Character $character)
    {
        $this->authorize('view', $character);

        return Inertia::render('Character', compact('character'));
    }

    public function create()
    {
        $this->authorize('create', Character::class);

        return Inertia::render('Character/Create');
    }

    public function store(Request $request): RedirectResponse|Response
    {
        $this->authorize('create', Character::class);

        $validated = $request->validate([
            'name'       => 'required|string|max:255|unique:characters',
            'user_id'    => 'required|exists:users,id',
            'occupation' => 'required|string',
            'age'        => 'required|integer|min:16',
            'gender'     => 'required|in:Male,Female,Other',
            'residence'  => 'required|string',
            'birthplace' => 'required|string',
        ]);

        $character       = Character::make($validated);
        $character->slug = Str::slug($validated['name']);
        $character->save();
        $character->refresh();
        $character->addAllSkills();

        return to_route('character.show', [$character->slug]);
    }

    public function aptitude(Character $character, Skill $skill)
    {
        $this->authorize('view', $character);

        return DB::table('character_skill')->where('character_id', $character->id)->where('skill_id', $skill->id)->pluck('value')->first();
    }

    public function update(Character $character, CharacterUpdateRequest $request): RedirectResponse
    {
        $character->update($request->validated());

        return to_route('character.show', $character->slug);
    }

    public function updateSkill(Character $character, Skill $skill, Request $request): RedirectResponse
    {
        $this->authorize('patch', $character);

        $validated = $request->validate([
            'value' => ['required', 'integer', 'between:0,100'],
        ]);

        $character->skills()->updateExistingPivot($skill->id, [
            'value' => $validated['value'],
        ]);

        return to_route('character.show', $character->slug);
    }

    public function attachSkill(Request $request): Response
    {
        $validated = $request->validate([
            'character_id' => 'required|integer|exists:characters,id',
            'skill_id'     => 'required|integer|exists:skills,id',
            'value'        => 'required|integer',
        ]);

        $character = Character::findOrFail($validated['character_id']);
        $this->authorize('patch', $character);

        DB::table('character_skill')->insert($validated);

        return response('OK', SymfonyResponse::HTTP_CREATED);
    }

    public function removeSkill(Request $request): Response
    {
        $validated = $request->validate([
            'character_id' => 'required|integer|exists:characters,id',
            'skill_id'     => 'required|integer|exists:skills,id',
        ]);

        $character = Character::findOrFail($validated['character_id']);
        $this->authorize('patch', $character);

        DB::table('character_skill')->where($validated)->delete();

        return response('OK', SymfonyResponse::HTTP_OK);
    }

    public function updateAttribute(Character $character, Request $request): RedirectResponse
    {
        $this->authorize('update', $character);

        $validated = $request->validate([
            'attribute' => ['required', 'string'],
            'value'     => ['required'],
        ]);

        $character->update([
            $validated['attribute'] => $validated['value'],
        ]);

        if (strtolower($validated['attribute']) === 'name') {
            $character->update(['slug' => Str::slug($validated['value'])]);
        }

        return to_route('character.show', $character->slug);
    }

    public function renameCharacter(Character $character, Request $request)
    {
        $this->authorize('patch', $character);

        $validator = Validator::make($request->all(), [
            'value' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response('Error', 400);
        }

        $character->setAttribute('name', $request->get('value'));
        $character->setAttribute('slug', Str::slug($request->get('value')));

        $character->save();
        $character->refresh();

        return to_route('character.show', [$character->slug]);
    }

    public function avatar(Character $character, Request $request)
    {
        $this->authorize('patch', $character);

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $path = $request->avatar->store('avatars/'.$character->slug, 'public');

        $character->update(['avatar' => $path]);

        return to_route('character.show', [$character->slug]);
    }

    public function destroy(Character $character)
    {
        $this->authorize('delete', $character);

        $character->delete();

        return to_route('dashboard')->with('success', 'Character deleted.');
    }
}
