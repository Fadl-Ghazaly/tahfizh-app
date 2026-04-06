<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ustadz;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1 Admin
        User::updateOrCreate(
            ['email' => 'admin@tahfizh.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // 5 Ustadz Users
        $ustadzs = Ustadz::all();
        foreach ($ustadzs as $ustadz) {
            $emailName = strtolower(explode(' ', $ustadz->nama_lengkap)[0]);
            User::updateOrCreate(
                ['email' => $emailName . '@tahfizh.com'],
                [
                    'name' => 'Ustadz ' . $ustadz->nama_lengkap,
                    'password' => Hash::make('password'),
                    'role' => 'user',
                    'ustadz_id' => $ustadz->id,
                ]
            );
        }
    }
}
