<?php

use App\Enums\CharacterStatus;
use App\Enums\Era;
use App\Misc\Wealth;
use App\Models\Character;
use App\Models\Group;
use App\Models\Occupation;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->seed(\Database\Seeders\SkillSeeder::class);
    $this->seed(\Database\Seeders\OccupationSeeder::class);

    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

/**
 * Deterministic draft used by the step tests. Derived stats are recomputed by
 * the factory from these values: HP 11, SAN 55, MP 11, dodge 35, build 0.
 */
function wizardDraft(User $user, array $attributes = []): Character
{
    return Character::factory()->create([
        'user_id'      => $user->id,
        'status'       => CharacterStatus::Draft,
        'wizard_step'  => 2,
        'age'          => 30,
        'strength'     => 40,
        'constitution' => 50,
        'size'         => 60,
        'dexterity'    => 70,
        'appearance'   => 50,
        'intelligence' => 80,
        'power'        => 55,
        'education'    => 70,
        'luck'         => 45,
        ...$attributes,
    ]);
}

function journalist(): Occupation
{
    return Occupation::where('name', 'Journalist')->firstOrFail();
}

function journalistDraft(User $user, array $attributes = []): Character
{
    $journalist = journalist();

    return wizardDraft($user, [
        'occupation_id' => $journalist->id,
        'occupation'    => $journalist->name,
        'wizard_step'   => 3,
        ...$attributes,
    ]);
}

function pivotValue(Character $character, string $slug): int
{
    return (int) $character->skills()->where('slug', $slug)->first()->pivot->value;
}

/**
 * @return array<string, int|string>
 */
function validProfile(array $overrides = []): array
{
    return [
        'name'       => 'Harvey Walters',
        'gender'     => 'Male',
        'age'        => 42,
        'residence'  => 'Arkham',
        'birthplace' => 'Boston',
        ...$overrides,
    ];
}

/**
 * @return array<string, int>
 */
function validCharacteristics(array $overrides = []): array
{
    return [
        'strength'     => 40,
        'constitution' => 50,
        'size'         => 60,
        'dexterity'    => 70,
        'appearance'   => 50,
        'intelligence' => 80,
        'power'        => 55,
        'education'    => 70,
        'luck'         => 45,
        ...$overrides,
    ];
}

test('happy path walks the full wizard from profile to completion', function () {
    $otherUser = User::factory()->create();

    // Step 0 — profile. A foreign user_id in the payload must be ignored.
    $this->post(route('character.wizard.store'), validProfile(['user_id' => $otherUser->id]))
        ->assertRedirect(route('character.create'));

    $draft = Character::where('name', 'Harvey Walters')->firstOrFail();

    expect($draft->status)->toBe(CharacterStatus::Draft)
        ->and($draft->wizard_step)->toBe(1)
        ->and($draft->slug)->toBe('harvey-walters')
        ->and($draft->user_id)->toBe($this->user->id)
        ->and($draft->skills)->toHaveCount(Skill::count());

    // All skills attached at their base value.
    expect(pivotValue($draft, 'library_use'))->toBe(20)
        ->and(pivotValue($draft, 'language_own'))->toBe(0)
        ->and(pivotValue($draft, 'credit_rating'))->toBe(0);

    // Step 1 — characteristics compute every derived stat.
    $this->put(route('character.wizard.characteristics', $draft->slug), validCharacteristics())
        ->assertRedirect(route('character.create'));

    $draft->refresh();
    expect($draft->hit_points)->toBe(11)     // (CON 50 + SIZ 60) / 10
        ->and($draft->sanity)->toBe(55)      // POW
        ->and($draft->magic_points)->toBe(11) // POW / 5
        ->and($draft->dodge)->toBe(35)       // DEX / 2
        ->and($draft->damage_bonus)->toBe('none') // STR + SIZ = 100 → Table I
        ->and($draft->build)->toBe(0)
        ->and($draft->move_rate)->toBe(7)    // mixed against SIZ → 8, less 1 for being 42
        ->and($draft->wizard_step)->toBe(2);

    // Step 2 — occupation.
    $this->put(route('character.wizard.occupation', $draft->slug), [
        'occupation_id' => journalist()->id,
    ])->assertRedirect(route('character.create'));

    $draft->refresh();
    expect($draft->occupation_id)->toBe(journalist()->id)
        ->and($draft->occupation)->toBe('Journalist')
        ->and($draft->wizard_step)->toBe(3);

    // Step 3 — skills. Journalist pool = EDU × 4 = 280, personal = INT × 2 = 160.
    $this->put(route('character.wizard.skills', $draft->slug), [
        'occupation' => [
            'credit_rating' => 20,
            'library_use'   => 40,
            'history'       => 30,
            'psychology'    => 30,
            'language_own'  => 20,
            'charm'         => 30,
            'occult'        => 20,
            'drive_auto'    => 20,
        ],
        'personal' => [
            'listen'      => 30,
            'spot-hidden' => 25,
            'first_aid'   => 20,
        ],
    ])->assertRedirect(route('character.create'));

    $draft->refresh();
    expect($draft->wizard_step)->toBe(4)
        ->and(pivotValue($draft, 'library_use'))->toBe(60)   // base 20 + 40
        ->and(pivotValue($draft, 'history'))->toBe(35)       // base 5 + 30
        ->and(pivotValue($draft, 'psychology'))->toBe(40)    // base 10 + 30
        ->and(pivotValue($draft, 'charm'))->toBe(45)         // base 15 + 30
        ->and(pivotValue($draft, 'occult'))->toBe(30)        // base 10 + 20
        ->and(pivotValue($draft, 'drive_auto'))->toBe(40)    // base 20 + 20
        ->and(pivotValue($draft, 'credit_rating'))->toBe(20)
        ->and(pivotValue($draft, 'language_own'))->toBe(90)  // EDU 70 + 20
        ->and(pivotValue($draft, 'dodge'))->toBe(35)         // DEX / 2
        ->and(pivotValue($draft, 'listen'))->toBe(50)        // base 20 + 30
        ->and(pivotValue($draft, 'spot-hidden'))->toBe(50)   // base 25 + 25
        ->and(pivotValue($draft, 'first_aid'))->toBe(50)     // base 30 + 20
        ->and(pivotValue($draft, 'swim'))->toBe(10);         // untouched

    // Step 4 — backstory.
    $this->put(route('character.wizard.backstory', $draft->slug), [
        'personal_description' => 'A wiry, chain-smoking reporter.',
        'ideology'             => 'The truth must out.',
        'traits'               => 'Relentless',
        'gear'                 => 'Notebook, camera',
    ])->assertRedirect(route('character.create'));

    $draft->refresh();
    expect($draft->wizard_step)->toBe(5)
        ->and($draft->backstory)->toBe([
            'personal_description' => 'A wiry, chain-smoking reporter.',
            'ideology'             => 'The truth must out.',
            'traits'               => 'Relentless',
            'gear'                 => 'Notebook, camera',
        ]);

    // Step 5 — completion.
    $this->put(route('character.wizard.complete', $draft->slug))
        ->assertRedirect(route('character.show', $draft->slug));

    expect($draft->refresh()->status)->toBe(CharacterStatus::Complete);
});

test('profile rejects an age below 15 or above 90', function (int $age) {
    $this->post(route('character.wizard.store'), validProfile(['age' => $age]))
        ->assertSessionHasErrors('age');

    expect(Character::count())->toBe(0);
})->with([14, 91]);

test('profile rejects a duplicate character name', function () {
    Character::factory()->create(['name' => 'Harvey Walters', 'slug' => 'harvey-walters']);

    $this->post(route('character.wizard.store'), validProfile(['name' => 'Harvey Walters']))
        ->assertSessionHasErrors('name');

    expect(Character::where('name', 'Harvey Walters')->count())->toBe(1);
});

test('characteristics rejects out of range values', function (string $field, int $value) {
    $draft = wizardDraft($this->user, ['wizard_step' => 1]);

    $this->put(route('character.wizard.characteristics', $draft->slug), validCharacteristics([$field => $value]))
        ->assertSessionHasErrors($field);

    $draft->refresh();
    expect($draft->wizard_step)->toBe(1)
        ->and($draft->strength)->toBe(40);
})->with([
    ['strength', 0],
    ['size', 100],
    ['luck', 91],
]);

test('characteristics store the move rate with the age deduction already taken off', function () {
    $draft = wizardDraft($this->user, ['wizard_step' => 1, 'age' => 45]);

    $this->put(route('character.wizard.characteristics', $draft->slug), validCharacteristics())
        ->assertRedirect(route('character.create'));

    // The column itself, not the accessor: what the wizard showed is what the
    // sheet is written with. STR 40 / DEX 70 against SIZ 60 → 8, less 1 for
    // the forties.
    expect(DB::table('characters')->where('id', $draft->id)->value('move_rate'))->toBe(7)
        ->and($draft->refresh()->move_rate)->toBe(7);
});

test('skills rejects overspending the occupation pool', function () {
    $draft = journalistDraft($this->user);

    // Journalist pool is EDU 70 × 4 = 280; 20 + 261 = 281 overspends by one.
    $this->put(route('character.wizard.skills', $draft->slug), [
        'occupation' => ['credit_rating' => 20, 'library_use' => 261],
    ])->assertSessionHasErrors('occupation');

    expect(pivotValue($draft->refresh(), 'library_use'))->toBe(20)
        ->and($draft->wizard_step)->toBe(3);
});

test('skills rejects overspending the personal pool', function () {
    $draft = journalistDraft($this->user);

    // Personal pool is INT 80 × 2 = 160.
    $this->put(route('character.wizard.skills', $draft->slug), [
        'occupation' => ['credit_rating' => 20],
        'personal'   => ['listen' => 161],
    ])->assertSessionHasErrors('personal');

    expect(pivotValue($draft->refresh(), 'listen'))->toBe(20);
});

test('skills rejects allocations to cthulhu mythos in either pool', function () {
    $draft = journalistDraft($this->user);

    $this->put(route('character.wizard.skills', $draft->slug), [
        'occupation' => ['credit_rating' => 20, 'cthulhu_mythos' => 5],
        'personal'   => ['cthulhu_mythos' => 5],
    ])->assertSessionHasErrors(['occupation.cthulhu_mythos', 'personal.cthulhu_mythos']);

    expect(pivotValue($draft->refresh(), 'cthulhu_mythos'))->toBe(0);
});

test('skills rejects a credit rating outside the occupation range', function (int $creditRating) {
    $draft = journalistDraft($this->user);

    // Journalist allows Credit Rating 9–30.
    $this->put(route('character.wizard.skills', $draft->slug), [
        'occupation' => ['credit_rating' => $creditRating],
    ])->assertSessionHasErrors('occupation.credit_rating');

    expect(pivotValue($draft->refresh(), 'credit_rating'))->toBe(0);
})->with([5, 35]);

test('skills rejects credit rating in the personal pool', function () {
    $draft = journalistDraft($this->user);

    $this->put(route('character.wizard.skills', $draft->slug), [
        'occupation' => ['credit_rating' => 20],
        'personal'   => ['credit_rating' => 10],
    ])->assertSessionHasErrors('personal.credit_rating');

    expect(pivotValue($draft->refresh(), 'credit_rating'))->toBe(0);
});

test('skills rejects unknown skill slugs', function () {
    $draft = journalistDraft($this->user);

    $this->put(route('character.wizard.skills', $draft->slug), [
        'occupation' => ['credit_rating' => 20],
        'personal'   => ['basket_weaving' => 5],
    ])->assertSessionHasErrors('basket_weaving');
});

test('skills cannot be allocated before an occupation is chosen', function () {
    $draft = wizardDraft($this->user);

    $this->put(route('character.wizard.skills', $draft->slug), [
        'occupation' => ['credit_rating' => 20],
    ])->assertSessionHasErrors('occupation_id');

    expect($draft->refresh()->wizard_step)->toBe(2);
});

test('completion is rejected before the backstory step is reached', function () {
    $draft = journalistDraft($this->user);

    $this->put(route('character.wizard.complete', $draft->slug))
        ->assertStatus(422);

    expect($draft->refresh()->status)->toBe(CharacterStatus::Draft);
});

test('another player cannot edit someone elses draft', function () {
    $draft = wizardDraft($this->user);

    $this->actingAs(User::factory()->create())
        ->put(route('character.wizard.profile', $draft->slug), validProfile())
        ->assertForbidden();
});

test('wizard endpoints reject a completed character', function () {
    $character = Character::factory()->create(['user_id' => $this->user->id]);

    expect($character->refresh()->status)->toBe(CharacterStatus::Complete);

    $this->put(route('character.wizard.characteristics', $character->slug), validCharacteristics())
        ->assertForbidden();
});

test('guests are redirected to login', function () {
    auth()->logout();

    $this->get(route('character.create'))->assertRedirect(route('login'));
    $this->post(route('character.wizard.store'), validProfile())->assertRedirect(route('login'));
});

test('revisiting an earlier step never lowers wizard_step', function () {
    $draft = wizardDraft($this->user, ['wizard_step' => 2]);

    $this->put(route('character.wizard.profile', $draft->slug), validProfile(['name' => 'Renamed Investigator']))
        ->assertRedirect(route('character.create'));

    $draft->refresh();
    expect($draft->wizard_step)->toBe(2)
        ->and($draft->name)->toBe('Renamed Investigator')
        ->and($draft->slug)->toBe('renamed-investigator');
});

test('foreign drafts are hidden from shared character lists', function () {
    $group = Group::factory()->create();
    $this->user->update(['group_id' => $group->id]);

    $foreignDraft   = wizardDraft($this->user, ['group_id' => $group->id]);
    $foreignVisible = Character::factory()->create(['user_id' => $this->user->id, 'group_id' => $group->id]);

    $viewer   = User::factory()->inGroup($group)->create();
    $ownDraft = wizardDraft($viewer, ['group_id' => $group->id]);

    $this->actingAs($viewer)
        ->get(route('dashboard'))
        ->assertInertia(fn (Assert $page) => $page
            ->where('auth.characters.others', function ($others) use ($foreignDraft, $foreignVisible) {
                $ids = collect($others)->pluck('id');

                return $ids->contains($foreignVisible->id) && ! $ids->contains($foreignDraft->id);
            })
            ->where('auth.characters.all', function ($all) use ($foreignDraft, $ownDraft) {
                $ids = collect($all)->pluck('id');

                return $ids->contains($ownDraft->id) && ! $ids->contains($foreignDraft->id);
            })
            ->where('auth.characters.own', fn ($own) => collect($own)->pluck('id')->contains($ownDraft->id))
        );

    // The wizard page never resumes another player's draft.
    $this->actingAs($viewer)
        ->get(route('character.create'))
        ->assertInertia(fn (Assert $page) => $page->where('draft.id', $ownDraft->id));
});

test('create page without a draft lists era occupations and no point pools', function () {
    $this->get(route('character.create'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Character/Create')
            ->where('draft', null)
            ->where('era', Era::Twenties->value)
            ->where('occupationPoints', null)
            ->where('personalPoints', null)
            ->where('wealth', null)
            ->where('occupations', function ($occupations) {
                $occupations = collect($occupations);

                return $occupations->count() === Occupation::count()
                    && $occupations->every(fn ($occupation) => filled($occupation['formula_label']));
            })
        );
});

test('create page computes point pools for a draft with an occupation', function () {
    $draft = journalistDraft($this->user);

    $this->get(route('character.create'))
        ->assertInertia(fn (Assert $page) => $page
            ->where('draft.id', $draft->id)
            ->where('occupationPoints', 280) // Journalist: EDU 70 × 4
            ->where('personalPoints', 160)   // INT 80 × 2
            ->where('wealth', null)
        );
});

test('create page reports 1920s wealth from the allocated credit rating', function () {
    $draft = journalistDraft($this->user);
    $draft->skills()->updateExistingPivot(Skill::where('slug', 'credit_rating')->value('id'), ['value' => 20]);

    $expected = Wealth::for(20, Era::Twenties);
    expect($expected['living_standard'])->toBe('Average')
        ->and($expected['cash'])->toBe(40.0)
        ->and($expected['assets'])->toBe(1000.0)
        ->and($expected['spending_level'])->toBe(10.0);

    $this->get(route('character.create'))
        ->assertInertia(fn (Assert $page) => $page
            ->where('era', Era::Twenties->value)
            // Integral floats lose their fraction in the JSON round trip.
            ->where('wealth', json_decode(json_encode($expected), true))
        );
});

test('create page uses the modern era of the users group for wealth', function () {
    Group::create(['name' => 'Modern Group', 'era' => Era::Modern->value])
        ->users()->save($this->user);

    $draft = journalistDraft($this->user);
    $draft->skills()->updateExistingPivot(Skill::where('slug', 'credit_rating')->value('id'), ['value' => 20]);

    $expected = Wealth::for(20, Era::Modern);
    expect($expected['cash'])->toBe(800.0)
        ->and($expected['assets'])->toBe(20000.0)
        ->and($expected['spending_level'])->toBe(200.0);

    $this->get(route('character.create'))
        ->assertInertia(fn (Assert $page) => $page
            ->where('era', Era::Modern->value)
            ->where('wealth', json_decode(json_encode($expected), true))
        );
});

test('every skill slug referenced by the occupation seeder exists', function () {
    $known = Skill::pluck('slug');

    Occupation::all()->each(function (Occupation $occupation) use ($known) {
        $referenced = collect($occupation->skills)->flatMap(fn (mixed $entry): array => match (true) {
            is_string($entry)                                         => [$entry],
            is_array($entry) && ($entry['type'] ?? null) === 'choice' => $entry['options'],
            default                                                   => [],
        });

        $missing = $referenced->diff($known);

        $this->assertSame(
            [],
            $missing->values()->all(),
            "Occupation [{$occupation->name}] references unknown skill slugs: {$missing->implode(', ')}"
        );
    });
});

describe('resuming a draft', function () {
    beforeEach(function () {
        $this->group  = Group::factory()->create();
        $this->active = $this->group->startGame('The Haunting');
        $this->past   = $this->group->startGame('The Lightless Beacon');

        $this->user->update(['group_id' => $this->group->id]);
    });

    test('a draft in the game being played is resumed', function () {
        $draft = wizardDraft($this->user, ['group_id' => $this->group->id]);
        $draft->games()->sync([$this->active->id]);

        $this->get(route('character.create'))
            ->assertInertia(fn (Assert $page) => $page->where('draft.id', $draft->id));
    });

    test('a draft left in a finished campaign starts the wizard over', function () {
        $draft = wizardDraft($this->user, ['group_id' => $this->group->id]);
        $draft->games()->sync([$this->past->id]);

        $this->get(route('character.create'))
            ->assertInertia(fn (Assert $page) => $page->where('draft', null));
    });

    test('a draft in no game at all starts the wizard over', function () {
        wizardDraft($this->user, ['group_id' => $this->group->id]);

        $this->get(route('character.create'))
            ->assertInertia(fn (Assert $page) => $page->where('draft', null));
    });

    test('the most recent draft in the game being played wins', function () {
        $older = wizardDraft($this->user, ['group_id' => $this->group->id]);
        $older->games()->sync([$this->active->id]);

        $newer = wizardDraft($this->user, ['group_id' => $this->group->id]);
        $newer->games()->sync([$this->active->id]);

        DB::table('characters')->where('id', $older->id)->update(['updated_at' => '2026-06-01 00:00:00']);
        DB::table('characters')->where('id', $newer->id)->update(['updated_at' => '2026-07-01 00:00:00']);

        $this->get(route('character.create'))
            ->assertInertia(fn (Assert $page) => $page->where('draft.id', $newer->id));
    });

    test('a group playing nothing still resumes its latest draft', function () {
        $draft = wizardDraft($this->user, ['group_id' => $this->group->id]);
        $draft->games()->sync([$this->past->id]);

        $this->group->update(['active_game_id' => null]);

        $this->get(route('character.create'))
            ->assertInertia(fn (Assert $page) => $page->where('draft.id', $draft->id));
    });

    test('a draft begun here is resumed rather than duplicated', function () {
        $this->post(route('character.wizard.store'), validProfile());

        $draft = Character::where('name', 'Harvey Walters')->firstOrFail();

        $this->get(route('character.create'))
            ->assertInertia(fn (Assert $page) => $page->where('draft.id', $draft->id));
    });
});
