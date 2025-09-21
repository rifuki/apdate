<?php $is_open = in_array($active_periode['status_code'], ['2', '2.1']) ? true : false; ?>
<form id="frm-import-nilai" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="jadwal_id" id="frm_jadwal_id" value="<?= $jadwal_id ?>">
    <input type="hidden" name="siswa_id" id="frm_siswa_id" value="<?= $siswa_id ?>">
    <?php if ($is_open): ?>
    <div class="form-group mb-5 row">
        <div class="col-lg-12 col-sm-12">
            <input type="file" name="file" class="form-control" id="file" accept=".xls, .xlsx">
        </div>
        <div class="col-lg-12 col-sm-12">
            <br>
            <button type="button" onclick="downloadTemplate()" class="form-control btn btn-success">Download Template</button>
        </div>
        <div class="col-lg-12 col-sm-12">
            <button type="submit" class="form-control btn btn-danger mt-2">Import Nilai</button>
        </div>
    </div>
    <?php endif ?>
</form>
<div class="table-responsive mt-5">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="20%">Pertemuan</th>
                <th width="20%">Absensi Kehadiran</th>
                <th width="20%">Absensi Diskusi</th>
                <th width="20%">Tugas</th>
                <th width="20%">Nilai Tugas</th>
            </tr>
        </thead>
        <tbody id="table-detail">
            <?php if(isset($data) && is_array($data) && count($data) > 0): ?>
                <?php foreach($data as $i => $a): ?>
                    <tr>
                        <td><?= htmlspecialchars($a['pertemuan_ke']) ?></td>
                        <td>
                            <select data-pertemuanid="<?= $a['id'] ?>" data-siswaid="<?= $a['siswa_id'] ?>" class="form-control absensi-kehadiran" <?= $is_open ? "" : "readonly" ?>>
                                <option value="" disabled selected>Belum Absen</option>
                                <option value="hadir" <?= $a['absensi_kehadiran'] == 'hadir' ? 'selected' : '' ?>>Hadir</option>
                                <option value="tanpa_keterangan" <?= $a['absensi_kehadiran'] == 'tanpa_keterangan' ? 'selected' : '' ?>>Tanpa Keterangan</option>
                                <option value="sakit" <?= $a['absensi_kehadiran'] == 'sakit' ? 'selected' : '' ?>>Sakit</option>
                                <option value="izin" <?= $a['absensi_kehadiran'] == 'izin' ? 'selected' : '' ?>>Izin</option>
                            </select>
                        </td>
                        <td>
                            <?php if($a['absensi_diskusi'] >= 2 ): ?>
                            <button class="btn btn-info btn-sm">Hadir</button>
                            <?php else: ?>
                            <button class="btn btn-danger btn-sm">Tidak Hadir</button>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if(!empty($a['tugas'])): ?>
                                <button class="btn btn-warning btn-sm" onclick="lihatTugas('<?= $a['tugas'] ?>')">Lihat</button>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <input type="text" onkeypress="javascript:return isNumber(event)" class="form-control nilai-tugas" data-pertemuanid="<?= $a['id'] ?>" data-siswaid="<?= $a['siswa_id'] ?>" placeholder="0" value="<?= $a['nilai_tugas'] ?>" <?= $is_open ? "" : "readonly" ?>>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php if ($is_open): ?>
<button type="button" class="btn btn-success btn-flat float-right" onclick="simpanPenilaian()">Simpan Penilaian</button>
<?php endif ?>
<script>
    $("#frm-import-nilai").on("submit", function(e) {
        e.preventDefault(); // Mencegah form submit secara default
        importNilai();
    });
</script>