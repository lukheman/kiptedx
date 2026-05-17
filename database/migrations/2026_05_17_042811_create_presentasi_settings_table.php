<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presentasi_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(false);
            $table->foreignId('current_mahasiswa_id')->nullable()->constrained('mahasiswas')->nullOnDelete();
            $table->timestamp('timer_started_at')->nullable();
            $table->timestamps();
        });

        // Insert default row
        DB::table('presentasi_settings')->insert([
            'is_active' => false,
            'current_mahasiswa_id' => null,
            'timer_started_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('presentasi_settings');
    }
};
