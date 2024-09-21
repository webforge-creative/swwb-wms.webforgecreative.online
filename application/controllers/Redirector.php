<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Redirector extends CI_Controller
{
	public function __construct()
    {
        parent::__construct();
        $this->load->database(); // Ensure database is loaded

        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            redirect('auth/login');
        } else {
            redirect('SuperAdmin/Dashboard');
        }
    }

    public function index()
    {
        // Your code here
    }
}