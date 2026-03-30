<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lengkap',
        'jenis_kelamin',
        'kelas',
        'kelas_halaqah',
        'nisn',
        'ustadz_id',
        'nama_orangtua',
        'wa_orangtua',
    ];

    public function ustadz()
    {
        return $this->belongsTo(Ustadz::class);
    }

    public function setorans()
    {
        return $this->hasMany(Setoran::class);
    }

    public function targetHafalans()
    {
        return $this->hasMany(TargetHafalan::class);
    }
}
