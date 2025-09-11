<div id="accordionContoh" class="accordion">
  <?php foreach($list_pertemuan as $pertemuan): ?>
    <?php 
      $i = $pertemuan['pertemuan_ke'];
      $slug = toSlug($jadwal_kelas['id'], $i);
      $status = "belum";
      if ($pertemuan['status'] == 1 && (!empty($pertemuan['close_at']) && strtotime(date('Y-m-d H:i:s')) > strtotime($pertemuan['close_at']))) {
        $status = "tutup";
      } elseif ($pertemuan['status'] == 1) {
        $status = "aktif";
      }
    ?>
    <div class="card">
      <div class="card-header" id="heading<?= $i ?>">
        <h5 class="mb-0">
          <button class="btn btn-link" data-toggle="collapse" data-target="#collapse<?= $i ?>" aria-expanded="false" aria-controls="collapse<?= $i ?>">
            <strong class="
              <?php if($status=='aktif'): ?>text-success<?php elseif($status=='tutup'): ?>text-dark<?php endif; ?>
            ">
              PERTEMUAN #<?= $i ?>
            </strong>
            <?php if($status == 'aktif'): ?>
              <span class="badge badge-success ml-2">AKTIF</span>
            <?php elseif($status == 'tutup'): ?>
              <span class="badge badge-dark ml-2">TUTUP</span>
            <?php endif; ?>
          </button>
        </h5>
      </div>
      <div id="collapse<?= $i ?>" class="collapse" aria-labelledby="heading<?= $i ?>" data-parent="#accordionContoh">
        <div class="card-body">
          <div class="mb-2">
            <?php if($status == 'belum' || $status == 'tutup'): ?>
              <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalAktifkanPertemuan" data-id="<?= $slug ?>" data-pertemuan="<?= $i ?>">Aktifkan Pertemuan</button>
            <?php elseif($status == 'aktif'): ?>
              <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modalTutupPertemuan" data-id="<?= $slug ?>" data-pertemuan="<?= $i ?>">Tutup Pertemuan</button>
            <?php endif; ?>
          </div>
          <div class="row">
            <div class="col-md-3 mb-2">
              <a href="<?= base_url('guru/pertemuan/modul/'.$slug) ?>" class="text-white" style="text-decoration:none;">
                <div class="card text-white bg-warning h-100">
                  <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="fa fa-book fa-2x mb-2"></i>
                    <span>Modul</span>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-md-3 mb-2">
              <a href="<?= base_url('guru/pertemuan/pranala-luar/'.$slug) ?>" class="text-white" style="text-decoration:none;">
                <div class="card text-white bg-success h-100">
                  <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="fa fa-link fa-2x mb-2"></i>
                    <span>Pranala Luar</span>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-md-3 mb-2">
              <a href="<?= base_url('guru/pertemuan/diskusi/'.$slug) ?>" class="text-white" style="text-decoration:none;">
                <div class="card text-white bg-danger h-100">
                  <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="fa fa-comments fa-2x mb-2"></i>
                    <span>Diskusi</span>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-md-3 mb-2">
              <a href="<?= base_url('guru/pertemuan/absensi/'.$slug) ?>" class="text-white" style="text-decoration:none;">
                <div class="card text-white bg-primary h-100">
                  <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="fa fa-user-check fa-2x mb-2"></i>
                    <span>Absensi dan Tugas</span>
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach ?>
</div>

<!-- Modal Aktifkan Pertemuan -->
<div class="modal fade" id="modalAktifkanPertemuan" tabindex="-1" role="dialog" aria-labelledby="modalAktifkanPertemuanLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="POST" action="<?= base_url('guru/pertemuan/aktifkan') ?>">
        <div class="modal-header">
          <h5 class="modal-title" id="modalAktifkanPertemuanLabel">Aktifkan Pertemuan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="slug" id="modal_slug">
          <input type="hidden" name="pertemuan_ke" id="modal_pertemuan_ke">
          <div class="form-group">
            <label for="tanggal_tutup">Tanggal Tutup</label>
            <input type="date" class="form-control" name="tanggal_tutup" id="tanggal_tutup" required>
          </div>
          <div class="form-group">
            <label for="jam_tutup">Jam Tutup</label>
            <input type="time" class="form-control" name="jam_tutup" id="jam_tutup" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">Aktifkan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Aktifkan Pertemuan -->
<div class="modal fade" id="modalTutupPertemuan" tabindex="-1" role="dialog" aria-labelledby="modalTutupPertemuanLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="POST" action="<?= base_url('guru/pertemuan/tutup') ?>">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTutupPertemuanLabel">Tutup Pertemuan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="slug" id="modal_slug">
          <input type="hidden" name="pertemuan_ke" id="modal_pertemuan_ke">
          <div class="form-group">
            <strong>ANDA YAKIN INGIN MENUTUP PERTEMUAN INI?</strong>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
  // Isi data modal saat tombol aktifkan diklik
  document.addEventListener('DOMContentLoaded', function() {
    $('#modalAktifkanPertemuan').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget);
      var slug = button.data('id');
      var pertemuan = button.data('pertemuan');
      $(this).find('#modal_slug').val(slug);
      $(this).find('#modal_pertemuan_ke').val(pertemuan);
    });

    $('#modalTutupPertemuan').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget);
      var slug = button.data('id');
      var pertemuan = button.data('pertemuan');
      $(this).find('#modal_slug').val(slug);
      $(this).find('#modal_pertemuan_ke').val(pertemuan);
    });
  });
</script>
</div>    