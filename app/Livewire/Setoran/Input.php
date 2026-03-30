<?php

namespace App\Livewire\Setoran;

use App\Models\Santri;
use App\Models\Setoran;
use App\Models\TargetHafalan;
use App\Models\Ustadz;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Input extends Component
{
    public $tab = 'sabaq';
    
    // Filters
    public $filterKelas = '';
    public $filterUstadz = '';

    // Form Fields
    public $santri_id;
    public $tanggal;
    public $juz = 1;
    public $nama_surat = '';
    public $ayat_mulai;
    public $ayat_selesai;
    public $jumlah_baris = 0;
    public $catatan = '';
    public $kehadiran = 'hadir';
    public $nilai_kelancaran = 100;

    public $suratList = [
        'Al-Fatihah', 'Al-Baqarah', 'Ali Imran', 'An-Nisa', 'Al-Maidah', 'Al-Anam', 'Al-Araf', 'Al-Anfal', 'At-Taubah', 'Yunus',
        'Hud', 'Yusuf', 'Ar-Rad', 'Ibrahim', 'Al-Hijr', 'An-Nahl', 'Al-Isra', 'Al-Kahf', 'Maryam', 'Taha',
        'Al-Anbiya', 'Al-Hajj', 'Al-Muminun', 'An-Nur', 'Al-Furqan', 'Asy-Syuara', 'An-Naml', 'Al-Qasas', 'Al-Ankabut', 'Ar-Rum',
        'Luqman', 'As-Sajdah', 'Al-Ahzab', 'Saba', 'Fatir', 'Yasin', 'As-Saffat', 'Sad', 'Az-Zumar', 'Ghafir',
        'Fussilat', 'Asy-Syura', 'Az-Zukhruf', 'Ad-Dukhan', 'Al-Jasiyah', 'Al-Ahqaf', 'Muhammad', 'Al-Fath', 'Al-Hujurat', 'Qaf',
        'Az-Zariyat', 'At-Tur', 'An-Najm', 'Al-Qamar', 'Ar-Rahman', 'Al-Waqiah', 'Al-Hadid', 'Al-Mujadilah', 'Al-Hasyr', 'Al-Mumtahanah',
        'As-Saff', 'Al-Jumuah', 'Al-Munafiqun', 'At-Tagabun', 'At-Talaq', 'At-Tahrim', 'Al-Mulk', 'Al-Qalam', 'Al-Haqqah', 'Al-Maarij',
        'Nuh', 'Al-Jinn', 'Al-Muzzammil', 'Al-Muddassir', 'Al-Qiyamah', 'Al-Insan', 'Al-Mursalat', 'An-Naba', 'An-Naziat', 'Abasa',
        'At-Takwir', 'Al-Infitar', 'Al-Mutaffifin', 'Al-Insyiqaq', 'Al-Buruj', 'At-Tariq', 'Al-Ala', 'Al-Gasyiyah', 'Al-Fajr', 'Al-Balad',
        'Asy-Syams', 'Al-Lail', 'Ad-Duha', 'Asy-Syarh', 'At-Tin', 'Al-Alaq', 'Al-Qadr', 'Al-Bayyinah', 'Az-Zalzalah', 'Al-Adiyat',
        'Al-Qariah', 'At-Takasur', 'Al-Asr', 'Al-Humazah', 'Al-Fil', 'Quraisy', 'Al-Maun', 'Al-Kausar', 'Al-Kafirun', 'An-Nasr',
        'Al-Lahab', 'Al-Ikhlas', 'Al-Falaq', 'An-Nas'
    ];

    public function mount()
    {
        $this->tanggal = date('Y-m-d');
        if (Auth::user()->role === 'user') {
            $this->filterUstadz = Auth::user()->ustadz_id;
        }
    }

    public function setTab($tabName)
    {
        $this->tab = $tabName;
        // reset form on tab change
        $this->reset(['santri_id', 'juz', 'nama_surat', 'ayat_mulai', 'ayat_selesai', 'jumlah_baris', 'catatan']);
        $this->kehadiran = 'hadir';
        $this->nilai_kelancaran = 100;
    }

    public function kurangiNilai()
    {
        $this->nilai_kelancaran = max(0, $this->nilai_kelancaran - 5);
    }

    public function calculateBaris()
    {
        if ($this->ayat_mulai && $this->ayat_selesai) {
            $diff = abs($this->ayat_selesai - $this->ayat_mulai) + 1;
            // Rough estimation
            $this->jumlah_baris = ceil($diff * 1.5);
        }
    }

    public function updatedAyatMulai() { $this->calculateBaris(); }
    public function updatedAyatSelesai() { $this->calculateBaris(); }

    public function store()
    {
        $this->validate([
            'santri_id' => 'required',
            'tanggal' => 'required|date',
            'juz' => 'required|numeric|min:1|max:30',
            'nama_surat' => 'required|string',
            'kehadiran' => 'required|in:hadir,izin,sakit,alpha',
            'ayat_mulai' => 'nullable|numeric',
            'ayat_selesai' => 'nullable|numeric',
            'jumlah_baris' => 'nullable|numeric',
            'nilai_kelancaran' => 'required|numeric|min:0|max:100',
        ]);

        Setoran::create([
            'santri_id' => $this->santri_id,
            'tanggal' => $this->tanggal,
            'juz' => $this->juz,
            'nama_surat' => $this->nama_surat,
            'ayat_mulai' => $this->ayat_mulai ?? 0,
            'ayat_selesai' => $this->ayat_selesai ?? 0,
            'jumlah_baris' => $this->jumlah_baris ?? 0,
            'catatan' => $this->catatan,
            'kehadiran' => $this->kehadiran,
            'nilai_kelancaran' => $this->nilai_kelancaran,
            'jenis_setoran' => $this->tab,
        ]);

        // Update target hafalan progress if sabaq
        if ($this->tab === 'sabaq') {
            $target = TargetHafalan::firstOrCreate(
                ['santri_id' => $this->santri_id],
                ['target_juz' => 0, 'status' => 'progress']
            );
            
            if ($this->juz > $target->target_juz) {
                $target->update(['target_juz' => $this->juz]);
            }
        }

        session()->flash('message', 'Setoran ' . ucfirst($this->tab) . ' berhasil disimpan!');
        
        // Reset form for next input
        $this->reset(['santri_id', 'juz', 'nama_surat', 'ayat_mulai', 'ayat_selesai', 'jumlah_baris', 'catatan']);
        $this->kehadiran = 'hadir';
        $this->nilai_kelancaran = 100;
        $this->tanggal = date('Y-m-d');
    }

    public function render()
    {
        $query = Santri::query();

        if ($this->filterKelas) {
            $query->where('kelas', $this->filterKelas);
        }
        
        if ($this->filterUstadz) {
            $query->where('ustadz_id', $this->filterUstadz);
        }

        $santris = $query->orderBy('nama_lengkap')->get();
        $ustadzs = Ustadz::all();

        // Recent setoran today
        $recentSetoran = Setoran::with('santri')
            ->whereDate('tanggal', $this->tanggal)
            ->where('jenis_setoran', $this->tab)
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.setoran.input', compact('santris', 'ustadzs', 'recentSetoran'))
             ->layout('layouts.app', ['header' => __('Input Setoran Tahfidz')]);
    }
}
