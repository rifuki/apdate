<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KenaikanKelas extends CI_Controller {
	var $menu_id = "";
	var $session_data = "";
	var $user_access_detail = "";
	var $table = "";
	var $judul = "";
	public function __Construct() {
		parent::__construct();
		$this->menu_id = 44;
		$this->session_data = $this->session->userdata('user_dashboard');
		// $this->user_access_detail = $this->session_data['user_access_detail'];

		$this->cekLogin();
		$this->own_link = admin_url('kenaikan-kelas');
		$this->load->model('kesiswaan/Siswa_model');
		$this->table = "mt_setting_lms";
		$this->judul = "Kenaikan Kelas";
	}

	public function index() {
		$setting = $this->db->get($this->table)->result_array();

		$data['judul'] 		= $this->judul;
		$data['subjudul'] 	= 'Kenaikan Kelas';
		$data['own_link'] 	= $this->own_link;
		$data['setting'] 	= $setting;
		$this->template->_v('kenaikan_kelas', $data);
	}

	public function datatables() {
		$user_access_detail = $this->user_access_detail;
		$list = $this->Siswa_model->get_datatables_kenaikan_kelas();
        $data = array();
        $no = isset($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $field) {
            $no++;
            $row_menu = array();
            $row_menu[] = $no;
            $row_menu[] = $field->kelas;
            $row_menu[] = $field->nisn;
            $row_menu[] = $field->nama;
            $data[] = $row_menu;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Siswa_model->count_all(),
            "recordsFiltered" => $this->Siswa_model->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
	}

	public function do_update() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$active_periode = active_periode();
			if (empty($active_periode)) {
				error("Periode aktif tidak ditemukan");
			}
			if ($active_periode['status_code'] != '3.2') {
				error("Kenaikan kelas hanya dapat dilakukan pada akhir semester 2");
			}
			$post = $this->input->post();
			$list_siswa = !empty($post['list_siswa']) ? $post['list_siswa'] : [];

			$this->db->trans_begin();
			try {
				$list = $this->Siswa_model->get_datatables_kenaikan_kelas();
				if (empty($list)) {
					throw new Exception("Data siswa tidak ditemukan");
				}

				$list_siswa_kelas_7 = $this->Siswa_model->get_list_siswa_tingkat('7');

				$list_naik_kelas_7 = [];
				$list_tinggal_kelas_7 = [];
				if (!empty($list_siswa_kelas_7)) {
					foreach ($list_siswa_kelas_7 as $v) {
						if (in_array($v->id, $list_siswa)) {
							$list_tinggal_kelas_7[] = $v->id;
						} else {
							$list_naik_kelas_7[] = $v->id;
						}
					}

					$update = $this->db->where_in('id', $list_naik_kelas_7)->update('mt_users_siswa', ['current_kelas_id' => NULL, 'status_siswa' => 'naik_kelas_8']);
					$update = $this->db->where_in('id', $list_tinggal_kelas_7)->update('mt_users_siswa', ['current_kelas_id' => NULL, 'status_siswa' => 'tinggal_kelas_7']);
				}

				$list_siswa_kelas_8 = $this->Siswa_model->get_list_siswa_tingkat('8');

				$list_naik_kelas_8 = [];
				$list_tinggal_kelas_8 = [];
				if (!empty($list_siswa_kelas_8)) {
					foreach ($list_siswa_kelas_8 as $v) {
						if (in_array($v->id, $list_siswa)) {
							$list_tinggal_kelas_8[] = $v->id;
						} else {
							$list_naik_kelas_8[] = $v->id;
						}
					}

					$update = $this->db->where_in('id', $list_naik_kelas_8)->update('mt_users_siswa', ['current_kelas_id' => NULL, 'status_siswa' => 'naik_kelas_9']);
					$update = $this->db->where_in('id', $list_tinggal_kelas_8)->update('mt_users_siswa', ['current_kelas_id' => NULL, 'status_siswa' => 'tinggal_kelas_8']);
				}

				$list_siswa_kelas_9 = $this->Siswa_model->get_list_siswa_tingkat('9');

				$list_naik_kelas_9 = [];
				$list_tinggal_kelas_9 = [];
				if (!empty($list_siswa_kelas_9)) {
					foreach ($list_siswa_kelas_9 as $v) {
						if (in_array($v->id, $list_siswa)) {
							$list_tinggal_kelas_9[] = $v->id;
						} else {
							$list_naik_kelas_9[] = $v->id;
						}
					}
					$update = $this->db->where_in('id', $list_naik_kelas_9)->update('mt_users_siswa', ['current_kelas_id' => NULL, 'status_siswa' => 'alumni']);
					$update = $this->db->where_in('id', $list_tinggal_kelas_9)->update('mt_users_siswa', ['current_kelas_id' => NULL, 'status_siswa' => 'tinggal_kelas_9']);
				}

				
					// throw new Exception("Tipe tidak dikenali");
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
