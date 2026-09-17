<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cron Controller
 *
 * Endpoint untuk pseudo-cron scheduled backup.
 * Bisa dipanggil via:
 *   - Server cron job:  wget -q -O /dev/null "{base_url}/cron/run-backup?token=SECRET"
 *   - Trigger otomatis: setiap request ke aplikasi (via hook) memanggil check_and_run_backup()
 *
 * Akses endpoint ini WAJIB menyertakan token yang cocok dengan CRON_SECRET di .env.
 */
class Cron extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['env', 'backup', 'activity']);
    }

    // -------------------------------------------------------
    //  Endpoint: /cron/run-backup?token=SECRET
    // -------------------------------------------------------
    public function run_backup()
    {
        // Validasi token
        $token        = $this->input->get('token');
        $cron_secret  = env('CRON_SECRET', '');

        if (empty($cron_secret) || $token !== $cron_secret) {
            show_error('Unauthorized', 403);
            return;
        }

        $result = $this->_do_scheduled_backup();
        echo json_encode($result);
    }

    // -------------------------------------------------------
    //  Dipanggil dari hook (pseudo-cron) — tidak butuh token
    //  karena dipanggil secara internal
    // -------------------------------------------------------
    public function check_and_run()
    {
        $result = $this->_do_scheduled_backup();
        // Tidak output apapun — dipanggil dari hook
        return $result;
    }

    // -------------------------------------------------------
    //  Core: cek jadwal dan jalankan backup jika waktunya
    // -------------------------------------------------------
    private function _do_scheduled_backup()
    {
        if (env('BACKUP_SCHEDULE_ENABLED', 'false') !== 'true') {
            return ['status' => 'skip', 'message' => 'Scheduled backup tidak aktif.'];
        }

        $frequency = env('BACKUP_SCHEDULE_FREQUENCY', 'daily'); // daily | weekly | monthly
        $time_str  = env('BACKUP_SCHEDULE_TIME', '02:00');       // HH:MM
        $last_run  = env('BACKUP_SCHEDULE_LAST_RUN', '');

        $now       = time();
        $today     = date('Y-m-d');
        $now_time  = date('H:i');

        // Parse jam jadwal
        list($sched_h, $sched_m) = explode(':', $time_str . ':00');
        $sched_time = sprintf('%02d:%02d', (int)$sched_h, (int)$sched_m);

        // Cek apakah sudah lewat waktu jadwal hari ini
        if ($now_time < $sched_time) {
            return ['status' => 'skip', 'message' => 'Belum waktunya backup (' . $sched_time . ').'];
        }

        // Cek apakah sudah pernah jalan dalam periode ini
        $last_run_ts = $last_run ? strtotime($last_run) : 0;

        $should_run = false;
        switch ($frequency) {
            case 'daily':
                // Harus jalan minimal 1x hari ini
                $should_run = (date('Y-m-d', $last_run_ts) !== $today);
                break;
            case 'weekly':
                // Harus jalan minimal 1x minggu ini (Senin s/d Minggu)
                $start_of_week = date('Y-m-d', strtotime('monday this week'));
                $should_run    = ($last_run_ts < strtotime($start_of_week));
                break;
            case 'monthly':
                // Harus jalan minimal 1x bulan ini
                $start_of_month = date('Y-m-01');
                $should_run     = ($last_run_ts < strtotime($start_of_month));
                break;
            default:
                $should_run = false;
        }

        if (!$should_run) {
            return ['status' => 'skip', 'message' => 'Backup sudah dijalankan dalam periode ini (last: ' . $last_run . ').'];
        }

        // Jalankan backup
        $type      = env('BACKUP_SCHEDULE_TYPE', 'full'); // db | files | full
        $backup_dir = FCPATH . 'backups' . DIRECTORY_SEPARATOR;
        backup_ensure_dir($backup_dir);

        $result  = ['status' => false, 'message' => 'Tipe backup tidak dikenal.'];
        $filename = '';

        if ($type === 'db' || $type === 'full') {
            $db_result = backup_database();
            if (!$db_result['status']) {
                return ['status' => 'error', 'message' => 'Gagal dump DB: ' . $db_result['message']];
            }
        }

        switch ($type) {
            case 'db':
                $filename = 'backup_db_sched_' . date('Ymd_His') . '.sql';
                file_put_contents($backup_dir . $filename, $db_result['sql']);
                $result = ['status' => true, 'message' => 'Backup DB selesai.'];
                break;

            case 'files':
                $filename     = 'backup_files_sched_' . date('Ymd_His') . '.zip';
                $uploads_path = FCPATH . env('STORAGE_LOCAL_PATH', 'uploads');
                $result       = backup_files($uploads_path, $backup_dir . $filename);
                break;

            case 'full':
                $timestamp  = date('Ymd_His');
                $sql_tmp    = $backup_dir . 'tmp_sched_' . $timestamp . '.sql';
                file_put_contents($sql_tmp, $db_result['sql']);

                $filename = 'backup_full_sched_' . $timestamp . '.zip';
                $filepath = $backup_dir . $filename;

                $zip = new ZipArchive();
                $zip->open($filepath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
                $zip->addFile($sql_tmp, 'database.sql');

                $uploads_path = rtrim(FCPATH . env('STORAGE_LOCAL_PATH', 'uploads'), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
                if (is_dir($uploads_path)) {
                    $files_iter = new RecursiveIteratorIterator(
                        new RecursiveDirectoryIterator($uploads_path, RecursiveDirectoryIterator::SKIP_DOTS),
                        RecursiveIteratorIterator::LEAVES_ONLY
                    );
                    foreach ($files_iter as $file) {
                        if (!$file->isFile()) continue;
                        $zip->addFile($file->getRealPath(), 'uploads/' . substr($file->getRealPath(), strlen($uploads_path)));
                    }
                }
                $zip->close();
                @unlink($sql_tmp);
                $result = ['status' => true, 'message' => 'Backup full selesai.'];
                break;
        }

        if (!$result['status']) {
            return ['status' => 'error', 'message' => $result['message']];
        }

        // Update last run
        set_env('BACKUP_SCHEDULE_LAST_RUN', date('Y-m-d H:i:s'));

        // Cloud upload jika aktif
        $cloud_msg = '';
        if ($filename) {
            $cloud = upload_backup_to_cloud($backup_dir . $filename, $filename);
            if ($cloud['status']) {
                $cloud_msg = ' Upload cloud OK.';
            }
        }

        // Log aktivitas
        log_activity('scheduled_backup', 'Scheduled backup (' . $type . '): ' . $filename . $cloud_msg, 'Backup');

        return [
            'status'   => 'ok',
            'message'  => $result['message'] . $cloud_msg,
            'filename' => $filename,
        ];
    }
}
