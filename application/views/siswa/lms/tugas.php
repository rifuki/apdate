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

   video, #preview {
          border: 1px solid black;
          margin-bottom: 10px;
      }
</style>


<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><?php echo $subjudul ?></h3>
      </div>
      <div class="card-body">
         <!-- Form Upload Tugas -->
          <form id="formTugas">
            <input type="hidden" name="pertemuan_id" value="<?= $pertemuan['id'] ?>">
            <div class="form-group row">
              <label for="jumlah_pertemuan" class="col-sm-12 col-md-12 col-form-label">Upload Tugas</label>
              <div class="col-sm-12 col-md-12">
                <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.txt">
                <br>
                <span>Hanya bisa mengupload file pdf, excel, docs, txt</span>
              </div>
            </div>
            <?php if(isset($tugas) && !empty($tugas['file'])): ?>
            <br>
              <a href="<?= base_url($tugas['file']) ?>" target="_blank" class="btn btn-info">LIHAT FILE</a>
            <br>
            <?php endif; ?>
            <div class="form-group row">
              <label for="jumlah_pertemuan" class="col-sm-12 col-md-12 col-form-label">Link</label>
              <div class="col-sm-12 col-md-12">
                <input type="text" name="link" class="form-control" value="<?php if(isset($tugas) && !empty($tugas['link'])) echo $tugas['link']; ?>">
              </div>
            </div>
            <div class="form-group row">
              <label for="jumlah_pertemuan" class="col-sm-12 col-md-12 col-form-label">Deskripsi <span class="text-danger">*</span></label>
              <div class="col-sm-12 col-md-12">
                <textarea name="deskripsi" class="form-control textarea"><?php if(isset($tugas) && !empty($tugas['deskripsi'])) echo htmlspecialchars($tugas['deskripsi']); ?></textarea>
              </div>
            </div>
            <div class="form-group mt-2">
              <a href="<?= base_url('siswa/kelas/detail/'.$pertemuan['jadwal_kelas_id']) ?>" class="btn btn-warning">Kembali</a>
              <?php if(!$is_close): ?>
              <button id="btnSimpanTugas" type="submit" class="btn btn-success">Simpan Tugas</button>
              <?php endif ?>
            </div>
          </form>
      </div>
    </div>
  </div>
</div>

<script>

  $('#formTugas').on('submit', function(e) {
    e.preventDefault();
    // Validasi: minimal salah satu file atau link harus diisi
    var fileInput = $(this).find('input[type="file"]')[0];
    var linkInput = $(this).find('input[name="link"]').val().trim();

    if ((!fileInput.files || fileInput.files.length === 0) && linkInput === '') {
        toastError('Silakan upload file tugas atau isi link terlebih dahulu!');
        return;
    }
    
    const formData = new FormData();
    formData.append('pertemuan_id', '<?= $pertemuan['id'] ?>');

    $("#btnSimpanTugas").prop('disabled', true).text('Mengirim...');

    // Append file jika ada
    if (fileInput.files && fileInput.files.length > 0) {
      formData.append('file', fileInput.files[0]);
    }
    // Append link jika ada
    formData.append('link', linkInput);
    formData.append('deskripsi', $(this).find('textarea[name="deskripsi"]').val());

    fetch("<?= base_url('siswa/pertemuan/tugas-update') ?>", {
        method: 'POST',
        body: formData
    })
    .then(response => response.json()) // jika kamu kirim JSON, sesuaikan
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
        $("#btnSimpanTugas").prop('disabled', false).text('Simpan Tugas');
        return toastError('Gagal mengirim data!');
    });
  });
</script>
