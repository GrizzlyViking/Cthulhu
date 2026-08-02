<?php

namespace App\Http\Middleware;

use App\Enums\CharacterStatus;
use App\Misc\WeaponTable;
use App\Models\Character;
use App\Models\User;
use App\Models\Weapon;
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
        return [
            ...parent::share($request),
            'auth' => [
                'user'       => $request->user(),
                'characters' => [
                    // Drafts are only ever visible to their owner.
                    'all' => Character::query()
                        ->where(function ($query) use ($request) {
                            $query->where('status', CharacterStatus::Complete)
                                ->orWhere('user_id', $request->user()?->id);
                        })
                        ->get(),
                    'others' => Character::query()->others()->where('status', CharacterStatus::Complete)->get(),
                    'own'    => Character::query()->playersOwn()->get(),
                    ],
                'equipment'          => $this->armoury(),
                'users'              => User::all(),
                'listOfMessageUsers' => [],
                'listOfRollUsers'    => [],
            ],
        ];
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
