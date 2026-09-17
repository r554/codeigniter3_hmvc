<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Installer_model extends CI_Model
{
    public function test_connection($input)
    {
        $config = array('hostname' => trim($input['hostname']), 'username' => $input['username'], 'password' => $input['password'], 'database' => trim($input['database']), 'dbdriver' => 'mysqli', 'db_debug' => false);
        $db = $this->load->database($config, true);
        if (!$db->conn_id) return array('ok' => false, 'message' => 'Koneksi database gagal.');
        return array('ok' => true, 'message' => 'Koneksi database berhasil.');
    }

    public function install($input)
    {
        $config = array('hostname' => trim($input['hostname']), 'username' => $input['username'], 'password' => $input['password'], 'database' => trim($input['database']), 'dbdriver' => 'mysqli', 'db_debug' => false);
        $db = $this->load->database($config, true);
        if (!$db->conn_id) return array('ok' => false, 'message' => 'Koneksi database gagal.');
        $db->trans_start();
        $sql = array(
            "CREATE TABLE IF NOT EXISTS status_grup (id_status int NOT NULL AUTO_INCREMENT, nama varchar(50) NOT NULL, PRIMARY KEY (id_status), UNIQUE KEY (nama)) ENGINE=InnoDB DEFAULT CHARSET=utf8",
            "CREATE TABLE IF NOT EXISTS grup (grup_id int NOT NULL AUTO_INCREMENT, nama_grup varchar(100) NOT NULL, deskripsi varchar(255) DEFAULT NULL, PRIMARY KEY (grup_id), UNIQUE KEY (nama_grup)) ENGINE=InnoDB DEFAULT CHARSET=utf8",
            "CREATE TABLE IF NOT EXISTS tbl_menu (id_menu int NOT NULL AUTO_INCREMENT, nama_menu varchar(100) NOT NULL, menuparent varchar(100) DEFAULT NULL, parent int DEFAULT 0, menu_file varchar(255) DEFAULT NULL, link varchar(255) DEFAULT NULL, icon varchar(100) DEFAULT NULL, urutan int DEFAULT 0, PRIMARY KEY (id_menu)) ENGINE=InnoDB DEFAULT CHARSET=utf8",
            "CREATE TABLE IF NOT EXISTS menu_akses (id_menuakses int NOT NULL AUTO_INCREMENT, grup_id int NOT NULL, id_menu int NOT NULL, view tinyint DEFAULT 0, `add` tinyint DEFAULT 0, `edit` tinyint DEFAULT 0, `del` tinyint DEFAULT 0, PRIMARY KEY (id_menuakses), UNIQUE KEY grup_menu (grup_id,id_menu)) ENGINE=InnoDB DEFAULT CHARSET=utf8",
            "CREATE TABLE IF NOT EXISTS admin (id int NOT NULL AUTO_INCREMENT, nama varchar(150) NOT NULL, email varchar(150) NOT NULL, username varchar(60) NOT NULL, password varchar(255) NOT NULL, grup_id int NOT NULL, foto varchar(255) DEFAULT 'no-image.jpg', hidden tinyint DEFAULT 1, status int DEFAULT 3, PRIMARY KEY (id), UNIQUE KEY username (username)) ENGINE=InnoDB DEFAULT CHARSET=utf8"
        );
        foreach ($sql as $query) if (!$db->query($query)) return array('ok' => false, 'message' => 'Gagal membuat tabel: ' . $db->error()['message']);
        $db->query("INSERT IGNORE INTO status_grup (id_status,nama) VALUES (1,'belum aktif'),(3,'aktif')");
        $db->query("INSERT IGNORE INTO grup (grup_id,nama_grup,deskripsi) VALUES (1,'Administrator','Akses penuh aplikasi')");
        $group = $db->query("SELECT grup_id FROM grup WHERE nama_grup='Administrator'")->row();
        $db->query("INSERT IGNORE INTO tbl_menu (nama_menu,menu_file,link,icon,urutan) VALUES ('Dashboard','view','dashboard','fa-dashboard',1),('Pengaturan','view','konfigurasi','fa-cogs',99)");
        $db->query("INSERT IGNORE INTO menu_akses (grup_id,id_menu,view,`add`,`edit`,`del`) SELECT ".(int)$group->grup_id.",id_menu,1,1,1,1 FROM tbl_menu");
        $username = $this->security->xss_clean(trim($input['admin_username']));
        $email = $this->security->xss_clean(trim($input['admin_email']));
        $password = md5($input['admin_password']);
        $db->query("INSERT IGNORE INTO admin (nama,email,username,password,grup_id,foto,hidden,status) VALUES ('Administrator','".$db->escape_str($email)."','".$db->escape_str($username)."','".$password."',".(int)$group->grup_id.",'no-image.jpg',1,3)");
        $db->trans_complete();
        return $db->trans_status() ? array('ok' => true, 'message' => 'Instalasi berhasil. Silakan login.') : array('ok' => false, 'message' => 'Transaksi instalasi gagal.');
    }
}
