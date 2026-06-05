<x-guest-layout>
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">نظام إدارة المخزن</h1>
        <p class="text-gray-500 text-sm mt-1">سجّل دخولك للمتابعة</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- البريد الإلكتروني -->
        <div class="mb-4">
            <x-input-label for="email" value="البريد الإلكتروني" />
            <x-text-input id="email" class="block mt-1 w-full text-right"
                type="email" name="email" :value="old('email')"
                required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- كلمة المرور -->
        <div class="mb-4">
            <x-input-label for="password" value="كلمة المرور" />
            <x-text-input id="password" class="block mt-1 w-full text-right"
                type="password" name="password"
                required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- تذكرني -->
        <div class="flex items-center justify-between mb-4">
            <label class="flex items-center gap-2 text-sm text-gray-600">
                <input type="checkbox" name="remember"
                    class="rounded border-gray-300 text-indigo-600">
                تذكرني
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                    class="text-sm text-indigo-600 hover:underline">
                    نسيت كلمة المرور؟
                </a>
            @endif
        </div>

        <x-primary-button class="w-full justify-center py-3">
            تسجيل الدخول
        </x-primary-button>
    </form>
</x-guest-layout>
