<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Warga;
use App\Models\MeterAir;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MeterAirImportController extends Controller
{
    public function form()
    {
        return view('air.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        $spreadsheet = IOFactory::load($request->file('file'));
        $rows = $spreadsheet->getActiveSheet()->toArray();

        if (count($rows) < 2) {
            return back()->with('error', 'File kosong');
        }

        $errors = [];
        $prepared = [];

        // =========================
        // PASS 1 — VALIDASI TOTAL
        // =========================
        foreach ($rows as $i => $row) {

            if ($i === 0) continue;

            if (count($row) < 11) {
                $errors[] = "Baris ".($i+1)." kolom tidak lengkap";
                continue;
            }

            [
                $no,
                $nomorPelanggan,
                $nama,
                $alamat,
                $bulan,
                $tahun,
                $pemakaian,
                $abonemenExcel,
                $tarifExcel,
                $tagihanLalu,
                $totalExcel
            ] = $row;

            $nomorPelanggan = trim((string)$nomorPelanggan);
            $bulan = (int)$bulan;
            $tahun = (int)$tahun;
            $pemakaian = (int)$pemakaian;
            $tagihanLalu = (int)($tagihanLalu ?? 0);

            if (!$nomorPelanggan || !$bulan || !$tahun) {
                $errors[] = "Baris ".($i+1)." field wajib kosong";
                continue;
            }

            // =========================
            // RULE: WARGA WAJIB ADA
            // =========================
            $warga = Warga::where('nomor_pelanggan', $nomorPelanggan)->first();

            if (!$warga) {
                $errors[] = "Baris ".($i+1).
                    " warga dengan nomor_pelanggan [$nomorPelanggan] belum terdaftar";
                continue;
            }

            // =========================
            // RULE: LUNAS TIDAK BOLEH DIIMPORT ULANG
            // =========================
            $record = MeterAir::where('user_id',$warga->user_id)
                ->where('bulan',$bulan)
                ->where('tahun',$tahun)
                ->first();

            if ($record && $record->status_lunas == 1) {
                $errors[] = "Baris ".($i+1).
                    " periode sudah lunas — tidak bisa diimport ulang";
                continue;
            }

            // =========================
            // HITUNG STAND
            // =========================
            $bulanPrev = $bulan == 1 ? 12 : $bulan-1;
            $tahunPrev = $bulan == 1 ? $tahun-1 : $tahun;

            $prev = MeterAir::where('user_id',$warga->user_id)
                ->where('bulan',$bulanPrev)
                ->where('tahun',$tahunPrev)
                ->first();

            $standLama = $prev?->stand_kini ?? 0;
            $standKini = $standLama + $pemakaian;

            $prepared[] = [
                'record' => $record,
                'data' => [
                    'user_id' => $warga->user_id,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'stand_lama' => $standLama,
                    'stand_kini' => $standKini,
                    'pemakaian' => $pemakaian,
                    'tagihan_bulan_lalu' => $tagihanLalu,
                    'status_lunas' => 0,
                ]
            ];
        }

        // =========================
        // ADA ERROR → STOP TOTAL
        // =========================
        if (!empty($errors)) {
            return back()
                ->with('error','Import dibatalkan — ada data tidak valid')
                ->with('failed_rows',$errors);
        }

        // =========================
        // PASS 2 — SIMPAN
        // =========================
        DB::transaction(function() use ($prepared) {
            foreach ($prepared as $row) {
                if ($row['record']) {
                    $row['record']->update($row['data']);
                } else {
                    MeterAir::create($row['data']);
                }
            }
        });

        return back()->with(
            'success',
            'Import berhasil — semua data tersimpan'
        );
    }
}
