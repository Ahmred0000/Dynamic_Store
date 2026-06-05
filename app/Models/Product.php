<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'sku', 'category', 'description',
        'price', 'quantity', 'min_quantity', 'unit', 'is_active'
    ];

    public function isLowStock(): bool
    {
        return $this->quantity <= $this->min_quantity;
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
