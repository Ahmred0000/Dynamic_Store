<x-app-layout>
    @slot('title', 'لوحة التحكم - العميل')

    <div class="container mx-auto p-6 space-y-8 font-sans" dir="rtl">

        {{-- هيدر الترحيب --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-center">
            <div>
                <h1 class="text-2xl font-black text-gray-800">👋 أهلاً بك يا {{ Auth::user()->name }}</h1>
                <p class="text-sm text-gray-500 mt-1">مرحباً بك في منصة <span class="font-bold text-blue-600">AdZone</span> - تابع طلباتك وأدِ طلبات جديدة بسهولة.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="text-xs text-gray-400">تاريخ اليوم: {{ date('Y-m-d') }}</span>
            </div>
        </div>

        {{-- كروت الإحصائيات السريعة --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 font-bold">إجمالي طلباتك</p>
                    <h4 class="text-2xl font-black text-gray-800 mt-1">{{ $my_orders->count() }}</h4>
                </div>
                <div class="bg-blue-50 text-blue-600 p-4 rounded-xl text-xl">📦</div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 font-bold">طلبات في انتظار المراجعة</p>
                    <h4 class="text-2xl font-black text-amber-600 mt-1">{{ $my_orders->where('status', 'pending')->count() }}</h4>
                </div>
                <div class="bg-amber-50 text-amber-600 p-4 rounded-xl text-xl">⏳</div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 font-bold">طلبات تم اعتمادها</p>
                    <h4 class="text-2xl font-black text-green-600 mt-1">{{ $my_orders->where('status', 'approved')->count() }}</h4>
                </div>
                <div class="bg-green-50 text-green-600 p-4 rounded-xl text-xl">✅</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- قسم معاينة المنتجات المتاحة للبيع --}}
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-black text-gray-700 flex items-center gap-2">🛍️ منتجات متاح طلبها الآن</h3>

                    {{-- الزرار الجديد الجذاب --}}
                    <a href="{{ route('customer.orders.index') }}"
                       class="group relative overflow-hidden bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-6 py-3 rounded-xl transition-all duration-300 shadow-lg shadow-blue-500/20 hover:shadow-blue-500/40 hover:-translate-y-0.5 flex items-center gap-2">
                       <span class="relative z-10">🚀 اطلب الآن من المتجر</span>
                       <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($products as $product)
                    <div class="p-4 rounded-xl border border-gray-100 bg-gray-50/50 flex justify-between items-center hover:border-blue-200 transition-colors">
                        <div>
                            <h5 class="font-bold text-gray-800 text-sm">{{ $product->name }}</h5>
                            <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold">{{ $product->category->name ?? 'غير مصنف' }}</p>
                        </div>
                        <div class="text-left">
                            <span class="text-xs font-black text-gray-900 block">{{ number_format($product->price, 2) }} ج.م</span>
                            <span class="text-[9px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded font-bold mt-1 inline-block">متاح بالمخزن</span>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-400 col-span-2 py-8 text-center">لا توجد منتجات بيع متاحة حالياً.</p>
                    @endforelse
                </div>
            </div>

            {{-- قسم آخر الطلبات --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-black text-gray-700 mb-6 flex items-center gap-2">⏱️ آخر الطلبات الفورية</h3>
                <div class="space-y-4">
                    @forelse($my_orders as $order)
                    <div class="p-4 rounded-xl border border-gray-100 bg-gray-50 flex justify-between items-center hover:bg-gray-100 transition-colors">
                        <div>
                            <span class="block text-xs font-black text-gray-800">فاتورة #{{ $order->order_number }}</span>
                            <span class="block text-[10px] text-gray-400 font-mono mt-1">{{ number_format($order->total_price, 2) }} ج.م</span>
                        </div>
                        <div>
                            @if($order->status == 'pending')
                                <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-3 py-1 rounded-full">⏳ معلق</span>
                            @elseif($order->status == 'approved')
                                <span class="bg-green-100 text-green-800 text-[10px] font-bold px-3 py-1 rounded-full">✅ معتمد</span>
                            @else
                                <span class="bg-red-100 text-red-800 text-[10px] font-bold px-3 py-1 rounded-full">❌ مرفوض</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-gray-400 py-8 text-center">لم تقم بعمل أي طلبات حتى الآن.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
