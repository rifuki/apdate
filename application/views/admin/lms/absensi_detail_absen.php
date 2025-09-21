<div class="container">
  <div class="row">
    <div class="col-md-12 mb-3 text-center">
      <h5>Foto Absensi</h5>
      <img src="<?= base_url($absensi['foto']) ?>" alt="Foto Absensi" class="img-thumbnail" width="320" height="240">
    </div>
    <div class="col-md-12 mb-3">
      <h5>Lokasi Absensi</h5>
      <?php
        $coords = explode(',', $absensi['coordinate']);
        $lat = $coords[0] ?? '';
        $lng = $coords[1] ?? '';
      ?>
      <?php if ($lat && $lng): ?>
        <iframe width="100%" height="300" frameborder="0" style="border:0"
          src="https://www.google.com/maps?q=<?= $lat ?>,<?= $lng ?>&hl=es;z=16&output=embed">
        </iframe>
        <div class="mt-2">
          <span class="badge badge-info">Lat: <?= htmlspecialchars($lat) ?>, Lng: <?= htmlspecialchars($lng) ?></span>
        </div>
      <?php else: ?>
        <p class="text-danger">Lokasi tidak tersedia.</p>
      <?php endif; ?>
    </div>
    <div class="col-md-12 mb-3">
      <h5>Waktu Absensi</h5>
      <span class="badge badge-primary">
        <?= date('d-m-Y H:i:s', strtotime($absensi['created_at'])) ?>
      </span>
    </div>
  </div>
</div>