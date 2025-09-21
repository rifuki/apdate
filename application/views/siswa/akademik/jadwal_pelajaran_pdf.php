<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Pelajaran Siswa</title>
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
        <h2>JADWAL PELAJARAN</h2>
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
                <th class="text-center">No</th>
                <th>Mata Pelajaran</th>
                <th class="text-center">Guru</th>
                <th class="text-center">Total Pertemuan</th>
              </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach($list_mapel as $i => $item): ?>
                    <tr>
                        <td class="text-center"><?= $no ?></td>
                        <td class="text-center"><?= $item['mapel_code'].' - '.$item['mapel_name'] ?></td>
                        <td class="text-center"><?= $item['guru_code'].' - '.$item['guru_name'] ?></td>
                        <td class="text-center"><?= ($item['jumlah_pertemuan'] - 2) ?></td>
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