<?php
	class Lms_model extends CI_Model{

		function __construct(){
			parent::__construct();
			$this->load->database();
		}

		public function find_pertemuan_byid($pertemuan_id) {
			$this->db->from('tref_pertemuan');
			$this->db->where('id', $pertemuan_id);
			$query = $this->db->get();

			return $query->row_array();
		}

		public function find_pertemuan($jadwal_kelas_id, $pertemuan_ke) {
			$this->db->from('tref_pertemuan');
			$this->db->where('jadwal_kelas_id', $jadwal_kelas_id);
			$this->db->where('pertemuan_ke', $pertemuan_ke);
			$query = $this->db->get();

			return $query->row_array();
		}

		public function find_modul($pertemuan_id) {
			$this->db->from('tref_pertemuan_modul');
			$this->db->where('pertemuan_id', $pertemuan_id);
			$query = $this->db->get();

			return $query->row_array();
		}

		public function find_pranala_luar($pertemuan_id) {
			$this->db->from('tref_pertemuan_pranala_luar');
			$this->db->where('pertemuan_id', $pertemuan_id);
			$query = $this->db->get();

			return $query->result_array();
		}

		public function find_pranala_luar_byid($id) {
			$this->db->from('tref_pertemuan_pranala_luar');
			$this->db->where('id', $id);
			$query = $this->db->get();

			return $query->row_array();
		}

		public function find_diskusi($pertemuan_id) {
			$this->db->select('d.*, u.name as user_nama, u.username as user_induk');
			$this->db->from('tref_pertemuan_diskusi d');
			$this->db->join('m_users u', 'd.user_id = u.id', 'left');
			$this->db->where('d.pertemuan_id', $pertemuan_id);
			$this->db->where('d.deleted_at', NULL);
			$this->db->order_by('d.created_at', 'asc');
			$query = $this->db->get();
			return $query->result_array();
		}


		public function find_absensi($jadwal_kelas_id, $pertemuan_id) {

			$query = "
				SELECT
						a.*,
						b.nisn,
						b.nama,
						c.id as jadwal_id,
						(SELECT status_kehadiran 
						FROM tref_pertemuan_absensi 
						WHERE siswa_id = a.siswa_id AND pertemuan_id = ?) AS absensi_kehadiran,
						(SELECT COUNT(id) FROM tref_pertemuan_diskusi WHERE siswa_id = a.siswa_id AND pertemuan_id = ? AND valid_absensi = 1 AND deleted_at IS NULL) as absensi_diskusi,
						(SELECT id 
						FROM tref_pertemuan_tugas 
						WHERE siswa_id = a.siswa_id AND pertemuan_id = ?) AS tugas,
						(SELECT nilai 
						FROM tref_pertemuan_tugas 
						WHERE siswa_id = a.siswa_id AND pertemuan_id = ?) AS nilai_tugas
				FROM 
						tref_kelas_siswa AS a
				INNER JOIN 
						mt_users_siswa AS b ON a.siswa_id = b.id
				INNER JOIN 
						tref_kelas_jadwal_pelajaran AS c ON a.kelas_id = c.kelas_id
				WHERE
						c.id = ?
			";
			$result = $this->db->query($query, [ $pertemuan_id,$pertemuan_id, $pertemuan_id, $pertemuan_id, $jadwal_kelas_id])->result_array();
			return $result;
		}

		public function find_mapel_siswa($semester_id, $kelas_id, $siswa_id) {
			$query = "
				SELECT
					a.id,
					COALESCE(a.jumlah_pertemuan, 0) as jumlah_pertemuan,
					c.code as mapel_code,
					c.name as mapel_name,
					d.nip as guru_code,
					d.nama as guru_nama,
					(SELECT COUNT(id) FROM tref_pertemuan_modul WHERE jadwal_kelas_id = a.id) AS jumlah_modul,
					(SELECT COUNT(id) FROM tref_pertemuan_diskusi WHERE jadwal_kelas_id = a.id) AS jumlah_diskusi
				FROM tref_kelas_jadwal_pelajaran a
				INNER JOIN mt_mata_pelajaran c ON a.mata_pelajaran_id = c.id
				INNER JOIN mt_users_guru d ON a.guru_id = d.id
				INNER JOIN tref_kelas_siswa e ON a.kelas_id = e.kelas_id AND e.`status` = 'aktif'
				WHERE
					a.semester_id = ? AND
					a.kelas_id = ? AND
					e.siswa_id = ?
			";
			$result = $this->db->query($query, [$semester_id, $kelas_id, $siswa_id])->result_array();
			return $result;
		}

		public function find_absensi_by_siswa_id($pertemuan_id, $siswa_id) {
			$query = "
				SELECT * 
				FROM tref_pertemuan_absensi 
				WHERE pertemuan_id = ? AND siswa_id = ?
			";
			$result = $this->db->query($query, [$pertemuan_id, $siswa_id])->row_array();
			return $result;
		}

		public function find_tugas_by_siswa_id($pertemuan_id, $siswa_id) {
			$query = "
				SELECT * 
				FROM tref_pertemuan_tugas 
				WHERE pertemuan_id = ? AND siswa_id = ?
			";
			$result = $this->db->query($query, [$pertemuan_id, $siswa_id])->row_array();
			return $result;
		}

		public function find_absensi_byid($id) {
			$query = "
				SELECT * 
				FROM tref_pertemuan_absensi 
				WHERE id = ?
			";
			$result = $this->db->query($query, [$id])->row_array();
			return $result;
		}

		public function find_tugas_byid($id) {
			$query = "
				SELECT * 
				FROM tref_pertemuan_tugas 
				WHERE id = ?
			";
			$result = $this->db->query($query, [$id])->row_array();
			return $result;
		}

		public function get_aspirasi_by_siswa_id($siswa_id) {
			$this->db->from('tref_aspirasi');
			$this->db->where('siswa_id', $siswa_id);
			$this->db->order_by('created_at', 'DESC');
			$query = $this->db->get();
			return $query->result_array();
		}

		public function get_konseling_by_siswa_id($siswa_id) {
			$this->db->select('tref_konseling.*, mt_users_siswa.nisn, mt_users_siswa.nomor_induk, mt_users_siswa.nama');
			$this->db->from('tref_konseling');
			$this->db->join('mt_users_siswa', 'tref_konseling.siswa_id = mt_users_siswa.id', 'left');
			if (is_array($siswa_id)) {
				$this->db->where_in('tref_konseling.siswa_id', $siswa_id);
			} else {
				$this->db->where('tref_konseling.siswa_id', $siswa_id);
			}
			$this->db->where('tref_konseling.deleted_at', NULL);
			$this->db->order_by('tref_konseling.created_at', 'DESC');
			$query = $this->db->get();
			return $query->result_array();
		}
	}
?>