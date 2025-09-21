<div class="container">
  <div class="row">
    <div class="col-md-12 mb-3">
      <h5>File Tugas</h5>
      <?php if (!empty($tugas['file'])): ?>
        <a href="<?= base_url($tugas['file']) ?>" target="_blank" class="btn btn-primary mb-2">
          <i class="fa fa-file"></i> Lihat File Tugas
        </a>
      <?php else: ?>
        <p class="text-danger">Tidak ada file tugas yang diupload.</p>
      <?php endif; ?>
    </div>
    <div class="col-md-12 mb-3">
      <h5>Link Tugas</h5>
      <?php if (!empty($tugas['link'])): ?>
        <!-- <a href="<?= htmlspecialchars($tugas['link']) ?>" target="_blank" class="btn btn-success mb-2">
          <i class="fa fa-link"></i> Buka Link di Tab Baru
        </a>
        <div class="text-muted"><?= htmlspecialchars($tugas['link']) ?></div> -->
        <a href="<?= htmlspecialchars($tugas['link']) ?>" target="_blank"><?= htmlspecialchars($tugas['link']) ?></a>
      <?php else: ?>
        <p class="text-danger">Tidak ada link tugas.</p>
      <?php endif; ?>
    </div>
    <div class="col-md-12 mb-3">
      <h5>Deskripsi Tugas</h5>
      <div class="border rounded p-2 bg-light">
        <?= !empty($tugas['deskripsi']) ? nl2br($tugas['deskripsi']) : '<span class="text-muted">Tidak ada deskripsi tugas.</span>' ?>
      </div>
    </div>
  </div>
</div>