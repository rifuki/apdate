<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_builder extends CI_Controller {
	var $menu_id = "";
	var $session_data = "";
	var $user_access_detail = "";
	var $table = "";
	public function __Construct() {
		parent::__construct();
		$this->menu_id = 3;
		$this->session_data = $this->session->userdata('user_dashboard');
		// $this->user_access_detail = $this->session_data['user_access_detail'];
		
		$this->user_access_detail = [];

		$this->cekLogin();
		$this->own_link = admin_url('configuration/menu_builder');
		$this->load->model('configuration/Menu_builder_model');
		$this->table = "m_menu";
	}

	public function index() {
		// $user_access_detail = $this->user_access_detail;
		// $is_create 			= $user_access_detail[$this->menu_id]["is_create"];
		$is_create = 1;

		$data['judul'] 		= 'Menu Builder';
		$data['subjudul'] 	= 'List Data';
		$data['own_link'] 	= $this->own_link;
		$data['is_create'] 	= $is_create;
		$this->template->_v('configuration/menu_builder/index', $data);
	}

	public function datatables() {
		$user_access_detail = $this->user_access_detail;
		$list = $this->Menu_builder_model->get_datatables();
        $data = array();
        $no = isset($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $field) {
            $no++;
            $row_menu = array();
            $row_menu[] = $no;
            $row_menu[] = $field->name;

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

            $sub_menu = $this->Menu_builder_model->sub_menu_data($field->id);
            if (count($sub_menu) > 0) {
	            foreach ($sub_menu as $field_sub) {
	            	$row_sub_menu = array();
	            	$row_sub_menu[] = "";
	            	$row_sub_menu[] = $field->name." / ".$field_sub->name;

	            	$btn_update = "";
		            $btn_delete = "";
		            // if ($user_access_detail[$this->menu_id]['is_update'] == 1) {
		            	$btn_update = '<a href="'.$this->own_link.'/edit/'.$field_sub->id.'" class="btn btn-info btn-sm btn-flat mb-2 mb-sm-0"><i class="fas fa-edit"></i></a>';
		            // }
		            // if ($user_access_detail[$this->menu_id]['is_delete'] == 1) {
		            	$btn_delete = '<a data-id="'.$field_sub->id.'" href="javascript:void(0)" class="btn btn-danger btn-sm btn-flat delete"><i class="fas fa-trash"></i></a>';
		            // }
		            $action = $btn_update." ".$btn_delete;
		            $row_sub_menu[] = $action;

		            $data[] = $row_sub_menu;
	            }
            }
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Menu_builder_model->count_all(),
            "recordsFiltered" => $this->Menu_builder_model->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
	}

	public function create() {
		$this->privilege('is_create');
		$menu_builder = $this->Dbhelper->selectTabel('*', 'm_menu', array("deleted_at" => NULL, "menu_parent_id" => 0));

		$data['judul'] 			= 'Menu Builder';
		$data['subjudul'] 		= 'Create Data';
		$data['own_link'] 		= $this->own_link;

		$data['menu_builder']	= $menu_builder;
		$data['action']			= "do_create";

		$this->template->_v('configuration/menu_builder/form', $data);
	}

	public function edit($id) {
		$id = (int) $id;
		$this->privilege('is_update', $id);
        $model = $this->Menu_builder_model->menu_builder_data($id);
        if (empty($model)) {
			$this->session->set_flashdata('error', "Data not found");
        	return redirect($this->own_link);
        }
        $menu_builder = $this->Dbhelper->selectTabel('*', 'm_menu', array("id !=" => $id, "deleted_at" => NULL, "menu_parent_id" => 0));

		$data['judul'] 			= 'Menu Builder';
		$data['subjudul'] 		= 'Edit Data';
		$data['own_link'] 		= $this->own_link;


		$data['model']			= $model;
		$data['menu_builder']	= $menu_builder;
		$data['action']			= "do_update";

		$this->template->_v('configuration/menu_builder/form', $data);
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
		$model = $this->Menu_builder_model->menu_builder_data($id);
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
		$menu_parent_id = $post_data["menu_parent_id"];
		$routes 		= $post_data["routes"];
		$icon 			= $post_data["icon"];
		$is_config 		= $post_data["is_config"];

		if (!empty($id)) {
			$data = $this->Menu_builder_model->menu_builder_data($id);
			if (empty($data)) {
				$this->session->set_flashdata('error', "Data not found");
	        	return redirect($this->own_link);
	        }
		}

		if (empty($name)) {
			$errMessage[] = "Name is required";
		}
		if (!is_numeric($menu_parent_id)) {
			$errMessage[] = "Menu Parent ID is not number";
		}
		if (empty($routes) && $menu_parent_id != 0) {
			$errMessage[] = "Routes is required";
		}
		if (empty($icon)) {
			$errMessage[] = "Icon is required";
		}
		if (!is_numeric($menu_parent_id)) {
			$errMessage[] = "Config Checked is not number";
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
