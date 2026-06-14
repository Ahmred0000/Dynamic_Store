@extends('layouts.admin')

@section('title', 'إدارة المنتجات')

@section('content')
<div class="p-6 bg-slate-50 min-h-screen text-slate-800">
    {{-- الهيدر --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">إدارة المخزون</h1>
        </div>
        <button onclick="document.getElementById('modal-add').classList.remove('hidden')"
            class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-indigo-700 shadow-lg">
            + إضافة منتج جديد
        </button>
    </div>

    {{-- الفئات --}}
    <div class="flex gap-2 overflow-x-auto pb-4 mb-6">
        <a href="{{ route('admin.products.index') }}" class="px-4 py-2 rounded-lg font-bold border {{ !request('category') ? 'bg-slate-800 text-white' : 'bg-white text-slate-600' }}">الكل</a>
        @foreach($categories as $cat)
        <a href="{{ route('admin.products.index', ['category' => $cat->id]) }}" class="px-4 py-2 rounded-lg font-bold whitespace-nowrap border {{ request('category') == $cat->id ? 'bg-indigo-600 text-white' : 'bg-white text-slate-600' }}">
            {{ $cat->name }}
        </a>
        @endforeach
    </div>

    {{-- الجدول --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50">
                <tr class="text-right text-slate-500 uppercase text-xs">
                    <th class="px-6 py-4">المنتج</th>
                    <th class="px-6 py-4">السعر</th>
                    <th class="px-6 py-4">الكمية</th>
                    <th class="px-6 py-4">الحالة</th>
                    <th class="px-6 py-4">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($products as $product)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 font-bold text-slate-700">{{ $product->name }}</td>
                    <td class="px-6 py-4 font-mono text-slate-600">{{ number_format($product->price, 2) }}</td>
                    <td class="px-6 py-4 font-mono text-slate-600">{{ $product->quantity }} {{ $product->unit }}</td>
                    <td class="px-6 py-4">
                        @if($product->quantity <= 0) <span class="text-red-600 bg-red-50 px-2 py-1 rounded-full text-xs font-bold">نفد</span>
                        @elseif($product->quantity <= $product->min_quantity) <span class="text-amber-600 bg-amber-50 px-2 py-1 rounded-full text-xs font-bold">منخفض</span>
                        @else <span class="text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full text-xs font-bold">متوفر</span> @endif
                    </td>
                    <td class="px-6 py-4 flex gap-3">
                        <button onclick="editProduct({{ $product->id }}, '{{ $product->name }}', {{ $product->category_id }}, {{ $product->price }}, {{ $product->quantity }}, {{ $product->min_quantity }}, '{{ $product->unit }}', '{{ $product->sku }}')"
                                class="text-indigo-600 font-bold hover:underline">تعديل</button>

                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 font-bold hover:underline">حذف</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-10 text-slate-400">لا توجد منتجات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@include('admin.products.modals')

<script>
function editProduct(id, name, catId, price, qty, min, unit, sku) {
    document.getElementById('edit-name').value = name;
    document.getElementById('edit-category').value = catId;
    document.getElementById('edit-price').value = price;
    document.getElementById('edit-quantity').value = qty;
    document.getElementById('edit-min').value = min;
    document.getElementById('edit-unit').value = unit;
    document.getElementById('edit-sku').value = sku;
    document.getElementById('edit-form').action = '/admin/products/' + id;
    document.getElementById('modal-edit').classList.remove('hidden');
}
</script>
@endsection
