<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Skill;
use Illuminate\Support\Facades\DB;

class ExperienceController extends Controller
{
    public function increment(Character $character, Skill $skill)
    {
        DB::table('character_skill')
            ->where('character_id', $character->id)
            ->where('skill_id', $skill->id)
            ->increment('experience');

        return \response('OK', 200);
    }

    public function reset(Character $character, Skill $skill)
    {
        DB::table('character_skill')
            ->where('character_id', $character->id)
            ->where('skill_id', $skill->id)
            ->update(['experience' => 0]);

        return \response('OK', 200);
    }
}
