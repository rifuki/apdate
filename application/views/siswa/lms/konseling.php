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
        <div class="mb-3">
          <button class="btn btn-primary" data-toggle="modal" data-target="#modalKonseling" id="btnAddKonseling">Tambah Konseling</button>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered table-striped" id="datatable">
            <thead>
              <tr>
                <th>NO</th>
                <th>JUDUL</th>
                <th>DESKRIPSI</th>
                <th>ACTION</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($listdata)): ?>
                <?php $no = 1; foreach ($listdata as $row): ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td><?= $row['deskripsi'] ?></td>
                    <td>
                      <button class="btn btn-warning btn-sm btnEditKonseling"
                        data-id="<?= $row['id'] ?>"
                        data-judul="<?= $row['judul'] ?>"
                        data-deskripsi="<?= htmlspecialchars($row['deskripsi']) ?>"
                        data-toggle="modal"
                        data-target="#modalKonseling">
                        Ubah
                      </button>
                      <button class="btn btn-danger btn-sm btnDelete" data-id="<?= $row["id"] ?>">Hapus</button>
                    </td>
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

        <!-- Modal Tambah/Edit konseling -->
        <div class="modal fade" id="modalKonseling" tabindex="-1" role="dialog" aria-labelledby="modalKonselingLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <form id="formKonseling">
                <div class="modal-header">
                  <h5 class="modal-title" id="modalKonselingLabel">Tambah/Edit Konseling</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <input type="hidden" name="id" id="id">
                  <div class="form-group">
                    <label>Judul</label>
                    <input type="text" name="judul" id="judul" class="form-control" required>
                  </div>
                  <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" id="deskripsi" rows="5"></textarea>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                  <button type="submit" class="btn btn-success" id="btnSimpan">Simpan</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="<?php echo base_url('assets/plugins/tinymce/js/tinymce/tinymce.min.js'); ?>"></script>
  

<script>
  document.addEventListener('DOMContentLoaded', function() {
    if (typeof tinymce !== 'undefined') {
      tinymce.init({
        selector: '#deskripsi',  // change this value according to your HTML
        license_key: 'gpl'
      });
    }
  });
  // Modal form handler untuk konseling
  document.addEventListener('DOMContentLoaded', function() {
    // Reset modal saat tambah konseling
    document.getElementById('btnAddKonseling').addEventListener('click', function() {
      document.getElementById('modalKonselingLabel').innerText = 'Tambah Konseling';
      document.getElementById('id').value = '';
        tinymce.get('deskripsi').setContent('');
    });
    // Isi modal saat edit konseling
    document.querySelectorAll('.btnEditKonseling').forEach(function(btn) {
      btn.addEventListener('click', function() {
        document.getElementById('modalKonselingLabel').innerText = 'Edit Konseling';
        document.getElementById('id').value = this.getAttribute('data-id');
        document.getElementById('judul').value = this.getAttribute('data-judul');
        tinymce.get('deskripsi').setContent(this.getAttribute('data-deskripsi'));
      });
    });

    $('#formKonseling">').on('submit', function(e) {
      e.preventDefault();
      if (typeof tinymce !== 'undefined') {
        var content = tinymce.get('deskripsi').getContent({ format: 'text' }).trim();
        if (content === '') {
          e.preventDefault();
          alert('Deskripsi wajib diisi!');
          tinymce.get('deskripsi').focus();
          return false;
        }
      }
      
      const formData = new FormData();
      formData.append('id', document.getElementById('id').value);
      formData.append('judul', document.getElementById('judul').value);
      formData.append('deskripsi', tinymce.get('deskripsi').getContent());
      $("#btnSimpan").prop('disabled', true).text('Mengirim...');
      console.log(formData);
      fetch("<?= base_url('siswa/konseling/save') ?>", {
          method: 'POST',
          body: formData
      })
      .then(response => response.json()) // jika kamu kirim JSON, sesuaikan
      .then(data => {
        if (data.status) {
          // setTimeout(() => {
          //   window.location.reload();
          // }, 1500);

          return toastSuccess(data.message);
        }
        return toastError(data.message);
      })
      .catch(error => {
          console.error('Gagal:', error);
          $("#btnSimpan").prop('disabled', false).text('Simpan');
          return toastError('Gagal mengirim data!');
      });
    });
  });
</script>