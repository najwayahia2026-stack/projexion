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
        Schema::table('project_sections', function (Blueprint $table) {
            $table->decimal('percentage', 5, 2)->default(0)->after('order_number'); // نسبة الجزئية من المشروع
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('percentage');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->after('status');
            $table->foreignId('rejected_by')->nullable()->constrained('users')->onDelete('set null')->after('approved_by');
            $table->text('rejection_reason')->nullable()->after('rejected_by');
            $table->timestamp('approved_at')->nullable()->after('rejection_reason');
            $table->timestamp('rejected_at')->nullable()->after('approved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_sections', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['rejected_by']);
            $table->dropColumn([
                'percentage',
                'status',
                'approved_by',
                'rejected_by',
                'rejection_reason',
                'approved_at',
                'rejected_at'
            ]);
        });
    }
};
