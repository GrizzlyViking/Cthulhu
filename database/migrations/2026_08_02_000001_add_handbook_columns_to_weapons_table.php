<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Columns the Investigator Handbook's weapons table carries but the first
     * import dropped, plus the spare rounds an investigator hauls around.
     */
    public function up(): void
    {
        Schema::table('weapons', function (Blueprint $table) {
            $table->string('category')->nullable()->after('name');
            $table->string('era')->nullable()->after('malfunction');
            $table->boolean('impale')->default(false)->after('era');
        });

        Schema::table('equipables', function (Blueprint $table) {
            // Rounds carried outside the magazine; `ammo` stays "loaded now".
            $table->unsignedSmallInteger('ammo_reserve')->default(0)->after('ammo');
        });
    }

    public function down(): void
    {
        Schema::table('weapons', function (Blueprint $table) {
            $table->dropColumn(['category', 'era', 'impale']);
        });

        Schema::table('equipables', function (Blueprint $table) {
            $table->dropColumn('ammo_reserve');
        });
    }
};
