<?php
  /********************************************************************************************************************
  ** Description: Common Model for NCVET Module
  ** Created BY: Gaurav Shewale On 11-08-2025
  ********************************************************************************************************************/
	class Ncvet_model extends CI_Model
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model('master_model');			
			$this->load->model('Emailsending');			
    }
		
		function check_session_login()/******** START : CHECK IF ANY SESSION IS ALREADY STARTED OR NOT ON GLOBAL LOGIN PAGE ********/
		{
			$login_user_id   = $this->session->userdata('NCVET_LOGIN_ID'); 
			$login_user_type = $this->session->userdata('NCVET_USER_TYPE'); 
			if(isset($login_user_id) && $login_user_id != "" && isset($login_user_type) && $login_user_type != "")
			{
        if($login_user_type == 'admin') //CHECK IN ADMIN
        {
          $admin_data = $this->master_model->getRecords('ncvet_admin', array('admin_id' => $login_user_id, 'is_active' => '1', 'is_deleted' => '0'), 'admin_id');

          if(count($admin_data) > 0) { redirect(site_url('ncvet/admin/dashboard_admin'),'refresh'); }
				  else { redirect(site_url('ncvet/login/logout'),'refresh'); }
        }
       			
      }
    }/******** END : CHECK IF ANY SESSION IS ALREADY STARTED OR NOT ON GLOBAL LOGIN PAGE ********/
		
    /******** START : CHECK SESSION AFTER LOGIN FOR ALL ADMIN, AGENCY, CENTER PAGES ********/
		function check_admin_session_all_pages($login_as='') //login_as = admin or recommender or approver
		{
			$login_user_id = $this->session->userdata('NCVET_LOGIN_ID');
			$login_user_type = $this->session->userdata('NCVET_USER_TYPE');

			if (!isset($login_user_id) || $login_user_id == "" || !isset($login_user_type) || $login_user_type == "")
			{
				redirect(site_url('ncvet/login/logout'),'refresh');
      }
			else
			{
        if($login_as == '') { redirect(site_url('ncvet/login/logout'),'refresh'); }
        else
        {
          if($login_as != $login_user_type) 
          {  
            if($login_user_type == 'admin') 
            { 
              redirect(site_url('ncvet/admin/dashboard_admin'),'refresh'); 
            }
            else if($login_user_type == 'recommender' || $login_user_type == 'approver') 
            { 
              //redirect(site_url('ncvet/agency/dashboard_agency'),'refresh');
            }
          }
        }
        
        if($login_user_type == 'admin')
        {
          $admin_data = $this->master_model->getRecords('ncvet_admin', array('admin_id' => $login_user_id, 'is_active' => '1', 'is_deleted' => '0'), 'admin_id');

          if(count($admin_data) == 0) 
          { 
            redirect(site_url('ncvet/login/logout'),'refresh'); 
          }
        }
        
      }
    }/******** END : CHECK SESSION AFTER LOGIN FOR ALL ADMIN, AGENCY PAGES ********/

    function check_session_candidate_login()/******** START : CHECK IF CANDIDATE SESSION IS ALREADY STARTED OR NOT ON CANDIDATE LOGIN PAGE **/
		{
			$login_candidate_id = $this->session->userdata('NCVET_CANDIDATE_LOGIN_ID');
			if(isset($login_candidate_id) && $login_candidate_id != "")
			{
        $candidate_data = $this->master_model->getRecords('ncvet_candidates cand', array('cand.candidate_id' => $login_candidate_id, 'cand.is_deleted' => '0'), 'cand.candidate_id');

        if(count($candidate_data) > 0) { redirect(site_url('ncvet/candidate/dashboard_candidate'),'refresh'); }
        else { redirect(site_url('ncvet/candidate/login_candidate/logout'),'refresh'); }        			
      }
    }/******** END : CHECK IF CANDIDATE SESSION IS ALREADY STARTED OR NOT ON CANDIDATE LOGIN PAGE ********/
		
    /******** START : CHECK SESSION AFTER LOGIN FOR CANDIDATE PAGES ********/
		function check_candidate_session_all_pages()
		{
			$login_candidate_id = $this->session->userdata('NCVET_CANDIDATE_LOGIN_ID');
      if (!isset($login_candidate_id) || $login_candidate_id == "")
			{
        $this->session->set_flashdata('error', 'Please login again to vistit this page.');
				redirect(site_url('ncvet/candidate/login_candidate/logout'),'refresh');
      }
			else
			{
        $candidate_data = $this->master_model->getRecords('ncvet_candidates', array('candidate_id' => $login_candidate_id, 'is_deleted' => '0'), 'candidate_id, is_active');

        if(count($candidate_data) == 0) 
        { 
          $this->session->set_flashdata('error', 'Please login again to vistit this page.');
          redirect(site_url('ncvet/candidate/login_candidate/logout'),'refresh'); 
        }
        else
        {
          if($candidate_data[0]['is_active'] != '1')
          {
            $this->session->set_flashdata('error', 'You are on Hold status');
          redirect(site_url('ncvet/candidate/login_candidate/logout'),'refresh'); 
        }
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
        $admin_data = $this->master_model->getRecords('ncvet_admin', array('admin_id' => $user_id), 'admin_id, admin_name');
              
        if(count($admin_data) > 0) { $disp_name = $admin_data[0]['admin_name']; }
      }
     
      else if($type == 'candidate')
      {
        $disp_name = $disp_sidebar_name = 'Candidate';
        $candidate_data = $this->master_model->getRecords('ncvet_candidates', array('candidate_id' => $user_id), 'candidate_id, salutation, first_name, middle_name, last_name,candidate_photo');
        
        $candidate_photo = base_url('uploads/ncvet/default-user.webp');

        if(count($candidate_data) > 0) 
        { 
          $disp_name = $candidate_data[0]['salutation'].' '.$candidate_data[0]['first_name']; 
          if($candidate_data[0]['middle_name'] != "") { $disp_name .= ' '.$candidate_data[0]['middle_name']; }
          if($candidate_data[0]['last_name'] != "") { $disp_name .= ' '.$candidate_data[0]['last_name']; }

          $disp_sidebar_name = $candidate_data[0]['salutation'].' '.$candidate_data[0]['first_name'];

          if ($candidate_data[0]['candidate_photo'] != '') {
            $candidate_photo = base_url('uploads/ncvet/photo/'.$candidate_data[0]['candidate_photo']);
          }
          $data['candidate_photo'] = $candidate_photo; 
        }
      }
      
      $data['disp_name']         = $disp_name;
      $data['disp_sidebar_name'] = $disp_sidebar_name;
			return $data;
    }/******** END : GET LOGGED IN ADMIN, AGENCY DETAILS ********/

    function insert_user_login_logs($user_id=0, $user_type=0, $type=0) /******** START : MAINTAIN LOGIN - LOGOUT LOGS ********/
		{
      $this->load->helper('ncvet/ncvet_helper');
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
			$this->master_model->insertRecord('ncvet_login_logs',$add_log);
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
      $this->load->helper('ncvet/ncvet_helper');
			$this->load->helper('url');
			$this->load->library('user_agent');
      
      $user_type = $this->session->userdata('NCVET_USER_TYPE');
      $login_id = $this->session->userdata('NCVET_LOGIN_ID');

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
			$this->master_model->insertRecord('ncvet_logs',$add_log);
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
			
			if(!in_array(strtolower($ext_img),$valid_ext_arr))
			{
				$flag=1;
			}
			
			if($flag == 0)
			{
        $this->load->helper('ncvet/ncvet_helper'); 
				create_directories($upload_path); //ncvet/ncvet_helper.php
				
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

          // Handle transparency for PNG images
          if (strtolower($ext) === 'png') 
          {
            $file_path = $upload_path.'/'.$final_img;
            $this->convert_png_to_white_background($file_path);
          }

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

    function convert_png_to_white_background($file_path) 
    {
      // Create image from PNG file
      $image = imagecreatefrompng($file_path);
      
      // Get image width and height
      $width = imagesx($image);
      $height = imagesy($image);
      
      // Create a new true color image with a white background
      $new_image = imagecreatetruecolor($width, $height);
      $white = imagecolorallocate($new_image, 255, 255, 255); // Set white background
      imagefilledrectangle($new_image, 0, 0, $width, $height, $white);
      
      // Copy original PNG image over the white background
      imagecopy($new_image, $image, 0, 0, 0, 0, $width, $height);
      
      // Save the new image as PNG (overwrite the original file)
      imagepng($new_image, $file_path);
      
      // Free memory
      imagedestroy($image);
      imagedestroy($new_image);
    }
    
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
        if(!isset($file_name) || $file_name == '' || !file_exists($file_path.$file_name))//file_path = "./ncvet/uploads/faculty_photo/"
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

   

    //START : THIS FUNCTION IS USED TO GET THE EXAM CODE USING BATCH TYPE
    function get_exam_code_individual($batch_type='')
    {
      $exam_code = '0';
      /* if($batch_type == '1') { $exam_code = '5001'; }
      else if($batch_type == '2') { $exam_code = '5002'; }
      return $exam_code; */
      
      $this->db->where_in('em.exam_code',array(1037,1038),FALSE);
      $this->db->having(' (CURRENT_TIMESTAMP BETWEEN ChkExamStart AND ChkExamEnd) ');
      $this->db->join('ncvet_exam_activation_master eam', 'eam.exam_code = em.exam_code', 'INNER');
      $get_active_exam_data = $this->master_model->getRecords('ncvet_exam_master em', array('em.exam_delete'=>'0', 'eam.exam_activation_delete' => '0', 'em.exam_type'=>$batch_type), "em.exam_code, em.description, em.exam_type, CONCAT(eam.exam_from_date,' ', eam.exam_from_time) AS ChkExamStart, CONCAT(eam.exam_to_date,' ', eam.exam_to_time) AS ChkExamEnd");
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
      
        
        //ELIGIBLE MASTER API CODE GOES HERE
        $eligible_api_res = $this->ncvet_eligible_master_api($exam_code,$exam_period, $regnumber);
        //_pa($eligible_api_res,1);
        if($eligible_api_res['api_res_flag'] == 'success')
        {
          if(isset($eligible_api_res['api_res_response'][0]) && count($eligible_api_res['api_res_response'][0]) > 0)
          {
            $eligible_data = $eligible_api_res['api_res_response'][0];
            $eligible_app_cat = '';
            if(isset($eligible_data['app_cat'])) { $eligible_app_cat = $eligible_data['app_cat']; }
            if($eligible_app_cat != "")
            {
              //if($eligible_app_cat == 'R' && in_array($regnumber, array(802706227,802706218))) { $eligible_app_cat = 'B1_2'; } //ADDED BY SAGAR MATALE ON 2024-12-16 AS CONFIRMED BY DEAN ON CALL AND MAIL
              //if($eligible_app_cat == 'R') { $eligible_app_cat = 'B1_1'; } //ADDED BY SAGAR MATALE ON 2025-05-23 AS CONFIRMED BY DEAN AT MUMBAI LOCATION DURING TECHNICAL KT SESSION
              
              $group_code = $eligible_app_cat;

              if($group_code == "R") { $group_code = "B1_1"; }

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

    public function ncvet_eligible_master_api($exam_code=0, $exam_period=0,$member_no=0)
    {
      $this->load->helper('ncvet/ncvet_helper');
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
      $log_id = $this->master_model->insertRecord('ncvet_eligible_api_response_log',$add_log_data,true);
      
      
      {
        //$api_url="http://10.10.233.66:8092/getBCBFEligibleData/".$exam_code."/".$exam_period."/".$member_no; //UAT API 
        $api_url="http://10.10.233.76:8089/getBCBFEligibleData/".$exam_code."/".$exam_period."/".$member_no;	//PRODUCTION API ADDED BY SAGAR ON 2024-03-19					
      }
      
      $string = preg_replace('/\s+/', '+', $api_url);
      $x = curl_init($string);
      curl_setopt($x, CURLOPT_HEADER, 0);    
      curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);
      curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);    
      curl_setopt($x, CURLOPT_SSL_VERIFYHOST, FALSE);
      curl_setopt($x, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($x, CURLOPT_TIMEOUT, 90); 
      
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
        $this->master_model->updateRecord('ncvet_eligible_api_response_log', $up_log_data,  array('log_id'=>$log_id));
      }

			return $api_result_arr;
    }

    function ncvet_send_mail_common($mail_arg=array())
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

        $this->email->from($from_email, $from_name);
        $this->email->to($to_email, $to_name); 
        $this->email->subject($subject);
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
    
    function ncvet_get_fees($exam_code=0,$exam_period=0)
    {
      $fresh_fee = $rep_fee = 0;
      $fee_data = $this->master_model->getRecords('ncvet_exam_fee_master',array('fee_delete'=>'0', 'member_category'=>'NM', 'exempt'=>'NE', 'exam_code'=>$exam_code, 'exam_period'=>$exam_period));
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
      $venue_master_eligible_exam_codes_arr = array(1041,1042,1057);
      $csc_venue_master_eligible_exam_codes_arr = array(1039,1040);

      $pt_id = url_decode($enc_pt_id);
      $payment_data = $this->master_model->getRecords('ncvet_payment_transaction',array('id'=>$pt_id, 'status'=>'1'), 'id, agency_id, centre_id, exam_ids, exam_code, exam_period, gateway, amount, date, agency_code, transaction_no, UTR_no, UTR_slip_file, pay_count, receipt_no, description, transaction_details, payment_mode, status');

      $eligible_exam_code_for_admit_card_arr = array(1039,1040,1041,1042,1057);
      if(count($payment_data) > 0 && in_array($payment_data[0]['exam_code'], $eligible_exam_code_for_admit_card_arr))
      {
        $centre_data = $this->master_model->getRecords('ncvet_centre_master',array('centre_id'=>$payment_data[0]['centre_id']), 'centre_id, centre_username, centre_name');

        $subject_master = $this->master_model->getRecords('ncvet_exam_subject_master',array('exam_code'=>$payment_data[0]['exam_code'], 'exam_period'=>$payment_data[0]['exam_period'],'subject_delete'=>'0','group_code'=>'C'),'subject_code, subject_description',array('subject_code'=>'ASC'));

        $agency_data = $this->master_model->getRecords('ncvet_agency_master',array('agency_id'=>$payment_data[0]['agency_id']), 'agency_id, agency_name, agency_code');
        
        $exam_mode = '';
        $exam_activation_data = $this->master_model->getRecords('ncvet_exam_activation_master',array('exam_code'=>$payment_data[0]['exam_code'], 'exam_period'=>$payment_data[0]['exam_period'], 'exam_activation_delete' => '0'), 'exam_mode');
        if(count($exam_activation_data) > 0)
        {
          $exam_mode = $exam_activation_data[0]['exam_mode'];
        }
        
        //_pa($payment_data,1);

        $this->db->where(" (me.member_exam_id IN (".$payment_data[0]['exam_ids'].") ) ");
        $this->db->join('ncvet_candidates cand', 'cand.candidate_id = me.candidate_id', 'INNER');
        $this->db->join('city_master cm', 'cm.id = cand.city', 'LEFT');
        $this->db->join('state_master sm', 'sm.state_code = cand.state', 'LEFT');
        $this->db->join('ncvet_exam_medium_master mm', 'mm.medium_code = me.exam_medium AND mm.exam_code = me.exam_code AND mm.exam_period = me.exam_period', 'LEFT');
        $member_exam_data = $this->master_model->getRecords('ncvet_member_exam me',array('me.exam_code'=>$payment_data[0]['exam_code'], 'me.exam_period'=>$payment_data[0]['exam_period']), 'me.member_exam_id, me.candidate_id, me.batch_id, me.exam_date, me.payment_mode, me.exam_centre_code, me.exam_venue_code, me.exam_time, cand.regnumber, cand.training_id, cand.salutation, cand.first_name, cand.middle_name, cand.last_name, cand.dob, cand.gender, cand.mobile_no, cand.email_id, cand.address1, cand.address2, cand.address3, cand.address4, cand.state, cand.city, cand.district, cand.pincode, cand.registration_type, cm.city_name, sm.state_name, sm.zone_code, mm.medium_description');

        //_pq();
        //_pa($member_exam_data);

        if(count($member_exam_data) > 0)
        {
          /* //START : CHECK IF ADMITCARD RECORD EXIST OR NOT. IF EXIST, DELETE IT AND INSERT NEW RECORD
          foreach($member_exam_data as $res)
          {
            $chk_admitcard_exist = $this->master_model->getRecords('ncvet_admit_card_details',array('mem_exam_id'=>$res['member_exam_id'], 'batchId'=>$res['batch_id'], 'mem_mem_no'=>$res['regnumber'], 'exm_cd'=>$payment_data[0]['exam_code'], 'exm_prd'=>$payment_data[0]['exam_period']), 'admitcard_id');
            _pq();
            
            if(count($chk_admitcard_exist) > 0)
            {
              foreach($chk_admitcard_exist as $chk_admitcard_res)
              {
                $this->db->where('admitcard_id', $chk_admitcard_res['admitcard_id']);
                $this->db->delete('ncvet_admit_card_details');
              }
            }
          }//END : CHECK IF ADMITCARD RECORD EXIST OR NOT. IF EXIST, DELETE IT AND INSERT NEW RECORD */
          
          foreach($member_exam_data as $res)
          {
            $chk_admitcard_exist = $this->master_model->getRecords('ncvet_admit_card_details',array('mem_exam_id'=>$res['member_exam_id'], 'batchId'=>$res['batch_id'], 'mem_mem_no'=>$res['regnumber'], 'exm_cd'=>$payment_data[0]['exam_code'], 'exm_prd'=>$payment_data[0]['exam_period']), 'admitcard_id');

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
            $get_exam_centre_name = $this->master_model->getRecords('ncvet_exam_centre_master',array('centre_code'=>$res['exam_centre_code'], 'exam_name'=>$payment_data[0]['exam_code'], 'exam_period'=>$payment_data[0]['exam_period'], 'centre_delete'=>'0'), 'centre_name');
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
              $venue_data = $this->master_model->getRecords('ncvet_exam_venue_master', array('exam_date' => $res['exam_date'], 'centre_code' => $res['exam_centre_code'], 'session_time' => $res['exam_time'], 'venue_code' => $res['exam_venue_code'], 'exam_period' => $payment_data[0]['exam_period'], 'exam_date >'=>date("Y-m-d")), 'venue_master_id, venue_code, venue_name, venue_addr1, venue_addr2, venue_addr3, venue_addr4, venue_addr5, venue_pincode', array('venue_addr1'=>'ASC'));
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
              $get_admitcard_data = $this->master_model->getRecords('ncvet_admit_card_details', array(
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
              $venue_data = $this->master_model->getRecords('ncvet_exam_venue_master', array('centre_code' => $res['exam_centre_code'], 'venue_code' => $res['exam_venue_code'], 'exam_period' => $payment_data[0]['exam_period'], 'exam_date'=>'0000-00-00'), 'venue_master_id, venue_code, venue_name, venue_addr1, venue_addr2, venue_addr3, venue_addr4, venue_addr5, venue_pincode', array('venue_addr1'=>'ASC'));              

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
              
              $get_admitcard_data = $this->master_model->getRecords('ncvet_admit_card_details', array(
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
              $admitcard_id = $this->master_model->insertRecord('ncvet_admit_card_details',$add_data,true);
              //echo '<br><br>'.$this->db->last_query();
            }
            else
            {
              $this->master_model->updateRecord('ncvet_admit_card_details', $add_data, array('admitcard_id'=>$chk_admitcard_exist[0]['admitcard_id']));

              //echo '<br><br>'.$this->db->last_query();
            }
            //_pq(1);
            //_pa($add_data);
          }
        }
      }
      //exit;
    }

    function get_capacity_bulk($exam_code = '', $exam_period = '',$exam_centre_code = '', $exam_venue_code = '', $exam_date = '', $exam_time = '', $member_exam_id=0, $chk_capacity_type='')
    {
      $valid_exam_code_arr = array(1041,1042,1057);
      $seat_capacity='0';
      
      if($exam_code != '' && $exam_period != '' && $exam_centre_code != '' && $exam_venue_code != '' && $exam_date != '' && $exam_time != '' && in_array($exam_code, $valid_exam_code_arr))
      {
        $this->db->where(" FIND_IN_SET('".$exam_code."',exam_codes) > 0 ");
        $seat_count = $this->master_model->getRecords('ncvet_exam_venue_master', array('exam_period'=>$exam_period,'centre_code'=>$exam_centre_code, 'venue_code'=>$exam_venue_code, 'exam_date'=>$exam_date, 'session_time'=>$exam_time),'session_capacity');//session_capacity

        //echo '<br><br>seat_count : '.$seat_count[0]['session_capacity'];
        if(count($seat_count) > 0)
        {
          if($chk_capacity_type == 'make_proforma_invoice') { $pay_status_arr = array(1, 3);}
          else { $pay_status_arr = array(1, 3, 2); }

          $this->db->where_in('me.pay_status',$pay_status_arr);
          $this->db->where_in('me.exam_code',array(1041,1042,1057));//As we are getting shared capacity for 1041,1042,1057
          $member_exam_Count = $this->master_model->getRecords('ncvet_member_exam me',array('me.is_deleted'=>'0',  'exam_centre_code'=>$exam_centre_code, 'exam_venue_code'=>$exam_venue_code, 'exam_date'=>$exam_date, 'exam_time'=>$exam_time, 'exam_period'=>$exam_period, 'me.member_exam_id !='=>$member_exam_id)); //'exam_code'=>$exam_code, 'exam_period'=>$exam_period, , 
          //echo '<br>'; _pq();
          
          $total_session_capacity = $seat_count[0]['session_capacity'];
          $total_applied_count = intval(count($member_exam_Count)); // + intval($regular_admit_card_Count))
          
          //echo '<br>total_applied_count : '.$total_applied_count; 

          if($chk_capacity_type == 'make_payment')
          {
            if($total_session_capacity >= $total_applied_count)
            {
              $seat_capacity = 1;
            }
          }
          else
          {
            $seat_capacity = $total_session_capacity - $total_applied_count;
          }
          
          if($seat_capacity < 0) { $seat_capacity = 0; }
        }       
      }
      //echo '<br>seat_capacity : '.$seat_capacity;
      return $seat_capacity;      
    }

    

    function is_active_common($posted_arr=array(), $action_by='')
    {
      $cand_id = htmlspecialchars_decode($posted_arr['cand_id']);
      $status = htmlspecialchars_decode($posted_arr['status']);

      $cand_data = $this->master_model->getRecords('ncvet_candidates', array('candidate_id' => $cand_id), 'candidate_id, is_active');
      
      if(count($cand_data) == 0) // check whether record exist or not
      {
        return "error"; 
      }
      else
      {
        $is_active = '';
        $new_hold_release_text = '';
        if($status == "true")
        {
          $is_active = 3;
          $new_hold_release_text = 'release';
        }
        else if($status == "false")
        {
          $is_active = 2;
          $new_hold_release_text = 'manual hold';
        }

        if($is_active == $cand_data[0]['is_active']) { return "error";  }
        else
        {			
          $posted_arr = json_encode($posted_arr);
          $dispName = $this->getLoggedInUserDetails($this->session->userdata('NCVET_LOGIN_ID'), $action_by);

          $up_data['is_active'] = $is_active;
          $up_data['updated_on'] = date("Y-m-d H:i:s");
          $up_data['updated_by'] = $this->session->userdata('NCVET_LOGIN_ID');            
          $this->master_model->updateRecord('ncvet_candidates', $up_data, array('candidate_id'=>$cand_id));
                        
          $this->insert_common_log($action_by.' : Candidate status Updated', 'ncvet_candidates', $this->db->last_query(), $cand_id,'candidate_action','The candidate has successfully mark as '.$new_hold_release_text.' by the '.$action_by.' '.$dispName['disp_name'], $posted_arr);
                          
          return "success";
        }
      }
    }

    
    //START : SEND TRANSACTION DETAILS EMAIL & SMS FOR INDIVIDUAL & CSC TRANSACTIONS
    function send_transaction_details_email_sms($pt_id='')
    {
      $this->db->join('ncvet_member_exam me', 'FIND_IN_SET(me.member_exam_id, pt.exam_ids)', 'INNER');
      $this->db->join('ncvet_candidates cand', 'cand.candidate_id = me.candidate_id', 'INNER');
      $this->db->join('state_master state', 'state.state_code = cand.state', 'LEFT');
      $this->db->join('city_master city', 'city.id = cand.city', 'LEFT');
      
      $this->db->where_in('pt.status', array(0,1));
      $payment_data = $this->master_model->getRecords('ncvet_payment_transaction pt', array('pt.id'=>$pt_id, 'pt.payment_mode !='=>'Bulk'), 'pt.id, pt.agency_id, pt.centre_id, pt.exam_ids, pt.exam_code, pt.exam_period, pt.gateway, pt.amount, pt.date, pt.approve_reject_date, pt.transaction_no, pt.UTR_no, pt.pay_count, pt.bankcode, pt.paymode, pt.receipt_no, pt.description, pt.transaction_details, pt.payment_mode, pt.status, cand.salutation, cand.first_name, cand.middle_name, cand.last_name, cand.mobile_no, cand.email_id, cand.regnumber, cand.address1, cand.address2, cand.address3, cand.address4, (SELECT CONCAT(description, " (", exam_code, ")") FROM ncvet_exam_master WHERE exam_delete = "0" AND exam_code = pt.exam_code LIMIT 1 ORDER BY id DESC) AS ExamName, (SELECT medium_description FROM ncvet_exam_medium_master WHERE medium_delete = "0" AND exam_code = pt.exam_code AND exam_period = pt.exam_period AND medium_code = me.exam_medium LIMIT 1 ORDER BY id DESC) AS MediumName, me.member_exam_id, me.exam_centre_code, IF(me.exam_mode = "ON", "Online", "Offline") AS exam_mode, me.exam_date, (SELECT centre_name FROM ncvet_exam_centre_master WHERE centre_delete = "0" AND exam_name = pt.exam_code AND exam_period = pt.exam_period AND centre_code = me.exam_centre_code LIMIT 1 ORDER BY id DESC) AS CentreName, state.state_name, city.city_name');
      
      if(count($payment_data) > 0)
      {
        $emailer_name = $transaction_status_msg = '';
        if($payment_data[0]['status'] == '1') { $emailer_name = 'NCVET_TRANSACTION_SUCCESS'; $transaction_status_msg = 'Transaction Successful'; }
        else if($payment_data[0]['status'] == '0') { $emailer_name = 'NCVET_TRANSACTION_FAIL'; $transaction_status_msg = 'Transaction Fail'; }
        
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
            $mail_arg['bcc_email'] = 'iibfteam@esds.co.in';//sagar.matale@esds.co.in,anil.s@esds.co.in
            $mail_arg['is_header_footer_required'] = '0';
            $mail_arg['view_flag'] = '0';

            $attachment_arr = array();
            if($payment_data[0]['status'] == '1')
            {
              $exam_invoice_data = $this->master_model->getRecords('exam_invoice ei', array('ei.exam_code'=>$payment_res['exam_code'], 'ei.exam_period'=>$payment_res['exam_period'], 'ei.pay_txn_id'=>$pt_id, 'ei.receipt_no'=>$payment_res['receipt_no'], 'ei.transaction_no'=>$payment_res['transaction_no'], 'ei.invoice_no !='=>'', 'ei.invoice_image !='=>'', 'ei.app_type'=>'BC'), 'ei.invoice_id, ei.invoice_no, ei.invoice_image', array('ei.invoice_id'=>'DESC'));
              if(count($exam_invoice_data) > 0)
              {
                $attachment_arr[] = './uploads/ncvet/ncvet_examinvoice/user/'.$exam_invoice_data[0]['invoice_image'];
              }

              if(in_array($payment_res['exam_code'], array(1039,1040,1041,1042,1057)))
              {
                $admit_card_data = $this->master_model->getRecords('ncvet_admit_card_details ac', array('ac.exm_cd'=>$payment_res['exam_code'], 'ac.exm_prd'=>$payment_res['exam_period'], 'ac.pt_id'=>$pt_id, 'ac.remark'=>'1', 'ac.mem_exam_id'=>$payment_res['member_exam_id']), 'ac.admitcard_id', array('ac.admitcard_id'=>'DESC'));
                if(count($admit_card_data) > 0)
                {
                  $attachment_arr[] = $this->Iibf_ncvet_model->download_admit_card_pdf_single(url_encode($admit_card_data[0]['admitcard_id']), 'save');              
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
            $mail_content = str_replace("#EXAM_DATE_FULL#", date("d-M-Y", strtotime($payment_res['exam_date'])), $mail_content);
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
            $this->ncvet_send_mail_common($mail_arg);
          } 
        } 
      }
    }//END : SEND TRANSACTION DETAILS EMAIL & SMS FOR INDIVIDUAL & CSC TRANSACTIONS

   
    function download_admit_card_pdf_single($enc_admitcard_id= '0', $type='') //$type = 'download' / 'save'
    {
      $admitcard_id = 0;
      if($enc_admitcard_id != '0') { $admitcard_id = url_decode($enc_admitcard_id); }
      
      $data = array();  

      $this->db->join('ncvet_payment_transaction pt', 'pt.id = ac.pt_id', 'INNER');
      $this->db->join('ncvet_candidates bc', 'bc.regnumber = ac.mem_mem_no', 'INNER');
      $data['admit_card_data'] = $admit_card_data = $this->master_model->getRecords('ncvet_admit_card_details ac', array('ac.admitcard_id' => $admitcard_id), '
      admitcard_id, ac.pt_id, ac.mem_exam_id, ac.batchId, ac.institute_code, ac.centre_code, ac.mem_mem_no, ac.mam_nam_1, ac.mem_adr_1, ac.mem_adr_2, ac.mem_adr_3, ac.mem_adr_4, ac.mem_adr_5, ac.mem_adr_6, ac.mem_pin_cd, ac.exm_cd, ac.exm_prd, ac.mode, ac.pwd, ac.m_1, ac.vendor_code, ac.created_on, ac.venueid, ac.venueadd1, ac.venueadd2, ac.venueadd3, ac.venueadd4, ac.venueadd5, ac.venpin, ac.insname, ac.venue_name, ac.seat_identification, ac.sub_dsc, ac.exam_date, ac.time, 
      pt.transaction_no, pt.UTR_no, pt.payment_mode, bc.dob', array("admitcard_id"=>"DESC")); 
      
      if(count($admit_card_data) > 0)
      {        
        $data['exam_result'] = $this->master_model->getRecords('ncvet_exam_master', array('exam_code'=>$admit_card_data[0]['exm_cd'], 'exam_delete'=>'0'), 'description, exam_type, exam_code','','0','1');
        
        //$this->Iibf_ncvet_model->insert_common_log('IIBF BCBF : Agency Generate admit card in 2', 'ncvet_admit_card_details', $this->db->last_query(), $this->login_agency_or_centre_id,'agency_action','The agency Generate admit card in 2 ', json_encode($subject_result));
        
        $directory_name = "./uploads/ncvet/ncvet_admitcard/".date('Ymd');
        create_directories($directory_name);

        $attchpath_admitcard = $this->load->view('ncvet/agency/admitcardpdf_attach', $data, true);
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

   
    /************************ START : DASHBOARD COUNT QUERIES *******************************/
    //START : THIS FUNCTION IS USED TO GET THE ALL INSPECTION COUNT AS PER INSPECTOR ID, BATCH ID
   

    //START : THIS FUNCTION IS USED TO GET THE TOTAL NCVET CANDIDATE COUNT
    function get_total_candidate_data()
    {
      $total_candidate_cnt = $total_kyc_completed_candidate_cnt = $total_training_completed_candidate_cnt = $total_exam_applied_candidate_cnt = 0;

      $total_bfsi_candidate_cnt = $total_kyc_completed_bfsi_candidate_cnt = $total_training_completed_bfsi_candidate_cnt = $total_exam_applied_bfsi_candidate_cnt = 0;
   
      $this->db->where('cand.regnumber !=', '');        
      $candidate_data = $this->master_model->getRecords('ncvet_candidates cand', array('cand.is_deleted'=>'0'), 'cand.candidate_id, cand.regnumber, cand.is_active, cand.kyc_status');
      
      if(count($candidate_data) > 0)
      {
        $total_candidate_cnt = count($candidate_data);
        foreach($candidate_data as $res)
        {
          if($res['kyc_status'] == '2') 
          {
            $total_kyc_completed_candidate_cnt++;
          }
        }
      }

      $this->db->where('cand.regnumber !=', '');
      $this->db->where('cand.reference', 'BFSI');        
      $bfsi_candidate_data = $this->master_model->getRecords('ncvet_candidates cand', array('cand.is_deleted'=>'0'), 'cand.candidate_id, cand.regnumber, cand.is_active, cand.kyc_status');
      
      if(count($bfsi_candidate_data) > 0)
      {
        $total_bfsi_candidate_cnt = count($bfsi_candidate_data);
        foreach($bfsi_candidate_data as $res_bfsi)
        {
          if($res_bfsi['kyc_status'] == '2') 
          {
            $total_kyc_completed_bfsi_candidate_cnt++;
          }
        }
      }


      $result['total_candidate_cnt']               = $total_candidate_cnt;
      $result['total_kyc_completed_candidate_cnt'] = $total_kyc_completed_candidate_cnt;
      // $result['total_hold_candidate_cnt'] = $total_hold_candidate_cnt;
      // $result['total_exam_applied_candidate_cnt'] = $total_exam_applied_candidate_cnt;

      $result['total_bfsi_candidate_cnt']               = $total_bfsi_candidate_cnt;
      $result['total_kyc_completed_bfsi_candidate_cnt'] = $total_kyc_completed_bfsi_candidate_cnt;
      // $result['total_hold_bfsi_candidate_cnt'] = $total_hold_bfsi_candidate_cnt;
      // $result['total_exam_applied_bfsi_candidate_cnt'] = $total_exam_applied_bfsi_candidate_cnt;

      return $result;
    }//END : THIS FUNCTION IS USED TO GET THE TOTAL CANDIDATE DATA AS PER AGENCY ID

    /************************ END : DASHBOARD COUNT QUERIES *******************************/

    //START : THIS FUNCTION IS USED TO GET THE TOTAL EXAM REGISTRATION DATA
    function get_total_exam_registraion_data()
    {
      $total_exam_registraion_cnt = $total_basic_exam_reg_cnt = $total_advanced_exam_reg_cnt = $total_basic_re_attempt_exam_reg_cnt = $total_advanced_re_attempt_exam_reg_cnt = 0; //FOR ALL REGISTRATION COUNT

      $today_exam_registraion_cnt = $today_basic_exam_reg_cnt = $today_advanced_exam_reg_cnt = $today_basic_re_attempt_exam_reg_cnt = $today_advanced_re_attempt_exam_reg_cnt = 0; //FOR TODAYS REGISTRATION COUNT
      
      $this->db->where_in('me.exam_code', array(1037,1038,1039,1040,1041,1042,1057));
      $this->db->join('ncvet_candidates bc', 'bc.candidate_id = me.candidate_id', 'INNER');
      $exam_registraion_data = $this->master_model->getRecords('ncvet_member_exam me', array('me.is_deleted'=>'0','me.pay_status'=>'1'), 'me.candidate_id, me.exam_code, DATE(me.created_on) AS created_on, bc.re_attempt, me.pay_status');
      //_pq(1);

      $basic_exam_code_arr = array(1038,1040,1042);
      $advanced_exam_code_arr = array(1037,1039,1041,1057);
      
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

      $this->db->join('ncvet_agency_centre_batch acb', 'acb.batch_id = bc.batch_id', 'INNER'); 
      $exam_registraion_data = $this->master_model->getRecords('ncvet_candidates bc', array('bc.is_deleted'=>'0', 'acb.batch_status'=>'3', 'acb.is_deleted'=>'0'), 'bc.re_attempt, acb.batch_type, bc.parent_id, bc.parent_table_name, bc.is_active, acb.batch_start_date, acb.batch_end_date');

      $todays_date = date('Y-m-d');
        $total_registraion_for_training_cnt = count($exam_registraion_data);
      if($total_registraion_for_training_cnt > 0)
      {        
        foreach($exam_registraion_data as $res)
        {
          if($res['batch_type'] == '1') 
          { 
            $total_basic_reg_for_training_cnt++; 
            if(/* $res['re_attempt'] > 1 &&  */$res['parent_table_name'] == 'ncvet_candidates') { $total_re_attempt_basic_reg_for_training_cnt++; } 
          }
          else if($res['batch_type'] == '2') 
          { 
            $total_advance_reg_for_training_cnt++; 
            if(/* $res['re_attempt'] > 1 &&  */$res['parent_table_name'] == 'ncvet_candidates') { $total_re_attempt_advanced_reg_for_training_cnt++; } 
          }          
          
          //START : ELIGIBLE FOR EXAMINATION BUT NOT APPLIED
          $validity_start_date = date('Y-m-d', strtotime("+".$this->buffer_days_after_training_end_date."days", strtotime($res['batch_end_date'])));
          $validity_end_date = date('Y-m-d', strtotime("+".$this->buffer_days_after_candidate_add_date."days", strtotime($res['batch_end_date'])));

          //todays date is greater than batch end date + buffer_days_after_training_end_date
          //todays date is less than batch end date + buffer_days_after_candidate_add_date
          //candidate is released
          //re-attempt is zero
          if($validity_start_date < $todays_date && $todays_date <= $validity_end_date && $res['re_attempt'] == '0' && $res['is_active'] == '1')
          {
            $total_eligible_for_exam++;
          }
          //END : ELIGIBLE FOR EXAMINATION BUT NOT APPLIED

          //START : CANDIDATES REQUIRED TO RE-ENROLL FOR TRAINING
          if($res['re_attempt'] == '3' || $todays_date > $validity_end_date || ($res['is_active'] != '1' && $validity_start_date < $todays_date))
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
        $this->db->join('ncvet_member_exam me', 'me.batch_id = bc.batch_id AND me.candidate_id = bc.candidate_id', 'INNER'); //acb11.batch_end_date < CURDATE()
        $this->db->join('ncvet_agency_centre_batch acb', 'acb.batch_id = bc.batch_id', 'INNER'); 
        $this->db->group_by('bc.regnumber'); 
        $exam_registraion_data = $this->master_model->getRecords('ncvet_candidates bc', array('bc.is_deleted'=>'0', 'bc.batch_id'=>$batch_id, 'acb.batch_status'=>'3', 'acb.is_deleted'=>'0', 'DATE(me.exam_date) < ' => $todays_date), 'me.exam_code, me.exam_period, bc.regnumber, bc.candidate_id, bc.re_attempt, acb.batch_type, bc.parent_id, bc.parent_table_name, bc.is_active, acb.batch_start_date, acb.batch_end_date, MAX(DATE(me.exam_date))');

        if($exam_registraion_data && count($exam_registraion_data) > 0){
          foreach($exam_registraion_data as $res){
            if($res["exam_code"] != "" && $res["exam_period"] != "" && $res["regnumber"] != ""){
              

              $response = $this->get_ncvet_result_related_api('getResult', $res["exam_code"], $res["exam_period"], '1', $res["regnumber"]);
              //$response = $this->get_ncvet_result_related_api('getResult', '21', '122', '1', '100019014');
              //$response = $this->get_ncvet_result_related_api('getResult', '60', '221', '1', '510028070');
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
 
   
    function get_candidate_exam_application_history_data_common($training_id_or_regnumber='0')
    {
      $this->db->limit(1);
      $this->db->where(" (cand.training_id = '" . $training_id_or_regnumber . "' or cand.regnumber = '" . $training_id_or_regnumber . "') ");
      $result['candidate_data'] = $candidate_data = $this->master_model->getRecords('ncvet_candidates cand', array('cand.is_deleted' => '0'), "cand.candidate_id, cand.agency_id, cand.regnumber, cand.training_id, cand.salutation, cand.first_name, cand.middle_name, cand.last_name, cand.dob, IF(cand.gender=1,'Male','Female') AS DispGender, cand.mobile_no, cand.alt_mobile_no, cand.email_id, cand.alt_email_id, IF(cand.is_active=1,'Auto Hold', IF(cand.is_active=2,'Manual Hold','Release')) AS is_active, cand.re_attempt, cand.created_on, cand.id_proof_file, cand.qualification_certificate_file, cand.candidate_photo, cand.candidate_sign", array('cand.candidate_id'=>'DESC'));
      
      if(count($candidate_data) > 0)
      {
        $this->db->where('me.candidate_id',$candidate_data[0]['candidate_id']);
        $this->db->join('ncvet_admit_card_details ac', 'ac.mem_mem_no = me.regnumber AND ac.exm_cd = me.exam_code AND ac.mem_exam_id = me.member_exam_id AND ac.remark = 1', 'LEFT');
        $this->db->join('ncvet_payment_transaction pt', 'pt.id = me.pt_id', 'LEFT');
        $result['exam_application_data'] = $exam_application_data = $this->master_model->getRecords('ncvet_member_exam me', array('me.is_deleted' => '0'), "me.*, ac.admitcard_id, pt.receipt_no, pt.description, pt.transaction_details", array('me.member_exam_id'=>'DESC'));
        //_pq(1);
      }

      return $result;
    }

    function get_exam_details_common_data(){

        $form_action = trim($this->security->xss_clean($this->input->post('form_action')));

        $table = 'ncvet_candidates cand'; //
         
        $column_order = array('cand.candidate_id', 'am1.agency_name', 'CONCAT(cm.centre_name," (",cm1.city_name,")") AS DispCentreName', 'acb.batch_code', 'cand.training_id', 'cand.regnumber', 'CONCAT(cand.salutation, " ", cand.first_name, IF(cand.middle_name != "", CONCAT(" ", cand.middle_name), ""), IF(cand.last_name != "", CONCAT(" ", cand.last_name), "")) AS DispName', 'cand.dob', 'cand.mobile_no', 'cand.email_id', '" " AS exam_date1', '" " AS exam_date2', '" " AS exam_date3', '" " AS result1', '" " AS result2', '" " AS result3', 'IF(cand2.candidate_id != "", "Y", "N") AS re_enrollment', 'cand2.training_id AS new_training_id', 'am2.agency_name AS new_agency_name', 'cm2.centre_name AS new_centre_name', 'btch2.batch_code AS new_batch_code', '" " AS exam_date4', '" " AS exam_date5', '" " AS exam_date6', '" " AS result4', '" " AS result5', '" " AS result6', 'GREATEST(290 - (DATEDIFF(CURDATE(), acb.batch_end_date )) ,0) AS validity_remaining', 'cand.parent_table_name', 'cand2.candidate_id AS new_candidate_id', 'cand2.regnumber AS new_regnumber', 'cand.parent_id'); //SET COLUMNS FOR SORT
        
        $column_search = array('am1.agency_name', 'CONCAT(cm.centre_name," (",cm1.city_name,")")', 'acb.batch_code', 'IF(acb.batch_type=1, "Basic", IF(acb.batch_type=2, "Advance", ""))', 'cand.training_id', 'cand.regnumber', 'CONCAT(cand.salutation, " ", cand.first_name, IF(cand.middle_name != "", CONCAT(" ", cand.middle_name), ""), IF(cand.last_name != "", CONCAT(" ", cand.last_name), ""))', 'cand.dob', 'cand.mobile_no', 'cand.email_id', 'IF(cand2.candidate_id != "", "Y", "N")', 'GREATEST(290 - (DATEDIFF(CURDATE(), acb.batch_end_date )) ,0)', 'cand2.training_id', 'am2.agency_name', 'cm2.centre_name', 'btch2.batch_code', 'cand2.regnumber', 'cand.parent_table_name', 'cand2.candidate_id', 'cand.parent_id'); //SET COLUMN FOR SEARCH
        $order = array('cand.candidate_id' => 'DESC'); // DEFAULT ORDER    
         
        $WhereForTotal = "WHERE acb.is_deleted = 0 AND cand.re_attempt > 0 AND acb.batch_end_date < '".date("Y-m-d")."'"; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
        $Where = "WHERE acb.is_deleted = 0 AND cand.re_attempt > 0 AND acb.batch_end_date < '".date("Y-m-d")."'";

        $login_user_id = $this->session->userdata('NCVET_LOGIN_ID');
        $login_user_type = $this->session->userdata('NCVET_USER_TYPE');
        if($login_user_type == 'agency') //CHECK IN AGENCY
        { 
          $Where .= " AND acb.agency_id = '".$login_user_id."'"; 
          $WhereForTotal .= " AND acb.agency_id = '".$login_user_id."'"; 
        } 
        
        if($_POST['search']['value']) // DATATABLE SEARCH
        {
          $Where .= " AND (";
          for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['search']['value']) )."%' ESCAPE '!' OR "; }
          $Where = substr_replace( $Where, "", -3 );
          $Where .= ')';
        }  

        if ($form_action == 'export')
        {
          if(isset($_POST['tbl_search_value']) && $_POST['tbl_search_value']) // DATATABLE SEARCH
          {
            $Where .= " AND (";
            for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['tbl_search_value']) )."%' ESCAPE '!' OR "; }
            $Where = substr_replace( $Where, "", -3 );
            $Where .= ')';
          }
        }
        
        //CUSTOM SEARCH
        $s_agency = trim($this->security->xss_clean($this->input->post('s_agency')));
        if($s_agency != "") { $Where .= " AND acb.agency_id = '".$s_agency."'"; } 

        $s_centre = trim($this->security->xss_clean($this->input->post('s_centre')));
        if($s_centre != "") { $Where .= " AND acb.centre_id = '".$s_centre."'"; } 

        $s_batch_type = trim($this->security->xss_clean($this->input->post('s_batch_type')));
        if ($s_batch_type != "")
        {
          $Where .= " AND acb.batch_type = '" . $s_batch_type . "'";
        }
        
        $s_from_date = trim($this->security->xss_clean($this->input->post('s_from_date')));
        $s_to_date = trim($this->security->xss_clean($this->input->post('s_to_date')));
        
        if($s_from_date != "" && $s_to_date != "")
        { 
          $Where .= " AND (acb.batch_start_date >= '".$s_from_date."' OR acb.batch_end_date >= '".$s_from_date."') AND (acb.batch_start_date <= '".$s_to_date."' OR acb.batch_end_date <= '".$s_to_date."')"; 
        }else if($s_from_date != "") { $Where .= " AND (acb.batch_start_date >= '".$s_from_date."' OR acb.batch_end_date >= '".$s_from_date."')"; 
        }else if($s_to_date != "") { $Where .= " AND (acb.batch_start_date <= '".$s_to_date."' OR acb.batch_end_date <= '".$s_to_date."')"; } 

        $s_batch_code = trim($this->security->xss_clean($this->input->post('s_batch_code')));
        if($s_batch_code != "") { $Where .= " AND acb.batch_code LIKE '%".custom_safe_string($s_batch_code)."%'"; } //ncvet/ncvet_helper.php
        
        /*$s_batch_status = trim($this->security->xss_clean($this->input->post('s_batch_status')));
        if($s_batch_status != "") { $Where .= " AND acb.batch_status = '".$s_batch_status."'"; }*/      

        $Order = ""; //DATATABLE SORT
        if(isset($_POST['order'])) { $explode_arr = explode(" AS ",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
        else if(isset($order)) { $Order = "ORDER BY ".key($order)." ".$order[key($order)]; }
        
        $Limit = ""; 
        if ($_POST['length'] != '-1' && $form_action != 'export') 
        { 
          $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); 
        } // DATATABLE LIMIT  
        

        $join_qry = " INNER JOIN ncvet_agency_master am1 ON am1.agency_id = cand.agency_id"; 
        $join_qry .= " INNER JOIN ncvet_centre_master cm ON cm.centre_id = cand.centre_id";
        $join_qry .= " INNER JOIN ncvet_agency_centre_batch acb ON acb.batch_id = cand.batch_id";
        $join_qry .= " LEFT JOIN ncvet_candidates cand2 ON cand2.parent_id = cand.candidate_id AND cand2.parent_table_name = 'ncvet_candidates'";
        $join_qry .= " LEFT JOIN ncvet_agency_master am2 ON am2.agency_id = cand2.agency_id";
        $join_qry .= " LEFT JOIN ncvet_centre_master cm2 ON cm2.centre_id = cand2.centre_id";
        $join_qry .= " LEFT JOIN ncvet_agency_centre_batch btch2 ON btch2.batch_id = cand2.batch_id";
        $join_qry .= " LEFT JOIN city_master cm1 ON cm1.id = cm.centre_city"; 

        /*$Where .= $GroupBy;
        $WhereForTotal .= $GroupBy;*/ 
              
        $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
        $Result = $this->db->query($print_query);  
        $Rows = $Result->result_array();
        
        $TotalResult = $this->Iibf_ncvet_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$WhereForTotal);
        $FilteredResult = $this->Iibf_ncvet_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);
        
        $data = array();
        $no = $_POST['start'];    
        
        if ($form_action == 'export')
        {
          // Excel file name for download 
          $fileName = "Exam_Details_".date('Y-m-d').".xls";  
          // Column names 
          $fields = array('Sr. No.', 'Agency Name', 'Centre Name', 'Batch Code', 'Training Id', 'Registration No.', 'Candidate Full Name', 'DOB', 'Mobile', 'Email', 'Exam Date1', 'Exam Date2', 'Exam Date3', 'Result1', 'Result2', 'Result3', 'Re-enrollment, if any (Y/N)', 'New Training ID', 'Agency Name', 'Centre Name', 'Batch Code', 'Exam Date4', 'Exam Date5', 'Exam Date6', 'Result4', 'Result5', 'Result6', 'Validity Remaining (in days)');  
          // Display column names as first row 
          $excelData = implode("\t", array_values($fields)) . "\n";  
        }

        foreach ($Rows as $Res) 
        {
          $no++;
          $row = array();

          $this->db->join('bcbf_marks bm', 'bm.exam_id = me.exam_code AND bm.exam_period = me.exam_period AND bm.regnumber = "'.$Res['regnumber'].'"', 'LEFT');
          $member_exam_data = $this->master_model->getRecords('ncvet_member_exam me', array('me.candidate_id' => $Res['candidate_id'], 'me.pay_status' => '1'), 'me.candidate_id, me.exam_code, me.exam_period, me.pay_status, me.exam_date, bm.status AS result',array('me.member_exam_id','ASC'));
          //echo $this->db->last_query();
          $exam_date1 = $exam_date2 = $exam_date3 = $result1 = $result2 = $result3 = '';        
          if(count($member_exam_data) > 0) 
          { 
            $exam_date1 = isset($member_exam_data[0]['exam_date']) ? $member_exam_data[0]['exam_date'] : ''; 
            $exam_date2 = isset($member_exam_data[1]['exam_date']) ? $member_exam_data[1]['exam_date'] : ''; 
            $exam_date3 = isset($member_exam_data[2]['exam_date']) ? $member_exam_data[2]['exam_date'] : '';  

            $result1 = isset($member_exam_data[0]['result']) ? $member_exam_data[0]['result'] : '';   
            $result2 = isset($member_exam_data[1]['result']) ? $member_exam_data[1]['result'] : '';   
            $result3 = isset($member_exam_data[2]['result']) ? $member_exam_data[2]['result'] : '';   
          }
          //$re_enrollment = 'N';
          //if($Res['new_candidate_id'] != ""){ $re_enrollment = 'Y'; }
          

          $this->db->join('bcbf_marks bm', 'bm.exam_id = me.exam_code AND bm.exam_period = me.exam_period AND bm.regnumber = "'.$Res['new_regnumber'].'"', 'LEFT');
          $member_exam_data2 = $this->master_model->getRecords('ncvet_member_exam me', array('me.candidate_id' => $Res['new_candidate_id'], 'me.pay_status' => '1'), 'me.candidate_id, me.exam_code, me.exam_period, me.pay_status, me.exam_date, bm.status AS result',array('me.member_exam_id','ASC'));
          //echo $this->db->last_query();
          $exam_date4 = $exam_date5 = $exam_date6 = $result4 = $result5 = $result6 = '';        
          if(count($member_exam_data2) > 0) 
          { 
            $exam_date4 = isset($member_exam_data2[0]['exam_date']) ? $member_exam_data2[0]['exam_date'] : ''; 
            $exam_date5 = isset($member_exam_data2[1]['exam_date']) ? $member_exam_data2[1]['exam_date'] : ''; 
            $exam_date6 = isset($member_exam_data2[2]['exam_date']) ? $member_exam_data2[2]['exam_date'] : '';  

            $result4 = isset($member_exam_data2[0]['result']) ? $member_exam_data2[0]['result'] : '';   
            $result5 = isset($member_exam_data2[1]['result']) ? $member_exam_data2[1]['result'] : '';   
            $result6 = isset($member_exam_data2[2]['result']) ? $member_exam_data2[2]['result'] : '';   
          }

          $row[] = $no;
          $row[] = $Res['agency_name'];
          $row[] = $Res['DispCentreName'];
          $row[] = $Res['batch_code'];
          $row[] = $Res['training_id'];
          $row[] = $Res['regnumber'];
          $row[] = $Res['DispName']; 
          $row[] = $Res['dob'];
          $row[] = $Res['mobile_no'];
          $row[] = $Res['email_id'];
          $row[] = $exam_date1;
          $row[] = $exam_date2;
          $row[] = $exam_date3;
          $row[] = $result1;
          $row[] = $result2;
          $row[] = $result3;
          $row[] = $Res['re_enrollment'];
          $row[] = $Res['new_training_id'];
          $row[] = $Res['new_agency_name'];
          $row[] = $Res['new_centre_name'];
          $row[] = $Res['new_batch_code'];
          $row[] = $exam_date4;
          $row[] = $exam_date5;
          $row[] = $exam_date6;
          $row[] = $result4;
          $row[] = $result5;
          $row[] = $result6;
          $row[] = $Res['validity_remaining'];
          
     
          if ($form_action == 'export')
          {
            array_walk($row, 'filterData');
            $excelData .= implode("\t", array_values($row)) . "\n";
          }

          $data[] = $row; 
        }     
        
        if ($form_action == 'export')
        {
          if (count($Rows) == '0')
          {
            $excelData .= 'No records found...' . "\n";
          }

          // Headers for download 
          header("Content-Type: application/vnd.ms-excel");
          header("Content-Disposition: attachment; filename=\"$fileName\"");
          // Render excel data 
          echo $excelData;
          exit;
        }

        $output = array(
        "draw" => $_POST['draw'],
        "recordsTotal" => $TotalResult, //All result count
        "recordsFiltered" => $FilteredResult, //Disp result count
        "Query" => $print_query,
        "data" => $data,
        );
        //output to json format
        return json_encode($output);
    }


    function get_eligible_candidate_for_examination_common_data(){

        $form_action = trim($this->security->xss_clean($this->input->post('form_action')));

        $table = 'ncvet_candidates cand'; //
         
        $column_order = array('cand.candidate_id', 'cand.regnumber', 'IF(cand.reference="BFSI","BFSI SSC","IIBF website") AS reference', 'cand.created_on',  'CONCAT(cand.first_name, IF(cand.middle_name != "", CONCAT(" ", cand.middle_name), ""), IF(cand.last_name != "", CONCAT(" ", cand.last_name), "")) AS DispName', 'IF(cand.gender=1,"Male","Female") AS DispGender', 'cand.dob', 'cand.dob', 'cand.mobile_no', 'cand.email_id', 'CASE cand.qualification WHEN 1 THEN "12th Pass with 1.5 years of experience in BFSI (not pursuing graduation / post graduation)" WHEN 2 THEN "Graduate not pursuing Post Graduation" WHEN 3 THEN "Pursuing Graduation" WHEN 4 THEN "Pursuing Postgraduation" END AS qualification', 'cand.university', 'cand.collage', 'sm.state_name AS eligibility_state', 'cand.id_proof_number', 'cand.aadhar_no', 'IF(cand.benchmark_disability="Y","Yes","No") AS benchmark_disability', 'CASE cand.kyc_status WHEN 0 THEN "Pending" WHEN 1 THEN "In Progress" WHEN 2 THEN "Approved" WHEN 3 THEN "Rejected" END AS kyc_status','CASE cand.benchmark_kyc_status WHEN 0 THEN "Pending" WHEN 1 THEN "In Progress" WHEN 2 THEN "Approved" WHEN 3 THEN "Rejected" END AS benchmark_kyc_status', 'IF(cand.is_active=1,"Active","Deactive") AS status'); //SET COLUMNS FOR SORT
        
        $column_search = array('cand.regnumber','IF(cand.reference="BFSI","BFSI SSC","IIBF website")' ,'cand.created_on', 'CONCAT(cand.first_name, IF(cand.middle_name != "", CONCAT(" ", cand.middle_name), ""), IF(cand.last_name != "", CONCAT(" ", cand.last_name), ""))', 'IF(cand.gender=1,"Male","Female")', 'cand.dob', 'cand.dob', 'cand.mobile_no', 'cand.email_id', 'CASE cand.qualification WHEN 1 THEN "12th Pass with 1.5 years of experience in BFSI (not pursuing graduation / post graduation)" WHEN 2 THEN "Graduate not pursuing Post Graduation" WHEN 3 THEN "Pursuing Graduation" WHEN 4 THEN "Pursuing Postgraduation" END', 'cand.university', 'cand.collage', 'sm.state_name', 'cand.id_proof_number', 'cand.aadhar_no', 'IF(cand.benchmark_disability="Y","Yes","No")'); //SET COLUMN FOR SEARCH (NOTE: is_active, kyc_status, and benchmark_kyc_status are no longer in this array)

        $order = array('cand.candidate_id' => 'DESC'); // DEFAULT ORDER    
         
        $WhereForTotal = "WHERE cand.is_deleted = '0' AND cand.regnumber != '' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
        $Where = "WHERE cand.is_deleted = '0' AND cand.regnumber != '' ";

        $login_user_id   = $this->session->userdata('NCVET_LOGIN_ID');
        $login_user_type = $this->session->userdata('NCVET_USER_TYPE');
        
        if($_POST['search']['value']) // DATATABLE SEARCH
        {
          $Where .= " AND (";
          for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['search']['value']) )."%' ESCAPE '!' OR "; }
          $Where = substr_replace( $Where, "", -3 );
          $Where .= ')';
        }  

        if ($form_action == 'export')
        {
          if(isset($_POST['tbl_search_value']) && $_POST['tbl_search_value']) // DATATABLE SEARCH
          {
            $Where .= " AND (";
            for($i=0; $i<count($column_search); $i++) 
            { 
              $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['tbl_search_value']) )."%' ESCAPE '!' OR "; 
            }

            // Custom search for `kyc_status` and `benchmark_kyc_status`
            $kyc_search_value = '';
            if (strpos($search_value, 'pend') !== false) {
                $kyc_search_value = 0;
            } else if (strpos($search_value, 'in pro') !== false) {
                $kyc_search_value = 1;
            } else if (strpos($search_value, 'appr') !== false) {
                $kyc_search_value = 2;
            } else if (strpos($search_value, 'rej') !== false) {
                $kyc_search_value = 3;
            }
            
            if ($kyc_search_value !== '') {
                $Where .= " cand.kyc_status = '" . $kyc_search_value . "' OR ";
                $Where .= " cand.benchmark_kyc_status = '" . $kyc_search_value . "' OR ";
            }

            // Custom search for `is_active`
            $is_active_search_value = '';
            if (strpos($search_value, 'deac') !== false) {
                $is_active_search_value = '0';
            } else if (strpos($search_value, 'act') !== false) {
                $is_active_search_value = '1';
            }

            if ($is_active_search_value !== '') {
                $Where .= " cand.is_active = '" . $is_active_search_value . "' OR ";
            }

            $Where = substr_replace( $Where, "", -3 );
            $Where .= ')';
          }
        }
        
        //CUSTOM SEARCH
        $s_reference = trim($this->security->xss_clean($this->input->post('s_reference')));
        if ($s_reference != "")
        {   
            if ( $s_reference == 'BFSI' ) {
                $Where .= " AND cand.reference = '" . $s_reference."'";   
            } elseif ( $s_reference == 'REGULAR' ) {
                $Where .= " AND (cand.reference != 'BFSI' OR cand.reference = '' OR cand.reference IS NULL)";
            } 
        }

        $s_gender = trim($this->security->xss_clean($this->input->post('s_gender')));
        if ($s_gender != "")
        {
            $Where .= " AND cand.gender = " .$s_gender;
        }

        $s_qualification = trim($this->security->xss_clean($this->input->post('s_qualification')));
        if ($s_qualification != "")
        {
            $Where .= " AND cand.qualification = " .$s_qualification;
        }

        $s_qualification_state = trim($this->security->xss_clean($this->input->post('s_qualification_state')));
        if ($s_qualification_state != "")
        {
            $Where .= " AND cand.qualification_state = '" .$s_qualification_state."' ";
        }

        $s_benchmark_disability = trim($this->security->xss_clean($this->input->post('s_benchmark_disability')));
        if ($s_benchmark_disability != "")
        {
            $Where .= " AND cand.benchmark_disability = '" .$s_benchmark_disability."' ";
        }

        $s_kyc_status = trim($this->security->xss_clean($this->input->post('s_kyc_status')));
        if ($s_kyc_status != "")
        {
            $Where .= " AND cand.kyc_status = '" .$s_kyc_status."' ";
        }

        $s_benchmark_kyc_status = trim($this->security->xss_clean($this->input->post('s_benchmark_kyc_status')));
        if ($s_benchmark_kyc_status != "")
        {
            $Where .= " AND cand.benchmark_kyc_status = '" .$s_benchmark_kyc_status."' AND cand.benchmark_disability = 'Y' ";
        }

        $s_status = trim($this->security->xss_clean($this->input->post('s_status')));
        if ($s_status != "")
        {
            $Where .= " AND cand.is_active = '" . $s_status . "'";
        }

        $s_from_date = trim($this->security->xss_clean($this->input->post('s_from_date')));
        $s_to_date = trim($this->security->xss_clean($this->input->post('s_to_date')));
        
        if($s_from_date != "" && $s_to_date != "")
        { 
          $Where .= " AND cand.created_on >= '".$s_from_date."' AND cand.created_on <= '".$s_to_date."'"; 
        }

        $Order = ""; // DATATABLE SORT
        if ( $form_action != 'export' )
        {
          if(isset($_POST['order'])) { $explode_arr = explode(" AS ",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
          else if(isset($order)) { $Order = "ORDER BY ".key($order)." ".$order[key($order)]; }
        }  
        
        $Limit = ""; 
        if ($_POST['length'] != '-1' && $form_action != 'export') 
        { 
          $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); 
        } // DATATABLE LIMIT  
        

        // $data['state_master_data'] = $this->master_model->getRecords('state_master', array('state_delete' => '0', 'id !=' => '11','state_code'=>$form_data[0]['state']), 'id, state_code, state_no, exempt, state_name', array('state_name'=>'ASC'));

        // $data['pr_state_master_data'] = $this->master_model->getRecords('state_master', array('state_delete' => '0', 'id !=' => '11','state_code'=>$form_data[0]['state_pr']), 'id, state_code, state_no, exempt, state_name', array('state_name'=>'ASC')); 

        $data['state_college_data'] = $this->master_model->getRecords('state_master', array('state_delete' => '0', 'id !=' => '11','state_code'=>$form_data[0]['qualification_state']), 'id, state_code, state_no, exempt, state_name', array('state_name'=>'ASC'));

        $join_qry = " LEFT JOIN state_master sm ON sm.state_code = cand.qualification_state"; 
        // $join_qry .= " INNER JOIN ncvet_centre_master cm ON cm.centre_id = cand.centre_id";
        // $join_qry .= " INNER JOIN ncvet_agency_centre_batch acb ON acb.batch_id = cand.batch_id";
        // $join_qry .= " LEFT JOIN city_master cm1 ON cm1.id = cm.centre_city"; 

        $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY

        $Result = $this->db->query($print_query);  
        $Rows = $Result->result_array();
        
        $data = array();
        $no = $_POST['start'];    
        
        if ($form_action == 'export')
        {
          // Excel file name for download 
          $fileName = "Candidate_Enrollment_".date('Y-m-d').".xls";  
          // Column names 
          $fields = array('Sr. No.', 'Registration Number', 'Enrollment channel', 'Enrollment Date', 'Candidate Full Name', 'Gender', 'DOB', 'Age', 'Mobile Number', 'Email id', 'Eligibility', 'University Name', 'College Name', 'State of college/university (both pursuing & completed)', 'APAAR ID', 'Aadhar Number', 'Disability', 'KYC Status', 'Benchmark KYC Status', 'Status');  
          // Display column names as first row 
          $excelData = implode("\t", array_values($fields)) . "\n";  
        }
        else 
        {
          $TotalResult = $this->Ncvet_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$WhereForTotal);
          $FilteredResult = $this->Ncvet_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);
        }
        // echo $this->db->last_query(); exit;
        // echo "<pre>"; print_r($Rows); exit;
        
        foreach ($Rows as $Res) 
        {
          $no++;
          $row = array();
           
          $row[] = $no;
          // $row[] = $Res['training_id'];
          $row[] = $Res['regnumber'];
          $row[] = $Res['reference'];
          $row[] = date('Y-m-d',strtotime($Res['created_on']));
          $row[] = $Res['DispName'];
          $row[] = $Res['DispGender'];
          $row[] = $Res['dob'];
          $row[] = $this->calculateAge($Res['dob']);
          $row[] = $Res['mobile_no'];
          $row[] = $Res['email_id'];
          $row[] = $Res['qualification'];
          $row[] = $Res['university'];
          $row[] = $Res['collage'];
          $row[] = $Res['eligibility_state'] != '' ? $Res['eligibility_state'] : 'Not Available';
          $row[] = $Res['id_proof_number'];
          $row[] = $Res['aadhar_no'];
          $row[] = $Res['benchmark_disability'];
          $row[] = $Res['kyc_status'];
          $row[] = $Res['benchmark_disability'] == 'Yes' ? $Res['benchmark_kyc_status']:'Not Applicable';
          $row[] = $Res['status'];
          
          if ($form_action == 'export')
          {
            array_walk($row, 'filterData');
            $excelData .= implode("\t", array_values($row)) . "\n";
          }

          $data[] = $row; 
        }     
        
        if ($form_action == 'export')
        {
          if (count($Rows) == '0')
          {
            $excelData .= 'No records found...' . "\n";
          }

          // Headers for download 
          header("Content-Type: application/vnd.ms-excel");
          header("Content-Disposition: attachment; filename=\"$fileName\"");
          // Render excel data 
          echo $excelData;
          exit;
        }

        $output = array(
        "draw" => $_POST['draw'],
        "recordsTotal" => $TotalResult, //All result count
        "recordsFiltered" => $FilteredResult, //Disp result count
        "Query" => $print_query,
        "data" => $data,
        );
        //output to json format
        echo json_encode($output);      
    }

  /******** START : CALCULATE AGE ********/
  private function calculateAge($dob) {
    // Convert date of birth to DateTime object
    $birthDate = new DateTime($dob);
    $today = new DateTime('today');
    
    // Calculate age difference
    $age = $birthDate->diff($today)->y;
    
    return $age;
  }
  /******** END : CALCULATE AGE ********/
    
    function get_candidates_to_re_enroll_for_training_common_data(){
        
        $form_action = trim($this->security->xss_clean($this->input->post('form_action')));

        $table = 'ncvet_candidates cand'; //
        
        //, 'GREATEST((3 - cand.re_attempt) ,0) AS attempt_remaining', 'GREATEST(290 - (DATEDIFF(CURDATE(), acb.batch_end_date )) ,0) AS validity_remaining'

        $column_order = array('cand.candidate_id', 'am1.agency_name', 'CONCAT(cm.centre_name," (",cm1.city_name,")") AS DispCentreName', 'acb.batch_code', 'cand.training_id', 'cand.regnumber', 'CONCAT(cand.salutation, " ", cand.first_name, IF(cand.middle_name != "", CONCAT(" ", cand.middle_name), ""), IF(cand.last_name != "", CONCAT(" ", cand.last_name), "")) AS DispName', 'acb.batch_start_date', 'acb.batch_end_date', '"" AS exam_date1', '"" AS exam_date2', '"" AS exam_date3', '"" AS result1', '"" AS result2', '"" AS result3', 'cand.parent_id'); //SET COLUMNS FOR SORT
        
        $column_search = array('am1.agency_name', 'CONCAT(cm.centre_name," (",cm1.city_name,")")', 'acb.batch_code', 'cand.training_id', 'cand.regnumber', 'CONCAT(cand.salutation, " ", cand.first_name, IF(cand.middle_name != "", CONCAT(" ", cand.middle_name), ""), IF(cand.last_name != "", CONCAT(" ", cand.last_name), ""))', 'acb.batch_start_date', 'acb.batch_end_date'); //SET COLUMN FOR SEARCH 
        //, 'IF(cand.is_active = "3", "Y", "N")', 'GREATEST((3 - cand.re_attempt) ,0)', 'GREATEST(290 - (DATEDIFF(CURDATE(), acb.batch_end_date )) ,0)', 'cand.parent_id'

        $order = array('cand.candidate_id' => 'DESC'); // DEFAULT ORDER    
         
        $WhereForTotal = "WHERE cand.is_deleted = '0' AND acb.is_deleted = '0' AND acb.batch_status = '3' AND acb.batch_end_date < '".date("Y-m-d")."'"; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
        $Where = "WHERE cand.is_deleted = '0' AND acb.is_deleted = '0' AND acb.batch_status = '3' AND acb.batch_end_date < '".date("Y-m-d")."'";

        $login_user_id = $this->session->userdata('NCVET_LOGIN_ID');
        $login_user_type = $this->session->userdata('NCVET_USER_TYPE');
        if($login_user_type == 'agency') //CHECK IN AGENCY
        { 
          $Where .= " AND acb.agency_id = '".$login_user_id."'"; 
          $WhereForTotal .= " AND acb.agency_id = '".$login_user_id."'"; 
        }
        
        if($_POST['search']['value']) // DATATABLE SEARCH
        {
          $Where .= " AND (";
          for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['search']['value']) )."%' ESCAPE '!' OR "; }
          $Where = substr_replace( $Where, "", -3 );
          $Where .= ')';
        }  

        if ($form_action == 'export')
        {
          if(isset($_POST['tbl_search_value']) && $_POST['tbl_search_value']) // DATATABLE SEARCH
          {
            $Where .= " AND (";
            for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['tbl_search_value']) )."%' ESCAPE '!' OR "; }
            $Where = substr_replace( $Where, "", -3 );
            $Where .= ')';
          }
        }
        
        //CUSTOM SEARCH
        $s_agency = trim($this->security->xss_clean($this->input->post('s_agency')));
        if($s_agency != "") { $Where .= " AND acb.agency_id = '".$s_agency."'"; } 

        $s_centre = trim($this->security->xss_clean($this->input->post('s_centre')));
        if($s_centre != "") { $Where .= " AND acb.centre_id = '".$s_centre."'"; }

        $s_batch_type = trim($this->security->xss_clean($this->input->post('s_batch_type')));
        if ($s_batch_type != "")
        {
          $Where .= " AND acb.batch_type = '" . $s_batch_type . "'";
        } 
        
        $s_from_date = trim($this->security->xss_clean($this->input->post('s_from_date')));
        $s_to_date = trim($this->security->xss_clean($this->input->post('s_to_date')));
        
        if($s_from_date != "" && $s_to_date != "")
        { 
          $Where .= " AND (acb.batch_start_date >= '".$s_from_date."' OR acb.batch_end_date >= '".$s_from_date."') AND (acb.batch_start_date <= '".$s_to_date."' OR acb.batch_end_date <= '".$s_to_date."')"; 
        }else if($s_from_date != "") { $Where .= " AND (acb.batch_start_date >= '".$s_from_date."' OR acb.batch_end_date >= '".$s_from_date."')"; 
        }else if($s_to_date != "") { $Where .= " AND (acb.batch_start_date <= '".$s_to_date."' OR acb.batch_end_date <= '".$s_to_date."')"; } 

        $s_batch_code = trim($this->security->xss_clean($this->input->post('s_batch_code')));
        if($s_batch_code != "") { $Where .= " AND acb.batch_code LIKE '%".custom_safe_string($s_batch_code)."%'"; } //ncvet/ncvet_helper.php
        
        /*$s_batch_status = trim($this->security->xss_clean($this->input->post('s_batch_status')));
        if($s_batch_status != "") { $Where .= " AND acb.batch_status = '".$s_batch_status."'"; }*/      

        $Order = ""; //DATATABLE SORT
        if(isset($_POST['order'])) { $explode_arr = explode(" AS ",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
        else if(isset($order)) { $Order = "ORDER BY ".key($order)." ".$order[key($order)]; }
        
        $Limit = ""; 
        if ($_POST['length'] != '-1' && $form_action != 'export') 
        { 
          $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); 
        } // DATATABLE LIMIT  
        

        $join_qry = " INNER JOIN ncvet_agency_master am1 ON am1.agency_id = cand.agency_id"; 
        $join_qry .= " INNER JOIN ncvet_centre_master cm ON cm.centre_id = cand.centre_id";
        $join_qry .= " INNER JOIN ncvet_agency_centre_batch acb ON acb.batch_id = cand.batch_id";
        $join_qry .= " LEFT JOIN city_master cm1 ON cm1.id = cm.centre_city"; 

        /*$Where .= $GroupBy;
        $WhereForTotal .= $GroupBy;*/
              
        $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
        $Result = $this->db->query($print_query);  
        $Rows = $Result->result_array();
        
        $TotalResult = $this->Iibf_ncvet_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$WhereForTotal);
        $FilteredResult = $this->Iibf_ncvet_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);
        
        $data = array();
        $no = $_POST['start'];    
        
        if ($form_action == 'export')
        {
          // Excel file name for download 
          $fileName = "Candidates_Required_to_re_enroll_for_Training_".date('Y-m-d').".xls";  
          // Column names 
          $fields = array('Sr. No.', 'Agency Name', 'Centre Name', 'Batch Code', 'Training Id', 'Registration No.', 'Candidate Full Name', 'Training From', 'Training To', 'Exam Date1', 'Exam Date2', 'Exam Date3', 'Result1', 'Result2', 'Result3');  
          // Display column names as first row 
          $excelData = implode("\t", array_values($fields)) . "\n";  
        }

        foreach ($Rows as $Res) 
        {
          $no++;
          $row = array();

          $this->db->join('bcbf_marks bm', 'bm.exam_id = me.exam_code AND bm.exam_period = me.exam_period AND bm.regnumber = "'.$Res['regnumber'].'"', 'LEFT');
          $member_exam_data = $this->master_model->getRecords('ncvet_member_exam me', array('me.candidate_id' => $Res['candidate_id'], 'me.pay_status' => '1'), 'me.candidate_id, me.exam_code, me.exam_period, me.pay_status, me.exam_date, bm.status AS result',array('me.member_exam_id','ASC'));
          //echo $this->db->last_query();
          $exam_date1 = $exam_date2 = $exam_date3 = $result1 = $result2 = $result3 = '';        
          if(count($member_exam_data) > 0) 
          { 
            $exam_date1 = isset($member_exam_data[0]['exam_date']) ? $member_exam_data[0]['exam_date'] : ''; 
            $exam_date2 = isset($member_exam_data[1]['exam_date']) ? $member_exam_data[1]['exam_date'] : ''; 
            $exam_date3 = isset($member_exam_data[2]['exam_date']) ? $member_exam_data[2]['exam_date'] : '';  

            $result1 = isset($member_exam_data[0]['result']) ? $member_exam_data[0]['result'] : '';   
            $result2 = isset($member_exam_data[1]['result']) ? $member_exam_data[1]['result'] : '';   
            $result3 = isset($member_exam_data[2]['result']) ? $member_exam_data[2]['result'] : '';   
          }
           
          $row[] = $no;
          $row[] = $Res['agency_name'];
          $row[] = $Res['DispCentreName'];
          $row[] = $Res['batch_code'];
          $row[] = $Res['training_id'];
          $row[] = $Res['regnumber'];
          $row[] = $Res['DispName']; 
          $row[] = $Res['batch_start_date'];
          $row[] = $Res['batch_end_date'];
          //$row[] = $Res['candidate_release_status'];
          $row[] = $exam_date1;
          $row[] = $exam_date2;
          $row[] = $exam_date3;
          $row[] = $result1;
          $row[] = $result2;
          $row[] = $result3;
          //$row[] = $Res['attempt_remaining'];
          //$row[] = $Res['validity_remaining'];
          
     
          if ($form_action == 'export')
          {
            array_walk($row, 'filterData');
            $excelData .= implode("\t", array_values($row)) . "\n";
          }

          $data[] = $row; 
        }     
        
        if ($form_action == 'export')
        {
          if (count($Rows) == '0')
          {
            $excelData .= 'No records found...' . "\n";
          }

          // Headers for download 
          header("Content-Type: application/vnd.ms-excel");
          header("Content-Disposition: attachment; filename=\"$fileName\"");
          // Render excel data 
          echo $excelData;
          exit;
        }

        $output = array(
        "draw" => $_POST['draw'],
        "recordsTotal" => $TotalResult, //All result count
        "recordsFiltered" => $FilteredResult, //Disp result count
        "Query" => $print_query,
        "data" => $data,
        );
        //output to json format
        echo json_encode($output);      
    }

    //NCVET No. of Pass/Fail/Absent Count API
    public function get_ncvet_no_of_pass_fail_absent_cnt_api($batch_code = '')
    { 
      //echo $type.' / '.$exam_code.' / '.$exam_period.' / '.$part_no.' / 1 / '.$member_no; 
      $final_arr = $response_msg = array();
      $response = '';  
      $pass_fail_absent_flag["pass_cnt"] = 0;
      $pass_fail_absent_flag["fail_cnt"] = 0;
      $pass_fail_absent_flag["absent_cnt"] = 0;
      $pass_fail_absent_flag["api_record_found"] = 0;

      if($batch_code != ""){ 

        /*$part_no = '1'; 
        if(base_url() == 'http://172.16.24.5/')//FOR QA ENVIRONMENT : THE CLIENT API DOES NOT HAVE ACCESS ON QA ENVIRONMENT
        {
          $url="https://iibf.esdsconnect.com/staging/ncvet/admin/Reports/get_ncvet_result_related_api_curl_qa/".$type."/".$exam_code."/".$exam_period."/".$part_no."/".$member_no; //for QA
                  
          $string = preg_replace('/\s+/', '+', $url);
          $x = curl_init($string);
          curl_setopt($x, CURLOPT_HEADER, 0);    
          curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);
          curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);    
          curl_setopt($x, CURLOPT_SSL_VERIFYHOST, FALSE);
          curl_setopt($x, CURLOPT_SSL_VERIFYPEER, FALSE);
          
          $result = curl_exec($x);
          return $pass_fail_absent_flag = json_decode($result,true);
        }*/
   
        $url="http://10.10.233.76:8099/resultcountapi/getResultPassFailAbsentCount/".$batch_code; // Production API
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
            //echo ($api_result); 
            $api_result = json_decode($api_result); 
            if (isset($api_result) && count($api_result) > 0)
            {   
              $batch_details = $batch_result_status1 = $batch_result_status2 = $batch_result_status3 = array();
  
              if(isset($api_result[0])){
                $batch_details = $api_result[0];
              }

              if(isset($api_result[0]) && isset($batch_details[1]) && $batch_details[1] == $batch_code){

                if(isset($api_result[1])){
                  $batch_result_status1 = $api_result[1]; 
                  if($batch_result_status1[0] == "P"){
                    //echo "Pass Count: ".$batch_result_status1[1];
                    $pass_fail_absent_flag["pass_cnt"] = $batch_result_status1[1];
                  }
                  if($batch_result_status1[0] == "F"){
                    //echo "Fail Count: ".$batch_result_status1[1];
                    $pass_fail_absent_flag["fail_cnt"] = $batch_result_status1[1];
                  }
                  if($batch_result_status1[0] == "A"){
                    //echo "Absent Count: ".$batch_result_status1[1];
                    $pass_fail_absent_flag["absent_cnt"] = $batch_result_status1[1];
                  }
                }

                if(isset($api_result[2])){
                  $batch_result_status2 = $api_result[2]; 
                  if($batch_result_status2[0] == "P"){
                    //echo "Pass Count: ".$batch_result_status2[1];
                    $pass_fail_absent_flag["pass_cnt"] = $batch_result_status2[1];
                  }
                  if($batch_result_status2[0] == "F"){
                    //echo "Fail Count: ".$batch_result_status2[1];
                    $pass_fail_absent_flag["fail_cnt"] = $batch_result_status2[1];
                  }
                  if($batch_result_status2[0] == "A"){
                    //echo "Absent Count: ".$batch_result_status2[1];
                    $pass_fail_absent_flag["absent_cnt"] = $batch_result_status2[1];
                  }
                }

                if(isset($api_result[3])){
                  $batch_result_status3 = $api_result[3]; 
                  if($batch_result_status3[0] == "P"){
                    //echo "Pass Count: ".$batch_result_status3[1];
                    $pass_fail_absent_flag["pass_cnt"] = $batch_result_status3[1];
                  }
                  if($batch_result_status3[0] == "F"){
                    //echo "Fail Count: ".$batch_result_status3[1];
                    $pass_fail_absent_flag["fail_cnt"] = $batch_result_status3[1];
                  }
                  if($batch_result_status3[0] == "A"){
                    //echo "Absent Count: ".$batch_result_status3[1];
                    $pass_fail_absent_flag["absent_cnt"] = $batch_result_status3[1];
                  }
                } 

              }
              else{
                $pass_fail_absent_flag["api_record_found"] = 0;
              }
               
               
            }else{
              $pass_fail_absent_flag["api_record_found"] = 0;
            } 
        }
        curl_close($x);
      }

      return $pass_fail_absent_flag;
    }


    function get_training_batch_details_common_dummy_data(){
        $form_action = trim($this->security->xss_clean($this->input->post('form_action')));
        $selected_batch_ids = trim($this->security->xss_clean($this->input->post('selected_batch_ids')));

        $table = 'ncvet_agency_centre_batch acb';
        //$table = 'ncvet_candidates bc';
        $GroupBy = " Group By acb.batch_id,bc.bank_associated";

        $column_order = array('""', 'acb.batch_id', 'am1.agency_name', 'acb.batch_code', 'bam.bank_name', 'COUNT(DISTINCT CASE WHEN bc.is_deleted = "0" AND acb.batch_status = "3" THEN bc.candidate_id END) AS tot_candiadtes_registered_for_training', 'COUNT(DISTINCT CASE WHEN bc.re_attempt = "0" AND bc.is_active = "3" AND DATE_FORMAT(DATE_ADD(acb.batch_end_date, INTERVAL '.$this->buffer_days_after_training_end_date.' DAY), "%Y-%m-%d") < CURDATE() AND CURDATE() <= DATE_FORMAT(DATE_ADD(acb.batch_end_date, INTERVAL '.$this->buffer_days_after_candidate_add_date.' DAY), "%Y-%m-%d") THEN bc.candidate_id END) AS no_of_candidates_eligible_for_exam', 'COUNT(DISTINCT CASE WHEN bc.re_attempt > "0" THEN bc.candidate_id END) AS no_of_candidates_applied_for_exam', 'acb.batch_status', 'acb.centre_id', 'acb.batch_type'); //SET COLUMNS FOR SORT
        
        $column_search = array('am1.agency_name', 'acb.batch_code', 'bam.bank_name', 'COUNT(DISTINCT CASE WHEN bc.is_deleted = "0" AND acb.batch_status = "3" THEN bc.candidate_id END)'); //SET COLUMN FOR SEARCH
        $order = array('acb.batch_id' => 'DESC'); // DEFAULT ORDER
        
         
        $WhereForTotal = "WHERE acb.is_deleted = 0 "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
        $Where = "WHERE acb.is_deleted = 0  ";

        $login_user_id = $this->session->userdata('NCVET_LOGIN_ID');
        $login_user_type = $this->session->userdata('NCVET_USER_TYPE');
        if($login_user_type == 'agency') //CHECK IN AGENCY
        { 
          $Where .= " AND acb.agency_id = '".$login_user_id."'"; 
          $WhereForTotal .= " AND acb.agency_id = '".$login_user_id."'"; 
        }
        
        if($_POST['search']['value']) // DATATABLE SEARCH
        {
          $Where .= " AND (";
          for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['search']['value']) )."%' ESCAPE '!' OR "; }
          $Where = substr_replace( $Where, "", -3 );
          $Where .= ')';
        }  

        if ($form_action == 'export')
        {
          if(isset($_POST['tbl_search_value']) && $_POST['tbl_search_value']) // DATATABLE SEARCH
          {
            $Where .= " AND (";
            for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['tbl_search_value']) )."%' ESCAPE '!' OR "; }
            $Where = substr_replace( $Where, "", -3 );
            $Where .= ')';
          }
        }
        
        //CUSTOM SEARCH
        $s_agency = trim($this->security->xss_clean($this->input->post('s_agency')));
        if($s_agency != "") { $Where .= " AND acb.agency_id = '".$s_agency."'"; } 

        $s_centre = trim($this->security->xss_clean($this->input->post('s_centre')));
        if($s_centre != "") { $Where .= " AND acb.centre_id = '".$s_centre."'"; } 

        $s_batch_type = trim($this->security->xss_clean($this->input->post('s_batch_type')));
        if ($s_batch_type != "")
        {
          $Where .= " AND acb.batch_type = '" . $s_batch_type . "'";
        }

        if($selected_batch_ids != "" && $form_action == 'export') { $Where .= " AND acb.batch_id IN (".$selected_batch_ids.")"; }
        
        $s_from_date = trim($this->security->xss_clean($this->input->post('s_from_date')));
        $s_to_date = trim($this->security->xss_clean($this->input->post('s_to_date')));
        
        if($s_from_date != "" && $s_to_date != "")
        { 
          $Where .= " AND (acb.batch_start_date >= '".$s_from_date."' OR acb.batch_end_date >= '".$s_from_date."') AND (acb.batch_start_date <= '".$s_to_date."' OR acb.batch_end_date <= '".$s_to_date."')"; 
        }else if($s_from_date != "") { $Where .= " AND (acb.batch_start_date >= '".$s_from_date."' OR acb.batch_end_date >= '".$s_from_date."')"; 
        }else if($s_to_date != "") { $Where .= " AND (acb.batch_start_date <= '".$s_to_date."' OR acb.batch_end_date <= '".$s_to_date."')"; } 

        $s_batch_code = trim($this->security->xss_clean($this->input->post('s_batch_code')));
        if($s_batch_code != "") { $Where .= " AND acb.batch_code LIKE '%".custom_safe_string($s_batch_code)."%'"; } //ncvet/ncvet_helper.php
        
        $s_batch_status = trim($this->security->xss_clean($this->input->post('s_batch_status')));
        if($s_batch_status != "") { $Where .= " AND acb.batch_status = '".$s_batch_status."'"; }      

        $Order = ""; //DATATABLE SORT
        if(isset($_POST['order'])) { $explode_arr = explode(" AS ",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
        else if(isset($order)) { $Order = "ORDER BY ".key($order)." ".$order[key($order)]; }
        
        $Limit = ""; 
        if ($_POST['length'] != '-1' && $form_action != 'export') 
        { 
          $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); 
        } // DATATABLE LIMIT  
        
        //$join_qry = " LEFT JOIN ncvet_centre_master cm ON cm.centre_id = acb.centre_id";
        //$join_qry .= " LEFT JOIN city_master cm1 ON cm1.id = cm.centre_city";
        

        $join_qry = " LEFT JOIN ncvet_agency_master am1 ON am1.agency_id = acb.agency_id"; 
        //$join_qry .= " LEFT JOIN ncvet_agency_centre_batch acb ON acb.batch_id = bc.batch_id"; 
        $join_qry .= " LEFT JOIN ncvet_candidates bc ON bc.batch_id = acb.batch_id"; 
        $join_qry .= " LEFT JOIN ncvet_bank_associated_master bam ON bam.bank_code = bc.bank_associated"; 

        $Where .= $GroupBy;
        $WhereForTotal .= $GroupBy;
              
        $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
        $Result = $this->db->query($print_query);  
        $Rows = $Result->result_array();
        
        $TotalResult = $this->Iibf_ncvet_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$WhereForTotal);
        $FilteredResult = $this->Iibf_ncvet_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);
        
        $data = array();
        $no = $_POST['start'];    
        
        if ($form_action == 'export')
        {
          // Excel file name for download 
          $fileName = "Training_Batch_Details_".date('Y-m-d').".xls";  
          // Column names 

            $fields = array('Sr. No.', 'Agency Name', 'Batch Code', 'Bank Name/Training Institute providing Training', 'Total Candiadtes registered for Training','No. of Candidates Eligible for Exam', 'No. of BCAs Appeared for BC Certification'); 

          if($selected_batch_ids != ""){
            $fields = array('Sr. No.', 'Agency Name', 'Batch Code', 'Bank Name/Training Institute providing Training', 'Total Candiadtes registered for Training','No. of Candidates Eligible for Exam', 'No. of BCAs Appeared for BC Certification', 'No. of BCAs passing the BC Certification'); 
          }
           
          // Display column names as first row 
          $excelData = implode("\t", array_values($fields)) . "\n";  
        }

        foreach ($Rows as $Res) 
        {
          $no++;
          $row = array();
          
          $checked = '';
          if($selected_batch_ids != "" && in_array($Res['batch_id'],explode(",",$selected_batch_ids))){
            $checked = 'checked';
          }

          $checkbox_str = '<label class="css_checkbox_radio"><input '.$checked.' onclick="set_all_batch_id_list(); only_checked_id('.$Res['batch_id'].');" type="checkbox" name="checkboxlist_batch_id" class="checkbox_training_details_btch_common" value="'.$Res['batch_id'].'" id="checkboxlist_batch_id_'.$Res['batch_id'].'"><span class="checkmark"></span></label>';

          if($form_action != 'export'){
            $row[] = $checkbox_str; 
          }
          $row[] = $no;
          $row[] = $Res['agency_name'];
          //$row[] = $Res['DispCentreName'];
          $row[] = $Res['batch_code'];  
          $row[] = $Res['bank_name']; //$Res['DispBatchType'];
          $row[] = $Res['tot_candiadtes_registered_for_training'];  
          $row[] = $Res['no_of_candidates_eligible_for_exam'];  
          $row[] = $Res['no_of_candidates_applied_for_exam'];  
          
          if ($form_action == 'export')
          {
            if($selected_batch_ids != "" && in_array($Res['batch_id'],explode(",",$selected_batch_ids))){
              //$chk_api_data = $this->Iibf_ncvet_model->calculate_no_of_pass_fail_absent_candidates($Res['batch_id']);
              $chk_api_data = $this->Iibf_ncvet_model->get_ncvet_no_of_pass_fail_absent_cnt_api($Res['batch_code']);
              //echo $this->db->last_query();die;
              //print_r($chk_api_data);die;
              //echo $chk_api_data["pass_cnt"];die;
              $row[] = $chk_api_data["pass_cnt"];  
            }
            array_walk($row, 'filterData');
            $excelData .= implode("\t", array_values($row)) . "\n";
          }

          $data[] = $row; 
        }     
        
        if ($form_action == 'export')
        {
          if (count($Rows) == '0')
          {
            $excelData .= 'No records found...' . "\n";
          }

          // Headers for download 
          header("Content-Type: application/vnd.ms-excel");
          header("Content-Disposition: attachment; filename=\"$fileName\"");
          // Render excel data 
          echo $excelData;
          exit;
        }

        $output = array(
        "draw" => $_POST['draw'],
        "recordsTotal" => $TotalResult, //All result count
        "recordsFiltered" => $FilteredResult, //Disp result count
        "Query" => $print_query, 
        "data" => $data,
        //"selected_batch_ids" => $selected_batch_ids,
        "chk_api_data" => $chk_api_data,
        );
        //output to json format
        echo json_encode($output);      
    }

    //START : SEND NOTIFICATION FOR APPROVED / REJECTED CANDIDATE KYC EMAIL
    function send_approve_reject_kyc_email_sms($candidate_data,$module_name,$kyc_action)
    {
      if(count($candidate_data) > 0)
      { 
      
      $emailer_data = $this->master_model->getRecords('emailer', array('emailer_name'=>'NCVET_KYC_APPROVE_REJECT'), 'emailer_name, emailer_text, sms_text, sms_template_id, sms_sender, subject'); 

      $candidate_name = ''; 
      $candidate_name .= $candidate_data[0]['salutation']; 
      $candidate_name .= $candidate_data[0]['first_name'] !=''? ' '.$candidate_data[0]['first_name']:'';
      $candidate_name .= $candidate_data[0]['middle_name'] !=''? ' '.$candidate_data[0]['middle_name']:'';
      $candidate_name .= $candidate_data[0]['last_name'] !=''? ' '.$candidate_data[0]['last_name']:'';

      $username = '';
      $password = '';
      $login_url = base_url('');
      if($module_name == "bcbf"){
        $username = $candidate_data[0]['training_id'].' / '.$candidate_data[0]['regnumber'];
        $password = $candidate_data[0]['email_id'].' / '.$candidate_data[0]['mobile_no'];
        $login_url = base_url('iibfbcbf/candidate/Login_candidate');
      }else if($module_name == "dra"){
        $username = $candidate_data[0]['regnumber'];
        $password = $candidate_data[0]['email_id'].' / '.$candidate_data[0]['mobile_no'];
        $login_url = base_url('iibfdra/candidate/Login_candidate');
      }

        $mail_arg = array();

        //KYC REJECTED FOR 
        $mail_arg['subject'] = str_replace("#KYC_ACTION#", $kyc_action, $emailer_data[0]['subject']);
        $mail_arg['to_email'] = $candidate_data[0]['email_id']; //'sagar.matale@esds.co.in'; 
        $mail_arg['to_name'] = $candidate_data[0]['first_name']; //'sagar'; //
        $mail_arg['cc_email'] = 'sagar.matale@esds.co.in,anil.s@esds.co.in';//sagar.matale@esds.co.in,anil.s@esds.co.in

        $mail_arg['bcc_email'] = 'iibfteam@esds.co.in';//sagar.matale@esds.co.in,anil.s@esds.co.in
        $mail_arg['is_header_footer_required'] = '0';
        $mail_arg['view_flag'] = '0';
        
        $mail_content = '
          <style type="text/css">            
            p { padding: 0; margin: 0; font-weight: bold; }
            p.footer_regards { line-height: 20px; }
            table.inner_tbl { font-size: 14px; border-collapse: collapse; width: 100%; color:#000; }
            table.inner_tbl tbody tr td { padding: 5px 10px; border-collapse: collapse; border: 1px solid #776f6f; line-height:20px; vertical-align:top; min-width:200px; }                          
          </style>'.$emailer_data[0]['emailer_text'];        

        /*$mail_content = str_replace("#REG_NO#", $candidate_data[0]['regnumber'], $mail_content);
        $mail_content = str_replace("#CANDIDATE_NAME#", $candidate_name, $mail_content);*/

        $mail_content = str_replace("#PHOTO_SIGN_IDPROF#", $kyc_action, $mail_content);

        $mail_content = str_replace("#LOGIN_ID#", $username, $mail_content);
        $mail_content = str_replace("#PASSWORD#", $password, $mail_content);
        $mail_content = str_replace("#LOGIN_LINK#", $login_url, $mail_content); 

        $mail_arg['mail_content'] = $mail_content;

        //echo "<pre>".print_r($mail_arg)."<pre>"; //die;
        $this->ncvet_send_mail_common($mail_arg);

        
      }
    }//END : SEND NOTIFICATION FOR APPROVED / REJECTED CANDIDATE KYC EMAIL

    /* Function to download E-invoice Added By ANIL S on 07 July 2025 for IIBF BCBF */
    public function download_e_invoice($enc_inv_no)
    { 

      $inv_no = url_decode($enc_inv_no);
      //echo $inv_no;
      ## Test invoice no
      //$inv_no = 'EDN_20-21_000310';
      //$inv_no = 'BC_25-26_009117'; 
      
      ## Live Url
      $service_url = 'http://10.10.233.76:8083/irnapi/getDataByDocNo/'.$inv_no;
      $curl = curl_init($service_url);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_POST, false);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      $curl_response = curl_exec($curl);
      curl_close($curl);
        $json_objekat=json_decode($curl_response);
      //print_r($json_objekat); exit;
      $file_cont=base64_decode($json_objekat->signedPdf);
      
      if(strlen($file_cont) > 0)
      {
        header('Set-Cookie: fileLoading=true'); 
        header('Content-Type: application/pdf');
        header('Content-Length:'.strlen($file_cont));
        header('Content-disposition: attachment; filename=invoice.pdf');
        header('Content-Transfer-Encoding: Binary');
        echo $file_cont;
      }
      else
      {
        echo 'Invoice Not available/generated';
      } 

    }
    /* Function to download E-invoice Added By ANIL S on 07 July 2025 for IIBF BCBF */

    function change_candidate_hold_release_status_common($posted_arr=array(), $action_by='')
    {
      $cand_id = htmlspecialchars_decode($posted_arr['cand_id']);
      $status = htmlspecialchars_decode($posted_arr['status']);

      $cand_data = $this->master_model->getRecords('ncvet_candidates', array('candidate_id' => $cand_id), 'candidate_id, is_active');
      // echo "<pre>"; print_r($posted_arr); exit;
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
          $new_hold_release_status = '1';
          $new_hold_release_text = 'Active';
        }
        else if($status == "false")
        {
          $new_hold_release_status = '0';
          $new_hold_release_text   = 'Deactive';
        }

        if($new_hold_release_status == $cand_data[0]['is_active']) { return "error";  }
        else
        {     
          $posted_arr = json_encode($posted_arr);
          $dispName = $this->getLoggedInUserDetails($this->session->userdata('NCVET_LOGIN_ID'), $action_by);

          $up_data['is_active']  = $new_hold_release_status;
          $up_data['updated_on'] = date("Y-m-d H:i:s");
          $up_data['updated_by'] = $this->session->userdata('NCVET_LOGIN_ID');            
          $this->master_model->updateRecord('ncvet_candidates', $up_data, array('candidate_id'=>$cand_id));
          // echo $this->db->last_query(); exit;              
          $this->insert_common_log($action_by.' : Candidate status Updated', 'ncvet_candidates', $this->db->last_query(), $cand_id,'candidate_action','The candidate has successfully mark as '.$new_hold_release_text.' by the '.$action_by.' '.$dispName['disp_name'], $posted_arr);
                          
          return "success";
        }
      }
    }

    //START : THIS FUNCTION IS USED TO CHECK THE CANDIDATE CHECK DUPLICATION FOR MOBILE, EMAIL, AAPAR ID, AADHAR
    function validation_check_candidate_eligibility_to_add_in_new_batch($candidate_id=0, $chk_column='', $chk_value='', $enc_batch_id='')
    {
      $candidate_data = [];
      
      if($chk_column == 'mobile') { $this->db->where('cand.mobile_no', $chk_value); }
      else if($chk_column == 'email') { $this->db->where('cand.email_id', $chk_value); }
      else if($chk_column == 'id_proof_number') { $this->db->where('cand.id_proof_number', $chk_value); }
      else if($chk_column == 'aadhar_no') { $this->db->where('cand.aadhar_no', $chk_value); }

      $this->db->order_by('cand.candidate_id', 'DESC');
      $candidate_data = $this->master_model->getRecords('ncvet_candidates cand', array('cand.is_deleted' => '0','cand.is_active' => '1', 'cand.candidate_id !=' => $candidate_id), 'cand.candidate_id, cand.mobile_no, cand.email_id, cand.id_proof_number, cand.aadhar_no, cand.created_on, cand.is_active');
     
      if(count($candidate_data) > 0)
      {
        return $candidate_data; 
      }
    }
  }				  