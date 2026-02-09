<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login — Pencatatan Air Artetis</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container vh-100 d-flex align-items-center justify-content-center">
    <div class="card shadow w-100" style="max-width: 400px;">
        <div class="card-body p-4">

            <h4 class="text-center mb-4">Login</h4>

            <form method="POST" action="/login">
                @csrf

                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username"
                        class="form-control @error('username') is-invalid @enderror"
                        value="{{ old('username') }}"
                        
                        required>

                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Password</label>

                    <div class="input-group">
                        <input type="password" name="password" id="password"
                            class="form-control" required>

                        <button type="button" class="btn btn-outline-secondary"
                            onclick="togglePassword()">
                            👁
                        </button>
                    </div>
                </div>
                @error('username')
                    <div class="alert alert-warning py-2">
                        Lupa password? Hubungi admin.
                    </div>
                @enderror
                <button class="btn btn-primary w-100">
                    Login
                </button>

            </form>


        </div>
    </div>
</div>
<script>
    function togglePassword() {
        const p = document.getElementById('password');
        p.type = p.type === 'password' ? 'text' : 'password';
    }
</script>

</body>
</html>
