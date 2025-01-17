<?php

namespace Database\Seeders;

use App\Enums\RolesEnum;
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
        Permission::create(['name' => 'delete users']);

        $role = Role::create(['name' => RolesEnum::ADMIN]);
        $role->givePermissionTo('delete users');

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
