<?php

namespace App\Http\Controllers;

use App\Enums\Era;
use App\Misc\EquipmentTable;
use App\Models\Character;
use App\Models\EquipmentItem;
use App\Models\StorageLocation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * What an investigator owns, beyond their weapons.
 *
 * Possessions live in the `equipables` pivot alongside weapons, so both kinds
 * carry the same "where is it kept" column and the Equipment tab can list them
 * together.
 */
class EquipmentController extends Controller
{
    use AuthorizesRequests;

    /**
     * Typeahead over the catalogue. Answers JSON so the field can suggest as
     * the player types without a page visit.
     */
    public function search(Request $request): JsonResponse
    {
        $search = trim((string) $request->query('search', ''));

        // The catalogue spans both eras, so the sheet says which one it is
        // shopping in. Passing nothing — or asking for every era outright —
        // searches the lot.
        $era = $request->boolean('all_eras')
            ? null
            : Era::tryFrom((string) $request->query('era', ''));

        $items = EquipmentItem::query()
            ->inEra($era)
            ->when($search !== '', function (Builder $query) use ($search): void {
                $term = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], mb_strtolower($search)).'%';

                $query->where(function (Builder $query) use ($term): void {
                    $query->orWhereRaw('lower(name) like ? escape \'\\\'', [$term])
                        ->orWhereRaw('lower(section) like ? escape \'\\\'', [$term]);
                });
            })
            ->catalogueOrder()
            ->limit(40)
            ->get(['id', 'name', 'section', 'cost', 'eras', 'is_custom']);

        return response()->json([
            'items'    => $items,
            'sections' => EquipmentTable::sections(),
        ]);
    }

    /**
     * Add something to the sheet. Either an existing catalogue row, or a name
     * the player typed that the catalogue did not have — in which case it joins
     * the catalogue flagged as custom, so the typeahead offers it next time.
     */
    public function store(Request $request, Character $character): RedirectResponse
    {
        $this->authorize('update', $character);

        $validated = $request->validate([
            'equipment_item_id'   => ['nullable', 'integer', Rule::exists(EquipmentItem::class, 'id')->withoutTrashed()],
            'name'                => ['nullable', 'string', 'max:255'],
            'storage_location_id' => ['nullable', 'integer', Rule::exists(StorageLocation::class, 'id')->withoutTrashed()],
            'quantity'            => ['nullable', 'integer', 'min:1', 'max:9999'],
            'notes'               => ['nullable', 'string', 'max:255'],
        ]);

        $item = $this->resolveItem($request, $validated);

        if ($item === null) {
            return back()->withErrors(['name' => 'Choose something from the list, or type a name for it.']);
        }

        $character->equipment()->attach($item->id, [
            'storage_location_id' => $validated['storage_location_id'] ?? $this->defaultLocationId(),
            'quantity'            => $validated['quantity'] ?? 1,
            'notes'               => $validated['notes'] ?? null,
        ]);

        return back()->with('success', "{$item->name} added.");
    }

    /**
     * Move something, change how many there are, or annotate it. Works for a
     * weapon's pivot row just as well as an equipment one — both are rows in
     * `equipables` belonging to this character.
     */
    public function update(Request $request, Character $character, int $equipable): RedirectResponse
    {
        $this->authorize('update', $character);

        $this->ownedRow($character, $equipable);

        $validated = $request->validate([
            'storage_location_id' => ['nullable', 'integer', Rule::exists(StorageLocation::class, 'id')->withoutTrashed()],
            'quantity'            => ['nullable', 'integer', 'min:1', 'max:9999'],
            'notes'               => ['nullable', 'string', 'max:255'],
        ]);

        DB::table('equipables')->where('id', $equipable)->update([
            'storage_location_id' => $validated['storage_location_id'] ?? null,
            'quantity'            => $validated['quantity'] ?? 1,
            'notes'               => $validated['notes'] ?? null,
            'updated_at'          => now(),
        ]);

        return back();
    }

    public function destroy(Character $character, int $equipable): RedirectResponse
    {
        $this->authorize('update', $character);

        $this->ownedRow($character, $equipable);

        DB::table('equipables')->where('id', $equipable)->delete();

        return back();
    }

    /**
     * Add a place to keep things. Locations are shared, so this refuses a name
     * that already exists rather than growing a second "Backpack".
     */
    public function storeLocation(Request $request, Character $character): RedirectResponse
    {
        $this->authorize('update', $character);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique(StorageLocation::class, 'name')],
        ], [
            'name.unique' => 'There is already a place with that name.',
        ]);

        StorageLocation::create([
            'slug'     => StorageLocation::uniqueSlug($validated['name']),
            'name'     => $validated['name'],
            'order_by' => StorageLocation::nextOrder(),
        ]);

        return back()->with('success', "“{$validated['name']}” added.");
    }

    /**
     * The catalogue row to attach: the one that was picked, or a new custom row
     * for a name the player typed.
     *
     * @param array<string, mixed> $validated
     */
    private function resolveItem(Request $request, array $validated): ?EquipmentItem
    {
        if (($validated['equipment_item_id'] ?? null) !== null) {
            return EquipmentItem::find($validated['equipment_item_id']);
        }

        $name = trim((string) ($validated['name'] ?? ''));

        if ($name === '') {
            return null;
        }

        // Typing the exact name of something already in the catalogue picks that
        // rather than making a near-duplicate custom row.
        $existing = EquipmentItem::query()->whereRaw('lower(name) = ?', [mb_strtolower($name)])->first();

        // A player inventing something says nothing about when it existed, so
        // it starts available throughout; an admin can narrow it later.
        return $existing ?? EquipmentItem::create([
            'slug'       => EquipmentItem::customSlug($name),
            'name'       => $name,
            'section'    => null,
            'cost'       => null,
            'eras'       => Era::all(),
            'is_custom'  => true,
            'created_by' => $request->user()?->id,
        ]);
    }

    /**
     * Where a thing goes when the player did not say: on their person.
     */
    private function defaultLocationId(): ?int
    {
        return StorageLocation::query()->orderBy('order_by')->value('id');
    }

    /**
     * A row in `equipables` belonging to this character, or a 404 — so one
     * sheet's id cannot be used to move another sheet's belongings.
     */
    private function ownedRow(Character $character, int $equipable): void
    {
        abort_unless(
            DB::table('equipables')->where('id', $equipable)->where('character_id', $character->id)->exists(),
            404,
        );
    }
}
