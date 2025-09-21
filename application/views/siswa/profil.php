<?php
// $siswa = array biodata siswa
// $orangtua = array of array data orang tua
?>
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Data <?php echo $judul ?></h3>
      </div>
      <div class="card-body">
        <form action="<?= site_url('siswa/profil_update') ?>" method="post">
          <div class="form-group">
            <label for="nama">Nama Lengkap</label>
            <input type="text" class="form-control" id="nama" name="nama" value="<?= isset($siswa['nama']) ? htmlspecialchars($siswa['nama']) : '' ?>" pattern="[A-Za-z\s]+" required>
          </div>
          <div class="form-group">
            <label for="nisn">NISN</label>
            <input oninput="this.value = this.value.replace(/[^0-9]/g, '')" type="text" class="form-control" id="nisn" name="nisn" value="<?= isset($siswa['nisn']) ? htmlspecialchars($siswa['nisn']) : '' ?>" readonly>
          </div>
          <div class="form-group">
            <label for="nomor_induk">Nomor Induk</label>
            <input oninput="this.value = this.value.replace(/[^0-9]/g, '')" type="text" class="form-control" id="nomor_induk" name="nomor_induk" value="<?= isset($siswa['nomor_induk']) ? htmlspecialchars($siswa['nomor_induk']) : '' ?>" readonly>
          </div>
          <div class="form-group">
            <label for="tempat_lahir">Tempat Lahir</label>
            <input pattern="[A-Za-z\s]+" type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="<?= isset($siswa['tempat_lahir']) ? htmlspecialchars($siswa['tempat_lahir']) : '' ?>">
          </div>
          <div class="form-group">
            <label for="tanggal_lahir">Tanggal Lahir</label>
            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?= isset($siswa['tanggal_lahir']) ? htmlspecialchars($siswa['tanggal_lahir']) : '' ?>">
          </div>
          <div class="form-group">
            <label for="agama">Agama</label>
            <input pattern="[A-Za-z\s]+" type="text" class="form-control" id="agama" name="agama" value="<?= isset($siswa['agama']) ? htmlspecialchars($siswa['agama']) : '' ?>">
          </div>
          <div class="form-group">
            <label for="sekolah_asal">Sekolah Asal</label>
            <input type="text" class="form-control" id="sekolah_asal" name="sekolah_asal" value="<?= isset($siswa['sekolah_asal']) ? htmlspecialchars($siswa['sekolah_asal']) : '' ?>">
          </div>
          <div class="form-group">
            <label for="nomor_hp">Nomor HP</label>
            <input oninput="this.value = this.value.replace(/[^0-9]/g, '')" type="text" class="form-control" id="nomor_hp" name="nomor_hp" value="<?= isset($siswa['nomor_hp']) ? htmlspecialchars($siswa['nomor_hp']) : '' ?>">
          </div>
          <div class="form-group">
            <label for="alamat">Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat"><?= isset($siswa['alamat']) ? htmlspecialchars($siswa['alamat']) : '' ?></textarea>
          </div>
          <hr>
          <h5>Data Orang Tua</h5>
          <?php if (!empty($orangtua)): ?>
            <?php foreach ($orangtua as $idx => $ortu): ?>
              <div class="border rounded p-2 mb-2">
                <div class="form-group">
                  <label>Nama Lengkap</label>
                  <input pattern="[A-Za-z\s]+" type="text" class="form-control" value="<?= htmlspecialchars($ortu['nama_lengkap']) ?>" readonly>
                </div>
                <div class="form-group">
                  <label>Hubungan Keluarga</label>
                  <input pattern="[A-Za-z\s]+" type="text" class="form-control" value="<?= htmlspecialchars($ortu['hubungan_keluarga']) ?>" readonly>
                </div>
                <div class="form-group">
                  <label>Nomor HP</label>
                  <input oninput="this.value = this.value.replace(/[^0-9]/g, '')" type="text" class="form-control" value="<?= htmlspecialchars($ortu['nomor_hp']) ?>" readonly>
                </div>
                <div class="form-group">
                  <label>Email</label>
                  <input type="email" class="form-control" value="<?= htmlspecialchars($ortu['email']) ?>" readonly>
                </div>
                <div class="form-group">
                  <label>Pekerjaan</label>
                  <input pattern="[A-Za-z\s]+" type="text" class="form-control" value="<?= htmlspecialchars($ortu['pekerjaan']) ?>" readonly>
                </div>
                <div class="form-group">
                  <label>Alamat</label>
                  <textarea class="form-control" readonly><?= htmlspecialchars($ortu['alamat']) ?></textarea>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="text-muted">Data orang tua belum tersedia.</p>
          <?php endif; ?>
          <hr>
          <h5>Ganti Password</h5>
          <div class="form-group">
            <label for="password_lama">Password Lama</label>
            <input type="password" class="form-control" id="password_lama" name="password_lama" autocomplete="off">
          </div>
          <div class="form-group">
            <label for="password_baru">Password Baru</label>
            <input type="password" class="form-control" id="password_baru" name="password_baru" autocomplete="off">
          </div>
          <div class="form-group">
            <label for="password_konfirmasi">Konfirmasi Password Baru</label>
            <input type="password" class="form-control" id="password_konfirmasi" name="password_konfirmasi" autocomplete="off">
          </div>
          <small class="form-text text-muted">Kosongkan bagian password jika tidak ingin mengganti password.</small>
          <br>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
      </div>
    </div>
  </div>
</div>