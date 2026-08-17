<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class() extends Migration
{
    /**
     * Put the fourteen skills whose base chance never matched the Investigator
     * Handbook back on the book's numbers (the skill list, pp. 97–98).
     *
     * Seven of them are combat skills that {@see \App\Misc\WeaponTable::skills()}
     * has always had right, so the app has been contradicting itself: whichever
     * of SkillSeeder and the weapons sync ran last won. SkillSeeder now agrees
     * with the book too, and a test holds the two together.
     *
     * The other three differences from the book are left as they are, because the
     * book prints no number for them: Dodge is half DEX, Language (Own) is EDU,
     * and the generic Fighting cannot be purchased at all.
     */
    public function up(): void
    {
        foreach ($this->baseChances() as $slug => [$from, $to]) {
            DB::table('skills')->where('slug', $slug)->update(['starting_value' => $to]);

            /*
             * Where the floor rose, sheets carrying the skill at the old base are
             * now below what the book gives everyone for free, so they come up to
             * it. Only values under the new base move, so an investigator who
             * spent points to get above it keeps every one of them, and a floor
             * that fell takes nothing off anybody.
             */
            if ($to > $from) {
                DB::table('character_skill')
                    ->whereIn('skill_id', DB::table('skills')->where('slug', $slug)->pluck('id'))
                    ->where('value', '<', $to)
                    ->update(['value' => $to]);
            }
        }
    }

    public function down(): void
    {
        foreach ($this->baseChances() as $slug => [$from, $to]) {
            DB::table('skills')->where('slug', $slug)->update(['starting_value' => $from]);
        }

        /*
         * The values lifted onto the raised floors are not put back. Which sheets
         * they were is not recorded, and taking points off an investigator to
         * undo a migration is worse than leaving them a little generous.
         */
    }

    /**
     * Every base chance that was wrong, as it was and as the book prints it.
     *
     * @return array<string, array{int, int}>
     */
    private function baseChances(): array
    {
        return [
            'fast_talking'     => [20, 5],
            'fighting-axe'     => [20, 15],
            'fighting-brawl'   => [20, 25],
            'fighting-flail'   => [20, 10],
            'fighting-garrote' => [20, 15],
            'fighting-whip'    => [20, 5],
            'firearms-bow'     => [20, 15],
            'firearms-mg'      => [20, 10],
            'firearms-rifle'   => [20, 25],
            'firearms-shotgun' => [20, 25],
            'firearms-smg'     => [20, 15],
            'mech_repair'      => [1, 10],
            'occult'           => [10, 5],
            'swim'             => [10, 20],
        ];
    }
};
