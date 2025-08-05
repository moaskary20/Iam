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
            // التحقق من وجود الأعمدة قبل إضافتها
            if (!Schema::hasColumn('users', 'verification_status')) {
                $table->string('verification_status')->nullable()->after('is_verified');
            }
            if (!Schema::hasColumn('users', 'profile_photo')) {
                $table->string('profile_photo')->nullable()->after('id_image_path');
            }
            if (!Schema::hasColumn('users', 'current_market_id')) {
                $table->integer('current_market_id')->default(1)->after('remember_token');
            }
            if (!Schema::hasColumn('users', 'current_product_index')) {
                $table->integer('current_product_index')->default(0)->after('current_market_id');
            }
            if (!Schema::hasColumn('users', 'purchased_products')) {
                $table->json('purchased_products')->nullable()->after('current_product_index');
            }
            if (!Schema::hasColumn('users', 'unlocked_markets')) {
                $table->json('unlocked_markets')->nullable()->after('purchased_products');
            }
            if (!Schema::hasColumn('users', 'balance')) {
                $table->decimal('balance', 10, 2)->default(0)->after('unlocked_markets');
            }
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
