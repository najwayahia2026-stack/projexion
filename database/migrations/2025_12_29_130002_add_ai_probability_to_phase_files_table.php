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
        Schema::table('phase_files', function (Blueprint $table) {
            $table->decimal('ai_probability', 5, 2)->nullable()->after('similarity_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('phase_files', function (Blueprint $table) {
            $table->dropColumn('ai_probability');
        });
    }
};
