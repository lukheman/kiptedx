<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presentasi_settings', function (Blueprint $table) {
            $table->string('phase')->default('idle')->after('is_active');
            $table->timestamp('countdown_started_at')->nullable()->after('all_scored_at');
        });
    }

    public function down(): void
    {
        Schema::table('presentasi_settings', function (Blueprint $table) {
            $table->dropColumn(['phase', 'countdown_started_at']);
        });
    }
};
