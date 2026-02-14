<!-- Modal Tambah User -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('users.store') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addModalLabel">Tambah User Warga</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Username Login</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <small id="passError" class="text-danger d-none">
                    Password minimal 4 karakter
                </small>

                <label class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="addPassword"
                    class="form-control" required minlength="4">
                    <button type="button" class="btn btn-outline-secondary" onclick="toggleAddPassword()">👁</button>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Nomor Pelanggan</label>
                <input type="text"
                    name="nomor_pelanggan"
                    class="form-control"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Warga</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="row">
                <div class="col-6 mb-3">
                    <label class="form-label">RT</label>
                    <input type="text" name="rt" class="form-control" required>
                </div>
                <div class="col-6 mb-3">
                    <label class="form-label">RW</label>
                    <input type="text" name="rw" class="form-control" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" rows="3" required></textarea>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan User</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
    const passInput = document.getElementById('addPassword');
    const passError = document.getElementById('passError');
    function toggleAddPassword() {
        const p = document.getElementById('addPassword');
        p.type = p.type === 'password' ? 'text' : 'password';
    }
    passInput.addEventListener('input', function () {
        if (this.value.length < 4) {
            passError.classList.remove('d-none');
        } else {
            passError.classList.add('d-none');
        }
    });
</script>
