<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	var $menu_id = "";
	var $session_data = "";
	var $user_access_detail = "";
	var $table = "";
	public function __Construct() {
		parent::__construct();
		$this->menu_id = 5;
		$this->session_data = $this->session->userdata('user_dashboard');
		// $this->user_access_detail = $this->session_data['user_access_detail'];
		$this->user_access_detail = [];

		$this->cekLogin();
		$this->own_link = admin_url('configuration/user');
		$this->load->model('configuration/User_model');
		$this->table = "m_users";
	}

	public function index() {
		$user_access_detail = $this->user_access_detail;
		// $is_create 			= $user_access_detail[$this->menu_id]["is_create"];
		$is_create = 1;

		$data['judul'] 		= 'User';
		$data['subjudul'] 	= 'List Data';
		$data['own_link'] 	= $this->own_link;
		$data['is_create'] 	= $is_create;
		$this->template->_v('configuration/user/index', $data);
	}

	public function datatables() {
		$user_access_detail = $this->user_access_detail;
		$list = $this->User_model->get_datatables();
        $data = array();
        $no = isset($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $field) {
            $no++;
            $row_menu = array();
            $row_menu[] = $no;
            $row_menu[] = $field->username;
            $row_menu[] = $field->name;
            $row_menu[] = $field->user_group;

            $btn_update = "";
            $btn_delete = "";
            if ($field->id > 1) {
            	// if ($user_access_detail[$this->menu_id]['is_update'] == 1) {
	            	$btn_update = '<a href="'.$this->own_link.'/edit/'.$field->id.'" class="btn btn-info btn-sm btn-flat mb-2 mb-sm-0"><i class="fas fa-edit"></i></a>';
	            // }
	            // if ($user_access_detail[$this->menu_id]['is_delete'] == 1) {
	            	$btn_delete = '<a onclick="deleteConfirm(`'.$field->id.'`)" data-id="'.$field->id.'" href="javascript:void(0)" class="btn btn-danger btn-sm btn-flat delete"><i class="fas fa-trash"></i></a>';
	            // }
            }
            $action = $btn_update." ".$btn_delete;
            $row_menu[] = $action;
            $data[] = $row_menu;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->User_model->count_all(),
            "recordsFiltered" => $this->User_model->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
	}

	public function create() {
		$this->privilege('is_create');

		$user_group = $this->Dbhelper->selectTabel('id_grup as id, name', 'm_users_group', array("deleted_at" => NULL, 'id_grup >' => 1));

		$data['judul'] 			= 'User';
		$data['subjudul'] 		= 'Create Data';
		$data['own_link'] 		= $this->own_link;
		$data['user_group']		= $user_group;

		$data['action']			= "do_create";

		$this->template->_v('configuration/user/form', $data);
	}

	public function edit($id) {
		$id = (int) $id;
		$this->privilege('is_update', $id);
        $model = $this->User_model->user_data($id);
        if (empty($model)) {
			$this->session->set_flashdata('error', "Data not found");
        	return redirect($this->own_link);
        }

        $user_group = $this->Dbhelper->selectTabel('id_grup as id, name', 'm_users_group', array("deleted_at" => NULL, 'id_grup >' => 1));

		$data['judul'] 			= 'User';
		$data['subjudul'] 		= 'Edit Data';
		$data['own_link'] 		= $this->own_link;
		$data['user_group']		= $user_group;


		$data['model']			= $model;
		$data['action']			= "do_update";

		$this->template->_v('configuration/user/form', $data);
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
			$save = $this->Dbhelper->updateData($this->table, array('id'=>$id), $post_data);
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
			if (empty($post_data["password"])) {
				$post_data["password"] 		= password_hash("123456", PASSWORD_DEFAULT);
			} else {
				$post_data["password"] 		= password_hash($post_data["password"], PASSWORD_DEFAULT);
			}
			// $post_data["is_verified"] 	= 1;
			// $post_data["token"] 		= $this->generate_token();
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
		$this->privilege('is_delete', $id);
		$id = (int) $id;
		$model = $this->User_model->user_data($id);
        if (empty($model)) {
			$this->session->set_flashdata('error', "Data not found");
        	return redirect($this->own_link);
        }

        $deleted_at = date("Y-m-d H:i:s");
		$save = $this->Dbhelper->updateData($this->table, array('id'=>$id), array("deleted_at" => $deleted_at));
		if ($save) {
			$this->session->set_flashdata('success', "Delete data success");
			return redirect($this->own_link);
		}
	}

	private function generate_token() {
		$token = null;
		$exist = 1;
		while ($exist == 1) {
			$generate_token = random_char();

			$check_token 	= $this->Dbhelper->selectTabel('id, name', $this->table, array("token" => $generate_token));
			if (empty($check_token)) {
				$exist = 0;
				$token = $generate_token;
			}
		}
		
		return $token;
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
		// $nip			= $post_data["nip"];
		// $phone			= $post_data["phone"];
		$user_group_id	= $post_data["user_group_id"];

		if (!empty($id)) {
			$data = $this->User_model->user_data($id);
			if (empty($data)) {
				$this->session->set_flashdata('error', "Data not found");
	        	return redirect($this->own_link);
	        }
		}

		if (empty($name)) {
			$errMessage[] = "Name is required";
		}
		// if (empty($nip)) {
		// 	$errMessage[] = "NIP is required";
		// }
		// if (empty($phone)) {
		// 	$errMessage[] = "Phone is required";
		// }
		// if (!is_numeric($phone)) {
		// 	$errMessage[] = "Phone field only contain number";
		// }
		if (!is_numeric($user_group_id)) {
			$errMessage[] = "User Group field only contain number";
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
