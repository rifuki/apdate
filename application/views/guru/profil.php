<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Data Profil Guru</h3>
      </div>
      <div class="card-body">
        <form action="<?= site_url('guru/profil_update') ?>" method="post">
          <div class="form-group">
            <label for="nip">NIP</label>
            <input type="text" class="form-control" id="nip" name="nip" value="<?= isset($guru['nip']) ? htmlspecialchars($guru['nip']) : '' ?>" readonly>
          </div>
          <div class="form-group">
            <label for="gelar_depan">Gelar Depan</label>
            <input type="text" class="form-control" id="gelar_depan" name="gelar_depan" value="<?= isset($guru['gelar_depan']) ? htmlspecialchars($guru['gelar_depan']) : '' ?>">
          </div>
          <div class="form-group">
            <label for="nama">Nama Lengkap</label>
            <input type="text" pattern="[A-Za-z\s]+"  class="form-control" id="nama" name="nama" value="<?= isset($guru['nama']) ? htmlspecialchars($guru['nama']) : '' ?>" required>
          </div>
          <div class="form-group">
            <label for="gelar_belakang">Gelar Belakang</label>
            <input type="text" class="form-control" id="gelar_belakang" name="gelar_belakang" value="<?= isset($guru['gelar_belakang']) ? htmlspecialchars($guru['gelar_belakang']) : '' ?>">
          </div>
          <div class="form-group">
            <label for="jenis_kelamin">Jenis Kelamin</label>
            <select class="form-control" id="jenis_kelamin" name="jenis_kelamin">
              <option value="Laki-laki" <?= (isset($guru['jenis_kelamin']) && $guru['jenis_kelamin'] == 'Laki-laki') ? 'selected' : '' ?>>Laki-laki</option>
              <option value="Perempuan" <?= (isset($guru['jenis_kelamin']) && $guru['jenis_kelamin'] == 'Perempuan') ? 'selected' : '' ?>>Perempuan</option>
            </select>
          </div>
          <div class="form-group">
            <label for="tempat_lahir">Tempat Lahir</label>
            <input type="text" pattern="[A-Za-z\s]+" class="form-control" id="tempat_lahir" name="tempat_lahir" value="<?= isset($guru['tempat_lahir']) ? htmlspecialchars($guru['tempat_lahir']) : '' ?>">
          </div>
          <div class="form-group">
            <label for="tanggal_lahir">Tanggal Lahir</label>
            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?= isset($guru['tanggal_lahir']) ? htmlspecialchars($guru['tanggal_lahir']) : '' ?>">
          </div>
          <div class="form-group">
            <label for="agama">Agama</label>
            <input type="text" pattern="[A-Za-z\s]+" class="form-control" id="agama" name="agama" value="<?= isset($guru['agama']) ? htmlspecialchars($guru['agama']) : '' ?>">
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= isset($guru['email']) ? htmlspecialchars($guru['email']) : '' ?>">
          </div>
          <div class="form-group">
            <label for="nomor_hp">Nomor HP</label>
            <input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '')"  class="form-control" id="nomor_hp" name="nomor_hp" value="<?= isset($guru['nomor_hp']) ? htmlspecialchars($guru['nomor_hp']) : '' ?>">
          </div>
          <div class="form-group">
            <label for="alamat">Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat"><?= isset($guru['alamat']) ? htmlspecialchars($guru['alamat']) : '' ?></textarea>
          </div>
          <div class="form-group">
            <label for="asal_universitas">Asal Universitas</label>
            <input type="text" pattern="[A-Za-z\s]+" class="form-control" id="asal_universitas" name="asal_universitas" value="<?= isset($guru['asal_universitas']) ? htmlspecialchars($guru['asal_universitas']) : '' ?>">
          </div>
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