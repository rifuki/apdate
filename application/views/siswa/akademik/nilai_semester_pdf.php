<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Hasil Studi Siswa</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 20px;
            font-size: 12px;
        }
        .page {
            border: 1px solid #ccc;
            padding: 25px;
            margin-bottom: 20px;
            page-break-after: always;
            background-color: white;
        }
        h1, h2, h3, h4 {
            text-align: center;
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 5px;
            text-align: left;
            vertical-align: top;
        }
        .info-table, .no-border-table {
            border: none;
        }
        .info-table td, .no-border-table td {
            border: none;
            padding: 2px 0;
        }
        .info-table td:first-child { width: 30%; }
        .info-table td:nth-child(2) { width: 2%; }
        .header-info {
            display: flex;
            justify-content: space-between;
        }
        .signature-table {
            border: none;
            margin-top: 30px;
        }
        .signature-table td {
            border: none;
            text-align: center;
        }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .small-text { font-size: 11px; }
        .sub-header { font-weight: bold; margin-top: 15px; margin-bottom: 5px; }
    </style>
</head>
<body>

    <div class="page">
        <br><br>
        <h2>KARTU HASIL STUDI SISWA</h2>
        <h2>SMP NEGERI 1 RANCABUNGUR</h2>
        <br><br><br>
        <div class="header-info">
            <table class="info-table" style="width: 100%;">
                <tr><td>Nama Peserta Didik</td><td>:</td><td><?= $siswa['nama'] ?></td></tr>
                <tr><td>Nomor Induk/NISN</td><td>:</td><td><?= $siswa['nomor_induk'].'/'.$siswa['nisn'] ?></td></tr>
                <tr><td>Kelas</td><td>:</td><td><?= $siswa['kelas'] ?></td></tr>
                <tr><td>Semester</td><td>:</td><td><?= $active_periode['semester'] ?></td></tr>
                <tr><td>Tahun Pelajaran</td><td>:</td><td><?= $active_periode['tahun_ajaran'] ?></td></tr>
            </table>
        </div>
        <table class="small-text">
            <thead>
                <tr>
                    <th class="text-center" width="10%">Nomor</th>
                    <th class="text-center" width="20%">Kode Mata Pelajaran</th>
                    <th class="text-center" width="20%">Nama Mata Pelajaran</th>
                    <th class="text-center" width="15%">Jumlah Kehadiran</th>
                    <th class="text-center" width="15%">Tugas</th>
                    <th class="text-center" width="10%">UTS</th>
                    <th class="text-center" width="10%">UAS</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach($list_nilai as $i => $row): ?>
                    <tr>
                        <td class="text-center"><?= $no ?></td>
                        <td  class="text-center"><?= $row['mapel_code'] ?></td>
                        <td  class="text-center"><?= $row['mapel_name'] ?></td>
                        <td  class="text-center"><?= $row['absensi_kehadiran'] ?></td>
                        <td  class="text-center"><?= $row['nilai_tugas'] ?></td>
                        <td  class="text-center"><?= $row['nilai_uts'] ?></td>
                        <td  class="text-center"><?= $row['nilai_uas'] ?></td>
                    </tr>
                <?php $no++; endforeach ?>
            </tbody>
        </table>
    </div>
    
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>