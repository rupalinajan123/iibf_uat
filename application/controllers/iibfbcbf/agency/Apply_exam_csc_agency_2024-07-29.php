<?php 
  /********************************************************************************************************************
  ** Description: Controller for BCBF CSC Apply exam functionality
  ** Created BY: Sagar Matale On 12-03-2023
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Apply_exam_csc_agency extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      
      //echo "<h4>Sorry for the inconvenience, we performing some maintenance for 2 hours</h4>"; exit;

      $this->load->model('master_model');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
      $this->load->helper('file'); 
      $this->load->helper('getregnumber_helper'); 
      $this->load->model('billdesk_pg_model');
      $this->load->model('log_model');
      
      $this->login_agency_or_centre_id = $this->session->userdata('IIBF_BCBF_LOGIN_ID');
      $this->login_user_type = $this->session->userdata('IIBF_BCBF_USER_TYPE');

      if($this->login_user_type != 'agency' && $this->login_user_type != 'centre') { $this->login_user_type = 'invalid'; }
			$this->Iibf_bcbf_model->check_admin_session_all_pages($this->login_user_type); // If admin session is not started then redirect to logout

      if($this->login_user_type != 'centre')
      {
        $this->session->set_flashdata('error','You do not have permission to access Apply Exam module');
        redirect(site_url('iibfbcbf/agency/dashboard_agency'));
      }

      $this->login_agency_id = $this->login_centre_id = '';
      if($this->login_user_type == 'agency')
      {
        $this->login_agency_id = $this->login_agency_or_centre_id;
      }
      else if($this->login_user_type == 'centre')
      {
        $this->login_centre_id = $this->login_agency_or_centre_id;

        $centre_data = $this->master_model->getRecords('iibfbcbf_centre_master',array('centre_id'=>$this->login_agency_or_centre_id), 'centre_id, agency_id');
        if(count($centre_data) > 0)
        {
          $this->login_agency_id = $centre_data[0]['agency_id'];
        }
      }

      $agency_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('agency_id' => $this->login_agency_id), "agency_id, allow_exam_codes, allow_exam_types");
      if(count($agency_data) > 0)
      {
        if($agency_data[0]['allow_exam_types'] != 'CSC')
        {
          $this->session->set_flashdata('error','You do not have permission to access Apply Exam module');
          redirect(site_url('iibfbcbf/agency/dashboard_agency'));
        }
      }

      $this->buffer_days_after_training_end_date = '0';
      $this->buffer_days_after_candidate_add_date = '270';
      $this->utr_slip_path = 'uploads/iibfbcbf/utr_slip';

      if (strpos(base_url(), '/staging') !== false) 
      {
        //STAGING URL
        $this->DOCUMENT_ROOT_PATH = $_SERVER['DOCUMENT_ROOT'].'/staging';
      } 
      else 
      {
        //PRODUCTION URL
        $this->DOCUMENT_ROOT_PATH = $_SERVER['DOCUMENT_ROOT'];
      }

      $this->csc_venue_master_eligible_exam_codes_arr = array(1039,1040);//CSC VENUE MASTER IS ONLY APPLICABLE FOR EXAM CODE 1039 & 1040. HENCE THE CAPACITY CHECK IS APPLY FOR ONLY 1039 & 1040. 1039 & 1040 ARE CSC MODE EXAMS
		}

    public function index($enc_exam_code=0)
    {      
      //START : FOR CSC WALLET PAYMENT
      $this->session->set_userdata('non_memberdata', ''); 
      $this->session->set_userdata('memtype', ''); 
      $this->session->set_userdata('csctype', ''); 
      $this->session->set_userdata('csc_id', ''); 
      //END : FOR CSC WALLET PAYMENT

      $data['enc_exam_code'] = $enc_exam_code;
      
      $data['active_exam_data'] = $active_exam_data = $this->Iibf_bcbf_model->get_exam_activation_details($enc_exam_code, $this->login_agency_id, 'csc');
      if(count($active_exam_data) == 0)
      {
        $this->session->set_flashdata('error','This exam is not active');
        redirect(site_url('iibfbcbf/agency/dashboard_agency'));
      }

      if(isset($_POST) && count($_POST) > 0)
      {
        //SERVER SIDE VALIDATION
        $this->form_validation->set_rules('training_id','Training ID','trim|required|xss_clean',array('required' => 'Please enter the %s'));
                
        if($this->form_validation->run())
        {
          $training_id = $this->input->post('training_id');          
          
          $this->db->limit(1);
          $this->db->where(" (cand.training_id = '".$training_id."' OR cand.regnumber = '".$training_id."') ");
          $this->db->join('iibfbcbf_agency_centre_batch btch', 'btch.batch_id = cand.batch_id','INNER');
          $getData = $this->master_model->getRecords('iibfbcbf_batch_candidates cand',array(),'cand.candidate_id, cand.agency_id, btch.batch_type', array('cand.candidate_id'=>'DESC'));//GET CANDIDATES DETAILS
          if(count($getData) > 0)
          {
            //START : GET CANDIDATES DETAILS
            $enc_exam_period = url_encode($active_exam_data[0]['exam_period']);
            $enc_candidate_id = url_encode($getData[0]['candidate_id']);
            $resData = $this->Iibf_bcbf_model->get_exam_candidate_details($enc_exam_code, $enc_exam_period, $enc_candidate_id, '','csc');
            //END : GET CANDIDATES DETAILS

            if($resData['flag'] == 'error')
            {
              $data['error'] = $resData['response_msg'];
            }
            else
            {
              redirect(site_url('iibfbcbf/agency/apply_exam_csc_agency/apply_exam_candidate/'.$enc_exam_code.'/'.$enc_candidate_id));              
            }           
          }
          else { $data['error'] = 'Please enter valid Training ID'; }
        }			
      }      
      
      $data['act_id'] = "Exam ".$active_exam_data[0]['exam_code'];
      $data['sub_act_id'] = "Exam ".$active_exam_data[0]['exam_code'];
      $data['page_title'] = 'IIBF - BCBF CSC '.ucfirst($this->login_user_type).' : Apply for '.display_exam_name($active_exam_data[0]['description'], $active_exam_data[0]['exam_code'], $active_exam_data[0]['exam_type']); /* helpers/iibfbcbf/iibf_bcbf_helper.php */ 
      
      $this->load->view('iibfbcbf/agency/apply_exam_csc_agency', $data);
    }	
    
    /******** START : CSC CANDIDATES EXAM APPLICATION FUNCTION ********/
    public function apply_exam_candidate($enc_exam_code='0', $enc_candidate_id='0')
    {      
      $data['enc_exam_code'] = $enc_exam_code;      
      $data['enc_candidate_id'] = $enc_candidate_id;  
      $data['csc_venue_master_eligible_exam_codes_arr'] = $this->csc_venue_master_eligible_exam_codes_arr;  
      $data['error'] = '';    
      
      //START : GET ACTIVE EXAM DETAILS
      $agency_id = '0';
      $cand_data = $this->master_model->getRecords('iibfbcbf_batch_candidates cand',array('cand.candidate_id'=>url_decode($enc_candidate_id)), 'cand.candidate_id, cand.agency_id');
      if(count($cand_data) > 0)
      {
        $agency_id = $cand_data[0]['agency_id'];
      }
      
      $data['active_exam_data'] = $active_exam_data = $this->Iibf_bcbf_model->get_exam_activation_details($enc_exam_code, $agency_id, 'csc');       
      if(count($active_exam_data) == 0)
      {
        $this->session->set_flashdata('error','The exam is not activated');
        redirect(site_url('iibfbcbf/agency/dashboard_agency'));
      }//END : GET ACTIVE EXAM DETAILS

      $data['act_id'] = "Exam ".$active_exam_data[0]['exam_code'];
      $data['sub_act_id'] = "Exam ".$active_exam_data[0]['exam_code'];
      $data['page_title'] = 'IIBF - BCBF Apply CSC Exam - '.$active_exam_data[0]['description'];
      
      //START : GET CANDIDATES DETAILS
      $enc_exam_period = url_encode($active_exam_data[0]['exam_period']);
      $resData = $this->Iibf_bcbf_model->get_exam_candidate_details($enc_exam_code, $enc_exam_period, $enc_candidate_id, '', 'csc');
      if($resData['flag'] == 'error')
      {
        $this->session->set_flashdata('error',$resData['response_msg']);
        redirect(site_url('iibfbcbf/agency/apply_exam_csc_agency/index/'.$enc_exam_code));  
        //redirect(site_url('iibfbcbf/agency/dashboard_agency'));
      }//END : GET CANDIDATES DETAILS
      
      $data['candidate_data'] = $candidate_data = $resData['result_data'];      
      $data['id_proof_file_path'] = 'uploads/iibfbcbf/id_proof';
      $data['qualification_certificate_file_path'] = 'uploads/iibfbcbf/qualification_certificate';
      $data['candidate_photo_path'] = 'uploads/iibfbcbf/photo';
      $data['candidate_sign_path'] = 'uploads/iibfbcbf/sign';
      
      $this->db->group_by('exam_code');
      $data['subject_master'] = $this->master_model->getRecords('iibfbcbf_exam_subject_master',array('exam_code'=>$active_exam_data[0]['exam_code'],'subject_delete'=>'0','group_code'=>'C'),'',array('subject_code'=>'ASC'));

      $this->db->join('iibfbcbf_exam_activation_master eam','eam.exam_code = ecm.exam_name AND eam.exam_period = ecm.exam_period');
      $data['centre_master'] = $this->master_model->getRecords('iibfbcbf_exam_centre_master ecm',array('ecm.exam_name'=>$active_exam_data[0]['exam_code'], 'eam.exam_activation_delete'=>'0', 'ecm.centre_delete'=>'0'),'',array('ecm.centre_name'=>'ASC'));
            
      $this->db->join('iibfbcbf_exam_activation_master eam','eam.exam_code = emm.exam_code AND eam.exam_period = emm.exam_period');
      $data['medium_master'] = $this->master_model->getRecords('iibfbcbf_exam_medium_master emm',array('emm.exam_code'=>$active_exam_data[0]['exam_code'], 'eam.exam_activation_delete'=>'0', 'emm.medium_delete'=>'0'));

      $data['applied_exam_data'] = $applied_exam_data = $this->master_model->getRecords('iibfbcbf_member_exam',array('candidate_id'=>$candidate_data[0]['candidate_id'], 'exam_code'=>$active_exam_data[0]['exam_code'], 'exam_period'=>$active_exam_data[0]['exam_period'], 'pay_status'=>'2'),'',array('member_exam_id'=>'DESC'),'',1);

      $data['exam_code'] = $exam_code = url_decode($enc_exam_code);
      $data['exam_period'] = $exam_period = $active_exam_data[0]['exam_period'];

      //START : CALCULATE GROUP CODE, FEE AMOUNT, FRESH CANDIDATE COUNT & REPEATER CANDIDATE COUNT
      $totol_amt = $total_igst_amt = $total_cgst_amt = $total_sgst_amt = $cgst_rate = $cgst_amt = $sgst_rate = $sgst_amt = $igst_rate = $igst_amt = $cs_total = $igst_total = $cess = $fresh_cnt = $repeater_cnt = 0;   
      $group_code = $this->Iibf_bcbf_model->get_group_code($active_exam_data[0]['exam_code'], $active_exam_data[0]['exam_period'], $candidate_data[0]['regnumber']);
      $fee_paid_flag = 'P';
      $exam_fees = '0.00';
      $load_main_view_flag = 1;

      if($candidate_data[0]['regnumber'] != 0 && $candidate_data[0]['regnumber'] != "")
      {        
        /* $this->db->order_by("id","DESC"); 
        $eligible_master_data = $this->master_model->getRecords('iibfbcbf_eligible_master',array('member_no'=>$candidate_data[0]['regnumber'],'exam_code'=>$active_exam_data[0]['exam_code'], 'eligible_period'=>$active_exam_data[0]['exam_period']),'app_category, fee_paid_flag');
          
        if(count($eligible_master_data)>0)
        {
          if($eligible_master_data[0]['app_category'] == 'R' || $eligible_master_data[0]['app_category'] == 'B1')
          {
            $fresh_cnt = $fresh_cnt+1;                    
          }
          elseif($eligible_master_data[0]['app_category'] == 'S1')
          {
            $repeater_cnt = $repeater_cnt+1;
          }
          else 
          { 
            $fresh_cnt = $fresh_cnt+1;
          }

          if($eligible_master_data[0]['fee_paid_flag'] == 'F')
          {
            $exam_fees = '0.00';
            $fee_paid_flag = 'F';
          }
        }
        else
        {
          $fresh_cnt = $fresh_cnt+1;
        } */

        //ELIGIBLE MASTER API CODE GOES HERE
        $eligible_api_res = $this->Iibf_bcbf_model->iibf_bcbf_eligible_master_api($active_exam_data[0]['exam_code'], $active_exam_data[0]['exam_period'], $candidate_data[0]['regnumber']);                                          
        if($eligible_api_res['api_res_flag'] == 'success')
        {
          if(isset($eligible_api_res['api_res_response'][0]) && count($eligible_api_res['api_res_response'][0]) > 0)
          {
            $eligible_data = $eligible_api_res['api_res_response'][0];
            
            if($eligible_data['app_cat'] == 'B1_1')
            {
              $fresh_cnt = $fresh_cnt+1;                    
            }
            elseif($eligible_data['app_cat'] == 'B1_2')
            {
              $repeater_cnt = $repeater_cnt+1;
            }
            else 
            { 
              $fresh_cnt = $fresh_cnt+1;
            }            
          }
          else { $fresh_cnt = $fresh_cnt+1; }
        }
        else
        {
          $fresh_cnt = $fresh_cnt+1;
        }
      }
      else 
      { 
        $fresh_cnt = $fresh_cnt+1; 
      }
      //END : CALCULATE GROUP CODE, FEE AMOUNT, FRESH CANDIDATE COUNT & REPEATER CANDIDATE COUNT

      //START : GET EXAM DETAILS
      $this->db->join('iibfbcbf_exam_fee_master fm','fm.exam_code = em.exam_code', 'INNER');
      $this->db->join('iibfbcbf_exam_misc_master mm','mm.exam_code = em.exam_code', 'INNER');
      $this->db->join('iibfbcbf_exam_subject_master sm','sm.exam_code = em.exam_code', 'INNER');
      $get_exam_fee_data = $this->master_model->getRecords('iibfbcbf_exam_master em',array('em.exam_code'=>$exam_code, 'em.exam_delete'=>'0', 'fm.fee_delete'=>'0', 'fm.member_category'=>$candidate_data[0]['registration_type'], 'fm.group_code'=>$group_code, 'fm.exempt'=>'NE', 'fm.exam_code'=>$exam_code, 'fm.exam_period'=>$exam_period, 'mm.misc_delete'=>'0', 'sm.subject_delete'=>'0'), 'fm.cs_tot, fm.igst_tot, sm.exam_date, sm.exam_time');
      
      if(count($get_exam_fee_data) == 0)
      {
        $this->session->set_flashdata('error','Your information is incorrect. Please contact to iibf admin.');
        redirect(site_url('iibfbcbf/agency/apply_exam_csc_agency/index/'.$enc_exam_code));  
        //redirect(site_url('iibfbcbf/agency/dashboard_agency'));
      }//END : GET EXAM DETAILS

      $free_candidate_cnt = 0;
      if($fee_paid_flag != 'F')
      {
        $fee_master = $this->master_model->getRecords('iibfbcbf_exam_fee_master',array('fee_delete'=>'0', 'member_category'=>$candidate_data[0]['registration_type'], 'group_code'=>$group_code, 'exempt'=>'NE', 'exam_code'=>$active_exam_data[0]['exam_code'], 'exam_period'=>$active_exam_data[0]['exam_period']));
                
        if(count($fee_master) > 0)
        {                  
          $totol_amt = $totol_amt + $fee_master[0]['fee_amount'];     
          if($candidate_data[0]['LoggedInCentreState'] == 'MAH') { $exam_fees = $get_exam_fee_data[0]['cs_tot']; }
          else { $exam_fees = $get_exam_fee_data[0]['igst_tot']; }
        }
      } 
      else { $free_candidate_cnt = 1; }    
      $data['exam_fees'] = $exam_fees;

      $fee_amt = $totol_amt; // Total amount without any GST                
      $tax_type = '';
            
      if(isset($_POST) && count($_POST) > 0)
			{	
        if($exam_fees == 0)
        {
          $this->session->set_flashdata('error','You can not make payment for zero(0) fee');
          redirect(site_url('iibfbcbf/agency/apply_exam_csc_agency/apply_exam_candidate/'.$enc_exam_code.'/'.$enc_candidate_id));
        }
        
        //$this->check_last_payment($candidate_data[0]['candidate_id']);
        
        $this->form_validation->set_rules('exam_centre', 'Exam centre', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('exam_medium', 'Exam medium', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('venue_name', 'venue', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('exam_date', 'exam date', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('exam_time', 'exam time', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        //$this->form_validation->set_rules('xxx', 'exam time', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        
        if($this->form_validation->run() == TRUE)  
				{
          if(count($get_exam_fee_data) > 0)
          {
            $exam_centre_code = trim($this->security->xss_clean($this->input->post('exam_centre')));
            if(in_array($exam_code, $this->csc_venue_master_eligible_exam_codes_arr)) //CSC VENUE MASTER IS ONLY APPLICABLE FOR EXAM CODE 1039 & 1040. HENCE THE CAPACITY CHECK IS APPLY FOR ONLY 1039 & 1040. 1039 & 1040 ARE HYBRID MODE EXAMS
            {
              $exam_venue_code = trim($this->security->xss_clean($this->input->post('venue_name')));
              $chk_member_exam_id = ''; if(count($applied_exam_data) > 0) { $chk_member_exam_id = $applied_exam_data[0]['member_exam_id']; } 
              $chk_capacity = $this->Iibf_bcbf_model->get_capacity_csc($exam_code, $exam_period, $exam_centre_code, $exam_venue_code, trim($this->security->xss_clean($this->input->post('exam_date'))), trim($this->security->xss_clean($this->input->post('exam_time'))), $chk_member_exam_id);

              if($chk_capacity <= 0)
              {
                $this->session->set_flashdata('error','The capacity is full');
                redirect(site_url('iibfbcbf/agency/apply_exam_csc_agency/apply_exam_candidate/'.$enc_exam_code.'/'.$enc_candidate_id));
              }
            }

            $form_total_fees = $this->security->xss_clean($this->input->post('form_total_fees'));
            if($form_total_fees == $exam_fees)
            {
              $up_candidiate = array();
              $up_candidiate['exam_code'] = $exam_code;
              $up_candidiate['ip_address'] = get_ip_address(); //general_helper.php 
              $up_candidiate['updated_on'] = date('Y-m-d H:i:s');
              $up_candidiate['updated_by'] = $this->login_agency_or_centre_id;
              $this->master_model->updateRecord('iibfbcbf_batch_candidates', $up_candidiate, array('candidate_id'=>$candidate_data[0]['candidate_id']));

              $this->Iibf_bcbf_model->insert_common_log('CSC MAKE PAYMENT', 'iibfbcbf_batch_candidates', $this->db->last_query(), $candidate_data[0]['candidate_id'],'csc_make_payment_action','The candidate data updated successfully', json_encode($up_candidiate));
              
              $add_exam_data = array();
              
              $add_exam_data['exam_date'] = trim($this->security->xss_clean($this->input->post('exam_date')));
              $add_exam_data['exam_venue_code'] = $exam_venue_code;
              $add_exam_data['exam_time'] = trim($this->security->xss_clean($this->input->post('exam_time')));
              
              $add_exam_data['exam_centre_code'] = trim($this->security->xss_clean($this->input->post('exam_centre')));
              $add_exam_data['exam_medium'] = trim($this->security->xss_clean($this->input->post('exam_medium')));
              $add_exam_data['exam_code'] = $exam_code;
              $add_exam_data['exam_fee'] = $exam_fees;
              $add_exam_data['batch_id'] = $candidate_data[0]['batch_id'];
              $add_exam_data['exam_period'] = $active_exam_data[0]['exam_period'];
              $add_exam_data['pay_status'] = '2';
              $add_exam_data['payment_mode'] = 'CSC';
              $add_exam_data['candidate_id'] = $candidate_data[0]['candidate_id'];
              $add_exam_data['batch_start_date'] = $candidate_data[0]['batch_start_date'];
              $add_exam_data['batch_end_date']	= $candidate_data[0]['batch_end_date'];              
              $add_exam_data['fee_paid_flag'] = $fee_paid_flag;            
              $add_exam_data['ip_address'] = get_ip_address(); //general_helper.php 
              
              $posted_arr = json_encode($_POST);
              $member_exam_id = '0';
              if(count($applied_exam_data) == 0) //FOR ADD MODE
              {
                $add_exam_data['created_on'] = date('Y-m-d H:i:s');
                $add_exam_data['created_by'] = $this->login_agency_or_centre_id;
                
                $member_exam_id = $this->master_model->insertRecord('iibfbcbf_member_exam',$add_exam_data,true); 

                $this->Iibf_bcbf_model->insert_common_log('CSC MAKE PAYMENT', 'iibfbcbf_member_exam', $this->db->last_query(), $member_exam_id,'csc_make_payment_action','The candidate exam data inserted successfully', json_encode($add_exam_data));
              }
              else //FOR UPDATE MODE
              {
                $this->master_model->updateRecord('iibfbcbf_member_exam', $add_exam_data,  array('member_exam_id'=>$applied_exam_data[0]['member_exam_id']));
                $member_exam_id = $applied_exam_data[0]['member_exam_id'];

                $this->Iibf_bcbf_model->insert_common_log('CSC MAKE PAYMENT', 'iibfbcbf_member_exam', $this->db->last_query(), $member_exam_id,'csc_make_payment_action','The candidate exam data updated successfully', json_encode($add_exam_data));
              }

              if($member_exam_id == '' || $member_exam_id == '0')
              {
                $this->session->set_flashdata('error','Error Occurred. Please try again..');
                redirect(site_url('iibfbcbf/agency/apply_exam_csc_agency/apply_exam_candidate/'.$enc_exam_code.'/'.$enc_candidate_id));
              } 

              $this->Iibf_bcbf_model->insert_common_log('Candidate : Candidate Applied for exam', 'iibfbcbf_batch_candidates', $this->db->last_query(), $candidate_data[0]['candidate_id'],'candidate_action','The candidate is applying for the exam ('.$exam_code.' & '.$active_exam_data[0]['exam_period'].') by csc centre.', $posted_arr);
              
              $add_payment_data = array();              
              $utr_no = '';  
              $state_code = $candidate_data[0]['LoggedInCentreState']; //logged in centre state code
              $gstin_no = '-';              
              
              //START : INSERT PAYMENT TABLE ENTRY
              $pg_flag = 'BC';
              $add_payment_data['agency_id'] = $candidate_data[0]['agency_id'];
              $add_payment_data['centre_id'] = $candidate_data[0]['centre_id'];      
              $add_payment_data['exam_ids'] = $member_exam_id;
              $add_payment_data['amount'] = $exam_fees;
              $add_payment_data['gateway'] = '3';  // 1= NEFT / RTGS, 2=Billdesk, 3=>CSC
              $add_payment_data['UTR_no'] = $utr_no;
              $add_payment_data['agency_code'] = $candidate_data[0]['agency_code'];
              $add_payment_data['date'] = date('Y-m-d H:i:s');
              $add_payment_data['pay_count'] =  '1';
              $add_payment_data['exam_code'] =  $active_exam_data[0]['exam_code'];
              $add_payment_data['exam_period'] =  $active_exam_data[0]['exam_period'];
              $add_payment_data['payment_mode'] =  'CSC';
              $add_payment_data['pg_flag'] =  $pg_flag;
              $add_payment_data['status'] =  '2'; //pending
              $add_payment_data['payment_done_by_agency_id'] =  $this->login_agency_id;
              $add_payment_data['payment_done_by_centre_id'] =  $this->login_centre_id;
              $add_payment_data['ip_address'] = get_ip_address(); //general_helper.php 
              $add_payment_data['created_on'] = date('Y-m-d H:i:s');
              $add_payment_data['created_by'] = $this->login_agency_or_centre_id;
              $pt_id = $this->master_model->insertRecord('iibfbcbf_payment_transaction', $add_payment_data, true);
              //echo $this->db->last_query(); exit;
              
              if($pt_id > 0)
              {
                $this->master_model->updateRecord('iibfbcbf_member_exam ', array('pt_id'=>$pt_id, 'regnumber'=>$candidate_data[0]['regnumber']), array('member_exam_id' => $member_exam_id));//UPDATE PT ID IN iibfbcbf_member_exam
                
                $posted_arr = json_encode($_POST);
                
                $this->Iibf_bcbf_model->insert_common_log('CSC MAKE PAYMENT', 'iibfbcbf_payment_transaction', $this->db->last_query(), $pt_id,'csc_make_payment_action','The candidate payment data inserted successfully for the exam ('.$active_exam_data[0]['exam_code'].' & '.$active_exam_data[0]['exam_period'].' by csc', $posted_arr);
                
                $receipt_no = rand(22222222, 99999999);
                // payment gateway custom fields -
                $custom_field = $receipt_no . "^iibfexam^iibfbcbfexam^" . $candidate_data[0]['candidate_id'];
                
                $this->master_model->updateRecord('iibfbcbf_payment_transaction', array('receipt_no'=>$receipt_no, 'pg_other_details' => $custom_field), array('id'=>$pt_id));

                $this->Iibf_bcbf_model->insert_common_log('CSC MAKE PAYMENT', 'iibfbcbf_payment_transaction', $this->db->last_query(), $pt_id,'csc_make_payment_action','The candidate payment data updated successfully', '');
                //END : INSERT PAYMENT TABLE ENTRY
                
                //START : INSERT EXAM INVOICE TABLE ENTRY
                $iibfbcbf_fees_data = $this->Iibf_bcbf_model->iibfbcbf_get_fees($active_exam_data[0]['exam_code'], $active_exam_data[0]['exam_period']);             
                if($exam_fees > 0) 
                {
                  $add_invoice = array();
                  $add_invoice['pay_txn_id'] = $pt_id;
                  $add_invoice['receipt_no'] = $receipt_no;
                  $add_invoice['exam_code'] = $active_exam_data[0]['exam_code'];
                  $add_invoice['exam_period'] = $active_exam_data[0]['exam_period'];

                  $this->db->join('state_master sm', 'sm.state_code = ecm.state_code', 'LEFT');
                  $exam_centre_data = $this->master_model->getRecords('iibfbcbf_exam_centre_master ecm',array('ecm.exam_name'=>$active_exam_data[0]['exam_code'], 'ecm.exam_period'=>$active_exam_data[0]['exam_period'], 'ecm.centre_code'=>$exam_centre_code, 'ecm.centre_delete'=>'0'),'ecm.centre_code, ecm.centre_name, ecm.state_code, ecm.state_description, sm.state_no, sm.state_name');
                  if(count($exam_centre_data) > 0)
                  {
                    $add_invoice['center_code'] = $exam_centre_data[0]['centre_code']; //$candidate_data[0]['centre_id'];
                    $add_invoice['center_name'] = $exam_centre_data[0]['centre_name']; //$candidate_data[0]['centre_name'];
                    $add_invoice['state_of_center'] = $exam_centre_data[0]['state_code']; //$state_code;

                    if(count($fee_master) > 0)
                    {                  
                      if($exam_centre_data[0]['state_code'] == 'MAH')
                      {
                        $total_cgst_amt = $total_cgst_amt + $fee_master[0]['cgst_amt'];
                        $total_sgst_amt = $total_sgst_amt + $fee_master[0]['sgst_amt'];
                      }
                      else
                      {
                        $total_igst_amt = $total_igst_amt + $fee_master[0]['igst_amt'];
                      }
                    }

                    if($exam_centre_data[0]['state_code'] == 'MAH')
                    {
                      //set a rate (e.g 9%,9% or 18%)
                      $cgst_rate = $this->config->item('cgst_rate');
                      $sgst_rate = $this->config->item('sgst_rate');
                      
                      //set an amount as per rate
                      $cgst_amt = $total_cgst_amt;
                      $sgst_amt = $total_sgst_amt;
                      $cs_total = $get_exam_fee_data[0]['cs_tot'];
                      $tax_type = 'Intra';
                    }
                    else
                    {
                      //set a rate (e.g 9%,9% or 18%)
                      $igst_rate = $this->config->item('igst_rate');
                      $igst_amt = $total_igst_amt;
                      $igst_total = $get_exam_fee_data[0]['igst_tot'];
                      $tax_type = 'Inter';
                    }
                  }
                  
                  $add_invoice['institute_code'] = $candidate_data[0]['agency_code'];
                  $add_invoice['institute_name'] = $candidate_data[0]['agency_name'];
                  $add_invoice['app_type'] = 'BC';
                  $add_invoice['tax_type'] = $tax_type;
                  $add_invoice['service_code'] = $this->config->item('exam_service_code');
                  $add_invoice['gstin_no'] = $gstin_no;
                  $add_invoice['qty'] = 1 - $free_candidate_cnt;
                  $add_invoice['state_code'] = $exam_centre_data[0]['state_no']; //$candidate_data[0]['state_no'];
                  $add_invoice['state_name'] = $exam_centre_data[0]['state_name']; //$candidate_data[0]['LoggedInCentreStateName'];
                  $add_invoice['fresh_fee'] = $iibfbcbf_fees_data['fresh_fee'];
                  $add_invoice['rep_fee'] = $iibfbcbf_fees_data['rep_fee'];
                  $add_invoice['fresh_count'] = $fresh_cnt;
                  $add_invoice['rep_count'] = $repeater_cnt;
                  $add_invoice['fee_amt'] = $fee_amt;
                  $add_invoice['cgst_rate'] = $cgst_rate;
                  $add_invoice['cgst_amt'] = $cgst_amt;
                  $add_invoice['sgst_rate'] = $sgst_rate;
                  $add_invoice['sgst_amt'] = $sgst_amt;
                  $add_invoice['igst_rate'] = $igst_rate;
                  $add_invoice['igst_amt'] = $igst_amt;
                  $add_invoice['cs_total'] = $cs_total;
                  $add_invoice['igst_total'] = $igst_total;
                  $add_invoice['cess'] = $cess;
                  $add_invoice['exempt'] = $candidate_data[0]['exempt'];
                  $add_invoice['transaction_no'] = $utr_no;
                  $add_invoice['created_on'] = date('Y-m-d H:i:s');
                  $invoice_id = $this->master_model->insertRecord('exam_invoice',$add_invoice,true);
                  
                  $this->Iibf_bcbf_model->insert_common_log('CSC MAKE PAYMENT', 'exam_invoice', $this->db->last_query(), $invoice_id,'csc_make_payment_action','The candidate record successfully inserted in exam invoice table by csc.', json_encode($add_invoice));
                  
                }//END : INSERT EXAM INVOICE TABLE ENTRY
                
                //START : CSC WALLET PAYMENT CODE
                if($exam_fees > 0)
                {
                  /* $billdesk_res = $this->billdesk_pg_model->init_payment_request($receipt_no, $exam_fees, $invoice_id, $invoice_id, $candidate_data[0]['first_name'], 'iibfbcbf/agency/apply_exam_csc_agency/handle_billdesk_response/'.url_encode($pt_id), '', '', '', $custom_field_billdesk);                  				
                    if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE')
                    {
                    $load_main_view_flag = 0;
                    $data['bdorderid'] = $billdesk_res['bdorderid'];
                    $data['token'] = $billdesk_res['token'];
                    $data['responseXHRUrl'] = $billdesk_res['responseXHRUrl'];
                    $data['returnUrl'] = $billdesk_res['returnUrl'];
                    $this->load->view('pg_billdesk/pg_billdesk_form', $data);
                    }
                    else
                    {
                    $this->session->set_flashdata('error','Transaction failed...!');
                    redirect(site_url('iibfbcbf/agency/apply_exam_csc_agency/apply_exam_candidate/'.$enc_exam_code.'/'.$enc_candidate_id));
                  } */
                  
                  $userarr = array();
                  $userarr['candidate_id'] = $candidate_data[0]['candidate_id'];
                  $userarr['email'] = $candidate_data[0]['email_id'];
                  $userarr['exam_fee'] = $exam_fees;
                  $userarr['exam_desc'] = $active_exam_data[0]['description'];
                  $userarr['excode'] = $active_exam_data[0]['exam_code'];
                  $userarr['memtype'] = $candidate_data[0]['registration_type'];
                  $userarr['member_exam_id'] = $member_exam_id;
                  $userarr['pt_id'] = $pt_id;
                  $userarr['receipt_no'] = $receipt_no;
                  $userarr['invoice_id'] = $invoice_id;
                  $this->session->set_userdata('non_memberdata', $userarr); 
                  
                  $this->session->set_userdata(array('memtype'=>$candidate_data[0]['registration_type'], 'csctype'=>'iibfbcbf_apply_exam'));
                  //_pa($_SESSION,1);

                  $this->Iibf_bcbf_model->insert_common_log('CSC MAKE PAYMENT', '', '0', '0','csc_make_payment_action','The center successfully redirected to csc wallet', json_encode($_SESSION));

                  redirect(site_url('CSC_connect/User.php'));
                }//END : CSC WALLET PAYMENT CODE
              }
              else
              {
                $this->Iibf_bcbf_model->insert_common_log('Candidate : Candidate Applied for exam', 'iibfbcbf_batch_candidates', $this->db->last_query(), $candidate_data[0]['candidate_id'],'candidate_action','Error occurred while candidate applying for the exam ('.$exam_code.' & '.$active_exam_data[0]['exam_period'].') by csc centre.', $posted_arr);

                $this->session->set_flashdata('error','Error occurred while making the payment.');
                redirect(site_url('iibfbcbf/agency/apply_exam_csc_agency/apply_exam_candidate/'.$enc_exam_code.'/'.$enc_candidate_id));
              }  
            }
            else
            {
              $this->session->set_flashdata('error','There is some error in posted data');
              redirect(site_url('iibfbcbf/agency/apply_exam_csc_agency/apply_exam_candidate/'.$enc_exam_code.'/'.$enc_candidate_id));
            }
          }
          else
          {
            $this->session->set_flashdata('error','Your information is incorrect. Please contact to iibf admin.');
            redirect(site_url('iibfbcbf/agency/apply_exam_csc_agency/apply_exam_candidate/'.$enc_exam_code.'/'.$enc_candidate_id));
          }
				}
			}
      
      if($load_main_view_flag == '1') { $this->load->view('iibfbcbf/agency/apply_exam_candidate_csc', $data); }
    }/******** END : CSC CANDIDATES EXAM APPLICATION FUNCTION ********/

    function wallet_make_payment($csc_id=0)
    {       
      $member_exam_id = $this->session->userdata['non_memberdata']['member_exam_id'];
      $pt_id = $this->session->userdata['non_memberdata']['pt_id'];
      $receipt_no = $this->session->userdata['non_memberdata']['receipt_no'];
      $candidate_id = $this->session->userdata['non_memberdata']['candidate_id'];
      $exam_desc = $this->session->userdata['non_memberdata']['exam_desc'];

      if(isset($csc_id) && $csc_id > 0)
      {
        if(!$this->session->userdata('csc_id'))
				{
					$this->session->set_userdata('csc_id',$csc_id);
				}
        
        require_once $this->DOCUMENT_ROOT_PATH.'/BridgePG/PHP_BridgePG/BridgePGUtil.php';
				$rand_no = date('Ymdhims');
				$csc_success_url = site_url('iibfbcbf/agency/apply_exam_csc_agency/csc_transsuccess');
				$csc_fail_url = site_url('iibfbcbf/agency/apply_exam_csc_agency/csc_transfail');
				$csc_product_id = $this->config->item('csc_product_id');
        
        $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction',array('id'=>$pt_id, 'receipt_no'=>$receipt_no, 'status'=>'2'),'id, agency_id, centre_id, exam_ids, exam_code, exam_period, amount, agency_code',array('id'=>'DESC'),'',1);
        
        $member_exam_data = $this->master_model->getRecords('iibfbcbf_member_exam',array('member_exam_id'=>$member_exam_id, 'pay_status'=>'2'),'member_exam_id, candidate_id, batch_id, exam_fee',array('member_exam_id'=>'DESC'),'',1);
        
        if(count($payment_data) > 0 && count($member_exam_data) > 0 && $payment_data[0]['amount'] > 0 && $member_exam_data[0]['exam_fee'] > 0 && $payment_data[0]['amount'] == $member_exam_data[0]['exam_fee'])
        {
          /*$allow_ip_arr = array('182.73.101.70', '115.124.115.69');
          if(in_array(get_ip_address(), $allow_ip_arr)) { $payment_data[0]['amount'] = 1; }*/
          $pay_arr = array();
          $pay_arr['csc_id'] = $csc_id;
          $pay_arr['merchant_receipt_no'] = $rand_no;
          $pay_arr['txn_amount'] = $payment_data[0]['amount'];
          $pay_arr['return_url'] = $csc_success_url;
          $pay_arr['cancel_url'] = $csc_fail_url;
          $pay_arr['product_id'] = $csc_product_id;
          $pay_arr['merchant_txn'] = $receipt_no;
                    
          ########## CSC wallet parameter #########
          $bconn = new BridgePGUtil();
          $bconn->set_params($pay_arr);
          $enc_text = $bconn->get_parameter_string();
          $frac = $bconn->get_fraction();
          
          $data = array();
          $data['enc_text'] = $enc_text;
          $data['frac'] = $frac;          
          $this->load->view('csc',$data);
        }
        else
        {
          if($payment_data[0]['status'] != '1')
          {
            $this->Iibf_bcbf_model->insert_common_log('CSC MAKE PAYMENT', '', '', '0','csc_make_payment_action','Invalid request 1', '');
            $this->wallet_make_payment_fail($member_exam_id,$pt_id,$receipt_no);
          }

          $this->session->set_flashdata('error','something went wrong!');
          redirect(site_url('iibfbcbf/agency/dashboard_agency'));
        }
      }
      else
      {    
        $this->Iibf_bcbf_model->insert_common_log('CSC MAKE PAYMENT', '', '', '','csc_make_payment_action','Invalid request 2', '');

        $this->wallet_make_payment_fail($member_exam_id,$pt_id,$receipt_no);
        $this->session->set_flashdata('error','something went wrong!!');
        redirect(site_url('iibfbcbf/agency/dashboard_agency'));
      }
    }

    function wallet_make_payment_fail($member_exam_id=0,$pt_id=0,$receipt_no=0)
    {
      $up_mem_exam = array();
      $up_mem_exam['pay_status'] = '0';
      $up_mem_exam['description'] = 'Error occurred while making payment using csc wallet';
      $up_mem_exam['ip_address'] = get_ip_address(); //general_helper.php 
      $up_mem_exam['updated_on'] = date('Y-m-d H:i:s');
      $up_mem_exam['updated_by'] = $this->login_agency_or_centre_id;
      $this->master_model->updateRecord('iibfbcbf_member_exam', $up_mem_exam, array('member_exam_id'=>$member_exam_id, 'pay_status'=>'2'));

      $up_payment_tra = array();
      $up_payment_tra['status'] = '0';
      $up_payment_tra['description'] = 'Error occurred while making payment using csc wallet';
      $up_payment_tra['transaction_details'] = 'Error occurred while making payment using csc wallet';
      $up_payment_tra['ip_address'] = get_ip_address(); //general_helper.php 
      $up_payment_tra['approve_reject_date'] = date('Y-m-d H:i:s');
      $up_payment_tra['updated_on'] = date('Y-m-d H:i:s');
      $up_payment_tra['updated_by'] = $this->login_agency_or_centre_id;
      $this->master_model->updateRecord('iibfbcbf_payment_transaction', $up_payment_tra, array('id'=>$pt_id, 'receipt_no'=>$receipt_no, 'status'=>'2'));

      $member_exam_data = $this->master_model->getRecords('iibfbcbf_member_exam',array('member_exam_id'=>$member_exam_id),'candidate_id, exam_code, exam_period');
      $this->Iibf_bcbf_model->insert_common_log('Candidate : Candidate payment fail', 'iibfbcbf_batch_candidates', $this->db->last_query(), $member_exam_data[0]['candidate_id'],'candidate_action','Error occurred while candidate applying for the exam ('.$member_exam_data[0]['exam_code'].' & '.$member_exam_data[0]['exam_period'].') by csc centre.', '');
    }

    public function csc_transsuccess()
		{
      require_once $this->DOCUMENT_ROOT_PATH. "/BridgePG/PHP_BridgePG/BridgePGUtil.php";
			$bconn = new BridgePGUtil ();
			$bridge_message = $bconn->get_bridge_message();
			$params = explode('|', $bridge_message);
    	//breack with pipe operators
			$fine_params = array();
			foreach ($params as $param) 
      {
				$param = explode('=', $param);
				if (isset($param[0])) 
        {
			   	if(isset($param[1]))
					{
						$fine_params[$param[0]] = $param[1];
					}
				}
			}
      /* echo '<pre>';
      print_r($fine_params);
      echo '</pre>';
      echo 'in - success'; exit; */

      $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK', '', '', '0', 'csc_payment_callback','The csc wallet payment response received successfully', json_encode($fine_params));
      
      $transaction_no = $fine_params['csc_txn'];
      $merchant_id = $fine_params['merchant_id'];
      $csc_id = $fine_params['csc_id'];
      $receipt_no = $fine_params['merchant_txn'];
      $txn_status = $fine_params['txn_status'];
      $merchant_txn_date_time = $fine_params['merchant_txn_date_time'];
      $product_id = $fine_params['product_id'];
      $txn_amount = $fine_params['txn_amount'];
      $merchant_receipt_no = $fine_params['merchant_receipt_no'];
      $txn_status_message = $fine_params['txn_status_message'];
      $status_message = $fine_params['status_message'];

      if ($txn_status == "100")
      {
        $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('receipt_no' => $receipt_no, 'payment_mode'=>'CSC'), 'id, status, date');					
        
        if($payment_data[0]['status'] == '2')//IF payment status is PENDING
        {
          // START : UPDATE PAYMENT SUCCESS STATUS IN PAYMENT TRANSACTION TABLE AND INSERT LOG
          $update_data = array();
          $update_data['transaction_no'] = $transaction_no;
          $update_data['status'] = '1';
          $update_data['transaction_details'] = $txn_status_message. " - " .$status_message. " - " .$merchant_id. " - " .$csc_id. " - " .$product_id. " - " .$merchant_receipt_no. " - " .$merchant_txn_date_time. " - " .$txn_amount;
          $update_data['auth_code'] = '0300';
          $update_data['bankcode'] = 'csc';
          $update_data['paymode'] = 'wallet';
          $update_data['callback'] = 'B2B';						
          $update_data['description'] = 'Payment Success By CSC';
          $update_data['approve_reject_date'] = date('Y-m-d H:i:s');
          $update_data['updated_on'] = date('Y-m-d H:i:s');
          $this->master_model->updateRecord('iibfbcbf_payment_transaction', $update_data, array('receipt_no' => $receipt_no, 'status' => '2', 'payment_mode'=>'CSC', 'id'=>$payment_data[0]['id']));
          
          $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK', 'iibfbcbf_payment_transaction', $this->db->last_query(), $payment_data[0]['id'],'csc_payment_callback','The csc wallet payment is success', json_encode($update_data));
          // END : UPDATE PAYMENT SUCCESS STATUS IN PAYMENT TRANSACTION TABLE AND INSERT LOG
        
          // QUERY TO GET UPDATED PAYMENT DETAILS AFTER PAYMENT SUCCESS
          $payment_info = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('receipt_no' => $receipt_no, 'payment_mode'=>'CSC'), 'transaction_no, date, amount, id, description, status, exam_ids, exam_code, exam_period, receipt_no');
    
          if(count($payment_info) > 0 && $payment_info[0]['status'] == '1')
          {
            $member_exam_id = $payment_info[0]['exam_ids'];

            //START : GET MEMBER REGNUMBER. IF IT IS EMPTY THEN GENERATE NEW REGNUMBER, RENAME THE IMAGES. ALSO UPDATE THE RE-ATTEMPT         
            $this->db->join('iibfbcbf_batch_candidates bc', 'bc.candidate_id = me.candidate_id', 'INNER');
            $member_data = $this->master_model->getRecords('iibfbcbf_member_exam me', array('me.member_exam_id' => $member_exam_id, 'payment_mode'=>'CSC'), 'bc.candidate_id, bc.regnumber, bc.candidate_photo, bc.candidate_sign, bc.id_proof_file, bc.qualification_certificate_file, bc.re_attempt');

            if(count($member_data) > 0)
            {
              $up_cand_data = array();

              //START : GENERATE REGNUMBER AND RENAME THE IMAGES
              $log_msg = '';
              if($member_data[0]['regnumber'] == '')
              {
                $id_proof_file_path = 'uploads/iibfbcbf/id_proof';
                $qualification_certificate_file_path = 'uploads/iibfbcbf/qualification_certificate';
                $candidate_photo_path = 'uploads/iibfbcbf/photo';
                $candidate_sign_path = 'uploads/iibfbcbf/sign';
                
                $current_id_proof_file = $member_data[0]['id_proof_file'];
                $current_qualification_certificate_file = $member_data[0]['qualification_certificate_file'];
                $current_candidate_photo = $member_data[0]['candidate_photo'];
                $current_candidate_sign = $member_data[0]['candidate_sign'];              
                
                $up_cand_data['regnumber'] = $new_regnumber = generate_NM_memreg($member_data[0]['candidate_id']);
                
                if(!empty($current_id_proof_file)) 
                {
                  $new_id_proof_file = 'id_proof_'.$new_regnumber.'.'.strtolower(pathinfo($current_id_proof_file, PATHINFO_EXTENSION));
                  $chk_rename_id_proof = $this->Iibf_bcbf_model->check_file_rename($current_id_proof_file, "./".$id_proof_file_path."/", $new_id_proof_file);

                  if($chk_rename_id_proof == 'success') { $up_cand_data['id_proof_file'] = $new_id_proof_file; }
                }

                if(!empty( $current_qualification_certificate_file)) 
                {
                  $new_qualification_certificate_file = 'quali_cert_'.$new_regnumber.'.'.strtolower(pathinfo($current_qualification_certificate_file, PATHINFO_EXTENSION));                
                  $chk_rename_quali_cert = $this->Iibf_bcbf_model->check_file_rename($current_qualification_certificate_file, "./".$qualification_certificate_file_path."/", $new_qualification_certificate_file);

                  if($chk_rename_quali_cert == 'success') { $up_cand_data['qualification_certificate_file'] = $new_qualification_certificate_file; }
                }

                if(!empty($current_candidate_photo)) 
                {
                  $new_candidate_photo = 'photo_'.$new_regnumber.'.'.strtolower(pathinfo($current_candidate_photo, PATHINFO_EXTENSION));
                  $chk_rename_photo = $this->Iibf_bcbf_model->check_file_rename($current_candidate_photo, "./".$candidate_photo_path."/", $new_candidate_photo);

                  if($chk_rename_photo == 'success') { $up_cand_data['candidate_photo'] = $new_candidate_photo; }
                }

                if(!empty( $current_candidate_sign)) 
                {
                  $new_candidate_sign = 'sign_'.$new_regnumber.'.'.strtolower(pathinfo($current_candidate_sign, PATHINFO_EXTENSION));
                  $chk_rename_sign = $this->Iibf_bcbf_model->check_file_rename($current_candidate_sign, "./".$candidate_sign_path."/", $new_candidate_sign);

                  if($chk_rename_sign == 'success') { $up_cand_data['candidate_sign'] = $new_candidate_sign; }
                }   
                
                $log_msg .= 'The regnumber is successfully generated, successfully rename the images';
              }//END : GENERATE REGNUMBER AND RENAME THE IMAGES
              
              $up_cand_data['re_attempt'] = $member_data[0]['re_attempt'] + 1;//UPDATE RE-ATTEMT
              $up_cand_data['updated_on'] = date('Y-m-d H:i:s');
              $this->master_model->updateRecord('iibfbcbf_batch_candidates',$up_cand_data, array('candidate_id' => $member_data[0]['candidate_id']));
              if($log_msg == "") { $log_msg .= 'The re-attempt is updated successfully'; }
              else { $log_msg .= ' and re-attempt is updated successfully'; }

              $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK : generate new regnumber, rename the images and update re-attempt', 'iibfbcbf_batch_candidates', $this->db->last_query(), $member_data[0]['candidate_id'],'csc_payment_callback',$log_msg, json_encode($update_data));

              //START : UPDATE TRANSACTION NUMBER & PAYMENT STATUS IN iibfbcbf_member_exam
              $up_exam_data = array();
              $up_exam_data['ref_utr_no'] = $transaction_no;
              $up_exam_data['pay_status'] = '1';
              if(isset($new_regnumber) && $new_regnumber != '') { $up_exam_data['regnumber'] = $new_regnumber; }
              $this->master_model->updateRecord('iibfbcbf_member_exam',$up_exam_data, array('member_exam_id' => $member_exam_id));
              
              $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK : update transaction number and payment status in member exam', 'iibfbcbf_member_exam', $this->db->last_query(), $member_exam_id,'csc_payment_callback','The transaction number and payment status is successfully updated in member exam', json_encode($up_exam_data));

              $this->Iibf_bcbf_model->insert_common_log('Candidate : Candidate payment success', 'iibfbcbf_batch_candidates', '', $member_data[0]['candidate_id'],'candidate_action','The candidate has successfully applied for the exam ('.$payment_info[0]['exam_code'].' & '.$payment_info[0]['exam_period'].') by csc centre.', '');
            }//END : GET MEMBER REGNUMBER. IF IT IS EMPTY THEN GENERATE NEW REGNUMBER, RENAME THE IMAGES. ALSO UPDATE THE RE-ATTEMPT
            
            // START : GENERATE INVOICE NUMBER AND INVOICE IMAGE.
            $exam_invoice = $this->master_model->getRecords('exam_invoice',array('pay_txn_id' => $payment_info[0]['id'], 'exam_code'=>$payment_info[0]['exam_code'], 'exam_period'=>$payment_info[0]['exam_period'], 'receipt_no'=>$payment_info[0]['receipt_no'], 'app_type'=>'BC'),'invoice_id');
          
            if(count($exam_invoice) > 0 && $payment_info[0]['amount'] > 0)
            {
              $invoice_no = generate_iibfbcbf_exam_invoice_number($exam_invoice[0]['invoice_id']); // Use helpers/iibfbcbf/iibf_bcbf_helper.php
            
              if($invoice_no)
              {
                $invoice_no = $this->config->item('iibfbcbf_exam_invoice_no_prefix').$invoice_no; // e.g. EXM/2017-18/000001
              }
              
              $up_invoice_data['invoice_no'] = $invoice_no;
              $up_invoice_data['transaction_no'] = $transaction_no;
              $up_invoice_data['date_of_invoice'] = date('Y-m-d H:i:s');
              $up_invoice_data['modified_on'] = date('Y-m-d H:i:s');            
              $this->master_model->updateRecord('exam_invoice',$up_invoice_data,array('invoice_id' => $exam_invoice[0]['invoice_id']));
              
              $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK : update exam invoice number and image', 'exam_invoice', $this->db->last_query(), $exam_invoice[0]['invoice_id'],'csc_payment_callback','The exam invoice number and image is successfully updated in exam invoice table', json_encode($up_invoice_data));          
              
              $invoice_img_path = genarate_iibf_bcbf_exam_invoice($exam_invoice[0]['invoice_id']); // Use helpers/iibfbcbf/iibf_bcbf_helper.php  
            }// END : GENERATE INVOICE NUMBER AND INVOICE IMAGE.
            
            $this->Iibf_bcbf_model->generate_admit_card_common(url_encode($payment_data[0]['id'])); //GENERATE ADMITCARD
          
            $this->Iibf_bcbf_model->send_transaction_details_email_sms($payment_data[0]['id']);
            
            $this->session->set_flashdata('success','Your transactions is successful.');
            redirect(site_url('iibfbcbf/agency/apply_exam_csc_agency/acknowledgment/'. url_encode($receipt_no)));
          }
          else
          {
            $this->session->set_flashdata('error','Error occurred.');            
            redirect(site_url('iibfbcbf/agency/dashboard_agency'));
          }
        }
        else
        {
          $this->session->set_flashdata('error','Error occurred.');            
          redirect(site_url('iibfbcbf/agency/dashboard_agency'));
        }
      }
      else
      {
        $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('receipt_no' => $receipt_no, 'payment_mode'=>'CSC'), 'id, status, date');					
        
        if($payment_data[0]['status'] == '2')//IF payment status is PENDING
        {
          // START : UPDATE PAYMENT FAIL STATUS IN PAYMENT TRANSACTION TABLE AND INSERT LOG
          $update_data = array();
          $update_data['transaction_no'] = $transaction_no;
          $update_data['status'] = '0';
          $update_data['transaction_details'] = $txn_status_message. " - " .$status_message. " - " .$merchant_id. " - " .$csc_id. " - " .$product_id. " - " .$merchant_receipt_no. " - " .$merchant_txn_date_time. " - " .$txn_amount;
          $update_data['auth_code'] = '0300';
          $update_data['bankcode'] = 'csc';
          $update_data['paymode'] = 'wallet';
          $update_data['callback'] = 'B2B';						
          $update_data['description'] = 'Payment Fail By CSC';
          $update_data['approve_reject_date'] = date('Y-m-d H:i:s');
          $update_data['updated_on'] = date('Y-m-d H:i:s');
          $this->master_model->updateRecord('iibfbcbf_payment_transaction', $update_data, array('receipt_no' => $receipt_no, 'status' => '2', 'payment_mode'=>'CSC', 'id'=>$payment_data[0]['id']));
          
          $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK', 'iibfbcbf_payment_transaction', $this->db->last_query(), $payment_data[0]['id'],'csc_payment_callback','The csc wallet payment is fail', json_encode($update_data));
          // END : UPDATE PAYMENT FAIL STATUS IN PAYMENT TRANSACTION TABLE AND INSERT LOG
        
          // QUERY TO GET UPDATED PAYMENT DETAILS AFTER PAYMENT SUCCESS
          $payment_info = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('receipt_no' => $receipt_no, 'payment_mode'=>'CSC'), 'transaction_no, date, amount, id, description, status, exam_ids, exam_code, exam_period, receipt_no');
    
          if(count($payment_info) > 0 && $payment_info[0]['status'] == '0')
          {
            $member_exam_id = $payment_info[0]['exam_ids'];

            $this->db->join('iibfbcbf_batch_candidates bc', 'bc.candidate_id = me.candidate_id', 'INNER');
            $member_data = $this->master_model->getRecords('iibfbcbf_member_exam me', array('me.member_exam_id' => $member_exam_id, 'payment_mode'=>'CSC'), 'bc.candidate_id, bc.regnumber, bc.candidate_photo, bc.candidate_sign, bc.id_proof_file, bc.qualification_certificate_file, bc.re_attempt');

            if(count($member_data) > 0)
            {
              //START : UPDATE TRANSACTION NUMBER & PAYMENT STATUS IN iibfbcbf_member_exam
              $up_exam_data = array();
              $up_exam_data['ref_utr_no'] = $transaction_no;
              $up_exam_data['pay_status'] = '0';
              $this->master_model->updateRecord('iibfbcbf_member_exam',$up_exam_data, array('member_exam_id' => $member_exam_id));
              
              $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK', 'iibfbcbf_member_exam', $this->db->last_query(), $member_exam_id,'csc_payment_callback','The csc wallet payment is fail and transaction number and payment status is updated in member exam', json_encode($up_exam_data));

              $this->Iibf_bcbf_model->insert_common_log('Candidate : Candidate payment fail', 'iibfbcbf_batch_candidates', '', $member_data[0]['candidate_id'],'candidate_action','The payment fail for the exam ('.$payment_info[0]['exam_code'].' & '.$payment_info[0]['exam_period'].') by csc centre.', '');
            }
            
            // START : UPDATE TRANSACTION NUMBER IN INVOICE TABLE
            $exam_invoice = $this->master_model->getRecords('exam_invoice',array('pay_txn_id' => $payment_info[0]['id'], 'exam_code'=>$payment_info[0]['exam_code'], 'exam_period'=>$payment_info[0]['exam_period'], 'receipt_no'=>$payment_info[0]['receipt_no'], 'app_type'=>'BC'),'invoice_id');
          
            if(count($exam_invoice) > 0 && $payment_info[0]['amount'] > 0)
            {
              $up_invoice_data['transaction_no'] = $transaction_no;                
              $up_invoice_data['modified_on'] = date('Y-m-d H:i:s');            
              $this->master_model->updateRecord('exam_invoice',$up_invoice_data,array('invoice_id' => $exam_invoice[0]['invoice_id']));
              
              $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK', 'exam_invoice', $this->db->last_query(), $exam_invoice[0]['invoice_id'],'csc_payment_callback','The csc wallet payment is fail and transaction number is updated in exam invoice table for fail payment', json_encode($up_invoice_data));
            }// END : UPDATE TRANSACTION NUMBER IN INVOICE TABLE
          
            //xxx $this->send_mail_common('success', $payment_info[0]['member_regnumber'], $payment_info[0]['description'], $payment_info[0]['amount'], $payment_info[0]['transaction_no'], $payment_info[0]['date']);
            
            $this->session->set_flashdata('error','Your transactions is fail.');
            redirect(site_url('iibfbcbf/agency/apply_exam_csc_agency/acknowledgment/'. url_encode($receipt_no)));
          }
          else
          {
            $this->session->set_flashdata('error','Error occurred.');            
            redirect(site_url('iibfbcbf/agency/dashboard_agency'));
          }
        }
        else
        {
          $this->session->set_flashdata('error','Error occurred.');            
          redirect(site_url('iibfbcbf/agency/dashboard_agency'));
        }
      }
		}

		public function csc_transfail()
		{
      require_once $this->DOCUMENT_ROOT_PATH. "/BridgePG/PHP_BridgePG/BridgePGUtil.php";
			$bconn = new BridgePGUtil ();
			$bridge_message = $bconn->get_bridge_message();
			$params = explode('|', $bridge_message);
    	//breack with pipe operators
			$fine_params = array();
			foreach ($params as $param) 
      {
				$param = explode('=', $param);
				if (isset($param[0])) 
        {
			   	if(isset($param[1]))
					{
						$fine_params[$param[0]] = $param[1];
					}
				}
			}

      /* echo '<pre>';
      print_r($fine_params);
      echo '</pre>';
      echo 'in - fail'; exit; */

      $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK', '', '', '0', 'csc_payment_callback','The csc wallet payment response received successfully', json_encode($fine_params));

      $transaction_no = ''; //$fine_params['csc_txn'];
      $merchant_id = $fine_params['merchant_id'];
      $csc_id = $fine_params['csc_id'];
      $receipt_no = $fine_params['merchant_txn'];
      $txn_status = $fine_params['txn_status'];
      $merchant_txn_date_time = '';// $fine_params['merchant_txn_date_time'];
      $product_id = ''; //$fine_params['product_id'];
      $txn_amount = ''; //$fine_params['txn_amount'];
      $merchant_receipt_no = '';//$fine_params['merchant_receipt_no'];
      $txn_status_message = $fine_params['txn_status_message'];
      $status_message = '';//$fine_params['status_message'];

    	// Handle transaction fail case 
			$payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('receipt_no' => $receipt_no, 'payment_mode'=>'CSC'), 'id, status');
			if($payment_data[0]['status'] == '2')//IF payment status is PENDING
			{
        // START : UPDATE PAYMENT FAIL STATUS IN PAYMENT TRANSACTION TABLE AND INSERT LOG
        $update_data = array();
        $update_data['transaction_no'] = $transaction_no;
        $update_data['status'] = '0';
        $update_data['transaction_details'] = $txn_status_message. " - " .$status_message. " - " .$merchant_id. " - " .$csc_id. " - " .$product_id. " - " .$merchant_receipt_no. " - " .$merchant_txn_date_time. " - " .$txn_amount;
        $update_data['auth_code'] = '0300';
        $update_data['bankcode'] = 'csc';
        $update_data['paymode'] = 'wallet';
        $update_data['callback'] = 'B2B';						
        $update_data['description'] = 'Payment Fail By CSC';
        $update_data['approve_reject_date'] = date('Y-m-d H:i:s');
        $update_data['updated_on'] = date('Y-m-d H:i:s');
        $this->master_model->updateRecord('iibfbcbf_payment_transaction', $update_data, array('receipt_no' => $receipt_no, 'status' => '2', 'payment_mode'=>'CSC', 'id'=>$payment_data[0]['id']));
        
        $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK', 'iibfbcbf_payment_transaction', $this->db->last_query(), $payment_data[0]['id'],'csc_payment_callback','The csc wallet payment is fail', json_encode($update_data));
        // END : UPDATE PAYMENT FAIL STATUS IN PAYMENT TRANSACTION TABLE AND INSERT LOG
      
        // QUERY TO GET UPDATED PAYMENT DETAILS AFTER PAYMENT SUCCESS
        $payment_info = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('receipt_no' => $receipt_no, 'payment_mode'=>'CSC'), 'transaction_no, date, amount, id, description, status, exam_ids, exam_code, exam_period, receipt_no');
  
        if(count($payment_info) > 0 && $payment_info[0]['status'] == '0')
        {
          $member_exam_id = $payment_info[0]['exam_ids'];

          $this->db->join('iibfbcbf_batch_candidates bc', 'bc.candidate_id = me.candidate_id', 'INNER');
          $member_data = $this->master_model->getRecords('iibfbcbf_member_exam me', array('me.member_exam_id' => $member_exam_id, 'payment_mode'=>'CSC'), 'bc.candidate_id, bc.regnumber, bc.candidate_photo, bc.candidate_sign, bc.id_proof_file, bc.qualification_certificate_file, bc.re_attempt');

          if(count($member_data) > 0)
          {
            //START : UPDATE TRANSACTION NUMBER & PAYMENT STATUS IN iibfbcbf_member_exam
            $up_exam_data = array();
            $up_exam_data['ref_utr_no'] = $transaction_no;
            $up_exam_data['pay_status'] = '0';
            $this->master_model->updateRecord('iibfbcbf_member_exam',$up_exam_data, array('member_exam_id' => $member_exam_id));
            
            $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK', 'iibfbcbf_member_exam', $this->db->last_query(), $member_exam_id,'csc_payment_callback','The csc wallet payment is fail and transaction number and payment status is updated in member exam', json_encode($up_exam_data));

            $this->Iibf_bcbf_model->insert_common_log('Candidate : Candidate payment fail', 'iibfbcbf_batch_candidates', '', $member_data[0]['candidate_id'],'candidate_action','The payment fail for the exam ('.$payment_info[0]['exam_code'].' & '.$payment_info[0]['exam_period'].') by csc centre.', '');
          }
          
          // START : UPDATE TRANSACTION NUMBER IN INVOICE TABLE
          $exam_invoice = $this->master_model->getRecords('exam_invoice',array('pay_txn_id' => $payment_info[0]['id'], 'exam_code'=>$payment_info[0]['exam_code'], 'exam_period'=>$payment_info[0]['exam_period'], 'receipt_no'=>$payment_info[0]['receipt_no'], 'app_type'=>'BC'),'invoice_id');
        
          if(count($exam_invoice) > 0 && $payment_info[0]['amount'] > 0)
          {
            $up_invoice_data['transaction_no'] = $transaction_no;                
            $up_invoice_data['modified_on'] = date('Y-m-d H:i:s');            
            $this->master_model->updateRecord('exam_invoice',$up_invoice_data,array('invoice_id' => $exam_invoice[0]['invoice_id']));
            
            $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK', 'exam_invoice', $this->db->last_query(), $exam_invoice[0]['invoice_id'],'csc_payment_callback','The csc wallet payment is fail and transaction number is updated in exam invoice table for fail payment', json_encode($up_invoice_data));
          }// END : UPDATE TRANSACTION NUMBER IN INVOICE TABLE
        
          //xxx $this->send_mail_common('success', $payment_info[0]['member_regnumber'], $payment_info[0]['description'], $payment_info[0]['amount'], $payment_info[0]['transaction_no'], $payment_info[0]['date']);
          
          $this->session->set_flashdata('error','Your transactions is fail.');
          redirect(site_url('iibfbcbf/agency/apply_exam_csc_agency/acknowledgment/'. url_encode($receipt_no)));
        }
        else
        {
          $this->session->set_flashdata('error','Error occurred.');            
          redirect(site_url('iibfbcbf/agency/dashboard_agency'));
        }
			}
      else
      {
        $this->session->set_flashdata('error','Error occurred.');            
        redirect(site_url('iibfbcbf/agency/dashboard_agency'));
      }
		}

    public function acknowledgment($enc_receipt_no = NULL)
		{
      //START : FOR CSC WALLET PAYMENT
      $this->session->set_userdata('non_memberdata', ''); 
      $this->session->set_userdata('memtype', ''); 
      $this->session->set_userdata('csctype', ''); 
      $this->session->set_userdata('csc_id', ''); 
      //END : FOR CSC WALLET PAYMENT

      $this->db->join('iibfbcbf_agency_master am', 'am.agency_id=pt.agency_id', 'LEFT');
      $this->db->join('iibfbcbf_centre_master cm', 'cm.centre_id=pt.centre_id', 'LEFT');
      $this->db->join('iibfbcbf_member_exam me', 'me.member_exam_id=pt.exam_ids', 'LEFT');
      $this->db->join('iibfbcbf_batch_candidates bc', 'bc.candidate_id=me.candidate_id', 'LEFT');
      $this->db->join('exam_invoice ei', 'ei.receipt_no=pt.receipt_no AND ei.exam_code=pt.exam_code AND ei.exam_period =pt.exam_period AND ei.pay_txn_id = pt.id AND ei.app_type = "BC"', 'LEFT');
			$payment_info = $this->master_model->getRecords('iibfbcbf_payment_transaction pt', array('pt.receipt_no' => url_decode($enc_receipt_no), 'pt.payment_mode'=>'CSC'), 'am.agency_name, am.agency_code, cm.centre_name, cm.centre_username, me.exam_date, bc.regnumber, bc.training_id, bc.salutation, bc.first_name, bc.middle_name, bc.last_name, bc.mobile_no, bc.email_id, pt.transaction_no, pt.date, pt.amount, pt.status, ei.invoice_id, ei.invoice_image');/* , 'pt.status'=>'1' */

			if (count($payment_info) == 0)
			{
				redirect(site_url('iibfbcbf/agency/dashboard_agency'));
			}

      $endTime = date("Y-m-d H:i:s",strtotime("+5 minutes",strtotime($payment_info[0]['date'])));
      $current_time= date("Y-m-d H:i:s");
      if(strtotime($current_time)>strtotime($endTime))
      {
        redirect(site_url('iibfbcbf/agency/dashboard_agency'));
      }
			
			$data['payment_info'] = $payment_info;			
			$this->load->view('iibfbcbf/agency/acknowledgment_agency',$data); 
		}
    
    /******** START : TO GET THE VENUE DETAILS FOR SELECTED CENTRE CODE AND VALID FOR EXAM CODE 1039,1040 ********/
    function get_csc_venue_details_ajax()
    {
			if(isset($_POST) && count($_POST) > 0)
			{
        $centre_code = $this->security->xss_clean($this->input->post('centre_code'));
				$exam_code = $this->security->xss_clean($this->input->post('exam_code'));
				$exam_period = $this->security->xss_clean($this->input->post('exam_period'));
				$selected_venue_name = $this->security->xss_clean($this->input->post('selected_venue_name'));

        $this->db->group_by('venue_code');
        $this->db->where(" FIND_IN_SET('".$exam_code."',exam_codes) > 0 ");
        $venue_data = $this->master_model->getRecords('iibfbcbf_exam_venue_master', array('centre_code' => $centre_code, 'exam_period' => $exam_period, 'exam_date'=>'0000-00-00'), 'venue_master_id, exam_date, centre_code, session_time, session_capacity, venue_code, venue_name, venue_addr1, venue_pincode, centre_code, exam_period, exam_codes', array('venue_addr1'=>'ASC'));
                        
        $html = '	<select required class="form-control" name="venue_name" id="venue_name" onchange="get_csc_venue_date_details(this.value)">';
        if(count($venue_data) > 0 && in_array($exam_code, $this->csc_venue_master_eligible_exam_codes_arr)) //CSC VENUE MASTER IS ONLY APPLICABLE FOR EXAM CODE 1039 & 1040. HENCE THE CAPACITY CHECK IS APPLY FOR ONLY 1039 & 1040. 1039 & 1040 ARE CSC MODE EXAMS
				{
					$html .= '	<option value="">Select Venue Name</option>';
					foreach($venue_data as $venue_res)
					{
            $selected = '';
            if($selected_venue_name == $venue_res['venue_code']) { $selected = 'selected'; }

            $disp_name = $venue_res['venue_name'];
            if($venue_res['venue_addr1'] != "") { $disp_name .= ' ('.$venue_res['venue_addr1'].')'; }
						$html .= '	<option value="'.$venue_res['venue_code'].'" '.$selected.'>'.$disp_name.'</option>';
          }
        }
				else
				{
					$html .= '	<option value="">Select Venue Name</option>';
        }
				$html .= '</select>';
				
        $result['flag'] = "success";
        $result['response'] = $html;
      }
      else
      {      
        $result['flag'] = "error";
      }
      echo json_encode($result);
    }/******** END : TO GET THE VENUE DETAILS FOR SELECTED CENTRE CODE AND VALID FOR EXAM CODE 1039,1040 ********/

    /******** START : TO GET THE VENUE DATE DETAILS FOR SELECTED VENUE CODE AND VALID FOR EXAM CODE 1039,1040 ********/
    function get_csc_venue_date_details_ajax()
    {
			if(isset($_POST) && count($_POST) > 0)
			{
        $exam_code = $this->security->xss_clean($this->input->post('exam_code'));
        $selected_venue_date = $this->security->xss_clean($this->input->post('selected_venue_date'));
        
        $this->db->group_by('exam_date');
        $venue_data = $this->master_model->getRecords('iibfbcbf_exam_csc_exam_dates', array('exam_date >'=>date('Y-m-d', strtotime(' + 6 days'))), 'exam_date', array('exam_date'=>'ASC'));
        
        $html = '	<select required class="form-control" name="exam_date" id="exam_date" onchange="get_csc_venue_time_details(this.value)">';
        if(count($venue_data) > 0 && in_array($exam_code, $this->csc_venue_master_eligible_exam_codes_arr)) //VENUE MASTER IS ONLY APPLICABLE FOR EXAM CODE 1041 & 1042. HENCE THE CAPACITY CHECK IS APPLY FOR ONLY 1041 & 1042. 1041 & 1042 ARE HYBRID MODE EXAMS
				{
					$html .= '	<option value="">Select Exam Date</option>';
					foreach($venue_data as $venue_res)
					{
            $selected = '';
            if($selected_venue_date == $venue_res['exam_date']) { $selected = 'selected'; }
						$html .= '	<option value="'.$venue_res['exam_date'].'" '.$selected.'>'.$venue_res['exam_date'].'</option>';
          }
        }
				else
				{
					$html .= '	<option value="">Select Exam Date</option>';
        }
				$html .= '</select>';
				
        $result['flag'] = "success";
        $result['response'] = $html;
      }
      else
      {      
        $result['flag'] = "error";
      }
      echo json_encode($result);
    }/******** END : TO GET THE VENUE DATE DETAILS FOR SELECTED VENUE CODE AND VALID FOR EXAM CODE 1039,1040 ********/

    /******** START : TO GET THE VENUE TIME DETAILS FOR SELECTED VENUE CODE AND VALID FOR EXAM CODE 1039,1040 ********/
    function get_csc_venue_time_details_ajax()
    {
			if(isset($_POST) && count($_POST) > 0)
			{
        $exam_code = $this->security->xss_clean($this->input->post('exam_code'));
        $exam_date = $this->security->xss_clean($this->input->post('exam_date'));
				$selected_venue_time = $this->security->xss_clean($this->input->post('selected_venue_time'));
        
        $venue_data = $this->master_model->getRecords('iibfbcbf_exam_csc_exam_dates', array('exam_date' => $exam_date,'exam_date >'=>date('Y-m-d', strtotime(' + 6 days'))), 'session_time', array('session_time'=>'ASC'));
               
        $html = '	<select required class="form-control" name="exam_time" id="exam_time" onchange="get_csc_capacity_details(this.value)">';
        if(count($venue_data) > 0 && in_array($exam_code, $this->csc_venue_master_eligible_exam_codes_arr))//VENUE MASTER IS ONLY APPLICABLE FOR EXAM CODE 1041 & 1042. HENCE THE CAPACITY CHECK IS APPLY FOR ONLY 1041 & 1042. 1041 & 1042 ARE HYBRID MODE EXAMS
				{
					$html .= '	<option value="">Select Exam Time</option>';
					foreach($venue_data as $venue_res)
					{
            $selected = '';
            if($selected_venue_time == $venue_res['session_time']) { $selected = 'selected'; }
						$html .= '	<option value="'.$venue_res['session_time'].'" '.$selected.'>'.$venue_res['session_time'].'</option>';
          }
        }
				else
				{
					$html .= '	<option value="">Select Exam Time</option>';
        }
				$html .= '</select>';
				
        $result['flag'] = "success";
        $result['response'] = $html;        
      }
      else
      {      
        $result['flag'] = "error";
      }
      echo json_encode($result);
    }/******** END : TO GET THE VENUE TIME DETAILS FOR SELECTED VENUE CODE AND VALID FOR EXAM CODE 1039,1040 ********/

    /******** START : TO GET THE VENUE TIME DETAILS FOR SELECTED VENUE CODE AND VALID FOR EXAM CODE 1039,1040 ********/
    function get_csc_capacity_details_ajax()
    {
			if(isset($_POST) && count($_POST) > 0)
			{
        $centre_code = $this->security->xss_clean($this->input->post('centre_code'));
        $venue_code = $this->security->xss_clean($this->input->post('venue_code'));
        $exam_date = $this->security->xss_clean($this->input->post('exam_date'));
        $exam_time = $this->security->xss_clean($this->input->post('exam_time'));
				$exam_code = $this->security->xss_clean($this->input->post('exam_code'));
				$exam_period = $this->security->xss_clean($this->input->post('exam_period'));
				$chk_member_exam_id = $this->security->xss_clean($this->input->post('chk_member_exam_id'));
        
        if($centre_code != '' && $venue_code != '' && $exam_date != '' && $exam_time != '' && $exam_code != '' && $exam_period != '')
        {
          $result['flag'] = "success";
          $result['response'] = $this->Iibf_bcbf_model->get_capacity_csc($exam_code, $exam_period, $centre_code, $venue_code, $exam_date, $exam_time, $chk_member_exam_id);
        }
        else
        {
          $result['flag'] = "success";
          $result['response'] = '-';
        }
      }
      else
      {      
        $result['flag'] = "error";
      }
      echo json_encode($result);
    }/******** END : TO GET THE VENUE TIME DETAILS FOR SELECTED VENUE CODE AND VALID FOR EXAM CODE 1039,1040 ********/
  } ?>  