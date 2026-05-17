<div>
    <x-page-header title="Manajemen Tema" subtitle="Kelola tema acara TEDx" />

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-alert variant="success" title="Sukses!" class="mb-4">
            {{ session('success') }}
        </x-alert>
    @endif

    <div class="row">
        {{-- Form Card --}}
        <div class="col-12 col-md-4 mb-4">
            <div class="modern-card">
                <h5 class="mb-4" style="color: var(--text-primary); font-weight: 600;">
                    {{ $editingId ? 'Edit Tema' : 'Tambah Tema' }}
                </h5>

                <form wire:submit.prevent="save">
                    <div class="mb-3">
                        <label for="judul" class="form-label text-muted">Judul Tema</label>
                        <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" wire:model="judul" placeholder="Masukkan judul tema">
                        @error('judul') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="d-flex gap-2">
                        @if ($editingId)
                            <button type="button" class="btn btn-outline-secondary w-50" wire:click="resetForm">Batal</button>
                            <button type="submit" class="btn btn-primary w-50">Update</button>
                        @else
                            <button type="submit" class="btn btn-primary w-100">Simpan Tema</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- List Card --}}
        <div class="col-12 col-md-8 mb-4">
            <div class="modern-card">
                <h5 class="mb-4" style="color: var(--text-primary); font-weight: 600;">Daftar Tema</h5>

                <div class="table-responsive">
                    <table class="table table-modern align-middle">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Judul Tema</th>
                                <th style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($temas as $index => $tema)
                                <tr wire:key="tema-{{ $tema->id }}">
                                    <td class="text-muted">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="fw-semibold" style="color: var(--text-primary);">{{ $tema->judul }}</div>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="action-btn action-btn-edit" wire:click="edit({{ $tema->id }})" title="Edit Tema">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="action-btn action-btn-delete" wire:click="delete({{ $tema->id }})" title="Hapus Tema" onclick="confirm('Apakah Anda yakin ingin menghapus tema ini?') || event.stopImmediatePropagation()">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-tags mb-2" style="font-size: 2rem;"></i>
                                            <p class="mb-0">Tidak ada data tema</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
