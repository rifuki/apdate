	<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lms extends CI_Controller {
	public function __Construct() {
		parent::__construct();
		$this->cekLogin();
		$this->load->model('Lms_model');
	}

	public function absensi($slug) {
		$slug = fromSlug($slug);
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();
		$pertemuan = $this->Lms_model->find_pertemuan($slug[0], $slug[1]);
		if (empty($pertemuan)) {
			$this->session->set_flashdata('success', "Data not found");
			return redirect('guru/dashboard');
		}
		$absensi = $this->Lms_model->find_absensi($pertemuan['jadwal_kelas_id'], $pertemuan['id']);
		$data['judul'] = 'LMS';
		$data['subjudul'] = 'Absensi';
		$data['pertemuan'] = $pertemuan;
		$data['absensi'] = $absensi;
		$data['active_periode'] = $active_periode;
		$this->template->_vGuru('lms/absensi', $data);
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

	public function absensi_update() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			$session = $this->session->userdata('user_dashboard');
			// dd($post);

			$pertemuan_id = $post['pertemuan_id'];
			$pertemuan = $this->Lms_model->find_pertemuan_byid($pertemuan_id);
			if (empty($pertemuan)) {
				error('Data pertemuan tidak ditemukan');
			}

			$absensi_siswa 	= $post['absensi'];
			$nilai_siswa 		= $post['nilai'];

			foreach ($nilai_siswa as $siswa_id => $nilai) {
				$update = $this->db->where('siswa_id', $siswa_id)->where('pertemuan_id', $pertemuan_id)->update('tref_pertemuan_tugas', ['nilai' => $nilai]);
				if (!$update) {
					error('Gagal mengupdate nilai siswa');
				}
			}

			foreach ($absensi_siswa as $siswa_id => $status_kehadiran) {
				$where = [
					'pertemuan_id' 		=> $pertemuan_id,
					'jadwal_kelas_id'	=> $pertemuan['jadwal_kelas_id'],
					'siswa_id'				=> $siswa_id
				];

				$checkdata = $this->db->where($where)->from('tref_pertemuan_absensi')->get()->row_array();
				if (!empty($checkdata)) {
					$update = $this->db->where($where)->update('tref_pertemuan_absensi', ['status_kehadiran' => $status_kehadiran]);
					if (!$update) {
						error('Gagal mengupdate absensi kehadiran siswa');
					}
				} else {
					$insertdata = array_merge(['status_kehadiran' => $status_kehadiran, 'created_at' => date('Y-m-d H:i:s')], $where);
					$insert = $this->db->insert('tref_pertemuan_absensi', $insertdata);
					if (!$insert) {
						error('Gagal mengupdate absensi kehadiran siswa');
					}
				}
			}

			success('Data berhasil diupdate.');
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
			return redirect('guru/dashboard');
		}
		$modul = $this->Lms_model->find_modul($pertemuan['id']);

		$data['judul'] = 'LMS';
		$data['subjudul'] = 'Modul';
		$data['pertemuan'] = $pertemuan;
		$data['modul'] = $modul;
		$data['active_periode'] = $active_periode;
		$this->template->_vGuru('lms/modul', $data);
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
			return redirect('guru/dashboard');
		}
		$pranala_luar = $this->Lms_model->find_pranala_luar($pertemuan['id']);

		$data['judul'] = 'LMS';
		$data['subjudul'] = 'Pranala Luar';
		$data['pertemuan'] = $pertemuan;
		$data['pranala_luar'] = $pranala_luar;
		$data['active_periode'] = $active_periode;
		$this->template->_vGuru('lms/pranala_luar', $data);
	}

	public function save_pranala_luar() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			$id = isset($post['id']) ? (int)$post['id'] : 0;
			$pertemuan_id = isset($post['pertemuan_id']) ? (int)$post['pertemuan_id'] : 0;
			$judul = isset($post['judul']) ? trim($post['judul']) : '';
			$deskripsi = isset($post['deskripsi']) ? trim($post['deskripsi']) : '';
			$link = isset($post['link']) ? trim($post['link']) : '';
			$now = date('Y-m-d H:i:s');

			
			$pertemuan = $this->Lms_model->find_pertemuan_byid($pertemuan_id);

			$data = [
				'pertemuan_id' => $pertemuan_id,
				'judul' => $judul,
				'deskripsi' => $deskripsi,
				'link' => $link,
				'jadwal_kelas_id'	=> $pertemuan['jadwal_kelas_id']
			];

			if ($id > 0) {
				// Update
				$data['updated_at'] = $now;
				$this->db->where('id', $id);
				$result = $this->db->update('tref_pertemuan_pranala_luar', $data);
				if ($result) {
					$this->session->set_flashdata('success', 'Data pranala luar berhasil diupdate.');
				} else {
					$this->session->set_flashdata('error', 'Gagal update data pranala luar.');
				}
			} else {
				// Create
				$data['created_at'] = $now;
				$result = $this->db->insert('tref_pertemuan_pranala_luar', $data);
				if ($result) {
					$this->session->set_flashdata('success', 'Data pranala luar berhasil ditambahkan.');
				} else {
					$this->session->set_flashdata('error', 'Gagal menambah data pranala luar.');
				}
			}
			// Redirect kembali ke halaman pranala luar
			$slug = toSlug($pertemuan['jadwal_kelas_id'], $pertemuan['pertemuan_ke']);
			return redirect('guru/pertemuan/pranala-luar/' . $slug);
		}
		$this->session->set_flashdata('error', 'Akses tidak diizinkan.');
		return redirect('guru/dashboard');
	}

	public function diskusi($slug) {
		$slug = fromSlug($slug);
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();
		$pertemuan = $this->Lms_model->find_pertemuan($slug[0], $slug[1]);
		if (empty($pertemuan)) {
			$this->session->set_flashdata('success', "Data not found");
			return redirect('guru/dashboard');
		}
		$diskusi = $this->Lms_model->find_diskusi($pertemuan['id']);
		$data['judul'] = 'LMS';
		$data['subjudul'] = 'Diskusi';
		$data['pertemuan'] = $pertemuan;
		$data['diskusi'] = $diskusi;
		$data['user']				= $session;
		$data['active_periode'] = $active_periode;
		$this->template->_vGuru('lms/diskusi', $data);
	}

	public function save_diskusi() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			$session = $this->session->userdata('user_dashboard');
			$id = isset($post['id']) ? (int)$post['id'] : 0;
			$pertemuan_id = isset($post['pertemuan_id']) ? (int)$post['pertemuan_id'] : 0;
			$deskripsi = isset($post['deskripsi']) ? trim($post['deskripsi']) : '';
			$now = date('Y-m-d H:i:s');
			// Redirect kembali ke halaman diskusi
			$pertemuan = $this->Lms_model->find_pertemuan_byid($pertemuan_id);
			$data = [
				'pertemuan_id' 		=> $pertemuan_id,
				'deskripsi' 		=> $deskripsi,
				'user_id'			=> $session['user']['id']
			];

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
				if (!empty($session['user']['guru'])) {
					$data['guru_id'] = $session['user']['guru']['id'];
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
			$slug = toSlug($pertemuan['jadwal_kelas_id'], $pertemuan['pertemuan_ke']);
			return redirect('guru/pertemuan/diskusi/' . $slug);
		}
		$this->session->set_flashdata('error', 'Akses tidak diizinkan.');
		return redirect('guru/dashboard');
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
			return redirect('guru/pertemuan/diskusi/' . $slug);
		}
		$this->session->set_flashdata('error', 'Akses tidak diizinkan.');
		return redirect('guru/dashboard');
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
