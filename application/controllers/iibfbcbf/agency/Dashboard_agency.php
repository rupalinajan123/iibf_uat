<?php 
  /********************************************************************************************************************
  ** Description: Controller for BCBF Agency DASHBOARD
  ** Created BY: Sagar Matale On 11-10-2023
  ********************************************************************************************************************/
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Dashboard_agency extends CI_Controller 
	{
		public function __construct()
		{
			parent::__construct();
			
			$this->load->model('master_model');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
      
      $this->login_agency_or_centre_id = $this->session->userdata('IIBF_BCBF_LOGIN_ID');
      $this->login_user_type = $this->session->userdata('IIBF_BCBF_USER_TYPE');
      
      if($this->login_user_type != 'agency' && $this->login_user_type != 'centre') { $this->login_user_type = 'invalid'; }
			$this->Iibf_bcbf_model->check_admin_session_all_pages($this->login_user_type); // If admin session is not started then redirect to logout
    }
        
    public function index()
		{   
			$data['act_id'] = "Dashboard";
			$data['sub_act_id'] = "";

      if($this->login_user_type == "agency") 
      { 
        $data['page_title'] = 'IIBF - BCBF Agency Dashboard'; 
        
        $this->db->join('state_master sm', 'sm.state_code = am.agency_state', 'LEFT');
        $this->db->join('city_master cm', 'cm.id = am.agency_city', 'LEFT');
        $this->db->where('am.agency_id',$this->login_agency_or_centre_id);
        $data['form_data'] = $form_data = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.is_deleted' => '0'), "am.*, IF(am.is_active=1, 'ACTIVE', 'DEACTIVE') AS AgencyStatus, sm.state_name, cm.city_name");
        
        //START : DASHBOARD COUNT DATA
        $all_centre_data = $this->Iibf_bcbf_model->get_total_centre_data($this->login_agency_or_centre_id);
        $data['total_centre_cnt'] = $all_centre_data['total_centre_cnt'];
        $data['total_active_centre_cnt'] = $all_centre_data['total_active_centre_cnt'];
        $data['total_in_active_centre_cnt'] = $all_centre_data['total_in_active_centre_cnt'];
        $data['total_in_review_centre_cnt'] = $all_centre_data['total_in_review_centre_cnt'];
        $data['total_re_submitted_centre_cnt'] = $all_centre_data['total_re_submitted_centre_cnt'];
        
        if(count($form_data) > 0 && ($form_data[0]['allow_exam_types'] == 'Bulk/Individual' || $form_data[0]['allow_exam_types'] == 'Hybrid'))
        {
          $all_batch_data = $this->Iibf_bcbf_model->get_total_batch_data($this->login_agency_or_centre_id);
          $data['total_batch_cnt'] = $all_batch_data['total_batch_cnt'];
          $data['total_completed_batch_cnt'] = $all_batch_data['total_completed_batch_cnt'];
          $data['total_ongoing_batch_cnt'] = $all_batch_data['total_ongoing_batch_cnt'];
          $data['total_upcoming_batch_cnt'] = $all_batch_data['total_upcoming_batch_cnt'];
          $data['total_rejected_hold_cancelled_batch_cnt'] = $all_batch_data['total_rejected_hold_cancelled_batch_cnt'];

          $all_faculty_data = $this->Iibf_bcbf_model->get_total_faculty_data($this->login_agency_or_centre_id);
          $data['total_faculty_cnt'] = $all_faculty_data['total_faculty_cnt'];
          $data['total_active_faculty_cnt'] = $all_faculty_data['total_active_faculty_cnt'];
          $data['total_in_active_faculty_cnt'] = $all_faculty_data['total_in_active_faculty_cnt'];
          $data['total_in_review_faculty_cnt'] = $all_faculty_data['total_in_review_faculty_cnt'];
          $data['total_re_submitted_faculty_cnt'] = $all_faculty_data['total_re_submitted_faculty_cnt'];

          $all_candidate_data = $this->Iibf_bcbf_model->get_total_candidate_data($this->login_agency_or_centre_id);
          $data['total_candidate_cnt'] = $all_candidate_data['total_candidate_cnt'];
          $data['total_training_completed_candidate_cnt'] = $all_candidate_data['total_training_completed_candidate_cnt'];
          $data['total_hold_candidate_cnt'] = $all_candidate_data['total_hold_candidate_cnt'];
          $data['total_exam_applied_candidate_cnt'] = $all_candidate_data['total_exam_applied_candidate_cnt'];
        }
        //END : DASHBOARD COUNT DATA
      }
      else if($this->login_user_type == "centre") 
      { 
        $data['page_title'] = 'IIBF - BCBF Centre Dashboard'; 

        //START : FOR CSC WALLET PAYMENT
        $this->session->set_userdata('non_memberdata', ''); 
        $this->session->set_userdata('memtype', ''); 
        $this->session->set_userdata('csctype', ''); 
        $this->session->set_userdata('csc_id', ''); 
        //END : FOR CSC WALLET PAYMENT
      }

      $this->load->view('iibfbcbf/agency/dashboard_agency', $data);
    }
    
    /******** START : CHANGE AGENCY PASSWORD ********/
    function change_password()
		{   
      $data['act_id'] = "Profile Settings";
			$data['sub_act_id'] = "Change Password";
      $log_slug = '';

      if($this->login_user_type == "agency") 
      { 
        $data['page_title'] = 'IIBF - BCBF Agency Change Password'; 

        $data['form_data'] = $form_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('agency_id' => $this->login_agency_or_centre_id), 'agency_id, is_active, is_deleted');

        $log_slug = 'agency_password_action';
      }
      else if($this->login_user_type == "centre") 
      { 
        $data['page_title'] = 'IIBF - BCBF Centre Change Password'; 

        $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = cm.agency_id', 'INNER');
        $data['form_data'] = $form_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.centre_id' => $this->login_agency_or_centre_id), 'cm.centre_id, cm.status, cm.is_deleted, am.allow_exam_types');
        if(count($form_data) > 0 && $form_data[0]['allow_exam_types'] == 'CSC')
        {
          $this->session->set_flashdata('error','You do not have permission to access Change Password Module');
          redirect(site_url('iibfbcbf/agency/dashboard_agency'));
        }

        $log_slug = 'centre_password_action';
      }      
      			
			if(isset($_POST) && count($_POST) > 0)
			{ 
				$this->form_validation->set_rules('current_pass_agency', 'Current Password', 'trim|required|xss_clean|callback_validation_check_old_password',array('required' => 'Please enter %s'));
				$this->form_validation->set_rules('new_pass_agency', 'New Password', 'trim|required|callback_fun_validate_password|xss_clean|callback_validation_check_new_password',array('required' => 'Please enter %s', 'min_length' => 'Please enter minimum 8 characters in length'));
				$this->form_validation->set_rules('confirm_pass_agency', 'Confirm Password', 'trim|required|min_length[8]|callback_validation_check_password|xss_clean',array('required' => 'Please enter %s', 'min_length' => 'Please enter minimum 8 characters in length'));
				
				if($this->form_validation->run())		
				{
          $posted_arr = json_encode($_POST);
          $agency_or_centre_name = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->login_agency_or_centre_id, $this->login_user_type);

          if($this->login_user_type == "agency")
          {
            $up_data['agency_password'] = $this->Iibf_bcbf_model->password_encryption($this->input->post('new_pass_agency'));
            $up_data['updated_on'] = date("Y-m-d H:i:s");
            $up_data['updated_by'] = $this->login_agency_or_centre_id;
            $this->master_model->updateRecord('iibfbcbf_agency_master', $up_data, array('agency_id' => $this->login_agency_or_centre_id));
            
            $this->Iibf_bcbf_model->insert_common_log('Agency : Profile password updated', 'iibfbcbf_agency_master', $this->db->last_query(), $this->login_agency_or_centre_id, $log_slug, 'The agency '.$agency_or_centre_name['disp_name'].' has successfully updated the password', $posted_arr); 
          }
          else if($this->login_user_type == "centre")
          {
            $up_data['centre_password'] = $this->Iibf_bcbf_model->password_encryption($this->input->post('new_pass_agency'));
            $up_data['updated_on'] = date("Y-m-d H:i:s");
            $up_data['updated_by'] = $this->login_agency_or_centre_id;
            $this->master_model->updateRecord('iibfbcbf_centre_master', $up_data, array('centre_id' => $this->login_agency_or_centre_id));
            
            $this->Iibf_bcbf_model->insert_common_log('Centre : Profile password updated', 'iibfbcbf_centre_master', $this->db->last_query(), $this->login_agency_or_centre_id, $log_slug, 'The centre '.$agency_or_centre_name['disp_name'].' has successfully updated the password', $posted_arr); 
          }

					$this->session->set_flashdata('success','Password successfully updated');
					
          redirect(site_url('iibfbcbf/agency/dashboard_agency/change_password'));
				}
			}
      
      $data['log_slug'] = $log_slug;
      $this->load->view('iibfbcbf/agency/change_password_agency', $data);
		}/******** END : CHANGE AGENCY PASSWORD ********/
		
    /******** START : VALIDATION FUNCTION TO CHECK THE OLD PASSWORD ********/
		function validation_check_old_password($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
		{
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['current_pass_agency'] != "")
			{
        if($type == '1') { $current_pass_agency = $this->security->xss_clean($this->input->post('current_pass_agency')); }
        else if($type == '0') { $current_pass_agency = $str; }        
								
				$enc_password = $this->Iibf_bcbf_model->password_encryption($current_pass_agency);

        if($this->login_user_type == "agency") 
        { 
          if(count($this->master_model->getRecords('iibfbcbf_agency_master', array('agency_password' => $enc_password, 'agency_id' => $this->login_agency_or_centre_id, 'is_active' => '1', 'is_deleted' => '0'), 'agency_id, is_active, is_deleted')) > 0)
          {
            $return_val_ajax = 'true';
          }
        }
        else if($this->login_user_type == "centre") 
        { 
          if(count($this->master_model->getRecords('iibfbcbf_centre_master', array('centre_password' => $enc_password, 'centre_id' => $this->login_agency_or_centre_id, 'status' => '1', 'is_deleted' => '0'), 'centre_id, status, is_deleted')) > 0)
          {
            $return_val_ajax = 'true';
          }
        }        
			}

      if($type == '1') { echo $return_val_ajax; }
      else if($type == '0') 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['current_pass_agency'] != "")
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
			if(isset($_POST) && $_POST['confirm_pass_agency'] != "")
			{
        $new_pass_agency = $this->security->xss_clean($this->input->post('new_pass_agency'));
        if($type == '1') { $confirm_pass_agency = $this->security->xss_clean($this->input->post('confirm_pass_agency')); }
        else if($type == '0') { $confirm_pass_agency = $str; }   
        
        if($new_pass_agency == $confirm_pass_agency)
        {
          $return_val_ajax = 'true';
        }
			}
      
      if($type == '1') { echo $return_val_ajax; }
      else if($type == '0') 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['confirm_pass_agency'] != "")
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
			
      if(isset($_POST) && $_POST['new_pass_agency'] != "")
			{
        $current_pass_agency = $this->security->xss_clean($this->input->post('current_pass_agency'));
        if($type == '1') { $new_pass_agency = $this->security->xss_clean($this->input->post('new_pass_agency')); }
        else if($type == '0') { $new_pass_agency = $str; } 
        
        if (preg_match('/[A-Z]/', $new_pass_agency) && preg_match('/[a-z]/', $new_pass_agency) && preg_match('/[0-9]/', $new_pass_agency))
        {
          if($current_pass_agency != $new_pass_agency)
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
        else if($_POST['new_pass_agency'] != "")
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
      $log_slug = '';

      if($this->login_user_type == "agency") 
      { 
        $data['page_title'] = 'IIBF - BCBF Agency View Profile'; 

        $this->db->join('state_master sm', 'sm.state_code = am.agency_state', 'LEFT');
        $this->db->join('city_master cm', 'cm.id = am.agency_city', 'LEFT');
        $this->db->where('am.agency_id',$this->login_agency_or_centre_id);
        $data['form_data'] = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.is_deleted' => '0'), "am.*, IF(am.is_active=1, 'ACTIVE', 'DEACTIVE') AS AgencyStatus, sm.state_name, cm.city_name");
        
        $log_slug = 'agency_action';
      }
      else if($this->login_user_type == "centre") 
      { 
        $data['page_title'] = 'IIBF - BCBF Centre View Profile'; 

        $this->db->join('state_master sm', 'sm.state_code = cm.centre_state', 'LEFT');
        $this->db->join('city_master cmm', 'cmm.id = cm.centre_city', 'LEFT');
        $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = cm.agency_id', 'LEFT');
        $data['form_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.centre_id' => $this->login_agency_or_centre_id, 'cm.is_deleted' => '0'), "cm.*, IF(cm.centre_type=1, 'Regular', IF(cm.centre_type=2, 'Temporary', '')) AS DispCentreType, IF(cm.status=0, 'Deactive', IF(cm.status=1, 'Active', IF(cm.status=2, 'In Review', 'Re-submitted'))) AS DispStatus, sm.state_name, cmm.city_name, am.agency_name, am.agency_code, am.allow_exam_types");

        $log_slug = 'centre_action';
      }
      
      $data['log_slug'] = $log_slug;
      $this->load->view('iibfbcbf/agency/view_profile_agency', $data);
		}/******** END : VIEW PROFILE ********/
  }