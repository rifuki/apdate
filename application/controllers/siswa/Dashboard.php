<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	public function __Construct() {
		parent::__construct();
		$this->cekLogin();
		$this->load->model('Lms_model');
		$this->load->model('Penilaian_model');
	}

	public function index() {
		
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();

		$semester_id = $active_periode['id'];
		$kelas_id = $session['user']['siswa']['current_kelas_id'];
		$siswa_id = $session['user']['siswa']['id'];

		$list_mapel = $this->Lms_model->find_mapel_siswa($semester_id, $kelas_id, $siswa_id);
		$data['judul'] = 'LMS';
		$data['subjudul'] = 'Dashboard';
		$data['list_mapel'] = $list_mapel;
		$this->template->_vSiswa('index', $data);
	}

	public function aktifitas_lms_index() {
		
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();

		$semester_id = $active_periode['id'];
		$kelas_id = $session['user']['siswa']['current_kelas_id'];
		$siswa_id = $session['user']['siswa']['id'];

		$list_mapel = $this->Lms_model->find_mapel_siswa($semester_id, $kelas_id, $siswa_id);
		$data['judul'] = 'LMS';
		$data['subjudul'] = 'Dashboard';
		$data['list_mapel'] = $list_mapel;
		$this->template->_vSiswa('aktifitas_index', $data);
	}

	public function aktitifas_lms_detail() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			$session = $this->session->userdata('user_dashboard');

			$siswa_id = $session['user']['siswa']['id'];
			$now = date('Y-m-d H:i:s');
			
			$jadwal_id = $this->input->post('jadwal_id');
			if (empty($jadwal_id) || empty($siswa_id)) {
				error('Jadwal atau Siswa tidak ditemukan.');
			}
			$data = $this->Penilaian_model->listPenilaian($jadwal_id, $siswa_id);
			if (!empty($data)) {
				$response['data'] = $data;
				$response['jadwal_id'] = $jadwal_id;
				$response['siswa_id'] = $siswa_id;
				$load_view = 'siswa/aktifitas_detail';
				success('Data berhasil didapatkan.', ['view' => $this->load->view($load_view, $response, TRUE)]);
			}
			success('Tugas berhasil disimpan.');
		}
		badrequest('Method not allowed');
	}

	public function lms_index() {
		
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();

		$semester_id = $active_periode['id'];
		$kelas_id = $session['user']['siswa']['current_kelas_id'];
		$siswa_id = $session['user']['siswa']['id'];

		$list_mapel = $this->Lms_model->find_mapel_siswa($semester_id, $kelas_id, $siswa_id);
		$data['judul'] = 'LMS';
		$data['subjudul'] = 'Dashboard';
		$data['list_mapel'] = $list_mapel;
		$this->template->_vSiswa('index', $data);
	}

	public function dokumen() {
		
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();

		$dokumen = $this->db->get('mt_dokumen')->result_array();
		$data['judul'] = 'Dokumen';
		$data['subjudul'] = 'Informasi Dokumen';
		$data['dokumen'] = $dokumen;
		$this->template->_vSiswa('dokumen', $data);
	}

	public function akademik() {
		
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();

		$data['judul'] = 'Akademik';
		$data['subjudul'] = 'Informasi Akademik';
		$data['active_periode'] = $active_periode;
		// dd($data);
		$this->template->_vSiswa('akademik', $data);
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
