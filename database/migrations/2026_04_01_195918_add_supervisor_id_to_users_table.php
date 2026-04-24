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
    Schema::table('users', function (Blueprint $table) {
        // نضع الكود داخل هذا "البلوك" لكي يتعرف على المتغير table
        $table->foreignId('supervisor_id')->nullable()->after('id')->constrained('users')->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        // لحذف الربط والعمود في حال التراجع عن الهجرة
        $table->dropForeign(['supervisor_id']);
        $table->dropColumn('supervisor_id');
    });
}
};
