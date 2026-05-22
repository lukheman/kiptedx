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

    <div class="row g-4">
        {{-- Left Column: MAIN CONTROLS --}}
        <div class="col-12 col-lg-7 col-xl-8">
            {{-- Phase Control Panel --}}
            @if ($isActive && $currentMhs)
                <div class="modern-card mb-4" style="border: 2px solid var(--primary-color); box-shadow: 0 10px 30px rgba(230,43,30,0.1);">

                    {{-- Current Presenter Info --}}
                    <div class="d-flex align-items-center gap-4 mb-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 70px; height: 70px; background: var(--primary-color); color: white; font-size: 2rem; font-weight: 800; flex-shrink: 0; box-shadow: 0 5px 15px rgba(230,43,30,0.3);">
                            {{ $currentMhs['urutan_tampil'] }}
                        </div>
                        <div>
                            <h3 class="mb-1" style="color: var(--text-primary); font-weight: 800;">{{ $currentMhs['nama'] }}</h3>
                            <p class="text-muted mb-0" style="font-size: 1.1rem;">
                                NIM: {{ $currentMhs['nim'] }}
                                @if (!empty($currentMhs['tema']))
                                    &middot; <span style="color: var(--primary-color); font-weight: 600;">{{ $currentMhs['tema']['judul'] }}</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <hr style="border-color: var(--border-color); margin-bottom: 2rem;">

                    {{-- Phase-specific actions --}}
                    @if ($phase === 'countdown')
                        <div class="text-center py-4">
                            <div class="mb-4" x-data="{
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
                                <h5 class="text-muted d-block mb-2" style="font-weight: 600; letter-spacing: 2px;">COUNTDOWN</h5>
                                <div style="font-size: 5rem; font-weight: 900; font-family: monospace; color: var(--primary-color); line-height: 1;">
                                    <span x-text="formatted"></span>
                                </div>
                            </div>
                            <button class="btn btn-primary btn-lg w-100 py-3" style="font-size: 1.25rem; font-weight: 700;" wire:click="goToIntro">
                                <i class="fas fa-forward me-2"></i>Skip Countdown
                            </button>
                        </div>

                    @elseif ($phase === 'intro')
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-users" style="font-size: 4rem; color: #17a2b8;"></i>
                            </div>
                            <h4 class="mb-4" style="color: var(--text-primary);">Peserta sedang diperkenalkan di layar publik...</h4>
                            <button class="btn btn-primary btn-lg w-100 py-3" style="font-size: 1.25rem; font-weight: 700;" wire:click="startPresenting">
                                <i class="fas fa-play me-2"></i>Mulai Sesi Presentasi Sekarang
                            </button>
                        </div>

                    @elseif ($phase === 'presenting')
                        <div class="row align-items-center g-4 mb-4">
                            {{-- Timer --}}
                            <div class="col-md-5 text-center" style="border-right: 2px dashed var(--border-color);">
                                <div x-data="{
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
                                    <h6 class="text-muted d-block mb-3" style="font-weight: 700; letter-spacing: 1px;">SISA WAKTU PRESENTASI</h6>
                                    <div style="font-size: 4.5rem; font-weight: 900; font-family: monospace; line-height: 1;"
                                        :style="timeLeft <= 30 ? 'color: var(--primary-color);' : 'color: var(--text-primary);'">
                                        <span x-text="formatted"></span>
                                    </div>
                                    <button class="btn btn-sm btn-outline-secondary mt-3" wire:click="resetTimer">
                                        <i class="fas fa-redo me-1"></i>Reset Timer
                                    </button>
                                </div>
                            </div>

                            {{-- Slide Control --}}
                            <div class="col-md-7">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0 text-muted" style="font-weight: 700;">KONTROL SLIDE</h6>
                                    <span class="badge bg-secondary" style="font-size: 0.9rem;">Slide {{ $currentSlideIndex + 1 }} / {{ max(1, $slidesCount) }}</span>
                                </div>
                                <div class="d-flex gap-3 mb-3">
                                    <button class="btn btn-outline-primary flex-fill py-4" wire:click="prevSlide" {{ $currentSlideIndex <= 0 ? 'disabled' : '' }} style="border-width: 2px;">
                                        <i class="fas fa-chevron-left fa-2x"></i>
                                    </button>
                                    <button class="btn btn-primary flex-fill py-4" wire:click="nextSlide" {{ $currentSlideIndex >= $slidesCount - 1 ? 'disabled' : '' }}>
                                        <i class="fas fa-chevron-right fa-2x"></i>
                                    </button>
                                </div>
                                @if (count($slides) > 0 && isset($slides[$currentSlideIndex]))
                                    <div class="bg-dark rounded overflow-hidden shadow-sm" style="position: relative; padding: 0.5rem; height: 160px; display: flex; align-items: center; justify-content: center;">
                                        <img src="{{ Storage::url($slides[$currentSlideIndex]['file_gambar']) }}"
                                             alt="Preview Slide"
                                             style="max-width: 100%; max-height: 100%; object-fit: contain; border-radius: 6px;">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <hr style="border-color: var(--border-color); margin-top: 2rem; margin-bottom: 1.5rem;">
                        <button class="btn btn-danger btn-lg w-100 py-3" style="font-size: 1.25rem; font-weight: 700;" wire:click="goToScoring">
                            <i class="fas fa-clipboard-check me-2"></i>Selesai & Lanjut Penilaian Juri
                        </button>

                    @elseif ($phase === 'scoring')
                        <div class="py-4">
                            <h4 class="text-center mb-4" style="color: var(--text-primary); font-weight: 800;">
                                <i class="fas fa-star me-2" style="color: #ffc107;"></i>Status Penilaian Juri
                            </h4>

                            <div class="row g-3 mb-4">
                                @foreach ($juriScoringDetails as $juri)
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center justify-content-between px-4 py-3 rounded"
                                            style="background: var(--hover-bg); border: 2px solid {{ $juri['scored'] ? '#28a745' : 'var(--border-color)' }};">
                                            <div class="d-flex align-items-center gap-3">
                                                <div style="width: 40px; height: 40px; border-radius: 50%;
                                                    background: {{ $juri['scored'] ? '#28a745' : 'var(--bg-white)' }};
                                                    border: 2px solid {{ $juri['scored'] ? '#28a745' : 'var(--text-muted)' }};
                                                    color: {{ $juri['scored'] ? 'white' : 'var(--text-muted)' }}; 
                                                    display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                                                    @if ($juri['scored'])
                                                        <i class="fas fa-check"></i>
                                                    @else
                                                        <i class="fas fa-hourglass-half"></i>
                                                    @endif
                                                </div>
                                                <span style="font-weight: 700; font-size: 1.1rem; color: var(--text-primary);">{{ $juri['nama'] }}</span>
                                            </div>
                                            <span class="badge {{ $juri['scored'] ? 'bg-success' : 'bg-secondary' }}" style="font-size: 0.8rem; padding: 0.5rem 0.8rem;">
                                                {{ $juri['scored'] ? 'Sudah Menilai' : 'Menunggu' }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <hr style="border-color: var(--border-color); margin-bottom: 1.5rem;">

                            @php $nextIdx = $currentIndex !== null ? $currentIndex + 1 : null; @endphp
                            <button class="btn btn-primary btn-lg w-100 py-3" style="font-size: 1.25rem; font-weight: 700;" wire:click="nextMahasiswa">
                                <i class="fas fa-arrow-right me-2"></i>
                                @if ($nextIdx !== null && $nextIdx < count($mahasiswaList))
                                    Pindah ke Peserta Selanjutnya
                                @else
                                    Selesaikan Seluruh Sesi Presentasi
                                @endif
                            </button>
                        </div>
                    @endif

                    {{-- Navigation shortcuts --}}
                    @if ($phase !== 'countdown')
                        <div class="mt-4 pt-3" style="border-top: 1px dashed var(--border-color);">
                            <div class="d-flex gap-3">
                                <button class="btn btn-outline-secondary flex-fill" wire:click="prevMahasiswa" {{ $currentIndex === 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Peserta Sebelumnya
                                </button>
                                <button class="btn btn-outline-secondary flex-fill" wire:click="nextMahasiswa" {{ $currentIndex >= count($mahasiswaList) - 1 ? 'disabled' : '' }}>
                                    Lompat ke Peserta Berikutnya <i class="fas fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="modern-card mb-4 text-center py-5">
                    <i class="fas fa-play-circle mb-3" style="font-size: 4rem; color: var(--text-muted);"></i>
                    <h4 style="color: var(--text-primary); font-weight: 700;">Presentasi Belum Dimulai</h4>
                    <p class="text-muted">Klik tombol "Mulai Presentasi" di sudut kanan atas untuk memulai.</p>
                </div>
            @endif
        </div>

        {{-- Right Column: SIDEBAR (Backsound & List) --}}
        <div class="col-12 col-lg-5 col-xl-4">
            
            {{-- Backsound Panel --}}
            <div class="modern-card mb-4" style="border: 2px solid var(--border-color);">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0" style="color: var(--text-primary); font-weight: 700;">
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
                        <div class="d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px; background: white; border-radius: 50%; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                            @if ($musicPlaying)
                                <div class="d-flex align-items-end gap-1" style="height: 14px;">
                                    <div class="eq-bar" style="width: 3px; background: var(--primary-color); border-radius: 2px; animation: eqAnim1 0.5s ease-in-out infinite alternate;"></div>
                                    <div class="eq-bar" style="width: 3px; background: var(--primary-color); border-radius: 2px; animation: eqAnim2 0.6s ease-in-out infinite alternate;"></div>
                                    <div class="eq-bar" style="width: 3px; background: var(--primary-color); border-radius: 2px; animation: eqAnim3 0.4s ease-in-out infinite alternate;"></div>
                                </div>
                            @else
                                <i class="fas fa-pause" style="color: var(--primary-color); font-size: 1rem;"></i>
                            @endif
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <small class="text-muted d-block" style="font-size: 0.7rem; font-weight: 700; letter-spacing: 1px;">{{ $musicPlaying ? 'SEDANG DIPUTAR' : 'DIJEDA' }}</small>
                            <span class="fw-bold d-block text-truncate" style="color: var(--text-primary); font-size: 1rem;">{{ $currentBacksound->judul }}</span>
                        </div>
                        <div class="d-flex gap-1">
                            @if ($musicPlaying)
                                <button class="btn btn-outline-danger btn-sm" wire:click="stopBacksound" title="Jeda" style="width: 36px; height: 36px; border-radius: 50%;">
                                    <i class="fas fa-pause"></i>
                                </button>
                            @else
                                <button class="btn btn-primary btn-sm" wire:click="resumeBacksound" title="Lanjutkan" style="width: 36px; height: 36px; border-radius: 50%;">
                                    <i class="fas fa-play"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Playlist --}}
                @if ($backsounds->count() > 0)
                    <div style="max-height: 250px; overflow-y: auto; padding-right: 5px;" class="custom-scrollbar">
                        @foreach ($backsounds as $bs)
                            <div class="d-flex align-items-center justify-content-between px-3 py-2 mb-2 rounded"
                                style="background: {{ $currentBacksoundId == $bs->id ? 'rgba(230,43,30,0.06)' : 'var(--bg-white)' }}; border: 1px solid {{ $currentBacksoundId == $bs->id ? 'rgba(230,43,30,0.2)' : 'var(--border-color)' }}; transition: all 0.2s; cursor: pointer;"
                                @if ($currentBacksoundId == $bs->id && $musicPlaying)
                                    wire:click="stopBacksound"
                                @else
                                    wire:click="playBacksound({{ $bs->id }})"
                                @endif>
                                <div class="d-flex align-items-center gap-2 min-w-0">
                                    @if ($currentBacksoundId == $bs->id && $musicPlaying)
                                        <i class="fas fa-volume-up" style="color: var(--primary-color); width: 20px;"></i>
                                    @else
                                        <i class="fas fa-music" style="color: var(--text-muted); width: 20px;"></i>
                                    @endif
                                    <span class="text-truncate" style="font-size: 0.95rem; font-weight: 500; color: var(--text-primary);">{{ $bs->judul }}</span>
                                </div>
                                <div class="flex-shrink-0">
                                    @if ($currentBacksoundId == $bs->id && $musicPlaying)
                                        <span class="badge bg-danger" style="font-size: 0.7rem;">STOP</span>
                                    @else
                                        <span class="badge bg-secondary" style="font-size: 0.7rem;">PLAY</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4" style="background: var(--hover-bg); border-radius: 12px;">
                        <i class="fas fa-music text-muted mb-2" style="font-size: 2rem;"></i>
                        <p class="text-muted mb-2" style="font-size: 0.9rem;">Belum ada backsound</p>
                        <a href="{{ route('admin.backsound') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus me-1"></i>Tambah
                        </a>
                    </div>
                @endif
            </div>

            {{-- Mahasiswa Order List (Simplified for Sidebar) --}}
            <div class="modern-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0" style="color: var(--text-primary); font-weight: 700;">
                        <i class="fas fa-list-ol me-2" style="color: var(--primary-color);"></i>Daftar Peserta
                    </h5>
                    <span class="badge bg-primary rounded-pill">{{ count($mahasiswaList) }}</span>
                </div>
                
                <div style="max-height: 400px; overflow-y: auto; padding-right: 5px;" class="custom-scrollbar">
                    @foreach ($mahasiswaList as $idx => $mhs)
                        @php
                            $isCurrent = $isActive && $mhs['id'] == $currentMahasiswaId;
                            $scored = $scoredStats[$mhs['id']] ?? 0;
                            $allScored = $scored >= $juriCount && $juriCount > 0;
                        @endphp
                        <div class="d-flex align-items-center p-2 mb-2 rounded border" style="background: {{ $isCurrent ? 'rgba(230,43,30,0.05)' : 'var(--bg-white)' }}; border-color: {{ $isCurrent ? 'var(--primary-color) !important' : 'var(--border-color)' }}; transition: all 0.2s;">
                            <div class="text-center me-3" style="min-width: 36px;">
                                <span class="badge {{ $isCurrent ? 'bg-danger' : 'bg-secondary' }} rounded-pill" style="font-size: 0.85rem; padding: 0.4rem 0.6rem;">
                                    {{ $mhs['urutan_tampil'] }}
                                </span>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <h6 class="mb-0 text-truncate" style="font-size: 0.95rem; color: var(--text-primary); {{ $isCurrent ? 'font-weight: 800;' : 'font-weight: 600;' }}">
                                    @if ($isCurrent)
                                        <i class="fas fa-play text-danger me-1" style="font-size: 0.7rem;"></i>
                                    @endif
                                    {{ $mhs['nama'] }}
                                </h6>
                                <div class="d-flex align-items-center mt-1">
                                    <div class="progress flex-grow-1 me-2" style="height: 5px; background: var(--border-color);">
                                        <div class="progress-bar {{ $allScored ? 'bg-success' : 'bg-warning' }}" style="width: {{ $juriCount > 0 ? ($scored / $juriCount) * 100 : 0 }}%;"></div>
                                    </div>
                                    <small class="text-muted" style="font-size: 0.7rem; font-weight: 600;">{{ $scored }}/{{ $juriCount }}</small>
                                </div>
                            </div>
                            <div class="ms-2">
                                @if ($isActive && !$isCurrent)
                                    <button class="btn btn-sm btn-light border shadow-sm" wire:click="goToMahasiswa({{ $mhs['id'] }})" title="Lompat ke peserta ini">
                                        <i class="fas fa-share text-secondary"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
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
