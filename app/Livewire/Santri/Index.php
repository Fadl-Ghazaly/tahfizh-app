<?php

namespace App\Livewire\Santri;

use App\Models\Santri;
use App\Models\Ustadz;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Cache;

class Index extends Component
{
    use WithPagination;

    public function paginationView()
    {
        return 'pagination::tailwind'; // Optional: Use Laravel's default Tailwind pagination
    }

    public $search = '';
    public $filterKelas = '';
    
    public $isModalOpen = 0;
    
    public $santri_id, $nama_lengkap, $jenis_kelamin, $kelas, $kelas_halaqah, $nisn, $ustadz_id, $nama_orangtua, $wa_orangtua;

    protected $rules = [
        'nama_lengkap' => 'required|string|max:255',
        'jenis_kelamin' => 'required|in:L,P',
        'kelas' => 'required',
        'kelas_halaqah' => 'required|string',
        'ustadz_id' => 'required|exists:ustadzs,id',
    ];

    public function render()
    {
        $query = Santri::with('ustadz');

        if (!empty($this->search)) {
            $query->where('nama_lengkap', 'like', '%' . $this->search . '%')
                  ->orWhere('nisn', 'like', '%' . $this->search . '%');
        }

        if (!empty($this->filterKelas)) {
            $query->where('kelas', $this->filterKelas);
        }

        $santris = $query->latest()->paginate(10);
        $ustadzs = Cache::remember('ustadzs_list', 300, fn() => Ustadz::orderBy('nama_lengkap')->get());

        return view('livewire.santri.index', compact('santris', 'ustadzs'))
               ->layout('layouts.app', ['header' => __('Data Profil Santri')]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->santri_id = '';
        $this->nama_lengkap = '';
        $this->jenis_kelamin = '';
        $this->kelas = '';
        $this->kelas_halaqah = '';
        $this->nisn = '';
        $this->ustadz_id = '';
        $this->nama_orangtua = '';
        $this->wa_orangtua = '';
    }

    public function store()
    {
        $this->validate();

        $santri = Santri::updateOrCreate(['id' => $this->santri_id], [
            'nama_lengkap' => $this->nama_lengkap,
            'jenis_kelamin' => $this->jenis_kelamin,
            'kelas' => $this->kelas,
            'kelas_halaqah' => $this->kelas_halaqah,
            'nisn' => $this->nisn,
            'ustadz_id' => $this->ustadz_id,
            'nama_orangtua' => $this->nama_orangtua,
            'wa_orangtua' => $this->wa_orangtua,
        ]);

        // Ensure TargetHafalan exists
        if (!$this->santri_id) {
            \App\Models\TargetHafalan::create([
                'santri_id' => $santri->id,
                'target_juz' => 0,
                'status' => 'progress'
            ]);
        }

        session()->flash('message', $this->santri_id ? 'Data Santri berhasil diupdate.' : 'Data Santri berhasil ditambahkan.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $santri = Santri::findOrFail($id);
        $this->santri_id = $id;
        $this->nama_lengkap = $santri->nama_lengkap;
        $this->jenis_kelamin = $santri->jenis_kelamin;
        $this->kelas = $santri->kelas;
        $this->kelas_halaqah = $santri->kelas_halaqah;
        $this->nisn = $santri->nisn;
        $this->ustadz_id = $santri->ustadz_id;
        $this->nama_orangtua = $santri->nama_orangtua;
        $this->wa_orangtua = $santri->wa_orangtua;
        
        $this->openModal();
    }

    public function delete($id)
    {
        Santri::findOrFail($id)->delete();
        session()->flash('message', 'Data Santri berhasil dihapus.');
    }
}
