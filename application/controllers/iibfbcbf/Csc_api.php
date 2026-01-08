<?php 
/********************************************************************
* Created BY	: Sagar Matale on 2024-07-10
* Update By 	: Sagar Matale & Anil S on 18 July 2025 for adding OLD BCBF CSC Venue Code Activate/Deactivate
* Description	: This is API controller for CSC exam masters. This is used to deactivate the csc exam venue master or csc exam centre master for exam code 1039, 1040. Also this is used to insert new csc exam centre or new csc exam venue record into database table for exam code 1039, 1040.
********************************************************************/

	defined('BASEPATH') OR exit('No direct script access allowed');
	require_once APPPATH . '/libraries/REST_Controller.php';
	require_once APPPATH . '/libraries/Format.php';
	use Restserver\Libraries\REST_Controller;
	
	class Csc_api extends REST_Controller	/* extends CI_Controller / REST_Controller */
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
		}    
		
		/*********************************************************************
		CREATED BY		: Sagar Matale on 2024-07-10
		UPDATE BY 		: Sagar Matale & Anil S on 18 July 2025 for adding OLD BCBF CSC Venue Code Activate/Deactivate
		DESCRIPTION		: DEACTIVATE THE CSC EXAM VENUE MASTER
		EXAM CODES		:	1039, 1040
		ACCESS LINK 	: 
		*********************************************************************/
		public function update_csc_venue_post()
		{
      $this->Cron_api_model->token_arr(strtolower($this->class_name), strtolower($this->method_name), $this->api_key);//CHECK TOKEN VALIDATION
      
      ini_set("memory_limit", "-1");
			//header("Access-Control-Allow-Origin: *");			
			
			$log_id=0; 
			$log_title='Update CSC venue master Data'; 
			$record_count='0'; 
			$posted_data='';							
			
			try 
			{
				$message = 'Execution Started';				
				
				$log_id = $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);

        $venue_code = $action = '';

        if(isset($_POST) && count($_POST) > 0)
        {
          if(isset($_POST['venue_code'])) { $venue_code = trim($this->security->xss_clean($this->input->post('venue_code'))); }          
          if(isset($_POST['action'])) { $action = trim($this->security->xss_clean($this->input->post('action'))); }   
          
          $posted_data = json_encode($_POST);
        }
				
				if($venue_code == "")
				{
          $data['status'] = 401; 
          $data['result'] = "false";

          $data['message'] = "Venue code missing.";
          
          $message .= ' - Venue code missing - Execution Ended';					
          $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
          
          echo json_encode($data);
				}
				else if($action == "")
				{
          $data['status'] = 401; 
          $data['result'] = "false";

          $data['message'] = "Action is missing.";
          
          $message .= ' - action missing - Execution Ended';					
          $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
          
          echo json_encode($data);
				}
        else if($action != "deactivate" && $action != 'activate')
				{
          $data['status'] = 401; 
          $data['result'] = "false";

          $data['message'] = "Invalid action value.";
          
          $message .= ' - Invalid action value '.$action.' - Execution Ended';					
          $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
          
          echo json_encode($data);
				}
        else
        {   
          $tbl_name = 'iibfbcbf_exam_venue_master';
          $primary_key = 'venue_master_id';
          if($action == 'activate') { $tbl_name = 'iibfbcbf_exam_venue_master_deleted_records'; $primary_key = 'id'; }

          $venue_data = $this->master_model->getRecords($tbl_name.' vm', array('vm.venue_code' => $venue_code, 'vm.exam_codes'=>'1039,1040'), 'vm.*', array('vm.'.$primary_key=>'DESC'), 0,1);
          
          if(count($venue_data) == 0)
          {
            if($action == 'activate') 
            {
              $chk_venue_data = $this->master_model->getRecords('iibfbcbf_exam_venue_master'.' vm', array('vm.venue_code' => $venue_code, 'vm.exam_codes'=>'1039,1040'), 'vm.*', array('vm.venue_master_id'=>'DESC'), 0,1);
              if(count($chk_venue_data) > 0)
              {
                $data['status'] = 200; 
                $data['result'] = "true";

                $data['message'] = "The Venue code ".$venue_code." is already activated.";
                
                $message .= ' - Venue code '.$venue_code.' is already activated - Execution Ended';					
                $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
                
                echo json_encode($data);
              }
              else
              {
                $data['status'] = 401; 
                $data['result'] = "false";

                $data['message'] = "Record not available for the venue code ".$venue_code." to activate";
                
                $message .= ' - Record not available for the venue code '.$venue_code.' to activate - Execution Ended';					
                $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
                
                echo json_encode($data);
              }
            }
            else if($action == 'deactivate') 
            {
              $chk_venue_data = $this->master_model->getRecords('iibfbcbf_exam_venue_master_deleted_records'.' vm', array('vm.venue_code' => $venue_code, 'vm.exam_codes'=>'1039,1040'), 'vm.*', array('vm.id'=>'DESC'), 0,1);

              if(count($chk_venue_data) > 0)
              {
                $data['status'] = 200; 
                $data['result'] = "true";

                $data['message'] = "The Venue code ".$venue_code." is already deactivated.";
                
                $message .= ' - Venue code '.$venue_code.' is already deactivated - Execution Ended';					
                $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
                
                echo json_encode($data);
              }
              else
              {
            $data['status'] = 401; 
            $data['result'] = "false";

                $data['message'] = "Record not available for the venue code ".$venue_code." to deactivate";
            
                $message .= ' - Record not available for the venue code '.$venue_code.' to deactivate - Execution Ended';					
            $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
            
            echo json_encode($data);
              }
            }
          }
          else
          {
            if($action == 'deactivate')
            {            
              //INSERT THE RECORD INTO iibfbcbf_exam_venue_master_deleted_records table
              $add_data = array();
              $add_data['venue_code'] = $venue_data[0]['venue_code'];
              $add_data['exam_codes'] = $venue_data[0]['exam_codes'];
              $add_data['deleted_data'] = json_encode($venue_data);
              if($this->master_model->insertRecord('iibfbcbf_exam_venue_master_deleted_records', $add_data, true))
              {
                
                $this->update_old_bcbf_csc_venue_post($venue_code,$action); //Deactivate CSC Venue Code for OLD BCBF 1052, 1053 & 1054

                //DELETE venue code record from iibfbcbf_exam_venue_master
                $this->db->where('venue_code', $venue_code);
                $this->db->where('exam_codes', '1039,1040');
                
                if($this->db->delete('iibfbcbf_exam_venue_master'))
                {
                  $data['status'] = 200;
                  $data['result'] = "true";
                  $data['message'] = "Record successfully deactivated for venue code : ".$venue_code;
                  
                  $message .= ' - Record successfully deactivated for venue code : '.$venue_code.' - Execution Ended';					
                  $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
                  
                  echo json_encode($data);
                }
                else
                {
                  $data['status'] = 401;			
                  $data['result'] = "false";
                  $data['message'] = "Error occurred. Please try again.";
                                    
                  $message .= ' - Error occurred while deleting the record from iibfbcbf_exam_venue_master table for venue code '.$venue_code.' - Execution Ended';					
                  $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
                  echo json_encode($data);
                }
              }	
              else
              {
                $data['status'] = 401;			
                $data['result'] = "false";
                $data['message'] = "Error occurred. Please try again.";
                
                $message .= ' - Error occurred while inserting record into iibfbcbf_exam_venue_master_deleted_records table for venue code '.$venue_code.' - Execution Ended';					
                $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
                echo json_encode($data);
              }              
            }
            else if($action == 'activate')
            {       
              $check_record_exist = $this->master_model->getRecords('iibfbcbf_exam_venue_master vm', array('vm.venue_code' => $venue_code, 'vm.exam_codes'=>'1039,1040'), 'vm.*');

              if(count($check_record_exist) == 0)//check venue code is already active or not
              {
                //GET RECORD FROM iibfbcbf_exam_venue_master_deleted_records TABLE & INSERT IT INTO iibfbcbf_exam_venue_master TABLE
                $deleted_data = json_decode($venue_data[0]['deleted_data'],1);

                if(isset($deleted_data[0]['exam_date']) && $deleted_data[0]['exam_date'] != "" && isset($deleted_data[0]['centre_code']) && $deleted_data[0]['centre_code'] != "" && isset($deleted_data[0]['session_capacity']) && $deleted_data[0]['session_capacity'] != "" && isset($deleted_data[0]['venue_code']) && $deleted_data[0]['venue_code'] != "" && isset($deleted_data[0]['venue_name']) && $deleted_data[0]['venue_name'] != "" && isset($deleted_data[0]['venue_addr1']) && $deleted_data[0]['venue_addr1'] != "" && isset($deleted_data[0]['exam_period']) && $deleted_data[0]['exam_period'] != "" && isset($deleted_data[0]['exam_codes']) && $deleted_data[0]['exam_codes'] != "")
                {              
                  $add_data = array();
                  $add_data['exam_date'] = $deleted_data[0]['exam_date'];
                  $add_data['centre_code'] = $deleted_data[0]['centre_code'];
                  $add_data['session_capacity'] = $deleted_data[0]['session_capacity'];
                  $add_data['venue_code'] = $deleted_data[0]['venue_code'];
                  $add_data['venue_name'] = $deleted_data[0]['venue_name'];
                  $add_data['venue_addr1'] = $deleted_data[0]['venue_addr1'];

                  if(isset($deleted_data[0]['venue_addr2'])) { $add_data['venue_addr2'] = $deleted_data[0]['venue_addr2']; }
                  if(isset($deleted_data[0]['venue_addr3'])) { $add_data['venue_addr3'] = $deleted_data[0]['venue_addr3']; }
                  if(isset($deleted_data[0]['venue_addr4'])) { $add_data['venue_addr4'] = $deleted_data[0]['venue_addr4']; }
                  if(isset($deleted_data[0]['venue_addr5'])) { $add_data['venue_addr5'] = $deleted_data[0]['venue_addr5']; }
                  if(isset($deleted_data[0]['venue_pincode'])) { $add_data['venue_pincode'] = $deleted_data[0]['venue_pincode']; }
                  if(isset($deleted_data[0]['pwd_enabled'])) { $add_data['pwd_enabled'] = $deleted_data[0]['pwd_enabled']; }
                  if(isset($deleted_data[0]['vendor_code'])) { $add_data['vendor_code'] = $deleted_data[0]['vendor_code']; }
                  
                  $add_data['exam_period'] = $deleted_data[0]['exam_period'];
                  $add_data['exam_codes'] = $deleted_data[0]['exam_codes'];
                  
                  if($this->master_model->insertRecord('iibfbcbf_exam_venue_master', $add_data, true))
                  {

                    $this->update_old_bcbf_csc_venue_post($venue_code,$action); //Activate CSC Venue Code for OLD BCBF 1052, 1053 & 1054

                    //DELETE RECORD FROM iibfbcbf_exam_venue_master_deleted_records
                    $this->db->where('id', $venue_data[0]['id']);                  
                    if($this->db->delete('iibfbcbf_exam_venue_master_deleted_records'))
                    {
                      $data['status'] = 200;
                      $data['result'] = "true";
                      $data['message'] = "Record successfully activated for venue code : ".$venue_code;
                      
                      $message .= ' - Record successfully activated for venue code : '.$venue_code.' - Execution Ended';					
                      $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
                      
                      echo json_encode($data);
                    }
                    else
                    {
                      $data['status'] = 401;			
                      $data['result'] = "false";
                      $data['message'] = "Error occurred. Please try again.";
                      
                      $message .= ' - Error occurred while deactivating the record from iibfbcbf_exam_venue_master_deleted_records table for venue code '.$venue_code.' - Execution Ended';					
                      $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
                      echo json_encode($data);
                    }
                  }	
                  else
                  {
                    $data['status'] = 401;			
                    $data['result'] = "false";
                    $data['message'] = "Error occurred. Please try again.";
                    
                    $message .= ' - Error occurred while inserting record into iibfbcbf_exam_venue_master table for venue code '.$venue_code.' - Execution Ended';					
                    $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
                    echo json_encode($data);
                  }              
                } 
                else
                {
                  $data['status'] = 401;			
                  $data['result'] = "false";
                  $data['message'] = "Error occurred. Please contact to admin.";
                  
                  $message .= ' - Error occurred while retriving data from iibfbcbf_exam_venue_master_deleted_records table for venue code '.$venue_code.' - Execution Ended';					
                  $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
                  echo json_encode($data);
                }  
              }
              else
              {
                $data['status'] = 401;			
                $data['result'] = "false";
                $data['message'] = "The venue code ".$venue_code." is already active";                
                                
                $message .= ' - The venue code '.$venue_code.' is already active - Execution Ended';					
                $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
                echo json_encode($data);
              }
            }
          }
        }
			}
			catch (Exception $e)
			{
				$data['status'] = 401;			
				$data['result'] = "false";
				$data['message'] = "Access denied : ".$e->getMessage();
				
				$log_id = $this->Cron_api_model->activity_log_common($log_id, $log_title, 'Access denied', $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);				
				echo json_encode($data);
			}			
		}/**************** END : DEACTIVATE THE CSC EXAM VENUE MASTER **************/


    /*START: Function To Activate / Deactivate CSC Venue Code for OLD BCBF 1052, 1053 & 1054 ADDED BY ANIL S ON 18 July 2025*/
    public function update_old_bcbf_csc_venue_post($venue_code='',$action='')
    {
        if($venue_code != "" && $action != "")
        {   
          $log_id=0; 
          $log_title='Update OLD BCBF CSC venue master Data'; 
          $record_count='0'; 
          $posted_data='';

          $tbl_name = 'venue_master';
          $primary_key = 'venue_master_id';
          if($action == 'activate') { $tbl_name = 'venue_master_deleted_records'; $primary_key = 'id'; }

          $venue_data = $this->master_model->getRecords($tbl_name.' vm', array('vm.venue_code' => $venue_code, 'vm.exam_date'=>'0000-00-00', 'vm.exam_period'=>'998', 'vm.vendor'=>'csc'), 'vm.*', array('vm.'.$primary_key=>'DESC'), 0,1);

          if(count($venue_data) == 0)
          {
            if($action == 'activate') 
            {
              $chk_venue_data = $this->master_model->getRecords('venue_master'.' vm', array('vm.venue_code' => $venue_code, 'vm.exam_date'=>'0000-00-00', 'vm.exam_period'=>'998', 'vm.vendor'=>'csc'), 'vm.*', array('vm.venue_master_id'=>'DESC'), 0,1);
              if(count($chk_venue_data) > 0)
              {
                $data['status'] = 200; 
                $data['result'] = "true";

                $data['message'] = "The OLD BCBF CSC Venue code ".$venue_code." is already activated.";
                
                $message .= ' - OLD BCBF CSC Venue code '.$venue_code.' is already activated - Execution Ended';         
                $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
                
                //echo json_encode($data);
              }
              else
              {
                $data['status'] = 401; 
                $data['result'] = "false";

                $data['message'] = "OLD BCBF CSC Record not available for the venue code ".$venue_code." to activate";
                
                $message .= ' - OLD BCBF CSC Record not available for the venue code '.$venue_code.' to activate - Execution Ended';         
                $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
                
                //echo json_encode($data);
              }
            }
            else if($action == 'deactivate') 
            {
              $chk_venue_data = $this->master_model->getRecords('venue_master_deleted_records'.' vm', array('vm.venue_code' => $venue_code, 'vm.exam_date'=>'0000-00-00', 'vm.exam_period'=>'998', 'vm.vendor'=>'csc'), 'vm.*', array('vm.id'=>'DESC'), 0,1);

              if(count($chk_venue_data) > 0)
              {
                $data['status'] = 200; 
                $data['result'] = "true";

                $data['message'] = "The OLD BCBF CSC Venue code ".$venue_code." is already deactivated.";
                
                $message .= ' - OLD BCBF CSC Venue code '.$venue_code.' is already deactivated - Execution Ended';         
                $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
                
                //echo json_encode($data);
              }
              else
              {
                  $data['status'] = 401; 
                  $data['result'] = "false";

                  $data['message'] = "OLD BCBF CSC Record not available for the venue code ".$venue_code." to deactivate";
                  
                  $message .= ' - OLD BCBF CSC Record not available for the venue code '.$venue_code.' to deactivate - Execution Ended';         
                  $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
                  
                  //echo json_encode($data);
              }
            }
          }
          else if(isset($venue_data) && count($venue_data) > 0 ){

                $posted_dt["venue_code"] = $venue_code;
                $posted_dt["action"] = $action;
                $posted_data = json_encode($posted_dt);

                if($action == 'deactivate')
                {            
                  //INSERT THE RECORD INTO venue_master_deleted_records table
                  $add_data = array();
                  $add_data['venue_code'] = $venue_data[0]['venue_code'];
                  $add_data['exam_date'] = $venue_data[0]['exam_date'];
                  $add_data['exam_period'] = $venue_data[0]['exam_period'];
                  $add_data['vendor'] = $venue_data[0]['vendor'];
                  $add_data['deleted_data'] = json_encode($venue_data);
                  if($this->master_model->insertRecord('venue_master_deleted_records', $add_data, true))
                  {
                    if($venue_data[0]['venue_master_id'] > 0){
                        //DELETE venue code record from venue_master
                        $this->db->where('venue_code', $venue_code);
                        $this->db->where('exam_date', '0000-00-00');
                        $this->db->where('exam_period', '998');
                        $this->db->where('vendor', 'csc');
                        $this->db->where('venue_master_id', $venue_data[0]['venue_master_id']);

                        if($this->db->delete('venue_master'))
                        {
                          $data['status'] = 200;
                          $data['result'] = "true";
                          $data['message'] = "OLD BCBF CSC Record successfully deactivated for venue code : ".$venue_code;
                          
                          $message .= ' - OLD BCBF CSC Record successfully deactivated for venue code : '.$venue_code.' - Execution Ended';   

                          $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);                      
                          //echo json_encode($data);
                        }
                        else
                        {
                          $data['status'] = 401;      
                          $data['result'] = "false";
                          $data['message'] = "OLD BCBF CSC Error occurred. Please try again.";
                                            
                          $message .= ' - OLD BCBF CSC Error occurred while deleting the record from venue_master table for venue code '.$venue_code.' - Execution Ended';         
                          $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
                          //echo json_encode($data);
                        }
                    } 
                  } 
                  else
                  {
                    $data['status'] = 401;      
                    $data['result'] = "false";
                    $data['message'] = "OLD BCBF CSC Error occurred. Please try again.";
                    
                    $message .= ' - OLD BCBF CSC Error occurred while inserting record into venue_master_deleted_records table for venue code '.$venue_code.' - Execution Ended';          
                    $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
                    //echo json_encode($data);
                  }              
                }
                else if($action == 'activate')
                {       
                  $check_record_exist = $this->master_model->getRecords('venue_master vm', array('vm.venue_code' => $venue_code, 'vm.exam_date'=>'0000-00-00', 'vm.exam_period'=>'998', 'vm.vendor'=>'csc'), 'vm.*');

                  if(count($check_record_exist) == 0)//check venue code is already active or not
                  {
                    //GET RECORD FROM venue_master_deleted_records TABLE & INSERT IT INTO venue_master TABLE
                    $deleted_data = json_decode($venue_data[0]['deleted_data'],1);

                    if(isset($deleted_data[0]['exam_date']) && $deleted_data[0]['exam_date'] != "" && isset($deleted_data[0]['center_code']) && $deleted_data[0]['center_code'] != "" && isset($deleted_data[0]['session_capacity']) && $deleted_data[0]['session_capacity'] != "" && isset($deleted_data[0]['venue_code']) && $deleted_data[0]['venue_code'] != "" && isset($deleted_data[0]['venue_name']) && $deleted_data[0]['venue_name'] != "" && isset($deleted_data[0]['venue_addr1']) && $deleted_data[0]['venue_addr1'] != "" && isset($deleted_data[0]['exam_period']) && $deleted_data[0]['exam_period'] != "" && isset($deleted_data[0]['vendor']) && $deleted_data[0]['vendor'] != "")
                    {              
                      $add_data = array();
                      $add_data['exam_date'] = $deleted_data[0]['exam_date'];
                      $add_data['center_code'] = $deleted_data[0]['center_code'];
                      $add_data['session_capacity'] = $deleted_data[0]['session_capacity'];
                      $add_data['venue_code'] = $deleted_data[0]['venue_code'];
                      $add_data['venue_name'] = $deleted_data[0]['venue_name'];
                      $add_data['venue_addr1'] = $deleted_data[0]['venue_addr1'];

                      if(isset($deleted_data[0]['venue_addr2'])) { $add_data['venue_addr2'] = $deleted_data[0]['venue_addr2']; }
                      if(isset($deleted_data[0]['venue_addr3'])) { $add_data['venue_addr3'] = $deleted_data[0]['venue_addr3']; }
                      if(isset($deleted_data[0]['venue_addr4'])) { $add_data['venue_addr4'] = $deleted_data[0]['venue_addr4']; }
                      if(isset($deleted_data[0]['venue_addr5'])) { $add_data['venue_addr5'] = $deleted_data[0]['venue_addr5']; }
                      if(isset($deleted_data[0]['venue_pincode'])) { $add_data['venue_pincode'] = $deleted_data[0]['venue_pincode']; }
                      if(isset($deleted_data[0]['pwd_enabled'])) { $add_data['pwd_enabled'] = $deleted_data[0]['pwd_enabled']; }
                      if(isset($deleted_data[0]['vendor_code'])) { $add_data['vendor_code'] = $deleted_data[0]['vendor_code']; }

                      $add_data['exam_period'] = $deleted_data[0]['exam_period'];  

                      if(isset($deleted_data[0]['exam_code1'])) { $add_data['exam_code1'] = $deleted_data[0]['exam_code1']; }
                      if(isset($deleted_data[0]['exam_code2'])) { $add_data['exam_code2'] = $deleted_data[0]['exam_code2']; }
                      if(isset($deleted_data[0]['venue_flag'])) { $add_data['venue_flag'] = $deleted_data[0]['venue_flag']; }
                      if(isset($deleted_data[0]['institute_code'])) { $add_data['institute_code'] = $deleted_data[0]['institute_code']; }

                      $add_data['vendor'] = $deleted_data[0]['vendor']; 

                      if(isset($deleted_data[0]['csc_id'])) { $add_data['csc_id'] = $deleted_data[0]['csc_id']; } 
                      
                      if($this->master_model->insertRecord('venue_master', $add_data, true))
                      {
                        //DELETE RECORD FROM venue_master_deleted_records
                        $this->db->where('id', $venue_data[0]['id']);                  
                        if($this->db->delete('venue_master_deleted_records'))
                        {
                          $data['status'] = 200;
                          $data['result'] = "true";
                          $data['message'] = "OLD BCBF CSC Record successfully activated for venue code : ".$venue_code;
                          
                          $message .= ' - OLD BCBF CSC Record successfully activated for venue code : '.$venue_code.' - Execution Ended';          
                          $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
                          
                          //echo json_encode($data);
                        }
                        else
                        {
                          $data['status'] = 401;      
                          $data['result'] = "false";
                          $data['message'] = "OLD BCBF CSC Error occurred. Please try again.";
                          
                          $message .= ' - OLD BCBF CSC Error occurred while deactivating the record from venue_master_deleted_records table for venue code '.$venue_code.' - Execution Ended';         
                          $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
                          //echo json_encode($data);
                        }
                      } 
                      else
                      {
                        $data['status'] = 401;      
                        $data['result'] = "false";
                        $data['message'] = "OLD BCBF CSC Error occurred. Please try again.";
                        
                        $message .= ' - OLD BCBF CSC Error occurred while inserting record into venue_master table for venue code '.$venue_code.' - Execution Ended';          
                        $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
                        //echo json_encode($data);
                      }              
                    } 
                    else
                    {
                      $data['status'] = 401;      
                      $data['result'] = "false";
                      $data['message'] = "OLD BCBF CSC Error occurred. Please contact to admin.";
                      
                      $message .= ' - OLD BCBF CSC Error occurred while retriving data from venue_master_deleted_records table for venue code '.$venue_code.' - Execution Ended';          
                      $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
                      //echo json_encode($data);
                    }  
                  }
                  else
                  {
                    $data['status'] = 401;      
                    $data['result'] = "false";
                    $data['message'] = "OLD BCBF CSC The venue code ".$venue_code." is already active";                
                                    
                    $message .= ' - OLD BCBF CSC The venue code '.$venue_code.' is already active - Execution Ended';          
                    $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
                    //echo json_encode($data);
                  }
                }
            } 
        }
    }
    /*END: Function To Activate / Deactivate CSC Venue Code for OLD BCBF 1052, 1053 & 1054 ADDED BY ANIL S ON 18 July 2025*/

	}