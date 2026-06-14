@extends('layouts.admin')

@section('title', 'تقرير النواقص')
@section('header', '📊 تقارير المخزون والنواقص')

@section('content')

{{-- الهيدر العلوي للتقرير --}}
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h3 class="text-gray-600 font-medium">الأصناف التي وصلت لحد الأمان أو نفدت حالياً</h3>
    </div>
    <a href="{{ route('admin.reports.export-low-stock') }}"
        class="bg-emerald-600 text-white px-5 py-2.5 rounded-xl hover:bg-emerald-700 shadow-lg shadow-emerald-600/20 flex items-center gap-2 transition-all text-sm font-semibold">
        📥 تنزيل التقرير 
    </a>
</div>

{{-- جدول عرض النواقص --}}
<div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 border-b border-gray-100">
            <tr class="text-right">
                <th class="px-6 py-4">كود المنتج (SKU)</th>
                <th class="px-6 py-4">اسم المنتج</th>
                <th class="px-6 py-4">الفئة</th>
                <th class="px-6 py-4 text-center">الكمية الحالية</th>
                <th class="px-6 py-4 text-center">الحد الأدنى (الأمان)</th>
                <th class="px-6 py-4">الوحدة</th>
                <th class="px-6 py-4">طبيعة النقص</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($products as $product)
            <tr class="hover:bg-gray-50/80 transition-colors">
                <td class="px-6 py-4 font-mono text-gray-500 text-xs">{{ $product->sku }}</td>
                <td class="px-6 py-4 font-bold text-gray-800">{{ $product->name }}</td>
                <td class="px-6 py-4 text-gray-500">
                    <span class="bg-gray-100 text-gray-600 px-2.5 py-1 rounded-lg text-xs">
                        {{ $product->category->name ?? 'بدون فئة' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-center font-bold {{ $product->quantity == 0 ? 'text-rose-600' : 'text-amber-600' }}">
                    {{ $product->quantity }}
                </td>
                <td class="px-6 py-4 text-center text-gray-400 font-medium">{{ $product->min_quantity }}</td>
                <td class="px-6 py-4 text-gray-500 text-xs">{{ $product->unit }}</td>
                <td class="px-6 py-4">
                    @if($product->quantity == 0)
                        <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-700 px-2.5 py-1 rounded-full text-xs font-bold animate-pulse">
                            ⚠️ نفد تماماً
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 px-2.5 py-1 rounded-full text-xs font-bold">
                            📉 منخفض (تحت الأمان)
                        </span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-12 text-gray-400">
                    <div class="flex flex-col items-center justify-center gap-2">
                        <span class="text-3xl">🎉</span>
                        <p class="font-medium text-gray-500">! لا توجد أي نواقص في المخزن حالياً.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t border-gray-50">{{ $products->links() }}</div>
</div>

@endsection
