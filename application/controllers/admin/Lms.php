	<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lms extends CI_Controller {
	public function __Construct() {
		parent::__construct();
		$this->cekLogin();
		$this->load->model('Lms_model');
		$this->load->model('kesiswaan/Kelas_model');
	}

	public function index($jadwal_id) {
		
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();

		$jadwal_kelas = $this->Dbhelper->selectTabelOne('*', 'tref_kelas_jadwal_pelajaran', array('id' => $jadwal_id));
		$list_pertemuan = find_pertemuan($jadwal_id, "", "array");
		$data['judul'] = 'LMS';
		$data['subjudul'] = 'List Pertemuan';
		$data['jadwal_kelas'] = $jadwal_kelas;
		$data['list_pertemuan'] = $list_pertemuan;
		$this->template->_v('lms/index', $data);
	}

	public function tingkat_kelas_index() {
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();
		$data['judul'] = 'LMS';
		$data['subjudul'] = 'Pantau LMS';
		$this->template->_v('lms/tingkat_kelas_index', $data);
	}

	public function lms_index($tingkat_kelas) {
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();
		$list_kelas = $this->Kelas_model->listKelasJadwalSemester($active_periode['id'], $tingkat_kelas);
		$data['judul'] = 'LMS';
		$data['subjudul'] = 'List Kelas';
		$data['active_periode'] = $active_periode;
		$data['list_kelas'] 		= $list_kelas;
		$this->template->_v('lms/lms_index', $data);
	}

	public function absensi($slug) {
		$slug = fromSlug($slug);
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();
		$pertemuan = $this->Lms_model->find_pertemuan($slug[0], $slug[1]);
		if (empty($pertemuan)) {
			$this->session->set_flashdata('success', "Data not found");
			return redirect('dashboard/lms/detail/'.$slug[0]);
		}
		$absensi = $this->Lms_model->find_absensi($pertemuan['jadwal_kelas_id'], $pertemuan['id']);
		$data['judul'] = 'LMS';
		$data['subjudul'] = 'Absensi';
		$data['pertemuan'] = $pertemuan;
		$data['absensi'] = $absensi;
		$data['active_periode'] = $active_periode;
		$this->template->_v('lms/absensi', $data);
	}
	
	public function absensi_detail() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			$session = $this->session->userdata('user_dashboard');
			$type = isset($post['type']) ? $post['type'] : '';
			if ($type == 'absensi') {
				$absensi_id = $post['id'];
				$data = $this->Lms_model->find_absensi_byid($absensi_id);
				$view = $this->load->view('guru/lms/absensi_detail_absen', ['absensi' => $data], true);
				
				success('Data berhasil didapatkan.', ['html' => $view]);
			} elseif ($type == 'tugas') {
				$absensi_id = $post['id'];
				$data = $this->Lms_model->find_tugas_byid($absensi_id);
				$view = $this->load->view('guru/lms/absensi_detail_tugas', ['tugas' => $data], true);
				
				success('Data berhasil didapatkan.', ['html' => $view]);
			}

			error('Data gagal didapatkan.');
		}
		badrequest('Method not allowed');
	}

	public function modul($slug) {
		$slug = fromSlug($slug);
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();
		$pertemuan = $this->Lms_model->find_pertemuan($slug[0], $slug[1]);
		if (empty($pertemuan)) {
			$this->session->set_flashdata('success', "Data not found");
			return redirect('dashboard/lms/detail/'.$slug[0]);
		}
		$modul = $this->Lms_model->find_modul($pertemuan['id']);

		$data['judul'] = 'LMS';
		$data['subjudul'] = 'Modul';
		$data['pertemuan'] = $pertemuan;
		$data['modul'] = $modul;
		$data['active_periode'] = $active_periode;
		$this->template->_v('lms/modul', $data);
	}

	public function update_modul() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			// dd($post, $_FILES); // Hapus debug
			$pertemuan_id = isset($post['pertemuan_id']) ? (int)$post['pertemuan_id'] : 0;
			$deskripsi = isset($post['deskripsi']) ? $post['deskripsi'] : '';
			$created_at = date('Y-m-d H:i:s');
			$file_name_db = null;

			$pertemuan = $this->Lms_model->find_pertemuan_byid($pertemuan_id);
			// dd($pertemuan);
			$slug = toSlug($pertemuan['jadwal_kelas_id'], $pertemuan['pertemuan_ke']);
			// Cek data modul
			$modul = $this->db->get_where('tref_pertemuan_modul', ['pertemuan_id' => $pertemuan_id])->row_array();
			$upload_path = FCPATH . 'upload/modul/';
			if (!is_dir($upload_path)) {
				mkdir($upload_path, 0777, true);
			}
			$file_name_db = $modul ? $modul['file'] : null;
			$file_uploaded = false;

			// Jika ada file baru diinput
			if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
				// Jika update dan ada file lama, hapus file lama
				if ($modul && !empty($modul['file']) && file_exists($upload_path . $modul['file'])) {
					@unlink($upload_path . $modul['file']);
				}
				$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
				$new_name = 'pertemuan' . str_pad($pertemuan_id, 2, '0', STR_PAD_LEFT) . '.' . $ext;
				$target_file = $upload_path . $new_name;
				if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
					$file_name_db = $new_name;
					$file_uploaded = true;
				} else {
					$this->session->set_flashdata('error', "File upload failed");
					return redirect($this->agent->referrer() ?: 'guru/dashboard');
				}
			}

			if ($modul) {
				// Update data
				$update_data = [
					'deskripsi' => $deskripsi,
					'updated_at' => $created_at
				];
				if ($file_uploaded) {
					$update_data['file'] = $file_name_db;
				}
				$this->db->where('pertemuan_id', $pertemuan_id);
				$update = $this->db->update('tref_pertemuan_modul', $update_data);
				if ($update) {
					$this->session->set_flashdata('success', "Update modul berhasil");
					return redirect('guru/pertemuan/modul/' . $slug);
				} else {
					$this->session->set_flashdata('error', "Gagal update data modul");
					return redirect('guru/pertemuan/modul/' . $slug);
				}
			} else {
				// Insert data baru
				$insert_data = [
					'pertemuan_id' => $pertemuan_id,
					'deskripsi'    => $deskripsi,
					'file'         => $file_name_db,
					'created_at'   => $created_at,
					'jadwal_kelas_id'	=> $pertemuan['jadwal_kelas_id']
				];
				$insert = $this->db->insert('tref_pertemuan_modul', $insert_data);
				if ($insert) {
					$this->session->set_flashdata('success', "Upload modul berhasil");
					return redirect('guru/pertemuan/modul/' . $slug);
				} else {
					$this->session->set_flashdata('error', "Gagal menyimpan data modul");
					return redirect('guru/pertemuan/modul/' . $slug);
				}
			}
		}
		$this->session->set_flashdata('error', "Access denied");
		return redirect('guru/dashboard');
	}

	public function pranala_luar($slug) {
		$slug = fromSlug($slug);
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();
		$pertemuan = $this->Lms_model->find_pertemuan($slug[0], $slug[1]);
		if (empty($pertemuan)) {
			$this->session->set_flashdata('success', "Data not found");
			return redirect('dashboard/lms/detail/'.$slug[0]);
		}
		$pranala_luar = $this->Lms_model->find_pranala_luar($pertemuan['id']);

		$data['judul'] = 'LMS';
		$data['subjudul'] = 'Pranala Luar';
		$data['pertemuan'] = $pertemuan;
		$data['pranala_luar'] = $pranala_luar;
		$data['active_periode'] = $active_periode;
		$this->template->_v('lms/pranala_luar', $data);
	}

	public function diskusi($slug) {
		$slug = fromSlug($slug);
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();
		$pertemuan = $this->Lms_model->find_pertemuan($slug[0], $slug[1]);
		if (empty($pertemuan)) {
			$this->session->set_flashdata('success', "Data not found");
			return redirect('dashboard/lms/detail/'.$slug[0]);
		}
		$diskusi = $this->Lms_model->find_diskusi($pertemuan['id']);
		$data['judul'] = 'LMS';
		$data['subjudul'] = 'Diskusi';
		$data['pertemuan'] = $pertemuan;
		$data['diskusi'] = $diskusi;
		$data['user']				= $session;
		$data['active_periode'] = $active_periode;
		$this->template->_v('lms/diskusi', $data);
	}
	
	public function diskusi_delete() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			$session = $this->session->userdata('user_dashboard');
			$id = isset($post['id']) ? (int)$post['id'] : 0;
			$pertemuan_id = isset($post['pertemuan_id']) ? (int)$post['pertemuan_id'] : 0;

			$pertemuan = $this->Lms_model->find_pertemuan_byid($pertemuan_id);
			$this->db->where('id', $id);
			$result = $this->db->update('tref_pertemuan_diskusi', ['deleted_by_admin' => date('Y-m-d H:i:s'), 'valid_absensi' => 0]);
			if ($result) {
				$this->session->set_flashdata('success', 'Data diskusi berhasil didelete.');
			} else {
				$this->session->set_flashdata('error', 'Data diskusi gagal didelete.');
			}
			// Redirect kembali ke halaman diskusi
			$slug = toSlug($pertemuan['jadwal_kelas_id'], $pertemuan['pertemuan_ke']);
			return redirect('dashboard/lms/pertemuan/diskusi/' . $slug);
		}
		$this->session->set_flashdata('error', 'Akses tidak diizinkan.');
		return redirect('dashboard');
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
