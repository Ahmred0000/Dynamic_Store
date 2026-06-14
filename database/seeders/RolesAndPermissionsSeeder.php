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

        // مصفوفة الصلاحيات
        $permissions = [
            'manage-inventory', 'manage-users', 'view-reports', 'manage-orders',
            'deduct-inventory', 'view-products', 'place-order', 'track-orders'
        ];

        // إنشاء الصلاحيات إذا لم تكن موجودة
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // إنشاء الأدوار وتعيين الصلاحيات
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions(Permission::all()); // syncPermissions أحسن من givePermissionTo في التعديل

        $worker = Role::firstOrCreate(['name' => 'worker']);
        $worker->syncPermissions(['deduct-inventory']);

        $customer = Role::firstOrCreate(['name' => 'customer']);
        $customer->syncPermissions(['view-products', 'place-order', 'track-orders']);
    }
}
