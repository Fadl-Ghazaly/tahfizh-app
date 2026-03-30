<?php

namespace App\Livewire;

use App\Models\Santri;
use App\Models\Setoran;
use App\Models\TargetHafalan;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public function render()
    {
        $currentMonth = (int) date('m');
        $currentYear  = (int) date('Y');

        // ── 1. Counts ──────────────────────────────────────────────────────────
        $totalSantri          = Santri::count();
        $totalSetoranBulanIni = Setoran::whereYear('tanggal', $currentYear)
                                       ->whereMonth('tanggal', $currentMonth)
                                       ->count();

        // ── 2. TargetHafalan data – single query, used for everything ──────────
        $allTargets = TargetHafalan::with(['santri:id,nama_lengkap,kelas,kelas_halaqah,ustadz_id',
                                           'santri.ustadz:id,nama_lengkap'])
                                   ->get();

        $rataProgress  = $allTargets->isNotEmpty() ? round($allTargets->avg('target_juz'), 1) : 0;
        $santriTerbaik = $allTargets->sortByDesc('target_juz')->first();

        // Top 10 Terbaik & Butuh Perhatian from in-memory collection
        $top10Terbaik    = $allTargets->sortByDesc('target_juz')->take(10)->values();
        $top10Perhatian  = $allTargets->where('target_juz', '<', 5)->sortBy('target_juz')->take(10)->values();

        // ── 3. Distribusi Hafalan – computed in PHP, zero extra queries ─────────
        $distHafalan = [
            '0_1'  => $allTargets->whereBetween('target_juz', [0,  1])->count(),
            '2_5'  => $allTargets->whereBetween('target_juz', [2,  5])->count(),
            '6_10' => $allTargets->whereBetween('target_juz', [6, 10])->count(),
            '11_20'=> $allTargets->whereBetween('target_juz', [11,20])->count(),
            '21_30'=> $allTargets->whereBetween('target_juz', [21,30])->count(),
        ];

        // ── 4. Progress per Kelas – group in PHP, zero extra queries ───────────
        $kelasLabels     = ['7', '8', '9', '10', '11', '12'];
        $progressPerKelas = [];
        foreach ($kelasLabels as $kelas) {
            $subset = $allTargets->filter(fn($t) => optional($t->santri)->kelas == $kelas);
            $progressPerKelas[] = $subset->isNotEmpty() ? round($subset->avg('target_juz'), 1) : 0;
        }

        // ── 5. Trend Setoran – one query with CASE aggregation ─────────────────
        $weeks        = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
        $trendRaw     = Setoran::selectRaw("
                SUM(CASE WHEN DAY(tanggal) BETWEEN 1  AND 7  THEN 1 ELSE 0 END) as w1,
                SUM(CASE WHEN DAY(tanggal) BETWEEN 8  AND 14 THEN 1 ELSE 0 END) as w2,
                SUM(CASE WHEN DAY(tanggal) BETWEEN 15 AND 21 THEN 1 ELSE 0 END) as w3,
                SUM(CASE WHEN DAY(tanggal) >  21             THEN 1 ELSE 0 END) as w4
            ")
            ->whereYear('tanggal', $currentYear)
            ->whereMonth('tanggal', $currentMonth)
            ->first();

        $trendSetoran = [
            (int) ($trendRaw->w1 ?? 0),
            (int) ($trendRaw->w2 ?? 0),
            (int) ($trendRaw->w3 ?? 0),
            (int) ($trendRaw->w4 ?? 0),
        ];

        return view('livewire.dashboard', compact(
            'totalSantri', 'totalSetoranBulanIni', 'rataProgress', 'santriTerbaik',
            'top10Terbaik', 'top10Perhatian', 'distHafalan', 'kelasLabels', 'progressPerKelas',
            'weeks', 'trendSetoran'
        ))->layout('layouts.app', ['header' => __('Dashboard')]);
    }
}
