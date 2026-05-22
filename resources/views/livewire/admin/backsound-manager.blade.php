<div>
    <x-page-header title="Kelola Backsound" subtitle="Upload dan kelola musik latar untuk presentasi">
        <x-slot:actions>
            <x-button variant="primary" icon="fas fa-plus" wire:click="$toggle('showForm')">
                {{ $showForm ? 'Tutup Form' : 'Tambah Backsound' }}
            </x-button>
        </x-slot:actions>
    </x-page-header>

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-alert variant="success" title="Sukses!" class="mb-4">{{ session('success') }}</x-alert>
    @endif

    {{-- Upload Form --}}
    @if ($showForm)
        <div class="modern-card mb-4" style="border: 2px solid var(--primary-color);">
            <h5 class="mb-4" style="color: var(--text-primary); font-weight: 600;">
                <i class="fas fa-upload me-2" style="color: var(--primary-color);"></i>Upload Backsound Baru
            </h5>
            <form wire:submit.prevent="save">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label text-muted">Judul Backsound <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('judul') is-invalid @enderror"
                            wire:model="judul" placeholder="Contoh: Instrumental Piano...">
                        @error('judul') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-5">
                        <label class="form-label text-muted">File Audio <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('file_audio') is-invalid @enderror"
                            wire:model="file_audio" accept=".mp3,.wav,.ogg,.m4a">
                        @error('file_audio') <span class="text-danger small">{{ $message }}</span> @enderror
                        <div wire:loading wire:target="file_audio" class="mt-1">
                            <small class="text-primary"><i class="fas fa-spinner fa-spin me-1"></i>Mengunggah file...</small>
                        </div>
                        <small class="text-muted">Format: MP3, WAV, OGG, M4A. Maks 20MB.</small>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100"
                            wire:loading.attr="disabled" wire:target="save, file_audio">
                            <span wire:loading.remove wire:target="save">
                                <i class="fas fa-save me-1"></i>Simpan
                            </span>
                            <span wire:loading wire:target="save">
                                <i class="fas fa-spinner fa-spin me-1"></i>
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    @endif

    {{-- Backsound List --}}
    <div class="modern-card">
        <h5 class="mb-4" style="color: var(--text-primary); font-weight: 600;">
            <i class="fas fa-list-music me-2" style="color: var(--primary-color);"></i>Daftar Backsound
            <span class="badge bg-secondary ms-2" style="font-size: 0.75rem;">{{ $backsounds->count() }}</span>
        </h5>

        @if ($backsounds->count() > 0)
            <div class="table-responsive">
                <table class="table table-modern align-middle">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Judul</th>
                            <th style="width: 200px;">Preview</th>
                            <th style="width: 160px;">Tanggal Upload</th>
                            <th style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($backsounds as $idx => $bs)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center justify-content-center"
                                        style="width: 36px; height: 36px; border-radius: 8px; background: rgba(230,43,30,0.08);">
                                        <i class="fas fa-music" style="color: var(--primary-color); font-size: 0.85rem;"></i>
                                    </div>
                                </td>
                                <td>
                                    @if ($editId === $bs->id)
                                        <form wire:submit.prevent="update" class="d-flex gap-2 align-items-center">
                                            <input type="text" class="form-control form-control-sm @error('editJudul') is-invalid @enderror"
                                                wire:model="editJudul" style="max-width: 300px;">
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="cancelEdit">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                        @error('editJudul') <small class="text-danger d-block">{{ $message }}</small> @enderror
                                    @else
                                        <span class="fw-semibold" style="color: var(--text-primary);">{{ $bs->judul }}</span>
                                    @endif
                                </td>
                                <td>
                                    <audio controls preload="none" style="height: 32px; width: 180px;">
                                        <source src="{{ Storage::url($bs->file_audio) }}" type="audio/mpeg">
                                    </audio>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-alt me-1"></i>{{ $bs->created_at->format('d M Y, H:i') }}
                                    </small>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        @if ($editId !== $bs->id)
                                            <button class="btn btn-sm btn-outline-primary" wire:click="edit({{ $bs->id }})" title="Edit Judul">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        @endif
                                        <button class="btn btn-sm btn-outline-danger" wire:click="delete({{ $bs->id }})"
                                            onclick="confirm('Hapus backsound \"{{ $bs->judul }}\"?') || event.stopImmediatePropagation()" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <div style="font-size: 4rem; color: var(--text-muted); margin-bottom: 1rem;">
                    <i class="fas fa-music"></i>
                </div>
                <h5 style="color: var(--text-primary); font-weight: 600;">Belum Ada Backsound</h5>
                <p class="text-muted mb-3">Upload musik latar untuk diputar saat presentasi berlangsung.</p>
                <button class="btn btn-primary" wire:click="$set('showForm', true)">
                    <i class="fas fa-plus me-2"></i>Tambah Backsound Pertama
                </button>
            </div>
        @endif
    </div>

    <style>
        audio::-webkit-media-controls-panel {
            background: var(--hover-bg);
            border-radius: 8px;
        }
    </style>
</div>
