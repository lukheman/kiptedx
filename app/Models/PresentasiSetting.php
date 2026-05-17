<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresentasiSetting extends Model
{
    protected $fillable = [
        'is_active',
        'is_paused',
        'current_mahasiswa_id',
        'timer_started_at',
        'timer_remaining',
        'current_slide_index',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_paused' => 'boolean',
            'timer_started_at' => 'datetime',
            'current_slide_index' => 'integer',
        ];
    }

    public function currentMahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'current_mahasiswa_id');
    }

    /**
     * Get the single settings row.
     */
    public static function instance(): self
    {
        return static::first() ?? static::create([
            'is_active' => false,
            'current_mahasiswa_id' => null,
            'timer_started_at' => null,
            'current_slide_index' => 0,
        ]);
    }
}
