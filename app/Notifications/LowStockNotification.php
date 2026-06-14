<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    use Queueable;

    protected $product;

    // بنمرر المنتج اللي كميته نقصت هنا
    public function __construct($product)
    {
        $this->product = $product;
    }

    // تحديد القنوات (هنخزن الإشعار في قاعدة البيانات للجرس)
    public function via($notifiable)
    {
        return ['database'];
    }

    // الداتا اللي جرس الأدمن هيقراها ويعرضها
    public function toArray($notifiable)
    {
        return [
            'type'         => 'low_stock',
            'product_id'   => $this->product->id,
            'product_name' => $this->product->name,
            'current_qty'  => $this->product->quantity,
        ];
    }
}
