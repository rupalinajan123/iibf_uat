<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Complaint extends CI_Controller {
		
		/**
			* Index Page for this controller.
			*
			* Maps to the following URL
			* 		http://example.com/index.php/welcome
			*	- or -
			* 		http://example.com/index.php/welcome/index
			*	- or -
			* Since this controller is set as the default controller in
			* config/routes.php, it's displayed at http://example.com/
			*
			* So any other public methods not prefixed with an underscore will
			* map to /index.php/welcome/<method_name>
			* @see https://codeigniter.com/user_guide/general/urls.html
		*/
		
		public function __construct()
		{
			parent::__construct(); 
			//load mPDF library
			$this->load->model('Master_model');
			
			/* $this->load->library('m_pdf');
			$this->load->model('Emailsending'); 
			$this->load->library('upload');	
			$this->load->helper('upload_helper');
			$this->load->helper('master_helper');
			$this->load->helper('general_helper');
			$this->load->library('email');
			$this->load->helper('date');
			$this->load->library('email');
			$this->load->model('log_model');
			$this->load->model('Ampmodel'); */
		} 
		
		public function index()
		{
			
			exit;
			$fday = $this->input->post('fday');
			
			if($fday == ''){
				
				$today = date("Y-m-d");
				$this->db->like('complain_date',$today);
				$result=$this->master_model->getRecords('cms_master','','regnumber,	member_type,complain_details,email,mobile,exam_code,subcatcode,category_code,complain_date,attachment',array('compid'=>'DESC'));
				
				}else{
				$today = $fday;
				$this->db->like('complain_date',$today);
				$result=$this->master_model->getRecords('cms_master','','regnumber,	member_type,complain_details,email,mobile,exam_code,subcatcode,category_code,complain_date,attachment',array('compid'=>'DESC'));
			}
			
			
			
	    $data = array("record"=>$result);
			
			$this->load->view('complaint',$data);
			
		}
		
		public function sbitranssuccess()
		{
			error_reporting(E_ALL);// Report all errors
			ini_set("error_reporting", E_ALL);// Same as error_reporting(E_ALL);
			ini_set('display_errors', 1);
			ini_set("memory_limit", "-1");
			
			//delete_cookie('regid');
			//print_r($_REQUEST['encData']);
			$_REQUEST['encData']='DlIXM8E3h+ARMBnNQLV4xmrj6PvxLPRPoMPYL3IcQkq8ZMVUGFlfbsqulXA7XSMFEUyplTx/QFa8Jwri3hJCGozJ2cepccpes/2Ylb5zfL/2ejq4DMtmOmxB0jN2n2TfrDmf4UchCvACq+FcPtXQFSWYIXWQ0RvBI5IIoQ/o8PORSZKDCNIMQRNVrw19d+MbkCbtC5bGhPstlrHr4RNRFFkCLsqT0NRl1gBEd9CNJIz8qoMBDXo0yPPcDNHioUxkmXHsn2VBczpZ34NL9k+AnQ==';		
						
			if (isset($_REQUEST['encData']))
			{
				//echo 'in'; exit;
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$key = $this->config->item('sbi_m_key');
				$aes = new CryptAES();
				$aes->set_key(base64_decode($key));
				$aes->require_pkcs5();
				//$encData = '4000181|7153617802411|SUCCESS|IN|INR|4000181^iibfamp^110^amp202006|4000181|70800|Payment In Clearing|sbiepay|202017662725838|2020-06-24 10:24:44|CC|10001692020062400022|1000169|615.96^110.87|||||||||';
				
				//$encData = '4000193|9345915678816|SUCCESS|IN|INR|4000193^iibfamp^123^amp202007|4000193|70800|Transaction Paid Out|sbiepay|202019011147784|2020-07-08 21:01:16|CC|10001692020070800120|1000169|615.96^110.87||||||||||';
				
				//$encData = '4000263|8963062002418|SUCCESS|IN|INR|4000263^iibfamp^117^amp202009|4000263|23600|Payment Sighted|sbiepay|202027467557299|2020-09-30 16:51:00|CC|10001692020093000226|1000169|205.32^36.96||||||||||';
				
				//$encData = '4000264|3113528324214|SUCCESS|IN|INR|4000264^iibfamp^135^amp202009|4000264|23600|Payment Sighted|sbiepay|202027463173842|2020-09-30 19:17:01|CC|10001692020093000275|1000169|205.32^36.96||||||||||';
				
				//$encData = '4000266|1746863957211|SUCCESS|IN|INR|4000266^iibfamp^125^amp202009|4000266|23600|Payment Sighted|sbiepay|202027441623538|2020-09-30 21:57:04|CC|10001692020093000375|1000169|205.32^36.96||||||||||';
				
				//$encData = '4000192|3165421652221|SUCCESS|IN|INR|4000192^iibfamp^122^amp202007|4000192|70800|Transaction Paid Out|sbiepay|202018985127542|2020-07-07 19:18:27|CC|10001692020070700106|1000169|615.96^110.87||||||||||';
				
				//$encData = '4000190|4801356041821|SUCCESS|IN|INR|4000190^iibfamp^120^amp202007|4000190|70800|Transaction Paid Out|sbiepay|202018903493893|2020-07-07 12:59:25|CC|10001692020070700049|1000169|615.96^110.87||||||||||';
				
				//$encData = '4000304|1733620411430|SUCCESS|IN|INR|4000304^iibfamp^165^amp202102|4000304|23600|Transaction Paid Out|sbiepay|202103481598645|2021-02-03 12:49:17|CC|10001692021020300225|1000169|205.32^36.96||||||||||';
				
				$responsedata = explode("|",$encData);
				
				$MerchantOrderNo = $responsedata[0]; 
				$transaction_no  = $responsedata[1];
				
				$merchIdVal = $Bank_Code = '';
				if (isset($_REQUEST['merchIdVal']))
				{
					$merchIdVal = $_REQUEST['merchIdVal'];
				}
				if (isset($_REQUEST['Bank_Code']))
				{
					$Bank_Code = $_REQUEST['Bank_Code'];
				}
				if (isset($_REQUEST['pushRespData']))
				{
					$encData = $_REQUEST['pushRespData'];
				}
				//Sbi B2B callback
				//check sbi payment status with MerchantOrderNo 
				//echo $MerchantOrderNo;
				$q_details = sbiqueryapi($MerchantOrderNo);
				//echo '<pre>'; print_r($q_details); exit; 
				
				if ($q_details)
				{
					if ($q_details[2] == "SUCCESS")
					{
						$get_user_regnum_info=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo),'id,ref_id,status,payment_option');
						
						//echo $this->db->last_query(); exit;
						//check user payment status is updated by s2s or not
						//	print_r($get_user_regnum_info);exit; 
						
						if($get_user_regnum_info[0]['status']==2) 
						{
							if($get_user_regnum_info[0]['payment_option']==1 || $get_user_regnum_info[0]['payment_option']==4)
							{
								//echo '1'; exit;
								$reg_id=$get_user_regnum_info[0]['ref_id'];
								//$applicationNo = generate_mem_reg_num();
								//Get membership number from 'amp_membershipno' and update in 'amp_candidates'
								$applicationNo =generate_amp_memreg($reg_id);
								//update amp registration table
								$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
								$this->master_model->updateRecord('amp_candidates',$update_mem_data,array('id'=>$reg_id));
								//get user information...
								//$user_info=$this->master_model->getRecords('amp_candidates',array('id'=>$reg_id),'name,regnumber,email_id,photograph,signature,mobile_no,sponsor');
								$user_info=$this->master_model->getRecords('amp_candidates',array('id'=>$reg_id));
								
								$update_data = array('member_regnumber' => $applicationNo,'sponsor'=>$user_info[0]['sponsor'],'transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'B2B');
								$this->master_model->updateRecord('amp_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
								//get payment details
								
								//Query to get Payment details	
								$payment_info=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$user_info[0]['regnumber']),'transaction_no,date,amount');
								
								$upd_files = array();
								$photo_file = 'p_'.$applicationNo.'.jpg';
								$sign_file = 's_'.$applicationNo.'.jpg';
								$proof_file = 'pr_'.$applicationNo.'.jpg';
								
								
								if(@ rename("./uploads/amp/photograph/".$user_info[0]['photograph'],"./uploads/amp/photograph/".$photo_file))
								{	
								$upd_files['photograph'] = $photo_file;	}
								
								if(@ rename("./uploads/amp/signature/".$user_info[0]['signature'],"./uploads/amp/signature/".$sign_file))
								{	
								$upd_files['signature'] = $sign_file;	}
								
								//print_r($upd_files);exit;
								if(count($upd_files)>0)
								{
									$this->master_model->updateRecord('amp_candidates',$upd_files,array('id'=>$reg_id));
								}
								
								
								//email to user
								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer'));
								if(count($emailerstr) > 0)
								{
									//echo 'in';
									$username=$user_info[0]['name'];
									$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
									$newstring1 = str_replace("#REG_NUM#", "".$user_info[0]['regnumber']."",  $emailerstr[0]['emailer_text']);
									$newstring2 = str_replace("#USERNAME#", "".$userfinalstrname."",  $newstring1);
									$newstring3 = str_replace("#EMAIL#", "".$user_info[0]['email_id']."",  $newstring2);
									$newstring4 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $newstring3);
									$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $newstring4);
									$newstring6 = str_replace("#STATUS#", "Transaction Successful",  $newstring5);
									$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring6);
									
									
									$info_arr=array('to'=>$user_info[0]['email_id'],
									'from'=>$emailerstr[0]['from'],
									'subject'=>$emailerstr[0]['subject'],
									'message'=>$final_str,
									//'bcc'=>'skdatta@iibf.org.in,kavan@iibf.org.in'
									);
									echo '<pre>',print_r($info_arr),'</pre>';
									//$this->send_mail($applicationNo);
									//$this->send_sms($applicationNo);
									
									//Invoice generation
									$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$get_user_regnum_info[0]['id']));
									
									if(count($getinvoice_number) > 0)
									{
										$invoiceNumber = generate_amp_invoice_number($getinvoice_number[0]['invoice_id']);
										//	echo '<pre>',print_r($invoiceNumber),'</pre>';
										if($invoiceNumber)
										{
											$invoiceNumber=$this->config->item('amp_invoice_no_prefix').$invoiceNumber;
										}
										
										$update_data = array('invoice_no' => $invoiceNumber,'member_no'=>$applicationNo,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
										$this->db->where('pay_txn_id',$get_user_regnum_info[0]['id']);
										$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
										$attachpath=genarate_amp_invoice($getinvoice_number[0]['invoice_id']);
										//echo $this->db->last_query();
										//echo '<pre>update_data',print_r($update_data),'</pre>';
										//echo '<pre>',print_r($attachpath),'</pre>';
										
										
									}
									//echo '<pre>user_info',print_r($user_info),'</pre>';exit;
									//exit;
									if($attachpath!='')
									{	 
										
										$sms_newstring = str_replace("#fee#", "".$payment_info[0]['amount']."",  $emailerstr[0]['sms_text']);
										$sms_newstring1 = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring);
										$sms_final_str = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $sms_newstring1);
										//$this->master_model->send_sms($user_info[0]['mobile_no'],$sms_final_str);	
										$this->master_model->send_sms_trustsignal(intval($user_info[0]['mobile']),$sms_final_str,'N8YRKIwMg');
										//if($this->Emailsending->mailsend($info_arr))
										//if($this->Emailsending->mailsend_attch($info_arr,$attachpath))
										//if($this->Emailsending->mailsend_attch($info_arr,$attachpath))
										if($this->Emailsending->mailsend_attch($info_arr,$attachpath))
										{
											
											//email send to sk datta and kavan for self sponsor
											if($user_info[0]['sponsor']=='self')
											{
												$emailerSelfStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer_self'));
												if(count($emailerSelfStr) > 0){
													$sponsor = ucwords($user_info[0]['sponsor']).' Sponsor';
													
													if($user_info[0]['phone_no']!=0){ $phone_no = $user_info[0]['phone_no']; }else{ $phone_no = ''; }
													
													if($user_info[0]['work_from_year']!=0){ $work_from_year = $user_info[0]['work_from_year']; }else{ $work_from_year = ''; }
													
													if($user_info[0]['work_to_year']!=0){ $work_to_year = $user_info[0]['work_to_year']; }else{ $work_to_year = ''; }
													
													if($user_info[0]['till_present']==1){ $till_present = 'Yes'; }else{ $till_present = ''; }
													
													if(strtolower($user_info[0]['payment'])=='full'){ $payment = 'Full Paid'; }else{ $payment = ucwords($user_info[0]['payment']).' Installment'; }
													
													if($user_info[0]['work_experiance']!=0){ $work_experiance = $user_info[0]['work_experiance']; }else{ $work_experiance = ''; }
													
													$selfstr1 = str_replace("#name#", "".$user_info[0]['name']."",  $emailerSelfStr[0]['emailer_text']);
													$selfstr2 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $selfstr1);
													$selfstr3 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $selfstr2);
													$selfstr4 = str_replace("#dob#", "".$user_info[0]['dob']."",  $selfstr3);
													$selfstr5 = str_replace("#address1#", "".$user_info[0]['address1']."",  $selfstr4);
													$selfstr6 = str_replace("#address2#", "".$user_info[0]['address2']."",  $selfstr5);
													$selfstr7 = str_replace("#address3#", "".$user_info[0]['address3']."",  $selfstr6);
													$selfstr8 = str_replace("#address4#", "".$user_info[0]['address4']."",  $selfstr7);
													$selfstr9 = str_replace("#pincode_address#", "".$user_info[0]['pincode_address']."",  $selfstr8);
													$selfstr10 = str_replace("#std_code#", "".$user_info[0]['std_code']."",  $selfstr9);
													$selfstr11 = str_replace("#phone_no#", "".$phone_no."",  $selfstr10);
													$selfstr12 = str_replace("#mobile_no#", "".$user_info[0]['mobile_no']."",  $selfstr11);
													$selfstr13 = str_replace("#email_id#", "".$user_info[0]['email_id']."",  $selfstr12);
													$selfstr14 = str_replace("#alt_email_id#", "".$user_info[0]['alt_email_id']."",  $selfstr13);
													$selfstr15 = str_replace("#graduation#", "".$user_info[0]['graduation']."",  $selfstr14);
													$selfstr16 = str_replace("#post_graduation#", "".$user_info[0]['post_graduation']."",  $selfstr15);
													$selfstr17 = str_replace("#special_qualification#", "".$user_info[0]['special_qualification']."",  $selfstr16);
													$selfstr18 = str_replace("#name_employer#", "".$user_info[0]['name_employer']."",  $selfstr17);
													$selfstr19 = str_replace("#position#", "".$user_info[0]['position']."",  $selfstr18);
													$selfstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $selfstr19);
													$selfstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $selfstr20);
													$selfstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $selfstr21);
													$selfstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $selfstr22);
													$selfstr24 = str_replace("#till_present#", "".$till_present."",  $selfstr23);
													$selfstr25 = str_replace("#work_experiance#", "".$work_experiance."",  $selfstr24);
													$selfstr26 = str_replace("#payment#", "".$payment."",  $selfstr25);
													$selfstr27 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $selfstr26);
													$selfstr28 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $selfstr27);
													$selfstr29 = str_replace("#STATUS#", "Transaction Successful",  $selfstr28);
													$selfstr30 = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $selfstr29);
													$final_selfstr = str_replace("#sponsor#", "".$sponsor."",  $selfstr30);
													
													$self_mail_arr = array(
													//'to'=>'skdatta@iibf.org.in,kavan@iibf.org.in,prabhakara@iibf.org.in',
													//'to'=>'21bhavsartejasvi@gmail.com',
													'to'=>'ravita@iibf.org.in,training@iibf.org.in',
													'from'=>$emailerSelfStr[0]['from'],
													'subject'=>$emailerSelfStr[0]['subject'],
													'message'=>$final_selfstr,
													);
													echo '<pre>',print_r($self_mail_arr),'</pre>';
													//$this->Emailsending->mailsend($self_mail_arr);
													$this->Emailsending->mailsend_attch($self_mail_arr,$attachpath);
												}
											}
											
											//email send to sk datta and kavan for bank sponsor
											if($user_info[0]['sponsor']=='bank'){
												//get bank contact email id
												$contact_mail_id = $user_info[0]['sponsor_contact_email'];
												
												$emailerBankStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer_bank'));
												if(count($emailerBankStr) > 0){
													$sponsor = ucwords($user_info[0]['sponsor']).' Sponsor';
													
													if($user_info[0]['phone_no']!=0){ $phone_no = $user_info[0]['phone_no']; }else{ $phone_no = ''; }
													
													if($user_info[0]['work_from_year']!=0){ $work_from_year = $user_info[0]['work_from_year']; }else{ $work_from_year = ''; }
													
													if($user_info[0]['work_to_year']!=0){ $work_to_year = $user_info[0]['work_to_year']; }else{ $work_to_year = ''; }
													
													if($user_info[0]['till_present']==1){ $till_present = 'Yes'; }else{ $till_present = 'NO'; }
													
													if(strtolower($user_info[0]['payment'])=='full'){ $payment = 'Full Paid'; }else{ $payment = ucwords($user_info[0]['payment']).' Installment'; }
													
													if($user_info[0]['work_experiance']!=0){ $work_experiance = $user_info[0]['work_experiance']; }else{ $work_experiance = ''; }
													
													if($user_info[0]['sponsor_contact_phone']!=0){ $sponsor_contact_phone = $user_info[0]['sponsor_contact_phone']; }else{ $sponsor_contact_phone = ''; }
													
													$bankstr1 = str_replace("#name#", "".$user_info[0]['name']."",  $emailerBankStr[0]['emailer_text']);
													$bankstr2 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $bankstr1);
													$bankstr3 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $bankstr2);
													$bankstr4 = str_replace("#dob#", "".$user_info[0]['dob']."",  $bankstr3);
													$bankstr5 = str_replace("#address1#", "".$user_info[0]['address1']."",  $bankstr4);
													$bankstr6 = str_replace("#address2#", "".$user_info[0]['address2']."",  $bankstr5);
													$bankstr7 = str_replace("#address3#", "".$user_info[0]['address3']."",  $bankstr6);
													$bankstr8 = str_replace("#address4#", "".$user_info[0]['address4']."",  $bankstr7);
													$bankstr9 = str_replace("#pincode_address#", "".$user_info[0]['pincode_address']."",  $bankstr8);
													$bankstr10 = str_replace("#std_code#", "".$user_info[0]['std_code']."",  $bankstr9);
													$bankstr11 = str_replace("#phone_no#", "".$phone_no."",  $bankstr10);
													$bankstr12 = str_replace("#mobile_no#", "".$user_info[0]['mobile_no']."",  $bankstr11);
													$bankstr13 = str_replace("#email_id#", "".$user_info[0]['email_id']."",  $bankstr12);
													$bankstr14 = str_replace("#alt_email_id#", "".$user_info[0]['alt_email_id']."",  $bankstr13);
													$bankstr15 = str_replace("#graduation#", "".$user_info[0]['graduation']."",  $bankstr14);
													$bankstr16 = str_replace("#post_graduation#", "".$user_info[0]['post_graduation']."",  $bankstr15);
													$bankstr17 = str_replace("#special_qualification#", "".$user_info[0]['special_qualification']."",  $bankstr16);
													$bankstr18 = str_replace("#name_employer#", "".$user_info[0]['name_employer']."",  $bankstr17);
													$bankstr19 = str_replace("#position#", "".$user_info[0]['position']."",  $bankstr18);
													$bankstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $bankstr19);
													$bankstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $bankstr20);
													$bankstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $bankstr21);
													$bankstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $bankstr22);
													$bankstr24 = str_replace("#till_present#", "".$till_present."",  $bankstr23);
													$bankstr25 = str_replace("#work_experiance#", "".$work_experiance."",  $bankstr24);
													$bankstr26 = str_replace("#payment#", "".$payment."",  $bankstr25);
													$bankstr27 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $bankstr26);
													$bankstr28 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $bankstr27);
													$bankstr29 = str_replace("#STATUS#", "Transaction Successful",  $bankstr28);
													$bankstr30 = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $bankstr29);
													$bankstr31 = str_replace("#sponsor#", "".$sponsor."",  $bankstr30);
													$bankstr32 = str_replace("#sponsor_bank_name#", "".$user_info[0]['sponsor_bank_name']."",  $bankstr31);
													$bankstr33 = str_replace("#sponsor_email#", "".$user_info[0]['sponsor_email']."",  $bankstr32);
													$bankstr34 = str_replace("#sponsor_contact_person#", "".$user_info[0]['sponsor_contact_person']."",  $bankstr33);
													$bankstr35 = str_replace("#sponsor_contact_person#", "".$user_info[0]['sponsor_contact_person']."",  $bankstr34);
													$bankstr36 = str_replace("#sponsor_contact_designation#", "".$user_info[0]['sponsor_contact_designation']."",  $bankstr35);
													$bankstr37 = str_replace("#sponsor_contact_std#", "".$user_info[0]['sponsor_contact_std']."",  $bankstr36);
													$bankstr38 = str_replace("#sponsor_contact_phone#", "".$sponsor_contact_phone."",  $bankstr37);
													$bankstr39 = str_replace("#sponsor_contact_mobile#", "".$user_info[0]['sponsor_contact_mobile']."",  $bankstr38);
													$final_bankstr = str_replace("#sponsor_contact_email#", "".$user_info[0]['sponsor_contact_email']."",  $bankstr39);
													
													$bank_mail_arr = array(
													//'to'=>'skdatta@iibf.org.in,kavan@iibf.org.in,prabhakara@iibf.org.in',
													//'to'=>'21bhavsartejasvi@gmail.com,'.$contact_mail_id.'',
													'to'=>'ravita@iibf.org.in,training@iibf.org.in,'.$contact_mail_id.'',
													'from'=>$emailerBankStr[0]['from'],
													'subject'=>$emailerBankStr[0]['subject'],
													'message'=>$final_bankstr,
													);
													//print_r($bank_mail_arr);exit;
													//$this->Emailsending->mailsend($bank_mail_arr);
													echo '<pre>',print_r($bank_mail_arr),'</pre>';
													$this->Emailsending->mailsend_attch($bank_mail_arr,$attachpath);
												}
											}
											
											$this->session->set_flashdata('success','Amp registration has been done successfully !!');
											redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
										}
										else
										{
											redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
										}
										
									}
									else
									{
										redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
									}	
									
									
									//Manage Log
									$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
									
									$this->log_model->logamptransaction("sbiepay", $pg_response, $responsedata[2]);
									
									
									
									
									redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
								}
								else
								{
									redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
								}
							}
							else if($get_user_regnum_info[0]['payment_option']==2 || $get_user_regnum_info[0]['payment_option']==3)
							{
								//echo '2'; exit;  
								$payment_option='';
								if($get_user_regnum_info[0]['payment_option']== 2)
								{
									$payment_option='second';
								}
								else if($get_user_regnum_info[0]['payment_option']== 3)
								{
									$payment_option='Full';
								}
								
								$reg_id=$get_user_regnum_info[0]['ref_id'];
								
								//update amp registration table with installment status
								$update_mem_data = array('payment' =>$payment_option);
								$this->master_model->updateRecord('amp_candidates',$update_mem_data,array('id'=>$reg_id));
								//$user_info=$this->master_model->getRecords('amp_candidates',array('id'=>$reg_id),'name,regnumber,email_id,photograph,signature,mobile_no,sponsor');
								$user_info=$this->master_model->getRecords('amp_candidates',array('id'=>$reg_id));
								
								//update payment transaction
								$update_data = array('member_regnumber' => $user_info[0]['regnumber'],'sponsor'=>$user_info[0]['sponsor'],'transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'B2B');
								$this->master_model->updateRecord('amp_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
								
								
								
								//maintain log in for updated transaction
								$log_title ="Installment payment";
								$update_info['membershipno'] = $user_info[0]['regnumber'];
								$log_message = serialize($update_mem_data);
								$this->Ampmodel->create_log($log_title, $log_message);
								
								//Query to get Payment details	
								$payment_info=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$user_info[0]['regnumber']),'transaction_no,date,amount');
								
								//email to user
								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer'));
								if(count($emailerstr) > 0)
								{
									$username=$user_info[0]['name'];
									$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
									$newstring1 = str_replace("#REG_NUM#", "".$user_info[0]['regnumber']."",  $emailerstr[0]['emailer_text']);
									$newstring2 = str_replace("#USERNAME#", "".$userfinalstrname."",  $newstring1);
									$newstring3 = str_replace("#EMAIL#", "".$user_info[0]['email_id']."",  $newstring2);
									$newstring4 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $newstring3);
									$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $newstring4);
									$newstring6 = str_replace("#STATUS#", "Transaction Successful",  $newstring5);
									$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring6);
									
									
									$info_arr=array('to'=>$user_info[0]['email_id'],
									'from'=>$emailerstr[0]['from'],
									'subject'=>$emailerstr[0]['subject'],
									'message'=>$final_str,
									//'bcc'=>'kumartupe@gmail.com,raajpardeshi@gmail.com'
									);
									echo '<pre>',print_r($info_arr),'</pre>';
									//$this->send_mail($applicationNo);
									//$this->send_sms($applicationNo);
									
									//Invoice generation
									$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$get_user_regnum_info[0]['id']));
									
									if(count($getinvoice_number) > 0)
									{
										$invoiceNumber = generate_amp_invoice_number($getinvoice_number[0]['invoice_id']);
										//	echo '<pre>',print_r($invoiceNumber),'</pre>';
										if($invoiceNumber)
										{
											$invoiceNumber=$this->config->item('amp_invoice_no_prefix').$invoiceNumber;
										}
										
										$update_data = array('invoice_no' => $invoiceNumber,'member_no'=>$user_info[0]['regnumber'],'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
										$this->db->where('pay_txn_id',$get_user_regnum_info[0]['id']);
										$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
										$attachpath=genarate_amp_invoice($getinvoice_number[0]['invoice_id']);
										//echo $this->db->last_query();
										//echo '<pre>update_data',print_r($update_data),'</pre>';
										//echo '<pre>',print_r($attachpath),'</pre>';
										
										
									}
									
									if($attachpath!='')
									{
										$sms_newstring = str_replace("#fee#", "".$payment_info[0]['amount']."",  $emailerstr[0]['sms_text']);
										$sms_newstring1 = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring);
										$sms_final_str = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $sms_newstring1);
										//$this->master_model->send_sms($user_info[0]['mobile_no'],$sms_final_str);	
										$this->master_model->send_sms_trustsignal(intval($user_info[0]['mobile']),$sms_final_str,'N8YRKIwMg');
										//$this->Emailsending->mailsend($info_arr);
										if($this->Emailsending->mailsend_attch($info_arr,$attachpath))
										{
											
											//email send to sk datta and kavan for self sponsor
											if($user_info[0]['sponsor']=='self')
											{
												$emailerSelfStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer_self'));
												if(count($emailerSelfStr) > 0){
													$sponsor = ucwords($user_info[0]['sponsor']).' Sponsor';
													
													if($user_info[0]['phone_no']!=0){ $phone_no = $user_info[0]['phone_no']; }else{ $phone_no = ''; }
													
													if($user_info[0]['work_from_year']!=0){ $work_from_year = $user_info[0]['work_from_year']; }else{ $work_from_year = ''; }
													
													if($user_info[0]['work_to_year']!=0){ $work_to_year = $user_info[0]['work_to_year']; }else{ $work_to_year = ''; }
													
													if($user_info[0]['till_present']==1){ $till_present = 'Yes'; }else{ $till_present = 'No'; }
													
													if(strtolower($user_info[0]['payment'])=='full'){ $payment = 'Full Paid'; }else{ $payment = ucwords($user_info[0]['payment']).' Installment'; }
													//echo $payment;exit;
													if($user_info[0]['work_experiance']!=0){ $work_experiance = $user_info[0]['work_experiance']; }else{ $work_experiance = ''; }
													
													$selfstr1 = str_replace("#name#", "".$user_info[0]['name']."",  $emailerSelfStr[0]['emailer_text']);
													$selfstr2 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $selfstr1);
													$selfstr3 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $selfstr2);
													$selfstr4 = str_replace("#dob#", "".$user_info[0]['dob']."",  $selfstr3);
													$selfstr5 = str_replace("#address1#", "".$user_info[0]['address1']."",  $selfstr4);
													$selfstr6 = str_replace("#address2#", "".$user_info[0]['address2']."",  $selfstr5);
													$selfstr7 = str_replace("#address3#", "".$user_info[0]['address3']."",  $selfstr6);
													$selfstr8 = str_replace("#address4#", "".$user_info[0]['address4']."",  $selfstr7);
													$selfstr9 = str_replace("#pincode_address#", "".$user_info[0]['pincode_address']."",  $selfstr8);
													$selfstr10 = str_replace("#std_code#", "".$user_info[0]['std_code']."",  $selfstr9);
													$selfstr11 = str_replace("#phone_no#", "".$phone_no."",  $selfstr10);
													$selfstr12 = str_replace("#mobile_no#", "".$user_info[0]['mobile_no']."",  $selfstr11);
													$selfstr13 = str_replace("#email_id#", "".$user_info[0]['email_id']."",  $selfstr12);
													$selfstr14 = str_replace("#alt_email_id#", "".$user_info[0]['alt_email_id']."",  $selfstr13);
													$selfstr15 = str_replace("#graduation#", "".$user_info[0]['graduation']."",  $selfstr14);
													$selfstr16 = str_replace("#post_graduation#", "".$user_info[0]['post_graduation']."",  $selfstr15);
													$selfstr17 = str_replace("#special_qualification#", "".$user_info[0]['special_qualification']."",  $selfstr16);
													$selfstr18 = str_replace("#name_employer#", "".$user_info[0]['name_employer']."",  $selfstr17);
													$selfstr19 = str_replace("#position#", "".$user_info[0]['position']."",  $selfstr18);
													$selfstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $selfstr19);
													$selfstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $selfstr20);
													$selfstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $selfstr21);
													$selfstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $selfstr22);
													$selfstr24 = str_replace("#till_present#", "".$till_present."",  $selfstr23);
													$selfstr25 = str_replace("#work_experiance#", "".$work_experiance."",  $selfstr24);
													$selfstr26 = str_replace("#payment#", "".$payment."",  $selfstr25);
													$selfstr27 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $selfstr26);
													$selfstr28 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $selfstr27);
													$selfstr29 = str_replace("#STATUS#", "Transaction Successful",  $selfstr28);
													$selfstr30 = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $selfstr29);
													$final_selfstr = str_replace("#sponsor#", "".$sponsor."",  $selfstr30);
													
													$self_mail_arr = array(
													//'to'=>'skdatta@iibf.org.in,kavan@iibf.org.in,prabhakara@iibf.org.in',
													'to'=>'ravita@iibf.org.in,training@iibf.org.in',
													'from'=>$emailerSelfStr[0]['from'],
													'subject'=>$emailerSelfStr[0]['subject'],
													'message'=>$final_selfstr,
													);
													
													echo '<pre>',print_r($self_mail_arr),'</pre>';
													$this->Emailsending->mailsend_attch($self_mail_arr,$attachpath);
												}
											}
											
											//email send to sk datta and kavan for bank sponsor
											if($user_info[0]['sponsor']=='bank'){
												$contact_mail_id = $user_info[0]['sponsor_contact_email'];
												$emailerBankStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer_bank'));
												if(count($emailerBankStr) > 0){
													$sponsor = ucwords($user_info[0]['sponsor']).' Sponsor';
													
													if($user_info[0]['phone_no']!=0){ $phone_no = $user_info[0]['phone_no']; }else{ $phone_no = ''; }
													
													if($user_info[0]['work_from_year']!=0){ $work_from_year = $user_info[0]['work_from_year']; }else{ $work_from_year = ''; }
													
													if($user_info[0]['work_to_year']!=0){ $work_to_year = $user_info[0]['work_to_year']; }else{ $work_to_year = ''; }
													
													if($user_info[0]['till_present']==1){ $till_present = 'Yes'; }else{ $till_present = ''; }
													
													if(strtolower($user_info[0]['payment'])=='full'){ $payment = 'Full Paid'; }else{ $payment = ucwords($user_info[0]['payment']).' Installment'; }
													
													if($user_info[0]['work_experiance']!=0){ $work_experiance = $user_info[0]['work_experiance']; }else{ $work_experiance = ''; }
													
													if($user_info[0]['sponsor_contact_phone']!=0){ $sponsor_contact_phone = $user_info[0]['sponsor_contact_phone']; }else{ $sponsor_contact_phone = ''; }
													
													$bankstr1 = str_replace("#name#", "".$user_info[0]['name']."",  $emailerBankStr[0]['emailer_text']);
													$bankstr2 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $bankstr1);
													$bankstr3 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $bankstr2);
													$bankstr4 = str_replace("#dob#", "".$user_info[0]['dob']."",  $bankstr3);
													$bankstr5 = str_replace("#address1#", "".$user_info[0]['address1']."",  $bankstr4);
													$bankstr6 = str_replace("#address2#", "".$user_info[0]['address2']."",  $bankstr5);
													$bankstr7 = str_replace("#address3#", "".$user_info[0]['address3']."",  $bankstr6);
													$bankstr8 = str_replace("#address4#", "".$user_info[0]['address4']."",  $bankstr7);
													$bankstr9 = str_replace("#pincode_address#", "".$user_info[0]['pincode_address']."",  $bankstr8);
													$bankstr10 = str_replace("#std_code#", "".$user_info[0]['std_code']."",  $bankstr9);
													$bankstr11 = str_replace("#phone_no#", "".$phone_no."",  $bankstr10);
													$bankstr12 = str_replace("#mobile_no#", "".$user_info[0]['mobile_no']."",  $bankstr11);
													$bankstr13 = str_replace("#email_id#", "".$user_info[0]['email_id']."",  $bankstr12);
													$bankstr14 = str_replace("#alt_email_id#", "".$user_info[0]['alt_email_id']."",  $bankstr13);
													$bankstr15 = str_replace("#graduation#", "".$user_info[0]['graduation']."",  $bankstr14);
													$bankstr16 = str_replace("#post_graduation#", "".$user_info[0]['post_graduation']."",  $bankstr15);
													$bankstr17 = str_replace("#special_qualification#", "".$user_info[0]['special_qualification']."",  $bankstr16);
													$bankstr18 = str_replace("#name_employer#", "".$user_info[0]['name_employer']."",  $bankstr17);
													$bankstr19 = str_replace("#position#", "".$user_info[0]['position']."",  $bankstr18);
													$bankstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $bankstr19);
													$bankstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $bankstr20);
													$bankstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $bankstr21);
													$bankstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $bankstr22);
													$bankstr24 = str_replace("#till_present#", "".$till_present."",  $bankstr23);
													$bankstr25 = str_replace("#work_experiance#", "".$work_experiance."",  $bankstr24);
													$bankstr26 = str_replace("#payment#", "".$payment."",  $bankstr25);
													$bankstr27 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $bankstr26);
													$bankstr28 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $bankstr27);
													$bankstr29 = str_replace("#STATUS#", "Transaction Successful",  $bankstr28);
													$bankstr30 = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $bankstr29);
													$bankstr31 = str_replace("#sponsor#", "".$sponsor."",  $bankstr30);
													$bankstr32 = str_replace("#sponsor_bank_name#", "".$user_info[0]['sponsor_bank_name']."",  $bankstr31);
													$bankstr33 = str_replace("#sponsor_email#", "".$user_info[0]['sponsor_email']."",  $bankstr32);
													$bankstr34 = str_replace("#sponsor_contact_person#", "".$user_info[0]['sponsor_contact_person']."",  $bankstr33);
													$bankstr35 = str_replace("#sponsor_contact_person#", "".$user_info[0]['sponsor_contact_person']."",  $bankstr34);
													$bankstr36 = str_replace("#sponsor_contact_designation#", "".$user_info[0]['sponsor_contact_designation']."",  $bankstr35);
													$bankstr37 = str_replace("#sponsor_contact_std#", "".$user_info[0]['sponsor_contact_std']."",  $bankstr36);
													$bankstr38 = str_replace("#sponsor_contact_phone#", "".$sponsor_contact_phone."",  $bankstr37);
													$bankstr39 = str_replace("#sponsor_contact_mobile#", "".$user_info[0]['sponsor_contact_mobile']."",  $bankstr38);
													$final_bankstr = str_replace("#sponsor_contact_email#", "".$user_info[0]['sponsor_contact_email']."",  $bankstr39);
													
													$bank_mail_arr = array(
													//'to'=>'skdatta@iibf.org.in,kavan@iibf.org.in',
													'to'=>'ravita@iibf.org.in,training@iibf.org.in,'.$contact_mail_id.'',
													'from'=>$emailerBankStr[0]['from'],
													'subject'=>$emailerBankStr[0]['subject'],
													'message'=>$final_bankstr,
													);
													echo '<pre>',print_r($bank_mail_arr),'</pre>';
													$this->Emailsending->mailsend_attch($bank_mail_arr,$attachpath);
												}
											}
											
										} 
										
									}
									
									//Manage Log
									$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
									
									$this->log_model->logamptransaction("sbiepay", $pg_response, $responsedata[2]);
									
									redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
								}
								else
								{
									redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
								}
							}
						}
					}
				}
				///End of SBI B2B callback 
				redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
			}
			
		}
		
		
		
		
		
		public function send_mail()
		
		{
			exit;
			
			$reg_id='123';
			$MerchantOrderNo='4000193';
			$get_user_regnum_info=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo),'id,ref_id,status,payment_option');
			
			$user_info=$this->master_model->getRecords('amp_candidates',array('id'=>$reg_id));
			
			
			$payment_info=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$user_info[0]['regnumber']),'transaction_no,date,amount');
			//email to user
			$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer'));
			if(count($emailerstr) > 0)
			{
				//echo 'in';
				$username=$user_info[0]['name'];
				$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
				$newstring1 = str_replace("#REG_NUM#", "".$user_info[0]['regnumber']."",  $emailerstr[0]['emailer_text']);
				$newstring2 = str_replace("#USERNAME#", "".$userfinalstrname."",  $newstring1);
				$newstring3 = str_replace("#EMAIL#", "".$user_info[0]['email_id']."",  $newstring2);
				$newstring4 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $newstring3);
				$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $newstring4);
				$newstring6 = str_replace("#STATUS#", "Transaction Successful",  $newstring5);
				$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring6);
				
				
				$info_arr=array('to'=>$user_info[0]['email_id'],
				
				'from'=>$emailerstr[0]['from'],
				'subject'=>$emailerstr[0]['subject'],
				'message'=>$final_str,
				//'bcc'=>'skdatta@iibf.org.in,kavan@iibf.org.in'
				);
				
				//$this->send_mail($applicationNo);
				//$this->send_sms($applicationNo);
				$attachpath='https://iibf.esdsconnect.com/uploads/ampinvoice/user/120_AMP_20-21_007.jpg';
				print_r($final_str);
				echo '<pre>user_info',print_r($emailerstr),'</pre>';//exit;
				//exit;
				if($attachpath!='')
				{	 
					
					$sms_newstring = str_replace("#fee#", "".$payment_info[0]['amount']."",  $emailerstr[0]['sms_text']);
					$sms_newstring1 = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring);
					$sms_final_str = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $sms_newstring1);
					//$this->master_model->send_sms($user_info[0]['mobile_no'],$sms_final_str);	
					
					/////if($this->Emailsending->mailsend($info_arr))
					//if($this->Emailsending->mailsend_attch($info_arr,$attachpath))
					//if($this->Emailsending->mailsend_attch($info_arr,$attachpath))
					
					//if($this->Emailsending->mailsend_attch($info_arr,$attachpath))
					//{
					//echo 'mail has been send';exit;							
					//email send to sk datta and kavan for self sponsor
					if($user_info[0]['sponsor']=='self')
					{
						$emailerSelfStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer_self'));
						if(count($emailerSelfStr) > 0){
							$sponsor = ucwords($user_info[0]['sponsor']).' Sponsor';
							
							if($user_info[0]['phone_no']!=0){ $phone_no = $user_info[0]['phone_no']; }else{ $phone_no = ''; }
							
							if($user_info[0]['work_from_year']!=0){ $work_from_year = $user_info[0]['work_from_year']; }else{ $work_from_year = ''; }
							
							if($user_info[0]['work_to_year']!=0){ $work_to_year = $user_info[0]['work_to_year']; }else{ $work_to_year = ''; }
							
							if($user_info[0]['till_present']==1){ $till_present = 'Yes'; }else{ $till_present = ''; }
							
							if(strtolower($user_info[0]['payment'])=='full'){ $payment = 'Full Paid'; }else{ $payment = ucwords($user_info[0]['payment']).' Installment'; }
							
							if($user_info[0]['work_experiance']!=0){ $work_experiance = $user_info[0]['work_experiance']; }else{ $work_experiance = ''; }
							
							$selfstr1 = str_replace("#name#", "".$user_info[0]['name']."",  $emailerSelfStr[0]['emailer_text']);
							$selfstr2 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $selfstr1);
							$selfstr3 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $selfstr2);
							$selfstr4 = str_replace("#dob#", "".$user_info[0]['dob']."",  $selfstr3);
							$selfstr5 = str_replace("#address1#", "".$user_info[0]['address1']."",  $selfstr4);
							$selfstr6 = str_replace("#address2#", "".$user_info[0]['address2']."",  $selfstr5);
							$selfstr7 = str_replace("#address3#", "".$user_info[0]['address3']."",  $selfstr6);
							$selfstr8 = str_replace("#address4#", "".$user_info[0]['address4']."",  $selfstr7);
							$selfstr9 = str_replace("#pincode_address#", "".$user_info[0]['pincode_address']."",  $selfstr8);
							$selfstr10 = str_replace("#std_code#", "".$user_info[0]['std_code']."",  $selfstr9);
							$selfstr11 = str_replace("#phone_no#", "".$phone_no."",  $selfstr10);
							$selfstr12 = str_replace("#mobile_no#", "".$user_info[0]['mobile_no']."",  $selfstr11);
							$selfstr13 = str_replace("#email_id#", "".$user_info[0]['email_id']."",  $selfstr12);
							$selfstr14 = str_replace("#alt_email_id#", "".$user_info[0]['alt_email_id']."",  $selfstr13);
							$selfstr15 = str_replace("#graduation#", "".$user_info[0]['graduation']."",  $selfstr14);
							$selfstr16 = str_replace("#post_graduation#", "".$user_info[0]['post_graduation']."",  $selfstr15);
							$selfstr17 = str_replace("#special_qualification#", "".$user_info[0]['special_qualification']."",  $selfstr16);
							$selfstr18 = str_replace("#name_employer#", "".$user_info[0]['name_employer']."",  $selfstr17);
							$selfstr19 = str_replace("#position#", "".$user_info[0]['position']."",  $selfstr18);
							$selfstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $selfstr19);
							$selfstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $selfstr20);
							$selfstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $selfstr21);
							$selfstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $selfstr22);
							$selfstr24 = str_replace("#till_present#", "".$till_present."",  $selfstr23);
							$selfstr25 = str_replace("#work_experiance#", "".$work_experiance."",  $selfstr24);
							$selfstr26 = str_replace("#payment#", "".$payment."",  $selfstr25);
							$selfstr27 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $selfstr26);
							$selfstr28 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $selfstr27);
							$selfstr29 = str_replace("#STATUS#", "Transaction Successful",  $selfstr28);
							$selfstr30 = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $selfstr29);
							$final_selfstr = str_replace("#sponsor#", "".$sponsor."",  $selfstr30);
							
							$self_mail_arr = array(
							//'to'=>'skdatta@iibf.org.in,kavan@iibf.org.in,prabhakara@iibf.org.in',
							//'to'=>'21bhavsartejasvi@gmail.com',
							'to'=>'ravita@iibf.org.in,training@iibf.org.in',
							'from'=>$emailerSelfStr[0]['from'],
							'subject'=>$emailerSelfStr[0]['subject'],
							'message'=>$final_selfstr,
							);
							print_r($emailerSelfStr);
							print_r($self_mail_arr);exit;
							//$this->Emailsending->mailsend($self_mail_arr);
							$this->Emailsending->mailsend_attch($self_mail_arr,$attachpath);
						}
					}
					
					//email send to sk datta and kavan for bank sponsor
					if($user_info[0]['sponsor']=='bank'){
						//get bank contact email id
						$contact_mail_id = $user_info[0]['sponsor_contact_email'];
						
						$emailerBankStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer_bank'));
						if(count($emailerBankStr) > 0){
							$sponsor = ucwords($user_info[0]['sponsor']).' Sponsor';
							
							if($user_info[0]['phone_no']!=0){ $phone_no = $user_info[0]['phone_no']; }else{ $phone_no = ''; }
							
							if($user_info[0]['work_from_year']!=0){ $work_from_year = $user_info[0]['work_from_year']; }else{ $work_from_year = ''; }
							
							if($user_info[0]['work_to_year']!=0){ $work_to_year = $user_info[0]['work_to_year']; }else{ $work_to_year = ''; }
							
							if($user_info[0]['till_present']==1){ $till_present = 'Yes'; }else{ $till_present = 'NO'; }
							
							if(strtolower($user_info[0]['payment'])=='full'){ $payment = 'Full Paid'; }else{ $payment = ucwords($user_info[0]['payment']).' Installment'; }
							
							if($user_info[0]['work_experiance']!=0){ $work_experiance = $user_info[0]['work_experiance']; }else{ $work_experiance = ''; }
							
							if($user_info[0]['sponsor_contact_phone']!=0){ $sponsor_contact_phone = $user_info[0]['sponsor_contact_phone']; }else{ $sponsor_contact_phone = ''; }
							
							$bankstr1 = str_replace("#name#", "".$user_info[0]['name']."",  $emailerBankStr[0]['emailer_text']);
							$bankstr2 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $bankstr1);
							$bankstr3 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $bankstr2);
							$bankstr4 = str_replace("#dob#", "".$user_info[0]['dob']."",  $bankstr3);
							$bankstr5 = str_replace("#address1#", "".$user_info[0]['address1']."",  $bankstr4);
							$bankstr6 = str_replace("#address2#", "".$user_info[0]['address2']."",  $bankstr5);
							$bankstr7 = str_replace("#address3#", "".$user_info[0]['address3']."",  $bankstr6);
							$bankstr8 = str_replace("#address4#", "".$user_info[0]['address4']."",  $bankstr7);
							$bankstr9 = str_replace("#pincode_address#", "".$user_info[0]['pincode_address']."",  $bankstr8);
							$bankstr10 = str_replace("#std_code#", "".$user_info[0]['std_code']."",  $bankstr9);
							$bankstr11 = str_replace("#phone_no#", "".$phone_no."",  $bankstr10);
							$bankstr12 = str_replace("#mobile_no#", "".$user_info[0]['mobile_no']."",  $bankstr11);
							$bankstr13 = str_replace("#email_id#", "".$user_info[0]['email_id']."",  $bankstr12);
							$bankstr14 = str_replace("#alt_email_id#", "".$user_info[0]['alt_email_id']."",  $bankstr13);
							$bankstr15 = str_replace("#graduation#", "".$user_info[0]['graduation']."",  $bankstr14);
							$bankstr16 = str_replace("#post_graduation#", "".$user_info[0]['post_graduation']."",  $bankstr15);
							$bankstr17 = str_replace("#special_qualification#", "".$user_info[0]['special_qualification']."",  $bankstr16);
							$bankstr18 = str_replace("#name_employer#", "".$user_info[0]['name_employer']."",  $bankstr17);
							$bankstr19 = str_replace("#position#", "".$user_info[0]['position']."",  $bankstr18);
							$bankstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $bankstr19);
							$bankstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $bankstr20);
							$bankstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $bankstr21);
							$bankstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $bankstr22);
							$bankstr24 = str_replace("#till_present#", "".$till_present."",  $bankstr23);
							$bankstr25 = str_replace("#work_experiance#", "".$work_experiance."",  $bankstr24);
							$bankstr26 = str_replace("#payment#", "".$payment."",  $bankstr25);
							$bankstr27 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $bankstr26);
							$bankstr28 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $bankstr27);
							$bankstr29 = str_replace("#STATUS#", "Transaction Successful",  $bankstr28);
							$bankstr30 = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $bankstr29);
							$bankstr31 = str_replace("#sponsor#", "".$sponsor."",  $bankstr30);
							$bankstr32 = str_replace("#sponsor_bank_name#", "".$user_info[0]['sponsor_bank_name']."",  $bankstr31);
							$bankstr33 = str_replace("#sponsor_email#", "".$user_info[0]['sponsor_email']."",  $bankstr32);
							$bankstr34 = str_replace("#sponsor_contact_person#", "".$user_info[0]['sponsor_contact_person']."",  $bankstr33);
							$bankstr35 = str_replace("#sponsor_contact_person#", "".$user_info[0]['sponsor_contact_person']."",  $bankstr34);
							$bankstr36 = str_replace("#sponsor_contact_designation#", "".$user_info[0]['sponsor_contact_designation']."",  $bankstr35);
							$bankstr37 = str_replace("#sponsor_contact_std#", "".$user_info[0]['sponsor_contact_std']."",  $bankstr36);
							$bankstr38 = str_replace("#sponsor_contact_phone#", "".$sponsor_contact_phone."",  $bankstr37);
							$bankstr39 = str_replace("#sponsor_contact_mobile#", "".$user_info[0]['sponsor_contact_mobile']."",  $bankstr38);
							$final_bankstr = str_replace("#sponsor_contact_email#", "".$user_info[0]['sponsor_contact_email']."",  $bankstr39);
							
							$bank_mail_arr = array(
							//'to'=>'skdatta@iibf.org.in,kavan@iibf.org.in,prabhakara@iibf.org.in',
							//'to'=>'21bhavsartejasvi@gmail.com,'.$contact_mail_id.'',
							'to'=>'ravita@iibf.org.in,training@iibf.org.in,'.$contact_mail_id.'',
							'from'=>$emailerBankStr[0]['from'],
							'subject'=>$emailerBankStr[0]['subject'],
							'message'=>$final_bankstr,
							);
							//print_r($bank_mail_arr);exit;
							//$this->Emailsending->mailsend($bank_mail_arr);
							$this->Emailsending->mailsend_attch($bank_mail_arr,$attachpath);
						}
					}
					
					$this->session->set_flashdata('success','Amp registration has been done successfully !!');
					redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
					//}
					
					
				}
				else
				{
					redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
				}	
				
				
				//Manage Log
				$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
				
				$this->log_model->logamptransaction("sbiepay", $pg_response, $responsedata[2]);
				
				
				
				
				redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
			}
			
			
		}
	}	