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
            // إضافة حقول الرصيد الإضافية فقط (balance موجود بالفعل)
            if (!Schema::hasColumn('users', 'balance_visible')) {
                $table->boolean('balance_visible')->default(true)->after('balance');
            }
            if (!Schema::hasColumn('users', 'last_balance_update')) {
                $table->timestamp('last_balance_update')->nullable()->after('balance_visible');
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
                'balance_visible',
                'last_balance_update'
            ]);
        });
    }
};