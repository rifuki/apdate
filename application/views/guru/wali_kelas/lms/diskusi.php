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

        <!-- Card List Diskusi -->
        <div class="card mt-3">
          <div class="card-header"><strong>List Diskusi</strong></div>
          <div class="card-body" style="max-height:350px; overflow-y:auto;">
            <?php if(isset($diskusi) && is_array($diskusi) && count($diskusi) > 0): ?>
              <?php foreach($diskusi as $d): ?>
                <?php
                  $createdAt = strtotime($d['created_at']);
                  $now = time();
                  $isDeletable = ($now - $createdAt) <= 3600; // 3600 detik = 1 jam
                ?>
                <div class="card mb-2">
                  <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <span class="font-weight-bold"><?= htmlspecialchars($d['user_nama'] ?? '-') ?> - <?= htmlspecialchars($d['user_induk'] ?? '-') ?></span><br>
                        <div class="mt-2"><?= nl2br($d['deskripsi']) ?></div>
                        <div class="mt-4 small text-muted">
                          <i class="fa fa-clock"></i> <?= date('d M Y H:i', strtotime($d['updated_at'] ?? $d['created_at'])) ?>  
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="text-center text-muted">Belum ada diskusi.</div>
            <?php endif; ?>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
