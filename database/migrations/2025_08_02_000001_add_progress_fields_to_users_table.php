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
            // إضافة حقول التقدم والإنجازات
            $table->json('progress_data')->nullable()->after('email_verified_at');
            $table->integer('level')->default(1)->after('progress_data');
            $table->integer('experience_points')->default(0)->after('level');
            $table->json('achievements')->nullable()->after('experience_points');
            $table->timestamp('last_activity')->nullable()->after('achievements');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'progress_data',
                'level',
                'experience_points',
                'achievements',
                'last_activity'
            ]);
        });
    }
};