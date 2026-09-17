<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lib
{
	function __construct()
	{
		$this->ci =& get_instance();
	}

	function destroy_all()
	{
		$this->ci->session->sess_destroy();
		$this->ci->session->sess_create();
	}

	function login()
	{
		return $this->ci->session->userdata('email');
	}

	function logout()
	{
		$this->ci->session->unset_userdata('email' ,'id');
		$this->ci->session->sess_destroy();
	}

	function record()
	{
		return $this->ci->mdl_login->loggedin(array('email' => $this->login()));
	}

	function format_rupiah($duit)
	{
		return "Rp ".number_format($duit,0,",",".");
	}

	function status_checkout($status)
	{
		if ($status == 1)
		{
			return "Pending";
		}
		elseif ($status == 2)
		{
			return "Transaksi Terkirim";
		}
	}
}