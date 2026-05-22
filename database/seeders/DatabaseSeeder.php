<?php

namespace Database\Seeders;

use App\Models\Juri;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => 'password123',
        ]);

        $juris = [
            [
                'nama' => 'Amelia Ovtafiana, S.Kom',
                'nim' => 'ameliaoktafiana@kiptedx.site',
                'password' => bcrypt('password123'),
            ],
            [
                'nama' => "Kharis Sya'ban G., S.T., M.Cs.",
                'nim' => 'kharissyaban@kiptedx.site',
                'password' => bcrypt('password123'),
            ],
            [
                'nama' => 'Anjar Pradipta, S.Kom., M.Kom',
                'nim' => 'anjarpradipta@kiptedx.site',
                'password' => bcrypt('password123'),
            ],
            [
                'nama' => 'Wisnu Tri Sardi, S.Kom.,M.M',
                'nim' => 'wisnutrisardi@kiptedx.site',
                'password' => bcrypt('password123'),
            ],
            [
                'nama' => 'Atia Rahma Trimurti',
                'nim' => '231220677',
                'password' => bcrypt('password123'),
            ],
            [
                'nama' => 'mumaulana',
                'nim' => '231220699',
                'password' => bcrypt('password123'),
            ],
            [
                'nama' => 'Taufik Hidayat',
                'nim' => '231230732',
                'password' => bcrypt('password123'),
            ],
            [
                'nama' => 'Alfat pandu kusuma',
                'nim' => '231210647',
                'password' => bcrypt('password123'),
            ],
            [
                'nama' => 'Juri',
                'nim' => '242221120',
                'password' => bcrypt('password123'),
            ],
        ];

        foreach ($juris as $juri) {
            Juri::create($juri);
        }

    }
}
