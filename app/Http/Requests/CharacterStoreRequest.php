<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CharacterStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Character::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Live investigators only: a deleted sheet keeps its slug but
            // not its claim on the name. See WizardProfileRequest.
            'name'       => ['required', 'string', 'max:255', Rule::unique('characters')->whereNull('deleted_at')],
            'user_id'    => ['required', 'exists:users,id'],
            'occupation' => ['required', 'string'],
            'age'        => ['required', 'integer', 'min:16'],
            'gender'     => ['required', 'in:Male,Female,Other'],
            'residence'  => ['required', 'string'],
            'birthplace' => ['required', 'string'],
        ];
    }
}
