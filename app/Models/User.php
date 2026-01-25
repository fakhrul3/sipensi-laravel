<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Inkubator;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Field yang boleh diisi secara mass-assignment
     */
    protected $fillable = [
        'username',
        'password',
        'is_admin',
        'is_verify',
        'verify_token', 
    ];

        'verify_token',
    ];

    /**
     * Find user by username for authentication
     */
    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relasi ke tabel Inkubator
     */
    public function inkubator()
    {
        return $this->hasOne(Inkubator::class, 'user_id', 'id');
    }

    /**
     * Casting attributes
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            // 'is_verify' dihapus dari boolean agar bisa menyimpan angka 0, 1, dan 2
            'is_verify' => 'integer', 
        ];
    }
}