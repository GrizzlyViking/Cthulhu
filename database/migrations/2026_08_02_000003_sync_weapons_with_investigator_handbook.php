<?php

use App\Misc\WeaponTable;
use App\Models\Weapon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class() extends Migration
{
    /**
     * Bring the weapons table in line with the Investigator Handbook, pp. 250–254.
     *
     * The original import ran twice, kept the book's "(i)"/"*" notation inside
     * the names, truncated a handful of rows and covered roughly a third of the
     * table. This collapses the duplicates onto the row characters are actually
     * holding, renames the legacy entries and fills in the rest of the book.
     */
    public function up(): void
    {
        $this->collapseDuplicates();
        $this->applyRenames();
        $this->syncSkills();
        $this->syncWeapons();
        $this->clampNegativeAmmo();
    }

    /**
     * Point every equipped weapon at the lowest id sharing its name, then drop
     * the copies. Nothing is deleted before the pivot rows have moved across.
     */
    private function collapseDuplicates(): void
    {
        $groups = DB::table('weapons')
            ->select('name')
            ->groupBy('name')
            ->havingRaw('count(*) > 1')
            ->pluck('name');

        foreach ($groups as $name) {
            $ids  = DB::table('weapons')->where('name', $name)->orderBy('id')->pluck('id');
            $keep = $ids->shift();

            DB::table('equipables')
                ->where('equipable_type', Weapon::class)
                ->whereIn('equipable_id', $ids)
                ->update(['equipable_id' => $keep]);

            DB::table('weapons')->whereIn('id', $ids)->delete();
        }
    }

    private function applyRenames(): void
    {
        foreach (WeaponTable::renames() as $legacy => $canonical) {
            // Skip when the canonical row already exists, so the rename cannot
            // collide with a row the sync would create anyway.
            if (DB::table('weapons')->where('name', $canonical)->exists()) {
                continue;
            }

            DB::table('weapons')->where('name', $legacy)->update(['name' => $canonical]);
        }
    }

    /**
     * The submachine gun and machine gun skills were slugged under "fighting-";
     * weapons have always referred to them as "firearms-". Renaming the slug
     * keeps every character_skill row intact, since those join on the id.
     */
    private function syncSkills(): void
    {
        $reslug = [
            'fighting-smg' => ['slug' => 'firearms-smg', 'display_name' => 'Firearms (SMG)'],
            'fighting-mg'  => ['slug' => 'firearms-mg', 'display_name' => 'Firearms (MG)'],
        ];

        foreach ($reslug as $old => $new) {
            if (DB::table('skills')->where('slug', $new['slug'])->exists()) {
                continue;
            }

            DB::table('skills')->where('slug', $old)->update($new);
        }

        foreach (WeaponTable::skills() as $slug => $skill) {
            if (DB::table('skills')->where('slug', $slug)->exists()) {
                continue;
            }

            DB::table('skills')->insert([
                'slug'           => $slug,
                'display_name'   => $skill['display_name'],
                'starting_value' => $skill['starting_value'],
            ]);
        }
    }

    private function syncWeapons(): void
    {
        $existing = DB::table('weapons')->pluck('name')->all();

        foreach (WeaponTable::all() as $weapon) {
            if (in_array($weapon['name'], $existing, true)) {
                DB::table('weapons')
                    ->where('name', $weapon['name'])
                    ->update([...$weapon, 'updated_at' => now()]);

                continue;
            }

            DB::table('weapons')->insert([...$weapon, 'created_at' => now(), 'updated_at' => now()]);
        }

        // The house rule brawl entry is not in the book but is still equippable.
        DB::table('weapons')->where('name', 'Brawl (Unarmed)')->update([
            'category' => WeaponTable::HAND_TO_HAND,
            'era'      => '1920s, Modern',
            'impale'   => false,
        ]);
    }

    /**
     * The old fire endpoint could drive a magazine below zero.
     */
    private function clampNegativeAmmo(): void
    {
        DB::table('equipables')->where('ammo', '<', 0)->update(['ammo' => 0]);
    }

    /**
     * Only the renames are reversed. The weapons this migration added stay put:
     * characters may already be carrying them, and re-deleting rows to restore
     * a partial import is worse than leaving the full table in place.
     */
    public function down(): void
    {
        foreach (WeaponTable::renames() as $legacy => $canonical) {
            DB::table('weapons')->where('name', $canonical)->update(['name' => $legacy]);
        }
    }
};
