<?php
	class Kelas_model extends CI_Model{
		var $table = 'tref_kelas';
	    var $column_order = array(null, 'Name', 'User Group', 'Email');
	    var $column_search = array('Name', 'User Group', 'Email'); 
	    var $order = array('id' => 'asc');

		function __construct(){
			parent::__construct();
			$this->load->database();
		}

		private function _get_datatables_query($filter = []) {
			$this->db->select("tref_kelas.id, tref_kelas.kelas, COALESCE(mt_users_guru.nip, '') || ' - ' || COALESCE(mt_users_guru.nama, '') as wali_kelas");
			$this->db->from($this->table);
			$this->db->join('mt_users_guru', $this->table.'.guru_id = mt_users_guru.id');
			$i = 0;  
			foreach ($this->column_search as $item) {
					if(isset($_POST['search']['value']) && !empty($_POST['search']['value'])) {
							if($i == 0) {
									$this->db->group_start();
									$this->db->like($item, $_POST['search']['value']);
							} else {
									$this->db->or_like($item, $_POST['search']['value']);
							}

							if(count($this->column_search) - 1 == $i) {
									$this->db->group_end();
							}
					}
					$i++;
			}

			if (!empty($filter)) {
				foreach ($filter as $column => $value) {
					$this->db->where($column, $value);
				}
			}
				
			if(isset($_POST['order'])) {
					$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
			} else if(isset($this->order)) {
					$order = $this->order;
					$this->db->order_by(key($order), $order[key($order)]);
			}
		}

		public function get_datatables($filter) {
	        $this->_get_datatables_query($filter);
	        if(isset($_POST['length']) && $_POST['length'] != -1) {
		        $this->db->limit($_POST['length'], $_POST['start']);
	        }
	        $query = $this->db->get();
	        return $query->result();
	    }
	 
	    public function count_filtered($filter) {
	        $this->_get_datatables_query($filter);
	        $query = $this->db->get();
	        return $query->num_rows();
	    }
	 
	    public function count_all() {
	        $this->db->from($this->table);
	        // $this->db->where('deleted_at IS NULL');
	        return $this->db->count_all_results();
	    }

	    public function user_data($id) {
	    	$this->db->from($this->table);
	    	$this->db->where('id', $id);
			// $this->db->where('deleted_at IS NULL');
	    	$query = $this->db->get();

	    	return $query->row();
	    }

	    public function listSiswa($kelas_id) {
	    	$this->db->select("a.kelas_id, b.id as siswa_id, b.nisn, b.nama, b.tanggal_lahir");
	    	$this->db->from("tref_kelas_siswa as a");
	    	$this->db->join("mt_users_siswa as b", "a.siswa_id = b.id");
	    	$this->db->where('a.kelas_id', $kelas_id);
	    	$query = $this->db->get();

				$data = $query->result();
	    	return ['total' => count($data), 'data' => $data];
	    }

		public function find($id) {
			$this->db->from($this->table);
			$this->db->where('id', $id);
			$query = $this->db->get();

			return $query->row();
		}

		public function find_active_periode() {
			$this->db->from('mt_periode');
			// Fix: Convert boolean to string for PostgreSQL compatibility
			$this->db->where('is_active', '1');
			$query = $this->db->get();

			return $query->row();
		}

		public function find_mapel($guru_id, $only_value = FALSE) {
			$this->db->from('tref_guru_mata_pelajaran');
			$this->db->where('guru_id', $guru_id);
			$query = $this->db->get()->result_array();
			if (!empty($query) && $only_value) {
				$mapel_ids = [];
				foreach ($query as $v) {
					$mapel_ids[] = $v['mata_pelajaran_id'];
				}

				return $mapel_ids;
			}

			return $query;
		}

		public function mapel_list($kelas_id) {
			$this->db->select("A.guru_id, A.mata_pelajaran_id, B.code as mapel_code, B.name as mapel_name, C.nip as guru_nip, C.nama as guru_nama");
			$this->db->from('tref_kelas_mata_pelajaran as A');
			$this->db->join('mt_mata_pelajaran as B', 'A.mata_pelajaran_id = B.id');
			$this->db->join('mt_users_guru as C', 'A.guru_id = C.id');
			$this->db->where('kelas_id', $kelas_id);

			$query = $this->db->get()->result_array();
			return $query;
		}

		public function all() {
			$this->db->from($this->table);
			$query = $this->db->get();

			return $query->result_array();
		}

		public function listSiswaGenerate($filter, $tingkatKelas) {
			$kelasID = $filter['kelas_id'];
			$tingkatKelasID = $tingkatKelas['code'];

			$result = [
				"new"			=> [],
				"current"	=> []
			];
			if ($tingkatKelasID == 7 && !empty($kelasID)) {
				$newStudent = $this->db->select('a.id, a.nisn, a.nama, a.current_kelas_id')
					->from('mt_users_siswa as a')
					->where('a.current_kelas_id', NULL)
					->get()->result_array();
				$currentStudent = $this->db->select('a.id, a.nisn, a.nama')
					->from('mt_users_siswa as a')
					->join('tref_kelas_siswa as b', 'a.id = b.siswa_id')
					->where('a.current_kelas_id', $kelasID)
					->where('b.kelas_id', $kelasID)
					->where('b.status', enumSiswaKelas('A'))
					->order_by('b.updated_at', 'DESC')
					->get()->result_array();
				$result["new"] 			= $newStudent;
				$result["current"]	= $currentStudent;
			} elseif ($tingkatKelasID == 8 && !empty($kelasID)) {
				$newStudent = $this->db->select('a.id, a.nisn, a.nama, a.current_kelas_id')
					->from('mt_users_siswa as a')
					->join('tref_kelas_siswa as b', 'a.id = b.siswa_id', 'left')
					->where_in('b.status', [enumSiswaKelas('D'), enumSiswaKelas('E')])
					->where('a.current_kelas_id !=', NULL)
					->or_where('a.current_kelas_id', NULL, FALSE)
					->get()->result_array();
				$currentStudent = $this->db->select('a.id, a.nisn, a.nama')
					->from('mt_users_siswa as a')
					->join('tref_kelas_siswa as b', 'a.id = b.siswa_id')
					->where('a.current_kelas_id', $kelasID)
					->where('b.kelas_id', $kelasID)
					->where('b.status', enumSiswaKelas('A'))
					->order_by('b.updated_at', 'DESC')
					->get()->result_array();
				$result["new"] 			= $newStudent;
				$result["current"]	= $currentStudent;
				// dd($newStudent);
			}
			return $result;
	  }

		public function listKelasJadwalGuru($semester_id, $guru_id) {
			$query = "
				SELECT 
					a.*, 
					b.kelas, 
					c.name as nama_mapel
				FROM tref_kelas_jadwal_pelajaran AS a
				INNER JOIN tref_kelas AS b ON a.kelas_id = b.id
				INNER JOIN mt_mata_pelajaran AS c ON a.mata_pelajaran_id = c.id
				WHERE
					a.semester_id = ?
					AND a.guru_id = ?
			";
			$result = $this->db->query($query, [$semester_id, $guru_id])->result_array();
			return $result;
		}

		public function listKelasWaliKelas($semester_id, $guru_id) {
			$query = "
				SELECT 
					a.*, 
					b.kelas, 
					c.name as nama_mapel
				FROM tref_kelas_jadwal_pelajaran AS a
				INNER JOIN tref_kelas AS b ON a.kelas_id = b.id
				INNER JOIN mt_mata_pelajaran AS c ON a.mata_pelajaran_id = c.id
				WHERE
					a.semester_id = ?
					AND b.guru_id = ?
			";
			$result = $this->db->query($query, [$semester_id, $guru_id])->result_array();
			return $result;
		}

		public function listSiswaWaliKelas($periode_id, $guru_id) {
			$query = "
				SELECT 
					a.*, 
					b.kelas,
					c.nisn,
					c.nomor_induk,
					c.nama
				FROM tref_kelas_siswa AS a
				INNER JOIN tref_kelas AS b ON a.kelas_id = b.id
				INNER JOIN mt_users_siswa AS c ON a.siswa_id = c.id
				WHERE
					b.periode_id = ?
					AND b.guru_id = ?
					AND a.`status` = 'aktif'
			";
			$result = $this->db->query($query, [$periode_id, $guru_id])->result_array();
			return $result;
		}

		public function listKelasJadwalSemester($semester_id) {
			$query = "
				SELECT 
					a.*, 
					b.kelas, 
					c.name as nama_mapel
				FROM tref_kelas_jadwal_pelajaran AS a
				INNER JOIN tref_kelas AS b ON a.kelas_id = b.id
				INNER JOIN mt_mata_pelajaran AS c ON a.mata_pelajaran_id = c.id
				WHERE
					a.semester_id = ?
			";
			$result = $this->db->query($query, [$semester_id])->result_array();
			return $result;
		}

		public function listKelasJadwal($periode_id, $semester_id) {
			$query = "
				SELECT
					a.*,
					b.nip as guru_nip,
					b.nama as guru_nama, 
					(SELECT COUNT(id) FROM tref_kelas_jadwal_pelajaran b WHERE b.kelas_id = a.id AND b.semester_id = ?) as total_mapel
				FROM tref_kelas as a
				INNER JOIN mt_users_guru as b ON a.guru_id = b.id
				WHERE
					a.periode_id = ?
				ORDER BY 
					a.tingkat_kelas_id ASC,
					a.kelas ASC 
			";
			$result = $this->db->query($query, [$semester_id, $periode_id])->result_array();
			return $result;
		}

		public function listJadwal($semester_id, $kelas_id) {
			$query = "
				SELECT
					'-' as hari,
					COALESCE(a.jumlah_pertemuan, 0) as jumlah_pertemuan,
					c.code as mapel_code,
					c.name as mapel_name,
					d.nip as guru_code,
					d.nama as guru_nama
				FROM tref_kelas_jadwal_pelajaran a
				INNER JOIN mt_mata_pelajaran c ON a.mata_pelajaran_id = c.id
				INNER JOIN mt_users_guru d ON a.guru_id = d.id
				WHERE
					a.semester_id = ? AND
					a.kelas_id = ?
			";
			$result = $this->db->query($query, [$semester_id, $kelas_id])->result_array();
			return $result;
		}
	}
?>