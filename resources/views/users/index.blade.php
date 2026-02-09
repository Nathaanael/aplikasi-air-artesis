<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Warga</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

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
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Nama</th>
                        <th>RT/RW</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->warga->nama }}</td>
                            <td>{{ $user->warga->rt }}/{{ $user->warga->rw }}</td>
                            <td>{{ $user->warga->alamat }}</td>
                            <td>
                                <!-- Tombol Edit -->
                                <button type="button" class="btn btn-sm btn-warning"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal"
                                        data-id="{{ $user->id }}"
                                        data-username="{{ $user->username }}"
                                        data-nama="{{ $user->nama }}"
                                        data-rt="{{ $user->rt }}"
                                        data-rw="{{ $user->rw }}"
                                        data-alamat="{{ $user->alamat }}">
                                    Edit
                                </button>
                                <button class="btn btn-sm btn-info"
                                        data-bs-toggle="modal"
                                        data-bs-target="#resetModal"
                                        data-id="{{ $user->id }}"
                                        data-username="{{ $user->username }}">
                                    Reset Password
                                </button>

                                <!-- Tombol Hapus -->
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada data warga.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @include('modal.users.edit')
    @include('modal.users.add')
    @include('modal.users.reset')

</div>

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- JS UNTUK MEMASUKKAN DATA KE MODAL -->
<script>
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
</script>
@if($errors->has('password'))
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = new bootstrap.Modal(document.getElementById('resetModal'));
        modal.show();
    });
    </script>
@endif

</body>
</html>
