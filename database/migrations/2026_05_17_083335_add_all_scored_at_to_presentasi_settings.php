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
        Schema::table('presentasi_settings', function (Blueprint $table) {
            $table->timestamp('all_scored_at')->nullable()->after('current_slide_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presentasi_settings', function (Blueprint $table) {
            $table->dropColumn('all_scored_at');
        });
    }
};
