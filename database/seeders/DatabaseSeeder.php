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
        ];

        foreach ($juris as $juri) {
            Juri::create($juri);
        }

    }
}
