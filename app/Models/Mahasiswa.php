<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Mahasiswa extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\MahasiswaFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'nim',
        'nama',
        'password',
        'foto_profil',
        'urutan_tampil',
        'urutan_dikunci',
        'tema_id',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'urutan_dikunci' => 'boolean',
        ];
    }

    public function tema()
    {
        return $this->belongsTo(Tema::class);
    }

    public function hasAvatar(): bool
    {
        return !empty($this->foto_profil);
    }

    public function avatarUrl(): string
    {
        return $this->foto_profil ? \Illuminate\Support\Facades\Storage::url($this->foto_profil) : '';
    }

    public function slidePresentasis()
    {
        return $this->hasMany(SlidePresentasi::class);
    }
}
