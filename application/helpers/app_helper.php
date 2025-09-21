<?php
	function dd(...$r){
		foreach ($r as $v) {
			echo "<pre>";
			print_r($v);
			echo "</pre>";
		}
		
			die;
	}

	function semesterText($semester) {
		if ($semester == 1) {
			return 'Semester Ganjil';
		} elseif ($semester == 2) {
			return 'Semester Genap';
		}
		return 'N/A';
	}

	function toSlug(...$r) {
		$slug = implode('-', $r);
		return $slug;
	}

	function fromSlug($slug) {
		$slug = explode('-', $slug);
		return $slug;
	}

	function admin_url($url) {
		return base_url().'dashboard/'.$url;
	}

	function dbClean($input){
		$inputer = trim(stripslashes(html_escape($input)));
		return $inputer;
	}

	function convMonth($vardate) {
		if($vardate!=''){
			$BulanIndo = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "Desember");
			return $BulanIndo[(int)$vardate-1];
		} else {
			return '-';
		}
	}

	function enumSiswaKelas($alphabet = '') {
		$enumSiswa = [
			'A' => 'aktif',
			'B'	=> 'nonaktif',
			'C'	=> 'transfer',
			'D'	=> 'naik_kelas',
			'E'	=> 'tinggal_kelas'
		];

		if (!empty($enumSiswa[$alphabet])) {
			return $enumSiswa[$alphabet];
		}

		return $enumSiswa;
	}

	function convDate($vardate) {
		if($vardate!=''){
			$pecah = explode("-", $vardate);

			$tahun = $pecah[0];
			$bulan = $pecah[1];
			$tgl   = $pecah[2];
			
			$BulanIndo = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "Desember");
			return $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun ;
		} else {
			return '-';
		}
	}

	function convertNumberToWord($num = false){
	    $num = str_replace(array(',', ' '), '' , trim($num));
	    if(! $num) {
	        return false;
	    }
	    $num = (int) $num;
	    $words = array();
	    $list1 = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
	        'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
	    );
	    $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
	    $list3 = array('', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
	        'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
	        'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
	    );
	    $num_length = strlen($num);
	    $levels = (int) (($num_length + 2) / 3);
	    $max_length = $levels * 3;
	    $num = substr('00' . $num, -$max_length);
	    $num_levels = str_split($num, 3);
	    for ($i = 0; $i < count($num_levels); $i++) {
	        $levels--;
	        $hundreds = (int) ($num_levels[$i] / 100);
	        $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ' ' : '');
	        $tens = (int) ($num_levels[$i] % 100);
	        $singles = '';
	        if ( $tens < 20 ) {
	            $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
	        } else {
	            $tens = (int)($tens / 10);
	            $tens = ' ' . $list2[$tens] . ' ';
	            $singles = (int) ($num_levels[$i] % 10);
	            $singles = ' ' . $list1[$singles] . ' ';
	        }
	        $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
	    } //end for loop
	    $commas = count($words);
	    if ($commas > 1) {
	        $commas = $commas - 1;
	    }
	    return trim(ucwords(implode(' ', $words)));
	}

	function random_char(){
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < 64; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }

	    return $randomString;
	}

	function session() {
		$CI =& get_instance();
		$session = $CI->session->userdata('user_dashboard');
		$user_session = $session['user'];

		return $user_session;
	}

	function is_admin() {
		$CI =& get_instance();
		$session = $CI->session->userdata('user_dashboard');
		$user_session = $session['user'];

		if ($user_session['user_group_id'] == 1) {
			return true;
		}
		return false;
	}

	function active_periode() {
		$CI =& get_instance();
		$CI->db->select("a.*, b.tahun_ajaran, c.code as status_code, c.title as status_periode");
		$CI->db->from("mt_periode_semester as a");
		$CI->db->join("mt_periode as b", "a.periode_id = b.id");
		$CI->db->join("mt_status_periode as c", "a.status = c.code", "left");
		$CI->db->where('a.is_active', 1);
		$data = $CI->db->get()->row_array();
		return $data;
	}

	function data_periode($where) {
		$CI =& get_instance();
		$CI->db->select("a.*, b.tahun_ajaran, c.code as status_code, c.title as status_periode");
		$CI->db->from("mt_periode_semester as a");
		$CI->db->join("mt_periode as b", "a.periode_id = b.id");
		$CI->db->join("mt_status_periode as c", "a.status = c.code", "left");
		$CI->db->where($where);
		$data = $CI->db->get()->row_array();
		return $data;
	}

	function wali_kelas($periode_id, $guru_id) {
		$CI =& get_instance();
		$CI->db->select("*");
		$CI->db->from("tref_kelas");
		$CI->db->where('periode_id', $periode_id);
		$CI->db->where('guru_id', $guru_id);
		$data = $CI->db->get()->result_array();
		return $data;
	}

	function find_pertemuan($jadwal_kelas_id, $pertemuan_ke = "", $return_as = "row") {
		$CI =& get_instance();
		$CI->db->from('tref_pertemuan');
		$CI->db->where('jadwal_kelas_id', $jadwal_kelas_id);
		if (!empty($pertemuan_ke)) {
			$CI->db->where('pertemuan_ke', $pertemuan_ke);
		}
		$query = $CI->db->get();

		if ($return_as == "array") {
			return $query->result_array();
		}
		return $query->row_array();
	}

	function menu_data($session, $is_config = 0) {
		$CI =& get_instance();

		$user_access = [];
		$CI->db->select("id, name, routes, icon");
		$CI->db->where('is_config', $is_config);
		$CI->db->where('menu_parent_id', 0);
		// $CI->db->where_in("id", $user_access);
		$CI->db->where('deleted_at IS NULL');
		$CI->db->order_by("precedence", "ASC");
		$data = $CI->db->get('m_menu')->result_array();
		
		$html = '';
		foreach ($data as $menu) {
			$id 		= $menu['id'];
			$name 		= $menu['name'];
			$url 		= base_url($menu['routes']);
			$icon 		= $menu['icon'];
			$is_parent 	= 0;
			$has_treeview	= "";

			$data_child = menu_data_child($id, $user_access);
			if (count($data_child) > 0) {
				$is_parent 		= 1;
				$url 			= "javascript:void(0)";
				$has_treeview 	= "has-treeview";
				$name 			.= ' <i class="right fas fa-angle-left"></i>';
			}
			$html .= '<li class="nav-item '.$has_treeview.'">';
				$html .= '<a href="'.$url.'" class="nav-link">';
              		$html .= '<i class="nav-icon fas '.$icon.'"></i>';
              		$html .= '<p>'.$name.'</p>';
            	$html .= '</a>';
            	if ($is_parent == 1) {
            		$html .= '<ul class="nav nav-treeview">';
            		foreach ($data_child as $menu_child) {
            			$name 		= $menu_child['name'];
						$url 		= base_url($menu_child['routes']);
						$icon 		= $menu_child['icon'];
            			$html .= '<li class="nav-item">';
		                	$html .= '<a href="'.$url.'" class="nav-link">';
		                    	$html .= '<i class="nav-icon far '.$icon.'"></i>';
		                    	$html .= '<p>'.$name.'</p>';
		                  	$html .= '</a>';
		                $html .= '</li>';
            		}
            		$html .= '</ul>';
            	}
	        $html .= '</li>';
		}

		return $html;
	}

	function menu_data_child($menu_parent_id, $user_access) {
		$CI =& get_instance();

		$CI->db->select("name, routes, icon");
		$CI->db->where('menu_parent_id', $menu_parent_id);
		// $CI->db->where_in("id", $user_access);
		$CI->db->where('deleted_at IS NULL');
		$CI->db->order_by("precedence", "ASC");
		$data = $CI->db->get('m_menu')->result_array();

		return $data;
	}

	function notifikasi(){
		$CI =& get_instance();

		$CI->db->select('*');
		$CI->db->where('is_read', 0);
		$CI->db->order_by('id','desc');
		$query = $CI->db->get('mt_notifikasi');
		
		return $query->result_array();
	}

	function setting_lms($where = []) {
		$CI =& get_instance();
		$CI->db->from('mt_setting_lms');
		if ($where) {
			$CI->db->where($where);
		}
		$query = $CI->db->get();
		return !empty($where) ? $query->row_array() : $query->result_array();
	}

	function mata_pelajaran($where = [], $column = "*") {
		$CI =& get_instance();
		$CI->db->select($column);
		$CI->db->from('mt_mata_pelajaran');
		if ($where) {
			$CI->db->where($where);
		}
		$query = $CI->db->get();
		return !empty($where) ? $query->row_array() : $query->result_array();
	}

	function send_email($to, $subject, $message) {
		$CI = &get_instance();
		$CI->load->library('email');

		$CI->email->initialize(array(
		  'protocol' => 'smtp',
		  'smtp_host' => '',
		  'smtp_user' => '',
		  'smtp_pass' => '',
		  'smtp_port' => 587,
		  'crlf' => "\r\n",
		  'newline' => "\r\n"
		));

		$CI->email->set_mailtype('html');
		$CI->email->from('no-reply@basecode.com', 'Basecode CI');
		$CI->email->to($to);
		// $CI->email->cc('another@another-example.com');
		// $CI->email->bcc('them@their-example.com');
		$CI->email->subject($subject);
		$CI->email->message($message);
		if ($CI->email->send()) {
			// debugCode("Berhasil");
			return "Berhasil";
		} else {
			// debugCode("GAGAL");
		}
		return "Gagal";
	}
?>