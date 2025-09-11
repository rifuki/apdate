<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backup extends CI_Controller {
	var $menu_id = "";
	var $session_data = "";
	var $user_access_detail = "";
	var $table = "";
	var $judul = "";
	public function __Construct() {
		parent::__construct();
		$this->menu_id = 43;
		$this->session_data = $this->session->userdata('user_dashboard');
		// $this->user_access_detail = $this->session_data['user_access_detail'];

		$this->cekLogin();
		$this->own_link = admin_url('backup');
		$this->judul = "Backup Database";
	}

	public function index() {

		$data['judul'] 		= $this->judul;
		$data['subjudul'] 	= 'Backup Database';
		$data['own_link'] 	= $this->own_link;
		$list_backup = [
			'Data Siswa',
			'Data Guru',
			'Data Kelas',
			'Data Mapel',
			'Data User',
			'Data Informasi',
			'Data Dokumen',
			'Data Absensi',
			'Data Tugas',
			'Data Pertemuan',
			'Data Nilai',
			'Data Raport'
		];
		$data['list_backup'] = $list_backup;
		$this->template->_v('backup_index', $data);
	}

	public function do_backup() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$this->load->dbutil();
			$prefs = array(
			        'format'      => 'zip',             // gzip, zip, txt
			        'filename'    => 'backup_db_'.date('Ymd_His').'.sql'
			      );
			$backup = $this->dbutil->backup($prefs); 

			$this->load->helper('file');
			$file_name = 'backup_db_'.date('Ymd_His').'.zip';
			// write_file('./uploads/backup/'.$file_name, $backup); 

			$this->load->helper('download');
			force_download($file_name, $backup);
			exit;
		}
		$this->session->set_flashdata('error', "Invalid request method");
		return redirect($this->own_link);
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
}
