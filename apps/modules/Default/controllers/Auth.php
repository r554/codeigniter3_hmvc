<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('M_auth');
		$this->load->helper('activity');
		$this->load->model('M_twofa');
		$this->load->library('Twofa_lib');
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
				// Check if user has 2FA enabled
				if (isset($data->two_factor_enabled) && $data->two_factor_enabled == 1) {
					// Store user data temporarily in session for 2FA verification
					$this->session->set_userdata('pending_2fa_user_id', $data->id);
					$this->session->set_userdata('pending_2fa_username', $data->username);
					$this->session->set_userdata('pending_2fa_session', $session);
					$this->session->set_userdata('pending_2fa_time', time());
					
					log_activity('login_2fa_pending', 'Login dengan 2FA: username ' . $username, 'Auth');
					redirect('Default/Auth/verify_2fa');
				} else {
					// Normal login without 2FA
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
				}
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
	
	/**
	 * Display 2FA verification page
	 */
	public function verify_2fa() {
		// Check if there's a pending 2FA login
		$pending_user_id = $this->session->userdata('pending_2fa_user_id');
		$pending_time = $this->session->userdata('pending_2fa_time');
		
		// Check if session expired (10 minutes)
		if (!$pending_user_id || !$pending_time || (time() - $pending_time) > 600) {
			$this->session->unset_userdata('pending_2fa_user_id');
			$this->session->unset_userdata('pending_2fa_username');
			$this->session->unset_userdata('pending_2fa_time');
			$this->session->set_flashdata('error_msg', 'Sesi verifikasi 2FA telah berakhir. Silakan login kembali.');
			redirect('login');
		}
		
		$this->load->view('verify_2fa');
	}
	
	/**
	 * Verify 2FA code and complete login
	 */
	public function verify_2fa_code() {
		// Check pending login
		$pending_user_id = $this->session->userdata('pending_2fa_user_id');
		if (!$pending_user_id) {
			$this->session->set_flashdata('error_msg', 'Sesi tidak valid.');
			redirect('login');
		}
		
		// Check if locked out
		if ($this->twofa_lib->is_locked_out()) {
			$remaining = $this->twofa_lib->get_lockout_time_remaining();
			$this->session->set_flashdata('error_msg', "Terlalu banyak percobaan gagal. Coba lagi dalam " . ceil($remaining / 60) . " menit.");
			redirect('Default/Auth/verify_2fa');
		}
		
		$code = trim($this->input->post('code'));
		$use_backup = $this->input->post('use_backup') == '1';
		
		if (empty($code)) {
			$this->session->set_flashdata('error_msg', 'Kode verifikasi wajib diisi.');
			redirect('Default/Auth/verify_2fa');
		}
		
		$verified = false;
		
		if ($use_backup) {
			// Verify backup code
			$verified = $this->M_twofa->verify_backup_code($pending_user_id, $code);
			if ($verified) {
				log_activity('2fa_backup_used', 'Backup code digunakan untuk user ID: ' . $pending_user_id, 'Auth');
			}
		} else {
			// Verify TOTP code
			$secret = $this->M_twofa->get_secret($pending_user_id);
			if ($secret) {
				$verified = $this->twofa_lib->verify_code($secret, $code);
			}
		}
		
		if ($verified) {
			// Get user data and complete login
			$username = $this->session->userdata('pending_2fa_username');
			
			// Clear pending session
			$this->session->unset_userdata('pending_2fa_user_id');
			$this->session->unset_userdata('pending_2fa_username');
			$this->session->unset_userdata('pending_2fa_time');
			
			// Get full user data
			$this->db->select('admin.*, grup.*');
			$this->db->from('admin');
			$this->db->where('admin.id', $pending_user_id);
			$this->db->join('grup', 'grup.grup_id = admin.grup_id');
			$data = $this->db->get()->row();
			
			if ($data) {
				$session = [
					'userdata' => $data,
					'status' => "Loged in",
					'id' => $data->id,
					'username' => $data->username
				];
				
				$this->session->set_userdata($session);
				$this->M_auth->update($data->id, 3);
				
				// Update last login
				$this->M_auth->update_user($data->id, [
					'last_login_user' => date('Y-m-d H:i:s')
				]);
				
				log_activity('login_2fa_success', 'Login dengan 2FA berhasil', 'Auth');
				redirect('Dashboard');
			}
		} else {
			$remaining = $this->twofa_lib->get_remaining_attempts();
			if ($remaining > 0) {
				$this->session->set_flashdata('error_msg', "Kode verifikasi salah. Sisa percobaan: " . $remaining);
			} else {
				$this->session->set_flashdata('error_msg', "Terlalu banyak percobaan gagal. Akun dikunci sementara.");
			}
			log_activity('2fa_failed', 'Verifikasi 2FA gagal untuk user ID: ' . $pending_user_id, 'Auth');
			redirect('Default/Auth/verify_2fa');
		}
	}
	
	/**
	 * Display 2FA setup page
	 */
	public function setup_2fa() {
		// Check if user is logged in
		if ($this->session->userdata('status') == '') {
			redirect('Auth');
		}
		
		$user_id = $this->session->userdata('id');
		
		// Check if 2FA is already enabled
		if ($this->M_twofa->is_2fa_enabled($user_id)) {
			$this->session->set_flashdata('info_msg', '2FA sudah aktif untuk akun Anda.');
			redirect('Dashboard');
		}
		
		// Generate new secret
		$secret = $this->twofa_lib->generate_secret();
		$username = $this->session->userdata('username');
		$qr_code_url = $this->twofa_lib->get_qr_code_url($username, $secret);
		
		// Store secret temporarily in session
		$this->session->set_userdata('temp_2fa_secret', $secret);
		
		$data = [
			'secret' => $secret,
			'qr_code_url' => $qr_code_url,
			'username' => $username
		];
		
		$this->load->view('setup_2fa', $data);
	}
	
	/**
	 * Confirm 2FA setup and enable it
	 */
	public function confirm_2fa() {
		// Check if user is logged in
		if ($this->session->userdata('status') == '') {
			redirect('Auth');
		}
		
		$user_id = $this->session->userdata('id');
		$code = trim($this->input->post('code'));
		$secret = $this->session->userdata('temp_2fa_secret');
		
		if (!$secret) {
			$this->session->set_flashdata('error_msg', 'Sesi setup tidak valid. Silakan ulangi.');
			redirect('Default/Auth/setup_2fa');
		}
		
		// Verify the code
		if ($this->twofa_lib->verify_code($secret, $code)) {
			// Generate backup codes
			$backup_codes = $this->twofa_lib->generate_backup_codes();
			
			// Enable 2FA
			$this->M_twofa->enable_2fa($user_id, $secret, $backup_codes);
			
			// Clear temp secret
			$this->session->unset_userdata('temp_2fa_secret');
			
			// Store backup codes in session temporarily to display
			$this->session->set_flashdata('backup_codes', json_encode($backup_codes));
			$this->session->set_flashdata('success_msg', '2FA berhasil diaktifkan!');
			
			log_activity('2fa_enabled', '2FA diaktifkan untuk akun', 'Auth');
			
			// Redirect to a page showing backup codes
			redirect('Default/Auth/show_backup_codes');
		} else {
			$this->session->set_flashdata('error_msg', 'Kode verifikasi salah. Silakan coba lagi.');
			redirect('Default/Auth/setup_2fa');
		}
	}
	
	/**
	 * Show backup codes after successful 2FA setup
	 */
	public function show_backup_codes() {
		// Check if user is logged in
		if ($this->session->userdata('status') == '') {
			redirect('Auth');
		}
		
		$backup_codes_json = $this->session->flashdata('backup_codes');
		if (!$backup_codes_json) {
			redirect('Dashboard');
		}
		
		$data = [
			'backup_codes' => json_decode($backup_codes_json, true)
		];
		
		$this->load->view('show_backup_codes', $data);
	}
	
	/**
	 * Disable 2FA for current user
	 */
	public function disable_2fa() {
		// Check if user is logged in
		if ($this->session->userdata('status') == '') {
			redirect('Auth');
		}
		
		$user_id = $this->session->userdata('id');
		
		if ($this->M_twofa->disable_2fa($user_id)) {
			$this->session->set_flashdata('success_msg', '2FA berhasil dinonaktifkan.');
			log_activity('2fa_disabled', '2FA dinonaktifkan untuk akun', 'Auth');
		} else {
			$this->session->set_flashdata('error_msg', 'Gagal menonaktifkan 2FA.');
		}
		
		redirect('Dashboard');
	}

	// ============================================================
	// GOOGLE OAUTH2 LOGIN
	// ============================================================

	/**
	 * Redirect user to Google OAuth consent screen
	 */
	public function google_login() {
		// Check if Google login is enabled
		if (env('GOOGLE_LOGIN_ENABLED', '0') !== '1') {
			$this->session->set_flashdata('error_msg', 'Login dengan Google tidak diaktifkan.');
			redirect('login');
			return;
		}

		$client_id = env('GOOGLE_CLIENT_ID', '');
		if (empty($client_id)) {
			$this->session->set_flashdata('error_msg', 'Google Client ID belum dikonfigurasi.');
			redirect('login');
			return;
		}

		// Generate state token for CSRF protection
		$state = bin2hex(random_bytes(16));
		$this->session->set_userdata('google_oauth_state', $state);

		$redirect_uri = site_url('auth/google/callback');

		$params = http_build_query([
			'client_id'     => $client_id,
			'redirect_uri'  => $redirect_uri,
			'response_type' => 'code',
			'scope'         => 'openid email profile',
			'state'         => $state,
			'access_type'   => 'online',
			'prompt'        => 'select_account',
		]);

		redirect('https://accounts.google.com/o/oauth2/v2/auth?' . $params);
	}

	/**
	 * Handle Google OAuth callback
	 */
	public function google_callback() {
		// Check if Google login is enabled
		if (env('GOOGLE_LOGIN_ENABLED', '0') !== '1') {
			$this->session->set_flashdata('error_msg', 'Login dengan Google tidak diaktifkan.');
			redirect('login');
			return;
		}

		// Check for error from Google
		if ($this->input->get('error')) {
			$this->session->set_flashdata('error_msg', 'Login Google dibatalkan.');
			redirect('login');
			return;
		}

		$code  = $this->input->get('code');
		$state = $this->input->get('state');

		// Validate state token (CSRF protection)
		$saved_state = $this->session->userdata('google_oauth_state');
		$this->session->unset_userdata('google_oauth_state');

		if (empty($code) || empty($state) || $state !== $saved_state) {
			$this->session->set_flashdata('error_msg', 'Sesi login Google tidak valid. Silakan coba lagi.');
			redirect('login');
			return;
		}

		$client_id     = env('GOOGLE_CLIENT_ID', '');
		$client_secret = env('GOOGLE_CLIENT_SECRET', '');
		$redirect_uri  = site_url('auth/google/callback');

		// Exchange authorization code for access token
		$token_data = $this->_google_curl_post('https://oauth2.googleapis.com/token', [
			'code'          => $code,
			'client_id'     => $client_id,
			'client_secret' => $client_secret,
			'redirect_uri'  => $redirect_uri,
			'grant_type'    => 'authorization_code',
		]);

		if (!$token_data || empty($token_data->access_token)) {
			$this->session->set_flashdata('error_msg', 'Gagal mendapatkan token dari Google.');
			redirect('login');
			return;
		}

		// Get user info from Google
		$user_info = $this->_google_curl_get('https://www.googleapis.com/oauth2/v2/userinfo', $token_data->access_token);

		if (!$user_info || empty($user_info->email)) {
			$this->session->set_flashdata('error_msg', 'Gagal mendapatkan informasi akun Google.');
			redirect('login');
			return;
		}

		// Ensure google_id column exists (auto-migration)
		$this->_ensure_google_id_column();

		// Find admin user by google_id or email
		$data = $this->M_auth->login_google($user_info->id, $user_info->email);

		if (!$data) {
			$this->session->set_flashdata('error_msg', 'Akun Google (' . $user_info->email . ') tidak terdaftar sebagai admin.');
			redirect('login');
			return;
		}

		// Check user status
		if ($data->status != 3) {
			$this->session->set_flashdata('error_msg', 'Maaf user belum aktif.');
			redirect('login');
			return;
		}

		// Check if user has 2FA enabled
		if (isset($data->two_factor_enabled) && $data->two_factor_enabled == 1) {
			$this->session->set_userdata('pending_2fa_user_id', $data->id);
			$this->session->set_userdata('pending_2fa_username', $data->username);
			$this->session->set_userdata('pending_2fa_time', time());
			log_activity('login_google_2fa_pending', 'Login Google dengan 2FA: ' . $user_info->email, 'Auth');
			redirect('Default/Auth/verify_2fa');
			return;
		}

		// Complete login
		$session = [
			'userdata' => $data,
			'status'   => "Loged in",
			'id'       => $data->id,
			'username' => $data->username,
		];

		$this->session->set_userdata($session);
		$this->M_auth->update($data->id, 3);
		$this->M_auth->update_user($data->id, [
			'last_login_user' => date('Y-m-d H:i:s')
		]);

		log_activity('login_google', 'Login dengan Google berhasil: ' . $user_info->email, 'Auth');
		redirect('Dashboard');
	}

	/**
	 * POST request via cURL
	 */
	private function _google_curl_post($url, $post_data) {
		$ch = curl_init();
		curl_setopt_array($ch, [
			CURLOPT_URL            => $url,
			CURLOPT_POST           => true,
			CURLOPT_POSTFIELDS     => http_build_query($post_data),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_TIMEOUT        => 15,
		]);
		$response = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if ($http_code !== 200) return null;
		return json_decode($response);
	}

	/**
	 * GET request via cURL with Bearer token
	 */
	private function _google_curl_get($url, $access_token) {
		$ch = curl_init();
		curl_setopt_array($ch, [
			CURLOPT_URL            => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_TIMEOUT        => 15,
			CURLOPT_HTTPHEADER     => [
				'Authorization: Bearer ' . $access_token,
			],
		]);
		$response = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if ($http_code !== 200) return null;
		return json_decode($response);
	}

	/**
	 * Ensure google_id column exists in admin table (auto-migration)
	 */
	private function _ensure_google_id_column() {
		$this->load->database();
		if (!$this->db->field_exists('google_id', 'admin')) {
			$this->db->query("ALTER TABLE `admin` ADD COLUMN `google_id` VARCHAR(255) DEFAULT NULL AFTER `email`");
		}
	}
}
