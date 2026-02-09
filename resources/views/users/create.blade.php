<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Warga</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-3" style="max-width:600px">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Tambah User Warga</h5>

        <a href="{{ route('air.index') }}" class="btn btn-sm btn-secondary">
            ← Kembali
        </a>
    </div>

    <!-- SUCCESS -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- ERROR -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Periksa input:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST" action="{{ route('users.store') }}">
                @csrf

                <!-- USERNAME -->
                <div class="mb-3">
                    <label class="form-label">Username Login</label>
                    <input name="username"
                           
                           class="form-control"
                           required>
                </div>

                <!-- PASSWORD -->
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            required>
                        <button type="button" class="btn btn-outline-secondary"
                                onclick="togglePassword()">
                            👁
                        </button>
                    </div>
                </div>

                <!-- NAMA -->
                <div class="mb-3">
                    <label class="form-label">Nama Warga</label>
                    <input name="nama"
                           value="{{ old('nama') }}"
                           class="form-control"
                           required>
                </div>

                <!-- RT RW -->
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label">RT</label>
                        <input name="rt"
                               value="{{ old('rt') }}"
                               class="form-control"
                               required>
                    </div>

                    <div class="col-6 mb-3">
                        <label class="form-label">RW</label>
                        <input name="rw"
                               value="{{ old('rw') }}"
                               class="form-control"
                               required>
                    </div>
                </div>

                <!-- ALAMAT -->
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat"
                              class="form-control"
                              rows="3"
                              required>{{ old('alamat') }}</textarea>
                </div>

                <button class="btn btn-primary w-100">
                    Simpan User
                </button>

            </form>

        </div>
    </div>

</div>
<script>
function togglePassword() {
    const p = document.getElementById('password');
    if(p.type === 'password'){
        p.type = 'text';
    } else {
        p.type = 'password';
    }
}
</script>

</body>
</html>
