<?php

namespace App\Http\Requests\Wizard;

use Illuminate\Foundation\Http\FormRequest;

class WizardCharacteristicsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Final percentage values: the player rolls physical dice, multiplies by
     * five and applies age modifiers client-side before submitting.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'strength'     => ['required', 'integer', 'between:1,99'],
            'constitution' => ['required', 'integer', 'between:1,99'],
            'size'         => ['required', 'integer', 'between:1,99'],
            'dexterity'    => ['required', 'integer', 'between:1,99'],
            'appearance'   => ['required', 'integer', 'between:1,99'],
            'intelligence' => ['required', 'integer', 'between:1,99'],
            'power'        => ['required', 'integer', 'between:1,99'],
            'education'    => ['required', 'integer', 'between:1,99'],
            'luck'         => ['required', 'integer', 'between:1,90'],
        ];
    }
}
