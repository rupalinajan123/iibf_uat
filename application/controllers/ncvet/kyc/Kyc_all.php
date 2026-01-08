<?php 
  /********************************************************************************************************************
  ** Description: Controller for Common KYC functionality (Recommender & Approver) 
  ** Created BY: Anil S On 14-08-2025
  ********************************************************************************************************************/
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Kyc_all extends CI_Controller 
	{
		public function __construct()
		{
			parent::__construct();
			
			$this->load->model('master_model');
      $this->load->model('ncvet/Kyc_model'); 
      $this->load->helper('ncvet/ncvet_helper'); 

      $this->login_admin_id = $this->session->userdata('NCVET_KYC_LOGIN_ID');
      $this->login_user_type = $this->session->userdata('NCVET_KYC_ADMIN_TYPE'); // 1=>RECOMMENDER, 2=>APPROVER
      $this->login_related_id = $this->session->userdata('NCVET_KYC_RELATED_ID');
      $this->Kyc_model->check_admin_session_all_pages($this->login_user_type); // IF ADMIN SESSION IS NOT STARTED THEN REDIRECT TO LOGOUT
 
      /* START : NCVET MODULE FILE PATH */
      $this->ncvet_candidate_photo_path = 'uploads/ncvet/photo';
      $this->ncvet_candidate_sign_path = 'uploads/ncvet/sign';
      $this->ncvet_id_proof_file_path = 'uploads/ncvet/id_proof';
      $this->ncvet_declaration_file_path = 'uploads/ncvet/declaration';
      $this->ncvet_institute_idproof_file_path = 'uploads/ncvet/institute_idproof';
      $this->ncvet_qualification_certificate_file_path = 'uploads/ncvet/qualification_certificate';
      $this->ncvet_exp_certificate_path = 'uploads/ncvet/experience';
      $this->ncvet_aadhar_file_path = 'uploads/ncvet/aadhar_file';
      /* END : NCVET MODULE FILE PATH */

       
    }

    /* START : GET COMMON DATA FOR VALIDATION PURPOSE FOR ALL MODULES */
    function get_common_data($module_name='')
    {
      $result = $form_membership_type_arr = $form_member_type_arr = $form_exam_codes_arr = array();      
      $disp_title = $photo_path = $sign_path = $id_proof_path = $declaration_path = '';

      if($module_name == 'ncvet')//FOR NCVET MODULE 
      { 
        $disp_title = 'NCVET Module'; 
        $form_membership_type_arr = array('NM'=>'Non Member');
        $form_member_type_arr = array('new'=>'New Candidates', 'edited'=>'Edited Candidates');
        //$form_exam_codes_arr = array(1037, 1038, 1039, 1040, 1041, 1042, 1057);
        $form_exam_codes_arr = array();
        $photo_path = $this->ncvet_candidate_photo_path;
        $sign_path = $this->ncvet_candidate_sign_path;
        $id_proof_path = $this->ncvet_id_proof_file_path;
        $declaration_path = $this->ncvet_declaration_file_path;
        $institute_idproof_path = $this->ncvet_institute_idproof_file_path;
        $qualification_certificate_path = $this->ncvet_qualification_certificate_file_path;
        $exp_certificate_path = $this->ncvet_exp_certificate_path;
        $aadhar_file_path = $this->ncvet_aadhar_file_path;
      }

      $result['disp_title'] = $disp_title;
      $result['form_membership_type_arr'] = $form_membership_type_arr;
      $result['form_member_type_arr'] = $form_member_type_arr;
      $result['form_exam_codes_arr'] = $form_exam_codes_arr;
      $result['photo_path'] = $photo_path;
      $result['sign_path'] = $sign_path;
      $result['id_proof_path'] = $id_proof_path;
      $result['declaration_path'] = $declaration_path;
      $result['institute_idproof_path'] = $institute_idproof_path;
      $result['qualification_certificate_path'] = $qualification_certificate_path;
      $result['exp_certificate_path'] = $exp_certificate_path;
      $result['aadhar_file_path'] = $aadhar_file_path;
      return $result;
    }/* END : GET COMMON DATA FOR VALIDATION PURPOSE FOR ALL MODULES */
    
    //MODULE_NAME LIKE ncvet
    public function index($module_name='')
		{   
      $module_name = strtolower($module_name);
      $data['act_id'] = $module_name;
			$data['sub_act_id'] = "";      
      $disp_title = 'KYC';
      $form_membership_type_arr = $form_exam_codes_arr = array(); 
      $membership_type = $member_type = $exam_code = '0';      

      /* START : CHECK FOR CORRECT MODULE NAME PASSED TO URL */
      if($module_name != '') 
      { 
        $common_data = $this->get_common_data($module_name);
        $disp_title = $common_data['disp_title']; 
        $form_membership_type_arr = $common_data['form_membership_type_arr'];
        $form_exam_codes_arr = $common_data['form_exam_codes_arr'];
      }
      else
      { 
        //echo "==".$module_name;die;
        $this->session->set_flashdata('error','Invalid URL accessed');
        redirect(site_url('ncvet/kyc/kyc_dashboard'));
      }/* END : CHECK FOR CORRECT MODULE NAME PASSED TO URL */
      
      /* START : CHECK IF KYC IS IN PROGRESS FOR ANY CANDIDATE WITH LOGGED IN ID & TYPE. IF YES, THEN REDIRECT TO COMPLETE THAT KYC PAGE */
      $kyc_inprogress_candidate_data = $this->get_kyc_inprogress_candidate_data($module_name, $form_exam_codes_arr);
      //_pa($kyc_inprogress_candidate_data,1);
      if(count($kyc_inprogress_candidate_data) > 0)
      {
        if($kyc_inprogress_candidate_data[0]['img_ediited_on'] == '' || $kyc_inprogress_candidate_data[0]['img_ediited_on'] == 'NULL' || $kyc_inprogress_candidate_data[0]['img_ediited_on'] == '0000-00-00 00:00:00')
        {
          $member_type = 'new';
        }
        else if($kyc_inprogress_candidate_data[0]['img_ediited_on'] != '' && $kyc_inprogress_candidate_data[0]['img_ediited_on'] != 'NULL' && $kyc_inprogress_candidate_data[0]['img_ediited_on'] != '0000-00-00 00:00:00')
        {
          $member_type = 'edited';
        }

        $this->session->set_flashdata('warning','Please complete the pending KYC first');
        redirect(site_url('ncvet/kyc/kyc_all/kyc_start/'.$module_name.'/'.$kyc_inprogress_candidate_data[0]['registration_type'].'/'.$member_type.'/'.$kyc_inprogress_candidate_data[0]['exam_code']));
      }/* END : CHECK IF KYC IS IN PROGRESS FOR ANY CANDIDATE WITH LOGGED IN ID & TYPE. IF YES, THEN REDIRECT TO COMPLETE THAT KYC PAGE */

      if(isset($_POST) && count($_POST) > 0 && isset($_POST['form_type']) && $_POST['form_type'] == 'search_form')
			{
        $this->form_validation->set_rules('s_membership_type', 'Membership Type', 'trim|required|xss_clean',array('required' => 'Please select %s'));
        $this->form_validation->set_rules('s_member_type', 'Member Type', 'trim|required|xss_clean',array('required' => 'Please select %s'));

        if($_POST['s_membership_type'] == 'NM')
        {
				  //$this->form_validation->set_rules('s_exam_code', 'Exam Code', 'trim|required|xss_clean',array('required' => 'Please select %s'));
          //$this->form_validation->set_rules('s_exam_code', 'Exam Code', 'trim|xss_clean');
        }
				
				if($this->form_validation->run())		
				{
          $membership_type = $this->input->post('s_membership_type');
          $member_type = $this->input->post('s_member_type');

          if(isset($_POST['s_exam_code'])) { $exam_code = $this->input->post('s_exam_code'); }
          redirect(site_url('ncvet/kyc/kyc_all/kyc_start/'.$module_name.'/'.$membership_type.'/'.$member_type.'/'.$exam_code));
				}
			}
      
			$data['module_name'] = $module_name;
			$data['disp_title'] = 'KYC - '.$disp_title;
      $data['page_title'] = 'NCVET KYC - '.$disp_title;
      $data['form_membership_type_arr'] = $form_membership_type_arr;
      $this->load->view('ncvet/kyc/kyc_all', $data);
    } 

    /* START : GET MEMBER TYPE DROPDOWN VALUE AJAX FUNCTION LIKE NEW CANDIDATES OR EDITED CANDIDATES ON SELECTION OF MEMBERSHIP TYPE  */
    function get_member_type_ajax()
    {
			if(isset($_POST) && count($_POST) > 0)
			{        
        $module_name = $this->security->xss_clean($this->input->post('module_name'));
        $selected_membership_type = $this->security->xss_clean($this->input->post('selected_membership_type'));
				
        $onchange_fun = "javascript:void(0)";
        if($selected_membership_type == 'NM') { $onchange_fun = "fun_get_exam_code_ajax(this.value)"; }
        $html = '	<div class="form-group text-left" style="min-width:250px;">';
        $html .= '  <select class="form-control search_opt" name="s_member_type" id="s_member_type" required onchange="'.$onchange_fun.'">';
        
        if($selected_membership_type != "")
        {
          $common_data = $this->get_common_data($module_name);
          $member_type_arr = $common_data['form_member_type_arr'];
          
          if(count($member_type_arr) > 0)
          {
            $html .= '	<option value="">Select Member Type</option>';
            foreach($member_type_arr as $member_type_key=>$member_type_res)
            {
              $html .= '	<option value="'.$member_type_key.'">'.$member_type_res.'</option>';
            }
          }
          else
          {
            $html .= '	<option value="">Member Type Not Available</option>';
          }
        }
        else
        {
          $html .= '	<option value="">Select Member Type</option>';
        }
				$html .= '  </select>';
				$html .= '</div>';
        
        $result['flag'] = "success";
        $result['response'] = $html;
      }
      else
      {      
        $result['flag'] = "error";
      }
      echo json_encode($result);
    }/* END : GET MEMBER TYPE DROPDOWN VALUE AJAX FUNCTION LIKE NEW CANDIDATES OR EDITED CANDIDATES ON SELECTION OF MEMBERSHIP TYPE  */

    /* START : GET EXAM CODE DROPDOWN VALUE WITH REMAINING KYC COUNT AJAX FUNCTION ON SELECTION OF MEMBERSHIP TYPE & MEMBER TYPE  */
    function get_exam_code_ajax()
    {
			if(isset($_POST) && count($_POST) > 0)
			{
        $html = '<input type="hidden" name="s_exam_code" id="s_exam_code" />';
        /*$html = '	<div class="form-group text-left" style="min-width:250px;">';
        $html .= '  <select class="form-control search_opt" name="s_exam_code" id="s_exam_code">';
				
        $module_name = $this->security->xss_clean($this->input->post('module_name'));
        $selected_member_type = $this->security->xss_clean($this->input->post('selected_member_type'));
        $selected_membership_type = $this->security->xss_clean($this->input->post('selected_membership_type'));
				
        $common_data = $this->get_common_data($module_name);
        $exam_code_arr = $common_data['form_exam_codes_arr'];
        //$reindexedArray = array_values($exam_code_arr);
        //$inCondition = "'" . implode("','", $reindexedArray) . "'"; 
        
        $exam_code_data = $this->get_kyc_pending_candidate_data($module_name, $selected_membership_type, $selected_member_type, $exam_code_arr, $exam_code='0', $is_dropdown = '1');
        
        if(count($exam_code_data) > 0)
				{
					$html .= '	<option value="">Select Exam Code</option>';
					foreach($exam_code_data as $exam_code_res)
					{
						$html .= '	<option value="'.$exam_code_res['exam_code'].'">'.$exam_code_res['exam_code'].' ('.$exam_code_res['RecordCnt'].')</option>';
          }
        }
				else
				{
					$html .= '	<option value="">Exam Code Not Available</option>';
        }
				$html .= '  </select>';
				$html .= '</div>';*/
        
        $result['flag'] = "success";
        $result['response'] = $html;
      }
      else
      {      
        $result['flag'] = "error";
      }
      echo json_encode($result);
    }/* END : GET EXAM CODE DROPDOWN VALUE WITH REMAINING KYC COUNT AJAX FUNCTION ON SELECTION OF MEMBERSHIP TYPE & MEMBER TYPE  */
    
    //module_name = ncvet
    //membership_type = O, NM
    //member_type = New, Edited
    //exam_code = 1037, 1038, 1039, 1040, 1041, 1042, 1057, 45
    public function kyc_start($module_name='', $membership_type='', $member_type='', $exam_code='')
    {
      //echo "is == ".$this->session->flashdata('success_kyc');
      if($this->session->flashdata('success_kyc')){
        $this->session->set_flashdata('success_kyc',$this->session->flashdata('success_kyc'));
      }
      $module_name = strtolower($module_name);
      $data['act_id'] = $module_name;
			$data['sub_act_id'] = "";      
      $disp_title = 'KYC';
      $form_membership_type_arr = $form_member_type_arr = $form_exam_codes_arr = array();
      $photo_path = $sign_path = $id_proof_path = $declaration_path = '';

      /* START : CHECK FOR CORRECT MODULE NAME PASSED TO URL */
      if($module_name != '') 
      { 
        $common_data = $this->get_common_data($module_name);
        $disp_title = $common_data['disp_title']; 
        $form_membership_type_arr = $common_data['form_membership_type_arr']; 
        $form_member_type_arr = $common_data['form_member_type_arr']; 
        $form_exam_codes_arr = $common_data['form_exam_codes_arr'];
        $photo_path = $common_data['photo_path'];
        $sign_path = $common_data['sign_path'];
        $id_proof_path = $common_data['id_proof_path'];
        $declaration_path = $common_data['declaration_path'];
      }
      else
      {
        //echo "==".$module_name;die;
        $this->session->set_flashdata('error','Invalid URL accessed');
        redirect(site_url('ncvet/kyc/kyc_dashboard'));
      }/* END : CHECK FOR CORRECT MODULE NAME PASSED TO URL */

      /* START : CHECK FOR CORRECT MEMBERSHIP TYPE, MEMBER TYPE & EXAM CODE AS PER THE MODULE */
      if(!array_key_exists($membership_type, $form_membership_type_arr))
      {
        //echo $membership_type."==".print_r($form_membership_type_arr);die;
        $this->session->set_flashdata('error','Invalid URL accessed');
        redirect(site_url('ncvet/kyc/kyc_dashboard'));
      }
      else if(!array_key_exists($member_type, $form_member_type_arr))
      {
        //echo "==".$module_name;die;
        $this->session->set_flashdata('error','Invalid URL accessed');
        redirect(site_url('ncvet/kyc/kyc_dashboard'));
      }
      else if($membership_type == 'NM' && !in_array($exam_code, $form_exam_codes_arr))
      {
        //echo "==".$module_name;die;
        //$this->session->set_flashdata('error','Invalid URL accessed');
        //redirect(site_url('ncvet/kyc/kyc_dashboard'));
      }/* END : CHECK FOR CORRECT MEMBERSHIP TYPE, MEMBER TYPE & EXAM CODE AS PER THE MODULE */
      
      $kyc_pending_candidate_data = $this->get_kyc_pending_candidate_data($module_name, $membership_type, $member_type, $form_exam_codes_arr, $exam_code, $is_dropdown = '');      
      $kyc_inprogress_candidate_data = $this->get_kyc_inprogress_candidate_data($module_name, $form_exam_codes_arr);
      
      //START : GET INPROGRESS CANDIDATE DATA COUNT & GET TOTAL KYC PENDING CANDIDATE DATA COUNT. IF INPROGRESS CANDIDATE COUNT IS ZERO & TOTAL PENDING CANDIDATE COUNT IS HIGHER THAN ZERO, THEN ALLOCATE THAT MEMBER FOR KYC
      if(count($kyc_pending_candidate_data) > 0 && count($kyc_inprogress_candidate_data) == 0)
      {
        $update_qry_res = '';
        $up_candidiate = array();
        $up_candidiate['kyc_status'] = '1';

        if($this->login_user_type == '1') //RECOMMENDER
        {
          $up_candidiate['kyc_recommender_status'] = '1';
          $up_candidiate['recommender_id'] = $this->login_admin_id;
          $up_candidiate['kyc_recommender_date'] = date('Y-m-d H:i:s');            
        }
        else if($this->login_user_type == '2') //APPROVER
        {
          $up_candidiate['kyc_approver_status'] = '1';
          $up_candidiate['approver_id'] = $this->login_admin_id;
          $up_candidiate['kyc_approver_date'] = date('Y-m-d H:i:s');
        }

        if($module_name == 'ncvet' && count($up_candidiate) > 0)//FOR NCVET MODULE
        {
          //UPDATE RECORDS IN ncvet_candidates table
          $update_qry_res = $this->master_model->updateRecord('ncvet_candidates', $up_candidiate, array('candidate_id'=>$kyc_pending_candidate_data[0]['candidate_id']));
        }       
          
        if($update_qry_res)
        {
          $session_msg = $this->session->flashdata('success');
          $this->session->set_flashdata('success',$session_msg);
          redirect(site_url('ncvet/kyc/kyc_all/kyc_start/'.$module_name.'/'.$membership_type.'/'.$member_type.'/'.$exam_code));
        }
      }
      else if(count($kyc_pending_candidate_data) == 0 && count($kyc_inprogress_candidate_data) == 0)
      {
        //echo count($kyc_pending_candidate_data)." == ".count($kyc_inprogress_candidate_data);die;
        $this->session->set_flashdata('error','No candidate available for selected criteria');
        redirect(site_url('ncvet/kyc/kyc_dashboard'));
      }//END : GET INPROGRESS CANDIDATE DATA COUNT & GET TOTAL PENDING CANDIDATE DATA COUNT. IF INPROGRESS CANDIDATE COUNT IS ZERO & TOTAL PENDING CANDIDATE COUNT IS HIGHER THAN ZERO, THEN ALLOCATE THAT MEMBER FOR KYC
      
      $data['module_name'] = $module_name;
      $data['membership_type'] = $membership_type;
      $data['member_type'] = $member_type;
      $data['exam_code'] = $exam_code;
      $data['disp_title'] = 'KYC for '.$disp_title.' - '.$form_membership_type_arr[$membership_type].' ('.$form_member_type_arr[$member_type].')';
      if($membership_type == 'NM')
      {
        //$data['disp_title'] = 'KYC for '.$disp_title.' - '.$form_membership_type_arr[$membership_type].' ('.$form_member_type_arr[$member_type].' - '.$exam_code.')';
        $data['disp_title'] = 'KYC for '.$disp_title.' - '.$form_membership_type_arr[$membership_type].' ('.$form_member_type_arr[$member_type].')';
      }
      $data['page_title'] = 'NCVET KYC - '.$disp_title;
      $data['kyc_pending_candidate_data'] = $kyc_pending_candidate_data;
      $data['kyc_inprogress_candidate_data'] = $this->get_kyc_inprogress_candidate_data($module_name, $form_exam_codes_arr);
      $data['photo_path'] = $photo_path;
      $data['sign_path'] = $sign_path;
      $data['id_proof_path'] = $id_proof_path;
      $data['declaration_path'] = $declaration_path;
      $this->load->view('ncvet/kyc/kyc_start', $data);
    } 

    /* START : THIS FUNCTION IS USED TO UPDATE THE KYC DATA BASED ON USER SUBMISSION */
    function process_kyc()
    {
      $this->load->model('ncvet/Ncvet_model');
      $success_kyc = '';

      if(isset($_POST) && COUNT($_POST) > 0)
      {
        $this->form_validation->set_rules('candidate_id', 'candidate_id', 'trim|required|xss_clean', array('required'=>"Please enter the %s"));  
        $this->form_validation->set_rules('module_name', 'module_name', 'trim|required|xss_clean', array('required'=>"Please enter the %s"));  
        $this->form_validation->set_rules('membership_type', 'membership_type', 'trim|required|xss_clean', array('required'=>"Please enter the %s"));  
        $this->form_validation->set_rules('member_type', 'member_type', 'trim|required|xss_clean', array('required'=>"Please enter the %s"));  
        $this->form_validation->set_rules('form_action', 'form_action', 'trim|required|xss_clean', array('required'=>"Please enter the %s"));  
        $this->form_validation->set_rules('photo_file_kyc', 'option', 'trim|required|xss_clean', array('required'=>"Please enter the %s"));  
        $this->form_validation->set_rules('sign_file_kyc', 'option', 'trim|required|xss_clean', array('required'=>"Please enter the %s"));  
        $this->form_validation->set_rules('id_proof_file_kyc', 'option', 'trim|required|xss_clean', array('required'=>"Please enter the %s"));  
        $this->form_validation->set_rules('declaration_file_kyc', 'option', 'trim|required|xss_clean', array('required'=>"Please enter the %s"));  
        if($this->form_validation->run())
        { 
          $candidate_id = $this->input->post('candidate_id');
          $module_name = $this->input->post('module_name');
          $membership_type = $this->input->post('membership_type');
          $member_type = $this->input->post('member_type');
          $exam_code = $this->input->post('exam_code');
          $form_action = $this->input->post('form_action');
          
          $photo_file_kyc = $sign_file_kyc = $id_proof_file_kyc = $declaration_file_kyc = '';
          if(isset($_POST['photo_file_kyc'])) { $photo_file_kyc = $this->input->post('photo_file_kyc'); }
          if(isset($_POST['sign_file_kyc'])) { $sign_file_kyc = $this->input->post('sign_file_kyc'); }
          if(isset($_POST['id_proof_file_kyc'])) { $id_proof_file_kyc = $this->input->post('id_proof_file_kyc'); }
          if(isset($_POST['declaration_file_kyc'])) { $declaration_file_kyc = $this->input->post('declaration_file_kyc'); }

          /* START : CHECK FOR CORRECT MODULE NAME PASSED TO URL */
          if($module_name != '') 
          { 
            $common_data = $this->get_common_data($module_name);            
            $form_membership_type_arr = $common_data['form_membership_type_arr']; 
            $form_member_type_arr = $common_data['form_member_type_arr']; 
            $form_exam_codes_arr = $common_data['form_exam_codes_arr'];
            $photo_path = $common_data['photo_path'];
            $sign_path = $common_data['sign_path'];
            $id_proof_path = $common_data['id_proof_path'];            
            $declaration_path = $common_data['declaration_path'];            
          }
          else
          {
            $this->session->set_flashdata('error','Invalid URL accessed');
            redirect(site_url('ncvet/kyc/kyc_dashboard'));
          }/* END : CHECK FOR CORRECT MODULE NAME PASSED TO URL */

          /* START : CHECK FOR CORRECT MEMBERSHIP TYPE, MEMBER TYPE & EXAM CODE AS PER THE MODULE */
          if(!array_key_exists($membership_type, $form_membership_type_arr))
          {
            $this->session->set_flashdata('error','Invalid URL accessed');
            redirect(site_url('ncvet/kyc/kyc_dashboard'));
          }
          else if(!array_key_exists($member_type, $form_member_type_arr))
          {
            $this->session->set_flashdata('error','Invalid URL accessed');
            redirect(site_url('ncvet/kyc/kyc_dashboard'));
          }
          else if($membership_type == 'NM' && !in_array($exam_code, $form_exam_codes_arr))
          {
            //$this->session->set_flashdata('error','Invalid URL accessed');
            //redirect(site_url('ncvet/kyc/kyc_dashboard'));
          }/* END : CHECK FOR CORRECT MEMBERSHIP TYPE, MEMBER TYPE & EXAM CODE AS PER THE MODULE */

          $update_qry_res = '';
          $candidate_data = array();
          if($this->login_user_type == '1') //RECOMMENDER
          {
            $this->db->where('recommender_id', $this->login_admin_id);
            $this->db->where('kyc_recommender_status', '1');
          }
          else if($this->login_user_type == '2') //APPROVER
          {
            $this->db->where('approver_id', $this->login_admin_id);
            $this->db->where('kyc_recommender_status', '2');
            $this->db->where('kyc_approver_status', '1');
          }

          if($module_name == 'ncvet')//FOR NCVET MODULE
          {
            $candidate_data = $this->master_model->getRecords('ncvet_candidates', array('candidate_id' => $candidate_id, 'kyc_status' => '1'), 'candidate_id, exam_code, training_id, regnumber, salutation, first_name, middle_name, last_name, mobile_no, email_id, candidate_photo, candidate_sign, id_proof_file, declarationform, kyc_photo_flag, kyc_sign_flag, institute_idproof, kyc_institute_idproof_flag, aadhar_no, id_proof_number, qualification_certificate_file, kyc_qualification_cert_flag, exp_certificate, kyc_exp_certificate_flag, aadhar_file, kyc_aadhar_file_flag');
          }

          if(count($candidate_data) == 0)
          {
            $this->session->set_flashdata('error','Something went wrong. Please try again.');
            redirect(site_url('ncvet/kyc/kyc_dashboard'));
          }

          $candidate_name = $candidate_data[0]['salutation']; 
          $candidate_name .= $candidate_data[0]['first_name'] !=''? ' '.$candidate_data[0]['first_name']:'';
          $candidate_name .= $candidate_data[0]['middle_name'] !=''? ' '.$candidate_data[0]['middle_name']:'';
          $candidate_name .= $candidate_data[0]['last_name'] !=''? ' '.$candidate_data[0]['last_name']:'';
          $candidate_name .= $candidate_data[0]['training_id'] !=''? ' ('.$candidate_data[0]['training_id'].')':'';

          $training_id = $candidate_data[0]['training_id'];
          $regnumber = $candidate_data[0]['regnumber'];

          $logged_in_user_name = $this->Kyc_model->getLoggedInUserDetails($this->login_admin_id, $this->login_user_type);

          $up_candidiate = array();
          $approve_reject_flag = 'Y';
          $log_message = $log_title = $kyc_action = '';
          if($module_name == 'ncvet')//FOR NCVET MODULE
          {
            //UPDATE RECORDS IN ncvet_candidates
            if($photo_file_kyc != '') 
            { 
              $up_candidiate['kyc_photo_flag'] = $photo_file_kyc; 
              if($photo_file_kyc == 'N')//IF PHOTO REJECT IN KYC, THEN UPDATE THE TABLE COLUMN & RENAME THE PHOTO
              { 
                $approve_reject_flag = 'N'; 
                $up_candidiate['candidate_photo'] = ''; 
                rename($photo_path.'/'.$candidate_data[0]['candidate_photo'], $photo_path.'/k_'.$candidate_data[0]['candidate_photo']);
                $log_message .= 'Rejected the Photo';
                $kyc_action .= 'Photo';
              }
              else if($photo_file_kyc == 'Y')
              {
                if(($this->login_user_type == '1' && $candidate_data[0]['kyc_photo_flag'] != $photo_file_kyc) || $this->login_user_type == '2') 
                {
                  $log_message .= 'Approved the Photo';
                }
              }
            }

            if($sign_file_kyc != '') 
            { 
              $up_candidiate['kyc_sign_flag'] = $sign_file_kyc; 
              if($sign_file_kyc == 'N') //IF SIGN REJECT IN KYC, THEN UPDATE THE TABLE COLUMN & RENAME THE SIGN
              { 
                $approve_reject_flag = 'N';
                $up_candidiate['candidate_sign'] = '';
                rename($sign_path.'/'.$candidate_data[0]['candidate_sign'], $sign_path.'/k_'.$candidate_data[0]['candidate_sign']);

                $log_message .= ($log_message != '' ? ', ' : '').'Rejected the Signature';
                $kyc_action .= ($kyc_action != '' ? ', ' : '').'Signature';
              }
              else if($sign_file_kyc == 'Y')
              {
                if(($this->login_user_type == '1' && $candidate_data[0]['kyc_sign_flag'] != $sign_file_kyc) || $this->login_user_type == '2') 
                {
                  $log_message .= ($log_message != '' ? ', ' : '').'Approved the Signature';
                }
              }
            }

            if($id_proof_file_kyc != '') 
            { 
              $up_candidiate['kyc_id_card_flag'] = $id_proof_file_kyc; 
              if($id_proof_file_kyc == 'N') //IF APAAR ID/ABC ID REJECT IN KYC, THEN UPDATE THE TABLE COLUMN & RENAME THE APAAR ID/ABC ID
              { 
                $approve_reject_flag = 'N';
                //$up_candidiate['id_proof_file'] = '';  
                $up_candidiate['id_proof_file'] = '';  
                rename($id_proof_path.'/'.$candidate_data[0]['id_proof_file'], $id_proof_path.'/k_'.$candidate_data[0]['id_proof_file']);

                $log_message .= ($log_message != '' ? ' & ' : '').'Rejected the APAAR ID/ABC ID';
                $kyc_action .= ($kyc_action != '' ? ' & ' : '').'APAAR ID/ABC ID'; 
              }
              else if($id_proof_file_kyc == 'Y')
              {
                if(($this->login_user_type == '1' && $candidate_data[0]['kyc_id_card_flag'] != $id_proof_file_kyc) || $this->login_user_type == '2') 
                {
                  $log_message .= ($log_message != '' ? ' & ' : '').'Approved the APAAR ID/ABC ID';
                }
              }
            }

            if($declaration_file_kyc != '') 
            { 
              $up_candidiate['kyc_declaration_flag'] = $declaration_file_kyc; 
              if($declaration_file_kyc == 'N') //IF DECLARATION REJECT IN KYC, THEN UPDATE THE TABLE COLUMN & RENAME THE DECLARATION
              { 
                $approve_reject_flag = 'N'; 
                $up_candidiate['declarationform'] = '';  
                rename($declaration_path.'/'.$candidate_data[0]['declarationform'], $declaration_path.'/k_'.$candidate_data[0]['declarationform']);

                $log_message .= ($log_message != '' ? ' & ' : '').'Rejected the Declaration';
                $kyc_action .= ($kyc_action != '' ? ' & ' : '').'Declaration'; 
              }
              else if($declaration_file_kyc == 'Y')
              {
                if(($this->login_user_type == '1' && $candidate_data[0]['kyc_declaration_flag'] != $declaration_file_kyc) || $this->login_user_type == '2') 
                {
                  $log_message .= ($log_message != '' ? ' & ' : '').'Approved the Declaration';
                }
              }
            }

          }
           

          if($this->login_user_type == '1') //RECOMMENDER
          {
            if($approve_reject_flag == 'Y') 
            { 
              $up_candidiate['kyc_recommender_status'] = '2'; 
              $up_candidiate['kyc_status'] = '1'; 
              $log_title = 'Approved'; 
            }
            else if($approve_reject_flag == 'N') 
            { 
              $up_candidiate['kyc_recommender_status'] = '3';
              $up_candidiate['kyc_status'] = '3'; 
              $log_title = 'Rejected';   

              ////SEND KYC REJECTION EMAIL TO CANDIDATE                 
            }
            
            $up_candidiate['recommender_id'] = $this->login_admin_id;
            $up_candidiate['kyc_recommender_date'] = date('Y-m-d H:i:s');
          }
          else if($this->login_user_type == '2') //APPROVER
          {
            if($approve_reject_flag == 'Y') 
            { 
              $up_candidiate['kyc_approver_status'] = '2';  
              $up_candidiate['kyc_status'] = '2';
              $log_title = 'Approved'; 
            }
            else if($approve_reject_flag == 'N') 
            { 
              $up_candidiate['kyc_approver_status'] = '3'; 
              $up_candidiate['kyc_status'] = '3';
              $log_title = 'Rejected'; 

              ////SEND KYC REJECTION EMAIL TO CANDIDATE  
            }
            
            $up_candidiate['approver_id'] = $this->login_admin_id;
            $up_candidiate['kyc_approver_date'] = date('Y-m-d H:i:s');
          }

          /*$get_ip_address = get_ip_address();
          if(in_array($get_ip_address,array('115.124.115.75')) )
          {
              echo "<br>".$get_ip_address;
              echo "<br>module_name: ".$module_name;
              echo "<br>action: ".strtoupper($kyc_action)."<br>";
              echo "<br>candidate_data: ".print_r($candidate_data);
              //die;
              $this->Kyc_model->send_approve_reject_kyc_email_sms($candidate_data,$module_name,$kyc_action);die;
          }*/


          if(count($up_candidiate) > 0)
          {
            if($module_name == 'ncvet')//FOR NCVET MODULE
            {
              $update_qry_res = $this->master_model->updateRecord('ncvet_candidates', $up_candidiate, array('candidate_id'=>$candidate_id));
            }

            $log_tbl_name = '';
            if($module_name == 'ncvet')//FOR NCVET MODULE
            {
              $log_tbl_name = 'ncvet_candidates';
            }

            ////SAVE KYC RELATED LOG IN DATABASE TABLE
            if($this->login_user_type == '1') //RECOMMENDER
            {
              $this->Kyc_model->insert_common_log('Recommender action : '.$log_title, $log_tbl_name, $this->db->last_query(), $candidate_id, 'kyc_recommender_'.$log_title, 'The Recommender ('.$logged_in_user_name['disp_name'].') '.$log_message.' for the candidate '.$candidate_name, json_encode($_POST), $module_name, $membership_type, $member_type, $exam_code, $training_id, $regnumber);
            }
            else if($this->login_user_type == '2') //APPROVER
            {
              $this->Kyc_model->insert_common_log('Approver action : '.$log_title, $log_tbl_name, $this->db->last_query(), $candidate_id, 'kyc_approver_'.$log_title, 'The Approver ('.$logged_in_user_name['disp_name'].') '.$log_message.' for the candidate '.$candidate_name, json_encode($_POST), $module_name, $membership_type, $member_type, $exam_code, $training_id, $regnumber);
            }  
            
            if($approve_reject_flag == 'Y') 
            {
              $this->session->set_flashdata('success_kyc','KYC successfully approved for candidate '.$candidate_name);
              $success_kyc = 'KYC successfully approved for candidate '.$candidate_name;
            }
            else
            {
              $this->session->set_flashdata('success_kyc','KYC details <strong>successfully updated for candidate</strong> '.$candidate_name.'. Also email has been successfully sent to candidate regarding the KYC status.');
              $success_kyc = 'KYC details <strong>successfully updated for candidate</strong> '.$candidate_name.'. Also email has been successfully sent to candidate regarding the KYC status.';
              //Send Email To Candidate For  
              //$this->Kyc_model->send_approve_reject_kyc_email_sms($candidate_data,$module_name,$kyc_action);

            }
          }
          else
          {
            $this->session->set_flashdata('error','Something went wrong. Please try again.');
            redirect(site_url('ncvet/kyc/kyc_dashboard'));
          }

          if($update_qry_res)
          {
            if($form_action == 'submit_and_next')
            { 
              //Send Email To Candidate For  
              //echo $this->session->flashdata('success_kyc');die;
              redirect(site_url('ncvet/kyc/kyc_all/kyc_start/'.$module_name.'/'.$membership_type.'/'.$member_type.'/'.$exam_code));
            }
            else
            {
              redirect(site_url('ncvet/kyc/kyc_dashboard'));
            }
          }
          else
          {
            $this->session->set_flashdata('error','Please submit the correct details');
            redirect(site_url('ncvet/kyc/kyc_all/kyc_start/'.$_POST['module_name'].'/'.$_POST['membership_type'].'/'.$_POST['member_type'].'/'.$_POST['exam_code']));
          }
        }
        else
        {
          $this->session->set_flashdata('error','Please submit the correct details');
          redirect(site_url('ncvet/kyc/kyc_all/kyc_start/'.$_POST['module_name'].'/'.$_POST['membership_type'].'/'.$_POST['member_type'].'/'.$_POST['exam_code']));
        }
      }
      else
      {
        $this->session->set_flashdata('error','You can not access this page directly.');
        redirect(site_url('ncvet/kyc/kyc_dashboard'));
      }
    }/* END : THIS FUNCTION IS USED TO UPDATE THE KYC DATA BASED ON USER SUBMISSION */

    /* START : GET KYC PENDING CANDIDATE DATA FOR EXAM CODE DROPDOWN WITH PENDING COUNT */
    function get_kyc_pending_candidate_data($module_name='', $membership_type='', $member_type='', $exam_code_arr=array(), $exam_code = '0', $is_dropdown='')
    {
      $kyc_pending_candidate_data = array();

      $reindexedArray = array_values($exam_code_arr);
      $inCondition = "'" . implode("','", $reindexedArray) . "'";
      $this->db->where(" kyc_eligible_date != '' AND kyc_eligible_date IS NOT NULL AND kyc_eligible_date != '0000-00-00 00:00:00' ");
      
      if($member_type == 'new') 
      { 
        $this->db->where(" (img_ediited_on = '' OR img_ediited_on IS NULL OR img_ediited_on = '0000-00-00 00:00:00') "); 
      }
      else if($member_type == 'edited') 
      { 
        $this->db->where(" (img_ediited_on != '' AND img_ediited_on IS NOT NULL AND img_ediited_on != '0000-00-00 00:00:00') "); 
      }

      if($this->login_user_type == '1') //Recommender
      {
        $this->db->where("recommender_id = '0' AND (kyc_recommender_status IS NULL OR kyc_recommender_status = '' OR kyc_recommender_status = '0') AND approver_id = '0' ");
        $this->db->where_in('kyc_status', array(0,2));
      }
      else if($this->login_user_type == '2') //Approver
      {
        //$this->db->where('recommender_id != ', $this->login_related_id);
        $this->db->where("approver_id = '0' AND (kyc_approver_status IS NULL OR kyc_approver_status = '' OR kyc_approver_status = '0') AND recommender_id > 0 AND kyc_recommender_status = '2'");
        $this->db->where_in('kyc_status', array(1));
      }

      if($membership_type == 'NM' && $module_name == 'ncvet')//FOR NCVET MODULE
      {
        //$this->db->where(" exam_code IN (".$inCondition.") ");

        $select_fields = '';
        /*if($is_dropdown == '1') 
        { 
          $this->db->group_by('exam_code'); 
          $select_fields = ', count(exam_code) AS RecordCnt';
        }
        else
        {*/
          $this->db->order_by('candidate_id', 'ASC');
        //}

        //if($exam_code != '' && $exam_code != '0') { $this->db->where('exam_code', $exam_code); }          
        $kyc_pending_candidate_data = $this->master_model->getRecords('ncvet_candidates', array('regnumber !=' => '', 'is_deleted'=>'0', 'DATE(kyc_eligible_date) <='=>date('Y-m-d')), 'candidate_id, exam_code'.$select_fields);
        //echo $this->db->last_query();die;
      }

      return $kyc_pending_candidate_data;
    }/* END : GET KYC PENDING CANDIDATE DATA FOR EXAM CODE DROPDOWN WITH PENDING COUNT */

    /* START : GET KYC INPROGRESS CANDIDATE DATA */
    function get_kyc_inprogress_candidate_data($module_name='',  $exam_code_arr=array())
    {
      $kyc_inprogress_candidate_data = array();

      $reindexedArray = array_values($exam_code_arr);
      $inCondition = "'" . implode("','", $reindexedArray) . "'";
      $this->db->where(" kyc_eligible_date != '' AND kyc_eligible_date IS NOT NULL AND kyc_eligible_date != '0000-00-00 00:00:00' ");
      
      if($this->login_user_type == '1') //Recommender
      {
        $this->db->where("recommender_id = '".$this->login_admin_id."' AND kyc_recommender_status = '1' AND approver_id = '0' ");
      }
      else if($this->login_user_type == '2') //Approver
      {
        $this->db->where("approver_id = '".$this->login_admin_id."' AND kyc_approver_status = '1' AND recommender_id > 0 AND kyc_recommender_status = '2'");
      }

      if($module_name == 'ncvet') //FOR NCVET MODULE
      {
        //$this->db->where(" exam_code IN (".$inCondition.") ");
        $this->db->where_in('kyc_status', array(1));
        $kyc_inprogress_candidate_data = $this->master_model->getRecords('ncvet_candidates', array('regnumber !=' => '', 'is_deleted'=>'0', 'DATE(kyc_eligible_date) <='=>date('Y-m-d')), 'candidate_id, regnumber, training_id, salutation, first_name, middle_name, last_name, dob, gender, mobile_no, email_id, id_proof_file, candidate_photo as photo_file, candidate_sign as sign_file, declarationform, registration_type, exam_code, kyc_eligible_date, img_ediited_on, kyc_fullname_flag, kyc_dob_flag, kyc_aadhar_flag, kyc_apaar_flag, kyc_eligibility_flag, kyc_photo_flag, kyc_sign_flag, kyc_id_card_flag, kyc_declaration_flag, kyc_status, kyc_recommender_status, recommender_id, kyc_approver_status, approver_id, kyc_recommender_date, kyc_approver_date, institute_idproof, kyc_institute_idproof_flag, aadhar_no, id_proof_number, qualification_certificate_file, kyc_qualification_cert_flag, exp_certificate, kyc_exp_certificate_flag, aadhar_file, kyc_aadhar_file_flag');        
      }

      return $kyc_inprogress_candidate_data;
    }/* END : GET KYC INPROGRESS CANDIDATE DATA */


    /*List All New Candidate (Enrolled Candidates) KYC */
    public function new_candidate()
    {    
      $dispName = $this->Kyc_model->getLoggedInUserDetails($this->login_admin_id, $this->login_user_type); 

      $data['act_id'] = "New Candidate KYC";
      $data['sub_act_id'] = "Enrolled Candidates";
      $data['page_title'] = 'New Candidates Enrolled';
      $data['dispName'] = $dispName;
      
      $data['action_name'] = 'get_new_common_candidate_data_for_kyc_ajax';

      $data['kyc_status_list'] = 'enrolled';
      $data['page_url'] = 'new_candidate';

      $this->load->view('ncvet/kyc/kyc_candidate_list', $data);
    }
    /*List All New Candidate (Enrolled Candidates) KYC */

    /*List All Pending Candidate KYC */
    public function pending_candidate()
    {    
      $dispName = $this->Kyc_model->getLoggedInUserDetails($this->login_admin_id, $this->login_user_type); 

      $data['act_id'] = "New Candidate KYC";
      $data['sub_act_id'] = "Pending for KYC";
      $data['page_title'] = 'Pending for KYC';
      $data['dispName'] = $dispName;
      
      $data['action_name'] = 'get_new_common_candidate_data_for_kyc_ajax';

      $data['kyc_status_list'] = 'pending';
      $data['page_url'] = 'pending_candidate';

      $this->load->view('ncvet/kyc/kyc_candidate_list', $data);
    }
    /*List All Pending Candidate KYC */

    /*List All Recommend Candidate KYC */
    public function recommend_candidate()
    {    
      $dispName = $this->Kyc_model->getLoggedInUserDetails($this->login_admin_id, $this->login_user_type); 

      $data['act_id'] = "New Candidate KYC";
      $data['sub_act_id'] = "Attend Candidates";
      $data['page_title'] = 'Attend Candidates';
      $data['dispName'] = $dispName;

      $data['action_name'] = 'get_new_common_candidate_data_for_kyc_ajax';

      $data['kyc_status_list'] = 'recommend';
      $data['page_url'] = 'recommend_candidate';
      
      $this->load->view('ncvet/kyc/kyc_candidate_list', $data);
    }
    /*List All Recommend Candidate KYC */

    /******** START : SERVER SIDE DATATABLE CALL FOR KYC LOG DATA DATA ********/
    function get_new_common_candidate_data_for_kyc_ajax($kyc_status_list='',$page_url='')
    {
      $this->load->model('ncvet/Ncvet_model');

      $kyc_inprogress_candidate_data = array();
   
      $table = 'ncvet_candidates nc';

      $column_order = array('nc.candidate_id', 'nc.regnumber', 'CONCAT(nc.salutation, " ", nc.first_name, IF(nc.middle_name != "", CONCAT(" ", nc.middle_name), ""), IF(nc.last_name != "", CONCAT(" ", nc.last_name), "")) AS DispName', 'nc.created_on', 'nc.mobile_no', 'nc.email_id', 'IF(nc.kyc_status=0, "Pending", IF(nc.kyc_status=1, "In Progress", IF(nc.kyc_status=2, "Approved", IF(nc.kyc_status=3, "Rejected", "-")))) AS KycStatus', 'nc.recommender_id', 'nc.approver_id', 'nc.kyc_status', 'nc.kyc_recommender_status', 'nc.img_ediited_on', 'nc.kyc_approver_status'); //, 'nc.exam_code' //SET COLUMNS FOR SORT
      
      $column_search = array('nc.regnumber', 'CONCAT(nc.salutation, " ", nc.first_name, IF(nc.middle_name != "", CONCAT(" ", nc.middle_name), ""), IF(nc.last_name != "", CONCAT(" ", nc.last_name), ""))', 'nc.created_on', 'nc.mobile_no', 'nc.email_id', 'IF(nc.kyc_status=0, "Pending", IF(nc.kyc_status=1, "In Progress", IF(nc.kyc_status=2, "Approved", IF(nc.kyc_status=3, "Rejected", "-"))))'); //, 'nc.exam_code' //SET COLUMN FOR SEARCH
      $order = array('nc.candidate_id' => 'DESC'); // DEFAULT ORDER
      
      /*$WhereForTotal = "WHERE nc.login_type = '".$this->login_user_type."' AND nc.login_id = '".$this->login_admin_id."' ";*/  

      $WhereForTotal = "WHERE nc.kyc_eligible_date != '' AND nc.kyc_eligible_date IS NOT NULL AND nc.kyc_eligible_date != '0000-00-00 00:00:00' AND nc.is_deleted = 0 AND nc.regnumber != '' AND nc.benchmark_disability = 'N' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE nc.kyc_eligible_date != '' AND nc.kyc_eligible_date IS NOT NULL AND nc.kyc_eligible_date != '0000-00-00 00:00:00' AND nc.is_deleted = 0 AND nc.regnumber != '' AND nc.benchmark_disability = 'N' ";      
      
      if($_POST['search']['value']) // DATATABLE SEARCH
      {
        $Where .= " AND (";
        for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['search']['value']) )."%' ESCAPE '!' OR "; }
        $Where = substr_replace( $Where, "", -3 );
        $Where .= ')';
      }  
      
      //CUSTOM SEARCH 
      //$Where .= " AND nc.module_name = '".$module_name."'"; 

      if($kyc_status_list != "enrolled"){
        $Where .= " AND (nc.img_ediited_on = '' OR nc.img_ediited_on IS NULL OR nc.img_ediited_on = '0000-00-00 00:00:00') "; 
      } 

      if($kyc_status_list == "enrolled")
      {
        if($this->login_user_type == '1') //Recommender
        { 
          //$Where .= " AND nc.recommender_id = '0' AND (nc.kyc_recommender_status IS NULL OR nc.kyc_recommender_status = '' OR nc.kyc_recommender_status = '0') AND nc.approver_id = '0' AND nc.kyc_status IN(0,2) ";  

        }
        else if($this->login_user_type == '2') //Approver
        {
          //$this->db->where('recommender_id != ', $this->login_related_id); 
          $Where .= " AND nc.approver_id = '0' AND (nc.kyc_approver_status IS NULL OR nc.kyc_approver_status = '' OR nc.kyc_approver_status = '0') AND nc.recommender_id > 0 AND nc.kyc_recommender_status = '2' AND nc.kyc_status IN(1) "; 
        } 
      }
      else if($kyc_status_list == "pending")
      {
        if($this->login_user_type == '1') //Recommender
        { 
          //$Where .= " AND nc.recommender_id = '0' AND (nc.kyc_recommender_status IS NULL OR nc.kyc_recommender_status = '' OR nc.kyc_recommender_status = '0') AND nc.approver_id = '0' AND nc.kyc_status IN(0,2) AND DATE(nc.created_on) < '".date("Y-m-d")."' "; 
          $Where .= " AND nc.recommender_id = '0' AND (nc.kyc_recommender_status IS NULL OR nc.kyc_recommender_status = '' OR nc.kyc_recommender_status = '0') AND nc.approver_id = '0' AND nc.kyc_status IN(0,2) "; 

        }
        else if($this->login_user_type == '2') //Approver
        {
          //$this->db->where('recommender_id != ', $this->login_related_id); 
          //$Where .= " AND nc.approver_id = '0' AND (nc.kyc_approver_status IS NULL OR nc.kyc_approver_status = '' OR nc.kyc_approver_status = '0') AND nc.recommender_id > 0 AND nc.kyc_recommender_status = '2' AND nc.kyc_status IN(1) AND DATE(nc.created_on) < '".date("Y-m-d")."' "; 
          $Where .= " AND nc.approver_id = '0' AND (nc.kyc_approver_status IS NULL OR nc.kyc_approver_status = '' OR nc.kyc_approver_status = '0') AND nc.recommender_id > 0 AND nc.kyc_recommender_status = '2' AND nc.kyc_status IN(1) "; 
        } 
      }
      else if($kyc_status_list == "recommend")
      {
        if($this->login_user_type == '1') //Recommender
        { 
          $Where .= " AND nc.recommender_id = '".$this->login_admin_id."' AND nc.kyc_recommender_status = '1' AND nc.approver_id = '0' AND nc.kyc_status IN(1) "; 

        }
        else if($this->login_user_type == '2') //Approver
        {
          //$this->db->where('recommender_id != ', $this->login_related_id); 
          $Where .= " AND nc.approver_id = '".$this->login_admin_id."' AND nc.kyc_approver_status= '1' AND nc.recommender_id > 0 AND nc.kyc_recommender_status = '2' AND nc.kyc_status IN(1) "; 
        } 
      }

      

      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY "; foreach($order as $o_key=>$o_val) { $Order .= $o_key." ".$o_val.", "; } $Order = rtrim($Order,", "); }
      
      $Limit = ""; if ($_POST['length'] != '-1' ) { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT 
      
      $join_qry = "";
            
      $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
      //echo $print_query;die;
      $Result = $this->db->query($print_query);  
      $Rows = $Result->result_array();
      
      $TotalResult = $this->Ncvet_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$WhereForTotal);
      $FilteredResult = $this->Ncvet_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);
      
      $data = array();
      $no = $_POST['start'];    
      
      foreach ($Rows as $Res) 
      {
        $no++;
        $row = array();
        
        $row[] = $no;
        $row[] = $Res['regnumber'];
        $row[] = $Res['DispName'];
        $row[] = $Res['created_on'];
        $row[] = $Res['mobile_no'];
        $row[] = $Res['email_id']; 

        if( $Res['KycStatus'] == "In Progress" && $this->login_user_type == '1' && $Res['kyc_recommender_status'] == '1' && (empty($Res['kyc_approver_status']) || $Res['kyc_approver_status'] == '0' ))
        {
          if($Res['img_ediited_on'] != '' && $Res['img_ediited_on'] != '0000-00-00 00:00:00'){
            $row[] = 'Attend Re-KYC';
          }
          else{
            $row[] = 'Attend';
          }
        }
        else if( $Res['KycStatus'] == "In Progress" && $this->login_user_type == '1' && $Res['kyc_recommender_status'] == '2' && (empty($Res['kyc_approver_status']) || $Res['kyc_approver_status'] == '0' ))
        {
          $row[] = 'Recommended';
        } 
        else if( $Res['KycStatus'] == "In Progress" && $this->login_user_type == '1' && $Res['kyc_recommender_status'] == '2' && $Res['kyc_approver_status'] == '1' && $Res['approver_id'] > 0)
        {
          if($Res['img_ediited_on'] != '' && $Res['img_ediited_on'] != '0000-00-00 00:00:00'){
            $row[] = 'Recommended Re-KYC';
          }else{
            $row[] = 'Recommended';
          } 
        } 
        else if($Res['KycStatus'] == "Pending" && $Res['img_ediited_on'] != '' && $Res['img_ediited_on'] != '0000-00-00 00:00:00')
        {
          $row[] = 'Re-KYC';
        }
        else if($Res['KycStatus'] == "In Progress" && $this->login_user_type == '2' && $Res['kyc_recommender_status'] == '2' && $Res['img_ediited_on'] != '' && $Res['img_ediited_on'] != '0000-00-00 00:00:00')
        {
          $row[] = 'Re-KYC';
        }
        else if( $Res['KycStatus'] == "In Progress" && $this->login_user_type == '2' && $Res['kyc_approver_status'] == '1' && $Res['recommender_id'] != '0' && $Res['kyc_recommender_status'] == '2' )
        {
          if($Res['img_ediited_on'] != '' && $Res['img_ediited_on'] != '0000-00-00 00:00:00'){
            $row[] = 'Attend Re-KYC';
          }
          else{
            $row[] = 'Attend';
          }
        }
        else if( $Res['KycStatus'] == "Rejected" && $this->login_user_type == '1' && $Res['kyc_recommender_status'] == '3')
        {
          if($Res['img_ediited_on'] != '' && $Res['img_ediited_on'] != '0000-00-00 00:00:00'){
            $row[] = 'Rejected Re-KYC';
          }else{
            $row[] = 'Rejected';
          } 
        }  
        else if( $Res['KycStatus'] == "Rejected" && $this->login_user_type == '1' && $Res['kyc_recommender_status'] == '2' && $Res['kyc_approver_status'] == '3' && $Res['approver_id'] > 0)
        {
          if($Res['img_ediited_on'] != '' && $Res['img_ediited_on'] != '0000-00-00 00:00:00'){
            $row[] = 'Rejected Re-KYC';
          }else{
            $row[] = 'Rejected';
          } 
        }
        else
        { 
            $row[] = $Res['KycStatus']; 
        }

        $Where .= " AND nc.recommender_id = '".$this->login_admin_id."' AND nc.kyc_recommender_status = '2' AND nc.kyc_status IN(1) ";

        $btn_str = ' <div class="text-center no_wrap"> ';

        if($kyc_status_list == "recommend"){
          $btn_str .= ' <a href="' . site_url('ncvet/kyc/kyc_all/candidate_details/'.url_encode($Res['candidate_id']).'/NM/new/'.$page_url).'" class="btn btn-success btn-xs" title="View for KYC"><i class="fa fa-eye" aria-hidden="true"></i></a> ';
        }
        else
        {
          //$Where .= " AND nc.recommender_id = '0' AND (nc.kyc_recommender_status IS NULL OR nc.kyc_recommender_status = '' OR nc.kyc_recommender_status = '0') AND nc.approver_id = '0' AND nc.kyc_status IN(0,2) "; 
          //$Where .= " AND (nc.img_ediited_on = '' OR nc.img_ediited_on IS NULL OR nc.img_ediited_on = '0000-00-00 00:00:00') ";   

          if( $this->login_user_type == '1' && ($kyc_status_list == "enrolled" || $kyc_status_list == "pending") && $Res['recommender_id'] == "0" && $Res['approver_id'] == "0" && $Res['kyc_status'] == "0" && (empty($Res['kyc_recommender_status']) || $Res['kyc_recommender_status'] == '' || $Res['kyc_recommender_status'] == '0') && (empty($Res['img_ediited_on']) || $Res['img_ediited_on'] == '' || $Res['img_ediited_on'] == '0000-00-00 00:00:00') ){
            $btn_str .= ' <a href="' . site_url('ncvet/kyc/kyc_all/update_as_recommend/'.url_encode($Res['candidate_id']).'/NM/new').'" class="btn btn-success" title="DO KYC">DO KYC</a> ';
          }else if( $this->login_user_type == '2' && ($kyc_status_list == "enrolled" || $kyc_status_list == "pending") && $Res['recommender_id'] > 0 && $Res['approver_id'] == "0" && $Res['kyc_status'] == "1" && $Res['kyc_recommender_status'] == "2" && (empty($Res['kyc_approver_status']) || $Res['kyc_approver_status'] == '' || $Res['kyc_approver_status'] == '0') && (empty($Res['img_ediited_on']) || $Res['img_ediited_on'] == '' || $Res['img_ediited_on'] == '0000-00-00 00:00:00') ){
            $btn_str .= ' <a href="' . site_url('ncvet/kyc/kyc_all/update_as_recommend/'.url_encode($Res['candidate_id']).'/NM/new').'" class="btn btn-success" title="DO KYC">DO KYC</a> ';
          }
          else{
            $btn_str .= '-'; //$Res['KycStatus'];
          }
          
        }

         
       
        $btn_str .= ' </div>';
        $row[] = $btn_str;
        
        $data[] = $row; 
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
    }/******** END : SERVER SIDE DATATABLE CALL FOR KYC LOG DATA DATA ********/
  
    /*List All Rejected Candidate KYC */
    public function rejected_candidate()
    {    
      $dispName = $this->Kyc_model->getLoggedInUserDetails($this->login_admin_id, $this->login_user_type); 

      $data['act_id'] = "New Candidate KYC";
      $data['sub_act_id'] = "Rejected Candidates";
      $data['page_title'] = 'Rejected Candidates';
      $data['dispName'] = $dispName;

      $data['action_name'] = 'get_common_candidate_data_for_kyc_ajax';

      $data['kyc_status_list'] = 'rejected';

      $data['page_url'] = 'rejected_candidate';
      
      $this->load->view('ncvet/kyc/kyc_candidate_list', $data);
    }
    /*List All Rejected Candidate KYC */

    /*List All Approved Candidate KYC */
    public function approved_candidate()
    {    
      $dispName = $this->Kyc_model->getLoggedInUserDetails($this->login_admin_id, $this->login_user_type); 

      $data['act_id'] = "New Candidate KYC";
      $data['sub_act_id'] = ($this->session->userdata('NCVET_KYC_ADMIN_TYPE') == '1' ? 'Recommended Candidates' : 'Approved Candidates' );
      $data['page_title'] = ($this->session->userdata('NCVET_KYC_ADMIN_TYPE') == '1' ? 'Recommended Candidates' : 'Approved Candidates' );
      $data['dispName'] = $dispName;

      $data['action_name'] = 'get_common_candidate_data_for_kyc_ajax';

      $data['kyc_status_list'] = 'approved';

      $data['page_url'] = 'approved_candidate';
      
      $this->load->view('ncvet/kyc/kyc_candidate_list', $data);
    }
    /*List All Rejected Candidate KYC */

    /******** START : SERVER SIDE DATATABLE CALL FOR KYC LOG DATA DATA ********/
    function get_common_candidate_data_for_kyc_ajax($kyc_status_list='',$page_url='')
    {
      $this->load->model('ncvet/Ncvet_model');

      $kyc_inprogress_candidate_data = array();
   
      $table = 'ncvet_candidates nc';
      
      $column_order = array('nc.candidate_id', 'nc.regnumber', 'CONCAT(nc.salutation, " ", nc.first_name, IF(nc.middle_name != "", CONCAT(" ", nc.middle_name), ""), IF(nc.last_name != "", CONCAT(" ", nc.last_name), "")) AS DispName', 'nc.created_on', 'nc.mobile_no', 'nc.email_id', 'IF(nc.kyc_status=0, "Pending", IF(nc.kyc_status=1, "In Progress", IF(nc.kyc_status=2, "Approved", IF(nc.kyc_status=3, "Rejected", "-")))) AS KycStatus', 'nc.kyc_recommender_status', 'nc.kyc_approver_status'); //, 'nc.exam_code' //SET COLUMNS FOR SORT
      
      $column_search = array('nc.regnumber', 'CONCAT(nc.salutation, " ", nc.first_name, IF(nc.middle_name != "", CONCAT(" ", nc.middle_name), ""), IF(nc.last_name != "", CONCAT(" ", nc.last_name), ""))', 'nc.created_on', 'nc.mobile_no', 'nc.email_id', 'IF(nc.kyc_status=0, "Pending", IF(nc.kyc_status=1, "In Progress", IF(nc.kyc_status=2, "Approved", IF(nc.kyc_status=3, "Rejected", "-"))))'); //, 'nc.exam_code' //SET COLUMN FOR SEARCH
      $order = array('nc.candidate_id' => 'DESC'); // DEFAULT ORDER
      
      /*$WhereForTotal = "WHERE nc.login_type = '".$this->login_user_type."' AND nc.login_id = '".$this->login_admin_id."' ";*/  

      $WhereForTotal = "WHERE nc.kyc_eligible_date != '' AND nc.kyc_eligible_date IS NOT NULL AND nc.kyc_eligible_date != '0000-00-00 00:00:00' AND nc.is_deleted = 0 AND nc.benchmark_disability = 'N' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE nc.kyc_eligible_date != '' AND nc.kyc_eligible_date IS NOT NULL AND nc.kyc_eligible_date != '0000-00-00 00:00:00' AND nc.is_deleted = 0 AND nc.benchmark_disability = 'N' ";      
      
      if($_POST['search']['value']) // DATATABLE SEARCH
      {
        $Where .= " AND (";
        for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['search']['value']) )."%' ESCAPE '!' OR "; }
        $Where = substr_replace( $Where, "", -3 );
        $Where .= ')';
      }  
      
      //CUSTOM SEARCH 
      //$Where .= " AND nc.module_name = '".$module_name."'"; 

      $Where .= " AND (nc.img_ediited_on = '' OR nc.img_ediited_on IS NULL OR nc.img_ediited_on = '0000-00-00 00:00:00') "; 

      if($this->login_user_type == '1') //Recommender
      { 
        if($kyc_status_list == "rejected"){
          $Where .= " AND nc.recommender_id = '".$this->login_admin_id."' AND nc.kyc_recommender_status = '3' AND nc.kyc_status IN(3) ";
        }else if($kyc_status_list == "approved"){
          $Where .= " AND nc.recommender_id = '".$this->login_admin_id."' AND nc.kyc_recommender_status = '2' AND nc.kyc_status IN(1) ";
        } 
      }
      else if($this->login_user_type == '2') //Approver
      {
        //$this->db->where('recommender_id != ', $this->login_related_id); 
        if($kyc_status_list == "rejected"){
          $Where .= " AND nc.approver_id = '".$this->login_admin_id."' AND nc.kyc_approver_status= '3' AND nc.recommender_id > 0 AND nc.kyc_recommender_status = '2' AND nc.kyc_status IN(3) "; 
        }else if($kyc_status_list == "approved"){
          $Where .= " AND nc.approver_id = '".$this->login_admin_id."' AND nc.kyc_approver_status= '2' AND nc.recommender_id > 0 AND nc.kyc_recommender_status = '2' AND nc.kyc_status IN(2) "; 
        }
        
      }  

      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY "; foreach($order as $o_key=>$o_val) { $Order .= $o_key." ".$o_val.", "; } $Order = rtrim($Order,", "); }
      
      $Limit = ""; if ($_POST['length'] != '-1' ) { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT 
      
      $join_qry = "";
            
      $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
      //echo $print_query;die;
      $Result = $this->db->query($print_query);  
      $Rows = $Result->result_array();
      
      $TotalResult = $this->Ncvet_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$WhereForTotal);
      $FilteredResult = $this->Ncvet_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);
      
      $data = array();
      $no = $_POST['start'];    
      
      foreach ($Rows as $Res) 
      {
        $no++;
        $row = array();
        
        $row[] = $no;
        $row[] = $Res['regnumber'];
        $row[] = $Res['DispName'];
        $row[] = $Res['created_on'];
        $row[] = $Res['mobile_no'];
        $row[] = $Res['email_id'];
        

        if($Res['KycStatus'] == "In Progress" && $this->login_user_type == '1' && $Res['kyc_recommender_status'] == '2' && ($Res['kyc_approver_status'] == '0' || $Res['kyc_approver_status'] == '1'))
        {
          $row[] = 'Recommended'; //'Pending from Approver';
        } 
        else{
          $row[] = $Res['KycStatus'];
        }
 
        $btn_str = ' <div class="text-center no_wrap"> ';

        $btn_str .= ' <a href="' . site_url('ncvet/kyc/kyc_all/view_kyc_details/'.url_encode($Res['candidate_id']).'/'.$page_url).'" class="btn btn-success btn-xs" title="View KYC Status"><i class="fa fa-eye" aria-hidden="true"></i></a> ';
       
        $btn_str .= ' </div>';
        $row[] = $btn_str;

        $data[] = $row; 
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
    }/******** END : SERVER SIDE DATATABLE CALL FOR KYC LOG DATA DATA ********/



    /*List All Edit Candidate (Re-Kyc Candidates) KYC */
    public function edit_candidate()
    {    
      $dispName = $this->Kyc_model->getLoggedInUserDetails($this->login_admin_id, $this->login_user_type); 

      $data['act_id'] = "Edit Candidate KYC";
      $data['sub_act_id'] = "Re-KYC Candidates";
      $data['page_title'] = 'Re-KYC Candidates';
      $data['dispName'] = $dispName;
      
      $data['action_name'] = 'get_edit_common_candidate_data_for_kyc_ajax';

      $data['kyc_status_list'] = 'rekyc';
      $data['page_url'] = 'edit_candidate';

      $this->load->view('ncvet/kyc/kyc_candidate_list', $data);
    }
    /*List All Edit Candidate (Re-Kyc Candidates) KYC */

    /*List All Edit Candidate (Re-Kyc Candidates) KYC */
    public function edit_pending_candidate()
    {    
      $dispName = $this->Kyc_model->getLoggedInUserDetails($this->login_admin_id, $this->login_user_type); 

      $data['act_id'] = "Edit Candidate KYC";
      $data['sub_act_id'] = "Pending for Re-KYC";
      $data['page_title'] = 'Pending for Re-KYC';
      $data['dispName'] = $dispName;
      
      $data['action_name'] = 'get_edit_common_candidate_data_for_kyc_ajax';

      $data['kyc_status_list'] = 'editpending';
      $data['page_url'] = 'edit_pending_candidate';

      $this->load->view('ncvet/kyc/kyc_candidate_list', $data);
    }
    /*List All Edit Candidate (Re-Kyc Candidates) KYC */

    /*List All Edit Attend Candidates KYC */
    public function edit_recommend_candidate()
    {    
      $dispName = $this->Kyc_model->getLoggedInUserDetails($this->login_admin_id, $this->login_user_type); 

      $data['act_id'] = "Edit Candidate KYC";
      $data['sub_act_id'] = "Edit Attend Candidates";
      $data['page_title'] = 'Edit Attend Candidates';
      $data['dispName'] = $dispName;
      
      $data['action_name'] = 'get_edit_common_candidate_data_for_kyc_ajax';

      $data['kyc_status_list'] = 'editrecommend';
      $data['page_url'] = 'edit_recommend_candidate';

      $this->load->view('ncvet/kyc/kyc_candidate_list', $data);
    }
    /*List All Edit Attend Candidates KYC */

    /******** START : SERVER SIDE DATATABLE CALL FOR KYC LOG DATA DATA ********/
    function get_edit_common_candidate_data_for_kyc_ajax($kyc_status_list='',$page_url='')
    {
      $this->load->model('ncvet/Ncvet_model');

      $kyc_inprogress_candidate_data = array();
   
      $table = 'ncvet_candidates nc';
      
      $column_order = array('nc.candidate_id', 'nc.regnumber', 'CONCAT(nc.salutation, " ", nc.first_name, IF(nc.middle_name != "", CONCAT(" ", nc.middle_name), ""), IF(nc.last_name != "", CONCAT(" ", nc.last_name), "")) AS DispName', 'nc.created_on', 'nc.mobile_no', 'nc.email_id', 'IF(nc.kyc_status=0, "Pending", IF(nc.kyc_status=1, "In Progress", IF(nc.kyc_status=2, "Approved", IF(nc.kyc_status=3, "Rejected", "-")))) AS KycStatus', 'nc.kyc_recommender_status', 'nc.approver_id', 'nc.kyc_approver_status', 'nc.recommender_id'); //, 'nc.exam_code' //SET COLUMNS FOR SORT
      
      $column_search = array('nc.regnumber', 'CONCAT(nc.salutation, " ", nc.first_name, IF(nc.middle_name != "", CONCAT(" ", nc.middle_name), ""), IF(nc.last_name != "", CONCAT(" ", nc.last_name), ""))', 'nc.created_on', 'nc.mobile_no', 'nc.email_id', 'IF(nc.kyc_status=0, "Pending", IF(nc.kyc_status=1, "In Progress", IF(nc.kyc_status=2, "Approved", IF(nc.kyc_status=3, "Rejected", "-"))))'); //, 'nc.exam_code' //SET COLUMN FOR SEARCH
      $order = array('nc.candidate_id' => 'DESC'); // DEFAULT ORDER
      
      /*$WhereForTotal = "WHERE nc.login_type = '".$this->login_user_type."' AND nc.login_id = '".$this->login_admin_id."' ";*/  

      $WhereForTotal = "WHERE nc.kyc_eligible_date != '' AND nc.kyc_eligible_date IS NOT NULL AND nc.kyc_eligible_date != '0000-00-00 00:00:00' AND nc.is_deleted = 0 AND nc.benchmark_disability = 'N' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE nc.kyc_eligible_date != '' AND nc.kyc_eligible_date IS NOT NULL AND nc.kyc_eligible_date != '0000-00-00 00:00:00' AND nc.is_deleted = 0 AND nc.benchmark_disability = 'N' ";      
      
      if($_POST['search']['value']) // DATATABLE SEARCH
      {
        $Where .= " AND (";
        for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['search']['value']) )."%' ESCAPE '!' OR "; }
        $Where = substr_replace( $Where, "", -3 );
        $Where .= ')';
      }  
      
      //CUSTOM SEARCH 
      //$Where .= " AND nc.module_name = '".$module_name."'"; 

      $Where .= " AND (nc.img_ediited_on != '' AND nc.img_ediited_on IS NOT NULL AND nc.img_ediited_on != '0000-00-00 00:00:00') "; 
  

      if($kyc_status_list == "rekyc")
      {
        if($this->login_user_type == '1') //Recommender
        { 
          $Where .= " AND nc.recommender_id = '0' AND (nc.kyc_recommender_status IS NULL OR nc.kyc_recommender_status = '' OR nc.kyc_recommender_status = '0') AND nc.approver_id = '0' AND nc.kyc_status IN(0,2) ";  
        }
        else if($this->login_user_type == '2') //Approver
        {
          //$this->db->where('recommender_id != ', $this->login_related_id); 
          $Where .= " AND nc.approver_id = '0' AND (nc.kyc_approver_status IS NULL OR nc.kyc_approver_status = '' OR nc.kyc_approver_status = '0') AND nc.recommender_id > 0 AND nc.kyc_recommender_status = '2' AND nc.kyc_status IN(1) "; 
        }
      }
      else if($kyc_status_list == "editpending")
      {
        if($this->login_user_type == '1') //Recommender
        { 
          //$Where .= " AND nc.recommender_id = '0' AND (nc.kyc_recommender_status IS NULL OR nc.kyc_recommender_status = '' OR nc.kyc_recommender_status = '0') AND nc.approver_id = '0' AND nc.kyc_status IN(0,2) AND DATE(nc.created_on) < '".date("Y-m-d")."' ";  
          $Where .= " AND nc.recommender_id = '0' AND (nc.kyc_recommender_status IS NULL OR nc.kyc_recommender_status = '' OR nc.kyc_recommender_status = '0') AND nc.approver_id = '0' AND nc.kyc_status IN(0,2) ";  
        }
        else if($this->login_user_type == '2') //Approver
        {
          //$this->db->where('recommender_id != ', $this->login_related_id); 
          $Where .= " AND nc.approver_id = '0' AND (nc.kyc_approver_status IS NULL OR nc.kyc_approver_status = '' OR nc.kyc_approver_status = '0') AND nc.recommender_id > 0 AND nc.kyc_recommender_status = '2' AND nc.kyc_status IN(1) AND DATE(nc.created_on) < '".date("Y-m-d")."' "; 
        }
      }
      else if($kyc_status_list == "editrecommend")
      {
        if($this->login_user_type == '1') //Recommender
        { 
          $Where .= " AND nc.recommender_id = '".$this->login_admin_id."' AND nc.kyc_recommender_status = '1' AND nc.approver_id = '0' AND nc.kyc_status IN(1) "; 

        }
        else if($this->login_user_type == '2') //Approver
        {
          //$this->db->where('recommender_id != ', $this->login_related_id); 
          $Where .= " AND nc.approver_id = '".$this->login_admin_id."' AND nc.kyc_approver_status= '1' AND nc.recommender_id > 0 AND nc.kyc_recommender_status = '2' AND nc.kyc_status IN(1) "; 
        } 
      }

      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY "; foreach($order as $o_key=>$o_val) { $Order .= $o_key." ".$o_val.", "; } $Order = rtrim($Order,", "); }
      
      $Limit = ""; if ($_POST['length'] != '-1' ) { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT 
      
      $join_qry = "";
            
      $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
      //echo $print_query;die;
      $Result = $this->db->query($print_query);  
      $Rows = $Result->result_array();
      
      $TotalResult = $this->Ncvet_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$WhereForTotal);
      $FilteredResult = $this->Ncvet_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);
      
      $data = array();
      $no = $_POST['start'];    
      
      foreach ($Rows as $Res) 
      {
        $no++;
        $row = array();
        
        $row[] = $no;
        $row[] = $Res['regnumber'];
        $row[] = $Res['DispName'];
        $row[] = $Res['created_on'];
        $row[] = $Res['mobile_no'];
        $row[] = $Res['email_id'];
 

        if( $Res['KycStatus'] == "In Progress" && $Res['kyc_recommender_status'] == '1' && $Res['approver_id'] == '0')
        {
          $row[] = 'Attend Re-KYC';
        }
        else if( $Res['KycStatus'] == "In Progress" && $Res['kyc_approver_status'] == '1' && $Res['kyc_recommender_status'] == '2' && $Res['recommender_id'] > 0)
        {
          $row[] = 'Attend Re-KYC';
        }
        else{
          $row[] = $Res['KycStatus'];
        } 

        $btn_str = ' <div class="text-center no_wrap"> ';


        if($kyc_status_list == "editrecommend"){
          $btn_str .= ' <a href="' . site_url('ncvet/kyc/kyc_all/candidate_details/'.url_encode($Res['candidate_id']).'/NM/edited/'.$page_url).'" class="btn btn-success btn-xs" title="View for KYC"><i class="fa fa-eye" aria-hidden="true"></i></a> ';
        }
        else
        {
          $btn_str .= ' <a href="' . site_url('ncvet/kyc/kyc_all/update_as_recommend/'.url_encode($Res['candidate_id']).'/NM/edited').'" class="btn btn-success" title="DO KYC">DO RE-KYC</a> ';
        } 
       
        $btn_str .= ' </div>';
        $row[] = $btn_str;
        
        $data[] = $row; 
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
    }/******** END : SERVER SIDE DATATABLE CALL FOR KYC LOG DATA DATA ********/

    /*List All Edit Rejected Candidate KYC */
    public function rejected_edit_candidate()
    {    
      $dispName = $this->Kyc_model->getLoggedInUserDetails($this->login_admin_id, $this->login_user_type); 

      $data['act_id'] = "Edit Candidate KYC";
      $data['sub_act_id'] = "Edit Rejected Candidates";
      $data['page_title'] = 'Edit Rejected Candidates';
      $data['dispName'] = $dispName;

      $data['action_name'] = 'get_common_editcandidate_data_for_kyc_ajax';

      $data['kyc_status_list'] = 'editrejected';
      $data['page_url'] = 'rejected_edit_candidate';
      
      $this->load->view('ncvet/kyc/kyc_candidate_list', $data);
    }
    /*List All Edit Rejected Candidate KYC */

    /*List All Edit Approved Candidate KYC */
    public function approved_edit_candidate()
    {    
      $dispName = $this->Kyc_model->getLoggedInUserDetails($this->login_admin_id, $this->login_user_type); 

      $data['act_id'] = "Edit Candidate KYC";
      $data['sub_act_id'] = "Edit ".($this->session->userdata('NCVET_KYC_ADMIN_TYPE') == '1' ? 'Recommended Candidates' : 'Approved Candidates' );
      $data['page_title'] = "Edit ".($this->session->userdata('NCVET_KYC_ADMIN_TYPE') == '1' ? 'Recommended Candidates' : 'Approved Candidates' );
      $data['dispName'] = $dispName;

      $data['action_name'] = 'get_common_editcandidate_data_for_kyc_ajax';

      $data['kyc_status_list'] = 'editapproved';
      $data['page_url'] = 'approved_edit_candidate';
      
      $this->load->view('ncvet/kyc/kyc_candidate_list', $data);
    }
    /*List All Edit Approved Candidate KYC */

    /******** START : SERVER SIDE DATATABLE CALL FOR KYC LOG DATA DATA ********/
    function get_common_editcandidate_data_for_kyc_ajax($kyc_status_list='',$page_url='')
    {
      $this->load->model('ncvet/Ncvet_model');

      $kyc_inprogress_candidate_data = array();
   
      $table = 'ncvet_candidates nc';
      
      $column_order = array('nc.candidate_id', 'nc.regnumber', 'CONCAT(nc.salutation, " ", nc.first_name, IF(nc.middle_name != "", CONCAT(" ", nc.middle_name), ""), IF(nc.last_name != "", CONCAT(" ", nc.last_name), "")) AS DispName', 'nc.created_on', 'nc.mobile_no', 'nc.email_id', 'IF(nc.kyc_status=0, "Pending", IF(nc.kyc_status=1, "In Progress", IF(nc.kyc_status=2, "Approved", IF(nc.kyc_status=3, "Rejected", "-")))) AS KycStatus', 'nc.kyc_recommender_status', 'nc.kyc_approver_status'); //, 'nc.exam_code' //SET COLUMNS FOR SORT
      
      $column_search = array('nc.regnumber', 'CONCAT(nc.salutation, " ", nc.first_name, IF(nc.middle_name != "", CONCAT(" ", nc.middle_name), ""), IF(nc.last_name != "", CONCAT(" ", nc.last_name), ""))', 'nc.created_on', 'nc.mobile_no', 'nc.email_id', 'IF(nc.kyc_status=0, "Pending", IF(nc.kyc_status=1, "In Progress", IF(nc.kyc_status=2, "Approved", IF(nc.kyc_status=3, "Rejected", "-"))))'); //, 'nc.exam_code' //SET COLUMN FOR SEARCH
      $order = array('nc.candidate_id' => 'DESC'); // DEFAULT ORDER
      
      /*$WhereForTotal = "WHERE nc.login_type = '".$this->login_user_type."' AND nc.login_id = '".$this->login_admin_id."' ";*/  

      $WhereForTotal = "WHERE nc.kyc_eligible_date != '' AND nc.kyc_eligible_date IS NOT NULL AND nc.kyc_eligible_date != '0000-00-00 00:00:00' AND nc.is_deleted = 0 AND nc.benchmark_disability = 'N' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE nc.kyc_eligible_date != '' AND nc.kyc_eligible_date IS NOT NULL AND nc.kyc_eligible_date != '0000-00-00 00:00:00' AND nc.is_deleted = 0 AND nc.benchmark_disability = 'N' ";      
      
      if($_POST['search']['value']) // DATATABLE SEARCH
      {
        $Where .= " AND (";
        for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['search']['value']) )."%' ESCAPE '!' OR "; }
        $Where = substr_replace( $Where, "", -3 );
        $Where .= ')';
      }  
      
      //CUSTOM SEARCH 
      //$Where .= " AND nc.module_name = '".$module_name."'"; 
 
      $Where .= " AND (nc.img_ediited_on != '' AND nc.img_ediited_on IS NOT NULL AND nc.img_ediited_on != '0000-00-00 00:00:00') ";

      if($this->login_user_type == '1') //Recommender
      { 
        if($kyc_status_list == "editrejected"){
          $Where .= " AND nc.recommender_id = '".$this->login_admin_id."' AND nc.kyc_recommender_status = '3' AND nc.kyc_status IN(3) ";
        }else if($kyc_status_list == "editapproved"){
          $Where .= " AND nc.recommender_id = '".$this->login_admin_id."' AND nc.kyc_recommender_status = '2' AND nc.kyc_status IN(1) ";
        } 
      }
      else if($this->login_user_type == '2') //Approver
      {
        //$this->db->where('recommender_id != ', $this->login_related_id); 
        if($kyc_status_list == "editrejected"){
          $Where .= " AND nc.approver_id = '".$this->login_admin_id."' AND nc.kyc_approver_status= '3' AND nc.recommender_id > 0 AND nc.kyc_recommender_status = '2' AND nc.kyc_status IN(3) "; 
        }else if($kyc_status_list == "editapproved"){
          $Where .= " AND nc.approver_id = '".$this->login_admin_id."' AND nc.kyc_approver_status= '2' AND nc.recommender_id > 0 AND nc.kyc_recommender_status = '2' AND nc.kyc_status IN(2) "; 
        } 
      }  

      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY "; foreach($order as $o_key=>$o_val) { $Order .= $o_key." ".$o_val.", "; } $Order = rtrim($Order,", "); }
      
      $Limit = ""; if ($_POST['length'] != '-1' ) { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT 
      
      $join_qry = "";
            
      $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
      //echo $print_query;die;
      $Result = $this->db->query($print_query);  
      $Rows = $Result->result_array();
      
      $TotalResult = $this->Ncvet_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$WhereForTotal);
      $FilteredResult = $this->Ncvet_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);
      
      $data = array();
      $no = $_POST['start'];    
      
      foreach ($Rows as $Res) 
      {
        $no++;
        $row = array();
        
        $row[] = $no;
        $row[] = $Res['regnumber'];
        $row[] = $Res['DispName'];
        $row[] = $Res['created_on'];
        $row[] = $Res['mobile_no'];
        $row[] = $Res['email_id'];
 
        if($Res['KycStatus'] == "In Progress" && $this->login_user_type == '1' && $Res['kyc_recommender_status'] == '2' && $Res['kyc_approver_status'] != '2'){
          $row[] = 'Recommended Re-KYC';
        } 
        else{
          $row[] = $Res['KycStatus'];
        }
 
        $btn_str = ' <div class="text-center no_wrap"> ';

        $btn_str .= ' <a href="' . site_url('ncvet/kyc/kyc_all/view_kyc_details/'.url_encode($Res['candidate_id']).'/'.$page_url).'" class="btn btn-success btn-xs" title="View KYC Status"><i class="fa fa-eye" aria-hidden="true"></i></a> ';
       
        $btn_str .= ' </div>';
        $row[] = $btn_str;

        $data[] = $row; 
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
    }/******** END : SERVER SIDE DATATABLE CALL FOR KYC LOG DATA DATA ********/


    public function candidate_details($enc_candidate_id='', $membership_type='', $member_type='', $page_url='')
    {
      $candidate_id = url_decode($enc_candidate_id);

      $exam_code='';

      //echo "is == ".$this->session->flashdata('success_kyc');
      if($this->session->flashdata('success_kyc')){
        $this->session->set_flashdata('success_kyc',$this->session->flashdata('success_kyc'));
      }
      $module_name = 'ncvet'; //strtolower($module_name);
      
      /*$data['act_id'] = $module_name;
      $data['sub_act_id'] = "";*/    

      if($member_type == 'new'){
        $data['act_id'] = "New Candidate KYC";
      }else{
        $data['act_id'] = "Edit Candidate KYC";
      }
      
      if($page_url == "new_candidate"){    // New
        $data['sub_act_id'] = "Enrolled Candidates"; 
      }else if($page_url == "pending_candidate"){
        $data['sub_act_id'] = "Pending for KYC"; 
      }else if($page_url == "recommend_candidate"){
        $data['sub_act_id'] = "Attend Candidates"; 
      }else if($this->session->userdata('NCVET_KYC_ADMIN_TYPE') == '1' && $page_url == "approved_candidate"){
        $data['sub_act_id'] = "Recommended Candidates"; 
      }else if($this->session->userdata('NCVET_KYC_ADMIN_TYPE') == '2' && $page_url == "approved_candidate"){
        $data['sub_act_id'] = "Approved Candidates"; 
      }else if($page_url == "rejected_candidate"){
        $data['sub_act_id'] = "Rejected Candidates"; 
      }else if($page_url == "edit_candidate"){   // Edit
        $data['sub_act_id'] = "Re-KYC Candidates"; 
      }else if($page_url == "edit_pending_candidate"){
        $data['sub_act_id'] = "Pending for Re-KYC"; 
      }else if($page_url == "edit_recommend_candidate"){
        $data['sub_act_id'] = "Edit Attend Candidates"; 
      }else if($this->session->userdata('NCVET_KYC_ADMIN_TYPE') == '1' && $page_url == "approved_edit_candidate"){
        $data['sub_act_id'] = "Edit Recommended Candidates"; 
      }else if($this->session->userdata('NCVET_KYC_ADMIN_TYPE') == '2' && $page_url == "approved_edit_candidate"){
        $data['sub_act_id'] = "Edit Approved Candidates"; 
      }else if($page_url == "rejected_edit_candidate"){
        $data['sub_act_id'] = "Edit Rejected Candidates"; 
      }

      $data['page_url'] = $page_url;

      $disp_title = 'KYC';
      $form_membership_type_arr = $form_member_type_arr = $form_exam_codes_arr = array();
      $photo_path = $sign_path = $id_proof_path = $declaration_path = $institute_idproof_path = $qualification_certificate_path = $exp_certificate_path = $aadhar_file_path = '';

      /* START : CHECK FOR CORRECT MODULE NAME PASSED TO URL */
      if($candidate_id != '') 
      { 
        $common_data = $this->get_common_data($module_name);
        $disp_title = $common_data['disp_title']; 
        $form_membership_type_arr = $common_data['form_membership_type_arr']; 
        $form_member_type_arr = $common_data['form_member_type_arr']; 
        $form_exam_codes_arr = $common_data['form_exam_codes_arr'];
        $photo_path = $common_data['photo_path'];
        $sign_path = $common_data['sign_path'];
        $id_proof_path = $common_data['id_proof_path'];
        $declaration_path = $common_data['declaration_path'];
        $institute_idproof_path = $common_data['institute_idproof_path'];
        $qualification_certificate_path = $common_data['qualification_certificate_path'];
        $exp_certificate_path = $common_data['exp_certificate_path'];
        $aadhar_file_path = $common_data['aadhar_file_path'];
      }
      else
      {
        //echo "==".$module_name;die;
        $this->session->set_flashdata('error','Invalid URL accessed');
        redirect(site_url('ncvet/kyc/kyc_dashboard'));
      }/* END : CHECK FOR CORRECT MODULE NAME PASSED TO URL */

      /* START : CHECK FOR CORRECT MEMBERSHIP TYPE, MEMBER TYPE & EXAM CODE AS PER THE MODULE */
      if(!array_key_exists($membership_type, $form_membership_type_arr))
      {
        //echo $membership_type."==".print_r($form_membership_type_arr);die;
        $this->session->set_flashdata('error','Invalid URL accessed');
        redirect(site_url('ncvet/kyc/kyc_dashboard'));
      }
      else if(!array_key_exists($member_type, $form_member_type_arr))
      {
        //echo "==".$module_name;die;
        $this->session->set_flashdata('error','Invalid URL accessed');
        redirect(site_url('ncvet/kyc/kyc_dashboard'));
      }
      else if($membership_type == 'NM' && !in_array($exam_code, $form_exam_codes_arr))
      {
        //echo "==".$module_name;die;
        //$this->session->set_flashdata('error','Invalid URL accessed');
        //redirect(site_url('ncvet/kyc/kyc_dashboard'));
      }/* END : CHECK FOR CORRECT MEMBERSHIP TYPE, MEMBER TYPE & EXAM CODE AS PER THE MODULE */
      
      $kyc_pending_candidate_data = $this->get_kyc_pending_candidate_details($candidate_id, $membership_type, $member_type, $form_exam_codes_arr, $exam_code, $is_dropdown = '');      
      $kyc_inprogress_candidate_data = $this->get_kyc_inprogress_candidate_details($candidate_id, $form_exam_codes_arr);
      
      //START : GET INPROGRESS CANDIDATE DATA COUNT & GET TOTAL KYC PENDING CANDIDATE DATA COUNT. IF INPROGRESS CANDIDATE COUNT IS ZERO & TOTAL PENDING CANDIDATE COUNT IS HIGHER THAN ZERO, THEN ALLOCATE THAT MEMBER FOR KYC
      if(count($kyc_pending_candidate_data) > 0 && count($kyc_inprogress_candidate_data) == 0)
      {
        $update_qry_res = '';
        $up_candidiate = array();
        $up_candidiate['kyc_status'] = '1';

        if($this->login_user_type == '1') //RECOMMENDER
        {
          $up_candidiate['kyc_recommender_status'] = '1';
          $up_candidiate['recommender_id'] = $this->login_admin_id;
          $up_candidiate['kyc_recommender_date'] = date('Y-m-d H:i:s');            
        }
        else if($this->login_user_type == '2') //APPROVER
        {
          $up_candidiate['kyc_approver_status'] = '1';
          $up_candidiate['approver_id'] = $this->login_admin_id;
          $up_candidiate['kyc_approver_date'] = date('Y-m-d H:i:s');
        }

        if($module_name == 'ncvet' && count($up_candidiate) > 0)//FOR NCVET MODULE
        {
          //UPDATE RECORDS IN ncvet_candidates table
          $update_qry_res = $this->master_model->updateRecord('ncvet_candidates', $up_candidiate, array('candidate_id'=>$kyc_pending_candidate_data[0]['candidate_id']));
        }       
          
        if($update_qry_res)
        {
          $session_msg = $this->session->flashdata('success');
          $this->session->set_flashdata('success',$session_msg);
          redirect(site_url('ncvet/kyc/kyc_all/candidate_details/'.url_encode($kyc_pending_candidate_data[0]['candidate_id']).'/'.$membership_type.'/'.$member_type.'/'.$exam_code));
        }
      }
      else if(count($kyc_pending_candidate_data) == 0 && count($kyc_inprogress_candidate_data) == 0)
      {
        //echo count($kyc_pending_candidate_data)." == ".count($kyc_inprogress_candidate_data);die;
        $this->session->set_flashdata('error','No candidate available for selected criteria');
        redirect(site_url('ncvet/kyc/kyc_dashboard'));
      }//END : GET INPROGRESS CANDIDATE DATA COUNT & GET TOTAL PENDING CANDIDATE DATA COUNT. IF INPROGRESS CANDIDATE COUNT IS ZERO & TOTAL PENDING CANDIDATE COUNT IS HIGHER THAN ZERO, THEN ALLOCATE THAT MEMBER FOR KYC
      
      $data['module_name'] = $module_name;
      $data['membership_type'] = $membership_type;
      $data['member_type'] = $member_type;
      $data['exam_code'] = $exam_code;
      $data['disp_title'] = 'KYC for '.$disp_title.' - '.$form_membership_type_arr[$membership_type].' ('.$form_member_type_arr[$member_type].')';
      if($membership_type == 'NM')
      {
        //$data['disp_title'] = 'KYC for '.$disp_title.' - '.$form_membership_type_arr[$membership_type].' ('.$form_member_type_arr[$member_type].' - '.$exam_code.')';
        $data['disp_title'] = 'KYC for '.$disp_title.' - '.$form_membership_type_arr[$membership_type].' ('.$form_member_type_arr[$member_type].')';
      }
      $data['page_title'] = 'NCVET KYC - '.$disp_title;
      $data['kyc_pending_candidate_data'] = $kyc_pending_candidate_data;
      $data['kyc_inprogress_candidate_data'] = $this->get_kyc_inprogress_candidate_details($candidate_id, $form_exam_codes_arr);
      $data['photo_path'] = $photo_path;
      $data['sign_path'] = $sign_path;
      $data['id_proof_path'] = $id_proof_path;
      $data['declaration_path'] = $declaration_path;
      $data['institute_idproof_path'] = $institute_idproof_path;
      $data['qualification_certificate_path'] = $qualification_certificate_path;
      $data['exp_certificate_path'] = $exp_certificate_path;
      $data['aadhar_file_path'] = $aadhar_file_path;
      $this->load->view('ncvet/kyc/candidate_details', $data);
    }

    /* START : GET KYC PENDING CANDIDATE DATA FOR EXAM CODE DROPDOWN WITH PENDING COUNT */
    function get_kyc_pending_candidate_details($candidate_id='', $membership_type='', $member_type='', $exam_code_arr=array(), $exam_code = '0', $is_dropdown='')
    {
      $kyc_pending_candidate_data = array();
      $module_name = 'ncvet';

      $reindexedArray = array_values($exam_code_arr);
      $inCondition = "'" . implode("','", $reindexedArray) . "'";
      $this->db->where(" kyc_eligible_date != '' AND kyc_eligible_date IS NOT NULL AND kyc_eligible_date != '0000-00-00 00:00:00' ");
      
      if($member_type == 'new') 
      { 
        $this->db->where(" (img_ediited_on = '' OR img_ediited_on IS NULL OR img_ediited_on = '0000-00-00 00:00:00') "); 
      }
      else if($member_type == 'edited') 
      { 
        $this->db->where(" (img_ediited_on != '' AND img_ediited_on IS NOT NULL AND img_ediited_on != '0000-00-00 00:00:00') "); 
      }

      if($this->login_user_type == '1') //Recommender
      {
        $this->db->where("recommender_id = '0' AND (kyc_recommender_status IS NULL OR kyc_recommender_status = '' OR kyc_recommender_status = '0') AND approver_id = '0' ");
        $this->db->where_in('kyc_status', array(0,2));
      }
      else if($this->login_user_type == '2') //Approver
      {
        //$this->db->where('recommender_id != ', $this->login_related_id);
        $this->db->where("approver_id = '0' AND (kyc_approver_status IS NULL OR kyc_approver_status = '' OR kyc_approver_status = '0') AND recommender_id > 0 AND kyc_recommender_status = '2'");
        $this->db->where_in('kyc_status', array(1));
      }

      if($membership_type == 'NM' && $module_name == 'ncvet')//FOR NCVET MODULE
      {

        $this->db->where('candidate_id', $candidate_id); 

        //$this->db->where(" exam_code IN (".$inCondition.") ");

        $select_fields = '';
        /*if($is_dropdown == '1') 
        { 
          $this->db->group_by('exam_code'); 
          $select_fields = ', count(exam_code) AS RecordCnt';
        }
        else
        {*/
          $this->db->order_by('candidate_id', 'ASC');
        //}

        //if($exam_code != '' && $exam_code != '0') { $this->db->where('exam_code', $exam_code); }          
        $kyc_pending_candidate_data = $this->master_model->getRecords('ncvet_candidates', array('regnumber !=' => '', 'is_deleted'=>'0', 'DATE(kyc_eligible_date) <='=>date('Y-m-d')), 'candidate_id, exam_code'.$select_fields);
        //echo $this->db->last_query();die;
      } 

      return $kyc_pending_candidate_data;
    }/* END : GET KYC PENDING CANDIDATE DATA FOR EXAM CODE DROPDOWN WITH PENDING COUNT */

    /* START : GET KYC INPROGRESS CANDIDATE DATA */
    function get_kyc_inprogress_candidate_details($candidate_id='',  $exam_code_arr=array())
    {
      $kyc_inprogress_candidate_data = array();
      $module_name = 'ncvet';

      $reindexedArray = array_values($exam_code_arr);
      $inCondition = "'" . implode("','", $reindexedArray) . "'";
      $this->db->where(" kyc_eligible_date != '' AND kyc_eligible_date IS NOT NULL AND kyc_eligible_date != '0000-00-00 00:00:00' ");
      
      if($this->login_user_type == '1') //Recommender
      {
        $this->db->where("recommender_id = '".$this->login_admin_id."' AND kyc_recommender_status = '1' AND approver_id = '0' ");
      }
      else if($this->login_user_type == '2') //Approver
      {
        $this->db->where("approver_id = '".$this->login_admin_id."' AND kyc_approver_status = '1' AND recommender_id > 0 AND kyc_recommender_status = '2'");
      }

      if($module_name == 'ncvet') //FOR NCVET MODULE
      {
        //$this->db->where(" exam_code IN (".$inCondition.") ");

        $this->db->where('candidate_id', $candidate_id); 

        $this->db->where_in('kyc_status', array(1));
        $kyc_inprogress_candidate_data = $this->master_model->getRecords('ncvet_candidates', array('regnumber !=' => '', 'is_deleted'=>'0', 'DATE(kyc_eligible_date) <='=>date('Y-m-d')), 'candidate_id, regnumber, training_id, salutation, first_name, middle_name, last_name, dob, gender, mobile_no, email_id, id_proof_file, candidate_photo as photo_file, candidate_sign as sign_file, declarationform, registration_type, exam_code, kyc_eligible_date, img_ediited_on, kyc_fullname_flag, kyc_dob_flag, kyc_aadhar_flag, kyc_apaar_flag, kyc_eligibility_flag, kyc_photo_flag, kyc_sign_flag, kyc_id_card_flag, kyc_declaration_flag, kyc_status, kyc_recommender_status, recommender_id, kyc_approver_status, approver_id, kyc_recommender_date, kyc_approver_date, qualification, institute_idproof, kyc_institute_idproof_flag, aadhar_no, id_proof_number, qualification_certificate_file, kyc_qualification_cert_flag, exp_certificate, kyc_exp_certificate_flag, aadhar_file, kyc_aadhar_file_flag');        
      } 

      return $kyc_inprogress_candidate_data;
    }/* END : GET KYC INPROGRESS CANDIDATE DATA */

    /* START : THIS FUNCTION IS USED TO UPDATE THE KYC DATA BASED ON USER SUBMISSION */
    function process_kyc_details()
    {
      $this->load->model('ncvet/Ncvet_model');
      $success_kyc = '';

      if(isset($_POST) && COUNT($_POST) > 0)
      {
        $this->form_validation->set_rules('candidate_id', 'candidate_id', 'trim|required|xss_clean', array('required'=>"Please enter the %s"));  
        $this->form_validation->set_rules('module_name', 'module_name', 'trim|required|xss_clean', array('required'=>"Please enter the %s"));  
        $this->form_validation->set_rules('membership_type', 'membership_type', 'trim|required|xss_clean', array('required'=>"Please enter the %s"));  
        $this->form_validation->set_rules('member_type', 'member_type', 'trim|required|xss_clean', array('required'=>"Please enter the %s"));  
        $this->form_validation->set_rules('form_action', 'form_action', 'trim|required|xss_clean', array('required'=>"Please enter the %s"));  
        $this->form_validation->set_rules('fullname_kyc', 'option', 'trim|required|xss_clean', array('required'=>"Please enter the %s"));  
        $this->form_validation->set_rules('dob_kyc', 'option', 'trim|required|xss_clean', array('required'=>"Please enter the %s"));  
        $this->form_validation->set_rules('aadhar_kyc', 'option', 'trim|required|xss_clean', array('required'=>"Please enter the %s"));  
        $this->form_validation->set_rules('apaar_kyc', 'option', 'trim|required|xss_clean', array('required'=>"Please enter the %s"));  
        $this->form_validation->set_rules('eligibility_kyc', 'option', 'trim|required|xss_clean', array('required'=>"Please enter the %s"));  
        $this->form_validation->set_rules('photo_file_kyc', 'option', 'trim|required|xss_clean', array('required'=>"Please enter the %s"));  
        $this->form_validation->set_rules('sign_file_kyc', 'option', 'trim|required|xss_clean', array('required'=>"Please enter the %s"));  
        $this->form_validation->set_rules('id_proof_file_kyc', 'option', 'trim|required|xss_clean', array('required'=>"Please enter the %s"));  
 
        //$this->form_validation->set_rules('declaration_file_kyc', 'option', 'trim|required|xss_clean', array('required'=>"Please enter the %s"));  
        if($this->form_validation->run())
        { 
          $candidate_id = $this->input->post('candidate_id');
          $module_name = $this->input->post('module_name');
          $membership_type = $this->input->post('membership_type');
          $member_type = $this->input->post('member_type');
          $exam_code = $this->input->post('exam_code');
          $form_action = $this->input->post('form_action');
          
          $fullname_kyc = $dob_kyc = $aadhar_kyc = $apaar_kyc = $eligibility_kyc = $photo_file_kyc = $sign_file_kyc = $id_proof_file_kyc = $declaration_file_kyc = $institute_idproof_file_kyc = $qualification_certificate_file_kyc = $exp_certificate_file_kyc = $aadhar_file_kyc = $declaration_show_hide = $institute_idproof_show_hide = $qualification_certificate_file_show_hide = $exp_certificate_show_hide = '';
          if(isset($_POST['fullname_kyc'])) { $fullname_kyc = $this->input->post('fullname_kyc'); }
          if(isset($_POST['dob_kyc'])) { $dob_kyc = $this->input->post('dob_kyc'); }
          if(isset($_POST['aadhar_kyc'])) { $aadhar_kyc = $this->input->post('aadhar_kyc'); }
          if(isset($_POST['apaar_kyc'])) { $apaar_kyc = $this->input->post('apaar_kyc'); }
          if(isset($_POST['eligibility_kyc'])) { $eligibility_kyc = $this->input->post('eligibility_kyc'); }
          if(isset($_POST['photo_file_kyc'])) { $photo_file_kyc = $this->input->post('photo_file_kyc'); }
          if(isset($_POST['sign_file_kyc'])) { $sign_file_kyc = $this->input->post('sign_file_kyc'); }
          if(isset($_POST['id_proof_file_kyc'])) { $id_proof_file_kyc = $this->input->post('id_proof_file_kyc'); }
          if(isset($_POST['declaration_file_kyc'])) { $declaration_file_kyc = $this->input->post('declaration_file_kyc'); }
          if(isset($_POST['institute_idproof_file_kyc'])) { $institute_idproof_file_kyc = $this->input->post('institute_idproof_file_kyc'); }
          if(isset($_POST['qualification_certificate_file_kyc'])) { $qualification_certificate_file_kyc = $this->input->post('qualification_certificate_file_kyc'); }
          if(isset($_POST['exp_certificate_file_kyc'])) { $exp_certificate_file_kyc = $this->input->post('exp_certificate_file_kyc'); }
          if(isset($_POST['aadhar_file_kyc'])) { $aadhar_file_kyc = $this->input->post('aadhar_file_kyc'); }

          if(isset($_POST['declaration_show_hide'])) { $declaration_show_hide = $this->input->post('declaration_show_hide'); }
          if(isset($_POST['institute_idproof_show_hide'])) { $institute_idproof_show_hide = $this->input->post('institute_idproof_show_hide'); }
          if(isset($_POST['qualification_certificate_file_show_hide'])) { $qualification_certificate_file_show_hide = $this->input->post('qualification_certificate_file_show_hide'); }
          if(isset($_POST['exp_certificate_show_hide'])) { $exp_certificate_show_hide = $this->input->post('exp_certificate_show_hide'); }

          /* START : CHECK FOR CORRECT MODULE NAME PASSED TO URL */
          if($module_name != '') 
          { 
            $common_data = $this->get_common_data($module_name);            
            $form_membership_type_arr = $common_data['form_membership_type_arr']; 
            $form_member_type_arr = $common_data['form_member_type_arr']; 
            $form_exam_codes_arr = $common_data['form_exam_codes_arr'];
            $photo_path = $common_data['photo_path'];
            $sign_path = $common_data['sign_path'];
            $id_proof_path = $common_data['id_proof_path'];            
            $declaration_path = $common_data['declaration_path'];            
            $institute_idproof_path = $common_data['institute_idproof_path'];            
            $qualification_certificate_path = $common_data['qualification_certificate_path'];            
            $exp_certificate_path = $common_data['exp_certificate_path'];            
            $aadhar_file_path = $common_data['aadhar_file_path'];            
          }
          else
          {
            $this->session->set_flashdata('error','Invalid URL accessed');
            redirect(site_url('ncvet/kyc/kyc_dashboard'));
          }/* END : CHECK FOR CORRECT MODULE NAME PASSED TO URL */

          /* START : CHECK FOR CORRECT MEMBERSHIP TYPE, MEMBER TYPE & EXAM CODE AS PER THE MODULE */
          if(!array_key_exists($membership_type, $form_membership_type_arr))
          {
            $this->session->set_flashdata('error','Invalid URL accessed');
            redirect(site_url('ncvet/kyc/kyc_dashboard'));
          }
          else if(!array_key_exists($member_type, $form_member_type_arr))
          {
            $this->session->set_flashdata('error','Invalid URL accessed');
            redirect(site_url('ncvet/kyc/kyc_dashboard'));
          }
          else if($membership_type == 'NM' && !in_array($exam_code, $form_exam_codes_arr))
          {
            //$this->session->set_flashdata('error','Invalid URL accessed');
            //redirect(site_url('ncvet/kyc/kyc_dashboard'));
          }/* END : CHECK FOR CORRECT MEMBERSHIP TYPE, MEMBER TYPE & EXAM CODE AS PER THE MODULE */

          $update_qry_res = '';
          $candidate_data = array();
          if($this->login_user_type == '1') //RECOMMENDER
          {
            $this->db->where('recommender_id', $this->login_admin_id);
            $this->db->where('kyc_recommender_status', '1');
          }
          else if($this->login_user_type == '2') //APPROVER
          {
            $this->db->where('approver_id', $this->login_admin_id);
            $this->db->where('kyc_recommender_status', '2');
            $this->db->where('kyc_approver_status', '1');
          }

         
          $candidate_data = $this->master_model->getRecords('ncvet_candidates', array('candidate_id' => $candidate_id, 'kyc_status' => '1'), 'candidate_id, regnumber, training_id, salutation, first_name, middle_name, last_name, dob, gender, mobile_no, email_id, id_proof_file, candidate_photo, candidate_sign, declarationform, registration_type, exam_code, password, kyc_eligible_date, img_ediited_on, kyc_fullname_flag, kyc_dob_flag, kyc_aadhar_flag, kyc_apaar_flag, kyc_eligibility_flag, kyc_photo_flag, kyc_sign_flag, kyc_id_card_flag, kyc_declaration_flag, kyc_status, kyc_recommender_status, recommender_id, kyc_approver_status, approver_id, kyc_recommender_date, kyc_approver_date, qualification, institute_idproof, kyc_institute_idproof_flag, aadhar_no, id_proof_number, qualification_certificate_file, kyc_qualification_cert_flag, exp_certificate, kyc_exp_certificate_flag, aadhar_file, kyc_aadhar_file_flag'); 

          if(count($candidate_data) == 0)
          {
            $this->session->set_flashdata('error','Something went wrong. Please try again.');
            redirect(site_url('ncvet/kyc/kyc_dashboard'));
          }

          $candidate_name = $candidate_data[0]['salutation']; 
          $candidate_name .= $candidate_data[0]['first_name'] !=''? ' '.$candidate_data[0]['first_name']:'';
          $candidate_name .= $candidate_data[0]['middle_name'] !=''? ' '.$candidate_data[0]['middle_name']:'';
          $candidate_name .= $candidate_data[0]['last_name'] !=''? ' '.$candidate_data[0]['last_name']:'';
          $candidate_name .= $candidate_data[0]['training_id'] !=''? ' ('.$candidate_data[0]['training_id'].')':'';

          $training_id = $candidate_data[0]['training_id'];
          $regnumber = $candidate_data[0]['regnumber'];

          $logged_in_user_name = $this->Kyc_model->getLoggedInUserDetails($this->login_admin_id, $this->login_user_type);

          $up_candidiate = array();
          $approve_reject_flag = 'Y';
          $log_message = $log_title = $kyc_action = '';
          
           
            //FOR NCVET MODULE
            //UPDATE RECORDS IN ncvet_candidates
            if($fullname_kyc != '') 
            { 
              $up_candidiate['kyc_fullname_flag'] = $fullname_kyc; 
              if($fullname_kyc == 'N')//IF Name REJECT IN KYC, THEN UPDATE THE TABLE COLUMN
              { 
                $approve_reject_flag = 'N'; 
                //$up_candidiate['first_name'] = '';  
                $log_message .= 'Rejected the Name';
                $kyc_action .= 'Name';
              }
              else if($fullname_kyc == 'Y')
              {
                if(($this->login_user_type == '1' && $candidate_data[0]['kyc_fullname_flag'] != $fullname_kyc) || $this->login_user_type == '2') 
                {
                  $log_message .= 'Approved the Name';
                }
              }
            }

            if($dob_kyc != '') 
            { 
              $up_candidiate['kyc_dob_flag'] = $dob_kyc; 
              if($dob_kyc == 'N')//IF Name REJECT IN KYC, THEN UPDATE THE TABLE COLUMN
              { 
                $approve_reject_flag = 'N'; 
                //$up_candidiate['first_name'] = '';  
                $log_message .= ($log_message != '' ? ', ' : '').'Rejected the Birth Date';
                $kyc_action .= ($kyc_action != '' ? ', ' : '').'Birth Date'; 
              }
              else if($dob_kyc == 'Y')
              {
                if(($this->login_user_type == '1' && $candidate_data[0]['kyc_dob_flag'] != $dob_kyc) || $this->login_user_type == '2') 
                {
                  $log_message .= ($log_message != '' ? ', ' : '').'Approved the Birth Date'; 
                }
              }
            }

            if($aadhar_kyc != '') 
            { 
              $up_candidiate['kyc_aadhar_flag'] = $aadhar_kyc; 
              if($aadhar_kyc == 'N')//IF Name REJECT IN KYC, THEN UPDATE THE TABLE COLUMN
              { 
                $approve_reject_flag = 'N'; 
                //$up_candidiate['first_name'] = '';  
                $log_message .= ($log_message != '' ? ', ' : '').'Rejected the Aadhar Number';
                $kyc_action .= ($kyc_action != '' ? ', ' : '').'Aadhar Number'; 
              }
              else if($aadhar_kyc == 'Y')
              {
                if(($this->login_user_type == '1' && $candidate_data[0]['kyc_aadhar_flag'] != $aadhar_kyc) || $this->login_user_type == '2') 
                {
                  $log_message .= ($log_message != '' ? ', ' : '').'Approved the Aadhar Number'; 
                }
              }
            }

            if($apaar_kyc != '') 
            { 
              $up_candidiate['kyc_apaar_flag'] = $apaar_kyc; 
              if($apaar_kyc == 'N')//IF Name REJECT IN KYC, THEN UPDATE THE TABLE COLUMN
              { 
                $approve_reject_flag = 'N'; 
                //$up_candidiate['first_name'] = '';  
                $log_message .= ($log_message != '' ? ', ' : '').'Rejected the APAAR ID/ABC ID Number';
                $kyc_action .= ($kyc_action != '' ? ', ' : '').'APAAR ID/ABC ID Number'; 
              }
              else if($apaar_kyc == 'Y')
              {
                if(($this->login_user_type == '1' && $candidate_data[0]['kyc_apaar_flag'] != $apaar_kyc) || $this->login_user_type == '2') 
                {
                  $log_message .= ($log_message != '' ? ', ' : '').'Approved the APAAR ID/ABC ID Number'; 
                }
              }
            }

            if($eligibility_kyc != '') 
            { 
              $up_candidiate['kyc_eligibility_flag'] = $eligibility_kyc; 
              if($eligibility_kyc == 'N')//IF Name REJECT IN KYC, THEN UPDATE THE TABLE COLUMN
              { 
                $approve_reject_flag = 'N'; 
                //$up_candidiate['first_name'] = '';  
                $log_message .= ($log_message != '' ? ', ' : '').'Rejected the Eligibility';
                $kyc_action .= ($kyc_action != '' ? ', ' : '').'Eligibility'; 
              }
              else if($eligibility_kyc == 'Y')
              {
                if(($this->login_user_type == '1' && $candidate_data[0]['kyc_eligibility_flag'] != $eligibility_kyc) || $this->login_user_type == '2') 
                {
                  $log_message .= ($log_message != '' ? ', ' : '').'Approved the Eligibility'; 
                }
              }
            }

            if($photo_file_kyc != '') 
            { 
              $up_candidiate['kyc_photo_flag'] = $photo_file_kyc; 
              if($photo_file_kyc == 'N')//IF PHOTO REJECT IN KYC, THEN UPDATE THE TABLE COLUMN & RENAME THE PHOTO
              { 
                $approve_reject_flag = 'N'; 
                $up_candidiate['candidate_photo'] = ''; 
                rename($photo_path.'/'.$candidate_data[0]['candidate_photo'], $photo_path.'/k_'.$candidate_data[0]['candidate_photo']);

                $log_message .= ($log_message != '' ? ', ' : '').'Rejected the Photo';
                $kyc_action .= ($kyc_action != '' ? ', ' : '').'Photo'; 
              }
              else if($photo_file_kyc == 'Y')
              {
                if(($this->login_user_type == '1' && $candidate_data[0]['kyc_photo_flag'] != $photo_file_kyc) || $this->login_user_type == '2') 
                { 
                  $log_message .= ($log_message != '' ? ', ' : '').'Approved the Photo';
                }
              }
            }

            if($sign_file_kyc != '') 
            { 
              $up_candidiate['kyc_sign_flag'] = $sign_file_kyc; 
              if($sign_file_kyc == 'N') //IF SIGN REJECT IN KYC, THEN UPDATE THE TABLE COLUMN & RENAME THE SIGN
              { 
                $approve_reject_flag = 'N';
                $up_candidiate['candidate_sign'] = '';
                rename($sign_path.'/'.$candidate_data[0]['candidate_sign'], $sign_path.'/k_'.$candidate_data[0]['candidate_sign']);

                $log_message .= ($log_message != '' ? ', ' : '').'Rejected the Signature';
                $kyc_action .= ($kyc_action != '' ? ', ' : '').'Signature';
              }
              else if($sign_file_kyc == 'Y')
              {
                if(($this->login_user_type == '1' && $candidate_data[0]['kyc_sign_flag'] != $sign_file_kyc) || $this->login_user_type == '2') 
                {
                  $log_message .= ($log_message != '' ? ', ' : '').'Approved the Signature';
                }
              }
            }

            if($id_proof_file_kyc != '') 
            { 
              $up_candidiate['kyc_id_card_flag'] = $id_proof_file_kyc; 
              if($id_proof_file_kyc == 'N') //IF APAAR ID/ABC ID REJECT IN KYC, THEN UPDATE THE TABLE COLUMN & RENAME THE APAAR ID/ABC ID
              { 
                $approve_reject_flag = 'N';
                //$up_candidiate['id_proof_file'] = '';  
                $up_candidiate['id_proof_file'] = '';  
                rename($id_proof_path.'/'.$candidate_data[0]['id_proof_file'], $id_proof_path.'/k_'.$candidate_data[0]['id_proof_file']);

                $log_message .= ($log_message != '' ? ' & ' : '').'Rejected the APAAR ID/ABC ID';
                $kyc_action .= ($kyc_action != '' ? ' & ' : '').'APAAR ID/ABC ID'; 
              }
              else if($id_proof_file_kyc == 'Y')
              {
                if(($this->login_user_type == '1' && $candidate_data[0]['kyc_id_card_flag'] != $id_proof_file_kyc) || $this->login_user_type == '2') 
                {
                  $log_message .= ($log_message != '' ? ' & ' : '').'Approved the APAAR ID/ABC ID';
                }
              }
            }

            if($aadhar_file_kyc != '')
            { 
                $up_candidiate['kyc_aadhar_file_flag'] = $aadhar_file_kyc; 
                if($aadhar_file_kyc == 'N') //IF Aadhar Card REJECT IN KYC, THEN UPDATE THE TABLE COLUMN & RENAME THE Aadhar Card
                { 
                  $approve_reject_flag = 'N'; 
                  $up_candidiate['aadhar_file'] = '';  
                  rename($aadhar_file_path.'/'.$candidate_data[0]['aadhar_file'], $aadhar_file_path.'/k_'.$candidate_data[0]['aadhar_file']);

                  $log_message .= ($log_message != '' ? ' & ' : '').'Rejected the Aadhar Card';
                  $kyc_action .= ($kyc_action != '' ? ' & ' : '').'Aadhar Card'; 
                }
                else if($aadhar_file_kyc == 'Y')
                {
                  if(($this->login_user_type == '1' && $candidate_data[0]['kyc_aadhar_file_flag'] != $aadhar_file_kyc) || $this->login_user_type == '2') 
                  {
                    $log_message .= ($log_message != '' ? ' & ' : '').'Approved the Aadhar Card';
                  }
                }
            }

            if($candidate_data[0]['qualification'] == "1" || $candidate_data[0]['qualification'] == "2")
            {
              if($qualification_certificate_file_kyc != '' && $qualification_certificate_file_show_hide == "") 
              { 
                $up_candidiate['kyc_qualification_cert_flag'] = $qualification_certificate_file_kyc; 
                if($qualification_certificate_file_kyc == 'N') //IF Qualification Certificate REJECT IN KYC, THEN UPDATE THE TABLE COLUMN & RENAME THE Qualification Certificate
                { 
                  $approve_reject_flag = 'N'; 
                  $up_candidiate['qualification_certificate_file'] = '';  
                  rename($qualification_certificate_path.'/'.$candidate_data[0]['qualification_certificate_file'], $qualification_certificate_path.'/k_'.$candidate_data[0]['qualification_certificate_file']);

                  $log_message .= ($log_message != '' ? ' & ' : '').'Rejected the Qualification Certificate';
                  $kyc_action .= ($kyc_action != '' ? ' & ' : '').'Qualification Certificate'; 
                }
                else if($qualification_certificate_file_kyc == 'Y')
                {
                  if(($this->login_user_type == '1' && $candidate_data[0]['kyc_qualification_cert_flag'] != $qualification_certificate_file_kyc) || $this->login_user_type == '2') 
                  {
                    $log_message .= ($log_message != '' ? ' & ' : '').'Approved the Qualification Certificate';
                  }
                }
              }
            }
 
            if($candidate_data[0]['qualification'] == "1" && $exp_certificate_file_kyc != '' && $exp_certificate_show_hide == "")
            { 
                $up_candidiate['kyc_exp_certificate_flag'] = $exp_certificate_file_kyc; 
                if($exp_certificate_file_kyc == 'N') //IF Exp Certificate REJECT IN KYC, THEN UPDATE THE TABLE COLUMN & RENAME THE Exp Certificate
                { 
                  $approve_reject_flag = 'N'; 
                  $up_candidiate['exp_certificate'] = '';  
                  rename($exp_certificate_path.'/'.$candidate_data[0]['exp_certificate'], $exp_certificate_path.'/k_'.$candidate_data[0]['exp_certificate']);

                  $log_message .= ($log_message != '' ? ' & ' : '').'Rejected the Exp Certificate';
                  $kyc_action .= ($kyc_action != '' ? ' & ' : '').'Exp Certificate'; 
                }
                else if($exp_certificate_file_kyc == 'Y')
                {
                  if(($this->login_user_type == '1' && $candidate_data[0]['kyc_exp_certificate_flag'] != $exp_certificate_file_kyc) || $this->login_user_type == '2') 
                  {
                    $log_message .= ($log_message != '' ? ' & ' : '').'Approved the Exp Certificate';
                  }
                }
            }

            if($candidate_data[0]['qualification'] == "3" || $candidate_data[0]['qualification'] == "4")
            {
                if($declaration_file_kyc != '' && $declaration_show_hide == "") 
                { 
                  $up_candidiate['kyc_declaration_flag'] = $declaration_file_kyc; 
                  if($declaration_file_kyc == 'N') //IF DECLARATION REJECT IN KYC, THEN UPDATE THE TABLE COLUMN & RENAME THE DECLARATION
                  { 
                    $approve_reject_flag = 'N'; 
                    $up_candidiate['declarationform'] = '';  
                    rename($declaration_path.'/'.$candidate_data[0]['declarationform'], $declaration_path.'/k_'.$candidate_data[0]['declarationform']);

                    $log_message .= ($log_message != '' ? ' & ' : '').'Rejected the Declaration';
                    $kyc_action .= ($kyc_action != '' ? ' & ' : '').'Declaration'; 
                  }
                  else if($declaration_file_kyc == 'Y')
                  {
                    if(($this->login_user_type == '1' && $candidate_data[0]['kyc_declaration_flag'] != $declaration_file_kyc) || $this->login_user_type == '2') 
                    {
                      $log_message .= ($log_message != '' ? ' & ' : '').'Approved the Declaration';
                    }
                  }
                }

                if($institute_idproof_file_kyc != '' && $institute_idproof_show_hide == "" && $candidate_data[0]['institute_idproof'] !='' && !empty($candidate_data[0]['institute_idproof']) ) 
                { 
                  $up_candidiate['kyc_institute_idproof_flag'] = $institute_idproof_file_kyc; 
                  if($institute_idproof_file_kyc == 'N') //IF Institute Id Proof REJECT IN KYC, THEN UPDATE THE TABLE COLUMN & RENAME THE Institute Id Proof
                  { 
                    $approve_reject_flag = 'N'; 
                    $up_candidiate['institute_idproof'] = '';  
                    rename($institute_idproof_path.'/'.$candidate_data[0]['institute_idproof'], $institute_idproof_path.'/k_'.$candidate_data[0]['institute_idproof']);

                    $log_message .= ($log_message != '' ? ' & ' : '').'Rejected the Institute Id Proof';
                    $kyc_action .= ($kyc_action != '' ? ' & ' : '').'Institute Id Proof'; 
                  }
                  else if($institute_idproof_file_kyc == 'Y')
                  {
                    if(($this->login_user_type == '1' && $candidate_data[0]['kyc_institute_idproof_flag'] != $institute_idproof_file_kyc) || $this->login_user_type == '2') 
                    {
                      $log_message .= ($log_message != '' ? ' & ' : '').'Approved the Institute Id Proof';
                    }
                  }
                }          
            }
             

          

          if($this->login_user_type == '1') //RECOMMENDER
          {
            if($approve_reject_flag == 'Y') 
            { 
              $up_candidiate['kyc_recommender_status'] = '2'; 
              $up_candidiate['kyc_status'] = '1'; 
              $log_title = 'Approved'; 
            }
            else if($approve_reject_flag == 'N') 
            { 
              $up_candidiate['kyc_recommender_status'] = '3';
              $up_candidiate['kyc_status'] = '3'; 
              $log_title = 'Rejected';   

              ////SEND KYC REJECTION EMAIL TO CANDIDATE                 
            }
            
            $up_candidiate['recommender_id'] = $this->login_admin_id;
            $up_candidiate['kyc_recommender_date'] = date('Y-m-d H:i:s');
          }
          else if($this->login_user_type == '2') //APPROVER
          {
            if($approve_reject_flag == 'Y') 
            { 
              $up_candidiate['kyc_approver_status'] = '2';  
              $up_candidiate['kyc_status'] = '2';
              $log_title = 'Approved'; 
            }
            else if($approve_reject_flag == 'N') 
            { 
              $up_candidiate['kyc_approver_status'] = '3'; 
              $up_candidiate['kyc_status'] = '3';
              $log_title = 'Rejected'; 

              ////SEND KYC REJECTION EMAIL TO CANDIDATE  
            }
            
            $up_candidiate['approver_id'] = $this->login_admin_id;
            $up_candidiate['kyc_approver_date'] = date('Y-m-d H:i:s');
          }

          /*$get_ip_address = get_ip_address();
          if(in_array($get_ip_address,array('115.124.115.75')) )
          {
              echo "<br>".$get_ip_address;
              echo "<br>module_name: ".$module_name;
              echo "<br>action: ".strtoupper($kyc_action)."<br>";
              echo "<br>candidate_data: ".print_r($candidate_data);
              //die;
              $this->Kyc_model->send_approve_reject_kyc_email_sms($candidate_data,$module_name,$kyc_action);die;
          }*/


          if(count($up_candidiate) > 0)
          {
            
            $update_qry_res = $this->master_model->updateRecord('ncvet_candidates', $up_candidiate, array('candidate_id'=>$candidate_id));

            $log_tbl_name = '';
            if($module_name == 'ncvet')//FOR NCVET MODULE
            {
              $log_tbl_name = 'ncvet_candidates';
            }

            ////SAVE KYC RELATED LOG IN DATABASE TABLE
            if($this->login_user_type == '1') //RECOMMENDER
            {
              $this->Kyc_model->insert_common_log('Recommender action : '.$log_title, $log_tbl_name, $this->db->last_query(), $candidate_id, 'kyc_recommender_'.$log_title, 'The Recommender ('.$logged_in_user_name['disp_name'].') '.$log_message.' for the candidate '.$candidate_name, json_encode($_POST), $module_name, $membership_type, $member_type, $exam_code, $training_id, $regnumber);
            }
            else if($this->login_user_type == '2') //APPROVER
            {
              $this->Kyc_model->insert_common_log('Approver action : '.$log_title, $log_tbl_name, $this->db->last_query(), $candidate_id, 'kyc_approver_'.$log_title, 'The Approver ('.$logged_in_user_name['disp_name'].') '.$log_message.' for the candidate '.$candidate_name, json_encode($_POST), $module_name, $membership_type, $member_type, $exam_code, $training_id, $regnumber);
            }  
            
            if($approve_reject_flag == 'Y') 
            {
              $this->session->set_flashdata('success_kyc','KYC successfully approved for candidate '.$candidate_name);
              $success_kyc = 'KYC successfully approved for candidate '.$candidate_name;
            }
            else
            {
              $this->session->set_flashdata('success_kyc','KYC details <strong>successfully updated for candidate</strong> '.$candidate_name.'. Also email has been successfully sent to candidate regarding the KYC status.');
              $success_kyc = 'KYC details <strong>successfully updated for candidate</strong> '.$candidate_name.'. Also email has been successfully sent to candidate regarding the KYC status.';
              //Send Email To Candidate For  
              $this->Kyc_model->send_approve_reject_kyc_email_sms($candidate_data,$module_name,$kyc_action);
            }
          }
          else
          {
            $this->session->set_flashdata('error','Something went wrong. Please try again.');
            redirect(site_url('ncvet/kyc/kyc_dashboard'));
          }

          if($update_qry_res)
          {
            if($form_action == 'submit_and_next')
            { 
              //Send Email To Candidate For  
              //echo $this->session->flashdata('success_kyc');die;
              redirect(site_url('ncvet/kyc/kyc_all/candidate_details/'.$module_name.'/'.$membership_type.'/'.$member_type.'/'.$exam_code));
            }
            else
            {
              //redirect(site_url('ncvet/kyc/kyc_dashboard'));

              if($member_type == 'new'){
                redirect(site_url('ncvet/kyc/kyc_all/recommend_candidate/'));
              }else{
                redirect(site_url('ncvet/kyc/kyc_all/edit_candidate/'));  
              }
              /*if($member_type == 'new'){
                redirect(site_url('ncvet/kyc/kyc_all/new_candidate'));
              }else{
                redirect(site_url('ncvet/kyc/kyc_all/edit_candidate'));
              }*/
              
            }
          }
          else
          {
            $this->session->set_flashdata('error','Please submit the correct details');
            redirect(site_url('ncvet/kyc/kyc_all/candidate_details/'.$_POST['module_name'].'/'.$_POST['membership_type'].'/'.$_POST['member_type'].'/'.$_POST['exam_code']));
          }
        }
        else
        {
          $this->session->set_flashdata('error','Please submit the correct details');
          redirect(site_url('ncvet/kyc/kyc_all/candidate_details/'.$_POST['module_name'].'/'.$_POST['membership_type'].'/'.$_POST['member_type'].'/'.$_POST['exam_code']));
        }
      }
      else
      {
        $this->session->set_flashdata('error','You can not access this page directly.');
        redirect(site_url('ncvet/kyc/kyc_dashboard'));
      }
    }/* END : THIS FUNCTION IS USED TO UPDATE THE KYC DATA BASED ON USER SUBMISSION */


    public function update_as_recommend($enc_candidate_id='', $membership_type='', $member_type='', $exam_code='')
    {
      $candidate_id = url_decode($enc_candidate_id);

      //echo "is == ".$this->session->flashdata('success_kyc');
      if($this->session->flashdata('success_kyc')){
        $this->session->set_flashdata('success_kyc',$this->session->flashdata('success_kyc'));
      }
      $module_name = 'ncvet'; //strtolower($module_name);
      $data['act_id'] = $module_name;
      $data['sub_act_id'] = "";      
      $disp_title = 'KYC';
      $form_membership_type_arr = $form_member_type_arr = $form_exam_codes_arr = array();
      $photo_path = $sign_path = $id_proof_path = $declaration_path = $institute_idproof_path = $qualification_certificate_path = $exp_certificate_path = $aadhar_file_path = '';

      /* START : CHECK FOR CORRECT MODULE NAME PASSED TO URL */
      if($candidate_id != '') 
      { 
        $common_data = $this->get_common_data($module_name);
        $disp_title = $common_data['disp_title']; 
        $form_membership_type_arr = $common_data['form_membership_type_arr']; 
        $form_member_type_arr = $common_data['form_member_type_arr']; 
        $form_exam_codes_arr = $common_data['form_exam_codes_arr'];
        $photo_path = $common_data['photo_path'];
        $sign_path = $common_data['sign_path'];
        $id_proof_path = $common_data['id_proof_path'];
        $declaration_path = $common_data['declaration_path'];
        $institute_idproof_path = $common_data['institute_idproof_path'];
        $qualification_certificate_path = $common_data['qualification_certificate_path'];
        $exp_certificate_path = $common_data['exp_certificate_path'];
        $aadhar_file_path = $common_data['aadhar_file_path'];
      }
      else
      {
        //echo "==".$module_name;die;
        $this->session->set_flashdata('error','Invalid URL accessed');
        redirect(site_url('ncvet/kyc/kyc_dashboard'));
      }/* END : CHECK FOR CORRECT MODULE NAME PASSED TO URL */

      /* START : CHECK FOR CORRECT MEMBERSHIP TYPE, MEMBER TYPE & EXAM CODE AS PER THE MODULE */
      if(!array_key_exists($membership_type, $form_membership_type_arr))
      {
        //echo $membership_type."==".print_r($form_membership_type_arr);die;
        $this->session->set_flashdata('error','Invalid URL accessed');
        redirect(site_url('ncvet/kyc/kyc_dashboard'));
      }
      else if(!array_key_exists($member_type, $form_member_type_arr))
      {
        //echo "==".$module_name;die;
        $this->session->set_flashdata('error','Invalid URL accessed');
        redirect(site_url('ncvet/kyc/kyc_dashboard'));
      }
      else if($membership_type == 'NM' && !in_array($exam_code, $form_exam_codes_arr))
      {
        //echo "==".$module_name;die;
        //$this->session->set_flashdata('error','Invalid URL accessed');
        //redirect(site_url('ncvet/kyc/kyc_dashboard'));
      }/* END : CHECK FOR CORRECT MEMBERSHIP TYPE, MEMBER TYPE & EXAM CODE AS PER THE MODULE */
      
      $kyc_pending_candidate_data = $this->get_kyc_pending_candidate_details($candidate_id, $membership_type, $member_type, $form_exam_codes_arr, $exam_code, $is_dropdown = '');      
      $kyc_inprogress_candidate_data = $this->get_kyc_inprogress_candidate_details($candidate_id, $form_exam_codes_arr);
      
      //START : GET INPROGRESS CANDIDATE DATA COUNT & GET TOTAL KYC PENDING CANDIDATE DATA COUNT. IF INPROGRESS CANDIDATE COUNT IS ZERO & TOTAL PENDING CANDIDATE COUNT IS HIGHER THAN ZERO, THEN ALLOCATE THAT MEMBER FOR KYC
      if(count($kyc_pending_candidate_data) > 0 && count($kyc_inprogress_candidate_data) == 0)
      {
        $update_qry_res = '';
        $up_candidiate = array();
        $up_candidiate['kyc_status'] = '1';

        if($this->login_user_type == '1') //RECOMMENDER
        {
          $up_candidiate['kyc_recommender_status'] = '1';
          $up_candidiate['recommender_id'] = $this->login_admin_id;
          $up_candidiate['kyc_recommender_date'] = date('Y-m-d H:i:s');            
        }
        else if($this->login_user_type == '2') //APPROVER
        {
          $up_candidiate['kyc_approver_status'] = '1';
          $up_candidiate['approver_id'] = $this->login_admin_id;
          $up_candidiate['kyc_approver_date'] = date('Y-m-d H:i:s');
        }

        if($module_name == 'ncvet' && count($up_candidiate) > 0)//FOR NCVET MODULE
        {
          //UPDATE RECORDS IN ncvet_candidates table
          $update_qry_res = $this->master_model->updateRecord('ncvet_candidates', $up_candidiate, array('candidate_id'=>$kyc_pending_candidate_data[0]['candidate_id']));
        }       
          
        if($update_qry_res)
        {
          //$session_msg = "Recommend successfully!.."; //$this->session->flashdata('success');
          $session_msg = "Attend successfully!.."; //$this->session->flashdata('success');
          $this->session->set_flashdata('success',$session_msg);
          //redirect(site_url('ncvet/kyc/kyc_all/candidate_details/'.url_encode($kyc_pending_candidate_data[0]['candidate_id']).'/'.$membership_type.'/'.$member_type.'/'.$exam_code));

          if($member_type == 'edited'){
            redirect(site_url('ncvet/kyc/kyc_all/edit_candidate/'));
          }else{
            redirect(site_url('ncvet/kyc/kyc_all/new_candidate/'));  
          }
          
        }
      }
      else if(count($kyc_pending_candidate_data) == 0 && count($kyc_inprogress_candidate_data) == 0)
      {
        //echo count($kyc_pending_candidate_data)." == ".count($kyc_inprogress_candidate_data);die;
        $this->session->set_flashdata('error','No candidate available for selected criteria');
        redirect(site_url('ncvet/kyc/kyc_dashboard'));
      }//END : GET INPROGRESS CANDIDATE DATA COUNT & GET TOTAL PENDING CANDIDATE DATA COUNT. IF INPROGRESS CANDIDATE COUNT IS ZERO & TOTAL PENDING CANDIDATE COUNT IS HIGHER THAN ZERO, THEN ALLOCATE THAT MEMBER FOR KYC
      
       
    }

    public function view_kyc_details($enc_candidate_id='', $page_url='')
    {
      $candidate_id = url_decode($enc_candidate_id);

      //echo "is == ".$this->session->flashdata('success_kyc');
      if($this->session->flashdata('success_kyc')){
        $this->session->set_flashdata('success_kyc',$this->session->flashdata('success_kyc'));
      }
      
      $data['module_name'] = $module_name = 'ncvet'; //strtolower($module_name);
      
      /*$data['act_id'] = $module_name;
      $data['sub_act_id'] = "";*/    

      $data['act_id'] = "New Candidate KYC";
      
      if($this->session->userdata('NCVET_KYC_ADMIN_TYPE') == '1' && $page_url == "approved_candidate"){
        $data['act_id'] = "New Candidate KYC";
        $data['sub_act_id'] = "Recommended Candidates"; 
      }else if($this->session->userdata('NCVET_KYC_ADMIN_TYPE') == '2' && $page_url == "approved_candidate"){
        $data['act_id'] = "New Candidate KYC";
        $data['sub_act_id'] = "Approved Candidates"; 
      }else if($page_url == "rejected_candidate"){
        $data['act_id'] = "New Candidate KYC";
        $data['sub_act_id'] = "Rejected Candidates"; 
      }else if($this->session->userdata('NCVET_KYC_ADMIN_TYPE') == '1' && $page_url == "approved_edit_candidate"){  //Edit
        $data['act_id'] = "Edit Candidate KYC";
        $data['sub_act_id'] = "Edit Recommended Candidates"; 
      }else if($this->session->userdata('NCVET_KYC_ADMIN_TYPE') == '2' && $page_url == "approved_edit_candidate"){
        $data['act_id'] = "Edit Candidate KYC";
        $data['sub_act_id'] = "Edit Approved Candidates"; 
      }else if($page_url == "rejected_edit_candidate"){
        $data['act_id'] = "Edit Candidate KYC";
        $data['sub_act_id'] = "Edit Rejected Candidates"; 
      } 

      $data['page_url'] = $page_url;

      $disp_title = 'KYC';
      $form_membership_type_arr = $form_member_type_arr = $form_exam_codes_arr = array();
      $photo_path = $sign_path = $id_proof_path = $declaration_path = $institute_idproof_path = $qualification_certificate_path = $exp_certificate_path = $aadhar_file_path = '';

      /* START : CHECK FOR CORRECT MODULE NAME PASSED TO URL */
      if($candidate_id != '') 
      { 
        $common_data = $this->get_common_data($module_name);
        $disp_title = $common_data['disp_title']; 
        $form_membership_type_arr = $common_data['form_membership_type_arr']; 
        $form_member_type_arr = $common_data['form_member_type_arr']; 
        $form_exam_codes_arr = $common_data['form_exam_codes_arr'];
        $photo_path = $common_data['photo_path'];
        $sign_path = $common_data['sign_path'];
        $id_proof_path = $common_data['id_proof_path'];
        $declaration_path = $common_data['declaration_path'];
        $institute_idproof_path = $common_data['institute_idproof_path'];
        $qualification_certificate_path = $common_data['qualification_certificate_path'];
        $exp_certificate_path = $common_data['exp_certificate_path'];
        $aadhar_file_path = $common_data['aadhar_file_path'];
      }
      else
      {
        //echo "==".$module_name;die;
        $this->session->set_flashdata('error','Invalid URL accessed');
        redirect(site_url('ncvet/kyc/kyc_dashboard'));
      }/* END : CHECK FOR CORRECT MODULE NAME PASSED TO URL */

      $this->db->where('candidate_id', $candidate_id);  
      $mem_data = $this->master_model->getRecords('ncvet_candidates', array('regnumber !=' => '', 'is_deleted'=>'0', 'DATE(kyc_eligible_date) <='=>date('Y-m-d')), 'candidate_id, regnumber, training_id, salutation, first_name, middle_name, last_name, dob, gender, mobile_no, email_id, id_proof_file, candidate_photo as photo_file, candidate_sign as sign_file, declarationform, registration_type, exam_code, kyc_eligible_date, img_ediited_on, kyc_fullname_flag, kyc_dob_flag, kyc_aadhar_flag, kyc_apaar_flag, kyc_eligibility_flag, kyc_photo_flag, kyc_sign_flag, kyc_id_card_flag, kyc_declaration_flag, kyc_status, kyc_recommender_status, recommender_id, kyc_approver_status, approver_id, kyc_recommender_date, kyc_approver_date, qualification, institute_idproof, kyc_institute_idproof_flag, aadhar_no, id_proof_number, qualification_certificate_file, kyc_qualification_cert_flag, exp_certificate, kyc_exp_certificate_flag, aadhar_file, kyc_aadhar_file_flag');       
        
 
      
       
      $disp_name = $mem_data[0]['salutation']; 
      $disp_name .= $mem_data[0]['first_name'] !=''? ' '.$mem_data[0]['first_name']:'';
      $disp_name .= $mem_data[0]['middle_name'] !=''? ' '.$mem_data[0]['middle_name']:'';
      $disp_name .= $mem_data[0]['last_name'] !=''? ' '.$mem_data[0]['last_name']:'';
       
      $data['disp_title'] = 'KYC for '.$disp_name;
      $data['page_title'] = 'NCVET KYC Module'; 
       
      $data['mem_data'] = $mem_data;
      $data['photo_path'] = $photo_path;
      $data['sign_path'] = $sign_path;
      $data['id_proof_path'] = $id_proof_path;
      $data['declaration_path'] = $declaration_path;
      $data['institute_idproof_path'] = $institute_idproof_path;
      $data['qualification_certificate_path'] = $qualification_certificate_path;
      $data['exp_certificate_path'] = $exp_certificate_path;
      $data['aadhar_file_path'] = $aadhar_file_path;
      $this->load->view('ncvet/kyc/view_kyc_details', $data);
    }

    /*List NCVET KYC STATUS Report for All Candidates */
    public function kyc_status_report()
    {    
      $dispName = $this->Kyc_model->getLoggedInUserDetails($this->login_admin_id, $this->login_user_type); 

      $data['act_id'] = "KYC Report";
      $data['sub_act_id'] = "KYC Status Report";
      $data['page_title'] = 'KYC Status Report';
      $data['dispName'] = $dispName;
      
      $data['action_name'] = 'get_kyc_status_report_ajax';

      $data['kyc_status_list'] = 'enrolled';
      $data['page_url'] = 'new_candidate';

      $this->load->view('ncvet/kyc/kyc_status_report', $data);
    }
    /*List NCVET KYC STATUS Report for All Candidates */

    /******** START : SERVER SIDE DATATABLE CALL FOR KYC STATUS Report DATA ********/
    function get_kyc_status_report_ajax($kyc_status_list='',$page_url='')
    {
      $this->load->model('ncvet/Ncvet_model');

      $form_action = trim($this->security->xss_clean($this->input->post('form_action')));

      $kyc_inprogress_candidate_data = array();
   
      $table = 'ncvet_candidates nc';

      $column_order = array('nc.candidate_id', 'nc.regnumber', 'CONCAT(nc.salutation, " ", nc.first_name, IF(nc.middle_name != "", CONCAT(" ", nc.middle_name), ""), IF(nc.last_name != "", CONCAT(" ", nc.last_name), "")) AS DispName', 'nc.created_on', 'nc.mobile_no', 'nc.email_id', 'IF(nc.kyc_status=0, "Pending", IF(nc.kyc_status=1, "In Progress", IF(nc.kyc_status=2, "Approved", IF(nc.kyc_status=3, "Rejected", "-")))) AS KycStatus', 'nc.recommender_id', 'nc.approver_id', 'nc.kyc_status', 'nc.kyc_recommender_status', 'nc.img_ediited_on', 'nc.kyc_approver_status', 'nc.reference', 'nc.kyc_fullname_flag', 'nc.kyc_dob_flag', 'nc.kyc_aadhar_flag', 'nc.kyc_apaar_flag', 'nc.kyc_eligibility_flag', 'nc.kyc_photo_flag', 'nc.kyc_sign_flag', 'nc.kyc_id_card_flag', 'nc.kyc_declaration_flag', 'nc.kyc_institute_idproof_flag', 'nc.kyc_qualification_cert_flag', 'nc.kyc_exp_certificate_flag', 'nc.kyc_aadhar_file_flag', 'nc.kyc_vis_imp_cert_flag', 'nc.kyc_orth_han_cert_flag', 'nc.kyc_cer_palsy_cert_flag', 'nc.benchmark_disability', 'nc.qualification', 'nc.institute_idproof'); //, 'nc.exam_code' //SET COLUMNS FOR SORT
      
      $column_search = array('nc.regnumber', 'CONCAT(nc.salutation, " ", nc.first_name, IF(nc.middle_name != "", CONCAT(" ", nc.middle_name), ""), IF(nc.last_name != "", CONCAT(" ", nc.last_name), ""))', 'nc.created_on', 'nc.mobile_no', 'nc.email_id', 'IF(nc.kyc_status=0, "Pending", IF(nc.kyc_status=1, "In Progress", IF(nc.kyc_status=2, "Approved", IF(nc.kyc_status=3, "Rejected", "-"))))'); //, 'nc.exam_code' //SET COLUMN FOR SEARCH
      $order = array('nc.candidate_id' => 'DESC'); // DEFAULT ORDER
      
      /*$WhereForTotal = "WHERE nc.login_type = '".$this->login_user_type."' AND nc.login_id = '".$this->login_admin_id."' ";*/  

      $WhereForTotal = "WHERE nc.kyc_eligible_date != '' AND nc.kyc_eligible_date IS NOT NULL AND nc.kyc_eligible_date != '0000-00-00 00:00:00' AND nc.is_deleted = 0 AND nc.regnumber != '' "; // AND nc.benchmark_disability = 'N' DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE nc.kyc_eligible_date != '' AND nc.kyc_eligible_date IS NOT NULL AND nc.kyc_eligible_date != '0000-00-00 00:00:00' AND nc.is_deleted = 0 AND nc.regnumber != '' "; // AND nc.benchmark_disability = 'N'     
      
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
      //echo "<pre>".print_r($_POST);die;
      $s_from_date = trim($this->security->xss_clean($this->input->post('s_from_date')));//die;
      $s_to_date = trim($this->security->xss_clean($this->input->post('s_to_date')));
      $enrollment_number = trim($this->security->xss_clean($this->input->post('enrollment_number')));
      $enrollment_channel = trim($this->security->xss_clean($this->input->post('enrollment_channel')));
      //$Where .= " AND nc.module_name = '".$module_name."'";  

      if($s_from_date != "") { 
        $Where .= " AND nc.created_on >= '".$s_from_date."' AND nc.created_on <= '".$s_to_date."'"; 
      }
      else if($s_to_date != "") { 
        $Where .= " AND nc.created_on >= '".$s_from_date."' AND nc.created_on <= '".$s_to_date."'"; 
      }
      else if($s_from_date != "" && $s_to_date != "")
      { 
        $Where .= " AND nc.created_on >= '".$s_from_date."' AND nc.created_on <= '".$s_to_date."'"; 
      }
      
      if($enrollment_number != "") { 
        $Where .= " AND nc.regnumber  LIKE '%" . (custom_safe_string($enrollment_number)) . "%'"; 
      }

      if($enrollment_channel != "" && $enrollment_channel == "BFSI") {  
          $Where .= " AND nc.reference = 'BFSI'";  
      }
      if($enrollment_channel != "" && $enrollment_channel == "Website"){
          $Where .= " AND (nc.reference IS NULL OR nc.reference = '')";
      }

      if($kyc_status_list == "enrolled")
      {
        if($this->login_user_type == '1') //Recommender
        { 
          //$Where .= " AND nc.recommender_id = '0' AND (nc.kyc_recommender_status IS NULL OR nc.kyc_recommender_status = '' OR nc.kyc_recommender_status = '0') AND nc.approver_id = '0' AND nc.kyc_status IN(0,2) ";  

        }
        else if($this->login_user_type == '2') //Approver
        {
          //$this->db->where('recommender_id != ', $this->login_related_id); 
           
        } 
      } 
      

      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY "; foreach($order as $o_key=>$o_val) { $Order .= $o_key." ".$o_val.", "; } $Order = rtrim($Order,", "); }
      
      $Limit = ""; if ($_POST['length'] != '-1' && $form_action != 'export') { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT 
      
      $join_qry = "";
            
      $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
      //echo $print_query;die;
      $Result = $this->db->query($print_query);  
      $Rows = $Result->result_array();
      
      $TotalResult = $this->Ncvet_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$WhereForTotal);
      $FilteredResult = $this->Ncvet_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);
      
      $data = array();
      $no = $_POST['start'];    

      if ($form_action == 'export')
      {
        // Excel file name for download 
        $fileName = "KYC_Status_Report_".date('Y-m-d').".xls";  
        // Column names  

        $fields = array('Sr. No.', 'Enrollment No.', 'Enrollment Channel', 'Name', 'Birth Date', 'Aadhar No.', 'APAAR ID/ABC ID', 'Eligibility', 'Photo', 'Signature', 'APAAR ID/ABC ID File', 'Aadhar Card File', 'Qualification Certificate', 'Institute ID', 'Declaration', 'Experience Certificate', 'Visually Impaired', 'Orthopedically Handicapped', 'Cerebral Palsy', 'KYC Status');  
        // Display column names as first row 
        $excelData = implode("\t", array_values($fields)) . "\n";  
      }
      
      foreach ($Rows as $Res) 
      {
        $no++;
        $row = array();
        $kyc_status_find = '';
        //$kyc_fullname_status = ($Res['kyc_fullname_flag'] != "" && $Res['kyc_status'] != "2" ? $Res['kyc_fullname_flag'] : 'P');
 
        $kyc_fullname_status = ($Res['kyc_fullname_flag'] != "" ? $Res['kyc_fullname_flag'] : 'P'); 
        $kyc_dob_status = ($Res['kyc_dob_flag'] != "" ? $Res['kyc_dob_flag'] : 'P'); 
        $kyc_aadhar_status = ($Res['kyc_aadhar_flag'] != "" ? $Res['kyc_aadhar_flag'] : 'P'); 
        $kyc_apaar_status = ($Res['kyc_apaar_flag'] != "" ? $Res['kyc_apaar_flag'] : 'P'); 
        $kyc_eligibility_status = ($Res['kyc_eligibility_flag'] != "" ? $Res['kyc_eligibility_flag'] : 'P'); 
        $kyc_photo_status = ($Res['kyc_photo_flag'] != "" ? $Res['kyc_photo_flag'] : 'P'); 
        $kyc_sign_status = ($Res['kyc_sign_flag'] != "" ? $Res['kyc_sign_flag'] : 'P'); 
        $kyc_id_card_status = ($Res['kyc_id_card_flag'] != "" ? $Res['kyc_id_card_flag'] : 'P'); 
        $kyc_aadhar_file_status = ($Res['kyc_aadhar_file_flag'] != "" ? $Res['kyc_aadhar_file_flag'] : 'P'); 
       
        $kyc_qualification_cert_status = (in_array($Res['qualification'],array(1,2)) ? ($Res['kyc_qualification_cert_flag'] != "" ? $Res['kyc_qualification_cert_flag'] : 'P') : 'NA');  

        $kyc_institute_idproof_status = (in_array($Res['qualification'],array(3,4)) && $Res['institute_idproof'] != "" ? ($Res['kyc_institute_idproof_flag'] != "" ? $Res['kyc_institute_idproof_flag'] : 'P') : 'NA');  

        $kyc_declaration_status = (in_array($Res['qualification'],array(3,4)) ? ($Res['kyc_declaration_flag'] != "" ? $Res['kyc_declaration_flag'] : 'P') : 'NA'); 

        $kyc_exp_certificate_status = (in_array($Res['qualification'],array(1)) ? ($Res['kyc_exp_certificate_flag'] != "" ? $Res['kyc_exp_certificate_flag'] : 'P') : 'NA'); 

        $kyc_vis_imp_cert_status = ($Res['benchmark_disability'] == 'Y' ? ($Res['kyc_vis_imp_cert_flag'] != "" ? $Res['kyc_vis_imp_cert_flag'] : 'P') : 'NA'); 
        $kyc_orth_han_cert_status = ($Res['benchmark_disability'] == 'Y' ? ($Res['kyc_orth_han_cert_flag'] != "" ? $Res['kyc_orth_han_cert_flag'] : 'P') : 'NA'); 
        $kyc_cer_palsy_cert_status = ($Res['benchmark_disability'] == 'Y' ? ($Res['kyc_cer_palsy_cert_flag'] != "" ? $Res['kyc_cer_palsy_cert_flag'] : 'P') : 'NA'); 
        

        $row[] = $no;
        $row[] = $Res['regnumber'];
        $row[] = $Res['reference'];
        $row[] = $kyc_fullname_status;
        $row[] = $kyc_dob_status;
        $row[] = $kyc_aadhar_status;
        $row[] = $kyc_apaar_status; 
        $row[] = $kyc_eligibility_status; 
        $row[] = $kyc_photo_status; 
        $row[] = $kyc_sign_status; 
        $row[] = $kyc_id_card_status; 
        $row[] = $kyc_aadhar_file_status; 
        $row[] = $kyc_qualification_cert_status; 
        $row[] = $kyc_institute_idproof_status; 
        $row[] = $kyc_declaration_status; 
        $row[] = $kyc_exp_certificate_status; 
        $row[] = $kyc_vis_imp_cert_status; 
        $row[] = $kyc_orth_han_cert_status; 
        $row[] = $kyc_cer_palsy_cert_status; 


      //if($form_action != 'export'){
         
        if( $Res['KycStatus'] == "In Progress" && $this->login_user_type == '1' && $Res['kyc_recommender_status'] == '1' && (empty($Res['kyc_approver_status']) || $Res['kyc_approver_status'] == '0' ))
        {
          if($Res['img_ediited_on'] != '' && $Res['img_ediited_on'] != '0000-00-00 00:00:00'){
            $row[] = $kyc_status_find = 'Attend Re-KYC';
          }
          else{
            $row[] = $kyc_status_find = 'Attend';
          }
        }
        else if( $Res['KycStatus'] == "In Progress" && $this->login_user_type == '1' && $Res['kyc_recommender_status'] == '2' && (empty($Res['kyc_approver_status']) || $Res['kyc_approver_status'] == '0' ))
        {
          $row[] = $kyc_status_find = 'Recommended';
        } 
        else if( $Res['KycStatus'] == "In Progress" && $this->login_user_type == '1' && $Res['kyc_recommender_status'] == '2' && $Res['kyc_approver_status'] == '1' && $Res['approver_id'] > 0)
        {
          if($Res['img_ediited_on'] != '' && $Res['img_ediited_on'] != '0000-00-00 00:00:00'){
            $row[] = $kyc_status_find = 'Recommended Re-KYC';
          }else{
            $row[] = $kyc_status_find = 'Recommended';
          } 
        } 
        else if($Res['KycStatus'] == "Pending" && $Res['img_ediited_on'] != '' && $Res['img_ediited_on'] != '0000-00-00 00:00:00')
        {
          $row[] = $kyc_status_find = 'Re-KYC';
        }
        else if($Res['KycStatus'] == "In Progress" && $this->login_user_type == '2' && $Res['kyc_recommender_status'] == '2' && $Res['img_ediited_on'] != '' && $Res['img_ediited_on'] != '0000-00-00 00:00:00')
        {
          $row[] = $kyc_status_find = 'Re-KYC';
        }
        else if( $Res['KycStatus'] == "In Progress" && $this->login_user_type == '2' && $Res['kyc_approver_status'] == '1' && $Res['recommender_id'] != '0' && $Res['kyc_recommender_status'] == '2' )
        {
          if($Res['img_ediited_on'] != '' && $Res['img_ediited_on'] != '0000-00-00 00:00:00'){
            $row[] = $kyc_status_find = 'Attend Re-KYC';
          }
          else{
            $row[] = $kyc_status_find = 'Attend';
          }
        }
        else if( $Res['KycStatus'] == "Rejected" && $this->login_user_type == '1' && $Res['kyc_recommender_status'] == '3')
        {
          if($Res['img_ediited_on'] != '' && $Res['img_ediited_on'] != '0000-00-00 00:00:00'){
            $row[] = $kyc_status_find = 'Rejected Re-KYC';
          }else{
            $row[] = $kyc_status_find = 'Rejected';
          } 
        }  
        else if( $Res['KycStatus'] == "Rejected" && $this->login_user_type == '1' && $Res['kyc_recommender_status'] == '2' && $Res['kyc_approver_status'] == '3' && $Res['approver_id'] > 0)
        {
          if($Res['img_ediited_on'] != '' && $Res['img_ediited_on'] != '0000-00-00 00:00:00'){
            $row[] = $kyc_status_find = 'Rejected Re-KYC';
          }else{
            $row[] = $kyc_status_find = 'Rejected';
          } 
        }
        else
        { 
            $row[] = $kyc_status_find = $Res['KycStatus']; 
        }

      //}

        $Where .= " AND nc.recommender_id = '".$this->login_admin_id."' AND nc.kyc_recommender_status = '2' AND nc.kyc_status IN(1) ";
 
        
        
        

        if ($form_action == 'export')
        {
          //$row[] = $kyc_status_find;
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
    }/******** END : SERVER SIDE DATATABLE CALL FOR KYC STATUS Report DATA ********/

  }	    