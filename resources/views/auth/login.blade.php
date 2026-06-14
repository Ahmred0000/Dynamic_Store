<x-app-layout>
    @slot('title', 'تسجيل الدخول')

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50">
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-xl shadow-gray-100 border border-gray-100 rounded-2xl">

            <div class="text-center mb-8">
                <div class="inline-block bg-blue-50 text-blue-600 font-black text-xl px-4 py-2 rounded-xl tracking-wider mb-2 shadow-sm border border-blue-100">
                    Ad<span class="text-gray-800">Zone</span>
                </div>
                <h2 class="text-xl font-black text-gray-800 mt-2">🔐 تسجيل الدخول</h2>
                <p class="text-xs text-gray-400 mt-1">أهلاً بك في نظام الإدارة الخاص بك</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-xs font-bold space-y-1">
                    @foreach ($errors->all() as $error)
                        <p>• {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    {{-- التعديل هنا: توضيح للعميل أن الخانة تقبل الإيميل أو رقم الهاتف --}}
                    <label for="email" class="block text-xs font-bold text-gray-600 mb-1">📧 البريد الإلكتروني أو رقم الهاتف</label>
                    <input id="email" type="text" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="اكتب البريد الإلكتروني أو رقم الهاتف"
                           class="w-full border-gray-200 rounded-xl text-sm text-gray-900 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all p-3">
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label for="password" class="block text-xs font-bold text-gray-600">🔑 كلمة المرور</label>
                        @if (Route::has('password.request'))
                            <a class="text-[11px] text-blue-600 hover:underline font-semibold" href="{{ route('password.request') }}">نسيت كلمة المرور؟</a>
                        @endif
                    </div>
                    <input id="password" type="password" name="password" required
                           placeholder="••••••••"
                           class="w-full border-gray-200 rounded-xl text-sm text-gray-900 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all p-3">
                </div>

                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" checked
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                    <label for="remember_me" class="mr-2 text-xs text-gray-500 font-bold select-none">تذكرني دائماً</label>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3.5 rounded-xl text-sm font-black transition-all shadow-lg shadow-blue-600/20 active:scale-95">
                        🔑 تسجيل الدخول
                    </button>
                </div>

                <div class="text-center pt-4 border-t border-gray-50">
                    <p class="text-xs text-gray-400">ليس لديك حساب؟
                        <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">اضغط هنا لإنشاء حساب عميل</a>
                    </p>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
