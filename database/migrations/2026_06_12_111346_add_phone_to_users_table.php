<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // إضافة حقل التليفون بعد حقل الاسم ويكون فريد (Unique) وغير إجباري في البداية عشان الحسابات القديمة ما تضربش
            $table->string('phone')->nullable()->unique()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // حذف الحقل لو عملنا Rollback
            $table->dropColumn('phone');
        });
    }
};
