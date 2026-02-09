<?php

namespace App\Http\Controllers;

use App\Models\MeterAir;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Barryvdh\DomPDF\Facade\Pdf;


class MeterAirExportController extends Controller
{
    public function excel(Request $request)
{
    // VALIDASI — jangan diam-diam fallback
    $request->validate([
        'bulan' => 'required|integer|min:1|max:12',
        'tahun' => 'required|integer|min:2000|max:2100',
    ]);

    $bulan = (int) $request->bulan;
    $tahun = (int) $request->tahun;

    $data = MeterAir::with('user')
        ->where('bulan', $bulan)
        ->where('tahun', $tahun)
        ->orderBy('user_id')
        ->get();

    if ($data->isEmpty()) {
        return back()->with('error', 'Tidak ada data untuk bulan & tahun tersebut');
    }

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $headers = [
        'A1' => 'No',
        'B1' => 'Nama Pelanggan',
        'C1' => 'Alamat',
        'D1' => 'Bulan',
        'E1' => 'Tahun',
        'F1' => 'Pemakaian (M³)',
        'G1' => 'Abonemen',
        'H1' => 'Tarif / M³',
        'I1' => 'Tagihan Bulan Lalu',
        'J1' => 'Total Bayar',
    ];

    foreach ($headers as $cell => $text) {
        $sheet->setCellValue($cell, $text);
    }

    $beban = 5000;
    $tarif = 2000;

    $row = 2;
    $no = 1;

    foreach ($data as $meter) {
        $tunggakan = $meter->tagihan_bulan_lalu ?? 0;
        $total = $beban + ($meter->pemakaian * $tarif) + $tunggakan;

        $sheet->setCellValue("A$row", $no++);
        $sheet->setCellValue("B$row", $meter->user->username);
        $sheet->setCellValue("C$row", $meter->user->alamat ?? '-');
        $sheet->setCellValue("D$row", $bulan);
        $sheet->setCellValue("E$row", $tahun);
        $sheet->setCellValue("F$row", $meter->pemakaian);
        $sheet->setCellValue("G$row", $beban);
        $sheet->setCellValue("H$row", $tarif);
        $sheet->setCellValue("I$row", $tunggakan);
        $sheet->setCellValue("J$row", $total);

        $row++;
    }

    foreach (range('A', 'J') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $filename = "laporan-air-$bulan-$tahun.xlsx";
    $writer = new Xlsx($spreadsheet);

    return response()->streamDownload(
        fn() => $writer->save('php://output'),
        $filename
    );
}

public function notaBulk(Request $request)
{
    $bulan = $request->bulan ?? date('n');
    $tahun = $request->tahun ?? date('Y');

    $meters = MeterAir::with('user')
        ->where('bulan', $bulan)
        ->where('tahun', $tahun)
        ->orderBy('user_id')
        ->get();

    if ($meters->isEmpty()) {
        return back()->with('error','Tidak ada data untuk periode ini');
    }

    $tarif = 2000;
    $beban = 5000;

    foreach ($meters as $m) {
        $m->tunggakan = $m->tagihan_bulan_lalu ?? 0;
        $m->total =
            $beban +
            ($m->pemakaian * $tarif) +
            $m->tunggakan;
    }

    $pdf = Pdf::loadView('air.nota_bulk_pdf', compact(
        'meters','tarif','beban','bulan','tahun'
    ))->setPaper('A4');

    return $pdf->stream("nota-bulk-$bulan-$tahun.pdf");
}
}
