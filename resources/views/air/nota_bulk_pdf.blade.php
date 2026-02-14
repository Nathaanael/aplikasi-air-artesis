<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    @page {
        size: A4;
        margin: 10mm; /* Margin standar printer */
    }
    body {
        font-family: "Times New Roman", Times, serif;
        font-size: 9pt;
        line-height: 1.1; /* Line height diperkecil agar muat */
        margin: 0;
        padding: 0;
    }

    /* Grid Utama */
    table.main-grid {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }
    table.main-grid td {
        width: 50%;
        padding: 2px; /* Padding minimal antar kotak */
        vertical-align: top;
    }

    /* KOTAK NOTA */
    .nota-box {
        border: 2px solid #000;
        width: 100%;
        /* Tinggi diset 90mm agar 3 baris muat pas (90x3 = 270mm < 277mm space available) */
        height: 90mm; 
        box-sizing: border-box;
        position: relative;
        overflow: hidden;
    }

    /* Header */
    .header {
        text-align: center;
        border-bottom: 1px solid #000;
        padding: 2px 0;
        height: 30px;
    }
    .header h1 {
        margin: 0;
        font-size: 11pt;
        font-weight: bold;
        text-transform: uppercase;
    }
    .header h2 {
        margin: 0;
        font-size: 9pt;
        font-weight: normal;
    }

    /* Info Section */
    table.info-section {
        width: 100%;
        border-collapse: collapse;
        /* Tinggi area info dijaga agar tidak mendorong tabel tagihan keluar */
        height: 75px; 
    }
    .col-info-left {
        width: 60%;
        padding: 2px 4px;
        vertical-align: top;
        border-right: 1px solid #000;
    }
    .col-info-right {
        width: 40%;
        padding: 0;
        vertical-align: top;
    }

    /* Detail Pelanggan */
    table.customer-details { width: 100%; font-size: 9pt; }
    table.customer-details td { padding: 0; vertical-align: top; }
    .val-name { font-weight: bold; }

    /* Info Kanan (Tarif/Meter) */
    .right-box-header {
        text-align: center;
        font-style: italic;
        border-bottom: 1px solid #000;
        font-size: 8pt;
        background-color: #f0f0f0;
    }
    .right-box-content { padding: 1px 4px; font-size: 8pt; }
    .border-bottom { border-bottom: 1px solid #000; }

    /* Tabel Tagihan */
    table.bill-table {
        width: 100%;
        border-collapse: collapse;
        border-top: 1px solid #000;
        font-size: 9pt;
        margin-top: 2px;
    }
    table.bill-table th {
        border: 1px solid #000;
        padding: 2px;
        text-align: center;
        font-size: 8pt;
        background-color: #fff;
    }
    table.bill-table td {
        border: 1px solid #000;
        padding: 2px 4px;
        vertical-align: middle;
        height: 14px; /* Tinggi baris diperkecil */
    }
    
    .ttd-col {
        text-align: center;
        vertical-align: top !important;
        padding: 2px;
        font-size: 8pt;
    }

    .total-row td {
        border-top: 2px solid #000;
        font-weight: bold;
        font-size: 11pt;
        padding: 3px 4px;
        background-color: #eee;
    }

    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .page-break { page-break-after: always; }
</style>
</head>
<body>

@php
    // Chunk 6 item per halaman
    $chunks = $meters->chunk(6); 
@endphp

@foreach($chunks as $page)

<table class="main-grid">
    @foreach($page->chunk(2) as $row)
    <tr>
        @foreach($row as $meter)
        <td>
            <div class="nota-box">
                <div class="header">
                    <h1>AIR BERSIH TIRTA DARUSSALAM</h1>
                    <h2>Pongangan Gunungpati</h2>
                </div>

                <table class="info-section">
                    <tr>
                        <td class="col-info-left">
                            <table class="customer-details">
                                <tr>
                                    <td width="20%">NAMA</td>
                                    <td width="5%">:</td>
                                    <td class="val-name">{{ substr($meter->user->username, 0, 18) }}</td>
                                </tr>
                                <tr>
                                    <td>ALAMAT</td>
                                    <td>:</td>
                                    <td>{{ $meter->user->warga->formatted_rt_rw ?? 'RT 04 / RW II' }}</td>
                                </tr>
                                <tr>
                                    <td>No. PLG</td>
                                    <td>:</td>
                                    <td>{{ str_pad($meter->user->warga->nomor_pelanggan ?? '003', 3, '0', STR_PAD_LEFT) }}</td>
                                </tr>
                                <tr>
                                    <td>BULAN</td>
                                    <td>:</td>
                                    <td style="text-transform: uppercase;">
                                        {{ \Carbon\Carbon::createFromDate($meter->tahun, $meter->bulan, 1)->locale('id')->isoFormat('MMM Y') }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td class="col-info-right">
                            <div class="right-box-header">Tarif</div>
                            <div class="right-box-content border-bottom">
                                <table width="100%">
                                    <tr><td>Dasar</td><td>: {{ number_format($beban, 0, ',', '.') }}</td></tr>
                                    <tr><td>1 M&sup3;</td><td>: {{ number_format($tarif, 0, ',', '.') }}</td></tr>
                                </table>
                            </div>
                            <div class="right-box-header">Meteran</div>
                            <div class="right-box-content">
                                <table width="100%">
                                    <tr><td width="40%">Lalu</td><td>: {{ $meter->stand_lama }}</td></tr>
                                    <tr><td>Kini</td><td>: {{ $meter->stand_kini }}</td></tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>

                <table class="bill-table">
                    <thead>
                        <tr>
                            <th width="8%">M&sup3;</th>
                            <th width="32%">KET</th>
                            <th width="20%">HARGA</th>
                            <th width="20%">JUMLAH</th>
                            <th width="20%" class="ttd-col" rowspan="4" style="border-bottom: 1px solid #000; vertical-align: top;">
                                Tanda Tangan<br><br>
                                <span style="font-size:7pt;">(Petugas)</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">-</td>
                            <td>Beban</td>
                            <td class="text-center">-</td>
                            <td class="text-right">{{ number_format($beban, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="text-center">{{ $meter->pemakaian }}</td>
                            <td>Pakai</td>
                            <td class="text-right">{{ number_format($tarif, 0, ',', '.') }}</td>
                            <td class="text-right">
                                {{ $meter->pemakaian > 0 ? number_format($meter->pemakaian * $tarif, 0, ',', '.') : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Lalu</td>
                            <td></td>
                            <td class="text-right">
                                {{ $meter->tunggakan != 0 ? number_format($meter->tunggakan, 0, ',', '.') : '-' }}
                            </td>
                        </tr>
                        <tr class="total-row">
                            <td colspan="3">Total : Rp</td>
                            <td class="text-right" style="font-size: 11pt;">{{ number_format($meter->total, 0, ',', '.') }}</td>
                            <td style="border: 1px solid #000;"></td> 
                        </tr>
                    </tbody>
                </table>
            </div>
        </td>
        @endforeach

        @if($row->count() == 1)
            <td></td>
        @endif
    </tr>
    @endforeach
</table>

@if(!$loop->last)
    <div class="page-break"></div>
@endif

@endforeach

</body>
</html>