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
        <form action="<?= base_url('guru/pertemuan/modul-update') ?>" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="pertemuan_id" value="<?= $pertemuan['id'] ?>">
          <div class="form-group row">
            <label for="jumlah_pertemuan" class="col-sm-12 col-md-12 col-form-label">Upload Modul <span class="text-danger">*</span></label>
            <div class="col-sm-12 col-md-12">
              <input type="file" name="file" class="form-control">
            </div>
          </div>
          <?php if(isset($modul) && !empty($modul['file'])): ?>
          <br>
            <a href="<?= base_url('upload/modul/' . $modul['file']) ?>" target="_blank" class="btn btn-info">LIHAT FILE</a>
          <br>
          <?php endif; ?>
          <div class="form-group row">
            <label for="jumlah_pertemuan" class="col-sm-12 col-md-12 col-form-label">Deskripsi <span class="text-danger">*</span></label>
            <div class="col-sm-12 col-md-12">
              <textarea name="deskripsi" class="form-control textarea"><?php if(isset($modul) && !empty($modul['deskripsi'])) echo htmlspecialchars($modul['deskripsi']); ?></textarea>
            </div>
          </div>
          <div class="form-group mt-2">
            <a href="<?= base_url('guru/jadwal-kelas/detail/'.$pertemuan['jadwal_kelas_id']) ?>" class="btn btn-warning">Batal</a>
            <button type="submit" class="btn btn-success">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>

    // Get the drop area element
    let dropArea = document.getElementById('drop-area');

    // Prevent default browser behavior for drag events
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    // Add a 'highlight' class when a file is dragged over the area
    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, () => dropArea.classList.add('highlight'), false);
    });

    // Remove the 'highlight' class when a file leaves the area
    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, () => dropArea.classList.remove('highlight'), false);
    });

    // Handle the dropped files
    dropArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        let dt = e.dataTransfer;
        let files = dt.files[0];
        document.getElementById('ready-submit').style.display = 'block';
    }

    function handleFiles(files) {
        files = [...files]; // Convert FileList to Array
        // files.forEach(uploadFile);
    }
    // Optional: Tambahkan event pada tombol submit
    document.addEventListener('DOMContentLoaded', function() {
      var submitBtn = document.getElementById('submitFile');
      if(submitBtn) {
        submitBtn.addEventListener('click', function() {
          alert('File siap diproses lebih lanjut!');
          // Tambahkan aksi submit sesuai kebutuhan
        });
      }
    });
</script>