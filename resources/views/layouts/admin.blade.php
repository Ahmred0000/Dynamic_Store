<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'نظام إدارة المخزن')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">

    <div class="flex h-screen">
        <aside class="w-64 bg-gray-900 text-white flex flex-col">
            <div class="p-6 border-b border-gray-700">
                <h1 class="text-xl font-bold">🏪 نظام المخزن</h1>
                <p class="text-gray-400 text-sm mt-1">لوحة المدير</p>
            </div>

            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
                    <span>📊</span> الرئيسية
                </a>
                <a href="{{ route('admin.products.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.products*') ? 'bg-gray-700' : '' }}">
                    <span>📦</span> المنتجات
                </a>
                <a href="{{ route('admin.orders.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.orders*') ? 'bg-gray-700' : '' }}">
                    <span>🛒</span> الطلبات
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.users*') ? 'bg-gray-700' : '' }}">
                    <span>👥</span> المستخدمين
                </a>
            </nav>

            <div class="p-4 border-t border-gray-700">
                <p class="text-gray-400 text-sm mb-2">{{ Auth::user()->name }}</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full text-right text-red-400 hover:text-red-300 text-sm">
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 overflow-auto">
            <header class="bg-white shadow px-8 py-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-700">
                    @yield('header', 'لوحة التحكم')
                </h2>
                <span class="text-gray-500 text-sm">
                    {{ Auth::user()->name }}
                </span>
            </header>

            <div class="px-8 pt-4">
                @if(session('success'))
                    <div class="bg-green-100 text-green-800 border border-green-300 rounded-lg px-4 py-3 mb-4">
                        ✅ {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 text-red-800 border border-red-300 rounded-lg px-4 py-3 mb-4">
                        ❌ {{ session('error') }}
                    </div>
                @endif
            </div>

            <div class="p-8">
                @yield('content')
            </div>
        </main>
    </div>

</body>
</html>
