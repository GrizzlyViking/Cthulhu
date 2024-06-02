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
    public function create()
    {
        return Inertia::render('Character/Create');
    }

    public function firstStep(Request $request): RedirectResponse|Response
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:characters',
            'user_id' => 'required|exists:users,id',
            'occupation' => 'required|string',
            'age' => 'required|integer',
            'gender' => 'required|in:Male,Female,Other',
            'residence' => 'required|string',
            'birthplace' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response($validator->errors(), 400);
        }

        $validated = $validator->safe()->only(['name', 'user_id', 'occupation', 'age', 'gender', 'residence', 'birthplace']);

        $character = Character::make($validated);
        $character->slug = Str::slug($validated['name']);
        $character->save();
        $character->refresh();
        $character->addAllSkills();

        if ($character->skills->isNotEmpty()) {
            return to_route('character.show', [$character->slug]);
        } else {
            return response($character->toJson(), 400);
        }
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

    function updateSkill(Character $character, Skill $skill, Request $request): \Illuminate\Http\Response
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
            ->update(['value' => $request->get('value')]);

        return \response('OK', 200);
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

    public function incrementExperience(Character $character, Skill $skill)
    {
        DB::table('character_skill')
            ->where('character_id', $character->id)
            ->where('skill_id', $skill->id)
            ->increment('experience');

        return \response('OK', 200);
    }

    public function resetExperience(Character $character, Skill $skill)
    {
        DB::table('character_skill')
            ->where('character_id', $character->id)
            ->where('skill_id', $skill->id)
            ->update(['experience' => 0]);

        return \response('OK', 200);
    }

    public function addWeapon(Character $character, Request $request): \Illuminate\Http\Response
    {
        $validator = Validator::make($request->all(), [
            'weapon_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response('Error', 400);
        }

        $weapon = Weapon::find($request->get('weapon_id'));
        $character->weapons()->attach($weapon);

        return \response('OK', 200);
    }

    public function reloadWeapon(Request $request)
    {
        Validator::make($request->all(), [
           'pivot_id' => 'required|integer',
           'ammo' => 'required|integer',
        ]);

        DB::table('equipables')->where('id', $request->get('pivot_id'))->update(['ammo' => $request->get('ammo')]);

        return \response('OK', 200);
    }

    public function fireWeapon(Request $request)
    {
        Validator::make($request->all(), [
           'pivot_id' => 'required|integer',
        ]);

        DB::table('equipables')->where('id', $request->get('pivot_id'))->update(['ammo' => DB::raw('ammo - 1')]);

        return \response('OK', 200);
    }

    public function avatar(Character $character, Request $request): \Illuminate\Http\Response
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return response('Error', 400);
        }

        $path = $request->avatar->store('avatars/'.$character->slug, 'public');

        $character->update(['avatar' => $path]);

        return \response('updated', 200);
    }
}
