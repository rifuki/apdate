<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelas extends CI_Controller {
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
		$this->own_link = admin_url('kesiswaan/kelas');
		$this->load->model('kesiswaan/Kelas_model');
		$this->table = "tref_kelas";
		$this->judul = "Kelas";
	}

	public function index() {
		$user_access_detail = $this->user_access_detail;
		// $is_create 			= $user_access_detail[$this->menu_id]["is_create"];
		$is_create = 1;
		$periode_active = $this->Kelas_model->find_active_periode();
		$periode_id = $periode_active->id ?? null;

		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			$periode_id = $post['periode_id'];
		}

		$filter = [
			'periode_id' => $periode_id
		];
		$periode = $this->Dbhelper->selectTabel('id, tahun_ajaran', 'mt_periode', [], 'id', 'desc');
		$data['judul'] 			= $this->judul;
		$data['subjudul'] 	= 'List Data';
		$data['own_link'] 	= $this->own_link;
		$data['is_create'] 	= $is_create;
		$data['filter']			= $filter;
		$data['periode_list'] = $periode;
		$this->template->_v('kesiswaan/kelas/index', $data);
	}

	public function datatables() {
		$user_access_detail = $this->user_access_detail;
		$filter = $_POST['filter'];
		$list = $this->Kelas_model->get_datatables($filter);
		$data = array();
		$no = isset($_POST['start']) ? $_POST['start'] : 0;
		foreach ($list as $field) {
				$no++;
				
				$mapel_kelas = $this->Kelas_model->mapel_list($field->id);

				$mapel_kelas_text = "";
				if (!empty($mapel_kelas)) {
					foreach ($mapel_kelas as $i => $v) {
						$mapel_code = $v['mapel_code'];
						$mapel_name = $v['mapel_name'];
						if ($i > 0) {
							$mapel_kelas_text .= "<br/>";
						}
						$mapel_kelas_text .= $mapel_code.' - '.$mapel_name;
					}
				}
				$row_menu = array();
				$row_menu[] = $no;
				$row_menu[] = $field->kelas;
				$row_menu[] = $field->wali_kelas;
				$row_menu[] = $mapel_kelas_text;
				$row_menu[] = '<button onclick="listSiswa(`'.$field->id.'`)" class="btn btn-success btn-sm btn-flat">List Siswa</button>';
				// $row_menu[] = $field->is_active == 1 ? '<span class="p-2 bg-success">Aktif</span>' : '<span class="p-2 bg-secondary">Non Aktif</span>';

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
				"recordsTotal" => $this->Kelas_model->count_all(),
				"recordsFiltered" => $this->Kelas_model->count_filtered($filter),
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

		$tingkat_kelas 	= $this->Dbhelper->selectTabel('id, code, name', 'mt_tingkat_kelas', array(), 'code',' ASC');
		$periode 				= $this->Dbhelper->selectTabelOne('id, tahun_ajaran', 'mt_periode', array("is_active" => 1));
		$guru 					= $this->Dbhelper->selectTabel('id, nip, nama', 'mt_users_guru', array('is_active' => 1), 'nip',' ASC');
		$data['tingkat_kelas']	= $tingkat_kelas;
		$data['periode']	= $periode;
		$data['guru']	= $guru;

		$this->template->_v('kesiswaan/kelas/form', $data);
	}

	public function edit($id) {
		$id = (int) $id;
		$this->privilege('is_update');
		$model = $this->Kelas_model->find($id);
		if (empty($model)) {
			$this->session->set_flashdata('error', "Data not found");
			return redirect($this->own_link);
		}
		$tingkat_kelas = $this->Dbhelper->selectTabel('id, code, name', 'mt_tingkat_kelas', array("deleted_at" => NULL), 'code', 'ASC');	
		$mata_pelajaran = $this->Dbhelper->selectTabel('id, code, name', 'mt_mata_pelajaran', array("is_active" => 1), 'code', 'ASC');
		$periode 				= $this->Dbhelper->selectTabelOne('id, tahun_ajaran', 'mt_periode', array("id" => $model->periode_id));
		$guru 					= $this->Dbhelper->selectTabel('id, nip, nama', 'mt_users_guru', array('is_active' => 1), 'nip',' ASC');
		$model_mapel = $this->Kelas_model->find_mapel($id, true);
		$data['judul'] 			= $this->judul;
		$data['subjudul'] 		= 'Edit Data';
		$data['own_link'] 		= $this->own_link;


		$data['model']			= $model;
		$data['model_mapel']			= $model_mapel;
		$data['tingkat_kelas'] = $tingkat_kelas;
		$data['mata_pelajaran'] = $mata_pelajaran;
		$data['periode']	= $periode;
		$data['guru']	= $guru;
		$data['action']			= "do_update";

		$this->template->_v('kesiswaan/kelas/form', $data);
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
						$insert_batch[] = [
							'periode_id'	=> $id,
							'tingkat_kelas_id'	=> $tk_id,
							'mata_pelajaran_id'		=> $v
						];
					}
				}
				
			}
			$save = $this->db->delete('tref_periode_mata_pelajaran', array('periode_id' => $id));
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

			$active_periode = active_periode();
			if ($active_periode['status'] != "1.2") {
				$this->session->set_flashdata('error', "Gagal disimpan karena periode saat ini belum dalam status Assign Kelas (1.1)");
				return redirect($this->own_link."/create");
			}

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
			$mapel_list = $this->Dbhelper->selectTabel('mata_pelajaran_id, guru_id', 'tref_periode_mata_pelajaran', ['periode_id' => $post_data['periode_id'], 'tingkat_kelas_id' => $post_data['tingkat_kelas_id']]);
			if (empty($mapel_list)) {
				$this->session->set_flashdata('error', "Gagal disimpan karena tidak ada mata pelajaran yang diassign pada tingkat kelas ini");
				return redirect($this->own_link."/create");
			}
			// dd($mapel_list);
			$kelasID = $this->Dbhelper->insertDataWithReturnID($this->table, $post_data, 'id');
			
			$insert_mapelList = [];
			foreach ($mapel_list as $v) {
				$insert_mapelList[] = [
					"kelas_id" => $kelasID,
					"mata_pelajaran_id"	=> $v['mata_pelajaran_id'],
					"guru_id"		=> $v['guru_id']
				];
			}

			// if (!empty($insert_mapelList)) {
			$save =	$this->db->insert_batch('tref_kelas_mata_pelajaran', $insert_mapelList);
			// }

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
		$model = $this->Kelas_model->find($id);
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

	public function list_siswa($id) {
		$listSiswa = $this->Kelas_model->listSiswa($id);

		echo json_encode($listSiswa);
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
			$data = $this->Kelas_model->find($id);
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
