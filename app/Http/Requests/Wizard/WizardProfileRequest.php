<?php

namespace App\Http\Requests\Wizard;

use App\Models\Character;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WizardProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        /** @var Character|null $character */
        $character = $this->route('character');

        return [
            'name'       => ['required', 'string', 'max:255', Rule::unique('characters')->ignore($character?->id)],
            'gender'     => ['required', 'in:Male,Female,Other'],
            'age'        => ['required', 'integer', 'between:15,90'],
            'residence'  => ['required', 'string', 'max:255'],
            'birthplace' => ['required', 'string', 'max:255'],
        ];
    }
}
