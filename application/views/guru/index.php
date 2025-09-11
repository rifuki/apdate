  <style>
    .accordion .card {
      border-radius: 10px;
      margin-bottom: 15px;
      border: 1px solid #e3e6f0;
      box-shadow: 0 2px 8px rgba(44,62,80,0.05);
      overflow: hidden;
    }
    .accordion-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .accordion .btn-link {
      color: #34495e;
      font-size: 1.1rem;
      text-align: left;
      padding: 18px 20px;
      background: #f8f9fc;
      border: none;
      width: 100%;
      transition: background 0.2s;
    }
    .accordion .btn-link:hover, .accordion .btn-link:focus {
      background: #e9ecef;
      text-decoration: none;
    }
    .card-header {
      background: #f8f9fc;
      border-bottom: 1px solid #e3e6f0;
      padding: 0;
    }
    .card-body {
      background: #fff;
      padding: 20px 25px;
      font-size: 1rem;
      color: #495057;
    }
    .card-body strong {
      font-weight: 600;
    }
    .btn-info.goto {
      margin-right: 10px;
    }
    .modal-content {
      border-radius: 12px;
      box-shadow: 0 4px 24px rgba(44,62,80,0.12);
    }
    .modal-header {
      background: #f8f9fc;
      border-bottom: 1px solid #e3e6f0;
      border-radius: 12px 12px 0 0;
    }
    .modal-title {
      color: #2d3436;
      font-weight: 600;
    }
    .form-group label {
      font-weight: 500;
      color: #636e72;
    }
    .form-control {
      border-radius: 6px;
      border: 1px solid #dfe6e9;
      font-size: 1rem;
    }
    .btn-primary, .btn-info, .btn-danger, .btn-secondary {
      border-radius: 6px;
      font-weight: 500;
      padding: 8px 18px;
      font-size: 1rem;
    }
    .text-success, .text-warning, .text-danger {
      font-size: 0.98rem;
      font-weight: 600;
      letter-spacing: 0.5px;
    }
    @media (max-width: 600px) {
      .card-body, .modal-body {
        padding: 15px 10px;
        font-size: 0.97rem;
      }
      .accordion .btn-link {
        padding: 14px 10px;
        font-size: 1rem;
      }
    }
  </style>
  <div class="mb-4">
    <h2 class="font-weight-bold" style="color:#2d3436;">Daftar Kelas Anda</h2>
    <p class="text-muted" style="margin-bottom:0;">Kelola jadwal dan pertemuan kelas dengan mudah.</p>
  </div>

<?php if ($active_periode['status'] < 2): ?>
  <div class="alert alert-danger" role="alert">
   Periode ini belum membuka akses LMS. Silakan hubungi administrator untuk informasi lebih lanjut.
  </div>
<?php endif ?>
<div id="accordionContoh" class="accordion">
  <?php foreach($list_kelas as $i => $item): ?>
  <div class="card">
    <div class="card-header" id="heading<?= $i ?>">
      <h5 class="mb-0 accordion-header">
        <button class="btn btn-link btn-block" data-toggle="collapse" data-target="#collapse<?= $i ?>" aria-expanded="false" aria-controls="collapse<?= $i ?>">
          <strong class="float-left">Kelas <?= $item['kelas'] ?></strong>
          <?php if ($item['jumlah_pertemuan'] > 0 && $item['status'] == 0): ?>
          <strong class="text-success float-right">ACTIVE</strong>
          <?php elseif ($item['status'] == 1): ?>
          <strong class="text-warning float-right">CLOSED</strong>
          <?php else: ?>
          <strong class="text-danger float-right">INACTIVE</strong>
          <?php endif ?>
        </button>
      </h5>
    </div>
    <div id="collapse<?= $i ?>" class="collapse" aria-labelledby="heading<?= $i ?>" data-parent="#accordionContoh">
      <div class="card-body">
        Kelas : <?= $item['kelas'] ?>
        <br>
        Mata Pelajaran : <?= $item['nama_mapel'] ?>
        <br>
        Jumlah Pertemuan : <?= $item['jumlah_pertemuan'] ?>
        <br>
        <?php if ($item['jumlah_pertemuan'] > 0): ?>
          <button type="button" class="btn btn-info goto" data-id="<?= $item['id'] ?>">Lihat LMS</button>
        <?php elseif ($active_periode['status'] == 2): ?>
          <button type="button" data-id="<?= $item['id'] ?>" data-kelas="<?= $item['kelas'] ?>" class="ml-2 btn btn-danger edit-data">Atur Jumlah Pertemuan</button>
        <?php endif ?>
      </div>
    </div>
  </div>
  <?php endforeach ?>
</div>
<!-- Modal Bootstrap 4 -->
<div class="modal fade" id="modalGuru" tabindex="-1" role="dialog" aria-labelledby="modalGuruLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalGuruLabel">Atur Jumlah Pertemuan Kelas <span id="form_jadwal_kelas_nama"></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formJadwalKelas">
        <input type="hidden" name="jadwal_kelas_id" id="form_jadwal_kelas" required>
        <div class="modal-body">
          <div class="form-group row">
            <label for="jumlah_pertemuan" class="col-sm-12 col-md-4 col-form-label">Jumlah Pertemuan <span class="text-danger">*</span></label>
            <div class="col-sm-12 col-md-8">
              <input type="text" onkeypress="javascript:return isNumber(event)" class="form-control" id="jumlah_pertemuan" name="jumlah_pertemuan" required>
            </div>
          </div>
          <div class="form-group row">
            <label for="posisi_uts" class="col-sm-12 col-md-4 col-form-label">Penempatan Posisi UTS <span class="text-danger">*</span></label>
            <div class="col-sm-12 col-md-8">
              <input type="text" onkeypress="javascript:return isNumber(event)" class="form-control" id="posisi_uts" name="posisi_uts" required>
            </div>
          </div>
          <br>
          <strong>* SETELAH DATA TERSIMPAN TIDAK BISA DILAKUKAN PERUBAHAN KEMBALI</strong>
          <br>
          <strong>* UAS OTOMATIS TERBUAT SETELAH PERTEMUAN TERAKHIR</strong>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary" id="btnSimpan">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
  $(document).on('click', '.edit-data', function(){
    var dataId = $(this).data('id');
    var dataKelas = $(this).data('kelas');
    
    // Kirim dataId ke modal, contoh: tampilkan di modal-body
    $('#modalGuru #form_jadwal_kelas').val(dataId);
    $('#modalGuru #form_jadwal_kelas_nama').text(dataKelas);
    $('#modalGuru').modal('show');
  });

  $(document).on('click', '.goto', function(){
    var dataId = $(this).data('id');
    var url = "<?= base_url('guru/jadwal-kelas/detail/') ?>" + dataId;
    window.location.href = url;
  });

  $('#formJadwalKelas').on('submit', function(e) {
    e.preventDefault();
    // Validasi
    let jumlah_pertemuan  = Number($("#jumlah_pertemuan").val().trim());
    let posisi_uts        = Number($("#posisi_uts").val().trim());
    if (posisi_uts === 1 || posisi_uts >= jumlah_pertemuan) {
        toastError('UTS tidak boleh diatur sebagai pertemuan pertama ataupun pertemuan terakhir!');
        return;
    }
    
    $("#btnSimpan").prop('disabled', true).text('Mengirim...');

    const formData = new FormData(this);

    fetch("<?= base_url('guru/jadwal-kelas/update') ?>", {
        method: 'POST',
        body: formData
    })
    .then(response => response.json()) // jika kamu kirim JSON, sesuaikan
    .then(data => {
      if (data.status) {
        setTimeout(() => {
          window.location.reload();
        }, 1500);

        return toastSuccess(data.message);
      }
      return toastError(data.message);
    })
    .catch(error => {
        console.error('Gagal:', error);
        $("#btnSimpan").prop('disabled', true).text('Simpan Perubahan');
        return toastError('Gagal mengirim data!');
    });
  });
});
</script>

