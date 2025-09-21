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
          <div class="row">
            <div class="col-md-3 mb-2">
              <a href="<?= base_url('dashboard/lms/pertemuan/modul/'.$slug) ?>" class="text-white" style="text-decoration:none;">
                <div class="card text-white bg-warning h-100">
                  <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="fa fa-book fa-2x mb-2"></i>
                    <span>Modul</span>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-md-3 mb-2">
              <a href="<?= base_url('dashboard/lms/pertemuan/pranala-luar/'.$slug) ?>" class="text-white" style="text-decoration:none;">
                <div class="card text-white bg-success h-100">
                  <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="fa fa-link fa-2x mb-2"></i>
                    <span>Pranala Luar</span>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-md-3 mb-2">
              <a href="<?= base_url('dashboard/lms/pertemuan/diskusi/'.$slug) ?>" class="text-white" style="text-decoration:none;">
                <div class="card text-white bg-danger h-100">
                  <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="fa fa-comments fa-2x mb-2"></i>
                    <span>Diskusi</span>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-md-3 mb-2">
              <a href="<?= base_url('dashboard/lms/pertemuan/absensi/'.$slug) ?>" class="text-white" style="text-decoration:none;">
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