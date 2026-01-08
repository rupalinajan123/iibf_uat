<?php 
/********************************************************************
* Created BY  : Anil S on 2025-08-12
* Update By   : 
* Description : This is the API controller for Certificate Verification for ibabcregistry. It is used to verify the certificates provided by IIBF for the Training and Certification of BCs.
********************************************************************/

  defined('BASEPATH') OR exit('No direct script access allowed');
  require_once APPPATH . '/libraries/REST_Controller.php';
  require_once APPPATH . '/libraries/Format.php';
  use Restserver\Libraries\REST_Controller;
  
  class Certificate_verification_for_ibabcregistry_api extends REST_Controller /* extends CI_Controller / REST_Controller */
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
    CREATED BY    : Anil S on 2025-08-12
    UPDATE BY     : 
    DESCRIPTION   : Verify the certificates provided by IIBF for the Training and Certification of BCs.
    EXAM CODES    : 
    ACCESS LINK   : 
    *********************************************************************/

    /**************** START : VERIFY THE CERTIFICATES PROVIDED BY IIBF **************/
    public function training_certificate_bc_post()
    {
      $this->Cron_api_model->token_arr(strtolower($this->class_name), strtolower($this->method_name), $this->api_key);//CHECK TOKEN VALIDATION
      
      ini_set("memory_limit", "-1");
      //header("Access-Control-Allow-Origin: *");     
      
      $log_id=0; 
      $log_title='Verify Certificate Data'; 
      $record_count='0'; 
      $posted_data='';              
      
      try 
      {
        $message = 'Execution Started';       
        
        $log_id = $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);

        $registration_no = $certificate_no = $certificate_date = '';

        if(isset($_POST) && count($_POST) > 0)
        {
          if(isset($_POST['registration_no'])) { $registration_no = trim($this->security->xss_clean($this->input->post('registration_no'))); }          
          if(isset($_POST['certificate_no'])) { $certificate_no = trim($this->security->xss_clean($this->input->post('certificate_no'))); }          
          if(isset($_POST['certificate_date'])) { $certificate_date = trim($this->security->xss_clean($this->input->post('certificate_date'))); }  
          
          $posted_data = json_encode($_POST);
        }
        
        if($registration_no == "")
        {
          $data['status'] = 401; 
          $data['result'] = "false";
          $data['message'] = "Registration number missing.";
          
          $message .= ' - Registration number missing - Execution Ended';          
          $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);          
          echo json_encode($data);
        }
        /*else if($certificate_no == "")
        {
          $data['status'] = 401; 
          $data['result'] = "false";
          $data['message'] = "Certificate number missing.";
          
          $message .= ' - Certificate number missing - Execution Ended';          
          $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);          
          echo json_encode($data);
        }
        else if($certificate_date == "")
        {
          $data['status'] = 401; 
          $data['result'] = "false";
          $data['message'] = "Certificate date missing.";
          
          $message .= ' - Certificate date missing - Execution Ended';          
          $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);          
          echo json_encode($data);
        }*/  
        else
        {   
          $tbl_name = 'iibfbcbf_batch_candidates';
          $primary_key = 'candidate_id'; 

          //if($registration_no != "" && $certificate_no != "" && $certificate_date != "" && strlen($registration_no) <= 50 && strlen($certificate_no) <= 50 && strlen($certificate_date) <= 50)
          if($registration_no != "" && strlen($registration_no) <= 50)
          { 
 

          //if($registration_no != "" && $certificate_no != "" && $certificate_date != "")
          if($registration_no != "")
          {
            //$iibf_certificate_data = $this->master_model->getRecords('iibf_certificate_data_for_ibabcregistry_portal', array('registration_number' => $registration_no, 'certificate_no' => $certificate_no, 'certificate_date' => $certificate_date), 'registration_number,name_of_the_bc,date_of_passing,certificate_name,certificate_no,certificate_date', array('id'=>'DESC'), 0,1);
            $iibf_certificate_data = $this->master_model->getRecords('iibf_certificate_data_for_ibabcregistry_portal', array('registration_number' => $registration_no), 'exam_code,registration_number,name_of_the_bc,date_of_passing,certificate_name,certificate_no,certificate_date', array('id'=>'DESC'), 0,1);
          }

          if(count($iibf_certificate_data) == 0)
          {
            $data['status'] = 401; 
            $data['result'] = "false";
            $data['message'] = "Record not available in iibf certificate data for the registration number ".$registration_no;
            
            $message .= ' - Record not available in iibf certificate data for the registration number '.$registration_no.' - Execution Ended';         
            $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key); 
            echo json_encode($data); 
          }
          else
          {
            $iibf_certificate_data = $this->find_latest_certificate_data($registration_no);
 
            $registration_data = $this->master_model->getRecords('iibfbcbf_batch_candidates bc', array('bc.regnumber' => $registration_no, 'bc.is_deleted'=>'0'), 'CONCAT(bc.salutation," ", bc.first_name, " ", bc.middle_name," ", bc.last_name) AS DispCandidateName,bc.training_id,bc.batch_id,bc.dob,bc.candidate_id', array('bc.candidate_id'=>'DESC'), 0,1);

            if(count($registration_data) == 0){
              $registration_data = $this->master_model->getRecords('member_registration mr', array('mr.regnumber' => $registration_no, 'mr.isdeleted'=>'0', 'mr.isactive' => '1'), 'CONCAT(mr.namesub," ", mr.firstname, " ", mr.middlename," ", mr.lastname) AS DispCandidateName,mr.dateofbirth AS dob', array('mr.regid'=>'DESC'), 0,1);
            }
            
            if(count($registration_data) == 0)
            {
              $data['status'] = 401; 
              $data['result'] = "false";
              $data['message'] = "Record not available for the registration number ".$registration_no;
              
              $message .= ' - Record not available for the registration number '.$registration_no.' - Execution Ended';         
              $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key); 
              echo json_encode($data); 
            }
            else
            {
              if(count($registration_data) > 0)
              {
                $data['status'] = 200;
                $data['result'] = "true";

                /*For Training:
                1. Name of the Training Institute
                2. Type of Training (Basic/ Advanced)
                3. Training Mode (Online/Offline)
                4. From Date
                5. To Date
                6. Name of the BC

                For Certification:
                1. Date of Passing (Certificate Date)
                2. Type of Certification  (Basic/ Advanced)
                */

                if(isset($registration_data[0]["training_id"]) && $registration_data[0]["training_id"] != ""){

                    $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
                    $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.agency_id'=>$agency_id, 'cm.status' => '1', 'cm.is_deleted'=>'0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm.centre_name, cm.centre_username, cm1.city_name');

                    $this->db->join('iibfbcbf_centre_master cm', 'cm.centre_id = acb.centre_id', 'LEFT');
                    $training_details = $this->master_model->getRecords('iibfbcbf_agency_centre_batch acb', array('acb.batch_id' => $registration_data[0]["batch_id"], 'acb.is_deleted'=>'0'), 'acb.*,cm.centre_name', array('acb.batch_id'=>'DESC'), 0,1);

                    if(count($training_details) > 0){

                        $training_certificate_details['training_institute_name'] = $training_details[0]["centre_name"];   

                        $training_certificate_details['training_type'] = ($training_details[0]["batch_type"] == "1" ? "Basic" : $training_details[0]["batch_type"] == "2" ? "Advance" : "");              

                        $training_certificate_details['training_mode'] = ($training_details[0]["batch_online_offline_flag"] == "1" ? "Offline Batch" : $training_details[0]["batch_online_offline_flag"] == "2" ? "Online Batch" : ""); 

                        $training_certificate_details['training_from_date'] = $training_details[0]["batch_start_date"];              
                        $training_certificate_details['training_to_date'] = $training_details[0]["batch_end_date"];    
                    } 
                
                    if($training_details[0]["batch_type"] == "1"){ // Basic
                      //$certificate_type = 'CERTIFICATE EXAMINATION FOR BUSINESS CORRESPONDENTS / FACILITATORS (BASIC)';
                    }
                    else if($training_details[0]["batch_type"] == "2"){ // Advance
                      //$certificate_type = 'CERTIFICATE EXAMINATION FOR BUSINESS CORRESPONDENTS / FACILITATORS (ADVANCED)';
                    }
                }else{
                  //$certificate_type = $iibf_certificate_data["certificate_name"];
                } 

                //$certificate_type = $iibf_certificate_data[0]["certificate_name"]; 

                //$training_certificate_details['training_bc_name'] = $registration_data[0]["DispCandidateName"]; 
                $training_certificate_details['training_bc_name'] = $iibf_certificate_data["name_of_the_bc"]; 

                $training_certificate_details['certificate_date'] = $iibf_certificate_data["certificate_date"];  


                $training_certificate_details['certificate_type'] = $iibf_certificate_data[0]["certificate_type"]; 
                $training_certificate_details['certificate_name'] = $iibf_certificate_data["certificate_name"]; 

                //$training_certificate_details['exam_code'] = $iibf_certificate_data["exam_code"]; 

                $training_certificate_details['date_of_birth'] = $registration_data[0]["dob"]; 
                

                $data['training_certificate_details'] = $training_certificate_details;  

                $data['message'] = "Record successfully verified for registration number : ".$registration_no;
                
                $message .= ' - Record successfully verified for registration number : '.$registration_no.' - Execution Ended';          
                $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
                
                echo json_encode($data);
              }
              else
              {
                $data['status'] = 401; 
                $data['result'] = "false";
                $data['message'] = "Record not available for the registration number ".$registration_no;
                
                $message .= ' - Record not available for the registration number '.$registration_no.' - Execution Ended';         
                $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key); 
                echo json_encode($data);
              }
               
            }
          }

          }else{
              $data['status'] = 401; 
              $data['result'] = "false";
              $data['message'] = "Invalid input. Each field must be non-empty and not exceed 50 characters.";
              
              $message .= ' - Invalid input. Each field must be non-empty and not exceed 50 characters. - Execution Ended';         
              $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key); 
              echo json_encode($data); 
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
    }
    /**************** END : VERIFY THE CERTIFICATES PROVIDED BY IIBF **************/
 

    /**************** START : FIND THE LATEST ADVANCED CERTIFICATES PROVIDED BY IIBF **************/
    public function find_latest_certificate_data($registration_no = "")
    {
        if ($registration_no != "") {

            // Define exam code groups
            $basic_exam_code_arr = array(994, 997, 1038, 1040, 1042, 1047, 1053, 1054, 1056); 
            $advanced_exam_code_arr = array(55, 98, 101, 991, 996, 1015, 1037, 1039, 1041, 1046, 1052, 1055, 1057);

            //Old Exam Code
            $old_exam_code_arr = array(55, 101, 991, 994, 996, 997, 1015);

            // Fetch all certificate records for the given registration number
            $res = $this->master_model->getRecords(
                'iibf_certificate_data_for_ibabcregistry_portal',
                array('registration_number' => $registration_no), 'exam_code,registration_number,name_of_the_bc,date_of_passing,certificate_name,certificate_no,certificate_date'
            );

            if (empty($res)) {
                return null;
            }

            $latest_advanced = null;
            $latest_basic = null;

            foreach ($res as $row) {

                //$row["certificate_type"] = "Basic";

                $exam_code = (int)$row['exam_code'];
                $cert_date = strtotime($row['certificate_date']);

                if (!$cert_date) continue; // skip invalid dates
 
                if (in_array($exam_code, $advanced_exam_code_arr)) { // Check for advanced exam code
                    if ($latest_advanced === null || $cert_date > strtotime($latest_advanced['certificate_date'])) {
                        
                        /*if(!in_array($exam_code,$old_exam_code_arr))
                        {
                          $row[0]["certificate_type"] = "Advance";
                        }else{
                          $row[0]["certificate_type"] = "";
                        }*/

                        $row[0]["certificate_type"] = "Advance";
                        
                        $latest_advanced = $row;
                    }
                } 
                else if (in_array($exam_code, $basic_exam_code_arr)) { // Otherwise check for basic exam code
                    if ($latest_basic === null || $cert_date > strtotime($latest_basic['certificate_date'])) {

                        /*if(!in_array($exam_code,$old_exam_code_arr))
                        {
                          $row[0]["certificate_type"] = "Basic";
                        }else{
                          $row[0]["certificate_type"] = "";
                        }*/

                        $row[0]["certificate_type"] = "Basic";
                        
                        $latest_basic = $row;
                    }
                }
            }

            // Priority 1: return latest advanced exam record
            if ($latest_advanced) {
                return $latest_advanced;
            }

            // Priority 2: return latest basic exam record
            if ($latest_basic) {
                return $latest_basic;
            }

            // If neither found
            return null; 
        }

        return null; // No record found
    } 
    /**************** END : FIND THE LATEST ADVANCED CERTIFICATES PROVIDED BY IIBF **************/

 
  }