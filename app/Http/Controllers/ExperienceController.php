<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Skill;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

/**
 * Experience checks — the house rule marks a skill earns on a successful roll.
 * Once they reach a tenth of the skill's value the investigator may roll to
 * improve it, so the sheet only ever needs to count them up, down and away.
 */
class ExperienceController extends Controller
{
    use AuthorizesRequests;

    /**
     * Far past anything a campaign will reach, but the pivot column is a tiny
     * integer and the tally has to stay drawable.
     */
    private const MAX_CHECKS = 99;

    public function increment(Character $character, Skill $skill): JsonResponse
    {
        return $this->apply($character, $skill, fn (int $checks): int => $checks + 1);
    }

    public function decrement(Character $character, Skill $skill): JsonResponse
    {
        return $this->apply($character, $skill, fn (int $checks): int => $checks - 1);
    }

    public function reset(Character $character, Skill $skill): JsonResponse
    {
        return $this->apply($character, $skill, fn (int $checks): int => 0);
    }

    /**
     * Move one skill's checks and hand back the figure that was actually
     * stored. Clamping happens here, so a sheet never has to guess what a
     * button did — and a check that was never earned cannot be given back.
     *
     * @param callable(int): int $change
     */
    private function apply(Character $character, Skill $skill, callable $change): JsonResponse
    {
        $this->authorize('update', $character);

        $current = (int) $character->skills()->findOrFail($skill->id)->pivot->experience;

        $checks = max(0, min(self::MAX_CHECKS, $change($current)));

        $character->skills()->updateExistingPivot($skill->id, [
            'experience' => $checks,
        ]);

        return response()->json(['experience' => $checks]);
    }
}
