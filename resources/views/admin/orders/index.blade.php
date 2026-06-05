@extends('layouts.admin')

@section('title', 'الطلبات')
@section('header', 'إدارة الطلبات')

@section('content')

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr class="text-right text-gray-500 border-b">
                <th class="px-4 py-3">رقم الطلب</th>
                <th class="px-4 py-3">العميل</th>
                <th class="px-4 py-3">المنتجات</th>
                <th class="px-4 py-3">الإجمالي</th>
                <th class="px-4 py-3">الحالة</th>
                <th class="px-4 py-3">التاريخ</th>
                <th class="px-4 py-3">إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-3 font-medium">#{{ $order->id }}</td>
                <td class="px-4 py-3">{{ $order->user->name }}</td>
                <td class="px-4 py-3">
                    @foreach($order->items as $item)
                        <div class="text-xs text-gray-500">{{ $item->product->name }} × {{ $item->quantity }}</div>
                    @endforeach
                </td>
                <td class="px-4 py-3">{{ number_format($order->total, 2) }} ج.م</td>
                <td class="px-4 py-3">
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
                <td class="px-4 py-3 text-gray-500">{{ $order->created_at->format('Y/m/d') }}</td>
                <td class="px-4 py-3">
                    @if($order->status == 'pending')
                    <div class="flex gap-2">
                        <form method="POST" action="{{ route('admin.orders.update', $order->id) }}">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="approved">
                            <button type="submit"
                                class="bg-green-100 text-green-700 px-3 py-1 rounded-lg text-xs hover:bg-green-200">
                                موافقة
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.orders.update', $order->id) }}">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit"
                                class="bg-red-100 text-red-700 px-3 py-1 rounded-lg text-xs hover:bg-red-200">
                                رفض
                            </button>
                        </form>
                    </div>
                    @elseif($order->status == 'approved')
                    <form method="POST" action="{{ route('admin.orders.update', $order->id) }}">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="delivered">
                        <button type="submit"
                            class="bg-blue-100 text-blue-700 px-3 py-1 rounded-lg text-xs hover:bg-blue-200">
                            تم التسليم
                        </button>
                    </form>
                    @else
                        <span class="text-gray-400 text-xs">لا يوجد إجراء</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-8 text-gray-400">لا توجد طلبات بعد</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $orders->links() }}</div>
</div>

@endsection
