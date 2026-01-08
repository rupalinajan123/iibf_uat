<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
class ElearningRecovery extends CI_Controller{
    public function __construct()
	{
        parent::__construct();
        $this->load->library('upload');
        $this->load->helper('upload_helper');
        $this->load->helper('master_helper');
        $this->load->helper('general_helper');
		$this->load->helper('exam_recovery_helper');
        $this->load->model('Master_model');
        $this->load->library('email');
        $this->load->helper('date');
        $this->load->library('email');
        $this->load->model('Emailsending');
        $this->load->model('log_model');
		$this->load->model('billdesk_pg_model');
    }
	/* Form View */
    public function index()
	{ 
        $selectedMemberId = '';
        if (isset($_POST['btnGetDetails'])) 
		{
            $selectedMemberId = $_POST['regnumber'];
            if ($selectedMemberId != '') 
			{
                $MemberArray = $this->validateMember($selectedMemberId);
                $admit_card_details = $this->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$selectedMemberId, 'exm_cd' => 420,'sub_el_flg' => 'Y','exm_prd'=>125,'remark' => 1),'');
            } 
			else 
			{
                $this->session->set_flashdata('flsh_msg', 'The Membership No. field is required.');
                redirect(base_url() . 'ElearningRecovery');
            }
            if (!empty($MemberArray) && !empty($admit_card_details)) 
			{
                $data = array('middle_content' => 'ElearningRecovery/elearningrecovery','MemberArray' => $MemberArray,'admit_card_details'=>$admit_card_details);
                $this->load->view('ElearningRecovery/elearningrecovery_common_view', $data);
            }
        } 
		else 
		{
            $data = array('middle_content' => 'ElearningRecovery/elearningrecovery');
            $this->load->view('ElearningRecovery/elearningrecovery_common_view', $data);
        }
    }
	
    /* Validate Member Function */
    function validateMember($selectedMemberId)
	{
        $validateQry = $this->db->query("SELECT member_no FROM exam_recovery_master WHERE member_no = '" . $selectedMemberId . "'  LIMIT 1 ");
		
		if($validateQry->num_rows() > 0)
		{
			$validateMemberNo = $validateQry->row_array();
			
			$this->db->join('admit_exam_master', 'exam_recovery_master.exam_code = admit_exam_master.exam_code', 'left');
             $this->db->join('member_registration', 'exam_recovery_master.member_no = member_registration.regnumber', 'left');
			$this->db->where('member_no', $selectedMemberId);
            $MemberArray = $this->master_model->getRecords('exam_recovery_master', '', 'exam_recovery_master.*,admit_exam_master.description,regnumber,firstname,middlename,lastname,email,mobile');
          	
			if (empty($MemberArray)) 
			{
                $this->session->set_flashdata('flsh_msg', 'Please Enter Valid Membership No.');
                redirect(base_url() . 'ElearningRecovery');
            }
			return $MemberArray;
		}
		else
		{
			$this->session->set_flashdata('flsh_msg', 'You are not eligible member for Exam Recovery..!');
            redirect(base_url() . 'ElearningRecovery');
		}
    }
	
    /* Stored detials after click on Pay Now Button */
    public function stored_details()
	{
        $regnumber = $invoice_id = $tax_type  = '';
		$cgst_rate = $sgst_rate = $cgst_amt = $sgst_amt = $cs_total  = $igst_rate = $igst_amt = $igst_total = 0;		
        $selected_invoice_id = base64_decode($this->uri->segment(3));
        
		if ($selected_invoice_id == "") 
		{ 
			redirect(base_url() . 'ElearningRecovery'); 
		} 
		else 
		{
			$this->db->where('invoice_id', $selected_invoice_id);
            $examArr = $this->master_model->getRecords('exam_recovery_master', '', '','','','1');
			if (!empty($examArr)) 
			{
				$regnumber   = $examArr[0]['member_no'];
				if($examArr[0]['state_of_center']=='MAH')
				{
					$cgst_rate = $examArr[0]['cgst_rate'];
					$sgst_rate = $examArr[0]['sgst_rate'];
					$cgst_amt  = $examArr[0]['cgst_amt'];
					$sgst_amt  = $examArr[0]['sgst_amt'];
					$cs_total  = $examArr[0]['cs_total'];
					$tax_type  = 'Intra';
				} 
				else 
				{
					$igst_rate  = $examArr[0]['igst_rate'];
					$igst_amt   = $examArr[0]['igst_amt'];
					$igst_total = $examArr[0]['igst_total']; 
					$tax_type   = 'Inter';
				}
					
				/* Stored Details in the Exam Invoice table */
				$invoice_insert_array=array(
					'exam_code' => $examArr[0]['exam_code'],
					'exam_period' => $examArr[0]['exam_period'],
					'center_code' => 0,
					'center_name' => 0,
					'state_of_center' => $examArr[0]['state_of_center'],
					'member_no' => $regnumber,
					'app_type' =>'EL',
					'service_code' =>999799,
					'qty' => '1',
					'state_code' => $examArr[0]['state_code'],
					'state_name' => $examArr[0]['state_name'],
					'tax_type' => $tax_type,
					'fee_amt' => $examArr[0]['fee_amt'],
					'cgst_rate'=>$cgst_rate,
					'cgst_amt'=>$cgst_amt,
					'sgst_rate'=>$sgst_rate,
					'sgst_amt'=>$sgst_amt,
					'igst_rate'=>$igst_rate,
					'igst_amt'=>$igst_amt,
					'cs_total'=>$cs_total,
					'igst_total'=>$igst_total,
					'exempt'=>$examArr[0]['exempt'],
					'created_on'=>date('Y-m-d H:i:s'));
          
                if ($new_invoice_id = $this->master_model->insertRecord('exam_invoice', $invoice_insert_array, true))
				{
                    /* User Log Activities  */
                    $log_title   = "Exam Recovery-Stored Details";
                    $log_message = serialize($invoice_insert_array);
                    $rId         = $selected_invoice_id;
                    $regNo       = $regnumber;
                    storedUserActivity($log_title, $log_message, $rId, $regNo);
                    
					/* Create User Session */
                    $userarr = array('selected_invoice_id' => $selected_invoice_id,'regnumber' => $regnumber,'new_invoice_id' => $new_invoice_id);
                    $this->session->set_userdata('memberdata', $userarr);
                    
					/* Go to Make Payment */
                    redirect(base_url() . "ElearningRecovery/make_payment");
                } 
				else 
				{
                    redirect(base_url() . 'ElearningRecovery');
                }
            } 
			else
			{
                redirect(base_url() . 'ElearningRecovery');
            }
        }
    }
	
    /* Make Payment */
    public function make_payment()
    {
        /* Variables */
        $igst_total = $igst_amt = $pay_type = $exam_code = $selected_invoice_id = $regnumber = $new_invoice_id = $custom_field = '';
        $flag = 1;
		$selected_invoice_id = $this->session->userdata['memberdata']['selected_invoice_id']; // Seleted Invoice Id
        $regnumber  = $this->session->userdata['memberdata']['regnumber'];  // Member No
        $new_invoice_id   = $this->session->userdata['memberdata']['new_invoice_id'];   // New Invoice Id
        
		if ($selected_invoice_id == "" || $regnumber == "" || $new_invoice_id == "") 
		{
            /* User Log Activities  */
            $log_title   = "Exam Recovery-Session Expired";
            $log_message = 'Program Code : ' . $this->session->userdata['memberdata']['selected_invoice_id'];
            $rId         = $this->session->userdata['memberdata']['new_invoice_id'];
            $regNo       = $this->session->userdata['memberdata']['regnumber'];
            storedUserActivity($log_title, $log_message, $rId, $regNo); 
			$this->session->set_flashdata('flsh_msg', 'Your session has been expired. Please try again...!');
            redirect(base_url() . 'ElearningRecovery');
        }
		
        if (isset($_POST['processPayment']) && $_POST['processPayment']) 
		{
            $pg_name = 'billdesk';
			$this->db->where('member_no', $regnumber);
            $this->db->where('invoice_id', $new_invoice_id);
            $MemberArray    = $this->master_model->getRecords('exam_invoice', '', '','','','1');
            if (!empty($MemberArray)) {
				$cs_total       = $MemberArray[0]['cs_total'];
				$igst_total     = $MemberArray[0]['igst_total'];
                if ($cs_total != "0.00") {
					$amount = $cs_total;
                } else {
					$amount = $igst_total;
				}
				$exam_code       = $MemberArray[0]['exam_code'];
            } else {
			 	redirect(base_url() . 'ElearningRecovery'); 
			 }
            $pg_flag         = 'iibfrec'; 
            $insert_data     = array(
                'member_regnumber' => $regnumber,
                'exam_code' => $exam_code,
                'gateway' => "sbiepay",
                'amount' => $amount,
                'date' => date('Y-m-d H:i:s'),
                'ref_id' => $new_invoice_id,
                'description' => "E-learning Recovery",
                'pay_type' => 20,
                'status' => 2,
                'pg_flag' => $pg_flag
            ); 
            /* Stored details in the Payment Transaction table */
            $pt_id           = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
            
			$MerchantOrderNo = sbi_exam_order_id($pt_id);
            $custom_field    = $MerchantOrderNo . "^iibfexam^" . "^iibfrec^" . "^" . $regnumber; 
			
            $billdesk_additional_info    = $MerchantOrderNo . "-iibfexam" . "-iibfrec" . "-" . $regnumber;

			$update_data     = array('receipt_no' => $MerchantOrderNo,'pg_other_details' => $custom_field);
			$this->master_model->updateRecord('payment_transaction', $update_data, array('id' => $pt_id));
            if ($pg_name == 'sbi') 
						{
                            exit();
                include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                $key            = $this->config->item('sbi_m_key');
                $merchIdVal     = $this->config->item('sbi_merchIdVal');
                $AggregatorId   = $this->config->item('sbi_AggregatorId');
                $pg_success_url = base_url() . "ElearningRecovery/sbitranssuccess";
                $pg_fail_url    = base_url() . "ElearningRecovery/sbitransfail";
            //exit;
            $MerchantCustomerID  = $new_invoice_id;
            $data["pg_form_url"] = $this->config->item('sbi_pg_form_url');
            $data["merchIdVal"]  = $merchIdVal;
            $EncryptTrans        = $merchIdVal . "|DOM|IN|INR|" . $amount . "|" . $custom_field . "|" . $pg_success_url . "|" . $pg_fail_url . "|" . $AggregatorId . "|" . $MerchantOrderNo . "|" . $MerchantCustomerID . "|NB|ONLINE|ONLINE";
            $aes                 = new CryptAES();
            $aes->set_key(base64_decode($key));
            $aes->require_pkcs5();
            $EncryptTrans         = $aes->encrypt($EncryptTrans);
            $data["EncryptTrans"] = $EncryptTrans;
                $this->load->view('pg_sbi_form', $data);
            } 
						elseif ($pg_name == 'billdesk') 
						{
							$update_payment_data = array('gateway' =>'billdesk');
							$this->master_model->updateRecord('payment_transaction',$update_payment_data,array('id'=>$pt_id));
							
                $billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $new_invoice_id, $new_invoice_id, '', 'ElearningRecovery/handle_billdesk_response','','','',$billdesk_additional_info);
				//print_r($billdesk_additional_info);
                if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE') {
                    $data['bdorderid'] = $billdesk_res['bdorderid'];
                    $data['token']     = $billdesk_res['token'];
                    $data['responseXHRUrl'] = $billdesk_res['responseXHRUrl']; 
                    $data['returnUrl'] = $billdesk_res['returnUrl']; 
                    $this->load->view('pg_billdesk/pg_billdesk_form', $data);
                }else{
                    $this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
                    redirect(base_url() . 'ElearningRecovery');
                }
            }
        } 
		else
		{
            $data['show_billdesk_option_flag'] = 1;
            $this->load->view('pg_sbi/make_payment_page', $data);
        }
    }

    public function handle_billdesk_response()
    {
  

        $selected_invoice_id = $attachpath = $invoiceNumber = '';
        $selected_invoice_id = $this->session->userdata['memberdata']['selected_invoice_id']; // Seleted Invoice Id

        if (isset($_REQUEST['transaction_response'])) {

            $response_encode = $_REQUEST['transaction_response'];
            $bd_response     = $this->billdesk_pg_model->verify_res($response_encode);

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
            $auth_status = $responsedata['auth_status'];

            $get_user_regnum_info   = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,member_regnumber,status,id', '', '', '1');
            if (empty($get_user_regnum_info)) {
                redirect(base_url() . 'ElearningRecovery');
            }
            $new_invoice_id   = $get_user_regnum_info[0]['ref_id'];
            $member_regnumber = $get_user_regnum_info[0]['member_regnumber'];
            //Query to get Payment details
            $payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $member_regnumber), 'transaction_no,date,amount,id');

            $qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);
            if ($auth_status == '0300' && $qry_api_response['auth_status'] == '0300' && $get_user_regnum_info[0]['status'] == 2) {

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
                $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));

                /* Transaction Log */
                $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);

                /* Update Exam Invoice */

                $exam_invoice_data = array('pay_txn_id' => $payment_info[0]['id'], 'receipt_no' => $MerchantOrderNo, 'modified_on' => date('Y-m-d H:i:s'), 'transaction_no' => $transaction_no);
                $this->master_model->updateRecord('exam_invoice', $exam_invoice_data, array('invoice_id' => $new_invoice_id, 'member_no' => $member_regnumber));
                /* Update Pay Status */
                $exam_recovery_master_data = array('pay_status' => 1, 'modified_on' => date('Y-m-d H:i:s'));

                $this->master_model->updateRecord('exam_recovery_master', $exam_recovery_master_data, array('invoice_id' => $selected_invoice_id, 'member_no' => $member_regnumber));
                /* Email */
                $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'user_exam_recovery_email'), '', '', '1');
                if (!empty($member_regnumber)) {
                    $user_info = $this->Master_model->getRecords('member_registration', array('regnumber' => $member_regnumber, 'isactive' => '1'), 'email,mobile', '', '', '1');
                }
                if (count($emailerstr) > 0) {
                    /* Set Email sending options */
                    $info_arr = array(
                        'to'      => 'iibfdevp@esds.co.in',//$user_info[0]['email'],
                        //'to' => 'esdstesting12@gmail.com',
                        //'to'      => 'vishal.phadol@esds.co.in',
                        //'to' => 'anishrivastava@iibf.org.in',
                        'from'    => $emailerstr[0]['from'],
                        'subject' => $emailerstr[0]['subject'],
                        'message' => $emailerstr[0]['emailer_text'],
                    );
                    /* Invoice Number Genarate Functinality */
                    if ($new_invoice_id != '') {
                        $invoiceNumber = generate_elearning_recovery_invoice_number($new_invoice_id);
                        if ($invoiceNumber != '') {
							//START : THIS CODE WAS UPDATED BY SAGAR M ON 2024-04-16. EARLIER THE INVOICE YEAR FOLLOW THE CALENDER YEAR INSTEAD OF FINANCIAL YEAR
							if(date("Y-m-d") >= date("Y").'-04-01') { $cyear = date("y"); } else { $cyear = date('y') - 1; }
							$nyear = $cyear + 1;
							if($cyear.'-'.$nyear == '24-25' && $invoiceNumber >= 6860) { $invoiceNumber = $invoiceNumber + 3056; }
							$invoiceNumber = 'EL/' . $cyear . '-' . $nyear . '/' . str_pad($invoiceNumber,6,0,STR_PAD_LEFT);
							//END : THIS CODE WAS UPDATED BY SAGAR M ON 2024-04-16. EARLIER THE INVOICE YEAR FOLLOW THE CALENDER YEAR INSTEAD OF FINANCIAL YEAR
                           // $invoiceNumber = $this->config->item('exam_invoice_no_prefix') . $invoiceNumber;
                        }
                        $update_data = array('invoice_no' => $invoiceNumber, 'transaction_no' => $transaction_no, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'));
                        $this->db->where('pay_txn_id', $payment_info[0]['id']);
                        $this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));
                        /* Invoice Create Function */
                        $attachpath = genarate_el_recovery_invoice($new_invoice_id);
                        /* User Log Activities  */
                        $log_title   = "Elearning Recovery-Invoice Genarate";
                        $log_message = serialize($update_data);
                        $rId         = $new_invoice_id;
                        $regNo       = $member_regnumber;
                        storedUserActivity($log_title, $log_message, $rId, $regNo);
                    }
                    if ($attachpath != '') {
                        if ($this->Emailsending->mailsend_attch($info_arr, $attachpath)) {
                            redirect(base_url() . 'ElearningRecovery/acknowledge/');
                        } else {
                            //redirect(base_url() . 'ElearningRecovery/acknowledge/');
                        }
                    } else {
                        //redirect(base_url() . 'ElearningRecovery/acknowledge/');
                    }
                }
            } 
            elseif($auth_status == '0002'){
                $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,status', '', '', '1');
                if ($get_user_regnum_info[0]['status'] == '2') {

                    $update_data22 = array(
                        'transaction_no'      => $transaction_no,
                        'status'              => '2',
                        'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
                        'auth_code'           => '0002',
                        'bankcode'            => $bankid,
                        'paymode'             => $txn_process_type,
                        'callback'            => 'B2B',
                    );
                    $this->master_model->updateRecord('payment_transaction', $update_data22, array('receipt_no' => $MerchantOrderNo));
                    $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                    $this->log_model->logtransaction("billdesk", $pg_response, $responsedata['transaction_error_type']);
                }
                $this->session->set_flashdata('flsh_msg', 'Transaction pending...!');
                redirect(base_url() . 'ElearningRecovery');
            }
            else {

                $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,status', '', '', '1');
                if ($get_user_regnum_info[0]['status'] == '2') {

                    $update_data22 = array(
                        'transaction_no'      => $transaction_no,
                        'status'              => '0',
                        'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
                        'auth_code'           => '0399',
                        'bankcode'            => $bankid,
                        'paymode'             => $txn_process_type,
                        'callback'            => 'B2B',
                    );
                    $this->master_model->updateRecord('payment_transaction', $update_data22, array('receipt_no' => $MerchantOrderNo));
                    $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                    $this->log_model->logtransaction("billdesk", $pg_response, $responsedata['transaction_error_type']);
                }
                $this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
                redirect(base_url() . 'ElearningRecovery');
            }
        } else {
            die("Please try again...");
        }
    }
   
    /* Showing acknowledge after registration */
    public function acknowledge()
	{
        $data = array('middle_content' => 'ElearningRecovery/elearningrecovery_acknowledge.php');
        $this->load->view('ElearningRecovery/elearningrecovery_common_view', $data);
    }
}