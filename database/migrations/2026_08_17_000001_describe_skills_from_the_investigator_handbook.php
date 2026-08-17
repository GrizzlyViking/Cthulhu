<?php

use App\Misc\SkillDescriptions;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class() extends Migration
{
    /**
     * Replace the skill descriptions with the Investigator Handbook's own,
     * transcribed in {@see SkillDescriptions} from chapter 5 (pp. 95–121).
     *
     * What was there before was invented prose — plausible one-liners that were
     * not the book's, and a third of the list had nothing at all. Players read
     * these while deciding what a roll is for, so they should say what the book
     * says.
     *
     * Retired skills are updated too: a restored skill comes back on every sheet
     * that carries it, and it should come back described.
     */
    public function up(): void
    {
        foreach (SkillDescriptions::all() as $slug => $description) {
            /*
             * Matched case-insensitively because of the legacy `Op_hv_machine`
             * row, which sits alongside `op_hv_machine` and is the one some
             * older sheets carry.
             */
            DB::table('skills')
                ->whereRaw('lower(slug) = ?', [$slug])
                ->update(['description' => $description]);
        }
    }

    /**
     * Nothing to undo. The descriptions this replaced were not the handbook's
     * and are not kept anywhere, so there is nothing to put back — and emptying
     * the column would throw away an admin's edits along with them.
     */
    public function down(): void
    {
        //
    }
};
