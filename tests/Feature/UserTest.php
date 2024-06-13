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
    $user = User::factory()->create();
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
        $response = $this->actingAs($user)->post('/character/rollForSkill', [
            'skill_slug' => 'spot-hidden',
            'users' => $characters->map(fn (Character $character) => $character->id)
        ]);
    } catch (Exception $exception) {
        dd($exception->getMessage());
    }

    dd($response->getContent());

    $response->assertStatus(200);
});
