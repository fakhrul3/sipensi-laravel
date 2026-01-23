<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aktifitas extends Model
{
    // Pastikan nama tabelnya sesuai dengan di database lu
    protected $table = 'aktifitas';

    // Biar bisa input data nantinya (CRUD)
    protected $fillable = ['inkubator_id', 'nama_kegiatan', 'path_photo'];

    public function inkubator()
    {
        return $this->belongsTo(Inkubator::class, 'inkubator_id', 'id');
    }
}