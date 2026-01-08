<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");

class Payment_Recovery extends CI_Controller
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
		//exit;
    }
	//Payment_Recovery/getOrderid
	public function getOrderid()
	{
		$start_time = date("Y-m-d H:i:s");
		//$interval1 = "1 HOUR";
		//$interval2 = "2 HOUR";
		$interval1 = "20 MINUTE";
		$interval2 = "40 MINUTE";
		$all_txn = array();
		$success_txn = array();
		$failure_txn = array();
		$current_date = date("Y-m-d");
		
		
		$sql = "SELECT receipt_no FROM payment_transaction WHERE date >= NOW() - INTERVAL ".$interval2." AND date < NOW() - INTERVAL ".$interval1." AND gateway = 'sbiepay' AND status = 2 AND pay_type = '30' ORDER BY id DESC";
		
		//$sql = "SELECT receipt_no FROM payment_transaction WHERE date BETWEEN '2020-06-26 16:00:00' AND '2020-06-26 18:00:00' AND gateway = 'sbiepay' AND status = 2 AND pay_type = '30' ORDER BY id DESC";
		
		
		$pt_query = $this->db->query($sql);	
		$last_query = $this->db->last_query();
		
		echo $last_query . "<br>";
		//exit;
		if ($pt_query->num_rows())
		{
			$monitoring_log = '';
			
			foreach ($pt_query->result_array() as $row)
			{
				$all_txn[] = $row['receipt_no'];
				$receipt_no = $row['receipt_no'];  // order_no
				
				$q_details = $this->cron_sbiqueryapi($MerchantOrderNo = $receipt_no);
				
				//echo "TEST";
				
				if ($q_details)
				{
					if ($q_details[2] == "SUCCESS")
					{
						$success_txn[] = $row['receipt_no'];
						
						$transaction_no = $q_details[1];
						$status = $q_details[2];//SUCCESS
						$IN = $q_details[3];
						$INR = $q_details[4];
						$other_details = $q_details[5];
						$order_id = $q_details[6];
						$pay_amount = $q_details[7];
						$pay_current_status = $q_details[8];
						$bank_code = $q_details[9];
						//$other_no = $q_details[10];
						$txn_date = $q_details[11];
						$pay_mode = $q_details[12];
						$bank_ref_id = $q_details[13];
						
						
						
						$get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $order_id,'status!='=>1), 'ref_id,member_regnumber,status,id,amount,date','','','1');
						
						//echo $this->db->last_query();
						
						
						//echo "<br><br>".$order_id;
						$member_regnumber = $get_user_regnum_info[0]['member_regnumber'];
						$invoice_id = $get_user_regnum_info[0]['ref_id'];
						
						
						
						
						$new_tbl_check = $this->master_model->getRecords('exam_invoice_without_gst', array('invoice_id' => $invoice_id,'member_no' => $member_regnumber), 'pay_status','','','1');
						
						//echo "<br>".$this->db->last_query();
						
						//exit;
						
						if($new_tbl_check[0]['pay_status'] == 0)
						{
							if($get_user_regnum_info[0]['status'] == 2)
							{		
							
								$insert_array = array('order_id' => $order_id,
								'member_no'=>$member_regnumber,
								'invoice_id'=>$invoice_id,
								'c_date'=>date('y-m-d H:i:s'));
								$this->master_model->insertRecord('recovery_order_id',$insert_array);
								
								$update_data             = array(
								'transaction_no' => $transaction_no,
								'status' => 1,
								'transaction_details' => $status,
								'auth_code' => '0300',
								'bankcode' => $bank_code,
								'paymode' => $pay_mode,
								'callback' => 'B2B'
								);
                        		$this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $order_id));
							
								/* Update Pay Status */
								$gst_recovery_details_data = array('pay_amount' => $pay_amount,'pay_modified_on' => $txn_date,'pay_status' => 1,'pay_txn_date' => $txn_date,'pay_txn_no'=>$transaction_no);
							$this->master_model->updateRecord('exam_invoice_without_gst', $gst_recovery_details_data, array('invoice_id' => $invoice_id,'member_no' => $member_regnumber));
							
								/* Email */
							$emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'rpe_gst_recovery_email'),'','','1');
								
							if (!empty($member_regnumber)) 
							{
								$user_info = $this->Master_model->getRecords('member_registration', array('regnumber' => $member_regnumber, 'isactive' => '1'), 'email,mobile','','','1');
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
								
								$this->Emailsending->mailsend($info_arr); 
								   
							}
							
						//exit;
							}
						}
						
					}
					$pg_response = "".implode("|", $q_details);
					$monitoring_log .= $pg_response . "\n\n";
				}
			}
			$end_time = date("Y-m-d H:i:s");
		}
		else
		{
			echo "No Transaction Found.";	
		}
	}
	
	// SBI ePay API for query transaction
	private function cron_sbiqueryapi($MerchantOrderNo = NULL)
	{
		if($MerchantOrderNo != NULL)
		{
			$merchIdVal = $this->config->item('sbi_merchIdVal');
			$AggregatorId = $this->config->item('sbi_AggregatorId');
			$atrn  = "";
	
			$queryRequest  = $atrn."|".$merchIdVal."|".$MerchantOrderNo;
			
			//echo "<br><br> Webservice URL : ".$service_url = "https://test.sbiepay.com/payagg/orderStatusQuery/getOrderStatusQuery";
			$service_url = $this->config->item('sbi_status_query_api');
			$post_param = "queryRequest=".$queryRequest."&aggregatorId=".$AggregatorId."&merchantId=".$merchIdVal;
	
			$ch = curl_init();       
			curl_setopt($ch, CURLOPT_URL,$service_url);                                                 
			curl_setopt($ch, CURLOPT_POST, true); 
			curl_setopt($ch, CURLOPT_POSTFIELDS,$post_param);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);      
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$result = curl_exec($ch);
			curl_close($ch);
			
			if($result)
			{
				$response_array = explode("|", $result);
				
				return $response_array;
			}
			else
			{
				return 0;
			}
		}
		else
		{
			return 0;
		}
		//print_r($response_array);
		//var_dump($result);   
	}
	
	
	
    public function index1()
    { //exit;
        $selectedMemberId = '';
        if (isset($_POST['btnGetDetails'])) 
		{
            $selectedMemberId = $_POST['member_no'];
            if ($selectedMemberId != '') 
			{
                $MemberArray = $this->validateMember($selectedMemberId);
            } 
			else 
			{
                $this->session->set_flashdata('flsh_msg', 'The Membership No. field is required.');
                redirect(base_url() . 'RPEGstRecovery');
            }
            if (!empty($MemberArray)) 
			{
                $data = array('middle_content' => 'RPEGstRecovery/rpegstrecovery','MemberArray' => $MemberArray);
                $this->load->view('RPEGstRecovery/rpegstrecovery_common_view', $data);
            }
        } 
		else 
		{
            $data = array('middle_content' => 'RPEGstRecovery/rpegstrecovery');
            $this->load->view('RPEGstRecovery/rpegstrecovery_common_view', $data);
        }
    }
	
    /* Validate Member Function */
    function validateMember1($selectedMemberId)
    {
        $validateQry      = $this->db->query("SELECT member_no FROM exam_invoice_without_gst WHERE member_no = '" . $selectedMemberId . "'  LIMIT 1 ");
		
		if($validateQry->num_rows() > 0)
		{
			$validateMemberNo = $validateQry->row_array();
			
			$this->db->join('admit_exam_master', 'exam_invoice_without_gst.exam_code = admit_exam_master.exam_code', 'left');
            $this->db->join('member_registration', 'exam_invoice_without_gst.member_no = member_registration.regnumber', 'left');
            $this->db->where('member_registration.isactive', '1');
            //$this->db->where('exam_invoice_without_gst.pay_status', 0);
            $this->db->where('exam_invoice_without_gst.member_no', $selectedMemberId);
            $MemberArray = $this->master_model->getRecords('exam_invoice_without_gst');
          	
			if (empty($MemberArray)) 
			{
                $this->session->set_flashdata('flsh_msg', 'Please Enter Valid Membership No.');
                redirect(base_url() . 'RPEGstRecovery');
            }
			return $MemberArray;
		}
		else
		{
			$this->session->set_flashdata('flsh_msg', 'You are not eligible member for RPE GST Recovery..!');
            redirect(base_url() . 'RPEGstRecovery');
		}
    }
	
    /* Stored detials after click on Pay Now Button */
    public function stored_details1()
    {
        /* Variables */
        $member_no = $invoice_id = '';
        $invoice_id = base64_decode($this->uri->segment(3));
        
		if ($invoice_id == "") 
		{ 
			redirect(base_url() . 'RPEGstRecovery'); 
		} 
		else 
		{
			$this->db->where('invoice_id', $invoice_id);
            $MemberArray = $this->master_model->getRecords('exam_invoice_without_gst', '', '*','','','1');
            
			if (!empty($MemberArray)) 
			{
                $member_no   = $MemberArray[0]['member_no'];
                
				/* Create User Session */
				$userarr = array('member_no' => $member_no,'invoice_id' => $invoice_id);
				
				$this->session->set_userdata('memberdata', $userarr);
				
				/* Go to Make Payment */
				redirect(base_url() . "RPEGstRecovery/make_payment");
               
            } 
			else 
			{
                redirect(base_url() . 'RPEGstRecovery');
            }
        }
    }
	
    /* Make Payment */
    public function make_payment1()
    {
        /* Variables */
        $igst_total = $igst_amt = $pay_type = $exam_code = $member_no = $invoice_id = $custom_field = '';
        $flag = 1;
       
		$invoice_id = $this->session->userdata['memberdata']['invoice_id'];
        $member_no = $this->session->userdata['memberdata']['member_no'];
        
        
		if ($invoice_id == "" || $member_no == "") 
		{
			$this->session->set_flashdata('flsh_msg', 'Your session has been expired. Please try again...!');
            redirect(base_url() . 'RPEGstRecovery');
        }
		
        if (isset($_POST['processPayment']) && $_POST['processPayment']) 
		{
            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
            $key            = $this->config->item('sbi_m_key');
            $merchIdVal     = $this->config->item('sbi_merchIdVal');
            $AggregatorId   = $this->config->item('sbi_AggregatorId');
            $pg_success_url = base_url() . "RPEGstRecovery/sbitranssuccess";
            $pg_fail_url    = base_url() . "RPEGstRecovery/sbitransfail";
			
			$amt_diff = 0;
			$this->db->where('member_no', $member_no);
			$this->db->where('invoice_id', $invoice_id);
			$MemberArray    = $this->master_model->getRecords('exam_invoice_without_gst');
            
			 if (!empty($MemberArray)) 
			 {
				$amt_diff        = $MemberArray[0]['amt_diff'];
				$exam_code       = $MemberArray[0]['exam_code'];
			 }
			 else 
			 { 
			 	redirect(base_url() . 'RPEGstRecovery'); 
			 }
            
			
				$description = "RPE GST Recovery";
			
			
			/* Stored details in the Payment Transaction table */
            $pg_flag         = 'iibfmisc'; // MISCELLANEOUS 
            $insert_data     = array(
                'member_regnumber' => $member_no,
                'exam_code' => $exam_code,
                'gateway' => "sbiepay",
                'amount' => $amt_diff,
                'date' => date('Y-m-d H:i:s'),
                'ref_id' => $invoice_id,
                'description' => $description,
                'pay_type' => 30,
                'status' => 2,
                'pg_flag' => $pg_flag
            );
			
            $pt_id           = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
            
			$MerchantOrderNo = sbi_exam_order_id($pt_id);
            $custom_field    = $invoice_id . "^" . $pg_flag . "^" . $MerchantOrderNo . "^" . $member_no;
            
			$update_data     = array('receipt_no' => $MerchantOrderNo,'pg_other_details' => $custom_field);
			$this->master_model->updateRecord('payment_transaction', $update_data, array('id' => $pt_id));
			
            $MerchantCustomerID  = $invoice_id;
            $data["pg_form_url"] = $this->config->item('sbi_pg_form_url');
            $data["merchIdVal"]  = $merchIdVal;
            $EncryptTrans        = $merchIdVal . "|DOM|IN|INR|" . $amt_diff . "|" . $custom_field . "|" . $pg_success_url . "|" . $pg_fail_url . "|" . $AggregatorId . "|" . $MerchantOrderNo . "|" . $MerchantCustomerID . "|NB|ONLINE|ONLINE";
            $aes                 = new CryptAES();
            $aes->set_key(base64_decode($key));
            $aes->require_pkcs5();
            $EncryptTrans         = $aes->encrypt($EncryptTrans);
            $data["EncryptTrans"] = $EncryptTrans;
            $this->load->view('pg_sbi_form', $data);
        } 
		else 
		{
            $this->load->view('pg_sbi/make_payment_page');
        }
    }
    /* Payment Success And Invoice genrate */
    public function sbitranssuccess1()
    {
        $invoice_id = $attachpath = $invoiceNumber = '';
        $invoice_id = $this->session->userdata['memberdata']['invoice_id'];
        
		if (isset($_REQUEST['encData'])) 
		{
            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
            $key = $this->config->item('sbi_m_key');
            $aes = new CryptAES();
            $aes->set_key(base64_decode($key));
            $aes->require_pkcs5();
            $encData         = $aes->decrypt($_REQUEST['encData']);
            $responsedata    = explode("|", $encData);
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
					$get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,member_regnumber,status,id,amount,date','','','1');
					
					if (empty($get_user_regnum_info)) 
					{ 
						redirect(base_url() . 'RPEGstRecovery');
					}
					
                    if ($get_user_regnum_info[0]['status'] == 2) 
					{
                        $invoice_id = $get_user_regnum_info[0]['ref_id'];
                        $member_regnumber = $get_user_regnum_info[0]['member_regnumber'];
						$pay_amount = $get_user_regnum_info[0]['amount'];
						$date = $get_user_regnum_info[0]['date'];
						//$pay_txn_no = $get_user_regnum_info[0]['transaction_no'];
                        
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
                        $gst_recovery_details_data = array('pay_amount' => $pay_amount,'pay_modified_on' => date('Y-m-d H:i:s'),'pay_status' => 1,'pay_txn_date' => $date,'pay_txn_no'=>$transaction_no);
                        $this->master_model->updateRecord('exam_invoice_without_gst', $gst_recovery_details_data, array( 'invoice_id' => $invoice_id,'member_no' => $member_regnumber));
                        
                        /* Email */
					$emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'rpe_gst_recovery_email'),'','','1');
						
                        if (!empty($member_regnumber)) 
						{
                            $user_info = $this->Master_model->getRecords('member_registration', array('regnumber' => $member_regnumber, 'isactive' => '1'), 'email,mobile','','','1');
                        }
						else 
						{ 
							redirect(base_url() . 'RPEGstRecovery'); 
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
							
                            
                                if ($this->Emailsending->mailsend($info_arr)) 
								{
                                    redirect(base_url() . 'RPEGstRecovery/acknowledge/');
                                } 
								else 
								{ 
									redirect(base_url() . 'RPEGstRecovery/acknowledge/');
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
    public function sbitransfail1()
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
            redirect(base_url() . 'RPEGstRecovery');
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
    public function acknowledge1()
    {
        $data = array('middle_content' => 'RPEGstRecovery/rpegstrecovery_acknowledge.php');
        $this->load->view('RPEGstRecovery/rpegstrecovery_common_view', $data);
    }
	
}