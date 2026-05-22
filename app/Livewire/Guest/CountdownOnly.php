<?php

namespace App\Livewire\Guest;

use App\Livewire\Admin\PresentasiControl;
use App\Models\PresentasiSetting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Live Countdown - KIP TALKS')]
class CountdownOnly extends Component
{
    public function render()
    {
        $setting = PresentasiSetting::instance();
        
        $phase = $setting->phase ?? 'idle';
        $isPaused = $setting->is_paused;
        
        $timerStartedAt = $setting->timer_started_at ? $setting->timer_started_at->timestamp : null;
        $timerDuration = PresentasiControl::TIMER_DURATION;
        
        $countdownStartedAt = $setting->countdown_started_at ? $setting->countdown_started_at->timestamp : null;
        $countdownDuration = PresentasiControl::COUNTDOWN_DURATION;

        return view('livewire.guest.countdown-only', [
            'phase' => $phase,
            'isPaused' => $isPaused,
            'timerStartedAt' => $timerStartedAt,
            'timerDuration' => $timerDuration,
            'countdownStartedAt' => $countdownStartedAt,
            'countdownDuration' => $countdownDuration,
        ])->layoutData(['type' => 'presentasi']); // Use 'presentasi' to hide the navbar
    }
}
