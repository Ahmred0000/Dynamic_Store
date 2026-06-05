<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)->get();
        $transactions = InventoryTransaction::with('product')
                        ->where('user_id', Auth::id())
                        ->latest()->paginate(10);

        return view('worker.inventory', compact('products', 'transactions'));
    }

    public function deduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'reason'     => 'required|string|max:255',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->quantity < $request->quantity) {
            return back()->with('error', 'الكمية المطلوبة أكبر من المتوفر في المخزن');
        }

        $product->decrement('quantity', $request->quantity);

        InventoryTransaction::create([
            'product_id' => $product->id,
            'user_id'    => Auth::id(),
            'type'       => 'out',
            'quantity'   => $request->quantity,
            'reason'     => $request->reason,
        ]);

        return back()->with('success', 'تم الخصم من المخزن بنجاح');
    }
}
