<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_admin extends CI_Model {
	public function update($data, $id) {
		$this->db->where("id", $id);
		$this->db->update("admin", $data);

		return $this->db->affected_rows();
	}

	public function select($id = '') {
		if ($id != '') {
			$this->db->where('id', $id);
		}

		$data = $this->db->get('admin');

		return $data->row();
	}
	
	public function select_group() {
		$data = $this->db->get('grup');
		return $data->result();
	}
	
	public function select_status() {
		
		$sql = " select * from status_grup WHERE nama in ('aktif','belum aktif')";
		$data = $this->db->query($sql);
		return $data->result();
	}
	
}

/* End of file M_admin.php */
/* Location: ./application/models/M_admin.php */