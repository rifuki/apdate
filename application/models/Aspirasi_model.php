<?php
	class Aspirasi_model extends CI_Model{
		var $table = 'mt_users_siswa';
	    var $column_order = array(null, 'Name', 'User Group', 'Email');
	    var $column_search = array('Name', 'User Group', 'Email'); 
	    var $order = array('id' => 'asc');

		function __construct(){
			parent::__construct();
			$this->load->database();
		}

		private function _get_datatables_query() {
			$this->db->select('tref_aspirasi.id, tref_aspirasi.siswa_id, tref_aspirasi.judul, tref_aspirasi.deskripsi, tref_aspirasi.created_at, mt_users_siswa.nisn, mt_users_siswa.nama, tref_kelas.kelas');
			$this->db->from('tref_aspirasi');
			$this->db->join('mt_users_siswa', 'tref_aspirasi.siswa_id = mt_users_siswa.id');
			$this->db->join('tref_kelas', $this->table.'.current_kelas_id = tref_kelas.id', 'left');
			$this->db->where('mt_users_siswa.deleted_at IS NULL');
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
				
			if(isset($_POST['order'])) {
					$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
			} else if(isset($this->order)) {
					$order = $this->order;
					$this->db->order_by(key($order), $order[key($order)]);
			}
		}

		public function get_datatables() {
				$this->_get_datatables_query();
				if(isset($_POST['length']) && $_POST['length'] != -1) {
					$this->db->limit($_POST['length'], $_POST['start']);
				}
				$query = $this->db->get();
				return $query->result();
		}
		public function count_filtered() {
				$this->_get_datatables_query();
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

		public function user_group_access($id_user_group) {
			$this->db->select("m_users_group.name, m_menu_access.id as menu_access_id, user_group_id, menu_id, is_create, is_update, is_delete, is_detail");
			$this->db->join("m_users_group", "m_menu_access.user_group_id = m_users_group.id_grup");
			$this->db->from("m_menu_access");
			$this->db->where('user_group_id', $id_user_group);
			$query = $this->db->get();

			return $query->result();
		}

		public function find($id) {
			$this->db->from($this->table);
			$this->db->where('id', $id);
			$query = $this->db->get();

			return $query->row();
		}

		public function findSiswa($id) {
			$this->db->select('mt_users_siswa.*, mt_periode.tahun_ajaran, tref_kelas.kelas, fn_status_siswa(mt_users_siswa.id, "status") as status');
			$this->db->from($this->table);
			$this->db->join('mt_periode', $this->table.'.join_periode_id = mt_periode.id');
			$this->db->join('tref_kelas', $this->table.'.current_kelas_id = tref_kelas.id', 'left');
			$this->db->where('mt_users_siswa.id', $id);
			$query = $this->db->get();

			return $query->row_array();
		}

		public function all() {
			$this->_get_datatables_query();
			$result = $this->db->get()->result_array();
			return $result;
		}

		public function find_active_siswa($periode_id, $siswa_id) {
			$query = "
				SELECT a.*
				FROM tref_kelas_siswa a
				INNER JOIN tref_kelas b ON a.kelas_id = b.id
				WHERE 
					b.periode_id = $periode_id AND
					a.siswa_id = $siswa_id AND
					a.status = 'aktif'
			";

			$result = $this->db->query($query)->row();
			return $result;
		}

		public function get_siswa_by_search($search) {
			$this->db->select('mt_users_siswa.id, mt_users_siswa.nisn, mt_users_siswa.nama, tref_kelas.kelas');
			$this->db->from($this->table);
	    	$this->db->join('tref_kelas', $this->table.'.current_kelas_id = tref_kelas.id', 'left');
			$this->db->like($this->table.'.nama', $search);
			$this->db->like($this->table.'.nisn', $search);
			$this->db->like('tref_kelas.kelas', $search);
			$this->db->where($this->table.'.deleted_at IS NULL');
			$this->db->limit(10);
			$query = $this->db->get();

			return $query->result();
		}
	}
?>