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
                <div class="card-body">
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Nama</label>
                    <div class="col-sm-8">
                      <input type="text" readonly="" class="form-control" value="<?php echo isset($notifikasi)?$notifikasi[0]['nama']:''; ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Tanggal</label>
                    <div class="col-sm-8">
                      <input type="text" readonly="" class="form-control" value="<?php echo isset($notifikasi)?convDate($notifikasi[0]['tanggal']):''; ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Address</label>
                    <div class="col-sm-8">
                      <textarea class="form-control" readonly=""><?php echo isset($notifikasi)?$notifikasi[0]['uraian']:''; ?></textarea>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <a href="<?php echo $own_link ?>" class="btn btn-default float-right ml-2">Kembali</a>
                </div>
            </div>
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>