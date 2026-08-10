<?php

namespace App\Http\Middleware;

use App\Enums\CharacterStatus;
use App\Misc\WeaponTable;
use App\Models\Character;
use App\Models\User;
use App\Models\Weapon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user,
                // Roles are cumulative, so the frontend gets the whole list
                // rather than a single value it would have to compare against.
                'roles'      => $user === null ? [] : $user->roleNames(),
                'characters' => [
                    'all'    => $this->visibleCharacters($user),
                    'others' => $this->otherCharacters($user),
                    'own'    => Character::query()->playersOwn()->get(),
                    ],
                // The armoury is rulebook data, not group data — it stays global.
                'equipment' => $this->armoury(),
                'users'     => $user === null ? new EloquentCollection() : User::query()->inGroupOf($user)->with('roles')->get(),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error'   => fn () => $request->session()->get('error'),
            ],
        ];
    }

    /**
     * Every character the user may see: their own (drafts included), plus the
     * completed characters of their group. Ungrouped users see only their own.
     */
    private function visibleCharacters(?User $user): EloquentCollection
    {
        if ($user === null) {
            return new EloquentCollection();
        }

        return Character::query()
            // Drafts are only ever visible to their owner.
            ->where(function (Builder $query) use ($user) {
                $query->where('status', CharacterStatus::Complete)
                    ->orWhere('user_id', $user->id);
            })
            ->where(function (Builder $query) use ($user) {
                $query->where('user_id', $user->id);

                if ($user->group_id !== null) {
                    $query->orWhere('group_id', $user->group_id);
                }
            })
            ->get();
    }

    /**
     * Completed characters of the user's groupmates. Empty while ungrouped.
     */
    private function otherCharacters(?User $user): EloquentCollection
    {
        if ($user?->group_id === null) {
            return new EloquentCollection();
        }

        return Character::query()
            ->others()
            ->where('status', CharacterStatus::Complete)
            ->where('group_id', $user->group_id)
            ->get();
    }

    /**
     * Every weapon that can be equipped, ordered the way the Investigator
     * Handbook prints its table so the picker groups read in the same order.
     */
    private function armoury(): Collection
    {
        $sections = WeaponTable::categories();

        return Weapon::all()
            ->sortBy(function (Weapon $weapon) use ($sections): array {
                $section = array_search($weapon->category, $sections, true);

                return [$section === false ? count($sections) : $section, $weapon->name];
            })
            ->values();
    }
}
