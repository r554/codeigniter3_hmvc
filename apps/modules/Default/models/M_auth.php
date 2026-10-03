<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_auth extends CI_Model {
	
	public function login($user, $pass) {
		$this->db->select('admin.*, grup.*, admin.two_factor_enabled, admin.two_factor_secret');
		$this->db->from('admin');
		$this->db->where('username', $user);
		$this->db->where('password', md5($pass));
		$this->db->join('grup', 'grup.grup_id = admin.grup_id');
		$data = $this->db->get();

		if ($data->num_rows() == 1) {
			$result = $data->row();
			$sessiondata['id']		    = $result->id;
			$sessiondata['username']	= $result->username;
			$sessiondata['password']	= $result->password;
			$sessiondata['nama']	    = $result->nama;
			$sessiondata['email']		= $result->email;
			$sessiondata['grup_id']		= $result->grup_id;
			$sessiondata['foto']		= $result->foto;
			$sessiondata['status']		= $result->status;
			$this->session->set_userdata($sessiondata);
			return $data->row();
			
		} else {
			return false;
		}
	}

	/**
	 * Login via Google OAuth
	 * Find admin by google_id first, then fallback to email match.
	 * If matched by email and google_id is not yet set, link the account.
	 */
	public function login_google($google_id, $email) {
		$this->db->select('admin.*, grup.*, admin.two_factor_enabled, admin.two_factor_secret');
		$this->db->from('admin');
		$this->db->join('grup', 'grup.grup_id = admin.grup_id');
		$this->db->group_start();
			$this->db->where('admin.google_id', $google_id);
			$this->db->or_where('admin.email', $email);
		$this->db->group_end();
		$data = $this->db->get();

		if ($data->num_rows() == 1) {
			$result = $data->row();

			// Link google_id to this admin if not yet linked
			if (empty($result->google_id) && $result->email === $email) {
				$this->db->where('id', $result->id);
				$this->db->update('admin', ['google_id' => $google_id]);
				$result->google_id = $google_id;
			}

			return $result;
		}

		return false;
	}
	
	
	function update($id,$stat)
    {
        $data['status'] = $stat;
        $this->db->where('id', $id);
        $this->db->update('admin', $data);
        return TRUE;
    }
	
	function update_user($id,$data)
    {
        $this->db->where('id', $id);
        $this->db->update('admin', $data);
        return TRUE;
    }
	
	
	
	
}
