<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Skill;
use App\Models\Weapon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    function updateAttribute(Character $character, Request $request): \Illuminate\Http\Response|RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'attribute' => 'required',
            'value' => 'required',
        ]);

        if ($validator->fails()) {
            return response('Error', 400);
        }


        $validated = $validator->safe()->only(['character_id', 'attribute', 'value']);

        // $character = Character::find($request['character_id']);
        $character->setAttribute($validated['attribute'], $validated['value']);

        if (strtolower($validated['attribute']) === 'name') {
            $slug = Str::slug($validated['value']);
            $character->slug = $slug;
        }

        $character->save();

        return \response('OK', 200);
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
            ->update(['value' => $request->get('value') ])
        ;

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
            ->update([$pivot => $request->get('value') ])
        ;

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

        return Inertia::location(route('character.show', ['character' => $character->slug]));
    }

    public function incrementExperience(Character $character, Skill $skill)
    {
        DB::table('character_skill')
            ->where('character_id', $character->id)
            ->where('skill_id', $skill->id)
            ->increment('experience')
        ;

        return \response('OK', 200);
    }

    public function resetExperience(Character $character, Skill $skill)
    {
        DB::table('character_skill')
            ->where('character_id', $character->id)
            ->where('skill_id', $skill->id)
            ->update(['experience' => 0])
        ;

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
}
