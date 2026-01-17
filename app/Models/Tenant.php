<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    // Nama tabel di SQL kamu adalah 'tenant'
    protected $table = 'tenant'; 
    protected $guarded = [];

    /**
     * Relationship dengan Inkubator
     */
    public function inkubator()
    {
        return $this->belongsTo(Inkubator::class, 'inkubator_id');
    }

    /**
     * Relationship dengan Pendanaan
     */
    public function pendanaan()
    {
        return $this->hasMany(Pendanaan::class, 'tenant_id');
    }
}