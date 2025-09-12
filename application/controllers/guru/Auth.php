<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
	public function __Construct() {
		parent::__construct();
	}

	public function index() {
		redirect('home');
	}

	function login(){
		$session_dashboard = $this->session->userdata('user_dashboard');
		// dd($session_dashboard);
		if(!empty($session_dashboard)){
			return redirect('guru/dashboard');
		}
		if ($this->input->post('username')) {
			$username 		= dbClean($this->input->post('username'));
			$password 	= dbClean($this->input->post('password'));
			$user = $this->Dbhelper->selectTabel('id, user_group_id, username, name, email, password', 'm_users', array('username'=>$username, 'user_group_id' => 2));
			if (count($user) < 1) {
				$this->session->set_flashdata('alert', 'User account not found');
			} elseif (count($user) > 0 && password_verify($password, $user[0]['password'])) {
				$user_id 		= $user[0]['id'];
				$user_group_id	= $user[0]['user_group_id'];
				$user_name 		= $user[0]['name'];
				$email 			= $user[0]['email'];

				$guru = $this->Dbhelper->selectTabelOne('*', 'mt_users_guru', array('users_id' => $user_id));

				$menu_access = $this->Dbhelper->selectTabel('menu_access', 'm_users_group', array('id_grup' => $user_group_id));

				$user_access = [];
				if (count($menu_access) > 0 && !empty($menu_access[0]['menu_access'])) {
					$user_access = explode(',', $menu_access[0]['menu_access']);
					$user_access = array_map('trim', $user_access);
				}

				$data["user"] = array(
					"id"			=> $user_id,
					"user_group_id"	=> $user_group_id,
					"name" 			=> $user_name,
					"email"			=> $email,
					"guru"			=> $guru
				);
				$data["user_access"] = $user_access;
				$data['role'] = 'guru';
				// $data["user_access_detail"] = $user_access_detail;

				if (!empty($this->session->userdata('user_dashboard'))) {
					$this->session->sess_destroy();
				}

				$session['user_dashboard'] = $data;
				$this->session->set_userdata($session);

				$session = $this->session->userdata('user_dashboard');

				return redirect('guru');
			} else {
				$this->session->set_flashdata('alert', 'Email or password incorrect');
			}
		}

		$this->load->view('guru/login');
	}


	function logout(){
		$session = $this->session->userdata('user_dashboard');
		if (empty($session)) {
			$this->session->sess_destroy();
			redirect('guru');
		}
		$this->session->sess_destroy();
		redirect('guru/login');
	}
	
}
