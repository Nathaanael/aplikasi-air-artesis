<!-- MODAL EDIT -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" id="editForm">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit Warga</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" id="editUsername" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="nama" id="editNama" class="form-control" required>
            </div>
            <div class="row">
                <div class="col-6 mb-3">
                    <label class="form-label">RT</label>
                    <input type="text" name="rt" id="editRT" class="form-control" required>
                </div>
                <div class="col-6 mb-3">
                    <label class="form-label">RW</label>
                    <input type="text" name="rw" id="editRW" class="form-control" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" id="editAlamat" class="form-control" rows="3" required></textarea>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </div>
    </form>
  </div>
</div>