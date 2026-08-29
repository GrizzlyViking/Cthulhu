<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A sheet carries two pictures, not one.
 *
 * `avatar` was always the investigator's likeness — it prints as the portrait
 * on the paper sheet — but the screen also used it as the wide backdrop behind
 * the name, and a face does not survive that crop. `banner` is that backdrop,
 * given its own column so a player can upload a landscape picture for it and
 * leave their portrait whole.
 */
return new class() extends Migration
{
    public function up(): void
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->string('banner')->nullable()->after('avatar');
        });
    }

    public function down(): void
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->dropColumn('banner');
        });
    }
};
