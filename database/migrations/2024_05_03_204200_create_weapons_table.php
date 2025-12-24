<?php

use App\Models\Character;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('weapons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('skill');
            $table->string('damage');
            $table->string('base_range');
            $table->string('uses_per_round')->default(1);
            $table->string('bullets_in_mag')->nullable();
            $table->string('cost');
            $table->string('malfunction')->nullable();
            $table->timestamps();
        });

        Schema::create('equipables', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Character::class);
            $table->morphs('equipable');
            $table->unsignedSmallInteger('ammo')->default(0);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipables');
        Schema::dropIfExists('weapons');
    }
};
