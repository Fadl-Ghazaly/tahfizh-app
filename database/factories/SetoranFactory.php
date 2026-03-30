<?php

namespace Database\Factories;

use App\Models\Santri;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Setoran>
 */
class SetoranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $suratList = ['Al-Fatihah', 'Al-Baqarah', 'Ali Imran', 'An-Nisa', 'Al-Maidah', 'Al-Anam', 'Al-Araf', 'Al-Anfal'];

        return [
            'santri_id' => Santri::inRandomOrder()->first()->id ?? 1,
            'tanggal' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'juz' => fake()->numberBetween(1, 30),
            'nama_surat' => fake()->randomElement($suratList),
            'ayat_mulai' => fake()->numberBetween(1, 10),
            'ayat_selesai' => fake()->numberBetween(11, 30),
            'jumlah_baris' => fake()->numberBetween(5, 15),
            'catatan' => fake()->optional()->sentence(),
            'kehadiran' => fake()->randomElement(['hadir', 'hadir', 'hadir', 'izin', 'sakit']), // Bias largely to 'hadir'
            'nilai_kelancaran' => fake()->randomElement([80, 85, 90, 95, 100]),
            'jenis_setoran' => fake()->randomElement(['sabaq', 'sabqi', 'manzil']),
        ];
    }
}
