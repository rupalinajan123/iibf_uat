<?php
  /********************************************************************************************************************
  ** Description: Common Model for MACRORESEARCH Module
  ** Created BY: Priyanka Dhikale 20-may-24
  ********************************************************************************************************************/
	class Macroresearch_model extends CI_Model
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model('master_model');			
			$this->load->model('Emailsending');			
    }
		
		function check_session_login()/******** START : CHECK IF ANY SESSION IS ALREADY STARTED OR NOT ON GLOBAL LOGIN PAGE ********/
		{
			$login_user_id = $this->session->userdata('MACRORESEARCH_LOGIN_ID');
			$login_user_type = $this->session->userdata('MACRORESEARCH_USER_TYPE');
			if(isset($login_user_id) && $login_user_id != "" && isset($login_user_type) && $login_user_type != "")
			{
        if($login_user_type == 'admin') //CHECK IN ADMIN
        {
          $admin_data = $this->master_model->getRecords('macroresearch_admin', array('admin_id' => $login_user_id, 'is_active' => '1', 'is_deleted' => '0'), 'admin_id');

          if(count($admin_data) > 0) { redirect(site_url('macroresearch/admin/dashboard_admin'),'refresh'); }
				  else { redirect(site_url('macroresearch/login/logout'),'refresh'); }
        }
        else if($login_user_type == 'candidate') //CHECK IN AGENCY
        {
				  $candidate_data = $this->master_model->getRecords('macroresearch_applications', array('id' => $login_user_id, 'is_active' => '1', 'is_deleted' => '0'), 'id');

          if(count($candidate_data) > 0) { redirect(site_url('macroresearch/candidate/dashboard_candidate'),'refresh'); }
				  else { 
            redirect(site_url('macroresearch/login/logout'),'refresh'); 
          }
        }	
      	
      }
    }/******** END : CHECK IF ANY SESSION IS ALREADY STARTED OR NOT ON GLOBAL LOGIN PAGE ********/
		
    /******** START : CHECK SESSION AFTER LOGIN FOR ALL ADMIN, AGENCY, CENTER PAGES ********/
		function check_admin_session_all_pages($login_as='') //login_as = admin or candidate or centre
		{
			$login_user_id = $this->session->userdata('MACRORESEARCH_LOGIN_ID');
			$login_user_type = $this->session->userdata('MACRORESEARCH_USER_TYPE');

			if (!isset($login_user_id) || $login_user_id == "" || !isset($login_user_type) || $login_user_type == "")
			{
				redirect(site_url('macroresearch/login/logout'),'refresh');
      }
			else
			{
        if($login_as == '') { redirect(site_url('macroresearch/login/logout'),'refresh'); }
        else
        {
          if($login_as != $login_user_type) 
          {  
            if($login_user_type == 'admin') 
            { 
              redirect(site_url('macroresearch/admin/dashboard_admin'),'refresh'); 
            }
            else if($login_user_type == 'candidate') 
            { 
              redirect(site_url('macroresearch/candidate/dashboard_candidate'),'refresh');
            }
           
          }
        }
        
        if($login_user_type == 'admin')
        {
          $admin_data = $this->master_model->getRecords('macroresearch_admin', array('admin_id' => $login_user_id, 'is_active' => '1', 'is_deleted' => '0'), 'admin_id');

          if(count($admin_data) == 0) 
          { 
            redirect(site_url('macroresearch/login/logout'),'refresh'); 
          }
        }
        else if($login_user_type == 'candidate')
        {
				  $candidate_data = $this->master_model->getRecords('macroresearch_applications', array('id' => $login_user_id, 'is_active' => '1', 'is_deleted' => '0'), 'id');

          if(count($candidate_data) == 0) 
          { 
            redirect(site_url('macroresearch/login/logout'),'refresh'); 
          }				  
        }
        
        
      }
    }/******** END : CHECK SESSION AFTER LOGIN FOR ALL ADMIN, AGENCY PAGES ********/

 
    public function getLoggedInUserDetails($user_id, $type) /******** START : GET LOGGED IN ADMIN, AGENCY DETAILS ********/
    {
      $disp_name = '';
      $disp_sidebar_name = '';
      if($type == 'admin')
      {
        $disp_name = 'Admin';
        $admin_data = $this->master_model->getRecords('macroresearch_admin', array('admin_id' => $user_id), 'admin_id, admin_name');
              
        if(count($admin_data) > 0) { $disp_name = $admin_data[0]['admin_name']; }
      }
      
     
      $data['disp_name'] = $disp_name;
      $data['disp_sidebar_name'] = $disp_sidebar_name;
			return $data;
    }/******** END : GET LOGGED IN ADMIN, AGENCY DETAILS ********/

    function insert_user_login_logs($user_id=0, $user_type=0, $type=0) /******** START : MAINTAIN LOGIN - LOGOUT LOGS ********/
		{
      $this->load->helper('macroresearch_helper');
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
			$this->master_model->insertRecord('macroresearch_login_logs',$add_log);
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
      $this->load->helper('macroresearch_helper');
			$this->load->helper('url');
			$this->load->library('user_agent');
      
      $user_type = $this->session->userdata('MACRORESEARCH_USER_TYPE');
      $login_id = $this->session->userdata('MACRORESEARCH_LOGIN_ID');

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
			$this->master_model->insertRecord('macroresearch_logs',$add_log);
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
    //parameter : separated by pipe 'input name|required|allowed extension|size in kb|input display name'
    //eg. 'pan_photo|y|jpg,jpeg,png|20|pan photo'
    //callback_fun_validate_file_upload[pan_photo|y|jpg,jpeg,png|20|pan photo]
    public function fun_validate_file_upload($parameter='') 
    {
      $result['flag'] = 'success';
      $result['response'] = '';

      $parameter_str = $parameter; 
      $parameter_err = explode('|',$parameter_str);

      $input_name = $is_required = $allow_ext = $size_in_kb = $input_disp_name = '';
      if(count($parameter_err) > 0 )
      {
        if(isset($parameter_err[0])) { $input_name = $parameter_err[0]; }
        if(isset($parameter_err[1])) { $is_required = $parameter_err[1]; }
        if(isset($parameter_err[2])) { $allow_ext = $parameter_err[2]; }
        if(isset($parameter_err[3])) { $size_in_kb = $parameter_err[3]; }
        if(isset($parameter_err[4])) { $input_disp_name = $parameter_err[4]; }
      }

           
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

        // Check if the file size is within limits
        $max_size = $size_in_kb; // 20kb
        if ($_FILES[$input_name]['size'] > $max_size * 1024) 
        {
          $result['response'] = 'The file size should not be more than '.$max_size.'KB';  
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
        $this->load->helper('macroresearch_helper'); 
				create_directories($upload_path); //macroresearch_helper.php
				
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
        if(!isset($file_name) || $file_name == '' || !file_exists($file_path.$file_name))//file_path = "./macroresearch/uploads/faculty_photo/"
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

      $batch_data = $this->master_model->getRecords('macroresearch_applications_centre_batch acb', array('acb.is_deleted' => '0', 'acb.batch_status' => '3', 'acb.batch_end_date >='=>date('Y-m-d'), 'acb.batch_extend_date !='=>'', 'acb.batch_extend_type !=' => '0'), "acb.batch_id, acb.batch_extend_date, acb.batch_extend_type");
      
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



    function macroresearch_send_mail_common($mail_arg=array())
    {
      $subject = $mail_content = $to_email = $to_name = $cc_email = $bcc_email = $attachment = '';
      $bcc_email = 'sagar.matale@esds.co.in,anil.s@esds.co.in';
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
        
        $bcc_email = 'sagar.matale@esds.co.in,anil.s@esds.co.in';
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
    
    
    function macroresearch_get_fees($exam_code=0,$exam_period=0)
    {
      $fresh_fee = $rep_fee = 0;
      $fee_data = $this->master_model->getRecords('macroresearch_exam_fee_master',array('fee_delete'=>'0', 'member_category'=>'NM', 'exempt'=>'NE', 'exam_code'=>$exam_code, 'exam_period'=>$exam_period));
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


  }