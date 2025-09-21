<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapor Lengkap Siswa</title>
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
        <h2>LAPORAN</h2>
        <h2>HASIL PENCAPAIAN KOMPETENSI PESERTA DIDIK</h2>
        <h2>SEKOLAH MENENGAH PERTAMA</h2>
        <h2>(SMP)</h2>
        <br><br><br>
        <table style="width: 60%; margin: 50px auto; border: none;">
            <tr>
                <td style="border: none; width: 40%;">Nama Peserta Didik:</td>
                <td style="border: 1px solid black; padding: 10px;"><?= $siswa['nama'] ?></td>
            </tr>
            <tr>
                <td style="border: none; width: 40%;">No. Induk/NISN:</td>
                <td style="border: 1px solid black; padding: 10px;"><?= $siswa['nomor_induk'].'/'.$siswa['nisn'] ?></td>
            </tr>
        </table>
        <div style="text-align: center; margin-top: 150px;">
            <h3>KEMENTERIAN PENDIDIKAN DAN KEBUDAYAAN</h3>
            <h3>REPUBLIK INDONESIA</h3>
        </div>
    </div>

    <div class="page">
        <h2>RAPORT SISWA</h2>
        <h2>SEKOLAH MENENGAH PERTAMA</h2>
        <h2>(SMP)</h2>
        <br>
        <table class="info-table">
            <tr><td>Nama Sekolah</td><td>:</td><td>SMP NEGERI 1 RANCABUNGUR</td></tr>
            <tr><td>NPSN</td><td>:</td><td>20200659</td></tr>
            <tr><td>NIS/NSS/NDS</td><td>:</td><td>2010202343390</td></tr>
            <tr><td>Alamat Sekolah</td><td>:</td><td>Jalan Letkol Atang Senjaja, Desa Pasirgaok</td></tr>
            <tr><td></td><td></td><td>Kode Pos: 16310 Telp: (0251) 8423707</td></tr>
            <tr><td>Kelurahan</td><td>:</td><td>Pasirgaok</td></tr>
            <tr><td>Kecamatan</td><td>:</td><td>Rancabungur</td></tr>
            <tr><td>Kabupaten/Kota</td><td>:</td><td>Bogor</td></tr>
            <tr><td>Provinsi</td><td>:</td><td>Jawa Barat</td></tr>
            <tr><td>Website</td><td>:</td><td>http://smpn1rancabungur.sch.id</td></tr>
            <tr><td>E-mail</td><td>:</td><td>smpn1rancabungur@gmail.com</td></tr>
        </table>
    </div>
    
    <div class="page">
        <h3>KETERANGAN TENTANG DIRI PESERTA DIDIK</h3>
        <table class="info-table">
            <tr><td>1. Nama Peserta Didik (Lengkap)</td><td>:</td><td><?= $siswa['nama'] ?></td></tr>
            <tr><td>2. Nomor Induk / NISN</td><td>:</td><td><?= $siswa['nomor_induk'].'/'.$siswa['nisn'] ?></td></tr>
            <tr><td>3. Tempat / Tanggal Lahir</td><td>:</td><td><?= $siswa['tempat_lahir'].', '.date('d/m/Y', strtotime($siswa['tanggal_lahir'])) ?></td></tr>
            <tr><td>4. Jenis Kelamin</td><td>:</td><td>Perempuan</td></tr>
            <tr><td>5. Agama</td><td>:</td><td><?= $siswa['agama'] ?></td></tr>
            <tr><td>6. Status Dalam Keluarga</td><td>:</td><td>Anak Kandung</td></tr>
            <tr><td>7. Anak ke</td><td>:</td><td>3</td></tr>
            <tr><td>8. Alamat Peserta Didik</td><td>:</td><td><?= $siswa['alamat'] ?></td></tr>
            <tr><td>9. Nomor Telepon</td><td>:</td><td><?= $siswa['nomor_hp'] ?></td></tr>
            <tr><td>10. Sekolah Asal</td><td>:</td><td><?= $siswa['sekolah_asal'] ?></td></tr>
            <tr><td>11. Diterima di Sekolah ini</td><td></td><td></td></tr>
            <tr><td style="padding-left: 20px;">a. Tanggal</td><td>:</td><td>01 Juli 2018</td></tr>
            <tr><td style="padding-left: 20px;">b. Di kelas</td><td>:</td><td>VII</td></tr>
            <tr><td>12. Nama Orangtua</td><td></td><td></td></tr>
            <tr><td style="padding-left: 20px;">a. Nama Ayah</td><td>:</td><td><?= $ortu_ayah['nama_lengkap'] ?></td></tr>
            <tr><td style="padding-left: 20px;">b. Nama Ibu</td><td>:</td><td><?= $ortu_ibu['nama_lengkap'] ?></td></tr>
            <tr><td>13. Alamat Orangtua</td><td>:</td><td><?= $ortu_ayah['alamat'] ?></td></tr>
            <tr><td>14. Pekerjaan Orangtua</td><td></td><td></td></tr>
            <tr><td style="padding-left: 20px;">a. Pekerjaan Ayah</td><td>:</td><td><?= $ortu_ayah['pekerjaan'] ?></td></tr>
            <tr><td style="padding-left: 20px;">b. Pekerjaan Ibu</td><td>:</td><td><?= $ortu_ibu['pekerjaan'] ?></td></tr>
        </table>
        <table class="signature-table">
             <tr>
                <td style="width: 50%;"><div style="width: 90px; height: 120px; border: 1px solid black; margin: auto; text-align:center; line-height: 120px;">Pas Foto 3x4</div></td>
                <td style="width: 50%;"><p>Mengetahui,</p><p>Kepala Sekolah</p><br><br><br><br><p class="font-bold"><u>Drs. BAYU URIP SETIAWATI, MM., Pd</u></p><p>NIP. 197002121995122004</p></td>
            </tr>
        </table>
    </div>

    <div class="page">
        <div class="header-info">
            <table class="info-table" style="width: 48%;">
                <tr><td>Nama Sekolah</td><td>:</td><td>SMP NEGERI 1 RANCABUNGUR</td></tr>
                <tr><td>Alamat</td><td>:</td><td>Jalan Letkol Atang Senjaya</td></tr>
                <tr><td>Nama Peserta Didik</td><td>:</td><td><?= $siswa['nama'] ?></td></tr>
                <tr><td>Nomor Induk/NISN</td><td>:</td><td><?= $siswa['nomor_induk'].'/'.$siswa['nisn'] ?></td></tr>
            </table>
            <table class="info-table" style="width: 48%;">
                <tr><td>Kelas</td><td>:</td><td><?= $siswa['kelas'] ?></td></tr>
                <tr><td>Semester</td><td>:</td><td><?= $active_periode['semester'] ?></td></tr>
                <tr><td>Tahun Pelajaran</td><td>:</td><td><?= $active_periode['tahun_ajaran'] ?></td></tr>
            </table>
        </div>
        <?php $rapor_nilai = json_decode($rapor['json_grading_akhir']); ?>
        <h3>PENCAPAIAN KOMPETENSI PESERTA DIDIK</h3>
        <p class="sub-header">A. PENGETAHUAN</p>
        <p><strong>Kriteria Ketuntasan Minimal = 72</strong></p>
        <table class="small-text">
            <thead><tr><th class="text-center">No</th><th>Mata Pelajaran</th><th class="text-center">Nilai</th><th class="text-center">Predikat</th><th>Deskripsi</th></tr></thead>
            <tbody>
                <?php $no = 1; foreach($rapor_nilai as $i => $item): ?>
                    <tr>
                        <td class="text-center"><?= $no ?></td>
                        <td><?= mata_pelajaran(['code' => $item->mapel_code], "name")['name'] ?></td>
                        <td class="text-center"><?= $item->nilai ?></td>
                        <td class="text-center"><?= $item->grade ?></td>
                        <td><?= $item->keterangan ?></td>
                    </tr>
                <?php $no++; endforeach ?>
            </tbody>
        </table>
    </div>


    <div class="page">
         <div class="header-info"><table class="info-table" style="width: 48%;"><tr><td>Nama Peserta Didik</td><td>:</td><td><?= $siswa['nama'] ?></td></tr><tr><td>Nomor Induk/NISN</td><td>:</td><td><?= $siswa['nomor_induk'].'/'.$siswa['nisn'] ?></td></tr></table><table class="info-table" style="width: 48%;"><tr><td>Kelas</td><td>:</td><td><?= $siswa['kelas'] ?></td></tr><tr><td>Semester</td><td>:</td><td><?= $active_periode['semester'] ?></td></tr><tr><td>Tahun Pelajaran</td><td>:</td><td><?= $active_periode['tahun_ajaran'] ?></td></tr></table></div>
        <p class="sub-header">C. EKSTRAKURIKULER</p>
        <table>
            <thead><tr><th class="text-center">No</th><th>Kegiatan Ekstrakurikuler</th><th class="text-center">Predikat</th><th>Keterangan</th></tr></thead>
            <tbody>
                <!-- <tr><td class="text-center">1</td><td>PRAMUKA WAJIB</td><td class="text-center">Baik</td><td class="small-text">Terampil dalam mengenal bentuk kepramukaan, sejarah kepramukaan, pengertian gerakan Pramuka, tujuan dan fungsi. Mengetahui AD ART gerakan Pramuka serta mengetahui konsep patuh dan taat pada Dasa Dharma, Tri Satya Pramuka dan HI Satya.</td></tr> -->
            </tbody>
        </table>
        <p class="sub-header">E. KETIDAKHADIRAN</p>
        <table style="width: 50%;"><tr><td>Sakit</td><td>: 1 hari</td></tr><tr><td>Izin</td><td>: 1 hari</td></tr><tr><td>Tanpa Keterangan</td><td>: 0 hari</td></tr></table>
        <p class="sub-header">F. CATATAN WALI KELAS</p>
        <div style="border: 1px solid black; padding: 10px; min-height: 50px;"></div>
        <p class="sub-header">G. TANGGAPAN ORANG TUA/WALI</p>
        <div style="border: 1px solid black; padding: 10px; min-height: 50px;"></div>
        <table class="signature-table">
            <tr>
                <td>Mengetahui,<br>Orang Tua/Wali,<br><br><br><br><b><u><?= $ortu_ayah['nama_lengkap'] ?></u></b></td>
                <td><br><br><br><br><br><br></td>
                <td>Bogor, <?= date('d M Y') ?><br>Wali Kelas,<br><br><br><br><b><u>Sutrisni, S.Pd., MM.</u></b><br>NIP. 198105012010012003</td>
            </tr>
        </table>
    </div>



    <div class="page">
        <br><br>
        <p class="font-bold">Keputusan :</p>
        <p>Berdasarkan hasil yang dicapai pada semester 1 dan 2, Peserta didik ditetapkan</p>
        <br>
        <p style="font-size: 16px; text-align: center; border: 1px solid black; padding: 10px; width: 80%; margin: auto;"><b>NAIK KE KELAS : VIII ( DELAPAN )</b></p>
        <br><br><br>
         <table class="signature-table">
            <tr>
                <td style="width: 33%;">Mengetahui,<br>Orang Tua/Wali,<br><br><br><br><b><u>Sriyono</u></b></td>
                <td style="width: 33%;">Mengetahui,<br>Kepala Sekolah<br><br><br><br><b><u>Drs. BAYU URIP SETIAWATI, MM., Pd</u></b><br>NIP. 197002121995122004</td>
                <td style="width: 33%;">Bogor, 25 Juni 2021<br>Wali Kelas,<br><br><br><br><b><u>Sutrisni, S.Pd., M.M.</u></b><br>NIP. 198105012010012003</td>
            </tr>
        </table>
    </div>

    <!-- <div class="page">
        <h3>KETERANGAN PINDAH SEKOLAH</h3>
        <p>Nama Peserta Didik: .....................................................</p>
        <p class="sub-header">KELUAR (Diisi oleh Sekolah lama)</p>
        <table><thead><tr><th>Tanggal</th><th>Kelas yang ditinggalkan</th><th>Sebab keluar dan atas permintaan (tertulis) dari</th><th>Tanda Tangan Kepala Sekolah, Stempel Sekolah, dan Tanda Tangan Orang Tua/Wali</th></tr></thead><tbody><tr><td style="height: 120px;"></td><td></td><td></td><td></td></tr></tbody></table>
        <p class="sub-header">MASUK (Diisi oleh Sekolah baru)</p>
        <table><tbody><tr><td style="width: 40%; border-right: none;"><p>1. Nomor Induk</p><p>2. Nama Sekolah</p><p>3. Masuk di sekolah ini</p><p style="padding-left: 15px;">a. Tanggal</p><p style="padding-left: 15px;">b. Di kelas</p><p>4. Tahun Pelajaran</p></td><td style="border-left: none;">: ..............................<br>: ..............................<br><br>: ..............................<br>: ..............................<br>: ..............................</td><td style="text-align: center;"><p>......................, ..............................</p><p>Kepala Sekolah</p><br><br><br><p>...........................................</p><p>NIP. ......................................</p></td></tr></tbody></table>
    </div> -->

</body>
</html>