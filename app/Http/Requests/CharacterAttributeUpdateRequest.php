<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CharacterAttributeUpdateRequest extends FormRequest
{
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
            'attribute' => ['required', 'string', 'in:hit_points,sanity,luck,magic_points,temporary_insanity,unconscious,name,strength,dexterity,intelligence,constitution,appearance,power,size,education'],
            'value'     => ['required'],
        ];
    }
}
