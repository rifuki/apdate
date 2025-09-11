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
                    <button class="btn btn-block btn-outline-primary btnLihatAktifitas mt-auto" data-id="<?= (int) $m['id'] ?>">
                      Lihat Aktifitas
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

<!-- Modal -->
<div class="modal fade" id="modalPertemuan" tabindex="-1" role="dialog" aria-labelledby="modalPertemuanLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalPertemuanLabel">Daftar Pertemuan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="bodyModalPertemuan">
        
      </div>
    </div>
  </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btnLihatAktifitas').forEach(function(btn) {
      btn.addEventListener('click', function(e) {
        var jadwal_id = this.getAttribute('data-id');
        $.ajax({
        url : "<?php echo base_url('siswa/aktifitas-lms/detail') ?>",
        type : "POST",
        data: {
          "jadwal_id": jadwal_id
        },
        dataType : "json",
        success:function(response)
        {
            if (response.status) {
              $("#bodyModalPertemuan").html(response.data.view);
              $('#modalPertemuan').modal('show');
              return toastSuccess(response.message);
            }
            return toastError(response.message);
        }
        });
      });
    });

    
  });
</script>