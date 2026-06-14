<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * عرض المنتجات مع إمكانية الفلترة حسب الفئة
     */
    public function index(Request $request)
    {
        // بناء استعلام يبدأ بـ Eager Loading للفئة عشان السرعة
        $query = Product::with('category');

        // فلترة المنتجات إذا اختار المستخدم فئة معينة
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // جلب النتائج مع الترقيم
        $products = $query->latest()->paginate(10);

        // جلب كل الفئات لعرضها في قائمة التبويبات والمودال
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * تخزين منتج جديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'sku'          => 'required|string|unique:products,sku',
            'category_id'  => 'required|exists:categories,id',
            'price'        => 'required|numeric|min:0',
            'quantity'     => 'required|integer|min:0',
            'min_quantity' => 'required|integer|min:0',
            'unit'         => 'required|string|in:قطعة,عود,كرتونة,لوح',
        ]);

        Product::create($validated);

        return redirect()->route('admin.products.index')
                         ->with('success', 'تم إضافة المنتج بنجاح');
    }

    /**
     * تحديث بيانات منتج موجود
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'category_id'  => 'required|exists:categories,id',
            'price'        => 'required|numeric|min:0',
            'quantity'     => 'required|integer|min:0',
            'min_quantity' => 'required|integer|min:0',
            'unit'         => 'required|string|in:قطعة,عود,كرتونة,لوح',
        ]);

        $product->update($validated);

        return redirect()->route('admin.products.index')
                         ->with('success', 'تم تعديل المنتج بنجاح');
    }

    /**
     * حذف منتج
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
                         ->with('success', 'تم حذف المنتج بنجاح');
    }
}
