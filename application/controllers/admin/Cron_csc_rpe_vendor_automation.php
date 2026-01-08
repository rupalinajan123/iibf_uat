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
	
	class Cron_csc_rpe_vendor_automation extends CI_Controller
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
			$cron_file_dir = "./uploads/cronCSV_cscvendor/";
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
			$desc = json_encode($result);
			$this->log_model->cronlog("CSC RPE VENDOR Cron Execution Start", $desc);
			
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
				//$yesterday = '2022-07-13'; 
        
        //$regnumberArr = array('510017365'); 
				//$this->db->where_in('a.regnumber', $regnumberArr);
				
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
				'DATE(a.created_on)' => $yesterday,
				), $select); 
				//echo $this->db->last_query(); exit;
				//echo "<pre>"; print_r($can_exam_data); echo "</pre>"; //exit;
				
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
						
						$exam_arr = array('1002' => 'ANTI MONEY LAUNDERING AND KNOW YOUR CUSTOME',
						'1003' => 'CERTIFICATE COURSE ON MSME',
						'1004' => 'PREVENTION OF CYBER CRIMES AND FRAUD MANAGEMENT',
						'1005' => 'CERTIFIED CREDIT PROFESSIONAL',
						'1006' => 'CERTIFIED TREASURY PROFESSIONALS',
						'1007' => 'CERTIFICATE IN RISK IN FINANCIAL SERVICES LEVEL 1',
						'1008' => 'CERTIFIED ACCOUNTING AND AUDIT PROFESSIONAL',
						'1009' => 'CERTIFICATE COURSE IN FOREIGN EXCHANGE');						
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
						$scannedsignaturephoto = $member_images['scannedsignaturephoto'];
						$idproofphoto = $member_images['idproofphoto'];
						
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
				//echo "<pre>"; echo json_encode($api_data_arr); echo "</pre>";  //exit;
				
				/*************** START : SEND DATA TO API IN JSON FORMAT ********************************/
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
					$this->send_mail('logs@iibf.esdsconnect.com', 'iibfdevp@esds.co.in', 'logs@iibf.esdsconnect.com', 'logs@iibf.esdsconnect.com', 'CSC RPE Data Automation Cron Execution : '.date("Y-m-d"), $mail_body, $view_flag='', $attachment);
					//$this->send_mail('sagar.matale@esds.co.in', 'sagar.matale@esds.co.in', '', '', 'CSC CSV RPE Data Automation Cron Execution : '.date("Y-m-d"), $mail_body, $view_flag='', $attachment);					
				}
				/*************** END : SEND ENCRYPTED DATA TO API IN JSON FORMAT ********************************/
				
				$this->master_model->updateRecord('cron_cscvendor_csc_sm',array('mail_content'=>json_encode($mail_body)),array('id'=>$last_inserted_id));
				
				$end_time = date("Y-m-d H:i:s");
				$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
				$desc = json_encode($result);
				$this->log_model->cronlog("CSC RPE VENDOR Cron Execution End", $desc);
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
	}				