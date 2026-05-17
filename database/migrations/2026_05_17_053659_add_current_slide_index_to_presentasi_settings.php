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
            $table->integer('current_slide_index')->default(0)->after('timer_remaining');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presentasi_settings', function (Blueprint $table) {
            $table->dropColumn('current_slide_index');
        });
    }
};
