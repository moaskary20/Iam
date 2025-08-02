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
            $table->integer('current_market_id')->default(1)->after('remember_token');
            $table->integer('current_product_index')->default(0)->after('current_market_id');
            $table->json('purchased_products')->nullable()->after('current_product_index');
            $table->json('unlocked_markets')->default(json_encode([1]))->after('purchased_products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['current_market_id', 'current_product_index', 'purchased_products', 'unlocked_markets']);
        });
    }
};
