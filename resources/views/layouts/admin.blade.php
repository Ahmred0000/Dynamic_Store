<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'نظام إدارة المخزن')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50 font-sans antialiased selection:bg-blue-500 selection:text-white">

    <div class="flex h-screen overflow-hidden">
        {{-- القائمة الجانبية - Sidebar --}}
        <aside class="w-64 bg-gray-900 text-white flex flex-col shadow-xl z-20">
            <div class="p-6 border-b border-gray-800">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🏪</span>
                    <div>
                        <h1 class="text-lg font-bold tracking-wide">نظام المخزن</h1>
                        <p class="text-gray-400 text-xs mt-0.5">لوحة المدير العام</p>
                    </div>
                </div>
            </div>

            {{-- روابط التنقل مع أنيميشن وسلاسة كاملة --}}
            <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto">
                {{-- الرئيسية --}}
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white font-semibold shadow-lg shadow-blue-600/20' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <span class="text-lg">📊</span> الرئيسية
                </a>

                {{-- إدارة الفئات --}}
                <a href="{{ route('admin.categories.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.categories.*') ? 'bg-blue-600 text-white font-semibold shadow-lg shadow-blue-600/20' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <span class="text-lg">📂</span> إدارة الفئات
                </a>

                {{-- المنتجات --}}
                <a href="{{ route('admin.products.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.products*') ? 'bg-blue-600 text-white font-semibold shadow-lg shadow-blue-600/20' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <span class="text-lg">📦</span> المنتجات
                </a>

                <a href="{{ route('admin.movements.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.movements.*') ? 'bg-blue-600 text-white font-semibold' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        <span class="text-lg">⚙️</span> حركة المخزن والطلبات
                    </a>

                {{-- المستخدمين --}}
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.users*') ? 'bg-blue-600 text-white font-semibold shadow-lg shadow-blue-600/20' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <span class="text-lg">👥</span> المستخدمين
                </a>
                {{-- تقرير النواقص --}}
                <a href="{{ route('admin.reports.low-stock') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.reports.*') ? 'bg-blue-600 text-white font-semibold shadow-lg shadow-blue-600/20' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <span class="text-lg">📊</span> تقرير النواقص
                </a>
            </nav>

            {{-- معلومات المستخدم وتسجيل الخروج --}}
            <div class="p-4 border-t border-gray-800 bg-gray-950/40">
                <div class="flex items-center gap-3 mb-3 px-2">
                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center font-bold text-sm text-white">
                        {{ mb_substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <p class="text-gray-300 text-sm font-medium truncate">{{ Auth::user()->name }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-2 justify-center px-4 py-2.5 rounded-xl text-red-400 hover:bg-red-500/10 hover:text-red-300 text-sm font-medium transition-colors border border-dashed border-red-500/20">
                        <span>🚪</span> تسجيل الخروج
                    </button>
                </form>
            </div>
        </aside>

        {{-- المحتوى الرئيسي للوحة التحكم --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- الهيدر العلوي --}}
            <header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between z-10">
                <h2 class="text-xl font-bold text-gray-800 tracking-tight">
                    @yield('header', 'لوحة التحكم')
                </h2>
                <div class="flex items-center gap-2 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">
                    <span class="inline-block w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    <span class="text-gray-600 text-xs font-semibold">
                        {{ Auth::user()->name }}
                    </span>
                </div>
            </header>

            {{-- منطقة العرض الأساسية --}}
            <main class="flex-1 overflow-y-auto bg-gray-50/50">
                {{-- قسم الإشعارات والتنبيهات السلسة --}}
                <div class="px-8 pt-6 max-w-7xl mx-auto w-full">
                    @if(session('success'))
                        <div class="bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-xl px-4 py-3.5 shadow-sm flex items-center gap-3 animate-fade-in transition-all">
                            <span class="text-emerald-500 text-lg">✅</span>
                            <span class="text-sm font-medium">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="bg-rose-50 text-rose-800 border border-rose-200 rounded-xl px-4 py-3.5 shadow-sm flex items-center gap-3 animate-fade-in transition-all">
                            <span class="text-rose-500 text-lg">❌</span>
                            <span class="text-sm font-medium">{{ session('error') }}</span>
                        </div>
                    @endif
                </div>

                {{-- المحتوى المتغير لكل صفحة --}}
                <div class="p-8 max-w-7xl mx-auto w-full">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

</body>
</html>
