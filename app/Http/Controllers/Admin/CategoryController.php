<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // عرض كل الفئات للأدمن
    public function index()
    {
        $categories = Category::latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    // حفظ فئة جديدة في قاعدة البيانات
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'is_for_sale' => 'required|boolean', // 1 للبيع، 0 للمصنع
        ]);

        Category::create([
            'name' => $request->name,
            'is_for_sale' => $request->is_for_sale,
        ]);

        return redirect()->back()->with('success', 'تم إضافة الفئة بنجاح!');
    }

    // تحديث بيانات فئة موجودة
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'is_for_sale' => 'required|boolean',
        ]);

        $category->update([
            'name' => $request->name,
            'is_for_sale' => $request->is_for_sale,
        ]);

        return redirect()->back()->with('success', 'تم تحديث الفئة بنجاح!');
    }

    // حذف فئة
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'تم حذف الفئة بنجاح!');
    }
}
