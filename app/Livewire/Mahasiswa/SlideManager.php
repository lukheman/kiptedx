<?php

namespace App\Livewire\Mahasiswa;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use App\Models\SlidePresentasi;
use App\Models\Tema;

#[Title('Tema dan Persentase')]
class SlideManager extends Component
{
    use WithFileUploads;

    public $judul_slides = [];
    public $file_gambars = [];
    public $existing_slides = [];
    public $uploadedCount = 0;
    public $selected_tema = null;

    public function mount()
    {
        $this->loadSlides();
        $this->selected_tema = Auth::guard('mahasiswa')->user()->tema_id;
    }

    public function loadSlides()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        $slides = $mahasiswa->slidePresentasis()->get()->keyBy('urutan');
        $this->uploadedCount = $slides->count();

        for ($i = 1; $i <= 5; $i++) {
            if ($slides->has($i)) {
                $this->existing_slides[$i] = $slides[$i];
                $this->judul_slides[$i] = $slides[$i]->judul_slide;
            } else {
                $this->existing_slides[$i] = null;
                $this->judul_slides[$i] = '';
            }
        }
    }

    public function saveSlide($urutan)
    {
        $this->validate([
            "judul_slides.$urutan" => 'required|string|max:255',
            "file_gambars.$urutan" => 'required|image|max:2048', // max 2MB
        ], [
            "judul_slides.$urutan.required" => 'Judul slide wajib diisi.',
            "file_gambars.$urutan.required" => 'File gambar wajib diunggah.',
            "file_gambars.$urutan.image" => 'File harus berupa gambar.',
            "file_gambars.$urutan.max" => 'Ukuran gambar maksimal 2MB.',
        ]);

        $mahasiswa = Auth::guard('mahasiswa')->user();
        $path = $this->file_gambars[$urutan]->store('slides', 'public');

        // Delete old file if exists
        $slide = SlidePresentasi::where('mahasiswa_id', $mahasiswa->id)->where('urutan', $urutan)->first();
        if ($slide && $slide->file_gambar) {
            Storage::disk('public')->delete($slide->file_gambar);
        }

        SlidePresentasi::updateOrCreate(
            ['mahasiswa_id' => $mahasiswa->id, 'urutan' => $urutan],
            ['judul_slide' => $this->judul_slides[$urutan], 'file_gambar' => $path]
        );

        $this->file_gambars[$urutan] = null;
        $this->loadSlides();

        session()->flash('success_slide_' . $urutan, 'Slide berhasil disimpan.');
        $this->dispatch('slide-updated');
    }

    public function deleteSlide($urutan)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        $slide = SlidePresentasi::where('mahasiswa_id', $mahasiswa->id)->where('urutan', $urutan)->first();

        if ($slide) {
            if ($slide->file_gambar) {
                Storage::disk('public')->delete($slide->file_gambar);
            }
            $slide->delete();
        }

        $this->judul_slides[$urutan] = '';
        $this->loadSlides();
        session()->flash('success_slide_' . $urutan, 'Slide berhasil dihapus.');
        $this->dispatch('slide-updated');
    }

    public function saveTema()
    {
        $this->validate([
            'selected_tema' => 'required|exists:temas,id',
        ], [
            'selected_tema.required' => 'Silakan pilih tema terlebih dahulu.',
            'selected_tema.exists' => 'Tema yang dipilih tidak valid.',
        ]);

        $mahasiswa = Auth::guard('mahasiswa')->user();
        $mahasiswa->tema_id = $this->selected_tema;
        $mahasiswa->save();

        session()->flash('success_tema', 'Tema berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.mahasiswa.slide-manager', [
            'temas' => Tema::all(),
        ]);
    }
}
