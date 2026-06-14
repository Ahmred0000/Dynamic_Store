<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فاتورة رقم: {{ $order->order_number }}</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 20px; color: #333; direction: rtl; text-align: right; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, .15); font-size: 14px; line-height: 24px; background: #fff; }
        .invoice-header { display: flex; justify-content: space-between; align-items: center; border-b: 2px solid #3b82f6; padding-bottom: 20px; margin-bottom: 20px; }
        .logo { font-size: 26px; font-weight: bold; color: #2563eb; }
        .logo span { color: #374151; }
        .table { w-full; width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #e5e7eb; padding: 12px; text-align: right; }
        .table th { background-color: #f9fafb; font-weight: bold; }
        .total-section { text-align: left; margin-top: 20px; font-size: 16px; font-weight: bold; }
        .btn-print { background: #2563eb; color: #fff; border: none; padding: 8px 16px; border-radius: 6px; font-size: 12px; font-weight: bold; cursor: pointer; margin-bottom: 15px; }
        @media print { .btn-print { display: none; } body { margin: 0; } .invoice-box { border: none; box-shadow: none; } }
    </style>
</head>
<body>

    <div class="invoice-box">
        <button class="btn-print" onclick="window.print()">🖨️ طباعة الفاتورة الآن</button>

        <div class="invoice-header">
            <div class="logo">Ad<span>Zone</span></div>
            <div>
                <strong>رقم الفاتورة:</strong> {{ $order->order_number }}<br>
                <strong>التاريخ:</strong> {{ $order->created_at->format('Y-m-d H:i') }}<br>
                <strong>الحالة:</strong>
                @if($order->status == 'pending') قيد المراجعة @elseif($order->status == 'approved') معتمدة @else مرفوضة @endif
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <strong>العميل الكريم:</strong> {{ $order->user->name }}<br>
            <strong>رقم الهاتف:</strong> {{ $order->user->phone ?? '---' }}
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>اسم المنتج</th>
                    <th>سعر الوحدة</th>
                    <th style="text-align: center;">الكمية</th>
                    <th>الإجمالي الفرعي</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'منتج غير مدرج' }}</td>
                    <td>{{ number_format($item->price, 2) }} ج.م</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price * $item->quantity, 2) }} ج.م</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($order->notes)
            <div style="margin-top: 15px; background: #f3f4f6; padding: 10px; border-radius: 6px; font-size: 12px;">
                <strong>ملاحظات الطلب:</strong> {{ $order->notes }}
            </div>
        @endif

        <div class="total-section">
            الإجمالي الكلي: <span style="color: #2563eb;">{{ number_format($order->total_price, 2) }} ج.م</span>
        </div>
    </div>

</body>
</html>
