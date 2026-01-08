<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
class Recovery extends CI_Controller{
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
        $this->load->model('ncvet/Ncvet_model');
        $this->load->helper('ncvet/ncvet_helper');
    }
	/* Form View */
    public function index()
	{ 
        $selectedMemberId = '';
        $data = array();
        if (isset($_POST['btnGetDetails'])) 
		{
            $selectedMemberId = $_POST['regnumber'];
            if ($selectedMemberId != '') 
			{
                $checkuser = $this->master_model->getRecords('ncvet_candidates', array('regnumber' => $selectedMemberId, 'regnumber !=' => '', 'is_active !=' => '0'));
                $data['user'] = $checkuser[0];

                $getinvoice_number = $this->master_model->getRecords('ncvet_exam_invoice', array('member_no' => $selectedMemberId));
                
                $userarr = array('selected_invoice_id' => $getinvoice_number[0]['invoice_id'],'regnumber' => $selectedMemberId);
                    $this->session->set_userdata('memberdata', $userarr);
                redirect(base_url() . "ncvet/recovery/make_payment");

            } 
			else 
			{
                $this->session->set_flashdata('flsh_msg', 'The Membership No. field is required.');
                redirect(base_url() . 'ncvet/recovery');
            }
           
        } 
		else 
		{
           
            return $this->load->view('ncvet/recovery', $data);
            
        }
    }
	
    
	
   
	
    /* Make Payment */
    public function make_payment()
    {
        /* Variables */
        $igst_total = $igst_amt = $pay_type = $exam_code = $selected_invoice_id = $regnumber = $new_invoice_id = $custom_field = '';
        $flag = 1;
		$new_invoice_id= $selected_invoice_id = $this->session->userdata['memberdata']['selected_invoice_id']; // Seleted Invoice Id
        $regnumber  = $this->session->userdata['memberdata']['regnumber'];  // Member No
       
		if ($selected_invoice_id == "" || $regnumber == "" ) 
		{
            /* User Log Activities  */
            $log_title   = "Exam Recovery-Session Expired";
            $log_message = 'Program Code : ' . $this->session->userdata['memberdata']['selected_invoice_id'];
           
            $regNo       = $this->session->userdata['memberdata']['regnumber'];
            storedUserActivity($log_title, $log_message, $rId, $regNo); 
			$this->session->set_flashdata('flsh_msg', 'Your session has been expired. Please try again...!');
            redirect(base_url() . 'ncvet/recovery');
        }
		
        if (isset($_POST['processPayment']) && $_POST['processPayment']) 
		{
            $pg_name = 'billdesk';
			$this->db->where('member_no', $regnumber);
            $this->db->where('invoice_id', $new_invoice_id);
            $MemberArray    = $this->master_model->getRecords('ncvet_exam_invoice', '', '','','','1');
            if (!empty($MemberArray)) {
				$cs_total       = $MemberArray[0]['cs_total'];
				$igst_total     = $MemberArray[0]['igst_total'];
                if ($cs_total != "0.00") {
					$amount = $cs_total;
                } else {
					$amount = $igst_total;
				}
				
            } else {
			 	redirect(base_url() . 'ncvet/recovery'); 
			 }
           
            
			$MerchantOrderNo = '10011';
            $custom_field    = $MerchantOrderNo . "^ncvetrec^" . "^iibfrec^" . "^" . $regnumber; 
			
            $billdesk_additional_info    = $MerchantOrderNo . "-ncvetPay" . "-iibfrec" . "-" . $regnumber;

			
            if ($pg_name == 'billdesk') 
						{
					
                $billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $new_invoice_id, $new_invoice_id, '', 'ncvet/recovery/handle_billdesk_response','','','',$billdesk_additional_info);
				//print_r($billdesk_additional_info);
                if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE') {
                    $data['bdorderid'] = $billdesk_res['bdorderid'];
                    $data['token']     = $billdesk_res['token'];
                    $data['responseXHRUrl'] = $billdesk_res['responseXHRUrl']; 
                    $data['returnUrl'] = $billdesk_res['returnUrl']; 
                    $this->load->view('pg_billdesk/pg_billdesk_form', $data);
                }else{
                    $this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
                    redirect(base_url() . 'ncvet/recovery');
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
        $member_regnumber = $this->session->userdata['memberdata']['regnumber'];
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

           $user_info = $this->Master_model->getRecords('ncvet_candidates', array('regnumber' => $member_regnumber, 'isactive' => '1'), 'email_id', '', '', '1');
           $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'ncvet_recovery'));

           $info_arr  = array(
              'to' => $user_info[0]['email_id'],
              
              'from'                  => $emailerstr[0]['from'],
              'subject'               => $emailerstr[0]['subject'],
              'message'               => $emailerstr[0]['emailer_text'],
            );
            $attachpath =array();
            $this->Emailsending->mailsend_attch($info_arr, $attachpath);
            }
         else {
            die("Please try again...");
        }
    }
   
    /* Showing acknowledge after registration */
    public function acknowledge()
	{
        $data = array();
        $this->load->view('ncvet/recovery_ack', $data);
    }
}