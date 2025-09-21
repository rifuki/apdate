<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Generate extends CI_Controller {
	var $menu_id = "";
	var $session_data = "";
	var $user_access_detail = "";
	var $table = "";
	var $judul = "";
	public function __Construct() {
		parent::__construct();
		$this->menu_id = 8;
		$this->session_data = $this->session->userdata('user_dashboard');
		// $this->user_access_detail = $this->session_data['user_access_detail'];

		$this->cekLogin();
		$this->own_link = admin_url('generate');
		$this->load->model('kesiswaan/Kelas_model');
		$this->load->model('Penilaian_model');
		$this->table = "mt_users_siswa";
		$this->judul = "Generate Data";
	}

	public function kelas_siswa_index() {
		$active_periode = active_periode();
		if (empty($active_periode)) {
			$this->session->set_flashdata('error', "Periode aktif tidak ditemukan. Silahkan setting periode aktif terlebih dahulu.");
			return redirect('dashboard');
		}

		if ($active_periode['status'] != '1.3') {
			$this->session->set_flashdata('error', "Gagal update karena periode saat ini belum dalam status Assign Siswa ke Kelas (1.3)");
			return redirect('dashboard');
		}

		$user_access_detail = $this->user_access_detail;
		// $is_create 			= $user_access_detail[$this->menu_id]["is_create"];
		$is_create = 1;
		$periode_active 				= (object) $active_periode;
		$tingkat_kelas 					= $this->Dbhelper->selectTabel('id, code, name', 'mt_tingkat_kelas', array(), 'code',' ASC');

		$filter = [
			"periode_id"				=> $periode_active->periode_id,
			"tingkat_kelas_id" 	=> "",
			"kelas_id"					=> ""
		];
		$list_kelas = [];
		$list_siswa = [
			"new"			=> [],
			"current"	=> []
		];
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			$filter["tingkat_kelas_id"] = dbClean($post['tingkat_kelas_id']);
			$filter["kelas_id"] = !empty($post['kelas_id']) ? dbClean($post['kelas_id']) : '';

			$whereKelas = $filter;
			unset($whereKelas['kelas_id']);
			$list_kelas = $this->Dbhelper->selectTabel('id, kelas', 'tref_kelas', $whereKelas, 'kelas',' ASC');
			$tingkatKelas = $this->Dbhelper->selectTabelOne('code, name', 'mt_tingkat_kelas', ['id' => $filter['tingkat_kelas_id']], 'code',' ASC');
			$list_siswa = $this->Kelas_model->listSiswaGenerate($filter, $tingkatKelas);
		}
		$data['judul'] 					= $this->judul;
		$data['subjudul'] 			= 'Generate Kelas Siswa';
		$data['own_link'] 			= $this->own_link.'/kelas-siswa';
		$data['is_create'] 			= $is_create;
		$data['periode']   			= (array) $periode_active;
		$data['tingkat_kelas'] 	= $tingkat_kelas;
		$data['filter']					= $filter;
		$data['list_kelas']			= $list_kelas;
		$data['list_siswa']			= $list_siswa;
		$this->template->_v('generate/kelas_siswa_index', $data);
	}

	public function kelas_siswa_update() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			$siswa_id = $post['siswa_id'];
			$kelas_id = $post['kelas_id'];

			$this->db->trans_begin();
			try {
				$message = '';
				if ($kelas_id == 0) {
					$kelasSebelumnya = $this->Dbhelper->selectTabelOne('current_kelas_id', 'mt_users_siswa', ['id' => $siswa_id]);
					$deleteSiswaKelas = $this->db->delete('tref_kelas_siswa', ['siswa_id' => $siswa_id, 'kelas_id' => $kelasSebelumnya['current_kelas_id'], 'status' => enumSiswaKelas('A')]);
					if (!$deleteSiswaKelas) {
						throw new Exception("Gagal mengeluarkan siswa dari kelas");
					}
					$updateSiswa = $this->db->where('id', $siswa_id)->update('mt_users_siswa', ['current_kelas_id' => NULL]);
					if (!$updateSiswa) {
						throw new Exception("Gagal mengubah data kelas aktif pada siswa");
					}
					$message = 'Siswa berhasil dikeluarkan dari kelas';
				} else {
					$post['updated_at'] = date('Y-m-d H:i:s');
					$insertSiswaKelas = $this->db->insert('tref_kelas_siswa', $post);
					if (!$insertSiswaKelas) {
						throw new Exception("Gagal menambahkan siswa kedalam kelas");
					}
					$updateSiswa = $this->db->where('id', $siswa_id)->update('mt_users_siswa', ['current_kelas_id' => $kelas_id]);
					if (!$updateSiswa) {
						throw new Exception("Gagal mengubah data kelas aktif pada siswa");
					}
					$message = 'Siswa berhasil ditambahkan pada kelas';
				}

				$this->db->trans_commit();
				success($message);
			} catch (Exception $e) {
				
				$this->db->trans_rollback();
				error($e->getMessage());
			}

		}
		badrequest('Method not allowed');
	}

	public function jadwal_kelas_index() {
		$active_periode = active_periode();
		if (empty($active_periode)) {
			$this->session->set_flashdata('error', "Periode aktif tidak ditemukan. Silahkan setting periode aktif terlebih dahulu.");
			return redirect('dashboard');
		}

		if ($active_periode['status'] != '1.4') {
			$this->session->set_flashdata('error', "Gagal update karena periode saat ini belum dalam status Generate Jadwal Kelas (1.4)");
			return redirect('dashboard');
		}

		$user_access_detail = $this->user_access_detail;
		// $is_create 			= $user_access_detail[$this->menu_id]["is_create"];
		$is_create = 1;

		$list_kelas = $this->Kelas_model->listKelasJadwal($active_periode['periode_id'] , $active_periode['id']);
		$data['judul'] 					= $this->judul;
		$data['subjudul'] 			= 'Generate Jadwal Kelas';
		$data['own_link'] 			= $this->own_link.'/jadwal-kelas';
		$data['is_create'] 			= $is_create;
		$data['periode']   			= (array) $active_periode;
		$data['list_kelas']			= $list_kelas;
		$this->template->_v('generate/jadwal_kelas_index', $data);
	}

	public function jadwal_kelas_detail() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			$periode_id = $post['periode_id'];
			$semester_id = $post['semester_id'];
			$kelas_id = $post['kelas_id'];

			try {
				$kelas = $this->Dbhelper->selectTabelOne('*', 'tref_kelas', ['periode_id' => $periode_id, 'id' => $kelas_id]);
				if (empty($kelas)) {
						throw new Exception("Kelas tidak ditemukan");
				}

				$jadwalKelas = $this->Kelas_model->listJadwal($semester_id, $kelas_id);
				$data = [
					"kelas"		=> $kelas,
					"jadwal"	=> $jadwalKelas
				];
				success('Berhasil mendapatkan jadwal kelas '.$kelas['kelas'], $data);
			} catch (Exception $e) {
				
				error($e->getMessage());
			}

		}
		badrequest('Method not allowed');
	}

	public function jadwal_kelas_update() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			$periode_id = $post['periode_id'];
			$semester_id = $post['semester_id'];
			$kelas_id = $post['kelas_id'];

			$this->db->trans_begin();
			try {
				$kelas = $this->Dbhelper->selectTabelOne('*', 'tref_kelas', ['periode_id' => $periode_id, 'id' => $kelas_id]);
				if (empty($kelas)) {
						throw new Exception("Kelas tidak ditemukan");
				}

				$checkExisting = $this->Dbhelper->selectTabelOne('id', 'tref_kelas_jadwal_pelajaran', ['semester_id' => $semester_id, 'kelas_id' => $kelas_id]);
				if (!empty($checkExisting)) {
						throw new Exception("Jadwal kelas sudah ada, silahkan hapus terlebih dahulu utk generate ulang");
				}

				$getMataPelajaran = $this->Dbhelper->selectTabel('*', 'tref_periode_mata_pelajaran', ['periode_id' => $periode_id, 'tingkat_kelas_id' => $kelas['tingkat_kelas_id']]);
				if (empty($getMataPelajaran)) {
					throw new Exception('Mata pelajaran untuk kelas '.$kelas['kelas'].' belum disetting.');
				}

				$insert_batch = [];
				foreach ($getMataPelajaran as $v) {
					$insert_batch[] = [
						"semester_id" => $semester_id,
						"kelas_id"		=> $kelas_id,
						"mata_pelajaran_id"	=> $v['mata_pelajaran_id'],
						"guru_id"	=> $v['guru_id'],
					];
				}

				$save = $this->db->insert_batch('tref_kelas_jadwal_pelajaran', $insert_batch);
				if (!$save) {
						throw new Exception("Gagal membuat jadwal kelas".$kelas['kelas']);
				}

				$this->db->trans_commit();
				success('Berhasil membuat jadwal kelas '.$kelas['kelas']);
			} catch (Exception $e) {
				
				$this->db->trans_rollback();
				error($e->getMessage());
			}

		}
		badrequest('Method not allowed');
	}

	public function closing_tahun_ajaran_index() {
		$user_access_detail = $this->user_access_detail;
		// $is_create 			= $user_access_detail[$this->menu_id]["is_create"];
		
		$active_periode = active_periode();
		$list_kelas = $this->Kelas_model->listKelasJadwalSemester($active_periode['id']);
		$filter = [
			"jadwal_id"					=> "",
		];
		$list_rapor = [];
		$list_grading = $this->Dbhelper->selectTabel('*', 'mt_grading', [], 'grade',' ASC');
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();

			$filter["jadwal_id"] = !empty($post['jadwal_id']) ? dbClean($post['jadwal_id']) : '';
			$list_rapor = $this->Penilaian_model->listRaporByKelas($active_periode['id'], $filter['jadwal_id']);
		}
		$data['judul'] 					= 'Generate';
		$data['subjudul'] 			= 'Grading E-Rapor';
		$data['own_link'] 			= $this->own_link.'/closing-tahun-ajaran';
		$data['active_periode']   			= $active_periode;
		$data['filter']					= $filter;
		$data['list_kelas']			= $list_kelas;
		$data['list_rapor']			= $list_rapor;
		$data['list_grading']			= $list_grading;
		$this->template->_v('generate/closing_tahun_ajaran_index', $data);
	}

	public function closing_tahun_ajaran_do_penilaian() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			$semester_id = $this->input->post('semester_id');
			$jadwal_id = $this->input->post('jadwal_id');
			$type = $this->input->post('type');
			
			$nilai = $post['nilai_akhir'];
			if (!empty($nilai)) {
				foreach ($nilai as $rapor_id => $value) {
					$checkRapor = $this->Penilaian_model->checkRaporById($rapor_id);
					if ($checkRapor) {
						$checkGrade = $this->Dbhelper->selectTabelOne('grade', 'mt_grading', ['nilai_min <=' => $value, 'nilai_max >=' => $value]);
						$data = [
							'grade' => $checkGrade['grade'],
							'nilai_akhir' => $value,
							'is_close' => $type == 'draft' ? 0 : 1,
							'updated_at' => date('Y-m-d H:i:s')
						];
						
						$rapor_id = $checkRapor['id'];
						$this->db->where('id', $rapor_id);
						$update = $this->db->update('tr_rapor', $data);
					}
				}
				success('Data berhasil disimpan.');
			}

			error('Data gagal disimpan.');
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
