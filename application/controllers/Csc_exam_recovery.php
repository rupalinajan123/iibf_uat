<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
class Csc_exam_recovery extends CI_Controller{
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

        $this->load->model('iibfbcbf/Iibf_bcbf_model');
        $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
        $this->load->helper('file'); 
        $this->load->helper('getregnumber_helper');   

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
            } 
			else 
			{
                $this->session->set_flashdata('flsh_msg', 'The Membership No. field is required.');
                redirect(base_url() . 'Csc_exam_recovery');
            }
            if (!empty($MemberArray)) 
			{
                $data = array('middle_content' => 'csc_exam_recovery/examrecovery','MemberArray' => $MemberArray);
                $this->load->view('csc_exam_recovery/examrecovery_common_view', $data);
            }
        } 
		else 
		{
            $data = array('middle_content' => 'csc_exam_recovery/examrecovery');
            $this->load->view('csc_exam_recovery/examrecovery_common_view', $data);
        }
    }
	
    /* Validate Member Function */
    function validateMember($selectedMemberId)
	{
        $validateQry = $this->db->query("SELECT member_no FROM exam_recovery_master WHERE member_no = '" . $selectedMemberId . "'  LIMIT 1 ");
		
		if($validateQry->num_rows() > 0)
		{
			$validateMemberNo = $validateQry->row_array();
			 
             $this->db->join('iibfbcbf_exam_master', 'exam_recovery_master.exam_code = iibfbcbf_exam_master.exam_code', 'left');
             $this->db->join('iibfbcbf_batch_candidates', 'exam_recovery_master.member_no = iibfbcbf_batch_candidates.regnumber', 'left');
			 $this->db->where('member_no', $selectedMemberId);
             $MemberArray = $this->master_model->getRecords('exam_recovery_master', '', 'exam_recovery_master.*,iibfbcbf_exam_master.description,regnumber,first_name AS firstname,middle_name AS middlename,last_name AS lastname,email_id AS email,mobile_no AS mobile');

             if(empty($MemberArray)){
                $this->db->join('exam_master', 'exam_recovery_master.exam_code = exam_master.exam_code', 'left');
                 $this->db->join('member_registration', 'exam_recovery_master.member_no = member_registration.regnumber', 'left');
                 $this->db->where('member_no', $selectedMemberId);
                 $MemberArray = $this->master_model->getRecords('exam_recovery_master', '', 'exam_recovery_master.*,exam_master.description,regnumber,firstname,middlename,lastname,email,mobile');
             }
          	
			if (empty($MemberArray)) 
			{
                $this->session->set_flashdata('flsh_msg', 'Please Enter Valid Membership No.');
                redirect(base_url() . 'Csc_exam_recovery');
            }
			return $MemberArray;
		}
		else
		{
			$this->session->set_flashdata('flsh_msg', 'You are not eligible member for Exam Recovery..!');
            redirect(base_url() . 'Csc_exam_recovery');
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
			redirect(base_url() . 'Csc_exam_recovery'); 
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
					'center_code' => $examArr[0]['center_code'],
					'center_name' => $examArr[0]['center_name'],
					'state_of_center' => $examArr[0]['state_of_center'],
					'member_no' => $regnumber,
					'app_type' =>'K',
					'service_code' =>$this->config->item('exam_service_code'),
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

                    $this->Iibf_bcbf_model->insert_common_log('CSC MAKE PAYMENT', 'exam_invoice', $this->db->last_query(), $new_invoice_id,'csc_make_payment_action','The candidate record successfully inserted in exam invoice table by csc recovery link.', json_encode($invoice_insert_array));

                    $log_title   = "CSC Exam Recovery-Stored Details";
                    $log_message = serialize($invoice_insert_array);
                    $rId         = $selected_invoice_id;
                    $regNo       = $regnumber;
                    storedUserActivity($log_title, $log_message, $rId, $regNo);
                    
					/* Create User Session */
                    $userarr = array('selected_invoice_id' => $selected_invoice_id,'regnumber' => $regnumber,'new_invoice_id' => $new_invoice_id);
                    $this->session->set_userdata('memberdata', $userarr);
                    
					/* Go to Make Payment */
                    redirect(base_url() . "Csc_exam_recovery/make_payment");
                } 
				else 
				{
                    redirect(base_url() . 'Csc_exam_recovery');
                }
            } 
			else
			{
                redirect(base_url() . 'Csc_exam_recovery');
            }
        }
    }
	
    /* Make Payment */
    public function make_payment()
    {
        /* Variables */
        $amount = 0;
        $igst_total = $igst_amt = $pay_type = $exam_code = $selected_invoice_id = $regnumber = $new_invoice_id = $custom_field = '';
        $flag = 1;
		$selected_invoice_id = $this->session->userdata['memberdata']['selected_invoice_id']; // Seleted Invoice Id
        $regnumber  = $this->session->userdata['memberdata']['regnumber'];  // Member No
        $new_invoice_id   = $this->session->userdata['memberdata']['new_invoice_id'];   // New Invoice Id
        
		if ($selected_invoice_id == "" || $regnumber == "" || $new_invoice_id == "") 
		{
            /* User Log Activities  */
            $log_title   = "CSC Exam Recovery-Session Expired";
            $log_message = 'Program Code : ' . $this->session->userdata['memberdata']['selected_invoice_id'];
            $rId         = $this->session->userdata['memberdata']['new_invoice_id'];
            $regNo       = $this->session->userdata['memberdata']['regnumber'];
            storedUserActivity($log_title, $log_message, $rId, $regNo); 
			$this->session->set_flashdata('flsh_msg', 'Your session has been expired. Please try again...!');
            redirect(base_url() . 'Csc_exam_recovery');
        }
		
        if ($new_invoice_id > 0 && $regnumber != "") 
		{
            //print_r($this->session->userdata());die;
			$this->db->where('member_no', $regnumber);
            $this->db->where('invoice_id', $new_invoice_id); 
            $MemberArray    = $this->master_model->getRecords('exam_invoice', '', '','','','1');
            //echo $this->db->last_query();die;
            //echo site_url('CSC_connect/User.php');die;
            if (!empty($MemberArray)) {
				$cs_total       = $MemberArray[0]['cs_total'];
				$igst_total     = $MemberArray[0]['igst_total'];
                if ($cs_total != "0.00") {
					$amount = $cs_total;
                } else {
					$amount = $igst_total;
				}
				$exam_code       = $MemberArray[0]['exam_code'];

                $this->db->where('regnumber', $regnumber); 
                $this->db->join('iibfbcbf_agency_master', 'iibfbcbf_agency_master.agency_id = iibfbcbf_batch_candidates.agency_id', 'left');
                $candidate_data = $this->master_model->getRecords('iibfbcbf_batch_candidates', '', '','','','1');                

            } else {
			 	redirect(base_url() . 'Csc_exam_recovery'); 
			}
            /*$pg_flag         = 'iibfrec'; 
            $insert_data     = array(
                'member_regnumber' => $regnumber,
                'exam_code' => $exam_code,
                'gateway' => "sbiepay",
                'amount' => $amount,
                'date' => date('Y-m-d H:i:s'),
                'ref_id' => $new_invoice_id,
                'description' => "Exam Recovery",
                'pay_type' => 14,
                'status' => 2,
                'pg_flag' => $pg_flag
            );
            /* Stored details in the Payment Transaction table */
            /*$pt_id           = $this->master_model->insertRecord('payment_transaction', $insert_data, true);*/

            $pg_flag = 'iibfrec';
            $utr_no = '';   
            $add_payment_data['amount'] = $amount;
            $add_payment_data['gateway'] = '3';  // 1= NEFT / RTGS, 2=Billdesk, 3=>CSC
            $add_payment_data['UTR_no'] = $utr_no;
             
            $add_payment_data['date'] = date('Y-m-d H:i:s');
            $add_payment_data['pay_count'] =  '1';
            $add_payment_data['exam_code'] =  $MemberArray[0]['exam_code'];
            $add_payment_data['exam_period'] =  $MemberArray[0]['exam_period'];
            $add_payment_data['payment_mode'] =  'CSC';
            $add_payment_data['pg_flag'] =  $pg_flag;
            $add_payment_data['status'] =  '2'; //pending 
            $add_payment_data['description'] =  "CSC Exam Recovery"; 
            $add_payment_data['ip_address'] = get_ip_address(); //general_helper.php 
            $add_payment_data['browser_details'] = $_SERVER['HTTP_USER_AGENT'];
            $add_payment_data['server_ip'] = $_SERVER['SERVER_ADDR'];
            $add_payment_data['created_on'] = date('Y-m-d H:i:s'); 

            $add_payment_data['agency_id'] = $candidate_data[0]['agency_id'];
            $add_payment_data['centre_id'] = $candidate_data[0]['centre_id'];    
            $add_payment_data['agency_code'] = $candidate_data[0]['agency_code'];

            $pt_id = $this->master_model->insertRecord('iibfbcbf_payment_transaction', $add_payment_data, true);
            
            $posted_arr = json_encode($add_payment_data);
            $this->Iibf_bcbf_model->insert_common_log('CSC MAKE PAYMENT', 'iibfbcbf_payment_transaction', $this->db->last_query(), $pt_id,'csc_make_payment_action','The candidate recovery payment data inserted successfully for the exam ('.$MemberArray[0]['exam_code'].' & '.$MemberArray[0]['exam_period'].' by csc recovery link ', $posted_arr);

			//$MerchantOrderNo = sbi_exam_order_id($pt_id);

            $receipt_no = $MerchantOrderNo = $this->master_model->generate_csc_receipt_number();

            $custom_field    = $MerchantOrderNo . "^iibfexam^" . "^iibfrec^" . "^" . $regnumber; 
			
            $billdesk_additional_info    = $MerchantOrderNo . "-iibfexam" . "-iibfrec" . "-" . $regnumber; 

            $update_invoice_data['pay_txn_id'] = $pt_id;
            $update_invoice_data['receipt_no'] = $receipt_no;
            $update_invoice_data['institute_code'] = $candidate_data[0]['agency_code'];
            $update_invoice_data['institute_name'] = $candidate_data[0]['agency_name'];
            $this->master_model->updateRecord('exam_invoice', $update_invoice_data, array('invoice_id' => $new_invoice_id));

            $update_exam_recovery_data['pay_txn_id'] = $pt_id;
            $update_exam_recovery_data['receipt_no'] = $receipt_no; 
            $this->master_model->updateRecord('exam_recovery_master', $update_exam_recovery_data, array('invoice_id' => $selected_invoice_id));

            $custom_field    = $MerchantOrderNo . "^iibfexam^" . "^iibfrec^" . "^" . $regnumber; 
                  
            $this->master_model->updateRecord('iibfbcbf_payment_transaction', array('receipt_no'=>$MerchantOrderNo, 'pg_other_details' => $custom_field), array('id'=>$pt_id));

            if($amount > 0){
                  //START : CSC WALLET PAYMENT CODE   
                    $userarr = array();
                    $userarr['candidate_id'] = $candidate_data[0]['candidate_id'];
                    $userarr['email'] = $candidate_data[0]['email_id'];
                    $userarr['exam_fee'] = $amount;
                    $userarr['exam_desc'] = $MemberArray[0]['exam_code'];
                    $userarr['excode'] = $MemberArray[0]['exam_code'];
                    $userarr['memtype'] = $candidate_data[0]['registration_type'];
                    //$userarr['member_exam_id'] = $member_exam_id;
                    $userarr['pt_id'] = $pt_id;
                    $userarr['receipt_no'] = $receipt_no;
                    $userarr['invoice_id'] = $new_invoice_id;
                    $this->session->set_userdata('non_memberdata', $userarr); 
                    
                    $this->session->set_userdata(array('memtype'=>$candidate_data[0]['registration_type'], 'csctype'=>'iibfbcbf_csc_exam_recovery'));
                    //_pa($_SESSION,1);
  
                    $this->Iibf_bcbf_model->insert_common_log('CSC MAKE PAYMENT', '', '0', '0','csc_make_payment_action','The candidate successfully redirected to csc wallet for recovery payment ', json_encode($_SESSION));
  
                    redirect(site_url('CSC_connect/User.php'));
                  //END : CSC WALLET PAYMENT CODE

            }               
        } 
		else
		{
            $data['show_billdesk_option_flag'] = 1; 
            $this->load->view('csc_exam_recovery/csc_wallet');
        }
    }

    function wallet_make_payment($csc_id=0)
    {       
      //$member_exam_id = $this->session->userdata['non_memberdata']['member_exam_id'];
      $pt_id = $this->session->userdata['non_memberdata']['pt_id'];
      $receipt_no = $this->session->userdata['non_memberdata']['receipt_no'];
      $candidate_id = $this->session->userdata['non_memberdata']['candidate_id'];
      $exam_desc = $this->session->userdata['non_memberdata']['exam_desc'];
      //echo $csc_id;die;
      if(isset($csc_id) && $csc_id > 0)
      {
        if(!$this->session->userdata('csc_id'))
        {
            $this->session->set_userdata('csc_id',$csc_id);
        }

        if (strpos(base_url(), '/staging') !== false) 
        {        
          require_once $_SERVER['DOCUMENT_ROOT'] . '/staging/BridgePG/PHP_BridgePG/BridgePGUtil.php';//STAGING URL        
        } 
        else 
        {        
          require_once $_SERVER['DOCUMENT_ROOT'] . '/BridgePG/PHP_BridgePG/BridgePGUtil.php';//PRODUCTION URL        
        }
        
         
                $rand_no = date('Ymdhims');
                $csc_success_url = site_url('Csc_exam_recovery/csc_transsuccess');
                $csc_fail_url = site_url('Csc_exam_recovery/csc_transfail');
                $csc_product_id = $this->config->item('csc_product_id');
        
        $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction',array('id'=>$pt_id, 'receipt_no'=>$receipt_no, 'status'=>'2'),'id, agency_id, centre_id, exam_ids, exam_code, exam_period, amount, agency_code',array('id'=>'DESC'),'',1);
        
        //$member_exam_data = $this->master_model->getRecords('iibfbcbf_member_exam',array('member_exam_id'=>$member_exam_id, 'pay_status'=>'2'),'member_exam_id, candidate_id, batch_id, exam_fee',array('member_exam_id'=>'DESC'),'',1);
        
        if(count($payment_data) > 0 && $payment_data[0]['amount'] > 0)
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

            $this->wallet_make_payment_fail($candidate_id,$pt_id,$receipt_no);
          }

          $this->session->set_flashdata('error','something went wrong!');
          redirect(site_url('Csc_exam_recovery'));
        }
      }
      else
      {    
        //echo "candidate_id: ".$candidate_id;die;
        $this->Iibf_bcbf_model->insert_common_log('CSC MAKE PAYMENT', '', '', '','csc_make_payment_action','Invalid request 2', '');

        $this->wallet_make_payment_fail($candidate_id,$pt_id,$receipt_no);
        $this->session->set_flashdata('error','something went wrong!!');
        redirect(site_url('Csc_exam_recovery'));
      }
    }

    function wallet_make_payment_fail($candidate_id=0,$pt_id=0,$receipt_no=0)
    {
       

      $up_payment_tra = array();
      $up_payment_tra['status'] = '0';
      $up_payment_tra['description'] = 'CSC Exam Recovery - Error occurred while making payment using csc wallet';
      $up_payment_tra['transaction_details'] = 'Error occurred while making payment using csc wallet';
      $up_payment_tra['ip_address'] = get_ip_address(); //general_helper.php 
      $up_payment_tra['approve_reject_date'] = date('Y-m-d H:i:s');
      $up_payment_tra['updated_on'] = date('Y-m-d H:i:s');
      //$up_payment_tra['updated_by'] = $this->login_agency_or_centre_id;
      $this->master_model->updateRecord('iibfbcbf_payment_transaction', $up_payment_tra, array('id'=>$pt_id, 'receipt_no'=>$receipt_no, 'status'=>'2'));
 
      $this->Iibf_bcbf_model->insert_common_log('Candidate : Candidate payment fail', 'iibfbcbf_batch_candidates', $this->db->last_query(), $candidate_id,'candidate_action','Error occurred while the candidate was attempting to make the recovery payment through the CSC recovery link.', '');
    }

    public function csc_transsuccess()
    {
        if (strpos(base_url(), '/staging') !== false) 
        {        
          require_once $_SERVER['DOCUMENT_ROOT'] . '/staging/BridgePG/PHP_BridgePG/BridgePGUtil.php';//STAGING URL        
        } 
        else 
        {        
          require_once $_SERVER['DOCUMENT_ROOT'] . '/BridgePG/PHP_BridgePG/BridgePGUtil.php';//PRODUCTION URL        
        }

        
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
          $update_data['description'] = 'CSC Exam Recovery - Payment Success By CSC';
          $update_data['approve_reject_date'] = date('Y-m-d H:i:s');
          $update_data['updated_on'] = date('Y-m-d H:i:s');
          $this->master_model->updateRecord('iibfbcbf_payment_transaction', $update_data, array('receipt_no' => $receipt_no, 'status' => '2', 'payment_mode'=>'CSC', 'id'=>$payment_data[0]['id']));
          
          $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK', 'iibfbcbf_payment_transaction', $this->db->last_query(), $payment_data[0]['id'],'csc_payment_callback','The csc wallet payment is success', json_encode($update_data));
          // END : UPDATE PAYMENT SUCCESS STATUS IN PAYMENT TRANSACTION TABLE AND INSERT LOG
        
          // QUERY TO GET UPDATED PAYMENT DETAILS AFTER PAYMENT SUCCESS
          $payment_info = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('receipt_no' => $receipt_no, 'payment_mode'=>'CSC'), 'transaction_no, date, amount, id, description, status, exam_ids, exam_code, exam_period, receipt_no');
    
          if(count($payment_info) > 0 && $payment_info[0]['status'] == '1')
          {

            $this->Iibf_bcbf_model->insert_common_log('Candidate : Candidate payment success', 'iibfbcbf_batch_candidates', '', $member_data[0]['candidate_id'],'candidate_action','The candidate has successfully made the recovery payment through the CSC recovery link.', ''); 
             
            // START : GENERATE INVOICE NUMBER AND INVOICE IMAGE.
            $exam_invoice = $this->master_model->getRecords('exam_invoice',array('pay_txn_id' => $payment_info[0]['id'], 'exam_code'=>$payment_info[0]['exam_code'], 'exam_period'=>$payment_info[0]['exam_period'], 'receipt_no'=>$payment_info[0]['receipt_no'], 'app_type'=>'K'),'invoice_id,member_no');
          
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

              /* Update Pay Status for exam_recovery_master */
              $up_exam_recovery_data['pay_status'] = 1;
              $up_exam_recovery_data['modified_on'] = date('Y-m-d H:i:s');            
              $this->master_model->updateRecord('exam_recovery_master',$up_exam_recovery_data,array('pay_txn_id' => $payment_info[0]['id'], 'receipt_no' => $payment_info[0]['receipt_no']));
              
              $invoice_img_path = genarate_iibf_bcbf_exam_invoice($exam_invoice[0]['invoice_id']); // Use helpers/iibfbcbf/iibf_bcbf_helper.php  

              $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK : update exam invoice number and image', 'exam_invoice', $this->db->last_query(), $exam_invoice[0]['invoice_id'],'csc_payment_callback','The exam invoice number and image is successfully updated in exam invoice table for CSC recovery payment.', json_encode($up_invoice_data));    

                 
                /* User Log Activities  */
                $log_title   = "CSC Exam Recovery-Invoice Genarate";
                $log_message = serialize($up_invoice_data);
                $rId         = $exam_invoice[0]['invoice_id'];
                $regNo       = $exam_invoice[0]['member_no'];
                storedUserActivity($log_title, $log_message, $rId, $regNo);

                /* Email Send for Recovery Payment*/
                $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'user_exam_recovery_email'), '', '', '1');
                if (!empty($exam_invoice[0]['member_no'])) {
                    $user_info = $this->Master_model->getRecords('iibfbcbf_batch_candidates', array('regnumber' => $exam_invoice[0]['member_no'], 'is_deleted' => '0'), 'email_id AS email,mobile_no', '', '', '1');
                }
                if (count($emailerstr) > 0) {
                    /* Set Email sending options */
                    $info_arr = array(
                        'to'      => 'anil.s@esds.co.in', //$user_info[0]['email'],
                        //'to' => 'esdstesting12@gmail.com',
                        //'to'      => 'vishal.phadol@esds.co.in',
                        //'to' => 'anishrivastava@iibf.org.in',
                        'from'    => $emailerstr[0]['from'],
                        'subject' => $emailerstr[0]['subject'],
                        'message' => $emailerstr[0]['emailer_text'],
                    );
                    /* Invoice Number Genarate Functinality */
                    if ($invoice_img_path != '') {
                         
                        $attachpath = $invoice_img_path;
                         
                    }
                    if ($attachpath != '') {
                        if ($this->Emailsending->mailsend_attch($info_arr, $attachpath)) {
                            $this->session->set_flashdata('success','Your transactions is successful.');
                            redirect(base_url() . 'Csc_exam_recovery/acknowledge/');
                        } else {
                            redirect(base_url() . 'Csc_exam_recovery/acknowledge/');
                        }
                    } else {
                        redirect(base_url() . 'Csc_exam_recovery/acknowledge/');
                    }
                } 

            }// END : GENERATE INVOICE NUMBER AND INVOICE IMAGE.
             
          
            //$this->Iibf_bcbf_model->send_transaction_details_email_sms($payment_data[0]['id']);
            
            $this->session->set_flashdata('success','Your transactions is successful.');
            //redirect(site_url('Csc_exam_recovery/acknowledgment/'. url_encode($receipt_no)));
            redirect(site_url('Csc_exam_recovery/acknowledge/'));
          }
          else
          {
            $this->session->set_flashdata('error','Error occurred.');            
            redirect(site_url('Csc_exam_recovery'));
          }
        }
        else
        {
          $this->session->set_flashdata('error','Error occurred.');            
          redirect(site_url('Csc_exam_recovery'));
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
          $update_data['description'] = 'CSC Exam Recovery - Payment Fail By CSC';
          $update_data['approve_reject_date'] = date('Y-m-d H:i:s');
          $update_data['updated_on'] = date('Y-m-d H:i:s');
          $this->master_model->updateRecord('iibfbcbf_payment_transaction', $update_data, array('receipt_no' => $receipt_no, 'status' => '2', 'payment_mode'=>'CSC', 'id'=>$payment_data[0]['id']));
          
          $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK', 'iibfbcbf_payment_transaction', $this->db->last_query(), $payment_data[0]['id'],'csc_payment_callback','The csc wallet payment is fail', json_encode($update_data));
          // END : UPDATE PAYMENT FAIL STATUS IN PAYMENT TRANSACTION TABLE AND INSERT LOG
        
          // QUERY TO GET UPDATED PAYMENT DETAILS AFTER PAYMENT SUCCESS
          $payment_info = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('receipt_no' => $receipt_no, 'payment_mode'=>'CSC'), 'transaction_no, date, amount, id, description, status, exam_ids, exam_code, exam_period, receipt_no');
    
          if(count($payment_info) > 0 && $payment_info[0]['status'] == '0')
          { 
            // START : UPDATE TRANSACTION NUMBER IN INVOICE TABLE
            $exam_invoice = $this->master_model->getRecords('exam_invoice',array('pay_txn_id' => $payment_info[0]['id'], 'exam_code'=>$payment_info[0]['exam_code'], 'exam_period'=>$payment_info[0]['exam_period'], 'receipt_no'=>$payment_info[0]['receipt_no'], 'app_type'=>'K'),'invoice_id');
          
            if(count($exam_invoice) > 0 && $payment_info[0]['amount'] > 0)
            {
              $up_invoice_data['transaction_no'] = $transaction_no;                
              $up_invoice_data['modified_on'] = date('Y-m-d H:i:s');            
              $this->master_model->updateRecord('exam_invoice',$up_invoice_data,array('invoice_id' => $exam_invoice[0]['invoice_id']));
              
              $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK', 'exam_invoice', $this->db->last_query(), $exam_invoice[0]['invoice_id'],'csc_payment_callback','The csc wallet payment is fail and transaction number is updated in exam invoice table for fail payment for CSC Exam Recovery', json_encode($up_invoice_data));
            }// END : UPDATE TRANSACTION NUMBER IN INVOICE TABLE
            
            $this->session->set_flashdata('error','Your transactions is fail.');
            //redirect(site_url('Csc_exam_recovery/acknowledgment/'. url_encode($receipt_no)));
            redirect(site_url('Csc_exam_recovery/acknowledge/'));
          }
          else
          {
            $this->session->set_flashdata('error','Error occurred.');            
            redirect(site_url('Csc_exam_recovery'));
          }
        }
        else
        {
          $this->session->set_flashdata('error','Error occurred.');            
          redirect(site_url('Csc_exam_recovery'));
        }
      }
    }

    public function csc_transfail()
    {
        if (strpos(base_url(), '/staging') !== false) 
        {        
          require_once $_SERVER['DOCUMENT_ROOT'] . '/staging/BridgePG/PHP_BridgePG/BridgePGUtil.php';//STAGING URL        
        } 
        else 
        {        
          require_once $_SERVER['DOCUMENT_ROOT'] . '/BridgePG/PHP_BridgePG/BridgePGUtil.php';//PRODUCTION URL        
        }
        
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
            $update_data['description'] = 'CSC Exam Recovery - Payment Fail By CSC';
            $update_data['approve_reject_date'] = date('Y-m-d H:i:s');
            $update_data['updated_on'] = date('Y-m-d H:i:s');
            $this->master_model->updateRecord('iibfbcbf_payment_transaction', $update_data, array('receipt_no' => $receipt_no, 'status' => '2', 'payment_mode'=>'CSC', 'id'=>$payment_data[0]['id']));
            
            $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK', 'iibfbcbf_payment_transaction', $this->db->last_query(), $payment_data[0]['id'],'csc_payment_callback','The csc wallet payment is fail', json_encode($update_data));
            // END : UPDATE PAYMENT FAIL STATUS IN PAYMENT TRANSACTION TABLE AND INSERT LOG
          
            // QUERY TO GET UPDATED PAYMENT DETAILS AFTER PAYMENT SUCCESS
            $payment_info = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('receipt_no' => $receipt_no, 'payment_mode'=>'CSC'), 'transaction_no, date, amount, id, description, status, exam_ids, exam_code, exam_period, receipt_no');

            if(count($payment_info) > 0 && $payment_info[0]['status'] == '0')
            {  
              // START : UPDATE TRANSACTION NUMBER IN INVOICE TABLE
              $exam_invoice = $this->master_model->getRecords('exam_invoice',array('pay_txn_id' => $payment_info[0]['id'], 'exam_code'=>$payment_info[0]['exam_code'], 'exam_period'=>$payment_info[0]['exam_period'], 'receipt_no'=>$payment_info[0]['receipt_no'], 'app_type'=>'K'),'invoice_id');
            
              if(count($exam_invoice) > 0 && $payment_info[0]['amount'] > 0)
              {
                $up_invoice_data['transaction_no'] = $transaction_no;                
                $up_invoice_data['modified_on'] = date('Y-m-d H:i:s');            
                $this->master_model->updateRecord('exam_invoice',$up_invoice_data,array('invoice_id' => $exam_invoice[0]['invoice_id']));
                
                $this->Iibf_bcbf_model->insert_common_log('CSC PAYMENT CALLBACK', 'exam_invoice', $this->db->last_query(), $exam_invoice[0]['invoice_id'],'csc_payment_callback','The csc wallet payment is fail and transaction number is updated in exam invoice table for fail payment for CSC Exam Recovery', json_encode($up_invoice_data));
              }// END : UPDATE TRANSACTION NUMBER IN INVOICE TABLE

              $this->session->set_flashdata('error','Your transactions is fail.');
              //redirect(site_url('Csc_exam_recovery/acknowledgment/'. url_encode($receipt_no)));
              redirect(site_url('Csc_exam_recovery/acknowledge/'));
            }
            else
            {
              $this->session->set_flashdata('error','Error occurred.');            
              redirect(site_url('Csc_exam_recovery'));
            }
        }
        else
        {
            $this->session->set_flashdata('error','Error occurred.');            
            redirect(site_url('Csc_exam_recovery'));
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
                redirect(base_url() . 'Csc_exam_recovery');
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
                        'to'      => $user_info[0]['email'],
                        //'to' => 'esdstesting12@gmail.com',
                        //'to'      => 'vishal.phadol@esds.co.in',
                        //'to' => 'anishrivastava@iibf.org.in',
                        'from'    => $emailerstr[0]['from'],
                        'subject' => $emailerstr[0]['subject'],
                        'message' => $emailerstr[0]['emailer_text'],
                    );
                    /* Invoice Number Genarate Functinality */
                    if ($new_invoice_id != '') {
                        $invoiceNumber = generate_exam_recovery_invoice_number($new_invoice_id);
                        if ($invoiceNumber != '') {
                            $invoiceNumber = $this->config->item('exam_invoice_no_prefix') . $invoiceNumber;
                        }
                        $update_data = array('invoice_no' => $invoiceNumber, 'transaction_no' => $transaction_no, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'));
                        $this->db->where('pay_txn_id', $payment_info[0]['id']);
                        $this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));
                        /* Invoice Create Function */
                        $attachpath = genarate_exam_recovery_invoice($new_invoice_id);
                        /* User Log Activities  */
                        $log_title   = "Exam Recovery-Invoice Genarate";
                        $log_message = serialize($update_data);
                        $rId         = $new_invoice_id;
                        $regNo       = $member_regnumber;
                        storedUserActivity($log_title, $log_message, $rId, $regNo);
                    }
                    if ($attachpath != '') {
                        if ($this->Emailsending->mailsend_attch($info_arr, $attachpath)) {
                            redirect(base_url() . 'Csc_exam_recovery/acknowledge/');
                        } else {
                            redirect(base_url() . 'Csc_exam_recovery/acknowledge/');
                        }
                    } else {
                        redirect(base_url() . 'Csc_exam_recovery/acknowledge/');
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
                redirect(base_url() . 'Csc_exam_recovery');
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
                redirect(base_url() . 'Csc_exam_recovery');
            }
        } else {
            die("Please try again...");
        }
    }
     
    /* Showing acknowledge after registration */
    public function acknowledge()
	{
        $data = array('middle_content' => 'csc_exam_recovery/examrecovery_acknowledge.php');
        $this->load->view('csc_exam_recovery/examrecovery_common_view', $data);
    }
}