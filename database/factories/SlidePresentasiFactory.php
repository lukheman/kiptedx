<?php

namespace Database\Factories;

use App\Models\SlidePresentasi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SlidePresentasi>
 */
class SlidePresentasiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mahasiswa_id' => \App\Models\Mahasiswa::factory(),
            'urutan' => fake()->numberBetween(1, 4),
            'judul_slide' => fake()->sentence(),
            'file_gambar' => 'slides/default.png',
        ];
    }
}
