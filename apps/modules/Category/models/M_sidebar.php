<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_sidebar extends CI_Model 
{
	
	//VIEW LEFT MENU
	function left_menu(){
		$query = "select * from tbl_menu where parent = '' order by urutan";
		$data = $this->db->query($query);
		return $data;
	}
	
	function left_menu_child($id){
		$query = "select * from tbl_menu where parent = '".$id."' order by urutan";
		$data = $this->db->query($query);
		return $data;
	}
	
	// ACCESS CONTROL LIST (action: view, add, edit, del)
 	function access($action, $menucode){
		$query = "SELECT count(a.id_menuakses) menuview 
				  FROM menu_akses a left join tbl_menu b on a.id_menu=b.id_menu 
				  where b.kode_menu= '".$menucode."' and a.grup_id= '".$this->session->userdata('grup_id')."' and  a.".$action." = 1";
		$data = $this->db->query($query);
		return $data->row();
	}		
	
}

