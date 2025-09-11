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
        <h3 class="card-title">Data <?php echo $subjudul ?></h3>
      </div>
      <div class="card-body">
        <form id="frm-filter" action="<?= $own_link ?>" method="post">
           <div class="form-group row">
            <label for="periode_data" class="col-lg-2 col-sm-12 col-form-label">Periode Aktif</label>
            <div class="col-lg-6 col-sm-12">
              <input type="text" value="<?= $active_periode['tahun_ajaran'].' - Semester '.$active_periode['semester'] ?>" class="form-control" readonly />
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
        <?php $is_close = 0; ?>
        <div class="row">
          <div class="col-md-6">
            <strong>FORM NILAI AKHIR</strong>
            <div class="table-responsive mt-2">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th width="25%">NISN</th>
                    <th width="30%">Nama Siswa</th>
                    <th width="15%">Total <br/> Nilai</th>
                    <th width="15%">Grade</th>
                    <th width="15%">Nilai Akhir</th>
                  </tr>
                </thead>
                <tbody>
                  <form id="frm-penilaian">
                    <?php if (!empty($list_rapor)): ?>
                      <?php foreach ($list_rapor as $row): ?>
                        <?php $is_close = $row['is_close'] == 1 ? 1 : $is_close; ?>
                        <tr>
                          <td><?= htmlspecialchars($row['nisn']) ?></td>
                          <td><?= htmlspecialchars($row['nama']) ?></td>
                          <td><?= htmlspecialchars($row['total_nilai']) ?></td>
                          <td><?= htmlspecialchars($row['grade']) ?></td>
                          <td>
                            <input type="number" name="nilai_akhir[<?= $row['id'] ?>]" class="form-control" value="<?= $row['nilai_akhir'] ?>" min="0" max="100" <?= $row['is_close'] ? 'disabled' : '' ?>>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="5" class="text-center">Data tidak ditemukan.</td>
                      </tr>
                    <?php endif; ?>
                  </form>
                </tbody>
              </table>
            </div>
            <div class="mt-2">
              <?php if ($is_close == 0 && !empty($list_rapor)): ?>
                <button type="button" class="btn btn-danger btn-flat" onclick="simpanPenilaianAkhir()">Simpan Akhir</button>
                <button type="button" class="btn btn-success btn-flat float-right" onclick="simpanPenilaian()">Simpan Sementara</button>
              <?php endif; ?>
            </div>
          </div>
          <div class="col-md-6">
            <strong>GRADING PENILAIAN AKHIR</strong>
            <div class="table-responsive mt-2">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th width="30%">GRADE</th>
                    <th width="40%">KETERANGAN</th>
                    <th width="15%">NILAI AWAL</th>
                    <th width="15%">NILAI AKHIR</th>
                  </tr>
                </thead>
                <tbody>
                  <form id="frm-penilaian">
                    <?php if (!empty($list_grading)): ?>
                      <?php foreach ($list_grading as $row): ?>
                        <tr>
                          <td><?= htmlspecialchars($row['grade']) ?></td>
                          <td><?= htmlspecialchars($row['keterangan']) ?></td>
                          <td><?= htmlspecialchars($row['nilai_min']) ?></td>
                          <td><?= htmlspecialchars($row['nilai_max']) ?></td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="4" class="text-center">Data tidak ditemukan.</td>
                      </tr>
                    <?php endif; ?>
                  </form>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="jadwalKelas" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="jadwalKelasLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="jadwalKelasLabel">Jadwal Kelas <span id="jadwaKelas_namakelas"></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Mata Pelajaran</th>
              <th>Guru</th>
              <th>Hari</th>
              <th>Jumlah Pertemuan</th>
            </tr>
          </thead>
          <tbody id="listMapel"></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  function simpanPenilaian() {
    var form = document.getElementById('frm-penilaian');
    var formData = new FormData(form);
    formData.append('type', 'draft');
    formData.append('jadwal_id', '<?= $filter['jadwal_id'] ?>');
    formData.append('semester_id', '<?= $active_periode['id'] ?>');

    fetch("<?= base_url('dashboard/generate/closing-tahun-ajaran/do_penilaian') ?>", {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
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
      return toastError('Gagal menyimpan penilaian!');
    });
  }

  function simpanPenilaianAkhir() {
    var form = document.getElementById('frm-penilaian');
    var formData = new FormData(form);
    formData.append('type', 'final');
    formData.append('jadwal_id', '<?= $filter['jadwal_id'] ?>');
    formData.append('semester_id', '<?= $active_periode['id'] ?>');

    fetch("<?= base_url('dashboard/generate/closing-tahun-ajaran/do_penilaian') ?>", {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
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
      return toastError('Gagal menyimpan penilaian!');
    });
  }
</script>