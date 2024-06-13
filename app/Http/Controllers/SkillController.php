<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use function Pest\Laravel\json;

class SkillController extends Controller
{
    private array $rolls;

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

        return to_route('character.show', $character->slug);
    }

    function update(Character $character, Skill $skill, Request $request): \Illuminate\Http\Response
    {
        $request->validate([
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

    public function roll(Request $request)
    {
        $request->validate([
            'skill_slug' => 'required|string|exists:skills,slug',
            'users' => 'required|array',
            'users.*' => 'integer|exists:users,id',
        ]);

        $skill = Skill::where('slug', $request->get('skill_slug'))->first();

        if (!$skill) {
            return response('Error, skill not found', 400);
        }

        collect($request->get('users'))->each(function ($user_id) use ($request) {
            $user = User::find($user_id);
            $user->characters->first()->skills
                ->filter(fn (Skill $skill) => $skill->slug === $request->get('skill_slug'))
                ->each(function (Skill $skill) use ($user) {
                $roll = rand(1, 100);
                $result = '';
                if ($roll >= 99) {
                        $result = 'Critical Failure';
                    } elseif ($roll > $skill->pivot->value) {
                        $result = 'Failure';
                    } elseif ($roll === 1) {
                        $result = 'Critical Success';
                    } elseif ($roll <= ceil($skill->pivot->value/5)) {
                        $result = 'Extreme Success';
                    } elseif ($roll <= ceil($skill->pivot->value/2)) {
                        $result = 'Hard Success';
                    } elseif ($roll <= $skill->pivot->value) {
                        $result = 'Success';
                    }

                $this->rolls[] = sprintf("%s using: %s rolled: %s against %s, outcome was %s",
                    $user->name,
                    $skill->display_name,
                    intval($roll),
                    $skill->pivot->value,
                    $result
                );
            });
        });

        return response()->json($this->rolls);
    }
}
