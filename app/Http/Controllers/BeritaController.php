<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

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

    protected $appends = ['image_url'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Biar blade aman: bisa asset(), bisa Storage::url(), bisa URL full
    public function getImageUrlAttribute(): string
    {
        $path = $this->path_gambar ?? '';

        if ($path === '') return '';

        // kalau udah URL full
        if (preg_match('/^https?:\/\//i', $path)) {
            return $path;
        }

        // kalau nyimpen "img/berita/xxx.jpg" (public)
        if (str_starts_with($path, 'img/') || str_starts_with($path, 'images/') || str_starts_with($path, 'uploads/')) {
            return asset($path);
        }

        // kalau nyimpen "public/..." atau "berita/..." di storage
        return Storage::url($path);
    }
}
