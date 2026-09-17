<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Activity Helper
 * Mencatat aktivitas pengguna ke tabel activity_logs
 *
 * Cara pakai:
 *   $this->load->helper('activity');
 *   log_activity('login', 'User berhasil login', 'Auth');
 */

if (!function_exists('log_activity')) {
    /**
     * Catat satu baris aktivitas ke tabel activity_logs
     *
     * @param string $action      Kode aksi: login, logout, backup_db, delete_user, dst.
     * @param string $description Deskripsi detail (opsional)
     * @param string $module      Nama modul (opsional)
     */
    function log_activity($action, $description = '', $module = '') {
        $CI =& get_instance();

        // ambil info user dari session
        $user_id  = $CI->session->userdata('id_user')  ?: null;
        $username = $CI->session->userdata('username')  ?: null;

        // fallback nama modul dari router jika kosong
        if ($module === '') {
            $module = ucfirst($CI->router->fetch_class());
        }

        $data = [
            'user_id'     => $user_id,
            'username'    => $username,
            'action'      => $action,
            'module'      => $module,
            'description' => $description,
            'ip_address'  => $CI->input->ip_address(),
            'user_agent'  => substr($CI->input->user_agent(), 0, 255),
            'created_at'  => date('Y-m-d H:i:s'),
        ];

        $CI->db->insert('activity_logs', $data);
    }
}
