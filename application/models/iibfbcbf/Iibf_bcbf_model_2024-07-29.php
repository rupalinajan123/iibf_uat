<?php
  /********************************************************************************************************************
  ** Description: Common Model for BCBF Module
  ** Created BY: Sagar Matale On 11-10-2023
  ********************************************************************************************************************/
	class Iibf_bcbf_model extends CI_Model
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model('master_model');			
			$this->load->model('Emailsending');		
      
      $this->buffer_days_after_training_end_date = '0';
      $this->buffer_days_after_candidate_add_date = '270';
    }
		
		function check_session_login()/******** START : CHECK IF ANY SESSION IS ALREADY STARTED OR NOT ON GLOBAL LOGIN PAGE ********/
		{
			$login_user_id = $this->session->userdata('IIBF_BCBF_LOGIN_ID');
			$login_user_type = $this->session->userdata('IIBF_BCBF_USER_TYPE');
			if(isset($login_user_id) && $login_user_id != "" && isset($login_user_type) && $login_user_type != "")
			{
        if($login_user_type == 'admin') //CHECK IN ADMIN
        {
          $admin_data = $this->master_model->getRecords('iibfbcbf_admin', array('admin_id' => $login_user_id, 'is_active' => '1', 'is_deleted' => '0'), 'admin_id');

          if(count($admin_data) > 0) { redirect(site_url('iibfbcbf/admin/dashboard_admin'),'refresh'); }
				  else { redirect(site_url('iibfbcbf/login/logout'),'refresh'); }
        }
        else if($login_user_type == 'agency') //CHECK IN AGENCY
        {
				  $agency_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('agency_id' => $login_user_id, 'is_active' => '1', 'is_deleted' => '0'), 'agency_id');

          if(count($agency_data) > 0) { redirect(site_url('iibfbcbf/agency/dashboard_agency'),'refresh'); }
				  else { redirect(site_url('iibfbcbf/login/logout'),'refresh'); }
        }	
        else if($login_user_type == 'centre') //CHECK IN CENTER
        {
          $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = cm.agency_id', 'INNER');
          $centre_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.centre_id' => $login_user_id, 'cm.status' => '1', 'cm.is_deleted' => '0', 'am.is_active' => '1', 'am.is_deleted'=>'0', 'am.allow_exam_types !=' => 'CSC'), 'cm.centre_id');				  

          if(count($centre_data) > 0) { redirect(site_url('iibfbcbf/agency/dashboard_agency'),'refresh'); }
				  else { redirect(site_url('iibfbcbf/login/logout'),'refresh'); }
        }				
        else if($login_user_type == 'inspector') //CHECK IN inspector
        {
          $inspector_data = $this->master_model->getRecords('iibfbcbf_inspector_master im', array('im.inspector_id' => $login_user_id, 'im.is_active' => '1', 'im.is_deleted' => '0'), 'im.inspector_id');				  

          if(count($inspector_data) > 0) { redirect(site_url('iibfbcbf/inspector/dashboard_inspector'),'refresh'); }
				  else { redirect(site_url('iibfbcbf/login/logout'),'refresh'); }
        }				
      }
    }/******** END : CHECK IF ANY SESSION IS ALREADY STARTED OR NOT ON GLOBAL LOGIN PAGE ********/
		
    /******** START : CHECK SESSION AFTER LOGIN FOR ALL ADMIN, AGENCY, CENTER PAGES ********/
		function check_admin_session_all_pages($login_as='') //login_as = admin or agency or centre
		{
			$login_user_id = $this->session->userdata('IIBF_BCBF_LOGIN_ID');
			$login_user_type = $this->session->userdata('IIBF_BCBF_USER_TYPE');

			if (!isset($login_user_id) || $login_user_id == "" || !isset($login_user_type) || $login_user_type == "")
			{
				redirect(site_url('iibfbcbf/login/logout'),'refresh');
      }
			else
			{
        if($login_as == '') { redirect(site_url('iibfbcbf/login/logout'),'refresh'); }
        else
        {
          if($login_as != $login_user_type) 
          {  
            if($login_user_type == 'admin') 
            { 
              redirect(site_url('iibfbcbf/admin/dashboard_admin'),'refresh'); 
            }
            else if($login_user_type == 'agency' || $login_user_type == 'centre') 
            { 
              redirect(site_url('iibfbcbf/agency/dashboard_agency'),'refresh');
            }
            else if($login_user_type == 'inspector') 
            { 
              redirect(site_url('iibfbcbf/inspector/dashboard_inspector'),'refresh');
            }
          }
        }
        
        if($login_user_type == 'admin')
        {
          $admin_data = $this->master_model->getRecords('iibfbcbf_admin', array('admin_id' => $login_user_id, 'is_active' => '1', 'is_deleted' => '0'), 'admin_id');

          if(count($admin_data) == 0) 
          { 
            redirect(site_url('iibfbcbf/login/logout'),'refresh'); 
          }
        }
        else if($login_user_type == 'agency')
        {
				  $agency_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('agency_id' => $login_user_id, 'is_active' => '1', 'is_deleted' => '0'), 'agency_id');

          if(count($agency_data) == 0) 
          { 
            redirect(site_url('iibfbcbf/login/logout'),'refresh'); 
          }				  
        }
        else if($login_user_type == 'centre')
        {
          $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = cm.agency_id', 'INNER');
          $centre_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.centre_id' => $login_user_id, 'cm.status' => '1', 'cm.is_deleted' => '0', 'am.is_active' => '1', 'am.is_deleted'=>'0'), 'cm.centre_id');

          if(count($centre_data) == 0) 
          { 
            redirect(site_url('iibfbcbf/login/logout'),'refresh'); 
          }				  
        }
        else if($login_user_type == 'inspector')
        {
          $inspector_data = $this->master_model->getRecords('iibfbcbf_inspector_master im', array('im.inspector_id' => $login_user_id, 'im.is_active' => '1', 'im.is_deleted' => '0'), 'im.inspector_id');

          if(count($inspector_data) == 0) 
          { 
            redirect(site_url('iibfbcbf/login/logout'),'refresh'); 
          }				  
        }
      }
    }/******** END : CHECK SESSION AFTER LOGIN FOR ALL ADMIN, AGENCY PAGES ********/

    function check_session_login_csc_centre()/****** START : CHECK IF ANY SESSION IS ALREADY STARTED OR NOT ON CSC CENTRE LOGIN PAGE ********/
		{
			$login_user_id = $this->session->userdata('IIBF_BCBF_LOGIN_ID');
			$login_user_type = $this->session->userdata('IIBF_BCBF_USER_TYPE');
			if(isset($login_user_id) && $login_user_id != "" && isset($login_user_type) && $login_user_type != "")
			{
        if($login_user_type == 'centre') //CHECK IN CENTER
        {
          $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = cm.agency_id', 'INNER');
          $centre_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.centre_id' => $login_user_id, 'cm.status' => '1', 'cm.is_deleted' => '0', 'am.is_active' => '1', 'am.is_deleted'=>'0', 'am.allow_exam_types' => 'CSC'), 'cm.centre_id');				  

          if(count($centre_data) > 0) { redirect(site_url('iibfbcbf/agency/dashboard_agency'),'refresh'); }
				  else { redirect(site_url('iibfbcbf/login_csc/logout'),'refresh'); }
        }			
      }
    }/******** END : CHECK IF ANY SESSION IS ALREADY STARTED OR NOT ON CSC CENTRE LOGIN PAGE ********/

    function check_session_candidate_login()/******** START : CHECK IF CANDIDATE SESSION IS ALREADY STARTED OR NOT ON CANDIDATE LOGIN PAGE **/
		{
			$login_candidate_id = $this->session->userdata('IIBF_BCBF_CANDIDATE_LOGIN_ID');
			if(isset($login_candidate_id) && $login_candidate_id != "")
			{
        $candidate_data = $this->master_model->getRecords('iibfbcbf_batch_candidates cand', array('cand.candidate_id' => $login_candidate_id, 'cand.is_deleted' => '0'), 'cand.candidate_id');

        if(count($candidate_data) > 0) { redirect(site_url('iibfbcbf/candidate/dashboard_candidate'),'refresh'); }
        else { redirect(site_url('iibfbcbf/candidate/login_candidate/logout'),'refresh'); }        			
      }
    }/******** END : CHECK IF CANDIDATE SESSION IS ALREADY STARTED OR NOT ON CANDIDATE LOGIN PAGE ********/
		
    /******** START : CHECK SESSION AFTER LOGIN FOR CANDIDATE PAGES ********/
		function check_candidate_session_all_pages()
		{
			$login_candidate_id = $this->session->userdata('IIBF_BCBF_CANDIDATE_LOGIN_ID');
      if (!isset($login_candidate_id) || $login_candidate_id == "")
			{
				redirect(site_url('iibfbcbf/candidate/login_candidate/logout'),'refresh');
      }
			else
			{
        $candidate_data = $this->master_model->getRecords('iibfbcbf_batch_candidates', array('candidate_id' => $login_candidate_id, 'is_deleted' => '0'), 'candidate_id');

        if(count($candidate_data) == 0) 
        { 
          redirect(site_url('iibfbcbf/candidate/login_candidate/logout'),'refresh'); 
        }
      }
    }/******** END : CHECK SESSION AFTER LOGIN FOR CANDIDATE PAGES ********/

    public function getLoggedInUserDetails($user_id, $type) /******** START : GET LOGGED IN ADMIN, AGENCY DETAILS ********/
    {
      $disp_name = '';
      $disp_sidebar_name = '';
      if($type == 'admin')
      {
        $disp_name = 'Admin';
        $admin_data = $this->master_model->getRecords('iibfbcbf_admin', array('admin_id' => $user_id), 'admin_id, admin_name');
              
        if(count($admin_data) > 0) { $disp_name = $admin_data[0]['admin_name']; }
      }
      else if($type == 'agency')
      {
        $disp_name = 'Agency';
        $agency_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('agency_id' => $user_id), 'agency_id, agency_name, agency_code, IF(allow_exam_types="Bulk/Individual", "Regular", allow_exam_types) AS agency_type');
              
        if(count($agency_data) > 0) 
        { 
          $disp_name = $agency_data[0]['agency_name']." (".$agency_data[0]['agency_code'].")";
          $disp_sidebar_name = $agency_data[0]['agency_name']."<span style='font-size: 14px;display: block;margin: 5px 0 0 0;'>(".$agency_data[0]['agency_code'].' - '.$agency_data[0]['agency_type'].")</span>";
        }
      }
      else if($type == 'centre')
      {
        $disp_name = 'Centre';

        $this-> db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
        $this-> db->join('iibfbcbf_agency_master am', 'am.agency_id = cm.agency_id', 'LEFT');
        $centre_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.centre_id' => $user_id), 'cm.centre_id, cm.centre_city, cm.centre_name, cm.centre_username, cm1.city_name, am.agency_name, am.agency_code, IF(am.allow_exam_types="Bulk/Individual", "Regular", am.allow_exam_types) AS agency_type');
              
        if(count($centre_data) > 0) 
        { 
          $disp_name = $centre_data[0]['centre_name']." (".$centre_data[0]['city_name'].")"; 
          $disp_sidebar_name = $centre_data[0]['agency_name']." - ".$centre_data[0]['centre_name']." <span style='font-size: 14px;white-space:nowrap;'>(".$centre_data[0]['centre_username']." - ".$centre_data[0]['agency_type'].")</span>";
        }
      }
      else if($type == 'inspector')
      {
        $disp_name = 'Inspector';
        $inspector_data = $this->master_model->getRecords('iibfbcbf_inspector_master', array('inspector_id' => $user_id), 'inspector_id, inspector_name');
              
        if(count($inspector_data) > 0) { $disp_name = $inspector_data[0]['inspector_name']; }
      }
      else if($type == 'candidate')
      {
        $disp_name = $disp_sidebar_name = 'Candidate';
        $candidate_data = $this->master_model->getRecords('iibfbcbf_batch_candidates', array('candidate_id' => $user_id), 'candidate_id, salutation, first_name, middle_name, last_name');
              
        if(count($candidate_data) > 0) 
        { 
          $disp_name = $candidate_data[0]['salutation'].' '.$candidate_data[0]['first_name']; 
          if($candidate_data[0]['middle_name'] != "") { $disp_name .= ' '.$candidate_data[0]['middle_name']; }
          if($candidate_data[0]['last_name'] != "") { $disp_name .= ' '.$candidate_data[0]['last_name']; }

          $disp_sidebar_name = $candidate_data[0]['salutation'].' '.$candidate_data[0]['first_name'];
        }
      }
      
      $data['disp_name'] = $disp_name;
      $data['disp_sidebar_name'] = $disp_sidebar_name;
			return $data;
    }/******** END : GET LOGGED IN ADMIN, AGENCY DETAILS ********/

    function insert_user_login_logs($user_id=0, $user_type=0, $type=0) /******** START : MAINTAIN LOGIN - LOGOUT LOGS ********/
		{
      $this->load->helper('iibfbcbf/iibf_bcbf_helper');
			$this->load->helper('url');
			$this->load->library('user_agent');
			
			$add_log['user_id'] = $user_id;
      $add_log['user_type'] = $user_type;
			$add_log['ip_address'] = get_ip_address(); //general_helper.php
			$add_log['browser'] = $this->agent->browser()." - ".$this->agent->version()." - ".$this->agent->platform();
			$add_log['details'] = $_SERVER['HTTP_USER_AGENT'];
			$add_log['type'] = $type;
			$add_log['status'] = 1;
			$add_log['created_on'] = date('Y-m-d H:i:s');
			$this->master_model->insertRecord('iibfbcbf_login_logs',$add_log);
		} /******** END : MAINTAIN LOGIN - LOGOUT LOGS ********/
		
    public function get_sort_no($table='', $where, $order_by) /******** START : GET SORT NUMBER ********/
		{
			if($table != "")
			{
				$get_sort_no = $this->Common_model->getRecordsCi($table, $where, '', $order_by, '', '', true);
				if($get_sort_no != '')
				{
					return $get_sort_no['sort_no'] + 1;
        }
				else
				{
					return 1;
        }
      }
    }/******** END : GET SORT NUMBER ********/		
    
    function datatable_record_cnt($select,$table,$where,$order_by=null) /******** START : GET ALL RECORDS WITH SELECT STRING ********/
		{		 			 
			$q = "select $select from $table $where $order_by";
			$query=$this->db->query($q);
			return $query->num_rows();      
		} /******** END : GET ALL RECORDS WITH SELECT STRING ********/

    public function password_encryption($password='') /******** START : PASSWORD ENCRYPTION ********/
    {
      if($_SERVER['HTTP_HOST'] == "localhost" ) 
      {
        return $password;
      }
      else
      {
        include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
        $key = $this->config->item('pass_key');
        $aes = new CryptAES();
        $aes->set_key(base64_decode($key));
        $aes->require_pkcs5();
        return $aes->encrypt($password);
      }
    } /******** END : PASSWORD ENCRYPTION ********/

    public function password_decryption($password='') /******** START : PASSWORD ENCRYPTION ********/
    {
      if($_SERVER['HTTP_HOST'] == "localhost" ) 
      {
        return $password;
      }
      else
      {
        include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
        $key = $this->config->item('pass_key');
        $aes = new CryptAES();
        $aes->set_key(base64_decode($key));
        $aes->require_pkcs5();
        return $aes->decrypt(trim($password));
      }
    } /******** END : PASSWORD ENCRYPTION ********/

    /******** START : MAINTAIN ALL GLOBAL LOGS ********/
    function insert_common_log($title='', $tbl_name='', $qry='', $pk_id='', $module_slug='', $description='', $posted_data='')
		{
      $this->load->helper('iibfbcbf/iibf_bcbf_helper');
			$this->load->helper('url');
			$this->load->library('user_agent');
      
      $user_type = $this->session->userdata('IIBF_BCBF_USER_TYPE');
      $login_id = $this->session->userdata('IIBF_BCBF_LOGIN_ID');

      if($user_type == "" || $user_type == 'NULL') { $user_type = ''; }
      if($login_id == "" || $login_id == 'NULL') { $login_id = '0'; }

			$add_log['module_slug'] = $module_slug;
			$add_log['title'] = $title;
			$add_log['tbl_name'] = $tbl_name;
			$add_log['pk_id'] = $pk_id;
			$add_log['class_name'] = $this->router->fetch_class();
			$add_log['method_name'] = $this->router->fetch_method();
			$add_log['current_url'] = current_url();
			$add_log['qry'] = $qry;
			$add_log['posted_data'] = $posted_data;
			$add_log['login_type'] = $user_type;
			$add_log['login_id'] = $login_id;
			$add_log['description'] = $description;
			$add_log['ip_address'] = get_ip_address(); //general_helper.php
			$this->master_model->insertRecord('iibfbcbf_logs',$add_log);
		} /******** END : MAINTAIN ALL GLOBAL LOGS ********/

    public function fun_restrict_input($str='',$type='') /******** START : CALLBACK CI VALIDATION FUNCTIONS FOR RESTRICT INPUTS ********/
    {
      $result['flag'] = 'error';
      $result['response'] = '';     

      if($type == 'allow_only_alphabets')// Custom callback function for Allow only alphabet
      {
        if (preg_match('/^[a-zA-Z]+$/', $str)) { $result['flag'] = 'success'; /* Valid input */ } 
        else { $result['response'] = 'Only alphabets are allowed'; }
      }
      else if($type == 'allow_only_alphabets_and_space')// Custom callback function for Allow only alphabet + space
      {
        if (preg_match('/^[a-zA-Z ]+$/', $str)) { $result['flag'] = 'success'; /* Valid input */ } 
        else { $result['response'] = 'Only alphabets and spaces are allowed'; }
      }
      else if($type == 'allow_only_alphabets_and_numbers')// Custom callback function for Allow only alphabet + numbers
      {
        if (preg_match('/^[a-zA-Z0-9]+$/', $str)) { $result['flag'] = 'success'; /* Valid input */ } 
        else { $result['response'] = 'Only alphabets and numbers are allowed'; }
      }
      else if($type == 'allow_only_alphabets_and_numbers_and_space')// Custom callback function for Allow only alphabet + numbers + space
      {
        if (preg_match('/^[a-zA-Z0-9 ]+$/', $str)) { $result['flag'] = 'success'; /* Valid input */ } 
        else { $result['response'] = 'Only alphabets, numbers and spaces are allowed'; }
      }
      else if($type == 'allow_only_alphabets_and_floats')// Custom callback function for Allow only alphabet + numbers + floats
      {
        if (preg_match('/^[a-zA-Z0-9.]+$/', $str)) { $result['flag'] = 'success'; /* Valid input */ } 
        else { $result['response'] = 'Only alphabets and numbers are allowed'; }
      }
      else if($type == 'allow_only_alphabets_and_floats_and_space')// Custom callback function for Allow only alphabet + numbers + floats + space
      {
        if (preg_match('/^[a-zA-Z0-9 .]+$/', $str)) { $result['flag'] = 'success'; /* Valid input */ } 
        else { $result['response'] = 'Only alphabets, numbers and spaces are allowed'; }
      }
      else if($type == 'allow_only_numbers')// Custom callback function for Allow only numbers
      {
        if (preg_match('/^[0-9]+$/', $str)) { $result['flag'] = 'success'; /* Valid input */ } 
        else { $result['response'] = 'Only numbers are allowed'; }
      }
      else if($type == 'allow_only_numbers_and_space')// Custom callback function for Allow only numbers + space
      {
        if (preg_match('/^[0-9 ]+$/', $str)) { $result['flag'] = 'success'; /* Valid input */ } 
        else { $result['response'] = 'Only numbers and spaces are allowed'; }
      }
      else if($type == 'allow_only_numbers_and_floats')// Custom callback function for Allow only numbers + floats
      {
        if (preg_match('/^[0-9.]+$/', $str)) { $result['flag'] = 'success'; /* Valid input */ } 
        else { $result['response'] = 'Only numbers are allowed'; }
      }
      else if($type == 'allow_only_numbers_and_floats_and_space')// Custom callback function for Allow only numbers + floats + space
      {
        if (preg_match('/^[0-9. ]+$/', $str)) { $result['flag'] = 'success'; /* Valid input */ } 
        else { $result['response'] = 'Only numbers and space are allowed'; }
      }
      else if($type == 'validAddress')// Custom callback function for Allow only alphabets + numbers + spaces + , - / #
      { 
        if (preg_match('/^[a-zA-Z0-9 .,\-\/#]+$/', $str)) { $result['flag'] = 'success'; /* Valid input */ } 
        else { $result['response'] = 'Only alphabets, numbers, spaces and . , - / # are allowed'; }
      }
      else if($type == 'validCustomInput')// Custom callback function for Allow only alphabets + numbers + spaces + , - / # ()
      { 
        if (preg_match('/^[a-zA-Z0-9 .,\-\/#()]+$/', $str)) { $result['flag'] = 'success'; /* Valid input */ } 
        else { $result['response'] = 'Only alphabets, numbers, spaces and . , - / # () are allowed'; }
      }
      else if($type == 'ValidUsername')// Custom callback function for Allow only alphabets + numbers +  _ @ # $ & * ! . ?
      { 
        if (preg_match('/^[a-zA-Z0-9_@#$&*!.?]+$/', $str)) { $result['flag'] = 'success'; /* Valid input */ } 
        else { $result['response'] = 'Only alphabets, numbers and _ @ # $ & * ! . ? are allowed'; }
      }
      else if($type == 'first_zero_not_allowed')// Custom callback function for first zero not allowed
      { 
        if (preg_match('/^[1-9][0-9]+$/', $str)) { $result['flag'] = 'success'; /* Valid input */ } 
        else { $result['response'] = 'Please enter first number between 1 to 9 only'; }
      }

      return $result;
    } /******** END : CALLBACK CI VALIDATION FUNCTIONS FOR RESTRICT INPUTS ********/

    public function fun_validate_pan_no($str='') /******** START : CALLBACK CI VALIDATION FUNCTIONS FOR CORRECT PAN NUMBER ********/
    {
      $result['flag'] = 'error';
      $result['response'] = '';

      if (preg_match('/^([A-Z]{5}[0-9]{4}[A-Z])$/', $str)) { $result['flag'] = 'success'; /* Valid PAN number format */ } 
      else { $result['response'] = 'Please enter the valid PAN no. like ABCTY1234D'; }
      
      return $result;
    } /******** END : CALLBACK CI VALIDATION FUNCTIONS FOR CORRECT PAN NUMBER ********/

    public function fun_validate_gst_no($str='') /******** START : CALLBACK CI VALIDATION FUNCTIONS FOR CORRECT GST NUMBER ********/
    {
      $result['flag'] = 'error';
      $result['response'] = ''; 

      if (preg_match('/^([0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[0-9A-Z]{1}[Z]{1}[0-9A-Z]{1})$/', $str)) { $result['flag'] = 'success'; /* Valid GST number format */ } 
      else { $result['response'] = 'Please enter the valid GST no. like 29ABCDE1234F1ZW'; }
      
      return $result;
    } /******** END : CALLBACK CI VALIDATION FUNCTIONS FOR CORRECT GST NUMBER ********/

    public function fun_validate_password($str) /******** START : CALLBACK CI VALIDATION FUNCTIONS FOR PASSWORD CHECK ********/
    {
      $result['flag'] = 'error';
      $result['response'] = '';

      if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/', $str)) 
      {
        $result['response'] = 'The password must contain at least one lowercase letter, one uppercase letter, one number, and one special character.';
      }
      else
      {
        $result['flag'] = 'success';
      }

      return $result;
    } /******** END : CALLBACK CI VALIDATION FUNCTIONS FOR PASSWORD CHECK ********/
    
    /******** START : CALLBACK CI VALIDATION FUNCTIONS FOR CORRECT FILE ********/
    //parameter : separated by pipe 'input name|required|allowed extension|max size in kb|input display name|min size in kb'
    //eg. 'pan_photo|y|jpg,jpeg,png|20|pan photo|50'
    //callback_fun_validate_file_upload[pan_photo|y|jpg,jpeg,png|20|pan photo|50]
    public function fun_validate_file_upload($parameter='') 
    {
      $result['flag'] = 'success';
      $result['response'] = '';

      $parameter_str = $parameter; 
      $parameter_err = explode('|',$parameter_str);

      $input_name = $is_required = $allow_ext = $max_size_in_kb = $input_disp_name = $min_size_in_kb = '';
      if(count($parameter_err) > 0 )
      {
        if(isset($parameter_err[0])) { $input_name = $parameter_err[0]; }
        if(isset($parameter_err[1])) { $is_required = $parameter_err[1]; }
        if(isset($parameter_err[2])) { $allow_ext = $parameter_err[2]; }
        if(isset($parameter_err[3])) { $max_size_in_kb = $parameter_err[3]; }
        if(isset($parameter_err[4])) { $input_disp_name = $parameter_err[4]; }
        if(isset($parameter_err[5])) { $min_size_in_kb = $parameter_err[5]; }
      }

      /* echo '<br>input_name : '.$input_name;
      echo '<br>is_required : '.$is_required;
      echo '<br>allow_ext : '.$allow_ext; 
      echo '<br>max_size_in_kb : '.$max_size_in_kb;
      echo '<br>input_disp_name : '.$input_disp_name; exit; */
      
      if($is_required == 'y')
      {
        if(empty($_FILES[$input_name]['name']))
        {
          $result['response'] = 'Please select the '.$input_disp_name;
          $result['flag'] = 'error';
          return $result;
        }

        // Check if the file was uploaded without errors
        if ($_FILES[$input_name]['error'] !== UPLOAD_ERR_OK) 
        {
          $result['response'] = 'Error uploading the '.$input_disp_name;
          $result['flag'] = 'error';
          return $result;
        }
      }

      if(!empty($_FILES[$input_name]['name']))
      {
        // Check if the uploaded file is an image
        //$allowed_types = array('jpg', 'jpeg', 'png', 'gif');
        $allowed_types = explode(",",$allow_ext);

        $file_ext = pathinfo($_FILES[$input_name]['name'], PATHINFO_EXTENSION);

        if (!in_array(strtolower($file_ext), $allowed_types)) 
        {
          $result['response'] = 'Only '.strtoupper(str_replace(",",", ",$allow_ext)).' files are allowed';
          $result['flag'] = 'error';
          return $result;
        }

        if ($_FILES[$input_name]['size'] == 0) 
        {
          $result['response'] = 'The file size should be more than 0KB';
          $result['flag'] = 'error';
          return $result;
        }

        // Check maximum file size
        $max_size = $max_size_in_kb; // 20kb
        if ($_FILES[$input_name]['size'] > $max_size * 1024) 
        {
          $result['response'] = 'The file size should not be more than '.$max_size.'KB';  
          $result['flag'] = 'error'; 
          return $result;       
        }

        // Check minimum file size
        $min_size = $min_size_in_kb; // 20kb
        if ($_FILES[$input_name]['size'] < $min_size * 1024) 
        {
          $result['response'] = 'The file size should not be less than '.$min_size.'KB';  
          $result['flag'] = 'error'; 
          return $result;       
        }
      }
      
      return $result;
    }/******** END : CALLBACK CI VALIDATION FUNCTIONS FOR CORRECT FILE ********/
    
    /******** START : GLOBAL FUNCTION TO UPLOAD THE FILE ********/
    function upload_file($input_name, $valid_arr, $new_file_name, $upload_path, $allowed_types, $is_multiple=0, $cnt='', $height=0, $width=0, $size=0,$resize_width=0,$resize_height=0,$resize_file_name='')
		{
			$flag = 0;
			if($is_multiple == 0) { $path_img = $_FILES[$input_name]['name']; }
			else { $path_img = $_FILES[$input_name]['name'][$cnt]; }
			
			$ext_img = pathinfo($path_img, PATHINFO_EXTENSION);
			$valid_ext_arr = $valid_arr;
			
			/* print_r($valid_ext_arr);
			echo '<br>'.$allowed_types; 
			echo '<br>'.$ext_img; */ 
			//exit;
			
			if(!in_array(strtolower($ext_img),$valid_ext_arr))
			{
				$flag=1;
			}
			
			if($flag == 0)
			{
        $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
				create_directories($upload_path); //iibfbcbf/iibf_bcbf_helper.php
				
				$file=$_FILES;	
				if($is_multiple == 0) { $_FILES['file_upload']['name'] = $file[$input_name]['name']; }
				else { $_FILES['file_upload']['name'] = $file[$input_name]['name'][$cnt]; }
				
				$path = $_FILES['file_upload']['name'];
				$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
				
				$filename = '';
				if($new_file_name != "") //CHECK NEW FILENAME IS BLANK OR NOT. IF BLANK, CONVERT CURRENT FILENAME INTO VALID FORMAT
				{ 
					$raw_filename = $this->remove_special_character_from_string($new_file_name, '50'); 
					$filename = $raw_filename.".".$ext; 
				}
				else 
				{ 
					$raw_filename = str_replace(".".$ext,"",strtolower($path));
					$raw_filename = $this->remove_special_character_from_string($raw_filename, '50')."_".rand(100,999).date("YmdHis"); 
					$filename = $raw_filename.".".$ext;
				}				
				$final_img = $filename;
				
				$config['file_name']     = $final_img;
				$config['upload_path']   = $upload_path;
				$config['allowed_types'] = $allowed_types;
        $config['max_size'] = $size; // in kilobytes
        
        $this->upload->initialize($config);					
				
				if($is_multiple == 0) 
				{
					$_FILES['file_upload']['type']=$file[$input_name]['type'];
					$_FILES['file_upload']['tmp_name']=$file[$input_name]['tmp_name'];
					$_FILES['file_upload']['error']=$file[$input_name]['error'];
					$_FILES['file_upload']['size']=$file[$input_name]['size'];
				}
				else
				{
					$_FILES['file_upload']['type']=$file[$input_name]['type'][$cnt];
					$_FILES['file_upload']['tmp_name']=$file[$input_name]['tmp_name'][$cnt];
					$_FILES['file_upload']['error']=$file[$input_name]['error'][$cnt];
					$_FILES['file_upload']['size']=$file[$input_name]['size'][$cnt];
				}
				
				if($this->upload->do_upload('file_upload'))
				{
          $this->upload->data();

          if($resize_width > 0 || $resize_height > 0)
          {           
            $img_data = getimagesize($_FILES['file_upload']['tmp_name']);
            $img_width = $img_data[0];
            $img_height = $img_data[1];
            if($img_width > $resize_width || $img_height > $resize_height)
            {
              //$file_name,$path,$width,$height,$maintain_ratio="",$new_img_name=""
              $this->create_thumb_resize($filename, $upload_path.'/',$resize_width,$resize_height,TRUE,$resize_file_name.".".$ext);              
            }
          }

					return array('response'=>'success','message' => $final_img);
				}
				else
				{
					return array('response'=>'error','message' => $this->upload->display_errors());
				}
			}
			else
			{
				return array('response'=>'error','message' => "Please upload valid ".str_replace('|',' | ',$allowed_types)." extension file.");
			}
		} /******** END : GLOBAL FUNCTION TO UPLOAD THE FILE ********/

    /******** START : FUNCTION FOR CREATING THUMB OR SMALL IMAGE ********/
    public function create_thumb_resize($file_name,$path,$width,$height,$maintain_ratio="",$new_img_name="")
		{
			//echo $file_name;
			$this->load->library('image_lib');
			$config_1['image_library']='gd2';
			$config_1['source_image']=$path.$file_name;
			$config_1['create_thumb']=TRUE;
			if($maintain_ratio == "") { $config_1['maintain_ratio']=FALSE; }
			{ $config_1['maintain_ratio']=$maintain_ratio; }
			$config_1['thumb_marker']='';
			if($new_img_name != "") { $config_1['new_image']=$path."/".$new_img_name; }
			else { $config_1['new_image']=$path."/thumb_".$file_name; }
			$config_1['width']=$width;
			$config_1['height']=$height;

      //echo '<pre>';print_r($config_1);
			$this->image_lib->initialize($config_1);
			$this->image_lib->resize();
		} /******** END : FUNCTION FOR CREATING THUMB OR SMALL IMAGE ********/
		
    /******** START : FUNCTION FOR RESIZING IMAGE INTO PROVIDED DIMENTIONS ********/
		public function resize_image($file_name,$path,$width,$height,$maintain_ratio="",$new_img_name="")
		{
			//echo $file_name;
			$this->load->library('image_lib');
			$config_1['image_library']='gd2';
			$config_1['source_image']=$path.$file_name;
			$config_1['create_thumb']=TRUE;
			if($maintain_ratio == "") { $config_1['maintain_ratio']=FALSE; }
			{ $config_1['maintain_ratio']=$maintain_ratio; }
			$config_1['thumb_marker']='';
			if($new_img_name != "") { $config_1['new_image']=$path."/".$new_img_name; }
			else { $config_1['new_image']=$path."/re_".$file_name; }
			$config_1['width']=$width;
			$config_1['height']=$height;
			$this->image_lib->initialize($config_1);
			$this->image_lib->resize();
		} /******** END : FUNCTION FOR RESIZING IMAGE INTO PROVIDED DIMENTIONS ********/

    /******** START : REMOVE SPECIAL CHARACTER FROM STRING ********/
		function remove_special_character_from_string($old_string='', $char_limit='50')
		{
			$find_arr = array('`', '~', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '=', '+', '[', '{', ']', '}', '|', ';', ':', '"', '<', ',', '.', '>', '/', '?', "'", '/\/', ' ');
			$new_string = substr($this->check_multiple_underscore(str_replace($find_arr,'_',$old_string)), 0, $char_limit);
			
			/* echo "<br>Old Name : ".$old_string;
			echo "<br>New Name : ".$new_string; */
			return $new_string;
		}/******** END : REMOVE SPECIAL CHARACTER FROM STRING ********/

    /******** START : REMOVE MULTIPLE UNDERSCORE FROM STRING ********/
		function check_multiple_underscore($new_name='')
		{
			if (strpos($new_name, '__') !== false)
			{
				$new_name = str_replace('__','_',$new_name);
				return $this->check_multiple_underscore($new_name);
			}
			else { return $new_name; }
		}/******** END : REMOVE MULTIPLE UNDERSCORE FROM STRING ********/	

    /******** START : CHECK FILE EXIST OR NOT ********/
    function check_file_exist($file_name='', $file_path='', $redirect_url='', $message='')
    {
      if($file_name != "" && $file_path != '' && $redirect_url != "" && $message != "")
      {
        if(!isset($file_name) || $file_name == '' || !file_exists($file_path.$file_name))//file_path = "./iibfbcbf/uploads/faculty_photo/"
        {
          $this->session->set_flashdata('error',$message);
          redirect(site_url($redirect_url));
        }
      }
    }/******** END : CHECK FILE EXIST OR NOT ********/

    /******** START : CHECK FILE EXIST OR NOT ********/
    function check_file_exist_common($file_path='', $file_name='')
    {
      $final_file_name ='';
      
      if($file_path != '' && $file_name != "" && file_exists($file_path.$file_name))
      {
        $final_file_name = $file_name;
      }

      return $final_file_name;
    }/******** END : CHECK FILE EXIST OR NOT ********/

    /******** START : COPY AND UNLINK OLD FILE ********/
    function check_file_rename($file_name='', $file_path='', $new_file_name='')
    {
      $flag = 'error';
      if($file_name != "" && $file_path != '' && $new_file_name != '' && file_exists($file_path.$file_name))
      {
        if(copy($file_path.$file_name, $file_path.$new_file_name) ) 
        {  
          if(file_exists($file_path.$new_file_name))
          {
            @unlink("./".$file_path."/".$file_name);
            $flag = 'success';
          }
        }  
      }

      return $flag;
    }/******** END : COPY AND UNLINK OLD FILE ********/

    function extend_candidate_add_update_date_arr()
    {
      $extend_date_arr = array();

      $batch_data = $this->master_model->getRecords('iibfbcbf_agency_centre_batch acb', array('acb.is_deleted' => '0', 'acb.batch_status' => '3', 'acb.batch_end_date >='=>date('Y-m-d'), 'acb.batch_extend_date !='=>'', 'acb.batch_extend_type !=' => '0'), "acb.batch_id, acb.batch_extend_date, acb.batch_extend_type");
      
      if(count($batch_data) > 0)
      {
        foreach($batch_data as $res)
        {
          $tmp_arr = array();
          $tmp_arr['batch_id'] = $res['batch_id'];
          $tmp_arr['batch_extend_date'] = $res['batch_extend_date'];
          $tmp_arr['batch_extend_type'] = $res['batch_extend_type'];
          $extend_date_arr[$res['batch_id']] = $tmp_arr;
        }
      }

      return $extend_date_arr;
    }

    //BATCH CANDIDATE EDIT DATE = BATCH START DATE+2
    //IT IS USED TO CALCULATE BATCH CANDIDATE EDIT DATE EXCLUDING ALL HOLIDAYS
    function calculate_batch_date_for_edit_candidate($batches_id_edit_candidate_arr = array(), $batch_id=0,$numDays=2)
    {
      $batch_data = $this->master_model->getRecords('iibfbcbf_agency_centre_batch acb', array('acb.centre_id' => $this->session->userdata('IIBF_BCBF_LOGIN_ID'), 'acb.batch_id' => $batch_id, 'acb.is_deleted' => '0'), "acb.*");
            
      if(count($batch_data) > 0)
      {
        $batch_holidays = $batch_data[0]['batch_holidays'];
        $batch_start_date = $batch_data[0]['batch_start_date'];        

        $finalDate = date('Y-m-d', strtotime($batch_start_date. ' + '.$numDays.' days'));
        if($batch_holidays != '')
        {
          $holiday_arr = explode(",",$batch_holidays);  
          $finalDate = strtotime($batch_start_date);
          for($i=1;$i<=$numDays;$i)
          {
            $finalDate = strtotime("+1 day", $finalDate);
            if (!in_array(date("Y-m-d", $finalDate),$holiday_arr))
            {
              $i++;
            }       
          }

          $finalDate = date('Y-m-d', $finalDate);
        }

        //ALLOW AGENCY TO ADD/EDIT CANDIDATE AFTER ADD/EDIT DATE IS OVER
        if(count($batches_id_edit_candidate_arr) > 0)
        {
          /* foreach($batches_id_edit_candidate_arr as $chk_batch_id => $extend_date)
          {
            if($chk_batch_id == $batch_id && $extend_date != "" && $extend_date != "0000-00-00") { return $extend_date; exit(); }
            else { return $finalDate; }
          } */

          if(array_key_exists($batch_id,$batches_id_edit_candidate_arr))
          {
            foreach($batches_id_edit_candidate_arr as $res)
            {
              if($res['batch_id'] == $batch_id)
              {
                if($res['batch_extend_date'] != "" && $res['batch_extend_date'] != "0000-00-00")
                {
                  return $res['batch_extend_date']; exit();
                }
                else { return $finalDate; }
              }
            }
          }
          else { return $finalDate; } 
        } 
        else { return $finalDate; exit(); }
      }
      else
      {
        return date('Y-m-d', strtotime(' - 1 days'));
      }
    }//END

    //START : THIS FUNCTION IS USED TO CHECK THE CANDIDATE IS ELIGIBLE TO ADD IN BATCH OR NOT. CHECK DUPLICATION FOR MOBILE, EMAIL, ID PROOF, AADHAR
    //CANDIDATE ABLE TO ADD IN NEW BATCH IF BELOW CONDITIONS ARE TRUE
    //IF BATCH IS CANCELLED or IF 270 DAYS IS COMPLETE FROM CANDIDATE DATE OF CREATION OR CANDIDATES 3 ATTEMPT IS OVER
    function validation_check_candidate_eligibility_to_add_in_new_batch($candidate_id=0, $chk_column='', $chk_value='')
    {
      if($chk_column == 'mobile') { $this->db->where('cand.mobile_no', $chk_value); }
      else if($chk_column == 'email') { $this->db->where('cand.email_id', $chk_value); }
      else if($chk_column == 'id_proof') { $this->db->where('cand.id_proof_number', $chk_value); }
      else if($chk_column == 'aadhar') { $this->db->where('cand.aadhar_no', $chk_value); }

      $this->db->order_by('cand.candidate_id', 'DESC');
      $date_270_Days_ago = date('Y-m-d', strtotime("-270days"));
      $this->db->where(" (DATE(cand.created_on) >= '".$date_270_Days_ago."' OR btch.batch_end_date >= '".$date_270_Days_ago."') ");
      $this->db->join('iibfbcbf_agency_centre_batch btch', 'btch.batch_id = cand.batch_id','INNER');
      $candidate_data = $this->master_model->getRecords('iibfbcbf_batch_candidates cand', array('cand.is_deleted' => '0', 'cand.candidate_id !=' => $candidate_id, 'btch.batch_status !=' => '7', 'cand.re_attempt <'=>'3'), 'cand.candidate_id, cand.agency_id, cand.centre_id, cand.batch_id, cand.mobile_no, cand.email_id, cand.id_proof_number, cand.aadhar_no, cand.created_on, btch.batch_end_date, cand.hold_release_status');

      if(count($candidate_data) > 0)
      {
        if($candidate_data[0]['batch_end_date'] < date("Y-m-d") && $candidate_data[0]['hold_release_status'] != '3')
        {
          $candidate_data = array(); 
        }
      }
      return $candidate_data;
    }


    //START : THIS FUNCTION IS USED TO CHECK THE CURRENT EXAM IS ACTIVATED OR NOT. 
    public function get_exam_activation_details($enc_exam_code='0', $agency_id='0',$apply_from='') //apply_from = individual or bulk
    {
      if($apply_from == 'bulk')
      {
        $allow_exam_codes = '0';
        if($agency_id != '0')
        {
          $agency_data = $this->master_model->getRecords('iibfbcbf_agency_master',array('agency_id'=>$agency_id), 'agency_id, allow_exam_codes, allow_exam_types');
          
          if(count($agency_data) > 0)
          {
            if($agency_data[0]['allow_exam_codes'] != '') 
            {  
              $allow_exam_codes .= ','.$agency_data[0]['allow_exam_codes'];
            }
          }
        }
      }
      else if($apply_from == 'individual')
      {
        $allow_exam_codes = '1037,1038';
      }
      else if($apply_from == 'csc')
      {
        $allow_exam_codes = '1039,1040';
      }

      $data['exam_code'] = $exam_code = url_decode($enc_exam_code);      
      if(in_array($exam_code, array(1037,1038)))
      {
        $this->db->where('sm.exam_date > ', date("Y-m-d"));
      }
      
      $this->db->where_in('em.exam_code', $allow_exam_codes,FALSE);
      $this->db->having(' (CURRENT_TIMESTAMP BETWEEN ChkExamStart AND ChkExamEnd) ');
      $this->db->join('iibfbcbf_exam_activation_master eam', 'eam.exam_code = em.exam_code', 'INNER');
      $this->db->join('iibfbcbf_exam_subject_master sm','sm.exam_code = em.exam_code', 'INNER');
      $active_exam_data = $this->master_model->getRecords('iibfbcbf_exam_master em', array('em.exam_delete'=>'0', 'eam.exam_activation_delete' => '0', 'sm.subject_delete' =>'0', 'em.exam_code'=>$exam_code), "em.exam_code, em.description, em.exam_type, IF(em.exam_type = 1,'Basic', IF(em.exam_type = 2, 'Advanced','')) AS DispExamType, eam.exam_period, CONCAT(eam.exam_from_date,' ', eam.exam_from_time) AS ChkExamStart, CONCAT(eam.exam_to_date,' ', eam.exam_to_time) AS ChkExamEnd, eam.exam_from_date, eam.exam_from_time, eam.exam_to_date, eam.exam_to_time, eam.exam_mode, (CASE WHEN em.exam_code = '1039' THEN '0000-00-00' WHEN em.exam_code = '1040' THEN '0000-00-00' ELSE sm.exam_date END) AS exam_date");
      //_pq(1);
      return $active_exam_data;
    }//END : THIS FUNCTION IS USED TO CHECK THE CURRENT EXAM IS ACTIVATED OR NOT. 


    //START : THIS FUNCTION IS USED TO CHECK THE CANDIDATE IS ELIGIBLE OR NOT FOR APPLY EXAM
    public function get_exam_candidate_details($enc_exam_code='0', $enc_exam_period='0', $enc_candidate_id='0', $login_agency_or_centre_id='0',$apply_from='')
    {
      $buffer_days_after_training_end_date = $this->buffer_days_after_training_end_date;
      $buffer_days_after_candidate_add_date = $this->buffer_days_after_candidate_add_date;

      $result_arr = array();
      $flag = $response_msg = 'error';
      $result_data = array();

      $exam_code = $exam_period = $candidate_id = 0;
      if($enc_exam_code != '0') { $exam_code = url_decode($enc_exam_code); }
      if($enc_exam_period != '0') { $exam_period = url_decode($enc_exam_period); }
      if($enc_candidate_id != '0') { $candidate_id = url_decode($enc_candidate_id); }
        
      //$chk_batch_type = $this->get_batch_type($exam_code);
      $agency_id = '0';
      $cand_data = $this->master_model->getRecords('iibfbcbf_batch_candidates cand',array('cand.candidate_id'=>$candidate_id), 'cand.candidate_id, cand.agency_id');
      if(count($cand_data) > 0)
      {
        $agency_id = $cand_data[0]['agency_id'];
      }

      $active_exam_data = $this->get_exam_activation_details($enc_exam_code,$agency_id,$apply_from);      
      
      $chk_batch_type = 0;
      if(count($active_exam_data) > 0)
      {
        $chk_batch_type = $active_exam_data[0]['exam_type'];
      }

      $todays_date = date("Y-m-d"); //'2023-12-16';//date("Y-m-d"); 

      if($login_agency_or_centre_id != '0' && $login_agency_or_centre_id != '') { $this->db->where('cand.centre_id', $login_agency_or_centre_id); }
      $this->db->join('state_master sm', 'sm.state_code = cand.state', 'LEFT');
      $this->db->join('city_master cm1', 'cm1.id = cand.city', 'LEFT'); 
      $this->db->join('iibfbcbf_centre_master cm2', 'cm2.centre_id = cand.centre_id', 'INNER');
      $this->db->join('city_master cm3', 'cm3.id = cm2.centre_city', 'LEFT');
      $this->db->join('state_master sm2', 'sm2.state_code = cm2.centre_state', 'LEFT');
      $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = cand.agency_id', 'LEFT'); 
      //$this->db->where( " (NOT EXISTS (SELECT el.member_no FROM iibfbcbf_eligible_master el WHERE el.exam_status IN('F','P') AND el.member_no = cand.regnumber AND el.member_no !='')) ", "", FALSE); 

      $candidate_data = $this->master_model->getRecords('iibfbcbf_batch_candidates cand', array('cand.is_deleted' => '0', 'cand.candidate_id' => $candidate_id), "cand.*, IF(cand.gender=1,'Male','Female') AS DispGender, IF(cand.qualification=1, 'Under Graduate', IF(cand.qualification=2,'Graduate','Post Graduate')) AS DispQualification, IF(cand.id_proof_type=1, 'Aadhar Card', IF(cand.id_proof_type=2,'Driving Licence',IF(cand.id_proof_type=3,'Employees Card', IF(cand.id_proof_type=4,'Pan Card','Passport')))) AS DispIdProofType, IF(cand.qualification_certificate_type=1, '10th Pass', IF(cand.qualification_certificate_type=2,'12th Pass',IF(cand.qualification_certificate_type=3,'Graduation',IF(cand.qualification_certificate_type=4,'Post Graduation','')))) AS DispQualificationCertificateType, IF(cand.hold_release_status=1,'Auto Hold', IF(cand.hold_release_status=2,'Manual Hold','Release')) AS Disphold_release_status, sm.state_name, cm1.city_name, cm2.centre_state AS LoggedInCentreState, sm2.state_no, sm2.state_name AS LoggedInCentreStateName, sm2.exempt, cm2.centre_name, cm2.centre_username, cm2.status AS CenterStatus, cm2.is_deleted AS CenterIsDeleted, am.agency_name, am.agency_code, am.allow_exam_types, am.is_active AS AgencyStatus, am.is_deleted AS AgencyIsDeleted, cm3.city_name AS centre_city_name");
      
      if(count($candidate_data) > 0)
      {
        if($candidate_data[0]['hold_release_status'] == '3')
        {
          if($candidate_data[0]['id_proof_file'] != '' && file_exists('uploads/iibfbcbf/id_proof/'.$candidate_data[0]['id_proof_file']))
          {
            if($candidate_data[0]['qualification_certificate_file'] != '' && file_exists('uploads/iibfbcbf/qualification_certificate/'.$candidate_data[0]['qualification_certificate_file']))
            {
              if($candidate_data[0]['candidate_photo'] != '' && file_exists('uploads/iibfbcbf/photo/'.$candidate_data[0]['candidate_photo']))
              {
                if($candidate_data[0]['candidate_sign'] != '' && file_exists('uploads/iibfbcbf/sign/'.$candidate_data[0]['candidate_sign']))
                {
                  if(date('Y-m-d', strtotime("+".$buffer_days_after_candidate_add_date."days", strtotime($candidate_data[0]['created_on']))) >= $todays_date)
                  {
                    if(date('Y-m-d', strtotime("+".$buffer_days_after_training_end_date."days", strtotime($candidate_data[0]['created_on']))) < $todays_date)
                    {
                      if($candidate_data[0]['CenterStatus'] == '1')
                      {
                        if($candidate_data[0]['CenterIsDeleted'] == '0')
                        {
                          if($candidate_data[0]['AgencyStatus'] == '1')
                          {
                            if($candidate_data[0]['AgencyIsDeleted'] == '0')
                            {
                              if($candidate_data[0]['re_attempt'] < '3')
                              {
                                if($login_agency_or_centre_id != '0' && $login_agency_or_centre_id != '') { $this->db->where('btch.centre_id', $login_agency_or_centre_id); }
                                $batch_data = $this->master_model->getRecords('iibfbcbf_agency_centre_batch btch', array('btch.batch_id'=>$candidate_data[0]['batch_id'], 'btch.is_deleted'=>'0'), "btch.batch_start_date, btch.batch_end_date, btch.batch_status, btch.batch_type");

                                if(count($batch_data) > 0)
                                {
                                  if($batch_data[0]['batch_status'] == '3')
                                  {
                                    if($batch_data[0]['batch_type'] == $chk_batch_type)
                                    {
                                      if(date('Y-m-d', strtotime("+".$buffer_days_after_training_end_date."days", strtotime($batch_data[0]['batch_end_date']))) < $todays_date)
                                      { 
                                        if(date('Y-m-d', strtotime("+".$buffer_days_after_candidate_add_date."days", strtotime($batch_data[0]['batch_end_date']))) >= $todays_date)
                                        {
                                          $exam_data = $this->master_model->getRecords('iibfbcbf_member_exam mem_ex', array('mem_ex.candidate_id' => $candidate_id), "mem_ex.pay_status, mem_ex.exam_code, mem_ex.exam_period, mem_ex.exam_date, mem_ex.payment_mode", array('mem_ex.member_exam_id'=>"DESC"));
                                          /* _pa($exam_data);//xxx
                                          _pa($active_exam_data,1);//xxx */
                                          //_pq(1);
                                          /* , 'mem_ex.exam_period' => $exam_period */
                                                                                
                                          if(count($exam_data) == 0)
                                          {
                                            $flag = 'success';
                                          }
                                          else 
                                          {
                                            $error_exam_data_flag = 0;
                                            foreach($exam_data as $exam_data_res)
                                            {
                                              $chk_payment_status_arr = array(1,2,3);
                                              if($exam_data_res['payment_mode'] == 'Bulk') 
                                              { 
                                                $chk_payment_status_arr = array(1,3); 
                                              }
                                              else if($exam_data_res['payment_mode'] == 'Individual' || $exam_data_res['payment_mode'] == 'CSC') 
                                              { 
                                                $chk_payment_status_arr = array(1,2); 
                                              }

                                              if(in_array($exam_data_res['pay_status'], $chk_payment_status_arr))
                                              {
                                                if(in_array($exam_data_res['exam_code'], array(1037,1038,1041,1042)) && $exam_data_res['exam_code'] == $active_exam_data[0]['exam_code'] && $exam_data_res['exam_period'] == $active_exam_data[0]['exam_period'])
                                                {
                                                  $response_msg = 'The candidate has already applied for exam';
                                                  $error_exam_data_flag = 1;
                                                }
                                                else if(date('Y-m-d') <= $exam_data_res['exam_date'])
                                                {
                                                  $response_msg = 'The candidate has already applied for exam.';
                                                  $error_exam_data_flag = 1;
                                                }
                                              }
                                            }

                                            if($error_exam_data_flag == 0)
                                            {
                                              $flag = 'success';
                                            }

                                            /* if($login_agency_or_centre_id != '0') //ONLY FOR CENTER LOGIN
                                            {
                                              if($exam_data[0]['pay_status'] == '1')
                                              {
                                                $response_msg = 'The candidate has already applied for exam';
                                              }
                                              else
                                              {
                                                $flag = 'success';
                                              }
                                            }
                                            else //ONLY FOR INDIVIDUAL APPLY
                                            {
                                              if($exam_data[0]['pay_status'] != '0' && $exam_data[0]['pay_status'] != '4')
                                              {
                                                $response_msg = 'The candidate has already applied for exam';
                                              }
                                              else
                                              {
                                                $flag = 'success';
                                              }
                                            }  */                                     
                                          }
                                          
                                          if($flag == 'success')
                                          {
                                            if($candidate_data[0]['regnumber'] != "")
                                            {
                                              /* $eligible_data = $this->master_model->getRecords(' iibfbcbf_eligible_master eligib', array('eligib.member_no !=' =>'', 'eligib.member_no' => $candidate_data[0]['regnumber'], 'eligib.exam_code' => $exam_code), "exam_status, remark");
                                              
                                              if(count($eligible_data) > 0)
                                              {
                                                if($eligible_data[0]['exam_status'] == 'F' || $eligible_data[0]['exam_status'] == 'V')
                                                { 
                                                  //VALID MEMBER
                                                }
                                                else
                                                {
                                                  $flag = 'error';
                                                  $response_msg = 'The candidate is not eligible to apply for exam as its status is '.$eligible_data[0]['exam_status'].' in eligible master.';
                                                  if($eligible_data[0]['remark'] != "") { $response_msg = $eligible_data[0]['remark']; }
                                                }
                                              } */

                                              //ELIGIBLE MASTER API CODE GOES HERE
                                              $eligible_api_res = $this->iibf_bcbf_eligible_master_api($exam_code,$exam_period, $candidate_data[0]['regnumber']);    
                                              //_pa($eligible_api_res,1);                                      
                                              if($eligible_api_res['api_res_flag'] == 'success')
                                              {
                                                if(isset($eligible_api_res['api_res_response'][0]) && count($eligible_api_res['api_res_response'][0]) > 0)
                                                {
                                                  $eligible_data = $eligible_api_res['api_res_response'][0];
                                                  if($eligible_data['pas_fail_val'] == "A" || $eligible_data['pas_fail_val'] == "F")
                                                  {
                                                    //VALID MEMBER
                                                  }
                                                  else
                                                  {
                                                    $eligible_res_msg = '-';
                                                    if($eligible_data['pas_fail_val'] != "") { $eligible_res_msg = $eligible_data['pas_fail_val']; }

                                                    $flag = 'error';
                                                    $response_msg = 'The candidate is not eligible to apply for exam as its valid attempt exist as per eligible ('.$eligible_res_msg.')';
                                                    //if($eligible_data['remark'] != "") { $response_msg .= ' ('.$eligible_data['remark'].')'; }
                                                  }
                                                }
                                                else
                                                {
                                                  $flag = 'error';
                                                  $response_msg = 'No response from eligible API. Please try after sometime.';
                                                }
                                              }
                                              else
                                              {
                                                $flag = 'error';
                                                $response_msg = 'Error occurred in eligible API. Please try after sometime.';
                                              }
                                            }
                                            
                                            if($flag == 'success')
                                            {
                                              $final_arr = $candidate_data[0];
                                              $final_arr['batch_start_date'] = $batch_data[0]['batch_start_date'];
                                              $final_arr['batch_end_date'] = $batch_data[0]['batch_end_date'];
                                              $final_arr['batch_status'] = $batch_data[0]['batch_status'];
                                              $final_arr['batch_type'] = $batch_data[0]['batch_type'];
                                              
                                              $result_data[] = $final_arr;
                                            }
                                          }
                                        }
                                        else { $response_msg = 'The candidate is not eligible to apply for exam as its batch end date is not over'; }
                                      }
                                      else 
                                      { 
                                        //$response_msg = 'The candidate is not eligible to apply for exam as its batch '.$buffer_days_after_training_end_date.' days is not over'; 
                                        $response_msg = 'The candidate is not eligible to apply for exam as its batch end date is not over'; 
                                      }
                                    }
                                    else { $response_msg = 'The candidate is not eligible to apply for exam as its batch type is different.'; }
                                  }
                                  else { $response_msg = 'The candidate is not eligible to apply for exam as its batch status is not Go Ahead.'; }
                                }
                                else { $response_msg = 'The candidate is not eligible to apply for exam as its batch is deleted.'; }
                              }
                              else { $response_msg = 'The candidate is not eligible to apply for exam as its 3 attempt is over.'; }
                            }
                            else { $response_msg = 'The candidate is not eligible to apply for exam as its agency is deleted'; }
                          }
                          else { $response_msg = 'The candidate is not eligible to apply for exam as its agency is not active'; }
                        }
                        else { $response_msg = 'The candidate is not eligible to apply for exam as its center is deleted'; }
                      }
                      else { $response_msg = 'The candidate is not eligible to apply for exam as its center is not active'; }
                    }
                    else { $response_msg = 'The candidate is not eligible to apply for exam as its training is not over'; }
                  }
                  else { $response_msg = 'The candidate is not eligible to apply for exam as its '.$buffer_days_after_candidate_add_date.' days is over'; }
                }
                else { $response_msg = 'The candidate is not eligible to apply for exam as its sign is missing.'; }
              }
              else { $response_msg = 'The candidate is not eligible to apply for exam as its photo is missing.'; }
            }
            else { $response_msg = 'The candidate is not eligible to apply for exam as its qualification certificate is missing.'; }
          }
          else { $response_msg = 'The candidate is not eligible to apply for exam as its id proof is missing.'; }
        }
        else { $response_msg = 'The candidate is not eligible to apply for exam as its status is not Release.'; }
      }
      else { $response_msg = 'Invalid candidate details.'; }

      $result_arr['flag'] = $flag;
      $result_arr['response_msg'] = $response_msg;
      $result_arr['result_data'] = $result_data;
      return $result_arr; 
      exit;
    }//END : THIS FUNCTION IS USED TO CHECK THE CANDIDATE IS ELIGIBLE OR NOT FOR APPLY EXAM

    //START : THIS FUNCTION IS USED TO GET THE BATCH TYPE USING EXAM CODE
    /* function get_batch_type($exam_code='')
    {
      $batch_type = '0';
      if($exam_code == '5001') { $batch_type = '1'; }
      else if($exam_code == '5002') { $batch_type = '2'; }
      return $batch_type;
    } */
    //END : THIS FUNCTION IS USED TO GET THE BATCH TYPE USING EXAM CODE

    //START : THIS FUNCTION IS USED TO GET THE EXAM CODE USING BATCH TYPE
    function get_exam_code_individual($batch_type='')
    {
      $exam_code = '0';
      /* if($batch_type == '1') { $exam_code = '5001'; }
      else if($batch_type == '2') { $exam_code = '5002'; }
      return $exam_code; */
      
      $this->db->where_in('em.exam_code',array(1037,1038),FALSE);
      $this->db->having(' (CURRENT_TIMESTAMP BETWEEN ChkExamStart AND ChkExamEnd) ');
      $this->db->join('iibfbcbf_exam_activation_master eam', 'eam.exam_code = em.exam_code', 'INNER');
      $get_active_exam_data = $this->master_model->getRecords('iibfbcbf_exam_master em', array('em.exam_delete'=>'0', 'eam.exam_activation_delete' => '0', 'em.exam_type'=>$batch_type), "em.exam_code, em.description, em.exam_type, CONCAT(eam.exam_from_date,' ', eam.exam_from_time) AS ChkExamStart, CONCAT(eam.exam_to_date,' ', eam.exam_to_time) AS ChkExamEnd");
      if(count($get_active_exam_data) > 0)
      {
        $exam_code = $get_active_exam_data[0]['exam_code'];
      }
      return $exam_code;
    } 
    //END : THIS FUNCTION IS USED TO GET THE EXAM CODE USING BATCH TYPE


    function get_group_code($exam_code=0,$exam_period=0,$regnumber=0)
    {
      $group_code = "";      
      if($regnumber > 0 && $regnumber != "")
      {
        /* $group_code_res = $this->master_model->getRecords('iibfbcbf_eligible_master', array('member_no' => $regnumber),'app_category');
        if(count($group_code_res) > 0)
        {
          $group_code = $group_code_res[0]['app_category'];
          if($group_code != "")
          {
            if($group_code == "R") { $group_code = "B1_1"; }
            else if($group_code == "S1") { $group_code = "B1_2"; }
          }
          else { $group_code = "B1_1"; }
        } */
        
        //ELIGIBLE MASTER API CODE GOES HERE
        $eligible_api_res = $this->iibf_bcbf_eligible_master_api($exam_code,$exam_period, $regnumber);
        //_pa($eligible_api_res,1);
        if($eligible_api_res['api_res_flag'] == 'success')
        {
          if(isset($eligible_api_res['api_res_response'][0]) && count($eligible_api_res['api_res_response'][0]) > 0)
          {
            $eligible_data = $eligible_api_res['api_res_response'][0];
            if($eligible_data['app_cat'] != "")
            {
              $group_code = $eligible_data['app_cat'];

              /* if($group_code == "R") { $group_code = "B1_1"; }
              else if($group_code == "S1") { $group_code = "B1_2"; } */
            }
          }
        }
      }
      else
      {
        $group_code = "B1_1";
      }

      return $group_code;
    }

    public function iibf_bcbf_eligible_master_api($exam_code=0, $exam_period=0,$member_no=0)
    {
      $this->load->helper('iibfbcbf/iibf_bcbf_helper');
      $this->load->helper('url');
      $this->load->library('user_agent');

      $api_res_flag = 'error';
      $api_res_msg = '';
      
      $add_log_data = array();
      $add_log_data['exam_code'] = $exam_code;
      $add_log_data['exam_period'] = $exam_period;
      $add_log_data['member_no'] = $member_no;
      $add_log_data['session_data'] = json_encode($_SESSION);
      $add_log_data['class_name'] = $this->router->fetch_class();
      $add_log_data['method_name'] = $this->router->fetch_method();
      $add_log_data['current_url'] = current_url();
      $add_log_data['created_on'] = date('Y-m-d H:i:s');
      $log_id = $this->master_model->insertRecord('iibfbcbf_eligible_api_response_log',$add_log_data,true);
      
      //$api_url="http://10.10.233.66:8092/getBCBFEligibleData/".$exam_code."/".$exam_period."/".$member_no; //OLD API 
      $api_url="http://10.10.233.76:8089/getBCBFEligibleData/".$exam_code."/".$exam_period."/".$member_no;	//NEW API ADDED BY SAGAR ON 2024-03-19					
      
      $string = preg_replace('/\s+/', '+', $api_url);
      $x = curl_init($string);
      curl_setopt($x, CURLOPT_HEADER, 0);    
      curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);
      curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);    
      curl_setopt($x, CURLOPT_SSL_VERIFYHOST, FALSE);
      curl_setopt($x, CURLOPT_SSL_VERIFYPEER, FALSE);
      
      $result = curl_exec($x);			
      if(curl_errno($x)) //CURL ERROR
      {
        $api_res_msg = curl_error($x);
      }
      else
      {
        $response_arr = json_decode($result,true);

        if(is_array($response_arr) && count($response_arr) > 0 && $response_arr[0]['exam_id'] == $exam_code && $response_arr[0]['eligible_period'] == $exam_period && $response_arr[0]['member_code'] == $member_no)
        {
          $api_res_flag = 'success';
          $api_res_msg = $response_arr;
        }
      }
      curl_close($x);

      $api_result_arr = array();
      $api_result_arr['api_res_flag'] = $api_res_flag;
			$api_result_arr['api_res_response'] = $api_res_msg;			

      if(isset($log_id) && $log_id > 0)
      {
        $up_log_data = array();
        $up_log_data['api_response'] = $result;
        $up_log_data['updated_on'] = date('Y-m-d H:i:s');
        $this->master_model->updateRecord('iibfbcbf_eligible_api_response_log', $up_log_data,  array('log_id'=>$log_id));
      }

			return $api_result_arr;
    }

    function iibfbcbf_send_mail_common($mail_arg=array())
    {
      $subject = $mail_content = $to_email = $to_name = $cc_email = $bcc_email = $attachment = '';
      $from_email='logs@iibf.esdsconnect.com'; 
      $from_name='IIBF';
      $reply_to_email='noreply@iibf.org.in';
      $reply_to_name='IIBF';
      $view_flag = '0'; 
      $is_header_footer_required='0';
      $is_smtp='1';
      
      if(count($mail_arg) > 0)
      {
        if(isset($mail_arg['subject']) && $mail_arg['subject'] != '') { $subject = $mail_arg['subject']; }
        if(isset($mail_arg['mail_content']) && $mail_arg['mail_content'] != '') { $mail_content = $mail_arg['mail_content']; }
        if(isset($mail_arg['to_email']) && $mail_arg['to_email'] != '') { $to_email = $mail_arg['to_email']; }
        if(isset($mail_arg['to_name']) && $mail_arg['to_name'] != '') { $to_name = $mail_arg['to_name']; }
        if(isset($mail_arg['cc_email']) && $mail_arg['cc_email'] != '') { $cc_email = $mail_arg['cc_email']; }
        if(isset($mail_arg['bcc_email']) && $mail_arg['bcc_email'] != '') { $bcc_email = $mail_arg['bcc_email']; }
        if(isset($mail_arg['attachment']) && $mail_arg['attachment'] != '') { $attachment = $mail_arg['attachment']; }
        
        if(isset($mail_arg['from_email']) && $mail_arg['from_email'] != '') { $from_email = $mail_arg['from_email']; }
        if(isset($mail_arg['from_name']) && $mail_arg['from_name'] != '') { $from_name = $mail_arg['from_name']; }
        if(isset($mail_arg['reply_to_email']) && $mail_arg['reply_to_email'] != '') { $reply_to_email = $mail_arg['reply_to_email']; }
        if(isset($mail_arg['reply_to_name']) && $mail_arg['reply_to_name'] != '') { $reply_to_name = $mail_arg['reply_to_name']; }
        if(isset($mail_arg['view_flag']) && $mail_arg['view_flag'] != '') { $view_flag = $mail_arg['view_flag']; }
        if(isset($mail_arg['is_header_footer_required']) && $mail_arg['is_header_footer_required'] != '') { $is_header_footer_required = $mail_arg['is_header_footer_required']; }
        if(isset($mail_arg['is_smtp']) && $mail_arg['is_smtp'] != '') { $is_smtp = $mail_arg['is_smtp']; }
      }

      if($subject != '' && $from_email != '' && $to_email != '' && $mail_content != '')
      {
        if($is_header_footer_required == '1')
        {
          $mail_body ='
            <!DOCTYPE html>
            <html>
              <head>
                <meta charset="UTF-8">
                <title>Email</title>
                <style type="text/css">
                  body { font-family: Times New Roman; font-size: 14px; color: #000; margin: 0; padding: 0; }                            
                </style>
              </head>
              <body>
                <br>
                <table cellspacing="0" cellpadding="0" width="600px" border="1" style="width: 100%; max-width:800px; border-collapse: collapse; font-size: 14px; line-height: 20px; border: 1px solid #041f38; margin: 0 auto; color:#000;">
                  <tbody>                    
                    <tr>
                      <td style="background-color: #00a7f6;color: #fff; font-size: 20px; font-weight: bold; text-align: center; padding: 20px 5px 15px; border-bottom: 1px solid #000; line-height: 25px;">INDIAN INSTITUTE OF BANKING & FINANCE<br><span style="font-size: 16px; font-weight: 600;">(AN ISO 21001:2018 Certified)</span></td>
                    </tr>
                    <tr>
                      <td style="padding:35px 40px 30px">                      
                        '.$mail_content.'                      
                      </td>
                    </tr>
                    <tr>
                      <td style="background-color: #00a7f6; color: #fff; font-weight: bold; text-align: center; padding: 0 8px; height: 38px;">&copy; '.date('Y').' IIBF. All rights reserved.</td>
                    </tr>
                  </tbody>
                </table>
                <br>
              </body>
            </html>';
        }
        else if($is_header_footer_required == '0')
        {
          $mail_body ='
            <!DOCTYPE html>
            <html>
              <head>
                <meta charset="UTF-8">
                <title>Email</title>
                <style type="text/css">
                  body { font-family: Times New Roman; font-size: 14px; color: #000; margin: 0; padding: 0; }                            
                </style>
              </head>
              <body>
                <br>
                <table cellspacing="0" cellpadding="0" width="100%" style="width: 100%; border-collapse: collapse; font-size: 14px; line-height: 20px; color:#000;">
                  <tbody>                    
                    <tr>
                      <td style="padding:10px">                      
                        '.$mail_content.'                      
                      </td>
                    </tr>
                  </tbody>
                </table>
                <br>
              </body>
            </html>';
        }

        $mail_body .='
                  ';
          
        if($view_flag=='1')
        {
          echo "<br>From = ".$from_email." (".$from_name.")";
          echo "<br>To = ".$to_email." (".$to_name.")";
          echo "<br>CC = ".$cc_email;					
          echo "<br>BCC = ".$bcc_email;					
          echo "<br>Reply to = ".$reply_to_email." (".$reply_to_name.")";			
          echo "<br>Subject = ".$subject;
          if(is_array($attachment)) { echo "<br>Attachment = "; print_r($attachment); } else { echo "<br>Attachment = ".$attachment; }
          echo "<br>Message = ".$mail_body; 
          exit;
        }
        
        $this->load->library('email');
        if($is_smtp == "0")
        {						
          //$config['protocol'] = 'sendmail';
          //$config['mailpath'] = '/usr/sbin/sendmail';
          $config['charset'] = 'iso-8859-1';
          $config['charset'] = 'UTF-8';
          $config['wordwrap'] = TRUE;
          $config['mailtype'] = 'html';
          $this->email->initialize($config);
          //$this->email->subject($subject." php mail");
        }
        else
        {
          $this->Emailsending->setting_smtp();
        }
        
        $this->email->clear(TRUE);
        $this->email->set_newline("\r\n");
        
        $to_email = 'sagar.matale@esds.co.in';
        $cc_email = 'pre_production@esds.co.in,anil.s@esds.co.in';
        $bcc_email = '';  

        $this->email->from($from_email, $from_name);
        $this->email->to($to_email, $to_name); 
        $this->email->subject($subject." : STAGING ENV");
        $this->email->message($mail_body);
        
        if($reply_to_email != "") { $this->email->reply_to($reply_to_email, $reply_to_name); }
        if($cc_email != "") { $this->email->cc($cc_email); }
        if($bcc_email != '') { $this->email->bcc($bcc_email); }
        
        if(is_array($attachment))
        {
          foreach($attachment as $row)
          {
            $this->email->attach($row);
          }
        }
        else
        {
          if($attachment!=NULL || $attachment!='')
          {
            $this->email->attach($attachment);
          }
        }
        
        if($this->email->send())
        {
          $final_msg = 'success';
        }
        else
        {
          $final_msg = 'error. Email not send<br>';
          $final_msg .= $this->email->print_debugger();
        }
        
        return $final_msg;
        $this->email->clear();				
      }
      else
      {
        return 'error - invalid form fields';
      }
    }      
    
    
    function iibfbcbf_get_fees($exam_code=0,$exam_period=0)
    {
      $fresh_fee = $rep_fee = 0;
      $fee_data = $this->master_model->getRecords('iibfbcbf_exam_fee_master',array('fee_delete'=>'0', 'member_category'=>'NM', 'exempt'=>'NE', 'exam_code'=>$exam_code, 'exam_period'=>$exam_period));
      if(count($fee_data) > 0)
      {
        foreach($fee_data as $fee_res)
        {
          if($fee_res['group_code'] == 'B1_1') { $fresh_fee = $fee_res['fee_amount']; }
          else if($fee_res['group_code'] == 'B1_2' || $fee_res['group_code'] == 'B2_1') { $rep_fee = $fee_res['fee_amount']; }
        }
      }
      $result['fresh_fee'] = $fresh_fee;
      $result['rep_fee'] = $rep_fee;        
      return $result;
    }

    function generate_admit_card_common($enc_pt_id='0')
    {
      $venue_master_eligible_exam_codes_arr = array(1041,1042);
      $csc_venue_master_eligible_exam_codes_arr = array(1039,1040);

      $pt_id = url_decode($enc_pt_id);
      $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction',array('id'=>$pt_id, 'status'=>'1'), 'id, agency_id, centre_id, exam_ids, exam_code, exam_period, gateway, amount, date, agency_code, transaction_no, UTR_no, UTR_slip_file, pay_count, receipt_no, description, transaction_details, payment_mode, status');

      $eligible_exam_code_for_admit_card_arr = array(1039,1040,1041,1042);
      if(count($payment_data) > 0 && in_array($payment_data[0]['exam_code'], $eligible_exam_code_for_admit_card_arr))
      {
        $centre_data = $this->master_model->getRecords('iibfbcbf_centre_master',array('centre_id'=>$payment_data[0]['centre_id']), 'centre_id, centre_username, centre_name');

        $subject_master = $this->master_model->getRecords('iibfbcbf_exam_subject_master',array('exam_code'=>$payment_data[0]['exam_code'],'subject_delete'=>'0','group_code'=>'C'),'subject_code, subject_description',array('subject_code'=>'ASC'));

        $agency_data = $this->master_model->getRecords('iibfbcbf_agency_master',array('agency_id'=>$payment_data[0]['agency_id']), 'agency_id, agency_name, agency_code');
        
        $exam_mode = '';
        $exam_activation_data = $this->master_model->getRecords('iibfbcbf_exam_activation_master',array('exam_code'=>$payment_data[0]['exam_code'], 'exam_period'=>$payment_data[0]['exam_period'], 'exam_activation_delete' => '0'), 'exam_mode');
        if(count($exam_activation_data) > 0)
        {
          $exam_mode = $exam_activation_data[0]['exam_mode'];
        }
        
        //_pa($payment_data,1);

        $this->db->where(" (me.member_exam_id IN (".$payment_data[0]['exam_ids'].") ) ");
        $this->db->join('iibfbcbf_batch_candidates cand', 'cand.candidate_id = me.candidate_id', 'INNER');
        $this->db->join('city_master cm', 'cm.id = cand.city', 'LEFT');
        $this->db->join('state_master sm', 'sm.state_code = cand.state', 'LEFT');
        $this->db->join('iibfbcbf_exam_medium_master mm', 'mm.medium_code = me.exam_medium AND mm.exam_code = me.exam_code AND mm.exam_period = me.exam_period', 'LEFT');
        $member_exam_data = $this->master_model->getRecords('iibfbcbf_member_exam me',array('me.exam_code'=>$payment_data[0]['exam_code'], 'me.exam_period'=>$payment_data[0]['exam_period']), 'me.member_exam_id, me.candidate_id, me.batch_id, me.exam_date, me.payment_mode, me.exam_centre_code, me.exam_venue_code, me.exam_time, cand.regnumber, cand.training_id, cand.salutation, cand.first_name, cand.middle_name, cand.last_name, cand.dob, cand.gender, cand.mobile_no, cand.email_id, cand.address1, cand.address2, cand.address3, cand.address4, cand.state, cand.city, cand.district, cand.pincode, cand.registration_type, cm.city_name, sm.state_name, sm.zone_code, mm.medium_description');

        //_pq();
        //_pa($member_exam_data);

        if(count($member_exam_data) > 0)
        {
          /* //START : CHECK IF ADMITCARD RECORD EXIST OR NOT. IF EXIST, DELETE IT AND INSERT NEW RECORD
          foreach($member_exam_data as $res)
          {
            $chk_admitcard_exist = $this->master_model->getRecords('iibfbcbf_admit_card_details',array('mem_exam_id'=>$res['member_exam_id'], 'batchId'=>$res['batch_id'], 'mem_mem_no'=>$res['regnumber'], 'exm_cd'=>$payment_data[0]['exam_code'], 'exm_prd'=>$payment_data[0]['exam_period']), 'admitcard_id');
            _pq();
            
            if(count($chk_admitcard_exist) > 0)
            {
              foreach($chk_admitcard_exist as $chk_admitcard_res)
              {
                $this->db->where('admitcard_id', $chk_admitcard_res['admitcard_id']);
                $this->db->delete('iibfbcbf_admit_card_details');
              }
            }
          }//END : CHECK IF ADMITCARD RECORD EXIST OR NOT. IF EXIST, DELETE IT AND INSERT NEW RECORD */
          
          foreach($member_exam_data as $res)
          {
            $chk_admitcard_exist = $this->master_model->getRecords('iibfbcbf_admit_card_details',array('mem_exam_id'=>$res['member_exam_id'], 'batchId'=>$res['batch_id'], 'mem_mem_no'=>$res['regnumber'], 'exm_cd'=>$payment_data[0]['exam_code'], 'exm_prd'=>$payment_data[0]['exam_period']), 'admitcard_id');

            $add_data = array();
            $add_data['pt_id'] = $payment_data[0]['id'];
            $add_data['mem_exam_id'] = $res['member_exam_id'];
            $add_data['batchId'] = $res['batch_id'];
            if(count($agency_data) > 0) { $add_data['institute_code'] = $agency_data[0]['agency_code']; }
            if(count($agency_data) > 0) { $add_data['inscd'] = $agency_data[0]['agency_code']; }
            if(count($centre_data) > 0) { $add_data['centre_code'] = $centre_data[0]['centre_username']; }
            //if(count($centre_data) > 0) { $add_data['center_code'] = $centre_data[0]['centre_username']; }
            if(count($centre_data) > 0) { $add_data['centre_name'] = $centre_data[0]['centre_name']; }
            //if(count($centre_data) > 0) { $add_data['center_name'] = $centre_data[0]['centre_name']; }
            $add_data['mem_type'] = $res['registration_type'];
            $add_data['mem_mem_no'] = $res['regnumber'];
            
            $gender = 'F'; if($res['gender'] == '1') { $gender = 'M'; }
            $add_data['g_1'] = $gender;

            $mam_nam_1 = $res['salutation'].' '.$res['first_name'];
            if($res['middle_name'] != "") { $mam_nam_1 .= ' '.$res['middle_name']; }
            if($res['last_name'] != "") { $mam_nam_1 .= ' '.$res['last_name']; }
            $add_data['mam_nam_1'] = $mam_nam_1;
            
            $add_data['mem_adr_1'] = $res['address1'];
            $add_data['mem_adr_2'] = $res['address2'];
            $add_data['mem_adr_3'] = $res['address3'];
            $add_data['mem_adr_4'] = $res['address4'];
            $add_data['mem_adr_5'] = $res['city_name'];
            $add_data['mem_adr_6'] = $res['district'];
            $add_data['mem_pin_cd'] = $res['pincode'];
            $add_data['zo'] = $res['zone_code'];
            $add_data['state'] = $res['state_name'];
            $add_data['exm_cd'] = $payment_data[0]['exam_code'];
            $add_data['exm_prd'] = $payment_data[0]['exam_period'];
            //$add_data['exam_period'] = $payment_data[0]['exam_period'];
            if(count($subject_master) > 0) { $add_data['sub_cd'] = $subject_master[0]['subject_code']; }
            if(count($subject_master) > 0) { $add_data['sub_dsc'] = $subject_master[0]['subject_description']; }
            $add_data['m_1'] = $res['medium_description'];
            $add_data['exam_centre_code'] = $res['exam_centre_code'];
            $get_exam_centre_name = $this->master_model->getRecords('iibfbcbf_exam_centre_master',array('centre_code'=>$res['exam_centre_code'], 'exam_name'=>$payment_data[0]['exam_code'], 'exam_period'=>$payment_data[0]['exam_period'], 'centre_delete'=>'0'), 'centre_name');
            if(count($get_exam_centre_name) > 0)
            {
              $add_data['exam_centre_name'] = $get_exam_centre_name[0]['centre_name'];
            }
                        
            if(count($agency_data) > 0) { $add_data['insname'] = $agency_data[0]['agency_name']; }
            
            $venueid = $venue_name = $venueadd1 = $venueadd2 = $venueadd3 = $venueadd4 = $venueadd5 = $venpin = '';
            if(in_array($payment_data[0]['exam_code'],$venue_master_eligible_exam_codes_arr))
            {
              $this->db->group_by('venue_code');
              $this->db->where(" FIND_IN_SET('".$payment_data[0]['exam_code']."',exam_codes) > 0 ");              
              $venue_data = $this->master_model->getRecords('iibfbcbf_exam_venue_master', array('exam_date' => $res['exam_date'], 'centre_code' => $res['exam_centre_code'], 'session_time' => $res['exam_time'], 'venue_code' => $res['exam_venue_code'], 'exam_period' => $payment_data[0]['exam_period'], 'exam_date >'=>date("Y-m-d")), 'venue_master_id, venue_code, venue_name, venue_addr1, venue_addr2, venue_addr3, venue_addr4, venue_addr5, venue_pincode', array('venue_addr1'=>'ASC'));
              if(count($venue_data) > 0)
              {
                $venueid = $venue_data[0]['venue_code'];
                $venue_name = $venue_data[0]['venue_name'];
                $venueadd1 = $venue_data[0]['venue_addr1'];
                $venueadd2 = $venue_data[0]['venue_addr2'];
                $venueadd3 = $venue_data[0]['venue_addr3'];
                $venueadd4 = $venue_data[0]['venue_addr4'];
                $venueadd5 = $venue_data[0]['venue_addr5'];
                $venpin = $venue_data[0]['venue_pincode'];
              }
              //_pa($res);

              if(count($chk_admitcard_exist) > 0)
              {
                $this->db->where('admitcard_id <',$chk_admitcard_exist[0]['admitcard_id']);
              }
              $get_admitcard_data = $this->master_model->getRecords('iibfbcbf_admit_card_details', array(
                'seat_identification !='=>'',
                'exm_cd' => $payment_data[0]['exam_code'], 
                'exm_prd' => $payment_data[0]['exam_period'], 
                'exam_date' => $res['exam_date'], 
                //'time' => $res['exam_time'],
                'exam_centre_code' => $res['exam_centre_code'],  
                'venueid' => $res['exam_venue_code']
              ), 'seat_identification', array('admitcard_id'=>'DESC'),'',1);
              //_pq(1);
              //echo '<br><br>'.$this->db->last_query();
              
              if(count($get_admitcard_data) == 0)
              {
                $add_data['seat_identification'] = str_pad(1, 3, '0', STR_PAD_LEFT);
              }
              else
              {
                $add_data['seat_identification'] = str_pad(($get_admitcard_data[0]['seat_identification']+1), 3, '0', STR_PAD_LEFT);
              }
              //_pa($add_data);
            }
            else if(in_array($payment_data[0]['exam_code'],$csc_venue_master_eligible_exam_codes_arr))
            {
              $this->db->group_by('venue_code');
              $this->db->where(" FIND_IN_SET('".$payment_data[0]['exam_code']."',exam_codes) > 0 ");              
              $venue_data = $this->master_model->getRecords('iibfbcbf_exam_venue_master', array('centre_code' => $res['exam_centre_code'], 'venue_code' => $res['exam_venue_code'], 'exam_period' => $payment_data[0]['exam_period'], 'exam_date'=>'0000-00-00'), 'venue_master_id, venue_code, venue_name, venue_addr1, venue_addr2, venue_addr3, venue_addr4, venue_addr5, venue_pincode', array('venue_addr1'=>'ASC'));              

              if(count($venue_data) > 0)
              {
                $venueid = $venue_data[0]['venue_code'];
                $venue_name = $venue_data[0]['venue_name'];
                $venueadd1 = $venue_data[0]['venue_addr1'];
                $venueadd2 = $venue_data[0]['venue_addr2'];
                $venueadd3 = $venue_data[0]['venue_addr3'];
                $venueadd4 = $venue_data[0]['venue_addr4'];
                $venueadd5 = $venue_data[0]['venue_addr5'];
                $venpin = $venue_data[0]['venue_pincode'];
              }
              //_pa($res);

              if(count($chk_admitcard_exist) > 0)
              {
                $this->db->where('admitcard_id <',$chk_admitcard_exist[0]['admitcard_id']);
              }
              
              $get_admitcard_data = $this->master_model->getRecords('iibfbcbf_admit_card_details', array(
                'seat_identification !='=>'',
                'exm_cd' => $payment_data[0]['exam_code'], 
                'exm_prd' => $payment_data[0]['exam_period'], 
                'exam_date' => $res['exam_date'], 
                //'time' => $res['exam_time'],
                'exam_centre_code' => $res['exam_centre_code'],  
                'venueid' => $res['exam_venue_code']
              ), 'seat_identification', array('admitcard_id'=>'DESC'),'',1);
              //_pq(1);
              //echo '<br><br>'.$this->db->last_query();
              
              if(count($get_admitcard_data) == 0)
              {
                $add_data['seat_identification'] = str_pad(1, 3, '0', STR_PAD_LEFT);
              }
              else
              {
                $add_data['seat_identification'] = str_pad(($get_admitcard_data[0]['seat_identification']+1), 3, '0', STR_PAD_LEFT);
              }
              //_pa($add_data);
            }

            $add_data['venueid'] = $venueid;
            $add_data['venue_name'] = $venue_name;
            $add_data['venueadd1'] = $venueadd1;
            $add_data['venueadd2'] = $venueadd2;
            $add_data['venueadd3'] = $venueadd3;
            $add_data['venueadd4'] = $venueadd4;
            $add_data['venueadd5'] = $venueadd5;
            $add_data['venpin'] = $venpin;
            
            $add_data['time'] = $res['exam_time'];
            $add_data['stat'] = '';
            $add_data['pwd'] = random_password();
            $add_data['exam_date'] = $res['exam_date'];
            $add_data['mode'] = $exam_mode;
            $add_data['remark'] = '1';
            $add_data['created_on'] = date("Y-m-d H:i:s");
            $add_data['record_source'] = $res['payment_mode'];

            if(count($chk_admitcard_exist) == 0)
            {
              $admitcard_id = $this->master_model->insertRecord('iibfbcbf_admit_card_details',$add_data,true);
              //echo '<br><br>'.$this->db->last_query();
            }
            else
            {
              $this->master_model->updateRecord('iibfbcbf_admit_card_details', $add_data, array('admitcard_id'=>$chk_admitcard_exist[0]['admitcard_id']));

              //echo '<br><br>'.$this->db->last_query();
            }
            //_pq(1);
            //_pa($add_data);
          }
        }
      }
      //exit;
    }

    function get_capacity_bulk($exam_code = '', $exam_period = '',$exam_centre_code = '', $exam_venue_code = '', $exam_date = '', $exam_time = '', $member_exam_id=0)
    {
      $valid_exam_code_arr = array(1041,1042);
      $seat_capacity='0';
      
      if($exam_code != '' && $exam_period != '' && $exam_centre_code != '' && $exam_venue_code != '' && $exam_date != '' && $exam_time != '' && in_array($exam_code, $valid_exam_code_arr))
      {
        $this->db->where(" FIND_IN_SET('".$exam_code."',exam_codes) > 0 ");
        $seat_count = $this->master_model->getRecords('iibfbcbf_exam_venue_master', array('exam_period'=>$exam_period,'centre_code'=>$exam_centre_code, 'venue_code'=>$exam_venue_code, 'exam_date'=>$exam_date, 'session_time'=>$exam_time),'session_capacity');//session_capacity
        if(count($seat_count) > 0)
        {
          $pay_status_arr = array(1, 3);//2, 
          $this->db->where_in('me.pay_status',$pay_status_arr);
          $member_exam_Count = $this->master_model->getRecords('iibfbcbf_member_exam me',array('me.is_deleted'=>'0',  'exam_centre_code'=>$exam_centre_code, 'exam_venue_code'=>$exam_venue_code, 'exam_date'=>$exam_date, 'exam_time'=>$exam_time)); //'exam_code'=>$exam_code, 'exam_period'=>$exam_period, , 'me.member_exam_id !='=>$member_exam_id
          
          $total_count = intval(count($member_exam_Count)); // + intval($regular_admit_card_Count))
          $seat_capacity = $seat_count[0]['session_capacity'] - $total_count;

          if($seat_capacity < 0) { $seat_capacity = 0; }
        }       
      }
      return $seat_capacity;      
    }

    function get_capacity_csc($exam_code = '', $exam_period = '',$exam_centre_code = '', $exam_venue_code = '', $exam_date = '', $exam_time = '', $member_exam_id=0)
    {
      $valid_exam_code_arr = array(1039,1040);
      $seat_capacity='0';
      
      if($exam_code != '' && $exam_period != '' && $exam_centre_code != '' && $exam_venue_code != '' && $exam_date != '' && $exam_time != '' && in_array($exam_code, $valid_exam_code_arr))
      {
        $this->db->where(" FIND_IN_SET('".$exam_code."',exam_codes) > 0 ");
        $seat_count = $this->master_model->getRecords('iibfbcbf_exam_venue_master', array('exam_period'=>$exam_period,'centre_code'=>$exam_centre_code, 'venue_code'=>$exam_venue_code, 'exam_date'=>'0000-00-00'),'session_capacity');//session_capacity
        
        if(count($seat_count) > 0)
        {
          $pay_status_arr = array(1, 2, 3);
          $this->db->where_in('me.pay_status',$pay_status_arr);
          $member_exam_Count = $this->master_model->getRecords('iibfbcbf_member_exam me',array('me.is_deleted'=>'0',  'exam_centre_code'=>$exam_centre_code, 'exam_venue_code'=>$exam_venue_code, 'exam_date'=>$exam_date, 'exam_time'=>$exam_time, 'me.member_exam_id !='=>$member_exam_id)); //'exam_code'=>$exam_code, 'exam_period'=>$exam_period,
          
          $total_count = intval(count($member_exam_Count)); // + intval($regular_admit_card_Count))
          $seat_capacity = $seat_count[0]['session_capacity'] - $total_count;

          if($seat_capacity < 0) { $seat_capacity = 0; }
        }       
      }
      return $seat_capacity;      
    }

    function change_candidate_hold_release_status_common($posted_arr=array(), $action_by='')
    {
      $cand_id = htmlspecialchars_decode($posted_arr['cand_id']);
      $status = htmlspecialchars_decode($posted_arr['status']);

      $cand_data = $this->master_model->getRecords('iibfbcbf_batch_candidates', array('candidate_id' => $cand_id), 'candidate_id, hold_release_status');
      
      if(count($cand_data) == 0) // check whether record exist or not
      {
        return "error"; 
      }
      else
      {
        $new_hold_release_status = '';
        $new_hold_release_text = '';
        if($status == "true")
        {
          $new_hold_release_status = 3;
          $new_hold_release_text = 'release';
        }
        else if($status == "false")
        {
          $new_hold_release_status = 2;
          $new_hold_release_text = 'manual hold';
        }

        if($new_hold_release_status == $cand_data[0]['hold_release_status']) { return "error";  }
        else
        {			
          $posted_arr = json_encode($posted_arr);
          $dispName = $this->getLoggedInUserDetails($this->session->userdata('IIBF_BCBF_LOGIN_ID'), $action_by);

          $up_data['hold_release_status'] = $new_hold_release_status;
          $up_data['updated_on'] = date("Y-m-d H:i:s");
          $up_data['updated_by'] = $this->session->userdata('IIBF_BCBF_LOGIN_ID');            
          $this->master_model->updateRecord('iibfbcbf_batch_candidates', $up_data, array('candidate_id'=>$cand_id));
                        
          $this->insert_common_log($action_by.' : Candidate status Updated', 'iibfbcbf_batch_candidates', $this->db->last_query(), $cand_id,'candidate_action','The candidate has successfully mark as '.$new_hold_release_text.' by the '.$action_by.' '.$dispName['disp_name'], $posted_arr);
                          
          return "success";
        }
      }
    }

    //START : SEND BATCH GO AHEAD/APPROVED, REJECTED, CANCELLED EMAIL
    function send_batch_action_email_sms($batch_id='', $action='', $action_performed_by_type='Admin')
    {
      $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = btch.agency_id', 'INNER');
      $this->db->join('iibfbcbf_centre_master cm', 'cm.centre_id = btch.centre_id', 'INNER');
      $batch_data = $this->master_model->getRecords('iibfbcbf_agency_centre_batch btch', array('btch.batch_id'=>$batch_id), 'btch.batch_code, btch.batch_type, btch.batch_start_date, btch.batch_end_date, btch.batch_status, am.agency_name, am.agency_code, am.allow_exam_types, cm.centre_name, cm.centre_username, cm.centre_contact_person_email');
      
      $emailer_data = $this->master_model->getRecords('emailer', array('emailer_name'=>'IIBFBCBF_BATCH_APPROVE_REJECT'), 'emailer_name, emailer_text, sms_text, sms_template_id, sms_sender, subject');

      if(count($batch_data) > 0 && count($emailer_data) > 0 && in_array($action, array(3,5,7))) // 3=>GO AHEAD/APPROVED, 5=> REJECTED, 7=> CANCELLED
      {
        $batch_type = 'Basic Batch'; if($batch_data[0]['batch_type'] == '2') { $batch_type = 'Advanced Batch'; }

        $batch_action = 'Approved';
        if($action == '5') { $batch_action = 'Rejected'; } else if($action == '7') { $batch_action = 'Cancelled'; }

        $mail_arg = array();
        $mail_arg['subject'] = str_replace("#BATCH_ACTION#", $batch_action, $emailer_data[0]['subject']);
        $mail_arg['to_email'] = $batch_data[0]['centre_contact_person_email']; //'sagar.matale@esds.co.in'; 
        $mail_arg['to_name'] = $batch_data[0]['centre_name']; //'sagar'; //
        $mail_arg['cc_email'] = '';//sagar.matale@esds.co.in,anil.s@esds.co.in
        $mail_arg['bcc_email'] = '';//sagar.matale@esds.co.in,anil.s@esds.co.in
        $mail_arg['is_header_footer_required'] = '0';
        $mail_arg['view_flag'] = '0';
        
        $mail_content = '
          <style type="text/css">            
            p { padding: 0; margin: 0; font-weight: bold; }
            p.footer_regards { line-height: 20px; }
            table.inner_tbl { font-size: 14px; border-collapse: collapse; width: 100%; color:#000; }
            table.inner_tbl tbody tr td { padding: 5px 10px; border-collapse: collapse; border: 1px solid #776f6f; line-height:20px; vertical-align:top; min-width:200px; }                          
          </style>'.$emailer_data[0]['emailer_text'];        

        $mail_content = str_replace("#CENTRE_NAME#", $batch_data[0]['centre_name'], $mail_content);
        $mail_content = str_replace("#AGENCY_NAME#", $batch_data[0]['agency_name'], $mail_content);
        $mail_content = str_replace("#AGENCY_CODE#", $batch_data[0]['agency_code'], $mail_content);
        $mail_content = str_replace("#BATCH_ACTION#", $batch_action, $mail_content);
        $mail_content = str_replace("#ACTION_PERFORMED_BY_TYPE#", $action_performed_by_type, $mail_content);
        $mail_content = str_replace("#CENTRE_USERNAME#", $batch_data[0]['centre_username'], $mail_content);
        $mail_content = str_replace("#BATCH_CODE#", $batch_data[0]['batch_code'], $mail_content);
        $mail_content = str_replace("#BATCH_TYPE#", $batch_type, $mail_content);
        $mail_content = str_replace("#BATCH_START_DATE#", date("d-M-Y", strtotime($batch_data[0]['batch_start_date'])), $mail_content);
        $mail_content = str_replace("#BATCH_END_DATE#", date("d-M-Y", strtotime($batch_data[0]['batch_end_date'])), $mail_content);

        $mail_arg['mail_content'] = $mail_content;
        $this->iibfbcbf_send_mail_common($mail_arg);
      }
    }//END : SEND BATCH GO AHEAD/APPROVED, REJECTED, CANCELLED EMAIL    

    //START : SEND TRANSACTION DETAILS EMAIL & SMS FOR INDIVIDUAL & CSC TRANSACTIONS
    function send_transaction_details_email_sms($pt_id='')
    {
      $this->db->join('iibfbcbf_member_exam me', 'FIND_IN_SET(me.member_exam_id, pt.exam_ids)', 'INNER');
      $this->db->join('iibfbcbf_batch_candidates cand', 'cand.candidate_id = me.candidate_id', 'INNER');
      $this->db->join('state_master state', 'state.state_code = cand.state', 'LEFT');
      $this->db->join('city_master city', 'city.id = cand.city', 'LEFT');
      
      $this->db->where_in('pt.status', array(0,1));
      $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction pt', array('pt.id'=>$pt_id, 'pt.payment_mode !='=>'Bulk'), 'pt.id, pt.agency_id, pt.centre_id, pt.exam_ids, pt.exam_code, pt.exam_period, pt.gateway, pt.amount, pt.date, pt.approve_reject_date, pt.transaction_no, pt.UTR_no, pt.pay_count, pt.bankcode, pt.paymode, pt.receipt_no, pt.description, pt.transaction_details, pt.payment_mode, pt.status, cand.salutation, cand.first_name, cand.middle_name, cand.last_name, cand.mobile_no, cand.email_id, cand.regnumber, cand.address1, cand.address2, cand.address3, cand.address4, (SELECT CONCAT(description, " (", exam_code, ")") FROM iibfbcbf_exam_master WHERE exam_delete = "0" AND exam_code = pt.exam_code LIMIT 1 ORDER BY id DESC) AS ExamName, (SELECT medium_description FROM iibfbcbf_exam_medium_master WHERE medium_delete = "0" AND exam_code = pt.exam_code AND exam_period = pt.exam_period AND medium_code = me.exam_medium LIMIT 1 ORDER BY id DESC) AS MediumName, me.member_exam_id, me.exam_centre_code, IF(me.exam_mode = "ON", "Online", "Offline") AS exam_mode, me.exam_date, (SELECT centre_name FROM iibfbcbf_exam_centre_master WHERE centre_delete = "0" AND exam_name = pt.exam_code AND exam_period = pt.exam_period AND centre_code = me.exam_centre_code LIMIT 1 ORDER BY id DESC) AS CentreName, state.state_name, city.city_name');
      
      if(count($payment_data) > 0)
      {
        $emailer_name = $transaction_status_msg = '';
        if($payment_data[0]['status'] == '1') { $emailer_name = 'IIBFBCBF_TRANSACTION_SUCCESS'; $transaction_status_msg = 'Transaction Successful'; }
        else if($payment_data[0]['status'] == '0') { $emailer_name = 'IIBFBCBF_TRANSACTION_FAIL'; $transaction_status_msg = 'Transaction Fail'; }
        
        $emailer_data = $this->master_model->getRecords('emailer', array('emailer_name'=>$emailer_name), 'emailer_name, emailer_text, sms_text, sms_template_id, sms_sender, subject');

        if(count($emailer_data) > 0)
        {
          foreach($payment_data as $payment_res)
          {
            $CandidateName = '';								
            if($payment_res['salutation'] != '') { $CandidateName .= $payment_res['salutation']; }
            if($payment_res['first_name'] != '') { $CandidateName .= ' '.$payment_res['first_name']; }
            if($payment_res['middle_name'] != '') { $CandidateName .= ' '.$payment_res['middle_name']; }
            if($payment_res['last_name'] != '') { $CandidateName .= ' '.$payment_res['last_name']; }

            $candAddress = '';
            if($payment_res['address1'] != '') { $candAddress .= rtrim(trim($payment_res['address1']),","); }
            if($payment_res['address2'] != '') { $candAddress .= ', '.rtrim(trim($payment_res['address2']),","); }
            if($payment_res['address3'] != '') { $candAddress .= ', '.rtrim(trim($payment_res['address3']),","); }
            if($payment_res['address4'] != '') { $candAddress .= ', '.rtrim(trim($payment_res['address4']),","); }
            if($payment_res['city_name'] != '') { $candAddress .= ', '.rtrim(trim($payment_res['city_name']),","); }
            if($payment_res['state_name'] != '') { $candAddress .= ', '.rtrim(trim($payment_res['state_name']),","); }

            $mail_arg = array();
            $mail_arg['subject'] = 'Exam Enrollment Acknowledgement';
            $mail_arg['to_email'] = $payment_res['email_id']; //'sagar.matale@esds.co.in'; 
            $mail_arg['to_name'] = $CandidateName; //'sagar'; 
            $mail_arg['cc_email'] = '';//sagar.matale@esds.co.in,anil.s@esds.co.in
            $mail_arg['bcc_email'] = '';//sagar.matale@esds.co.in,anil.s@esds.co.in
            $mail_arg['is_header_footer_required'] = '0';
            $mail_arg['view_flag'] = '0';

            $attachment_arr = array();
            if($payment_data[0]['status'] == '1')
            {
              $exam_invoice_data = $this->master_model->getRecords('exam_invoice ei', array('ei.exam_code'=>$payment_res['exam_code'], 'ei.exam_period'=>$payment_res['exam_period'], 'ei.pay_txn_id'=>$pt_id, 'ei.receipt_no'=>$payment_res['receipt_no'], 'ei.transaction_no'=>$payment_res['transaction_no'], 'ei.invoice_no !='=>'', 'ei.invoice_image !='=>'', 'ei.app_type'=>'BC'), 'ei.invoice_id, ei.invoice_no, ei.invoice_image', array('ei.invoice_id'=>'DESC'));
              if(count($exam_invoice_data) > 0)
              {
                $attachment_arr[] = './uploads/iibfbcbf/iibf_bcbf_examinvoice/user/'.$exam_invoice_data[0]['invoice_image'];
              }

              if(in_array($payment_res['exam_code'], array(1039,1040,1041,1042)))
              {
                $admit_card_data = $this->master_model->getRecords('iibfbcbf_admit_card_details ac', array('ac.exm_cd'=>$payment_res['exam_code'], 'ac.exm_prd'=>$payment_res['exam_period'], 'ac.pt_id'=>$pt_id, 'ac.remark'=>'1', 'ac.mem_exam_id'=>$payment_res['member_exam_id']), 'ac.admitcard_id', array('ac.admitcard_id'=>'DESC'));
                if(count($admit_card_data) > 0)
                {
                  $attachment_arr[] = $this->Iibf_bcbf_model->download_admit_card_pdf_single(url_encode($admit_card_data[0]['admitcard_id']), 'save');              
                }
              }
            }            
            $mail_arg['attachment'] = $attachment_arr;

            $mail_content = '
              <style type="text/css">            
                p { padding: 0; margin: 0; font-weight: bold; }
                p.footer_regards { line-height: 20px; }
                table.inner_tbl { font-size: 14px; border-collapse: collapse; width: 100%; color:#000; }
                table.inner_tbl tbody tr td { padding: 5px 10px; border-collapse: collapse; border: 1px solid #776f6f; line-height:20px; vertical-align:top; min-width:200px; }                          
              </style>'.$emailer_data[0]['emailer_text'];

            $mail_content = str_replace("#CANDIDATENAME#", $CandidateName, $mail_content);
            $mail_content = str_replace("#REGNUMBER#", ($payment_res['regnumber']==""?"-":$payment_res['regnumber']), $mail_content);
            $mail_content = str_replace("#EXAMNAME#", $payment_res['ExamName'], $mail_content);
            $mail_content = str_replace("#EXAM_DATE#", date("m/Y", strtotime($payment_res['exam_date'])), $mail_content);
            $mail_content = str_replace("#AMOUNT#", $payment_res['amount'], $mail_content);
            $mail_content = str_replace("#CANDADDRESS#", $candAddress, $mail_content);
            $mail_content = str_replace("#EMAIL_ID#", $payment_res['email_id'], $mail_content);
            $mail_content = str_replace("#MEDIUMNAME#", $payment_res['MediumName'], $mail_content);
            $mail_content = str_replace("#CENTRENAME#", $payment_res['CentreName'], $mail_content);
            $mail_content = str_replace("#EXAM_CENTRE_CODE#", $payment_res['exam_centre_code'], $mail_content);
            $mail_content = str_replace("#EXAM_MODE#", $payment_res['exam_mode'], $mail_content);
            $mail_content = str_replace("#TRANSACTION_NO#", ($payment_res['transaction_no']==""?"-":$payment_res['transaction_no']), $mail_content);
            $mail_content = str_replace("#PAYMENT_MODE#", $payment_res['payment_mode'], $mail_content);
            $mail_content = str_replace("#TRANSACTION_STATUS#", $transaction_status_msg, $mail_content);
            $mail_content = str_replace("#DATE#", $payment_res['date'], $mail_content);       

            $mail_arg['mail_content'] = $mail_content;
            $this->iibfbcbf_send_mail_common($mail_arg);
          } 
        } 
      }
    }//END : SEND TRANSACTION DETAILS EMAIL & SMS FOR INDIVIDUAL & CSC TRANSACTIONS

    function download_admit_card_pdf_single($enc_admitcard_id= '0', $type='') //$type = 'download' / 'save'
    {
      $admitcard_id = 0;
      if($enc_admitcard_id != '0') { $admitcard_id = url_decode($enc_admitcard_id); }
      
      $data = array();  

      $this->db->join('iibfbcbf_payment_transaction pt', 'pt.id = ac.pt_id', 'INNER');
      $this->db->join('iibfbcbf_batch_candidates bc', 'bc.regnumber = ac.mem_mem_no', 'INNER');
      $data['admit_card_data'] = $admit_card_data = $this->master_model->getRecords('iibfbcbf_admit_card_details ac', array('ac.admitcard_id' => $admitcard_id), '
      admitcard_id, ac.pt_id, ac.mem_exam_id, ac.batchId, ac.institute_code, ac.centre_code, ac.mem_mem_no, ac.mam_nam_1, ac.mem_adr_1, ac.mem_adr_2, ac.mem_adr_3, ac.mem_adr_4, ac.mem_adr_5, ac.mem_adr_6, ac.mem_pin_cd, ac.exm_cd, ac.exm_prd, ac.mode, ac.pwd, ac.m_1, ac.vendor_code, ac.created_on, ac.venueid, ac.venueadd1, ac.venueadd2, ac.venueadd3, ac.venueadd4, ac.venueadd5, ac.venpin, ac.insname, ac.venue_name, ac.seat_identification, ac.sub_dsc, ac.exam_date, ac.time, 
      pt.transaction_no, pt.UTR_no, pt.payment_mode, bc.dob', array("admitcard_id"=>"DESC")); 
      
      if(count($admit_card_data) > 0)
      {        
        $data['exam_result'] = $this->master_model->getRecords('iibfbcbf_exam_master', array('exam_code'=>$admit_card_data[0]['exm_cd'], 'exam_delete'=>'0'), 'description, exam_type, exam_code','','0','1');
        
        //$this->Iibf_bcbf_model->insert_common_log('IIBF BCBF : Agency Generate admit card in 2', 'iibfbcbf_admit_card_details', $this->db->last_query(), $this->login_agency_or_centre_id,'agency_action','The agency Generate admit card in 2 ', json_encode($subject_result));
        
        $directory_name = "./uploads/iibfbcbf/iibf_bcbf_admitcard/".date('Ymd');
        create_directories($directory_name);

        $attchpath_admitcard = $this->load->view('iibfbcbf/agency/admitcardpdf_attach', $data, true);
        //echo $attchpath_admitcard;die;
        $this->load->library('m_pdf');
        $pdf = $this->m_pdf->load();
        $pdfFilePath = $admit_card_data[0]['exm_cd']."_".$admit_card_data[0]['exm_prd']."_".$admit_card_data[0]['mem_mem_no'].".pdf";
        $pdf->WriteHTML($attchpath_admitcard);

        if($type == 'download') { $pdf->Output($pdfFilePath, "D"); }
        else if($type == 'save') 
        { 
          $pdf->Output($directory_name.'/'.$pdfFilePath, "F");
          return $directory_name.'/'.$pdfFilePath;
        }
        die;
      } 
    }   

    //START : THIS FUNCTION IS USED TO GET THE TOTAL INSPECTION DATA AS PER BATCH ID & INSPECTOR ID
    function get_inspection_data($batch_id=0,$logged_in_inspector_id='')
    {
      $this->db->where_in('acb.batch_status', array(3,4));
      $this->db->join('iibfbcbf_faculty_master fm1', 'fm1.faculty_id = acb.first_faculty', 'LEFT');
      $this->db->join('iibfbcbf_faculty_master fm2', 'fm2.faculty_id = acb.second_faculty', 'LEFT');
      $this->db->join('iibfbcbf_faculty_master fm3', 'fm3.faculty_id = acb.third_faculty', 'LEFT');
      $this->db->join('iibfbcbf_faculty_master fm4', 'fm4.faculty_id = acb.fourth_faculty', 'LEFT');
      $this->db->join('iibfbcbf_agency_master am1', 'am1.agency_id = acb.agency_id', 'LEFT'); 
      $this->db->join('iibfbcbf_centre_master cm', 'cm.centre_id = acb.centre_id', 'INNER');
      $this->db->join('city_master cm2', 'cm2.id = cm.centre_city', 'LEFT');
      $this->db->join('iibfbcbf_inspector_master im', 'im.inspector_id = acb.inspector_id', 'LEFT');
      $data['batch_data'] = $batch_data = $this->master_model->getRecords('iibfbcbf_agency_centre_batch acb', array('acb.batch_id' => $batch_id, 'acb.is_deleted' => '0'), "acb.*, IF(acb.batch_type=1, 'Basic', 'Advanced') AS DispBatchType, CONCAT(fm1.salutation, ' ', fm1.faculty_name, ' (', fm1.faculty_number,')') AS FirstFaculty, CONCAT(fm2.salutation, ' ', fm2.faculty_name, ' (', fm2.faculty_number,')') AS SecondFaculty, CONCAT(fm3.salutation, ' ', fm3.faculty_name, ' (', fm3.faculty_number,')') AS ThirdFaculty, CONCAT(fm4.salutation, ' ', fm4.faculty_name, ' (', fm4.faculty_number,')') AS FourthFaculty, IF(acb.batch_online_offline_flag=1, 'Offline', 'Online') AS DispBatchInfrastructure, IF(acb.batch_status = 0, 'In Review', IF(acb.batch_status = 1, 'Final Review', IF(acb.batch_status = 2, 'Batch Error', IF(acb.batch_status = 3, 'Go Ahead', IF(acb.batch_status = 4, 'Hold', IF(acb.batch_status = 5, 'Rejected', IF(acb.batch_status = 6, 'Re-Submitted', 'Cancelled'))))))) AS DispBatchStatus,am1.agency_name,am1.agency_code, am1.allow_exam_types, cm.centre_name, cm.centre_city, cm.centre_username, cm2.city_name, im.inspector_name");
              
      $data['user_data'] = $this->master_model->getRecords('iibfbcbf_online_batch_user_details', array('batch_id' => $batch_id, 'is_active' => '1', 'is_deleted'=>'0'), 'login_id, password', array('created_on'=>'ASC'));  

      $data['bank_cand_data'] = $this->master_model->getRecords('iibfbcbf_batch_bak_name_cand_src_details', array('batch_id' => $batch_id, 'is_active' => '1', 'is_deleted'=>'0'), 'bank_id, bank_name, cand_src', array('created_on'=>'ASC')); 

      $this->db->join('state_master sm', 'sm.state_code = cm.centre_state', 'LEFT');
      $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT'); 
      $data['centre_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.centre_id'=>$batch_data[0]['centre_id']), 'cm.centre_id, cm.agency_id, cm.centre_address1, cm.centre_state, cm.centre_city, cm.centre_district, cm.centre_pincode, sm.state_name, cm1.city_name');

      $inspector_id = $batch_data[0]['inspector_id'];
      if($logged_in_inspector_id != "" && $logged_in_inspector_id > 0) { $inspector_id = $logged_in_inspector_id; }
      $this->db->join('iibfbcbf_inspector_master im', 'im.inspector_id = bi.inspector_id', 'INNER');
      $data['inspection_data'] = $this->master_model->getRecords('iibfbcbf_batch_inspection bi', array('bi.inspector_id'=>$inspector_id, 'bi.batch_id'=>$batch_id, 'bi.agency_id'=>$batch_data[0]['agency_id'], 'bi.centre_id'=>$batch_data[0]['centre_id']), 'bi.*, im.inspector_name', array('bi.inspection_no'=>'ASC'));  
            
      $data['batch_candidate_data'] = $this->master_model->getRecords('iibfbcbf_batch_candidates bc', array('bc.agency_id'=>$batch_data[0]['agency_id'], 'bc.centre_id'=>$batch_data[0]['centre_id'], 'bc.batch_id'=>$batch_data[0]['batch_id'], 'bc.is_deleted'=>'0'), 'bc.candidate_id, bc.training_id, bc.regnumber, bc.salutation, bc.first_name, bc.middle_name, bc.last_name, bc.dob, bc.mobile_no, bc.email_id, bc.candidate_photo, bc.hold_release_status', array('bc.candidate_id'=>'ASC'));

      $candidate_inspection_data = $this->master_model->getRecords('iibfbcbf_candidate_inspection ci', array('ci.batch_id'=>$batch_data[0]['batch_id']), 'ci.batch_id, ci.batch_inspection_id, ci.candidate_id, ci.inspector_id, ci.attendance, ci.remark', array('id'=>'ASC'));

      $candidate_inspection_data_arr = array();
      foreach($candidate_inspection_data as $res)
      {
        $present_cnt = $absent_cnt = 0;
        $remark = '';
        $remark_cnt = 1;
        if(array_key_exists($res['candidate_id'], $candidate_inspection_data_arr))
        {
          $present_cnt = $candidate_inspection_data_arr[$res['candidate_id']]['present_cnt'];
          $absent_cnt = $candidate_inspection_data_arr[$res['candidate_id']]['absent_cnt'];
          $remark_cnt = $candidate_inspection_data_arr[$res['candidate_id']]['remark_cnt'];
          $remark = $candidate_inspection_data_arr[$res['candidate_id']]['remark'];
        }

        if($res['attendance'] == 'Present') { $present_cnt = $present_cnt + 1; }
        else if($res['attendance'] == 'Absent') { $absent_cnt = $absent_cnt + 1; }

        if($res['remark'] != "") { $remark .= $remark_cnt.') '.$res['remark'].'<br>'; $remark_cnt++; }

        $candidate_inspection_data_arr[$res['candidate_id']]['present_cnt'] = $present_cnt;
        $candidate_inspection_data_arr[$res['candidate_id']]['absent_cnt'] = $absent_cnt;
        $candidate_inspection_data_arr[$res['candidate_id']]['remark_cnt'] = $remark_cnt;
        $candidate_inspection_data_arr[$res['candidate_id']]['remark'] = $remark;
      }
      $data['candidate_inspection_data_arr'] = $candidate_inspection_data_arr;

      return $data;
    }

    /************************ START : DASHBOARD COUNT QUERIES *******************************/
    //START : THIS FUNCTION IS USED TO GET THE ALL INSPECTION COUNT AS PER INSPECTOR ID, BATCH ID
    function get_inspection_count_data($inspector_id='', $batch_id='')
    {
      $total_inspection_cnt_arr = array();
      $total_upcoming_inspection_cnt = $total_ongoing_inspection_cnt = $total_completed_inspection_cnt = $total_re_inspection_cnt = $total_missed_inspection_cnt = 0;

      //START : TOTAL INSPECTION, UPCOMING INSPECTION & ONGOING INSPECTION COUNT
      //TOTAL INSPECTION : CURRENTLY INSPECTOR ASSIGNED TO BATCH OR PREVIOUSLY ASSIGNED TO BATCH & SUBMITTED THE INSPECTION AT LEAST ONCE
      //UPCOMING INSPECTION : CURRENTLY INSPECTOR ASSIGNED TO BATCH AND BATCH NOT YET STARTED
      //ONGOING INSPECTION : CURRENTLY INSPECTOR ASSIGNED TO BATCH AND TODAYS DATE IS BETWEEN BATCH START & END DATE
      if($inspector_id > 0) { $this->db->where('inspector_id',$inspector_id); }      
      if($batch_id > 0) { $this->db->where('batch_id',$batch_id); }
      $inspection_alloted_batches = $this->master_model->getRecords('iibfbcbf_agency_centre_batch', array('is_deleted'=>'0'), 'batch_id, batch_start_date, batch_end_date');
      
      if(count($inspection_alloted_batches) > 0) 
      { 
        foreach($inspection_alloted_batches as $res)
        {
          $total_inspection_cnt_arr[$res['batch_id']] = $res['batch_id'];

          if($res['batch_start_date'] > date('Y-m-d'))
          {
            $total_upcoming_inspection_cnt++;
          }
          else if($res['batch_start_date'] <= date('Y-m-d') && date('Y-m-d') <= $res['batch_end_date'])
          {
            $total_ongoing_inspection_cnt++;
          }
        }
      }

      
      if($inspector_id > 0) { $this->db->where('inspector_id',$inspector_id); }      
      if($batch_id > 0) { $this->db->where('batch_id',$batch_id); }
      $inspection_submitted_batches = $this->master_model->getRecords('iibfbcbf_batch_inspection', array('is_deleted'=>'0'), 'DISTINCT(batch_id)');
      if(count($inspection_submitted_batches) > 0) 
      { 
        foreach($inspection_submitted_batches as $res)
        {
          $total_inspection_cnt_arr[$res['batch_id']] = $res['batch_id'];
        }
      }//END : TOTAL INSPECTION, UPCOMING INSPECTION & ONGOING INSPECTION COUNT

      //START : COMPLETED & RE-INSPECTION COUNT
      //COMPLETED COUNT : BATCH END DATE IS OVER AND INSPECTOR SUBMITTED ONLY ONE INSPECTION AGAINST THE BATCH
      //RE-INSPECTION COUNT : BATCH END DATE IS OVER AND INSPECTOR SUBMITTED MORE THAN ONE INSPECTION AGAINST THE BATCH
      if($inspector_id > 0) { $this->db->where('bi.inspector_id',$inspector_id); }      
      if($batch_id > 0) { $this->db->where('bi.batch_id',$inspector_id); }  
      $this->db->group_by('bi.batch_id');    
      $this->db->join('iibfbcbf_agency_centre_batch btch', 'btch.batch_id = bi.batch_id', 'INNER');
      $inspection_submitted_data = $this->master_model->getRecords('iibfbcbf_batch_inspection bi', array('btch.is_deleted'=>'0'), 'bi.inspection_id, bi.agency_id, bi.centre_id, bi.batch_id, bi.inspector_id, bi.inspection_no, bi.inspection_start_time, btch.batch_start_date, btch.batch_end_date, (SELECT count(inspection_no) FROM iibfbcbf_batch_inspection WHERE batch_id = bi.batch_id AND inspector_id = bi.inspector_id) AS Inspection_count');
      
      if(count($inspection_submitted_data) > 0)
      {
        foreach($inspection_submitted_data as $res)
        {
          if($res['batch_end_date'] < date('Y-m-d'))
          {
            if($res['Inspection_count'] == 1) { $total_completed_inspection_cnt++; }
            else if($res['Inspection_count'] > 1) { $total_re_inspection_cnt++; }
          }
        }
      }//END : COMPLETED + RE-INSPECTION COUNT  
      
      //START : MISSED INSPECTION COUNT
      //MISSED COUNT : BATCH END DATE IS OVER, INSPECTOR ASSIGNED TO BATCH BUT INSPECTOR DID NOT SUBMITTED ANY INSPECTION AGAINST THE BATCH
      if($inspector_id > 0) { $this->db->where('btch.inspector_id',$inspector_id); }      
      if($batch_id > 0) { $this->db->where('btch.batch_id',$inspector_id); }  
      $inspection_submitted_data = $this->master_model->getRecords('iibfbcbf_agency_centre_batch btch', array('btch.is_deleted'=>'0', 'btch.batch_end_date <'=> date("Y-m-d")), 'btch.batch_id, (SELECT count(inspection_no) FROM iibfbcbf_batch_inspection WHERE batch_id = btch.batch_id AND inspector_id = btch.inspector_id) AS Inspection_count');
      
      if(count($inspection_submitted_data) > 0)
      {
        foreach($inspection_submitted_data as $res)
        {
          if($res['Inspection_count'] == 0) { $total_missed_inspection_cnt++; }
        }
      }//END : MISSED INSPECTION COUNT

      $result['total_inspection_cnt'] = count($total_inspection_cnt_arr);
      $result['total_upcoming_inspection_cnt'] = $total_upcoming_inspection_cnt;
      $result['total_ongoing_inspection_cnt'] = $total_ongoing_inspection_cnt;
      $result['total_completed_inspection_cnt'] = $total_completed_inspection_cnt;
      $result['total_re_inspection_cnt'] = $total_re_inspection_cnt;
      $result['total_missed_inspection_cnt'] = $total_missed_inspection_cnt;

      return $result;
      
    }//END : THIS FUNCTION IS USED TO GET THE ALL INSPECTION COUNT AS PER INSPECTOR ID, BATCH ID
        
    //START : THIS FUNCTION IS USED TO GET THE TOTAL CENTRE DATA AS PER AGENCY ID
    function get_total_centre_data($agency_id='')
    {
      $total_centre_cnt = $total_active_centre_cnt = $total_in_active_centre_cnt = $total_in_review_centre_cnt = $total_re_submitted_centre_cnt = 0;

      if($agency_id > 0) { $this->db->where('cm.agency_id',$agency_id); }           
      $centre_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.is_deleted'=>'0'), 'cm.centre_id, cm.agency_id, cm.centre_name, cm.status');

      if(count($centre_data) > 0)
      {
        $total_centre_cnt = count($centre_data);
        foreach($centre_data as $res)
        {
          if($res['status'] == '0') { $total_in_active_centre_cnt++; }
          else if($res['status'] == '1') { $total_active_centre_cnt++; }
          else if($res['status'] == '2') { $total_in_review_centre_cnt++; }
          else if($res['status'] == '3') { $total_re_submitted_centre_cnt++; }
        }
      }

      $result['total_centre_cnt'] = $total_centre_cnt;
      $result['total_active_centre_cnt'] = $total_active_centre_cnt;
      $result['total_in_active_centre_cnt'] = $total_in_active_centre_cnt;
      $result['total_in_review_centre_cnt'] = $total_in_review_centre_cnt;
      $result['total_re_submitted_centre_cnt'] = $total_re_submitted_centre_cnt;

      return $result;
    }//END : THIS FUNCTION IS USED TO GET THE TOTAL CENTRE DATA AS PER AGENCY ID

    //START : THIS FUNCTION IS USED TO GET THE TOTAL BATCH DATA AS PER AGENCY ID
    function get_total_batch_data($agency_id='')
    {
      $total_batch_cnt = $total_completed_batch_cnt = $total_ongoing_batch_cnt = $total_upcoming_batch_cnt = $total_rejected_hold_cancelled_batch_cnt = 0;

      if($agency_id > 0) { $this->db->where('acb.agency_id',$agency_id); } 
      $this->db->where(" acb.batch_status != '0' AND acb.batch_status != '8'");
      $batch_data = $this->master_model->getRecords('iibfbcbf_agency_centre_batch acb', array('acb.is_deleted'=>'0'), 'acb.batch_id, acb.agency_id, acb.centre_id, acb.inspector_id, acb.batch_code, acb.batch_start_date, acb.batch_end_date, acb.batch_status');
      
      if(count($batch_data) > 0)
      {
        $total_batch_cnt = count($batch_data);
        foreach($batch_data as $res)
        {
          if(date('Y-m-d') > $res['batch_end_date']) 
          { 
            if($res['batch_status'] == '3') { $total_completed_batch_cnt++; }
          }
          else if(date('Y-m-d') < $res['batch_start_date']) { $total_upcoming_batch_cnt++; }
          else if(date('Y-m-d') >= $res['batch_start_date'] && date('Y-m-d') <= $res['batch_end_date']) { $total_ongoing_batch_cnt++; }

          if($res['batch_status'] == '4' || $res['batch_status'] == '5' || $res['batch_status'] == '7') { $total_rejected_hold_cancelled_batch_cnt++; }
        }
      }

      $result['total_batch_cnt'] = $total_batch_cnt;
      $result['total_completed_batch_cnt'] = $total_completed_batch_cnt;
      $result['total_ongoing_batch_cnt'] = $total_ongoing_batch_cnt;
      $result['total_upcoming_batch_cnt'] = $total_upcoming_batch_cnt;
      $result['total_rejected_hold_cancelled_batch_cnt'] = $total_rejected_hold_cancelled_batch_cnt;

      return $result;
    }//END : THIS FUNCTION IS USED TO GET THE TOTAL BATCH DATA AS PER AGENCY ID

    //START : THIS FUNCTION IS USED TO GET THE TOTAL FACULTY DATA AS PER AGENCY ID
    function get_total_faculty_data($agency_id='')
    {
      $total_faculty_cnt = $total_active_faculty_cnt = $total_in_active_faculty_cnt = $total_in_review_faculty_cnt = $total_re_submitted_faculty_cnt = 0;

      if($agency_id > 0) { $this->db->where('fm.agency_id',$agency_id); }           
      $faculty_data = $this->master_model->getRecords('iibfbcbf_faculty_master fm', array('fm.is_deleted'=>'0'), 'fm.faculty_id, fm.agency_id, fm.centre_id, fm.faculty_number, fm.faculty_name, fm.status');

      if(count($faculty_data) > 0)
      {
        $total_faculty_cnt = count($faculty_data);
        foreach($faculty_data as $res)
        {
          if($res['status'] == '0') { $total_in_active_faculty_cnt++; }
          else if($res['status'] == '1') { $total_active_faculty_cnt++; }
          else if($res['status'] == '2') { $total_in_review_faculty_cnt++; }
          else if($res['status'] == '3') { $total_re_submitted_faculty_cnt++; }
        }
      }

      $result['total_faculty_cnt'] = $total_faculty_cnt;
      $result['total_active_faculty_cnt'] = $total_active_faculty_cnt;
      $result['total_in_active_faculty_cnt'] = $total_in_active_faculty_cnt;
      $result['total_in_review_faculty_cnt'] = $total_in_review_faculty_cnt;
      $result['total_re_submitted_faculty_cnt'] = $total_re_submitted_faculty_cnt;

      return $result;
    }//END : THIS FUNCTION IS USED TO GET THE TOTAL FACULTY DATA AS PER AGENCY ID

    //START : THIS FUNCTION IS USED TO GET THE TOTAL CANDIDATE DATA AS PER AGENCY ID
    function get_total_candidate_data($agency_id='')
    {
      $total_candidate_cnt = $total_training_completed_candidate_cnt = $total_hold_candidate_cnt = $total_exam_applied_candidate_cnt = 0;

      if($agency_id > 0) { $this->db->where('cand.agency_id',$agency_id); }   
      $this->db->join('iibfbcbf_agency_centre_batch acb', 'acb.batch_id = cand.batch_id', 'INNER');        
      $candidate_data = $this->master_model->getRecords('iibfbcbf_batch_candidates cand', array('cand.is_deleted'=>'0'), 'cand.candidate_id, cand.agency_id, cand.centre_id, cand.batch_id, cand.regnumber, cand.training_id, cand.hold_release_status, cand.re_attempt, acb.batch_end_date');

      if(count($candidate_data) > 0)
      {
        $total_candidate_cnt = count($candidate_data);
        foreach($candidate_data as $res)
        {
          if($res['hold_release_status'] == '3' && $res['batch_end_date'] < date('Y-m-d')) 
          {
            $total_training_completed_candidate_cnt++;
          }
          else if($res['hold_release_status'] == '1' || $res['hold_release_status'] == '2') { $total_hold_candidate_cnt++; }

          if($res['re_attempt'] > 0) { $total_exam_applied_candidate_cnt++; }
        }
      }

      $result['total_candidate_cnt'] = $total_candidate_cnt;
      $result['total_training_completed_candidate_cnt'] = $total_training_completed_candidate_cnt;
      $result['total_hold_candidate_cnt'] = $total_hold_candidate_cnt;
      $result['total_exam_applied_candidate_cnt'] = $total_exam_applied_candidate_cnt;

      return $result;
    }//END : THIS FUNCTION IS USED TO GET THE TOTAL CANDIDATE DATA AS PER AGENCY ID

    /************************ END : DASHBOARD COUNT QUERIES *******************************/

    //START : THIS FUNCTION IS USED TO GET THE TOTAL EXAM REGISTRATION DATA
    function get_total_exam_registraion_data()
    {
      $total_exam_registraion_cnt = $total_basic_exam_reg_cnt = $total_advanced_exam_reg_cnt = $total_basic_re_attempt_exam_reg_cnt = $total_advanced_re_attempt_exam_reg_cnt = 0; //FOR ALL REGISTRATION COUNT

      $today_exam_registraion_cnt = $today_basic_exam_reg_cnt = $today_advanced_exam_reg_cnt = $today_basic_re_attempt_exam_reg_cnt = $today_advanced_re_attempt_exam_reg_cnt = 0; //FOR TODAYS REGISTRATION COUNT
      
      $this->db->where_in('me.exam_code', array(1037,1038,1039,1040,1041,1042));
      $this->db->join('iibfbcbf_batch_candidates bc', 'bc.candidate_id = me.candidate_id', 'INNER');
      $exam_registraion_data = $this->master_model->getRecords('iibfbcbf_member_exam me', array('me.is_deleted'=>'0','me.pay_status'=>'1'), 'me.candidate_id, me.exam_code, DATE(me.created_on) AS created_on, bc.re_attempt, me.pay_status');
      //_pq(1);

      $basic_exam_code_arr = array(1038,1040,1042);
      $advanced_exam_code_arr = array(1037,1039,1041);
      
      $total_exam_registraion_cnt = count($exam_registraion_data);
      if($total_exam_registraion_cnt > 0)
      {        
        foreach($exam_registraion_data as $res)
        {
          if(in_array($res['exam_code'], $basic_exam_code_arr))//FOR BASIC
          {
            $total_basic_exam_reg_cnt++;
            
            if($res['re_attempt'] > 1) { $total_basic_re_attempt_exam_reg_cnt++; }

            //START : FOR TODAYS REPORT
            if($res['created_on'] == date('Y-m-d')) 
            {  
              $today_exam_registraion_cnt++;
              $today_basic_exam_reg_cnt++;

              if($res['re_attempt'] > 1) { $today_basic_re_attempt_exam_reg_cnt++; }              
            }//END : FOR TODAYS REPORT
          }
          else if(in_array($res['exam_code'], $advanced_exam_code_arr))//FOR ADVANCED
          {
            $total_advanced_exam_reg_cnt++;

            if($res['re_attempt'] > 1) { $total_advanced_re_attempt_exam_reg_cnt++; }

            //START : FOR TODAYS REPORT
            if($res['created_on'] == date('Y-m-d')) 
            {  
              $today_exam_registraion_cnt++;
              $today_advanced_exam_reg_cnt++;

              if($res['re_attempt'] > 1) { $today_advanced_re_attempt_exam_reg_cnt++; }
            }//END : FOR TODAYS REPORT
          }
        }
      }

      $result['total_exam_registraion_cnt'] = $total_exam_registraion_cnt;
      $result['total_basic_exam_reg_cnt'] = $total_basic_exam_reg_cnt;
      $result['total_advanced_exam_reg_cnt'] = $total_advanced_exam_reg_cnt;
      $result['total_basic_re_attempt_exam_reg_cnt'] = $total_basic_re_attempt_exam_reg_cnt;
      $result['total_advanced_re_attempt_exam_reg_cnt'] = $total_advanced_re_attempt_exam_reg_cnt;
      
      $result['today_exam_registraion_cnt'] = $today_exam_registraion_cnt;
      $result['today_basic_exam_reg_cnt'] = $today_basic_exam_reg_cnt;
      $result['today_advanced_exam_reg_cnt'] = $today_advanced_exam_reg_cnt;
      $result['today_basic_re_attempt_exam_reg_cnt'] = $today_basic_re_attempt_exam_reg_cnt;
      $result['today_advanced_re_attempt_exam_reg_cnt'] = $today_advanced_re_attempt_exam_reg_cnt;

      return $result;
    }//END : THIS FUNCTION IS USED TO GET THE TOTAL EXAM REGISTRATION DATA

    //START : THIS FUNCTION IS USED TO GET THE TOTAL REGISTRATION DATA FOR TRAINING
    function get_total_registraion_for_training_data($id='')
    {
      $total_registraion_for_training_cnt = $total_basic_reg_for_training_cnt = $total_advance_reg_for_training_cnt = $total_re_attempt_basic_reg_for_training_cnt = $total_re_attempt_advanced_reg_for_training_cnt = $total_eligible_for_exam = $total_re_enroll_for_training = 0; 

      $this->db->join('iibfbcbf_agency_centre_batch acb', 'acb.batch_id = bc.batch_id', 'INNER'); 
      $exam_registraion_data = $this->master_model->getRecords('iibfbcbf_batch_candidates bc', array('bc.is_deleted'=>'0', 'acb.batch_status'=>'3', 'acb.is_deleted'=>'0'), 'bc.re_attempt, acb.batch_type, bc.parent_id, bc.parent_table_name, bc.hold_release_status, acb.batch_start_date, acb.batch_end_date');
      
      $todays_date = date('Y-m-d');
      $total_registraion_for_training_cnt = count($exam_registraion_data);
      if($total_registraion_for_training_cnt > 0)
      {        
        foreach($exam_registraion_data as $res)
        {
          if($res['batch_type'] == '1') 
          { 
            $total_basic_reg_for_training_cnt++; 
            if(/* $res['re_attempt'] > 1 &&  */$res['parent_table_name'] == 'iibfbcbf_batch_candidates') { $total_re_attempt_basic_reg_for_training_cnt++; } 
          }
          else if($res['batch_type'] == '2') 
          { 
            $total_advance_reg_for_training_cnt++; 
            if(/* $res['re_attempt'] > 1 &&  */$res['parent_table_name'] == 'iibfbcbf_batch_candidates') { $total_re_attempt_advanced_reg_for_training_cnt++; } 
          }  
          
          //START : ELIGIBLE FOR EXAMINATION BUT NOT APPLIED
          $validity_start_date = date('Y-m-d', strtotime("+".$this->buffer_days_after_training_end_date."days", strtotime($res['batch_end_date'])));
          $validity_end_date = date('Y-m-d', strtotime("+".$this->buffer_days_after_candidate_add_date."days", strtotime($res['batch_end_date'])));

          //todays date is greater than batch end date + buffer_days_after_training_end_date
          //todays date is less than batch end date + buffer_days_after_candidate_add_date
          //candidate is released
          //re-attempt is zero
          if($validity_start_date < $todays_date && $todays_date <= $validity_end_date && $res['re_attempt'] == '0' && $res['hold_release_status'] == '3')
          {
            $total_eligible_for_exam++;
          }
          //END : ELIGIBLE FOR EXAMINATION BUT NOT APPLIED

          //START : CANDIDATES REQUIRED TO RE-ENROLL FOR TRAINING
          if($res['re_attempt'] == '3' || $todays_date > $validity_end_date || ($res['hold_release_status'] != '3' && $validity_start_date < $todays_date))
          {
            $total_re_enroll_for_training++;
          }
          //END : CANDIDATES REQUIRED TO RE-ENROLL FOR TRAINING
        }
      }

      $result['total_registraion_for_training_cnt'] = $total_registraion_for_training_cnt;
      $result['total_basic_reg_for_training_cnt'] = $total_basic_reg_for_training_cnt;
      $result['total_advance_reg_for_training_cnt'] = $total_advance_reg_for_training_cnt;
      $result['total_re_attempt_basic_reg_for_training_cnt'] = $total_re_attempt_basic_reg_for_training_cnt;
      $result['total_re_attempt_advanced_reg_for_training_cnt'] = $total_re_attempt_advanced_reg_for_training_cnt;
      $result['total_eligible_for_exam'] = $total_eligible_for_exam;
      $result['total_re_enroll_for_training'] = $total_re_enroll_for_training;

      return $result;
    }//END : THIS FUNCTION IS USED TO GET THE TOTAL REGISTRATION DATA FOR TRAINING

    public function mask_email_mobile($type = '', $str = '') //$type = 'mobile' / 'email' : 
    {
      $show_start = 2;
      $show_end = 3;

      if ($type == 'email')
      {
        $show_start = 2;

        $explode_email_arr = explode("@", $str);
        $show_end = strlen($explode_email_arr[1]) + 2;
      }

      return substr($str, 0, $show_start) . str_repeat('*', (strlen($str) - ($show_start + $show_end))) . substr($str, '-' . $show_end);
    }

    public function calculate_no_of_pass_fail_absent_candidates($batch_id){
      
      $pass_cnt = $fail_cnt = $absent_cnt = $api_record_found = 0;

      if($batch_id != ""){
        $todays_date = date("Y-m-d");
        $this->db->join('iibfbcbf_member_exam me', 'me.batch_id = bc.batch_id AND me.candidate_id = bc.candidate_id', 'INNER'); //acb11.batch_end_date < CURDATE()
        $this->db->join('iibfbcbf_agency_centre_batch acb', 'acb.batch_id = bc.batch_id', 'INNER'); 
        $this->db->group_by('bc.regnumber'); 
        $exam_registraion_data = $this->master_model->getRecords('iibfbcbf_batch_candidates bc', array('bc.is_deleted'=>'0', 'bc.batch_id'=>$batch_id, 'acb.batch_status'=>'3', 'acb.is_deleted'=>'0', 'DATE(me.exam_date) < ' => $todays_date), 'me.exam_code, me.exam_period, bc.regnumber, bc.candidate_id, bc.re_attempt, acb.batch_type, bc.parent_id, bc.parent_table_name, bc.hold_release_status, acb.batch_start_date, acb.batch_end_date, MAX(DATE(me.exam_date))');

        if($exam_registraion_data && count($exam_registraion_data) > 0){
          foreach($exam_registraion_data as $res){
            if($res["exam_code"] != "" && $res["exam_period"] != "" && $res["regnumber"] != ""){
              //echo $res["exam_code"]."/".$res["exam_period"]."/".$res["regnumber"];die;
              //21/122/1/100019014
              //$response = $this->get_iibfbcbf_result_related_api('getResult', $res["exam_code"], $res["exam_period"], '1', $res["regnumber"]);
              

              /*if($res["regnumber"] == "800000235"){
                $res["exam_code"] = "21";
                $res["exam_period"] = "122"; 
                $res["regnumber"] = "100019014";
              }else if($res["regnumber"] == "800000220"){
                $res["exam_code"] = "60";
                $res["exam_period"] = "221";  
                $res["regnumber"] = "510028070";
              }*/

              $response = $this->get_iibfbcbf_result_related_api('getResult', $res["exam_code"], $res["exam_period"], '1', $res["regnumber"]);
              //$response = $this->get_iibfbcbf_result_related_api('getResult', '21', '122', '1', '100019014');
              //$response = $this->get_iibfbcbf_result_related_api('getResult', '60', '221', '1', '510028070');
              //echo $response;die;

              if(trim($response) == "Pass"){
                $pass_cnt++;
              }else if(trim($response) == "Fail"){
                $fail_cnt++;
              }else if(trim($response) == "Absent"){
                $absent_cnt++;
              }else if(trim($response) == "Notfound"){
                $api_record_found++;
              }

            }
          }
        }

      }
      
      $data['pass_cnt'] = $pass_cnt;
      $data['fail_cnt'] = $fail_cnt;
      $data['absent_cnt'] = $absent_cnt;
      $data['api_record_found'] = $api_record_found;
      return $data;
    }

    //IIBFBCBF Result Details API
    public function get_iibfbcbf_result_related_api($type = '', $exam_code = '', $exam_period = '', $part_no = '1', $member_no = '')
    {
 
      //echo $type.' / '.$exam_code.' / '.$exam_period.' / '.$part_no.' / 1 / '.$member_no; 
      $final_arr = $response_msg = array();
      $response = '';

      $pass_cnt = $fail_cnt = $absent_cnt = $api_record_found = 0; 
      
      $pass_fail_absent_flag = "Notfound";

      $part_no = '1';
      /*$exam_code = '21'; $exam_period = '122'; $member_no = '100019014';*/
      /*60/221/1/510028070*/

      if(base_url() == 'http://172.16.24.5/')//FOR QA ENVIRONMENT : THE CLIENT API DOES NOT HAVE ACCESS ON QA ENVIRONMENT
      {
        $url="https://iibf.esdsconnect.com/staging/iibfbcbf/admin/Reports/get_iibfbcbf_result_related_api_curl_qa/".$type."/".$exam_code."/".$exam_period."/".$part_no."/".$member_no; //for QA
                
        $string = preg_replace('/\s+/', '+', $url);
        $x = curl_init($string);
        curl_setopt($x, CURLOPT_HEADER, 0);    
        curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);    
        curl_setopt($x, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($x, CURLOPT_SSL_VERIFYPEER, FALSE);
        
        $result = curl_exec($x);
        return $pass_fail_absent_flag = json_decode($result,true);
      }

      $api_name = "Result Downloading Member wise API UAT";  
      $url="http://10.10.233.66:8088/ResultDownloadApi/getResultDownloadDtls/".$exam_code."/".$exam_period."/".$part_no."/".$member_no; // Result Downloading Member wise API UAT  
      if($type == "getResult"){
        $api_name = "Result Downloading Member wise API UAT";  
        $url="http://10.10.233.66:8088/ResultDownloadApi/getResultDownloadDtls/".$exam_code."/".$exam_period."/".$part_no."/".$member_no; // Result Downloading Member wise API UAT
      }else if($type == "getMarks"){
        $api_name = "Marks Obtained Details API UAT";      
        $url="http://10.10.233.66:8088/ResultDownloadApi/getMarksObtained/".$exam_code."/".$exam_period."/".$part_no; // Marks Obtained Details API UAT
      }else if($type == "getMember"){
        $api_name = "Member Details API UAT";  
        $url="http://10.10.233.66:8088/ResultDownloadApi/getMemberDetails/".$exam_code."/".$exam_period."/".$part_no; // Member Details API UAT
      }else if($type == "getSubject"){
        $api_name = "Subject Details API UAT";  
        $url="http://10.10.233.66:8088/ResultDownloadApi/getSubjectDetails/".$exam_code."/".$exam_period."/".$part_no; // Subject Details API UAT
      }else if($type == "getExam"){
        $api_name = "Exam Details API UAT";  
        $url="http://10.10.233.66:8088/ResultDownloadApi/getExamDetails/".$exam_code."/".$exam_period."/".$part_no; // Exam Details API UAT
      } 

      $string = preg_replace('/\s+/', '+', $url);
      $x = curl_init($url);
      curl_setopt($x, CURLOPT_HEADER, 0);
      curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);
      curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($x, CURLOPT_SSL_VERIFYHOST, FALSE);
      curl_setopt($x, CURLOPT_SSL_VERIFYPEER, FALSE);

      $api_result = curl_exec($x);

      if (curl_errno($x))  //CURL ERROR
      {
        echo "<h4 class='error_block'>Invalid Data</h4>";
        echo '<br>response_msg : '.$response_msg = curl_error($x);
      }
      else
      {
        if ($api_result)
        {
          //echo ($api_result);
          $api_result = json_decode($api_result);

          /*echo "<br> <b>API Name:</b> ".$api_name."<br>";  
          echo '<pre>';
          //print_r($api_result);
          echo "<br>marksObtained: ";
          print_r($api_result[0]->marksObtained);die;

          echo "<br>memberDetails: ";
          print_r($api_result[0]->memberDetails);

          echo "<br>subjectDetails: ";
          print_r($api_result[0]->subjectDetails);

          echo "<br>examDetails: ";
          print_r($api_result[0]->examDetails);

          echo '</pre>';
          exit;*/   

          if(isset($api_result[0]) && isset($api_result[0]->marksObtained) && count($api_result[0]->marksObtained) > 0){
            foreach($api_result[0]->marksObtained as $res){
              if($res->memberCode != "" && $res->passFail != ""){
                //echo $res->memberCode."/".$res->examCd."/".$res->examPeriod."/".$res->passFail;die;
                if($res->examCd == $exam_code && $res->examPeriod == $exam_period && $res->memberCode == $member_no){ 
                  $api_record_found++;
                  if(trim($res->passFail) == "F"){
                    $fail_cnt++; 
                  }
                  if(trim($res->passFail) == "A"){
                    $absent_cnt++; 
                  }
                  if(trim($res->passFail) == "P"){
                    $pass_cnt++; 
                  } 
                } 
              } 
            }

            if(count($api_result[0]->marksObtained) == $pass_cnt){
              $pass_fail_absent_flag = "Pass";
            }else if(count($api_result[0]->marksObtained) == $fail_cnt){
              $pass_fail_absent_flag = "Fail";
            }else if(count($api_result[0]->marksObtained) == $absent_cnt){
              $pass_fail_absent_flag = "Absent";
            }else{
                if($fail_cnt > 0){
                  $pass_fail_absent_flag = "Fail";
                }else if($absent_cnt > 0 && $fail_cnt == 0){
                  $pass_fail_absent_flag = "Absent";
                }else if($absent_cnt > 0 && $fail_cnt > 0){
                  $pass_fail_absent_flag = "Fail";
                }
            }  
          } 
        }
         
      }
      curl_close($x);

      return $pass_fail_absent_flag;
    }

  }				  