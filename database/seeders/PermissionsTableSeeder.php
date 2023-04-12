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
        
        $createPermission = Permission::updateOrCreate(['name' => 'create'],['name' => 'create']);
        $readPermission = Permission::updateOrCreate(['name' => 'read'],['name' => 'read']);
        $editPermission = Permission::updateOrCreate(['name' => 'edit'],['name' => 'edit']);
        $deletePermission = Permission::updateOrCreate(['name' => 'delete'],['name' => 'delete']);
        
        $superAdminRole->givePermissionTo([$createPermission, $editPermission, $readPermission, $deletePermission]);
        $adminRole->givePermissionTo([$createPermission, $editPermission, $readPermission]);
        $repRole->givePermissionTo([$readPermission]);
        $vendorRole->givePermissionTo([$readPermission]);
        $customerRole->givePermissionTo($readPermission);
    }
}
