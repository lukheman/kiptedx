<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Backsound extends Model
{
    protected $fillable = ['judul', 'file_audio'];

    public function audioUrl(): string
    {
        return Storage::url($this->file_audio);
    }
}
