<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Load necessary libraries and helpers
        $this->load->library(array('session', 'form_validation'));
        $this->load->model('User_Model');
        $this->load->library('Login_Attempts');
        $this->load->library('Remember_me');
        $this->load->library('form_validation');
        $this->remember_me->check_login();
        $this->load->helper(array('security', 'cookie', 'form'));
    }

    // Login form view
    public function login()
    {
        // If user is already logged in, redirect to dashboard
        if ($this->session->userdata('user_id')) {
            $user_role = $this->session->userdata('role');
            redirect($user_role . '/Dashboard');
        }

        $this->load->view('login_view');
    }

    // Authentication process
    public function authenticate()
    {
        // Validate CSRF token
        $csrf_token_name = $this->security->get_csrf_token_name();
        $csrf_token_value = $this->input->post($csrf_token_name);

        if (!$this->security->csrf_verify($csrf_token_value)) {
            // CSRF token verification failed, handle accordingly (e.g., redirect to an error page)
            redirect('error/403');
        }

        // Get input
        $identity = $this->security->xss_clean($this->input->post('identity', true)); // XSS clean input
        $password = $this->security->xss_clean($this->input->post('password', true));
        $remember = $this->input->post('remember');

        // Determine if identity is username or email
        if (filter_var($identity, FILTER_VALIDATE_EMAIL)) {
            // Input is an email address, attempt to find user by email
            $user = $this->User_Model->get_user_by_email($identity);
        } else {
            // Input is a username, attempt to find user by username
            $user = $this->User_Model->get_user_by_username($identity);
        }

        // Validate input
        $this->form_validation->set_rules('identity', 'Username or Email', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // Handle validation errors
            $this->session->set_flashdata('error', 'Invalid username or password.');
            redirect('auth/login');
        }

        // Check if user is locked out
        if ($this->login_attempts->is_locked_out($identity)) {
            $this->session->set_flashdata('error', 'You are temporarily locked out. Please try again later.');
            redirect('auth/login');
        }

        // Check if user exists and password is correct
        if (!$user || !password_verify($password, $user['password'])) {
            // Increment login attempts
            $this->login_attempts->increment_attempts($identity);

            // Set error message
            $this->session->set_flashdata('error', 'Invalid username or password.');
            redirect('auth/login');
        }

        // Clear previous login attempts
        $this->login_attempts->reset_attempts($identity); // Reset attempts to zero

        // Set session data
        $session_data = array(
            'user_id' => $user['id'],
            'name' => $user['name'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role'],
            'company_id' => $user['company_id'],
        );

        $this->session->set_userdata($session_data);

        // Regenerate session ID for security and bind session to user's IP address and user agent
        $this->regenerate_session_id();

        // Set Remember Me cookie if checked
        if ($remember) {
            $token = bin2hex(random_bytes(16)); // Generate a random token
            $this->User_Model->update_remember_token($user['id'], $token);
            $this->User_Model->save_remember_token($user['id'], $token);

            $cookie = array(
                'name'   => 'remember',
                'value'  => $token,
                'expire' => 604800, // Expires in 7 days (in seconds)
                'secure' => TRUE
            );
            $this->input->set_cookie($cookie);
        }

        // Redirect based on user role
        redirect($user['role'] . '/Dashboard');
    }


    // Logout user
    public function logout()
    {
        // Get user ID from session
        $user_id = $this->session->userdata('user_id');

        // Destroy session data
        $this->session->unset_userdata(array('user_id', 'username', 'role', 'ip_address', 'user_agent'));

        // Destroy session
        $this->session->sess_destroy();

        // Delete Remember Me token from database
        $this->User_Model->delete_remember_token($user_id);

        // Delete Remember Me cookie if it exists
        delete_cookie('remember');

        // Redirect to login page
        redirect('auth/login');
    }

    // Regenerate session ID and bind to IP address and user agent
    private function regenerate_session_id()
    {
        // Regenerate session ID
        $this->session->sess_regenerate(TRUE);

        // Bind session to user's IP address and user agent
        $this->session->set_userdata('ip_address', $this->input->ip_address());
        $this->session->set_userdata('user_agent', $this->input->user_agent());
    }
}
