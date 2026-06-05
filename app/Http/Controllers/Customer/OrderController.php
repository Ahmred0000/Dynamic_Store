<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
// use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{


    public function index()
    {
        $products = Product::where('is_active', true)->where('quantity', '>', 0)->get();
        $orders   = Order::with('items.product')
                         ->where('user_id', Auth::id())
                         ->latest()->paginate(10);

        return view('customer.orders', compact('products', 'orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'            => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        $total = 0;
        $orderItems = [];

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $subtotal = $product->price * $item['quantity'];
            $total += $subtotal;
            $orderItems[] = [
                'product_id' => $product->id,
                'quantity'   => $item['quantity'],
                'price'      => $product->price,
            ];
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'status'  => 'pending',
            'notes'   => $request->notes,
            'total'   => $total,
        ]);

        $order->items()->createMany($orderItems);

        return back()->with('success', 'تم إرسال طلبك بنجاح وسيتم مراجعته قريباً');
    }
}
