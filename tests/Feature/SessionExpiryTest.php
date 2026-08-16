<?php

use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Route;

/*
 * A sheet left open on a phone outlives its session, and the page goes on
 * holding the CSRF token it was rendered with. What the player used to get for
 * that was Symfony's "419 Page Expired" in an Inertia modal, with no way back.
 */

beforeEach(function () {
    Route::middleware('web')->match(['post', 'put'], '/testing/stale-token', function () {
        throw new TokenMismatchException('CSRF token mismatch.');
    });
});

test('a save from a page whose session has lapsed is sent back with a message', function () {
    $this->from('/dashboard')
        ->post('/testing/stale-token')
        ->assertRedirect('/dashboard')
        ->assertSessionHas('error');
});

test('it redirects with 303, so the browser does not repeat the failed save', function () {
    Route::middleware('web')->put('/testing/stale-put', function () {
        throw new TokenMismatchException('CSRF token mismatch.');
    });

    // A 302 is re-issued as another PUT, which 419s again, and the browser
    // loops until it gives up. Only a 303 turns the retry into a GET.
    $this->from('/character/george-bartolemew')
        ->put('/testing/stale-put')
        ->assertStatus(303);
});

test('the message says the change was not saved, so nobody assumes it was', function () {
    $this->from('/dashboard')->post('/testing/stale-token');

    expect(session('error'))->toContain('was not saved');
});

test('an inertia visit is sent back, so the banner comes with the page', function () {
    $this->from('/dashboard')
        ->withHeader('X-Inertia', 'true')
        ->post('/testing/stale-token')
        ->assertRedirect('/dashboard')
        ->assertSessionHas('error');
});

/*
 * Most of the sheet saves over axios, not Inertia. A redirect there is followed
 * by the browser itself and dropped by the .then(), and the flash is spent on a
 * page nobody sees — which is why the banner never arrived. The 419 has to come
 * back to the caller for the interceptor to act on.
 */
test('an axios save is answered 419 rather than redirected', function () {
    $this->from('/character/george-bartolemew')
        ->withHeader('X-Requested-With', 'XMLHttpRequest')
        ->put('/testing/stale-token')
        ->assertStatus(419)
        ->assertJson(['message' => 'That page had been open long enough to go stale, so the change was not saved. Please try it again.']);
});

test('the message waits in the session for the page the interceptor asks for', function () {
    $this->withHeader('X-Requested-With', 'XMLHttpRequest')
        ->put('/testing/stale-token')
        ->assertSessionHas('error');
});

test('nothing else is turned into a redirect', function () {
    Route::middleware('web')->post('/testing/missing', fn () => abort(404));

    $this->from('/dashboard')
        ->post('/testing/missing')
        ->assertNotFound();
});

test('sessions outlast a game evening, and survive the browser closing', function () {
    // Two hours of idling used to be enough to lose the sheet mid-game.
    expect(config('session.lifetime'))->toBeGreaterThanOrEqual(480)
        ->and(config('session.expire_on_close'))->toBeFalse();
});
