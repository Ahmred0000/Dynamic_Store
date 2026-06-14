<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    // الحقول المسموح بحفظها تلقائياً في قاعدة البيانات
    protected $fillable = [
        'name',
        'is_for_sale',
    ];

    /**
     * علاقة الفئة بالمنتجات (الفئة الواحدة تحتوي على منتجات متعددة)
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
