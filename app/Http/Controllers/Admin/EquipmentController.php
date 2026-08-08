<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Era;
use App\Misc\EquipmentTable;
use App\Models\EquipmentItem;
use App\Models\StorageLocation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

/**
 * The equipment catalogue and the places investigators keep things. Both are
 * shared by every group on the server, so the writes sit behind
 * `cthulhu.admin.edit_reference_data` alongside skills and weapons.
 *
 * This is also where custom items get pruned: anything a player typed that the
 * handbook did not have lands in the catalogue flagged custom, and somebody has
 * to be able to tidy up after a session.
 */
class EquipmentController extends AdminController
{
    use SearchesCaseInsensitively;

    public function index(Request $request): Response
    {
        $search  = trim((string) $request->query('search', ''));
        $section = (string) $request->query('section', '');
        $era     = Era::tryFrom((string) $request->query('era', ''));
        $trashed = $request->boolean('trashed');
        $custom  = $request->boolean('custom');

        $sections = EquipmentTable::sections();

        $items = EquipmentItem::query()
            ->when($trashed, fn (Builder $query) => $query->onlyTrashed())
            ->when($custom, fn (Builder $query) => $query->where('is_custom', true))
            ->when($search !== '', function (Builder $query) use ($search): void {
                $this->whereAnyLike($query, ['name', 'section'], $search);
            })
            ->when(in_array($section, $sections, true), fn (Builder $query) => $query->where('section', $section))
            ->inEra($era)
            ->withCount('characters')
            ->catalogueOrder()
            ->paginate(25)
            ->withQueryString();

        return Inertia::render('Admin/Equipment', [
            'items'    => $items,
            'sections' => $sections,
            'eras'     => Era::options(),
            'filters'  => [
                'search'  => $search,
                'section' => in_array($section, $sections, true) ? $section : '',
                'era'     => $era?->value ?? '',
                'trashed' => $trashed,
                'custom'  => $custom,
            ],
            'locations' => StorageLocation::query()
                ->orderBy('order_by')
                ->orderBy('name')
                ->withCount('equipables')
                ->get(),
            'editable' => $this->referenceDataIsEditable(),
            'counts'   => [
                'active'  => EquipmentItem::query()->count(),
                'custom'  => EquipmentItem::query()->where('is_custom', true)->count(),
                'retired' => EquipmentItem::onlyTrashed()->count(),
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $item = EquipmentItem::create([
            ...$validated,
            'slug'       => EquipmentItem::customSlug($validated['name']),
            'is_custom'  => true,
            'created_by' => $request->user()->id,
        ]);

        return back()->with('success', "“{$item->name}” added to the catalogue.");
    }

    public function update(Request $request, EquipmentItem $equipment): RedirectResponse
    {
        $equipment->update($this->validated($request, $equipment));

        return back()->with('success', "“{$equipment->name}” updated.");
    }

    /**
     * Retire an item. It leaves the sheets carrying it, and its `equipables`
     * rows survive so restoring puts it back where each investigator had it.
     */
    public function destroy(EquipmentItem $equipment): RedirectResponse
    {
        $equipment->delete();

        return back()->with('success', "“{$equipment->name}” retired. Restore it from the retired list.");
    }

    public function restore(string $slug): RedirectResponse
    {
        $item = EquipmentItem::onlyTrashed()->where('slug', $slug)->firstOrFail();

        $item->restore();

        return back()->with('success', "“{$item->name}” restored.");
    }

    public function storeLocation(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique(StorageLocation::class, 'name')],
        ]);

        StorageLocation::create([
            'slug'     => StorageLocation::uniqueSlug($validated['name']),
            'name'     => $validated['name'],
            'order_by' => StorageLocation::nextOrder(),
        ]);

        return back()->with('success', "“{$validated['name']}” added.");
    }

    public function updateLocation(Request $request, StorageLocation $location): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255', Rule::unique(StorageLocation::class, 'name')->ignore($location->id)],
            'order_by' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ]);

        $location->update($validated);

        return back()->with('success', 'Place updated.');
    }

    /**
     * Retire a place. Anything kept there falls back to "not stored anywhere"
     * rather than disappearing, because the foreign key nulls on delete — but
     * a soft delete leaves the rows pointing at it, so this refuses while
     * anything is still in it.
     */
    public function destroyLocation(StorageLocation $location): RedirectResponse
    {
        $inUse = $location->equipables()->count();

        if ($inUse > 0) {
            return back()->with('error', "“{$location->name}” still holds {$inUse} thing(s). Move them first.");
        }

        $location->delete();

        return back()->with('success', "“{$location->name}” removed.");
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?EquipmentItem $item = null): array
    {
        return $request->validate([
            'name'    => ['required', 'string', 'max:255', Rule::unique(EquipmentItem::class, 'name')->ignore($item?->id)],
            'section' => ['nullable', 'string', 'max:255'],
            // The book's price cell verbatim: "$1.35-$2.25", "9¢/lb.", "79¢".
            'cost' => ['nullable', 'string', 'max:255'],
            // The catalogue is the 1920s lists, but most of what is in it — rope,
            // a compass, a first aid case — would serve a modern table just as
            // well, so this is set per item rather than assumed.
            'eras'   => ['required', 'array', 'min:1'],
            'eras.*' => [Rule::enum(Era::class)],
        ], [
            'name.unique'   => 'Something in the catalogue already has that name. If it is retired, restore it instead.',
            'eras.required' => 'Pick at least one era, or no group could ever buy it.',
            'eras.min'      => 'Pick at least one era, or no group could ever buy it.',
        ]);
    }
}
