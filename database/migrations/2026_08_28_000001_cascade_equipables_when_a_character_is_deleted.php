<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The one cascade `characters` was missing.
 *
 * `character_skill` and `character_game` have cascaded since they were created,
 * but `equipables.character_id` was declared with `foreignIdFor()` and never
 * `constrained()` — a column and an index, no foreign key. Nothing enforced it,
 * so every sheet deleted for good left its weapons and its belongings behind as
 * rows pointing at an id that no longer exists. {@see \App\Models\Character::purge()}
 * detaches them by hand, which is why nobody noticed; anything that deletes a
 * character any other way — a player's account going, a `forceDelete()` in a
 * console command — did not.
 *
 * The orphans already in the table are swept first, or the constraint cannot be
 * added at all.
 */
return new class() extends Migration
{
    public function up(): void
    {
        DB::table('equipables')
            ->whereNotIn('character_id', DB::table('characters')->select('id'))
            ->delete();

        Schema::table('equipables', function (Blueprint $table): void {
            $table->foreign('character_id')->references('id')->on('characters')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('equipables', function (Blueprint $table): void {
            $table->dropForeign(['character_id']);
        });
    }
};
