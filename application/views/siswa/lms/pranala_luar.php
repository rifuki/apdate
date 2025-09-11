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
          <a href="<?= base_url('siswa/kelas/detail/'.$pertemuan['jadwal_kelas_id']) ?>" class="btn btn-warning">Kembali</a>
        </div>
        <hr>
        <?php if(isset($pranala_luar) && is_array($pranala_luar) && count($pranala_luar) > 0): ?>
          <?php foreach($pranala_luar as $i => $row): ?>
            <div class="card mb-2">
              <div class="card-body p-2">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <div class="mt-2">
                      Judul : <?= htmlspecialchars($row['judul']) ?><br>
                      <strong>Link</strong> : <a href="<?= htmlspecialchars($row['link']) ?>" target="_blank"><?= htmlspecialchars($row['link']) ?></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="card mb-2">
              <div class="card-body p-2">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <span class="font-weight-bold">Tidak ada data<br>
                  </div>
                </div>
              </div>
            </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</div>