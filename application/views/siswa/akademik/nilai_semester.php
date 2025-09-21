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
         <form id="frm-filter" action="<?= base_url('siswa/akademik/nilai-semester') ?>" method="POST">
          <div class="form-group row">
            <label for="periode_data" class="col-lg-2 col-sm-12 col-form-label">Semester</label>
            <div class="col-lg-6 col-sm-12">
              <select name="periode_semester" class="form-control">
                <option value="" selected disabled>- Pilih Semester -</option>
                <?php foreach ($list_semester as $row): ?>
                  <option value="<?= $row['id'].'_1' ?>" <?= ($filter['periode_semester'] == $row['id'].'_1') ? 'selected' : '' ?>>
                    <?= $row['tahun_ajaran'] ?> Semester Ganjil
                  </option>
                  <option value="<?= $row['id'].'_2' ?>" <?= ($filter['periode_semester'] == $row['id'].'_2') ? 'selected' : '' ?>>
                    <?= $row['tahun_ajaran'] ?> Semester Genap
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="mb-2">
            <button type="submit" class="btn btn-info btn-flat">Filter</button>
            <button type="submit" class="btn btn-danger btn-flat" formaction="<?= base_url('siswa/akademik/nilai-semester/pdf') ?>" formtarget="_blank">Cetak</button>
          </div>
        </form>
        <br>
        <div class="row">
          <div class="col-md-12">
            <div class="table-responsive mt-5">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th width="10%">Nomor</th>
                    <th width="20%">Kode Mata Pelajaran</th>
                    <th width="20%">Nama Mata Pelajaran</th>
                    <th width="15%">Jumlah Kehadiran</th>
                    <th width="15%">Tugas</th>
                    <th width="10%">UTS</th>
                    <th width="10%">UAS</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($list_nilai)): ?>
                    <?php foreach ($list_nilai as $i => $row): ?>
                      <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= $row['mapel_code'] ?></td>
                        <td><?= $row['mapel_name'] ?></td>
                        <td><?= $row['absensi_kehadiran'] ?></td>
                        <td><?= $row['nilai_tugas'] ?></td>
                        <td><?= $row['nilai_uts'] ?></td>
                        <td><?= $row['nilai_uas'] ?></td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="7" class="text-center">Data tidak ditemukan.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>