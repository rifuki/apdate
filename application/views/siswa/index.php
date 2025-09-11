<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">List Mata Pelajaran</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <?php if(isset($list_mapel) && is_array($list_mapel) && count($list_mapel) > 0): ?>
            <?php foreach($list_mapel as $i => $m): ?>
              <?php if($i % 3 == 0 && $i > 0): ?></div><div class="row mt-3"><?php endif; ?>
              <div class="col-md-4 mb-4">
                <div class="card shadow h-100 border-0">
                  <div class="card-body d-flex flex-column justify-content-between">
                    <div class="align-items-center mb-3" style="height:10rem;">
                      <div class="bg-primary text-white d-flex align-items-center justify-content-center mr-3" style="width:100%;height:100%;font-size:1.5rem;border-radius:5%">
                        <i class="fa fa-book" style="font-size:80px;"></i>
                      </div>
                    </div>
                    <div>
                      <div class="font-weight-bold" style="font-size:1.1rem;"><?= htmlspecialchars($m['mapel_name']) ?></div>
                      <div class="text-muted" style="font-size:1.1rem;"><?= 'Guru : '.htmlspecialchars($m['guru_nama']) ?></div>
                    </div>
                    <hr style="border: 1px solid black; width:100%">
                    <div class="mb-3 d-flex flex-row justify-content-between">
                      <span class=""><i class="fa fa-users"></i> <?= (int) $m['jumlah_pertemuan'] ?> Pertemuan</span>
                      <span class=""><i class="fa fa-book"></i> <?= (int) $m['jumlah_modul'] ?> Modul</span>
                      <span class=""><i class="fa fa-comments"></i> <?= (int) $m['jumlah_diskusi'] ?> Diskusi</span>
                    </div>
                    <button class="btn btn-block btn-outline-primary btnMulaiPembelajaran mt-auto" data-jumlah="<?= (int)$m['jumlah_pertemuan'] ?>" data-mapel="<?= htmlspecialchars($m['mapel_name']) ?>" data-id="<?= (int) $m['id'] ?>">
                      Mulai Belajar
                    </button>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="col-12 text-center text-muted">Belum ada data mata pelajaran.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btnMulaiPembelajaran').forEach(function(btn) {
      btn.addEventListener('click', function(e) {
        var jumlah = parseInt(this.getAttribute('data-jumlah'));
        var mapel = this.getAttribute('data-mapel');
        if(jumlah === 0) {
          toastError('Mata pelajaran "' + mapel + '" belum aktif. Silakan hubungi guru.');
          e.preventDefault();
        } else {
          var id = this.getAttribute('data-id');
          window.location.href = '<?= base_url('siswa/kelas/detail/') ?>' + id;
        }
      });
    });
  });
</script>