<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'tugas']);
        Permission::create(['name' => 'semester']);
        Permission::create(['name' => 'mata kuliah']);
        Permission::create(['name' => 'edit profile']);
        Permission::create(['name' => 'ganti password']);
        Permission::create(['name' => 'user']);

        $role = Role::create(['name' => 'admin']);
        // $role->givePermissionTo(Permission::all());

        $role = Role::create(['name' => 'mahasiswa']);
        // $role->givePermissionTo(['tugas', 'semester', 'mata kuliah', 'edit profile', 'ganti password']);
    }
}
