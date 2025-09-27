<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('group_user', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete();

            $table->unique(['user_id', 'group_id']);
        });

        // Argument for this: a character can belong to a user in two teams, ie only visible while user is in a correct
        // team.
        Schema::create('group_character', function (Blueprint $table) {
            $table->foreignId('character_id')->constrained('characters')->cascadeOnDelete();
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete();

            $table->unique(['character_id', 'group_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_user');
        Schema::dropIfExists('group_character');
        Schema::dropIfExists('groups');
    }
};
