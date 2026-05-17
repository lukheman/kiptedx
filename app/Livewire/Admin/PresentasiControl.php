<?php

namespace App\Livewire\Admin;

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

    public const TIMER_DURATION = 20; // detik (ubah ke 180 untuk produksi)

    public function loadState()
    {
        $setting = PresentasiSetting::instance();
        $this->isActive = $setting->is_active;
        $this->isPaused = $setting->is_paused;
        $this->currentMahasiswaId = $setting->current_mahasiswa_id;
        $this->currentSlideIndex = $setting->current_slide_index ?? 0;

        // Fetch slide count for current mahasiswa if active
        if ($this->isActive && $this->currentMahasiswaId) {
            $this->slidesCount = SlidePresentasi::where('mahasiswa_id', $this->currentMahasiswaId)->count();
        } else {
            $this->slidesCount = 0;
        }

        // Auto-advance: jika aktif, TIDAK dijeda, timer habis, dan semua juri sudah menilai
        if ($this->isActive && ! $this->isPaused && $this->currentMahasiswaId && $setting->timer_started_at) {
            $elapsed = now()->timestamp - $setting->timer_started_at->timestamp;
            $timerExpired = $elapsed >= self::TIMER_DURATION;

            $juriCount = Juri::count();
            $scoredCount = Nilai::where('mahasiswa_id', $this->currentMahasiswaId)->count();
            $allScored = $juriCount > 0 && $scoredCount >= $juriCount;

            if ($timerExpired && $allScored) {
                $this->autoAdvance();
            }
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

    protected function autoAdvance()
    {
        $currentIndex = $this->getCurrentIndex();
        if ($currentIndex !== null && $currentIndex < count($this->mahasiswaList) - 1) {
            $next = $this->mahasiswaList[$currentIndex + 1];
            $setting = PresentasiSetting::instance();
            $setting->update([
                'current_mahasiswa_id' => $next['id'],
                'timer_started_at' => now(),
                'current_slide_index' => 0,
            ]);
            $this->currentMahasiswaId = $next['id'];
            $this->currentSlideIndex = 0;
        } else {
            // Semua mahasiswa sudah selesai
            $setting = PresentasiSetting::instance();
            $setting->update([
                'is_active' => false,
                'current_mahasiswa_id' => null,
                'timer_started_at' => null,
                'current_slide_index' => 0,
            ]);
            $this->isActive = false;
            $this->currentMahasiswaId = null;
            $this->currentSlideIndex = 0;
        }
    }

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
            'current_mahasiswa_id' => $firstMhs['id'],
            'timer_started_at' => now(),
            'current_slide_index' => 0,
        ]);

        $this->loadState();
        session()->flash('success', 'Presentasi dimulai!');
    }

    public function stopPresentasi()
    {
        $setting = PresentasiSetting::instance();
        $setting->update([
            'is_active' => false,
            'current_mahasiswa_id' => null,
            'timer_started_at' => null,
            'current_slide_index' => 0,
        ]);

        $this->loadState();
        session()->flash('success', 'Presentasi dihentikan.');
    }

    public function goToMahasiswa($mahasiswaId)
    {
        $setting = PresentasiSetting::instance();
        $setting->update([
            'current_mahasiswa_id' => $mahasiswaId,
            'timer_started_at' => now(),
            'current_slide_index' => 0,
        ]);

        $this->loadState();
    }

    public function nextMahasiswa()
    {
        $currentIndex = $this->getCurrentIndex();
        if ($currentIndex !== null && $currentIndex < count($this->mahasiswaList) - 1) {
            $next = $this->mahasiswaList[$currentIndex + 1];
            $this->goToMahasiswa($next['id']);
        }
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

        return view('livewire.admin.presentasi-control', [
            'currentIndex' => $currentIndex,
            'currentMhs' => $currentMhs,
            'juriCount' => $juriCount,
            'scoredStats' => $scoredStats,
            'slides' => $slides,
        ]);
    }
}
