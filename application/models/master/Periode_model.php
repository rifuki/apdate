<?php
	class Periode_model extends CI_Model{
		var $table = 'mt_periode';
	    var $column_order = array(null, 'tahun_ajaran', 'semester');
	    var $column_search = array('tahun_ajaran', 'semester'); 
	    var $order = array('id' => 'asc');

		function __construct(){
			parent::__construct();
			$this->load->database();
		}

		private function _get_datatables_query() {
			$this->db->select($this->table.".*");
				$this->db->from($this->table);
				$this->db->where('deleted_at IS NULL');
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
				$this->db->from('mt_periode');
				$this->db->where('deleted_at IS NULL');
				return $this->db->count_all_results();
		}

		public function find($id) {
			$this->db->select("A.*, B.semester");
			$this->db->from('mt_periode as A');
			$this->db->join('mt_periode_semester as B', 'B.periode_id = A.id AND B.is_active = TRUE', 'left');
			$this->db->where('A.id', $id);
			$this->db->where('A.deleted_at IS NULL');
			$query = $this->db->get();

			return $query->row();
		}

		public function find_mapel($guru_id, $only_value = FALSE) {
			$this->db->from('tref_periode_mata_pelajaran');
			$this->db->where('periode_id', $guru_id);
			$query = $this->db->get()->result_array();
			if (!empty($query) && $only_value) {
				$mapel_ids = [];
				foreach ($query as $v) {
					$mapel_ids[$v['tingkat_kelas_id']][] = $v['guru_id'].'-'.$v['mata_pelajaran_id'];
				}

				return $mapel_ids;
			}

			return $query;
		}

		public function find_mapel_guru() {
			$this->db->select("A.guru_id, A.mata_pelajaran_id, B.code as mapel_code, B.name as mapel_name, C.nip as guru_nip, C.nama as guru_nama");
			$this->db->from('tref_guru_mata_pelajaran as A');
			$this->db->join('mt_mata_pelajaran as B', 'A.mata_pelajaran_id = B.id');
			$this->db->join('mt_users_guru as C', 'A.guru_id = C.id');

			$query = $this->db->get()->result_array();
			return $query;
		}

		public function all() {
			$this->db->from($this->table);
			$this->db->where('deleted_at IS NULL');
			$query = $this->db->get();

			return $query->result_array();
		}
	}
?>