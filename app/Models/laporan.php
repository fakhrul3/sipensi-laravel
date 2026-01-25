<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporan';

    protected $casts = [
        'path_laporan' => 'array', // otomatis decode JSON
    ];

    public function inkubator()
    {
        return $this->belongsTo(Inkubator::class, 'inkubator_id', 'id');
    }
}
