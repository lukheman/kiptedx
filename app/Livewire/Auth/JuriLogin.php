<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Login Juri - KIP TALKS')]
class JuriLogin extends Component
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

        if (Auth::guard('juri')->attempt($credentials, $this->remember)) {
            session()->regenerate();

            return redirect()->to(route('juri.presentasi'));
        }

        $this->addError('nim', 'NIM/NIP atau Password salah.');
    }

    public function render()
    {
        return view('livewire.auth.juri-login')
            ->layoutData(['type' => 'auth']);
    }
}
