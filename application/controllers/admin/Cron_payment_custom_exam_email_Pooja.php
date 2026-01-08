<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cron_payment_custom_exam_email_Pooja extends CI_Controller {
	public function __construct(){
		//exit;
		parent::__construct();
		$this->load->model('Master_model');
		$this->load->model('log_model');
		$this->load->model('Emailsending');
		$this->load->library('upload');
		$this->load->helper('upload_helper');
		$this->load->helper('master_helper');
		$this->load->helper('general_helper');
		$this->load->helper('blended_invoice_helper');
		$this->load->model('Master_model');
		$this->load->library('email');
		$this->load->helper('date');
		$this->load->library('email');
	  //  $this->load->model('Emailsending');
	   // $this->load->model('log_model');
		error_reporting(E_ALL);
		ini_set("display_errors", 1);
	}
//Attempt update for blended traning 
	public function attempt_change_blended()
	{
		//find all pay status 1 members
		$this->db->where_in('program_code','CCP');
		$this->db->group_by('member_no,program_code'); 
		$pay_member=$this->master_model->getRecords('blended_registration',array('pay_status'=>1),'member_no,program_code');
		/*echo  $this->db->last_query();
		 echo '<pre>';
		 print_r($pay_member);
		 exit;*/
		 if(!empty($pay_member))
		 {
			$i=0;
			foreach($pay_member as $val)
			{
				if($val['program_code']=='CCO')
				{
					$val['program_code']='CCP';
				}elseif($val['program_code']=='CTD')
				{
					$val['program_code']='CTP';
				}
					//Find the recode and update the attempt
				$update_data =array('attempt'=>1);
				if( $this->master_model->updateRecord('blended_eligible_master',$update_data,array('member_number'=>$val['member_no'],'program_code'=>$val['program_code'],'attempt'=>0)))
				{
					$i++;
				}
			}
			echo  $i;
			echo '<br>';
			echo $val['program_code'];
		 }else
		 {
			echo "No Recode found..";
		 }
		}
		
		//Blended attempt reverse
		public function blended_attempt_reverse()
		{
		//find all pay status 1 members and whoes attempt is 2
			$this->db->where_in('program_code','CCP');
			$this->db->group_by('member_no,program_code'); 
			$pay_member=$this->master_model->getRecords('blended_registration',array('pay_status'=>1,'attempt'=>2),'member_no,program_code');
		/*echo  $this->db->last_query();
		 echo '<pre>';
		 print_r($pay_member);
		 exit;*/
		 if(!empty($pay_member))
		 {
			$i=0;
			foreach($pay_member as $val)
			{
				if($val['program_code']=='CCO')
				{
					$val['program_code']='CCP';
				}elseif($val['program_code']=='CTD')
				{
					$val['program_code']='CTP';
				}
					//Find the recode and update the attempt
				$update_data =array('attempt'=>0);
				if( $this->master_model->updateRecord('blended_eligible_master',$update_data,array('member_number'=>$val['member_no'],'program_code'=>$val['program_code'],'attempt'=>1)))
				{
					$i++;
				}
			}
			echo  $i;
			echo '<br>';
			echo $val['program_code'];
		 }else
		 {
			echo "No Recode found..";
		 }
		}	
//settlement blended
		public function S2S_blended()
		{
	//exit;	
				//sleep(8);
				//$MerchantOrderNo = $responsedata[0]; 
			//	$transaction_no  = $responsedata[1];
			$MerchantOrderNo = 902735720;
			$transaction_no  = 1544439238123;
			$responsedata[2]='SUCCESS';
			$payment_status = 1;
				//$responsedata[2]='SUCCESS';
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
				
				
				$get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,member_regnumber,status,id');
					//check user payment status is updated by S2S or not
				if ($get_user_regnum_info[0]['status'] == 1) 
				{ 
					$reg_id        = $get_user_regnum_info[0]['ref_id'];
					$applicationNo = $get_user_regnum_info[0]['member_regnumber'];
					$update_data   = array(
							//'member_regnumber' => $applicationNo,
						//'transaction_no' => $transaction_no,
						'status' => 1,
						 //   'transaction_details' => $responsedata[2] . " - " . $responsedata[7],
						'auth_code' => '0300',
						  //  'bankcode' => $responsedata[8],
						  ///  'paymode' => $responsedata[5],
						'callback' => 'S2S'
					);
					if($this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo)))
					{ 
						$reg_data = $this->Master_model->getRecords('blended_registration', array('member_no' => $applicationNo,'blended_id' => $reg_id),'program_code,center_code,batch_code,training_type,venue_code,start_date');
						
						//echo $this->db->last_query();
						$selected_program_code = $reg_data[0]['program_code'];
						$selected_center_code = $reg_data[0]['center_code'];
						$venue_batch_code = $reg_data[0]['batch_code'];
						$selected_training_type = $reg_data[0]['training_type'];
						$selected_venue_code	= $reg_data[0]['venue_code'];		
						$sDate = $reg_data[0]['start_date'];
						/* Check Registration Capacity */
						$RegCount = "";
						$RegCount = blendedRegistrationCapacity($selected_program_code,$selected_center_code,$venue_batch_code,$selected_training_type,$selected_venue_code,$sDate);
						
						/* Get Venue Capacity */
						$capacity = "";
						$capacity = getVenueCapacity($selected_program_code,$selected_center_code,$venue_batch_code,$selected_training_type,$selected_venue_code,$sDate);
						
						/* Get User Attempt */
						$attemptQry=$this->db->query("SELECT attempt,fee_flag FROM blended_eligible_master WHERE member_number='".$applicationNo."' AND program_code = '" . $selected_program_code . "' LIMIT 1"); 
						$attemptArr = $attemptQry->row_array();
						$attempt = $attemptArr['attempt'];
						$fee_flag=$attemptArr['fee_flag'];
						/* Check Count of Vitual Attempts */
						$VitualAttemptsCount = "";
						$VitualAttemptsCount = getVitualAttemptsCounts($applicationNo,$selected_program_code,$venue_batch_code);
						if($VitualAttemptsCount != 0)
						{
							$attempt = 1;
						}
						$attempt = $attempt+1;
						if($RegCount >= $capacity)
						{
							// Refundable
							$blended_data = array('pay_status' => 3, 'attempt'=>$attempt, 'modify_date' => date('Y-m-d H:i:s'));
							$this->master_model->updateRecord('blended_registration',$blended_data,array('blended_id'=>$reg_id));
							/* User Log Activities  */
							$log_title ="Blended Course Registraion - Capacity is full after payment success.";
							$log_message = $log_message = 'Program Code : '.$selected_program_code;
							$rId = $reg_id;
							$regNo = $applicationNo;
							storedUserActivity($log_title, $log_message, $rId, $regNo);
						}
						
						/* Update Pay Status and User Attemp Status */
						$blended_data = array('pay_status'=>1, 'attempt'=>$attempt, 'modify_date'=>date('Y-m-d H:i:s'));
						$this->master_model->updateRecord('blended_registration',$blended_data,array('blended_id'=>$reg_id));
						
						$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'user_blended_email'));
						if (!empty($applicationNo)) {
							$user_info = $this->Master_model->getRecords('member_registration',array('regnumber'=> $applicationNo,'isactive'=>'1'),'email,mobile');
						}
						if (count($emailerstr) > 0) 
						{
							/* Set Email Content For user */
							$Qry=$this->db->query("SELECT program_code, program_name, training_type, center_name, venue_name, start_date, end_date FROM blended_registration WHERE blended_id = '".$reg_id."' LIMIT 1");
							$detailsArr        = $Qry->row_array();
							$program_code = $detailsArr['program_code'];
							$program_name = $detailsArr['program_name'];
							$training_type = $detailsArr['training_type'];
							
							if($training_type=="PC"){
								$training_type='Physical Classroom';
								$venue_name   = $detailsArr['venue_name'];
							}
							else{
								$training_type='Virtual Classes';
								$venue_name   = '-';
							}
							$center_name  = $detailsArr['center_name'];
							$start_date1  = $detailsArr['start_date'];
							$end_date1    = $detailsArr['end_date'];
							$start_date   = date("d-M-Y", strtotime($start_date1));
							$end_date     = date("d-M-Y", strtotime($end_date1));
							$newstring    = str_replace("#program_name#","".$program_name."",$emailerstr[0]['emailer_text']);
							$newstring1   = str_replace("#training_type#","".$training_type."",$newstring);
							$newstring2   = str_replace("#center_name#","".$center_name."",$newstring1);
							$newstring3   = str_replace("#venue_name#","".$venue_name."",$newstring2);
							$newstring4   = str_replace("#start_date#","".$start_date."",$newstring3);
							$newstring5   = str_replace("#end_date#", "".$end_date."",$newstring4);
							
							/* Set Email sending options */
							$info_arr          = array(
								'to' => $user_info[0]['email'],
							  // 'to' => 'kyciibf@gmail.com',
								//'to' => 'bhushan.amrutkar09@gmail.com',
								'from' => $emailerstr[0]['from'],
								'subject' => $emailerstr[0]['subject'],
								'message' => $newstring5
							);
							$getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo,'pay_txn_id' => $get_user_regnum_info[0]['id']));
							//print_r($getinvoice_number); die;
							//echo $this->db->last_query(); die;
							$zone_code = ""; 
							$zoneArr = array();
							//$regno = $this->session->userdata['memberdata']['regno'];
							$zoneArr = $this->master_model->getRecords('blended_registration',array('blended_id'=>$reg_id,'pay_status'=>1),'zone_code,gstin_no');
							$zone_code = $zoneArr[0]['zone_code'];
							
							$gstin_no          = $zoneArr[0]['gstin_no'];
							/* Invoice Number Genarate Functinality */
							if (count($getinvoice_number) > 0){
								$invoiceNumber = generate_blended_invoice_number($getinvoice_number[0]['invoice_id'],$zone_code);
								if($invoiceNumber){$invoiceNumber = $this->config->item('blended_invoice_T'.$zone_code.'_prefix').$invoiceNumber;}
								$update_data = array(
									'invoice_no' => $invoiceNumber,
									//'member_no' => $applicationNo,
									'transaction_no' => $transaction_no,
									'date_of_invoice' => $getinvoice_number[0]['created_on'], //date('Y-m-d H:i:s'),
									'modified_on' => $getinvoice_number[0]['created_on']//date('Y-m-d H:i:s')
								);
								$this->db->where('pay_txn_id', $get_user_regnum_info[0]['id']);
								$this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));
								/* Invoice Genarate Function */
								$attachpath = genarate_blended_invoice($getinvoice_number[0]['invoice_id'],$zone_code,$program_name,$gstin_no);
								/* User Log Activities  */
								$log_title ="Blended Course Registration-Invoice Genarate";
								$log_message = serialize($update_data);
								$rId = $reg_id;
								$regNo = $applicationNo;
								storedUserActivity($log_title, $log_message, $rId, $regNo);
							}
							if ($attachpath != '') 
							{	
								/* Email Send To Clints */
								if (!empty($applicationNo)) {
									$reg_info = $this->Master_model->getRecords('blended_registration',array('member_no'=> $applicationNo,'blended_id' => $reg_id));
								}
								$payment_infoArr=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$reg_info[0]['member_no']),'transaction_no,date,amount');
								if($reg_info[0]['member_no'] == $applicationNo)
								{
									$emailerSelfStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'blended_emailer_client'));
									if(count($emailerSelfStr) > 0)
									{
										$designation_name=$undergraduate_name=$graduate_name=$postgraduate_name=$specify_qualification=$institution_name=$pay="";
										
										$undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));
										$graduate=$this->master_model->getRecords('qualification', array('type' => 'GR'));
										$postgraduate=$this->master_model->getRecords('qualification', array('type'=> 'PG'));
										
										$institution_master = $this->master_model->getRecords('institution_master');
										$states             = $this->master_model->getRecords('state_master');
										$designation        = $this->master_model->getRecords('designation_master');
										if(count($designation)){
											foreach($designation as $designation_row){
												if($reg_info[0]['designation']==$designation_row['dcode']){
													$designation_name = $designation_row['dname'];}
												} 
											}
											if(count($institution_master)){
												foreach($institution_master as $institution_row){ 	
													if($reg_info[0]['associatedinstitute']==$institution_row['institude_id']){
														$institution_name = $institution_row['name'];}
													}
												}
												if($reg_info[0]['qualification']=='U'){$undergraduate_name = 'Under Graduate';}
												if($reg_info[0]['qualification']=='G'){$graduate_name = 'Graduate';}
												if($reg_info[0]['qualification']=='P'){$postgraduate_name = 'Post Graduate';}	
												$qualificationArr=$this->master_model->getRecords('qualification',array('qid'=>$reg_info[0]['specify_qualification']),'name','','1');
												if(count($qualificationArr)) 
												{
													$specify_qualification = $qualificationArr[0]['name'];
												}
												$training_type = $reg_info[0]['training_type'];
												if($training_type=="PC")
												{
													$training_type='Physical Classroom';
													$venue_name   = $reg_info[0]['venue_name'];
												}
												else
												{
													$training_type='Virtual Classes';
													$venue_name   = "-";
												}
												$center_name  = $reg_info[0]['center_name'];
												$start_date   = date("d-M-Y", strtotime($reg_info[0]['start_date']));
												$end_date     = date("d-M-Y", strtotime($reg_info[0]['end_date']));
												if($reg_info[0]['pay_status'] == 1){ $pay = "Success";}
												$selfstr1 = str_replace("#regnumber#", "".$reg_info[0]['member_no']."",  $emailerSelfStr[0]['emailer_text']);
												$selfstr2 = str_replace("#program_name#", "".$reg_info[0]['program_name']."",  $selfstr1);
												$selfstr3 = str_replace("#center_name#", "".$center_name."",  $selfstr2);
												$selfstr4 = str_replace("#venue_name#", "".$venue_name."", $selfstr3);
												$selfstr5 = str_replace("#start_date#", "".$start_date."", $selfstr4);
												$selfstr6 = str_replace("#end_date#", "".$end_date."",  $selfstr5);
												$selfstr7 = str_replace("#training_type#", "".$training_type."",  $selfstr6);	
												$selfstr8 = str_replace("#name#", "".$reg_info[0]['namesub']." ".$reg_info[0]['firstname']." ".$reg_info[0]['middlename']." ".$reg_info[0]['lastname'],  $selfstr7);
												$selfstr9 = str_replace("#address1#", "".$reg_info[0]['address1']."",  $selfstr8);
												$selfstr10 = str_replace("#address2#", "".$reg_info[0]['address2']."",  $selfstr9);
												$selfstr11 = str_replace("#address3#", "".$reg_info[0]['address3']."",  $selfstr10);
												$selfstr12 = str_replace("#address4#", "".$reg_info[0]['address4']."",  $selfstr11);
												$selfstr13 = str_replace("#district#", "".$reg_info[0]['district']."",  $selfstr12);
												$selfstr14 = str_replace("#city#", "".$reg_info[0]['city']."",  $selfstr13);
												$selfstr15 = str_replace("#state#", "".$reg_info[0]['state']."",  $selfstr14);
												$selfstr16 = str_replace("#pincode#", "".$reg_info[0]['pincode']."",  $selfstr15);
												$selfstr17 = str_replace("#designation#", "".$designation_name."",  $selfstr16);
												$selfstr18 = str_replace("#associatedinstitute#", "".$institution_name."",  $selfstr17);
												$selfstr19 = str_replace("#dateofbirth#", "".$reg_info[0]['dateofbirth']."",  $selfstr18);
												$selfstr20 = str_replace("#email#", "".$reg_info[0]['email']."",  $selfstr19);
												$selfstr21 = str_replace("#mobile#", "".$reg_info[0]['mobile']."",  $selfstr20);
												$selfstr22 = str_replace("#res_stdcode#", "".$reg_info[0]['res_stdcode']."",  $selfstr21);
												$selfstr23 = str_replace("#residential_phone#", "".$reg_info[0]['residential_phone']."",  $selfstr22);
												$selfstr24 = str_replace("#stdcode#", "".$reg_info[0]['stdcode']."",  $selfstr23);
												$selfstr25 = str_replace("#office_phone#", "".$reg_info[0]['office_phone']."",  $selfstr24);
												$selfstr26 = str_replace("#qualification#", "".$undergraduate_name.$graduate_name.$postgraduate_name."",  $selfstr25);
												$selfstr27 = str_replace("#specify_qualification#", "".$specify_qualification."",  $selfstr26);
												$selfstr28 = str_replace("#emergency_name#", "".$reg_info[0]['emergency_name']."",  $selfstr27);
												$selfstr29 = str_replace("#emergency_contact_no#", "".$reg_info[0]['emergency_contact_no']."",  $selfstr28);
												$selfstr30 = str_replace("#blood_group#", "".$reg_info[0]['blood_group']."",  $selfstr29);
												$selfstr31 = str_replace("#TRANSACTION_NO#", "".$payment_infoArr[0]['transaction_no']."",  $selfstr30);
												$selfstr32 = str_replace("#AMOUNT#", "".$payment_infoArr[0]['amount']."",  $selfstr31);
												$selfstr33 = str_replace("#STATUS#", "Transaction Successful",  $selfstr32);
												$final_selfstr = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_infoArr[0]['date']))."",  $selfstr33);
												$s1 = str_replace("#program_code#", "".$reg_info[0]['program_code'],$emailerSelfStr[0]['subject']);
												$final_sub = str_replace("#Batch_code#", "".$reg_info[0]['batch_code']."",  $s1);
												/* Get Client Emails Details */
												$emailsQry = $this->db->query("SELECT emails FROM offline_email_master WHERE zone_code = '" . $reg_info[0]['zone_code'] . "' AND program_code = '" . $reg_info[0]['program_code'] . "' AND batch_code = '" . $reg_info[0]['batch_code'] . "'AND isdelete = 0 LIMIT 1 ");
												$emailsArr    = $emailsQry->row_array();
												$emails  = $emailsArr['emails'];	
												$self_mail_arr = array(
													'to'=>$emails,
													'from'=>$emailerSelfStr[0]['from'],
													'subject'=> $final_sub,
													'message'=>$final_selfstr);	
												$this->Emailsending->mailsend_attch($self_mail_arr,$attachpath);
											}
										}
										/* SMS Sending Code */
										$sms_newstring = str_replace("#program_name#", "" . $program_name . "", $emailerstr[0]['sms_text']);
										$this->master_model->send_sms($user_info[0]['mobile'], $sms_newstring);
										if ($this->Emailsending->mailsend_attch($info_arr, $attachpath)) {
											echo  'Email send sucessfully..!<br>'.$emails.'<br>';              
										} 
									} 
								}
							}
						}
					}	
					else if($payment_status==0)
					{
						$get_user_regnum_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status');
						if($get_user_regnum_info[0]['status']==2)
						{
							$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0399', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
							$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
						}
					}
				}
				function random_password($length = 6)
				{
					$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
					$password = substr( str_shuffle( $chars ), 0, $length );
					return $password;
				}	
				public function member_settlement_new()
				{   
				
	/*//get member no 
	$member_no=$this->uri->segment(4);
	//get member exam id 
	$mem_exam_id=$this->uri->segment(5);
	//get member  exam code 
	$exam_code=$this->uri->segment(6);
	//get member exam period
	$exam_prd=$this->uri->segment(7);*/ 
	
	

	$member_no = 802206378;   
	$mem_exam_id =7570091; // member exam table primary key OR admit_card_detail mem_exam_id
	$exam_code = 991;  
	$exam_prd = 998; 
	$password = $this->random_password(); 
	
	//check in admit card  table
	$this->db->group_by('sub_cd,mem_exam_id');
	$admit_card_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$mem_exam_id,'mem_mem_no'=>$member_no,'exm_cd'=>$exam_code,
		'exm_prd'=>$exam_prd)); 
		//,'app_update'=>'1' 
		
		//echo $this->db->last_query(); exit;
	//echo $this->db->last_query(); exit;
	// print_r($admit_card_details); die; 
	/********Password Code********/
	
	
	if(!empty($admit_card_details))
	{
		foreach($admit_card_details as $val)
		{
			if($val['pwd']!='')
			{
				$password =$val['pwd'];
			}	
		}
	}
	if($password == '')
	{
		$password = $this->random_password(); 
	}
	/********End of Password Code********/
	echo 'here';
	if(!empty($admit_card_details))
	{echo 'here123123';
		echo 'Total recode found in admit card table :<br>';
		echo count($admit_card_details);
		// print_r($admit_card_details);
		//exit;
		foreach($admit_card_details as $val)
		{
			if($val['seat_identification']=='')
			{
				echo "prt--";
			//get the  seat number from the seat allocation table 2
				$this->db->order_by("seat_no", "desc"); 
				$seat_allocation=$this->master_model->getRecords('seat_allocation',array('venue_code'=>$val['venueid'],'session'=>$val['time'],'center_code'=>$val['center_code'],'date'=>$val['exam_date']));
				if(!empty($seat_allocation))
				{
			//check venue_capacity
					$venue_capacity=$this->master_model->getRecords('venue_master',array(			        'venue_code'=>$val['venueid'],
						'session_time'=>$val['time'],
						'center_code'=>$val['center_code'],
						'institute_code'=>'0',
						'exam_date'=>$val['exam_date']));
		//echo  $this->db->last_query(); exit;
					
					$venue_capacity=$venue_capacity[0]['session_capacity']+600;
					if(!empty($venue_capacity))
					{
		   //if(count($seat_allocation)<=$venue_capacity)
						if($seat_allocation[0]['seat_no']<=$venue_capacity)
						{
							$seat_no=$seat_allocation[0]['seat_no'];
				//inset new recode with append  seat number
							$seat_no=$seat_no+1;
							if($seat_no<10)
							{
								$seat_no='00'.$seat_no;
							}
							elseif($seat_no>10 && $seat_no<100)
							{
								$seat_no='0'.$seat_no;
							}
							$invoice_insert_array = array(
								'seat_no' => $seat_no,
								'exam_code' => $val['exm_cd'],
								'venue_code'=>$val['venueid'],
								'session'=>$val['time'],
								'center_code'=>$val['center_code'],
								'date'=>$val['exam_date'],
								'exam_period'=>$val['exm_prd'],
								'subject_code'=>$val['sub_cd'],
								'admit_card_id'=>$val['admitcard_id'],
								'createddate'=>date('Y-m-d H:i:s')
							);
							if($this->master_model->insertRecord('seat_allocation', $invoice_insert_array))
							{	
				//inset new recode with append  seat number
			//$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
			//$password = substr( str_shuffle( $chars ), 0, 6 );
								$admitcard_image=$val['exm_cd'].'_'.$val['exm_prd'].'_'.$val['mem_mem_no'].'.pdf';
								$update_info = array(
									'seat_identification' => $seat_no,
									'modified_on'=>$val['created_on'],
									'admitcard_image'=>$admitcard_image,
									'pwd' => $password, 
									'remark'=>1,
								);
								if($this->master_model->updateRecord('admit_card_details', $update_info,array('admitcard_id'=>$val['admitcard_id'])))
								{
									echo '<br>Recode updated sucessfully in admit card<br>';
								}else
								{
									echo '<br>Recode Not updated sucessfully in admit card<br>';
								}
							}
						}else
						{
							echo '<br>Capacity has been full<br>';
						}
					}else
					{
						echo '<br>Venue not present in venue master123<br>';
					}
				}else
				{
					$venue_capacity=$this->master_model->getRecords('venue_master',array(			        'venue_code'=>$val['venueid'],
						'session_time'=>$val['time'],
						'center_code'=>$val['center_code'],
						'exam_date'=>$val['exam_date']));
						echo $this->db->last_query();
					if(!empty($venue_capacity))
					{
						if($seat_allocation[0]['seat_no']<=$venue_capacity[0]['session_capacity'])
						{
			//inset new recode with oo1
							$seat_no='001';
							$invoice_insert_array = array(
								'seat_no' => $seat_no,
								'exam_code' => $val['exm_cd'],
								'venue_code'=>$val['venueid'],
								'session'=>$val['time'],
								'center_code'=>$val['center_code'],
								'date'=>$val['exam_date'],
								'exam_period'=>$val['exm_prd'],
								'subject_code'=>$val['sub_cd'],
								'admit_card_id'=>$val['admitcard_id'],
								'createddate'=>date('Y-m-d H:i:s')
							);
							if($this->master_model->insertRecord('seat_allocation', $invoice_insert_array))
							{
								echo 'Seat alloation primary key :<br>';
								echo $inser_id;
					//update the admit card table :
			//$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
			//$password = substr( str_shuffle( $chars ), 0, 6 );
								$admitcard_image=$val['exm_cd'].'_'.$val['exm_prd'].'_'.$val['mem_mem_no'].'.pdf';
								$update_info = array(
									'seat_identification' => $seat_no,
									'modified_on'=>$val['created_on'],
									'admitcard_image'=>$admitcard_image,
									'pwd' => $password, 
									'remark'=>1,
								);
								if($this->master_model->updateRecord('admit_card_details', $update_info,array('admitcard_id'=>$val['admitcard_id'])))
								{
									echo 'Recode updated sucessfully in admit card<br>';
								}else
								{
									echo 'Recode Not updated sucessfully in admit card<br>';
								}
							}
						}else
						{
							echo '<br>Capacity has been full<br>';
						}
					}else
					{
						echo '<br>Venue not present in venue master234<br>';
					}
				}}
			}
		}
	}
	
	public function member_settlement_new_dynamic()
				{  
				
	/*//get member no 
	$member_no=$this->uri->segment(4);
	//get member exam id 
	$mem_exam_id=$this->uri->segment(5);
	//get member  exam code 
	$exam_code=$this->uri->segment(6);
	//get member exam period
	$exam_prd=$this->uri->segment(7);*/
	//$this->db->limit(1);
	$admit_card_details=$this->master_model->getRecords('admit_generate',array('app_update'=>'0'));
//echo $this->db->last_query();exit;
	
		if(count($admit_card_details) >0)
		{
		
			foreach($admit_card_details as $rowmem)
			{
				
					$member_no = $rowmem['regnumber'];  
					$mem_exam_id =$rowmem['id']; 
					$exam_code = $rowmem['exam_code'];  
					$exam_prd = $rowmem['exam_period'];
					$password = $this->random_password();   
		//check in admit card  table
		$admit_card_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$mem_exam_id,'mem_mem_no'=>$member_no,'exm_cd'=>$exam_code,
			'exm_prd'=>$exam_prd)); 
			echo $this->db->last_query();
		//echo $this->db->last_query(); exit;
	//print_r($admit_card_details); die; 
		/********Password Code********/
		if(!empty($admit_card_details))
		{
			foreach($admit_card_details as $val)
			{
				if($val['pwd']!='')
				{
					$password =$val['pwd'];
				}	
			}
		}
		if($password == '')
		{
			$password = $this->random_password(); 
		}
		/********End of Password Code********/
		echo $password;
		if(!empty($admit_card_details))
		{
			echo 'Total recode found in admit card table :<br>';
			echo count($admit_card_details);
			//exit;
			foreach($admit_card_details as $val)
			{
				if($val['seat_identification']=='')
				{
				//get the  seat number from the seat allocation table 2
					$this->db->order_by("seat_no", "desc"); 
					$seat_allocation=$this->master_model->getRecords('seat_allocation',array('venue_code'=>$val['venueid'],'session'=>$val['time'],'center_code'=>$val['center_code'],'date'=>$val['exam_date']));
					echo '<br>';echo $this->db->last_query();
					if(!empty($seat_allocation))
					{
				//check venue_capacity
						$venue_capacity=$this->master_model->getRecords('venue_master',array(			        'venue_code'=>$val['venueid'],
							'session_time'=>$val['time'],
							'center_code'=>$val['center_code'],
							'exam_date'=>$val['exam_date']));
							echo '<br>';echo $this->db->last_query();
			//echo  $this->db->last_query();
						$venue_capacity=$venue_capacity[0]['session_capacity']+800;
						if(!empty($venue_capacity))
						{
			   //if(count($seat_allocation)<=$venue_capacity)
							if($seat_allocation[0]['seat_no']<=$venue_capacity)
							{
								$seat_no=$seat_allocation[0]['seat_no'];
					//inset new recode with append  seat number
								$seat_no=$seat_no+1;
								if($seat_no<10)
								{
									$seat_no='00'.$seat_no;
								}
								elseif($seat_no>10 && $seat_no<100)
								{
									$seat_no='0'.$seat_no;
								}
								$invoice_insert_array = array(
									'seat_no' => $seat_no,
									'exam_code' => $val['exm_cd'],
									'venue_code'=>$val['venueid'],
									'session'=>$val['time'],
									'center_code'=>$val['center_code'],
									'date'=>$val['exam_date'],
									'exam_period'=>$val['exm_prd'],
									'subject_code'=>$val['sub_cd'],
									'admit_card_id'=>$val['admitcard_id'],
									'createddate'=>date('Y-m-d H:i:s')
								);
								if($this->master_model->insertRecord('seat_allocation', $invoice_insert_array))
								{	
					//inset new recode with append  seat number
				//$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
				//$password = substr( str_shuffle( $chars ), 0, 6 );
									$admitcard_image=$val['exm_cd'].'_'.$val['exm_prd'].'_'.$val['mem_mem_no'].'.pdf';
									$update_info = array(
										'seat_identification' => $seat_no,
										'modified_on'=>$val['created_on'],
										'admitcard_image'=>$admitcard_image,
										'pwd' => $password, 
										'remark'=>1,
									);
									echo '<br>';echo $this->db->last_query();
									if($this->master_model->updateRecord('admit_card_details', $update_info,array('mem_exam_id'=>$val['mem_exam_id'])))
									{
										$update_info_generate = array(
										'app_update' => '1'
									);
									echo '<br>';echo $this->db->last_query();
										$this->master_model->updateRecord('admit_generate', $update_info_generate,array('id '=>$val['mem_exam_id']));
										echo '<br>';echo $this->db->last_query();
										echo '<br>Recode updated sucessfully in admit card<br>';
									}else
									{
										echo '<br>Recode Not updated sucessfully in admit card<br>';
									}
								}
							}else
							{
								echo '<br>Capacity has been full<br>';
							}
						}else
						{
							echo '<br>Venue not present in venue master123<br>';
						}
					}}
				}
			}
		
			}
		}
	}
//Member settlement 
	
		public function update_member_number()
		{
			//$this->db->limit(1);
			$admit_card_details=$this->master_model->getRecords('admit_generate',array('app_update'=>'0'));
			if(count($admit_card_details) >0)
		{
		
			foreach($admit_card_details as $rowmem)
			{
				$update_info_generate = array(
										'mem_mem_no' => $rowmem['regnumber']
									);
				$this->master_model->updateRecord('admit_card_details', $update_info_generate,array('mem_exam_id '=>$rowmem['id']));
				
				echo  $rowmem['id'].'<br>';
			}
		}
		}
	public function member_settlement()
	{
	/*//get member no 
	$member_no=$this->uri->segment(4);
	//get member exam id 
	$mem_exam_id=$this->uri->segment(5);
	//get member  exam code 
	$exam_code=$this->uri->segment(6);
	//get member exam period
	$exam_prd=$this->uri->segment(7);*/
	
	$member_no = 500050103;  
	$mem_exam_id = 4485905;
	$exam_code = $this->config->item('examCodeCaiib');
	$exam_prd = 219;
	
	//check in admit card  table
	$admit_card_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$mem_exam_id,'mem_mem_no'=>$member_no,'exm_cd'=>$exam_code,
		'exm_prd'=>$exam_prd));
	
//print_r($admit_card_details); die;
	if(!empty($admit_card_details))
	{
		echo 'Total recode found in admit card table :<br>';
		echo count($admit_card_details);
		foreach($admit_card_details as $val)
		{
			if($val['seat_identification']=='')
			{
			//get the  seat number from the seat allocation table 2
				$seat_allocation=$this->master_model->getRecords('seat_allocation',array('venue_code'=>$val['venueid'],'session'=>$val['time'],'center_code'=>$val['center_code'],'date'=>$val['exam_date']));
				if(!empty($seat_allocation))
				{
			//check venue_capacity
					$venue_capacity=$this->master_model->getRecords('venue_master',array(			        'venue_code'=>$val['venueid'],
						'session_time'=>$val['time'],
						'center_code'=>$val['center_code'],
						'exam_date'=>$val['exam_date']));
		//echo  $this->db->last_query();
					$venue_capacity=$venue_capacity[0]['session_capacity']+11;
					if(!empty($venue_capacity))
					{
						if(count($seat_allocation)<=$venue_capacity)
						{
							$seat_no=count($seat_allocation);
				//inset new recode with append  seat number
							$seat_no=$seat_no+1;
							if($seat_no<10)
							{
								$seat_no='00'.$seat_no;
							}
							elseif($seat_no>10 && $seat_no<100)
							{
								$seat_no='0'.$seat_no;
							}
							$invoice_insert_array = array(
								'seat_no' => $seat_no,
								'exam_code' => $val['exm_cd'],
								'venue_code'=>$val['venueid'],
								'session'=>$val['time'],
								'center_code'=>$val['center_code'],
								'date'=>$val['exam_date'],
								'exam_period'=>$val['exm_prd'],
								'subject_code'=>$val['sub_cd'],
								'admit_card_id'=>$val['admitcard_id'],
								'createddate'=>date('Y-m-d H:i:s')
							);
							if($this->master_model->insertRecord('seat_allocation', $invoice_insert_array))
							{	
				//inset new recode with append  seat number
			//$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
			//$password = substr( str_shuffle( $chars ), 0, 6 );
								$admitcard_image=$val['exm_cd'].'_'.$val['exm_prd'].'_'.$val['mem_mem_no'].'.pdf';
								$update_info = array(
									'seat_identification' => $seat_no,
									'modified_on'=>$val['created_on'],
									'admitcard_image'=>$admitcard_image,
									'remark'=>1,
								);
								if($this->master_model->updateRecord('admit_card_details', $update_info,array('admitcard_id'=>$val['admitcard_id'])))
								{
									echo '<br>Recode updated sucessfully in admit card<br>';
								}else
								{
									echo '<br>Recode Not updated sucessfully in admit card<br>';
								}
							}
						}else
						{
							echo '<br>Capacity has been full<br>';
						}
					}else
					{
						echo '<br>Venue not present in venue master345<br>';
					}
				}else
				{
					$venue_capacity=$this->master_model->getRecords('venue_master',array(			        'venue_code'=>$val['venueid'],
						'session_time'=>$val['time'],
						'center_code'=>$val['center_code'],
						'exam_date'=>$val['exam_date']));
					if(!empty($venue_capacity))
					{
						if(count($seat_allocation)<=$venue_capacity[0]['session_capacity'])
						{
			//inset new recode with oo1
							$seat_no='001';
							$invoice_insert_array = array(
								'seat_no' => $seat_no,
								'exam_code' => $val['exm_cd'],
								'venue_code'=>$val['venueid'],
								'session'=>$val['time'],
								'center_code'=>$val['center_code'],
								'date'=>$val['exam_date'],
								'exam_period'=>$val['exm_prd'],
								'subject_code'=>$val['sub_cd'],
								'admit_card_id'=>$val['admitcard_id'],
								'createddate'=>date('Y-m-d H:i:s')
							);
							if($this->master_model->insertRecord('seat_allocation', $invoice_insert_array))
							{
								echo 'Seat alloation primary key :<br>';
								echo $inser_id;
					//update the admit card table :
			//$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
			//$password = substr( str_shuffle( $chars ), 0, 6 );
								$admitcard_image=$val['exm_cd'].'_'.$val['exm_prd'].'_'.$val['mem_mem_no'].'.pdf';
								$update_info = array(
									'seat_identification' => $seat_no,
									'modified_on'=>$val['created_on'],
									'admitcard_image'=>$admitcard_image,
									'remark'=>1,
								);
								if($this->master_model->updateRecord('admit_card_details', $update_info,array('admitcard_id'=>$val['admitcard_id'])))
								{
									echo 'Recode updated sucessfully in admit card<br>';
								}else
								{
									echo 'Recode Not updated sucessfully in admit card<br>';
								}
							}
						}else
						{
							echo '<br>Capacity has been full<br>';
						}
					}else
					{
						echo '<br>Venue not present in venue master567<br>';
					}
				}}
			}
		}
	}
//Capacity update 
	public function Venue_capacity_update()
	{ 
		$duplicate_venue=$oldcapacity_greater=array();
		$count_upadted=0;
		$count_upadted_not=0;
		$new_venue_details = $this->master_model->getRecords('update_venue_master',array('Updated_status'=>'no'));
		//print_r($new_venue_details);
		foreach($new_venue_details as $val){
		//print_r($val);
			$old_venue_details = $this->master_model->getRecords('venue_master',
				array('center_code'=>$val['center_code'],
					'venue_code'=>$val['venue_code'],
					'exam_date'=>$val['exam_date'],
					'session_time'=>$val['session_time']
				));
				
			//echo '<pre>';
			//print_r($old_venue_details);
			if(count($old_venue_details)==1)
			{
				if($old_venue_details[0]['session_capacity'] <=$val['session_capacity'])
				{
					$update_data = array('session_capacity'=>$val['session_capacity']);
					$this->master_model->updateRecord('venue_master',$update_data, array('center_code'=>$val['center_code'],
						'venue_code'=>$val['venue_code'],
						'exam_date'=>$val['exam_date'],
						'session_time'=>$val['session_time']
					));
					$update_data_new = array('Updated_status'=>'yes');
					$this->master_model->updateRecord('update_venue_master',$update_data_new,array('center_code'=>$val['center_code'],
						'venue_code'=>$val['venue_code'],
						'exam_date'=>$val['exam_date'],
						'session_time'=>$val['session_time']
					));	
					$count_upadted++;
				}elseif($old_venue_details[0]['session_capacity'] >=$val['session_capacity'])
				{
					$oldcapacity_greater[]=$old_venue_details;
				}
				$count_upadted_not++;
			}elseif(count($old_venue_details)>1)
			{
	 //$count_upadted_not++;
				$duplicate_venue[]=$old_venue_details;
			//echo 'Duplicate recode found for the venue: ';
			}
		}
		echo 'Recode updated sucessfully: ';
		echo '<br>';	
		echo  $count_upadted;
		echo '<br>';
		echo 'Duplicate recode found for the venue: ';
		echo '<br>';
		echo '<br>';
		echo 'venue_code'.' '.'exam_date'.' '.'center_code'.'session_time'.' '.'session_capacity';
		echo '<br>';
		foreach($duplicate_venue as $rec2)
		{
			foreach($rec2 as $val2)
			{
				echo $val2['venue_code'].' '.$val2['exam_date'].' '.$val2['center_code'].' '.$val2['session_time'].' '.$val2['session_capacity'];
				echo '<br>';
			}
		}
		//echo '<br>';
		echo '<br>';
		echo 'old capacity is more and new capcity is less : ';
		echo '<br>';
		echo '<br>';
		echo 'venue_code'.' '.'exam_date'.' '.'center_code'.'session_time'.' '.'session_capacity';
		echo '<br>';
		foreach($oldcapacity_greater as $rec)
		{
			foreach($rec as $val1)
			{
				echo $val1['venue_code'].' '.$val1['exam_date'].' '.$val1['center_code'].' '.$val1['session_time'].' '.$val1['session_capacity'];
				echo '<br>';
			}
		}
		//echo '<br>';
		echo '<br>';
	}
	public function custom_bcbf_invoice_pooja(){
		$arr = array(901791,901802,901817,901833,901857,901872,901919,901938,901958,901341093,901341107,901341147,901341154,901341159,901341181,901341222,901341230,901341257,901341290,901341305,901341321,901341325,901341352,901341383,901341426,901341437,901341449,901341536,901341540,901341562,901341625,901341643,901341647,901341736,901341762,901341791,901341833,901341862,901341863,901341871,901341885,901341914,901341989,901342021,901342084,901342099,901342145,901342233,901342257,901342268,901342297,901342448,901342589,901342703,901342746,901342781,901342836,901343024,901343030,901343038,901343044,901343062,901343227,901343256,901343299,901343326,901343339,901343344,901343062,901343227,901343256,901343299,901343326,901343339,901343344,901343375,901343389,901343431);    
		for($i=0;$i<=77;$i++){ 
			echo $path = custom_genarate_bcbfexam_invoice($arr[$i]);
			echo "<br/>"; 
		}
		//echo $path = custom_genarate_bcbfexam_invoice(901330107);
	}
	public function Idcard_invoice()
	{
		$attachpath=genarate_duplicateicard_invoice($getinvoice_number[0]['invoice_id']);
		echo  $attachpath;
	}
	public function dup_cert()
	{
		$MerchantOrderNo=901395419;
		$get_user_regnum[0]['id']=3257405;
		$user_info[0]['email']='vahitha@iibf.org.in';
		$attachpath=" https://iibf.esdsconnect.com/uploads/dupcertinvoice/user/801191163_EDC_18-19_04509.jpg";
		$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'duplicate_cert'));
		$final_str = $emailerstr[0]['emailer_text'];
		$info_arr = array(
			'to'=>$user_info[0]['email'],
			'from'=>$emailerstr[0]['from'],
			'subject'=>$emailerstr[0]['subject'],
			'message'=>$final_str);
									//genertate invoice and email send with invoice attach 8-7-2017					
									//get invoice	
		$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$get_user_regnum[0]['id']));
									//echo $this->db->last_query();exit;
		if(count($getinvoice_number) > 0)
		{ 
			if($this->Emailsending->mailsend_attch($info_arr,"https://iibf.esdsconnect.com/uploads/dupcertinvoice/user/801191163_EDC_18-19_04509.jpg"))
			{
				echo 'Email has been send ..!';
				echo '<br>';
				echo $user_info[0]['email'] ;
			}
		}
	}
	public function blended_invoice()
	{
		$zone_code='SZ';
		$program_name='CERTIFIED CREDIT OFFICER';
		$gstin_no='';
		$getinvoice_number[0]['invoice_id']=1586495;
		$attachpath = genarate_blended_invoice($getinvoice_number[0]['invoice_id'],$zone_code,$program_name,$gstin_no);
		if ($attachpath != '') 
		{
			echo  $attachpath;
		}
	}
	public function blended_email_VC()
	{
		$regnumber='510044091';
		$last_id='18714';
		//$emails='dost143_rch@rediffmail.com';
		$emails='murali_cakrishna@yahoo.co.in';
		$attachpath = '';
		//$attachpath="https://iibf.esdsconnect.com/uploads/blended_invoice/user/CO/500032401_TCO_1819_00772.jpg";
		if (!empty($regnumber)) {
			$reg_info = $this->Master_model->getRecords('blended_registration',array('member_no'=> $regnumber,'blended_id' => $last_id)); }
			if($reg_info[0]['member_no'] == $regnumber)
			{
				$emailerSelfStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'blended_virtual_emailer_client'));
				if(count($emailerSelfStr) > 0)
				{
					$designation_name=$undergraduate_name=$graduate_name=$postgraduate_name=$specify_qualification=$institution_name=$pay="";
					$undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));
					$graduate=$this->master_model->getRecords('qualification', array('type' => 'GR'));
					$postgraduate=$this->master_model->getRecords('qualification', array('type'=> 'PG'));
					$institution_master = $this->master_model->getRecords('institution_master');
					$states             = $this->master_model->getRecords('state_master');
					$designation        = $this->master_model->getRecords('designation_master');
					if(count($designation)){
						foreach($designation as $designation_row){
							if($reg_info[0]['designation']==$designation_row['dcode']){
								$designation_name = $designation_row['dname'];}} 
							}
							if(count($institution_master)){
								foreach($institution_master as $institution_row){ 	
									if($reg_info[0]['associatedinstitute']==$institution_row['institude_id']){
										$institution_name = $institution_row['name'];}
									}
								}
								if($reg_info[0]['qualification']=='U'){$undergraduate_name = 'Under Graduate';}
								if($reg_info[0]['qualification']=='G'){$graduate_name = 'Graduate';}
								if($reg_info[0]['qualification']=='P'){$postgraduate_name = 'Post Graduate';}	
								$qualificationArr=$this->master_model->getRecords('qualification',array('qid'=>$reg_info[0]['specify_qualification']),'name','','1');
								if(count($qualificationArr)){
									$specify_qualification = $qualificationArr[0]['name'];
								}
								$training_type = $reg_info[0]['training_type'];
								if($training_type=="PC"){
									$training_type='Physical Classroom';
								}
								else{
									$training_type='Virtual Classes';
								}
								$venue_name   = "-";
								$start_date   = date("d-M-Y", strtotime($reg_info[0]['start_date']));
								$end_date     = date("d-M-Y", strtotime($reg_info[0]['end_date']));
								if($reg_info[0]['pay_status'] == 1){ $pay = "Success";}
								$selfstr1 = str_replace("#regnumber#", "".$reg_info[0]['member_no']."",  $emailerSelfStr[0]['emailer_text']);
								$selfstr2 = str_replace("#program_name#", "".$reg_info[0]['program_name']."",  $selfstr1);
								$selfstr3 = str_replace("#center_name#", "".$reg_info[0]['center_name']."",  $selfstr2);
								$selfstr4 = str_replace("#venue_name#", "".$venue_name."",  $selfstr3);
								$selfstr5 = str_replace("#start_date#", "".$start_date."",  $selfstr4);
								$selfstr6 = str_replace("#end_date#", "".$end_date."",  $selfstr5);
								$selfstr7 = str_replace("#training_type#", "".$training_type."",  $selfstr6);
								$selfstr8 = str_replace("#fees#", "0",  $selfstr7);
								$selfstr9 = str_replace("#name#", "".$reg_info[0]['namesub']." ".$reg_info[0]['firstname']." ".$reg_info[0]['middlename']." ".$reg_info[0]['lastname'],  $selfstr8);
								$selfstr10 = str_replace("#address1#", "".$reg_info[0]['address1']."",  $selfstr9);
								$selfstr11 = str_replace("#address2#", "".$reg_info[0]['address2']."",  $selfstr10);
								$selfstr12 = str_replace("#address3#", "".$reg_info[0]['address3']."",  $selfstr11);
								$selfstr13 = str_replace("#address4#", "".$reg_info[0]['address4']."",  $selfstr12);
								$selfstr14 = str_replace("#district#", "".$reg_info[0]['district']."",  $selfstr13);
								$selfstr15 = str_replace("#city#", "".$reg_info[0]['city']."",  $selfstr14);
								$selfstr16 = str_replace("#state#", "".$reg_info[0]['state']."",  $selfstr15);
								$selfstr17 = str_replace("#pincode#", "".$reg_info[0]['pincode']."",  $selfstr16);
								$selfstr18 = str_replace("#designation#", "".$designation_name."",  $selfstr17);
								$selfstr19 = str_replace("#associatedinstitute#", "".$institution_name."",  $selfstr18);
								$selfstr20 = str_replace("#dateofbirth#", "".$reg_info[0]['dateofbirth']."",  $selfstr19);
								$selfstr21 = str_replace("#email#", "".$reg_info[0]['email']."",  $selfstr20);
								$selfstr22 = str_replace("#mobile#", "".$reg_info[0]['mobile']."",  $selfstr21);
								$selfstr23 = str_replace("#res_stdcode#", "".$reg_info[0]['res_stdcode']."",  $selfstr22);
								$selfstr24 = str_replace("#residential_phone#", "".$reg_info[0]['residential_phone']."",  $selfstr23);
								$selfstr25 = str_replace("#stdcode#", "".$reg_info[0]['stdcode']."",  $selfstr24);
								$selfstr26 = str_replace("#office_phone#", "".$reg_info[0]['office_phone']."",  $selfstr25);
								$selfstr27 = str_replace("#qualification#", "".$undergraduate_name.$graduate_name.$postgraduate_name."",  $selfstr26);
								$selfstr28 = str_replace("#specify_qualification#", "".$specify_qualification."",  $selfstr27);
								$selfstr29 = str_replace("#emergency_name#", "".$reg_info[0]['emergency_name']."",  $selfstr28);
								$selfstr30 = str_replace("#emergency_contact_no#", "".$reg_info[0]['emergency_contact_no']."",  $selfstr29);
								$final_selfstr = str_replace("#blood_group#", "".$reg_info[0]['blood_group']."",  $selfstr30);
								$s1 = str_replace("#program_code#", "".$reg_info[0]['program_code'],$emailerSelfStr[0]['subject']);
								$final_sub = str_replace("#Batch_code#", "".$reg_info[0]['batch_code']."",  $s1);
								/* Get Client Emails Details */
								$emailsQry = $this->db->query("SELECT emails FROM offline_email_master WHERE zone_code = '" . $reg_info[0]['zone_code'] . "' AND program_code = '" . $reg_info[0]['program_code'] . "' AND batch_code = '" . $reg_info[0]['batch_code'] . "'AND isdelete = 0 LIMIT 1 ");
								$emailsArr    = $emailsQry->row_array();
							//$emails  = $emailsArr['emails'];
								$self_mail_arr = array(
									'to'=>$emails,
							//'to'=>'kyciibf@gmail.com',
									'from'=>$emailerSelfStr[0]['from'],
									'subject'=>$final_sub,
									'message'=>$final_selfstr);					
								if($this->Emailsending->mailsend_attch($self_mail_arr,$attachpath))
								{
									echo $regnumber;
									echo '**ghjgfhj'; 
									echo  $emails;
								}
							}
						}
					}
					public function blended_email_PC()
					{ 
						echo '****************';  
	//priya@iibf.org.in,iibfsz@iibf.org.in
	//training@iibf.org.in,shivshankar@iibf.org.in
		//$email='pooja.godse@esds.co.in';
		//$email='lingkarthik@gmail.com';
						$email='m.raghuvansi@gmail.com';
	//chandanmishra9889@rediffmail.com
						$regnumber=500187478;
						$MerchantOrderNo= 902185497;
						$batchcode='VCCP025';
						$attachpath="https://iibf.esdsconnect.com/uploads/blended_invoice/user/CO/500187478_TCO_1920_00597.jpg";
						$reg_info = $this->Master_model->getRecords('blended_registration',array('member_no'=>$regnumber,'batch_code' =>$batchcode,'pay_status' => 1)); 
						$payment_infoArr=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$reg_info[0]['member_no']),'transaction_no,date,amount');
	//print_r($reg_info);exit;
						$emailerSelfStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'blended_emailer_client'));
						if(count($emailerSelfStr) > 0)
						{
							$designation_name=$undergraduate_name=$graduate_name=$postgraduate_name=$specify_qualification=$institution_name=$pay="";
							$undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));
							$graduate=$this->master_model->getRecords('qualification', array('type' => 'GR'));
							$postgraduate=$this->master_model->getRecords('qualification', array('type'=> 'PG'));
							$institution_master = $this->master_model->getRecords('institution_master');
							$states             = $this->master_model->getRecords('state_master');
							$designation        = $this->master_model->getRecords('designation_master');
							if(count($designation)){
								foreach($designation as $designation_row){
									if($reg_info[0]['designation']==$designation_row['dcode']){
										$designation_name = $designation_row['dname'];}
									} 
								}
								if(count($institution_master)){
									foreach($institution_master as $institution_row){ 	
										if($reg_info[0]['associatedinstitute']==$institution_row['institude_id']){
											$institution_name = $institution_row['name'];}
										}
									}
									if($reg_info[0]['qualification']=='U'){$undergraduate_name = 'Under Graduate';}
									if($reg_info[0]['qualification']=='G'){$graduate_name = 'Graduate';}
									if($reg_info[0]['qualification']=='P'){$postgraduate_name = 'Post Graduate';}	
									$qualificationArr=$this->master_model->getRecords('qualification',array('qid'=>$reg_info[0]['specify_qualification']),'name','','1');
									if(count($qualificationArr)) 
									{
										$specify_qualification = $qualificationArr[0]['name'];
									}
									$training_type = $reg_info[0]['training_type'];
									if($training_type=="PC")
									{
										$training_type='Physical Classroom';
										$venue_name   = $reg_info[0]['venue_name'];
									}
									else
									{
										$training_type='Virtual Classes';
										$venue_name   = "-";
									}
									$center_name  = $reg_info[0]['center_name'];
									$start_date   = date("d-M-Y", strtotime($reg_info[0]['start_date']));
									$end_date     = date("d-M-Y", strtotime($reg_info[0]['end_date']));
									if($reg_info[0]['pay_status'] == 1){ $pay = "Success";}
									$selfstr1 = str_replace("#regnumber#", "".$reg_info[0]['member_no']."",  $emailerSelfStr[0]['emailer_text']);
									$selfstr2 = str_replace("#program_name#", "".$reg_info[0]['program_name']."",  $selfstr1);
									$selfstr3 = str_replace("#center_name#", "".$center_name."",  $selfstr2);
									$selfstr4 = str_replace("#venue_name#", "".$venue_name."", $selfstr3);
									$selfstr5 = str_replace("#start_date#", "".$start_date."", $selfstr4);
									$selfstr6 = str_replace("#end_date#", "".$end_date."",  $selfstr5);
									$selfstr7 = str_replace("#training_type#", "".$training_type."",  $selfstr6);	
									$selfstr8 = str_replace("#name#", "".$reg_info[0]['namesub']." ".$reg_info[0]['firstname']." ".$reg_info[0]['middlename']." ".$reg_info[0]['lastname'],  $selfstr7);
									$selfstr9 = str_replace("#address1#", "".$reg_info[0]['address1']."",  $selfstr8);
									$selfstr10 = str_replace("#address2#", "".$reg_info[0]['address2']."",  $selfstr9);
									$selfstr11 = str_replace("#address3#", "".$reg_info[0]['address3']."",  $selfstr10);
									$selfstr12 = str_replace("#address4#", "".$reg_info[0]['address4']."",  $selfstr11);
									$selfstr13 = str_replace("#district#", "".$reg_info[0]['district']."",  $selfstr12);
									$selfstr14 = str_replace("#city#", "".$reg_info[0]['city']."",  $selfstr13);
									$selfstr15 = str_replace("#state#", "".$reg_info[0]['state']."",  $selfstr14);
									$selfstr16 = str_replace("#pincode#", "".$reg_info[0]['pincode']."",  $selfstr15);
									$selfstr17 = str_replace("#designation#", "".$designation_name."",  $selfstr16);
									$selfstr18 = str_replace("#associatedinstitute#", "".$institution_name."",  $selfstr17);
									$selfstr19 = str_replace("#dateofbirth#", "".$reg_info[0]['dateofbirth']."",  $selfstr18);
									$selfstr20 = str_replace("#email#", "".$reg_info[0]['email']."",  $selfstr19);
									$selfstr21 = str_replace("#mobile#", "".$reg_info[0]['mobile']."",  $selfstr20);
									$selfstr22 = str_replace("#res_stdcode#", "".$reg_info[0]['res_stdcode']."",  $selfstr21);
									$selfstr23 = str_replace("#residential_phone#", "".$reg_info[0]['residential_phone']."",  $selfstr22);
									$selfstr24 = str_replace("#stdcode#", "".$reg_info[0]['stdcode']."",  $selfstr23);
									$selfstr25 = str_replace("#office_phone#", "".$reg_info[0]['office_phone']."",  $selfstr24);
									$selfstr26 = str_replace("#qualification#", "".$undergraduate_name.$graduate_name.$postgraduate_name."",  $selfstr25);
									$selfstr27 = str_replace("#specify_qualification#", "".$specify_qualification."",  $selfstr26);
									$selfstr28 = str_replace("#emergency_name#", "".$reg_info[0]['emergency_name']."",  $selfstr27);
									$selfstr29 = str_replace("#emergency_contact_no#", "".$reg_info[0]['emergency_contact_no']."",  $selfstr28);
									$selfstr30 = str_replace("#blood_group#", "".$reg_info[0]['blood_group']."",  $selfstr29);
									$selfstr31 = str_replace("#TRANSACTION_NO#", "".$payment_infoArr[0]['transaction_no']."",  $selfstr30);
									$selfstr32 = str_replace("#AMOUNT#", "".$payment_infoArr[0]['amount']."",  $selfstr31);
									$selfstr33 = str_replace("#STATUS#", "Transaction Successful",  $selfstr32);
									$final_selfstr = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_infoArr[0]['date']))."",  $selfstr33);
									$s1 = str_replace("#program_code#", "".$reg_info[0]['program_code'],$emailerSelfStr[0]['subject']);
									$final_sub = str_replace("#Batch_code#", "".$reg_info[0]['batch_code']."",  $s1);
									/* Get Client Emails Details */
									$emailsQry = $this->db->query("SELECT emails FROM offline_email_master WHERE zone_code = '" . $reg_info[0]['zone_code'] . "' AND program_code = '" . $reg_info[0]['program_code'] . "' AND batch_code = '" . $reg_info[0]['batch_code'] . "'AND isdelete = 0 LIMIT 1 ");
									$emailsArr    = $emailsQry->row_array();
									$emails  = $emailsArr['emails'];	
									$self_mail_arr = array(
										'to'=>$email,
										
									//'to'=>'nirmala.menezes@yahoo.co.in',
										'from'=>$emailerSelfStr[0]['from'],
										'subject'=> $final_sub.' - <strong>Venue Correction',
										'message'=>$final_selfstr);	
									if($this->Emailsending->mailsend_attch($self_mail_arr,$attachpath))
									{
										echo $email. ' '.$regnumber; 
									}
								}
							}
							public function index_pawan()
	{	//echo '**';exit;
	$final_str = "Please check your revised admitcard for PREVENTION OF CYBER CRIMES AND FRAUD MANAGEMENT Examination - Nov 2018 ."; 
	// $final_str = "Please check your revised admitcard for CAIIB examination 2018."; 
/*		$final_str ='<table class="MsoNormalTable" style="width: 37%; background: rgb(255, 255, 204) none repeat scroll 0% 0%;" width="37%" cellspacing="0" cellpadding="0" border="1">
<tbody style="">
<tr style="">
<td style="width: 100%; padding: 0cm;" width="100%">
<p style="margin-bottom: 12pt;">Date : 2018-03-31<br style=""><br style="">Mem No :  510294513<br style=""><br style="">Dear Member<br style=""><br style="">We have received your application for Duplicate ID card.<br style="">The Id card will be issued in your name JITENDRA DEWANGAN<br style=""><br style="">In case of non receipt of ID card within 20 days of submitting the online application, contact respective Zonal Office.<br style=""><br style="">Thanking you,</p>
</td>
</tr>
</tbody>
</table>
';*/
$info_arr=array(
						//'to'=>$result[0]['email'], 
		//'to'=>'poojagodse1221@gmail.com',
	'to'=>'himanshidua@yahoo.com', 
	'from'=>'noreply@iibf.org.in',
	'subject'=>'Exam Enrolment Acknowledgement',
						//'subject'=>'Duplicate ID Card Acknowledgement',
	'message'=>$final_str
);
$admitcard_pdf='https://iibf.esdsconnect.com/uploads/admitcardpdf/1770_738_510309311.pdf'; 
$attachpath="https://iibf.esdsconnect.com/uploads/examinvoice/user/510309311_EX_18-19_271792.jpg";   
	//	$attachpath='https://iibf.esdsconnect.com/uploads/dupicardinvoice/user/510294513_MDIC_17-18_01072.jpg'; 
if($attachpath!='')
{ 		   
	$files=array($attachpath,$admitcard_pdf);
			//$files=array($attachpath);
	$this->Emailsending->mailsend_attch($info_arr,$files);
	echo '<BR>	 Successfully Mail Send!!'; 
	echo  'to meber';
	echo '<br>';
	//echo  'RKUMAR300@YAHOO.COM';
		//echo 'sandeeppinto@rocketmail.com'; 
}
}
public function index()
	{	//exit;
		$result=$this->db->query('Select * from member_registration where regnumber =801177065 ');
		$result= $result->result_array();
		$exam_info=$this->db->query('Select * from member_exam where regnumber =801177065  AND	id=2561483');
		$exam_info= $exam_info->result_array();
		$medium[0]['medium_description']='ENGLISH';
		$payment_info=$this->db->query('Select * from payment_transaction where member_regnumber =801177065  AND	receipt_no=900474463');
		$payment_info= $payment_info->result_array();
		$decpass='Lbx4g0NQ';
		$mode='Online';
		$applicationNo='801177065';
		$exam_info[0]['center_name']='HYDERABAD';
		$exam_period_date='January 2018';
		$exam_info[0]['description']='CERTIFICATE COURSE IN FOREIGN EXCHANGE  Examination ';
		$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount,id');
		//$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'apply_exam_transaction_success'));
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
		$info_arr=array(
			'to'=>'apoorva1995j@gmail.com', 
			
//'to'=>'kyciibf@gmail.com',
			'from'=>$emailerstr[0]['from'],
			'subject'=>$emailerstr[0]['subject'],
			'message'=>$final_str
		);
		echo '<pre>';
/*				$info_arr=array(
//'to'=>'',
'to'=>'kyciibf@gmail.comapoorva1995j@gmail.com',
										
										'from'=>$emailerstr[0]['from'],
										'subject'=>"Exam Enrollment Acknowledgment",
										 
										'message'=>''
									);
			*/
				//get invoice 	
									$attachpath='https://iibf.esdsconnect.com/uploads/examinvoice/user/801177065_EX_17-18_134638.jpg';
									$admitcard_pdf='https://iibf.esdsconnect.com/uploads/admitcardpdf/IIBF_ADMIT_CARD_801177065.pdf';
		//	$admitcard_pdf='https://iibf.esdsconnect.com/uploads/admitcard_runtime/IIBF_ADMIT_CARD_510592.pdf';
									if($admitcard_pdf!='')
									{ 		   
										$files=array($attachpath,$admitcard_pdf);
		  //$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
										$this->Emailsending->mailsend_attch($info_arr,$files);
										echo '<BR>	 Successfully Mail Send!!';
										echo  'to me';
										echo '<br>';
										echo  $result[0]['email'];
				//$this->Emailsending->mailsend($info_arr);
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
													$info_arr=array(
//'to'=>$result[0]['email'],
														'to'=>'kyciibf@gmail.com',
														'from'=>$emailerstr[0]['from'],
														'subject'=>$emailerstr[0]['subject'],
														'message'=>$final_str 
													);
				//get invoice	
													$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
			//echo $this->db->last_query();exit;
													if(count($getinvoice_number) > 0)
													{
														if($getinvoice_number[0]['state_of_center']=='JAM')
														{
					//$invoiceNumber = generate_exam_invoice_number_jammu($MerchantOrderNo);
					/*if($invoiceNumber)
					{
						$invoiceNumber=$this->config->item('exam_invoice_no_prefix_jammu').$invoiceNumber;
					}*/
				}
				
				else
				{
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
				}	
		//	$attachpath='https://iibf.esdsconnect.com/uploads/examinvoice/user/510289865_EX_17-18_075277.jpg';
				$admitcard_pdf='C:\Users\esds001\Desktop\New folder\IIBF_ADMIT_CARD_500061652.pdf';
				echo 'JAIIB ';
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
		  //$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
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
				$info_arr=array(//'to'=>$result[0]['email'],
					'to'=>'kyciibf@gmail.com',
					'from'=>$emailerstr[0]['from'],
					'subject'=>$emailerstr[0]['subject'],
					'message'=>$final_str
				);
				//get invoice	
				$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
			//echo $this->db->last_query();exit;
				if(count($getinvoice_number) > 0)
				{
					if($getinvoice_number[0]['state_of_center']=='JAM')
					{
						$invoiceNumber = custom_genarate_exam_invoice_jk($getinvoice_number[0]['invoice_id']);
						if($invoiceNumber)
						{
							$invoiceNumber=$this->config->item('exam_invoice_no_prefix_jammu').$invoiceNumber;
						}
					}
					else
					{
						$invoiceNumber =custom_genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
						if($invoiceNumber)
						{
							$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
						}
					}
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
			//	$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
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
			$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[8],'auth_code' => '0399','bankcode' => $responsedata[9],'paymode' =>  $responsedata[12],'callback'=>'DV');
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
					if($getinvoice_number[0]['state_of_center']=='JAM')
					{
						$attachpath=custom_genarate_exam_invoice_jk($MerchantOrderNo);
					}
					else
					{
						$attachpath=custom_genarate_exam_invoice($MerchantOrderNo);
					}
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
				$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[8],'auth_code' =>'0399','bankcode' => $responsedata[9],'paymode' =>  $responsedata[12],'callback'=>'DV');
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
							'to'=>'kyciibf@gmail.com',
										//'to'=>'21bhavsartejasvi@gmail.com',
							'from'=>$emailerstr[0]['from'],
							'subject'=>$emailerstr[0]['subject'],
							'message'=>$final_str 
						);
						echo 'final';
						$attachpath='https://iibf.esdsconnect.com/uploads/examinvoice/user/700017858_EX_17-18_054050.jpg';
						$admitcard_pdf='https://iibf.esdsconnect.com/uploads/admitcardpdf/42_217_700017858.pdf';
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
						$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[8],'auth_code' => '0399','bankcode' => $responsedata[9],'paymode' =>  $responsedata[12],'callback'=>'DV');
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
				$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[8],'auth_code' => '0399','bankcode' => $responsedata[9],'paymode' =>  $responsedata[12],'callback'=>'DV');
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
					$info_arr=array(//	'to'=>$result[0]['email'],
						'to'=>'kyciibf@gmail.com',
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
function contact_class_mail()
{
	 $receipt_no = '902903479';
	 $member_no = '500104164';
	 $payment_info = $this->master_model->getRecords('payment_transaction', array(
                            'receipt_no' => $receipt_no,
                            'status' => 1
                        ));
						echo $this->db->last_query();
                        $member       = $this->db->query("SELECT *
															FROM contact_classes_registration
															WHERE contact_classes_id IN (
																SELECT MAX(contact_classes_id)
																FROM contact_classes_registration
																GROUP BY member_no
															) and pay_status = 1 AND member_no=500104164");
                        $memtype      = $member->result_array();
						echo '<br>'.$this->db->last_query();
                        //get center name
                        
                        $this->db->where('center_code', $memtype[0]['center_code']);
                        $center_info = $this->master_model->getRecords('contact_classes_center_master');
                        
                          $user_info  = $this->master_model->getRecords('contact_classes_Subject_registration', array(
                            'member_no' => $member_no,'center_code'=>$memtype[0]['center_code'],'contact_classes_regid'=>$memtype[0]['contact_classes_id']
                        ));
                       
                        $emailerstr = $this->master_model->getRecords('emailer', array(
                            'emailer_name' => 'contactclasses'
                        ));
                         $selfstr1  = str_replace("#regnumber#", "" . $member_no . "", $emailerstr[0]['emailer_text']);
                        $selfstr2  = str_replace("#program_name#", "" . $user_info[0]['program_name'] . "", $selfstr1);
                        $selfstr3  = str_replace("#center_name#", "" . $center_info[0]['center_name'] . "", $selfstr2);
                        $selfstr4  = str_replace("#venue_name#", "" . $user_info[0]['venue_name'] . "", $selfstr3);
                        $selfstr7  = str_replace("#name#", "" . $memtype[0]['namesub'] . " " . $memtype[0]['firstname'] . " " . $memtype[0]['middlename'] . " " . $memtype[0]['lastname'], $selfstr4);
                        $selfstr8  = str_replace("#address1#", "" . $memtype[0]['address1'] . "", $selfstr7);
                        $selfstr9  = str_replace("#address2#", "" . $memtype[0]['address2'] . "", $selfstr8);
                        $selfstr10 = str_replace("#address3#", "" . $memtype[0]['address3'] . "", $selfstr9);
                        $selfstr11 = str_replace("#address4#", "" . $memtype[0]['address4'] . "", $selfstr10);
                        
                        $selfstr12 = str_replace("#district#", "" . $memtype[0]['district'] . "", $selfstr11);
                        $selfstr13 = str_replace("#city#", "" . $memtype[0]['city'] . "", $selfstr12);
                        $selfstr14 = str_replace("#state#", "" . $memtype[0]['state'] . "", $selfstr13);
                        $selfstr15 = str_replace("#pincode#", "" . $memtype[0]['pincode'] . "", $selfstr14);
                        $selfstr19 = str_replace("#email#", "" . $memtype[0]['email'] . "", $selfstr15);
                        $selfstr20 = str_replace("#mobile#", "" . $memtype[0]['mobile'] . "", $selfstr19);
                        
                        $selfstr29     = str_replace("#TRANSACTION_NO#", "" . $payment_info[0]['transaction_no'] . "", $selfstr20);
                        $selfstr30     = str_replace("#AMOUNT#", "" . $payment_info[0]['amount'] . "", $selfstr29);
                        $selfstr31     = str_replace("#STATUS#", "Transaction Successful", $selfstr30);
                        $final_selfstr = str_replace("#TRANSACTION_DATE#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $selfstr31);
                        
                        
                        //	$newstring1 = str_replace("#NO#", "". $subscription_number."",  $emailerstr[0]['emailer_text']);
                        //	$final_str= str_replace("#DATE#",  $emailerstr[0]['emailer_text']);
                          $info_arr = array('to'=>$memtype[0]['email'],
                  //  'to' => 'kyciibf@gmail.com',
                            'from' => $emailerstr[0]['from'],
                            'subject' => $emailerstr[0]['subject'],
                            'message' => $final_selfstr
                        );
						$zone_code = 'NZ';
						  if ($zone_code == 'NZ') {
                            $client_arr = array(
                            // 'to'=>'kyciibf@gmail.com',
                               'to'=>'chaitali.jadhav@esds.co.in',
                                'from' => $emailerstr[0]['from'],
                                'subject' => $emailerstr[0]['subject'],
                                'message' => $final_selfstr
                            );
                        } exit;
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
	public function admit_card_sendmail(){ 
		
		$member_array = array(500015335,510124037,510242188,7529877,500183196,500033729,510321744,510249344,510482366,510482196,510106107,500165390,510265772,510266312,801938762,500092106,510224955,500157636,510011766,500082001,510084397,510283164,510526235,801674139,510531668,801260452,510375156,801947520,510551118,801197202,801963594,510125259,510551066,801982578,510506788,510015205,500058653,510551140,510043113,801260345,510180242,510476229,500061237,500051276,100051611,500069357,510346713,510546194,510161111,500145487,500131973,500100540,500078227,500023235,500131515,510349263,510108438,510105637,500158397,510449944,510466379,510226010,510531640,510345721,510341431,510431004,510307726,400046127,510217194,510063965,510410305,510325470,500074855,500050141,801218224,801218224,510052992,801426833,801256087,801978610,510511893,801176140,510437030,510356673); 
		 
		$this->db->like('created_on','2022-07-'); 
		$this->db->distinct();
		$this->db->select('pwd,mem_mem_no,exm_cd,admitcard_image');
		$this->db->where('exm_prd','806');
		$this->db->where_in('mem_mem_no',$member_array);
		$sql = $this->master_model->getRecords('admit_card_details');
		
		
		$final_str = 'Please check your  admit card letter for RPE examination (23rd July 2022) under your Profile.';
		
		foreach($sql as $rec){
			
			$email = $this->master_model->getRecords('member_registration',array('regnumber'=>$rec['mem_mem_no'],'isactive'=>'1'),'email'); 
			
			
			
			$attachpath = "uploads/admitcardpdf/".$rec['admitcard_image'];
			
			$info_arr=array('to'=>$email[0]['email'],
							//'to'=>'ztest2500@gmail.com',
							'from'=>'noreply@iibf.org.in',
							'subject'=>'Exam Enrolment Acknowledgement',
							'message'=>$final_str
						);
						
			
			$files=array($attachpath);
			
			
			
			if($this->Emailsending->mailsend_attch($info_arr,$files)){
				/* $update_array = array('mail_send'=>'1');
				$this->master_model->updateRecord('admit_card_details_21_118',$update_array,array('admitcard_image'=>$rec['admitcard_image']));
				 */
				echo "Mail send to => ".$rec['mem_mem_no'];
				echo "<br/>"; 
			}
			
			
				
		}
	}
	public function send_mail(){
		ini_set("memory_limit", "-1");
		//$yesterday = date('Y-m-d', strtotime("- 1 day"));
		$mem = array(801837343,801837579,801838750);
		//510522124,510528915,510530944
		$this->db->select("a.regnumber,a.regid,a.email,a.mobile");
		$this->db->where_in('a.regnumber', $mem);
		$new_mem_reg = $this->Master_model->getRecords('member_registration a',array('isactive'=>'1','isdeleted'=>0));
		//,'excode'=>991,'exam_period'=> 998
		if(COUNT($new_mem_reg) > 0){
			foreach ($new_mem_reg as $new_mem_reg) {
				$mem_pass = $this->getpassword($new_mem_reg['regnumber']);
				//$this->db->where_in('exam_code', array('991')); 
				//$exam_name = $this->master_model->getRecords('exam_master','','description');
				$final_str = 'Hello Sir/Madam <br/><br/>';
				$final_str.= 'Kindly update your Photo,signture and Idproof of examination otherwise your exam application will not be process futher.' . '<a href = "https://iibf.esdsconnect.com/nonmem"> https://iibf.esdsconnect.com/nonmem</a>';   
				$final_str.= '<br/><br/>';
				$final_str.= 'Member no:- '.$new_mem_reg['regnumber'];
				$final_str.= '<br/><br/>';
				$final_str.= 'Password:- '.$mem_pass;
				//$final_str.= 'Password:- <a> https://iibf.esdsconnect.com/dwnletter/getpassword/'.$new_mem_reg['regnumber'].'</a>';
				$final_str.= '<br/><br/>';
				$final_str.= 'Regards,';
				$final_str.= '<br/>';
				$final_str.= 'IIBF TEAM'; 
			//$attachpath = "uploads/admitcardpdf/".$rec['admitcard_image'];  
				$info_arr=array('to'=>$new_mem_reg['email'],
							//'to'=>'chaitali.jadhav@esds.co.in',
					'from'=>'noreply@iibf.org.in',
					'subject'=>'IIBF: upload photo,signature and idproof',
					'message'=>$final_str
				); 
			//$this->Emailsending->mailsend_attch($info_arr,$files);
				if($this->Emailsending->mailsend_attch($info_arr,$mem)){
					echo "Mail send to => ".$new_mem_reg['regnumber'];
					echo "<br/>"; 
					$insert_data  = array(
						'member_no' => $new_mem_reg['regnumber'],
						'email' => '1',
						'update_date' => date('Y-m-d H:i:s')
					);
					$this->master_model->insertRecord('daily_csc_admitcard', $insert_data);
					
				}
			}
		}    
	}
	
	public function send_regmem_mail()
	{
		ini_set("memory_limit", "-1");
		//$yesterday = date('Y-m-d', strtotime("- 1 day"));
		$mem = array(801552264);
		$this->db->select("a.regnumber,a.regid,a.email,a.mobile");
		$this->db->where_in('a.regnumber', $mem);
		$new_mem_reg = $this->Master_model->getRecords('member_registration a',array('isactive'=>'1','isdeleted'=>0));
//,'excode'=>991,'exam_period'=> 998
		if(COUNT($new_mem_reg) > 0){
			foreach ($new_mem_reg as $new_mem_reg) {
				//$this->db->where_in('exam_code', array('991')); 
				$exam_name = $this->master_model->getRecords('exam_master','','description');
				$final_str = 'Hello Sir/Madam <br/><br/>';
				$final_str.= 'Kindly find your member no and password for '.$exam_name[0]['description'].' examination' . '<a> https://iibf.esdsconnect.com/nonmem/ </a>';   
				$final_str.= '<br/><br/>';
				$final_str.= 'Member no:- '.$new_mem_reg['regnumber'];
				$final_str.= '<br/><br/>';
				//$final_str.= 'Password:- <a> https://iibf.esdsconnect.com/dwnletter/getpassword/'.$new_mem_reg['regnumber'].'</a>';
				$final_str.='Password:- sO02X9WR';	
				$final_str.= '<br/><br/>';
				$final_str.= 'Regards,';
				$final_str.= '<br/>';
				$final_str.= 'IIBF TEAM'; 
			//$attachpath = "uploads/admitcardpdf/".$rec['admitcard_image'];  
				$info_arr=array('to'=>$new_mem_reg['email'],
			//'to'=>'chaitali.jadhav@esds.co.in',
			
					'from'=>'noreply@iibf.org.in',
					'subject'=>'IIBF: Member Details',
					'message'=>$final_str
				); 
			//$this->Emailsending->mailsend_attch($info_arr,$files);
				if($this->mailsend_attch($info_arr,$mem)){
					$insert_data  = array(
		'member_no' => $new_mem_reg['regnumber'],
		'email' => '1',
						'update_date' => date('Y-m-d H:i:s')
					);
					$this->master_model->insertRecord('daily_csc_admitcard', $insert_data);
				}
	}
		}    
	}
	
	public function send_mail_jaiib_exam()
	{
		ini_set("memory_limit", "-1");
		//$yesterday = date('Y-m-d', strtotime("- 1 day"));
		$mem = array(510126326,510215582,510301234,510311053,510321683,510356488,510358121,510378554,510409754,510410692,510432407,510488511,510490782,510515121,510522226);
		$this->db->select("a.regnumber,a.regid,a.email,a.mobile");
		$this->db->where_in('a.regnumber', $mem);
		$new_mem_reg = $this->Master_model->getRecords('member_registration a',array('isactive'=>'1','isdeleted'=>0));
//,'excode'=>991,'exam_period'=> 998
		if(COUNT($new_mem_reg) > 0){
			foreach ($new_mem_reg as $new_mem_reg) {
				//$this->db->where_in('exam_code', array('991')); 
				$exam_name = $this->master_model->getRecords('exam_master','','description');
				$final_str = 'Hello Sir/Madam <br/><br/>';
				$final_str.= 'Your previous application of NOV-2022 JAIIB exam is refunded because of incorrect fees,
				Requesting you to kindly apply again for JAIIB.';   
				$final_str.= '<br/><br/>';
				$final_str.= 'Member no:- '.$new_mem_reg['regnumber'];
				$final_str.= '<br/><br/>';
				$final_str.= 'Password:- '.$mem_pass;
				//$final_str.= 'Password:- <a> https://iibf.esdsconnect.com/dwnletter/getpassword/'.$new_mem_reg['regnumber'].'</a>';
				//$final_str.='Password:- sO02X9WR';	
				$final_str.= '<br/><br/>';
				$final_str.= 'Regards,';
				$final_str.= '<br/>';
				$final_str.= 'IIBF TEAM'; 
				$info_arr=array('to'=>$new_mem_reg['email'],
			//'to'=>'chaitali.jadhav@esds.co.in',
			
					'from'=>'noreply@iibf.org.in',
					'subject'=>'IIBF: Member Details',
					'message'=>$final_str
				); 
			//$this->Emailsending->mailsend_attch($info_arr,$files);
				if($this->mailsend_attch($info_arr,$mem)){
					$insert_data  = array(
		'member_no' => $new_mem_reg['regnumber'],
		'email' => '1',
						'update_date' => date('Y-m-d H:i:s')
					);
					$this->master_model->insertRecord('daily_csc_admitcard', $insert_data);
				}
	}
		}    
	}
	
	public function getpassword($member){ 
		
		include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
		$number = $member;
		$res = $this->master_model->getRecords('member_registration',array('regnumber'=>$number),'usrpassword');
		$key = $this->config->item('pass_key');
		$aes = new CryptAES();
		$aes->set_key(base64_decode($key));
		$aes->require_pkcs5();
		return $decpass = $aes->decrypt(trim($res[0]['usrpassword']));
		//iibf.teamgrowth.net/dwnletter/getpassword/511000086
	}
	public function send_mem_mail_refund()
	{
		ini_set("memory_limit", "-1");
		//$yesterday = date('Y-m-d', strtotime("- 1 day"));
		$mem = array(510238109,510361739);
		$this->db->select("a.regnumber,a.regid,a.email,a.mobile");
		$this->db->where_in('a.regnumber', $mem);
		$new_mem_reg = $this->Master_model->getRecords('member_registration a',array('isactive'=>'1','isdeleted'=>0));
		//echo $new_mem_reg[0]['email'];
		if(COUNT($new_mem_reg) > 0){
			foreach ($new_mem_reg as $new_mem_reg) {
				$mem_pass = $this->getpassword($new_mem_reg['regnumber']);
				//exit;
 				//$this->db->where_in('exam_code', array('991')); 
				//$exam_name = $this->master_model->getRecords('exam_master','','description');
				$final_str = 'Hello Sir/Madam <br/><br/>';
				$final_str.= 'Your CAIIB exam Amount is refunded as there was not capacity for selected center,soon you will receive Amount in your account.';   
				$final_str.= '<br/><br/>';
				$final_str.= 'Member no:- '.$new_mem_reg['regnumber'];
				$final_str.= '<br/><br/>';
				$final_str.= 'Password:- '.$mem_pass;
				$final_str.= '<br/><br/>';
				$final_str.= 'Requesting you to again apply with the other center.';
				$final_str.= '<br/><br/>';
				
				$final_str.= 'Regards,';
				$final_str.= '<br/>';
				$final_str.= 'IIBF TEAM'; 
			//$attachpath = "uploads/admitcardpdf/".$rec['admitcard_image'];  
				$info_arr=array('to'=>$new_mem_reg['email'],
					//'to'=>'chaitali.jadhav@esds.co.in',
					'from'=>'noreply@iibf.org.in',
					'subject'=>'IIBF: Exam Refund',
					'message'=>$final_str
				); 
			$this->Emailsending->mailsend_attch($info_arr,'');
				/* if($this->Emailsending->mailsend_attch($info_arr,'https://iibf.esdsconnect.com/uploads/admitcardpdf/991_998_802059593.pdf')){
					$insert_data  = array(
						'member_no' => $new_mem_reg['regnumber'],
						'email' => '1',
						'update_date' => date('Y-m-d H:i:s')
					);
					$this->master_model->insertRecord('daily_csc_admitcard', $insert_data);
					
				} */
			}
		}    
	
	}
	public function send_mem_mail()
	{
		ini_set("memory_limit", "-1");
		//$yesterday = date('Y-m-d', strtotime("- 1 day"));
		$mem = array(802049181,802058705);
		$this->db->select("a.regnumber,a.regid,a.email,a.mobile");
		$this->db->where_in('a.regnumber', $mem);
		$new_mem_reg = $this->Master_model->getRecords('member_registration a',array('isactive'=>'1','isdeleted'=>0));
		//echo $new_mem_reg[0]['email'];
		if(COUNT($new_mem_reg) > 0){
			foreach ($new_mem_reg as $new_mem_reg) {
				$mem_pass = $this->getpassword($new_mem_reg['regnumber']);
				//exit;
 				//$this->db->where_in('exam_code', array('991')); 
				//$exam_name = $this->master_model->getRecords('exam_master','','description');
				$final_str = 'Hello Sir/Madam <br/><br/>';
				$final_str.= 'Kindly find your member no and password  ';   
				$final_str.= '<br/><br/>';
				$final_str.= 'Member no:- '.$new_mem_reg['regnumber'];
				$final_str.= '<br/><br/>';
				$final_str.= 'Password:- '.$mem_pass;
				$final_str.= '<br/><br/>';
				$final_str.= 'Regards,';
				$final_str.= '<br/>';
				$final_str.= 'IIBF TEAM'; 
			//$attachpath = "uploads/admitcardpdf/".$rec['admitcard_image'];  
				$info_arr=array(//'to'=>$new_mem_reg['email'],
					'to'=>'vishal.phadol@esds.co.in',
					'from'=>'noreply@iibf.org.in',
					'subject'=>'IIBF: Member Details',
					'message'=>$final_str
				); 
			//$this->Emailsending->mailsend_attch($info_arr,$files);
				if($this->Emailsending->mailsend_attch($info_arr,'https://iibf.esdsconnect.com/uploads/admitcardpdf/991_998_802059593.pdf')){
					$insert_data  = array(
						'member_no' => $new_mem_reg['regnumber'],
						'email' => '1',
						'update_date' => date('Y-m-d H:i:s')
					);
					$this->master_model->insertRecord('daily_csc_admitcard', $insert_data);
					
				}
			}
		}    
	
	}
	
	public function send_mail_blended()
	{
		ini_set("memory_limit", "-1");
		//$yesterday = date('Y-m-d', strtotime("- 1 day"));
		$mem = array('510063936','510300315','510194812','510333270','510301265','500156163','200003714','510261183','510449414','510204966','510066146','510365548','510206297','500206323','510113659','510164092','510408316','510094797','510035695','510065698','500177559','510235747','500123784','500105474','510301537','510021104','510304299','510145501','500091284','510354747','510344105','510040219','500128574','7250032','510401744','500033337','510285190','510246485','510450570','510450569','510406362','510370753','510153659','801261290','510090040','500162228','510425206','500192873','500055102','510151238','510325066','510278044','500087570','801447223','510278530','510092241','510214993','510025356','100090191','500135554','500032537','500141829','510038438','510279232','510342540','510013154','510338573','510142519','500050432','510285412','500049793','510135445','510208087','510023866','510268653','510104801','510213374','500027166','510440344','510123234','500109720','510321685','510017580','500014577','500143538','500147837','510419279','510287334','510230000','510007510','510045261');
		//$this->db->select("a.member_no,a.blended_id,a.email,a.mobile,b.transaction_no,b.amount,a.firstname");
		//$this->db->join('payment_transaction b','a.blended_id = b.ref_id','LEFT');
		//$this->db->where_in('a.member_no', $mem);
		//$new_mem_reg = $this->Master_model->getRecords('blended_registration a',array('a.pay_status'=>'1' , 'a.program_code'=>'RFS','b.pay_type'=>'10','b.status'=>'1','batch_code'=>'VRFS010'));
	 $this->db->select("a.member_no,a.blended_id,a.email,a.mobile,a.firstname");
		//$this->db->join('payment_transaction b','a.blended_id = b.ref_id','LEFT');
		$this->db->where_in('a.member_no', $mem);
		$new_mem_reg = $this->Master_model->getRecords('blended_registration a',array('a.pay_status'=>'1' , 'a.program_code'=>'CCP','batch_code'=>'VCCP027'));
	
	 echo $this->db->last_query();
		//die;
		 
		  
		if(COUNT($new_mem_reg) > 0){  
			$final_str = '';
			foreach ($new_mem_reg as $res_new_mem_reg) 
			{
			
				 
				$final_str = 'Hello&nbsp;'.$res_new_mem_reg['firstname'].', <br/><br/>'; 
				$final_str.= '38th Post Examination Virtual Mode Training for Certified Credit Professionals scheduled from 7th to 9th April, 2020 IS NOW RESCHEDULED FROM 5TH TO 7TH JUNE , 2020.';   
				$final_str.= '<br/><br/>';
				$final_str.= 'In case you DO NOT attend the Training program , your attempt will be counted. ';
				$final_str.= '<br/><br/>';
				$final_str.= 'In case of 1st attempt getting exhausted , YOU have to  then enroll for the next training program as and when announced by the Institute  and pay fees as per extant guidelines.';
				$final_str.= '<br/><br/>';
				//$final_str.= 'Avani Heights, 2nd Floor';
				//$final_str.= '<br/>';
				//$final_str.= '59A, Jawaharlal Nehru Road';
				//$final_str.= '<br/>';
				$final_str.= 'The Login credentials for the Program shall be shared by 4th June , 2020 (4 pm).';
				$final_str.= '<br/><br/>';
				$final_str.= 'Regards,';
				$final_str.= '<br/>';
				$final_str.= 'Training department , IIBF'; 
		 
				$info_arr=array('to'=>$res_new_mem_reg['email'],
				'from'=>'noreply@iibf.org.in',
				'subject'=>'IIBF: Blended Training ',
				'message'=>$final_str
			); 
			
				if($this->Emailsending->mailsend_attch($info_arr,$mem)){
				$insert_data  = array(
					'member_no' => $res_new_mem_reg['member_no'],
					'email' => '1',
					'update_date' => date('Y-m-d H:i:s')
				);
				$this->master_model->insertRecord('daily_csc_admitcard', $insert_data);
			}
			
				
			}
	
		}
	}
	
	public function send_mail_bk(){
		ini_set("memory_limit", "-1");
		//$yesterday = date('Y-m-d', strtotime("- 1 day"));
		$email = array('gaurav5432singh@gmail.com','injamam.ahmad9223@gmail.com','tamrkar.ashok@gmail.com','anilgautam06661@gmail.com','hankabir8@gmail.com','minaruli19@gmail.com','rgita4744@gmail.com','p.bijlwan80@gmail.com','dubeyindraprakash@gmail.com','ketanrajput004@gmail.com','pramodbdn0010@gmail.com','rajesh.pd0305@gmail.com','mukeshsoni2789@gmail.com','manikantmishra1101@gmail.com','RAJESHKUMARMUKHIYA2020@GMAIL.COM','sudarshanksingha@gmail.com','IMRANALI2649@GMAIL.COM','msushilnanded@gmail.com','ishwarchandm2@gmail.com','antrajalkendra@gmail.com','agarwal.h99@gmail.com','ambigaiassociates@gmail.com','mohibulsk122@gmail.com','SHIVA.KSS123@GMAIL.COM','nitya.sbi2020@gmail.com','prasadadya@gmail.com','kailashpal261@gmail.com','vinku0076@gmail.com','msyadav29195@gmail.com','misbah.shine@gmail.com','ahsanahmadmacet@gmail.com','gothisir@gmail.com','vinayakenterprises5227@gmail.com','renukadasp453@gmail.com','AKRBL89@GMAIL.COM','mohammadkamrain786@gmail.com','priyankurjoybhattacharjee@gmail.com','AK63705@GMAIL.COM','ASHISHBAGHEL.1999@GMAIL.COM','akhileshydvgpa@gmail.com','rajesh19861842@gmail.com','nirinani@gmail.com','pancholicomputers@gmail.com','rajasekharvelugu@gmail.com','mscomputerknd@gmail.com','kumar.dharmendra7852817328@gmail.com','sevanthreddy@gmail.com','beheraprasant51@gmail.com','surajtanwar86@gmail.com','satyabratanayak15@gmail.com','geetasalunkhe90@gmail.com','ravikant2aug@gmail.com','mahendrapkhandare@gmail.com','kambariya61@gmail.com','kashemali8060@gmail.com','puvala.rushikesh@gmail.com','smtdccsc@gmail.com','afazahmed1981@gmail.com','at040480@gmail.com');
		if(COUNT($email) > 0){
			foreach ($email as $email) {
				$final_str = 'Dear Candidate, <br/><br/>';
				$final_str.= 'You cannot appear for examination as your application has been rejected . Your amount will be refunded in 10-15 working days.';
            	$final_str.= '<br/><br/>';
				$final_str.= 'Regards,';
				$final_str.= '<br/>';
				$final_str.= 'IIBF TEAM'; 
				$info_arr=array('to'=>$email,
							//'to'=>'chaitali.jadhav@esds.co.in',
					'from'=>'noreply@iibf.org.in',
					'subject'=>'IIBF:Exam Note',
					'message'=>$final_str
				); 
			  	$this->Emailsending->mailsend_attch($info_arr,$email);
					echo "Mail send to => ".$email;
					echo "<br/>"; 
			}
		}
	}    
public function mailsend_attch($info_arr,$path)
	{
		   $this->setting_smtp();
		   //$config = Array('mailtype'  => 'html', 'charset' => 'utf-8','wordwrap' => TRUE);
			//$this->email->initialize($config);
			//$this->email->from($info_arr['from'],"iibf.com"); 
			$this->email->from('logs@iibf.esdsconnect.com',"IIBF"); 
			$this->email->to($info_arr['to']);
			//$this->email->to('spnair@iibf.org.in');
			$this->email->reply_to('noreply@iibf.org.in', 'IIBF');
			$this->email->cc('iibfdevp@esds.co.in');	// CC email added by Bhagwan Sahane, on 03-06-2017
			$this->email->subject($info_arr['subject']);
			$this->email->message($info_arr['message']);
			if(is_array($path))
			{
				foreach($path as $row)
				{
					$this->email->attach($row);
				}
			}
			else
			{
				if($path!=NULL || $path!='')
				{
					$this->email->attach($path);
				}
			}
			if($this->email->send())
			{
				//$this->email->print_debugger();
				//	echo $this->email->print_debugger();
				$this->email->clear(TRUE);
				return true;
			}
							
							
	}
// SMTP email setting here
	public function setting_smtp()
	{
		$permission=TRUE;
		
		if($permission==TRUE)
		{
			$config['protocol']    	= 'SMTP';
			//$config['smtp_host']    = 'iibf.esdsconnect.com';
			$config['smtp_host']    = '115.124.123.26';
			$config['smtp_port']    = '465';
			$config['smtp_timeout'] = '10';
			$config['smtp_user']    = 'logs@iibf.esdsconnect.com';
			$config['smtp_pass']    = 'logs@IiBf!@#';
			$config['charset']    	= 'utf-8';
			$config['newline']    	= "\r\n";
			$config['mailtype'] 	= 'html'; // or html
			$config['validation'] 	= TRUE; // bool whether to validate email or not  
			$this->email->initialize($config);	
		}
	}

	public function Venue_replace_pratibha()
	{ 
		$duplicate_venue=$oldcapacity_greater=array();
		$count_upadted=0;
		$count_upadted_not=0;
		$new_venue_details = $this->master_model->getRecords('update_venue_master_pratibha',array('Updated_status'=>'no'));
		//print_r($new_venue_details);
		foreach($new_venue_details as $val){
		//print_r($val);
			$old_venue_details = $this->master_model->getRecords('venue_master',
				array('center_code'=>$val['center_code'],
					'venue_code'=>$val['venue_code'],
					'exam_date'=>$val['exam_date'],
					'session_time'=>$val['session_time']
				));
				
			//echo '<pre>';
			//print_r($old_venue_details);
			if(count($old_venue_details)==1)
			{
				if($old_venue_details[0]['session_capacity'] <=$val['session_capacity'])
				{
					$update_data = array('session_capacity'=>$val['session_capacity']);
					$this->master_model->updateRecord('venue_master',$update_data, array('center_code'=>$val['center_code'],
						'venue_code'=>$val['venue_code'],
						'exam_date'=>$val['exam_date'],
						'session_time'=>$val['session_time']
					));
					$update_data_new = array('Updated_status'=>'yes');
					$this->master_model->updateRecord('update_venue_master_pratibha',$update_data_new,array('center_code'=>$val['center_code'],
						'venue_code'=>$val['venue_code'],
						'exam_date'=>$val['exam_date'],
						'session_time'=>$val['session_time']
					));	
					$count_upadted++;
				}elseif($old_venue_details[0]['session_capacity'] >=$val['session_capacity'])
				{
					$oldcapacity_greater[]=$old_venue_details;
				}
				$count_upadted_not++;
			}elseif(count($old_venue_details)>1)
			{
	 			//$count_upadted_not++;
				$duplicate_venue[]=$old_venue_details;
				//echo 'Duplicate recode found for the venue: ';
			}
		}
		echo 'Recode updated sucessfully: ';
		echo '<br>';	
		echo  $count_upadted;
		echo '<br>';
		echo 'Duplicate recode found for the venue: ';
		echo '<br>';
		echo '<br>';
		echo 'venue_code'.' '.'exam_date'.' '.'center_code'.'session_time'.' '.'session_capacity';
		echo '<br>';
		foreach($duplicate_venue as $rec2)
		{
			foreach($rec2 as $val2)
			{
				echo $val2['venue_code'].' '.$val2['exam_date'].' '.$val2['center_code'].' '.$val2['session_time'].' '.$val2['session_capacity'];
				echo '<br>';
			}
		}
		//echo '<br>';
		echo '<br>';
		echo 'old capacity is more and new capcity is less : ';
		echo '<br>';
		echo '<br>';
		echo 'venue_code'.' '.'exam_date'.' '.'center_code'.'session_time'.' '.'session_capacity';
		echo '<br>';
		foreach($oldcapacity_greater as $rec)
		{
			foreach($rec as $val1)
			{
				echo $val1['venue_code'].' '.$val1['exam_date'].' '.$val1['center_code'].' '.$val1['session_time'].' '.$val1['session_capacity'];
				echo '<br>';
			}
		}
		//echo '<br>';
		echo '<br>';
	}
}