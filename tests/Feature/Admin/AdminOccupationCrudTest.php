<?php

use App\Enums\Era;
use App\Enums\RoleEnum;
use App\Models\Character;
use App\Models\Occupation;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->seed(\Database\Seeders\SkillSeeder::class);

    config()->set('cthulhu.admin.edit_reference_data', true);

    $this->admin = User::factory()->inGroup()->create();
    $this->admin->assignRole(RoleEnum::ADMIN->value);
});

/**
 * @param  array<string, mixed> $overrides
 * @return array<string, mixed>
 */
function adminOccupationPayload(array $overrides = []): array
{
    return [
        'name'                 => 'Lighthouse Keeper',
        'description'          => 'Alone with the lamp and whatever the sea brings in.',
        'eras'                 => Era::all(),
        'skill_points_formula' => [['multiplier' => 2, 'options' => ['education']]],
        'credit_rating_min'    => 9,
        'credit_rating_max'    => 30,
        'skills'               => ['listen', 'spot-hidden'],
        'any_count'            => 0,
        ...$overrides,
    ];
}

function customOccupation(?User $author = null): Occupation
{
    return Occupation::create([
        ...adminOccupationPayload(['name' => 'Wireless Operator']),
        'skills'     => ['listen', 'electric_repair'],
        'is_custom'  => true,
        'created_by' => $author?->id,
    ]);
}

test('the occupations page lists them with their formula spelled out', function () {
    $this->seed(\Database\Seeders\OccupationSeeder::class);

    $this->actingAs($this->admin)
        ->get(route('admin.occupations.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Occupations')
            ->where('editable', true)
            ->has('skillOptions')
            ->where('characteristics.education', 'EDU')
            ->where('occupations.data', fn ($rows) => collect($rows)->contains(
                fn (array $row): bool => $row['name'] === 'Antiquarian' && $row['formula_label'] === 'EDU × 4'
            ))
        );
});

test('an admin can add an occupation', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.occupations.store'), adminOccupationPayload())
        ->assertRedirect();

    $occupation = Occupation::where('name', 'Lighthouse Keeper')->firstOrFail();

    expect($occupation->is_custom)->toBeFalse()
        ->and($occupation->created_by)->toBeNull()
        ->and($occupation->skills)->toBe(['listen', 'spot-hidden']);
});

test('an admin can edit a player-written occupation without disowning it', function () {
    $player     = User::factory()->create();
    $occupation = customOccupation($player);

    $this->actingAs($this->admin)
        ->put(route('admin.occupations.update', $occupation), adminOccupationPayload([
            'name'        => 'Radio Operator',
            'description' => 'Tidied up by the Keeper.',
        ]))
        ->assertRedirect();

    $occupation->refresh();

    expect($occupation->name)->toBe('Radio Operator')
        ->and($occupation->is_custom)->toBeTrue()
        ->and($occupation->created_by)->toBe($player->id);
});

test('retiring an occupation takes it off the list but leaves the sheets alone', function () {
    $occupation = customOccupation();

    $character = Character::factory()->create([
        'occupation_id' => $occupation->id,
        'occupation'    => $occupation->name,
    ]);

    $this->actingAs($this->admin)
        ->delete(route('admin.occupations.destroy', $occupation))
        ->assertRedirect();

    expect(Occupation::find($occupation->id))->toBeNull()
        ->and(Occupation::onlyTrashed()->find($occupation->id))->not->toBeNull()
        ->and($character->fresh()->occupation_id)->toBe($occupation->id);
});

test('a retired occupation can be restored', function () {
    $occupation = customOccupation();
    $occupation->delete();

    $this->actingAs($this->admin)
        ->put(route('admin.occupations.restore', ['id' => $occupation->id]))
        ->assertRedirect();

    expect(Occupation::find($occupation->id))->not->toBeNull();
});

test('the player-written filter shows only what came out of the wizard', function () {
    $this->seed(\Database\Seeders\OccupationSeeder::class);
    customOccupation(User::factory()->create());

    $this->actingAs($this->admin)
        ->get(route('admin.occupations.index', ['custom' => true]))
        ->assertInertia(fn (Assert $page) => $page
            ->where('counts.custom', 1)
            ->where('occupations.data', fn ($rows) => collect($rows)->pluck('name')->all() === ['Wireless Operator'])
        );
});

test('the era filter narrows the list', function () {
    Occupation::create(adminOccupationPayload(['name' => 'Computer Programmer', 'eras' => [Era::Modern->value]]));
    Occupation::create(adminOccupationPayload(['name' => 'Gentleman Detective', 'eras' => [Era::Twenties->value]]));

    $this->actingAs($this->admin)
        ->get(route('admin.occupations.index', ['era' => Era::Modern->value]))
        ->assertInertia(fn (Assert $page) => $page->where(
            'occupations.data',
            fn ($rows) => collect($rows)->pluck('name')->all() === ['Computer Programmer']
        ));
});

test('an occupation cannot take the name of a retired one', function () {
    $occupation = customOccupation();
    $occupation->delete();

    $this->actingAs($this->admin)
        ->post(route('admin.occupations.store'), adminOccupationPayload(['name' => 'Wireless Operator']))
        ->assertSessionHasErrors('name');
});

test('an occupation keeps its own name when edited', function () {
    $occupation = customOccupation();

    $this->actingAs($this->admin)
        ->put(route('admin.occupations.update', $occupation), adminOccupationPayload([
            'name'        => 'Wireless Operator',
            'description' => 'Reworded, same name.',
        ]))
        ->assertSessionHasNoErrors();
});

test('a player may not reach the admin occupations page', function () {
    $player = User::factory()->inGroup()->create();
    $player->assignRole(RoleEnum::PLAYER->value);

    $this->actingAs($player)
        ->get(route('admin.occupations.index'))
        ->assertForbidden();
});

test('with reference data locked the writes are gone and the page is read only', function () {
    config()->set('cthulhu.admin.edit_reference_data', false);

    $this->actingAs($this->admin)
        ->get(route('admin.occupations.index'))
        ->assertInertia(fn (Assert $page) => $page->where('editable', false));

    $this->actingAs($this->admin)
        ->post(route('admin.occupations.store'), adminOccupationPayload())
        ->assertForbidden();

    expect(Occupation::where('name', 'Lighthouse Keeper')->exists())->toBeFalse();
});
