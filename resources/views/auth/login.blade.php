<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login — Pencatatan Air Artetis</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #4B1F6F, #6A2C91);
        }

        .login-card {
            border: none;
            border-top: 6px solid #F4C430;
            border-radius: 14px;
        }

        .btn-unika {
            background-color: #4B1F6F;
            border: none;
            color: white;
            font-weight: 600;
        }

        .btn-unika:hover {
            background-color: #3a1656;
        }

        .form-control:focus {
            border-color: #6A2C91;
            box-shadow: 0 0 0 0.2rem rgba(106,44,145,.25);
        }

        .login-title {
            color: #4B1F6F;
            font-weight: 700;
        }
    </style>
</head>

<body>

<div class="container vh-100 d-flex align-items-center justify-content-center">
    <div class="card shadow w-100 login-card" style="max-width: 420px;">
        <div class="card-body p-4">

            <div class="text-center mb-3">
                <img src="{{ asset('images/logo_artetis.jpeg') }}"
                    alt="Logo Unika"
                    style="height:120px; object-fit:contain;">
            </div>

            <h4 class="text-center mb-4 login-title">
                Login Sistem Meter Air
            </h4>


            <form method="POST" action="/login">
                @csrf

                <div class="mb-3">
                    <label class="fw-semibold">Username</label>
                    <input type="text" name="username"
                        class="form-control @error('username') is-invalid @enderror"
                        value="{{ old('username') }}"
                        required>

                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="fw-semibold">Password</label>

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

                <button class="btn btn-unika w-100 mt-2">
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
