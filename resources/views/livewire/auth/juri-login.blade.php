<div class="login-container">
    <div class="login-card">
        <!-- Brand Logo -->
        <div class="brand-logo">
            <div class="icon-wrapper">
                <i class="fas fa-user-tie"></i>
            </div>
            <h1>Login Juri</h1>
            <p>Panel penilaian presentasi KIP TALKS</p>
        </div>

        <!-- Login Form -->
        <form wire:submit="submit">
            <!-- NIM/NIP Field -->
            <div class="form-floating position-relative">
                <i class="fas fa-id-card input-icon"></i>
                <input type="text" wire:model="nim" class="form-control @error('nim') is-invalid @enderror"
                    id="nim" placeholder="NIM / NIP" autofocus>
                <label for="nim">NIM / NIP</label>
                @error('nim')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password Field -->
            <div class="form-floating position-relative">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" wire:model="password"
                    class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Password">
                <label for="password">Password</label>
                <button type="button" class="password-toggle" onclick="togglePasswordJuri()">
                    <i class="fas fa-eye" id="toggleIconJuri"></i>
                </button>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <script>
                function togglePasswordJuri() {
                    const input = document.getElementById('password');
                    const icon = document.getElementById('toggleIconJuri');
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.replace('fa-eye', 'fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.replace('fa-eye-slash', 'fa-eye');
                    }
                }
            </script>

            <!-- Remember Me -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" wire:model="remember" id="remember">
                    <label class="form-check-label" for="remember">Ingat Saya</label>
                </div>
            </div>

            <!-- Login Button -->
            <button type="submit" class="btn btn-login" wire:loading.attr="disabled">
                <span wire:loading.remove>Masuk <i class="fas fa-arrow-right"></i></span>
                <span wire:loading>
                    <i class="fas fa-spinner fa-spin me-2"></i> Memproses...
                </span>
            </button>
        </form>

        <!-- Divider -->
        <div class="divider">
            <span>Portal Lain</span>
        </div>

        <div class="signup-link">
            <a href="{{ route('mahasiswa.login') }}">Login Mahasiswa</a> &middot; <a href="{{ route('login') }}">Login Admin</a>
        </div>
    </div>
</div>
