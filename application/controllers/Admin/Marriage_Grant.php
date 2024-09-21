<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Marriage_Grant extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->require_login();
        $this->check_access_control('Admin');
        $this->load->library('grocery_CRUD');
    }

    public function all_mg_list()
    {
        $data['page_title'] = 'Marriage Grant Management | ' . $this->config->item('site_name');
        $data['Heading'] = 'Marriage Grant';
        $data['subject'] = 'Marriage Grant';

        $crud = new grocery_CRUD();

        $crud->unset_jquery();
        $crud->set_table('marriage_grants');
        $crud->set_subject('Marriage Grant');

        $crud->unset_clone();
        $crud->unset_columns('created_at', 'updated_at');

        $crud->display_as('region_id', 'Region');
        $crud->display_as('worker_name', 'Worker Name');
        $crud->display_as('worker_father_name', 'Father\'s Name');
        $crud->display_as('worker_cnic', 'Worker CNIC');
        $crud->display_as('worker_dob', 'Worker DOB');
        $crud->display_as('eobi_no', 'EOBI No');
        $crud->display_as('sessi_no', 'SESSI No');
        $crud->display_as('daughter_name', 'Daughter\'s Name');
        $crud->display_as('daughter_cnic', 'Daughter\'s CNIC');
        $crud->display_as('daughter_dob', 'Daughter\'s DOB');
        $crud->display_as('factory_name', 'Factory Name');
        $crud->display_as('receiving_date', 'Receiving Date');
        $crud->display_as('nikkah_date', 'Nikkah Date');
        $crud->display_as('worker_account_no', 'Worker Account No');
        $crud->display_as('branch_code', 'Branch Code');
        $crud->display_as('worker_bank', 'Worker Bank');

        $crud->fields('region_id', 'worker_name', 'worker_father_name', 'worker_cnic', 'worker_dob', 'eobi_no', 'sessi_no', 'daughter_name', 'daughter_cnic', 'daughter_dob', 'factory_name', 'receiving_date', 'nikkah_date', 'worker_account_no', 'branch_code', 'worker_bank');

        $crud->required_fields('region_id', 'worker_name', 'worker_cnic', 'worker_dob', 'daughter_name', 'daughter_cnic', 'factory_name', 'receiving_date', 'nikkah_date', 'worker_account_no', 'branch_code', 'worker_bank');

        $crud->field_type('receiving_date', 'date');
        $crud->field_type('nikkah_date', 'date');
        $crud->field_type('daughter_dob', 'date');
        $crud->field_type('worker_dob', 'date');

        $crud->set_relation('region_id', 'regions', 'region_name');

        $record_id = $this->uri->segment(5);

        if ($record_id) {
            $crud->set_rules('daughter_cnic', 'Daughter\'s CNIC', 'required|callback_check_unique_cnic[' . $record_id . ']');
        } else {
            $crud->set_rules('daughter_cnic', 'Daughter\'s CNIC', 'required|callback_check_unique_cnic');
        }
        $crud->set_rules('daughter_dob', 'Daughter\'s DOB', 'callback_validate_daughter_age');
        $crud->set_rules('worker_dob', 'Worker DOB', 'callback_validate_worker_age');
        $crud->set_rules('nikkah_date', 'Nikkah Date', 'callback_validate_nikkah_receiving_date');
        $crud->set_rules('sessi_no', 'SESSI No', 'callback_validate_sessi_eobi');
        $crud->set_rules('eobi_no', 'EOBI No', 'callback_validate_sessi_eobi');

        $crud->callback_before_insert(array($this, 'format_cnic'));
        $crud->callback_before_update(array($this, 'format_cnic'));

        $crud->callback_column('worker_dob', function ($value) {
            if ($value == '1970-01-01') {
                return $value;
            }
            return $value;
        });

        $data['crud'] = $crud->render();

        $content['index'] = $this->load->view('Admin/crud', $data, true);
        $this->load->view('_template/Admin', $content);
    }

    // Old Cnic Check Method

    // public function check_unique_cnic($daughter_cnic, $record_id)
    // {
    //     $this->db->where('daughter_cnic', $daughter_cnic);
    //     $this->db->where('grant_id !=', $record_id); // Exclude the current record ID
    //     $query = $this->db->get('marriage_grants');

    //     if ($query->num_rows() > 0) {
    //         $this->form_validation->set_message('check_unique_cnic', 'The CNIC is already in use by another record.');
    //         return false;
    //     }

    //     return true;
    // }

    // New Cnic Check Method

    public function check_unique_cnic($daughter_cnic, $record_id)
    {
        // Check in marriage_grants table
        $this->db->where('daughter_cnic', $daughter_cnic);
        if ($record_id) {
            $this->db->where('grant_id !=', $record_id); // Exclude the current record ID
        }
        $query1 = $this->db->get('marriage_grants');

        // Check in worker_daughter_info table
        $this->db->where('daughter_cnic', $daughter_cnic);
        $query2 = $this->db->get('worker_daughter_info');

        if ($query1->num_rows() > 0 || $query2->num_rows() > 0) {
            $this->form_validation->set_message('check_unique_cnic', 'The CNIC is already in use by another record.');
            return false;
        }

        return true;
    }

    public function check_daughter_cnic_unique()
    {
        $daughter_cnic = $this->input->post('daughter_cnic');
        $response = array('is_unique' => true);

        // Check in marriage_grants
        $this->db->where('daughter_cnic', $daughter_cnic);
        $query1 = $this->db->get('marriage_grants');

        // Check in worker_daughter_info
        $this->db->where('daughter_cnic', $daughter_cnic);
        $query2 = $this->db->get('worker_daughter_info');

        if ($query1->num_rows() > 0 || $query2->num_rows() > 0) {
            $response['is_unique'] = false;
        }

        echo json_encode($response);
    }

    // Validation function for SESSI/EOBI
    public function validate_sessi_eobi($value)
    {
        $sessi_no = $this->input->post('sessi_no');
        $eobi_no = $this->input->post('eobi_no');

        if (empty($sessi_no) && empty($eobi_no)) {
            $this->form_validation->set_message('validate_sessi_eobi', 'Either SESSI or EOBI number must be provided.');
            return false;
        }

        return true;
    }

    // Validation function for daughter's age
    public function validate_daughter_age($daughter_dob)
    {
        $daughter_dob = DateTime::createFromFormat('d/m/Y', $daughter_dob);
        $current_date = new DateTime();
        $daughter_age = $current_date->diff($daughter_dob)->y;

        if ($daughter_age < 18) {
            $this->form_validation->set_message('validate_daughter_age', 'Daughter must be at least 18 years old.');
            return false;
        }

        return true;
    }

    // Validation function for worker's age and father-daughter age difference
    public function validate_worker_age($worker_dob)
    {
        $worker_dob = DateTime::createFromFormat('d/m/Y', $worker_dob);
        $current_date = new DateTime();
        $worker_age = $current_date->diff($worker_dob)->y;

        if ($worker_age > 60) {
            $this->form_validation->set_message('validate_worker_age', 'Worker\'s age must be below 60 years.');
            return false;
        }

        if ($this->input->post('daughter_dob')) {
            $daughter_dob = DateTime::createFromFormat('d/m/Y', $this->input->post('daughter_dob'));
            $age_diff = $worker_dob->diff($daughter_dob)->y;

            if ($age_diff < 18) {
                $this->form_validation->set_message('validate_worker_age', 'Worker must be at least 18 years older than daughter.');
                return false;
            }
        }

        return true;
    }

    // Validation function for Nikkah and receiving dates
    public function validate_nikkah_receiving_date($nikkah_date)
    {
        $nikkah_date = DateTime::createFromFormat('d/m/Y', $nikkah_date);
        $receiving_date = DateTime::createFromFormat('d/m/Y', $this->input->post('receiving_date'));

        if ($nikkah_date > $receiving_date) {
            $this->form_validation->set_message('validate_nikkah_receiving_date', 'Receiving date cannot be before the Nikkah date.');
            return false;
        }

        $diff = $nikkah_date->diff($receiving_date)->y;
        if ($diff > 1) {
            $this->form_validation->set_message('validate_nikkah_receiving_date', 'The receiving date must be within 1 year of the Nikkah date.');
            return false;
        }

        return true;
    }

    // Formatting CNIC
    public function format_cnic($post_array)
    {
        if (!empty($post_array['worker_cnic'])) {
            $post_array['worker_cnic'] = preg_replace('/[^0-9]/', '', $post_array['worker_cnic']);
            if (strlen($post_array['worker_cnic']) > 13) {
                $post_array['worker_cnic'] = substr($post_array['worker_cnic'], 0, 13);
            }
            $post_array['worker_cnic'] = substr($post_array['worker_cnic'], 0, 5) . '-' . substr($post_array['worker_cnic'], 5, 7) . '-' . substr($post_array['worker_cnic'], 12);
        }

        if (!empty($post_array['daughter_cnic'])) {
            $post_array['daughter_cnic'] = preg_replace('/[^0-9]/', '', $post_array['daughter_cnic']);
            if (strlen($post_array['daughter_cnic']) > 13) {
                $post_array['daughter_cnic'] = substr($post_array['daughter_cnic'], 0, 13);
            }
            $post_array['daughter_cnic'] = substr($post_array['daughter_cnic'], 0, 5) . '-' . substr($post_array['daughter_cnic'], 5, 7) . '-' . substr($post_array['daughter_cnic'], 12);
        }

        return $post_array;
    }
}
