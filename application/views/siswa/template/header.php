<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>APDATE</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?php echo base_url() ?>/assets/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>/assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>/assets/plugins/jqvmap/jqvmap.min.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>/assets/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>/assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>/assets/plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>/assets/plugins/summernote/summernote-bs4.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>/assets/plugins/toastr/toastr.min.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo base_url() ?>/assets/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>/assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>/assets/dist/css/custom.css">

  <style type="text/css">
    .normal-weight {
      font-weight: normal !important;
    }
  </style>
  <script src="<?php echo base_url() ?>/assets/plugins/jquery/jquery.min.js"></script>
  <script src="<?php echo base_url() ?>/assets/plugins/jquery-ui/jquery-ui.min.js"></script>
</head>
<?php $session = $this->session->userdata('user_dashboard'); ?>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  <nav class="main-header navbar navbar-expand">
    <ul class="navbar-nav d-flex align-items-center">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
      <?php 
        $total_notifikasi = 0;
        $data_notifikasi  = [];
        $notifikasi = [];
        if (!empty($notifikasi)) {
          $total_notifikasi = $notifikasi['total'];
          $data_notifikasi  = $notifikasi['result'];
        }
      ?>
      <li class="nav-item">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fas fa-bell"></i>
          <?php if ($total_notifikasi > 0): ?>
            <span class="badge badge-danger navbar-badge"><?php echo $total_notifikasi ?></span>
          <?php endif ?>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header"><?php echo $total_notifikasi ?> Notifications</span>
          <div class="dropdown-divider"></div>
          <?php if (!empty($data_notifikasi)): ?>
            <?php foreach ($data_notifikasi as $dn): ?>
              <a href="javascript:void(0)" class="dropdown-item">
                <i class="fas fa-envelope mr-2"></i> Kode Klasifikasi <?php echo $dn['name'] ?>
                <span class="float-right text-muted text-sm"><?php echo $dn['data'] ?> data kadaluwarsa</span>
              </a>
            <?php endforeach ?>
          <?php endif ?>
          <div class="dropdown-divider"></div>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fas fa-th-large"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="<?php echo admin_url('profile') ?>" class="dropdown-item">
            <i class="nav-icon fas fa-user"></i>&nbsp;&nbsp;Profil
          </a>
          <a href="<?php echo base_url() ?>siswa/logout" class="dropdown-item">
            <i class="nav-icon fas fa-sign-out-alt"></i>&nbsp;&nbsp;Logout
          </a>
        </div>
      </li>
    </ul>
  </nav>
  <aside class="main-sidebar sidebar-light-primary elevation-4">
    <div class="sidebar">
      <a href="<?php echo base_url('siswa') ?>" class="nav-link custom-brand-container">
          <img src="<?php echo base_url() ?>/assets/dist/images/logo.jpg" alt="APDATE Logo" class="custom-brand-image">
          <div class="custom-brand-text-wrapper">
              <span class="custom-brand-text-main">APDATE</span>
              <span class="custom-brand-text-sub">SMPN 1 RANCABUNGUR</span> 
          </div>
      </a>
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="#" class="d-block"><?php echo $session['user']['name'] ?></a>
        </div>
      </div>
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item "><a href="<?= base_url('siswa/dashboard') ?>" class="nav-link"><i class="nav-icon fas fa-home"></i><p>Dashboard</p></a></li>
          <li class="nav-item "><a href="<?= base_url('guru/akademik') ?>" class="nav-link"><i class="nav-icon fas fa-star"></i><p>Akademik</p></a></li>
          <li class="nav-item "><a href="<?= base_url('siswa/aktifitas-lms') ?>" class="nav-link"><i class="nav-icon fas fa-book"></i><p>Aktifitas LMS</p></a></li>
          <li class="nav-item "><a href="<?= base_url('siswa/lms') ?>" class="nav-link"><i class="nav-icon fas fa-book"></i><p>LMS</p></a></li>
          <li class="nav-item "><a href="<?= base_url('siswa/aspirasi') ?>" class="nav-link"><i class="nav-icon fas fa-share"></i><p>Aspirasi Siswa</p></a></li>
          <li class="nav-item "><a href="<?= base_url('siswa/konseling') ?>" class="nav-link"><i class="nav-icon fas fa-share"></i><p>Konseling Siswa</p></a></li><li class="nav-item "><a href="<?= base_url('guru/dokumen') ?>" class="nav-link"><i class="nav-icon fas fa-file"></i><p>Dokumen</p></a></li>
         
        </ul>
      </nav>
    </div>
  </aside>

  <div class="content-wrapper">
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
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <?php if ($this->session->flashdata('alert')): ?>
        <div class="alert alert-danger" role="alert">
          <?php echo $this->session->flashdata('alert') ?>
        </div>
      <?php endif ?>