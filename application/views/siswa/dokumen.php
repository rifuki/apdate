<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Data <?php echo $judul ?></h3>
      </div>
      <div class="card-body">
        <div class="table-responsive mt-5">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th width="50%">Judul</th>
                <th width="50%">Link Download</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($dokumen)): ?>
                <?php foreach ($dokumen as $row): ?>
                  <tr>
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td><a href="<?= base_url($row['file']) ?>" class="btn btn-info"><i class="fas fa-download"></i></a></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="2" class="text-center">Data tidak ditemukan.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Absensi Detail -->
<div class="modal fade" id="modalAbsensiDetail" tabindex="-1" role="dialog" aria-labelledby="modalAbsensiDetailLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAbsensiDetailLabel">Detail Absensi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="absensiDetailBody"></div>
    </div>
  </div>
</div>
<!-- Modal Tugas Detail -->
<div class="modal fade" id="modalTugasDetail" tabindex="-1" role="dialog" aria-labelledby="modalTugasDetailLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTugasDetailLabel">Detail Tugas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="tugasDetailBody"></div>
    </div>
  </div>
</div>


<script type="text/javascript">
  function cekDetail(siswa_id, jadwal_id) {
    var url = "<?= base_url('guru/laporan-penilaian/detail') ?>";
    $("#rowSiswa").removeClass('col-md-6');
    $("#rowSiswa").addClass('col-md-12');
    $("#rowDetail").empty();
    $.ajax({
        url : url,
        type : "POST",
        data: {
          "siswa_id": siswa_id,
          "jadwal_id": jadwal_id
        },
        dataType : "json",
        success:function(response)
        {
            if (response.status) {
              $("#rowSiswa").addClass('col-md-6');
              $("#rowSiswa").removeClass('col-md-12');
              $("#rowDetail").html(response.data.view);
              // let dataJadwal = response.data.list;

              // let html_jadwal = dataJadwal.map(function(item, i) {
              //   console.log(item, item.pertemuan_ke);
              //   let buttonTugas = item.tugas ? `<button class="btn btn-success btn-sm" onclick="lihatTugas('${item.tugas}')">Lihat Tugas</button>` : '<span class="text-muted">-</span>';
              //   let buttonAbsensi = item.absensi ? `<button class="btn btn-success btn-sm" onclick="lihatAbsensi('${item.absensi}')">Lihat Absensi</button>` : '<span class="text-muted">-</span>';
              //   return `
              //     <tr>
              //       <td>${item.pertemuan_ke}</td>
              //       <td>${buttonAbsensi}</td>
              //       <td>${buttonAbsensi}</td>
              //       <td>${buttonTugas}</td>
              //     </tr>
              //   `;
              // });
              // console.log(html_jadwal);
              // $("#table-detail").html(html_jadwal);
              return toastSuccess(response.message);
            }
            return toastError(response.message);
        }
    });
  }


  function lihatAbsensi(absensi) {
    const formData = new FormData();
    formData.append('id', absensi);
    formData.append('type', 'absensi');
    fetch("<?= base_url('guru/pertemuan/absensi-detail') ?>", {
        method: 'POST',
        body: formData
    })
    .then(response => response.json()) // jika kamu kirim JSON, sesuaikan
    .then(data => {
      if (data.status) {
        $('#absensiDetailBody').html(data.data.html);
        $('#modalAbsensiDetail').modal('show');
      }
      return toastSuccess(data.message);
    })
    .catch(error => {
        console.error('Gagal:', error);
        return toastError('Gagal mencari data!');
    });

  };
  // Handler tombol detail tugas
  function lihatTugas(tugas) {

    const formData = new FormData();
    formData.append('id', tugas);
    formData.append('type', 'tugas');
    fetch("<?= base_url('guru/pertemuan/absensi-detail') ?>", {
        method: 'POST',
        body: formData
    })
    .then(response => response.json()) // jika kamu kirim JSON, sesuaikan
    .then(data => {
      if (data.status) {
        $('#tugasDetailBody').html(data.data.html);
        $('#modalTugasDetail').modal('show');
      }
      return toastSuccess(data.message);
    })
    .catch(error => {
        console.error('Gagal:', error);
        return toastError('Gagal mencari data!');
    });
  };

  function simpanPenilaian() {
    let formData = new FormData();
    $(".nilai-tugas").each(function(i, item) {
      let pertemuan_id  = $(item).data('pertemuanid');
      let siswa_id  = $(item).data('siswaid');
      let nilai     = $(item).val();
      formData.append(`nilai[${siswa_id}_${pertemuan_id}]`, nilai);
    });

    $(".absensi-kehadiran").each(function(i, item) {
      let pertemuan_id  = $(item).data('pertemuanid');
      let siswa_id            = $(item).data('siswaid');
      let status_kehadiran    = $(item).val() ?? '';
      formData.append(`absensi[${siswa_id}_${pertemuan_id}]`, status_kehadiran);
    });

    fetch("<?= base_url('guru/laporan-penilaian/do_penilaian') ?>", {
        method: 'POST',
        body: formData
    })
    .then(response => response.json()) // jika kamu kirim JSON, sesuaikan
    .then(data => {
      // if (data.status) {
      //   $('#tugasDetailBody').html(data.data.html);
      //   $('#modalTugasDetail').modal('show');
      // }
      return toastSuccess(data.message);
    })
    .catch(error => {
        console.error('Gagal:', error);
        return toastError('Gagal mencari data!');
    });
  }
</script>