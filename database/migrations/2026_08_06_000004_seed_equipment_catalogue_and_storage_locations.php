<?php

use App\Misc\EquipmentTable;
use App\Models\StorageLocation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Fill the catalogue from the handbook transcription and lay down the four
 * places an investigator starts with. Matching is by slug, so re-running after
 * EquipmentTable grows only adds what is missing and leaves custom rows alone.
 */
return new class() extends Migration
{
    public function up(): void
    {
        $now = now();

        foreach (array_chunk(EquipmentTable::all(), 100) as $chunk) {
            DB::table('equipment_items')->upsert(
                array_map(fn (array $item): array => [
                    ...$item,
                    'era'        => '1920s',
                    'is_custom'  => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ], $chunk),
                ['slug'],
                ['name', 'section', 'cost', 'era', 'updated_at'],
            );
        }

        foreach (StorageLocation::STARTING_LOCATIONS as $order => $name) {
            DB::table('storage_locations')->upsert([[
                'slug'       => str($name)->slug()->value(),
                'name'       => $name,
                'order_by'   => $order,
                'created_at' => $now,
                'updated_at' => $now,
            ]], ['slug'], ['name', 'order_by', 'updated_at']);
        }
    }

    public function down(): void
    {
        DB::table('equipment_items')->whereIn('slug', array_column(EquipmentTable::all(), 'slug'))->delete();

        DB::table('storage_locations')
            ->whereIn('name', StorageLocation::STARTING_LOCATIONS)
            ->delete();
    }
};
