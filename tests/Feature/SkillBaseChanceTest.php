<?php

use App\Misc\WeaponTable;
use App\Models\Skill;
use Database\Seeders\SkillSeeder;

beforeEach(function () {
    $this->seed(SkillSeeder::class);
});

/**
 * WeaponTable has always carried the book's base chances for the skills weapons
 * are used with, and SkillSeeder used to contradict it — seven of them were 20
 * where the book prints 25, 15, 10 or 05. Whichever ran last won. This holds the
 * two lists together so that cannot come back.
 */
test('the combat skills agree with the weapons table', function () {
    foreach (WeaponTable::skills() as $slug => $skill) {
        $seeded = Skill::where('slug', $slug)->first();

        expect($seeded)->not->toBeNull("{$slug} is in the weapons table but not seeded");

        expect($seeded->starting_value)
            ->toBe($skill['starting_value'], "{$slug} starts at a different value in each list");
    }
});

test('the base chances corrected on 2026-08-17 are the handbook’s', function () {
    $book = [
        'fast_talking'     => 5,
        'fighting-axe'     => 15,
        'fighting-brawl'   => 25,
        'fighting-flail'   => 10,
        'fighting-garrote' => 15,
        'fighting-whip'    => 5,
        'firearms-bow'     => 15,
        'firearms-mg'      => 10,
        'firearms-rifle'   => 25,
        'firearms-shotgun' => 25,
        'firearms-smg'     => 15,
        'mech_repair'      => 10,
        'occult'           => 5,
        'swim'             => 20,
    ];

    foreach ($book as $slug => $value) {
        expect(Skill::where('slug', $slug)->value('starting_value'))->toBe($value, $slug);
    }
});

/**
 * The three the book gives no number for: Dodge is half DEX, Language (Own) is
 * EDU, and the generic Fighting cannot be purchased. They are deliberately not
 * the book's printed base chance, so nothing should "correct" them either.
 */
test('the derived skills keep their own values', function () {
    expect(Skill::where('slug', 'dodge')->value('starting_value'))->toBe(0);
    expect(Skill::where('slug', 'language_own')->value('starting_value'))->toBe(0);
});
