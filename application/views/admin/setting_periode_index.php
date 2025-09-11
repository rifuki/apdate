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
          <div class="col-md-12 col-sm-12">
            <div class="table-responsive mt-2">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th width="30%">CURRENT PERIODE</th>
                    <th width="30%">SEMESTER</th>
                    <th width="40%">STATUS PERIODE</th>
                  </tr>
                </thead>
                <tbody>
                  <form id="frm-periode">
                      <tr>
                        <td>
                          <select name="periode" class="form-control">
                            <option value="" disabled selected>- PILIH PERIODE -</option>
                            <?php foreach ($periode as $p): ?>
                              <option value="<?= $p['id'] ?>" <?= (!empty($active_periode) && $active_periode['periode_id'] == $p['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($p['tahun_ajaran']) ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                        </td>
                        <td>
                          <select name="semester" class="form-control" required>
                            <option value="" disabled selected>- PILIH SEMESTER -</option>
                            <option value="1" <?= (!empty($active_periode) && $active_periode['semester'] == 1) ? 'selected' : '' ?>>1</option>
                            <option value="2" <?= (!empty($active_periode) && $active_periode['semester'] == 2) ? 'selected' : '' ?>>2</option>
                          </select>
                        </td>
                        <td>
                          <select name="status" class="form-control">
                            <option value="" disabled selected>- PILIH STATUS -</option>
                            <?php foreach ($setting as $s): ?>
                                <option  <?= (!empty($active_periode) && $active_periode['status'] == $s['code']) ? 'selected' : '' ?> value="<?= $s['code'] ?>"><?= $s['code'].' - '.$s['title'] ?></option>
                            <?php endforeach; ?>
                          </select>
                          <br>
                          <button type="button" class="btn btn-primary btn-sm" onclick="simpanPerubahan()">
                            <i class="fa fa-save"></i> Simpan Perubahan
                          </button>
                        </td>
                      </tr>
                  </form>
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-md-12 col-sm-12">
            <div class="table-responsive mt-2">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th width="20%">CODE</th>
                    <th width="25%">TITLE</th>
                    <th width="55%">DESCRIPTION</th>
                  </tr>
                </thead>
                <tbody>
                  <form id="frm-penilaian">
                    <?php if (!empty($setting)): ?>
                      <?php foreach ($setting as $row): ?>
                        <tr>
                          <td><?= htmlspecialchars($row['code']) ?></td>
                          <td><?= htmlspecialchars($row['title']) ?></td>
                          <td><?= htmlspecialchars($row['description']) ?></td>
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

<script type="text/javascript">
  function simpanPerubahan() {
    var form = document.getElementById('frm-periode');
    
    var formData = new FormData(form);
    
    fetch("<?= base_url('dashboard/setting-periode/do_update') ?>", {
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
      return toastError('Gagal menyimpan perubahan!');
    });
  }
</script>