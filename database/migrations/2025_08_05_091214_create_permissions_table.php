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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('اسم الصلاحية الفريد');
            $table->string('display_name')->comment('الاسم المعروض');
            $table->text('description')->nullable()->comment('وصف الصلاحية');
            $table->string('group')->default('general')->comment('مجموعة الصلاحيات');
            $table->boolean('is_active')->default(true)->comment('حالة الصلاحية');
            $table->integer('priority')->default(100)->comment('أولوية الصلاحية');
            $table->string('icon')->nullable()->comment('أيقونة الصلاحية');
            $table->timestamps();

            $table->index(['is_active', 'group']);
            $table->index(['name', 'is_active']);
            $table->index('group');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
