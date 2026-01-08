<?php
  /********************************************************************************************************************
  ** Description: Common Model for DRA Module
  ** Created BY: Gaurav Shewale On 15-11-2024
  ********************************************************************************************************************/
	class Iibf_dra_model extends CI_Model
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model('master_model');			
			$this->load->model('Emailsending');		
    }	  

    function check_session_candidate_login()/******** START : CHECK IF CANDIDATE SESSION IS ALREADY STARTED OR NOT ON CANDIDATE LOGIN PAGE **/
		{
			$login_candidate_id = $this->session->userdata('IIBF_DRA_CANDIDATE_LOGIN_ID');
			if(isset($login_candidate_id) && $login_candidate_id != "")
			{
        $candidate_data = $this->master_model->getRecords('dra_members cand', array('cand.regid' => $login_candidate_id, 'cand.isdeleted' => '0'), 'cand.regid');

        if(count($candidate_data) > 0) { redirect(site_url('iibfdra/candidate/dashboard_candidate'),'refresh'); }
        else { redirect(site_url('iibfdra/candidate/login_candidate/logout'),'refresh'); }        			
      }
    }/******** END : CHECK IF CANDIDATE SESSION IS ALREADY STARTED OR NOT ON CANDIDATE LOGIN PAGE ********/
		
    /******** START : CHECK SESSION AFTER LOGIN FOR CANDIDATE PAGES ********/
		function check_candidate_session_all_pages()
		{
			$login_candidate_id = $this->session->userdata('IIBF_DRA_CANDIDATE_LOGIN_ID');
      if (!isset($login_candidate_id) || $login_candidate_id == "")
			{
        $this->session->set_flashdata('error', 'Please login again to vistit this page.');
				redirect(site_url('iibfdra/candidate/login_candidate/logout'),'refresh');
      }
			else
			{
        $candidate_data = $this->master_model->getRecords('dra_members', array('regid' => $login_candidate_id, 'isdeleted' => '0'), 'regid, hold_release');

        if(count($candidate_data) == 0) 
        { 
          $this->session->set_flashdata('error', 'Please login again to vistit this page.');
          redirect(site_url('iibfdra/candidate/login_candidate/logout'),'refresh'); 
        }
        else
        {
          if($candidate_data[0]['hold_release'] != 'Release')
          {
            $this->session->set_flashdata('error', 'You are on Hold status');
            redirect(site_url('iibfdra/candidate/login_candidate/logout'),'refresh'); 
          }
        }
      }
    }/******** END : CHECK SESSION AFTER LOGIN FOR CANDIDATE PAGES ********/

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

    public function getLoggedInUserDetails($user_id, $type) /******** START : GET LOGGED IN ADMIN, AGENCY DETAILS ********/
    {
      $disp_name = '';
      $disp_sidebar_name = '';
      if($type == 'candidate')
      {
        $disp_name = $disp_sidebar_name = 'Candidate';
        $candidate_data = $this->master_model->getRecords('dra_members', array('regid' => $user_id), 'regid, namesub, firstname, middlename, lastname');
              
        if(count($candidate_data) > 0) 
        { 
          $disp_name = $candidate_data[0]['namesub'].' '.$candidate_data[0]['firstname']; 
          if($candidate_data[0]['middlename'] != "") { $disp_name .= ' '.$candidate_data[0]['middlename']; }
          if($candidate_data[0]['lastname'] != "") { $disp_name .= ' '.$candidate_data[0]['lastname']; }

          $disp_sidebar_name = $candidate_data[0]['namesub'].' '.$candidate_data[0]['firstname'];
        }
      }
      
      $data['disp_name'] = $disp_name;
      $data['disp_sidebar_name'] = $disp_sidebar_name;
			return $data;
    }/******** END : GET LOGGED IN ADMIN, AGENCY DETAILS ********/

    function insert_user_login_logs($user_id=0, $user_type=0, $type=0) /******** START : MAINTAIN LOGIN - LOGOUT LOGS ********/
		{
      $this->load->helper('iibfdra/iibf_dra_helper');
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
			$this->master_model->insertRecord('iibfdra_login_logs',$add_log);
		} /******** END : MAINTAIN LOGIN - LOGOUT LOGS ********/
		
    //START : THIS FUNCTION IS USED TO CHECK THE CANDIDATE IS ELIGIBLE TO ADD IN BATCH OR NOT. CHECK DUPLICATION FOR MOBILE, EMAIL, ID PROOF, AADHAR
    //CANDIDATE ABLE TO ADD IN NEW BATCH IF BELOW CONDITIONS ARE TRUE
    //IF BATCH IS CANCELLED or IF 270 DAYS IS COMPLETE FROM CANDIDATE DATE OF CREATION OR CANDIDATES 3 ATTEMPT IS OVER
    function validation_check_candidate_eligibility_to_add_in_new_batch($candidate_id=0, $chk_column='', $chk_value='')
    {
      if($chk_column == 'mobile') { $this->db->where('cand.mobile_no', $chk_value); }
      else if($chk_column == 'email') { $this->db->where('cand.email_id', $chk_value); }
      else if($chk_column == 'id_proof') { $this->db->where('cand.idproof_no', $chk_value); }
      else if($chk_column == 'aadhar') { $this->db->where('cand.aadhar_no', $chk_value); }

      $this->db->order_by('cand.regid', 'DESC');
      $date_270_Days_ago = date('Y-m-d', strtotime("-270days"));
      $this->db->where(" (DATE(cand.createdon) >= '".$date_270_Days_ago."' OR btch.batch_to_date >= '".$date_270_Days_ago."') ");
      $this->db->join('agency_batch btch', 'btch.id = cand.batch_id','INNER');
      $candidate_data = $this->master_model->getRecords('dra_members cand', array('cand.isdeleted' => '0', 'cand.regid !=' => $candidate_id, 'btch.batch_status !=' => 'Cancelled', 'cand.re_attempt <'=>'3'), 'cand.regid, btch.agency_id, btch.center_id, cand.batch_id, cand.mobile_no, cand.email_id, cand.idproof_no, cand.aadhar_no, cand.createdon, btch.batch_to_date, cand.hold_release');

      if(count($candidate_data) > 0)
      {
        if($candidate_data[0]['batch_to_date'] < date("Y-m-d") && $candidate_data[0]['hold_release'] != 'Release')
        {
          $candidate_data = array(); 
        }
      }
      return $candidate_data;
    }  

    /******** START : MAINTAIN ALL GLOBAL LOGS ********/
    function insert_common_log($title='', $tbl_name='', $qry='', $pk_id='', $module_slug='', $description='', $posted_data='')
		{
      $this->load->helper('iibfdra/iibf_dra_helper');
			$this->load->helper('url');
			$this->load->library('user_agent');
      
      $user_type = $this->session->userdata('IIBF_DRA_USER_TYPE');
      $login_id = $this->session->userdata('IIBF_DRA_LOGIN_ID');

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
      $result['flag']     = 'error';
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

    function iibfdra_send_mail_common($mail_arg=array())
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
        
        $to_email = 'gaurav.shewale@esds.co.in';
        $cc_email = 'sagar.matale@esds.co.in,pre_production@esds.co.in,chetan.bhamare@esds.co.in';
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
  }				  