<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    // Nama tabel di database
    protected $table = 'tenant';

    // Mass assignment
    protected $guarded = [];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Relasi ke tabel produk
     * Satu tenant punya banyak produk (galeri produk)
     * 
     * NOTE: Nama method galeriProduk() untuk menghindari konflik dengan kolom 'produk' di tabel tenant
     */
    public function galeriProduk()
    {
        return $this->hasMany(\App\Models\Produk::class, 'tenant_id');
    }
}
