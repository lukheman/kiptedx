<div wire:poll.3s="loadState">
    <x-page-header title="Kontrol Presentasi" subtitle="Kelola jalannya presentasi secara real-time">
        <x-slot:actions>
            @if ($isActive)
                <div class="d-flex gap-2">
                    @if ($isPaused)
                        <x-button variant="primary" icon="fas fa-play" wire:click="resumePresentasi">
                            Lanjutkan
                        </x-button>
                    @else
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
        if ($isActive && $isPaused) {
            $statusColor = '#ffc107';
            $statusText = 'DIJEDA';
            $statusIcon = 'fa-pause-circle';
        } elseif ($isActive) {
            $statusColor = '#28a745';
            $statusText = 'SEDANG BERLANGSUNG';
            $statusIcon = 'fa-broadcast-tower';
        } else {
            $statusColor = 'var(--text-muted)';
            $statusText = 'BELUM DIMULAI';
            $statusIcon = 'fa-stop-circle';
        }
    @endphp
    <div class="modern-card mb-4 d-flex align-items-center gap-3" style="border-left: 4px solid {{ $statusColor }};">
        <div class="rounded-circle d-flex align-items-center justify-content-center"
            style="width: 48px; height: 48px; background: {{ $statusColor }}20; flex-shrink: 0;">
            <i class="fas {{ $statusIcon }}"
                style="font-size: 1.25rem; color: {{ $statusColor }};"></i>
        </div>
        <div>
            <h6 class="mb-0" style="color: var(--text-primary);">
                Status: <strong>{{ $statusText }}</strong>
            </h6>
            <small class="text-muted">
                {{ count($mahasiswaList) }} mahasiswa terdaftar &middot; {{ $juriCount }} juri
            </small>
        </div>
    </div>

        <div class="row"> 
        <div class="col-8"> 

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
        <div class="col-4"> 

    @if ($isActive && $currentMhs)
        {{-- Current Presenter Card --}}
        <div class="modern-card mb-4" style="border: 2px solid var(--primary-color);">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 56px; height: 56px; background: var(--primary-color); color: white; font-size: 1.5rem; font-weight: 800; flex-shrink: 0;">
                        {{ $currentMhs['urutan_tampil'] }}
                    </div>
                    <div>
                        <h4 class="mb-0" style="color: var(--text-primary); font-weight: 700;">{{ $currentMhs['nama'] }}</h4>
                        <small class="text-muted">
                            NIM: {{ $currentMhs['nim'] }}
                            @if (!empty($currentMhs['tema']))
                                &middot; Tema: {{ $currentMhs['tema']['judul'] }}
                            @endif
                        </small>
                    </div>
                </div>

                {{-- Navigation --}}
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary" wire:click="prevMahasiswa" {{ $currentIndex === 0 ? 'disabled' : '' }}>
                        <i class="fas fa-arrow-left me-1"></i>Sebelum
                    </button>
                    <button class="btn btn-outline-secondary" wire:click="resetTimer">
                        <i class="fas fa-redo me-1"></i>Reset Timer
                    </button>
                    <button class="btn btn-primary" wire:click="nextMahasiswa" {{ $currentIndex >= count($mahasiswaList) - 1 ? 'disabled' : '' }}>
                        Selanjutnya<i class="fas fa-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

            {{-- Score Status --}}
            <div class="mt-3 pt-3" style="border-top: 1px solid var(--border-color);">
                <small class="text-muted">
                    <i class="fas fa-clipboard-check me-1"></i>
                    Sudah dinilai oleh <strong>{{ $scoredStats[$currentMhs['id']] ?? 0 }}</strong> dari <strong>{{ $juriCount }}</strong> juri
                </small>
                @if (($scoredStats[$currentMhs['id']] ?? 0) >= $juriCount && $juriCount > 0)
                    <span class="badge bg-success ms-2">Lengkap</span>
                @endif
            </div>

            {{-- Slide Control --}}
            <div class="mt-3 pt-3" style="border-top: 1px solid var(--border-color);">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
                    <div>
                        <h6 class="mb-0" style="color: var(--text-primary); font-weight: 600;">Kontrol Slide Presentasi</h6>
                        <small class="text-muted">Slide {{ $currentSlideIndex + 1 }} dari {{ max(1, $slidesCount) }}</small>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary" wire:click="prevSlide" {{ $currentSlideIndex <= 0 ? 'disabled' : '' }}>
                            <i class="fas fa-chevron-left me-1"></i>Slide Sebelumnya
                        </button>
                        <button class="btn btn-primary" wire:click="nextSlide" {{ $currentSlideIndex >= $slidesCount - 1 ? 'disabled' : '' }}>
                            Slide Selanjutnya<i class="fas fa-chevron-right ms-1"></i>
                        </button>
                    </div>
                </div>

                @if (count($slides) > 0 && isset($slides[$currentSlideIndex]))
                    <div class="text-center bg-dark rounded overflow-hidden" style="position: relative; padding: 1rem;">
                        <img src="{{ Storage::url($slides[$currentSlideIndex]['file_gambar']) }}"
                             alt="Preview Slide"
                             style="max-width: 100%; max-height: 400px; object-fit: contain; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                        <div class="position-absolute px-3 py-1 rounded" style="bottom: 10px; right: 10px; background: rgba(0,0,0,0.7); color: white; font-size: 0.8rem;">
                            Slide {{ $currentSlideIndex + 1 }}
                        </div>
                    </div>
                @else
                    <div class="text-center text-muted p-4 bg-light rounded" style="border: 1px dashed var(--border-color);">
                        <i class="fas fa-image mb-2" style="font-size: 2rem;"></i>
                        <p class="mb-0">Tidak ada slide yang tersedia.</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
        </div>
        </div>

</div>
