<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $fillable = [
        'juri_id',
        'mahasiswa_id',
        'nilai',
        'catatan',
    ];

    public function juri()
    {
        return $this->belongsTo(Juri::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
