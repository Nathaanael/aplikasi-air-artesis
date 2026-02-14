<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeterAir extends Model
{
    protected $table = 'meter_air';
    private const ABONEMEN = 5000;
    private const TARIF_PER_M3 = 2000;

    protected $fillable = [
        'user_id',
        'bulan',
        'tahun',
        'stand_lama',
        'stand_kini',
        'pemakaian',
        'tagihan_bulan_lalu',
        'status_lunas',
        // 'total_bayar',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getTotalBayarAttribute()
    {
        return self::ABONEMEN +
            ($this->pemakaian * self::TARIF_PER_M3) -
            ($this->tagihan_bulan_lalu ?? 0);
    }
    public static function prevRecord($userId, $bulan, $tahun)
    {
        return self::where('user_id', $userId)
            ->where(function ($q) use ($bulan, $tahun) {
                $q->where('tahun', '<', $tahun)
                ->orWhere(function ($qq) use ($bulan, $tahun) {
                    $qq->where('tahun', $tahun)
                        ->where('bulan', '<', $bulan);
                });
            })
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->first();
    }

}

