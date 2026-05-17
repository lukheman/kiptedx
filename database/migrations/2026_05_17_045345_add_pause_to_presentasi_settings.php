<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presentasi_settings', function (Blueprint $table) {
            $table->boolean('is_paused')->default(false)->after('is_active');
            $table->unsignedInteger('timer_remaining')->nullable()->after('timer_started_at');
        });
    }

    public function down(): void
    {
        Schema::table('presentasi_settings', function (Blueprint $table) {
            $table->dropColumn(['is_paused', 'timer_remaining']);
        });
    }
};
