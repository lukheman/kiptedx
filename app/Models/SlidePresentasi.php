<?php

namespace App\Models;

use Database\Factories\SlidePresentasiFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlidePresentasi extends Model
{
    /** @use HasFactory<SlidePresentasiFactory> */
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'urutan',
        'file_gambar',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
