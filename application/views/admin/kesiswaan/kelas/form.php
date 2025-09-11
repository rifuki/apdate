<style>
    .select2-selection__choice {
    display: block !important;
    width: 100%;
    margin: 2px 0;
    }
</style>
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
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">Periode</label>
            <div class="col-lg-10 col-sm-12">
              <select class="form-control select2" style="width: 100%;" name="periode_id">
                <option value="<?= $periode['id'] ?>" selected><?= $periode['tahun_ajaran'] ?></option>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">Tingkat Kelas</label>
            <div class="col-lg-10 col-sm-12">
              <select class="form-control select2" style="width: 100%;" name="tingkat_kelas_id">
                <?php foreach ($tingkat_kelas as $field): ?>
                  <option <?= (!empty($model) && $model->tingkat_kelas_id == $field['id']) ? 'selected' : '' ?> value="<?= $field['id'] ?>"><?= $field['code'].' - '.$field['name'] ?></option>
                <?php endforeach ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">Nama Kelas</label>
            <div class="col-lg-10 col-sm-12">
              <input type="text" name="kelas" class="form-control" autocomplete="off" value="<?php echo isset($model)?$model->kelas:""; ?>" required>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">Wali Kelas</label>
            <div class="col-lg-10 col-sm-12">
              <select class="form-control select2" style="width: 100%;" name="guru_id">
                <?php foreach ($guru as $field): ?>
                  <option <?= (!empty($model) && $model->guru_id == $field['id']) ? 'selected' : '' ?> value="<?= $field['id'] ?>"><?= $field['nip'].' - '.$field['nama'] ?></option>
                <?php endforeach ?>
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