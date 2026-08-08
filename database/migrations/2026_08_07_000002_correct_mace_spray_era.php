<?php

use App\Enums\Era;
use App\Misc\EraTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Mace Spray is the one weapon whose printed availability cell disagrees with
 * the rest of its own row.
 *
 * The handbook prints it "1920s, Modern", but its cost cell is "-/$10" — a dash
 * where the 1920s price would be — and chemical mace was not invented until the
 * 1960s. The previous migration read the era cell and duly offered mace spray
 * to Twenties investigators. {@see EraTable::WEAPONS} now overrides it, and this
 * corrects the row that migration wrote.
 */
return new class() extends Migration
{
    public function up(): void
    {
        DB::table('weapons')
            ->where('name', 'Mace Spray')
            ->update(['eras' => json_encode([Era::Modern->value])]);
    }

    public function down(): void
    {
        // Back to what the era cell alone says.
        DB::table('weapons')
            ->where('name', 'Mace Spray')
            ->update(['eras' => json_encode(EraTable::forWeapon('1920s, Modern'))]);
    }
};
