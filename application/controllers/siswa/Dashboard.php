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

		$informasi = $this->db->order_by('created_at', 'DESC')->get('mt_informasi')->result_array();
		$data['informasi'] = $informasi;
		$data['judul'] = 'Dashboard';
		$data['subjudul'] = 'Index';
		$this->template->_vSiswa('dashboard', $data);
	}

	public function profil() {
		
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();
		$biodata 		= $this->db->get_where('mt_users_siswa', ['users_id' => $session['user']['id']])->row_array();
		$orang_tua 	= $this->db->get_where('mt_users_siswa_orangtua', ['users_id' => $session['user']['id']])->result_array();
		// dd($session, $biodata, $orang_tua);
		$data['judul'] = 'Profil';
		$data['subjudul'] = 'Biodata Siswa';
		$data['siswa'] = $biodata;
		$data['orangtua'] = $orang_tua;
		$this->template->_vSiswa('profil', $data);
	}

	public function profil_update() {
		$session = $this->session->userdata('user_dashboard');
		if (!$session) {
			redirect('siswa/login');
		}

		$user_id = $session['user']['id'];

		// Ambil input dari form
		$nama = $this->input->post('nama', true);
		$email = $this->input->post('email', true);
		$alamat = $this->input->post('alamat', true);
		$tempat_lahir = $this->input->post('tempat_lahir', true);
		$tanggal_lahir = $this->input->post('tanggal_lahir', true);
		$agama = $this->input->post('agama', true);
		$sekolah_asal = $this->input->post('sekolah_asal', true);
		$nomor_hp = $this->input->post('nomor_hp', true);
		$password = $this->input->post('password', true);

		// Update mt_users_siswa
		$data_siswa = [
			'nama' => $nama,
			'tempat_lahir' => $tempat_lahir,
			'tanggal_lahir' => $tanggal_lahir,
			'agama' => $agama,
			'sekolah_asal' => $sekolah_asal,
			'alamat' => $alamat,
			'nomor_hp' => $nomor_hp
		];
		$this->db->where('users_id', $user_id);
		$this->db->update('mt_users_siswa', $data_siswa);

		// Update mt_users (jika password diisi)
		if (!empty($password)) {
			$data_user['password'] = password_hash($password, PASSWORD_DEFAULT);
			$data_user['password_raw'] = $password;
			$this->db->where('id', $user_id);
			$this->db->update('m_users', $data_user);
		}

		$this->session->set_flashdata('success', 'Profil berhasil diperbarui.');
		redirect('siswa/profil');
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
