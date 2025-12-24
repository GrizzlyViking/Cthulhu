<?php

use App\Models\Character;
use App\Models\Skill;
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
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('slug', length: 20)->unique();
            $table->string('display_name', length: 50)->unique();
            $table->unsignedTinyInteger('starting_value')->default(0);
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('order_by')->default(0);
        });

        Schema::create('character_skill', function (Blueprint $table) {
            $table->unique(['character_id', 'skill_id']);
            $table->foreignIdFor(Character::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Skill::class)->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('value')->default(0);
            $table->unsignedTinyInteger('experience')->default(0);
            $table->unsignedTinyInteger('order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_skill');
        Schema::dropIfExists('skills');
    }
};
