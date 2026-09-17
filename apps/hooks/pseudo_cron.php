<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Hook: Pseudo-Cron untuk Scheduled Backup
 * Dipanggil setiap request via post_controller_constructor.
 * Menggunakan probabilitas 1% agar tidak memperlambat setiap request.
 */
function pseudo_cron_backup()
{
    // Hanya jalan ~1% dari semua request untuk efisiensi
    if (mt_rand(1, 100) > 1) return;

    // Load env helper
    if (!function_exists('env')) {
        require_once APPPATH . 'helpers/env_helper.php';
    }

    if (env('BACKUP_SCHEDULE_ENABLED', 'false') !== 'true') return;

    // Hindari rekursi jika sedang di endpoint cron itu sendiri
    $CI =& get_instance();
    $controller = $CI->router->fetch_class();
    if (strtolower($controller) === 'cron') return;

    // Load helper backup dan activity jika belum
    if (!function_exists('backup_database')) {
        $CI->load->helper(['backup', 'activity']);
    }

    // Jalankan cek jadwal via Cron controller (load langsung)
    $cron = new Setting_Cron();
    $cron->check_and_run();
}
