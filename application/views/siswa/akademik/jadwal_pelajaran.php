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
        
        <form id="frm-filter" action="<?= base_url('siswa/akademik/jadwal-pelajaran/pdf') ?>" method="POST" target="_blank">
          <div class="form-group row">
            <label for="periode_data" class="col-lg-2 col-sm-12 col-form-label">Periode Aktif</label>
            <div class="col-lg-6 col-sm-12">
              <input type="text" value="<?= $active_periode['tahun_ajaran'].' - '.semesterText($active_periode['semester']) ?>" class="form-control" readonly />
            </div>
          </div>
          <div class="mb-2">
            <button type="submit" class="btn btn-info btn-flat">Cetak</button>
          </div>
        </form>
        <br>
        <div class="row">
          <div class="col-md-12">
            <div class="table-responsive mt-5">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th width="15%">Nomor</th>
                    <th width="35%">Mata Pelajaran</th>
                    <th width="35%">Guru Pengajaran</th>
                    <th width="15%">Total Pertemuan</th>
                  </tr>
                </thead>
                <tbody>
                  <form id="frm-penilaian">
                    <?php if (!empty($list_mapel)): ?>
                      <?php foreach ($list_mapel as $i => $row): ?>
                        <tr>
                          <td><?= $i + 1 ?></td>
                          <td><?= $row['mapel_code'].' - '.$row['mapel_name'] ?></td>
                          <td><?= $row['guru_code'].' - '.$row['guru_name'] ?></td>
                          <td><?= ($row['jumlah_pertemuan'] - 2) ?></td>
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