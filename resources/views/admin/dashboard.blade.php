@extends('layouts.admin')

@section('title', 'لوحة التحكم')
@section('header', 'لوحة التحكم')

@section('content')

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 shadow text-center">
            <div class="text-3xl font-bold text-blue-600">{{ $stats['total_products'] }}</div>
            <div class="text-gray-500 text-sm mt-1">إجمالي المنتجات</div>
        </div>
        <div class="bg-white rounded-xl p-6 shadow text-center">
            <div class="text-3xl font-bold text-yellow-500">{{ $stats['low_stock'] }}</div>
            <div class="text-gray-500 text-sm mt-1">مخزون منخفض</div>
        </div>
        <div class="bg-white rounded-xl p-6 shadow text-center">
            <div class="text-3xl font-bold text-red-500">{{ $stats['out_of_stock'] }}</div>
            <div class="text-gray-500 text-sm mt-1">نفد المخزون</div>
        </div>
        <div class="bg-white rounded-xl p-6 shadow text-center">
            <div class="text-3xl font-bold text-green-500">{{ $stats['pending_orders'] }}</div>
            <div class="text-gray-500 text-sm mt-1">طلبات معلقة</div>
        </div>
    </div>

    {{-- Low Stock --}}
    @if($low_stock_products->count())
    <div class="bg-white rounded-xl shadow p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">⚠️ منتجات تحتاج تجديد</h3>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-right text-gray-500 border-b">
                    <th class="pb-2">المنتج</th>
                    <th class="pb-2">الكمية</th>
                    <th class="pb-2">الحد الأدنى</th>
                    <th class="pb-2">الحالة</th>
                </tr>
            </thead>
            <tbody>
                @foreach($low_stock_products as $product)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3">{{ $product->name }}</td>
                    <td class="py-3">{{ $product->quantity }}</td>
                    <td class="py-3">{{ $product->min_quantity }}</td>
                    <td class="py-3">
                        @if($product->quantity == 0)
                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs">نفد</span>
                        @else
                            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs">منخفض</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Recent Orders --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">🛒 آخر الطلبات</h3>
        @if($recent_orders->count())
        <table class="w-full text-sm">
            <thead>
                <tr class="text-right text-gray-500 border-b">
                    <th class="pb-2">رقم الطلب</th>
                    <th class="pb-2">العميل</th>
                    <th class="pb-2">الإجمالي</th>
                    <th class="pb-2">الحالة</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recent_orders as $order)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3">#{{ $order->id }}</td>
                    <td class="py-3">{{ $order->user->name }}</td>
                    <td class="py-3">{{ number_format($order->total, 2) }} ج.م</td>
                    <td class="py-3">
                        @if($order->status == 'pending')
                            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs">معلق</span>
                        @elseif($order->status == 'approved')
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">موافق عليه</span>
                        @elseif($order->status == 'rejected')
                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs">مرفوض</span>
                        @else
                            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs">تم التسليم</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <p class="text-gray-400 text-center py-4">لا توجد طلبات بعد</p>
        @endif
    </div>

@endsection
