<!DOCTYPE html>
<html>
<head>
    <title>Input Data Air</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex flex-column min-vh-100">

<main class="flex-fill">
<div class="container py-3">
    <form method="POST" action="{{ route('air.store') }}">
        @csrf
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6">
                        <label>Bulan</label>
                        <select name="bulan" id="bulanSelect" class="form-select">
                            @foreach(range(1,12) as $m)
                            <option value="{{ $m }}" {{ $bulan==$m?'selected':'' }}>
                                {{ date('F', mktime(0,0,0,$m,1)) }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6">
                        <label>Tahun</label>
                        <select name="tahun" id="tahunSelect" class="form-select">
                            @foreach(range(date('Y')-3, date('Y')+1) as $y)
                            <option value="{{ $y }}" {{ $tahun==$y?'selected':'' }}>
                                {{ $y }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Input Meter Air</h5>

                <a href="{{ route('air.index') }}" class="btn btn-sm btn-secondary">
                    ← Kembali
                </a>
            </div>
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif
                @csrf

                <!-- <input type="hidden" name="bulan" value="{{ $bulan }}">
                <input type="hidden" name="tahun" value="{{ $tahun }}"> -->

                <div class="mb-3">
                    <label class="form-label">Warga</label>
                    <select id="userSelect" name="user_id" class="form-select">
                        @foreach($users as $u)
                            <option value="{{ $u->id }}"
                                    data-rt="{{ $u->warga->rt ?? '' }}"
                                    data-rw="{{ $u->warga->rw ?? '' }}">
                                {{ $u->warga->nama ?? '' }}
                            </option>
                        @endforeach
                    </select>

                </div>
                <div class="mb-3">
                    <label class="form-label">RT / RW</label>
                    <input id="rtRwField" class="form-control" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Stand Lama (Auto)</label>
                    <input type="number" id="standLama" class="form-control bg-secondary-subtle" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Stand Kini</label>
                    <input type="number" name="stand_kini" id="standKini" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Pemakaian (M³)</label>
                    <input type="number" id="pemakaian" name="pemakaian" class="form-control" required readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tagihan Bulan Lalu (Opsional)</label>
                    <input
                        type="text"
                        id="tagihanField"
                        name="tagihan_bulan_lalu"
                        class="form-control"
                    >


                </div>

                <button class="btn btn-primary w-100">
                    Simpan Data
                </button>
                
            </form>
        </div>
    </div>

</div>
</main>
@include('partials.footer')
<script>
    const userSelect = document.getElementById('userSelect');
    const rtRwField = document.getElementById('rtRwField');
    const bulanSelect = document.getElementById('bulanSelect');
    const tahunSelect = document.getElementById('tahunSelect');
    const tagihanInput = document.getElementById('tagihanField');

    function updateRT() {
        const opt = userSelect.options[userSelect.selectedIndex];
        const rt = opt.dataset.rt || '';
        const rw = opt.dataset.rw || '';
        rtRwField.value = rt && rw ? `${rt}/${rw}` : '';
    }

    userSelect.addEventListener('change', updateRT);
    updateRT();

    document.getElementById('standKini').addEventListener('input', function() {
        const standLama = parseInt(document.getElementById('standLama').value || 0);
        const kini = parseInt(this.value || 0);
        document.getElementById('pemakaian').value = Math.max(0, kini - standLama);
    });

    async function loadPrevStand() {
        const user = userSelect.value;
        const bulan = bulanSelect.value;
        const tahun = tahunSelect.value;

        const res = await fetch(`/air/prev-stand?user_id=${user}&bulan=${bulan}&tahun=${tahun}`);
        const data = await res.json();

        document.getElementById('standLama').value = data.stand_lama || 0;

        const t = Math.abs(data.tagihan || 0);
        tagihanInput.value = t === 0 ? '' : '-' + t;


        tagihanInput.readOnly = false;
        tagihanInput.classList.remove('bg-secondary-subtle');
    }

    userSelect.addEventListener('change', loadPrevStand);
    bulanSelect.addEventListener('change', loadPrevStand);
    tahunSelect.addEventListener('change', loadPrevStand);
    document.querySelector('form').addEventListener('submit', function () {
            const f = document.getElementById('tagihanField');
            if (f.value) {
                f.value = f.value.replace(/(?!^-)[^0-9]/g, '');
            }

    });
    loadPrevStand();
    

</script>


</body>
</html>
