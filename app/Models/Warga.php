<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warga extends Model
{
    protected $table = 'warga';
    protected $fillable = [
        'user_id',
        'nama',
        'alamat',
        'rt',
        'rw',
        'nomor_pelanggan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

