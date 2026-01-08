<?php 
  /********************************************************************************************************************
  ** Description: Controller for BCBF INSPECTOR DASHBOARD
  ** Created BY: Sagar Matale On 09-01-2024
  ********************************************************************************************************************/
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Dashboard_inspector extends CI_Controller 
	{
		public function __construct()
		{
			parent::__construct();
			
			$this->load->model('master_model');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
      
      $this->login_inspector_id = $this->session->userdata('IIBF_BCBF_LOGIN_ID');
      $this->login_user_type = $this->session->userdata('IIBF_BCBF_USER_TYPE');
      
      if($this->login_user_type != 'inspector') { $this->login_user_type = 'invalid'; }
			$this->Iibf_bcbf_model->check_admin_session_all_pages($this->login_user_type); // If admin session is not started then redirect to logout
    }
        
    public function index()
		{   
			$data['act_id'] = "Dashboard";
			$data['sub_act_id'] = "";
      $data['page_title'] = 'IIBF - BCBF Inspector Dashboard';

      $inspection_count_data = $this->Iibf_bcbf_model->get_inspection_count_data($this->login_inspector_id);
      $data['total_inspection_cnt'] = $inspection_count_data['total_inspection_cnt'];
      $data['total_upcoming_inspection_cnt'] = $inspection_count_data['total_upcoming_inspection_cnt'];
      $data['total_ongoing_inspection_cnt'] = $inspection_count_data['total_ongoing_inspection_cnt'];
      $data['total_completed_inspection_cnt'] = $inspection_count_data['total_completed_inspection_cnt'];
      $data['total_re_inspection_cnt'] = $inspection_count_data['total_re_inspection_cnt'];
      $data['total_missed_inspection_cnt'] = $inspection_count_data['total_missed_inspection_cnt'];

      $this->load->view('iibfbcbf/inspector/dashboard_inspector', $data);
    }
    
    /******** START : CHANGE INSPECTOR PASSWORD ********/
    function change_password()
		{   
      $data['act_id'] = "Profile Settings";
			$data['sub_act_id'] = "Change Password";
            
      $data['page_title'] = 'IIBF - BCBF Inspector Change Password'; 
      $log_slug = 'inspector_password_action';

      $data['form_data'] = $form_data = $this->master_model->getRecords('iibfbcbf_inspector_master', array('inspector_id' => $this->login_inspector_id), 'inspector_id, is_active, is_deleted');
      			
			if(isset($_POST) && count($_POST) > 0)
			{ 
				$this->form_validation->set_rules('current_pass_inspector', 'Current Password', 'trim|required|xss_clean|callback_validation_check_old_password',array('required' => 'Please enter %s'));
				$this->form_validation->set_rules('new_pass_inspector', 'New Password', 'trim|required|callback_fun_validate_password|xss_clean|callback_validation_check_new_password',array('required' => 'Please enter %s', 'min_length' => 'Please enter minimum 8 characters in length'));
				$this->form_validation->set_rules('confirm_pass_inspector', 'Confirm Password', 'trim|required|min_length[8]|callback_validation_check_password|xss_clean',array('required' => 'Please enter %s', 'min_length' => 'Please enter minimum 8 characters in length'));
				
				if($this->form_validation->run())		
				{
          $posted_arr = json_encode($_POST);
          $inspector_name = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->login_inspector_id, $this->login_user_type);
          
          $up_data['inspector_password'] = $this->Iibf_bcbf_model->password_encryption($this->input->post('new_pass_inspector'));
          $up_data['updated_on'] = date("Y-m-d H:i:s");
          $up_data['updated_by'] = $this->login_inspector_id;
          $this->master_model->updateRecord('iibfbcbf_inspector_master', $up_data, array('inspector_id' => $this->login_inspector_id));
          
          $this->Iibf_bcbf_model->insert_common_log('Inspector : Profile password updated', 'iibfbcbf_inspector_master', $this->db->last_query(), $this->login_inspector_id, $log_slug, 'The inspector '.$inspector_name['disp_name'].' has successfully updated the password', $posted_arr);

					$this->session->set_flashdata('success','Password successfully updated');
					
          redirect(site_url('iibfbcbf/inspector/dashboard_inspector/change_password'));
				}
			}
      
      $data['log_slug'] = $log_slug;
      $this->load->view('iibfbcbf/inspector/change_password_inspector', $data);
		}/******** END : CHANGE INSPECTOR PASSWORD ********/
		
    /******** START : VALIDATION FUNCTION TO CHECK THE OLD PASSWORD ********/
		function validation_check_old_password($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
		{
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['current_pass_inspector'] != "")
			{
        if($type == '1') { $current_pass_inspector = $this->security->xss_clean($this->input->post('current_pass_inspector')); }
        else if($type == '0') { $current_pass_inspector = $str; }        
								
				$enc_password = $this->Iibf_bcbf_model->password_encryption($current_pass_inspector);

        if(count($this->master_model->getRecords('iibfbcbf_inspector_master', array('inspector_password' => $enc_password, 'inspector_id' => $this->login_inspector_id, 'is_active' => '1', 'is_deleted' => '0'), 'inspector_id, is_active, is_deleted')) > 0)
        {
          $return_val_ajax = 'true';
        }      
			}

      if($type == '1') { echo $return_val_ajax; }
      else if($type == '0') 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['current_pass_inspector'] != "")
        {
          $this->form_validation->set_message('validation_check_old_password','Please enter correct old password');
          return false;
        }
      }
		}/******** END : VALIDATION FUNCTION TO CHECK THE OLD PASSWORD ********/
		
    /******** START : VALIDATION FUNCTION TO CHECK THE NEW PASSWORD & CONFIRM PASSWORD IS SAME OR NOT ********/
		function validation_check_password($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
		{
      $return_val_ajax = 'false';
      $msg = 'Please enter the Confirm Password to match the New Password';
			if(isset($_POST) && $_POST['confirm_pass_inspector'] != "")
			{
        $new_pass_inspector = $this->security->xss_clean($this->input->post('new_pass_inspector'));
        if($type == '1') { $confirm_pass_inspector = $this->security->xss_clean($this->input->post('confirm_pass_inspector')); }
        else if($type == '0') { $confirm_pass_inspector = $str; }   
        
        if($new_pass_inspector == $confirm_pass_inspector)
        {
          $return_val_ajax = 'true';
        }
			}
      
      if($type == '1') { echo $return_val_ajax; }
      else if($type == '0') 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['confirm_pass_inspector'] != "")
        {
          $this->form_validation->set_message('validation_check_password',$msg);
          return false;
        }
      }
		}/******** END : VALIDATION FUNCTION TO CHECK THE NEW PASSWORD & CONFIRM PASSWORD IS SAME OR NOT ********/

    /******** START : VALIDATION FUNCTION TO CHECK VALID PASSWORD ********/
    function fun_validate_password($str) // Custom callback function for check valid PASSWORD
    {
      if($str != '')
      {
        $password_length = strlen($str);
        $err_msg = '';
        if($password_length < 8) { $err_msg = 'Please enter minimum 8 characters in password'; }
        else if($password_length > 20) { $err_msg = 'Please enter maximum 20 characters in password'; }

        if($err_msg != "")
        {
          $this->form_validation->set_message('fun_validate_password', $err_msg);
          return false;
        }
        else
        {
          $result = $this->Iibf_bcbf_model->fun_validate_password($str); 
          if($result['flag'] == 'success') { return true; }
          else
          {
            $this->form_validation->set_message('fun_validate_password', $result['response']);
            return false;
          }
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK VALID PASSWORD ********/
		
    /******** START : VALIDATION FUNCTION TO CHECK THE NEW PASSWORD IS DIFFERENT FROM CURRENT PASSWORD ********/
		function validation_check_new_password($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
		{
      $return_val_ajax = 'false';
      $msg = 'New password must be different from Current password';
			
      if(isset($_POST) && $_POST['new_pass_inspector'] != "")
			{
        $current_pass_inspector = $this->security->xss_clean($this->input->post('current_pass_inspector'));
        if($type == '1') { $new_pass_inspector = $this->security->xss_clean($this->input->post('new_pass_inspector')); }
        else if($type == '0') { $new_pass_inspector = $str; } 
        
        if (preg_match('/[A-Z]/', $new_pass_inspector) && preg_match('/[a-z]/', $new_pass_inspector) && preg_match('/[0-9]/', $new_pass_inspector))
        {
          if($current_pass_inspector != $new_pass_inspector)
          {
            $return_val_ajax = 'true';
          }
        }
        else
        {
          $msg = 'Password must contain at least one upper-case character, one lower-case character, one digit and one special character';
        }
			}
      
      if($type == '1') { echo $return_val_ajax; }
      else if($type == '0') 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['new_pass_inspector'] != "")
        {
          $this->form_validation->set_message('validation_check_new_password',$msg);
          return false;
        }
      }
		}/******** END : VALIDATION FUNCTION TO CHECK THE NEW PASSWORD IS DIFFERENT FROM CURRENT PASSWORD ********/

    /******** START : VIEW PROFILE ********/
    function view_profile()
		{   
      $data['act_id'] = "Profile Settings";
			$data['sub_act_id'] = "View Profile";
      
      $data['page_title'] = 'IIBF - BCBF Centre View Profile'; 

      $data['form_data'] = $this->master_model->getRecords('iibfbcbf_inspector_master im', array('im.inspector_id'=>$this->login_inspector_id, 'im.is_deleted' => '0'), "*, IF(im.batch_online_offline_flag=1, 'Offline', 'Online') AS DispType, IF(im.is_active=0, 'In-Active', 'Active') AS DispStatus, (SELECT GROUP_CONCAT(sm.state_name SEPARATOR ', ') FROM state_master sm WHERE FIND_IN_SET(sm.state_code,im.state_codes)) AS AssignedStates, (SELECT GROUP_CONCAT(cm.city_name SEPARATOR ', ') FROM iibfbcbf_inspector_centres imc INNER JOIN city_master cm ON cm.id = imc.city WHERE imc.inspector_id = im.inspector_id) AS AssignedCities");
      
      $this->load->view('iibfbcbf/inspector/view_profile_inspector', $data);
		}/******** END : VIEW PROFILE ********/
    
  }