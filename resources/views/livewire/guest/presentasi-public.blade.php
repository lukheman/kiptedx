<div wire:poll>
    @if (!$isActive)
        <div class="d-flex justify-content-center align-items-center" style="min-height: 70vh;">
            <div class="text-center">
                <div class="mb-4" style="font-size: 4rem; color: var(--text-muted);">
                    <i class="fas fa-hourglass-half fa-spin" style="animation-duration: 3s;"></i>
                </div>
                <h2 style="color: var(--text-primary);">Menunggu Presentasi Dimulai</h2>
                <p class="text-muted">Presentasi belum dimulai.<br>Halaman ini akan otomatis diperbarui.</p>
            </div>
        </div>
    @elseif ($isPaused)
        <div class="d-flex justify-content-center align-items-center" style="min-height: 70vh;">
            <div class="text-center">
                <div class="mb-4" style="font-size: 4rem; color: #ffc107;">
                    <i class="fas fa-pause-circle"></i>
                </div>
                <h2 style="color: var(--text-primary);">Presentasi Dijeda</h2>
                <p class="text-muted">Presentasi sedang dijeda oleh admin.</p>
            </div>
        </div>
    @elseif (!$currentMahasiswa)
        <div class="d-flex justify-content-center align-items-center" style="min-height: 70vh;">
            <div class="text-center">
                <div style="font-size: 4rem; color: var(--primary-color); margin-bottom: 1rem;">
                    <i class="fas fa-trophy"></i>
                </div>
                <h2 style="color: var(--text-primary);">Semua Presentasi Selesai!</h2>
                <p class="text-muted">Terima kasih telah menyaksikan presentasi.</p>
            </div>
        </div>
    @else
        {{-- Main container with Alpine for timer-based overlay --}}
        <div wire:key="presenter-{{ $currentMahasiswaId }}"
            x-data="{
                startedAt: {{ $timerStartedAt ?? 'null' }},
                duration: {{ $timerDuration }},
                timeLeft: 0,
                interval: null,
                allScoredAt: {{ $allScoredAt ?? 'null' }},
                countdown: 5,
                countdownInterval: null,
                get timerExpired() { return this.timeLeft <= 0 && this.startedAt !== null; },
                get formattedTime() {
                    const m = Math.floor(this.timeLeft / 60);
                    const s = this.timeLeft % 60;
                    return String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
                },
                calcTime() {
                    if (!this.startedAt) { this.timeLeft = this.duration; return; }
                    const now = Math.floor(Date.now() / 1000);
                    const elapsed = now - this.startedAt;
                    this.timeLeft = Math.max(0, this.duration - elapsed);
                },
                startCountdown() {
                    if (this.allScoredAt && !this.countdownInterval) {
                        const now = Math.floor(Date.now() / 1000);
                        const elapsed = now - this.allScoredAt;
                        this.countdown = Math.max(0, 5 - elapsed);
                        this.countdownInterval = setInterval(() => {
                            this.countdown = Math.max(0, this.countdown - 1);
                            if (this.countdown <= 0) clearInterval(this.countdownInterval);
                        }, 1000);
                    }
                },
                init() {
                    this.calcTime();
                    this.interval = setInterval(() => { this.calcTime(); }, 1000);
                    if (this.allScoredAt) this.startCountdown();
                }
            }" x-init="init()">

            {{-- Presenter Info + Timer --}}
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    @if ($currentMahasiswa->hasAvatar())
                        <img src="{{ $currentMahasiswa->avatarUrl() }}" alt=""
                            style="width: 56px; height: 56px; border-radius: 50%; object-fit: cover; border: 3px solid var(--primary-color);">
                    @else
                        <div style="width: 56px; height: 56px; border-radius: 50%; background: var(--primary-color); color: white;
                            display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 800;">
                            {{ $currentMahasiswa->urutan_tampil }}
                        </div>
                    @endif
                    <div>
                        <h3 class="mb-0" style="color: var(--text-primary); font-weight: 700;">
                            {{ $currentMahasiswa->nama }}
                        </h3>
                        <small class="text-muted">
                            NIM: {{ $currentMahasiswa->nim }}
                            @if ($currentMahasiswa->tema)
                                &middot; Tema: <strong>{{ $currentMahasiswa->tema->judul }}</strong>
                            @endif
                        </small>
                    </div>
                </div>

                {{-- Timer --}}
                <div style="background: var(--bg-white); border: 1px solid var(--border-color); border-radius: 12px; padding: 0.5rem 1.5rem; text-align: center; min-width: 150px;"
                    :style="timeLeft <= 10 ? 'border-color: var(--primary-color);' : ''">
                    <small class="text-muted d-block" style="font-size: 0.7rem;">SISA WAKTU</small>
                    <div style="font-size: 1.75rem; font-weight: 800; font-family: monospace;"
                        :style="timeLeft <= 10 ? 'color: var(--primary-color);' : 'color: var(--text-primary);'">
                        <span x-text="formattedTime"></span>
                    </div>
                </div>
            </div>

            {{-- Slide Viewer --}}
            <div x-show="!timerExpired" style="background: var(--bg-white); border: 1px solid var(--border-color); border-radius: 16px; overflow: hidden;">
                @if (count($slides) > 0)
                    <div style="background: #000; min-height: 450px; display: flex; align-items: center; justify-content: center; position: relative;">
                        <img src="{{ Storage::url($slides[$currentSlideIndex]['file_gambar']) }}"
                            alt="Slide {{ $currentSlideIndex + 1 }}"
                            style="max-width: 100%; max-height: 550px; object-fit: contain;">
                    </div>
                @else
                    <div class="d-flex justify-content-center align-items-center" style="min-height: 450px;">
                        <div class="text-center text-muted">
                            <i class="fas fa-image mb-3" style="font-size: 3rem;"></i>
                            <p class="mb-0">Mahasiswa ini belum memiliki slide presentasi.</p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Scoring Panel (shown when timer expires) --}}
            <div x-show="timerExpired" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100"
                style="background: var(--bg-white); border: 2px solid var(--primary-color); border-radius: 16px; overflow: hidden; min-height: 450px;">

                <div class="text-center py-5 px-4">
                    <div class="mb-3">
                        <i class="fas fa-clipboard-check" style="font-size: 3rem; color: var(--primary-color);"></i>
                    </div>
                    <h3 style="color: var(--text-primary); font-weight: 700; margin-bottom: 0.5rem;">Waktu Presentasi Selesai</h3>
                    <p class="text-muted mb-4">Menunggu penilaian dari seluruh dewan juri...</p>

                    {{-- Juri Scoring List --}}
                    <div style="max-width: 500px; margin: 0 auto;">
                        @foreach ($juriScoringDetails as $juri)
                            <div class="d-flex align-items-center justify-content-between px-4 py-3 mb-2 rounded"
                                style="background: var(--bg-light); border: 1px solid var(--border-color);">
                                <div class="d-flex align-items-center gap-3">
                                    <div style="width: 40px; height: 40px; border-radius: 50%;
                                        background: {{ $juri['scored'] ? '#28a745' : 'var(--border-color)' }};
                                        color: white; display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                                        @if ($juri['scored'])
                                            <i class="fas fa-check"></i>
                                        @else
                                            <i class="fas fa-hourglass-half" style="color: var(--text-muted);"></i>
                                        @endif
                                    </div>
                                    <span style="font-weight: 600; color: var(--text-primary);">{{ $juri['nama'] }}</span>
                                </div>
                                <span class="badge {{ $juri['scored'] ? 'bg-success' : 'bg-secondary' }}" style="font-size: 0.8rem;">
                                    {{ $juri['scored'] ? 'Sudah Menilai' : 'Menunggu...' }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Countdown when all scored --}}
                    @if ($allScoredAt)
                        <div class="mt-4 pt-4" style="border-top: 1px solid var(--border-color);">
                            <p style="color: #28a745; font-weight: 600; margin-bottom: 0.5rem;">
                                <i class="fas fa-check-circle me-2"></i>Semua juri telah memberikan nilai!
                            </p>
                            <div style="font-size: 3rem; font-weight: 800; color: var(--primary-color); font-family: monospace;">
                                <span x-text="countdown"></span>
                            </div>
                            <p class="text-muted" style="font-size: 0.9rem;">Peserta selanjutnya dalam <span x-text="countdown"></span> detik...</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
