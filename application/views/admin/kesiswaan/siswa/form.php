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
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">NISN</label>
            <div class="col-lg-10 col-sm-12">
              <input type="text" name="nisn" class="form-control" autocomplete="off" value="<?php echo isset($model)?$model->nisn:""; ?>" required>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">Name</label>
            <div class="col-lg-10 col-sm-12">
              <input type="text" name="nama" class="form-control" autocomplete="off" value="<?php echo isset($model)?$model->nama:""; ?>" required>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">Tanggal Lahir</label>
            <div class="col-lg-10 col-sm-12">
              <input type="date" name="tanggal_lahir" class="form-control" autocomplete="off" value="<?php echo isset($model)?$model->tanggal_lahir : ""; ?>" required>
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