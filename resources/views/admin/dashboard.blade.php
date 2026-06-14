@extends('layouts.admin')

@section('title', 'لوحة التحكم ')

@section('content')
<div class="p-6 bg-[#0f172a] min-h-screen text-slate-200">

    {{-- هيدر اللوحة --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white tracking-tight">نظرة عامة على المخزن الالكتروني</h1>
        <p class="text-slate-400 text-sm mt-1">أهلاً بك مجددا.</p>
    </div>

    {{-- البطاقات العلوية --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        @foreach([
            ['إجمالي المنتجات', $stats['total_products'], 'text-blue-400', 'bg-blue-500/10'],
            ['مخزون منخفض', $stats['low_stock'], 'text-amber-400', 'bg-amber-500/10'],
            ['نفاد المخزون', $stats['out_of_stock'], 'text-rose-400', 'bg-rose-500/10'],
            ['طلبات معلقة', $stats['pending_orders'], 'text-emerald-400', 'bg-emerald-500/10']
        ] as $stat)
        <div class="bg-[#1e293b] p-5 rounded-2xl border border-slate-700/50 flex flex-col justify-between hover:border-slate-500 transition-all">
            <span class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">{{ $stat[0] }}</span>
            <div class="flex items-end justify-between mt-2">
                <span class="text-2xl font-black text-white">{{ $stat[1] }}</span>
                <div class="{{ $stat[3] }} {{ $stat[2] }} px-2 py-1 rounded-lg text-xs font-bold">●</div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- الشبكة الرئيسية للبيانات --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- جدول النواقص --}}
        <div class="bg-[#1e293b] rounded-2xl border border-slate-700/50 p-6 lg:col-span-1">
            <h3 class="font-bold text-white mb-6 flex items-center gap-2">⚠️ تنبيهات المخزون</h3>
            <div class="space-y-4">
                @forelse($low_stock_products->take(5) as $product)
                <div class="flex items-center justify-between border-b border-slate-700/50 pb-3 last:border-0 last:pb-0">
                    <span class="text-slate-300 text-sm truncate w-3/4">{{ $product->name }}</span>
                    <span class="bg-slate-700 text-slate-200 px-2 py-0.5 rounded text-[10px] font-bold">{{ $product->quantity }}</span>
                </div>
                @empty
                <p class="text-slate-500 text-xs text-center py-4">المخزون مكتمل!</p>
                @endforelse
            </div>
        </div>

        {{-- آخر الطلبات --}}
        <div class="bg-[#1e293b] rounded-2xl border border-slate-700/50 p-6 lg:col-span-1">
            <h3 class="font-bold text-white mb-6 flex items-center gap-2">🛒 آخر الطلبات</h3>
            <div class="space-y-4">
                @forelse($recent_orders->take(5) as $order)
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-white truncate">{{ $order->user->name ?? 'غير معروف' }}</p>
                        <p class="text-[9px] text-slate-500">طلب #{{ $order->id }}</p>
                    </div>
                    <span class="text-xs font-bold text-emerald-400">{{ number_format($order->total, 0) }} ج.م</span>
                </div>
                @empty
                <p class="text-slate-500 text-xs text-center py-4">لا توجد طلبات</p>
                @endforelse
            </div>
        </div>

        {{-- العمال والفئات (دمجهم في كولوم واحد عشان التناسق) --}}
        <div class="space-y-6 lg:col-span-1">
            <div class="bg-[#1e293b] rounded-2xl border border-slate-700/50 p-6">
                <h3 class="font-bold text-white mb-4 text-sm">👷‍♂️ أحدث العمال</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($workers as $worker)
                        <span class="bg-slate-700 text-slate-300 px-3 py-1 rounded-lg text-[10px] font-bold">{{ $worker->name }}</span>
                    @endforeach
                </div>
            </div>
            <div class="bg-[#1e293b] rounded-2xl border border-slate-700/50 p-6">
                <h3 class="font-bold text-white mb-4 text-sm">📂 فئات المنتجات</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($categories as $cat)
                        <span class="bg-blue-900/30 text-blue-300 px-3 py-1 rounded-lg text-[10px] font-bold border border-blue-900">{{ $cat->name }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
