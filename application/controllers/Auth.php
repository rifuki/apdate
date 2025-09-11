<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
	public function __Construct() {
		parent::__construct();
	}

	public function index() {
		redirect('home');
	}

	function login_dashboard(){
		$session_dashboard = $this->session->userdata('user_dashboard');
		// dd($session_dashboard);
		if(!empty($session_dashboard)){
			return redirect('dashboard');
		}
		if ($this->input->post('username')) {
			$username 		= dbClean($this->input->post('username'));
			$password 	= dbClean($this->input->post('password'));
			$user = $this->Dbhelper->selectTabel('id, user_group_id, username, name, email, password', 'm_users', array('username'=>$username));
			if (count($user) < 1 || in_array($user[0]['user_group_id'], [2, 3])) {
				$this->session->set_flashdata('alert', 'User account not found');
			} elseif (count($user) > 0 && password_verify($password, $user[0]['password'])) {
				$user_id 		= $user[0]['id'];
				$user_group_id	= $user[0]['user_group_id'];
				$user_name 		= $user[0]['name'];
				$email 			= $user[0]['email'];

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
					"email"			=> $email
				);
				$data["user_access"] = $user_access;
				$data['role'] = 'admin';
				// $data["user_access_detail"] = $user_access_detail;

				if (!empty($this->session->userdata('user_dashboard'))) {
					$this->session->sess_destroy();
				}

				$session['user_dashboard'] = $data;
				$this->session->set_userdata($session);

				$session = $this->session->userdata('user_dashboard');

				return redirect('dashboard');
			} else {
				$this->session->set_flashdata('alert', 'Email or password incorrect');
			}
		}

		$this->load->view('admin/login');
	}

	function user(){
		$email = dbClean($this->input->post('email'));
		$password = dbClean($this->input->post('password'));
		$user = $this->Dbhelper->selectTabel('*', 'mt_user',array('email'=>$email, 'password'=>md5($password)));
		if (count($user) > 0) {
			if ($user[0]['is_verified'] == 0) {
				$this->session->set_flashdata('failed', 'Your account is not verified yet, please check your email to verifiy');
			} else {
				$new_session = array(
					'user_id'		=> $user[0]['id'],
					'user_name'		=> $user[0]['name'],
					'user_level'	=> $user[0]['email'],
					'is_backend'	=> 0,
				);
				$this->session->set_userdata($new_session);

				if ($this->session->userdata('current_trip') != null && $this->session->userdata('current_trip') != 0) {
					$id = $this->session->userdata('current_trip');
					redirect('trip/'.$id);
				}
			}
			// debugCode($this->session->all_userdata());
		} else {
			$this->session->set_flashdata('failed', 'Email or password is incorrect');
		}
		redirect('home');
	}

	function register(){
		$email	=	dbClean($this->input->post('email'));
		$user = $this->Dbhelper->selectTabel('*', 'mt_user',array('email'=>$email));
		if (count($user) < 1) {
			$token = random_char();
			$userArray = array(
				"name"			=> dbClean($this->input->post('name')),
				"email"			=> dbClean($this->input->post('email')),
				"password"		=> md5(dbClean($this->input->post('password'))),
				"password_raw"	=> dbClean($this->input->post('password')),
				"phone"			=> dbClean($this->input->post('phone')),
				"address"		=> dbClean($this->input->post('address')),
				"created_date"	=> date('Y-m-d H:i:s'),
				"is_verified"	=> 0,
				"token"			=> $token,
			);

			if ($userArray['name'] == "" || $userArray['email'] == "" || $userArray['password'] == "" || $userArray['phone'] == "" || $userArray['address'] == "") {
				$this->session->set_flashdata('failed', 'Please check your data again');
				redirect('home');
			}

			$save = $this->Dbhelper->insertData('mt_user', $userArray);
			if ($save) {
				$to = $email;
				$subject = "Verify Account";
				$message = "Plese click link below to verify your registry account";
				$message .= "<br/> <a href='".base_url()."token/".$token."'><h3>Verify Account</h3></a>";
				// debugCode($message);
				$send = send_email($email, $subject, $message);
				// debugCode($send);
				
				$uraian = $userArray['name'].' mendaftar sebagai user baru pada tanggal '.convDate(date('Y-m-d')).' dengan menggunakan email '.$to;
				$notifArray = array(
					"nama"			=> "New User",
					"uraian"		=> $uraian,
					"tanggal"		=> date('Y-m-d'),
					"is_read"		=> 0,
				);
				$notification = $this->Dbhelper->insertData('mt_notifikasi', $notifArray);

				$this->session->set_flashdata('success', 'Registration success, please check your inbox/spam email to verification your account');
				redirect('home');
			} else {
				$this->session->set_flashdata('failed', 'Please check your data again');
				redirect('home');
			}
		} else {
			$this->session->set_flashdata('failed', 'Email already used');
			redirect('home');
		}
	}

	function token($token) {
		$user = $this->Dbhelper->selectTabel('*', 'mt_user', array('token'=>$token, 'is_verified'=>0));
		if (count($user) < 1) {
			$this->session->set_flashdata('failed', 'Token invalid or account has already verified');
		} else {
			$update = $this->Dbhelper->updateData('mt_user', array('token'=>$token), array('is_verified'=>1));
			if ($update) {

				$uraian = $user[0]['name'].' sudah memverifikasikan diri melalui email pada tanggal '.convDate(date('Y-m-d'));
				$notifArray = array(
					"nama"			=> "Verified New User",
					"uraian"		=> $uraian,
					"tanggal"		=> date('Y-m-d'),
					"is_read"		=> 0,
				);
				$notification = $this->Dbhelper->insertData('mt_notifikasi', $notifArray);

				$this->session->set_flashdata('success', 'Verify account success, thank you.');
			} else {
				$this->session->set_flashdata('failed', 'Verify account is failed, please contact owner to check the problem');
			}
		}

		redirect('home');
	}

	function logout(){
		$session = $this->session->userdata('user_dashboard');
		if (empty($session)) {
			$this->session->sess_destroy();
			redirect('home');
		}
		$this->session->sess_destroy();
		redirect('login_dashboard');
	}
	
}
