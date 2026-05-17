<div wire:poll
    x-data="{
        currentId: {{ $currentMahasiswaId ?? 'null' }},
        transitioning: false,
        checkTransition() {
            const newId = {{ $currentMahasiswaId ?? 'null' }};
            if (this.currentId !== null && newId !== null && this.currentId !== newId) {
                this.transitioning = true;
                setTimeout(() => { this.transitioning = false; }, 3000);
            }
            this.currentId = newId;
        }
    }"
    x-init="$watch('$wire.__instance.effects', () => { $nextTick(() => checkTransition()) })"
    x-effect="checkTransition()">

    {{-- Transition Overlay --}}
    <div x-show="transitioning" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        style="position: fixed; inset: 0; z-index: 9999; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.85);">
        <div class="text-center">
            <div class="mb-4" style="position: relative; width: 80px; height: 80px; margin: 0 auto;">
                <div style="width: 80px; height: 80px; border: 4px solid rgba(230,43,30,0.2); border-top-color: var(--primary-color); border-radius: 50%; animation: spin 1s linear infinite;"></div>
            </div>
            <h3 style="color: #fff; font-weight: 700; margin-bottom: 0.5rem;">Peserta Selanjutnya</h3>
            <p style="color: rgba(255,255,255,0.6); font-size: 0.95rem;">Mempersiapkan presentasi...</p>
        </div>
    </div>

    @if (!$isActive)
        {{-- Waiting Screen --}}
        <div class="d-flex justify-content-center align-items-center" style="min-height: 70vh;">
            <div class="text-center">
                <div class="mb-4" style="font-size: 4rem; color: var(--text-muted);">
                    <i class="fas fa-hourglass-half fa-spin" style="animation-duration: 3s;"></i>
                </div>
                <h2 style="color: var(--text-primary);">Menunggu Presentasi Dimulai</h2>
                <p class="text-muted">Admin belum memulai sesi presentasi.<br>Halaman ini akan otomatis diperbarui.</p>
            </div>
        </div>
    @elseif ($isPaused)
        {{-- Paused Screen --}}
        <div class="d-flex justify-content-center align-items-center" style="min-height: 70vh;">
            <div class="text-center">
                <div class="mb-4" style="font-size: 4rem; color: #ffc107;">
                    <i class="fas fa-pause-circle"></i>
                </div>
                <h2 style="color: var(--text-primary);">Presentasi Dijeda</h2>
                <p class="text-muted">Menunggu admin melanjutkan sesi presentasi...<br>Halaman ini akan otomatis diperbarui.</p>
                @if ($timerRemaining)
                    <div class="mt-3">
                        <span class="badge bg-warning text-dark px-3 py-2" style="font-size: 1rem;">
                            <i class="fas fa-clock me-2"></i>Sisa waktu: {{ floor($timerRemaining / 60) }}:{{ str_pad($timerRemaining % 60, 2, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                @endif
            </div>
        </div>
    @elseif (!$currentMahasiswa)
        <div class="d-flex justify-content-center align-items-center" style="min-height: 70vh;">
            <div class="text-center">
                <div style="font-size: 4rem; color: var(--primary-color); margin-bottom: 1rem;">
                    <i class="fas fa-trophy"></i>
                </div>
                <h2 style="color: var(--text-primary);">Semua Presentasi Selesai!</h2>
                <p class="text-muted">Terima kasih telah memberikan penilaian.</p>
            </div>
        </div>
    @else
        {{-- Top Bar: Presenter Info + Timer --}}
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                @if ($currentMahasiswa->hasAvatar())
                    <img src="{{ $currentMahasiswa->avatarUrl() }}" alt="Foto" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 3px solid var(--border-color);">
                @else
                    <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 700; border: 3px solid var(--border-color);">
                        {{ $currentMahasiswa->urutan_tampil }}
                    </div>
                @endif
                <div>
                    <h4 class="mb-1" style="color: var(--text-primary); font-weight: 700;">
                        @if (!$currentMahasiswa->hasAvatar())
                            <span class="badge bg-danger me-2" style="font-size: 0.8rem;">{{ $currentMahasiswa->urutan_tampil }}</span>
                        @endif
                        {{ $currentMahasiswa->nama }}
                    </h4>
                <small class="text-muted">
                    NIM: {{ $currentMahasiswa->nim }}
                    @if ($currentMahasiswa->tema)
                        &middot; Tema: <strong>{{ $currentMahasiswa->tema->judul }}</strong>
                    @endif
                </small>
            </div>

            {{-- Server-synced Timer: wire:key forces Alpine re-init when presenter changes --}}
            <div wire:key="timer-{{ $timerStartedAt }}"
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
                <div class="modern-card py-2 px-4 text-center mb-0" style="min-width: 150px;"
                    :style="timeLeft <= 10 ? 'border-color: var(--primary-color);' : ''">
                    <small class="text-muted d-block" style="font-size: 0.7rem;">SISA WAKTU</small>
                    <div style="font-size: 1.75rem; font-weight: 800; font-family: monospace;"
                        :style="timeLeft <= 10 ? 'color: var(--primary-color);' : 'color: var(--text-primary);'">
                        <span x-text="formattedTime"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Navigator Pills --}}
        <div class="d-flex flex-wrap gap-2 mb-4">
            @foreach ($mahasiswaList as $mhs)
                @php
                    $isCurrent = $mhs->id == $currentMahasiswaId;
                    $isScored = in_array($mhs->id, $scoredIds);
                @endphp
                <div class="btn btn-sm {{ $isCurrent ? 'btn-danger' : ($isScored ? 'btn-success' : 'btn-outline-secondary') }}"
                    style="font-size: 0.75rem; min-width: 36px; cursor: default;">
                    {{ $mhs->urutan_tampil }}
                </div>
            @endforeach
        </div>

        <div class="row">
            {{-- Slide Viewer --}}
            <div class="col-12 col-lg-8 mb-4">
                <div class="modern-card" style="padding: 0; overflow: hidden;">
                    @if (count($slides) > 0)
                        <div style="background: #000; min-height: 400px; display: flex; align-items: center; justify-content: center; position: relative;">
                            <img src="{{ Storage::url($slides[$currentSlideIndex]['file_gambar']) }}"
                                alt="Slide {{ $currentSlideIndex + 1 }}"
                                style="max-width: 100%; max-height: 500px; object-fit: contain;">

                        </div>
                    @else
                        <div class="d-flex justify-content-center align-items-center" style="min-height: 400px;">
                            <div class="text-center text-muted">
                                <i class="fas fa-image mb-3" style="font-size: 3rem;"></i>
                                <p class="mb-0">Mahasiswa ini belum memiliki slide presentasi.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Scoring Panel --}}
            <div class="col-12 col-lg-4 mb-4">
                <div class="modern-card">
                    <h5 class="mb-4" style="color: var(--text-primary); font-weight: 600;">
                        <i class="fas fa-star me-2" style="color: #ffc107;"></i>Penilaian
                    </h5>

                    @if (session('success_nilai'))
                        <x-alert variant="success" class="py-2 mb-3">{{ session('success_nilai') }}</x-alert>
                    @endif

                    @if ($alreadyScored)
                        <div class="alert border-0 mb-3" style="background-color: rgba(40, 167, 69, 0.1); color: #28a745;">
                            <i class="fas fa-check-circle me-2"></i>Sudah dinilai. Anda dapat memperbarui nilai.
                        </div>
                    @endif

                    <form wire:submit.prevent="submitNilai">
                        <div class="mb-3">
                            <label class="form-label text-muted">Nilai (1 - 100)</label>
                            <input type="number" class="form-control form-control-lg text-center @error('nilai') is-invalid @enderror"
                                wire:model="nilai" min="1" max="100" placeholder="0"
                                style="font-size: 2rem; font-weight: 800; letter-spacing: 2px;">
                            @error('nilai') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted">Catatan (opsional)</label>
                            <textarea class="form-control" wire:model="catatan" rows="3" placeholder="Catatan untuk mahasiswa..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100" wire:loading.attr="disabled" wire:target="submitNilai">
                            <span wire:loading.remove wire:target="submitNilai">
                                <i class="fas fa-check me-2"></i>{{ $alreadyScored ? 'Perbarui Nilai' : 'Simpan Nilai' }}
                            </span>
                            <span wire:loading wire:target="submitNilai">
                                <i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <style>
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</div>

