<?php

namespace Database\Factories;

use App\Models\Ustadz;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Santri>
 */
class SantriFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $ustadzs = Ustadz::pluck('id')->toArray();

        return [
            'nama_lengkap' => fake()->name(),
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'kelas' => fake()->randomElement(['7', '8', '9', '10', '11', '12']),
            'kelas_halaqah' => fake()->randomElement(['A', 'B', 'C', 'D']),
            'nisn' => fake()->unique()->numerify('##########'),
            'ustadz_id' => fake()->randomElement($ustadzs),
            'nama_orangtua' => fake()->name(),
            'wa_orangtua' => fake()->phoneNumber(),
        ];
    }
}
