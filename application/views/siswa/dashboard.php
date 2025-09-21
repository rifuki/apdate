<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
<style>
  .dashboard-card .card-body {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.5rem;
  }

  .dashboard-card .card-text-section {
    line-height: 1.2;
  }
  
  .dashboard-card .card-count {
    font-size: 2rem;
    font-weight: 700;
  }

  .dashboard-card .icon-container {
    width: 60px;
    height: 60px;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .dashboard-card .icon-container i {
    color: white;
    font-size: 1.75rem;
  }

  /* Custom Background Colors from Image */
  .bg-custom-blue { background-color: #435ebe; }
  .bg-custom-orange { background-color: #f39c12; }
  .bg-custom-red { background-color: #e74c3c; }
</style>
<div class="container mt-4">
  <div class="row mb-4">
  <div class="col-md-4">
    <div class="card shadow-sm border-0 dashboard-card mb-3">
      <div class="card-body">
        <div class="card-text-section">
          <p class="card-text text-muted mb-1">Total Absen</p>
          <p class="card-count">
            <?= isset($total_absen) ? $total_absen : 15 ?>
          </p>
        </div>
        <div class="icon-container bg-custom-blue">
          <i class="fa-solid fa-clipboard-list"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card shadow-sm border-0 dashboard-card mb-3">
      <div class="card-body">
        <div class="card-text-section">
          <p class="card-text text-muted mb-1">Total Diskusi</p>
          <p class="card-count">
            <?= isset($total_diskusi) ? $total_diskusi : 20 ?>
          </p>
        </div>
        <div class="icon-container bg-custom-orange">
          <i class="fa-solid fa-comments"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card shadow-sm border-0 dashboard-card mb-3">
      <div class="card-body">
        <div class="card-text-section">
          <p class="card-text text-muted mb-1">Total Tugas</p>
          <p class="card-count">
            <?= isset($total_tugas) ? $total_tugas : 10 ?>
          </p>
        </div>
        <div class="icon-container bg-custom-red">
          <i class="fa-solid fa-list-check"></i>
        </div>
      </div>
    </div>
  </div>
</div>

  <!-- Pusat Informasi -->
  <div class="card">
    <div class="card-header bg-info text-white">
      <h5 class="mb-0">Pusat Informasi</h5>
    </div>
    <div class="card-body">
      <?php if (!empty($informasi) && is_array($informasi)): ?>
        <?php foreach ($informasi as $info): ?>
          <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <h6 class="font-weight-bold mb-1"><?= htmlspecialchars($info['judul']) ?></h6>
            <span style="font-size: 0.95rem; color: #888; white-space: nowrap;"><?= date('d M Y', strtotime($info['tanggal'])) ?></span>
          </div>
          <p><?php echo $info['deskripsi'] ?></p>
          <hr>
        <?php endforeach; ?>
      <?php else: ?>
        <p>Tidak ada informasi terbaru.</p>
      <?php endif; ?>
    </div>
  </div>
</div>