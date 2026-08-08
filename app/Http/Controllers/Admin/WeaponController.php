<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Era;
use App\Misc\WeaponTable;
use App\Models\Skill;
use App\Models\Weapon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

/**
 * The armoury — the Investigator Handbook weapons table (pp. 250–254) plus
 * whatever this server has added to it. Shared by every group, so the write
 * actions are gated behind `cthulhu.admin.edit_reference_data`; see
 * config/cthulhu.php.
 *
 * Deletes are soft: a retired weapon keeps its id and its `equipables` rows, so
 * it disappears from the sheets that carry it and returns, with its ammunition,
 * when restored.
 */
class WeaponController extends AdminController
{
    use SearchesCaseInsensitively;

    public function index(Request $request): Response
    {
        $search   = trim((string) $request->query('search', ''));
        $category = (string) $request->query('category', '');
        $era      = Era::tryFrom((string) $request->query('era', ''));
        $trashed  = $request->boolean('trashed');

        $categories = WeaponTable::categories();

        $weapons = Weapon::query()
            ->when($trashed, fn (Builder $query) => $query->onlyTrashed())
            ->when($search !== '', function (Builder $query) use ($search): void {
                $this->whereAnyLike($query, ['name', 'skill', 'damage'], $search);
            })
            ->when(in_array($category, $categories, true), fn (Builder $query) => $query->where('category', $category))
            // Filtering goes through `eras`, the list read off the handbook's
            // availability cell. The cell itself stays as printed — "1920s,
            // Modern", "WWII, Later", "Rare" — and is not something to match on.
            ->inEra($era)
            // Order the way the handbook prints the table, so the groupings read
            // in the same sequence as the book.
            ->orderByRaw($this->categoryOrdering($categories))
            ->orderBy('name')
            ->paginate(25)
            ->withQueryString();

        return Inertia::render('Admin/Weapons', [
            'weapons'    => $weapons,
            'categories' => $categories,
            'eras'       => Era::options(),
            // Every live skill, with the book's combat ones first — a house
            // ruled weapon may well want a house ruled skill.
            'skills' => Skill::query()
                ->orderByRaw('case when slug in ('.implode(',', array_fill(0, count(WeaponTable::skills()), '?')).') then 0 else 1 end', array_keys(WeaponTable::skills()))
                ->orderBy('display_name')
                ->get(['slug', 'display_name']),
            'filters' => [
                'search'   => $search,
                'category' => in_array($category, $categories, true) ? $category : '',
                'era'      => $era?->value ?? '',
                'trashed'  => $trashed,
            ],
            'editable' => $this->referenceDataIsEditable(),
            'counts'   => [
                'active'  => Weapon::query()->count(),
                'retired' => Weapon::onlyTrashed()->count(),
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $weapon = Weapon::create($this->validated($request));

        return back()->with('success', "“{$weapon->name}” added to the armoury.");
    }

    public function update(Request $request, Weapon $weapon): RedirectResponse
    {
        $weapon->update($this->validated($request, $weapon));

        return back()->with('success', "“{$weapon->name}” updated.");
    }

    public function destroy(Weapon $weapon): RedirectResponse
    {
        $weapon->delete();

        return back()->with('success', "“{$weapon->name}” retired. Restore it from the retired list.");
    }

    public function restore(int $id): RedirectResponse
    {
        $weapon = Weapon::onlyTrashed()->findOrFail($id);

        $weapon->restore();

        return back()->with('success', "“{$weapon->name}” restored to the armoury.");
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Weapon $weapon = null): array
    {
        return $request->validate([
            // The weapon sync migration keys on the name, and it does not skip
            // retired rows, so names stay unique across both.
            'name'           => ['required', 'string', 'max:255', Rule::unique(Weapon::class, 'name')->ignore($weapon?->id)],
            'category'       => ['required', 'string', Rule::in(WeaponTable::categories())],
            'skill'          => ['required', 'string', Rule::exists(Skill::class, 'slug')->withoutTrashed()],
            'damage'         => ['required', 'string', 'max:255'],
            'base_range'     => ['required', 'string', 'max:255'],
            'uses_per_round' => ['required', 'string', 'max:255'],
            // Free text in the book: "6", "20/30/32", "25 Squirts", "Varies".
            'bullets_in_mag' => ['nullable', 'string', 'max:255'],
            'cost'           => ['required', 'string', 'max:255'],
            'malfunction'    => ['nullable', 'string', 'max:255'],
            // Also free text: "1920s", "1920s, Modern", "WWII, Later", "Rare".
            // It is the book's note, kept as printed; `eras` is what the app
            // filters on, and the two are set independently so a Keeper can
            // hand a 1920s table a WWII rifle without rewriting the book.
            'era'    => ['nullable', 'string', 'max:255'],
            'eras'   => ['required', 'array', 'min:1'],
            'eras.*' => [Rule::enum(Era::class)],
            'impale' => ['required', 'boolean'],
        ], [
            'name.unique'   => 'A weapon with that name already exists. If it is retired, restore it instead.',
            'skill.exists'  => 'Pick a skill that exists. Retired skills cannot be attached to a weapon.',
            'eras.required' => 'Pick at least one era, or no group could ever carry it.',
            'eras.min'      => 'Pick at least one era, or no group could ever carry it.',
        ]);
    }

    /**
     * A CASE expression putting the categories in handbook order. The list is
     * a hard-coded constant, never user input.
     *
     * @param array<int, string> $categories
     */
    private function categoryOrdering(array $categories): string
    {
        $whens = '';

        foreach ($categories as $position => $category) {
            $whens .= " when '".str_replace("'", "''", $category)."' then {$position}";
        }

        return '(case category'.$whens.' else '.count($categories).' end)';
    }
}
