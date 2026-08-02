<?php

use App\Models\Occupation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->string('era')->default('1920s');
        });

        Schema::create('occupations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->json('eras');
            $table->json('skill_points_formula');
            $table->unsignedTinyInteger('credit_rating_min')->default(0);
            $table->unsignedTinyInteger('credit_rating_max')->default(99);
            $table->json('skills');
        });

        Schema::table('characters', function (Blueprint $table) {
            $table->string('status')->default('complete');
            $table->json('backstory')->nullable();
            $table->foreignIdFor(Occupation::class)->nullable()->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(Occupation::class);
            $table->dropColumn(['status', 'backstory']);
        });

        Schema::dropIfExists('occupations');

        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn('era');
        });
    }
};
