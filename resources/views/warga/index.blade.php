<!DOCTYPE html>
<html>
<head>
    <title>Tagihan Air Saya</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex flex-column min-vh-100">

<main class="flex-fill">
<div class="container py-3">

    <!-- HEADER -->
    <div class="d-flex justify-content-between mb-3 align-items-center">
        <h5 class="mb-0">
            Tagihan Air Saya —
            <span class="text-primary">
                {{ auth()->user()->username }}
            </span>
        </h5>


        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-sm btn-danger">Logout</button>
        </form>
    </div>

    <!-- FILTER -->
    <!-- <form method="GET" action="{{ route('warga.index') }}" class="card p-3 mb-3 shadow-sm"> -->
        <div class="card p-3 mb-3 shadow-sm">

            <div class="row g-2">
                <div class="col-6">
                    <select id="bulan" name="bulan" class="form-select">
                        @foreach(range(1,12) as $m)
                            <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                {{ date('F', mktime(0,0,0,$m,1)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-6">
                    <select id="tahun" name="tahun" class="form-select">
                        @foreach(range(date('Y')-3, date('Y')+1) as $y)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    <!-- </form> -->

    <div id="tagihanContainer">
        @include('warga.partials.cards')
    </div>

</div>
</main>
@include('partials.footer')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function loadData() {
        const bulan = document.getElementById('bulan').value
        const tahun = document.getElementById('tahun').value

        fetch(`{{ route('warga.index') }}?bulan=${bulan}&tahun=${tahun}`, {
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
        .then(r => r.text())
        .then(html => {
            document.getElementById('tagihanContainer').innerHTML = html
        })
    }

    document.getElementById('bulan').addEventListener('change', loadData)
    document.getElementById('tahun').addEventListener('change', loadData)
</script>
</body>
</html>
