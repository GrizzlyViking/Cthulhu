<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Weapon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WeaponController extends Controller
{
    public function addWeapon(Character $character, Request $request): RedirectResponse
    {
        $request->validate([
            'weapon_id' => 'required|integer',
        ]);

        $weapon = Weapon::find($request->get('weapon_id'));
        $character->weapons()->attach($weapon);

        return to_route('character.show', $character->slug);
    }

    public function reloadWeapon(Request $request)
    {
        $validated = $request->validate([
            'pivot_id' => 'required|integer|exists:equipables,id',
            'ammo'     => 'required|integer|min:0',
        ]);

        DB::table('equipables')->where('id', $validated['pivot_id'])->update(['ammo' => $validated['ammo']]);

        return response('OK', 200);
    }

    public function removeWeapon(Request $request)
    {
        $validated = $request->validate([
            'character_slug' => 'required|string|exists:characters,slug',
            'pivot_id'       => 'required|integer|exists:equipables,id',
        ]);

        DB::table('equipables')->where('id', $validated['pivot_id'])->delete();

        return to_route('character.show', $validated['character_slug']);
    }

    public function fireWeapon(Request $request)
    {
        $validated = $request->validate([
            'pivot_id' => 'required|integer|exists:equipables,id',
        ]);

        DB::table('equipables')->where('id', $validated['pivot_id'])->update(['ammo' => DB::raw('ammo - 1')]);

        return response('OK', 200);
    }
}
