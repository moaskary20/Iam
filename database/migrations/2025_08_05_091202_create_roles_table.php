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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('اسم الدور الفريد');
            $table->string('display_name')->comment('الاسم المعروض');
            $table->text('description')->nullable()->comment('وصف الدور');
            $table->boolean('is_active')->default(true)->comment('حالة الدور');
            $table->integer('priority')->default(100)->comment('أولوية الدور (الأقل = أولوية أعلى)');
            $table->string('color', 7)->default('#3B82F6')->comment('لون الدور');
            $table->timestamps();

            $table->index(['is_active', 'priority']);
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
