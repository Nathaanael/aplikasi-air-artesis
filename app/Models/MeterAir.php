<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeterAir extends Model
{
    protected $table = 'meter_air';

    protected $fillable = [
        'user_id',
        'bulan',
        'tahun',
        'stand_lama',
        'stand_kini',
        'pemakaian',
        'tagihan_bulan_lalu',
        'total_bayar',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

