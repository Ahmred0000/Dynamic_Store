<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf; // الاستدعاء الصريح المباشر لمنع أي خطأ

class ReportController extends Controller
{
    /**
     * عرض صفحة تقرير النواقص في لوحة التحكم
     */
    public function lowStock()
    {
        $products = Product::with('category')
            ->whereRaw('quantity <= min_quantity')
            ->latest()
            ->paginate(15);

        return view('admin.reports.low_stock', compact('products'));
    }

    /**
     * تصدير التقرير كـ PDF رسمي غير قابل للتعديل
     */
    public function exportLowStock()
{
    // جلب المنتجات اللي تحت حد الأمان
    $products = \App\Models\Product::with('category')
        ->whereRaw('quantity <= min_quantity')
        ->get();

    // هنفتح صفحة وورد / طباعة عادية جداً
    return view('admin.reports.print_pdf', compact('products'));
}
}
