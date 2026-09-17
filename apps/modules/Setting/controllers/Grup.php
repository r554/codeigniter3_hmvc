<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grup extends AUTH_Controller {
	

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_grup');
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
        $data['userdata'] 	= $this->userdata;  
		$data['page'] 		= "grup";
		$data['judul'] 		= "grup";
		$data['dataWarna'] = $this->M_grup->get_datatables();
		$this->loadkonten('v_grup/v_home',$data);
		
	}
	
	// public function ajax_list()
	// {
		// $list = $this->M_grup->get_datatables();
		// $data = array();
		// $no = $_POST['start'];
		// foreach ($list as $grup) {
			// $no++;
			// $row = array();
			// $row[] = $no;
			// $row[] = $grup->nama_grup;  
			// $row[] = $grup->deskripsi;
			
			// //add html for action
			// $row[] =  anchor('edit-grup/'.$grup->grup_id, ' <span class="fa fa-edit"></span> ', ' class="btn btn-sm btn-primary ajaxify klik " ').
					 // ' '.anchor('hak-akses/'.$grup->grup_id, ' <span class="fa fa-cogs"></span> ', ' class="btn btn-success btn-sm btn-icon ajaxify klik " ').			
				      // ' <button class="btn btn-sm btn-danger hapus-grup" data-id='."'".$grup->grup_id."'".'><i class="glyphicon glyphicon-trash"></i></button>';  
			// $data[] = $row;
		// }

		// $output = array(
						// "draw" => $_POST['draw'],
						// "data" => $data,
				// );
		// //output to json format
		// echo json_encode($output);
		
		
	// }
	
	
	public function add() {
		
		$data['userdata'] = $this->userdata;
		
		$data['page'] 		= "grup";
		$data['judul'] 		= "grup";
		$data['deskripsi'] = "Manage Data Kategori Halaman";
		
		$this->loadkonten('v_grup/v_tambah-grup',$data);
	}

	public function prosesTambah() {
		
		
		
		if (isset($_POST["nama_grup"]) && !empty($_POST["nama_grup"])) 
		{
		$data = array(
		'nama_grup'   	       => $this->input->post('nama_grup'), 
		'deskripsi'            => $this->input->post('deskripsi'),
		'created_by'           => $this->input->post('created_by'),
		'last_created_date'	   => date('Y-m-d H:i:s')
		);
		$result = $this->M_grup->simpan_data($data);
			
		if ($result > 0) {
		$out['status'] = 'berhasil';
		} else {
		$out['status'] = 'gagal';
		}
		
		} else {
			$out['status'] = 'gagal';
		}

		echo json_encode($out);
		
	}
	
	
	public function Edit($id) {
		
		$where=array('grup_id' => $id);
		
		$data['datagrup'] = $this->M_grup->select_by_id($id);
		$data['userdata'] = $this->userdata;

		$data['page'] 		= "grup";
		$data['judul'] 		= "grup";
		$data['deskripsi'] = "Manage Data Kategori";
	    $this->loadkonten('v_grup/v_update-grup',$data);
	}
	
	
	
	public function prosesUpdate() {
		
		if (isset($_POST["nama_grup"]) && !empty($_POST["nama_grup"])) 
		{
			
		$where = array(
		'grup_id'            => $this->input->post('grup_id'),
		);	
			
		$data = array(
		'nama_grup'   	       => $this->input->post('nama_grup'), 
		'deskripsi'            => $this->input->post('deskripsi'),
		'last_update_by'       => $this->input->post('last_update_by'),
		'last_update_date'	   => date('Y-m-d H:i:s')
		);	
		
		$result = $this->M_grup->update($data,$where);
			
		if ($result > 0) {
		$out['status'] = 'berhasil';
		} else {
		$out['status'] = 'gagal';
		}
		
		} else {
		  $out['status'] = 'gagal';
		}

		echo json_encode($out);
	}
	
	
	public function hapus() {
		$id = $_POST['grup_id'];
		$result = $this->M_grup->hapus($id);
		
		if ($result > 0) {
			$out['status'] = 'berhasil';
		} else {
			$out['status'] = 'gagal';
		}
	}	


}
