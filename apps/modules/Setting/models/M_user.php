<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_User extends CI_Model 
{
	public function get_datatables() {
		
		$sql = " SELECT 				 
				 admin.foto As foto, 
				 admin.id AS id,  
				 admin.email AS email, 
				 admin.nama As nama, 
				 grup.nama_grup AS grup_id, 
				 status_grup.nama AS status 
		         FROM admin
		         left join(grup)on admin.grup_id=grup.grup_id
				 left join(status_grup)on admin.status=status_grup.id_status
				  WHERE admin.hidden ='1'
		         ORDER BY id DESC ";
		$data = $this->db->query($sql);
		return $data->result();
	}

	public function select_by_id($id) {
		$sql = "SELECT * FROM admin WHERE id = '{$id}'";

		$data = $this->db->query($sql);

		return $data->row();
	}
	
	public function select_status() {
		
		$sql = " select * from status_grup WHERE nama in ('aktif','belum aktif')";
		$data = $this->db->query($sql);
		return $data->result();
	}
	
	public function select_group() {
		$data = $this->db->get('grup');
		return $data->result();
	}
	
	
	function simpan_data($data){
		$result= $this->db->insert('admin',$data);
	    return $result;
	}
	

	public function update($data,$where) {
		$result= $this->db->update('admin',$data,$where);
	    return $result;
	}

	public function hapus($id) {
		$sql = "DELETE FROM admin WHERE id='" .$id ."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}

}

