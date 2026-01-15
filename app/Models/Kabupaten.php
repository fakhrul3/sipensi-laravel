<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    protected $table = 'kabupaten';

	protected $casts = [
		'provinsi_id' => 'int'
	];

	protected $fillable = [
		'provinsi_id',
		'kode_kabupaten',
		'name'
	];

	public function provinsi()
	{
		return $this->belongsTo(Provinsi::class);
	}

	public function kecamatans()
	{
		return $this->hasMany(Kecamatan::class);
	}

	public function inkubators()
	{
		return $this->hasMany(Inkubator::class);
	}
}
