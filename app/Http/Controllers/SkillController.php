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
use Illuminate\Validation\Rule;

class SkillController extends Controller
{
    use AuthorizesRequests;

    /** @var array<int, string> */
    private array $rolls = [];

    /**
     * Create a skill the canonical list lacks and put it on the sheet that
     * asked for it. The skill itself is shared — every group on the server
     * sees it afterwards — so the name has to clear the same uniqueness
     * checks the admin section applies.
     */
    public function store(Request $request): RedirectResponse
    {
        // The slug is what sheets, weapons and the seeders refer to. It is
        // derived from the name and filled in before validation so it goes
        // through the same uniqueness check as an admin's hand-written one.
        $request->merge(['slug' => Str::slug((string) $request->input('display_name'))]);

        $validated = $request->validate([
            // Rule::unique queries the table rather than the model, so it sees
            // retired skills too — which is what we want, since the unique
            // indexes still cover them.
            'display_name'   => ['required', 'string', 'max:50', Rule::unique(Skill::class, 'display_name')],
            'slug'           => ['required', 'string', 'max:60', 'alpha_dash', Rule::unique(Skill::class, 'slug')],
            'description'    => ['nullable', 'string', 'max:2000'],
            'starting_value' => ['required', 'integer', 'min:0', 'max:100'],
            'value_obtained' => ['nullable', 'integer', 'min:0', 'max:100'],
            'character_id'   => ['required', 'integer', 'exists:characters,id'],
        ], [
            'display_name.unique' => 'A skill with that name already exists. If it is not on this sheet, add it from “Add a skill” instead — and if it has been retired, an admin can restore it.',
            'slug.required'       => 'That name has no letters or numbers to build a slug from — give the skill a different name.',
            'slug.unique'         => 'That name is too close to an existing skill’s — both would share the slug “'.$request->input('slug').'”.',
        ]);

        $character = Character::findOrFail($validated['character_id']);

        $this->authorize('update', $character);

        $skill = Skill::create([
            'slug'           => $validated['slug'],
            'display_name'   => $validated['display_name'],
            'description'    => $validated['description'] ?? null,
            'starting_value' => $validated['starting_value'],
            'order_by'       => Skill::nextOrder(),
        ]);

        $character->skills()->attach($skill->id, [
            'order' => $skill->order_by,
            'value' => $validated['value_obtained'] ?? $validated['starting_value'],
        ]);

        return to_route('character.show', $character->slug)
            ->with('success', "Skill “{$skill->display_name}” created and added to the sheet.");
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
