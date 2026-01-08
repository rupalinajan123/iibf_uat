<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Custom_sm extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->library('upload');
    $this->load->helper('upload_helper');
    $this->load->helper('master_helper');
    $this->load->helper('general_helper');
    $this->load->model('Master_model');
    $this->load->library('email');
    $this->load->helper('date');
    $this->load->library('email');
    $this->load->model('Emailsending');
    $this->load->model('log_model');
    $this->load->model('billdesk_pg_model');

    error_reporting(E_ALL); // Report all types of errors
    ini_set('display_errors', '1'); // Display errors on the screen

    // echo '<div style="text-align: left; width: 800px; margin: 0 0 20px 0; background: #eee; color: #000;font-weight: 600;padding: 8px 10px;">IP Address : ' . $this->get_client_ip_email() . '</div>';
  }

  function index()
  {
    echo 'This is a test function created by Sagar Matale';
  }

  public function crop_image(){
    // $this->master_model->resize_admitcard_images('https://iibf.esdsconnect.com/uploads/iibfdra/p_802731412.jpg?1737695547')
     
    $file_full_path = 'https://iibf.esdsconnect.com/staging/uploads/iibfdra/s_802732636.jpg';

    '<br>file_full_path : ' . $file_full_path;
    $explode_arr = explode("/", $file_full_path);
    '<br>file_name : ' . $file_name = $explode_arr[count($explode_arr) - 1];
    '<br>file_path : ' . $file_path = str_replace($file_name, '', $file_full_path);
      
    $headers = @get_headers($file_full_path);
      // echo '<pre>'; print_r($headers); exit;
    if (stripos($headers[0], "200 OK"))
    {
      list($width, $height) = getimagesize($file_full_path);
        // echo "<br>Image exists. Width: $width px, Height: $height px"; exit;
      if ($width > 500 || $height > 500)
      {
      '<br>copy_directory : ' . $copy_directory = 'uploads/admitcard_resize_images/iibfdra/';
        $this->create_directories($copy_directory);
        echo $file_path = 'uploads/iibfdra/';
        echo $file_path."<br>";
        echo $file_path.$file_name.'----'.$copy_directory.$file_name; 
        //copy("uploads/iibfdra/".$file_name, "uploads/admitcard_resize_images/".$file_name);

        //exit;

        if(copy($file_path.$file_name, $copy_directory.$file_name) ) 
        { 
          $this->load->helper('file'); 
          $this->load->helper('url'); 
          $this->load->library('image_lib');
          $config_1['image_library']='gd2';
          $config_1['source_image']= $file_path.$file_name; //'./uploads/photograph/p_510259010.jpg'; //
          $config_1['create_thumb']=TRUE;
          $config_1['maintain_ratio']=TRUE;
          $config_1['thumb_marker']='';
          $config_1['new_image']= $file_path.$file_name; //'./uploads/photograph/thumb_p_510259010.jpg'; //
          $config_1['width']='500';
          $config_1['height']='500';

          //echo '<pre>';print_r($config_1);
          $this->image_lib->clear();
          $this->image_lib->initialize($config_1);
          $this->image_lib->resize();
        }
      }
    }
  }

  public function create_directories($directory_path = '')
  {
    $directory_path = str_replace("./", "", $directory_path);
    $directory_path_arr = explode("/", $directory_path);
    $chk_dir_path = './';
    if (count($directory_path_arr) > 0)
    {
      $i = 0;
      foreach ($directory_path_arr as $res)
      {
        if ($i > 0)
        {
          $chk_dir_path .= "/";
        }
        $chk_dir_path .= $res;

        if (!is_dir($chk_dir_path))
        {
          $dir = mkdir($chk_dir_path, 0777);
          $myfile = fopen($chk_dir_path . "/index.php", "w") or die("Unable to open file!");
          $txt = "";
          fwrite($myfile, $txt);
          fclose($myfile);
        }
        $i++;
      }
    }
    return $chk_dir_path;
  }/******** END : CREATE N NUMBER OF NESTED DIRECTORIES ********/

  function qry($exam_code='1005', $exam_date_arr=array('2024-09-21', '2024-09-22', '2024-09-23', '2024-09-24', '2024-09-28'))
  {
    echo '<br>The candidate is already applied for exam code 1005 in individual mode and exam date is 2024-09-28';
    $regnumber = '500007751';
    $exam_code = base64_encode($exam_code);
    //$exam_date = '2024-09-28';
    //echo 'examapplied'; die;
    //check where exam alredy apply or not
    $cnt        = 0;
    $today_date = date('Y-m-d');
    $this->db->select('member_exam.*');
    $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
    $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
    //$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
    $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
    $this->db->where('exam_master.elg_mem_o', 'Y');
    $this->db->where('pay_status', '1');
    $applied_exam_info = $this->master_model->getRecords('member_exam', array('member_exam.exam_code' => base64_decode($exam_code), 'regnumber' => $regnumber));
    echo '<br><br>1 ' . $this->db->last_query(); //exit;

    //Added by Priyank W for RPE exam Validation
    $exCode = base64_decode($exam_code);
    $examCdArr = array('1002', '1003', '1004', '1005', '1006', '1007', '1008', '1009', '1010', '1011', '1012', '1013', '1014', '1017', '1019', '1020', '2027');

    if (in_array($exCode, $examCdArr))
    {
      $get_exam_period = $this->master_model->getRecords('exam_activation_master', array('exam_code' => $exCode));

      $this->db->select('member_exam.*');
      $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
      $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
      //$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
      $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
      $this->db->where('exam_master.elg_mem_o', 'Y');
      $this->db->where('pay_status', '1');
      $applied_exam_info = $this->master_model->getRecords('member_exam', array('member_exam.exam_period' => $get_exam_period[0]['exam_period'], 'regnumber' => $regnumber));
      echo '<br><br>2 ' . $this->db->last_query(); //die;
    }
    //End

    ####check if number applied through the bulk registration (Prafull)###
    if (count($applied_exam_info) <= 0)
    {
      $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
      $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
      //$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
      $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
      $this->db->where('exam_master.elg_mem_o', 'Y');
      $this->db->where('bulk_isdelete', '0');
      $this->db->where('institute_id!=', '');
      $applied_exam_info = $this->master_model->getRecords('member_exam', array('member_exam.exam_code' => base64_decode($exam_code), 'regnumber' => $regnumber));
      echo '<br><br>3 ' . $this->db->last_query(); //die;
    }

    //START : ADDED BY SAGAR ON 2024-09-12 TO PREVENT THE DUPLICATION APPLICATION FOR SAME DATE
    if (count($applied_exam_info) <= 0)
    {
      $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
      $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
      $this->db->join('admit_card_details', 'admit_card_details.mem_exam_id = member_exam.id', 'inner');
      $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
      $this->db->where('exam_master.elg_mem_o', 'Y');
      $this->db->where('bulk_isdelete', '0');
      //$this->db->where('institute_id!=', '');
      $this->db->where_in('admit_card_details.exam_date', $exam_date_arr);

      //if instutute id is null/empty then check remark is 1 else no need to check remark
      $this->db->where(" (IF(institute_id IS NULL OR institute_id = '', remark = '1', '')) ");      

      $applied_exam_info = $this->master_model->getRecords('member_exam', array('regnumber' => $regnumber));
      echo '<br><br>4 ' . $this->db->last_query(); //die;
    } //END : ADDED BY SAGAR ON 2024-09-12 TO PREVENT THE DUPLICATION APPLICATION FOR SAME DATE

    //echo $this->db->last_query();
    //exit;
    ###### End of check  number applied through the bulk registration###
    ######get eligible created on data##########
    $this->db->limit('1');
    $get_eligible_date = $this->master_model->getRecords('eligible_master', array('eligible_master.exam_code' => base64_decode($exam_code), 'member_no' => $regnumber), 'eligible_master.created_on');
    //echo count($applied_exam_info);exit;

    if (count($applied_exam_info) > 0)
    {
      if (base64_decode($exam_code) != $this->config->item('examCodeJaiib') && base64_decode($exam_code) != $this->config->item('examCodeDBF') && base64_decode($exam_code) != $this->config->item('examCodeSOB') && base64_decode($exam_code) != $this->config->item('examCodeCaiib') && base64_decode($exam_code) != 62 && base64_decode($exam_code) != $this->config->item('examCodeCaiibElective63') && base64_decode($exam_code) != 64 && base64_decode($exam_code) != 65 && base64_decode($exam_code) != 66 && base64_decode($exam_code) != 67 && base64_decode($exam_code) != $this->config->item('examCodeCaiibElective68') && base64_decode($exam_code) != $this->config->item('examCodeCaiibElective69') && base64_decode($exam_code) != $this->config->item('examCodeCaiibElective70') && base64_decode($exam_code) != $this->config->item('examCodeCaiibElective71') && base64_decode($exam_code) != 72)
      {

        if (count($get_eligible_date) > 0)
        {

          if (strtotime($applied_exam_info[0]['created_on']) > strtotime($get_eligible_date[0]['created_on']))
          {
            $cnt = $cnt + 1;
          }
        }
        else
        {
          $cnt = count($applied_exam_info);
        }
      }
      else
      {
        $cnt = count($applied_exam_info);
      }
    }
    echo '<br><br>' . $cnt;
    if($cnt > 0) { echo '<br>Already Applied'; }
    else { echo '<br>Eligible for apply'; }
    //return count($applied_exam_info);
  }
  
  function banchCandidateCount()
  {
    $print_query = "SELECT * FROM agency_batch b WHERE batch_status <> 'In Review' AND b.agency_id IN (213,199,186,220,126,198,200,202,201,231,232,129,167,193,180,205,123,6,189,183,146,188,182,209,130,158,217,58) ORDER BY id ASC  LIMIT 500, 500";

    // 500,500 this limit updated next need to replace 1000,500

    $Result      = $this->db->query($print_query); 
    $arr_batches = $Result->result_array();

    foreach ($arr_batches as $key => $batch) 
    {
      $candidate_query = "SELECT * FROM dra_members WHERE batch_id = ".$batch['id']." AND isdeleted = 0";      
      $candidate_result = $this->db->query($candidate_query);
      
      // Get the count of candidates
      $count_candidate = $candidate_result->num_rows();
      
      $res = $this->master_model->updateRecord('agency_batch',['total_registered_candidates'=>$count_candidate],['id'=>$batch['id']]);
    }
    echo "exit";
  }

  function get_my_ip(){
    echo "<h1>IP: ".$this->get_client_ip_email()."</h1>";
  }

  function test_sms($mobile_no = '')
  {
    echo '<div style="text-align: left; width: 800px; margin: 0 0 20px 0; background: #eee; color: #000;font-weight: 600;padding: 8px 10px;">This is a test function for sending test sms</div>';

    if ($mobile_no == '')
    {
      echo '<br><span style="color:red">Please enter mobile number into the url</span><br>';
      exit;
    }

    $otp = date("His");

    $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'member_login_with_otp'));
    $sms_text = $emailerstr[0]['sms_text'];
    $message = str_replace('#OTP#', $otp, $sms_text);

    echo '<br>sms_user : ' . $sms_user = 'IIBF';
    echo '<br>sms_api_key : ' . $sms_api_key = 'c6b75a20f6XX';
    echo '<br>sms_entityid : ' . $sms_entityid = '1701162807222263362';
    echo '<br>route : ' . $route = 'transactional';
    $sender_id = $emailerstr[0]['sms_sender'];
    if ($sender_id == '')
    {
      $sender_id = 'IIBFCO';
    }
    echo '<br>sender_id : ' . $sender_id;
    echo '<br>template_id : ' . $template_id = $emailerstr[0]['sms_template_id'];

    echo '<br><br>mobile_no : ' . $mobile_no = '+91' . str_replace(",", ",+91", $mobile_no);
    echo '<br>message : ' . $message;

    $xml_data = 'user=' . $sms_user . '&key=' . $sms_api_key . '&mobile=' . $mobile_no . '&message=' . $message . '&senderid=' . $sender_id . '&accusage=1&entityid=' . $sms_entityid . '&tempid=' . $template_id;

    $ch = curl_init("http://redirect.ds3.in/submitsms.jsp?");
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
    curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err)
    {
      echo '<br><br><span style="color:red">Curl Error : </span>' . $err;
    }
    else
    {
      $response_arr = explode(",", $response);
      if (count($response_arr) > 0 && trim($response_arr[0]) == 'fail')
      {
        echo '<br><br><span style="color:red">Curl Response : fail</span>';
      }
      else
      {
        echo '<br><br>Curl Response : success';
        echo '<br><br>Response : ' . $response;
      }
    }
  }

  function test_billdesk()
  {
    echo "<div style=''>SERVER_ADDR IP Address: ".$_SERVER['SERVER_ADDR']; echo "&nbsp;&nbsp;&nbsp;&nbsp;</div><br>";

    echo '<div style="text-align: left; width: 800px; margin: 0 0 20px 0; background: #eee; color: #000;font-weight: 600;padding: 8px 10px;">This is a test function for testing billdesk payment</div>';

    if ($this->config->item('bd_payment_mode_sm') == 'production')
    {
      echo '<span style="color:red">Current Billdesk Payment Mode is Production instead of Sandbox.</span>';
      exit;
    }

    $MerchantOrderNo = '9999999999999999991' . date("YmdHis");
    $amount = '101';
    $regid = '123456';
    $pg_flag = "IIBFELS";
    $custom_field_billdesk = $MerchantOrderNo . "-iibfexam-" . $pg_flag . "-" . $regid;
    $billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $regid, $regid, '', 'Custom_sm/test_billdesk', '', '', '', $custom_field_billdesk);

    echo '<pre>';
    print_r($billdesk_res);
    echo '</pre>';
    exit;
  }

  //IIBFBCBF Result Details API
  public function get_iibfbcbf_result_related_api($type = '', $exam_code = '', $exam_period = '', $part_no = '1', $member_no = '')
  {
    //echo '<br>Start: '; 
    $final_arr = $response_msg = array();
    $response = '';

    $part_no = '1';
    /*$exam_code = '21';
    $exam_period = '122';
    $member_no = '100019014';*/
    /*60/221/1/510028070*/

    $api_name = "Result Downloading Member wise API UAT";  
    $url="http://10.10.233.66:8088/ResultDownloadApi/getResultDownloadDtls/".$exam_code."/".$exam_period."/".$part_no."/".$member_no; // Result Downloading Member wise API UAT  
    if($type == "getResult"){
      $api_name = "Result Downloading Member wise API UAT";  
      $url="http://10.10.233.66:8088/ResultDownloadApi/getResultDownloadDtls/".$exam_code."/".$exam_period."/".$part_no."/".$member_no; // Result Downloading Member wise API UAT
    }else if($type == "getMarks"){
      $api_name = "Marks Obtained Details API UAT";      
      $url="http://10.10.233.66:8088/ResultDownloadApi/getMarksObtained/".$exam_code."/".$exam_period."/".$part_no; // Marks Obtained Details API UAT
    }else if($type == "getMember"){
      $api_name = "Member Details API UAT";  
      $url="http://10.10.233.66:8088/ResultDownloadApi/getMemberDetails/".$exam_code."/".$exam_period."/".$part_no; // Member Details API UAT
    }else if($type == "getSubject"){
      $api_name = "Subject Details API UAT";  
      $url="http://10.10.233.66:8088/ResultDownloadApi/getSubjectDetails/".$exam_code."/".$exam_period."/".$part_no; // Subject Details API UAT
    }else if($type == "getExam"){
      $api_name = "Exam Details API UAT";  
      $url="http://10.10.233.66:8088/ResultDownloadApi/getExamDetails/".$exam_code."/".$exam_period."/".$part_no; // Exam Details API UAT
    }

    

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
      echo '<br>response_msg : '.$response_msg = curl_error($x);
    }
    else
    {
      if ($api_result)
      {
        //echo ($api_result);
        $api_result = json_decode($api_result);

        echo "<br> <b>API Name:</b> ".$api_name."<br>";  
        echo '<pre>';
        print_r($api_result);
        echo '</pre>';
        exit;
        
        if (isset($api_result->status) && $api_result->status != 200)
        {
          echo '<pre>';
          print_r($api_result);
          echo '</pre>';
        }
        else
        { 
        ?> 
        <?php
              $sub_arr = array();
              if (count($api_result) > 0)
              {
                $k = 0;
                foreach ($api_result as $result)
                {
                  if (isset($result[0]) && $result[0] != "")
                  {
                    $m = 0;
                    for ($i = 13; $i <= 22; $i++)
                    {
                      if (isset($result[$i]) && $result[$i] != "")
                      {
                        $sub_arr[$k][$m][] = $result[0]; //Exam Code 
                        $sub_arr[$k][$m][] = $result[1]; //Exam Name 
                        $sub_arr[$k][$m][] = $result[2]; //Exam Period 

                        $sub_arr[$k][$m][] = $result[$i];    // Subject Id - Subject Name
                        $sub_arr[$k][$m][] = $result[$i + 10]; // Examination Date

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
          }
      }
      else
      {
        echo "<h4 class='error_block'>No Data!..</h4>";
      }
    }
    curl_close($x);
  }

  //Test API Using Curl
  public function test_api_curl()
  {
    //echo '<br>Start: '; 
    $final_arr = $response_msg = array();
    $response = '';
 
    $url="https://iibf.esdsconnect.com/staging/iibfbcbf/csc_api/update_csc_venue"; // Result Downloading Member wise API UAT  
    
    //$post_data["venue_code"] = "123";
    //$post_data["update_status"] = "activate";

    $apiKey = 'api_csc_venue_'.date("Ymd");
    $venue_code = "796823";  
    $update_status = "activate";  
    //$post_data = 'venue_code=' . $venue_code . '&update_status=' . $update_status;
    $post_data = 'venue_code=' . $venue_code; 
     
    // Convert the data to JSON format
    //$jsonData = json_encode($post_data);

    // Initialize cURL
    $ch = curl_init($url);
    // Set the cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
    curl_setopt($ch, CURLOPT_POST, true); // Set the request method to POST
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); // Set the request data
    curl_setopt($ch, CURLOPT_HTTPHEADER, [ 
        'Api-Key: ' . $apiKey, // Set the Api-Key 
    ]);

    // Execute the cURL request
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        echo 'cURL error: ' . curl_error($ch);
    } else {
        // Get the HTTP response code
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code == 200 || $http_code == 201) {
            // Success - do something with the response
            echo "Response: " . $response;
        } else {
            // API returned an error
            echo "API error: HTTP code " . $http_code . "\nResponse: " . $response;
        }
    }

    // Close the cURL session
    curl_close($ch);

    die;


    //////////////////////////////////////////////////////////////////////////////////
 
    /*$x = curl_init($url);
    curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($x, CURLOPT_POST, 1);
    curl_setopt($x, CURLOPT_POSTFIELDS, "$post_data");
    //curl_setopt($x, CURLOPT_HTTPHEADER, array('Authorization: ' . $apiKey));
    curl_setopt($x, CURLOPT_HTTPHEADER, array('Api-Key: ' . $apiKey));
    curl_setopt($x, CURLINFO_HEADER_OUT, true);
    curl_setopt($x, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($x, CURLOPT_SSL_VERIFYPEER, FALSE);
    $api_result = curl_exec($x);

    if (curl_errno($x))  //CURL ERROR
    {
      echo "<h4 class='error_block'>Invalid Data</h4>";
      echo '<br>response_msg : '.$response_msg = curl_error($x);
    }
    else
    {
        //echo ($api_result);
        $api_result = json_decode($api_result); 
        echo '<pre>';
        print_r($api_result);
        echo '</pre>';
        exit;
    }
    curl_close($x);*/
  }

  public function image_detection_api(){
          
      $curl = curl_init();
       
      curl_setopt_array($curl, [

        CURLOPT_URL => "https://faceanalyzer-ai.p.rapidapi.com/faceanalysis",

        CURLOPT_RETURNTRANSFER => true,

        CURLOPT_ENCODING => "",

        CURLOPT_MAXREDIRS => 10,

        CURLOPT_TIMEOUT => 30,

        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

        CURLOPT_CUSTOMREQUEST => "POST",

        CURLOPT_POSTFIELDS => "-----011000010111000001101001\r\nContent-Disposition: form-data; name=\"url\"\r\n\r\nhttps://openmediadata.s3.eu-west-3.amazonaws.com/face.jpg\r\n-----011000010111000001101001--\r\n\r\n",

        CURLOPT_HTTPHEADER => [

          "Content-Type: multipart/form-data; boundary=---011000010111000001101001",

          "x-rapidapi-host: faceanalyzer-ai.p.rapidapi.com",

          "x-rapidapi-key: 4330cd4bc5msh8250c0a753ecce8p1c7f79jsnb757510a12f5"

        ],

      ]);
       
      $response = curl_exec($curl);

      $err = curl_error($curl);
       
      curl_close($curl);
       
      if ($err) {

        echo "cURL Error #:" . $err;

      } else {

        echo $response;

      }
  }

  public function website_exam_related_notices_dynamic_api($value = '')
  {
    //echo '<br>Start: '; 
    $final_arr = $response_msg = array();
    $response = '';

    //$url = "http://10.10.233.66:8088/ExamScheduleApi/getExamScheduleDtls"; //UAT
    $url = "http://10.10.233.76:8092/ExamScheduleApi/getExamScheduleDtls"; //PRODUCTION
    //$url="http://10.10.233.76:8092/ExamScheduleApi/getExamScheduleDtlsByExamCode/600";
    

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
      echo '<br>response_msg : '.$response_msg = curl_error($x);
    }
    else
    {
      if ($api_result)
      {
        //echo ($api_result);
        $api_result = json_decode($api_result);

        echo '<pre>';
        print_r($api_result);
        echo '</pre>';
        exit;
        
        if (isset($api_result->status) && $api_result->status != 200)
        {
          echo '<pre>';
          print_r($api_result);
          echo '</pre>';
        }
        else
        {
          echo '<p style="text-align: center;"><strong>Examinations Scheduled at Glance</strong></p>';
?>
          <style>
            .website_exam_details_tbl {
              border-collapse: collapse;
              border: 1px solid #000;
              max-width: 800px;
              margin: 20px auto;
              font-family: Arial, Helvetica, sans-serif;
              width: 90%;
            }

            .website_exam_details_tbl thead th {
              text-align: center;
              background-color: #433e5a;
              border-bottom: 1px solid #eee;
              border-right: 1px solid #eee;
              padding: 10px;
              color: #fff;
              text-transform: uppercase;
              text-align: center;
              font-weight: bold;
              font-size: 14px;
            }

            .website_exam_details_tbl tbody td {
              border: 1px solid #000;
              padding: 8px 10px;
              font-size: 14px;
              line-height: 22px;
              vertical-align: top;
              min-width: 215px;
            }

            h4.error_block {
              font-size: 20px;
              text-align: center;
              margin: 40px auto 100px;
              color: red;
            }
          </style>
          <table class="website_exam_details_tbl">
            <thead>
              <tr>
                <th>Sr No</th>
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
              if (count($api_result) > 0)
              {
                $k = 0;
                foreach ($api_result as $result)
                {
                  if (isset($result[0]) && $result[0] != "")
                  {
                    $m = 0;
                    for ($i = 13; $i <= 22; $i++)
                    {
                      if (isset($result[$i]) && $result[$i] != "")
                      {
                        $sub_arr[$k][$m][] = $result[0]; //Exam Code 
                        $sub_arr[$k][$m][] = $result[1]; //Exam Name 
                        $sub_arr[$k][$m][] = $result[2]; //Exam Period 

                        $sub_arr[$k][$m][] = $result[$i];    // Subject Id - Subject Name
                        $sub_arr[$k][$m][] = $result[$i + 10]; // Examination Date

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

              if (count($sub_arr) > 0)
              {
                $sr_no = 1;
                foreach ($sub_arr as $res)
                {
                  foreach ($res as $result)
                  {
                    if (isset($result[0]) && $result[0] != "")
                    {
              ?>
                      <tr>
                        <td><?php echo $sr_no;; ?></td>
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
                    $sr_no++;
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

  /*Start: Training Schedule API*/
  public function website_training_schedule_dynamic_api($value = '')
  {
    //echo '<br>Start: '; 
    $final_arr = $response_msg = array();
    $response = '';

    //echo $url = "http://10.10.233.66:8096/TrainingScheduleApi/getTrainingScheduleDtls";
    echo $url = "http://10.10.233.76:8096/TrainingScheduleApi/getTrainingScheduleDtls";
    

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
      echo '<br>response_msg : '.$response_msg = curl_error($x);
    }
    else
    {
      if ($api_result)
      {
        //echo ($api_result);
        $api_result = json_decode($api_result);

        echo '<pre>';
        print_r($api_result);
        echo '</pre>';
        exit;
        
        if (isset($api_result->status) && $api_result->status != 200)
        {
          echo '<pre>';
          print_r($api_result);
          echo '</pre>';
        }
        else
        {
          echo '<p style="text-align: center;"><strong>Training Schedule API</strong></p>';
?>
          <style>
            .website_exam_details_tbl {
              border-collapse: collapse;
              border: 1px solid #000;
              max-width: 800px;
              margin: 20px auto;
              font-family: Arial, Helvetica, sans-serif;
              /*width: 90%;*/
            }

            .website_exam_details_tbl thead th {
              text-align: center;
              background-color: #433e5a;
              border-bottom: 1px solid #eee;
              border-right: 1px solid #eee;
              padding: 10px;
              color: #fff;
              text-transform: uppercase;
              text-align: center;
              font-weight: bold;
              font-size: 14px;
            }

            .website_exam_details_tbl tbody td {
              border: 1px solid #000;
              padding: 8px 10px;
              font-size: 14px;
              line-height: 22px;
              vertical-align: top;
              /*min-width: 215px;*/
            }

            h4.error_block {
              font-size: 20px;
              text-align: center;
              margin: 40px auto 100px;
              color: red;
            }
          </style>
          <table class="website_exam_details_tbl">
            <thead>  
              <tr>
                <th>Sr No</th>
                <th>Program Type</th>
                <th>Program Code</th>
                <th>Program Description</th>
                <th>Program From Date</th>
                <th>Program To Date</th>
                <th>Duration</th>
                <th>No. of Sessions</th>
                <th>Fees</th>
                <th>Co-Ordinator/Facilitators</th>
                <th>Brochure File</th>
                <th>Zone</th>
              </tr>
            </thead> 
            <tbody> 
              <?php  
              if (count($api_result) > 0)
              {
                $sr_no = 1;
                foreach ($api_result as $result)
                {
                  //foreach ($res as $result)
                  {  
              ?>
                      <tr>
                        <td><?php echo $sr_no;; ?></td>
                        <td><?php echo isset($result[0]) && $result[0] != "" ? $result[0] : ''; ?></td>
                        <td><?php echo isset($result[1]) && $result[1] != "" ? $result[1] : ''; ?></td>
                        <td><?php echo isset($result[2]) && $result[2] != "" ? $result[2] : ''; ?></td>
                        <td><?php echo isset($result[3]) && $result[3] != "" ? $result[3] : ''; ?></td>
                        <td><?php echo isset($result[4]) && $result[4] != "" ? $result[4] : ''; ?></td>
                        <td><?php echo isset($result[5]) && $result[5] != "" ? $result[5] : ''; ?></td>
                        <td><?php echo isset($result[6]) && $result[6] != "" ? $result[6] : ''; ?></td> 
                        <td><?php echo isset($result[7]) && $result[7] != "" ? $result[7] : ''; ?></td>
                        <td><?php echo isset($result[8]) && $result[8] != "" ? $result[8] : ''; ?></td> 
                        <td><?php 
                          /*echo isset($result[10]) && $result[10] != "" ? '<a target="_blank" href="https://iibf.esdsconnect.com/staging/custom_sm/Show_Brochure_File_Pdf/'.($result[9]).'/'.$result[10].'">'.$result[10].'</a>' : '';*/
                          $onclick = "download_training_schedule_api('".$result[10]."','".$result[9]."')";

                          echo '<input type="hidden" id="training_file_download_'.$sr_no.'" value="'.$result[9].'" />';
                          echo isset($result[10]) && $result[10] != "" ? '<a onclick="'.$onclick.'" href="javascript:void(0);">'.$result[10].'</a>' : ''; 

                      ?></td> 
                        <td><?php echo isset($result[11]) && $result[11] != "" ? $result[11] : ''; ?></td>
                      </tr>
              <?php
                    $sr_no++;
                     
                  }
                }
              }
              else
              {
                echo "<tr><td colspan='12'>No Data!..</td></tr>";
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

    $this->load->view('website_training_schedule_dynamic_api');
  }

  function Show_Brochure_File_Pdf_Download()
  {
    $Brochure_File_Pdf = trim($_POST['binary_pdf_data']);
    $file_name = trim($_POST['file_name']);
    $file_cont = base64_decode($Brochure_File_Pdf);
    //$file_cont = $Brochure_File_Pdf;

    $certificate_time = date('YmdHis');
    //$certificate_name = $file_name.'.pdf';
    $certificate_name = $file_name;
    $certificate_path = 'uploads/rahultest/sagar/' . $certificate_name;
    file_put_contents('./' . $certificate_path, $file_cont);

    //header('Content-Type: application/pdf');
    //header('Content-disposition: attachment;filename='.$certificate_path);
    //readfile($certificate_path);
    $url = "https://iibf.esdsconnect.com/staging/".$certificate_path;
    //echo json_encode($url);
    echo ($url);
    die;
  }

  function download_training_pdf_file(){
      // Check if the file exists
      $file_path = 'uploads/rahultest/sagar/SupervisionStatusReport.pdf';
      if (file_exists($file_path)) {
          // Set headers
          header('Content-Description: File Transfer');
          header('Content-Type: application/pdf');
          header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
          header('Expires: 0');
          header('Cache-Control: must-revalidate');
          header('Pragma: public');
          header('Content-Length: ' . filesize($file_path));
          
          // Clear output buffer
          ob_clean();
          flush();
          
          // Read the file and send it to the output buffer
          readfile($file_path);
          exit;
      } else {
          // File doesn't exist
          http_response_code(404);
          echo "File not found.";
      }
  }

  function Show_Brochure_File_Pdf($Brochure_File_Pdf='', $file_name='')
  {
     
    $file_cont = base64_decode($Brochure_File_Pdf);
    //$file_cont = $Brochure_File_Pdf;

    $certificate_time = date('YmdHis');
    $certificate_name = 'certificate_1.pdf';
    $certificate_path = 'uploads/rahultest/sagar/' . $certificate_name;
    file_put_contents('./' . $certificate_path, $file_cont);

    header('Content-Type: application/pdf');
    header('Content-disposition: attachment;filename='.$certificate_path);
    readfile($certificate_path);


    /*End: eCertificate Code*/

  }

  public function test_api_url($value = '')
  {
    //echo '<br>Start: '; 
    $final_arr = $response_msg = array();
    $response = '';

    //$url = "http://10.10.233.66:8088/ExamScheduleApi/getExamScheduleDtls"; //UAT
    //$url = "http://10.10.233.76:8092/ExamScheduleApi/getExamScheduleDtls"; //PRODUCTION
    //$url = "http://10.10.233.76:8093/fedaieligibleapi/getFedaiEligible/1009/811/510280156";
    $url = "http://10.10.233.76:8096/TrainingScheduleApi/getTrainingScheduleDtls"; // Trainig Schedule API
    

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
      echo '<br>response_msg : '.$response_msg = curl_error($x);
    }
    else
    {
      if ($api_result)
      {
        //echo ($api_result);
        $api_result = json_decode($api_result);

        echo '<pre>';
        print_r($api_result);
        echo '</pre>';
        exit;
        
        if (isset($api_result->status) && $api_result->status != 200)
        {
          echo '<pre>';
          print_r($api_result);
          echo '</pre>';
        }
        else
        {
          echo '<p style="text-align: center;"><strong>Test API</strong></p>'; 
        }
      }
    }
  }

  /*End: Training Schedule API*/

  public function upload_img_custom()
  {
    echo '<br>source_path : ' . $source_path = './uploads/';
    echo '<br>img_name : ' . $img_name = "p_510259010.jpg";

    echo '<br>destination_path : ' . $destination_path = "/uploads/photograph/";
    $outputphoto = getcwd() . $destination_path . $img_name;
    file_put_contents($outputphoto, file_get_contents($source_path . $img_name));

    echo '<br>Uploaded Image : ' . base_url() . $destination_path . $img_name;
  } 

  public function list_images() {
    $this->load->helper('url');
    // Define the folder path
    $folder_path = './uploads/scansignature/';

    // Get the full server path to the folder
    $full_folder_path = FCPATH . $folder_path;

    // Get all image files from the folder
    $images = $this->get_images_from_folder_today($full_folder_path);

    echo base_url($folder_path);

    if(count($images) > 0)
    {
      foreach($images as $res)
      {
        $headers = @get_headers(base_url().$folder_path.$res);
        if(stripos($headers[0], "200 OK"))
        {
          list($width, $height) = getimagesize(base_url().$folder_path.$res);
          //echo "<br>Image exists. Width: $width px, Height: $height px";
          if($width > 500 || $height > 500)
          {
            echo '<br>'.$res.' width : '.$width.' & height'.$height;
            $this->master_model->resize_admitcard_images($folder_path.$res);
          }
        }
      }
    }
    
  }

  private function get_images_from_folder_today($folder_path) 
  {
    // Check if the folder exists
    if (!is_dir($folder_path)) {
        return [];
    }

    // Read the contents of the folder
    $files = scandir($folder_path);

    // Get today's date
    $today = date('Y-m-d', strtotime("-6days"));

    // Filter out non-image files and files not uploaded today
    $images = array_filter($files, function($file) use ($folder_path, $today) {
        $file_path = $folder_path . DIRECTORY_SEPARATOR . $file;
        if (is_file($file_path) && $this->is_image($file_path)) {
            // Check if the file was modified today
            /* $file_date = date('Y-m-d', filemtime($file_path));
            return $file_date === $today; */
            return true;
        }
        return false;
    });

    return $images;
}

  private function is_image($file_path) {
      // Check if the file is an image
      $image_info = getimagesize($file_path);
      return $image_info && in_array($image_info['mime'], ['image/jpeg', 'image/png', 'image/gif']);
  }


  public function get_client_ip_email()
  {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
      $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
      $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
      $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
      $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
      $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
      $ipaddress = getenv('REMOTE_ADDR');
    else
      $ipaddress = 'UNKNOWN';
    return $ipaddress;
  }

  public function settle_member_institute() {
    $member_nos = array(510624196,510624208,510624215);
    $this->db->where_in('regnumber', $member_nos);
    $user_infos   = $this->master_model->getRecords('member_registration', array(
      'associatedinstitute'     => '',
      'registrationtype' => 'O',
      'isactive'  => '1',
    ));
    //echo $this->db->last_query();

    if(count($user_infos)) {
      foreach($user_infos as $user_info) {
        $this->db->like('title', 'Ordinory member insert array :'.$user_info['regid']);
        $log   = $this->master_model->getRecords('userlogs', array(
          'regid'     => $user_info['regid'],
          
        ));
          foreach($log as $logrow) {
            $description = unserialize($logrow['description']);
           // echo'<pre>';print_r($description);exit;
           $update_data['associatedinstitute'] = $description['associatedinstitute'];
           $this->master_model->updateRecord('member_registration', $update_data, array(
            'regid'     => $user_info['regid'],
            'regnumber' => $user_info['regnumber'],
           'associatedinstitute'=>''
           ));
           echo $this->db->last_query().'<br>';
          }
      }
    }
  }

  public function copy_bc_bank_card_images()
  {

    /*SELECT mr.regid,mr.regnumber,mr.bank_bc_id_card,pt.exam_code FROM `member_registration` mr INNER JOIN payment_transaction pt ON pt.member_regnumber = mr.regnumber AND pt.status = '1' AND pt.exam_code IN (1046,1047,1052,1053,1054) WHERE bank_bc_id_card LIKE "%non_mem_empidproof_%" ORDER BY `mr`.`bank_bc_id_card` ASC; */

    $select = 'mr.regid,mr.regnumber,mr.bank_bc_id_card,pt.exam_code'; 
    
    $this->db->join('payment_transaction pt', 'pt.member_regnumber=mr.regnumber AND pt.status = "1" AND pt.exam_code IN (1046,1047,1052,1053,1054)', 'INNER'); 
    $this->db->limit('500');
    $member_registration_data = $this->Master_model->getRecords('member_registration mr', array( 
          'mr.bank_bc_id_card LIKE ' => "%non_mem_empidproof_%"
    ), $select);

    //echo $this->db->last_query();

    $i = 1;
    if($member_registration_data){
      foreach($member_registration_data as $res){
        if($res["bank_bc_id_card"] != ""){
          $file_nm = "uploads/empidproof/".$res["bank_bc_id_card"];
          if(file_exists($file_nm)) 
          { 
            $new_file_expload = explode(".", $res["bank_bc_id_card"]);
            $new_file_extension = strtolower(end($new_file_expload));
            $new_copy_file = "bank_bc_id_card_".$res["regnumber"].".".$new_file_extension;
            $succ = copy("./uploads/empidproof/".basename($file_nm), "./uploads/rahultest/bank_bc_id/".$new_copy_file); 
            if($succ){
              $insert_info = array('regid' => $res["regid"], 'regnumber' => $res["regnumber"], 'bank_bc_id_card' => $res["bank_bc_id_card"], 'new_bank_bc_id_card' => $new_copy_file, 'exam_code' => $res["exam_code"]);
              $this->master_model->insertRecord('mem_reg_bank_bc', $insert_info, true); 
              echo $i.") New Copy file: " .$new_copy_file. "<br>"; 

              $valid_member_reg_data = $this->Master_model->getRecords('member_registration', array( 
                    'regid' => $res['regid'],
                    'regnumber' => $res['regnumber']
              ), 'regid');

              if($valid_member_reg_data && count($valid_member_reg_data) > 0){
                 $update_data['bank_bc_id_card'] = $new_copy_file;
                 $this->master_model->updateRecord('member_registration', $update_data, array(
                  'regid'     => $res['regid'] 
                 ));
              }

               

            }
          }
        } 
        $i++;
      }
    }

    /*$i = 1;
    foreach($mem_array as $mem_no)
    {  
        //echo "1009_853_".$mem_no.".pdf"."<br>";
        $file_nm = "uploads/admitcardpdf/1009_853_".$mem_no.".pdf";
        if (file_exists($file_nm)) {
          echo $i.") Copy file: " . basename($file_nm) . "<br>";
          copy("./uploads/admitcardpdf/".basename($file_nm), "./uploads/rahultest/1009_pdf/".basename($file_nm)); 
        }
        $i++;
    }*/
  }



public function getFolderListAll() 
{
    $directory = FCPATH . 'uploads'; // Change to your target directory
    $folderList = $this->getFolderList($directory);
    
    echo "<pre>";
    print_r($folderList);
    echo "</pre>";
}

private function getFolderList($dir) {
    $folders = [];

    // Scan directory
    foreach (scandir($dir) as $folder) {
        if ($folder === '.' || $folder === '..') continue;
        
        $path = $dir . DIRECTORY_SEPARATOR . $folder;
        
        if (is_dir($path)) {
            //$fileCount = $this->countFiles($path);
            $folders[] = [
                'folder' => $folder,
                'path' => $path,
                //'file_count' => $fileCount,
                'subfolders' => $this->getFolderList($path) // Recursive call for subfolders
            ];
        }
    }

    return $folders;
}

private function countFiles($dir) {
    $fileCount = 0;
    foreach (scandir($dir) as $file) {
        if ($file !== '.' && $file !== '..' && is_file($dir . DIRECTORY_SEPARATOR . $file)) {
            $fileCount++;
        }
    }
    return $fileCount;
}

Function test()
{
  $final_arr = $response_msg = array();
			$response = '';
			
			//$url="http://10.10.233.66:8082/professionalbankersapi/getProfessionalBankersDetails/".$exam_code."/".$member_number; //for staging
			
			$url="http://10.10.233.76:8086/professionalbankersapi/getProfessionalBankersDetails/".$exam_code."/".$member_number; //for production
						
			$string = preg_replace('/\s+/', '+', $url);
			$x = curl_init($string);
			curl_setopt($x, CURLOPT_HEADER, 0);    
			curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);
			curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);    
			curl_setopt($x, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($x, CURLOPT_SSL_VERIFYPEER, FALSE);
			
			$result = curl_exec($x);
			
			if(curl_errno($x)) //CURL ERROR
			{
				$response = 'error';
				$response_msg = curl_error($x);
			}
			else
			{
				$response = "success"; 
				$response_msg = $result;
				
				//Response string : exam_code, member_number, eligible or not (Y/N), EXM CD1, Y/N, EXM CD2, Y/N, EXM CD3, Y/N, EXM CD4, Y/N, EXM CD5, Y/N, EXM CD6, Y/N
				// If exam code is 0, then ignore that exam code
			}
			curl_close($x);
			
			$final_arr['response'] = $response;
			$final_arr['response_msg'] = $response_msg;
			
			if($return_flag == 0)
			{
				return $final_arr;
			}
			else
			{
				print_r(json_encode($final_arr));
			}
}


 function disa_api_curl($exam_code = 990, $exam_period =904, $member_number = 510393613)
  {
    $final_arr = $response_msg = array();
    $flag = 'error';
    $eligible_flag = '';

    $url = "http://10.10.233.66:8094/disaeligibleapi/getDisaEligible/" . $exam_code . "/" . $exam_period . "/" . $member_number; //for staging
    // $url = "http://10.10.233.76:8094/disaeligibleapi/getDisaEligible/" . $exam_code . "/" . $exam_period . "/" . $member_number; //for production

    $string = preg_replace('/\s+/', '+', $url);
    $x = curl_init($string);
    curl_setopt($x, CURLOPT_HEADER, 0);
    curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($x, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($x, CURLOPT_SSL_VERIFYPEER, FALSE);

    $result = curl_exec($x);
    print_r($result);exit;

    if (curl_errno($x)) //CURL ERROR
    {
      $response_msg = curl_error($x);
    }
    else
    {
      $response_msg = $result;
      //Response string : EXAM_ID, MEMBERSHIP_NO, ELIGIBLE_FLAG ( 'N' - Not Eligible / 'Y' - Eligible)

      if ($result != "")
      {
        $result_arr = json_decode($result, true);
        if (isset($result_arr[0]) && count($result_arr[0]) > 0)
        {
          if (isset($result_arr[0][0]) && $result_arr[0][0] == $exam_code && isset($result_arr[0][1]) && $result_arr[0][1] == $member_number)
          {
            if (isset($result_arr[0][2]))
            {
              $flag = "success";

              $response_flag = $result_arr[0][2];
              if ($response_flag == 'Y')
              {
                $eligible_flag = $response_flag;
              }
            }
          }
        }
      }
    }
    curl_close($x);

    $final_arr['flag'] = $flag;
    $final_arr['api_endpoint'] = $url;
    $final_arr['response_msg'] = $response_msg;
    $final_arr['eligible_flag'] = $eligible_flag;
    return $final_arr;
  }

public function isCfpEligible1()
		{
			$service_url = 'http://10.10.233.66:8084/cfpapi/getExamCodeByMemNo/100000059;
			/510208326';
      // print_r($service_url);exit;
			$curl = curl_init($service_url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			$curl_response = curl_exec($curl);
			print_r($curl_response);
			curl_close($curl);
			exit;
		}

    public function vp_main($value = '')
  {
    $this->load->model('billdesk_pg_model');
    $enc_res = "eyJhbGciOiJIUzI1NiIsImNsaWVudGlkIjoiaW5kaW5zYmFmIiwia2lkIjoiSE1BQyJ9.eyJtZXJjaWQiOiJJTkRJTlNCQUYiLCJ0cmFuc2FjdGlvbl9kYXRlIjoiMjAyMy0wOS0wM1QyMTo0OToyNiswNTozMCIsInN1cmNoYXJnZSI6IjAuMDAiLCJwYXltZW50X21ldGhvZF90eXBlIjoidXBpIiwiYW1vdW50IjoiMTc3MC4wMCIsInJ1IjoiaHR0cHM6Ly9paWJmLmVzZHNjb25uZWN0LmNvbS9yZWdpc3Rlci9oYW5kbGVfYmlsbGRlc2tfcmVzcG9uc2UiLCJvcmRlcmlkIjoiODEyNDI4ODQxIiwidHJhbnNhY3Rpb25fZXJyb3JfdHlwZSI6InN1Y2Nlc3MiLCJkaXNjb3VudCI6IjAuMDAiLCJwYXltZW50X2NhdGVnb3J5IjoiMTAiLCJiYW5rX3JlZl9ubyI6IjMyNDY0NzI1ODk1OSIsInRyYW5zYWN0aW9uaWQiOiJaSEQ1MTM2Nzk3NDcwNCIsInR4bl9wcm9jZXNzX3R5cGUiOiJpbnRlbnQiLCJiYW5raWQiOiJIRDUiLCJhZGRpdGlvbmFsX2luZm8iOnsiYWRkaXRpb25hbF9pbmZvNyI6Ik5BIiwiYWRkaXRpb25hbF9pbmZvNiI6Ik5BIiwiYWRkaXRpb25hbF9pbmZvOSI6Ik5BIiwiYWRkaXRpb25hbF9pbmZvOCI6Ik5BIiwiYWRkaXRpb25hbF9pbmZvMTAiOiJOQSIsImFkZGl0aW9uYWxfaW5mbzEiOiI4MTEwNDI1IiwiYWRkaXRpb25hbF9pbmZvMyI6IjgxMTA0MjUiLCJhZGRpdGlvbmFsX2luZm8yIjoiaWliZnJlZ24iLCJhZGRpdGlvbmFsX2luZm81IjoiTkEiLCJhZGRpdGlvbmFsX2luZm80IjoiODEyNDI4ODQxIn0sIml0ZW1jb2RlIjoiRElSRUNUIiwidHJhbnNhY3Rpb25fZXJyb3JfY29kZSI6IlRSUzAwMDAiLCJjdXJyZW5jeSI6IjM1NiIsImF1dGhfc3RhdHVzIjoiMDMwMCIsInRyYW5zYWN0aW9uX2Vycm9yX2Rlc2MiOiJUcmFuc2FjdGlvbiBTdWNjZXNzZnVsIiwib2JqZWN0aWQiOiJ0cmFuc2FjdGlvbiIsImNoYXJnZV9hbW91bnQiOiIxNzcwLjAwIn0.rPDuwJTKSA0ct7UbvYLDMzPTWCxqBjKirpcp-J4xZbA";
    $bd_response            = $this->billdesk_pg_model->verify_res($enc_res);
    echo "<pre>";
    print_r($bd_response);
  }

   function getcentrenonavailability($venue_code=954618)
       {
   
        $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,'https://iibf.cscexams.in/backend/web/user/getcentrenonavailability');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		$response=(array)json_decode($response);

    // print_r($response);exit;
		
		$i=0;$disabledDates=array();$weeklyOff='';

		foreach($response as $currData) {
			$currData = (array)$currData;
            if($currData['venue_code']==$venue_code) {
                if($currData['weekly_off']!=null && $currData['weekly_off']!='')
			        $weeklyOff=$currData['weekly_off'];
             //   echo $currData['weekly_off'];
            }
			if($currData['venue_code']==$venue_code && $currData['off_duty_from']!='' && $currData['off_duty_to']!='') {
				//echo'<pre>';print_r($currData);
				//$disabledDates[$i]['off_duty_from']=$currData['off_duty_from'];
				//$disabledDates[$i]['off_duty_to']=$currData['off_duty_to'];
				$period = new DatePeriod(
					new DateTime($currData['off_duty_from']),
					new DateInterval('P1D'),
					new DateTime($currData['off_duty_to'])
				);
				foreach ($period as $key => $value) {
				$disabledDates[]=$value->format('Y-m-d');
				//echo $value->format('Y-m-d').'<br>';
				}
				$i++;
                $disabledDates[]=date('Y-m-d',strtotime($currData['off_duty_to']));
                
			}
		}
		return array('disabledDates'=>$disabledDates,'weeklyOff'=>$weeklyOff);
       }



       public function send_sms_common_all($mobile_no='', $message='', $template_id='', $sender_id='', $exam_code='', $route='', $call_cnt=0)
    {
      if($this->get_client_ip_master() =='115.124.115.75')
      {
        if(!in_array($mobile_no, array('7588096918', '9527676118'))) { return 1; }
      }
      $return_arr = $add_log = array();
      $status = $response = $data_string = '';
      $sms_user = 'IIBF';
      $sms_api_key = 'c6b75a20f6XX';
      $sms_entityid = '1701162807222263362';

      $this->load->helper('url');
      $add_log['mobile_no'] = json_encode($mobile_no);
      $add_log['message'] = json_encode($message);
      $add_log['template_id'] = $template_id;
      $add_log['api_key'] = $sms_api_key;
      $add_log['sms_user'] = $sms_user;
      $add_log['sms_entityid'] = $sms_entityid;
      $add_log['sender_id'] = $sender_id;
      $add_log['exam_code'] = $exam_code;
      $add_log['data_string'] = $data_string;
      $add_log['class_name'] = $this->router->fetch_class();
      $add_log['method_name'] = $this->router->fetch_method();

      if(isset($_SERVER['SERVER_ADDR'])) { $add_log['server_ip'] = $_SERVER['SERVER_ADDR']; }
      else { $add_log['server_ip'] = ''; }

      $add_log['current_url'] = current_url();      
      $add_log['created_on'] = date('Y-m-d H:i:s');      
      $sms_log_id = $this->insertRecord('sms_log_mobicomm',$add_log, true);
      
      if($mobile_no != '' && $message != '' && $template_id != '')
      {
        $fun_mobile_no = $mobile_no;

        if($route == '') { $route = 'transactional'; }
        if($sender_id == '') { $sender_id = 'IIBFCO'; }

        $mobile_no = '+91'.str_replace(",",",+91",$mobile_no);

        $xml_data = 'user='.$sms_user.'&key='.$sms_api_key.'&mobile='.$mobile_no.'&message='.$message.'&senderid='.$sender_id.'&accusage=1&entityid='.$sms_entityid.'&tempid='.$template_id;
        
        $ch = curl_init("http://redirect.ds3.in/submitsms.jsp?");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');            
        curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 75);
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        // echo "Error : ".$err;
        // echo "Response : ".$response; exit;        
   print_r($response);exit;
        if ($err) 
        {
          $return_arr['status'] = $status = 'fail';
          $return_arr['message'] = $err;

          $this->updateRecord('sms_log_mobicomm', array('response'=>json_encode($err), 'sender_id'=>$sender_id, 'updated_on'=>date('Y-m-d H:i:s')), array('id' => $sms_log_id));

          /* if($call_cnt == 0)
          {
            $call_cnt++;
            $this->send_sms_common_all($fun_mobile_no, $message, $template_id, $sender_id, $exam_code, $route, $call_cnt);
          } */
        } 
        else 
        {
          $response_arr = explode(",",$response); 
          if(count($response_arr) > 0 && trim($response_arr[0]) == 'fail')  
          {
            $return_arr['status'] = $status = 'fail';
            
            $this->updateRecord('sms_log_mobicomm', array('response'=>trim($response), 'sender_id'=>$sender_id, 'updated_on'=>date('Y-m-d H:i:s')), array('id' => $sms_log_id));

            if (strpos($response, 'InsufficientBalance') !== false)
            {
              $this->load->model('Emailsending');
              $info_arr = array('to' => array('iibfdevp@esds.co.in', 'sagar.matale@esds.co.in'), 'from' => 'logs@iibf.esdsconnect.com', 'subject' => 'SMS balance is over', 'message' => trim($response));
              $this->Emailsending->mailsend($info_arr);
            }
          }
          else
          {
            $return_arr['status'] = $status = 'success';
            
            $this->updateRecord('sms_log_mobicomm', array('response'=>trim($response), 'sender_id'=>$sender_id, 'updated_on'=>date('Y-m-d H:i:s')), array('id' => $sms_log_id));
          }
          $return_arr['message'] = $response;
          $return_arr['data'] = $xml_data;
        }
      }
      else
      {
        $return_arr['status'] = $status = 'fail';
        $return_arr['message'] = 'Invalid parameter supplied to function';

        $this->updateRecord('sms_log_mobicomm', array('response'=>'Invalid parameter supplied to function', 'updated_on'=>date('Y-m-d H:i:s')), array('id' => $sms_log_id));
      }
      
      return $return_arr;         
    }//END : FUNCTION FOR SENDING SMS USING mobicomm GATEWAY. ADDED ON 07-09-2023



    function check_receipt_no()
    {
      //$this->generate_csc_reeipt_no1();
      echo $this->generate_csc_receipt_no2();
    }

    function generate_csc_reeipt_no1()
    {
      echo '<br>generate_csc_reeipt_no1 : '.$receipt_no = rand(99999988, 99999999);
      $chk_exist1 = $this->master_model->getRecords('payment_transaction',array('receipt_no'=>$receipt_no),'receipt_no');
      
      if(count($chk_exist1) == 0)
      {
        $chk_exist2 = $this->master_model->getRecords('iibfbcbf_payment_transaction',array('receipt_no'=>$receipt_no),'receipt_no');
        
        if(count($chk_exist2) == 0)
        {
          return $receipt_no;
        }
        else
        {
          $this->generate_csc_reeipt_no1();
        }
      }
      else
      {
        $this->generate_csc_reeipt_no1();
      }
    }

    function generate_csc_receipt_no2()
    {
      do 
      {
        $receipt_no = rand(22222222, 99999999);

        $exists_in_main = $this->master_model->getRecords('payment_transaction', ['receipt_no' => $receipt_no], 'receipt_no');
        $exists_in_secondary = $this->master_model->getRecords('iibfbcbf_payment_transaction', ['receipt_no' => $receipt_no], 'receipt_no');

      } while (!empty($exists_in_main) || !empty($exists_in_secondary));

      return $receipt_no;
    }

}
