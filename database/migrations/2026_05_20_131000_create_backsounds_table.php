<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('backsounds', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('file_audio');
            $table->timestamps();
        });

        Schema::table('presentasi_settings', function (Blueprint $table) {
            $table->foreignId('current_backsound_id')->nullable()->after('countdown_started_at')->constrained('backsounds')->nullOnDelete();
            $table->boolean('music_playing')->default(false)->after('current_backsound_id');
        });
    }

    public function down(): void
    {
        Schema::table('presentasi_settings', function (Blueprint $table) {
            $table->dropForeign(['current_backsound_id']);
            $table->dropColumn(['current_backsound_id', 'music_playing']);
        });

        Schema::dropIfExists('backsounds');
    }
};
