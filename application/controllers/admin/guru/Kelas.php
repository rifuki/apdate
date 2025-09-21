<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelas extends CI_Controller {
	public function __Construct() {
		parent::__construct();
		$this->cekLogin();
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
		$this->template->_vGuru('lms/index', $data);
	}

	public function aktifkan_kelas() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			$slug = fromSlug($post['slug']);
			$close_at = date('Y-m-d H:i:s', strtotime($post['tanggal_tutup'].' '.$post['jam_tutup']));
			$where = [
				'jadwal_kelas_id' => $slug[0],
				'pertemuan_ke'		=> $slug[1]
			];
			$update = [
				'status' 			=> 1,
				'close_at'		=> $close_at,
				'updated_at'	=> date('Y-m-d H:i:s')
			];
			$save = $this->Dbhelper->updateData('tref_pertemuan', $where, $update);
			if ($save) {
				$this->session->set_flashdata('success', "Update data success");
				return redirect('guru/jadwal-kelas/detail/'.$slug[0]);
			}
			$this->session->set_flashdata('error', "Update data failed");
				return redirect('guru/jadwal-kelas/detail/'.$slug[0]);
		}
		$this->session->set_flashdata('error', "Access denied");
		return redirect('guru/dashboard');
	}

	public function tutup_kelas() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			$slug = fromSlug($post['slug']);
			$close_at = date('Y-m-d H:i:s', '-10 seconds');
			$where = [
				'jadwal_kelas_id' => $slug[0],
				'pertemuan_ke'		=> $slug[1]
			];
			$update = [
				'status' 		=> 1,
				'close_at'		=> $close_at,
				'updated_at'	=> date('Y-m-d H:i:s')
			];
			$save = $this->Dbhelper->updateData('tref_pertemuan', $where, $update);
			if ($save) {
				$this->session->set_flashdata('success', "Update data success");
				return redirect('guru/jadwal-kelas/detail/'.$slug[0]);
			}
			$this->session->set_flashdata('error', "Update data failed");
				return redirect('guru/jadwal-kelas/detail/'.$slug[0]);
		}
		$this->session->set_flashdata('error', "Access denied");
		return redirect('guru/dashboard');
	}

	public function update_jadwal_kelas() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			// dd($post);
			$id = (int) $post["jadwal_kelas_id"];
			$jumlah_pertemuan = (int) $post["jumlah_pertemuan"];

			$insert_batch = [];
			for ($i = 1; $i <= $jumlah_pertemuan; $i++) {
				$insert_batch[] = [
					'jadwal_kelas_id' => $id,
					'pertemuan_ke' 		=> $i,
					'created_at' 			=> date('Y-m-d H:i:s'),
					'updated_at' 			=> date('Y-m-d H:i:s')
				];
			}

			// Insert data into pertemuan table
			$insert = $this->db->insert_batch('tref_pertemuan', $insert_batch);
			if ($insert) {
				// Update jadwal_kelas table
				$save = $this->Dbhelper->updateData('tref_kelas_jadwal_pelajaran', array('id'=>$id), array('jumlah_pertemuan' => $jumlah_pertemuan));
				if ($save) {
					$this->session->set_flashdata('success', "Update data success");
					return redirect('guru/dashboard');
				}
			}
			$this->session->set_flashdata('error', "Update data failed");
			return redirect('guru/dashboard');
		}
		$this->session->set_flashdata('error', "Access denied");
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
