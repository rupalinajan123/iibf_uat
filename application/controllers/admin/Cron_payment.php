<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cron_payment extends CI_Controller {
	public function __construct(){
		parent::__construct();
		
		$this->load->model('Master_model');
		$this->load->model('log_model');
		$this->load->model('Emailsending');
		error_reporting(E_ALL);
		ini_set("display_errors", 1);
	}
	
	/*
	SBI ePay : payment double verification cron for Member registration and Duplicate ID card
	Algorithm:
		- 1) Select payment_transaction table records where payment gateway is "sbiepay" and status "pending" with datetime is 15 min before
		- 2) Call SBI Query API and check transaction status
		- 3) As per transaction status, update system values
	*/
	public function index()
	{
		$interval = "10 MINUTE";
		//$interval = "1 DAY";
	 	//$pt_query = $this->db->query("SELECT * FROM payment_transaction WHERE date > NOW() - INTERVAL ".$interval." AND gateway = 'sbiepay' AND status = 2 AND (pay_type = '1' OR pay_type = '3') ORDER BY id DESC LIMIT 1");
		
		$pt_query = $this->db->query("SELECT * FROM payment_transaction WHERE date > NOW() - INTERVAL ".$interval." AND gateway = 'sbiepay' AND status = 2 AND (pay_type = '1' OR pay_type = '3') ORDER BY id DESC LIMIT 25");
		
		//$pt_query = $this->db->query("SELECT * FROM payment_transaction WHERE gateway = 'sbiepay' AND status = 2 AND (pay_type = '1') AND receipt_no IN (811878003)");

		
		//echo $this->db->last_query(); exit;
		if ($pt_query->num_rows())
		{
			foreach ($pt_query->result_array() as $row)
			{
				//print_r($row); exit;
				 $receipt_no = $row['receipt_no'];  // order_no
				 $reg_no = $row['ref_id'];  // order_no
				
				$q_details = $this->cron_sbiqueryapi($MerchantOrderNo = $receipt_no);

				if ($q_details)
				{
					if ($q_details[2] == "SUCCESS")
					{
						
						if ($row['pay_type'] == 1)
						{
							$this->update_mem_reg_transaction($MerchantOrderNo, $reg_no, $q_details);
							
						}
						else if ($row['pay_type'] == 3)
						{
							$this->update_id_card_transaction($MerchantOrderNo, $reg_no, $q_details);
						}
					}
					else if ($q_details[2] == "FAIL")
					{
						
						if ($row['pay_type'] == 1)
						{
							$this->update_mem_reg_transaction($MerchantOrderNo, $reg_no, $q_details);
						}
						else if ($row['pay_type'] == 3)
						{
							$this->update_id_card_transaction($MerchantOrderNo, $reg_no, $q_details);
						}
					}
					
					// add query responce in log
					$pg_response = "SBI transaction query responce: ".implode("|", $q_details);
					$this->log_dv_transaction("sbiepay", $pg_response, $q_details[2]);
					sleep(1);
				}
			}
		}
	}
	
	// SBI ePay API for query transaction
	private function cron_sbiqueryapi($MerchantOrderNo = NULL)
	{
		if($MerchantOrderNo!=NULL)
		{
		$merchIdVal = $this->config->item('sbi_merchIdVal');
		$AggregatorId = $this->config->item('sbi_AggregatorId');
		$atrn  = "";

		$queryRequest  = $atrn."|".$merchIdVal."|".$MerchantOrderNo;
		
		//echo "<br><br> Webservice URL : ".$service_url = "https://test.sbiepay.com/payagg/orderStatusQuery/getOrderStatusQuery";
		$service_url = $this->config->item('sbi_status_query_api');
		$post_param = "queryRequest=".$queryRequest."&aggregatorId=".$AggregatorId."&merchantId=".$merchIdVal;

		$ch = curl_init();       
		curl_setopt($ch,CURLOPT_URL,$service_url);                                                 
		curl_setopt($ch, CURLOPT_POST, true); 
		curl_setopt($ch, CURLOPT_POSTFIELDS,$post_param);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);      
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
	
	private function update_mem_reg_transaction($MerchantOrderNo, $reg_no, $responsedata)
	{
		$flag=0;
		if ($responsedata[2] == "SUCCESS")
		{
				$transaction_no=$responsedata[1];
			
				/*$get_user_regnum_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id');
			$reg_id=$get_user_regnum_info[0]['ref_id'];
			
			$applicationNo = generate_mem_reg_num();
			
			$update_data = array('member_regnumber' => $applicationNo,'transaction_no' => $q_details[1],'status' => 1, 'transaction_details' => $q_details[2]." - ".$q_details[7],'auth_code' => '0300', 'bankcode' => $q_details[8], 'paymode' => $q_details[5]);
			$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
				
			if(count($get_user_regnum_info) > 0)
			{
				$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
				$this->master_model->updateRecord('member_registration',$update_mem_data,array('regid'=>$reg_id));
				
				$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$reg_id),'usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,mobile');
				
				
				$upd_files = array();
				$photo_file = 'p_'.$applicationNo.'.jpg';
				$sign_file = 's_'.$applicationNo.'.jpg';
				$proof_file = 'pr_'.$applicationNo.'.jpg';
				
				if(@ rename("./uploads/photograph/".$user_info[0]['scannedphoto'],"./uploads/photograph/".$photo_file))
				{	$upd_files['scannedphoto'] = $photo_file;	}
				
				if(@ rename("./uploads/scansignature/".$user_info[0]['scannedsignaturephoto'],"./uploads/scansignature/".$sign_file))
				{	$upd_files['scannedsignaturephoto'] = $sign_file;	}
				
				if(@ rename("./uploads/idproof/".$user_info[0]['idproofphoto'],"./uploads/idproof/".$proof_file))
				{	$upd_files['idproofphoto'] = $proof_file;	}
				
				if(count($upd_files)>0)
				{
					$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$reg_id));
				}
			}
			
			 
			
			//email to user
			$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'user_register_email'));
			if(count($emailerstr) > 0)
			{
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$key = $this->config->item('pass_key');
				$aes = new CryptAES();
				$aes->set_key(base64_decode($key));
				$aes->require_pkcs5();
				//$encPass = $aes->encrypt(trim($user_info[0]['usrpassword']));
				$decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));
				//$decpass = $aes->decrypt($user_info[0]['usrpassword']);
				$newstring = str_replace("#application_num#", "".$applicationNo."",  $emailerstr[0]['emailer_text']);
				$final_str= str_replace("#password#", "".$decpass."",  $newstring);
				$info_arr=array('to'=>$user_info[0]['email'],
										  'from'=>$emailerstr[0]['from'],
										  'subject'=>$emailerstr[0]['subject'],
										  'message'=>$final_str
										);
				
					$sms_newstring = str_replace("#application_num#", "".$applicationNo."",  $emailerstr[0]['sms_text']);
					$sms_final_str= str_replace("#password#", "".$decpass."",  $sms_newstring);
					$this->master_model->send_sms($user_info[0]['mobile'],$sms_final_str);
					//$this->master_model->send_sms_trustsignal(intval($user_info[0]['mobile']),$sms_final_str,'DPDoOIwMR'); 
													
					if($this->Emailsending->mailsend($info_arr))
					{
						
					//$this->session->set_flashdata('success','Your Application has been Submitted Successfully !!');
						//redirect(base_url('register/acknowledge/'));
				}
				else
				{
						echo 'Error while sending email';
						//$this->session->set_flashdata('error','Error while sending email !!');
						//redirect(base_url('register/preview/'));
				}
			}
			else
			{
				//$this->session->set_flashdata('error','Error while sending email !!');
				//redirect(base_url('register/preview/'));
			}*/
				
				//New payment transaction code for double verification	
				$get_user_regnum_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status');
			
				$reg_id=$get_user_regnum_info[0]['ref_id'];
				
				$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$reg_id),'usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,mobile,registrationtype');
			
				$checkuserexist=$this->master_model->getRecords('member_registration',array('email'=>$user_info[0]['email'],'mobile'=>$user_info[0]['mobile'],'registrationtype'=>$user_info[0]['registrationtype']));
				if(count($checkuserexist) > 0)
				{
					foreach($checkuserexist as $row)
					{
						if($row['isactive']==1)
						{
							$flag=1;
						}						
					}
				}
			if($flag==0)
			{
				//$applicationNo = generate_mem_reg_num();
				$applicationNo =generate_O_memreg($reg_id);
				$update_data = array('member_regnumber' => $applicationNo,'transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[8],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' => $responsedata[12],'callback'=>'DV');
				$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
					
				/*$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber');*/
				
				if(count($get_user_regnum_info) > 0)
				{
					$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
					$this->master_model->updateRecord('member_registration',$update_mem_data,array('regid'=>$reg_id));
					
					$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$reg_id),'usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,mobile');
					
					$upd_files = array();
					$photo_file = 'p_'.$applicationNo.'.jpg';
					$sign_file = 's_'.$applicationNo.'.jpg';
					$proof_file = 'pr_'.$applicationNo.'.jpg';
					
					if(@ rename("./uploads/photograph/".$user_info[0]['scannedphoto'],"./uploads/photograph/".$photo_file))
					{	$upd_files['scannedphoto'] = $photo_file;	}
					
					if(@ rename("./uploads/scansignature/".$user_info[0]['scannedsignaturephoto'],"./uploads/scansignature/".$sign_file))
					{	$upd_files['scannedsignaturephoto'] = $sign_file;	}
					
					if(@ rename("./uploads/idproof/".$user_info[0]['idproofphoto'],"./uploads/idproof/".$proof_file))
					{	$upd_files['idproofphoto'] = $proof_file;	}
					
					if(count($upd_files)>0)
					{
						$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$reg_id));
					}
				}
				 //email to user
				 $emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'user_register_email'));
				 if(count($emailerstr) > 0)
				{
					include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
					$key = $this->config->item('pass_key');
					$aes = new CryptAES();
					$aes->set_key(base64_decode($key));
					$aes->require_pkcs5();
					//$encPass = $aes->encrypt(trim($user_info[0]['usrpassword']));
					$decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));
					//$decpass = $aes->decrypt($user_info[0]['usrpassword']);
					$newstring = str_replace("#application_num#", "".$applicationNo."",  $emailerstr[0]['emailer_text']);
					$final_str= str_replace("#password#", "".$decpass."",  $newstring);
					$info_arr=array('to'=>$user_info[0]['email'],
											  'from'=>$emailerstr[0]['from'],
											  'subject'=>$emailerstr[0]['subject'],
											  'message'=>$final_str
											);
					
						$sms_newstring = str_replace("#application_num#", "".$applicationNo."",  $emailerstr[0]['sms_text']);
						$sms_final_str= str_replace("#password#", "".$decpass."",  $sms_newstring);
						//$this->master_model->send_sms($user_info[0]['mobile'],$sms_final_str);
						$this->master_model->send_sms_trustsignal(intval($user_info[0]['mobile']),$sms_final_str,'DPDoOIwMR');
														
						if($this->Emailsending->mailsend($info_arr))
						{
							echo 'Successfully Mail Send!!';
							//$this->session->set_flashdata('success','Your Application has been Submitted Successfully !!');
							//redirect(base_url('register/acknowledge/'));
						}
						else
						{
							echo 'Error While Sending Mail!!';
							//echo 'Error while sending email';
							//$this->session->set_flashdata('error','Error while sending email !!');
							//redirect(base_url('register/preview/'));
						}
						}
				else
				{
						echo 'Mail String Not Found!!';
						//$this->session->set_flashdata('error','Error while sending email !!');
						//redirect(base_url('register/preview/'));
					}
				}
			else
			{
				if($get_user_regnum_info[0]['status']==2)
				{
					$q_details = $this->cron_sbiqueryapi($MerchantOrderNo = $MerchantOrderNo);
					if ($q_details)
					{
						if ($q_details[2] == "SUCCESS")
						{
							$insert_arr=array('reg_id'=>$reg_id,
														'receipt_no'=>$MerchantOrderNo,
														'type'=>'Registration');
						   $inser_id=$this->master_model->insertRecord('dv_refund_case',$insert_arr);			
						}
					}
				}
			}	
			//$pg_response = "query_api_response=".implode("|", $responsedata);
			//$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
		}
		else if ($q_details[2] == "FAIL")
		{
			$update_data = array('transaction_no' => $q_details[1],'status' => 0,'transaction_details' => $q_details[2]." - ".$q_details[8],'auth_code' => '0399', 'bankcode' => $q_details[9], 'paymode' => $q_details[12],'callback'=>'DV');
			$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
			
			//$pg_response = "query_api_response=".implode("|", $responsedata);
			//$this->log_dv_transaction("sbiepay", $pg_response, $responsedata[2]);
		}
	}
	
	private function update_id_card_transaction($MerchantOrderNo, $reg_no, $q_details)
	{
		if ($q_details[2] == "SUCCESS")
		{
			$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,member_regnumber');
		
			if(count($get_user_regnum) > 0)
			{
				$update_data = array('pay_status' => '1');
				$this->master_model->updateRecord('duplicate_icard',$update_data,array('did'=>$get_user_regnum[0]['ref_id']));
				$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'duplicate_id'));
				/*$update_data = array('transaction_no' => $q_details[1],'status' => 1, 'transaction_details' => $q_details[2]." - ".$q_details[7],'auth_code' => '0300', 'bankcode' => $q_details[8], 'paymode' => $q_details[5]);*/
				$update_data = array('transaction_no' => $q_details[1],
												 'status' => 1, 
												 'transaction_details' => $q_details[2]." - ".$q_details[8],
												 'auth_code' => '0300', 
												 'bankcode' => $q_details[9], 
												 'paymode' => $q_details[12],
												 'callback'=>'DV'
                                                    );
				$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
				if(count($emailerstr) > 0)
				{
					//Query to get user details
					$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'namesub,firstname,middlename,lastname,email');
					$username=$user_info[0]['namesub'].' '.$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
					$newstring1 = str_replace("#DATE#", "".date('Y-m-d h:s:i')."",  $emailerstr[0]['emailer_text']);
					$newstring2 = str_replace("#MEM_NO#", "".$get_user_regnum[0]['member_regnumber']."", $newstring1 );
					$final_str= str_replace("#USERNAME#", "".$userfinalstrname."",  $newstring2);
					$info_arr = array('to'=>$user_info[0]['email'],'from'=>$emailerstr[0]['from'],'subject'=>$emailerstr[0]['subject'],'message'=>$final_str);
					$this->Emailsending->mailsend($info_arr);
					}
			}

			
		}
		else if ($q_details[2] == "FAIL")
		{
			$update_data = array('transaction_no' => $q_details[1],'status' => 0,'transaction_details' => $q_details[2]." - ".$q_details[8],'auth_code' => '0399', 'bankcode' => $q_details[9], 'paymode' => $q_details[12],'callback'=>'DV');
			$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
		}
	}
	
	function log_dv_transaction($gateway, $pg_response, $result)
	{
		$CI = & get_instance();
		$data['date'] = date('Y-m-d H:i:s');
		$data['gateway'] = $gateway;
		$data['data'] = $pg_response;
		$data['result'] = $result;
		$CI->db->insert('dv_paymentlogs', $data);
	}
}