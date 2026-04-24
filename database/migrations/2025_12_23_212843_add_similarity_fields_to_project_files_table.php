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
        Schema::table('project_files', function (Blueprint $table) {
            $table->enum('file_type', ['word', 'pdf'])->nullable()->after('type');
            $table->text('extracted_text')->nullable()->after('file_type');
            $table->decimal('similarity_score', 5, 2)->nullable()->after('extracted_text');
            $table->json('similar_files')->nullable()->after('similarity_score');
            $table->enum('status', ['pending', 'checked', 'approved', 'rejected'])->default('pending')->after('similar_files');
            $table->text('rejection_reason')->nullable()->after('status');
            $table->decimal('ai_probability', 5, 2)->nullable()->after('rejection_reason')->comment('نسبة احتمالية أن يكون الملف معمول بال AI');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_files', function (Blueprint $table) {
            $table->dropColumn([
                'file_type',
                'extracted_text',
                'similarity_score',
                'similar_files',
                'status',
                'rejection_reason',
                'ai_probability'
            ]);
        });
    }
};
