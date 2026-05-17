<?php

namespace App\Livewire\Admin;

use App\Models\Mahasiswa;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Atur Urutan Tampil')]
class UrutanManager extends Component
{
    public $mahasiswas = [];

    public function mount()
    {
        $this->loadMahasiswas();
    }

    public function loadMahasiswas()
    {
        $this->mahasiswas = Mahasiswa::orderByRaw('urutan_tampil IS NULL, urutan_tampil ASC')
            ->orderBy('nama')
            ->get()
            ->map(function ($mhs) {
                return [
                    'id' => $mhs->id,
                    'nim' => $mhs->nim,
                    'nama' => $mhs->nama,
                    'foto_profil' => $mhs->foto_profil ? \Illuminate\Support\Facades\Storage::url($mhs->foto_profil) : null,
                    'urutan_tampil' => $mhs->urutan_tampil,
                    'urutan_dikunci' => $mhs->urutan_dikunci,
                    'slide_count' => $mhs->slidePresentasis()->count(),
                ];
            })
            ->toArray();
    }

    public function updateUrutan($mahasiswaId, $urutan)
    {
        $urutan = $urutan !== '' ? (int) $urutan : null;

        // Validate: urutan must be unique (if not null)
        if ($urutan !== null) {
            $existing = Mahasiswa::where('urutan_tampil', $urutan)
                ->where('id', '!=', $mahasiswaId)
                ->first();

            if ($existing) {
                session()->flash('error', "Urutan {$urutan} sudah digunakan oleh {$existing->nama}.");
                $this->loadMahasiswas();
                return;
            }
        }

        Mahasiswa::where('id', $mahasiswaId)->update(['urutan_tampil' => $urutan]);
        $this->loadMahasiswas();
    }

    public function toggleKunci($mahasiswaId)
    {
        $mhs = Mahasiswa::findOrFail($mahasiswaId);
        $mhs->urutan_dikunci = !$mhs->urutan_dikunci;
        $mhs->save();
        $this->loadMahasiswas();
    }

    public function acakUrutan()
    {
        $allMahasiswas = Mahasiswa::all();

        // Collect locked positions and their urutan
        $locked = $allMahasiswas->where('urutan_dikunci', true)
            ->pluck('urutan_tampil', 'id')
            ->toArray();

        // Get unlocked mahasiswas
        $unlocked = $allMahasiswas->where('urutan_dikunci', false);

        // Determine all available positions (1 to total count)
        $totalCount = $allMahasiswas->count();
        $allPositions = range(1, $totalCount);

        // Remove positions occupied by locked mahasiswas
        $lockedPositions = array_filter(array_values($locked), fn ($v) => $v !== null);
        $availablePositions = array_values(array_diff($allPositions, $lockedPositions));

        // Shuffle available positions
        shuffle($availablePositions);

        // Assign shuffled positions to unlocked mahasiswas
        $i = 0;
        foreach ($unlocked as $mhs) {
            $mhs->urutan_tampil = $availablePositions[$i] ?? null;
            $mhs->save();
            $i++;
        }

        $this->loadMahasiswas();
        session()->flash('success', 'Urutan berhasil diacak! Mahasiswa yang terkunci tidak berubah.');
    }

    public function resetUrutan()
    {
        Mahasiswa::where('urutan_dikunci', false)->update(['urutan_tampil' => null]);
        $this->loadMahasiswas();
        session()->flash('success', 'Urutan yang tidak terkunci berhasil direset.');
    }

    public function render()
    {
        return view('livewire.admin.urutan-manager');
    }
}
