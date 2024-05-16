<?php

use App\Enums\Gender;
use App\Models\User;
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
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignIdFor(User::class)->nullable()->constrained()->cascadeOnDelete();
            $table->string('occupation')->nullable();
            $table->string('residence')->nullable();
            $table->string('birthplace')->nullable();
            $table->integer('age')->nullable();
            $table->enum('gender', Arr::map(Gender::cases(), fn (Gender $gender) => $gender->name))->nullable();
            $table->unsignedTinyInteger('strength')->default(0);
            $table->unsignedTinyInteger('dexterity')->default(0);
            $table->unsignedTinyInteger('intelligence')->default(0);
            $table->unsignedTinyInteger('constitution')->default(0);
            $table->unsignedTinyInteger('appearance')->default(0);
            $table->unsignedTinyInteger('power')->default(0);
            $table->unsignedTinyInteger('size')->default(0);
            $table->unsignedTinyInteger('education')->default(0);
            $table->unsignedTinyInteger('move_rate')->default(0);
            $table->boolean('temporary_insanity')->default(false);
            $table->boolean('indefinite_insanity')->default(false);
            $table->boolean('major_wound')->default(false);
            $table->boolean('unconscious')->default(false);
            $table->boolean('dying')->default(false);

            $table->unsignedTinyInteger('hit_points')->default(0);
            $table->unsignedTinyInteger('sanity')->default(0);
            $table->unsignedTinyInteger('luck')->default(0);
            $table->unsignedTinyInteger('magic_points')->default(0);

            $table->unsignedTinyInteger('dodge')->default(0);
            $table->tinyInteger('build')->default(0);
            $table->string('damage_bonus')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};
