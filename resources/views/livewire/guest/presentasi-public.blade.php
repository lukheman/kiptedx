<div wire:poll>
    @if (!$isActive || $phase === 'idle')
        {{-- Waiting Screen --}}
        <div class="d-flex justify-content-center align-items-center" style="min-height: 90vh;">
            <div class="text-center">
                <div class="mb-4" style="font-size: 5rem; color: var(--text-muted);">
                    <i class="fas fa-hourglass-half fa-spin" style="animation-duration: 3s;"></i>
                </div>
                <h2 style="color: var(--text-primary); font-weight: 800; font-size: 2.5rem;">Menunggu Presentasi Dimulai</h2>
                <p class="text-muted" style="font-size: 1.2rem;">Presentasi belum dimulai.<br>Halaman ini akan otomatis diperbarui.</p>
            </div>
        </div>

    @elseif ($isPaused)
        {{-- Paused Screen --}}
        <div class="d-flex justify-content-center align-items-center" style="min-height: 90vh;">
            <div class="text-center">
                <div class="mb-4" style="font-size: 5rem; color: #ffc107;">
                    <i class="fas fa-pause-circle"></i>
                </div>
                <h2 style="color: var(--text-primary); font-weight: 800; font-size: 2.5rem;">Presentasi Dijeda</h2>
                <p class="text-muted" style="font-size: 1.2rem;">Presentasi sedang dijeda oleh admin.</p>
            </div>
        </div>

    @elseif ($phase === 'countdown')
        {{-- PHASE 1: Countdown 5 Minutes --}}
        <div class="d-flex justify-content-center align-items-center" style="min-height: 90vh;"
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
                get progress() {
                    return ((this.duration - this.timeLeft) / this.duration) * 100;
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
                {{-- Animated ring --}}
                <div style="position: relative; width: 280px; height: 280px; margin: 0 auto 2rem;">
                    <svg width="280" height="280" viewBox="0 0 280 280" style="transform: rotate(-90deg);">
                        <circle cx="140" cy="140" r="120" fill="none" stroke="var(--border-color)" stroke-width="8" opacity="0.3" />
                        <circle cx="140" cy="140" r="120" fill="none" stroke="var(--primary-color)" stroke-width="8"
                            :stroke-dasharray="2 * Math.PI * 120"
                            :stroke-dashoffset="2 * Math.PI * 120 * (1 - progress / 100)"
                            stroke-linecap="round"
                            style="transition: stroke-dashoffset 1s linear;" />
                    </svg>
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                        <div style="font-size: 4rem; font-weight: 900; font-family: 'Inter', monospace; color: var(--text-primary); letter-spacing: 2px;"
                            :style="timeLeft <= 30 ? 'color: var(--primary-color);' : ''">
                            <span x-text="formatted"></span>
                        </div>
                    </div>
                </div>
                <h2 style="color: var(--text-primary); font-weight: 800; font-size: 2.2rem; margin-bottom: 0.5rem;">
                    Presentasi Akan Segera Dimulai
                </h2>
                <p class="text-muted" style="font-size: 1.1rem;">Silakan bersiap-siap...</p>

                {{-- Pulse dots animation --}}
                <div class="d-flex justify-content-center gap-2 mt-4">
                    <div class="pulse-dot" style="animation-delay: 0s;"></div>
                    <div class="pulse-dot" style="animation-delay: 0.3s;"></div>
                    <div class="pulse-dot" style="animation-delay: 0.6s;"></div>
                </div>
            </div>
        </div>

    @elseif ($phase === 'intro' && $currentMahasiswa)
        {{-- PHASE 2: Presenter Introduction with entrance animation --}}
        <div class="d-flex justify-content-center align-items-center" style="min-height: 90vh;"
            x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)">
            <div class="text-center" style="max-width: 700px;"
                x-show="show"
                x-transition:enter="transition ease-out duration-700"
                x-transition:enter-start="opacity-0 transform translate-y-8 scale-95"
                x-transition:enter-end="opacity-100 transform translate-y-0 scale-100">

                {{-- Presenter Photo --}}
                <div class="intro-photo-container mb-4" style="animation: introFloat 3s ease-in-out infinite;">
                    @if ($currentMahasiswa->hasAvatar())
                        <img src="{{ $currentMahasiswa->avatarUrl() }}" alt="{{ $currentMahasiswa->nama }}"
                            style="width: 200px; height: 200px; border-radius: 50%; object-fit: cover;
                            border: 5px solid var(--primary-color); box-shadow: 0 20px 60px rgba(230,43,30,0.3);">
                    @else
                        <div style="width: 200px; height: 200px; border-radius: 50%; margin: 0 auto;
                            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
                            color: white; display: flex; align-items: center; justify-content: center;
                            font-size: 5rem; font-weight: 900; box-shadow: 0 20px 60px rgba(230,43,30,0.3);">
                            {{ $currentMahasiswa->urutan_tampil }}
                        </div>
                    @endif
                </div>

                {{-- Order Badge --}}
                <div style="animation: introSlideUp 0.8s ease-out 0.3s both;">
                    <span class="badge" style="background: var(--primary-color); color: white; font-size: 1rem; padding: 0.5rem 1.5rem; border-radius: 50px; margin-bottom: 1rem; display: inline-block;">
                        Peserta #{{ $currentMahasiswa->urutan_tampil }}
                    </span>
                </div>

                {{-- Name --}}
                <div style="animation: introSlideUp 0.8s ease-out 0.5s both;">
                    <h1 style="color: var(--text-primary); font-weight: 900; font-size: 3rem; margin-bottom: 0.5rem; line-height: 1.2;">
                        {{ $currentMahasiswa->nama }}
                    </h1>
                </div>

                {{-- NIM --}}
                <div style="animation: introSlideUp 0.8s ease-out 0.7s both;">
                    <p class="text-muted" style="font-size: 1.1rem; margin-bottom: 1.5rem;">
                        {{ $currentMahasiswa->nim }}
                    </p>
                </div>

                {{-- Tema --}}
                @if ($currentMahasiswa->tema)
                    <div style="animation: introSlideUp 0.8s ease-out 0.9s both;">
                        <div style="background: var(--bg-white); border: 2px solid var(--border-color); border-radius: 16px;
                            padding: 1.5rem 2.5rem; display: inline-block; max-width: 100%;">
                            <small class="text-muted d-block mb-1" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Tema Presentasi</small>
                            <h3 style="color: var(--primary-color); font-weight: 700; margin: 0; font-size: 1.5rem;">
                                "{{ $currentMahasiswa->tema->judul }}"
                            </h3>
                        </div>
                    </div>
                @endif

                {{-- Waiting indicator --}}
                <div style="animation: introSlideUp 0.8s ease-out 1.1s both;" class="mt-4">
                    <p class="text-muted" style="font-size: 0.95rem;">
                        <i class="fas fa-clock me-1"></i>Menunggu untuk presentasi...
                    </p>
                </div>
            </div>
        </div>

    @elseif ($phase === 'presenting' && $currentMahasiswa)
        {{-- PHASE 3: Slide Presentation --}}
        <div wire:key="presenter-{{ $currentMahasiswaId }}"
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
                            {{ $currentMahasiswa->nim }}
                            @if ($currentMahasiswa->tema)
                                &middot; Tema: <strong>{{ $currentMahasiswa->tema->judul }}</strong>
                            @endif
                        </small>
                    </div>
                </div>

            </div>

            {{-- Slide Viewer --}}
            <div x-data="{ slideLoaded: false }" x-init="setTimeout(() => slideLoaded = true, 100)">
                @if (count($slides) > 0)
                    <div style="display: flex; align-items: center; justify-content: center; position: relative;"
                        x-show="slideLoaded"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100">
                        <img src="{{ Storage::url($slides[$currentSlideIndex]['file_gambar']) }}"
                            alt="Slide {{ $currentSlideIndex + 1 }}"
                            style="width: 100%; height: auto; display: block; border-radius: 12px;">
                        {{-- Slide indicator --}}
                        <div style="position: absolute; bottom: 16px; right: 16px; background: rgba(0,0,0,0.7); color: white;
                            padding: 0.3rem 0.8rem; border-radius: 8px; font-size: 0.8rem; font-weight: 600;">
                            {{ $currentSlideIndex + 1 }} / {{ count($slides) }}
                        </div>
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

    @elseif ($phase === 'scoring' && $currentMahasiswa)
        {{-- PHASE 4: Scoring - Photo + Jury Wait --}}
        <div class="d-flex justify-content-center align-items-center" style="min-height: 90vh;"
            x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)">
            <div style="max-width: 900px; width: 100%;"
                x-show="show"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100">

                <div class="row align-items-center g-4">
                    {{-- Left: Presenter Photo --}}
                    <div class="col-md-5 text-center">
                        <div style="animation: introFloat 3s ease-in-out infinite;">
                            @if ($currentMahasiswa->hasAvatar())
                                <img src="{{ $currentMahasiswa->avatarUrl() }}" alt="{{ $currentMahasiswa->nama }}"
                                    style="width: 180px; height: 180px; border-radius: 50%; object-fit: cover;
                                    border: 4px solid var(--primary-color); box-shadow: 0 15px 40px rgba(230,43,30,0.25);">
                            @else
                                <div style="width: 180px; height: 180px; border-radius: 50%; margin: 0 auto;
                                    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
                                    color: white; display: flex; align-items: center; justify-content: center;
                                    font-size: 4rem; font-weight: 900; box-shadow: 0 15px 40px rgba(230,43,30,0.25);">
                                    {{ $currentMahasiswa->urutan_tampil }}
                                </div>
                            @endif
                        </div>
                        <h3 style="color: var(--text-primary); font-weight: 800; margin-top: 1.5rem;">{{ $currentMahasiswa->nama }}</h3>
                        <p class="text-muted">{{ $currentMahasiswa->nim }}</p>
                        @if ($currentMahasiswa->tema)
                            <span class="badge" style="background: var(--primary-color); color: white; font-size: 0.85rem; padding: 0.4rem 1rem;">
                                {{ $currentMahasiswa->tema->judul }}
                            </span>
                        @endif
                    </div>

                    {{-- Right: Jury Scoring Status --}}
                    <div class="col-md-7">
                        <div style="background: var(--bg-white); border: 2px solid var(--primary-color); border-radius: 20px; padding: 2rem; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
                            <div class="text-center mb-4">
                                <i class="fas fa-clipboard-check" style="font-size: 2.5rem; color: var(--primary-color);"></i>
                                <h4 style="color: var(--text-primary); font-weight: 700; margin-top: 0.75rem;">Waktu Presentasi Selesai</h4>
                                <p class="text-muted mb-0">Menunggu penilaian dari seluruh dewan juri...</p>
                            </div>

                            {{-- Juri Scoring List --}}
                            @foreach ($juriScoringDetails as $juri)
                                <div class="d-flex align-items-center justify-content-between px-4 py-3 mb-2 rounded-3"
                                    style="background: var(--bg-light); border: 1px solid var(--border-color); transition: all 0.3s ease;">
                                    <div class="d-flex align-items-center gap-3">
                                        <div style="width: 44px; height: 44px; border-radius: 50%;
                                            background: {{ $juri['scored'] ? '#28a745' : 'var(--border-color)' }};
                                            color: white; display: flex; align-items: center; justify-content: center; font-size: 1rem;
                                            transition: background 0.5s ease;">
                                            @if ($juri['scored'])
                                                <i class="fas fa-check"></i>
                                            @else
                                                <i class="fas fa-hourglass-half" style="color: var(--text-muted); animation: pulse-opacity 2s infinite;"></i>
                                            @endif
                                        </div>
                                        <span style="font-weight: 600; color: var(--text-primary); font-size: 1.05rem;">{{ $juri['nama'] }}</span>
                                    </div>
                                    <span class="badge {{ $juri['scored'] ? 'bg-success' : 'bg-secondary' }}" style="font-size: 0.85rem; padding: 0.4rem 0.8rem;">
                                        {{ $juri['scored'] ? 'Sudah Menilai' : 'Menunggu...' }}
                                    </span>
                                </div>
                            @endforeach

                            {{-- All scored check --}}
                            @php
                                $allScored = count($juriScoringDetails) > 0 && collect($juriScoringDetails)->every(fn($j) => $j['scored']);
                            @endphp
                            @if ($allScored)
                                <div class="text-center mt-4 pt-3" style="border-top: 1px solid var(--border-color);">
                                    <p style="color: #28a745; font-weight: 700; font-size: 1.1rem; margin-bottom: 0;">
                                        <i class="fas fa-check-circle me-2"></i>Semua juri telah memberikan nilai!
                                    </p>
                                    <p class="text-muted mt-1" style="font-size: 0.9rem;">
                                        Menunggu admin melanjutkan ke peserta berikutnya...
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @elseif (!$currentMahasiswa && $isActive)
        {{-- All Done --}}
        <div class="d-flex justify-content-center align-items-center" style="min-height: 90vh;">
            <div class="text-center">
                <div style="font-size: 5rem; color: var(--primary-color); margin-bottom: 1rem;">
                    <i class="fas fa-trophy"></i>
                </div>
                <h2 style="color: var(--text-primary); font-weight: 800; font-size: 2.5rem;">Semua Presentasi Selesai!</h2>
                <p class="text-muted" style="font-size: 1.2rem;">Terima kasih telah menyaksikan presentasi.</p>
            </div>
        </div>
    @endif

    {{-- Hidden audio player for backsound --}}
    @if ($backsoundUrl)
        <div x-data="{
            shouldPlay: {{ $musicPlaying ? 'true' : 'false' }},
            src: '{{ $backsoundUrl }}',
            audio: null,
            init() {
                this.audio = new Audio(this.src);
                this.audio.loop = true;
                this.audio.volume = 0.4;
                if (this.shouldPlay) {
                    this.audio.play().catch(() => {
                        // Auto-play blocked — user interaction needed
                        document.addEventListener('click', () => {
                            if (this.shouldPlay) this.audio.play().catch(() => {});
                        }, { once: true });
                    });
                }
            },
            destroy() {
                if (this.audio) { this.audio.pause(); this.audio = null; }
            }
        }" x-effect="shouldPlay ? audio?.play().catch(() => {}) : audio?.pause()"
            wire:key="public-audio-{{ $backsoundId }}-{{ $musicPlaying ? '1' : '0' }}"
            style="display: none;">
        </div>
    @endif

    <style>
        @keyframes introFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        @keyframes introSlideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulse-opacity {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }
        .pulse-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--primary-color);
            animation: pulseDot 1.4s ease-in-out infinite;
        }
        @keyframes pulseDot {
            0%, 80%, 100% { transform: scale(0.6); opacity: 0.4; }
            40% { transform: scale(1); opacity: 1; }
        }
    </style>
</div>
