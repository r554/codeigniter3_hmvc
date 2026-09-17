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

/*   route modul download   */
$route['log-download'] =   'Master/Download/index';

/*   route modul crud generator  */
$route['crud-generator'] = 'CrudGenerator/CrudGenerator/index';
$route['module-destroyer'] = 'CrudGenerator/CrudGenerator/destroyer';
$route['CrudGenerator/CrudGenerator/ajax_module_list'] = 'CrudGenerator/CrudGenerator/ajax_module_list';
$route['CrudGenerator/CrudGenerator/hapus_module']     = 'CrudGenerator/CrudGenerator/hapus_module';
