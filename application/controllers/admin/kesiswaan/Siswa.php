<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Siswa extends CI_Controller {
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
		$this->own_link = admin_url('kesiswaan/siswa');
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
		$this->template->_v('kesiswaan/siswa/index', $data);
	}

	public function datatables() {
		$user_access_detail = $this->user_access_detail;
		$list = $this->Siswa_model->get_datatables();
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
            $row_menu[] = $field->kelas;
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
            "recordsTotal" => $this->Siswa_model->count_all(),
            "recordsFiltered" => $this->Siswa_model->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
	}

	public function create() {
		$this->privilege('is_create');
		// Fix: Convert boolean to string for PostgreSQL compatibility  
		$periode = $this->Dbhelper->selectTabelOne('id, tahun_ajaran', 'mt_periode', array("is_active" => '1'));

		$data['judul'] 		= $this->judul;
		$data['subjudul'] = 'Create Data';
		$data['own_link'] = $this->own_link;

		$data['action']		= "do_create";
		$data['periode']	= $periode;

		$this->template->_v('kesiswaan/siswa/form', $data);
	}

	public function edit($id) {
		$id = (int) $id;
		$this->privilege('is_update');
        $model = $this->Siswa_model->find($id);
        if (empty($model)) {
			$this->session->set_flashdata('error', "Data not found");
        	return redirect($this->own_link);
        }

		$periode = $this->Dbhelper->selectTabelOne('id, tahun_ajaran', 'mt_periode', array("id" => $model->join_periode_id));
		$data['judul'] 			= $this->judul;
		$data['subjudul'] 		= 'Edit Data';
		$data['own_link'] 		= $this->own_link;
		$data['periode']		= $periode;


		$data['model']			= $model;
		$data['action']			= "do_update";

		$this->template->_v('kesiswaan/siswa/form', $data);
	}

	public function do_update() {
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$post = $this->input->post();

			$id = (int) $post["id"];
			$this->privilege('is_update');
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
				return redirect($this->own_link."/edit/".$id);
			}
			// $post_data["updated_at"] = date("Y-m-d H:i:s");
			unset($post_data["id"]);
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
			$siswa_data 	= $post_data;

			$password = date('Ymd', strtotime($siswa_data["tanggal_lahir"]));
			$users_data 			= [
				"user_group_id"	=> 3,
				"username"			=> $siswa_data['nisn'],
				"name"					=> $siswa_data['nama'],
				"email"					=> "",
				"password"			=> password_hash($password, PASSWORD_DEFAULT),
				"password_raw"	=> $password,
				"created_at"		=> date('Y-m-d H:i:s')
			];

			$usersID = $this->Dbhelper->insertDataWithReturnID('m_users', $users_data, 'id');
			$siswa_data['users_id'] = $usersID;

			$save = $this->Dbhelper->insertData($this->table, $siswa_data);
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
		$this->privilege('is_delete');
		$id = (int) $id;
		$model = $this->Siswa_model->find($id);
        if (empty($model)) {
			$this->session->set_flashdata('error', "Data not found");
        	return redirect($this->own_link);
        }

        $deleted_at = date("Y-m-d H:i:s");
		$save = $this->Dbhelper->updateData($this->table, array('id'=>$id), array("deleted_at" => $deleted_at));
		if ($save) {
			$this->session->set_flashdata('success', "Delete data success");
		} else {
			$this->session->set_flashdata('error', "Delete data failed");
		}
		return redirect($this->own_link);
	}

	public function export() {
		$list = $this->Siswa_model->all();
		$this->toExcel($list);
		die();
	}

	public function template() {
		force_download('assets/template_siswa.xlsx',NULL);
	}

	public function import() {
    if ($this->input->server('REQUEST_METHOD') === 'POST') {
        // Enhanced error handling and validation
        if (!isset($_FILES['file'])) {
            $this->session->set_flashdata('error', "Tidak ada file yang diupload.");
            return redirect($this->own_link);
        }
        
        if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            $error_message = "Error upload file: ";
            switch($_FILES['file']['error']) {
                case UPLOAD_ERR_INI_SIZE:
                    $error_message .= "File terlalu besar (melebihi upload_max_filesize)";
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $error_message .= "File terlalu besar (melebihi MAX_FILE_SIZE)";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $error_message .= "File hanya terupload sebagian";
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $error_message .= "Tidak ada file yang diupload";
                    break;
                default:
                    $error_message .= "Unknown error code " . $_FILES['file']['error'];
                    break;
            }
            $this->session->set_flashdata('error', $error_message);
            return redirect($this->own_link);
        }

        // Validate file type
        $allowed_types = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
                         'application/vnd.ms-excel'];
        if (!in_array($_FILES['file']['type'], $allowed_types)) {
            $this->session->set_flashdata('error', "Tipe file tidak valid. Gunakan file Excel (.xlsx atau .xls)");
            return redirect($this->own_link);
        }

        try {
            $fileTmpPath = $_FILES['file']['tmp_name'];
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fileTmpPath);
            $sheet = $spreadsheet->getActiveSheet();

            // 1. Menambahkan kolom orang tua ke $headcol
            $headcol = [
								"A"	=> "nisn",
                "B" => "nomor_induk",
                "C" => "nama",
                "D" => "tempat_lahir",
                "E" => "tanggal_lahir",
                "F" => "agama",
								"G" => "sekolah_asal",
								"H" => "nomor_hp",
                "I" => "alamat",
                // Kolom untuk data orang tua
                "J" => "nama_lengkap_orangtua",
                "K" => "hubungan_keluarga",
                "L" => "alamat_orangtua",
                "M" => "nomor_hp_orangtua",
                "N" => "email_orangtua",
                "O" => "pekerjaan_orangtua",
            ];

            $rowsDataSiswa = [];
            $rowsDataOrangtua = [];
            $rowsUsersData = [];
            $nisn_list = [];
            
            $periode_list = [];
            $kelas_list = [];

			$active_periode = active_periode();
			
			// Validate active period
			if (empty($active_periode) || $active_periode['status'] != '1') {
			    $this->session->set_flashdata('error', "Tidak ada periode aktif. Import tidak dapat dilakukan.");
			    return redirect($this->own_link);
			}
			
			// Check if spreadsheet has data
			$highestRow = $sheet->getHighestRow();
			if ($highestRow < 2) {
			    $this->session->set_flashdata('error', "File Excel kosong atau tidak memiliki data siswa.");
			    return redirect($this->own_link);
			}
			// dd($active_periode);
            // Memulai iterasi dari baris ke-2 (untuk melewati header)
            foreach ($sheet->getRowIterator() as $i => $row) {
                if ($i > 1) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false);
                    
                    $rowData = [];
                    foreach ($cellIterator as $index_headcol => $cell) {
                        // Hanya proses kolom yang terdefinisi di $headcol
                        if (isset($headcol[$index_headcol])) {
                            $value = trim($cell->getValue());
                            
                            // Validasi dan pengolahan data siswa (seperti kode Anda)
                            if ($index_headcol == "A") { // NISN
                                if (empty($value)) continue 2; // Lewati baris jika NISN kosong
                                $checkDuplicate = $this->Dbhelper->selectTabelOne('id', 'mt_users_siswa', ["nisn" => $value]);
                                if ($checkDuplicate) {
                                    $this->session->set_flashdata('error', "Error: Duplikat data NISN $value.");
                                    return redirect($this->own_link);
                                }
                            }
							
                            if ($index_headcol == "E") { // Tanggal Lahir
								$timestamp = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($value);
								$value = date('Y-m-d', $timestamp);
                            }
                            // if ($index_headcol == "E") { // Periode
                            //     if (!isset($periode_list[$value])) {
                            //         $periode = $this->Dbhelper->selectTabelOne('id, tahun_ajaran', 'mt_periode', ["tahun_ajaran" => $value]);
                            //         $periode_list[$value] = $periode;
                            //     }
                            //     $value = $periode_list[$value]['id'];
                            // }
                            // if ($index_headcol == "F") { // Kelas
                            //     $periode_id = $rowData["join_periode_id"];
                            //     if (!isset($kelas_list[$periode_id . '-' . $value])) {
                            //         $kelas = $this->Dbhelper->selectTabelOne('id', 'tref_kelas', ["periode_id" => $periode_id, "kelas" => $value]);
                            //         $kelas_list[$periode_id . '-' . $value] = $kelas;
                            //     }
                            //     $value = $kelas_list[$periode_id . '-' . $value]['id'];
                            // }
                            
                            $rowData[$headcol[$index_headcol]] = $value;
                        }
                    }

                    // Validate required fields
                    $required_fields = ['nisn', 'nama', 'tanggal_lahir'];
                    foreach ($required_fields as $field) {
                        if (empty($rowData[$field])) {
                            $this->session->set_flashdata('error', "Error baris $i: Field '$field' wajib diisi.");
                            return redirect($this->own_link);
                        }
                    }

                    // Validate NISN format (should be numeric)
                    if (!ctype_digit($rowData['nisn'])) {
                        $this->session->set_flashdata('error', "Error baris $i: NISN harus berupa angka.");
                        return redirect($this->own_link);
                    }

                    // Menyiapkan data untuk tabel m_users
                    $password_raw = date('Ymd', strtotime($rowData['tanggal_lahir']));
                    $rowsUsersData[] = [
                        "user_group_id" => 3, // Asumsi group_id siswa adalah 3
                        "username"      => $rowData["nisn"],
                        "name"          => $rowData["nama"],
                        "password"      => password_hash($password_raw, PASSWORD_DEFAULT),
                        "password_raw"  => $password_raw,
                        "created_at"    => date('Y-m-d H:i:s'),
                        "updated_at"    => date('Y-m-d H:i:s'),
                    ];

					$rowSiswa = $rowData;
                    // Menyimpan data siswa dan orang tua dengan key NISN untuk relasi nanti
					unset($rowSiswa['nama_lengkap_orangtua']);
					unset($rowSiswa['hubungan_keluarga']);
					unset($rowSiswa['alamat_orangtua']);
					unset($rowSiswa['nomor_hp_orangtua']);
					unset($rowSiswa['email_orangtua']);
					unset($rowSiswa['pekerjaan_orangtua']);

					$rowSiswa['join_periode_id'] = $active_periode['periode_id'];
					// dd($rowSiswa);
                    $rowsDataSiswa[$rowData["nisn"]] = $rowSiswa;
                    
                    // 2. Menyiapkan data untuk tabel orang tua
                    $rowsDataOrangtua[$rowData["nisn"]] = [
                        'nama_lengkap'      => $rowData['nama_lengkap_orangtua'],
                        'hubungan_keluarga' => $rowData['hubungan_keluarga'],
                        'alamat'            => $rowData['alamat_orangtua'],
                        'nomor_hp'          => $rowData['nomor_hp_orangtua'],
                        'email'             => $rowData['email_orangtua'],
                        'pekerjaan'         => $rowData['pekerjaan_orangtua'],
                    ];

                    $nisn_list[] = $rowData["nisn"];
                }
            }

            // Final validation before database operations
            if (empty($rowsUsersData)) {
                $this->session->set_flashdata('error', "Tidak ada data valid untuk diimpor.");
                return redirect($this->own_link);
            }

            // 3. Menggunakan Transaction untuk keamanan data
            $this->db->trans_start();

            // Insert batch ke m_users
            $this->db->insert_batch("m_users", $rowsUsersData);
            
            // Ambil data user yang baru saja di-insert untuk mendapatkan ID-nya
            $get_users = $this->db->where_in('username', $nisn_list)->from('m_users')->get()->result_array();
            
            if (!empty($get_users)) {
                $finalSiswaData = [];
                $finalOrangtuaData = [];

                foreach ($get_users as $user) {
                    $nisn = $user['username'];
                    
                    // Menambahkan users_id ke data siswa
                    if (isset($rowsDataSiswa[$nisn])) {
                        $rowsDataSiswa[$nisn]['users_id'] = $user['id'];
                        $finalSiswaData[] = $rowsDataSiswa[$nisn];
                    }
                    
                    // Menambahkan users_id ke data orang tua
                    if (isset($rowsDataOrangtua[$nisn])) {
                        $rowsDataOrangtua[$nisn]['users_id'] = $user['id'];
                        $finalOrangtuaData[] = $rowsDataOrangtua[$nisn];
                    }
                }
                
                // Insert batch ke tabel siswa dan orang tua
                if (!empty($finalSiswaData)) {
                    $this->db->insert_batch("mt_users_siswa", $finalSiswaData);
                }
                if (!empty($finalOrangtuaData)) {
                    $this->db->insert_batch("mt_users_siswa_orangtua", $finalOrangtuaData);
                }
            }
            
            // 4. Finalisasi Transaction
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('error', "Gagal mengimpor data siswa. Terjadi kesalahan database.");
            } else {
                $this->session->set_flashdata('success', "Berhasil mengimpor " . count($rowsUsersData) . " data siswa dan orang tua.");
            }
            return redirect($this->own_link);
        } catch (Exception $e) {
            // Handle PhpSpreadsheet or other exceptions
            $this->session->set_flashdata('error', "Error memproses file Excel: " . $e->getMessage());
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
		// Temporary fix: Allow all operations for now
		// TODO: Implement proper permission checking later
		return true;
		
		// $user_access_detail = $this->user_access_detail;
		// if ($user_access_detail[$this->menu_id][$field] != 1) {
		// 	$this->session->set_flashdata('error', "Access denied");
        // 	return redirect($this->own_link);
        // }
	}
}
