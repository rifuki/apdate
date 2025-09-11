<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><?php echo $judul ?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#"><?php echo $judul ?></a></li>
              <li class="breadcrumb-item active"><?php echo $subjudul ?></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title"><?php echo $subjudul.' '.$judul ?></h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal" enctype="multipart/form-data" method="post" action="">
                <div class="card-body">
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Header Image</label>
                    <div class="col-sm-4">
                      <input type="file" name="header_image">
                    </div>
                    <?php if (isset($config) && $config[0]['header_image'] != ""): ?>
                      <div class="col-sm-4">
                        <a href="<?php echo base_url() ?>assets_front/img/<?php echo $config[0]['header_image'] ?>" class="btn btn-primary" target="_blank">Image</a>
                      </div>
                    <?php endif ?>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Office Number</label>
                    <div class="col-sm-8">
                      <input type="text" onkeypress="javascript:return isNumber(event)" class="form-control" name="office_number" required="" value="<?php echo isset($config)?$config[0]['office_number']:''; ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Phone Number</label>
                    <div class="col-sm-8">
                      <input type="text" onkeypress="javascript:return isNumber(event)" class="form-control" name="phone_number" required="" value="<?php echo isset($config)?$config[0]['phone_number']:''; ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Address</label>
                    <div class="col-sm-8">
                      <textarea class="form-control" name="address"><?php echo isset($config)?$config[0]['address']:''; ?></textarea>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <a href="<?php echo $own_link ?>" class="btn btn-default float-right ml-2">Batal</a>
                  <input type="submit" class="btn btn-info float-right" value="Simpan" name="simpan">
                </div>
                <!-- /.card-footer -->
              </form>
            </div>
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>