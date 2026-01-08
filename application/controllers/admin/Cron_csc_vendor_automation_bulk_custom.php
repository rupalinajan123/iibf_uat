<?php
	/********************************************************************
		* Created BY: Sagar Matale
		* Update By: Sagar Matale on: 24/06/2021
		* Update By: Sagar Matale on: 14/09/2021
		* Description: This is automation cron for sending data to CSC VENDOR for RPE exam. 
		* Previous cron file : controllers/admin/Cron_csv.php : exam_csv_csc_vendor
		* Exam codes : 1003, 1004, 1005, 1006, 1007, 1008, 1009
	********************************************************************/
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	/* header("Access-Control-Allow-Origin: *"); */
	
	class Cron_csc_vendor_automation_bulk_custom extends CI_Controller
	{    
		public function __construct()
		{ 
			parent::__construct();
			$this->load->library('upload');
			$this->load->model('Master_model');
			$this->load->model('log_model'); 
			$this->load->model('Emailsending'); 
			
			error_reporting(E_ALL);// Report all errors
			ini_set("error_reporting", E_ALL);// Same as error_reporting(E_ALL);
			ini_set('display_errors', 1);
			ini_set("memory_limit", "-1");
			//exit;
		}
		
		function valid_time($time)
		{
			$final_time = '00:00:00';
			if($time != '')
			{
				$time = strtolower($time);
				$time = str_replace('a','am',$time);
				$time = str_replace('p','pm',$time);
				$time = str_replace('amm','am',$time);
				$time = str_replace('pmm','pm',$time);
				$time = str_replace('.',':',$time);
				$time = str_replace('::',':',$time);
				$time = str_replace(':pm',' pm',$time);
				$time = str_replace(':am',' am',$time);
				
				$explode_time = explode(":", $time);
				if(count($explode_time) >= 2)
				{	
					if($explode_time[0] > 12 && (strpos($time,'am')!==false || strpos($time,'pm')!==false))
					{
						$time = str_replace(':pm','',$time);
						$time = str_replace(':am','',$time);
						$time = str_replace('pm','',$time);
						$time = str_replace('am','',$time);
					}
					
					$final_time = date('H:i:s', strtotime($time));
				}
			}			
			return $final_time;
		}
		
		//Previous cron file : controllers/admin/Cron_csv.php : exam_csv_csc_vendor
		public function index()
    {

			error_reporting(E_ALL);// Report all errors
			ini_set("error_reporting", E_ALL);// Same as error_reporting(E_ALL);
			ini_set('display_errors', 1);
			ini_set("memory_limit", "-1");
			
			$dir_flg = $parent_dir_flg = $exam_file_flg  = 0;
			$success = $error = array();
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd");
			$cron_file_dir = "./uploads/rahultest/";
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
			$desc = json_encode($result);
			//$this->log_model->cronlog("CSC RPE VENDOR Cron Execution Start", $desc);
			
			if (!file_exists($cron_file_dir . $current_date)) { $parent_dir_flg = mkdir($cron_file_dir . $current_date, 0700); }
			
			if (file_exists($cron_file_dir . $current_date)) 
			{
				$cron_file_path = $cron_file_dir . $current_date; // Path with CURRENT DATE DIRECTORY
				
				$file1 = "Automation_csc_rpe_vendor_cron_logs_" . $current_date . ".txt";					
				$fp1 = fopen($cron_file_path . '/' . $file1, 'a');
				fwrite($fp1, "\n***** CSC RPE VENDOR Cron Execution Started - " . $start_time . " ***** \n");
				
				$file2 = "Automation_csc_rpe_vendor_member_img_logs_" . $current_date . ".txt";
				$member_img_log = fopen($cron_file_path.'/'.$file2,'a');
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2022-01-01'; 
        
       
				
				$exam_code = array('1005'); // Send Free and Paid Both Applications '1002',
				$exam_period = '819';
        
				//$this->db->limit(85);
				// DISTINCT(b.transaction_no),
				$select = 'a.exam_code, c.regid, c.firstname, c.middlename, c.lastname, c.image_path, c.reg_no, c.scannedphoto, c.idproofphoto, c.scannedsignaturephoto, a.regnumber, c.dateofbirth, c.gender, c.email, c.stdcode, c.office_phone, c.mobile, c.address1, c.address2, c.address3, c.address4, c.district, c.city, c.state, c.pincode, c.associatedinstitute, c.designation, a.exam_period, a.elected_sub_code, c.editedon, a.examination_date, a.id AS mem_exam_id, a.created_on AS registration_date, a.exam_medium, a.exam_center_code, d.venueid, d.pwd, d.exam_date, d.time';
				//$this->db->join('payment_transaction b', 'b.ref_id = a.id', 'LEFT');
				$this->db->join('member_registration c', 'a.regnumber=c.regnumber', 'LEFT');
				$this->db->join('admit_card_details d', 'a.id=d.mem_exam_id', 'LEFT');
				$this->db->where_in('a.exam_code', $exam_code);
				$this->db->where('a.exam_period', $exam_period);
				//$this->db->where('a.regnumber', '500203850');
				

        $can_exam_data = $this->Master_model->getRecords('member_exam a', array(
				'remark' => 1,
				/*'pay_type' => 2, 
				'status' => 1,*/
				'a.pay_status'=>1,
				'a.bulk_isdelete'=>0,
				'isactive' => '1',
				'isdeleted' => 0,
				'pay_status' => 1,
				 // 'd.exam_date' => '2022-09-24', 
				), $select); 

				// echo $this->db->last_query(); exit;
				// echo "<pre>"; print_r($can_exam_data); echo "</pre>"; exit;
				
				$exam_cnt = 0;
				$api_data_arr = array();
				if (count($can_exam_data)) 
				{
					$i = 1;					
					foreach ($can_exam_data as $exam) 
					{
						/* $data1 = "First Name, Middle name, Last Name, Mem. Number, Password, Date of Birth, Gender, Email ID, Mobile, Address, State, Pin Code, Country, Profession, Organization, Designation, Exam Code, Course, Elective Sub Code, Elective Sub Desc, Attempt, Registration Date, Exam date, Time, Exam Medium, Exam Center Code,Venue Code \n"; */
						
						$firstname = $middlename = $lastname = $mem_number = $password = $dateofbirth = $gender = $email_id = $mobile = $address = $state = $pincode = $country = $profession = $institution_name = $designation_name = $exam_code = $exam_name = $subject_code = $subject_description = $attempt_count = $registration_date = $exam_date = $time = $medium_name = $exam_center_code = $venue_code = $scannedphoto = $scannedsignaturephoto = $idproofphoto = '';
						
						$exam_code = $exam['exam_code'];
						$exam_date = $exam['exam_date'];	
						
						$firstname = preg_replace('/[^A-Za-z\/ ]/', '', $exam['firstname']);
						$middlename = preg_replace('/[^A-Za-z]/', '', $exam['middlename']);
						$lastname = preg_replace('/[^A-Za-z]/', '', $exam['lastname']);
						$mem_number = $exam['regnumber'];
						$password = $exam['pwd'];
						$dateofbirth = date('d-m-Y', strtotime($exam['dateofbirth']));
						
						$gender = $exam['gender'];
						if ($gender == 'male' || $gender == 'Male') { $gender = 'M'; } 
						elseif($gender == 'female' || $gender == 'Female') { $gender = 'F'; }
						
						$email_id = $exam['email'];
						$mobile = preg_replace('/[^0-9]/', '', $exam['mobile']);
						
						/* $address = $exam['address1'].' '.$exam['address2'].' '.$exam['address3'].' '.$exam['address4'].' '.$exam['district'].' '.$exam['city'];
						$address = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address); */
						
						$state = $exam['state'];
						/* $pincode = preg_replace('/[^0-9]/', '', $exam['pincode']); */
						/* $country = 'INDIA'; */
						
						/* $institution_master = $this->master_model->getRecords('institution_master');
							if (count($institution_master)) 
							{
							foreach ($institution_master as $institution_row) 
							{
							if ($exam['associatedinstitute'] == $institution_row['institude_id']) 
							{
							$institution_name = $institution_row['name'];
							}
							}
							}
						$institution_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $institution_name); */
						
						/* $designation = $this->master_model->getRecords('designation_master');
							if (count($designation)) 
							{
							foreach ($designation as $designation_row) 
							{
							if ($exam['designation'] == $designation_row['dcode']) 
							{
							$designation_name = $designation_row['dname'];
							}
							}
							}
						$designation_name   = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $designation_name); */
						
						$exam_arr = array('1027' => 'CERTIFICATE EXAM ON KYC-AML & COMPLIANCE FOR EMPLOYEES OF UCO BANK');						
						foreach ($exam_arr as $k => $val)
						{
							if ($exam_code == $k) 
							{
								$exam_name = $val;
							}
						}
						
						//$exam_code_arr = array('1003','1004');
						$select = 'regnumber';
						$this->db->where_in('exam_code', $exam['exam_code']);
						$this->db->where_in('regnumber', $exam['regnumber']);
						$attempt_count = $this->Master_model->getRecords('member_exam', array('pay_status' => 1), $select);
						$attempt_count = count($attempt_count);
						$attempt_count = $attempt_count - 1;
						
						$registration_date = date('d-m-Y', strtotime($exam['registration_date']));							
						$time = $exam['time'];
						
						$medium = $this->master_model->getRecords('medium_master');
						if (count($medium)) 
						{
							foreach ($medium as $medium_row) 
							{
								if ($exam['exam_medium'] == $medium_row['medium_code']) 
								{
									$medium_name = $medium_row['medium_description'];
								}
							}
						}
						$medium_name   = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $medium_name);
						
						/* $exam_center_code = $exam['exam_center_code']; */
						/* $venue_code = $exam['venueid']; */
						
						//START : ADDED BY SAGAR ON 27-06-2020 FOR MEMBER IMAGES          
						$member_images = $this->get_member_images($exam['image_path'], $exam['reg_no'], $exam['regnumber'], $exam['scannedphoto'], $exam['idproofphoto'], $exam['scannedsignaturephoto'], $exam['regid'], $yesterday);
						
						$scannedphoto = $member_images['scannedphoto'];
						$expected_scannedphoto = base_url().'uploads/photograph/p_'.$mem_number.'.jpg';
						if($scannedphoto != $expected_scannedphoto)
						{
							$chk_response = $this->update_image_name($scannedphoto,$expected_scannedphoto);
							if($chk_response != "") 
							{ 
								$scannedphoto = $chk_response;
								//$update_data = array('scannedphoto' => $expected_scannedphoto);
								//$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $mem_number));
							}
						}
						
						$scannedsignaturephoto = $member_images['scannedsignaturephoto'];
						$expected_scannedsignaturephoto = base_url().'uploads/scansignature/s_'.$mem_number.'.jpg';
						if($scannedsignaturephoto != $expected_scannedsignaturephoto)
						{
							$chk_response = $this->update_image_name($scannedsignaturephoto,$expected_scannedsignaturephoto);
							if($chk_response != "") 
							{ 
								$scannedsignaturephoto = $chk_response;
								//$update_data = array('scannedphoto' => $expected_scannedphoto);
								//$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $mem_number));
							}
						}
						
						$idproofphoto = $member_images['idproofphoto'];
						$expected_idproofphoto = base_url().'uploads/idproof/pr_'.$mem_number.'.jpg';
						if($idproofphoto != $expected_idproofphoto)
						{
							$chk_response = $this->update_image_name($idproofphoto,$expected_idproofphoto);
							if($chk_response != "") 
							{ 
								$idproofphoto = $chk_response;
								//$update_data = array('scannedphoto' => $expected_scannedphoto);
								//$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $mem_number));
							}
						}
						
						// $scannedphoto = 'https://iibf.esdsconnect.com/uploads/photograph/p_'.$exam['regnumber'].'.jpg';

						//       $scannedsignaturephoto = ' https://iibf.esdsconnect.com/uploads/scansignature/s_'.$exam['regnumber'].'.jpg';
						//       $idproofphoto = 'https://iibf.esdsconnect.com/uploads/idproof/pr_'.$exam['regnumber'].'.jpg';


						if($scannedphoto == "") { fwrite($member_img_log, "Photo missing - " . $exam['regnumber'] . " \n"); }
						if($scannedsignaturephoto == "") { fwrite($member_img_log, "Signature missing - " . $exam['regnumber'] . " \n"); }
						if($idproofphoto == "") { fwrite($member_img_log, "ID Proof missing - " .$exam['regnumber'] . " \n"); }
						//END : ADDED BY SAGAR ON 27-06-2020 FOR MEMBER IMAGES          			
						
						if($scannedphoto == "" || $scannedsignaturephoto == "" || $idproofphoto == "") 
						{ 
							fwrite($member_img_log, "\n"); 
						}
						else //NEED TO CHECK ALL THESE FIELDS WITH BHUSHAN ONCE.. 
						{

							$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period']),'subject_code,subject_description',array('id'=>'DESC'),0,1);
							//echo $this->db->last_query();
							if(count($subject_data)>0)
							{
								$subject_code = 'S_'.$subject_data[0]['subject_code'];
								$subject_description = $subject_data[0]['subject_description'];
								$subject_description = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $subject_description);
							}
							
							$post_field_arr = array();
							$post_field_arr['first_name'] = $firstname;
							$post_field_arr['middle_name'] = $middlename;
							$post_field_arr['last_name'] = $lastname;
							$post_field_arr['member_number'] = $mem_number;
							$post_field_arr['password'] = $password;
							$post_field_arr['dob'] = $dob_log = date("Y-m-d", strtotime($dateofbirth));
							$post_field_arr['gender'] = $gender;
							$post_field_arr['email_id'] = $email_id;
							$post_field_arr['mobile'] = (string)$mobile;
							$post_field_arr['state'] = $state;
							$post_field_arr['exam_code'] = $exam_code;
							$post_field_arr['exam_medium'] = $subject_code;
							$post_field_arr['course'] = $exam_name;
							$post_field_arr['subject_code'] = $subject_code;
							$post_field_arr['subject'] = $subject_description;
							$post_field_arr['attempt'] = (string)$attempt_count; 
							$post_field_arr['registration_date'] = $registration_date_log = date("Y-m-d", strtotime($registration_date));
							$post_field_arr['exam_date'] = $exam_date_log = $exam_date = date("Y-m-d", strtotime($exam_date));
							$post_field_arr['batch_start_time'] = $batch_start_time_log = $exam_date.' '.$this->valid_time($time);
							$post_field_arr['student_photo'] = $scannedphoto;
							$post_field_arr['student_signature'] = $scannedsignaturephoto;
							$post_field_arr['student_document_photo'] = $idproofphoto;
							
							/* $post_field_arr['address'] = $address;
								$post_field_arr['pin_code'] = $pincode;
								$post_field_arr['country'] = $country;
								$post_field_arr['profession'] = $profession;
								$post_field_arr['organization'] = $institution_name;
								$post_field_arr['designation'] = $designation_name;								
								$post_field_arr['exam_center_code'] = $exam_center_code;
								$post_field_arr['venue_code'] = $venue_code;		 */						
							
							$api_data_arr[] = $post_field_arr;
							
							$i++;
							$exam_cnt++;
							
							$append_log_data = $firstname.' | '.$middlename.' | '.$lastname.' | '.$mem_number.' | '.$password.' | '.$dob_log.' | '.$gender.' | '.$email_id.' | '.(string)$mobile.' | '.$state.' | '.$exam_code.' | '.$medium_name.' | '.$exam_name.' | '.$subject_code.' | '.$subject_description.' | '.(string)$attempt_count.' | '.$registration_date_log.' | '.$exam_date_log.' | '.$batch_start_time_log.' | '.$scannedphoto.' | '.$scannedsignaturephoto.' | '.$idproofphoto;
							fwrite($fp1, "\n".$exam_cnt.' - '.$append_log_data."\n");
						}
					}
					fwrite($fp1, "Total Applications - " . $exam_cnt . "\n");
					
					$insert_info = array('CurrentDate' => $current_date,'old_file_name' => '','new_file_name' => '','record_count' => $exam_cnt,'createdon' => date('Y-m-d H:i:s'));
					$this->master_model->insertRecord('cron_cscvendor_csc_sm', $insert_info, true); 
					$last_inserted_id = $this->db->insert_id();
				} 
				else 
				{
					fwrite($fp1, "No data found for the date: " . $yesterday . " \n");
					$insert_info = array('CurrentDate' => $current_date,'old_file_name' => '','new_file_name' => '','record_count' => 0,'createdon' => date('Y-m-d H:i:s'));
					$this->master_model->insertRecord('cron_cscvendor_csc_sm', $insert_info, true);
					$last_inserted_id = $this->db->insert_id();
					$success[] = "No data found for the date";
				}
				fclose($member_img_log);
				//echo "<pre>"; print_r($api_data_arr); echo "</pre>";  exit;
				//echo "<pre>"; echo json_encode($api_data_arr); echo "</pre>"; //exit;
				
				/*************** START : SEND DATA TO API IN JSON FORMAT ********************************/
				
				
				// foreach ($api_data_arr as $key => $value) {
				// 	echo $value['member_number'].',';
				// }
				// die;
				// echo "<pre>";
				// print_r($api_data_arr);
				// die;
				$mail_body = '';
				if(count($api_data_arr) > 0) 
				{ 
					//$data_send_url = 'https://uat.cscexams.in/cscLogin/iibf2Reg2.obj';
					$data_send_url = 'https://admin.cscexams.in/cscLogin/iibf2Reg2.obj';
					
					//Send a POST request without cURL.
					//$result = $this->post_data($data_send_url, $api_data_arr);  
					$result = $this->post_data_new($data_send_url, $api_data_arr, $cron_file_path.'/'.$file2);  
					
					/* $result = str_replace("</pre>","",$result);
					$result = str_replace("<pre>","",$result);
					
					echo "<br><br>".$result; //exit;
					
					$mail_body = 
					'************************* CSC RPE VENDOR Cron Execution Start - '.$start_time.' *************************<br><br>
					Total Applications : '.$exam_cnt.'<br>
					Message : <br>'.$result.'<br><br>
					************************* CSC RPE VENDOR Cron Execution End - '.date("Y-m-d H:i:s").' *************************';
					
					$attachment = $cron_file_path.'/'.$file2;
					$this->send_mail('logs@iibf.esdsconnect.com', 'iibfdevp@esds.co.in', 'logs@iibf.esdsconnect.com', 'logs@iibf.esdsconnect.com', 'CSC RPE Data Automation Cron Execution : '.date("Y-m-d"), $mail_body, $view_flag='', $attachment); */
					//$this->send_mail('sagar.matale@esds.co.in', 'sagar.matale@esds.co.in', '', '', 'CSC CSV RPE Data Automation Cron Execution : '.date("Y-m-d"), $mail_body, $view_flag='', $attachment);
				}
				else
				{
					$mail_body = 
					'************************* CSC RPE VENDOR Cron Execution Start - '.$start_time.' *************************<br><br>
					Total Applications : 0<br><br>					
					************************* CSC RPE VENDOR Cron Execution End - '.date("Y-m-d H:i:s").' *************************';
					
					$attachment = '';
					/* $this->send_mail('logs@iibf.esdsconnect.com', 'iibfdevp@esds.co.in', 'logs@iibf.esdsconnect.com', 'logs@iibf.esdsconnect.com', 'CSC RPE Data Automation Cron Execution : '.date("Y-m-d"), $mail_body, $view_flag='', $attachment); */
					$this->send_mail('vishal.phadol@esds.co.in', 'vishal.phadol@esds.co.in', '', '', 'CSC CSV RPE Data Automation Cron Execution : '.date("Y-m-d"), $mail_body, $view_flag='', $attachment);					
				}
				/*************** END : SEND ENCRYPTED DATA TO API IN JSON FORMAT ********************************/
				
				$this->master_model->updateRecord('cron_cscvendor_csc_sm',array('mail_content'=>json_encode($mail_body)),array('id'=>$last_inserted_id));
				
				$end_time = date("Y-m-d H:i:s");
				$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
				$desc = json_encode($result);
				//$this->log_model->cronlog("CSC RPE VENDOR Cron Execution End", $desc);
				fwrite($fp1, "\n" . "***** CSC RPE VENDOR Cron Execution End " . $end_time . " ******" . "\n");
				fclose($fp1); 
			}
		}
		
		function post_data_new($url, $postVars = array(), $attachment='')
		{	
			//echo count($postVars); exit;
			//print_r($postVars); //exit;
			//echo json_encode($postVars); exit;
			/* $ch = curl_init();
			curl_setopt($ch,CURLOPT_URL, $url);
			curl_setopt($ch,CURLOPT_POST, count($postVars));
			curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($postVars));
			$result = curl_exec($ch);
			
			if(curl_errno($ch))
			{
				echo curl_errno($ch);
			}
			curl_close($ch);
			var_dump($result);
			exit; */
			
			if(count($postVars) > 0)
			{
				'<br> total_record_cnt : '.$total_record_cnt = count($postVars); 
				'<br> max_record_send : '.$max_record_send = 60;
				
				'<br> total_slots : '.$total_slots = floor($total_record_cnt / $max_record_send);
				'<br> total_slots_mod : '.$total_slots_mod = $total_record_cnt % $max_record_send; 
				
				$key_arr = array();
				$start_val = $end_val = 0; 
				if($total_slots > 0)
				{
					for($i=0; $i< $total_slots; $i++)
					{
						$temp_arr = array();
						
						if($i == 0) { $start_val = 0; $end_val = $max_record_send-1; }
						else{ $start_val = $end_val + 1; $end_val = $end_val + $max_record_send; }
						
						$temp_arr[0] = $start_val;
						$temp_arr[1] = $end_val;
						$key_arr[] = $temp_arr;
					}					
				}
				
				if($total_slots_mod > 0)
				{
					$temp_arr = array();
					
					if($total_slots == 0)
					{
						$temp_arr[0] = 0;
						$temp_arr[1] = $total_record_cnt -1;
					}
					else
					{
						$temp_arr[0] = $end_val+1;
						$temp_arr[1] = $end_val+$total_slots_mod;
					}
					$key_arr[] = $temp_arr;
				}
				
				
				//echo '<pre>'; 
				//print_r($postVars);exit;				
				//echo '<pre>'; print_r($key_arr); echo '</pre>'; exit;
				
				$mail_text = '';
				if(count($key_arr) > 0)
				{		
					foreach($key_arr as $key_res) 
					{
						$options = array(
							'http' => array(
							'method'  => 'POST',
							'header'=> "Content-Type: application/x-www-form-urlencoded\r\n",
							'content' => json_encode(array_slice($postVars, $key_res[0], $max_record_send)),
							)
						);
						
						//echo '<pre>'; print_r($options); echo '</pre>'; //exit; 
						
						//Pass our $options array into stream_context_create.
						//This will return a stream context resource.
						$streamContext  = stream_context_create($options);
						
						//Use PHP's file_get_contents function to carry out the request.
						//We pass the $streamContext variable in as a third parameter.
						$result = file_get_contents($url, false, $streamContext);
						
						//If $result is FALSE, then the request has failed.
						if($result === false)
						{
							//If the request failed, throw an Exception containing
							//the error.
							$error = error_get_last();
							throw new Exception('POST request failed: ' . $error['message']);
						}
						//If everything went OK, return the response.
						
						$result = str_replace("</pre>","",$result);
						$result = str_replace("<pre>","",$result);
						
						echo "<br><br>".$result; //exit;
						
						$mail_text .= '<br>Range : '.$key_res[0].' - '.$key_res[1].'<br>';
						$mail_text .= 'Message : '.$result.'<br><br>';
					}
					
					$mail_body = 
					'************************* CSC RPE VENDOR Cron Execution Start - '.date("Y-m-d H:i:s").' *************************<br><br>
					Total Applications : '.$total_record_cnt.'<br>
					'.$mail_text.'<br>					
					************************* CSC RPE VENDOR Cron Execution End - '.date("Y-m-d H:i:s").' *************************';
					
					$this->send_mail('logs@iibf.esdsconnect.com', 'iibfdevp@esds.co.in', 'logs@iibf.esdsconnect.com', 'logs@iibf.esdsconnect.com', 'CSC RPE Data Automation Cron Execution : '.date("Y-m-d"), $mail_body, $view_flag='', $attachment);
				}
			}
		}
		
		function post_data($url, $postVars = array())
		{	
			//echo count($postVars); exit;
			//print_r($postVars); //exit;
			//echo json_encode($postVars); exit;
			/* $ch = curl_init();
			curl_setopt($ch,CURLOPT_URL, $url);
			curl_setopt($ch,CURLOPT_POST, count($postVars));
			curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($postVars));
			$result = curl_exec($ch);
			
			if(curl_errno($ch))
			{
				echo curl_errno($ch);
			}
			curl_close($ch);
			var_dump($result);
			exit; */
			
			//echo json_encode($postVars) ;exit;
			$options = array(
			'http' => array(
			'method'  => 'POST',
			'header'=> "Content-Type: application/x-www-form-urlencoded\r\n",
			'content' => json_encode($postVars),
			)
			);
			
			//echo '<pre>'; print_r($options); echo '</pre>'; //exit; 
			
			//Pass our $options array into stream_context_create.
			//This will return a stream context resource.
			$streamContext  = stream_context_create($options);
			
			//Use PHP's file_get_contents function to carry out the request.
			//We pass the $streamContext variable in as a third parameter.
			$result = file_get_contents($url, false, $streamContext);
			
			//If $result is FALSE, then the request has failed.
			if($result === false)
			{
				//If the request failed, throw an Exception containing
				//the error.
				$error = error_get_last();
				throw new Exception('POST request failed: ' . $error['message']);
			}
			//If everything went OK, return the response.
			return $result;
		}
		
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
			
			$final_photo_img = '';
			if($scannedphoto != "")
			{
				$photo_img_arr = explode('.', $scannedphoto);
				if(count($photo_img_arr) > 0)
				{
					$chk_photo_img = $photo_img_arr[0];
					
					if(file_exists(FCPATH."uploads/photograph/".$chk_photo_img.'.jpg'))
					{
						$final_photo_img = $chk_photo_img.'.jpg';
					}
					else if(file_exists(FCPATH."uploads/photograph/".$chk_photo_img.'.jpeg'))
					{
						$final_photo_img = $chk_photo_img.'.jpeg';
					}
				}
			}
			
			if($final_photo_img == "")
			{
				if(file_exists(FCPATH."uploads/photograph/p_".$member_no.'.jpg'))
				{
					$final_photo_img = "p_".$member_no.'.jpg';
				}
				else if(file_exists(FCPATH."uploads/photograph/p_".$member_no.'.jpeg'))
				{
					$final_photo_img = "p_".$member_no.'.jpeg';
				}
			}
			
			if($final_photo_img != "") //Check photo in regular folder
			{ 
				$scannedphoto_res = base_url()."uploads/photograph/".$final_photo_img; 
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
			
			
			$final_idproofphoto_img = '';
			if($idproofphoto != "")
			{
				$idproofphoto_img_arr = explode('.', $idproofphoto);
				if(count($idproofphoto_img_arr) > 0)
				{
					$chk_idproofphoto_img = $idproofphoto_img_arr[0];
					
					if(file_exists(FCPATH."uploads/idproof/".$chk_idproofphoto_img.'.jpg'))
					{
						$final_idproofphoto_img = $chk_idproofphoto_img.'.jpg';
					}
					else if(file_exists(FCPATH."uploads/idproof/".$chk_idproofphoto_img.'.jpeg'))
					{
						$final_idproofphoto_img = $chk_idproofphoto_img.'.jpeg';
					}
				}
			}
			
			if($final_idproofphoto_img == "")
			{
				if(file_exists(FCPATH."uploads/idproof/pr_".$member_no.'.jpg'))
				{
					$final_idproofphoto_img = "pr_".$member_no.'.jpg';
				}
				else if(file_exists(FCPATH."uploads/idproof/pr_".$member_no.'.jpeg'))
				{
					$final_idproofphoto_img = "pr_".$member_no.'.jpeg';
				}
			}
			
			if ($final_idproofphoto_img != "") //Check id proof in regular folder
			{ 
				$idproofphoto_res = base_url()."uploads/idproof/".$final_idproofphoto_img; 
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
			
			
			$final_scanphoto_img = '';
			if($scannedsignaturephoto != "")
			{
				$scanphoto_img_arr = explode('.', $scannedsignaturephoto);
				if(count($scanphoto_img_arr) > 0)
				{
					$chk_scanphoto_img = $scanphoto_img_arr[0];
					
					if(file_exists(FCPATH."uploads/scansignature/".$chk_scanphoto_img.'.jpg'))
					{
						$final_scanphoto_img = $chk_scanphoto_img.'.jpg';
					}
					else if(file_exists(FCPATH."uploads/scansignature/".$chk_scanphoto_img.'.jpeg'))
					{
						$final_scanphoto_img = $chk_scanphoto_img.'.jpeg';
					}
				}
			}
			
			if($final_scanphoto_img == "")
			{
				if(file_exists(FCPATH."uploads/scansignature/s_".$member_no.'.jpg'))
				{
					$final_scanphoto_img = "s_".$member_no.'.jpg';
				}
				else if(file_exists(FCPATH."uploads/scansignature/s_".$member_no.'.jpeg'))
				{
					$final_scanphoto_img = "s_".$member_no.'.jpeg';
				}
			}
			
			if ($final_scanphoto_img != "") //Check signature in regular folder
			{ 
				$scannedsignaturephoto_res = base_url()."uploads/scansignature/".$final_scanphoto_img; 
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
		
		function send_mail($from_mail='', $to_email='', $cc_email='', $bcc_email='', $subject='', $mail_data='', $view_flag='', $attachment='')
		{
			if($from_mail != '' && $to_email != '' && $subject != '' && $mail_data != '')
			{
				if($view_flag=='1')
				{
					echo "<br>From = ".$from_mail;
					echo "<br>To = ".$to_email;				
					echo "<br>CC = ".$cc_email;				
					echo "<br>BCC = ".$bcc_email;				
					echo "<br>subject = ".$subject;
					echo "<br>message = ".$mail_data; 
					echo "<br>"; //exit;
				}
				
				$is_smtp = 1;
				if($is_smtp == 1)
				{
					//$this->Emailsending->setting_smtp();
					$config['protocol']    	= 'SMTP';
					//$config['smtp_host']    = 'iibf.esdsconnect.com';
					$config['smtp_host']    = '115.124.123.26';
					$config['smtp_port']    = '465';
					$config['smtp_timeout'] = '10';
					$config['smtp_user']    = 'logs@iibf.esdsconnect.com';
					$config['smtp_pass']    = 'logs@IiBf!@#';
					$config['smtp_crypto'] = 'tls';
					$config['charset']    	= 'utf-8';
					$config['newline']    	= "\r\n";
					$config['mailtype'] 	= 'html'; // or html
					$config['validation'] 	= TRUE; // bool whether to validate email or not  
					$this->email->initialize($config);	
				}
				else
				{
					$this->load->library('email');
					//$config['protocol'] = 'sendmail';
					//$config['mailpath'] = '/usr/sbin/sendmail';
					$config['charset'] = 'iso-8859-1';
					$config['charset'] = 'UTF-8';
					$config['wordwrap'] = TRUE;
					$config['mailtype'] = 'html';
					$this->email->initialize($config);
					//$this->email->subject($subject." php mail");
				}
				
				$this->email->from($from_mail);
				$this->email->to($to_email);
				
				if($cc_email != '') { $this->email->cc($cc_email); }
				if($bcc_email != '') { $this->email->bcc($bcc_email); }
				
				$this->email->subject($subject);
				$this->email->message($mail_data);
				
				if($attachment != '')
				{
					$this->email->attach($attachment);
				}
				
				if(@$this->email->send())
				{
					$final_msg = 'success';
				}
				else
				{
					$final_msg = 'error. Email not send<br>';
					$final_msg .= $this->email->print_debugger();
				}
				
				return $final_msg;
				$this->email->clear();				
			}
			else
			{
				return 'error - invalid form fields';
			}
		}	
		
		public function repair_images_temp()
		{
			$yesterday = '2021-10-19';
			$regnumberArr = array(500170494,500192136,510411663,510245902,500196964,500098559,500052672,500042311,510102892,510398177,510084755,500043293,510481122,510389125,510196958,100041276,510247955,510207612,500181559,500181321,510143739,510332365,510440894,510229108,510118234,510143101,510041743,500043571,500148013,510075456,510515110,510474652,7412801,510213180,510477773,500093869,100030497,510031398,510428398,510134323,500113292,510236360,510093078,510436579,510290086,510286586,510482427,510289131,510263643,510093655,510315480,500121135,510355183,510204120,500026702,510295508,510396976,510337953,510261054,500099333,510184646,510483336,510355193,510165689,510149081,510069679,500028032,500168238,510033364,510199249,510085680,510275253,801794388,510374107,510063348,510132772,510098106,500101547,510268253,510093653,510449389,510488564,500128688,510125583,500082785,500164271,510194543,510123308,510474660,510410426,510350129,5751397,510322428,510227671,500210379,510325903,500050705,510030890,510290652,801797889,510447901,510062768,510438270,510497461,801622935,510339195,6990891,510177481,801221633,510459143,510058590,801798388,510395444,510112181,510135501,500041315,510209152,500051005,500192522,510324142,510121866,510136146,510272479,510380455,500160155,510442513,500131152,510173040,510275958,510368214,500124307,510289953,510026385,510084033,510245491,510050791,500104488,510089801,510025208,100041599,510470626,510436231,510437528,510504001,510051582,500054378,510369624,510033947,510299585,510082592,500187146,510290374,510157018,510472547,500182304,510352661,510429013,510136185,510086677,510133561,510474669,510035055,510292776,510346480,510062473,510518951,510416234,500179127,510473929,510018425,510437204,6633253,801800528,510137030,510084123,510370366,500177855,510432426,510449716,510081905,510038800,510465399,510465726,500083492,510252832,500007222,510445881,801801076,510407528,510051526,500192319,510136244,400075451,510316394,500153981,500202258,510510812,510332491,500053534,510319801,510448494,500131945,500035532,510379784,510356867,510002192,510449202,510494889,510282532,510359134,510449960,510383727,500016669,510472446,510217263,500005138,510353022,500116622,510315784,500015670,500084208,500170277,510204243,510335040,510170948,510018621,500075259,500132793,510452675,510071272,801792350,500053662,510201193,510372376,510432984,510468523,510376315,510335507,510248798,510299539,500167169,500084111,510182953,500040452,500004140,510103086,510291769,510322759,510111909,510238724,500041537,510224424,510050436,510448616,510362905,7005340,801793012,500045852,500092296,510208298,510321762,510442435,510356471,510228981,510149982,510248344,510165186,801793516,6577109,500187034,510185945,801794284,510435563,100014887,510335565,500077207,510463259,510100029,500072215,500206336,510048675,510405606,500059577,510247959,510358852,500148877,510117938,500044997,500153264,500154391,510374939,510008872,510343729,510517124,510219097,510062149,100090234,500032022,510145283,510255373,510392542,510239695,7599620,510430341,510090725,200077450,801798576,510457485,510109926,500195903,510439101,510066929,510012089,510218596,510143537,510327374,510140342,500212986,510285811,510238059,500170262,510183020,510396656,510430170,510452684,510172876,510400998,510161595,510377713,510454437,510412757,510449348,500048724,500212123,801029544,510268118,510513298,801799520,510128690,510326146,510311499,500053929,510509046,510483658,801765158,510203337,510510885,510400957,801799759,510306813,500138812,510487191,801192534,510167274,510168540,500095757,510108387,510508329,510473140,510356132,510178250,510022000,500109910,510410443,510165036,510398651,100015347,510347578,801801090,801764479,801764528,801801158,510355428,500191189,510517939,510365002,6646592,500106136,510280231,7294725,7142321,510063037,510124023,510147716,510317006,510394848,510167707,510522214,510379525,510481867,510445584,510041293,510236338,510191874,510122584,400001564,510169570,500206394,510018456,500019287,500104164,500114812,510033293,510021415,510182510,510179172,510483568,510216552,510167748,510204720,510222455,510146778,510510502,500053241,510458876,510317758,510373150,500181462,510432932,510306866,510048837,500106938,500110685,500036377,500165154,510474301,500191298,510053335,500113672,510117485,510365601,510123990,500136944,801794656,510130540,510035715,510012851,510365001,510454980,510247235,510339284,510348536,510456075,510429159,801742749,510280495,500104463,510168634,510137877,801655247,510140455,510273825,510088022,510433244,510190828,100088352,6627292,500058297,510219549,510045208,510456448,7553676,510390497,510288699,510246271,510403838,500085422,510179430,7199833,500134512,510484939,500080300,500009316,100074080,510061398,500094124,510428984,510232554,510081170,5993502,510166266,510398199,510041212,500090949,510322976,500037574,510109768,510118288,500078702,500002573,500051172,510023596,510238074,510500988,500077672,510231777,510208508,500164619,500153413,510285218,400021155,500073620,510400028,500055998,510280823,510438322,300011601,801557138,801799372,510320314,510391381,500185886,500095965,500172167,6871282,200053055,801800354,500055134,801800801,500034607,510293814,500060202,500102455,801743819,801539014,500153768,510152324,400116428,6696324,500212721,200046963,510170625,500198295,7495643,100058907,510141022,510231818,500081542,801674185,100061635,6859706,500070532,500085913,500072375,300024744,400035025,510046720,6849906,510515197,510299670,500036414,200001406,200064694,500157309,500153569,7290271,510394590,500050443,510169559,500051456,500006569,500157513,300004053,500076112,500121694,500050028,500055681,500179796,500170556,500140170,5647531,5322947,300008491,510338950,500030171,510389281,300021742,400133401,510077628,300012467,500184432,500083125,801193769,500011005,510194888,500081948,500063211,6733366,510273976,500012765,510189592,510501619,7214804,510113306,400036416,510079244,510061788,510416630,500196619,500139651,510426194,7434728,510268830,801739332,500155394,510254549,500018119,300028618,510441182,6632873,500103023,300005097,510377915,510382455,500067357,500110499,7525214,500026215,500071623,510296355,500047529,510180874,510009931,510328066,500093372,510040545,510005677,510111147,510053367,510048893,100033229,100055772,7232123,500092853,400077581,510312448,500033376,100005181,500128839,400111412,400001161,510483701,510357523,200008158,510034841,510166403,510508718,500082855,510232423,7216685,6928741,500011121,510355235,510507801,510211393,510230506,500125707,7497497,300006675,510023133,7072164,500155684,510338767,400063932,300000395,500052118,510325616,500065880,500007637,510119043,801792232,510413858,7281861,510069697,7057346,7010027,6901668,500054167,500047607,7460782,510317892,100043341,400128269,510237397,801471873,500087853,510342835,510112546,500072343,510252080,500185713,510257384,510355488,510412089,7236383,500008355,510139280,510017515,7152579,7141771,801792500,510040690,7538651,7459511,801254220,5745872,500052214,500152438,510224071,500116140,510155303,510271749,200029855,510042066,7527157,500117686,500178121,500077641,6707938,7446961,510508076,500108748,500059879,7653958,400068751,500152931,510062923,510136356,510427958,801533373,500009657,510087478,510217130,500108812,200064068,510356133,510009489,510507434,500071384,801001038,500073758,510069416,510381509,500137737,510189572,510328881,510202541,510505379,510232447,510402802,500057437,500009209,801508759,7347177,500082648,7644645,300017141,510234371,510478315,500127278,801478578,801792877,510515070,510266139,7145378,500172994,500104027,510515205,510016522,400083908,510357277,500169389,801220817,500174104,510211819,801471598,6722504,100010734,510132675,510176842,510474199,510296702,510035546,801515256,500017706,300002335,801257439,510477940,500019425,500079455,801794112,400068993,801794168,200041528,510195275,510337292,7404522,500064408,500061726,500060570,510484311,300000152,510042107,500210589,500008782,510377888,500168048,500059787,510136763,510317116,500135317,500118188,510101313,500157301,200030776,500138245,500119495,510058921,6883443,500087412,7663060,801680612,510455842,510309214,510483817,510308671,510417015,510081764,500132167,5679024,500119233,500028782,801742760,100078024,100080381,500187245,510344897,500168886,300008704,300007046,510288308,510096578,200040226,510418902,500051445,510436162,510428496,7653081,500148483,510428405,510092416,510513590,7024499,510000180,100089725,7658749,500035682,500018571,7340499,510102287,500094849,6749043,5472461,400081046,500148184,500151922,510473167,510231905,510507647,510516718,510468817,7644780,500135003,500060216,500170240,510411904,300000887,510434572,500070759,510468554,500155492,510476488,801323401,510376397,510053844,500074557,510411878,100025432,801800338,5107108,200028508,500153094,510451186,500016528,100050481,300007476,500075921,500001425,510465179,801800609,500215701,510478757,801798432,510032752,500166975,510052502,500144349,510141772,510203395,510443964,500164563,500018924,510070591,801427429,510183711,510385825,510015326,510119454,510395471,510384271,500065271,510052197,510513686,510468506,500034646,510518003,510013924,500040043,510100218,510412752,801654698,510510511,510202987,801678709,500183359,510521479,7172016,500038214,801423827,510471065,300039756,100053910,510292130,7449079,500014772,7307592,400068465,5962211,500080258,510521377,6060338,500040417,801801292,510521570,500157546,510349263,801010927,500145212,510279137,500075480,801426528,500018216,510517249,100020955,801426436,7194631,510507925,300024851,7090449,510209127,510347633,500054934,500164142,500072498,801802615,510129509,801471376,510479553,500053269,510428967,510113272,500184195,510425860,7659147,801520468,7285181,510512195,510508720,400005115,500139518,801802786,500196994,500122917,400026812,801253752,801742570,510297711,510473357,510337035,400082393,510135222,801803108,510176904,510438419,100039394,510298428,500033925,500130911,500056134,500019039,510444345,500076704,500003373,510516737,500051594,500181989,510360434,500061739,510012481,500187803,500056032,510476416,801803159,510214883,510109552,500154490,300006559,510356944,510162966,510106658,6156878,100047851,5458767,500139556,500121424,510399306,100096541,510386737,801673651,500060613,5978650,510319015,500127500,510146994,6841800,500086253,500067989,510356895,510508085,510037271,510258485,500107227,500010016,801803474,500039254,510480227,510508511,510343698,510283083,510445115,510126792,500152214,500045315,500042502,7275505,500159547,7646229,801803538,510349096,500173655,500071737,500171096,510053418,510406523,500160800,510355932,510026172,500028772,500020021,500120279,510015871,510181586,7391925,500008819,510081493,7261733,500029703,510330439,510355673,801773211,500162521,7515961,100071794,500153456,500167585,510057789,510239989,510512517,801771061); 
			$this->db->where_in('a.regnumber', $regnumberArr);
				
			$exam_code = array('1003','1004','1005','1006','1007','1008','1009'); // Send Free and Paid Both Applications '1002',
        
			//$this->db->limit(85);
			// DISTINCT(b.transaction_no),
			$select = 'a.exam_code, c.regid, c.firstname, c.middlename, c.lastname, c.image_path, c.reg_no, c.scannedphoto, c.idproofphoto, c.scannedsignaturephoto, a.regnumber, c.dateofbirth, c.gender, c.email, c.stdcode, c.office_phone, c.mobile, c.address1, c.address2, c.address3, c.address4, c.district, c.city, c.state, c.pincode, c.associatedinstitute, c.designation, a.exam_period, a.elected_sub_code, c.editedon, a.examination_date, a.id AS mem_exam_id, a.created_on AS registration_date, a.exam_medium, a.exam_center_code, d.venueid, d.pwd, d.exam_date, d.time';
			//$this->db->join('payment_transaction b', 'b.ref_id = a.id', 'LEFT');
			$this->db->join('member_registration c', 'a.regnumber=c.regnumber', 'LEFT');
			$this->db->join('admit_card_details d', 'a.id=d.mem_exam_id', 'LEFT');
			$this->db->where_in('a.exam_code', $exam_code);
			$can_exam_data = $this->Master_model->getRecords('member_exam a', array(
			'remark' => 1,
			/*'pay_type' => 2, 
			'status' => 1,*/
			'isactive' => '1',
			'isdeleted' => 0,
			'pay_status' => 1,
			'DATE(a.created_on) >=' =>'2021-01-01', //$yesterday,
			), $select); 
			//echo $this->db->last_query(); //exit;
			//echo "<pre>"; print_r($can_exam_data); echo "</pre>"; //exit;
				
			if (count($can_exam_data)) 
			{
				foreach ($can_exam_data as $exam) 
				{
					echo '<br>mem_number : '.$mem_number = $exam['regnumber'];
					$member_images = $this->get_member_images($exam['image_path'], $exam['reg_no'], $exam['regnumber'], $exam['scannedphoto'], $exam['idproofphoto'], $exam['scannedsignaturephoto'], $exam['regid'], $yesterday);
						
					$scannedphoto = $member_images['scannedphoto'];
					$expected_scannedphoto = base_url().'uploads/photograph/p_'.$mem_number.'.jpg';
					if($scannedphoto != $expected_scannedphoto)
					{						
						$chk_response = $this->update_image_name($scannedphoto,$expected_scannedphoto);
						if($chk_response != "") 
						{ 
							$scannedphoto = $chk_response;
							//$update_data = array('scannedphoto' => $expected_scannedphoto);
							//$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $mem_number));
						}
					}
					echo '<br>photo : '.$scannedphoto;
					echo '<br>photo : '.$expected_scannedphoto;
						
					$scannedsignaturephoto = $member_images['scannedsignaturephoto'];
					$expected_scannedsignaturephoto = base_url().'uploads/scansignature/s_'.$mem_number.'.jpg';
					if($scannedsignaturephoto != $expected_scannedsignaturephoto)
					{
						$chk_response = $this->update_image_name($scannedsignaturephoto,$expected_scannedsignaturephoto);
						if($chk_response != "") 
						{ 
							$scannedsignaturephoto = $chk_response;
							//$update_data = array('scannedphoto' => $expected_scannedphoto);
							//$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $mem_number));
						}
					}
					echo '<br>signature : '.$scannedsignaturephoto;
					echo '<br>signature : '.$expected_scannedsignaturephoto;
					
					$idproofphoto = $member_images['idproofphoto'];
					$expected_idproofphoto = base_url().'uploads/idproof/pr_'.$mem_number.'.jpg';
					if($idproofphoto != $expected_idproofphoto)
					{
						$chk_response = $this->update_image_name($idproofphoto,$expected_idproofphoto);
						if($chk_response != "") 
						{ 
							$idproofphoto = $chk_response;
							//$update_data = array('scannedphoto' => $expected_scannedphoto);
							//$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $mem_number));
						}
					}
					echo '<br>idproof : '.$idproofphoto;
					echo '<br>idproof : '.$expected_idproofphoto;
					echo "<br>=========================================================================================<br>";
				}
			}
		}
		
		public function update_image_name($current_img_name='', $new_img_name='')
		{
			$base_url = base_url();
			$current_img_name = str_replace($base_url,'./',$current_img_name);
			$new_img_name = str_replace($base_url,'./',$new_img_name); //exit;
			
			$final_img_name = '';
			
			if($current_img_name != "")
			{
				if(file_exists($current_img_name))
				{
					if($new_img_name != "" && $new_img_name != $current_img_name)
					{
						if(file_exists($new_img_name))
						{
							$final_img_name = $new_img_name;
						}
						else
						{
							@copy($current_img_name,$new_img_name);
							
							if(file_exists($new_img_name))
							{
								$final_img_name = $new_img_name;
							}
							else
							{
								$final_img_name = $current_img_name;
							}
						}
					}
					else
					{
						$final_img_name = $current_img_name;
					}
				}
			}
			
			return str_replace('./',$base_url,$final_img_name);
		}
	
	}				