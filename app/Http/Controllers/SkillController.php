<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SkillController extends Controller
{
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'display_name' => 'required|string|max:255|unique:skills',
            'starting_value' => 'required|integer',
            'character_id' => 'integer|exists:characters,id',
        ]);

        $skill = new Skill();
        $skill->display_name = $request->display_name;
        $skill->starting_value = $request->starting_value;
        $skill->slug = Str::slug($request->display_name);
        $skill->save();

        $skill->refresh();

        if (!$skill->id) {
            return response('Error, skill not created', 400);
        }

        $character = Character::find($request->character_id);
        $character->skills()->attach($skill->id);
        $character->refresh();

        return to_route('character.show', $character);
    }

    function update(Character $character, Skill $skill, Request $request): \Illuminate\Http\Response
    {
        $request->validate($request->all(), [
            'value' => 'required',
        ]);

        DB::table('character_skill')
            ->where('character_id', $character->id)
            ->where('skill_id', $skill->id)
            ->update(['value' => $request->get('value')]);

        return \response('OK', 200);
    }

    public function aptitude(Character $character, Skill $skill)
    {
        return DB::table('character_skill')->where('character_id', $character->id)->where('skill_id', $skill->id)->pluck('value')->first();
    }

    public function appendAllMissingSkills(Character $character)
    {
        $character->appendSkills();

        return to_route('character.show', $character);
    }
}
