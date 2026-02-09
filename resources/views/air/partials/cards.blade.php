@forelse($data as $row)
<div class="card mb-4 shadow-sm">
    <div class="card-body">

        <div class="text-center mb-3">
            <h6 class="fw-bold mb-0">AIR BERSIH TIRTA DARUSSALAM</h6>
            <small>Pongangan Gunungpati</small>
        </div>

        <table class="table table-sm table-borderless mb-2">
            <tr>
                <td width="35%">NAMA PELANGGAN</td>
                <td width="5%">:</td>
                <td>{{ $row->user->username }}</td>
            </tr>
            <tr>
                <td>ALAMAT</td>
                <td>:</td>
                <td>{{ $row->user->warga->alamat ?? '-' }}</td>
            </tr>
            <tr>
                <td>No. PELANGGAN</td>
                <td>:</td>
                <td>{{ $row->user->warga->nomor_pelanggan ?? '-' }}</td>
            </tr>
            <tr>
                <td>REKENING BULAN</td>
                <td>:</td>
                <td>
                    {{ strtoupper(date('F', mktime(0,0,0,$row->bulan,1))) }}
                    {{ $row->tahun }}
                </td>
            </tr>
        </table>

        <hr>

        <!-- TARIF & PEMAKAIAN -->
        <table class="table table-bordered table-sm">
            <thead class="table-light text-center">
                <tr>
                    <th>M³</th>
                    <th>KETERANGAN</th>
                    <th>HARGA / M³</th>
                    <th>JUMLAH</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">-</td>
                    <td>Abonemen</td>
                    <td class="text-center">-</td>
                    <td class="text-end">Rp 5.000</td>
                </tr>
                <tr>
                    <td class="text-center">{{ $row->pemakaian }}</td>
                    <td>Pemakaian</td>
                    <td class="text-end">Rp 2.000</td>
                    <td class="text-end">
                        Rp {{ number_format($row->pemakaian * 2000, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td colspan="3">Tagihan Bulan Lalu</td>
                    <td class="text-end">
                        @if($row->tagihan_bulan_lalu < 0)
                            - Rp {{ number_format(abs($row->tagihan_bulan_lalu), 0, ',', '.') }}
                        @else
                            Rp {{ number_format($row->tagihan_bulan_lalu, 0, ',', '.') }}
                        @endif
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="fw-bold">
                    <td colspan="3">Jumlah yang dibayar</td>
                    <td class="text-end">
                        Rp {{ number_format($row->total_bayar, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>

        <!-- ACTION -->
        <div class="d-flex gap-2 mt-3">
            <button
                class="btn btn-warning btn-sm"
                data-id="{{ $row->id }}"
                data-username="{{ $row->user->username }}"
                data-bulan="{{ $row->bulan }}"
                data-tahun="{{ $row->tahun }}"
                data-stand-lama="{{ $row->stand_lama }}"
                data-stand-kini="{{ $row->stand_kini }}"
                data-tagihan="{{ $row->tagihan_bulan_lalu }}"
                data-bs-toggle="modal"
                data-bs-target="#editModal"
            >
                Edit
            </button>

            <form action="{{ route('air.destroy', $row->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm"
                        onclick="return confirm('Yakin hapus data?')">
                    Hapus
                </button>
            </form>
        </div>

    </div>
</div>
@empty
<div class="alert alert-secondary">
    Belum ada data
</div>
@endforelse

@if($data->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $data->links('pagination::bootstrap-5') }}
    </div>
@endif