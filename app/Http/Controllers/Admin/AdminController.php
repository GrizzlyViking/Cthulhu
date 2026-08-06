<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Shared ground for the admin section.
 *
 * Admin authority is per-group: an admin manages the group they belong to and
 * nothing else. Work that has to reach across groups — creating groups, moving
 * a player between them, editing the canonical skill and weapon lists — stays
 * with the artisan commands, which run with the server operator's authority
 * rather than any one group's.
 */
abstract class AdminController extends Controller
{
    /**
     * The group the acting admin manages, or null while they are ungrouped.
     */
    protected function currentGroup(Request $request): ?Group
    {
        return $request->user()->group;
    }

    /**
     * The admin's group, refusing the request when they have none — there is
     * nothing for an ungrouped admin to administer.
     */
    protected function requireGroup(Request $request): Group
    {
        $group = $this->currentGroup($request);

        abort_if($group === null, 403, 'You do not belong to a group yet, so there is nothing to administer.');

        return $group;
    }

    /**
     * Whether the shared skill and weapon lists may be edited from the browser
     * on this server. The routes enforce it; the pages use it to decide what to
     * offer. See config/cthulhu.php.
     */
    protected function referenceDataIsEditable(): bool
    {
        return config('cthulhu.admin.edit_reference_data') === true;
    }

    /**
     * Resolve a user within the admin's own group, 404ing on anyone outside it
     * so the admin section cannot be used to probe for other groups' members.
     */
    protected function memberOfCurrentGroup(Request $request, User $user): User
    {
        $group = $this->requireGroup($request);

        abort_unless($user->group_id === $group->id, 404);

        return $user;
    }
}
