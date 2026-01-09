<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Galeri extends Model
{
    use SoftDeletes;

    protected $table = 'galeri';

    protected $fillable = [
        'user_id',
        'path_gambar',
        'judul',
        'slug',
        'kategori',
        'is_show',
        'tanggal_kegiatan',
        'deskripsi',
        'excerpt',
        'alt_text',
        'sort_order',
        'published_at',
    ];

    protected $casts = [
        'is_show' => 'boolean',
        'tanggal_kegiatan' => 'date',
        'published_at' => 'datetime',
    ];
}
