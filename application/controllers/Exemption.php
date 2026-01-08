<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Exemption extends CI_Controller
{
    public function __construct()
    {
        
        parent::__construct();
        $this->load->library('upload');
        $this->load->helper('upload_helper');
        $this->load->helper('master_helper');
        $this->load->model('master_model');
        $this->load->library('email');
        $this->load->model('chk_session');
        $this->load->model('Emailsending');
        $this->load->helper('cookie');
        $this->load->model('log_model');
        $this->load->model('billdesk_pg_model');
        $this->load->model('refund_after_capacity_full');
        
        $this->chk_session->Check_mult_session();
        if ($this->router->fetch_method() != 'Msuccess'  && $this->router->fetch_method() != 'sbi_make_payment' && $this->router->fetch_method() != 'sbitranssuccess' && $this->router->fetch_method() != 'sbitransfail' && $this->router->fetch_method() != 'accessdenied' && $this->router->fetch_method() != 'getFee'  && $this->router->fetch_method() != 'refund'  && $this->router->fetch_method() != 'handle_billdesk_response'  && $this->router->fetch_method() != 'process' && $this->router->fetch_method() != 'exemption_api_call_func') { 
            if ($this->session->userdata('examinfo')) {
                $this->session->unset_userdata('examinfo'); 
            }
            if ($this->session->userdata('examcode')) {
                $this->session->unset_userdata('examcode');
            }
        }
       
      
    }

    // ##---------Thank you page for user (prafull)-----------##
    public function acknowledge()
    {
        $kycflag = 0;
        // $this->chk_session->checkphoto();
        $data = array(
            'middle_content'     => 'exemption_acknowledge',
           // 'application_number' => $this->session->userdata('regnumber'),
            'password'           => base64_decode($this->session->userdata('password')),
        );
        if($this->session->userdata('mregnumber_applyexam'))
            $this->load->view('memapplyexam/mem_apply_exam_common_view', $data);

        else if($this->session->userdata('nmregnumber'))
            $this->load->view('nonmember/nm_common_view', $data);
        else
            $this->load->view('common_view', $data);
    }

   
    public function accessdenied()
    {
        // echo 'accessdenied';exit;
        $message = '<div style="color:#F00">You are not eligible to register for the selected examination. For details refer the link <strong>"Important Notice of Diploma and Certificate Examination syllabus" </strong> at the institutes website</div>';
        $data    = array(
            'middle_content'    => 'not_eligible',
            'check_eligibility' => $message,
        );
        if($this->session->userdata('mregnumber_applyexam'))
        return $this->load->view('memapplyexam/mem_apply_exam_common_view', $data);

        else if($this->session->userdata('nmregnumber'))
            return $this->load->view('nonmember/nm_common_view', $data);
        else
        return $this->load->view('common_view', $data);
    }

    function get_client_ip1() {
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
		   $ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}
    public function call_from_QA($exam_code,$exam_period,$regnumber) {
        $exemptionres = $this->master_model->exemption_api_call_func($exam_code,$exam_period,$regnumber);
        echo $exemptionres;
    }
    //exemptionprocess function
    public function process() {
        
        $exam_code = $this->session->userdata('examcode');

        if($this->session->userdata('nmregnumber'))
            $regnumber = $this->session->userdata('nmregnumber');

        if($this->session->userdata('regnumber'))
            $regnumber = $this->session->userdata('regnumber');

        if($this->session->userdata('mregnumber_applyexam'))
            $regnumber = $this->session->userdata('mregnumber_applyexam');

        if($this->session->userdata('mregid_applyexam'))
            $regid = $this->session->userdata('mregid_applyexam');

        if($this->session->userdata('regid'))
            $regid = $this->session->userdata('regid');
        
        if($this->session->userdata('nmregid'))
            $regid = $this->session->userdata('nmregid');
        
        $this->db->select('exam_master.*,misc_master.*');
        $this->db->join('misc_master', 'misc_master.exam_code=exam_master.exam_code');
        $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=misc_master.exam_code AND misc_master.exam_period=exam_activation_master.exam_period'); //added on 5/6/2017
        $this->db->where("misc_master.misc_delete", '0');
        $this->db->where('exam_master.exam_code', $exam_code);
        $examinfo = $this->master_model->getRecords('exam_master');
        
        $exemptionres = $this->master_model->exemption_api_call_func($exam_code,$examinfo[0]['exam_period'],$regnumber);
        
            if($exemptionres=='true') {
                    
            }
            else {
                redirect(base_url() . 'exemption/accessdenied/');
            }

           // $this->db->join('payment_transaction', 'payment_transaction.ref_id =exam_exemptions.id');
       
           // $this->db->where('payment_transaction.status', '2');
            //$this->db->where('pay_type', $this->config->item('exemption_pay_type'));

            $check_payment_val = $this->master_model->getRecords('payment_transaction', array(
                'status'=>2,
                'payment_transaction.exam_code' => $exam_code,
                'member_regnumber'             => $regnumber,
            ));
            
            if (count($check_payment_val)>0) {
               
                $msg  = '<h4> Your transaction is in process. Please wait for some time.</h4>';
                $data = array('middle_content' => 'member_notification', 'msg' => $msg);
                if($this->session->userdata('mregnumber_applyexam'))
                    return $this->load->view('memapplyexam/mem_apply_exam_common_view', $data);

                else if($this->session->userdata('nmregnumber'))
                    return $this->load->view('nonmember/nm_common_view', $data);
                else
                return $this->load->view('common_view', $data);
            }

            
            $check = $this->examapplied($regnumber, $exam_code);
            if($check){
                $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=misc_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
                $get_period_info = $this->master_model->getRecords('misc_master', array(
                    'misc_master.exam_code'   => $exam_code,
                    'misc_master.misc_delete' => '0',
                ), 'exam_month');
               
                $month            = date('Y') . "-" . substr($get_period_info[0]['exam_month'], 4); 
                $exam_period_date = date('F', strtotime($month)) . "-" . substr($get_period_info[0]['exam_month'], 0, -2);
                $message          = 'Application for this examination is already registered by you and is valid for <strong>' . $exam_period_date . '</strong>.... period. Hence you need not apply for the same.';
                $data             = array(
                    'middle_content'    => 'already_apply',
                    'check_eligibility' => $message,
                );
                if($this->session->userdata('mregnumber_applyexam'))
                    return $this->load->view('memapplyexam/mem_apply_exam_common_view', $data);

                else if($this->session->userdata('nmregnumber'))
                    return $this->load->view('nonmember/nm_common_view', $data);
                else
                return $this->load->view('common_view', $data);
            }
            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=center_master.exam_name AND exam_activation_master.exam_period=center_master.exam_period');
            $this->db->where('center_master.exam_name', $exam_code);
            $this->db->where("center_delete", '0');
            $center = $this->master_model->getRecords('center_master', '', '', array(
                'center_name' => 'ASC',
            ));

            $this->db->where('state_delete', '0');
            $states = $this->master_model->getRecords('state_master');

            $user_info = $this->master_model->getRecords('member_registration', array(
                'regid'     => $regid,
                'regnumber' => $regnumber,
            ));
            
            $check_exam_activation = check_exam_activate($exam_code);
           // echo count($user_info).'='.count($examinfo).'='.$check_exam_activation;exit;
            if (count($user_info) <= 0 || count($examinfo) <=0 || $check_exam_activation == 0) {
                redirect(base_url() . 'exemption/accessdenied');
            }

            if (isset($_POST['btnPaySubmit'])) {
              
                $this->form_validation->set_rules('txtCenterCode', 'Centre Code', 'required|xss_clean');
                if ($this->form_validation->run() == true) {  
                     
                    $inser_array = array(
                        'regnumber'              => $regnumber,
                        'exam_code'              => $exam_code,                       
                        'exam_period'            => $examinfo[0]['exam_period'],
                        'pay_status'             => 0,
                        'created_on'             => date('Y-m-d H:i:s'),
                    );
                    //echo'<pre>';print_r($inser_array);exit;
                    if ($inser_id = $this->master_model->insertRecord('exam_exemptions', $inser_array, true)) {
                        // echo'<pre>';print_r($_POST);exit;
                        $log_title   = "exam_exemptions - Insert ";
                        $log_message = serialize($inser_array);
                        $rId         = $regid;
                        $regNo       = $regnumber;
                        storedUserActivity($log_title, $log_message, $rId, $regNo);

                        $user_data = array(
                            'email'                  => $user_info[0]["email"],
                            'mobile'                 => $user_info[0]["mobile"],
                            'photo'                  => '',
                            'signname'               => '',
                            'medium'                 => '',
                            'selCenterName'          => $_POST['selCenterName'],
                            'optmode'                => '',
                            'extype'                 => '',
                            'exname'                 => $examinfo[0]['description'],
                            'excd'                   => base64_encode($exam_code),
                            'eprid'                  => $examinfo[0]['exam_period'],
                            'fee'                    => $_POST['fee'],
                            'txtCenterCode'          => $_POST['txtCenterCode'],
                            'insdet_id'              => $inser_id,
                            'selected_elect_subcode' => 0,
                            'selected_elect_subname' => 0,
                            'placeofwork'            => '',
                            'state_place_of_work'    => '',
                            'pincode_place_of_work'  => '',
                            'elected_exam_mode'      => '',
                            'grp_code'               => $_POST['grp_code'],
                            'subject_arr'            => array(),
                            'el_subject'             => '',
                            'scribe_flag'            => 0,
                            'scribe_flag_d'          => 0,
                            'disability_value'       => '',
                            'Sub_menue_disability'   => 0,
                            'elearning_flag'         => 'N',
                            'optval'                 => '', // priyanka d- 24-03-23
                            'optFlg'                 =>  '',
                            'selinstitute'           => '',
                            'selinstitutionname'     => '',
                            'exemption'              => 1 ,//priyanka d >> 03-july-24
                            'regnumber'              => $regnumber,
                            'regid'                  => $regid
                        );
                        $this->session->set_userdata('examinfo', $user_data);
                        
                        $log_title   = "exemption session details";
                        $log_message = serialize($user_data);
                        $rId         = $regid;
                        $regNo       = $regnumber;
                        storedUserActivity($log_title, $log_message, $rId, $regNo);

                        redirect(base_url() . 'exemption/sbi_make_payment/');

                    }
                }
                else {
                    $var_errors = str_replace("<p>", "<span>", $var_errors);
                    $var_errors = str_replace("</p>", "</span><br />", $var_errors);
                }
            }

            $eligiblity_details = $this->master_model->getRecords('eligible_master', array('eligible_master.exam_code' => $exam_code, 'member_no' => $regnumber, 'eligible_period' => $examinfo[0]['exam_period']));
            //echo $this->db->last_query();
            //echo'<pre>';print_r($eligiblity_details);exit;
            if($eligiblity_details) {
                foreach($eligiblity_details as $e) {
                    if($e['exam_status']=='F') {
                    }
                    else {
                        $message          = 'Application for this examination is already registered by you <strong>' ;
                        $data             = array(
                            'middle_content'    => 'already_apply',
                            'check_eligibility' => $message,
                        );
                        if($this->session->userdata('mregnumber_applyexam'))
                            return $this->load->view('memapplyexam/mem_apply_exam_common_view', $data);
        
                        else if($this->session->userdata('nmregnumber'))
                            return $this->load->view('nonmember/nm_common_view', $data);
                        else
                        return $this->load->view('common_view', $data);
                    }
                }
            }
            $data                      = array(
                'middle_content'            => 'exemptionprocess',
                'states'                    => $states,                
                'user_info'                 => $user_info,
                'examinfo'                  => $examinfo,
                'center'                    => $center,
                'eligiblity_details'        => $eligiblity_details,
            );
            if($this->session->userdata('mregnumber_applyexam'))
                $this->load->view('memapplyexam/mem_apply_exam_common_view', $data);

            else if($this->session->userdata('nmregnumber'))
            $this->load->view('nonmember/nm_common_view', $data);
            else
                $this->load->view('common_view', $data);
    }

    public function exemption_handle_billdesk_response(){
            // ini_set('display_errors', 1);
            // ini_set('display_startup_errors', 1);
            // error_reporting(E_ALL);
       
        delete_cookie('regid');
        $this->session->unset_userdata('enduserinfo');
        $this->session->unset_userdata('memberdata');

        if (isset($_REQUEST['transaction_response'])) {
            
            $response_encode        = $_REQUEST['transaction_response'];
            $bd_response            = $this->billdesk_pg_model->verify_res($response_encode);
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

            $user_payment_txn_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,status,id,member_regnumber');

            $qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);

           
            if ($auth_status == '0300' && $qry_api_response['auth_status'] == '0300' && $user_payment_txn_info[0]['status'] == 2) {
                
                $update_data = array(
                    'transaction_no'      => $transaction_no,
                    'status'              => 1,
                    'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
                    'gateway'             => 'billdesk',
                    'auth_code'           => '0300',
                    'bankcode'            => $bankid,
                    'paymode'             => $txn_process_type,
                    'callback'            => 'B2B',
                );

                $update_query = $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo, 'status' => 2));

                $log_title   = "Exemption payment paymentupdate";
                $log_message = serialize($update_data);
                $reg_id         = $user_payment_txn_info[0]['ref_id'];
                

                storedUserActivity($log_title, $log_message, $reg_id, $reg_id);

                if ($this->db->affected_rows()) {
        
                    if (count($user_payment_txn_info) > 0) {
                        
                        $update_mem_data = array('pay_status' => '1');
                        $this->master_model->updateRecord('exam_exemptions', $update_mem_data, array('id' => $reg_id));
                    }
                    
                    //get invoice
                    $getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo, 'pay_txn_id' => $user_payment_txn_info[0]['id']));
                    
                    if (count($getinvoice_number) > 0) {

                        $invoiceNumber = generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
                        if ($invoiceNumber) {
                            $invoiceNumber = $this->config->item('exam_invoice_no_prefix'). $invoiceNumber;
                        }

                        $update_data = array('invoice_no' => $invoiceNumber,'transaction_no' => $transaction_no, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'));
                        $this->db->where('pay_txn_id', $user_payment_txn_info[0]['id']);
                        $this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));
                        
                        $attachpath = genarate_exam_invoice($getinvoice_number[0]['invoice_id'],$reg_id);
                        $log_title   = "Exemption Invoice log update  :" . $reg_id;
                        $log_message = serialize($this->db->last_query());
                        storedUserActivity($log_title, $log_message, $reg_id, $reg_id);
                    }

                    $emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'exemption_email'));
                    if(count($emailerstr) > 0)
                    {  
                        /*'to'=>$email,*/
                        $final_str = $emailerstr[0]['emailer_text'];
                       // echo '<pre>';print_r($user_payment_txn_info);
                        $user_data = $this->master_model->getRecords('member_registration',array('regnumber'=>$user_payment_txn_info[0]['member_regnumber']));
                        if (count($user_data)>0) { 
                                $info_arr = array(
                                'to'=>$user_data[0]['email'],
                                'from'=>$emailerstr[0]['from'],
                                'subject'=>$emailerstr[0]['subject'],
                                'message'=>$final_str
                                );

                            if($attachpath!='')
                            { 
                            $this->Emailsending->mailsend_attch($info_arr,$attachpath);
                            }
                        }
                        
                    }
                    
                    
                }

                //Manage Log
                $pg_response = "encData=" . json_encode($responsedata) . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);

                redirect(base_url() . 'exemption/acknowledge/'.base64_encode($MerchantOrderNo).'/'.$custom_reg_id);
                exit();

            } 
            elseif ($auth_status == '0002'  && $user_payment_txn_info[0]['status'] == 2) {
                
                    $update_data = array(
                        'transaction_no'      => $transaction_no,
                        'status'              => 2,
                        'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
                        'auth_code'           => '0300',
                        'bankcode'            => $bankid,
                        'paymode'             => $txn_process_type,
                        'callback'            => 'B2B',
                    );

                    $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
                    $pg_response = "encData=" . json_encode($responsedata) . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                    $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);

                    $this->session->set_flashdata('flsh_msg', 'Transaction under process...!');
                    redirect(base_url() . 'exemption/acknowledge/'.base64_encode($MerchantOrderNo));
            }
            else /* if ($transaction_error_type == 'payment_authorization_error') */
            {
                if ($user_payment_txn_info[0]['status'] != 0 && $user_payment_txn_info[0]['status'] == 2) {
                    $update_data = array(
                        'transaction_no'      => $transaction_no,
                        'status'              => 0,
                        'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
                        'auth_code'           => '0399',
                        'bankcode'            => $bankid,
                        'paymode'             => $txn_process_type,
                        'callback'            => 'B2B',
                    );

                    $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
                    $pg_response = "encData=" . json_encode($responsedata) . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                    $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);

                    $this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
                }
                redirect(base_url() . 'exemption/acknowledge/'.base64_encode($MerchantOrderNo));

            }

        } else {
            die("Please try again...");
        }

    }

    
    public function sbi_make_payment()
    {
        $cgst_rate           = $sgst_rate           = $igst_rate           = $tax_type           = '';
        $cgst_amt            = $sgst_amt            = $igst_amt            = '';
        $cs_total            = $igst_total            = '';
        $total_el_amount     = 0;
        $el_subject_cnt      = 0;
        $total_elearning_amt = 0;
        $total_el_base_amount = 0;
        $total_el_gst_amount  = 0;
        $total_el_cgst_amount = 0;
        $total_el_sgst_amount = 0;
        $total_el_igst_amount = 0;
        $getstate             = $getcenter             = $getfees             = array();
        $valcookie            = applyexam_get_cookie();
        if ($valcookie) {
            redirect(base_url(), 'exemption/process/');
        }

        if (isset($_POST['processPayment']) && $_POST['processPayment']) {
            $pg_name = 'billdesk';
            //checked for application in payment process and prevent user to apply exam on the same time(Prafull)
            $checkpayment = $this->master_model->getRecords('payment_transaction', array('member_regnumber' => $this->session->userdata['examinfo']['regnumber'], 'status' => '2'), '', array('id' => 'DESC'));
            if (count($checkpayment) > 0) {
                $endTime      = date("Y-m-d H:i:s", strtotime("+120 minutes", strtotime($checkpayment[0]['date'])));
                $current_time = date("Y-m-d H:i:s");
                if (strtotime($current_time) <= strtotime($endTime)) {
                    $this->session->set_flashdata('error', 'Your transactions is in process, please try after 2 hrs after your initial transaction.');
                    redirect(base_url() . 'exemption/process');
                }
            }

            $sub_flag = 1;           

            $regno = $this->session->userdata['examinfo']['regnumber'];
            $el_subject_cnt = 0;

            if ($this->config->item('sb_test_mode')) {
                $amount = $this->config->item('exam_apply_fee');
            } else {
                $amount = getExamFee($this->session->userdata['examinfo']['txtCenterCode'], $this->session->userdata['examinfo']['eprid'], $this->session->userdata['examinfo']['excd'], $this->session->userdata['examinfo']['grp_code'], $this->session->userdata('memtype'), $this->session->userdata['examinfo']['elearning_flag']);
                // $amount=$this->session->userdata['examinfo']['fee'];
                
                if (isset($this->session->userdata['examinfo']['el_subject']) && count($this->session->userdata['examinfo']['el_subject']) > 0 && (base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeJaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeDBF') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeSOB') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeCaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == 65)) {
                    $el_amount = get_el_ExamFee($this->session->userdata['examinfo']['txtCenterCode'], $this->session->userdata['examinfo']['eprid'], $this->session->userdata['examinfo']['excd'], $this->session->userdata['examinfo']['grp_code'], $this->session->userdata('memtype'), $this->session->userdata['examinfo']['elearning_flag']);

                    $total_elearning_amt = $el_amount * $el_subject_cnt;
                    $amount              = $amount + $total_elearning_amt;
                    ## New elarning columns code
                    $total_el_base_amount = $el_subject_cnt;
                    $total_el_cgst_amount = $el_subject_cnt;
                    $total_el_sgst_amount = $el_subject_cnt;
                    $total_el_igst_amount = $el_subject_cnt;
                }
            }

            if ($amount == 0 || $amount == '') {
                $this->session->set_flashdata('error', 'Fee can not be zero(0) or Blank!!');
              //  echo'here';exit;
                redirect(base_url() . 'exemption/process/');
            }

            // $MerchantOrderNo    = generate_order_id("sbi_exam_order_id");
            // Ordinary member Apply exam
            //    Ref1 = orderid
            //    Ref2 = iibfexam
            //    Ref3 = member reg num
            //    Ref4 = exam_code + exam year + exam month ex (101201602)
            $yearmonth = $this->master_model->getRecords('misc_master', array(
                'exam_code'   => base64_decode($this->session->userdata['examinfo']['excd']),
                'exam_period' => $this->session->userdata['examinfo']['eprid'],
            ), 'exam_month');
            if (base64_decode($this->session->userdata['examinfo']['excd']) == 340 || base64_decode($this->session->userdata['examinfo']['excd']) == 3400) {
                $exam_code = 34;
            } elseif (base64_decode($this->session->userdata['examinfo']['excd']) == 580 || base64_decode($this->session->userdata['examinfo']['excd']) == 5800) {
                $exam_code = 58;
            } elseif (base64_decode($this->session->userdata['examinfo']['excd']) == 1600 || base64_decode($this->session->userdata['examinfo']['excd']) == 16000) {
                $exam_code = 160;
            } elseif (base64_decode($this->session->userdata['examinfo']['excd']) == 200) {
                $exam_code = 20;
            } elseif (base64_decode($this->session->userdata['examinfo']['excd']) == 1770 || base64_decode($this->session->userdata['examinfo']['excd']) == 17700) {
                $exam_code = 177;
            } elseif (base64_decode($this->session->userdata['examinfo']['excd']) == 590) {
                $exam_code = 59;
            } elseif (base64_decode($this->session->userdata['examinfo']['excd']) == 810) {
                $exam_code = 81;
            } elseif (base64_decode($this->session->userdata['examinfo']['excd']) == 1750) {
                $exam_code = 175;
            } else {
                $exam_code = base64_decode($this->session->userdata['examinfo']['excd']);
            }
            $ref4 = ($exam_code) . $yearmonth[0]['exam_month'];
            // Create transaction
            $insert_data = array(
                'member_regnumber' => $regno,
                'amount'           => $amount,
                'gateway'          => "billdesk",
                'date'             => date('Y-m-d H:i:s'),
                'pay_type'         => $this->config->item('exemption_pay_type'),
                'ref_id'           => $this->session->userdata['examinfo']['insdet_id'],
                'description'      => $this->session->userdata['examinfo']['exname'],
                'status'           => '2',
                'exam_code'        => base64_decode($this->session->userdata['examinfo']['excd']),
                // 'receipt_no'       => $MerchantOrderNo,
                'pg_flag'          => "IIBF_EXAM_O",
                // 'pg_other_details'=>$custom_field
            );
            $pt_id           = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
            $MerchantOrderNo = sbi_exam_order_id($pt_id);
            // payment gateway custom fields -
            $custom_field = $MerchantOrderNo . "^iibfexam^" . $this->session->userdata['examinfo']['regnumber'] . "^" . $ref4;

            $custom_field_billdesk = $MerchantOrderNo . "-iibfexam-" . $this->session->userdata['examinfo']['regnumber'] . "-" . $ref4;
          //  echo $custom_field_billdesk;exit;
            // update receipt no. in payment transaction -
            $update_data = array(
                'receipt_no'       => $MerchantOrderNo,
                'pg_other_details' => $custom_field,
            );
           
            $this->master_model->updateRecord('payment_transaction', $update_data, array(
                'id' => $pt_id,
            ));
            // set invoice details(Prafull)
            $getcenter = $this->master_model->getRecords('center_master', array(
                'exam_name'     => base64_decode($this->session->userdata['examinfo']['excd']),
                'center_code'   => $this->session->userdata['examinfo']['txtCenterCode'],
                'exam_period'   => $this->session->userdata['examinfo']['eprid'],
                'center_delete' => '0',
            ));
            if (count($getcenter) > 0) {
                // get state code,state name,state number.
                $getstate = $this->master_model->getRecords('state_master', array(
                    'state_code'   => $getcenter[0]['state_code'],
                    'state_delete' => '0',
                ));
                // call to helper (fee_helper)
                $getfees = getExamFeedetails($this->session->userdata['examinfo']['txtCenterCode'], $this->session->userdata['examinfo']['eprid'], $this->session->userdata['examinfo']['excd'], $this->session->userdata['examinfo']['grp_code'], $this->session->userdata('memtype'), $this->session->userdata['examinfo']['elearning_flag']);
            }
            if ($getcenter[0]['state_code'] == 'MAH') {
                // set a rate (e.g 9%,9% or 18%)
                $cgst_rate = $this->config->item('cgst_rate');
                $sgst_rate = $this->config->item('sgst_rate');
                if ($this->session->userdata['examinfo']['elearning_flag'] == 'Y') {
                    if (isset($this->session->userdata['examinfo']['el_subject']) && count($this->session->userdata['examinfo']['el_subject']) > 0 && (base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeJaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeDBF') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeSOB') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeCaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == 65)) {
                        $cs_total        = $amount;
                        $total_el_amount = $total_elearning_amt;
                        $amount_base     = $getfees[0]['fee_amount'];
                        $cgst_amt        = $getfees[0]['cgst_amt'];
                        $sgst_amt        = $getfees[0]['sgst_amt'];
                        ## New elarning columns code
                        $total_el_base_amount = $total_el_base_amount * $getfees[0]['elearning_fee_amt'];
                        $total_el_cgst_amount = $total_el_cgst_amount * $getfees[0]['elearning_cgst_amt'];
                        $total_el_sgst_amount = $total_el_sgst_amount * $getfees[0]['elearning_sgst_amt'];
                        $total_el_gst_amount  = $total_el_cgst_amount + $total_el_sgst_amount;
                    } else {
                        $cs_total        = $getfees[0]['elearning_cs_amt_total'];
                        $total_el_amount = 0;
                        $amount_base     = $getfees[0]['elearning_fee_amt'];

                        $cgst_amt             = $getfees[0]['elearning_cgst_amt'];
                        $sgst_amt             = $getfees[0]['elearning_sgst_amt'];
                        $total_el_base_amount = 0;
                        $total_el_gst_amount  = 0;
                    }
                } else {
                    //set an amount as per rate
                    $cgst_amt = $getfees[0]['cgst_amt'];
                    $sgst_amt = $getfees[0]['sgst_amt'];
                    //set an total amount
                    $cs_total             = $getfees[0]['cs_tot'];
                    $amount_base          = $getfees[0]['fee_amount'];
                    $total_el_base_amount = 0;
                    $total_el_gst_amount  = 0;
                }
                $tax_type = 'Intra';
            } else {
                if ($this->session->userdata['examinfo']['elearning_flag'] == 'Y') {

                    $igst_rate = $this->config->item('igst_rate');

                    if (isset($this->session->userdata['examinfo']['el_subject']) && count($this->session->userdata['examinfo']['el_subject']) > 0 && (base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeJaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeDBF') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeSOB') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeCaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == 65)) {
                        $igst_total      = $amount;
                        $total_el_amount = $total_elearning_amt;
                        $amount_base     = $getfees[0]['fee_amount'];
                        $igst_amt        = $getfees[0]['igst_amt'];
                        ## New elarning columns code
                        $total_el_base_amount = $total_el_base_amount * $getfees[0]['elearning_fee_amt'];
                        $total_el_igst_amount = $total_el_igst_amount * $getfees[0]['elearning_igst_amt'];
                        $total_el_gst_amount  = $total_el_igst_amount;
                    } else {
                        $igst_total           = $getfees[0]['elearning_igst_amt_total'];
                        $total_el_amount      = 0;
                        $amount_base          = $getfees[0]['elearning_fee_amt'];
                        $igst_amt             = $getfees[0]['elearning_igst_amt'];
                        $total_el_base_amount = 0;
                        $total_el_gst_amount  = 0;
                    }
                } else {
                    $igst_rate   = $this->config->item('igst_rate');
                    $igst_amt    = $getfees[0]['igst_amt'];
                    $igst_total  = $getfees[0]['igst_tot'];
                    $amount_base = $getfees[0]['fee_amount'];
                    ## Code added on 22 Oct 2021 - chaitali
                    $cgst_rate            = $cgst_amt            = $sgst_rate            = $sgst_amt            = $cs_total            = '';
                    $total_el_base_amount = 0;
                    $total_el_gst_amount  = 0;
                }
                $tax_type = 'Inter';
            }
            if ($getstate[0]['exempt'] == 'E') {
                $cgst_rate = $sgst_rate = $igst_rate = '';
                $cgst_amt  = $sgst_amt  = $igst_amt  = '';
            }
            $gst_no = '0';
            /*if($this->session->userdata['examinfo']['gstin_no']!='')
            {
            $gst_no=$this->session->userdata['examinfo']['gstin_no'];
            }*/
            ## Code added on 22 Oct 2021    - chaitali
            $fee_details = array('state' => $getcenter[0]['state_code'], 'fee_amt' => $amount_base,
                'total_el_amount'            => $total_el_amount,
                'cgst_rate'                  => $cgst_rate,
                'cgst_amt'                   => $cgst_amt,
                'sgst_rate'                  => $sgst_rate,
                'sgst_amt'                   => $sgst_amt,
                'igst_rate'                  => $igst_rate,
                'igst_amt'                   => $igst_amt,
                'cs_total'                   => $cs_total,
                'igst_total'                 => $igst_total);
            $log_title   = "Exam invoice data from home cntrlr before insert array";
            $log_message = serialize($fee_details);
            $rId         = $this->session->userdata['examinfo']['regnumber'];
            $regNo       = $this->session->userdata['examinfo']['regnumber'];
            storedUserActivity($log_title, $log_message, $rId, $regNo);

            $invoice_insert_array = array(
                'pay_txn_id'           => $pt_id,
                'receipt_no'           => $MerchantOrderNo,
                'exam_code'            => base64_decode($this->session->userdata['examinfo']['excd']),
                'center_code'          => $getcenter[0]['center_code'],
                'center_name'          => $getcenter[0]['center_name'],
                'state_of_center'      => $getcenter[0]['state_code'],
                'member_no'            => $this->session->userdata['examinfo']['regnumber'],
                'app_type'             => 'EE',
                'exam_period'          => $this->session->userdata['examinfo']['eprid'],
                'service_code'         => $this->config->item('exam_service_code'),
                'qty'                  => '1',
                'state_code'           => $getstate[0]['state_no'],
                'state_name'           => $getstate[0]['state_name'],
                'tax_type'             => $tax_type,
                'fee_amt'              => $amount_base,
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
            $inser_id    = $this->master_model->insertRecord('exam_invoice', $invoice_insert_array, true);
            $log_title   = "Exam invoice data from home cntrlr last id inser_id = '" . $inser_id . "'";
            $log_message = serialize($invoice_insert_array);
            $rId         = $this->session->userdata['examinfo']['regnumber'];
            $regNo       = $this->session->userdata['examinfo']['regnumber'];
            storedUserActivity($log_title, $log_message, $rId, $regNo);
            // insert into admit card table
            // ################get userdata###########
            $user_info = $this->master_model->getRecords('member_registration', array(
                'regnumber' => $this->session->userdata['examinfo']['regnumber'],
                'isactive'  => '1',
            ));
            // get associate institute details
            $institute_id     = '';
            $institution_name = '';
            if ($user_info[0]['associatedinstitute'] != '') {
                $institution_master = $this->master_model->getRecords('institution_master', array(
                    'institude_id' => $user_info[0]['associatedinstitute'],
                ));
                if (count($institution_master) > 0) {
                    $institute_id     = $institution_master[0]['institude_id'];
                    $institution_name = $institution_master[0]['name'];
                }
            }
            // ############check Gender########
            if ($user_info[0]['gender'] == 'male') {
                $gender = 'M';
            } else {
                $gender = 'F';
            }
            // ########prepare user name########
           // $username         = $user_info[0]['firstname'] . ' ' . $user_info[0]['middlename'] . ' ' . $user_info[0]['lastname'];
            // priyanka -d -08-feb-23 >> commented this
           $username        =   $user_info[0]['displayname']; // priyanka -d -08-feb-23 >> on admitcard it was not showing full name so changed it as asked by tester
            $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
            // ###########get State##########
            $states = $this->master_model->getRecords('state_master', array(
                'state_code'   => $user_info[0]['state'],
                'state_delete' => '0',
            ));
            $state_name = '';
            if (count($states) > 0) {
                $state_name = $states[0]['state_name'];
            }
            // ##############Examination Mode###########
            if ($this->session->userdata['examinfo']['optmode'] == 'ON') {
                $mode = 'Online';
            } else {
                $mode = 'Offline';
            }

            $sub_el_flg = 'N';
         //   echo'<pre>';print_r($this->session->userdata['examinfo']['subject_arr']);exit;
            if (!empty($this->session->userdata['examinfo']['subject_arr'])) {
                foreach ($this->session->userdata['examinfo']['subject_arr'] as $k => $v) {
                    /*    $seat_count=$CI->master_model->getRecords('venue_master',array('exam_date'=>$sel_date,'venue_code'=>$sel_venue,'session_time'=>$sel_time,'center_code'=>$sel_center),'session_capacity');*/
                    $this->db->group_by('subject_code');
                    $compulsory_subjects = $this->master_model->getRecords('subject_master', array(
                        'exam_code'      => base64_decode($this->session->userdata['examinfo']['excd']),
                        'subject_delete' => '0',
                        'exam_period'    => $this->session->userdata['examinfo']['eprid'],
                        'subject_code'   => $k,
                    ), 'subject_description');
                    $get_subject_details = $this->master_model->getRecords('venue_master', array(
                        'venue_code'   => $v['venue'],
                        'exam_date'    => $v['date'],
                        'session_time' => $v['session_time'],
                        'center_code'  => $this->session->userdata['examinfo']['selCenterName']));

                    if (isset($this->session->userdata['examinfo']['el_subject']) && (base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeJaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeDBF') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeSOB') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeCaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == 65)) {

                        if ($this->session->userdata['examinfo']['el_subject'] != 'N') {
                            if (array_key_exists($k, $this->session->userdata['examinfo']['el_subject'])) {
                                $sub_el_flg = 'Y';
                            } else {
                                $sub_el_flg = 'N';
                            }
                        }

                    }
                    $check_last_seat_available = preventUser(base64_decode($this->session->userdata['examinfo']['excd']), $this->session->userdata['examinfo']['selCenterName'], $v['venue'], $v['date'], $v['session_time'], $this->session->userdata['examinfo']['eprid']);
                    if ($check_last_seat_available <= 0) {
                        $msg = 'There is only one seat left for the selected venue which is under the process of allocation to a candidate, You can reapply only if this seat becomes available, Please try after sometime.';
                        $this->session->set_flashdata('error', $msg);
                        redirect(base_url() . 'exemption/process');
                    }

                    $admitcard_insert_array = array(
                        'mem_exam_id'      => $this->session->userdata['examinfo']['insdet_id'],
                        'center_code'      => $getcenter[0]['center_code'],
                        'center_name'      => $getcenter[0]['center_name'],
                        'mem_type'         => $this->session->userdata('memtype'),
                        'mem_mem_no'       => $this->session->userdata['examinfo']['regnumber'],
                        'g_1'              => $gender,
                        'mam_nam_1'        => $userfinalstrname,
                        'mem_adr_1'        => $user_info[0]['address1'],
                        'mem_adr_2'        => $user_info[0]['address2'],
                        'mem_adr_3'        => $user_info[0]['address3'],
                        'mem_adr_4'        => $user_info[0]['address4'],
                        'mem_adr_5'        => $user_info[0]['district'],
                        'mem_adr_6'        => $user_info[0]['city'],
                        'mem_pin_cd'       => $user_info[0]['pincode'],
                        'state'            => $state_name,
                        'exm_cd'           => base64_decode($this->session->userdata['examinfo']['excd']),
                        'exm_prd'          => $this->session->userdata['examinfo']['eprid'],
                        'sub_cd '          => $k,
                        'sub_dsc'          => $compulsory_subjects[0]['subject_description'],
                        'sub_el_flg'       => $sub_el_flg,
                        'm_1'              => $this->session->userdata['examinfo']['medium'],
                        'inscd'            => $institute_id,
                        'insname'          => $institution_name,
                        'venueid'          => $get_subject_details[0]['venue_code'],
                        'venue_name'       => $get_subject_details[0]['venue_name'],
                        'venueadd1'        => $get_subject_details[0]['venue_addr1'],
                        'venueadd2'        => $get_subject_details[0]['venue_addr2'],
                        'venueadd3'        => $get_subject_details[0]['venue_addr3'],
                        'venueadd4'        => $get_subject_details[0]['venue_addr4'],
                        'venueadd5'        => $get_subject_details[0]['venue_addr5'],
                        'venpin'           => $get_subject_details[0]['venue_pincode'],
                        'exam_date'        => $get_subject_details[0]['exam_date'],
                        'time'             => $get_subject_details[0]['session_time'],
                        'mode'             => $mode,
                        'scribe_flag'      => $this->session->userdata['examinfo']['scribe_flag'],
                        'scribe_flag_PwBD' => $this->session->userdata['examinfo']['scribe_flag_d'],
                        'disability'       => $this->session->userdata['examinfo']['disability_value'],
                        'sub_disability'   => $this->session->userdata['examinfo']['Sub_menue_disability'],
                        'vendor_code'      => $get_subject_details[0]['vendor_code'],
                        'remark'           => 2,
                        'created_on'       => date('Y-m-d H:i:s'),
                    );
                    $inser_id    = $this->master_model->insertRecord('admit_card_details', $admitcard_insert_array);
                    $log_title   = "Admit card data from exemption cntrlr";
                    $log_message = serialize($admitcard_insert_array);
                    $rId         = $this->session->userdata['examinfo']['regnumber'];
                    $regNo       = $this->session->userdata['examinfo']['regnumber'];
                    storedUserActivity($log_title, $log_message, $rId, $regNo);
                }

                ##code added to verify if master tables has the raw entries - 2021-10-22 - by chaitali
                $marchant_id = $MerchantOrderNo;
                $exam_code   = base64_decode($this->session->userdata['examinfo']['excd']);
                $member_no   = $this->session->userdata['examinfo']['regnumber'];
                $ref_id      = $this->session->userdata['examinfo']['insdet_id'];

                $payment_raw = $this->master_model->getRecordCount('payment_transaction', array('receipt_no' => $marchant_id, 'exam_code' => $exam_code, 'member_regnumber' => $member_no));

                $exam_invoice_raw = $this->master_model->getRecordCount('exam_invoice', array('receipt_no' => $marchant_id, 'exam_code' => $exam_code, 'member_no' => $member_no));

                $admit_card_raw = $this->master_model->getRecordCount('admit_card_details', array('mem_exam_id' => $ref_id, 'exm_cd' => $exam_code, 'mem_mem_no' => $member_no));

                if ($payment_raw == 0 || $exam_invoice_raw == 0 || $admit_card_raw == 0) {
                    $this->session->set_flashdata('error', 'Something went wrong!!');
                    redirect(base_url() . 'exemption/process');
                }

                ############check for missing subject############
                $this->db->where('app_category !=', 'R');
                $this->db->where('app_category !=', '');
                $this->db->where('exam_status !=', 'V');
                $this->db->where('exam_status !=', 'P');
                $this->db->where('exam_status !=', 'D');
                $check_eligibility_for_applied_exam = $this->master_model->getRecords('eligible_master', array('eligible_master.exam_code' => base64_decode($this->session->userdata['examinfo']['excd']), 'member_no' => $this->session->userdata['examinfo']['regnumber'], 'eligible_period' => $this->session->userdata['examinfo']['eprid']));

                $treatAsFresher=0; //priyanka d- 24-01-23
                if (count($check_eligibility_for_applied_exam) <= 0 || $check_eligibility_for_applied_exam[0]['app_category'] == 'R')
						$treatAsFresher=1;
					else if(isset($this->session->userdata['examinfo']['optval']) && $this->session->userdata['examinfo']['optval']==1)
						$treatAsFresher=1;

                if ($treatAsFresher==1) {//priyanka d- 24-01-23

               // if (count($check_eligibility_for_applied_exam) <= 0 || $check_eligibility_for_applied_exam[0]['app_category'] == 'R') {
                    if (!empty($this->session->userdata['examinfo']['subject_arr'])) {
                        $count = 0;
                        foreach ($this->session->userdata['examinfo']['subject_arr'] as $k => $v) {
                            $check_admit_card_details = $this->master_model->getRecords('admit_card_details', array('mem_mem_no' => $this->session->userdata['examinfo']['regnumber'], 'exm_cd' => base64_decode($this->session->userdata['examinfo']['excd']), 'sub_cd' => $k, 'venueid' => $v['venue'], 'exam_date' => $v['date'], 'time' => $v['session_time'], 'center_code' => $this->session->userdata['examinfo']['selCenterName']));
                            if (count($check_admit_card_details) > 0) {
                                $count++;
                            }
                        }
                    }
                    
                } else {
                    $count = 0;
                    if (count($check_eligibility_for_applied_exam) == count($this->session->userdata['examinfo']['subject_arr'])) {
                        if (!empty($this->session->userdata['examinfo']['subject_arr'])) {
                            foreach ($this->session->userdata['examinfo']['subject_arr'] as $k => $v) {
                                $check_admit_card_details = $this->master_model->getRecords('admit_card_details', array('mem_mem_no' => $this->session->userdata['examinfo']['regnumber'], 'exm_cd' => base64_decode($this->session->userdata['examinfo']['excd']), 'sub_cd' => $k, 'venueid' => $v['venue'], 'exam_date' => $v['date'], 'time' => $v['session_time'], 'center_code' => $this->session->userdata['examinfo']['selCenterName']));
                                if (count($check_admit_card_details) > 0) {
                                    $count++;
                                }
                            }
                        }
                    }
                    if (count($check_eligibility_for_applied_exam) != $count) {
                        $log_title   = "Existing Member subject missing  Home cntrlr";
                        $log_message = serialize($this->session->userdata['examinfo']['subject_arr']);
                        $rId         = $this->session->userdata['examinfo']['regnumber'];
                        $regNo       = $this->session->userdata['examinfo']['regnumber'];
                        storedUserActivity($log_title, $log_message, $rId, $regNo);
                        delete_cookie('examid');
                        $this->session->set_flashdata('error', 'Something went wrong!!');
                        redirect(base_url() . 'exemption/process');
                    }
                }
                ############END check for missing subject############
            } else if($this->session->userdata['examinfo']['exemption']!=1) { // exemption
                if (base64_decode($this->session->userdata['examinfo']['excd']) != 101) {
                    $this->session->set_flashdata('Error', 'Something went wrong!!');
                    redirect(base_url() . 'exemption/process');
                }
            }
            // set cookie for Apply Exam
            applyexam_set_cookie($this->session->userdata['examinfo']['insdet_id']);
            $MerchantCustomerID  = $regno;
            $data["pg_form_url"] = $this->config->item('sbi_pg_form_url'); // SBI ePay form URL
            $data["merchIdVal"]  = $merchIdVal;
            /*
            requestparameter=
            MerchantId | OperatingMode | MerchantCountry | MerchantCurrency |
            PostingAmount | OtherDetails | SuccessURL | FailURL | AggregatorId | MerchantOrderNo |
            MerchantCustomerID | Paymode | Accesmedium | TransactionSource
            Ex.
            requestparameter
            =1000003|DOM|IN|INR|2|Other|https://test.sbiepay.coom/secure/fail.jsp|SBIEPAY|2|2|NB|ONLINE|ONLINE
             */
            if ($pg_name == 'sbi') {
                exit();
                include APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                $key            = $this->config->item('sbi_m_key');
                $merchIdVal     = $this->config->item('sbi_merchIdVal');
                $AggregatorId   = $this->config->item('sbi_AggregatorId');
                $pg_success_url = base_url() . "exemption/sbitranssuccess";
                $pg_fail_url    = base_url() . "exemption/sbitransfail";
                $EncryptTrans   = $merchIdVal . "|DOM|IN|INR|" . $amount . "|" . $custom_field . "|" . $pg_success_url . "|" . $pg_fail_url . "|" . $AggregatorId . "|" . $MerchantOrderNo . "|" . $MerchantCustomerID . "|NB|ONLINE|ONLINE";
                $aes            = new CryptAES();
                $aes->set_key(base64_decode($key));
                $aes->require_pkcs5();
                $EncryptTrans         = $aes->encrypt($EncryptTrans);
                $data["EncryptTrans"] = $EncryptTrans; // SBI encrypted form field value
                $this->load->view('pg_sbi_form', $data);
            } elseif ($pg_name == 'billdesk') {

                $callback_link = 'exemption/exemption_handle_billdesk_response';

                $update_payment_data = array('gateway' => 'billdesk');
                $this->master_model->updateRecord('payment_transaction', $update_payment_data, array('id' => $pt_id));
                $billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $regno, $regno, '', $callback_link, '', '', '', $custom_field_billdesk);
             
                if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE') {
                    $data['bdorderid']      = $billdesk_res['bdorderid'];
                    $data['token']          = $billdesk_res['token'];
                    $data['responseXHRUrl'] = $billdesk_res['responseXHRUrl'];
                    $data['returnUrl']      = $billdesk_res['returnUrl'];
                    $this->load->view('pg_billdesk/pg_billdesk_form', $data);
                } else {
                    $this->session->set_flashdata('error', 'Transaction failed...!');
                    redirect(base_url() . 'exemption/process/' . base64_encode($MerchantOrderNo));
                }
            }

        } else {
            $data['show_billdesk_option_flag'] = 1;
            $this->load->view('pg_sbi/make_payment_page', $data);
        }
    }



    

    // ######## if seat allocation full show message#######
    public function refund($order_no = null)
    {
      
        $payment_info = $this->master_model->getRecords('payment_transaction', array(
            'receipt_no' => base64_decode($order_no),
        ));
        if (count($payment_info) <= 0) {
            redirect(base_url());
        }
        $this->db->where('remark', '2');
        $admit_card_refund = $this->master_model->getRecords('admit_card_details', array(
            'mem_exam_id' => $payment_info[0]['ref_id'],
        ));
        if (count($admit_card_refund) > 0) {
            $update_data = array(
                'remark' => 3,
            );
            $this->master_model->updateRecord('admit_card_details', $update_data, array(
                'mem_exam_id' => $payment_info[0]['ref_id'],
            ));
        }
        $exam_name = $this->master_model->getRecords('exam_master', array(
            'exam_code' => $payment_info[0]['exam_code'],
        ));

        ##adding below code for processing the refund process - added by chaitali on 2021-09-17
        $insert_data = array('receipt_no' => base64_decode($order_no), 'transaction_no' => $payment_info[0]['transaction_no'], 'refund' => '0', 'created_on' => date('Y-m-d'), 'email_flag' => '0', 'sms_flag' => '0');
        $this->master_model->insertRecord('exam_payment_refund', $insert_data);
        //echo $this->db->last_query(); die;
        ## ended insert code

        $data = array(
            'middle_content' => 'member_refund',
            'payment_info'   => $payment_info,
            'exam_name'      => $exam_name,
        );
        $this->load->view('common_view', $data);
    }

    // check user already exam apply or not(Prafull)
    public function examapplied($regnumber = null, $exam_code = null)
    {
       
        $this->db->join('payment_transaction', 'payment_transaction.ref_id =exam_exemptions.id');
       
        $this->db->where('pay_status', '1');
        $this->db->where('pay_type', $this->config->item('exemption_pay_type'));
        $applied_exam_info = $this->master_model->getRecords('exam_exemptions', array(
            'payment_transaction.exam_code' => ($exam_code),
            'regnumber'             => $regnumber,
        ));
      
        return count($applied_exam_info);
       
    }

  
    // #--------- get applied exam name which is fall on same date(Prafull)
    public function get_alredy_applied_examname($regnumber = null, $exam_code = null)
    {
        $flag       = 0;
        $msg        = '';
        $today_date = date('Y-m-d');
        $this->db->select('subject_master.*,exam_master.description');
        $this->db->join('exam_master', 'exam_master.exam_code=subject_master.exam_code');
        $applied_exam_date = $this->master_model->getRecords('subject_master', array(
            'subject_master.exam_code' => base64_decode($exam_code),
            'exam_date >='             => $today_date,
            'subject_delete'           => '0',
        ));
        if (count($applied_exam_date) > 0) {
            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
            $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
            $getapplied_exam_code = $this->master_model->getRecords('member_exam', array(
                'regnumber'  => $regnumber,
                'pay_status' => '1',
            ), 'member_exam.exam_code,exam_master.description');
            // ## checking bulk applied ######
            if (count($getapplied_exam_code) <= 0) {
                $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
                $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
                $this->db->where('bulk_isdelete', '0');
                $this->db->where('institute_id!=', '');
                $getapplied_exam_code = $this->master_model->getRecords('member_exam', array(
                    'regnumber'  => $regnumber,
                    'pay_status' => '2',
                ), 'member_exam.exam_code,exam_master.description');
            }
            if (count($getapplied_exam_code) > 0) {
                foreach ($getapplied_exam_code as $exist_ex_code) {
                    $getapplied_exam_date = $this->master_model->getRecords('subject_master', array(
                        'exam_code'      => $exist_ex_code['exam_code'],
                        'exam_date >='   => $today_date,
                        'subject_delete' => '0',
                    ));
                    if (count($getapplied_exam_date) > 0) {
                        foreach ($getapplied_exam_date as $exist_ex_date) {
                            foreach ($applied_exam_date as $sel_ex_date) {
                                if ($sel_ex_date['exam_date'] == $exist_ex_date['exam_date']) {
                                    $msg  = "You have already applied for <strong>" . $exist_ex_code['description'] . "</strong> falling on same day, So you can not apply for <strong>" . $sel_ex_date['description'] . "</strong> examination.";
                                    $flag = 1;
                                    break;
                                }
                            }
                            if ($flag == 1) {
                                $msg = "You have already applied for <strong>" . $exist_ex_code['description'] . "</strong> falling on same day, So you can not apply for <strong>" . $sel_ex_date['description'] . "</strong> examination.";
                                break;
                            }
                        }
                    }
                    if ($flag == 1) {
                        break;
                    }
                }
            }
        }
        return $msg;
    }


}
