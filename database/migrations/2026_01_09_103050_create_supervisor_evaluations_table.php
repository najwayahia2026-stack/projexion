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
        Schema::create('supervisor_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('supervisor_id')->constrained('users')->onDelete('cascade');
            
            // معايير التقييم المختلفة (نظام نقاط من 1-10)
            $table->decimal('work_quality', 3, 1)->nullable()->comment('جودة العمل (1-10)');
            $table->decimal('punctuality', 3, 1)->nullable()->comment('الالتزام بالمواعيد (1-10)');
            $table->decimal('teamwork', 3, 1)->nullable()->comment('التعاون والعمل الجماعي (1-10)');
            $table->decimal('innovation', 3, 1)->nullable()->comment('الإبداع والابتكار (1-10)');
            $table->decimal('technical_skills', 3, 1)->nullable()->comment('المهارات التقنية (1-10)');
            $table->decimal('communication', 3, 1)->nullable()->comment('التواصل والتقديم (1-10)');
            
            // التقييم الشامل (نقطة واحدة من 1-10)
            $table->decimal('overall_assessment', 3, 1)->nullable()->comment('التقييم الشامل (1-10)');
            
            // الدرجة الإجمالية (محسوبة تلقائياً)
            $table->decimal('total_score', 5, 2)->nullable()->comment('الدرجة الإجمالية');
            
            // ملاحظات تفصيلية
            $table->text('strengths')->nullable()->comment('نقاط القوة');
            $table->text('weaknesses')->nullable()->comment('نقاط الضعف');
            $table->text('recommendations')->nullable()->comment('التوصيات');
            $table->text('general_comments')->nullable()->comment('ملاحظات عامة');
            
            // حالة التقييم
            $table->enum('status', ['draft', 'submitted', 'finalized'])->default('draft');
            $table->timestamp('evaluated_at')->nullable();
            
            $table->timestamps();
            
            // فهرس فريد لمنع التقييم المتكرر من نفس المشرف لنفس المشروع
            $table->unique(['project_id', 'supervisor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supervisor_evaluations');
    }
};
