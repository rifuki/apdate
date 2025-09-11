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
        <div class="row">
          <div class="col-md-12">
            <strong>Data Berikut akan Terbackup</strong>
            <div class="table-responsive mt-2">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th width="100%">BACKUP</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($list_backup)): ?>
                      <?php foreach ($list_backup as $row): ?>
                        <tr>
                          <td><?= $row ?></td>
                        </tr>
                      <?php endforeach ?>
                    <?php endif; ?>
                </tbody>
              </table>
            </div>
            <form action="<?= base_url('dashboard/backup/do-backup') ?>" method="POST">
              <div class="mt-2">
                  <button type="submit" class="btn btn-danger btn-flat" >Backup Data</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>