<?php

namespace App\Http\Controllers;

use App\Enums\Era;
use App\Models\Character;
use App\Models\EquipmentItem;
use App\Models\Skill;
use App\Models\StorageLocation;
use App\Models\Weapon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class GearTransferController extends Controller
{
    use AuthorizesRequests;

    public function catalogue(Character $character): JsonResponse
    {
        $this->authorize('update', $character);

        return response()->json([
            'skills'    => Skill::orderBy('display_name')->get(['slug', 'display_name']),
            'equipment' => EquipmentItem::orderBy('name')->get(['id', 'name']),
            'weapon'    => Weapon::without('skills')->orderBy('name')->get(['id', 'name']),
        ]);
    }

    /** Move reviewed possessions without charging for things already owned. */
    public function store(Request $request, Character $character): RedirectResponse
    {
        $this->authorize('update', $character);

        $data = $request->validate([
            'gear'                 => ['sometimes', 'required', 'string', 'max:2000'],
            'saved_gear'           => ['present_with:gear', 'nullable', 'string', 'max:2000'],
            'items.*.source_index' => ['required_with:gear', 'integer', 'min:0', 'distinct'],
            'items.*.remaining'    => ['present_with:gear', 'nullable', 'string', 'max:2000'],
            'items'                => ['required', 'array', 'min:1', 'max:200'],
            'items.*.type'         => ['required', Rule::in(['equipment', 'weapon'])],
            'items.*.name'         => ['required', 'string', 'max:255'],
            'items.*.catalogue_id' => ['nullable', 'integer', 'min:1'],
            'items.*.quantity'     => ['required', 'integer', 'min:1', 'max:9999'],
            'items.*.damage'       => ['nullable', 'string', 'max:255'],
            'items.*.skill'        => ['nullable', 'string', Rule::exists(Skill::class, 'slug')->withoutTrashed()],
        ]);

        // Validate the whole batch before writing any shared catalogue entries.
        foreach ($data['items'] as $index => $entry) {
            if ($entry['catalogue_id'] ?? null) {
                $model = $entry['type'] === 'weapon' ? Weapon::class : EquipmentItem::class;
                $request->validate([
                    "items.$index.catalogue_id" => [Rule::exists($model, 'id')->withoutTrashed()],
                ]);
            }
        }

        $added = DB::transaction(function () use ($data, $character, $request): int {
            // Serialise transfers for this sheet, including double submissions.
            $locked    = Character::whereKey($character->id)->lockForUpdate()->firstOrFail();
            $backstory = $locked->getAttribute('backstory') ?? [];
            $moving    = array_key_exists('gear', $data);
            $parts     = $moving ? preg_split('/[;\r\n]+/u', $data['gear'] ?? '') : [];
            if ($moving && ($backstory['gear'] ?? '') !== ($data['saved_gear'] ?? '')) {
                throw ValidationException::withMessages(['gear' => 'Gear & possessions changed while you were reviewing. Reload the sheet before transferring.']);
            }
            if ($moving) {
                foreach ($data['items'] as $entry) {
                    if (! array_key_exists($entry['source_index'], $parts)) {
                        throw ValidationException::withMessages(['items' => 'An entry no longer matches the gear text. Open the transfer again.']);
                    }
                }
            }
            $location = StorageLocation::orderBy('order_by')->value('id');
            $added    = 0;

            foreach ($data['items'] as $entry) {
                $weapon = $entry['type'] === 'weapon';
                $model  = $weapon ? Weapon::class : EquipmentItem::class;
                $name   = trim($entry['name']);
                $item   = ($entry['catalogue_id'] ?? null)
                    ? $model::findOrFail($entry['catalogue_id'])
                    : $model::whereRaw('lower(name) = ?', [mb_strtolower($name)])->first();

                if (! $item) {
                    $attributes = [
                        'name'       => $name,
                        'eras'       => Era::all(),
                        'is_custom'  => true,
                        'created_by' => $request->user()->id,
                    ];
                    $item = $weapon
                        ? Weapon::create([...$attributes,
                            'skill'          => $entry['skill'] ?? 'Unspecified',
                            'damage'         => $entry['damage'] ?? '—',
                            'base_range'     => '—',
                            'uses_per_round' => '—',
                            'cost'           => '—',
                        ])
                        : EquipmentItem::create([...$attributes, 'slug' => EquipmentItem::customSlug($name)]);
                }

                $relation = $weapon ? $character->weapons() : $character->equipment();
                if ($relation->whereKey($item->id)->exists()) {
                    continue;
                }

                $relation->attach($item->id, [
                    'quantity'            => $entry['quantity'],
                    'storage_location_id' => $location,
                ]);
                if ($moving) {
                    $parts[$entry['source_index']] = $entry['remaining'] ?? '';
                }
                $added++;
            }

            if ($moving) {
                $remaining = implode("\n", array_values(array_filter(array_map('trim', $parts), fn (string $part): bool => $part !== '')));
                if (mb_strlen($remaining) > 2000) {
                    throw ValidationException::withMessages(['gear' => 'The remaining gear text must be at most 2000 characters.']);
                }
                $locked->update(['backstory' => [...$backstory, 'gear' => $remaining]]);
            }

            return $added;
        });

        return back()->with('success', "$added added to Equipment. Items already on the sheet were skipped.");
    }
}
