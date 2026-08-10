<?php

use App\Enums\CharacterStatus;
use App\Models\Character;
use App\Models\Group;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

function memberWithCharacter(Group $group): array
{
    $user      = User::factory()->inGroup($group)->create();
    $character = Character::factory()->create([
        'user_id'  => $user->id,
        'group_id' => $group->id,
        'status'   => CharacterStatus::Complete,
    ]);

    return [$user, $character];
}

test('a player sees the characters and users of their own group', function () {
    $group                    = Group::factory()->create();
    $viewer                   = User::factory()->inGroup($group)->create();
    [$groupmate, $groupChar]  = memberWithCharacter($group);
    [$outsider, $foreignChar] = memberWithCharacter(Group::factory()->create());

    $this->actingAs($viewer)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('auth.characters.all', function ($all) use ($groupChar, $foreignChar) {
                $ids = collect($all)->pluck('id');

                return $ids->contains($groupChar->id) && ! $ids->contains($foreignChar->id);
            })
            ->where('auth.characters.others', function ($others) use ($groupChar, $foreignChar) {
                $ids = collect($others)->pluck('id');

                return $ids->contains($groupChar->id) && ! $ids->contains($foreignChar->id);
            })
            ->where('auth.users', function ($users) use ($groupmate, $outsider) {
                $ids = collect($users)->pluck('id');

                return $ids->contains($groupmate->id) && ! $ids->contains($outsider->id);
            })
        );
});

test('an ungrouped player sees an empty world besides themselves', function () {
    $viewer = User::factory()->create();

    $ownCharacter = Character::factory()->create([
        'user_id' => $viewer->id,
        'status'  => CharacterStatus::Complete,
    ]);

    // Both a grouped and another ungrouped character exist elsewhere.
    memberWithCharacter(Group::factory()->create());
    $strayCharacter = Character::factory()->create(['status' => CharacterStatus::Complete]);

    $this->actingAs($viewer)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('auth.characters.all', fn ($all) => collect($all)->pluck('id')->all() === [$ownCharacter->id])
            ->where('auth.characters.others', fn ($others) => collect($others)->isEmpty())
            ->where('auth.users', fn ($users) => collect($users)->pluck('id')->all() === [$viewer->id])
        );
});

test('drafts of groupmates stay invisible in the shared props', function () {
    $group  = Group::factory()->create();
    $viewer = User::factory()->inGroup($group)->create();
    $author = User::factory()->inGroup($group)->create();

    $draft = Character::factory()->create([
        'user_id'  => $author->id,
        'group_id' => $group->id,
        'status'   => CharacterStatus::Draft,
    ]);

    $this->actingAs($viewer)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('auth.characters.all', fn ($all) => ! collect($all)->pluck('id')->contains($draft->id))
            ->where('auth.characters.others', fn ($others) => ! collect($others)->pluck('id')->contains($draft->id))
        );
});

test('a character created through the wizard inherits the creators group', function () {
    $group = Group::factory()->create();
    $user  = User::factory()->inGroup($group)->create();

    $this->actingAs($user)->post(route('character.wizard.store'), [
        'name'       => 'Harvey Walters',
        'gender'     => 'Male',
        'age'        => 42,
        'residence'  => 'Arkham',
        'birthplace' => 'Boston',
    ])->assertRedirect(route('character.create'));

    $character = Character::where('name', 'Harvey Walters')->firstOrFail();

    expect($character->group_id)->toBe($group->id);
});

test('an ungrouped creators wizard character carries no group', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('character.wizard.store'), [
        'name'       => 'Harvey Walters',
        'gender'     => 'Male',
        'age'        => 42,
        'residence'  => 'Arkham',
        'birthplace' => 'Boston',
    ])->assertRedirect(route('character.create'));

    expect(Character::where('name', 'Harvey Walters')->firstOrFail()->group_id)->toBeNull();
});

test('a player cannot view a character from another group', function () {
    [$viewer]        = memberWithCharacter(Group::factory()->create());
    [, $foreignChar] = memberWithCharacter(Group::factory()->create());

    $this->actingAs($viewer)
        ->get(route('character.show', $foreignChar->slug))
        ->assertForbidden();
});

test('a player may view a completed character of a groupmate', function () {
    $group         = Group::factory()->create();
    [$viewer]      = memberWithCharacter($group);
    [, $groupChar] = memberWithCharacter($group);

    expect($viewer->can('view', $groupChar))->toBeTrue();
});

test('an ungrouped player cannot view another ungrouped players character', function () {
    $viewer    = User::factory()->create();
    $character = Character::factory()->create(['status' => CharacterStatus::Complete]);

    expect($viewer->can('view', $character))->toBeFalse();
});
