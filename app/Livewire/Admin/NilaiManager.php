<?php

namespace App\Livewire\Admin;

use App\Models\Juri;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Nilai Mahasiswa')]
class NilaiManager extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    public string $sortBy = 'urutan_tampil';

    public string $sortDirection = 'asc';

    public ?int $selectedMahasiswaId = null;

    public bool $showDetailModal = false;

    protected $paginationTheme = 'bootstrap';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function openDetailModal(int $mahasiswaId): void
    {
        $this->selectedMahasiswaId = $mahasiswaId;
        $this->showDetailModal = true;
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->selectedMahasiswaId = null;
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

    public function exportCsv()
    {
        $juris = Juri::orderBy('nama')->get();
        $mahasiswas = Mahasiswa::query()
            ->with(['tema'])
            ->withAvg('nilais as rata_rata', 'nilai')
            ->orderBy('urutan_tampil')
            ->get();

        $allNilais = Nilai::whereIn('mahasiswa_id', $mahasiswas->pluck('id'))
            ->get()
            ->groupBy('mahasiswa_id');

        return response()->streamDownload(function () use ($juris, $mahasiswas, $allNilais) {
            $handle = fopen('php://output', 'w');

            // Header CSV
            $headers = ['No', 'Urutan Tampil', 'NIM', 'Nama Mahasiswa', 'Tema Presentasi'];
            foreach ($juris as $juri) {
                $headers[] = 'Nilai: ' . $juri->nama;
            }
            $headers[] = 'Rata-Rata';
            fputcsv($handle, $headers);

            // Baris Data
            $no = 1;
            foreach ($mahasiswas as $m) {
                $row = [
                    $no++,
                    $m->urutan_tampil ?? '-',
                    $m->nim,
                    $m->nama,
                    $m->tema ? $m->tema->judul : '-',
                ];

                $mNilais = $allNilais->get($m->id, collect());
                foreach ($juris as $juri) {
                    $nilaiObj = $mNilais->firstWhere('juri_id', $juri->id);
                    $row[] = $nilaiObj ? round($nilaiObj->nilai, 2) : '-';
                }
                
                $row[] = $m->rata_rata ? round($m->rata_rata, 2) : '0';

                fputcsv($handle, $row);
            }

            fclose($handle);
        }, 'Rekap_Nilai_Peserta.csv');
    }

    public function render()
    {
        $juris = Juri::orderBy('nama')->get();

        $query = Mahasiswa::query()
            ->with(['tema'])
            ->when($this->search, function ($query) {
                $query->where('nama', 'like', '%'.$this->search.'%')
                    ->orWhere('nim', 'like', '%'.$this->search.'%');
            })
            ->withAvg('nilais as rata_rata', 'nilai')
            ->withCount('nilais as jumlah_menilai');

        if ($this->sortBy === 'rata_rata') {
            $query->orderBy('rata_rata', $this->sortDirection);
        } elseif ($this->sortBy === 'jumlah_menilai') {
            $query->orderBy('jumlah_menilai', $this->sortDirection);
        } else {
            $query->orderBy($this->sortBy, $this->sortDirection);
        }

        $paginatedMahasiswas = $query->paginate(10);

        // Fetch detail if modal is open
        $selectedMahasiswa = null;
        $scoresDetail = [];
        if ($this->selectedMahasiswaId) {
            $selectedMahasiswa = Mahasiswa::with('tema')->find($this->selectedMahasiswaId);
            $scoresDetail = Nilai::with('juri')
                ->where('mahasiswa_id', $this->selectedMahasiswaId)
                ->get();
        }

        // To map jury scores in the main table, we can fetch all Nilai for the paginated students
        $mahasiswaIds = $paginatedMahasiswas->pluck('id')->toArray();
        $allNilais = Nilai::whereIn('mahasiswa_id', $mahasiswaIds)->get()->groupBy('mahasiswa_id');

        return view('livewire.admin.nilai-manager', [
            'mahasiswas' => $paginatedMahasiswas,
            'juris' => $juris,
            'allNilais' => $allNilais,
            'selectedMahasiswa' => $selectedMahasiswa,
            'scoresDetail' => $scoresDetail,
        ]);
    }
}
