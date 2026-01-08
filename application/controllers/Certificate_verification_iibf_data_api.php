<?php 
/********************************************************************
* Created BY  : Anil S on 2025-10-14
* Update By   : 
* Description : This is the API controller for Certificate Verification Data provided by IIBF to store into ESDS Database for the Certificate Verification API under the IBA BC Registry.
********************************************************************/

  defined('BASEPATH') OR exit('No direct script access allowed');
  require_once APPPATH . '/libraries/REST_Controller.php';
  require_once APPPATH . '/libraries/Format.php';
  use Restserver\Libraries\REST_Controller;
  
  class Certificate_verification_iibf_data_api extends REST_Controller /* extends CI_Controller / REST_Controller */
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
    CREATED BY    : Anil S on 2025-10-14
    UPDATE BY     : 
    DESCRIPTION   : verify the certificates provided by IIBF for the Training and Certification of BCs.
    EXAM CODES    : 
    ACCESS LINK   : 
    *********************************************************************/
    public function certificate_verification_data_post()
    {
      $this->Cron_api_model->token_arr(strtolower($this->class_name), strtolower($this->method_name), $this->api_key);//CHECK TOKEN VALIDATION
      
      ini_set("memory_limit", "-1");
      //header("Access-Control-Allow-Origin: *");     
      
      $log_id=0; 
      $log_title='IIBF Certificate Verification Data Posted'; 
      $record_count='0'; 
      $posted_data='';              
      
      try 
      {
        $message = 'Execution Started';       
        
        $log_id = $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);

        $registration_number = $certificate_no = $certificate_date = '';

        $success_count = 0;
        $already_exist_count = 0;
        $success_list = [];
        $already_exist_list = [];
        $failure_list = [];
        $error_list = [];

        // Read raw input (JSON data)
        $json_input = file_get_contents('php://input');
        $posted_data = $json_input;
        $records = json_decode($json_input, true); 

        // Validate JSON structure
        if (!is_array($records) || count($records) == 0) 
        {
            $data = [
                'status' => 401,
                'result' => "false",
                'message' => "Invalid or empty JSON data."
            ];
            $this->Cron_api_model->activity_log_common($log_id, $log_title, 'Invalid or empty JSON - Execution Ended', $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
            echo json_encode($data);
            return;
        }
        else
        {
            //echo "Inn<pre>";count($records);die;
            if(is_array($records) && count($records) > 0)
            { 
                foreach ($records as $index => $record) 
                {
                    $exam_code = isset($record['exam_code']) ? trim($record['exam_code']) : '';
                    $registration_number = isset($record['registration_number']) ? trim($record['registration_number']) : '';
                    $name_of_the_bc = isset($record['name_of_the_bc']) ? trim($record['name_of_the_bc']) : '';
                    $date_of_passing = isset($record['date_of_passing']) ? trim($record['date_of_passing']) : '';
                    $certificate_name = isset($record['certificate_name']) ? trim($record['certificate_name']) : '';
                    $certificate_no = isset($record['certificate_no']) ? trim($record['certificate_no']) : '';
                    $certificate_date = isset($record['certificate_date']) ? trim($record['certificate_date']) : ''; 

                    // Basic validation
                    if ($exam_code == '' || $registration_number == '' || $name_of_the_bc == '' || $certificate_name == '' || $certificate_no == '' || $certificate_date == '') {
                        $error_list[] = "Missing or empty field(s) found in record index " .($index + 1);

                        $failure_data["registration_number"] = $registration_number;
                        $failure_data["exam_code"] = $exam_code;
                        $failure_data["certificate_no"] = $certificate_no;
                        /*$failure_data["name_of_the_bc"] = $name_of_the_bc;
                        $failure_data["date_of_passing"] = $date_of_passing;
                        $failure_data["certificate_name"] = $certificate_name;
                        $failure_data["certificate_date"] = $certificate_date;*/
                        $failure_data["failure_reason"] = "Missing or empty field(s) found in record index " .($index + 1);
                        $failure_list[] = $failure_data;
                        continue;
                    } 
                    if (strlen($registration_number) > 50 || strlen($certificate_no) > 50 || strlen($exam_code) > 50) {
                        //$error_list[] = "Field length exceeded in record index " . ($index + 1);
                        $error_list[] = "One or more fields exceed 50 characters in record index " .($index + 1);

                        $failure_data["registration_number"] = $registration_number;
                        $failure_data["exam_code"] = $exam_code;
                        $failure_data["certificate_no"] = $certificate_no;
                        /*$failure_data["name_of_the_bc"] = $name_of_the_bc;
                        $failure_data["date_of_passing"] = $date_of_passing;
                        $failure_data["certificate_name"] = $certificate_name;
                        $failure_data["certificate_date"] = $certificate_date;*/
                        $failure_data["failure_reason"] = "One or more fields exceed 50 characters in record index " .($index + 1);
                        $failure_list[] = $failure_data;
                        continue;
                    } 

                    if ($exam_code != '' && $registration_number != '' && $name_of_the_bc != '' && $certificate_name != '' && $certificate_no != '' && $certificate_date != '')
                    {
                        $iibf_certificate_data = $this->master_model->getRecords('iibf_certificate_data_for_ibabcregistry_portal', array('registration_number' => $registration_number,'exam_code' => $exam_code,'certificate_no' => $certificate_no), 'exam_code,registration_number,name_of_the_bc,date_of_passing,certificate_name,certificate_no,certificate_date', array('id'=>'DESC'), 0,1);

                        if(is_array($iibf_certificate_data) && count($iibf_certificate_data) > 0)
                        {
                            // Prepare insert data
                            $exist_data["registration_number"] = $iibf_certificate_data[0]["registration_number"];
                            $exist_data["exam_code"] = $iibf_certificate_data[0]["exam_code"];                            
                            $exist_data["certificate_no"] = $iibf_certificate_data[0]["certificate_no"];
                            /*$exist_data["name_of_the_bc"] = $iibf_certificate_data[0]["name_of_the_bc"];
                            $exist_data["date_of_passing"] = $iibf_certificate_data[0]["date_of_passing"];
                            $exist_data["certificate_name"] = $iibf_certificate_data[0]["certificate_name"]; 
                            $exist_data["certificate_date"] = $iibf_certificate_data[0]["certificate_date"];*/

                            $already_exist_count++;
                            $already_exist_list[] = $exist_data;
                        }
                        else
                        { 
                            // Prepare insert data
                            $insert_data["registration_number"] = $insert_success_data["registration_number"] = $registration_number;
                            $insert_data["exam_code"] = $insert_success_data["exam_code"] = $exam_code;
                            $insert_data["certificate_no"] = $insert_success_data["certificate_no"] = $certificate_no;
                            $insert_data["name_of_the_bc"] = $name_of_the_bc;
                            //$insert_data["date_of_passing"] = $date_of_passing;
                            $insert_data["certificate_name"] = $certificate_name;
                            $insert_data["certificate_date"] = $certificate_date;

                            // Attempt insert
                            //$insert_status = 1;
                            $insert_status = $this->master_model->insertRecord('iibf_certificate_data_for_ibabcregistry_portal', $insert_data); 
                            if ($insert_status) {
                                $success_count++;
                                //$success_list[] = 'Record successfully inserted for Registration Number '.$registration_number.' and Certificate Number '.$certificate_no;
                                $success_list[] = $insert_success_data;
                            }
                            else 
                            {
                                $failure_data["registration_number"] = $registration_number;
                                $failure_data["exam_code"] = $exam_code;
                                $failure_data["certificate_no"] = $certificate_no;
                                /*$failure_data["name_of_the_bc"] = $name_of_the_bc;
                                $failure_data["date_of_passing"] = $date_of_passing;
                                $failure_data["certificate_name"] = $certificate_name;
                                $failure_data["certificate_date"] = $certificate_date;*/
                                $failure_data["failure_reason"] = "Failed to insert record for registration number: $registration_number";
                                $failure_list[] = $failure_data;
                                $error_list[] = "Failed to insert record for registration number: $registration_number";
                            } 
                        }
                    }
                    else 
                    {
                        $failure_data["registration_number"] = $registration_number;
                        $failure_data["exam_code"] = $exam_code;
                        $failure_data["certificate_no"] = $certificate_no;
                        /*$failure_data["name_of_the_bc"] = $name_of_the_bc;
                        $failure_data["date_of_passing"] = $date_of_passing;
                        $failure_data["certificate_name"] = $certificate_name;
                        $failure_data["certificate_date"] = $certificate_date;*/
                        $failure_data["failure_reason"] = "Failed to insert record for registration number: $registration_number";
                        $failure_list[] = $failure_data;
                        $error_list[] = "Failed to insert record for registration number: $registration_number";
                    }
                    
                }

                // Prepare final response
                if($success_count > 0 && $already_exist_count > 0)
                {
                    $data = [
                        'status' => 200,
                        'result' => "true",
                        'message' => "$success_count record(s) successfully inserted and ".$already_exist_count." record(s) already exist.",
                        'success_list' => $success_list,
                        'already_exist_list' => $already_exist_list,
                        'failure_list' => $failure_list,
                        //'errors' => $error_list
                    ];
                }  
                else if ($success_count > 0 && count($error_list) == 0) {
                    $data = [
                        'status' => 200,
                        'result' => "true",
                        'message' => "$success_count record(s) inserted successfully.",
                        'success_list' => $success_list,
                        'already_exist_list' => $already_exist_list,
                        'failure_list' => $failure_list,
                        //'errors' => $error_list
                    ];
                }
                else if ($already_exist_count > 0 && count($error_list) == 0) {
                    $data = [
                        'status' => 200,
                        'result' => "true",
                        'message' => "$already_exist_count record(s) already exist.",
                        'success_list' => $success_list,
                        'already_exist_list' => $already_exist_list,
                        'failure_list' => $failure_list,
                        //'errors' => $error_list
                    ];
                }
                else if ($success_count > 0 && count($error_list) > 0) {
                    $data = [
                        'status' => 200,
                        'result' => "true",
                        'message' => "$success_count record(s) inserted successfully, but some failed.",
                        'success_list' => $success_list,
                        'already_exist_list' => $already_exist_list,
                        'failure_list' => $failure_list,
                        //'errors' => $error_list
                    ];
                }
                else if ($already_exist_count > 0 && count($error_list) > 0) {
                    $data = [
                        'status' => 200,
                        'result' => "true",
                        'message' => "$already_exist_count record(s) already exist.",
                        'success_list' => $success_list,
                        'already_exist_list' => $already_exist_list,
                        'failure_list' => $failure_list,
                        //'errors' => $error_list
                    ];
                }
                else {
                    $data = [
                        'status' => 401,
                        'result' => "false",
                        'message' => "No records were inserted.",
                        'success_list' => $success_list,
                        'already_exist_list' => $already_exist_list,
                        'failure_list' => $failure_list,
                        //'errors' => $error_list
                    ];
                }
       
                $message .= ' - Execution completed. Success: ' . $success_count . ' Errors: ' . count($error_list);
                /*$message .= ' - Record successfully inserted for Registration Number '.$registration_number.' and Certificate Number '.$certificate_no.' - Execution Ended';  */        
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

    /*public function certificate_verification_data_new_post()
    {
        $this->Cron_api_model->token_arr(
            strtolower($this->class_name),
            strtolower($this->method_name),
            $this->api_key
        ); // TOKEN VALIDATION

        ini_set("memory_limit", "-1");

        $log_id = 0;
        $log_title = 'Verify Certificate Data';
        $record_count = '0';
        $posted_data = '';

        try {
            $message = 'Execution Started';

            $log_id = $this->Cron_api_model->activity_log_common(
                $log_id,
                $log_title,
                $message,
                $record_count,
                $posted_data,
                $this->class_name,
                $this->method_name,
                $this->api_key
            );

            // Read raw input (JSON data)
            $json_input = file_get_contents('php://input');
            $posted_data = $json_input;
            $records = json_decode($json_input, true);

            // Validate JSON structure
            if (!is_array($records) || count($records) == 0) {
                $data = [
                    'status' => 401,
                    'result' => "false",
                    'message' => "Invalid or empty JSON data."
                ];
                $this->Cron_api_model->activity_log_common($log_id, $log_title, 'Invalid or empty JSON - Execution Ended', $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
                echo json_encode($data);
                return;
            }

            $success_count = 0;
            $success_list = [];
            $error_list = [];

            foreach ($records as $index => $record) {
                $registration_number = isset($record['registration_number']) ? trim($record['registration_number']) : '';
                $certificate_no = isset($record['certificate_no']) ? trim($record['certificate_no']) : '';
                $certificate_date = isset($record['certificate_date']) ? trim($record['certificate_date']) : '';

                // Basic validation
                if ($registration_number == '' || $certificate_no == '' || $certificate_date == '') {
                    $error_list[] = "Missing fields in record index " . ($index + 1);
                    continue;
                }

                if (strlen($registration_number) > 50 || strlen($certificate_no) > 50 || strlen($certificate_date) > 50) {
                    $error_list[] = "Field length exceeded in record index " . ($index + 1);
                    continue;
                }

                // Prepare insert data
                $insert_data = [
                    "registration_number" => $registration_number,
                    "certificate_no" => $certificate_no,
                    "certificate_date" => $certificate_date
                ];

                // Attempt insert
                $insert_status = 1;
                //$insert_status = $this->master_model->insertRecord('cron_data', $insert_data);

                if ($insert_status) {
                    $success_count++;
                    $success_list[] = 'Record successfully inserted for Registration Number '.$registration_number.' and Certificate Number '.$certificate_no;
                } else {
                    $error_list[] = "Failed to insert record for registration number: $registration_number";
                }
            }

            // Prepare final response
            if ($success_count > 0 && count($error_list) == 0) {
                $data = [
                    'status' => 200,
                    'result' => "true",
                    'message' => "$success_count record(s) successfully inserted."
                ];
            } elseif ($success_count > 0 && count($error_list) > 0) {
                $data = [
                    'status' => 206,
                    'result' => "partial",
                    'message' => "$success_count record(s) inserted successfully, but some failed.",
                    'errors' => $error_list
                ];
            } else {
                $data = [
                    'status' => 401,
                    'result' => "false",
                    'message' => "No records were inserted.",
                    'errors' => $error_list
                ];
            }

            $message .= ' - Execution completed. Success: ' . $success_count . ' Errors: ' . count($error_list);

            $this->Cron_api_model->activity_log_common($log_id, $log_title, $message, $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);

            echo json_encode($data);

        } catch (Exception $e) {
            $data = [
                'status' => 401,
                'result' => "false",
                'message' => "Access denied: " . $e->getMessage()
            ];
            $this->Cron_api_model->activity_log_common($log_id, $log_title, 'Access denied', $record_count, $posted_data, $this->class_name, $this->method_name, $this->api_key);
            echo json_encode($data);
        }
    }*/
 

 
  }