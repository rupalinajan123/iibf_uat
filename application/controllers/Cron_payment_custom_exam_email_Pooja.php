<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cron_payment_custom_exam_email_Pooja extends CI_Controller {
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
	
		//$interval = "15 MINUTE";
		$interval = "1 DAY";
	 	//$pt_query = $this->db->query("SELECT * FROM payment_transaction WHERE date > NOW() - INTERVAL ".$interval." AND gateway = 'sbiepay' AND status = 2 AND (pay_type = '1' OR pay_type = '3') ORDER BY id DESC LIMIT 1");
		
		//$pt_query = $this->db->query("SELECT * FROM payment_transaction WHERE date > NOW() - INTERVAL ".$interval." AND gateway = 'sbiepay' AND status = 2 AND (pay_type = '1' OR pay_type = '3') ORDER BY id DESC LIMIT 25");
		/*$pt_query = $this->db->query("SELECT * FROM payment_transaction WHERE gateway = 'sbiepay' AND status = 2 AND (pay_type = '2') AND receipt_no IN (900000104,900000112,900000109,900000110,900000114,900000115,900000124,900000136,900000145,900000147,900000165,900000166,900000167,900000168,900000169,900000170,900000153,900000155,900000158,900000159,900000160,900000161,900000173,900000175,900000188,900000184)");
		*/

	    //for exam
		$pt_query = $this->db->query("SELECT * FROM payment_transaction WHERE gateway = 'sbiepay' AND status = 1 AND (pay_type = '2') AND receipt_no IN (900391742)");   //
		
		//for registration
		//$pt_query = $this->db->query("SELECT * FROM payment_transaction WHERE gateway = 'sbiepay' AND status = 1 AND (pay_type = '1') AND receipt_no IN (900217069)"); 

		//echo $this->db->last_query(); exit;
		if ($pt_query->num_rows())
		{
			$cnt=1;
			foreach ($pt_query->result_array() as $row)
			{
				//print_r($row); exit;
				 $receipt_no = $row['receipt_no'];  // order_no
				 $reg_no = $row['ref_id'];  // order_no
				
				$q_details = $this->cron_sbiqueryapi($MerchantOrderNo = $receipt_no);
//print_r($q_details);
				if ($q_details)
				{
					if ($q_details[2] == "SUCCESS")
					{
						
						if ($row['pay_type'] == 2)
						{
							$this->update_exam_transaction($MerchantOrderNo, $reg_no, $q_details);
							echo $cnt.'<br>';
						}
						if ($row['pay_type'] == 1)
						{
							$this->update_exam_transaction($MerchantOrderNo, $reg_no, $q_details);
							echo $cnt.'<br>';
						}
					}
					else if ($q_details[2] == "FAIL")
					{
						
						if ($row['pay_type'] == 2)
						{
							$this->update_exam_transaction($MerchantOrderNo, $reg_no, $q_details);
						}
					}
					
					// add query responce in log
					$pg_response = "SBI transaction query responce: ".implode("|", $q_details);
					//$this->log_dv_transaction("sbiepay", $pg_response, $q_details[2]);
					//sleep(1);
				}
				$cnt++;
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

	private function update_exam_transaction($MerchantOrderNo, $test, $q_details)
	{

		if ($q_details[2] == "SUCCESS")
		{
			$responsedata = $q_details;
			//print_r($responsedata);
			$cust=explode('^',$responsedata[5]);
			$responsedata[5]=$cust['1'];
			if($responsedata[5]=='iibfexam')
			{
				if($cust['2']!='iibfdra')
				{
					//$MerchantOrderNo = $responsedata[6]; 
					$get_pg_flag=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'pg_flag');
					$responsedata[5]=$get_pg_flag[0]['pg_flag'];
				}
				else
				{
					$responsedata[5]=$cust['2'];
				}
			}
			
			
			//echo " test ".$responsedata[5]; exit;
			
			if($responsedata[5] == "IIBF_EXAM_O")
			{
			//	sleep(1);
//				$MerchantOrderNo = $responsedata[0]; 
				$transaction_no  = $responsedata[1];
				$payment_status = 2;
				
				switch ($responsedata[2])
				{
					case "SUCCESS":
						$payment_status = 1;
						break;
					case "FAIL":
						$payment_status = 0;
						break;
					case "PENDING":
						$payment_status = 2;
						break;
				}
				
			if($payment_status==1)
			{	
				$elective_subject_name='';
				$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,id');
				
				if($get_user_regnum[0]['status']==1)
				{
					if(count($get_user_regnum) > 0)
					{
					$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');
					}
		
					$update_data = array('pay_status' => '1');
					//$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));
					
					$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[8],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' => $responsedata[12],'callback'=>'DV');
					//$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
					
					//Query to get user details
					$this->db->join('state_master','state_master.state_code=member_registration.state');
					$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
					$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,	pincode,state_master.state_name,institution_master.name');
				
					//Query to get exam details	
					$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
					$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
					$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.state_place_of_work,member_exam.place_of_work,member_exam.pin_code_place_of_work,member_exam.examination_date,member_exam.elected_sub_code');
					if(count($exam_info) <= 0)
					{
						$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']));

					}
	
					if($exam_info[0]['exam_mode']=='ON')
					{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
					{$mode='Offline';}
					else{$mode='';}
					if($exam_info[0]['examination_date']!='0000-00-00')
					{
						$exam_period_date= date('d-M-Y',strtotime($exam_info[0]['examination_date']));
					}
					else if($exam_info[0]['exam_code']!=990)
					{
						$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
						$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
					}
					//Query to get Medium	
					$this->db->where('exam_code',$exam_info[0]['exam_code']);
					$this->db->where('exam_period',$exam_info[0]['exam_period']);
					$this->db->where('medium_code',$exam_info[0]['exam_medium']);
					$this->db->where('medium_delete','0');
					$medium=$this->master_model->getRecords('medium_master','','medium_description');
					
					$this->db->where('state_delete','0');
					$states=$this->master_model->getRecords('state_master',array('state_code'=>$exam_info[0]['state_place_of_work']),'state_name');
			
					//Query to get Payment details	
					$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount,id');
					
					$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
				//if($this->session->userdata['examinfo']['elected_exam_mode']=='E')
					if($exam_info[0]['place_of_work']!='' && $exam_info[0]['state_place_of_work']!='' && $exam_info[0]['pin_code_place_of_work']!='')
					{
						//get Elective Subeject name for CAIIB Exam	
					   if($exam_info[0]['elected_sub_code']!=0 && $exam_info[0]['elected_sub_code']!='')
					   {
						   $elective_sub_name_arr=$this->master_model->getRecords('subject_master',array('subject_code'=>$exam_info[0]['elected_sub_code'],'subject_delete'=>0),'subject_description');
							if(count($elective_sub_name_arr) > 0)
							{
								$elective_subject_name=$elective_sub_name_arr[0]['subject_description'];
							}	
					   }
						$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'member_exam_enrollment_nofee_elective'));
						$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
						$newstring2 = str_replace("#REG_NUM#", "".$get_user_regnum[0]['member_regnumber']."",$newstring1);
						$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
						$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
						$newstring5 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring4);
						$newstring6 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring5);
						$newstring7 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring6);
						$newstring8 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring7);
						$newstring9 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring8);
						$newstring10 = str_replace("#CITY#", "".$result[0]['city']."",$newstring9);
						$newstring11 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring10);
						$newstring12 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring11);
						$newstring13 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring12);
						$newstring14 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring13);
						$newstring15 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring14);
						$newstring16 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring15);
						$newstring17 = str_replace("#ELECTIVE_SUB#", "".$elective_subject_name."",$newstring16);
						$newstring18 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring17);
						$newstring19 = str_replace("#PLACE_OF_WORK#", "".strtoupper($exam_info[0]['place_of_work'])."",$newstring18);
						$newstring20 = str_replace("#STATE_PLACE_OF_WORK#", "".$states[0]['state_name']."",$newstring19);
						$newstring21 = str_replace("#PINCODE_PLACE_OF_WORK#", "".$exam_info[0]['pin_code_place_of_work']."",$newstring20);
						$final_str = str_replace("#MODE#", "".$mode."",$newstring21);
				 }
				else
				{
					
					if($exam_info[0]['exam_code']==990)
					{
						$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'DISA_emailer'));
						$final_str = $emailerstr[0]['emailer_text'];
					}
					else
					{
						$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'apply_exam_transaction_success'));
						$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
						$newstring2 = str_replace("#REG_NUM#", "".$get_user_regnum[0]['member_regnumber']."",$newstring1);
						$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
						$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
						$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);
						$newstring6 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring5);
						$newstring7 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring6);
						$newstring8 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring7);
						$newstring9 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring8);
						$newstring10 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring9);
						$newstring11 = str_replace("#CITY#", "".$result[0]['city']."",$newstring10);
						$newstring12 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring11);
						$newstring13 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring12);
						$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);
						$newstring15 = str_replace("#INSTITUDE#", "".$result[0]['name']."",$newstring14);
						$newstring16 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring15);
						$newstring17 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring16);
						$newstring18 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring17);
						$newstring19 = str_replace("#MODE#", "".$mode."",$newstring18);
						$newstring20 = str_replace("#PLACE_OF_WORK#", "".$result[0]['office']."",$newstring19);
						$newstring21 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring20);
						$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring21);
					}
				}
				$info_arr=array(//'to'=>$result[0]['email'], 
										//'to'=>'kyciibf@gmail.com',
										
										'from'=>$emailerstr[0]['from'],
										'subject'=>$emailerstr[0]['subject'],
										
										'message'=>$final_str 
									);
				
				//get invoice	
				
			$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
			//echo $this->db->last_query();exit;
			if(count($getinvoice_number) > 0)
			{
				/*if($getinvoice_number[0]['state_of_center']=='JAM')
				{
					//$invoiceNumber = generate_exam_invoice_number_jammu($MerchantOrderNo);
					if($invoiceNumber)
					{
						$invoiceNumber=$this->config->item('exam_invoice_no_prefix_jammu').$invoiceNumber;
					}
				}*/
				
			
				
					if($exam_info[0]['exam_code']!=990)
					{
						//$attachpath=custom_genarate_exam_invoice($MerchantOrderNo);
					}
					else
					{
						/*$invoiceNumber =generate_DISA_invoice_number($MerchantOrderNo);
						if($invoiceNumber)
						{
							$invoiceNumber=$this->config->item('DISA_invoice_no_prefix').$invoiceNumber;
						}*/
					}
					//$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
				
				echo '<pre>in';
				print_r($info_arr);
				//$this->db->where('pay_txn_id',$payment_info[0]['id']);
				//$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
				//$attachpath=genarate_DISA_invoice($getinvoice_number[0]['invoice_id']);
			
			    	

			$attachpath='https://iibf.esdsconnect.com/uploads/examinvoice/user/510046457_EX_17-18_089011.jpg';
			$admitcard_pdf='https://iibf.esdsconnect.com/uploads/admitcardpdf/60_217_510046457.pdf';
			echo '<BR>Successfully Mail Send!!';
			if($attachpath!='')
			{ 		   
				if($exam_info[0]['exam_code']==990)
				{
					$sms_final_str = $emailerstr[0]['sms_text'];
				} 
				else
		 		{
					$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
					$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
					$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
					$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
				}
			
				$files=array($attachpath,$admitcard_pdf);
				$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
				$this->Emailsending->mailsend_attch($info_arr,$files);
				echo '<BR>Successfully Mail Send!!';
				//$this->Emailsending->mailsend($info_arr);
				}
				
				}
			}
			else
			{
				echo 'in else';exit;
			}
			/*else if($payment_status==0)
			{
					$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[8],'auth_code' => 0399,'bankcode' => $responsedata[9],'paymode' =>  $responsedata[12],'callback'=>'DV');
					$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
					$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id');
					//Query to get Payment details	
					$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount');
					//Query to get user details
					$this->db->join('state_master','state_master.state_code=member_registration.state');
					$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
					$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,	pincode,state_master.state_name,institution_master.name');
					//Query to get exam details	
					$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code');
					$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
					$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code');
					$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
					$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
				
					$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
					$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
					
					$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
					$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
					$newstring1 = str_replace("#application_num#", "".$get_user_regnum[0]['member_regnumber']."",  $emailerstr[0]['emailer_text']);
					$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
					$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);
					$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
					
					$info_arr=array(	'to'=>$result[0]['email'],
												'from'=>$emailerstr[0]['from'],
												'subject'=>$emailerstr[0]['subject'],
												'message'=>$final_str
											);
					//send sms to Ordinary Member
					$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
					$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
					$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
					if ($this->Emailsending->mailsend($info_arr))
				    {
				    	echo '<BR>Successfully Mail Send!!';
				    }
				}*/				
				}
			}
			else if($responsedata[5] == "IIBF_EXAM_NM")
			{
				sleep(1);
				//$MerchantOrderNo = $responsedata[0]; 
				$transaction_no  = $responsedata[1];
				$payment_status = 2;
				
				switch ($responsedata[2])
				{
					case "SUCCESS":
						$payment_status = 1;
						break;
					case "FAIL":
						$payment_status = 0;
						break;
					case "PENDING":
						$payment_status = 2;
						break;
				}
				
			if($payment_status==1)
			{
				// Handle transaction success case 
				$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');
				if($get_user_regnum[0]['status']==1)
				{
					if(count($get_user_regnum) > 0)
				{
					$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');
				}
				$update_data = array('pay_status' => '1');
				//$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));
				$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[8],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' => $responsedata[12],'callback'=>'DV');
				//$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
				
				//Query to get user details
				$this->db->join('state_master','state_master.state_code=member_registration.state');
				//$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
				$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword');
			
				//Query to get exam details	
				$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
				$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
				$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
				$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
				$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.examination_date');
				
				if(count($exam_info) <= 0)
				{
					$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']));
				}
				
				//echo $this->db->last_query();exit;
			
				if($exam_info[0]['exam_mode']=='ON')
				{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
				{$mode='Offline';}
				else{$mode='';}
				//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
				//$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
				
				if($exam_info[0]['examination_date']!='0000-00-00')
				{
					$exam_period_date= date('d-M-Y',strtotime($exam_info[0]['examination_date']));
				}
				else
				{
					$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
					$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
				}

				//Query to get Medium	
				$this->db->where('exam_code',$exam_info[0]['exam_code']);
				$this->db->where('exam_period',$exam_info[0]['exam_period']);
				$this->db->where('medium_code',$exam_info[0]['exam_medium']);
				$this->db->where('medium_delete','0');
				$medium=$this->master_model->getRecords('medium_master','','medium_description');
			
				//Query to get Payment details	
				$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount,id');
				
				$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
				$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
				
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$key = $this->config->item('pass_key');
				$aes = new CryptAES();
				$aes->set_key(base64_decode($key));
				$aes->require_pkcs5();
				$decpass = $aes->decrypt(trim($result[0]['usrpassword']));
				
				if($exam_info[0]['exam_code']==990)
				{
					$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'DISA_emailer'));
					$final_str = $emailerstr[0]['emailer_text'];
				}
				else
				{
				$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));
				$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
				$newstring2 = str_replace("#REG_NUM#", "".$get_user_regnum[0]['member_regnumber']."",$newstring1);
				$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
				$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
				$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);
				$newstring6 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring5);
				$newstring7 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring6);
				$newstring8 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring7);
				$newstring9 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring8);
				$newstring10 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring9);
				$newstring11 = str_replace("#CITY#", "".$result[0]['city']."",$newstring10);
				$newstring12 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring11);
				$newstring13 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring12);
				$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);
				$newstring15 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring14);
				$newstring16 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring15);
				$newstring17 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring16);
				$newstring18 = str_replace("#MODE#", "".$mode."",$newstring17);
				$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
				$newstring20 = str_replace("#PASS#", "".$decpass."",$newstring19);
				$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring20);
				}
			
				$info_arr=array('to'=>$result[0]['email'],
			//	'to'=>'kyciibf@gmail.com',
										'from'=>$emailerstr[0]['from'],
										'subject'=>$emailerstr[0]['subject'],
										'message'=>$final_str
									);
									
				//get invoice	
			$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
			//echo $this->db->last_query();exit;
			if(count($getinvoice_number) > 0)
			{
				/*if($getinvoice_number[0]['state_of_center']=='JAM')
				{
					$invoiceNumber = custom_genarate_exam_invoice_jk($getinvoice_number[0]['invoice_id']);
					if($invoiceNumber)
					{
						$invoiceNumber=$this->config->item('exam_invoice_no_prefix_jammu').$invoiceNumber;
					}
				}
				else
				{*/
					$invoiceNumber =custom_genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
					if($invoiceNumber)
					{
						$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
					}
				//}
				//$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'));
				//$this->db->where('pay_txn_id',$payment_info[0]['id']);
				//$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
				$attachpath=custom_genarate_exam_invoice($MerchantOrderNo);
			}					
			if($attachpath!='')
			{	
				if($exam_info[0]['exam_code']==990)
				{
					$sms_final_str = $emailerstr[0]['sms_text'];
				}
				else
				{
					$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
					$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
					$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
					$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
				}
				
				$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
				$this->Emailsending->mailsend_attch($info_arr,$attachpath);
				//$this->Emailsending->mailsend($info_arr);
			}
			
			echo '<pre>';
			print_r($info_arr);
			  }
			}
			else if($payment_status==0)
			{
				// Handle transaction 
				$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id');
				
				$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[8],'auth_code' => 0399,'bankcode' => $responsedata[9],'paymode' =>  $responsedata[12],'callback'=>'DV');
				//$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
			
				$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,email,mobile');
				
				//Query to get Payment details	
				$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount');
				
				//Query to get exam details	
				$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
				$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
				$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
				$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
				$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
		
				$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
				$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
		
				$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
				$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
				$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
				$newstring1 = str_replace("#application_num#", "".$get_user_regnum[0]['member_regnumber']."",  $emailerstr[0]['emailer_text']);
				$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
				$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);
				$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
				
				$info_arr=array(	'to'=>$result[0]['email'],
											'from'=>$emailerstr[0]['from'],
											'subject'=>$emailerstr[0]['subject'],
											'message'=>$final_str
										);
				
				$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
				$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
				$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
				//echo $info_arr;
				if ($this->Emailsending->mailsend($info_arr))
				{
						$info_arr1=array('to'=>'esdstesting1@gmail.com',
											'from'=>$emailerstr[0]['from'],
											'subject'=>$emailerstr[0]['subject'],
											'message'=>$final_str
										);
										
						$this->Emailsending->mailsend($$info_arr1);
						
					echo '<BR>Successfully Mail Send!!';
				}
			}
			
			}
			
			else if($responsedata[5] == "IIBF_EXAM_REG")
			{
				sleep(1);
				//$MerchantOrderNo = $responsedata[0]; 
				$transaction_no  = $responsedata[1];
				$payment_status = 2;
				
				switch ($responsedata[2])
				{
					case "SUCCESS":
						$payment_status = 1;
						break;
					case "FAIL":
						$payment_status = 0;
						break;
					case "PENDING":
						$payment_status = 2;
						break;
				}
					
				if($payment_status==1)
				{
					// Handle transaction success case 
					$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status,id');
				if($get_user_regnum[0]['status']==1)
				{
					$exam_code=$get_user_regnum[0]['exam_code'];
					$reg_id='1520503';
					//$applicationNo = generate_nm_reg_num();
					$applicationNo = '801167888';
					$update_data = array('pay_status' => '1','regnumber'=>$applicationNo);
					//$this->master_model->updateRecord('member_exam',$update_data,array('regnumber'=>$reg_id));
				
					$update_data = array('transaction_no' => $transaction_no,'status' => 1,'member_regnumber'=>$applicationNo,'transaction_details' => $responsedata[2]." - ".$responsedata[8],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' => $responsedata[12],'callback'=>'DV');
					//$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
					
					$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
					//$this->master_model->updateRecord('member_registration',$update_mem_data,array('regid'=>$reg_id));
					
					//Query to get exam details	
					$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
					$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
					$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$applicationNo),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.examination_date');
				//echo $this->db->last_query();exit;
					if($exam_info[0]['exam_mode']=='ON')
					{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
					{$mode='Offline';}
					else{$mode='';}
					//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
					//$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
					if($exam_info[0]['examination_date']!='0000-00-00')
					{
						$exam_period_date= date('d-M-Y',strtotime($exam_info[0]['examination_date']));
					}
					else
					{
						$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
						$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
					}
			
					//Query to get Medium	
					$this->db->where('exam_code',$exam_code);
					$this->db->where('exam_period',$exam_info[0]['exam_period']);
					$this->db->where('medium_code',$exam_info[0]['exam_medium']);
					$this->db->where('medium_delete','0');
					$medium=$this->master_model->getRecords('medium_master','','medium_description');
				
					//Query to get Payment details	
					$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'transaction_no,date,amount,id');
					//$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$applicationNo),'transaction_no,date,amount,id');
				//	echo $this->db->last_query();exit;	
					//Query to get user details
					$this->db->join('state_master','state_master.state_code=member_registration.state');
					//$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
					$result=$this->master_model->getRecords('member_registration',array('regid'=>$reg_id),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,usrpassword');	
					
					$upd_files = array();
					$photo_file = 'p_'.$applicationNo.'.jpg';
					$sign_file = 's_'.$applicationNo.'.jpg';
					$proof_file = 'pr_'.$applicationNo.'.jpg';
					
					if(@ rename("./uploads/photograph/".$result[0]['scannedphoto'],"./uploads/photograph/".$photo_file))
					{	$upd_files['scannedphoto'] = $photo_file;	}
					
					if(@ rename("./uploads/scansignature/".$result[0]['scannedsignaturephoto'],"./uploads/scansignature/".$sign_file))
					{	$upd_files['scannedsignaturephoto'] = $sign_file;	}
					
					if(@ rename("./uploads/idproof/".$result[0]['idproofphoto'],"./uploads/idproof/".$proof_file))
					{	$upd_files['idproofphoto'] = $proof_file;	}
					
					if(count($upd_files)>0)
					{
						//$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$reg_id));
					}
			
					include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
					$key = $this->config->item('pass_key');
					$aes = new CryptAES();
					$aes->set_key(base64_decode($key));
					$aes->require_pkcs5();
					$decpass = $aes->decrypt(trim($result[0]['usrpassword']));
				
					$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
					$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));
					$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
					$newstring2 = str_replace("#REG_NUM#", "".$applicationNo."",$newstring1);
					$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
					$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
					$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);
					$newstring6 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring5);
					$newstring7 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring6);
					$newstring8 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring7);
					$newstring9 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring8);
					$newstring10 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring9);
					$newstring11 = str_replace("#CITY#", "".$result[0]['city']."",$newstring10);
					$newstring12 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring11);
					$newstring13 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring12);
					$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);
					$newstring15 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring14);
					$newstring16 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring15);
					$newstring17 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring16);
					$newstring18 = str_replace("#MODE#", "".$mode."",$newstring17);
					$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
					$newstring20 = str_replace("#PASS#", "".$decpass."",$newstring19);
					$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring20);
				
					$info_arr=array('to'=>$result[0]['email'],
											'from'=>$emailerstr[0]['from'],
											'subject'=>$emailerstr[0]['subject'],
											'message'=>$final_str
										);
					echo '<pre>';
					print_r($info_arr);
					
					//get invoice	
			$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
			//echo $this->db->last_query();exit;
			if(count($getinvoice_number) > 0)
			{
				/*if($getinvoice_number[0]['state_of_center']=='JAM')
				{
					$attachpath=custom_genarate_exam_invoice_jk($MerchantOrderNo);
				}
				else
				{*/
					$attachpath=custom_genarate_exam_invoice($MerchantOrderNo);
				//}
				
				
			}	
			if($attachpath!='')
			{		
				//send sms					
				$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
				$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
				$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
				$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
				$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);						
				//$this->Emailsending->mailsend($info_arr);
				$this->Emailsending->mailsend_attch($info_arr,$attachpath);
			}
			//Manage Log
			
					//send sms					
					/*$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
					$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
					$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
					$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
					$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);						
					
					if ($this->Emailsending->mailsend($info_arr))
					{
						
						$info_arr1=array('to'=>'esdstesting1@gmail.com',
											'from'=>$emailerstr[0]['from'],
											'subject'=>$emailerstr[0]['subject'],
											'message'=>$final_str
										);
										
						$this->Emailsending->mailsend($$info_arr1);
						echo '<BR>Successfully Mail Send!!';
					}*/
					
					}
				}
				else if($payment_status==0)
				{
					// Handle transaction fail case 
					$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[8],'auth_code' =>0399,'bankcode' => $responsedata[9],'paymode' =>  $responsedata[12],'callback'=>'DV');
					//$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
				
					//Query to get Payment details	
					$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'transaction_no,date,amount,ref_id');
					$result=$this->master_model->getRecords('member_registration',array('regid'=>$payment_info[0]['ref_id']),'firstname,middlename,lastname,email,mobile');
					
					$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
					$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code');
					$exam_info=$this->master_model->getRecords('member_exam',array('member_exam.regnumber'=>$payment_info[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
				
					$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
					$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
					
					$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
					$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
					$newstring1 = str_replace("#application_num#", "",  $emailerstr[0]['emailer_text']);
					$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
					$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);

					$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
					
					$info_arr=array(	'to'=>$result[0]['email'],
												'from'=>$emailerstr[0]['from'],
												'subject'=>$emailerstr[0]['subject'],
												'message'=>$final_str
											);
					
					// send SMS
					$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
					$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
					//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
					echo $info_arr;
					/*if ($this->Emailsending->mailsend($info_arr))
					{
						echo '<BR>Successfully Mail Send!!';
					}*/
				}
			}	
			else if($responsedata[5] == "IIBF_EXAM_DB")
			{
				sleep(1);
				//$MerchantOrderNo = $responsedata[0]; 
				$transaction_no  = $responsedata[1];
				$payment_status = 2;
				
				switch ($responsedata[2])
				{
					case "SUCCESS":
						$payment_status = 1;
						break;
					case "FAIL":
						$payment_status = 0;
						break;
					case "PENDING":
						$payment_status = 2;
						break;
				}
					
				if($payment_status==1)
				{
					// Handle transaction success case 
					$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code');
					$exam_code=$get_user_regnum[0]['exam_code'];
					$reg_id=$get_user_regnum[0]['ref_id'];
					//$applicationNo = generate_dbf_reg_num(); 
					$applicationNo = $get_user_regnum[0]['member_regnumber'];
					$update_data = array('pay_status' => '1','regnumber'=>$applicationNo);
					//$this->master_model->updateRecord('member_exam',$update_data,array('regnumber'=>$reg_id));
					
					$update_data = array('transaction_no' => $transaction_no,'status' => 1,'member_regnumber'=>$applicationNo,'transaction_details' => $responsedata[2]." - ".$responsedata[8],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' => $responsedata[12],'callback'=>'DV');
					//$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
					
					$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
					//$this->master_model->updateRecord('member_registration',$update_mem_data,array('regid'=>$reg_id));
				
					//Query to get exam details	
				    $this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
					$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
					$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$applicationNo),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
				 
					if($exam_info[0]['exam_mode']=='ON')
					{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
					{$mode='Offline';}
					else{$mode='';}
					//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
					//$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
					
					if($exam_info[0]['examination_date']!='0000-00-00' && $exam_info[0]['examination_date']!='')
					{  
						$exam_period_date= date('d-M-Y',strtotime($exam_info[0]['examination_date']));
					}
					else
					{
						//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
						//$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
						$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
						$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);	
					}
				
					//Query to get Medium	
					$this->db->where('exam_code',$exam_code);
					$this->db->where('exam_period',$exam_info[0]['exam_period']);
					$this->db->where('medium_code',$exam_info[0]['exam_medium']);
					$this->db->where('medium_delete','0');
					$medium=$this->master_model->getRecords('medium_master','','medium_description');
					//Query to get Payment details	
					$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$applicationNo),'transaction_no,date,amount');
					//Query to get user details
					$this->db->join('state_master','state_master.state_code=member_registration.state');
					//$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
					$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$applicationNo),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto');	
				
				
					$upd_files = array();
					$photo_file = 'p_'.$applicationNo.'.jpg';
					$sign_file = 's_'.$applicationNo.'.jpg';
					$proof_file = 'pr_'.$applicationNo.'.jpg';
					
					if(@ rename("./uploads/photograph/".$result[0]['scannedphoto'],"./uploads/photograph/".$photo_file))
					{	$upd_files['scannedphoto'] = $photo_file;	}
					
					if(@ rename("./uploads/scansignature/".$result[0]['scannedsignaturephoto'],"./uploads/scansignature/".$sign_file))
					{	$upd_files['scannedsignaturephoto'] = $sign_file;	}
					
					if(@ rename("./uploads/idproof/".$result[0]['idproofphoto'],"./uploads/idproof/".$proof_file))
					{	$upd_files['idproofphoto'] = $proof_file;	}
					
					if(count($upd_files)>0)
					{
						//$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$reg_id));
					}
				
					$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
					$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));
					$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
					$newstring2 = str_replace("#REG_NUM#", "".$applicationNo."",$newstring1);
					$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
					$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
					$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);
					$newstring6 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring5);
					$newstring7 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring6);
					$newstring8 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring7);
					$newstring9 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring8);
					$newstring10 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring9);
					$newstring11 = str_replace("#CITY#", "".$result[0]['city']."",$newstring10);
					$newstring12 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring11);
					$newstring13 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring12);
					$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);
					$newstring15 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring14);
					$newstring16 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring15);
					$newstring17 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring16);
					$newstring18 = str_replace("#MODE#", "".$mode."",$newstring17);
					$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
					$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring19);
				
						$info_arr=array(//'to'=>$result[0]['email'], 
										//'to'=>'kumartupe@gmail.com',
										'to'=>'21bhavsartejasvi@gmail.com',
										'from'=>$emailerstr[0]['from'],
										'subject'=>$emailerstr[0]['subject'],
										'message'=>$final_str 
									);
					$attachpath='https://iibf.esdsconnect.com/uploads/examinvoice/user/700017395_EX_17-18_037137.jpg';
			 		$admitcard_pdf='https://iibf.esdsconnect.com/uploads/admitcardpdf/42_217_700017395.pdf';
					if($attachpath!='')
					{ 		
					if($exam_info[0]['exam_code']==990)
					{
						$sms_final_str = $emailerstr[0]['sms_text'];
					} 
					else
					{
						$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
						$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
						$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
						$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
					}
				
					$files=array($attachpath,$admitcard_pdf);
					$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
					$this->Emailsending->mailsend_attch($info_arr,$files);
					echo '<BR>Successfully Mail Send!!';
					//$this->Emailsending->mailsend($info_arr);
					}
				}
				else if($payment_status==0)
				{
					// Handle transaction fail case 
					$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[8],'auth_code' => 0399,'bankcode' => $responsedata[9],'paymode' =>  $responsedata[12],'callback'=>'DV');
					//$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
				
					//Query to get Payment details	
					$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'transaction_no,date,amount,ref_id');
					
					$result=$this->master_model->getRecords('member_registration',array('regid'=>$payment_info[0]['ref_id']),'firstname,middlename,lastname,email,mobile');
					
					$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
					$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code');
					$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
					$exam_info=$this->master_model->getRecords('member_exam',array('member_exam.regnumber'=>$payment_info[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
					
					$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
					$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
					
					$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
					$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
					$newstring1 = str_replace("#application_num#", "",  $emailerstr[0]['emailer_text']);
					$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
					$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);
					$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
					
					$info_arr=array(	'to'=>$result[0]['email'],
												'from'=>$emailerstr[0]['from'],
												'subject'=>$emailerstr[0]['subject'],
												'message'=>$final_str
											);
					
					// send SMS
					$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
					$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
					//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
					echo $info_arr;
					/*if ($this->Emailsending->mailsend($info_arr))
					{
						echo '<BR>Successfully Mail Send!!';
					}*/
				}
			}		
			else if($responsedata[5] == "IIBF_EXAM_DB_EXAM")
			{
				sleep(1);
				//$MerchantOrderNo = $responsedata[0]; 
				$transaction_no  = $responsedata[1];
				$payment_status = 2;
				
				switch ($responsedata[2])
				{
					case "SUCCESS":
						$payment_status = 1;
						break;
					case "FAIL":
						$payment_status = 0;
						break;
					case "PENDING":
						$payment_status = 2;
						break;
				}
					
				if($payment_status==1)
				{	
					// Handle transaction success case 
					$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');
					if($get_user_regnum[0]['status']==2)
					{
						if(count($get_user_regnum) > 0)
					{
						$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');
					}
					
					$update_data = array('pay_status' => '1');
					//$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));
					
					$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[8],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' => $responsedata[12],'callback'=>'DV');
					//$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
					
					//Query to get user details
					$this->db->join('state_master','state_master.state_code=member_registration.state');
					//$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
					$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name');
					
					//Query to get exam details	
					$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
					$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
					$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
			
					if($exam_info[0]['exam_mode']=='ON')
					{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
					{$mode='Offline';}
					else{$mode='';}
					$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
					$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
					//Query to get Medium	
					$this->db->where('exam_code',$exam_info[0]['exam_code']);
					$this->db->where('exam_period',$exam_info[0]['exam_period']);
					$this->db->where('medium_code',$exam_info[0]['exam_medium']);
					$this->db->where('medium_delete','0');
					$medium=$this->master_model->getRecords('medium_master','','medium_description');
					
					//Query to get Payment details	
					$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount');
			
					$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
					$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));
					$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
					$newstring2 = str_replace("#REG_NUM#", "".$get_user_regnum[0]['member_regnumber']."",$newstring1);
					$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
					$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
					$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);
					$newstring6 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring5);
					$newstring7 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring6);
					$newstring8 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring7);
					$newstring9 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring8);
					$newstring10 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring9);
					$newstring11 = str_replace("#CITY#", "".$result[0]['city']."",$newstring10);
					$newstring12 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring11);
					$newstring13 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring12);
					$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);
					$newstring15 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring14);
					$newstring16 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring15);
					$newstring17 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring16);
					$newstring18 = str_replace("#MODE#", "".$mode."",$newstring17);
					$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
					$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring19);
				
					$info_arr=array('to'=>$result[0]['email'],
											'from'=>$emailerstr[0]['from'],
											'subject'=>$emailerstr[0]['subject'],
											'message'=>$final_str
										);
										
					$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
					$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
					$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
					$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
					//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
					echo $info_arr;
					/*if ($this->Emailsending->mailsend($info_arr))
					{
						echo '<BR>Successfully Mail Send!!';
					}*/
					
					}
				}
				else if($payment_status==0)
				{
					// Handle transaction fail case 
					$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[8],'auth_code' => 0399,'bankcode' => $responsedata[9],'paymode' =>  $responsedata[12],'callback'=>'DV');
					//$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
				
					$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,email,mobile');
					
					//Query to get Payment details	
					$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount');
						
				   // Handle transaction 
					$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id');
					//Query to get exam details	
					$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code');
					$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
					$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code');
					$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
					$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
			
					$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
					$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
				
					$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
					$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
					$newstring1 = str_replace("#application_num#", "".$get_user_regnum[0]['member_regnumber']."",  $emailerstr[0]['emailer_text']);
					$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
					$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);
					$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
					
					$info_arr=array(	'to'=>$result[0]['email'],
												'from'=>$emailerstr[0]['from'],
												'subject'=>$emailerstr[0]['subject'],
												'message'=>$final_str
											);
					
					$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
					$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
					//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
					echo $info_arr;
					/*if ($this->Emailsending->mailsend($info_arr))
					{
						echo '<BR>Successfully Mail Send!!';
					}*/

				}
			}
			
			else if ($responsedata[5] == "iibfregn")
			{
			
				sleep(8);
				//$MerchantOrderNo = $responsedata[0]; 
				$transaction_no  = $responsedata[1];
				
				$payment_status = 2;
				
				switch ($responsedata[2])
				{
					case "SUCCESS":
						$payment_status = 1;
						break;
					case "FAIL":
						$payment_status = 0;
						break;
					case "PENDING":
						$payment_status = 2;
						break;
				}

				if($payment_status==1)
				{
					$get_user_regnum_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status,id');
					//echo $this->db->last_query();exit;
					//check user payment status is updated by B2B or not
					
					if($get_user_regnum_info[0]['status']==1)
					{
					 //echo 'in status';
					 $reg_id=$get_user_regnum_info[0]['ref_id'];
						/*$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber');*/
					$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$reg_id));
				
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
						$newstring = str_replace("#application_num#", "".$user_info[0]['regnumber']."",  $emailerstr[0]['emailer_text']);
						$final_str= str_replace("#password#", "".$decpass."",  $newstring);
						$info_arr=array('to'=>$user_info[0]['email'],
												 //'to'=>'kumartupe@gmail.com',
												  'from'=>$emailerstr[0]['from'],
												  'subject'=>$emailerstr[0]['subject'],
												  'message'=>$final_str);
					
						//echo '<pre>';
						//	print_r($info_arr);
					
						//set invoice
						$attachpath=custom_genarate_reg_invoice($MerchantOrderNo);
						if($attachpath!='')
						{	
			
							$sms_newstring = str_replace("#application_num#", "".$user_info[0]['regnumber']."",  $emailerstr[0]['sms_text']);
							$sms_final_str= str_replace("#password#", "".$decpass."",  $sms_newstring);
							$this->master_model->send_sms($user_info[0]['mobile'],$sms_final_str);
															
							//if($this->Emailsending->mailsend($info_arr))
							if($this->Emailsending->mailsend_attch($info_arr,$attachpath))
							{
								
									echo '<BR>Successfully Mail Send!!';
							//$this->session->set_flashdata('success','Your Application has been Submitted Successfully !!');
								//redirect(base_url('register/acknowledge/'));
							}
							else
							{
								//echo 'Error while sending email';
								//$this->session->set_flashdata('error','Error while sending email !!');
								//redirect(base_url('register/preview/'));
							}
							}
						else
						{
							//$this->session->set_flashdata('error','Error while sending email !!');
							//redirect(base_url('register/preview/'));
						}
					}
				}
			}	
		}
			
		}
	}

	function DRA_exam_invoice()
	{
		$cnt=1;
		$this->db->where_in('receipt_no', array('706448','706445','706444','706442','706441','706435','706429','706428','706427'));
		$getinvoice_number=$this->master_model->getRecords('exam_invoice');
		if(count($getinvoice_number) > 0)
		{
			foreach ($getinvoice_number as $row)
			{
			 $receipt_no = $row['receipt_no'];  // order_no
			 $attachpath=custom_genarate_draexam_invoice($receipt_no);
			 echo $cnt++."<br/>";
			}	
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