<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_CrudGenerator extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('file');
    }

    // ----------------------------------------------------------------
    // DB Introspection
    // ----------------------------------------------------------------

    public function get_tables() {
        $tables = $this->db->list_tables();
        return $tables;
    }

    public function get_columns($table) {
        $fields = $this->db->field_data($table);
        return $fields;
    }

    // ----------------------------------------------------------------
    // File Generator
    // ----------------------------------------------------------------

    public function generate_files($config) {
        /*
         * $config = [
         *   'module_name'   => 'TestModule',       // PascalCase, used as folder & class name
         *   'table_name'    => 'tbl_test',
         *   'primary_key'   => 'id_test',
         *   'kode_menu'     => 'test-module',
         *   'judul'         => 'Test Module',
         *   'list_columns'  => [ ['field'=>'nama_test','label'=>'Nama Test'], ... ],
         *   'form_fields'   => [ ['field'=>'nama_test','label'=>'Nama Test','type'=>'text','required'=>true], ... ],
         * ]
         */

        $module    = $config['module_name'];
        $base_path = APPPATH . 'modules/' . $module . '/';
        $pk = $config['primary_key'];
        $config['list_columns'] = array_values(array_filter(isset($config['list_columns']) ? $config['list_columns'] : [], function ($col) use ($pk) {
            return isset($col['field']) && $col['field'] !== $pk;
        }));
        $config['form_fields'] = array_values(array_filter(isset($config['form_fields']) ? $config['form_fields'] : [], function ($field) use ($pk) {
            return isset($field['field']) && $field['field'] !== $pk;
        }));

        $dirs = [
            $base_path . 'controllers',
            $base_path . 'models',
            $base_path . 'views/v_' . strtolower($module),
        ];

        foreach ($dirs as $dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
        }

        $files = [];

        // Controller
        $ctrl_path = $base_path . 'controllers/' . $module . '.php';
        $ctrl_code = $this->tpl_controller($config);
        write_file($ctrl_path, $ctrl_code);
        $files[] = $ctrl_path;

        // Model
        $model_name = 'M_' . strtolower($module);
        $model_path = $base_path . 'models/' . $model_name . '.php';
        $model_code = $this->tpl_model($config);
        write_file($model_path, $model_code);
        $files[] = $model_path;

        // Views
        $view_dir = $base_path . 'views/v_' . strtolower($module) . '/';

        $home_path = $view_dir . 'home.php';
        write_file($home_path, $this->tpl_view_home($config));
        $files[] = $home_path;

        $tambah_path = $view_dir . 'tambah.php';
        write_file($tambah_path, $this->tpl_view_tambah($config));
        $files[] = $tambah_path;

        $update_path = $view_dir . 'update.php';
        write_file($update_path, $this->tpl_view_update($config));
        $files[] = $update_path;

        // Copy shared models required by AUTH_Controller (HMVC resolves per-module)
        $shared_models = ['M_admin', 'M_sidebar'];
        $src_module = APPPATH . 'modules/Dashboard/models/';
        foreach ($shared_models as $m) {
            $src  = $src_module . $m . '.php';
            $dest = $base_path . 'models/' . $m . '.php';
            if (file_exists($src) && !file_exists($dest)) {
                copy($src, $dest);
                $files[] = $dest;
            }
        }

        return $files;
    }

    /**
     * Create menu entry in tbl_menu
     * Returns array with status and menu_id
     */
    public function create_menu($config, $created_by = 'system') {
        $kode_menu = $config['kode_menu'];
        $judul     = $config['judul'];
        $module    = $config['module_name'];

        // Check if menu already exists
        $existing = $this->db->get_where('tbl_menu', ['kode_menu' => $kode_menu])->row();
        if ($existing) {
            return ['status' => 'exists', 'menu_id' => $existing->id_menu];
        }

        // Get max urutan for new menu
        $max_urutan = $this->db->select_max('urutan')->get('tbl_menu')->row()->urutan;
        $urutan = $max_urutan ? $max_urutan + 1 : 99;

        $data = [
            'nama_menu'         => $judul,
            'icon'              => 'fa fa-circle-o',
            'link'              => $kode_menu,
            'kode_menu'         => $kode_menu,
            'parent'            => '',
            'urutan'            => $urutan,
            'menu_file'         => 'view,add,edit,del',
            'created_by'        => $created_by,
            'last_created_date' => date('Y-m-d H:i:s')
        ];

        $this->db->insert('tbl_menu', $data);
        $menu_id = $this->db->insert_id();

        return ['status' => 'created', 'menu_id' => $menu_id];
    }

    /**
     * Register routes for a generated module in routes.php
     * Returns 'added' or 'exists'
     */
    public function register_routes($config) {
        $module    = $config['module_name'];
        $kode_menu = $config['kode_menu'];
        $routes_path = APPPATH . 'config/routes.php';

        $content = file_get_contents($routes_path);

        // Check if route already registered
        $marker = "route modul {$lower} (generated)";
        if (strpos($content, $marker) !== FALSE) {
            return 'exists';
        }

        $lower = strtolower($module);
        $route_block = "\n/*   route modul {$lower} (generated)  */\n"
            . "\$route['{$kode_menu}']                        = '{$module}/{$module}/index';\n"
            . "\$route['{$kode_menu}/ajax_list']              = '{$module}/{$module}/ajax_list';\n"
            . "\$route['add-{$lower}']                        = '{$module}/{$module}/Add';\n"
            . "\$route['{$module}/{$module}/prosesAdd']        = '{$module}/{$module}/prosesAdd';\n"
            . "\$route['edit-{$lower}/(:any)']                = '{$module}/{$module}/Edit/\$1';\n"
            . "\$route['{$module}/{$module}/prosesUpdate']     = '{$module}/{$module}/prosesUpdate';\n"
            . "\$route['{$module}/{$module}/hapus']            = '{$module}/{$module}/hapus';\n";

        // Insert before the End of file comment
        $end_marker = '/* End of file routes.php */';
        $content = str_replace($end_marker, $route_block . $end_marker, $content);

        write_file($routes_path, $content);
        return 'added';
    }

    // ----------------------------------------------------------------
    // Destroyer Methods
    // ----------------------------------------------------------------

    // Core modules that cannot be deleted
    private $core_modules = ['Category', 'CrudGenerator', 'Dashboard', 'Default', 'Setting'];

    /**
     * Scan modules folder and return list of non-core modules
     * with status: has_menu, has_route
     */
    public function list_generated_modules() {
        $modules_path = APPPATH . 'modules/';
        $all = array_filter(glob($modules_path . '*'), 'is_dir');
        $routes_content = file_get_contents(APPPATH . 'config/routes.php');

        $result = [];
        foreach ($all as $dir) {
            $name = basename($dir);
            if (in_array($name, $this->core_modules)) continue;

            // Check if has menu
            $menu = $this->db->get_where('tbl_menu', ['kode_menu' => strtolower($name)])->row();
            if (!$menu) {
                // Try by link
                $menu = $this->db->get_where('tbl_menu', ['link' => strtolower($name)])->row();
            }

            // Check if has route
            $has_route = strpos($routes_content, "'{$name}/{$name}/index'") !== FALSE
                      || strpos($routes_content, "= '{$name}/{$name}/index'") !== FALSE;

            $result[] = [
                'module'    => $name,
                'path'      => $dir,
                'has_menu'  => $menu ? true : false,
                'menu_id'   => $menu ? $menu->id_menu : null,
                'kode_menu' => $menu ? $menu->kode_menu : strtolower($name),
                'has_route' => $has_route,
            ];
        }
        return $result;
    }

    /**
     * Delete entire module folder recursively
     */
    public function destroy_module($module_name) {
        if (in_array($module_name, $this->core_modules)) {
            return ['status' => 'gagal', 'message' => 'Modul core tidak bisa dihapus'];
        }
        $path = APPPATH . 'modules/' . $module_name;
        if (!is_dir($path)) {
            return ['status' => 'gagal', 'message' => 'Folder modul tidak ditemukan'];
        }
        $this->_delete_dir($path);
        return ['status' => 'berhasil'];
    }

    private function _delete_dir($dir) {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $full = $dir . DIRECTORY_SEPARATOR . $file;
            if (is_dir($full)) {
                $this->_delete_dir($full);
            } else {
                unlink($full);
            }
        }
        rmdir($dir);
    }

    /**
     * Remove module route block from routes.php
     */
    public function remove_routes($module_name) {
        $routes_path = APPPATH . 'config/routes.php';
        $content = file_get_contents($routes_path);
        $lower = strtolower($module_name);

        // Remove generated block comment style
        $pattern = '/\/\*\s*route modul ' . preg_quote($lower, '/') . '.*?\*\/\s*(\$route\[.*?\].*?;\s*)+/s';
        $new_content = preg_replace($pattern, '', $content);

        // Also remove any stray individual routes referencing this module
        $pattern2 = '/^\$route\[.*?\]\s*=\s*[\'"]' . preg_quote($module_name, '/') . '\/' . preg_quote($module_name, '/') . '\/.*?[\'"];\s*$/m';
        $new_content = preg_replace($pattern2, '', $new_content);

        if ($new_content !== $content) {
            write_file($routes_path, $new_content);
            return 'removed';
        }
        return 'not_found';
    }

    /**
     * Remove menu entry and all its akses from tbl_menu + menu_akses
     */
    public function remove_menu($kode_menu) {
        // Find menu
        $menu = $this->db->get_where('tbl_menu', ['kode_menu' => $kode_menu])->row();
        if (!$menu) {
            $menu = $this->db->get_where('tbl_menu', ['link' => $kode_menu])->row();
        }
        if (!$menu) return 'not_found';

        $menu_id = $menu->id_menu;

        // Delete akses first
        $this->db->delete('menu_akses', ['id_menu' => $menu_id]);

        // Delete menu
        $this->db->delete('tbl_menu', ['id_menu' => $menu_id]);

        return 'removed';
    }

    // ----------------------------------------------------------------
    // Templates
    // ----------------------------------------------------------------

    private function tpl_controller($c) {
        $mod    = $c['module_name'];
        $table  = $c['table_name'];
        $pk     = $c['primary_key'];
        $kode   = $c['kode_menu'];
        $judul  = $c['judul'];
        $model  = 'M_' . strtolower($mod);
        $folder = 'v_' . strtolower($mod) . '/';

        // Build ajax_list row columns
        $list_rows = '';
        foreach ($c['list_columns'] as $col) {
            if ($col['field'] === $pk) continue;
            $list_rows .= "\t\t\t\$row[] = \$item->{$col['field']};\n";
        }

        // Build prosesAdd data array
        $add_data = '';
        foreach ($c['form_fields'] as $f) {
            $add_data .= "\t\t\t'{$f['field']}' => \$this->input->post('{$f['field']}'),\n";
        }

        // Build prosesUpdate data array
        $upd_data = '';
        foreach ($c['form_fields'] as $f) {
            $upd_data .= "\t\t\t'{$f['field']}' => \$this->input->post('{$f['field']}'),\n";
        }

        // Build required field validation (first required field)
        $first_required = '';
        foreach ($c['form_fields'] as $f) {
            if (!empty($f['required'])) {
                $first_required = $f['field'];
                break;
            }
        }
        if (empty($first_required) && !empty($c['form_fields'])) {
            $first_required = $c['form_fields'][0]['field'];
        }

        return <<<PHP
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class {$mod} extends AUTH_Controller {

\tconst __tableName = '{$table}';
\tconst __tableId   = '{$pk}';
\tconst __folder    = '{$folder}';
\tconst __kode_menu = '{$kode}';
\tconst __title     = '{$judul}';
\tconst __model     = '{$model}';

\tpublic function __construct() {
\t\tparent::__construct();
\t\t\$this->load->model(self::__model);
\t\t\$this->load->model('M_sidebar');
\t}

\tpublic function loadkonten(\$page, \$data) {
\t\t\$data['userdata'] = \$this->userdata;
\t\t\$ajax = (\$this->input->post('status_link') == 'ajax' ? true : false);
\t\tif (!\$ajax) {
\t\t\t\$this->load->view('Dashboard/layouts/header', \$data);
\t\t}
\t\t\$this->load->view(\$page, \$data);
\t\tif (!\$ajax) \$this->load->view('Dashboard/layouts/footer', \$data);
\t}

\tpublic function index() {
\t\t\$accessAdd = \$this->M_sidebar->access('add', self::__kode_menu);
\t\t\$data['accessAdd'] = \$accessAdd->menuview;
\t\t\$data['userdata']  = \$this->userdata;
\t\t\$data['page']      = self::__title;
\t\t\$data['judul']     = self::__title;
\t\t\$this->loadkonten(self::__folder . 'home', \$data);
\t}

\tpublic function ajax_list() {
\t\t\$accessEdit = \$this->M_sidebar->access('edit', self::__kode_menu);
\t\t\$accessDel  = \$this->M_sidebar->access('del',  self::__kode_menu);
\t\t\$list = \$this->{$model}->get_data();
\t\t\$data = array();
\t\t\$no   = \$_POST['start'];
\t\tforeach (\$list as \$item) {
\t\t\t\$no++;
\t\t\t\$row   = array();
\t\t\t\$row[] = \$no;
{$list_rows}
\t\t\t\$btnEdit = '';
\t\t\tif (\$accessEdit->menuview > 0) {
\t\t\t\t\$btnEdit = anchor('edit-{$kode}/' . \$item->{$pk}, ' <span class="fa fa-edit"></span> ', ' class="btn btn-sm btn-primary klik ajaxify" ');
\t\t\t}
\t\t\t\$btnDel = '';
\t\t\tif (\$accessDel->menuview > 0) {
\t\t\t\t\$btnDel = '<button class="btn btn-sm btn-danger hapus-{$kode}" data-id=\\''. \$item->{$pk} .'\\'><i class="glyphicon glyphicon-trash"></i></button>';
\t\t\t}
\t\t\t\$row[] = \$btnEdit . '  ' . \$btnDel;
\t\t\t\$data[] = \$row;
\t\t}
\t\t\$output = array(
\t\t\t'draw' => \$_POST['draw'],
\t\t\t'data' => \$data,
\t\t);
\t\techo json_encode(\$output);
\t}

\tpublic function Add() {
\t\t\$data['userdata'] = \$this->userdata;
\t\t\$access = \$this->M_sidebar->access('add', self::__kode_menu);
\t\tif (\$access->menuview == 0) {
\t\t\t\$data['page']  = self::__title;
\t\t\t\$data['judul'] = self::__title;
\t\t\t\$this->loadkonten('Dashboard/layouts/no_akses', \$data);
\t\t} else {
\t\t\t\$data['page']  = self::__title;
\t\t\t\$data['judul'] = self::__title;
\t\t\t\$this->loadkonten(self::__folder . 'tambah', \$data);
\t\t}
\t}

\tpublic function prosesAdd() {
\t\t\$username = \$this->userdata->nama;
\t\t\$date     = date('Y-m-d H:i:s');
\t\t\$this->db->trans_begin();
\t\tif (isset(\$_POST['{$first_required}']) && !empty(\$_POST['{$first_required}'])) {
\t\t\t\$data = array(
{$add_data}\t\t\t'created_by'   => \$username,
\t\t\t'created_date' => \$date,
\t\t\t'updated_by'   => \$username,
\t\t\t'updated_date' => \$date,
\t\t\t);
\t\t\t\$result = \$this->db->insert(self::__tableName, \$data);
\t\t\tif (\$this->db->trans_status() === FALSE) {
\t\t\t\t\$out['status'] = 'gagal';
\t\t\t} elseif (\$result > 0) {
\t\t\t\t\$this->db->trans_commit();
\t\t\t\t\$out['status'] = 'berhasil';
\t\t\t} else {
\t\t\t\t\$this->db->trans_rollback();
\t\t\t\t\$out['status'] = 'gagal';
\t\t\t}
\t\t} else {
\t\t\t\$out['status'] = 'gagal';
\t\t}
\t\techo json_encode(\$out);
\t}

\tpublic function Edit(\$id) {
\t\t\$data['userdata'] = \$this->userdata;
\t\t\$access = \$this->M_sidebar->access('edit', self::__kode_menu);
\t\tif (\$access->menuview == 0) {
\t\t\t\$data['page']  = self::__title;
\t\t\t\$data['judul'] = self::__title;
\t\t\t\$this->loadkonten('Dashboard/layouts/no_akses', \$data);
\t\t} else {
\t\t\t\$data['row']   = \$this->{$model}->selectById(\$id);
\t\t\t\$data['page']  = self::__title;
\t\t\t\$data['judul'] = self::__title;
\t\t\t\$this->loadkonten(self::__folder . 'update', \$data);
\t\t}
\t}

\tpublic function prosesUpdate() {
\t\t\$username = \$this->userdata->nama;
\t\t\$date     = date('Y-m-d H:i:s');
\t\t\$where    = trim(\$this->input->post(self::__tableId));
\t\t\$this->db->trans_begin();
\t\tif (isset(\$_POST['{$first_required}']) && !empty(\$_POST['{$first_required}'])) {
\t\t\t\$data = array(
{$upd_data}\t\t\t'updated_by'   => \$username,
\t\t\t'updated_date' => \$date,
\t\t\t);
\t\t\t\$result = \$this->db->update(self::__tableName, \$data, array(self::__tableId => \$where));
\t\t\tif (\$this->db->trans_status() === FALSE) {
\t\t\t\t\$out['status'] = 'gagal';
\t\t\t} elseif (\$result > 0) {
\t\t\t\t\$this->db->trans_commit();
\t\t\t\t\$out['status'] = 'berhasil';
\t\t\t} else {
\t\t\t\t\$this->db->trans_rollback();
\t\t\t\t\$out['status'] = 'gagal';
\t\t\t}
\t\t} else {
\t\t\t\$out['status'] = 'gagal';
\t\t}
\t\techo json_encode(\$out);
\t}

\tpublic function hapus() {
\t\t\$access = \$this->M_sidebar->access('del', self::__kode_menu);
\t\tif (\$access->menuview == 0) {
\t\t\t\$out['status'] = 'gagal';
\t\t} else {
\t\t\t\$id     = \$_POST[self::__tableId];
\t\t\t\$result = \$this->{$model}->hapus(\$id);
\t\t\tif (\$result > 0) {
\t\t\t\t\$out['status'] = 'berhasil';
\t\t\t} else {
\t\t\t\t\$out['status'] = 'gagal';
\t\t\t}
\t\t}
\t\techo json_encode(\$out);
\t}
}
PHP;
    }

    private function tpl_model($c) {
        $mod   = $c['module_name'];
        $table = $c['table_name'];
        $pk    = $c['primary_key'];
        $model = 'M_' . strtolower($mod);

        return <<<PHP
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class {$model} extends CI_Model {

\tconst __tableName = '{$table}';
\tconst __tableId   = '{$pk}';

\tpublic function __construct() {
\t\tparent::__construct();
\t\t\$this->load->database();
\t}

\tpublic function get_data() {
\t\t\$this->db->from(self::__tableName);
\t\t\$this->db->order_by(self::__tableId);
\t\treturn \$this->db->get()->result();
\t}

\tpublic function selectById(\$id) {
\t\t\$sql  = "SELECT * FROM " . self::__tableName . " WHERE " . self::__tableId . " = '{\$id}'";
\t\treturn \$this->db->query(\$sql)->row();
\t}

\tpublic function update(\$data, \$where) {
\t\treturn \$this->db->update(self::__tableName, \$data, \$where);
\t}

\tpublic function hapus(\$id) {
\t\t\$sql = "DELETE FROM " . self::__tableName . " WHERE " . self::__tableId . " = '{\$id}'";
\t\t\$this->db->query(\$sql);
\t\treturn \$this->db->affected_rows();
\t}
}
PHP;
    }

    private function tpl_view_home($c) {
        $mod    = $c['module_name'];
        $pk     = $c['primary_key'];
        $kode   = $c['kode_menu'];
        $judul  = $c['judul'];
        $module_lc = strtolower($mod);

        // Build thead columns
        $th = "<th>#</th>\n";
        foreach ($c['list_columns'] as $col) {
            if ($col['field'] === $pk) continue;
            $th .= "   <th>{$col['label']}</th>\n";
        }
        $th .= "   <th style=\"width:125px;\">Action</th>\n";

        $ajax_url  = "<?php echo site_url('{$mod}/{$mod}/ajax_list'); ?>";
        $add_url   = "<?php echo site_url('add-{$kode}'); ?>";
        $hapus_url = "<?php echo base_url('{$mod}/{$mod}/hapus'); ?>";

        return <<<HTML
<?php \$this->load->view('_heading/_headerContent') ?>
<section class="content">
<div class="box">
  <?php if (\$accessAdd > 0) { ?>
  <div class="box-header">
    <div class="col-md-2">
      <a class="klik ajaxify" href="{$add_url}">
        <button class="btn btn-success"><i class="glyphicon glyphicon-plus-sign"></i> Add Data</button>
      </a>
    </div>
  </div>
  <?php } ?>
  <div class="box-body">
    <div class="table-responsive">
      <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
          <tr>
            {$th}
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>
</section>

<script type="text/javascript">
var table;
\$(document).ready(function() {
  table = \$('#table').DataTable({
    "processing": true,
    "order": [],
    "ajax": {
      "url": "{$ajax_url}",
      "type": "POST"
    },
    "columnDefs": [{ "targets": [-1], "orderable": false }]
  });
});

function reload_table() { table.ajax.reload(null, false); }

\$(document).on("click", ".hapus-{$kode}", function() {
  var id = \$(this).attr("data-id");
  swal({
    title: "Hapus Data?",
    text: "Yakin anda akan menghapus data ini?",
    type: "warning",
    showCancelButton: true,
    confirmButtonText: "Hapus",
    confirmButtonColor: '#dc1227',
    closeOnConfirm: true,
    html: true
  }, function() {
    \$.ajax({
      method: "POST",
      url: "{$hapus_url}",
      data: "{$pk}=" + id,
      success: function(data) {
        hapus_berhasil();
        reload_table();
      }
    });
  });
});
</script>
HTML;
    }

    private function tpl_view_tambah($c) {
        $mod   = $c['module_name'];
        $kode  = $c['kode_menu'];
        $judul = $c['judul'];

        $fields_html = '';
        foreach ($c['form_fields'] as $f) {
            if ($f['field'] === $c['primary_key']) continue;
            $input = $this->_render_input($f, null);
            $req   = !empty($f['required']) ? ' <span style="color:red">*</span>' : '';
            $fields_html .= <<<HTML

            <div class="form-group">
              <label class="col-sm-2 control-label">{$f['label']}{$req}</label>
              <div class="col-sm-6">{$input}</div>
            </div>
HTML;
        }

        $proses_url = "<?php echo site_url('{$mod}/{$mod}/prosesAdd'); ?>";
        $back_url   = "<?php echo site_url('{$kode}'); ?>";

        return <<<HTML
<?php \$this->load->view('_heading/_headerContent') ?>
<section class="content">
  <div class="loading2"></div>
  <div class="box">
    <div class="row">
      <div class="col-md-9">
        <div class="box-header with-border">
          <h3 class="box-title">Tambah {$judul}</h3>
        </div>
        <form class="form-horizontal" id="form-tambah" method="POST">
          <input type="hidden" name="created_by" value="<?php echo \$userdata->nama; ?>">
          <div class="box-body">
            {$fields_html}
          </div>
          <div class="box-footer">
            <button id="simpan" type="submit" class="btn btn-success btn-flat"><i class="fa fa-save"></i> Simpan</button>
            <a class="klik ajaxify" href="{$back_url}">
              <button type="button" class="btn btn-primary btn-flat"><i class="fa fa-arrow-left"></i> Kembali</button>
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<script type="text/javascript">
\$('#form-tambah').submit(function(e) {
  e.preventDefault();
  var data = \$(this).serialize();
  \$.ajax({
    method: 'POST',
    beforeSend: function() { \$('.loading2').show(); },
    url: '{$proses_url}',
    data: data
  }).done(function(res) {
    var result = jQuery.parseJSON(res);
    \$('.loading2').hide();
    if (result.status == 'berhasil') {
      document.getElementById('form-tambah').reset();
      save_berhasil();
      setTimeout(location.reload.bind(location), 700);
    } else {
      gagal();
    }
  });
});
</script>
HTML;
    }

    private function tpl_view_update($c) {
        $mod   = $c['module_name'];
        $pk    = $c['primary_key'];
        $kode  = $c['kode_menu'];
        $judul = $c['judul'];

        $fields_html = '';
        foreach ($c['form_fields'] as $f) {
            if ($f['field'] === $pk) continue;
            $input = $this->_render_input($f, "\$row->{$f['field']}");
            $req   = !empty($f['required']) ? ' <span style="color:red">*</span>' : '';
            $fields_html .= <<<HTML

            <div class="form-group">
              <label class="col-sm-2 control-label">{$f['label']}{$req}</label>
              <div class="col-sm-6">{$input}</div>
            </div>
HTML;
        }

        $proses_url = "<?php echo site_url('{$mod}/{$mod}/prosesUpdate'); ?>";
        $back_url   = "<?php echo site_url('{$kode}'); ?>";

        return <<<HTML
<?php \$this->load->view('_heading/_headerContent') ?>
<section class="content">
  <div class="loading2"></div>
  <div class="box">
    <div class="row">
      <div class="col-md-9">
        <div class="box-header with-border">
          <h3 class="box-title">Update {$judul}</h3>
        </div>
        <form class="form-horizontal" id="form-update" method="POST">
          <input type="hidden" name="{$pk}" value="<?php echo \$row->{$pk}; ?>">
          <input type="hidden" name="updated_by" value="<?php echo \$userdata->nama; ?>">
          <div class="box-body">
            {$fields_html}
          </div>
          <div class="box-footer">
            <button type="submit" class="btn btn-success btn-flat"><i class="fa fa-save"></i> Update</button>
            <a class="klik ajaxify" href="{$back_url}">
              <button type="button" class="btn btn-primary btn-flat"><i class="fa fa-arrow-left"></i> Kembali</button>
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<script type="text/javascript">
\$('#form-update').submit(function(e) {
  e.preventDefault();
  var data = \$(this).serialize();
  \$.ajax({
    method: 'POST',
    beforeSend: function() { \$('.loading2').show(); },
    url: '{$proses_url}',
    data: data
  }).done(function(res) {
    var result = jQuery.parseJSON(res);
    \$('.loading2').hide();
    if (result.status == 'berhasil') {
      save_berhasil();
    } else {
      gagal();
    }
  });
});
</script>
HTML;
    }

    // Helper: render a single form input element based on type
    private function _render_input($f, $value_expr) {
        $name  = $f['field'];
        $type  = isset($f['type']) ? $f['type'] : 'text';
        $val   = ($value_expr !== null) ? "<?php echo {$value_expr}; ?>" : '';

        switch ($type) {
            case 'textarea':
                return "<textarea class=\"form-control\" name=\"{$name}\" rows=\"4\">{$val}</textarea>";

            case 'number':
                return "<input type=\"number\" class=\"form-control\" name=\"{$name}\" value=\"{$val}\">";

            case 'date':
                return "<input type=\"date\" class=\"form-control\" name=\"{$name}\" value=\"{$val}\">";

            case 'select':
                $opts = '';
                if (!empty($f['options'])) {
                    foreach ($f['options'] as $opt) {
                        $opts .= "<option value=\"{$opt['value']}\">{$opt['label']}</option>";
                    }
                }
                return "<select class=\"form-control\" name=\"{$name}\"><option value=\"\">-- Pilih --</option>{$opts}</select>";

            case 'file':
                return "<input type=\"file\" class=\"form-control\" name=\"{$name}\">";

            default: // text
                return "<input type=\"text\" class=\"form-control\" name=\"{$name}\" value=\"{$val}\">";
        }
    }
}
