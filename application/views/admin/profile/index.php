<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><?php echo $subjudul ?></h3>
      </div>
      <div class="card-body">
        <form action="<?php echo $own_link ?>/update" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="id" value="<?php echo isset($model)?$model->id_pegawai:""; ?>">
          <div class="form-group row">
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">Name</label>
            <div class="col-lg-10 col-sm-12">
              <input type="text" name="name" class="form-control" autocomplete="off" value="<?php echo isset($model)?$model->name:""; ?>" required>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">NIP</label>
            <div class="col-lg-10 col-sm-12">
              <input type="text" name="nip" class="form-control" autocomplete="off" value="<?php echo isset($model)?$model->nip:""; ?>" required>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">Phone Number</label>
            <div class="col-lg-10 col-sm-12">
              <input type="text" name="phone" onkeypress="javascript:return isNumber(event)" class="form-control" autocomplete="off" value="<?php echo isset($model)?$model->phone:""; ?>" required>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">Address</label>
            <div class="col-lg-10 col-sm-12">
              <textarea class="form-control" name="address"><?php echo isset($model)?$model->address:""; ?></textarea>
            </div>
          </div>
          <hr>
          <div class="form-group row">
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">Current Password</label>
            <div class="col-lg-10 col-sm-12">
              <input type="password" class="form-control" name="current_password">
            </div>
          </div>
          <div class="form-group row">
              <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">New Password</label>
              <div class="col-lg-10 col-sm-12">
                <input type="password" class="form-control" name="new_password">
                <br>
                <small style="color: red">* Isi current password dan new password jika ingin mengubah password anda</small>
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