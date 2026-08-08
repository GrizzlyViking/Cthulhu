<?php

namespace Database\Seeders;

use App\Misc\EquipmentTable;
use App\Misc\EraTable;
use App\Models\EquipmentItem;
use App\Models\StorageLocation;
use Illuminate\Database\Seeder;

/**
 * The handbook catalogue and the four starting storage locations. Matching is
 * by slug, so this is safe to re-run and leaves players' custom items alone.
 */
class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        foreach (EquipmentTable::all() as $item) {
            EquipmentItem::updateOrCreate(
                ['slug' => $item['slug']],
                [
                    ...$item,
                    'eras'      => EraTable::forEquipment($item['slug'], $item['section']),
                    'is_custom' => false,
                ],
            );
        }

        foreach (StorageLocation::STARTING_LOCATIONS as $order => $name) {
            StorageLocation::updateOrCreate(
                ['slug' => str($name)->slug()->value()],
                ['name' => $name, 'order_by' => $order],
            );
        }
    }
}
