<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CrudGenerator extends AUTH_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_CrudGenerator');
        $this->load->helper('file');
    }

    public function loadkonten($page, $data) {
        $data['userdata'] = $this->userdata;
        $ajax = ($this->input->post('status_link') == 'ajax' ? true : false);
        if (!$ajax) {
            $this->load->view('Dashboard/layouts/header', $data);
        }
        $this->load->view($page, $data);
        if (!$ajax) $this->load->view('Dashboard/layouts/footer', $data);
    }

    public function index() {
        $data['page']      = 'CRUD Generator';
        $data['judul']     = 'CRUD Generator';
        $data['userdata']  = $this->userdata;
        $this->loadkonten('CrudGenerator/v_generator/home', $data);
    }

    // AJAX: return list of all DB tables
    public function get_tables() {
        $tables = $this->M_CrudGenerator->get_tables();
        echo json_encode(['status' => 'ok', 'data' => $tables]);
    }

    // AJAX: return columns for a given table
    public function get_columns() {
        $table = $this->input->post('table');
        if (empty($table)) {
            echo json_encode(['status' => 'gagal', 'message' => 'Table name required']);
            return;
        }
        $columns = $this->M_CrudGenerator->get_columns($table);
        $out = [];
        foreach ($columns as $col) {
            $out[] = [
                'field'   => $col->name,
                'type'    => $col->type,
                'max_length' => $col->max_length,
                'primary_key' => $col->primary_key,
            ];
        }
        echo json_encode(['status' => 'ok', 'data' => $out]);
    }

    // AJAX: preview generated code without writing to disk
    public function preview() {
        $config = $this->_parse_config();
        if (!$config) {
            echo json_encode(['status' => 'gagal', 'message' => 'Konfigurasi tidak lengkap']);
            return;
        }
        // Primary key hanya dipakai internal, bukan kolom tampilan atau field form.
        $pk = $config['primary_key'];
        $config['list_columns'] = array_values(array_filter(isset($config['list_columns']) ? $config['list_columns'] : [], function ($col) use ($pk) {
            return isset($col['field']) && $col['field'] !== $pk;
        }));
        $config['form_fields'] = array_values(array_filter(isset($config['form_fields']) ? $config['form_fields'] : [], function ($field) use ($pk) {
            return isset($field['field']) && $field['field'] !== $pk;
        }));

        // Re-use model methods via reflection for preview only
        $m = $this->M_CrudGenerator;
        $files = [
            'controller' => $this->_call_tpl($m, 'tpl_controller', $config),
            'model'      => $this->_call_tpl($m, 'tpl_model',      $config),
            'view_home'  => $this->_call_tpl($m, 'tpl_view_home',  $config),
            'view_tambah'=> $this->_call_tpl($m, 'tpl_view_tambah',$config),
            'view_update'=> $this->_call_tpl($m, 'tpl_view_update', $config),
        ];
        echo json_encode(['status' => 'ok', 'data' => $files]);
    }

    // AJAX: generate and write all files to disk
    public function generate() {
        $config = $this->_parse_config();
        if (!$config) {
            echo json_encode(['status' => 'gagal', 'message' => 'Konfigurasi tidak lengkap']);
            return;
        }

        // Safety: sanitize module name (PascalCase, alphanumeric only)
        $config['module_name'] = preg_replace('/[^A-Za-z0-9]/', '', $config['module_name']);
        if (empty($config['module_name'])) {
            echo json_encode(['status' => 'gagal', 'message' => 'Nama module tidak valid']);
            return;
        }

        $files = $this->M_CrudGenerator->generate_files($config);

        $relative = [];
        foreach ($files as $f) {
            $relative[] = str_replace(APPPATH, 'apps/', $f);
        }

        // Always register routes
        $route_result = $this->M_CrudGenerator->register_routes($config);

        // Create menu if requested
        $menu_result = null;
        $create_menu = $this->input->post('create_menu');
        if ($create_menu == '1') {
            $menu_result = $this->M_CrudGenerator->create_menu($config, $this->userdata->nama);
        }

        echo json_encode([
            'status' => 'berhasil',
            'files'  => $relative,
            'module' => $config['module_name'],
            'url'    => site_url($config['kode_menu']),
            'menu'   => $menu_result,
            'route'  => $route_result
        ]);
    }

    // ----------------------------------------------------------------
    // Helpers
    // ----------------------------------------------------------------

    private function _parse_config() {
        $raw = $this->input->post('config');
        if (empty($raw)) return false;
        $config = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE) return false;
        if (empty($config['module_name']) || empty($config['table_name']) || empty($config['primary_key'])) return false;
        return $config;
    }

    // Call private template methods on model via Reflection
    private function _call_tpl($model, $method, $config) {
        $ref = new ReflectionMethod($model, $method);
        $ref->setAccessible(true);
        return $ref->invoke($model, $config);
    }

    // ----------------------------------------------------------------
    // Destroyer
    // ----------------------------------------------------------------

    public function destroyer() {
        $data['userdata'] = $this->userdata;
        $data['page']     = 'Module Destroyer';
        $data['judul']    = 'Module Destroyer';
        $ajax = ($this->input->post('status_link') == 'ajax' ? true : false);
        if (!$ajax) $this->load->view('Dashboard/layouts/header', $data);
        $this->load->view('v_generator/destroy', $data);
        if (!$ajax) $this->load->view('Dashboard/layouts/footer', $data);
    }

    public function ajax_module_list() {
        $modules = $this->M_CrudGenerator->list_generated_modules();
        echo json_encode(['status' => 'ok', 'data' => $modules]);
    }

    public function hapus_module() {
        $module    = $this->input->post('module');
        $del_files = $this->input->post('del_files');
        $del_route = $this->input->post('del_route');
        $del_menu  = $this->input->post('del_menu');
        $kode_menu = $this->input->post('kode_menu');

        if (empty($module)) {
            echo json_encode(['status' => 'gagal', 'message' => 'Nama modul tidak boleh kosong']);
            return;
        }

        $result = ['status' => 'berhasil', 'detail' => []];

        if ($del_files == '1') {
            $r = $this->M_CrudGenerator->destroy_module($module);
            $result['detail']['files'] = $r['status'];
            if ($r['status'] === 'gagal') {
                echo json_encode(['status' => 'gagal', 'message' => $r['message']]);
                return;
            }
        }

        if ($del_route == '1') {
            $result['detail']['route'] = $this->M_CrudGenerator->remove_routes($module);
        }

        if ($del_menu == '1') {
            $result['detail']['menu'] = $this->M_CrudGenerator->remove_menu($kode_menu ?: strtolower($module));
        }

        echo json_encode($result);
    }
}
