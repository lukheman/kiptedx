<div wire:poll.3s="loadState">
    <x-page-header title="Kontrol Presentasi" subtitle="Kelola jalannya presentasi secara real-time">
        <x-slot:actions>
            @if ($isActive)
                <div class="d-flex gap-2">
                    @if ($isPaused && $phase === 'presenting')
                        <x-button variant="primary" icon="fas fa-play" wire:click="resumePresentasi">
                            Lanjutkan
                        </x-button>
                    @elseif ($phase === 'presenting')
                        <x-button variant="outline" icon="fas fa-pause" wire:click="pausePresentasi">
                            Jeda
                        </x-button>
                    @endif
                    <x-button variant="outline" icon="fas fa-stop" wire:click="stopPresentasi"
                        onclick="confirm('Hentikan presentasi?') || event.stopImmediatePropagation()">
                        Hentikan
                    </x-button>
                </div>
            @else
                <x-button variant="primary" icon="fas fa-play" wire:click="startPresentasi">
                    Mulai Presentasi
                </x-button>
            @endif
        </x-slot:actions>
    </x-page-header>

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-alert variant="success" title="Sukses!" class="mb-4">{{ session('success') }}</x-alert>
    @endif
    @if (session('error'))
        <x-alert variant="danger" title="Error!" class="mb-4">{{ session('error') }}</x-alert>
    @endif

    {{-- Status Banner --}}
    @php
        $phaseLabels = [
            'idle' => ['color' => 'var(--text-muted)', 'text' => 'BELUM DIMULAI', 'icon' => 'fa-stop-circle'],
            'countdown' => ['color' => '#ffc107', 'text' => 'COUNTDOWN', 'icon' => 'fa-hourglass-half'],
            'intro' => ['color' => '#17a2b8', 'text' => 'PERKENALAN PESERTA', 'icon' => 'fa-user-circle'],
            'presenting' => ['color' => '#28a745', 'text' => 'SEDANG PRESENTASI', 'icon' => 'fa-broadcast-tower'],
            'scoring' => ['color' => '#e62b1e', 'text' => 'MENUNGGU PENILAIAN', 'icon' => 'fa-clipboard-check'],
        ];
        $pl = $phaseLabels[$phase] ?? $phaseLabels['idle'];
        if ($isActive && $isPaused) {
            $pl = ['color' => '#ffc107', 'text' => 'DIJEDA', 'icon' => 'fa-pause-circle'];
        }
    @endphp
    <div class="modern-card mb-4 d-flex align-items-center gap-3" style="border-left: 4px solid {{ $pl['color'] }};">
        <div class="rounded-circle d-flex align-items-center justify-content-center"
            style="width: 48px; height: 48px; background: {{ $pl['color'] }}20; flex-shrink: 0;">
            <i class="fas {{ $pl['icon'] }}"
                style="font-size: 1.25rem; color: {{ $pl['color'] }};"></i>
        </div>
        <div>
            <h6 class="mb-0" style="color: var(--text-primary);">
                Status: <strong>{{ $pl['text'] }}</strong>
            </h6>
            <small class="text-muted">
                {{ count($mahasiswaList) }} mahasiswa terdaftar &middot; {{ $juriCount }} juri
            </small>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">

            {{-- Mahasiswa Order List --}}
            <div class="modern-card">
                <h5 class="mb-4" style="color: var(--text-primary); font-weight: 600;">
                    <i class="fas fa-list-ol me-2"></i>Daftar Urutan Presentasi
                </h5>

                <div class="table-responsive">
                    <table class="table table-modern align-middle">
                        <thead>
                            <tr>
                                <th style="width: 70px;">Urutan</th>
                                <th>Nama</th>
                                <th>Tema</th>
                                <th>Penilaian</th>
                                <th style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mahasiswaList as $idx => $mhs)
                                @php
                                    $isCurrent = $isActive && $mhs['id'] == $currentMahasiswaId;
                                    $scored = $scoredStats[$mhs['id']] ?? 0;
                                @endphp
                                <tr style="{{ $isCurrent ? 'background: rgba(230,43,30,0.06);' : '' }}">
                                    <td>
                                        <span class="badge {{ $isCurrent ? 'bg-danger' : 'bg-secondary' }}" style="font-size: 0.85rem;">
                                            {{ $mhs['urutan_tampil'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if ($isCurrent)
                                                <i class="fas fa-microphone-alt text-danger" style="font-size: 0.8rem;"></i>
                                            @endif
                                            <span class="fw-semibold" style="color: var(--text-primary);">{{ $mhs['nama'] }}</span>
                                        </div>
                                        <small class="text-muted">{{ $mhs['nim'] }}</small>
                                    </td>
                                    <td style="color: var(--text-secondary);">{{ $mhs['tema']['judul'] ?? '-' }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress" style="width: 60px; height: 6px; background: var(--hover-bg);">
                                                <div class="progress-bar {{ $scored >= $juriCount && $juriCount > 0 ? 'bg-success' : 'bg-warning' }}"
                                                    style="width: {{ $juriCount > 0 ? ($scored / $juriCount) * 100 : 0 }}%;"></div>
                                            </div>
                                            <small class="text-muted">{{ $scored }}/{{ $juriCount }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($isActive)
                                            <button class="btn btn-sm {{ $isCurrent ? 'btn-danger' : 'btn-outline-secondary' }}"
                                                wire:click="goToMahasiswa({{ $mhs['id'] }})"
                                                {{ $isCurrent ? 'disabled' : '' }}>
                                                @if ($isCurrent)
                                                    <i class="fas fa-volume-up"></i>
                                                @else
                                                    <i class="fas fa-play"></i>
                                                @endif
                                            </button>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">

            {{-- Phase Control Panel --}}
            @if ($isActive && $currentMhs)
                <div class="modern-card mb-4" style="border: 2px solid var(--primary-color);">

                    {{-- Current Presenter Info --}}
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 56px; height: 56px; background: var(--primary-color); color: white; font-size: 1.5rem; font-weight: 800; flex-shrink: 0;">
                            {{ $currentMhs['urutan_tampil'] }}
                        </div>
                        <div>
                            <h5 class="mb-0" style="color: var(--text-primary); font-weight: 700;">{{ $currentMhs['nama'] }}</h5>
                            <small class="text-muted">
                                NIM: {{ $currentMhs['nim'] }}
                                @if (!empty($currentMhs['tema']))
                                    &middot; {{ $currentMhs['tema']['judul'] }}
                                @endif
                            </small>
                        </div>
                    </div>

                    <hr style="border-color: var(--border-color);">

                    {{-- Phase-specific actions --}}
                    @if ($phase === 'countdown')
                        <div class="text-center py-3">
                            <div class="mb-3" x-data="{
                                started: {{ $countdownStartedAt ?? 'null' }},
                                duration: {{ \App\Livewire\Admin\PresentasiControl::COUNTDOWN_DURATION }},
                                timeLeft: 0,
                                interval: null,
                                get formatted() {
                                    const m = Math.floor(this.timeLeft / 60);
                                    const s = this.timeLeft % 60;
                                    return String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
                                },
                                calc() {
                                    if (!this.started) { this.timeLeft = this.duration; return; }
                                    const now = Math.floor(Date.now() / 1000);
                                    const elapsed = now - this.started;
                                    this.timeLeft = Math.max(0, this.duration - elapsed);
                                    if (this.timeLeft <= 0) {
                                        clearInterval(this.interval);
                                        $wire.goToIntro();
                                    }
                                },
                                init() { this.calc(); this.interval = setInterval(() => this.calc(), 1000); }
                            }">
                                <small class="text-muted d-block mb-1">COUNTDOWN</small>
                                <div style="font-size: 2rem; font-weight: 800; font-family: monospace; color: var(--primary-color);">
                                    <span x-text="formatted"></span>
                                </div>
                            </div>
                            <button class="btn btn-primary w-100" wire:click="goToIntro">
                                <i class="fas fa-forward me-2"></i>Skip Countdown
                            </button>
                        </div>
                    @elseif ($phase === 'intro')
                        <div class="text-center py-3">
                            <p class="text-muted mb-3">
                                <i class="fas fa-user-circle me-1"></i>
                                Peserta sedang diperkenalkan di layar publik
                            </p>
                            <button class="btn btn-primary btn-lg w-100" wire:click="startPresenting">
                                <i class="fas fa-play me-2"></i>Mulai Presentasi
                            </button>
                        </div>
                    @elseif ($phase === 'presenting')
                        {{-- Timer --}}
                        <div class="text-center mb-3" x-data="{
                            started: {{ $timerStartedAt ?? 'null' }},
                            duration: {{ \App\Livewire\Admin\PresentasiControl::TIMER_DURATION }},
                            timeLeft: 0,
                            interval: null,
                            get formatted() {
                                const m = Math.floor(this.timeLeft / 60);
                                const s = this.timeLeft % 60;
                                return String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
                            },
                            calc() {
                                if (!this.started) { this.timeLeft = this.duration; return; }
                                const now = Math.floor(Date.now() / 1000);
                                const elapsed = now - this.started;
                                this.timeLeft = Math.max(0, this.duration - elapsed);
                            },
                            init() { this.calc(); this.interval = setInterval(() => this.calc(), 1000); }
                        }">
                            <small class="text-muted d-block mb-1">SISA WAKTU</small>
                            <div style="font-size: 2rem; font-weight: 800; font-family: monospace;"
                                :style="timeLeft <= 30 ? 'color: var(--primary-color);' : 'color: var(--text-primary);'">
                                <span x-text="formatted"></span>
                            </div>
                        </div>

                        {{-- Slide Control --}}
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted fw-semibold">Slide {{ $currentSlideIndex + 1 }} / {{ max(1, $slidesCount) }}</small>
                                <button class="btn btn-sm btn-outline-secondary" wire:click="resetTimer">
                                    <i class="fas fa-redo"></i> Reset Timer
                                </button>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary flex-fill" wire:click="prevSlide" {{ $currentSlideIndex <= 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button class="btn btn-primary flex-fill" wire:click="nextSlide" {{ $currentSlideIndex >= $slidesCount - 1 ? 'disabled' : '' }}>
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>

                        @if (count($slides) > 0 && isset($slides[$currentSlideIndex]))
                            <div class="text-center bg-dark rounded overflow-hidden" style="position: relative; padding: 0.5rem;">
                                <img src="{{ Storage::url($slides[$currentSlideIndex]['file_gambar']) }}"
                                     alt="Preview Slide"
                                     style="max-width: 100%; max-height: 200px; object-fit: contain; border-radius: 8px;">
                            </div>
                        @endif

                        <hr style="border-color: var(--border-color);">
                        <button class="btn btn-danger w-100" wire:click="goToScoring">
                            <i class="fas fa-clipboard-check me-2"></i>Selesai → Penilaian
                        </button>
                    @elseif ($phase === 'scoring')
                        <div class="py-3">
                            <h6 class="text-center mb-3" style="color: var(--text-primary); font-weight: 600;">
                                <i class="fas fa-clipboard-check me-2" style="color: var(--primary-color);"></i>Penilaian Juri
                            </h6>

                            @foreach ($juriScoringDetails as $juri)
                                <div class="d-flex align-items-center justify-content-between px-3 py-2 mb-2 rounded"
                                    style="background: var(--hover-bg); border: 1px solid var(--border-color);">
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width: 32px; height: 32px; border-radius: 50%;
                                            background: {{ $juri['scored'] ? '#28a745' : 'var(--border-color)' }};
                                            color: white; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">
                                            @if ($juri['scored'])
                                                <i class="fas fa-check"></i>
                                            @else
                                                <i class="fas fa-hourglass-half" style="color: var(--text-muted);"></i>
                                            @endif
                                        </div>
                                        <span style="font-weight: 500; font-size: 0.9rem; color: var(--text-primary);">{{ $juri['nama'] }}</span>
                                    </div>
                                    <span class="badge {{ $juri['scored'] ? 'bg-success' : 'bg-secondary' }}" style="font-size: 0.7rem;">
                                        {{ $juri['scored'] ? '✓' : '...' }}
                                    </span>
                                </div>
                            @endforeach

                            <hr style="border-color: var(--border-color);">

                            @php $nextIdx = $currentIndex !== null ? $currentIndex + 1 : null; @endphp
                            <button class="btn btn-primary w-100" wire:click="nextMahasiswa">
                                <i class="fas fa-arrow-right me-2"></i>
                                @if ($nextIdx !== null && $nextIdx < count($mahasiswaList))
                                    Lanjut ke Peserta Berikutnya
                                @else
                                    Selesaikan Presentasi
                                @endif
                            </button>
                        </div>
                    @endif

                    {{-- Navigation shortcuts --}}
                    @if ($phase !== 'countdown')
                        <div class="mt-3 pt-3" style="border-top: 1px solid var(--border-color);">
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-secondary btn-sm flex-fill" wire:click="prevMahasiswa" {{ $currentIndex === 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-arrow-left"></i> Sebelum
                                </button>
                                <button class="btn btn-outline-secondary btn-sm flex-fill" wire:click="nextMahasiswa" {{ $currentIndex >= count($mahasiswaList) - 1 ? 'disabled' : '' }}>
                                    Selanjutnya <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Backsound Panel --}}
            <div class="modern-card mb-4" style="border: 1px solid var(--border-color);">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">
                        <i class="fas fa-music me-2" style="color: var(--primary-color);"></i>Backsound
                    </h5>
                    <a href="{{ route('admin.backsound') }}" class="btn btn-sm btn-outline-secondary" title="Kelola Backsound">
                        <i class="fas fa-cog"></i>
                    </a>
                </div>

                {{-- Now Playing --}}
                @if ($currentBacksound)
                    <div class="d-flex align-items-center gap-3 p-3 mb-3 rounded-3"
                        style="background: linear-gradient(135deg, rgba(230,43,30,0.08), rgba(230,43,30,0.02)); border: 1px solid rgba(230,43,30,0.2);">
                        <div class="d-flex align-items-center gap-1 flex-shrink-0" style="width: 24px; height: 24px;">
                            @if ($musicPlaying)
                                <div class="eq-bar" style="width: 4px; background: var(--primary-color); border-radius: 2px; animation: eqAnim1 0.5s ease-in-out infinite alternate;"></div>
                                <div class="eq-bar" style="width: 4px; background: var(--primary-color); border-radius: 2px; animation: eqAnim2 0.6s ease-in-out infinite alternate;"></div>
                                <div class="eq-bar" style="width: 4px; background: var(--primary-color); border-radius: 2px; animation: eqAnim3 0.4s ease-in-out infinite alternate;"></div>
                                <div class="eq-bar" style="width: 4px; background: var(--primary-color); border-radius: 2px; animation: eqAnim1 0.55s ease-in-out infinite alternate;"></div>
                            @else
                                <i class="fas fa-pause" style="color: var(--text-muted); font-size: 1rem;"></i>
                            @endif
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <small class="text-muted d-block" style="font-size: 0.7rem;">{{ $musicPlaying ? 'SEDANG DIPUTAR' : 'DIJEDA' }}</small>
                            <span class="fw-semibold d-block text-truncate" style="color: var(--text-primary); font-size: 0.9rem;">{{ $currentBacksound->judul }}</span>
                        </div>
                        <div class="d-flex gap-1">
                            @if ($musicPlaying)
                                <button class="btn btn-sm btn-outline-secondary" wire:click="stopBacksound" title="Jeda">
                                    <i class="fas fa-pause"></i>
                                </button>
                            @else
                                <button class="btn btn-sm btn-primary" wire:click="resumeBacksound" title="Lanjutkan">
                                    <i class="fas fa-play"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Playlist --}}
                @if ($backsounds->count() > 0)
                    <div style="max-height: 220px; overflow-y: auto;">
                        @foreach ($backsounds as $bs)
                            <div class="d-flex align-items-center justify-content-between px-3 py-2 mb-1 rounded-2"
                                style="background: {{ $currentBacksoundId == $bs->id ? 'rgba(230,43,30,0.06)' : 'var(--hover-bg)' }}; border: 1px solid {{ $currentBacksoundId == $bs->id ? 'rgba(230,43,30,0.2)' : 'transparent' }}; transition: all 0.2s; cursor: pointer;"
                                @if ($currentBacksoundId == $bs->id && $musicPlaying)
                                    wire:click="stopBacksound"
                                @else
                                    wire:click="playBacksound({{ $bs->id }})"
                                @endif>
                                <div class="d-flex align-items-center gap-2 min-w-0">
                                    @if ($currentBacksoundId == $bs->id && $musicPlaying)
                                        <i class="fas fa-volume-up" style="color: var(--primary-color); font-size: 0.8rem; width: 16px;"></i>
                                    @else
                                        <i class="fas fa-music" style="color: var(--text-muted); font-size: 0.8rem; width: 16px;"></i>
                                    @endif
                                    <span class="text-truncate" style="font-size: 0.85rem; font-weight: 500; color: var(--text-primary);">{{ $bs->judul }}</span>
                                </div>
                                <div class="flex-shrink-0">
                                    @if ($currentBacksoundId == $bs->id && $musicPlaying)
                                        <span class="badge bg-danger" style="font-size: 0.65rem;">
                                            <i class="fas fa-stop me-1"></i>Stop
                                        </span>
                                    @else
                                        <span class="badge bg-secondary" style="font-size: 0.65rem;">
                                            <i class="fas fa-play me-1"></i>Putar
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-3" style="background: var(--hover-bg); border-radius: 8px;">
                        <i class="fas fa-music text-muted mb-2" style="font-size: 1.5rem;"></i>
                        <p class="text-muted mb-1" style="font-size: 0.85rem;">Belum ada backsound</p>
                        <a href="{{ route('admin.backsound') }}" class="btn btn-sm btn-outline-primary mt-1">
                            <i class="fas fa-plus me-1"></i>Tambah Backsound
                        </a>
                    </div>
                @endif
            </div>

            {{-- Persistent audio player (wire:ignore prevents Livewire from destroying it) --}}
            <div wire:ignore>
                <div x-data="{
                    audio: null,
                    currentSrc: '',
                    isPlaying: false,

                    play(url) {
                        if (this.currentSrc !== url) {
                            if (this.audio) { this.audio.pause(); }
                            this.audio = new Audio(url);
                            this.audio.loop = true;
                            this.audio.volume = 0.5;
                            this.currentSrc = url;
                        }
                        this.audio.play().catch(() => {});
                        this.isPlaying = true;
                    },

                    pause() {
                        if (this.audio) { this.audio.pause(); }
                        this.isPlaying = false;
                    },

                    stop() {
                        if (this.audio) {
                            this.audio.pause();
                            this.audio.currentTime = 0;
                        }
                        this.isPlaying = false;
                    },

                    init() {
                        Livewire.on('backsound-play', (data) => {
                            this.play(data[0].url);
                        });
                        Livewire.on('backsound-stop', () => {
                            this.pause();
                        });
                        Livewire.on('backsound-changed', () => {
                            this.stop();
                        });
                    }
                }">
                </div>
            </div>
        </div>
    </div>

    <style>
        .eq-bar { display: inline-block; }
        @keyframes eqAnim1 { from { height: 6px; } to { height: 20px; } }
        @keyframes eqAnim2 { from { height: 14px; } to { height: 8px; } }
        @keyframes eqAnim3 { from { height: 10px; } to { height: 22px; } }
    </style>
</div>
