<?php

use App\Models\Santri;
use App\Models\TargetHafalan;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$count = 0;
Santri::all()->each(function($s) use (&$count) {
    if (!$s->targetHafalans()->exists()) {
        TargetHafalan::create([
            'santri_id' => $s->id,
            'target_juz' => 0,
            'status' => 'belum'
        ]);
        $count++;
    }
});

echo "Fixed $count students.\n";
