<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Applytraining extends CI_Controller 
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
    
   

    public function traininglist()
		{   
			$data['act_id'] = "Training / Re-Enrollment ";
			$data['sub_act_id'] = "";
      $data['page_title'] = 'IIBF - NCVET Candidate Re-Enrollment';

      $today_date = date('Y-m-d');
      $data['candidate_id']     = $candidate_id     = $this->login_candidate_id;

      $this->session->unset_userdata('traininginfo');
      $checkCandidateEligible = ncvet_checkCandidateEligible($candidate_id); //ncvet_helper

      $data['candidate_data'] = $checkCandidateEligible['candidate_data'];

      if($checkCandidateEligible['flag']!=1  && $checkCandidateEligible['errorType']=='kyc' ) { //if kyc is not comppleted
        //$this->session->set_flashdata('error', "Please wait until KYC Process done for your documents");
           // redirect(base_url() . 'ncvet/candidate/dashboard_candidate/update_profile');
      }
     
      
      $this->db->where("'$today_date' BETWEEN ncvet_training_activation.from_date AND ncvet_training_activation.to_date");
      $this->db->where("ncvet_training_activation.training_activation_delete", "0");
      $this->db->group_by('ncvet_training_activation.program_code');
      $training_list = $this->master_model->getRecords('ncvet_training_activation');
      
      $data['training_list'] =$training_list;

      $this->load->view('ncvet/candidate/traininglist', $data);
    }

    /******** START : Exam Details Page ********/
    
    public function trainingdetails()
    {

      $program_code = base64_decode($this->input->get('program_code'));
      $this->session->set_userdata('ncvet_curr_program_code',$program_code);
      
      $data['candidate_id']     = $candidate_id     = $this->login_candidate_id;

      $check = $this->common_validations($candidate_id,$program_code);
       
      $this->session->unset_userdata('traininginfo');

      $training_info = $this->master_model->getRecords('ncvet_training_activation', array(
            'program_code' => $program_code,
        ));

        $data = array(
            
            'training_info'      => $training_info,
        );
        redirect(base_url() . 'ncvet/candidate/applytraining/trainingform');
      //$this->load->view('ncvet/candidate/trainingdetails', $data);
       
    }
    /******** END : Exam Details Page ********/

    public function trainingform() { 
      $program_code = $this->session->userdata('ncvet_curr_program_code');
      $data = array();

      $data['candidate_id']     = $candidate_id     = $this->login_candidate_id;
       
      $check = $this->common_validations($candidate_id,$program_code);

      $data['form_data'] = $form_data = $this->master_model->getRecords('ncvet_candidates bc', array('bc.candidate_id' => $candidate_id, 'bc.is_deleted' => '0'), "bc.*");   

      
      $data['training_details']=$training_details=$check['training_details'];
      
      $eligible_data = $this->master_model->getRecords('ncvet_training_eligible', array(
                    'ncvet_training_eligible.program_code' => $program_code,
                    'member_no'                 => $form_data[0]['regnumber'],
        ));
      $data['eligible_data'] = $eligible_data;
      $data['amount'] = ncvet_gettrainingfee($form_data[0]['regnumber'],$program_code);

      if(count($form_data) == 0) { redirect(site_url('ncvet/candidate/dashboard_candidate')); }

      
      if (isset($_POST['submitAll']) && count($_POST) > 0)
      {
        $this->form_validation->set_rules('program_name', 'Training Details', 'required');
        $this->form_validation->set_rules('amount', 'Training Fee', 'required|xss_clean'); 
        
        
        if ($this->form_validation->run() == true) {
         
          $today = date('Y-m-d h:i:s');

         
          $user_data = array(
            'candidate_id'      => $candidate_id,
            'program_code'         => $program_code,
            'amount'         => $_POST['amount'],
            'registration_type' => $form_data[0]['registration_type'],
          );
          $this->session->set_userdata('traininginfo', $user_data);
          $this->Ncvet_model->insert_common_log('Candidate - Applytraining session', 'ncvet_training_registrations', '', $candidate_id, 'candidate_action_applytraining session', 'Applytraining session', serialize($user_data));
          redirect(base_url() . 'ncvet/candidate/applytraining/process_application');

        }
        else {
          $var_errors = str_replace("<p>", "<span>", $var_errors);
          $var_errors = str_replace("</p>", "</span><br />", $var_errors);
          $data['var_errors'] = $var_errors;
          
        }
      }

      
        $this->load->view('ncvet/candidate/trainingform', $data);
    }

    public function preview() {

      $data = array();

      $data['candidate_id']     = $candidate_id     = $this->login_candidate_id;

      $data['form_data'] = $form_data = $this->master_model->getRecords('ncvet_candidates bc', array('bc.candidate_id' => $candidate_id, 'bc.is_deleted' => '0'), "bc.*"); 
      $data['training_details'] = $this->session->userdata('traininginfo');  

     
      $this->load->view('ncvet/candidate/trainingreview', $data);

    }
    public function process_application() {
      $data = array();

      $data['candidate_id']     = $candidate_id     = $this->login_candidate_id;

      
      if ($this->session->userdata('traininginfo') == '') {
            redirect(base_url() . 'ncvet/candidate/applytraining/traininglist/');
        }

      $data['traininginfo'] = $traininginfo= $this->session->userdata('traininginfo');  
      $program_code = $data['traininginfo']['program_code'];

      $check = $this->common_validations($candidate_id,$program_code);
      
      $data['form_data'] = $form_data = $this->master_model->getRecords('ncvet_candidates bc', array('bc.candidate_id' => $candidate_id, 'bc.is_deleted' => '0'), "bc.*"); 
      $regnumber = $form_data[0]['regnumber'];
      
      if (!empty($traininginfo)) {

        $amount = ncvet_gettrainingfee($regnumber,$program_code);

        $insert_array = array(
                'regnumber'              => $regnumber,
                'program_code'              => $program_code,
                'created_on'             => date('y-m-d H:i:s'),
                'from_date'              => $check['training_details']['from_date'],
                'to_date'                => $check['training_details']['to_date'],
                'status'                 => 0
            );
            if ($insert_id = $this->master_model->insertRecord('ncvet_training_registrations', $insert_array, true)) {

                /* User Log Activities  */
                $this->Ncvet_model->insert_common_log('Candidate - Member training apply details', 'ncvet_training_registrations', $this->db->last_query(), $candidate_id, 'candidate_action_applytraining insertion', 'Applytraining insert_array', serialize($insert_array));
                /* Close User Log Actitives */

                // echo $this->session->userdata['traininginfo']['fee'];
                $this->session->userdata['traininginfo']['training_reg_id'] = $insert_id;
                

                if($amount>0) {
                  redirect(base_url() . 'ncvet/candidate/applytraining/make_payment/');
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
      $data['traininginfo'] = $traininginfo = $this->session->userdata('traininginfo');  

      $program_code = $data['traininginfo']['program_code'];

      $check = $this->common_validations($candidate_id,$program_code);

      if (isset($_POST['processPayment']) && $_POST['processPayment']) {
         $checkpayment = $this->master_model->getRecords('ncvet_payment_transaction', array('pay_type' => 3, 'ref_id' => $this->session->userdata['traininginfo']['training_reg_id']));
            
            if (count($checkpayment) > 0) {
                $this->session->set_flashdata('error', 'Wait your transaction is under process!..');
                redirect(base_url() . 'ncvet/candidate/applytraining/traininglist/');
            } 
            $pg_name = 'billdesk';
            //checked for application in payment process and prevent user to apply exam on the same time
            $checkpayment = $this->master_model->getRecords('ncvet_payment_transaction', array('member_regnumber' => $regnumber, 'status' => '2', 'pay_type' => '3'), '', array('id' => 'DESC'));
            if (count($checkpayment) > 0) {
                $endTime      = date("Y-m-d H:i:s", strtotime("+120 minutes", strtotime($checkpayment[0]['date'])));
                $current_time = date("Y-m-d H:i:s");
                if (strtotime($current_time) <= strtotime($endTime)) {
                    $this->session->set_flashdata('error', 'Your transactions is in process, please try after 2 hrs after your initial transaction.');
                    redirect(base_url() . 'ncvet/candidate/applytraining/traininglist/');
                }
            }

            
            $amount = ncvet_gettrainingfee($regnumber,$program_code);

            if ($amount == 0 || $amount == '') {
                $this->session->set_flashdata('error', 'Fee can not be zero(0) or Blank!!');
                redirect(base_url() . 'ncvet/candidate/applytraining/traininglist/');
            }
           

            $ref4 = $program_code.date('m');
            // Create transaction
            $insert_data = array(
                'member_regnumber' => $regnumber,
                'amount'           => $amount,
                'gateway'          => "billdesk",
                'date'             => date('Y-m-d H:i:s'),
                'pay_type'         => '3',
                'ref_id'           => $traininginfo['training_reg_id'],
                'description'      => $program_code.' Re-registration',
                'status'           => '2',
                'exam_code'        => 0,
                'pg_flag'          => "NCVET_TRAINING",
                'gateway'         => 'billdesk',
            );
            $pt_id           = $this->master_model->insertRecord('ncvet_payment_transaction', $insert_data, true);
            $MerchantOrderNo = generate_ncvet_reg_receipt_no($pt_id);
            // payment gateway custom fields -
            $custom_field = $MerchantOrderNo . "^iibfncvettraining^" . $regnumber . "^" . $ref4;

            $custom_field_billdesk = $MerchantOrderNo . "-iibfncvettraining-" . $regnumber . "-" . $ref4;
            //  echo $custom_field_billdesk;exit;
            // update receipt no. in payment transaction -
            $update_data = array(
                'receipt_no'       => $MerchantOrderNo,
                'pg_other_details' => $custom_field,
            );

            $this->master_model->updateRecord('ncvet_payment_transaction', $update_data, array(
                'id' => $pt_id,
            ));

            $tax_type = '-';
            $igst_total      = $amount;
            $gst_no = '0';
            $state = $form_data[0]['state_pr'];

            $getstate = $this->master_model->getRecords('state_master', array('state_code' => $state, 'state_delete' => '0'));

            $invoice_insert_array = array(
              'pay_txn_id'                                => $pt_id,
              'receipt_no'                               => $MerchantOrderNo,
              'member_no'                                 => $regnumber,
              'state_of_center'                          => $state,
              'app_type'                                 => 'T',
              'service_code'                             => $this->config->item('NCVET_service_code'),
              'qty'                                      => '1',
              'state_code'                               => $getstate[0]['state_no'],
              'state_name'                               => $getstate[0]['state_name'],
              'tax_type'                                 => $tax_type,
              'fee_amt'                                  => $amount,
              'cgst_rate'                                => $cgst_rate,
              'cgst_amt'                                 => $cgst_amt,
              'sgst_rate'                                => $sgst_rate,
              'sgst_amt'                                 => $sgst_amt,
              'igst_rate'                                => $igst_rate,
              'igst_amt'                                 => $igst_amt,
              'cs_total'                                 => $cs_total,
              'igst_total'                               => $igst_total,
              'gstin_no'                                 => '',
              'exempt'                                   => $getstate[0]['exempt'],
              'created_on'                               => date('Y-m-d H:i:s')
            );
            $insert_id    = $this->master_model->insertRecord('ncvet_exam_invoice', $invoice_insert_array, true);
            /* User Log Activities  */
             $this->Ncvet_model->insert_common_log('Candidate - Insert training invoice details', 'ncvet_exam_invoice', $this->db->last_query(), $candidate_id, 'candidate_action_applytraining insertion', 'Applytraining insert_array', serialize($invoice_insert_array));
                /* Close User Log Actitives */

            $marchant_id = $MerchantOrderNo;
                
              $ref_id      = $this->session->userdata['traininginfo']['training_reg_id'];

              $payment_raw = $this->master_model->getRecordCount('ncvet_payment_transaction', array('receipt_no' => $marchant_id, 'exam_code' =>0, 'member_regnumber' => $regnumber));

              $exam_invoice_raw = $this->master_model->getRecordCount('ncvet_exam_invoice', array('receipt_no' => $marchant_id, 'exam_code' => 0, 'member_no' => $regnumber));

              if ($payment_raw == 0 || $exam_invoice_raw == 0) {
                  $this->session->set_flashdata('error', 'Something went wrong!!');
                  redirect(base_url() . 'ncvet/candidate/applytraining/traininglist/');
              }

              // set cookie for Apply Exam
              ncvetexam_set_cookie($ref_id);
              
              $callback_link = '/ncvet/candidate/applytraining/handle_billdesk_response';


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
                  redirect(base_url() . '/ncvet/candidate/applytraining/fail/' . base64_encode($MerchantOrderNo));
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
            $get_user_regnum = $this->master_model->getRecords('ncvet_payment_transaction', array('receipt_no' => $MerchantOrderNo), 'id,member_regnumber,ref_id,status,date');

            $qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);

            $this->db->join('state_master', 'state_master.state_code=ncvet_candidates.state');
            $result = $this->master_model->getRecords('ncvet_candidates', array(
                'regnumber' => $get_user_regnum[0]['member_regnumber'],
            ), 'candidate_id,first_name,middle_name,last_name,address1,address2,address3,district,city,email_id,mobile_no,pincode,state_master.state_name');

            $candidate_id = $result[0]['candidate_id'];
            $regnumber = $result[0]['regnumber'];

            if ($auth_status == '0300' && $qry_api_response['auth_status'] == '0300') {
              if ($get_user_regnum[0]['status'] == 2) {
                    
                    
                    $this->db->join('ncvet_training_activation', 'ncvet_training_activation.program_code=ncvet_training_registrations.program_code');
                    $applied_training_info = $this->master_model->getRecords('ncvet_training_registrations', array(
                        'regnumber'      => $get_user_regnum[0]['member_regnumber'],
                        'ncvet_training_registrations.id' => $get_user_regnum[0]['ref_id'],
                    ));

               
                    if (count($applied_training_info) > 0 ) {
                      // ######## payment Transaction ############

                        $update_data  = array('transaction_no' => $transaction_no, 'status' => 1, 'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc, 'auth_code' => '0300', 'bankcode' => $bankid, 'paymode' => $txn_process_type, 'callback' => 'B2B');
                        $update_query = $this->master_model->updateRecord('ncvet_payment_transaction', $update_data, array(
                            'receipt_no' => $MerchantOrderNo,
                            'status'     => 2,
                        ));
                        $this->Ncvet_model->insert_common_log('Candidate - Applytraining update payment info', 'ncvet_payment_transaction', $this->db->last_query(), $candidate_id, 'candidate_action_applytraining', 'Applytraining update payment info', serialize($update_data));

                        

                        $getinvoice_number = $this->master_model->getRecords('ncvet_exam_invoice', array(
                            'receipt_no' => $MerchantOrderNo,
                            'pay_txn_id' => $get_user_regnum[0]['id'],
                        ));

                        if (count($getinvoice_number) > 0) {
                            $invoiceNumber = '';
                            $invoiceNumber       = generate_ncvet_enroll_invoice_number($getinvoice_number[0]['invoice_id']);
                            $invoiceNumber = $this->config->item('ncvet_mem_invoice_no_prefix') . $invoiceNumber; 
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
                            $this->Ncvet_model->insert_common_log('Candidate - Applytraining update invoice info', 'ncvet_exam_invoice', $this->db->last_query(), $candidate_id, 'candidate_action_applytraining', 'Applytraining update invoice info', serialize($update_data_invoice));
                            $invoice_path = ncvet_genarate_reg_invoice($getinvoice_number[0]['invoice_id'],'re-enrollment');

                            $update_data_arr=array('status'=>1);

                            $this->master_model->updateRecord('ncvet_training_registrations', $update_data_arr, array(
                                'id' => $get_user_regnum[0]['ref_id'],
                            ));

                            $ref_id = $get_user_regnum[0]['ref_id'];
                            $message = ''; $files =array();
      
                            $message = 'Successfully applied';
                            $files = array(
                                        
                                          $admitcard_pdf,
                            );
                            if($invoice_path!='') {
                              $files = array(
                                          $invoice_path,
                                          
                              );
                            }
                          
                          $info_arr = array(
                              'to'=>'iibfdevp@esds.co.in',
                              'from'    => 'norelpy@iibf.org.in',
                              'subject' => 'NCVET - Re-Enrollment Acknowledgement',
                              'message' => $message,
                          );
                        $this->Emailsending->mailsend_attch($info_arr, $files);
                            redirect(base_url() . 'ncvet/candidate/applytraining/acknowledgement/'.base64_encode($ref_id));

                        }

                        else {
                          $this->Ncvet_model->insert_common_log('Candidate - Applytraining no invoice record found', 'ncvet_exam_invoice', $this->db->last_query(), $candidate_id, 'candidate_action_applytraining', 'Applytraining no invoice record found', serialize(array()));
                          redirect(base_url() . '/ncvet/candidate/applytraining/fail/' . base64_encode($MerchantOrderNo));
                        }
                    }
              }
              else {
                $this->Ncvet_model->insert_common_log('Candidate - Applytraining payment update problem', 'ncvet_payment_transaction', '', $candidate_id, 'candidate_action_applytraining', 'Applytraining payment update problem', serialize($get_user_regnum));
                redirect(base_url() . '/ncvet/candidate/applytraining/fail/' . base64_encode($MerchantOrderNo));
              }
            }
            elseif ($auth_status == '0002') {

                if ($get_user_regnum[0]['status'] != 0 && $get_user_regnum[0]['status'] == 2) {

                  $update_data = array('transaction_no' => $transaction_no, 'status' => '2', 'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc, 'auth_code' => '0002', 'bankcode' => $bankid, 'paymode' => $txn_process_type, 'callback' => 'B2B');
                  $this->master_model->updateRecord('ncvet_payment_transaction', $update_data, array(
                      'receipt_no' => $MerchantOrderNo,
                  ));

                  $this->Ncvet_model->insert_common_log('Candidate - Applytraining update payment info', 'ncvet_payment_transaction', $this->db->last_query(), $candidate_id, 'candidate_action_applytraining', 'Applytraining update payment info', serialize($update_data));

                  //send mail to candidate regarding pending payment
                }
                redirect(base_url() . '/ncvet/candidate/applytraining/fail/' . base64_encode($MerchantOrderNo));
            }
            else {
              if ($get_user_regnum[0]['status'] != 0 && $get_user_regnum[0]['status'] == 2) {

                $update_data = array('transaction_no' => $transaction_no, 'status' => '0', 'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc, 'auth_code' => '0399', 'bankcode' => $bankid, 'paymode' => $txn_process_type, 'callback' => 'B2B');
                $this->master_model->updateRecord('payment_transaction', $update_data, array(
                    'receipt_no' => $MerchantOrderNo,
                ));
              
              $this->Ncvet_model->insert_common_log('Candidate - Applytraining update payment info', 'ncvet_payment_transaction', $this->db->last_query(), $candidate_id, 'candidate_action_applytraining', 'Applytraining update payment info', serialize($update_data));

                  //send mail to candidate regarding payment
              }

              redirect(base_url() . '/ncvet/candidate/applytraining/fail/' . base64_encode($MerchantOrderNo));
            }

      }
    }
    public function fail() {
      echo'payment failed';
    }

    public function acknowledgement($training_reg_id) {

      $candidate_id     = $this->login_candidate_id;
      
      
      $data['form_data'] = $form_data = $this->master_model->getRecords('ncvet_candidates bc', array('bc.candidate_id' => $candidate_id, 'bc.is_deleted' => '0'), "bc.*");   

      $today_date = date('Y-m-d');
      
      $this->db->join('ncvet_training_activation', 'ncvet_training_activation.program_code=ncvet_training_registrations.program_code');
      
      $applied_training_info = $this->master_model->getRecords('ncvet_training_registrations', array(
          'ncvet_training_registrations.id' => base64_decode($training_reg_id),
          'regnumber'             => $form_data[0]['regnumber'],
          'status'            => '1',
      ));
      
      if (count($applied_training_info) <= 0) {
          redirect(base_url() . 'ncvet/candidate/applytraining/traininglist');
      }
      $this->session->unset_userdata('traininginfo');
      $data['applied_training_info']=$applied_training_info;
      $this->load->view('ncvet/candidate/training_acknowledgement', $data);


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
    public function common_validations($candidate_id,$program_code) {
      // check exam activate or not
      
      $check_training_activation = check_ncvet_training_activate($program_code);

      if ($check_training_activation['flag'] == 0) {
         $this->session->set_flashdata('error', "Training Registrations are not activated");
         redirect(base_url() . 'ncvet/candidate/applytraining/traininglist/');
      } 
      
      
      $checkCandidateEligible = ncvet_checkCandidateEligibleTraining($candidate_id,$program_code); //ncvet_helper

      $data['candidate_data'] = $checkCandidateEligible['candidate_data'];

      if($checkCandidateEligible['flag']==0  && $checkCandidateEligible['errorType']=='kyc' ) {
        $this->session->set_flashdata('error', $checkCandidateEligible['message']);
           // redirect(base_url() . 'ncvet/candidate/dashboard_candidate/update_profile');
      }

     

      if($checkCandidateEligible['flag']!=1  && ($checkCandidateEligible['errorType']=='trainingligible' || $checkCandidateEligible['errorType']=='alreadyappliedfortraining' )) {
        $this->session->set_flashdata('error', $checkCandidateEligible['message']);
        redirect(base_url() . 'ncvet/candidate/applytraining/traininglist/');
      }
        $cookieflag = $exam_status = 1;
       
        $message            = '';
        $applied_exam_info  = array();
        $flag               = 1;
        $checkqualifyflag   = 0;

       
        // ask user to wait for 5 min, until the payment transaction process complete by (PRAFULL)
        $valcookie = $candidate_id;
        // $valcookie= applyexam_get_cookie();
        if ($valcookie) {
            $regnumber    = $valcookie;
            $checkpayment = $this->master_model->getRecords('ncvet_payment_transaction', array(
                'member_regnumber' => $regnumber,
                'status'           => '2',
                'pay_type'         => '3',
                'exam_code'        => 0
            ), '', array(
                'id' => 'DESC',
            ));
            if (count($checkpayment) > 0) {
                $endTime      = date("Y-m-d H:i:s", strtotime("+120 minutes", strtotime($checkpayment[0]['date'])));
                $current_time = date("Y-m-d H:i:s");
                if (strtotime($current_time) <= strtotime($endTime)) {
                    $cookieflag = 0;
                } else {
                    delete_cookie('ncvet_trainingid');
                }
            } else {
                delete_cookie('ncvet_trainingid');
            }
        } else {
            delete_cookie('ncvet_trainingid');
        }
        // END Of ask user to wait, until the payment transaction process complete

        if ($cookieflag == 0) {
           
            $this->session->set_flashdata('error', 'Please wait, your transaction is in process');
            redirect(base_url() . 'ncvet/candidate/applytraining/traininglist/');
        }
        
        return array('training_details'=>$check_training_activation['training_list'][0]);
    }

    public function accessdenied()
    {
       
        $data    = array(
            
            'message' => $message,
        );
        $this->load->view('ncvet/candidate/accessdenied', $data);
    }
  }	    