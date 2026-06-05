<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{

    public function index()
    {
        $stats = [
            'total_products'  => Product::count(),
            'low_stock'       => Product::whereColumn('quantity', '<=', 'min_quantity')->count(),
            'out_of_stock'    => Product::where('quantity', 0)->count(),
            'total_orders'    => Order::count(),
            'pending_orders'  => Order::where('status', 'pending')->count(),
            'total_customers' => User::role('customer')->count(),
            'total_workers'   => User::role('worker')->count(),
        ];

        $low_stock_products = Product::whereColumn('quantity', '<=', 'min_quantity')
                                     ->latest()->take(5)->get();

        $recent_orders = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'low_stock_products', 'recent_orders'));
    }
}
