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

    public function getFormattedRtRwAttribute()
    {
        if (empty($this->alamat)) {
            return '-';
        }

        // Jika tidak ada tanda '/'
        if (!str_contains($this->alamat, '/')) {
            return $this->alamat;
        }

        [$rt, $rw] = explode('/', $this->alamat);

        return 'RT ' . trim($rt) . ' / RW ' . trim($rw);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

