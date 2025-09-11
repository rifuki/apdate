<style>
  #drop-area {
      border: 2px dashed #ccc;
      border-radius: 20px;
      width: 500px;
      padding: 50px;
      text-align: center;
      font-size: 1.2em;
      color: #666;
      transition: border-color 0.3s, background-color 0.3s;
  }
  #drop-area.highlight {
      border-color: #007bff;
      background-color: #f0f8ff;
  }
  #status {
      margin-top: 20px;
      font-weight: bold;
  }
  #status .success { color: green; }
  #status .error { color: red; }
</style>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><?php echo $subjudul ?></h3>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped" id="datatable">
            <thead>
              <tr>
                <th>NO</th>
                <th>SISWA</th>
                <th>JUDUL</th>
                <th>DESKRIPSI</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($listdata)): ?>
                <?php $no = 1; foreach ($listdata as $row): ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $row['nisn'].' - '.$row['nama'] ?></td>
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td><?= $row['deskripsi'] ?></td>
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
      </div>
    </div>
  </div>
</div>