<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Akses extends AUTH_Controller {

	function __construct(){
        parent::__construct();   
		$this->load->model('M_akses', 'akses');
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
	
	// ADD DATA
	public function hak_akses($id) {
		$access = $this->M_sidebar->access('edit','grup');
	    ($access->menuview == 1)?exit('No direct script access allowed'):'';
		
		$access = $this->M_sidebar->access('edit','grup');
		$this->_set_rules_privilege();
		$data['privilege'] 		= $this->akses->hak_akses($id);
		$data['groupname']		= $this->akses->select_by_id($id);
		$data['grup_id']		= $id;
		$data['userdata']       = $this->userdata;

		$data['page'] 		= "Hak Akses";
		$data['judul'] 		= "Hak Akses";
	    $this->loadkonten('v_akses/v_hak_akses',$data);
	}
	
	
	
	public function update_hak_akses($id) {
		
		$data['privilege'] 		= $this->akses->hak_akses($id);
		$data['groupname']		= $this->akses->select_by_id($id);
		$data['grup_id']		= $id;
		$data['userdata']       = $this->userdata;
		
	    $delete = $this->akses->hapus($id);
		
		if($delete==FALSE){
		$menu 	    = $this->input->post('id_menu');		
		foreach($menu as $row=>$id_menu){
		$view 	= isset($_REQUEST['view'][$id_menu]);
		$add 	= isset($_REQUEST['add'][$id_menu]);
		$edit 	= isset($_REQUEST['edit'][$id_menu]);
		$del 	= isset($_REQUEST['del'][$id_menu]);		
			
		$data = array(
	    'id_menu' 	=> $id_menu,
		'grup_id' 	=> $id,
		'view' 		=> $view,
		'add' 		=> $add,
		'edit' 		=> $edit,
		'del' 		=> $del,
		);
		$result = $this->akses->save($data);
			
		if ($result > 0) {
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."user-grup'>";
		} else {
		$out['status'] = 'gagal';
		}				
		}
				
		}
		else
		{
		
		$menu 	    = $this->input->post('id_menu');		
		foreach($menu as $row=>$id_menu){
		$view 	= isset($_REQUEST['view'][$id_menu]);
		$add 	= isset($_REQUEST['add'][$id_menu]);
		$edit 	= isset($_REQUEST['edit'][$id_menu]);
		$del 	= isset($_REQUEST['del'][$id_menu]);		
			
		$data = array(
		'id_menu' 	=> $id_menu,
		'grup_id' 	=> $id,
		'view' 		=> $view,
		'add' 		=> $add,
		'edit' 		=> $edit,
		'del' 		=> $del,
		);
		$result = $this->akses->save($data);
			
		if ($result > 0) {
			
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."user-grup'>";
		} else {
		$out['status'] = 'gagal';
		}				
		}
		
		}		
	}
	
	function _set_rules_privilege(){
		$this->form_validation->set_rules('id_menu','Menu','');
	}
	
		
}
