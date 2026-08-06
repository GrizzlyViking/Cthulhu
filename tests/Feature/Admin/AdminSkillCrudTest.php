<?php

use App\Enums\RoleEnum;
use App\Models\Character;
use App\Models\Skill;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    config()->set('cthulhu.admin.edit_reference_data', true);

    $this->admin = User::factory()->inGroup()->create();
    $this->admin->assignRole(RoleEnum::ADMIN->value);
});

/**
 * @param  array<string, mixed> $overrides
 * @return array<string, mixed>
 */
function skillPayload(array $overrides = []): array
{
    return [
        'display_name'   => 'Dream Lore',
        'description'    => 'What the sleeper knows and cannot prove.',
        'starting_value' => 5,
        ...$overrides,
    ];
}

test('an admin can add a skill, and the slug is derived from the name', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.skills.store'), skillPayload())
        ->assertRedirect();

    $skill = Skill::where('display_name', 'Dream Lore')->firstOrFail();

    expect($skill->slug)->toBe('dream-lore')
        ->and($skill->starting_value)->toBe(5);
});

test('a slug given by hand is kept', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.skills.store'), skillPayload(['slug' => 'oneiromancy']))
        ->assertRedirect();

    expect(Skill::where('slug', 'oneiromancy')->exists())->toBeTrue();
});

test('a new skill sorts after everything that already exists', function () {
    $highest = Skill::withTrashed()->max('order_by');

    $this->actingAs($this->admin)->post(route('admin.skills.store'), skillPayload());

    expect(Skill::where('slug', 'dream-lore')->firstOrFail()->order_by)->toBe($highest + 1);
});

test('an admin can edit a skill', function () {
    $skill = Skill::factory()->create(['display_name' => 'Dream Lore', 'slug' => 'dream-lore']);

    $this->actingAs($this->admin)
        ->put(route('admin.skills.update', $skill), skillPayload([
            'slug'           => 'dream-lore',
            'display_name'   => 'Dreaming',
            'starting_value' => 12,
        ]))
        ->assertRedirect();

    expect($skill->fresh()->display_name)->toBe('Dreaming')
        ->and($skill->fresh()->starting_value)->toBe(12);
});

test('a duplicate name is refused, and says so when the clash is with a retired skill', function () {
    Skill::factory()->create(['display_name' => 'Dream Lore', 'slug' => 'dream-lore'])->delete();

    $this->actingAs($this->admin)
        ->post(route('admin.skills.store'), skillPayload())
        ->assertSessionHasErrors('display_name');

    expect(session('errors')->first('display_name'))->toContain('retired');
});

test('a name whose derived slug collides is refused rather than dying on the constraint', function () {
    Skill::factory()->create(['display_name' => 'Dream lore', 'slug' => 'dream-lore']);

    $this->actingAs($this->admin)
        ->post(route('admin.skills.store'), skillPayload())
        ->assertSessionHasErrors('slug');

    expect(Skill::where('display_name', 'Dream Lore')->exists())->toBeFalse();
});

test('retiring a skill is a soft delete that takes it off every sheet', function () {
    $skill = Skill::factory()->create(['display_name' => 'Dream Lore', 'slug' => 'dream-lore']);
    // The character factory attaches every skill that exists, this one included.
    $character = Character::factory()->create();
    $character->skills()->updateExistingPivot($skill->id, ['value' => 65]);

    expect($character->fresh()->skills->pluck('slug'))->toContain('dream-lore');

    $this->actingAs($this->admin)
        ->delete(route('admin.skills.destroy', $skill))
        ->assertRedirect();

    expect($skill->fresh()->trashed())->toBeTrue()
        ->and(Skill::where('slug', 'dream-lore')->exists())->toBeFalse()
        // The pivot survives, which is what makes the restore below meaningful.
        ->and($character->fresh()->skills->pluck('slug'))->not->toContain('dream-lore');
});

test('restoring a skill puts it back on the sheets with the values they had', function () {
    $skill = Skill::factory()->create(['display_name' => 'Dream Lore', 'slug' => 'dream-lore']);
    // The character factory attaches every skill that exists, this one included.
    $character = Character::factory()->create();
    $character->skills()->updateExistingPivot($skill->id, ['value' => 65]);

    $this->actingAs($this->admin)->delete(route('admin.skills.destroy', $skill));

    $this->actingAs($this->admin)
        ->put(route('admin.skills.restore', ['slug' => 'dream-lore']))
        ->assertRedirect();

    $restored = $character->fresh()->skills->firstWhere('slug', 'dream-lore');

    expect($skill->fresh()->trashed())->toBeFalse()
        ->and($restored)->not->toBeNull()
        ->and($restored->pivot->value)->toBe(65);
});

test('a retired skill cannot be edited or retired again through the bound routes', function () {
    $skill = Skill::factory()->create(['display_name' => 'Dream Lore', 'slug' => 'dream-lore']);
    $skill->delete();

    $this->actingAs($this->admin)->put(route('admin.skills.update', 'dream-lore'), skillPayload())->assertNotFound();
    $this->actingAs($this->admin)->delete(route('admin.skills.destroy', 'dream-lore'))->assertNotFound();
});

test('the retired list shows retired skills and the live list does not', function () {
    Skill::factory()->create(['display_name' => 'Dream Lore', 'slug' => 'dream-lore'])->delete();

    $this->actingAs($this->admin)
        ->get(route('admin.skills.index', ['search' => 'dream-lore']))
        ->assertInertia(fn (Assert $page) => $page->has('skills.data', 0));

    $this->actingAs($this->admin)
        ->get(route('admin.skills.index', ['search' => 'dream-lore', 'trashed' => 1]))
        ->assertInertia(fn (Assert $page) => $page
            ->has('skills.data', 1)
            ->where('skills.data.0.slug', 'dream-lore')
            ->where('filters.trashed', true));
});

test('the list carries how many sheets each skill is on', function () {
    Skill::factory()->create(['display_name' => 'Dream Lore', 'slug' => 'dream-lore']);

    // The factory gives each character every skill, so both pick this one up.
    Character::factory()->count(2)->create();

    $this->actingAs($this->admin)
        ->get(route('admin.skills.index', ['search' => 'dream-lore']))
        ->assertInertia(fn (Assert $page) => $page->where('skills.data.0.characters_count', 2));
});

test('a keeper cannot write to the skill list', function () {
    $keeper = User::factory()->inGroup()->create();
    $keeper->assignRole(RoleEnum::KEEPER->value);

    $this->actingAs($keeper)->post(route('admin.skills.store'), skillPayload())->assertForbidden();

    expect(Skill::where('display_name', 'Dream Lore')->exists())->toBeFalse();
});
