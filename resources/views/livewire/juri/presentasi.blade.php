<div wire:poll
    x-data="{
        currentId: {{ $currentMahasiswaId ?? 'null' }},
        currentPhase: '{{ $phase }}',
        transitioning: false,
        checkTransition() {
            const newId = {{ $currentMahasiswaId ?? 'null' }};
            if (this.currentId !== null && newId !== null && this.currentId !== newId) {
                this.transitioning = true;
                setTimeout(() => { this.transitioning = false; }, 2000);
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

    @if (!$isActive || $phase === 'idle')
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

    @elseif ($phase === 'countdown')
        {{-- PHASE 1: Countdown --}}
        <div class="d-flex justify-content-center align-items-center" style="min-height: 70vh;"
            x-data="{
                started: {{ $countdownStartedAt ?? 'null' }},
                duration: {{ $countdownDuration }},
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
            <div class="text-center">
                <div class="display-1 fw-bold" style="font-family: monospace; color: var(--primary-color); margin-bottom: 1rem; line-height: 1;">
                    <span x-text="formatted"></span>
                </div>
                <h3 style="color: var(--text-primary); font-weight: 700;">Presentasi Akan Segera Dimulai</h3>
                <p class="text-muted">Silakan bersiap-siap untuk memberikan penilaian...</p>
            </div>
        </div>

    @elseif ($phase === 'intro' && $currentMahasiswa)
        {{-- PHASE 2: Presenter Introduction --}}
        <div class="d-flex justify-content-center align-items-center" style="min-height: 70vh;"
            x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)">
            <div class="text-center" style="max-width: 600px;"
                x-show="show"
                x-transition:enter="transition ease-out duration-700"
                x-transition:enter-start="opacity-0 transform translate-y-8 scale-95"
                x-transition:enter-end="opacity-100 transform translate-y-0 scale-100">

                {{-- Presenter Photo --}}
                <div class="mb-4" style="animation: introFloat 3s ease-in-out infinite;">
                    @if ($currentMahasiswa->hasAvatar())
                        <img src="{{ $currentMahasiswa->avatarUrl() }}" alt="{{ $currentMahasiswa->nama }}"
                            class="intro-avatar"
                            style="border-radius: 50%; object-fit: cover;
                            border: 4px solid var(--primary-color); box-shadow: 0 15px 40px rgba(230,43,30,0.25);">
                    @else
                        <div class="intro-avatar" style="border-radius: 50%; margin: 0 auto;
                            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
                            color: white; display: flex; align-items: center; justify-content: center;
                            font-weight: 900; box-shadow: 0 15px 40px rgba(230,43,30,0.25);">
                            {{ $currentMahasiswa->urutan_tampil }}
                        </div>
                    @endif
                </div>

                <span class="badge" style="background: var(--primary-color); color: white; font-size: 0.85rem; padding: 0.4rem 1.2rem; border-radius: 50px; margin-bottom: 0.75rem; display: inline-block;">
                    Peserta #{{ $currentMahasiswa->urutan_tampil }}
                </span>
                <h2 class="h3 h2-md" style="color: var(--text-primary); font-weight: 800; margin-bottom: 0.5rem;">{{ $currentMahasiswa->nama }}</h2>
                <p class="text-muted">NIM: {{ $currentMahasiswa->nim }}</p>

                @if ($currentMahasiswa->tema)
                    <div style="background: var(--hover-bg); border: 1px solid var(--border-color); border-radius: 12px; padding: 1rem 1.5rem; display: inline-block;">
                        <small class="text-muted d-block" style="font-size: 0.75rem; text-transform: uppercase;">Tema</small>
                        <strong style="color: var(--primary-color); font-size: 1.1rem;">{{ $currentMahasiswa->tema->judul }}</strong>
                    </div>
                @endif

                <p class="text-muted mt-3" style="font-size: 0.9rem;">
                    <i class="fas fa-clock me-1"></i>Menunggu presentasi dimulai...
                </p>
            </div>
        </div>

    @elseif (($phase === 'presenting' || $phase === 'scoring') && $currentMahasiswa)
        {{-- PHASE 3 & 4: Presenting + Scoring --}}
        <div wire:key="presenter-juri-{{ $currentMahasiswaId }}"
            x-data="{
                startedAt: {{ $timerStartedAt ?? 'null' }},
                duration: {{ $timerDuration }},
                timeLeft: 0,
                interval: null,
                phase: '{{ $phase }}',
                get timerExpired() { return this.phase === 'scoring'; },
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

            {{-- Presenter Info + Timer --}}
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3 text-center text-md-start">
                <div class="d-flex flex-column flex-md-row align-items-center gap-3 w-100">
                    @if ($currentMahasiswa->hasAvatar())
                        <img src="{{ $currentMahasiswa->avatarUrl() }}" alt="Foto" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 3px solid var(--border-color);">
                    @else
                        <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 700; border: 3px solid var(--border-color);">
                            {{ $currentMahasiswa->urutan_tampil }}
                        </div>
                    @endif
                    <div class="flex-grow-1">
                        <h4 class="mb-1 h5" style="color: var(--text-primary); font-weight: 700;">
                            @if (!$currentMahasiswa->hasAvatar())
                                <span class="badge bg-danger me-2 d-md-none" style="font-size: 0.8rem;">{{ $currentMahasiswa->urutan_tampil }}</span>
                            @endif
                            {{ $currentMahasiswa->nama }}
                        </h4>
                        <div class="text-muted small d-flex flex-column flex-md-row align-items-center gap-1 justify-content-center justify-content-md-start">
                            <span>NIM: {{ $currentMahasiswa->nim }}</span>
                            @if ($currentMahasiswa->tema)
                                <span class="d-none d-md-inline">&middot;</span>
                                <span>Tema: <strong>{{ $currentMahasiswa->tema->judul }}</strong></span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Timer --}}
                <div class="w-100 d-flex justify-content-center justify-content-md-end" style="max-width: 200px;">
                    @if ($phase === 'presenting')
                        <div class="modern-card py-2 px-3 text-center mb-0 w-100"
                            :style="timeLeft <= 30 ? 'border-color: var(--primary-color);' : ''">
                            <small class="text-muted d-block" style="font-size: 0.7rem;">SISA WAKTU</small>
                            <div style="font-size: 1.5rem; font-weight: 800; font-family: monospace;"
                                :style="timeLeft <= 30 ? 'color: var(--primary-color);' : 'color: var(--text-primary);'">
                                <span x-text="formattedTime"></span>
                            </div>
                        </div>
                    @else
                        <div class="modern-card py-2 px-3 text-center mb-0 w-100" style="border-color: var(--primary-color);">
                            <small class="text-muted d-block" style="font-size: 0.7rem;">STATUS</small>
                            <div style="font-size: 1rem; font-weight: 700; color: var(--primary-color);">
                                <i class="fas fa-clipboard-check me-1"></i>Penilaian
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Navigator Pills --}}
            <div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-2 mb-4">
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
                <div class="col-12 col-lg-8 mb-4 order-2 order-lg-1">
                    {{-- Scoring Info Panel --}}
                    <div class="modern-card h-100" style="overflow: hidden; border: 2px solid var(--primary-color);"
                        x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)"
                        x-show="show"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100">

                            <div class="d-flex flex-column flex-lg-row align-items-center align-items-lg-start gap-4 py-4 px-3 text-center text-lg-start">
                                {{-- Presenter Photo --}}
                                <div class="flex-shrink-0" style="min-width: 140px;">
                                    @if ($currentMahasiswa->hasAvatar())
                                        <img src="{{ $currentMahasiswa->avatarUrl() }}" alt="{{ $currentMahasiswa->nama }}"
                                            class="info-avatar"
                                            style="border-radius: 50%; object-fit: cover;
                                            border: 4px solid var(--primary-color); box-shadow: 0 10px 30px rgba(230,43,30,0.2);
                                            animation: introFloat 3s ease-in-out infinite; margin: 0 auto;">
                                    @else
                                        <div class="info-avatar" style="border-radius: 50%; margin: 0 auto;
                                            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
                                            color: white; display: flex; align-items: center; justify-content: center;
                                            font-weight: 900; box-shadow: 0 10px 30px rgba(230,43,30,0.2);
                                            animation: introFloat 3s ease-in-out infinite;">
                                            {{ $currentMahasiswa->urutan_tampil }}
                                        </div>
                                    @endif
                                    <h5 class="mt-3 mb-1" style="color: var(--text-primary); font-weight: 700;">{{ $currentMahasiswa->nama }}</h5>
                                    @if ($currentMahasiswa->tema)
                                        <span class="badge" style="background: var(--primary-color); color: white; font-size: 0.8rem;">
                                            {{ $currentMahasiswa->tema->judul }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Juri Scoring Status --}}
                                <div class="flex-grow-1 w-100">
                                    <div class="text-center mb-3">
                                        <i class="fas fa-clipboard-check" style="font-size: 2rem; color: var(--primary-color);"></i>
                                        <h4 style="color: var(--text-primary); font-weight: 700; margin-top: 0.5rem;">Penilaian Juri</h4>
                                        <p class="text-muted mb-3" style="font-size: 0.9rem;">Menunggu penilaian dari seluruh juri...</p>
                                    </div>

                                    @foreach ($juriScoringDetails as $juri)
                                        <div class="d-flex align-items-center justify-content-between px-3 py-2 mb-2 rounded"
                                            style="background: var(--bg-light); border: 1px solid var(--border-color);">
                                            <div class="d-flex align-items-center gap-2">
                                                <div style="width: 36px; height: 36px; border-radius: 50%;
                                                    background: {{ $juri['scored'] ? '#28a745' : 'var(--border-color)' }};
                                                    color: white; display: flex; align-items: center; justify-content: center; font-size: 0.85rem;">
                                                    @if ($juri['scored'])
                                                        <i class="fas fa-check"></i>
                                                    @else
                                                        <i class="fas fa-hourglass-half" style="color: var(--text-muted);"></i>
                                                    @endif
                                                </div>
                                                <span style="font-weight: 600; color: var(--text-primary);">{{ $juri['nama'] }}</span>
                                            </div>
                                            <span class="badge {{ $juri['scored'] ? 'bg-success' : 'bg-secondary' }}" style="font-size: 0.75rem;">
                                                {{ $juri['scored'] ? 'Sudah Menilai' : 'Menunggu...' }}
                                            </span>
                                        </div>
                                    @endforeach

                                    @php
                                        $allScored = count($juriScoringDetails) > 0 && collect($juriScoringDetails)->every(fn($j) => $j['scored']);
                                    @endphp
                                    @if ($allScored)
                                        <div class="text-center mt-3 pt-3" style="border-top: 1px solid var(--border-color);">
                                            <p style="color: #28a745; font-weight: 600; margin-bottom: 0;">
                                                <i class="fas fa-check-circle me-2"></i>Semua juri telah memberikan nilai!
                                            </p>
                                            <small class="text-muted">Menunggu admin melanjutkan...</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                </div>

                {{-- Scoring Panel --}}
                <div class="col-12 col-lg-4 mb-4 order-1 order-lg-2">
                    <div class="modern-card h-100">
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
        </div>

    @elseif (!$currentMahasiswa && $isActive)
        <div class="d-flex justify-content-center align-items-center" style="min-height: 70vh;">
            <div class="text-center">
                <div style="font-size: 4rem; color: var(--primary-color); margin-bottom: 1rem;">
                    <i class="fas fa-trophy"></i>
                </div>
                <h2 style="color: var(--text-primary);">Semua Presentasi Selesai!</h2>
                <p class="text-muted">Terima kasih telah memberikan penilaian.</p>
            </div>
        </div>
    @endif

    <style>
        .intro-avatar { width: 120px; height: 120px; font-size: 3rem; }
        .info-avatar { width: 100px; height: 100px; font-size: 2.5rem; }
        @media(min-width: 768px) {
            .intro-avatar { width: 150px; height: 150px; font-size: 4rem; }
            .info-avatar { width: 130px; height: 130px; font-size: 3.5rem; }
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        @keyframes introFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
    </style>
</div>
