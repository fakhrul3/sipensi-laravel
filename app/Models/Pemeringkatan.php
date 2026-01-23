<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemeringkatan extends Model
{
    protected $table = 'pemeringkatan';

    protected $fillable = [
        'inkubator_id',
        'grade',
        'tanggal_sk',
        'masa_berlaku_sk',
        'tanggal_habis_sk',
        'file_pemeringkatan',
        'file_pengelola',
        'file_profil_lembaga',
    ];
}
