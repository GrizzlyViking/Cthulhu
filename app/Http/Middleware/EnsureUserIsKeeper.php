<?php

namespace App\Http\Middleware;

use App\Enums\RoleEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The Keeper's screen is for Keepers only. Roles are cumulative, so this asks
 * whether the user wears the Keeper's hat — an admin who does not run the game
 * has no business seeing the party's sanity, and is refused like anyone else.
 */
class EnsureUserIsKeeper
{
    /**
     * @param Closure(Request): Response $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($request->user()?->hasRole(RoleEnum::KEEPER->value) === true, 403);

        return $next($request);
    }
}
