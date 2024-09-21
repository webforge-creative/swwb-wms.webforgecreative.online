<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Errors extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function show_404()
    {
        $this->load->view('error/404');
    }
}
