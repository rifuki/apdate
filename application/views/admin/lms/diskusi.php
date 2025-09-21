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
          <a href="<?= base_url('dashboard/lms/detail/'.$pertemuan['jadwal_kelas_id']) ?>" class="btn btn-warning">Kembali</a>
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
                        <?php if (!empty($d['deleted_by_admin'])): ?>
                          <div class="mt-2 text-danger"><em>This discussion has been deleted by admin.</em></div>
                        <?php else: ?>
                          <div class="mt-2"><?= nl2br($d['deskripsi']) ?></div>
                          <div class="mt-4 small text-muted">
                            <i class="fa fa-clock"></i> <?= date('d M Y H:i', strtotime($d['updated_at'] ?? $d['created_at'])) ?>  
                          </div>
                        <?php endif ?>
                      </div>
                      <div>
                        <?php if ($isDeletable): ?>
                          <button class="btn btn-sm btn-danger btnDeleteDiskusi" data-id="<?= $d['id'] ?>" data-toggle="modal" data-target="#modalDiskusiDelete">Delete</button>
                        <?php endif ?>
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


<!-- Modal Delete Diskusi -->
<div class="modal fade" id="modalDiskusiDelete" tabindex="-1" role="dialog" aria-labelledby="modalDiskusiDeleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="formDiskusi" method="POST" action="<?= base_url('dashboard/lms/pertemuan/diskusi-delete') ?>">
        <div class="modal-header">
          <h5 class="modal-title" id="modalDiskusiDeleteLabel">Delete Diskusi</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="diskusi_id_delete">
          <input type="hidden" name="pertemuan_id" value="<?= $pertemuan['id'] ?>">
          <h3>Anda yakin ingin menghapus diskusi ini?</h3>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Hapus</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.querySelectorAll('.btnDeleteDiskusi').forEach(function(btn) {
    btn.addEventListener('click', function() {
      document.getElementById('diskusi_id_delete').value = this.getAttribute('data-id');
    });
  });
</script>