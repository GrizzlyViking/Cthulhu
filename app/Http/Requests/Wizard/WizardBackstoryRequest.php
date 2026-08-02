<?php

namespace App\Http\Requests\Wizard;

use Illuminate\Foundation\Http\FormRequest;

class WizardBackstoryRequest extends FormRequest
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
            'personal_description'  => ['nullable', 'string', 'max:2000'],
            'ideology'              => ['nullable', 'string', 'max:2000'],
            'significant_people'    => ['nullable', 'string', 'max:2000'],
            'meaningful_locations'  => ['nullable', 'string', 'max:2000'],
            'treasured_possessions' => ['nullable', 'string', 'max:2000'],
            'traits'                => ['nullable', 'string', 'max:2000'],
            'injuries_scars'        => ['nullable', 'string', 'max:2000'],
            'phobias_manias'        => ['nullable', 'string', 'max:2000'],
            'key_connection'        => ['nullable', 'string', 'max:2000'],
            'gear'                  => ['nullable', 'string', 'max:2000'],
        ];
    }
}
