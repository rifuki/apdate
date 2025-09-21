<h5 class="text-danger">
    Wali Kelas bisa meng-generate rapor ketika periode berubah ke status reporting <br/> 
    dan nilai akhir sudah diinput semua oleh guru
</h5>
<?php 
    $ternilai   = 0; 
    $total_data = count($data);
    $is_close   = (!empty($rapor) && $rapor['is_close'] == 1) ? 1:0;
?>
<form id="frm-rapor">
    <input type="hidden" name="periode_id" value="<?= $siswa['periode_id'] ?>">
    <input type="hidden" name="semester_id" value="<?= $siswa['semester_id'] ?>">
    <input type="hidden" name="siswa_id" value="<?= $siswa['siswa_id'] ?>">
    <input type="hidden" name="kelas_id" value="<?= $siswa['kelas_id'] ?>">
    <div class="table-responsive mt-5">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="20%">Mata Pelajaran</th>
                    <th width="20%">Pengajar</th>
                    <th width="20%">Nilai Akhir</th>
                    <th width="20%">Grade</th>
                    <th width="20%">Keterangan</th>
                </tr>
            </thead>
            <tbody id="table-detail">
                <?php if(isset($data) && is_array($data) && $total_data > 0): ?>
                    <?php foreach($data as $i => $a): ?>
                        <input type="hidden" name="mapel_code[<?= $i ?>]" value="<?= $a['mapel_code'] ?>">
                        <input type="hidden" name="guru_code[<?= $i ?>]" value="<?= $a['guru_code'] ?>">
                        <input type="hidden" name="grade[<?= $i ?>]" value="<?= $a['grade'] ?>">
                        <input type="hidden" name="keterangan[<?= $i ?>]" value="<?= $a['keterangan'] ?>">
                        <input type="hidden" name="nilai_old[<?= $i ?>]" value="<?= $a['nilai_akhir'] ?>">
                        <tr>
                            <td><?= $a['mapel_code'].' - '.$a['mapel_name'] ?></td>
                            <td><?= $a['guru_code'].' - '.$a['guru_name'] ?></td>
                            <td><input type="number" name="nilai[<?= $i ?>]" max="100" class="form-control nilai_akhir" value="<?= $a['nilai_akhir'] ?>" readonly></td>
                            <td><?= $a['grade'] ?></td>
                            <td><?= $a['keterangan'] ?></td>
                        </tr>
                        <?php if (!is_null($a['nilai_akhir'])) { $ternilai++; } ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</form>
<?php if ($is_close == 0 && $total_data > 0 && $ternilai == $total_data && $active_periode['status_code'] == '3'): ?>
    <span class="text-danger">* Jika anda mengubah nilai maka keterangan grade akan mengikuti pengaturan awal</span>
    <br>
    <span class="text-danger">* Rapor yang sudah digenerate tidak bisa dirubah kembali</span>
    <br>
    <button type="button" onclick="editRapor()" class="btn btn-warning" id="btn-edit-nilai">Edit Nilai</button>
    <button type="button" onclick="generateRapor(false)" class="btn btn-info" id="btn-simpan-nilai" style="display:none">Simpan Nilai Sementara</button>
    <button type="button" onclick="generateRapor(true)" class="btn btn-danger">Generate Rapor</button>
<?php endif ?>