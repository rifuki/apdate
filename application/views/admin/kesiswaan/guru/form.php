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
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">NIP</label>
            <div class="col-lg-10 col-sm-12">
              <input type="text" name="nip" class="form-control" autocomplete="off" value="<?php echo isset($model)?$model->nip:""; ?>" required>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">Name</label>
            <div class="col-lg-10 col-sm-12">
              <input type="text" name="nama" class="form-control" autocomplete="off" value="<?php echo isset($model)?$model->nama:""; ?>" required>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">Email</label>
            <div class="col-lg-10 col-sm-12">
              <input type="email" name="email" class="form-control" autocomplete="off" value="<?php echo isset($model)?$model->email : ""; ?>" required>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">Nomor HP</label>
            <div class="col-lg-10 col-sm-12">
              <input type="text" name="nomor_hp" class="form-control" autocomplete="off" value="<?php echo isset($model)?$model->nomor_hp : ""; ?>" required>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">Join Periode</label>
            <div class="col-lg-10 col-sm-12">
              <select class="form-control select2" style="width: 100%;" name="join_periode_id">
                <option value="<?= $periode['id'] ?>" selected><?= $periode['tahun_ajaran'] ?></option>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">Aktif</label>
            <div class="col-lg-10 col-sm-12">
              <select class="form-control select2" style="width: 100%;" name="is_active">
                <option <?= (isset($model) && $model->is_active == 0) ? 'selected' : '' ?> value="0">No</option>
                <option <?= (isset($model) && $model->is_active == 1) ? 'selected' : '' ?> value="1">Yes</option>
              </select>
            </div>
          </div>
          <?php if(isset($model)): ?>
          <div class="form-group row">
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">Mata Pelajaran</label>
            <div class="col-lg-10 col-sm-12">
              <select class="form-control select2" style="width: 100%;" name="mata_pelajaran_ids[]" multiple>
                 <?php foreach ($mata_pelajaran as $field): ?>
                  <option <?= in_array($field['id'], $model_mapel) ? 'selected' : '' ?> value="<?= $field['id'] ?>"><?= $field['code'].' - '.$field['name'] ?></option>
                <?php endforeach ?>
              </select>
            </div>
          </div>
          <?php endif ?>
          <div class="form-group row">
            <div class="col-lg-10 col-sm-12">
              <a href="<?php echo $own_link ?>" class="btn btn-danger">Batal</a>
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    
  });
</script>