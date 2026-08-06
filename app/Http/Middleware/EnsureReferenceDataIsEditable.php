<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Guards the writes to the shared reference data — skills and the armoury —
 * behind `cthulhu.admin.edit_reference_data`. With the toggle off the routes
 * refuse outright rather than merely hiding their buttons.
 */
class EnsureReferenceDataIsEditable
{
    /**
     * @param Closure(Request): Response $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(
            config('cthulhu.admin.edit_reference_data') === true,
            403,
            'Editing skills and weapons is switched off on this server.',
        );

        return $next($request);
    }
}
