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
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@tahfizh.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 5 Ustadz Users
        $ustadzs = Ustadz::all();
        foreach ($ustadzs as $ustadz) {
            $emailName = strtolower(explode(' ', $ustadz->nama_lengkap)[0]);
            User::create([
                'name' => 'Ustadz ' . $ustadz->nama_lengkap,
                'email' => $emailName . '@tahfizh.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'ustadz_id' => $ustadz->id,
            ]);
        }
    }
}
