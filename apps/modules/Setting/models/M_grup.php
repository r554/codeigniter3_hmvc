<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_grup extends CI_Model 
{
	public function get_datatables() {
		$data = $this->db->get('grup');
		return $data->result();
	}

	public function select_by_id($id) {
		$sql = "SELECT * FROM grup WHERE grup_id = '{$id}'";

		$data = $this->db->query($sql);

		return $data->row();
	}
	
	
	function simpan_data($data){
		$result= $this->db->insert('grup',$data);
	    return $result;
	}
	

	public function update($data,$where) {
		$result= $this->db->update('grup',$data,$where);
	    return $result;
	}

	public function hapus($id) {
		$sql = "DELETE FROM grup WHERE grup_id ='" .$id ."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	
	// VIEW USER PRIVILEGE
	public function akses_grup($loop){
		if(!$loop) return FALSE;
		$query = "  select a.id_menuakses, b.id_menu as menus, a.id_menu, b.nama_menu, a.grup_id, b.menuparent, c.nama_grup, a.view, a.add, a.edit, a.del, b.menu_file
					from (select * from menu_akses where grup_id = '".$loop."') a
					right join (select a.*, b.nama_menu as menuparent from tbl_menu a left join tbl_menu b on a.parent = b.id_menu) b
					on a.id_menu = b.id_menu
					left join grup c
					on a.grup_id = c.grup_id
					order by id_menu ASC";
		$data = $this->db->query($query);
		
		return $data->result();
	}
	

}

