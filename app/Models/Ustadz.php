<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ustadz extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lengkap',
        'jenis_kelamin',
        'no_wa',
        'asal_pondok',
    ];

    public function santris()
    {
        return $this->hasMany(Santri::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
