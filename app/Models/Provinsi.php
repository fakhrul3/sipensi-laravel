<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    protected $table = 'provinsi';

    // relasi ke inkubator (cukup ini)
    public function inkubators()
    {
        return $this->hasMany(Inkubator::class, 'kode_provinsi', 'kode_provinsi');
    }
}
