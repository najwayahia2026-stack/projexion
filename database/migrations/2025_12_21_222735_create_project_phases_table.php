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
        Schema::create('project_phases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('title'); // عنوان الجزء (مثل: الفصل الأول، الباب الثاني)
            $table->text('description')->nullable();
            $table->integer('phase_number'); // رقم الجزء (1, 2, 3, ...)
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected'])->default('pending');
            $table->decimal('similarity_score', 5, 2)->nullable(); // نسبة التشابه
            $table->text('rejection_reason')->nullable(); // سبب الرفض
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_phases');
    }
};
