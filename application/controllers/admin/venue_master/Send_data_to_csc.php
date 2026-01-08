<?php
/********************************************************************
* Created BY: Sagar Matale
* Update By : Sagar Matale on: 14/09/2021
* Description: This is automation cron for sending data to CSC VENDOR for csc exam. 
* Previous cron file : 
* Exam codes : 991
********************************************************************/

	defined('BASEPATH') OR exit('No direct script access allowed');
	/* header("Access-Control-Allow-Origin: *"); */
	
	class Send_data_to_csc extends CI_Controller
	{    
		public function __construct()
		{ 
			parent::__construct();
			$this->load->library('upload');
			$this->load->model('Master_model');
			$this->load->model('log_model'); 
		}
		//usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron_csc_vendor_automation_custom 
		public function index()
    {
			ini_set("memory_limit", "-1");
			$dir_flg = $parent_dir_flg = $exam_file_flg  = 0;
			$success = $error = array();
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd");
			$cron_file_dir = "./uploads/rahultest/"; 
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
			$desc = json_encode($result);
			//$this->log_model->cronlog("CSC CSV Cron Execution Start", $desc);
			
			if (!file_exists($cron_file_dir . $current_date)) { $parent_dir_flg = mkdir($cron_file_dir . $current_date, 0700); }
			
			if (file_exists($cron_file_dir . $current_date)) 
			{
				$cron_file_path = $cron_file_dir . $current_date; // Path with CURRENT DATE DIRECTORY
				
				$file1 = "Automation_csc_vendor_cron_logs_" . $current_date . ".txt";
				$fp1 = fopen($cron_file_path . '/' . $file1, 'a');
				fwrite($fp1, "\n***** CSC CSV Cron Execution Started - " . $start_time . " ***** \n");
				
				$file2 = "Automation_csc_vendor_member_img_missing_logs_" . $current_date . ".txt";
				$member_img_log = fopen($cron_file_path.'/'.$file2,'a');
				
				$file3 = "Automation_csc_vendor_img_cron_logs_" . $current_date . ".txt";
				$fp3 = fopen($cron_file_path . '/' . $file3, 'a');
				fwrite($fp3, "\n***** CSC CSV Cron Execution Started - " . $start_time . " ***** \n");

				$yesterday = '2022-09-12'; //date('Y-m-d', strtotime("- 1 day"));
				 
				$exam_code = array('991','997'); 
				if(isset($_GET['member_ids']) && !empty($_GET['member_ids'])) {
					$member_nos=explode(',',$_GET['member_ids']);
					foreach($member_nos as $m)
						$member_no[]=trim($m);
				}
				if(isset($_GET['exam_date']) && !empty($_GET['exam_date'])) {
					$exam_date=date('Y-m-d',strtotime($_GET['exam_date']));
				}
				if(isset($_GET['register_date']) && !empty($_GET['register_date'])){
					$register_date=date('Y-m-d',strtotime($_GET['register_date']));
				}
				//$member_no = array(802164364);

				
				$this->db->where("((a.exam_code = '991' AND bankcode = 'csc') OR (a.exam_code = '997'))");
				
				$select = 'DISTINCT(b.transaction_no),a.exam_code,c.regid, c.firstname,c.middlename,c.lastname, c.image_path, c.reg_no, c.regnumber, c.scannedphoto, c.idproofphoto, c.scannedsignaturephoto, a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id,a.created_on AS registration_date,a.exam_medium,a.exam_center_code,d.venueid,d.pwd,d.exam_date,d.time';

				if(isset($_GET['register_date']) && !empty($_GET['register_date']))
					$this->db->where('date(a.created_on)',$register_date);

				if(isset($_GET['member_ids']) && !empty($_GET['member_ids']))
					$this->db->where_in('a.regnumber',$member_no);

				if(isset($_GET['exam_date']) && !empty($_GET['exam_date']))
			    	$this->db->where('d.exam_date',$exam_date);
				else
					$this->db->where('d.exam_date >=',date('Y-m-d'));
					
				$this->db->join('payment_transaction b', 'b.ref_id = a.id', 'LEFT');
				$this->db->join('member_registration c', 'a.regnumber=c.regnumber', 'LEFT');
				$this->db->join('admit_card_details d', 'a.id=d.mem_exam_id', 'LEFT');
				
				//384691
				// for custom data send 
				/* $regnumberArr = array('801671553'); 
				$this->db->where_in('a.regnumber', $regnumberArr); */				
				$can_exam_data = $this->Master_model->getRecords('member_exam a', array(
				'remark' => 1,
				'pay_type' => 2,
				'status' => 1,
				'isactive' => '1',
				'isdeleted' => 0,
				'pay_status' => 1,
			//	'DATE(d.exam_date)' =>'2022-11-30',  					
				), $select,'','', 
				'' 
				);	
				//'DATE(a.created_on)' => $yesterday,
				//'DATE(d.exam_date)' => '2022-08-24',				
			//	echo $this->db->last_query(); exit;
			//	echo "<pre>"; print_r($can_exam_data); echo "</pre>"; exit;
				$exam_cnt = 0;
				$api_data_arr = array();
				if (count($can_exam_data)) 
				{
					$i = 1;					
					foreach ($can_exam_data as $exam) 
					{
						$firstname = $middlename = $lastname = $city = $state = $pincode = $dateofbirth = $address = $gender = $exam_name = $registration_date = $data = $syllabus_code = $subject_description = $subject_code = $subject_description = $institution_name = $designation_name = $exam_code = $exam_name = $medium_name = $server_url = $scannedphoto = $scannedsignaturephoto = $idproofphoto = '';
						
						//ADDED BY SAGAR ON 27-06-2020 FOR MEMBER IMAGES          
            $member_images = $this->get_member_images($exam['image_path'], $exam['reg_no'], $exam['regnumber'], $exam['scannedphoto'], $exam['idproofphoto'], $exam['scannedsignaturephoto'], $exam['regid'], $yesterday);
            $scannedphoto = $member_images['scannedphoto'];
            $scannedsignaturephoto = $member_images['scannedsignaturephoto'];
            $idproofphoto = $member_images['idproofphoto'];
						
						if($scannedphoto == "") { fwrite($member_img_log, "Photo missing - " . $exam['regnumber'] . " \n"); }
            if($scannedsignaturephoto == "") { fwrite($member_img_log, "Signature missing - " . $exam['regnumber'] . " \n"); }
            if($idproofphoto == "") { fwrite($member_img_log, "ID Proof missing - " . $exam['regnumber'] . " \n"); }            
            if($scannedphoto == "" || $scannedsignaturephoto == "" || $idproofphoto == "") { fwrite($member_img_log, "\n"); }
						 
						if ($exam['exam_code'] != '' && $exam['exam_code'] != 0) 
						{
              $ex_code = $this->master_model->getRecords('exam_activation_master', array('exam_code' => $exam['exam_code']));
              if(count($ex_code)) 
              {
                if($ex_code[0]['original_val'] != '' && $ex_code[0]['original_val'] != 0) 
								{
                  $exam_code = $ex_code[0]['original_val'];
								} 
                else 
                {
                  $exam_code = $exam['exam_code'];
								}
							}
              else 
              {
                $exam_code = $exam['exam_code'];
							}
						} 
            else 
						{
              $exam_code = $exam['exam_code'];
						}
						
						$dateofbirth = date('d-m-Y', strtotime($exam['dateofbirth']));
            $registration_date = date('d-m-Y', strtotime($exam['registration_date']));
            
            $address = $exam['address1'] . ' ' . $exam['address2'] . ' ' . $exam['address3'] . ' ' . $exam['address4'] . ' ' . $exam['district'] . ' ' . $exam['city'];
            $address = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
            $gender = $exam['gender'];
            if ($gender == 'male' || $gender == 'Male') { $gender = 'M'; } elseif($gender == 'female' || $gender == 'Female') { $gender = 'F'; }
            $designation = $this->master_model->getRecords('designation_master');
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
            $designation_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $designation_name);
						
						$medium = $this->master_model->getRecords('medium_master');
            if(count($medium)) 
						{
              foreach ($medium as $medium_row) 
							{
                if ($exam['exam_medium'] == $medium_row['medium_code']) 
								{
                  $medium_name = $medium_row['medium_description'];
								}
							}
						}
            $medium_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $medium_name);
            
            $institution_master = $this->master_model->getRecords('institution_master');
            if(count($institution_master)) 
						{
              foreach ($institution_master as $institution_row) 
							{
                if ($exam['associatedinstitute'] == $institution_row['institude_id']) 
								{
                  $institution_name = $institution_row['name'];
								}
							}
						}
            $institution_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $institution_name);
            $firstname = preg_replace('/[^A-Za-z\/ ]/', '', $exam['firstname']);
            $middlename = preg_replace('/[^A-Za-z]/', '', $exam['middlename']);
            $lastname = preg_replace('/[^A-Za-z]/', '', $exam['lastname']);
            $mobile = preg_replace('/[^0-9]/', '', $exam['mobile']);
            $pincode = preg_replace('/[^0-9]/', '', $exam['pincode']);
						
						$exam_arr = array('991'=>'CERTIFICATE EXAMINATION FOR BUSINESS CORRESPONDENTS/FACILITATORS');            
            foreach ($exam_arr as $k => $val) 
						{
              if ($exam_code == $k)  
							{
                $exam_name = $val;
							}
						}
						
            $select    = 'regnumber';
            $this->db->where_in('exam_code', 991);
            $this->db->where_in('regnumber', $exam['regnumber']);
            $attempt_count = $this->Master_model->getRecords('member_exam', array('pay_status' => 1), $select);
            $attempt_count = count($attempt_count);            
            $attempt_count = $attempt_count - 1;
						
						$post_field_arr['first_name'] = $firstname;
            $post_field_arr['middle_name'] = $middlename;
            $post_field_arr['last_name'] = $lastname;
            $post_field_arr['member_number'] = $exam['regnumber'];
            $post_field_arr['password'] = $exam['pwd'];
            $post_field_arr['dob'] = date("Y-m-d", strtotime($dateofbirth));
            $post_field_arr['gender'] = $gender;
            $post_field_arr['email_id'] = $exam['email'];
            $post_field_arr['mobile'] = $mobile;
            $post_field_arr['address'] = $address;
            $post_field_arr['state'] = $exam['state'];
            $post_field_arr['pin_code'] = $pincode;
            $post_field_arr['country'] = 'INDIA';
            $post_field_arr['profession'] = '';
            $post_field_arr['organization'] = $institution_name;
            $post_field_arr['designation'] = $designation_name;
            $post_field_arr['exam_code'] = $exam_code;
            $post_field_arr['course'] = $exam_name;
            $post_field_arr['elective_sub_code'] = $subject_code;
            $post_field_arr['elective_sub_desc'] = $subject_description;
            $post_field_arr['attempt'] = $attempt_count;
            $post_field_arr['registration_date'] = date("Y-m-d", strtotime($registration_date));
            $post_field_arr['exam_date'] = date("Y-m-d", strtotime($exam['exam_date']));
            $post_field_arr['batch_start_time'] = $exam['time'];
            $post_field_arr['exam_medium'] = $medium_name;
            $post_field_arr['exam_center_code'] = $exam['exam_center_code'];
            $post_field_arr['venue_code'] = $exam['venueid'];
            $post_field_arr['server_url'] = $server_url;
            $post_field_arr['p_image'] = $scannedphoto;
            $post_field_arr['s_image'] = $scannedsignaturephoto;
            $post_field_arr['pr_image'] = $idproofphoto;
						
            
						
						$i++;
						$exam_cnt++;
						
						$append_log_data = $firstname.' | '.$middlename.' | '.$lastname.' | '.$exam['regnumber'].' | '.$exam['pwd'].' | '.date("Y-m-d", strtotime($dateofbirth)).' | '.$gender.' | '.$exam['email'].' | '.$mobile.' | '.$address.' | '.$exam['state'].' | '.$pincode.' | '.'INDIA'.' | '."".' | '.$institution_name.' | '.$designation_name.' | '.$exam_code.' | '.$exam_name.' | '.$subject_code.' | '.$subject_description.' | '.$attempt_count.' | '.date("Y-m-d", strtotime($registration_date)).' | '.date("Y-m-d", strtotime($exam['exam_date'])).' | '.$exam['time'].' | '.$medium_name.' | '.$exam['exam_center_code'].' | '.$exam['venueid'].' | '.$server_url.' | '.$scannedphoto.' | '.$scannedsignaturephoto.' | '.$idproofphoto;
						fwrite($fp1, "\n".$exam_cnt.' - '.$append_log_data."\n");
						// $exam['scannedphoto'], $exam['idproofphoto'], $exam['scannedsignaturephoto']
						if(strpos($exam['scannedphoto'], "k_") !== false){
							$alternate_scannedphoto	=	base_url()."uploads/photograph/".str_replace("k_","",$exam['scannedphoto']);
						}
						else
						$alternate_scannedphoto	=	base_url()."uploads/photograph/".'k_'.$exam['scannedphoto'];
						
						if(strpos($exam['idproofphoto'], "k_") !== false){
							$alternate_idproofphoto	=	base_url()."uploads/idproof/".str_replace("k_","",$exam['idproofphoto']);
						}
						else
						$alternate_idproofphoto	=	base_url()."uploads/idproof/".'k_'.$exam['idproofphoto'];

						if(strpos($exam['scannedsignaturephoto'], "k_") !== false){
							$alternate_scannedsignaturephoto	=	base_url()."uploads/scansignature/".str_replace("k_","",$exam['scannedsignaturephoto']);
						}
						else
						$alternate_scannedsignaturephoto	=	base_url()."uploads/scansignature/".'k_'.$exam['scannedsignaturephoto'];

						$append_img_log =  $exam['regnumber'].' | '. $scannedphoto. ' | '.$scannedsignaturephoto.' | '.$idproofphoto .' || '.$alternate_scannedphoto.' | '.$alternate_scannedsignaturephoto.' | '.$alternate_idproofphoto;
						
						fwrite($fp3, "\n".$exam_cnt.' - '.$append_img_log."\n");
						$post_field_arr['k_p_image']	=	$alternate_scannedphoto;
						$post_field_arr['k_s_image']	=	$alternate_scannedsignaturephoto;
						$post_field_arr['k_pr_image']	=	$alternate_idproofphoto;
					$api_data_arr[] = $post_field_arr; 
				
					}
					fwrite($fp1, "Total Applications - " . $exam_cnt . "\n");
					
					$insert_info = array('CurrentDate' => $current_date,'old_file_name' => '','new_file_name' => '','record_count' => $exam_cnt,'createdon' => date('Y-m-d H:i:s'));
					$this->master_model->insertRecord('cron_csv_csc_sm', $insert_info, true); 
					$last_inserted_id = $this->db->insert_id();
				} 
				else 
				{
					fwrite($fp1, "No data found for the date: " . $yesterday . " \n");
					$insert_info = array('CurrentDate' => $current_date,'old_file_name' => '','new_file_name' => '','record_count' => 0,'createdon' => date('Y-m-d H:i:s'));
					$this->master_model->insertRecord('cron_csv_csc_sm', $insert_info, true);
					$last_inserted_id = $this->db->insert_id();
					$success[] = "No data found for the date";
				}
				fclose($member_img_log);
				//echo "<pre>"; print_r($api_data_arr); exit;  
				
				//echo json_encode($api_data_arr); exit;
				
				/*************** START : SEND DATA TO API IN JSON FORMAT ********************************/
				//$api_data_new_arr['request_data'] = $api_data_arr; 
				$mail_body = '';
				if(count($api_data_arr) > 0) 
				{ 
					$api_json = json_encode($api_data_arr);
					
					$data_send_url = 'https://iibf.cscexams.in/backend/web/user/getstudentsapi';
					
					//$data_send_url = 'https://iibf.esdsconnect.com/admin/cron_csc_vendor_automation/test_post_data/'.$exam_cnt;
					
					//Send a POST request without cURL.
					$result = $this->post_data($data_send_url, $api_data_arr);  
					//print_r($result); die;
					$result = str_replace("</pre>","",$result);
					$result = str_replace("<pre>","",$result); 
					
					echo $result;
										
					$mail_body = 
					'************************* CSC CSV  Execution Start - '.$start_time.' *************************<br><br>
					Total Applications : '.$exam_cnt.'<br>
					Message : <br>'.$result.'<br><br>
					************************* CSC CSV  Execution End - '.date("Y-m-d H:i:s").' *************************';
					
					$attachment1 = $cron_file_path.'/'.$file2;
					$attachment2 = $cron_file_path.'/'.$file1;
					$attachment3 = $cron_file_path.'/'.$file3;
					//$this->send_mail('logs@iibf.esdsconnect.com', 'iibfdevp@esds.co.in, iibfexam@cscacademy.org', 'CSC Data Automation Cron Execution : '.date("Y-m-d"), $mail_body, $view_flag='', $attachment);
					//$this->send_mail('priyanka.dhikale@esds.co.in', 'priyanka.dhikale@esds.co.in', 'CSC Data Automation Execution : '.date("Y-m-d"), $mail_body, $view_flag='', $attachment1,$attachment2,$attachment3);
				}
				else
				{
					$mail_body = 
					'************************* CSC CSV Execution Start - '.$start_time.' *************************<br><br>
					Total Applications : 0<br>					
					************************* CSC CSV Execution End - '.date("Y-m-d H:i:s").' *************************';
					
					$attachment1 = $cron_file_path.'/'.$file2;
					$this->send_mail('logs@iibf.esdsconnect.com', 'iibfdevp@esds.co.in', 'CSC Data Automation Execution : '.date("Y-m-d"), $mail_body, $view_flag='', $attachment1);
				}
				/*************** END : SEND ENCRYPTED DATA TO API IN JSON FORMAT ********************************/
				
				$this->master_model->updateRecord('cron_csv_csc_sm',array('mail_content'=>json_encode($mail_body)),array('id'=>$last_inserted_id));
				
				$end_time = date("Y-m-d H:i:s");
				$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
				$desc = json_encode($result);
				//$this->log_model->cronlog("CSC CSV Cron Execution End", $desc);
				fwrite($fp1, "\n" . "***** CSC CSV Cron Execution End " . $end_time . " ******" . "\n");
				fclose($fp1); 
			}
		}
		
		function post_data($url, $postVars = array())
		{
			//Transform our POST array into a URL-encoded query string.
			//$postStr = http_build_query($postVars);
			//$postStr = json_encode(http_build_query($postVars));
			//echo $postStr; exit;
			
			//Create an $options array that can be passed into stream_context_create.
			/* $options = array(
				'http' =>
						array(
								'method'  => 'POST', //We are using the POST HTTP method.
								'header'  => 'Content-type: application/x-www-form-urlencoded'.
														 'Authorization: Bearer VGVjQ1NDU1BWOlRlY0NTQ1NQXv',
								'content' => json_encode( $postVars ) //Our URL-encoded query string.
						)
				); */
				
			$options = array(
				'http' => array(
					'method'  => 'POST',
					'header'=>  "Content-Type: application/json\r\n" .
											"Accept: application/json\r\n".
											"Authorization: Bearer VGVjQ1NDU1BWOlRlY0NTQ1NQXv",
					'content' => json_encode( $postVars ),					
					)
			);
				
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
				$this->db->where("REPLACE(title,' ','') LIKE '%CSCnonregINSERTArray%'");
				$user_log = $this->Master_model->getRecords('userlogs a',array('regid'=>$regid,' DATE(date) >= '=>$yesterday));
				
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
		
		public function recover_images_bk($image_path='', $reg_no='', $regnumber='', $scannedphoto='', $idproofphoto='', $scannedsignaturephoto='', $regid='', $yesterday='')
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
			
			if($scannedphoto_res == "")
			{
				if(file_exists(FCPATH."uploads/photograph/p_".$member_no.'.jpg'))
				{
					$scannedphoto_res = base_url()."uploads/photograph/p_".$member_no.'.jpg';
				}
				else if(file_exists(FCPATH."uploads/photograph/p_".$member_no.'.jpeg'))
				{
					$scannedphoto_res = base_url()."uploads/photograph/p_".$member_no.'.jpeg';
				}
			}
			
			if($idproofphoto_res == "")
			{
				if(file_exists(FCPATH."uploads/idproof/pr_".$member_no.'.jpg'))
				{
					$idproofphoto_res = base_url()."uploads/idproof/pr_".$member_no.'.jpg';
				}
				else if(file_exists(FCPATH."uploads/idproof/pr_".$member_no.'.jpeg'))
				{
					$idproofphoto_res = base_url()."uploads/idproof/pr_".$member_no.'.jpeg';
				}
			}
			
			if($scannedsignaturephoto_res == "")
			{
				if(file_exists(FCPATH."uploads/scansignature/s_".$member_no.'.jpg'))
				{
					$scannedsignaturephoto_res = base_url()."uploads/scansignature/s_".$member_no.'.jpg';
				}
				else if(file_exists(FCPATH."uploads/scansignature/s_".$member_no.'.jpeg'))
				{
					$scannedsignaturephoto_res = base_url()."uploads/scansignature/s_".$member_no.'.jpeg';
				}
			}
			
			$data['scannedphoto'] = $scannedphoto_res;
      $data['idproofphoto'] = $idproofphoto_res;
      $data['scannedsignaturephoto'] = $scannedsignaturephoto_res;
      return $data;
		}
		
		public function test_post_data($exam_cnt=0)
		{
			echo json_encode(array('status' => 100, 'count'=>$exam_cnt));
		}		
		
		function send_mail($from_mail='', $to_email='', $subject='', $mail_data='', $view_flag='', $attachment='',$attachment2='',$attachment3='')
		{
			if($from_mail != '' && $to_email != '' && $subject != '' && $mail_data != '')
			{
				if($view_flag=='1')
				{
					echo "<br>From = ".$from_mail;
					echo "<br>To = ".$to_email;				
					echo "<br>subject = ".$subject;
					echo "<br>message = ".$mail_data; exit;
				}
				
				$this->load->library('email');
				//$config['protocol'] = 'sendmail';
				//$config['mailpath'] = '/usr/sbin/sendmail';
				$config['charset'] = 'iso-8859-1';
				$config['charset'] = 'UTF-8';
				$config['wordwrap'] = TRUE;
				$config['mailtype'] = 'html';
				$this->email->initialize($config);
				//$this->email->subject($subject." php mail");
				
				$this->email->from($from_mail);
				$this->email->to($to_email);
				$this->email->subject($subject);
				$this->email->message($mail_data);
				
				if($attachment != '')
				{
					$this->email->attach($attachment);
				}
				if($attachment2 != '')
				{
					$this->email->attach($attachment2);
				}
				if($attachment3 != '')
				{
					$this->email->attach($attachment3);
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
	