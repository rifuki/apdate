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
        <form id="frm-filter" action="<?= base_url('guru/wali-kelas/e-grading') ?>" method="POST">
          <div class="form-group row">
            <label for="periode_data" class="col-lg-2 col-sm-12 col-form-label">Periode Aktif</label>
            <div class="col-lg-6 col-sm-12">
              <input type="text" value="<?= $active_periode['tahun_ajaran'].' - '.semesterText($active_periode['semester']) ?>" class="form-control" readonly />
            </div>
          </div>
          <div class="form-group row">
            <label for="kelas_id" class="col-lg-2 col-sm-12 col-form-label">Kelas</label>
            <div class="col-lg-6 col-sm-12">
              <select class="form-control select2" style="width: 100%;" name="kelas_id" required>
                <option value="">- Pilih Kelas -</option>
                <?php foreach ($wali_kelas as $field): ?>
                  <option <?= $filter['kelas_id'] == $field['id'] ? 'selected' : '' ?> value="<?= $field['id'] ?>"><?= $field['kelas'] ?></option>
                <?php endforeach ?>
              </select>
            </div>
          </div>
          <div class="mb-2">
            <button type="submit" class="btn btn-warning btn-flat">Filter</button>
          </div>
        </form>
        <br>
        <div class="row">
          <div class="col-md-12">
            <form id="frm-penilaian">
            <div class="table-responsive mt-5">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th width="15%">NISN</th>
                    <th width="25%">Nama Siswa</th>
                    <th width="15%">Nilai Akhir</th>
                    <th width="15%">Grade</th>
                    <th width="30%">Keterangan</th>
                  </tr>
                </thead>
                <tbody>
                    <?php if (!empty($list_penilaian)): ?>
                      <?php foreach ($list_penilaian as $row): ?>
                        <tr>
                          <td><?= htmlspecialchars($row['nisn']) ?></td>
                          <td><?= htmlspecialchars($row['nama']) ?></td>
                          <td><input type="number" min="0" max="100" name="nilai_grade[]" data-siswaid="<?= $row['siswa_id'] ?>" class="form-control nilai_akhir" value="<?= $row['nilai_akhir'] ?>"></td>
                          <td><?= $row['grade'] ?></td>
                          <td><?= $row['keterangan'] ?></td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="5" class="text-center">Data tidak ditemukan.</td>
                      </tr>
                    <?php endif; ?>
                </tbody>
              </table>
            </div>
            <button type="submit" class="btn btn-info btn-flat">Simpan Pengaturan</button>
            </form>
            <br>
            <strong style="font-size: 14px">*Grade akan otomatis berubah setelah menginput nilai dan submit formnya</strong>
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


  $(document).on('submit', '#frm-penilaian', function(e) {
    e.preventDefault();
    const jadwal_kelas_id = '<?= $filter['jadwal_id'] ?>';
    let formData = new FormData();
    $(".nilai_akhir").each(function(i, item) {
      let siswa_id  = $(item).data('siswaid');
      let nilai     = $(item).val();
      formData.append(`nilai[${siswa_id}]`, nilai);
    });

    formData.append('jadwal_kelas_id', jadwal_kelas_id);
    fetch("<?= base_url('guru/e-grading/submit') ?>", {
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
        return toastError('Gagal update data!');
    });
  });
</script>