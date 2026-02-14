<!DOCTYPE html>
<html>
<head>
    <title>Import Meter Air</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex flex-column min-vh-100">
    
<main class="flex-fill">
<div class="container py-4">

    <div class="card shadow-sm">
        <div class="card-body">

            <h5 class="mb-3">Import Data Meter Air (Excel)</h5>

            {{-- pesan sukses / gagal --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('failed_rows'))
                <div class="alert alert-danger">
                    <b>Detail Gagal:</b>
                    <ul>
                        @foreach(session('failed_rows') as $f)
                            <li>{{ $f }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- FORM IMPORT --}}
            <form action="{{ route('meter.import') }}"
                    method="POST"
                    enctype="multipart/form-data">

                @csrf

                <div class="mb-3">
                    <label class="form-label">File Excel</label>
                    <input type="file"
                            name="file"
                            class="form-control"
                            required>
                </div>

                <button class="btn btn-warning">
                    Import Sekarang
                </button>

                <a href="{{ route('air.index') }}"
                   class="btn btn-secondary">
                   Kembali
                </a>

            </form>

            <hr>

            <div class="small text-muted">
                Template kolom wajib:<br>
                No | Nomor Pelanggan | Nama | Alamat | Bulan | Tahun | Pemakaian | Abonemen | Tarif | Tagihan Lalu | Total | Status<br>
            </div>

        </div>
    </div>

</div>
</main>
@include('partials.footer')
</body>
</html>
