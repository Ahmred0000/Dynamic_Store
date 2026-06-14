<x-app-layout>
    <div class="p-6 min-h-screen text-slate-100" style="background-color: #0f172a;" x-data="{ openWithdraw: false, selectedProduct: {}, activeCategory: 'all' }">

        {{-- هيدر الصفحة والترحيب بنفس أسلوب الأدمن --}}
        <div class="flex justify-between items-center mb-8 border-b border-slate-700 pb-4">
            <div>
                <h1 class="text-2xl font-bold text-white">نظام سحب المواد الخام - خط الإنتاج</h1>
                <p class="text-slate-400 text-sm mt-1">مرحباً بك: {{ auth()->user()->name }} | يمكنك طلب سحب للكميات المطلوبة للتصنيع فوراً</p>
            </div>
            <span class="bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 text-xs font-bold px-3 py-1.5 rounded-full">
                حساب خط الإنتاج
            </span>
        </div>

        {{-- رسائل النجاح أو الأخطاء --}}
        @if(session('success'))
            <div class="mb-4 p-4 bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 rounded-xl font-bold">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 bg-rose-500/20 border border-rose-500/30 text-rose-400 rounded-xl font-bold">
                {{ session('error') }}
            </div>
        @endif

        {{-- 2️⃣ ثانياً: عرض الفئات (Categories) كأزرار فلترة ديناميكية زي صفحة الأدمن بالظبط --}}
        <div class="mb-6 flex flex-wrap gap-2">
            <button @click="activeCategory = 'all'"
                    :class="activeCategory === 'all' ? 'bg-indigo-600 text-white' : 'bg-slate-800 text-slate-400 hover:bg-slate-700'"
                    class="px-4 py-2 rounded-xl text-sm font-bold transition-colors duration-200">
                الكل
            </button>
            @php
                // جلب الفئات الفريدة المتوفرة في منتجات هذا العامل فقط
                $categories = $products->pluck('category')->unique('id')->filter();
            @endphp
            @foreach($categories as $category)
                <button @click="activeCategory = '{{ $category->id }}'"
                        :class="activeCategory === '{{ $category->id }}' ? 'bg-indigo-600 text-white' : 'bg-slate-800 text-slate-400 hover:bg-slate-700'"
                        class="px-4 py-2 rounded-xl text-sm font-bold transition-colors duration-200">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>

        {{-- شبكة المنتجات بشكل كروت متناسقة مع الثيم الغامق --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($products as $product)
            <div x-show="activeCategory === 'all' || activeCategory === '{{ $product->category_id }}'"
                 class="bg-slate-900 border border-slate-800 p-6 rounded-2xl shadow-lg transition-all duration-300 hover:border-slate-700">

                <div class="flex justify-between items-start mb-4">
                    <span class="bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 text-xs font-bold px-2.5 py-1 rounded-lg">
                        {{ $product->category->name ?? 'عام' }}
                    </span>
                    <span class="text-sm font-mono text-slate-400 font-bold">
                        المتاح: <span class="text-emerald-400">{{ $product->quantity }}</span> {{ $product->unit ?? 'قطعة' }}
                    </span>
                </div>

                <h3 class="text-lg font-bold text-white mb-6">{{ $product->name }}</h3>

                {{-- العامل ليس له أي صلاحية سوى الضغط على زر السحب فقط --}}
                <button @click="selectedProduct = { id: {{ $product->id }}, name: '{{ $product->name }}', max: {{ $product->quantity }}, unit: '{{ $product->unit ?? 'قطعة' }}' }; openWithdraw = true"
                        class="w-full bg-indigo-600 text-white py-2.5 rounded-xl font-bold hover:bg-indigo-500 transition-colors shadow-lg shadow-indigo-600/10"
                        {{ $product->quantity <= 0 ? 'disabled' : '' }}>
                    {{ $product->quantity <= 0 ? 'نفد من المخزن' : 'طلب سحب للمصنع' }}
                </button>
            </div>
            @endforeach
        </div>

        {{-- مودال السحب التفاعلي --}}
        <div x-show="openWithdraw"
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" x-cloak>

            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 w-full max-w-md shadow-2xl" @click.away="openWithdraw = false">
                <h2 class="text-xl font-bold text-white mb-2">تأكيد سحب مادة خام</h2>
                <p class="text-sm text-slate-400 mb-6">أنت تقوم بسحب صنف: <span class="text-indigo-400 font-bold" x-text="selectedProduct.name"></span></p>

                <form action="{{ route('worker.inventory.deduct') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" :value="selectedProduct.id">

                    <div class="mb-4">
                        <label class="text-xs font-bold text-slate-400 block mb-2">الكمية المطلوبة للتصنيع</label>
                        <div class="flex items-center gap-2">
                            <input type="number" name="quantity" required min="1" :max="selectedProduct.max"
                                   class="w-full bg-slate-800 border border-slate-700 rounded-xl p-2.5 text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none focus:border-indigo-500">
                            <span class="text-slate-300 font-bold text-sm bg-slate-800 px-4 py-2.5 rounded-xl border border-slate-700" x-text="selectedProduct.unit"></span>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-8">
                        <button type="button" @click="openWithdraw = false" class="text-slate-400 font-bold hover:text-slate-200 transition-colors px-4 py-2">إلغاء</button>
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-indigo-500 transition-colors shadow-lg shadow-indigo-600/20">
                            تأكيد السحب الفوري
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
