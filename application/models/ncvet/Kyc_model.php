<?php
  /********************************************************************************************************************
  ** Description: Common Model for BULK KYC Module
  ** Created BY: Anil S On 14-08-2025
  ********************************************************************************************************************/
	class Kyc_model extends CI_Model
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model('master_model');			
			$this->load->model('Emailsending');
    }
		
		function check_session_login()/******** START : CHECK IF ANY SESSION IS ALREADY STARTED OR NOT ON KYC LOGIN PAGE ********/
		{
			$login_user_id = $this->session->userdata('NCVET_KYC_LOGIN_ID');
			$login_user_type = $this->session->userdata('NCVET_KYC_ADMIN_TYPE');
			if(isset($login_user_id) && $login_user_id != "" && isset($login_user_type) && $login_user_type != "")
			{
        $admin_data = $this->master_model->getRecords('ncvet_kyc_admin', array('kyc_admin_id' => $login_user_id, 'is_active' => '1', 'is_deleted' => '0'), 'kyc_admin_id');

        if(count($admin_data) > 0) { redirect(site_url('ncvet/kyc/kyc_dashboard'),'refresh'); }
        else { redirect(site_url('ncvet/kyc/login/logout'),'refresh'); }			
      }
    }/******** END : CHECK IF ANY SESSION IS ALREADY STARTED OR NOT ON KYC LOGIN PAGE ********/
		
    /******** START : CHECK SESSION AFTER LOGIN FOR ALL RECOMMENDER OR APPROVER PAGES ********/
		function check_admin_session_all_pages($login_as='') //login_as = recommender or approver
		{
			$login_user_id = $this->session->userdata('NCVET_KYC_LOGIN_ID');
			$login_user_type = $this->session->userdata('NCVET_KYC_ADMIN_TYPE');

			if (!isset($login_user_id) || $login_user_id == "" || !isset($login_user_type) || $login_user_type == "")
			{
				redirect(site_url('ncvet/kyc/login/logout'),'refresh');
      }
			else
			{
        if($login_as == '') { redirect(site_url('ncvet/kyc/login/logout'),'refresh'); }
        else
        {
          if($login_as != $login_user_type) 
          {  
            if($login_user_type == '1') //Recommender
            { 
              redirect(site_url('ncvet/kyc/kyc_dashboard'),'refresh'); 
            }
            else if($login_user_type == '2') //Approver
            { 
              redirect(site_url('ncvet/kyc/kyc_dashboard'),'refresh');
            }
          }
        }

        $admin_data = $this->master_model->getRecords('ncvet_kyc_admin', array('kyc_admin_id' => $login_user_id, 'is_active' => '1', 'is_deleted' => '0'), 'kyc_admin_id');

        if(count($admin_data) == 0) 
        { 
          redirect(site_url('ncvet/kyc/login/logout'),'refresh'); 
        }        
      }
    }/******** END : CHECK SESSION AFTER LOGIN FOR ALL RECOMMENDER OR APPROVER PAGES ********/

    public function getLoggedInUserDetails($user_id, $type) /******** START : GET LOGGED IN APPROVER, RECOMMENDER DETAILS ********/
    {
      $disp_name = '';
      $disp_sidebar_name = '';
      if($type == '1') { $disp_sidebar_name = $disp_name = 'Recommender'; }
      else if($type == '2') { $disp_sidebar_name = $disp_name = 'Approver'; }
      
      $admin_data = $this->master_model->getRecords('ncvet_kyc_admin', array('kyc_admin_id' => $user_id), 'kyc_admin_id, kyc_admin_name');
              
      if(count($admin_data) > 0) { $disp_name = $admin_data[0]['kyc_admin_name']; }
      
      $data['disp_name'] = $disp_name;
      $data['disp_sidebar_name'] = $disp_sidebar_name;
			return $data;
    }/******** END : GET LOGGED IN APPROVER, RECOMMENDER DETAILS ********/

    
    
    function insert_user_login_logs($user_id=0, $user_type=0, $login_logout_type=0) /******** START : MAINTAIN LOGIN - LOGOUT LOGS ********/
		{
      $this->load->helper('ncvet/ncvet_helper');
			$this->load->helper('url');
			$this->load->library('user_agent');
			
			$add_log['user_id'] = $user_id;
      $add_log['user_type'] = $user_type;
			$add_log['ip_address'] = get_ip_address(); //general_helper.php
			$add_log['browser'] = $this->agent->browser()." - ".$this->agent->version()." - ".$this->agent->platform();
			$add_log['details'] = $_SERVER['HTTP_USER_AGENT'];
			$add_log['type'] = $login_logout_type;
			$add_log['status'] = 1;
			$add_log['created_on'] = date('Y-m-d H:i:s');
			$this->master_model->insertRecord('ncvet_kyc_login_logs',$add_log);
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
    function insert_common_log($title='', $tbl_name='', $qry='', $pk_id='', $module_slug='', $description='', $posted_data='', $module_name='', $membership_type='', $member_type='', $exam_code='', $training_id='', $regnumber='')
		{
      $this->load->helper('ncvet/ncvet_helper');
			$this->load->helper('url');
			$this->load->library('user_agent');
      
      $user_type = $this->session->userdata('NCVET_KYC_ADMIN_TYPE');
      $login_id = $this->session->userdata('NCVET_KYC_LOGIN_ID');

      if($user_type == "" || $user_type == 'NULL') { $user_type = ''; }
      if($login_id == "" || $login_id == 'NULL') { $login_id = '0'; }

			$add_log['module_slug'] = $module_slug;
			$add_log['title'] = $title;
			$add_log['tbl_name'] = $tbl_name;
			$add_log['pk_id'] = $pk_id;
			$add_log['training_id'] = $training_id;
			$add_log['regnumber'] = $regnumber;
			$add_log['module_name'] = $module_name;
			$add_log['membership_type'] = $membership_type;
			$add_log['member_type'] = $member_type;
			$add_log['exam_code'] = $exam_code;
			$add_log['class_name'] = $this->router->fetch_class();
			$add_log['method_name'] = $this->router->fetch_method();
			$add_log['current_url'] = current_url();
			$add_log['qry'] = $qry;
			$add_log['posted_data'] = $posted_data;
			$add_log['login_type'] = $user_type;
			$add_log['login_id'] = $login_id;
			$add_log['description'] = $description;
			$add_log['ip_address'] = get_ip_address(); //general_helper.php
			$this->master_model->insertRecord('ncvet_kyc_log_data',$add_log);
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

    //START : SEND NOTIFICATION FOR APPROVED / REJECTED CANDIDATE KYC EMAIL
    function send_approve_reject_kyc_email_sms($candidate_data,$module_name,$kyc_action)
    {
      $this->load->model('ncvet/Ncvet_model');

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

        $username = $candidate_data[0]['regnumber'];
        $password = $this->Ncvet_model->password_decryption($candidate_data[0]['password']);
        $login_url = base_url('ncvet/candidate/login_candidate');

        $mail_arg = array();

        //KYC REJECTED FOR 
        $mail_arg['subject'] = str_replace("#KYC_ACTION#", $kyc_action, $emailer_data[0]['subject']);
        $mail_arg['to_email'] = 'Chetan.Bhamare@esds.co.in'; //$candidate_data[0]['email_id']; //'sagar.matale@esds.co.in'; 
        $mail_arg['to_name'] = $candidate_data[0]['first_name']; //'sagar'; //
        $mail_arg['cc_email'] = 'Gaurav.Shewale@esds.co.in,anil.s@esds.co.in,Shweta.Pingale@esds.co.in,Priyanka.Dhikale@esds.co.in';//sagar.matale@esds.co.in,anil.s@esds.co.in

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
        $this->Ncvet_model->ncvet_send_mail_common($mail_arg);

        
      }
    }//END : SEND NOTIFICATION FOR APPROVED / REJECTED CANDIDATE KYC EMAIL

  }				  