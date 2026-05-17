<?php

namespace App\Livewire\Admin;

use App\Models\Juri;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Manajemen Juri')]
class JuriManager extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    public string $sortBy = 'nim';

    public string $sortDirection = 'asc';

    public string $nim = '';

    public string $nama = '';

    public string $password = '';

    public string $password_confirmation = '';

    public ?int $editingId = null;

    public bool $showModal = false;

    public bool $showDeleteModal = false;

    public ?int $deletingId = null;

    protected function rules(): array
    {
        $rules = [
            'nama' => ['required', 'string', 'max:255'],
        ];

        if ($this->editingId) {
            $rules['nim'] = ['required', 'string', 'max:50', 'unique:juris,nim,'.$this->editingId];
            if ($this->password) {
                $rules['password'] = ['confirmed', Password::defaults()];
            }
        } else {
            $rules['nim'] = ['required', 'string', 'max:50', 'unique:juris,nim'];
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        }

        return $rules;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->editingId = null;
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $juri = Juri::findOrFail($id);
        $this->editingId = $id;
        $this->nim = $juri->nim;
        $this->nama = $juri->nama;
        $this->password = '';
        $this->password_confirmation = '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->editingId) {
            $juri = Juri::findOrFail($this->editingId);
            $juri->nim = $validated['nim'];
            $juri->nama = $validated['nama'];

            if (! empty($this->password)) {
                $juri->password = Hash::make($this->password);
            }

            $juri->save();
            session()->flash('success', 'Data Juri berhasil diperbarui.');
        } else {
            Juri::create([
                'nim' => $validated['nim'],
                'nama' => $validated['nama'],
                'password' => Hash::make($validated['password']),
            ]);
            session()->flash('success', 'Data Juri berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteJuri(): void
    {
        if ($this->deletingId) {
            Juri::destroy($this->deletingId);
            session()->flash('success', 'Data Juri berhasil dihapus.');
        }

        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    protected function resetForm(): void
    {
        $this->nim = '';
        $this->nama = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->editingId = null;
    }

    public function sortByColumn(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function render()
    {
        $juris = Juri::query()
            ->when($this->search, function ($query) {
                $query->where('nama', 'like', '%'.$this->search.'%')
                    ->orWhere('nim', 'like', '%'.$this->search.'%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.juri-manager', [
            'juris' => $juris,
        ]);
    }
}
