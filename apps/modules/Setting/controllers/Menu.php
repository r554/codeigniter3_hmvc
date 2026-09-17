<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends AUTH_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_menu');
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
		$data['page'] 		= "menu";
		$data['judul'] 		= "menu";
		$this->loadkonten('v_menu/v_home',$data);
		
	}
	
	public function ajax_list()
	{
		$list = $this->M_menu->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $menu) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $menu->nama_menu;

			// Kolom parent dengan badge
			if (!empty($menu->nama_parent)) {
				$row[] = '<span class="label label-primary">' . htmlspecialchars($menu->nama_parent) . '</span>';
			} else {
				$row[] = '<span class="label label-success">Main Menu</span>';
			}

			$row[] = $menu->icon;
			$row[] = $menu->link;
			$row[] = $menu->menu_file;

			$row[] = anchor('edit-menu/'.$menu->id_menu, ' <span class="fa fa-edit"></span> ', ' class="btn btn-sm btn-primary ajaxify klik " ')
				   . ' <button class="btn btn-sm btn-danger hapus-menu" data-id=\''. $menu->id_menu .'\'><i class="glyphicon glyphicon-trash"></i></button>';
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"data" => $data,
		);
		echo json_encode($output);
	}
	
	public function menu_ajak(){
            $cari = $this->input->get('q');
            $data_menu = $this->M_menu->cari_ajak($cari);
            echo json_encode($data_menu);
        }
	
	public function icon() {
		
		$data['userdata'] 	= $this->userdata;  
		$data['page'] 		= "icon";
		$data['judul'] 		= "icon";
		$this->loadkonten('v_menu/icon',$data);
	}
	
	
	public function add() {
		
		/*ini harus ada boss */
		$data['userdata'] = $this->userdata;
		$access = $this->M_sidebar->access('add','menu-master');
		if ($access->menuview == 0){
			$data['page'] 	= "user";
			$data['judul'] 	= "add Menu ";
			$this->loadkonten('layouts/no_akses',$data);
		}
		 /*ini harus ada boss */
		else{
			
		$data['dataMenu'] = $this->M_menu->pilih_menu();
		$data['page'] 		= "menu";
		$data['judul'] 		= "menu";
		
		$this->loadkonten('v_menu/v_tambah-menu',$data);
	}
  }

	public function prosesTambah() {
		
		if (isset($_POST["nama_menu"]) && !empty($_POST["nama_menu"])) 
		{
		$data = array(
		'nama_menu' 		       => $this->input->post('nama_menu'),
		'icon' 		       		   => $this->input->post('icon'),
		'link' 		       		   => $this->input->post('link'),
		'kode_menu' 		       => $this->input->post('kode_menu'),
		'parent'		   		   => $this->input->post('parent'),
		'urutan'		   		   => $this->input->post('urutan'),
		'menu_file' 			   => implode(",",$this->input->post('menu_file')),
		'created_by'               => $this->input->post('created_by'),
		'last_created_date'		   => date('Y-m-d H:i:s')
		);
		$result = $this->M_menu->simpan_data($data);
			
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
		
		//*ini harus ada boss */
		$data['userdata'] = $this->userdata;
		$access = $this->M_sidebar->access('edit','menu-master');
		if ($access->menuview == 0){
			$data['page'] 	= "user";
			$data['judul'] 	= "edit Menu";
			$this->loadkonten('layouts/no_akses',$data);
		}
		 /*ini harus ada boss */
		else{	
		
		$where=array('id_menu' => $id);
		
		$data['dataMenu'] = $this->M_menu->select_by_id($id);
		$data['dataMenu2'] = $this->M_menu->pilih_menu();
		$data['userdata'] = $this->userdata;

		$data['page'] 	= "menu";
		$data['judul'] 	= "menu";
		
	    $this->loadkonten('v_menu/v_update-menu',$data);
	}
 }
	
	public function prosesUpdate() {

		if (isset($_POST["nama_menu"]) && !empty($_POST["nama_menu"])) 
		{
			
		$where = array(
		'id_menu' 		   => $this->input->post('id_menu')
		);	
			
		$data = array(
		'nama_menu' 		       => $this->input->post('nama_menu'),
		'icon' 		       		   => $this->input->post('icon'),
		'link' 		       		   => $this->input->post('link'),
		'kode_menu' 		       => $this->input->post('kode_menu'),
		'parent'		   		   => $this->input->post('parent'),
		'urutan'		   		   => $this->input->post('urutan'),
		'menu_file' 			   => implode(",",$this->input->post('menu_file')),
		'last_update_by'           => $this->input->post('last_update_by'),
		'last_update_date'		   => date('Y-m-d H:i:s')
		);
		
		$result = $this->M_menu->update($data,$where);
			
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
		
		//*ini harus ada boss */
		$data['userdata'] = $this->userdata;
		$access = $this->M_sidebar->access('del','menu-master');
		if ($access->menuview == 0){
			$data['page'] 	= "user";
			$data['judul'] 	= "delete";
			$this->loadkonten('layouts/no_akses',$data);
		}
		 /*ini harus ada boss */
		else{	
		
		$id = $_POST['id_menu'];
		$result = $this->M_menu->hapus($id);
		
		if ($result > 0) {
			$out['status'] = 'berhasil';
		} else {
			$out['status'] = 'gagal';
		}
	  }
   }	
	
}
