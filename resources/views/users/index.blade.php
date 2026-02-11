<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Warga</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

<main class="flex-fill">
<div class="container py-3" style="max-width:900px">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Daftar Warga</h5>
        <a href="{{ route('air.index') }}" class="btn btn-sm btn-secondary me-2">
            ← Kembali ke Air
        </a>
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            + Tambah Warga
        </button>
    </div>

    <!-- ALERT -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <!-- TABEL USER -->
    <div class="mb-3">
        <input type="text"
            id="liveSearch"
            class="form-control"
            placeholder="Ketik untuk mencari username / nama / alamat...">
    </div>
    <div id="userTable">
        @include('users.partials.table')
    </div>

    @include('modal.users.edit')
    @include('modal.users.add')
    @include('modal.users.reset')

</div>

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- JS UNTUK MEMASUKKAN DATA KE MODAL -->
<script>
    let timer = null;
    const resetModal = document.getElementById('resetModal');
    const editModal = document.getElementById('editModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        const id = button.getAttribute('data-id');
        const username = button.getAttribute('data-username');
        const nama = button.getAttribute('data-nama');
        const rt = button.getAttribute('data-rt');
        const rw = button.getAttribute('data-rw');
        const alamat = button.getAttribute('data-alamat');

        const form = document.getElementById('editForm');
        form.action = `/users/${id}`; // route update

        document.getElementById('editUsername').value = username;
        document.getElementById('editNama').value = nama;
        document.getElementById('editRT').value = rt;
        document.getElementById('editRW').value = rw;
        document.getElementById('editAlamat').value = alamat;
    });
    resetModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        const id = button.getAttribute('data-id');
        const username = button.getAttribute('data-username');

        document.getElementById('resetUsername').value = username;

        const form = document.getElementById('resetForm');
        form.action = `/users/${id}/reset-password`;
    });
    document.getElementById('liveSearch').addEventListener('keyup', function () {
        clearTimeout(timer);

        const q = this.value;

        timer = setTimeout(() => {
            fetch(`/users?q=${encodeURIComponent(q)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(html => {
                document.getElementById('userTable').innerHTML = html;
            });
        }, 400); // debounce 400ms
    });
    document.addEventListener('click', function(e) {
    if (e.target.closest('#userTable .pagination a')) {
        e.preventDefault();

        fetch(e.target.href, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.text())
        .then(html => {
            document.getElementById('userTable').innerHTML = html;
        });
    }
});
</script>
@if($errors->has('password'))
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = new bootstrap.Modal(document.getElementById('resetModal'));
        modal.show();
    });
    </script>
@endif
</main>
@include('partials.footer')
</body>
</html>
