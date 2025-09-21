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
      <?php $readonly = in_array($active_periode['status_code'], ['2', '2.1']) ? "" : "readonly"; ?>
      <div class="card-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header"><strong>Daftar Absensi Siswa</strong></div>
              <div class="card-body p-2">
                <a href="<?= base_url('guru/jadwal-kelas/detail/'.$pertemuan['jadwal_kelas_id']) ?>" class="btn btn-warning">Kembali</a>
                <div class="table-responsive mt-2 mb-2">
                  <table class="table table-bordered table-sm">
                    <thead>
                      <tr>
                        <th width="5%">No</th>
                        <th width="25%">Nama</th>
                        <th width="15%">NISN</th>
                        <th width="15%">Absensi Kelas</th>
                        <th width="15%">Absensi Diskusi</th>
                        <th width="10%">Tugas</th>
                        <th width="15%">Nilai Tugas</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(isset($absensi) && is_array($absensi) && count($absensi) > 0): ?>
                        <?php foreach($absensi as $i => $a): ?>
                          <tr>
                            <td><?= $i+1 ?></td>
                            <td><?= htmlspecialchars($a['nama']) ?></td>
                            <td><?= htmlspecialchars($a['nisn']) ?></td>
                            <td>
                              <select data-siswaid="<?= $a['siswa_id'] ?>" class="form-control absensi-kehadiran" <?= $readonly ?>>
                                <option value="" disabled selected>Belum Absen</option>
                                <option value="hadir" <?= $a['absensi_kehadiran'] == 'hadir' ? 'selected' : '' ?>>Hadir</option>
                                <option value="tanpa_keterangan" <?= $a['absensi_kehadiran'] == 'tanpa_keterangan' ? 'selected' : '' ?>>Tanpa Keterangan</option>
                                <option value="sakit" <?= $a['absensi_kehadiran'] == 'sakit' ? 'selected' : '' ?>>Sakit</option>
                                <option value="izin" <?= $a['absensi_kehadiran'] == 'izin' ? 'selected' : '' ?>>Izin</option>
                              </select>
                            </td>
                            <td>
                              <?php if($a['absensi_diskusi'] >= 2 ): ?>
                                <button class="btn btn-info btn-sm">Hadir</button>
                              <?php else: ?>
                                <button class="btn btn-danger btn-sm">Tidak Hadir</button>
                              <?php endif; ?>
                            </td>
                            <td>
                              <?php if(!empty($a['tugas'])): ?>
                                <button class="btn btn-warning btn-sm btnTugasDetail" data-tugas="<?= htmlspecialchars($a['tugas']) ?>" data-nama="<?= htmlspecialchars($a['nama']) ?>">Lihat</button>
                              <?php else: ?>
                                <span class="text-muted">-</span>
                              <?php endif; ?>
                            </td>
                            <td>
                              <input type="number" min="0" max="100" class="form-control nilai-tugas" data-siswaid="<?= $a['siswa_id'] ?>" placeholder="0" value="<?= $a['nilai_tugas'] ?>" <?= $readonly ?>>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr><td colspan="7" class="text-center">Belum ada data absensi.</td></tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
                <?php if (empty($readonly)): ?>
                <button type="button" class="btn btn-success mb-2" id="btnSimpanData">Simpan Data</button>
                <?php endif ?>
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
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function() {
      // Handler tombol detail absensi
      $(document).on('click', '.btnAbsensiDetail', function() {
        var nama = $(this).data('nama');
        var absensi = $(this).data('absensi');

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

      });
      // Handler tombol detail tugas
      $(document).on('click', '.btnTugasDetail', function() {
        var nama = $(this).data('nama');
        var tugas = $(this).data('tugas');

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
      });

      $(document).on('click', '#btnSimpanData', function() {
        let formData = new FormData();
        formData.append('pertemuan_id', `<?=  $pertemuan['id'] ?>`);
        $(".nilai-tugas").each(function(i, item) {
          let siswa_id  = $(item).data('siswaid');
          let nilai     = $(item).val();
          formData.append(`nilai[${siswa_id}]`, nilai);
        });

        $(".absensi-kehadiran").each(function(i, item) {
          let siswa_id            = $(item).data('siswaid');
          let status_kehadiran    = $(item).val() ?? '';
          formData.append(`absensi[${siswa_id}]`, status_kehadiran);
        });

        fetch("<?= base_url('guru/pertemuan/absensi-update') ?>", {
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
      });
  });
</script>