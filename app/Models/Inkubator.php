<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Laporan;

class Inkubator extends Model
{
    protected $table = 'inkubator';

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

    /**
     * RELASI LAPORAN
     * tabel laporan
     * FK: laporan.inkubator_id -> inkubator.id
     */
    public function laporan()
    {
        return $this->hasMany(Laporan::class, 'inkubator_id', 'id');
    }

    // Tambahkan di dalam class Inkubator
    public function aktifitas()
    {
        return $this->hasMany(Aktifitas::class, 'inkubator_id', 'id');
}
}
