<div>
    {{-- Page Header --}}
    <x-page-header title="Manajemen Mahasiswa" subtitle="Kelola data mahasiswa dan slide presentasi">
        <x-slot:actions>
            <x-button variant="primary" icon="fas fa-plus" wire:click="openCreateModal">
                Tambah Mahasiswa
            </x-button>
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

    {{-- Table Card --}}
    <div class="modern-card">
        {{-- Search and Filters --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">Daftar Mahasiswa</h5>
            <div class="input-group" style="max-width: 300px;">
                <span class="input-group-text" style="background: var(--input-bg); border-color: var(--border-color);">
                    <i class="fas fa-search" style="color: var(--text-muted);"></i>
                </span>
                <input type="text" class="form-control" placeholder="Cari nama atau NIM..."
                    wire:model.live.debounce.300ms="search" style="border-left: none;">
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th style="cursor: pointer;" wire:click="sortByColumn('nim')">
                            NIM
                            @if ($sortBy === 'nim')
                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1" style="font-size: 0.75rem;"></i>
                            @else
                                <i class="fas fa-sort ms-1" style="font-size: 0.75rem; opacity: 0.3;"></i>
                            @endif
                        </th>
                        <th style="cursor: pointer;" wire:click="sortByColumn('nama')">
                            Nama
                            @if ($sortBy === 'nama')
                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1" style="font-size: 0.75rem;"></i>
                            @else
                                <i class="fas fa-sort ms-1" style="font-size: 0.75rem; opacity: 0.3;"></i>
                            @endif
                        </th>
                        <th>Jumlah Slide</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mahasiswas as $mhs)
                        <tr wire:key="mhs-{{ $mhs->id }}">
                            <td style="color: var(--text-secondary); font-weight: 600;">{{ $mhs->nim }}</td>
                            <td>
                                <div class="fw-semibold" style="color: var(--text-primary);">{{ $mhs->nama }}</div>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $mhs->slide_presentasis_count }} Slide</span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="action-btn action-btn-edit" wire:click="openEditModal({{ $mhs->id }})" title="Edit Mahasiswa">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn action-btn-delete" wire:click="confirmDelete({{ $mhs->id }})" title="Hapus Mahasiswa">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-users mb-2" style="font-size: 2rem;"></i>
                                    <p class="mb-0">Tidak ada data mahasiswa</p>
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

    {{-- Create/Edit Modal --}}
    @if ($showModal)
        <div class="modal-backdrop-custom" wire:click.self="closeModal">
            <div class="modal-content-custom" wire:click.stop>
                <div class="modal-header-custom">
                    <h5 class="modal-title-custom">
                        {{ $editingId ? 'Edit Mahasiswa' : 'Tambah Mahasiswa' }}
                    </h5>
                    <button type="button" class="modal-close-btn" wire:click="closeModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit="save">
                    <div class="mb-3">
                        <label for="nim" class="form-label">NIM <span style="color: var(--danger-color);">*</span></label>
                        <input type="text" class="form-control @error('nim') is-invalid @enderror" id="nim"
                            wire:model="nim" placeholder="Masukkan NIM">
                        @error('nim')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Lengkap <span style="color: var(--danger-color);">*</span></label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                            wire:model="nama" placeholder="Masukkan nama lengkap">
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            Password
                            @if (!$editingId)
                                <span style="color: var(--danger-color);">*</span>
                            @else
                                <small class="text-muted">(kosongkan jika tidak ingin mengubah)</small>
                            @endif
                        </label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                            wire:model="password"
                            placeholder="{{ $editingId ? 'Masukkan password baru' : 'Masukkan password' }}">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="password_confirmation"
                            wire:model="password_confirmation" placeholder="Konfirmasi password">
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <x-button type="button" variant="outline" wire:click="closeModal">
                            Batal
                        </x-button>
                        <x-button type="submit" variant="primary">
                            {{ $editingId ? 'Simpan Perubahan' : 'Simpan Mahasiswa' }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    <x-confirm-modal
        :show="$showDeleteModal"
        title="Konfirmasi Hapus"
        message="Apakah Anda yakin ingin menghapus data mahasiswa ini? Tindakan ini tidak dapat dibatalkan dan akan menghapus semua slide terkait."
        on-confirm="deleteMahasiswa"
        on-cancel="cancelDelete"
        variant="danger"
        icon="fas fa-exclamation-triangle"
    >
        <x-slot:confirmButton>
            <i class="fas fa-trash-alt me-2"></i>Hapus
        </x-slot:confirmButton>
    </x-confirm-modal>
</div>
