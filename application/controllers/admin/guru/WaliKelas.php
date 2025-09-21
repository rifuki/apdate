	<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WaliKelas extends CI_Controller {
	public function __Construct() {
		parent::__construct();
		$this->cekLogin();
		$this->load->model('Lms_model');
		$this->load->model('Penilaian_model');
		$this->load->model('kesiswaan/Kelas_model');
		$this->load->model('kesiswaan/Siswa_model');
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
		$this->template->_vGuru('wali_kelas/lms/index', $data);
	}

	public function lms_index() {
		
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();
		// dd($session);
		$list_kelas = $this->Kelas_model->listKelasWaliKelas($active_periode['id'], $session['user']['guru']['id']);
		// dd($list_kelas);
		$data['judul'] = 'Wali Kelas';
		$data['subjudul'] = 'Pantau LMS';
		$data['list_kelas'] = $list_kelas;
		$data['active_periode'] = $active_periode;
		$this->template->_vGuru('wali_kelas/lms_index', $data);
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
		$this->template->_vGuru('wali_kelas/lms/absensi', $data);
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
			return redirect('guru/dashboard');
		}
		$modul = $this->Lms_model->find_modul($pertemuan['id']);

		$data['judul'] = 'LMS';
		$data['subjudul'] = 'Modul';
		$data['pertemuan'] = $pertemuan;
		$data['modul'] = $modul;
		$this->template->_vGuru('wali_kelas/lms/modul', $data);
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
		$this->template->_vGuru('wali_kelas/lms/pranala_luar', $data);
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
		$this->template->_vGuru('wali_kelas/lms/diskusi', $data);
	}

	public function konseling() {
		
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();

		$list_siswa = $this->Kelas_model->listSiswaWaliKelas($active_periode['periode_id'], $session['user']['guru']['id']);
		$siswa_ids = [];
		foreach ($list_siswa as $v) {
			$siswa_ids[] = $v['siswa_id'];
		}

		$listdata = $this->Lms_model->get_konseling_by_siswa_id($siswa_ids);
		$data['judul'] = 'Konseling';
		$data['subjudul'] = 'List Data';
		$data['listdata'] = $listdata;
		$this->template->_vGuru('wali_kelas/lms/konseling', $data);
	}

	public function siswa() {
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();
		$data['list_siswa'] = [];
		$data['filter'] = [
			"kelas_id" => ""
		];
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			$kelas_id = isset($post['kelas_id']) ? (int)$post['kelas_id'] : 0;
			if ($kelas_id > 0) {
				$data['list_siswa'] = $this->Penilaian_model->listSiswa($kelas_id, $active_periode['id']);
				$data['filter'] = [
					'kelas_id' => $kelas_id
				];
			}
		}
		$data['judul'] = 'E-Rapor';
		$data['subjudul'] = 'E-Rapor';
		// $data['list_kelas'] = $list_kelas;
		$data['active_periode'] = $active_periode;
		// dd($data);
		$this->template->_vGuru('wali_kelas/siswa', $data);
	}

	public function siswa_ekstrakulikuler() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$session = $this->session->userdata('user_dashboard');
			$active_periode = active_periode();
			$kelas_id = $this->input->post('kelas_id');
			$siswa_id = $this->input->post('siswa_id');
			if (empty($kelas_id) || empty($siswa_id)) {
				error('Kelas atau Siswa tidak ditemukan.');
			}
			$data = $this->db->select('a.*')
						->from('tref_kelas_siswa_ekskul as a')
						->where('a.kelas_id', $kelas_id)
						->where('a.siswa_id', $siswa_id)
						->get()->result_array();
			// dd($data);
			if (!empty($data)) {
				success('Data berhasil didapatkan.', $data);
			}

			error('Tidak ada data.');
		}
		badrequest('Method not allowed');
	}

	public function siswa_ekstrakulikuler_do_update() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			$type = $post['type'];

			$this->db->trans_begin();
			try {
				if ($type == 'tambah_ekstrakulikuler') {
					$ekstrakulikuler = $this->db->where('kelas_id', $post['kelas_id'])->where('siswa_id', $post['siswa_id'])->get('tref_kelas_siswa_ekskul')->row_array();

					$ekskul_value = [];
					if (!empty($ekstrakulikuler)) {
						$ekskul_value = json_decode($ekstrakulikuler['ekskul_list']);
					}
					

					$ekskul_value[] = $post['ekstrakulikuler'];
					$ekskul_json = json_encode($ekskul_value);

					if (!empty($ekstrakulikuler)) {
						$update = $this->db->where('kelas_id', $post['kelas_id'])->where('siswa_id', $post['siswa_id'])->update('tref_kelas_siswa_ekskul', ['ekskul_list' => $ekskul_json]);
						if (!$update) {
							throw new Exception("Gagal menambah ekstrakulikuler");
						}
					} else {
						$insert = $this->db->insert('tref_kelas_siswa_ekskul', [
							'kelas_id' => $post['kelas_id'],
							'siswa_id' => $post['siswa_id'],
							'ekskul_list' => $ekskul_json
						]);
						if (!$insert) {
							throw new Exception("Gagal menambah ekstrakulikuler");
						}
					}

				} elseif ($type == 'hapus_ekstrakulikuler') {
					$ekstrakulikuler = $this->db->where('kelas_id', $post['kelas_id'])->where('siswa_id', $post['siswa_id'])->get('tref_kelas_siswa_ekskul')->row_array();
					
					$fk_value = json_decode($ekstrakulikuler['ekskul_list']) ?? [];
					$ekskul_list = json_decode($post['ekstrakulikuler']) ?? [];

					$fk_value = array_diff($fk_value, $ekskul_list);
					$ekskul_json = json_encode(array_values($fk_value));

					$update = $this->db->where('kelas_id', $post['kelas_id'])->where('siswa_id', $post['siswa_id'])->update('tref_kelas_siswa_ekskul', ['ekskul_list' => $ekskul_json]);
					if (!$update) {
						throw new Exception("Gagal menambah ekstrakulikuler");
					}

				} else {
					throw new Exception("Tipe tidak dikenali");
				}
				$this->db->trans_commit();
				success('Perubahan berhasil disimpan');
			} catch (Exception $e) {
				$this->db->trans_rollback();
				error($e->getMessage());
			}

		}
		badrequest('Method not allowed');
	}

	public function erapor() {
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();
		$data['list_siswa'] = [];
		$data['filter'] = [
			"kelas_id" => ""
		];
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			$kelas_id = isset($post['kelas_id']) ? (int)$post['kelas_id'] : 0;
			if ($kelas_id > 0) {
				$data['list_siswa'] = $this->Penilaian_model->listSiswa($kelas_id, $active_periode['periode_id'], $active_periode['id']);
				$data['filter'] = [
					'kelas_id' => $kelas_id
				];
			}
		}
		$gradingDefault = $this->Dbhelper->selectTabel('*', 'mt_grading', [], 'nilai_max', 'DESC');
		$data['judul'] = 'E-Rapor';
		$data['subjudul'] = 'E-Rapor';
		$data['active_periode'] = $active_periode;
		$data['list_grading'] = $gradingDefault;
		// dd($data);
		$this->template->_vGuru('wali_kelas/e_rapor', $data);
	}

	public function erapor_detail() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$session = $this->session->userdata('user_dashboard');
			$active_periode = active_periode();
			$kelas_id = $this->input->post('kelas_id');
			$siswa_id = $this->input->post('siswa_id');
			if (empty($kelas_id) || empty($siswa_id)) {
				error('Kelas atau Siswa tidak ditemukan.');
			}
			$siswa = [
				"siswa_id" 		=> $siswa_id,
				"kelas_id"		=> $kelas_id,
				"periode_id"	=> $active_periode['periode_id'],
				"semester_id"	=> $active_periode['id']
			];
			$data = $this->Penilaian_model->listEGrading($kelas_id, $siswa_id);
			$rapor = $this->Dbhelper->selectTabelOne('*', 'tr_rapor', $siswa);
			if (!empty($data)) {
				success('Data berhasil didapatkan.', ['view' => $this->load->view('guru/wali_kelas/e_rapor_detail', ['data' => $data, 'active_periode' => $active_periode, 'siswa' => $siswa, 'rapor' => $rapor], TRUE)]);
			}

			error('Data gagal didapatkan.');
		}
		badrequest('Method not allowed');
	}

	public function erapor_submit() {
		if ($this->input->server('REQUEST_METHOD') !== 'POST') {
			badrequest('Method not allowed');
		}

		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();
		$post = $this->input->post();

		$periode_id = $this->input->post('periode_id');
		$semester_id = $this->input->post('semester_id');
		$kelas_id = $this->input->post('kelas_id');
		$siswa_id = $this->input->post('siswa_id');
		$is_close = $this->input->post('is_close');
		if (empty($kelas_id) || empty($siswa_id)) {
			error('Kelas atau Siswa tidak ditemukan.');
		}

		$nilai_rapor 	= [];
		$mapel_code 	=  $this->input->post('mapel_code');
		$guru_code 		=  $this->input->post('guru_code');
		$nilai_old 		=  $this->input->post('nilai_old');
		$nilai 			=  $this->input->post('nilai');
		$grade 			=  $this->input->post('grade');
		$keterangan 	=  $this->input->post('keterangan');
		foreach ($nilai as $i => $v) {
			$slug = $mapel_code[$i].'-'.$guru_code[$i];
			$as_data = [
				"mapel_code"	=> $mapel_code[$i],
				"guru_code" 	=> $guru_code[$i],
				"nilai" 		=> $v,
				"grade"			=> $grade[$i],
				"keterangan"	=> $keterangan[$i]
			];

			if ($v != $nilai_old[$i]) {
				$checkGrade = $this->Dbhelper->selectTabelOne('grade, keterangan', 'mt_grading', ['nilai_min <=' => $v, 'nilai_max >=' => $v]);
				$as_data["grade"] = $checkGrade['grade'];
				$as_data["keterangan"] = $checkGrade['keterangan'];
			}

			$nilai_rapor[$slug] = $as_data;
		}

		$whereCondition = [
			"siswa_id" 		=> $siswa_id,
			"kelas_id"		=> $kelas_id,
			"periode_id"	=> $periode_id,
			"semester_id"	=> $semester_id
		];

		$json_nilai = json_encode($nilai_rapor);
		$in_data = [
			"json_grading_akhir" 	=> $json_nilai,
			"is_close"				=> $is_close == "true" ? 1 : 0,
			"updated_at"			=> date("Y-m-d H:i:s")
		];
		$checkRapor = $this->Dbhelper->selectTabelOne('*', 'tr_rapor', $whereCondition);
		if (!empty($checkRapor)) {
			$save = $this->db->where($whereCondition)->update('tr_rapor', $in_data);
		} else {
			$in_data["created_at"] = date("Y-m-d H:i:s");
			$insert_data = array_merge($whereCondition, $in_data);
			$save = $this->db->insert('tr_rapor', $insert_data);
		}

		if ($save) {
			success('Rapor berhasil disimpan');
		}

		error('Data gagal didapatkan.');
	}

	public function erapor_pdf($rapor_id) {
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();
		
		$rapor = $this->Dbhelper->selectTabelOne('*', 'tr_rapor', ['id' => $rapor_id]);
		if (empty($rapor)) {
			$this->session->set_flashdata('error', "Data rapor tidak ditemukan");
			return redirect('guru/wali-kelas/e-rapor');
		}

		$data['rapor']			= $rapor;
		$data['siswa'] 			= $this->Siswa_model->findSiswa($rapor['siswa_id']);
		$data['ortu_ayah'] 			= $this->Dbhelper->selectTabelOne("*", "mt_users_siswa_orangtua", ["hubungan_keluarga" => "Ayah", "users_id" => $data['siswa']['users_id']]);
		$data['ortu_ibu'] 			= $this->Dbhelper->selectTabelOne("*", "mt_users_siswa_orangtua", ["hubungan_keluarga" => "Ibu", "users_id" => $data['siswa']['users_id']]);
		if (empty($data['ortu_ibu'])) {
			$data['ortu_ibu'] = $data['ortu_ayah'];
		}
		$data['active_periode'] = $active_periode;
		// dd($data);
		$this->load->view('guru/wali_kelas/e_rapor_pdf_'.$active_periode['semester'], $data);
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
