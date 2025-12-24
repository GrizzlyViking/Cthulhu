<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SkillController extends Controller
{
    private array $rolls;

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'display_name'   => 'required|string|max:255|unique:skills',
            'starting_value' => 'required|integer',
            'value_obtained' => 'sometimes|integer',
            'character_id'   => 'sometimes|integer|exists:characters,id',
        ]);

        $skill       = Skill::make($validated->validated());
        $skill->slug = Str::slug($request->display_name);
        $skill->save();

        $skill->refresh();

        if (! $skill->id) {
            return response('Error, skill not created', 400);
        }

        $character = Character::find($request->character_id);
        $character->skills()->attach($skill->id);
        $character->refresh();

        return to_route('character.show', $character->slug);
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
            'users'      => 'required|array',
            'users.*'    => 'integer|exists:users,id',
        ]);

        $skill = Skill::where('slug', $request->get('skill_slug'))->first();

        if (! $skill) {
            return response('Error, skill not found', 400);
        }

        collect($request->get('users'))->each(function ($user_id) use ($request) {
            $user = User::find($user_id);
            if ($user instanceof User) {
                $user->characters->first()->skills
                    ->filter(fn (Skill $skill) => $skill->slug === $request->get('skill_slug'))
                    ->each(function (Skill $skill) use ($user) {
                        $roll   = rand(1, 100);
                        $result = '';
                        if ($roll >= 99) {
                            $result = 'Critical Failure';
                        } elseif ($roll > $skill->pivot->value) {
                            $result = 'Failure';
                        } elseif ($roll === 1) {
                            $result = 'Critical Success';
                        } elseif ($roll <= ceil($skill->pivot->value / 5)) {
                            $result = 'Extreme Success';
                        } elseif ($roll <= ceil($skill->pivot->value / 2)) {
                            $result = 'Hard Success';
                        } elseif ($roll <= $skill->pivot->value) {
                            $result = 'Success';
                        }

                        $this->rolls[] = sprintf('%s using: %s rolled: %s against %s, outcome was %s',
                            $user->name,
                            $skill->display_name,
                            intval($roll),
                            $skill->pivot->value,
                            $result
                        );
                    });
            }
        });

        return response('ok', 200)->json($this->rolls);
    }
}
