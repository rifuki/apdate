<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BusinessUnit extends CI_Controller {
	var $menu_id = "";
	var $session_data = "";
	public function __Construct() {
		parent::__construct();
		$this->menu_ids = ['M006'];
		$this->session_data = $this->session->userdata('user_dashboard');

		cekAkses($this->menu_ids);
		$this->own_link = admin_url('master/business-unit');
		$this->load->model('BusinessUnit_model', 'BusinessUnit');
	}

	public function index() {

		$data['title'] 			= 'DATA BUSINESS UNIT';
		$data['user']				= user_session();

		$this->template->_v('master/business_unit/index', $data);
	}

	public function find() {	
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			
			if (empty($post['CODE'])) {
				error('Post CODE not found');
			}
			$where = [
				"A.CODE"	=> $post['CODE'],
			];
			$find = $this->BusinessUnit->find($where);
			if ($find) {
				success("Find data success", $find);
			}
			error("Find data failed");
		}
		badrequest("Access denied");
	}

	public function do_update() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();
			if (empty($post['code'])) {
				error('Code not found');
			}
			$where = [
				"HEAD_CODE"	=> "AB",
				"CODE"			=> $post['code']
			];

			try {
				// UPDATE CD CODE USE_YN
				$useYN = !empty($post['is_active']) ? 'Y' : 'N';
				$this->db->trans_start();
				$this->db->trans_strict();

				// Update CD_CODE
				$this->db->where($where);
				$this->db->update('CD_CODE', ['USE_YN' => $useYN]);
				if (!$this->db->trans_status()) {
					$this->db->trans_rollback();
					throw new Exception('Update status business unit gagal, silahkan coba lagi.');
				}

				if (!empty($post['area_type'])) {
					// Insert or update CD_AREA_UNIT
					$find = $this->db->where($where)->from('CD_AREA_UNIT')->get()->row_array();
					if (empty($find)) {
						$insert = array_merge($where, ["AREA_TYPE" => $post['area_type']]);
						$this->db->insert('CD_AREA_UNIT', $insert);
					} else {
						$this->db->where($where);
						$this->db->update('CD_AREA_UNIT', ['AREA_TYPE' => $post['area_type']]);
					}
				}


				if (!$this->db->trans_status()) {
					$this->db->trans_rollback();
					throw new Exception('Update unit area gagal, silahkan coba lagi.');
				}
				$this->db->trans_commit();
				success('Update data success');
			} catch (\Throwable $th) {
				error($th->getMessage());
			}
		}
		badrequest("Access denied");
	}

	public function datatables() {
		$list = $this->BusinessUnit->get_datatables();
		$data = array();
		$no = isset($_POST['start']) ? $_POST['start'] : 0;
		foreach ($list as $field) {
				$no++;
				$row_menu = array();
				$row_menu[] = $field->HEAD_CODE;
				$row_menu[] = $field->CODE;
				$row_menu[] = $field->CODE_NAME;
				$row_menu[] = $field->USE_YN;
				$row_menu[] = !empty($field->AREA_TYPE) ? 'AREA '.$field->AREA_TYPE : '';
				$row_menu[] = $field->DESC4;

				$btn_update = '<button type="button" class="btn btn-info btn-sm btn-flat btnUpdate" data-code="'.$field->CODE.'"><i class="fas fa-edit"></i></button>';
				$action = $btn_update;
				$row_menu[] = $action;
				$data[] = $row_menu;
		}

		$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->BusinessUnit->count_all(),
				"recordsFiltered" => $this->BusinessUnit->count_filtered(),
				"data" => $data,
		);
		//output dalam format JSON
		echo json_encode($output);
	}
}

