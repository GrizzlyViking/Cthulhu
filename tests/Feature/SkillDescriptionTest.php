<?php

use App\Misc\SkillDescriptions;
use App\Models\Skill;
use Database\Seeders\SkillSeeder;

beforeEach(function () {
    $this->seed(SkillSeeder::class);
});

test('every seeded skill carries the handbook description', function () {
    $missing = Skill::all()
        ->filter(fn (Skill $skill): bool => blank($skill->description))
        ->pluck('slug')
        ->all();

    expect($missing)->toBeEmpty();
});

test('the description is the handbook word for word', function () {
    foreach (SkillDescriptions::all() as $slug => $description) {
        $skill = Skill::where('slug', $slug)->first();

        // The table covers a few skills the players added, which the seeder does not.
        if ($skill === null) {
            continue;
        }

        expect($skill->description)->toBe($description);
    }
});

/**
 * The seeder is the list of skills the app ships with; the descriptions are a
 * separate transcription. A skill added to one and not the other would ship
 * undescribed, which is what this catches.
 */
test('the handbook describes every skill the seeder plants', function () {
    $undescribed = Skill::all()
        ->reject(fn (Skill $skill): bool => SkillDescriptions::for($skill->slug) !== null)
        ->pluck('slug')
        ->all();

    expect($undescribed)->toBeEmpty();
});

test('a skill the handbook does not cover is left alone', function () {
    expect(SkillDescriptions::for('sword-swallowing'))->toBeNull();
});
