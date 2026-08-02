<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Weapon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WeaponController extends Controller
{
    use AuthorizesRequests;

    public function addWeapon(Character $character, Request $request): RedirectResponse
    {
        $this->authorize('update', $character);

        $validated = $request->validate([
            'weapon_id' => ['required', 'integer', 'exists:weapons,id'],
        ]);

        $weapon = Weapon::findOrFail($validated['weapon_id']);

        // A newly picked up firearm arrives with a full magazine and no spares.
        $character->weapons()->attach($weapon->id, [
            'ammo'         => $weapon->magazine_capacity ?? 0,
            'ammo_reserve' => 0,
        ]);

        return to_route('character.show', $character->slug);
    }

    public function removeWeapon(Character $character, int $equipable): RedirectResponse
    {
        $this->authorize('update', $character);

        $this->equippedWeapon($character, $equipable);

        DB::table('equipables')->where('id', $equipable)->delete();

        return to_route('character.show', $character->slug);
    }

    /**
     * Spend a single round from the magazine.
     */
    public function fireWeapon(Character $character, int $equipable): JsonResponse
    {
        $this->authorize('update', $character);

        $equipped = $this->equippedWeapon($character, $equipable);

        if ($equipped->ammo < 1) {
            return response()->json(['message' => 'The magazine is empty.'], 422);
        }

        return $this->store($equipable, $equipped->ammo - 1, $equipped->ammo_reserve);
    }

    /**
     * Refill the magazine from the rounds the investigator is carrying.
     */
    public function reloadWeapon(Character $character, int $equipable): JsonResponse
    {
        $this->authorize('update', $character);

        $equipped = $this->equippedWeapon($character, $equipable);
        $capacity = Weapon::findOrFail($equipped->equipable_id)->magazine_capacity;

        if ($capacity === null) {
            return response()->json(['message' => 'This weapon does not take ammunition.'], 422);
        }

        $loaded = min($capacity - $equipped->ammo, $equipped->ammo_reserve);

        if ($loaded < 1) {
            return response()->json(['message' => 'No rounds left to load.'], 422);
        }

        return $this->store($equipable, $equipped->ammo + $loaded, $equipped->ammo_reserve - $loaded);
    }

    /**
     * Set the magazine and the rounds carried by hand, for when play has run
     * ahead of the buttons.
     */
    public function updateAmmo(Character $character, int $equipable, Request $request): JsonResponse
    {
        $this->authorize('update', $character);

        $equipped = $this->equippedWeapon($character, $equipable);
        $capacity = Weapon::findOrFail($equipped->equipable_id)->magazine_capacity;

        $validated = $request->validate([
            'ammo'         => ['nullable', 'integer', 'min:0', 'max:9999'],
            'ammo_reserve' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        $ammo = $validated['ammo'] ?? $equipped->ammo;

        return $this->store(
            $equipable,
            $capacity === null ? $ammo : min($ammo, $capacity),
            $validated['ammo_reserve'] ?? $equipped->ammo_reserve,
        );
    }

    private function store(int $equipable, int $ammo, int $reserve): JsonResponse
    {
        DB::table('equipables')->where('id', $equipable)->update([
            'ammo'         => $ammo,
            'ammo_reserve' => $reserve,
            'updated_at'   => now(),
        ]);

        return response()->json(['ammo' => $ammo, 'ammo_reserve' => $reserve]);
    }

    /**
     * Resolve a pivot row, refusing anything that belongs to another sheet.
     */
    private function equippedWeapon(Character $character, int $equipable): object
    {
        $equipped = DB::table('equipables')
            ->where('id', $equipable)
            ->where('character_id', $character->id)
            ->where('equipable_type', Weapon::class)
            ->first();

        if ($equipped === null) {
            throw new NotFoundHttpException('That weapon is not on this sheet.');
        }

        return $equipped;
    }
}
