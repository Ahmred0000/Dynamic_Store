<nav x-data="{ open: false }" class="bg-slate-900 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}" class="text-xl font-black tracking-wider text-white flex items-center gap-1">
                        <span class="text-indigo-500">Ad</span>Zone
                    </a>
                </div>

                @php
                    // الإجراء الآمن: تحديد الـ Route ديناميكياً فقط إذا كان هناك مستخدم مسجل دخول
                    $dashboardRoute = route('login');
                    $userRole = '';

                    if (auth()->check()) {
                        $userRole = auth()->user()->roles->first()->name ?? '';
                        if ($userRole === 'admin') $dashboardRoute = route('admin.dashboard');
                        if ($userRole === 'worker') $dashboardRoute = route('worker.dashboard');
                        if ($userRole === 'customer') $dashboardRoute = route('customer.dashboard');
                    }
                @endphp

                <div class="hidden space-x-8 space-x-reverse sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="$dashboardRoute" :active="request()->routeIs('*.dashboard')" class="text-slate-300 hover:text-white border-indigo-500">
                        {{ __('لوحة التحكم') }}
                    </x-nav-link>
                </div>
            </div>

            {{-- تظهر القائمة فقط إذا كان هناك مستخدم مسجل دخول --}}
            @if(auth()->check())
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-slate-300 bg-slate-900 hover:text-white focus:outline-none transition ease-in-out duration-150">
                            <div>{{ auth()->user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        {{-- الأمان: زر تعديل الحساب يظهر فقط للأدمن --}}
                        @if($userRole === 'admin')
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('الملف الشخصي') }}
                            </x-dropdown-link>
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('تسجيل الخروج') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
            @else
            {{-- لو زائر مش مسجل دخول (زي صفحة الـ Register) بنعرض له زرار تسجيل الدخول --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <a href="{{ route('login') }}" class="text-sm text-slate-300 hover:text-white font-medium">تسجيل الدخول</a>
            </div>
            @endif

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-white hover:bg-slate-800 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- القائمة الخاصة بالموبايل --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="$dashboardRoute" :active="request()->routeIs('*.dashboard')" class="text-slate-300">
                {{ __('لوحة التحكم') }}
            </x-responsive-nav-link>
        </div>

        @if(auth()->check())
        <div class="pt-4 pb-1 border-t border-slate-800">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ auth()->user()->name }}</div>
                <div class="font-medium text-sm text-slate-400">{{ auth()->user()->email ?? auth()->user()->phone }}</div>
            </div>

            <div class="mt-3 space-y-1">
                @if($userRole === 'admin')
                    <x-responsive-nav-link :href="route('profile.edit')" class="text-slate-300">
                        {{ __('الملف الشخصي') }}
                    </x-responsive-nav-link>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();" class="text-slate-300">
                        {{ __('تسجيل الخروج') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @endif
    </div>
</nav>
