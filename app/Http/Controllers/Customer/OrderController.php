<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
   public function index()
    {
        // جلب الفئات التي (متاحة للبيع) و (بها منتجات نشطة ومتاحة بالمخزن)
        $categories = Category::where('is_for_sale', 1)
            ->whereHas('products', function($query) {
                $query->where('is_active', true)->where('quantity', '>', 0);
            })
            ->with(['products' => function($query) {
                $query->where('is_active', true)->where('quantity', '>', 0);
            }])
            ->get();

        // جلب سجل طلبيات العميل
        $orders = Order::with('items.product')
                       ->where('user_id', Auth::id())
                       ->latest()
                       ->paginate(6);

        return view('customer.orders', compact('categories', 'orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        $totalPrice = 0; // توحيد المسمى ليتوافق مع قاعدة البيانات
        $orderItems = [];

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);

            if ($product->quantity < $item['quantity']) {
                return back()->with('error', "الكمية المطلوبة من ({$product->name}) غير متوفرة. المتاح: {$product->quantity}");
            }

            $subtotal = $product->price * $item['quantity'];
            $totalPrice += $subtotal;
            $orderItems[] = [
                'product_id' => $product->id,
                'quantity'   => $item['quantity'],
                'price'      => $product->price,
            ];
        }

        // توليد رقم طلب عشوائي مميز لمنع إيرور الـ default value
        $orderNumber = 'INV-' . strtoupper(Str::random(4)) . rand(1000, 9999);

        // التعديل الشامل والمطابق للموديل الخاص بك 100%
        $order = Order::create([
            'order_number'  => $orderNumber,
            'user_id'       => Auth::id(),
            'customer_name' => Auth::user()->name, // جلب اسم العميل تلقائياً
            'status'        => 'pending',
            'notes'         => $request->notes,
            'total_price'   => $totalPrice,        // حفظ الإجمالي في الحقل الصحيح total_price
        ]);

        $order->items()->createMany($orderItems);

        return back()->with('success', 'تم إرسال طلبك بنجاح وجاري مراجعته الآن برقم: ' . $orderNumber);
    }

    // دالة طباعة الفاتورة للعميل بشكل مستقل
    public function print(Order $order)
    {
        // التأكد أن العميل يطبع فاتورته هو فقط وليس فاتورة شخص آخر لحماية البيانات
        if ($order->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك باستعراض هذه الفاتورة.');
        }

        $order->load('items.product', 'user');
        return view('customer.print_order', compact('order'));
    }
}
