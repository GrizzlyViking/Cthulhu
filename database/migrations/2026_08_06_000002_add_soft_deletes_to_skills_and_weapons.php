<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Skills and weapons become retirable rather than destroyable.
 *
 * A retired row keeps its id, so the pivots that reference it (character_skill,
 * equipables) stay intact and restoring brings the skill or weapon back onto
 * the sheets that had it. The unique indexes on skills.slug and
 * skills.display_name still cover retired rows, so a retired skill's name
 * cannot be reused until it is restored — the admin pages say so.
 */
return new class() extends Migration
{
    public function up(): void
    {
        Schema::table('skills', function (Blueprint $table): void {
            $table->softDeletes();
        });

        Schema::table('weapons', function (Blueprint $table): void {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('skills', function (Blueprint $table): void {
            $table->dropSoftDeletes();
        });

        Schema::table('weapons', function (Blueprint $table): void {
            $table->dropSoftDeletes();
        });
    }
};
