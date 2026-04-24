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
        Schema::table('project_phases', function (Blueprint $table) {
            if (!Schema::hasColumn('project_phases', 'delivery_date')) {
                $table->date('delivery_date')->nullable()->after('reviewed_at')->comment('تاريخ تسليم الجزئية المحدد من قبل المشرف');
            }
            if (!Schema::hasColumn('project_phases', 'notification_sent_at')) {
                $table->date('notification_sent_at')->nullable()->after('delivery_date')->comment('تاريخ إرسال إشعار التنبيه قبل انتهاء مدة التسليم');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_phases', function (Blueprint $table) {
            $table->dropColumn(['delivery_date', 'notification_sent_at']);
        });
    }
};
