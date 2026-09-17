<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_menu extends CI_Model 
{
	public function get_datatables() {
		$sql = "SELECT a.*, b.nama_menu as nama_parent
				FROM tbl_menu a
				LEFT JOIN tbl_menu b ON a.parent = b.id_menu
				ORDER BY COALESCE(a.parent, a.id_menu), a.urutan";
		$data = $this->db->query($sql);
		return $data->result();
	}

	public function select_by_id($id) {
		$sql = "SELECT * FROM tbl_menu WHERE id_menu = '{$id}'";

		$data = $this->db->query($sql);

		return $data->row();
	}
	
	public function pilih_menu() {
		// Hanya ambil main menu (parent kosong atau 0) sebagai pilihan parent
		$sql = "SELECT * FROM tbl_menu WHERE (parent = '' OR parent = '0' OR parent IS NULL) ORDER BY urutan";
		$data = $this->db->query($sql);
		return $data->result();
	}
	
	
	function simpan_data($data){
		$result= $this->db->insert('tbl_menu',$data);
	    return $result;
	}
	

	public function update($data,$where) {
		$result= $this->db->update('tbl_menu',$data,$where);
	    return $result;
	}

	public function hapus($id) {
		$sql = "DELETE FROM tbl_menu WHERE id_menu ='" .$id ."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	
	public function cari_ajak($cari){
	$where = "menu_file='view'";	
    $this->db->select('*');
    $this->db->from('tbl_menu');
    $this->db->like('nama_menu', $cari);
	$this->db->where($where);
    return $this->db->get()->result_array();
	}
	


}

