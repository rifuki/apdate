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
          <?php if(!$is_close): ?>
          <div class="float-right">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalDiskusi" id="btnAddDiskusi">Tambah Diskusi</button>
          </div>
          <?php endif ?>
        </div>

        <!-- Card List Diskusi -->
        <!-- <div class="card mt-3">
          <div class="card-body" style="max-height:350px; overflow-y:auto;">
            
          </div>
        </div> -->
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

                      <?php if($user['user']['id'] == $d['user_id'] && !$is_close): ?>
                      <div>
                        <button class="btn btn-sm btn-warning btnEditDiskusi" data-id="<?= $d['id'] ?>" data-deskripsi="<?= htmlspecialchars($d['deskripsi']) ?>" data-toggle="modal" data-target="#modalDiskusi">Edit</button>
                        <?php if ($isDeletable): ?>
                            <button class="btn btn-sm btn-danger btnDeleteDiskusi" data-id="<?= $d['id'] ?>" data-toggle="modal" data-target="#modalDiskusiDelete">Delete</button>
                        <?php endif ?>
                      </div>
                      <?php endif ?>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="text-center text-muted">Belum ada diskusi.</div>
            <?php endif; ?>

        <!-- Modal Tambah/Edit Diskusi -->
        <div class="modal fade" id="modalDiskusi" tabindex="-1" role="dialog" aria-labelledby="modalDiskusiLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <form id="formDiskusi" method="POST" action="<?= base_url('siswa/pertemuan/diskusi-save') ?>">
                <div class="modal-header">
                  <h5 class="modal-title" id="modalDiskusiLabel">Tambah/Edit Diskusi</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <input type="hidden" name="id" id="diskusi_id">
                  <input type="hidden" name="pertemuan_id" value="<?= $pertemuan['id'] ?>">
                  <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea class="form-control textarea" name="deskripsi" id="diskusi_deskripsi" rows="5" required></textarea>
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

        <!-- Modal Delete Diskusi -->
        <div class="modal fade" id="modalDiskusiDelete" tabindex="-1" role="dialog" aria-labelledby="modalDiskusiDeleteLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <form id="formDiskusi" method="POST" action="<?= base_url('siswa/pertemuan/diskusi-delete') ?>">
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


      </div>
    </div>
  </div>
</div>

<script>
  // Modal form handler untuk diskusi
  document.addEventListener('DOMContentLoaded', function() {
    // Reset modal saat tambah diskusi
    document.getElementById('btnAddDiskusi').addEventListener('click', function() {
      document.getElementById('modalDiskusiLabel').innerText = 'Tambah Diskusi';
      document.getElementById('diskusi_id').value = '';
      $('.textarea').summernote('code', '');
      // document.getElementById('diskusi_deskripsi').value = '';
    });
    // Isi modal saat edit diskusi
    document.querySelectorAll('.btnEditDiskusi').forEach(function(btn) {
      btn.addEventListener('click', function() {
        document.getElementById('modalDiskusiLabel').innerText = 'Edit Diskusi';
        document.getElementById('diskusi_id').value = this.getAttribute('data-id');
        $('.textarea').summernote('code', this.getAttribute('data-deskripsi'));
        // document.getElementById('diskusi_deskripsi').value = this.getAttribute('data-deskripsi');
      });
    });

    document.querySelectorAll('.btnDeleteDiskusi').forEach(function(btn) {
      btn.addEventListener('click', function() {
        document.getElementById('diskusi_id_delete').value = this.getAttribute('data-id');
      });
    });
  });
</script>