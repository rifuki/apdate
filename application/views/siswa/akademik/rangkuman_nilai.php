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
         <form id="frm-filter" action="<?= base_url('siswa/akademik/rangkuman-nilai/pdf') ?>" method="POST" target="_blank">
            <button type="submit" class="btn btn-danger btn-flat">Cetak</button>
        </form>
        <div class="row">
          <div class="col-md-12">
            <div class="table-responsive mt-5">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th class="text-center" width="10%">Nomor</th>
                    <th class="text-center" width="20%">Kelas</th>
                    <th class="text-center" width="25%">Kode Mata Pelajaran</th>
                    <th class="text-center" width="25%">Nama Mata Pelajaran</th>
                    <th class="text-center" width="10%">Grade</th>
                    <th class="text-center" width="10%">Nilai</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($list_nilai)): ?>
                    <?php foreach ($list_nilai as $i => $row): ?>
                      <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= $row['kelas'] ?></td>
                        <td><?= $row['mapel_code'] ?></td>
                        <td><?= $row['mapel_name'] ?></td>
                        <td><?= $row['grade'] ?></td>
                        <td><?= $row['nilai_akhir'] ?></td>
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
          </div>
        </div>
      </div>
    </div>
  </div>
</div>