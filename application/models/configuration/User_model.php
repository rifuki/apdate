<?php
	class User_model extends CI_Model{
		var $table = 'm_users';
	    var $column_order = array(null, 'Name', 'User Group', 'Email');
	    var $column_search = array('Name', 'User Group', 'Email'); 
	    var $order = array('id' => 'asc');

		function __construct(){
			parent::__construct();
			$this->load->database();
		}

		private function _get_datatables_query() {
			$this->db->select('m_users.id, m_users.username, m_users.name, m_users_group.name as user_group, m_users.email');
	        $this->db->from($this->table);
	        $this->db->join('m_users_group', $this->table.'.user_group_id = m_users_group.id_grup');
	        $this->db->where('m_users.deleted_at IS NULL');
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
	        $this->db->where('deleted_at IS NULL');
	        return $this->db->count_all_results();
	    }

	    public function user_data($id) {
	    	$this->db->from($this->table);
	    	$this->db->where('id', $id);
			$this->db->where('deleted_at IS NULL');
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
	}
?>