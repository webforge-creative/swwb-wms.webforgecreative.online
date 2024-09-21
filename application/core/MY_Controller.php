<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('grocery_CRUD');
        $this->load->helper(array('security', 'cookie'));
        $this->load->model('User_Model');
        $this->cleanup_sessions();
    }

    // Clean up expired sessions
    private function cleanup_sessions()
    {
        // Define the expiration time for sessions (e.g., 6 hours)
        $expiration_time = time() - (6 * 60 * 60); // 6 hours ago

        // Delete expired sessions from the database
        $this->db->where('timestamp <', $expiration_time);
        $this->db->delete('ci_sessions');
    }

    // Function to check access control for portals
    protected function check_access_control($required_role)
    {
        // Check if user is logged in
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }

        // Get user's role from session
        $user_role = $this->session->userdata('role');

        // Check if user's role matches the required role
        if ($user_role != $required_role) {
            // Load the unauthorized access view
            $this->show_unauthorized_access();
            exit; // Stop further execution
        }
    }

    // Function to display unauthorized access view
    protected function show_unauthorized_access()
    {
        include(APPPATH . 'views/error/unauthorized_access.php');
    }

    protected function require_login()
    {
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
    }

    protected function load_view($view, $data = [])
    {
        // $this->load->view('templates/header');
        $this->load->view($view, $data);
        // $this->load->view('templates/footer');/
    }
}
