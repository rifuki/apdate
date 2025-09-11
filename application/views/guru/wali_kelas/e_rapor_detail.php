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
            <?php if(isset($data) && is_array($data) && count($data) > 0): ?>
                <?php foreach($data as $i => $a): ?>
                    <tr>
                        <td><?= $a['mapel_code'].' - '.$a['mapel_name'] ?></td>
                        <td><?= $a['guru_code'].' - '.$a['guru_name'] ?></td>
                        <td><?= $a['nilai_akhir'] ?></td>
                        <td><?= $a['grade'] ?></td>
                        <td><?= $a['keterangan'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>