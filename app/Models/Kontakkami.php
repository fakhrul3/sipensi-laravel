<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KontakKami extends Model
{
    protected $table = 'kontak_kami';

    protected $fillable = [
        'nama_lengkap',
        'email',
        'pesan',
    ];
}
