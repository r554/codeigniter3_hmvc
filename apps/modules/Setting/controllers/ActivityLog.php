<?php defined('BASEPATH') OR exit('No direct script access allowed');

class ActivityLog extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('id_user')) redirect('login');
        $this->load->helper(['activity', 'env']);
        $this->load->database();
    }

    // ----------------------------------------------------------------
    // Halaman utama
    // ----------------------------------------------------------------
    public function index() {
        $data['title']       = 'Log Aktivitas';
        $data['active_menu'] = 'activity-log';

        $this->load->view('layouts/header', $data);
        $this->load->view('v_activity_log/v_home', $data);
        $this->load->view('layouts/footer');
    }

    // ----------------------------------------------------------------
    // DataTables server-side
    // ----------------------------------------------------------------
    public function get_data() {
        $draw   = (int)$this->input->post('draw');
        $start  = (int)$this->input->post('start');
        $length = (int)$this->input->post('length');
        $search = $this->input->post('search')['value'] ?? '';

        // filter opsional
        $filter_user   = $this->input->post('filter_user');
        $filter_action = $this->input->post('filter_action');
        $filter_date   = $this->input->post('filter_date');   // format: YYYY-MM-DD

        $this->db->from('activity_logs');

        // search global
        if ($search !== '') {
            $this->db->group_start();
            $this->db->like('username', $search);
            $this->db->or_like('action', $search);
            $this->db->or_like('description', $search);
            $this->db->or_like('ip_address', $search);
            $this->db->group_end();
        }

        if ($filter_user)   $this->db->where('username', $filter_user);
        if ($filter_action) $this->db->where('action', $filter_action);
        if ($filter_date)   $this->db->where('DATE(created_at)', $filter_date);

        $total_filtered = $this->db->count_all_results('', false);

        $rows = $this->db
            ->order_by('created_at', 'DESC')
            ->limit($length, $start)
            ->get()
            ->result_array();

        $total = $this->db->count_all('activity_logs');

        $result = [];
        foreach ($rows as $i => $row) {
            $badge = $this->_action_badge($row['action']);
            $result[] = [
                'no'          => $start + $i + 1,
                'created_at'  => date('d M Y H:i:s', strtotime($row['created_at'])),
                'username'    => htmlspecialchars($row['username'] ?? '-'),
                'module'      => htmlspecialchars($row['module'] ?? '-'),
                'action'      => $badge,
                'description' => htmlspecialchars($row['description'] ?? '-'),
                'ip_address'  => htmlspecialchars($row['ip_address'] ?? '-'),
            ];
        }

        echo json_encode([
            'draw'            => $draw,
            'recordsTotal'    => $total,
            'recordsFiltered' => $total_filtered,
            'data'            => $result,
        ]);
    }

    // ----------------------------------------------------------------
    // Hapus satu log
    // ----------------------------------------------------------------
    public function delete($id) {
        $this->db->where('id', (int)$id)->delete('activity_logs');
        echo json_encode(['status' => 'ok']);
    }

    // ----------------------------------------------------------------
    // Hapus semua log
    // ----------------------------------------------------------------
    public function clear_all() {
        $this->db->truncate('activity_logs');
        echo json_encode(['status' => 'ok', 'message' => 'Semua log berhasil dihapus.']);
    }

    // ----------------------------------------------------------------
    // Export CSV
    // ----------------------------------------------------------------
    public function export() {
        $rows = $this->db->order_by('created_at', 'DESC')->get('activity_logs')->result_array();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="activity_log_' . date('Ymd_His') . '.csv"');

        $out = fopen('php://output', 'w');
        fputcsv($out, ['No', 'Tanggal', 'Username', 'Modul', 'Aksi', 'Deskripsi', 'IP Address']);
        foreach ($rows as $i => $row) {
            fputcsv($out, [
                $i + 1,
                $row['created_at'],
                $row['username'],
                $row['module'],
                $row['action'],
                $row['description'],
                $row['ip_address'],
            ]);
        }
        fclose($out);
    }

    // ----------------------------------------------------------------
    // Helper: badge HTML per action
    // ----------------------------------------------------------------
    private function _action_badge($action) {
        $map = [
            'login'         => 'success',
            'logout'        => 'default',
            'backup_db'     => 'primary',
            'backup_files'  => 'primary',
            'backup_full'   => 'primary',
            'restore_db'    => 'warning',
            'restore_files' => 'warning',
            'delete_backup' => 'danger',
            'save_config'   => 'info',
        ];
        $cls = $map[$action] ?? 'default';
        return '<span class="label label-' . $cls . '">' . htmlspecialchars($action) . '</span>';
    }
}
