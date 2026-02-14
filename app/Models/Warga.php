<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function setNomorPelangganAttribute($value)
    {
        $this->attributes['nomor_pelanggan'] =
            str_pad((int)$value, 3, '0', STR_PAD_LEFT);
    }

    public static function insertWithShift(int $target, array $data)
    {
        DB::transaction(function () use ($target, $data) {

            $rows = self::orderByRaw('CAST(nomor_pelanggan AS UNSIGNED) DESC')
                ->lockForUpdate()
                ->get();

            foreach ($rows as $w) {
                if ((int)$w->nomor_pelanggan >= $target) {
                    $w->nomor_pelanggan = ((int)$w->nomor_pelanggan) + 1;
                    $w->save();
                }
            }

            $data['nomor_pelanggan'] = $target;
            self::create($data);
        });
    }


    public function deleteWithShift()
    {
        DB::transaction(function () {

            $deleted = (int)$this->nomor_pelanggan;
            $this->delete();

            $rows = self::orderByRaw('CAST(nomor_pelanggan AS UNSIGNED) ASC')
                ->lockForUpdate()
                ->get();

            foreach ($rows as $w) {
                if ((int)$w->nomor_pelanggan > $deleted) {
                    $w->nomor_pelanggan = ((int)$w->nomor_pelanggan) - 1;
                    $w->save();
                }
            }
        });
    }

    public function getFormattedRtRwAttribute()
    {
        if (empty($this->alamat)) {
            return '-';
        }

        $alamat = trim($this->alamat);

        if (preg_match('/^\d{2}\/\d{2}$/', $alamat)) {
            [$rt, $rw] = explode('/', $alamat);

            return 'RT ' . $rt . ' / RW ' . $rw;
        }

        return $alamat;
    }

}
