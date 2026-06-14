<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\InventoryTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\LowStockNotification;
use Illuminate\Support\Facades\Notification;

class InventoryController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)->get();
        $transactions = InventoryTransaction::with('product')
                        ->where('user_id', Auth::id())
                        ->latest()->paginate(10);

        return view('worker.dashboard', compact('products', 'transactions'));
    }

    public function deduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->quantity < $request->quantity) {
            return back()->with('error', 'الكمية المطلوبة أكبر من المتوفر في المخزن');
        }

        // الخصم من المخزن
        $product->decrement('quantity', $request->quantity);

        // تسجيل العملية في جدول الحركات (مع تثبيت السبب لراحة العامل)
        InventoryTransaction::create([
            'product_id' => $product->id,
            'user_id'    => Auth::id(),
            'type'       => 'out',
            'quantity'   => $request->quantity,
            'reason'     => 'سحب لعملية التصنيع بالمصنع',
        ]);

        // [تلقائي] إرسال إشعار للأدمن إذا وصل المنتج للحد الأدنى
        if ($product->quantity <= $product->min_quantity) {
            $admins = User::whereHas('roles', function($q){
                $q->where('name', 'admin');
            })->get();

            Notification::send($admins, new LowStockNotification($product));
        }

        return back()->with('success', 'تم الخصم من المخزن بنجاح وترحيل العملية للتقارير');
    }
}
