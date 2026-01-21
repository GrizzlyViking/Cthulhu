<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        Permission::create(['name' => 'manage characters']);
        Permission::create(['name' => 'edit characters']);
        Permission::create(['name' => 'view characters']);

        // Create roles and assign created permissions
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());

        $role = Role::create(['name' => 'keeper']);
        $role->givePermissionTo(['edit characters', 'view characters']);

        $role = Role::create(['name' => 'player']);
        $role->givePermissionTo(['view characters']);
    }
}
