<?php 
  /********************************************************************************************************************
  ** Description: Controller for BCBF INDIVIDUAL Apply exam functionality
  ** Created BY: Sagar Matale On 01-02-2024
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Apply_exam_individual extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      
      $this->load->model('master_model');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
      $this->load->helper('file'); 
      $this->load->helper('getregnumber_helper'); 
      $this->load->model('billdesk_pg_model');
      $this->load->model('log_model');
      
      $this->buffer_days_after_training_end_date = '0';
      $this->buffer_days_after_candidate_add_date = '270';
      $this->utr_slip_path = 'uploads/iibfbcbf/utr_slip';
		}

    public function index() 
    { 
      $data['act_id'] = "";
      $data['sub_act_id'] = "";
      $data['page_title'] = 'IIBF - BCBF Apply Exam Individual';
      $data['error'] = '';

      if(isset($_POST) && count($_POST) > 0)
      {
        //SERVER SIDE VALIDATION
        $this->form_validation->set_rules('training_id','Training ID','trim|required|xss_clean',array('required' => 'Please enter the %s'));
        $this->form_validation->set_rules('iibf_bcbf_captcha','code','trim|required|xss_clean|callback_validation_check_captcha',array('required' => 'Please enter the %s'));	
                
        if($this->form_validation->run())
        {
          $data['error'] = 'Please enter valid Training ID';

          generate_captcha('IIBF_BCBF_APPLY_EXAM_INDIVIDUAL_CAPTCHA',6);
          $training_id = $this->input->post('training_id');          
          
          $this->db->limit(1);
          $this->db->where(" (cand.training_id = '".$training_id."' OR cand.regnumber = '".$training_id."') ");
          $this->db->join('iibfbcbf_agency_centre_batch btch', 'btch.batch_id = cand.batch_id','INNER');
          $getData = $this->master_model->getRecords('iibfbcbf_batch_candidates cand',array(),'cand.candidate_id, cand.agency_id, btch.batch_type', array('cand.candidate_id'=>'DESC'));//GET CANDIDATES DETAILS
          if(count($getData) > 0)
          {    
            $exam_code = $this->Iibf_bcbf_model->get_exam_code_individual($getData[0]['batch_type']);//GET EXAM CODE FOR SELECTED CANDIDATES
            $enc_exam_code = url_encode($exam_code);
            
            $active_exam_data = $this->Iibf_bcbf_model->get_exam_activation_details($enc_exam_code, $getData[0]['agency_id'],'individual');  //GET ACTIVE EXAM DETAILS
            if(count($active_exam_data) == 0)
            {
              $data['error'] = 'The exam is not activated';
            }
            else
            {
              //START : GET CANDIDATES DETAILS
              $enc_exam_period = url_encode($active_exam_data[0]['exam_period']);
              $enc_candidate_id = url_encode($getData[0]['candidate_id']);
              $resData = $this->Iibf_bcbf_model->get_exam_candidate_details($enc_exam_code, $enc_exam_period, $enc_candidate_id, '','individual');
              //END : GET CANDIDATES DETAILS

              if($resData['flag'] == 'error')
              {
                $data['error'] = $resData['response_msg'];
              }
              else
              {
                redirect(site_url('iibfbcbf/apply_exam_individual/apply_exam_candidate/'.$enc_exam_code.'/'.$enc_candidate_id));
              }
            }            
          }
        }			
      } 
      
      $this->load->helper('captcha');
      $data['captcha_img'] = generate_captcha('IIBF_BCBF_APPLY_EXAM_INDIVIDUAL_CAPTCHA',6); //iibfbcbf/iibf_bcbf_helper.php
      $this->load->view('iibfbcbf/apply_exam_individual', $data);
    }

    public function refresh_captcha() /******** START : REFRESH CAPTCHA ********/
    { 
      $this->load->helper('captcha');
      echo generate_captcha('IIBF_BCBF_APPLY_EXAM_INDIVIDUAL_CAPTCHA',6); //iibfbcbf/iibf_bcbf_helper.php
    }  /******** END : REFRESH CAPTCHA ********/
    
    /******** START : VALIDATION FUNCTION TO CHECK THE CORRECT CAPTCHA ********/
    public function validation_check_captcha($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {
      $return_val_ajax = 'false';
      if(isset($_POST) && $_POST['iibf_bcbf_captcha'] != "")
      {
        if($type == '1') { $captcha = $this->security->xss_clean($this->input->post('iibf_bcbf_captcha')); }
        else if($type == '0') { $captcha = $str; }
        
        $session_captcha = $this->session->userdata('IIBF_BCBF_APPLY_EXAM_INDIVIDUAL_CAPTCHA');
        
        if($captcha == $session_captcha)
        {
          $return_val_ajax = 'true';
        }
      }   
      
      if($type == '1') { echo $return_val_ajax; }
      else if($type == '0') 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['iibf_bcbf_captcha'] != "")
        {
          $this->form_validation->set_message('validation_check_captcha','Please enter the valid code');
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK THE CORRECT CAPTCHA ********/
     
    /******** START : INDIVIDUAL CANDIDATES EXAM APPLICATION FUNCTION ********/
    public function apply_exam_candidate($enc_exam_code='0', $enc_candidate_id='0')
    {      
      $data['enc_exam_code'] = $enc_exam_code;      
      $data['enc_candidate_id'] = $enc_candidate_id;  
      $data['error'] = '';    

      //START : GET ACTIVE EXAM DETAILS
      $agency_id = '0';
      $cand_data = $this->master_model->getRecords('iibfbcbf_batch_candidates cand',array('cand.candidate_id'=>url_decode($enc_candidate_id)), 'cand.candidate_id, cand.agency_id');
      if(count($cand_data) > 0)
      {
        $agency_id = $cand_data[0]['agency_id'];
      }
      
      $data['active_exam_data'] = $active_exam_data = $this->Iibf_bcbf_model->get_exam_activation_details($enc_exam_code, $agency_id, 'individual');       
      if(count($active_exam_data) == 0)
      {
        $this->session->set_flashdata('error','The exam is not activated');
        redirect(site_url('iibfbcbf/apply_exam_individual'));
      }//END : GET ACTIVE EXAM DETAILS

      $data['act_id'] = $data['sub_act_id'] = "";
      $data['page_title'] = 'IIBF - BCBF Apply Exam Individual - '.$active_exam_data[0]['description']; 
      
      //START : GET CANDIDATES DETAILS
      $enc_exam_period = url_encode($active_exam_data[0]['exam_period']);
      $resData = $this->Iibf_bcbf_model->get_exam_candidate_details($enc_exam_code, $enc_exam_period, $enc_candidate_id, '', 'individual');
      if($resData['flag'] == 'error')
      {
        $this->session->set_flashdata('error',$resData['response_msg']);
        redirect(site_url('iibfbcbf/apply_exam_individual'));
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

      $data['applied_exam_data'] = $applied_exam_data = $this->master_model->getRecords('iibfbcbf_member_exam',array('candidate_id'=>$candidate_data[0]['candidate_id'], 'exam_period'=>$active_exam_data[0]['exam_period'], 'pay_status'=>'2'),'',array('member_exam_id'=>'DESC'),'',1);

      $exam_code = url_decode($enc_exam_code);

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
      $get_exam_fee_data = $this->master_model->getRecords('iibfbcbf_exam_master em',array('em.exam_code'=>$exam_code, 'em.exam_delete'=>'0', 'fm.fee_delete'=>'0', 'fm.member_category'=>$candidate_data[0]['registration_type'], 'fm.group_code'=>$group_code, 'fm.exempt'=>'NE', 'fm.exam_code'=>$exam_code, 'fm.exam_period'=>$active_exam_data[0]['exam_period'], 'mm.misc_delete'=>'0', 'sm.subject_delete'=>'0'), 'fm.cs_tot, fm.igst_tot, sm.exam_date, sm.exam_time');

      if(count($get_exam_fee_data) == 0)
      {
        $this->session->set_flashdata('error','Error occurred while calculating the fees. Please try again later.');
        redirect(site_url('iibfbcbf/apply_exam_individual'));
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
          redirect(site_url('iibfbcbf/apply_exam_individual/apply_exam_candidate/'.$enc_exam_code.'/'.$enc_candidate_id));
        }
        
        $this->check_last_payment($candidate_data[0]['candidate_id']);
        
        $this->form_validation->set_rules('exam_centre', 'Exam centre', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('exam_medium', 'Exam medium', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        
        if($this->form_validation->run() == TRUE)  
        {
          if(count($get_exam_fee_data) > 0)
          {
            $up_candidiate = array();
            $up_candidiate['exam_code'] = $exam_code;
            $up_candidiate['ip_address'] = get_ip_address(); //general_helper.php 
            $up_candidiate['updated_on'] = date('Y-m-d H:i:s');
            $up_candidiate['updated_by'] = $candidate_data[0]['candidate_id'];
            $this->master_model->updateRecord('iibfbcbf_batch_candidates', $up_candidiate, array('candidate_id'=>$candidate_data[0]['candidate_id']));
            
            $add_exam_data = array();           
                      
            $add_exam_data['exam_date'] = $get_exam_fee_data[0]['exam_date']; 
            $add_exam_data['exam_centre_code'] = $exam_centre_code = trim($this->security->xss_clean($this->input->post('exam_centre')));
            $add_exam_data['exam_medium'] = trim($this->security->xss_clean($this->input->post('exam_medium')));
            $add_exam_data['exam_code'] = $exam_code;
            $add_exam_data['exam_fee'] = $exam_fees;
            $add_exam_data['batch_id'] = $candidate_data[0]['batch_id'];
            $add_exam_data['exam_period'] = $active_exam_data[0]['exam_period'];
            $add_exam_data['pay_status'] = '2';
            $add_exam_data['payment_mode'] = 'Individual';
            
            $posted_arr = json_encode($_POST);
            $member_exam_id = '0';
            if(count($applied_exam_data) == 0) //FOR ADD MODE
            {
              $add_exam_data['candidate_id'] = $candidate_data[0]['candidate_id'];
              $add_exam_data['batch_start_date'] = $candidate_data[0]['batch_start_date'];
              $add_exam_data['batch_end_date']	= $candidate_data[0]['batch_end_date'];              
              $add_exam_data['exam_time'] = $get_exam_fee_data[0]['exam_time'];
              $add_exam_data['fee_paid_flag'] = $fee_paid_flag;            
              $add_exam_data['ip_address'] = get_ip_address(); //general_helper.php 
              $add_exam_data['created_on'] = date('Y-m-d H:i:s');
              $add_exam_data['created_by'] = $candidate_data[0]['candidate_id'];
              
              $member_exam_id = $this->master_model->insertRecord('iibfbcbf_member_exam',$add_exam_data,true); 
            }
            else //FOR UPDATE MODE
            {
              $this->master_model->updateRecord('iibfbcbf_member_exam', $add_exam_data,  array('member_exam_id'=>$applied_exam_data[0]['member_exam_id']));
              $member_exam_id = $applied_exam_data[0]['member_exam_id'];
            }

            if($member_exam_id == '')
            {
              $this->session->set_flashdata('error','Error Occurred. Please try again..');
              redirect(site_url('iibfbcbf/apply_exam_individual'));
            } 
            
            $this->Iibf_bcbf_model->insert_common_log('Candidate : Candidate Applied for exam', 'iibfbcbf_batch_candidates', $this->db->last_query(), $candidate_data[0]['candidate_id'],'candidate_action','The candidate is applying for the exam ('.$exam_code.' & '.$active_exam_data[0]['exam_period'].') by individual.', $posted_arr);
            
            $add_payment_data = array();            
            $form_total_fees = $this->security->xss_clean($this->input->post('form_total_fees'));  
            $utr_no = '';  
            //$state_code = $candidate_data[0]['LoggedInCentreState']; //logged in centre state code
            $gstin_no = '-';
            
            if($form_total_fees == $exam_fees)
            {
              //START : INSERT PAYMENT TABLE ENTRY
              $pg_flag = 'BC';
              $add_payment_data['agency_id'] = $candidate_data[0]['agency_id'];
              $add_payment_data['centre_id'] = $candidate_data[0]['centre_id'];      
              $add_payment_data['exam_ids'] = $member_exam_id;
              $add_payment_data['amount'] = $exam_fees;
              $add_payment_data['gateway'] = '2';  // 1= NEFT / RTGS, 2=Billdesk
              $add_payment_data['UTR_no'] = $utr_no;
              $add_payment_data['agency_code'] = $candidate_data[0]['agency_code'];
              $add_payment_data['date'] = date('Y-m-d H:i:s');
              $add_payment_data['pay_count'] =  '1';
              $add_payment_data['exam_code'] =  $active_exam_data[0]['exam_code'];
              $add_payment_data['exam_period'] =  $active_exam_data[0]['exam_period'];
              $add_payment_data['payment_mode'] =  'Individual';
              $add_payment_data['pg_flag'] =  $pg_flag;
              $add_payment_data['status'] =  '2'; //pending
              $add_payment_data['payment_done_by_agency_id'] =  $candidate_data[0]['agency_id'];
              $add_payment_data['payment_done_by_centre_id'] =  $candidate_data[0]['centre_id'];
              $add_payment_data['ip_address'] = get_ip_address(); //general_helper.php 
              $add_payment_data['browser_details'] = $_SERVER['HTTP_USER_AGENT'];  
              $add_payment_data['server_ip'] = $_SERVER['SERVER_ADDR'];
              $add_payment_data['created_on'] = date('Y-m-d H:i:s');
              $add_payment_data['created_by'] = $candidate_data[0]['candidate_id'];
              $pt_id = $this->master_model->insertRecord('iibfbcbf_payment_transaction', $add_payment_data, true);
              //echo $this->db->last_query(); exit;

              if($pt_id > 0)
              {
                $this->master_model->updateRecord('iibfbcbf_member_exam ', array('pt_id'=>$pt_id, 'regnumber'=>$candidate_data[0]['regnumber']), array('member_exam_id' => $member_exam_id));//UPDATE PT ID IN iibfbcbf_member_exam 

                $posted_arr = json_encode($_POST);
                
                $this->Iibf_bcbf_model->insert_common_log('Candidate : Make payment', 'iibfbcbf_payment_transaction', $this->db->last_query(), $pt_id,'make_payment','The candidate initiated the payment for the exam ('.$active_exam_data[0]['exam_code'].' & '.$active_exam_data[0]['exam_period'].').', $posted_arr);
                
                $receipt_no = sbi_exam_order_id($pt_id); //getregnumber_helper.php
                // payment gateway custom fields -
                $custom_field = $receipt_no . "^iibfexam^iibfbcbfexam^" . $candidate_data[0]['candidate_id'];
                $custom_field_billdesk = $receipt_no . "-iibfexam-iibfbcbfexam-" . $candidate_data[0]['candidate_id'];                

                $this->master_model->updateRecord('iibfbcbf_payment_transaction', array('receipt_no'=>$receipt_no, 'pg_other_details' => $custom_field), array('id'=>$pt_id));
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

                  $this->Iibf_bcbf_model->insert_common_log('Candidate : Make payment', 'exam_invoice', $this->db->last_query(), $invoice_id,'make_payment','The candidate has successfully inserted record in exam invoice table by individual.', $posted_arr);

                }//END : INSERT EXAM INVOICE TABLE ENTRY

                //START : BILLDESK PAYMENT CODE
                if($exam_fees > 0)
                {
                  /*$allow_ip_arr = array('182.73.101.70', '115.124.115.69');
                  if(in_array(get_ip_address(), $allow_ip_arr)) { $exam_fees = 1; } */

                  $billdesk_res = $this->billdesk_pg_model->init_payment_request($receipt_no, $exam_fees, $invoice_id, $invoice_id, $candidate_data[0]['first_name'], 'iibfbcbf/apply_exam_individual/handle_billdesk_response/'.url_encode($pt_id), '', '', '', $custom_field_billdesk);                  				
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
                    redirect(site_url('iibfbcbf/apply_exam_individual'));
                  }
                }                
                //END : BILLDESK PAYMENT CODE
              }
              else
              {
                $this->session->set_flashdata('error','Error occurred while making the payment.');
                redirect(site_url('iibfbcbf/apply_exam_individual'));
              }
            }
            else
            {
              $this->Iibf_bcbf_model->insert_common_log('Candidate : Candidate Applied for exam', 'iibfbcbf_batch_candidates', $this->db->last_query(), $candidate_data[0]['candidate_id'],'candidate_action','Error occurred while candidate applying for the exam ('.$exam_code.' & '.$active_exam_data[0]['exam_period'].') by individual.', $posted_arr);

              $this->session->set_flashdata('error','There is some error in posted data');
              redirect(site_url('iibfbcbf/apply_exam_individual'));
            }
          }
          else
          {
            $this->session->set_flashdata('error','Your information is incorrect. Please contact to iibf admin.');
            redirect(site_url('iibfbcbf/apply_exam_individual'));
          }
        }
      }
      
      if($load_main_view_flag == '1') { $this->load->view('iibfbcbf/apply_exam_candidate_individual', $data); }
    }/******** END : INDIVIDUAL CANDIDATES EXAM APPLICATION FUNCTION ********/

    public function check_last_payment($candidate_id=0)
    {
      //checked for application in payment process and prevent user to apply exam on the same time
      $this->db->join('iibfbcbf_payment_transaction pt', 'pt.exam_ids = me.member_exam_id', 'INNER');
      $checkpayment = $this->master_model->getRecords('iibfbcbf_member_exam me',array('me.candidate_id'=>$candidate_id, 'me.payment_mode'=>'Individual', 'me.pay_status'=>'2'),'pt.date',array('me.member_exam_id'=>'DESC'));
      if(count($checkpayment) > 0)
      {
        $endTime = date("Y-m-d H:i:s",strtotime("+15 minutes",strtotime($checkpayment[0]['date'])));
        $current_time= date("Y-m-d H:i:s");
        if(strtotime($current_time)<=strtotime($endTime))
        {
          $this->session->set_flashdata('error','Wait your transaction is under process!.');
          redirect(site_url('iibfbcbf/apply_exam_individual'));
        }
			}
		}

    function handle_billdesk_response($enc_pt_id=0)
    {
      if (isset($_REQUEST['transaction_response'])) 
      {
        $response_encode = $_REQUEST['transaction_response'];
        $bd_response = $this->billdesk_pg_model->verify_res($response_encode);
        $responsedata = $bd_response['payload'];
        
        $MerchantOrderNo = $responsedata['orderid'];
        $transaction_no  = $responsedata['transactionid'];
        $merchIdVal = $responsedata['mercid'];
        $Bank_Code = $responsedata['bankid'];
        $auth_status = $responsedata['auth_status'];
        $encData = $_REQUEST['transaction_response'];
        
        $qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);			
        if ($auth_status == "0300" && $qry_api_response['auth_status'] == '0300')
        {
          $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('receipt_no' => $MerchantOrderNo, 'payment_mode'=>'Individual'), 'id, status, date');					
          
          if($payment_data[0]['status'] == '2')//IF payment status is PENDING
          {
            // START : UPDATE PAYMENT SUCCESS STATUS IN PAYMENT TRANSACTION TABLE AND INSERT LOG
            $update_data = array();
            $update_data['transaction_no'] = $transaction_no;
            $update_data['status'] = '1';
            $update_data['transaction_details'] = $responsedata['transaction_error_type']. " - " .$responsedata['transaction_error_desc'];
            $update_data['auth_code'] = '0300';
            $update_data['bankcode'] = $responsedata['bankid'];
            $update_data['paymode'] = $responsedata['txn_process_type'];
            $update_data['callback'] = 'B2B';						
            $update_data['description'] = 'Payment Success By Individual Candidate';
            $update_data['approve_reject_date'] = date('Y-m-d H:i:s');
            $update_data['updated_on'] = date('Y-m-d H:i:s');
            $this->master_model->updateRecord('iibfbcbf_payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo, 'status' => '2', 'payment_mode'=>'Individual'));
            
            $this->Iibf_bcbf_model->insert_common_log('BILLDESK RESPONSE : payment status updated as success', 'iibfbcbf_payment_transaction', $this->db->last_query(), $payment_data[0]['id'],'billdesk_action','The payment status successfully updated as success', json_encode($update_data));
            // END : UPDATE PAYMENT SUCCESS STATUS IN PAYMENT TRANSACTION TABLE AND INSERT LOG
          
            // QUERY TO GET UPDATED PAYMENT DETAILS AFTER PAYMENT SUCCESS
            $payment_info = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('receipt_no' => $MerchantOrderNo, 'payment_mode'=>'Individual'), 'transaction_no, date, amount, id, description, status, exam_ids, exam_code, exam_period, receipt_no');
      
            if(count($payment_info) > 0 && $payment_info[0]['status'] == '1')
            {
              $member_exam_id = $payment_info[0]['exam_ids'];

              //START : GET MEMBER REGNUMBER. IF IT IS EMPTY THEN GENERATE NEW REGNUMBER, RENAME THE IMAGES. ALSO UPDATE THE RE-ATTEMPT         
              $this->db->join('iibfbcbf_batch_candidates bc', 'bc.candidate_id = me.candidate_id', 'INNER');
              $member_data = $this->master_model->getRecords('iibfbcbf_member_exam me', array('me.member_exam_id' => $member_exam_id, 'payment_mode'=>'Individual'), 'bc.candidate_id, bc.regnumber, bc.candidate_photo, bc.candidate_sign, bc.id_proof_file, bc.qualification_certificate_file, bc.re_attempt');

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

                $this->Iibf_bcbf_model->insert_common_log('BILLDESK RESPONSE : generate new regnumber, rename the images and update re-attempt', 'iibfbcbf_batch_candidates', $this->db->last_query(), $member_data[0]['candidate_id'],'billdesk_action',$log_msg, json_encode($up_cand_data));

                //START : UPDATE TRANSACTION NUMBER & PAYMENT STATUS IN iibfbcbf_member_exam
                $up_exam_data = array();
                $up_exam_data['ref_utr_no'] = $transaction_no;
                $up_exam_data['pay_status'] = '1';
                if(isset($new_regnumber) && $new_regnumber != '') { $up_exam_data['regnumber'] = $new_regnumber; }
                $this->master_model->updateRecord('iibfbcbf_member_exam',$up_exam_data, array('member_exam_id' => $member_exam_id));
                
                $this->Iibf_bcbf_model->insert_common_log('BILLDESK RESPONSE : update transaction number and payment status in member exam', 'iibfbcbf_member_exam', $this->db->last_query(), $member_exam_id,'billdesk_action','The transaction number and payment status is successfully updated in member exam', json_encode($up_exam_data));

                $this->Iibf_bcbf_model->insert_common_log('Candidate : Candidate payment success', 'iibfbcbf_batch_candidates', '', $member_data[0]['candidate_id'],'candidate_action','The candidate has successfully applied for the exam ('.$payment_info[0]['exam_code'].' & '.$payment_info[0]['exam_period'].') by individual.', '');
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
                
                $this->Iibf_bcbf_model->insert_common_log('BILLDESK RESPONSE : update exam invoice number and image', 'exam_invoice', $this->db->last_query(), $exam_invoice[0]['invoice_id'],'billdesk_action','The exam invoice number and image is successfully updated in exam invoice table', json_encode($up_invoice_data));          
                
                $invoice_img_path = genarate_iibf_bcbf_exam_invoice($exam_invoice[0]['invoice_id']); // Use helpers/iibfbcbf/iibf_bcbf_helper.php  
              }// END : GENERATE INVOICE NUMBER AND INVOICE IMAGE.
              
              $this->Iibf_bcbf_model->generate_admit_card_common($enc_pt_id); //GENERATE ADMITCARD
            
              $this->Iibf_bcbf_model->send_transaction_details_email_sms($payment_info[0]['id']);

              $this->session->set_flashdata('success','Your transactions is successful.');
              redirect(site_url('iibfbcbf/apply_exam_individual/acknowledgment/'. url_encode($MerchantOrderNo)));
            }
            else
            {
              $this->session->set_flashdata('error','Error occurred.');            
              redirect(site_url('iibfbcbf/apply_exam_individual'));
            }
          }
          else
          {
            $this->session->set_flashdata('error','Error occurred.');            
            redirect(site_url('iibfbcbf/apply_exam_individual'));
          }
        }
        elseif ($auth_status == "0002") //BillDesk is waiting for Response from Bank
        {
          $this->session->set_flashdata('success','Your transactions is in process.');
          redirect(site_url('iibfbcbf/apply_exam_individual/acknowledgment/'. url_encode($MerchantOrderNo)));
        }
        else
        {
          $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('receipt_no' => $MerchantOrderNo, 'payment_mode'=>'Individual'), 'id, status, date');					
          
          if($payment_data[0]['status'] == '2')//IF payment status is PENDING
          {
            // START : UPDATE PAYMENT FAIL STATUS IN PAYMENT TRANSACTION TABLE AND INSERT LOG
            $update_data = array();
            $update_data['transaction_no'] = $transaction_no;
            $update_data['status'] = '0';
            $update_data['transaction_details'] = $responsedata['transaction_error_type']. " - " .$responsedata['transaction_error_desc'];
            $update_data['auth_code'] = '0300';
            $update_data['bankcode'] = $responsedata['bankid'];
            $update_data['paymode'] = $responsedata['txn_process_type'];
            $update_data['callback'] = 'B2B';						
            $update_data['description'] = 'Payment Fail By Individual Candidate';
            $update_data['approve_reject_date'] = date('Y-m-d H:i:s');
            $update_data['updated_on'] = date('Y-m-d H:i:s');
            $this->master_model->updateRecord('iibfbcbf_payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo, 'status' => '2', 'payment_mode'=>'Individual'));
            
            $this->Iibf_bcbf_model->insert_common_log('BILLDESK RESPONSE : payment status updated as fail', 'iibfbcbf_payment_transaction', $this->db->last_query(), $payment_data[0]['id'],'billdesk_action','The payment is fail', json_encode($update_data));
            // END : UPDATE PAYMENT FAIL STATUS IN PAYMENT TRANSACTION TABLE AND INSERT LOG
          
            // QUERY TO GET UPDATED PAYMENT DETAILS AFTER PAYMENT SUCCESS
            $payment_info = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('receipt_no' => $MerchantOrderNo, 'payment_mode'=>'Individual'), 'transaction_no, date, amount, id, description, status, exam_ids, exam_code, exam_period, receipt_no');
      
            if(count($payment_info) > 0 && $payment_info[0]['status'] == '0')
            {
              $member_exam_id = $payment_info[0]['exam_ids'];

              $this->db->join('iibfbcbf_batch_candidates bc', 'bc.candidate_id = me.candidate_id', 'INNER');
              $member_data = $this->master_model->getRecords('iibfbcbf_member_exam me', array('me.member_exam_id' => $member_exam_id, 'payment_mode'=>'Individual'), 'bc.candidate_id, bc.regnumber, bc.candidate_photo, bc.candidate_sign, bc.id_proof_file, bc.qualification_certificate_file, bc.re_attempt');

              if(count($member_data) > 0)
              {
                //START : UPDATE TRANSACTION NUMBER & PAYMENT STATUS IN iibfbcbf_member_exam
                $up_exam_data = array();
                $up_exam_data['ref_utr_no'] = $transaction_no;
                $up_exam_data['pay_status'] = '0';
                $this->master_model->updateRecord('iibfbcbf_member_exam',$up_exam_data, array('member_exam_id' => $member_exam_id));
                
                $this->Iibf_bcbf_model->insert_common_log('BILLDESK RESPONSE : update transaction number and payment status in member exam', 'iibfbcbf_member_exam', $this->db->last_query(), $member_exam_id,'billdesk_action','The payment fail transaction number and payment status is updated in member exam', json_encode($up_exam_data));

                $this->Iibf_bcbf_model->insert_common_log('Candidate : Candidate payment fail', 'iibfbcbf_batch_candidates', '', $member_data[0]['candidate_id'],'candidate_action','The payment fail for the exam ('.$payment_info[0]['exam_code'].' & '.$payment_info[0]['exam_period'].') by individual.', '');
              }
              
              // START : UPDATE TRANSACTION NUMBER IN INVOICE TABLE
              $exam_invoice = $this->master_model->getRecords('exam_invoice',array('pay_txn_id' => $payment_info[0]['id'], 'exam_code'=>$payment_info[0]['exam_code'], 'exam_period'=>$payment_info[0]['exam_period'], 'receipt_no'=>$payment_info[0]['receipt_no'], 'app_type'=>'BC'),'invoice_id');
            
              if(count($exam_invoice) > 0 && $payment_info[0]['amount'] > 0)
              {
                $up_invoice_data['transaction_no'] = $transaction_no;                
                $up_invoice_data['modified_on'] = date('Y-m-d H:i:s');            
                $this->master_model->updateRecord('exam_invoice',$up_invoice_data,array('invoice_id' => $exam_invoice[0]['invoice_id']));
                
                $this->Iibf_bcbf_model->insert_common_log('BILLDESK RESPONSE : update transaction number for fail payment in invoice', 'exam_invoice', $this->db->last_query(), $exam_invoice[0]['invoice_id'],'billdesk_action','The exam transaction number is updated in exam invoice table for fail payment', json_encode($up_invoice_data));
              }// END : UPDATE TRANSACTION NUMBER IN INVOICE TABLE
            
              //xxx $this->send_mail_common('success', $payment_info[0]['member_regnumber'], $payment_info[0]['description'], $payment_info[0]['amount'], $payment_info[0]['transaction_no'], $payment_info[0]['date']);
              
              $this->session->set_flashdata('error','Your transactions is fail.');
              redirect(site_url('iibfbcbf/apply_exam_individual/acknowledgment/'. url_encode($MerchantOrderNo)));
            }
            else
            {
              $this->session->set_flashdata('error','Error occurred.');            
              redirect(site_url('iibfbcbf/apply_exam_individual'));
            }
          }
          else
          {
            $this->session->set_flashdata('error','Error occurred.');            
            redirect(site_url('iibfbcbf/apply_exam_individual'));
          }
        }        
      }
      else 
      {
        if (isset($_REQUEST['status']) && $_REQUEST['status'] == '404' && $enc_pt_id != '0')        
        {
          $pt_id = url_decode($enc_pt_id);
          $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction pt',array('pt.id'=>$pt_id, 'pt.payment_mode'=>'Individual', 'pt.status !='=>'1'),'pt.id, pt.exam_ids');

          if(count($payment_data) > 0)
          {
            $up_payment = array();
            $up_payment['status'] = '0';
            $up_payment['description'] = 'Payment Fail By Individual Candidate as there is no response from payment gateway';
            if(isset($_REQUEST['message'])) { $up_payment['transaction_details'] = $_REQUEST['message']; }
            $up_payment['ip_address'] = get_ip_address(); //general_helper.php 
            $up_payment['approve_reject_date'] = date('Y-m-d H:i:s');
            $up_payment['updated_on'] = date('Y-m-d H:i:s');
            $this->master_model->updateRecord('iibfbcbf_payment_transaction', $up_payment, array('id'=>$pt_id));

            $member_exam_ids = $payment_data[0]['exam_ids'];
            $member_exam_id_arr = explode(",",$member_exam_ids);
            if(count($member_exam_id_arr) > 0)
            {
              foreach($member_exam_id_arr as $member_exam_res)
              {
                $up_mem_ex = array();
                $up_mem_ex['pay_status'] = '0';
                $up_mem_ex['description'] = 'Payment Fail By Individual Candidate as there is no response from payment gateway';
                $up_mem_ex['ip_address'] = get_ip_address(); //general_helper.php 
                $up_mem_ex['updated_on'] = date('Y-m-d H:i:s');
                $this->master_model->updateRecord('iibfbcbf_member_exam', $up_mem_ex, array('member_exam_id'=>$member_exam_res));

                $member_exam_data = $this->master_model->getRecords('iibfbcbf_member_exam',array('member_exam_id'=>$member_exam_res),'candidate_id, exam_code, exam_period');
                $this->Iibf_bcbf_model->insert_common_log('Candidate : Candidate payment fail', 'iibfbcbf_batch_candidates', $this->db->last_query(), $member_exam_data[0]['candidate_id'],'candidate_action','Error occurred while candidate applying for the exam ('.$member_exam_data[0]['exam_code'].' & '.$member_exam_data[0]['exam_period'].') by individual.', '');
              }
            }
          }
        }
        $this->session->set_flashdata('error','Error Occurred. Please try again.');
        redirect(site_url('iibfbcbf/apply_exam_individual'));
      }
    }

    public function acknowledgment($enc_receipt_no = NULL)
		{
      $this->db->join('iibfbcbf_agency_master am', 'am.agency_id=pt.agency_id', 'LEFT');
      $this->db->join('iibfbcbf_centre_master cm', 'cm.centre_id=pt.centre_id', 'LEFT');
      $this->db->join('iibfbcbf_member_exam me', 'me.member_exam_id=pt.exam_ids', 'LEFT');
      $this->db->join('iibfbcbf_batch_candidates bc', 'bc.candidate_id=me.candidate_id', 'LEFT');
      $this->db->join('exam_invoice ei', 'ei.receipt_no=pt.receipt_no AND ei.exam_code=pt.exam_code AND ei.exam_period =pt.exam_period AND ei.pay_txn_id = pt.id AND ei.app_type = "BC"', 'LEFT'); 
      $this->db->where_in('pt.status', array(0,1,2));
			$payment_info = $this->master_model->getRecords('iibfbcbf_payment_transaction pt', array('pt.receipt_no' => url_decode($enc_receipt_no), 'pt.payment_mode'=>'Individual'), 'am.agency_name, am.agency_code, cm.centre_name, cm.centre_username, me.exam_date, bc.regnumber, bc.training_id, bc.salutation, bc.first_name, bc.middle_name, bc.last_name, bc.mobile_no, bc.email_id, pt.transaction_no, pt.date, pt.amount, pt.status, ei.invoice_id, ei.invoice_image');
            
      if (count($payment_info) == 0)
			{
				redirect(base_url('iibfbcbf/apply_exam_individual'));
			}

      $endTime = date("Y-m-d H:i:s",strtotime("+5 minutes",strtotime($payment_info[0]['date'])));
      $current_time= date("Y-m-d H:i:s");
      if(strtotime($current_time)>strtotime($endTime))
      {
        redirect(site_url('iibfbcbf/apply_exam_individual'));
      }
			
			$data['payment_info'] = $payment_info;			
			$this->load->view('iibfbcbf/acknowledgment',$data); 
		}    
  } ?>  