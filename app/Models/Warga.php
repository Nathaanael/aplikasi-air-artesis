<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warga extends Model
{
    protected $fillable = [
        'user_id',
        'nomor_pelanggan',
        'nama',
        'rt',
        'rw',
        'alamat',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
