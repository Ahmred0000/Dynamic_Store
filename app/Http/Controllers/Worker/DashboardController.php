<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $transactions = InventoryTransaction::with('product')
            ->where('user_id', Auth::id())
            ->latest()->take(10)->get();

        $products_count = Product::where('is_active', true)->count();

        return view('worker.dashboard', compact('transactions', 'products_count'));
    }
}
