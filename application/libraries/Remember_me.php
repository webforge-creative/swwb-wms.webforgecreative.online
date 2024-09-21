<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Remember_me
{

    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('User_Model');
    }

    public function check_login()
    {
        if (!$this->CI->session->userdata('user_id') && $this->CI->input->cookie('remember')) {
            $token = $this->CI->input->cookie('remember');
            // Add this line to debug
            echo "Remember Me Token: $token <br>";

            $user = $this->CI->User_Model->get_user_by_remember_token($token);

            if ($user) {
                // Add this line to debug
                echo "User Found: " . print_r($user, true) . "<br>";

                // Check if the token exists in the remember_tokens table
                if ($this->CI->User_Model->is_remember_token_exist($token)) {
                    $this->CI->session->set_userdata(array(
                        'user_id' => $user['id'],
                        'username' => $user['username'],
                        'role' => $user['role']
                    ));
                    // Add this line to debug
                    echo "User logged in with Remember Me <br>";
                } else {
                    // Token is not valid, remove it from the user's record
                    $this->CI->User_Model->update_remember_token($user['id'], null);
                    delete_cookie('remember');
                    // Add this line to debug
                    echo "Remember Me Token expired or not valid <br>";
                }
            } else {
                // Add this line to debug
                echo "User not found <br>";
            }
        }
    }
}