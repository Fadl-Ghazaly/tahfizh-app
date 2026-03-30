<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetHafalan extends Model
{
    use HasFactory;

    protected $fillable = [
        'santri_id',
        'target_juz',
        'status',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}
