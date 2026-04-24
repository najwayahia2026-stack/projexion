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
        Schema::table('projects', function (Blueprint $table) {
            $table->integer('phases_count')->nullable()->after('progress_percentage')->comment('عدد الجزئيات المحدد من قبل المشرف');
            $table->json('phases_delivery_dates')->nullable()->after('phases_count')->comment('تواريخ تسليم الجزئيات');
            $table->timestamp('last_archived_at')->nullable()->after('archived_at')->comment('تاريخ آخر أرشفة من المشرف');
            $table->foreignId('last_archived_by')->nullable()->after('last_archived_at')->constrained('users')->onDelete('set null')->comment('من قام بآخر أرشفة');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['last_archived_by']);
            $table->dropColumn(['phases_count', 'phases_delivery_dates', 'last_archived_at', 'last_archived_by']);
        });
    }
};
