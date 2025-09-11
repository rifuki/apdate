 <style>
        /* Sederhana styling untuk kejelasan */
        .column { flex: 1; padding: 15px; border: 1px solid #ccc; margin: 5px; background-color: #f9f9f9; }
        .list-siswa { list-style-type: none; padding: 0; min-height: 50px; background-color: #fff; border: 1px dashed #ddd; }
        .list-siswa li { padding: 10px; margin: 5px; background-color: #e9efff; border: 1px solid #b3c7ff; cursor: grab; }
    </style>
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Data <?php echo $judul ?></h3>
      </div>
      <div class="card-body">
        <form id="frm-filter" action="<?= base_url('guru/laporan-absensi') ?>" method="POST">
          <div class="form-group row">
            <label for="periode_data" class="col-lg-2 col-sm-12 col-form-label">Periode Aktif</label>
            <div class="col-lg-6 col-sm-12">
              <input type="text" value="<?= $active_periode['tahun_ajaran'].' - '.semesterText($active_periode['semester']) ?>" class="form-control" readonly />
            </div>
          </div>
          <div class="form-group row">
            <label for="jadwal_id" class="col-lg-2 col-sm-12 col-form-label">Kelas</label>
            <div class="col-lg-6 col-sm-12">
              <select class="form-control select2" style="width: 100%;" name="jadwal_id" required>
                <option value="">- Pilih Kelas -</option>
                <?php foreach ($list_kelas as $field): ?>
                  <option <?= $filter['jadwal_id'] == $field['id'] ? 'selected' : '' ?> value="<?= $field['id'] ?>"><?= $field['kelas'].' - '.$field['nama_mapel'] ?></option>
                <?php endforeach ?>
              </select>
            </div>
          </div>
          <div class="mb-2">
            <button type="submit" class="btn btn-warning btn-flat">Filter</button>
          </div>
        </form>
        <br>
        <?php $is_final = 0; ?>
        <div class="row">
          <div id="rowSiswa" class="col-md-12">
            <div class="table-responsive mt-5">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th width="25%">NISN</th>
                    <th width="45%">Nama Siswa</th>
                    <th width="10%">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <form id="frm-penilaian">
                    <?php if (!empty($list_penilaian)): ?>
                      <?php foreach ($list_penilaian as $row): ?>
                        <?php $is_final = $row['is_final'] == 1 ? 1 : $is_final; ?>
                        <tr>
                          <td><?= htmlspecialchars($row['nisn']) ?></td>
                          <td><?= htmlspecialchars($row['nama']) ?></td>
                          <td>
                            <button type="button" class="btn btn-info btn-sm" onclick="cekDetail('<?= $row['siswa_id'] ?>', <?= $filter['jadwal_id'] ?>)">
                              Detail
                            </button>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="3" class="text-center">Data tidak ditemukan.</td>
                      </tr>
                    <?php endif; ?>
                  </form>
                </tbody>
              </table>
            </div>
            <div class="mt-2">
            </div>
          </div>
          <div id="rowDetail" class="col-md-6">
            
          </div>
        </div>
      </div>
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
          "jadwal_id": jadwal_id,
          'type': 'absensi'
        },
        dataType : "json",
        success:function(response)
        {
            if (response.status) {
              $("#rowSiswa").addClass('col-md-6');
              $("#rowSiswa").removeClass('col-md-12');
              $("#rowDetail").html(response.data.view);
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
</script>