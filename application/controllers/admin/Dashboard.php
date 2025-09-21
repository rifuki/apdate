<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	public function __Construct() {
		parent::__construct();
		$this->cekLogin();
	}

	public function index() {
		
		$informasi = $this->db->order_by('created_at', 'DESC')->get('mt_informasi')->result_array();
		$data['active_periode'] = active_periode();
		$data['informasi'] = $informasi;
		$data['judul'] = 'Dashboard';
		$data['subjudul'] = 'Index';
		$this->template->_v('index', $data);
	}

	public function tes_pdf(){
        
        // Get output html
        $html = "
        	<div>
				<center>
					<h2><strong>NUSA DEWATA SURF & CHARTERS</strong></h2>
					<h5>
						Jl. Singgalang V Rt:006/003 Gunung Pangilun, Padang, West Sumatra, Indonesia 25138
						<br>
						Ph/fax: 62 751 4481123 mobile: 62 8116667728
						<br>
						Email: nusadewatasurf@yahoo.com.au
						<br>
						www.nusadewatasurf.com
					</h5>
					<h3><strong>RECEIPT</strong></h3>
				</center>
				<table width='100%'>
					<tr>
						<td>Trip</td>
						<td>:</td>
						<td>ND12/12 July - Augst 4 2020</td>
					</tr>
					<tr>
						<td>No</td>
						<td>:</td>
						<td>ND12/FP01/2020</td>
					</tr>
					<tr>
						<td colspan='4' align='right'><em>Amount</em></td>
					</tr>
					<tr>
						<td>Receipt From</td>
						<td>:</td>
						<td style='border-bottom:1px solid black'>ABC</td>
						<td align='right'><span style='border: 1px solid black; padding: 10px;'>250 USD</span></td>
					</tr>
					<tr>
						<td>Amount in Words</td>
						<td>:</td>
						<td style='border-bottom:1px solid black'>Fifty Thousand Dollar</td>
					</tr>
					<tr>
						<td>Purpose</td>
						<td>:</td>
						<td style='border-bottom:1px solid black'>Down Payment received and confirmed on 30 January 2020</td>
					</tr>
					<tr><td colspan='4' align='right'>&nbsp;&nbsp;&nbsp;</td></tr>
					<tr>
						<td>Reminder</td>
						<td>:</td>
						<td>Down Payment received and confirmed on 30 January 2020</td>
					</tr>
					<tr>
						<td>Term</td>
						<td>:</td>
						<td>-</td>
					</tr>
					<tr>
						<td>Rate</td>
						<td>:</td>
						<td>USD $</td>
					</tr>
					<tr><td colspan='4' align='right'>&nbsp;&nbsp;&nbsp;</td></tr>
					<tr><td colspan='4' align='right'>&nbsp;&nbsp;&nbsp;</td></tr>
					<tr>
						<td colspan='4' align='right'><em>Padang, 10 February 2020</em></td>
					</tr>
					<tr><td colspan='4' align='right'>&nbsp;&nbsp;&nbsp;</td></tr>
					<tr><td colspan='4' align='right'>&nbsp;&nbsp;&nbsp;</td></tr>
					<tr><td colspan='4' align='right'>&nbsp;&nbsp;&nbsp;</td></tr>
					<tr>
						<td colspan='4' align='right'><em><u>Siti Sholikhah Dale</u></em></td>
					</tr>
				</table>
			</div>
		";
        
        // Load pdf library
        $this->load->library('pdf');
        
        // Load HTML content
        $this->dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation
        $this->dompdf->setPaper('A4', 'landscape');
        
        // Render the HTML as PDF
        $this->dompdf->render();
        
        // Output the generated PDF (1 = download and 0 = preview)
        $this->dompdf->stream("welcome.pdf", array("Attachment"=>0));
    }
	
	private function cekLogin() {
		$menu_id = 1;
		$session = $this->session->userdata('user_dashboard');
		if (empty($session)) {
			redirect('login_dashboard');
		}

		// $user_access = $session['user_access'];
		// if (!in_array($menu_id, $user_access)) {
		// 	redirect('login_dashboard');
		// }
	}
}
