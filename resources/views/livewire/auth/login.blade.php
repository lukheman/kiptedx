<div class="login-container">
    <div class="login-card">
        <!-- Brand Logo -->
        <div class="brand-logo">
            <div class="icon-wrapper">
                <i class="fas fa-layer-group"></i>
            </div>
            <h1>KIP TALKS</h1>
            <p>Masuk ke portal Anda</p>
        </div>

        <!-- Role Tabs -->
        <div class="role-tabs mb-4">
            <button type="button" class="role-tab {{ $role === 'mahasiswa' ? 'active' : '' }}"
                wire:click="$set('role', 'mahasiswa')">
                <i class="fas fa-user-graduate"></i><span>Mahasiswa</span>
            </button>
            <button type="button" class="role-tab {{ $role === 'juri' ? 'active' : '' }}"
                wire:click="$set('role', 'juri')">
                <i class="fas fa-user-tie"></i><span>Juri</span>
            </button>
            <button type="button" class="role-tab {{ $role === 'admin' ? 'active' : '' }}"
                wire:click="$set('role', 'admin')">
                <i class="fas fa-user-shield"></i><span>Admin</span>
            </button>
        </div>

        <!-- Login Form -->
        <form wire:submit.prevent="submit">
            @if ($role === 'admin')
                <!-- Email Field (Admin) -->
                <div class="form-floating position-relative" wire:key="admin-email-field">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" wire:model="email" class="form-control @error('email') is-invalid @enderror"
                        id="email" placeholder="Email Address" autofocus>
                    <label for="email">Email</label>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @else
                <!-- NIM/NIP Field (Mahasiswa & Juri) -->
                <div class="form-floating position-relative" wire:key="non-admin-nim-field-{{ $role }}">
                    <i class="fas fa-id-card input-icon"></i>
                    <input type="text" wire:model="nim" class="form-control @error('nim') is-invalid @enderror"
                        id="nim" placeholder="{{ $role === 'juri' ? 'NIM / EMAIL' : 'NIM' }}" autofocus>
                    <label for="nim">{{ $role === 'juri' ? 'NIM / EMAIL' : 'NIM' }}</label>
                    @error('nim')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @endif

            <!-- Password Field -->
            <div class="form-floating position-relative" wire:key="password-field">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" wire:model="password"
                    class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Password">
                <label for="password">Password</label>
                <button type="button" class="password-toggle" onclick="togglePassword()">
                    <i class="fas fa-eye" id="toggleIcon"></i>
                </button>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <script>
                function togglePassword() {
                    const input = document.getElementById('password');
                    const icon = document.getElementById('toggleIcon');
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
                <span wire:loading.remove>
                    Masuk <i class="fas fa-arrow-right"></i>
                </span>
                <span wire:loading>
                    <i class="fas fa-spinner fa-spin me-2"></i> Memproses...
                </span>
            </button>
        </form>
    </div>

    <style>
        .role-tabs {
            display: flex;
            gap: 4px;
            background: var(--bg-light);
            border-radius: 12px;
            padding: 4px;
            border: 1px solid var(--border-color);
        }
        .role-tab {
            flex: 1;
            padding: 0.5rem 0.25rem;
            border: none;
            border-radius: 10px;
            background: transparent;
            color: var(--text-muted);
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
            white-space: nowrap;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }
        .role-tab i {
            font-size: 1.1rem;
        }
        .role-tab:hover {
            color: var(--text-primary);
            background: rgba(0,0,0,0.03);
        }
        [data-theme="dark"] .role-tab:hover {
            background: rgba(255,255,255,0.05);
        }
        .role-tab.active {
            background: var(--primary-color);
            color: white;
            box-shadow: 0 4px 10px rgba(230, 43, 30, 0.3);
        }

        @media (min-width: 576px) {
            .role-tabs {
                gap: 6px;
                padding: 6px;
            }
            .role-tab {
                padding: 0.6rem 0.5rem;
                font-size: 0.85rem;
                flex-direction: row;
                gap: 6px;
            }
            .role-tab i {
                font-size: 1rem;
            }
        }
    </style>
</div>
