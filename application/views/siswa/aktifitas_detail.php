<div class="table-responsive mt-5">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="20%">Pertemuan</th>
                <th width="40%">Absensi Kehadiran</th>
                <th width="20%">Absensi Diskusi</th>
                <th width="20%">Tugas</th>
            </tr>
        </thead>
        <tbody id="table-detail">
            <?php if(isset($data) && is_array($data) && count($data) > 0): ?>
                <?php foreach($data as $i => $a): ?>
                    <tr>
                        <td><?= htmlspecialchars($a['pertemuan_ke']) ?></td>
                        <td>
                            <?= ucwords(str_replace("_", " ", $a['absensi_kehadiran'])) ?>
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
                                <span class="text-success">Sudah Mengerjakan</span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>