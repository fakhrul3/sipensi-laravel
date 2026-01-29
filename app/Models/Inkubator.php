<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Laporan;
use App\Models\Aktifitas;
use App\Models\Tenant;

class Inkubator extends Model
{
    protected $table = 'inkubator';

    /**
     * Mass Assignment
     * Lu harus daftarin semua kolom yang mau lu simpan lewat form di sini.
     * Ini bakal nyelesain error "Add [user_id] to fillable property" yang tadi muncul.
     */
    protected $fillable = [
        'user_id',
        'nama_inkubator',
        'induk_inkubator',
        'nama_pimpinan',
        'email',
        'no_kontak',
        'alamat_kantor',
        'website',
        'kode_provinsi',
        'provinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'logo',
        'jenis_inkubator',
        'facebook',
        'instagram',
        'tiktok',
        'path_legalitas',
        'path_ruang_pelatihan',
        'path_ruang_komunikasi',
        'path_spesialisasi_inkubasi',
        'deskripsi',
        'status', // Opsional jika ada status verifikasi
    ];

    /**
     * Casting 
     * Karena path_legalitas di database lu bentuknya JSON ["path/file.pdf"]
     */
    protected $casts = [
        'path_legalitas' => 'array',
    ];

    // --- RELASI ---

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'kode_provinsi', 'kode_provinsi');
    }

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id', 'id');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id', 'id');
    }

    public function laporan()
    {
        return $this->hasMany(Laporan::class, 'inkubator_id', 'id');
    }

    public function aktifitas()
    {
        return $this->hasMany(Aktifitas::class, 'inkubator_id', 'id');
    }

    public function tenant()
    {
        return $this->hasMany(Tenant::class, 'inkubator_id');
    }
}