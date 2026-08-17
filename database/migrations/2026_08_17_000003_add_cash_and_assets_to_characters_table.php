<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * What an investigator actually has on them, and what they own besides.
 *
 * Both are nullable on purpose. Until a player spends something — or types a
 * figure of their own — the sheet shows the Credit Rating band's cash and assets
 * straight out of Table II, so raising Credit Rating raises the money with it.
 * The first purchase settles the figures into these columns and they stop
 * following the band. See `Character::wealth()`.
 */
return new class() extends Migration
{
    public function up(): void
    {
        Schema::table('characters', function (Blueprint $table): void {
            $table->decimal('cash', 12, 2)->nullable()->after('luck');
            $table->decimal('assets', 12, 2)->nullable()->after('cash');
        });
    }

    public function down(): void
    {
        Schema::table('characters', function (Blueprint $table): void {
            $table->dropColumn(['cash', 'assets']);
        });
    }
};
