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

        Juri::create([
            'nim' => '242221120',
            'nama' => 'Juri 1',
            'password' => bcrypt('password123'),
        ]);

        Juri::create([
            'nim' => '242221121',
            'nama' => 'Juri 2',
            'password' => bcrypt('password123'),
        ]);

        Juri::create([
            'nim' => '242221122',
            'nama' => 'Juri 3',
            'password' => bcrypt('password123'),
        ]);

    }
}
