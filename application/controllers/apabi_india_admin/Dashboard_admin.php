<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Dashboard_admin extends CI_Controller 
	{
		public function __construct()
		{
			parent::__construct();
			
			$this->load->model('master_model');
      $this->load->model('Apabi_india_model');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper');

      $this->login_admin_id = $this->session->userdata('APABI_INDIA_ADMIN_LOGIN_ID');
      $this->Apabi_india_model->check_apabi_session_all_pages(); // If admin session is not started then redirect to logout
    }
    
    public function index()
		{   
      $data['act_id'] = "Dashboard";
			$data['sub_act_id'] = "";
      $data['page_title'] = 'APABI INDIA Admin Dashboard';
      
      $this->load->view('apabi_india_admin/dashboard_admin', $data);
    }

    /******** START : CHANGE ADMIN PASSWORD ********/
    function change_password()
		{   
      $data['act_id'] = "Change Password";
			$data['sub_act_id'] = "Change Password";
      $log_slug = '';

      $data['page_title'] = 'IIBF - APABI INDIA Admin Change Password'; 

      $data['form_data'] = $form_data = $this->master_model->getRecords('apabi_india_admin', array('admin_id' => $this->login_admin_id), 'admin_id, is_active, is_deleted');

      $log_slug = 'admin_self_password_action';
      			
			if(isset($_POST) && count($_POST) > 0)
			{   
        $this->form_validation->set_rules('current_pass_admin', 'Current Password', 'trim|required|xss_clean|callback_validation_check_old_password',array('required' => 'Please enter %s'));
				$this->form_validation->set_rules('new_pass_admin', 'New Password', 'trim|required|callback_fun_validate_password|xss_clean|callback_validation_check_new_password',array('required' => 'Please enter %s', 'min_length' => 'Please enter minimum 8 characters in length'));
				$this->form_validation->set_rules('confirm_pass_admin', 'Confirm Password', 'trim|required|min_length[8]|callback_validation_check_password|xss_clean',array('required' => 'Please enter %s', 'min_length' => 'Please enter minimum 8 characters in length'));
        
				if($this->form_validation->run())		
				{ 
          $posted_arr = json_encode($_POST);
          $admin_name = $this->Apabi_india_model->getLoggedInUserDetails($this->login_admin_id, 'admin');
          
          $up_data['admin_password'] = $this->Apabi_india_model->password_encryption($this->input->post('new_pass_admin'));
          $up_data['updated_on'] = date("Y-m-d H:i:s");
          $up_data['updated_by'] = $this->login_admin_id;
          $this->master_model->updateRecord('apabi_india_admin', $up_data, array('admin_id' => $this->login_admin_id));
          
          $this->Apabi_india_model->insert_common_log('APABI INDIA Admin : Profile password updated', 'apabi_india_admin', $this->db->last_query(), $this->login_admin_id,'admin_self_password_action','The admin '.$admin_name['disp_name'].' has successfully updated the password', $posted_arr); 
					$this->session->set_flashdata('success','Password successfully updated');
					
          redirect(site_url('apabi_india_admin/dashboard_admin/change_password'));
				}
			}
			
      $data["enc_login_admin_id"] = url_encode($this->login_admin_id);
      //$data['log_data'] = $this->master_model->getRecords('iibfbcbf_logs', array('module_slug' => $log_slug, 'pk_id' => $this->login_admin_id), 'log_id, module_slug, description, created_on', array('created_on'=>'ASC'));
			$this->load->view('apabi_india_admin/change_password_admin', $data);
		}/******** END : CHANGE ADMIN PASSWORD ********/
		
    /******** START : VALIDATION FUNCTION TO CHECK THE OLD PASSWORD ********/
		function validation_check_old_password($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
		{
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['current_pass_admin'] != "")
			{
        if($type == '1') { $current_pass_admin = $this->security->xss_clean($this->input->post('current_pass_admin')); }
        else if($type == '0') { $current_pass_admin = $str; }        
								
				$enc_password = $this->Apabi_india_model->password_encryption($current_pass_admin);

        if(count($this->master_model->getRecords('apabi_india_admin', array('admin_password' => $enc_password, 'admin_id' => $this->login_admin_id, 'is_active' => '1', 'is_deleted' => '0'), 'admin_id, is_active, is_deleted')) > 0)
        {
          $return_val_ajax = 'true';
        }      
			}

      if($type == '1') { echo $return_val_ajax; }
      else if($type == '0') 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['current_pass_admin'] != "")
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
			if(isset($_POST) && $_POST['confirm_pass_admin'] != "")
			{
        $new_pass_admin = $this->security->xss_clean($this->input->post('new_pass_admin'));
        if($type == '1') { $confirm_pass_admin = $this->security->xss_clean($this->input->post('confirm_pass_admin')); }
        else if($type == '0') { $confirm_pass_admin = $str; }   
        
        if($new_pass_admin == $confirm_pass_admin)
        {
          $return_val_ajax = 'true';
        }
			}
      
      if($type == '1') { echo $return_val_ajax; }
      else if($type == '0') 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['confirm_pass_admin'] != "")
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
          $result = $this->Apabi_india_model->fun_validate_password($str); 
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
			
      if(isset($_POST) && $_POST['new_pass_admin'] != "")
			{
        $current_pass_admin = $this->security->xss_clean($this->input->post('current_pass_admin'));
        if($type == '1') { $new_pass_admin = $this->security->xss_clean($this->input->post('new_pass_admin')); }
        else if($type == '0') { $new_pass_admin = $str; } 
        
        if (preg_match('/[A-Z]/', $new_pass_admin) && preg_match('/[a-z]/', $new_pass_admin) && preg_match('/[0-9]/', $new_pass_admin))
        {
          if($current_pass_admin != $new_pass_admin)
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
        else if($_POST['new_pass_admin'] != "")
        {
          $this->form_validation->set_message('validation_check_new_password',$msg);
          return false;
        }
      }
		}/******** END : VALIDATION FUNCTION TO CHECK THE NEW PASSWORD IS DIFFERENT FROM CURRENT PASSWORD ********/		
  }	    