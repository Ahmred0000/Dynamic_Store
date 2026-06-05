<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)
                           ->where('quantity', '>', 0)
                           ->latest()->get();

        $my_orders = Order::where('user_id', Auth::id())
                          ->latest()->take(5)->get();

        return view('customer.dashboard', compact('products', 'my_orders'));
    }
}
