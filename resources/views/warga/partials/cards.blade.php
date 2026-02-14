@forelse($data as $row)
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="text-center mb-2">
                @if($row->status_lunas)
                    <span class="badge bg-success">LUNAS</span>
                @else
                    <span class="badge bg-danger">BELUM LUNAS</span>
                @endif
            </div>
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
                    <td>{{ $row->user->warga->formatted_rt_rw ?? '-' }}</td>
                </tr>
                <tr>
                    <td>No. PELANGGAN</td>
                    <td>:</td>
                    <td>{{ str_pad($row->user->id, 3, '0', STR_PAD_LEFT) }}</td>
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
                        <td colspan="3">Rekening Bulan Lalu</td>
                        <td class="text-end">
                            Rp {{ number_format($row->tagihan_bulan_lalu ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="fw-bold">
                        <td colspan="3">Jumlah yang dibayar</td>
                        <td class="text-end">
                            Rp {{
                                number_format(
                                    5000 +
                                    ($row->pemakaian * 2000) -
                                    ($row->tagihan_bulan_lalu ?? 0),
                                    0, ',', '.'
                                )
                            }}
                        </td>
                    </tr>
                </tfoot>
            </table>

        </div>
    </div>

    @empty
        <div class="alert alert-secondary">
            Belum ada tagihan untuk periode ini
        </div>
    @endforelse
    @if($data->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $data->links('pagination::bootstrap-5') }}
        </div>
    @endif