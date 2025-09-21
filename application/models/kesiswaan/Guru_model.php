<?php
	class Guru_model extends CI_Model{
		var $table = 'mt_users_guru';
	    var $column_order = array(null, 'Name', 'User Group', 'Email');
	    var $column_search = array('Name', 'User Group', 'Email'); 
	    var $order = array('id' => 'asc');

		function __construct(){
			parent::__construct();
			$this->load->database();
		}

		private function _get_datatables_query() {
			$this->db->select('mt_users_guru.id, mt_users_guru.nip, mt_users_guru.nama, mt_users_guru.email, mt_users_guru.nomor_hp, mt_periode.tahun_ajaran');
	        $this->db->from($this->table);
	        $this->db->join('mt_periode', $this->table.'.join_periode_id = mt_periode.id');
					$this->db->where('mt_users_guru.deleted_at IS NULL');
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

	    public function all() {
	        $this->_get_datatables_query();
			$result = $this->db->get()->result_array();
	    	return $result;
	    }

			public function find_active_guru($periode_id, $guru_id) {
				$query = "
					SELECT a.*
					FROM tref_kelas_jadwal_pelajaran a
					INNER JOIN tref_kelas b ON a.kelas_id = b.id
					WHERE 
						b.periode_id = $periode_id AND
						(a.guru_id = $guru_id OR b.guru_id = $guru_id)
				";

				$result = $this->db->query($query)->row();
				return $result;
			}
	}
?>