<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Check if user exists by username
    public function is_user_exists($username)
    {
        $query = $this->db->get_where('users', array('username' => $username));
        return $query->num_rows() > 0;
    }

    // Get user details by username
    public function get_user_by_username($identity)
    {
        $query = $this->db->get_where('users', array('username' => $identity));
        return $query->row_array();
    }

    // Get user details by email
    public function get_user_by_email($identity)
    {
        $query = $this->db->get_where('users', array('email' => $identity));
        return $query->row_array();
    }

    // Get user by remember token
    public function get_user_by_remember_token($token)
    {
        $query = $this->db->get_where('users', array('remember_token' => $token));
        return $query->row_array();
    }

    // Update remember token for user
    public function update_remember_token($user_id, $token)
    {
        $this->db->set('remember_token', $token);
        $this->db->where('id', $user_id);
        $this->db->update('users');
    }

    // Save remember me token
    public function save_remember_token($user_id, $token)
    {
        $data = array(
            'user_id' => $user_id,
            'token' => $token
        );
        $this->db->insert('remember_tokens', $data);
    }

    // Delete remember me token
    public function delete_remember_token($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->delete('remember_tokens');
    }

    // Check if a remember me token exists
    public function is_remember_token_exist($token)
    {
        $query = $this->db->get_where('remember_tokens', array('token' => $token));
        return $query->num_rows() > 0;
    }

    // Finance Models

    public function get_dashboard_data()
    {
        $data = array();

        // Get total income
        $this->db->select_sum('amount');
        $this->db->where('transaction_type', 'income');
        $query = $this->db->get('transactions');
        $data['total_income'] = $query->row()->amount;

        // Get total expense
        $this->db->select_sum('amount');
        $this->db->where('transaction_type', 'expense');
        $query = $this->db->get('transactions');
        $data['total_expense'] = $query->row()->amount;

        // Calculate profit
        $data['profit'] = $data['total_income'] - $data['total_expense'];

        return $data;
    }

    public function get_income_chart_data()
    {
        $this->db->select('MONTH(created_at) as month, SUM(amount) as total_income');
        $this->db->where('transaction_type', 'income');
        $this->db->group_by('MONTH(created_at)');
        $this->db->order_by('MONTH(created_at)');
        $query = $this->db->get('transactions');
        return $query->result_array();
    }

    public function get_expense_chart_data()
    {
        $this->db->select('MONTH(created_at) as month, SUM(amount) as total_expense');
        $this->db->where('transaction_type', 'expense');
        $this->db->group_by('MONTH(created_at)');
        $this->db->order_by('MONTH(created_at)');
        $query = $this->db->get('transactions');
        return $query->result_array();
    }

    public function get_latest_transactions()
    {
        $this->db->select('id, DATE_FORMAT(created_at, "%Y-%m-%d") as date, description, transaction_type as type, amount');
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit(5);
        $query = $this->db->get('transactions');
        return $query->result_array();
    }

    public function get_accounts_by_company($company_id)
    {
        $this->db->select('id, account_name');
        $this->db->from('accounts');
        $this->db->where('company_id', $company_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_heads_by_type($transaction_type)
    {
        $this->db->select('id, head_name');
        $this->db->from('transaction_heads');
        $this->db->where('head_type', $transaction_type);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_report_data($report_type, $from_date, $to_date)
    {
        $this->db->select('transactions.*, accounts.account_name');
        $this->db->from('transactions');
        $this->db->join('accounts', 'transactions.account_id = accounts.id');

        // Check if from_date and to_date are not empty
        if (!empty($from_date) && !empty($to_date)) {
            // Ensure from_date is less than or equal to to_date
            if ($from_date > $to_date) {
                // Swap the dates if they are in the wrong order
                list($from_date, $to_date) = array($to_date, $from_date);
            }

            // Add where clause to fetch records between from_date and to_date
            $this->db->where('transactions.created_at >=', $from_date);
            $this->db->where('transactions.created_at <=', $to_date);
        }

        // Check if report_type is not 'all'
        if ($report_type !== 'all') {
            // Add where clause to fetch records based on transaction_type
            $this->db->where('transactions.transaction_type', $report_type);
        }

        $this->db->order_by('transactions.created_at', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    // TODO List
    public function getTasks()
    {
        $query = $this->db->get('todo_tasks');
        return $query->result_array();
    }
    public function addTask($data)
    {
        $this->db->insert('todo_tasks', $data);
        return $this->db->insert_id();
    }
    public function updateTask($id, $title, $description, $priority, $due_date, $status)
    {
        $data = array(
            'title' => $title,
            'description' => $description,
            'priority' => $priority,
            'due_date' => $due_date,
            'status' => $status
        );
        $this->db->where('id', $id);
        return $this->db->update('todo_tasks', $data);
    }
    public function deleteTask($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('todo_tasks');
    }
    public function updateTaskStatus($task_id, $status)
    {
        $data = array(
            'status' => $status
        );

        $this->db->where('id', $task_id);
        $this->db->update('todo_tasks', $data);
        return $this->db->affected_rows() > 0;
    }

    // Inventory Model Methods

    public function get_items_by_category($category_id)
    {
        $this->db->where('category_id', $category_id);
        return $this->db->get('inventory_items')->result_array();
    }
    public function update_status_returned($id)
    {
        $data = array('status' => 'Returned');
        $this->db->where('id', $id);
        return $this->db->update('inventory_issue_items', $data);
    }

    // Patient Method
    public function get_patient_details($patient_id)
    {
        $this->db->select('patients.*, guardians.first_name AS guardian_first_name, guardians.last_name AS guardian_last_name, blood_types.blood_type');
        $this->db->from('patients');
        $this->db->join('guardians', 'patients.guardian_id = guardians.guardian_id', 'left');
        $this->db->join('blood_types', 'patients.blood_type_id = blood_types.blood_type_id', 'left');
        $this->db->where('patients.patient_id', $patient_id);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    // Staff Methods

    // Method to fetch all staff data
    public function get_all_staff()
    {
        $this->db->select('staff.*, users.*');
        $this->db->from('staff');
        $this->db->join('users', 'users.id = staff.user_id', 'left');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_staff_info($staff_id)
    {
        $this->db->select('staff.*, users.*');
        $this->db->from('staff');
        $this->db->join('users', 'users.id = staff.user_id', 'left');
        $this->db->join('blood_types', 'blood_types.blood_type_id = staff.blood_group', 'left');
        $this->db->where('staff_id', $staff_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    }
    public function get_users_without_staff_id()
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id NOT IN (SELECT user_id FROM staff)', NULL, FALSE);
        $query = $this->db->get();

        return $query->result();
    }
    public function get_all_blood()
    {
        $query = $this->db->get('blood_types');
        return $query->result();
    }
    public function get_all_users()
    {
        $query = $this->db->get('users');
        return $query->result();
    }
    public function add_staff($data)
    {
        return $this->db->insert('staff', $data);
    }
    public function update_staff($staff_id, $data)
    {
        $this->db->where('staff_id', $staff_id);
        $this->db->update('staff', $data);

        return $this->db->affected_rows() > 0;
    }

    // public function generate_payroll()
    // {
    //     // Get all employees
    //     $this->db->select('e.id, e.name, e.salary, e.company_id, e.account_id, c.company_name, a.account_name');
    //     $this->db->from('employees e');
    //     $this->db->join('companies c', 'e.company_id = c.id');
    //     $this->db->join('accounts a', 'e.account_id = a.id');
    //     $query = $this->db->get();
    //     $employees = $query->result_array();

    //     // Initialize payroll data array
    //     $payroll_data = [];

    //     // Current date and month
    //     $current_date = date('Y-m-d');
    //     $current_month = date('F Y');

    //     // Initialize an array to store total salary expenses for each company
    //     $company_salaries = [];

    //     foreach ($employees as $employee) {
    //         // Calculate salary details
    //         $attendance = 22; // Example value
    //         $leaves = 4; // Example value
    //         $fine = 50; // Example value
    //         $allowed_leave = 2; // Example value
    //         $overtime_hours = 10; // Example value
    //         $overtime_amount = $overtime_hours * 20; // Example value: $20/hour
    //         $late_fine = 30; // Example value
    //         $salary_advance = 500; // Example value
    //         $gross_salary = $employee['salary'];
    //         $net_salary = $gross_salary - ($leaves * 20) + $overtime_amount - $fine - $late_fine - $salary_advance; // Example calculation

    //         // Generate payroll record
    //         $payroll_record = [
    //             'employee_id' => $employee['id'],
    //             'company_id' => $employee['company_id'],
    //             'account_id' => $employee['account_id'],
    //             'transaction_type' => 'expense',
    //             'head_id' => 9, // Get head ID for Salaries and Wages
    //             'date' => $current_date,
    //             'attendance' => $attendance,
    //             'leaves' => $leaves,
    //             'fine' => $fine,
    //             'allowed_leave' => $allowed_leave,
    //             'overtime_hours' => $overtime_hours,
    //             'overtime_amount' => $overtime_amount,
    //             'late_fine' => $late_fine,
    //             'salary_advance' => $salary_advance,
    //             'gross_salary' => $gross_salary,
    //             'net_salary' => $net_salary,
    //             'invoice_number' => '',
    //             'description' => 'Monthly salary for ' . $current_month,
    //             'attachment' => null
    //         ];

    //         // Add net salary to the total salary expenses for the company
    //         if (!isset($company_salaries[$employee['company_id']])) {
    //             $company_salaries[$employee['company_id']] = 0;
    //         }
    //         $company_salaries[$employee['company_id']] += $net_salary;

    //         // Insert payroll record into payroll table
    //         $this->db->insert('payroll', $payroll_record);
    //         $primary_key = $this->db->insert_id();

    //         // Generate invoice number with 'P' prefix for payroll records
    //         $invoice_number = 'P-INV-' . str_pad($primary_key, 6, '0', STR_PAD_LEFT);

    //         // Update record with the generated invoice number
    //         $this->db->where('id', $primary_key);
    //         $this->db->update('payroll', array('invoice_number' => $invoice_number));

    //         // Add invoice number to payroll record
    //         $payroll_record['invoice_number'] = $invoice_number;

    //         // Add record to payroll data array
    //         $payroll_data[] = $payroll_record;

    //         // Insert transaction record into transaction table
    //         $transaction_record = [
    //             'company_id' => $employee['company_id'],
    //             'account_id' => 5, // Assuming account ID for salaries and wages
    //             'transaction_type' => 'expense',
    //             'head_id' => 9, // Get head ID for Salaries and Wages
    //             'date' => $current_date,
    //             'amount' => $net_salary,
    //             'description' => 'Monthly salary expenses for ' . $current_month,
    //             'invoice_number' => $invoice_number, // Use the same invoice number as payroll
    //             'attachment' => null
    //         ];

    //         // Insert transaction record into transaction table
    //         $this->db->insert('transactions', $transaction_record);
    //     }

    //     return $payroll_data;
    // }

    public function generate_payroll()
    {
        // Get all employees
        $this->db->select('e.id, e.name, e.salary, e.company_id, e.account_id, c.company_name, a.account_name');
        $this->db->from('employees e');
        $this->db->join('companies c', 'e.company_id = c.id');
        $this->db->join('accounts a', 'e.account_id = a.id');
        $query = $this->db->get();
        $employees = $query->result_array();

        // Initialize payroll data array
        $payroll_data = [];

        // Current date and month
        $current_date = date('Y-m-d');
        $current_month = date('F Y');

        // Initialize an array to store total salary expenses for each company
        $company_salaries = [];

        foreach ($employees as $employee) {
            $employee_id = $employee['id'];

            // Fetch attendance details
            $this->db->select('COUNT(id) as attendance_count');
            $this->db->from('employee_attendance');
            $this->db->where('employee_id', $employee_id);
            $this->db->where('status', 'Present');
            $this->db->where('MONTH(attendance_date)', date('m'));
            $this->db->where('YEAR(attendance_date)', date('Y'));
            $attendance_query = $this->db->get();
            $attendance = $attendance_query->row()->attendance_count;

            // Fetch leave details
            $this->db->select('COUNT(id) as leave_count');
            $this->db->from('employee_leaves');
            $this->db->where('employee_id', $employee_id);
            $this->db->where('leave_status', 'Approved');
            $this->db->where('MONTH(leave_date)', date('m'));
            $this->db->where('YEAR(leave_date)', date('Y'));
            $leave_query = $this->db->get();
            $leaves = $leave_query->row()->leave_count;

            // Fetch fine details
            $this->db->select('SUM(amount) as total_fine');
            $this->db->from('employee_fines');
            $this->db->where('employee_id', $employee_id);
            $this->db->where('MONTH(fine_date)', date('m'));
            $this->db->where('YEAR(fine_date)', date('Y'));
            $fine_query = $this->db->get();
            $fine = $fine_query->row()->total_fine;

            // Fetch overtime details
            $this->db->select('SUM(hours) as total_overtime_hours, SUM(hours * rate) as total_overtime_amount');
            $this->db->from('employee_overtime');
            $this->db->where('employee_id', $employee_id);
            $this->db->where('MONTH(overtime_date)', date('m'));
            $this->db->where('YEAR(overtime_date)', date('Y'));
            $overtime_query = $this->db->get();
            $overtime_hours = $overtime_query->row()->total_overtime_hours;
            $overtime_amount = $overtime_query->row()->total_overtime_amount;

            // Fetch late fine details (Assuming similar to regular fine for simplicity)
            $this->db->select('SUM(amount) as total_late_fine');
            $this->db->from('employee_fines');
            $this->db->where('employee_id', $employee_id);
            $this->db->where('reason', 'Late');
            $this->db->where('MONTH(fine_date)', date('m'));
            $this->db->where('YEAR(fine_date)', date('Y'));
            $late_fine_query = $this->db->get();
            $late_fine = $late_fine_query->row()->total_late_fine;

            // Fetch salary advance details
            $this->db->select('SUM(amount) as total_salary_advance');
            $this->db->from('employee_salary_advances');
            $this->db->where('employee_id', $employee_id);
            $this->db->where('MONTH(advance_date)', date('m'));
            $this->db->where('YEAR(advance_date)', date('Y'));
            $salary_advance_query = $this->db->get();
            $salary_advance = $salary_advance_query->row()->total_salary_advance;

            // Calculate gross and net salary
            $gross_salary = $employee['salary'];
            $net_salary = $gross_salary - ($leaves * 20) + $overtime_amount - $fine - $late_fine - $salary_advance; // Example calculation

            // Generate payroll record
            $payroll_record = [
                'employee_id' => $employee_id,
                'company_id' => $employee['company_id'],
                'account_id' => $employee['account_id'],
                'transaction_type' => 'expense',
                'head_id' => 9, // Get head ID for Salaries and Wages
                'date' => $current_date,
                'attendance' => $attendance,
                'leaves' => $leaves,
                'fine' => $fine,
                'allowed_leave' => 2, // Example value
                'overtime_hours' => $overtime_hours,
                'overtime_amount' => $overtime_amount,
                'late_fine' => $late_fine,
                'salary_advance' => $salary_advance,
                'gross_salary' => $gross_salary,
                'net_salary' => $net_salary,
                'invoice_number' => '',
                'description' => 'Monthly salary for ' . $current_month,
                'attachment' => null
            ];

            // Add net salary to the total salary expenses for the company
            if (!isset($company_salaries[$employee['company_id']])) {
                $company_salaries[$employee['company_id']] = 0;
            }
            $company_salaries[$employee['company_id']] += $net_salary;

            // Insert payroll record into payroll table
            $this->db->insert('payroll', $payroll_record);
            $primary_key = $this->db->insert_id();

            // Generate invoice number with 'P' prefix for payroll records
            $invoice_number = 'P-INV-' . str_pad($primary_key, 6, '0', STR_PAD_LEFT);

            // Update record with the generated invoice number
            $this->db->where('id', $primary_key);
            $this->db->update('payroll', array('invoice_number' => $invoice_number));

            // Add invoice number to payroll record
            $payroll_record['invoice_number'] = $invoice_number;

            // Add record to payroll data array
            $payroll_data[] = $payroll_record;

            // Insert transaction record into transaction table
            $transaction_record = [
                'company_id' => $employee['company_id'],
                'account_id' => 5, // Assuming account ID for salaries and wages
                'transaction_type' => 'expense',
                'head_id' => 9, // Get head ID for Salaries and Wages
                'date' => $current_date,
                'amount' => $net_salary,
                'description' => 'Monthly salary expenses for ' . $current_month,
                'invoice_number' => $invoice_number, // Use the same invoice number as payroll
                'attachment' => null
            ];

            // Insert transaction record into transaction table
            $this->db->insert('transactions', $transaction_record);
        }

        return $payroll_data;
    }

    public function get_payroll_data($payroll_id)
    {
        $this->db->select('payroll.*, employees.*, accounts.*, companies.*');
        $this->db->from('payroll');
        $this->db->join('employees', 'payroll.employee_id = employees.id', 'left');
        $this->db->join('companies', 'payroll.company_id = companies.id', 'left');
        $this->db->join('accounts', 'payroll.account_id = accounts.id', 'left');
        $this->db->where('payroll.id', $payroll_id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_all_payroll_by_employee($employee_id)
    {
        $this->db->select('payroll.*, employees.*, accounts.*, companies.*');
        $this->db->from('payroll');
        $this->db->join('employees', 'payroll.employee_id = employees.id', 'left');
        $this->db->join('companies', 'payroll.company_id = companies.id', 'left');
        $this->db->join('accounts', 'payroll.account_id = accounts.id', 'left');
        $this->db->where('payroll.employee_id', $employee_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_employee($employee_id)
    {
        $this->db->select('employees.*, accounts.*, companies.*');
        $this->db->from('employees');
        $this->db->join('companies', 'employees.company_id = companies.id', 'left');
        $this->db->join('accounts', 'employees.account_id = accounts.id', 'left');
        $this->db->where('employees.id', $employee_id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_account_types()
    {
        $this->db->distinct();
        $this->db->select('account_type');
        $query = $this->db->get('accounts');
        return $query->result_array();
    }

    public function get_report_data_by_account_type($account_type)
    {
        $this->db->select('transactions.*, accounts.account_name');
        $this->db->from('transactions');
        $this->db->join('accounts', 'transactions.account_id = accounts.id');
        $this->db->where('accounts.account_type', $account_type);
        $this->db->order_by('transactions.created_at', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function update_payroll_status($payroll_id, $status)
    {
        $data = array(
            'status' => $status
        );

        $this->db->where('id', $payroll_id);
        $this->db->update('payroll', $data);

        return $this->db->affected_rows() > 0;
    }

    public function insert_transaction($payroll_data)
    {
        $transaction_data = array(
            'company_id' => $payroll_data['company_id'],
            'account_id' => $payroll_data['account_id'],
            'transaction_type' => $payroll_data['transaction_type'],
            'head_id' => $payroll_data['head_id'],
            'invoice_number' => $payroll_data['invoice_number'],
            'date' => date('Y-m-d H:i:s'),
            'description' => 'Payroll for employee ID ' . $payroll_data['employee_id'],
            'amount' => $payroll_data['net_salary'], // Assuming net salary is the transaction amount
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        $this->db->insert('transactions', $transaction_data);

        return $this->db->insert_id(); // Return the ID of the inserted transaction
    }
}
