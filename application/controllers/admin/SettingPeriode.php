<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SettingPeriode extends CI_Controller {
	var $menu_id = "";
	var $session_data = "";
	var $user_access_detail = "";
	var $table = "";
	var $judul = "";
	public function __Construct() {
		parent::__construct();
		$this->menu_id = 41;
		$this->session_data = $this->session->userdata('user_dashboard');
		// $this->user_access_detail = $this->session_data['user_access_detail'];

		$this->cekLogin();
		$this->own_link = admin_url('setting-periode');
		$this->table = "mt_status_periode";
		$this->judul = "Setting Periode";
	}

	public function index() {
		$setting = $this->db->get($this->table)->result_array();
		$active_periode = active_periode();
		$list_periode = $this->db->get('mt_periode')->result_array();
		$data['judul'] 		= $this->judul;
		$data['subjudul'] 	= 'Setting';
		$data['own_link'] 	= $this->own_link;
		$data['setting'] 	= $setting;
		$data['active_periode'] = $active_periode;
		$data['periode']    = $list_periode;
		$this->template->_v('setting_periode_index', $data);
	}

	public function do_update() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			$this->db->trans_begin();
			try {
				$periode_id = $post['periode'];
				$semester = $post['semester'];
				$status = $post['status'];

				$reset_all = $this->db->where('is_active', 1)->update('mt_periode_semester', ['is_active' => 0]);
				$reset_all = $this->db->where('is_active', 1)->update('mt_periode', ['is_active' => 0]);

				$update_periode = $this->db->where('id', $periode_id)->update('mt_periode', ['is_active' => 1]);
				$update_periode_semester = $this->db->where('periode_id', $periode_id)->where('semester', $semester)->update('mt_periode_semester', ['is_active' => 1, 'status' => $status]);
				
				if (!$update_periode || !$update_periode_semester) {
					throw new Exception("Gagal mengubah setting periode");
				}

				// JIKA STATUS KBM FINISH / REPORTING
				if ($status > 2) {
					$close_all_pertemuan = $this->db->where('status', 0)->where('close_at is NULL', null, false)->update('tref_pertemuan', ['status' => 1, 'close_at' => date('Y-m-d H:i:s')]);
				}

				// JIKA STATUS NEW SEMESTER
				if ($status == '1') {
					foreach (['upload/modul', 'upload/tugas'] as $folder) {
						$fullPath = FCPATH . $folder;
						if (is_dir($fullPath)) {
							$files = glob($fullPath . '/*');
							if ($files) {
								foreach ($files as $file) {
									if (is_file($file)) {
										@unlink($file);
									} elseif (is_dir($file)) {
										@rmdir($file);
									}
								}
							}
							@rmdir($fullPath);
						}
						if (!is_dir($fullPath)) {
							@mkdir($fullPath, 0777, true);
						}
					}
				}

				$this->db->trans_commit();
				success('Perubahan berhasil disimpan');
			} catch (Exception $e) {
				$this->db->trans_rollback();
				error($e->getMessage());
			}

		}
		badrequest('Method not allowed');
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
			$data = $this->Siswa_model->find($id);
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
