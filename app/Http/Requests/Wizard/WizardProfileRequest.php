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
            /*
             * A deleted sheet does not hold on to its name. It keeps its slug,
             * so restoring it puts it back at the address it always had, but
             * the name is what a player types — and being told that somebody
             * they buried a month ago is in the way, with no way to see them,
             * is nonsense. Two live investigators may not share one.
             */
            'name'       => ['required', 'string', 'max:255', Rule::unique('characters')->whereNull('deleted_at')->ignore($character?->id)],
            'gender'     => ['required', 'in:Male,Female,Other'],
            'age'        => ['required', 'integer', 'between:15,90'],
            'residence'  => ['required', 'string', 'max:255'],
            'birthplace' => ['required', 'string', 'max:255'],
        ];
    }
}
