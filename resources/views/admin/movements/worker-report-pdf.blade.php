<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تقرير سحبيات العامل - {{ $worker->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style> @media print { .no-print { display: none !important; } } </style>
</head>
<body class="bg-gray-50 p-8">

    <div class="no-print max-w-4xl mx-auto mb-6 bg-purple-50 border border-purple-200 p-4 rounded-xl flex justify-between items-center">
        <p class="text-purple-800 text-sm font-bold">✨ تقرير سحبيات العامل جاهز للحفظ كـ PDF</p>
        <button onclick="window.print()" class="bg-purple-600 text-white px-4 py-2 rounded-lg text-sm font-bold shadow hover:bg-purple-700">🖨️ طباعة التقرير</button>
    </div>

    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-200">
        <div class="border-b-4 border-purple-600 pb-4 mb-6">
            <h1 class="text-2xl font-bold text-gray-900">تقرير حركة سحبيات العامل</h1>
            <p class="text-gray-500 text-sm">اسم العامل: {{ $worker->name }} | التاريخ: {{ date('Y-m-d') }}</p>
        </div>

        <table class="w-full text-right text-sm border-collapse">
            <thead>
                <tr class="bg-gray-900 text-white">
                    <th class="p-3">التاريخ</th>
                    <th class="p-3">المنتج</th>
                    <th class="p-3 text-center">الكمية</th>
                    <th class="p-3">الملاحظات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($workerLogs as $log)
                <tr>
                    <td class="p-3 text-gray-500">{{ $log->created_at->format('Y-m-d') }}</td>
                    <td class="p-3 font-bold">{{ $log->product->name ?? '---' }}</td>
                    <td class="p-3 text-center">{{ $log->quantity }}</td>
                    <td class="p-3 text-gray-600">{{ $log->notes }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script> window.onload = () => setTimeout(() => window.print(), 500); </script>
</body>
</html>
