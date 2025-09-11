<?php
	class Penilaian_model extends CI_Model{

		function __construct(){
			parent::__construct();
			$this->load->database();
		}

		public function listRaporByKelas($semester_id, $jadwal_id) {
			$query = "
				SELECT
						a.*,
						b.nisn,
						b.nama,
						(SELECT SUM(nilai) 
						FROM tr_rapor_penilaian 
						WHERE rapor_id = a.id AND jadwal_id = ?) AS total_nilai
				FROM 
						tr_rapor AS a
				INNER JOIN 
						mt_users_siswa AS b ON a.siswa_id = b.id
				WHERE
						a.semester_id = ?
						AND EXISTS (
						SELECT 1 FROM tr_rapor_penilaian 
						WHERE rapor_id = a.id AND jadwal_id = ?
					)
			";
			$result = $this->db->query($query, [$jadwal_id, $semester_id, $jadwal_id])->result_array();
			return $result;
		}

		public function listPenilaian($jadwal_kelas_id, $siswa_id) {
			$query = "
				SELECT 
					a.id, a.jadwal_kelas_id, a.pertemuan_ke, b.status_kehadiran AS absensi_kehadiran, c.id as tugas, c.nilai as nilai_tugas, '$siswa_id' as siswa_id, 
					(SELECT COUNT(id) FROM tref_pertemuan_diskusi WHERE siswa_id = '$siswa_id' AND pertemuan_id = a.id  AND valid_absensi = 1  AND deleted_at IS NULL) as absensi_diskusi
				FROM tref_pertemuan AS a
				LEFT JOIN tref_pertemuan_absensi AS b ON a.id = b.pertemuan_id AND b.siswa_id = ?
				LEFT JOIN tref_pertemuan_tugas AS c ON a.id = c.pertemuan_id AND c.siswa_id = ?
				WHERE
					a.jadwal_kelas_id = ?
			";
			$result = $this->db->query($query, [$siswa_id, $siswa_id, $jadwal_kelas_id])->result_array();
			return $result;
		}
 
		public function listPenilaianByKelas($jadwal_id, $semester_id) {
			$query = "
				SELECT
						a.*,
						b.nisn,
						b.nama,
						COALESCE(d.nilai, 0) AS nilai,
						d.is_final
				FROM 
						tref_kelas_siswa AS a
				INNER JOIN 
						mt_users_siswa AS b ON a.siswa_id = b.id
				INNER JOIN 
						tref_kelas_jadwal_pelajaran AS c ON a.kelas_id = c.kelas_id
				LEFT JOIN
						tr_rapor_penilaian AS d ON a.siswa_id = d.siswa_id AND c.id = d.jadwal_id AND d.semester_id = c.semester_id
				WHERE
						c.id = ? AND c.semester_id = ?
			";
			$result = $this->db->query($query, [$jadwal_id, $semester_id])->result_array();
			return $result;
		}

		public function listPenilaianGradingKelas($jadwal_id, $semester_id) {
			$query = "
				SELECT
						a.*,
						b.nisn,
						b.nama,
						d.nilai_akhir,
						d.grade,
						d.keterangan
				FROM 
						tref_kelas_siswa AS a
				INNER JOIN 
						mt_users_siswa AS b ON a.siswa_id = b.id
				INNER JOIN 
						tref_kelas_jadwal_pelajaran AS c ON a.kelas_id = c.kelas_id
				LEFT JOIN
						tr_egrading_siswa AS d ON a.siswa_id = d.siswa_id AND c.id = d.jadwal_kelas_id
				WHERE
						c.id = ? AND c.semester_id = ?
			";
			$result = $this->db->query($query, [$jadwal_id, $semester_id])->result_array();
			return $result;
		}

		public function listPertemuanBySiswa($siswa_id, $jadwal_id) {
			$query = "
				SELECT
						a.*,
						(SELECT id 
						FROM tref_pertemuan_absensi 
						WHERE siswa_id = ? AND pertemuan_id = a.id) AS absensi,
						(SELECT id 
						FROM tref_pertemuan_tugas 
						WHERE siswa_id = ? AND pertemuan_id = a.id) AS tugas
				FROM 
						tref_pertemuan AS a
				WHERE
						a.jadwal_kelas_id = ?
			";
			$result = $this->db->query($query, [$siswa_id, $siswa_id, $jadwal_id])->result_array();
			return $result;
		}

		public function listEGrading($kelas_id, $siswa_id) {
			$query = "
				SELECT
					a.*,
					b.jumlah_pertemuan,
					c.code AS mapel_code,
					c.name AS mapel_name,
					d.nip AS guru_code,
					d.nama AS guru_name
				FROM tr_egrading_siswa AS a
				INNER JOIN
					tref_kelas_jadwal_pelajaran AS b ON a.jadwal_kelas_id = b.id
				INNER JOIN
					mt_mata_pelajaran AS c ON b.mata_pelajaran_id = c.id
				INNER JOIN
					mt_users_guru AS d ON b.guru_id = d.id
				WHERE
					b.kelas_id = ? AND a.siswa_id = ?
			";
			$result = $this->db->query($query, [$kelas_id, $siswa_id])->result_array();
			return $result;
		}

		public function listSiswa($kelas_id, $semester_id) {
			$query = "
				SELECT
						a.*,
						b.nisn,
						b.nama
				FROM 
						tref_kelas_siswa AS a
				INNER JOIN 
						mt_users_siswa AS b ON a.siswa_id = b.id
				INNER JOIN
						tref_kelas AS c ON a.kelas_id = c.id
				WHERE
					a.kelas_id = ? AND c.periode_id = ?
			";
			$result = $this->db->query($query, [$kelas_id, $semester_id])->result_array();
			return $result;
		}

		public function checkRapor($semester_id, $siswa_id) {
			$query = "
				SELECT
					*
				FROM 
					tr_rapor
				WHERE
					semester_id = ? AND siswa_id = ?
			";
			$result = $this->db->query($query, [$semester_id, $siswa_id])->row_array();
			return $result;
		}

		public function checkRaporById($id) {
			$query = "
				SELECT
					*
				FROM 
					tr_rapor
				WHERE
					id = ?
			";
			$result = $this->db->query($query, [$id])->row_array();
			return $result;
		}

		public function checkNilai($rapor_id, $semester_id, $siswa_id, $jadwal_id) {
			$query = "
				SELECT
					*
				FROM 
					tr_rapor_penilaian
				WHERE
					rapor_id = ? AND semester_id = ? AND siswa_id = ? AND jadwal_id = ?
			";
			$result = $this->db->query($query, [$rapor_id, $semester_id, $siswa_id, $jadwal_id])->row_array();
			return $result;
		}
	}
?>