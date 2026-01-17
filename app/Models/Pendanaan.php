<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendanaan extends Model
{
    protected $table = 'pendanaan';
    protected $guarded = [];

    /**
     * Relationship dengan Tenant
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
}
