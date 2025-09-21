<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Periode extends CI_Controller {
	var $menu_id = "";
	var $session_data = "";
	var $user_access_detail = "";
	var $table = "";
	var $judul = "";
	public function __Construct() {
		parent::__construct();
		$this->menu_id = 8;
		$this->session_data = $this->session->userdata('user_dashboard');
		// $this->user_access_detail = $this->session_data['user_access_detail'];

		$this->cekLogin();
		$this->own_link = admin_url('master/periode');
		$this->load->model('master/Periode_model');
		$this->table = "mt_periode";
		$this->judul = "Periode";
	}

	public function index() {
		$user_access_detail = $this->user_access_detail;
		// $is_create 			= $user_access_detail[$this->menu_id]["is_create"];
		$is_create = 1;

		$data['judul'] 		= $this->judul;
		$data['subjudul'] 	= 'List Data';
		$data['own_link'] 	= $this->own_link;
		$data['is_create'] 	= $is_create;
		$this->template->_v('master/periode/index', $data);
	}

	public function datatables() {
		$user_access_detail = $this->user_access_detail;
		$list = $this->Periode_model->get_datatables();
        $data = array();
        $no = isset($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $field) {
            $no++;
            $row_menu = array();
            $row_menu[] = $no;
            $row_menu[] = $field->tahun_ajaran;
            $row_menu[] = $field->is_active == 1 ? '<span class="p-2 bg-success">Aktif</span>' : '<span class="p-2 bg-secondary">Non Aktif</span>';

            $btn_update = "";
            $btn_delete = "";
        	// if ($user_access_detail[$this->menu_id]['is_update'] == 1) {
            	$btn_update = '<a href="'.$this->own_link.'/edit/'.$field->id.'" class="btn btn-info btn-sm btn-flat mb-2 mb-sm-0"><i class="fas fa-edit"></i></a>';
            // }
            // if ($user_access_detail[$this->menu_id]['is_delete'] == 1) {
            	$btn_delete = '<a onclick="deleteConfirm(`'.$field->id.'`)" data-id="'.$field->id.'" href="javascript:void(0)" class="btn btn-danger btn-sm btn-flat delete"><i class="fas fa-trash"></i></a>';
            // }
            $action = $btn_update." ".$btn_delete;
            $row_menu[] = $action;
            $data[] = $row_menu;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Periode_model->count_all(),
            "recordsFiltered" => $this->Periode_model->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
	}

	public function create() {
		$this->privilege('is_create');

		$data['judul'] 			= $this->judul;
		$data['subjudul'] 		= 'Create Data';
		$data['own_link'] 		= $this->own_link;

		$data['action']			= "do_create";

		$this->template->_v('master/periode/form', $data);
	}

	public function edit($id) {
		$id = (int) $id;
		$this->privilege('is_update');
		$model = $this->Periode_model->find($id);
		if (empty($model)) {
			$this->session->set_flashdata('error', "Data not found");
			return redirect($this->own_link);
		}

		$tingkat_kelas = $this->Dbhelper->selectTabel('id, code, name', 'mt_tingkat_kelas', array("deleted_at" => NULL), 'code', 'ASC');	
		$mata_pelajaran = $this->Periode_model->find_mapel_guru();
		$model_mapel = $this->Periode_model->find_mapel($id, true);
		$data['judul'] 			= $this->judul;
		$data['subjudul'] 		= 'Edit Data';
		$data['own_link'] 		= $this->own_link;


		$data['model']			= $model;
		$data['model_mapel']			= $model_mapel;
		$data['tingkat_kelas'] = $tingkat_kelas;
		$data['mata_pelajaran'] = $mata_pelajaran;
		$data['action']			= "do_update";

		// dd($data);
		$this->template->_v('master/periode/form', $data);
	}

	public function do_update() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();

			$id = (int) $post["id"];
			$this->privilege('is_update');
			$post_data = [];
			foreach ($post as $key => $value) {
				if (is_array($value)) {
					continue;
				}
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

			$active_periode = active_periode();
			if ($active_periode['status'] != "1.1") {
				$this->session->set_flashdata('error', "Gagal update karena periode saat ini belum dalam status Assign Mata Pelajaran (1.1)");
				return redirect($this->own_link."/edit/".$id);
			}

			// $post_data["updated_at"] = date("Y-m-d H:i:s");
			unset($post_data["id"]);
			if ($post_data['is_active'] == 1) {
				$update_all = [
					"is_active" => 0
				];
				$save = $this->Dbhelper->updateData($this->table, array(), $update_all);
			}


			$insert_batch = [];
			if (!empty($post['mata_pelajaran_ids'])) {
				foreach ($post['mata_pelajaran_ids'] as $tk_id => $mapel_id) {
					foreach ($mapel_id as $v) {
						$v = explode('-', $v);
						$insert_batch[] = [
							'periode_id'	=> $id,
							'tingkat_kelas_id'	=> $tk_id,
							'guru_id'	=> $v[0],
							'mata_pelajaran_id'		=> $v[1]
						];
					}
				}
				
			}
			// dd($insert_batch);
			$this->db->delete('tref_periode_mata_pelajaran', array('periode_id' => $id));
			if (!empty($insert_batch)) {
				$save = $this->db->insert_batch('tref_periode_mata_pelajaran', $insert_batch);	
			}

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
			$post_data["semester"] = 1;
			$insert_periode = $this->Dbhelper->insertDataWithReturnID($this->table, [
				"tahun_ajaran" => $post_data["tahun_ajaran"],
				"is_active" => 0
			], 'id');

			$periode_id = $insert_periode;
			$post_semester = [
				[
					"periode_id" => $periode_id,
					"semester" => 1,
					"is_active" => 0
				],
				[
					"periode_id" => $periode_id,
					"semester" => 2,
					"is_active" => 0
				]
			];
			$save = $this->db->insert_batch('mt_periode_semester', $post_semester);
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
		$model = $this->Periode_model->find($id);
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
		// $name			= $post_data["name"];

		if (!empty($id)) {
			$data = $this->Periode_model->find($id);
			if (empty($data)) {
				$this->session->set_flashdata('error', "Data not found");
	        	return redirect($this->own_link);
	        }
		}

		// if (empty($name)) {
		// 	$errMessage[] = "Name is required";
		// }

		return $errMessage;
	}
	
	private function privilege($field, $id = null) {
		// $user_access_detail = $this->user_access_detail;
		// if ($user_access_detail[$this->menu_id][$field] != 1) {
		// 	$this->session->set_flashdata('error', "Access denied");
        // 	return redirect($this->own_link);
        // }
	}
}
