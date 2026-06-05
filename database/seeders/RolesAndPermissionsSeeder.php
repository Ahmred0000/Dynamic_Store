<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // مسح الكاش
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // إنشاء الصلاحيات
        Permission::create(['name' => 'manage-inventory']);
        Permission::create(['name' => 'manage-users']);
        Permission::create(['name' => 'view-reports']);
        Permission::create(['name' => 'manage-orders']);
        Permission::create(['name' => 'deduct-inventory']);
        Permission::create(['name' => 'view-products']);
        Permission::create(['name' => 'place-order']);
        Permission::create(['name' => 'track-orders']);

        // إنشاء الأدوار وتعيين الصلاحيات
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $worker = Role::create(['name' => 'worker']);
        $worker->givePermissionTo(['deduct-inventory']);

        $customer = Role::create(['name' => 'customer']);
        $customer->givePermissionTo(['view-products', 'place-order', 'track-orders']);
    }
}
