<?php

namespace Database\Seeders;

use App\Enums\PermissionsEnum;
use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        foreach (PermissionsEnum::values() as $case) {
            Permission::updateOrCreate(['name' => $case]);
        }

        // create roles and assign created permissions

        // Player
        $player = Role::firstOrCreate(['name' => RoleEnum::PLAYER->value]);
        $player->givePermissionTo(PermissionsEnum::EDIT_OWN_CHARACTER);
        $player->givePermissionTo(PermissionsEnum::VIEW_OWN_CHARACTER);
        $player->givePermissionTo(PermissionsEnum::DELETE_OWN_CHARACTER);
        $player->givePermissionTo(PermissionsEnum::VIEW_ANY_CHARACTER);

        // keeper
        $keeper = Role::firstOrCreate(['name' => RoleEnum::KEEPER->value]);
        $keeper->givePermissionTo(PermissionsEnum::VIEW_OWN_CHARACTER);
        $keeper->givePermissionTo(PermissionsEnum::EDIT_OWN_CHARACTER);
        $keeper->givePermissionTo(PermissionsEnum::VIEW_ANY_CHARACTER);
        $keeper->givePermissionTo(PermissionsEnum::EDIT_ANY_CHARACTER);
        $keeper->givePermissionTo(PermissionsEnum::DELETE_OWN_CHARACTER);

        // admin
        $admin = Role::firstOrCreate(['name' => RoleEnum::ADMIN->value]);
        $admin->givePermissionTo(Permission::all());
    }
}
