<?php

namespace Database\Seeders;

use App\Models\Ustadz;
use Illuminate\Database\Seeder;

class UstadzSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ustadzs = [
            ['nama_lengkap' => 'Ahmad Zain', 'jenis_kelamin' => 'L', 'asal_pondok' => 'Gontor'],
            ['nama_lengkap' => 'Muhammad Hasan', 'jenis_kelamin' => 'L', 'asal_pondok' => 'Lirboyo'],
            ['nama_lengkap' => 'Fatimah Az-Zahra', 'jenis_kelamin' => 'P', 'asal_pondok' => 'Bahrul Ulum'],
            ['nama_lengkap' => 'Anisa Rahmawati', 'jenis_kelamin' => 'P', 'asal_pondok' => 'Langitan'],
            ['nama_lengkap' => 'Ali Rahmat', 'jenis_kelamin' => 'L', 'asal_pondok' => 'Tebuireng'],
        ];

        foreach ($ustadzs as $u) {
            Ustadz::create($u);
        }
    }
}
