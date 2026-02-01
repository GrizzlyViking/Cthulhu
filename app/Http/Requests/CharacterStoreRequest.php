<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'name'       => ['required', 'string', 'max:255', 'unique:characters'],
            'user_id'    => ['required', 'exists:users,id'],
            'occupation' => ['required', 'string'],
            'age'        => ['required', 'integer', 'min:16'],
            'gender'     => ['required', 'in:Male,Female,Other'],
            'residence'  => ['required', 'string'],
            'birthplace' => ['required', 'string'],
        ];
    }
}
