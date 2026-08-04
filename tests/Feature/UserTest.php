<?php

use App\Models\Character;
use App\Models\Group;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

beforeEach(function () {
    $this->seed();
    $this->withoutMiddleware(VerifyCsrfToken::class);
});

test('keeper roll against spot hidden', function () {
    $group      = Group::factory()->create();
    $user       = User::factory()->inGroup($group)->create();
    $characters = Character::factory(8)->create();
    $characters->each(function ($character) use ($group) {
        $character->player->update(['group_id' => $group->id]);
        $character->skills->filter(function (Skill $skill) {
            return $skill->slug === 'spot-hidden';
        })->each(function (Skill $skill) {
            $skill->pivot->value = 50;
            $skill->pivot->save();
        });
        $character->refresh();
    });

    /**  'skill_slug' => 'required|string|exists:skills,slug',
            'users' => 'required|array',
            'users.*' => 'integer|exists:users,id',
     **/
    try {
        /** @var Illuminate\Testing\TestResponse $response */
        $response = $this->actingAs($user)->post(route('skill.roll'), [
            'skill_slug' => 'spot-hidden',
            'users'      => $characters->map(fn (Character $character) => $character->user_id)->toArray(),
        ]);
    } catch (Exception $exception) {
        dd($exception->getMessage());
    }

    $response->assertStatus(200);
    // Group scoping must not have filtered out any of the groupmates.
    $response->assertJsonCount(8);
});

test('rolls silently skip users outside the rollers group', function () {
    $user       = User::factory()->inGroup()->create();
    $characters = Character::factory(2)->create();

    $response = $this->actingAs($user)->post(route('skill.roll'), [
        'skill_slug' => 'spot-hidden',
        'users'      => $characters->map(fn (Character $character) => $character->user_id)->toArray(),
    ]);

    $response->assertStatus(200);
    $response->assertJsonCount(0);
});

test('a keeper can update the role of a groupmate', function () {
    $group  = Group::factory()->create();
    $keeper = User::factory()->inGroup($group)->create();
    $keeper->assignRole('keeper');
    $player = User::factory()->inGroup($group)->create();
    $player->assignRole('player');

    $response = $this->actingAs($keeper)->put(route('users.role', $player), [
        'role' => 'Keeper of Arcane Lore',
    ]);

    $response->assertRedirect();
    expect($player->fresh()->role)->toBe('Keeper of Arcane Lore');
});

test('a player cannot update roles, not even their own', function () {
    $group  = Group::factory()->create();
    $player = User::factory()->inGroup($group)->create();
    $player->assignRole('player');
    $groupmate = User::factory()->inGroup($group)->create();

    $this->actingAs($player)->put(route('users.role', $player), [
        'role' => 'Keeper of Arcane Lore',
    ])->assertForbidden();

    $this->actingAs($player)->put(route('users.role', $groupmate), [
        'role' => 'Keeper of Arcane Lore',
    ])->assertForbidden();

    expect($player->fresh()->role)->not->toBe('Keeper of Arcane Lore');
});

test('a keeper cannot update the role of a user outside their group', function () {
    $keeper = User::factory()->inGroup()->create();
    $keeper->assignRole('keeper');
    $stranger = User::factory()->inGroup()->create();

    $this->actingAs($keeper)->put(route('users.role', $stranger), [
        'role' => 'Keeper of Arcane Lore',
    ])->assertForbidden();
});

test('a keeper can delete a groupmate', function () {
    $group  = Group::factory()->create();
    $keeper = User::factory()->inGroup($group)->create();
    $keeper->assignRole('keeper');
    $player = User::factory()->inGroup($group)->create();

    $this->actingAs($keeper)->delete(route('users.destroy', $player))->assertRedirect();

    expect($player->fresh()->trashed())->toBeTrue();
});

test('a player cannot delete users', function () {
    $group  = Group::factory()->create();
    $player = User::factory()->inGroup($group)->create();
    $player->assignRole('player');
    $groupmate = User::factory()->inGroup($group)->create();

    $this->actingAs($player)->delete(route('users.destroy', $groupmate))->assertForbidden();

    expect($groupmate->fresh()->trashed())->toBeFalse();
});

test('a keeper cannot delete a user outside their group', function () {
    $keeper = User::factory()->inGroup()->create();
    $keeper->assignRole('keeper');
    $stranger = User::factory()->inGroup()->create();

    $this->actingAs($keeper)->delete(route('users.destroy', $stranger))->assertForbidden();

    expect($stranger->fresh()->trashed())->toBeFalse();
});
