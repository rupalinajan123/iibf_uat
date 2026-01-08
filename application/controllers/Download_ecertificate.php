<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Download_ecertificate extends CI_Controller
{
  public $UserID;

  public function __construct()
  {
    #echo '<br><br><h3><center>This page is under maintenance. <br><br><center></h3>'; exit;
    parent::__construct();
    $this->load->library('upload');
    $this->load->helper('upload_helper');
    $this->load->helper('master_helper');
    $this->load->library('email');
    $this->load->model('Emailsending');
    $this->load->model('log_model');

    $this->otptime = 60;
  }

  /* function to detect mobile device added on 23 Jun 2021*/
  public function isMobileDevice()
  {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
  }

  public function index()
  {
    $this->delete_old_files_from_server();

    $data = '';
    $this->load->model('Captcha_model');
    $data['captcha_img'] = $captcha_img = $this->Captcha_model->generate_captcha_img('DOWNLOAD_E_CERTIFICATE');

    if ($this->isMobileDevice()) {
      $this->load->view('download_ecertificate_mobile', $data);
    } else {
      $this->load->view('download_ecertificate', $data);
    }
  }

  public function reset()
  {
    $data = '';
    redirect(base_url() . 'Download_ecertificate');
  }

  public function getMemberDetails()
  {
    ///print_r($_POST);
    if (empty($_POST)) {
      redirect(base_url() . 'Download_ecertificate');
    }
    /*if($this->session->userdata('userinfo'))
		{
			$this->session->unset_userdata('userinfo');
		}*/

    $data['validation_errors'] = $sel_exam = '';
    if (ctype_space($_POST['member_no'])) {
      $this->session->set_flashdata('error', 'Please enter valid registration number.');
      redirect(base_url() . 'Download_ecertificate');
    }

    $otp_no = '';
    if (isset($_POST) && !empty($_POST['member_no'])) {
      $member_no = ltrim(rtrim($_POST['member_no']));
      $otp_no = ltrim(rtrim($_POST['verify_otp']));
      $exam = explode("##", $_POST['sel_exam']);
      $exam_code = $exam[1];

      $chk_otp_verified = $this->master_model->getRecords('verify_otp_ecertificate', array('regnumber' => $member_no, 'user_otp' => $otp_no, 'is_otp_verified' => 'y'), true);
      if (count($chk_otp_verified) > 0) {
        if ($exam_code == '210') {
          $exam_code = '21';
        } elseif ($exam_code == '600') {
          $exam_code = '60';
        }

        $download_cnt = 0;
        $download_cnt = $this->master_model->getRecordCount('download_certificate', array('regnumber' => $member_no, 'exam_code' => $exam_code));
        //echo '<br>>> 1'.$this->db->last_query();
        //exit;
        /*if(!empty($eligible_mem))
						$download_cnt = $eligible_mem[0]['download_cnt'];
					else     
						$download_cnt = 0;*/

        if ($download_cnt == 0  || $download_cnt < 3) {
          $result_data = $this->master_model->getRecords('member_registration', array('regnumber' => $member_no, 'isactive' => '1'), true);
          ///echo '<br>>> 2'.$this->db->last_query();
          if (!empty($result_data[0]['regnumber'])) {
            $result_data[0]['is_dra_mem'] = '1';
            /*if($result_data[0]['createdon'] < date('Y-m-d',strtotime('01-10-2019')))
							{
								$this->session->set_flashdata('error','You are not eligible to Download E-certificate.');
								redirect(base_url().'Download_ecertificate');
							}*/
          } else {
            $result_data = $this->master_model->getRecords('dra_members', array('regnumber' => $member_no), true);
            ///echo '<br>>> 3'.$this->db->last_query();
            $result_data[0]['is_dra_mem'] = '2';
          }
        } else {
          $this->session->set_flashdata('error', 'You are not eligible to Download E-certificate more than 3 times.');
          redirect(base_url() . 'Download_ecertificate');
        }
      } else {
        $this->session->set_flashdata('error', 'Invalid request.');
        redirect(base_url() . 'Download_ecertificate');
      }
    } else {
      $this->session->set_flashdata('error', 'Please enter valid registration number.');
      redirect(base_url() . 'Download_ecertificate');
    }

    ## Code to get certificate
    if (isset($_POST) && isset($_POST['btnSubmit'])) {
      $member_no = $_POST["member_no"];
      $exam = explode("##", $_POST['sel_exam']);
      $exam_code = $exam[1];
      $this->getCertificate($exam_code, $member_no);
    }

    $exams = '';
    $exams = $this->getExams($_POST["member_no"]);
    ///echo '<br>>> 4'; print_r($exams);
    if (empty($exams)) {
      $this->session->set_flashdata('error', 'No exam data found for registration number: ' . $_POST["member_no"]);
      redirect(base_url() . 'Download_ecertificate');
    }

    ///echo '<br>>> 5'; print_r($result_data);
    if (!empty($result_data)) {
      $data = array('middle_content' => 'download_certificate_next', 'result' => $result_data, 'exams' => $exams, 'otp_no' => $otp_no);
      $this->load->view('common_view_fullwidth', $data);
    } else {
      //$this->session->set_flashdata('error','Membership number not exist');
      $this->session->set_flashdata('error', 'You are not eligible to Download E-certificate.');
      redirect(base_url() . 'Download_ecertificate');
    }
  }

  public function getExams($regno)
  {
    //$service_url = 'http://10.10.233.66:8082/dcertapi/getExamCodeByMemNo/510434621';

    ##Staging URL
    //$service_url = 'http://10.10.233.66:8082/dcertapi/getExamCodeByMemNo/'.$regno;

    ## Live url
    $service_url = 'http://10.10.233.76:8082/dcertapi/getExamCodeByMemNo/' . $regno;

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
    foreach ($json_objekat as $exams) {
      //print_r($exams);
      foreach ($exams as $key => $value) {
        # Get exam name by code
        $exam_code = $value;
        if ($exam_code == '21') {
          $exam_code = '210';
        } elseif ($exam_code == '60') {
          $exam_code = '600';
        }
        $result = $this->master_model->getRecords('exam_master', array('exam_code' => $exam_code), true);
        $exam_desc = $result[0]['description'];
        $exam_array[$exam_code] = $exam_desc;
        #echo $this->db->last_query();die;
      }
    }
    return $exam_array;
  }

  public function getCertificate($exam_code, $regno)
  {
    if ($exam_code == '210') {
      $exam_code = '21';
    } elseif ($exam_code == '600') {
      $exam_code = '60';
    }

    ## Staging url
    //$service_url = 'http://10.10.233.66:8082/dcertapi/getDataByExamCodeAndMemNo/'.$exam_code.'/'.$regno;

    ## Live Url
    $service_url = 'http://10.10.233.76:8082/dcertapi/getDataByExamCodeAndMemNo/' . $exam_code . '/' . $regno;

    $curl = curl_init($service_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $curl_response = curl_exec($curl);
    curl_close($curl);
    $json_objekat = json_decode($curl_response);
    $file_cont = base64_decode($json_objekat->signedCertfPDF);

    $certificate_time = date('YmdHis');
    $certificate_name = 'certificate_' . $regno . '_' . $exam_code . '_' . $certificate_time . '.pdf';
    $certificate_path = 'uploads/ecertificate/' . $certificate_name;
    file_put_contents('./' . $certificate_path, $file_cont);

    /* header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename=certificate.pdf');
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('location: '.$file_cont.'?showDownload=true');
		print($file_cont);  */

    /*header('Content-Type: application/pdf');
		header('Content-Length:' . strlen($file_cont));
		header('Content-disposition: attachment; filename=certificate.pdf');
		header('Content-Transfer-Encoding: Binary');
		echo $file_cont; */

    ## Update users downlaod certificate count 
    /* $result = $this->master_model->getRecords('download_certificate',array('exam_code'=>$exam_code,'regnumber'=>$regno));
        $dcnt = $result[0]['download_cnt'];
        if(!empty($result) && $dcnt > 0)
        {
        	$update    = array('download_cnt'=> $dcnt + 1);
        	$this->master_model->updateRecord('download_certificate', $update, array('exam_code'=>$exam_code,'regnumber'=>$regno));
        }else{*/
    $insert = array('exam_code' => $exam_code, 'regnumber' => $regno, 'download_cnt' => 1);
    $this->master_model->insertRecord('download_certificate', $insert);
    //}

    #$this->session->set_flashdata('success', 'E-certificate downloaded successfully');
    //$this->session->set_flashdata('success_final', 'E-certificate downloaded successfully. If you are experiencing any issues with the download, please <a href="'.site_url('Download_ecertificate/download_file/'.base64_encode($regno).'/'.base64_encode($exam_code).'/'.base64_encode($certificate_time)).'" target="_blank"><strong>CLICK HERE</strong></a> to download it manually.###'.$certificate_name);
    $this->session->set_flashdata('success_final', 'E-certificate downloaded successfully.###' . $certificate_name);
    redirect(base_url() . 'Download_ecertificate');
  }

  //call back for check captcha server side
  public function check_captcha_userreg($code)
  {
    if (isset($_SESSION["regcaptcha"])) {
      if ($code == '' || $_SESSION["regcaptcha"] != $code) {
        $this->form_validation->set_message('check_captcha_userreg', 'Invalid %s.');
        //$this->session->set_userdata("regcaptcha", rand(1,100000));
        return false;
      }
      if ($_SESSION["regcaptcha"] == $code) {
        return true;
      }
    } else {
      return false;
    }
  }

  function delete_old_files_from_server()
  {
    //START : DELETE ALL PREVIOUS FILE BEFORE 2 HOURS
    $this->load->helper('directory');
    $map = directory_map('./uploads/ecertificate/');
    if (count($map) > 0) {
      foreach ($map as $res) {
        if ($res != 'index.php') {
          if (strpos($res, date('YmdH')) !== false) {
          } else {
            if (strpos($res, date('YmdH', strtotime('-1 hour'))) !== false) {
            } else {
              @unlink('./uploads/ecertificate/' . $res);
            }
          }
        }
      }
    }
    //END : DELETE ALL PREVIOUS FILE BEFORE 2 HOURS
  }

  function download_file($enc_regno = '', $enc_exam_code = '', $enc_certificate_time = '')
  {
    $this->delete_old_files_from_server();

    if ($enc_exam_code == '' || $enc_regno == '' || $enc_certificate_time == '') {
      $this->session->set_flashdata('error', 'Invalid request');
      redirect(base_url() . 'Download_ecertificate');
    } else {
      $exam_code = base64_decode($enc_exam_code);
      $regno = base64_decode($enc_regno);
      $certificate_time = base64_decode($enc_certificate_time);

      $file_name = 'certificate_' . $regno . '_' . $exam_code . '_' . $certificate_time . '.pdf';
      $file_path = 'uploads/ecertificate/' . $file_name;
      if (file_exists($file_path)) {
        redirect(base_url($file_path));
      } else {
        $this->session->set_flashdata('error', 'Invalid download request');
        redirect(base_url() . 'Download_ecertificate');
      }
    }
  }

  //START : RELOAD CAPTCHA IMAGE FUNCTIONALITY
  ///ADDED BY SM ON 11-07-2023
  public function generatecaptchaajax()
  {
    $this->load->model('Captcha_model');
    echo $captcha_img = $this->Captcha_model->generate_captcha_img('DOWNLOAD_E_CERTIFICATE');
  } //END : RELOAD CAPTCHA IMAGE FUNCTIONALITY

  //START : SEND OTP FUNCTIONALITY USING AJAX
  ///ADDED BY SM ON 12-07-2023
  public function send_otp_ajax()
  {
    $result['flag'] = 'error';
    $result['response'] = 'Error occurred. Please try after sometime';
    $result['response_member_no'] = $result['response_captcha_code'] = '';

    $member_no = $captcha_code = $resend_flag = $exam_code = $otp_firtsname = $otp_email = $otp_mobile = '';
    $validate_flag = 1;
    $result_data = array();

    if (isset($_POST)) {
      if ($this->security->xss_clean(trim($this->input->post('member_no'))) != "") {
        $member_no = $this->security->xss_clean(trim($this->input->post('member_no')));
      }

      if ($this->security->xss_clean(trim($this->input->post('captcha_code'))) != "") {
        $captcha_code = $this->security->xss_clean(trim($this->input->post('captcha_code')));
      }

      if ($this->security->xss_clean(trim($this->input->post('resend_flag'))) != "") {
        $resend_flag = $this->security->xss_clean(trim($this->input->post('resend_flag')));
      }

      if ($this->security->xss_clean(trim($this->input->post('sel_exam'))) != "") {
        $sel_exam = $this->security->xss_clean(trim($this->input->post('sel_exam')));

        $explode_arr = explode("##", $sel_exam);
        $exam_code = $explode_arr[1];

        if ($exam_code == '210') {
          $exam_code = '21';
        } elseif ($exam_code == '600') {
          $exam_code = '60';
        }
      }

      //START : VALIDATE MEMBER NUMBER
      if ($member_no == "") {
        $result['response_member_no'] = 'Please enter Membership/Registration no';
        $validate_flag = 0;
      } else {
        $chk_res_data = $result_data = $this->master_model->getRecords('member_registration', array('regnumber' => $member_no, 'isactive' => '1'), true);

        if (!empty($result_data[0]['regnumber'])) {
          $result_data[0]['is_dra_mem'] = '1';
          $otp_firtsname = $result_data[0]['firstname'];
          $otp_email = $result_data[0]['email'];
          $otp_mobile = $result_data[0]['mobile'];
        } else {
          $chk_res_data = $result_data = $this->master_model->getRecords('dra_members', array('regnumber' => $member_no), true);
          $result_data[0]['is_dra_mem'] = '2';
          $otp_firtsname = $result_data[0]['firstname'];
          $otp_email = $result_data[0]['email_id'];
          $otp_mobile = $result_data[0]['mobile_no'];
        }

        if (!empty($chk_res_data)) {
          $exams = '';
          $exams = $this->getExams($member_no);
          if (empty($exams)) {
            $result['response_member_no'] = 'No exam data found for registration number: ' . $member_no;
            $validate_flag = 0;
          }
        } else {
          $result['response_member_no'] = 'Please enter valid Membership/Registration no';
          $validate_flag = 0;
        }
      }
      //END : VALIDATE MEMBER NUMBER

      //START : VALIDATE CAPTCHA
      if ($resend_flag == '') {
        if ($captcha_code == "") {
          $result['response_captcha_code'] = 'Please enter Security Code';
          $validate_flag = 0;
        } else {
          $session_name = 'DOWNLOAD_E_CERTIFICATE';
          $session_captcha = '';

          if (isset($_SESSION[$session_name])) {
            $session_captcha = $_SESSION[$session_name];
          }

          if ($captcha_code != $session_captcha) {
            $validate_flag = 0;
            $result['response_captcha_code'] = 'Please enter valid Security Code';
          }
        }
      }
      //END : VALIDATE CAPTCHA

      if ($validate_flag == 1) {
        $download_cnt = $this->master_model->getRecordCount('download_certificate', array('regnumber' => $member_no, 'exam_code' => $exam_code));
        //echo $this->db->last_query();

        if ($download_cnt == 0  || $download_cnt < 3) {
          if (!empty($result_data)) {
            //check if email id or mobile number is blank. If found blank, show the msg
            if ($otp_mobile == "" || $otp_email == "") {
              $error_msg = '';
              if ($otp_mobile == '') {
                $error_msg .= 'mobile number';
              }
              if ($otp_email == '') {
                if ($error_msg != '') {
                  $error_msg .= ' and ';
                }
                $error_msg .= 'email-id';
              }

              $result['response'] = 'Your ' . $error_msg . ' is not updated in our records. You are requested to send an email to iibfwzmem@iibf.org.in/je.mss1@iibf.org.in for updating the details.';
            } else {
              $otp_sent_on = date('Y-m-d H:i:s');
              $otp_expired_on = date('Y-m-d H:i:s', strtotime('+30 minutes', strtotime($otp_sent_on)));
              $otp = $this->generate_otp();

              $otp_arr = array();
              $otp_arr['regnumber'] = $member_no;
              $otp_arr['firstname'] = $otp_firtsname;
              $otp_arr['email'] = $otp_email;
              $otp_arr['mobile'] = $otp_mobile;

              // with this function send otp to user via sms
              $is_otp_send = $this->send_otp_via_api($otp, $otp_arr);

              if ($is_otp_send == true) {
                $this->db->delete('verify_otp_ecertificate', array('regnumber' => $member_no));
                $data['regnumber'] = $member_no;
                $data['email'] = $otp_email;
                $data['mobile'] = $otp_mobile;
                $data['user_otp'] = $otp;
                $data['user_otp_send_on'] = $otp_sent_on;
                $data['user_otp_expired_on'] = $otp_expired_on;
                $data['is_otp_verified'] = 'n';
                $this->db->insert('verify_otp_ecertificate', $data);
                $inserted_id = $this->db->insert_id();

                $result['flag'] = 'success';
                $result['response'] = 'OTP has been successfully sent on registered email and mobile no';
                $result['sec'] = $this->check_time($otp_sent_on);
                $result['inserted_id'] = $inserted_id;
                $result['verify_otp_block'] = '
									<div class="form-group m_t_15">
											<label for="roleid" class="col-sm-4 control-label">Enter OTP<span style="color:#F00">*</span></label>

											<div class="col-sm-8">
												<input type="text" class="form-control" placeholder="Enter OTP Here" id="verify_otp" name="verify_otp" value="" required autocomplete="off">
												<small style="color: #444444; line-height: 18px; display: block; margin: 5px 0 0 0;">The OTP will expire after 30 minutes. Please do not share the OTP with anyone.</small>
												<label class="error" id="verify_otp_error"></label>												
											</div>
										</div>
									</div><div class="clearfix"></div>
									<div class="box-footer">
										<div class="form-group">
											<div class="col-sm-8 col-sm-offset-4">
												<a href="' . base_url() . 'Download_ecertificate/reset" class="btn btn-default">Reset</a>&nbsp;
												<button type="button" class="btn btn-info verify_otp_btn" onclick="validate_otp_certificate_ajax()">Submit OTP</button> 
												<span id="mobile_timer"></span> 
												<button type="button" class="btn btn-info resend_otp resend_hide" style="display:none;" onclick="send_otp_download_certificate(1)"> Click here to resend OTP</button>
											</div>
										</div>
									</div>';
              } else {
                $result['response'] = 'Error occured while sending OTP. Please try after sometime.';
              }
            }
          } else {
            $result['response'] = 'You are not eligible to Download E-certificate.';
          }
        } else {
          $result['response'] = 'You are not eligible to Download E-certificate more than 3 times.';
        }
      }
    } else {
      $result['response'] = 'Invalid request';
    }

    echo json_encode($result);
  } //END : SEND OTP FUNCTIONALITY USING AJAX

  //START : VALIDATE OTP FUNCTIONALITY USING AJAX
  ///ADDED BY SM ON 17-07-2023
  public function validate_otp_certificate_ajax()
  {
    $result['flag'] = 'error';
    $result['response'] = 'Please enter OTP received on your registered Email & Mobile Number';

    $member_no = $otp_code = '';

    if (isset($_POST)) {
      if ($this->security->xss_clean(trim($this->input->post('member_no'))) != "") {
        $member_no = $this->security->xss_clean(trim($this->input->post('member_no')));
      }

      if ($this->security->xss_clean(trim($this->input->post('otp_code'))) != "") {
        $otp_code = $this->security->xss_clean(trim($this->input->post('otp_code')));
      }

      if ($member_no != "" && $otp_code != "") {
        $result_data = $this->master_model->getRecords('verify_otp_ecertificate', array('regnumber' => $member_no, 'user_otp' => $otp_code, 'is_otp_verified' => 'n'), true);

        if (!empty($result_data)) {
          if ($result_data[0]['user_otp_expired_on'] < date("Y-m-d H:i:s")) {
            $result['response'] = 'Your OTP has expired. Please click the Resend OTP button to receive a new one.';
          } else {
            $update_arr = array('is_otp_verified' => 'y', 'otp_verified_on' => date("Y-m-d H:i:s"));
            $this->master_model->updateRecord('verify_otp_ecertificate ', $update_arr, array('regnumber' => $member_no, 'user_otp' => $otp_code));

            $result['flag'] = 'success';
            $result['response'] = '';
          }
        } else {
          $result['response'] = 'Please enter valid OTP received on your registered Email & Mobile Number';
        }
      }
    } else {
      $result['response'] = 'Invalid request';
    }

    echo json_encode($result);
  } //END : VALIDATE OTP FUNCTIONALITY USING AJAX

  //START : GENERATE OTP FUNCTIONALITY
  ///ADDED BY SM ON 12-07-2023
  public function generate_otp()
  {
    return rand(100000, 999999);
    // return '111111';
  } //END : GENERATE OTP FUNCTIONALITY

  //START : SEND OTP ON MOBILE NUMBER AND EMAIL FUNCTIONALITY
  ///ADDED BY SM ON 12-07-2023
  public function send_otp_via_api($otp, $otp_arr)
  {
    $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'DOWNLOAD_ECERTIFICATE_OTP'));

    if (count($emailerstr) > 0) {
      // SEND OTP ON MOBILE (SMS) : START
      /*$mobile = $otp_arr['mobile']; 
     $message = 'Your OTP for downloading Exam Certificate is '.$otp.'. This password is valid for one transaction or 30 mins whichever is earlier. Do not share it with anyone. IIBF Team';
      $res = $this->master_model->send_sms_trustsignal(intval($mobile), $message, 'AKDgA8rVR');
		  // $res = $this->master_model->send_sms_trustsignal(intval($mobile), $message, '3M1ie3r7g', '997', 'otp', 'IIBFSM'); */

      $mobile = $otp_arr['mobile'];
      $message = str_replace('#OTP#', $otp, $emailerstr[0]['sms_text']);
      $res = $this->master_model->send_sms_common_all($mobile, $message, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']);
      // SEND OTP ON MOBILE (SMS) : END

      // SEND OTP ON EMAIL : START
      /*$otp_mail_arr['to'] = $otp_arr['email'];
      $otp_mail_arr['subject'] = 'IIBF : OTP for downloading the E-Certificate';
      $otp_mail_arr['message'] = '
            Dear ' . $otp_arr['firstname'] . ' (Employee ID: ' . $otp_arr['regnumber'] . '), <br/><br/>
            Your OTP for downloading the E-Certificate is <strong>' . $otp . '</strong> (Valid for 30 minutes). <br/>
            <div style="margin-top:10px;">Note : Do not share it with anyone.</div><br>
            Thanks & Regards,<br>IIBF TEAM ';
      $email_response = $this->Emailsending->mailsend($otp_mail_arr);*/

      $email_text = $emailerstr[0]['emailer_text'];
      $email_text = str_replace('#FIRSTNAME#', $otp_arr['firstname'], $email_text);
      $email_text = str_replace('#REGNUMBER#', $otp_arr['regnumber'], $email_text);
      $email_text = str_replace('#OTP#', $otp, $email_text);

      $otp_mail_arr['to'] = $otp_arr['email'];
      $otp_mail_arr['subject'] = $emailerstr[0]['subject'];
      $otp_mail_arr['message'] = $email_text;
      $email_response = $this->Emailsending->mailsend($otp_mail_arr);
      return true;
      // SEND OTP ON EMAIL : END
    } else {
      return false;
    }
  } //END : SEND OTP ON MOBILE NUMBER AND EMAIL FUNCTIONALITY

  //START : TO DISPLAY THE REMAINING TIME FOR RESEND OTP
  ///ADDED BY SM ON 12-07-2023
  function check_time($time)
  {
    $timeMobileFirst  = strtotime($time);
    $currentTimeInSec = strtotime(date('Y-m-d H:i:s'));

    $remainingTimeMobile = 0;
    if ($timeMobileFirst <= $currentTimeInSec) {
      $diffTimeMobile =  $currentTimeInSec - $timeMobileFirst;

      if ($diffTimeMobile < $this->otptime) {
        $remainingTimeMobile = $this->otptime - $diffTimeMobile;
      }
    }
    return $remainingTimeMobile;
  } //END : TO DISPLAY THE REMAINING TIME FOR RESEND OTP
}
