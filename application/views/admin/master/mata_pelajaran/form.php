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
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">Kode</label>
            <div class="col-lg-10 col-sm-12">
              <input type="text" name="code" class="form-control" autocomplete="off" value="<?php echo isset($model)?$model->code:""; ?>" required>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">Nama</label>
            <div class="col-lg-10 col-sm-12">
              <input type="text" name="name" class="form-control" autocomplete="off" value="<?php echo isset($model)?$model->name:""; ?>" required>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">Ekstrakulikuler</label>
            <div class="col-lg-10 col-sm-12">
              <select name="is_ekstrakulikuler" id="" class="form-control">
                <option value="0" <?= (isset($model) && $model->is_ekstrakulikuler == 0) ? 'selected' : '' ?>>TIDAK</option>
                <option value="1"  <?= (isset($model) && $model->is_ekstrakulikuler == 1) ? 'selected' : '' ?>>YA</option>
              </select>
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