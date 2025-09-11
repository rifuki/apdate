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
          <a href="<?= base_url('guru/wali-kelas/jadwal-kelas/detail/'.$pertemuan['jadwal_kelas_id']) ?>" class="btn btn-warning">Kembali</a>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th>Link</th>
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
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="5" class="text-center">Belum ada data pranala luar.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
