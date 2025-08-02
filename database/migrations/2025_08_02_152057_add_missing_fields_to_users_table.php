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
            // إضافة الأعمدة المفقودة فقط
            $table->string('verification_status')->nullable()->after('is_verified');
            $table->string('profile_photo')->nullable()->after('id_image_path');
            $table->integer('current_market_id')->default(1)->after('remember_token');
            $table->integer('current_product_index')->default(0)->after('current_market_id');
            $table->json('purchased_products')->nullable()->after('current_product_index');
            $table->json('unlocked_markets')->default(json_encode([1]))->after('purchased_products');
            $table->decimal('balance', 10, 2)->default(0)->after('unlocked_markets');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'verification_status',
                'profile_photo',
                'current_market_id',
                'current_product_index',
                'purchased_products',
                'unlocked_markets',
                'balance'
            ]);
        });
    }
};
