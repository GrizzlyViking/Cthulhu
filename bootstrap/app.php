<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\EnsureUserIsNotBlocked::class,
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        // Someone already signed in who asks for a guest page lands where
        // every other authenticated entry point does.
        $middleware->redirectUsersTo('/home');

        $middleware->alias([
            'admin'          => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'keeper'         => \App\Http\Middleware\EnsureUserIsKeeper::class,
            'reference-data' => \App\Http\Middleware\EnsureReferenceDataIsEditable::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // A sheet left open outlives its session, and the page goes on holding
        // the CSRF token it was rendered with — so the next save answers 419.
        // Inertia would show the player Symfony's error page in a modal; send
        // them back with a sentence instead. Signed-in players are carried
        // over by the remember-me cookie and land on the sheet again with a
        // fresh token; anyone else meets the login page, as they would anyway.
        $stale = 'That page had been open long enough to go stale, so the change was not saved. Please try it again.';

        $exceptions->respond(function (Response $response, Throwable $e, Request $request) use ($stale): Response {
            if ($response->getStatusCode() !== 419) {
                return $response;
            }

            if ($request->header('X-Inertia')) {
                // 303, not 302: the save that failed was a PUT or a POST, and a
                // 302 is re-issued with the same method — straight back into the
                // same 419, until the browser gives up with
                // ERR_TOO_MANY_REDIRECTS. This is the conversion Inertia does for
                // its own redirects, except that its middleware never runs for a
                // request the CSRF check rejected.
                return back(303)->with('error', $stale);
            }

            // Most of the sheet does not save through Inertia at all: a
            // characteristic, a skill value, a round fired are plain axios calls.
            // Redirecting those is worse than useless — the browser follows the
            // 303 by itself, the page that comes back is dropped by the .then(),
            // and the flash is eaten on the way past, so the next real visit has
            // nothing left to show either. Hand XHR the 419 it can act on, and
            // leave the message in the session for the page the interceptor then
            // asks for. That request is a GET, so it also puts a working CSRF
            // token back in the tab.
            if ($request->ajax() || $request->expectsJson()) {
                if ($request->hasSession()) {
                    $request->session()->flash('error', $stale);
                }

                return response()->json(['message' => $stale], 419);
            }

            return back(303)->with('error', $stale);
        });
    })->create();
