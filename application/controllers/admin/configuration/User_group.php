<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_group extends CI_Controller {
	var $menu_id = "";
	var $session_data = "";
	var $user_access_detail = "";
	var $table = "";
	public function __Construct() {
		parent::__construct();
		$this->menu_id = 4;
		$this->session_data = $this->session->userdata('user_dashboard');
		$this->user_access_detail = [];
		// $this->user_access_detail = $this->session_data['user_access_detail'];

		$this->cekLogin();
		$this->own_link = admin_url('configuration/user_group');
		$this->load->model('configuration/User_group_model');
		$this->table = "m_users_group";
	}

	public function index() {
		$user_access_detail = $this->user_access_detail;
		// $is_create 			= $user_access_detail[$this->menu_id]["is_create"];
		$is_create = 1;

		$data['judul'] 		= 'User Group';
		$data['subjudul'] 	= 'List Data';
		$data['own_link'] 	= $this->own_link;
		$data['is_create'] 	= $is_create;
		$this->template->_v('configuration/user_group/index', $data);
	}

	public function datatables() {
		$user_access_detail = $this->user_access_detail;
		$list = $this->User_group_model->get_datatables();
        $data = array();
        $no = isset($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $field) {
            $no++;
            $row_menu = array();
            $row_menu[] = $no;
            $row_menu[] = $field->name;

            $btn_update = "";
            $btn_delete = "";
            $btn_detail = "";
            if ($field->id > 1) {
            	// if ($user_access_detail[$this->menu_id]['is_update'] == 1) {
	            	$btn_update = '<a href="'.$this->own_link.'/edit/'.$field->id.'" class="btn btn-info btn-sm btn-flat mb-2 mb-sm-0"><i class="fas fa-edit"></i></a>';
	            // }
	            // if ($user_access_detail[$this->menu_id]['is_delete'] == 1) {
	            	$btn_delete = '<a onclick="deleteConfirm(`'.$field->id.'`)" data-id="'.$field->id.'" href="javascript:void(0)" class="btn btn-danger btn-sm btn-flat delete"><i class="fas fa-trash"></i></a>';
	            // }
            }
            // if ($user_access_detail[$this->menu_id]['is_detail'] == 1) {
            	$btn_detail = '<a href="'.$this->own_link.'/privilege/'.$field->id.'" class="btn btn-success btn-sm btn-flat mb-2 mb-sm-0" data-toggle="tooltip" title="Hak Akses"><i class="fas fa-list"></i></a>';
            // }
            $action = $btn_update." ".$btn_delete." ".$btn_detail;
            $row_menu[] = $action;
            $data[] = $row_menu;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->User_group_model->count_all(),
            "recordsFiltered" => $this->User_group_model->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
	}

	public function create() {
		$this->privilege('is_create');

		$data['judul'] 			= 'User Group';
		$data['subjudul'] 		= 'Create Data';
		$data['own_link'] 		= $this->own_link;

		$data['action']			= "do_create";

		$this->template->_v('configuration/user_group/form', $data);
	}

	public function edit($id) {
		$id = (int) $id;
		$this->privilege('is_update', $id);
        $model = $this->User_group_model->user_group_data($id);
        if (empty($model)) {
			$this->session->set_flashdata('error', "Data not found");
        	return redirect($this->own_link);
        }

		$data['judul'] 			= 'User Group';
		$data['subjudul'] 		= 'Edit Data';
		$data['own_link'] 		= $this->own_link;


		$data['model']			= $model;
		$data['action']			= "do_update";

		$this->template->_v('configuration/user_group/form', $data);
	}

	public function do_update() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();

			$id = (int) $post["id"];
			$this->privilege('is_update', $id);
			$post_data = [];
			foreach ($post as $key => $value) {
				$val = dbClean($value);
				$post_data[$key] = $val;
			}
			$validation 	= $this->validation($post_data);
			$htmlMessage 	= "";
			if (count($validation) > 0) {
				$htmlMessage .= "<ul>";
				foreach ($validation as $value) {
					$htmlMessage .= "<li>".$value."</li>";
				}
				$htmlMessage .= "</ul>";
				$this->session->set_flashdata('alert', $htmlMessage);
				return redirect($this->own_link."/edit/".$id);
			}
			$post_data["updated_at"] = date("Y-m-d H:i:s");
			unset($post_data["id"]);
			$save = $this->Dbhelper->updateData($this->table, array('id_grup'=>$id), $post_data);
			if ($save) {
				$this->session->set_flashdata('success', "Update data success");
				return redirect($this->own_link."/edit/".$id);
			}
			$this->session->set_flashdata('error', "Update data failed");
			return redirect($this->own_link."/edit/".$id);
		}
		$this->session->set_flashdata('error', "Access denied");
        return redirect($this->own_link);
	}

	public function do_create() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();

			$id = (int) $post["id"];
			$this->privilege('is_create');
			$post_data = [];
			foreach ($post as $key => $value) {
				$val = dbClean($value);
				$post_data[$key] = $val;
			}
			$validation 	= $this->validation($post_data);
			$htmlMessage 	= "";
			if (count($validation) > 0) {
				$htmlMessage .= "<ul>";
				foreach ($validation as $value) {
					$htmlMessage .= "<li>".$value."</li>";
				}
				$htmlMessage .= "</ul>";
				$this->session->set_flashdata('alert', $htmlMessage);
				return redirect($this->own_link."/create");
			}
			unset($post_data["id"]);
			$save = $this->Dbhelper->insertData($this->table, $post_data);
			if ($save) {
				$this->session->set_flashdata('success', "Create data success");
				return redirect($this->own_link);
			}
			$this->session->set_flashdata('error', "Create data failed");
			return redirect($this->own_link."/create");
		}
		$this->session->set_flashdata('error', "Access denied");
        return redirect($this->own_link);
	}

	public function delete($id) {
		$this->privilege('is_delete');
		$id = (int) $id;
		$model = $this->User_group_model->user_group_data($id);
        if (empty($model)) {
			$this->session->set_flashdata('error', "Data not found");
        	return redirect($this->own_link);
        }

        $deleted_at = date("Y-m-d H:i:s");
		$save = $this->Dbhelper->updateData($this->table, array('id_grup'=>$id), array("deleted_at" => $deleted_at));
		if ($save) {
			$this->session->set_flashdata('success', "Delete data success");
			return redirect($this->own_link);
		}
	}

	public function detail($id) {
		$this->privilege('is_detail');
		$id = (int) $id;
        // $model = $this->User_group_model->user_group_access($id);
		$model = [];
        $user_group_data = $this->User_group_model->user_group_data($id);
        if (empty($user_group_data)) {
			$this->session->set_flashdata('error', "Data not found");
        	return redirect($this->own_link);
        }

        // $menu_access_id = "";
        // foreach ($model as $item) {
        // 	$access_id 	= $item->menu_access_id;
        // 	$menu_id 	= $item->menu_id;

        // 	$menu_access_id .= ",".$access_id."-".$menu_id;
        // }
        $menu_builder = $this->Dbhelper->selectTabel('id, name, menu_parent_id', 'm_menu', array("deleted_at" => NULL, "menu_parent_id" => 0));
        $data['menu_builder'] = [];
        foreach ($menu_builder as $item) {
        	$id = $item['id'];
        	$menu_parent_id = $item['menu_parent_id'];
        	$name = $item['name'];
        	if ($menu_parent_id == 0) {
    			$data['menu_builder'][$id]['menu'] 		= $item;

    			$sub_menu_builder = $this->Dbhelper->selectTabel('id, name, menu_parent_id', 'm_menu', array("deleted_at" => NULL, "menu_parent_id" => $id));
    			foreach ($sub_menu_builder as $sub_menu) {
		        	$id = $sub_menu['id'];
		        	$menu_parent_id = $sub_menu['menu_parent_id'];
		        	$name = $sub_menu['name'];

		        	$data['menu_builder'][$menu_parent_id]['sub_menu'][] 	= $sub_menu;
		        }
        	}
        }
        $data['user_group_data'] 	= $user_group_data;
		$data['judul'] 			= 'User Group';
		$data['subjudul'] 		= 'Privilege Data';
		$data['own_link'] 		= $this->own_link;
		$data['model']			= $model;
		$data['action']			= "do_privilege";

		$this->template->_v('configuration/user_group/detail', $data);
	}

	public function do_detail() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$this->privilege('is_update');

			$post = $this->input->post();
			$user_group_id 	= (int) $post["user_group_id"];
			$menu_id 		= $post["menu_id"];
			$is_create 		= [];
			$is_update 		= [];
			$is_delete 		= [];
			$is_detail 		= [];

			if (array_key_exists("is_create", $post)) {
				$is_create 		= $post["is_create"];
			}
			if (array_key_exists("is_update", $post)) {
				$is_update 		= $post["is_update"];
			}
			if (array_key_exists("is_delete", $post)) {
				$is_delete 		= $post["is_delete"];
			}
			if (array_key_exists("is_detail", $post)) {
				$is_detail 		= $post["is_detail"];
			}

			$insert_data = [];
			foreach ($menu_id as $id) {
				$is_create_access = 0;
				$is_update_access = 0;
				$is_delete_access = 0;
				$is_detail_access = 0;

				if (array_key_exists($id, $is_create)) {
					$is_create_access = 1;
				}
				if (array_key_exists($id, $is_update)) {
					$is_update_access = 1;
				}
				if (array_key_exists($id, $is_delete)) {
					$is_delete_access = 1;
				}
				if (array_key_exists($id, $is_detail)) {
					$is_detail_access = 1;
				}

				$data = array(
					"user_group_id" => $user_group_id,
					"menu_id"		=> $id,
					"is_create"		=> $is_create_access,
					"is_update"		=> $is_update_access,
					"is_delete"		=> $is_delete_access,
					"is_detail"		=> $is_detail_access
				);

				$insert_data[] = $data;
			}
			// debugCode($insert_data);

			$this->db->delete('m_menu_access', array('user_group_id' => $user_group_id));

			$save = $this->db->insert_batch('m_menu_access', $insert_data);
			if ($save) {
				$this->session->set_flashdata('success', "Update data success");
				return redirect($this->own_link."/privilege/".$user_group_id);
			}
			$this->session->set_flashdata('error', "Update data failed");
			return redirect($this->own_link."/privilege/".$user_group_id);
		}
		$this->session->set_flashdata('error', "Access denied");
        return redirect($this->own_link);
	}

	// CHANGE NECESSARY POINT
	private function cekLogin() {
		$session = $this->session_data;
		if (empty($session)) {
			redirect('login_dashboard');
		}

		// $user_access = $session['user_access'];
		// if (!in_array($this->menu_id, $user_access)) {
		// 	redirect('dashboard');
		// }
	}

	private function validation($post_data) {
		$errMessage 	= [];
		$id 			= $post_data["id"];
		$name			= $post_data["name"];

		if (!empty($id)) {
			$data = $this->User_group_model->user_group_data($id);
			if (empty($data)) {
				$this->session->set_flashdata('error', "Data not found");
	        	return redirect($this->own_link);
	        }
		}

		if (empty($name)) {
			$errMessage[] = "Name is required";
		}

		return $errMessage;
	}

	private function privilege($field, $id = null) {
		// $user_access_detail = $this->user_access_detail;
		// if ($user_access_detail[$this->menu_id][$field] != 1) {
		// 	$this->session->set_flashdata('error', "Access denied");
        // 	return redirect($this->own_link);
        // }

        if (!empty($id)) {
        	if ($id < 2) {
	        	$this->session->set_flashdata('error', "Access denied");
	        	return redirect($this->own_link);
	        }
        }
	}
}
