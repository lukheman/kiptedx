<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresentasiSetting extends Model
{
    // Phase constants
    const PHASE_IDLE = 'idle';
    const PHASE_COUNTDOWN = 'countdown';
    const PHASE_INTRO = 'intro';
    const PHASE_PRESENTING = 'presenting';
    const PHASE_SCORING = 'scoring';

    protected $fillable = [
        'is_active',
        'is_paused',
        'phase',
        'current_mahasiswa_id',
        'timer_started_at',
        'timer_remaining',
        'current_slide_index',
        'all_scored_at',
        'countdown_started_at',
        'current_backsound_id',
        'music_playing',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_paused' => 'boolean',
            'timer_started_at' => 'datetime',
            'current_slide_index' => 'integer',
            'all_scored_at' => 'datetime',
            'countdown_started_at' => 'datetime',
            'music_playing' => 'boolean',
        ];
    }

    public function currentMahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'current_mahasiswa_id');
    }

    public function currentBacksound()
    {
        return $this->belongsTo(Backsound::class, 'current_backsound_id');
    }

    /**
     * Get the single settings row.
     */
    public static function instance(): self
    {
        return static::first() ?? static::create([
            'is_active' => false,
            'phase' => self::PHASE_IDLE,
            'current_mahasiswa_id' => null,
            'timer_started_at' => null,
            'current_slide_index' => 0,
        ]);
    }
}
