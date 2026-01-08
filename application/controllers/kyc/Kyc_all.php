<?php 
  /********************************************************************************************************************
  ** Description: Controller for Common KYC functionality (Recommender & Approver) 
  ** Created BY: Sagar Matale On 25-11-2024
  ********************************************************************************************************************/
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Kyc_all extends CI_Controller 
	{
		public function __construct()
		{
			parent::__construct();
			
			$this->load->model('master_model');
      $this->load->model('kyc/Kyc_model'); 
      $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 

      $this->login_admin_id = $this->session->userdata('KYC_LOGIN_ID');
      $this->login_user_type = $this->session->userdata('KYC_ADMIN_TYPE'); // 1=>RECOMMENDER, 2=>APPROVER
      $this->login_related_id = $this->session->userdata('KYC_RELATED_ID');
      $this->Kyc_model->check_admin_session_all_pages($this->login_user_type); // IF ADMIN SESSION IS NOT STARTED THEN REDIRECT TO LOGOUT

      /* START : BCBF MODULE FILE PATH */
      $this->bcbf_candidate_photo_path = 'uploads/iibfbcbf/photo';
      $this->bcbf_candidate_sign_path = 'uploads/iibfbcbf/sign';
      $this->bcbf_id_proof_file_path = 'uploads/iibfbcbf/id_proof';
      /* END : BCBF MODULE FILE PATH */

      /* START : DRA MODULE FILE PATH */
      $this->dra_candidate_photo_path = 'uploads/iibfdra';
      $this->dra_candidate_sign_path = 'uploads/iibfdra';
      $this->dra_id_proof_file_path = 'uploads/iibfdra';
      /* END : DRA MODULE FILE PATH */
    }

    /* START : GET COMMON DATA FOR VALIDATION PURPOSE FOR ALL MODULES */
    function get_common_data($module_name='')
    {
      $result = $form_membership_type_arr = $form_member_type_arr = $form_exam_codes_arr = array();      
      $disp_title = $photo_path = $sign_path = $id_proof_path = '';

      if($module_name == 'bcbf')//FOR BCBF MODULE 
      { 
        $disp_title = 'BCBF Module'; 
        $form_membership_type_arr = array('NM'=>'Non Member');
        $form_member_type_arr = array('new'=>'New Candidates', 'edited'=>'Edited Candidates');
        $form_exam_codes_arr = array(1037, 1038, 1039, 1040, 1041, 1042, 1057);
        $photo_path = $this->bcbf_candidate_photo_path;
        $sign_path = $this->bcbf_candidate_sign_path;
        $id_proof_path = $this->bcbf_id_proof_file_path;
      }
      else if($module_name == 'dra') //FOR DRA MODULE
      { 
        $disp_title = 'DRA Module';
        $form_membership_type_arr = array('NM'=>'Non Member');
        $form_member_type_arr = array('new'=>'New Candidates', 'edited'=>'Edited Candidates');
        $form_exam_codes_arr = array(45, 57, 1036);
        $photo_path = $this->dra_candidate_photo_path;
        $sign_path = $this->dra_candidate_sign_path;
        $id_proof_path = $this->dra_id_proof_file_path;
      }

      $result['disp_title'] = $disp_title;
      $result['form_membership_type_arr'] = $form_membership_type_arr;
      $result['form_member_type_arr'] = $form_member_type_arr;
      $result['form_exam_codes_arr'] = $form_exam_codes_arr;
      $result['photo_path'] = $photo_path;
      $result['sign_path'] = $sign_path;
      $result['id_proof_path'] = $id_proof_path;
      return $result;
    }/* END : GET COMMON DATA FOR VALIDATION PURPOSE FOR ALL MODULES */
    
    //MODULE_NAME LIKE bcbf, dra
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
        $this->session->set_flashdata('error','Invalid URL accessed');
        redirect(site_url('kyc/kyc_dashboard'));
      }/* END : CHECK FOR CORRECT MODULE NAME PASSED TO URL */
      
      /* START : CHECK IF KYC IS IN PROGRESS FOR ANY CANDIDATE WITH LOGGED IN ID & TYPE. IF YES, THEN REDIRECT TO COMPLETE THAT KYC PAGE */
      $kyc_inprogress_candidate_data = $this->get_kyc_inprogress_candidate_data($module_name, $form_exam_codes_arr);
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
        redirect(site_url('kyc/kyc_all/kyc_start/'.$module_name.'/'.$kyc_inprogress_candidate_data[0]['registration_type'].'/'.$member_type.'/'.$kyc_inprogress_candidate_data[0]['exam_code']));
      }/* END : CHECK IF KYC IS IN PROGRESS FOR ANY CANDIDATE WITH LOGGED IN ID & TYPE. IF YES, THEN REDIRECT TO COMPLETE THAT KYC PAGE */

      if(isset($_POST) && count($_POST) > 0 && isset($_POST['form_type']) && $_POST['form_type'] == 'search_form')
			{
        $this->form_validation->set_rules('s_membership_type', 'Membership Type', 'trim|required|xss_clean',array('required' => 'Please select %s'));
        $this->form_validation->set_rules('s_member_type', 'Member Type', 'trim|required|xss_clean',array('required' => 'Please select %s'));

        if($_POST['s_membership_type'] == 'NM')
        {
				  $this->form_validation->set_rules('s_exam_code', 'Exam Code', 'trim|required|xss_clean',array('required' => 'Please select %s'));
        }
				
				if($this->form_validation->run())		
				{
          $membership_type = $this->input->post('s_membership_type');
          $member_type = $this->input->post('s_member_type');

          if(isset($_POST['s_exam_code'])) { $exam_code = $this->input->post('s_exam_code'); }
          redirect(site_url('kyc/kyc_all/kyc_start/'.$module_name.'/'.$membership_type.'/'.$member_type.'/'.$exam_code));
				}
			}
      
			$data['module_name'] = $module_name;
			$data['disp_title'] = 'KYC - '.$disp_title;
      $data['page_title'] = 'IIBF KYC - '.$disp_title;
      $data['form_membership_type_arr'] = $form_membership_type_arr;
      $this->load->view('kyc/kyc_all', $data);
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
        $html = '	<div class="form-group text-left" style="min-width:250px;">';
        $html .= '  <select class="form-control search_opt" name="s_exam_code" id="s_exam_code" required>';
				
        $module_name = $this->security->xss_clean($this->input->post('module_name'));
        $selected_member_type = $this->security->xss_clean($this->input->post('selected_member_type'));
        $selected_membership_type = $this->security->xss_clean($this->input->post('selected_membership_type'));
				
        $common_data = $this->get_common_data($module_name);
        $exam_code_arr = $common_data['form_exam_codes_arr'];
        /* $reindexedArray = array_values($exam_code_arr);
        $inCondition = "'" . implode("','", $reindexedArray) . "'"; */
        
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
				$html .= '</div>';
        
        $result['flag'] = "success";
        $result['response'] = $html;
      }
      else
      {      
        $result['flag'] = "error";
      }
      echo json_encode($result);
    }/* END : GET EXAM CODE DROPDOWN VALUE WITH REMAINING KYC COUNT AJAX FUNCTION ON SELECTION OF MEMBERSHIP TYPE & MEMBER TYPE  */
    
    //module_name = bcbf, dra
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
      $photo_path = $sign_path = $id_proof_path = '';

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
      }
      else
      {
        $this->session->set_flashdata('error','Invalid URL accessed');
        redirect(site_url('kyc/kyc_dashboard'));
      }/* END : CHECK FOR CORRECT MODULE NAME PASSED TO URL */

      /* START : CHECK FOR CORRECT MEMBERSHIP TYPE, MEMBER TYPE & EXAM CODE AS PER THE MODULE */
      if(!array_key_exists($membership_type, $form_membership_type_arr))
      {
        $this->session->set_flashdata('error','Invalid URL accessed');
        redirect(site_url('kyc/kyc_dashboard'));
      }
      else if(!array_key_exists($member_type, $form_member_type_arr))
      {
        $this->session->set_flashdata('error','Invalid URL accessed');
        redirect(site_url('kyc/kyc_dashboard'));
      }
      else if($membership_type == 'NM' && !in_array($exam_code, $form_exam_codes_arr))
      {
        $this->session->set_flashdata('error','Invalid URL accessed');
        redirect(site_url('kyc/kyc_dashboard'));
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

        if($module_name == 'bcbf' && count($up_candidiate) > 0)//FOR BCBF MODULE
        {
          //UPDATE RECORDS IN iibfbcbf_batch_candidates table
          $update_qry_res = $this->master_model->updateRecord('iibfbcbf_batch_candidates', $up_candidiate, array('candidate_id'=>$kyc_pending_candidate_data[0]['candidate_id']));
        }  
        else if($module_name == 'dra' && count($up_candidiate) > 0)//FOR DRA MODULE
        {
          //UPDATE RECORDS IN dra_members table
          $update_qry_res = $this->master_model->updateRecord('dra_members', $up_candidiate, array('regid'=>$kyc_pending_candidate_data[0]['candidate_id']));
        }       
          
        if($update_qry_res)
        {
          $session_msg = $this->session->flashdata('success');
          $this->session->set_flashdata('success',$session_msg);
          redirect(site_url('kyc/kyc_all/kyc_start/'.$module_name.'/'.$membership_type.'/'.$member_type.'/'.$exam_code));
        }
      }
      else if(count($kyc_pending_candidate_data) == 0 && count($kyc_inprogress_candidate_data) == 0)
      {
        $this->session->set_flashdata('error','No candidate available for selected criteria');
        redirect(site_url('kyc/kyc_dashboard'));
      }//END : GET INPROGRESS CANDIDATE DATA COUNT & GET TOTAL PENDING CANDIDATE DATA COUNT. IF INPROGRESS CANDIDATE COUNT IS ZERO & TOTAL PENDING CANDIDATE COUNT IS HIGHER THAN ZERO, THEN ALLOCATE THAT MEMBER FOR KYC
      
      $data['module_name'] = $module_name;
      $data['membership_type'] = $membership_type;
      $data['member_type'] = $member_type;
      $data['exam_code'] = $exam_code;
      $data['disp_title'] = 'KYC for '.$disp_title.' - '.$form_membership_type_arr[$membership_type].' ('.$form_member_type_arr[$member_type].')';
      if($membership_type == 'NM')
      {
        $data['disp_title'] = 'KYC for '.$disp_title.' - '.$form_membership_type_arr[$membership_type].' ('.$form_member_type_arr[$member_type].' - '.$exam_code.')';
      }
      $data['page_title'] = 'IIBF KYC - '.$disp_title;
      $data['kyc_pending_candidate_data'] = $kyc_pending_candidate_data;
      $data['kyc_inprogress_candidate_data'] = $this->get_kyc_inprogress_candidate_data($module_name, $form_exam_codes_arr);
      $data['photo_path'] = $photo_path;
      $data['sign_path'] = $sign_path;
      $data['id_proof_path'] = $id_proof_path;
      $this->load->view('kyc/kyc_start', $data);
    } 

    /* START : THIS FUNCTION IS USED TO UPDATE THE KYC DATA BASED ON USER SUBMISSION */
    function process_kyc()
    {
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
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
        if($this->form_validation->run())
        { 
          $candidate_id = $this->input->post('candidate_id');
          $module_name = $this->input->post('module_name');
          $membership_type = $this->input->post('membership_type');
          $member_type = $this->input->post('member_type');
          $exam_code = $this->input->post('exam_code');
          $form_action = $this->input->post('form_action');
          
          $photo_file_kyc = $sign_file_kyc = $id_proof_file_kyc = '';
          if(isset($_POST['photo_file_kyc'])) { $photo_file_kyc = $this->input->post('photo_file_kyc'); }
          if(isset($_POST['sign_file_kyc'])) { $sign_file_kyc = $this->input->post('sign_file_kyc'); }
          if(isset($_POST['id_proof_file_kyc'])) { $id_proof_file_kyc = $this->input->post('id_proof_file_kyc'); }

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
          }
          else
          {
            $this->session->set_flashdata('error','Invalid URL accessed');
            redirect(site_url('kyc/kyc_dashboard'));
          }/* END : CHECK FOR CORRECT MODULE NAME PASSED TO URL */

          /* START : CHECK FOR CORRECT MEMBERSHIP TYPE, MEMBER TYPE & EXAM CODE AS PER THE MODULE */
          if(!array_key_exists($membership_type, $form_membership_type_arr))
          {
            $this->session->set_flashdata('error','Invalid URL accessed');
            redirect(site_url('kyc/kyc_dashboard'));
          }
          else if(!array_key_exists($member_type, $form_member_type_arr))
          {
            $this->session->set_flashdata('error','Invalid URL accessed');
            redirect(site_url('kyc/kyc_dashboard'));
          }
          else if($membership_type == 'NM' && !in_array($exam_code, $form_exam_codes_arr))
          {
            $this->session->set_flashdata('error','Invalid URL accessed');
            redirect(site_url('kyc/kyc_dashboard'));
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

          if($module_name == 'bcbf')//FOR BCBF MODULE
          {
            $candidate_data = $this->master_model->getRecords('iibfbcbf_batch_candidates', array('candidate_id' => $candidate_id, 'kyc_status' => '1'), 'candidate_id, exam_code, training_id, regnumber, salutation, first_name, middle_name, last_name, mobile_no, email_id, candidate_photo, candidate_sign, id_proof_file, kyc_photo_flag, kyc_sign_flag');
          }
          else if($module_name == 'dra')//FOR DRA MODULE
          {
            $candidate_data = $this->master_model->getRecords('dra_members', array('regid' => $candidate_id, 'kyc_status' => '1'), 'regid AS candidate_id, excode AS exam_code, training_id, regnumber, namesub AS salutation, firstname AS first_name, middlename AS middle_name, lastname AS last_name, mobile_no, email_id, scannedphoto AS candidate_photo, scannedsignaturephoto AS candidate_sign, idproofphoto AS id_proof_file, kyc_photo_flag, kyc_sign_flag');
          }

          if(count($candidate_data) == 0)
          {
            $this->session->set_flashdata('error','Something went wrong. Please try again.');
            redirect(site_url('kyc/kyc_dashboard'));
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
          if($module_name == 'bcbf')//FOR BCBF MODULE
          {
            //UPDATE RECORDS IN iibfbcbf_batch_candidates
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
              if($id_proof_file_kyc == 'N') //IF ID PROOF REJECT IN KYC, THEN UPDATE THE TABLE COLUMN & RENAME THE ID PROOF
              { 
                $approve_reject_flag = 'N';
                $up_candidiate['id_proof_file'] = '';  
                rename($id_proof_path.'/'.$candidate_data[0]['id_proof_file'], $id_proof_path.'/k_'.$candidate_data[0]['id_proof_file']);

                $log_message .= ($log_message != '' ? ' & ' : '').'Rejected the ID Proof';
                $kyc_action .= ($kyc_action != '' ? ' & ' : '').'ID Proof'; 
              }
              else if($id_proof_file_kyc == 'Y')
              {
                if(($this->login_user_type == '1' && $candidate_data[0]['kyc_id_card_flag'] != $id_proof_file_kyc) || $this->login_user_type == '2') 
                {
                  $log_message .= ($log_message != '' ? ' & ' : '').'Approved the ID Proof';
                }
              }
            }
          }
          else if($module_name == 'dra')//FOR DRA MODULE
          {
            //UPDATE RECORDS IN iibfbcbf_batch_candidates
            if($photo_file_kyc != '') 
            { 
              $up_candidiate['kyc_photo_flag'] = $photo_file_kyc; 
              if($photo_file_kyc == 'N')//IF PHOTO REJECT IN KYC, THEN UPDATE THE TABLE COLUMN & RENAME THE PHOTO
              { 
                $approve_reject_flag = 'N'; 
                $up_candidiate['scannedphoto'] = ''; 
                rename($photo_path.'/'.$candidate_data[0]['scannedphoto'], $photo_path.'/k_'.$candidate_data[0]['scannedphoto']);
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
                $up_candidiate['scannedsignaturephoto'] = '';
                rename($sign_path.'/'.$candidate_data[0]['scannedsignaturephoto'], $sign_path.'/k_'.$candidate_data[0]['scannedsignaturephoto']);

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
              if($id_proof_file_kyc == 'N') //IF ID PROOF REJECT IN KYC, THEN UPDATE THE TABLE COLUMN & RENAME THE ID PROOF
              { 
                $approve_reject_flag = 'N';
                $up_candidiate['idproofphoto'] = '';  
                rename($id_proof_path.'/'.$candidate_data[0]['idproofphoto'], $id_proof_path.'/k_'.$candidate_data[0]['idproofphoto']);

                $log_message .= ($log_message != '' ? ' & ' : '').'Rejected the ID Proof';
                $kyc_action .= ($kyc_action != '' ? ' & ' : '').'ID Proof'; 
              }
              else if($id_proof_file_kyc == 'Y')
              {
                if(($this->login_user_type == '1' && $candidate_data[0]['kyc_id_card_flag'] != $id_proof_file_kyc) || $this->login_user_type == '2') 
                {
                  $log_message .= ($log_message != '' ? ' & ' : '').'Approved the ID Proof';
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
              $this->Iibf_bcbf_model->send_approve_reject_kyc_email_sms($candidate_data,$module_name,$kyc_action);die;
          }*/


          if(count($up_candidiate) > 0)
          {
            if($module_name == 'bcbf')//FOR BCBF MODULE
            {
              $update_qry_res = $this->master_model->updateRecord('iibfbcbf_batch_candidates', $up_candidiate, array('candidate_id'=>$candidate_id));
            }
            else if($module_name == 'dra')//FOR DRA MODULE
            {
              $update_qry_res = $this->master_model->updateRecord('dra_members', $up_candidiate, array('regid'=>$candidate_id));
            }

            $log_tbl_name = '';
            if($module_name == 'bcbf')//FOR BCBF MODULE
            {
              $log_tbl_name = 'iibfbcbf_batch_candidates';
            }
            else if($module_name == 'dra')//FOR DRA MODULE
            {
              $log_tbl_name = 'dra_members';
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
              $this->Iibf_bcbf_model->send_approve_reject_kyc_email_sms($candidate_data,$module_name,$kyc_action);

            }
          }
          else
          {
            $this->session->set_flashdata('error','Something went wrong. Please try again.');
            redirect(site_url('kyc/kyc_dashboard'));
          }

          if($update_qry_res)
          {
            if($form_action == 'submit_and_next')
            { 
              //Send Email To Candidate For  
              //echo $this->session->flashdata('success_kyc');die;
              redirect(site_url('kyc/kyc_all/kyc_start/'.$module_name.'/'.$membership_type.'/'.$member_type.'/'.$exam_code));
            }
            else
            {
              redirect(site_url('kyc/kyc_dashboard'));
            }
          }
          else
          {
            $this->session->set_flashdata('error','Please submit the correct details');
            redirect(site_url('kyc/kyc_all/kyc_start/'.$_POST['module_name'].'/'.$_POST['membership_type'].'/'.$_POST['member_type'].'/'.$_POST['exam_code']));
          }
        }
        else
        {
          $this->session->set_flashdata('error','Please submit the correct details');
          redirect(site_url('kyc/kyc_all/kyc_start/'.$_POST['module_name'].'/'.$_POST['membership_type'].'/'.$_POST['member_type'].'/'.$_POST['exam_code']));
        }
      }
      else
      {
        $this->session->set_flashdata('error','You can not access this page directly.');
        redirect(site_url('kyc/kyc_dashboard'));
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
        $this->db->where('recommender_id != ', $this->login_related_id);
        $this->db->where("approver_id = '0' AND (kyc_approver_status IS NULL OR kyc_approver_status = '' OR kyc_approver_status = '0') AND recommender_id > 0 AND kyc_recommender_status = '2'");
        $this->db->where_in('kyc_status', array(1));
      }

      if($membership_type == 'NM' && $module_name == 'bcbf')//FOR BCBF MODULE
      {
        $this->db->where(" exam_code IN (".$inCondition.") ");

        $select_fields = '';
        if($is_dropdown == '1') 
        { 
          $this->db->group_by('exam_code'); 
          $select_fields = ', count(exam_code) AS RecordCnt';
        }
        else
        {
          $this->db->order_by('candidate_id', 'ASC');
        }

        if($exam_code != '' && $exam_code != '0') { $this->db->where('exam_code', $exam_code); }          
        $kyc_pending_candidate_data = $this->master_model->getRecords('iibfbcbf_batch_candidates', array('regnumber !=' => '', 'hold_release_status'=>'3', 'is_deleted'=>'0', 'DATE(kyc_eligible_date) <='=>date('Y-m-d')), 'candidate_id, exam_code'.$select_fields);
      }
      else if($membership_type == 'NM' && $module_name == 'dra')//FOR DRA MODULE
      {
        $this->db->where(" excode IN (".$inCondition.") ");

        $select_fields = '';
        if($is_dropdown == '1') 
        { 
          $this->db->group_by('excode'); 
          $select_fields = ', count(excode) AS RecordCnt';
        }
        else
        {
          $this->db->order_by('regid', 'ASC');
        }

        if($exam_code != '' && $exam_code != '0') { $this->db->where('excode', $exam_code); }          
        $kyc_pending_candidate_data = $this->master_model->getRecords('dra_members', array('regnumber !=' => '', 'hold_release'=>'Release', 'isdeleted'=>'0', 'DATE(kyc_eligible_date) <='=>date('Y-m-d')), 'regid AS candidate_id, excode AS exam_code'.$select_fields);
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

      if($module_name == 'bcbf') //FOR BCBF MODULE
      {
        $this->db->where(" exam_code IN (".$inCondition.") ");
        $this->db->where_in('kyc_status', array(1));
        $kyc_inprogress_candidate_data = $this->master_model->getRecords('iibfbcbf_batch_candidates', array('regnumber !=' => '', 'hold_release_status'=>'3', 'is_deleted'=>'0', 'DATE(kyc_eligible_date) <='=>date('Y-m-d')), 'candidate_id, regnumber, training_id, salutation, first_name, middle_name, last_name, dob, gender, mobile_no, email_id, id_proof_file, qualification_certificate_file as cert_file, candidate_photo as photo_file, candidate_sign as sign_file, registration_type, exam_code, kyc_eligible_date, img_ediited_on, kyc_photo_flag, kyc_sign_flag, kyc_id_card_flag AS kyc_id_proof_flag, kyc_status, kyc_recommender_status, recommender_id, kyc_approver_status, approver_id, kyc_recommender_date, kyc_approver_date');        
      }
      else if($module_name == 'dra') //FOR DRA MODULE
      {
        $this->db->where(" excode IN (".$inCondition.") ");        
        $this->db->where_in('kyc_status', array(1));
        $kyc_inprogress_candidate_data = $this->master_model->getRecords('dra_members', array('regnumber !=' => '', 'hold_release'=>'Release', 'isdeleted'=>'0', 'DATE(kyc_eligible_date) <='=>date('Y-m-d')), 'regid AS candidate_id, regnumber, training_id, namesub AS salutation, firstname AS first_name, middlename AS middle_name, lastname AS last_name, dateofbirth AS dob, gender, mobile_no, email_id, idproofphoto AS id_proof_file, quali_certificate as cert_file, scannedphoto as photo_file, scannedsignaturephoto as sign_file, "NM" AS registration_type, excode AS exam_code, kyc_eligible_date, img_ediited_on, kyc_photo_flag, kyc_sign_flag, kyc_id_card_flag AS kyc_id_proof_flag, kyc_status, kyc_recommender_status, recommender_id, kyc_approver_status, approver_id, kyc_recommender_date, kyc_approver_date');        
      }

      return $kyc_inprogress_candidate_data;
    }/* END : GET KYC INPROGRESS CANDIDATE DATA */
  }	    