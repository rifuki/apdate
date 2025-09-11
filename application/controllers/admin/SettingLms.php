<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SettingLms extends CI_Controller {
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
		$this->own_link = admin_url('setting-lms');
		$this->table = "mt_setting_lms";
		$this->judul = "Setting LMS";
	}

	public function index() {
		$setting = $this->db->get($this->table)->result_array();

		$data['judul'] 		= $this->judul;
		$data['subjudul'] 	= 'Setting';
		$data['own_link'] 	= $this->own_link;
		$data['setting'] 	= $setting;
		$this->template->_v('setting_lms_index', $data);
	}

	public function do_update() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			$type = $post['type'];

			$this->db->trans_begin();
			try {
				if ($type == 'absensi_diskusi') {
					$absen_diskusi = $post['absen_diskusi'];
					$absen_diskusi_total_kata = $post['absen_diskusi_total_kata'];

					$update1 = $this->db->where('code', 'absen_diskusi')->update($this->table, ['value' => $absen_diskusi]);
					if (!$update1) {
						throw new Exception("Gagal mengubah setting absensi diskusi");
					}
					$update2 = $this->db->where('code', 'absen_diskusi_total_kata')->update($this->table, ['value' => $absen_diskusi_total_kata]);
					if (!$update2) {	
						throw new Exception("Gagal mengubah setting absen diskusi total kata");
					}

				} elseif ($type == 'filter_kalimat') {
					$filter_kalimat_data = $this->db->where('code', 'filter_kalimat')->get($this->table)->row_array();
					
					$fk_value = json_decode($filter_kalimat_data['value']) ?? [];

					$filter_kalimat = $post['filter_kalimat'];
					$fk_value[] = $filter_kalimat;
					$filter_kalimat_json = json_encode($fk_value);

					$update = $this->db->where('code', 'filter_kalimat')->update($this->table, ['value' => $filter_kalimat_json]);
					if (!$update) {
						throw new Exception("Gagal mengubah setting filter kalimat");
					}
				} elseif ($type == 'hapus_filter_kalimat') {
					$filter_kalimat_data = $this->db->where('code', 'filter_kalimat')->get($this->table)->row_array();
					
					$fk_value = json_decode($filter_kalimat_data['value']) ?? [];
					$filter_kalimat = json_decode($post['filter_kalimat']) ?? [];

					$fk_value = array_diff($fk_value, $filter_kalimat);
					$filter_kalimat_json = json_encode(array_values($fk_value));

					$update = $this->db->where('code', 'filter_kalimat')->update($this->table, ['value' => $filter_kalimat_json]);
					if (!$update) {
						throw new Exception("Gagal mengubah setting filter kalimat");
					}

				} else {
					throw new Exception("Tipe tidak dikenali");
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
