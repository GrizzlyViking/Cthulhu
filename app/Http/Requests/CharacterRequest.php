<?php

namespace App\Http\Requests;

use App\Enums\Gender;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CharacterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // TODO: implement permissions
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Identity
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'lowercase',
                'alpha_dash:ascii',
                'max:255',
                Rule::unique('characters', 'slug')->ignore($this->route('character')),
            ],
            'occupation' => ['sometimes', 'nullable', 'string', 'max:255'],
            'residence' => ['sometimes', 'nullable', 'string', 'max:255'],
            'birthplace' => ['sometimes', 'nullable', 'string', 'max:255'],
            'age' => ['sometimes', 'nullable', 'integer', 'between:15,90'],
            'gender' => ['sometimes', 'nullable', Rule::enum(Gender::class)],

            // Characteristics (7e creation typically multiples of 5; SIZ/EDU tend to be higher bases)
            'strength' => ['required', 'integer', 'between:15,90', 'multiple_of:5'],
            'dexterity' => ['required', 'integer', 'between:15,90', 'multiple_of:5'],
            'intelligence' => ['required', 'integer', 'between:15,90', 'multiple_of:5'],
            'constitution' => ['required', 'integer', 'between:15,90', 'multiple_of:5'],
            'appearance' => ['required', 'integer', 'between:15,90', 'multiple_of:5'],
            'power' => ['required', 'integer', 'between:15,90', 'multiple_of:5'],
            'size' => ['required', 'integer', 'between:40,90', 'multiple_of:5'],
            'education' => ['required', 'integer', 'between:40,90', 'multiple_of:5'],

            // Derived values / states
            'move_rate' => ['sometimes', 'nullable', 'integer', 'between:1,12'],
            'temporary_insanity' => ['sometimes', 'boolean'],
            'indefinite_insanity' => ['sometimes', 'boolean'],
            'major_wound' => ['sometimes', 'boolean'],
            'unconscious' => ['sometimes', 'boolean'],
            'dying' => ['sometimes', 'boolean'],

            // Pools and combat-related
            // HP for human investigators typically up to ~18; allow a little headroom
            'hit_points' => ['sometimes', 'nullable', 'integer', 'between:1,30'],
            // Sanity usually 0–99 for humans
            'sanity' => ['sometimes', 'nullable', 'integer', 'between:0,99'],
            // Luck 0–99
            'luck' => ['sometimes', 'nullable', 'integer', 'between:0,99'],
            // Magic Points are POW/5; usually <= 18 for humans
            'magic_points' => ['sometimes', 'nullable', 'integer', 'between:0,20'],
            // Dodge is a percentile skill
            'dodge' => ['sometimes', 'nullable', 'integer', 'between:0,99'],
            // Build typically ranges -2..+2 for humans; allow a bit more
            'build' => ['sometimes', 'nullable', 'integer', 'between:-2,6'],
            // Damage bonus as fixed negatives or dice bonuses (common human ranges shown)
            'damage_bonus' => [
                'sometimes',
                'nullable',
                'string',
                'regex:/^(-2|-1|0|\+1D4|\+1D6|\+2D6|\+3D6|\+4D6|\+5D6)$/i',
            ],

            // Media
            'avatar' => ['sometimes', 'nullable', 'image', 'max:2048'],
        ];

    }
}
