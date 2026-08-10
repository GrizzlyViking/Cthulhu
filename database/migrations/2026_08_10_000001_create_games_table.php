<?php

use App\Models\Character;
use App\Models\Game;
use App\Models\Group;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * A game is a campaign: the thing a group actually plays, and the unit the
     * era now belongs to. Characters join games many-to-many, because a party
     * is several investigators and — rarely — one investigator turns up in two
     * campaigns.
     */
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Group::class)->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('era')->default('1920s');
            $table->timestamps();

            $table->unique(['group_id', 'name']);
        });

        Schema::create('character_game', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Character::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Game::class)->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['character_id', 'game_id']);
        });

        /*
         * Only one game is ever active, so the group points at it rather than
         * each game carrying a flag two rows could both hold. Deleting the
         * active game leaves the group with none, which is a legitimate state.
         */
        Schema::table('groups', function (Blueprint $table) {
            $table->foreignIdFor(Game::class, 'active_game_id')->nullable()->constrained('games')->nullOnDelete();
        });

        $this->backfill();
    }

    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(Game::class, 'active_game_id');
        });

        Schema::dropIfExists('character_game');
        Schema::dropIfExists('games');
    }

    /**
     * Every group already playing gets one game, named after itself and
     * carrying the era it was set to, holding every character the group has.
     * The admin renames it to whatever the campaign is really called.
     */
    private function backfill(): void
    {
        $now = now();

        foreach (DB::table('groups')->orderBy('id')->get() as $group) {
            $gameId = DB::table('games')->insertGetId([
                'group_id'   => $group->id,
                'name'       => $group->name,
                'era'        => $group->era ?? '1920s',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Retired investigators come along: restoring one should put it
            // back in the campaign it was played in.
            $rows = DB::table('characters')
                ->where('group_id', $group->id)
                ->pluck('id')
                ->map(fn (int $characterId): array => [
                    'character_id' => $characterId,
                    'game_id'      => $gameId,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ])
                ->all();

            if ($rows !== []) {
                DB::table('character_game')->insert($rows);
            }

            DB::table('groups')->where('id', $group->id)->update(['active_game_id' => $gameId]);
        }
    }
};
