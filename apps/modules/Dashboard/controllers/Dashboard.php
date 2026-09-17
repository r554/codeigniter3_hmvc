<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends AUTH_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function loadkonten($page, $data)
    {
        $data['userdata'] = $this->userdata;
        $ajax = ($this->input->post('status_link') === 'ajax');

        if (!$ajax) {
            $this->load->view('layouts/header', $data);
        }
        $this->load->view($page, $data);
        if (!$ajax) {
            $this->load->view('layouts/footer', $data);
        }
    }

    public function index()
    {
        $data = [
            'judul' => 'Dashboard',
            'page' => 'home',
            'stats' => [
                ['label' => 'Total Users', 'value' => '128', 'change' => '+12%', 'icon' => 'fa-users', 'tone' => 'violet'],
                ['label' => 'Active Groups', 'value' => '08', 'change' => '+2', 'icon' => 'fa-sitemap', 'tone' => 'cyan'],
                ['label' => 'Departments', 'value' => '14', 'change' => '+1', 'icon' => 'fa-building-o', 'tone' => 'orange'],
                ['label' => 'Branches', 'value' => '06', 'change' => '100%', 'icon' => 'fa-map-marker', 'tone' => 'green'],
            ],
            'activities' => [
                ['title' => 'New user account created', 'meta' => 'Admin System · 12 minutes ago', 'icon' => 'fa-user-plus', 'tone' => 'violet'],
                ['title' => 'Department data updated', 'meta' => 'HR Administrator · 48 minutes ago', 'icon' => 'fa-pencil', 'tone' => 'cyan'],
                ['title' => 'New branch registered', 'meta' => 'Super Admin · 2 hours ago', 'icon' => 'fa-building-o', 'tone' => 'orange'],
                ['title' => 'Profile settings changed', 'meta' => 'You · Yesterday', 'icon' => 'fa-cog', 'tone' => 'green'],
            ],
            'system_progress' => [
                ['label' => 'User management', 'value' => 86, 'tone' => 'violet'],
                ['label' => 'Organization setup', 'value' => 72, 'tone' => 'cyan'],
                ['label' => 'Access configuration', 'value' => 94, 'tone' => 'green'],
            ],
        ];

        $this->loadkonten('home', $data);
    }
}