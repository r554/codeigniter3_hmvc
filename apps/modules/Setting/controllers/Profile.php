<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends AUTH_Controller {
	public function __construct() {
		parent::__construct();
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
	

	public function index() {
		$data['userdata'] 		= $this->userdata;
		
		$data['page'] 			= "profile";
		$data['judul'] 			= "Profile";
		$data['datagrup']       = $this->M_admin->select_group();
		$data['dataStatus']     = $this->M_admin->select_status();
		$this->loadkonten('v_profil/profile', $data);
	}

	public function update() {
		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]|max_length[30]');
		$this->form_validation->set_rules('nama', 'Nama', 'trim|required');
		$this->form_validation->set_rules('last_update_by', 'last_update_by', 'trim|required');
		$id = $this->userdata->id;
		$data = $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$config['upload_path'] = './upload/user/';
			$config['allowed_types'] = 'jpg|png';
			
			$this->load->library('upload', $config);
			
			if (!$this->upload->do_upload('foto')){
				$error = array('error' => $this->upload->display_errors());
			}
			else{
				$data_foto = $this->upload->data();
				$data['foto'] = $data_foto['file_name'];
			}

			$result = $this->M_admin->update($data, $id);
			if ($result > 0) {
				$this->updateProfil();
				$out['status'] = 'berhasil';
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."profile.html'>";
			} else {
				$out['status'] = 'gagal';
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."profile.html'>";
			}
		} else {
			$out['status'] = 'gagal';
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."profile.html'>";
		}
	}

	public function ubah_password() {
		$this->form_validation->set_rules('passLama', 'Password Lama', 'trim|required');
		$this->form_validation->set_rules('passBaru', 'Password Baru', 'trim|required');
		$this->form_validation->set_rules('passKonf', 'Password Konfirmasi', 'trim|required');
		$this->form_validation->set_rules('last_update_by', 'last_update_by', 'trim|required');

		$id = $this->userdata->id;
		if ($this->form_validation->run() == TRUE) {
			if (md5($this->input->post('passLama')) == $this->userdata->password) {
				if ($this->input->post('passBaru') != $this->input->post('passKonf')) {
					$this->session->set_flashdata('msg', show_err_msg('Password Baru dan Konfirmasi Password harus sama'));
					redirect('Profile');
				} else {
					$data = [
						'password' => md5($this->input->post('passBaru'))
					];

					$result = $this->M_admin->update($data, $id);
					if ($result > 0) {
					$this->updateProfil();
					$out['status'] = 'berhasil';
					echo "<meta http-equiv='refresh' content='0; url=".base_url()."profile.html'>";
					} else {
					$out['status'] = 'gagal';
					echo "<meta http-equiv='refresh' content='0; url=".base_url()."profile.html'>";
					}
				}
			} else {
				$out['status'] = 'gagal';
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."profile.html'>";;
			}
		} else {
			$out['status'] = 'gagal';
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."profile.html'>";
		}
	}

}

/* End of file Profile.php */
/* Location: ./application/controllers/Profile.php */