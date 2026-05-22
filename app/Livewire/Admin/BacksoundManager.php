<?php

namespace App\Livewire\Admin;

use App\Models\Backsound;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Kelola Backsound')]
class BacksoundManager extends Component
{
    use WithFileUploads;

    public $judul = '';

    public $file_audio;

    public $editId = null;

    public $editJudul = '';

    public $showForm = false;

    public function save()
    {
        $this->validate([
            'judul' => 'required|string|max:255',
            'file_audio' => 'required|file|mimes:mp3,wav,ogg,m4a|max:20480',
        ], [
            'judul.required' => 'Judul backsound wajib diisi.',
            'file_audio.required' => 'File audio wajib diunggah.',
            'file_audio.mimes' => 'Format file harus mp3, wav, ogg, atau m4a.',
            'file_audio.max' => 'Ukuran file maksimal 20MB.',
        ]);

        $path = $this->file_audio->store('backsounds', 'public');

        Backsound::create([
            'judul' => $this->judul,
            'file_audio' => $path,
        ]);

        $this->reset(['judul', 'file_audio', 'showForm']);
        session()->flash('success', 'Backsound berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $bs = Backsound::findOrFail($id);
        $this->editId = $bs->id;
        $this->editJudul = $bs->judul;
    }

    public function update()
    {
        $this->validate([
            'editJudul' => 'required|string|max:255',
        ], [
            'editJudul.required' => 'Judul backsound wajib diisi.',
        ]);

        $bs = Backsound::findOrFail($this->editId);
        $bs->update(['judul' => $this->editJudul]);

        $this->reset(['editId', 'editJudul']);
        session()->flash('success', 'Backsound berhasil diperbarui!');
    }

    public function cancelEdit()
    {
        $this->reset(['editId', 'editJudul']);
    }

    public function delete($id)
    {
        $bs = Backsound::find($id);
        if ($bs) {
            // Clear from presentasi_settings if currently selected
            $setting = \App\Models\PresentasiSetting::instance();
            if ($setting->current_backsound_id == $id) {
                $setting->update(['current_backsound_id' => null, 'music_playing' => false]);
            }

            Storage::disk('public')->delete($bs->file_audio);
            $bs->delete();
            session()->flash('success', 'Backsound berhasil dihapus.');
        }
    }

    public function render()
    {
        $backsounds = Backsound::orderBy('created_at', 'desc')->get();

        return view('livewire.admin.backsound-manager', [
            'backsounds' => $backsounds,
        ]);
    }
}
