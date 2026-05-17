<?php

namespace App\Livewire\Mahasiswa;

use App\Models\SlidePresentasi;
use App\Models\Tema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Tema dan Persentase')]
class SlideManager extends Component
{
    use WithFileUploads;

    public $slides = [];

    public $existing_slides = [];

    public $uploadedCount = 0;

    public $selected_tema = null;

    public $maxSlides = 5;

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

        for ($i = 0; $i < $this->maxSlides; $i++) {
            if (isset($slides[$i])) {
                $this->existing_slides[$i] = $slides[$i];
            } else {
                $this->existing_slides[$i] = null;
            }
        }
    }

    public function saveSlide($urutan)
    {
        $this->validate([
            "slides.$urutan" => 'required|image|max:2048',
        ], [
            "slides.$urutan.required" => 'File gambar wajib diunggah.',
            "slides.$urutan.image" => 'File harus berupa gambar.',
            "slides.$urutan.max" => 'Ukuran file maksimal 2MB.',
        ]);

        $mahasiswa = Auth::guard('mahasiswa')->user();
        $path = $this->slides[$urutan]->store('slides', 'public');

        // Delete old file if exists
        $slide = SlidePresentasi::where('mahasiswa_id', $mahasiswa->id)->where('urutan', $urutan)->first();
        if ($slide && $slide->file_gambar) {
            Storage::disk('public')->delete($slide->file_gambar);
        }

        SlidePresentasi::updateOrCreate(
            ['mahasiswa_id' => $mahasiswa->id, 'urutan' => $urutan],
            ['file_gambar' => $path]
        );

        $this->slides[$urutan] = null;
        $this->loadSlides();

        session()->flash('success_slide_'.$urutan, 'Slide berhasil disimpan.');
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

        unset($this->slides[$urutan]);
        $this->loadSlides();
        session()->flash('success_slide_'.$urutan, 'Slide berhasil dihapus.');
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
