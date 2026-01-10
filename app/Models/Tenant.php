<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    // Nama tabel di SQL kamu adalah 'tenant'
    protected $table = 'tenant'; 
    protected $guarded = [];
}