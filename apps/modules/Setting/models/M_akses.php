
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_akses extends CI_Model 
{
	public function select_by_id($id) {
		$sql = "SELECT * FROM grup WHERE grup_id = '{$id}'";

		$data = $this->db->query($sql);

		return $data->row();
	}
	
	public function hapus($id) {
		$sql = "DELETE FROM menu_akses WHERE grup_id='" .$id ."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	
	// VIEW USER PRIVILEGE
	public function hak_akses($id){
		if(!$id) return FALSE;
		$query = "SELECT a.id_menuakses, b.id_menu as menus, a.id_menu, b.nama_menu, a.grup_id,
						b.parent, b.menuparent, c.nama_grup, a.view, a.add, a.edit, a.del, b.menu_file
					FROM (SELECT * FROM menu_akses WHERE grup_id = '".$id."') a
					RIGHT JOIN (
						SELECT a.*, b.nama_menu AS menuparent
						FROM tbl_menu a
						LEFT JOIN tbl_menu b ON a.parent = b.id_menu
					) b ON a.id_menu = b.id_menu
					LEFT JOIN grup c ON a.grup_id = c.grup_id
					ORDER BY COALESCE(b.parent, b.id_menu), b.urutan";
		$data = $this->db->query($query);
		return $data->result();
	}
	
	function save($data){
		$result= $this->db->insert('menu_akses',$data);
	    return $result;
	}
	
	public function delete($id) {
		$sql = "DELETE FROM menu_akses WHERE grup_id ='" .$id ."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	
	

}

