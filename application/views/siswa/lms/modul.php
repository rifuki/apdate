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
          <?php if(isset($modul) && !empty($modul['file'])): ?>
            <a href="<?= base_url('upload/modul/' . $modul['file']) ?>" target="_blank" class="btn btn-info">LIHAT FILE</a>
          <br>
          <?php endif; ?>
          <div class="form-group row">
            <label for="jumlah_pertemuan" class="col-sm-12 col-md-12 col-form-label">Deskripsi <span class="text-danger">*</span></label>
            <div class="col-sm-12 col-md-12">
              <?php echo $modul['deskripsi'] ?>
            </div>
          </div>
          <div class="form-group mt-2">
            <a href="<?= base_url('siswa/kelas/detail/'.$pertemuan['jadwal_kelas_id']) ?>" class="btn btn-warning">Kembali</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
