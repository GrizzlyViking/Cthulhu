<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Move group membership from the unused group_user pivot onto users.group_id
     * (one group per user) and add the blocked_at flag, then drop the old pivots.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('group_id')->nullable()->constrained('groups')->nullOnDelete();
            $table->timestamp('blocked_at')->nullable();
        });

        // Safety net: preserve any existing memberships (first group per user wins).
        if (Schema::hasTable('group_user')) {
            $memberships = DB::table('group_user')
                ->orderBy('group_id')
                ->get()
                ->unique('user_id');

            foreach ($memberships as $membership) {
                DB::table('users')
                    ->where('id', $membership->user_id)
                    ->update(['group_id' => $membership->group_id]);
            }
        }

        Schema::dropIfExists('group_user');
        Schema::dropIfExists('group_character');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('group_user', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete();

            $table->unique(['user_id', 'group_id']);
        });

        Schema::create('group_character', function (Blueprint $table) {
            $table->foreignId('character_id')->constrained('characters')->cascadeOnDelete();
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete();

            $table->unique(['character_id', 'group_id']);
        });

        DB::table('users')
            ->whereNotNull('group_id')
            ->orderBy('id')
            ->each(function (object $user): void {
                DB::table('group_user')->insert([
                    'user_id'  => $user->id,
                    'group_id' => $user->group_id,
                ]);
            });

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('group_id');
            $table->dropColumn('blocked_at');
        });
    }
};
