<!-- EDIT MODAL -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <form id="editForm" method="POST">

        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit Data Meter Air</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Username -->
          <div class="mb-3">
            <label>Username</label>
            <input type="text" id="editUsername" name="username" class="form-control" readonly>
          </div>

          <!-- Bulan & Tahun -->
          <div class="row g-2 mb-3">
            <div class="col">
              <label>Bulan</label>
              <input type="number" id="editBulan" name="bulan" class="form-control">
            </div>
            <div class="col">
              <label>Tahun</label>
              <input type="number" id="editTahun" name="tahun" class="form-control">
            </div>
          </div>

          <!-- Stand Lama -->
          <div class="mb-3">
            <label>Stand Lama</label>
            <input type="number" id="editStandLama" name="stand_lama" class="form-control" readonly>
          </div>

          <!-- Stand Kini -->
          <div class="mb-3">
            <label>Stand Kini</label>
            <input type="number" id="editStandKini" name="stand_kini" class="form-control">
          </div>

          <!-- Pemakaian m³ -->
          <div class="mb-3">
            <label>Pemakaian (m³)</label>
            <input type="number" id="editPemakaian" name="pemakaian" class="form-control" readonly>
        </div>


          <!-- Hutang Lalu -->
          <div class="mb-3">
            <label>Tagihan Bulan Lalu</label>
            <input type="number" id="editTagihan" name="tagihan_bulan_lalu" class="form-control">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>
