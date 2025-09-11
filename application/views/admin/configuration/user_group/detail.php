<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><?php echo $subjudul ?></h3>
      </div>
      <div class="card-body">
        <form action="<?php echo $own_link ?>/<?php echo $action ?>" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="user_group_id" value="<?php echo $user_group_data->id_grup; ?>">
          <div class="form-group row">
            <label for="inputEmail3" class="col-lg-2 col-sm-12 col-form-label">User Group Name</label>
            <div class="col-lg-10 col-sm-12">
              <input type="text" name="name" class="form-control" value="<?php echo $user_group_data->name; ?>" readonly>
            </div>
          </div>
          <div class="form-group row">
            <div class="table-responsive mt-5">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th width="5%">No</th>
                    <th>Menu Name</th>
                    <th>View</th>
                    <th>Create</th>
                    <th>Update</th>
                    <th>Delete</th>
                    <th>Detail</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    $no = 1;
                    foreach ($menu_builder as $item) {
                      $menu = $item['menu'];
                      $id   = $menu['id'];
                      $sub_menu = [];
                      if (array_key_exists("sub_menu", $item)) {
                        $sub_menu = $item['sub_menu'];
                      }

                      $is_view    = 0;
                      $is_create  = 0;
                      $is_update  = 0;
                      $is_delete  = 0;
                      $is_detail  = 0;
                      foreach ($model as $menu_access) {
                        if ($menu_access->menu_id == $id) {
                          $is_view = 1;
                          if ($menu_access->is_create == 1) {
                            $is_create = 1;
                          }
                          if ($menu_access->is_update == 1) {
                            $is_update = 1;
                          }
                          if ($menu_access->is_delete == 1) {
                            $is_delete = 1;
                          }
                          if ($menu_access->is_detail == 1) {
                            $is_detail = 1;
                          }
                          break;
                        }
                      }

                      $is_parent = 0;
                      if (count($sub_menu) > 0) {
                        $is_parent = 1;
                      }
                  ?>
                      <tr>
                        <td><?php echo $no ?></td>
                        <td><?php echo $menu['name'] ?></td>
                        <td>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="menu_id[]" value="<?php echo $id ?>" <?php echo $is_view == 1 ? "checked" : "" ?>>
                          </div>
                        </td>
                        <td>
                          <?php if ($is_parent == 0): ?>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="is_create[<?php echo $id ?>]" value="1" <?php echo $is_create == 1 ? "checked" : "" ?>>
                            </div>
                          <?php endif ?>
                        </td>
                        <td>
                          <?php if ($is_parent == 0): ?>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="is_update[<?php echo $id ?>]" value="1" <?php echo $is_update == 1 ? "checked" : "" ?>>
                            </div>
                          <?php endif ?>
                        </td>
                        <td>
                          <?php if ($is_parent == 0): ?>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="is_delete[<?php echo $id ?>]" value="1" <?php echo $is_delete == 1 ? "checked" : "" ?>>
                            </div>
                          <?php endif ?>
                        </td>
                        <td>
                          <?php if ($is_parent == 0): ?>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="is_detail[<?php echo $id ?>]" value="1" <?php echo $is_detail == 1 ? "checked" : "" ?>>
                            </div>
                          <?php endif ?>
                        </td>
                      </tr>
                  <?php
                      if ($is_parent == 1) {
                        foreach ($sub_menu as $key => $subs) {
                          $id = $subs['id'];

                          $is_view    = 0;
                          $is_create  = 0;
                          $is_update  = 0;
                          $is_delete  = 0;
                          $is_detail  = 0;
                          foreach ($model as $menu_access) {
                            if ($menu_access->menu_id == $id) {
                              $is_view = 1;
                              if ($menu_access->is_create == 1) {
                                $is_create = 1;
                              }
                              if ($menu_access->is_update == 1) {
                                $is_update = 1;
                              }
                              if ($menu_access->is_delete == 1) {
                                $is_delete = 1;
                              }
                              if ($menu_access->is_detail == 1) {
                                $is_detail = 1;
                              }
                              break;
                            }
                          }
                  ?>
                          <tr>
                            <td></td>
                            <td><?php echo $subs['name'] ?></td>
                            <td>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" name="menu_id[]" value="<?php echo $id ?>" <?php echo $is_view == 1 ? "checked" : "" ?>>
                                </div>
                            </td>
                            <td>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" name="is_create[<?php echo $id ?>]" value="1" <?php echo $is_create == 1 ? "checked" : "" ?>>
                                </div>
                            </td>
                            <td>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" name="is_update[<?php echo $id ?>]" value="1" <?php echo $is_update == 1 ? "checked" : "" ?>>
                                </div>
                            </td>
                            <td>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" name="is_delete[<?php echo $id ?>]" value="1" <?php echo $is_delete == 1 ? "checked" : "" ?>>
                                </div>
                            </td>
                            <td>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" name="is_detail[<?php echo $id ?>]" value="1" <?php echo $is_detail == 1 ? "checked" : "" ?>>
                                </div>
                            </td>
                          </tr>
                  <?php
                        }
                      }
                      $no++;
                    }
                  ?>
                </tbody>
              </table>
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