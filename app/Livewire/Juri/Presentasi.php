<?php

namespace App\Livewire\Juri;

use App\Livewire\Admin\PresentasiControl;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use App\Models\PresentasiSetting;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Presentasi - KIP TALKS')]
class Presentasi extends Component
{
    public $lastMahasiswaId = null;

    public $nilai = '';

    public $catatan = '';

    public $alreadyScored = false;

    public function loadScoreState($mahasiswaId)
    {
        $juri = Auth::guard('juri')->user();
        $existing = Nilai::where('juri_id', $juri->id)
            ->where('mahasiswa_id', $mahasiswaId)
            ->first();

        $this->alreadyScored = $existing !== null;
        if ($existing) {
            $this->nilai = $existing->nilai;
            $this->catatan = $existing->catatan ?? '';
        } else {
            $this->nilai = '';
            $this->catatan = '';
        }
    }

    public function submitNilai()
    {
        $this->validate([
            'nilai' => 'required|integer|min:1|max:100',
        ], [
            'nilai.required' => 'Nilai wajib diisi.',
            'nilai.integer' => 'Nilai harus berupa angka.',
            'nilai.min' => 'Nilai minimal 1.',
            'nilai.max' => 'Nilai maksimal 100.',
        ]);

        $setting = PresentasiSetting::instance();
        if (! $setting->is_active || ! $setting->current_mahasiswa_id) {
            session()->flash('error', 'Presentasi belum dimulai.');

            return;
        }

        $juri = Auth::guard('juri')->user();

        Nilai::updateOrCreate(
            [
                'juri_id' => $juri->id,
                'mahasiswa_id' => $setting->current_mahasiswa_id,
            ],
            [
                'nilai' => $this->nilai,
                'catatan' => $this->catatan,
            ]
        );

        $this->alreadyScored = true;
        session()->flash('success_nilai', 'Nilai berhasil disimpan!');
    }

    public function render()
    {
        $setting = PresentasiSetting::instance();
        $isActive = $setting->is_active;
        $phase = $setting->phase ?? 'idle';
        $currentMahasiswa = null;
        $slides = [];

        if ($isActive && $setting->current_mahasiswa_id) {
            $currentMahasiswa = Mahasiswa::with(['slidePresentasis' => fn ($q) => $q->orderBy('urutan'), 'tema'])
                ->find($setting->current_mahasiswa_id);

            if ($currentMahasiswa) {
                $slides = $currentMahasiswa->slidePresentasis->toArray();

                // Only reload score state when mahasiswa changes
                if ($this->lastMahasiswaId !== $currentMahasiswa->id) {
                    $this->loadScoreState($currentMahasiswa->id);
                    $this->lastMahasiswaId = $currentMahasiswa->id;
                }
            }
        }

        $currentSlideIndex = $setting->current_slide_index ?? 0;
        if ($currentSlideIndex >= count($slides)) {
            $currentSlideIndex = max(0, count($slides) - 1);
        }

        // Pass timer_started_at as Unix timestamp for client-side calculation
        $timerStartedAt = $setting->timer_started_at ? $setting->timer_started_at->timestamp : null;
        $timerDuration = PresentasiControl::TIMER_DURATION;
        $countdownStartedAt = $setting->countdown_started_at ? $setting->countdown_started_at->timestamp : null;
        $countdownDuration = PresentasiControl::COUNTDOWN_DURATION;

        // Get ordered list for navigation pills
        $mahasiswaList = Mahasiswa::whereNotNull('urutan_tampil')
            ->orderBy('urutan_tampil')
            ->get(['id', 'urutan_tampil', 'nama']);

        // Get juri scoring details for current mahasiswa
        $juriCount = \App\Models\Juri::count();
        $juriScoringDetails = [];
        if ($isActive && $setting->current_mahasiswa_id) {
            $allJuri = \App\Models\Juri::all(['id', 'nama']);
            $scoredJuriIds = Nilai::where('mahasiswa_id', $setting->current_mahasiswa_id)->pluck('juri_id')->toArray();
            foreach ($allJuri as $j) {
                $juriScoringDetails[] = [
                    'id' => $j->id,
                    'nama' => $j->nama,
                    'scored' => in_array($j->id, $scoredJuriIds),
                ];
            }
        }

        $juriId = Auth::guard('juri')->id();
        $scoredIds = Nilai::where('juri_id', $juriId)->pluck('mahasiswa_id')->toArray();

        return view('livewire.juri.presentasi', [
            'isActive' => $isActive,
            'isPaused' => $setting->is_paused,
            'phase' => $phase,
            'currentMahasiswa' => $currentMahasiswa,
            'slides' => $slides,
            'currentSlideIndex' => $currentSlideIndex,
            'timerStartedAt' => $timerStartedAt,
            'timerDuration' => $timerDuration,
            'timerRemaining' => $setting->timer_remaining,
            'countdownStartedAt' => $countdownStartedAt,
            'countdownDuration' => $countdownDuration,
            'mahasiswaList' => $mahasiswaList,
            'scoredIds' => $scoredIds,
            'currentMahasiswaId' => $setting->current_mahasiswa_id,
            'juriCount' => $juriCount,
            'juriScoringDetails' => $juriScoringDetails,
        ]);
    }
}
