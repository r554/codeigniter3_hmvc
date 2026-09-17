<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Installer extends CI_Controller
{
    private $lock_file;

    public function __construct()
    {
        parent::__construct();
        $this->lock_file = APPPATH . 'config/install.lock';
        $this->load->model('Installer_model');
        $this->load->helper(array('url', 'form', 'security'));
    }

    private function locked()
    {
        return is_file($this->lock_file);
    }

    private function guard()
    {
        if ($this->locked()) {
            show_error('Installer sudah dikunci. Hapus install.lock hanya untuk kebutuhan pengembangan.', 403);
        }
    }

    public function index()
    {
        $this->guard();
        $data['requirements'] = $this->requirements();
        $data['db_config'] = array('hostname' => 'localhost', 'username' => 'root', 'database' => 'coatesapp');
        $this->load->view('installer/index', $data);
    }

    public function requirements_json()
    {
        $this->guard();
        $this->output->set_content_type('application/json')->set_output(json_encode($this->requirements()));
    }

    private function requirements()
    {
        $checks = array();
        $add = function ($name, $ok, $actual, $required, $help = '') use (&$checks) {
            $checks[] = compact('name', 'ok', 'actual', 'required', 'help');
        };
        $add('PHP', version_compare(PHP_VERSION, '7.0.0', '>='), PHP_VERSION, '>= 7.0');
        foreach (array('mysqli', 'mbstring', 'openssl', 'json', 'curl', 'fileinfo', 'zip') as $ext) {
            $add('Ekstensi ' . $ext, extension_loaded($ext), extension_loaded($ext) ? 'Aktif' : 'Tidak tersedia', 'Aktif', 'Aktifkan ekstensi pada php.ini');
        }
        foreach (array(APPPATH . 'config' => 'apps/config', APPPATH . 'logs' => 'apps/logs', FCPATH . 'upload' => 'upload') as $path => $label) {
            $add('Permission ' . $label, is_dir($path) && is_writable($path), is_writable($path) ? 'Writable' : 'Tidak writable', 'Writable', 'Berikan permission tulis pada folder tersebut');
        }
        $add('JSON', function_exists('json_encode'), 'json_encode()', 'Tersedia');
        return $checks;
    }

    public function test_database()
    {
        $this->guard();
        $this->output->set_content_type('application/json')->set_output(json_encode($this->Installer_model->test_connection($this->input->post())));
    }

    public function install()
    {
        $this->guard();
        $this->output->set_content_type('application/json');
        $requirements = $this->requirements();
        foreach ($requirements as $item) if (!$item['ok']) return $this->output->set_output(json_encode(array('ok' => false, 'message' => 'Perbaiki semua requirement yang gagal terlebih dahulu.')));
        $result = $this->Installer_model->install($this->input->post());
        if ($result['ok']) {
            @file_put_contents($this->lock_file, date('c') . PHP_EOL);
        }
        $this->output->set_output(json_encode($result));
    }
}
