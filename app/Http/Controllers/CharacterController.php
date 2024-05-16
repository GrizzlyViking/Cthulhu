<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Response;

class CharacterController extends Controller
{
    public function update(Request $request)
    {

    }

    function updateAttribute(Character $character, Request $request): \Illuminate\Http\Response
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
}
