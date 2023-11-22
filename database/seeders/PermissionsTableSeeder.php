<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdminRole = Role::updateOrCreate(['name' => 'super admin'],['name' => 'super admin']);
        $adminRole = Role::updateOrCreate(['name' => 'admin'],['name' => 'admin']);
        $repRole = Role::updateOrCreate(['name' => 'rep'],['name' => 'rep']);
        $vendorRole = Role::updateOrCreate(['name' => 'vendor'],['name' => 'vendor']);
        $customerRole = Role::updateOrCreate(['name' => 'customer'],['name' => 'customer']);
        
        $createPermission = Permission::updateOrCreate(['name' => 'create.users'],['name' => 'create.users']);
        $readPermission = Permission::updateOrCreate(['name' => 'read.users'],['name' => 'read.users']);
        $editPermission = Permission::updateOrCreate(['name' => 'edit.users'],['name' => 'edit.users']);
        $deletePermission = Permission::updateOrCreate(['name' => 'delete.users'],['name' => 'delete.users']);
        
        $superAdminRole->givePermissionTo([$createPermission, $editPermission, $readPermission, $deletePermission]);
        $adminRole->givePermissionTo([$createPermission, $editPermission, $readPermission]);
    }
}
