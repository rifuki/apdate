	<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {
	public function __Construct() {
		parent::__construct();
		$this->cekLogin();
		$this->load->model('Lms_model');
		$this->load->model('Penilaian_model');
		$this->load->model('kesiswaan/Kelas_model');
	}

	public function penilaian() {
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();
		$list_kelas = $this->Kelas_model->listKelasJadwalGuru($active_periode['id'], $session['user']['guru']['id']);

		
		$data['list_penilaian'] = [];
		$data['filter'] = [
			"jadwal_id" => ""
		];
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			$jadwal_id = isset($post['jadwal_id']) ? (int)$post['jadwal_id'] : 0;
			if ($jadwal_id > 0) {
				$data['list_penilaian'] = $this->Penilaian_model->listPenilaianByKelas($jadwal_id, $active_periode['id']);
				$data['filter'] = [
					'jadwal_id' => $jadwal_id
				];
			}
		}
		$data['judul'] = 'Laporan';
		$data['subjudul'] = 'Penilaian';
		$data['list_kelas'] = $list_kelas;
		$data['active_periode'] = $active_periode;
		$this->template->_vGuru('laporan/penilaian', $data);
	}

	public function penilaian_detail() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$session = $this->session->userdata('user_dashboard');
			$active_periode = active_periode();
			$jadwal_id = $this->input->post('jadwal_id');
			$siswa_id = $this->input->post('siswa_id');
			if (empty($jadwal_id) || empty($siswa_id)) {
				error('Jadwal atau Siswa tidak ditemukan.');
			}
			// Ambil data pertemuan
			$pertemuan = $this->Dbhelper->selectTabel('*', 'tref_pertemuan', ['jadwal_kelas_id' => $jadwal_id], 'id', 'ASC');
			if (empty($pertemuan)) {
				error('Data pertemuan tidak ditemukan.');
			}

			$data = $this->Penilaian_model->listPenilaian($jadwal_id, $siswa_id);
			if (!empty($data)) {
				$response['data'] = $data;
				$response['jadwal_id'] = $jadwal_id;
				$response['siswa_id'] = $siswa_id;
				$response['active_periode'] = $active_periode;
				$load_view = 'guru/laporan/penilaian_detail';
				if ($this->input->post('type') == 'absensi') {
					$load_view = 'guru/laporan/absensi_detail';
				}
				success('Data berhasil didapatkan.', ['view' => $this->load->view($load_view, $response, TRUE)]);
			}

			error('Data gagal didapatkan.');
		}
		badrequest('Method not allowed');
	}

	public function do_penilaian() {
		if ($this->input->server('REQUEST_METHOD') !== 'POST') {
			badrequest('Method not allowed');
		}
		$post = $this->input->post();
		$session = $this->session->userdata('user_dashboard');

		$absensi_siswa 	= $post['absensi'];
		$nilai_siswa 		= $post['nilai'];
		$jadwal_kelas_id = $post['jadwal_kelas_id'];
		
		if (empty($jadwal_kelas_id) || empty($absensi_siswa) || empty($nilai_siswa)) {
			error('Data tidak lengkap.');
		}

		try {
			//code...
		} catch (\Throwable $th) {
			//throw $th;
		}

			if (empty($post['absensi']) && empty($post['nilai'])) {
				error('Tidak ada data untuk disimpan.');
			}

			foreach ($nilai_siswa as $key_id => $nilai) {
				$exp = explode("_", $key_id);
				$siswa_id = $exp[0];
				$pertemuan_id = $exp[1];

				if ($nilai > 100) {
					error('Nilai tidak boleh lebih dari 100');
				}

				$checkdata = $this->db->where('siswa_id', $siswa_id)->where('pertemuan_id', $pertemuan_id)->from('tref_pertemuan_tugas')->get()->row_array();
				if (!empty($checkdata)) {
					$update = $this->db->where('siswa_id', $siswa_id)->where('pertemuan_id', $pertemuan_id)->update('tref_pertemuan_tugas', ['nilai' => $nilai]);
					if (!$update) {
						error('Gagal mengupdate nilai siswa');
					}
				} else {
					$insertdata = [
						'pertemuan_id'	=> $pertemuan_id,
						'siswa_id' 		=> $siswa_id,
						'jadwal_kelas_id' => $jadwal_kelas_id,
						'nilai'			=> $nilai,
						'created_at'	=> date('Y-m-d H:i:s'),
						'updated_at'	=> date('Y-m-d H:i:s')
					];

					$insert = $this->db->insert('tref_pertemuan_tugas', $insertdata);
					if (!$insert) {
						error('Gagal mengupdate nilai siswa');
					}
				}
			}

			foreach ($absensi_siswa as $key_id => $status_kehadiran) {
				$exp = explode("_", $key_id);
				$siswa_id = $exp[0];
				$pertemuan_id = $exp[1];
				$pertemuan = $this->Lms_model->find_pertemuan_byid($pertemuan_id);
				$where = [
					'pertemuan_id' 		=> $pertemuan_id,
					'jadwal_kelas_id'	=> $pertemuan['jadwal_kelas_id'],
					'siswa_id'				=> $siswa_id
				];

				if (!empty($status_kehadiran)) {
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
				
			}

			success('Data berhasil diupdate.');

	}

	public function penilaian_detail_excel()
	{
			// Pastikan method GET atau POST sesuai kebutuhan
			$jadwal_id = $this->input->post('jadwal_id');
			$siswa_id = $this->input->post('siswa_id');
			if (empty($jadwal_id) || empty($siswa_id)) {
					error('Jadwal atau Siswa tidak ditemukan.');
			}

			// Ambil data penilaian
			$data = $this->Penilaian_model->listPenilaian($jadwal_id, $siswa_id);

			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();

			// Header
			$sheet->setCellValue('A1', 'Pertemuan ID');
			$sheet->setCellValue('B1', 'Pertemuan Ke');
			$sheet->setCellValue('C1', 'Nilai Tugas');

			// Data
			$row = 2;
			foreach ($data as $item) {
					$sheet->setCellValue('A' . $row, $item['id']);
					$sheet->setCellValue('B' . $row, $item['pertemuan_ke']);
					$sheet->setCellValue('C' . $row, $item['nilai_tugas'] ?? '');
					$row++;
			}

			ob_clean();
			// Output Excel
			$filename = 'Penilaian Detail.xlsx';
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="' . $filename . '"');
			header('Cache-Control: max-age=0');

			$writer = new Xlsx($spreadsheet);
			$writer->save('php://output');
			exit;
	}
	public function penilaian_detail_import()
	{
		
		if ($this->input->server('REQUEST_METHOD') !== 'POST') {
			badrequest('Method not allowed');
		}

		$jadwal_id = $this->input->post('jadwal_id');
		$siswa_id = $this->input->post('siswa_id');

		if (empty($jadwal_id) || empty($siswa_id)) {
			error('Jadwal atau Siswa tidak ditemukan.');
		}
		
		// Pastikan file diupload
		if (empty($_FILES['file']['name'])) {
			error('File tidak ditemukan.');
		}

		try {
			// Load library PhpSpreadsheet
			$this->load->library('upload');
			$config['upload_path']   = './upload/';
			$config['allowed_types'] = 'xlsx|xls';
			$config['max_size'] = 1536;
			$this->upload->initialize($config);
			if ($_FILES['file']['size'] > ($config['max_size'] * 1024)) {
				error('Ukuran file melebihi batas maksimum 1.5MB.');
			}

			if (!$this->upload->do_upload('file')) {
				error('File gagal diupload');
			}

			$fileData = $this->upload->data();
			$filePath = $fileData['full_path'];

			// Baca file Excel
			$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
			$sheet = $spreadsheet->getActiveSheet();
			$rows = $sheet->toArray();

			// Mulai dari baris ke-2 (baris pertama header)
			for ($i = 1; $i < count($rows); $i++) {
				$id = isset($rows[$i][0]) ? $rows[$i][0] : null; // Kolom A
				$nilai = isset($rows[$i][2]) && $rows[$i][2] !== null && $rows[$i][2] !== '' ? $rows[$i][2] : 40; // Kolom C
				if ($nilai > 100) {
					@unlink($filePath);
					error('Nilai tidak boleh lebih dari 100 pada baris ke-' . ($i + 1));
				}
				dd($rows[$i]);
				if ($id) {
					$this->db->where('id', $id)->update('tref_pertemuan_tugas', ['nilai' => $nilai]);
				}
			}

			// Hapus file setelah import
			@unlink($filePath);
			success('Data berhasil diimport.');
		} catch (\Throwable $th) {
			error('Error loading file: ' . $th->getMessage());
		}
	}

	public function egrading() {
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();
		$list_kelas = $this->Kelas_model->listKelasJadwalGuru($active_periode['id'], $session['user']['guru']['id']);

		$gradingDefault = $this->Dbhelper->selectTabel('*', 'mt_grading', [], 'nilai_max', 'DESC');
		$gradingGuru 	= $this->Dbhelper->selectTabel('*', 'mt_grading_guru', ['guru_id' => $session['user']['guru']['id']], 'nilai_max', 'DESC');
		
		$data['list_penilaian'] = [];
		$data['filter'] = [
			"jadwal_id" => ""
		];
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			$jadwal_id = isset($post['jadwal_id']) ? (int)$post['jadwal_id'] : 0;
			if ($jadwal_id > 0) {
				$data['list_penilaian'] = $this->Penilaian_model->listPenilaianGradingKelas($jadwal_id, $active_periode['id']);
				$data['filter'] = [
					'jadwal_id' => $jadwal_id
				];
			}
		}
		$data['list_grading'] = !empty($gradingGuru) ? $gradingGuru : $gradingDefault;
		$data['judul'] = 'E-Grading';
		$data['subjudul'] = 'E-Grading';
		$data['list_kelas'] = $list_kelas;
		$data['active_periode'] = $active_periode;
		$this->template->_vGuru('laporan/e_grading', $data);
	}

	public function egrading_submit() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			$session = $this->session->userdata('user_dashboard');

			$nilai_siswa 		= $post['nilai'];
			$jadwal_kelas_id 	= $post['jadwal_kelas_id'];
			$guru_id 			= $session['user']['guru']['id'];

			foreach ($nilai_siswa as $siswa_id => $nilai) {

				if (!is_null($nilai)) {
					$where = [
						'jadwal_kelas_id'	=> $jadwal_kelas_id,
						'siswa_id'			=> $siswa_id
					];
					$checkdata = $this->db->where($where)->from('tr_egrading_siswa')->get()->row_array();
					$grade = $this->Dbhelper->selectTabelOne('grade, keterangan', 'mt_grading_guru', ['guru_id' => $guru_id, 'nilai_min <=' => $nilai, 'nilai_max >=' => $nilai]);
					if (empty($grade)) {
						$grade = $this->Dbhelper->selectTabelOne('grade, keterangan', 'mt_grading', ['nilai_min <=' => $nilai, 'nilai_max >=' => $nilai]);
					}
					$append_data = [
						'nilai_akhir' 	=> $nilai,
						'grade'			=> $grade['grade'],
						'keterangan'	=> $grade['keterangan'],
						'status'		=> 'aktif',
						'updated_at'	=> date('Y-m-d H:i:s')
					];
					if (!empty($checkdata)) {
						$update = $this->db->where($where)->update('tr_egrading_siswa', $append_data);
					} else {
						$append_data['created_at'] = date('Y-m-d H:i:s');
						$insertdata = array_merge($append_data, $where);
						$insert = $this->db->insert('tr_egrading_siswa', $insertdata);
					}
				}
				
			}

			success('Data berhasil diupdate.');
		}
		badrequest('Method not allowed');
	}

	public function setting_egrading() {
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();
		$gradingDefault = $this->Dbhelper->selectTabel('*', 'mt_grading', [], 'nilai_max', 'DESC');
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			$delete = $this->db->where('guru_id', $session['user']['guru']['id'])->delete('mt_grading_guru');
			if (empty($post['submit'])) {
				$insert_batch = [];
				foreach ($post['keterangan'] as $grade => $v) {
					$insert_batch[] = [
						'guru_id' 		=> $session['user']['guru']['id'],
						'grade'			=> $grade,
						'keterangan'	=> $v,
						'nilai_min'		=> $post['nilai_min'][$grade],
						'nilai_max'		=> $post['nilai_max'][$grade]
					];
				}
				$insert = $this->db->insert_batch('mt_grading_guru', $insert_batch);
			}
			$this->session->set_flashdata('success', "Update data success");
		}
		$gradingGuru 	= $this->Dbhelper->selectTabel('*', 'mt_grading_guru', ['guru_id' => $session['user']['guru']['id']], 'nilai_max', 'DESC');
		$data['judul'] = 'Setting';
		$data['subjudul'] = 'E-Grading';
		$data['list_grading'] = !empty($gradingGuru) ? $gradingGuru : $gradingDefault;
		$this->template->_vGuru('laporan/setting_e_grading', $data);
	}

	public function absensi() {
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();
		$list_kelas = $this->Kelas_model->listKelasJadwalGuru($active_periode['id'], $session['user']['guru']['id']);

		
		$data['list_penilaian'] = [];
		$data['filter'] = [
			"jadwal_id" => ""
		];
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			$jadwal_id = isset($post['jadwal_id']) ? (int)$post['jadwal_id'] : 0;
			if ($jadwal_id > 0) {
				$data['list_penilaian'] = $this->Penilaian_model->listPenilaianByKelas($jadwal_id, $active_periode['id']);
				$data['filter'] = [
					'jadwal_id' => $jadwal_id
				];
			}
		}
		$data['judul'] = 'Rekap Absensi';
		$data['subjudul'] = 'Absensi';
		$data['list_kelas'] = $list_kelas;
		$data['active_periode'] = $active_periode;
		$this->template->_vGuru('laporan/absensi', $data);
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
