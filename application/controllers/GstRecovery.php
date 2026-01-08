<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
class GstRecovery extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('upload');
        $this->load->helper('upload_helper');
        $this->load->helper('master_helper');
        $this->load->helper('general_helper');
        $this->load->helper('gstrecovery_invoice_helper');
        $this->load->model('Master_model');
        $this->load->library('email');
        $this->load->helper('date');
        $this->load->library('email');
        $this->load->model('Emailsending');
        $this->load->model('log_model');
        $this->load->model('billdesk_pg_model');
    }
		
		public function check_login_captcha() 
	{
		$val1 = mysqli_real_escape_string($this->db->conn_id,$this->input->post('val1'));		  
		$val2 = mysqli_real_escape_string($this->db->conn_id,$this->input->post('val2'));		  
		$val3 = mysqli_real_escape_string($this->db->conn_id,$this->input->post('val3'));
		$add_val = ($val1+$val2);
		
		if($val1 == "" || $val2 == "" || $val3 == "" || $add_val != $val3)
		{
			$this->form_validation->set_message('check_login_captcha', 'Please enter correct answer');	
			return FALSE;
		}
		else
		{
			return TRUE;								
		}
	}
	public function check_captcha_examapply($code) 
	{
		if(!isset($this->session->gst_captcha) && empty($this->session->gst_captcha))
		{
			return false;
		}
		
		if($code == '' || $this->session->gst_captcha != $code )
		{
			$this->form_validation->set_message('check_captcha_examapply', 'Invalid %s.'); 
			$this->session->set_userdata("gst_captcha", rand(1,100000));
			return false;
		}
		if($this->session->gst_captcha == $code)
		{
			$this->session->set_userdata('gst_captcha','');
			$this->session->unset_userdata("gst_captcha");
			return true;
		}
	}
	
	
	//##---- reload captcha functionality
	public function generatecaptchaajax()
	{
		/* $this->load->helper('captcha');
		$this->session->unset_userdata("gst_captcha");
		$this->session->set_userdata("gst_captcha", rand(1, 100000));
		$vals = array('img_path' => './uploads/applications/','img_url' => base_url().'uploads/applications/',
					);
		$cap = create_captcha($vals);
		$data = $cap['image'];
		$_SESSION["gst_captcha"] = $cap['word'];
		echo $data; */
		
		$this->load->model('Captcha_model');
		echo $this->Captcha_model->generate_captcha_img('gst_captcha');
	}	
	
	
    public function index()
    { //exit;
        $selectedMemberId = '';
        if (isset($_POST['btnGetDetails'])) 
			{
				$config = array(
				array(
						'field' => 'regnumber',
						'label' => 'Memberno',
						'rules' => 'trim|required|numeric|xss_clean'
				),
				array(
						'field' => 'code',
						'label' => 'Code',
						'rules' => 'trim|required|callback_check_captcha_examapply',
				),
			);
			
			$this->form_validation->set_rules($config);
			$dataarr=array(
					'regnumber'=> mysqli_real_escape_string($this->db->conn_id,$this->security->xss_clean($this->input->post('Username'))),
					'isactive'=>'1',
					'isdeleted'=>'0'
				);
				
				if($this->form_validation->run()==TRUE)
				{
					$selectedMemberId = mysqli_real_escape_string($this->db->conn_id,$this->security->xss_clean($_POST['regnumber']));
					if ($selectedMemberId != '') 
					{
						$MemberArray = $this->validateMember($selectedMemberId);
					} 
					else 
					{
						$this->session->set_flashdata('flsh_msg', 'The Membership No. field is required.');
						redirect(base_url() . 'GstRecovery');
					}
							
					if (!empty($MemberArray)) 
					{
						$data = array('middle_content' => 'GstRecovery/gstrecovery','MemberArray' => $MemberArray);
						$this->load->view('GstRecovery/gstrecovery_common_view', $data);
					}
				} 
			else{ 
					$this->session->set_flashdata('flsh_msg', 'Please enter correct answer.');
                redirect(base_url() . 'GstRecovery'); }
			}
			else 
			{
				$this->load->model('Captcha_model');
				$data['image'] = $this->Captcha_model->generate_captcha_img('gst_captcha');
				
				$data = array('middle_content' => 'GstRecovery/gstrecovery','image'=>$data['image']);
				$this->load->view('GstRecovery/gstrecovery_common_view', $data);
			}
		}
	
    /* Validate Member Function */
    function validateMember($selectedMemberId)
    {
    	$selectedMemberId = mysqli_real_escape_string($this->db->conn_id,$this->security->xss_clean($selectedMemberId));
        $validateQry      = $this->db->query("SELECT member_no FROM gst_recovery_master WHERE member_no = '" . $selectedMemberId . "'  LIMIT 1 ");
		
		if($validateQry->num_rows() > 0)
		{
			$validateMemberNo = $validateQry->row_array();
			$this->db->join('admit_exam_master', 'gst_recovery_master.exam_code = admit_exam_master.exam_code', 'left');
            $this->db->join('member_registration', 'gst_recovery_master.member_no = member_registration.regnumber', 'left');
            $this->db->where('member_registration.isactive', '1');
            $this->db->where('regnumber', $selectedMemberId);
            $MemberArray = $this->master_model->getRecords('gst_recovery_master', '', 'gst_recovery_master_pk,regnumber,firstname,middlename,lastname,email,mobile,igst_amt,pay_type,pay_status,description,fee_amt');
          	
			if (empty($MemberArray)) 
			{
                $this->session->set_flashdata('flsh_msg', 'Please Enter Valid Membership No.');
                redirect(base_url() . 'GstRecovery');
            }
			return $MemberArray;
		}
		else
		{
			$this->session->set_flashdata('flsh_msg', 'You are not eligible member for GST Recovery..!');
            redirect(base_url() . 'GstRecovery');
		}
    }
	
    /* Stored detials after click on Pay Now Button */
    public function stored_details()
    {
        /* Variables */
        $regnumber = $gst_recovery_master_pk = '';
        $gst_recovery_master_pk = base64_decode($this->uri->segment(3));
        
		if ($gst_recovery_master_pk == "") 
		{ 
			redirect(base_url() . 'GstRecovery'); 
		} 
		else 
		{
			$this->db->where('gst_recovery_master_pk', $gst_recovery_master_pk);
            $MemberArray = $this->master_model->getRecords('gst_recovery_master', '', '*','','','1');
            
			if (!empty($MemberArray)) 
			{
                $regnumber   = $MemberArray[0]['member_no'];
                
				/* Stored Details in the  'gst_recovery_details' */
				$insert_info = array(
                    'member_no' => $regnumber,
                    'exam_code' => $MemberArray[0]['exam_code'],
                    'exam_period' => $MemberArray[0]['exam_period'],
                    'pay_type' => $MemberArray[0]['pay_type'],
                    'invoice_no' => $MemberArray[0]['invoice_no'],
                    'date_of_invoice' => $MemberArray[0]['date_of_invoice'],
                    'igst_amt' => $MemberArray[0]['igst_amt'],
                    'state_code' => $MemberArray[0]['state_code'],
                    'state_name' => $MemberArray[0]['state_name'],
                    'pay_status' => $MemberArray[0]['pay_status'],
                    'created_on' => date('Y-m-d H:i:s'),
					'fee_amt' => $MemberArray[0]['fee_amt']
                );
				
                if ($gst_recovery_details_pk = $this->master_model->insertRecord('gst_recovery_details', $insert_info, true))                {
                    /* User Log Activities  */
					if($MemberArray[0]['exam_period'] == '552')
					{
						 $log_title   = "GST Recovery - BCBF Stored Details";
					}
					else
					{
						 $log_title   = "GST Recovery - Stored Details";
					}
                    $log_message = serialize($insert_info);
                    $rId         = $gst_recovery_details_pk;
                    $regNo       = $regnumber;
                    storedUserActivity($log_title, $log_message, $rId, $regNo);
                    
					/* Create User Session */
                    $userarr = array('gst_recovery_details_pk' => $gst_recovery_details_pk,'regnumber' => $regnumber,'gst_recovery_master_pk' => $gst_recovery_master_pk);
					
                    $this->session->set_userdata('memberdata', $userarr);
                    
					/* Go to Make Payment */
                    redirect(base_url() . "GstRecovery/make_payment");
                }
				else 
				{
                    redirect(base_url() . 'GstRecovery');
                }
            } 
			else 
			{
                redirect(base_url() . 'GstRecovery');
            }
        }
    }
	
    /* Make Payment */
    public function make_payment()
    {
        /* Variables */
        $igst_total = $igst_amt = $pay_type = $exam_code = $gst_recovery_details_pk = $regnumber = $gst_recovery_master_pk = $custom_field = '';
        $flag = 1;
       
		$gst_recovery_details_pk = $this->session->userdata['memberdata']['gst_recovery_details_pk'];
        $regnumber               = $this->session->userdata['memberdata']['regnumber'];
        $gst_recovery_master_pk  = $this->session->userdata['memberdata']['gst_recovery_master_pk'];
        
		if ($gst_recovery_details_pk == "" || $regnumber == "" || $gst_recovery_master_pk == "") 
		{
            /* User Log Activities  */
            $log_title   = "GST Recovery-Session Expired";
            $log_message = 'Program Code : ' . $this->session->userdata['memberdata']['gst_recovery_master_pk'];
            $rId         = $this->session->userdata['memberdata']['gst_recovery_details_pk'];
            $regNo       = $this->session->userdata['memberdata']['regnumber'];
            storedUserActivity($log_title, $log_message, $rId, $regNo);
            
			$this->session->set_flashdata('flsh_msg', 'Your session has been expired. Please try again...!');
            redirect(base_url() . 'GstRecovery');
        }
		
        /*if (isset($_POST['processPayment']) && $_POST['processPayment']) 
		{
            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
            $key            = $this->config->item('sbi_m_key');
            $merchIdVal     = $this->config->item('sbi_merchIdVal');
            $AggregatorId   = $this->config->item('sbi_AggregatorId');
            $pg_success_url = base_url() . "GstRecovery/sbitranssuccess";
            $pg_fail_url    = base_url() . "GstRecovery/sbitransfail";
			
			$this->db->where('member_no', $regnumber);
            $this->db->where('gst_recovery_details_pk', $gst_recovery_details_pk);
            $MemberArray    = $this->master_model->getRecords('gst_recovery_details', '', 'gst_recovery_details_pk,igst_amt,exam_code,exam_period,fee_amt','','','1');
            
			 if (!empty($MemberArray)) 
			 {
				 if($MemberArray[0]['exam_period'] == '552')
				 {
					$igst_amt    = $MemberArray[0]['fee_amt'];
					$exam_code       = $MemberArray[0]['exam_code'];
				 }
				 else
				 {
					$igst_amt        = $MemberArray[0]['igst_amt'];
					$exam_code       = $MemberArray[0]['exam_code'];
				 } 
			 }
			 else 
			 { 
			 	redirect(base_url() . 'GstRecovery'); 
			 }
            
			if($MemberArray[0]['exam_period'] == '552')
			{
				$description = "GST Recovery - BCBF";
			}
			else
			{
				$description = "GST Recovery";
			}
			
            $pg_flag         = 'iibfmisc'; // MISCELLANEOUS 
            $insert_data     = array(
                'member_regnumber' => $regnumber,
                'exam_code' => $exam_code,
                'gateway' => "sbiepay",
                'amount' => $igst_amt,
                'date' => date('Y-m-d H:i:s'),
                'ref_id' => $gst_recovery_details_pk,
                'description' => $description,
                'pay_type' => 13,
                'status' => 2,
                'pg_flag' => $pg_flag
            );
			
            $pt_id           = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
            
			$MerchantOrderNo = sbi_exam_order_id($pt_id);
            $custom_field    = $gst_recovery_details_pk . "^" . $pg_flag . "^" . $MerchantOrderNo . "^" . $regnumber;
            
			$update_data     = array('receipt_no' => $MerchantOrderNo,'pg_other_details' => $custom_field);
			$this->master_model->updateRecord('payment_transaction', $update_data, array('id' => $pt_id));
			
            $MerchantCustomerID  = $gst_recovery_details_pk;
            $data["pg_form_url"] = $this->config->item('sbi_pg_form_url');
            $data["merchIdVal"]  = $merchIdVal;
            $EncryptTrans        = $merchIdVal . "|DOM|IN|INR|" . $igst_amt . "|" . $custom_field . "|" . $pg_success_url . "|" . $pg_fail_url . "|" . $AggregatorId . "|" . $MerchantOrderNo . "|" . $MerchantCustomerID . "|NB|ONLINE|ONLINE";
            $aes                 = new CryptAES();
            $aes->set_key(base64_decode($key));
            $aes->require_pkcs5();
            $EncryptTrans         = $aes->encrypt($EncryptTrans);
            $data["EncryptTrans"] = $EncryptTrans;
            $this->load->view('pg_sbi_form', $data);
        } */
        
        if (isset($_POST['processPayment']) && $_POST['processPayment']) 
		{
			$pg_name = $this->input->post('pg_name');
			// $pg_name = 'billdesk';
            //print_r($pg_name); exit;
			$this->db->where('member_no', $regnumber);
            $this->db->where('gst_recovery_details_pk', $gst_recovery_details_pk);
            $MemberArray    = $this->master_model->getRecords('gst_recovery_details', '', 'gst_recovery_details_pk,igst_amt,exam_code,exam_period,fee_amt','','','1');
            
			 if (!empty($MemberArray)) 
			 {
				 if($MemberArray[0]['exam_period'] == '552')
				 {
					$igst_amt    = $MemberArray[0]['fee_amt'];
					$exam_code       = $MemberArray[0]['exam_code'];
				 }
				 else
				 {
					$igst_amt        = $MemberArray[0]['igst_amt'];
					$exam_code       = $MemberArray[0]['exam_code'];
				 } 
			 }
			 else 
			 { 
			 	redirect(base_url() . 'GstRecovery'); 
			 }
            
			if($MemberArray[0]['exam_period'] == '552')
			{
				$description = "GST Recovery - BCBF";
			}
			else
			{
				$description = "GST Recovery";
			}
			
			/* Stored details in the Payment Transaction table */
            $pg_flag         = 'iibf_gst_recovery'; // MISCELLANEOUS 
            if ($pg_name == 'sbi'){
                $gateway='sbiepay';
            }else{
                $gateway='billdesk';
            }
            $insert_data     = array(
                'member_regnumber' => $regnumber,
                'exam_code' => $exam_code,
                'gateway' => $gateway,
                'amount' => $igst_amt,
                'date' => date('Y-m-d H:i:s'),
                'ref_id' => $gst_recovery_details_pk,
                'description' => $description,
                'pay_type' => 13,
                'status' => 2,
                'pg_flag' => $pg_flag
            );
			
            $pt_id           = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
            
			$MerchantOrderNo = sbi_exam_order_id($pt_id);
            $custom_field    = $gst_recovery_details_pk . "^" . $pg_flag . "^" . $MerchantOrderNo . "^" . $regnumber;
            
			$update_data     = array('receipt_no' => $MerchantOrderNo,'pg_other_details' => $custom_field);
			$this->master_model->updateRecord('payment_transaction', $update_data, array('id' => $pt_id));
			
            
			/* This changes made by Pratibha borse Start code 09Feb2022 */
            if ($pg_name == 'sbi'){
                include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
	            $key            = $this->config->item('sbi_m_key');
	            $merchIdVal     = $this->config->item('sbi_merchIdVal');
	            $AggregatorId   = $this->config->item('sbi_AggregatorId');
	            $pg_success_url = base_url() . "GstRecovery/sbitranssuccess";
	            $pg_fail_url    = base_url() . "GstRecovery/sbitransfail";

                $MerchantCustomerID  = $gst_recovery_details_pk;
	            $data["pg_form_url"] = $this->config->item('sbi_pg_form_url');
	            $data["merchIdVal"]  = $merchIdVal;
	            $EncryptTrans        = $merchIdVal . "|DOM|IN|INR|" . $igst_amt . "|" . $custom_field . "|" . $pg_success_url . "|" . $pg_fail_url . "|" . $AggregatorId . "|" . $MerchantOrderNo . "|" . $MerchantCustomerID . "|NB|ONLINE|ONLINE";
	            $aes                 = new CryptAES();
	            $aes->set_key(base64_decode($key));
	            $aes->require_pkcs5();
	            $EncryptTrans         = $aes->encrypt($EncryptTrans);
	            $data["EncryptTrans"] = $EncryptTrans;
	            $this->load->view('pg_sbi_form', $data);
			}elseif ($pg_name == 'billdesk'){
                $custom_field_billdesk    = $gst_recovery_details_pk . "-" . $pg_flag . "-" . $MerchantOrderNo . "-" . $regnumber;
                $billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $igst_amt, $gst_recovery_details_pk, $gst_recovery_details_pk, '', 'GstRecovery/handle_billdesk_response', '', '', '', $custom_field_billdesk);
				// $billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $igst_amt, $gst_recovery_details_pk, $gst_recovery_details_pk, '', 'GstRecovery/handle_billdesk_response','','');
                //print_r($billdesk_res); //exit; Array ( [billdesk_pg_complete_res] => Array ( [headers] => Array ( [alg] => HS256 [clientid] => uatiibfv2 [kid] => HMAC ) [payload] => Array ( [status] => 422 [error_type] => invalid_data_error [error_code] => AIIDE0001 [message] => Invalid additional_info ) ) [status] => 422 )
                if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE') {

                    $data['bdorderid'] = $billdesk_res['bdorderid'];
                    $data['token']     = $billdesk_res['token'];
                    $data['responseXHRUrl'] = $billdesk_res['responseXHRUrl']; 
										$data['returnUrl'] = $billdesk_res['returnUrl'];
                    $this->load->view('pg_billdesk/pg_billdesk_form', $data);
                }else{
                    $this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
                    redirect(base_url() . 'GstRecovery');
                }
			}
			/*  End code */

        } 
		else 
		{
			$data['show_billdesk_option_flag'] = 1;
			$this->load->view('pg_sbi/make_payment_page', $data);
		}
    }
    /* Payment Success And Invoice genrate */
    public function sbitranssuccess()
    {
        $gst_recovery_master_pk = $attachpath = $invoiceNumber = '';
        $gst_recovery_master_pk = $this->session->userdata['memberdata']['gst_recovery_master_pk'];
        
		if (isset($_REQUEST['encData'])) 
		{
            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
            $key = $this->config->item('sbi_m_key');
            $aes = new CryptAES();
            $aes->set_key(base64_decode($key));
            $aes->require_pkcs5();
            $encData         = $aes->decrypt($_REQUEST['encData']);
            $responsedata    = explode("|", $encData);
            // print_r($responsedata); exit;
            $MerchantOrderNo = $responsedata[0];
            $transaction_no  = $responsedata[1];
            $attachpath      = $invoiceNumber = '';
            
			if (isset($_REQUEST['merchIdVal'])){$merchIdVal = $_REQUEST['merchIdVal'];}
            if (isset($_REQUEST['Bank_Code'])){$Bank_Code = $_REQUEST['Bank_Code'];}
            if (isset($_REQUEST['pushRespData'])){$encData = $_REQUEST['pushRespData'];}
            
			$q_details = sbiqueryapi($MerchantOrderNo);
            
			if ($q_details) 
			{
                if ($q_details[2] == "SUCCESS") 
				{
					$get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,member_regnumber,status,id','','','1');
					
					if (empty($get_user_regnum_info)) 
					{ 
						redirect(base_url() . 'GstRecovery');
					}
					
                    if ($get_user_regnum_info[0]['status'] == 2) 
					{
                        $gst_recovery_details_pk = $get_user_regnum_info[0]['ref_id'];
                        $member_regnumber        = $get_user_regnum_info[0]['member_regnumber'];
                        
						$update_data             = array(
                            'transaction_no' => $transaction_no,
                            'status' => 1,
                            'transaction_details' => $responsedata[2] . " - " . $responsedata[7],
                            'auth_code' => '0300',
                            'bankcode' => $responsedata[8],
                            'paymode' => $responsedata[5],
                            'callback' => 'B2B'
                        );
                        $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
					    /* Transaction Log */
                        $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                        $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
                        
						/* Update Pay Status */
                        $gst_recovery_details_data = array('pay_status' => 1,'modified_on' => date('Y-m-d H:i:s'));
                        $this->master_model->updateRecord('gst_recovery_details', $gst_recovery_details_data, array( 'gst_recovery_details_pk' => $gst_recovery_details_pk,'member_no' => $member_regnumber));
                        
						
						
						/* Update Pay Status */
						$gst_recovery_master_data = array('pay_status' => 1,'modified_on' => date('Y-m-d H:i:s'));
						if(isset($gst_recovery_master_pk) && $gst_recovery_master_pk != '')
						{
							 
							$this->master_model->updateRecord('gst_recovery_master', $gst_recovery_master_data, array( 'gst_recovery_master_pk' => $gst_recovery_master_pk,'member_no' => $member_regnumber));
						}
						else{
							
							$get_gst_invoice_no = $this->master_model->getRecords('gst_recovery_details', array('pay_status' => '1','gst_recovery_details_pk' => $gst_recovery_details_pk), 'invoice_no','','','1');
							
							if(count($get_gst_invoice_no) > 0)
							{
								$this->master_model->updateRecord('gst_recovery_master', $gst_recovery_master_data, array( 'invoice_no' => $get_gst_invoice_no[0]['invoice_no'],'member_no' => $member_regnumber));
							}
						}
                       
						
                        /* Email */
						$get_exam_period = $this->master_model->getRecords('gst_recovery_details', array('gst_recovery_details_pk' => $get_user_regnum_info[0]['ref_id']), 'exam_period','','','');

						if($get_exam_period[0]['exam_period'] == '552')
						{
         					$emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'user_bcbf_gst_recovery_email'),'','','1');
						}
						else
						{
							$emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'user_gst_recovery_email'),'','','1');
						}
						
                        if (!empty($member_regnumber)) 
						{
                            $user_info = $this->Master_model->getRecords('member_registration', array('regnumber' => $member_regnumber, 'isactive' => '1'), 'email,mobile','','','1');
                        }
						else 
						{ 
							redirect(base_url() . 'GstRecovery'); 
						}
						
                        if (count($emailerstr) > 0) 
						{ 
							/* Set Email sending options */
                            $info_arr   = array(
                                'to' => $user_info[0]['email'],
                                //'to' => 'esdstesting12@gmail.com',
                                //'to' => 'bhushan.amrutkar@esds.co.in',
                                'from' => $emailerstr[0]['from'],
                                'subject' => $emailerstr[0]['subject'],
                                'message' => $emailerstr[0]['emailer_text']
                            );
							
                            /* Invoice Number Genarate Functinality */
                            if ($gst_recovery_details_pk != '') 
							{
								
								if($get_exam_period[0]['exam_period'] == '552')
				 				{
									$invoiceNumber = "";
									$update_data_invoice = array();
								}
								else
								{
									$invoiceNumber = generate_gst_recovery_invoice_number($gst_recovery_details_pk);
								}
								
								if ($invoiceNumber != '') 
								{
                                    $invoiceNumber = $this->config->item('gst_recovery_prefix') . $invoiceNumber;
									$update_data_invoice = array('doc_no' => $invoiceNumber,'date_of_doc' => date('Y-m-d H:i:s'));
                                $this->db->where('gst_recovery_details_pk', $gst_recovery_details_pk);
                                $this->master_model->updateRecord('gst_recovery_details', $update_data_invoice, array('modified_on' => date('Y-m-d H:i:s')));
                                }
								
								/* Invoice Create Function */
								$get_exam_period = $this->master_model->getRecords('gst_recovery_details', array('gst_recovery_details_pk' => $gst_recovery_details_pk), 'exam_period','','','');

								if($get_exam_period[0]['exam_period'] == '552')
				 				{
									$attachpath  = '';
									$log_title   = "GST Recovery - BCBF - sbitranssuccess function";
									
								}
								else
								{
									$attachpath  = genarate_gst_recovery_invoice($gst_recovery_details_pk);
                                	$log_title   = "GST Recovery-Invoice Genarate";
								}
								
								/* User Log Activities  */
								$log_message = serialize($update_data_invoice);
                                $rId         = $gst_recovery_details_pk;
                                $regNo       = $member_regnumber;
                                storedUserActivity($log_title, $log_message, $rId, $regNo);
                            }
							
                            if ($attachpath != '') 
							{
                                if ($this->Emailsending->mailsend_attch($info_arr, $attachpath)) 
								{
                                    redirect(base_url() . 'GstRecovery/acknowledge/');
                                } 
								else 
								{ 
									redirect(base_url() . 'GstRecovery/acknowledge/');
                                }
                            } 
							else 
							{
								
								if($get_exam_period[0]['exam_period'] == '552')
								{
									
									if ($this->Emailsending->mailsend($info_arr)) 
									{
										$anil_info_arr   = array(  
										'to' => 'anil@iibf.org.in',
										//'to' => 'bhushan.amrutkar@esds.co.in',
										'from' => $emailerstr[0]['from'],
										'subject' => "BCBF 8 Rs Fee Paid",
										'message' => "Member No.".$member_regnumber);
										$this->Emailsending->mailsend($anil_info_arr);
										
										redirect(base_url() . 'GstRecovery/bcbf_acknowledge/');
									} 
									
									
								}
								
                                //redirect(base_url() . 'GstRecovery/acknowledge/');
                            }
							
							
							
                        }
                    }
                }
            }
            //redirect(base_url() . 'GstRecovery/acknowledge/');
        } 
		else 
		{
            die("Please try again...");
        }
    }
    /* Payment Fail */
    public function sbitransfail()
    {
        if (isset($_REQUEST['encData'])) {
            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
            $key = $this->config->item('sbi_m_key');
            $aes = new CryptAES();
            $aes->set_key(base64_decode($key));
            $aes->require_pkcs5();
            $encData              = $aes->decrypt($_REQUEST['encData']);
            $responsedata         = explode("|", $encData);
            $MerchantOrderNo      = $responsedata[0];
            $transaction_no       = $responsedata[1];
            $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,status','','','1');
            if ($get_user_regnum_info[0]['status'] != 0 && $get_user_regnum_info[0]['status'] == 2) {
                if (isset($_REQUEST['merchIdVal'])) { $merchIdVal = $_REQUEST['merchIdVal'];}
                if (isset($_REQUEST['Bank_Code'])) { $Bank_Code = $_REQUEST['Bank_Code']; }
                if (isset($_REQUEST['pushRespData'])) { $encData = $_REQUEST['pushRespData'];}
                $update_data = array(
                    'transaction_no' => $transaction_no,
                    'status' => 0,
                    'transaction_details' => $responsedata[2] . " - " . $responsedata[7],
                    'auth_code' => '0399',
                    'bankcode' => $responsedata[8],
                    'paymode' => $responsedata[5],
                    'callback' => 'B2B'
                );
        		$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
                $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
            }
            $this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
            redirect(base_url() . 'GstRecovery');
            //echo "Transaction failed";
            echo "<script>
                (function (global) {
                
                    if(typeof (global) === 'undefined')
                    {
                        throw new Error('window is undefined');
                    }
                
                    var _hash = '!';
                    var noBackPlease = function () {
                        global.location.href += '#';
                
                        // making sure we have the fruit available for juice....
                        // 50 milliseconds for just once do not cost much (^__^)
                        global.setTimeout(function () {
                            global.location.href += '!';
                        }, 50);
                    };
                    
                    // Earlier we had setInerval here....
                    global.onhashchange = function () {
                        if (global.location.hash !== _hash) {
                            global.location.hash = _hash;
                        }
                    };
                
                    global.onload = function () {
                        
                        noBackPlease();
                
                        // disables backspace on page except on input fields and textarea..
                        document.body.onkeydown = function (e) {
                            var elm = e.target.nodeName.toLowerCase();
                            if (e.which === 8 && (elm !== 'input' && elm  !== 'textarea')) {
                                e.preventDefault();
                            }
                            // stopping event bubbling up the DOM tree..
                            e.stopPropagation();
                        };
                        
                    };
                
                })(window);
                </script>";
            exit;
        } else {
            die("Please try again...");
        }
    }
    /* Showing acknowledge after registration */
    public function acknowledge()
    {
        $data = array('middle_content' => 'GstRecovery/gstrecovery_acknowledge.php');
        $this->load->view('GstRecovery/gstrecovery_common_view', $data);
    }
	
	 public function bcbf_acknowledge()
    {
        $data = array('middle_content' => 'GstRecovery/bcbfrecovery_acknowledge.php');
        $this->load->view('GstRecovery/gstrecovery_common_view', $data);
    }
	
	
	
    public function invoice()
    {	exit;
        echo $path = genarate_gst_recovery_invoice(3723);
		//uploads/gst_recovery_invoice/user/510331950_.jpg
    }		
		
    /* BILLDESK RESPONSE CODE BY PRATIBA BORSE */
    public function handle_billdesk_response_old_pb()
    {
        // print_r($this->session->userdata['memberdata']); exit;

        // 	echo "<pre>"; print_r($_REQUEST['transaction_response']); exit;
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
      	
        $gst_recovery_master_pk = $attachpath = $invoiceNumber = '';
        $gst_recovery_master_pk = $this->session->userdata['memberdata']['gst_recovery_master_pk'];

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


            $get_user_regnum_info   = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,member_regnumber,status,id', '', '', '1');
            if (empty($get_user_regnum_info)) {
                redirect(base_url() . 'GstRecovery');
            }

            if ($get_user_regnum_info[0]['status'] == 2){


                $gst_recovery_details_pk = $get_user_regnum_info[0]['ref_id'];
                $member_regnumber        = $get_user_regnum_info[0]['member_regnumber'];

                $update_data  = array(
                    'transaction_no'      => $transaction_no,
                    'status'              => 1,
                    'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
                    'gateway'             =>'billdesk',
                    'auth_code'           => '0300',
                    'bankcode'            => $bankid,
                    'paymode'             => $txn_process_type,
                    'callback'            => 'B2B',
                );

                $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
                /* Transaction Log */
                $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                // $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
                $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);
                
                /* Update Pay Status */
                $gst_recovery_details_data = array('pay_status' => 1,'modified_on' => date('Y-m-d H:i:s'));
                $this->master_model->updateRecord('gst_recovery_details', $gst_recovery_details_data, array( 'gst_recovery_details_pk' => $gst_recovery_details_pk,'member_no' => $member_regnumber));
                
              
                /* Email */
                $get_exam_period = $this->master_model->getRecords('gst_recovery_details', array('gst_recovery_details_pk' => $gst_recovery_details_pk), 'exam_period,invoice_no','','','');
				
				/* Update Pay Status */
                $gst_recovery_master_data = array('pay_status' => 1,'modified_on' => date('Y-m-d H:i:s'));
                $this->master_model->updateRecord('gst_recovery_master', $gst_recovery_master_data, array('invoice_no'=>$get_exam_period[0]['invoice_no'],'member_no' => $member_regnumber));
				 
                if($get_exam_period[0]['exam_period'] == '552'){
                    $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'user_bcbf_gst_recovery_email'),'','','1');
                }else{
                    $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'user_gst_recovery_email'),'','','1');
                }
                
                if (!empty($member_regnumber)){
                    $user_info = $this->Master_model->getRecords('member_registration', array('regnumber' => $member_regnumber, 'isactive' => '1'), 'email,mobile','','','1');
                }else{ 
                    redirect(base_url() . 'GstRecovery'); 
                }
                
                if (count($emailerstr) > 0){
                    /* Set Email sending options */
                    $info_arr   = array(
                        'to' => $user_info[0]['email'],
                        // 'to' => 'pratibha.borse@esds.co.in',
                        'from' => $emailerstr[0]['from'],
                        'subject' => $emailerstr[0]['subject'],
                        'message' => $emailerstr[0]['emailer_text']
                    );
                    
                    /* Invoice Number Genarate Functinality */
                    if ($gst_recovery_details_pk != ''){
                        
                        if($get_exam_period[0]['exam_period'] == '552'){
                            $invoiceNumber = "";
                            $update_data_invoice = array();
                        }else{
                            $invoiceNumber = generate_gst_recovery_invoice_number($gst_recovery_details_pk);
                        }
                        
                        if ($invoiceNumber != ''){
                            $invoiceNumber = $this->config->item('gst_recovery_prefix') . $invoiceNumber;
                            $update_data_invoice = array('doc_no' => $invoiceNumber,'date_of_doc' => date('Y-m-d H:i:s'));
                            $this->db->where('gst_recovery_details_pk', $gst_recovery_details_pk);
                            $this->master_model->updateRecord('gst_recovery_details', $update_data_invoice, array('modified_on' => date('Y-m-d H:i:s')));
                        }
                        
                        /* Invoice Create Function */

                        if($get_exam_period[0]['exam_period'] == '552'){
                            $attachpath  = '';
                            $log_title   = "GST Recovery - BCBF - sbitranssuccess function";
                        }else{
                            $attachpath  = genarate_gst_recovery_invoice($gst_recovery_details_pk);
                            $log_title   = "GST Recovery-Invoice Genarate";
                        }
                        
                        /* User Log Activities  */
                        $log_message = serialize($update_data_invoice);
                        $rId         = $gst_recovery_details_pk;
                        $regNo       = $member_regnumber;
                        storedUserActivity($log_title, $log_message, $rId, $regNo);
                    }
                    
                    if($attachpath != ''){
                        if($this->Emailsending->mailsend_attch($info_arr, $attachpath)){
                            redirect(base_url() . 'GstRecovery/acknowledge/');
                        }else{ 
                            redirect(base_url() . 'GstRecovery/acknowledge/');
                        }
                    }else{
                        redirect(base_url() . 'GstRecovery/bcbf_acknowledge/');
                    }
                }
            }else if ($transaction_error_type == 'payment_authorization_error') {
                $this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
                redirect(base_url() . 'GstRecovery');
            }
        }else {
            die("Please try again...");
        }

    }

    //Handle billdesk payment gateway with failed case
    public function handle_billdesk_response()
    {

    	// 	echo "<pre>"; print_r($_REQUEST['transaction_response']); exit;
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
      	
        $gst_recovery_master_pk = $attachpath = $invoiceNumber = '';
        $gst_recovery_master_pk = $this->session->userdata['memberdata']['gst_recovery_master_pk'];

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
                redirect(base_url() . 'GstRecovery');
            }

            $qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);
            if($auth_status == '0300' && $qry_api_response['auth_status'] == '0300' && $get_user_regnum_info[0]['status'] == 2)
            {

                $gst_recovery_details_pk = $get_user_regnum_info[0]['ref_id'];
                $member_regnumber        = $get_user_regnum_info[0]['member_regnumber'];

                $update_data  = array(
                    'transaction_no'      => $transaction_no,
                    'status'              => 1,
                    'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
                    'gateway'             =>'billdesk',
                    'auth_code'           => '0300',
                    'bankcode'            => $bankid,
                    'paymode'             => $txn_process_type,
                    'callback'            => 'B2B',
                );

                $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
                /* Transaction Log */
                $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                // $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
                $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);
                
                /* Update Pay Status */
                $gst_recovery_details_data = array('pay_status' => 1,'modified_on' => date('Y-m-d H:i:s'));
                $this->master_model->updateRecord('gst_recovery_details', $gst_recovery_details_data, array( 'gst_recovery_details_pk' => $gst_recovery_details_pk,'member_no' => $member_regnumber));
                
                $get_exam_period = $this->master_model->getRecords('gst_recovery_details', array('gst_recovery_details_pk' => $gst_recovery_details_pk), 'exam_period,invoice_no','','','');
				
				/* Update Pay Status */
                $gst_recovery_master_data = array('pay_status' => 1,'modified_on' => date('Y-m-d H:i:s'));
                $this->master_model->updateRecord('gst_recovery_master', $gst_recovery_master_data, array('invoice_no'=>$get_exam_period[0]['invoice_no'],'member_no' => $member_regnumber));
                
                /* Email */
                $get_exam_period = $this->master_model->getRecords('gst_recovery_details', array('gst_recovery_details_pk' => $gst_recovery_details_pk), 'exam_period','','','');

                if($get_exam_period[0]['exam_period'] == '552'){
                    $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'user_bcbf_gst_recovery_email'),'','','1');
                }else{
                    $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'user_gst_recovery_email'),'','','1');
                }
                
                if (!empty($member_regnumber)){
                    $user_info = $this->Master_model->getRecords('member_registration', array('regnumber' => $member_regnumber, 'isactive' => '1'), 'email,mobile','','','1');
                }else{ 
                    redirect(base_url() . 'GstRecovery'); 
                }
                
                if (count($emailerstr) > 0){
                    /* Set Email sending options */
                    $info_arr   = array(
                        'to' => $user_info[0]['email'],
                        // 'to' => 'pratibha.borse@esds.co.in',
                        'from' => $emailerstr[0]['from'],
                        'subject' => $emailerstr[0]['subject'],
                        'message' => $emailerstr[0]['emailer_text']
                    );
                    
                    /* Invoice Number Genarate Functinality */
                    if ($gst_recovery_details_pk != ''){
                        
                        if($get_exam_period[0]['exam_period'] == '552'){
                            $invoiceNumber = "";
                            $update_data_invoice = array();
                        }else{
                            $invoiceNumber = generate_gst_recovery_invoice_number($gst_recovery_details_pk);
                        }
                        
                        if ($invoiceNumber != ''){
                            $invoiceNumber = $this->config->item('gst_recovery_prefix') . $invoiceNumber;
                            $update_data_invoice = array('doc_no' => $invoiceNumber,'date_of_doc' => date('Y-m-d H:i:s'));
                            $this->db->where('gst_recovery_details_pk', $gst_recovery_details_pk);
                            $this->master_model->updateRecord('gst_recovery_details', $update_data_invoice, array('modified_on' => date('Y-m-d H:i:s')));
                        }
                        
                        /* Invoice Create Function */

                        if($get_exam_period[0]['exam_period'] == '552'){
                            $attachpath  = '';
                            $log_title   = "GST Recovery - BCBF - sbitranssuccess function";
                        }else{
                            $attachpath  = genarate_gst_recovery_invoice($gst_recovery_details_pk);
                            $log_title   = "GST Recovery-Invoice Genarate";
                        }
                        
                        /* User Log Activities  */
                        $log_message = serialize($update_data_invoice);
                        $rId         = $gst_recovery_details_pk;
                        $regNo       = $member_regnumber;
                        storedUserActivity($log_title, $log_message, $rId, $regNo);
                    }
                    
                    if($attachpath != ''){
                        if($this->Emailsending->mailsend_attch($info_arr, $attachpath)){
                            redirect(base_url() . 'GstRecovery/acknowledge/');
                        }else{ 
                            redirect(base_url() . 'GstRecovery/acknowledge/');
                        }
                    }else{
                        redirect(base_url() . 'GstRecovery/bcbf_acknowledge/');
                    }
                }
            }
            elseif ($auth_status == '0002') {
            	$update_data22 = array(
                    'transaction_no' => $transaction_no,
                    'status' => 2,
                    'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
                    'auth_code' => '0002',
                    'bankcode' => $bankid,
                    'paymode' => $txn_process_type,
                    'callback' => 'B2B'
                );

		        		$this->master_model->updateRecord('payment_transaction',$update_data22,array('receipt_no'=>$MerchantOrderNo));
		                $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
		                $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);

			            $this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
			            redirect(base_url() . 'GstRecovery');
            }
						else /* if ($transaction_error_type == 'payment_authorization_error') */
						{
                $update_data22 = array(
                    'transaction_no' => $transaction_no,
                    'status' => 0,
                    'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
                    'auth_code' => '0399',
                    'bankcode' => $bankid,
                    'paymode' => $txn_process_type,
                    'callback' => 'B2B'
                );

        		$this->master_model->updateRecord('payment_transaction',$update_data22,array('receipt_no'=>$MerchantOrderNo));
                $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);

	            $this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
	            redirect(base_url() . 'GstRecovery');
	            //echo "Transaction failed";
	            echo "<script>
	                (function (global) {
	                
	                    if(typeof (global) === 'undefined')
	                    {
	                        throw new Error('window is undefined');
	                    }
	                
	                    var _hash = '!';
	                    var noBackPlease = function () {
	                        global.location.href += '#';
	                
	                        // making sure we have the fruit available for juice....
	                        // 50 milliseconds for just once do not cost much (^__^)
	                        global.setTimeout(function () {
	                            global.location.href += '!';
	                        }, 50);
	                    };
	                    
	                    // Earlier we had setInerval here....
	                    global.onhashchange = function () {
	                        if (global.location.hash !== _hash) {
	                            global.location.hash = _hash;
	                        }
	                    };
	                
	                    global.onload = function () {
	                        
	                        noBackPlease();
	                
	                        // disables backspace on page except on input fields and textarea..
	                        document.body.onkeydown = function (e) {
	                            var elm = e.target.nodeName.toLowerCase();
	                            if (e.which === 8 && (elm !== 'input' && elm  !== 'textarea')) {
	                                e.preventDefault();
	                            }
	                            // stopping event bubbling up the DOM tree..
	                            e.stopPropagation();
	                        };
	                        
	                    };
	                
	                })(window);
	                </script>";
	            exit;

            }
        }else {
            die("Please try again...");
        }

    }
}