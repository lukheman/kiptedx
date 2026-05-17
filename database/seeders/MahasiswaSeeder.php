<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use Illuminate\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nim' => '242211075', 'nama' => 'DINA RAHMAWATI'],
            ['nim' => '242211079', 'nama' => 'PUTU RESTI SETIAWATI'],
            ['nim' => '242211083', 'nama' => 'ANISA AMELIA.R'],
            ['nim' => '242211084', 'nama' => 'SUBEDA RIANTI'],
            ['nim' => '242211085', 'nama' => 'NURHIDAYAH'],
            ['nim' => '242211086', 'nama' => 'FADILLAH AYMAN SEPTIANA'],
            ['nim' => '242211088', 'nama' => 'BAKHRAENI'],
            ['nim' => '242211089', 'nama' => 'RIFANA AL MUNAWWARAH'],
            ['nim' => '242211090', 'nama' => 'LORITSZA SITI AULIA NOOR'],
            ['nim' => '242211091', 'nama' => 'FINA FENIKA'],
            ['nim' => '242211092', 'nama' => 'NURFADILLAH AMIRUDDIN'],
            ['nim' => '242211097', 'nama' => 'CITRA WULANDARI'],
            ['nim' => '242221098', 'nama' => 'ANGGIKA JANUARKA'],
            ['nim' => '242221100', 'nama' => 'NURFADILA'],
            ['nim' => '242221106', 'nama' => 'NAHDAH MUHIBAH'],
            ['nim' => '242221113', 'nama' => 'YUNI SARDILA'],
            ['nim' => '242221114', 'nama' => 'MELISA'],
            ['nim' => '242221115', 'nama' => 'FATIMAH AZZAHARA ILHAM'],
            ['nim' => '242221116', 'nama' => 'NURHIJRA'],
            ['nim' => '242221117', 'nama' => 'ILBRIANA'],
            ['nim' => '242221118', 'nama' => 'DEA'],
            ['nim' => '242221119', 'nama' => 'KADEK FERI PURNIAWAN'],
            ['nim' => '242221120', 'nama' => 'LUKMANUL RAHMAN'],
            ['nim' => '242221121', 'nama' => 'NUR SALSABILA'],
            ['nim' => '242221122', 'nama' => 'HILWA ZABILA'],
            ['nim' => '242221125', 'nama' => 'NABILA ANGGUN AZZAHRA'],
        ];

        foreach ($data as $mhs) {
            Mahasiswa::create([
                'nim' => $mhs['nim'],
                'nama' => $mhs['nama'],
                'password' => bcrypt('password123'),
            ]);
        }
    }
}
