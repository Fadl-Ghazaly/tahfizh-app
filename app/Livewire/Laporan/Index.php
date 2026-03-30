<?php

namespace App\Livewire\Laporan;

use App\Models\Santri;
use App\Models\Setoran;
use App\Models\TargetHafalan;
use App\Models\Ustadz;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class Index extends Component
{
    use WithPagination;
    public $filterBulan;
    public $filterKelas = '';
    public $filterUstadz = '';

    public function updatingFilterBulan() { $this->resetPage(); }
    public function updatingFilterKelas() { $this->resetPage(); }
    public function updatingFilterUstadz() { $this->resetPage(); }

    public function mount()
    {
        $this->filterBulan = date('Y-m');
        if (Auth::user()->role === 'user') {
            $this->filterUstadz = Auth::user()->ustadz_id;
        }
    }

    public function getStatistik($paginate = true)
    {
        $year = substr($this->filterBulan, 0, 4);
        $month = substr($this->filterBulan, 5, 2);

        $santriQuery = Santri::query();
        if ($this->filterKelas) $santriQuery->where('kelas', $this->filterKelas);
        if ($this->filterUstadz) $santriQuery->where('ustadz_id', $this->filterUstadz);
        
        $santriIds = $santriQuery->pluck('id')->toArray();

        // Self-healing: Ensure all filtered santri have TargetHafalan records
        $existingTargets = TargetHafalan::whereIn('santri_id', $santriIds)->pluck('santri_id')->toArray();
        $missingTargets = array_diff($santriIds, $existingTargets);
        if (!empty($missingTargets)) {
            foreach ($missingTargets as $santriId) {
                TargetHafalan::create([
                    'santri_id' => $santriId,
                    'target_juz' => 0,
                    'status' => 'progress'
                ]);
            }
        }

        $totalSantri = count($santriIds);
        
        $setorans = Setoran::whereIn('santri_id', $santriIds)
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->get();

        $totalSetoran = $setorans->count();
        $rataNilai = $totalSetoran > 0 ? round($setorans->avg('nilai_kelancaran'), 1) : 0;

        // Progress Tercepat (santri dengan juz tertinggi yang ada di filter)
        $tercepat = TargetHafalan::with('santri')
            ->whereIn('santri_id', $santriIds)
            ->orderByDesc('target_juz')
            ->first();

        // Ranking
        $rankingQuery = TargetHafalan::with('santri.ustadz')
            ->whereIn('santri_id', $santriIds)
            ->orderByDesc('target_juz');

        $ranking = $paginate ? $rankingQuery->paginate(10) : $rankingQuery->get();

        return [
            'totalSantri' => $totalSantri,
            'totalSetoran' => $totalSetoran,
            'rataNilai' => $rataNilai,
            'tercepat' => $tercepat,
            'ranking' => $ranking,
            'monthName' => date("F Y", strtotime($this->filterBulan . '-01')),
            'kelas' => $this->filterKelas ?: 'Semua',
        ];
    }

    public function downloadPdf()
    {
        $data = $this->getStatistik(false);
        
        $pdf = Pdf::loadView('laporan.pdf', $data);
        
        $fileName = 'Laporan_Tahfidz_' . $data['monthName'] . '.pdf';
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $fileName);
    }

    public function render()
    {
        $ustadzs = Cache::remember('ustadzs_list', 300, fn() => Ustadz::orderBy('nama_lengkap')->get());
        $stats = $this->getStatistik(true);

        return view('livewire.laporan.index', compact('ustadzs', 'stats'))
               ->layout('layouts.app', ['header' => __('Laporan Bulanan & Progress')]);
    }
}
