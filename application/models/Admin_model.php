<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 */
class Admin_model extends CI_Model
{

	#region //! MISC

	public function get_dashboard_chart()
	{
		$query = $query = $this->db->query("SELECT
			COUNT(CASE WHEN MONTH(created_on) = 1 THEN id ELSE null END) AS Total_Jan,
			COUNT(CASE WHEN MONTH(created_on) = 2 THEN id ELSE null END) AS Total_Feb,
			COUNT(CASE WHEN MONTH(created_on) = 3 THEN id ELSE null END) AS Total_Mar,
			COUNT(CASE WHEN MONTH(created_on) = 4 THEN id ELSE null END) AS Total_Apr,
			COUNT(CASE WHEN MONTH(created_on) = 5 THEN id ELSE null END) AS Total_May,
			COUNT(CASE WHEN MONTH(created_on) = 6 THEN id ELSE null END) AS Total_Jun,
			COUNT(CASE WHEN MONTH(created_on) = 7 THEN id ELSE null END) AS Total_Jul,
			COUNT(CASE WHEN MONTH(created_on) = 8 THEN id ELSE null END) AS Total_Aug,
			COUNT(CASE WHEN MONTH(created_on) = 9 THEN id ELSE null END) AS Total_Sep,
			COUNT(CASE WHEN MONTH(created_on) = 10 THEN id ELSE null END) AS Total_Oct,
			COUNT(CASE WHEN MONTH(created_on) = 11 THEN id ELSE null END) AS Total_Nov,
			COUNT(CASE WHEN MONTH(created_on) = 12 THEN id ELSE null END) AS Total_Dec
		FROM
			properties");
		return $query->result_array()[0];
	}

	public function get_username($u)
	{
		$this->db->where('admin_username', $u);
		$query = $this->db->get('administrator');

		if ($query->num_rows() == 1) {
			return $query->row();
		} else {
			return false;
		}
	}
	public function get_usernamebranch($u)
	{
		$this->db->select('ubranch.*, branch.branch_name, branch.branch_code, branch.branch_address');
		$this->db->from('userbranch as ubranch');
		$this->db->where('ubranch.username', $u);
		$this->db->join('branch', 'ubranch.branch = branch.branch_id', 'left');
		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			return $query->row();
		} else {
			return false;
		}
	}
	public function get_useremail($e)
	{
		$this->db->select('Admins.*, branch.branch_name, branch.branch_code, branch.branch_address');
		$this->db->from('Admins');
		$this->db->where('Admins.email', $e);
		$this->db->join('branch', 'Admins.branch = branch.branch_id', 'left');

		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			return $query->row();
		} else {
			return false;
		}
	}
	public function get_dashboard_data()
	{
		$query = $query = $this->db->query("SELECT (SELECT COUNT(*) FROM `users` where role = 'Admin') AS users_count_admin,(SELECT COUNT(*) FROM `users` where role = 'Doctor') AS users_count_doctor,(SELECT COUNT(*) FROM `users` where role = 'Accountant') AS users_count_account,(SELECT COUNT(*) FROM `opd`) AS opd_count,(SELECT COUNT(*) FROM `ipd`) AS ipd_count,(SELECT COUNT(*) FROM `pharmacy`) AS pharmacy_count,(SELECT COUNT(*) FROM `pathology`) AS pathology_count,(SELECT COUNT(*) FROM `radiology`) AS radiology_count,(SELECT COUNT(*) FROM `ambulance`) AS ambulance_count,(SELECT COUNT(*) FROM `expense`) AS expense_count");
		return $query->result_array()[0];
	}



	public function get_student_created()
	{
		$query = $this->db->query("select Submit_Date_Time from Customers where Submit_Date_Time >= current_date - 3");
		return $query->result_array();
	}
	public function get_student_not_active()
	{
		$query = $this->db->query("select * from Customers  where status = 'inactive' and Submit_Date_Time >= current_date - 3 ");
		return $query->result_array();
	}
	public function get_student_active()
	{
		$query = $this->db->query("select * from Customers  where status = 'active' and Submit_Date_Time >= current_date - 3 ");
		return $query->result_array();
	}
	// $this->db->select('title, content, date');
	// $query = $this->db->get('mytable');

	public function get_dashboard_student_data()
	{
		$query = $query = $this->db->query("SELECT (SELECT COUNT(*) FROM `Customers`) AS student_count");
		return $query->result_array()[0];
	}

	#endregion


	public function delete_user($id)
	{
		$result = $this->db->query("delete from `users` where id = $id");
		if ($result) {
			return [
				'status' => true,
				'message' => 'Data deleted successfully'
			];
		} else {
			return [
				'status' => false,
				'message' => 'Error has occurred'
			];
		}
	}

	public function get_edit_student($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('Customers');
		return $query->row_array();
	}


	public function get_user($id)
	{
		$this->db->where('users_id', $id);
		$result = $this->db->get('users');
		return $result->row_array();
	}

	public function getuserrole($user_id)
	{
		$this->db->select('userselect_role.*,role.type_name,role.type_id');
		$this->db->from('user_select_roles as userselect_role');
		$this->db->join('userrole role', 'userselect_role.role_id = role.type_id');
		$this->db->where('userselect_role.user_id', $user_id);
		$result = $this->db->get();
		return $result->result_array();

	}
	public function get_users()
	{
		$this->db->limit(10);
		return $this->db->get('users');
	}

	public function get_students($limit = null)
	{
		if ($limit != null) {
			$this->db->limit($limit);
		}
		return $this->db->get('Customers')->result_array();
	}

	public function changepassword($newpassword, $id)
	{
		$data = array(
			'password' => $newpassword
		);
		$this->security->xss_clean($data);
		$this->db->where('id', $id);
		$this->db->update('Customers', $data);
	}

	public function password($password, $id)
	{
		$data = array(
			'password' => $password
		);
		$this->security->xss_clean($data);
		$this->db->where('id', $id);
		$this->db->update('Customers', $data);
	}

	public function get_employees($limit = null)
	{
		if ($limit != null) {
			$this->db->limit($limit);
		}
		return $this->db->get('Employments')->result_array();
	}

	public function get_locations()
	{
		$this->db->select('cities.*,countries.c_name AS country_name');
		$this->db->join('countries', 'countries.id = cities.country');
		return $this->db->get('cities');
	}
	public function get_cities($country)
	{
		$this->db->select('cities.*,countries.c_name AS country_name');
		$this->db->where('cities.country', $country);
		$this->db->join('countries', 'countries.id = cities.country');
		return $this->db->get('cities');
	}


	public function get_type_properties()
	{
		return $this->db->get('property_types');
	}

	public function getuserdata($role)
	{
		$this->db->where('role', $role);
		$query = $this->db->get('users');
		return $query->row_array();
	}

	public function calculate_arrearold($resident_id)
	{
		//get arrears by adding invoices and subtracting payments
		$this->db->select('SUM(payment_amount) as payment_amount');
		$payments = $this->db->get_where('payments', ['resident_id' => $resident_id])->row()->payment_amount;
		$this->db->select('SUM(total_amount) as total_amount');
		$invoices = $this->db->get_where('invoices', ['resident_id' => $resident_id])->row()->total_amount;
		$arrear_amount = $invoices - $payments;
		// echo $invoices;
		// echo '<br>';
		// echo $payments;
		// echo '<br>';
		// echo $arrear_amount;
		// die;
		return $arrear_amount;
	}



	public function get_resident_last_invoice($resident_id)
	{

		// $this->db->where('resident_id', $resident_id);
		// $arrer1 =  $this->db->get('invoices');


		$arrer1 = $this->db->query("SELECT *
		FROM 
			invoices
		WHERE `resident_id` = $resident_id  ORDER BY id DESC LIMIT 1")->row_array();

		// print_r($arrer1);
		// die;
		return $arrer1;
	}
	public function calculate_arrear($resident_id)
	{

		// $arrer1 = $this->db->query("SELECT *
		// FROM 
		// 	invoices
		// WHERE `resident_id` = $resident_id ORDER BY id DESC LIMIT 1")->row_array();


		// $arrer1['arrear_amount'];

		$arrer1 = $this->get_resident_last_invoice($resident_id);

		// print_r($arrer1['total_amount']);
		// die;


		// $this->db->select('SUM(payment_amount) as payment_amount');
		// $payments = $this->db->get_where('payments', ['resident_id' => $resident_id])->row()->payment_amount;

		// $this->db->select('SUM(surcharge) as surcharge');
		// $surcharge = $this->db->get_where('invoices', ['resident_id' => $resident_id])->row()->surcharge;
		// $this->db->select('SUM(amount) as amount');
		// $invoices = $this->db->get_where('invoices', ['resident_id' => $resident_id])->row()->amount;
		$arrear_amount = $arrer1['total_amount'] - $arrer1['total_paid_amount'];
		return $arrear_amount;
	}


	public function calculate_totalinvoiceamount($resident_id)
	{
		//get arrears by adding invoices and subtracting payments
		$arrer1 = $this->db->query("SELECT *
	FROM 
		invoices
	WHERE `resident_id` = $resident_id")->row_array();


		$arrer1['arrear_amount'];

		$this->db->select('SUM(surcharge) as surcharge');
		$surcharge = $this->db->get_where('invoices', ['resident_id' => $resident_id])->row()->surcharge;
		$this->db->select('SUM(amount) as amount');
		$invoices = $this->db->get_where('invoices', ['resident_id' => $resident_id])->row()->amount;
		$arrear_amount = $invoices + $arrer1['arrear_amount'] + $surcharge;
		// echo $invoices;
		// echo '<br>';
		// echo $payments;
		// echo '<br>';
		// echo $arrear_amount;
		// die;
		return $arrear_amount;
	}

	public function calculate_paid($resident_id)
	{
		//get arrears by adding invoices and subtracting payments
		$this->db->select('SUM(payment_amount) as payment_amount');
		$payments = $this->db->get_where('payments', ['resident_id' => $resident_id])->row()->payment_amount;
		$this->db->select('SUM(total_amount) as total_amount');
		$invoices = $this->db->get_where('invoices', ['resident_id' => $resident_id])->row()->total_amount;
		$total_amount = $payments;
		// echo $invoices;
		// echo '<br>';
		// echo $payments;
		// echo '<br>';
		// echo $arrear_amount;
		// die;
		return $total_amount;
	}

	public function calculate_remainingbalance($resident_id)
	{
		//get arrears by adding invoices and subtracting payments
		$this->db->select('SUM(payment_amount) as payment_amount');
		$payments = $this->db->get_where('payments', ['resident_id' => $resident_id])->row()->payment_amount;
		$this->db->select('SUM(total_amount) as total_amount');
		$invoices = $this->db->get_where('invoices', ['resident_id' => $resident_id])->row()->total_amount;
		$total_amount = $invoices - $payments;
		// echo $invoices;
		// echo '<br>';
		// echo $payments;
		// echo '<br>';
		// echo $arrear_amount;
		// die;
		return $total_amount;
	}
	public function calculate_totalinvoice($resident_id)
	{
		//get arrears by adding invoices and subtracting payments
		// $this->db->select('SUM(payment_amount) as payment_amount');
		// $payments = $this->db->get_where('payments', ['resident_id' => $resident_id])->row()->payment_amount;
		// $this->db->select('SUM(total_amount) as total_amount');
		$this->db->select('SUM(total_amount) as total_amount');
		$invoices = $this->db->get_where('invoices', ['resident_id' => $resident_id])->row()->total_amount;
		//$total_amount = $invoices;
		// echo $invoices;
		// echo '<br>';
		// echo $payments;
		// echo '<br>';
		// echo $arrear_amount;
		// die;
		return $invoices;
	}



	public function allgetdoctor()
	{
		$this->db->where('role', 'Doctor');
		$query = $this->db->get('users');
		return $query->result_array();
	}
	public function allgetfind_category()
	{
		$query = $this->db->get('find_category');
		return $query->result_array();
	}
	public function allgetdoctorslot()
	{
		$this->db->where('role', 'Doctor');
		$query = $this->db->get('users');
		return $query->result_array();
	}
	public function getbloodgroup()
	{
		$this->db->where('type', 'Blood Group');
		$query = $this->db->get('products');
		return $query->result_array();
	}
	public function component_blood()
	{
		$this->db->distinct();
		$this->db->select('components.*, products.product_id , products.name');
		$this->db->from('components');
		$this->db->join('componentsname', 'componentsname.componentsid = components.components_id', 'left');
		$this->db->join('products', 'products.product_id = components.bloodgroup', 'left');
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getcomponentbags($components)
	{
		$this->db->where('compname', $components);
		$query = $this->db->get('componentsname');
		return $query->result_array();
	}
	public function getallbagcomponent()
	{
		$query = $this->db->get('componentsname');
		return $query->result_array();
	}
	public function allchargename()
	{
		$query = $this->db->get('charges');
		return $query->result_array();
	}
	public function allchargecatergory()
	{
		$this->db->where('charge_type', 2);
		$this->db->or_where('charge_type', 8);
		$this->db->or_where('charge_type', 10);
		$this->db->or_where('charge_type', 11);
		$this->db->or_where('charge_type', 12);
		$query = $this->db->get('charge_category');
		return $query->result_array();
	}
	public function chargecategoryblood()
	{
		$this->db->where('charge_type', 6);
		$this->db->or_where('charge_type', 12);
		$query = $this->db->get('charge_category');
		return $query->result_array();
	}
	public function getallproduct()
	{
		$this->db->where('type', 'Blood Group');
		$query = $this->db->get('products');
		return $query->result_array();
	}
	public function allbloodchargecatergory()
	{
		$this->db->where('charge_type', 6);
		$this->db->or_where('charge_type', 12);
		$query = $this->db->get('charge_category');
		return $query->result_array();
	}

	public function allsymtype()
	{
		$query = $this->db->get('symptoms_type');
		return $query->result_array();
	}
	public function getleavetype()
	{
		$query = $this->db->get('leave_type');
		return $query->result_array();
	}

	public function getallbedbroup()
	{
		$query = $this->db->get('bed_groups');
		return $query->result_array();
	}

	public function getalltpa()
	{
		$query = $this->db->get('tpa');
		return $query->result_array();
	}

	public function getallmedicinecategory()
	{
		$query = $this->db->get('medicine_category');
		return $query->result_array();
	}
	public function getalllbatch()
	{
		$query = $this->db->get('medicineamount');
		return $query->result_array();
	}

	public function getchargetype()
	{
		$query = $this->db->get('charge_types');
		return $query->result_array();
	}
	public function gettaxcategory()
	{
		$query = $this->db->get('tax_category');
		return $query->result_array();
	}
	public function getunittype()
	{
		$query = $this->db->get('unit_charges');
		return $query->result_array();
	}
	public function getallpriority()
	{
		$query = $this->db->get('priority');
		return $query->result_array();
	}
	public function getallparameterpath()
	{
		$query = $this->db->get('parameter');
		return $query->result_array();
	}
	public function getallcategorypath()
	{
		$query = $this->db->get('path_category');
		return $query->result_array();
	}
	public function getallparameterradio()
	{
		$query = $this->db->get('radio_parameter');
		return $query->result_array();
	}
	public function getallcategoryradio()
	{
		$query = $this->db->get('radio_category');
		return $query->result_array();
	}
	public function getvehiclmodel()
	{
		$query = $this->db->get('ambulance_vehicle');
		return $query->result_array();
	}
	public function getchargecatvehicle()
	{
		$this->db->where('charge_type', 7);
		$this->db->or_where('charge_type', 12);
		$query = $this->db->get('charge_category');
		return $query->result_array();
	}
	public function getcategoryradio()
	{
		$this->db->where('charge_type', 5);
		$this->db->or_where('charge_type', 9);
		$this->db->or_where('charge_type', 12);
		$query = $this->db->get('charge_category');
		return $query->result_array();
	}
	public function getallrefercategory()
	{
		$query = $this->db->get('refer_category');
		return $query->result_array();
	}
	public function getitemcategory()
	{
		$query = $this->db->get('item_category');
		return $query->result_array();
	}
	public function insertappoint($data)
	{
		$this->db->insert('appointment', $data);
		return true;
	}
	public function insertbadstock($data)
	{
		$this->db->insert('badstock', $data);
		return true;
	}
	public function insertmedicinestock($data)
	{
		$this->db->insert('medicine_stock', $data);
		return true;
	}
	public function insertpayment($data)
	{
		$this->db->insert('payments', $data);
		return true;
	}
	public function addnursenote($data)
	{
		$this->db->insert('nursenote', $data);
		return true;
	}
	public function addmedication($data)
	{
		$this->db->insert('medication', $data);
		return true;
	}
	public function insertconsultregister($data)
	{
		$this->db->insert('consultregister', $data);
		return true;
	}
	public function insertmedicinepurchase($data)
	{
		$this->db->insert('medicinepurchase', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	public function insertcomponents($data)
	{
		$this->db->insert('components', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	public function insertapproveleave($data)
	{
		$this->db->insert('approveleave', $data);
		return true;
	}
	public function insertuser($data)
	{
		$this->db->insert('users', $data);
		return true;
	}
	public function addmessage($data)
	{
		$this->db->insert('message', $data);
		return true;
	}
	public function insertvisitor($data)
	{
		$this->db->insert('visitor', $data);
		return true;
	}
	public function insertitemstock($data)
	{
		$this->db->insert('item_stock', $data);
		return true;
	}
	public function insertreferperson($data)
	{
		$this->db->insert('referperson', $data);
		return true;
	}
	public function insertrefercomission($data)
	{
		$this->db->insert('refercomission', $data);
		return true;
	}
	public function insertambulance($data)
	{
		return $this->db->insert('ambulance', $data);

	}

	public function updateappoint($appointment_id, $data)
	{
		$this->db->where('appointment_id', $appointment_id);
		$this->db->update('appointment', $data);
		return true;
	}
	public function updatepath($path_id, $data)
	{
		$this->db->where('path_id', $path_id);
		$this->db->update('pathology', $data);
		return true;
	}
	// public function updatepathamount($path_id, $path_amount_id, $update)
	// {
	// 	$where = "path_amount_id = '$path_amount_id' AND path_id = '$path_id'";
	// 	$this->db->where($where);
	// 	$this->db->update('pathamount', $update);
	// 	return true;
	// }
	public function updatepathamount($path_id, $path_amount_id, $update)
	{
		if (!empty($path_amount_id)) {
			// Update an existing path amount record
			$this->db->where('path_id', $path_id);
			$this->db->where('path_amount_id', $path_amount_id);
			$this->db->update('pathamount', $update);
		} else {
			// Insert a new path amount record
			$update['path_id'] = $path_id;
			$this->db->insert('pathamount', $update);
		}
		// Check if the operation was successful and return the result
		return $this->db->affected_rows() > 0;
	}
	public function updatepharmaamount($pharma_id, $pharma_amount_id, $update)
	{
		if (!empty($pharma_amount_id)) {
			// Update an existing path amount record
			$this->db->where('pharma_id', $pharma_id);
			$this->db->where('pharma_amount_id', $pharma_amount_id);
			$this->db->update('pharmaamount', $update);
		} else {
			// Insert a new path amount record
			$update['pharma_id'] = $pharma_id;
			$this->db->insert('pharmaamount', $update);
		}
		// Check if the operation was successful and return the result
		return $this->db->affected_rows() > 0;
	}
	public function updateradioamount($radio_id, $radio_amount_id, $update)
	{
		if (!empty($radio_amount_id)) {
			// Update an existing radio amount record
			$this->db->where('radio_id', $radio_id);
			$this->db->where('radio_amount_id', $radio_amount_id);
			$this->db->update('radioamount', $update);
		} else {
			// Insert a new radio amount record
			$update['radio_id'] = $radio_id;
			$this->db->insert('radioamount', $update);
		}

		// Check if the operation was successful and return the result
		return $this->db->affected_rows() > 0;
	}


	// public function updatepathamount($path_id, $path_amount_ids, $update)
	// {
	// 	foreach ($path_amount_ids as $path_amount_id) {
	// 		$where = "path_amount_id = '$path_amount_id' AND path_id = '$path_id'";
	// 		$this->db->where($where);
	// 		$this->db->update('pathamount', $update);
	// 	}
	// 	return true;
	// }
	public function updaterefercomission($refer_comm_id, $data)
	{
		$this->db->where('refer_comm_id', $refer_comm_id);
		$this->db->update('refercomission', $data);
		return true;
	}
	public function updateopd($opd_id, $update)
	{
		$this->db->where('opd_id', $opd_id);
		$this->db->update('opd', $update);
		return true;
	}
	public function updateitemissue($issueitem_id, $data)
	{
		$this->db->where('issueitem_id', $issueitem_id);
		$this->db->update('issueitem', $data);
		return true;
	}
	public function insertpatient($data)
	{
		$this->db->insert('patient', $data);
		return true;
	}
	public function insertopd($data)
	{
		$this->db->insert('opd', $data);
		return true;
	}
	public function insertpath($data)
	{
		$this->db->insert('pathology', $data);
		$insertid = $this->db->insert_id();
		return $insertid;
	}
	public function insertradio($data)
	{
		$this->db->insert('radiology', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	public function insertamountpath($insert)
	{
		return $this->db->insert('pathamount', $insert);
	}
	public function insertcomponentsname($insert)
	{
		return $this->db->insert('componentsname', $insert);
	}
	public function updateamountpath($path_id, $update)
	{
		$this->db->where('path_id', $path_id);
		$this->db->insert('pathamount', $update);
		return true;
	}
	public function insertamountradio($insert)
	{
		return $this->db->insert('radioamount', $insert);
	}
	public function insertissueblood($data)
	{
		$this->db->insert('issueblood', $data);
		return true;
	}
	public function insertcomponentblood($data)
	{
		$this->db->insert('bloodissuecomponent', $data);
		return true;
	}

	public function insertshift($data)
	{
		$this->db->insert('doctor_shift', $data);
		return true;
	}

	public function updateshift($shift_id, $data)
	{
		$this->db->where('shift_id', $shift_id);
		$this->db->update('doctor_shift', $data);
		return true;
	}
	public function deletedoctorshift($shift_id)
	{
		$this->db->where('shift_id', $shift_id);
		$this->db->delete('doctor_shift');
		return true;
	}
	public function deletecomponentsname($componentsname_id)
	{
		$this->db->where('componentsname_id', $componentsname_id);
		$this->db->delete('componentsname');
		return true;
	}

	public function getallpatient()
	{
		$query = $this->db->get('patient');
		return $query->result_array();
	}

	public function getallappointment()
	{
		$this->db->select('appoint.*,p.patient_name,p.gender,p.phone,p.email,u.first_name,u.last_name,s.time_from,s.time_to,s.shift');
		$this->db->from('appointment as appoint');
		$this->db->join('patient p', 'appoint.patient = p.patient_id', 'left');
		$this->db->join('users u', 'appoint.doctor = u.users_id', 'left');
		$this->db->join('slots s', 'appoint.slot = s.slots_id', 'left');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getcomponentdata($blood_group)
	{
		$this->db->select('compname.*');
		$this->db->from('componentsname as compname');
		$this->db->join('components comp', 'compname.componentsid = comp.components_id', 'left');
		$this->db->where('comp.bloodgroup', $blood_group);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getsymtomstitle($symp_type)
	{
		$this->db->select('symhead.*');
		$this->db->from('symptoms_head as symhead');
		$this->db->join('symptoms_type symtype', 'symhead.symp_type = symtype.symp_type_id', 'left');
		$this->db->where('symhead.symp_type', $symp_type);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getcomponentdataedit()
	{
		$this->db->select('compname.*');
		$this->db->from('componentsname as compname');
		$this->db->join('components comp', 'compname.componentsid = comp.components_id', 'left');
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getcomponentdataedits()
	{
		$this->db->distinct();
		$this->db->select('compname.*');
		$this->db->from('componentsname as compname');
		$this->db->join('components comp', 'compname.componentsid = comp.components_id', 'left');
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getallcomission()
	{
		$this->db->select('refer.*,cat.refer_name');
		$this->db->from('refercomission as refer');
		$this->db->join('refer_category cat', 'refer.category = cat.refer_cat_id', 'left');
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getallcomponents()
	{
		$this->db->select('compname.*,bloodpr.name');
		$this->db->from('componentsname as compname');
		$this->db->join('components comp', 'compname.componentsid = comp.components_id', 'left');
		$this->db->join('products bloodpr', 'comp.bloodgroup = bloodpr.product_id', 'left');
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getallpharmacy()
	{
		$this->db->select('phr.*,u.first_name,u.last_name,p.patient_name,p.gender,p.phone,p.email');
		$this->db->from('pharmacy as phr');
		$this->db->join('patient p', 'phr.patient_id = p.patient_id', 'left');
		$this->db->join('users u', 'phr.doctor_name = u.users_id', 'left');
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getallperson()
	{
		$this->db->select('person.*,cat.refer_name');
		$this->db->from('referperson as person');
		$this->db->join('refer_category cat', 'person.category = cat.refer_cat_id', 'left');
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getallmedicinepurchase()
	{
		$this->db->select('purchase.*,sp.supplier_name');
		$this->db->from('medicinepurchase as purchase');
		$this->db->join('supplier sp', 'purchase.supplier = sp.supplier_id', 'left');
		$this->db->order_by('purchase.med_prch_id', 'desc');
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getallopdout()
	{
		$this->db->select('o.*,p.patient_name,p.patient_id,p.gender,p.phone,p.guardian_name,u.first_name,u.last_name');
		$this->db->from('opd as o');
		$this->db->join('users u', 'o.consult_doctor = u.users_id', 'left');
		$this->db->join('patient p', 'o.patient_id = p.patient_id', 'left');
		$this->db->group_by('p.patient_name');
		$this->db->order_by('o.opd_id', 'desc');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getallitemstock()
	{
		$this->db->select('stock.*,i.name,icat.cat_name,si.si_name,supi.sup_i_name');
		$this->db->from('item_stock as stock');
		$this->db->join('item i', 'stock.item = i.item_id', 'left');
		$this->db->join('item_category icat', 'stock.item_cat = icat.item_cat_id', 'left');
		$this->db->join('store_item si', 'stock.store = si.store_item_id', 'left');
		$this->db->join('supplier_item supi', 'stock.supplier = supi.supplier_item_id', 'left');
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getallipd()
	{
		$this->db->select('i.*,p.patient_name,p.patient_id,p.gender,p.phone,,u.first_name,u.last_name,bdg.bed_grp_name,bdg.floor,bd.bed_name');
		$this->db->from('ipd as i');
		$this->db->join('users u', 'i.consult_doctor = u.users_id', 'left');
		$this->db->join('patient p', 'i.patient_id = p.patient_id', 'left');
		$this->db->join('bed bd', 'i.bed_no = bd.bed_id', 'left');
		$this->db->join('bed_groups bdg', 'i.bedgroup = bdg.bed_group_id', 'left');
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getallopd()
	{
		$this->db->select('o.*,p.patient_id,p.patient_name,p.guardian_name,p.gender,p.phone,u.first_name,u.last_name');
		$this->db->from('opd as o');
		$this->db->join('patient p', 'o.patient_id = p.patient_id', 'left');
		$this->db->join('users u', 'o.consult_doctor = u.users_id', 'left');
		$this->db->group_by('p.patient_name');
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getallambulance()
	{
		$this->db->select('amb.*,p.patient_name,p.address,ambveh.vehicle_no,ambveh.vehicle_mo,ambveh.driver_name,ambveh.driver_contact,type_cat.name,c.charges_name');
		$this->db->from('ambulance as amb');
		$this->db->join('patient p', 'amb.patient_id = p.patient_id', 'left');
		$this->db->join('ambulance_vehicle ambveh', 'amb.vehicle_model = ambveh.ambulance_vehicleid', 'left');
		$this->db->join('charge_category type_cat', 'amb.charge_category = type_cat.charge_cat_id', 'left');
		$this->db->join('charges c', 'amb.charge_name = c.charges_id', 'left');
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getallpathtest()
	{
		$this->db->select('test.*,pathcat.pathcategory_name,type_cat.name,c.charges_name');
		$this->db->from('pathtest as test');
		$this->db->join('path_category pathcat', 'test.category_name = pathcat.pathology_cat_id', 'left');
		$this->db->join('charge_category type_cat', 'test.charge_category = type_cat.charge_cat_id', 'left');
		$this->db->join('charges c', 'test.charge_name = c.charges_id', 'left');
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getpathtest($pathtest_id)
	{
		$this->db->select('test.*,pathcat.pathcategory_name,type_cat.name,c.charges_name');
		$this->db->from('pathtest as test');
		$this->db->join('path_category pathcat', 'test.category_name = pathcat.pathology_cat_id', 'left');
		$this->db->join('charge_category type_cat', 'test.charge_category = type_cat.charge_cat_id', 'left');
		$this->db->join('charges c', 'test.charge_name = c.charges_id', 'left');
		$this->db->where('test.path_test_id', $pathtest_id);
		$query = $this->db->get();
		return $query->row_array();
	}

	public function getallbed()
	{
		$this->db->select('b.*,bgrp.bed_grp_name,f.name,type.typename');
		$this->db->from('bed as b');
		$this->db->join('bed_groups bgrp', 'b.bed_group = bgrp.bed_group_id', 'left');
		$this->db->join('floors f', 'bgrp.floor = f.floor_id', 'left');
		$this->db->join('bed_types type', 'b.bed_type = type.type_id', 'left');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getparatestpath($pathtest_id)
	{
		$this->db->select('parapath.*,para.paraname');
		$this->db->from('parametertest as parapath');
		$this->db->join('parameter para', 'parapath.para_name = para.parameter_id', 'left');
		$this->db->join('pathtest ptest', 'parapath.pathtest_id = ptest.path_test_id', 'left');
		$this->db->where('parapath.pathtest_id', $pathtest_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getparatestradio($radiotest_id)
	{
		$this->db->select('pararadio.*,rpara.paraname,type_cat.radiocategory_name');
		$this->db->from('parametertestradio as pararadio');
		$this->db->join('radio_parameter rpara', 'pararadio.para_name = rpara.radio_parameter_id', 'left');
		$this->db->join('radiotest rtest', 'pararadio.radiotest_id = rtest.radio_test_id', 'left');
		$this->db->join('radio_category type_cat', 'pararadio.para_name = type_cat.radio_cat_id', 'left');
		$this->db->where('pararadio.radiotest_id', $radiotest_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getallradiotest()
	{
		$this->db->select('testradio.*,radiocat.radiocategory_name,type_cat.name,c.charges_name');
		$this->db->from('radiotest as testradio');
		$this->db->join('radio_category radiocat', 'testradio.category_name = radiocat.radio_cat_id', 'left');
		$this->db->join('charge_category type_cat', 'testradio.charge_category = type_cat.charge_cat_id', 'left');
		$this->db->join('charges c', 'testradio.charge_name = c.charges_id', 'left');
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getradiotest($radiotest_id)
	{
		$this->db->select('testradio.*,radiocat.radiocategory_name,type_cat.name,c.charges_name');
		$this->db->from('radiotest as testradio');
		$this->db->join('radio_category radiocat', 'testradio.category_name = radiocat.radio_cat_id', 'left');
		$this->db->join('charge_category type_cat', 'testradio.charge_category = type_cat.charge_cat_id', 'left');
		$this->db->join('charges c', 'testradio.charge_name = c.charges_id', 'left');
		$query = $this->db->get();
		return $query->row_array();
	}
	public function getallstaff()
	{
		$this->db->select('u.*,spec.spec_name,depart.dpart_name');
		$this->db->from('users as u');
		$this->db->join('specialist spec', 'u.specialist = spec.specialist_id', 'left');
		$this->db->join('department depart', 'u.department = depart.department_id', 'left');
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getallpathology()
	{
		$this->db->select('path.*,p.patient_name,u.first_name,u.last_name');
		$this->db->from('pathology as path');
		$this->db->join('patient p', 'path.patient_id = p.patient_id', 'left');
		$this->db->join('users u', 'path.doctor_name = u.users_id', 'left');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getpath($pathid)
	{
		$this->db->select('path.*,p.patient_name,p.age,p.gender,u.first_name,u.last_name,pr.name,p.phone,p.email,p.address');
		$this->db->from('pathology as path');
		$this->db->join('patient p', 'path.patient_id = p.patient_id', 'left');
		$this->db->join('users u', 'path.doctor_name = u.users_id', 'left');
		$this->db->join('products pr', 'p.blood_group = pr.product_id', 'left');
		$this->db->where('path.path_id', $pathid);
		$query = $this->db->get();
		return $query->row_array();
	}
	public function getpurchasemedicine($med_prch_id)
	{
		$this->db->select('purchase.*,sp.supplier_name,sp.supplier_contact,sp.cpn,sp.address');
		$this->db->from('medicinepurchase as purchase');
		$this->db->join('supplier sp', 'purchase.supplier = sp.supplier_id', 'left');
		$this->db->where('purchase.med_prch_id', $med_prch_id);
		$query = $this->db->get();
		return $query->row_array();
	}
	public function getpurchasemedicineamount($med_prch_id)
	{
		$this->db->select('amount.*,cat.med_cat_name,stock.stockname');
		$this->db->from('medicineamount as amount');
		$this->db->join('medicine_category cat', 'amount.med_cat = cat.medicine_cat_id', 'left');
		$this->db->join('medicine_stock stock', 'amount.med_name = stock.medicine_stock_id', 'left');
		$this->db->where('amount.med_purchase_id', $med_prch_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getradio($radioid)
	{
		$this->db->select('radio.*,p.patient_name,u.first_name,u.last_name');
		$this->db->from('radiology as radio');
		$this->db->join('patient p', 'radio.patient_id = p.patient_id', 'left');
		$this->db->join('users u', 'radio.doctor_name = u.users_id', 'left');
		$this->db->where('radio.radio_id', $radioid);
		$query = $this->db->get();
		return $query->row_array();
	}
	public function getpathamount($pathid)
	{
		$this->db->select('pamount.*,ptest.testname');
		$this->db->from('pathamount as pamount');
		$this->db->join('pathtest ptest', 'pamount.test_name = ptest.path_test_id', 'left');
		$this->db->where('pamount.path_id', $pathid);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getradioamount($radioid)
	{
		$this->db->select('ramount.*,rtest.testname');
		$this->db->from('radioamount as ramount');
		$this->db->join('radiotest rtest', 'ramount.test_name = rtest.radio_test_id', 'left');
		$this->db->where('ramount.radio_id', $radioid);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getallpathologytest()
	{
		$query = $this->db->get('pathtest');
		return $query->result_array();
	}
	public function getinterval()
	{
		$query = $this->db->get('interval');
		return $query->result_array();
	}
	public function getduration()
	{
		$query = $this->db->get('duration');
		return $query->result_array();
	}
	public function getallfindcat()
	{
		$query = $this->db->get('find_category');
		return $query->result_array();
	}
	public function getallradiotestform()
	{
		$query = $this->db->get('radiotest');
		return $query->result_array();
	}
	public function getaallradiology()
	{
		$this->db->select('radio.*,p.patient_name,u.first_name,u.last_name');
		$this->db->from('radiology as radio');
		$this->db->join('patient p', 'radio.patient_id = p.patient_id', 'left');
		$this->db->join('users u', 'radio.doctor_name = u.users_id', 'left');
		$this->db->order_by('radio.radio_id', 'DESC');
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getappointment($appointment_id)
	{
		// $this->db->select('appoint.*');
		// $this->db->from('appointment as appoint');
		// $this->db->join('patient p', 'appoint.patient = p.patient_id', 'left');
		// $this->db->join('users u', 'appoint.doctor = u.users_id', 'left');
		// $this->db->where('appoint.appointment_id', $appointment_id);
		$this->db->where('appointment_id', $appointment_id);
		$query = $this->db->get('appointment');
		return $query->row_array();
	}
	public function getpharma($pharma_id)
	{
		$this->db->select('p.*,pat.patient_name,pat.patient_id,pat.phone,u.first_name,u.last_name');
		$this->db->from('pharmacy as p');
		$this->db->join('patient pat', 'p.patient_id = p.patient_id', 'left');
		$this->db->join('users u', 'p.doctor_name = u.users_id', 'left');
		$this->db->where('p.pharma_id', $pharma_id);
		$query = $this->db->get();
		return $query->row_array();
	}
	public function getpharmaamount($pharma_id)
	{
		$this->db->select('pamount.*,medcat.med_cat_name,medstock.stockname,medstock.unit');
		$this->db->from('pharmaamount as pamount');
		$this->db->join('pharmacy p', 'pamount.pharma_id = p.pharma_id', 'left');
		$this->db->join('medicine_category medcat', 'pamount.medicine_cat = medcat.medicine_cat_id', 'left');
		$this->db->join('medicine_stock medstock', 'pamount.medicine_name = medstock.medicine_stock_id', 'left');
		$this->db->where('pamount.pharma_id', $pharma_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getambulance($ambulance_id)
	{
		$this->db->where('ambulance_id', $ambulance_id);
		$query = $this->db->get('ambulance');
		return $query->row_array();
	}
	public function getrole($role)
	{
		$this->db->where('role', $role);
		$query = $this->db->get('users');
		return $query->result_array();
	}
	public function getmessage($message_id)
	{
		$this->db->where('message_id', $message_id);
		$query = $this->db->get('message');
		return $query->row_array();
	}

	public function getitemstock($item_stock_id)
	{
		$this->db->where('item_stock_id', $item_stock_id);
		$query = $this->db->get('item_stock');
		return $query->row_array();
	}

	public function getstaff($user_id)
	{
		$this->db->where('users_id', $user_id);
		$query = $this->db->get('users');
		return $query->row_array();
	}

	public function getcomission($refer_comm_id)
	{
		$this->db->where('refer_comm_id', $refer_comm_id);
		$query = $this->db->get('refercomission');
		return $query->row_array();
	}
	public function getperson($refer_person_id)
	{
		$this->db->where('refer_person_id', $refer_person_id);
		$query = $this->db->get('referperson');
		return $query->row_array();
	}
	public function getrefercom($category)
	{
		$this->db->where('category', $category);
		$query = $this->db->get('refercomission');
		return $query->row_array();
	}
	public function getdeleteappointment($appointment_id)
	{
		$this->db->where('appointment_id', $appointment_id);
		$this->db->delete('appointment');
		return true;
	}
	public function deltemedicinepurchase($med_prch_id)
	{
		$this->db->where('med_prch_id', $med_prch_id);
		$this->db->delete('medicinepurchase');
		return true;
	}
	public function deletepath($path_id)
	{
		$this->db->where('path_id', $path_id);
		$this->db->delete('pathology');
		return true;
	}
	public function deleteradio($radio_id)
	{
		$this->db->where('radio_id', $radio_id);
		$this->db->delete('radiology');
		return true;
	}
	public function getmessagedelete($message_id)
	{
		$this->db->where('message_id', $message_id);
		$this->db->delete('message');
		return true;
	}
	public function delete_comission($refer_comm_id)
	{
		$this->db->where('refer_comm_id', $refer_comm_id);
		$this->db->delete('refercomission');
		return true;
	}
	public function delete_person($refer_person_id)
	{
		$this->db->where('refer_person_id', $refer_person_id);
		$this->db->delete('referperson');
		return true;
	}
	public function getissueblooddelete($ib_id)
	{
		$this->db->where('ib_id', $ib_id);
		$this->db->delete('issueblood');
		return true;
	}
	public function allgetdoctorshift()
	{
		$this->db->select('shift.*,u.first_name,u.last_name');
		$this->db->from('doctor_shift as shift');
		$this->db->join('users u', 'shift.doctor_name = u.users_id', 'left');
		$this->db->where('u.role', 'Doctor');
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getallapproveleave()
	{
		$this->db->select('apleave.*,u.users_id,u.first_name,u.last_name,type.leavename');
		$this->db->from('approveleave as apleave');
		$this->db->join('users u', 'apleave.name = u.users_id', 'left');
		$this->db->join('leave_type type', 'apleave.leave_type = type.leave_id', 'left');
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getallnursenote($patient_id)
	{
		$this->db->select('note.*,p.patient_name');
		$this->db->from('nursenote as note');
		$this->db->join('patient p', 'note.patient_id = p.patient_id', 'left');
		$this->db->where('note.patient_id', $patient_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getallmedication($patient_id)
	{
		$this->db->select('medic.*,stock.stockname,dos.dose');
		$this->db->from('medication as medic');
		$this->db->join('medicine_stock stock', 'medic.medicine_name = stock.medicine_stock_id', 'left');
		$this->db->join('dosage dos', 'medic.dosage = dos.dosage_id', 'left');
		$this->db->where('medic.patient_id', $patient_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getallconsultregister($patient_id)
	{
		$this->db->select('consult.*,u.first_name,u.last_name,u.users_id');
		$this->db->from('consultregister as consult');
		$this->db->join('users u', 'consult.consult_doctor = u.users_id', 'left');
		$this->db->where('consult.patient_id', $patient_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getalloperations($patient_id)
	{
		$this->db->select('op.*,u.first_name,u.last_name,opcat.name,oper.operate_name');
		$this->db->from('operations as op');
		$this->db->join('users u', 'op.consult_doctor = u.users_id', 'left');
		$this->db->join('operation_category opcat', 'op.ope_cat = opcat.operate_cat_id', 'left');
		$this->db->join('operation oper', 'op.ope_name = oper.operation_id', 'left');
		$this->db->where('op.patient_id', $patient_id);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getallpayments($patient_id)
	{
		$this->db->where('patient_id', $patient_id);
		$query = $this->db->get('payments');
		return $query->result_array();
	}
	public function getalltimeline($patient_id)
	{
		$this->db->where('patient_id', $patient_id);
		$query = $this->db->get('timeline');
		return $query->result_array();
	}
	public function getalltimelineopd($patient_id)
	{
		$this->db->where('patient_id', $patient_id);
		$query = $this->db->get('timeline_opd');
		return $query->result_array();
	}
	public function allgetdoctorshiftform()
	{
		$this->db->where('role', 'Doctor');
		$query = $this->db->get('users');
		return $query->result_array();
	}
	public function getallproducts()
	{
		$this->db->where('type', 'Blood Group');
		$query = $this->db->get('products');
		return $query->result_array();
	}
	public function getusername($user_type)
	{
		$this->db->where('role', $user_type);
		$query = $this->db->get('users');
		return $query->result_array();
	}
	public function getmedicinename($medicinecategory)
	{
		$this->db->where('category', $medicinecategory);
		$query = $this->db->get('medicine_stock');
		return $query->result_array();
	}
	public function getmedicinenamedosage($medicinecategory)
	{
		$this->db->where('medicine_cat', $medicinecategory);
		$query = $this->db->get('dosage');
		return $query->result_array();
	}
	public function getbatch($med_name)
	{
		$this->db->where('med_name', $med_name);
		$query = $this->db->get('medicineamount');
		return $query->result_array();
	}
	public function getmedamount($batch_no)
	{
		$this->db->where('batch_number', $batch_no);
		$query = $this->db->get('medicineamount');
		return $query->row_array();
	}
	public function getbplusallblooddonor()
	{
		$this->db->where('blood_group', 13);
		$query = $this->db->get('blood_donor_detail');
		return $query->result_array();
	}
	public function getbplusallcomponent()
	{
		$this->db->select('compname.*,comp.components_id');
		$this->db->from('componentsname as compname');
		$this->db->join('components comp', 'compname.componentsid = comp.components_id', 'left');
		$this->db->join('products bloodpr', 'comp.bloodgroup = bloodpr.product_id', 'left');
		$this->db->where('comp.bloodgroup', 13);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getaplusallcomponent()
	{
		$this->db->select('compname.*');
		$this->db->from('componentsname as compname');
		$this->db->join('components comp', 'compname.componentsid = comp.components_id', 'left');
		$this->db->join('products bloodpr', 'comp.bloodgroup = bloodpr.product_id', 'left');
		$this->db->where('comp.bloodgroup', 12);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getabplusallcomponent()
	{
		$this->db->select('compname.*');
		$this->db->from('componentsname as compname');
		$this->db->join('components comp', 'compname.componentsid = comp.components_id', 'left');
		$this->db->join('products bloodpr', 'comp.bloodgroup = bloodpr.product_id', 'left');
		$this->db->where('comp.bloodgroup', 10);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getabminusallcomponent()
	{
		$this->db->select('compname.*');
		$this->db->from('componentsname as compname');
		$this->db->join('components comp', 'compname.componentsid = comp.components_id', 'left');
		$this->db->join('products bloodpr', 'comp.bloodgroup = bloodpr.product_id', 'left');
		$this->db->where('comp.bloodgroup', 11);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getominusallcomponent()
	{
		$this->db->select('compname.*');
		$this->db->from('componentsname as compname');
		$this->db->join('components comp', 'compname.componentsid = comp.components_id', 'left');
		$this->db->join('products bloodpr', 'comp.bloodgroup = bloodpr.product_id', 'left');
		$this->db->where('comp.bloodgroup', 9);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getaminusallcomponent()
	{
		$this->db->select('compname.*');
		$this->db->from('componentsname as compname');
		$this->db->join('components comp', 'compname.componentsid = comp.components_id', 'left');
		$this->db->join('products bloodpr', 'comp.bloodgroup = bloodpr.product_id', 'left');
		$this->db->where('comp.bloodgroup', 8);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getbminusallcomponent()
	{
		$this->db->select('compname.*');
		$this->db->from('componentsname as compname');
		$this->db->join('components comp', 'compname.componentsid = comp.components_id', 'left');
		$this->db->join('products bloodpr', 'comp.bloodgroup = bloodpr.product_id', 'left');
		$this->db->where('comp.bloodgroup', 7);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getoplusallcomponent()
	{
		$this->db->select('compname.*');
		$this->db->from('componentsname as compname');
		$this->db->join('components comp', 'compname.componentsid = comp.components_id', 'left');
		$this->db->join('products bloodpr', 'comp.bloodgroup = bloodpr.product_id', 'left');
		$this->db->where('comp.bloodgroup', 6);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getallblooddonor()
	{
		$query = $this->db->get('blood_donor_detail');
		return $query->result_array();
	}
	public function getallmedicine_category()
	{
		$query = $this->db->get('medicine_category');
		return $query->result_array();
	}
	public function getalloperationcategory()
	{
		$query = $this->db->get('operation_category');
		return $query->result_array();
	}
	public function getmedicinenames($medicine_category)
	{
		$this->db->where('category', $medicine_category);
		$query = $this->db->get('medicine_stock');
		return $query->result_array();
	}
	public function getmedicinedosage($medicine_category)
	{
		$this->db->where('medicine_cat', $medicine_category);
		$query = $this->db->get('dosage');
		return $query->result_array();
	}
	public function getnameoperation($operation_category)
	{
		$this->db->where('category', $operation_category);
		$query = $this->db->get('operation');
		return $query->result_array();
	}

	public function getspecialist()
	{
		$query = $this->db->get('specialist');
		return $query->result_array();
	}
	public function getalldesignations()
	{
		$query = $this->db->get('designations');
		return $query->result_array();
	}
	public function getalldepartment()
	{
		$query = $this->db->get('department');
		return $query->result_array();
	}

	public function getaplusallblooddonor()
	{
		$this->db->where('blood_group', 12);
		$query = $this->db->get('blood_donor_detail');
		return $query->result_array();
	}
	public function getabplusallblooddonor()
	{
		$this->db->where('blood_group', 10);
		$query = $this->db->get('blood_donor_detail');
		return $query->result_array();
	}
	public function getabminusallblooddonor()
	{
		$this->db->where('blood_group', 11);
		$query = $this->db->get('blood_donor_detail');
		return $query->result_array();
	}
	public function getominusallblooddonor()
	{
		$this->db->where('blood_group', 9);
		$query = $this->db->get('blood_donor_detail');
		return $query->result_array();
	}
	public function getaminusallblooddonor()
	{
		$this->db->where('blood_group', 8);
		$query = $this->db->get('blood_donor_detail');
		return $query->result_array();
	}
	public function getbminusallblooddonor()
	{
		$this->db->where('blood_group', 7);
		$query = $this->db->get('blood_donor_detail');
		return $query->result_array();
	}
	public function getoplusallblooddonor()
	{
		$this->db->where('blood_group', 6);
		$query = $this->db->get('blood_donor_detail');
		return $query->result_array();
	}
	public function getallbloodbag()
	{
		$query = $this->db->get('blood_donor_detail');
		return $query->result_array();
	}
	public function getdoctorshift($shift_id)
	{
		$this->db->select('shift.*,u.first_name,u.last_name');
		$this->db->from('doctor_shift as shift');
		$this->db->join('users u', 'shift.doctor_name = u.users_id', 'left');
		$this->db->where('u.role', 'Doctor');
		$this->db->where('shift.shift_id', $shift_id);
		$query = $this->db->get();
		return $query->row_array();
	}
	public function getmedicinedata($medicine_stock_id)
	{
		$this->db->select('stock.*,medcat.med_cat_name');
		$this->db->from('medicine_stock as stock');
		$this->db->join('medicine_category medcat', 'stock.category = medcat.medicine_cat_id', 'left');
		$this->db->where('stock.medicine_stock_id', $medicine_stock_id);
		$query = $this->db->get();
		return $query->row_array();
	}

	public function getshift($doctor_name)
	{
		$this->db->select('shift.*');
		$this->db->from('doctor_shift as shift');
		$this->db->where('shift.doctor_name', $doctor_name);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getshift1($doctor)
	{
		$this->db->select('shift.*');
		$this->db->from('doctor_shift as shift');
		$this->db->where('shift.doctor_name', $doctor);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getshift2($doctordetail)
	{
		$this->db->select('shift.*');
		$this->db->from('doctor_shift as shift');
		$this->db->where('shift.doctor_name', $doctordetail);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getshift1app($doctor)
	{
		$this->db->select('shift.*');
		$this->db->from('doctor_shift as shift');
		$this->db->where('shift.doctor_name', $doctor);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getcomponentdetail()
	{
		$this->db->select('compdetail.*,p.patient_name,p.gender,prd.name,componame.bag,componame.compname');
		$this->db->from('component_detail as compdetail');
		$this->db->join('patient p', 'compdetail.patient_id = p.patient_id', 'left');
		$this->db->join('products prd', 'compdetail.blood_grp = prd.product_id', 'left');
		$this->db->join('componentsname componame', 'compdetail.bag = componame.bag', 'left');
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getallshift()
	{
		$query = $this->db->get('shift');
		return $query->result_array();
	}

	public function getallslotsselected($appointment_id)
	{
		$this->db->select('app.*,s.slots_id,s.time_from,s.time_to');
		$this->db->from('appointment as app');
		$this->db->join('slots s', 'app.slot = s.slots_id', 'left');
		$this->db->where('app.appointment_id', $appointment_id);
		$query = $this->db->get();
		return $query->row_array();
	}
	public function getallslots()
	{
		$query = $this->db->get('slots');
		return $query->result_array();
	}
	public function gettestpath($test_name)
	{
		$this->db->where('path_test_id', $test_name);
		$query = $this->db->get('pathtest');
		return $query->row_array();
	}
	public function getallnurse()
	{
		$this->db->where('role', 'Nurse');
		$query = $this->db->get('users');
		return $query->result_array();
	}

	public function gettestradio($test_name)
	{
		$this->db->where('radio_test_id', $test_name);
		$query = $this->db->get('radiotest');
		return $query->row_array();
	}
	public function getcomponent($componentsname_id)
	{
		$this->db->select('componame.*,comp.bloodgroup');
		$this->db->from('componentsname as componame');
		$this->db->join('components comp', 'componame.componentsid = comp.components_id', 'left');
		$this->db->where('componame.componentsname_id', $componentsname_id);
		$query = $this->db->get();
		return $query->row_array();
	}
	public function allproductcomp()
	{
		$this->db->where('type', 'Component');
		$query = $this->db->get('products');
		return $query->result_array();
	}
	public function getcomponentissuedetail($component_issuedetail_id)
	{
		$this->db->where('component_issuedetail_id', $component_issuedetail_id);
		$query = $this->db->get('component_detail');
		return $query->row_array();
	}
	public function getcomponentissuedetailpay($component_issuedetail_id)
	{
		$this->db->select('compdetail.*,p.patient_name,prod.name');
		$this->db->from('component_detail as compdetail');
		$this->db->join('patient p', 'compdetail.patient_id = p.patient_id', 'left');
		$this->db->join('products prod', 'compdetail.blood_grp = prod.product_id', 'left');
		$this->db->where('compdetail.component_issuedetail_id', $component_issuedetail_id);
		$query = $this->db->get('');
		return $query->row_array();
	}
	public function getalltransaction($component_issuedetail_id)
	{
		$this->db->where('component_detailid', $component_issuedetail_id);
		$query = $this->db->get('transaction_compissue');
		return $query->result_array();
	}
	public function getallblodissuepay($ib_id)
	{
		$this->db->where('ib_id', $ib_id);
		$query = $this->db->get('transaction_issueblood');
		return $query->result_array();
	}
	public function getallpathpayment($path_id)
	{
		$this->db->where('path_id', $path_id);
		$query = $this->db->get('transaction_path');
		return $query->result_array();
	}
	public function getallpathpaymentpharma($pharma_id)
	{
		$this->db->where('pharma_id', $pharma_id);
		$query = $this->db->get('transaction_pharma');
		return $query->result_array();
	}
	public function getalltransactionradio($radio_id)
	{
		$this->db->where('radio_id', $radio_id);
		$query = $this->db->get('transaction_radio');
		return $query->result_array();
	}
	public function getallblooddonors()
	{
		$query = $this->db->get('donor');
		return $query->result_array();
	}
	public function getallpurpose()
	{
		$query = $this->db->get('purpose');
		return $query->result_array();
	}
	public function getallunitype()
	{
		$query = $this->db->get('unit_charges');
		return $query->result_array();
	}
	public function getallitem_category()
	{
		$query = $this->db->get('item_category');
		return $query->result_array();
	}
	public function getitems($item_category)
	{
		$this->db->where('category', $item_category);
		$query = $this->db->get('item');
		return $query->result_array();
	}

	public function getallsupplier()
	{
		$query = $this->db->get('supplier_item');
		return $query->result_array();
	}
	public function getallsuppliers()
	{
		$query = $this->db->get('supplier');
		return $query->result_array();
	}
	public function getallstoreitem()
	{
		$query = $this->db->get('store_item');
		return $query->result_array();
	}

	public function checkexist($doctor_name)
	{
		$this->db->where('doctor_name', $doctor_name);
		$query = $this->db->get('doctor_shift');
		return $query->row();
	}
	public function checkexistdoctor($doctordetail)
	{
		$this->db->where('doctor', $doctordetail);
		$query = $this->db->get('slots_doctor');
		return $query->row();
	}
	public function getchargedata($charge)
	{
		$this->db->where('charges_id', $charge);
		$query = $this->db->get('charges');
		return $query->row();
	}
	public function getchargedatablood($charge_name)
	{
		$this->db->where('charges_id', $charge_name);
		$query = $this->db->get('charges');
		return $query->row();
	}
	public function getchargesv($chargename)
	{
		$this->db->where('charges_id', $chargename);
		$query = $this->db->get('charges');
		return $query->row();
	}

	public function getchargecategory($charge_type)
	{
		$this->db->select('charge.*');
		$this->db->from('charge_category as charge');
		$this->db->join('charge_types types', 'charge.charge_type = types.charge_type_id', 'left');
		$this->db->where('charge.charge_type', $charge_type);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function chargecategory()
	{
		// $this->db->select('charge.*');
		// $this->db->from('charge_category as charge');
		// $this->db->where('charge.charge_type', 1, 12);
		$this->db->where('charge_type', 1, 12);
		$query = $this->db->get('charge_category');
		return $query->result_array();
	}
	public function getbednumber($bed_group)
	{
		$this->db->where('bed_group', $bed_group);
		$query = $this->db->get('bed');
		return $query->result_array();
	}

	public function getchargeedit($charge_category)
	{
		$this->db->where('charges_id', $charge_category);
		$query = $this->db->get('charges');
		return $query->row_array();
	}
	public function insertcharges($data)
	{
		$this->db->insert('charges', $data);
		return true;
	}

	public function insertranscissuecomp($data)
	{
		$this->db->insert('transaction_compissue', $data);
		return true;
	}
	public function insertpaypath($data)
	{
		$this->db->insert('transaction_path', $data);
		return true;
	}
	public function insertpaypharma($data)
	{
		$this->db->insert('transaction_pharma', $data);
		return true;
	}
	public function insertpayradio($data)
	{
		$this->db->insert('transaction_radio', $data);
		return true;
	}
	public function insertpaybloodissue($data)
	{
		$this->db->insert('transaction_issueblood', $data);
		return true;
	}
	public function inserttimeline($data)
	{
		$this->db->insert('timeline', $data);
		return true;
	}
	public function inserttimelineopd($data)
	{
		$this->db->insert('timeline_opd', $data);
		return true;
	}
	public function insertoperations($data)
	{
		$this->db->insert('operations', $data);
		return true;
	}
	public function insertcomponentissue($data)
	{
		$this->db->insert('component_detail', $data);
		return true;
	}
	public function updatedatacomponentissue($component_issuedetail_id, $data)
	{
		$this->db->where('component_issuedetail_id', $component_issuedetail_id);
		$this->db->update('component_detail', $data);
		return true;
	}
	public function insertissueitem($data)
	{
		$this->db->insert('issueitem', $data);
		return true;
	}
	public function insertaddtest($data)
	{
		$this->db->insert('pathtest', $data);
		$insertid = $this->db->insert_id();
		return $insertid;
	}
	public function insertprescription($data)
	{
		$this->db->insert('prescription', $data);
		$insertid = $this->db->insert_id();
		return $insertid;
	}
	public function insertaddpharma($data)
	{
		$this->db->insert('pharmacy', $data);
		$insertid = $this->db->insert_id();
		return $insertid;
	}
	public function insertparametertest($insert)
	{
		return $this->db->insert('parametertest', $insert);
	}
	public function insertparametertestradio($insert)
	{
		return $this->db->insert('parametertestradio', $insert);
	}
	public function insertmedicineamount($data)
	{
		return $this->db->insert('medicineamount', $data);
	}
	public function insertpharmaamount($insert)
	{
		return $this->db->insert('pharmaamount', $insert);
	}
	public function insermedicinepresc($insert)
	{
		return $this->db->insert('medicineprescriptions', $insert);
	}
	public function insertaddtestradio($data)
	{
		$this->db->insert('radiotest', $data);
		return true;
	}
	public function insertipd($data)
	{
		$this->db->insert('ipd', $data);
		return true;
	}
	public function insertblooddonor($data)
	{
		$this->db->insert('blood_donor_detail', $data);
		return true;
	}

	public function updatecharges($charges_id, $data)
	{
		$this->db->where('charges_id', $charges_id);
		$this->db->update('charges', $data);
		return true;
	}
	public function updatemedicinestock($medicine_stock_id, $data)
	{
		$this->db->where('medicine_stock_id', $medicine_stock_id);
		$this->db->update('medicine_stock', $data);
		return true;
	}
	public function update_pharma($pharma_id, $data)
	{
		$this->db->where('pharma_id', $pharma_id);
		$this->db->update('pharmacy', $data);
		return true;
	}
	public function getblooddonordata($bloodgroup)
	{
		$this->db->where('blood_group', $bloodgroup);
		$query = $this->db->get('blood_donor_detail');
		return $query->result_array();
	}
	public function updateradio($radio_id, $data)
	{
		$this->db->where('radio_id', $radio_id);
		$this->db->update('radiology', $data);
		return true;
	}
	public function updateuser($user_id, $data)
	{
		$this->db->where('users_id', $user_id);
		$this->db->update('users', $data);
		return true;
	}
	public function editmessage($message_id, $data)
	{
		$this->db->where('message_id', $message_id);
		$this->db->update('message', $data);
		return true;
	}
	public function getupdateitemstock($item_stock_id, $data)
	{
		$this->db->where('item_stock_id', $item_stock_id);
		$this->db->update('item_stock', $data);
		return true;
	}
	public function updatereferperson($refer_person_id, $data)
	{
		$this->db->where('refer_person_id', $refer_person_id);
		$this->db->update('referperson', $data);
		return true;
	}
	public function getupdateambulance($ambulance_id, $data)
	{
		$this->db->where('ambulance_id', $ambulance_id);
		$this->db->update('ambulance', $data);
		return true;
	}
	public function updateissueblood($ib_id, $data)
	{
		$this->db->where('ib_id', $ib_id);
		$this->db->update('issueblood', $data);
		return true;
	}
	public function deletecharges($charges_id)
	{
		$this->db->where('charges_id', $charges_id);
		$this->db->delete('charges');
		return true;
	}
	public function deletemedicine($medicine_stock_id)
	{
		$this->db->where('medicine_stock_id', $medicine_stock_id);
		$this->db->delete('medicine_stock');
		return true;
	}
	public function deletecomponentissue($component_issuedetail_id)
	{
		$this->db->where('component_issuedetail_id', $component_issuedetail_id);
		$this->db->delete('component_detail');
		return true;
	}
	public function deletetranasction($compissue_transc_id)
	{
		$this->db->where('compissue_transc_id', $compissue_transc_id);
		$this->db->delete('transaction_compissue');
		return true;
	}
	public function getitemstockdelete($item_stock_id)
	{
		$this->db->where('item_stock_id', $item_stock_id);
		$this->db->delete('item_stock');
		return true;
	}
	public function deleteambulance($ambulance_id)
	{
		$this->db->where('ambulance_id', $ambulance_id);
		$this->db->delete('ambulance');
		return true;
	}
	public function getcharges()
	{
		$this->db->select('c.*,type.charge_name,type_cat.name,unit.unit_name');
		$this->db->from('charges as c');
		$this->db->join('charge_types type', 'c.charge_type = type.charge_type_id', 'left');
		$this->db->join('unit_charges unit', 'c.unit_type = unit.unit_id', 'left');
		$this->db->join('charge_category type_cat', 'c.charge_category = type_cat.charge_cat_id', 'left');
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getallissueitem()
	{
		$this->db->select('issue.*,i.name,ic.cat_name,u.first_name,u.last_name');
		$this->db->from('issueitem as issue');
		$this->db->join('item i', 'issue.item = i.item_id', 'left');
		$this->db->join('item_category ic', 'issue.item_category = ic.item_cat_id', 'left');
		$this->db->join('users u', 'issue.issue_to = u.users_id', 'left');
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getallissueblood()
	{
		$this->db->select('issue.*,p.name,pat.gender,d.donor_name,blood.bag,pat.patient_name');
		$this->db->from('issueblood as issue');
		$this->db->join('products p', 'issue.blood_grp = p.product_id', 'left');
		$this->db->join('patient pat', 'issue.patient_id = pat.patient_id', 'left');
		$this->db->join('blood_donor_detail blood', 'issue.bag = blood.blood_donor_id', 'left');
		$this->db->join('donor d', 'blood.blood_donor = d.donor_id', 'left');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getbag($blood_donor_id)
	{
		$this->db->where('blood_donor_id', $blood_donor_id);
		$query = $this->db->get('blood_donor_detail');
		return $query->row_array();
	}
	public function getallbag()
	{
		$query = $this->db->get('blood_donor_detail');
		return $query->result_array();
	}
	public function getallbagcomp()
	{
		$query = $this->db->get('componentsname');
		return $query->result_array();
	}
	public function getBagsByBloodGroup($selectedBloodGroup)
	{
		$this->db->where('blood_group', $selectedBloodGroup);
		$query = $this->db->get('blood_donor_detail');
		return $query->result_array();
	}

	public function getallcharge()
	{
		$query = $this->db->get('charges');
		return $query->result_array();
	}

	public function getcharge($charge_category)
	{
		$this->db->select('c.*');
		$this->db->from('charges as c');
		$this->db->where('c.charge_category', $charge_category);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getchargevehicle($chargecategory)
	{
		$this->db->select('c.*');
		$this->db->from('charges as c');
		$this->db->where('c.charge_category', $chargecategory);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getchargeblood($charge_category)
	{
		$this->db->where('charge_category', $charge_category);
		$query = $this->db->get('charges');
		return $query->result_array();
	}

	public function getbloodbag($blood_group)
	{
		$this->db->where('blood_group', $blood_group);
		$query = $this->db->get('blood_donor_detail');
		return $query->result_array();
	}
	public function getvehicle($vehicle_model)
	{
		$this->db->where('ambulance_vehicleid', $vehicle_model);
		$query = $this->db->get('ambulance_vehicle');
		return $query->row_array();
	}

	public function insertslots($data)
	{
		$this->db->insert('slots', $data);
		return true;
	}
	public function insertslotsdoctor($data)
	{
		$this->db->insert('slots_doctor', $data);
		return true;
	}

	public function searchslots($doctor_name, $shifting)
	{
		$this->db->select('s.*');
		$this->db->from('slots as s');
		$this->db->where('s.doctor', $doctor_name);
		$this->db->where('s.shift', $shifting);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function slotsdoctordata($dcotor)
	{
		$this->db->select('s.*');
		$this->db->from('slots as s');
		$this->db->where('s.doctor', $dcotor);
		$query = $this->db->get();
		return $query->row_array();
	}
	public function opdpatientprofile($patient_id)
	{
		$this->db->select('o.*,p.patient_name,p.patient_photo,p.gender,p.age,p.guardian_name,p.phone,u.first_name,u.last_name,u.photo');
		$this->db->from('opd as o');
		$this->db->join('patient p', 'o.patient_id = p.patient_id', 'left');
		$this->db->join('users u', 'o.consult_doctor = u.users_id', 'left');
		$this->db->where('o.patient_id', $patient_id);
		$query = $this->db->get();
		return $query->row_array();
	}
	public function opdprofile($opd_id)
	{
		$this->db->select('o.*,p.patient_name,p.patient_photo,p.gender,p.age,p.guardian_name,p.phone,u.first_name,u.last_name,u.photo');
		$this->db->from('opd as o');
		$this->db->join('patient p', 'o.patient_id = p.patient_id', 'left');
		$this->db->join('users u', 'o.consult_doctor = u.users_id', 'left');
		$this->db->where('o.opd_id', $opd_id);
		$query = $this->db->get();
		return $query->row_array();
	}
	public function getprescription($opd_id)
	{
		$this->db->select('pre.*,p.patient_name,p.patient_id,p.patient_photo,p.gender,p.age,p.guardian_name,p.phone,p.blood_group,u.first_name,u.last_name,u.photo');
		$this->db->from('prescription as pre');
		$this->db->join('opd o', 'pre.opd_id = o.opd_id', 'left');
		$this->db->join('patient p', 'o.patient_id = p.patient_id', 'left');
		$this->db->join('users u', 'o.consult_doctor = u.users_id', 'left');
		$this->db->where('pre.opd_id', $opd_id);
		$query = $this->db->get();
		return $query->row_array();
	}
	public function opdpatientprofileall($patient_id)
	{
		$this->db->select('o.*,p.patient_name,p.patient_photo,p.gender,p.age,p.guardian_name,p.phone,u.first_name,u.last_name,u.users_id,u.photo');
		$this->db->from('opd as o');
		$this->db->join('patient p', 'o.patient_id = p.patient_id', 'left');
		$this->db->join('users u', 'o.consult_doctor = u.users_id', 'left');
		$this->db->where('o.patient_id', $patient_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getstaffprofile($user_id)
	{
		$this->db->select('u.*,d.d_name,dpart.dpart_name,spec.spec_name,p.name');
		$this->db->from('users as u');
		$this->db->join('designations d', 'u.designation = d.designation_id', 'left');
		$this->db->join('department dpart', 'u.department = dpart.department_id', 'left');
		$this->db->join('specialist spec', 'u.specialist = spec.specialist_id', 'left');
		$this->db->join('products p', 'u.blood_group = p.product_id', 'left');
		$this->db->where('u.users_id', $user_id);
		$query = $this->db->get();
		return $query->row_array();
	}
	public function ipdpatientprofile($patient_id)
	{
		$this->db->select('ip.*,p.patient_name,p.patient_photo,p.gender,p.age,p.guardian_name,p.phone,u.first_name,u.last_name,u.photo');
		$this->db->from('ipd as ip');
		$this->db->join('patient p', 'ip.patient_id = p.patient_id', 'left');
		$this->db->join('users u', 'ip.consult_doctor = u.users_id', 'left');
		$this->db->where('ip.patient_id', $patient_id);
		$query = $this->db->get();
		return $query->row_array();
	}

	public function getchargeamount($charge)
	{
		$this->db->select('c.*');
		$this->db->from('charges as c');
		$this->db->where('c.charges_id', $charge);
		$query = $this->db->get();
		return $query->row_array();
	}
	public function getslotdetail($doctor)
	{
		$this->db->select('sdetail.*');
		$this->db->from('slots_doctor as sdetail');
		$this->db->where('sdetail.doctor', $doctor);
		$query = $this->db->get();
		return $query->row_array();
	}
	public function getpatient($patient_id)
	{
		$this->db->select('p.*,bloodgrp.name');
		$this->db->from('patient as p');
		$this->db->join('products bloodgrp', 'p.blood_group = bloodgrp.product_id', 'left');
		$this->db->where('p.patient_id', $patient_id);
		$query = $this->db->get();
		return $query->row_array();
	}
	public function getbloodissue($ib_id)
	{
		$this->db->where('ib_id', $ib_id);
		$query = $this->db->get('issueblood');
		return $query->row_array();
	}
	public function parameterdata($parameter_name)
	{
		$this->db->where('parameter_id', $parameter_name);
		$query = $this->db->get('parameter');
		return $query->row_array();
	}
	public function parameterdataradio($parameter_name)
	{
		$this->db->where('radio_parameter_id', $parameter_name);
		$query = $this->db->get('radio_parameter');
		return $query->row_array();
	}
	public function getopdpatient($opd_id)
	{
		$this->db->where('opd_id', $opd_id);
		$query = $this->db->get('opd');
		return $query->row_array();
	}

	public function getallopdpatient($patient_id)
	{
		$this->db->select('o.*,p.patient_id,p.patient_name,p.guardian_name,p.gender,p.phone,p.martial_status,p.email,p.address,pr.name,p.age,p.any_known_allergies,u.first_name,u.last_name,t.nametpa');
		$this->db->from('opd as o');
		$this->db->join('patient p', 'o.patient_id = p.patient_id', 'left');
		$this->db->join('users u', 'o.consult_doctor = u.users_id', 'left');
		$this->db->join('products pr', 'p.blood_group = pr.product_id', 'left');
		$this->db->join('tpa t', 'o.tpa = t.tpa_id', 'left');
		$this->db->where('o.patient_id', $patient_id);
		$this->db->order_by('o.opd_id', 'desc');
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getslotsdata($shifting1)
	{
		$this->db->select('s.*');
		$this->db->from('slots as s');
		$this->db->where('s.shift', $shifting1);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function deleteslots($slots_id)
	{
		$this->db->where('slots_id', $slots_id);
		$this->db->delete('slots');
		return true;
	}
	public function deleteissueitem($issueitem_id)
	{
		$this->db->where('issueitem_id', $issueitem_id);
		$this->db->delete('issueitem');
		return true;
	}
	public function deletepaymentpath($path_transc_id)
	{
		$this->db->where('path_transc_id', $path_transc_id);
		$this->db->delete('transaction_path');
		return true;
	}
	public function deletepaymentpharma($pharma_transc_id)
	{
		$this->db->where('pharma_transc_id', $pharma_transc_id);
		$this->db->delete('transaction_pharma');
		return true;
	}
	public function deletepaymentradio($radio_transc_id)
	{
		$this->db->where('radio_transc_id', $radio_transc_id);
		$this->db->delete('transaction_radio');
		return true;
	}
	public function deletepaymenbloodissue($issueblood_transc_id)
	{
		$this->db->where('issueblood_transc_id', $issueblood_transc_id);
		$this->db->delete('transaction_issueblood');
		return true;
	}
	public function deletepaymencomponentissue($compissue_transc_id)
	{
		$this->db->where('compissue_transc_id', $compissue_transc_id);
		$this->db->delete('transaction_compissue');
		return true;
	}

	public function insertopdout($data)
	{
		$this->db->insert('opd_outpatient', $data);
		return true;
	}


	function generateUsername()
	{
		return 'user_' . uniqid();
	}

	function generatePassword()
	{
		return password_hash(uniqid(), PASSWORD_BCRYPT);
	}

	public function getAppointments($doctorId, $appointmentDate)
	{
		$this->db->select('patient.patient_name, patient.gender, patient.phone, appointment.live_consult, appointment.doctor_fee, appointment.status, appointment.appointment_date');
		$this->db->from('appointment');
		$this->db->join('users', 'users.users_id = appointment.doctor', 'inner');
		$this->db->join('patient', 'patient.patient_id = appointment.patient', 'inner');
		$this->db->where('appointment.doctor', $doctorId);
		$this->db->where('appointment.appointment_date', $appointmentDate);

		$query = $this->db->get();

		return $query->result();
	}
	public function getQueue($doctorId, $appointmentDate, $shiftId, $slotId)
	{
		$this->db->select('patient.patient_name, patient.gender, patient.phone, appointment.live_consult, appointment.doctor_fee, appointment.status, appointment.appointment_date');
		$this->db->from('appointment');
		$this->db->join('users', 'users.users_id = appointment.doctor', 'inner');
		$this->db->join('patient', 'patient.patient_id = appointment.patient', 'inner');
		$this->db->where('appointment.doctor', $doctorId);
		$this->db->where('appointment.appointment_date', $appointmentDate);
		$this->db->where('appointment.doctor', $doctorId);
		$this->db->where('appointment.appointment_date', $appointmentDate);
		// $this->db->where('appointment.shift_id', $shiftId);
		// $this->db->where('appointment.slot_id', $slotId);
		$query = $this->db->get();

		return $query->result();
	}

	public function insertMedicines($data)
	{
		$this->db->insert_batch('medicine_stock', $data);
		if ($this->db->affected_rows() > 0) {
			return 1;
		} else {
			return 0;
		}
	}

	// Country
	public function updatecountry($country_id, $data)
	{
		$this->db->where('id', $country_id);
		$this->db->update('countries', $data);
		return true;
	}

	public function insertcountry($data)
	{
		$this->db->insert('countries', $data);
		return true;
	}

	public function deletecountry($country_id)
	{
		$this->db->where('id', $country_id);
		$this->db->delete('countries');
		return true;
	}

	public function getallcountry($country_id = NULL)
	{
		if ($country_id != NULL) {
			$this->db->where('id', $country_id);
		}

		$query = $this->db->get('countries');
		return $query->result_array();
	}
	// End...

	// SuperAdmin Start

	public function getallbranch()
	{
		$query = $this->db->get('branch');
		return $query->result_array();
	}

	public function getalluserreception()
	{
		$this->db->select('u.*,c.city_name,s.shiftname,b.branch_name,b.branch_code');
		$this->db->from('Admins as u');
		$this->db->join('city c', 'u.city = c.city_id', 'left');
		$this->db->join('shift s', 'u.shift_id = s.shift_id', 'left');
		$this->db->join('branch b', 'u.branch = b.branch_id', 'left');
		$this->db->where('u.admin_type', 'Reception');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getallbranchuser()
	{
		$this->db->select('ubranch.*,b.branch_name');
		$this->db->from('userbranch as ubranch');
		$this->db->join('branch b', 'ubranch.branch = b.branch_id', 'left');
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getallusers()
	{
		$this->db->select('u.*,c.city_name,s.shiftname,b.branch_name,b.branch_code');
		$this->db->from('Admins as u');
		$this->db->join('city c', 'u.city = c.city_id', 'left');
		$this->db->join('shift s', 'u.shift_id = s.shift_id', 'left');
		$this->db->join('branch b', 'u.branch = b.branch_id', 'left');
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getallusertechnician()
	{
		$this->db->select('u.*,c.city_name,s.shiftname,b.branch_name,b.branch_code');
		$this->db->from('Admins as u');
		$this->db->join('city c', 'u.city = c.city_id', 'left');
		$this->db->join('shift s', 'u.shift_id = s.shift_id', 'left');
		$this->db->join('branch b', 'u.branch = b.branch_id', 'left');
		$this->db->where('u.admin_type', 'Technician');
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getalluserdoctor()
	{
		$this->db->select('u.*,c.city_name,s.shiftname,b.branch_name,b.branch_code');
		$this->db->from('Admins as u');
		$this->db->join('city c', 'u.city = c.city_id', 'left');
		$this->db->join('shift s', 'u.shift_id = s.shift_id', 'left');
		$this->db->join('branch b', 'u.branch = b.branch_id', 'left');
		$this->db->where('u.admin_type', 'Doctor');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getallcity()
	{
		$query = $this->db->get('city');
		return $query->result_array();
	}

	public function insertusers($data)
	{
		$this->db->insert('Admins', $data);
		return true;
	}
	public function insertusersbranch($data)
	{
		$this->db->insert('userbranch', $data);
		return true;
	}
	public function insertbranch($data)
	{
		$this->db->insert('branch', $data);
		return true;
	}
	public function insertmedicine($data)
	{
		$this->db->insert('medicine', $data);
		return true;
	}

	public function getuser($user)
	{
		$this->db->where('id', $user);
		$query = $this->db->get('Admins');
		return $query->row_array();
	}
	public function getusermedicine($medicine)
	{
		$this->db->where('medicine_id', $medicine);
		$query = $this->db->get('medicine');
		return $query->row_array();
	}
	public function getbranch($branch)
	{
		$this->db->where('branch_id', $branch);
		$query = $this->db->get('branch');
		return $query->row_array();
	}
	public function getbranchuser($userbranch_id)
	{
		$this->db->where('userbranch_id', $userbranch_id);
		$query = $this->db->get('userbranch');
		return $query->row_array();
	}

	public function updateusers($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('Admins', $data);
		return true;
	}
	public function updatebranch($branch_id, $data)
	{
		$this->db->where('branch_id', $branch_id);
		$this->db->update('branch', $data);
		return true;
	}
	public function updatemedicine($id, $data)
	{
		$this->db->where('medicine_id', $id);
		$this->db->update('medicine', $data);
		return true;
	}
	public function updateusersbranch($userbranch_id, $data)
	{
		$this->db->where('userbranch_id', $userbranch_id);
		$this->db->update('userbranch', $data);
		return true;
	}
	public function deleteuser($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('Admins');
		return true;
	}
	public function deletemeidcine($medicine_id)
	{
		$this->db->where('medicine_id', $medicine_id);
		$this->db->delete('medicine');
		return true;
	}
	public function deletebranch($id)
	{
		$this->db->where('branch_id', $id);
		$this->db->delete('branch');
		return true;
	}
	public function deletebranchuser($id)
	{
		$this->db->where('userbranch_id', $id);
		$this->db->delete('userbranch');
		return true;
	}

	// public function getsearch($branch_id = NULL, $status = NULL, $shift = NULL)
	// {
	// 	$this->db->select('p.patient_id,p.patient_id,p.patient_name,p.patient_mro,s.shiftname,p.created_at,p.phone');
	// 	$this->db->from('patient as p');
	// 	$this->db->join('shift s', 'p.patient_shift = s.shift_id', 'left');

	// 	if ($branch_id != NULL) {
	// 		$this->db->where('p.patient_brh', $branch_id);
	// 	}
	// 	if ($shift != NULL) {
	// 		$this->db->where('p.patient_shift', $shift);
	// 	}
	// 	if ($status != NULL) {
	// 		$this->db->where('p.status', $status);
	// 	}


	// 	$query = $this->db->get();
	// 	return $query->result();
	// }

	public function getsearch($branch_id = NULL, $status = NULL, $shift = NULL, $date_from = NULL, $date_to = NULL)
	{
		$sql = "SELECT p.*, s.shiftname, b.branch_name
				FROM patient AS p
				LEFT JOIN shift AS s ON p.patient_shift = s.shift_id
				LEFT JOIN branch AS b ON p.patient_brh = b.branch_id
				WHERE 1";
	
		if ($branch_id !== NULL && $branch_id !== '0') {
			$sql .= " AND p.patient_brh = '$branch_id'";
		}
	
		if ($status !== NULL && $status !== '0') {
			$sql .= " AND p.status = '$status'";
		}
	
		if ($shift !== NULL && $shift !== '0') {
			$sql .= " AND p.patient_shift = '$shift'";
		}
	
		if ($date_from !== NULL) {
			$sql .= " AND DATE(p.created_at) >= DATE('$date_from')";
		}
	
		if ($date_to !== NULL) {
			$sql .= " AND DATE(p.created_at) <= DATE('$date_to')";
		}
	
		$data = $this->db->query($sql);
		return $data->result_array();
	}
	

	public function getsearchbranch($branch_id = NULL, $status = NULL, $shift = NULL)
	{
		$this->db->select('p.patient_id,p.patient_id,p.patient_name,p.patient_mro,s.shiftname,p.created_at,p.phone');
		$this->db->from('patient as p');
		$this->db->join('shift s', 'p.patient_shift = s.shift_id', 'left');

		if ($branch_id != NULL) {
			$this->db->where('p.patient_brh', $branch_id);
		}
		if ($shift != NULL) {
			$this->db->where('p.patient_shift', $shift);
		}
		if ($status != NULL) {
			$this->db->where('p.status', $status);
		}


		$query = $this->db->get();
		return $query->result();
	}
	// public function getsearchappoint($branch_id, $status)
	// {
	// 	$this->db->select('ap.appointment_id,p.patient_name,d.name,ap.slot,ap.date');
	// 	$this->db->from('appointment as ap');
	// 	$this->db->join('patient p', 'ap.patient = p.patient_id', 'left');
	// 	$this->db->join('Admins d', 'ap.doctor = d.id', 'left');
	// 	$this->db->where('ap.branch', $branch_id);
	// 	$this->db->where('ap.status', $status);
	// 	$query = $this->db->get();
	// 	return $query->result();
	// }

	public function getsearchappoint($branch_id = NULL, $status = NULL, $date_from = NULL, $date_to = NULL)
	{
		$sql = "SELECT ap.*, a.name, b.branch_name, p.patient_name, p.patient_mro
				FROM appointment AS ap
				LEFT JOIN patient AS p ON ap.patient = p.patient_id
				LEFT JOIN branch AS b ON ap.branch = b.branch_id
				LEFT JOIN Admins AS a ON ap.doctor = a.id
				WHERE 1";
	
		if ($branch_id !== NULL && $branch_id !== '0') {
			$sql .= " AND ap.branch = '$branch_id'";
		}
	
		if ($status !== NULL && $status !== '0') {
			$sql .= " AND ap.status = '$status'";
		}
	
		if ($date_from !== NULL) {
			$sql .= " AND DATE(ap.date) >= DATE('$date_from')";
		}
	
		if ($date_to !== NULL) {
			$sql .= " AND DATE(ap.date) <= DATE('$date_to')";
		}
	
		$data = $this->db->query($sql);
		return $data->result_array();
	}


	public function getsearchappointbranch($branch_id, $status)
	{
		$this->db->select('ap.appointment_id,p.patient_name,d.name,ap.slot,ap.date');
		$this->db->from('appointment as ap');
		$this->db->join('patient p', 'ap.patient = p.patient_id', 'left');
		$this->db->join('Admins d', 'ap.doctor = d.id', 'left');
		$this->db->where('ap.branch', $branch_id);
		$this->db->where('ap.status', $status);
		$query = $this->db->get();
		return $query->result();
	}

	public function getallmedicine()
	{
		$query = $this->db->get('medicine');
		return $query->result_array();
	}


	// For Dashboard

	public function gettotalpatient($branch = NULL, $shift = NULL)
	{
		$sql = "SELECT 
				p.patient_brh AS branch,
				COUNT(p.patient_id) AS total_patient,
				SUM(CASE WHEN p.status = 'OPD' THEN 1 ELSE 0 END) AS total_opd_patient,
				SUM(CASE WHEN p.status = 'Dialysis' THEN 1 ELSE 0 END) AS total_dialysis_patient,
				SUM(CASE WHEN p.status = 'Closed' THEN 1 ELSE 0 END) AS total_closed_patient
				FROM branch AS b
				LEFT JOIN patient AS p ON p.patient_brh = b.branch_id
				WHERE 1";

		if ($branch != NULL) {
			$sql .= " AND p.patient_brh = '$branch'";
		}

		if ($shift != NULL) {
			$sql .= " AND p.patient_shift = '$shift'";
		}

		// $sql .= " GROUP BY b.branch_id";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function gettotalpatientwaiting($branch = NULL, $shift = NULL)
	{
		$sql = "SELECT 
				p.branch AS branch,
				COUNT(p.id) AS total_patient_waiting
				FROM branch AS b
				LEFT JOIN patient_waiting AS p ON p.branch = b.branch_id
				WHERE 1";

		if ($branch != NULL) {
			$sql .= " AND p.branch = '$branch'";
		}

		if ($shift != NULL) {
			$sql .= " AND p.shift = '$shift'";
		}

		// $sql .= " GROUP BY b.branch_id";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function gettotalappointment($branch = NULL)
	{
		$sql = "SELECT 
				p.branch AS branch,
				COUNT(p.appointment_id) as total_appointment,
				SUM(CASE WHEN p.status = 'Pending Confirmation' THEN 1 ELSE 0 END) AS total_pending_confirmation,
				SUM(CASE WHEN p.status = 'Confirmed' THEN 1 ELSE 0 END) AS total_confirmed,
				SUM(CASE WHEN p.status = 'Treated' THEN 1 ELSE 0 END) AS total_treated,
				SUM(CASE WHEN p.status = 'Requested' THEN 1 ELSE 0 END) AS total_requested,
				SUM(CASE WHEN p.status = 'Cancelled' THEN 1 ELSE 0 END) AS total_cancelled
				FROM branch AS b
				LEFT JOIN appointment AS p ON p.branch = b.branch_id
				WHERE 1";

		if ($branch != NULL) {
			$sql .= " AND p.branch = '$branch'";
		}

		// if($shift != NULL){
		// 	$sql .= " AND p.shift = '$shift'";
		// }

		// $sql .= " GROUP BY b.branch_id";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function gettotalbranch()
	{
		$sql = "SELECT COUNT(*) AS total_branch FROM branch";
		$query = $this->db->query($sql);
		return $query->roW_array();
	}

	public function gettotalbed($branch = NULL)
	{
		$sql = "SELECT 
				p.branch AS branch,
				COUNT(p.bed_id) as total_bed,
				SUM(CASE WHEN p.status = 'Alloted' THEN 1 ELSE 0 END) AS total_alloted,
				SUM(CASE WHEN p.status = 'Available' THEN 1 ELSE 0 END) AS total_available
				FROM branch AS b
				LEFT JOIN bed AS p ON p.branch = b.branch_id
				WHERE 1";

		if ($branch != NULL) {
			$sql .= " AND p.branch = '$branch'";
		}

		// if($shift != NULL){
		// 	$sql .= " AND p.shift = '$shift'";
		// }

		// $sql .= " GROUP BY b.branch_id";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function gettotaluser($branch = NULL, $shift = NULL)
	{
		$sql = "SELECT 
				p.branch AS branch,
				COUNT(p.id) as total_user,
				SUM(CASE WHEN p.admin_type = 'Doctor' THEN 1 ELSE 0 END) AS total_doctor,
				SUM(CASE WHEN p.admin_type = 'Reception' THEN 1 ELSE 0 END) AS total_reception,
				SUM(CASE WHEN p.admin_type = 'Technician' THEN 1 ELSE 0 END) AS total_technician
				FROM branch AS b
				LEFT JOIN Admins AS p ON p.branch = b.branch_id
				WHERE 1";

		if ($branch != NULL) {
			$sql .= " AND p.branch = '$branch'";
		}

		if ($shift != NULL) {
			$sql .= " AND p.shift_id = '$shift'";
		}

		// $sql .= " GROUP BY b.branch_id";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function gettotallabreport($branch = NULL)
	{
		$sql = "SELECT 
				p.branch AS branch,
				COUNT(p.lab_id) as total_lab_report
				FROM branch AS b
				LEFT JOIN lab AS p ON p.branch = b.branch_id
				WHERE 1";

		if ($branch != NULL) {
			$sql .= " AND p.branch = '$branch'";
		}

		// if($shift != NULL){
		// 	$sql .= " AND p.shift = '$shift'";
		// }

		// $sql .= " GROUP BY b.branch_id";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function gettotalcase($branch = NULL)
	{
		$sql = "SELECT 
					p.branch AS branch,
					COUNT(p.case_id) AS total_case
				FROM branch AS b
				LEFT JOIN `case` AS p ON p.branch = b.branch_id
				WHERE 1";

		if ($branch != NULL) {
			$sql .= " AND p.branch = '$branch'";
		}

		// if($shift != NULL){
		//     $sql .= " AND p.shift = '$shift'";
		// }

		// $sql .= " GROUP BY b.branch_id";

		$query = $this->db->query($sql);
		return $query->result_array();
	}


	public function gettotalprescription($branch = NULL)
	{
		$sql = "SELECT 
				p.branch AS branch,
				COUNT(p.prescription_id) as total_prescription
				FROM branch AS b
				LEFT JOIN prescription AS p ON p.branch = b.branch_id
				WHERE 1";

		if ($branch != NULL) {
			$sql .= " AND p.branch = '$branch'";
		}

		// if($shift != NULL){
		//     $sql .= " AND p.shift = '$shift'";
		// }

		// $sql .= " GROUP BY b.branch_id";

		$query = $this->db->query($sql);
		return $query->result_array();
	}


	// Branch Admin start

	public function getalluserbranch($branch)
	{
		$this->db->select('u.*,c.city_name,s.shiftname,b.branch_name,b.branch_code');
		$this->db->from('Admins as u');
		$this->db->join('city c', 'u.city = c.city_id', 'left');
		$this->db->join('shift s', 'u.shift_id = s.shift_id', 'left');
		$this->db->join('branch b', 'u.branch = b.branch_id', 'left');
		$this->db->where('u.branch', $branch);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getalluserbranchreception($branch)
	{
		$this->db->select('u.*,c.city_name,s.shiftname,b.branch_name,b.branch_code');
		$this->db->from('Admins as u');
		$this->db->join('city c', 'u.city = c.city_id', 'left');
		$this->db->join('shift s', 'u.shift_id = s.shift_id', 'left');
		$this->db->join('branch b', 'u.branch = b.branch_id', 'left');
		$this->db->where('u.admin_type', 'Reception');
		$this->db->where('u.branch', $branch);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getalluserbranchtechnician($branch)
	{
		$this->db->select('u.*,c.city_name,s.shiftname,b.branch_name,b.branch_code');
		$this->db->from('Admins as u');
		$this->db->join('city c', 'u.city = c.city_id', 'left');
		$this->db->join('shift s', 'u.shift_id = s.shift_id', 'left');
		$this->db->join('branch b', 'u.branch = b.branch_id', 'left');
		$this->db->where('u.admin_type', 'Technician');
		$this->db->where('u.branch', $branch);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getalluserbranchdoctor($branch)
	{
		$this->db->select('u.*,c.city_name,s.shiftname,b.branch_name,b.branch_code');
		$this->db->from('Admins as u');
		$this->db->join('city c', 'u.city = c.city_id', 'left');
		$this->db->join('shift s', 'u.shift_id = s.shift_id', 'left');
		$this->db->join('branch b', 'u.branch = b.branch_id', 'left');
		$this->db->where('u.admin_type', 'Doctor');
		$this->db->where('u.branch', $branch);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getuserbranch($id)
	{
		$this->db->where('userbranch_id', $id);
		$query = $this->db->get('userbranch');
		return $query->row_array();
	}
	public function getuseradmin($id)
	{
		$this->db->where('administrator_id', $id);
		$query = $this->db->get('administrator');
		return $query->row_array();
	}

	public function updateprofilebranch($id, $data)
	{
		$this->db->where('userbranch_id', $id);
		$this->db->update('userbranch', $data);
		return true;
	}
	public function updateprofileadmin($id, $data)
	{
		$this->db->where('administrator_id', $id);
		$this->db->update('administrator', $data);
		return true;
	}
}