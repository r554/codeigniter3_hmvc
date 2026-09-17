<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cabang extends AUTH_Controller {

	const __tableName = 'tbl_cabang';
	const __tableId   = 'id';
	const __folder    = 'v_cabang/';
	const __kode_menu = 'cabang';
	const __title     = 'Branch ';
	const __model     = 'M_cabang' ;


	public function __construct()
	{
		parent::__construct();
		$this->load->model(self::__model);
		$this->load->model('M_sidebar');
	}
	
	public function loadkonten($page, $data) {
		
		$data['userdata'] 	= $this->userdata;
		$ajax = ($this->input->post('status_link') == "ajax" ? true : false);
		if (!$ajax) { 
			$this->load->view('Dashboard/layouts/header', $data);
		}
		$this->load->view($page, $data);
		if (!$ajax) $this->load->view('Dashboard/layouts/footer', $data);
	}

	public function index()
	{
		$accessAdd = $this->M_sidebar->access('add',self::__kode_menu);
        $data['accessAdd']  = $accessAdd->menuview;
		$data['userdata'] 	= $this->userdata; 
		$data['page'] 		= self::__title;
		$data['judul'] 		= self::__title;
		
		$this->loadkonten(''.self::__folder.'home',$data);
	}

	public function ajax_list()
	{
		$accessEdit = $this->M_sidebar->access('edit',self::__kode_menu);
        $accessDel = $this->M_sidebar->access('del',self::__kode_menu);
		$list = $this->M_cabang->get_data();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $brand) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $brand->nama_cabang;
			$row[] = $brand->created_by;

			//add html for action
		     $buttonEdit = '';
            if ($accessEdit->menuview > 0) {
                $buttonEdit = anchor('edit-cabang/' . $brand->id, ' <span class="fa fa-edit"></span> ', ' class="btn btn-sm btn-primary klik ajaxify" ');
            }
            $buttonDel = '';
            if ($accessDel->menuview > 0) {
                $buttonDel = '<button class="btn btn-sm btn-danger hapus-cabang" data-id=' . "'" . $brand->id . "'" . '><i class="glyphicon glyphicon-trash"></i></button>';
            }

			$row[] = $buttonEdit . '  ' . $buttonDel;
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	
	public function Add() {
		
		/*ini harus ada boss */
		$data['userdata'] = $this->userdata;
		$access = $this->M_sidebar->access('add',self::__kode_menu);
		if ($access->menuview == 0){
			$data['page'] 		= self::__title;
			$data['judul'] 		= self::__title;
			$this->loadkonten('Dashboard/layouts/no_akses',$data);
		 }
		
		/*ini harus ada boss */
		
		else{
		$data['page'] 		= self::__title;
		$data['judul'] 		= self::__title;
		$this->loadkonten(''.self::__folder.'tambah',$data);
	}
}



    public function prosesAdd() {

    	$username = $this->userdata->nama;
        $date = date('Y-m-d H:i:s');

        $this->db->trans_begin();

    	if (isset($_POST["nama_cabang"]) && !empty($_POST["nama_cabang"])) 
		{
		$data = array(
		       'nama_cabang'   => $this->input->post('nama_cabang'),
	           'created_by'   => $username,
               'created_date' => $date,
               'updated_by'   => $username,
               'updated_date' => $date,
		);
		$result = $this->db->insert(self::__tableName, $data);

		if ($this->db->trans_status() === FALSE) {
              $out['status'] = 'gagal';   
            }
			
		if ($result > 0) {
		$this->db->trans_commit();
		$out['status'] = 'berhasil';
		} else {
		$this->db->trans_rollback();
		$out['status'] = 'gagal';
		}
		
		} else {
		$out['status'] = 'gagal';
		}

		echo json_encode($out);
	}
	

    //  public function checkBrand($isAjax = 1, $nama_brand, $idBrand = NULL) {
    //     $isExist = false;
    //     if ($isAjax > 0) {
    //         $nama_brand = trim($this->input->post("nama_brand"));
    //         $idBrand = trim($this->input->post("id_brand"));
    //     }
    //     if (strlen($phone) > 0) {
    //         $q = "SELECT * FROM tbl_brand WHERE nama_brand = '{$nama_brand}'";
    //         if (strlen($idBrand) > 0) {
    //             $q .= " AND id_brand <> '{$idBrand}'";
    //         }
    //         $result = $this->db->query($q)->row_array();
    //         if ($result != NULL) {
    //             $isExist = true;
    //         }
    //     }
    //     $arr = array('is_exist' => $isExist);

    //     if ($isAjax > 0) {
    //         echo json_encode($arr);
    //     } else {
    //         return $isExist;
    //     }
    // }

	
	
	public function Edit($id) {
		
		/*ini harus ada boss */
		$data['userdata'] = $this->userdata;
		$access = $this->M_sidebar->access('edit',self::__kode_menu);
		if ($access->menuview == 0){
			$data['page'] 		= self::__title;
			$data['judul'] 		= self::__title;
			$this->loadkonten('Dashboard/layouts/no_akses',$data);
		 }
		 /*ini harus ada boss */
		 else{
		
		$where=array(self::__tableId => $id);
		$data['brand']  = $this->M_cabang->selectById($id);

		$data['page'] 		= self::__title;
		$data['judul'] 		= self::__title;
	    $this->loadkonten(''.self::__folder.'update',$data);
	}
	}
	

	public function prosesUpdate() {
		
		$username = $this->userdata->nama;
        $date = date('Y-m-d H:i:s');
        $where = trim($this->input->post(self::__tableId));

        $this->db->trans_begin();

    	if (isset($_POST["nama_cabang"]) && !empty($_POST["nama_cabang"])) 
		{
		$data = array(
		       'nama_cabang'  => $this->input->post('nama_cabang'),
	           'updated_by' => $username,
               'updated_date' => $date,
		);
		$result = $this->db->update(self::__tableName, $data, array(self::__tableId => $where));

		if ($this->db->trans_status() === FALSE) {
              $out['status'] = 'gagal';   
            }
			
		if ($result > 0) {
		$this->db->trans_commit();
		$out['status'] = 'berhasil';
		} else {
		$this->db->trans_rollback();
		$out['status'] = 'gagal';
		}
		
		} else {
		$out['status'] = 'gagal';
		}

		echo json_encode($out);
	}
	
	public function hapus() {
        
        $id = $_POST[self::__tableId];
		$result = $this->M_cabang->hapus($id);
		
		if ($result > 0) {
			
			$out['status'] = 'berhasil';
		} else {
			
			$out['status'] = 'gagal';
		}
	}
	
	
}
