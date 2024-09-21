<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->require_login();
        $this->check_access_control('Admin');
        $this->load->library('grocery_CRUD');
    }

    public function index()
    {
        $data['page_title'] = $this->config->item('site_name');
        $data['Heading'] = 'Dashboard';

        // Load views
        $content['Head'] = $this->load->view('_global/Admin/Head', $data, true);
        $content['Header'] = $this->load->view('_global/Admin/Header', $data, true);
        $content['Navigation'] = $this->load->view('_global/Admin/Navigation', $data, true);
        $content['Footer'] = $this->load->view('_global/Admin/Footer', $data, true);
        $content['index'] = $this->load->view('Admin/index', $data, true);
        $this->load->view('_template/Admin', $content);
    }
}
