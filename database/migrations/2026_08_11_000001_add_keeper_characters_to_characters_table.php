<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The Keeper's own cast: characters nobody plays.
 *
 * A cultist thrown together mid-scene is the same shape as an investigator —
 * characteristics, skills, a knife, hit points to lose — so it is a row in
 * `characters` rather than a table of its own. Three columns tell it apart:
 *
 * - `kind` — what the row is. A monster would be a third value here.
 * - `keeper_id` — whose it is. `user_id` stays null: no player owns it, and
 *   that alone keeps it out of every list that asks for a player's own sheets.
 * - `archetype` — what it was conjured up as, so the screen can say so.
 */
return new class() extends Migration
{
    public function up(): void
    {
        Schema::table('characters', function (Blueprint $table): void {
            $table->string('kind')->default('investigator')->after('status');
            $table->string('archetype')->nullable()->after('kind');
            $table->foreignIdFor(User::class, 'keeper_id')->nullable()->after('user_id')
                ->constrained('users')->cascadeOnDelete();

            // Every list of the Keeper's cast asks for both at once.
            $table->index(['kind', 'keeper_id']);
        });
    }

    public function down(): void
    {
        Schema::table('characters', function (Blueprint $table): void {
            $table->dropIndex(['kind', 'keeper_id']);
            $table->dropForeign(['keeper_id']);
            $table->dropColumn(['kind', 'archetype', 'keeper_id']);
        });
    }
};
