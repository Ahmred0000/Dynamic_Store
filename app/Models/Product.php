<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'category_id', // تعديل الربط ليكون بالـ ID الجديد للفئة
        'description',
        'price',
        'quantity',
        'min_quantity',
        'unit',
        'is_active'
    ];

    public function isLowStock(): bool
    {
        return $this->quantity <= $this->min_quantity;
    }

    /**
     * علاقة المنتج بالفئة (كل منتج ينتمي إلى فئة معينة)
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
