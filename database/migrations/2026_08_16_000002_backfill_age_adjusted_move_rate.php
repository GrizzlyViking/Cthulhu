<?php

use App\Misc\CharacterCreation;
use App\Models\Character;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/*
 * `characters.move_rate` held the 7/8/9 rule with the age deduction left off,
 * while the wizard's summary and the sheet both showed it taken off — the
 * column disagreed with every figure a player saw. The deduction now lives in
 * `CharacterCreation::moveRate()`, so the stored numbers are brought in line
 * with it here.
 *
 * Written straight to the table so nothing's `updated_at` moves: that is what
 * the landing route sorts on, and a backfill is not a player editing a sheet.
 */
return new class() extends Migration
{
    public function up(): void
    {
        Character::query()->withoutGlobalScopes()->each(function (Character $character) {
            DB::table('characters')
                ->where('id', $character->id)
                ->update(['move_rate' => CharacterCreation::moveRate($character)]);
        });
    }

    public function down(): void
    {
        Character::query()->withoutGlobalScopes()->each(function (Character $character) {
            DB::table('characters')
                ->where('id', $character->id)
                ->update(['move_rate' => CharacterCreation::baseMoveRate($character)]);
        });
    }
};
