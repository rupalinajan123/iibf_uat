<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Speciallogin extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->library('upload');
    $this->load->helper('upload_helper');
    $this->load->helper('master_helper');
    $this->load->model('master_model');
    $this->load->library('email');
    $this->load->model('Emailsending');
    $this->load->model('chk_session');
    //$this->load->library('OS_BR');
    if ($this->session->userdata('memberdata'))
    {
      $this->session->unset_userdata('memberdata');
    }

    $this->otptime = 60; //in sec
  }

  ##---------default userlogin (prafull)-----------##
  public function index()
  {
    $_SESSION['LOGIN_OTP_MEMBERSHIP_NO'] = '';
    $_SESSION['FORGOT_OTP_MEMBERSHIP_NO'] = '';

    if (!isset($_COOKIE["instruction"]) || $_COOKIE["instruction"] == 0)
    {
      //redirect('https://iibf.esdsconnect.com/instructoinLogin.php');
      //redirect('https://iibf.esdsconnect.com/validate_user_captcha'); 
    }
    //$this->chk_session->checklogin();

    if ($this->session->userdata('regid'))
    {
      redirect(base_url() . 'home/dashboard/');
    }

    $data = array();
    $data['error'] = '';
    if (isset($_POST) && count($_POST) > 0 && isset($_POST['login']))
    {
      $this->form_validation->set_rules('username', 'Membership No', 'trim|required|xss_clean', array('required' => 'Please enter the %s'));
      $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean', array('required' => 'Please enter the %s'));
      $this->form_validation->set_rules('code', 'characters you see in the picture', 'trim|required|callback_validation_check_captcha|xss_clean', array('required' => 'Please enter the %s'));

      /* $config = array(
                
                array(
                'field' => 'Username',
                'label' => 'Username',
                'rules' => 'trim|required'
                ),
                array(
                'field' => 'Password',
                'label' => 'Password',
                'rules' => 'trim|required',
                ),
                array(
                'field' => 'val3',
                'label' => 'Answer',
                'rules' => 'trim|required',
                )  
                );
                
                $this->form_validation->set_rules($config);*/

      include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
      $key = $this->config->item('pass_key');
      $aes = new CryptAES();
      $aes->set_key(base64_decode($key));
      $aes->require_pkcs5();
      $encpass = $aes->encrypt($this->input->post('password'));
      //$encpass = $aes->encrypt($_POST['Password']);
      $dataarr['regnumber'] = $this->input->post('username');
      $dataarr['usrpassword'] = $encpass;
      $exam_name = '';

      if ($this->form_validation->run() == TRUE)
      {
        //echo 'in'; exit;
        /* $val1=$_POST['val1'];          
                    $val2=$_POST['val2'];         
                    $val3=$_POST['val3'];
                    $add_val= ($val1+$val2); */

        //if($add_val==$val3val3)
        // {
        $this->db->select('registrationtype,regid,regnumber,firstname,middlename,lastname,createdon,registrationtype,isactive,usrpassword');
        $where = "(registrationtype='O' OR registrationtype='A' OR registrationtype='F')";
        $this->db->where($where);
        $this->db->where('isactive', '1');

        $user_info = $this->master_model->getRecords('member_registration', $dataarr);
        //$data['chk_qry'] = $this->db->last_query();
        //$data['posted_arr'] = $_POST;

        /* // Pratibha borse: if password encrypt format is old then getting issue while login, is case of this update existing password for given user
                        if($this->input->post('Username') == '510029214'){
                            echo "<pre> Doing test here with submit user infor-->"; 
                            print_r( $this->db->last_query());
                            print_r($user_info);
                            exit;
                        } */
        if (count($user_info) > 0)
        {
          if ($user_info[0]['isactive'] == 1)
          {
            $showlink = "no";
            $mysqltime = date("H:i:s");
            $user_data = array(
              'regid' => $user_info[0]['regid'],
              'regnumber' => $user_info[0]['regnumber'],
              'firstname' => $user_info[0]['firstname'],
              'middlename' => $user_info[0]['middlename'],
              'lastname' => $user_info[0]['lastname'],
              'memtype' => $user_info[0]['registrationtype'],
              'timer' => base64_encode($mysqltime),
              'showlink' => $showlink,
              'exam_name' => $exam_name,
              'special_login' => 'Yes', // Add this value in session for check special login member when applying the exam
              'password' => base64_encode($this->input->post('password'))
            );
            $this->session->set_userdata($user_data);

            //redirect(base_url().'home/dashboard/');   
            if (isset($_SESSION['GARP_REDIRECT_EDIT_PROFILE_FLAG']) && $_SESSION['GARP_REDIRECT_EDIT_PROFILE_FLAG'] == 1 && isset($_SESSION['GARP_REDIRECT_EDIT_PROFILE_VALIDITY']) && $_SESSION['GARP_REDIRECT_EDIT_PROFILE_VALIDITY'] >= date("Y-m-d H:i"))
            {
              redirect(site_url('home/profile'));
            }
            else
            {
              $_SESSION['GARP_REDIRECT_EDIT_PROFILE_FLAG'] = 0;
              $_SESSION['GARP_REDIRECT_EDIT_PROFILE_VALIDITY'] = '';
              redirect(site_url('home/dashboard'));
            }
          }
          else if ($user_info[0]['isactive'] == 0)
          {
            $data['error'] = '<span style="">Invalid Credentials</span>';
          }
          else
          {
            $data['error'] = '<span style="">This account is suspended</span>';
          }
        }
        else
        {
          $data['error'] = '<span style="">Invalid Credentials..</span>';
        }
        // }
        /* else 
                    {
                        $data['error']='Invalid Credentials';
                        //$this->session->set_flashdata('error_message','Invalid Credentials');
                        //$this->load->view('admin/login/login',$dataarr);
                    } */
      }
    }
    /*$this->load->helper('captcha');
                $vals = array(
                'img_path' => './uploads/applications/',
                'img_url' => base_url().'uploads/applications/',
                );
                $cap = create_captcha($vals);
                $data['image'] = $cap['image'];
            $data['code']=$cap['word'];
            $data['image'] ='' ;
            $data['code']='';
            //$this->session->set_userdata('userlogincaptcha', $cap['word']);*/

    $this->load->model('Captcha_model');
    $data['image'] = $this->Captcha_model->generate_captcha_img('USERLOGINCAPTCHA');
    $this->load->view('login', $data);
  }


  //##---- reload captcha functionality
  public function generatecaptchaajax()
  {
    $this->load->model('Captcha_model');
    echo $captcha_img = $this->Captcha_model->generate_captcha_img('USERLOGINCAPTCHA');
  }

  public function validation_check_captcha($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
  {
    $return_val_ajax = 'false';
    if (isset($_POST) && $_POST['code'] != "")
    {
      if ($type == '1')
      {
        $captcha = $this->input->post('code');
      }
      else if ($type == '0')
      {
        $captcha = $str;
      }

      $session_captcha = $this->session->userdata('USERLOGINCAPTCHA');
      if ($captcha == $session_captcha)
      {
        $return_val_ajax = 'true';
      }
    }

    if ($type == '1')
    {
      echo $return_val_ajax;
    }
    else if ($type == '0')
    {
      if ($return_val_ajax == 'true')
      {
        return TRUE;
      }
      else
      {
        $this->form_validation->set_message('validation_check_captcha', 'Please enter the exact characters you see in the picture');
        return false;
      }
    }
  }

  public function instruction()
  {
    $data = array();
    $data['error'] = '';
    if (isset($_POST['submit']))
    {
      $config = array(
        array(
          'field' => 'code',
          'label' => 'Code',
          'rules' => 'trim|required|callback_check_captcha_userlogin',
        ),
      );
      if ($this->form_validation->run() == TRUE)
      {
        redirect(base_url());
      }
      else
      {
        $data['validation_errors'] = validation_errors();
      }
    }
    $this->load->helper('captcha');
    $vals = array(
      'img_path' => './uploads/applications/',
      'img_url' => base_url() . 'uploads/applications/',
    );
    $cap = create_captcha($vals);
    $data['image'] = $cap['image'];
    $data['code'] = $cap['word'];
    $this->session->set_userdata('userlogincaptcha', $cap['word']);
    $this->load->view('instructoinLogin', $data);
  }

  ##---------check captcha userlogin (prafull)-----------##
  public function check_captcha_userlogin($code)
  {
    /*if(!isset($this->session->userlogincaptcha) && empty($this->session->userlogincaptcha))
            {
                redirect(base_url().'login/');
            }
            
            if($code == '' || $this->session->userlogincaptcha != $code )
            {
                $this->form_validation->set_message('check_captcha_userlogin', 'Invalid %s.'); 
                $this->session->set_userdata("userlogincaptcha", rand(1,100000));
                return false;
            }
            if($this->session->userlogincaptcha == $code)
            {
                $this->session->set_userdata('userlogincaptcha','');
                $this->session->unset_userdata("userlogincaptcha");
                return true;
            }*/
    $session_name = 'userlogincaptcha';
    $session_captcha = '';
    if (isset($_SESSION[$session_name]))
    {
      $session_captcha = $_SESSION[$session_name];
    }

    $captcha_code = $this->security->xss_clean(trim($this->input->post('code')));

    if ($captcha_code == $session_captcha)
    {
      return TRUE;
    }
    else
    {
      $this->form_validation->set_message('check_captcha_userlogin', 'Please enter correct code');
      return FALSE;
    }
  }

  ##---------forget password (prafull)-----------##
  public function forgotpassword()
  {
    if ($this->session->userdata('regid'))
    {
      redirect(base_url() . 'home/dashboard/');
    }

    $data['page_title'] = 'Forget Password';
    $data['pass_error'] = $data['error'] = '';

    if (isset($_POST) && count($_POST) > 0 && isset($_POST['forgot_pass']))
    {
      $this->form_validation->set_rules('memno', 'Membership No.', 'trim|required|callback_validation_check_member_no_forgot_password|xss_clean', array('required' => 'Please enter the %s'));
      $this->form_validation->set_rules('code', 'characters you see in the picture', 'trim|required|callback_validation_check_captcha_forgot_password|xss_clean', array('required' => 'Please enter the %s'));

      if ($this->form_validation->run() == TRUE)
      {
        $memno = $this->input->post('memno');
        $result = $this->member_data_forgot_password($memno);
        //echo $this->db->last_query(); exit;

        if (count($result) > 0)
        {
          //generate random password
          //$password=$this->generate_random_password();
          include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
          $key = $this->config->item('pass_key');
          $aes = new CryptAES();
          $aes->set_key(base64_decode($key));
          $aes->require_pkcs5();
          //$encPass = $aes->encrypt($password);
          $password = $aes->decrypt($result[0]['usrpassword']);
          //$query=$this->master_model->updateRecord('member_registration',array('usrpassword'=>$encPass),array('regid'=>$result[0]['regid']));
          //$log_arr=array('regnumber'=>$memno,'usrpassword'=>$encPass,'editedon'=>date('Y-m-d H:i:s'),'editedby'=>'Candidate');
          //logactivity($log_title ="Forget pass Member ", $log_message = serialize($log_arr));
          //if($query)
          {
            $username = $result[0]['firstname'] . ' ' . $result[0]['middlename'] . ' ' . $result[0]['lastname'];
            $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
            $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'member_forgetpass'));
            $newstring1 = str_replace("#application_num#", "" . $result[0]['regnumber'] . "",  $emailerstr[0]['emailer_text']);
            $newstring2 = str_replace("#password#", "" . $password . "",  $newstring1);
            $newstring3 = str_replace("#username#", "" . $userfinalstrname . "",  $newstring2);
            $final_str = str_replace("#url#", "" . base_url() . "",  $newstring3);

            /******** START : SEND SMS CODE ***********/
            if ($result[0]['mobile'] != "")
            {
              $disp_email = $this->obfuscate_email($result[0]['email']);
              $sms_string = str_replace("#EMAIL_ID#", $disp_email, $emailerstr[0]['sms_text']);
              $sms_string = str_replace("#APPLICATION_NUM#", $result[0]['regnumber'], $sms_string);
              $sms_string = str_replace("#PASSWORD#", $password,  $sms_string);

              /* echo $sms_string;  */
              //$this->master_model->send_sms($result[0]['mobile'], $sms_string); 

              $sms_response = $this->master_model->send_sms_common_all($result[0]['mobile'], $sms_string, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']);
              
              // $this->master_model->send_sms_trustsignal(intval($result[0]['mobile']), $sms_string, 'jYZ1dSwGR');
            }

            /******** END : SEND SMS CODE ***********/
            $info_arr = array(
              'to' => $result[0]['email'],
              'from' => $emailerstr[0]['from'],
              'subject' => $emailerstr[0]['subject'] . ' ' . $result[0]['regnumber'],
              'message' => $final_str
            );
            //echo '<pre>'; print_r($info_arr); exit;

            if ($this->Emailsending->mailsend($info_arr))
            {
              $this->session->set_flashdata('success', 'We have received your request for the entered member id. Kindly check your email for new password');
              redirect(base_url() . 'login/forgotpassword');
              //redirect(base_url().'login/forgetack');
            }
            else
            {
              $this->session->set_flashdata('error', 'Error while sending email !!');
              redirect(base_url() . 'login');
            }
          }
        }
        else
        {
          $this->session->set_flashdata('error_message', 'Please enter valid membership number');
          redirect(base_url() . 'login/forgotpassword');
        }
      }
    }

    $this->load->model('Captcha_model');
    $captcha_img = $this->Captcha_model->generate_captcha_img('FORGOT_PASSWORD_CAPTCHA');
    $data['image'] = $captcha_img;
    $this->load->view('forgetpass', $data);
  }

  function member_data_forgot_password($regnumber = '')
  {
    if ($regnumber != '')
    {
      return $this->master_model->getRecords('member_registration', array("isactive" => '1', "regnumber" => $regnumber), 'regid, reg_no, regnumber, namesub, firstname, middlename, lastname, dateofbirth, gender, email, registrationtype, mobile, usrpassword, registration_status, createdon, kyc_status', array('regid' => 'DESC'));
      //echo $this->db->last_query();
    }
    else
    {
      return 0;
    }
  }

  public function generate_captcha_ajax_forgotpassword()
  {
    $this->load->model('Captcha_model');
    echo $captcha_img = $this->Captcha_model->generate_captcha_img('FORGOT_PASSWORD_CAPTCHA');
  }

  public function validation_check_member_no_forgot_password($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
  {
    $error_msg = 'Membership details not found for the provided Membership Number';
    $return_val_ajax = 'false';
    if (isset($_POST) && $_POST['memno'] != "")
    {
      if ($type == '1')
      {
        $memno = $this->input->post('memno');
      }
      else if ($type == '0')
      {
        $memno = $str;
      }

      $user_data = $this->member_data_forgot_password($memno);

      if (count($user_data) > 0)
      {
        if($user_data[0]['mobile'] != "") { $return_val_ajax = 'true'; }
        else { $error_msg = 'Your mobile number is not registred with us. Please contact to IIBF'; }
      }
    }

    if ($type == '1')
    {
      echo $return_val_ajax;
    }
    else if ($type == '0')
    {
      if ($return_val_ajax == 'true')
      {
        return TRUE;
      }
      else
      {
        $this->form_validation->set_message('validation_check_member_no_forgot_password', $error_msg);
        return false;
      }
    }
  }

  public function validation_check_captcha_forgot_password($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
  {
    $return_val_ajax = 'false';
    if (isset($_POST) && $_POST['code'] != "")
    {
      if ($type == '1')
      {
        $captcha = $this->input->post('code');
      }
      else if ($type == '0')
      {
        $captcha = $str;
      }

      $session_captcha = $this->session->userdata('FORGOT_PASSWORD_CAPTCHA');
      if ($captcha == $session_captcha)
      {
        $return_val_ajax = 'true';
      }
    }

    if ($type == '1')
    {
      echo $return_val_ajax;
    }
    else if ($type == '0')
    {
      if ($return_val_ajax == 'true')
      {
        return TRUE;
      }
      else
      {
        $this->form_validation->set_message('validation_check_captcha_forgot_password', 'Please enter the exact characters you see in the picture');
        return false;
      }
    }
  }

  function obfuscate_email($email)
  {
    
    $extension = explode("@", $email);
    $name = implode('@', array_slice($extension, 0, count($extension) - 1));
    $len = strlen($name);
    $start = $len - 2;
    if($start < 0){
      $start = 0;
    }
    return str_repeat('*', $start) . substr($name, $start, $len) . "@" . end($extension);
  }

  ##---------Genereate random password function (prafull)-----------##
  function generate_random_password($length = 8, $level = 2) // function to generate new password
  {
    list($usec, $sec) = explode(' ', microtime());
    srand((float) $sec + ((float) $usec * 100000));
    $validchars[1] = "0123456789abcdfghjkmnpqrstvwxyz";
    $validchars[2] = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $validchars[3] = "0123456789_!@#*()-=+abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_!@#*()-=+";
    $password = "";
    $counter = 0;
    while ($counter < $length)
    {
      $actChar = substr($validchars[$level], rand(0, strlen($validchars[$level]) - 1), 1);
      if (!strstr($password, $actChar))
      {
        $password .= $actChar;
        $counter++;
      }
    }
    return $password;
  }
  //call back for check captcha server side
  public function check_captcha_userreg($code)
  {
    if (isset($_SESSION["regcaptcha"]))
    {
      if ($code == '' || $_SESSION["regcaptcha"] != $code)
      {
        $this->form_validation->set_message('check_captcha_userreg', 'Invalid %s.');
        //$this->session->set_userdata("regcaptcha", rand(1,100000));
        return false;
      }
      if ($_SESSION["regcaptcha"] == $code)
      {
        return true;
      }
    }
    else
    {
      return false;
    }
  }
  //## forget pass acknowledgment
  public function forgetack()
  {

    $this->load->view('forgetacknowledgement');
  }

  ##---------End user logout (prafull)-----------##//
  public function Logout()
  {
    $_SESSION['GARP_REDIRECT_EDIT_PROFILE_FLAG'] = 0;
    $_SESSION['GARP_REDIRECT_EDIT_PROFILE_VALIDITY'] = '';

    $sessionData = $this->session->all_userdata();
    foreach ($sessionData as $key => $val)
    {
      $this->session->unset_userdata($key);
    }
    $cookie_name = "instruction";
    $cookie_value = "0";
    setcookie($cookie_name, $cookie_value, time() + (60 * 10), "/"); // 60 seconds ( 1 minute) * 10 = 10 minutes
    redirect('http://iibf.org.in/');
  }

  // function forgot_membership_no()
  // {            
  //   if($this->session->userdata('regid'))
  //      {
  //          redirect(base_url().'home/dashboard/');
  //      }

  //      if(isset($_POST) && count($_POST) > 0 && isset($_POST['forgot_membership_no']))
  //      {
  //     $this->form_validation->set_rules('email_mobile','Email Id / Mobile Number','trim|required|callback_validation_email_mobile_forgot_membership_no|xss_clean',array('required' => 'Please enter the %s'));
  //     $this->form_validation->set_rules('code','characters you see in the picture','trim|required|callback_validation_check_captcha_forgot_membership_no|xss_clean',array('required' => 'Please enter the %s'));

  //     if ($this->form_validation->run() == TRUE)
  //          {
  //       $email_mobile = $this->input->post('email_mobile');
  //       $user_data = $this->member_data_forgot_membership_no($email_mobile);
  //       redirect(site_url('login/forgot_membership_details/'.base64_encode($user_data[0]['regid']).'/'.base64_encode($user_data[0]['regnumber'])));
  //     }
  //   }

  //      $this->load->model('Captcha_model');
  //      $data['image'] = $this->Captcha_model->generate_captcha_img('FORGOT_MEMBERSHIP_NO_CAPTCHA');
  //      $this->load->view('forgot_membership_no',$data);            
  //  }

  function forgot_membership_no()
  {
    // echo"hi";exit;
    if($this->session->userdata('regid'))
    {
      redirect(base_url() . 'home/dashboard/');
    }

    if (count($_POST) && count($_POST) > 0)
    {
      $action_flag = '';
      if (isset($_POST['send_otp']) && $_POST['send_otp'] == 'Send OTP')
      {
        $action_flag = 'send_otp';
      }
      else if (isset($_POST['verify_otp']) && $_POST['verify_otp'] == 'Verify OTP')
      {
        $action_flag = 'verify_otp';
      }

      $this->form_validation->set_rules('email_mobile', 'Email Id / Mobile Number', 'trim|required|callback_validation_email_mobile_forgot_membership_no|xss_clean', array('required' => 'Please enter the %s'));
      if ($action_flag == 'send_otp')
      {
        $this->form_validation->set_rules('code', 'characters you see in the picture', 'trim|required|callback_validation_check_captcha_forgot_membership_no|xss_clean', array('required' => 'Please enter the %s'));
      }

      if ($action_flag == 'verify_otp')
      {
        $this->form_validation->set_rules('input_otp', 'OTP', 'trim|required|callback_validation_validate_otp_forgot[0]|xss_clean', array('required' => 'Please enter the %s'));
      }

      if ($this->form_validation->run() == TRUE)
      {
        //echo $action_flag;
        $email_mobile = $this->input->post('email_mobile');
        $member_data = $this->member_data_forgot_membership_no($email_mobile);
        // $member_data = $this->get_forgot_member_data_with_otp($email_mobile);
        //print_r($member_data);exit;

        if (count($member_data) > 0)
        {
          if ($action_flag == 'send_otp')
          {
            $_SESSION['FORGOT_OTP_MEMBERSHIP_NO'] = $email_mobile;
            $send_otp = $this->fun_send_otp_sms_forgot($member_data);
            // print_r($send_otp);exit;
            if ($send_otp)
            {
              // echo "test";
              $this->session->set_flashdata('success', 'OTP successfully sent on ' . $this->mask_email_mobile('mobile', $member_data[0]['mobile']) . ' & ' . $this->mask_email_mobile('email', $member_data[0]['email']) . '. OTP is valid for 10 minutes.');
            }
            else
            {
              $this->session->set_flashdata('error', 'Error occurred. Please try again.');
            }
            redirect(site_url('login/forgot_membership_no'));
          }
          else if ($action_flag == 'verify_otp')
          {
            $otp_data = $this->master_model->getRecords('member_login_otp', array('email' => $member_data[0]['email'], 'mobile' => $member_data[0]['mobile'], 'otp_type' => '2'), 'otp_id, otp, is_validate, otp_expired_on', array('otp_id' => 'DESC'), '', 1);
            // print_r($otp_data);exit;
            $input_otp = $this->input->post('input_otp');

            if (count($otp_data) > 0)
            {
              if ($otp_data[0]['otp'] != $input_otp)
              {
                $this->session->set_flashdata('error', 'Please enter the correct OTP.');
                redirect(site_url('login/forgot_membership_no'));
              }
              else if ($otp_data[0]['otp_expired_on'] < date("Y-m-d H:i:s"))
              {
                $this->session->set_flashdata('error', 'The OTP has already expired.');
                redirect(site_url('login/forgot_membership_no'));
              }
              else
              {
                $up_data['is_validate'] = 1;
                $up_data['updated_on'] = date("Y-m-d H:i:s");
                $this->master_model->updateRecord('member_login_otp', $up_data, array('otp_id' => $otp_data[0]['otp_id']));

                $_SESSION['FORGOT_OTP_MEMBERSHIP_NO'] = '';

               
                //send email to user for its details
                 $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'forgot_membership_no_details'));

                  $email_text = $emailerstr[0]['emailer_text'];
                  $email_text = str_replace('#CANDIDATENAME#', $member_data[0]['firstname'] . " " . $member_data[0]['lastname'], $email_text);
                  $email_text = str_replace('#MEMBERSHIPNO#', $member_data[0]['regnumber'], $email_text);
                  $email_text = str_replace('#EMAILID#', $member_data[0]['email'], $email_text);
                  $email_text = str_replace('#MOBILENO#', $member_data[0]['mobile'], $email_text);
                  $email_text = str_replace('#BIRTHDATE#', $member_data[0]['dateofbirth'], $email_text);
                  $email_text = str_replace('#GENDER#', $member_data[0]['gender'], $email_text);
                    
                  $sms_text = $emailerstr[0]['sms_text'];
                  $sms_text = str_replace('#CANDIDATENAME#', $member_data[0]['firstname'] . " " . $member_data[0]['lastname'], $sms_text);
                  $sms_text = str_replace('#MEMBERSHIPNO#', $member_data[0]['regnumber'], $sms_text);
                  $sms_text = str_replace('#BIRTHDATE#', $member_data[0]['dateofbirth'], $sms_text);

                  $otp_mail_arr['to'] = $member_data[0]['email']; //$email_id;
                  $otp_mail_arr['subject'] = $emailerstr[0]['subject'];
                  $otp_mail_arr['message'] = $email_text;
                  $email_response = $this->Emailsending->mailsend($otp_mail_arr);
                //redirect to thank you page.
                  
                  $sms_response = $this->master_model->send_sms_common_all($member_data[0]['mobile'], $sms_text, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']);
                  
                 redirect(site_url('login/membership_details_success'));exit;
                 
                 //end of send email to user for its details
              }
            }
            else
            {
              $this->session->set_flashdata('error', 'Please enter the correct OTP.');
              redirect(site_url('login/forgot_membership_no'));
            }
          }
        }
        else
        {
          $this->session->set_flashdata('error', 'Error occurred. Please try again.');
          redirect(site_url('login/forgot_membership_no'));
        }
      }
    }

    $session_email_mobile = $_SESSION['FORGOT_OTP_MEMBERSHIP_NO'];
    $member_data = $this->member_data_forgot_membership_no($session_email_mobile);   
    $show_otp_input_flag = 0;
    if (count($member_data) > 0)
    {
      $regid = $member_data[0]['regid'];
      $session_email_mobile = $_SESSION['FORGOT_OTP_MEMBERSHIP_NO'];// = $email_mobile = $member_data[0]['email_mobile'];
      //print_r($session_email_mobile);exit;
      $email_id = $member_data[0]['email'];
      $mobile_no = $member_data[0]['mobile'];

      $show_otp_input_flag = 1;
      $data['mask_email'] = $this->mask_email_mobile('email', $email_id);
      $data['mask_mobile'] = $this->mask_email_mobile('mobile', $mobile_no);

      $get_otp_record = $this->master_model->getRecords('member_login_otp', array('email' => $email_id, 'mobile' => $mobile_no, 'otp_type'=>'2'), 'otp_id, otp_expired_on, created_on', array('otp_id' => 'DESC'), '', 1);
      //print_r($get_otp_record);exit;
      if (count($get_otp_record) > 0)
      {
        $get_otp_record[0]['created_on'];
        $data['resend_time_sec'] = $resend_time_sec = $this->check_time($get_otp_record[0]['created_on']);
      }
    }

    $data['session_email_mobile'] = $session_email_mobile;
    $data['member_data'] = $member_data;
    $data['show_otp_input_flag'] = $show_otp_input_flag;

    $this->load->model('Captcha_model');
    $data['image'] = $this->Captcha_model->generate_captcha_img('FORGOT_MEMBERSHIP_NO_CAPTCHA');
    $this->load->view('forgot_membership_no', $data);
  }

    function membership_details_success()
    {
        //   $otp_data = $this->master_model->getRecords('member_login_otp', array('email' => $member_data[0]['email'], 'mobile' => $member_data[0]['mobile'], 'otp_type' => '2'), 'otp_id, otp, is_validate, otp_expired_on', array('otp_id' => 'DESC'), '', 1);
        //     print_r($otp_data);exit;
        //$this->db->where("(email='" . $email_mobile . "' OR mobile='" . $email_mobile . "')");
        // $email_id = $member_data[0]['email'];
        // $data['member_data'] = $this->master_model->getRecords('member_registration', array("isactive" => '1','email' => $email_id, 'otp_type'=>'2'), , array('regid' => 'DESC'));  
        // print_r($data);exit;
        $this->load->view('thankyou_member');
    }

 
  function forgot_membership_details($enc_regid = '', $enc_regnumber = '')
  {
    if ($this->session->userdata('regid'))
    {
      redirect(base_url() . 'home/dashboard/');
    }

    $redirect_flag = 1;
    if ($enc_regid != "" && $enc_regnumber != "")
    {
      $regid = base64_decode($enc_regid);
      $regnumber = base64_decode($enc_regnumber);
      $user_data = $this->member_data_forgot_membership_no('', $regid, $regnumber);
      if (count($user_data) > 0)
      {
        $redirect_flag = 0;
        $data['user_data'] = $user_data;
        $this->load->view('forgot_membership_no_details', $data);
      }
    }

    if ($redirect_flag == 1)
    {
      $this->session->set_flashdata('error', 'Membership details not found for the provided Email Id / Mobile Number');
      redirect(site_url('login/forgot_membership_no'));
    }
  }

  function member_data_forgot_membership_no($email_mobile = '', $reg_id = '', $regnumber = '')
  {
    if ($email_mobile != '' || ($reg_id != "" && $regnumber != ""))
    {
      if ($email_mobile != '')
      {
        $this->db->where("(email='" . $email_mobile . "' OR mobile='" . $email_mobile . "')");
      }
      else if ($reg_id != '' && $regnumber != '')
      {
        $this->db->where("regid='" . $reg_id . "' AND regnumber='" . $regnumber . "'");
      }

      return $this->master_model->getRecords('member_registration', array("isactive" => '1'), 'regid, reg_no, regnumber, namesub, firstname, middlename, lastname, dateofbirth, gender, email, registrationtype, mobile, registration_status, createdon, kyc_status, usrpassword', array('regid' => 'DESC'));
      //echo $this->db->last_query();
    }
    else
    {
      return array();
    }
  }

  public function generate_captcha_ajax_forgot_membership_no()
  {
    $this->load->model('Captcha_model');
    echo $captcha_img = $this->Captcha_model->generate_captcha_img('FORGOT_MEMBERSHIP_NO_CAPTCHA');
  }

  public function validation_email_mobile_forgot_membership_no($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
  {
    $return_val_ajax = 'false';
    if (isset($_POST) && $_POST['email_mobile'] != "")
    {
      if ($type == '1')
      {
        $email_mobile = $this->input->post('email_mobile');
      }
      else if ($type == '0')
      {
        $email_mobile = $str;
      }

      $user_data = $this->member_data_forgot_membership_no($email_mobile);

      if (count($user_data) > 0)
      {
        $return_val_ajax = 'true';
      }
    }

    if ($type == '1')
    {
      echo $return_val_ajax;
    }
    else if ($type == '0')
    {
      if ($return_val_ajax == 'true')
      {
        return TRUE;
      }
      else
      {
        $this->form_validation->set_message('validation_email_mobile_forgot_membership_no', 'Please enter the valid Email Id / Mobile Number');
        return false;
      }
    }
  }

  public function validation_check_captcha_forgot_membership_no($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
  {
    $return_val_ajax = 'false';
    if (isset($_POST) && $_POST['code'] != "")
    {
      if ($type == '1')
      {
        $captcha = $this->input->post('code');
      }
      else if ($type == '0')
      {
        $captcha = $str;
      }

      $session_captcha = $this->session->userdata('FORGOT_MEMBERSHIP_NO_CAPTCHA');
      if ($captcha == $session_captcha)
      {
        $return_val_ajax = 'true';
      }
    }

    if ($type == '1')
    {
      echo $return_val_ajax;
    }
    else if ($type == '0')
    {
      if ($return_val_ajax == 'true')
      {
        return TRUE;
      }
      else
      {
        $this->form_validation->set_message('validation_check_captcha_forgot_membership_no', 'Please enter the exact characters you see in the picture');
        return false;
      }
    }
  }
  
  public function set_session_member_with_otp_ajax()
  {
    $result['flag'] = "error";

    if (isset($_POST['email_mobile']))
    {
      $membership_no = $this->input->post('email_mobile');
      $_SESSION['FORGOT_OTP_MEMBERSHIP_NO'] = $membership_no;

      $member_data = $this->member_data_forgot_membership_no($membership_no);
      if (count($member_data) > 0)
      {
        $this->fun_send_otp_sms_forgot($member_data);
        $result['flag'] = "success";
        $result['resend_time_sec'] = $this->check_time(date('Y-m-d H:i:s'));
      }
    }

    echo json_encode($result);
  }

  public function set_session_login_with_otp_ajax()
  {
    $result['flag'] = "error";

    if (isset($_POST['membership_no']))
    {
      $membership_no = $this->input->post('membership_no');
      $_SESSION['LOGIN_OTP_MEMBERSHIP_NO'] = $membership_no;

      $member_data = $this->get_member_data_login_with_otp($membership_no);
      if (count($member_data) > 0)
      {
        $this->fun_send_otp_sms($member_data);
        $result['flag'] = "success";
        $result['resend_time_sec'] = $this->check_time(date('Y-m-d H:i:s'));
      }
    }

    echo json_encode($result);
  }

  function get_forgot_member_data_with_otp($email_mobile = '')
  {
    $this->db->where("(email='" . $email_mobile . "' OR mobile='" . $email_mobile . "')");
    return $this->master_model->getRecords('member_registration', array("isactive" => '1', 'email' => $email, 'mobile' => $mobile), 'regid, reg_no, regnumber, namesub, firstname, middlename, lastname, dateofbirth, gender, email, registrationtype, mobile, registration_status, createdon, kyc_status, usrpassword', array('regid' => 'DESC'));
  }

  function get_member_data_login_with_otp($regnumber = '')
  {
    if (trim($regnumber) != '') {
      $this->db->where("(registrationtype='O' OR registrationtype='A' OR registrationtype='F')");
      return $this->master_model->getRecords('member_registration', array('isactive' => '1', 'regnumber' => $regnumber), 'regid, reg_no, regnumber, namesub, firstname, middlename, lastname, dateofbirth, gender, email, registrationtype, mobile, registration_status, createdon, kyc_status', array('regid' => 'DESC'));  
    } else {
      return array();
    }
    
  }

  function fun_send_otp_sms($member_data = array())
  {
    if (count($member_data) > 0)
    {
      $regid = $member_data[0]['regid'];
      $regnumber = $member_data[0]['regnumber'];
      $email_id = $member_data[0]['email'];
      $mobile_no = $member_data[0]['mobile'];
      $otp = $this->generate_otp();
      $otp_sent_on = date('Y-m-d H:i:s');
      $otp_expired_on = date('Y-m-d H:i:s', strtotime('+ 10 minutes', strtotime($otp_sent_on)));

      //send email and sms for otp
      //if email and sms sent successfully then execute below code 

      // SEND OTP ON EMAIL : START
      /*$otp_mail_arr['to'] = 'shrawanee.satpute@esds.co.in';//$email_id;
        $otp_mail_arr['subject'] = 'IIBF : Ordinary membership login OTP';
        $otp_mail_arr['message'] = '
            Dear ' . $member_data[0]['firstname'] . ' (Employee ID: ' . $member_data[0]['regnumber'] . '), <br/><br/>
            Your OTP for Login is <strong>' . $otp . '</strong> (Valid for 10 minutes). <br/>
            <div style="margin-top:10px;">Note : Do not share it with anyone.</div><br>
            Thanks & Regards,<br>IIBF TEAM ';
        $email_response = $this->Emailsending->mailsend($otp_mail_arr);*/

      $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'member_login_with_otp'));


      $email_text = $emailerstr[0]['emailer_text'];
      $email_text = str_replace('#CANDIDATENAME#', $member_data[0]['firstname'] . " " . $member_data[0]['lastname'], $email_text);
      $email_text = str_replace('#OTP#', $otp, $email_text);

      $sms_text = $emailerstr[0]['sms_text'];
      $sms_text = str_replace('#OTP#', $otp, $sms_text);

      $otp_mail_arr['to'] = $email_id; //$email_id;
      $otp_mail_arr['subject'] = $emailerstr[0]['subject'];
      $otp_mail_arr['message'] = $email_text;
      $email_response = $this->Emailsending->mailsend($otp_mail_arr);

      $sms_response = $this->master_model->send_sms_common_all($mobile_no, $sms_text, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']);

      if ($email_response)
      {
        $add_data['regid'] = $regid;
        $add_data['regnumber'] = $regnumber;
        $add_data['email'] = $email_id;
        $add_data['mobile'] = $mobile_no;
        $add_data['otp'] = $otp;
        $add_data['is_validate'] = '0';
        $add_data['otp_type'] = '1';
        $add_data['otp_expired_on'] = $otp_expired_on;
        $add_data['created_on'] = $otp_sent_on;
        $this->db->insert('member_login_otp ', $add_data);

        return true;
      }
      else
      {
        return false;
      }
    }
    else
    {
      return false;
    }
  }

  function fun_send_otp_sms_forgot($member_data = array())
  {
    if (count($member_data) > 0)
    {
      $regid = $member_data[0]['regid'];
      $regnumber = $member_data[0]['regnumber'];
      $email_id = $member_data[0]['email'];
      $mobile_no = $member_data[0]['mobile'];
      $otp = $this->generate_otp();
      //print_r($otp);
      $otp_sent_on = date('Y-m-d H:i:s');
      $otp_expired_on = date('Y-m-d H:i:s', strtotime('+ 10 minutes', strtotime($otp_sent_on)));

      $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'forgot_membership_no_otp'));
      //print_r($emailerstr);exit;

      $email_text = $emailerstr[0]['emailer_text'];
      $email_text = str_replace('#CANDIDATENAME#', $member_data[0]['firstname'] . " " . $member_data[0]['lastname'], $email_text);
      $email_text = str_replace('#OTP#', $otp, $email_text);

      $sms_text = $emailerstr[0]['sms_text'];
      $sms_text = str_replace('#OTP#', $otp, $sms_text);

      $otp_mail_arr['to'] = $email_id; //$email_id;
      $otp_mail_arr['subject'] = $emailerstr[0]['subject'];
      $otp_mail_arr['message'] = $email_text;
      $email_response = $this->Emailsending->mailsend($otp_mail_arr);
      //print_r($email_response);exit;

      $sms_response = $this->master_model->send_sms_common_all($mobile_no, $sms_text, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']);

      if ($email_response)
      {
        $add_data['regid'] = $regid;
        $add_data['regnumber'] = $regnumber;
        $add_data['email'] = $email_id;
        $add_data['mobile'] = $mobile_no;
        $add_data['otp'] = $otp;
        $add_data['is_validate'] = '';
        $add_data['otp_type'] = '2';
        $add_data['otp_expired_on'] = $otp_expired_on;
        $add_data['created_on'] = $otp_sent_on;
        // print_r($add_data);exit;
        $this->db->insert('member_login_otp ', $add_data);
        //print_r($p);exit;

        return true;
      }
      else
      {
        return false;
      }
    }
    else
    {
      return false;
    }
  }


  public function login_with_otp()
  {
    if ($this->session->userdata('regid'))
    {
      redirect(base_url() . 'home/dashboard/');
    }

    if (count($_POST) && count($_POST) > 0)
    {
      $action_flag = '';
      if (isset($_POST['send_otp']) && $_POST['send_otp'] == 'Send OTP')
      {
        $action_flag = 'send_otp';
      }
      else if (isset($_POST['verify_otp']) && $_POST['verify_otp'] == 'Verify OTP')
      {
        $action_flag = 'verify_otp';
      }

      $this->form_validation->set_rules('regnumber', 'Membership No', 'trim|required|callback_validation_validate_membership_no|xss_clean', array('required' => 'Please enter the %s'));

      if ($action_flag == 'send_otp')
      {
        $this->form_validation->set_rules('code', 'characters you see in the picture', 'trim|required|callback_validation_check_captcha_login_with_otp|xss_clean', array('required' => 'Please enter the %s'));
      }

      if ($action_flag == 'verify_otp')
      {
        $this->form_validation->set_rules('input_otp', 'OTP', 'trim|required|callback_validation_validate_otp|xss_clean', array('required' => 'Please enter the %s'));
      }

      if ($this->form_validation->run() == TRUE)
      {
        $regnumber   = $this->input->post('regnumber');
        $member_data = $this->get_member_data_login_with_otp($regnumber);

        if (count($member_data) > 0)
        {
          if ($action_flag == 'send_otp')
          {
            $_SESSION['LOGIN_OTP_MEMBERSHIP_NO'] = $regnumber;

            $send_otp = $this->fun_send_otp_sms($member_data);
            // print_r($send_otp);exit;
            if ($send_otp)
            {
              $this->session->set_flashdata('success', 'OTP successfully sent on ' . $this->mask_email_mobile('mobile', $member_data[0]['mobile']) . ' & ' . $this->mask_email_mobile('email', $member_data[0]['email']) . '. OTP is valid for 10 minutes.');
            }
            else
            {
              $this->session->set_flashdata('error', 'Error occurred. Please try again.');
            }
            redirect(site_url('login/login_with_otp'));
          }
          else if ($action_flag == 'verify_otp')
          {
            $otp_data = $this->master_model->getRecords('member_login_otp', array('regnumber' => $regnumber, 'is_validate' => '0', 'otp_type' => '1'), 'otp_id, otp, is_validate, otp_expired_on', array('otp_id' => 'DESC'), '', 1);
            $input_otp = $this->input->post('input_otp');

            if (count($otp_data) > 0)
            {
              if ($otp_data[0]['otp'] != $input_otp)
              {
                $this->session->set_flashdata('error', 'Please enter the correct OTP.');
                redirect(site_url('login/login_with_otp'));
              }
              else if ($otp_data[0]['otp_expired_on'] < date("Y-m-d H:i:s"))
              {
                $this->session->set_flashdata('error', 'The OTP has already expired.');
                redirect(site_url('login/login_with_otp'));
              }
              else
              {
                $up_data['is_validate'] = 1;
                $up_data['updated_on']  = date("Y-m-d H:i:s");
                $this->master_model->updateRecord('member_login_otp', $up_data, array('otp_id' => $otp_data[0]['otp_id']));

                $_SESSION['LOGIN_OTP_MEMBERSHIP_NO'] = '';

                $showlink = "no";
                $mysqltime = date("H:i:s");
                $user_data['regid'] = $member_data[0]['regid'];
                $user_data['regnumber'] = $member_data[0]['regnumber'];
                $user_data['firstname'] = $member_data[0]['firstname'];
                $user_data['middlename'] = $member_data[0]['middlename'];
                $user_data['lastname'] = $member_data[0]['lastname'];
                $user_data['memtype'] = $member_data[0]['registrationtype'];
                $user_data['timer'] = base64_encode($mysqltime);
                $user_data['showlink'] = $showlink;
                $user_data['exam_name'] = '';
                $user_data['password'] = $member_data[0]['usrpassword'];
                $this->session->set_userdata($user_data);

                //redirect(base_url().'home/dashboard/'); 
                if (isset($_SESSION['GARP_REDIRECT_EDIT_PROFILE_FLAG']) && $_SESSION['GARP_REDIRECT_EDIT_PROFILE_FLAG'] == 1 && isset($_SESSION['GARP_REDIRECT_EDIT_PROFILE_VALIDITY']) && $_SESSION['GARP_REDIRECT_EDIT_PROFILE_VALIDITY'] >= date("Y-m-d H:i"))
                {
                  redirect(site_url('home/profile'));
                }
                else
                {
                  $_SESSION['GARP_REDIRECT_EDIT_PROFILE_FLAG'] = 0;
                  $_SESSION['GARP_REDIRECT_EDIT_PROFILE_VALIDITY'] = '';
                  redirect(site_url('home/dashboard'));
                }
              }
            }
            else
            {
              $this->session->set_flashdata('error', 'Please enter the correct OTP.');
              redirect(site_url('login/login_with_otp'));
            }
          }
        }
      }
    }

    $session_regnumber = $_SESSION['LOGIN_OTP_MEMBERSHIP_NO'];

    $member_data = $this->get_member_data_login_with_otp($session_regnumber);
    // print_r($member_data);exit;
    $show_otp_input_flag = 0;
    if (count($member_data) > 0)
    {
      $regid = $member_data[0]['regid'];
      $session_regnumber = $_SESSION['LOGIN_OTP_MEMBERSHIP_NO'] = $regnumber = $member_data[0]['regnumber'];
      $email_id = $member_data[0]['email'];
      $mobile_no = $member_data[0]['mobile'];

      $show_otp_input_flag = 1;
      $data['mask_email'] = $this->mask_email_mobile('email', $email_id);
      $data['mask_mobile'] = $this->mask_email_mobile('mobile', $mobile_no);

      $get_otp_record = $this->master_model->getRecords('member_login_otp', array('regnumber' => $regnumber, 'otp_type'=>'1'), 'otp_id, otp_expired_on, created_on', array('otp_id' => 'DESC'), '', 1);
      //print_r($get_otp_record);
      if (count($get_otp_record) > 0)
      {
        $get_otp_record[0]['created_on'];
        $data['resend_time_sec'] = $resend_time_sec = $this->check_time($get_otp_record[0]['created_on']);
      }
    }

    $data['session_regnumber'] = $session_regnumber;
    $data['member_data'] = $member_data;
    $data['show_otp_input_flag'] = $show_otp_input_flag;

    $this->load->model('Captcha_model');
    $captcha_img = $this->Captcha_model->generate_captcha_img('LOGIN_WITH_OTP_CAPTCHA');
    $data['image'] = $captcha_img;

    $this->load->view('login_with_otp', $data);
  }

  public function generate_captcha_ajax_login_with_otp()
  {
    $this->load->model('Captcha_model');
    echo $captcha_img = $this->Captcha_model->generate_captcha_img('LOGIN_WITH_OTP_CAPTCHA');
  }

  public function validation_validate_membership_no($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
  {
    $return_val_ajax = 'false';
    $error_msg = 'Please enter the valid membership number';
    if (isset($_POST) && $_POST['regnumber'] != "")
    {
      if ($type == '1')
      {
        $regnumber = $this->input->post('regnumber');
      }
      else if ($type == '0')
      {
        $regnumber = $str;
      }

      $member_data = $this->get_member_data_login_with_otp($regnumber);
      if (count($member_data) > 0)
      {
        if($member_data[0]['mobile'] != "") { $return_val_ajax = 'true'; }
        else { $error_msg = 'Your mobile number is not registred with us. Please contact to IIBF'; }
      }
    }

    if ($type == '1')
    {
      echo $return_val_ajax;
    }
    else if ($type == '0')
    {
      if ($return_val_ajax == 'true')
      {
        return TRUE;
      }
      else
      {
        $this->form_validation->set_message('validation_validate_membership_no', $error_msg);
        return false;
      }
    }
  }

  public function validation_check_captcha_login_with_otp($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
  {
    $return_val_ajax = 'false';
    if (isset($_POST) && $_POST['code'] != "")
    {
      if ($type == '1')
      {
        $captcha = $this->input->post('code');
      }
      else if ($type == '0')
      {
        $captcha = $str;
      }

      $session_captcha = $this->session->userdata('LOGIN_WITH_OTP_CAPTCHA');
      if ($captcha == $session_captcha)
      {
        $return_val_ajax = 'true';
      }
    }

    if ($type == '1')
    {
      echo $return_val_ajax;
    }
    else if ($type == '0')
    {
      if ($return_val_ajax == 'true')
      {
        return TRUE;
      }
      else
      {
        $this->form_validation->set_message('validation_check_captcha_login_with_otp', 'Please enter the exact characters you see in the picture');
        return false;
      }
    }
  }

  public function validation_validate_otp_forgot($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
  {
    //$return_val_ajax = 'false';
    $result['flag'] = $flag = "error";
    $response = 'Please enter the correct OTP';
    if (isset($_POST) && $_POST['input_otp'] != "" && $_POST['email_mobile'] != "")
    {
      if ($type == '1')
      {
        $input_otp = $this->input->post('input_otp');
      }
      else if ($type == '0')
      {
        $input_otp = $str;
      }

      $email_mobile = $this->input->post('email_mobile');

      $this->db->where(" (email = '".$email_mobile."' OR mobile = '".$email_mobile."') "); 
      $otp_data = $this->master_model->getRecords('member_login_otp', array('otp_type' => '2'), 'otp_id, otp, is_validate, otp_expired_on', array('otp_id' => 'DESC'), '', 1);      
      if (count($otp_data) > 0)
      {
        if ($otp_data[0]['otp'] != $input_otp)
        {
          $result['response'] = $response = "Please enter the correct OTP.";
        }
        else if ($otp_data[0]['otp_expired_on'] < date("Y-m-d H:i:s"))
        {
          $result['response'] = $response = "The OTP has already expired.";
        }
        else
        {
          $result['flag'] = $flag = "success";
          $result['response'] = $response = '';
        }
      }
      else
      {
        $result['response'] = $response = "Please enter the correct OTP.";
      }
    }

    if ($type == '1')
    {/*  echo $return_val_ajax; */
      echo json_encode($result);
    }
    else if ($type == '0')
    {
      /* echo $flag;
      print_r($response);
      exit; */
      if ($flag == 'success')
      {
        return TRUE;
      }
      else
      {
        $this->form_validation->set_message('validation_validate_otp_forgot', $response);
        return false;
      }
    }
  }


  public function validation_validate_otp($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
  {
    //$return_val_ajax = 'false';
    $result['flag'] = $flag = "error";
    $response = 'Please enter the correct OTP';
    if (isset($_POST) && $_POST['input_otp'] != "" && $_POST['regnumber'] != "")
    {
      if ($type == '1')
      {
        $input_otp = $this->input->post('input_otp');
      }
      else if ($type == '0')
      {
        $input_otp = $str;
      }

      $regnumber = $this->input->post('regnumber');

      $otp_data = $this->master_model->getRecords('member_login_otp', array('regnumber' => $regnumber, 'is_validate' => '0', 'otp_type'=>'1'), 'otp_id, otp, is_validate, otp_expired_on', array('otp_id' => 'DESC'), '', 1);
      if (count($otp_data) > 0)
      {
        if ($otp_data[0]['otp'] != $input_otp)
        {
          $result['response'] = $response = "Please enter the correct OTP.";
        }
        else if ($otp_data[0]['otp_expired_on'] < date("Y-m-d H:i:s"))
        {
          $result['response'] = $response = "The OTP has already expired.";
        }
        else
        {
          $result['flag'] = $flag = "success";
        }
      }
      else
      {
        $result['response'] = $response = "Please enter the correct OTP.";
      }
    }

    if ($type == '1')
    {/*  echo $return_val_ajax; */
      echo json_encode($result);
    }
    else if ($type == '0')
    {
      if ($flag == 'success')
      {
        return TRUE;
      }
      else
      {
        $this->form_validation->set_message('validation_validate_otp', $response);
        return false;
      }
    }
  }

  public function reset_form_ajax()
  {
    $_SESSION['LOGIN_OTP_MEMBERSHIP_NO'] = '';
    $result['flag'] = "success";
    echo json_encode($result);
  }

  public function reset_form_forgot_ajax()
  {
    $_SESSION['FORGOT_OTP_MEMBERSHIP_NO'] = '';
    $result['flag'] = "success";
    echo json_encode($result);
  }
  
  public function generate_otp()
  {
    //return '123456';
    return rand(100000, 999999);
  } //END : GENERATE OTP FUNCTIONALITY

  public function mask_email_mobile($type = '', $str = '') //$type = 'mobile' / 'email' : 
  {
    $show_start = 0;
    $show_end = 3;

    if ($type == 'email')
    {
      $show_start = 1;

      $explode_email_arr = explode("@", $str);
      $show_end = strlen($explode_email_arr[1]) + 2;
    }

    $str_repeat = 0;
    $str_repeat = (strlen($str) - ($show_start + $show_end));
    
    // Ensure the repeat count is non-negative
    $str_repeat = max(0, $str_repeat);

    //return substr($str, 0, $show_start) . str_repeat('*', (strlen($str) - ($show_start + $show_end))) . substr($str, '-' . $show_end);
    return substr($str, 0, $show_start) . str_repeat('*', $str_repeat) . substr($str, '-' . $show_end);
  }

  function check_time($time)
  {
    $timeMobileFirst  = strtotime($time);
    $currentTimeInSec = strtotime(date('Y-m-d H:i:s'));

    $remainingTimeMobile = 0;
    if ($timeMobileFirst <= $currentTimeInSec)
    {
      $diffTimeMobile =  $currentTimeInSec - $timeMobileFirst;

      if ($diffTimeMobile < $this->otptime)
      {
        $remainingTimeMobile = $this->otptime - $diffTimeMobile;
      }
    }
    return $remainingTimeMobile;
  } //END : TO DISPLAY THE REMAINING TIME FOR RESEND OTP
}
