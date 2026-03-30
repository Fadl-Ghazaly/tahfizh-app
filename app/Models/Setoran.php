<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setoran extends Model
{
    use HasFactory;

    protected $fillable = [
        'santri_id',
        'tanggal',
        'juz',
        'nama_surat',
        'ayat_mulai',
        'ayat_selesai',
        'jumlah_baris',
        'catatan',
        'kehadiran',
        'nilai_kelancaran',
        'jenis_setoran',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}
