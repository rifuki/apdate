<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Alumni extends CI_Controller {
	var $menu_id = "";
	var $session_data = "";
	var $user_access_detail = "";
	var $table = "";
	var $judul = "";
	public function __Construct() {
		parent::__construct();
		$this->menu_id = 8;
		$this->session_data = $this->session->userdata('user_dashboard');
		// $this->user_access_detail = $this->session_data['user_access_detail'];

		$this->cekLogin();
		$this->own_link = admin_url('kesiswaan/alumni');
		$this->load->model('kesiswaan/Siswa_model');
		$this->table = "mt_users_siswa";
		$this->judul = "Siswa";
	}

	public function index() {
		$active_periode = active_periode();
		$user_access_detail = $this->user_access_detail;
		// $is_create 			= $user_access_detail[$this->menu_id]["is_create"];
		$is_create = 1;

		$data['judul'] 		= $this->judul;
		$data['subjudul'] 	= 'List Data';
		$data['own_link'] 	= $this->own_link;
		$data['is_create'] 	= $is_create;
		$data['active_periode'] = $active_periode;
		$this->template->_v('kesiswaan/alumni/index', $data);
	}

	public function datatables() {
		$user_access_detail = $this->user_access_detail;
		$list = $this->Siswa_model->get_datatables(1);
        $data = array();
        $no = isset($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $field) {
            $no++;
            $row_menu = array();
            $row_menu[] = $no;
            $row_menu[] = $field->nisn;
            $row_menu[] = $field->nama;
            $row_menu[] = $field->tanggal_lahir;
            $row_menu[] = 'Tahun Ajaran '.$field->tahun_ajaran;
            $data[] = $row_menu;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Siswa_model->count_all(1),
            "recordsFiltered" => $this->Siswa_model->count_filtered(1),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
	}

	// CHANGE NECESSARY POINT
	private function toExcel($data = null) {
		$spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $styleBoldCenter = [
		    'font' => [
		        'bold' => true,
		        'size' => 12
		    ],
		    'alignment' => [
		        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
		    ],
		    'borders' => [
		        'allBorders' => [
		            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
		            'color' => ['argb' => '00000000'],
		        ],
		    ],
		];

		$styleBorder = [
		    'borders' => [
		        'allBorders' => [
		            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
		            'color' => ['argb' => '00000000'],
		        ],
		    ],
		];
		$rowCell = 1;
		$sheet->setCellValue("A".$rowCell, "NO");
		$sheet->setCellValue("B".$rowCell, "NISN");
		$sheet->setCellValue("C".$rowCell, "NAMA");
		$sheet->setCellValue("D".$rowCell, "TANGGAL LAHIR");
		$sheet->setCellValue("E".$rowCell, "PERIODE BERGABUNG");
		$sheet->setCellValue("F".$rowCell, "KELAS SAAT INI");

		$sheet->getStyle('A'.$rowCell.':F'.$rowCell)->applyFromArray($styleBorder);
		$rowCell++;
        $no = 1;
        if (!empty($data)) {
        	foreach ($data as $items) {
	        	$item = (object) $items;
	        	$sheet->setCellValue("A".$rowCell, $no);
	        	$sheet->setCellValue("B".$rowCell, $item->nisn);
	        	$sheet->setCellValue("C".$rowCell, $item->nama);
	        	$sheet->setCellValue("D".$rowCell, date('Y-m-d', strtotime($item->tanggal_lahir)));
	        	$sheet->setCellValue("E".$rowCell, $item->tahun_ajaran);
	        	$sheet->setCellValue("F".$rowCell, $item->kelas);

	        	$sheet->getStyle('A'.$rowCell.':F'.$rowCell)->applyFromArray($styleBorder);

	        	$no++;
		        $rowCell++;
	        }
        }
        
        $writer = new Xlsx($spreadsheet);
 	
 		$today = strtotime('now');
        $filename = 'data-siswa-'.$today;
 
        ob_end_clean();
 
        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
	}

	private function toPDF($kode_klasifikasi, $data = null) {
		$header_html = '
			<center>
				<h2><strong>Daftar Draft Arsip '.$kode_klasifikasi->name.' - '.$kode_klasifikasi->description.'</strong></h2>
				<h3><strong>LIPI BOGOR</strong></h3>
			</center>
		';
		$table = '';
		if (!empty($data)) {
			$no = 1;
			$table = '<table width="100%"" style="border-collapse: collapse">';
			$header_table = '
				<tr>
					<td align="center" rowspan="2" style="border:1px solid black;">NO</td>
					<td align="center" rowspan="1" colspan="3" style="border:1px solid black;">JENIS/SERI ARSIP</td>
					<td align="center" rowspan="2" style="border:1px solid black;">TINGKAT PERKEMBANGAN</td>
					<td align="center" rowspan="2" style="border:1px solid black;">KURUN WAKTU</td>
					<td align="center" rowspan="2" style="border:1px solid black;">JUMLAH (LEMBAR)</td>
					<td align="center" rowspan="2" style="border:1px solid black;">KETERANGAN NASIB AKHIR</td>
				</tr>
				<tr>
					<td align="center" style="border:1px solid black;">KODE KLASIFIKASI</td>
					<td align="center" style="border:1px solid black;">URAIAN INFORMASI BERKAS</td>
					<td align="center" style="border:1px solid black;">URAIAN INFORMASI ARSIP</td>
				</tr>
			';
			$table .= $header_table;
			$body_table = '';
			foreach ($data as $items) {
				$item = (object) $items;

	        	$lembar = "0 Lembar";
	        	if (is_numeric($item->jumlah_lembar) && $item->jumlah_lembar > 0) {
	        		$lembar = $item->jumlah_lembar." Lembar";
	        	}

	        	$body_table .= '<tr>';
	        		$body_table .= '<td align="center" style="border:1px solid black;">'.$no.'</td>';
	        		$body_table .= '<td align="center" style="border:1px solid black;">'.$kode_klasifikasi->name.'</td>';
	        		$body_table .= '<td align="center" style="border:1px solid black;">'.$kode_klasifikasi->description.'</td>';
	        		$body_table .= '<td align="center" style="border:1px solid black;">'.$item->uraian_informasi_arsip.'</td>';
	        		$body_table .= '<td align="center" style="border:1px solid black;">'.$item->tingkat_perkembangan.'</td>';
	        		$body_table .= '<td align="center" style="border:1px solid black;">'.$item->kurun_waktu.'</td>';
	        		$body_table .= '<td align="center" style="border:1px solid black;">'.$lembar.'</td>';
	        		$body_table .= '<td align="center" style="border:1px solid black;">'.$item->keterangan.'</td>';
	        	$body_table .= '</tr>';

	        	$no++;
	        }
	        $table .= $body_table;
			$table .= "</table>";
			unset($body_table);
			unset($header_table);
		}
		$html = $header_html.$table;
		$today = strtotime('now');
        $filename = 'arsip-'.$kode_klasifikasi->name.'-'.$today;
        
        // Load pdf library
        $this->load->library('pdf');
        
        // Load HTML content
        $this->dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation
        $this->dompdf->setPaper('A4', 'landscape');
        
        // Render the HTML as PDF
        $this->dompdf->render();
        
        // Output the generated PDF (1 = download and 0 = preview)
        $this->dompdf->stream($filename.".pdf", array("Attachment"=>0));
	}

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

	private function validation($post_data) {
		$errMessage 	= [];
		$id 			= $post_data["id"];
		// $name			= $post_data["name"];

		if (!empty($id)) {
			$data = $this->Siswa_model->find($id);
			if (empty($data)) {
				$this->session->set_flashdata('error', "Data not found");
	        	return redirect($this->own_link);
	        }
		}

		// if (empty($name)) {
		// 	$errMessage[] = "Name is required";
		// }

		return $errMessage;
	}
	
	private function privilege($field, $id = null) {
		// $user_access_detail = $this->user_access_detail;
		// if ($user_access_detail[$this->menu_id][$field] != 1) {
		// 	$this->session->set_flashdata('error', "Access denied");
        // 	return redirect($this->own_link);
        // }
	}
}
