<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends AUTH_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('M_sidebar');
		$this->load->model('Default/M_twofa');
		$this->load->library('Twofa_lib');
		$this->load->helper('activity');
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
		// Reload the current user from database so 2FA status is always current.
		$fresh_userdata = $this->M_admin->select($this->userdata->id);
		if ($fresh_userdata) {
			$this->userdata = $fresh_userdata;
			$this->session->set_userdata('userdata', $fresh_userdata);
		}
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

	// AJAX: Generate 2FA secret and QR code
	public function generate_2fa_secret() {
		$user_id = $this->userdata->id;
		$email = $this->userdata->email;
		$username = $this->userdata->username;

		// Generate secret
		$secret = $this->twofa_lib->generate_secret();
		
		// Store secret in session temporarily with timestamp
		$this->session->set_userdata([
			'temp_2fa_secret' => $secret,
			'temp_2fa_secret_time' => time(),
			'temp_2fa_user_id' => $user_id
		]);
		
		// Generate QR code URL
		$qr_url = $this->twofa_lib->get_qr_code_url($username, $secret, 'CoatesApp');

		echo json_encode([
			'status' => 'success',
			'qr_url' => $qr_url,
			'secret' => $secret,
			'debug' => [
				'session_id' => $this->session->userdata('session_id'),
				'timestamp' => time()
			]
		]);
	}

	// AJAX: Verify code and enable 2FA
	public function verify_and_enable_2fa() {
		$code = $this->input->post('code');
		$secret = $this->session->userdata('temp_2fa_secret');
		$secret_time = $this->session->userdata('temp_2fa_secret_time');
		$stored_user_id = $this->session->userdata('temp_2fa_user_id');
		$user_id = $this->userdata->id;

		// Check if session data exists
		if (empty($secret)) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Session kadaluarsa. Silakan tutup modal dan klik "Aktifkan 2FA" lagi.',
				'debug' => [
					'has_secret' => !empty($secret),
					'has_time' => !empty($secret_time),
					'session_id' => $this->session->userdata('session_id')
				]
			]);
			return;
		}

		// Check session timeout (10 minutes)
		if (!empty($secret_time) && (time() - $secret_time) > 600) {
			$this->session->unset_userdata(['temp_2fa_secret', 'temp_2fa_secret_time', 'temp_2fa_user_id']);
			echo json_encode([
				'status' => 'error',
				'message' => 'Session timeout (lebih dari 10 menit). Silakan ulangi dari awal.'
			]);
			return;
		}

		// Verify user ID matches
		if (!empty($stored_user_id) && $stored_user_id != $user_id) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Session tidak valid. Silakan ulangi dari awal.'
			]);
			return;
		}

		// Verify code
		if ($this->twofa_lib->verify_code($secret, $code)) {
			// Generate backup codes
			$backup_codes = $this->twofa_lib->generate_backup_codes();
			
			// Enable 2FA in database
			$this->M_twofa->enable_2fa($user_id, $secret, $backup_codes);
			
			// Clear temp secret from session
			$this->session->unset_userdata(['temp_2fa_secret', 'temp_2fa_secret_time', 'temp_2fa_user_id']);
			
			// Log activity
			log_activity('2fa_enabled', 'Two-Factor Authentication diaktifkan', 'Profile');

			echo json_encode([
				'status' => 'success',
				'message' => '2FA berhasil diaktifkan!',
				'backup_codes' => $backup_codes
			]);
		} else {
			echo json_encode([
				'status' => 'error',
				'message' => 'Kode tidak valid. Pastikan Anda memasukkan kode 6 digit yang benar dari aplikasi authenticator.'
			]);
		}
	}

	// AJAX: Disable 2FA
	public function disable_2fa_profile() {
		$user_id = $this->userdata->id;
		
		// Disable 2FA
		$result = $this->M_twofa->disable_2fa($user_id);
		
		if ($result) {
			log_activity('2fa_disabled', 'Two-Factor Authentication dinonaktifkan', 'Profile');
			
			echo json_encode([
				'status' => 'success',
				'message' => '2FA berhasil dinonaktifkan.'
			]);
		} else {
			echo json_encode([
				'status' => 'error',
				'message' => 'Gagal menonaktifkan 2FA.'
			]);
		}
	}

	// AJAX: Get backup codes
	public function get_backup_codes() {
		$user_id = $this->userdata->id;
		
		// Get user data with backup codes
		$user = $this->M_twofa->get_user_2fa_status($user_id);
		
		if ($user && !empty($user['two_factor_backup_codes'])) {
			$codes = json_decode($user['two_factor_backup_codes'], true);
			
			echo json_encode([
				'status' => 'success',
				'codes' => $codes
			]);
		} else {
			echo json_encode([
				'status' => 'error',
				'message' => 'Backup codes tidak ditemukan.'
			]);
		}
	}

}

/* End of file Profile.php */
/* Location: ./application/controllers/Profile.php */