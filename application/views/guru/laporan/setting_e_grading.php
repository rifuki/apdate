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
        <form action="<?= base_url('guru/setting/e-grading') ?>" method="POST">
          <strong>SETTING GRADING PENILAIAN AKHIR</strong>
          <div class="table-responsive mt-2">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th width="30%">GRADE</th>
                  <th width="40%">KETERANGAN</th>
                  <th width="15%">NILAI AWAL</th>
                  <th width="15%">NILAI AKHIR</th>
                </tr>
              </thead>
              <tbody>
                <form id="frm-penilaian">
                  <?php if (!empty($list_grading)): ?>
                    <?php foreach ($list_grading as $row): ?>
                      <tr>
                        <td><?= htmlspecialchars($row['grade']) ?></td>
                        <td><input type="text" name="keterangan[<?= $row['grade'] ?>]" class="form-control" value="<?= $row['keterangan'] ?>"></td>
                        <td><input type="number" min="0" max="100" name="nilai_min[<?= $row['grade'] ?>]" class="form-control" value="<?= $row['nilai_min'] ?>" readonly></td>
                        <td><input type="number" min="0" max="100" name="nilai_max[<?= $row['grade'] ?>]" class="form-control" value="<?= $row['nilai_max'] ?>" readonly></td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="4" class="text-center">Data tidak ditemukan.</td>
                    </tr>
                  <?php endif; ?>
                </form>
              </tbody>
            </table>
          </div>
          <button type="submit" class="btn btn-info btn-flat">Simpan Pengaturan</button>
          <input type="submit" name="submit" class="btn btn-warning btn-flat" value="Reset">
        </form>
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