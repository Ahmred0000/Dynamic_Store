<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير نواقص المخزن - {{ date('Y-m-d') }}</title>
    {{-- استخدام Tailwind من الـ CDN عشان يلقط التنسيق فوري --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* إعدادات مخصصة للطباعة عشان تخفي أي زراير وتخلي الورقة نظيفة */
        @media print {
            .no-print { display: none !important; }
            body { background-color: white; padding: 0; }
        }
    </style>
</head>
<body class="bg-gray-50 font-sans p-8 antialiased">

    {{-- شريط علوي للمستخدم يظهر في الشاشة ويزول عند الحفظ كـ PDF --}}
    <div class="no-print max-w-4xl mx-auto mb-6 bg-blue-50 border border-blue-200 p-4 rounded-xl flex justify-between items-center shadow-sm">
        <p class="text-blue-800 text-sm font-medium">✨ جاهز للحفظ! اضغط على <b>"حفظ بتنسيق PDF"</b> أو <b>"Save as PDF"</b> من قائمة الطباعة.</p>
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold shadow hover:bg-blue-700 transition-all">
            🖨️ فتح أمر الحفظ مجدداً
        </button>
    </div>

    {{-- جسم التقرير الرسمي --}}
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl border border-gray-200 shadow-sm">

        {{-- الهيدر الرئيسي --}}
        <div class="flex justify-between items-center border-b-4 border-blue-600 pb-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">تقرير نواقص المخزن الالكترونى</h1>
                <p class="text-gray-500 text-sm mt-1">تقرير حصر النواقص والأصناف المنخفضة المعتمد</p>
            </div>
            <div class="text-left">
                <p class="text-sm font-semibold text-gray-700">التاريخ: <span class="font-mono">{{ date('Y-m-d') }}</span></p>
                <p class="text-xs text-gray-400 mt-1">حالة المستند: رسمي محمي</p>
            </div>
        </div>

        {{-- بيانات المدير --}}
        <div class="bg-gray-50 p-3 rounded-lg text-sm text-gray-600 mb-6 flex justify-between">
            <div>👤 <strong>المسؤول الصادر باسمه:</strong> المدير التنفيذي </div>
            <div>🔒 <strong>طبيعة الملف:</strong> نسخة  غير قابلة للتعديل</div>
        </div>

        {{-- الجدول النظيف --}}
        <table class="w-full text-right text-sm border-collapse">
            <thead>
                <tr class="bg-gray-900 text-white">
                    <th class="p-3 rounded-r-lg">كود المنتج (SKU)</th>
                    <th class="p-3">اسم المنتج</th>
                    <th class="p-3">الفئة</th>
                    <th class="p-3 text-center">الكمية الحالية</th>
                    <th class="p-3 text-center">حد الأمان</th>
                    <th class="p-3 rounded-l-lg">الحالة</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="p-3 font-mono text-xs text-gray-500">{{ $product->sku }}</td>
                    <td class="p-3 font-bold text-gray-800">{{ $product->name }}</td>
                    <td class="p-3 text-gray-600">{{ $product->category->name ?? 'بدون فئة' }}</td>
                    <td class="p-3 text-center font-bold {{ $product->quantity == 0 ? 'text-red-600' : 'text-amber-600' }}">
                        {{ $product->quantity }} {{ $product->unit }}
                    </td>
                    <td class="p-3 text-center text-gray-400 font-medium">{{ $product->min_quantity }}</td>
                    <td class="p-3">
                        @if($product->quantity == 0)
                            <span class="text-red-700 font-bold text-xs">⚠️ نفد تماماً</span>
                        @else
                            <span class="text-amber-700 font-bold text-xs">📉 منخفض</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-8 text-gray-400"> ! لا توجد نواقص بالمخزن حالياً.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- التوقيع والختم أسفل الصفحة --}}
        <div class="mt-12 pt-6 border-t border-dashed border-gray-200 flex justify-between text-sm text-gray-500">
            <p>* يتم توليد هذا المستند إلكترونياً وبشكل فوري  .</p>
            {{-- <div class="text-center pl-6">
                <p class="font-bold text-gray-700">اعتماد الإدارة العامة</p>
                <div class="w-24 h-12 border border-gray-300 border-dashed mx-auto mt-2 rounded flex items-center justify-center text-xs text-gray-300">الختم الرسمي</div>
            </div> --}}
        </div>

    </div>

    {{-- الكود السحري لفتح قائمة الـ PDF فوراً --}}
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
