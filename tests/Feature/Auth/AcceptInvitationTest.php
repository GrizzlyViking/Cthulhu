<?php

use App\Models\Invitation;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->withoutVite();
    $this->seed(RolesAndPermissionsSeeder::class);
});

test('the accept page renders for a valid token', function () {
    $invitation = Invitation::factory()->create();

    $this->get(route('invitation.show', $invitation->token))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Auth/AcceptInvitation')
            ->where('invalid', false)
            ->where('token', $invitation->token)
            ->where('email', $invitation->email)
            ->where('groupName', $invitation->group->name));
});

test('an unknown token renders the invalid state', function () {
    $this->get(route('invitation.show', 'not-a-real-token'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Auth/AcceptInvitation')
            ->where('invalid', true));
});

test('accepting a valid invitation creates the user in the group with the player role and logs them in', function () {
    $invitation = Invitation::factory()->create();

    $response = $this->post(route('invitation.store', $invitation->token), [
        'name'                  => 'Harvey Walters',
        'password'              => 'secret-password',
        'password_confirmation' => 'secret-password',
    ]);

    $response->assertRedirect(route('home', absolute: false));

    $user = User::where('email', $invitation->email)->first();

    expect($user)->not->toBeNull()
        ->and($user->name)->toBe('Harvey Walters')
        ->and($user->group_id)->toBe($invitation->group_id)
        ->and($user->hasRole('player'))->toBeTrue()
        ->and($user->roleNames())->toBe(['player'])
        ->and($invitation->fresh()->isAccepted())->toBeTrue();

    $this->assertAuthenticated();
    expect(Auth::id())->toBe($user->id);
});

test('the email is taken from the invitation, not the request', function () {
    $invitation = Invitation::factory()->create(['email' => 'invited@example.com']);

    $this->post(route('invitation.store', $invitation->token), [
        'name'                  => 'Sneaky Cultist',
        'email'                 => 'attacker@example.com',
        'password'              => 'secret-password',
        'password_confirmation' => 'secret-password',
    ]);

    expect(User::where('email', 'attacker@example.com')->exists())->toBeFalse()
        ->and(User::where('email', 'invited@example.com')->exists())->toBeTrue();
});

test('an expired token shows the invalid state and creates no user', function () {
    $invitation = Invitation::factory()->expired()->create();

    $this->get(route('invitation.show', $invitation->token))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Auth/AcceptInvitation')
            ->where('invalid', true));

    $this->post(route('invitation.store', $invitation->token), [
        'name'                  => 'Too Late',
        'password'              => 'secret-password',
        'password_confirmation' => 'secret-password',
    ])->assertRedirect(route('invitation.show', $invitation->token));

    expect(User::where('email', $invitation->email)->exists())->toBeFalse();
    $this->assertGuest();
});

test('an accepted token cannot be reused', function () {
    $invitation = Invitation::factory()->accepted()->create();

    $this->get(route('invitation.show', $invitation->token))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->where('invalid', true));

    $this->post(route('invitation.store', $invitation->token), [
        'name'                  => 'Second Try',
        'password'              => 'secret-password',
        'password_confirmation' => 'secret-password',
    ])->assertRedirect(route('invitation.show', $invitation->token));

    expect(User::where('email', $invitation->email)->exists())->toBeFalse();
    $this->assertGuest();
});

test('a token whose email meanwhile belongs to a user shows the invalid state', function () {
    $invitation = Invitation::factory()->create(['email' => 'taken@example.com']);
    User::factory()->create(['email' => 'taken@example.com']);

    $this->get(route('invitation.show', $invitation->token))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->where('invalid', true));

    $this->post(route('invitation.store', $invitation->token), [
        'name'                  => 'Doppelganger',
        'password'              => 'secret-password',
        'password_confirmation' => 'secret-password',
    ])->assertRedirect(route('invitation.show', $invitation->token));

    expect(User::where('email', 'taken@example.com')->count())->toBe(1);
    $this->assertGuest();
});
