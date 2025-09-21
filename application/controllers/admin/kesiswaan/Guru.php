<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Guru extends CI_Controller {
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
		$this->own_link = admin_url('kesiswaan/guru');
		$this->load->model('kesiswaan/Guru_model');
		$this->table = "mt_users_guru";
		$this->judul = "Guru";
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
		$this->template->_v('kesiswaan/guru/index', $data);
	}

	public function datatables() {
		$user_access_detail = $this->user_access_detail;
		$list = $this->Guru_model->get_datatables();
        $data = array();
        $no = isset($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $field) {
            $no++;
            $row_menu = array();
            $row_menu[] = $no;
            $row_menu[] = $field->nip;
            $row_menu[] = $field->nama;
            $row_menu[] = $field->email;
            $row_menu[] = $field->nomor_hp;
            $row_menu[] = 'Tahun Ajaran '.$field->tahun_ajaran;
            // $row_menu[] = $field->is_active == 1 ? '<span class="p-2 bg-success">Aktif</span>' : '<span class="p-2 bg-secondary">Non Aktif</span>';

            $btn_update = "";
            $btn_delete = "";
        	// if ($user_access_detail[$this->menu_id]['is_update'] == 1) {
            	$btn_update = '<a href="'.$this->own_link.'/edit/'.$field->id.'" class="btn btn-info btn-sm btn-flat mb-2 mb-sm-0"><i class="fas fa-edit"></i></a>';
            // }
            // if ($user_access_detail[$this->menu_id]['is_delete'] == 1) {
            	$btn_delete = '<a onclick="deleteConfirm(`'.$field->id.'`)" data-id="'.$field->id.'" href="javascript:void(0)" class="btn btn-danger btn-sm btn-flat delete"><i class="fas fa-trash"></i></a>';
            // }
            $action = $btn_update." ".$btn_delete;
            $row_menu[] = $action;
            $data[] = $row_menu;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Guru_model->count_all(),
            "recordsFiltered" => $this->Guru_model->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
	}

	public function create() {
		$this->privilege('is_create');
		$periode = $this->Dbhelper->selectTabelOne('id, tahun_ajaran', 'mt_periode', array("is_active" => 1));

		$data['judul'] 		= $this->judul;
		$data['subjudul'] = 'Create Data';
		$data['own_link'] = $this->own_link;

		$data['action']		= "do_create";
		$data['periode']	= $periode;

		$this->template->_v('kesiswaan/guru/form', $data);
	}

	public function edit($id) {
		$id = (int) $id;
		$this->privilege('is_update');
		$model = $this->Guru_model->find($id);
		if (empty($model)) {
			$this->session->set_flashdata('error', "Data not found");
			return redirect($this->own_link);
		}

		$model_mapel = $this->Guru_model->find_mapel($id, true);
		$periode = $this->Dbhelper->selectTabelOne('id, tahun_ajaran', 'mt_periode', array("id" => $model->join_periode_id));
		$mata_pelajaran = $this->Dbhelper->selectTabel('id, code, name', 'mt_mata_pelajaran', array("is_active" => 1), 'code', 'ASC');
		$data['judul'] 			= $this->judul;
		$data['subjudul'] 		= 'Edit Data';
		$data['own_link'] 		= $this->own_link;
		$data['periode']		= $periode;


		$data['model']			= $model;
		$data['model_mapel']			= $model_mapel;
		$data['mata_pelajaran'] = $mata_pelajaran;
		$data['action']			= "do_update";

		$this->template->_v('kesiswaan/guru/form', $data);
	}

	public function do_update() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();

			$id = (int) $post["id"];
			$this->privilege('is_update');
			$post_data = [];
			foreach ($post as $key => $value) {
				if (is_array($value)) {
					continue;
				}
				$val = dbClean($value);
				$post_data[$key] = $value;
			}
			$validation 	= $this->validation($post_data);
			$htmlMessage 	= "";
			if (count($validation) > 0) {
				$htmlMessage .= "<ul>";
				foreach ($validation as $value) {
					$htmlMessage .= "<li>".$value."</li>";
				}
				$htmlMessage .= "</ul>";
				$this->session->set_flashdata('alert', $htmlMessage);
				return redirect($this->own_link."/edit/".$id);
			}

			$insert_batch = [];
			
			if (!empty($post['mata_pelajaran_ids'])) {
				$active_periode = active_periode();
				if ($active_periode['status'] != "1.1") {
					$this->session->set_flashdata('error', "Gagal update karena periode saat ini belum dalam status Assign Mata Pelajaran (1.1)");
					return redirect($this->own_link."/edit/".$id);
				}

				foreach ($post['mata_pelajaran_ids'] as $v) {
					$insert_batch[] = [
						'guru_id'	=> $id,
						'mata_pelajaran_id'		=> $v
					];
				}
				
			}

			$this->db->delete('tref_guru_mata_pelajaran', array('guru_id' => $id));
			if (!empty($insert_batch)) {
				$save = $this->db->insert_batch('tref_guru_mata_pelajaran', $insert_batch);	
			}

			$save = $this->Dbhelper->updateData($this->table, array('id'=>$id), $post_data);
			if ($save) {
				$this->session->set_flashdata('success', "Update data success");
				return redirect($this->own_link."/edit/".$id);
			}
			$this->session->set_flashdata('error', "Update data failed");
			return redirect($this->own_link."/edit/".$id);
		}
		$this->session->set_flashdata('error', "Access denied");
        return redirect($this->own_link);
	}

	public function do_create() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();

			$id = (int) $post["id"];
			$this->privilege('is_create');
			$post_data = [];
			foreach ($post as $key => $value) {
				$val = dbClean($value);
				$post_data[$key] = $val;
			}
			$validation 	= $this->validation($post_data);
			$htmlMessage 	= "";
			if (count($validation) > 0) {
				$htmlMessage .= "<ul>";
				foreach ($validation as $value) {
					$htmlMessage .= "<li>".$value."</li>";
				}
				$htmlMessage .= "</ul>";
				$this->session->set_flashdata('alert', $htmlMessage);
				return redirect($this->own_link."/create");
			}
			unset($post_data["id"]);
			$guru_data 	= $post_data;

			$password = date('Ymd', strtotime($guru_data["nomor_hp"]));
			$users_data 			= [
				"user_group_id"	=> 2,
				"username"			=> $guru_data['nip'],
				"name"					=> $guru_data['nama'],
				"email"					=> $guru_data['email'],
				"password"			=> password_hash($password, PASSWORD_DEFAULT),
				"password_raw"	=> $password,
				"created_at"		=> date('Y-m-d H:i:s')
			];

			$usersID = $this->Dbhelper->insertDataWithReturnID('m_users', $users_data, 'id');
			$guru_data['users_id'] = $usersID;

			$save = $this->Dbhelper->insertData($this->table, $guru_data);
			if ($save) {
				$this->session->set_flashdata('success', "Create data success");
				return redirect($this->own_link);
			}
			$this->session->set_flashdata('error', "Create data failed");
			return redirect($this->own_link."/create");
		}
		$this->session->set_flashdata('error', "Access denied");
        return redirect($this->own_link);
	}

	public function delete($id) {
		// $this->privilege('is_delete');
		$id = (int) $id;

		$active_periode = active_periode();
		$active_guru = $this->Guru_model->find_active_guru($active_periode['periode_id'], $id);
		if (!empty($active_guru)) {
			$this->session->set_flashdata('error', "Data guru sedang aktif di periode ".$active_periode['tahun_ajaran'].", tidak dapat dihapus.");
			return redirect($this->own_link);
		}

		$model = $this->Guru_model->find($id);
        if (empty($model)) {
			$this->session->set_flashdata('error', "Data not found");
        	return redirect($this->own_link);
        }

        $deleted_at = date("Y-m-d H:i:s");
		$save = $this->Dbhelper->updateData($this->table, array('id'=>$id), array("deleted_at" => $deleted_at));
		$save = $this->Dbhelper->updateData('m_users', array('id'=>$model->users_id), array("deleted_at" => $deleted_at));
		if ($save) {
			$this->session->set_flashdata('success', "Delete data success");
			return redirect($this->own_link);
		}
	}

	public function export() {
		$list = $this->Guru_model->all();
		$this->toExcel($list);
		die();
	}

	public function template() {
		force_download('assets/template_guru.xlsx',NULL);
	}

	public function import() { // Disarankan menggunakan nama fungsi yang berbeda
    	if ($this->input->server('REQUEST_METHOD') === 'POST') {
			if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
				$fileTmpPath = $_FILES['file']['tmp_name'];
				$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fileTmpPath);
				$sheet = $spreadsheet->getActiveSheet();

				// 1. Menyesuaikan headcol dengan template guru yang baru
				$headcol = [
					"B" => "nip",
					"C" => "gelar_depan",
					"D" => "nama",
					"E" => "gelar_belakang",
					"F" => "jenis_kelamin",
					"G" => "tempat_lahir",
					"H" => "tanggal_lahir",
					"I" => "agama",
					"J" => "email",
					"K" => "nomor_hp",
					"L" => "alamat",
					"M" => "asal_universitas", // Nama kolom di DB untuk "PERIODE_BERGABUNG"
				];

				$rowsDataGuru = [];
				$rowsUsersData = [];
				$nip_list = [];
				$periode_list = [];
				$active_periode = active_periode();

				// Memulai iterasi dari baris ke-2
				foreach ($sheet->getRowIterator() as $i => $row) {
					if ($i > 1) {
						$cellIterator = $row->getCellIterator();
						$cellIterator->setIterateOnlyExistingCells(false);
						
						$rowData = [];
						foreach ($cellIterator as $index_headcol => $cell) {
							if (isset($headcol[$index_headcol])) {
								$value = trim($cell->getValue());

								// Cek duplikasi NIP
								if ($index_headcol == "B") {
									if (empty($value)) continue 2; // Lewati baris jika NIP kosong
									$checkDuplicate = $this->Dbhelper->selectTabelOne('id', 'mt_users_guru', ["nip" => $value]);
									if ($checkDuplicate) {
										$this->session->set_flashdata('error', "Error: Duplikat data NIP $value.");
										return redirect($this->own_link);
									}
								}

								// Format tanggal lahir
								if ($index_headcol == "H") { 
									// PhpSpreadsheet terkadang membaca tanggal sebagai float, konversi dulu
									if (is_numeric($value)) {
										$value = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
									} else {
										$value = date('Y-m-d', strtotime($value));
									}
								}

								// Ambil ID periode
								if ($index_headcol == "M") { 
									if (!isset($periode_list[$value])) {
										$periode = $this->Dbhelper->selectTabelOne('id, tahun_ajaran', 'mt_periode', ["tahun_ajaran" => $value]);
										$periode_list[$value] = $periode;
									}
									$value = $periode_list[$value]['id'] ?? null;
								}
								
								$rowData[$headcol[$index_headcol]] = $value;
							}
						}

						// 2. Menyiapkan data untuk tabel m_users
						// Password default diambil dari tanggal lahir (Format: Ymd)
						$password_raw = date('Ymd', strtotime($rowData['tanggal_lahir']));
						$rowsUsersData[] = [
							"user_group_id" => 2, // Asumsi group_id guru adalah 2
							"username"      => $rowData["nip"],
							"name"          => $rowData["nama"],
							"password"      => password_hash($password_raw, PASSWORD_DEFAULT),
							"password_raw"  => $password_raw,
							"created_at"    => date('Y-m-d H:i:s'),
							"updated_at"    => date('Y-m-d H:i:s'),
						];

						$rowData['join_periode_id'] = $active_periode['periode_id'];
						// Menyimpan data guru dengan key NIP untuk relasi nanti
						// dd($rowData);
						$rowsDataGuru[$rowData["nip"]] = $rowData;
						$nip_list[] = $rowData["nip"];
					}
				}

				// 3. Memulai Transaksi Database
				$this->db->trans_start();

				// Insert batch ke m_users
				$this->db->insert_batch("m_users", $rowsUsersData);
				
				// Ambil data user yang baru di-insert untuk mendapatkan ID-nya
				$get_users = $this->db->where_in('username', $nip_list)->from('m_users')->get()->result_array();
				
				if (!empty($get_users)) {
					$finalGuruData = [];
					foreach ($get_users as $user) {
						$nip = $user['username'];
						
						// Menambahkan users_id ke data guru
						if (isset($rowsDataGuru[$nip])) {
							$rowsDataGuru[$nip]['users_id'] = $user['id'];
							// Gabungkan nama lengkap dari gelar depan, nama, dan gelar belakang
							$rowsDataGuru[$nip]['nama'] = trim($rowsDataGuru[$nip]['gelar_depan'] . ' ' . $rowsDataGuru[$nip]['nama'] . ' ' . $rowsDataGuru[$nip]['gelar_belakang']);
							$finalGuruData[] = $rowsDataGuru[$nip];
						}
					}
					
					// Insert batch ke tabel guru
					if (!empty($finalGuruData)) {
						$this->db->insert_batch("mt_users_guru", $finalGuruData);
					}
				}
				
				// 4. Finalisasi Transaction
				$this->db->trans_complete();

				if ($this->db->trans_status() === FALSE) {
					$this->session->set_flashdata('error', "Gagal mengimpor data guru. Terjadi kesalahan database.");
				} else {
					$this->session->set_flashdata('success', "Berhasil mengimpor " . count($rowsUsersData) . " data guru.");
				}
				return redirect($this->own_link);
			}
			
			$this->session->set_flashdata('error', "Gagal mengunggah file.");
			return redirect($this->own_link);
		}
		
		$this->session->set_flashdata('error', "Akses ditolak.");
		return redirect($this->own_link);
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
		$sheet->setCellValue("B".$rowCell, "NIP");
		$sheet->setCellValue("C".$rowCell, "NAMA");
		$sheet->setCellValue("D".$rowCell, "EMAIL");
		$sheet->setCellValue("E".$rowCell, "NOMOR HP");
		$sheet->setCellValue("F".$rowCell, "PERIODE BERGABUNG");

		$sheet->getStyle('A'.$rowCell.':F'.$rowCell)->applyFromArray($styleBorder);
		$rowCell++;
        $no = 1;
        if (!empty($data)) {
        	foreach ($data as $items) {
	        	$item = (object) $items;
	        	$sheet->setCellValue("A".$rowCell, $no);
	        	$sheet->setCellValue("B".$rowCell, $item->nip);
	        	$sheet->setCellValue("C".$rowCell, $item->nama);
	        	$sheet->setCellValue("D".$rowCell, $item->email);
	        	$sheet->setCellValue("E".$rowCell, $item->nomor_hp);
	        	$sheet->setCellValue("F".$rowCell, $item->tahun_ajaran);

	        	$sheet->getStyle('A'.$rowCell.':F'.$rowCell)->applyFromArray($styleBorder);

	        	$no++;
		        $rowCell++;
	        }
        }
        
        $writer = new Xlsx($spreadsheet);
 	
 		$today = strtotime('now');
        $filename = 'data-guru-'.$today;
 
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
			$data = $this->Guru_model->find($id);
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
