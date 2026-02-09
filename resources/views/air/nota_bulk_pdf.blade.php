<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>
body {
    font-family: sans-serif;
    font-size: 10px;
}

table.grid {
    width: 100%;
    border-collapse: collapse;
}

table.grid td {
    width: 50%;
    vertical-align: top;
    padding: 6px;
}

.nota {
    border: 1px solid #000;
    padding: 6px;
    height: 250px;
}

.header {
    text-align: center;
    font-weight: bold;
    font-size: 11px;
}

.sub {
    text-align: center;
    font-size: 9px;
    margin-bottom: 4px;
}

table.inner {
    width: 100%;
    border-collapse: collapse;
}

table.inner td,
table.inner th {
    border: 1px solid #000;
    padding: 3px;
    font-size: 9px;
}

.small td {
    border: none;
    padding: 2px;
}

.page-break {
    page-break-after: always;
}
</style>

</head>
<body>

@php
$chunks = $meters->chunk(8); // 8 nota per halaman
@endphp

@foreach($chunks as $page)

<table class="grid">

@foreach($page->chunk(2) as $row)
<tr>

@foreach($row as $meter)
<td>

<div class="nota">

<div class="header">AIR BERSIH TIRTA DARUSSALAM</div>
<div class="sub">Pongangan Gunungpati</div>

<table class="small">
<tr><td>Nama</td><td>: {{ $meter->user->username }}</td></tr>
<tr><td>Alamat</td><td>: {{ $meter->user->alamat }}</td></tr>
<tr><td>No</td><td>: {{ str_pad($meter->user->id,3,'0',STR_PAD_LEFT) }}</td></tr>
<tr>
<td>Periode</td>
<td>: {{ date('M', mktime(0,0,0,$meter->bulan,1)) }} {{ $meter->tahun }}</td>
</tr>
</table>

<table class="inner">
<tr>
<th>M³</th>
<th>Ket</th>
<th>Harga</th>
<th>Jumlah</th>
</tr>

<tr>
<td>-</td>
<td>Abn</td>
<td>-</td>
<td>{{ number_format($beban) }}</td>
</tr>

<tr>
<td>{{ $meter->pemakaian }}</td>
<td>Pakai</td>
<td>{{ number_format($tarif) }}</td>
<td>{{ number_format($meter->pemakaian * $tarif) }}</td>
</tr>

<tr>
<td colspan="3">Tunggakan</td>
<td>{{ number_format($meter->tunggakan) }}</td>
</tr>

<tr>
<td colspan="3"><b>Total</b></td>
<td><b>{{ number_format($meter->total) }}</b></td>
</tr>
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
