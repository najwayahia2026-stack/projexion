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
        Schema::table('similarity_checks', function (Blueprint $table) {
            $table->text('source_comparison')->nullable()->after('details');
            $table->json('comparison_sources')->nullable()->after('source_comparison');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('similarity_checks', function (Blueprint $table) {
            $table->dropColumn(['source_comparison', 'comparison_sources']);
        });
    }
};
