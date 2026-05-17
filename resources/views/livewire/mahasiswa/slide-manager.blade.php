<div>
    <x-page-header title="Kelola Slide Presentasi" subtitle="Pilih tema dan unggah slide presentasi Anda" />

    {{-- Pemilihan Tema --}}
    <div class="modern-card mb-4">
        <h5 class="mb-3" style="color: var(--text-primary);">Pilih Tema Presentasi</h5>
        
        @if (session('success_tema'))
            <x-alert variant="success" class="py-2 mb-3">
                {{ session('success_tema') }}
            </x-alert>
        @endif

        <form wire:submit.prevent="saveTema" class="d-flex flex-column flex-md-row gap-3 align-items-md-end">
            <div class="flex-grow-1">
                <label for="tema" class="form-label text-muted">Tema Pilihan Anda</label>
                <select class="form-select @error('selected_tema') is-invalid @enderror" id="tema" wire:model="selected_tema">
                    <option value="">-- Pilih Tema --</option>
                    @foreach ($temas as $tema)
                        <option value="{{ $tema->id }}">{{ $tema->judul }}</option>
                    @endforeach
                </select>
                @error('selected_tema') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            <div>
                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="saveTema">
                    <span wire:loading.remove wire:target="saveTema">Simpan Tema</span>
                    <span wire:loading wire:target="saveTema"><i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
    
    @if ($uploadedCount < 3)
        <div class="alert alert-warning d-flex align-items-center mb-4 border-0" style="background-color: rgba(255, 193, 7, 0.1); color: #ffc107;" role="alert">
            <i class="fas fa-exclamation-triangle me-3 fs-4"></i>
            <div>
                <strong>Perhatian!</strong> Anda baru mengunggah <strong>{{ $uploadedCount }}</strong> slide. Diwajibkan untuk mengunggah minimal <strong>3 slide</strong> (maksimal 5 slide).
            </div>
        </div>
    @elseif ($uploadedCount == 5)
        <div class="alert alert-success d-flex align-items-center mb-4 border-0" style="background-color: rgba(40, 167, 69, 0.1); color: #28a745;" role="alert">
            <i class="fas fa-check-circle me-3 fs-4"></i>
            <div>
                <strong>Luar Biasa!</strong> Anda telah mengunggah kuota maksimal <strong>5 slide</strong>.
            </div>
        </div>
    @else
        <div class="alert alert-info d-flex align-items-center mb-4 border-0" style="background-color: rgba(23, 162, 184, 0.1); color: #17a2b8;" role="alert">
            <i class="fas fa-info-circle me-3 fs-4"></i>
            <div>
                Anda telah mengunggah <strong>{{ $uploadedCount }}</strong> slide. Anda masih dapat menambah hingga total 5 slide.
            </div>
        </div>
    @endif

    <div class="row">
        @for ($i = 1; $i <= 5; $i++)
            <div class="col-12 col-md-6 mb-4">
                <div class="modern-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0" style="color: var(--text-primary);">Slide #{{ $i }}</h5>
                        @if ($existing_slides[$i])
                            <span class="badge bg-success">Tersimpan</span>
                        @else
                            <span class="badge bg-secondary">Kosong</span>
                        @endif
                    </div>
                    
                    @if (session('success_slide_' . $i))
                        <x-alert variant="success" class="py-2 mb-3">
                            {{ session('success_slide_' . $i) }}
                        </x-alert>
                    @endif

                    @if ($existing_slides[$i])
                        <!-- Show existing slide -->
                        <div class="mb-3 text-center">
                            <img src="{{ Storage::url($existing_slides[$i]->file_gambar) }}" alt="Slide {{ $i }}" class="img-fluid rounded border" style="max-height: 200px; object-fit: cover;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Judul Slide</label>
                            <p class="fw-semibold" style="color: var(--text-primary);">{{ $existing_slides[$i]->judul_slide }}</p>
                        </div>
                        <div class="d-flex gap-2 mt-auto">
                            <button type="button" class="btn btn-sm btn-outline-danger w-100" wire:click="deleteSlide({{ $i }})" onclick="confirm('Yakin ingin menghapus slide ini?') || event.stopImmediatePropagation()">
                                <i class="fas fa-trash-alt me-1"></i> Hapus
                            </button>
                        </div>
                    @else
                        <!-- Form to upload slide -->
                        <form wire:submit.prevent="saveSlide({{ $i }})" class="d-flex flex-column h-100">
                            <div class="mb-3">
                                <label class="form-label">Judul Slide <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('judul_slides.'.$i) is-invalid @enderror" wire:model="judul_slides.{{ $i }}" placeholder="Contoh: Pendahuluan">
                                @error('judul_slides.'.$i) <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">File Gambar <span class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('file_gambars.'.$i) is-invalid @enderror" wire:model="file_gambars.{{ $i }}" accept="image/*">
                                @error('file_gambars.'.$i) <span class="text-danger small">{{ $message }}</span> @enderror
                                <div wire:loading wire:target="file_gambars.{{ $i }}" class="mt-2 text-primary small">
                                    <i class="fas fa-spinner fa-spin"></i> Mengunggah...
                                </div>
                            </div>
                            
                            @if (isset($file_gambars[$i]) && method_exists($file_gambars[$i], 'temporaryUrl'))
                                <div class="mb-3 text-center">
                                    <p class="text-muted small mb-1">Preview:</p>
                                    <img src="{{ $file_gambars[$i]->temporaryUrl() }}" class="img-fluid rounded border" style="max-height: 150px; object-fit: cover;">
                                </div>
                            @endif

                            <div class="mt-auto">
                                <button type="submit" class="btn btn-primary w-100" wire:loading.attr="disabled" wire:target="saveSlide({{ $i }}), file_gambars.{{ $i }}">
                                    <span wire:loading.remove wire:target="saveSlide({{ $i }})">
                                        <i class="fas fa-save me-1"></i> Simpan Slide {{ $i }}
                                    </span>
                                    <span wire:loading wire:target="saveSlide({{ $i }})">
                                        <i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...
                                    </span>
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        @endfor
    </div>
</div>
