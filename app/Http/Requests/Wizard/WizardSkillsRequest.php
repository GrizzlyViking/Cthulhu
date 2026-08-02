<?php

namespace App\Http\Requests\Wizard;

use App\Models\Character;
use App\Models\Occupation;
use App\Models\Skill;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Validation\Validator;

/**
 * Validates the two skill point pools of the character creation wizard.
 *
 * Payload shape:
 * array{
 *     occupation?: array<string, int>,
 *     personal?: array<string, int>,
 * }
 * where keys are skill slugs and values are the points allocated.
 */
class WizardSkillsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'occupation'   => ['sometimes', 'array'],
            'occupation.*' => ['integer', 'min:1'],
            'personal'     => ['sometimes', 'array'],
            'personal.*'   => ['integer', 'min:1'],
        ];
    }

    /**
     * Cross-field validation of the occupation and personal interest pools.
     *
     * @return array<int, callable(Validator): void>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                /** @var Character $character */
                $character = $this->route('character');

                if ($character->occupation_id === null) {
                    $validator->errors()->add('occupation_id', 'Choose an occupation before allocating skill points.');

                    return;
                }

                $occupationPool = $this->allocations('occupation');
                $personalPool   = $this->allocations('personal');

                $this->validateSlugsExist($validator, $occupationPool->keys()->merge($personalPool->keys())->unique());
                $this->validateOccupationPool($validator, $character, $occupationPool);
                $this->validatePersonalPool($validator, $character, $personalPool);
            },
        ];
    }

    /**
     * @return Collection<string, int>
     */
    public function allocations(string $pool): Collection
    {
        return collect($this->input($pool, []))
            ->map(fn (mixed $points): int => (int) $points);
    }

    /**
     * @param Collection<int, string> $slugs
     */
    private function validateSlugsExist(Validator $validator, Collection $slugs): void
    {
        $known = Skill::whereIn('slug', $slugs)->pluck('slug');

        $slugs->diff($known)->each(function (string $slug) use ($validator): void {
            $validator->errors()->add($slug, "Unknown skill [{$slug}].");
        });
    }

    /**
     * @param Collection<string, int> $pool
     */
    private function validateOccupationPool(Validator $validator, Character $character, Collection $pool): void
    {
        /** @var Occupation $occupation */
        $occupation = $character->occupationDetails;

        if ($pool->has('cthulhu_mythos')) {
            $validator->errors()->add('occupation.cthulhu_mythos', 'No skill points may be spent on Cthulhu Mythos.');
        }

        $available = $occupation->skillPointsFor($character);
        if ($pool->sum() > $available) {
            $validator->errors()->add('occupation', "Occupation skill points exceed the available pool of {$available}.");
        }

        $creditRating = (int) $pool->get('credit_rating', 0);
        if ($creditRating < $occupation->credit_rating_min || $creditRating > $occupation->credit_rating_max) {
            $validator->errors()->add(
                'occupation.credit_rating',
                "Credit Rating must be between {$occupation->credit_rating_min} and {$occupation->credit_rating_max} for a {$occupation->name}."
            );
        }

        $skills  = collect($occupation->skills);
        $allowed = $skills->filter(fn (mixed $entry): bool => is_string($entry))
            ->merge($skills->filter(fn (mixed $entry): bool => is_array($entry) && ($entry['type'] ?? null) === 'choice')
                ->flatMap(fn (array $entry): array => $entry['options']))
            ->push('credit_rating')
            ->unique();

        $anySlots = $skills->filter(fn (mixed $entry): bool => is_array($entry) && ($entry['type'] ?? null) === 'any')
            ->sum(fn (array $entry): int => (int) ($entry['count'] ?? 0));

        $extras = $pool->keys()->diff($allowed);
        if ($extras->count() > $anySlots) {
            $validator->errors()->add(
                'occupation',
                "Only {$anySlots} skill(s) outside the occupation list may receive occupation points; got: {$extras->implode(', ')}."
            );
        }
    }

    /**
     * @param Collection<string, int> $pool
     */
    private function validatePersonalPool(Validator $validator, Character $character, Collection $pool): void
    {
        if ($pool->has('cthulhu_mythos')) {
            $validator->errors()->add('personal.cthulhu_mythos', 'No skill points may be spent on Cthulhu Mythos.');
        }

        if ($pool->has('credit_rating')) {
            $validator->errors()->add('personal.credit_rating', 'Credit Rating may only receive occupation skill points.');
        }

        $available = $character->intelligence * 2;
        if ($pool->sum() > $available) {
            $validator->errors()->add('personal', "Personal interest points exceed the available pool of {$available} (INT × 2).");
        }
    }
}
