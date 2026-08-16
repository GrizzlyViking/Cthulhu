<?php

namespace App\Http\Requests;

use App\Enums\Gender;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CharacterAttributeUpdateRequest extends FormRequest
{
    /**
     * Everything the sheet edits in place, and what a value for each has to
     * look like. An attribute missing from here is refused outright, so a
     * field the sheet offers has to be listed or it silently will not save —
     * which is what the Background panel did until the five below were added.
     *
     * The characteristics and the vitals carry no rule of their own: they were
     * always taken as given, and tightening them is a separate question from
     * letting these save at all.
     *
     * @var array<string, array<int, mixed>>
     */
    private const ATTRIBUTES = [
        'hit_points'         => [],
        'sanity'             => [],
        'luck'               => [],
        'magic_points'       => [],
        'temporary_insanity' => [],
        'unconscious'        => [],
        'name'               => [],
        'strength'           => [],
        'dexterity'          => [],
        'intelligence'       => [],
        'constitution'       => [],
        'appearance'         => [],
        'power'              => [],
        'size'               => [],
        'education'          => [],
        'age'                => ['integer', 'between:15,90'],
        'gender'             => [],
        'occupation'         => ['string', 'max:255'],
        'residence'          => ['string', 'max:255'],
        'birthplace'         => ['string', 'max:255'],
    ];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('character'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'attribute' => ['required', 'string', Rule::in(array_keys(self::ATTRIBUTES))],
            'value'     => ['required', ...$this->valueRules()],
        ];
    }

    /**
     * The rules for the value being written, which depend on which attribute is
     * being written to.
     *
     * `gender` is spelled out here rather than in the table above because the
     * column is an enum with a check constraint behind it: anything but a case
     * name is refused by the database as well, so the rule is read off the enum
     * instead of being repeated. `Rule::enum()` cannot do it — `Gender` is a
     * pure enum, and that rule needs a backed one.
     *
     * @return array<int, mixed>
     */
    private function valueRules(): array
    {
        $attribute = $this->input('attribute');

        if ($attribute === 'gender') {
            return [Rule::in(array_column(Gender::cases(), 'name'))];
        }

        return is_string($attribute) ? self::ATTRIBUTES[$attribute] ?? [] : [];
    }
}
