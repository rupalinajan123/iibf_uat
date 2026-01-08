<?php 
	/********************************************************************
		* Created BY	: Sagar Matale On 21-10-2021
		* Update By 	: Anil S On 29-10-2025
		* Description	: This is the model which is used for cron api.
	********************************************************************/
	
	if( !defined('BASEPATH')) exit('No direct script access alloed');
	
	class Cron_api_model extends CI_Model
	{
		/*********************************************************************
		CREATED BY		: Sagar Matale On 21-10-2021
		UPDATE BY 		: Sagar Matale On 30-11-2021, Anil S On 29-10-2025
		DESCRIPTION		: STATIC TOKEN ARRAY AS PER METHODS USED IN controllers/api_web/Cron_api.php
		*********************************************************************/
		function token_arr($class_name='', $method_name='', $api_key='')
		{
			$request_ip = $this->get_ip_address();
			
			/* echo '<br>class_name : '.$class_name;  
			echo '<br>method_name : '.$method_name; 
			echo '<br>api_key : '.$api_key; 
			echo '<br>request_ip : '.$request_ip; */
			
			$response_result = 'true';
      $response_message = 'Authenticated';
			
			$authorization_arr['member'] = array('token'=>array('api_member_'.date('Ymd')), 'allowed_ips'=>array('115.124.115.71', '182.73.101.70', '111.125.242.49')); //FOR MEMBER REGISTRATION DATA
			
      $authorization_arr['update_csc_venue'] = array('token'=>array(/* 'api_csc_venue_'.date('Ymd') */), 'allowed_ips'=>array('115.124.115.71', '182.73.101.70', '111.125.242.49', '10.11.38.109', '54.86.50.139', '14.97.62.70', '115.124.115.75', '13.201.107.251')); //FOR ACTIVATE - DEACTIVATE THE CSC EXAM VENUE MASTER		

      	$authorization_arr['training_certificate_bc'] = array('token'=>array(/* 'api_csc_venue_'.date('Ymd') */), 'allowed_ips'=>array('168.220.234.90', '168.220.234.73', '115.124.115.75')); //FOR verify the certificates provided by IIBF for the Training and Certification of BCs.		

      	$authorization_arr['certificate_verification_data'] = array('token'=>array(/* 'api_csc_venue_'.date('Ymd') */), 'allowed_ips'=>array('115.124.115.71', '182.73.101.70', '111.125.242.49', '10.11.38.109', '10.11.38.110', '54.86.50.139', '14.97.62.70', '115.124.115.75', '13.201.107.251')); //FOR verify the certificates provided by IIBF for the Training and Certification of BCs.		
			
			if($class_name == 'cron_api' && array_key_exists($method_name, $authorization_arr))//CHECK METHOD EXIST IN authorization_arr
			{
				$valid_token_arr = array();
				if(isset($authorization_arr[$method_name]['token']))//CHECK TOKEN ARRAY EXIST IN authorization_arr
				{
					$valid_token_arr = $authorization_arr[$method_name]['token'];
										
					if(in_array($api_key, $valid_token_arr))//CHECK TOKEN VALIDATION
					{
						$valid_ip_arr = array();
						if(isset($authorization_arr[$method_name]['allowed_ips']))//CHECK ALLOWED IP ARRAY EXIST IN authorization_arr
						{
							$valid_ip_arr = $authorization_arr[$method_name]['allowed_ips'];
							
							if(in_array($request_ip, $valid_ip_arr))//CHECK ALLOWED IP VALIDATION
							{
								$response['result'] = 'true';
								$response['message'] = 'Authenticated';
							}
							else//ALLOWED IP VALIDATION FAILED
							{
								$response['message'] = 'Authorization error : Invalid IP Request';
							}
						}
						else//ALLOWED IP ARRAY NOT EXIST IN authorization_arr
						{
							$response['message'] = 'Authorization error : Invalid IP Request';
						}						
					}
					else//TOKEN VALIDATION FAILED
					{
						$response['message'] = 'Authorization error : Invalid Token Request';
					}
				}
				else// TOKEN ARRAY NOT EXIST IN authorization_arr
				{
					$response['message'] = 'Authorization error : Invalid Token Request';
				}				
			}
			else if($class_name == 'csc_api' && array_key_exists($method_name, $authorization_arr))//CHECK METHOD EXIST IN authorization_arr
			{
        //START : CHECK TOKEN VALIDATION
				if(isset($authorization_arr[$method_name]['token']) && count($authorization_arr[$method_name]['token']) > 0)//CHECK TOKEN ARRAY EXIST IN authorization_arr
				{
					$valid_token_arr = $authorization_arr[$method_name]['token'];										
					if(!in_array($api_key, $valid_token_arr))
					{
            $response_result = 'false';
            $response_message = 'Authorization error : Invalid Token Request (Api Key : '.$api_key.')';
					}
				}//END : CHECK TOKEN VALIDATION		
        
        //START : CHECK ALLOWED IP'S VALIDATION
				if(isset($authorization_arr[$method_name]['allowed_ips']) && count($authorization_arr[$method_name]['allowed_ips']) > 0)//CHECK ALLOWED IP ARRAY EXIST IN authorization_arr
				{
					$valid_ip_arr = $authorization_arr[$method_name]['allowed_ips'];								
					if(!in_array($request_ip, $valid_ip_arr))//CHECK ALLOWED IP VALIDATION
					{
            $response_result = 'false';
            $response_message = 'Authorization error : Invalid IP Request (Your IP : '.$request_ip.')';
					}
				}//END : CHECK ALLOWED IP'S VALIDATION
			}
			else if($class_name == 'certificate_verification_for_ibabcregistry_api')//CHECK METHOD EXIST IN authorization_arr
			{
				//  && array_key_exists($method_name, $authorization_arr)
				
        		/*//START : CHECK TOKEN VALIDATION
				if(isset($authorization_arr[$method_name]['token']) && count($authorization_arr[$method_name]['token']) > 0)//CHECK TOKEN ARRAY EXIST IN authorization_arr
				{
					$valid_token_arr = $authorization_arr[$method_name]['token'];										
					if(!in_array($api_key, $valid_token_arr))
					{
            			$response_result = 'false';
            			$response_message = 'Authorization error : Invalid Token Request (Api Key : '.$api_key.')';
					}
				}//END : CHECK TOKEN VALIDATION		
        
        		//START : CHECK ALLOWED IP'S VALIDATION
				if(isset($authorization_arr[$method_name]['allowed_ips']) && count($authorization_arr[$method_name]['allowed_ips']) > 0)//CHECK ALLOWED IP ARRAY EXIST IN authorization_arr
				{
					$valid_ip_arr = $authorization_arr[$method_name]['allowed_ips'];								
					if(!in_array($request_ip, $valid_ip_arr))//CHECK ALLOWED IP VALIDATION
					{
            			$response_result = 'false';
            			$response_message = 'Authorization error : Invalid IP Request (Your IP : '.$request_ip.')';
					}
				}//END : CHECK ALLOWED IP'S VALIDATION*/
			}
			else if($class_name == 'certificate_verification_iibf_data_api')//CHECK METHOD EXIST IN authorization_arr
			{

			}
			else// METHOD NOT EXIST IN authorization_arr
			{
        		$response_result = 'false';
        		$response_message = 'Authorization error : Invalid Method ('.$class_name.' & '.$method_name.')';
			}
			
			if($response_result == 'true')
			{
        $message = $response_message;
				//$this->activity_log_common(0, 'Authenticated ', $message, '0', '', $class_name, $method_name, $api_key);
      }
			else
			{
				$data['status'] = 401;
				$data['result'] = $response_result;
				$data['message'] = $response_message;
				
				$message = $response_message;
				$this->activity_log_common(0, 'Authorization error ', $message, '0', '', $class_name, $method_name, $api_key);
				
				echo json_encode($data);
				exit; 
			}
		}
		
		/*********************************************************************
		CREATED BY		: Sagar Matale On 22-10-2021
		UPDATE BY 		: 
		DESCRIPTION		: STORE COMMON ACTIVITY LOG
		*********************************************************************/
		public function activity_log_common($log_id=0, $log_title='', $message='', $record_count='', $posted_data='', $class_name='', $method_name='', $api_key='')
		{			
			$add_data["class_name"] = $class_name;
			$add_data["method_name"] = $method_name;
			$add_data["ip_address"] = $this->get_ip_address();			
			$add_data["token"] = $api_key;			
			$add_data["log_title"] = $log_title; 			
			$add_data["message"] = $message; 			
			$add_data["record_count"] = $record_count; 			
			$add_data["posted_data"] = $posted_data;
			
			if($log_id == '0')
			{
				$add_data["start_time"] = date("Y-m-d H:i:s");
				$add_data["end_time"] = date("Y-m-d H:i:s");
				return $this->master_model->insertRecord('api_log', $add_data, true);				
			}
			else
			{
				$add_data["end_time"] = date("Y-m-d H:i:s");				
				$this->master_model->updateRecord('api_log', $add_data, array('log_id' => $log_id));
			} 
		}
		
		/*********************************************************************
		CREATED BY		: Sagar Matale On 22-10-2021
		UPDATE BY 		: 
		DESCRIPTION		: GET MEMBER DATA WHICH IS USED IN FILE Cron_api.php/member_get
		*********************************************************************/
		public function get_member_data($count_flag=1, $member_number=0, $start_limit=0, $max_limit=500) //count_flag : 1=>total database record count, 0=>result member data
		{
			$excode = array('526','527','991');
			$this->db->where_not_in('a.excode', $excode);			
			
			if($count_flag == 1)
			{
				$member_count = $this->Master_model->getRecordCount('member_registration a',array('a.isactive'=>'1','a.isdeleted'=>'0', 'a.is_renewal' => '0'));
				return $member_count;
			}
			else
			{
				if($member_number != "0" && $member_number != "") { $this->db->where('regnumber', $member_number); }
				else { $this->db->limit($max_limit, $start_limit); }
				
				/* $current_date = date("Y-m-d");
				$yesterday = date('Y-m-d', strtotime("- 1 day", strtotime($current_date)));
				$this->db->where('DATE(a.createdon)', $yesterday); */				
			
				$member_data = $this->Master_model->getRecords('member_registration a',array('a.isactive'=>'1','a.isdeleted'=>'0', 'a.is_renewal' => '0'));
				//echo $this->db->last_query();
				return $member_data;
			}			
		}
		
		/*********************************************************************
		CREATED BY		: Sagar Matale On 21-10-2021
		UPDATE BY 		: 
		DESCRIPTION		: GET CURRENT IP ADDRESS
		*********************************************************************/
		function get_ip_address() // GET IP ADDRESS
		{
			$ipaddress = '';
			if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
			else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
			else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
			else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
			else if(getenv('HTTP_FORWARDED'))
			$ipaddress = getenv('HTTP_FORWARDED');
			else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
			else
			$ipaddress = 'UNKNOWN';
			return $ipaddress;
		}
		
	}
?>