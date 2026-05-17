<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tema extends Model
{
    /** @use HasFactory<\Database\Factories\TemaFactory> */
    use HasFactory;

    protected $fillable = ['judul'];

    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class);
    }
}
