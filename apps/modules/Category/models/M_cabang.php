<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_cabang extends CI_Model {

	const __tableName = 'tbl_cabang';
    const __tableId = 'id';

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function get_data() {
        $this->db->from(self::__tableName);
        $this->db->order_by(self::__tableId);
        $data = $this->db->get();
        return $data->result();
    }
	
	public function selectById($id) {
        $sql = "SELECT * FROM " . self::__tableName . " WHERE " . self::__tableId . " = '{$id}'";
        $data = $this->db->query($sql);
        return $data->row();
    }

	
	public function update($data,$where) {
		$result= $this->db->update(self::__tableName,$data,$where);
	    return $result;
	}
	

	public function hapus($id) {
		$sql = "DELETE FROM " . self::__tableName . " WHERE  ". self::__tableId . " = '{$id}'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
	
	
}
