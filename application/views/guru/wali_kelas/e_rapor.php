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
        <form id="frm-filter" action="<?= base_url('guru/wali-kelas/e-rapor') ?>" method="POST">
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
                    <th>Nama Siswa</th>
                    <th width="15%">Detail</th>
                    <th width="15%">E-Rapor</th>
                  </tr>
                </thead>
                <tbody>
                    <?php if (!empty($list_siswa)): ?>
                      <?php foreach ($list_siswa as $row): ?>
                        <tr>
                          <td><?= htmlspecialchars($row['nisn']) ?></td>
                          <td><?= htmlspecialchars($row['nama']) ?></td>
                          <td><button type="button" data-siswaid="<?= $row['siswa_id'] ?>" data-kelasid="<?= $row['kelas_id'] ?>" class="btn btn-info btnDetail">Lihat Detail Rapor</button></td>
                          <td><a href="<?= base_url('guru/wali-kelas/e-rapor/detail_page') ?>" target="_blank" class="btn btn-success">E-Rapor</a></td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="4" class="text-center">Data tidak ditemukan.</td>
                      </tr>
                    <?php endif; ?>
                </tbody>
              </table>
            </div>
            <!-- <button type="submit" class="btn btn-info btn-flat">Simpan Pengaturan</button> -->
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal E-Rapor Detail -->
<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalDetailLabel">Detail E Rapor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="eraporDetailBody"></div>
    </div>
  </div>
</div>

<script type="text/javascript">

  $(document).on('click', '.btnDetail', function(e) {
    const kelasid = $(this).data('kelasid');
    const siswaid = $(this).data('siswaid');
    let formData = new FormData();
    formData.append('siswa_id', siswaid);
    formData.append('kelas_id', kelasid);
    fetch("<?= base_url('guru/wali-kelas/e-rapor/detail') ?>", {
        method: 'POST',
        body: formData
    })
    .then(response => response.json()) // jika kamu kirim JSON, sesuaikan
    .then(data => {
      if (data.status) {
        $('#eraporDetailBody').html(data.data.view);
        $('#modalDetail').modal('show');
      }
      return toastSuccess(data.message);
    })
    .catch(error => {
        console.error('Gagal:', error);
        return toastError('Gagal update data!');
    });
  });
</script>