<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warga extends Model
{
    protected $fillable = [
        'user_id',
        'nama',
        'alamat_rtrw',
        'nomor_pelanggan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
