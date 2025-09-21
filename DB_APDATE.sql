-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for apdate
CREATE DATABASE IF NOT EXISTS `apdate` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `apdate`;

-- Dumping structure for function apdate.fn_status_siswa
DELIMITER //
CREATE FUNCTION `fn_status_siswa`(p_siswa_id INT, p_field VARCHAR(100)) RETURNS varchar(100) CHARSET utf8mb4 COLLATE utf8mb4_general_ci
    DETERMINISTIC
BEGIN
    DECLARE v_result VARCHAR(100);

    IF p_field = 'status' THEN
        SELECT status INTO v_result
        FROM tref_kelas_siswa
        WHERE siswa_id = p_siswa_id
        ORDER BY id DESC
        LIMIT 1;

    ELSEIF p_field = 'kelas_id' THEN
        SELECT kelas_id INTO v_result
        FROM tref_kelas_siswa
        WHERE siswa_id = p_siswa_id
        ORDER BY id DESC
        LIMIT 1;

    ELSE
        SELECT id INTO v_result
        FROM tref_kelas_siswa
        WHERE siswa_id = p_siswa_id
        ORDER BY id DESC
        LIMIT 1;
    END IF;

    RETURN v_result;
END//
DELIMITER ;

-- Dumping structure for table apdate.mt_dokumen
CREATE TABLE IF NOT EXISTS `mt_dokumen` (
  `id` int NOT NULL AUTO_INCREMENT,
  `judul` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `file` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table apdate.mt_dokumen: ~0 rows (approximately)
DELETE FROM `mt_dokumen`;
INSERT INTO `mt_dokumen` (`id`, `judul`, `file`, `deleted_at`) VALUES
	(1, 'CEK KARTU TANDA SISWA', 'upload/dokumen/npwp-front.png', NULL);

-- Dumping structure for table apdate.mt_grading
CREATE TABLE IF NOT EXISTS `mt_grading` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `grade` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nilai_min` int NOT NULL,
  `nilai_max` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table apdate.mt_grading: ~5 rows (approximately)
DELETE FROM `mt_grading`;
INSERT INTO `mt_grading` (`id`, `grade`, `keterangan`, `nilai_min`, `nilai_max`) VALUES
	(1, 'A', 'Siswa mampu melakukan tugasnya secara tepat dan komperensif', 81, 100),
	(2, 'B', 'Siswa mampu melakukan tugasnya secara tepat', 61, 80),
	(3, 'C', 'Siswa mampu melakukan tugasnya tetapi kurang tepat', 41, 60),
	(4, 'D', 'Siswa tidak tepat dalam melakukan tugasnya', 21, 40),
	(5, 'E', 'Siswa tidak melakukan tugasnya', 0, 20);

-- Dumping structure for table apdate.mt_grading_guru
CREATE TABLE IF NOT EXISTS `mt_grading_guru` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `guru_id` bigint NOT NULL,
  `grade` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nilai_min` int NOT NULL,
  `nilai_max` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table apdate.mt_grading_guru: ~0 rows (approximately)
DELETE FROM `mt_grading_guru`;

-- Dumping structure for table apdate.mt_informasi
CREATE TABLE IF NOT EXISTS `mt_informasi` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `judul` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_by` bigint NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` bigint NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table apdate.mt_informasi: ~0 rows (approximately)
DELETE FROM `mt_informasi`;
INSERT INTO `mt_informasi` (`id`, `tanggal`, `judul`, `deskripsi`, `created_by`, `created_at`, `updated_by`, `updated_at`, `deleted_at`) VALUES
	(1, '2025-08-27', 'PPOB 2025/2026 SEGERA DIBUKA', '<p>PPOB 2025/2026 SEGERA DIBUKA, SILAHKAN PERSIAPKAN DIRI</p>', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', NULL);

-- Dumping structure for table apdate.mt_mata_pelajaran
CREATE TABLE IF NOT EXISTS `mt_mata_pelajaran` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_active` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '1',
  `is_ekstrakulikuler` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table apdate.mt_mata_pelajaran: ~4 rows (approximately)
DELETE FROM `mt_mata_pelajaran`;
INSERT INTO `mt_mata_pelajaran` (`id`, `code`, `name`, `is_active`, `is_ekstrakulikuler`, `deleted_at`) VALUES
	(1, 'IND07', 'Bahasa Indonesia', '1', '0', NULL),
	(2, 'ENG07', 'Bahasa Inggris', '1', '0', NULL),
	(3, 'MTK07', 'Matematika', '1', '0', NULL),
	(7, 'EKS01', 'FUTSAL', '1', '1', NULL);

-- Dumping structure for table apdate.mt_periode
CREATE TABLE IF NOT EXISTS `mt_periode` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `tahun_ajaran` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `is_active` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table apdate.mt_periode: ~2 rows (approximately)
DELETE FROM `mt_periode`;
INSERT INTO `mt_periode` (`id`, `tahun_ajaran`, `is_active`, `deleted_at`) VALUES
	(1, '2025/2026', '1', NULL);

-- Dumping structure for table apdate.mt_periode_semester
CREATE TABLE IF NOT EXISTS `mt_periode_semester` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `periode_id` bigint NOT NULL,
  `semester` tinyint NOT NULL,
  `is_active` tinyint NOT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'belum-aktif,aktif,proses-nilai,tutup',
  `is_close` tinyint NOT NULL DEFAULT '0',
  `closing_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table apdate.mt_periode_semester: ~2 rows (approximately)
DELETE FROM `mt_periode_semester`;
INSERT INTO `mt_periode_semester` (`id`, `periode_id`, `semester`, `is_active`, `status`, `is_close`, `closing_at`, `deleted_at`) VALUES
	(1, 1, 1, 1, '3.1', 0, NULL, NULL),
	(2, 1, 2, 0, '', 0, NULL, NULL);

-- Dumping structure for table apdate.mt_setting_lms
CREATE TABLE IF NOT EXISTS `mt_setting_lms` (
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table apdate.mt_setting_lms: ~4 rows (approximately)
DELETE FROM `mt_setting_lms`;
INSERT INTO `mt_setting_lms` (`code`, `value`) VALUES
	('absen_diskusi', '2'),
	('absen_diskusi_total_kata', '4'),
	('filter_kalimat', '["OKE BU","SIAP BU"]'),
	('aspirasi_status', 'close');

-- Dumping structure for table apdate.mt_status_periode
CREATE TABLE IF NOT EXISTS `mt_status_periode` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table apdate.mt_status_periode: ~11 rows (approximately)
DELETE FROM `mt_status_periode`;
INSERT INTO `mt_status_periode` (`id`, `code`, `title`, `description`) VALUES
	(1, '1', 'New Semester', 'Prepare data guru, data siswa, data mata pelajaran, dan data ekstrakulikuler'),
	(2, '1.1', 'Assign Mata Pelajaran', 'Assign mata pelajaran dengan guru, lalu assign mata pelajaran ke tiap tingkat kelas (7. 8. 9) pada menu periode'),
	(3, '1.2', 'Assign Kelas', 'Create data kelas dalam semester, assign wali kelas'),
	(4, '1.3', 'Assign Siswa ke Kelas', 'Assign siswa baru / naik kelas ke masing masing kelas nya'),
	(5, '1.4', 'Generate Jadwal Kelas', 'Generate jadwal kelas, setelah siswa sudah terdaftar pada kelas nya'),
	(6, '2', 'KBM Start', 'Guru melakukan KBM, mulai dari sini ekskul bisa diisi oleh Wali Kelas terhadap siswa nya, dan Siswa dapat melakukan KBM ELearning'),
	(8, '2.1', 'KBM Finish', 'Diskusi ditutup, tugas tidak bisa dikumpulkan, GURU merekap nilai tugas / diskusi / absensi, dan membuat rekap penilaian untuk ditujukan kepada wali kelas'),
	(9, '3', 'Reporting', 'Wali Kelas SUDAH menerima seluruh nilai hasil rekapan guru dari masing2 mapel, lalu merevisi jika ada perbaikan (remedial input disini), dan mengisi nilai ekstrakulikuler'),
	(10, '3.1', 'Reporting Finish - Smt Ganjil', 'Wali kelas SUDAH selesai menyiapkan rapor, tidak ada perubahan nilai ataupun kehadiran. Data sudah fix tidak bisa dirubah'),
	(11, '3.2', 'Reporting Finish - Smt Genap', 'Wali kelas SUDAH selesai menyiapkan rapor, tidak ada perubahan nilai ataupun kehadiran. Data sudah fix tidak bisa dirubah. Di proses ini, wali kelas menentukan siswa naik kelas / lulus / tidak'),
	(12, '4', 'Close Semester', 'KBM selesai, rekap penilaian selesai, rapor sudah dibagikan, pilihan semester baru akan muncul untuk membuka semester selanjutnya');

-- Dumping structure for table apdate.mt_tingkat_kelas
CREATE TABLE IF NOT EXISTS `mt_tingkat_kelas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table apdate.mt_tingkat_kelas: ~3 rows (approximately)
DELETE FROM `mt_tingkat_kelas`;
INSERT INTO `mt_tingkat_kelas` (`id`, `code`, `name`, `deleted_at`) VALUES
	(1, '7', 'Kelas 7', NULL),
	(2, '8', 'Kelas 8', NULL),
	(3, '9', 'Kelas 9', NULL);

-- Dumping structure for table apdate.mt_users_guru
CREATE TABLE IF NOT EXISTS `mt_users_guru` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `users_id` bigint DEFAULT NULL,
  `join_periode_id` bigint NOT NULL,
  `nip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `gelar_depan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `gelar_belakang` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jenis_kelamin` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tempat_lahir` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_lahir` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `agama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nomor_hp` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `asal_universitas` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_active` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '1',
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table apdate.mt_users_guru: ~2 rows (approximately)
DELETE FROM `mt_users_guru`;
INSERT INTO `mt_users_guru` (`id`, `users_id`, `join_periode_id`, `nip`, `gelar_depan`, `nama`, `gelar_belakang`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `agama`, `email`, `nomor_hp`, `alamat`, `asal_universitas`, `is_active`, `deleted_at`) VALUES
	(1, 65, 1, '123456789', 'Drs.', 'Drs. Budi Santoso M.Pd.', 'M.Pd.', 'Laki-laki', 'Bandung', '1985-05-10', 'Islam', 'budi.santoso@email.com', '81211112222', 'Jl. Pendidikan No. 12, Bandung', NULL, '1', NULL),
	(2, 66, 1, '12345678910', '', 'Siti Aminah S.Kom.', 'S.Kom.', 'Perempuan', 'Surabaya', '1990-11-20', 'Islam', 'siti.aminah@email.com', '87733334444', 'Jl. Cendekia No. 5, Surabaya', NULL, '1', NULL);

-- Dumping structure for table apdate.mt_users_siswa
CREATE TABLE IF NOT EXISTS `mt_users_siswa` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `join_periode_id` bigint NOT NULL,
  `users_id` bigint NOT NULL,
  `current_kelas_id` bigint DEFAULT NULL,
  `nisn` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `nomor_induk` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `nama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `tempat_lahir` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `tanggal_lahir` date NOT NULL,
  `agama` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `sekolah_asal` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `nomor_hp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `status_siswa` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'baru',
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table apdate.mt_users_siswa: ~3 rows (approximately)
DELETE FROM `mt_users_siswa`;
INSERT INTO `mt_users_siswa` (`id`, `join_periode_id`, `users_id`, `current_kelas_id`, `nisn`, `nomor_induk`, `nama`, `tempat_lahir`, `tanggal_lahir`, `agama`, `sekolah_asal`, `nomor_hp`, `status_siswa`, `alamat`, `deleted_at`) VALUES
	(1, 1, 62, 1, '1220014', '25001', 'Muhammad Ichsan Fathurrochman', 'Jakarta', '1999-11-30', 'Islam', 'SMPN 1 Jakarta', '81234567890', 'aktif', 'Jl. Merdeka No. 1, Jakarta', NULL),
	(2, 1, 63, 1, '1220015', '25002', 'Annisa Putri', 'Bandung', '2000-05-15', 'Kristen', 'SMPN 2 Bandung', '87654321098', 'aktif', 'Jl. Asia Afrika No. 10, Bandung', NULL),
	(3, 1, 64, 1, '1220016', '25003', 'Made Sanjaya', 'Denpasar', '1999-08-20', 'Hindu', 'SMPN 3 Denpasar', '85566778899', 'aktif', 'Jl. Gatot Subroto No. 5, Denpasar', NULL);

-- Dumping structure for table apdate.mt_users_siswa_orangtua
CREATE TABLE IF NOT EXISTS `mt_users_siswa_orangtua` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `users_id` bigint NOT NULL DEFAULT '0',
  `nama_lengkap` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `hubungan_keluarga` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `alamat` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `nomor_hp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `email` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `pekerjaan` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table apdate.mt_users_siswa_orangtua: ~3 rows (approximately)
DELETE FROM `mt_users_siswa_orangtua`;
INSERT INTO `mt_users_siswa_orangtua` (`id`, `users_id`, `nama_lengkap`, `hubungan_keluarga`, `alamat`, `nomor_hp`, `email`, `pekerjaan`) VALUES
	(1, 62, 'Ahmad Budi', 'Ayah', 'Jl. Merdeka No. 1, J', '81122334455', 'ahmad.budi@email.com', 'Wiraswasta'),
	(2, 63, 'Maria Lestari', 'Ibu', 'Jl. Asia Afrika No. ', '87788990011', 'maria.lestari@email.', 'Pegawai Negeri'),
	(3, 64, 'I Ketut Wijaya', 'Ayah', 'Jl. Gatot Subroto No', '85511223344', 'ketut.wijaya@email.c', 'Seniman');

-- Dumping structure for table apdate.m_menu
CREATE TABLE IF NOT EXISTS `m_menu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `menu_parent_id` int NOT NULL DEFAULT '0',
  `routes` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `icon` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_config` int NOT NULL DEFAULT '0',
  `precedence` bigint NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_parent_id` (`menu_parent_id`),
  KEY `name` (`name`,`routes`,`icon`,`is_config`),
  KEY `created_at` (`created_at`,`deleted_at`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table apdate.m_menu: ~24 rows (approximately)
DELETE FROM `m_menu`;
INSERT INTO `m_menu` (`id`, `name`, `menu_parent_id`, `routes`, `icon`, `is_config`, `precedence`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Configuration', 0, NULL, 'fa-cog', 1, 0, '2020-10-30 18:19:55', '2020-10-30 18:19:55', NULL),
	(2, 'Menu Builder', 1, 'dashboard/configuration/menu_builder', 'fa-circle', 1, 1, '2020-10-30 18:19:55', '2021-08-31 09:11:10', NULL),
	(3, 'User Group', 1, 'dashboard/configuration/user_group', 'fa-circle', 1, 2, '2020-10-30 18:19:55', '2020-10-30 18:19:55', NULL),
	(4, 'Periode', 33, 'dashboard/master/periode', 'fa-circle', 0, 3, '2021-09-01 23:01:14', '2025-06-10 02:04:48', NULL),
	(5, 'User', 1, 'dashboard/configuration/user', 'fa-circle', 1, 2, '2020-10-30 18:19:55', '2020-10-30 18:19:55', NULL),
	(6, 'Mata Pelajaran', 33, 'dashboard/master/mata-pelajaran', 'fa-circle', 0, 2, '2021-09-01 23:01:14', '2025-06-10 02:04:40', NULL),
	(7, 'Data Siswa', 8, 'dashboard/kesiswaan/siswa', 'fa-circle', 0, 1, '2021-09-01 23:01:14', '2025-06-09 19:53:16', NULL),
	(8, 'Kesiswaan', 0, '', 'fa-users', 0, 2, '2021-09-01 23:01:14', '2025-06-09 19:52:57', NULL),
	(10, 'Tingkat Kelas', 33, 'dashboard/master/tingkat-kelas', 'fa-circle', 1, 1, '2024-04-08 03:03:57', '2025-06-09 19:51:34', NULL),
	(33, 'Master Data', 0, '', 'fa-list', 0, 1, '2025-06-10 02:50:36', '2025-06-10 02:50:36', NULL),
	(34, 'Data Guru', 8, 'dashboard/kesiswaan/guru', 'fa-circle', 0, 2, '2025-06-10 02:53:35', '2025-06-10 02:53:35', NULL),
	(35, 'Data Kelas', 8, 'dashboard/kesiswaan/kelas', 'fa-circle', 0, 3, '2025-06-10 02:54:26', '2025-06-10 02:54:26', NULL),
	(36, 'Generate Kelas Siswa', 0, 'dashboard/generate/kelas-siswa', 'fa-file', 0, 3, '2025-06-23 14:43:53', '2025-06-23 07:44:05', NULL),
	(37, 'Generate Jadwal Kelas', 0, 'dashboard/generate/jadwal-kelas', 'fa-file', 0, 5, '2025-06-28 14:34:00', '2025-07-29 07:50:32', NULL),
	(38, 'Grading E-Rapor', 0, 'dashboard/generate/closing-tahun-ajaran', 'fa-file', 0, 6, '2025-07-29 07:50:24', '2025-07-29 09:17:05', '2025-09-15 09:12:38'),
	(39, 'Dokumen', 33, 'dashboard/master/dokumen', 'fa-circle', 0, 6, '2025-08-12 23:27:01', '2025-08-12 23:27:01', NULL),
	(40, 'Informasi', 33, 'dashboard/master/informasi', 'fa-circle', 0, 6, '2025-08-12 23:27:01', '2025-08-12 23:27:01', NULL),
	(41, 'Setting LMS', 0, 'dashboard/setting-lms', 'fa-cog', 1, 6, '2025-08-26 16:54:53', '2025-08-26 16:54:53', NULL),
	(42, 'Setting Periode', 0, 'dashboard/setting-periode', 'fa-cog', 1, 7, '2025-09-01 22:55:12', '2025-09-01 22:55:12', NULL),
	(43, 'Backup Data', 0, 'dashboard/backup', 'fa-cog', 1, 8, '2025-09-09 16:42:00', '2025-09-09 16:42:00', NULL),
	(44, 'Kenaikan Kelas', 0, 'dashboard/kenaikan-kelas', 'fa-file', 0, 7, '2025-09-12 21:06:03', '2025-09-12 21:06:03', NULL),
	(45, 'Aspirasi', 0, 'dashboard/aspirasi', 'fa-file', 0, 8, '2025-09-15 09:05:38', '2025-09-15 09:05:38', NULL),
	(46, 'Data Alumni', 8, 'dashboard/kesiswaan/alumni', 'fa-circle', 0, 4, '2021-09-01 23:01:14', '2025-06-09 19:53:16', NULL),
	(47, 'Pantau LMS', 0, 'dashboard/pantau-lms', 'fa-book', 0, 9, '2025-09-15 18:37:48', '2025-09-15 18:37:48', NULL);

-- Dumping structure for table apdate.m_users
CREATE TABLE IF NOT EXISTS `m_users` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `user_group_id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `name` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `password` varchar(225) NOT NULL,
  `password_raw` varchar(225) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role` (`user_group_id`,`name`,`email`,`created_at`),
  KEY `deleted_at` (`deleted_at`),
  CONSTRAINT `m_users_ibfk_1` FOREIGN KEY (`user_group_id`) REFERENCES `m_users_group` (`id_grup`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=latin1;

-- Dumping data for table apdate.m_users: ~6 rows (approximately)
DELETE FROM `m_users`;
INSERT INTO `m_users` (`id`, `user_group_id`, `username`, `name`, `email`, `password`, `password_raw`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 1, 'admin', 'Administrator', 'superadmin@admin.com', '$2y$10$jcYzWD8KQVlRAx2EjDfVy.en2HrBTv3rb0yQBRhYIz8a.V2TANAnG', 'admin123', '2025-06-07 19:44:14', '2025-06-08 02:44:14', NULL),
	(62, 3, '1220014', 'Muhammad Ichsan Fathurrochman', '', '$2y$10$ejFutVtiJDOuvvUfr1BxJedpz4.OmQrYPSzWBpZX3nrGA6LeJ562q', '19991130', '2025-09-02 14:53:38', '2025-09-02 14:53:38', NULL),
	(63, 3, '1220015', 'Annisa Putri', '', '$2y$10$FHd0RUDrbNikt5Yj0QMs7.p986Qleg21g7zHBTtUq2EM2HII2zbqy', '20000515', '2025-09-02 14:53:38', '2025-09-02 14:53:38', NULL),
	(64, 3, '1220016', 'Made Sanjaya', '', '$2y$10$WkBNwXXNX.VWmN9iXBJh..38PfwE57Lcd10uGWLc0R3cwKFnyDu/u', '19990820', '2025-09-02 14:53:38', '2025-09-02 14:53:38', NULL),
	(65, 2, '123456789', 'Budi Santoso', '', '$2y$10$H6yGkWcU7Z.qDUaXJU/7eepoe.v79GtOG4TIGbDB5YhnPumY7q3By', '19850510', '2025-09-02 14:54:03', '2025-09-02 14:54:03', NULL),
	(66, 2, '12345678910', 'Siti Aminah', '', '$2y$10$pT.id9TXWd9.B3/.2Y5Xw.qUU9za/3PnX.vpCZ6VpJCKqLRVtHwF2', '19901120', '2025-09-02 14:54:03', '2025-09-02 14:54:03', NULL);

-- Dumping structure for table apdate.m_users_group
CREATE TABLE IF NOT EXISTS `m_users_group` (
  `id_grup` int NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `menu_access` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_grup`),
  KEY `name` (`name`),
  KEY `created_at` (`created_at`,`deleted_at`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table apdate.m_users_group: ~3 rows (approximately)
DELETE FROM `m_users_group`;
INSERT INTO `m_users_group` (`id_grup`, `name`, `menu_access`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Admin', '1,10,2,6,7,8,5,3', '2020-10-31 01:39:03', '2020-10-31 01:39:03', NULL),
	(2, 'Guru', '1,4,10,6,7,8', '2021-07-12 00:29:59', '2021-09-27 06:24:09', NULL),
	(3, 'Siswa', NULL, '2025-06-10 09:27:33', '2025-06-10 09:27:33', NULL);

-- Dumping structure for table apdate.tref_aspirasi
CREATE TABLE IF NOT EXISTS `tref_aspirasi` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `siswa_id` bigint NOT NULL DEFAULT '0',
  `judul` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `deskripsi` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table apdate.tref_aspirasi: ~0 rows (approximately)
DELETE FROM `tref_aspirasi`;
INSERT INTO `tref_aspirasi` (`id`, `siswa_id`, `judul`, `deskripsi`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`) VALUES
	(1, 1, 'TEST', 'TEST DESKRIPSI', '2025-09-15 09:11:02', '1', '2025-09-15 09:11:09', '1', NULL);

-- Dumping structure for table apdate.tref_guru_mata_pelajaran
CREATE TABLE IF NOT EXISTS `tref_guru_mata_pelajaran` (
  `guru_id` bigint NOT NULL,
  `mata_pelajaran_id` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table apdate.tref_guru_mata_pelajaran: ~3 rows (approximately)
DELETE FROM `tref_guru_mata_pelajaran`;
INSERT INTO `tref_guru_mata_pelajaran` (`guru_id`, `mata_pelajaran_id`) VALUES
	(1, 2),
	(1, 1),
	(2, 3);

-- Dumping structure for table apdate.tref_kelas
CREATE TABLE IF NOT EXISTS `tref_kelas` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `periode_id` bigint NOT NULL,
  `tingkat_kelas_id` bigint NOT NULL,
  `guru_id` bigint NOT NULL,
  `kelas` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table apdate.tref_kelas: ~1 rows (approximately)
DELETE FROM `tref_kelas`;
INSERT INTO `tref_kelas` (`id`, `periode_id`, `tingkat_kelas_id`, `guru_id`, `kelas`) VALUES
	(1, 1, 1, 2, '7.1');

-- Dumping structure for table apdate.tref_kelas_jadwal_pelajaran
CREATE TABLE IF NOT EXISTS `tref_kelas_jadwal_pelajaran` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `semester_id` bigint NOT NULL,
  `kelas_id` bigint NOT NULL,
  `mata_pelajaran_id` bigint NOT NULL,
  `guru_id` bigint NOT NULL,
  `jumlah_pertemuan` int DEFAULT NULL,
  `status` tinyint DEFAULT '0',
  `close_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table apdate.tref_kelas_jadwal_pelajaran: ~2 rows (approximately)
DELETE FROM `tref_kelas_jadwal_pelajaran`;
INSERT INTO `tref_kelas_jadwal_pelajaran` (`id`, `semester_id`, `kelas_id`, `mata_pelajaran_id`, `guru_id`, `jumlah_pertemuan`, `status`, `close_at`) VALUES
	(1, 1, 1, 2, 1, 5, 0, NULL),
	(2, 1, 1, 1, 1, 5, 0, NULL);

-- Dumping structure for table apdate.tref_kelas_mata_pelajaran
CREATE TABLE IF NOT EXISTS `tref_kelas_mata_pelajaran` (
  `kelas_id` bigint NOT NULL,
  `mata_pelajaran_id` bigint NOT NULL,
  `guru_id` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table apdate.tref_kelas_mata_pelajaran: ~2 rows (approximately)
DELETE FROM `tref_kelas_mata_pelajaran`;
INSERT INTO `tref_kelas_mata_pelajaran` (`kelas_id`, `mata_pelajaran_id`, `guru_id`) VALUES
	(1, 2, 1),
	(1, 1, 1);

-- Dumping structure for table apdate.tref_kelas_siswa
CREATE TABLE IF NOT EXISTS `tref_kelas_siswa` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `kelas_id` bigint DEFAULT NULL,
  `siswa_id` bigint DEFAULT NULL,
  `status` enum('aktif','nonaktif','transfer','naik_kelas','tinggal_kelas','lulus') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'aktif',
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table apdate.tref_kelas_siswa: ~3 rows (approximately)
DELETE FROM `tref_kelas_siswa`;
INSERT INTO `tref_kelas_siswa` (`id`, `kelas_id`, `siswa_id`, `status`, `updated_at`) VALUES
	(1, 1, 1, 'aktif', '2025-09-02 15:29:41'),
	(2, 1, 3, 'aktif', '2025-09-02 15:29:43'),
	(3, 1, 2, 'aktif', '2025-09-02 15:29:45');

-- Dumping structure for table apdate.tref_kelas_siswa_ekskul
CREATE TABLE IF NOT EXISTS `tref_kelas_siswa_ekskul` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `kelas_id` bigint NOT NULL DEFAULT '0',
  `siswa_id` bigint NOT NULL DEFAULT '0',
  `ekskul_list` json DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table apdate.tref_kelas_siswa_ekskul: ~0 rows (approximately)
DELETE FROM `tref_kelas_siswa_ekskul`;
INSERT INTO `tref_kelas_siswa_ekskul` (`id`, `kelas_id`, `siswa_id`, `ekskul_list`) VALUES
	(1, 1, 1, '["FUTSAL"]');

-- Dumping structure for table apdate.tref_konseling
CREATE TABLE IF NOT EXISTS `tref_konseling` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `siswa_id` bigint NOT NULL DEFAULT '0',
  `judul` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `deskripsi` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table apdate.tref_konseling: ~0 rows (approximately)
DELETE FROM `tref_konseling`;
INSERT INTO `tref_konseling` (`id`, `siswa_id`, `judul`, `deskripsi`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`) VALUES
	(1, 1, 'TEST', 'TESTINGAN AJA', '2025-09-10 02:38:59', '1', '2025-09-10 02:39:02', '1', NULL);

-- Dumping structure for table apdate.tref_periode_mata_pelajaran
CREATE TABLE IF NOT EXISTS `tref_periode_mata_pelajaran` (
  `periode_id` bigint NOT NULL,
  `tingkat_kelas_id` bigint NOT NULL,
  `mata_pelajaran_id` bigint NOT NULL,
  `guru_id` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table apdate.tref_periode_mata_pelajaran: ~3 rows (approximately)
DELETE FROM `tref_periode_mata_pelajaran`;
INSERT INTO `tref_periode_mata_pelajaran` (`periode_id`, `tingkat_kelas_id`, `mata_pelajaran_id`, `guru_id`) VALUES
	(1, 1, 2, 1),
	(1, 1, 1, 1);

-- Dumping structure for table apdate.tref_pertemuan
CREATE TABLE IF NOT EXISTS `tref_pertemuan` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `jadwal_kelas_id` bigint NOT NULL,
  `pertemuan_ke` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `status` tinyint NOT NULL DEFAULT '0',
  `close_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table apdate.tref_pertemuan: ~10 rows (approximately)
DELETE FROM `tref_pertemuan`;
INSERT INTO `tref_pertemuan` (`id`, `jadwal_kelas_id`, `pertemuan_ke`, `status`, `close_at`, `created_at`, `updated_at`) VALUES
	(1, 1, '1', 1, '2025-09-05 16:27:00', '2025-09-02 16:27:20', '2025-09-02 16:27:51'),
	(2, 1, 'UTS', 1, '2025-09-14 07:41:57', '2025-09-02 16:27:20', '2025-09-02 16:27:20'),
	(3, 1, '3', 1, '2025-09-14 07:41:57', '2025-09-02 16:27:20', '2025-09-02 16:27:20'),
	(4, 1, '4', 1, '2025-09-14 07:41:57', '2025-09-02 16:27:20', '2025-09-02 16:27:20'),
	(5, 1, 'UAS', 1, '2025-09-14 07:41:57', '2025-09-02 16:27:20', '2025-09-02 16:27:20'),
	(6, 2, '1', 1, '2025-09-14 08:01:48', '2025-09-14 08:01:22', '2025-09-14 08:01:22'),
	(7, 2, 'UTS', 1, '2025-09-14 08:01:48', '2025-09-14 08:01:22', '2025-09-14 08:01:22'),
	(8, 2, '3', 1, '2025-09-14 08:01:48', '2025-09-14 08:01:22', '2025-09-14 08:01:22'),
	(9, 2, '4', 1, '2025-09-14 08:01:48', '2025-09-14 08:01:22', '2025-09-14 08:01:22'),
	(10, 2, 'UAS', 1, '2025-09-14 08:01:48', '2025-09-14 08:01:22', '2025-09-14 08:01:22');

-- Dumping structure for table apdate.tref_pertemuan_absensi
CREATE TABLE IF NOT EXISTS `tref_pertemuan_absensi` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `pertemuan_id` bigint NOT NULL,
  `jadwal_kelas_id` bigint NOT NULL,
  `siswa_id` bigint NOT NULL,
  `status_kehadiran` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jumlah_absensi_diskusi` int DEFAULT NULL,
  `keterangan` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table apdate.tref_pertemuan_absensi: ~30 rows (approximately)
DELETE FROM `tref_pertemuan_absensi`;
INSERT INTO `tref_pertemuan_absensi` (`id`, `pertemuan_id`, `jadwal_kelas_id`, `siswa_id`, `status_kehadiran`, `jumlah_absensi_diskusi`, `keterangan`, `created_at`) VALUES
	(2, 1, 1, 1, 'hadir', NULL, '', '2025-09-02 16:29:37'),
	(4, 1, 1, 2, 'hadir', NULL, '', '2025-09-02 16:29:37'),
	(5, 2, 1, 1, 'hadir', NULL, '', '2025-09-02 16:31:40'),
	(6, 3, 1, 1, 'izin', NULL, '', '2025-09-02 16:31:40'),
	(7, 4, 1, 1, 'hadir', NULL, '', '2025-09-02 16:31:40'),
	(8, 5, 1, 1, 'hadir', NULL, '', '2025-09-02 16:31:40'),
	(9, 1, 1, 3, 'hadir', NULL, '', '2025-09-14 07:44:02'),
	(10, 2, 1, 3, 'hadir', NULL, '', '2025-09-14 07:44:02'),
	(11, 3, 1, 3, 'hadir', NULL, '', '2025-09-14 07:44:02'),
	(12, 4, 1, 3, 'hadir', NULL, '', '2025-09-14 07:44:02'),
	(13, 5, 1, 3, 'hadir', NULL, '', '2025-09-14 07:44:02'),
	(14, 2, 1, 2, 'hadir', NULL, '', '2025-09-14 07:44:55'),
	(15, 3, 1, 2, 'hadir', NULL, '', '2025-09-14 07:44:55'),
	(16, 4, 1, 2, 'hadir', NULL, '', '2025-09-14 07:44:55'),
	(17, 5, 1, 2, 'hadir', NULL, '', '2025-09-14 07:44:55'),
	(18, 6, 2, 1, 'hadir', NULL, '', '2025-09-14 08:02:27'),
	(19, 7, 2, 1, 'hadir', NULL, '', '2025-09-14 08:02:27'),
	(20, 8, 2, 1, 'hadir', NULL, '', '2025-09-14 08:02:27'),
	(21, 9, 2, 1, 'hadir', NULL, '', '2025-09-14 08:02:27'),
	(22, 10, 2, 1, 'hadir', NULL, '', '2025-09-14 08:02:27'),
	(23, 6, 2, 3, 'hadir', NULL, '', '2025-09-14 08:03:01'),
	(24, 7, 2, 3, 'hadir', NULL, '', '2025-09-14 08:03:01'),
	(25, 8, 2, 3, 'hadir', NULL, '', '2025-09-14 08:03:01'),
	(26, 9, 2, 3, 'hadir', NULL, '', '2025-09-14 08:03:01'),
	(27, 10, 2, 3, 'hadir', NULL, '', '2025-09-14 08:03:01'),
	(28, 6, 2, 2, 'hadir', NULL, '', '2025-09-14 08:04:05'),
	(29, 7, 2, 2, 'hadir', NULL, '', '2025-09-14 08:04:05'),
	(30, 8, 2, 2, 'tanpa_keterangan', NULL, '', '2025-09-14 08:04:05'),
	(31, 9, 2, 2, 'sakit', NULL, '', '2025-09-14 08:04:05'),
	(32, 10, 2, 2, 'hadir', NULL, '', '2025-09-14 08:04:05');

-- Dumping structure for table apdate.tref_pertemuan_diskusi
CREATE TABLE IF NOT EXISTS `tref_pertemuan_diskusi` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `pertemuan_id` bigint NOT NULL,
  `jadwal_kelas_id` bigint DEFAULT NULL,
  `siswa_id` bigint DEFAULT NULL,
  `guru_id` bigint DEFAULT NULL,
  `user_id` bigint NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `valid_absensi` tinyint NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by_admin` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table apdate.tref_pertemuan_diskusi: ~4 rows (approximately)
DELETE FROM `tref_pertemuan_diskusi`;
INSERT INTO `tref_pertemuan_diskusi` (`id`, `pertemuan_id`, `jadwal_kelas_id`, `siswa_id`, `guru_id`, `user_id`, `deskripsi`, `valid_absensi`, `created_at`, `updated_at`, `deleted_at`, `deleted_by_admin`) VALUES
	(7, 1, 1, NULL, 1, 65, 'TEST DISKUSI YA KAWAN', 0, '2025-09-02 16:29:00', '2025-09-02 16:29:00', NULL, NULL),
	(8, 1, 1, 1, NULL, 62, 'OKE PAK, TERIMA KASIH ATAS WAKTUNYA', 0, '2025-09-02 15:30:18', '2025-09-02 16:30:18', NULL, '2025-09-16 10:40:34'),
	(9, 1, 1, 1, NULL, 62, 'OKE PAK', 1, '2025-09-02 16:30:28', '2025-09-02 16:30:28', NULL, NULL),
	(10, 1, 1, NULL, 1, 65, '<p>OKE</p>', 0, '2025-09-02 19:44:05', '2025-09-02 19:44:05', NULL, NULL);

-- Dumping structure for table apdate.tref_pertemuan_modul
CREATE TABLE IF NOT EXISTS `tref_pertemuan_modul` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `pertemuan_id` bigint NOT NULL,
  `jadwal_kelas_id` bigint NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `file` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table apdate.tref_pertemuan_modul: ~0 rows (approximately)
DELETE FROM `tref_pertemuan_modul`;
INSERT INTO `tref_pertemuan_modul` (`id`, `pertemuan_id`, `jadwal_kelas_id`, `deskripsi`, `file`, `created_at`, `updated_at`) VALUES
	(3, 1, 1, '<p>TEST MODUL</p>', NULL, '2025-09-02 16:28:25', NULL);

-- Dumping structure for table apdate.tref_pertemuan_pranala_luar
CREATE TABLE IF NOT EXISTS `tref_pertemuan_pranala_luar` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `pertemuan_id` bigint NOT NULL,
  `jadwal_kelas_id` int DEFAULT NULL,
  `judul` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `link` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table apdate.tref_pertemuan_pranala_luar: ~0 rows (approximately)
DELETE FROM `tref_pertemuan_pranala_luar`;
INSERT INTO `tref_pertemuan_pranala_luar` (`id`, `pertemuan_id`, `jadwal_kelas_id`, `judul`, `deskripsi`, `link`, `created_at`, `updated_at`) VALUES
	(4, 1, 1, 'TEST', 'TEST CEK', 'https://www.google.com/', '2025-09-02 16:28:44', NULL);

-- Dumping structure for table apdate.tref_pertemuan_tugas
CREATE TABLE IF NOT EXISTS `tref_pertemuan_tugas` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `pertemuan_id` bigint NOT NULL,
  `jadwal_kelas_id` bigint NOT NULL,
  `siswa_id` bigint DEFAULT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `file` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `link` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `nilai` float DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table apdate.tref_pertemuan_tugas: ~30 rows (approximately)
DELETE FROM `tref_pertemuan_tugas`;
INSERT INTO `tref_pertemuan_tugas` (`id`, `pertemuan_id`, `jadwal_kelas_id`, `siswa_id`, `deskripsi`, `file`, `link`, `nilai`, `created_at`, `updated_at`) VALUES
	(2, 1, 1, 1, '<p>TEST TUGAS</p>', NULL, 'https://www.google.com/', 50, '2025-09-02 16:31:03', '2025-09-02 16:31:03'),
	(3, 2, 1, 1, '', NULL, NULL, 80, '2025-09-13 19:08:36', '2025-09-13 19:08:36'),
	(4, 3, 1, 1, '', NULL, NULL, 75, '2025-09-13 19:08:36', '2025-09-13 19:08:36'),
	(5, 4, 1, 1, '', NULL, NULL, 100, '2025-09-13 19:08:36', '2025-09-13 19:08:36'),
	(6, 5, 1, 1, '', NULL, NULL, 90, '2025-09-13 19:08:36', '2025-09-13 19:08:36'),
	(7, 1, 1, 3, '', NULL, NULL, 90, '2025-09-14 07:44:02', '2025-09-14 07:44:02'),
	(8, 2, 1, 3, '', NULL, NULL, 85, '2025-09-14 07:44:02', '2025-09-14 07:44:02'),
	(9, 3, 1, 3, '', NULL, NULL, 78, '2025-09-14 07:44:02', '2025-09-14 07:44:02'),
	(10, 4, 1, 3, '', NULL, NULL, 65, '2025-09-14 07:44:02', '2025-09-14 07:44:02'),
	(11, 5, 1, 3, '', NULL, NULL, 82, '2025-09-14 07:44:02', '2025-09-14 07:44:02'),
	(12, 1, 1, 2, '', NULL, NULL, 76, '2025-09-14 07:44:55', '2025-09-14 07:44:55'),
	(13, 2, 1, 2, '', NULL, NULL, 81, '2025-09-14 07:44:55', '2025-09-14 07:44:55'),
	(14, 3, 1, 2, '', NULL, NULL, 77, '2025-09-14 07:44:55', '2025-09-14 07:44:55'),
	(15, 4, 1, 2, '', NULL, NULL, 81, '2025-09-14 07:44:55', '2025-09-14 07:44:55'),
	(16, 5, 1, 2, '', NULL, NULL, 90, '2025-09-14 07:44:55', '2025-09-14 07:44:55'),
	(17, 6, 2, 1, '', NULL, NULL, 70, '2025-09-14 08:02:27', '2025-09-14 08:02:27'),
	(18, 7, 2, 1, '', NULL, NULL, 80, '2025-09-14 08:02:27', '2025-09-14 08:02:27'),
	(19, 8, 2, 1, '', NULL, NULL, 82, '2025-09-14 08:02:27', '2025-09-14 08:02:27'),
	(20, 9, 2, 1, '', NULL, NULL, 85, '2025-09-14 08:02:27', '2025-09-14 08:02:27'),
	(21, 10, 2, 1, '', NULL, NULL, 90, '2025-09-14 08:02:27', '2025-09-14 08:02:27'),
	(22, 6, 2, 3, '', NULL, NULL, 81, '2025-09-14 08:03:01', '2025-09-14 08:03:01'),
	(23, 7, 2, 3, '', NULL, NULL, 82, '2025-09-14 08:03:01', '2025-09-14 08:03:01'),
	(24, 8, 2, 3, '', NULL, NULL, 77, '2025-09-14 08:03:01', '2025-09-14 08:03:01'),
	(25, 9, 2, 3, '', NULL, NULL, 72, '2025-09-14 08:03:01', '2025-09-14 08:03:01'),
	(26, 10, 2, 3, '', NULL, NULL, 79, '2025-09-14 08:03:01', '2025-09-14 08:03:01'),
	(27, 6, 2, 2, '', NULL, NULL, 90, '2025-09-14 08:04:05', '2025-09-14 08:04:05'),
	(28, 7, 2, 2, '', NULL, NULL, 75, '2025-09-14 08:04:05', '2025-09-14 08:04:05'),
	(29, 8, 2, 2, '', NULL, NULL, 0, '2025-09-14 08:04:05', '2025-09-14 08:04:05'),
	(30, 9, 2, 2, '', NULL, NULL, 0, '2025-09-14 08:04:05', '2025-09-14 08:04:05'),
	(31, 10, 2, 2, '', NULL, NULL, 81, '2025-09-14 08:04:05', '2025-09-14 08:04:05');

-- Dumping structure for table apdate.tr_egrading_siswa
CREATE TABLE IF NOT EXISTS `tr_egrading_siswa` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `jadwal_kelas_id` bigint NOT NULL,
  `siswa_id` bigint NOT NULL,
  `nilai_akhir` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `grade` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `keterangan` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `status` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table apdate.tr_egrading_siswa: ~6 rows (approximately)
DELETE FROM `tr_egrading_siswa`;
INSERT INTO `tr_egrading_siswa` (`id`, `jadwal_kelas_id`, `siswa_id`, `nilai_akhir`, `grade`, `keterangan`, `status`, `created_at`, `updated_at`) VALUES
	(3, 1, 1, '85', 'A', 'Siswa mampu melakukan tugasnya secara tepat dan komperensif', 'aktif', '2025-09-14 05:31:13', '2025-09-14 05:38:48'),
	(4, 1, 3, '81', 'A', 'Siswa mampu melakukan tugasnya secara tepat dan komperensif', 'aktif', '2025-09-14 05:31:13', '2025-09-14 05:38:48'),
	(5, 1, 2, '75', 'B', 'Siswa mampu melakukan tugasnya secara tepat', 'aktif', '2025-09-14 05:31:13', '2025-09-14 05:38:48'),
	(6, 2, 1, '82', 'A', 'Siswa mampu melakukan tugasnya secara tepat dan komperensif', 'aktif', '2025-09-14 09:44:29', '2025-09-14 09:46:41'),
	(7, 2, 3, '75', 'B', 'Siswa mampu melakukan tugasnya secara tepat', 'aktif', '2025-09-14 09:44:29', '2025-09-14 09:46:41'),
	(8, 2, 2, '70', 'B', 'Siswa mampu melakukan tugasnya secara tepat', 'aktif', '2025-09-14 09:44:29', '2025-09-14 09:46:41');

-- Dumping structure for table apdate.tr_rapor
CREATE TABLE IF NOT EXISTS `tr_rapor` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `siswa_id` bigint NOT NULL,
  `kelas_id` bigint NOT NULL,
  `periode_id` bigint NOT NULL,
  `semester_id` bigint NOT NULL,
  `json_grading_akhir` json DEFAULT NULL,
  `is_close` tinyint NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table apdate.tr_rapor: ~2 rows (approximately)
DELETE FROM `tr_rapor`;
INSERT INTO `tr_rapor` (`id`, `siswa_id`, `kelas_id`, `periode_id`, `semester_id`, `json_grading_akhir`, `is_close`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 1, 1, '{"ENG07-123456789": {"grade": "A", "nilai": "85", "guru_code": "123456789", "keterangan": "Siswa mampu melakukan tugasnya secara tepat dan komperensif", "mapel_code": "ENG07"}, "IND07-123456789": {"grade": "A", "nilai": "82", "guru_code": "123456789", "keterangan": "Siswa mampu melakukan tugasnya secara tepat dan komperensif", "mapel_code": "IND07"}}', 1, '2025-09-14 19:11:52', '2025-09-14 19:13:51'),
	(2, 3, 1, 1, 1, '{"ENG07-123456789": {"grade": "A", "nilai": "81", "guru_code": "123456789", "keterangan": "Siswa mampu melakukan tugasnya secara tepat dan komperensif", "mapel_code": "ENG07"}, "IND07-123456789": {"grade": "A", "nilai": "83", "guru_code": "123456789", "keterangan": "Siswa mampu melakukan tugasnya secara tepat dan komperensif", "mapel_code": "IND07"}}', 0, '2025-09-14 19:16:42', '2025-09-14 19:16:42');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
