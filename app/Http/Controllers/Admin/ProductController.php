<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'sku'          => 'required|string|unique:products',
            'category'     => 'required|string',
            'price'        => 'required|numeric|min:0',
            'quantity'     => 'required|integer|min:0',
            'min_quantity' => 'required|integer|min:0',
            'unit'         => 'required|string',
        ]);

        Product::create($request->all());
        return redirect()->route('admin.products.index')
                         ->with('success', 'تم إضافة المنتج بنجاح');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'price'        => 'required|numeric|min:0',
            'quantity'     => 'required|integer|min:0',
            'min_quantity' => 'required|integer|min:0',
        ]);

        $product->update($request->all());
        return redirect()->route('admin.products.index')
                         ->with('success', 'تم تعديل المنتج بنجاح');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')
                         ->with('success', 'تم حذف المنتج بنجاح');
    }
}
