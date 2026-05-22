<?php

namespace App\Livewire\Guest;

use App\Livewire\Admin\PresentasiControl;
use App\Models\Mahasiswa;
use App\Models\PresentasiSetting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Presentasi - KIP TALKS')]
class PresentasiPublic extends Component
{
    public $lastMahasiswaId = null;

    public function render()
    {
        $setting = PresentasiSetting::instance();
        $isActive = $setting->is_active;
        $isPaused = $setting->is_paused;
        $phase = $setting->phase ?? 'idle';
        $currentMahasiswa = null;
        $slides = [];

        if ($isActive && $setting->current_mahasiswa_id) {
            $currentMahasiswa = Mahasiswa::with(['slidePresentasis' => fn ($q) => $q->orderBy('urutan'), 'tema'])
                ->find($setting->current_mahasiswa_id);

            if ($currentMahasiswa) {
                $slides = $currentMahasiswa->slidePresentasis->toArray();

                if ($this->lastMahasiswaId !== $currentMahasiswa->id) {
                    $this->lastMahasiswaId = $currentMahasiswa->id;
                }
            }
        }

        $currentSlideIndex = $setting->current_slide_index ?? 0;
        if ($currentSlideIndex >= count($slides)) {
            $currentSlideIndex = max(0, count($slides) - 1);
        }

        $timerStartedAt = $setting->timer_started_at ? $setting->timer_started_at->timestamp : null;
        $timerDuration = PresentasiControl::TIMER_DURATION;
        $countdownStartedAt = $setting->countdown_started_at ? $setting->countdown_started_at->timestamp : null;
        $countdownDuration = PresentasiControl::COUNTDOWN_DURATION;

        $mahasiswaList = Mahasiswa::whereNotNull('urutan_tampil')
            ->orderBy('urutan_tampil')
            ->get(['id', 'urutan_tampil', 'nama']);

        // Get juri scoring details for current mahasiswa
        $juriCount = \App\Models\Juri::count();
        $juriScoringDetails = [];
        if ($isActive && $setting->current_mahasiswa_id) {
            $allJuri = \App\Models\Juri::all(['id', 'nama']);
            $scoredJuriIds = \App\Models\Nilai::where('mahasiswa_id', $setting->current_mahasiswa_id)->pluck('juri_id')->toArray();
            foreach ($allJuri as $j) {
                $juriScoringDetails[] = [
                    'id' => $j->id,
                    'nama' => $j->nama,
                    'scored' => in_array($j->id, $scoredJuriIds),
                ];
            }
        }

        // Backsound
        $backsoundUrl = null;
        $musicPlaying = $setting->music_playing;
        $backsoundId = $setting->current_backsound_id;
        if ($backsoundId) {
            $backsound = \App\Models\Backsound::find($backsoundId);
            if ($backsound) {
                $backsoundUrl = $backsound->audioUrl();
            }
        }

        return view('livewire.guest.presentasi-public', [
            'isActive' => $isActive,
            'isPaused' => $isPaused,
            'phase' => $phase,
            'currentMahasiswa' => $currentMahasiswa,
            'slides' => $slides,
            'currentSlideIndex' => $currentSlideIndex,
            'timerStartedAt' => $timerStartedAt,
            'timerDuration' => $timerDuration,
            'countdownStartedAt' => $countdownStartedAt,
            'countdownDuration' => $countdownDuration,
            'mahasiswaList' => $mahasiswaList,
            'currentMahasiswaId' => $setting->current_mahasiswa_id,
            'juriCount' => $juriCount,
            'juriScoringDetails' => $juriScoringDetails,
            'backsoundUrl' => $backsoundUrl,
            'musicPlaying' => $musicPlaying,
            'backsoundId' => $backsoundId,
        ])->layoutData(['type' => 'presentasi']);
    }
}
