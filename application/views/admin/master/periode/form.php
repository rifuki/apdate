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
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">Tahun Ajaran</label>
            <div class="col-lg-10 col-sm-12">
              <input type="text" name="tahun_ajaran" class="form-control" autocomplete="off" value="<?php echo isset($model)?$model->tahun_ajaran:""; ?>" required>
            </div>
          </div>
          <?php if(isset($model)): ?>
            <?php foreach ($tingkat_kelas as $tk): ?>
              <div class="form-group row">
                <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label"><?= $tk['name'] ?></label>
                <div class="col-lg-10 col-sm-12">
                  <select class="form-control select2" style="width: 100%;" name="mata_pelajaran_ids[<?= $tk['id'] ?>][]" multiple>
                    <?php foreach ($mata_pelajaran as $field): ?>
                      <option 
                        <?php
                          $asvalue = $field['guru_id'].'-'.$field['mata_pelajaran_id'];
                          echo (!empty($model_mapel[$tk['id']]) && in_array($asvalue, $model_mapel[$tk['id']])) ? 'selected' : '';
                        ?> 
                        value="<?= $asvalue ?>">
                          <?= $field['mapel_code'].' '.$field['mapel_name'].' - '.$field['guru_nip'].' '.$field['guru_nama'] ?>
                      </option>
                    <?php endforeach ?>
                  </select>
                </div>
              </div>
            <?php endforeach ?>
          <?php endif ?>
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