<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة طلبية رقم {{ $order->order_number }} - {{ date('Y-m-d') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background-color: white; padding: 0; }
        }
    </style>
</head>
<body class="bg-gray-50 font-sans p-8 antialiased">

    {{-- شريط الإجراءات (لا يظهر في الـ PDF) --}}
    <div class="no-print max-w-4xl mx-auto mb-6 bg-blue-50 border border-blue-200 p-4 rounded-xl flex justify-between items-center shadow-sm">
        <p class="text-blue-800 text-sm font-medium">✨ فاتورة العميل جاهزة! اضغط على "Save as PDF".</p>
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold shadow hover:bg-blue-700 transition-all">
            🖨️ طباعة أو حفظ الفاتورة
        </button>
    </div>

    {{-- جسم الفاتورة الرسمي --}}
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl border border-gray-200 shadow-sm">

        {{-- الهيدر --}}
        <div class="flex justify-between items-center border-b-4 border-blue-600 pb-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">فاتورة مبيعات</h1>
                <p class="text-gray-500 text-sm mt-1">شركة AdZone للتسويق الرقمي والبرمجيات</p>
            </div>
            <div class="text-left">
                <p class="text-sm font-semibold text-gray-700">رقم الطلب: <span class="font-mono text-blue-600">{{ $order->order_number }}</span></p>
                <p class="text-xs text-gray-400 mt-1">التاريخ: {{ $order->created_at->format('Y-m-d') }}</p>
            </div>
        </div>

        {{-- بيانات العميل --}}
        <div class="bg-gray-50 p-4 rounded-lg text-sm mb-6 flex justify-between">
            <div>👤 <strong>العميل:</strong> {{ $order->customer_name }}</div>
            <div>💼 <strong>الحالة:</strong>
                <span class="font-bold {{ $order->status == 'approved' ? 'text-green-600' : 'text-amber-600' }}">
                    {{ $order->status == 'approved' ? 'تم الاعتماد' : 'معلق' }}
                </span>
            </div>
        </div>

        {{-- جدول المنتجات --}}
        <table class="w-full text-right text-sm border-collapse mb-8">
            <thead>
                <tr class="bg-gray-900 text-white">
                    <th class="p-3 rounded-r-lg">المنتج</th>
                    <th class="p-3 text-center">الكمية</th>
                    <th class="p-3 text-center">سعر الوحدة</th>
                    <th class="p-3 text-center rounded-l-lg">الإجمالي الفرعي</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($order->items as $item)
                <tr class="hover:bg-gray-50">
                    <td class="p-3 font-bold text-gray-800">{{ $item->product->name ?? 'منتج محذوف' }}</td>
                    <td class="p-3 text-center text-gray-600">{{ $item->quantity }}</td>
                    <td class="p-3 text-center text-gray-600">{{ number_format($item->price, 2) }} ج.م</td>
                    <td class="p-3 text-center font-bold text-gray-900">{{ number_format($item->quantity * $item->price, 2) }} ج.م</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- الإجمالي النهائي --}}
        <div class="flex justify-end">
            <div class="w-full md:w-1/3 bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="flex justify-between text-lg font-bold">
                    <span>الإجمالي الكلي:</span>
                    <span class="text-blue-700">{{ number_format($order->total_price, 2) }} ج.م</span>
                </div>
            </div>
        </div>

        {{-- التذييل --}}
        <div class="mt-12 pt-6 border-t border-dashed border-gray-200 text-center text-sm text-gray-500">
            <p>شكراً لثقتكم بشركة AdZone - نحن هنا لخدمتكم دائماً</p>
            <p class="mt-2 text-xs">تم إصدار هذه الفاتورة إلكترونياً</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() { window.print(); }, 500);
        };
    </script>
</body>
</html>
