<?php

namespace App\Livewire\Admin;

use App\Models\Tema;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Manajemen Tema')]
class TemaManager extends Component
{
    public $temas;
    public $judul;
    public $editingId = null;

    protected $rules = [
        'judul' => 'required|string|max:255',
    ];

    public function mount()
    {
        $this->loadTemas();
    }

    public function loadTemas()
    {
        $this->temas = Tema::all();
    }

    public function create()
    {
        $this->resetForm();
    }

    public function edit($id)
    {
        $tema = Tema::findOrFail($id);
        $this->editingId = $id;
        $this->judul = $tema->judul;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $tema = Tema::findOrFail($this->editingId);
            $tema->update(['judul' => $this->judul]);
            session()->flash('success', 'Tema berhasil diupdate.');
        } else {
            Tema::create(['judul' => $this->judul]);
            session()->flash('success', 'Tema berhasil ditambahkan.');
        }

        $this->loadTemas();
        $this->resetForm();
    }

    public function delete($id)
    {
        Tema::findOrFail($id)->delete();
        $this->loadTemas();
        session()->flash('success', 'Tema berhasil dihapus.');
    }

    public function resetForm()
    {
        $this->judul = '';
        $this->editingId = null;
    }

    public function render()
    {
        return view('livewire.admin.tema-manager');
    }
}
