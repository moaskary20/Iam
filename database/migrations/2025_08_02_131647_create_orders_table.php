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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->enum('sell_method', ['shipping', 'ai', 'social'])->default('shipping');
            $table->decimal('product_price', 10, 2);
            $table->decimal('marketing_fee', 10, 2)->default(0);
            $table->decimal('system_commission', 10, 2)->default(0);
            $table->decimal('total_cost', 10, 2);
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->json('shipping_details')->nullable(); // رقم الهاتف، العنوان، البلد، طريقة الدفع
            $table->string('share_link')->nullable(); // للبيع عبر السوشيال ميديا
            $table->string('referrer_id')->nullable(); // معرف المسوق المحيل
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
