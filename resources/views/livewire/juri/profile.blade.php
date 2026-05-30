<div> 
<div></div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

    <x-page-header title="Profil Juri" subtitle="Kelola foto profil dan password Anda" />

    <div class="row">
        <!-- Informasi Dasar (Read-Only) -->
        <div class="col-12 col-lg-4 mb-4">
            <div class="modern-card h-100">
                <h5 class="mb-4" style="color: var(--text-primary);">Informasi Dasar</h5>

                <div class="mb-3">
                    <label class="form-label text-muted">NIM / NIP</label>
                    <input type="text" class="form-control" value="{{ $juri->nim }}" readonly disabled style="cursor: not-allowed;">
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted">Nama Lengkap</label>
                    <input type="text" class="form-control" value="{{ $juri->nama }}" readonly disabled style="cursor: not-allowed;">
                </div>

                <p class="text-muted small mt-4">
                    <i class="fas fa-info-circle me-1"></i> Hubungi administrator jika Anda perlu mengubah informasi dasar.
                </p>
            </div>
        </div>

        <!-- Foto Profil -->
        <div class="col-12 col-lg-4 mb-4" x-data="cropImage">
            <div class="modern-card h-100">
                <h5 class="mb-4" style="color: var(--text-primary);">Foto Profil</h5>

                @if (session('success_foto'))
                    <x-alert variant="success" class="py-2 mb-3">
                        {{ session('success_foto') }}
                    </x-alert>
                @endif

                <div class="text-center mb-4">
                    @if ($juri->foto_profil)
                        <img src="{{ Storage::url($juri->foto_profil) }}" class="rounded-circle border" style="width: 120px; height: 120px; object-fit: cover;">
                    @else
                        <div class="bg-secondary rounded-circle d-flex justify-content-center align-items-center mx-auto text-white" style="width: 120px; height: 120px; font-size: 2.5rem;">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                </div>

                <div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Ubah Foto (Otomatis potong 1:1)</label>
                        <input type="file" class="form-control" id="foto-input" accept="image/*" @change="fileSelected($event)">
                    </div>
                    
                    <div wire:loading wire:target="updateFotoProfil" class="w-100 text-center text-primary mt-2">
                        <i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...
                    </div>
                </div>
            </div>

            <!-- Modal for Cropping -->
            <div class="modal fade" id="cropModal" tabindex="-1" aria-hidden="true" wire:ignore>
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content" style="background-color: var(--bg-secondary); border-color: var(--border-color);">
                        <div class="modal-header border-0">
                            <h5 class="modal-title" style="color: var(--text-primary);">Sesuaikan Foto (1:1)</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <div style="max-height: 400px; width: 100%;">
                                <img id="image-to-crop" src="" style="max-width: 100%;">
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-primary" @click="cropAndSave()">Potong & Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <script>
                function initJuriCropImage() {
                    if (typeof Alpine !== 'undefined' && Alpine.data) {
                        if (!Alpine.data('cropImage')) {
                            Alpine.data('cropImage', () => ({
                                cropper: null,
                                fileSelected(event) {
                                    const files = event.target.files;
                                    if (files && files.length > 0) {
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            const imageToCrop = document.getElementById('image-to-crop');
                                            imageToCrop.src = e.target.result;
                                            
                                            if (this.cropper) {
                                                this.cropper.destroy();
                                            }
                                            
                                            const cropModal = new bootstrap.Modal(document.getElementById('cropModal'));
                                            cropModal.show();

                                            document.getElementById('cropModal').addEventListener('shown.bs.modal', () => {
                                                this.cropper = new Cropper(imageToCrop, {
                                                    aspectRatio: 1,
                                                    viewMode: 1,
                                                    autoCropArea: 1,
                                                });
                                            }, { once: true });
                                        };
                                        reader.readAsDataURL(files[0]);
                                    }
                                },
                                cropAndSave() {
                                    if (this.cropper) {
                                        const canvas = this.cropper.getCroppedCanvas({
                                            width: 400,
                                            height: 400,
                                        });
                                        
                                        const base64Image = canvas.toDataURL('image/jpeg');
                                        
                                        // Assign to livewire property and trigger method
                                        @this.set('cropped_foto', base64Image);
                                        @this.call('updateFotoProfil');
                                        
                                        // Close modal
                                        const cropModal = bootstrap.Modal.getInstance(document.getElementById('cropModal'));
                                        cropModal.hide();
                                        
                                        // Reset input
                                        document.getElementById('foto-input').value = '';
                                    }
                                }
                            }));
                        }
                    }
                }

                if (window.Alpine) {
                    initJuriCropImage();
                } else {
                    document.addEventListener('alpine:init', initJuriCropImage);
                }
            </script>
        </div>

        <!-- Ubah Password -->
        <div class="col-12 col-lg-4 mb-4">
            <div class="modern-card h-100">
                <h5 class="mb-4" style="color: var(--text-primary);">Ubah Password</h5>

                @if (session('success_password'))
                    <x-alert variant="success" class="py-2 mb-3">
                        {{ session('success_password') }}
                    </x-alert>
                @endif

                <form wire:submit.prevent="updatePassword">
                    <div class="mb-3">
                        <label class="form-label text-muted">Password Saat Ini</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" wire:model="current_password" placeholder="Masukkan password saat ini">
                        @error('current_password') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Password Baru</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" wire:model="password" placeholder="Masukkan password baru">
                        @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" wire:model="password_confirmation" placeholder="Ulangi password baru">
                    </div>

                    <button type="submit" class="btn btn-primary w-100" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="updatePassword">Perbarui Password</span>
                        <span wire:loading wire:target="updatePassword"><i class="fas fa-spinner fa-spin me-2"></i>Memperbarui...</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

</div>
