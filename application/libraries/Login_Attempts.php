<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login_Attempts
{
    protected $CI;
    protected $lockout_threshold = 5; // Max attempts before lockout
    protected $lockout_duration = 3600; // Lockout duration in seconds (1 hour)

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->database();
    }

    // Get login attempts
    public function get_attempts($username)
    {
        $this->clear_old_attempts($username); // Clear old attempts before fetching
        $query = $this->CI->db->get_where('login_attempts', array('username' => $username));
        $result = $query->row();
        return $result ? $result->attempts : 0;
    }

    // Increment login attempts
    public function increment_attempts($username)
    {
        // Check if the username exists in the users table
        $user = $this->CI->db->get_where('users', array('username' => $username))->row();
        if (!$user) {
            // If the username doesn't exist, handle the error appropriately
            return; // Or you can throw an exception, log the error, etc.
        }

        // Get current attempts
        $attempts = $this->get_attempts($username);

        if ($attempts >= $this->lockout_threshold) {
            // If attempts reach threshold, lock the user out
            $this->lockout_user($username);
            return;
        }

        // Update existing attempt if user already has an entry
        $this->CI->db->where('username', $username);
        $this->CI->db->set('attempts', 'attempts+1', FALSE);
        $this->CI->db->set('last_attempt', date('Y-m-d H:i:s'));
        $this->CI->db->update('login_attempts');

        if ($this->CI->db->affected_rows() == 0) {
            // Insert new attempt if no existing attempt
            $data = array('username' => $username, 'attempts' => 1, 'last_attempt' => date('Y-m-d H:i:s'));
            $this->CI->db->insert('login_attempts', $data);
        }
    }

    // Clear old attempts older than 24 hours
    public function clear_old_attempts($username)
    {
        $twentyFourHoursAgo = date('Y-m-d H:i:s', strtotime('-24 hours'));
        $this->CI->db->where('username', $username);
        $this->CI->db->where('last_attempt <', $twentyFourHoursAgo);
        $this->CI->db->delete('login_attempts');
    }

    // Lock user out for one hour
    protected function lockout_user($username)
    {
        $data = array(
            'attempts' => $this->lockout_threshold + 1, // So that it exceeds the threshold
            'last_attempt' => date('Y-m-d H:i:s', strtotime('+1 hour'))
        );
        $this->CI->db->where('username', $username);
        $this->CI->db->update('login_attempts', $data);
    }

    // Check if user is locked out
    public function is_locked_out($username)
    {
        $query = $this->CI->db->get_where('login_attempts', array('username' => $username));
        $result = $query->row();
        if ($result && $result->attempts >= $this->lockout_threshold && strtotime($result->last_attempt) > time()) {
            return true; // User is locked out
        }
        return false; // User is not locked out
    }

    // Reset attempts to zero on successful login
    public function reset_attempts($username)
    {
        $data = array('attempts' => 0);
        $this->CI->db->where('username', $username);
        $this->CI->db->update('login_attempts', $data);
    }
}
