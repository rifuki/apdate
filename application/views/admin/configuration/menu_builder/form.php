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
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">Name</label>
            <div class="col-lg-10 col-sm-12">
              <input type="text" name="name" class="form-control" autocomplete="off" value="<?php echo isset($model)?$model->name:""; ?>" required>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">Parent</label>
            <div class="col-lg-10 col-sm-12">
              <select class="form-control select2" style="width: 100%;" name="menu_parent_id" id="menu_parent_id">
                <option value="0">-</option>
                <?php foreach ($menu_builder as $key => $field): ?>
                  <?php 
                    $selected = ""; if(isset($model->menu_parent_id) && $model->menu_parent_id==$field['id']){ $selected = "selected"; } 
                  ?>
                  <option <?php echo $selected ?> value="<?php echo $field['id'] ?>"><?php echo $field['name'] ?></option>
                <?php endforeach ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">Routes</label>
            <div class="col-lg-10 col-sm-12">
              <input type="text" name="routes" id="routes" class="form-control" autocomplete="off" value="<?php echo isset($model)?$model->routes:""; ?>" required>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">Icon</label>
            <div class="col-lg-10 col-sm-12">
              <input type="text" name="icon" class="form-control" autocomplete="off" id="icon" value="<?php echo isset($model)?$model->icon:""; ?>" required>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">Order</label>
            <div class="col-lg-10 col-sm-12">
              <input type="text" name="precedence" onkeypress="javascript:return isNumber(event)" class="form-control" autocomplete="off" required value="<?php echo isset($model)?$model->precedence:""; ?>">
            </div>
          </div>
          <div id="is_config" class="form-group row">
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">Is Config</label>
            <div class="col-lg-10 col-sm-12">
              <?php 
                $is_config = 0;
                if (isset($model) && $model->is_config == 1) {
                  $is_config = 1;
                }
              ?>
              <div class="form-check">
                <input class="form-check-input" type="radio" <?php echo $is_config == 1 ? "checked" : ""; ?> name="is_config" value="1">
                <label class="form-check-label">Yes</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" <?php echo $is_config == 0 ? "checked" : ""; ?> name="is_config" value="0">
                <label class="form-check-label">No</label>
              </div>
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
    menu_parent_id();
    $("#menu_parent_id").change(function(){
      menu_parent_id();
    });
  });

  function menu_parent_id() {
    var menu_parent_id = $("#menu_parent_id").val();
    if (menu_parent_id == 0) {
      $("#routes").attr("required", false);
      $("#icon").attr("readonly", false);
    } else {
      $("#icon").val("fa-circle");
      $("#routes").attr("required", true);
      $("#icon").attr("readonly", true);
    }
  }
</script>