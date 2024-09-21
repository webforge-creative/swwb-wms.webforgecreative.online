<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setup extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->require_login();
        $this->check_access_control('Admin');
        $this->load->library('grocery_CRUD');
        $this->load->model('Setup_model');
    }

    public function manage_user()
    {
        $data['page_title'] = 'Users Management | ' . $this->config->item('site_name');
        $data['Heading'] = 'Users';
        $data['subject'] = 'Users';

        $crud = new grocery_CRUD();

        $crud->unset_jquery();
        $crud->set_table('users');
        $crud->set_subject('Users');

        $crud->unset_clone();

        $crud->display_as('name', 'Name');
        $crud->display_as('username', 'Username');
        $crud->display_as('email', 'Email');
        $crud->display_as('password', 'Password');
        $crud->display_as('role', 'Role');
        $crud->display_as('remember_token', 'Remember Token');
        $crud->display_as('created_at', 'Created At');

        $crud->fields('name', 'username', 'email', 'password', 'role');

        $crud->required_fields('name', 'username', 'email', 'password', 'role');

        // Set the password field type
        $crud->field_type('password', 'password');

        $crud->unset_columns('remember_token', 'created_at');

        // Callback to encrypt password before insert and update
        $crud->callback_before_insert(array($this, 'encrypt_password'));
        $crud->callback_before_update(array($this, 'encrypt_password'));

        // Callback to clear the password field when editing
        $crud->callback_edit_field('password', array($this, 'clear_password_field'));

        $data['crud'] = $crud->render();

        $content['index'] = $this->load->view('Admin/crud', $data, true);
        $this->load->view('_template/Admin', $content);
    }

    public function encrypt_password($post_array)
    {
        if (!empty($post_array['password'])) {
            $post_array['password'] = password_hash($post_array['password'], PASSWORD_BCRYPT);
        } else {
            unset($post_array['password']);
        }
        return $post_array;
    }

    public function clear_password_field($value, $primary_key)
    {
        return '<input id="field-password" name="password" type="password" value="" class="form-control">';
    }

    public function manage_regions()
    {
        $data['page_title'] = 'Regions Management | ' . $this->config->item('site_name');
        $data['Heading'] = 'Regions';
        $data['subject'] = 'Regions';

        $crud = new grocery_CRUD();

        $crud->unset_jquery();
        $crud->set_table('regions');
        $crud->set_subject('Region');

        $crud->unset_clone();

        $crud->display_as('region_name', 'Region Name');
        $crud->display_as('created_at', 'Created At');
        $crud->display_as('updated_at', 'Updated At');

        $crud->fields('region_name');
        $crud->required_fields('region_name');

        // Optional: Set field types if needed
        $crud->field_type('region_name', 'string');

        $data['crud'] = $crud->render();

        $content['index'] = $this->load->view('Admin/crud', $data, true);
        $this->load->view('_template/Admin', $content);
    }
}
