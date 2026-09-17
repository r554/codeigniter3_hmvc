<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['install'] = 'Installer/Installer/index';
$route['install/requirements'] = 'Installer/Installer/requirements_json';
$route['install/test_database'] = 'Installer/Installer/test_database';
$route['install/install'] = 'Installer/Installer/install';

$route['default_controller'] = "Default/Auth";
$route['404_override'] = 'Default/Not_found';
$route['login'] = 'Default/Auth';
$route['logout'] = 'Auth/logout';


/*   route modul profile */
$route['profile'] = 'Setting/Profile/index';


/*   route modul dashboard  */
$route['dashboard'] = 'Support/Dashboard/index';


/*     
$route['cat-halaman.html'] = 'cat_halaman/index';
$route['add-cat-halaman.html'] = 'cat_halaman/add';
$route['edit-cat-halaman.html/(:any)'] = 'cat_halaman/edit/$1';

*/

/*   route modul konfigurasi website  */
$route['konfigurasi'] = 'Setting/WebConfig/index';
$route['simpan-konfigurasi'] = 'Setting/WebConfig/simpan';
$route['simpan-konfigurasi-db'] = 'Setting/WebConfig/simpan_db';
$route['test-koneksi-db'] = 'Setting/WebConfig/test_koneksi_db';
$route['simpan-konfigurasi-email'] = 'Setting/WebConfig/simpan_email';
$route['test-kirim-email'] = 'Setting/WebConfig/test_kirim_email';
$route['docs-email'] = 'Setting/WebConfig/docs_email';
$route['simpan-konfigurasi-storage'] = 'Setting/WebConfig/simpan_storage';
$route['test-koneksi-storage'] = 'Setting/WebConfig/test_koneksi_storage';
$route['docs-storage'] = 'Setting/WebConfig/docs_storage';

/*   route modul backup & restore  */
$route['backup']                 = 'Setting/Backup/index';
$route['backup-database']        = 'Setting/Backup/backup_db';
$route['backup-files']           = 'Setting/Backup/backup_files';
$route['backup-full']            = 'Setting/Backup/backup_full';
$route['restore-database']       = 'Setting/Backup/restore_db';
$route['restore-files']          = 'Setting/Backup/restore_files';
$route['delete-backup']          = 'Setting/Backup/delete_backup';
$route['download-backup/(:any)'] = 'Setting/Backup/download/$1';
$route['save-cloud-settings']    = 'Setting/Backup/save_cloud_settings';

/*   route modul activity log  */
$route['activity-log']               = 'Setting/ActivityLog/index';
$route['activity-log/data']          = 'Setting/ActivityLog/get_data';
$route['activity-log/delete/(:num)'] = 'Setting/ActivityLog/delete/$1';
$route['activity-log/clear']         = 'Setting/ActivityLog/clear_all';
$route['activity-log/export']        = 'Setting/ActivityLog/export';

/*   route cron / scheduled backup  */
$route['cron/run-backup']            = 'Setting/Cron/run_backup';
$route['save-schedule-settings']     = 'Setting/Backup/save_schedule_settings';


/*   route modul menu  */
$route['menu'] = 'Setting/Menu/index';
$route['icon'] = 'Setting/Menu/icon';
$route['add-menu'] = 'Setting/Menu/add';
$route['edit-menu/(:any)'] = 'Setting/Menu/edit/$1';


/*   route modul user  */
$route['user'] = 'Setting/User/index';
$route['add-user'] = 'Setting/User/add';
$route['edit-user/(:any)'] = 'Setting/User/edit/$1';


/*   route modul grup  */
$route['user-grup'] = 'Setting/Grup/index';
$route['add-grup'] = 'Setting/Grup/add';
$route['edit-grup/(:any)'] = 'Setting/Grup/edit/$1';


/*    route modul Akses  */
$route['hak-akses/(:any)'] = 'Setting/Akses/hak_akses/$1';


/*   route modul pembayaran  */
$route['pembayaran'] =   'Category/Pembayaran/index';
$route['add-pembayaran'] = 'Category/pembayaran/add';
$route['edit-pembayaran/(:any)'] = 'Category/Pembayaran/edit/$1';
$route['preview-data/(:any)'] = 'Category/Pembayaran/preview/$1';


/*   route modul histori pembayaran   */
$route['histori'] =   'Category/Histori_pembayaran/index';
$route['edit-histori/(:any)'] = 'Category/Histori_pembayaran/edit/$1';
$route['print-data/(:any)'] = 'Category/Histori_pembayaran/preview/$1';

/*   route modul beban  */
$route['brand'] =   'Category/Brand/index';
$route['add-brand'] = 'Category/Brand/add';
$route['edit-brand/(:any)'] = 'Category/Brand/edit/$1';

/*   route modul beban  */
$route['department'] =   'Category/Department/index';
$route['add-department'] = 'Category/Department/add';
$route['edit-department/(:any)'] = 'Category/Department/edit/$1';

/*   route modul beban  */
$route['cabang'] =   'Category/Cabang/index';
$route['add-cabang'] = 'Category/Cabang/add';
$route['edit-cabang/(:any)'] = 'Category/Cabang/edit/$1';

/*   route modul beban  */
$route['folder'] =   'Category/Folder/index';
$route['add-folder'] = 'Category/Folder/add';
$route['edit-folder/(:any)'] = 'Category/Folder/edit/$1';


/*   route modul pelanggan   */
$route['sop'] =   'Master/Sop/index';
$route['add-sop'] = 'Master/Sop/add';
$route['edit-sop/(:any)'] = 'Master/Sop/edit/$1';

/*   route modul pelanggan   */
$route['employe'] =   'Master/Employe/index';
$route['add-employe'] = 'Master/Employe/add';
$route['edit-employe/(:any)'] = 'Master/Employe/edit/$1';
$route['view-employe/(:any)'] = 'Master/Employe/view/$1';
$route['view-employe-calendar/(:any)'] = 'Master/Employe/view_detail/$1';
$route['view-employe-psb/(:any)'] = 'Master/Employe/viewPSB/$1';
$route['view-employe-psb-detail/(:any)/(:any)/(:any)'] = 'Master/Employe/view_detailPSB/$1/$2/$3';
$route['view-employe-take5/(:any)'] = 'Master/Employe/viewtake5/$1';
$route['view-employe-take5-detail/(:any)/(:any)/(:any)'] = 'Master/Employe/view_detailtake5/$1/$2/$3';

/*   route modul pelanggan   */
$route['company'] =   'Master/Company/index';
$route['add-company'] = 'Master/Company/add';
$route['edit-company/(:any)'] = 'Master/Company/edit/$1';

/*   route modul pelanggan   */
$route['contact'] =   'Master/Contact/index';
$route['add-contact'] = 'Master/Contact/add';
$route['edit-contact/(:any)'] = 'Master/Contact/edit/$1';

/*   route modul pelanggan   */
$route['slider'] =   'Master/Slider/index';
$route['add-slider'] = 'Master/Slider/add';
$route['edit-slider/(:any)'] = 'Master/Slider/edit/$1';

/*   route modul pelanggan   */
$route['form-psb'] =   'Master/Form_psb/index';
$route['add-form-psb'] = 'Master/Form_psb/add';
$route['edit-form-psb/(:any)'] = 'Master/Form_psb/edit/$1';

/*   route modul pelanggan   */
$route['form-take5'] =   'Master/Form_take5/index';
$route['add-form-take5'] = 'Master/Form_take5/add';
$route['edit-form-take5/(:any)'] = 'Master/Form_take5/edit/$1';

/*   route modul download   */
$route['log-download'] =   'Master/Download/index';

/*   route modul kategori   */
$route['report-employe'] =   'Support/Report_employe/index';
$route['filter-employe'] = 'Support/Report_employe/filter';

/*   route modul kategori   */
$route['report-sop'] = 'Support/Report_sop/index';
$route['filter-sop'] = 'Support/Report_sop/filter';

/*   route modul kategori   */
$route['report-psb-form'] = 'Support/Report_psb/index';
$route['filter-psb-form'] = 'Support/Report_psb/filter';


/*   route modul kategori   */
$route['report-take5-form'] = 'Support/Report_take5/index';
$route['filter-take5-form'] = 'Support/Report_take5/filter';

/*   route modul kategori   */
$route['report-history'] =   'Support/Report_history/index';
$route['filter-history'] = 'Support/Report_history/filter';

/*   route modul kalender kerja   */
$route['report-kalender-kerja'] =   'Support/Report_kalender_kerja/index';
$route['filter-kalender-kerja'] =   'Support/Report_kalender_kerja/filter';

/*   route modul pre start checklist */
$route['report-pre-start-checklist'] =   'Support/Report_pre_start_checklist/index';
$route['filter-pre-start-checklist'] =   'Support/Report_pre_start_checklist/filter';

$route['buat-kalender-kerja'] =   'WebService/WebService/buat_kalender_kerja';
$route['get-kalender-kerja/(:any)'] =   'WebService/WebService/get_kalender_kerja/$1';
$route['get-kalender-kerja-filter/(:any)/(:num)'] =   'WebService/WebService/get_kalender_kerja_filter/$1/$2';
$route['get-filter/(:any)'] =   'WebService/WebService/get_filter/$1';
$route['get-onday/(:any)'] =   'WebService/WebService/get_onday/$1';
$route['hapus-kalender-kerja/(:any)'] =   'WebService/WebService/hapus_kalender_kerja/$1';
$route['get-kalender-kerja-detail/(:any)'] =   'WebService/WebService/get_kalender_kerja_detail/$1';
$route['update-status-kalender'] =   'WebService/WebService/update_status_kalender';
$route['download-kalender-kerja-detail/(:any)'] =   'WebService/WebService/export_excel/$1';
$route['kebijakan-privasi'] =   'WebService/WebService/privacyPolicy';
$route['get'] =   'WebService/WebService/get';

$route['get-foto-psb/(:any)/(:any)/(:any)'] =   'WebService/WebService/get_foto_psb/$1/$2/$3';
$route['get-buletin'] =   'WebService/WebService/get_buletin';

/*   route modul pre start checklist form */
$route['pre-start-checklist-form'] =   'Master/PS_Checklist/index';
$route['pre-start-checklist-detail/(:any)'] =   'Master/PS_Checklist/detail/$1';
$route['pre-start-print/(:any)'] =   'Master/PS_Checklist/print/$1';

/*   route modul master visual check */
$route['master-visual-check'] =   'Master/Visual_check/index';

/*   route modul master operationed cheeck */
$route['master-operationed-check'] =   'Master/Opt_check/index';

/*   route modul crud generator  */
$route['crud-generator'] = 'CrudGenerator/CrudGenerator/index';
$route['module-destroyer'] = 'CrudGenerator/CrudGenerator/destroyer';
$route['CrudGenerator/CrudGenerator/ajax_module_list'] = 'CrudGenerator/CrudGenerator/ajax_module_list';
$route['CrudGenerator/CrudGenerator/hapus_module']     = 'CrudGenerator/CrudGenerator/hapus_module';

/* End of file routes.php */
/* Location: ./application/config/routes.php */