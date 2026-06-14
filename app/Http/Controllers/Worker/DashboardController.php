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
        // 1. الكود القديم بتاعك (بيجيب إحصائيات العامل)
        $transactions = InventoryTransaction::with('product')
            ->where('user_id', Auth::id())
            ->latest()->take(10)->get();

        $products_count = Product::where('is_active', true)->count();

        // 2. الإضافة الجديدة (علشان كروت المنتجات تظهر وم تضربش الـ View)
        $products = Product::with('category')->where('is_active', true)->get();

        // بنبعت كله مع بعضه للـ View
        return view('worker.dashboard', compact('transactions', 'products_count', 'products'));
    }
}
