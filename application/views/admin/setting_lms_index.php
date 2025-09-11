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
            <strong>Filter Kalimat</strong>
            <br>
            <em>Tidak Terhitung Absen Jika Diskusi hanya Mengandung Kalimat Dibawah</em>
            <form id="frm-filter-kalimat">
              <div class="form-group row">
                <div class="col-lg-6 col-sm-12">
                  <input type="text" name="filter_kalimat" class="form-control" />
                </div>
                <div class="col-lg-6 col-sm-12">
                  <button type="button" onclick="simpanFilterKalimat()" class="btn btn-success btn-flat">Tambah</button>
                </div>
              </div>
            </form>
            <div class="table-responsive mt-2">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th width="10%"></th>
                    <th width="90%">FILTERED WORD</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $settingFilter = json_decode($setting[2]['value']) ?? []; ?>
                  <?php if (!empty($settingFilter)): ?>
                      <?php foreach ($settingFilter as $row): ?>
                        <tr>
                          <td><input type="checkbox" name="filter" value="<?= $row ?>"></td>
                          <td><?= $row ?></td>
                        </tr>
                      <?php endforeach ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="2" class="text-center">Data tidak ditemukan.</td>
                      </tr>
                    <?php endif; ?>
                </tbody>
              </table>
            </div>
            <div class="mt-2">
                <button type="button" class="btn btn-danger btn-flat" onclick="hapusFilterKata()">Hapus Filter Kata</button>
            </div>
          </div>
        </div>
        <br>
        <form id="frm-absensi-diskusi">
          <div class="form-group row">
            <label for="periode_data" class="col-lg-2 col-sm-12 col-form-label">Total Kata Terhitung Absen setelah Filter</label>
            <div class="col-lg-6 col-sm-12">
              <input type="number" name="<?= $setting[1]['code'] ?>" value="<?= $setting[1]['value'] ?>" class="form-control" />
            </div>
          </div>
          <div class="form-group row">
            <label for="periode_data" class="col-lg-2 col-sm-12 col-form-label">Total Diskusi Terhitung Absen</label>
            <div class="col-lg-6 col-sm-12">
              <input type="number" name="<?= $setting[0]['code'] ?>" value="<?= $setting[0]['value'] ?>" class="form-control" />
            </div>
          </div>
          <div class="mb-2">
            <button type="button" onclick="simpanPerubahanAbsensi()" class="btn btn-info btn-flat">Update</button>
          </div>
        </form>
        <br>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  function simpanPerubahanAbsensi() {
    var form = document.getElementById('frm-absensi-diskusi');
    
    var formData = new FormData(form);
    formData.append('type', 'absensi_diskusi');
    
    fetch("<?= base_url('dashboard/setting-lms/do_update') ?>", {
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

  function simpanFilterKalimat() {
    var form = document.getElementById('frm-filter-kalimat');
    var formData = new FormData(form);
    formData.append('type', 'filter_kalimat');

    fetch("<?= base_url('dashboard/setting-lms/do_update') ?>", {
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

  function hapusFilterKata() {
    var checkedValues = Array.from(document.querySelectorAll('input[name="filter"]:checked')).map(cb => cb.value);
    if (checkedValues.length === 0) {
      return toastError('Pilih minimal satu filter kata untuk dihapus!');
    }

    var formData = new FormData();
    formData.append('type', 'hapus_filter_kalimat');
    formData.append('filter_kalimat', JSON.stringify(checkedValues));

    fetch("<?= base_url('dashboard/setting-lms/do_update') ?>", {
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
      return toastError('Gagal menghapus filter kata!');
    });
  }
</script>