<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	public function __Construct() {
		parent::__construct();
	}

	public function index() {
		redirect('dashboard');
		// $this->template->_vFront('index', );
	}

	public function testEmail() {
		$html = "<h2>INI ADALAH PESANNYA</h2>";
		send_email("muhichsan67@gmail.com", "TESTING EMAIL NUSADEWATA", $html);
	}
}
