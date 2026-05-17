<?php

namespace App\Livewire\Mahasiswa;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard Mahasiswa')]
class Dashboard extends Component
{
    #[On('slide-updated')]
    public function updateSlideCount()
    {
        // Triggers re-render to update the count
    }

    public function render()
    {
        return view('livewire.mahasiswa.dashboard', [
            'mahasiswa' => Auth::guard('mahasiswa')->user(),
        ]);
    }
}
