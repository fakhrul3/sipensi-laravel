<?php

// app/Models/Inkubator.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inkubator extends Model
{
    // Pastikan ini 'inkubator' (sesuai file SQL kamu), bukan 'inkubators'
    protected $table = 'inkubator'; 
    protected $guarded = [];

    /**
     * Relationship dengan Tenant
     */
    public function tenant()
    {
        return $this->hasMany(Tenant::class, 'inkubator_id');
    }
}