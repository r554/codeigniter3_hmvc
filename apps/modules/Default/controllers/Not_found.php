<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Not_found extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_auth');
    }

    public function index() {

        $session = $this->session->userdata('status');

        if ($session == '') {
            $this->load->view('login');
        } else {
            $this->load->view('not-found');
        }
    }

}
