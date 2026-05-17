<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlidePresentasi extends Model
{
    /** @use HasFactory<\Database\Factories\SlidePresentasiFactory> */
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'urutan',
        'judul_slide',
        'file_gambar',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
