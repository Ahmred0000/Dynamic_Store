<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\StockLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. تشغيل السييدرز القدام بتوعك الأول بالترتيب عشان ينزلوا الأدوار وحساب الأدمن
        $this->call([
            RolesAndPermissionsSeeder::class,
            AdminSeeder::class,
        ]);

        // 2. التأكد من وجود دور "worker" (العامل) في السيستم
        $workerRole = Role::firstOrCreate(['name' => 'worker', 'guard_name' => 'web']);

        // 3. إنشاء 2 عمال تجريبيين لخط الإنتاج (لو مش موجودين)
        $worker1 = User::firstOrCreate(
            ['email' => 'worker1@adzone.com'],
            ['name' => 'العامل محمد حسن', 'password' => Hash::make('12345678')]
        );
        $worker1->assignRole($workerRole);

        $worker2 = User::firstOrCreate(
            ['email' => 'worker2@adzone.com'],
            ['name' => 'العامل أحمد فتحي', 'password' => Hash::make('12345678')]
        );
        $worker2->assignRole($workerRole);

        // 4. إنشاء منتجات تجريبية بالمخزن عشان نقدر نسحب منها
        $product1 = Product::firstOrCreate(
            ['sku' => 'PROD-001'],
            ['name' => 'شاشات عرض AdZone', 'quantity' => 50, 'min_quantity' => 10, 'price' => 1500, 'unit' => 'قطعة']
        );

        $product2 = Product::firstOrCreate(
            ['sku' => 'PROD-002'],
            ['name' => 'كابلات توصيل ألياف', 'quantity' => 100, 'min_quantity' => 20, 'price' => 200, 'unit' => 'متر']
        );

        // 5. محاكاة سحب العمال (تظهر في جدول السحبيات للأدمن)
        StockLog::create([
            'user_id' => $worker1->id,
            'product_id' => $product1->id,
            'quantity' => 5,
            'notes' => 'سحب مباشر لتركيبات خط الشاشات أ'
        ]);

        StockLog::create([
            'user_id' => $worker2->id,
            'product_id' => $product2->id,
            'quantity' => 30,
            'notes' => 'سحب لشبكة سيرفرات الشركة الداخلية'
        ]);

        // 6. محاكاة طلبية مشتريات لعميل خارجي (تظهر في جدول الفواتير معلقة Pending)
        $order = Order::create([
            'order_number'  => 'ADZ-6605A',
            'customer_name' => 'شركة النصر للتوزيع',
            'user_id'       => User::first()->id, // هنا بنحدد أول مستخدم في الداتا كصاحب الطلب
            'total_price'   => 7500,
            'status'        => 'pending'
        ]);

        $order->items()->create([
            'product_id' => $product1->id,
            'quantity' => 5, // 5 شاشات * 1500 = 7500 ج.م
            'price' => 1500
        ]);
    }
}
