<div>
    <x-page-header title="Dashboard Mahasiswa" subtitle="Selamat datang di portal mahasiswa KIP TALKS">
    </x-page-header>

    <div class="row">
        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="modern-card">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="icon-wrapper bg-primary text-white p-3 rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h5 class="mb-0" style="color: var(--text-primary);">Profil Anda</h5>
                        <p class="text-muted mb-0">Informasi Dasar</p>
                    </div>
                </div>
                <hr style="border-color: var(--border-color);">
                <div class="mb-2">
                    <strong style="color: var(--text-secondary);">Nama:</strong>
                    <span style="color: var(--text-primary);">{{ $mahasiswa->nama }}</span>
                </div>
                <div>
                    <strong style="color: var(--text-secondary);">NIM:</strong>
                    <span style="color: var(--text-primary);">{{ $mahasiswa->nim }}</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="modern-card">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="icon-wrapper bg-danger text-white p-3 rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-images"></i>
                    </div>
                    <div>
                        <h5 class="mb-0" style="color: var(--text-primary);">Slide Presentasi</h5>
                        <p class="text-muted mb-0">Jumlah slide Anda</p>
                    </div>
                </div>
                <hr style="border-color: var(--border-color);">
                <div class="text-center py-3">
                    <h2 class="mb-0" style="color: var(--text-primary); font-size: 2.5rem;">{{ $mahasiswa->slidePresentasis()->count() }}</h2>
                    <p class="text-muted">Slide Diunggah</p>
                </div>
            </div>
        </div>
    </div>
</div>
