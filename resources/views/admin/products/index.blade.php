@extends('layouts.admin')

@section('title', 'المنتجات')
@section('header', 'إدارة المنتجات')

@section('content')

{{-- زرار إضافة منتج --}}
<div class="flex justify-between items-center mb-6">
    <h3 class="text-gray-600">إجمالي المنتجات: {{ $products->total() }}</h3>
    <button onclick="document.getElementById('modal-add').classList.remove('hidden')"
        class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">
        + إضافة منتج
    </button>
</div>

{{-- جدول المنتجات --}}
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr class="text-right text-gray-500 border-b">
                <th class="px-4 py-3">اسم المنتج</th>
                <th class="px-4 py-3">الفئة</th>
                <th class="px-4 py-3">السعر</th>
                <th class="px-4 py-3">الكمية</th>
                <th class="px-4 py-3">الحد الأدنى</th>
                <th class="px-4 py-3">الوحدة</th>
                <th class="px-4 py-3">الحالة</th>
                <th class="px-4 py-3">إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-3 font-medium">{{ $product->name }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $product->category }}</td>
                <td class="px-4 py-3">{{ number_format($product->price, 2) }} ج.م</td>
                <td class="px-4 py-3">{{ $product->quantity }}</td>
                <td class="px-4 py-3">{{ $product->min_quantity }}</td>
                <td class="px-4 py-3">{{ $product->unit }}</td>
                <td class="px-4 py-3">
                    @if($product->quantity == 0)
                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs">نفد</span>
                    @elseif($product->quantity <= $product->min_quantity)
                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs">منخفض</span>
                    @else
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">متوفر</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <div class="flex gap-2">
                        <button onclick="editProduct({{ $product->id }}, '{{ $product->name }}', '{{ $product->category }}', {{ $product->price }}, {{ $product->quantity }}, {{ $product->min_quantity }}, '{{ $product->unit }}')"
                            class="text-blue-600 hover:underline text-xs">تعديل</button>
                        <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}"
                            onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-xs">حذف</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-8 text-gray-400">لا توجد منتجات بعد</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $products->links() }}</div>
</div>

{{-- Modal إضافة منتج --}}
<div id="modal-add" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl p-8 w-full max-w-lg">
        <h3 class="text-lg font-semibold mb-6">إضافة منتج جديد</h3>
        <form method="POST" action="{{ route('admin.products.store') }}">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm text-gray-600 mb-1">اسم المنتج</label>
                    <input type="text" name="name" required
                        class="w-full border rounded-lg px-3 py-2 text-right">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">الكود (SKU)</label>
                    <input type="text" name="sku" required
                        class="w-full border rounded-lg px-3 py-2 text-right">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">الفئة</label>
                    <input type="text" name="category" required
                        class="w-full border rounded-lg px-3 py-2 text-right">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">السعر</label>
                    <input type="number" name="price" step="0.01" required
                        class="w-full border rounded-lg px-3 py-2 text-right">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">الكمية</label>
                    <input type="number" name="quantity" required
                        class="w-full border rounded-lg px-3 py-2 text-right">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">الحد الأدنى</label>
                    <input type="number" name="min_quantity" required
                        class="w-full border rounded-lg px-3 py-2 text-right">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">الوحدة</label>
                    <input type="text" name="unit" value="قطعة" required
                        class="w-full border rounded-lg px-3 py-2 text-right">
                </div>
            </div>
            <div class="flex gap-3 mt-6 justify-end">
                <button type="button"
                    onclick="document.getElementById('modal-add').classList.add('hidden')"
                    class="px-5 py-2 border rounded-lg text-gray-600 hover:bg-gray-50">إلغاء</button>
                <button type="submit"
                    class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">حفظ</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal تعديل منتج --}}
<div id="modal-edit" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl p-8 w-full max-w-lg">
        <h3 class="text-lg font-semibold mb-6">تعديل المنتج</h3>
        <form method="POST" id="edit-form" action="">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm text-gray-600 mb-1">اسم المنتج</label>
                    <input type="text" name="name" id="edit-name" required
                        class="w-full border rounded-lg px-3 py-2 text-right">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">الفئة</label>
                    <input type="text" name="category" id="edit-category" required
                        class="w-full border rounded-lg px-3 py-2 text-right">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">السعر</label>
                    <input type="number" name="price" id="edit-price" step="0.01" required
                        class="w-full border rounded-lg px-3 py-2 text-right">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">الكمية</label>
                    <input type="number" name="quantity" id="edit-quantity" required
                        class="w-full border rounded-lg px-3 py-2 text-right">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">الحد الأدنى</label>
                    <input type="number" name="min_quantity" id="edit-min" required
                        class="w-full border rounded-lg px-3 py-2 text-right">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">الوحدة</label>
                    <input type="text" name="unit" id="edit-unit" required
                        class="w-full border rounded-lg px-3 py-2 text-right">
                </div>
            </div>
            <div class="flex gap-3 mt-6 justify-end">
                <button type="button"
                    onclick="document.getElementById('modal-edit').classList.add('hidden')"
                    class="px-5 py-2 border rounded-lg text-gray-600 hover:bg-gray-50">إلغاء</button>
                <button type="submit"
                    class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">حفظ التعديلات</button>
            </div>
        </form>
    </div>
</div>

<script>
function editProduct(id, name, category, price, quantity, min, unit) {
    document.getElementById('edit-name').value = name;
    document.getElementById('edit-category').value = category;
    document.getElementById('edit-price').value = price;
    document.getElementById('edit-quantity').value = quantity;
    document.getElementById('edit-min').value = min;
    document.getElementById('edit-unit').value = unit;
    document.getElementById('edit-form').action = '/admin/products/' + id;
    document.getElementById('modal-edit').classList.remove('hidden');
}
</script>

@endsection
