	<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lms extends CI_Controller {
	public function __Construct() {
		parent::__construct();
		$this->cekLogin();
		$this->load->model('Lms_model');
	}

	public function index($jadwal_id) {
		
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();

		$kelas = $this->Dbhelper->selectTabelOne('*', 'tref_kelas_jadwal_pelajaran', array('id' => $jadwal_id));
		$list_pertemuan = find_pertemuan($jadwal_id, "", "array");
		$data['judul'] = 'LMS';
		$data['subjudul'] = 'List Pertemuan';
		$data['kelas'] = $kelas;
		$data['list_pertemuan'] = $list_pertemuan;
		$this->template->_vSiswa('lms/index', $data);
	}

	public function tugas($slug) {
		$slug = fromSlug($slug);
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();
		$pertemuan = $this->Lms_model->find_pertemuan($slug[0], $slug[1]);
		if (empty($pertemuan)) {
			$this->session->set_flashdata('success', "Data not found");
			return redirect('siswa/dashboard');
		}
		$tugas = $this->Lms_model->find_tugas_by_siswa_id($pertemuan['id'], $session['user']['siswa']['id']);
		$is_close = (!empty($pertemuan['close_at']) && strtotime(date('Y-m-d H:i:s')) > strtotime($pertemuan['close_at'])) ? true : false;

		$data['judul'] 			= 'LMS';
		$data['subjudul'] 	= 'Absensi dan Tugas';
		$data['pertemuan'] 	= $pertemuan;
		$data['tugas'] 		= $tugas;
		$data['is_close'] = $is_close;
		$this->template->_vSiswa('lms/tugas', $data);
	}

	public function modul($slug) {
		$slug = fromSlug($slug);
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();
		$pertemuan = $this->Lms_model->find_pertemuan($slug[0], $slug[1]);
		if (empty($pertemuan)) {
			$this->session->set_flashdata('success', "Data not found");
			return redirect('siswa/dashboard');
		}
		$modul = $this->Lms_model->find_modul($pertemuan['id']);

		$data['judul'] = 'LMS';
		$data['subjudul'] = 'Modul';
		$data['pertemuan'] = $pertemuan;
		$data['modul'] = $modul;
		$this->template->_vSiswa('lms/modul', $data);
	}

	public function pranala_luar($slug) {
		$slug = fromSlug($slug);
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();
		$pertemuan = $this->Lms_model->find_pertemuan($slug[0], $slug[1]);
		if (empty($pertemuan)) {
			$this->session->set_flashdata('success', "Data not found");
			return redirect('siswa/dashboard');
		}
		$pranala_luar = $this->Lms_model->find_pranala_luar($pertemuan['id']);

		$data['judul'] = 'LMS';
		$data['subjudul'] = 'Pranala Luar';
		$data['pertemuan'] = $pertemuan;
		$data['pranala_luar'] = $pranala_luar;
		$this->template->_vSiswa('lms/pranala_luar', $data);
	}

	public function diskusi($slug) {
		$slug = fromSlug($slug);
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();
		$pertemuan = $this->Lms_model->find_pertemuan($slug[0], $slug[1]);
		if (empty($pertemuan)) {
			$this->session->set_flashdata('success', "Data not found");
			return redirect('siswa/dashboard');
		}
		$diskusi = $this->Lms_model->find_diskusi($pertemuan['id']);
		$is_close = (!empty($pertemuan['close_at']) && strtotime(date('Y-m-d H:i:s')) > strtotime($pertemuan['close_at'])) ? true : false;

		$data['judul'] 			= 'LMS';
		$data['subjudul'] 	= 'Diskusi';
		$data['pertemuan'] 	= $pertemuan;
		$data['diskusi'] 		= $diskusi;
		$data['user']				= $session;
		$data['is_close'] = $is_close;
		$this->template->_vSiswa('lms/diskusi', $data);
	}

	public function save_diskusi() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			$session = $this->session->userdata('user_dashboard');
			$id = isset($post['id']) ? (int)$post['id'] : 0;
			$pertemuan_id = isset($post['pertemuan_id']) ? (int)$post['pertemuan_id'] : 0;
			$deskripsi = isset($post['deskripsi']) ? trim($post['deskripsi']) : '';
			$now = date('Y-m-d H:i:s');

			$pertemuan = $this->Lms_model->find_pertemuan_byid($pertemuan_id);
			$data = [
				'pertemuan_id' => $pertemuan_id,
				'deskripsi' => $deskripsi,
				'user_id'		=> $session['user']['id'],
			];

			$setting_lms = setting_lms();
			$valid_absensi = 1;
			$filter_kalimat = json_decode($setting_lms[2]['value']);
			if (!empty($filter_kalimat)) {
				foreach ($filter_kalimat as $row) {
					if (stripos($deskripsi, $row) !== false && str_word_count($deskripsi) < $setting_lms[1]['value']) {
						$valid_absensi = 0;
					}
				}
			}


			$data['valid_absensi'] = $valid_absensi;

			if ($id > 0) {
				// Update
				$data['updated_at'] = $now;
				$this->db->where('id', $id);
				$result = $this->db->update('tref_pertemuan_diskusi', $data);
				if ($result) {
					$this->session->set_flashdata('success', 'Data diskusi berhasil diupdate.');
				} else {
					$this->session->set_flashdata('error', 'Gagal update data diskusi.');
				}
			} else {
				if (!empty($session['user']['siswa'])) {
					$data['siswa_id'] = $session['user']['siswa']['id'];
				}
				// Create
				$data['created_at'] = $now;
				$data['updated_at'] = $now;
				$data['jadwal_kelas_id'] = $pertemuan['jadwal_kelas_id'];
				$result = $this->db->insert('tref_pertemuan_diskusi', $data);
				if ($result) {
					$this->session->set_flashdata('success', 'Data diskusi berhasil ditambahkan.');
				} else {
					$this->session->set_flashdata('error', 'Gagal menambah data diskusi.');
				}
			}
			// Redirect kembali ke halaman diskusi
			$slug = toSlug($pertemuan['jadwal_kelas_id'], $pertemuan['pertemuan_ke']);
			return redirect('siswa/pertemuan/diskusi/' . $slug);
		}
		$this->session->set_flashdata('error', 'Akses tidak diizinkan.');
		return redirect('siswa/dashboard');
	}

	public function delete_diskusi() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			$session = $this->session->userdata('user_dashboard');
			$id = isset($post['id']) ? (int)$post['id'] : 0;
			$pertemuan_id = isset($post['pertemuan_id']) ? (int)$post['pertemuan_id'] : 0;

			$pertemuan = $this->Lms_model->find_pertemuan_byid($pertemuan_id);
			$this->db->where('id', $id);
			$result = $this->db->update('tref_pertemuan_diskusi', ['deleted_at' => date('Y-m-d H:i:s')]);
			if ($result) {
				$this->session->set_flashdata('success', 'Data diskusi berhasil didelete.');
			} else {
				$this->session->set_flashdata('error', 'Data diskusi gagal didelete.');
			}
			// Redirect kembali ke halaman diskusi
			$slug = toSlug($pertemuan['jadwal_kelas_id'], $pertemuan['pertemuan_ke']);
			return redirect('siswa/pertemuan/diskusi/' . $slug);
		}
		$this->session->set_flashdata('error', 'Akses tidak diizinkan.');
		return redirect('siswa/dashboard');
	}

	public function update_tugas() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			$session = $this->session->userdata('user_dashboard');

			$pertemuan_id = isset($post['pertemuan_id']) ? (int)$post['pertemuan_id'] : 0;
			if ($pertemuan_id <= 0) {
				error('Invalid pertemuan ID');
			}
			$siswa_id = $session['user']['siswa']['id'];
			$now = date('Y-m-d H:i:s');
			
			$pertemuan = $this->Lms_model->find_pertemuan_byid($pertemuan_id);
			$file_path = null;
			
			// Simpan absensi ke database (contoh: tabel tref_pertemuan_absensi)
			$data = [
				'jadwal_kelas_id'	=> $pertemuan['jadwal_kelas_id'],
				'pertemuan_id' 		=> $pertemuan_id,
				'siswa_id' 			=> $siswa_id,
				'deskripsi' 		=> isset($post['deskripsi']) ? $post['deskripsi'] : null,
				'link' 					=> isset($post['link']) ? $post['link'] : null,
				'created_at' 		=> $now,
				'updated_at' 		=> $now,
			];
			// Proses upload file jika ada
			if (isset($_FILES['file'])) {
				if ($_FILES['file']['error'] != 0) {
					error('Upload file gagal, silahkan cek kembali file Anda');
				}

				$byte = 1024;
				$max_size = 1536 * $byte;
				if ($_FILES['file']['size'] > $max_size) {
					error('Ukuran file melebihi batas maksimum 1.5MB.');
				}

				$uploadDir = FCPATH . 'upload/tugas/';
				if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
				$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
				$fileName = 'tugas_' . $pertemuan_id . '_' . $siswa_id . '_' . time() . '.' . $ext;
				$filePath = $uploadDir . $fileName;
				if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
						$file_path = 'upload/tugas/' . $fileName;
						$data['file'] = $file_path;
				} else {
						error('Gagal upload file tugas');
				}
			}

			$cek_tugas = $this->Lms_model->find_tugas_by_siswa_id($pertemuan_id, $siswa_id);
			if ($cek_tugas) {
				unset($data['created_at']);
				// Update data
				$this->db->where('pertemuan_id', $pertemuan_id);
				$this->db->where('siswa_id', $siswa_id);
				$update = $this->db->update('tref_pertemuan_tugas', $data);
				if ($update) {
					success('Tugas berhasil disimpan.');
				} else {
					success('Tugas gagal disimpan.');
				}
			}

			$this->db->insert('tref_pertemuan_tugas', $data);
			success('Tugas berhasil disimpan.');
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
