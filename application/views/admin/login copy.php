<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>LIPI Center | Log in</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>/assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/dist/css/adminlte.min.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  </head>
  <body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="<?php echo base_url() ?>"><b>Arsip</b> LIPI</a>
        <br>
        <img class="img-fluid" src="<?php echo base_url('assets/img/lipi.png') ?>" style="width: auto; height: 100px;">
      </div>
      <div class="card">
        <div class="card-body login-card-body">
          <?php if ($this->session->flashdata('alert')): ?>
            <div class="alert alert-danger" role="alert">
              <?php echo $this->session->flashdata('alert') ?>
            </div>
          <?php endif ?>

          <form action="<?php echo base_url() ?>auth/login_dashboard" method="post">
            <div class="input-group mb-3">
              <input type="text" class="form-control" name="nip" placeholder="Nomor Induk Pegawai">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-user"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="password" class="form-control" name="password" placeholder="Password">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block btn-flat">Masuk</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <script src="<?php echo base_url() ?>assets/plugins/jquery/jquery.min.js"></script>
    <script src="<?php echo base_url() ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url() ?>assets/dist/js/adminlte.min.js"></script>

  </body>
</html>