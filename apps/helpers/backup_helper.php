<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Backup Helper
 * Fungsi backup & restore database dan file tanpa membutuhkan akses shell/mysqldump.
 */

// ======================================================
//  DATABASE BACKUP
// ======================================================

/**
 * Generate SQL dump dari database aktif menggunakan MySQLi murni.
 *
 * @param  array  $tables  Daftar tabel yang di-backup. Kosong = semua tabel.
 * @return array  ['status'=>bool, 'sql'=>string, 'message'=>string]
 */
function backup_database($tables = [])
{
    $CI =& get_instance();
    $CI->load->helper('env');

    // Baca konfigurasi DB dari database.php
    $db_config_file = APPPATH . 'config/database.php';
    include($db_config_file);

    $host   = $db['default']['hostname'];
    $user   = $db['default']['username'];
    $pass   = $db['default']['password'];
    $dbname = $db['default']['database'];
    $port   = 3306;

    // Support hostname:port
    if (strpos($host, ':') !== false) {
        list($host, $port) = explode(':', $host, 2);
    }

    $mysqli = @new mysqli($host, $user, $pass, $dbname, (int)$port);
    if ($mysqli->connect_errno) {
        return ['status' => false, 'message' => 'Koneksi DB gagal: ' . $mysqli->connect_error];
    }
    $mysqli->set_charset('utf8mb4');

    // Ambil semua tabel jika tidak dispesifikasi
    if (empty($tables)) {
        $result = $mysqli->query("SHOW TABLES");
        while ($row = $result->fetch_array()) {
            $tables[] = $row[0];
        }
    }

    $sql  = "-- ============================================================\n";
    $sql .= "-- Database Backup: {$dbname}\n";
    $sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
    $sql .= "-- ============================================================\n\n";
    $sql .= "SET FOREIGN_KEY_CHECKS=0;\n";
    $sql .= "SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';\n";
    $sql .= "SET NAMES utf8mb4;\n\n";

    foreach ($tables as $table) {
        $table = $mysqli->real_escape_string($table);

        // DROP + CREATE TABLE
        $create = $mysqli->query("SHOW CREATE TABLE `{$table}`");
        $row    = $create->fetch_assoc();
        $sql   .= "-- Table: {$table}\n";
        $sql   .= "DROP TABLE IF EXISTS `{$table}`;\n";
        $sql   .= $row['Create Table'] . ";\n\n";

        // Data
        $data = $mysqli->query("SELECT * FROM `{$table}`");
        if ($data->num_rows === 0) {
            $sql .= "-- (no data in {$table})\n\n";
            continue;
        }

        $fields = [];
        foreach ($data->fetch_fields() as $f) {
            $fields[] = '`' . $f->name . '`';
        }
        $fields_str = implode(', ', $fields);

        $sql .= "INSERT INTO `{$table}` ({$fields_str}) VALUES\n";
        $rows   = [];
        $data->data_seek(0);
        while ($row = $data->fetch_row()) {
            $vals = [];
            foreach ($row as $val) {
                if ($val === null) {
                    $vals[] = 'NULL';
                } else {
                    $vals[] = "'" . $mysqli->real_escape_string($val) . "'";
                }
            }
            $rows[] = '(' . implode(', ', $vals) . ')';
        }
        $sql .= implode(",\n", $rows) . ";\n\n";
    }

    $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
    $mysqli->close();

    return ['status' => true, 'sql' => $sql, 'message' => 'OK'];
}

// ======================================================
//  FILE BACKUP
// ======================================================

/**
 * Buat ZIP dari sebuah folder.
 *
 * @param  string  $source_path  Path absolut folder yang akan di-zip
 * @param  string  $zip_path     Path absolut file ZIP tujuan
 * @return array   ['status'=>bool, 'message'=>string]
 */
function backup_files($source_path, $zip_path)
{
    if (!class_exists('ZipArchive')) {
        return ['status' => false, 'message' => 'ZipArchive tidak tersedia di server ini.'];
    }

    if (!is_dir($source_path)) {
        return ['status' => false, 'message' => 'Folder tidak ditemukan: ' . $source_path];
    }

    $zip = new ZipArchive();
    if ($zip->open($zip_path, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        return ['status' => false, 'message' => 'Tidak dapat membuat file ZIP: ' . $zip_path];
    }

    $source_path = rtrim(realpath($source_path), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($source_path, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $file) {
        if (!$file->isFile()) continue;
        $file_path     = $file->getRealPath();
        $relative_path = substr($file_path, strlen($source_path));
        $zip->addFile($file_path, $relative_path);
    }

    $zip->close();
    return ['status' => true, 'message' => 'ZIP berhasil dibuat.'];
}

// ======================================================
//  DATABASE RESTORE
// ======================================================

/**
 * Restore database dari file SQL.
 *
 * @param  string  $sql_file  Path absolut file .sql
 * @return array   ['status'=>bool, 'message'=>string]
 */
function restore_database($sql_file)
{
    if (!file_exists($sql_file)) {
        return ['status' => false, 'message' => 'File SQL tidak ditemukan.'];
    }

    $db_config_file = APPPATH . 'config/database.php';
    include($db_config_file);

    $host   = $db['default']['hostname'];
    $user   = $db['default']['username'];
    $pass   = $db['default']['password'];
    $dbname = $db['default']['database'];
    $port   = 3306;

    if (strpos($host, ':') !== false) {
        list($host, $port) = explode(':', $host, 2);
    }

    $mysqli = @new mysqli($host, $user, $pass, $dbname, (int)$port);
    if ($mysqli->connect_errno) {
        return ['status' => false, 'message' => 'Koneksi DB gagal: ' . $mysqli->connect_error];
    }
    $mysqli->set_charset('utf8mb4');

    // Baca SQL, pisahkan per statement
    $sql_content = file_get_contents($sql_file);

    // Hapus komentar
    $sql_content = preg_replace('/^--.*$/m', '', $sql_content);
    $sql_content = preg_replace('/\/\*.*?\*\//s', '', $sql_content);

    // Split per delimiter ;
    $statements = array_filter(array_map('trim', explode(';', $sql_content)));

    $mysqli->query("SET FOREIGN_KEY_CHECKS=0");
    $errors = [];
    foreach ($statements as $stmt) {
        if (empty($stmt)) continue;
        if (!$mysqli->query($stmt)) {
            $errors[] = $mysqli->error . ' | Query: ' . substr($stmt, 0, 80);
        }
    }
    $mysqli->query("SET FOREIGN_KEY_CHECKS=1");
    $mysqli->close();

    if (!empty($errors)) {
        return ['status' => false, 'message' => 'Restore selesai dengan ' . count($errors) . ' error: ' . implode('; ', array_slice($errors, 0, 3))];
    }

    return ['status' => true, 'message' => 'Database berhasil di-restore.'];
}

// ======================================================
//  FILE RESTORE
// ======================================================

/**
 * Restore files dari ZIP ke folder target.
 *
 * @param  string  $zip_file     Path absolut file ZIP
 * @param  string  $target_path  Path absolut folder tujuan ekstrak
 * @return array   ['status'=>bool, 'message'=>string]
 */
function restore_files($zip_file, $target_path)
{
    if (!class_exists('ZipArchive')) {
        return ['status' => false, 'message' => 'ZipArchive tidak tersedia di server ini.'];
    }

    if (!file_exists($zip_file)) {
        return ['status' => false, 'message' => 'File ZIP tidak ditemukan.'];
    }

    if (!is_dir($target_path)) {
        @mkdir($target_path, 0775, true);
    }

    $zip = new ZipArchive();
    if ($zip->open($zip_file) !== true) {
        return ['status' => false, 'message' => 'Tidak dapat membuka file ZIP.'];
    }

    $zip->extractTo($target_path);
    $zip->close();

    return ['status' => true, 'message' => 'Files berhasil di-restore ke: ' . $target_path];
}

// ======================================================
//  UTILITY
// ======================================================

/**
 * Format ukuran file ke human-readable string.
 */
function backup_format_size($bytes)
{
    if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
    if ($bytes >= 1048576)    return number_format($bytes / 1048576, 2) . ' MB';
    if ($bytes >= 1024)       return number_format($bytes / 1024, 2) . ' KB';
    return $bytes . ' B';
}

/**
 * Pastikan folder backups/ ada dan dilindungi .htaccess.
 *
 * @param  string  $backup_dir  Path absolut folder backup
 */
function backup_ensure_dir($backup_dir)
{
    if (!is_dir($backup_dir)) {
        mkdir($backup_dir, 0755, true);
    }

    $htaccess = $backup_dir . DIRECTORY_SEPARATOR . '.htaccess';
    if (!file_exists($htaccess)) {
        file_put_contents($htaccess, "Options -Indexes\nDeny from all\n");
    }
}

// ======================================================
//  CLOUD UPLOAD
// ======================================================

/**
 * Upload file backup ke cloud storage (S3/R2/MinIO).
 * Memanfaatkan storage_helper yang sudah ada.
 *
 * @param  string  $local_path  Path absolut file backup lokal
 * @param  string  $filename    Nama file (tanpa path)
 * @return array   ['status'=>bool, 'url'=>string, 'message'=>string]
 */
function upload_backup_to_cloud($local_path, $filename)
{
    $CI =& get_instance();
    $CI->load->helper(['env', 'storage']);

    // Cek apakah fitur cloud upload aktif
    if (env('BACKUP_UPLOAD_TO_CLOUD', 'false') !== 'true') {
        return ['status' => false, 'message' => 'Cloud upload tidak aktif.'];
    }

    if (!file_exists($local_path)) {
        return ['status' => false, 'message' => 'File lokal tidak ditemukan.'];
    }

    // Path di cloud: backups/nama_file
    $cloud_path = 'backups/' . $filename;
    $mime       = (pathinfo($filename, PATHINFO_EXTENSION) === 'zip')
                    ? 'application/zip'
                    : 'application/octet-stream';

    $result = storage_upload($local_path, $cloud_path, $mime);

    if ($result['status']) {
        // Hapus file lokal jika dikonfigurasi demikian
        if (env('BACKUP_CLOUD_DELETE_LOCAL', 'false') === 'true') {
            @unlink($local_path);
        }
        return [
            'status'  => true,
            'url'     => $result['url'] ?? '',
            'message' => 'Upload ke cloud berhasil.',
        ];
    }

    return [
        'status'  => false,
        'url'     => '',
        'message' => 'Upload ke cloud gagal: ' . ($result['message'] ?? 'Unknown error'),
    ];
}
