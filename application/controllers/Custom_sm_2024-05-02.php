<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Custom_sm extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Master_model');
        $this->load->model('log_model');
        $this->load->model('Emailsending');
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        ini_set("memory_limit", "-1");
    }

    public function get_client_ip() {
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

    public function my_ipaddress()
    {
        $my_ip = $this->get_client_ip();
        echo '<span style="text-align: center;font-size: 40;font-weight: bold;">'.$my_ip.'</span>';
    }

    public function vp_main($value = '')
    {
        $this->load->model('billdesk_pg_model');
        $enc_res = 'eyJhbGciOiJIUzI1NiIsImNsaWVudGlkIjoiaW5kaW5zYmFmIiwia2lkIjoiSE1BQyJ9.eyJtZXJjaWQiOiJJTkRJTlNCQUYiLCJ0cmFuc2FjdGlvbl9kYXRlIjoiMjAyMy0wNC0wNFQxMTo0Mjo0OCswNTozMCIsInN1cmNoYXJnZSI6IjAuMDAiLCJwYXltZW50X21ldGhvZF90eXBlIjoidXBpIiwiYW1vdW50IjoiNTkwMC4wMCIsInJ1IjoiaHR0cHM6Ly9paWJmLmVzZHNjb25uZWN0LmNvbS9ob21lL2hhbmRsZV9iaWxsZGVza19yZXNwb25zZSIsIm9yZGVyaWQiOiI5MDQwMzk2MjQiLCJ0cmFuc2FjdGlvbl9lcnJvcl90eXBlIjoic3VjY2VzcyIsImRpc2NvdW50IjoiMC4wMCIsImJhbmtfcmVmX25vIjoiMzA5NDgyMDgzMjQ0IiwidHJhbnNhY3Rpb25pZCI6IlhIRDUxMDc5MTExNTU2IiwidHhuX3Byb2Nlc3NfdHlwZSI6ImNvbGxlY3QiLCJiYW5raWQiOiJIRDUiLCJhZGRpdGlvbmFsX2luZm8iOnsiYWRkaXRpb25hbF9pbmZvNyI6Ik5BIiwiYWRkaXRpb25hbF9pbmZvNiI6Ik5BIiwiYWRkaXRpb25hbF9pbmZvOSI6Ik5BIiwiYWRkaXRpb25hbF9pbmZvOCI6Ik5BIiwiYWRkaXRpb25hbF9pbmZvMTAiOiJOQSIsImFkZGl0aW9uYWxfaW5mbzEiOiI5MDQwMzk2MjQiLCJhZGRpdGlvbmFsX2luZm8zIjoiNTEwMzY1MzQ4IiwiYWRkaXRpb25hbF9pbmZvMiI6ImlpYmZleGFtIiwiYWRkaXRpb25hbF9pbmZvNSI6Ik5BIiwiYWRkaXRpb25hbF9pbmZvNCI6IjYwMDIwMjMwNiJ9LCJpdGVtY29kZSI6IkRJUkVDVCIsInRyYW5zYWN0aW9uX2Vycm9yX2NvZGUiOiJUUlMwMDAwIiwiY3VycmVuY3kiOiIzNTYiLCJhdXRoX3N0YXR1cyI6IjAzMDAiLCJ0cmFuc2FjdGlvbl9lcnJvcl9kZXNjIjoiVHJhbnNhY3Rpb24gU3VjY2Vzc2Z1bCIsIm9iamVjdGlkIjoidHJhbnNhY3Rpb24iLCJjaGFyZ2VfYW1vdW50IjoiNTkwMC4wMCJ9.bYmy00k4IUkTlmc-vTXcnkU-H7z1kM_T3P9vOS3AlVA';
        $bd_response            = $this->billdesk_pg_model->verify_res($enc_res);
        echo "<pre>";
        print_r($bd_response);
    }


    function test_exam_schedule()
    {
        //echo phpinfo();
        
         echo '<br>Start: ';
        
        $final_arr = $response_msg = array();
        $response = ''; 
        
        //$url="http://10.10.233.66:8088/ExamScheduleApi/getExamScheduleDtls";
        //$url="http://10.10.233.66:8088/ExamScheduleApi/getExamScheduleDtlsByExamCode/1006";
        $url="http://10.10.233.66:8088/ExamScheduleApi/getExamScheduleDtlsByExamCode/8";
        $url="http://10.10.233.66:8091/masterData/getExamDetails/1037/1/1"; // IIBF BCBF API - Exam Master
        $url="http://10.10.233.66:8092/getBCBFEligibleData/1037/1/500005437"; // IIBF BCBF API - Eligible Master
        //$url="http://10.10.233.66:8088/ExamScheduleApi/getExamScheduleDtlsByExamCode/11";
        //
                    
        $string = preg_replace('/\s+/', '+', $url);
        $x = curl_init($string);
        curl_setopt($x, CURLOPT_HEADER, 0);    
        curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);    
        curl_setopt($x, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($x, CURLOPT_SSL_VERIFYPEER, FALSE);
        
        $result = curl_exec($x);
        
        echo '<br>Result: <pre>'; print_r($result); echo '</pre>';
         
        if(curl_errno($x)) //CURL ERROR
        {
            $response = 'error';
            $response_msg = curl_error($x);
            echo '<br>response_msg : '.$response_msg = curl_error($x);
        }
        else
        {
            $response = "success"; 
            $response_msg = $result;
        }
        curl_close($x);
        
        $final_arr['response_msg'] = $response_msg;
        
        //echo '<br>Final Res: <pre>'; print_r($final_arr); echo '</pre>';
        echo '<pre>'; print_r(json_decode($result)); echo '</pre>';  
    }

    public function test_all_exam_schedule_api($value='')
    {

        echo '<br>Start: ';

        $final_arr = $response_msg = array();
        $response = ''; 
        
        echo 'API Endpoint :'.$url="http://10.10.233.66:8088/ExamScheduleApi/getExamScheduleDtls";
        //echo 'API Endpoint :'.$url="http://10.10.233.66:8089/dbfToJAIIBconversion/700027194/36";
        //$url="http://10.10.233.66:8088//ExamScheduleApi/getExamScheduleDtlsByExamCode/8";
                    
        $string = preg_replace('/\s+/', '+', $url);
        $x = curl_init($url);
        curl_setopt($x, CURLOPT_HEADER, 0);    
        curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);    
        curl_setopt($x, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($x, CURLOPT_SSL_VERIFYPEER, FALSE);
        
        $result = curl_exec($x);
        
        echo '<br>Result: <pre>'; print_r($result); echo '</pre>';
         
        if(curl_errno($x)) //CURL ERROR
        {
            $response = 'error';
            $response_msg = curl_error($x);
        }
        else
        {
            $response = "success"; 
            $response_msg = $result;
        }
        curl_close($x);
        
        $final_arr['response_msg'] = $response_msg;
        
        //echo '<br>Final Res: <pre>'; print_r($final_arr); echo '</pre>';
        echo '<br><pre>'; print_r(json_decode($result)); echo '</pre>';
    }

    public function website_exam_related_notices_dynamic_api($value='')
    { 
        //echo '<br>Start: '; 
        $final_arr = $response_msg = array();
        $response = ''; 
        
        $url="http://10.10.233.66:8088/ExamScheduleApi/getExamScheduleDtls";
        //$url="http://10.10.233.66:8088//ExamScheduleApi/getExamScheduleDtlsByExamCode/8";
                    
        $string = preg_replace('/\s+/', '+', $url);
        $x = curl_init($url);
        curl_setopt($x, CURLOPT_HEADER, 0);    
        curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);    
        curl_setopt($x, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($x, CURLOPT_SSL_VERIFYPEER, FALSE);
        
        $api_result = curl_exec($x);

        if (curl_errno($x))  //CURL ERROR
        {     
            echo "<h4 class='error_block'>Invalid Data</h4>";
            //echo '<br>response_msg : '.$response_msg = curl_error($x);
        } 
        else 
        {
            if($api_result) 
            {
              $api_result = json_decode($api_result); 

              if(isset($api_result->status) && $api_result->status != 200)
              {
                echo '<pre>'; print_r($api_result); echo '</pre>'; 
              }
              else
              {
                echo '<p style="text-align: center;"><strong>Examinations Scheduled at Glance</strong></p>';
            ?>
            <style>
            .website_exam_details_tbl { border-collapse: collapse; border: 1px solid #000; max-width: 800px; margin: 20px auto; font-family: Arial, Helvetica, sans-serif; width: 90%; }

            .website_exam_details_tbl thead th { text-align: center; background-color: #433e5a; border-bottom: 1px solid #eee;border-right: 1px solid #eee;padding: 10px;color: #fff;text-transform: uppercase;text-align: center;font-weight: bold;font-size: 14px; }

            .website_exam_details_tbl tbody td { border: 1px solid #000; padding: 8px 10px; font-size: 14px; line-height: 22px; vertical-align: top; min-width: 215px; }

            h4.error_block { font-size:20px; text-align:center; margin:40px auto 100px; color:red; }
            </style>
            <table class="website_exam_details_tbl">
                <thead>
                    <tr>
                      <th>Exam Code</th>
                      <th>Exam Name</th>
                      <th>Exam Period</th>
                      <th>Subject Id - Subject Name</th>
                      <th>Examination Date</th>
                      <th>Registration To Date</th>
                      <th>Registration From Date</th>
                      <th>Tentative Result Date</th>
                      <th>Actual Result Date</th>
                      <th>Tentative Certificate Date
                      <th>Actual Certificate Date</th>
                      <th>Publish on Website</th>
                      <th>Active on IVR</th>
                      <th>Exam Cycle</th> 
                    </tr>
                </thead>
                
                <tbody>
                    
                    <?php
                    $sub_arr = array(); 
                    if(count($api_result) > 0){ 
                        $k = 0;
                        foreach($api_result as $result){ 
                            if(isset($result[0]) && $result[0] != ""){  
                                $m=0;
                                for($i=13;$i<=22;$i++){
                                    if(isset($result[$i]) && $result[$i] != ""){  
                                      $sub_arr[$k][$m][] = $result[0]; //Exam Code 
                                      $sub_arr[$k][$m][] = $result[1]; //Exam Name 
                                      $sub_arr[$k][$m][] = $result[2]; //Exam Period 

                                      $sub_arr[$k][$m][] = $result[$i];    // Subject Id - Subject Name
                                      $sub_arr[$k][$m][] = $result[$i+10]; // Examination Date

                                      $sub_arr[$k][$m][] = $result[8];  //Registration To Date  
                                      $sub_arr[$k][$m][] = $result[7];  //Registration From Date
                                      $sub_arr[$k][$m][] = $result[9];  //Tentative Result Date
                                      $sub_arr[$k][$m][] = $result[10]; //Actual Result Date 
                                      $sub_arr[$k][$m][] = $result[11]; //Tentative Certificate Date 
                                      $sub_arr[$k][$m][] = $result[12]; //Actual Certificate Date
                                      $sub_arr[$k][$m][] = $result[6];  //Exam Cycle
                                    }
                                    $m++;
                                } 
                            }     
                            $k++;    
                        }
                    }
                    //echo "<pre>";print_r($sub_arr);

                    /*$url_exam="http://10.10.233.66:8088/ExamScheduleApi/getExamScheduleDtlsByExamCode/".$result[0]; 
                    $string_exam = preg_replace('/\s+/', '+', $url_exam);
                    $x_exam = curl_init($string_exam);
                    curl_setopt($x_exam, CURLOPT_HEADER, 0);    
                    curl_setopt($x_exam, CURLOPT_FOLLOWLOCATION, 0);
                    curl_setopt($x_exam, CURLOPT_RETURNTRANSFER, 1);    
                    curl_setopt($x_exam, CURLOPT_SSL_VERIFYHOST, FALSE);
                    curl_setopt($x_exam, CURLOPT_SSL_VERIFYPEER, FALSE); 
                    $result_exam = curl_exec($x_exam);      
                    if(curl_errno($x_exam))  //CURL ERROR
                    {     
                        echo "<h4 class='error_block'>Data Not Found!.</h4>"; 
                    } 
                    else 
                    { 
                        $result_exam = json_decode($result_exam); 
                        if(count($result_exam) > 0){ 
                            foreach($result_exam as $result){*/

                    if(count($sub_arr) > 0){ 
                        foreach($sub_arr as $res){ 
                        foreach($res as $result){ 
                            if(isset($result[0]) && $result[0] != ""){  
                                ?>
                                <tr> 
                                    <td><?php echo isset($result[0]) && $result[0] != "" ? $result[0] : ''; ?></td> 
                                    <td><?php echo isset($result[1]) && $result[1] != "" ? $result[1] : ''; ?></td> 
                                    <td><?php echo isset($result[2]) && $result[2] != "" ? $result[2] : ''; ?></td> 
                                    <td><?php echo isset($result[3]) && $result[3] != "" ? $result[3] : ''; ?></td> 
                                    <td><?php echo isset($result[4]) && $result[4] != "" ? $result[4] : ''; ?></td> 
                                    <td><?php echo isset($result[5]) && $result[5] != "" ? $result[5] : ''; ?></td> 
                                    <td><?php echo isset($result[6]) && $result[6] != "" ? $result[6] : ''; ?></td> 

                                    <td><?php echo isset($result[7]) && $result[7] != "" ? $result[7] : ''; ?></td> 
                                    <td><?php echo isset($result[8]) && $result[8] != "" ? $result[8] : ''; ?></td> 

                                    <td><?php echo isset($result[9]) && $result[9] != "" ? $result[9] : ''; ?></td> 
                                    <td><?php echo isset($result[10]) && $result[10] != "" ? $result[10] : ''; ?></td> 
                                    <td><?php echo ''; ?></td> 
                                    <td><?php echo ''; ?></td> 
                                    <td><?php echo isset($result[11]) && $result[11] != "" ? $result[11] : ''; ?></td> 
                                </tr>     
                                <?php 
                                }
                            }
                         }
                      }
                      else
                      {
                        echo "<tr><td colspan='5'>No Data!..</td></tr>";
                      } ?>
                    
                </tbody>
            </table>            
    <?php }
        } 
        else 
        {
          echo "<h4 class='error_block'>No Data!..</h4>";
        }
      }
      curl_close($x);
         
    }

    public function sms_test()
    {
      $sms_final_str1 = 'You have successfully subscribed for IIBF Finquest.';
      $r=$this->master_model->send_sms_common_all(8308318490, $sms_final_str1, '1707163293328405009', 'IIBFCO');
      print_r($r);
    }

    public function index()
    {
        $this->load->helper('csc_admitcard_helper');
        echo genarate_admitcard_csc();

        // echo CI_VERSION;
        /* echo '<br>current_date : '.$current_date = date('Y-m-d H:i');
        //echo '<br>current_date : '.$current_date = '2022-02-01 13:00';

        if($current_date >= '2022-01-31 21:00' && $current_date <= '2022-02-01 13:00')
        {
        echo '<br>Under maintenance';
        }
        else
        {
        echo '<br>Working';
        } */

        /* echo date('d-m-Y H:i:s');
    //unlink("./uploads/scansignature/s_510516976.jpg");
    //echo copy("./uploads/s_510516976.jpg", "./uploads/scansignature/s_510516976.jpg");
    $this->db->join('exam_activation_master','exam_activation_master.exam_code=center_master.exam_name AND exam_activation_master.exam_period=center_master.exam_period');
    $this->db->where("center_delete",'0');
    $this->db->where('exam_name','991');
    $this->db->where("center_master.center_code !=",751);
    $this->db->group_by('center_master.center_name');
    $center=$this->master_model->getRecords('center_master');
    echo "<br/ >".$this->db->last_query(); */
        // $user_info = $this->master_model->getRecords('member_registration', array());
        // echo $this->db->last_query();
    }

    function random_password($length = 6)
    {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
        $password = substr(str_shuffle($chars), 0, $length);
        return $password;
    }


    public function member_settlement_new()
    {
        /*
		
    
    
    
    
    
    
    
    
    
	*/
        $member_no = array(510148355);
        $mem_exam_id = array(7233000); // member exam table primary key OR admit_card_detail mem_exam_id
        $exam_code = $this->config->item('examCodeCaiib');
        $exam_prd = 222;
        $password = $this->random_password();

        //check in admit card  table
        $this->db->where_in('mem_mem_no', $member_no);
        $this->db->where_in('mem_exam_id', $mem_exam_id);
        $this->db->group_by('sub_cd,mem_exam_id');
        $admit_card_details = $this->master_model->getRecords('admit_card_details', array(
            'exm_cd' => $exam_code,
            'exm_prd' => $exam_prd
        ));

        //echo $this->db->last_query(); exit;

        /********Password Code********/


        if (!empty($admit_card_details)) {
            foreach ($admit_card_details as $val) {
                if ($val['pwd'] != '') {
                    $password = $val['pwd'];
                }
            }
        }
        if ($password == '') {
            $password = $this->random_password();
        }
        /********End of Password Code********/
        echo 'here';
        if (!empty($admit_card_details)) {
            echo 'here123123';
            echo 'Total recode found in admit card table :<br>';
            echo count($admit_card_details);
            // print_r($admit_card_details);
            //exit;
            foreach ($admit_card_details as $val) {
                if ($val['seat_identification'] == '') {
                    echo "prt--";
                    //get the  seat number from the seat allocation table 2
                    $this->db->order_by("seat_no", "desc");
                    $seat_allocation = $this->master_model->getRecords('seat_allocation', array('venue_code' => $val['venueid'], 'session' => $val['time'], 'center_code' => $val['center_code'], 'date' => $val['exam_date']));
                    if (!empty($seat_allocation)) {
                        //check venue_capacity
                        $venue_capacity = $this->master_model->getRecords('venue_master', array(
                            'venue_code' => $val['venueid'],
                            'session_time' => $val['time'],
                            'center_code' => $val['center_code'],
                            'institute_code' => '0',
                            'exam_date' => $val['exam_date']
                        ));
                        //echo  $this->db->last_query(); exit;

                        $venue_capacity = $venue_capacity[0]['session_capacity'] + 20;
                        if (!empty($venue_capacity)) {
                            //if(count($seat_allocation)<=$venue_capacity)
                            if ($seat_allocation[0]['seat_no'] <= $venue_capacity) {
                                $seat_no = $seat_allocation[0]['seat_no'];
                                //inset new recode with append  seat number
                                $seat_no = $seat_no + 1;
                                if ($seat_no < 10) {
                                    $seat_no = '00' . $seat_no;
                                } elseif ($seat_no > 10 && $seat_no < 100) {
                                    $seat_no = '0' . $seat_no;
                                }
                                $invoice_insert_array = array(
                                    'seat_no' => $seat_no,
                                    'exam_code' => $val['exm_cd'],
                                    'venue_code' => $val['venueid'],
                                    'session' => $val['time'],
                                    'center_code' => $val['center_code'],
                                    'date' => $val['exam_date'],
                                    'exam_period' => $val['exm_prd'],
                                    'subject_code' => $val['sub_cd'],
                                    'admit_card_id' => $val['admitcard_id'],
                                    'createddate' => date('Y-m-d H:i:s')
                                );
                                if ($this->master_model->insertRecord('seat_allocation', $invoice_insert_array)) {
                                    //inset new recode with append  seat number
                                    //$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
                                    //$password = substr( str_shuffle( $chars ), 0, 6 );
                                    $admitcard_image = $val['exm_cd'] . '_' . $val['exm_prd'] . '_' . $val['mem_mem_no'] . '.pdf';
                                    $update_info = array(
                                        'seat_identification' => $seat_no,
                                        'modified_on' => $val['created_on'],
                                        'admitcard_image' => $admitcard_image,
                                        'pwd' => $password,
                                        'remark' => 1,
                                    );
                                    if ($this->master_model->updateRecord('admit_card_details', $update_info, array('admitcard_id' => $val['admitcard_id']))) {
                                        echo '<br>Recode updated sucessfully in admit card<br>';
                                    } else {
                                        echo '<br>Recode Not updated sucessfully in admit card<br>';
                                    }
                                }
                            } else {
                                echo '<br>Capacity has been full<br>';
                            }
                        } else {
                            echo '<br>Venue not present in venue master123<br>';
                        }
                    } else {
                        $venue_capacity = $this->master_model->getRecords('venue_master', array(
                            'venue_code' => $val['venueid'],
                            'session_time' => $val['time'],
                            'center_code' => $val['center_code'],
                            'exam_date' => $val['exam_date']
                        ));
                        echo $this->db->last_query();
                        if (!empty($venue_capacity)) {
                            if ($seat_allocation[0]['seat_no'] <= $venue_capacity[0]['session_capacity']) {
                                //inset new recode with oo1
                                $seat_no = '001';
                                $invoice_insert_array = array(
                                    'seat_no' => $seat_no,
                                    'exam_code' => $val['exm_cd'],
                                    'venue_code' => $val['venueid'],
                                    'session' => $val['time'],
                                    'center_code' => $val['center_code'],
                                    'date' => $val['exam_date'],
                                    'exam_period' => $val['exm_prd'],
                                    'subject_code' => $val['sub_cd'],
                                    'admit_card_id' => $val['admitcard_id'],
                                    'createddate' => date('Y-m-d H:i:s')
                                );
                                if ($this->master_model->insertRecord('seat_allocation', $invoice_insert_array)) {
                                    echo 'Seat alloation primary key :<br>';
                                    echo $inser_id;
                                    //update the admit card table :
                                    //$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
                                    //$password = substr( str_shuffle( $chars ), 0, 6 );
                                    $admitcard_image = $val['exm_cd'] . '_' . $val['exm_prd'] . '_' . $val['mem_mem_no'] . '.pdf';
                                    $update_info = array(
                                        'seat_identification' => $seat_no,
                                        'modified_on' => $val['created_on'],
                                        'admitcard_image' => $admitcard_image,
                                        'pwd' => $password,
                                        'remark' => 1,
                                    );
                                    if ($this->master_model->updateRecord('admit_card_details', $update_info, array('admitcard_id' => $val['admitcard_id']))) {
                                        echo 'Recode updated sucessfully in admit card<br>';
                                    } else {
                                        echo 'Recode Not updated sucessfully in admit card<br>';
                                    }
                                }
                            } else {
                                echo '<br>Capacity has been full<br>';
                            }
                        } else {
                            echo '<br>Venue not present in venue master234<br>';
                        }
                    }
                }
            }
        }
    }

    public function exam_invoice_update()
    {
        $rec = array(903523426, 903523429, 903523431, 903523436, 903523448, 903523453, 903523459, 903523460, 903523468, 903523473, 903523486, 903523488, 903523491, 903524489, 903524563, 903524522, 903524574, 903524576, 903524586, 903524590, 903524552, 903524600, 903524803, 903524810, 903524820, 903524829, 903524830, 903524831, 903524765, 903524834, 903524836, 903524835, 903524772, 903524846, 903524856, 903524855, 903524859, 903524862, 903524866, 903524878, 903524879, 903524884, 903524839, 903524889, 903524890, 903524895, 903524854, 903524912, 903525025, 903525026, 903525032, 903525036, 903525034, 903524971, 903525048, 903525051, 903525053, 903524997, 903525055, 903525059, 903525064, 903525065, 903525070, 903525076, 903525077, 903525080);
        $this->db->where_in('receipt_no', $rec);
        $invoice_data = $this->Master_model->getRecords('exam_invoice', array('invoice_no !=' => '', 'app_type' => 'O'));
        if (!empty($invoice_data)) {
            foreach ($invoice_data as $res) {
                $regnumber = $res['member_no'];
                $transaction_no = $res['transaction_no'];
                $receipt_no = $res['receipt_no'];

                $update_data = array('transaction_no' => $transaction_no, 'status' => '1');
                //,'transaction_no'=>$transaction_no,'status'=>'1'
                $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $receipt_no));

                echo $this->db->last_query();
            }
        }
        //echo $this->db->last_query(); die;
    }

    ## Function addded by Pratibha on 22 Nov 2021 to get kerala candidates applied for DBF exam
    public function get_kerala_asap_data()
    {
        $aadhar_array  = array('532278898713', '910776221686', '328419428833', '651201390716', '425880119228', '729128046746', '803220017852', '285815715616', '841247553209', '543396243986', '932152305404', '315499628985', '416147488885', '423194673073', '919271251830', '381267710394', '692782074043', '370587652557', '983032014610', '870054453811', '202205208015', '915752685145', '555485331060', '748276172811', '406534621235', '390154601757', '741710831091', '856453716771', '647963167447', '823858709102', '395486661993', '747395055503', '427221992798', '901255272230', '448988561627', '438267191679', '459140745265');
        $dob           = array('06-01-2000', '13-10-1986', '11-05-1990', '24-04-1993', '06-09-2006', '23-02-1996', '27-01-1999', '30-05-1983', '03-09-1998', '09-11-1998', '03-09-1998', '29-02-2000', '01-02-2001', '06-11-1994', '27-01-1992', '12-04-1998', '15-11-2000', '26-10-2000', '12-11-1997', '23-07-2001', '05-06-2006', '13-10-1998', '16-10-2000', '30-04-1980', '08-01-1998', '30-08-1994', '29-10-2000', '17-08-1996', '14-12-1996', '21-04-1999', '23-11-1995', '28-08-1998', '27-12-1999', '25-10-2000', '07-09-2001', '07-05-1995', '09-01-2000');
        $getCandidates = $this->master_model->getRecords('member_exam', array('exam_code' => $this->config->item('examCodeDBF'), 'pay_status' => '1', 'exam_period' => '221'), 'regnumber');
        /*echo "<br/ >".$this->db->last_query();
        echo '<pre>';
        print_r($getCandidates);
         */
        if (count($getCandidates) > 0) {
            foreach ($getCandidates as $candidate) {
                //echo "<br/> ".$candidate['regnumber'];
                ## check aadhar card number and dateofbirth from member registration
                $this->db->limit(1);
                $member_data = $this->master_model->getRecords('member_registration', array('regnumber' => $candidate['regnumber'], 'isactive' => '1'), 'aadhar_card,dateofbirth');
                //echo "<br/ >".$this->db->last_query();
                if (count($member_data) > 0) {
                    if (in_array($member_data[0]['aadhar_card'], $aadhar_array)) {
                        if (in_array(date('d-m-Y', strtotime($member_data[0]['dateofbirth'])), $dob)) {
                            //echo "<br/> ".$candidate['regnumber']." ===> ".$member_data[0]['aadhar_card']." ===> ".date('d-m-Y',strtotime($member_data[0]['dateofbirth']));
                            echo "<br/> " . $member_data[0]['aadhar_card'];
                        }
                    }
                }
            }
        }
    }

    ########get venue#########
    public function getVenue()
    {
        $centerCode    = '221'; //$_POST['centerCode'];
        $examCode      = $this->config->item('examCodeJaiib'); //$_POST['examCode'];
        $subject_array = array('34', '35', '36'); //$_POST['subject_array'];
        $venue_option  = '<option value="">Select Venue</option>';
        $date_option   = '<option value="">Select Date</option>';
        $time_option   = '<option value="">Select Time</option>';
        $subject_date  = $venue_arr  = array();
        if ($centerCode != "" && $examCode != '') {
            //exam period change for getting  venue as per their exam period
            $this->db->where_in('subject_code', $subject_array, false);
            $this->db->where('exam_date !=', '0000-00-00');
            $getSubject_date = $this->master_model->getRecords('subject_master', array('exam_code' => $examCode), array('exam_date'), array('exam_date' => 'ASC')); //'exam_period'
            echo $this->db->last_query() . '</br>';
            if (count($getSubject_date) > 0) {
                $i = 1;
                foreach ($getSubject_date as $row) {
                    //$subject_date[]=$row['exam_date'];
                    //$exam_period =$getSubject_date[0]['exam_period'];
                    $this->db->where('exam_date', $row['exam_date']);
                    //$this->db->where('exam_period', $exam_period);
                    $this->db->group_by('venue_code');
                    $getvenue_details = $this->master_model->getRecords('venue_master', array('center_code' => $centerCode), 'venue_name,venue_addr1,venue_addr2,venue_addr3,venue_addr4,venue_addr5,venue_code,venue_pincode,pwd_enabled,vendor_code');
                    echo $this->db->last_query(), '</br>';
                    if (count($getvenue_details) > 0) {
                        $venue_option = '<option value="">Select Venue</option>';
                        foreach ($getvenue_details as $row) {
                            $pwd_enable = '';
                            if ($row['pwd_enabled'] == 'Y') {
                                $pwd_enable = ' (PWD enabled)';
                            }
                            $venue_add             = '';
                            $venue_add             = $row['venue_name'] . '*' . $row['venue_addr1'] . '*' . $row['venue_addr2'] . '*' . $row['venue_addr3'] . '*' . $row['venue_addr4'] . '*' . $row['venue_addr5'] . '*' . $row['venue_pincode'];
                            $venue_add_finalstring = preg_replace('#[\*]+#', ',', $venue_add);
                            $venue_option .= '<option value=' . $row['venue_code'] . ' title="' . $venue_add_finalstring . '' . $pwd_enable . '">' . substr($venue_add_finalstring, 0, 39) . '' . $pwd_enable . '.</option>';
                        }

                        $venue_arr['venue_option_' . $i] = $venue_option;
                    } else {
                        $venue_option                    = '<option value="">Select Venue</option>';
                        $venue_arr['venue_option_' . $i] = $venue_option;
                    }
                    $i++;
                }
            }
            /*//$exam_period =$getSubject_date[0]['exam_period'];
        $this->db->where_in('exam_date', $subject_date);
        //$this->db->where('exam_period', $exam_period);
        $this->db->distinct('venue_code');
        $getvenue_details=$this->master_model->getRecords('venue_master',array('center_code'=>$centerCode),'venue_name,venue_addr1,venue_addr2,venue_addr3,venue_addr4,venue_addr5,venue_code,venue_pincode,pwd_enabled,vendor_code');
        //echo $this->db->last_query(),'</br>';
        if(count($getvenue_details) > 0)
        {
        foreach($getvenue_details as $row)
        {
        $pwd_enable='';
        if($row['pwd_enabled']=='Y')
        {
        $pwd_enable=' (PWD enabled)';
        }
        $venue_add='';
        $venue_add=$row['venue_name'].'*'.$row['venue_addr1'].'*'.$row['venue_addr2'].'*'.$row['venue_addr3'].'*'.$row['venue_addr4'].'*'.$row['venue_addr5'].'*'.$row['venue_pincode'];
        $venue_add_finalstring= preg_replace('#[\*]+#', ',', $venue_add);
        $venue_option.='<option value='.$row['venue_code'].' title="'.$venue_add_finalstring.''.$pwd_enable.'">'.substr($venue_add_finalstring,0,39).''.$pwd_enable.'.</option>';
        }
        }*/
        }
        $other_option_arr = array('date_option' => $date_option, 'time_option' => $time_option);
        //$data_arr=array('venue_option'=>$venue_option,'date_option'=>$date_option,'time_option'=>$time_option);
        $data_arr = array_merge($venue_arr, $other_option_arr);
        echo json_encode($data_arr);
    }

    public function check_mail_php()
    {
        $from_name  = 'IIBF';
        $from_email = 'logs@iibf.esdsconnect.com';
        $subject    = 'Test mail : php ' . date('d-m-Y H:i:s');
        //$recipient_list = array('sagar.matale@esds.co.in','pratibha.purkar@esds.co.in');
        //$recipient_list = array('sagar.matale01@gmail.com','khambekar.rucha@gmail.com');

        $recipient_list = array('supp0rt24x7@gmail.com', 'sagar.matale01@gmail.com', 'khambekar.rucha@gmail.com', 'sagar.matale@esds.co.in', 'pratibha.purkar@esds.co.in');

        echo '<pre>';
        print_r($recipient_list);
        echo '</pre>';

        //$attachment_filename = 'logs_' . $current_date . '.txt';
        //$attachment_path     = './uploads/cronCSV/' . $current_date . '/' . $attachment_filename;

        $message = 'Test Mail : php ' . date('d-m-Y H:i:s');

        $config = array(
            'mailtype' => 'html',
            'charset'  => 'utf-8',
            'wordwrap' => true,
        );

        $this->email->initialize($config);
        $this->email->from($from_email, $from_name);
        $this->email->to($recipient_list);
        $this->email->subject($subject);
        $this->email->message($message);
        /* if ($attachment_path != '') {
        $this->email->attach($attachment_path);
        } */

        if ($this->email->send()) {
            echo 'Email Sent.';
        } else {
            echo 'Email Not Sent.';
        }
    }

    public function check_mail_smtp()
    {
        $this->load->model('Emailsending');
        $info_arr = array(
            'to' => 'sagar.matale@esds.co.in',
            'from'                 => 'logs@iibf.esdsconnect.com',
            'subject'              => 'Test mail : smtp',
            'message'              => 'Test mail : smtp',
        );

        if ($this->Emailsending->mailsend($info_arr)) {
            echo 'mail send';
        } else {
            echo 'mail not send';
        }
    }

    public function update_old_member_data()
    {
        exit;
        /* SELECT regid, regnumber, firstname, email, DATE(createdon)
        FROM spm_elearning_registration
        WHERE isactive = 1 AND DATE(createdon) < '2021-09-21'
        ORDER BY DATE(createdon) DESC
        active member : 4444

        SELECT regid, regnumber, firstname, email, createdon, (SELECT COUNT(el_sub_id) FROM spm_elearning_member_subjects WHERE status = 1 AND regnumber = spm_elearning_registration.regnumber) AS SuccessCnt
        FROM spm_elearning_registration
        WHERE isactive = 1 AND DATE(createdon) < '2021-09-21'
        HAVING SuccessCnt > 0
        ORDER BY createdon DESC
        Active member with success payment : 2394

        SELECT regid, regnumber, firstname, email, createdon, (SELECT COUNT(el_sub_id) FROM spm_elearning_member_subjects WHERE status = 1 AND regnumber = spm_elearning_registration.regnumber) AS SuccessCnt
        FROM spm_elearning_registration
        WHERE isactive = 1 AND DATE(createdon) < '2021-09-21'
        HAVING SuccessCnt = 0
        ORDER BY createdon DESC
        Active member with fail payment : 2050 */

        $current_date  = date("Ymd");
        $cron_file_dir = "./uploads/rahultest/";

        if (!file_exists($cron_file_dir . $current_date)) {
            $parent_dir_flg = mkdir($cron_file_dir . $current_date, 0700);
        }

        if (file_exists($cron_file_dir . $current_date)) {
            $cron_file_path = $cron_file_dir . $current_date; // Path with CURRENT DATE DIRECTORY

            $file = "iibf_delete_member_elearning_spm_details2_" . $current_date . ".txt";
            $fp   = fopen($cron_file_path . '/' . $file, 'w');

            $mem_data = $this->master_model->getRecords('spm_elearning_registration mr', array('isactive' => '0', 'mr.createdon <=' => '2021-10-03'), 'mr.regid, mr.regnumber, mr.namesub, mr.firstname, mr.middlename, mr.lastname, mr.state, mr.email, mr.mobile, mr.isactive, mr.createdon');
            echo $this->db->last_query();
            exit;

            if (count($mem_data) > 0) {
                $cnt = 0;
                foreach ($mem_data as $mem_res) {
                    $chk_payment_status = $this->master_model->getRecords('payment_transaction', array('member_regnumber' => $mem_res['regnumber'], 'ref_id' => $mem_res['regid'], 'pay_type' => '20', 'status' => '1'), 'id, transaction_no, status');

                    if (count($chk_payment_status) > 0) {
                        $up_data['isactive'] = '1';
                        //$this->master_model->updateRecord('spm_elearning_registration',$up_data,array('regid'=>$mem_res['regid']));

                        $data = $mem_res['regnumber'] . '|' . $mem_res['namesub'] . '|' . $mem_res['firstname'] . '|' . $mem_res['middlename'] . '|' . $mem_res['lastname'] . '|' . $mem_res['state'] . '|' . $mem_res['email'] . '|' . $mem_res['mobile'] . '|' . $mem_res['createdon'] . "\n";
                        fwrite($fp, $data);

                        $cnt++;
                    }
                }

                fwrite($fp, "Total E-learning Separate Module Member Registration - " . $cnt . "\n");
            }

            fclose($fp);
        }
    }

    public function qry() 
    {
      echo $query_bcbf = 'SELECT receipt_no, pg_flag FROM iibfbcbf_payment_transaction WHERE date <= NOW() - INTERVAL 20 MINUTE AND date > NOW() - INTERVAL 60 MINUTE AND gateway = "2" AND status = "2" AND payment_mode = "Individual"';
      
    }

    /* Function added by Pratibha on 16-11-2021 to check users paid for elarning but not selected elearning
    for RPE exams
    Select members from member_exam from 9-11-2021 to 11-11-2021
    Check elearning flag from member exam
    Check elearning flag from userlogs
    match both flags if same then nothing to do
    if different then check fees. If paid for elearning with flag N
    then proceed for refund
     */

    public function member_invoice_settlement()
    {

        $arr = array(812321251, 812321257, 812321259);

        for ($i = 0; $i < count($arr); $i++) {

            $this->db->where('receipt_no', $arr[$i]);

            $invoice_info = $this->master_model->getRecords('exam_invoice', '', 'invoice_id,member_no');



            echo $this->db->last_query();

            echo '<br/>';



            $this->db->where('receipt_no', $arr[$i]);

            $payment_info = $this->master_model->getRecords('payment_transaction', '', 'ref_id,member_regnumber,date,transaction_no');



            $transaction_no = $payment_info[0]['transaction_no'];



            echo $this->db->last_query();

            echo '<br/>';



            $this->db->where('regnumber', $payment_info[0]['member_regnumber']);

            $member_info = $this->master_model->getRecords('member_registration', '', 'regid,regnumber');



            echo $this->db->last_query();

            echo '<br/>';





            if ($payment_info[0]['member_regnumber'] == $member_info[0]['regnumber']) {

                $invoice_mem_no = $member_info[0]['regnumber'];
            } else {

                $invoice_mem_no = $invoice_info[0]['member_no'];
            }



            echo $invoice_date_of_invoice = $payment_info[0]['date'];

            echo '<br/>';

            echo $invoice_modified_on = $payment_info[0]['date'];

            echo '<br/>';







            $this->db->where('invoice_id', $invoice_info[0]['invoice_id']);

            $config_info = $this->master_model->getRecords('config_reg_invoice', '', 'reg_invoice_no');


            // echo '>>>'. $config_info[0]['reg_invoice_no'];

            // echo '<br/>';



            // echo $this->db->last_query();

            // echo '<br/>';

            // echo '****';

            // echo '<br/>';



            if (count($config_info) > 0 && $config_info[0]['reg_invoice_no'] != '') {

                $invoice_no = $config_info[0]['reg_invoice_no'];

                echo 'invoice number already present';

                exit;
            } else {
                echo 'config insert done';
                echo '<br/>';

                $insert_info = array('invoice_id' => $invoice_info[0]['invoice_id']);

                $last_id = $this->master_model->insertRecord('config_reg_invoice', $insert_info, true);

                $last_id = str_pad($last_id, 6, "0", STR_PAD_LEFT);
            }





            echo $this->db->last_query();

            echo '<br/>';



            echo $invoice_number = 'M/22-23/' . $last_id;

            echo '<br/>';

            echo $invoice_img_name = $invoice_mem_no . '_M_22-23_' . $last_id . '.jpg';

            echo '<br/>';


            $update_arr = array(

                'member_no' => $invoice_mem_no,

                'transaction_no' => $transaction_no,

                'invoice_no' => $invoice_number,

                'invoice_image' => $invoice_img_name,

                'date_of_invoice' => $invoice_date_of_invoice,

                'modified_on' => $invoice_modified_on

            );



            $this->master_model->updateRecord('exam_invoice', $update_arr, array('invoice_id' => $invoice_info[0]['invoice_id'], 'receipt_no' => $arr[$i]));



            echo $this->db->last_query();

            echo '<br/>';





            echo 'Receipt no >> ' . $arr[$i];

            echo '<br/>';
        }
    }


    public function rpe_el()
    {
        $regnumbers = array('510433084', '500041529', '510335040', '801792062', '510449801', '510335040', '510366100', '801792091', '510234053', '801792114', '510111753', '510466080', '510481986', '500182182', '801792129', '510183478', '510512108', '801792147', '801257439', '500098559', '510512403', '500052672', '100098401', '801672798', '500202492', '510466080', '801792211', '500042311', '510102892', '500175248', '801792229', '500042311', '500051476', '510481122', '510452675', '801792279', '510389125', '510399153', '510140156', '801792365', '510514662', '510405609', '801655165', '510430168', '500032859', '801792400', '801792435', '510178498', '801792449', '510138672', '510412037', '801792466', '801792524', '510126039', '510512880', '510020118', '510059049', '500182607', '500193268', '801792557', '510485097', '500136284', '801792583', '801792576', '510432984', '510332365', '500184094', '801792645', '510509081', '801792669', '500157086', '500104177', '510287423', '500131074', '510407591', '510512348', '500104164', '510509459', '510387449', '801792716', '500148013', '801792741', '510474652', '510170767', '510068818', '510513825', '510033293', '801172829', '510335507', '510139421', '510482660', '510021415', '510009878', '510021415', '500135469', '510511473', '801792858', '510277866', '510182953', '510134323', '500004140', '510504715', '510064489', '510484423', '510483568', '510349183', '500004140', '7455414', '510167748', '510138330', '801792935', '500210474', '510134746', '510081676', '801219623', '510271357', '510251934', '801792952', '801792962', '500097212', '510204720', '801792971', '500149960', '510104986', '510093078', '801792975', '500211926', '510142947', '500068283', '510436579', '510111909', '510290086', '510157536', '801674133', '510513551', '500041537', '510498564', '510021369', '510216214', '510341369', '510513792', '510206886', '510469564', '510263643', '400137286', '510483699', '510383516', '510050436', '510073302', '510367321', '510246404', '500184562', '510186838', '500184562', '510452960');
        ## Emails ids of above users
        $emails   = array('vivekanand.hembram@nhb.org.in', 'vmahesh13@gmail.com', 'mohitnagar.1977@gmail.com', 'deshdeepak_pandey@yahoo.com', 'manoj_iob08@yahoo.co.in', 'awanish81@gmail.com', 'prakashiob@yahoo.in', 'withlovanand@gmail.com', 'ksingh2818@gmail.com', 'tanna.hiren@gmail.com', 'ashok.andhrabank@gmail.com', 'a4archit@rediffmail.com', 'deepakwbo@gmail.com', 'rakeshkrroushan87@gmail.com', 'rsooraj85@gmail.com', 'bhoria.dimple@gmail.com', 'laxman.rathore@sbi.co.in', 'sandeepkaur2427@gmail.com', 'rajesh.gonuguntla@gmail.com', 'aryagp100@gmail.com', 'gautam.khairnar@jjsbl.co.in', 'chandra1485@gmail.com', 'shantanu121287@gmail.com', 'nikhade.nilesh88@gmail.com', 'prabha.nandan461@gmail.com', 'arvindqumar@gmail.com', 'jagdeep.singh1@sbi.co.in', 'd.tarun.kumar@gmail.com', 'alieema.914@gmail.com', 'sravanakumaru@gmail.com', 'athirasudhakar@gmail.com', 'kumar.lakshmana@gmail.com', 'jijocatenation23@gmail.com', 'arpan.das7@gmail.com', 'harvinder121@gmail.com', 'SNEHAL.PATEL2590@GMAIL.COM', 'sushra10@gmail.com', 'mymail.shiv@rediffmail.com', 'mmareestims@yahoo.com', 'amandeep_binji@yahoo.com', 'vishalwan@gmail.com', 'swativipulgajjar@gmail.com', 'vips3203@gmail.com', 'naveenkumar6791@gmail.com', 'naveenkumar6791@gmail.com', 'gaurav.omar@hotmail.com', 'nashinevikas@gmail.com', 'sujana_bhaskar@yahoo.com', 'hanote_s@yahoo.in', 'priyashekartuty@gmail.com', 'manishbanda@gmail.com', 'devi.j.vijayan@gmail.com', 'ponsankarr@gmail.com', 'BANSAL.PRIYANKA1990@YAHOO.COM', 'thamil.shiva@gmail.com', 'srinivascarthick@gmail.com', 'ajaykumar.singh515@gmail.com', 'namratha.kv07@gmail.com', 'madhuradk23@gmail.com', 'madhavisakarey@gmail.com', 'naveen.chauhan99@yahoo.com', 'tarunverma2309@gmail.com', 'tarunverma2309@gmail.com', 'vemireddy.varunreddy@gmail.com', 'mamillapallijahnavi@gmail.com', 'vicky31sebastein@gmail.com', 'casajeeshe@gmail.com', 'saro100sv@gmail.com', 'itsuramit@gmail.com', 'tirumalasettidivya@gmail.com', 'sharmaaniket117@gmail.com', 'mallaiahbella@gmail.com', 'vinayakgupta001@gmail.com', 'satish2908@gmail.com', 'aparna1555@gmail.com', 'spaidalar@gmail.com', 'walle.sap7@gmail.com', 'kishoreravi1993@gmail.com', 'kvaravind.94@gmail.com', 'ARORAVIRENDER73@GMAIL.COM', 'rohiniis24@gmail.com', 'prettyspaul@gmail.com', 'kshlexam2016@gmail.com', 'ualfarah@gmail.com', 'bhatnagaranmol37@gmail.com', 'sruthihari110911@gmail.com', 'mala.nair@esafbank.com', 'bikaskumar23@yahoo.com', 'aneeshalexander64@gmail.com', 'arunjosh71@sbi.co.in', 'amirtharajravichandran@gmail.com', 'sandee148@gmail.com', 'chaudharypriya42@yahoo.com', 'saimanojna18@gmail.com', 'anindoroy330.nalanda@gmail.com', 'ishanbansal620@gmail.com', 'bittuabhishekkarn@gmail.com', 'jaybagaria1234@gmail.com', 'sadeeshmuruga@gmail.com', 'psraja11ae068@gmail.com', 'nabinasim@gmail.com', 'vr281185@gmail.com', 'fabsna@gmail.com', 'sachinpbadole01@gmail.com', 'subhathraselvaraj@gmail.com', 'pratikmendhe.mendhe3@gmail.com', 'jino.george.97@gmail.com', 'sonu_bilal2003@yahoo.co.in', 'gujarathiakshay1@gmail.com', 'apoorv.katiyar.96@gmail.com', 'MOHDI8358@GMAIL.COM', 'j.u.nagendra@gmail.com', 'poojasharma299485@gmail.com', 'jayakumar.n@sbi.co.in', 'kesuvm@yahoo.com', 'josyj96@gmail.com', 'mayathevar@gmail.com', 'deepak.mahtha2@gmail.com', 'nagappak01@gmail.com', 'ankita.shrivastava96@gmail.com', 'paul.priya5@gmail.com', 'sharmaaakash.itm@gmail.com', 'bhaskarnadar4@gmail.com', 'parv.international@yahoo.com', 'prachipadaya98@gmail.com', 'princedubey323@gmail.com', 'kavithashanmugam18@gmail.com', 'nabanitamazumdar289@gmail.com', 'naveennk333@gmail.com', 'sauravjaiswal289@gmail.com', 'ramankumarrks3720@gmail.com', 'gaurav.mishra03@gmail.com', 'hiteshpandey06@gmail.com', 'joshideepali30@gmail.com', 'hridyaprakash1996@gmail.com', 'officialashishranjan01@gmail.com', 'ankitasingh872@gmail.com', 'pratibhabaheti19@gmail.com', 'ashish.agg1509@gmail.com', 'manugaur@kpmg.com', 'sinha92lovely@gmail.com', 'suniltoragal111@gmail.com', 'kubermakkar@kpmg.com', 'bajaj.shubham20@gmail.com', 'snehamanamohan@gmail.com', 'SHRAVANBGSK@gmail.com', 'nav.15choudhary@gmail.com', 'ankita.samaddar999@gmail.com', 'rahul_kul281281@rediffmail.com', 'msmageshwar@gmail.com', 'Ankitamahadik31@gmail.com', 'sayhiikiran@gmail.com');
        $email_or = '(';
        //$email_or = implode('description LIKE "%'.$email.'%" OR',$emails);
        foreach ($emails as $email) {
            $email_or .= 'description LIKE "%' . $email . '%" OR ';
            ///$this->db->like_or('description',$email,'both');
        }
        $email_or = rtrim($email_or, 'OR ');
        $email_or .= ')';
        //echo $email_or;
        //$this->db->where($email_or);
        $this->db->where_in('regnumber', $regnumbers);
        $this->db->where('DATE(date)', '2021-11-10');
        $this->db->like('title', 'Member exam apply details', 'both');
        $this->db->like('description', '"elearning_flag";s:1:"N"', 'both');
        $userlogs = $this->master_model->getRecords('userlogs_temp_10', array(), 'description');
        echo '<br/>userlog_qry : ' . $this->db->last_query();
        exit;
        //SELECT * FROM `member_exam` WHERE `exam_code` NOT IN(21,42,992) AND `pay_status` = 1 AND `created_on` LIKE '%2021-11-12%' AND `elearning_flag` = 'Y'
        //$exam_period = '221';
        $exam_code = array($this->config->item('examCodeJaiib'), $this->config->item('examCodeDBF'), $this->config->item('examCodeSOB'));
        $this->db->where_not_in('exam_code', $exam_code);
        $this->db->like('DATE(created_on)', '2021-11-10', 'both');
        $member_exam_data = $this->master_model->getRecords('member_exam', array('elearning_flag' => 'Y', 'pay_status' => '1'), 'regnumber,exam_code,exam_fee,elearning_flag,sub_el_count,created_on');
        echo '<br/>member_exam_qry : ' . $this->db->last_query();
        //print_r($member_exam_data);
        if (count($member_exam_data) > 0) {
            foreach ($member_exam_data as $member_exam_res) {
                ## check user logs for Member exam apply details
                $this->db->limit(1);
                $this->db->where('DATE(date)', $member_exam_res['created_on']);
                $this->db->like('title', 'Member exam apply details', 'both');
                $this->db->like('description', '"elearning_flag";s:1:"N"', 'both');
                $userlogs = $this->master_model->getRecords('userlogs', array('regnumber' => $member_exam_res['regnumber']), 'description');
                echo '<br/>userlog_qry : ' . $this->db->last_query();
                exit;
                if (count($userlogs) > 0) {
                    echo "<br/>Regnumber =>" . $member_exam_res['regnumber'];
                }
                exit;
            } //foreach
        } //if member_exam_data
    }

    ## function added by pratibha on 4 dec 2021
    public function caiib_member_elearning_settlement()
    {
        $this->load->helper('fee_helper');
        $exam_period = '221';
        $exam_code   = array($this->config->item('examCodeCaiib'), $this->config->item('examCodeCaiibElective63'), 65, $this->config->item('examCodeCaiibElective68'), $this->config->item('examCodeCaiibElective69'), $this->config->item('examCodeCaiibElective70'), 71);

        //$this->db->limit(3);
        $mem_exam_id = array(6196157, 6196252, 6196417, 6245951);
        $this->db->where_in('exam_code', $exam_code);
        $this->db->where_in('id', $mem_exam_id);
        //$member_exam_data = $this->master_model->getRecords('member_exam',array('exam_period'=>$exam_period,  'pay_status'=>'1','elearning_flag'=>'N','sub_el_count!='=>0),'*,DATE(created_on) as created_on');
        $member_exam_data = $this->master_model->getRecords('member_exam', array('exam_period' => $exam_period, 'pay_status' => '1'), '*,DATE(created_on) as created_on');
        echo '<br>member_exam_qry : ' . $this->db->last_query();
        echo '<br><br>====================== Count : ' . count($member_exam_data) . ' ===========================================================================<br>';

        if (count($member_exam_data) > 0) {
            foreach ($member_exam_data as $member_exam_res) {
                echo '<br><strong>id</strong> : ' . $member_exam_res['id'];
                echo '<br><strong>regnumber</strong> : ' . $member_exam_res['regnumber'];
                echo '<br><strong>created_on</strong> : ' . $created_on = $member_exam_res['created_on'];
                $payment_data                                           = $this->master_model->getRecords('payment_transaction', array('ref_id' => $member_exam_res['id']), 'id, receipt_no, ref_id', array('id' => 'DESC'), '', '1');
                '<br>payment_data_qry : ' . $this->db->last_query();

                if (count($payment_data) > 0) {
                    $exam_invoice_data = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $payment_data[0]['receipt_no']), '*', array('invoice_id' => 'DESC'), '', '1');
                    '<br>exam_invoice_data_qry : ' . $this->db->last_query();

                    if (count($exam_invoice_data) > 0) {
                        echo "<br/> <strong>State</strong>: " . $state_code = $exam_invoice_data[0]['state_of_center'];

                        $this->db->where_in('exam_code', $exam_invoice_data[0]['exam_code']);
                        $eligible_data = $this->master_model->getRecords('eligible_master', array('eligible_period' => $exam_period, 'member_no' => $exam_invoice_data[0]['member_no']), 'member_type, app_category', array('id' => 'DESC'), '', '1');
                        echo '<br><strong>eligible_data_qry</strong> : ' . $this->db->last_query();

                        if (count($eligible_data) > 0) {
                            if (isset($eligible_data[0]['app_category']) && $eligible_data[0]['app_category'] != "R") {
                                $grp_code = $eligible_data[0]['app_category'];
                            } else {
                                $grp_code = 'B1_1';
                            }

                            $group_code  = $grp_code;
                            $member_type = $eligible_data[0]['member_type'];
                        } else {
                            $this->db->where_in('regnumber', $exam_invoice_data[0]['member_no']);
                            $member_data = $this->master_model->getRecords('member_registration', array(), 'registrationtype', array('regid' => 'DESC'), '', '1');
                            $group_code  = 'B1_1';
                            $member_type = $member_data[0]['registrationtype'];
                            echo '<br>member_registration : ' . $this->db->last_query();
                        }
                        $center_code = $exam_invoice_data[0]['center_code'];
                        $exam_period = $exam_invoice_data[0]['exam_period'];
                        $exam_code   = $exam_invoice_data[0]['exam_code'];

                        $elearning_flag = 'Y';

                        $cgst_rate = $sgst_rate = $igst_rate = $tax_type = $cgst_amt = $sgst_amt = $igst_amt = $cs_total = $igst_total = '';
                        $getstate  = $getcenter  = array();

                        $total_el_amount = $total_elearning_amt = 0;
                        $el_subject_cnt  = $member_exam_res['sub_el_count'];
                        ## New elarning columns code
                        $total_el_base_amount = $total_el_gst_amount = $total_el_cgst_amount = $total_el_sgst_amount = $total_el_igst_amount = 0;

                        echo '<br/><strong>group_code</strong>: ' . $group_code;
                        echo '<br><strong>amount</strong> : ' . $amount = $this->getExamFee($center_code, $exam_period, base64_encode($exam_code), $group_code, $member_type, $elearning_flag, $el_subject_cnt, $created_on);
                        if ($member_exam_res['sub_el_count'] > 0 && ($exam_code == $this->config->item('examCodeCaiib') || $exam_code == 65)) {
                            echo '<br><strong>el_amount</strong> : ' . $el_amount = $this->get_el_ExamFee($center_code, $exam_period, base64_encode($exam_code), $group_code, $member_type, $elearning_flag, $created_on);

                            $total_elearning_amt = $el_amount * $el_subject_cnt;
                            $amount              = $amount + $total_elearning_amt;
                            ## New elarning columns code
                            $total_el_base_amount = $el_subject_cnt;
                            $total_el_cgst_amount = $el_subject_cnt;
                            $total_el_sgst_amount = $el_subject_cnt;
                            $total_el_igst_amount = $el_subject_cnt;
                        }
                        echo '<br>amount : ' . $amount;

                        $getfees = $this->getExamFeedetails($center_code, $exam_period, base64_encode($exam_code), $group_code, $member_type, $elearning_flag, $created_on);
                        print_r($getfees);
                        if (count($getfees) > 0) {
                            //echo '<pre>'; print_r($getfees); echo '</pre>';

                            if ($state_code == 'MAH') {
                                //set a rate (e.g 9%,9% or 18%)
                                $cgst_rate = $this->config->item('cgst_rate');
                                $sgst_rate = $this->config->item('sgst_rate');

                                if ($elearning_flag == 'Y') {
                                    //set an total amount
                                    if ($member_exam_res['sub_el_count'] > 0 && ($exam_code == $this->config->item('examCodeCaiib') || $exam_code == $this->config->item('examCodeCaiibElective63') || $exam_code == 65 || $exam_code == $this->config->item('examCodeCaiibElective68') || $exam_code == $this->config->item('examCodeCaiibElective69') || $exam_code == $this->config->item('examCodeCaiibElective70') || $exam_code == $this->config->item('examCodeCaiibElective71'))) {
                                        $cs_total        = $amount;
                                        $total_el_amount = $total_elearning_amt;
                                        $amount_base     = $getfees[0]['fee_amount'];
                                        $cgst_amt        = $getfees[0]['cgst_amt'];
                                        $sgst_amt        = $getfees[0]['sgst_amt'];
                                        ## New elarning columns code
                                        $total_el_base_amount = $total_el_base_amount * $getfees[0]['elearning_fee_amt'];
                                        $total_el_cgst_amount = $total_el_cgst_amount * $getfees[0]['elearning_cgst_amt'];
                                        $total_el_sgst_amount = $total_el_sgst_amount * $getfees[0]['elearning_sgst_amt'];
                                        $total_el_gst_amount  = $total_el_cgst_amount + $total_el_sgst_amount;
                                    } else {
                                        $cs_total        = $getfees[0]['elearning_cs_amt_total'];
                                        $total_el_amount = 0;
                                        $amount_base     = $getfees[0]['elearning_fee_amt'];

                                        $cgst_amt             = $getfees[0]['elearning_cgst_amt'];
                                        $sgst_amt             = $getfees[0]['elearning_sgst_amt'];
                                        $total_el_base_amount = 0;
                                        $total_el_gst_amount  = 0;
                                    }
                                } else {
                                    //set an amount as per rate
                                    $cgst_amt = $getfees[0]['cgst_amt'];
                                    $sgst_amt = $getfees[0]['sgst_amt'];
                                    //set an total amount
                                    $cs_total             = $getfees[0]['cs_tot'];
                                    $amount_base          = $getfees[0]['fee_amount'];
                                    $total_el_base_amount = 0;
                                    $total_el_gst_amount  = 0;
                                }
                                $tax_type = 'Intra';
                            } else {
                                if ($elearning_flag == 'Y') {
                                    $igst_rate = $this->config->item('igst_rate');

                                    if ($member_exam_res['sub_el_count'] > 0 && ($exam_code == $this->config->item('examCodeCaiib') || $exam_code == $this->config->item('examCodeCaiibElective63') || $exam_code == 65 || $exam_code == $this->config->item('examCodeCaiibElective68') || $exam_code == $this->config->item('examCodeCaiibElective69') || $exam_code == $this->config->item('examCodeCaiibElective70') || $exam_code == $this->config->item('examCodeCaiibElective71'))) {
                                        $igst_total      = $amount;
                                        $total_el_amount = $total_elearning_amt;
                                        $amount_base     = $getfees[0]['fee_amount'];
                                        $igst_amt        = $getfees[0]['igst_amt'];
                                        ## New elarning columns code
                                        $total_el_base_amount = $total_el_base_amount * $getfees[0]['elearning_fee_amt'];
                                        $total_el_igst_amount = $total_el_igst_amount * $getfees[0]['elearning_igst_amt'];
                                        $total_el_gst_amount  = $total_el_igst_amount;
                                    } else {
                                        $igst_total           = $getfees[0]['elearning_igst_amt_total'];
                                        $total_el_amount      = 0;
                                        $amount_base          = $getfees[0]['elearning_fee_amt'];
                                        $igst_amt             = $getfees[0]['elearning_igst_amt'];
                                        $total_el_base_amount = 0;
                                        $total_el_gst_amount  = 0;
                                    }
                                } else {
                                    $igst_rate   = $this->config->item('igst_rate');
                                    $igst_amt    = $getfees[0]['igst_amt'];
                                    $igst_total  = $getfees[0]['igst_tot'];
                                    $amount_base = $getfees[0]['fee_amount'];
                                    ## Code added on 6 Oct 2021
                                    $cgst_rate            = $cgst_amt            = $sgst_rate            = $sgst_amt            = $cs_total            = '';
                                    $total_el_base_amount = 0;
                                    $total_el_gst_amount  = 0;
                                }
                                $tax_type = 'Inter';
                            }

                            if ($exam_invoice_data[0]['exempt'] == 'E') {
                                $cgst_rate = $sgst_rate = $igst_rate = '';
                                $cgst_amt  = $sgst_amt  = $igst_amt  = '';
                            }

                            $gst_no = '0';

                            echo '<br>amount_base : ' . $amount_base;
                            echo '<br>cgst_amt : ' . $cgst_amt;
                            echo '<br>sgst_amt : ' . $sgst_amt;
                            echo '<br>cs_total : ' . $cs_total;
                            echo '<br>igst_amt : ' . $igst_amt;
                            echo '<br>igst_total : ' . $igst_total;
                            echo '<br>total_el_amount : ' . $total_el_amount;
                            echo '<br>total_el_base_amount : ' . $total_el_base_amount;
                            echo '<br>total_el_gst_amount : ' . $total_el_gst_amount;

                            $sbi_response = $this->sbiqueryapi($payment_data[0]['receipt_no']);
                            //echo '<pre>';
                            //print_r($sbi_response);
                            if ($state_code == 'MAH') {
                                echo '<br>' . $check_total = $cs_total;
                            } else {
                                echo '<br>' . $check_total = $igst_total;
                            }
                            if ($check_total == $sbi_response[7]) {
                                echo "<br/>Correct Fees paid to SBI so proceed further";
                                ## Update member exam elarning flag
                                $update_data = array('elearning_flag' => 'Y');
                                echo '<pre>';
                                print_r($update_data);
                                print_r(array('id' => $member_exam_res['id']));
                                /*$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$member_exam_res['id']));
                                $log_title ="Member exam update query from Custom_sm:".$member_exam_res['regnumber'];
                                $log_message = serialize($this->db->last_query());
                                $rId = $member_exam_res['id'];
                                $regNo = $member_exam_res['regnumber'];
                                storedUserActivity($log_title, $log_message, $rId, $regNo);*/

                                ## Update exam invoice fees
                                $update_data2 = array('total_el_amount' => $total_el_amount, 'total_el_base_amount' => $total_el_base_amount, 'total_el_gst_amount' => $total_el_gst_amount, 'cgst_amt' => $cgst_amt, 'sgst_amt' => $sgst_amt, 'cs_total' => $cs_total, 'igst_amt' => $igst_amt, 'igst_total' => $igst_total, 'modified_on' => date('Y-m-d H:i:s'));
                                print_r($update_data2);
                                print_r(array('receipt_no' => $payment_data[0]['receipt_no']));
                                echo '</pre>';

                                /*$this->master_model->updateRecord('exam_invoice',$update_data2,array('receipt_no'=>$payment_data[0]['receipt_no']));

                            $log_title ="Exam invoice update query from Custom_sm:".$member_exam_res['regnumber'];
                            $log_message = serialize($this->db->last_query());
                            $rId = $payment_data[0]['receipt_no'];
                            $regNo = $member_exam_res['regnumber'];
                            storedUserActivity($log_title, $log_message, $rId, $regNo);
                            }else{
                            echo "<br/>In Correct Fees paid to SBI so no action further => ".$payment_data[0]['receipt_no'];*/
                            }
                        } else {
                            echo '<br>Fee not found';
                        }
                    } else {
                        echo '<br>Exam invoice data not found';
                    }
                } else {
                    echo '<br>Payment data not found';
                }

                echo '<br><br>=================================================================================================<br>';
            }
        } else {
            echo '<br>Member exam data not found';
        }
    }

    //function added by sagar & Pratibha on 11-11-2021 to settled the JAIIB member Elearning flag and amount issue in ESDS db
    /* Process to follow
    SELECT * FROM `member_exam` WHERE `exam_period` = 221 AND `elearning_flag` = 'N' AND `sub_el_count` != 0 AND pay_status = 1

    SELECT * FROM `payment_transaction` WHERE `ref_id` = 5990080

    SELECT * FROM `exam_invoice` WHERE `receipt_no` = 902910117

    Calculate fee from Fee master

    https://115.124.123.26/sbisq/sbi_query_api.php?orderId=902910117
    if sbi fee == calculated fee then
    {
    update member exam elearning flag
    Update exam_invoice
    Regenrate invoice image
    Inform Pallavi for all new invoice images
    Pallavi will inform Datta to delete old invoce
    Update Pallavi to Push in PG
    }
    else
    {
    record regnumber
    }
     */
    //getRecords($tbl_name,$condition=FALSE,$select=FALSE,$order_by=FALSE,$start=FALSE,$limit=FALSE)
    public function jaiib_member_elearning_settlement()
    {
        $this->load->helper('fee_helper');
        $exam_period = '221';
        $exam_code   = array($this->config->item('examCodeDBF'));
        $m_id        = array(6048026, 6049744, 6049880, 6039086, 6049009, 6021254, 6051514, 6050292, 6049527, 6041895, 6036506, 6016665, 6037450);
        //$m_id = array(6048026,6049744);
        //$m_id = array(6011883);

        //$this->db->limit(50);
        $this->db->where_in('id', $m_id);
        $this->db->where_in('exam_code', $exam_code);
        $member_exam_data = $this->master_model->getRecords('member_exam', array('exam_period' => $exam_period, 'pay_status' => '1'), '*');
        echo '<br>member_exam_qry : ' . $this->db->last_query();
        echo '<br><br>====================== Count : ' . count($member_exam_data) . ' ===========================================================================<br>';

        if (count($member_exam_data) > 0) {
            foreach ($member_exam_data as $member_exam_res) {
                '<br>id : ' . $member_exam_res['id'];
                '<br>regnumber : ' . $member_exam_res['regnumber'];

                $payment_data = $this->master_model->getRecords('payment_transaction', array('ref_id' => $member_exam_res['id']), 'id, receipt_no, ref_id', array('id' => 'DESC'), '', '1');
                '<br>payment_data_qry : ' . $this->db->last_query();

                if (count($payment_data) > 0) {
                    $exam_invoice_data = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $payment_data[0]['receipt_no']), '*', array('invoice_id' => 'DESC'), '', '1');
                    '<br>exam_invoice_data_qry : ' . $this->db->last_query();

                    if (count($exam_invoice_data) > 0) {
                        echo "<br/> State: " . $state_code = $exam_invoice_data[0]['state_of_center'];

                        $this->db->where_in('exam_code', $exam_invoice_data[0]['exam_code']);
                        $eligible_data = $this->master_model->getRecords('eligible_master', array('eligible_period' => $exam_period, 'member_no' => $exam_invoice_data[0]['member_no']), 'member_type, app_category', array('id' => 'DESC'), '', '1');
                        echo '<br>eligible_data_qry : ' . $this->db->last_query();

                        if (count($eligible_data) > 0) {
                            if (isset($eligible_data[0]['app_category']) && $eligible_data[0]['app_category'] != "R") {
                                $grp_code = $eligible_data[0]['app_category'];
                            } else {
                                $grp_code = 'B1_1';
                            }

                            $group_code  = $grp_code;
                            $member_type = $eligible_data[0]['member_type'];
                        } else {
                            $this->db->where_in('regnumber', $exam_invoice_data[0]['member_no']);
                            $member_data = $this->master_model->getRecords('member_registration', array(), 'registrationtype', array('regid' => 'DESC'), '', '1');
                            $group_code  = 'B1_1';
                            $member_type = $member_data[0]['registrationtype'];
                            echo '<br>member_registration : ' . $this->db->last_query();
                        }
                        $center_code = $exam_invoice_data[0]['center_code'];
                        $exam_period = $exam_invoice_data[0]['exam_period'];
                        $exam_code   = $exam_invoice_data[0]['exam_code'];

                        $elearning_flag = 'Y';

                        $cgst_rate = $sgst_rate = $igst_rate = $tax_type = $cgst_amt = $sgst_amt = $igst_amt = $cs_total = $igst_total = '';
                        $getstate  = $getcenter  = array();

                        $total_el_amount = $total_elearning_amt = 0;
                        $el_subject_cnt  = $member_exam_res['sub_el_count'];
                        ## New elarning columns code
                        $total_el_base_amount = $total_el_gst_amount = $total_el_cgst_amount = $total_el_sgst_amount = $total_el_igst_amount = 0;

                        echo '<br/>group_code: ' . $group_code;
                        echo '<br>amount : ' . $amount = $this->getExamFee($center_code, $exam_period, base64_encode($exam_code), $group_code, $member_type, $elearning_flag, $el_subject_cnt);

                        if ($member_exam_res['sub_el_count'] > 0 && ($exam_code == $this->config->item('examCodeJaiib') || $exam_code == $this->config->item('examCodeDBF') || $exam_code == $this->config->item('examCodeSOB'))) {
                            echo '<br>el_amount : ' . $el_amount = $this->get_el_ExamFee($center_code, $exam_period, base64_encode($exam_code), $group_code, $member_type, $elearning_flag);

                            $total_elearning_amt = $el_amount * $el_subject_cnt;
                            $amount              = $amount + $total_elearning_amt;
                            ## New elarning columns code
                            $total_el_base_amount = $el_subject_cnt;
                            $total_el_cgst_amount = $el_subject_cnt;
                            $total_el_sgst_amount = $el_subject_cnt;
                            $total_el_igst_amount = $el_subject_cnt;
                        }
                        echo '<br>amount : ' . $amount;

                        $getfees = $this->getExamFeedetails($center_code, $exam_period, base64_encode($exam_code), $group_code, $member_type, $elearning_flag);
                        print_r($getfees);
                        if (count($getfees) > 0) {
                            //echo '<pre>'; print_r($getfees); echo '</pre>';

                            if ($state_code == 'MAH') {
                                //set a rate (e.g 9%,9% or 18%)
                                $cgst_rate = $this->config->item('cgst_rate');
                                $sgst_rate = $this->config->item('sgst_rate');

                                if ($elearning_flag == 'Y') {
                                    //set an total amount
                                    if ($member_exam_res['sub_el_count'] > 0 && ($exam_code == $this->config->item('examCodeJaiib') || $exam_code == $this->config->item('examCodeDBF') || $exam_code == $this->config->item('examCodeSOB'))) {
                                        $cs_total        = $amount;
                                        $total_el_amount = $total_elearning_amt;
                                        $amount_base     = $getfees[0]['fee_amount'];
                                        $cgst_amt        = $getfees[0]['cgst_amt'];
                                        $sgst_amt        = $getfees[0]['sgst_amt'];
                                        ## New elarning columns code
                                        $total_el_base_amount = $total_el_base_amount * $getfees[0]['elearning_fee_amt'];
                                        $total_el_cgst_amount = $total_el_cgst_amount * $getfees[0]['elearning_cgst_amt'];
                                        $total_el_sgst_amount = $total_el_sgst_amount * $getfees[0]['elearning_sgst_amt'];
                                        $total_el_gst_amount  = $total_el_cgst_amount + $total_el_sgst_amount;
                                    } else {
                                        $cs_total        = $getfees[0]['elearning_cs_amt_total'];
                                        $total_el_amount = 0;
                                        $amount_base     = $getfees[0]['elearning_fee_amt'];

                                        $cgst_amt             = $getfees[0]['elearning_cgst_amt'];
                                        $sgst_amt             = $getfees[0]['elearning_sgst_amt'];
                                        $total_el_base_amount = 0;
                                        $total_el_gst_amount  = 0;
                                    }
                                } else {
                                    //set an amount as per rate
                                    $cgst_amt = $getfees[0]['cgst_amt'];
                                    $sgst_amt = $getfees[0]['sgst_amt'];
                                    //set an total amount
                                    $cs_total             = $getfees[0]['cs_tot'];
                                    $amount_base          = $getfees[0]['fee_amount'];
                                    $total_el_base_amount = 0;
                                    $total_el_gst_amount  = 0;
                                }
                                $tax_type = 'Intra';
                            } else {
                                if ($elearning_flag == 'Y') {
                                    $igst_rate = $this->config->item('igst_rate');

                                    if ($member_exam_res['sub_el_count'] > 0 && ($exam_code == $this->config->item('examCodeJaiib') || $exam_code == $this->config->item('examCodeDBF') || $exam_code == $this->config->item('examCodeSOB'))) {
                                        $igst_total      = $amount;
                                        $total_el_amount = $total_elearning_amt;
                                        $amount_base     = $getfees[0]['fee_amount'];
                                        $igst_amt        = $getfees[0]['igst_amt'];
                                        ## New elarning columns code
                                        $total_el_base_amount = $total_el_base_amount * $getfees[0]['elearning_fee_amt'];
                                        $total_el_igst_amount = $total_el_igst_amount * $getfees[0]['elearning_igst_amt'];
                                        $total_el_gst_amount  = $total_el_igst_amount;
                                    } else {
                                        $igst_total           = $getfees[0]['elearning_igst_amt_total'];
                                        $total_el_amount      = 0;
                                        $amount_base          = $getfees[0]['elearning_fee_amt'];
                                        $igst_amt             = $getfees[0]['elearning_igst_amt'];
                                        $total_el_base_amount = 0;
                                        $total_el_gst_amount  = 0;
                                    }
                                } else {
                                    $igst_rate   = $this->config->item('igst_rate');
                                    $igst_amt    = $getfees[0]['igst_amt'];
                                    $igst_total  = $getfees[0]['igst_tot'];
                                    $amount_base = $getfees[0]['fee_amount'];
                                    ## Code added on 6 Oct 2021
                                    $cgst_rate            = $cgst_amt            = $sgst_rate            = $sgst_amt            = $cs_total            = '';
                                    $total_el_base_amount = 0;
                                    $total_el_gst_amount  = 0;
                                }
                                $tax_type = 'Inter';
                            }

                            if ($exam_invoice_data[0]['exempt'] == 'E') {
                                $cgst_rate = $sgst_rate = $igst_rate = '';
                                $cgst_amt  = $sgst_amt  = $igst_amt  = '';
                            }

                            $gst_no = '0';

                            echo '<br>amount_base : ' . $amount_base;
                            echo '<br>cgst_amt : ' . $cgst_amt;
                            echo '<br>sgst_amt : ' . $sgst_amt;
                            echo '<br>cs_total : ' . $cs_total;
                            echo '<br>igst_amt : ' . $igst_amt;
                            echo '<br>igst_total : ' . $igst_total;
                            echo '<br>total_el_amount : ' . $total_el_amount;
                            echo '<br>total_el_base_amount : ' . $total_el_base_amount;
                            echo '<br>total_el_gst_amount : ' . $total_el_gst_amount;

                            $sbi_response = $this->sbiqueryapi($payment_data[0]['receipt_no']);
                            //echo '<pre>';
                            //print_r($sbi_response);
                            if ($state_code == 'MAH') {
                                echo $check_total = $cs_total;
                            } else {
                                echo $check_total = $igst_total;
                            }
                            if ($check_total == $sbi_response[7]) {
                                echo "<br/>Correct Fees paid to SBI so proceed further";
                                ## Update member exam elarning flag
                                $update_data = array('elearning_flag' => 'Y');
                                echo '<pre>';
                                print_r($update_data);
                                print_r(array('id' => $member_exam_res['id']));
                                /*$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$member_exam_res['id']));
                                $log_title ="Member exam update query from Custom_sm:".$member_exam_res['regnumber'];
                                $log_message = serialize($this->db->last_query());
                                $rId = $member_exam_res['id'];
                                $regNo = $member_exam_res['regnumber'];
                                storedUserActivity($log_title, $log_message, $rId, $regNo);    */

                                ## Update exam invoice fees
                                $update_data2 = array('total_el_amount' => $total_el_amount, 'total_el_base_amount' => $total_el_base_amount, 'total_el_gst_amount' => $total_el_gst_amount, 'cgst_amt' => $cgst_amt, 'sgst_amt' => $sgst_amt, 'cs_total' => $cs_total, 'igst_amt' => $igst_amt, 'igst_total' => $igst_total, 'modified_on' => date('Y-m-d H:i:s'));
                                print_r($update_data2);
                                print_r(array('receipt_no' => $payment_data[0]['receipt_no']));
                                echo '</pre>';

                                $this->master_model->updateRecord('exam_invoice', $update_data2, array('receipt_no' => $payment_data[0]['receipt_no']));

                                $log_title   = "Exam invoice update query from Custom_sm:" . $member_exam_res['regnumber'];
                                $log_message = serialize($this->db->last_query());
                                $rId         = $payment_data[0]['receipt_no'];
                                $regNo       = $member_exam_res['regnumber'];
                                storedUserActivity($log_title, $log_message, $rId, $regNo);
                            } else {
                                echo "<br/>In Correct Fees paid to SBI so no action further => " . $payment_data[0]['receipt_no'];
                            }
                        } else {
                            echo '<br>Fee not found';
                        }
                    } else {
                        echo '<br>Exam invoice data not found';
                    }
                } else {
                    echo '<br>Payment data not found';
                }

                echo '<br><br>=================================================================================================<br>';
            }
        } else {
            echo '<br>Member exam data not found';
        }
    }

    //getRecords($tbl_name,$condition=FALSE,$select=FALSE,$order_by=FALSE,$start=FALSE,$limit=FALSE)
    public function jaiib_member_elearning_settlement_invoice()
    {
        $this->load->helper('fee_helper');
        $exam_period = '121';
        $exam_code   = array($this->config->item('examCodeJaiib'));
        $receipt_no  = array(903104572);
        //$m_id = array(6011883);
        $chk_date = '2021-12-06';
        //$this->db->limit(50);
        $this->db->where_in('receipt_no', $receipt_no);
        $this->db->where_in('exam_code', $exam_code);
        $exam_invoice_data = $this->master_model->getRecords('exam_invoice', array('exam_period' => $exam_period, 'invoice_no !=' => ''), '*');
        //, 'total_el_amount'=>'0.00'
        echo '<br>exam_invoice_qry : ' . $this->db->last_query();
        echo '<br><br>====================== Count : ' . count($exam_invoice_data) . ' ===========================================================================<br>';
        //exit;
        if (count($exam_invoice_data) > 0) {
            foreach ($exam_invoice_data as $exam_invoice_data) {
                /* if(count($payment_data) > 0)
                { */
                /* $exam_invoice_data = $this->master_model->getRecords('exam_invoice',array('receipt_no'=>$payment_data[0]['receipt_no']),'*', array('invoice_id'=>'DESC'), '', '1');
                '<br>exam_invoice_data_qry : '.$this->db->last_query(); */

                /*     if(count($exam_invoice_data) > 0)
                { */
                echo "<br/> State: " . $state_code = $exam_invoice_data['state_of_center'];

                $this->db->where_in('exam_code', $exam_invoice_data['exam_code']);
                $eligible_data = $this->master_model->getRecords('eligible_master', array('eligible_period' => $exam_period, 'member_no' => $exam_invoice_data['member_no']), 'member_type, app_category', array('id' => 'DESC'), '', '1');
                echo '<br>eligible_data_qry : ' . $this->db->last_query();

                if (count($eligible_data) > 0) {
                    if (isset($eligible_data[0]['app_category']) && $eligible_data[0]['app_category'] != "R") {
                        $grp_code = $eligible_data[0]['app_category'];
                    } else {
                        $grp_code = 'B1_1';
                    }

                    $group_code  = $grp_code;
                    $member_type = $eligible_data[0]['member_type'];
                } else {
                    $this->db->where_in('regnumber', $exam_invoice_data['member_no']);
                    $member_data = $this->master_model->getRecords('member_registration', array(), 'registrationtype', array('regid' => 'DESC'), '', '1');
                    $group_code  = 'B1_1';
                    $member_type = $member_data[0]['registrationtype'];
                    echo '<br>member_registration : ' . $this->db->last_query();
                }
                $center_code = $exam_invoice_data['center_code'];
                $exam_period = $exam_invoice_data['exam_period'];
                $exam_code   = $exam_invoice_data['exam_code'];

                $elearning_flag = 'Y';

                $cgst_rate = $sgst_rate = $igst_rate = $tax_type = $cgst_amt = $sgst_amt = $igst_amt = $cs_total = $igst_total = '';
                $getstate  = $getcenter  = array();

                $this->db->where_in('exam_code', $exam_code);
                $member_exam_data = $this->master_model->getRecords('member_exam', array('exam_period' => $exam_period, 'elearning_flag' => 'Y', 'sub_el_count !=' => '0', 'pay_status' => '1'), '*');

                $total_el_amount = $total_elearning_amt = 0;
                $el_subject_cnt  = $member_exam_data[0]['sub_el_count'];
                ## New elarning columns code
                $total_el_base_amount = $total_el_gst_amount = $total_el_cgst_amount = $total_el_sgst_amount = $total_el_igst_amount = 0;

                echo '<br/>group_code: ' . $group_code;
                echo '<br>amount : ' . $amount = $this->getExamFee($center_code, $exam_period, base64_encode($exam_code), $group_code, $member_type, $elearning_flag, $el_subject_cnt, $chk_date);

                if ($member_exam_data[0]['sub_el_count'] > 0 && ($exam_code == $this->config->item('examCodeJaiib') || $exam_code == $this->config->item('examCodeDBF') || $exam_code == $this->config->item('examCodeSOB'))) {
                    '<br>el_amount : ' . $el_amount = $this->get_el_ExamFee($center_code, $exam_period, base64_encode($exam_code), $group_code, $member_type, $elearning_flag, $chk_date);

                    $total_elearning_amt = $el_amount * $el_subject_cnt;
                    $amount              = $amount + $total_elearning_amt;
                    ## New elarning columns code
                    $total_el_base_amount = $el_subject_cnt;
                    $total_el_cgst_amount = $el_subject_cnt;
                    $total_el_sgst_amount = $el_subject_cnt;
                    $total_el_igst_amount = $el_subject_cnt;
                }
                echo '<br>amount : ' . $amount;

                $getfees = $this->getExamFeedetails($center_code, $exam_period, base64_encode($exam_code), $group_code, $member_type, $elearning_flag, $chk_date);
                print_r($getfees);
                if (count($getfees) > 0) {
                    //echo '<pre>'; print_r($getfees); echo '</pre>';

                    if ($state_code == 'MAH') {
                        //set a rate (e.g 9%,9% or 18%)
                        $cgst_rate = $this->config->item('cgst_rate');
                        $sgst_rate = $this->config->item('sgst_rate');

                        if ($elearning_flag == 'Y') {
                            //set an total amount
                            if ($member_exam_data[0]['sub_el_count'] > 0 && ($exam_code == $this->config->item('examCodeJaiib') || $exam_code == $this->config->item('examCodeDBF') || $exam_code == $this->config->item('examCodeSOB'))) {
                                $cs_total        = $amount;
                                $total_el_amount = $total_elearning_amt;
                                $amount_base     = $getfees[0]['fee_amount'];
                                $cgst_amt        = $getfees[0]['cgst_amt'];
                                $sgst_amt        = $getfees[0]['sgst_amt'];
                                ## New elarning columns code
                                $total_el_base_amount = $total_el_base_amount * $getfees[0]['elearning_fee_amt'];
                                $total_el_cgst_amount = $total_el_cgst_amount * $getfees[0]['elearning_cgst_amt'];
                                $total_el_sgst_amount = $total_el_sgst_amount * $getfees[0]['elearning_sgst_amt'];
                                $total_el_gst_amount  = $total_el_cgst_amount + $total_el_sgst_amount;
                            } else {
                                $cs_total        = $getfees[0]['elearning_cs_amt_total'];
                                $total_el_amount = 0;
                                $amount_base     = $getfees[0]['elearning_fee_amt'];

                                $cgst_amt             = $getfees[0]['elearning_cgst_amt'];
                                $sgst_amt             = $getfees[0]['elearning_sgst_amt'];
                                $total_el_base_amount = 0;
                                $total_el_gst_amount  = 0;
                            }
                        } else {
                            //set an amount as per rate
                            $cgst_amt = $getfees[0]['cgst_amt'];
                            $sgst_amt = $getfees[0]['sgst_amt'];
                            //set an total amount
                            $cs_total             = $getfees[0]['cs_tot'];
                            $amount_base          = $getfees[0]['fee_amount'];
                            $total_el_base_amount = 0;
                            $total_el_gst_amount  = 0;
                        }
                        $tax_type = 'Intra';
                    } else {
                        if ($elearning_flag == 'Y') {
                            $igst_rate = $this->config->item('igst_rate');

                            if ($member_exam_data[0]['sub_el_count'] > 0 && ($exam_code == $this->config->item('examCodeJaiib') || $exam_code == $this->config->item('examCodeDBF') || $exam_code == $this->config->item('examCodeSOB'))) {
                                $igst_total      = $amount;
                                $total_el_amount = $total_elearning_amt;
                                $amount_base     = $getfees[0]['fee_amount'];
                                $igst_amt        = $getfees[0]['igst_amt'];
                                ## New elarning columns code
                                $total_el_base_amount = $total_el_base_amount * $getfees[0]['elearning_fee_amt'];
                                $total_el_igst_amount = $total_el_igst_amount * $getfees[0]['elearning_igst_amt'];
                                $total_el_gst_amount  = $total_el_igst_amount;
                            } else {
                                $igst_total           = $getfees[0]['elearning_igst_amt_total'];
                                $total_el_amount      = 0;
                                $amount_base          = $getfees[0]['elearning_fee_amt'];
                                $igst_amt             = $getfees[0]['elearning_igst_amt'];
                                $total_el_base_amount = 0;
                                $total_el_gst_amount  = 0;
                            }
                        } else {
                            $igst_rate   = $this->config->item('igst_rate');
                            $igst_amt    = $getfees[0]['igst_amt'];
                            $igst_total  = $getfees[0]['igst_tot'];
                            $amount_base = $getfees[0]['fee_amount'];
                            ## Code added on 6 Oct 2021
                            $cgst_rate            = $cgst_amt            = $sgst_rate            = $sgst_amt            = $cs_total            = '';
                            $total_el_base_amount = 0;
                            $total_el_gst_amount  = 0;
                        }
                        $tax_type = 'Inter';
                    }

                    if ($exam_invoice_data['exempt'] == 'E') {
                        $cgst_rate = $sgst_rate = $igst_rate = '';
                        $cgst_amt  = $sgst_amt  = $igst_amt  = '';
                    }

                    $gst_no = '0';

                    /* echo '<br>amount_base : '.$amount_base;
                    echo '<br>cgst_amt : '.$cgst_amt;
                    echo '<br>sgst_amt : '.$sgst_amt;
                    echo '<br>cs_total : '.$cs_total;
                    echo '<br>igst_amt : '.$igst_amt;
                    echo '<br>igst_total : '.$igst_total;
                    echo '<br>total_el_amount : '.$total_el_amount;
                    echo '<br>total_el_base_amount : '.$total_el_base_amount;
                    echo '<br>total_el_gst_amount : '.$total_el_gst_amount; exit; */

                    $sbi_response = $this->sbiqueryapi($exam_invoice_data['receipt_no']);
                    //echo '<pre>';
                    //print_r($sbi_response);
                    if ($state_code == 'MAH') {
                        $check_total = $cs_total;
                    } else {
                        $check_total = $igst_total;
                    }
                    if ($check_total == $sbi_response[7]) {
                        echo "<br/>Correct Fees paid to SBI so proceed further";
                        ## Update member exam elarning flag
                        $update_data = array('elearning_flag' => 'Y');
                        echo '<pre>';
                        print_r($update_data);
                        print_r(array('id' => $member_exam_data[0]['id']));
                        /*$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$member_exam_res['id']));
                        $log_title ="Member exam update query from Custom_sm:".$member_exam_res['regnumber'];
                        $log_message = serialize($this->db->last_query());
                        $rId = $member_exam_res['id'];
                        $regNo = $member_exam_res['regnumber'];
                        storedUserActivity($log_title, $log_message, $rId, $regNo);    */

                        ## Update exam invoice fees
                        $update_data2 = array('total_el_amount' => $total_el_amount, 'total_el_base_amount' => $total_el_base_amount, 'total_el_gst_amount' => $total_el_gst_amount, 'cgst_amt' => $cgst_amt, 'sgst_amt' => $sgst_amt, 'cs_total' => $cs_total, 'igst_amt' => $igst_amt, 'igst_total' => $igst_total, 'modified_on' => date('Y-m-d H:i:s'));
                        print_r($update_data2);
                        print_r(array('receipt_no' => $exam_invoice_data['receipt_no']));
                        echo '</pre>';
                        exit;
                        $this->master_model->updateRecord('exam_invoice', $update_data2, array('receipt_no' => $exam_invoice_data['receipt_no']));

                        $log_title   = "Exam invoice update query from Custom_sm:" . $member_exam_data[0]['regnumber'];
                        $log_message = serialize($this->db->last_query());
                        $rId         = $exam_invoice_data['receipt_no'];
                        $regNo       = $member_exam_data[0]['regnumber'];
                        storedUserActivity($log_title, $log_message, $rId, $regNo);
                    } else {
                        echo "<br/>In Correct Fees paid to SBI so no action further => " . $exam_invoice_data['receipt_no'];
                    }
                } else {
                    echo '<br>Fee not found';
                }

                /* }
                else
                {
                echo '<br>Exam invoice data not found';
                } */
                //}
                /* else
                {
                echo '<br>Payment data not found';
                } */

                echo '<br><br>=================================================================================================<br>';
            }
        } else {
            echo '<br>Member exam data not found';
        }
    }

    public function getExamFee($centerCode = null, $eprid = null, $excd = null, $grp_code = null, $memcategory = null, $elearning_flag = null, $el_subject_cnt = null, $chk_date = null)
    {
        $fee = 0;
        $CI  = &get_instance();
        //$centerCode= '306';
        //$eprid= '221';
        //$excd= base64_encode('71');
        //$grp_code= 'S1';
        //$memcategory = 'O';
        if ($centerCode != null && $eprid != null && $excd != null && $grp_code != null && $memcategory != null) {
            $getstate = $CI->master_model->getRecords('center_master', array('exam_name' => base64_decode($excd), 'center_code' => $centerCode, 'exam_period' => $eprid, 'center_delete' => '0'));
            echo '<br><strong>getstate</strong> : ' . $CI->db->last_query();
            if (count($getstate) <= 0) {
                $getstate = $CI->master_model->getRecords('center_master', array('exam_name' => $excd, 'center_code' => $centerCode, 'exam_period' => $eprid, 'center_delete' => '0'));
            }

            if (count($getstate) > 0) {
                $getstatedetails = $CI->master_model->getRecords('state_master', array('state_code' => $getstate[0]['state_code'], 'state_delete' => '0'));

                if ($grp_code != '') {
                    if (substr($grp_code, 0, 1) == 'S') {
                        $grp_code = 'S1';
                    }
                } else {

                    $grp_code = 'B1_1';
                }

                $today_date = $chk_date; //'2021-12-06';//date('Y-m-d');

                $CI->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
                $getfees = $CI->master_model->getRecords('fee_master', array('exam_code' => base64_decode($excd), 'member_category' => $memcategory, 'exam_period' => $eprid, 'group_code' => $grp_code, 'exempt' => $getstatedetails[0]['exempt']));
                echo '<br/> <strong>Fee</strong>: ' . $CI->db->last_query();
                echo '<br/> <strong>elearning_flag</strong>: ' . $elearning_flag;
                if (count($getfees) <= 0) {
                    $getfees = $CI->master_model->getRecords('fee_master', array('exam_code' => base64_decode($excd), 'member_category' => $memcategory, 'exam_period' => $eprid, 'group_code' => $grp_code));
                    //echo '<br/> Fee: '.$CI->db->last_query();exit;
                }
                if (count($getfees) > 0) {
                    if ($getstate[0]['state_code'] == 'MAH') {
                        if ($elearning_flag == 'Y') {
                            if ($el_subject_cnt > 0 && (base64_decode($excd) == $this->config->item('examCodeJaiib') || base64_decode($excd) == $this->config->item('examCodeDBF') || base64_decode($excd) == $this->config->item('examCodeSOB') || base64_decode($excd) == $this->config->item('examCodeCaiib') || base64_decode($excd) == 65)) {
                                $fee = $getfees[0]['cs_tot'];
                            } else {
                                $fee = $getfees[0]['elearning_cs_amt_total'];
                            }
                        } else {
                            $fee = $getfees[0]['cs_tot'];
                        }
                    } else {
                        if ($elearning_flag == 'Y') {

                            if ($el_subject_cnt > 0 && (base64_decode($excd) == $this->config->item('examCodeJaiib') || base64_decode($excd) == $this->config->item('examCodeDBF') || base64_decode($excd) == $this->config->item('examCodeSOB') || base64_decode($excd) == $this->config->item('examCodeCaiib') || base64_decode($excd) == 65)) {
                                $fee = $getfees[0]['igst_tot'];
                            } else {
                                $fee = $getfees[0]['elearning_igst_amt_total'];
                            }
                        } else {
                            $fee = $getfees[0]['igst_tot'];
                        }
                    }
                }
            }
        }
        //print_r($fee);
        return $fee;
    }

    // Get fee only for JAIIB multiple subject elearning selection
    public function get_el_ExamFee($centerCode = null, $eprid = null, $excd = null, $grp_code = null, $memcategory = null, $elearning_flag = null, $chk_date = null)
    {
        $fee = 0;
        $CI  = &get_instance();
        //$centerCode= $_POST['centerCode'];
        //$eprid=$_POST['eprid'];
        //    $excd=$_POST['excd'];
        //$grp_code=$_POST['grp_code'];

        if ($centerCode != null && $eprid != null && $excd != null && $grp_code != null && $memcategory != null) {
            $getstate = $CI->master_model->getRecords('center_master', array('exam_name' => base64_decode($excd), 'center_code' => $centerCode, 'exam_period' => $eprid, 'center_delete' => '0'));
            //echo $CI->db->last_query();exit;
            if (count($getstate) <= 0) {
                $getstate = $CI->master_model->getRecords('center_master', array('exam_name' => $excd, 'center_code' => $centerCode, 'exam_period' => $eprid, 'center_delete' => '0'));
            }

            if (count($getstate) > 0) {
                $getstatedetails = $CI->master_model->getRecords('state_master', array('state_code' => $getstate[0]['state_code'], 'state_delete' => '0'));

                if ($grp_code != '') {
                    if (substr($grp_code, 0, 1) == 'S') {
                        $grp_code = 'S1';
                    }
                } else {

                    $grp_code = 'B1_1';
                }

                $today_date = $chk_date; //'2021-12-01';//date('Y-m-d');
                //$today_date='2017-08-15';
                // $this->db->where('('."'".$today_date."'". ' BETWEEN DATE_FORMAT(`fr_date`,"%y-%m-%d") AND DATE_FORMAT(`to_date`, "%y-%m-%d"))');
                $CI->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
                $getfees = $CI->master_model->getRecords('fee_master', array('exam_code' => base64_decode($excd), 'member_category' => $memcategory, 'exam_period' => $eprid, 'group_code' => $grp_code, 'exempt' => $getstatedetails[0]['exempt']));
                //echo $CI->db->last_query();exit;
                if (count($getfees) <= 0) {
                    $getfees = $CI->master_model->getRecords('fee_master', array('exam_code' => $excd, 'member_category' => $memcategory, 'exam_period' => $eprid, 'group_code' => $grp_code));
                }
                if (count($getfees) > 0) {
                    if ($getstate[0]['state_code'] == 'MAH') {
                        if ($elearning_flag == 'Y') {
                            $fee = $getfees[0]['elearning_cs_amt_total'];
                        } else {
                            $fee = $getfees[0]['elearning_cs_amt_total'];
                        }
                    } else {
                        if ($elearning_flag == 'Y') {
                            $fee = $getfees[0]['elearning_igst_amt_total'];
                        } else {
                            $fee = $getfees[0]['elearning_igst_amt_total'];
                        }
                    }
                }
            }
        }
        return $fee;
    }

    public function getExamFeedetails($centerCode = null, $eprid = null, $excd = null, $grp_code = null, $memcategory = null, $elearning_flag = null, $chk_date = null)
    {
        $getfees = array();
        $fee     = 0;
        $CI      = &get_instance();
        //$centerCode= $_POST['centerCode'];
        //$eprid=$_POST['eprid'];
        //    $excd=$_POST['excd'];
        //$grp_code=$_POST['grp_code'];

        if ($centerCode != null && $eprid != null && $excd != null && $grp_code != null && $memcategory != null) {
            $getstate = $CI->master_model->getRecords('center_master', array('exam_name' => base64_decode($excd), 'center_code' => $centerCode, 'exam_period' => $eprid, 'center_delete' => '0'));
            //echo $CI->db->last_query();exit;
            if (count($getstate) <= 0) {
                $getstate = $CI->master_model->getRecords('center_master', array('exam_name' => $excd, 'center_code' => $centerCode, 'exam_period' => $eprid, 'center_delete' => '0'));
            }

            if (count($getstate) > 0) {
                $getstatedetails = $CI->master_model->getRecords('state_master', array('state_code' => $getstate[0]['state_code'], 'state_delete' => '0'));
                if ($grp_code != '') {
                    if (substr($grp_code, 0, 1) == 'S') {
                        $grp_code = 'S1';
                    }
                } else {

                    $grp_code = 'B1_1';
                }
                //$today_date=date('Y-m-d');
                $today_date = $chk_date; //'2021-12-01';
                // $this->db->where('('."'".$today_date."'". ' BETWEEN DATE_FORMAT(`fr_date`,"%y-%m-%d") AND DATE_FORMAT(`to_date`, "%y-%m-%d"))');

                //$getfees=$CI->master_model->getRecords('fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$memcategory,'exam_period'=>$eprid,'group_code'=>$grp_code,'exempt'=>$getstatedetails[0]['exempt']));
                $CI->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
                $getfees = $CI->master_model->getRecords('fee_master', array('exam_code' => base64_decode($excd), 'member_category' => $memcategory, 'exam_period' => $eprid, 'group_code' => $grp_code));
                //echo $CI->db->last_query();exit;
                if (count($getfees) <= 0) {
                    $getfees = $CI->master_model->getRecords('fee_master', array('exam_code' => $excd, 'member_category' => $memcategory, 'exam_period' => $eprid, 'group_code' => $grp_code));
                }

                /*if(count($getfees) > 0)
            {
            if($getstate[0]['state_code']=='MAH')
            {
            $fee=$getfees[0]['cs_tot'];
            }
            else
            {
            $fee=$getfees[0]['igst_tot'];
            }
            }*/
            }
        }
        return $getfees;
    }

    public function sbiqueryapi($MerchantOrderNo)
    {
        $service_url = "https://www.sbiepay.sbi/payagg/orderStatusQuery/getOrderStatusQuery";

        $merchIdVal   = "1000169";
        $AggregatorId = "SBIEPAY";

        $atrn = "";

        $queryRequest = $atrn . "|" . $merchIdVal . "|" . $MerchantOrderNo;

        $post_param = "queryRequest=" . $queryRequest . "&aggregatorId=" . $AggregatorId . "&merchantId=" . $merchIdVal;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $service_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_param);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $info   = curl_getinfo($ch);
        $result = curl_exec($ch);
        curl_close($ch);

        //print_r($info);

        if ($result) {
            $response_array = explode("|", $result);

            return $response_array;
        } else {
            return 0;
        }
    }

    public function generate_credit_note()
    {
        exit;
        //echo $transaction_no; exit;
        $transaction_no = '8948299135037';

        $payment_txn = $this->master_model->getRecords('payment_transaction', array('transaction_no' => $transaction_no), 'receipt_no, id, pay_type');

        $invoice_info = $this->master_model->getRecords('exam_invoice', array('pay_txn_id' => $payment_txn[0]['id']));
        /*echo $this->db->last_query();
        echo '<pre>';
        print_r($invoice_info);*/

        if ($payment_txn[0]['pay_type'] == 4) //FOR DRA MEMBER : CODE ADDED BY SAGAR ON 05-04-2021 TO GENERATE CREDIT NOTE
        {
            $mem_info = $this->master_model->getRecords('dra_members', array('regnumber' => $invoice_info[0]['member_no']), 'firstname,middlename,lastname,address1,address2,address3,address4,district,city,pincode');
        } else {
            $mem_info = $this->master_model->getRecords('member_registration', array('regnumber' => $invoice_info[0]['member_no']), 'firstname,middlename,lastname,address1,address2,address3,address4,district,city,pincode');
        }

        if (count($mem_info) > 0) {
            $member_name = $mem_info[0]['firstname'] . " " . $mem_info[0]['middlename'] . " " . $mem_info[0]['lastname'];

            $address1 = $mem_info[0]['address1'] . " " . $mem_info[0]['address2'] . " " . $mem_info[0]['address3'] . " " . $mem_info[0]['address4'];

            $address2 = $mem_info[0]['district'] . " " . $mem_info[0]['city'] . " " . $mem_info[0]['pincode'];
        } else {
            $member_name = $address1 = $address2 = "";
        }

        if ($invoice_info[0]['center_name'] != '') {
            $city = $invoice_info[0]['center_name'];
        } else {
            $city = '';
        }

        if ($invoice_info[0]['state_of_center'] == 'MAH') {
            $wordamt = amtinword($invoice_info[0]['cs_total']);
        } elseif ($invoice_info[0]['state_of_center'] != 'MAH') {
            $wordamt = amtinword($invoice_info[0]['igst_total']);
        }

        //echo "<br>".$wordamt; exit;

        $date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));

        $exp          = explode("/", $invoice_info[0]['invoice_no']);
        $cr_imagename = "CN_" . $exp[0] . "_" . $exp[1] . "_" . $exp[2] . ".jpg";

        $chk_config = $this->master_model->getRecords('config_credit_note', array('invoice_id' => $invoice_info[0]['invoice_id']));

        if (count($chk_config) == 0) {
            $config_inset_arr = array(
                'invoice_id'   => $invoice_info[0]['invoice_id'],
                'created_date' => date('Y-m-d H:i:s'),
            );
            $config_last_id = str_pad($this->master_model->insertRecord('config_credit_note', $config_inset_arr, true), 5, "0", STR_PAD_LEFT);
        } else {
            $config_last_id = $chk_config[0]['creditnote_no'];
        }

        $y  = date('y');
        $ny = date('y') + 1;

        $credit_note_no = 'CDN/' . $exp[1] . '/' . $config_last_id;

        /* $update_arr = array('credit_note_image'=>$cr_imagename,'credit_note_gen_date'=>date('Y-m-d'),'credit_note_number'=>$credit_note_no);
        //$update_arr = array('credit_note_image'=>$cr_imagename,'credit_note_number'=>$credit_note_no);
        $this->master_model->updateRecord('maker_checker',$update_arr,array('transaction_no'=>$transaction_no)); */

        $this->db->where('transaction_no', $transaction_no);
        $this->db->where('req_status', 5);
        $maker_rec = $this->master_model->getRecords('maker_checker', '', 'refund_date,credit_note_number,req_module,sbi_refund_date');

        $credit_title = $this->master_model->getRecords('credit_note_title', array('pay_type' => $maker_rec[0]['req_module']), 'title,service_code');

        $im               = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
        $background_color = imagecolorallocate($im, 255, 255, 255); // white
        $black            = imagecolorallocate($im, 0, 0, 0); // black

        //imageline ($im,   x1,  y1, x2, y2, color);
        imageline($im, 20, 20, 980, 20, $black); // line-1
        imageline($im, 20, 980, 980, 980, $black); // line-2
        imageline($im, 20, 20, 20, 980, $black); // line-3
        imageline($im, 980, 20, 980, 980, $black); // line-4
        imageline($im, 20, 160, 980, 160, $black); // line-5
        imageline($im, 20, 200, 980, 200, $black); // line-6
        imageline($im, 20, 480, 980, 480, $black); // line-7
        imageline($im, 20, 520, 980, 520, $black); // line-8
        imageline($im, 20, 580, 980, 580, $black); // line-9
        imageline($im, 20, 850, 980, 850, $black); // line-10
        imageline($im, 580, 200, 580, 480, $black); // line-11
        imageline($im, 85, 520, 85, 850, $black); // line-12
        imageline($im, 500, 520, 500, 850, $black); // line-13
        imageline($im, 650, 520, 650, 850, $black); // line-14
        imageline($im, 785, 520, 785, 850, $black); // line-15
        imageline($im, 860, 520, 860, 850, $black); // line-16
        imageline($im, 40, 880, 625, 880, $black); // line-17

        //imagestring(image,font,x,y,string,color);
        imagestring($im, 5, 100, 40, "INDIAN INSTITUTE OF BANKING & FINANCE", $black);
        imagestring($im, 3, 100, 60, "ISO 21001:2018 Certified", $black);
        imagestring($im, 3, 100, 80, "(CINU9111OMH1928GAP1391)", $black);
        imagestring($im, 5, 400, 170, "Credit Note", $black);

        imagestring($im, 5, 40, 220, "Details of service recipient", $black);
        imagestring($im, 5, 600, 220, "Details of Assessee", $black);
        imagestring($im, 3, 40, 260, "Membership number: " . $invoice_info[0]['member_no'], $black);
        imagestring($im, 3, 40, 280, "Name of the Recipient: " . $member_name, $black);
        imagestring($im, 3, 40, 300, "Address: " . $address1, $black);
        imagestring($im, 3, 40, 320, $address2, $black);

        imagestring($im, 3, 40, 340, "City: " . $city, $black);
        imagestring($im, 3, 40, 360, "State: " . $invoice_info[0]['state_name'], $black);
        imagestring($im, 3, 40, 380, "State Code: " . $invoice_info[0]['state_code'], $black);
        imagestring($im, 3, 40, 400, "GST No: NA", $black);
        imagestring($im, 3, 40, 420, "Reference no of Original Invoice: " . $invoice_info[0]['invoice_no'], $black);
        imagestring($im, 3, 40, 440, "Date of Original Invoice: " . $date_of_invoice, $black);
        imagestring($im, 3, 40, 460, "Transaction no : " . $transaction_no, $black);

        imagestring($im, 3, 600, 260, "Address: Registered office Kohinoor City,", $black);
        imagestring($im, 3, 600, 280, "Commercial - II,  Tower 1, 2nd Floor, Kirole Road", $black);

        imagestring($im, 3, 600, 300, "Off LBS Marg, Kurla(West), Mumbai - 400 070,", $black);
        imagestring($im, 3, 600, 320, "Maharashtra", $black);
        imagestring($im, 3, 600, 340, "www.iibf.org.in", $black);
        imagestring($im, 3, 600, 360, "Credit Note no: " . $maker_rec[0]['credit_note_number'], $black);
        //imagestring($im, 3, 600,  380, "Date : ", $black);

        imagestring($im, 3, 600, 380, "Refund Date : " . date("d-m-Y", strtotime($maker_rec[0]['sbi_refund_date'])), $black);
        imagestring($im, 3, 600, 400, "GSTIN No: 27AAATT3309D1ZS", $black);

        imagestring($im, 3, 40, 530, "Sr.No", $black);
        imagestring($im, 3, 118, 530, "Description of Service", $black);
        imagestring($im, 3, 535, 530, "Accounting ", $black);
        imagestring($im, 3, 535, 542, "code", $black);
        imagestring($im, 3, 535, 554, "of Service/HSN", $black);
        imagestring($im, 3, 660, 530, "Rate per unit(Rs)", $black);
        imagestring($im, 3, 808, 530, "Unit", $black);
        imagestring($im, 3, 900, 530, "Total(Rs)", $black);

        imagestring($im, 3, 40, 600, "1", $black);
        imagestring($im, 3, 118, 600, $credit_title[0]['title'], $black);
        imagestring($im, 3, 550, 600, $invoice_info[0]['service_code'], $black);
        imagestring($im, 3, 690, 600, $invoice_info[0]['fee_amt'], $black);
        imagestring($im, 3, 820, 600, 1, $black);
        imagestring($im, 3, 900, 600, $invoice_info[0]['fee_amt'], $black);

        if ($invoice_info[0]['state_of_center'] == 'MAH') {
            imagestring($im, 3, 118, 700, "CGST", $black);
            imagestring($im, 3, 118, 720, "SGST", $black);
            imagestring($im, 3, 118, 740, "IGST", $black);

            imagestring($im, 3, 690, 700, $invoice_info[0]['cgst_rate'] . "%", $black);
            imagestring($im, 3, 690, 720, $invoice_info[0]['sgst_rate'] . "%", $black);
            imagestring($im, 3, 690, 740, "-", $black);

            imagestring($im, 3, 900, 700, $invoice_info[0]['cgst_amt'], $black);
            imagestring($im, 3, 900, 720, $invoice_info[0]['sgst_amt'], $black);
            imagestring($im, 3, 900, 740, "-", $black);
        }

        if ($invoice_info[0]['state_of_center'] != 'MAH') {
            imagestring($im, 3, 118, 700, "CGST", $black);
            imagestring($im, 3, 118, 720, "SGST", $black);
            imagestring($im, 3, 118, 740, "IGST", $black);

            imagestring($im, 3, 690, 700, "-", $black);
            imagestring($im, 3, 690, 720, "-", $black);
            imagestring($im, 3, 690, 740, $invoice_info[0]['igst_rate'] . "%", $black);

            imagestring($im, 3, 900, 700, "-", $black);
            imagestring($im, 3, 900, 720, "-", $black);
            imagestring($im, 3, 900, 740, $invoice_info[0]['igst_amt'], $black);
        }

        imagestring($im, 3, 535, 830, "Total(Rs.) ", $black);
        if ($invoice_info[0]['state_of_center'] == 'MAH') {
            imagestring($im, 3, 900, 830, $invoice_info[0]['cs_total'], $black);
        } elseif ($invoice_info[0]['state_of_center'] != 'MAH') {
            imagestring($im, 3, 900, 830, $invoice_info[0]['igst_total'], $black);
        }

        imagestring($im, 3, 40, 860, "Amount in words : Rs. " . $wordamt, $black);
        imagestring($im, 3, 650, 880, "For Indian Institute of Banking & Finance", $black);
        imagestring($im, 3, 720, 950, "Authorised Signatory", $black);

        $savepath = base_url() . "uploads/CreditNoteTmp/";

        $ex        = explode("/", $invoice_info[0]['invoice_no']);
        $imagename = "CN_" . $ex[0] . "_" . $ex[1] . "_" . $ex[2] . ".jpg";

        $png  = @imagecreatefromjpeg('assets/images/sign.jpg');
        $png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
        $jpeg = @imagecreatefromjpeg("uploads/CreditNoteTmp/" . $imagename);

        //imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
        @imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
        @imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
        imagepng($im, 'uploads/CreditNoteTmp/' . $imagename);
        imagedestroy($im);

        echo $attachpath = "uploads/CreditNoteTmp/" . $imagename;
        //exit;
    }

    public function decrypt_payment_response()
    {
        include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
        $key = $this->config->item('sbi_m_key');
        $aes = new CryptAES();
        $aes->set_key(base64_decode($key));
        $aes->require_pkcs5();
        $encData      = $aes->decrypt('FMJXoPXkkqDqABtZweuX8ZAqGgaWLNosUHkVQu0XfcaS1yRz22Njds1EVZ8DGTI12ryVAnJNSF+UqZVmVd8ZbQ==');
        $attachpath   = $invoiceNumber   = $admitcard_pdf   = '';
        $responsedata = explode("|", $encData);

        print_r($responsedata);
    }

    public function check_dra_mail()
    {
        $batch_id       = '6038';
        $user_type_flag = 1;
        $emailerstr     = $this->master_model->getRecords('emailer', array('emailer_name' => 'batch_approve'));

        $this->db->select('agency_batch.*,dra_inst_registration.inst_name,dra_inst_registration.inst_head_email,city_master.city_name,state_master.state_name');
        $this->db->join('dra_inst_registration', 'dra_inst_registration.id = agency_batch.agency_id', 'left');
        $this->db->join('agency_center', 'agency_center.center_id = agency_batch.center_id');
        $this->db->join('city_master', 'agency_center.location_name=city_master.id', 'LEFT');
        $this->db->join('state_master', 'agency_center.state=state_master.state_code', 'LEFT');
        $user_info = $this->master_model->getRecords('agency_batch', array('agency_batch.id' => $batch_id));

        $institute_name = $user_info[0]['inst_name'] . ' ' . $user_info[0]['city_name'] . ' ' . $user_info[0]['state_name'] . ' ' . $user_info[0]['batch_name'] . ' ' . $user_info[0]['batch_code'] . ' ' . $user_info[0]['batch_status'] . ' ' . $user_info[0]['batch_from_date'] . ' ' . $user_info[0]['batch_to_date'];

        //$to_mail = 'kapil.nerkar@esds.co.in';
        $to_mail = 'pratibha.purkar@esds.co.in';

        $userfinalstrname = preg_replace('#[\s]+#', ' ', $institute_name);
        $newstring1       = str_replace("#DATE#", "" . date('Y-m-d h:s:i') . "", $emailerstr[0]['emailer_text']);
        $newstring2       = str_replace("#INSITUTE_NAME#", "" . $user_info[0]['inst_name'] . "", $newstring1);
        $newstring3       = str_replace("#LOCATION_NAME#", "" . $user_info[0]['city_name'] . "", $newstring2);
        $newstring4       = str_replace("#BATCH_NAME#", "" . $user_info[0]['batch_name'] . "", $newstring3);
        $newstring5       = str_replace("#STATE#", "" . $user_info[0]['state_name'] . "", $newstring4);
        if ($user_info[0]['batch_type'] == 'C') {
            $user_info[0]['batch_type'] = ' Combine Batch ';
        } else {
            $user_info[0]['batch_type'] = 'Separate Batch';
        }
        $newstring6 = str_replace("#BATCH_TYPE#", "" . $user_info[0]['batch_type'] . "", $newstring5);
        $newstring7 = str_replace("#FROM_DATE#", "" . date('d-M-Y', strtotime($user_info[0]['batch_from_date'])) . "", $newstring6);
        $newstring8 = str_replace("#TO_DATE#", "" . date('d-M-Y', strtotime($user_info[0]['batch_to_date'])) . "", $newstring7);
        if ($user_info[0]['batch_status'] == 'A') {
            $user_info[0]['batch_status'] = 'Approved';
        }

        $newstring9 = str_replace("#BATCH_STATUS#", "" . $user_info[0]['batch_status'] . "", $newstring8);
        $final_str  = str_replace("#BATCH_CODE#", "" . $user_info[0]['batch_code'] . "", $newstring9);

        $bcc      = array('iibfdevp@esds.co.in', 'sagar.matale@esds.co.in');
        $info_arr = array('to' => $to_mail, 'from' => 'logs@iibf.esdsconnect.com', 'bcc' => $bcc, 'subject' => 'Your Batch ' . $user_info[0]['batch_name'] . ' is Approved.', 'message' => $final_str);

        echo '<pre>';
        print_r($info_arr);
        echo '</pre>'; //exit;
        echo '>> ' . $result = $this->Emailsending->mailsend($info_arr);
    }

    //START : SEND DRA BATCHES MAIL CUSTOM
    public function check_dra_batch_mail()
    {
        exit;
        $this->load->helper('dra_agency_center_mail_helper');
        //6038,6039,6040,6041,6042,6043,6044,6045,6046,6047,6048,6049,6050,6051,6052,6053,6054,6055,6056,6057,6058,6059,6060,6061,6062,6063
        //6064
        //6065,6067,6068,6069,6070,6071,6072,6073,6074,6075,6076,6077,6078,6079,6082,6083,6084,6085,6086,6087,6088,6089
        //5999,6000
        //6080,6090,6092
        ////6110,6111,6112,6113,6114,6115,6116,6117,6118,6119,6120,6121,6122,6123,6124,6125,6126,6127,6128,6129,6130,6131,6132,6133,6134,6136,6137,6138,6139,6140,6141,6142,6143,6144,6145,6146,6147,6148,6149,6150,6151,6152,6153,6154,6155

        //6093,6094,6095,6096,6097,6098,6099,6100,6101,6102,6103,6104,6105,6106,6107,6108,6109

        $batch_id_arr = array(6156, 6157, 6158, 6159, 6160, 6161, 6162, 6163);
        if (count($batch_id_arr) > 0) {
            foreach ($batch_id_arr as $batch_res) {
                echo ' <br> >> ' . $batch_res . ' >> ';
                $batch_id       = $batch_res; //'6038';
                $user_type_flag = 1;
                $this->batch_approve_mail_custom($batch_id, $user_type_flag, $user_type_flag);
            }
        }
    }

    public function batch_approve_mail_custom($batch_id, $user_type_flag)
    {
        $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'batch_approve'));

        $this->db->select('agency_batch.*,dra_inst_registration.inst_name,dra_inst_registration.inst_head_email,city_master.city_name,state_master.state_name');
        $this->db->join('dra_inst_registration', 'dra_inst_registration.id = agency_batch.agency_id', 'left');
        $this->db->join('agency_center', 'agency_center.center_id = agency_batch.center_id');
        $this->db->join('city_master', 'agency_center.location_name=city_master.id', 'LEFT');
        $this->db->join('state_master', 'agency_center.state=state_master.state_code', 'LEFT');
        $user_info = $this->master_model->getRecords('agency_batch', array('agency_batch.id' => $batch_id));

        $institute_name = $user_info[0]['inst_name'] . ' ' . $user_info[0]['city_name'] . ' ' . $user_info[0]['state_name'] . ' ' . $user_info[0]['batch_name'] . ' ' . $user_info[0]['batch_code'] . ' ' . $user_info[0]['batch_status'] . ' ' . $user_info[0]['batch_from_date'] . ' ' . $user_info[0]['batch_to_date'];

        $to_mail = $user_info[0]['inst_head_email'];
        //$to_mail = 'sagar.matale01@gmail.com';

        $userfinalstrname = preg_replace('#[\s]+#', ' ', $institute_name);
        $newstring1       = str_replace("#DATE#", "" . date('Y-m-d h:s:i') . "", $emailerstr[0]['emailer_text']);
        $newstring2       = str_replace("#INSITUTE_NAME#", "" . $user_info[0]['inst_name'] . "", $newstring1);
        $newstring3       = str_replace("#LOCATION_NAME#", "" . $user_info[0]['city_name'] . "", $newstring2);
        $newstring4       = str_replace("#BATCH_NAME#", "" . $user_info[0]['batch_name'] . "", $newstring3);
        $newstring5       = str_replace("#STATE#", "" . $user_info[0]['state_name'] . "", $newstring4);
        if ($user_info[0]['batch_type'] == 'C') {
            $user_info[0]['batch_type'] = ' Combine Batch ';
        } else {
            $user_info[0]['batch_type'] = 'Separate Batch';
        }
        $newstring6 = str_replace("#BATCH_TYPE#", "" . $user_info[0]['batch_type'] . "", $newstring5);
        $newstring7 = str_replace("#FROM_DATE#", "" . date('d-M-Y', strtotime($user_info[0]['batch_from_date'])) . "", $newstring6);
        $newstring8 = str_replace("#TO_DATE#", "" . date('d-M-Y', strtotime($user_info[0]['batch_to_date'])) . "", $newstring7);
        if ($user_info[0]['batch_status'] == 'A') {
            $user_info[0]['batch_status'] = 'Approved';
        }

        $newstring9 = str_replace("#BATCH_STATUS#", "" . $user_info[0]['batch_status'] . "", $newstring8);
        $final_str  = str_replace("#BATCH_CODE#", "" . $user_info[0]['batch_code'] . "", $newstring9);

        $bcc      = array('iibfdevp@esds.co.in', 'sagar.matale@esds.co.in');
        $info_arr = array('to' => $to_mail, 'from' => 'logs@iibf.esdsconnect.com', 'bcc' => $bcc, 'subject' => 'Your Batch ' . $user_info[0]['batch_name'] . ' is Approved.', 'message' => $final_str);

        //echo '<pre>'; print_r($info_arr); echo '</pre>'; exit;
        echo '>> ' . $result = $this->Emailsending->mailsend($info_arr);
    }
    //END : SEND DRA BATCHES MAIL CUSTOM

    //START : SEND DRA INSPECTION MAIL CUSTOM
    public function check_dra_inspection_mail()
    {
        //exit;

        $this->load->helper('dra_agency_center_mail_helper');
        //6038,6039,6040,6041,6042,6043,6044,6045,6046,6047,6048,6049,6050,6051,6052,6053,6054,6055,6056,6057,6058,6059,6060,6061,6062,6063
        //6064
        //6065,6067,6068,6069,6070,6071,6072,6073,6074,6075,6076,6077,6078,6079,6082,6083,6084,6085,6086,6087,6088,6089
        //5999,6000
        //6080,6090,6092
        ////6110,6111,6112,6113,6114,6115,6116,6117,6118,6119,6120,6121,6122,6123,6124,6125,6126,6127,6128,6129,6130,6131,6132,6133,6134,6136,6137,6138,6139,6140,6141,6142,6143,6144,6145,6146,6147,6148,6149,6150,6151,6152,6153,6154,6155

        //6093,6094,6095,6096,6097,6098,6099,6100,6101,6102,6103,6104,6105,6106,6107,6108,6109

        $batch_id_arr = array(6156, 6157, 6158, 6159, 6160, 6161, 6162, 6163);
        if (count($batch_id_arr) > 0) {
            foreach ($batch_id_arr as $batch_res) {
                echo ' <br> >> ' . $batch_res . ' >> ';
                $batch_id       = $batch_res; //'6038';
                $user_type_flag = 1;

                $this->db->group_by('ab.id');
                $this->db->limit(1);
                $this->db->select('im.inspector_email');
                $this->db->join('agency_inspector_master im', 'im.id = ab.inspector_id', 'INNER');
                $batch_data = $this->master_model->getRecords('agency_batch ab', array('ab.id' => $batch_id));

                //echo $this->db->last_query(); exit;
                if (count($batch_data) > 0) {
                    $this->batch_inspection_mail($batch_id, $batch_data[0]['inspector_email']);
                }
            }
        }
    }

    public function batch_inspection_mail($batch_id = 0, $inspector_email)
    {
        $attachpath = "";
        $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'batch_inspection'));
        $this->db->select("dra_inst_registration.*,dra_inst_registration.id as institute_id,agency_center.location_name,dra_medium_master.medium_description,agency_batch.*,state_master.state_name,city_master.city_name,cs.city_name as cityname");
        $this->db->join('agency_center', 'agency_batch.center_id=agency_center.center_id', 'LEFT');
        $this->db->join('city_master as cs', 'agency_center.location_name=cs.id', 'LEFT');
        $this->db->join('city_master', 'agency_batch.city=city_master.id', 'LEFT');
        $this->db->join('state_master', 'state_master.state_code=agency_batch.state_code', 'LEFT');
        $this->db->join('dra_inst_registration', 'agency_batch.agency_id=dra_inst_registration.id', 'LEFT');
        $this->db->join('dra_medium_master', 'dra_medium_master.medium_code=agency_batch.training_medium', 'LEFT');
        $this->db->where('agency_batch.id = ' . $batch_id);
        $this->db->where('agency_center.center_display_status', '1'); // to hide centers and batches.

        $user_info = $this->master_model->getRecords("agency_batch");

        $institute_name = $user_info[0]['inst_name'] . ' ' . $user_info[0]['cityname'] . ' ' . $user_info[0]['inst_head_name'] . ' ' . $user_info[0]['inst_head_contact_no'] . ' ' . $user_info[0]['batch_name'] . ' ' . $user_info[0]['batch_from_date'] . ' ' . $user_info[0]['batch_to_date'] . ' ' . $user_info[0]['timing_from'] . ' ' . $user_info[0]['timing_to'] . ' ' . $user_info[0]['total_candidates'] . ' ' . $user_info[0]['batch_code'];

        /* Code added By Manoj: 15 May 2019 */
        $batch_address = "";
        $batch_address = $user_info[0]['addressline1'] . ' ' . $user_info[0]['addressline2'] . ' ' . $user_info[0]['addressline3'] . ' ' . $user_info[0]['addressline4'] . ' ' . $user_info[0]['district'] . ' ' . $user_info[0]['state_name'] . ' ' . $user_info[0]['city_name'] . ' ' . $user_info[0]['pincode'];
        /* Close Code added By Manoj: 15 May 2019 */

        $userfinalstrname = preg_replace('#[\s]+#', ' ', $institute_name);
        $newstring1       = str_replace("#DATE#", "" . date('Y-m-d h:s:i') . "", $emailerstr[0]['emailer_text']);
        $newstring2       = str_replace("#INSITUTE_NAME#", "" . $user_info[0]['inst_name'] . "", $newstring1);
        $newstring3       = str_replace("#LOCATION_NAME#", "" . $user_info[0]['cityname'] . "", $newstring2);
        $newstring4       = str_replace("#CONTACT_PERSON#", "" . $user_info[0]['contact_person_name'] . "", $newstring3);

        /*if($user_info[0]['inst_head_contact_no']=='')
        {
        $user_info[0]['inst_head_contact_no']= '-';
        }
        else
        {
        $user_info[0]['inst_head_contact_no'];
        }*/

        if ($user_info[0]['contact_person_phone'] == '') {
            $user_info[0]['contact_person_phone'] = '-';
        } else {
            $user_info[0]['contact_person_phone'];
        }

        // name_of_bank and remarks
        if ($user_info[0]['name_of_bank'] == '') {
            $name_of_bank = '-';
        } else {
            $name_of_bank = $user_info[0]['name_of_bank'];
        }

        if ($user_info[0]['remarks'] == '') {
            $remarks = '-';
        } else {
            $remarks = $user_info[0]['remarks'];
        }

        if ($user_info[0]['faculty_name'] == '') {
            $faculty_name = '-';
        } elseif ($user_info[0]['faculty_qualification'] == '') {
            $faculty_name = $user_info[0]['faculty_name'];
        } else {
            $faculty_name = $user_info[0]['faculty_name'] . ' , ' . $user_info[0]['faculty_qualification'];
        }

        if ($user_info[0]['faculty_name2'] == '') {
            $faculty_name2 = '-';
        } elseif ($user_info[0]['faculty_qualification2'] == '') {
            $faculty_name2 = $user_info[0]['faculty_name2'];
        } else {
            $faculty_name2 = $user_info[0]['faculty_name2'] . ' , ' . $user_info[0]['faculty_qualification2'];
        }

        $newstring5  = str_replace("#CONTACT_NUMBER#", "" . $user_info[0]['contact_person_phone'] . "", $newstring4);
        $newstring6  = str_replace("#BATCH_NAME#", "" . $user_info[0]['batch_name'] . "", $newstring5);
        $newstring7  = str_replace("#FROM_DATE#", "" . date('d-M-Y', strtotime($user_info[0]['batch_from_date'])) . "", $newstring6);
        $newstring8  = str_replace("#TO_DATE#", "" . date('d-M-Y', strtotime($user_info[0]['batch_to_date'])) . "", $newstring7);
        $newstring9  = str_replace("#TIMING_FROM#", "" . $user_info[0]['timing_from'] . "", $newstring8);
        $newstring10 = str_replace("#TIMING_TO#", "" . $user_info[0]['timing_to'] . "", $newstring9);
        $newstring11 = str_replace("#BATCH_CODE#", "" . $user_info[0]['batch_code'] . "", $newstring10);
        $newstring12 = str_replace("#BANK_NAME#", "" . $name_of_bank . "", $newstring11);
        $newstring13 = str_replace("#REMARK#", "" . $remarks . "", $newstring12);
        $newstring14 = str_replace("#ADDRESS#", "" . $batch_address . "", $newstring13);
        $newstring15 = str_replace("#FACULTY_DETAILS_1#", "" . $faculty_name . "", $newstring14);
        $newstring16 = str_replace("#FACULTY_DETAILS_2#", "" . $faculty_name2 . "", $newstring15);
        $newstring17 = str_replace("#BATCH_CODE#", "" . $user_info[0]['batch_code'] . "", $newstring16);

        $final_str = str_replace("#CANDIDATES#", "" . $user_info[0]['total_candidates'] . "", $newstring17);
        ########## START : CODE ADDED BY SAGAR ON 19-08-2020 ###################
        $online_user_details = '';
        if (isset($user_info[0]['batch_online_offline_flag']) && $user_info[0]['batch_online_offline_flag'] == 1) //IF BATCH IS ONLINE THEN SEND USER IDS AND PASSWORD WITH URL
        {
            $this->db->where('agency_id = ' . $user_info[0]['agency_id']);
            $this->db->where('batch_id = ' . $batch_id);
            $user_id_password_data = $this->master_model->getRecords("agency_online_batch_user_details");
            if (count($user_id_password_data) > 0) {
                $online_user_details .= '<p>Please check below login details and On-line training platform details</p>
					<table style=" font-family:Arial, Helvetica, sans-serif; font-size:14px;" width="50%" cellspacing="" cellpadding="" border="1">
					<thead>
					<tr>
					<th style="padding:5px 10px;">Login Id</th>
					<th style="padding:5px 10px;">Password</th>
					</tr>
					</thead>
					<tbody>';
                foreach ($user_id_password_data as $Res) {
                    $online_user_details .= '
						<tr>
						<td style="padding:5px 10px;">' . $Res['login_id'] . '</td>
						<td style="padding:5px 10px;">' . base64_decode($Res['password']) . '</td>
						</tr>
						';
                }
                $online_user_details .= '
					</tbody>
					</table>';

                $online_user_details .= '<p><strong>On-line training platform used : </strong>' . $user_info[0]['online_training_platform'] . '</p>';
            }
        }
        $final_str = str_replace("#ONLINE_USER_DETAILS#", "" . $online_user_details . "", $final_str);
        ########## END : CODE ADDED BY SAGAR ON 19-08-2020 ###################

        //$attachpath = 'https://iibf.esdsconnect.com/uploads/iibfdra_inspection_report/INSPECTION_FORMAT.pdf';
        $attachpath = 'https://iibf.esdsconnect.com/uploads/iibfdra_inspection_report/INSPECTION_FORMAT.xlsx';

        $inspector_email_send = $inspector_email;

        ########## START : CODE ADDED BY SAGAR ON 24-08-2020 ###################
        $info_arr1 = array('to' => $inspector_email_send, 'from' => 'logs@iibf.esdsconnect.com', 'cc' => 'dd.mss@iibf.org.in,iibfdevp@esds.co.in,balasalian@iibf.org.in', 'subject' => 'DRA Inspection ' . $user_info[0]['cityname'], 'message' => $final_str);
        //echo '<pre>'; print_r($info_arr1); echo '</pre>'; exit;
        $this->Emailsending->mailsend_attch_cc($info_arr1, $attachpath);
        ########## END : CODE ADDED BY SAGAR ON 24-08-2020 ###################
    }
    //END : SEND DRA INSPECTION MAIL CUSTOM

    public function check_sms_trustsignal()
    {
        $mobile  = '9881191703';
        $message = 'Thanks for enrolling for BCBF-Payment Bank-exam. Your exam form and fee 105 is received vide transaction 105105. Refer email for details. IIBF Team';
        $res     = $this->master_model->send_sms_trustsignal(intval($mobile), $message, 'J0DWe39nR', '', '', 'IIBFSM');
        echo '<pre>';
        print_r($res);
    }

    public function check_sms_trustsignal_vishal()
    {
        $mobile  = '7588553132';
        $message = 'Thanks for enrolling for BCBF-Payment Bank-exam. Your exam form and fee 105 is received vide transaction 105105. Refer email for details. IIBF Team';
        $res     = $this->send_sms_trustsignal(intval($mobile), $message, 'J0DWe39nR', '', '', 'IIBFSM');
        echo '<pre>';
        print_r($res);
    }

    public function send_sms_trustsignal($mobile_no = '', $message = '', $template_id = '', $exam_code = '', $route = '', $sender_id = '')
    {
        $return_arr = $add_log = array();
        $api_key = '6cc49b51-5a2e-4e4d-a34a-ef5c2c203da2';
        $status = $response = $data_string = '';

        if ($route == '') {
            $route = 'transactional';
        }
        if ($sender_id == '') {
            $sender_id = 'IIBFCO';
        }

        $mobile_no = array($mobile_no);
        $data = array("sender_id" => $sender_id, "to" => $mobile_no, "message" => $message, "route" => $route, "template_id" => $template_id);
        $data_string = json_encode($data);
        $ch = curl_init('https://api.trustsignal.io/v1/sms?api_key=' . $api_key);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            )
        );
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        $msg_res = json_decode($response, true);
        print_r($msg_res);
    }

    public function test_professional_banker_api($exam_code = 0, $member_no = 0)
    {
        $response = $this->master_model->professional_bankers_api_curl($exam_code, $member_no, $return_flag = 0);
        echo '<pre>';
        print_r($response);
        echo '</pre>';
        exit;
    }

    public function test_professional_banker_fees()
    {
        $member_data = $this->master_model->getRecords('member_registration', array(
            'regnumber' => '300012681',
            'isactive'                                                                              => '1', 'isdeleted' => '0'
        ), 'registrationtype, regid, regnumber, namesub, firstname, middlename, lastname, email, mobile, state, createdon, registrationtype, isactive, usrpassword');
        echo '<br>' . $this->db->last_query();

        $exm_cd   = $this->session->userdata('PROFESSIONAL_BANKERS_LOGIN_EXAM_CODE');
        $response = $this->getProfessionalBankerCreditFee('997', $exm_cd, $grp_code = 'B1_1', 'F', 'MAH');
        echo '<pre>';
        print_r($response);
        echo '</pre>';
        exit;
    }

    public function getProfessionalBankerCreditFee($exam_period = null, $excd = null, $grp_code = null, $memcategory = null, $state_code = null)
    {
        $result_arr = array();
        $fee_amount = $sgst_amt = $cgst_amt = $igst_amt = $cs_tot = $igst_tot = 0;

        /* echo '<br> exam_period : '.$exam_period;
        echo '<br> excd : '.$excd;
        echo '<br> grp_code : '.$grp_code;
        echo '<br> memcategory : '.$memcategory;
        echo '<br> state_code : '.$state_code; */

        if ($exam_period != null && $excd != null && $grp_code != null && $memcategory != null) {
            $getstatedetails = $this->master_model->getRecords('state_master', array('state_code' => $state_code, 'state_delete' => '0'));
            echo '<br>' . $this->db->last_query();

            $today_date = date('Y-m-d');
            $this->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
            $getfees = $this->master_model->getRecords('fee_master', array('exam_code' => $excd, 'member_category' => $memcategory, 'exam_period' => $exam_period, 'group_code' => $grp_code, 'exempt' => $getstatedetails[0]['exempt']));
            echo '<br>' . $this->db->last_query();

            if (count($getfees) > 0) {
                if ($state_code == 'MAH') {
                    $fee_amount = $getfees[0]['fee_amount'];
                    $cs_tot     = $getfees[0]['cs_tot'];
                    $sgst_amt   = $getfees[0]['sgst_amt'];
                    $cgst_amt   = $getfees[0]['cgst_amt'];
                } else {
                    $fee_amount = $getfees[0]['fee_amount'];
                    $igst_tot   = $getfees[0]['igst_tot'];
                    $igst_amt   = $getfees[0]['igst_amt'];
                }
            }
        }

        $result_arr['fee_amount'] = $fee_amount;
        $result_arr['sgst_amt']   = $sgst_amt;
        $result_arr['cgst_amt']   = $cgst_amt;
        $result_arr['igst_amt']   = $igst_amt;
        $result_arr['cs_tot']     = $cs_tot;
        $result_arr['igst_tot']   = $igst_tot;
        return $result_arr;
    }

    public function test_institute_subscription_api($institute_no = 0, $invoice_no = 0)
    {
        $response     = $this->master_model->institute_subscription_api_curl($institute_no, $invoice_no);
        $response_res = json_decode($response, true);

        /* $response = $this->institute_subscription_api_curl_uat($institute_no,$invoice_no,$flag);
        return $response; */
        //echo $response;
    }

    public function test_center_master_api($a = 0, $b = 0)
    {
        $final_arr = $response_msg = array();
        $response  = '';

        $url = "https://iibf.cscexams.in/backend/web/user/sharecsconboarding";

        $string = preg_replace('/\s+/', '+', $url);
        $x      = curl_init($string);
        curl_setopt($x, CURLOPT_HEADER, 0);
        curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($x, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($x, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($x);

        if (curl_errno($x)) //CURL ERROR
        {
            $response     = 'error';
            $response_msg = curl_error($x);
        } else {
            $response     = "success";
            $response_msg = $result;
        }
        curl_close($x);

        $final_arr['response_msg'] = $response_msg;

        echo '<pre>';
        print_r(json_decode($result));
        echo '</pre>';
    }

    public function repair_images_temp()
    {

        //$regnumberArr = array(802056698);
        //$this->db->where_in('a.regnumber', $regnumberArr);

        //$exam_code = array('1003','1004','1005','1006','1007','1008','1009'); // Send Free and Paid Both Applications '1002',
        $yesterday = date('Y-m-d', strtotime("- 1 day")); //
        $yesterday = '2022-04-01';

        //$exam_code = array('1002', '1010', '1011', '1012', '1013', '1014', '1019', '1020', '2027'); // Send Free and Paid Both Applications
        $regnumberArr = array(500165002, 510287101, 500161337, 510139489, 510296003, 510044971, 500073847, 510025771, 7643764, 510220604, 510097293, 510181233, 500132072, 500077232, 510214862, 510183628, 510104370, 510162945, 500013147);
        $this->db->where_in('c.regnumber', $regnumberArr);
        $select = 'a.exam_code,c.regid, c.firstname,c.middlename,c.lastname, c.image_path, c.reg_no, c.regnumber, c.scannedphoto, c.idproofphoto, c.scannedsignaturephoto, a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id,a.created_on AS registration_date,a.exam_medium,a.exam_center_code';
        $this->db->join('member_registration c', 'a.regnumber=c.regnumber', 'LEFT');
        // $this->db->join('admit_card_details d', 'a.id=d.mem_exam_id', 'LEFT');
        $this->db->group_by('a.regnumber');
        // $this->db->where_in('a.exam_code', $exam_code);
        $can_exam_data = $this->Master_model->getRecords(
            'member_exam a',
            array(
                //'remark'     => 1,
                'isactive'   => '1',
                'isdeleted'  => 0,
                'pay_status' => 1,

            ),
            $select,
            '',
            ''
        ); /* ,'1'  */
        //'DATE(a.created_on) >=' => $yesterday,
        // echo $this->db->last_query(); // exit;

        if (count($can_exam_data)) {
            foreach ($can_exam_data as $exam) {
                echo '<br>mem_number : ' . $mem_number = $exam['regnumber'];
                $member_images                         = $this->get_member_images($exam['image_path'], $exam['reg_no'], $exam['regnumber'], $exam['scannedphoto'], $exam['idproofphoto'], $exam['scannedsignaturephoto'], $exam['regid'], $yesterday);

                $scannedphoto          = $member_images['scannedphoto'];
                $expected_scannedphoto = base_url() . 'uploads/photograph/p_' . $mem_number . '.jpg';
                if ($scannedphoto != $expected_scannedphoto) {
                    $chk_response = $this->update_image_name($scannedphoto, $expected_scannedphoto);
                    if ($chk_response != "") {
                        $scannedphoto = $chk_response;
                        //$update_data = array('scannedphoto' => $expected_scannedphoto);
                        //$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $mem_number));
                    }
                }
                echo '<br>photo : ' . $scannedphoto;
                echo '<br>photo : ' . $expected_scannedphoto;

                $scannedsignaturephoto          = $member_images['scannedsignaturephoto'];
                $expected_scannedsignaturephoto = base_url() . 'uploads/scansignature/s_' . $mem_number . '.jpg';
                if ($scannedsignaturephoto != $expected_scannedsignaturephoto) {
                    $chk_response = $this->update_image_name($scannedsignaturephoto, $expected_scannedsignaturephoto);
                    if ($chk_response != "") {
                        $scannedsignaturephoto = $chk_response;
                        //$update_data = array('scannedphoto' => $expected_scannedphoto);
                        //$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $mem_number));
                    }
                }
                echo '<br>signature : ' . $scannedsignaturephoto;
                echo '<br>signature : ' . $expected_scannedsignaturephoto;

                $idproofphoto          = $member_images['idproofphoto'];
                $expected_idproofphoto = base_url() . 'uploads/idproof/pr_' . $mem_number . '.jpg';
                if ($idproofphoto != $expected_idproofphoto) {
                    $chk_response = $this->update_image_name($idproofphoto, $expected_idproofphoto);
                    if ($chk_response != "") {
                        $idproofphoto = $chk_response;
                        //$update_data = array('scannedphoto' => $expected_scannedphoto);
                        //$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $mem_number));
                    }
                }
                echo '<br>idproof : ' . $idproofphoto;
                echo '<br>idproof : ' . $expected_idproofphoto;
                echo "<br>=========================================================================================<br>";
            }
        }
    }

    public function update_image_name($current_img_name = '', $new_img_name = '')
    {
        $base_url         = base_url();
        $current_img_name = str_replace($base_url, './', $current_img_name);
        $new_img_name     = str_replace($base_url, './', $new_img_name); //exit;

        $final_img_name = '';

        if ($current_img_name != "") {
            if (file_exists($current_img_name)) {
                if ($new_img_name != "" && $new_img_name != $current_img_name) {
                    if (file_exists($new_img_name)) {
                        $final_img_name = $new_img_name;
                    } else {
                        @copy($current_img_name, $new_img_name);

                        if (file_exists($new_img_name)) {
                            $final_img_name = $new_img_name;
                        } else {
                            $final_img_name = $current_img_name;
                        }
                    }
                } else {
                    $final_img_name = $current_img_name;
                }
            }
        }

        return str_replace('./', $base_url, $final_img_name);
    }

    public function get_member_images($image_path = '', $reg_no = '', $regnumber = '', $scannedphoto = '', $idproofphoto = '', $scannedsignaturephoto = '', $regid = '', $yesterday = '')
    {
        $recover_images            = $this->recover_images($image_path, $reg_no, $regnumber, $scannedphoto, $idproofphoto, $scannedsignaturephoto, $regid, $yesterday);
        $scannedphoto_res          = $recover_images['scannedphoto'];
        $idproofphoto_res          = $recover_images['idproofphoto'];
        $scannedsignaturephoto_res = $recover_images['scannedsignaturephoto'];

        if ($scannedphoto_res == "" || $idproofphoto_res == "" || $scannedsignaturephoto_res == "") {
            $this->db->where("REPLACE(title,' ','') LIKE '%CSCnonregINSERTArray%'");
            $user_log = $this->Master_model->getRecords('userlogs a', array('regid' => $regid, ' DATE(date) >=' => $yesterday));
            //echo $this->db->last_query(); die;
            if (COUNT($user_log) > 0) {
                $description           = unserialize($user_log[0]['description']);
                $scannedphoto          = $description['scannedphoto'];
                $scannedsignaturephoto = $description['scannedsignaturephoto'];
                $idproofphoto          = $description['idproofphoto'];

                $recover_images2           = $this->recover_images($image_path, $reg_no, $regnumber, $scannedphoto, $idproofphoto, $scannedsignaturephoto, $regid, $yesterday);
                $scannedphoto_res          = $recover_images2['scannedphoto'];
                $idproofphoto_res          = $recover_images2['idproofphoto'];
                $scannedsignaturephoto_res = $recover_images2['scannedsignaturephoto'];
            }
        }

        $data['scannedphoto']          = $scannedphoto_res;
        $data['idproofphoto']          = $idproofphoto_res;
        $data['scannedsignaturephoto'] = $scannedsignaturephoto_res;
        return $data;
    }

    public function recover_images($image_path = '', $reg_no = '', $regnumber = '', $scannedphoto = '', $idproofphoto = '', $scannedsignaturephoto = '', $regid = '', $yesterday = '')
    {
        //// FOR PHOTO
        if ($scannedphoto != '' && $scannedphoto != 'p_' . $regnumber . '.jpg') {
            $attachpath = "uploads/photograph/" . $scannedphoto;
            if (file_exists($attachpath)) {
                if (@rename("./uploads/photograph/" . $scannedphoto, "./uploads/photograph/p_" . $regnumber . ".jpg")) {
                    $insert_data = array(
                        'member_no'    => $regnumber,
                        'update_value' => "uploads folder Photo rename",
                        'update_date'  => date('Y-m-d H:i:s'),
                    );
                    $this->master_model->insertRecord('member_images_update', $insert_data);
                }
            }
        }

        //// FOR SIGNATURE
        if ($scannedsignaturephoto != '' && $scannedsignaturephoto != 's_' . $regnumber . '.jpg') {
            $attachpath = "uploads/scansignature/" . $scannedsignaturephoto;
            if (file_exists($attachpath)) {
                if (@rename("./uploads/scansignature/" . $scannedsignaturephoto, "./uploads/scansignature/s_" . $regnumber . ".jpg")) {
                    $insert_data = array(
                        'member_no'    => $regnumber,
                        'update_value' => "uploads folder Signature rename",
                        'update_date'  => date('Y-m-d H:i:s'),
                    );
                    $this->master_model->insertRecord('member_images_update', $insert_data);
                }
            }
        }

        //// FOR IDPROOF
        if ($idproofphoto != '' && $idproofphoto != 'pr_' . $regnumber . '.jpg') {
            $attachpath = "uploads/idproof/" . $idproofphoto;
            if (file_exists($attachpath)) {
                if (@rename("./uploads/idproof/" . $idproofphoto, "./uploads/idproof/pr_" . $regnumber . ".jpg")) {
                    $insert_data = array(
                        'member_no'    => $regnumber,
                        'update_value' => "uploads folder id proof rename",
                        'update_date'  => date('Y-m-d H:i:s'),
                    );
                    $this->master_model->insertRecord('member_images_update', $insert_data);
                }
            }
        }

        $extn      = '.jpg';
        $member_no = $regnumber;

        //// Code for Photo
        $photo_name = $scannedphoto;
        $photo      = strpos($photo_name, 'photo');
        if ($photo == 8) {
            $photo_replace = str_replace($photo_name, 'p_', $photo_name);
            $updated_photo = $photo_replace . $member_no . $extn;

            $update_data = array('scannedphoto' => $updated_photo);
            $this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));

            $insert_data = array(
                'member_no'    => $member_no,
                'update_value' => "Photo",
                'update_date'  => date('Y-m-d H:i:s'),
            );
            $this->master_model->insertRecord('member_images_update', $insert_data);

            $scannedphoto = $updated_photo;
        }

        //// Code for Signature
        $sign_name = $scannedsignaturephoto;
        $sign      = strpos($sign_name, 'sign');
        if ($sign == 8) {
            $sign_replace = str_replace($sign_name, 's_', $sign_name);
            $updated_sign = $sign_replace . $member_no . $extn;

            $update_data = array('scannedsignaturephoto' => $updated_sign);
            $this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));

            $insert_data = array(
                'member_no'    => $member_no,
                'update_value' => "Signature",
                'update_date'  => date('Y-m-d H:i:s'),
            );
            $this->master_model->insertRecord('member_images_update', $insert_data);

            $scannedsignaturephoto = $updated_sign;
        }

        //// Code for IDPROOF
        $idproof_name = $idproofphoto;
        $idproof      = strpos($idproof_name, 'idproof');
        if ($idproof == 8) {
            $idproof_replace = str_replace($idproof_name, 'pr_', $idproof_name);
            $updated_idproof = $idproof_replace . $member_no . $extn;

            $update_data = array('idproofphoto' => $updated_idproof);
            $this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));

            $insert_data = array(
                'member_no'    => $member_no,
                'update_value' => "ID Proof",
                'update_date'  => date('Y-m-d H:i:s'),
            );
            $this->master_model->insertRecord('member_images_update', $insert_data);

            $idproofphoto = $updated_idproof;
        }

        $db_img_path      = $image_path; //Get old image path from database
        $scannedphoto_res = $idproofphoto_res = $scannedsignaturephoto_res = '';

        $final_photo_img = '';
        if ($scannedphoto != "") {
            $photo_img_arr = explode('.', $scannedphoto);
            if (count($photo_img_arr) > 0) {
                $chk_photo_img = $photo_img_arr[0];

                if (file_exists(FCPATH . "uploads/photograph/" . $chk_photo_img . '.jpg')) {
                    $final_photo_img = $chk_photo_img . '.jpg';
                } else if (file_exists(FCPATH . "uploads/photograph/" . $chk_photo_img . '.jpeg')) {
                    $final_photo_img = $chk_photo_img . '.jpeg';
                }
            }
        }

        if ($final_photo_img == "") {
            if (file_exists(FCPATH . "uploads/photograph/p_" . $member_no . '.jpg')) {
                $final_photo_img = "p_" . $member_no . '.jpg';
            } else if (file_exists(FCPATH . "uploads/photograph/p_" . $member_no . '.jpeg')) {
                $final_photo_img = "p_" . $member_no . '.jpeg';
            }
        }

        if ($final_photo_img != "") //Check photo in regular folder
        {
            $scannedphoto_res = base_url() . "uploads/photograph/" . $final_photo_img;
        } else if ($db_img_path != "") //Check photo in old image path
        {
            if ($reg_no != "" && file_exists(FCPATH . "uploads" . $db_img_path . "photo/p_" . $reg_no . ".jpg")) {
                $scannedphoto_res = base_url() . "uploads" . $db_img_path . "photo/p_" . $reg_no . ".jpg";
            } else if ($regnumber != "" && file_exists(FCPATH . "uploads" . $db_img_path . "photo/p_" . $regnumber . ".jpg")) {
                $scannedphoto_res = base_url() . "uploads" . $db_img_path . "photo/p_" . $regnumber . ".jpg";
            }
        } else //Check photo in kyc folder
        {
            if ($reg_no != "" && file_exists(FCPATH . "uploads/photograph/k_p_" . $reg_no . ".jpg")) {
                $scannedphoto_res = base_url() . "uploads/photograph/k_p_" . $reg_no . ".jpg";
            } else if ($regnumber != "" && file_exists(FCPATH . "uploads/photograph/k_p_" . $regnumber . ".jpg")) {
                $scannedphoto_res = base_url() . "uploads/photograph/k_p_" . $regnumber . ".jpg";
            }
        }

        $final_idproofphoto_img = '';
        if ($idproofphoto != "") {
            $idproofphoto_img_arr = explode('.', $idproofphoto);
            if (count($idproofphoto_img_arr) > 0) {
                $chk_idproofphoto_img = $idproofphoto_img_arr[0];

                if (file_exists(FCPATH . "uploads/idproof/" . $chk_idproofphoto_img . '.jpg')) {
                    $final_idproofphoto_img = $chk_idproofphoto_img . '.jpg';
                } else if (file_exists(FCPATH . "uploads/idproof/" . $chk_idproofphoto_img . '.jpeg')) {
                    $final_idproofphoto_img = $chk_idproofphoto_img . '.jpeg';
                }
            }
        }

        if ($final_idproofphoto_img == "") {
            if (file_exists(FCPATH . "uploads/idproof/pr_" . $member_no . '.jpg')) {
                $final_idproofphoto_img = "pr_" . $member_no . '.jpg';
            } else if (file_exists(FCPATH . "uploads/idproof/pr_" . $member_no . '.jpeg')) {
                $final_idproofphoto_img = "pr_" . $member_no . '.jpeg';
            }
        }

        if ($final_idproofphoto_img != "") //Check id proof in regular folder
        {
            $idproofphoto_res = base_url() . "uploads/idproof/" . $final_idproofphoto_img;
        } else if ($db_img_path != "") //Check id proof in old image path
        {
            if ($reg_no != "" && file_exists(FCPATH . "uploads" . $db_img_path . "idproof/pr_" . $reg_no . ".jpg")) {
                $idproofphoto_res = base_url() . "uploads" . $db_img_path . "idproof/pr_" . $reg_no . ".jpg";
            } else if ($regnumber != "" && file_exists(FCPATH . "uploads" . $db_img_path . "idproof/pr_" . $regnumber . ".jpg")) {
                $idproofphoto_res = base_url() . "uploads" . $db_img_path . "idproof/pr_" . $regnumber . ".jpg";
            }
        } else //Check photo in kyc folder
        {
            if ($reg_no != "" && file_exists(FCPATH . "uploads/idproof/k_pr_" . $reg_no . ".jpg")) {
                $idproofphoto_res = base_url() . "uploads/idproof/k_pr_" . $reg_no . ".jpg";
            } else if ($regnumber != "" && file_exists(FCPATH . "uploads/idproof/k_pr_" . $regnumber . ".jpg")) {
                $idproofphoto_res = base_url() . "uploads/idproof/k_pr_" . $regnumber . ".jpg";
            }
        }

        $final_scanphoto_img = '';
        if ($scannedsignaturephoto != "") {
            $scanphoto_img_arr = explode('.', $scannedsignaturephoto);
            if (count($scanphoto_img_arr) > 0) {
                $chk_scanphoto_img = $scanphoto_img_arr[0];

                if (file_exists(FCPATH . "uploads/scansignature/" . $chk_scanphoto_img . '.jpg')) {
                    $final_scanphoto_img = $chk_scanphoto_img . '.jpg';
                } else if (file_exists(FCPATH . "uploads/scansignature/" . $chk_scanphoto_img . '.jpeg')) {
                    $final_scanphoto_img = $chk_scanphoto_img . '.jpeg';
                }
            }
        }

        if ($final_scanphoto_img == "") {
            if (file_exists(FCPATH . "uploads/scansignature/s_" . $member_no . '.jpg')) {
                $final_scanphoto_img = "s_" . $member_no . '.jpg';
            } else if (file_exists(FCPATH . "uploads/scansignature/s_" . $member_no . '.jpeg')) {
                $final_scanphoto_img = "s_" . $member_no . '.jpeg';
            }
        }

        if ($final_scanphoto_img != "") //Check signature in regular folder
        {
            $scannedsignaturephoto_res = base_url() . "uploads/scansignature/" . $final_scanphoto_img;
        } else if ($db_img_path != "") //Check signature in old image path
        {
            if ($reg_no != "" && file_exists(FCPATH . "uploads" . $db_img_path . "signature/s_" . $reg_no . ".jpg")) {
                $scannedsignaturephoto_res = base_url() . "uploads" . $db_img_path . "signature/s_" . $reg_no . ".jpg";
            } else if ($regnumber != "" && file_exists(FCPATH . "uploads" . $db_img_path . "signature/s_" . $regnumber . ".jpg")) {
                $scannedsignaturephoto_res = base_url() . "uploads" . $db_img_path . "signature/s_" . $regnumber . ".jpg";
            }
        } else //Check signature in kyc folder
        {
            if ($reg_no != "" && file_exists(FCPATH . "uploads/scansignature/k_s_" . $reg_no . ".jpg")) {
                $scannedsignaturephoto_res = base_url() . "uploads/scansignature/k_s_" . $reg_no . ".jpg";
            } else if ($regnumber != "" && file_exists(FCPATH . "uploads/scansignature/k_s_" . $regnumber . ".jpg")) {
                $scannedsignaturephoto_res = base_url() . "uploads/scansignature/k_s_" . $regnumber . ".jpg";
            }
        }

        $data['scannedphoto']          = $scannedphoto_res;
        $data['idproofphoto']          = $idproofphoto_res;
        $data['scannedsignaturephoto'] = $scannedsignaturephoto_res;
        return $data;
    }

    public function check_binary_img()
    {
        echo '<br><br>' . $img_name = 'https://iibf.esdsconnect.com/uploads/scansignature/s_801839106.jpg';
        echo '<br><br>';
        //echo $contents = file_get_contents($img_name);

        $mime     = 'image/' . strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
        $contents = file_get_contents($img_name);
        $base64   = base64_encode($contents);
        echo ('data:' . $mime . ';base64,' . $base64);
        exit;
        //echo '<br><br>'.$this->data_uri($img_name);
        /* echo '<br><br>'.$this->scaleImageFileToBlob($img_name);

        $add_data['imageType'] = $this->scaleImageFileToBlob($img_name);
        $add_data['imageData'] = $this->scaleImageFileToBlob($img_name);
        $add_data['imgdata2'] = $this->scaleImageFileToBlob($img_name);
        $add_data['imgdata3'] = $this->scaleImageFileToBlob($img_name);*/
    }

    //START : UPDATE EMAIL ID AND MOBILE ON STAGING SERVER TO AVOID UNNECESSARY EMAILS AND SMS SENDING TO CLIENT DATA. 
    //ADDED BY SAGAR M ON 02-08-2023
    function update_email_mobile_custom()
    {
        echo '<br> table_name : ' . $table_name  = 'table_name';
        echo '<br> table_pk_col_name : ' . $table_pk_col_name = 'table_pk_col_name';
        echo '<br> table_mobile_col_name : ' . $table_mobile_col_name = 'table_mobile_col_name';
        echo '<br> table_email_col_name : ' . $table_email_col_name = 'table_email_col_name';

        $select_str = $table_pk_col_name . ',' . $table_mobile_col_name . ',' . $table_email_col_name;
        $result = $this->master_model->getRecords($table_name, array(), $select_str);
        echo '<br>Qry : ' . $this->db->last_query();
        exit;

        echo '<br> Record count : ' . count($result);

        $i = 0;
        if (count($result) > 0) {
            foreach ($result as $res) {
                $update_data = array();

                //REPLACE FIRST DIGIT WITH 0 TO MOBILE NUMBER
                if ($res[$table_mobile_col_name] != "") {
                    $mobile = $res[$table_mobile_col_name];
                    $new_mobile = '1' . substr($mobile, 1);
                    $update_data[$table_mobile_col_name] = $new_mobile;
                }

                //APPEND IIBFDEV_ TO EMAIL ID
                if ($res[$table_email_col_name] != "") {
                    $email = $res[$table_email_col_name];
                    if (substr($email, 0, 7) != 'iibfdev') {
                        $update_data[$table_email_col_name] = 'iibfdev_' . $email;
                    }
                }

                if (count($update_data) > 0) {
                    $this->master_model->updateRecord($table_name, $update_data, array($table_pk_col_name => $res[$table_pk_col_name]));
                    $i++;
                }
            }
        }
        echo '<br> updated record count : ' . $i;
    }
    //END : UPDATE EMAIL ID AND MOBILE ON STAGING SERVER TO AVOID UNNECESSARY EMAILS AND SMS SENDING TO CLIENT DATA. 






    function test_date()
    {
      $date_arr = array('2023-10-02','2023-10-03','2023-10-04','2023-10-05','2023-10-06','2023-10-07','2023-10-08','2023-10-09','2023-08-09','2023-08-10','2023-08-11','2023-08-12','2023-08-13','2023-08-14','2023-08-15','2023-08-16');
      $date_arr = array('2024-01-22','2024-01-23','2024-01-24','2024-01-25','2024-01-26','2024-01-27','2024-01-28','2024-01-29');
      foreach($date_arr as $res)
      {
        echo $res.' >> ';
        echo $date_check = $this->calculate_batch_start_date($res);
        echo '<br>';
      }
    }

    //START : ADDED BY SAGAR ON 2023-09-14
    //BATCH START DATE = T+2
    //IT IS USED TO TO CALCULATE BATCH START DATE EXCLUDING ALL SATURDAYS, SUNDAYS, 15 AUG, 16 JAN
    function calculate_batch_start_date($current_date='',$numDays=0)
    {
      if($current_date == '') { $current_date = date('Y-m-d'); }      
      if($numDays == 0) { $numDays = 2; } 
      
      $holiday_arr = array('01-26', '08-15');
      
      /*$finalDate = strtotime($current_date);
      for($i=1;$i<=$numDays;$i)
      {
        $finalDate = strtotime("+1 day", $finalDate);
        $dayOfWeek = date('N', $finalDate); // Get day of the week (1 = Monday, 7 = Sunday)
        if ($dayOfWeek != 6 && $dayOfWeek != 7 && !in_array(date("Y-m-d", $finalDate),$holiday_arr))
        {
          $i++;
        }       
      }
      return date('Y-m-d', $finalDate);*/

      $finalDate = $current_date;
      for($i=1;$i<=$numDays;$i)
      {
        '<br>chkDate : '.$finalDate = date('Y-m-d', strtotime("+1day", strtotime($finalDate)));
        '<br>dayOfWeek : '.$dayOfWeek = date('N', strtotime($finalDate)); // Get day of the week (1 = Monday, 7 = Sunday)
        if ($dayOfWeek != 6 && $dayOfWeek != 7 && !in_array(date('m-d', strtotime($finalDate)),$holiday_arr))
        {
         $i++;
        }
      }

      //return date('Y-m-d', $finalDate);
      return date('Y-m-d', strtotime("+1day", strtotime($finalDate)));
    }//END : ADDED BY SAGAR ON 2023-09-14


    function calculate_batch_date_for_edit_candidate($batch_id=0,$numDays=0)
    {
      //$batch_data = $this->master_model->getRecords('agency_batch',array('id'=>$batch_id));      
      //if(count($batch_data) > 0)
      {
        echo '<br>batch_from_date : '.$batch_from_date = '2023-10-04';
        echo '<br>batch_holidays : '.$batch_holidays = '05-10-2023,06-10-2023,07-10-2023';
        if($numDays == 0) { $numDays = 2; }

        if($batch_holidays == '')
        {
          echo "<br>>>".date('Y-m-d', strtotime($batch_from_date. ' + '.$numDays.' days'));
        }
        else
        {
          $holiday_arr = explode(",",$batch_holidays);          

          $finalDate = strtotime($batch_from_date);
          for($i=1;$i<=$numDays;$i)
          {
            $finalDate = strtotime("+1 day", $finalDate);
            if (!in_array(date("d-m-Y", $finalDate),$holiday_arr))
            {
              $i++;
            }       
          }

          echo "<br>==".date('Y-m-d', $finalDate);
        }
      }
      //else
      //{
        //echo 'false';
        //return false;
      //}
    }

    function check_capacity_bulk($sel_venue = NULL, $sel_date = NULL, $sel_time = NULL,$sel_center = NULL)
    {
        $seat_flag=1; 
        $CI = & get_instance();
        $sel_venue = 'IIBFRP01';
        $sel_date = '2023-11-25';
        $sel_time = '10:00 AM';
        $sel_center= '990';

        //$CI->load->model('my_model');
        if($sel_venue !=NULL && $sel_date !=NULL && $sel_time !=NULL && $sel_center !=NULL)
        {
            
            $CI->db->trans_start();
        if($CI->session->userdata('examcode') == 996){
                $CI->db->where('institute_code',$CI->session->userdata('institute_id'));
            }
            $seat_count=$CI->master_model->getRecords('venue_master',array('exam_date'=>$sel_date,'venue_code'=>$sel_venue,'session_time'=>$sel_time,'center_code'=>$sel_center),'session_capacity');//session_capacity
            
            echo'<pre>';echo $this->db->last_query();//die;
            print_r($seat_count);echo'<br>';
            if(count($seat_count) > 0)
            {
                
                $CI->db->where('remark','2');
                $CI->db->join('member_exam','member_exam.id = admit_card_details.mem_exam_id');
                $admit_card_Count=$CI->master_model->getRecords('admit_card_details',array('exam_date'=>$sel_date,'venueid'=>$sel_venue,'time'=>$sel_time,'center_code'=>$sel_center,'record_source'=>'bulk','bulk_isdelete'=>'0'));        
                
                #### code by prafull ####
                $regular_admit_card_Count=$CI->master_model->getRecordCount('seat_allocation',array('date'=>$sel_date,'venue_code'=>$sel_venue,'session'=>$sel_time,'center_code'=>$sel_center));
                $total_count=(intval(count($admit_card_Count)) + intval($regular_admit_card_Count));
                
               echo'<pre>';echo $this->db->last_query();//die;
                
                if($seat_count[0]['session_capacity'] <=($total_count))
                {
                    $seat_flag=0;
                }
                else if($total_count > $seat_count[0]['session_capacity'])
                {
                    $seat_flag=0;
                }
                ### End of code by prafull #### 
                /*if(!(count($admit_card_Count) < $seat_count[0]['session_capacity']))
                {
                    $seat_flag=0;
                }*/
            }
            $CI->db->trans_complete();
            //echo $CI->db->last_query().'<br>';exit;
            //return $seat_number;
        }
        
        return $seat_flag;
        
    }


    function date_diff()
    {
      $dateTimeObject1 = date_create('2023-11-16 19:03:01');  
      $dateTimeObject2 = date_create('2023-11-16 19:10:01');  
          
      // Calculating the difference between DateTime Objects 
      $interval = date_diff($dateTimeObject1, $dateTimeObject2);  
      $min = $interval->days * 24 * 60; 
      $min += $interval->h * 60; 
      $min += $interval->i; 
      echo $min; 
    }
}
