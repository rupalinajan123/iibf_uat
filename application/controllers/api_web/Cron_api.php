<?php 
/********************************************************************
* Created BY	: Sagar Matale
* Update By 	: Sagar Matale on 22-10-2021
* Description	: This is API controller, which is used to fetch the data.
********************************************************************/

	defined('BASEPATH') OR exit('No direct script access allowed');
	require_once APPPATH . '/libraries/REST_Controller.php';
	require_once APPPATH . '/libraries/Format.php';
	use Restserver\Libraries\REST_Controller;
	
	class Cron_api extends REST_Controller	/* extends CI_Controller / REST_Controller */
	{
		public function __construct() 
		{
			parent::__construct();
			$this->load->model('Master_model');
			$this->load->model('Cron_api_model');
			
			$this->headers = $this->input->request_headers();
			$this->class_name = $this->router->fetch_class();
			$this->method_name = $this->router->fetch_method();
			
			$api_key = '';
			if(isset($this->headers['Api-Key']))//GET API KEY FROM HEADERS
			{
				$api_key = $this->headers['Api-Key'];
			}
			$this->api_key = $api_key;
			
			$member_number = '';
			if(isset($this->headers['Member-Number']))//GET API KEY FROM HEADERS
			{
				$member_number = $this->headers['Member-Number'];
			}
			$this->member_number = $member_number;
			
			$start_limit = '';
			if(isset($this->headers['Start-Limit']))//GET API KEY FROM HEADERS
			{
				$start_limit = $this->headers['Start-Limit'];
			}
			$this->start_limit = $start_limit;
		}
		
		/*********************************************************************
		CREATED BY		: Sagar Matale
		UPDATE BY 		: Sagar Matale On 22-10-2021
		DESCRIPTION		: MEMBER REGISTRATION DATA
		PREVIOUS FILE : Cron.php => member()
		EXAM CODES		:	NOT IN (526, 527, 991)
		ACCESS LINK 	: 
		*********************************************************************/
		public function member_get()//$member_number=''//$start=0 //
		{
			$validate_token_response = $this->Cron_api_model->token_arr($this->class_name, $this->method_name, $this->api_key);//CHECK TOKEN VALIDATION
			
			//if no parameter, send only total count
			//if member number present, then send total count + specific member data
			//if member number not present, and limit is present, send that limit data + total count
			ini_set("memory_limit", "-1");
			//header("Access-Control-Allow-Origin: *");
			$result_data = array();				
			$current_date = date("Y-m-d");			
			//$log_id = date("YmdHis", strtotime($current_date." ".date("H:i:s")));
			
			$log_id=0; 
			$log_title='Member Registration Data'; 
			$record_count='0'; 
			$posted_data='';							
			
			try 
			{
				$message = 'Execution Started';				
				
				$log_id = $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
				
				$dir_flg = $parent_dir_flg = $file_w_flg = $photo_zip_flg = $sign_zip_flg = $idproof_zip_flg = $vis_imp_cert_img_zip_flg = $orth_han_cert_img_zip_flg = $cer_palsy_cert_img_zip_flg = 0;
				
				$member_number = $this->member_number; 
				$start_limit = $this->start_limit;

				if($member_number == '' && $start_limit == '')
				{
					$data['result'] = "true";
					$data['total_record_count'] = $this->Cron_api_model->get_member_data(1);
					$data['message'] = "";
					$data['error'] = "";
					$data['status'] = 200;
					
					$message .= ' - Only Count Provided - Execution Ended';					
					$this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
					
					echo json_encode($data);
				}
				else
				{
					$max_limit=200;					
					$new_mem_reg = $this->Cron_api_model->get_member_data(0, $member_number, $start_limit, $max_limit);						
					//print_r($new_mem_reg);				
					
					if($start_limit == '') { $sequence_number = 1; } else { $sequence_number = $start_limit+1; }
					if(count($new_mem_reg) > 0)
					{
						$i = $mem_cnt = $photo_cnt = $sign_cnt = $idproof_cnt = $vis_imp_cert_img_cnt = $orth_han_cert_img_cnt = $cer_palsy_cert_img_cnt = 0;
						
						foreach($new_mem_reg as $key => $reg_data)
						{
							$gender = '';
							if($reg_data['gender'] == 'male')	{ $gender = 'M';}
							else if($reg_data['gender'] == 'female')	{ $gender = 'F';}
							
							$qualification = '';
							switch($reg_data['qualification'])
							{
								case "U"	: 	$qualification = 'UG'; break;
								case "G"	: 	$qualification = 'G'; break;
								case "P"	: 	$qualification = 'PG'; break;												
							}
							
							$transaction_no = $transaction_date = '';
							$transaction_amt = '0';
							if($reg_data['registrationtype']!='NM')
							{
								if($reg_data['registrationtype'] == 'DB')
								{
									$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_EXAM_DB','pay_type'=>2,'member_regnumber'=>$reg_data['regnumber'],'status'=>1),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
								}
								else
								{
									$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'iibfregn','status'=>1,'pay_type'=>1,'ref_id'=>$reg_data['regid']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
								}
							}
							else
							{
								$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_EXAM_REG','status'=>1,'pay_type'=>2,'member_regnumber'=>$reg_data['regnumber']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
								
								// For Bulk Members if Non-Member Application 
								if(empty($trans_details))
								{
									$this->db->select("DATE(a.updated_date) AS date, a.UTR_no AS transaction_no, a.amount");
									$this->db->join('bulk_member_payment_transaction b','a.id = b.ptid','LEFT');
									$this->db->join('member_exam c','b.memexamid = c.id','LEFT');
									$this->db->join('member_registration d','d.regnumber = c.regnumber','LEFT');
									$this->db->where("( DATE(a.updated_date) = '".$yesterday."') AND a.status = 1 AND d.regnumber = '".$reg_data['regnumber']."'");
									$trans_details = $this->Master_model->getRecords('bulk_payment_transaction a');	
									//$trans_details = $this->db->get('bulk_payment_transaction a');
								}
							}
							
							if(count($trans_details))
							{
								$transaction_no = $trans_details[0]['transaction_no'];
								$transaction_amt = $trans_details[0]['amount'];
								$date = $trans_details[0]['date'];
								if($date!='0000-00-00') {	$transaction_date = date('d-M-y',strtotime($date));	}
							}
							elseif($reg_data['excode'] == '191' || $reg_data['excode'] == '1910' || $reg_data['excode'] == '19100')
							{
								$get_date = $this->Master_model->getRecords('member_registration',array('isactive'=>'1','regnumber'=>$reg_data['regnumber']),'DATE_FORMAT(createdon,"%Y-%m-%d") createdon');
								
								$date = $get_date[0]['createdon'];
								$transaction_date = date('d-M-y',strtotime($date));	
								$transaction_no = '000000000000';
								$transaction_amt = '0';	
							}
							
							$mem_dob = '';
							if($reg_data['dateofbirth'] != '0000-00-00')
							{
								$mem_dob = date('d-M-y',strtotime($reg_data['dateofbirth']));
							}
							
							$mem_doj = '';
							if($reg_data['dateofjoin'] != '0000-00-00')
							{
								$mem_doj = date('d-M-y',strtotime($reg_data['dateofjoin']));
							}
							
							if(strlen($reg_data['stdcode']) > 10) {	$std_code = substr($reg_data['stdcode'],0,9);	}
							else {	$std_code = $reg_data['stdcode'];	}
							
							if(strlen($reg_data['office']) > 20) {	$branch = substr($reg_data['office'],0,19);	}
							else {	$branch = $reg_data['office'];	}
							
							if(strlen($reg_data['address1']) > 30) {	$address1 = substr($reg_data['address1'],0,29);	}
							else {	$address1 = $reg_data['address1'];	}
							
							if(strlen($reg_data['address2']) > 30) {	$address2 = substr($reg_data['address2'],0,29);	}
							else {	$address2 = $reg_data['address2'];	}
							
							if(strlen($reg_data['address3']) > 30) {	$address3 = substr($reg_data['address3'],0,29);	}
							else {	$address3 = $reg_data['address3'];	}
							
							if(strlen($reg_data['address4']) > 30) {	$address4 = substr($reg_data['address4'],0,29);	}
							else {	$address4 = $reg_data['address4'];	}
							
							if(strlen($reg_data['district']) > 30) {	$district = substr($reg_data['district'],0,29);	}
							else {	$district = $reg_data['district'];	}
							
							if(strlen($reg_data['city']) > 30) {	$city = substr($reg_data['city'],0,29);	}
							else {	$city = $reg_data['city'];	}
							
							// code for permanent address fields
							if(strlen($reg_data['address1_pr']) > 30) {	$address1_pr = substr($reg_data['address1_pr'],0,29);	}
							else {	$address1_pr = $reg_data['address1_pr'];	}
							
							if(strlen($reg_data['address2_pr']) > 30) {	$address2_pr = substr($reg_data['address2_pr'],0,29);	}
							else {	$address2_pr = $reg_data['address2_pr'];	}
							
							if(strlen($reg_data['address3_pr']) > 30) {	$address3_pr = substr($reg_data['address3_pr'],0,29);	}
							else {	$address3_pr = $reg_data['address3_pr'];	}
							
							if(strlen($reg_data['address4_pr']) > 30) {	$address4_pr = substr($reg_data['address4_pr'],0,29);	}
							else {	$address4_pr = $reg_data['address4_pr'];	}
							
							if(strlen($reg_data['district_pr']) > 30) {	$district_pr = substr($reg_data['district_pr'],0,29);	}
							else {	$district_pr = $reg_data['district_pr'];	}
							
							if(strlen($reg_data['city_pr']) > 30) {	$city_pr = substr($reg_data['city_pr'],0,29);	}
							else {	$city_pr = $reg_data['city_pr'];	}
							
							$optnletter = "Y";
							if($reg_data['optnletter'] == "optnl") {	$optnletter = "Y";	}
							else {	$optnletter = $reg_data['optnletter'];	}
							
							// Benchmark Code Start
							$benchmark_disability = $reg_data['benchmark_disability'];
							$visually_impaired = $reg_data['visually_impaired'];
							$vis_imp_cert_img = '';
							$orthopedically_handicapped = $reg_data['orthopedically_handicapped'];
							$orth_han_cert_img = '';
							$cerebral_palsy = $reg_data['cerebral_palsy'];
							$cer_palsy_cert_img = '';
							
							if($benchmark_disability == 'Y')
							{
								$vis_imp_cert_img = '';
								if($visually_impaired == 'Y')
								{
									if(is_file("./uploads/disability/".$reg_data['vis_imp_cert_img']))
									{
										$vis_imp_cert_img = base_url("uploads/disability/".$reg_data['vis_imp_cert_img']);
									}
								}
								
								$orth_han_cert_img = '';
								if($orthopedically_handicapped == 'Y')
								{
									if(is_file("./uploads/disability/".$reg_data['orth_han_cert_img']))
									{
										$orth_han_cert_img 	= base_url("uploads/disability/".$reg_data['orth_han_cert_img']);
									}
								}
								
								$cer_palsy_cert_img = '';
								if($cerebral_palsy == 'Y')
								{
									if(is_file("./uploads/disability/".$reg_data['cer_palsy_cert_img']))
									{
										$cer_palsy_cert_img 	= base_url("uploads/disability/".$reg_data['cer_palsy_cert_img']);
									}
								}
							}						
							
							$scannedphoto = $scannedsignaturephoto = $idproofphoto = '';
							
							$member_images = $this->get_member_images($reg_data['image_path'], $reg_data['reg_no'], $reg_data['regnumber'], $reg_data['scannedphoto'], $reg_data['idproofphoto'], $reg_data['scannedsignaturephoto'], $reg_data['regid'], $yesterday);
							$scannedphoto = $member_images['scannedphoto'];
							$scannedsignaturephoto = $member_images['scannedsignaturephoto'];
							$idproofphoto = $member_images['idproofphoto'];
							
							if($scannedphoto != "") { $scannedphoto = /* addslashes($this->scaleImageFileToBlob($scannedphoto)); */ $this->image_to_binary($scannedphoto); }
							if($scannedsignaturephoto != "") { $scannedsignaturephoto = /* addslashes($this->scaleImageFileToBlob($scannedsignaturephoto)); */ $this->image_to_binary($scannedsignaturephoto); }
							if($idproofphoto != "") { $idproofphoto = /* addslashes($this->scaleImageFileToBlob($idproofphoto)); */ $this->image_to_binary($idproofphoto); }
							
							//$result_data[$key]['regid'] = $reg_data['regid'];
							$result_data[$key]['sequence_number'] = $sequence_number;
							$result_data[$key]['regnumber'] = $reg_data['regnumber'];
							$result_data[$key]['registrationtype'] = $reg_data['registrationtype'];
							$result_data[$key]['namesub'] = $reg_data['namesub'];
							$result_data[$key]['firstname'] = $reg_data['firstname'];
							$result_data[$key]['middlename'] = $reg_data['middlename'];
							$result_data[$key]['lastname'] = $reg_data['lastname'];
							$result_data[$key]['displayname'] = $reg_data['displayname'];
							$result_data[$key]['address1'] = $address1;
							$result_data[$key]['address2'] = $address2;
							$result_data[$key]['address3'] = $address3;
							$result_data[$key]['address4'] = $address4;
							$result_data[$key]['district'] = $district;
							$result_data[$key]['city'] = $city;							
							$result_data[$key]['pincode'] = $reg_data['pincode'];							
							$result_data[$key]['state'] = $reg_data['state'];							
							$result_data[$key]['address1_pr'] = $address1_pr;							
							$result_data[$key]['address2_pr'] = $address2_pr;							
							$result_data[$key]['address3_pr'] = $address3_pr;							
							$result_data[$key]['address4_pr'] = $address4_pr;							
							$result_data[$key]['district_pr'] = $district_pr;							
							$result_data[$key]['city_pr'] = $city_pr;							
							$result_data[$key]['pincode_pr'] = $reg_data['pincode_pr'];							
							$result_data[$key]['state_pr'] = $reg_data['state_pr'];							
							$result_data[$key]['mem_dob'] = $mem_dob;							
							$result_data[$key]['gender'] = $gender;							
							$result_data[$key]['qualification'] = $qualification;							
							$result_data[$key]['specify_qualification'] = $reg_data['specify_qualification'];							
							$result_data[$key]['associatedinstitute'] = $reg_data['associatedinstitute'];							
							$result_data[$key]['branch'] = $branch;							
							$result_data[$key]['designation'] = $reg_data['designation'];							
							$result_data[$key]['mem_doj'] = $mem_doj;					
							$result_data[$key]['email'] = $reg_data['email'];							
							$result_data[$key]['std_code'] = $std_code;							
							$result_data[$key]['office_phone'] = $reg_data['office_phone'];							
							$result_data[$key]['mobile'] = $reg_data['mobile'];							
							$result_data[$key]['idproof'] = $reg_data['idproof'];							
							$result_data[$key]['idNo'] = $reg_data['idNo'];							
							$result_data[$key]['transaction_no'] = $transaction_no;							
							$result_data[$key]['transaction_date'] = $transaction_date;							
							$result_data[$key]['transaction_amt'] = $transaction_amt;							
							$result_data[$key]['transaction_no'] = $transaction_no;							
							$result_data[$key]['optnletter'] = $optnletter;
							$result_data[$key]['photo'] = $scannedphoto;							
							$result_data[$key]['signature'] = $scannedsignaturephoto;							
							$result_data[$key]['idproofimg'] = $idproofphoto; 
							$result_data[$key]['aadhar_card'] = $reg_data['aadhar_card'];							
							$result_data[$key]['bank_emp_id'] = $reg_data['bank_emp_id'];							
							$result_data[$key]['benchmark_disability'] = $benchmark_disability;							
							$result_data[$key]['visually_impaired'] = $visually_impaired;							
							$result_data[$key]['vis_imp_cert_img'] = $vis_imp_cert_img;							
							$result_data[$key]['orthopedically_handicapped'] = $orthopedically_handicapped;							
							$result_data[$key]['orth_han_cert_img'] = $orth_han_cert_img;							
							$result_data[$key]['cerebral_palsy'] = $cerebral_palsy;							
							$result_data[$key]['cer_palsy_cert_img'] = $cer_palsy_cert_img;
							
							$i++;
							$mem_cnt++;
							$sequence_number++;
						}
						/* echo "<pre>"; print_r($can_exam_data); echo "</pre>";
						echo "<pre>"; print_r($result_data); echo "</pre>"; */					
												
						$message .= ' - Record found - Execution Ended';
						$record_count = $mem_cnt;
						$posted_data = json_encode($result_data);
						$this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
						
						$data['result'] = "true";
						$data['total_record_count'] = $this->Cron_api_model->get_member_data(1);
						$data['message'] = "Record found";
						$data['result_count'] = $mem_cnt;
						
						if($mem_cnt < $max_limit) { $range_end = $start_limit+$mem_cnt; } else { $range_end = $start_limit+$max_limit; }
						$data['result_range'] = ($start_limit+1).' - '.$range_end;
						$data['result_data'] = $result_data;
						$data['status'] = 200; 
						
						//echo "<pre>"; print_r($data); echo "</pre>";
						echo json_encode($data);
					}
					else
					{
						$data['result'] = "false";
						$data['total_record_count'] = $this->Cron_api_model->get_member_data(1);
						$data['message'] = "No Record found";
						$data['error'] = "";
						$data['status'] = 401; 
						
						$message .= ' - No Record found - Execution Ended';					
						$this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
						
						echo json_encode($data);
					}							
				}
			}
			catch (Exception $e)
			{
				$data['result'] = "false";
				$data['message'] = "Access denied";
				$data['error'] = $error_msg = $e->getMessage();
				$data['status'] = 401;			
				
				$log_id = $this->Cron_api_model->activity_log_common($log_id, $log_title, 'Access denied', $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);				
				echo json_encode($data);
			}			
		}/**************** END : MEMBER REGISTRATION DATA **************/
				
		
		/*********************************************************************
		CREATED BY		: Sagar Matale
		UPDATE BY 		: 
		DESCRIPTION		: EXAM APPLICATION DATA
		PREVIOUS FILE :  
		*********************************************************************/
		public function exam_get_xx() 
		{
			header("Access-Control-Allow-Origin: *");
			
			$result_data = array();				
			$current_date = date("Y-m-d");
			$start_time = date("Y-m-d H:i:s", strtotime($current_date." ".date("H:i:s")));
			$log_id = date("YmdHis", strtotime($current_date." ".date("H:i:s")));
			
			try 
			{
				$this->master_model->insertRecord('api_log', array("log_title" => "Candidate Exam Details : ".$log_id, "action"=>"Execution Started","start_time" => $start_time, "end_time" => date("Y-m-d H:i:s"), "message" => "Execution Started", "record_count"=>0, "posted_data"=>""));
				
				$yesterday = date('Y-m-d', strtotime("- 1 day", strtotime($current_date)));
				
				$not_exam_codes = array('991','1002','1003','1004','1005','1006','1007','1008','1009','1010','1011','1012','1013','1014','2027'); // Not becoz this exam code we send exam date from other crons CSC and remote
				$this->db->limit(20);
				$select = 'DISTINCT(pt.transaction_no), me.exam_code, me.exam_mode, me.exam_medium, me.exam_period, me.exam_center_code, me.exam_fee, me.examination_date, pt.member_regnumber, pt.member_exam_id, pt.amount, DATE_FORMAT(pt.date,"%Y-%m-%d")date, pt.receipt_no, pt.ref_id, pt.status, mr.regnumber, mr.registrationtype, me.elected_sub_code, me.place_of_work, me.state_place_of_work, me.pin_code_place_of_work, mr.editedon, mr.branch, mr.office, mr.city, mr.state, mr.pincode, me.scribe_flag, me.elearning_flag, me.sub_el_count';
				$this->db->join('payment_transaction pt','pt.ref_id = me.id','LEFT');
				$this->db->join('member_registration mr','mr.regnumber = me.regnumber','LEFT'); 
				$this->db->where_not_in('me.exam_code',$not_exam_codes);
				$can_exam_data = $this->Master_model->getRecords('member_exam me',array('me.created_on >='=>$yesterday." 00:00:00", 'me.created_on <='=>$yesterday." 23:59:59", 'pt.status'=>1,'mr.isactive'=>'1', 'mr.isdeleted'=>0, 'me.pay_status'=>1, 'pt.pay_type'=>2),$select);
				
				$last_query = $this->db->last_query();
				$this->master_model->insertRecord('api_log', array("log_title" => "Candidate Exam Details : ".$log_id, "action"=>"Executed Query", "start_time" => $start_time, "end_time" => date("Y-m-d H:i:s"), "message" => $last_query, "record_count"=>0, "posted_data"=>""));
				
				if(count($can_exam_data))
				{
					$i = 1;
					$exam_cnt = 0;
					foreach($can_exam_data as $key => $exam)
					{
						$exam_mode = 'O';
						if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
						else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
						else if($exam['exam_mode'] == 'OF'){ $exam_mode = 'F';}
						
						$syllabus_code = '';
						$part_no = '';
						$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period']),'',array('id'=>'DESC'),0,1);
						if(count($subject_data)>0)
						{
							$syllabus_code = $subject_data[0]['syllabus_code'];
							$part_no = $subject_data[0]['part_no'];
						}
						
						$trans_date = '';
						if($exam['date'] != '0000-00-00') { $trans_date = date('d-M-Y',strtotime($exam['date'])); }
						
						$exam_period = '';
						if($exam['examination_date'] != '' && $exam['examination_date'] != "0000-00-00")
						{
							$ex_period = $this->master_model->getRecords('special_exam_dates',array('examination_date'=>$exam['examination_date']));
							if(count($ex_period)) { $exam_period = $ex_period[0]['period'];	 }
						}
						else{	$exam_period = $exam['exam_period'];	}
						
						$exam_code = '';
						if($exam['exam_code'] == 340 || $exam['exam_code'] == 3400 || $exam['exam_code'] == 34000) { $exam_code = 34; }
						elseif($exam['exam_code'] == 580 || $exam['exam_code'] == 5800 || $exam['exam_code'] == 58000){ $exam_code = 58; }
						elseif($exam['exam_code'] == 1600 || $exam['exam_code'] == 16000){ $exam_code = 160; }
						elseif($exam['exam_code'] == 200){ $exam_code = 20; }
						elseif($exam['exam_code'] == 1770 || $exam['exam_code'] == 17700){ $exam_code =177; }
						elseif ($exam['exam_code'] == 590){ $exam_code = 59; }
						elseif ($exam['exam_code'] == 810){ $exam_code = 81; }
						elseif ($exam['exam_code'] == 1750){ $exam_code = 175; }
						else{ $exam_code = $exam['exam_code']; }
						
						$scribe_flag = $exam['scribe_flag'];
						
						// Condition for DISA and CSIC Exam Application
						if($exam_code == '990' || $exam_code == '993')
						{
							$part_no = 1;
							$exam_mode = 'O';
							$syllabus_code = 'R';
							$scribe_flag = 'N';
						}
						
						$place_of_work = $pin_code_place_of_work = $state_place_of_work = $city = $branch = $branch_name = $state = $pincode = $elected_sub_code = '';
						
						if($exam_code == $this->config->item('examCodeCaiib') || $exam_code == 62 || $exam_code == $this->config->item('examCodeCaiibElective63') || $exam_code == 64 || $exam_code == 65 || $exam_code == 66 || $exam_code == 67 || $exam_code == $this->config->item('examCodeCaiibElective68') || $exam_code == $this->config->item('examCodeCaiibElective69') || $exam_code == $this->config->item('examCodeCaiibElective70') || $exam_code == $this->config->item('examCodeCaiibElective71') || $exam_code == 72 || $exam_code == $this->config->item('examCodeJaiib')) // For CAIIB, CAIIB Elective & JAIIB, above line commented and this line added by Bhagwan Sahane, on 06-10-2017
						{
							if($exam['state']) { $state = $exam['state']; }							
							if($exam['pincode']) { $pincode = $exam['pincode']; }							
							
							if(strlen($exam['city']) > 30) {	$city = substr($exam['city'],0,29); }
							else {	$city = $exam['city'];	}
							
							if($exam['editedon'] < "2016-12-29 00:00:00") { $branch = $exam['branch']; }
							else if($exam['editedon'] >= "2016-12-29")
							{
								if(is_numeric($exam['office']))
								{
									if($exam['branch']!='') { $branch = $exam['branch']; }
									else { $branch = $city; }
								}
								else
								{
									if($exam['branch']!='') { $branch = $exam['branch']; }
									else { $branch = $exam['office']; }
								}
							}
							
							if($branch == '') { $branch = $city; }
							
							if(strlen($branch) > 20) {	$branch_name = substr($branch,0,19); }
							else {	$branch_name = $branch;	}
							
							if($exam['place_of_work']) { $place_of_work = $exam['place_of_work']; }
							else { $place_of_work =  $branch_name; }
							
							if($exam['pin_code_place_of_work']) { $pin_code_place_of_work = $exam['pin_code_place_of_work']; }
							else { $pin_code_place_of_work =  $pincode; }
							
							if($exam['state_place_of_work']) { $state_place_of_work = $exam['state_place_of_work']; }
							else { $state_place_of_work =  $state; }
							
							if($exam_code == $this->config->item('examCodeCaiib')) {	$elected_sub_code = $exam['elected_sub_code'];	}
							
							if(strlen($place_of_work) > 30) {	$place_of_work = substr($place_of_work,0,29);		}
							else {	$place_of_work = $place_of_work;	}				
							
							/* $data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|'.$elected_sub_code.'|'.$place_of_work.'|'.$pin_code_place_of_work.'|'.$state_place_of_work.'|'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'
							.$exam['transaction_no'].'|'.$scribe_flag.'|'.$exam['elearning_flag'].'|'.$exam['sub_el_count']."\n";  */							
						}
						/* else
							{
							$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$exam['transaction_no'].'|'.$trans_date.'|'
							.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$scribe_flag.'|'.$exam['elearning_flag'].'|'.$exam['sub_el_count']."\n";   
						}*/
						
						$result_data[$key]['exam_code'] = $exam_code;
						$result_data[$key]['exam_period'] = $exam_period;
						$result_data[$key]['regnumber'] = $exam['regnumber'];
						$result_data[$key]['registrationtype'] = $exam['registrationtype'];
						$result_data[$key]['exam_medium'] = $exam['exam_medium'];
						$result_data[$key]['exam_center_code'] = $exam['exam_center_code'];
						$result_data[$key]['exam_mode'] = $exam_mode;
						$result_data[$key]['syllabus_code'] = $syllabus_code;
						$result_data[$key]['currency_code'] = "IRE";
						$result_data[$key]['part_no'] = $part_no;
						$result_data[$key]['elected_sub_code'] = $elected_sub_code;
						$result_data[$key]['place_of_work'] = $place_of_work;
						$result_data[$key]['pin_code_place_of_work'] = $pin_code_place_of_work;
						$result_data[$key]['state_place_of_work'] = $state_place_of_work;
						$result_data[$key]['transaction_no'] = $exam['transaction_no'];
						$result_data[$key]['trans_date'] = $trans_date;
						$result_data[$key]['exam_fee'] = $exam['exam_fee'];
						$result_data[$key]['scribe_flag'] = $scribe_flag;
						$result_data[$key]['elearning_flag'] = $exam['elearning_flag'];
						$result_data[$key]['sub_el_count'] = $exam['sub_el_count'];
						
						$i++;
						$exam_cnt++;
					}
					/* echo "<pre>"; print_r($can_exam_data); echo "</pre>";
					echo "<pre>"; print_r($result_data); echo "</pre>"; */
					
					$this->master_model->insertRecord('api_log', array("log_title" => "Candidate Exam Details : ".$log_id, "action"=>"Execution Ended","start_time" => $start_time, "end_time" => date("Y-m-d H:i:s"), "message" => "Execution Ended : Record found", "record_count"=>$exam_cnt, "posted_data"=>json_encode($result_data)));
					
					$data['result'] = "true";
					$data['message'] = "Record found";
					$data['result_count'] = $exam_cnt;
					$data['result_data'] = $result_data;
					$data['status'] = 200; 
					
					//echo "<pre>"; print_r($data); echo "</pre>";
					echo json_encode($data);
				}
				else
				{
					$data['result'] = "false";
					$data['message'] = "No Record found";
					$data['error'] = "";
					$data['status'] = 401;
					
					$this->master_model->insertRecord('api_log', array("log_title" => "Candidate Exam Details : ".$log_id, "action"=>"Execution Ended", "start_time" => $start_time, "end_time" => date("Y-m-d H:i:s"), "message" => "Execution Ended : No Record found", "record_count"=>0, "posted_data"=>""));
					
					echo json_encode($data);
				}
			}
			catch (Exception $e)
			{
				$data['result'] = "false";
				$data['message'] = "Access denied";
				$data['error'] = $error_msg = $e->getMessage();
				$data['status'] = 401;
				
				$this->master_model->insertRecord('api_log', array("log_title" => "Candidate Exam Details : ".date("YmdHis"), "action"=>"In Catch Exception", "start_time" => $start_time, "end_time" => date("Y-m-d H:i:s"), "message" => "Access denied : ".$error_msg, "record_count"=>0, "posted_data"=>""));
				
				echo json_encode($data);
			}			
		}/************** END : EXAM DATA **************/		
		
		public function get_member_images($image_path='', $reg_no='', $regnumber='', $scannedphoto='', $idproofphoto='', $scannedsignaturephoto='', $regid='', $yesterday='')
    {	
			$recover_images = $this->recover_images($image_path, $reg_no, $regnumber, $scannedphoto, $idproofphoto, $scannedsignaturephoto, $regid, $yesterday);
			$scannedphoto_res = $recover_images['scannedphoto'];
			$idproofphoto_res = $recover_images['idproofphoto'];
			$scannedsignaturephoto_res = $recover_images['scannedsignaturephoto'];
			
			if($scannedphoto_res == "" || $idproofphoto_res == "" || $scannedsignaturephoto_res == "")
			{			
				$this->db->where("REPLACE(title,' ','') LIKE '%CSCINSERTArray%'");
				$user_log = $this->Master_model->getRecords('userlogs a',array('regid'=>$regid,' DATE(date)'=>$yesterday));
				
				if(COUNT($user_log) > 0)
				{
					$description = unserialize($user_log[0]['description']);
					$scannedphoto =  $description['scannedphoto'];
					$scannedsignaturephoto =  $description['scannedsignaturephoto'];
					$idproofphoto =  $description['idproofphoto'];
					
					$recover_images2 = $this->recover_images($image_path, $reg_no, $regnumber, $scannedphoto, $idproofphoto, $scannedsignaturephoto, $regid, $yesterday);
					$scannedphoto_res = $recover_images2['scannedphoto'];
					$idproofphoto_res = $recover_images2['idproofphoto'];
					$scannedsignaturephoto_res = $recover_images2['scannedsignaturephoto'];
				}
			}
			
			$data['scannedphoto'] = $scannedphoto_res;
			$data['idproofphoto'] = $idproofphoto_res;
			$data['scannedsignaturephoto'] = $scannedsignaturephoto_res;
			return $data;
		}
		
		public function recover_images($image_path='', $reg_no='', $regnumber='', $scannedphoto='', $idproofphoto='', $scannedsignaturephoto='', $regid='', $yesterday='')
    {	
			//// FOR PHOTO
			if($scannedphoto != '' && $scannedphoto != 'p_'.$regnumber.'.jpg')
			{							
				$attachpath = "uploads/photograph/".$scannedphoto;
				if(file_exists($attachpath))
				{
					if(@ rename("./uploads/photograph/".$scannedphoto,"./uploads/photograph/p_".$regnumber.".jpg"))
					{
						$insert_data  = array(
						'member_no' => $regnumber,
						'update_value' => "uploads folder Photo rename",
						'update_date' => date('Y-m-d H:i:s')
						);
						$this->master_model->insertRecord('member_images_update', $insert_data);
					}					
				}				
			}
			
			//// FOR SIGNATURE
			if($scannedsignaturephoto != '' && $scannedsignaturephoto != 's_'.$regnumber.'.jpg')
			{							
				$attachpath = "uploads/scansignature/".$scannedsignaturephoto;
				if(file_exists($attachpath))
				{
					if(@ rename("./uploads/scansignature/".$scannedsignaturephoto,"./uploads/scansignature/s_".$regnumber.".jpg"))
					{
						$insert_data  = array(
						'member_no' => $regnumber,
						'update_value' => "uploads folder Signature rename",
						'update_date' => date('Y-m-d H:i:s')
						);
						$this->master_model->insertRecord('member_images_update', $insert_data);
					}					
				}				
			}
			
			//// FOR IDPROOF
			if($idproofphoto != '' && $idproofphoto != 'pr_'.$regnumber.'.jpg')
			{							
				$attachpath = "uploads/idproof/".$idproofphoto;
				if(file_exists($attachpath))
				{
					if(@ rename("./uploads/idproof/".$idproofphoto,"./uploads/idproof/pr_".$regnumber.".jpg"))
					{
						$insert_data  = array(
						'member_no' => $regnumber,
						'update_value' => "uploads folder id proof rename",
						'update_date' => date('Y-m-d H:i:s')
						);
						$this->master_model->insertRecord('member_images_update', $insert_data);
					}					
				}				
			}
			
			$extn = '.jpg';
			$member_no = $regnumber;
			
			//// Code for Photo
			$photo_name = $scannedphoto;
			$photo = strpos($photo_name,'photo');
			if($photo == 8)
			{
				$photo_replace = str_replace($photo_name,'p_',$photo_name);
				$updated_photo = $photo_replace.$member_no.$extn;
				
				$update_data = array('scannedphoto' => $updated_photo);
				$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));
				
				$insert_data  = array(
				'member_no' => $member_no,
				'update_value' => "Photo",
				'update_date' => date('Y-m-d H:i:s')
				);
				$this->master_model->insertRecord('member_images_update', $insert_data);
				
				$scannedphoto = $updated_photo;
			} 
			
			//// Code for Signature
			$sign_name = $scannedsignaturephoto;
			$sign = strpos($sign_name,'sign');
			if($sign == 8)
			{
				$sign_replace = str_replace($sign_name,'s_',$sign_name);
				$updated_sign = $sign_replace.$member_no.$extn;
				
				$update_data = array('scannedsignaturephoto' => $updated_sign);
				$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));
				
				$insert_data  = array(
				'member_no' => $member_no,
				'update_value' => "Signature",
				'update_date' => date('Y-m-d H:i:s')
				);
				$this->master_model->insertRecord('member_images_update', $insert_data);
				
				$scannedsignaturephoto = $updated_sign;
			}
			
			//// Code for IDPROOF
			$idproof_name = $idproofphoto;
			$idproof = strpos($idproof_name,'idproof');
			if($idproof == 8)
			{
				$idproof_replace = str_replace($idproof_name,'pr_',$idproof_name);
				$updated_idproof = $idproof_replace.$member_no.$extn;
				
				$update_data = array('idproofphoto' => $updated_idproof);
				$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));
				
				$insert_data  = array(
				'member_no' => $member_no,
				'update_value' => "ID Proof",
				'update_date' => date('Y-m-d H:i:s')
				);
				$this->master_model->insertRecord('member_images_update', $insert_data);
				
				$idproofphoto = $updated_idproof;
			}
			
			$db_img_path = $image_path; //Get old image path from database
      $scannedphoto_res = $idproofphoto_res = $scannedsignaturephoto_res = '';
      
      if($scannedphoto != "" && file_exists(FCPATH."uploads/photograph/".$scannedphoto)) //Check photo in regular folder
      { 
        $scannedphoto_res = base_url()."uploads/photograph/".$scannedphoto; 
			}
      else if($db_img_path != "") //Check photo in old image path
      { 
        if($reg_no != "" && file_exists(FCPATH."uploads".$db_img_path."photo/p_".$reg_no.".jpg"))
        {
          $scannedphoto_res = base_url()."uploads".$db_img_path."photo/p_".$reg_no.".jpg"; 
				}
        else if($regnumber != "" && file_exists(FCPATH."uploads".$db_img_path."photo/p_".$regnumber.".jpg"))
        {
          $scannedphoto_res = base_url()."uploads".$db_img_path."photo/p_".$regnumber.".jpg"; 
				}
			}
      else  //Check photo in kyc folder          
      {
        if($reg_no != "" && file_exists(FCPATH."uploads/photograph/k_p_".$reg_no.".jpg"))
        {
          $scannedphoto_res = base_url()."uploads/photograph/k_p_".$reg_no.".jpg"; 
				}
        else if($regnumber != "" && file_exists(FCPATH."uploads/photograph/k_p_".$regnumber.".jpg"))
        {
          $scannedphoto_res = base_url()."uploads/photograph/k_p_".$regnumber.".jpg"; 
				}
			}
      
      if ($idproofphoto != "" && file_exists(FCPATH."uploads/idproof/".$idproofphoto)) //Check id proof in regular folder
      { 
        $idproofphoto_res = base_url()."uploads/idproof/".$idproofphoto; 
			}
      else if($db_img_path != "") //Check id proof in old image path
      { 
        if($reg_no != "" && file_exists(FCPATH."uploads".$db_img_path."idproof/pr_".$reg_no.".jpg"))
        {
          $idproofphoto_res = base_url()."uploads".$db_img_path."idproof/pr_".$reg_no.".jpg"; 
				}
        else if($regnumber != "" && file_exists(FCPATH."uploads".$db_img_path."idproof/pr_".$regnumber.".jpg"))
        {
          $idproofphoto_res = base_url()."uploads".$db_img_path."idproof/pr_".$regnumber.".jpg"; 
				}
			}
      else //Check photo in kyc folder
      {
        if($reg_no != "" && file_exists(FCPATH."uploads/idproof/k_pr_".$reg_no.".jpg"))
        {
          $idproofphoto_res = base_url()."uploads/idproof/k_pr_".$reg_no.".jpg"; 
				}
        else if($regnumber != "" && file_exists(FCPATH."uploads/idproof/k_pr_".$regnumber.".jpg"))
        {
          $idproofphoto_res = base_url()."uploads/idproof/k_pr_".$regnumber.".jpg"; 
				}
			}
      
      if ($scannedsignaturephoto != "" && file_exists(FCPATH."uploads/scansignature/".$scannedsignaturephoto)) //Check signature in regular folder
      { 
        $scannedsignaturephoto_res = base_url()."uploads/scansignature/".$scannedsignaturephoto; 
			}
      else if($db_img_path != "") //Check signature in old image path
      { 
        if($reg_no != "" && file_exists(FCPATH."uploads".$db_img_path."signature/s_".$reg_no.".jpg"))
        {
          $scannedsignaturephoto_res = base_url()."uploads".$db_img_path."signature/s_".$reg_no.".jpg"; 
				}
        else if($regnumber != "" && file_exists(FCPATH."uploads".$db_img_path."signature/s_".$regnumber.".jpg"))
        {
          $scannedsignaturephoto_res = base_url()."uploads".$db_img_path."signature/s_".$regnumber.".jpg"; 
				}
			}
      else //Check signature in kyc folder
      {
        if($reg_no != "" && file_exists(FCPATH."uploads/scansignature/k_s_".$reg_no.".jpg"))
        {
          $scannedsignaturephoto_res = base_url()."uploads/scansignature/k_s_".$reg_no.".jpg"; 
				}
        else if($regnumber != "" && file_exists(FCPATH."uploads/scansignature/k_s_".$regnumber.".jpg"))
        {
          $scannedsignaturephoto_res = base_url()."uploads/scansignature/k_s_".$regnumber.".jpg"; 
				}
			}
			
			$data['scannedphoto'] = $scannedphoto_res;
      $data['idproofphoto'] = $idproofphoto_res;
      $data['scannedsignaturephoto'] = $scannedsignaturephoto_res;
      return $data;
		}		
	
		function image_to_binary($file) 
		{  
			$mime = 'image/'.strtolower(pathinfo($file, PATHINFO_EXTENSION));
			
			$contents = file_get_contents($file);
			return $base64   = base64_encode($contents); 
			//return ('data:' . $mime . ';base64,' . $base64);			
		}
		
		function scaleImageFileToBlob($file) 
		{
			list($width, $height, $image_type) = getimagesize($file);
			
			$thumb = imagecreatetruecolor($width, $height);
			$source = imagecreatefromjpeg($file);
			
			// Resize
			imagecopyresized($thumb, $source, 0, 0, 0, 0, $width, $height, $width, $height);

			// Output
			//return imagejpeg($thumb);
			
			ob_start();
			imagejpeg($thumb);
			$final_image = ob_get_contents();
			ob_end_clean();
			return $final_image;
		}
	}