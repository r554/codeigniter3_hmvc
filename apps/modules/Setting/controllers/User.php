<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User extends AUTH_Controller {

	public function __construct()
    {
		parent::__construct();
		$this->load->model('M_user');
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
		$data['page'] 		= "user";
		$data['judul'] 		= "user";
		$this->loadkonten('v_user/v_home',$data);
	}
	
	public function ajax_list()
	{
		$list = $this->M_user->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $user) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = '<a href="'.base_url().'upload/user/'.$user->foto.'" target="_blank"><img class="img-thumbnail" src="'.base_url().'upload/user/'.$user->foto.'"   width="90" /></a>';    
			$row[] = $user->email;
			$row[] = $user->nama;  
			$row[] = $user->grup_id;
			$row[] = '<small class="label pull-center bg-green">'.$user->status.'</small">';
			
			//add html for action
			$row[] =  anchor('edit-user/'.$user->id, ' <span class="fa fa-edit"></span>', ' class="btn btn-sm btn-primary ajaxify klik " '). 
				      ' <button class="btn btn-sm btn-danger hapus-user" data-id='."'".$user->id."'".'><i class="glyphicon glyphicon-trash"></i></button>';  
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
		
		
	}
	
	public function add() {
		
	    /*ini harus ada boss */
		$data['userdata'] = $this->userdata;
		$access = $this->M_sidebar->access('add','user');
		if ($access->menuview == 0){
			$data['userdata'] = $this->userdata;
			$data['page'] 	= "user";
			$data['judul'] 	= "add user";
			$this->loadkonten('layouts/no_akses',$data);
		 }
		 /*ini harus ada boss */
		 else
		 {
		
		 $data['datagrup']   = $this->M_user->select_group();	
		 $data['page'] 	= "user";
		 $data['judul'] 	= "user";
		 $this->loadkonten('v_user/v_tambah-user',$data);
		 }
	}

	public function prosesTambah() {
		
		
		if (isset($_POST["username"]) && !empty($_POST["password"])) 
		{
		$data2 = array( 
					'nama'   	           => $this->input->post('nama'), 
					'email'            	   => $this->input->post('email'),
					'username'             => $this->input->post('username'),
					'password'             => md5($this->input->post('password')),
					'grup_id'              => $this->input->post('grup_id'),
					'foto'                 => 'no-image.jpg',	
					'hidden'               => '1',
					'status'               => $this->input->post('status'),
					'created_by'           => $this->input->post('created_by'),					
					'last_created_date'    => date('Y-m-d H:i:s')						 
					);  

	    $result = $this->M_user->simpan_data($data2);
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
		
		/*ini harus ada boss */
		$data['userdata'] = $this->userdata;
		$access = $this->M_sidebar->access('edit','user');
		if ($access->menuview == 0){
			$data['userdata'] = $this->userdata;
			$data['page'] 	= "user";
			$data['judul'] 	= "edit user";
			$this->loadkonten('layouts/no_akses',$data);
		 }
		 /*ini harus ada boss */
		 else{
		 $where=array('id_warna' => $id);
		
		$data['dataUser']   = $this->M_user->select_by_id($id);
		$data['datagrup']   = $this->M_user->select_group();
		$data['dataStatus'] = $this->M_user->select_status();
		
		$data['page'] = "user";
		$data['judul'] = "user";
	    $this->loadkonten('v_user/v_update-user',$data);
		 }
	}
	
	
	
	public function prosesUpdate() {
		
		if (isset($_POST["username"]) && !empty($_POST["password"])) 
		{
		
		$where = array(
		'id'   => $this->input->post('id')
		);
	    $data2 = array( 
					'nama'   	           => $this->input->post('nama'), 
					'email'            	   => $this->input->post('email'),
					'username'             => $this->input->post('username'),
					'password'             => md5($this->input->post('password')),
					'grup_id'              => $this->input->post('grup_id'),				
					'status'               => $this->input->post('status'),
					'last_update_by'       => $this->input->post('last_update_by'),					
					'last_update_date'     => date('Y-m-d H:i:s')					 
					); 
		
		$result = $this->M_user->update($data2,$where);
			
			
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
		
		/*ini harus ada boss */
		$access = $this->M_sidebar->access('del','user');
		if ($access->menuview == 0){
			$data['userdata'] = $this->userdata;
			$data['page'] 	= "hapus ";
			$data['judul'] 	= "Delete User";
			$this->loadkonten('layouts/no_akses',$data);
		 }
		 /*ini harus ada boss */
		
		else{	
		$id = $_POST['id'];
		$result = $this->M_user->hapus($id);
		
		if ($result > 0) {
			$out['status'] = 'berhasil';
		} else {
			$out['status'] = 'gagal';
		}
	  }
	}
	
	
}
