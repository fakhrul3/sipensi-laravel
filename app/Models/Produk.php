<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    protected $guarded = [];

    protected $casts = [
        'foto_produk' => 'array', // ✅ biar JSON otomatis jadi array
    ];

    // ✅ FIX: kalau JSON di DB pakai backslash (\), cast bisa gagal
    // kita rapihin dulu sebelum decode
    public function getFotoProdukAttribute($value)
    {
        if (!$value) return [];

        // kalau sudah array (kadang dari cast), balikin aja
        if (is_array($value)) return $value;

        // normalisasi: public\file -> public/file
        $value = str_replace('\\', '/', $value);

        $decoded = json_decode($value, true);

        return is_array($decoded) ? $decoded : [];
    }
}
