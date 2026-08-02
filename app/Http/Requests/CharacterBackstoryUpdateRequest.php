<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CharacterBackstoryUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('character'));
    }

    /**
     * Unlike the wizard endpoint this works on completed characters: the
     * backstory keeps evolving during play (injuries, phobias, gear).
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'personal_description'  => ['sometimes', 'nullable', 'string', 'max:2000'],
            'ideology'              => ['sometimes', 'nullable', 'string', 'max:2000'],
            'significant_people'    => ['sometimes', 'nullable', 'string', 'max:2000'],
            'meaningful_locations'  => ['sometimes', 'nullable', 'string', 'max:2000'],
            'treasured_possessions' => ['sometimes', 'nullable', 'string', 'max:2000'],
            'traits'                => ['sometimes', 'nullable', 'string', 'max:2000'],
            'injuries_scars'        => ['sometimes', 'nullable', 'string', 'max:2000'],
            'phobias_manias'        => ['sometimes', 'nullable', 'string', 'max:2000'],
            'key_connection'        => ['sometimes', 'nullable', 'string', 'max:2000'],
            'gear'                  => ['sometimes', 'nullable', 'string', 'max:2000'],
        ];
    }
}
