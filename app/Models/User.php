<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles, Notifiable;

    // الحقول المسموح بحفظها تلقائياً في قاعدة البيانات
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone', // ← التعديل: ضفنا التليفون هنا عشان السيستم يقبله فوراً أثناء التسجيل
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }
}
