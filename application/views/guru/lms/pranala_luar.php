<style>
  #drop-area {
      border: 2px dashed #ccc;
      border-radius: 20px;
      width: 500px;
      padding: 50px;
      text-align: center;
      font-size: 1.2em;
      color: #666;
      transition: border-color 0.3s, background-color 0.3s;
  }
  #drop-area.highlight {
      border-color: #007bff;
      background-color: #f0f8ff;
  }
  #status {
      margin-top: 20px;
      font-weight: bold;
  }
  #status .success { color: green; }
  #status .error { color: red; }
</style>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><?php echo $subjudul ?></h3>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <a href="<?= base_url('guru/jadwal-kelas/detail/'.$pertemuan['jadwal_kelas_id']) ?>" class="btn btn-warning">Kembali</a>
          <?php if ($pertemuan['status'] == 0): ?>
              <button class="btn btn-primary" data-toggle="modal" data-target="#modalPranalaLuar" id="btnAddPranala">Tambah Pranala Luar</button>
          <?php endif ?>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th>Link</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if(isset($pranala_luar) && is_array($pranala_luar) && count($pranala_luar) > 0): ?>
                <?php foreach($pranala_luar as $i => $row): ?>
                  <tr>
                    <td><?= $i+1 ?></td>
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                    <td><a href="<?= htmlspecialchars($row['link']) ?>" target="_blank">Lihat</a></td>
                    <td>
                      <?php if ($pertemuan['status'] == 0): ?>
                        <button class="btn btn-sm btn-warning btnEditPranala" 
                          data-id="<?= $row['id'] ?>" 
                          data-judul="<?= htmlspecialchars($row['judul']) ?>" 
                          data-deskripsi="<?= htmlspecialchars($row['deskripsi']) ?>" 
                          data-link="<?= htmlspecialchars($row['link']) ?>"
                          data-toggle="modal" data-target="#modalPranalaLuar">
                          Edit
                        </button>
                      <?php endif ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="5" class="text-center">Belum ada data pranala luar.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- Modal Tambah/Edit Pranala Luar -->
        <div class="modal fade" id="modalPranalaLuar" tabindex="-1" role="dialog" aria-labelledby="modalPranalaLuarLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <form id="formPranalaLuar" method="POST" action="<?= base_url('guru/pertemuan/pranala-luar-save') ?>">
                <div class="modal-header">
                  <h5 class="modal-title" id="modalPranalaLuarLabel">Tambah/Edit Pranala Luar</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <input type="hidden" name="id" id="pranala_id">
                  <input type="hidden" name="pertemuan_id" value="<?= $pertemuan['id'] ?>">
                  <div class="form-group">
                    <label>Judul</label>
                    <input type="text" class="form-control" name="judul" id="pranala_judul" required>
                  </div>
                  <div class="form-group">
                    <label>Deskripsi</label>
                    <input type="text" class="form-control" name="deskripsi" id="pranala_deskripsi" required>
                  </div>
                  <div class="form-group">
                    <label>Link</label>
                    <input type="url" class="form-control" name="link" id="pranala_link" required>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                  <button type="submit" class="btn btn-success">Simpan</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Modal form handler
  document.addEventListener('DOMContentLoaded', function() {
    // Reset modal saat tambah
    document.getElementById('btnAddPranala').addEventListener('click', function() {
      document.getElementById('modalPranalaLuarLabel').innerText = 'Tambah Pranala Luar';
      document.getElementById('pranala_id').value = '';
      document.getElementById('pranala_deskripsi').value = '';
      document.getElementById('pranala_judul').value = '';
      document.getElementById('pranala_link').value = '';
    });
    // Isi modal saat edit
    document.querySelectorAll('.btnEditPranala').forEach(function(btn) {
      btn.addEventListener('click', function() {
        document.getElementById('modalPranalaLuarLabel').innerText = 'Edit Pranala Luar';
        document.getElementById('pranala_id').value = this.getAttribute('data-id');
        document.getElementById('pranala_judul').value = this.getAttribute('data-judul');
        document.getElementById('pranala_deskripsi').value = this.getAttribute('data-deskripsi');
        document.getElementById('pranala_link').value = this.getAttribute('data-link');
      });
    });
  });
</script>