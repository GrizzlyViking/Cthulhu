<?php

use App\Models\Character;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

beforeEach(function () {
    $this->seed();
    $this->withoutMiddleware(VerifyCsrfToken::class);
});

test('keeper roll against spot hidden', function () {
    $user       = User::factory()->create();
    $characters = Character::factory(8)->create();
    $characters->each(function ($character) {
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
});

test('update role', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->put(route('users.role', $user), [
        'role' => 'Keeper of Arcane Lore',
    ]);

    $response->assertRedirect();
    expect($user->fresh()->role)->toBe('Keeper of Arcane Lore');
});
