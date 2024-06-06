<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Skill;
use App\Models\Weapon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CharacterController extends Controller
{
    public function show(Character $character)
    {
        return Inertia::render('Character', compact('character'));
    }

    public function create()
    {
        return Inertia::render('Character/Create');
    }

    public function store(Request $request): RedirectResponse|Response
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:characters',
            'user_id' => 'required|exists:users,id',
            'occupation' => 'required|string',
            'age' => 'required|integer|min:16',
            'gender' => 'required|in:Male,Female,Other',
            'residence' => 'required|string',
            'birthplace' => 'required|string',
        ]);

        $character = Character::make($validated);
        $character->slug = Str::slug($validated['name']);
        $character->save();
        $character->refresh();
        $character->addAllSkills();

        return to_route('character.show', [$character->slug]);
    }

    function updateAttribute(Character $character, Request $request): \Inertia\Response
    {
        $validator = Validator::make($request->all(), [
            'attribute' => 'required',
            'value' => 'required',
        ]);

        $validated = $validator->safe()->only(['character_id', 'attribute', 'value']);

        $character->setAttribute($validated['attribute'], $validated['value']);

        if (strtolower($validated['attribute']) === 'name') {
            $slug = Str::slug($validated['value']);
            $character->slug = $slug;
        }

        $character->save();
        $character->refresh();

        return Inertia::render('Character', ['character' => $character]);
    }

    function updatePivot(Character $character, Skill $skill, string $pivot, Request $request): \Illuminate\Http\Response
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required',
        ]);

        if ($validator->fails()) {
            return response('Error', 400);
        }

        DB::table('character_skill')
            ->where('character_id', $character->id)
            ->where('skill_id', $skill->id)
            ->update([$pivot => $request->get('value')]);

        return \response('OK', 200);
    }

    public function renameCharacter(Character $character, Request $request)
    {
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
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg'
        ]);

        $path = $request->avatar->store('avatars/' . $character->slug, 'public');

        $character->update(['avatar' => $path]);

        return to_route('character.show', [$character->slug]);
    }

    public function destroy(Character $character)
    {
        $character->delete();
        return to_route('dashboard')->with('success', 'Character deleted.');
    }
}
