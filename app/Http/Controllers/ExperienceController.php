<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Skill;
use Illuminate\Support\Facades\DB;

class ExperienceController extends Controller
{
    public function increment(Character $character, Skill $skill)
    {
        $character->skills()->updateExistingPivot($skill->id, [
            'experience' => DB::raw('experience + 1'),
        ]);

        return response('OK', 200);
    }

    public function reset(Character $character, Skill $skill)
    {
        $character->skills()->updateExistingPivot($skill->id, [
            'experience' => 0,
        ]);

        return response('OK', 200);
    }
}
