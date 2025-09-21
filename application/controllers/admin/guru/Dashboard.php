<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	public function __Construct() {
		parent::__construct();
		$this->cekLogin();
		$this->load->model('kesiswaan/Kelas_model');
	}

	public function index() {
		
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();
		// dd($active_periode);
		$list_kelas = $this->Kelas_model->listKelasJadwalGuru($active_periode['id'], $session['user']['guru']['id']);
		$informasi = $this->db->order_by('created_at', 'DESC')->get('mt_informasi')->result_array();
		$data['informasi'] = $informasi;
		$data['judul'] = 'Dashboard';
		$data['subjudul'] = 'Index';
		$data['list_kelas'] = $list_kelas;
		$this->template->_vGuru('dashboard', $data);
	}

	public function profil() {
		
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();
		$guru 		= $this->db->get_where('mt_users_guru', ['users_id' => $session['user']['id']])->row_array();
		// dd($guru);
		$data['judul'] = 'Profil';
		$data['subjudul'] = 'Biodata Guru';
		$data['guru'] = $guru;
		$this->template->_vGuru('profil', $data);
	}

	public function profil_update() {
		$session = $this->session->userdata('user_dashboard');
		$user_id = $session['user']['id'];
		// Ambil input dari form profil guru
		$nip = $this->input->post('nip', true);
		$nuptk = $this->input->post('nuptk', true);
		$jenis_kelamin = $this->input->post('jenis_kelamin', true);
		$pendidikan = $this->input->post('pendidikan', true);
		$jabatan = $this->input->post('jabatan', true);

		// Update mt_users_guru
		$data_guru = [
			'jenis_kelamin' => $jenis_kelamin,
			'pendidikan' => $pendidikan,
			'jabatan' => $jabatan,
			'nama' => $nama,
			'tempat_lahir' => $tempat_lahir,
			'tanggal_lahir' => $tanggal_lahir,
			'agama' => $agama,
			'alamat' => $alamat,
			'nomor_hp' => $nomor_hp,
			'email' => $email
		];
		$this->db->where('users_id', $user_id);
		$this->db->update('mt_users_guru', $data_guru);

		// Update m_users (jika password diisi)
		if (!empty($password)) {
			$data_user['password'] = password_hash($password, PASSWORD_DEFAULT);
			$data_user['password_raw'] = $password;
			$this->db->where('id', $user_id);
			$this->db->update('m_users', $data_user);
		}
		

		$this->session->set_flashdata('success', 'Profil berhasil diperbarui.');
		redirect('guru/profil');
	}

	public function lms_index() {
		
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();

		$list_kelas = $this->Kelas_model->listKelasJadwalGuru($active_periode['id'], $session['user']['guru']['id']);
		$data['judul'] = 'Dashboard';
		$data['subjudul'] = 'Index';
		$data['list_kelas'] = $list_kelas;
		$data['active_periode'] = $active_periode;
		$this->template->_vGuru('index', $data);
	}

	public function update_jadwal_kelas() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			// dd($post);
			$id = (int) $post["jadwal_kelas_id"];
			$jumlah_pertemuan 	= (int) $post["jumlah_pertemuan"] + 2;
			$posisi_uts 		= (int) $post["posisi_uts"];

			$insert_batch = [];
			for ($i = 1; $i <= $jumlah_pertemuan; $i++) {
				$pertemuan_ke = $i;
				if ($posisi_uts == $i) {
					$pertemuan_ke = "UTS";
				} elseif ($i == $jumlah_pertemuan) {
					$pertemuan_ke = "UAS";
				}
				$insert_batch[] = [
					'jadwal_kelas_id' 		=> $id,
					'pertemuan_ke' 			=> $pertemuan_ke,
					'created_at' 			=> date('Y-m-d H:i:s'),
					'updated_at' 			=> date('Y-m-d H:i:s')
				];
			}

			// Insert data into pertemuan table
			$insert = $this->db->insert_batch('tref_pertemuan', $insert_batch);
			if (!$insert) {
				error('Gagal mengatur jumlah pertemuan');
			}

			$save = $this->Dbhelper->updateData('tref_kelas_jadwal_pelajaran', array('id'=>$id), array('jumlah_pertemuan' => $jumlah_pertemuan));
			if ($save) {
				success('Jumlah pertemuan berhasil dibuat');
			}
			error('Gagal mengatur jumlah pertemuan.');
		}
		badrequest('Method not allowed');
	}

	public function dokumen() {
		
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();

		$dokumen = $this->db->get('mt_dokumen')->result_array();
		$data['judul'] = 'Dokumen';
		$data['subjudul'] = 'Informasi Dokumen';
		$data['dokumen'] = $dokumen;
		$this->template->_vGuru('dokumen', $data);
	}

	public function akademik() {
		
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();

		$data['judul'] = 'Akademik';
		$data['subjudul'] = 'Informasi Akademik';
		$data['active_periode'] = $active_periode;
		// dd($data);
		$this->template->_vGuru('akademik', $data);
	}

	private function cekLogin() {
		$menu_id = 1;
		$session = $this->session->userdata('user_dashboard');
		if (empty($session)) {
			redirect('guru/login');
		}

		// $user_access = $session['user_access'];
		// if (!in_array($menu_id, $user_access)) {
		// 	redirect('login_dashboard');
		// }
	}
}
