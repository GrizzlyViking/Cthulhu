<?php

use App\Enums\Era;
use App\Misc\EquipmentTable;
use App\Misc\EraTable;
use App\Misc\WeaponTable;
use App\Models\Character;
use App\Models\EquipmentItem;
use App\Models\Group;
use App\Models\Skill;
use App\Models\User;
use App\Models\Weapon;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\SkillSeeder;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->withoutMiddleware(VerifyCsrfToken::class);
});

// ---- The guess table ----------------------------------------------------

test('every slug the era guesses name is a slug the equipment catalogue has', function () {
    $catalogue = array_column(EquipmentTable::all(), 'slug');

    $unknown = array_diff(
        [...EraTable::EQUIPMENT_TIMELESS, ...EraTable::EQUIPMENT_PERIOD_ONLY],
        $catalogue,
    );

    expect($unknown)->toBeEmpty();
});

test('the handbook availability cell is read into a list of eras', function (string $printed, array $expected) {
    expect(EraTable::forWeapon($printed))->toBe($expected);
})->with([
    ['1920s, Modern', ['1920s', 'modern']],
    ['1920s', ['1920s']],
    ['Modern', ['modern']],
    // "Rare" next to an era narrows it; on its own it says nothing about when.
    ['1920s, Rare', ['1920s']],
    ['Rare', ['1920s', 'modern']],
    // WWII kit is past the Twenties and still around.
    ['WWII, Later', ['modern']],
    // A house ruled weapon with the cell left empty is available throughout.
    ['', ['1920s', 'modern']],
]);

test('a weapon whose printed cell contradicts its own row is overridden by name', function () {
    // "1920s, Modern" in the era cell, "-/$10" in the cost cell, and chemical
    // mace not invented until the 1960s.
    expect(EraTable::forWeapon('1920s, Modern', 'Mace Spray'))->toBe([Era::Modern->value])
        // The same cell without the name still reads as the book prints it.
        ->and(EraTable::forWeapon('1920s, Modern'))->toBe(Era::all());
});

test('the weapons marked Rare alone are available in both eras, as their prices say', function () {
    // "Rare" is scarcity, not an era (Key, p.255), and each of these carries a
    // price on both sides of the book's "Cost 20s/Modern" cell.
    $rare = collect(WeaponTable::all())->where('era', 'Rare');

    expect($rare->pluck('name')->sort()->values()->all())->toBe([
        '.58 Springfield Rifle Musket', 'Flintlock', 'Spear, Thrown', 'War Boomerang',
    ]);

    $rare->each(function (array $weapon): void {
        [$twenties, $modern] = explode('/', $weapon['cost']);

        expect(EraTable::forWeapon($weapon['era'], $weapon['name']))->toBe(Era::all())
            ->and(trim($twenties))->not->toBe('-')
            ->and(trim($modern))->not->toBe('-');
    });
});

test('Rare beside an era narrows to that era', function () {
    // The .41 Revolver is "1920s, Rare" at "$30/-" — a dash where the modern
    // price would be.
    expect(EraTable::forWeapon('1920s, Rare', '.41 Revolver'))->toBe([Era::Twenties->value]);
});

test('the clothing sections are of their time, and the ordinary gear is not', function () {
    expect(EraTable::forEquipment('men-s-clothing--chesterfield-overcoat', EquipmentTable::MENS_CLOTHING))
        ->toBe([Era::Twenties->value])
        ->and(EraTable::forEquipment('tools--rope-50-feet', EquipmentTable::TOOLS))
        ->toBe(Era::all())
        // An exception inside a period section.
        ->and(EraTable::forEquipment('men-s-clothing--hiking-boots', EquipmentTable::MENS_CLOTHING))
        ->toBe(Era::all())
        // And one outside it.
        ->and(EraTable::forEquipment('communications--telegraph-outfit', EquipmentTable::COMMUNICATIONS))
        ->toBe([Era::Twenties->value]);
});

// ---- The column ---------------------------------------------------------

test('the seeded reference data carries the eras the guess table gives it', function () {
    // Weapons and equipment are laid down by migration; the skill list is not.
    $this->seed(SkillSeeder::class);

    expect(Skill::where('slug', 'fighting-chainsaw')->firstOrFail()->eras)->toBe([Era::Modern->value])
        ->and(Skill::where('slug', 'stealth')->firstOrFail()->eras)->toBe(Era::all())
        ->and(Weapon::where('name', 'Chainsaw')->firstOrFail()->eras)->toBe([Era::Modern->value])
        ->and(EquipmentItem::where('slug', 'outdoor-travel-gear--dark-lantern')->firstOrFail()->eras)
        ->toBe([Era::Twenties->value]);
});

test('a thing saved with no eras is available throughout rather than nowhere', function () {
    $skill = Skill::factory()->create(['eras' => []]);

    expect($skill->fresh()->eras)->toBe(Era::all());
});

test('the in-era scope keeps what belongs to the era and what belongs to both', function () {
    Skill::factory()->create(['slug' => 'zzq-both', 'eras' => Era::all()]);
    Skill::factory()->create(['slug' => 'zzq-twenties', 'eras' => [Era::Twenties->value]]);
    Skill::factory()->create(['slug' => 'zzq-modern', 'eras' => [Era::Modern->value]]);

    $slugs = fn (?Era $era) => Skill::query()->inEra($era)->where('slug', 'like', 'zzq-%')->pluck('slug')->sort()->values()->all();

    expect($slugs(Era::Twenties))->toBe(['zzq-both', 'zzq-twenties'])
        ->and($slugs(Era::Modern))->toBe(['zzq-both', 'zzq-modern'])
        // No era is every era, so a caller without one need not branch.
        ->and($slugs(null))->toBe(['zzq-both', 'zzq-modern', 'zzq-twenties']);
});

// ---- The sheet ----------------------------------------------------------

test('a character plays in their group era, and the Twenties while ungrouped', function () {
    $modern = Character::factory()->create([
        'group_id' => Group::factory()->create(['era' => Era::Modern])->id,
    ]);

    expect($modern->era())->toBe(Era::Modern)
        ->and(Character::factory()->create(['group_id' => null])->era())->toBe(Era::Twenties);
});

test('the sheet is told which era it is played in', function () {
    $group = Group::factory()->create(['era' => Era::Modern]);
    $owner = User::factory()->inGroup($group)->create();
    $owner->assignRole('player');

    $character = Character::factory()->create(['user_id' => $owner->id, 'group_id' => $group->id]);

    $this->actingAs($owner)
        ->get(route('character.show', $character))
        ->assertInertia(fn (Assert $page) => $page
            ->where('era', Era::Modern->value)
            ->has('eras', count(Era::cases())));
});

test('the equipment typeahead answers this era, and every era when asked', function () {
    $owner = User::factory()->inGroup()->create();
    $owner->assignRole('player');

    EquipmentItem::factory()->create(['name' => 'Zzq Lantern', 'eras' => [Era::Twenties->value]]);
    EquipmentItem::factory()->create(['name' => 'Zzq Torch', 'eras' => [Era::Modern->value]]);

    $names = fn (array $params) => collect(
        $this->actingAs($owner)->getJson(route('equipment.search', $params))->json('items')
    )->pluck('name')->sort()->values()->all();

    expect($names(['search' => 'Zzq', 'era' => Era::Twenties->value]))->toBe(['Zzq Lantern'])
        ->and($names(['search' => 'Zzq', 'era' => Era::Modern->value]))->toBe(['Zzq Torch'])
        ->and($names(['search' => 'Zzq', 'era' => Era::Twenties->value, 'all_eras' => true]))
        ->toBe(['Zzq Lantern', 'Zzq Torch'])
        // Nothing to say about the era is every era, not none of them.
        ->and($names(['search' => 'Zzq']))->toBe(['Zzq Lantern', 'Zzq Torch']);
});

test('something a player invents is available throughout', function () {
    $owner = User::factory()->inGroup()->create();
    $owner->assignRole('player');

    $character = Character::factory()->create(['user_id' => $owner->id, 'group_id' => $owner->group_id]);

    $this->actingAs($owner)
        ->post(route('equipment.store', $character), ['name' => 'Grandfather’s Reliquary'])
        ->assertRedirect();

    expect(EquipmentItem::where('name', 'Grandfather’s Reliquary')->firstOrFail()->eras)->toBe(Era::all());
});
