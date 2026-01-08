<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Benchmark_kyc_dashboard extends CI_Controller 
	{
		public function __construct()
		{
			parent::__construct();
			
			$this->load->model('master_model');
      $this->load->model('ncvet/Kyc_model');
      //$this->load->model('ncvet/Ncvet_model');
      $this->load->helper('ncvet/ncvet_helper'); 

      $this->login_admin_id = $this->session->userdata('NCVET_KYC_LOGIN_ID');
      $this->login_user_type = $this->session->userdata('NCVET_KYC_ADMIN_TYPE');
      $this->Kyc_model->check_admin_session_all_pages($this->login_user_type); // If admin session is not started then redirect to logout
    }
    
    public function index()
		{   
			$data['main_act_id'] = "Benchmark KYC";
      $data['act_id'] = "Benchmark KYC Dashboard";
			$data['sub_act_id'] = "";
      $data['page_title'] = 'NCVET - Benchmark KYC Dashboard';

      $total_enrolled_new = $total_pending_new = $total_recommend_new = $total_approved_new = $total_rejected_new = $total_enrolled_edited = $total_pending_edited = $total_recommend_edited = $total_approved_edited = $total_rejected_edited = 0;

      // New Candidate
      $total_enrolled_new = $this->dashboard_counts('enrolled','new');
      $total_pending_new = $this->dashboard_counts('pending','new');
      $total_recommend_new = $this->dashboard_counts('recommend','new');
      $total_approved_new = $this->dashboard_counts('approved','new');
      $total_rejected_new = $this->dashboard_counts('rejected','new'); 
      $data['total_enrolled_new'] = $total_enrolled_new;
      $data['total_pending_new'] = $total_pending_new;
      $data['total_recommend_new'] = $total_recommend_new;
      $data['total_approved_new'] = $total_approved_new;
      $data['total_rejected_new'] = $total_rejected_new;

      // Edit Candidate
      $total_enrolled_edited = $this->dashboard_counts('enrolled','edited');
      $total_pending_edited = $this->dashboard_counts('pending','edited');
      $total_recommend_edited = $this->dashboard_counts('recommend','edited');
      $total_approved_edited = $this->dashboard_counts('approved','edited');
      $total_rejected_edited = $this->dashboard_counts('rejected','edited'); 
      $data['total_enrolled_edited'] = $total_enrolled_edited;
      $data['total_pending_edited'] = $total_pending_edited;
      $data['total_recommend_edited'] = $total_recommend_edited;
      $data['total_approved_edited'] = $total_approved_edited;
      $data['total_rejected_edited'] = $total_rejected_edited;
      
      $this->load->view('ncvet/kyc/benchmark_kyc_dashboard', $data);
    }

    public function dashboard_counts($kyc_status_list='',$kyc_type=''){
  
      $tot_cnt = 0; 

      $this->db->where("nc.kyc_eligible_date != '' AND nc.kyc_eligible_date IS NOT NULL AND nc.kyc_eligible_date != '0000-00-00 00:00:00' AND nc.benchmark_disability = 'Y'");

      if($kyc_type == 'new'){

        if($kyc_status_list != "enrolled"){ 
          $this->db->where("(nc.img_ediited_on = '' OR nc.img_ediited_on IS NULL OR nc.img_ediited_on = '0000-00-00 00:00:00')"); 
        } 
        
      }else if($kyc_type == 'edited'){       
        $this->db->where("(nc.img_ediited_on != '' AND nc.img_ediited_on IS NOT NULL AND nc.img_ediited_on != '0000-00-00 00:00:00')"); 
      } 

      if($kyc_status_list == "enrolled")
      {
        if($this->login_user_type == '1') //Recommender
        { 
          if($kyc_type == 'new'){

          }
          else{
            $this->db->where("nc.recommender_id = '0' AND (nc.kyc_recommender_status IS NULL OR nc.kyc_recommender_status = '' OR nc.kyc_recommender_status = '0') AND nc.approver_id = '0' AND nc.kyc_status IN(0,2) ");
          }
        }
        else if($this->login_user_type == '2') //Approver
        {
          //$this->db->where('recommender_id != ', $this->login_related_id);  
          $this->db->where("nc.approver_id = '0' AND (nc.kyc_approver_status IS NULL OR nc.kyc_approver_status = '' OR nc.kyc_approver_status = '0') AND nc.recommender_id > 0 AND nc.kyc_recommender_status = '2' AND nc.kyc_status IN(1) ");
        } 
      }
      else if($kyc_status_list == "pending")
      {
        if($this->login_user_type == '1') //Recommender
        { 
          //$this->db->where("nc.recommender_id = '0' AND (nc.kyc_recommender_status IS NULL OR nc.kyc_recommender_status = '' OR nc.kyc_recommender_status = '0') AND nc.approver_id = '0' AND nc.kyc_status IN(0,2) AND DATE(nc.created_on) < '".date("Y-m-d")."' ");
          $this->db->where("nc.recommender_id = '0' AND (nc.kyc_recommender_status IS NULL OR nc.kyc_recommender_status = '' OR nc.kyc_recommender_status = '0') AND nc.approver_id = '0' AND nc.kyc_status IN(0,2) ");
        }
        else if($this->login_user_type == '2') //Approver
        {
          //$this->db->where('recommender_id != ', $this->login_related_id);  
          $this->db->where("nc.approver_id = '0' AND (nc.kyc_approver_status IS NULL OR nc.kyc_approver_status = '' OR nc.kyc_approver_status = '0') AND nc.recommender_id > 0 AND nc.kyc_recommender_status = '2' AND nc.kyc_status IN(1) AND DATE(nc.created_on) < '".date("Y-m-d")."' ");
        } 
      }
      else if($kyc_status_list == "recommend")
      {
        if($this->login_user_type == '1') //Recommender
        { 
          $this->db->where("nc.recommender_id = '".$this->login_admin_id."' AND nc.kyc_recommender_status = '1' AND nc.approver_id = '0' AND nc.kyc_status IN(1) ");
        }
        else if($this->login_user_type == '2') //Approver
        {
          //$this->db->where('recommender_id != ', $this->login_related_id); 
          $this->db->where("nc.approver_id = '".$this->login_admin_id."' AND nc.kyc_approver_status= '1' AND nc.recommender_id > 0 AND nc.kyc_recommender_status = '2' AND nc.kyc_status IN(1) ");
        } 
      }
      else if($kyc_status_list == "approved")
      {
        if($this->login_user_type == '1') //Recommender
        {  
          $this->db->where("nc.recommender_id = '".$this->login_admin_id."' AND nc.kyc_recommender_status = '2' AND nc.kyc_status IN(1) ");
        }
        else if($this->login_user_type == '2') //Approver
        { 
          $this->db->where("nc.approver_id = '".$this->login_admin_id."' AND nc.kyc_approver_status= '2' AND nc.recommender_id > 0 AND nc.kyc_recommender_status = '2' AND nc.kyc_status IN(2) ");
        }
      }
      else if($kyc_status_list == "rejected")
      {
        if($this->login_user_type == '1') //Recommender
        { 
          $this->db->where("nc.recommender_id = '".$this->login_admin_id."' AND nc.kyc_recommender_status = '3' AND nc.kyc_status IN(3) ");
        }
        else if($this->login_user_type == '2') //Approver
        {
          $this->db->where("nc.approver_id = '".$this->login_admin_id."' AND nc.kyc_approver_status= '3' AND nc.recommender_id > 0 AND nc.kyc_recommender_status = '2' AND nc.kyc_status IN(3) ");
        }
      } 

      $total_rec = $this->master_model->getRecords('ncvet_candidates nc', array('nc.regnumber !=' => '', 'nc.is_deleted'=>'0'));
      $tot_cnt = count($total_rec);  
      return $tot_cnt;
    }

    /******** START : CHANGE ADMIN PASSWORD ********/
    function change_password()
		{   
      $data['act_id'] = "Change Password";
			$data['sub_act_id'] = "Change Password";
      $log_slug = '';
      $logged_in_type = '';

      if($this->login_user_type == "1") //RECOMMENDER
      { 
        $data['page_title'] = 'NCVET - KYC RECOMMENDER Change Password';
        $log_slug = 'kyc_recommender_self_password_action';
        $logged_in_type = 'Recommender';
      }   
      else if($this->login_user_type == "2") //APPROVER
      { 
        $data['page_title'] = 'NCVET - KYC APPROVER Change Password';
        $log_slug = 'kyc_approver_self_password_action';
        $logged_in_type = 'Approver';
      }   
      
      $data['form_data'] = $form_data = $this->master_model->getRecords('ncvet_kyc_admin', array('kyc_admin_id' => $this->login_admin_id, 'kyc_admin_type' => $this->login_user_type), 'kyc_admin_id, is_active, is_deleted');
      			
			if(isset($_POST) && count($_POST) > 0)
			{ 
				$this->form_validation->set_rules('current_pass_kyc', 'Current Password', 'trim|required|xss_clean|callback_validation_check_old_password',array('required' => 'Please enter %s'));
				$this->form_validation->set_rules('new_pass_kyc', 'New Password', 'trim|required|callback_fun_validate_password|xss_clean|callback_validation_check_new_password',array('required' => 'Please enter %s', 'min_length' => 'Please enter minimum 8 characters in length'));
				$this->form_validation->set_rules('confirm_pass_kyc', 'Confirm Password', 'trim|required|min_length[8]|callback_validation_check_password|xss_clean',array('required' => 'Please enter %s', 'min_length' => 'Please enter minimum 8 characters in length'));
				
				if($this->form_validation->run())		
				{
          $posted_arr = json_encode($_POST);
          $admin_name = $this->Kyc_model->getLoggedInUserDetails($this->login_admin_id, $this->login_user_type);

          $up_data['kyc_admin_password'] = $this->Kyc_model->password_encryption($this->input->post('new_pass_kyc'));
          $up_data['updated_on'] = date("Y-m-d H:i:s");
          $up_data['updated_by'] = $this->login_admin_id;
          $this->master_model->updateRecord('ncvet_kyc_admin', $up_data, array('kyc_admin_id' => $this->login_admin_id, 'kyc_admin_type' => $this->login_user_type));
          
          $this->Kyc_model->insert_common_log($logged_in_type.' : Profile password updated', 'ncvet_kyc_admin', $this->db->last_query(), $this->login_admin_id, $log_slug, 'The '.$logged_in_type.' '.$admin_name['disp_name'].' has successfully updated the password', $posted_arr); 
					$this->session->set_flashdata('success','Password successfully updated');
					
          redirect(site_url('ncvet/kyc/benchmark_kyc_dashboard/change_password'));
				}
			}
			
      $data["enc_login_admin_id"] = url_encode($this->login_admin_id);      
      $data["logged_in_type"] = $logged_in_type;      
      $data["log_slug"] = $log_slug;      
			$this->load->view('ncvet/kyc/kyc_change_password', $data);
		}/******** END : CHANGE ADMIN PASSWORD ********/
		
    /******** START : VALIDATION FUNCTION TO CHECK THE OLD PASSWORD ********/
		function validation_check_old_password($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
		{
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['current_pass_kyc'] != "")
			{
        if($type == '1') { $current_pass_kyc = $this->security->xss_clean($this->input->post('current_pass_kyc')); }
        else if($type == '0') { $current_pass_kyc = $str; }        
								
				$enc_password = $this->Kyc_model->password_encryption($current_pass_kyc);

        if(count($this->master_model->getRecords('ncvet_kyc_admin', array('kyc_admin_password' => $enc_password, 'kyc_admin_id' => $this->login_admin_id, 'kyc_admin_type' => $this->login_user_type, 'is_active' => '1', 'is_deleted' => '0'), 'kyc_admin_id, is_active, is_deleted')) > 0)
        {
          $return_val_ajax = 'true';
        }       
			}

      if($type == '1') { echo $return_val_ajax; }
      else if($type == '0') 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['current_pass_kyc'] != "")
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
			if(isset($_POST) && $_POST['confirm_pass_kyc'] != "")
			{
        $new_pass_kyc = $this->security->xss_clean($this->input->post('new_pass_kyc'));
        if($type == '1') { $confirm_pass_kyc = $this->security->xss_clean($this->input->post('confirm_pass_kyc')); }
        else if($type == '0') { $confirm_pass_kyc = $str; }   
        
        if($new_pass_kyc == $confirm_pass_kyc)
        {
          $return_val_ajax = 'true';
        }
			}
      
      if($type == '1') { echo $return_val_ajax; }
      else if($type == '0') 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['confirm_pass_kyc'] != "")
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
          $result = $this->Kyc_model->fun_validate_password($str); 
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
			
      if(isset($_POST) && $_POST['new_pass_kyc'] != "")
			{
        $current_pass_kyc = $this->security->xss_clean($this->input->post('current_pass_kyc'));
        if($type == '1') { $new_pass_kyc = $this->security->xss_clean($this->input->post('new_pass_kyc')); }
        else if($type == '0') { $new_pass_kyc = $str; } 
        
        if (preg_match('/[A-Z]/', $new_pass_kyc) && preg_match('/[a-z]/', $new_pass_kyc) && preg_match('/[0-9]/', $new_pass_kyc))
        {
          if($current_pass_kyc != $new_pass_kyc)
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
        else if($_POST['new_pass_kyc'] != "")
        {
          $this->form_validation->set_message('validation_check_new_password',$msg);
          return false;
        }
      }
		}/******** END : VALIDATION FUNCTION TO CHECK THE NEW PASSWORD IS DIFFERENT FROM CURRENT PASSWORD ********/		
  }	    