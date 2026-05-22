<?php

namespace Database\Seeders;

use App\Models\Tema;
use Illuminate\Database\Seeder;

class TemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $temas = [
            'Kuliah daring: Benarkah kita belajar?',
            'IPK tinggi vs. Keterampilan tinggi',
            'Mengapa banyak sarjana menganggur?',
            'AI di kelas: Musuh atau teman?',
            'Kesehatan mental bukan berarti cengeng',
            'Budaya batal (cancel culture): Kritik atau perundungan massal?',
            'FOMO vs. JOMO',
            'Literasi keuangan anak kos',
            'Hidup lambat (slow living) di era serba cepat',
            'Menjadi aktivis dari kamar kos',
            'Satu kebiasaan toksik',
            'Chat ke diri sendiri',
            'Perubahan kampus',
            'Jujur saja',
            'Aplikasi penyelamat',
            'Sindrom penipu (impostor syndrome) di kampus',
            'Kelelahan (burnout) vs. produktivitas toksik',
            'Berani berkata tidak',
            'Pencitraan diri (personal branding) sejak semester satu',
            'Krisis seperempat abad (quarter-life crisis) versi mahasiswa'
        ];

        foreach ($temas as $tema) {
            Tema::firstOrCreate(['judul' => $tema]);
        }
    }
}
