<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Login Mahasiswa - KIPTEDX')]
class MahasiswaLogin extends Component
{
    #[Rule(['required', 'string'])]
    public string $nim = '';

    #[Rule(['required', 'string'])]
    public string $password = '';

    public bool $remember = false;

    public function submit()
    {
        $this->validate();

        $credentials = [
            'nim' => $this->nim,
            'password' => $this->password,
        ];

        if (Auth::guard('mahasiswa')->attempt($credentials, $this->remember)) {
            session()->regenerate();
            return redirect()->to(route('mahasiswa.dashboard'));
        }

        $this->addError('nim', 'NIM atau Password salah.');
    }

    public function render()
    {
        return view('livewire.auth.mahasiswa-login')
            ->layoutData(['type' => 'auth']);
    }
}
