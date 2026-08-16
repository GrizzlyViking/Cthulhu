<?php

use App\Enums\CharacterStatus;
use App\Enums\Era;
use App\Models\Character;
use App\Models\Occupation;
use App\Models\Skill;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

/*
 * A player writing their own occupation in the wizard. What they write joins
 * the list everyone picks from, which is the point of the feature — see
 * CharacterWizardController::storeOccupation.
 */

beforeEach(function () {
    $this->seed(\Database\Seeders\SkillSeeder::class);
    $this->seed(\Database\Seeders\OccupationSeeder::class);

    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

function occupationDraft(User $user, array $attributes = []): Character
{
    return Character::factory()->create([
        'user_id'      => $user->id,
        'status'       => CharacterStatus::Draft,
        'wizard_step'  => 2,
        'intelligence' => 80,
        'education'    => 70,
        'dexterity'    => 60,
        ...$attributes,
    ]);
}

/**
 * @param  array<string, mixed> $overrides
 * @return array<string, mixed>
 */
function customOccupationPayload(array $overrides = []): array
{
    return [
        'name'                 => 'Lighthouse Keeper',
        'description'          => 'Alone with the lamp and whatever the sea brings in.',
        'eras'                 => Era::all(),
        'skill_points_formula' => [
            ['multiplier' => 2, 'options' => ['education']],
            ['multiplier' => 2, 'options' => ['strength', 'dexterity']],
        ],
        'credit_rating_min' => 9,
        'credit_rating_max' => 30,
        'skills'            => ['listen', 'spot-hidden', 'mech_repair'],
        'choices'           => [
            ['count' => 1, 'options' => ['charm', 'persuade'], 'label' => 'one interpersonal skill'],
        ],
        'any_count' => 1,
        'any_label' => 'any one other skill',
        ...$overrides,
    ];
}

test('a player can write an occupation, and it joins the shared list', function () {
    $draft = occupationDraft($this->user);

    $this->post(route('character.wizard.occupation.store', $draft->slug), customOccupationPayload())
        ->assertRedirect(route('character.create'));

    $occupation = Occupation::where('name', 'Lighthouse Keeper')->firstOrFail();

    expect($occupation->is_custom)->toBeTrue()
        ->and($occupation->created_by)->toBe($this->user->id)
        ->and($occupation->credit_rating_min)->toBe(9)
        ->and($occupation->credit_rating_max)->toBe(30)
        ->and($occupation->eras)->toBe(Era::all())
        ->and($occupation->formulaLabel())->toBe('EDU × 2 + STR or DEX × 2');
});

test('the three kinds of skill entry are folded back into one column', function () {
    $draft = occupationDraft($this->user);

    $this->post(route('character.wizard.occupation.store', $draft->slug), customOccupationPayload());

    $occupation = Occupation::where('name', 'Lighthouse Keeper')->firstOrFail();

    expect($occupation->skills)->toBe([
        'listen',
        'spot-hidden',
        'mech_repair',
        ['type' => 'choice', 'count' => 1, 'options' => ['charm', 'persuade'], 'label' => 'one interpersonal skill'],
        ['type' => 'any', 'count' => 1, 'label' => 'any one other skill'],
    ]);
});

test('an unlabelled choice and free slot are described for the player', function () {
    $draft = occupationDraft($this->user);

    $this->post(route('character.wizard.occupation.store', $draft->slug), customOccupationPayload([
        'choices'   => [['count' => 2, 'options' => ['charm', 'persuade', 'intimidate']]],
        'any_count' => 1,
        'any_label' => '',
    ]));

    $skills = Occupation::where('name', 'Lighthouse Keeper')->firstOrFail()->skills;

    expect($skills[3]['label'])->toBe('2 of these skills')
        ->and($skills[4]['label'])->toBe('any one other skill');
});

test('writing an occupation chooses it for the draft without skipping the step', function () {
    $draft = occupationDraft($this->user, ['wizard_step' => 2]);

    $this->post(route('character.wizard.occupation.store', $draft->slug), customOccupationPayload());

    $occupation = Occupation::where('name', 'Lighthouse Keeper')->firstOrFail();

    $draft->refresh();

    expect($draft->occupation_id)->toBe($occupation->id)
        ->and($draft->occupation)->toBe('Lighthouse Keeper')
        ->and($draft->wizard_step)->toBe(3);
});

test('the new occupation is offered to the next player', function () {
    $draft = occupationDraft($this->user);

    $this->post(route('character.wizard.occupation.store', $draft->slug), customOccupationPayload());

    $other = User::factory()->create();

    $this->actingAs($other)
        ->get(route('character.create'))
        ->assertInertia(fn (Assert $page) => $page->where(
            'occupations',
            fn ($occupations) => collect($occupations)->contains('name', 'Lighthouse Keeper')
        ));
});

test('an occupation may be marked for one era only, and is then not offered in the other', function () {
    $draft = occupationDraft($this->user);

    $this->post(route('character.wizard.occupation.store', $draft->slug), customOccupationPayload([
        'name' => 'Computer Programmer',
        'eras' => [Era::Modern->value],
    ]));

    // The group plays the Twenties, so a modern-only occupation is not offered.
    $this->get(route('character.create'))
        ->assertInertia(fn (Assert $page) => $page->where(
            'occupations',
            fn ($occupations) => ! collect($occupations)->contains('name', 'Computer Programmer')
        ));
});

test('an occupation cannot take the name of one that already exists', function () {
    $draft = occupationDraft($this->user);

    $this->post(route('character.wizard.occupation.store', $draft->slug), customOccupationPayload([
        'name' => 'Antiquarian',
    ]))->assertSessionHasErrors('name');
});

test('an occupation needs a name, a description, a formula, a range and a skill', function (array $payload, string $field) {
    $draft = occupationDraft($this->user);

    $this->post(route('character.wizard.occupation.store', $draft->slug), customOccupationPayload($payload))
        ->assertSessionHasErrors($field);
})->with([
    'no name'                        => [['name' => ''], 'name'],
    'no description'                 => [['description' => ''], 'description'],
    'no era'                         => [['eras' => []], 'eras'],
    'no formula'                     => [['skill_points_formula' => []], 'skill_points_formula'],
    'formula with no characteristic' => [['skill_points_formula' => [['multiplier' => 2, 'options' => []]]], 'skill_points_formula.0.options'],
    'unknown characteristic'         => [['skill_points_formula' => [['multiplier' => 2, 'options' => ['charisma']]]], 'skill_points_formula.0.options.0'],
    'no skills'                      => [['skills' => []], 'skills'],
    'unknown skill'                  => [['skills' => ['telepathy']], 'skills.0'],
    'inverted range'                 => [['credit_rating_min' => 60, 'credit_rating_max' => 30], 'credit_rating_max'],
]);

test('credit rating and the mythos cannot be occupation skills', function (string $slug) {
    $draft = occupationDraft($this->user);

    // Credit Rating has its own range, and no points may ever go on the Mythos:
    // listing either offers points the skills step then refuses.
    $this->post(route('character.wizard.occupation.store', $draft->slug), customOccupationPayload([
        'skills' => ['listen', $slug],
    ]))->assertSessionHasErrors('skills.1');

    $this->post(route('character.wizard.occupation.store', $draft->slug), customOccupationPayload([
        'choices' => [['count' => 1, 'options' => ['charm', $slug]]],
    ]))->assertSessionHasErrors('choices.0.options.1');
})->with(['credit_rating', 'cthulhu_mythos']);

test('neither is offered in the picker', function () {
    occupationDraft($this->user);

    $this->get(route('character.create'))
        ->assertInertia(fn (Assert $page) => $page->where(
            'skillOptions',
            fn ($options) => collect($options)->pluck('slug')
                ->intersect(['credit_rating', 'cthulhu_mythos'])
                ->isEmpty()
        ));
});

test('a choice offering fewer skills than it asks for is refused', function () {
    $draft = occupationDraft($this->user);

    $this->post(route('character.wizard.occupation.store', $draft->slug), customOccupationPayload([
        'choices' => [['count' => 3, 'options' => ['charm', 'persuade']]],
    ]))->assertSessionHasErrors('choices.0.count');
});

test('a retired skill cannot be named by a new occupation', function () {
    Skill::where('slug', 'listen')->firstOrFail()->delete();

    $draft = occupationDraft($this->user);

    $this->post(route('character.wizard.occupation.store', $draft->slug), customOccupationPayload())
        ->assertSessionHasErrors('skills.0');
});

test('a player cannot write an occupation onto someone else\'s draft', function () {
    $draft = occupationDraft(User::factory()->create());

    $this->post(route('character.wizard.occupation.store', $draft->slug), customOccupationPayload())
        ->assertForbidden();

    expect(Occupation::where('name', 'Lighthouse Keeper')->exists())->toBeFalse();
});

test('a finished character is not a draft to write occupations onto', function () {
    $character = occupationDraft($this->user, ['status' => CharacterStatus::Complete]);

    $this->post(route('character.wizard.occupation.store', $character->slug), customOccupationPayload())
        ->assertForbidden();
});

test('the wizard hands the custom occupation form what it needs', function () {
    occupationDraft($this->user);

    $this->get(route('character.create'))
        ->assertInertia(fn (Assert $page) => $page
            ->has('eras', 2)
            ->has('skillOptions')
            ->where('characteristics.education', 'EDU')
            ->etc()
        );
});
