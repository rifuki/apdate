<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller {
	public function __Construct() {
		parent::__construct();
	}

	public function search_siswa() {
		$this->load->model('kesiswaan/Siswa_model');
		$search = $this->input->get('search');
		$result = $this->Siswa_model->get_siswa_by_search($search);

		$data = [];
		foreach ($result as $row) {
			$data[] = [
				'id' => $row->id,
				'text' => $row->nisn.' - '.$row->nama.' ('.$row->kelas.')'
			];
		}

		echo json_encode($data);
	// Set header ke application/json agar browser tahu ini adalah response JSON
		// $this->output->set_content_type('application/json')->set_output(json_encode(['items' => $data]));
	}
}
