<div>
    {{-- Page Header --}}
    <x-page-header title="Nilai Mahasiswa" subtitle="Rangkuman dan rincian penilaian presentasi mahasiswa oleh Dewan Juri">
    </x-page-header>

    {{-- Stats Cards for Quick Overview --}}
    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card" style="--accent-color: var(--primary-color);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted d-block mb-1" style="font-size: 0.85rem; font-weight: 600;">TOTAL MAHASISWA</span>
                        <h3 class="mb-0" style="color: var(--text-primary); font-weight: 800;">{{ \App\Models\Mahasiswa::count() }}</h3>
                    </div>
                    <div class="stat-icon" style="background: rgba(230, 43, 30, 0.1); color: var(--primary-color);">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card" style="--accent-color: #10b981;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted d-block mb-1" style="font-size: 0.85rem; font-weight: 600;">SUDAH DINILAI (SEMUA JURI)</span>
                        @php
                            $totalJuri = count($juris);
                            $fullyGraded = \App\Models\Mahasiswa::whereHas('nilais', function($q) {}, '=', $totalJuri)->count();
                        @endphp
                        <h3 class="mb-0" style="color: var(--text-primary); font-weight: 800;">{{ $fullyGraded }}</h3>
                    </div>
                    <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <i class="fas fa-check-double"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card" style="--accent-color: #f59e0b;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted d-block mb-1" style="font-size: 0.85rem; font-weight: 600;">SEDANG DINILAI</span>
                        @php
                            $partiallyGraded = \App\Models\Mahasiswa::whereHas('nilais')->whereHas('nilais', function($q) {}, '<', $totalJuri)->count();
                        @endphp
                        <h3 class="mb-0" style="color: var(--text-primary); font-weight: 800;">{{ $partiallyGraded }}</h3>
                    </div>
                    <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                        <i class="fas fa-spinner"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card" style="--accent-color: #3b82f6;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted d-block mb-1" style="font-size: 0.85rem; font-weight: 600;">TOTAL JURI</span>
                        <h3 class="mb-0" style="color: var(--text-primary); font-weight: 800;">{{ $totalJuri }}</h3>
                    </div>
                    <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                        <i class="fas fa-user-tie"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="modern-card">
        {{-- Search and Filters --}}
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <h5 class="mb-0" style="color: var(--text-primary); font-weight: 700;">Rekapitulasi Nilai</h5>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <button wire:click="exportCsv" class="btn btn-success" style="font-weight: 600; display: inline-flex; align-items: center; border-radius: 10px; padding: 0.5rem 1rem;">
                    <i class="fas fa-file-excel me-2"></i> Export CSV
                </button>
                <div class="input-group" style="max-width: 300px;">
                    <span class="input-group-text" style="background: var(--input-bg); border-color: var(--border-color);">
                        <i class="fas fa-search" style="color: var(--text-muted);"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Cari nama atau NIM..."
                        wire:model.live.debounce.300ms="search" style="border-left: none;">
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th style="width: 70px;" class="text-center">Urutan</th>
                        <th style="width: 80px;" class="text-center">Foto</th>
                        <th style="cursor: pointer;" wire:click="sortByColumn('nim')">
                            NIM
                            @if ($sortBy === 'nim')
                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1" style="font-size: 0.75rem;"></i>
                            @else
                                <i class="fas fa-sort ms-1" style="font-size: 0.75rem; opacity: 0.3;"></i>
                            @endif
                        </th>
                        <th style="cursor: pointer;" wire:click="sortByColumn('nama')">
                            Nama Mahasiswa
                            @if ($sortBy === 'nama')
                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1" style="font-size: 0.75rem;"></i>
                            @else
                                <i class="fas fa-sort ms-1" style="font-size: 0.75rem; opacity: 0.3;"></i>
                            @endif
                        </th>
                        
                        {{-- Dynamic Jury Columns --}}
                        @foreach ($juris as $juri)
                            <th class="text-center" style="font-size: 0.8rem; font-weight: 600; min-width: 110px;">
                                <i class="fas fa-user-tie me-1"></i>{{ Str::limit($juri->nama, 12) }}
                            </th>
                        @endforeach

                        <th class="text-center" style="cursor: pointer; min-width: 100px;" wire:click="sortByColumn('rata_rata')">
                            Rata-Rata
                            @if ($sortBy === 'rata_rata')
                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1" style="font-size: 0.75rem;"></i>
                            @else
                                <i class="fas fa-sort ms-1" style="font-size: 0.75rem; opacity: 0.3;"></i>
                            @endif
                        </th>
                        <th class="text-center" style="cursor: pointer; min-width: 120px;" wire:click="sortByColumn('jumlah_menilai')">
                            Status Juri
                            @if ($sortBy === 'jumlah_menilai')
                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1" style="font-size: 0.75rem;"></i>
                            @else
                                <i class="fas fa-sort ms-1" style="font-size: 0.75rem; opacity: 0.3;"></i>
                            @endif
                        </th>
                        <th style="width: 100px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mahasiswas as $mhs)
                        @php
                            $mhsNilais = $allNilais->get($mhs->id) ?? collect();
                            $rataRata = $mhs->rata_rata;
                            $jumlahMenilai = $mhs->jumlah_menilai;
                            $allScored = $totalJuri > 0 && $jumlahMenilai === $totalJuri;
                        @endphp
                        <tr wire:key="mhs-score-{{ $mhs->id }}">
                            <td class="text-center">
                                <span class="badge bg-secondary rounded-pill" style="font-size: 0.85rem; padding: 0.4rem 0.6rem;">
                                    {{ $mhs->urutan_tampil ?? '-' }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if ($mhs->hasAvatar())
                                    <img src="{{ $mhs->avatarUrl() }}" alt="Foto" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid var(--border-color);">
                                @else
                                    <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--bg-light); border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                        <i class="fas fa-user text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td style="color: var(--text-secondary); font-weight: 600;">{{ $mhs->nim }}</td>
                            <td>
                                <div class="fw-bold" style="color: var(--text-primary);">{{ $mhs->nama }}</div>
                                @if ($mhs->tema)
                                    <div class="text-muted small mt-1" style="font-size: 0.75rem;">
                                        Tema: <span class="text-danger" style="font-weight: 500;">"{{ $mhs->tema->judul }}"</span>
                                    </div>
                                @else
                                    <div class="text-muted small mt-1" style="font-size: 0.75rem; font-style: italic;">
                                        Belum memilih tema
                                    </div>
                                @endif
                            </td>

                            {{-- Dynamic Jury Scores --}}
                            @foreach ($juris as $juri)
                                @php
                                    $scoreObj = $mhsNilais->firstWhere('juri_id', $juri->id);
                                @endphp
                                <td class="text-center">
                                    @if ($scoreObj)
                                        <span class="fw-bold" style="font-size: 1rem; color: var(--text-primary);">
                                            {{ $scoreObj->nilai }}
                                        </span>
                                    @else
                                        <span class="text-muted" style="opacity: 0.4;">-</span>
                                    @endif
                                </td>
                            @endforeach

                            {{-- Rata-Rata --}}
                            <td class="text-center">
                                @if ($rataRata !== null)
                                    @php
                                        $avg = round($rataRata, 1);
                                        $bgColor = '#e62b1e'; // Default red
                                        $textColor = '#ffffff';
                                        if ($avg >= 80) {
                                            $bgColor = '#10b981'; // Green
                                        } elseif ($avg >= 70) {
                                            $bgColor = '#f59e0b'; // Yellow
                                        }
                                    @endphp
                                    <span class="badge-modern fw-bold" style="background: {{ $bgColor }}; color: {{ $textColor }}; font-size: 0.95rem; padding: 0.4rem 0.8rem; border-radius: 8px;">
                                        {{ $avg }}
                                    </span>
                                @else
                                    <span class="badge bg-light text-dark py-1 px-2 border" style="font-size: 0.8rem;">Belum ada</span>
                                @endif
                            </td>

                            {{-- Progress Juri --}}
                            <td class="text-center">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <span class="badge {{ $allScored ? 'bg-success' : 'bg-warning text-dark' }} mb-1" style="font-size: 0.75rem; padding: 0.3rem 0.6rem;">
                                        {{ $jumlahMenilai }} / {{ $totalJuri }} Juri
                                    </span>
                                    <div class="progress-modern w-100" style="height: 4px; max-width: 80px; background: var(--border-color);">
                                        <div class="progress-bar-modern {{ $allScored ? 'bg-success' : 'bg-warning' }}" 
                                             style="height: 100%; width: {{ $totalJuri > 0 ? ($jumlahMenilai / $totalJuri) * 100 : 0 }}%;"></div>
                                    </div>
                                </div>
                            </td>

                            {{-- Actions --}}
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-danger" 
                                        wire:click="openDetailModal({{ $mhs->id }})" 
                                        title="Lihat Detail Penilaian"
                                        style="border-radius: 8px; font-weight: 600; padding: 0.4rem 0.75rem; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="fas fa-eye"></i> Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 7 + count($juris) }}" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-clipboard-list mb-3" style="font-size: 3rem; color: var(--text-muted);"></i>
                                    <h5 class="mb-1" style="font-weight: 700; color: var(--text-primary);">Tidak Ada Data Nilai</h5>
                                    <p class="mb-0">Belum ada juri yang memberikan penilaian presentasi.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($mahasiswas->hasPages())
            <div class="d-flex justify-content-end mt-4">
                {{ $mahasiswas->links() }}
            </div>
        @endif
    </div>

    {{-- Detail Modal --}}
    @if ($showDetailModal && $selectedMahasiswa)
        <div class="modal-backdrop-custom" wire:click.self="closeDetailModal">
            <div class="modal-content-custom" style="max-width: 650px;" wire:click.stop>
                <div class="modal-header-custom" style="border-bottom: 1px solid var(--border-color); padding-bottom: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <h5 class="modal-title-custom mb-1" style="font-weight: 800; font-size: 1.35rem;">
                            Rincian Penilaian Juri
                        </h5>
                        <p class="text-muted mb-0" style="font-size: 0.85rem; font-weight: 500;">
                            Mahasiswa: <strong>{{ $selectedMahasiswa->nama }}</strong> ({{ $selectedMahasiswa->nim }})
                        </p>
                    </div>
                    <button type="button" class="modal-close-btn" wire:click="closeDetailModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                {{-- Tema Info --}}
                @if ($selectedMahasiswa->tema)
                    <div class="mb-4 p-3 rounded-3" style="background: var(--hover-bg); border-left: 4px solid var(--primary-color);">
                        <small class="text-muted d-block mb-1" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Tema Presentasi</small>
                        <h6 class="mb-0 text-danger" style="font-weight: 700; font-size: 1rem;">
                            "{{ $selectedMahasiswa->tema->judul }}"
                        </h6>
                    </div>
                @endif

                {{-- Jury Feedback Cards --}}
                <div class="d-flex flex-column gap-3 overflow-y-auto mb-4" style="max-height: 380px; padding-right: 5px;">
                    @forelse ($scoresDetail as $score)
                        <div class="p-3 rounded-3 border" style="background: var(--bg-white); border-color: var(--border-color) !important;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 32px; height: 32px; background: rgba(230, 43, 30, 0.08); color: var(--primary-color);">
                                        <i class="fas fa-user-tie" style="font-size: 0.85rem;"></i>
                                    </div>
                                    <span style="font-weight: 700; color: var(--text-primary); font-size: 0.95rem;">
                                        {{ $score->juri->nama }}
                                    </span>
                                    <span class="text-muted small" style="font-size: 0.75rem;">(NIM: {{ $score->juri->nim }})</span>
                                </div>
                                <div>
                                    @php
                                        $scoreVal = $score->nilai;
                                        $scoreBg = 'rgba(230, 43, 30, 0.1)';
                                        $scoreText = 'var(--primary-color)';
                                        if ($scoreVal >= 80) {
                                            $scoreBg = 'rgba(16, 185, 129, 0.1)';
                                            $scoreText = '#10b981';
                                        } elseif ($scoreVal >= 70) {
                                            $scoreBg = 'rgba(245, 158, 11, 0.1)';
                                            $scoreText = '#f59e0b';
                                        }
                                    @endphp
                                    <span class="badge fw-bold" style="background: {{ $scoreBg }}; color: {{ $scoreText }}; font-size: 1rem; padding: 0.4rem 0.8rem; border-radius: 8px;">
                                        {{ $scoreVal }}
                                    </span>
                                </div>
                            </div>
                            
                            {{-- Notes / Catatan --}}
                            <div class="p-3 rounded-2" style="background: var(--bg-light); border: 1px solid var(--border-light);">
                                <small class="text-muted d-block mb-1" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Catatan / Feedback:</small>
                                <p class="mb-0" style="color: var(--text-secondary); font-size: 0.9rem; line-height: 1.5; font-style: {{ empty($score->catatan) ? 'italic' : 'normal' }};">
                                    {{ !empty($score->catatan) ? $score->catatan : 'Tidak ada catatan/feedback dari juri.' }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-comment-slash mb-2" style="font-size: 2rem;"></i>
                            <p class="mb-0">Belum ada juri yang memberikan nilai dan feedback.</p>
                        </div>
                    @endforelse
                </div>

                <div class="d-flex justify-content-end pt-3" style="border-top: 1px solid var(--border-color);">
                    <x-button type="button" variant="primary" wire:click="closeDetailModal">
                        Tutup
                    </x-button>
                </div>
            </div>
        </div>
    @endif
</div>
