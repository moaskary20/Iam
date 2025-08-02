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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('market_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->json('images'); // سيحتوي على 3 صور
            $table->decimal('purchase_price', 10, 2);
            $table->decimal('expected_selling_price', 10, 2);
            $table->decimal('system_commission', 5, 2)->default(5.00); // نسبة مئوية
            $table->decimal('marketing_commission', 5, 2)->default(3.00); // نسبة مئوية
            $table->boolean('active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
