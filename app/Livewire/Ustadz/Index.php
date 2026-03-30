<?php

namespace App\Livewire\Ustadz;

use App\Models\Ustadz;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $isModalOpen = 0;
    
    public $ustadz_id, $nama_lengkap, $jenis_kelamin, $no_wa, $asal_pondok;

    protected $rules = [
        'nama_lengkap' => 'required|string|max:255',
        'jenis_kelamin' => 'required|in:L,P',
        'no_wa' => 'nullable|string|max:20',
        'asal_pondok' => 'nullable|string|max:255',
    ];

    public function render()
    {
        $query = Ustadz::query();

        if (!empty($this->search)) {
            $query->where('nama_lengkap', 'like', '%' . $this->search . '%')
                  ->orWhere('asal_pondok', 'like', '%' . $this->search . '%');
        }

        $ustadzs = $query->latest()->paginate(10);

        return view('livewire.ustadz.index', compact('ustadzs'))
               ->layout('layouts.app', ['header' => __('Data Profil Ustadz')]);
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
        $this->ustadz_id = '';
        $this->nama_lengkap = '';
        $this->jenis_kelamin = '';
        $this->no_wa = '';
        $this->asal_pondok = '';
    }

    public function store()
    {
        $this->validate();

        Ustadz::updateOrCreate(['id' => $this->ustadz_id], [
            'nama_lengkap' => $this->nama_lengkap,
            'jenis_kelamin' => $this->jenis_kelamin,
            'no_wa' => $this->no_wa,
            'asal_pondok' => $this->asal_pondok,
        ]);

        session()->flash('message', $this->ustadz_id ? 'Data Ustadz berhasil diupdate.' : 'Data Ustadz berhasil ditambahkan.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $ustadz = Ustadz::findOrFail($id);
        $this->ustadz_id = $id;
        $this->nama_lengkap = $ustadz->nama_lengkap;
        $this->jenis_kelamin = $ustadz->jenis_kelamin;
        $this->no_wa = $ustadz->no_wa;
        $this->asal_pondok = $ustadz->asal_pondok;
        
        $this->openModal();
    }

    public function delete($id)
    {
        Ustadz::findOrFail($id)->delete();
        session()->flash('message', 'Data Ustadz berhasil dihapus.');
    }
}
