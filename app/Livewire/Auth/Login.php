<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Login - KIP TALKS')]
class Login extends Component
{
    public string $role = 'mahasiswa'; // default tab

    public string $email = '';

    public string $nim = '';

    public string $password = '';

    public bool $remember = false;

    public function updatedRole()
    {
        $this->resetErrorBag();
        $this->email = '';
        $this->nim = '';
        $this->password = '';
    }

    public function submit()
    {
        if ($this->role === 'admin') {
            return $this->loginAdmin();
        } elseif ($this->role === 'juri') {
            return $this->loginJuri();
        } else {
            return $this->loginMahasiswa();
        }
    }

    protected function loginAdmin()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();

            return redirect()->to(route('dashboard'));
        }

        $this->addError('email', 'Email atau password salah.');
    }

    protected function loginMahasiswa()
    {
        $this->validate([
            'nim' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::guard('mahasiswa')->attempt(['nim' => $this->nim, 'password' => $this->password], $this->remember)) {
            session()->regenerate();

            return redirect()->to(route('mahasiswa.dashboard'));
        }

        $this->addError('nim', 'NIM atau Password salah.');
    }

    protected function loginJuri()
    {
        $this->validate([
            'nim' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::guard('juri')->attempt(['nim' => $this->nim, 'password' => $this->password], $this->remember)) {
            session()->regenerate();

            return redirect()->to(route('juri.presentasi'));
        }

        $this->addError('nim', 'NIM/NIP atau Password salah.');
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layoutData(['type' => 'auth']);
    }
}
