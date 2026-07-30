<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class() extends Migration
{
    public function up(): void
    {
        // Skill id:16 (slug:firearms_handgun) is a duplicate of id:60 (slug:firearms-handgun).
        // Weapons reference firearms-handgun via slug, so id:60 is the authoritative record.
        $charsWith16 = DB::table('character_skill')->where('skill_id', 16)->pluck('character_id');
        $charsWith60 = DB::table('character_skill')->where('skill_id', 60)->pluck('character_id');

        // Re-point rows that only have the old skill
        $toUpdate = $charsWith16->diff($charsWith60);
        DB::table('character_skill')
            ->whereIn('character_id', $toUpdate)
            ->where('skill_id', 16)
            ->update(['skill_id' => 60]);

        // Drop remaining rows for the old skill (characters that already had both)
        DB::table('character_skill')->where('skill_id', 16)->delete();

        DB::table('skills')->where('id', 16)->delete();
    }

    public function down(): void
    {
        // Destructive — original data cannot be restored automatically.
    }
};
