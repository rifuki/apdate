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
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">Username</label>
            <div class="col-lg-10 col-sm-12">
              <input type="text" name="username" class="form-control" autocomplete="off" value="<?php echo isset($model)?$model->username:""; ?>" required>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">Name</label>
            <div class="col-lg-10 col-sm-12">
              <input type="text" name="name" class="form-control" autocomplete="off" value="<?php echo isset($model)?$model->name:""; ?>" required>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">User Group</label>
            <div class="col-lg-10 col-sm-12">
              <select class="form-control select2" style="width: 100%;" name="user_group_id">
                <?php foreach ($user_group as $key => $field): ?>
                  <?php 
                    $selected = ""; if(isset($model->user_group_id) && $model->user_group_id==$field['id']){ $selected = "selected"; } 
                  ?>
                  <option <?php echo $selected ?> value="<?php echo $field['id'] ?>"><?php echo $field['name'] ?></option>
                <?php endforeach ?>
              </select>
            </div>
          </div>
          <?php if (!isset($model)): ?>
            <div class="form-group row">
              <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">Password</label>
              <div class="col-lg-10 col-sm-12">
                <input type="password" class="form-control" name="password">
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