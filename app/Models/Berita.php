<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'berita';

    protected $fillable = [
        'user_id',
        'judul',
        'isi',
        'is_publikasi',
        'path_gambar',
        'tgl_tayang',
        'tgl_akhir',
        'is_highlight'
    ];

    protected $casts = [
        'is_publikasi' => 'boolean',
        'is_highlight' => 'boolean',
        'tgl_tayang'   => 'date',
        'tgl_akhir'    => 'date',
    ];
}
