<?php

namespace App\Livewire\Admin;

use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Atur Urutan Tampil')]
class UrutanManager extends Component
{
    public $mahasiswas = [];

    public array $urutanInputs = [];

    public function mount()
    {
        $this->loadMahasiswas();
    }

    public function loadMahasiswas()
    {
        $this->urutanInputs = [];
        $this->mahasiswas = Mahasiswa::orderByRaw('urutan_tampil IS NULL, urutan_tampil ASC')
            ->orderBy('nama')
            ->get()
            ->map(function ($mhs) {
                $this->urutanInputs[$mhs->id] = $mhs->urutan_tampil;
                return [
                    'id' => $mhs->id,
                    'nim' => $mhs->nim,
                    'nama' => $mhs->nama,
                    'foto_profil' => $mhs->foto_profil ? Storage::url($mhs->foto_profil) : null,
                    'urutan_tampil' => $mhs->urutan_tampil,
                    'urutan_dikunci' => $mhs->urutan_dikunci,
                    'slide_count' => $mhs->slidePresentasis()->count(),
                ];
            })
            ->toArray();
    }

    public function simpanUrutan()
    {
        // validate uniqueness among the inputs
        $values = array_filter($this->urutanInputs, fn($v) => $v !== null && $v !== '');
        $counts = array_count_values($values);
        $duplicates = array_filter($counts, fn($c) => $c > 1);

        if (!empty($duplicates)) {
            $dupKeys = implode(', ', array_keys($duplicates));
            session()->flash('error', "Terdapat urutan ganda pada angka: {$dupKeys}. Harap pastikan setiap urutan unik.");
            return;
        }

        foreach ($this->urutanInputs as $id => $urutan) {
            $val = ($urutan !== null && $urutan !== '') ? (int) $urutan : null;
            Mahasiswa::where('id', $id)->update(['urutan_tampil' => $val]);
        }

        $this->loadMahasiswas();
        session()->flash('success', 'Urutan berhasil disimpan!');
    }

    public function toggleKunci($mahasiswaId)
    {
        $mhs = Mahasiswa::findOrFail($mahasiswaId);
        $mhs->urutan_dikunci = ! $mhs->urutan_dikunci;
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
