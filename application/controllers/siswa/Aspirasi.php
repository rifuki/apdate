<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aspirasi extends CI_Controller {
	public function __Construct() {
		parent::__construct();
		$this->cekLogin();
		$this->load->model('Lms_model');
	}

	public function index() {
		
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();

		$listdata = $this->Lms_model->get_aspirasi_by_siswa_id($session['user']['siswa']['id']);
		$data['judul'] = 'Aspirasi';
		$data['subjudul'] = 'List Data';
		$data['listdata'] = $listdata;
		$this->template->_vSiswa('lms/aspirasi', $data);
	}

	public function save_aspirasi() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post 		= $this->input->post();
			$session 	= $this->session->userdata('user_dashboard');
			$id 		= isset($post['id']) ? (int)$post['id'] : 0;
			$judul 		= isset($post['judul']) ? $post['judul'] : 0;
			$deskripsi 	= isset($post['deskripsi']) ? trim($post['deskripsi']) : '';
			$now 		= date('Y-m-d H:i:s');

			$data = [
				'siswa_id' 		=> $session['user']['siswa']['id'],
				'judul'			=> $judul,
				'deskripsi' 	=> $deskripsi,
				'created_at' 	=> $now,
				'created_by'	=> $session['user']['id'],
				'updated_at' 	=> $now,
				'updated_by'	=> $session['user']['id']
			];

			if ($id > 0) {
				// Update
				$this->db->where('id', $id);
				unset($data['created_at'], $data['created_by'], $data['siswa_id']);
				$result = $this->db->update('tref_aspirasi', $data);
				if ($result) {
					success('Aspirasi berhasil disimpan.');
				} else {
					error('Aspirasi gagal disimpan.');
				}
			} else {
				// Create
				$data['created_at'] = $now;
				$result = $this->db->insert('tref_aspirasi', $data);
				if ($result) {
					success('Aspirasi berhasil disimpan.');
				} else {
					error('Aspirasi gagal disimpan.');
				}
			}
		}
		badrequest('Method not allowed');
	}

	public function index_konseling() {
		
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();

		$listdata = $this->Lms_model->get_konseling_by_siswa_id($session['user']['siswa']['id']);
		$data['judul'] = 'Konseling';
		$data['subjudul'] = 'List Data';
		$data['listdata'] = $listdata;
		$this->template->_vSiswa('lms/konseling', $data);
	}

	public function save_konseling() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post 		= $this->input->post();
			$session 	= $this->session->userdata('user_dashboard');
			$id 		= isset($post['id']) ? (int)$post['id'] : 0;
			$judul 		= isset($post['judul']) ? $post['judul'] : 0;
			$deskripsi 	= isset($post['deskripsi']) ? trim($post['deskripsi']) : '';
			$now 		= date('Y-m-d H:i:s');

			$data = [
				'siswa_id' 		=> $session['user']['siswa']['id'],
				'judul'			=> $judul,
				'deskripsi' 	=> $deskripsi,
				'created_at' 	=> $now,
				'created_by'	=> $session['user']['id'],
				'updated_at' 	=> $now,
				'updated_by'	=> $session['user']['id']
			];

			if ($id > 0) {
				// Update
				$this->db->where('id', $id);
				unset($data['created_at'], $data['created_by'], $data['siswa_id']);
				$result = $this->db->update('tref_konseling', $data);
				if ($result) {
					success('Konseling berhasil disimpan.');
				} else {
					error('Konseling gagal disimpan.');
				}
			} else {
				// Create
				$data['created_at'] = $now;
				$result = $this->db->insert('tref_konseling', $data);
				if ($result) {
					success('Konseling berhasil disimpan.');
				} else {
					error('Konseling gagal disimpan.');
				}
			}
		}
		badrequest('Method not allowed');
	}

	private function cekLogin() {
		$menu_id = 1;
		$session = $this->session->userdata('user_dashboard');
		if (empty($session)) {
			redirect('siswa/login');
		}

		// $user_access = $session['user_access'];
		// if (!in_array($menu_id, $user_access)) {
		// 	redirect('login_dashboard');
		// }
	}
}
