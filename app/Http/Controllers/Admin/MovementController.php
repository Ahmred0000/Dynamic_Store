<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\InventoryTransaction; // التعديل: إستدعاء الموديل الصحيح هنا
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovementController extends Controller
{
    /**
     * لوحة تحكم الأدمن: مراقبة سحب العمال واعتماد طلبات العملاء
     */
    public function index(Request $request)
    {
        $workers = User::whereHas('roles', function($q){
            $q->where('name', 'worker');
        })->get();

        $selectedWorkerId = $request->input('worker_id');

        // التعديل: القراءة مباشرة من جدول inventory_transactions وحركات السحب فقط
        $workerLogs = InventoryTransaction::with('product', 'user')
            ->where('type', 'out')
            ->when($selectedWorkerId, function ($query) use ($selectedWorkerId) {
                return $query->where('user_id', $selectedWorkerId);
            })
            ->latest()
            ->paginate(15, ['*'], 'workers_page');

        $orders = Order::with('items.product')->latest()->paginate(10, ['*'], 'orders_page');

        return view('admin.movements.index', compact('workers', 'workerLogs', 'orders', 'selectedWorkerId'));
    }

    /**
     * طباعة تقرير سحبيات العامل PDF
     */
    public function printWorkerReport($workerId)
    {
        $worker = User::findOrFail($workerId);
        // التعديل: القراءة من الجدول الصحيح للطباعة
        $workerLogs = InventoryTransaction::where('user_id', $workerId)->where('type', 'out')->with('product')->latest()->get();
        return view('admin.movements.worker-report-pdf', compact('worker', 'workerLogs'));
    }

    /**
     * تصفير سجلات عامل معين
     */
    public function clearWorkerLogs($workerId)
    {
        // التعديل: حذف السجلات من الجدول الصحيح
        InventoryTransaction::where('user_id', $workerId)->where('type', 'out')->delete();
        return redirect()->back()->with('success', 'تم تصفير سجلات العامل بنجاح.');
    }

    /**
     * طباعة فاتورة العميل PDF
     */
    public function printInvoice($orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);
        return view('admin.movements.invoice-pdf', compact('order'));
    }

    /**
     * اعتماد الطلبية
     */
    public function approveOrder($id)
    {
        DB::beginTransaction();
        try {
            $order = Order::with('items.product')->findOrFail($id);

            if ($order->status !== 'pending') {
                return redirect()->back()->with('error', 'هذا الطلب تم معالجته مسبقاً.');
            }

            foreach ($order->items as $item) {
                if ($item->product->quantity < $item->quantity) {
                    return redirect()->back()->with('error', "لا يمكن الموافقة، المخزن لا يكفي للمنتج: {$item->product->name}");
                }
            }

            foreach ($order->items as $item) {
                $item->product->decrement('quantity', $item->quantity);
            }

            $order->update(['status' => 'approved']);
            DB::commit();

            return redirect()->back()->with('success', 'تمت الموافقة على الطلبية وخصم الكميات.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ أثناء اعتماد الطلب.');
        }
    }

    /**
     * رفض الطلبية
     */
    public function rejectOrder($id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'تم رفض طلبية العميل بنجاح.');
    }
}
