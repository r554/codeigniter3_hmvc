<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_total extends CI_Model 
{
	
	public function total_sop() {
		$data = $this->db->get('tbl_sop');
		return $data->num_rows();
	}
	
	public function total_employe() {
		$data = $this->db->get('tbl_employe');
		return $data->num_rows();
	}
	
	public function total_form5() {
		$data = $this->db->get('tbl_form_5');
		return $data->num_rows();
	}

	public function total_psb() {
		$data = $this->db->get('tbl_form_psb');
		return $data->num_rows();
	}
	
	public function get_data_total(){

         $query = $this->db->query("SELECT tanggal,COUNT(id_employe) AS id_employe FROM tbl_employe WHERE tanggal between DATE_ADD(date(now()), INTERVAL -30 DAY) and date(now()) GROUP BY tanggal");
          
        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }
    
    
    public function get_total_psb() {
        
        $date = new DateTime("now");
        $BlnSkrng = $date->format('m');
        $ThnSkrng = $date->format('Y');
        
        $query = $this->db->query("SELECT tanggal,COUNT(id_form) AS id_form FROM tbl_form_psb where month(tanggal)='$BlnSkrng' and year(tanggal) = '$ThnSkrng'  GROUP BY tanggal ASC");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $data) {
                $hasil[] = $data;
            }
            return $hasil;
        }
    }

    public function get_total_take5() {
        
         $date = new DateTime("now");
         $BlnSkrng = $date->format('m');
         $ThnSkrng = $date->format('Y');
        
        $query = $this->db->query("SELECT tanggal,COUNT(id_form) AS id_form FROM tbl_form_5 where month(tanggal)='$BlnSkrng' and year(tanggal) = '$ThnSkrng'  GROUP BY tanggal ASC");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $data) {
                $hasil[] = $data;
            }
            return $hasil;
        }
    }
    
    public function total_ps_checklist() {
		$data = $this->db->get('tbl_ps_checklist');
		return $data->num_rows();
	}
	
}

