	<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Akademik extends CI_Controller {
	public function __Construct() {
		parent::__construct();
		$this->cekLogin();
		$this->load->model('Akademik_model');
		$this->load->model('kesiswaan/Siswa_model');
	}
	
	public function jadwal_pelajaran() {
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();
		$kelas_id 		= $session['user']['siswa']['current_kelas_id'];
		$semester_id 	= $active_periode['id'];
		$list_semester 	= $this->Akademik_model->find_semester_siswa($session['user']['siswa']['id']);

		$list_mapel 	= $this->Akademik_model->find_mapel_siswa($semester_id, $kelas_id, $session['user']['siswa']['id']);
		$data['judul'] 		= 'Akademik';
		$data['subjudul'] 	= 'Jadwal Pelajaran';
		$data['list_mapel'] = $list_mapel;
		$data['active_periode'] = $active_periode;
		$this->template->_vSiswa('akademik/jadwal_pelajaran', $data);
	}

	public function jadwal_pelajaran_pdf() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$session = $this->session->userdata('user_dashboard');
			$active_periode = active_periode();
			$kelas_id 		= $session['user']['siswa']['current_kelas_id'];
			$semester_id 	= $active_periode['id'];
			$siswa_id 		= $session['user']['siswa']['id'];

			$list_mapel 	= $this->Akademik_model->find_mapel_siswa($semester_id, $kelas_id, $siswa_id);
			$data['siswa'] 			= $this->Siswa_model->findSiswa($siswa_id);
			$data['list_mapel'] = $list_mapel;
			$data['active_periode'] = $active_periode;

			$view = $this->load->view('siswa/akademik/jadwal_pelajaran_pdf', $data, TRUE);

			// Load pdf library
			$this->load->library('pdf');
			$this->dompdf->loadHtml($view);			
			$this->dompdf->setPaper('A4', 'portrait');
			$this->dompdf->render();
			$this->dompdf->stream("JADWAL PELAJARAN.pdf", array("Attachment"=>0));
		}
	}

	public function nilai_semester() {
		$session = $this->session->userdata('user_dashboard');
		$active_periode = active_periode();
		$kelas_id 		= $session['user']['siswa']['current_kelas_id'];
		$semester_id 	= $active_periode['id'];		
		$list_semester 	= $this->Akademik_model->find_semester_siswa($session['user']['siswa']['id']);

		$list_nilai = [];
		$filter = [
			"periode_semester" => "",
		];
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			$explode_post = explode('_', $post['periode_semester']);
			
			$periode_id = (int)$explode_post[0];
			$semester_id = (int)$explode_post[1];
			$filter['periode_semester'] = $post['periode_semester'];

			$list_nilai = $this->Akademik_model->list_nilai_semester($semester_id, $session['user']['siswa']['id']);
			// dd($semester_id, $list_nilai);
		}

		$data['judul'] 					= 'Akademik';
		$data['subjudul'] 			= 'Nilai Semester';
		$data['list_nilai'] 		= $list_nilai;
		$data['active_periode'] = $active_periode;
		$data['list_semester'] 	= $list_semester;
		$data['filter'] 				= $filter;
		$this->template->_vSiswa('akademik/nilai_semester', $data);
	}

	public function nilai_semester_pdf() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$session = $this->session->userdata('user_dashboard');
			$post = $this->input->post();
			$explode_post = explode('_', $post['periode_semester']);
			
			$periode_id = (int)$explode_post[0];
			$semester_id = (int)$explode_post[1];
			$siswa_id 		= $session['user']['siswa']['id'];

			$active_periode = data_periode(['a.semester' => $semester_id, 'a.periode_id' => $periode_id]);
			$list_nilai = $this->Akademik_model->list_nilai_semester($semester_id, $session['user']['siswa']['id']);
			$data['siswa'] 					= $this->Siswa_model->findSiswa($siswa_id);
			$data['list_nilai'] 		= $list_nilai;
			$data['active_periode'] = $active_periode;
			$view = $this->load->view('siswa/akademik/nilai_semester_pdf', $data, TRUE);

			// Load pdf library
			$this->load->library('pdf');
			$this->dompdf->loadHtml($view);			
			$this->dompdf->setPaper('A4', 'portrait');
			$this->dompdf->render();
			$this->dompdf->stream("KHS.pdf", array("Attachment"=>0));
		}
	}

	public function rangkuman_nilai() {
		$session = $this->session->userdata('user_dashboard');

		$siswa_id 		= $session['user']['siswa']['id'];
		$list_nilai 	= $this->Akademik_model->list_nilai_siswa($siswa_id);
		$data['judul'] 		= 'Akademik';
		$data['subjudul'] 	= 'Rangkuman Nilai';
		$data['list_nilai'] = $list_nilai;
		$this->template->_vSiswa('akademik/rangkuman_nilai', $data);
	}

	public function rangkuman_nilai_pdf() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$session 						= $this->session->userdata('user_dashboard');
			$siswa_id 					= $session['user']['siswa']['id'];
			$list_nilai 				= $this->Akademik_model->list_nilai_siswa($siswa_id);
			$data['siswa'] 					= $this->Siswa_model->findSiswa($siswa_id);
			$data['list_nilai'] = $list_nilai;
			$view = $this->load->view('siswa/akademik/rangkuman_nilai_pdf', $data, TRUE);

			// Load pdf library
			$this->load->library('pdf');
			$this->dompdf->loadHtml($view);			
			$this->dompdf->setPaper('A4', 'portrait');
			$this->dompdf->render();
			$this->dompdf->stream("RANGKUMAN NILAI.pdf", array("Attachment"=>0));
		}
	}


	private function cekLogin() {
		$menu_id = 1;
		$session = $this->session->userdata('user_dashboard');
		if (empty($session)) {
			redirect('siswa/login');
		}
	}
}
