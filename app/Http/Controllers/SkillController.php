<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SkillController extends Controller
{
    use AuthorizesRequests;

    /** @var array<int, string> */
    private array $rolls = [];

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'display_name'   => ['required', 'string', 'max:255', 'unique:skills'],
            'starting_value' => ['required', 'integer'],
            'value_obtained' => ['sometimes', 'integer'],
            'character_id'   => ['required', 'integer', 'exists:characters,id'],
        ]);

        $character = Character::findOrFail($request->character_id);

        $this->authorize('update', $character);

        $skill       = Skill::make($validated);
        $skill->slug = Str::slug($request->display_name);
        $skill->save();

        $character->skills()->attach($skill->id, [
            'value' => $validated['value_obtained'] ?? $validated['starting_value'],
        ]);

        return to_route('character.show', $character->slug);
    }

    public function appendAllMissingSkills(Character $character): RedirectResponse
    {
        $this->authorize('update', $character);

        $character->appendSkills();

        return to_route('character.show', $character->slug);
    }

    public function roll(Request $request): JsonResponse
    {
        $request->validate([
            'skill_slug' => ['required', 'string', 'exists:skills,slug'],
            'users'      => ['required', 'array'],
            'users.*'    => ['integer', 'exists:users,id'],
        ]);

        $skill = Skill::where('slug', $request->get('skill_slug'))->firstOrFail();

        // Rolls never cross group boundaries: targets outside the roller's
        // group are dropped silently.
        $allowedUserIds = User::query()->inGroupOf($request->user())->pluck('id');

        collect($request->get('users'))->filter(fn ($user_id): bool => $allowedUserIds->contains((int) $user_id))->each(function ($user_id) use ($skill) {
            $user = User::find($user_id);
            if ($user instanceof User && $user->characters->first()) {
                $character = $user->characters->first();
                $charSkill = $character->skills()->where('skills.id', $skill->id)->first();

                if ($charSkill) {
                    $roll   = rand(1, 100);
                    $result = match (true) {
                        $roll >= 99                                 => 'Critical Failure',
                        $roll === 1                                 => 'Critical Success',
                        $roll <= ceil($charSkill->pivot->value / 5) => 'Extreme Success',
                        $roll <= ceil($charSkill->pivot->value / 2) => 'Hard Success',
                        $roll <= $charSkill->pivot->value           => 'Success',
                        default                                     => 'Failure',
                    };

                    $this->rolls[] = sprintf('%s using: %s rolled: %d against %d, outcome was %s',
                        $user->name,
                        $charSkill->display_name,
                        $roll,
                        $charSkill->pivot->value,
                        $result
                    );
                }
            }
        });

        return response()->json($this->rolls);
    }
}
