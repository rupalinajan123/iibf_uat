<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Applyexam extends CI_Controller 
	{
		public function __construct()
		{
			parent::__construct();

      $this->load->model('master_model');
      $this->load->model('ncvet/Ncvet_model');
      $this->load->helper('ncvet/ncvet_helper'); 
      $this->load->model('billdesk_pg_model');

      $this->login_candidate_id = $this->session->userdata('NCVET_CANDIDATE_LOGIN_ID');
      // $this->login_user_type    = $this->session->userdata('NCVET_USER_TYPE');
      $this->Ncvet_model->check_candidate_session_all_pages(); // If admin session is not started then redirect to logout

     
    }
    public function examlist()
		{   
			$data['act_id'] = "Exam Registration ";
			$data['sub_act_id'] = "";
      $data['page_title'] = 'IIBF - NCVET Candidate Exam Registration';

      $today_date = date('Y-m-d');
      $data['candidate_id']     = $candidate_id     = $this->login_candidate_id;

      $this->session->unset_userdata('examinfo');
      $checkCandidateEligible = ncvet_checkCandidateEligible($candidate_id); //ncvet_helper

      $data['candidate_data'] = $checkCandidateEligible['candidate_data'];

      if($checkCandidateEligible['flag']!=1  && $checkCandidateEligible['errorType']=='kyc' ) {
        //$this->session->set_flashdata('error', "Please wait until KYC Process done for your documents");
           // redirect(base_url() . 'ncvet/candidate/dashboard_candidate/update_profile');
      }
      if($candidate_data[0]['registration_type']=='NM')
        $this->db->where('elg_mem_nm', 'Y');
      else if($candidate_data[0]['registration_type']=='O')
        $this->db->where('elg_mem_o', 'Y');
      $this->db->join('ncvet_subject_master', 'ncvet_subject_master.exam_code=ncvet_exam_master.exam_code');
      $this->db->join('ncvet_center_master', 'ncvet_center_master.exam_name=ncvet_exam_master.exam_code');
      $this->db->join('ncvet_exam_activation_master', 'ncvet_exam_activation_master.exam_code=ncvet_exam_master.exam_code');
      $this->db->join('ncvet_medium_master', 'ncvet_medium_master.exam_code=ncvet_exam_activation_master.exam_code AND ncvet_medium_master.exam_period=ncvet_exam_activation_master.exam_period');
      $this->db->join('ncvet_misc_master', 'ncvet_misc_master.exam_code=ncvet_exam_master.exam_code AND ncvet_misc_master.exam_period=ncvet_exam_activation_master.exam_period AND ncvet_misc_master.exam_period=ncvet_center_master.exam_period AND ncvet_subject_master.exam_period=ncvet_misc_master.exam_period');
      $this->db->where('medium_delete', '0');
      $this->db->where("ncvet_misc_master.misc_delete", '0');
      $this->db->where("'$today_date' BETWEEN ncvet_exam_activation_master.exam_from_date AND ncvet_exam_activation_master.exam_to_date");
      $this->db->where("ncvet_exam_activation_master.exam_activation_delete", "0");
      $this->db->group_by('ncvet_medium_master.exam_code');
      $exam_list = $this->master_model->getRecords('ncvet_exam_master');

      $data = array(
                
                'exam_list'      => $exam_list,
                
            );

      $this->load->view('ncvet/candidate/examlist', $data);
    }

    /******** START : Exam Details Page ********/
    
    public function examdetails()
    {

      $examcode = base64_decode($this->input->get('examcode'));
      $this->session->set_userdata('ncvet_curr_examcode',$examcode);
      
      $data['candidate_id']     = $candidate_id     = $this->login_candidate_id;

      $check = $this->common_validations($candidate_id,$examcode);
       
      $this->session->unset_userdata('examinfo');

      $exam_info = $this->master_model->getRecords('ncvet_exam_master', array(
            'exam_code' => $examcode,
        ));

        $data = array(
            
            'exam_info'      => $exam_info,
        );
      $this->load->view('ncvet/candidate/examdetails', $data);
       
    }
    /******** END : Exam Details Page ********/

    public function fetchExamDetails() {
      

    }
    public function examform() { 
      $examcode = $this->session->userdata('ncvet_curr_examcode');
      $data = array();

      $data['candidate_id']     = $candidate_id     = $this->login_candidate_id;
      
      $check = $this->common_validations($candidate_id,$examcode);

      $data['form_data'] = $form_data = $this->master_model->getRecords('ncvet_candidates bc', array('bc.candidate_id' => $candidate_id, 'bc.is_deleted' => '0'), "bc.*");   

      $exam_info = $this->master_model->getRecords('ncvet_exam_master', array(
            'exam_code' => $examcode,
        ));
        
        $data['exam_info']=$exam_info;
        $data['examcode']=$examcode;
        $data['exam_details']=$exam_details=$check['exam_details'];
        

        $center_details = $this->master_model->getRecords('ncvet_center_master', array(
              'exam_name' => $examcode,
              'exam_period' => $exam_details['exam_period'],
        ));
        $data['center_details'] = $center_details;

        $medium_details = $this->master_model->getRecords('ncvet_medium_master', array(
              'exam_code' => $examcode,
              'exam_period' => $exam_details['exam_period'],
        ));
        $data['medium_details'] = $medium_details;
       // 
        
         $eligible_details = $this->master_model->getRecords('ncvet_eligible_master', array(
                    'ncvet_eligible_master.exam_code' => $examcode,
                    'member_no'                 => $form_data[0]['regnumber'],
        ));
       // 
       $data['subject_details'] = array();
       foreach($eligible_details as $e) {
        $subject_details = $this->master_model->getRecords('ncvet_subject_master', array(
              'exam_code' => $examcode,
              'exam_period' => $exam_details['exam_period'],
              'subject_code' => $e['subject_code']
          ));
         
         
        $data['subject_details'][] = $subject_details[0];
       } 
      
      
        $data['eligible_details'] = $eligible_details;
      if(count($form_data) == 0) { redirect(site_url('ncvet/candidate/dashboard_candidate')); }

      if (isset($_POST['submitAll']) && count($_POST) > 0)
      {
        $this->form_validation->set_rules('scribe_flag', 'Scribe Services', 'required');
        $this->form_validation->set_rules('exam_center', 'Center Name', 'required|xss_clean'); 
        $this->form_validation->set_rules('exam_medium', 'Medium', 'required|xss_clean');
        
        
        if ($this->form_validation->run() == true) {
          $subject_arr        = array();
          $scribe_flag          = 'N';
          $elearning_flag_new = 'N';
          $el_subject = 'N';
          $today = date('Y-m-d h:i:s');

          $Sub_menue_disability = $disability_value = '';
          if (isset($_POST['scribe_flag'])) {
              $scribe_flag = $_POST['scribe_flag'];
          }
          $month = date('Y')."-".substr($exam_details['exam_month'],4);

          $selected_center_details = $this->master_model->getRecords('ncvet_center_master', array(
              'exam_name' => $examcode,
              'exam_period' => $exam_details['exam_period'],
              'center_code' => $_POST['exam_center'],
          ));

          $elearning_flag='N';

          $user_data = array(
            'candidate_id'      => $candidate_id,
            'exam_code'         => $examcode,
            'exam_name'         => $exam_info[0]['description'],
            'exam_month'        =>  date('F',strtotime($month))."-".substr($exam_details['exam_month'],0,-2),
            'exam_period'       => $exam_details['exam_period'],
            'exam_medium'       => $_POST['exam_medium'],
            'exam_center'       => $_POST['exam_center'],
            'exam_center_name'  => $selected_center_details[0]['center_name'],
            'exam_fee'          => $_POST['exam_fee'],
            'scribe_flag'       => $scribe_flag,
            'grp_code'          => $eligible_details[0]['app_category'],
            'registration_type' => $form_data[0]['registration_type'],
            'subject_details'   => $data['subject_details'],
            'elearning_flag'    => $elearning_flag,
          );
          $this->session->set_userdata('examinfo', $user_data);
          $this->Ncvet_model->insert_common_log('Candidate - Applyexam session', 'ncvet_member_exam', $this->db->last_query(), $candidate_id, 'candidate_action_applyexam session', 'Applyexam session', serialize($user_data));
          redirect(base_url() . 'ncvet/candidate/applyexam/preview');

        }
        else {
          $var_errors = str_replace("<p>", "<span>", $var_errors);
          $var_errors = str_replace("</p>", "</span><br />", $var_errors);
          $data['var_errors'] = $var_errors;
          
        }
      }

      
        $this->load->view('ncvet/candidate/examform', $data);
    }

    public function preview() {

      $data = array();

      $data['candidate_id']     = $candidate_id     = $this->login_candidate_id;

      $data['form_data'] = $form_data = $this->master_model->getRecords('ncvet_candidates bc', array('bc.candidate_id' => $candidate_id, 'bc.is_deleted' => '0'), "bc.*"); 
      $data['examinfo'] = $this->session->userdata('examinfo');  

      $medium_details = $this->master_model->getRecords('ncvet_medium_master', array(
              'exam_code' => $data['examinfo']['exam_code'],
              'exam_period' => $data['examinfo']['exam_period'],
        ));
        $data['medium_details'] = $medium_details;
      
      $this->load->view('ncvet/candidate/exampreview', $data);

    }
    public function process_application() {
      $data = array();

      $data['candidate_id']     = $candidate_id     = $this->login_candidate_id;

      
      if ($this->session->userdata('examinfo') == '') {
            redirect(base_url() . 'ncvet/candidate/applyexam/examlist/');
        }

      $data['examinfo'] = $examinfo= $this->session->userdata('examinfo');  
      $examcode = $data['examinfo']['exam_code'];

      $check = $this->common_validations($candidate_id,$examcode);

      $data['form_data'] = $form_data = $this->master_model->getRecords('ncvet_candidates bc', array('bc.candidate_id' => $examinfo['candidate_id'], 'bc.is_deleted' => '0'), "bc.*"); 
      $regnumber = $form_data[0]['regnumber'];
      if (isset($_POST['process_application'])) {
        
        $amount = ncvet_getexamfee($examinfo['exam_center'], $examinfo['exam_period'], $examinfo['exam_code'], $examinfo['grp_code'], $form_data[0]['registration_type'], $examinfo['elearning_flag']);

        $insert_array = array(
                'regnumber'              => $regnumber,
                'exam_code'              => $examcode,
                'exam_mode'              => 'ON',
                'exam_medium'            => $examinfo['exam_medium'],
                'exam_period'            => $examinfo['exam_period'],
                'exam_center_code'       => $examinfo['exam_center'],
                'exam_fee'               => $amount,
                'elected_sub_code'       => 0,
                'place_of_work'          => '',
                'state_place_of_work'    => '',
                'pin_code_place_of_work' => '',
                'scribe_flag'            => $examinfo['scribe_flag'],
                'scribe_flag_PwBD'       => '',
                'disability'             => '',
                'sub_disability'         => '',
                'created_on'             => date('y-m-d H:i:s'),
                'elearning_flag'         => $examinfo['elearning_flag'],
                'sub_el_count'           => 0,
                'pay_status'             => 0
            );
            if ($insert_id = $this->master_model->insertRecord('ncvet_member_exam', $insert_array, true)) {

                /* User Log Activities  */
                $this->Ncvet_model->insert_common_log('Candidate - Member exam apply details', 'ncvet_member_exam', $this->db->last_query(), $candidate_id, 'candidate_action_applyexam insertion', 'Applyexam insert_array', serialize($insert_array));
                /* Close User Log Actitives */

                // echo $this->session->userdata['examinfo']['fee'];
                $this->session->userdata['examinfo']['mem_exam_id'] = $insert_id;
                
                // admitcard data insertion start
                $institute_id     = '';
                $institution_name = '';

                if ($form_data[0]['gender'] == '1') {
                    $gender = 'M';
                } else {
                    $gender = 'F';
                }
                // ########prepare user name########
                $username         = $form_data[0]['first_name'] . ' ' . $form_data[0]['middle_name'] . ' ' . $form_data[0]['last_name'];
                $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);

                // ###########get State##########
                $states = $this->master_model->getRecords('state_master', array(
                    'state_code'   => $form_data[0]['state'],
                    'state_delete' => '0',
                ));
                $state_name = '';
                if (count($states) > 0) {
                    $state_name = $states[0]['state_name'];
                }
                // ##############Examination Mode###########
                $mode = 'Online';
                $getcenter = $this->master_model->getRecords('ncvet_center_master', array(
                    'exam_name'     => $examcode,
                    'center_code'   => $this->session->userdata['examinfo']['exam_center'],
                    'exam_period'   => $this->session->userdata['examinfo']['exam_period'],
                    'center_delete' => '0',
                ));

                $sub_el_flg = 'N';

                if (!empty($this->session->userdata['examinfo']['subject_details'])) {
                  $subject_details = $this->session->userdata['examinfo']['subject_details'];
                  foreach($subject_details as $subject) {

                    $admitcard_insert_array = array(
                            'mem_exam_id'      => $this->session->userdata['examinfo']['mem_exam_id'],
                            'center_code'      => $getcenter[0]['center_code'],
                            'center_name'      => $getcenter[0]['center_name'],
                            'mem_type'         => $form_data[0]['registration_type'],
                            'mem_mem_no'       => $regnumber,
                            'g_1'              => $gender,
                            'mam_nam_1'        => $userfinalstrname,
                            'mem_adr_1'        => $form_data[0]['address1'],
                            'mem_adr_2'        => $form_data[0]['address2'],
                            'mem_adr_3'        => $form_data[0]['address3'],
                            'mem_adr_4'        => '',
                            'mem_adr_5'        => $form_data[0]['district'],
                            'mem_adr_6'        => $form_data[0]['city'],
                            'mem_pin_cd'       => $form_data[0]['pincode'],
                            'state'            => $state_name,
                            'exm_cd'           => $examcode,
                            'exm_prd'          => $this->session->userdata['examinfo']['exam_period'],
                            'sub_cd '          => $subject['subject_code'],
                            'sub_dsc'          => $subject['subject_description'],
                            'sub_el_flg'       => $sub_el_flg,
                            'm_1'              => $this->session->userdata['examinfo']['exam_medium'],
                            'inscd'            => $institute_id,
                            'insname'          => $institution_name,
                            'exam_date'        => $subject['exam_date'],
                            'time'             => $subject['exam_time'],
                            'mode'             => $mode,
                            'scribe_flag'      => $this->session->userdata['examinfo']['scribe_flag'],
                            'scribe_flag_PwBD' => '',                        
                            'vendor_code'      => $getcenter[0]['vendor_code'],
                            'remark'           => 2,
                            'created_on'       => date('Y-m-d H:i:s'),
                        );
                        $inser_id    = $this->master_model->insertRecord('ncvet_admit_card_details', $admitcard_insert_array);
                        

                        /* User Log Activities  */
                    $this->Ncvet_model->insert_common_log('Candidate - Insert Admitcard details', 'ncvet_admit_card_details', $this->db->last_query(), $candidate_id, 'candidate_action_applyexam insertion', 'Applyexam insert_array', serialize($admitcard_insert_array));
                    /* Close User Log Actitives */
                  }
                }  
             
                if($amount>0) {
                  redirect(base_url() . 'ncvet/candidate/applyexam/make_payment/');
                }
                else {
                  $mem_exam_id = $this->session->userdata['examinfo']['mem_exam_id'];
                  $admitcard_pdf = $this->generate_admitcard($mem_exam_id,$examcode,$regnumber);
                  $this->sendmail($this->session->userdata['examinfo']['mem_exam_id'],$examcode,$regnumber,'success',$admitcard_pdf);
                  redirect(base_url() . 'ncvet/candidate/applyexam/acknowledgement/'.base64_encode($mem_exam_id));
                }
                  
            }
      }
      
    }
    public function make_payment() {
      $data = array();
      $cgst_rate           = $sgst_rate           = $igst_rate           = $tax_type           = '';
      $cgst_amt            = $sgst_amt            = $igst_amt            = '';
      $cs_total            = $igst_total            = '';
      $total_el_amount     = 0;
      $el_subject_cnt      = 0;
      $total_elearning_amt = 0;
      ## New elarning columns code
      $total_el_base_amount = 0;
      $total_el_gst_amount  = 0;
      $total_el_cgst_amount = 0;
      $total_el_sgst_amount = 0;
      $total_el_igst_amount = 0;
      $getstate             = $getcenter             = $getfees             = array();

      $data['candidate_id']     = $candidate_id     = $this->login_candidate_id;

      $data['form_data'] = $form_data = $this->master_model->getRecords('ncvet_candidates bc', array('bc.candidate_id' => $candidate_id, 'bc.is_deleted' => '0'), "bc.*"); 
      $regnumber = $form_data[0]['regnumber'];
      $data['examinfo'] = $examinfo = $this->session->userdata('examinfo');  

      $examcode = $data['examinfo']['exam_code'];

      $check = $this->common_validations($candidate_id,$examcode);

      if (isset($_POST['processPayment']) && $_POST['processPayment']) {
         $checkpayment = $this->master_model->getRecords('ncvet_payment_transaction', array('pay_type' => 2, 'ref_id' => $this->session->userdata['examinfo']['mem_exam_id']));
            
            if (count($checkpayment) > 0) {
                $this->session->set_flashdata('error', 'Wait your transaction is under process!..');
                redirect(base_url() . 'ncvet/candidate/applyexam/examlist/');
            } 
            $pg_name = 'billdesk';
            //checked for application in payment process and prevent user to apply exam on the same time
            $checkpayment = $this->master_model->getRecords('ncvet_payment_transaction', array('member_regnumber' => $regnumber, 'status' => '2', 'pay_type' => '2'), '', array('id' => 'DESC'));
            if (count($checkpayment) > 0) {
                $endTime      = date("Y-m-d H:i:s", strtotime("+120 minutes", strtotime($checkpayment[0]['date'])));
                $current_time = date("Y-m-d H:i:s");
                if (strtotime($current_time) <= strtotime($endTime)) {
                    $this->session->set_flashdata('error', 'Your transactions is in process, please try after 2 hrs after your initial transaction.');
                    redirect(base_url() . 'ncvet/candidate/applyexam/examlist/');
                }
            }

            $el_subject_cnt = 0;
            $amount = ncvet_getexamfee($examinfo['exam_center'], $examinfo['exam_period'], $examinfo['exam_code'], $examinfo['grp_code'], $form_data[0]['registration_type'], $examinfo['elearning_flag']);

            if ($amount == 0 || $amount == '') {
                $this->session->set_flashdata('error', 'Fee can not be zero(0) or Blank!!');
                redirect(base_url() . 'ncvet/candidate/applyexam/examlist/');
            }
            $yearmonth = $this->master_model->getRecords('ncvet_misc_master', array(
                'exam_code'   => $examcode,
                'exam_period' => $examinfo['exam_period'],
            ), 'exam_month');

            $ref4 = ($examcode) . $yearmonth[0]['exam_month'];
            // Create transaction
            $insert_data = array(
                'member_regnumber' => $regnumber,
                'amount'           => $amount,
                'gateway'          => "billdesk",
                'date'             => date('Y-m-d H:i:s'),
                'pay_type'         => '2',
                'ref_id'           => $examinfo['mem_exam_id'],
                'description'      => $examinfo['exam_name'],
                'status'           => '2',
                'exam_code'        => $examcode,
                'pg_flag'          => "NCVET_EXAM",
                'gateway'         => 'billdesk',
                'payment_mode'    => 'Individual'
            );
            $pt_id           = $this->master_model->insertRecord('ncvet_payment_transaction', $insert_data, true);
            $MerchantOrderNo = generate_ncvet_exam_order_id($pt_id);
            // payment gateway custom fields -
            $custom_field = $MerchantOrderNo . "^iibfncvetexam^" . $regnumber . "^" . $ref4;

            $custom_field_billdesk = $MerchantOrderNo . "-iibfncvetexam-" . $regnumber . "-" . $ref4;
            //  echo $custom_field_billdesk;exit;
            // update receipt no. in payment transaction -
            $update_data = array(
                'receipt_no'       => $MerchantOrderNo,
                'pg_other_details' => $custom_field,
            );

            $this->master_model->updateRecord('ncvet_payment_transaction', $update_data, array(
                'id' => $pt_id,
            ));

            $getcenter = $this->master_model->getRecords('ncvet_center_master', array(
                'exam_name'     => $examcode,
                'center_code'   => $this->session->userdata['examinfo']['exam_center'],
                'exam_period'   => $this->session->userdata['examinfo']['exam_period'],
                'center_delete' => '0',
            ));
            if (count($getcenter) > 0) {
                // get state code,state name,state number.
                $getstate = $this->master_model->getRecords('state_master', array(
                    'state_code'   => $getcenter[0]['state_code'],
                    'state_delete' => '0',
                ));
                
            }
            if ($getcenter[0]['state_code'] == 'MAH') {
              $tax_type = 'Intra';
            }
            else {
              $tax_type = 'Inter';
            }
            $igst_total      = $amount;
            $gst_no = '0';

            $invoice_insert_array = array(
                'pay_txn_id'           => $pt_id,
                'receipt_no'           => $MerchantOrderNo,
                'exam_code'            => $examcode,
                'center_code'          => $getcenter[0]['center_code'],
                'center_name'          => $getcenter[0]['center_name'],
                'state_of_center'      => $getcenter[0]['state_code'],
                'member_no'            => $regnumber,
                'app_type'             => 'O',
                'exam_period'          => $this->session->userdata['examinfo']['exam_period'],
                'service_code'         => $this->config->item('ncvet_exam_service_code'),
                'qty'                  => '1',
                'state_code'           => $getstate[0]['state_no'],
                'state_name'           => $getstate[0]['state_name'],
                'tax_type'             => $tax_type,
                'fee_amt'              => $amount,
                'total_el_amount'      => $total_el_amount,
                'total_el_base_amount' => $total_el_base_amount,
                'total_el_gst_amount'  => $total_el_gst_amount,
                'cgst_rate'            => $cgst_rate,
                'cgst_amt'             => $cgst_amt,
                'sgst_rate'            => $sgst_rate,
                'sgst_amt'             => $sgst_amt,
                'igst_rate'            => $igst_rate,
                'igst_amt'             => $igst_amt,
                'cs_total'             => $cs_total,
                'igst_total'           => $igst_total,
                'exempt'               => $getstate[0]['exempt'],
                'created_on'           => date('Y-m-d H:i:s'),
            );
            $insert_id    = $this->master_model->insertRecord('ncvet_exam_invoice', $invoice_insert_array, true);
            /* User Log Activities  */
                $this->Ncvet_model->insert_common_log('Candidate - Insert invoice details', 'ncvet_exam_invoice', $this->db->last_query(), $candidate_id, 'candidate_action_applyexam insertion', 'Applyexam insert_array', serialize($invoice_insert_array));
                /* Close User Log Actitives */

            $marchant_id = $MerchantOrderNo;
                
              $ref_id      = $this->session->userdata['examinfo']['mem_exam_id'];

              $payment_raw = $this->master_model->getRecordCount('ncvet_payment_transaction', array('receipt_no' => $marchant_id, 'exam_code' => $examcode, 'member_regnumber' => $regnumber));

              $exam_invoice_raw = $this->master_model->getRecordCount('ncvet_exam_invoice', array('receipt_no' => $marchant_id, 'exam_code' => $examcode, 'member_no' => $regnumber));

              $admit_card_raw = $this->master_model->getRecordCount('ncvet_admit_card_details', array('mem_exam_id' => $ref_id, 'exm_cd' => $examcode, 'mem_mem_no' => $regnumber));

              if ($payment_raw == 0 || $exam_invoice_raw == 0 || $admit_card_raw == 0) {
                  $this->session->set_flashdata('error', 'Something went wrong!!');
                  redirect(base_url() . 'ncvet/candidate/applyexam/examlist/');
              }

              // set cookie for Apply Exam
              ncvetexam_set_cookie($ref_id);
              
              $callback_link = '/ncvet/candidate/applyexam/handle_billdesk_response';


              $billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $regnumber, $regnumber, '', $callback_link, '', '', '', $custom_field_billdesk);
              //  echo'<pre>';print_r($billdesk_res);exit;
              if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE') {
                  $data['bdorderid']      = $billdesk_res['bdorderid'];
                  $data['token']          = $billdesk_res['token'];
                  $data['responseXHRUrl'] = $billdesk_res['responseXHRUrl'];
                  $data['returnUrl']      = $billdesk_res['returnUrl'];
                  $this->load->view('pg_billdesk/pg_billdesk_form', $data);
              } else {
                  $this->session->set_flashdata('error', 'Transaction failed...!');
                  redirect(base_url() . '/ncvet/candidate/applyexam/fail/' . base64_encode($MerchantOrderNo));
              }
      }

      else {
            $data['show_billdesk_option_flag'] = 1;
            $this->load->view('pg_sbi/make_payment_page', $data);
        }
    }
    public function handle_billdesk_response() {
      if (isset($_REQUEST['transaction_response'])) {
            $response_encode        = $_REQUEST['transaction_response'];
            $bd_response            = $this->billdesk_pg_model->verify_res($response_encode);
            $date_for_log = date('Y-m-d H:i:s');
            $responsedata           = $bd_response['payload'];
            $attachpath             = $invoiceNumber             = '';
            $MerchantOrderNo        = $responsedata['orderid'];
            $transaction_no         = $responsedata['transactionid'];
            $transaction_error_type = $responsedata['transaction_error_type'];
            $transaction_error_desc = $responsedata['transaction_error_desc'];
            $bankid                 = $responsedata['bankid'];
            $txn_process_type       = $responsedata['txn_process_type'];
            $merchIdVal             = $responsedata['mercid'];
            $Bank_Code              = $responsedata['bankid'];
            $encData                = $_REQUEST['transaction_response'];
            $auth_status            = $responsedata['auth_status'];
            $this->db->limit(1);
            $this->db->order_by('id', 'DESC');
            $get_user_regnum = $this->master_model->getRecords('ncvet_payment_transaction', array('receipt_no' => $MerchantOrderNo));

            $qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);

            $this->db->join('state_master', 'state_master.state_code=ncvet_candidates.state');
            $result = $this->master_model->getRecords('ncvet_candidates', array(
                'regnumber' => $get_user_regnum[0]['member_regnumber'],
            ), 'candidate_id,first_name,middle_name,last_name,address1,address2,address3,district,city,email_id,mobile_no,pincode,state_master.state_name');

            $candidate_id = $result[0]['candidate_id'];
            $regnumber = $result[0]['regnumber'];

            if ($auth_status == '0300' && $qry_api_response['auth_status'] == '0300') {
              if ($get_user_regnum[0]['status'] == 2) {
                    
                    $this->db->join('ncvet_center_master', 'ncvet_center_master.center_code=ncvet_member_exam.exam_center_code AND ncvet_center_master.exam_name=ncvet_member_exam.exam_code AND ncvet_center_master.exam_period=ncvet_member_exam.exam_period');
                    $this->db->join('ncvet_exam_master', 'ncvet_exam_master.exam_code=ncvet_member_exam.exam_code');
                    $this->db->join('ncvet_misc_master', 'ncvet_misc_master.exam_code=ncvet_member_exam.exam_code AND ncvet_misc_master.exam_period=ncvet_member_exam.exam_period');
                    $this->db->join('ncvet_exam_activation_master', 'ncvet_exam_activation_master.exam_code=ncvet_member_exam.exam_code AND ncvet_exam_activation_master.exam_period=ncvet_member_exam.exam_period');
                    $exam_info = $this->master_model->getRecords('ncvet_member_exam', array(
                        'regnumber'      => $get_user_regnum[0]['member_regnumber'],
                        'ncvet_member_exam.id' => $get_user_regnum[0]['ref_id'],
                    ), 'ncvet_member_exam.exam_code,ncvet_member_exam.exam_mode,ncvet_member_exam.exam_medium,ncvet_member_exam.exam_period,ncvet_center_master.center_name,ncvet_member_exam.exam_center_code,ncvet_exam_master.description,ncvet_misc_master.exam_month,ncvet_member_exam.state_place_of_work,ncvet_member_exam.place_of_work,ncvet_member_exam.pin_code_place_of_work,ncvet_member_exam.elected_sub_code');

                    $exam_admicard_details = $this->master_model->getRecords('ncvet_admit_card_details', array(
                            'mem_exam_id' => $get_user_regnum[0]['ref_id'],
                        ));
                    if (count($exam_admicard_details) > 0 ) {
                      // ######## payment Transaction ############

                        $update_data  = array('transaction_no' => $transaction_no, 'status' => 1, 'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc, 'auth_code' => '0300', 'bankcode' => $bankid, 'paymode' => $txn_process_type, 'callback' => 'B2B');
                        $update_query = $this->master_model->updateRecord('ncvet_payment_transaction', $update_data, array(
                            'receipt_no' => $MerchantOrderNo,
                            'status'     => 2,
                        ));
                        $this->Ncvet_model->insert_common_log('Candidate - Applyexam update payment info', 'ncvet_payment_transaction', $this->db->last_query(), $candidate_id, 'candidate_action_applyexam', 'Applyexam update payment info', serialize($update_data));

                        

                        $getinvoice_number = $this->master_model->getRecords('ncvet_exam_invoice', array(
                            'receipt_no' => $MerchantOrderNo,
                            'pay_txn_id' => $get_user_regnum[0]['id'],
                        ));

                        $this->Ncvet_model->insert_common_log('Candidate - Applyexam get invoice info', 'ncvet_exam_invoice', $this->db->last_query(), $candidate_id, 'candidate_action_applyexam', 'Applyexam get invoice info', serialize($get_user_regnum));
                        if (count($getinvoice_number) > 0) {
                            $invoiceNumber = '';
                            $invoiceNumber       = generate_ncvet_reg_receipt_no($getinvoice_number[0]['invoice_id']);
                            $invoiceNumber = $this->config->item('ncvet_exam_invoice_no_prefix') . $invoiceNumber; 
                            $update_data_invoice = array(
                                'invoice_no'      => $invoiceNumber,
                                'transaction_no'  => $transaction_no,
                                'date_of_invoice' => date('Y-m-d H:i:s'),
                                'modified_on'     => date('Y-m-d H:i:s'),
                            );
                            $this->db->where('pay_txn_id', $get_user_regnum[0]['id']);
                            $this->master_model->updateRecord('ncvet_exam_invoice', $update_data_invoice, array(
                                'receipt_no' => $MerchantOrderNo,
                            ));
                            $this->Ncvet_model->insert_common_log('Candidate - Applyexam update invoice info', 'ncvet_exam_invoice', $this->db->last_query(), $candidate_id, 'candidate_action_applyexam', 'Applyexam update invoice info', serialize($update_data_invoice));
                            $invoice_path = genarate_ncvet_exam_invoice($getinvoice_number[0]['invoice_id']);

                            $admitcard_pdf = $this->generate_admitcard($get_user_regnum[0]['ref_id'],$get_user_regnum[0]['exam_code'],$regnumber);
                            $this->sendmail($get_user_regnum[0]['ref_id'],$get_user_regnum[0]['exam_code'],$regnumber,'success',$admitcard_pdf,$invoice_path);
                            redirect(base_url() . 'ncvet/candidate/applyexam/acknowledgement/'.base64_encode($get_user_regnum[0]['ref_id']));

                        }

                    }
              }
              else {
                $this->Ncvet_model->insert_common_log('Candidate - Applyexam payment update problem', 'ncvet_payment_transaction', '', $candidate_id, 'candidate_action_applyexam', 'Applyexam payment update problem', serialize($get_user_regnum));
              }
            }
            elseif ($auth_status == '0002') {

                if ($get_user_regnum[0]['status'] != 0 && $get_user_regnum[0]['status'] == 2) {

                  $update_data = array('transaction_no' => $transaction_no, 'status' => '2', 'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc, 'auth_code' => '0002', 'bankcode' => $bankid, 'paymode' => $txn_process_type, 'callback' => 'B2B');
                  $this->master_model->updateRecord('ncvet_payment_transaction', $update_data, array(
                      'receipt_no' => $MerchantOrderNo,
                  ));

                  $this->Ncvet_model->insert_common_log('Candidate - Applyexam update payment info', 'ncvet_payment_transaction', $this->db->last_query(), $candidate_id, 'candidate_action_applyexam', 'Applyexam update payment info', serialize($update_data));

                  //send mail to candidate regarding pending payment
                }
                redirect(base_url() . '/ncvet/candidate/applyexam/fail/' . base64_encode($MerchantOrderNo));
            }
            else {
              if ($get_user_regnum[0]['status'] != 0 && $get_user_regnum[0]['status'] == 2) {

                $update_data = array('transaction_no' => $transaction_no, 'status' => '0', 'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc, 'auth_code' => '0399', 'bankcode' => $bankid, 'paymode' => $txn_process_type, 'callback' => 'B2B');
                $this->master_model->updateRecord('payment_transaction', $update_data, array(
                    'receipt_no' => $MerchantOrderNo,
                ));
              
              $this->Ncvet_model->insert_common_log('Candidate - Applyexam update payment info', 'ncvet_payment_transaction', $this->db->last_query(), $candidate_id, 'candidate_action_applyexam', 'Applyexam update payment info', serialize($update_data));

                  //send mail to candidate regarding payment
              }

              redirect(base_url() . '/ncvet/candidate/applyexam/fail/' . base64_encode($MerchantOrderNo));
            }

      }
    }
    public function fail() {
      echo'payment failed';
    }

    public function generate_admitcard($mem_exam_id,$examcode,$regnumber) {
      $data = array();

      $candidate_id     = $this->login_candidate_id;

      $data['form_data'] = $form_data = $this->master_model->getRecords('ncvet_candidates bc', array('bc.candidate_id' => $candidate_id, 'bc.is_deleted' => '0'), "bc.*");  

      $admit_card_details = $this->master_model->getRecords('ncvet_admit_card_details', array('mem_exam_id' => $mem_exam_id, 'exm_cd' => $examcode));

      $password = generate_random_string(6);
      foreach ($admit_card_details as $row) {
          
          $update_data       = array(
              'pwd'                 => $password,
              'remark'              => 1,
              'modified_on'         => date('Y-m-d H:i:s'),
          );
          $this->master_model->updateRecord('ncvet_admit_card_details', $update_data, array(
              'admitcard_id' => $row['admitcard_id'],
          ));
          $this->session->unset_userdata('examinfo');
          
      }
      $this->Ncvet_model->insert_common_log('Candidate - Applyexam admitcard card details', 'ncvet_mencvet_admit_card_detailsmber_exam', $this->db->last_query(), $candidate_id, 'candidate_action_applyexam', 'Applyexam admitcard card details', serialize($admit_card_details));
      $update_data = array(
          'pay_status' => '1',
      );
      $this->master_model->updateRecord('ncvet_member_exam', $update_data, array(
              'id' => $mem_exam_id,
      ));

      $this->Ncvet_model->insert_common_log('Candidate - Applyexam update mem exam info', 'ncvet_member_exam', $this->db->last_query(), $candidate_id, 'candidate_action_applyexam', 'Applyexam update mem exam info', serialize($update_data));

      return $admitcard_pdf = genarate_ncvet_admitcard($admit_card_details[0]['mem_mem_no'], $examcode, $admit_card_details[0]['exm_prd']);
      
    }
    public function sendmail($mem_exam_id,$examcode,$regnumber,$flag,$admitcard_pdf,$invoice_path='') {

      //echo $admitcard_pdf;exit;
      $message = ''; $files =array();

      $candidate_id     = $this->login_candidate_id;

      $data['form_data'] = $form_data = $this->master_model->getRecords('ncvet_candidates bc', array('bc.candidate_id' => $candidate_id, 'bc.is_deleted' => '0'), "bc.*");  

      if($flag=='success') {
        $emailerstr = $this->master_model->getRecords('emailer', array(
                                'emailer_name' => 'ncvet_exam_acknowledge',
                            ));

        $message = $emailerstr[0]['emailer_text'];
        $files = array(
                     
                      $admitcard_pdf,
        );
        if($invoice_path!='') {
          $files = array(
                      $invoice_path,
                      $admitcard_pdf,
          );
        }
      }
      $info_arr = array(
          'to'=>'iibfdevp@esds.co.in',
          'from'    => 'norelpy@iibf.org.in',
          'subject' => $emailerstr[0]['subject'],
          'message' => $message,
      );
      $this->Emailsending->mailsend_attch($info_arr, $files);

    }
    public function acknowledgement($mem_exam_id) {

      $candidate_id     = $this->login_candidate_id;
      
      
      $data['form_data'] = $form_data = $this->master_model->getRecords('ncvet_candidates bc', array('bc.candidate_id' => $candidate_id, 'bc.is_deleted' => '0'), "bc.*");   


      $today_date = date('Y-m-d');
      $this->db->select('ncvet_member_exam.id,ncvet_member_exam.regnumber,ncvet_member_exam.exam_code,ncvet_member_exam.exam_mode,ncvet_member_exam.exam_medium,ncvet_member_exam.exam_period,ncvet_member_exam.exam_center_code,ncvet_member_exam.exam_fee,ncvet_member_exam.pay_status,ncvet_exam_master.description,ncvet_misc_master.exam_month,ncvet_member_exam.place_of_work,ncvet_member_exam.state_place_of_work,ncvet_member_exam.pin_code_place_of_work,ncvet_member_exam.elected_sub_code');
      $this->db->where('elg_mem_nm', 'Y');
      $this->db->join('ncvet_misc_master', 'ncvet_misc_master.exam_code=ncvet_member_exam.exam_code AND ncvet_misc_master.exam_period=ncvet_member_exam.exam_period');
      $this->db->where("ncvet_misc_master.misc_delete", '0');
      $this->db->join('ncvet_exam_master', 'ncvet_exam_master.exam_code=ncvet_member_exam.exam_code');
      $this->db->join('ncvet_exam_activation_master', 'ncvet_exam_activation_master.exam_code=ncvet_member_exam.exam_code');
      $this->db->where("'$today_date' BETWEEN ncvet_exam_activation_master.exam_from_date AND ncvet_exam_activation_master.exam_to_date");
      $applied_exam_info = $this->master_model->getRecords('ncvet_member_exam', array(
          'ncvet_member_exam.id' => base64_decode($mem_exam_id),
          'regnumber'             => $form_data[0]['regnumber'],
          'pay_status'            => '1',
      ));
      //echo $this->db->last_query();exit;
      
      if (count($applied_exam_info) <= 0) {
          redirect(base_url() . 'ncvet/candidate/applyexam/examlist');
      }

      $data['applied_exam_info']=$applied_exam_info;
      $this->load->view('ncvet/candidate/exam_acknowledgement', $data);
    }

  public function getFee()
	{
		$centerCode= $_POST['centerCode'];
		$eprid=$_POST['eprid'];
		$excd=$_POST['excd'];
		$grp_code=$_POST['grp_code'];
		$memcategory=$_POST['mtype'];
		$elearning_flag=$_POST['elearning_flag'];
		
		echo ncvet_getexamfee($centerCode,$eprid,$excd,$grp_code,$memcategory,$elearning_flag);
    exit;
		
  }
    public function common_validations($candidate_id,$examcode) {
      // check exam activate or not
      
      $check_exam_activation = check_ncvet_exam_activate($examcode);

      if ($check_exam_activation['flag'] == 0) {
         $this->session->set_flashdata('error', "You are not eligible for exam");
         redirect(base_url() . 'ncvet/candidate/applyexam/examlist/');
      } 
       
      $examperiod = $check_exam_activation['exam_list'][0]['exam_period'];
      
      $checkCandidateEligible = ncvet_checkCandidateEligible($candidate_id,$examcode,$examperiod); //ncvet_helper

      $data['candidate_data'] = $checkCandidateEligible['candidate_data'];

      if($checkCandidateEligible['flag']==0  && $checkCandidateEligible['errorType']=='kyc' ) {
        $this->session->set_flashdata('error', $checkCandidateEligible['message']);
           // redirect(base_url() . 'ncvet/candidate/dashboard_candidate/update_profile');
      }

     

      if($checkCandidateEligible['flag']!=1  && ($checkCandidateEligible['errorType']=='exameligible' || $checkCandidateEligible['errorType']=='alreadyappliedondate' )) {
        $this->session->set_flashdata('error', $checkCandidateEligible['message']);
        redirect(base_url() . 'ncvet/candidate/applyexam/examlist/');
      }
        $cookieflag = $exam_status = 1;
       
        $message            = '';
        $applied_exam_info  = array();
        $flag               = 1;
        $checkqualifyflag   = 0;

        $checkqualify = ncvet_checkqualify($candidate_id,$examcode);
        if ($checkqualify['flag']==0) {
           $this->session->set_flashdata('error', $checkqualify['message']);
          redirect(base_url() . 'ncvet/candidate/applyexam/examlist/');
        } 

        $examapplied = ncvet_examapplied($candidate_id,$examcode);
        if ($examapplied['flag']==0) {
           $this->session->set_flashdata('error', $examapplied['message']);
          redirect(base_url() . 'ncvet/candidate/applyexam/examlist/');
        } 
       
        // ask user to wait for 5 min, until the payment transaction process complete by (PRAFULL)
        $valcookie = $candidate_id;
        // $valcookie= applyexam_get_cookie();
        if ($valcookie) {
            $regnumber    = $valcookie;
            $checkpayment = $this->master_model->getRecords('ncvet_payment_transaction', array(
                'member_regnumber' => $regnumber,
                'status'           => '2',
                'pay_type'         => '2',
                'exam_code'        => $examcode
            ), '', array(
                'id' => 'DESC',
            ));
            if (count($checkpayment) > 0) {
                $endTime      = date("Y-m-d H:i:s", strtotime("+120 minutes", strtotime($checkpayment[0]['date'])));
                $current_time = date("Y-m-d H:i:s");
                if (strtotime($current_time) <= strtotime($endTime)) {
                    $cookieflag = 0;
                } else {
                    delete_cookie('ncvet_examid');
                }
            } else {
                delete_cookie('ncvet_examid');
            }
        } else {
            delete_cookie('ncvet_examid');
        }
        // END Of ask user to wait, until the payment transaction process complete

        if ($cookieflag == 0) {
           
            $this->session->set_flashdata('error', 'Please wait, your transaction is in process');
            redirect(base_url() . 'ncvet/candidate/applyexam/examlist/');
        }
        
        return array('exam_details'=>$check_exam_activation['exam_list'][0]);
    }

    public function accessdenied()
    {
       
        $data    = array(
            
            'message' => $message,
        );
        $this->load->view('ncvet/candidate/accessdenied', $data);
    }
    public function admitcards($enc_id=0)
    {      
      $data['act_id']        = "Admitcard Details";
      $data['sub_act_id']    = "Admitcard Details";
      $data['page_title'] = 'NCVET '.ucfirst($this->login_user_type).' Admitcards : List'; 
      
      $this->load->view('ncvet/candidate/admitcard_list', $data);
    }
    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE TRANSACTION DATA ********/
    public function get_admitcard_data_ajax()
    {

      $form_data = $this->master_model->getRecords('ncvet_candidates bc', array('bc.candidate_id' => $this->login_candidate_id, 'bc.is_deleted' => '0'), "bc.*"); 

      $table = 'ncvet_member_exam m';
      
      $column_order = array('m.id', 'em.description', 'ad.center_name', 'ad.admitcard_image','DATE_FORMAT(m.created_on, "%Y-%m-%d %H:%i") AS ApplicationDate', 'm.pay_status'); //SET COLUMNS FOR SORT
      
      $column_search = array( 'DATE_FORMAT(m.created_on, "%Y-%m-%d %H:%i")', 'IF(m.pay_status=0, "Fail", IF(m.pay_status=1, "Success"))'); //SET COLUMN FOR SEARCH
      $order = array('m.id' => 'DESC'); // DEFAULT ORDER
      
      //$WhereForTotal = " WHERE pt.gateway = '1' ";
      $WhereForTotal = " WHERE m.regnumber = ".$form_data[0]['regnumber']."  and m.pay_status=1  ";
      $Where = " WHERE m.regnumber = ".$form_data[0]['regnumber']."  and m.pay_status=1 and ad.admitcard_image!=''  ";
      

      
      if($_POST['search']['value']) // DATATABLE SEARCH
      {
        $Where .= " AND (";
        for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['search']['value']) )."%' ESCAPE '!' OR "; }
        $Where = substr_replace( $Where, "", -3 );
        $Where .= ')';
      }  
      $s_from_date = trim($this->security->xss_clean($this->input->post('s_from_date')));
      if($s_from_date != "") { $Where .= " AND DATE(m.created_on) >= '".$s_from_date."'"; } 
      
      $s_to_date = trim($this->security->xss_clean($this->input->post('s_to_date')));
      if($s_to_date != "") { $Where .= " AND DATE(m.created_on) <= '".$s_to_date."'"; } 
     
      $join_qry = ' INNER JOIN ncvet_admit_card_details  as ad on (m.id=ad.mem_exam_id) inner join ncvet_exam_master em on (m.exam_code=em.exam_code) ';
      
      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY ".key($order)." ".$order[key($order)]; }
      
      $Limit = ""; if ($_POST['length'] != '-1' ) { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT	
      
      $WhereForTotal .= " GROUP BY m.id";
      $Where .= " GROUP BY m.id";
      
      $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
      $Result = $this->db->query($print_query);  
      $Rows = $Result->result_array();
      
      $TotalResult = $this->Ncvet_model->datatable_record_cnt($column_order[0],$table,$WhereForTotal);
      $FilteredResult = $this->Ncvet_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);
      
      $data = array();
      $no = $_POST['start'];    
      
      foreach ($Rows as $Res) 
      {
        $no++;
        $row = array();
        
        $row[] = $no;
        $row[] = 'Exam Registration';
        $row[] = $Res['description'];
        $row[] = $Res['center_name'];
        $row[] = 'Success';
        $row[] = $Res['ApplicationDate'];
        
        $btn_str = ' <div class="text-right"> ';
        $btn_str .= ' <a download target="_blank" href="'.site_url('uploads/ncvet/admitcardpdf/'.($Res['admitcard_image']).'').'" class="btn btn-danger" title="View Admitcard">Download </a> ';
        
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
    }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE TRANSACTION DATA ********/
    
  }	    