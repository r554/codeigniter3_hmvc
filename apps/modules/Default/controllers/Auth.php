<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('M_auth');
		$this->load->helper('activity');
	}
	
	public function index() {
		$session = $this->session->userdata('status');

		if ($session == '') {
			$this->load->view('login');
		} else {
			$this->load->view('login');
		}
	}

	public function login() {
		$this->form_validation->set_rules('username', 'Username', 'required|min_length[4]|max_length[30]');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == TRUE) {
			$username = trim($_POST['username']);
			$password = trim($_POST['password']);

			$data = $this->M_auth->login($username, $password);
			
			/* ketika status aktif tidak sama*/
			
			if($data) {
			$session = [ 'userdata' => $data, 'status' => "Loged in"];			
			
            $session['id'] = $data->id;
            $session['username'] = $data->username;
            $session['status'] = $data->status;
            $stat = $data->status;
            if($stat==3) {
                $this->session->set_userdata($session);
                $status = 3;
                $this->M_auth->update($session['id'],$status);
				
				/* last login user */
				
				$data = array(
				'last_login_user' => date('Y-m-d H:i:s')
				);
				
		        $this->M_auth->update_user($session['id'],$data);
                
				log_activity('login', 'Login berhasil', 'Auth');
				redirect('Dashboard');
            } else {
                $this->session->set_flashdata('error_msg', 'Maaf user belum aktif .');
                redirect('login');
            }
			}
			
			/* ketika status nama dan password tidak sama*/
			
			if ($data == false) {
				log_activity('login_gagal', 'Login gagal: username ' . $this->input->post('username'), 'Auth');
				$this->session->set_flashdata('error_msg', 'Username / Password Anda Salah.');
				redirect('login');
			} else {
				$session = [
					'userdata' => $data,
					'status' => "Loged in"
				];
				$this->session->set_userdata($session);
				redirect('Dashboard');
			}
		} 
		
		/* ketika semuanya salah */
		
		else {
			$this->session->set_flashdata('error_msg', validation_errors());
			redirect('login');
		}
			
			
	}

	public function logout() {
		log_activity('logout', 'User logout', 'Auth');
		$this->session->sess_destroy();
		redirect('login');
	}
}
