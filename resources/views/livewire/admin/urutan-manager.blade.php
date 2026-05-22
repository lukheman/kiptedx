<div>
    <x-page-header title="Atur Urutan Tampil" subtitle="Kelola urutan tampil presentasi mahasiswa">
        <x-slot:actions>
            <div class="d-flex gap-2">
                <x-button variant="outline" icon="fas fa-undo" wire:click="resetUrutan"
                    onclick="confirm('Reset semua urutan yang tidak terkunci?') || event.stopImmediatePropagation()">
                    Reset
                </x-button>
                <x-button variant="primary" icon="fas fa-random" wire:click="acakUrutan">
                    Acak Urutan
                </x-button>
            </div>
        </x-slot:actions>
    </x-page-header>

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-alert variant="success" title="Sukses!" class="mb-4">
            {{ session('success') }}
        </x-alert>
    @endif

    @if (session('error'))
        <x-alert variant="danger" title="Error!" class="mb-4">
            {{ session('error') }}
        </x-alert>
    @endif

    {{-- Info --}}
    <div class="alert d-flex align-items-start mb-4 border-0" style="background-color: rgba(23, 162, 184, 0.1); color: #17a2b8;" role="alert">
        <i class="fas fa-info-circle me-3 mt-1 fs-5"></i>
        <div>
            <strong>Panduan:</strong>
            <ul class="mb-0 mt-1 ps-3">
                <li>Isi kolom <strong>Urutan</strong> secara manual untuk menentukan posisi tampil mahasiswa.</li>
                <li>Klik ikon <strong>gembok</strong> <i class="fas fa-lock"></i> untuk mengunci posisi. Mahasiswa yang terkunci <strong>tidak akan berubah</strong> saat urutan diacak.</li>
                <li>Tekan tombol <strong>Acak Urutan</strong> untuk mengacak posisi mahasiswa yang <strong>tidak terkunci</strong>.</li>
            </ul>
        </div>
    </div>

    {{-- Card Grid --}}
    <div class="row">
        @forelse ($mahasiswas as $index => $mhs)
            <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-4" wire:key="urutan-{{ $mhs['id'] }}-{{ $mhs['urutan_tampil'] ?? 'none' }}-{{ $mhs['urutan_dikunci'] ? 'lock' : 'unlock' }}">
                <div class="modern-card h-100 position-relative text-center p-3"
                    style="{{ $mhs['urutan_dikunci'] ? 'border-color: var(--primary-color); border-width: 2px;' : '' }}">

                    {{-- Lock badge --}}
                    @if ($mhs['urutan_dikunci'])
                        <div class="position-absolute" style="top: 8px; left: 8px;">
                            <span class="badge bg-danger" style="font-size: 0.65rem;">
                                <i class="fas fa-lock me-1"></i>Terkunci
                            </span>
                        </div>
                    @endif

                    {{-- Slide count badge --}}
                    <div class="position-absolute" style="top: 8px; right: 8px;">
                        <span class="badge {{ $mhs['slide_count'] >= 3 ? 'bg-success' : 'bg-secondary' }}" style="font-size: 0.65rem;">
                            {{ $mhs['slide_count'] }} Slide
                        </span>
                    </div>

                    {{-- Profile Photo --}}
                    <div class="mx-auto mb-3" style="margin-top: 10px;">
                        @if ($mhs['foto_profil'])
                            <img src="{{ $mhs['foto_profil'] }}" alt="{{ $mhs['nama'] }}"
                                class="rounded-circle border"
                                style="width: 72px; height: 72px; object-fit: cover; {{ $mhs['urutan_dikunci'] ? 'border-color: var(--primary-color) !important; border-width: 2px !important;' : '' }}">
                        @else
                            <div class="rounded-circle d-flex justify-content-center align-items-center mx-auto"
                                style="width: 72px; height: 72px; font-size: 1.5rem; background: var(--hover-bg); color: var(--text-muted);">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                    </div>

                    {{-- Name & NIM --}}
                    <h6 class="mb-0" style="color: var(--text-primary); font-size: 0.85rem; font-weight: 600; line-height: 1.3;">
                        {{ $mhs['nama'] }}
                    </h6>
                    <small class="text-muted" style="font-size: 0.75rem;">{{ $mhs['nim'] }}</small>

                    {{-- Urutan Input --}}
                    <div class="mt-3 mb-2">
                        <label class="form-label text-muted mb-1" style="font-size: 0.7rem;">Urutan Tampil</label>
                        <input type="number"
                            class="form-control form-control-sm text-center mx-auto"
                            style="width: 65px; {{ $mhs['urutan_dikunci'] ? 'border-color: var(--primary-color); font-weight: 700;' : '' }}"
                            value="{{ $mhs['urutan_tampil'] }}"
                            min="1"
                            wire:change="updateUrutan({{ $mhs['id'] }}, $event.target.value)"
                            {{ $mhs['urutan_dikunci'] ? 'readonly' : '' }}>
                    </div>

                    {{-- Lock Toggle --}}
                    <button class="btn btn-sm w-100 {{ $mhs['urutan_dikunci'] ? 'btn-danger' : 'btn-outline-secondary' }}"
                        wire:click="toggleKunci({{ $mhs['id'] }})"
                        style="font-size: 0.75rem;">
                        <i class="fas {{ $mhs['urutan_dikunci'] ? 'fa-lock' : 'fa-unlock' }} me-1"></i>
                        {{ $mhs['urutan_dikunci'] ? 'Terkunci' : 'Kunci' }}
                    </button>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="modern-card text-center py-5">
                    <div class="text-muted">
                        <i class="fas fa-users mb-2" style="font-size: 2rem;"></i>
                        <p class="mb-0">Tidak ada data mahasiswa</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Preview Urutan Final --}}
    @php
        $ordered = collect($mahasiswas)->filter(fn($m) => $m['urutan_tampil'] !== null)->sortBy('urutan_tampil')->values();
        $unordered = collect($mahasiswas)->filter(fn($m) => $m['urutan_tampil'] === null)->values();
    @endphp

    @if ($ordered->count() > 0)
        <div class="modern-card mt-2">
            <h6 class="mb-3" style="color: var(--text-primary); font-weight: 600;">
                <i class="fas fa-list-ol me-2"></i>Preview Urutan Tampil
            </h6>
            <div class="d-flex flex-wrap gap-2">
                @foreach ($ordered as $mhs)
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill"
                        wire:key="preview-{{ $mhs['id'] }}-{{ $mhs['urutan_tampil'] ?? 'none' }}"
                        style="background: {{ $mhs['urutan_dikunci'] ? 'rgba(230, 43, 30, 0.1)' : 'var(--hover-bg)' }};
                               border: 1px solid {{ $mhs['urutan_dikunci'] ? 'var(--primary-color)' : 'var(--border-color)' }};">
                        <span class="badge rounded-pill {{ $mhs['urutan_dikunci'] ? 'bg-danger' : 'bg-secondary' }}" style="font-size: 0.7rem;">
                            {{ $mhs['urutan_tampil'] }}
                        </span>
                        @if ($mhs['foto_profil'])
                            <img src="{{ $mhs['foto_profil'] }}" class="rounded-circle" style="width: 22px; height: 22px; object-fit: cover;">
                        @endif
                        <span style="color: var(--text-primary); font-size: 0.875rem;">{{ $mhs['nama'] }}</span>
                        @if ($mhs['urutan_dikunci'])
                            <i class="fas fa-lock" style="font-size: 0.65rem; color: var(--primary-color);"></i>
                        @endif
                    </div>
                @endforeach
            </div>

            @if ($unordered->count() > 0)
                <p class="text-muted small mt-3 mb-0">
                    <i class="fas fa-exclamation-circle me-1"></i>
                    {{ $unordered->count() }} mahasiswa belum memiliki urutan tampil.
                </p>
            @endif
        </div>
    @endif
</div>
