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
