<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Era;
use App\Http\Requests\OccupationRequest;
use App\Models\Occupation;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * The occupation list. Like skills and the armoury it is one list for the whole
 * server, so the write actions sit behind `cthulhu.admin.edit_reference_data` —
 * see config/cthulhu.php for when to turn that off.
 *
 * Unlike those, the list also grows from play: a player who writes their own
 * occupation in the wizard adds it here, marked `is_custom`. Filtering to those
 * is how an admin finds what to tidy, rename or retire.
 *
 * Deletes are soft. A retired occupation keeps its id, so the investigators who
 * trained as it still read as what they are; it simply stops being offered.
 */
class OccupationController extends AdminController
{
    use SearchesCaseInsensitively;

    public function index(Request $request): Response
    {
        $search  = trim((string) $request->query('search', ''));
        $era     = Era::tryFrom((string) $request->query('era', ''));
        $trashed = $request->boolean('trashed');
        $custom  = $request->boolean('custom');

        $occupations = Occupation::query()
            ->when($trashed, fn (Builder $query) => $query->onlyTrashed())
            ->when($custom, fn (Builder $query) => $query->where('is_custom', true))
            ->when($search !== '', function (Builder $query) use ($search): void {
                $this->whereAnyLike($query, ['name', 'description'], $search);
            })
            ->inEra($era)
            ->with('creator:id,name')
            ->withCount('characters')
            ->orderBy('name')
            ->paginate(25)
            ->withQueryString()
            ->through(fn (Occupation $occupation): array => [
                ...$occupation->toWizardArray(),
                'characters_count' => $occupation->characters_count,
                'creator_name'     => $occupation->creator?->name,
            ]);

        return Inertia::render('Admin/Occupations', [
            'occupations' => $occupations,
            'eras'        => Era::options(),
            // The whole skill list, not the era's: an occupation may be marked
            // for an era this group is not playing and still needs its skills.
            'skillOptions' => Skill::query()
                ->whereNotIn('slug', Occupation::UNSELECTABLE_SKILLS)
                ->orderBy('display_name')
                ->get(['slug', 'display_name'])
                ->map(fn (Skill $skill): array => [
                    'slug'  => $skill->slug,
                    'label' => $skill->display_name,
                ]),
            'characteristics' => Occupation::CHARACTERISTICS,
            'filters'         => [
                'search'  => $search,
                'era'     => (string) $era?->value,
                'trashed' => $trashed,
                'custom'  => $custom,
            ],
            'editable' => $this->referenceDataIsEditable(),
            'counts'   => [
                'active'  => Occupation::query()->count(),
                'custom'  => Occupation::query()->where('is_custom', true)->count(),
                'retired' => Occupation::onlyTrashed()->count(),
            ],
        ]);
    }

    public function store(OccupationRequest $request): RedirectResponse
    {
        $occupation = Occupation::create($request->occupationAttributes());

        return back()->with('success', "Occupation “{$occupation->name}” added.");
    }

    /**
     * Editing leaves `is_custom` and the author alone: who wrote it is a fact
     * about where it came from, not something an edit changes.
     */
    public function update(OccupationRequest $request, Occupation $occupation): RedirectResponse
    {
        $occupation->update($request->occupationAttributes());

        return back()->with('success', "Occupation “{$occupation->name}” updated.");
    }

    public function destroy(Occupation $occupation): RedirectResponse
    {
        $occupation->delete();

        return back()->with('success', "Occupation “{$occupation->name}” retired. Restore it from the retired list.");
    }

    public function restore(int $id): RedirectResponse
    {
        $occupation = Occupation::onlyTrashed()->findOrFail($id);

        $occupation->restore();

        return back()->with('success', "Occupation “{$occupation->name}” restored.");
    }
}
