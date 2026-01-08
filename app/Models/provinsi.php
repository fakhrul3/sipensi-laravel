<?php
// app/Models/Provinsi.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    protected $table = 'provinsi';

    public function inkubators()
    {
        // hasMany(Model, foreignKey di Inkubator, localKey di Provinsi)
        return $this->hasMany(Inkubator::class, 'kode_provinsi', 'kode_provinsi');
    }
}