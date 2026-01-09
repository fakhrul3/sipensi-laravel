<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManajemenGambar extends Model
{
    protected $table = 'manajemen_gambar';

    protected $fillable = [
        'option_gambar',
        'path_gambar',
        'is_show'
    ];
}
