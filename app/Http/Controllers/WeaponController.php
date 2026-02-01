<?php

namespace App\Http\Controllers;

use App\Models\Character;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class WeaponController extends Controller
{
    public function addWeapon(Character $character, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'weapon_id' => ['required', 'integer', 'exists:weapons,id'],
        ]);

        $character->weapons()->attach($validated['weapon_id']);

        return to_route('character.show', $character->slug);
    }

    public function reloadWeapon(Request $request): Response
    {
        $validated = $request->validate([
            'pivot_id' => ['required', 'integer', 'exists:equipables,id'],
            'ammo'     => ['required', 'integer', 'min:0'],
        ]);

        DB::table('equipables')->where('id', $validated['pivot_id'])->update(['ammo' => $validated['ammo']]);

        return response('OK', 200);
    }

    public function removeWeapon(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'character_slug' => ['required', 'string', 'exists:characters,slug'],
            'pivot_id'       => ['required', 'integer', 'exists:equipables,id'],
        ]);

        DB::table('equipables')->where('id', $validated['pivot_id'])->delete();

        return to_route('character.show', $validated['character_slug']);
    }

    public function fireWeapon(Request $request): Response
    {
        $validated = $request->validate([
            'pivot_id' => ['required', 'integer', 'exists:equipables,id'],
        ]);

        DB::table('equipables')->where('id', $validated['pivot_id'])->update(['ammo' => DB::raw('ammo - 1')]);

        return response('OK', 200);
    }
}
