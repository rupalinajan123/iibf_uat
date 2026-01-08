<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');
	require_once APPPATH . '/libraries/REST_Controller.php';
	
	class Testing extends REST_Controller	/* extends CI_Controller */
	{
		public function __construct() 
		{
			parent::__construct();			
		}
		
		/* -------------- CONTACT FORM --------------- */
		public function demo_post() 
		{
			header("Access-Control-Allow-Origin: *");
			
			try 
			{				
				$data=array();
				
				$ajax_full_date = trim($this->security->xss_clean($this->post('ajax_full_date')));  
				$ajax_full_time = trim($this->security->xss_clean($this->post('ajax_full_time')));  
				$ajax_full_name = trim($this->security->xss_clean($this->post('ajax_full_name')));  
				$ajax_address = trim($this->security->xss_clean($this->post('ajax_address')));
				$ajax_pin_code = trim($this->security->xss_clean($this->post('ajax_pin_code')));
				$ajax_mobile_no = trim($this->security->xss_clean($this->post('ajax_mobile_no')));
				$ajax_occupation = trim($this->security->xss_clean($this->post('ajax_occupation')));
				$ajax_about_whom = trim($this->security->xss_clean($this->post('ajax_about_whom')));
				$ajax_have_patrika = trim($this->security->xss_clean($this->post('ajax_have_patrika')));
				$ajax_reference = trim($this->security->xss_clean($this->post('ajax_reference')));
				
				if($ajax_full_date == '' || $ajax_full_time == '' || $ajax_full_name == '' || $ajax_address == '' || $ajax_pin_code == '' || $ajax_mobile_no == '' || $ajax_occupation == '' || $ajax_about_whom == '' || $ajax_have_patrika == '' || $ajax_reference == '')
				{		
					$error_data = array();
					if($ajax_full_date == "") { $error_data['ajax_full_date'] = 'Please enter the date'; }
					if($ajax_full_time == "") { $error_data['ajax_full_time'] = 'Please enter the time'; }
					if($ajax_full_name == "") { $error_data['ajax_full_name'] = 'Please enter the name'; }
					if($ajax_address == "") { $error_data['ajax_address'] = 'Please enter the address'; }			
					
					http_response_code(401);					
					
					echo json_encode(array(
					"response"=>"error",
					"message"=>"validation errors",	
					"error_data"=>$error_data,	
					"status"=>http_response_code(401)
					));
				}
				else
				{
					http_response_code(200);				
          echo json_encode(array(
          "response"=>"success",
          "message" => "success",
          "status"=>http_response_code(200)
          ));			
				}					
			}
			catch (Exception $e)
			{
				http_response_code(401);				
				echo json_encode(array(
				"message" => "Access denied.",
				"error" => $e->getMessage(),
				"status"=>http_response_code(401)
				));
			}			
		}
		
		public function test_post() 
		{
			header("Access-Control-Allow-Origin: *");
			
			try 
			{				
				$data = $this->post('request_data');
				
				if($data == '')
				{		
					http_response_code(401);					
					
					echo json_encode(array(
					"response"=>"error",
					"message"=>"validation errors",	
					"error_data"=>"no data posted",	
					"status"=>http_response_code(401)
					));
				}
				else
				{
					http_response_code(200);				
          echo json_encode(array(
          "response"=>"success",
          "message" => $data,
          "headers" => getallheaders(),
          "status"=>http_response_code(200)
          ));			
				}					
			}
			catch (Exception $e)
			{
				http_response_code(401);				
				echo json_encode(array(
				"message" => "Access denied.",
				"error" => $e->getMessage(),
				"status"=>http_response_code(401)
				));
			}			
		}
		
	}