<?php

namespace Database\Seeders;

use App\Models\Setoran;
use App\Models\Santri;
use App\Models\TargetHafalan;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UstadzSeeder::class,
            UserSeeder::class,
            SantriSeeder::class,
        ]);

        Setoran::factory()->count(200)->create();

        // Create target hafalan for every santri to be complete
        $santris = Santri::all();
        foreach ($santris as $santri) {
            TargetHafalan::create([
                'santri_id' => $santri->id,
                'target_juz' => rand(1, 30),
                'status' => 'progress'
            ]);
        }
    }
}
