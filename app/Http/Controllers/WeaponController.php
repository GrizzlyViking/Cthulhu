<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Weapon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
        $request->validate([
            'pivot_id' => 'required|integer',
            'ammo'     => 'required|integer',
        ]);

        DB::table('equipables')->where('id', $request->get('pivot_id'))->update(['ammo' => $request->get('ammo')]);

        return \response('OK', 200);
    }

    public function removeWeapon(Request $request)
    {
        Validator::make($request->all(), [
            'character_slug' => 'required|string',
            'pivot_id'       => 'required|integer',
        ]);

        DB::table('equipables')->where('id', $request->get('pivot_id'))->delete();

        return to_route('character.show', $request->get('character_slug'));
    }

    public function fireWeapon(Request $request)
    {
        Validator::make($request->all(), [
            'pivot_id' => 'required|integer',
        ]);

        DB::table('equipables')->where('id', $request->get('pivot_id'))->update(['ammo' => DB::raw('ammo - 1')]);

        return \response('OK', 200);
    }
}
