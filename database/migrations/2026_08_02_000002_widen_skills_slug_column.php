<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Twenty characters is not enough for the weapon skills the handbook uses
     * ("firearms-flamethrower" is twenty-one).
     */
    public function up(): void
    {
        // The existing unique index carries over; only the width changes.
        Schema::table('skills', function (Blueprint $table) {
            $table->string('slug', 60)->change();
        });
    }

    public function down(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->string('slug', 20)->change();
        });
    }
};
