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
            <div wire:key="timer-pub-{{ $timerStartedAt }}"
                x-data="{
                    startedAt: {{ $timerStartedAt ?? 'null' }},
                    duration: {{ $timerDuration }},
                    timeLeft: 0,
                    interval: null,
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
                    init() {
                        this.calcTime();
                        this.interval = setInterval(() => { this.calcTime(); }, 1000);
                    }
                }" x-init="init()">
                <div style="background: var(--bg-white); border: 1px solid var(--border-color); border-radius: 12px; padding: 0.5rem 1.5rem; text-align: center; min-width: 150px;"
                    :style="timeLeft <= 10 ? 'border-color: var(--primary-color);' : ''">
                    <small class="text-muted d-block" style="font-size: 0.7rem;">SISA WAKTU</small>
                    <div style="font-size: 1.75rem; font-weight: 800; font-family: monospace;"
                        :style="timeLeft <= 10 ? 'color: var(--primary-color);' : 'color: var(--text-primary);'">
                        <span x-text="formattedTime"></span>
                    </div>
                </div>
            </div>
        </div>


        {{-- Slide Viewer (Full Width) --}}
        <div style="background: var(--bg-white); border: 1px solid var(--border-color); border-radius: 16px; overflow: hidden;">
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
    @endif
</div>
