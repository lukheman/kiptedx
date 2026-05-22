<?php

namespace App\Livewire\Admin;

use App\Models\Backsound;
use App\Models\Juri;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use App\Models\PresentasiSetting;
use App\Models\SlidePresentasi;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Kontrol Presentasi')]
class PresentasiControl extends Component
{
    public $isActive = false;

    public $isPaused = false;

    public $phase = 'idle';

    public $currentMahasiswaId = null;

    public $mahasiswaList = [];

    public $currentSlideIndex = 0;

    public $slidesCount = 0;

    public function mount()
    {
        $this->loadState();
        $this->mahasiswaList = Mahasiswa::whereNotNull('urutan_tampil')
            ->orderBy('urutan_tampil')
            ->with('tema')
            ->get()
            ->toArray();
    }

    public const TIMER_DURATION = 300; // 5 menit (300 detik)

    public const COUNTDOWN_DURATION = 300; // 5 menit countdown awal

    public function loadState()
    {
        $setting = PresentasiSetting::instance();
        $this->isActive = $setting->is_active;
        $this->isPaused = $setting->is_paused;
        $this->phase = $setting->phase ?? 'idle';
        $this->currentMahasiswaId = $setting->current_mahasiswa_id;
        $this->currentSlideIndex = $setting->current_slide_index ?? 0;

        // Fetch slide count for current mahasiswa if active
        if ($this->isActive && $this->currentMahasiswaId) {
            $this->slidesCount = SlidePresentasi::where('mahasiswa_id', $this->currentMahasiswaId)->count();
        } else {
            $this->slidesCount = 0;
        }
    }

    public function pausePresentasi()
    {
        $setting = PresentasiSetting::instance();

        // Hitung sisa waktu dan simpan
        $remaining = self::TIMER_DURATION;
        if ($setting->timer_started_at) {
            $elapsed = now()->timestamp - $setting->timer_started_at->timestamp;
            $remaining = max(0, self::TIMER_DURATION - $elapsed);
        }

        $setting->update([
            'is_paused' => true,
            'timer_remaining' => $remaining,
            'timer_started_at' => null,
        ]);

        $this->loadState();
        session()->flash('success', 'Presentasi dijeda.');
    }

    public function resumePresentasi()
    {
        $setting = PresentasiSetting::instance();
        $remaining = $setting->timer_remaining ?? self::TIMER_DURATION;

        // Set timer_started_at mundur agar sisa waktu sesuai
        $setting->update([
            'is_paused' => false,
            'timer_started_at' => now()->subSeconds(self::TIMER_DURATION - $remaining),
            'timer_remaining' => null,
        ]);

        $this->loadState();
        session()->flash('success', 'Presentasi dilanjutkan.');
    }

    /**
     * Step 1: Admin starts the presentation session
     * Goes to COUNTDOWN phase (5 min countdown before anything starts)
     */
    public function startPresentasi()
    {
        if (count($this->mahasiswaList) === 0) {
            session()->flash('error', 'Tidak ada mahasiswa dengan urutan tampil.');

            return;
        }

        $firstMhs = $this->mahasiswaList[0];

        $setting = PresentasiSetting::instance();
        $setting->update([
            'is_active' => true,
            'is_paused' => false,
            'phase' => PresentasiSetting::PHASE_COUNTDOWN,
            'current_mahasiswa_id' => $firstMhs['id'],
            'timer_started_at' => null,
            'current_slide_index' => 0,
            'all_scored_at' => null,
            'countdown_started_at' => now(),
        ]);

        $this->loadState();
        session()->flash('success', 'Countdown dimulai! Presentasi akan segera dimulai.');
    }

    /**
     * Step 2: Admin skips countdown or countdown finishes
     * Goes to INTRO phase (show presenter photo + topic)
     */
    public function goToIntro()
    {
        $setting = PresentasiSetting::instance();
        $setting->update([
            'phase' => PresentasiSetting::PHASE_INTRO,
            'countdown_started_at' => null,
        ]);

        $this->loadState();
        session()->flash('success', 'Memperkenalkan peserta...');
    }

    /**
     * Step 3: Admin clicks "Mulai Presentasi" for current presenter
     * Goes from INTRO to PRESENTING phase (show slides + timer)
     */
    public function startPresenting()
    {
        $setting = PresentasiSetting::instance();
        $setting->update([
            'phase' => PresentasiSetting::PHASE_PRESENTING,
            'timer_started_at' => now(),
            'current_slide_index' => 0,
        ]);

        $this->loadState();
        session()->flash('success', 'Presentasi dimulai!');
    }

    /**
     * Step 4: Timer expires or admin ends presentation
     * Goes to SCORING phase (show photo + wait for jury)
     */
    public function goToScoring()
    {
        $setting = PresentasiSetting::instance();
        $setting->update([
            'phase' => PresentasiSetting::PHASE_SCORING,
            'timer_started_at' => null,
            'all_scored_at' => null,
        ]);

        $this->loadState();
        session()->flash('success', 'Menunggu penilaian juri...');
    }

    /**
     * Step 5: Admin clicks "Lanjut" → next presenter intro or finish
     */
    public function nextMahasiswa()
    {
        $currentIndex = $this->getCurrentIndex();
        if ($currentIndex !== null && $currentIndex < count($this->mahasiswaList) - 1) {
            $next = $this->mahasiswaList[$currentIndex + 1];
            $setting = PresentasiSetting::instance();
            $setting->update([
                'phase' => PresentasiSetting::PHASE_INTRO,
                'current_mahasiswa_id' => $next['id'],
                'timer_started_at' => null,
                'current_slide_index' => 0,
                'all_scored_at' => null,
            ]);
            $this->loadState();
            session()->flash('success', 'Peserta selanjutnya!');
        } else {
            // All done
            $this->stopPresentasi();
        }
    }

    public function stopPresentasi()
    {
        $setting = PresentasiSetting::instance();
        $setting->update([
            'is_active' => false,
            'phase' => PresentasiSetting::PHASE_IDLE,
            'current_mahasiswa_id' => null,
            'timer_started_at' => null,
            'current_slide_index' => 0,
            'all_scored_at' => null,
            'countdown_started_at' => null,
        ]);

        $this->loadState();
        session()->flash('success', 'Presentasi dihentikan.');
    }

    public function goToMahasiswa($mahasiswaId)
    {
        $setting = PresentasiSetting::instance();
        $setting->update([
            'phase' => PresentasiSetting::PHASE_INTRO,
            'current_mahasiswa_id' => $mahasiswaId,
            'timer_started_at' => null,
            'current_slide_index' => 0,
            'all_scored_at' => null,
        ]);

        $this->loadState();
    }

    public function prevMahasiswa()
    {
        $currentIndex = $this->getCurrentIndex();
        if ($currentIndex !== null && $currentIndex > 0) {
            $prev = $this->mahasiswaList[$currentIndex - 1];
            $this->goToMahasiswa($prev['id']);
        }
    }

    public function resetTimer()
    {
        $setting = PresentasiSetting::instance();
        $setting->update(['timer_started_at' => now()]);
        $this->loadState();
    }

    public function nextSlide()
    {
        if ($this->currentSlideIndex < $this->slidesCount - 1) {
            $setting = PresentasiSetting::instance();
            $setting->update(['current_slide_index' => $this->currentSlideIndex + 1]);
            $this->loadState();
        }
    }

    public function prevSlide()
    {
        if ($this->currentSlideIndex > 0) {
            $setting = PresentasiSetting::instance();
            $setting->update(['current_slide_index' => $this->currentSlideIndex - 1]);
            $this->loadState();
        }
    }

    protected function getCurrentIndex(): ?int
    {
        foreach ($this->mahasiswaList as $idx => $mhs) {
            if ($mhs['id'] == $this->currentMahasiswaId) {
                return $idx;
            }
        }

        return null;
    }

    public function render()
    {
        $currentIndex = $this->getCurrentIndex();
        $currentMhs = $currentIndex !== null ? $this->mahasiswaList[$currentIndex] : null;

        // Get scoring stats
        $juriCount = Juri::count();
        $scoredStats = [];
        foreach ($this->mahasiswaList as $mhs) {
            $scoredStats[$mhs['id']] = Nilai::where('mahasiswa_id', $mhs['id'])->count();
        }

        $slides = [];
        if ($this->isActive && $this->currentMahasiswaId) {
            $slides = SlidePresentasi::where('mahasiswa_id', $this->currentMahasiswaId)
                ->orderBy('urutan')
                ->get()
                ->toArray();
        }

        $setting = PresentasiSetting::instance();
        $countdownStartedAt = $setting->countdown_started_at ? $setting->countdown_started_at->timestamp : null;

        // Juri scoring details for scoring phase
        $juriScoringDetails = [];
        if ($this->isActive && $this->currentMahasiswaId) {
            $allJuri = Juri::all(['id', 'nama']);
            $scoredJuriIds = Nilai::where('mahasiswa_id', $this->currentMahasiswaId)->pluck('juri_id')->toArray();
            foreach ($allJuri as $j) {
                $juriScoringDetails[] = [
                    'id' => $j->id,
                    'nama' => $j->nama,
                    'scored' => in_array($j->id, $scoredJuriIds),
                ];
            }
        }

        $timerStartedAt = $setting->timer_started_at ? $setting->timer_started_at->timestamp : null;

        // Backsound data
        $backsounds = Backsound::orderBy('created_at', 'desc')->get();
        $currentBacksoundId = $setting->current_backsound_id;
        $musicPlaying = $setting->music_playing;
        $currentBacksound = $currentBacksoundId ? Backsound::find($currentBacksoundId) : null;

        return view('livewire.admin.presentasi-control', [
            'currentIndex' => $currentIndex,
            'currentMhs' => $currentMhs,
            'juriCount' => $juriCount,
            'scoredStats' => $scoredStats,
            'slides' => $slides,
            'countdownStartedAt' => $countdownStartedAt,
            'timerStartedAt' => $timerStartedAt,
            'juriScoringDetails' => $juriScoringDetails,
            'backsounds' => $backsounds,
            'currentBacksoundId' => $currentBacksoundId,
            'musicPlaying' => $musicPlaying,
            'currentBacksound' => $currentBacksound,
        ]);
    }

    // ========== Backsound Methods ==========

    public function playBacksound($id)
    {
        $backsound = Backsound::find($id);
        if (!$backsound) return;

        $setting = PresentasiSetting::instance();
        $setting->update([
            'current_backsound_id' => $id,
            'music_playing' => true,
        ]);
        $this->dispatch('backsound-play', ['url' => $backsound->audioUrl()]);
    }

    public function stopBacksound()
    {
        $setting = PresentasiSetting::instance();
        $setting->update(['music_playing' => false]);
        $this->dispatch('backsound-stop');
    }

    public function resumeBacksound()
    {
        $setting = PresentasiSetting::instance();
        $backsound = $setting->current_backsound_id ? Backsound::find($setting->current_backsound_id) : null;
        if ($backsound) {
            $setting->update(['music_playing' => true]);
            $this->dispatch('backsound-play', ['url' => $backsound->audioUrl()]);
        }
    }
}
