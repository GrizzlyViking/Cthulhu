<?php

use App\Models\User;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email'    => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('home', absolute: false));
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email'    => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('blocked users can not authenticate even with valid credentials', function () {
    $user = User::factory()->blocked()->create();

    $response = $this->from('/login')->post('/login', [
        'email'    => $user->email,
        'password' => 'password',
    ]);

    $this->assertGuest();
    // The error must be indistinguishable from a bad password.
    $response->assertSessionHasErrors(['email' => trans('auth.failed')]);
});

test('users blocked mid-session are logged out on their next request', function () {
    $user = User::factory()->blocked()->create();

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertRedirect(route('login'));
    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
