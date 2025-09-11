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
            <div class="card">
            <div class="card-header">
              <h3 class="card-title">Data <?php echo $judul ?></h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="table-responsive mt-5">
                <table id="datatable" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th width="6%">No</th>
                      <th width="15%">Nama</th>
                      <th width="15%">Tanggal</th>
                      <th width="10%"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $no=1; foreach ($notifikasi as $val): ?>
                      <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $val['nama'] ?></td>
                        <td><?php echo convDate($val['tanggal']) ?></td>
                        <td>
                          <a href="<?php echo $own_link ?>/<?php echo $val['id'] ?>" class="btn btn-success btn-sm btn-flat"><i class="fas fa-list"></i></a>
                        </td>
                      </tr>
                    <?php endforeach ?>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>