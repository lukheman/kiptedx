<?php

namespace App\Livewire\Mahasiswa;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Profil Mahasiswa')]
class Profile extends Component
{
    use WithFileUploads;

    public $mahasiswa;

    public $cropped_foto;

    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount()
    {
        $this->mahasiswa = Auth::guard('mahasiswa')->user();
    }

    public function updateFotoProfil()
    {
        if ($this->cropped_foto) {
            $image_parts = explode(';base64,', $this->cropped_foto);
            $image_type_aux = explode('image/', $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);

            // Generate filename based on random string and extension
            $extension = str_replace('jpeg', 'jpg', $image_type);
            $fileName = 'profiles/'.uniqid().'.'.$extension;
            Storage::disk('public')->put($fileName, $image_base64);

            if ($this->mahasiswa->foto_profil) {
                Storage::disk('public')->delete($this->mahasiswa->foto_profil);
            }

            $this->mahasiswa->foto_profil = $fileName;
            $this->mahasiswa->save();

            $this->cropped_foto = null;
            session()->flash('success_foto', 'Foto profil berhasil diperbarui.');
            $this->dispatch('profile-updated'); // trigger topbar refresh if needed
        }
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if (! Hash::check($this->current_password, $this->mahasiswa->password)) {
            $this->addError('current_password', 'Password saat ini salah.');

            return;
        }

        $this->mahasiswa->password = Hash::make($this->password);
        $this->mahasiswa->save();

        $this->reset(['current_password', 'password', 'password_confirmation']);
        session()->flash('success_password', 'Password berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.mahasiswa.profile');
    }
}
