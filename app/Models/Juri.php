<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class Juri extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'nim',
        'nama',
        'password',
        'foto_profil',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function hasAvatar(): bool
    {
        return ! empty($this->foto_profil);
    }

    public function avatarUrl(): string
    {
        return $this->foto_profil ? Storage::url($this->foto_profil) : '';
    }
}
