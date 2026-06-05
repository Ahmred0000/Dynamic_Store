<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user', 'items.product')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function update(\Illuminate\Http\Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,delivered',
        ]);

        $order->update(['status' => $request->status]);
        return back()->with('success', 'تم تحديث حالة الطلب بنجاح');
    }
}
