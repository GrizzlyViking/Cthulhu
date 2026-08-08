<?php

use App\Enums\Era;
use App\Misc\EraTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Which eras a skill, a weapon and a catalogue item belong to.
 *
 * A group plays in one era (`groups.era`), and until now nothing it drew on
 * knew that: a 1920s table was offered the chainsaw and the Uzi, and a modern
 * one the carbide lamp and the telegraph outfit. `eras` is a list rather than a
 * single value because most things belong to both.
 *
 * Weapons keep their `era` column: that is the handbook's availability cell
 * verbatim ("1920s, Modern", "WWII, Later", "Rare"), which says things the
 * two-value list cannot, and the new column is read from it. Equipment does
 * not — its `era` was the string "1920s" on every row, seeded that way because
 * the whole catalogue came from the 1920s lists, so it goes.
 *
 * The values come from {@see EraTable}. Only the weapons' are from the book;
 * the rest are guesses an admin can overrule.
 */
return new class() extends Migration
{
    public function up(): void
    {
        $default = json_encode(Era::all());

        foreach (['skills', 'weapons', 'equipment_items'] as $table) {
            Schema::table($table, function (Blueprint $blueprint) use ($default): void {
                $blueprint->json('eras')->default($default)->after('id');
            });
        }

        Schema::table('equipment_items', function (Blueprint $table): void {
            $table->dropColumn('era');
        });

        $this->backfill();
    }

    public function down(): void
    {
        foreach (['skills', 'weapons', 'equipment_items'] as $table) {
            Schema::table($table, function (Blueprint $blueprint): void {
                $blueprint->dropColumn('eras');
            });
        }

        Schema::table('equipment_items', function (Blueprint $table): void {
            $table->string('era')->nullable()->after('cost');
        });

        DB::table('equipment_items')->update(['era' => '1920s']);
    }

    /**
     * Fill the new column in from the era table. Rows this server has added of
     * its own — a house ruled skill, a custom item a player typed — are not in
     * it, and keep the column default of every era.
     */
    private function backfill(): void
    {
        foreach (EraTable::SKILLS as $slug => $eras) {
            DB::table('skills')->where('slug', $slug)->update(['eras' => json_encode($eras)]);
        }

        DB::table('weapons')->select('id', 'era')->orderBy('id')->chunk(200, function ($weapons): void {
            foreach ($weapons as $weapon) {
                DB::table('weapons')
                    ->where('id', $weapon->id)
                    ->update(['eras' => json_encode(EraTable::forWeapon($weapon->era))]);
            }
        });

        DB::table('equipment_items')->select('id', 'slug', 'section', 'is_custom')->orderBy('id')
            ->chunk(200, function ($items): void {
                foreach ($items as $item) {
                    if ($item->is_custom) {
                        continue;
                    }

                    DB::table('equipment_items')
                        ->where('id', $item->id)
                        ->update(['eras' => json_encode(EraTable::forEquipment($item->slug, $item->section))]);
                }
            });
    }
};
