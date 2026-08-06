<?php

namespace App\Http\Middleware;

use App\Enums\RoleEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The admin section is for admins only. Roles are cumulative, so this asks
 * whether the user wears the admin hat, not whether admin is all they are.
 */
class EnsureUserIsAdmin
{
    /**
     * @param Closure(Request): Response $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($request->user()?->hasRole(RoleEnum::ADMIN->value) === true, 403);

        return $next($request);
    }
}
