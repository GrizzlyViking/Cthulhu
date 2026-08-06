<?php

namespace App\Http\Controllers\Admin;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

/**
 * The canonical skill list. It is shared by every group on the server, so the
 * write actions here are gated behind `cthulhu.admin.edit_reference_data` —
 * see config/cthulhu.php for when to turn that off.
 *
 * Deletes are soft: a retired skill keeps its id and its pivot rows, drops off
 * every character sheet, and comes back onto them when restored.
 */
class SkillController extends AdminController
{
    use SearchesCaseInsensitively;

    public function index(Request $request): Response
    {
        $search  = trim((string) $request->query('search', ''));
        $trashed = $request->boolean('trashed');

        $skills = Skill::query()
            ->when($trashed, fn (Builder $query) => $query->onlyTrashed())
            ->when($search !== '', function (Builder $query) use ($search): void {
                $this->whereAnyLike($query, ['display_name', 'slug', 'description'], $search);
            })
            ->withCount('characters')
            ->orderBy('display_name')
            ->paginate(25)
            ->withQueryString();

        return Inertia::render('Admin/Skills', [
            'skills'   => $skills,
            'filters'  => ['search' => $search, 'trashed' => $trashed],
            'editable' => $this->referenceDataIsEditable(),
            'counts'   => [
                'active'  => Skill::query()->count(),
                'retired' => Skill::onlyTrashed()->count(),
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $skill = Skill::create([
            ...$validated,
            'order_by' => $validated['order_by'] ?? Skill::nextOrder(),
        ]);

        return back()->with('success', "Skill “{$skill->display_name}” added.");
    }

    public function update(Request $request, Skill $skill): RedirectResponse
    {
        $skill->update($this->validated($request, $skill));

        return back()->with('success', "Skill “{$skill->display_name}” updated.");
    }

    /**
     * Retire the skill. It leaves every sheet at once; the pivot rows survive
     * so restoring puts it back with the values each character had.
     */
    public function destroy(Skill $skill): RedirectResponse
    {
        $skill->delete();

        return back()->with('success', "Skill “{$skill->display_name}” retired. Restore it from the retired list.");
    }

    public function restore(string $slug): RedirectResponse
    {
        $skill = Skill::onlyTrashed()->where('slug', $slug)->firstOrFail();

        $skill->restore();

        return back()->with('success', "Skill “{$skill->display_name}” restored.");
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Skill $skill = null): array
    {
        // The slug is what characters, weapons and the seeders refer to, so it
        // is derived from the name unless the admin sets one deliberately —
        // and it is filled in before validation so the derived value goes
        // through the same uniqueness check as a hand-written one.
        $request->merge([
            'slug' => trim((string) $request->input('slug')) !== ''
                ? $request->input('slug')
                : Str::slug((string) $request->input('display_name')),
        ]);

        return $request->validate([
            // Retired skills keep their unique slug and name — Rule::unique
            // queries the table rather than the model, so it sees them, which
            // is what we want: the alternative is passing validation and then
            // dying on the database constraint.
            'display_name'   => ['required', 'string', 'max:255', Rule::unique(Skill::class, 'display_name')->ignore($skill?->id)],
            'slug'           => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique(Skill::class, 'slug')->ignore($skill?->id)],
            'description'    => ['nullable', 'string', 'max:2000'],
            'starting_value' => ['required', 'integer', 'min:0', 'max:100'],
            'order_by'       => ['nullable', 'integer', 'min:0', 'max:255'],
        ], [
            'display_name.unique' => 'A skill with that name already exists. If it is retired, restore it instead.',
            'slug.unique'         => 'That slug is taken. If the skill using it is retired, restore it instead.',
            'slug.required'       => 'The name produced an empty slug — give the skill a slug of its own.',
        ]);
    }
}
