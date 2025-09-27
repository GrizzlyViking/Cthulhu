<?php

use App\Models\Group;
use App\Models\User;
use App\Models\Character;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

it('creates a group via factory', function () {
    $group = Group::factory()->create();

    expect($group->id)->not->toBeNull()
        ->and($group->name)->toBeString()->not->toBe('');
});

it('allows mass assignment of name', function () {
    $group = Group::create(['name' => 'Investigators']);

    expect($group->exists)->toBeTrue()
        ->and($group->fresh()->name)->toBe('Investigators');
});

it('relates to users via a many-to-many pivot (group_user)', function () {
    $group = Group::factory()->create();
    $users = User::factory()->count(2)->create();

    $group->users()->attach($users->pluck('id')->all());

    expect($group->users()->count())->toBe(2);

    foreach ($users as $user) {
        expect(DB::table('group_user')
            ->where('group_id', $group->id)
            ->where('user_id', $user->id)
            ->exists())->toBeTrue();
    }
});

it('relates to characters via a many-to-many pivot (group_character)', function () {
    $group = Group::factory()->create();
    $characters = Character::factory()->count(2)->create();

    $group->characters()->attach($characters->pluck('id')->all());

    expect($group->characters()->count())->toBe(2);

    foreach ($characters as $character) {
        expect(DB::table('group_character')
            ->where('group_id', $group->id)
            ->where('character_id', $character->id)
            ->exists())->toBeTrue();
    }
});

it('cascades pivot rows when a group is deleted', function () {
    $group = Group::factory()->create();
    $user = User::factory()->create();
    $character = Character::factory()->create();

    $group->users()->attach($user->id);
    $group->characters()->attach($character->id);

    $groupId = $group->id;

    $group->delete();

    expect(DB::table('group_user')->where('group_id', $groupId)->count())->toBe(0)
        ->and(DB::table('group_character')->where('group_id', $groupId)->count())->toBe(0);
});

it('cascades group_user pivot rows when a user is deleted', function () {
    $group = Group::factory()->create();
    $user = User::factory()->create();

    $group->users()->attach($user->id);
    $userId = $user->id;

    $user->delete();

    expect(DB::table('group_user')->where('user_id', $userId)->count())->toBe(0);
});

it('cascades group_character pivot rows when a character is deleted', function () {
    $group = Group::factory()->create();
    $character = Character::factory()->create();

    $group->characters()->attach($character->id);
    $characterId = $character->id;

    $character->delete();

    expect(DB::table('group_character')->where('character_id', $characterId)->count())->toBe(0);
});
