<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backup extends AUTH_Controller {

    private $backup_dir;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['backup', 'env', 'activity']);
        $this->backup_dir = FCPATH . 'backups' . DIRECTORY_SEPARATOR;
        backup_ensure_dir($this->backup_dir);
    }

    private function loadkonten($page, $data) {
        $data['userdata'] = $this->userdata;
        $ajax = ($this->input->post('status_link') == 'ajax' ? true : false);
        if (!$ajax) {
            $this->load->view('Dashboard/layouts/header', $data);
        }
        $this->load->view($page, $data);
        if (!$ajax) $this->load->view('Dashboard/layouts/footer', $data);
    }

    // -------------------------------------------------------
    //  Halaman Utama
    // -------------------------------------------------------
    public function index()
    {
        $data['judul'] = 'Backup & Restore';
        $data['page']  = 'backup';
        $data['files'] = $this->_list_backups();
        $this->loadkonten('v_backup/v_home', $data);
    }

    // -------------------------------------------------------
    //  Backup Database
    // -------------------------------------------------------
    public function backup_db()
    {
        $result = backup_database();

        if (!$result['status']) {
            echo json_encode(['status' => 'error', 'message' => $result['message']]);
            return;
        }

        $filename = 'backup_db_' . date('Ymd_His') . '.sql';
        $filepath = $this->backup_dir . $filename;

        file_put_contents($filepath, $result['sql']);

        log_activity('backup_db', 'Backup database: ' . $filename, 'Backup');

        $cloud = upload_backup_to_cloud($filepath, $filename);

        echo json_encode([
            'status'    => 'ok',
            'message'   => 'Backup database berhasil.' . ($cloud['status'] ? ' Upload cloud OK.' : ''),
            'filename'  => $filename,
            'size'      => backup_format_size(file_exists($filepath) ? filesize($filepath) : 0),
            'url'       => file_exists($filepath) ? site_url('download-backup/' . $filename) : '',
            'cloud_url' => $cloud['url'] ?? '',
        ]);
    }

    // -------------------------------------------------------
    //  Backup Files
    // -------------------------------------------------------
    public function backup_files()
    {
        $uploads_path = FCPATH . (env('STORAGE_LOCAL_PATH', 'uploads'));
        $filename     = 'backup_files_' . date('Ymd_His') . '.zip';
        $filepath     = $this->backup_dir . $filename;

        $result = backup_files($uploads_path, $filepath);

        if (!$result['status']) {
            echo json_encode(['status' => 'error', 'message' => $result['message']]);
            return;
        }

        log_activity('backup_files', 'Backup files: ' . $filename, 'Backup');

        $cloud = upload_backup_to_cloud($filepath, $filename);

        echo json_encode([
            'status'    => 'ok',
            'message'   => 'Backup files berhasil.' . ($cloud['status'] ? ' Upload cloud OK.' : ''),
            'filename'  => $filename,
            'size'      => backup_format_size(file_exists($filepath) ? filesize($filepath) : 0),
            'url'       => file_exists($filepath) ? site_url('download-backup/' . $filename) : '',
            'cloud_url' => $cloud['url'] ?? '',
        ]);
    }

    // -------------------------------------------------------
    //  Backup Full (DB + Files dalam satu ZIP)
    // -------------------------------------------------------
    public function backup_full()
    {
        // 1. Dump SQL
        $db_result = backup_database();
        if (!$db_result['status']) {
            echo json_encode(['status' => 'error', 'message' => 'Gagal dump DB: ' . $db_result['message']]);
            return;
        }

        $timestamp  = date('Ymd_His');
        $sql_tmp    = $this->backup_dir . 'tmp_db_' . $timestamp . '.sql';
        file_put_contents($sql_tmp, $db_result['sql']);

        // 2. Buat ZIP berisi SQL + folder uploads
        $filename = 'backup_full_' . $timestamp . '.zip';
        $filepath = $this->backup_dir . $filename;

        if (!class_exists('ZipArchive')) {
            @unlink($sql_tmp);
            echo json_encode(['status' => 'error', 'message' => 'ZipArchive tidak tersedia.']);
            return;
        }

        $zip = new ZipArchive();
        $zip->open($filepath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // Tambahkan file SQL
        $zip->addFile($sql_tmp, 'database.sql');

        // Tambahkan folder uploads
        $uploads_path = rtrim(FCPATH . env('STORAGE_LOCAL_PATH', 'uploads'), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        if (is_dir($uploads_path)) {
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($uploads_path, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::LEAVES_ONLY
            );
            foreach ($files as $file) {
                if (!$file->isFile()) continue;
                $zip->addFile($file->getRealPath(), 'uploads/' . substr($file->getRealPath(), strlen($uploads_path)));
            }
        }

        $zip->close();
        @unlink($sql_tmp);

        log_activity('backup_full', 'Backup full: ' . $filename, 'Backup');

        $cloud = upload_backup_to_cloud($filepath, $filename);

        echo json_encode([
            'status'    => 'ok',
            'message'   => 'Backup full berhasil.' . ($cloud['status'] ? ' Upload cloud OK.' : ''),
            'filename'  => $filename,
            'size'      => backup_format_size(file_exists($filepath) ? filesize($filepath) : 0),
            'url'       => file_exists($filepath) ? site_url('download-backup/' . $filename) : '',
            'cloud_url' => $cloud['url'] ?? '',
        ]);
    }

    // -------------------------------------------------------
    //  Restore Database
    // -------------------------------------------------------
    public function restore_db()
    {
        if (empty($_FILES['sql_file']['name'])) {
            echo json_encode(['status' => 'error', 'message' => 'Pilih file .sql terlebih dahulu.']);
            return;
        }

        $ext = strtolower(pathinfo($_FILES['sql_file']['name'], PATHINFO_EXTENSION));
        if ($ext !== 'sql') {
            echo json_encode(['status' => 'error', 'message' => 'Hanya file .sql yang diizinkan.']);
            return;
        }

        $tmp = $_FILES['sql_file']['tmp_name'];
        $result = restore_database($tmp);

        if ($result['status']) {
            log_activity('restore_db', 'Restore database dari file upload', 'Backup');
        }

        echo json_encode([
            'status'  => $result['status'] ? 'ok' : 'error',
            'message' => $result['message'],
        ]);
    }

    // -------------------------------------------------------
    //  Restore Files
    // -------------------------------------------------------
    public function restore_files()
    {
        if (empty($_FILES['zip_file']['name'])) {
            echo json_encode(['status' => 'error', 'message' => 'Pilih file .zip terlebih dahulu.']);
            return;
        }

        $ext = strtolower(pathinfo($_FILES['zip_file']['name'], PATHINFO_EXTENSION));
        if ($ext !== 'zip') {
            echo json_encode(['status' => 'error', 'message' => 'Hanya file .zip yang diizinkan.']);
            return;
        }

        $target = FCPATH . env('STORAGE_LOCAL_PATH', 'uploads');
        $result = restore_files($_FILES['zip_file']['tmp_name'], $target);

        if ($result['status']) {
            log_activity('restore_files', 'Restore files dari file upload', 'Backup');
        }

        echo json_encode([
            'status'  => $result['status'] ? 'ok' : 'error',
            'message' => $result['message'],
        ]);
    }

    // -------------------------------------------------------
    //  Download File Backup
    // -------------------------------------------------------
    public function download($filename)
    {
        // Sanitasi: hanya izinkan karakter aman
        $filename = basename($filename);
        if (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $filename)) {
            show_error('Nama file tidak valid.', 400);
            return;
        }

        $filepath = $this->backup_dir . $filename;
        if (!file_exists($filepath)) {
            show_404();
            return;
        }

        $mime = (pathinfo($filename, PATHINFO_EXTENSION) === 'zip') ? 'application/zip' : 'application/octet-stream';

        header('Content-Type: ' . $mime);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filepath));
        header('Cache-Control: no-cache');
        readfile($filepath);
        exit;
    }

    // -------------------------------------------------------
    //  Hapus File Backup
    // -------------------------------------------------------
    public function delete_backup()
    {
        $filename = basename($this->input->post('filename'));
        if (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $filename)) {
            echo json_encode(['status' => 'error', 'message' => 'Nama file tidak valid.']);
            return;
        }

        $filepath = $this->backup_dir . $filename;
        if (!file_exists($filepath)) {
            echo json_encode(['status' => 'error', 'message' => 'File tidak ditemukan.']);
            return;
        }

        @unlink($filepath);
        log_activity('delete_backup', 'Hapus file backup: ' . $filename, 'Backup');
        echo json_encode(['status' => 'ok', 'message' => 'File backup berhasil dihapus.']);
    }

    // -------------------------------------------------------
    //  Simpan Pengaturan Cloud Storage
    // -------------------------------------------------------
    public function save_cloud_settings()
    {
        $this->load->helper('env');

        $upload_to_cloud = $this->input->post('upload_to_cloud') === 'true' ? 'true' : 'false';
        $delete_local    = $this->input->post('delete_local')    === 'true' ? 'true' : 'false';

        set_env('BACKUP_UPLOAD_TO_CLOUD',    $upload_to_cloud);
        set_env('BACKUP_CLOUD_DELETE_LOCAL', $delete_local);

        log_activity(
            'save_cloud_settings',
            'Update pengaturan cloud backup: upload=' . $upload_to_cloud . ', delete_local=' . $delete_local,
            'Backup'
        );

        echo json_encode(['status' => 'ok', 'message' => 'Pengaturan cloud berhasil disimpan.']);
    }

    // -------------------------------------------------------
    //  Simpan Pengaturan Jadwal Backup
    // -------------------------------------------------------
    public function save_schedule_settings()
    {
        $this->load->helper('env');

        $enabled   = $this->input->post('schedule_enabled')   === 'true'  ? 'true'  : 'false';
        $type      = in_array($this->input->post('schedule_type'), ['db','files','full'])
                     ? $this->input->post('schedule_type') : 'full';
        $frequency = in_array($this->input->post('schedule_frequency'), ['daily','weekly','monthly'])
                     ? $this->input->post('schedule_frequency') : 'daily';
        $time      = preg_match('/^\d{2}:\d{2}$/', $this->input->post('schedule_time'))
                     ? $this->input->post('schedule_time') : '02:00';
        $secret    = trim($this->input->post('cron_secret'));

        set_env('BACKUP_SCHEDULE_ENABLED',   $enabled);
        set_env('BACKUP_SCHEDULE_TYPE',      $type);
        set_env('BACKUP_SCHEDULE_FREQUENCY', $frequency);
        set_env('BACKUP_SCHEDULE_TIME',      $time);

        if ($secret !== '') {
            set_env('CRON_SECRET', $secret);
        }

        log_activity(
            'save_schedule_settings',
            'Update jadwal backup: enabled=' . $enabled . ', type=' . $type . ', freq=' . $frequency . ', time=' . $time,
            'Backup'
        );

        echo json_encode(['status' => 'ok', 'message' => 'Pengaturan jadwal berhasil disimpan.']);
    }

    // -------------------------------------------------------
    //  Helper: List semua file backup
    // -------------------------------------------------------
    private function _list_backups()
    {
        $files = [];
        if (!is_dir($this->backup_dir)) return $files;

        foreach (glob($this->backup_dir . '*.{sql,zip}', GLOB_BRACE) as $f) {
            $name = basename($f);
            $files[] = [
                'name'     => $name,
                'size'     => backup_format_size(filesize($f)),
                'size_raw' => filesize($f),
                'date'     => date('d-m-Y H:i:s', filemtime($f)),
                'type'     => (strpos($name, '_db_') !== false) ? 'database' :
                              (strpos($name, '_files_') !== false ? 'files' : 'full'),
                'ext'      => pathinfo($name, PATHINFO_EXTENSION),
            ];
        }

        // Urutkan terbaru dulu
        usort($files, function($a, $b) {
            return strcmp($b['name'], $a['name']);
        });

        return $files;
    }
}
