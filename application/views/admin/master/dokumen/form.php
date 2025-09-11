<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><?php echo $subjudul ?></h3>
      </div>
      <div class="card-body">
        <form action="<?php echo $own_link ?>/<?php echo $action ?>" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="id" value="<?php echo isset($model)?$model->id:""; ?>">
          <div class="form-group row">
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">Judul</label>
            <div class="col-lg-10 col-sm-12">
              <input type="text" name="judul" class="form-control" autocomplete="off" value="<?php echo isset($model)?$model->judul:""; ?>" required>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">Link</label>
            <div class="col-lg-10 col-sm-12">
              <?php if (isset($model) && !empty($model->file)): ?>
                <a href="<?php echo base_url($model->file); ?>" target="_blank" class="btn btn-success mb-2">Lihat File</a>
              <?php endif; ?>
              <input type="file" name="file" class="form-control">
            </div>
          </div>
          <div class="form-group row">
            <div class="col-lg-10 col-sm-12">
              <a href="<?php echo $own_link ?>" class="btn btn-danger">Kembali</a>
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>