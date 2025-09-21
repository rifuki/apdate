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
          <?php if(isset($modul) && !empty($modul['file'])): ?>
          <br>
            <a href="<?= base_url('upload/modul/' . $modul['file']) ?>" target="_blank" class="btn btn-info">LIHAT FILE</a>
          <br>
          <?php endif; ?>
          <div class="form-group row">
            <label for="jumlah_pertemuan" class="col-sm-12 col-md-12 col-form-label">Deskripsi <span class="text-danger">*</span></label>
            <div class="col-sm-12 col-md-12">
              <?php if(isset($modul) && !empty($modul['deskripsi'])) echo $modul['deskripsi']; ?>
            </div>
          </div>
          <div class="form-group mt-2">
            <a href="<?= base_url('dashboard/lms/detail/'.$pertemuan['jadwal_kelas_id']) ?>" class="btn btn-warning">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>