<x-app-layout>
    @slot('title', 'تسجيل عميل جديد')

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50">
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-sm border border-gray-100 rounded-xl">

            <div class="text-center mb-8">
                <div class="inline-block bg-blue-50 text-blue-600 font-black text-xl px-4 py-2 rounded-xl tracking-wider mb-2 shadow-sm border border-blue-100">
                    Ad<span class="text-gray-800">Zone</span>
                </div>
                <h2 class="text-xl font-bold text-gray-800 mt-2">✨ إنشاء حساب عميل جديد</h2>
                <p class="text-xs text-gray-400 mt-1">أهلاً بك في المنصة الرقمية الذكية</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 rounded-lg text-xs font-bold space-y-1">
                    @foreach ($errors->all() as $error)
                        <p>• {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-bold text-gray-600 mb-1"> الاسم </label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                           placeholder="مثال: شركة الأمل للتجارة"
                           class="w-full border-gray-300 rounded-xl text-sm text-gray-900 bg-white focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2.5">
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold text-gray-600 mb-1">📱 رقم التليفون</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required
                           placeholder="مثال: 0123456789"
                           class="w-full text-left border-gray-300 rounded-xl text-sm text-gray-900 bg-white focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2.5"
                           style="direction: ltr;">
                    <p class="text-[10px] text-gray-400 mt-1">ملاحظة: رقم تليفونك هو نفسه سيكون الايميل و كلمة المرور الخاصة بك.عند الدخول مره اخرى على الموقع

                    </p>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-blue-600 text-white py-2.5 rounded-xl text-xs font-bold hover:bg-blue-700 transition-all shadow-md shadow-blue-600/10">
                     إنشاء الحساب
                    </button>
                </div>

                <div class="text-center pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-400">لديك حساب بالفعل؟
                        <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">اضغط هنا لتسجيل الدخول</a>
                    </p>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
