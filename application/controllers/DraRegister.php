<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class DraRegister extends CI_Controller
	{
    public function __construct()
    {
			parent::__construct();
			$this->load->model('Master_model');
			$this->load->library('upload');
			$this->load->helper('upload_helper');
			$this->load->helper('master_helper');
			$this->load->library('email');
			$this->load->model('Emailsending');
			$this->load->model('log_model');
			$this->load->helper('general_agency_helper');
			$this->load->model('Log_agency_model');
			$this->load->model('UserModel');
			$this->load->model('billdesk_pg_model');
			//exit;
		}
    public function index()
    {	
			if (count($_POST) > 0 ) {
				$date = date('Y-m-d H:i:s');
				
				// Added by Manoj Now add location Name as City  
				if($this->input->post('city') != '' )
				{
					$_POST["location_name"] =  $this->input->post('city');
				}
				$this->form_validation->set_rules('inst_name', 'Name of Bank/Institute', 'trim|required|xss_clean');
				$this->form_validation->set_rules('estb_year', 'Year of establishment ', 'trim|required|xss_clean');
				$this->form_validation->set_rules('main_address1', 'Addressline1', 'trim|max_length[75]|required|xss_clean');
				if (isset($_POST['main_address2']) && $_POST['main_address2'] != '') {
					$this->form_validation->set_rules('main_address2', 'Addressline2', 'trim|max_length[75]|required|xss_clean');
				}
				if (isset($_POST['main_address3']) && $_POST['main_address3'] != '') {
					$this->form_validation->set_rules('main_address3', 'Addressline3', 'trim|max_length[75]|required|xss_clean');
				}
				if (isset($_POST['main_address4']) && $_POST['main_address4'] != '') {
					$this->form_validation->set_rules('main_address4', 'Addressline4', 'trim|max_length[75]|required|xss_clean');
				}
				$this->form_validation->set_rules('main_district', 'District', 'trim|max_length[30]|required|xss_clean');
				$this->form_validation->set_rules('main_city', 'City', 'trim|max_length[30]|required|xss_clean');
				$this->form_validation->set_rules('main_state', 'State', 'trim|required|xss_clean');
				if ($this->input->post('main_state') != '') {
					$main_state = $this->input->post('main_state');
				}
				$this->form_validation->set_rules('pincode', 'Pincode/Zipcode', 'trim|required|numeric|xss_clean|callback_check_checkpin[' . $main_state . ']');
				// $this->form_validation->set_rules('inst_stdcode', 'STD code ', 'trim|required|xss_clean');
				// $this->form_validation->set_rules('inst_phone', 'Telephone No of the Institute ', 'trim|required|xss_clean');
				$this->form_validation->set_rules('inst_fax_no', 'Fax no of the Institute ', 'trim|xss_clean');
				$this->form_validation->set_rules('inst_website', 'Website address ', 'trim|xss_clean');
				$this->form_validation->set_rules('inst_head_name', 'Name of director/head of the Institute', 'trim|required|xss_clean');
				$this->form_validation->set_rules('inst_head_contact_no', 'Mobile no of Head of the institute', 'trim|required|xss_clean');
				$this->form_validation->set_rules('inst_head_email', 'Email id of the head of the institute', 'trim|required|xss_clean');


				$this->form_validation->set_rules('inst_altr_person_name', 'Name of altername contact person of the Institute', 'trim|required|xss_clean');
				$this->form_validation->set_rules('inst_alter_contact_no', 'Mobile no of altername contact person of the institute', 'trim|required|xss_clean');
				$this->form_validation->set_rules('inst_altr_email', 'Email id of altername contact person of the institute', 'trim|required|xss_clean');


				// validation comment by Manoj
				//$this->form_validation->set_rules('location_name', 'Name of Location', 'trim|required|xss_clean');
				$this->form_validation->set_rules('addressline1', 'Addressline1', 'trim|max_length[30]|required|xss_clean');
				if (isset($_POST['addressline2']) && $_POST['addressline2'] != '') {
					$this->form_validation->set_rules('addressline2', 'Addressline2', 'trim|max_length[30]|required|xss_clean');
				}
				if (isset($_POST['addressline3']) && $_POST['addressline3'] != '') {
					$this->form_validation->set_rules('addressline3', 'Addressline3', 'trim|max_length[30]|required|xss_clean');
				}
				if (isset($_POST['addressline4']) && $_POST['addressline4'] != '') {
					$this->form_validation->set_rules('addressline4', 'Addressline4', 'trim|max_length[30]|required|xss_clean');
				}
				$this->form_validation->set_rules('district', 'District', 'trim|max_length[30]|required|xss_clean');
				$this->form_validation->set_rules('city', 'City', 'trim|max_length[30]|required|xss_clean');
				$this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean');
				if ($this->input->post('state') != '') {
					$state = $this->input->post('state');
				}
				$this->form_validation->set_rules('pincode', 'Pincode/Zipcode', 'trim|required|numeric|xss_clean|callback_check_checkpin[' . $state . ']');
				
				// $this->form_validation->set_rules('stdcode', 'STD Code Number', 'trim|required|xss_clean');
				// $this->form_validation->set_rules('office_no', 'Office Number', 'trim|required|xss_clean');
				$this->form_validation->set_rules('contact_person_name', 'Contact Person Name', 'trim|required|xss_clean');
				$this->form_validation->set_rules('contact_person_mobile', 'Mobile Number', 'trim|required|xss_clean');
				$this->form_validation->set_rules('email_id', 'Email id', 'trim|required|xss_clean');
				$this->form_validation->set_rules('center_type', 'Center Type', 'trim|required|xss_clean');
				$this->form_validation->set_rules('due_diligence', 'Due diligence', 'trim|xss_clean');
				$this->form_validation->set_rules('gstin_no', 'GSTIN No', 'trim|xss_clean');
				// $this->form_validation->set_rules('invoice_flag', 'Please select the address checkbox', 'trim|xss_clean');
				
				if ($this->form_validation->run() == TRUE) {
					$data_arr = array(
					'inst_name' => $_POST['inst_name'],
					'estb_year' => $_POST['estb_year'],
					'main_address1' => $_POST["main_address1"],
					'main_address2' => $_POST["main_address2"],
					'main_address3' => $_POST["main_address3"],
					'main_address4' => $_POST["main_address4"],
					'main_district' => substr($_POST["main_district"], 0, 30),
					'main_city' => $_POST["main_city"],
					'main_state' => $_POST["main_state"],
					'main_pincode' => $_POST["main_pincode"],
					
					'inst_stdcode' => $_POST['inst_stdcode'],
					'inst_phone' => $_POST['inst_phone'],
					'inst_fax_no' => $_POST['inst_fax_no'],
					'inst_website' => $_POST['inst_website'],
					'inst_head_name' => $_POST['inst_head_name'],
					'inst_head_contact_no' => $_POST['inst_head_contact_no'],
					'inst_head_email' => $_POST['inst_head_email'],
					'inst_altr_person_name' => $_POST['inst_altr_person_name'],
					'inst_alter_contact_no' => $_POST['inst_alter_contact_no'],
					'inst_altr_email' => $_POST['inst_altr_email'],
					'location_name' => $_POST['location_name'],
					'addressline1' => $_POST["addressline1"],
					'addressline2' => $_POST["addressline2"],
					'addressline3' => $_POST["addressline3"],
					'addressline4' => $_POST["addressline4"],
					'district' => substr($_POST["district"], 0, 30),
					'city' => $_POST["city"],
					'state' => $_POST["state"],
					'pincode' => $_POST["pincode"],					
					'stdcode' => $_POST['stdcode'],
					'office_no' => $_POST['office_no'],
					'contact_person_name' => $_POST['contact_person_name'],
					'contact_person_mobile' => $_POST['contact_person_mobile'],
					'email_id' => $_POST['email_id'],
					'center_type' => $_POST['center_type'],
					'due_diligence' => @$_POST['due_diligence'],
					'gstin_no' => @$_POST['gstin_no'],
					'remarks' => $_POST['remarks'], 					
					'invoice_flag' => 'CS', // Agency state => AS, Center State => CS
					'agency_type' => $_POST['agency_type']
					);
					$this->session->set_userdata('userinfo', $data_arr);
					$this->form_validation->set_message('error', "");
					
					/* User Log Activities  */
					$log_title ="Dra Register Session Data";
					$log_data = serialize($data_arr);
					$user_id = 0;
					storedDraActivity($log_title, $log_data, $user_id);
					
					redirect(base_url() . 'DraRegister/preview');
				}
			}
			//cpatcha generation
			$this->load->helper('captcha');
			$this->session->set_userdata("regcaptcha", rand(1, 100000));
			$vals                   = array(
			'img_path' => './uploads/applications/',
			'img_url' => base_url() . 'uploads/applications/'
			);
			$cap                    = create_captcha($vals);
			$_SESSION["regcaptcha"] = $cap['word'];
			$this->db->where('state_master.state_delete', '0');
			$states = $this->master_model->getRecords('state_master');
			$this->db->where('city_master.city_delete', '0');
			$cities = $this->master_model->getRecords('city_master');
			
			$data   = array(
			'middle_content' => 'DRA/institute_register',
			'image' => $cap['image'],
			'states' => $states,
			'cities' => $cities
			);
			$this->load->view('common_view_fullwidth', $data);
		}
    public function preview()
    {
			if (!$this->session->userdata('userinfo')) {
				redirect(base_url());
			}
			$this->db->where('state_master.state_delete', '0');
			$states = $this->master_model->getRecords('state_master');
			$this->db->where('city_master.city_delete', '0');
			$cities = $this->master_model->getRecords('city_master');
			$data   = array(
			'middle_content' => 'DRA/preview_draregister',
			'states' => $states,
			'cities' => $cities
			);
			$this->load->view('common_view_fullwidth', $data);
		}
    public function register()
    {
			if (!$this->session->userdata['userinfo']) {
				redirect(base_url());
			}
			// Stored Data in agency table (dra_inst_registration)
			$data_array = array(
			'inst_name' => $this->session->userdata['userinfo']['inst_name'],
			'estb_year' => $this->session->userdata['userinfo']['estb_year'],
			'main_address1' => strtoupper($this->session->userdata['userinfo']['main_address1']),
			'main_address2' => strtoupper($this->session->userdata['userinfo']['main_address2']),
			'main_address3' => strtoupper($this->session->userdata['userinfo']['main_address3']),
			'main_address4' => strtoupper($this->session->userdata['userinfo']['main_address4']),
			'main_district' => strtoupper($this->session->userdata['userinfo']['main_district']),
			'main_city' => strtoupper($this->session->userdata['userinfo']['main_city']),
			'main_state' => $this->session->userdata['userinfo']['main_state'],
			'main_pincode' => $this->session->userdata['userinfo']['main_pincode'],			
			'inst_stdcode' => $this->session->userdata['userinfo']['inst_stdcode'],
			'inst_phone' => $this->session->userdata['userinfo']['inst_phone'],
			'inst_fax_no' => $this->session->userdata['userinfo']['inst_fax_no'],
			'inst_website' => $this->session->userdata['userinfo']['inst_website'],
			'inst_head_name' => $this->session->userdata['userinfo']['inst_head_name'],
			'inst_head_contact_no' => $this->session->userdata['userinfo']['inst_head_contact_no'],
			'inst_head_email' => $this->session->userdata['userinfo']['inst_head_email'],
			'inst_altr_person_name' => $this->session->userdata['userinfo']['inst_altr_person_name'],
			'inst_alter_contact_no' => $this->session->userdata['userinfo']['inst_alter_contact_no'],
			'inst_altr_email' => $this->session->userdata['userinfo']['inst_altr_email'],
			'created_on' => date('Y-m-d H:i:s')
			);
			// Stored Data in Dra old table
			if ($last_id = $this->master_model->insertRecord('dra_inst_registration', $data_array, true)) 
			{
				/* User Log Activities  */
				$log_title ="Insert in dra_inst_registration table";
				$log_data = serialize($data_array);
				$user_id = 0;
				storedDraActivity($log_title, $log_data, $user_id);
				
				$old_data_array = array(
				'dra_inst_registration_id' => $last_id,
				'agency_type' => $this->session->userdata['userinfo']['agency_type'],
				'institute_name' => $this->session->userdata['userinfo']['inst_name'],
				'address1' => strtoupper($this->session->userdata['userinfo']['main_address1']),
				'address2' => strtoupper($this->session->userdata['userinfo']['main_address2']),
				'address3' => strtoupper($this->session->userdata['userinfo']['main_address3']),
				'address4' => strtoupper($this->session->userdata['userinfo']['main_address4']),
				'address5' => strtoupper($this->session->userdata['userinfo']['main_district']),
				'address6' => strtoupper($this->session->userdata['userinfo']['main_city']),
				'ste_code' => $this->session->userdata['userinfo']['main_state'],
				'pin_code' => $this->session->userdata['userinfo']['main_pincode'],
				'inst_stdcode' => $this->session->userdata['userinfo']['inst_stdcode'],
				'phone' => $this->session->userdata['userinfo']['inst_phone'],
				'mobile' => $this->session->userdata['userinfo']['inst_fax_no'],
				'coord_name ' => $this->session->userdata['userinfo']['inst_head_name'],
				'email' => $this->session->userdata['userinfo']['inst_head_email'],			
				'created_on' => date('Y-m-d H:i:s')
				);
				
				// Stored Data in agency center table
				if ($this->master_model->insertRecord('dra_accerdited_master', $old_data_array, true)) 
				{
					/* User Log Activities  */
					$log_title ="Insert in dra_accerdited_master table";
					$log_data = serialize($old_data_array);
					$user_id = 0;
					storedDraActivity($log_title, $log_data, $user_id);
					
					// Added by Manoj Now add location Name as City  
					if($this->session->userdata['userinfo']['city'] != '' ){
						$_POST["location_name"] =  $this->session->userdata['userinfo']['city'];
					}
					
					
        			$dra_accerdited_data = $this->master_model->getRecords('state_master sm',array('sm.state_code'=>$this->session->userdata['userinfo']['main_state']),'sm.state_name');

					$center_data_array = array(
					'agency_id' => $last_id,
					'location_name' => $this->session->userdata['userinfo']['location_name'],
					'address1' => strtoupper($this->session->userdata['userinfo']['addressline1']),
					'address2' => strtoupper($this->session->userdata['userinfo']['addressline2']),
					'address3' => strtoupper($this->session->userdata['userinfo']['addressline3']),
					'address4' => strtoupper($this->session->userdata['userinfo']['addressline4']),
					'district' => strtoupper($this->session->userdata['userinfo']['district']),
					'city' => strtoupper($this->session->userdata['userinfo']['city']),
					'state' => $this->session->userdata['userinfo']['state'],
					'pincode' => $this->session->userdata['userinfo']['pincode'],					
					'stdcode' => $this->session->userdata['userinfo']['stdcode'],
					'office_no' => $this->session->userdata['userinfo']['office_no'],
					'contact_person_name' => $this->session->userdata['userinfo']['contact_person_name'],
					'contact_person_mobile' => $this->session->userdata['userinfo']['contact_person_mobile'],
					'email_id' => $this->session->userdata['userinfo']['email_id'],
					'due_diligence' => $this->session->userdata['userinfo']['due_diligence'],
					'gstin_no' => $this->session->userdata['userinfo']['gstin_no'],
					'remarks' => $this->session->userdata['userinfo']['remarks'],
					'center_type' => 'R',
					'center_add_status' => 'F',
					'created_on' => date('Y-m-d H:i:s'),
					'invoice_flag' => $this->session->userdata['userinfo']['invoice_flag'],
            		'check_city_state_for_active' => $dra_accerdited_data[0]['state_name']
					);
					
					/*echo "<pre>";
						print_r($center_data_array);
					exit;*/
					
					// invoice_flag : Agency state => AS, Center State => CS
					$agency_center_id = $this->master_model->insertRecord('agency_center', $center_data_array, true);
					
					/* User Log Activities  */
					$log_title ="Insert in agency_center table";
					$log_data = serialize($center_data_array);
					$user_id = 0;
					storedDraActivity($log_title, $log_data, $user_id);
					
					$userarr = array(
					'agency_id' => $last_id, //Primary Key of dra_inst_registration
					'center_id' => $agency_center_id, //Primary Key of agency_center
					'center_type' => 'R',
					'invoice_flag' => $this->session->userdata['userinfo']['invoice_flag']
					);
					$this->session->set_userdata('memberdata', $userarr);
					redirect(base_url() . "DraRegister/goToPayment");
				}
				else
				{
					$userarr = array('agency_id' => '','center_id' => '','center_type' => '','invoice_flag' => '');
					$this->session->set_userdata('memberdata', $userarr);
					$this->session->set_flashdata('error', 'Error while during registration.please try again!');
					redirect(base_url());
				}
			} 
			else 
			{
				$userarr = array('agency_id' => '','center_id' => '','center_type' => '','invoice_flag' => '');
				$this->session->set_userdata('memberdata', $userarr);
				$this->session->set_flashdata('error', 'Error while during registration.please try again!');
				redirect(base_url());
			}
		}
		

	public function goToPayment()
	{	
		if (!$this->session->userdata('userinfo')) {
			redirect(base_url('/DraRegister'));
		}

		$agency_type = $this->session->userdata['userinfo']['agency_type'];

		if ($agency_type == 'BANK') {
			// For Banking Institute fee
		$baseAmount  = $this->config->item('DRA_regular_apply_fee');
		$totalAmount = $this->config->item('DRA_regular_cs_total');
		} else {
			// For Non Banking Institute fee
			$baseAmount  = $this->config->item('DRA_regular_apply_with_dilligance_fee');
			$totalAmount = $this->config->item('DRA_regular_apply_with_dilligance_cs_total');
		}

		$data['baseAmount']     = $baseAmount;
		$data['tot_fee']        = base64_encode($totalAmount);
		$data["middle_content"] = 'institute_make_online_payment_page';

		$this->load->view('common_view_fullwidth', $data);
	}
	

    public function make_payment()
    {
			$cgst_rate = $sgst_rate  = $igst_rate = $tax_type = '';
			$cgst_amt  = $sgst_amt   = $igst_amt = '';
			$cs_total  = $igst_total = '';
			$getstate  = $getcenter  = $getfees = array();
			$flag      = 1;
			$state_code = $gstin_no = '';
			
			$agency_id = $this->session->userdata['memberdata']['agency_id']; // Primary Key of dra_inst_registration table
			$center_id = $this->session->userdata['memberdata']['center_id']; // Primary Key of agency_center table
			$center_type = $this->session->userdata['memberdata']['center_type']; // Type of center
			$invoice_flag = $this->session->userdata['memberdata']['invoice_flag']; //Agency state => AS,Center State => CS
			
			//added by swati...............
			$inst_result = $this->master_model->getRecords('agency_center',array('center_id'=>$center_id),array('institute_code'));
			$institute_code = $inst_result[0]['institute_code']; 
			$institute_result = $this->master_model->getRecords('dra_inst_registration',array('id'=>$agency_id),array('inst_name'));
			$institute_name = $institute_result[0]['inst_name'];
			
			//end by swati.............
			
			if($invoice_flag != "")
			{
				if($invoice_flag == "AS") /* Get State of Agency*/
				{
					if (!empty($agency_id)) 
					{   
						$state_result = $this->master_model->getRecords('dra_inst_registration',array('id'=>$agency_id),array('main_state','gstin_no'));
						$state_code = $state_result[0]['main_state'];
						$gstin_no = $state_result[0]['gstin_no'];
					}
				}
				elseif($invoice_flag == "CS") /* Get State of Center*/
				{
					if (!empty($center_id)) 
					{   
						$state_result = $this->master_model->getRecords('agency_center',array('center_id'=>$center_id),array('state','gstin_no'));
						$state_code = $state_result[0]['state'];
						$gstin_no = $state_result[0]['gstin_no'];
					}
				}
				
			if (isset($_POST['processPayment']) && $_POST['processPayment'] != '') 
				{
				if (!$this->session->userdata('paymentinfo')) {
					redirect(base_url('/DraRegister'));
				}

				$totalAmountWithTds    = $this->session->userdata['paymentinfo']['totalAmountWithTds'];
				$tdsAmount             = $this->session->userdata['paymentinfo']['tdsAmount'];
				$totalAmountWithoutTds = $this->session->userdata['paymentinfo']['totalAmountWithoutTds'];
				$tdsFlag               = $this->session->userdata['paymentinfo']['tdsFlag'];

				if ($tdsFlag == 'No') {
					$tdsType  = 0;					
				} else {
					$tdsType = $this->session->userdata['paymentinfo']['tdsType'];
				}
				
					$pg_name = $this->input->post('pg_name');
					$state          = $state_code;
					$gstin_no       = $gstin_no;
					
					if (!empty($state)) 
					{
						//get state code,state name,state number.
						$getstate = $this->master_model->getRecords('state_master',array('state_code' => $state,'state_delete' => '0'));
					}
					if ($state == 'MAH') 
					{
						if ($center_type == 'R') 
						{
							//set a rate (e.g 9%,9% or 18%)
							$cgst_rate = $this->config->item('DRA_inst_cgst_rate');
							$sgst_rate = $this->config->item('DRA_inst_sgst_rate');
						
						$agency_type = $this->session->userdata['userinfo']['agency_type'];
						if ($agency_type == 'BANK') {
							//set an amount as per rate for banking institute
							$cgst_amt  = $this->config->item('DRA_regular_cgst_amt');
							$sgst_amt  = $this->config->item('DRA_regular_sgst_amt');
						} else {
							//set an amount as per rate for non banking institute
							$cgst_amt  = $this->config->item('DRA_regular_apply_with_dilligance_cgst_amt');
							$sgst_amt  = $this->config->item('DRA_regular_apply_with_dilligance_sgst_amt');
						}	

							//set an total amount
							$tax_type  = 'Intra';
						// $amount    = $this->config->item('DRA_regular_cs_total');
						$amount    = $totalAmountWithTds;
							$cs_total  = $amount;
						} elseif($center_type == 'T') 
						{
							// addedy by manoj For Center type Temporary (or mobile)
							//set a rate (e.g 9%,9% or 18%)
							$cgst_rate = $this->config->item('DRA_inst_cgst_rate');
							$sgst_rate = $this->config->item('DRA_inst_sgst_rate');
							//set an amount as per rate
							$cgst_amt  = $this->config->item('DRA_mobile_cgst_amt');
							$sgst_amt  = $this->config->item('DRA_mobile_sgst_amt');
							//set an total amount
							$tax_type  = 'Intra';
						// $amount    = $this->config->item('DRA_mobile_cs_total');
						$amount    = $totalAmountWithTds;
							$cs_total  = $amount;
						}
					} 
					else 
					{
						if ($center_type == 'R') 
						{
							$igst_rate  = $this->config->item('DRA_inst_igst_rate');
						$agency_type = $this->session->userdata['userinfo']['agency_type'];
						if ($agency_type == 'BANK') {	
							// Set amount for banking institute 
							$igst_amt   = $this->config->item('DRA_regular_igst_amt');
						} else {
							// Set amount for non banking institute 
							$igst_amt   = $this->config->item('DRA_regular_apply_with_dilligance_igst_amt');
						}	
						
							$tax_type   = 'Inter';
						// $amount     = $this->config->item('DRA_regular_igst_tot');
						$amount     = $totalAmountWithTds;
							$igst_total = $amount;
						} elseif ($center_type == 'T') 
						{
							// addedy by manoj For Center type Temporary (or mobile)
							$igst_rate  = $this->config->item('DRA_inst_igst_rate');
							$igst_amt   = $this->config->item('DRA_mobile_igst_amt');
							$tax_type   = 'Inter';
						// $amount     = $this->config->item('DRA_mobile_igst_tot');
						$amount     = $totalAmountWithTds;
							$igst_total = $amount;
						}
					}
					
					// Create transaction
					$pg_flag = 'IIBFDRAREG';  //'IIBF_DRA_REG';
					$insert_data     = array(
					'member_regnumber' => '',
					'gateway' => "billdesk",
					'amount' => $amount,
					'isTDS'  => $tdsFlag,
					'tds_type' => $tdsType,
		          	'tds_amount' => $tdsAmount,
					'date' => date('Y-m-d H:i:s'),
					'ref_id' => $center_id, // Primary Key of agency_center table
					'description' => "DRA Agency Registration",
					'pay_type' => 12,
					'status' => 2,
					'pg_flag' => $pg_flag
					);
					$pt_id           = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
					
					/* Payment logs */
					$log_title ="Insert log of payment_transaction table";
					$log_data = serialize($insert_data);
					$user_id = 0;
					storedDraActivity($log_title, $log_data, $user_id);
					
					$MerchantOrderNo = sbi_exam_order_id($pt_id);
					$custom_field    = $center_id."^iibfregn^".$pg_flag."^".$MerchantOrderNo;
					$custom_field_billdesk    = $center_id."-iibfregn-".$pg_flag."-".$MerchantOrderNo;
					
					// update receipt no. in payment transaction -
					$update_data     = array(
					'receipt_no' => $MerchantOrderNo,
					'pg_other_details' => $custom_field
					);
					$this->master_model->updateRecord('payment_transaction', $update_data, array('id' => $pt_id));
					
					if ($center_type == 'R') 
					{
						$agency_type = $this->session->userdata['userinfo']['agency_type'];
						if ($agency_type == 'BANK') 
						{
							// Fee amount for banking Institutes
							$fee_amt = $this->config->item('DRA_regular_apply_fee');
						} else {
							// Fee amount for non banking Institutes
							$fee_amt = $this->config->item('DRA_regular_apply_with_dilligance_fee');
						}	
					}
					elseif ($center_type == 'T') 
					{	
						// addedy by manoj For Center type Temporary (or mobile)
						$fee_amt = $this->config->item('DRA_mobile_apply_fee');
					}
					
					$invoice_insert_array = array(
					'pay_txn_id' => $pt_id,
					'receipt_no' => $MerchantOrderNo,
					'exam_code' => '',
					'state_of_center' => $state,
					'gstin_no' => $gstin_no,
					'app_type' => 'H', // A for accrediated institute 
					'service_code' => $this->config->item('DRA_inst_service_code'),
					'qty' => '1',
					'state_code' => $getstate[0]['state_no'],
					'state_name' => $getstate[0]['state_name'],
					'tax_type' => $tax_type,
					'fee_amt' => $fee_amt,
					'cgst_rate' => $cgst_rate,
					'cgst_amt' => $cgst_amt,
					'sgst_rate' => $sgst_rate,
					'sgst_amt' => $sgst_amt,
					'igst_rate' => $igst_rate,
					'igst_amt' => $igst_amt,
					'cs_total' => $cs_total,
				'tds_amt'  => $tdsAmount,
					'igst_total' => $igst_total,
					'exempt' => $getstate[0]['exempt'],
					'institute_code' => $institute_code,// by swati
					'institute_name' => $institute_name, // by swati
					'created_on' => date('Y-m-d H:i:s')
					);
					$inser_id             = $this->master_model->insertRecord('exam_invoice', $invoice_insert_array);
					
					/* Exam Invoice logs */
					$log_title ="Insert log of exam_invoice table";
					$log_data = serialize($invoice_insert_array);
					$user_id = 0;
					storedDraActivity($log_title, $log_data, $user_id);
					
					$MerchantCustomerID   = $center_id;
					if($pg_name == 'sbi'){
						include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
						$key = $this->config->item('sbi_m_key');
						$merchIdVal = $this->config->item('sbi_merchIdVal');
						$AggregatorId = $this->config->item('sbi_AggregatorId');
						
						$pg_success_url = base_url()."DraRegister/sbitranssuccess";
						$pg_fail_url    = base_url()."DraRegister/sbitransfail";
						$data["pg_form_url"] = $this->config->item('sbi_pg_form_url'); // SBI ePay form URL
						$data["merchIdVal"]  = $merchIdVal;
						
						$EncryptTrans = $merchIdVal."|DOM|IN|INR|".$amount."|".$custom_field."|".$pg_success_url."|".$pg_fail_url."|".$AggregatorId."|".$MerchantOrderNo."|".$MerchantCustomerID."|NB|ONLINE|ONLINE";
						$aes = new CryptAES();
						$aes->set_key(base64_decode($key));
						$aes->require_pkcs5();
						
						$EncryptTrans = $aes->encrypt($EncryptTrans);
						
						$data["EncryptTrans"] = $EncryptTrans; // SBI encrypted form field value
						$this->load->view('pg_sbi_form',$data);
						
					}
					elseif ($pg_name == 'billdesk')
					{
						
						$update_payment_data = array('gateway' =>'billdesk');
						$this->master_model->updateRecord('payment_transaction',$update_payment_data,array('id'=>$pt_id));
						
						$billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $regno, $regno, '', 'DraRegister/handle_billdesk_response', '', '', '', $custom_field_billdesk);
						
						if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE') {
							$data['bdorderid'] = $billdesk_res['bdorderid'];
							$data['token']     = $billdesk_res['token'];
							$data['responseXHRUrl'] = $billdesk_res['responseXHRUrl']; 
							$data['returnUrl'] = $billdesk_res['returnUrl'];
							$this->load->view('pg_billdesk/pg_billdesk_form', $data);
							}else{
							$this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
							redirect(base_url() . 'DraRegister');
						}
					}
				} 
				else 
				{
				
				$totalAmountWithTds    = base64_decode($_POST['final_amount']);
				$tdsAmount             = base64_decode($_POST['tds_amount']);
				$totalAmountWithoutTds = $totalAmountWithTds+$tdsAmount;
				$tdsFlag               = $_POST['TDS'];
				$tdsType               = isset($_POST['tds_type']) && $_POST['tds_type'] != '' ? $_POST['tds_type'] : 0;

				$paymentArray = [];
				$paymentArray['totalAmountWithTds']    = $totalAmountWithTds;
				$paymentArray['tdsAmount']             = $tdsAmount;
				$paymentArray['totalAmountWithoutTds'] = $totalAmountWithoutTds;
				$paymentArray['tdsFlag']               = $tdsFlag; 
				$paymentArray['tdsType']               = $tdsType; 

				$this->session->set_userdata('paymentinfo', $paymentArray);
					
					$data['show_billdesk_option_flag'] = 1;
					$this->load->view('pg_sbi/make_payment_page', $data);
				}
				
			}
			else
			{
				redirect(base_url());
			}
		}
    
		//Bill desk payment
    public function handle_billdesk_response()
    {
			/* ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
			error_reporting(E_ALL); */
			
			if (isset($_REQUEST['transaction_response']))
			{
				$response_encode = $_REQUEST['transaction_response'];
				$bd_response = $this->billdesk_pg_model->verify_res($response_encode);
				
				$responsedata = $bd_response['payload'];
				
				$MerchantOrderNo = $responsedata['orderid']; // To DO: temp testing changes please remove it and use valid receipt id
				$transaction_no  = $responsedata['transactionid'];
				$merchIdVal = $responsedata['mercid'];
				$Bank_Code = $responsedata['bankid'];
				$encData = $_REQUEST['transaction_response'];
				$auth_status 						= $responsedata['auth_status'];
				
				$get_user_regnum = $this->master_model->getRecords('payment_transaction', array(
				'receipt_no' => $MerchantOrderNo
				), 'ref_id,status,id');
				
				$transaction_error_type = $responsedata['transaction_error_type'];
				$qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);
				
				if($auth_status == "0300" && $qry_api_response['auth_status'] == '0300' && $get_user_regnum[0]['status'] == 2) 
				{
					if (count($get_user_regnum) > 0) 
					{	
						// Get agency_id from agency_center table
						$agency_center_info = $this->master_model->getRecords('agency_center', array(
						'center_id' => $get_user_regnum[0]['ref_id']
						), 'agency_id,center_id'); 
						
						// Get Details from dra_inst_registration table for email and center type
						$user_info = $this->master_model->getRecords('dra_inst_registration', array(
						'id' => $agency_center_info[0]['agency_id']
						), 'id,email_id,inst_head_email,center_type'); 
					}
					
					$agency_id = $agency_center_info[0]['agency_id'];
					$center_id = $agency_center_info[0]['center_id'];
					
					/* Payment Status Updates */
					$update_data1 = array('status' => '1');
					$this->master_model->updateRecord('dra_inst_registration',$update_data1,array('id'=>$agency_id));
					
					$update_data2 = array('pay_status' => '1','center_status'=>'A');
					$this->master_model->updateRecord('agency_center',$update_data2, array('center_id'=>$center_id));
					
					$update_data3 = array('pay_status' => '1');
					$this->master_model->updateRecord('dra_accerdited_master', $update_data3, array('dra_inst_registration_id' => $agency_id));
					
					
					$check_status = $this->master_model->getRecords('dra_accerdited_master',array('dra_inst_registration_id' => $agency_id,'pay_status' => '1'));
					if($check_status[0]['pay_status'] == '1')
					{
						$update_data4 = array('center_id' => $center_id);
						$last_id = $this->master_model->insertRecord('config_institute_code', $update_data4, true);
						
						/* Institute id config log*/
						$log_title ="Update log of config_institute_code table.";
						$log_data = serialize($update_data4);
						$user_id = 0;
						storedDraActivity($log_title, $log_data, $user_id);
						
						/* Get last Inst. Code */
						$institute_code = $last_id;
						if($institute_code != "" && $institute_code > 0)
						{
							/* Add Inst. Code  in agency_center and dra_accerdited_master */
							$update_data = array('institute_code' => $institute_code);
							$this->master_model->updateRecord('agency_center', $update_data, array('center_id' => $center_id));
							$update_data = array('institute_code' => $institute_code);
							$this->master_model->updateRecord('dra_accerdited_master', $update_data, array('dra_inst_registration_id' => $agency_id));
							
						}
						
					}
					
					$update_data = array(
					'transaction_no' => $transaction_no,
					'status' => 1,
					'transaction_details' => $responsedata['transaction_error_type']. " - " .$responsedata['transaction_error_desc'],
					'auth_code' => '0300',
					'bankcode' => $responsedata['bankid'],
					'paymode' => $responsedata['txn_process_type'],
					'callback' => 'B2B'
					);
					$this->master_model->updateRecord('payment_transaction', $update_data, array(
					'receipt_no' => $MerchantOrderNo
					));
					
					/*   $update_data_status = array(
						'center_display_status' => '1',
						);
						
					$this->master_model->updateRecord('agency_center', $update_data_status, array('center_id' =>$center_id));*/ // added by aayusha
					
					/* payment_transaction Update log*/
					$log_title ="Update in payment_transaction table";
					$log_data = serialize($update_data);
					$user_id = 0;
					storedDraActivity($log_title, $log_data, $user_id);
					
					//Manage Log
					$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code . "&CALLBACK=B2B";
					$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
					
					$emailerstr = $this->master_model->getRecords('emailer', array(
					'emailer_name' => 'dra_institute'
					));
					if (count($emailerstr) > 0 && (count($get_user_regnum) > 0)) {
						//Query to get user details
						
						$final_str         = $emailerstr[0]['emailer_text'];
						$info_arr          = array(
						'to' => $user_info[0]['email_id'],
						'from' => $emailerstr[0]['from'],
						'subject' => $emailerstr[0]['subject'],
						'message' => $final_str
						);
						//genertate invoice and email send with invoice attach 8-7-2017                    
						//get invoice    
						$getinvoice_number = $this->master_model->getRecords('exam_invoice', array(
						'receipt_no' => $MerchantOrderNo,
						'pay_txn_id' => $get_user_regnum[0]['id']
						));
						if (count($getinvoice_number) > 0) {
							$invoiceNumber = generate_dra_invoice_number($getinvoice_number[0]['invoice_id']);
							if ($invoiceNumber) {
								$invoiceNumber = $this->config->item('DRA_invoice_no_prefix') . $invoiceNumber;
							}
							$update_data = array(
							'invoice_no' => $invoiceNumber,
							'transaction_no' => $transaction_no,
							'date_of_invoice' => date('Y-m-d H:i:s'),
							'institute_code' => $institute_code,
							'modified_on' => date('Y-m-d H:i:s')
							);
							$this->db->where('pay_txn_id', $get_user_regnum[0]['id']);
							$this->master_model->updateRecord('exam_invoice', $update_data, array(
							'receipt_no' => $MerchantOrderNo
							));
							$attachpath = genarate_dra_invoice($getinvoice_number[0]['invoice_id']);
						}
						if ($attachpath != '') {
							
							if ($this->Emailsending->mailsend_attch_DRA($info_arr, $attachpath)) {
								$this->session->set_flashdata('success', 'DRA agency registration has been done successfully !!');
								redirect(base_url() . 'DraRegister/acknowledge/' . base64_encode($MerchantOrderNo));
								} else {
								redirect(base_url('DraRegister/acknowledge/'));
							}
							} else {
							redirect(base_url('DraRegister/acknowledge/'));
						}
					}
					
				}
				//bill desk fail
				else 
				{					
					$get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array(
					'receipt_no' => $MerchantOrderNo
					), 'ref_id,status');
					if ($get_user_regnum_info[0]['status'] != 0 && $get_user_regnum_info[0]['status'] == 2) {
						if (isset($_REQUEST['merchIdVal'])) {
							$merchIdVal = $_REQUEST['merchIdVal'];
						}
						if (isset($_REQUEST['Bank_Code'])) {
							$Bank_Code = $_REQUEST['Bank_Code'];
						}
						if (isset($_REQUEST['pushRespData'])) {
							$encData = $_REQUEST['pushRespData'];
						}
						$update_data = array(
						'transaction_no' => $transaction_no,
						'status' => 0,
						'transaction_details' => $responsedata['transaction_error_type']. " - " .$responsedata['transaction_error_desc'],
						'auth_code' => '0399',
						'bankcode' => $responsedata['bankid'],
						'paymode' => $responsedata['txn_process_type'],
						'callback' => 'B2B'
						);
						$this->master_model->updateRecord('payment_transaction', $update_data, array(
						'receipt_no' => $MerchantOrderNo
						));
						//Manage Log
						$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
						$this->log_model->logtransaction("billdesk", $pg_response, $responsedata['transaction_error_type']);
					}
					$this->session->set_flashdata('error', 'Transaction has been fail, please try again!!');
					redirect(base_url('DraRegister'));
					//Sbi fail code without callback
					echo "Transaction failed";
					echo "<script>
					(function (global) {
					
					if(typeof (global) === 'undefined')
					{
					throw new Error('window is undefined');
					}
					
					var _hash = '!';
					var noBackPlease = function () {
					global.location.href += '#';
					
					// making sure we have the fruit available for juice....
					// 50 milliseconds for just once do not cost much (^__^)
					global.setTimeout(function () {
					global.location.href += '!';
					}, 50);
					};
					
					// Earlier we had setInerval here....
					global.onhashchange = function () {
					if (global.location.hash !== _hash) {
					global.location.hash = _hash;
					}
					};
					
					global.onload = function () {
					
					noBackPlease();
					
					// disables backspace on page except on input fields and textarea..
					document.body.onkeydown = function (e) {
					var elm = e.target.nodeName.toLowerCase();
					if (e.which === 8 && (elm !== 'input' && elm  !== 'textarea')) {
					e.preventDefault();
					}
					// stopping event bubbling up the DOM tree..
					e.stopPropagation();
					};
					
					};
					
					})(window);
					</script>";
					exit;
					
				}
				///End of SBI B2B callback 
				redirect(base_url() . 'DraRegister/acknowledge/');
			} 
			else 
			{
				die("Please try again...");
			}
		}
    
    //if sbi transaction success
    public function sbitranssuccess()
    {
			if (isset($_REQUEST['encData'])) {
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$key = $this->config->item('sbi_m_key');
				$aes = new CryptAES();
				$aes->set_key(base64_decode($key));
				$aes->require_pkcs5();
				$encData         = $aes->decrypt($_REQUEST['encData']);
				$responsedata    = explode("|", $encData);
				$MerchantOrderNo = $responsedata[0];
				$transaction_no  = $responsedata[1];
				$attachpath      = $invoiceNumber = '';
				if (isset($_REQUEST['merchIdVal'])) {
					$merchIdVal = $_REQUEST['merchIdVal'];
				}
				if (isset($_REQUEST['Bank_Code'])) {
					$Bank_Code = $_REQUEST['Bank_Code'];
				}
				if (isset($_REQUEST['pushRespData'])) {
					$encData = $_REQUEST['pushRespData'];
				}
				//Sbi B2B callback
				//check sbi payment status with MerchantOrderNo 
				$q_details = sbiqueryapi($MerchantOrderNo);
				if ($q_details) {
					if ($q_details[2] == "SUCCESS") {
						$get_user_regnum = $this->master_model->getRecords('payment_transaction', array(
						'receipt_no' => $MerchantOrderNo
						), 'ref_id,status,id');
						
						if ($get_user_regnum[0]['status'] == 2) 
						{
							if (count($get_user_regnum) > 0) 
							{	
								// Get agency_id from agency_center table
								$agency_center_info = $this->master_model->getRecords('agency_center', array(
								'center_id' => $get_user_regnum[0]['ref_id']
								), 'agency_id,center_id'); 
								
								// Get Details from dra_inst_registration table for email and center type
								$user_info = $this->master_model->getRecords('dra_inst_registration', array(
								'id' => $agency_center_info[0]['agency_id']
								), 'id,email_id,inst_head_email,center_type'); 
							}
							
							$agency_id = $agency_center_info[0]['agency_id'];
							$center_id = $agency_center_info[0]['center_id'];
							
							/* Payment Status Updates */
							$update_data1 = array('status' => '1');
							$this->master_model->updateRecord('dra_inst_registration',$update_data1,array('id'=>$agency_id));
							
							$update_data2 = array('pay_status' => '1','center_status'=>'A');
							$this->master_model->updateRecord('agency_center',$update_data2, array('center_id'=>$center_id));
							
							$update_data3 = array('pay_status' => '1');
							$this->master_model->updateRecord('dra_accerdited_master', $update_data3, array('dra_inst_registration_id' => $agency_id));
							
							$institute_code ='';
							$check_status = $this->master_model->getRecords('dra_accerdited_master',array('dra_inst_registration_id' => $agency_id,'pay_status' => '1'));
							if($check_status[0]['pay_status'] == '1')
							{
								$update_data4 = array('center_id' => $center_id);
								$last_id = $this->master_model->insertRecord('config_institute_code', $update_data4, true);
								
								/* Institute id config log*/
								$log_title ="Update log of config_institute_code table.";
								$log_data = serialize($update_data4);
								$user_id = 0;
								storedDraActivity($log_title, $log_data, $user_id);
								
								/* Get last Inst. Code */
								$institute_code = $last_id;
								if($institute_code != "" && $institute_code > 0)
								{
									/* Add Inst. Code  in agency_center and dra_accerdited_master */
									$update_data = array('institute_code' => $institute_code);
									$this->master_model->updateRecord('agency_center', $update_data, array('center_id' => $center_id));
									$update_data = array('institute_code' => $institute_code);
									$this->master_model->updateRecord('dra_accerdited_master', $update_data, array('dra_inst_registration_id' => $agency_id));
									
								}
								
							}
							
							$update_data = array(
							'transaction_no' => $transaction_no,
							'status' => 1,
							'transaction_details' => $responsedata[2] . " - " . $responsedata[7],
							'auth_code' => '0300',
							'bankcode' => $responsedata[8],
							'paymode' => $responsedata[5],
							'callback' => 'B2B'
							);
							$this->master_model->updateRecord('payment_transaction', $update_data, array(
							'receipt_no' => $MerchantOrderNo
							));
							
							/*   $update_data_status = array(
								'center_display_status' => '1',
								);
								
							$this->master_model->updateRecord('agency_center', $update_data_status, array('center_id' =>$center_id));*/ // added by aayusha
							
							/* payment_transaction Update log*/
							$log_title ="Update in payment_transaction table";
							$log_data = serialize($update_data);
							$user_id = 0;
							storedDraActivity($log_title, $log_data, $user_id);
							
							//Manage Log
							$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code . "&CALLBACK=B2B";
							$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
							
							$emailerstr = $this->master_model->getRecords('emailer', array(
							'emailer_name' => 'dra_institute'
							));
							if (count($emailerstr) > 0 && (count($get_user_regnum) > 0)) {
								//Query to get user details
								
								$final_str         = $emailerstr[0]['emailer_text'];
								$info_arr          = array(
								'to' => $user_info[0]['email_id'],
								'from' => $emailerstr[0]['from'],
								'subject' => $emailerstr[0]['subject'],
								'message' => $final_str
								);
								//genertate invoice and email send with invoice attach 8-7-2017                    
								//get invoice    
								$getinvoice_number = $this->master_model->getRecords('exam_invoice', array(
								'receipt_no' => $MerchantOrderNo,
								'pay_txn_id' => $get_user_regnum[0]['id']
								));
								if (count($getinvoice_number) > 0) {
									$invoiceNumber = generate_dra_invoice_number($getinvoice_number[0]['invoice_id']);
									if ($invoiceNumber) {
										$invoiceNumber = $this->config->item('DRA_invoice_no_prefix') . $invoiceNumber;
									}
									$update_data = array(
									'invoice_no' => $invoiceNumber,
									'institute_code' => $institute_code,
									'transaction_no' => $transaction_no,
									'date_of_invoice' => date('Y-m-d H:i:s'),
									'modified_on' => date('Y-m-d H:i:s')
									);
									$this->db->where('pay_txn_id', $get_user_regnum[0]['id']);
									$this->master_model->updateRecord('exam_invoice', $update_data, array(
									'receipt_no' => $MerchantOrderNo
									));
									$attachpath = genarate_dra_invoice($getinvoice_number[0]['invoice_id']);
								}
								if ($attachpath != '') {
									
									if ($this->Emailsending->mailsend_attch_DRA($info_arr, $attachpath)) {
										$this->session->set_flashdata('success', 'DRA agency registration has been done successfully !!');
										redirect(base_url() . 'DraRegister/acknowledge/' . base64_encode($MerchantOrderNo));
										} else {
										redirect(base_url('DraRegister/acknowledge/'));
									}
									} else {
									redirect(base_url('DraRegister/acknowledge/'));
								}
							}
						}
					}
				}
				///End of SBI B2B callback 
				redirect(base_url() . 'DraRegister/acknowledge/');
        } else {
				die("Please try again...");
			}
		}
    //if sbi payment fail
    public function sbitransfail()
    {
			delete_cookie('regid');
			if (isset($_REQUEST['encData'])) {
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$key = $this->config->item('sbi_m_key');
				$aes = new CryptAES();
				$aes->set_key(base64_decode($key));
				$aes->require_pkcs5();
				$encData              = $aes->decrypt($_REQUEST['encData']);
				$responsedata         = explode("|", $encData);
				$MerchantOrderNo      = $responsedata[0];
				$transaction_no       = $responsedata[1];
				$get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array(
				'receipt_no' => $MerchantOrderNo
				), 'ref_id,status');
				if ($get_user_regnum_info[0]['status'] != 0 && $get_user_regnum_info[0]['status'] == 2) {
					if (isset($_REQUEST['merchIdVal'])) {
						$merchIdVal = $_REQUEST['merchIdVal'];
					}
					if (isset($_REQUEST['Bank_Code'])) {
						$Bank_Code = $_REQUEST['Bank_Code'];
					}
					if (isset($_REQUEST['pushRespData'])) {
						$encData = $_REQUEST['pushRespData'];
					}
					$update_data = array(
					'transaction_no' => $transaction_no,
					'status' => 0,
					'transaction_details' => $responsedata[2] . " - " . $responsedata[7],
					'auth_code' => '0399',
					'bankcode' => $responsedata[8],
					'paymode' => $responsedata[5],
					'callback' => 'B2B'
					);
					$this->master_model->updateRecord('payment_transaction', $update_data, array(
					'receipt_no' => $MerchantOrderNo
					));
					//Manage Log
					$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
					$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
				}
				$this->session->set_flashdata('error', 'Transaction has been fail, please try again!!');
				redirect(base_url('DraRegister'));
				//Sbi fail code without callback
				echo "Transaction failed";
				echo "<script>
				(function (global) {
				
				if(typeof (global) === 'undefined')
				{
				throw new Error('window is undefined');
				}
				
				var _hash = '!';
				var noBackPlease = function () {
				global.location.href += '#';
				
				// making sure we have the fruit available for juice....
				// 50 milliseconds for just once do not cost much (^__^)
				global.setTimeout(function () {
				global.location.href += '!';
				}, 50);
				};
				
				// Earlier we had setInerval here....
				global.onhashchange = function () {
				if (global.location.hash !== _hash) {
				global.location.hash = _hash;
				}
				};
				
				global.onload = function () {
				
				noBackPlease();
				
				// disables backspace on page except on input fields and textarea..
				document.body.onkeydown = function (e) {
				var elm = e.target.nodeName.toLowerCase();
				if (e.which === 8 && (elm !== 'input' && elm  !== 'textarea')) {
				e.preventDefault();
				}
				// stopping event bubbling up the DOM tree..
				e.stopPropagation();
				};
				
				};
				
				})(window);
				</script>";
				exit;
        } else {
				die("Please try again...");
			}
		}
    //##-----------------Thank you message to end user
    public function acknowledge($MerchantOrderNo = NULL)
    {
			if (!empty($MerchantOrderNo)) {
				$payment_info = $this->master_model->getRecords('payment_transaction', array(
				'receipt_no' => base64_decode($MerchantOrderNo)
				), 'ref_id,transaction_no,date,amount,status');
			}
			if (count(@$payment_info) <= 0) {
				redirect(base_url());
			}
			$data = array();
			if ($this->session->userdata('memberdata') == '') {
				redirect(base_url());
			}
			if ($this->session->userdata('userinfo')) {
				$this->session->unset_userdata('userinfo');
			}
			if ($this->session->userdata('paymentinfo')) {
				$this->session->unset_userdata('paymentinfo');
			}
			$user_info = $this->master_model->getRecords('dra_inst_registration', array(
			'id' => $this->session->userdata['memberdata']['agency_id']
			));
			$data      = array(
			'middle_content' => 'DRA/dra_thankyou',
			'application_number' => $payment_info[0]['ref_id'],
			'user_info' => $user_info,
			'payment_info' => $payment_info
			);
			$this->load->view('common_view_fullwidth', $data);
			/*$data=array('middle_content'=>'dup_cert/duplicate_cert_thankyou','application_number','application_number'=>$user_info[0]['member_no']);
			$this->load->view('common_view',$data);*/
		}
    // reload captcha functionality
    public function generatecaptchaajax()
    {
			$this->load->helper('captcha');
			$this->session->unset_userdata("regcaptcha");
			$this->session->set_userdata("regcaptcha", rand(1, 100000));
			$vals                   = array(
			'img_path' => './uploads/applications/',
			'img_url' => base_url() . 'uploads/applications/'
			);
			$cap                    = create_captcha($vals);
			$data                   = $cap['image'];
			$_SESSION["regcaptcha"] = $cap['word'];
			echo $data;
		}
    //##---------------check ajax code captcha---------##
    public function ajax_check_captcha()
    {
			$code = $_POST['code'];
			// check if captcha is set -
			if ($code == '' || $_SESSION["regcaptcha"] != $code) {
				$this->form_validation->set_message('ajax_check_captcha', 'Invalid %s.');
				echo 'false';
        } else if ($_SESSION["regcaptcha"] == $code) {
				echo 'true';
			}
		}
    //##---------------call back for checkpin-----------------------##
    public function check_checkpin($pincode, $statecode)
    {
			if ($statecode != "" && $pincode != '') {
				$this->db->where("$pincode BETWEEN start_pin AND end_pin");
				$prev_count = $this->master_model->getRecordCount('state_master', array(
				'state_code' => $statecode
				));
				//echo $this->db->last_query();
				if ($prev_count == 0) {
					$str = 'Please enter Valid Pincode';
					$this->form_validation->set_message('check_checkpin', $str);
					return false;
				} else
				$this->form_validation->set_message('error', ""); {
					return true;
				}
        } else {
				$str = 'Pincode/State field is required.';
				$this->form_validation->set_message('check_checkpin', $str);
				return false;
			}
		}
    ##---------check pincode/zipcode alredy exist or not -----------##
    public function checkpin_main_addr()
    {
			$statecode = $_POST['statecode'];
			$pincode   = $_POST['pincode'];
			if ($statecode != "") {
				$this->db->where("$pincode BETWEEN start_pin AND end_pin");
				$prev_count = $this->master_model->getRecordCount('state_master', array(
				'state_code' => $statecode
				));
				//echo $this->db->last_query();
				//exit;
				if ($prev_count == 0) {
					echo 'false';
					} else {
					echo 'true';
				}
        } else {
				echo 'false';
			}
		}
    ##---------check pincode/zipcode alredy exist or not -----------##
    public function checkpin()
    {
			$statecode = $_POST['statecode'];
			$pincode   = $_POST['pincode'];
			if ($statecode != "") {
				$this->db->where("$pincode BETWEEN start_pin AND end_pin");
				$prev_count = $this->master_model->getRecordCount('state_master', array(
				'state_code' => $statecode
				));
				//echo $this->db->last_query();
				//exit;
				if ($prev_count == 0) {
					echo 'false';
					} else {
					echo 'true';
				}
        } else {
				echo 'false';
			}
		}
    //-----------check Institute phone number already exist or not---------//
    public function inst_phoneduplication()
    {
			$inst_phone = $_POST['inst_phone'];
			if ($inst_phone != "") {
				$prev_count = $this->master_model->getRecordCount('dra_inst_registration', array(
				'inst_phone' => $inst_phone,
				'status' => '1'
				));
				if ($prev_count == 0) {
					$data_arr = array(
					'ans' => 'ok'
					);
					echo json_encode($data_arr);
					} else {
					$user_info        = $this->master_model->getRecords('dra_inst_registration', array(
					'inst_phone' => $inst_phone
					), 'inst_name');
					$inst_name        = $user_info[0]['inst_name'];
					$userfinalstrname = preg_replace('#[\s]+#', ' ', $inst_name);
					$str              = 'The entered  phone no already exist ';
					$data_arr         = array(
					'ans' => 'exists',
					'output' => $str
					);
					echo json_encode($data_arr);
				}
        } else {
				echo 'error';
			}
		}
    //--------------check institute fax number already exist or not-------------------//
    public function inst_fax_noduplication()
    {
			$inst_fax_no = $_POST['inst_fax_no'];
			if ($inst_fax_no != "") {
				$prev_count = $this->master_model->getRecordCount('dra_inst_registration', array(
				'inst_fax_no' => $inst_fax_no,
				'status' => '1'
				));
				//echo $this->db->last_query();
				if ($prev_count == 0) {
					$data_arr = array(
					'ans' => 'ok'
					);
					echo json_encode($data_arr);
					} else {
					$user_info        = $this->master_model->getRecords('dra_inst_registration', array(
					'inst_fax_no' => $inst_fax_no
					), 'inst_name');
					$inst_name        = $user_info[0]['inst_name'];
					$userfinalstrname = preg_replace('#[\s]+#', ' ', $inst_name);
					$str              = 'The entered  FAX no already exist ';
					$data_arr         = array(
					'ans' => 'exists',
					'output' => $str
					);
					echo json_encode($data_arr);
				}
        } else {
				echo 'error';
			}
		}
    //--------------Check institute mobile number exist or not-------------//
    public function inst_head_mobile_noduplication()
    {
			$inst_head_contact_no = $_POST['inst_head_contact_no'];
			if ($inst_head_contact_no != "") {
				$prev_count = $this->master_model->getRecordCount('dra_inst_registration', array(
				'inst_head_contact_no' => $inst_head_contact_no,
				'status' => '1'
				));
				if ($prev_count == 0) {
					$data_arr = array(
					'ans' => 'ok'
					);
					echo json_encode($data_arr);
					} else {
					$user_info        = $this->master_model->getRecords('dra_inst_registration', array(
					'inst_head_contact_no' => $inst_head_contact_no
					), 'inst_name');
					$inst_name        = $user_info[0]['inst_name'];
					$userfinalstrname = preg_replace('#[\s]+#', ' ', $inst_name);
					$str              = 'The entered  mobile no already exist';
					$data_arr         = array(
					'ans' => 'exists',
					'output' => $str
					);
					echo json_encode($data_arr);
				}
        } else {
				echo 'error';
			}
		}

	//--------------Check alternate mobile number exist or not-------------//
    public function inst_altr_mobile_noduplication()
    {
		$inst_alter_contact_no = $_POST['inst_alter_contact_no'];
		if ($inst_alter_contact_no != "") 
		{
			$prev_count = $this->master_model->getRecordCount('dra_inst_registration', array(
			'inst_alter_contact_no' => $inst_alter_contact_no,
			'status' => '1'
			));
			if ($prev_count == 0) 
			{
				$data_arr = array(
				'ans' => 'ok'
				);
				echo json_encode($data_arr);
			} 
			else 
			{
				$user_info        = $this->master_model->getRecords('dra_inst_registration', array(
				'inst_alter_contact_no' => $inst_alter_contact_no
				), 'inst_name');
				$inst_name        = $user_info[0]['inst_name'];
				$userfinalstrname = preg_replace('#[\s]+#', ' ', $inst_name);
				$str              = 'The entered  mobile no already exist';
				$data_arr         = array(
				'ans' => 'exists',
				'output' => $str
				);
				echo json_encode($data_arr);
			}
	    } 
	    else 
	    {
			echo 'error';
		}
	}	

    //--------------check institute email already exist or not-------------------//
    public function inst_emailduplication()
    {
			$inst_email = $_POST['inst_email'];
			if ($inst_email != "") {
				$prev_count = $this->master_model->getRecordCount('dra_inst_registration', array(
				'inst_email' => $inst_email,
				'status' => '1'
				));
				//echo $this->db->last_query();
				if ($prev_count == 0) {
					$data_arr = array(
					'ans' => 'ok'
					);
					echo json_encode($data_arr);
					} else {
					$user_info        = $this->master_model->getRecords('dra_inst_registration', array(
					'inst_email' => $inst_email
					), 'inst_name');
					$inst_name        = $user_info[0]['inst_name'];
					$userfinalstrname = preg_replace('#[\s]+#', ' ', $inst_name);
					$str              = 'The entered  Email id already exist';
					$data_arr         = array(
					'ans' => 'exists',
					'output' => $str
					);
					echo json_encode($data_arr);
				}
        } else {
				
				echo 'error';
			}
		}
    //--------------check institute head email already exist or not-------------------//
    public function inst_head_emailduplication()
    {
			$inst_head_email = $_POST['inst_head_email'];
			if ($inst_head_email != "") {
				$prev_count = $this->master_model->getRecordCount('dra_inst_registration', array(
				'inst_head_email' => $inst_head_email,
				'status' => '1'
				));
				//echo $this->db->last_query();
				if ($prev_count == 0) {
					$data_arr = array(
					'ans' => 'ok'
					);
					echo json_encode($data_arr);
					} else {
					$user_info        = $this->master_model->getRecords('dra_inst_registration', array(
					'inst_head_email' => $inst_head_email
					), 'inst_name');
					$inst_name        = $user_info[0]['inst_name'];
					$userfinalstrname = preg_replace('#[\s]+#', ' ', $inst_name);
					$str              = 'The entered  Email id already exist ';
					$data_arr         = array(
					'ans' => 'exists',
					'output' => $str
					);
					echo json_encode($data_arr);
				}
        } else {
				echo 'error';
			}
		}

	//--------------check institute head email already exist or not-------------------//
    public function inst_altr_emailduplication()
    {
		$inst_altr_email = $_POST['inst_altr_email'];
		if ($inst_altr_email != "") 
		{
			$prev_count = $this->master_model->getRecordCount('dra_inst_registration', array(
			'inst_altr_email' => $inst_altr_email,
			'status' => '1'
			));
			//echo $this->db->last_query();
			if ($prev_count == 0) {
				$data_arr = array(
				'ans' => 'ok'
				);
				echo json_encode($data_arr);
				} else {
				$user_info        = $this->master_model->getRecords('dra_inst_registration', array(
				'inst_altr_email' => $inst_altr_email
				), 'inst_name');
				$inst_name        = $user_info[0]['inst_name'];
				$userfinalstrname = preg_replace('#[\s]+#', ' ', $inst_name);
				$str              = 'The entered  Email id already exist ';
				$data_arr         = array(
				'ans' => 'exists',
				'output' => $str
				);
				echo json_encode($data_arr);
			}
        } else {
				echo 'error';
			}
		}	

		
    //-----------check Institute phone number already exist or not---------//
    public function office_no_duplication()
    {
			$office_no = $_POST['office_no'];
			if ($office_no != "") {
				$today_date = date('Y-m-d');
				$this->db->where("'$today_date' < dra_inst_registration.validate_upto");
				$prev_count = $this->master_model->getRecordCount('dra_inst_registration', array(
				'office_no' => $office_no,
				'status' => '1'
				));
				if ($prev_count == 0) {
					$data_arr = array(
					'ans' => 'ok'
					);
					echo json_encode($data_arr);
					} else {
					$user_info        = $this->master_model->getRecords('dra_inst_registration', array(
					'office_no' => $office_no
					), 'inst_name');
					$inst_name        = $user_info[0]['inst_name'];
					$userfinalstrname = preg_replace('#[\s]+#', ' ', $inst_name);
					$str              = 'The entered  phone no already exist';
					$data_arr         = array(
					'ans' => 'exists',
					'output' => $str
					);
					echo json_encode($data_arr);
				}
        } else {
				echo 'error';
			}
		}
    //-----------check Institute phone number already exist or not---------//
    public function cpmobile_duplication()
    {
			$contact_person_mobile = $_POST['contact_person_mobile'];
			if ($contact_person_mobile != "") {
				$today_date = date('Y-m-d');
				$this->db->where("'$today_date' < dra_inst_registration.validate_upto");
				$prev_count = $this->master_model->getRecordCount('dra_inst_registration', array(
				'contact_person_mobile' => $contact_person_mobile,
				'status' => '1'
				));
				if ($prev_count == 0) {
					$data_arr = array(
					'ans' => 'ok'
					);
					echo json_encode($data_arr);
					} else {
					$user_info        = $this->master_model->getRecords('dra_inst_registration', array(
					'contact_person_mobile' => $contact_person_mobile
					), 'inst_name');
					$inst_name        = $user_info[0]['inst_name'];
					$userfinalstrname = preg_replace('#[\s]+#', ' ', $inst_name);
					$str              = 'The entered  phone no already exist';
					$data_arr         = array(
					'ans' => 'exists',
					'output' => $str
					);
					echo json_encode($data_arr);
				}
        } else {
				echo 'error';
			}
		}
    //--------------check contact person email already exist or not-------------------//
    public function cp_emailduplication()
    {
			$email_id = $_POST['email_id'];
			if ($email_id != "") {
				$today_date = date('Y-m-d');
				$this->db->where("'$today_date' < dra_inst_registration.validate_upto");
				$prev_count = $this->master_model->getRecordCount('dra_inst_registration', array(
				'email_id' => $email_id,
				'status' => '1'
				));
				if ($prev_count == 0) {
					$data_arr = array(
					'ans' => 'ok'
					);
					echo json_encode($data_arr);
					} else {
					$user_info        = $this->master_model->getRecords('dra_inst_registration', array(
					'email_id' => $email_id
					), 'inst_name');
					$inst_name        = $user_info[0]['inst_name'];
					$userfinalstrname = preg_replace('#[\s]+#', ' ', $inst_name);
					$str              = 'The entered  Email id already exist';
					$data_arr         = array(
					'ans' => 'exists',
					'output' => $str
					);
					echo json_encode($data_arr);
				}
        } else {
				echo 'error';
			}
		}
		
		public function getCity() 
		{
			if (isset($_POST["state_code"]) && !empty($_POST["state_code"])) 
			{
				$state_code = $this->security->xss_clean($this->input->post('state_code'));
				$result = $this->master_model->getRecords('city_master', array('state_code' => $state_code,'city_delete' => 0));
				if ($result) 
				{
					echo '<option value="">- Select - </option>';
					foreach ($result AS $data) 
					{
						if ($data) 
						{
							echo '<option value="' . $data['id'] . '">' . $data['city_name'] . '</option>';
						}
					}
				} 
				else 
				{
					echo '<option value="">City Not Available, Please select other state</option>';
				}
				
			}
		}
		
		
	}
?>
