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
    // 3. جدول مراقبة سحب العمال الفوري للمصنع
    Schema::create('stock_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // العامل اللي سحب
        $table->foreignId('product_id')->constrained()->onDelete('cascade'); // المنتج المسحوب
        $table->integer('quantity'); // الكمية
        $table->string('notes')->nullable(); // ملاحظات التشغيل
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_logs');
    }
};
