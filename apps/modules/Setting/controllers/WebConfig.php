<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WebConfig extends AUTH_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_sidebar');
        $this->load->helper('activity');
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

    public function index()
    {
        $data['userdata'] = $this->userdata;
        $data['page']     = 'konfigurasi';
        $data['judul']    = 'Konfigurasi Website';

        // Ambil nilai saat ini dari .env
        $data['cfg'] = [
            'app_name'           => env('APP_NAME', 'My Application'),
            'app_skin'           => env('APP_SKIN', 'skin-red'),
            'app_favicon'        => env('APP_FAVICON', ''),
            'app_logo'           => env('APP_LOGO', ''),
            'app_logo_mini'      => env('APP_LOGO_MINI', ''),
            'app_footer'         => env('APP_FOOTER_TEXT', ''),
            'app_fixed_layout'   => env('APP_FIXED_LAYOUT', ''),
            'app_sidebar_collapse' => env('APP_SIDEBAR_COLLAPSE', ''),
            'app_boxed_layout'   => env('APP_BOXED_LAYOUT', ''),
        ];

        // Ambil konfigurasi database saat ini
        $db_config_file = APPPATH . 'config/database.php';
        include($db_config_file);
        $data['db_cfg'] = [
            'hostname' => $db['default']['hostname'],
            'username' => $db['default']['username'],
            'password' => $db['default']['password'],
            'database' => $db['default']['database'],
            'dbdriver' => $db['default']['dbdriver'],
            'char_set' => $db['default']['char_set'],
        ];

        $this->loadkonten('v_config/v_home', $data);
    }

    public function docs_email()
    {
        $data['userdata'] = $this->userdata;
        $data['page']     = 'konfigurasi';
        $data['judul']    = 'Dokumentasi Email Helper';
        $this->load->view('Dashboard/layouts/header', $data);
        $this->load->view('v_config/v_docs_email', $data);
    }

    public function simpan()
    {
        $this->load->helper('url');

        // Field teks
        set_env('APP_NAME',        $this->input->post('app_name'));
        set_env('APP_SKIN',        $this->input->post('app_skin'));
        set_env('APP_FOOTER_TEXT', $this->input->post('app_footer'));

        // Opsi layout (checkbox: 1 jika dicentang, 0 jika tidak)
        set_env('APP_FIXED_LAYOUT',     $this->input->post('app_fixed_layout')     ? '1' : '0');
        set_env('APP_SIDEBAR_COLLAPSE', $this->input->post('app_sidebar_collapse') ? '1' : '0');
        set_env('APP_BOXED_LAYOUT',     $this->input->post('app_boxed_layout')     ? '1' : '0');

        // Upload favicon
        if (!empty($_FILES['app_favicon']['name'])) {
            $result = $this->_upload_file('app_favicon', 'assets/tambahan/gambar/');
            if ($result['status']) {
                set_env('APP_FAVICON', 'assets/tambahan/gambar/' . $result['file_name']);
            }
        }

        // Upload logo
        if (!empty($_FILES['app_logo']['name'])) {
            $result = $this->_upload_file('app_logo', 'assets/tambahan/gambar/');
            if ($result['status']) {
                set_env('APP_LOGO', 'assets/tambahan/gambar/' . $result['file_name']);
            }
        }

        // Upload logo mini
        if (!empty($_FILES['app_logo_mini']['name'])) {
            $result = $this->_upload_file('app_logo_mini', 'assets/tambahan/gambar/');
            if ($result['status']) {
                set_env('APP_LOGO_MINI', 'assets/tambahan/gambar/' . $result['file_name']);
            }
        }

        log_activity('save_config', 'Simpan konfigurasi website', 'WebConfig');
        echo json_encode(['status' => 'berhasil']);
    }

    public function simpan_db()
    {
        $hostname = $this->input->post('db_hostname');
        $username = $this->input->post('db_username');
        $password = $this->input->post('db_password');
        $database = $this->input->post('db_database');
        $driver   = $this->input->post('db_driver');
        $charset  = $this->input->post('db_charset');

        // Validasi sederhana
        if (empty($hostname) || empty($username) || empty($database)) {
            echo json_encode(['status' => 'gagal', 'message' => 'Hostname, username, dan nama database wajib diisi.']);
            return;
        }

        $template = "<?php\ndefined('BASEPATH') or exit('No direct script access allowed');\n\n"
            . "\$active_group = 'default';\n"
            . "\$query_builder = TRUE;\n\n"
            . "\$db['default'] = array(\n"
            . "\t'dsn'\t   => '',\n"
            . "\t'hostname' => '" . addslashes($hostname) . "',\n"
            . "\t'username' => '" . addslashes($username) . "',\n"
            . "\t'password' => '" . addslashes($password) . "',\n"
            . "\t'database' => '" . addslashes($database) . "',\n"
            . "\t'dbdriver' => '" . addslashes($driver)   . "',\n"
            . "\t'dbprefix' => '',\n"
            . "\t'pconnect' => FALSE,\n"
            . "\t'db_debug' => TRUE,\n"
            . "\t'cache_on' => FALSE,\n"
            . "\t'cachedir' => '',\n"
            . "\t'char_set' => '" . addslashes($charset)  . "',\n"
            . "\t'dbcollat' => '" . addslashes($charset)  . "_general_ci',\n"
            . "\t'swap_pre' => '',\n"
            . "\t'encrypt'  => FALSE,\n"
            . "\t'compress' => FALSE,\n"
            . "\t'stricton' => FALSE,\n"
            . "\t'failover' => array(),\n"
            . "\t'save_queries' => TRUE\n"
            . ");\n";

        $file = APPPATH . 'config/database.php';
        if (file_put_contents($file, $template) !== false) {
            echo json_encode(['status' => 'berhasil']);
        } else {
            echo json_encode(['status' => 'gagal', 'message' => 'Gagal menulis file database.php. Periksa izin file.']);
        }
    }

    public function test_koneksi_db()
    {
        $hostname = $this->input->post('db_hostname');
        $username = $this->input->post('db_username');
        $password = $this->input->post('db_password');
        $database = $this->input->post('db_database');

        $conn = @mysqli_connect($hostname, $username, $password, $database);
        if ($conn) {
            mysqli_close($conn);
            echo json_encode(['status' => 'ok']);
        } else {
            echo json_encode(['status' => 'error', 'message' => mysqli_connect_error()]);
        }
    }

    public function simpan_email()
    {
        $host       = $this->input->post('mail_host');
        $port       = $this->input->post('mail_port');
        $encryption = $this->input->post('mail_encryption');
        $username   = $this->input->post('mail_username');
        $password   = $this->input->post('mail_password');
        $from_email = $this->input->post('mail_from_email');
        $from_name  = $this->input->post('mail_from_name');

        if (empty($host) || empty($username)) {
            echo json_encode(['status' => 'gagal', 'message' => 'Host dan Username wajib diisi.']);
            return;
        }

        set_env('MAIL_HOST',       $host);
        set_env('MAIL_PORT',       $port ?: '587');
        set_env('MAIL_ENCRYPTION', $encryption);
        set_env('MAIL_USERNAME',   $username);
        // Hanya update password jika diisi (hindari menghapus password yang ada)
        if ($password !== '') {
            set_env('MAIL_PASSWORD', $password);
        }
        set_env('MAIL_FROM_EMAIL', $from_email ?: $username);
        set_env('MAIL_FROM_NAME',  $from_name ?: 'No Reply');

        log_activity('save_config', 'Simpan konfigurasi email/SMTP', 'WebConfig');
        echo json_encode(['status' => 'berhasil']);
    }

    public function test_kirim_email()
    {
        $this->load->helper('email');

        $test_to = $this->input->post('test_to');
        if (empty($test_to)) {
            echo json_encode(['status' => 'error', 'message' => 'Email tujuan tidak boleh kosong.']);
            return;
        }

        $app_name = env('APP_NAME', 'Aplikasi');
        $subject  = '[Test] Konfigurasi Email - ' . $app_name;
        $body     = '
            <p>Halo,</p>
            <p>Ini adalah email test dari <strong>' . htmlspecialchars($app_name) . '</strong>.</p>
            <p>Jika Anda menerima email ini, berarti konfigurasi SMTP sudah berjalan dengan baik.</p>
            <hr>
            <small>Dikirim pada: ' . date('d-m-Y H:i:s') . '</small>
        ';

        $result = send_email($test_to, $test_to, $subject, $body);

        if ($result['status']) {
            echo json_encode(['status' => 'ok']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $result['message']]);
        }
    }

    public function simpan_storage()
    {
        $driver        = $this->input->post('storage_driver') ?: 'local';
        $local_path    = $this->input->post('storage_local_path') ?: 'uploads';
        $local_url     = $this->input->post('storage_local_url');
        $bucket        = $this->input->post('storage_bucket');
        $region        = $this->input->post('storage_region') ?: 'us-east-1';
        $key           = $this->input->post('storage_key');
        $secret        = $this->input->post('storage_secret');
        $endpoint      = $this->input->post('storage_endpoint');
        $custom_domain = $this->input->post('storage_custom_domain');
        $acl           = $this->input->post('storage_acl');
        $path_style    = $this->input->post('storage_path_style') ? '1' : '0';

        set_env('STORAGE_DRIVER',        $driver);
        set_env('STORAGE_LOCAL_PATH',    $local_path);
        set_env('STORAGE_LOCAL_URL',     $local_url);
        set_env('STORAGE_BUCKET',        $bucket);
        set_env('STORAGE_REGION',        $region);
        set_env('STORAGE_KEY',           $key);
        // Hanya update secret jika tidak kosong
        if ($secret !== '') {
            set_env('STORAGE_SECRET', $secret);
        }
        set_env('STORAGE_ENDPOINT',      $endpoint);
        set_env('STORAGE_CUSTOM_DOMAIN', $custom_domain);
        set_env('STORAGE_ACL',           $acl);
        set_env('STORAGE_PATH_STYLE',    $path_style);

        log_activity('save_config', 'Simpan konfigurasi storage', 'WebConfig');
        echo json_encode(['status' => 'berhasil']);
    }

    public function test_koneksi_storage()
    {
        $this->load->helper('storage');

        $driver = $this->input->post('storage_driver') ?: 'local';

        if ($driver === 'local') {
            $path = FCPATH . ($this->input->post('storage_local_path') ?: 'uploads');
            if (!is_dir($path)) {
                @mkdir($path, 0775, true);
            }
            if (is_writable($path)) {
                echo json_encode(['status' => 'ok', 'message' => 'Folder lokal dapat ditulis: ' . $path]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Folder tidak dapat ditulis: ' . $path]);
            }
            return;
        }

        // Test S3: upload file kecil lalu hapus
        $test_key    = '.test-connection-' . time() . '.txt';
        $test_content = 'storage connection test ' . date('Y-m-d H:i:s');

        // Gunakan konfigurasi dari POST (belum disimpan)
        $old = [
            'STORAGE_DRIVER'        => env('STORAGE_DRIVER'),
            'STORAGE_BUCKET'        => env('STORAGE_BUCKET'),
            'STORAGE_REGION'        => env('STORAGE_REGION'),
            'STORAGE_KEY'           => env('STORAGE_KEY'),
            'STORAGE_SECRET'        => env('STORAGE_SECRET'),
            'STORAGE_ENDPOINT'      => env('STORAGE_ENDPOINT'),
            'STORAGE_CUSTOM_DOMAIN' => env('STORAGE_CUSTOM_DOMAIN'),
            'STORAGE_ACL'           => env('STORAGE_ACL'),
            'STORAGE_PATH_STYLE'    => env('STORAGE_PATH_STYLE'),
        ];

        // Sementara set env dari POST untuk test
        putenv('STORAGE_DRIVER=s3');
        putenv('STORAGE_BUCKET='   . $this->input->post('storage_bucket'));
        putenv('STORAGE_REGION='   . ($this->input->post('storage_region') ?: 'us-east-1'));
        putenv('STORAGE_KEY='      . $this->input->post('storage_key'));
        putenv('STORAGE_SECRET='   . $this->input->post('storage_secret'));
        putenv('STORAGE_ENDPOINT=' . $this->input->post('storage_endpoint'));
        putenv('STORAGE_CUSTOM_DOMAIN=' . $this->input->post('storage_custom_domain'));
        putenv('STORAGE_ACL='      . $this->input->post('storage_acl'));
        putenv('STORAGE_PATH_STYLE=' . ($this->input->post('storage_path_style') ? '1' : '0'));

        $result = storage_upload_content($test_key, $test_content, 'text/plain');

        // Restore env lama
        foreach ($old as $k => $v) {
            putenv($k . '=' . $v);
        }

        if ($result['status']) {
            storage_delete($test_key);
            echo json_encode(['status' => 'ok', 'message' => 'Koneksi S3 berhasil! File test telah dihapus.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $result['message']]);
        }
    }

    public function docs_storage()
    {
        $data['userdata'] = $this->userdata;
        $data['page']     = 'konfigurasi';
        $data['judul']    = 'Dokumentasi Storage Helper';
        $this->load->view('Dashboard/layouts/header', $data);
        $this->load->view('v_config/v_docs_storage', $data);
    }

    private function _upload_file($field, $upload_path)
    {
        $config = [
            'upload_path'   => FCPATH . $upload_path,
            'allowed_types' => 'gif|jpg|jpeg|png|ico',
            'max_size'      => 2048,
        ];
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ($this->upload->do_upload($field)) {
            return ['status' => true, 'file_name' => $this->upload->data('file_name')];
        }
        return ['status' => false, 'error' => $this->upload->display_errors()];
    }
}
