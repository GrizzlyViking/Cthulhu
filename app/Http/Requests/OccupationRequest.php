<?php

namespace App\Http\Requests;

use App\Enums\Era;
use App\Models\Occupation;
use App\Models\Skill;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Validation\Validator;

/**
 * One occupation, however it was written — by an admin on the Occupations page
 * or by a player inventing their own in the wizard. Both reach the same shared
 * list, so both go through the same rules.
 *
 * The stored `skills` column mixes plain slugs with the book's two kinds of
 * slot ("one interpersonal skill", "any one other skill"), which is awkward to
 * validate as one array. The form sends the three apart instead — `skills`,
 * `choices` and the `any_*` pair — and {@see occupationAttributes()} assembles
 * the column from them.
 */
class OccupationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // The routes carry the gate: `admin` plus `reference-data` on the
        // admin side, and the draft's own ownership check in the wizard.
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $occupation = $this->route('occupation');

        return [
            // Retired occupations keep their name, so uniqueness is checked
            // against the table rather than the model — the alternative is
            // passing validation and dying on the database constraint.
            'name'        => ['required', 'string', 'max:255', Rule::unique(Occupation::class, 'name')->ignore($occupation?->id)],
            'description' => ['required', 'string', 'max:2000'],

            // Which eras the occupation makes sense in. Most make sense in
            // both; a Computer Programmer does not belong in 1925.
            'eras'   => ['required', 'array', 'min:1'],
            'eras.*' => [Rule::enum(Era::class)],

            // The skill point pool: components summed, each drawing on the
            // best of the characteristics it allows.
            'skill_points_formula'              => ['required', 'array', 'min:1', 'max:3'],
            'skill_points_formula.*.multiplier' => ['required', 'integer', 'min:1', 'max:4'],
            'skill_points_formula.*.options'    => ['required', 'array', 'min:1', 'max:8'],
            'skill_points_formula.*.options.*'  => ['required', 'string', Rule::in(array_keys(Occupation::CHARACTERISTICS))],

            'credit_rating_min' => ['required', 'integer', 'min:0', 'max:99'],
            'credit_rating_max' => ['required', 'integer', 'min:0', 'max:99', 'gte:credit_rating_min'],

            // The skills the occupation trains, as slugs.
            'skills'   => ['required', 'array', 'min:1', 'max:20'],
            'skills.*' => $this->selectableSkill(),

            // "One interpersonal skill", "a firearms specialisation": pick a
            // number of them from a named set.
            'choices'             => ['sometimes', 'array', 'max:4'],
            'choices.*.count'     => ['required', 'integer', 'min:1', 'max:4'],
            'choices.*.options'   => ['required', 'array', 'min:2', 'max:12'],
            'choices.*.options.*' => $this->selectableSkill(),
            'choices.*.label'     => ['nullable', 'string', 'max:120'],

            // Free slots — "any one other skill as a personal specialty".
            'any_count' => ['nullable', 'integer', 'min:0', 'max:4'],
            'any_label' => ['nullable', 'string', 'max:120'],
        ];
    }

    /**
     * A skill that is actually on the sheets — a retired one would name a
     * skill nobody has, and the skills step would have nowhere to put points.
     */
    private function liveSkill(): Exists
    {
        return Rule::exists(Skill::class, 'slug')->whereNull('deleted_at');
    }

    /**
     * @return array<int, mixed>
     */
    private function selectableSkill(): array
    {
        return ['required', 'string', $this->liveSkill(), Rule::notIn(Occupation::UNSELECTABLE_SKILLS)];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.unique'                        => 'An occupation with that name already exists. If it is retired, restore it instead.',
            'eras.required'                      => 'Pick at least one era, or no group would ever see the occupation.',
            'eras.min'                           => 'Pick at least one era, or no group would ever see the occupation.',
            'skill_points_formula.min'           => 'An occupation needs at least one characteristic to draw its skill points from.',
            'skill_points_formula.*.options.min' => 'Each part of the formula needs at least one characteristic.',
            'credit_rating_max.gte'              => 'The Credit Rating maximum cannot be below the minimum.',
            'skills.min'                         => 'An occupation needs at least one skill.',
            'skills.*.not_in'                    => 'Credit Rating and Cthulhu Mythos cannot be occupation skills — Credit Rating has its own range above, and no points may ever go on the Mythos.',
            'choices.*.options.*.not_in'         => 'Credit Rating and Cthulhu Mythos cannot be offered as a choice.',
            'choices.*.options.min'              => 'A choice is only a choice with two or more skills to pick from.',
        ];
    }

    /**
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                // A choice that has to be made from fewer skills than it asks
                // for is unfillable, and would strand the player on the skills
                // step with no way forward.
                foreach ($this->input('choices', []) as $index => $choice) {
                    $options = is_array($choice['options'] ?? null) ? $choice['options'] : [];

                    if (($choice['count'] ?? 0) > count($options)) {
                        $validator->errors()->add(
                            "choices.{$index}.count",
                            'Asking for more skills than the choice offers leaves the player nowhere to go.'
                        );
                    }
                }
            },
        ];
    }

    /**
     * The occupation as it is stored: the validated fields, with the three
     * kinds of skill entry folded back into the one `skills` column.
     *
     * @return array<string, mixed>
     */
    public function occupationAttributes(): array
    {
        $validated = $this->validated();

        $choices = collect($validated['choices'] ?? [])
            ->map(fn (array $choice): array => [
                'type'    => 'choice',
                'count'   => (int) $choice['count'],
                'options' => array_values($choice['options']),
                'label'   => trim((string) ($choice['label'] ?? '')) ?: self::describeChoice((int) $choice['count']),
            ]);

        $anyCount = (int) ($validated['any_count'] ?? 0);

        $any = $anyCount > 0
            ? [[
                'type'  => 'any',
                'count' => $anyCount,
                'label' => trim((string) ($validated['any_label'] ?? '')) ?: self::describeAny($anyCount),
            ]]
            : [];

        return [
            'name'                 => $validated['name'],
            'description'          => $validated['description'],
            'eras'                 => array_values($validated['eras']),
            'skill_points_formula' => array_values(array_map(
                fn (array $component): array => [
                    'multiplier' => (int) $component['multiplier'],
                    'options'    => array_values(array_unique($component['options'])),
                ],
                $validated['skill_points_formula'],
            )),
            'credit_rating_min' => (int) $validated['credit_rating_min'],
            'credit_rating_max' => (int) $validated['credit_rating_max'],
            'skills'            => [
                ...array_values(array_unique($validated['skills'])),
                ...$choices->all(),
                ...$any,
            ],
        ];
    }

    private static function describeChoice(int $count): string
    {
        return $count === 1 ? 'one of these skills' : "{$count} of these skills";
    }

    private static function describeAny(int $count): string
    {
        return $count === 1 ? 'any one other skill' : "any {$count} other skills";
    }
}
