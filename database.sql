-- --------------------------------------------------------
-- PostgreSQL Database Dump for APDATE Educational Management System
-- Converted from MySQL to PostgreSQL
-- --------------------------------------------------------

-- Database will be created by POSTGRES_DB environment variable
-- Connect to the database
\c apdate;

-- Create sequences for auto-increment columns
CREATE SEQUENCE mt_dokumen_id_seq;
CREATE SEQUENCE mt_grading_id_seq;
CREATE SEQUENCE mt_grading_guru_id_seq;
CREATE SEQUENCE mt_informasi_id_seq;
CREATE SEQUENCE mt_mata_pelajaran_id_seq;
CREATE SEQUENCE mt_periode_id_seq;
CREATE SEQUENCE mt_periode_semester_id_seq;
CREATE SEQUENCE mt_status_periode_id_seq;
CREATE SEQUENCE mt_tingkat_kelas_id_seq;
CREATE SEQUENCE mt_users_guru_id_seq;
CREATE SEQUENCE mt_users_siswa_id_seq;
CREATE SEQUENCE mt_users_siswa_orangtua_id_seq;
CREATE SEQUENCE m_menu_id_seq;
CREATE SEQUENCE m_users_id_seq;
CREATE SEQUENCE m_users_group_id_grup_seq;
CREATE SEQUENCE tref_aspirasi_id_seq;
CREATE SEQUENCE tref_kelas_id_seq;
CREATE SEQUENCE tref_kelas_jadwal_pelajaran_id_seq;
CREATE SEQUENCE tref_kelas_siswa_id_seq;
CREATE SEQUENCE tref_kelas_siswa_ekskul_id_seq;
CREATE SEQUENCE tref_konseling_id_seq;
CREATE SEQUENCE tref_pertemuan_id_seq;
CREATE SEQUENCE tref_pertemuan_absensi_id_seq;
CREATE SEQUENCE tref_pertemuan_diskusi_id_seq;
CREATE SEQUENCE tref_pertemuan_modul_id_seq;
CREATE SEQUENCE tref_pertemuan_pranala_luar_id_seq;
CREATE SEQUENCE tref_pertemuan_tugas_id_seq;
CREATE SEQUENCE tr_egrading_siswa_id_seq;
CREATE SEQUENCE tr_rapor_id_seq;
CREATE SEQUENCE tr_rapor_penilaian_id_seq;

-- Table structure for mt_dokumen
CREATE TABLE mt_dokumen (
  id INTEGER NOT NULL DEFAULT nextval('mt_dokumen_id_seq'),
  judul VARCHAR(100) DEFAULT NULL,
  file VARCHAR(500) DEFAULT NULL,
  deleted_at TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (id)
);
ALTER SEQUENCE mt_dokumen_id_seq OWNED BY mt_dokumen.id;

-- Data for table mt_dokumen
INSERT INTO mt_dokumen (id, judul, file, deleted_at) VALUES
	(1, 'CEK KARTU TANDA SISWA', 'upload/dokumen/npwp-front.png', NULL);

-- Update sequence to match current max ID
SELECT setval('mt_dokumen_id_seq', (SELECT COALESCE(MAX(id), 1) FROM mt_dokumen));

-- Table structure for mt_grading
CREATE TABLE mt_grading (
  id BIGSERIAL NOT NULL,
  grade VARCHAR(1) NOT NULL,
  keterangan TEXT NOT NULL,
  nilai_min INTEGER NOT NULL,
  nilai_max INTEGER NOT NULL,
  PRIMARY KEY (id)
);

-- Data for table mt_grading
INSERT INTO mt_grading (id, grade, keterangan, nilai_min, nilai_max) VALUES
	(1, 'A', 'Siswa mampu melakukan tugasnya secara tepat dan komperensif', 81, 100),
	(2, 'B', 'Siswa mampu melakukan tugasnya secara tepat', 61, 80),
	(3, 'C', 'Siswa mampu melakukan tugasnya tetapi kurang tepat', 41, 60),
	(4, 'D', 'Siswa tidak tepat dalam melakukan tugasnya', 21, 40),
	(5, 'E', 'Siswa tidak melakukan tugasnya', 0, 20);

-- Update sequence to match current max ID
SELECT setval('mt_grading_id_seq', (SELECT COALESCE(MAX(id), 1) FROM mt_grading));

-- Table structure for mt_grading_guru
CREATE TABLE mt_grading_guru (
  id BIGSERIAL NOT NULL,
  guru_id BIGINT NOT NULL,
  grade VARCHAR(1) NOT NULL,
  keterangan TEXT NOT NULL,
  nilai_min INTEGER NOT NULL,
  nilai_max INTEGER NOT NULL,
  PRIMARY KEY (id)
);

-- Data for table mt_grading_guru (empty)

-- Table structure for mt_informasi
CREATE TABLE mt_informasi (
  id BIGSERIAL NOT NULL,
  tanggal DATE NOT NULL,
  judul VARCHAR(200) NOT NULL,
  deskripsi TEXT NOT NULL,
  created_by BIGINT NOT NULL,
  created_at TIMESTAMP NOT NULL,
  updated_by BIGINT NOT NULL,
  updated_at TIMESTAMP NOT NULL,
  deleted_at TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (id)
);

-- Data for table mt_informasi
INSERT INTO mt_informasi (id, tanggal, judul, deskripsi, created_by, created_at, updated_by, updated_at, deleted_at) VALUES
	(1, '2025-08-27', 'PPOB 2025/2026 SEGERA DIBUKA', '<p>PPOB 2025/2026 SEGERA DIBUKA, SILAHKAN PERSIAPKAN DIRI</p>', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00', NULL);

-- Update sequence to match current max ID
SELECT setval('mt_informasi_id_seq', (SELECT COALESCE(MAX(id), 1) FROM mt_informasi));

-- Table structure for mt_mata_pelajaran
CREATE TABLE mt_mata_pelajaran (
  id BIGSERIAL NOT NULL,
  code VARCHAR(50) NOT NULL,
  name VARCHAR(50) NOT NULL,
  is_active VARCHAR(1) NOT NULL DEFAULT '1',
  is_ekstrakulikuler VARCHAR(1) NOT NULL DEFAULT '0',
  deleted_at TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (id)
);

-- Data for table mt_mata_pelajaran
INSERT INTO mt_mata_pelajaran (id, code, name, is_active, is_ekstrakulikuler, deleted_at) VALUES
	(1, 'IND07', 'Bahasa Indonesia', '1', '0', NULL),
	(2, 'ENG07', 'Bahasa Inggris', '1', '0', NULL),
	(3, 'MTK07', 'Matematika', '1', '0', NULL),
	(7, 'EKS01', 'FUTSAL', '1', '1', NULL);

-- Update sequence to match current max ID
SELECT setval('mt_mata_pelajaran_id_seq', (SELECT COALESCE(MAX(id), 1) FROM mt_mata_pelajaran));

-- Table structure for mt_periode
CREATE TABLE mt_periode (
  id BIGSERIAL NOT NULL,
  tahun_ajaran VARCHAR(50) NOT NULL DEFAULT '0',
  is_active VARCHAR(1) NOT NULL DEFAULT '0',
  deleted_at TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (id)
);

-- Data for table mt_periode
INSERT INTO mt_periode (id, tahun_ajaran, is_active, deleted_at) VALUES
	(1, '2025/2026', '1', NULL);

-- Update sequence to match current max ID
SELECT setval('mt_periode_id_seq', (SELECT COALESCE(MAX(id), 1) FROM mt_periode));

-- Table structure for mt_periode_semester
CREATE TABLE mt_periode_semester (
  id BIGSERIAL NOT NULL,
  periode_id BIGINT NOT NULL,
  semester SMALLINT NOT NULL,
  is_active SMALLINT NOT NULL,
  status VARCHAR(50) NOT NULL DEFAULT '',
  is_close SMALLINT NOT NULL DEFAULT 0,
  closing_at TIMESTAMP DEFAULT NULL,
  deleted_at TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (id)
);

-- Data for table mt_periode_semester
INSERT INTO mt_periode_semester (id, periode_id, semester, is_active, status, is_close, closing_at, deleted_at) VALUES
	(1, 1, 1, 1, '2.1', 0, NULL, NULL),
	(2, 1, 2, 0, '', 0, NULL, NULL);

-- Update sequence to match current max ID
SELECT setval('mt_periode_semester_id_seq', (SELECT COALESCE(MAX(id), 1) FROM mt_periode_semester));

-- Table structure for mt_setting_lms
CREATE TABLE mt_setting_lms (
  code VARCHAR(50) NOT NULL,
  value TEXT
);

-- Data for table mt_setting_lms
INSERT INTO mt_setting_lms (code, value) VALUES
	('absen_diskusi', '2'),
	('absen_diskusi_total_kata', '4'),
	('filter_kalimat', '["OKE BU","SIAP BU"]');

-- Table structure for mt_status_periode
CREATE TABLE mt_status_periode (
  id BIGSERIAL NOT NULL,
  code VARCHAR(10) NOT NULL,
  title VARCHAR(100) NOT NULL,
  description VARCHAR(200) NOT NULL,
  PRIMARY KEY (id)
);

-- Data for table mt_status_periode
INSERT INTO mt_status_periode (id, code, title, description) VALUES
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

-- Update sequence to match current max ID
SELECT setval('mt_status_periode_id_seq', (SELECT COALESCE(MAX(id), 1) FROM mt_status_periode));

-- Table structure for mt_tingkat_kelas
CREATE TABLE mt_tingkat_kelas (
  id SERIAL NOT NULL,
  code VARCHAR(2) DEFAULT NULL,
  name VARCHAR(10) DEFAULT NULL,
  deleted_at TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (id)
);

-- Data for table mt_tingkat_kelas
INSERT INTO mt_tingkat_kelas (id, code, name, deleted_at) VALUES
	(1, '7', 'Kelas 7', NULL),
	(2, '8', 'Kelas 8', NULL),
	(3, '9', 'Kelas 9', NULL);

-- Update sequence to match current max ID
SELECT setval('mt_tingkat_kelas_id_seq', (SELECT COALESCE(MAX(id), 1) FROM mt_tingkat_kelas));

-- Table structure for mt_users_guru
CREATE TABLE mt_users_guru (
  id BIGSERIAL NOT NULL,
  users_id BIGINT DEFAULT NULL,
  join_periode_id BIGINT NOT NULL,
  nip VARCHAR(50) NOT NULL,
  gelar_depan VARCHAR(50) NOT NULL,
  nama VARCHAR(50) NOT NULL,
  gelar_belakang VARCHAR(50) NOT NULL,
  jenis_kelamin VARCHAR(50) NOT NULL,
  tempat_lahir VARCHAR(50) NOT NULL,
  tanggal_lahir VARCHAR(50) NOT NULL,
  agama VARCHAR(50) NOT NULL,
  email VARCHAR(50) NOT NULL,
  nomor_hp VARCHAR(50) NOT NULL,
  alamat VARCHAR(50) NOT NULL,
  asal_universitas VARCHAR(50) DEFAULT NULL,
  is_active VARCHAR(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (id)
);

-- Data for table mt_users_guru
INSERT INTO mt_users_guru (id, users_id, join_periode_id, nip, gelar_depan, nama, gelar_belakang, jenis_kelamin, tempat_lahir, tanggal_lahir, agama, email, nomor_hp, alamat, asal_universitas, is_active) VALUES
	(1, 65, 1, '123456789', 'Drs.', 'Drs. Budi Santoso M.Pd.', 'M.Pd.', 'Laki-laki', 'Bandung', '1985-05-10', 'Islam', 'budi.santoso@email.com', '81211112222', 'Jl. Pendidikan No. 12, Bandung', NULL, '1'),
	(2, 66, 1, '12345678910', '', 'Siti Aminah S.Kom.', 'S.Kom.', 'Perempuan', 'Surabaya', '1990-11-20', 'Islam', 'siti.aminah@email.com', '87733334444', 'Jl. Cendekia No. 5, Surabaya', NULL, '1');

-- Update sequence to match current max ID
SELECT setval('mt_users_guru_id_seq', (SELECT COALESCE(MAX(id), 1) FROM mt_users_guru));

-- Table structure for mt_users_siswa
CREATE TABLE mt_users_siswa (
  id BIGSERIAL NOT NULL,
  join_periode_id BIGINT NOT NULL,
  users_id BIGINT NOT NULL,
  current_kelas_id BIGINT DEFAULT NULL,
  nisn VARCHAR(50) NOT NULL DEFAULT '',
  nomor_induk VARCHAR(50) NOT NULL DEFAULT '',
  nama VARCHAR(50) NOT NULL DEFAULT '',
  tempat_lahir VARCHAR(50) NOT NULL DEFAULT '',
  tanggal_lahir DATE NOT NULL,
  agama VARCHAR(20) NOT NULL DEFAULT '',
  sekolah_asal VARCHAR(50) NOT NULL DEFAULT '',
  nomor_hp VARCHAR(20) NOT NULL DEFAULT '',
  alamat TEXT NOT NULL,
  PRIMARY KEY (id)
);

-- Data for table mt_users_siswa
INSERT INTO mt_users_siswa (id, join_periode_id, users_id, current_kelas_id, nisn, nomor_induk, nama, tempat_lahir, tanggal_lahir, agama, sekolah_asal, nomor_hp, alamat) VALUES
	(1, 1, 62, 1, '1220014', '25001', 'Muhammad Ichsan Fathurrochman', 'Jakarta', '1999-11-30', 'Islam', 'SMPN 1 Jakarta', '81234567890', 'Jl. Merdeka No. 1, Jakarta'),
	(2, 1, 63, 1, '1220015', '25002', 'Annisa Putri', 'Bandung', '2000-05-15', 'Kristen', 'SMPN 2 Bandung', '87654321098', 'Jl. Asia Afrika No. 10, Bandung'),
	(3, 1, 64, 1, '1220016', '25003', 'Made Sanjaya', 'Denpasar', '1999-08-20', 'Hindu', 'SMPN 3 Denpasar', '85566778899', 'Jl. Gatot Subroto No. 5, Denpasar');

-- Update sequence to match current max ID
SELECT setval('mt_users_siswa_id_seq', (SELECT COALESCE(MAX(id), 1) FROM mt_users_siswa));

-- Table structure for mt_users_siswa_orangtua
CREATE TABLE mt_users_siswa_orangtua (
  id BIGSERIAL NOT NULL,
  users_id BIGINT NOT NULL DEFAULT 0,
  nama_lengkap VARCHAR(100) NOT NULL DEFAULT '',
  hubungan_keluarga VARCHAR(10) NOT NULL DEFAULT '',
  alamat VARCHAR(20) NOT NULL DEFAULT '',
  nomor_hp VARCHAR(20) NOT NULL DEFAULT '',
  email VARCHAR(20) NOT NULL DEFAULT '',
  pekerjaan VARCHAR(20) NOT NULL DEFAULT '',
  PRIMARY KEY (id)
);

-- Data for table mt_users_siswa_orangtua
INSERT INTO mt_users_siswa_orangtua (id, users_id, nama_lengkap, hubungan_keluarga, alamat, nomor_hp, email, pekerjaan) VALUES
	(1, 62, 'Ahmad Budi', 'Ayah', 'Jl. Merdeka No. 1, J', '81122334455', 'ahmad.budi@email.com', 'Wiraswasta'),
	(2, 63, 'Maria Lestari', 'Ibu', 'Jl. Asia Afrika No. ', '87788990011', 'maria.lestari@email.', 'Pegawai Negeri'),
	(3, 64, 'I Ketut Wijaya', 'Ayah', 'Jl. Gatot Subroto No', '85511223344', 'ketut.wijaya@email.c', 'Seniman');

-- Update sequence to match current max ID
SELECT setval('mt_users_siswa_orangtua_id_seq', (SELECT COALESCE(MAX(id), 1) FROM mt_users_siswa_orangtua));

-- Table structure for m_menu
CREATE TABLE m_menu (
  id SERIAL NOT NULL,
  name VARCHAR(64) NOT NULL,
  menu_parent_id INTEGER NOT NULL DEFAULT 0,
  routes VARCHAR(225) DEFAULT NULL,
  icon VARCHAR(32) NOT NULL,
  is_config INTEGER NOT NULL DEFAULT 0,
  precedence BIGINT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (id)
);

-- Create indexes for m_menu
CREATE INDEX idx_m_menu_parent_id ON m_menu(menu_parent_id);
CREATE INDEX idx_m_menu_multi ON m_menu(name, routes, icon, is_config);
CREATE INDEX idx_m_menu_created_deleted ON m_menu(created_at, deleted_at);

-- Data for table m_menu
INSERT INTO m_menu (id, name, menu_parent_id, routes, icon, is_config, precedence, created_at, updated_at, deleted_at) VALUES
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
	(38, 'Grading E-Rapor', 0, 'dashboard/generate/closing-tahun-ajaran', 'fa-file', 0, 6, '2025-07-29 07:50:24', '2025-07-29 09:17:05', NULL),
	(39, 'Dokumen', 33, 'dashboard/master/dokumen', 'fa-circle', 0, 6, '2025-08-12 23:27:01', '2025-08-12 23:27:01', NULL),
	(40, 'Informasi', 33, 'dashboard/master/informasi', 'fa-circle', 0, 6, '2025-08-12 23:27:01', '2025-08-12 23:27:01', NULL),
	(41, 'Setting LMS', 0, 'dashboard/setting-lms', 'fa-cog', 1, 6, '2025-08-26 16:54:53', '2025-08-26 16:54:53', NULL),
	(42, 'Setting Periode', 0, 'dashboard/setting-periode', 'fa-cog', 1, 7, '2025-09-01 22:55:12', '2025-09-01 22:55:12', NULL),
	(43, 'Backup Data', 0, 'dashboard/backup', 'fa-cog', 1, 8, '2025-09-09 16:42:00', '2025-09-09 16:42:00', NULL);

-- Update sequence to match current max ID
SELECT setval('m_menu_id_seq', (SELECT COALESCE(MAX(id), 1) FROM m_menu));

-- Table structure for m_users_group
CREATE TABLE m_users_group (
  id_grup SERIAL NOT NULL,
  name VARCHAR(64) NOT NULL,
  menu_access TEXT,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (id_grup)
);

-- Create indexes for m_users_group
CREATE INDEX idx_m_users_group_name ON m_users_group(name);
CREATE INDEX idx_m_users_group_created_deleted ON m_users_group(created_at, deleted_at);

-- Data for table m_users_group
INSERT INTO m_users_group (id_grup, name, menu_access, created_at, updated_at, deleted_at) VALUES
	(1, 'Admin', '1,10,2,6,7,8,5,3', '2020-10-31 01:39:03', '2020-10-31 01:39:03', NULL),
	(2, 'Guru', '1,4,10,6,7,8', '2021-07-12 00:29:59', '2021-09-27 06:24:09', NULL),
	(3, 'Siswa', NULL, '2025-06-10 09:27:33', '2025-06-10 09:27:33', NULL);

-- Update sequence to match current max ID
SELECT setval('m_users_group_id_grup_seq', (SELECT COALESCE(MAX(id_grup), 1) FROM m_users_group));

-- Table structure for m_users
CREATE TABLE m_users (
  id BIGSERIAL NOT NULL,
  user_group_id INTEGER NOT NULL,
  username VARCHAR(255) NOT NULL,
  name VARCHAR(64) NOT NULL,
  email VARCHAR(64) NOT NULL,
  password VARCHAR(225) NOT NULL,
  password_raw VARCHAR(225) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT m_users_ibfk_1 FOREIGN KEY (user_group_id) REFERENCES m_users_group (id_grup)
);

-- Create indexes for m_users
CREATE INDEX idx_m_users_role ON m_users(user_group_id, name, email, created_at);
CREATE INDEX idx_m_users_deleted_at ON m_users(deleted_at);

-- Data for table m_users
INSERT INTO m_users (id, user_group_id, username, name, email, password, password_raw, created_at, updated_at, deleted_at) VALUES
	(1, 1, 'admin', 'Administrator', 'superadmin@admin.com', '$2y$10$jcYzWD8KQVlRAx2EjDfVy.en2HrBTv3rb0yQBRhYIz8a.V2TANAnG', 'admin123', '2025-06-07 19:44:14', '2025-06-08 02:44:14', NULL),
	(62, 3, '1220014', 'Muhammad Ichsan Fathurrochman', '', '$2y$10$ejFutVtiJDOuvvUfr1BxJedpz4.OmQrYPSzWBpZX3nrGA6LeJ562q', '19991130', '2025-09-02 14:53:38', '2025-09-02 14:53:38', NULL),
	(63, 3, '1220015', 'Annisa Putri', '', '$2y$10$FHd0RUDrbNikt5Yj0QMs7.p986Qleg21g7zHBTtUq2EM2HII2zbqy', '20000515', '2025-09-02 14:53:38', '2025-09-02 14:53:38', NULL),
	(64, 3, '1220016', 'Made Sanjaya', '', '$2y$10$WkBNwXXNX.VWmN9iXBJh..38PfwE57Lcd10uGWLc0R3cwKFnyDu/u', '19990820', '2025-09-02 14:53:38', '2025-09-02 14:53:38', NULL),
	(65, 2, '123456789', 'Budi Santoso', '', '$2y$10$H6yGkWcU7Z.qDUaXJU/7eepoe.v79GtOG4TIGbDB5YhnPumY7q3By', '19850510', '2025-09-02 14:54:03', '2025-09-02 14:54:03', NULL),
	(66, 2, '12345678910', 'Siti Aminah', '', '$2y$10$pT.id9TXWd9.B3/.2Y5Xw.qUU9za/3PnX.vpCZ6VpJCKqLRVtHwF2', '19901120', '2025-09-02 14:54:03', '2025-09-02 14:54:03', NULL);

-- Update sequence to match current max ID
SELECT setval('m_users_id_seq', (SELECT COALESCE(MAX(id), 1) FROM m_users));

-- Table structure for tref_aspirasi
CREATE TABLE tref_aspirasi (
  id BIGSERIAL NOT NULL,
  siswa_id BIGINT NOT NULL DEFAULT 0,
  judul VARCHAR(250) NOT NULL DEFAULT '',
  deskripsi TEXT NOT NULL,
  created_at TIMESTAMP NOT NULL,
  created_by VARCHAR(50) NOT NULL,
  updated_at TIMESTAMP NOT NULL,
  updated_by VARCHAR(50) NOT NULL,
  deleted_at TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (id)
);

-- Data for table tref_aspirasi (empty)

-- Table structure for tref_guru_mata_pelajaran
CREATE TABLE tref_guru_mata_pelajaran (
  guru_id BIGINT NOT NULL,
  mata_pelajaran_id BIGINT NOT NULL
);

-- Data for table tref_guru_mata_pelajaran
INSERT INTO tref_guru_mata_pelajaran (guru_id, mata_pelajaran_id) VALUES
	(1, 2),
	(1, 1),
	(2, 3);

-- Table structure for tref_kelas
CREATE TABLE tref_kelas (
  id BIGSERIAL NOT NULL,
  periode_id BIGINT NOT NULL,
  tingkat_kelas_id BIGINT NOT NULL,
  guru_id BIGINT NOT NULL,
  kelas VARCHAR(50) NOT NULL,
  PRIMARY KEY (id)
);

-- Data for table tref_kelas
INSERT INTO tref_kelas (id, periode_id, tingkat_kelas_id, guru_id, kelas) VALUES
	(1, 1, 1, 2, '7.1');

-- Update sequence to match current max ID
SELECT setval('tref_kelas_id_seq', (SELECT COALESCE(MAX(id), 1) FROM tref_kelas));

-- Table structure for tref_kelas_jadwal_pelajaran
CREATE TABLE tref_kelas_jadwal_pelajaran (
  id BIGSERIAL NOT NULL,
  semester_id BIGINT NOT NULL,
  kelas_id BIGINT NOT NULL,
  mata_pelajaran_id BIGINT NOT NULL,
  guru_id BIGINT NOT NULL,
  jumlah_pertemuan INTEGER DEFAULT NULL,
  status SMALLINT DEFAULT 0,
  close_at TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (id)
);

-- Data for table tref_kelas_jadwal_pelajaran
INSERT INTO tref_kelas_jadwal_pelajaran (id, semester_id, kelas_id, mata_pelajaran_id, guru_id, jumlah_pertemuan, status, close_at) VALUES
	(1, 1, 1, 2, 1, 5, 0, NULL),
	(2, 1, 1, 1, 1, NULL, 0, NULL);

-- Update sequence to match current max ID
SELECT setval('tref_kelas_jadwal_pelajaran_id_seq', (SELECT COALESCE(MAX(id), 1) FROM tref_kelas_jadwal_pelajaran));

-- Table structure for tref_kelas_mata_pelajaran
CREATE TABLE tref_kelas_mata_pelajaran (
  kelas_id BIGINT NOT NULL,
  mata_pelajaran_id BIGINT NOT NULL,
  guru_id BIGINT NOT NULL
);

-- Data for table tref_kelas_mata_pelajaran
INSERT INTO tref_kelas_mata_pelajaran (kelas_id, mata_pelajaran_id, guru_id) VALUES
	(1, 2, 1),
	(1, 1, 1),
	(4, 3, 2);

-- Table structure for tref_kelas_siswa
CREATE TABLE tref_kelas_siswa (
  id BIGSERIAL NOT NULL,
  kelas_id BIGINT DEFAULT NULL,
  siswa_id BIGINT DEFAULT NULL,
  status VARCHAR(20) DEFAULT 'aktif' CHECK (status IN ('aktif','nonaktif','transfer','naik_kelas','tinggal_kelas','lulus')),
  updated_at TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (id)
);

-- Data for table tref_kelas_siswa
INSERT INTO tref_kelas_siswa (id, kelas_id, siswa_id, status, updated_at) VALUES
	(1, 1, 1, 'aktif', '2025-09-02 15:29:41'),
	(2, 1, 3, 'aktif', '2025-09-02 15:29:43'),
	(3, 1, 2, 'aktif', '2025-09-02 15:29:45');

-- Update sequence to match current max ID
SELECT setval('tref_kelas_siswa_id_seq', (SELECT COALESCE(MAX(id), 1) FROM tref_kelas_siswa));

-- Table structure for tref_kelas_siswa_ekskul
CREATE TABLE tref_kelas_siswa_ekskul (
  id BIGSERIAL NOT NULL,
  kelas_id BIGINT NOT NULL DEFAULT 0,
  siswa_id BIGINT NOT NULL DEFAULT 0,
  ekskul_list JSONB DEFAULT NULL,
  PRIMARY KEY (id)
);

-- Data for table tref_kelas_siswa_ekskul (empty)

-- Table structure for tref_konseling
CREATE TABLE tref_konseling (
  id BIGSERIAL NOT NULL,
  siswa_id BIGINT NOT NULL DEFAULT 0,
  judul VARCHAR(250) NOT NULL DEFAULT '',
  deskripsi TEXT NOT NULL,
  created_at TIMESTAMP NOT NULL,
  created_by VARCHAR(50) NOT NULL,
  updated_at TIMESTAMP NOT NULL,
  updated_by VARCHAR(50) NOT NULL,
  deleted_at TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (id)
);

-- Data for table tref_konseling
INSERT INTO tref_konseling (id, siswa_id, judul, deskripsi, created_at, created_by, updated_at, updated_by, deleted_at) VALUES
	(1, 1, 'TEST', 'TESTINGAN AJA', '2025-09-10 02:38:59', '1', '2025-09-10 02:39:02', '1', NULL);

-- Update sequence to match current max ID
SELECT setval('tref_konseling_id_seq', (SELECT COALESCE(MAX(id), 1) FROM tref_konseling));

-- Table structure for tref_periode_mata_pelajaran
CREATE TABLE tref_periode_mata_pelajaran (
  periode_id BIGINT NOT NULL,
  tingkat_kelas_id BIGINT NOT NULL,
  mata_pelajaran_id BIGINT NOT NULL,
  guru_id BIGINT NOT NULL
);

-- Data for table tref_periode_mata_pelajaran
INSERT INTO tref_periode_mata_pelajaran (periode_id, tingkat_kelas_id, mata_pelajaran_id, guru_id) VALUES
	(1, 1, 2, 1),
	(1, 1, 1, 1);

-- Table structure for tref_pertemuan
CREATE TABLE tref_pertemuan (
  id BIGSERIAL NOT NULL,
  jadwal_kelas_id BIGINT NOT NULL,
  pertemuan_ke VARCHAR(10) NOT NULL DEFAULT '',
  status SMALLINT NOT NULL DEFAULT 0,
  close_at TIMESTAMP DEFAULT NULL,
  created_at TIMESTAMP NOT NULL,
  updated_at TIMESTAMP NOT NULL,
  PRIMARY KEY (id)
);

-- Data for table tref_pertemuan
INSERT INTO tref_pertemuan (id, jadwal_kelas_id, pertemuan_ke, status, close_at, created_at, updated_at) VALUES
	(1, 1, '1', 1, '2025-09-05 16:27:00', '2025-09-02 16:27:20', '2025-09-02 16:27:51'),
	(2, 1, 'UTS', 0, NULL, '2025-09-02 16:27:20', '2025-09-02 16:27:20'),
	(3, 1, '3', 0, NULL, '2025-09-02 16:27:20', '2025-09-02 16:27:20'),
	(4, 1, '4', 0, NULL, '2025-09-02 16:27:20', '2025-09-02 16:27:20'),
	(5, 1, 'UAS', 0, NULL, '2025-09-02 16:27:20', '2025-09-02 16:27:20');

-- Update sequence to match current max ID
SELECT setval('tref_pertemuan_id_seq', (SELECT COALESCE(MAX(id), 1) FROM tref_pertemuan));

-- Table structure for tref_pertemuan_absensi
CREATE TABLE tref_pertemuan_absensi (
  id BIGSERIAL NOT NULL,
  pertemuan_id BIGINT NOT NULL,
  jadwal_kelas_id BIGINT NOT NULL,
  siswa_id BIGINT NOT NULL,
  status_kehadiran VARCHAR(50) NOT NULL,
  jumlah_absensi_diskusi INTEGER DEFAULT NULL,
  keterangan VARCHAR(250) NOT NULL DEFAULT '',
  created_at TIMESTAMP NOT NULL,
  PRIMARY KEY (id)
);

-- Data for table tref_pertemuan_absensi
INSERT INTO tref_pertemuan_absensi (id, pertemuan_id, jadwal_kelas_id, siswa_id, status_kehadiran, jumlah_absensi_diskusi, keterangan, created_at) VALUES
	(2, 1, 1, 1, 'hadir', NULL, '', '2025-09-02 16:29:37'),
	(3, 1, 1, 3, '', NULL, '', '2025-09-02 16:29:37'),
	(4, 1, 1, 2, 'sakit', NULL, '', '2025-09-02 16:29:37'),
	(5, 2, 1, 1, 'tanpa_keterangan', NULL, '', '2025-09-02 16:31:40'),
	(6, 3, 1, 1, 'tanpa_keterangan', NULL, '', '2025-09-02 16:31:40'),
	(7, 4, 1, 1, 'sakit', NULL, '', '2025-09-02 16:31:40'),
	(8, 5, 1, 1, 'izin', NULL, '', '2025-09-02 16:31:40');

-- Update sequence to match current max ID
SELECT setval('tref_pertemuan_absensi_id_seq', (SELECT COALESCE(MAX(id), 1) FROM tref_pertemuan_absensi));

-- Table structure for tref_pertemuan_diskusi
CREATE TABLE tref_pertemuan_diskusi (
  id BIGSERIAL NOT NULL,
  pertemuan_id BIGINT NOT NULL,
  jadwal_kelas_id BIGINT DEFAULT NULL,
  siswa_id BIGINT DEFAULT NULL,
  guru_id BIGINT DEFAULT NULL,
  user_id BIGINT NOT NULL,
  deskripsi TEXT NOT NULL,
  valid_absensi SMALLINT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL,
  updated_at TIMESTAMP NOT NULL,
  deleted_at TIMESTAMP DEFAULT NULL,
  deleted_by_admin TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (id)
);

-- Data for table tref_pertemuan_diskusi
INSERT INTO tref_pertemuan_diskusi (id, pertemuan_id, jadwal_kelas_id, siswa_id, guru_id, user_id, deskripsi, valid_absensi, created_at, updated_at, deleted_at, deleted_by_admin) VALUES
	(7, 1, 1, NULL, 1, 65, 'TEST DISKUSI YA KAWAN', 0, '2025-09-02 16:29:00', '2025-09-02 16:29:00', NULL, NULL),
	(8, 1, 1, 1, NULL, 62, 'OKE PAK, TERIMA KASIH ATAS WAKTUNYA', 1, '2025-09-02 15:30:18', '2025-09-02 16:30:18', NULL, NULL),
	(9, 1, 1, 1, NULL, 62, 'OKE PAK', 1, '2025-09-02 16:30:28', '2025-09-02 16:30:28', NULL, NULL),
	(10, 1, 1, NULL, 1, 65, '<p>OKE</p>', 0, '2025-09-02 19:44:05', '2025-09-02 19:44:05', NULL, NULL);

-- Update sequence to match current max ID
SELECT setval('tref_pertemuan_diskusi_id_seq', (SELECT COALESCE(MAX(id), 1) FROM tref_pertemuan_diskusi));

-- Table structure for tref_pertemuan_modul
CREATE TABLE tref_pertemuan_modul (
  id BIGSERIAL NOT NULL,
  pertemuan_id BIGINT NOT NULL,
  jadwal_kelas_id BIGINT NOT NULL,
  deskripsi TEXT,
  file TEXT,
  created_at TIMESTAMP NOT NULL,
  updated_at TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (id)
);

-- Data for table tref_pertemuan_modul
INSERT INTO tref_pertemuan_modul (id, pertemuan_id, jadwal_kelas_id, deskripsi, file, created_at, updated_at) VALUES
	(3, 1, 1, '<p>TEST MODUL</p>', NULL, '2025-09-02 16:28:25', NULL);

-- Update sequence to match current max ID
SELECT setval('tref_pertemuan_modul_id_seq', (SELECT COALESCE(MAX(id), 1) FROM tref_pertemuan_modul));

-- Table structure for tref_pertemuan_pranala_luar
CREATE TABLE tref_pertemuan_pranala_luar (
  id BIGSERIAL NOT NULL,
  pertemuan_id BIGINT NOT NULL,
  jadwal_kelas_id INTEGER DEFAULT NULL,
  judul VARCHAR(50) NOT NULL,
  deskripsi TEXT NOT NULL,
  link TEXT NOT NULL,
  created_at TIMESTAMP NOT NULL,
  updated_at TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (id)
);

-- Data for table tref_pertemuan_pranala_luar
INSERT INTO tref_pertemuan_pranala_luar (id, pertemuan_id, jadwal_kelas_id, judul, deskripsi, link, created_at, updated_at) VALUES
	(4, 1, 1, 'TEST', 'TEST CEK', 'https://www.google.com/', '2025-09-02 16:28:44', NULL);

-- Update sequence to match current max ID
SELECT setval('tref_pertemuan_pranala_luar_id_seq', (SELECT COALESCE(MAX(id), 1) FROM tref_pertemuan_pranala_luar));

-- Table structure for tref_pertemuan_tugas
CREATE TABLE tref_pertemuan_tugas (
  id BIGSERIAL NOT NULL,
  pertemuan_id BIGINT NOT NULL,
  jadwal_kelas_id BIGINT NOT NULL,
  siswa_id BIGINT DEFAULT NULL,
  deskripsi TEXT NOT NULL,
  file TEXT,
  link TEXT,
  nilai REAL DEFAULT NULL,
  nilai_feedback TEXT,
  nilai_at TIMESTAMP DEFAULT NULL,
  created_at TIMESTAMP NOT NULL,
  updated_at TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (id)
);

-- Data for table tref_pertemuan_tugas
INSERT INTO tref_pertemuan_tugas (id, pertemuan_id, jadwal_kelas_id, siswa_id, deskripsi, file, link, nilai, nilai_feedback, nilai_at, created_at, updated_at) VALUES
	(2, 1, 1, 1, '<p>TEST TUGAS</p>', NULL, 'https://www.google.com/', 50, NULL, NULL, '2025-09-02 16:31:03', '2025-09-02 16:31:03');

-- Update sequence to match current max ID
SELECT setval('tref_pertemuan_tugas_id_seq', (SELECT COALESCE(MAX(id), 1) FROM tref_pertemuan_tugas));

-- Table structure for tr_egrading_siswa
CREATE TABLE tr_egrading_siswa (
  id BIGSERIAL NOT NULL,
  jadwal_kelas_id BIGINT NOT NULL,
  siswa_id BIGINT NOT NULL,
  nilai_akhir VARCHAR(50) NOT NULL DEFAULT '',
  grade VARCHAR(5) NOT NULL DEFAULT '',
  keterangan VARCHAR(500) NOT NULL DEFAULT '',
  status VARCHAR(10) NOT NULL DEFAULT '',
  created_at TIMESTAMP NOT NULL,
  updated_at TIMESTAMP NOT NULL,
  PRIMARY KEY (id)
);

-- Data for table tr_egrading_siswa (empty)

-- Table structure for tr_rapor
CREATE TABLE tr_rapor (
  id BIGSERIAL NOT NULL,
  siswa_id BIGINT NOT NULL,
  semester_id BIGINT NOT NULL,
  json_grading_akhir JSONB DEFAULT NULL,
  is_close SMALLINT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL,
  updated_at TIMESTAMP NOT NULL,
  PRIMARY KEY (id)
);

-- Data for table tr_rapor (empty)

-- Table structure for tr_rapor_penilaian
CREATE TABLE tr_rapor_penilaian (
  id BIGSERIAL NOT NULL,
  rapor_id BIGINT NOT NULL,
  semester_id BIGINT NOT NULL,
  siswa_id BIGINT NOT NULL,
  jadwal_id BIGINT NOT NULL,
  nilai VARCHAR(50) NOT NULL DEFAULT '0',
  is_final SMALLINT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL,
  updated_at TIMESTAMP NOT NULL,
  PRIMARY KEY (id)
);

-- Data for table tr_rapor_penilaian (empty)

-- Add any missing foreign key constraints that are appropriate
-- Note: The original MySQL dump only had one explicit foreign key constraint for m_users
-- Other foreign key relationships exist through data relationships but were not explicitly defined

COMMENT ON DATABASE apdate IS 'APDATE Educational Management System - PostgreSQL Version';
COMMENT ON TABLE m_users IS 'Main users table for authentication';
COMMENT ON TABLE m_users_group IS 'User groups/roles for access control';
COMMENT ON TABLE mt_users_siswa IS 'Student master data';
COMMENT ON TABLE mt_users_guru IS 'Teacher master data';
COMMENT ON TABLE mt_mata_pelajaran IS 'Subject/course master data';
COMMENT ON TABLE tref_kelas IS 'Class reference data';
COMMENT ON TABLE tref_pertemuan IS 'Meeting/session data for classes';