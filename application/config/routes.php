<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] 			= 'home';

$route['login_dashboard'] 			= 'auth/login_dashboard';
$route['logout'] 								= 'auth/logout';

$route["token/(:any)"]					= 'auth/token/$1';

$route['ajax_siswa'] 				= 'Ajax/search_siswa';	

$route['dashboard'] 								= 'admin/dashboard';

/* CONFIGURATION ROUTES */
	$route['dashboard/configuration/menu_builder'] 					= 'admin/configuration/menu_builder';
	$route['dashboard/configuration/menu_builder/create'] 			= 'admin/configuration/menu_builder/create';
	$route['dashboard/configuration/menu_builder/do_create'] 		= 'admin/configuration/menu_builder/do_create';
	$route['dashboard/configuration/menu_builder/edit/(:any)'] 		= 'admin/configuration/menu_builder/edit/$1';
	$route['dashboard/configuration/menu_builder/do_update'] 		= 'admin/configuration/menu_builder/do_update';
	$route['dashboard/configuration/menu_builder/delete/(:any)']	= 'admin/configuration/menu_builder/delete/$1';

	$route['dashboard/configuration/user_group'] 					= 'admin/configuration/user_group';
	$route['dashboard/configuration/user_group/create'] 			= 'admin/configuration/user_group/create';
	$route['dashboard/configuration/user_group/do_create'] 			= 'admin/configuration/user_group/do_create';
	$route['dashboard/configuration/user_group/edit/(:any)'] 		= 'admin/configuration/user_group/edit/$1';
	$route['dashboard/configuration/user_group/do_update'] 			= 'admin/configuration/user_group/do_update';
	$route['dashboard/configuration/user_group/privilege/(:any)']	= 'admin/configuration/user_group/detail/$1';
	$route['dashboard/configuration/user_group/do_privilege']		= 'admin/configuration/user_group/do_detail';
	$route['dashboard/configuration/user_group/delete/(:any)']		= 'admin/configuration/user_group/delete/$1';

	$route['dashboard/configuration/user'] 					= 'admin/configuration/user';
	$route['dashboard/configuration/user/create'] 			= 'admin/configuration/user/create';
	$route['dashboard/configuration/user/do_create'] 		= 'admin/configuration/user/do_create';
	$route['dashboard/configuration/user/edit/(:any)'] 		= 'admin/configuration/user/edit/$1';
	$route['dashboard/configuration/user/do_update'] 		= 'admin/configuration/user/do_update';
	$route['dashboard/configuration/user/delete/(:any)']	= 'admin/configuration/user/delete/$1';

	$route['dashboard/profile'] 					= 'admin/profile';
	$route['dashboard/profile/update'] 				= 'admin/profile/do_update';
/* CONFIGURATION ROUTES */

/* MASTER DATA ROUTES */
	$route['dashboard/master/tingkat-kelas'] 									= 'admin/master/Tingkat_kelas';
	$route['dashboard/master/tingkat-kelas/create'] 					= 'admin/master/Tingkat_kelas/create';
	$route['dashboard/master/tingkat-kelas/do_create'] 				= 'admin/master/Tingkat_kelas/do_create';
	$route['dashboard/master/tingkat-kelas/edit/(:any)'] 			= 'admin/master/Tingkat_kelas/edit/$1';
	$route['dashboard/master/tingkat-kelas/do_update'] 				= 'admin/master/Tingkat_kelas/do_update';
	$route['dashboard/master/tingkat-kelas/delete/(:any)']		= 'admin/master/Tingkat_kelas/delete/$1';
	$route['dashboard/master/tingkat-kelas/datatables'] 			= 'admin/master/Tingkat_kelas/datatables';

	
	$route['dashboard/master/periode'] 									= 'admin/master/Periode';
	$route['dashboard/master/periode/create'] 					= 'admin/master/Periode/create';
	$route['dashboard/master/periode/do_create'] 				= 'admin/master/Periode/do_create';
	$route['dashboard/master/periode/edit/(:any)'] 			= 'admin/master/Periode/edit/$1';
	$route['dashboard/master/periode/do_update'] 				= 'admin/master/Periode/do_update';
	$route['dashboard/master/periode/delete/(:any)']		= 'admin/master/Periode/delete/$1';
	$route['dashboard/master/periode/datatables'] 			= 'admin/master/Periode/datatables';

	
	$route['dashboard/master/mata-pelajaran'] 								= 'admin/master/Mata_pelajaran';
	$route['dashboard/master/mata-pelajaran/create'] 					= 'admin/master/Mata_pelajaran/create';
	$route['dashboard/master/mata-pelajaran/do_create'] 			= 'admin/master/Mata_pelajaran/do_create';
	$route['dashboard/master/mata-pelajaran/edit/(:any)'] 		= 'admin/master/Mata_pelajaran/edit/$1';
	$route['dashboard/master/mata-pelajaran/do_update'] 			= 'admin/master/Mata_pelajaran/do_update';
	$route['dashboard/master/mata-pelajaran/delete/(:any)']		= 'admin/master/Mata_pelajaran/delete/$1';
	$route['dashboard/master/mata-pelajaran/datatables'] 			= 'admin/master/Mata_pelajaran/datatables';

	
	$route['dashboard/master/dokumen'] 									= 'admin/master/Dokumen';
	$route['dashboard/master/dokumen/create'] 					= 'admin/master/Dokumen/create';
	$route['dashboard/master/dokumen/do_create'] 				= 'admin/master/Dokumen/do_create';
	$route['dashboard/master/dokumen/edit/(:any)'] 			= 'admin/master/Dokumen/edit/$1';
	$route['dashboard/master/dokumen/do_update'] 				= 'admin/master/Dokumen/do_update';
	$route['dashboard/master/dokumen/delete/(:any)']		= 'admin/master/Dokumen/delete/$1';
	$route['dashboard/master/dokumen/datatables'] 			= 'admin/master/Dokumen/datatables';

	
	$route['dashboard/master/informasi'] 									= 'admin/master/Informasi';
	$route['dashboard/master/informasi/create'] 					= 'admin/master/Informasi/create';
	$route['dashboard/master/informasi/do_create'] 				= 'admin/master/Informasi/do_create';
	$route['dashboard/master/informasi/edit/(:any)'] 			= 'admin/master/Informasi/edit/$1';
	$route['dashboard/master/informasi/do_update'] 				= 'admin/master/Informasi/do_update';
	$route['dashboard/master/informasi/delete/(:any)']		= 'admin/master/Informasi/delete/$1';
	$route['dashboard/master/informasi/datatables'] 			= 'admin/master/Informasi/datatables';

/* MASTER DATA ROUTES */



/* KESISWAAN ROUTES */
	
	$route['dashboard/kesiswaan/siswa'] 								= 'admin/kesiswaan/Siswa';
	$route['dashboard/kesiswaan/siswa/create'] 					= 'admin/kesiswaan/Siswa/create';
	$route['dashboard/kesiswaan/siswa/do_create'] 			= 'admin/kesiswaan/Siswa/do_create';
	$route['dashboard/kesiswaan/siswa/edit/(:any)'] 		= 'admin/kesiswaan/Siswa/edit/$1';
	$route['dashboard/kesiswaan/siswa/do_update'] 			= 'admin/kesiswaan/Siswa/do_update';
	$route['dashboard/kesiswaan/siswa/delete/(:any)']		= 'admin/kesiswaan/Siswa/delete/$1';
	$route['dashboard/kesiswaan/siswa/datatables'] 			= 'admin/kesiswaan/Siswa/datatables';
	$route['dashboard/kesiswaan/siswa/template'] 			= 'admin/kesiswaan/Siswa/template';
	$route['dashboard/kesiswaan/siswa/export'] 				= 'admin/kesiswaan/Siswa/export';
	$route['dashboard/kesiswaan/siswa/import'] 				= 'admin/kesiswaan/Siswa/import';

	$route['dashboard/kesiswaan/guru'] 									= 'admin/kesiswaan/Guru';
	$route['dashboard/kesiswaan/guru/create'] 					= 'admin/kesiswaan/Guru/create';
	$route['dashboard/kesiswaan/guru/do_create'] 				= 'admin/kesiswaan/Guru/do_create';
	$route['dashboard/kesiswaan/guru/edit/(:any)'] 			= 'admin/kesiswaan/Guru/edit/$1';
	$route['dashboard/kesiswaan/guru/do_update'] 				= 'admin/kesiswaan/Guru/do_update';
	$route['dashboard/kesiswaan/guru/delete/(:any)']		= 'admin/kesiswaan/Guru/delete/$1';
	$route['dashboard/kesiswaan/guru/datatables'] 			= 'admin/kesiswaan/Guru/datatables';
	$route['dashboard/kesiswaan/guru/template'] 			= 'admin/kesiswaan/Guru/template';
	$route['dashboard/kesiswaan/guru/export'] 				= 'admin/kesiswaan/Guru/export';
	$route['dashboard/kesiswaan/guru/import'] 				= 'admin/kesiswaan/Guru/import';

	
	$route['dashboard/kesiswaan/kelas'] 								= 'admin/kesiswaan/Kelas';
	$route['dashboard/kesiswaan/kelas/create'] 					= 'admin/kesiswaan/Kelas/create';
	$route['dashboard/kesiswaan/kelas/do_create'] 			= 'admin/kesiswaan/Kelas/do_create';
	$route['dashboard/kesiswaan/kelas/edit/(:any)'] 		= 'admin/kesiswaan/Kelas/edit/$1';
	$route['dashboard/kesiswaan/kelas/do_update'] 			= 'admin/kesiswaan/Kelas/do_update';
	$route['dashboard/kesiswaan/kelas/delete/(:any)']		= 'admin/kesiswaan/Kelas/delete/$1';
	$route['dashboard/kesiswaan/kelas/datatables'] 			= 'admin/kesiswaan/Kelas/datatables';
	$route['dashboard/kesiswaan/kelas/list-siswa/(:any)']		= 'admin/kesiswaan/Kelas/list_siswa/$1';

	$route['dashboard/kesiswaan/alumni'] 								= 'admin/kesiswaan/Alumni';
	$route['dashboard/kesiswaan/alumni/datatables'] 			= 'admin/kesiswaan/Alumni/datatables';

/* KESISWAAN ROUTES */

/* GENERATE ROUTES */
	$route['dashboard/generate/kelas-siswa'] 								= 'admin/Generate/kelas_siswa_index';
	$route['dashboard/generate/kelas-siswa/update'] 				= 'admin/Generate/kelas_siswa_update';
	$route['dashboard/generate/jadwal-kelas'] 							= 'admin/Generate/jadwal_kelas_index';
	$route['dashboard/generate/jadwal-kelas/update'] 							= 'admin/Generate/jadwal_kelas_update';
	$route['dashboard/generate/jadwal-kelas/detail'] 							= 'admin/Generate/jadwal_kelas_detail';
	$route['dashboard/generate/jadwal-kelas/delete'] 							= 'admin/Generate/jadwal_kelas_delete';

	$route['dashboard/generate/closing-tahun-ajaran'] 							= 'admin/Generate/closing_tahun_ajaran_index';
	$route['dashboard/generate/closing-tahun-ajaran/do_penilaian'] 							= 'admin/Generate/closing_tahun_ajaran_do_penilaian';
	
	$route['dashboard/setting-lms'] 								= 'admin/SettingLms/index';
	$route['dashboard/setting-lms/do_update'] 						= 'admin/SettingLms/do_update';

	$route['dashboard/kenaikan-kelas'] 								= 'admin/KenaikanKelas/index';
	$route['dashboard/kenaikan-kelas/datatables'] 		= 'admin/KenaikanKelas/datatables';
	$route['dashboard/kenaikan-kelas/do_update'] 			= 'admin/KenaikanKelas/do_update';

	
	$route['dashboard/aspirasi'] 								= 'admin/Aspirasi/index';
	$route['dashboard/aspirasi/datatables'] 		= 'admin/Aspirasi/datatables';

	$route['dashboard/setting-periode'] 								= 'admin/SettingPeriode/index';
	$route['dashboard/setting-periode/do_update'] 						= 'admin/SettingPeriode/do_update';

	$route['dashboard/backup'] 								= 'admin/Backup/index';
	$route['dashboard/backup/do-backup'] 			= 'admin/Backup/do_backup';

	$route['dashboard/pantau-lms'] 													= 'admin/Lms/tingkat_kelas_index';
	$route['dashboard/lms/kelas/(:any)'] 										= 'admin/Lms/lms_index/$1';
	$route['dashboard/lms/detail/(:any)'] 									= 'admin/Lms/index/$1';
	$route['dashboard/lms/pertemuan/absensi/(:any)'] 				= 'admin/Lms/absensi/$1';
	$route['dashboard/lms/pertemuan/diskusi/(:any)'] 				= 'admin/Lms/diskusi/$1';
	$route['dashboard/lms/pertemuan/diskusi-delete'] 				= 'admin/Lms/diskusi_delete';
	$route['dashboard/lms/pertemuan/modul/(:any)'] 					= 'admin/Lms/modul/$1';
	$route['dashboard/lms/pertemuan/pranala-luar/(:any)'] 	= 'admin/Lms/pranala_luar/$1';
/* GENERATE ROUTES */

/* GURU ROUTES */
	$route['guru'] 										= 'guru/Dashboard/index';
	$route['guru/dashboard'] 					= 'guru/Dashboard/index';
	$route['guru/lms'] 								= 'guru/Dashboard/lms_index';
	$route['guru/jadwal-kelas/update'] 			= 'guru/Dashboard/update_jadwal_kelas/$1';
	$route['guru/jadwal-kelas/detail/(:any)'] 	= 'guru/Kelas/index/$1';
	$route['guru/pertemuan/aktifkan'] 			= 'guru/Kelas/aktifkan_kelas';
	$route['guru/pertemuan/tutup'] 			= 'guru/Kelas/tutup_kelas';

	$route['guru/pertemuan/absensi/(:any)'] = 'guru/Lms/absensi/$1';
	$route['guru/pertemuan/absensi-detail'] = 'guru/Lms/absensi_detail/$1';
	$route['guru/pertemuan/absensi-update'] = 'guru/Lms/absensi_update';

	$route['guru/pertemuan/diskusi/(:any)'] = 'guru/Lms/diskusi/$1';
	$route['guru/pertemuan/diskusi-save'] 		= 'guru/Lms/save_diskusi';
	$route['guru/pertemuan/diskusi-delete'] 		= 'guru/Lms/delete_diskusi';

	$route['guru/pertemuan/modul/(:any)'] = 'guru/Lms/modul/$1';
	$route['guru/pertemuan/modul-update'] = 'guru/Lms/update_modul';

	$route['guru/pertemuan/pranala-luar/(:any)'] 	= 'guru/Lms/pranala_luar/$1';
	$route['guru/pertemuan/pranala-luar-save'] 		= 'guru/Lms/save_pranala_luar';
	$route['guru/pertemuan/pranala-luar-delete'] 	= 'guru/Lms/delete_pranala_luar';

	$route['guru/laporan-penilaian'] 						= 'guru/Laporan/penilaian';
	$route['guru/laporan-penilaian/detail'] 				= 'guru/Laporan/penilaian_detail';
	$route['guru/laporan-penilaian/do_penilaian']		 	= 'guru/Laporan/do_penilaian';
	$route['guru/laporan-penilaian/detail_export'] 			= 'guru/Laporan/penilaian_detail_excel';
	$route['guru/laporan-penilaian/detail_import'] 			= 'guru/Laporan/penilaian_detail_import';
	$route['guru/e-grading']		 						= 'guru/Laporan/egrading';
	$route['guru/e-grading/submit']		 					= 'guru/Laporan/egrading_submit';
	$route['guru/setting/e-grading'] 						= 'guru/Laporan/setting_egrading';
	$route['guru/laporan-absensi'] 							= 'guru/Laporan/absensi';

	
	$route['guru/wali-kelas/e-rapor'] 						= 'guru/WaliKelas/erapor';
	$route['guru/wali-kelas/e-rapor/submit'] 				= 'guru/WaliKelas/erapor_submit';
	$route['guru/wali-kelas/e-rapor/detail'] 				= 'guru/WaliKelas/erapor_detail';
	$route['guru/wali-kelas/e-rapor/pdf/(:any)'] 			= 'guru/WaliKelas/erapor_pdf/$1';

	$route['guru/wali-kelas/siswa'] 													= 'guru/WaliKelas/siswa';
	$route['guru/wali-kelas/siswa/ekstrakulikuler'] 					= 'guru/WaliKelas/siswa_ekstrakulikuler';
	$route['guru/wali-kelas/siswa/ekstrakulikuler/do_update'] = 'guru/WaliKelas/siswa_ekstrakulikuler_do_update';
	$route['guru/wali-kelas/konseling'] 	= 'guru/WaliKelas/konseling';

	
	$route['guru/wali-kelas/jadwal-kelas/detail/(:any)'] 	= 'guru/WaliKelas/index/$1';
	$route['guru/wali-kelas/lms'] 														= 'guru/WaliKelas/lms_index';
	$route['guru/wali-kelas/pertemuan/absensi/(:any)'] 				= 'guru/WaliKelas/absensi/$1';
	$route['guru/wali-kelas/pertemuan/diskusi/(:any)'] 				= 'guru/WaliKelas/diskusi/$1';
	$route['guru/wali-kelas/pertemuan/modul/(:any)'] 					= 'guru/WaliKelas/modul/$1';
	$route['guru/wali-kelas/pertemuan/pranala-luar/(:any)'] 	= 'guru/WaliKelas/pranala_luar/$1';

	$route['guru/login'] 					= 'guru/Auth/login';
	$route['guru/logout'] 				= 'guru/Auth/logout';
	$route['guru/akademik'] 		= 'guru/Dashboard/akademik';
	$route['guru/dokumen'] 		= 'guru/Dashboard/dokumen';
	$route['guru/profil'] 					= 'guru/Dashboard/profil';
	$route['guru/profil_update'] 					= 'guru/Dashboard/profil_update';

/* SISWA ROUTES*/

	$route['siswa'] 								= 'siswa/Dashboard/index';
	$route['siswa/dashboard'] 			= 'siswa/Dashboard/index';
	$route['siswa/profil'] 					= 'siswa/Dashboard/profil';
	$route['siswa/profil_update'] 					= 'siswa/Dashboard/profil_update';
	$route['siswa/lms'] 						= 'siswa/Dashboard/lms_index';
	$route['siswa/login'] 					= 'siswa/Auth/login';
	$route['siswa/logout'] 				= 'siswa/Auth/logout';

	$route['siswa/aktifitas-lms'] 						= 'siswa/Dashboard/aktifitas_lms_index';
	$route['siswa/aktifitas-lms/detail'] 				= 'siswa/Dashboard/aktitifas_lms_detail';
	
	$route['siswa/akademik'] 		= 'siswa/Dashboard/akademik';
	$route['siswa/dokumen'] 		= 'siswa/Dashboard/dokumen';
	
	$route['siswa/kelas/detail/(:any)'] 	= 'siswa/Lms/index/$1';
	$route['siswa/pertemuan/tugas/(:any)'] 	= 'siswa/Lms/tugas/$1';
	$route['siswa/pertemuan/tugas-update'] = 'siswa/Lms/update_tugas';


	$route['siswa/pertemuan/diskusi/(:any)'] = 'siswa/Lms/diskusi/$1';
	$route['siswa/pertemuan/diskusi-save'] 		= 'siswa/Lms/save_diskusi';
	$route['siswa/pertemuan/diskusi-delete'] 		= 'siswa/Lms/delete_diskusi';

	$route['siswa/pertemuan/modul/(:any)'] = 'siswa/Lms/modul/$1';
	$route['siswa/pertemuan/pranala-luar/(:any)'] 	= 'siswa/Lms/pranala_luar/$1';

	
	$route['siswa/aspirasi'] 			= 'siswa/Aspirasi/index';
	$route['siswa/aspirasi/save'] 		= 'siswa/Aspirasi/save_aspirasi';
	$route['siswa/konseling'] 			= 'siswa/Aspirasi/index_konseling';
	$route['siswa/konseling/save'] 		= 'siswa/Aspirasi/save_konseling';

	$route['siswa/akademik/jadwal-pelajaran'] 			= 'siswa/Akademik/jadwal_pelajaran';
	$route['siswa/akademik/jadwal-pelajaran/pdf'] 	= 'siswa/Akademik/jadwal_pelajaran_pdf';
	$route['siswa/akademik/nilai-semester'] 				= 'siswa/Akademik/nilai_semester';
	$route['siswa/akademik/nilai-semester/pdf'] 		= 'siswa/Akademik/nilai_semester_pdf';
	$route['siswa/akademik/rangkuman-nilai'] 				= 'siswa/Akademik/rangkuman_nilai';
	$route['siswa/akademik/rangkuman-nilai/pdf'] 		= 'siswa/Akademik/rangkuman_nilai_pdf';


$route['404_override'] 					= '';
$route['translate_uri_dashes'] 			= FALSE;
