<!DOCTYPE html>
<html>
<head>
    <title>Data Air</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-3">

   <div class="d-flex justify-content-between mb-3 align-items-center">
    <h5>Data Meter Air</h5>

    <div class="d-flex gap-2">
        <a href="{{ route('air.create') }}" class="btn btn-sm btn-primary">
            + Tambah Data
        </a>
        <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary">
            Lihat User
        </a>

        <form method="POST" action="/logout">
            @csrf
            <button class="btn btn-sm btn-danger">Logout</button>
        </form>
    </div>
</div>


    <!-- FILTER -->
    <div class="card p-3 mb-3 shadow-sm">

    <div class="row g-2">
        <div class="col-12">
            <input type="text"
                   id="searchInput"
                   class="form-control"
                   placeholder="Cari nama pelanggan / username...">
        </div>

        <div class="col-6">
            <select id="bulan" class="form-select">
                @foreach(range(1,12) as $m)
                    <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                        {{ date('F', mktime(0,0,0,$m,1)) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-6">
            <select id="tahun" class="form-select">
                @foreach(range(date('Y')-3, date('Y')+1) as $y)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

</div>

<div class="d-flex gap-2 mb-3">
    <button id="btnExportExcel" class="btn btn-success btn-sm mb-3">
        Export Excel
    </button>
    <!-- <a href="{{ route('air.export.excel', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
        class="btn btn-success btn-sm mb-3">
        Export Excel
    </a> -->
    <!-- <a href="{{ route('air.export.nota.bulk', [
        'bulan' => $bulan,
        'tahun' => $tahun
    ]) }}"
    class="btn btn-danger btn-sm mb-3">
    Export Semua Nota
    </a> -->
    <button id="btnExportPdf" class="btn btn-danger btn-sm mb-3">
        Export Semua Nota
    </button>



</div>




<div id="cardContainer">
    @include('air.partials.cards')
</div>

@include('modal.air.edit')


</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
const editModal = document.getElementById('editModal');

editModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;

    const id = button.getAttribute('data-id');
    const username = button.getAttribute('data-username');
    const bulan = button.getAttribute('data-bulan');
    const tahun = button.getAttribute('data-tahun');
    const standLama = parseFloat(button.getAttribute('data-stand-lama')) || 0;
    const standKini = parseFloat(button.getAttribute('data-stand-kini')) || 0;
    const tagihan = button.getAttribute('data-tagihan') || '';

    const form = document.getElementById('editForm');
    form.action = `/air/${id}`;

    document.getElementById('editUsername').value = username;
    document.getElementById('editBulan').value = bulan;
    document.getElementById('editTahun').value = tahun;
    document.getElementById('editStandLama').value = standLama;
    document.getElementById('editStandKini').value = standKini;
    document.getElementById('editPemakaian').value = standKini - standLama;
    document.getElementById('editTagihan').value = tagihan;
});

// Update pemakaian saat stand_kini diubah
document.getElementById('editStandKini').addEventListener('input', function() {
    const standLama = parseFloat(document.getElementById('editStandLama').value) || 0;
    const standKini = parseFloat(this.value) || 0;
    document.getElementById('editPemakaian').value = standKini - standLama;
});
function loadData() {

    const search = document.getElementById('searchInput').value
    const bulan  = document.getElementById('bulan').value
    const tahun  = document.getElementById('tahun').value

    fetch(`{{ route('air.index') }}?search=${encodeURIComponent(search)}&bulan=${bulan}&tahun=${tahun}`, {
        headers: { "X-Requested-With": "XMLHttpRequest" }
    })
    .then(r => r.text())
    .then(html => {
        document.getElementById('cardContainer').innerHTML = html
    })
}

function debounce(fn, delay){
    let t
    return (...args)=>{
        clearTimeout(t)
        t = setTimeout(()=>fn.apply(this,args), delay)
    }
}

document.getElementById('searchInput')
    .addEventListener('input', debounce(loadData, 400))

document.getElementById('bulan')
    .addEventListener('change', loadData)

document.getElementById('tahun')
    .addEventListener('change', loadData)

document.getElementById('btnExportExcel').addEventListener('click', function () {
    const bulan = document.getElementById('bulan').value;
    const tahun = document.getElementById('tahun').value;

    if (!bulan || !tahun) {
        alert('Filter bulan & tahun harus dipilih');
        return;
    }

    const url = `{{ route('air.export.excel') }}?bulan=${bulan}&tahun=${tahun}`;
    window.location.href = url;
});
document.getElementById('btnExportPdf').addEventListener('click', function () {
    const bulan = document.getElementById('bulan').value;
    const tahun = document.getElementById('tahun').value;

    if (!bulan || !tahun) {
        alert('Filter bulan & tahun harus dipilih');
        return;
    }

    const url = `{{ route('air.export.nota.bulk') }}?bulan=${bulan}&tahun=${tahun}`;
    window.open(url, '_blank');
});



</script>

</body>
</html>
