<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CharacterKind;
use App\Enums\Era;
use App\Models\Game;
use App\Models\Group;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class GroupController extends AdminController
{
    /**
     * The group's own settings, its roster and its outstanding invitations.
     */
    public function edit(Request $request): Response
    {
        $group = $this->requireGroup($request);

        return Inertia::render('Admin/Group', [
            'group' => [
                'id'   => $group->id,
                'name' => $group->name,
                'era'  => $group->era->value,
            ],
            'eras'  => $this->eraOptions(),
            'games' => $group->games()
                // Investigators: a Keeper's cast is in these games too, and is
                // not the admin's business.
                ->withCount(['characters' => fn (Builder $query) => $query->where('kind', CharacterKind::Investigator)])
                ->orderByDesc('id')
                ->get()
                ->map(fn (Game $game): array => [
                    'id'              => $game->id,
                    'name'            => $game->name,
                    'era'             => $game->era->value,
                    'active'          => $game->id === $group->active_game_id,
                    'charactersCount' => $game->characters_count,
                ]),
            'members' => User::query()
                ->where('group_id', $group->id)
                ->with('roles')
                ->withCount('characters')
                ->orderBy('name')
                ->get()
                ->map(fn (User $user): array => [
                    'id'              => $user->id,
                    'name'            => $user->name,
                    'email'           => $user->email,
                    'roles'           => $user->roleNames(),
                    'blocked'         => $user->isBlocked(),
                    'charactersCount' => $user->characters_count,
                ]),
            'invitations' => $group->pendingInvitations()
                ->orderBy('created_at')
                ->get()
                ->map(fn (Invitation $invitation): array => [
                    'id'        => $invitation->id,
                    'email'     => $invitation->email,
                    'expiresAt' => $invitation->expires_at->toDateString(),
                ]),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $group = $this->requireGroup($request);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Group::class, 'name')->ignore($group->id),
            ],
            'era' => ['required', Rule::enum(Era::class)],
        ]);

        $group->update($validated);

        return back()->with('success', 'Group updated.');
    }

    /**
     * @return array<int, array{value: string, label: string}>
     */
    private function eraOptions(): array
    {
        return array_map(
            fn (Era $era): array => ['value' => $era->value, 'label' => $era->label()],
            Era::cases(),
        );
    }
}
