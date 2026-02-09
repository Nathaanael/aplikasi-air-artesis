<!-- MODAL RESET PASSWORD -->
<div class="modal fade" id="resetModal" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">

<form method="POST" id="resetForm">
@csrf

<div class="modal-header">
    <h5 class="modal-title">Reset Password User</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

    <div class="mb-2">
        <label class="form-label">Username</label>
        <input type="text" id="resetUsername" class="form-control" readonly>
    </div>
    @if($errors->has('password'))
        <div class="alert alert-danger">
            {{ $errors->first('password') }}
        </div>
    @endif

    <div class="mb-2">
        <label class="form-label">Password Baru</label>
        <input type="text"
            name="password"
            id="resetPasswordInput"
            class="form-control"
            minlength="4"
            required>

        <div class="invalid-feedback">
            Password minimal 4 karakter
        </div>

        <div class="form-text">
            Gunakan password sederhana (4–6 karakter/angka)
        </div>
    </div>


</div>

<div class="modal-footer">
    <button class="btn btn-primary">
        Simpan Password Baru
    </button>
</div>

</form>

</div>
</div>
</div>
<script>
document.getElementById('resetForm').addEventListener('submit', function(e) {
    const input = document.getElementById('resetPasswordInput');

    if (input.value.length < 4) {
        e.preventDefault();
        input.classList.add('is-invalid');
        return;
    }

    input.classList.remove('is-invalid');
});
</script>
