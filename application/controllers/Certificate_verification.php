<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Certificate_verification extends CI_Controller
{
  public $UserID;

  public function __construct()
  {
    parent::__construct();
    $this->load->library('upload');
    $this->load->helper('upload_helper');
    $this->load->helper('master_helper');
    $this->load->library('email');
    $this->load->model('Emailsending');
    $this->load->model('log_model');
  }
  /* function to detect mobile device added on 23 Jun 2021*/
  public function isMobileDevice()
  {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
  }

  public function index()
  {
    $array = [["1", "2"]];;

    $data = array();
    $data['api_res'] = array();
    $data['post_count'] = 0;

    //cpatcha generation

    if (isset($_POST) && count($_POST) > 0)
    {
      $this->form_validation->set_rules('member_no', 'Registration/Membership No.', 'trim|required|xss_clean', array('required' => 'Please enter the %s'));
      $this->form_validation->set_rules('certificate_number', 'certificate number', 'required|trim|xss_clean', array('required' => 'Please enter the %s'));
      $this->form_validation->set_rules('certificate_date', 'certificate date', 'required|trim|xss_clean', array('required' => 'Please enter the %s'));

      $this->form_validation->set_rules('captcha_code', 'Security Code', 'trim|callback_check_login_captcha|xss_clean', array('required' => 'Please enter the %s'));

      if ($this->form_validation->run() == TRUE)
      {
        $user_info = $this->master_model->getRecords('member_registration', array('regnumber' => $this->input->post('member_no'), 'isactive' => '1'), 'regid');

        //print_r($user_info);
        if (!empty($user_info) && count($user_info) > 0)
        {
          $cert_date = date('Y-m-d', strtotime($this->input->post('certificate_date')));
          $data['api_res'] = $api_res = $this->getBCBFdata($this->input->post('member_no'), $this->input->post('certificate_number'), $cert_date);
          $data['post_count'] = count($_POST);
          if (array_key_exists('status', $api_res) && $api_res['status'] == '400')
          {

            $data['api_res'] = $api_res = array();
            // $this->session->set_flashdata('error','Please login again');
            redirect(base_url() . 'certificate_verification');
          }
        }
        else
        {
          $data['error'] = "Invalid Membership/Registration No. Please register using following form. ";
        }
      }
    }

    /*$this->load->helper('captcha');
        $this->session->set_userdata("bcbf_app_cap", rand(1, 100000));
        $vals = array(
            'img_path' => './uploads/applications/',
            'img_url'  => base_url() . 'uploads/applications/',
        );
        $cap                    = create_captcha($vals);
        $_SESSION["bcbf_app_cap"] = $cap['word'];*/
    $this->load->model('Captcha_model');
    $captcha_img = $this->Captcha_model->generate_captcha_img('bcbf_app_cap');

    $data['image'] = $captcha_img;
    $this->load->view('certificate_verification', $data);
  }
  public function reset()
  {
    $data = '';
    redirect(base_url() . 'certificate_verification');
  }


  public function getExams($regno)
  {
    $service_url = 'http://10.10.233.66:8082/dcertapi/getExamCodeByMemNo/510434621';
    ##Staging URL
    //$service_url = 'http://10.10.233.66:8082/dcertapi/getExamCodeByMemNo/'.$regno;

    ## Live url
    //$service_url = 'http://10.10.233.76:8082/dcertapi/getExamCodeByMemNo/'.$regno;

    $curl = curl_init($service_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $curl_response = curl_exec($curl);
    curl_close($curl);
    $json_objekat = json_decode($curl_response);
    //print_r($json_objekat);exit;
    ## Get exam name
    $exam_array = array();
    foreach ($json_objekat as $exams)
    {
      //print_r($exams);
      foreach ($exams as $key => $value)
      {
        # Get exam name by code
        $exam_code              = $value;
        $result                 = $this->master_model->getRecords('exam_master', array('exam_code' => $exam_code), true);
        $exam_desc              = $result[0]['description'];
        $exam_array[$exam_code] = $exam_desc;
      }
    }
    return $exam_array;
  }
  public function getBCBFdata_temp()
  {
    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);
    ## Staging url
    ## Live Url
    // $service_url = 'http://10.10.233.76:8082/dcertapi/getDataByExamCodeAndMemNo/' . $exam_code . '/' . $regno;

    // $service_url = 'http://10.10.233.66:8083/bcbfapi/getExamCodeByMemNo/845037926/20404/2009-11-10';

    // $service_url ='http://10.10.233.76:8087/bcbfapi/getExamCodeByMemNo/802207406/195690/2023-04-20';

    // $service_url = 'http://10.10.233.76:8084/bcbfapi/getExamCodeByMemNo/'.$member_no.'/'.$certificate_number.'/'.$certificate_date;

    $service_url = 'http://10.10.233.76:8087/bcbfapi/getExamCodeByMemNo/' . $member_no . '/' . $certificate_number . '/' . $certificate_date;

    $curl = curl_init($service_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $curl_response = curl_exec($curl);
    if (curl_errno($curl))
    {
      echo $error_msg = curl_error($curl);
      die;
    }

    curl_close($curl);


    return $curl_response;
  }

  public function getBCBFdata($member_no, $certificate_number, $certificate_date)
  {
    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);
    ## Staging url
    ## Live Url
    // $service_url = 'http://10.10.233.76:8082/dcertapi/getDataByExamCodeAndMemNo/' . $exam_code . '/' . $regno;

    // $service_url = 'http://10.10.233.76:8087/bcbfapi/getExamCodeByMemNo/802207406/195690/2023-04-20';

    // $service_url = 'http://10.10.233.76:8087/bcbfapi/getExamCodeByMemNo/'.$member_no.'/'.$certificate_number.'/'.$certificate_date;

    $service_url = 'http://10.10.233.66:8086/certificateApi/getCertVerByCertNo/' . $member_no . '/' . $certificate_number . '/' . $certificate_date; //new API added by sagar m on 2023-11-29. (Mail subject : API for Certificate Verification for all exams - Reg.)
 
    $curl = curl_init($service_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $curl_response = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($curl_response, true);
    // print_r($result);die;
    return $result;
  }
  // reload captcha functionality
  public function generatecaptchaajax()
  {
    /*$this->load->helper('captcha');
        $this->session->unset_userdata("bcbf_app_cap");
        $this->session->set_userdata("bcbf_app_cap", rand(1, 100000));
        $vals = array('img_path' => './uploads/applications/', 'img_url' => base_url() . 'uploads/applications/',
        );
        $cap                    = create_captcha($vals);
        $data                   = $cap['image'];
        $_SESSION["bcbf_app_cap"] = $cap['word'];*/

    $this->load->model('Captcha_model');
    $captcha_img = $this->Captcha_model->generate_captcha_img('bcbf_app_cap');

    echo $captcha_img;
  }
  //call back for check captcha server side
  public function check_login_captcha()
  {


    $session_name = 'bcbf_app_cap';
    $session_captcha = '';
    if (isset($_SESSION[$session_name]))
    {
      $session_captcha = $_SESSION[$session_name];
    }

    $captcha_code = $this->security->xss_clean(trim($this->input->post('captcha_code')));

    if ($captcha_code == $session_captcha)
    {
      return TRUE;
    }
    else
    {
      $this->form_validation->set_message('check_login_captcha', 'Please enter correct code');
      return FALSE;
    }
  }

  public function check_captcha_code_ajax()
  {
    if (isset($_POST) && count($_POST) > 0)
    {
      $session_name = 'bcbf_app_cap';
      $session_captcha = '';

      if (isset($_POST['session_name']) && $_POST['session_name'] != "")
      {
        $session_name = $this->security->xss_clean(trim($this->input->post('session_name')));
      }

      if (isset($_SESSION[$session_name]))
      {
        $session_captcha = $_SESSION[$session_name];
      }

      $captcha_code = $this->security->xss_clean(trim($this->input->post('captcha_code')));

      if ($captcha_code == $session_captcha)
      {
        echo 'true';
      }
      else
      {
        echo "false";
      }
    }
    else
    {
      echo "false";
    }
  }


  
  public function check_member_no_ajax()
  {
    $result['flag'] = 'error';
    $result['response'] = 'Please enter valid Membership/Registration No';

    if (isset($_POST) && $_POST['member_no'] != "")
    {

      $member_no = $this->security->xss_clean(trim($this->input->post('member_no')));
      $member_info = array();
      $member_info = $this->master_model->getRecords('member_registration', array('regnumber' => $member_no, 'isactive' => '1'), 'regid');
      // echo $this->db->last_query();die;
      if (!empty($member_info) && count($member_info) > 0)
      {
        $result['flag'] = 'success';
      }
    }

    echo json_encode($result);
  }

  public function verify_certificate($member_no, $certificate_number,$certificate_date)
  {
    $service_url = 'http://10.10.233.66:8086/certificateApi/getCertVerByCertNo/'.$member_no.'/'.$certificate_number.'/'.$certificate_date; //new API added by sagar m on 2023-11-29. (Mail subject : API for Certificate Verification for all exams - Reg.)
    
    $curl = curl_init($service_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $curl_response = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($curl_response, true);
    
    if(isset($result) && is_array($result) && count($result) > 0)
    {
      if(isset($result['status']) && $result['status'] == '404')
      { ?>        
        <table style="border: 1px solid #000; border-collapse: collapse; margin: 30px auto;">
          <thead><th colspan="2" style="text-align: center;font-size: 18px;padding: 5px 10px;border: 1px solid #000;">Certificate details</th></thead>
          <tbody>
            <tr><td colspan="2" style="font-size: 16px;padding: 5px 10px;border: 1px solid #000;">Certificate details not found</td></tr>
          </tbody>
        </table>
      <?php }
      else
      { ?>
        <table style="border: 1px solid #000; border-collapse: collapse; margin: 30px auto;">
          <thead><th colspan="2"style="text-align: center;font-size: 18px;padding: 5px 10px;border: 1px solid #000;">Certificate details</th></thead>
          <tbody>
            <tr><td  style="font-size: 16px;padding: 5px 10px;border: 1px solid #000;">Membership/Registration no</td><td  style="font-size: 16px;padding: 5px 10px;border: 1px solid #000;"><?php if(isset($result[0]) && count($result[0]) > 0 && isset($result[0][0]) && $result[0][0] != "") { echo $result[0][0]; } else { echo 'Not Available'; } ?></td></tr>
            <tr><td  style="font-size: 16px;padding: 5px 10px;border: 1px solid #000;">Candidate Name</td><td  style="font-size: 16px;padding: 5px 10px;border: 1px solid #000;"><?php if(isset($result[0]) && count($result[0]) > 0 && isset($result[0][1]) && $result[0][1] != "") { echo $result[0][1]; } else { echo 'Not Available'; } ?></td></tr>
            <tr><td  style="font-size: 16px;padding: 5px 10px;border: 1px solid #000;">Exam Name</td><td  style="font-size: 16px;padding: 5px 10px;border: 1px solid #000;"><?php if(isset($result[0]) && count($result[0]) > 0 && isset($result[0][2]) && $result[0][2] != "") { echo $result[0][2]; } else { echo 'Not Available'; } ?></td></tr>
            <tr><td  style="font-size: 16px;padding: 5px 10px;border: 1px solid #000;">Certificate date</td><td  style="font-size: 16px;padding: 5px 10px;border: 1px solid #000;"><?php if(isset($result[0]) && count($result[0]) > 0 && isset($result[0][3]) && $result[0][3] != "") { echo $result[0][3]; } else { echo 'Not Available'; } ?></td></tr>
            <tr><td  style="font-size: 16px;padding: 5px 10px;border: 1px solid #000;">Certificate Sr. number</td><td  style="font-size: 16px;padding: 5px 10px;border: 1px solid #000;"><?php if(isset($result[0]) && count($result[0]) > 0 && isset($result[0][4]) && $result[0][4] != "") { echo $result[0][4]; } else { echo 'Not Available'; } ?></td></tr>
            <tr><td  style="font-size: 16px;padding: 5px 10px;border: 1px solid #000;">Date of Birth</td><td  style="font-size: 16px;padding: 5px 10px;border: 1px solid #000;"><?php if(isset($result[0]) && count($result[0]) > 0 && isset($result[0][5]) && $result[0][5] != "") { echo $result[0][5]; } else { echo 'Not Available'; } ?></td></tr>
            <tr><td  style="font-size: 16px;padding: 5px 10px;border: 1px solid #000;">Employee ID</td><td  style="font-size: 16px;padding: 5px 10px;border: 1px solid #000;"><?php if(isset($result[0]) && count($result[0]) > 0 && isset($result[0][6]) && $result[0][6] != "") { echo $result[0][6]; } else { echo 'Not Available'; } ?></td></tr>
            <tr><td  style="font-size: 16px;padding: 5px 10px;border: 1px solid #000;">ID Proof Number</td><td  style="font-size: 16px;padding: 5px 10px;border: 1px solid #000;"><?php if(isset($result[0]) && count($result[0]) > 0 && isset($result[0][7]) && $result[0][7] != "") { echo $result[0][7]; } else { echo 'Not Available'; } ?></td></tr>
            <tr><td  style="font-size: 16px;padding: 5px 10px;border: 1px solid #000;">Email ID</td><td  style="font-size: 16px;padding: 5px 10px;border: 1px solid #000;"><?php if(isset($result[0]) && count($result[0]) > 0 && isset($result[0][8]) && $result[0][8] != "") { echo $result[0][8]; } else { echo 'Not Available'; } ?></td></tr>
          </tbody>
        </table>
      <?php }
    }
    else
    { ?>
      <table style="border: 1px solid #000; border-collapse: collapse; margin: 30px auto;">
        <thead><th colspan="2"style="text-align: center;font-size: 18px;padding: 5px 10px;border: 1px solid #000;">Certificate details</th></thead>
        <tbody>
          <tr><td colspan="2" style="font-size: 16px;padding: 5px 10px;border: 1px solid #000;">Certificate details not found</td></tr>
        </tbody>
      </table>
    <?php }
  }
}
