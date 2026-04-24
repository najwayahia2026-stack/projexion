<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        // لتخزين معرف المشرف الذي حجز الطالب
        $table->unsignedBigInteger('pool_supervisor_id')->nullable()->after('specialty_id');
        
        // ربط الحقل بجدول المستخدمين كعلاقة
        $table->foreign('pool_supervisor_id')->references('id')->on('users')->onDelete('set null');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
