<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Register extends CI_Controller
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
    }
    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *         http://example.com/index.php/welcome
     *    - or -
     *         http://example.com/index.php/welcome/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function get_client_ip() {
        // echo "test";exit;

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

    /*--- Function created by gaurav shewale(12th march 2024) for email verification--*/
    public function send_otp()
    {
        $email = strtolower($_POST['email']);
        $type  = $_POST['type'];
        if ($type == 'send_otp' || $type == 'resend_otp') 
        {
            $arr_email_status = $this->check_email_exist($email);
            if ( $arr_email_status['status'] ) 
            {
                $sendOTPStatus = $this->send_otp_sms_email($email,'email');
                if ( $sendOTPStatus ) {
                    $status = true;
                    $msg    = 'OTP successfully sent to email address. The OTP is valid for 10 minutes.';  
                } else {
                    $status = false;
                    $msg    = 'Error occured, While sending an OTP on email id.';
                }                 
            }
            else 
            {
                $status = $arr_email_status['status'];;
                $msg    = $arr_email_status['msg'];
            }     
        }
        elseif ($type == 'verify_otp') 
        {
            $input_otp = $_POST['otp'];

            $otp_data = $this->master_model->getRecords('member_login_otp', array('email' => $email, 'is_validate' => '0', 'otp_type' => '3'), 'otp_id, otp, is_validate, otp_expired_on', array('otp_id' => 'DESC'), '', 1);
            
            if (count($otp_data) > 0)
            {
                if ($otp_data[0]['otp'] != $input_otp)
                {
                    $status = false;
                    $msg = 'Please enter the correct OTP.';
                }
                else if ($otp_data[0]['otp_expired_on'] < date("Y-m-d H:i:s"))
                {
                    $status = false;
                    $msg = 'The OTP has already expired.';
                }
                else
                {
                    $up_data['is_validate'] = 1;
                    $up_data['updated_on']  = date("Y-m-d H:i:s");
                    $this->master_model->updateRecord('member_login_otp', $up_data, array('otp_id' => $otp_data[0]['otp_id']));

                    $status = true;
                    $msg = 'OTP verified successfully.'; 
                }
            }
            else
            {
                $status = false;
                $msg    = 'No record found.';
            }      
        }

        $arr_email_status['status'] = $status;
        $arr_email_status['msg']    = $msg;
        echo json_encode($arr_email_status);
    }

    public function send_otp_mobile()
    {
        $mobile = $_POST['mobile'];
        $type   = $_POST['type'];
        if ($type == 'send_otp' || $type == 'resend_otp') 
        {
            $arr_mobile_status = $this->check_mobile_exist($mobile);

            if ( $arr_mobile_status['status'] ) 
            {
                $sendOTPStatus = $this->send_otp_sms_email($mobile,'mobile');
                if ( $sendOTPStatus ) {
                    $status = true;
                    $msg    = 'OTP successfully sent to mobile number. The OTP is valid for 10 minutes.';  
                } else {
                    $status = false;
                    $msg    = 'Error occured, While sending an OTP on mobile no.';
                }                 
            } 
            else 
            {
                $status = $arr_mobile_status['status'];;
                $msg    = $arr_mobile_status['msg'];
            }  
        }
        elseif ($type == 'verify_otp') 
        {
            $input_otp = $_POST['otp'];

            $otp_data = $this->master_model->getRecords('member_login_otp', array('mobile' => $mobile, 'is_validate' => '0', 'otp_type' => '4'), 'otp_id, otp, is_validate, otp_expired_on', array('otp_id' => 'DESC'), '', 1);
            
            if (count($otp_data) > 0)
            {
                if ($otp_data[0]['otp'] != $input_otp)
                // if (false)
                {
                    $status = false;
                    $msg = 'Please enter the correct OTP.';
                }
                else if ($otp_data[0]['otp_expired_on'] < date("Y-m-d H:i:s"))
                // else if (false)
                {
                    $status = false;
                    $msg = 'The OTP has already expired.';
                }
                else
                {
                    $up_data['is_validate'] = 1;
                    $up_data['updated_on']  = date("Y-m-d H:i:s");
                    $this->master_model->updateRecord('member_login_otp', $up_data, array('otp_id' => $otp_data[0]['otp_id']));

                    $status = true;
                    $msg = 'OTP verified successfully.'; 
                }
            }
            else
            {
                $status = false;
                $msg    = 'No record found.';
            }      
        }

        $arr_mobile_status['status'] = $status;
        $arr_mobile_status['msg']    = $msg;
        echo json_encode($arr_mobile_status);
    }

    /*--- Function created by gaurav shewale(12th march 2024) for email verification--*/
    private function send_otp_sms_email( $data,$field_type )
    { 
        $data           = $data;
        // $email_id    = $email;
        $otp            = rand(100000, 999999);;
        $otp_sent_on    = date('Y-m-d H:i:s');
        $otp_expired_on = date('Y-m-d H:i:s', strtotime('+ 10 minutes', strtotime($otp_sent_on)));

        $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'email_mobile_verification'));
        if ($field_type == 'email') 
        {
            $email_text = $emailerstr[0]['emailer_text'];
            // $email_text = str_replace('#CANDIDATENAME#', "Test", $email_text);
            $email_text = str_replace('#OTP#', $otp, $email_text);
            
            $otp_mail_arr['to']      = $data;
            $otp_mail_arr['subject'] = $emailerstr[0]['subject'];
            $otp_mail_arr['message'] = $email_text;
            $email_sms_response = $this->Emailsending->mailsend($otp_mail_arr);
        }
        elseif ($field_type == 'mobile') 
        {
            $sms_text = $emailerstr[0]['sms_text'];
            $sms_text = str_replace('#OTP#', $otp, $sms_text);

            $email_sms_response = $this->master_model->send_sms_common_all($data, $sms_text, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']);
            if(isset($email_sms_response['status']) && $email_sms_response['status'] == 'success') { }
            else { return false; exit; }
        }

        if ($email_sms_response)
        {
            if ($field_type == 'email') {
                $add_data['email']    = $data;
                $add_data['otp_type'] = '3';
            } else {
                $add_data['mobile']   = $data;
                $add_data['otp_type'] = '4';
            }

            $add_data['otp']            = $otp;
            $add_data['is_validate']    = '0';
            $add_data['otp_expired_on'] = $otp_expired_on;
            $add_data['created_on']     = $otp_sent_on;

            $this->db->insert('member_login_otp ', $add_data);
            return true;
        }
        else
        {
            return false;
        }
    }

    private function check_email_exist($email)
    {
        $arr_response = array();
        $arr_response['status'] = true;
        $arr_response['msg']    = '';

        if ($email != "") {
            $where = "( registrationtype='O' OR registrationtype='A' OR registrationtype='F')";
            $this->db->where($where);
            $prev_count = $this->master_model->getRecordCount('member_registration', array('email' => $email, 'isactive' => '1'));
            //echo $this->db->last_query();
            if ( $prev_count == 0 ) {
                $arr_response['status'] = true;
                $arr_response['msg']    = '';  
            } else {
                $user_info        = $this->master_model->getRecords('member_registration', array('email' => $email), 'regnumber,firstname,middlename,lastname');
                $username         = $user_info[0]['firstname'] . ' ' . $user_info[0]['middlename'] . ' ' . $user_info[0]['lastname'];
                $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
                $str              = 'The entered email ID already exist for membership / registration number ' . $user_info[0]['regnumber'] . ' , ' . $userfinalstrname . '';

                $arr_response['status'] = false;
                $arr_response['msg']    = $str;
            }
        } else {
            $arr_response['status'] = false;
            $arr_response['msg']    = 'Please enter email id.';
        }
        return $arr_response;
    }

    //call back for mobile duplication
    public function check_mobile_exist($mobile)
    {
        $arr_response = array();
        $arr_response['status'] = true;
        $arr_response['msg']    = '';

        if ($mobile != "") {
            $where = "( registrationtype='O' OR registrationtype='A' OR registrationtype='F')";
            $this->db->where($where);
            $prev_count = $this->master_model->getRecordCount('member_registration', array('mobile' => $mobile, 'isactive' => '1'));
            //echo $this->db->last_query();
            if ($prev_count == 0) {
                $arr_response['status'] = true;
                $arr_response['msg']    = '';
            } else {
                $user_info        = $this->master_model->getRecords('member_registration', array('mobile' => $mobile), 'regnumber,firstname,middlename,lastname');
                $username         = $user_info[0]['firstname'] . ' ' . $user_info[0]['middlename'] . ' ' . $user_info[0]['lastname'];
                $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
                $str              = 'The entered  mobile no already exist for membership / registration number ' . $user_info[0]['regnumber'] . ' , ' . $userfinalstrname . '';
                $this->form_validation->set_message('check_mobileduplication', $str);
                
                $arr_response['status'] = false;
                $arr_response['msg']    = $str;
            }
        } else {
            $arr_response['status'] = false;
            $arr_response['msg']    = 'Please enter mobile no.';
        }
        return $arr_response;
    }

    public function member()
    {
      $this->load->helper('iibfbcbf/iibf_bcbf_helper');
      $scannedphoto_path = 'uploads/photograph';
      $scannedsignaturephoto_path = 'uploads/scansignature';
      $idproofphoto_path = 'uploads/idproof';
      $declarationform_path = 'uploads/declaration';
      
      ## uncomment below code to redirect page under maintenance
      //redirect ("uc.html");
      //exit;
      if ($this->session->userdata('enduserinfo'))
      {
        $this->session->unset_userdata('enduserinfo');
      }
      $flag = 1;
      $valcookie = register_get_cookie();
    //   echo $valcookie;exit;
      
      if ($valcookie)
      {
        $regid = $valcookie;
        //$regid= '57';
        $checkuser = $this->master_model->getRecords('member_registration', array('regid' => $regid, 'regnumber !=' => '', 'isactive !=' => '0'));
        if (count($checkuser) > 0)
        {
          delete_cookie('regid');
        }
        else
        {
          $checkpayment = $this->master_model->getRecords('payment_transaction', array('ref_id' => $regid, 'status' => '2'));
          //echo $this->db->last_query();
          //exit;
          if (count($checkpayment) > 0)
          {
            ///$datearr=explode(' ',$checkpayment[0]['date']);
            $endTime      = date("Y-m-d H:i:s", strtotime("+20 minutes", strtotime($checkpayment[0]['date'])));
            $current_time = date("Y-m-d H:i:s");
            if (strtotime($current_time) <= strtotime($endTime))
            {
              $flag = 0;
            }
            else
            {
              delete_cookie('regid');
            }
          }
          else
          {
            $flag = 1;
            delete_cookie('regid');
          }
        }
      }
      $scannedphoto_file = $scannedsignaturephoto_file = $idproofphoto_file = $password = $var_errors = '';
      $scanned_vis_imp_cert_file = $scanned_vis_imp_cert_file = $scanned_cer_palsy_cert_file = $declarationphoto_file = '';
      $data['validation_errors'] = '';
      if (isset($_POST['btnSubmit']))
      {
        $scannedphoto_file = $scannedsignaturephoto_file = $idproof_file = $outputphoto1 = $outputsign1 = $outputidproof1 = $declaration_file = $declaration_form = $state = '';
        $output_vis_imp_cert1 = $output_orth_han_cert1 = $output_cer_palsy_cert1 = $scanned_vis_imp_cert_file = $scanned_vis_imp_cert_file = $scanned_cer_palsy_cert_file = '';
        $this->form_validation->set_rules('sel_namesub', 'Name Prefix', 'trim|required|xss_clean');
        $this->form_validation->set_rules('firstname', 'First Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean|callback_validate_full_name_total_length');
        //$this->form_validation->set_rules('nameoncard', 'Name as to appear on Card', 'trim|max_length[35]|required|alpha_numeric_spaces|xss_clean');
        $this->form_validation->set_rules('addressline1', 'Addressline1', 'trim|max_length[30]|required|xss_clean|callback_address1');
        $this->form_validation->set_rules('district', 'District', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
        $this->form_validation->set_rules('city', 'City', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
        $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean');
        /*if($this->input->post('state')!='')
        {
          $state=mysqli_real_escape_string($this->db->conn_id,$this->input->post('state'));
        }*/
        $state = $this->input->post('state');
        $this->form_validation->set_rules('pincode', 'Pincode/Zipcode', 'trim|required|numeric|xss_clean|callback_check_checkpin[' . $state . ']');
        $this->form_validation->set_rules('dob1', 'Date of Birth', 'trim|required|xss_clean');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|required|xss_clean');
        $this->form_validation->set_rules('optedu', 'Qualification', 'trim|required|xss_clean');

        if (isset($_POST['middlename']))
        {
          $this->form_validation->set_rules('middlename', 'Middle Name', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
        }
        if (isset($_POST['lastname']))
        {
          $this->form_validation->set_rules('lastname', 'Last Name', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
        }
        if (isset($_POST['optedu']) && $_POST['optedu'] == 'U')
        {
          $this->form_validation->set_rules('eduqual1', 'Please specify', 'trim|required|xss_clean');
        }
        else if (isset($_POST['optedu']) && $_POST['optedu'] == 'G')
        {
          $this->form_validation->set_rules('eduqual2', 'Please specify', 'trim|required|xss_clean');
        }
        else if (isset($_POST['optedu']) && $_POST['optedu'] == 'P')
        {
          $this->form_validation->set_rules('eduqual3', 'Please specify', 'trim|required|xss_clean');
        }

        if (isset($_POST['addressline2']) && $_POST['addressline2'] != '')
        {
          $this->form_validation->set_rules('addressline2', 'Addressline2', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
        }

        if (isset($_POST['addressline3']) && $_POST['addressline3'] != '')
        {
          $this->form_validation->set_rules('addressline3', 'Addressline3', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
        }

        if (isset($_POST['addressline4']) && $_POST['addressline4'] != '')
        {
          $this->form_validation->set_rules('addressline4', 'Addressline4', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
        }

        //====================changes by tejasvi============
        /*  if($this->input->post('state_pr')!='')
        {
          $state_pr=mysqli_real_escape_string($this->db->conn_id,$this->input->post('state_pr'));
        } */
        $state_pr = $this->input->post('state_pr');
        $this->form_validation->set_rules('addressline1_pr', 'Addressline1', 'trim|max_length[30]|required|xss_clean|callback_address1');
        $this->form_validation->set_rules('district_pr', 'District', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
        $this->form_validation->set_rules('city_pr', 'City', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
        $this->form_validation->set_rules('state_pr', 'State', 'trim|required|xss_clean');
        $this->form_validation->set_rules('pincode_pr', 'Pincode/Zipcode', 'trim|required|numeric|xss_clean|callback_check_checkpin_permanant[' . $state_pr . ']');

        if (isset($_POST['addressline2_pr']) && $_POST['addressline2_pr'] != '')
        {
          $this->form_validation->set_rules('addressline2_pr', 'Addressline2', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
        }
        if (isset($_POST['addressline3_pr']) && $_POST['addressline3_pr'] != '')
        {
          $this->form_validation->set_rules('addressline3_pr', 'Addressline3', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
        }
        if (isset($_POST['addressline4_pr']) && $_POST['addressline4_pr'] != '')
        {
          $this->form_validation->set_rules('addressline4_pr', 'Addressline4', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
        }
        //================================================

        if (isset($_POST['stdcode']) && $_POST['stdcode'] != '')
        {
          $this->form_validation->set_rules('stdcode', 'STD Code', 'trim|max_length[4]|required|numeric|xss_clean');
        }

        if (isset($_POST['phone']) && $_POST['phone'] != '')
        {
          $this->form_validation->set_rules('phone', ' Phone No', 'trim|required|numeric|xss_clean');
        }

        $this->form_validation->set_rules('institutionworking', 'Bank/Institution working', 'trim|required|alpha_numeric_spaces|xss_clean');
        $this->form_validation->set_rules('office', 'Branch/Office', 'trim|required|alpha_numeric_spaces|xss_clean');
        $this->form_validation->set_rules('designation', 'Designation', 'trim|required|xss_clean');
        $this->form_validation->set_rules('doj1', 'Date of joining Bank/Institution', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean|callback_check_emailduplication|callback_check_email_mobile_otp_verification[email]');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|xss_clean|callback_check_mobileduplication|callback_check_email_mobile_otp_verification[mobile]');
        
        if ($this->input->post('state') == 'ASS' || $this->input->post('state') == 'JAM' || $this->input->post('state') == 'MEG')
        {
          if ($this->input->post('aadhar_card') != '')
          {
            //$this->form_validation->set_rules('aadhar_card','Aadhar Card Number','trim|max_length[12]|numeric|xss_clean|callback_check_aadhar');
            $this->form_validation->set_rules('aadhar_card', 'Aadhar Card Number', 'trim|max_length[12]|min_length[12]|numeric|xss_clean|callback_check_aadhar');
          }
        }
        else
        {
          if ($this->input->post('aadhar_card') != '')
          {
            //$this->form_validation->set_rules('aadhar_card','Aadhar Card Number','trim|required|max_length[12]|numeric|xss_clean|callback_check_aadhar');
            //$this->form_validation->set_rules('aadhar_card','Aadhar Card Number','trim|max_length[12]|numeric|xss_clean|callback_check_aadhar');
            $this->form_validation->set_rules('aadhar_card', 'Aadhar Card Number', 'trim|max_length[12]|min_length[12]|numeric|xss_clean|callback_check_aadhar');
          }
        }

        $scannedphoto_req_flg = $scannedsignaturephoto_req_flg = $idproofphoto_req_flg = $declarationform_req_flg = 'required|';

        if (isset($_POST['scannedphoto_cropper']) && $_POST['scannedphoto_cropper'] != "") { $scannedphoto_req_flg = ''; }
        if (isset($_POST['scannedsignaturephoto_cropper']) && $_POST['scannedsignaturephoto_cropper'] != "") { $scannedsignaturephoto_req_flg = ''; }        
        if (isset($_POST['idproofphoto_cropper']) && $_POST['idproofphoto_cropper'] != "") { $idproofphoto_req_flg = ''; }
        if (isset($_POST['declarationform_cropper']) && $_POST['declarationform_cropper'] != "") { $declarationform_req_flg = ''; }

        $this->form_validation->set_rules('scannedphoto', 'Scanned Photograph', 'trim|'.$scannedphoto_req_flg.'xss_clean');
        $this->form_validation->set_rules('scannedsignaturephoto', 'Scanned Signature Specimen', 'trim|'.$scannedsignaturephoto_req_flg.'xss_clean');
        $this->form_validation->set_rules('idproofphoto', 'Id Proof', 'trim|'.$idproofphoto_req_flg.'xss_clean');
        $this->form_validation->set_rules('declarationform', 'Declaration Form', 'trim|'.$declarationform_req_flg.'xss_clean');        
        
        /* $this->form_validation->set_rules('scannedphoto', 'scanned Photograph', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[50]|callback_scannedphoto_upload');
        $this->form_validation->set_rules('scannedsignaturephoto', 'Scanned Signature Specimen', 'file_required|file_allowed_type[jpg,jpeg,png]|file_size_max[50]|callback_scannedsignaturephoto_upload');
        // $this->form_validation->set_rules('idproof','Id Proof','trim|required|xss_clean');
        $this->form_validation->set_rules('idproofphoto', 'Id proof', 'file_required|file_allowed_type[jpg,jpeg,png]|file_size_max[300]|callback_idproofphoto_upload');
        $this->form_validation->set_rules('declarationform', 'Declaration Form', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[300]|callback_declarationform_upload'); */
        
        $this->form_validation->set_rules('code', 'Security Code ', 'trim|required|xss_clean|callback_check_captcha_userreg');
        $this->form_validation->set_rules('declaration1', 'I Accept', 'trim|required|xss_clean');
        $this->form_validation->set_rules('bank_emp_id', 'Bank Employee Id', 'required|xss_clean');

        if (isset($_POST['visually_impaired']) && $_POST['visually_impaired'] == 'Y')
        {
          $this->form_validation->set_rules('scanned_vis_imp_cert', 'Visually impaired Attach scan copy of PWD certificate', 'required');
        }
        if (isset($_POST['orthopedically_handicapped']) && $_POST['orthopedically_handicapped'] == 'Y')
        {
          $this->form_validation->set_rules('scanned_orth_han_cert', 'Orthopedically handicapped Attach scan copy of PWD certificate', 'required');
        }
        if (isset($_POST['cerebral_palsy']) && $_POST['cerebral_palsy'] == 'Y')
        {
          $this->form_validation->set_rules('scanned_cer_palsy_cert', 'Cerebral palsy Attach scan copy of PWD certificate', 'required');
        }

        if ($this->form_validation->run() == true)
        {
          $outputphoto1 = $outputsign1 = '';
          $scannedphoto_file = $scannedsignaturephoto_file = $idproof_file = $declaration_file = '';

          $output_vis_imp_cert1 = $output_orth_han_cert1 = $output_cer_palsy_cert1 = $scanned_vis_imp_cert_file = $scanned_orth_han_cert_file = $scanned_vis_imp_cert_file = '';
          
          $this->session->unset_userdata('enduserinfo');
          $eduqual1 = $eduqual2 = $eduqual3 = '';
          if ($_POST['optedu'] == 'U')
          {
            $eduqual1 = $_POST["eduqual1"];
          }
          else if ($_POST['optedu'] == 'G')
          {
            $eduqual2 = $_POST["eduqual2"];
          }
          else if ($_POST['optedu'] == 'P')
          {
            $eduqual3 = $_POST["eduqual3"];
          }

          if ($_POST["optnletter"] == 'N')
          {
            $_POST["optnletter"] = 'N';
          }
          elseif ($_POST["optnletter"] == 'Y')
          {
            $_POST["optnletter"] = 'Y';
          }
          else
          {
            $_POST["optnletter"] = 'Y';
          }

          $date = date('Y-m-d h:i:s');
          //$file_name_str = strtotime($date) . rand(0, 100);
          $file_name_str = strtotime($date).rand(10000, 99999);

          //Generate dynamic photo
          /* $input = $_POST["hiddenphoto"];
          if (isset($_FILES['scannedphoto']['name']) && ($_FILES['scannedphoto']['name'] != ''))
          {
            $img = "scannedphoto";
            $tmp_nm = strtotime($date) . rand(0, 100);
            $new_filename = 'photo_' . $tmp_nm;
            $config = array(
              'upload_path' => './uploads/photograph',
              'allowed_types' => 'jpg|jpeg',
              'file_name' => $new_filename
            );

            $this->upload->initialize($config);
            $size = @getimagesize($_FILES['scannedphoto']['tmp_name']);
            if ($size)
            {
              if ($this->upload->do_upload($img))
              {
                $dt = $this->upload->data();
                $file = $dt['file_name'];
                $scannedphoto_file = $dt['file_name'];
                $outputphoto1 = base_url() . "uploads/photograph/" . $scannedphoto_file;
              }
              else
              {
                $var_errors .= $this->upload->display_errors();
                //$data['error']=$this->upload->display_errors();
              }
            }
            else
            {
              $var_errors .= 'The filetype you are attempting to upload is not allowed';
            }
          } */

          if (isset($_POST['scannedphoto_cropper']) && $_POST['scannedphoto_cropper'] != "")
          {
            $scannedphoto_cropper = $this->security->xss_clean($this->input->post('scannedphoto_cropper'));
            $new_file_name1 = "photo_" . $file_name_str . '.' . strtolower(pathinfo($scannedphoto_cropper, PATHINFO_EXTENSION));
            if (copy(str_replace(base_url(), '', $scannedphoto_cropper), $scannedphoto_path . '/' . $new_file_name1))
            {
              $scannedphoto_file = $new_file_name1;
              $outputphoto1 = base_url() . "uploads/photograph/" . $scannedphoto_file;
            }
          }

          // generate dynamic scan signature
          /* $inputsignature = $_POST["hiddenscansignature"];
          if (isset($_FILES['scannedsignaturephoto']['name']) && ($_FILES['scannedsignaturephoto']['name'] != ''))
          {
            $img = "scannedsignaturephoto";
            $tmp_signnm = strtotime($date) . rand(0, 100);
            $new_filename = 'sign_' . $tmp_signnm;
            $config = array(
              'upload_path' => './uploads/scansignature',
              'allowed_types' => 'jpg|jpeg|png',
              'file_name' => $new_filename
            );

            $this->upload->initialize($config);
            $size = @getimagesize($_FILES['scannedsignaturephoto']['tmp_name']);
            if ($size)
            {
              if ($this->upload->do_upload($img))
              {
                $dt = $this->upload->data();
                $scannedsignaturephoto_file = $dt['file_name'];
                $outputsign1 = base_url() . "uploads/scansignature/" . $scannedsignaturephoto_file;
              }
              else
              {
                $var_errors .= $this->upload->display_errors();
                //$data['error']=$this->upload->display_errors();
              }
            }
            else
            {
              $var_errors .= 'The filetype you are attempting to upload is not allowed';
            }
          } */

          if (isset($_POST['scannedsignaturephoto_cropper']) && $_POST['scannedsignaturephoto_cropper'] != "")
          {
            $scannedsignaturephoto_cropper = $this->security->xss_clean($this->input->post('scannedsignaturephoto_cropper'));
            $new_file_name2 = "sign_" . $file_name_str . '.' . strtolower(pathinfo($scannedsignaturephoto_cropper, PATHINFO_EXTENSION));
            if (copy(str_replace(base_url(), '', $scannedsignaturephoto_cropper), $scannedsignaturephoto_path . '/' . $new_file_name2))
            {
              $scannedsignaturephoto_file = $new_file_name2;
              $outputsign1 = base_url() . "uploads/scansignature/" . $scannedsignaturephoto_file;
            }
          }

          // generate dynamic id proof
          /* $inputidproofphoto = $_POST["hiddenidproofphoto"];
          if (isset($_FILES['idproofphoto']['name']) && ($_FILES['idproofphoto']['name'] != ''))
          {
            $img = "idproofphoto";
            $tmp_inputidproof = strtotime($date) . rand(0, 100);
            $new_filename = 'idproof_' . $tmp_inputidproof;
            $config = array(
              'upload_path' => './uploads/idproof',
              'allowed_types' => 'jpg|jpeg|png',
              'file_name' => $new_filename
            );

            $this->upload->initialize($config);
            $size = @getimagesize($_FILES['idproofphoto']['tmp_name']);
            if ($size)
            {
              if ($this->upload->do_upload($img))
              {
                $dt = $this->upload->data();
                $idproof_file = $dt['file_name'];
                $outputidproof1 = base_url() . "uploads/idproof/" . $idproof_file;
              }
              else
              {
                $var_errors .= $this->upload->display_errors();
                //$data['error']=$this->upload->display_errors();
              }
            }
            else
            {
              $var_errors .= 'The filetype you are attempting to upload is not allowed';
            }
          } */

          if (isset($_POST['idproofphoto_cropper']) && $_POST['idproofphoto_cropper'] != "")
          {
            $idproofphoto_cropper = $this->security->xss_clean($this->input->post('idproofphoto_cropper'));
            $new_file_name3 = "idproof_" . $file_name_str . '.' . strtolower(pathinfo($idproofphoto_cropper, PATHINFO_EXTENSION));
            if (copy(str_replace(base_url(), '', $idproofphoto_cropper), $idproofphoto_path . '/' . $new_file_name3))
            {
              $idproof_file = $new_file_name3;
              $outputidproof1 = base_url() . "uploads/idproof/" . $idproof_file;
            }
          }

          // generate declaration form by pratibha borse
          /* $inputdeclarationform = $_POST["hiddendeclarationform"];
          if (isset($_FILES['declarationform']['name']) && ($_FILES['declarationform']['name'] != ''))
          {
            $img = "declarationform";
            $tmp_declaration_form = strtotime($date) . rand(0, 100);
            $new_filename = 'declaration_' . $tmp_declaration_form;
            $config = array(
              'upload_path' => './uploads/declaration',
              'allowed_types' => 'jpg|jpeg',
              'file_name' => $new_filename
            );

            $this->upload->initialize($config);
            $size = @getimagesize($_FILES['declarationform']['tmp_name']);
            if ($size)
            {
              if ($this->upload->do_upload($img))
              {
                $dt = $this->upload->data();
                $declaration_file = $dt['file_name'];
                $declaration_form = base_url() . "uploads/declaration/" . $declaration_file;
              }
              else
              {
                $var_errors .= $this->upload->display_errors();
                //$data['error']=$this->upload->display_errors();
              }
            }
            else
            {
              $var_errors .= 'The filetype you are attempting to upload is not allowed';
            }
          } */

          if (isset($_POST['declarationform_cropper']) && $_POST['declarationform_cropper'] != "")
          {
            $declarationform_cropper = $this->security->xss_clean($this->input->post('declarationform_cropper'));
            $new_file_name4 = "declaration_" . $file_name_str . '.' . strtolower(pathinfo($declarationform_cropper, PATHINFO_EXTENSION));
            if (copy(str_replace(base_url(), '', $declarationform_cropper), $declarationform_path . '/' . $new_file_name4))
            {
              $declaration_file = $new_file_name4;
              $declaration_form = base_url() . "uploads/declaration/" . $declaration_file;
            }
          }

          /* Visually impaired certificate */
          $input_vis_imp_cert = $_POST["hidden_vis_imp_cert"];
          if (isset($_FILES['scanned_vis_imp_cert']['name']) && ($_FILES['scanned_vis_imp_cert']['name'] != ''))
          {
            $img = "scanned_vis_imp_cert";
            $tmp_nm = strtotime($date) . rand(0, 100);
            $new_filename = 'vis_imp_cert_' . $tmp_nm;
            $config = array(
              'upload_path' => './uploads/disability',
              'allowed_types'                     => 'jpg|jpeg',
              'file_name'                         => $new_filename
            );
            $this->upload->initialize($config);
            $size = @getimagesize($_FILES['scanned_vis_imp_cert']['tmp_name']);
            if ($size)
            {
              if ($this->upload->do_upload($img))
              {
                $dt = $this->upload->data();
                $file = $dt['file_name'];
                $scanned_vis_imp_cert_file = $dt['file_name'];
                $output_vis_imp_cert1 = base_url() . "uploads/disability/" . $scanned_vis_imp_cert_file;
              }
              else
              {
                $var_errors .= $this->upload->display_errors();
              }
            }
            else
            {
              $var_errors .= 'The filetype you are attempting to upload is not allowed';
            }
          }

          /* Orthopedically handicapped certificate */
          $input_orth_han_cert = $_POST["hidden_orth_han_cert"];
          if (isset($_FILES['scanned_orth_han_cert']['name']) && ($_FILES['scanned_orth_han_cert']['name'] != ''))
          {
            $img = "scanned_orth_han_cert";
            $tmp_nm = strtotime($date) . rand(0, 100);
            $new_filename = 'orth_han_cert_' . $tmp_nm;
            $config = array(
              'upload_path' => './uploads/disability',
              'allowed_types' => 'jpg|jpeg',
              'file_name' => $new_filename
            );
            $this->upload->initialize($config);
            $size = @getimagesize($_FILES['scanned_orth_han_cert']['tmp_name']);
            if ($size)
            {
              if ($this->upload->do_upload($img))
              {
                $dt = $this->upload->data();
                $file = $dt['file_name'];
                $scanned_orth_han_cert_file = $dt['file_name'];
                $output_orth_han_cert1 = base_url() . "uploads/disability/" . $scanned_orth_han_cert_file;
              }
              else
              {
                $var_errors .= $this->upload->display_errors();
              }
            }
            else
            {
              $var_errors .= 'The filetype you are attempting to upload is not allowed';
            }
          }

          /* Cerebral palsy certificate */
          $input_cer_palsy_cert = $_POST["hidden_cer_palsy_cert"];
          if (isset($_FILES['scanned_cer_palsy_cert']['name']) && ($_FILES['scanned_cer_palsy_cert']['name'] != ''))
          {
            $img = "scanned_cer_palsy_cert";
            $tmp_nm = strtotime($date) . rand(0, 100);
            $new_filename = 'cer_palsy_cert_' . $tmp_nm;
            $config = array(
              'upload_path' => './uploads/disability',
              'allowed_types' => 'jpg|jpeg',
              'file_name' => $new_filename
            );
            $this->upload->initialize($config);
            $size = @getimagesize($_FILES['scanned_cer_palsy_cert']['tmp_name']);
            if ($size)
            {
              if ($this->upload->do_upload($img))
              {
                $dt = $this->upload->data();
                $file = $dt['file_name'];
                $scanned_cer_palsy_cert_file = $dt['file_name'];
                $output_cer_palsy_cert1 = base_url() . "uploads/disability/" . $scanned_cer_palsy_cert_file;
              }
              else
              {
                $var_errors .= $this->upload->display_errors();
              }
            }
            else
            {
              $var_errors .= 'The filetype you are attempting to upload is not allowed';
            }
          }

          $benchmark_disability = $_POST['benchmark_disability'];
          $dob1 = $_POST["dob1"];
          $dob = str_replace('/', '-', $dob1);
          $dateOfBirth = date('Y-m-d', strtotime($dob));

          $doj1 = $_POST["doj1"];
          $doj = str_replace('/', '-', $doj1);
          $dateOfJoin = date('Y-m-d', strtotime($doj));

          $firstname_post = $this->security->xss_clean(trim($this->input->post('firstname')));
          $middlename_post = $this->security->xss_clean(trim($this->input->post('middlename')));
          $lastname_post = $this->security->xss_clean(trim($this->input->post('lastname')));

          $ins_nameoncard = $firstname_post;
          if ($middlename_post != "")
          {
            $ins_nameoncard .= ' ' . $middlename_post;
          }
          if ($lastname_post != "")
          {
            $ins_nameoncard .= ' ' . $lastname_post;
          }

          if ($scannedphoto_file != '' && $idproof_file != '' && $scannedsignaturephoto_file != '' && $declaration_file != '')
          {
            $user_data = array(
              'firstname' => $_POST["firstname"],
              'sel_namesub' => $_POST["sel_namesub"],
              'addressline1' => $_POST["addressline1"],
              'addressline2' => $_POST["addressline2"],
              'addressline3' => $_POST["addressline3"],
              'addressline4' => $_POST["addressline4"],
              'city' => $_POST["city"],
              'code' => trim($_POST["code"]),
              'designation' => $_POST["designation"],
              'district' => substr($_POST["district"], 0, 30),
              'dob' => $dateOfBirth,
              'doj' => $dateOfJoin,
              'eduqual' => $_POST["eduqual"],
              'eduqual1' => $eduqual1,
              'eduqual2' => $eduqual2,
              'eduqual3' => $eduqual3,
              'email' => strtolower($_POST["email"]),
              'gender' => $_POST["gender"],
              'idNo' => '',
              'idproof' => '4',
              'institution' => trim($_POST["institutionworking"]),
              'lastname' => $_POST["lastname"],
              'middlename' => $_POST["middlename"],
              'mobile' => $_POST["mobile"],
              //'nameoncard' => $_POST["nameoncard"],
              'nameoncard' => $ins_nameoncard,
              'office' => $_POST["office"],
              'optedu' => $_POST["optedu"],
              'optnletter' => $_POST["optnletter"],
              'phone' => $_POST["phone"],
              'pincode' => $_POST["pincode"],
              'state' => $_POST["state"],
              'stdcode' => $_POST["stdcode"],
              'scannedphoto' => $outputphoto1,
              'scannedsignaturephoto' => $outputsign1,
              'idproofphoto' => $outputidproof1,
              'declarationform' => $declaration_form,
              'photoname' => $scannedphoto_file,
              'signname' => $scannedsignaturephoto_file,
              'idname' => $idproof_file,
              'declaration' => $declaration_file,
              'aadhar_card' => $_POST['aadhar_card'],
              'addressline1_pr' => $_POST["addressline1_pr"], //later changes
              'addressline2_pr' => $_POST["addressline2_pr"],
              'addressline3_pr' => $_POST["addressline3_pr"],
              'addressline4_pr' => $_POST["addressline4_pr"],
              'city_pr' => $_POST["city_pr"],
              'district_pr' => substr($_POST["district_pr"], 0, 30),
              'pincode_pr' => $_POST["pincode_pr"],
              'state_pr' => $_POST["state_pr"],
              'bank_emp_id' => $this->input->post('bank_emp_id'),
              'benchmark_disability' => $benchmark_disability,
              'scanned_vis_imp_cert' => $output_vis_imp_cert1,
              'vis_imp_cert_name' => $scanned_vis_imp_cert_file,
              'scanned_orth_han_cert' => $output_orth_han_cert1,
              'orth_han_cert_name' => $scanned_orth_han_cert_file,
              'scanned_cer_palsy_cert' => $output_cer_palsy_cert1,
              'cer_palsy_cert_name' => $scanned_cer_palsy_cert_file,
              'visually_impaired' => $_POST["visually_impaired"],
              'orthopedically_handicapped' => $_POST["orthopedically_handicapped"],
              'cerebral_palsy' => $_POST["cerebral_palsy"], //
            );
            $this->session->set_userdata('enduserinfo', $user_data);

            $this->form_validation->set_message('error', "");
            redirect(base_url() . 'register/preview');
          }
          else
          {
            $var_errors = str_replace("<p>", "<span>", $var_errors);
            $var_errors = str_replace("</p>", "</span><br>", $var_errors);
          }
        }
      }

      $undergraduate = $this->master_model->getRecords('qualification', array('type' => 'UG'));
      $graduate      = $this->master_model->getRecords('qualification', array('type' => 'GR'));
      $postgraduate  = $this->master_model->getRecords('qualification', array('type' => 'PG'));

        $instiarray = array(4, 5, 8, 21, 30, 31, 47, 48, 57, 96, 129, 143, 160, 171, 175, 179, 187, 192, 341, 380, 397, 398, 409, 501, 628, 678, 722, 725, 755, 774, 828, 890, 912, 952, 968, 1010, 1012, 1185, 1186, 1344, 1369, 1454, 1484, 1665, 27518, 620, 627, 668, 911, 764, 793, 898, 939, 946, 1449, 1456, 1458, 1459, 1460, 1464, 1465, 1469, 1470, 1471, 1472, 1476, 1487, 1490, 1491, 1497, 1506, 1507, 1511, 1513, 1522, 1525, 1526, 1527, 1528, 1530, 1538, 1539, 1540, 1541, 1549, 1567, 1570, 1571, 1573, 1574, 1575, 1576, 1581, 1584, 1587, 1589, 1591, 1592, 1593, 1594, 1598, 1602, 1607, 1608, 1609, 1612, 1616, 1617, 1620, 1625, 1626, 1627, 1628, 1629, 1630, 1635, 1643, 1644, 1646, 1648, 1652, 1654, 1656, 1657, 1658, 1660, 1661, 1663, 1669, 1679, 1680, 1687, 1688, 1690, 1691, 1692, 1699, 1708, 1709, 1714, 1720, 1721, 1723, 1724, 1725, 1727, 1730, 1731, 1740, 1742, 1743, 1755, 1758, 1760, 1767, 1769, 1773, 1774, 1775, 1780, 1781, 1782, 1783, 1785, 1786, 1790, 1795, 1796, 1802, 1803, 1806, 1813, 1815, 1817, 1820, 1824, 1825, 1828, 1844, 1845, 1846, 1848, 1850, 1851, 1852, 1853, 1855, 1862, 1863, 1864, 1868, 1869, 1870, 1876, 1884, 1885, 1886, 1890, 1894, 1897, 1898, 1905, 1908, 1909, 1912, 1913, 1914, 1921, 1925, 1926, 1930, 1931, 1936, 1947, 1951, 1952, 1971, 1976, 1982, 1986, 1988, 1991, 1992, 1994, 1995, 1996, 1997, 2000, 2002, 2012, 2025, 2028, 2029, 2034, 2041, 2043, 2044, 2046, 2050, 2052, 2053, 2054, 2056, 2058, 2059, 2060, 2062, 2063, 2064, 2065, 2066, 2067, 2068, 2069, 2070, 2071, 2072, 2073, 2076, 2077, 2078, 2079, 2080, 2081, 2082, 2083, 2084, 2086, 2087, 2090, 2093, 2096, 2097, 2098, 2100, 2101, 2102, 2105, 2106, 2107, 2108, 2109, 2111, 2112, 2113, 2115, 2116, 2117, 2119, 2120, 2122, 2123, 2125, 2126, 2129, 2130, 2131, 2132, 2133, 2134, 2135, 2136, 2137, 2138, 2139, 2140, 2141, 2145, 2146, 2147, 2152, 2153, 2154, 2155, 2156, 2180, 2183, 2184, 2185, 2186, 2187, 2683, 2698, 41, 104, 543, 558, 570, 759, 856, 956, 976, 1052, 1081, 1227, 1237, 1261, 1267, 1283, 1288, 1297, 1300, 1302, 1307, 1310, 1317, 1322, 1340, 1347, 1351, 1362, 1379, 1390, 1403, 1404, 1406, 1416, 1420, 1424, 1429, 1500, 1501, 1606, 1621, 1650, 1659, 1675, 1712, 1746, 1906, 2033, 2635, 2703,2714, 2707,2729,2746, 687, 1203, 9993, 33, 65, 120, 126, 168, 237, 248, 298, 315, 321, 361, 367, 391, 460, 512, 517, 562,619, 650, 712, 732, 742, 847, 863, 870, 880, 912, 927, 930, 949, 962, 970, 989, 1005, 1016, 1018, 1037, 1038, 1040, 1046, 1053, 1059, 1095, 1104, 1116, 1122, 1168, 1170, 1182, 1183, 1188, 1191, 1199, 1201, 1216, 1220, 1222, 1224, 1240, 1246, 1253, 1254, 1276, 1282, 1284, 1287, 1299, 1311, 1316, 1325, 1326, 1330, 1336, 1346, 1360, 1373, 1376, 1377, 1380, 1383, 1384, 1391, 1393, 1394, 1397, 1405, 1407, 1408, 1421, 1422, 1423, 1426, 1439, 1440, 1447, 1463, 1483, 1710, 1711, 1793, 1827, 1907, 2091, 2104, 2161, 2167, 2181, 2637, 2679, 2681, 2688, 2689, 2696, 2709, 2737, 2751, 2753, 9991, 9992, 9995, 1516, 9996, 9999);
      $this->db->where_not_in('institude_id', $instiarray);
      $this->db->where('institution_master.institution_delete', '0');
      $institution_master = $this->master_model->getRecords('institution_master', '', '', array('name' => 'asc'));

      $this->db->where('state_master.state_delete', '0');
      $states = $this->master_model->getRecords('state_master');

      $this->db->where('designation_master.designation_delete', '0');
      $designation = $this->master_model->getRecords('designation_master');

      $this->db->where('id', 4);
      $this->db->or_where('id', 8);
      $idtype_master = $this->master_model->getRecords('idtype_master');
      //echo $this->db->last_query();
      //exit;

      $this->load->model('Captcha_model');
      $captcha_img = $this->Captcha_model->generate_captcha_img('regcaptcha');

      //$calendar = get_calendar_input();
      if ($flag == 0)
      {
        $data = array('middle_content' => 'cookie_msg');
        $this->load->view('common_view_fullwidth', $data);
      }
      else
      {
        $data = array('middle_content' => 'register', 'states' => $states, 'undergraduate' => $undergraduate, 'graduate' => $graduate, 'postgraduate' => $postgraduate, 'institution_master' => $institution_master, 'designation' => $designation, 'image' => $captcha_img, 'idtype_master' => $idtype_master, 'var_errors' => $var_errors);
        $this->load->view('common_view_fullwidth', $data);
      }
    }

// callback function to validate addressline 1
    public function address1($addressline1)
    {
        //( ! preg_match("/^(?:[A-Za-z0-9]+)(?:[A-Za-z0-9 \~\,\!\@\#\$\%\&\*\^\(\)\-\=\|\\\:\;\"\'\.\<\>\\?\/]*)$/", $addressline1)) ? FALSE : TRUE;
        if (!preg_match('/^[a-z0-9 .,-]+$/i', $addressline1)) {
            $this->form_validation->set_message('address1', "Please enter valid addressline1");
            return false;
        } else {
            return true;
        }
    }
    //callback to validate photo
    public function scannedphoto_upload()
    {
        if ($_FILES['scannedphoto']['size'] != 0) {
            return true;
        } else {
            $this->form_validation->set_message('scannedphoto_upload', "No Scanned Photograph file selected");
            return false;
        }
    }

    //callback to validate scannedsignaturephoto
    public function scannedsignaturephoto_upload()
    {
        if ($_FILES['scannedsignaturephoto']['size'] != 0) {
            return true;
        } else {
            $this->form_validation->set_message('scannedsignaturephoto_upload', "No  Scanned Signature file selected");
            return false;
        }
    }

    //callback to validate idproofphoto
    public function idproofphoto_upload()
    {
        if ($_FILES['idproofphoto']['size'] != 0) {
            return true;
        } else {
            $this->form_validation->set_message('idproofphoto_upload', "No Id proof file selected");
            return false;
        }
    }

    //callback to validate declarationform by pratibha borse
    public function declarationform_upload()
    {
        if ($_FILES['declarationform']['size'] != 0) {
            return true;
        } else {
            $this->form_validation->set_message('idproofphoto_upload', "No declaration file selected");
            return false;
        }
    }

    //call back for e-mail duplication
    public function check_emailduplication($email)
    {
        if ($email != "") {
            $where = "( registrationtype='O' OR registrationtype='A' OR registrationtype='F')";
            $this->db->where($where);
            $prev_count = $this->master_model->getRecordCount('member_registration', array('email' => $email, 'isactive' => '1'));
            //echo $this->db->last_query();
            if ($prev_count == 0) {
                return true;
            } else {
                $user_info        = $this->master_model->getRecords('member_registration', array('email' => $email), 'regnumber,firstname,middlename,lastname');
                $username         = $user_info[0]['firstname'] . ' ' . $user_info[0]['middlename'] . ' ' . $user_info[0]['lastname'];
                $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
                $str              = 'The entered email ID already exist for membership / registration number ' . $user_info[0]['regnumber'] . ' , ' . $userfinalstrname . '';

                $this->form_validation->set_message('check_emailduplication', $str);
                return false;
            }
        } else {
            return false;
        }
    }

    //call back for mobile duplication
    public function check_mobileduplication($mobile)
    {
        if ($mobile != "") {
            $where = "( registrationtype='O' OR registrationtype='A' OR registrationtype='F')";
            $this->db->where($where);
            $prev_count = $this->master_model->getRecordCount('member_registration', array('mobile' => $mobile, 'isactive' => '1'));
            //echo $this->db->last_query();
            if ($prev_count == 0) {
                return true;
            } else {
                $user_info        = $this->master_model->getRecords('member_registration', array('mobile' => $mobile), 'regnumber,firstname,middlename,lastname');
                $username         = $user_info[0]['firstname'] . ' ' . $user_info[0]['middlename'] . ' ' . $user_info[0]['lastname'];
                $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
                $str              = 'The entered  mobile no already exist for membership / registration number ' . $user_info[0]['regnumber'] . ' , ' . $userfinalstrname . '';
                $this->form_validation->set_message('check_mobileduplication', $str);
                return false;
            }
        } else {
            return false;
        }
    }

    //START : ADDED BY SAGAR & ANIL ON 2024-08-27. SERVER SIDE VALIDATION TO CHECK THE EMAIL & MOBILE IS VERIFIED CORRECTLY OR NOT
    function check_email_mobile_otp_verification($str='', $type='')
    {
      $flag = '';
      $message = 'Please verify the email or mobile';

      if($type != '' && ($type == 'email' || $type == 'mobile'))
      {
        $this->db->where_in('otp_type', array(3,4));
        $this->db->limit(1);
        $otp_data = $this->master_model->getRecords('member_login_otp', array($type => $str), 'email, otp, is_validate, created_on, DATE(otp_expired_on) AS OtpExpiryDate', array('otp_id'=>'DESC'));
        if(count($otp_data) > 0)
        {
          if($otp_data[0]['is_validate'] == '1' && $otp_data[0]['OtpExpiryDate'] >= date('Y-m-d')) { $flag = 'success'; }
          else { $message = 'The OTP is not verified for '.$type.' '.$str; }
        }
      }
      
      if($flag == 'success') { return true; }
      else
      {
        $this->form_validation->set_message('check_email_mobile_otp_verification',$message);
        return false;
      }
    }//END : ADDED BY SAGAR & ANIL ON 2024-08-27. SERVER SIDE VALIDATION TO CHECK THE EMAIL & MOBILE IS VERIFIED CORRECTLY OR NOT

    //call back for checkpin
    public function check_checkpin($pincode, $statecode)
    {
        return true;
        //exit;
        if ($statecode != "" && $pincode != '') {
            //$statecode = mysqli_real_escape_string($this->db->conn_id,$statecode);
            //$pincode = mysqli_real_escape_string($this->db->conn_id,$pincode);

            $this->db->where("$pincode BETWEEN start_pin AND end_pin");
            $prev_count = $this->master_model->getRecordCount('state_master', array('state_code' => $statecode));
            //echo $this->db->last_query();
            if ($prev_count == 0) {
                $str = 'Please enter Valid Pincode';
                $this->form_validation->set_message('check_checkpin', $str);
                return false;} else {
                $this->form_validation->set_message('error', "");
            }

            {return true;}
        } else {
            $str = 'Pincode/State field is required.';
            $this->form_validation->set_message('check_checkpin', $str);
            return false;
        }
    }

    public function check_checkpin_permanant($pincode, $statecode)
    {

        if ($statecode != "" && $pincode != '') {
            //$statecode = mysqli_real_escape_string($this->db->conn_id,$statecode);
            //$pincode = mysqli_real_escape_string($this->db->conn_id,$pincode);
            $this->db->where("$pincode BETWEEN start_pin AND end_pin");
            $prev_count = $this->master_model->getRecordCount('state_master', array('state_code' => $statecode));
            //echo $this->db->last_query();
            if ($prev_count == 0) {
                $str = 'Please enter Valid Pincode';
                $this->form_validation->set_message('check_checkpin_permanant', $str);
                return false;} else {
                $this->form_validation->set_message('error', "");
            }

            {return true;}
        } else {
            $str = 'Pincode/State field is required.';
            $this->form_validation->set_message('check_checkpin_permanant', $str);
            return false;
        }
    }

    //check aadhar card
    public function check_aadhar($aadhar_card)
    {
        if ($aadhar_card != "") {
            $where = "( registrationtype='O' OR registrationtype='A' OR registrationtype='F')";
            $this->db->where($where);
            //$this->db->where('registrationtype !=','DB');
            $prev_count = $this->master_model->getRecordCount('member_registration', array('aadhar_card' => $aadhar_card, 'isactive' => '1'));
            //echo $this->db->last_query();
            //exit;
            if ($prev_count == 0) {
                return true;
            } else {
                $str = 'The entered Aadhar card number already exist';
                $this->form_validation->set_message('check_aadhar', $str);
                return false;
            }
        } else {
            return false;
        }
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

    public function date_view()
    {
        $undergraduate = $this->master_model->getRecords('qualification', array('type' => 'UG'));
        $graduate      = $this->master_model->getRecords('qualification', array('type' => 'GR'));
        $postgraduate  = $this->master_model->getRecords('qualification', array('type' => 'PG'));

        $this->db->where('institution_master.institution_delete', '0');
        $institution_master = $this->master_model->getRecords('institution_master');

        $this->db->where('state_master.state_delete', '0');
        $states = $this->master_model->getRecords('state_master');

        $this->db->where('designation_master.designation_delete', '0');
        $designation = $this->master_model->getRecords('designation_master');

        $this->db->not_like('name', 'college');
        $idtype_master = $this->master_model->getRecords('idtype_master');

        /*$this->load->helper('captcha');
        $vals = array(
        'img_path' => './uploads/applications/',
        'img_url' => base_url().'uploads/applications/',
        );
        $cap = create_captcha($vals);
        $data['image'] = $cap['image'];
        $data['code']=$cap['word'];
        $this->session->set_userdata('regcaptcha', $cap['word']);*/

        $this->load->model('Captcha_model');
        $captcha_img = $this->Captcha_model->generate_captcha_img('regcaptcha');

        $calendar = get_calendar_input();
        //print_r($calendar);
        //exit;

        $data = array('middle_content' => 'register1', 'states' => $states, 'undergraduate' => $undergraduate, 'graduate' => $graduate, 'postgraduate' => $postgraduate, 'institution_master' => $institution_master, 'designation' => $designation, 'image' => $captcha_img, 'idtype_master' => $idtype_master, 'calendar' => $calendar);
        $this->load->view('common_view_fullwidth', $data);
    }

    public function addmember()
    {
        $this->load->helper('update_image_name_helper');
        $aadhar_card = '';
        if (!$this->session->userdata['enduserinfo']) {
            redirect(base_url());
        }
        //check email,mobile duplication on the same time from different browser!!
        $endTime    = date("H:i:s");
        $start_time = date("H:i:s", strtotime("-20 minutes", strtotime($endTime)));
        $this->db->where('Time(createdon) BETWEEN "' . $start_time . '" and "' . $endTime . '"');
        $this->db->where('email', $this->session->userdata['enduserinfo']['email']);
        $this->db->or_where('mobile', $this->session->userdata['enduserinfo']['mobile']);
        $check_duplication = $this->master_model->getRecords('member_registration', array('isactive' => 0));

        if (count($check_duplication) > 0) {
            redirect(base_url() . 'Register/cookie_msg');
        }
        //$last_id=$this->master_model->getRecords('member_registration','','regid',array('regid'=>'DESC'),'',1);
        /*if(count($last_id) > 0)
        {
        $last_count = $last_id[0]['regid'];
        $last_count = str_pad($last_count, 7, '0', STR_PAD_LEFT);
        $randomNumber=mt_rand(0,9999);
        $applicationNo = date('Y').$randomNumber.$last_count;
        }
        else
        {
        $last_count = '0';
        $last_count = str_pad($last_count, 7, '0', STR_PAD_LEFT);
        $randomNumber=mt_rand(0,9999);
        $applicationNo = date('Y').$randomNumber.$last_count;
        }    */
        $password = $this->generate_random_password();
        include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
        $key = $this->config->item('pass_key');
        $aes = new CryptAES();
        $aes->set_key(base64_decode($key));
        $aes->require_pkcs5();
        $encPass = $aes->encrypt($password);
        // $encPass = $aes->encrypt($pass);
        // $decData = $aes->decrypt($encPass);
        //if(isset($_POST['btnSubmit']))
        //{

        $image_error_flag = 0;

        $scannedphoto_file = $this->session->userdata['enduserinfo']['photoname'];
        $img_response      = check_files_exist('./uploads/photograph/' . $scannedphoto_file); //update_image_name_helper.php
        if ($img_response['flag'] != 'success') {$image_error_flag = 1;}

        $scannedsignaturephoto_file = $this->session->userdata['enduserinfo']['signname'];
        $img_response               = check_files_exist('./uploads/scansignature/' . $scannedsignaturephoto_file); //update_image_name_helper.php
        if ($img_response['flag'] != 'success') {$image_error_flag = 1;}

        $idproofphoto_file = $this->session->userdata['enduserinfo']['idname'];
        $img_response      = check_files_exist('./uploads/idproof/' . $idproofphoto_file); //update_image_name_helper.php
        if ($img_response['flag'] != 'success') {$image_error_flag = 1;}

        $declarationphoto_file = $this->session->userdata['enduserinfo']['declaration'];
        $img_response          = check_files_exist('./uploads/declaration/' . $declarationphoto_file); //update_image_name_helper.php
        if ($img_response['flag'] != 'success') {$image_error_flag = 1;}
        if ($image_error_flag == 1) {
            $this->session->set_flashdata('error', 'Please upload valid image(s)');
            redirect(base_url() . 'Register/member/');
        }

        $sel_namesub  = $this->session->userdata['enduserinfo']['sel_namesub'];
        $firstname    = strtoupper($this->session->userdata['enduserinfo']['firstname']);
        $middlename   = strtoupper($this->session->userdata['enduserinfo']['middlename']);
        $lastname     = strtoupper($this->session->userdata['enduserinfo']['lastname']);
        $nameoncard   = strtoupper($this->session->userdata['enduserinfo']['nameoncard']);
        $addressline1 = strtoupper($this->session->userdata['enduserinfo']['addressline1']);
        $addressline2 = strtoupper($this->session->userdata['enduserinfo']['addressline2']);
        $addressline3 = strtoupper($this->session->userdata['enduserinfo']['addressline3']);
        $addressline4 = strtoupper($this->session->userdata['enduserinfo']['addressline4']);
        $district     = strtoupper($this->session->userdata['enduserinfo']['district']);
        $nationality  = strtoupper($this->session->userdata['enduserinfo']['city']);
        $state        = $this->session->userdata['enduserinfo']['state'];
        $pincode      = $this->session->userdata['enduserinfo']['pincode'];
        $dob          = $this->session->userdata['enduserinfo']['dob'];
        $gender       = $this->session->userdata['enduserinfo']['gender'];
        $optedu       = $this->session->userdata['enduserinfo']['optedu'];

        //=================later changes by tejasvi===============
        $addressline1_pr = strtoupper($this->session->userdata['enduserinfo']['addressline1_pr']);
        $addressline2_pr = strtoupper($this->session->userdata['enduserinfo']['addressline2_pr']);
        $addressline3_pr = strtoupper($this->session->userdata['enduserinfo']['addressline3_pr']);
        $addressline4_pr = strtoupper($this->session->userdata['enduserinfo']['addressline4_pr']);
        $district_Pr     = strtoupper($this->session->userdata['enduserinfo']['district_pr']);
        $city_pr         = strtoupper($this->session->userdata['enduserinfo']['city_pr']);
        $state_pr        = $this->session->userdata['enduserinfo']['state_pr'];
        $pincode_pr      = $this->session->userdata['enduserinfo']['pincode_pr'];
        //=========================================================

        if ($optedu == 'U') {
            $specify_qualification = $this->session->userdata['enduserinfo']['eduqual1'];
        } elseif ($optedu == 'G') {
            $specify_qualification = $this->session->userdata['enduserinfo']['eduqual2'];
        } else if ($optedu == 'P') {
            $specify_qualification = $this->session->userdata['enduserinfo']['eduqual3'];
        }
        $institutionworking = $this->session->userdata['enduserinfo']['institution'];
        $office             = strtoupper($this->session->userdata['enduserinfo']['office']);
        $designation        = $this->session->userdata['enduserinfo']['designation'];
        $doj                = $this->session->userdata['enduserinfo']['doj'];
        $email              = $this->session->userdata['enduserinfo']['email'];
        $stdcode            = $this->session->userdata['enduserinfo']['stdcode'];
        $phone              = $this->session->userdata['enduserinfo']['phone'];
        $mobile             = $this->session->userdata['enduserinfo']['mobile'];
        $idproof            = $this->session->userdata['enduserinfo']['idproof'];
        $idNo               = $this->session->userdata['enduserinfo']['idNo'];
        $aadhar_card        = $this->session->userdata['enduserinfo']['aadhar_card'];
        $optnletter         = $this->session->userdata['enduserinfo']['optnletter'];
        $bank_emp_id        = $this->session->userdata['enduserinfo']['bank_emp_id'];
        /* Benchmark */
        $benchmark_disability        = $this->session->userdata['enduserinfo']['benchmark_disability'];
        $scanned_vis_imp_cert_file   = $this->session->userdata['enduserinfo']['vis_imp_cert_name'];
        $scanned_orth_han_cert_file  = $this->session->userdata['enduserinfo']['orth_han_cert_name'];
        $scanned_cer_palsy_cert_file = $this->session->userdata['enduserinfo']['cer_palsy_cert_name'];

        $visually_impaired          = $this->session->userdata['enduserinfo']['visually_impaired'];
        $orthopedically_handicapped = $this->session->userdata['enduserinfo']['orthopedically_handicapped'];
        $cerebral_palsy             = $this->session->userdata['enduserinfo']['cerebral_palsy'];
        if ($benchmark_disability == 'N') {
            $scanned_vis_imp_cert_file   = '';
            $scanned_orth_han_cert_file  = '';
            $scanned_cer_palsy_cert_file = '';
            $visually_impaired           = '';
            $orthopedically_handicapped  = '';
            $cerebral_palsy              = '';
        }
        if ($visually_impaired == 'N') {
            $scanned_vis_imp_cert_file = '';
        }
        if ($orthopedically_handicapped == 'N') {
            $scanned_orth_han_cert_file = '';
        }
        if ($cerebral_palsy == 'N') {
            $scanned_cer_palsy_cert_file = '';
        }

        if ($benchmark_disability == 'Y') {
            if ($visually_impaired == 'N' && $orthopedically_handicapped == 'N' && $cerebral_palsy == 'N') {
                $benchmark_disability = 'N';
            }
        }

        $email_verification_flag = $this->check_email_mobile_otp_verification($email,'email');
        if($email_verification_flag == false)
        {
          $this->session->set_flashdata('error','The OTP is not verified for email '.$email);  
          redirect(site_url('register/preview'));
        }

        $mobile_verification_flag = $this->check_email_mobile_otp_verification($mobile,'mobile');
        if($mobile_verification_flag == false)
        {
          $this->session->set_flashdata('error','The OTP is not verified for mobile '.$mobile); 
          redirect(site_url('register/preview'));
        }


        $insert_info = array(
            'usrpassword'                => $encPass,
            'namesub'                    => $sel_namesub,
            'firstname'                  => $firstname,
            'middlename'                 => $middlename,
            'lastname'                   => $lastname,
            'displayname'                => $nameoncard,
            'address1'                   => $addressline1,
            'address2'                   => $addressline2,
            'address3'                   => $addressline3,
            'address4'                   => $addressline4,
            'district'                   => $district,
            'city'                       => $nationality,
            'state'                      => $state,
            'pincode'                    => $pincode,
            'dateofbirth'                => date('Y-m-d', strtotime($dob)),
            'gender'                     => $gender,
            'qualification'              => $optedu,
            'specify_qualification'      => $specify_qualification,
            'associatedinstitute'        => $institutionworking,
            'office'                     => $office,
            'designation'                => $designation,
            'dateofjoin'                 => date('Y-m-d', strtotime($doj)),
            'email'                      => $email,
            'registrationtype'           => 'O',
            'stdcode'                    => $stdcode,
            'office_phone'               => $phone,
            'mobile'                     => $mobile,
            'scannedphoto'               => $scannedphoto_file,
            'scannedsignaturephoto'      => $scannedsignaturephoto_file,
            'idproof'                    => $idproof,
            'declaration'                => $declarationphoto_file,
            'idNo'                       => $idNo,
            'optnletter'                 => $optnletter,
            'idproofphoto'               => $idproofphoto_file,
            'createdon'                  => date('Y-m-d H:i:s'),
            'aadhar_card'                => $aadhar_card,
            'id_proof_flag'              => $idproof,
            'address1_pr'                => $addressline1_pr, //later changes
            'address2_pr'                => $addressline2_pr,
            'address3_pr'                => $addressline3_pr,
            'address4_pr'                => $addressline4_pr,
            'district_pr'                => $district_Pr,
            'city_pr'                    => $city_pr,
            'state_pr'                   => $state_pr,
            'pincode_pr'                 => $pincode_pr,
            'bank_emp_id'                => $bank_emp_id,
            'benchmark_disability'       => $benchmark_disability,
            'vis_imp_cert_img'           => $scanned_vis_imp_cert_file,
            'orth_han_cert_img'          => $scanned_orth_han_cert_file,
            'cer_palsy_cert_img'         => $scanned_cer_palsy_cert_file,
            'visually_impaired'          => $visually_impaired,
            'orthopedically_handicapped' => $orthopedically_handicapped,
            'cerebral_palsy'             => $cerebral_palsy,
        );

        if ($last_id = $this->master_model->insertRecord('member_registration', $insert_info, true)) {
            $add_img_data['reg_id']      = $last_id;
            $add_img_data['photo']       = convert_img_into_base64(base_url() . 'uploads/photograph/' . $scannedphoto_file);
            $add_img_data['sign']        = convert_img_into_base64(base_url() . 'uploads/scansignature/' . $scannedsignaturephoto_file);
            $add_img_data['idproof']     = convert_img_into_base64(base_url() . 'uploads/idproof/' . $idproofphoto_file);
            $add_img_data['declaration'] = convert_img_into_base64(base_url() . 'uploads/declaration/' . $declarationphoto_file);
            $add_img_data['created_on']  = date('Y-m-d H:i:s');
            // Below code commented by Vishal to reduce database usage - 2023-04-06
            // $this->master_model->insertRecord('tbl_member_image_base64', $add_img_data, true);

            $log_title   = "Ordinory member insert array :" . $last_id;
            $log_message = serialize($insert_info);
            $rId         = $last_id;
            $regNo       = $last_id;
            storedUserActivity($log_title, $log_message, $rId, $regNo);
            //print_r($state_pr);exit;
            // Renaming the previously uploaded file with Reg Num inserted in database
            $upd_files = array();
            /*$photo_file = 'p_'.$last_id.'.jpg';
            $sign_file = 's_'.$last_id.'.jpg';
            $proof_file = 'pr_'.$last_id.'.jpg';

            if(@ rename("./uploads/photograph/".$scannedphoto_file,"./uploads/photograph/".$photo_file))
            {    $upd_files['scannedphoto'] = $photo_file;    }

            if(@ rename("./uploads/scansignature/".$scannedsignaturephoto_file,"./uploads/scansignature/".$sign_file))
            {    $upd_files['scannedsignaturephoto'] = $sign_file;    }

            if(@ rename("./uploads/idproof/".$idproofphoto_file,"./uploads/idproof/".$proof_file))
            {    $upd_files['idproofphoto'] = $proof_file;    }

            if(count($upd_files)>0)
            {
            $this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$last_id));
            }*/
            logactivity($log_title = "Member user registration ", $log_message = serialize($insert_info));

            $userarr = array('regno' => $last_id,
                'password'               => $password,
                'email'                  => $email);
            $this->session->set_userdata('memberdata', $userarr);

            redirect(base_url() . "Register/make_payment");

        } else {
            $userarr = array('regno' => '',
                'password'               => '',
                'email'                  => '');
            $this->session->set_userdata('memberdata', $userarr);
            //$this->make_payment();
            $this->session->set_flashdata('error', 'Error while during registration.please try again!');
            redirect(base_url());
        }

        //}

    }
    //validate captcha
    ##---------check captcha userlogin (prafull)-----------##
    public function ajax_check_captcha()
    {
        $code = $_POST['code'];
        // check if captcha is set -
        if ($code == '' || $_SESSION["regcaptcha"] != $code) {
            $this->form_validation->set_message('ajax_check_captcha', 'Invalid %s.');
            //$this->session->set_userdata("regcaptcha", rand(1, 100000));
            echo 'false';
        } else if ($_SESSION["regcaptcha"] == $code) {
            //$this->session->unset_userdata("regcaptcha");
            // $this->session->set_userdata("mycaptcha", rand(1,100000));
            echo 'true';
        }
    }

    // reload captcha functionality
    public function generatecaptchaajax()
    {
        /*$this->load->helper('captcha');
        $this->session->unset_userdata("regcaptcha");
        $this->session->set_userdata("regcaptcha", rand(1, 100000));
        $vals = array('img_path' => './uploads/applications/','img_url' => base_url().'uploads/applications/',
        );
        $cap = create_captcha($vals);
        $data = $cap['image'];
        $_SESSION["regcaptcha"] = $cap['word'];
        echo $data;*/

        $this->load->model('Captcha_model');
        echo $captcha_img = $this->Captcha_model->generate_captcha_img('regcaptcha');

    }

    //Thank you message to end user
    public function acknowledge()
    {      
      /* $data = array();
      if ($this->session->userdata('memberdata') == '') {
          redirect(base_url());
      }
      if ($this->session->userdata('enduserinfo')) {
          $this->session->unset_userdata('enduserinfo');
          $this->session->unset_userdata('memberdata');
      }

      $user_info = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata['memberdata']['regno']), 'regnumber'); */
      /*
      $user_data=array('firstname'=>'',
      'sel_namesub'=>'',
      'addressline1'=>'',
      'addressline2'=>'',
      'addressline3'=>'',
      'addressline4'=>'',
      'city'=>'',
      'code'=>'',
      'designation'=>'',
      'district'=>'',
      'dob'=>'',
      'doj'=>'',
      'eduqual'=>'',
      'eduqual1'=>'',
      'eduqual2'=>'',
      'eduqual3'=>'',
      'email'=>'',
      'gender'=>'',
      'idNo'=>'',
      'idproof'=>'',
      'institution'=>'',
      'lastname'=>'',
      'middlename'=>'',
      'mobile'=>'',
      'nameoncard'=>'',
      'office'=>'',
      'optedu'=>'',
      'optnletter'=>'',
      'phone'=>'',
      'pincode'=>'',
      'state'=>'',
      'stdcode'=>'',
      'scannedphoto'=>'',
      'scannedsignaturephoto'=>'',
      'idproofphoto'=>'',
      'photoname'=>'',
      'signname'=>'',
      'idname'=>'',
      'aadhar_card'=>'',
      'addressline1_pr'=>'',
      'addressline2_pr'=>'',
      'addressline3_pr'=>'',
      'addressline4_pr'=>'',
      'district_pr'=>'',
      'city_pr'=>'',
      'state_pr'=>'',
      'pincode_pr'=>'');
      $this->session->unset_userdata('enduserinfo',$user_data);
        */
      $data = array();
      if (isset($_SESSION['session_regid']) && $_SESSION['session_regid'] != '') 
      {
        $user_info = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('session_regid')), 'regnumber, usrpassword, createdon');

        $time_after_5min = date('Y-m-d H:i:s', strtotime("+5 min", strtotime($user_info[0]['createdon'])));
        if(date('Y-m-d H:i:s') > $time_after_5min) { $_SESSION['session_regid'] = ''; redirect(base_url()); }

        include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$key = $this->config->item('pass_key');
        $aes = new CryptAES();
        $aes->set_key(base64_decode($key));
        $aes->require_pkcs5();
        $decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));
        
        $data = array('middle_content' => 'thankyou', 'application_number', 'application_number' => $user_info[0]['regnumber'], 'password' => $decpass, 'user_info'=>$user_info);
        $this->load->view('common_view_fullwidth', $data);          
      }
      else
      {
        redirect(base_url());
      }        
    }

    //print user Register profile (Prafull)
    public function printUser()
    {

        $qualification = array();
        $this->db->select('member_registration.*,institution_master.name,state_master.state_name,designation_master.dname');
        $this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute');
        $this->db->join('state_master', 'state_master.state_code=member_registration.state');
        $this->db->join('designation_master', 'designation_master.dcode=member_registration.designation');
        $this->db->where('institution_master.institution_delete', '0');
        $this->db->where('state_master.state_delete', '0');
        $this->db->where('designation_master.designation_delete', '0');
        $user_info = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata['memberdata']['regno'], 'isactive' => '1'));
        if (count($user_info) <= 0) {
            redirect(base_url());
        }

        if ($user_info[0]['qualification'] == 'U') {
            $this->db->where('qid', $user_info[0]['specify_qualification']);
            $qualification = $this->master_model->getRecords('qualification', array('type' => 'UG'), 'name as qname', '', '', 1);
        } else if ($user_info[0]['qualification'] == 'G') {
            $this->db->where('qid', $user_info[0]['specify_qualification']);
            $qualification = $this->master_model->getRecords('qualification', array('type' => 'GR'), 'name as qname', '', '', 1);
        } else if ($user_info[0]['qualification'] == 'P') {
            $this->db->where('qid', $user_info[0]['specify_qualification']);
            $qualification = $this->master_model->getRecords('qualification', array('type' => 'PG'), 'name as qname', '', '', 1);
        }

        $this->db->where('id', $user_info[0]['idproof']);
        $idtype_master = $this->master_model->getRecords('idtype_master', '', 'name');
        $data          = array('middle_content' => 'print_member_profile', 'user_info' => $user_info, 'qualification' => $qualification, 'idtype_master' => $idtype_master);
        $this->load->view('common_view', $data);
    }

    //Download pdf(Prafull)
    public function pdf()
    {
      if (!isset($_SESSION['session_regid']) || $_SESSION['session_regid'] == '')  { redirect(base_url()); }
        $qualification = array();
        $this->db->select('member_registration.*,institution_master.name,state_master.state_name,designation_master.dname');
        $this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute');
        $this->db->join('state_master', 'state_master.state_code=member_registration.state');
        $this->db->join('designation_master', 'designation_master.dcode=member_registration.designation');
        $this->db->where('institution_master.institution_delete', '0');
        $this->db->where('state_master.state_delete', '0');
        $this->db->where('designation_master.designation_delete', '0');
        $user_info = $this->master_model->getRecords('member_registration', array('regid' => $_SESSION['session_regid'], 'isactive' => '1'));

        if (count($user_info) <= 0) {
            redirect(base_url());
        }
        if ($user_info[0]['qualification'] == 'U') {
            $this->db->where('qid', $user_info[0]['specify_qualification']);
            $qualification = $this->master_model->getRecords('qualification', array('type' => 'UG'), 'name as qname', '', '', 1);
        } else if ($user_info[0]['qualification'] == 'G') {
            $this->db->where('qid', $user_info[0]['specify_qualification']);
            $qualification = $this->master_model->getRecords('qualification', array('type' => 'GR'), 'name as qname', '', '', 1);
        } else if ($user_info[0]['qualification'] == 'P') {
            $this->db->where('qid', $user_info[0]['specify_qualification']);
            $qualification = $this->master_model->getRecords('qualification', array('type' => 'PG'), 'name as qname', '', '', 1);
        }
        $this->db->where('id', $user_info[0]['idproof']);
        $idtype_master = $this->master_model->getRecords('idtype_master', '', 'name');

        $username         = $user_info[0]['namesub'] . ' ' . $user_info[0]['firstname'] . ' ' . $user_info[0]['middlename'] . ' ' . $user_info[0]['lastname'];
        $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
        $userfinalstrname;
        if ($user_info[0]['gender'] == 'female') {$gender = 'Female';}
        if ($user_info[0]['gender'] == 'male') {$gender = 'Male';}
        if ($user_info[0]['qualification'] == 'U') {$memqualification = 'Under Graduate';}
        if ($user_info[0]['qualification'] == 'G') {$memqualification = 'Graduate';}
        if ($user_info[0]['qualification'] == 'P') {$memqualification = 'Post Graduate';}

        if ($user_info[0]['optnletter'] == 'Y') {$optnletter = 'Yes';}
        if ($user_info[0]['optnletter'] == 'N') {$optnletter = 'No';}

        include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
        $key = $this->config->item('pass_key');
        $aes = new CryptAES();
        $aes->set_key(base64_decode($key));
        $aes->require_pkcs5();
        $decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));
        if ($user_info[0]['address2'] != '') {
            $user_info[0]['address2'] = $user_info[0]['address2'] . '<br>';
        }
        if ($user_info[0]['address3'] != '') {
            $user_info[0]['address3'] = $user_info[0]['address3'] . '<br>';
        }
        if ($user_info[0]['address4'] != '') {
            $user_info[0]['address4'] = $user_info[0]['address4'] . '<br>';
        }
        $useradd = $user_info[0]['address1'] . $user_info[0]['address2'] . $user_info[0]['address3'] . $user_info[0]['address4'] . ',' . $user_info[0]['district'] . ',' . $user_info[0]['city'] . ',' . $user_info[0]['state_name'] . $user_info[0]['pincode'];
        $html    = '<table width="754" cellspacing="0" cellpadding="0" border="0" align="center" style=" background: #fff ;
    border: 1px solid #000; padding:25px;
  ">
<tbody><tr> <td colspan="4" align="left">&nbsp;</td> </tr>
<tr>
    <td colspan="4" align="center" height="25">
        <span id="1001a1" class="alert">
        </span>
    </td>
</tr>
<tr style="border-bottom:solid 1px #000;">
    <td colspan="4" height="1"><img src="' . base_url() . 'assets/images/logo1.png"></td>
</tr>

<tr>
    <td colspan="4">
    </hr>
    <table width="98%" cellspacing="2" cellpadding="2" border="0" align="center">
                    <tbody><tr>
            <td class="tablecontent2" width="51%">Membership No : </td>
            <td colspan="2" class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info[0]['regnumber'] . '</td>
            <td class="tablecontent" rowspan="4" valign="top">
            <img src="' . base_url() . 'uploads/photograph/' . $user_info[0]['scannedphoto'] . '" height="100" width="100" >
            </td>
        </tr>
                <tr>
            <td class="tablecontent2">Password :</td>
            <td colspan="2" class="tablecontent2" nowrap="nowrap">' . $decpass . ' </td>
        </tr>
        <tr>
            <td class="tablecontent2">Full Name :</td>
            <td colspan="2" class="tablecontent2" nowrap="nowrap">' . $userfinalstrname . '
            </td>
        </tr>
        <tr>
            <td class="tablecontent2">Name as to appear on Card :</td>
            <td colspan="2" class="tablecontent2" nowrap="nowrap">' . $user_info[0]['displayname'] . '</td>
        </tr>
        <tr>
            <td class="tablecontent2" width="51%">Office/Residential Address for communication :</td>
            <td colspan="3" class="tablecontent2" width="49%" nowrap="nowrap">
                ' . $useradd . '            </td>
        </tr>

        <tr>
            <td class="tablecontent2">Date of Birth :</td>
            <td colspan="3" class="tablecontent2" nowrap="nowrap">' . date('d-m-Y', strtotime($user_info[0]['dateofbirth'])) . '</td>
        </tr>
        <tr>
            <td class="tablecontent2">Gender :</td>
            <td colspan="3" class="tablecontent2" nowrap="nowrap">' . $gender . ' </td>
        </tr>

        <tr>
            <td class="tablecontent2">Qualification :</td>
            <td colspan="3" class="tablecontent2" nowrap="nowrap">' . $memqualification . ' </td>
        </tr>
        <tr>
            <td class="tablecontent2">Specify :</td>
            <td colspan="3" class="tablecontent2" nowrap="nowrap">' . $qualification[0]['qname'] . ' </td>
        </tr>
        <tr>
            <td class="tablecontent2">Bank/Institution working :</td>
            <td colspan="3" class="tablecontent2" nowrap="nowrap">' . $user_info[0]['name'] . ' </td>
        </tr>
        <tr>
            <td class="tablecontent2">Branch/Office :</td>
            <td colspan="3" class="tablecontent2" nowrap="nowrap">' . $user_info[0]['office'] . ' </td>
        </tr>
        <tr>
            <td class="tablecontent2">Designation :</td>
            <td colspan="3" class="tablecontent2" nowrap="nowrap">' . $user_info[0]['dname'] . ' </td>
        </tr>
        <tr>
            <td class="tablecontent2">Date of Joining :</td>
            <td colspan="3" class="tablecontent2" nowrap="nowrap">' . date('d-m-Y', strtotime($user_info[0]['dateofjoin'])) . ' </td>
        </tr>
        <tr>
            <td class="tablecontent2">Email :</td>
            <td colspan="3" class="tablecontent2" nowrap="nowrap">' . $user_info[0]['email'] . ' </td>
        </tr>


        <tr>
            <td class="tablecontent2">Mobile :</td>
            <td colspan="3" class="tablecontent2" nowrap="nowrap">' . $user_info[0]['mobile'] . ' </td>
        </tr>
        <tr>
            <td class="tablecontent2">ID Proof :</td>
            <td colspan="3" class="tablecontent2" nowrap="nowrap">' . $idtype_master[0]['name'] . '</td>
        </tr>
        <tr>
            <td class="tablecontent2">Declaration :</td>
            <td colspan="3" class="tablecontent2" nowrap="nowrap"><img src="' . base_url() . 'uploads/declaration/' . $user_info[0]['declaration'] . '" height="100" width="100"></td>
        </tr>
        <tr>
            <td class="tablecontent2">Aadhar Card Number :</td>
            <td colspan="3" class="tablecontent2" nowrap="nowrap">' . $user_info[0]['aadhar_card'] . '</td>
        </tr>
                <tr>
            <td class="tablecontent2">I agree to receive the Annual report from the Institute in a softcopy, at my registered email ID, in place of physical copy :</td>
            <td colspan="3" class="tablecontent2" nowrap="nowrap"> ' . $optnletter . ' </td>
        </tr>
        <tr>
            <td class="tablecontent2">ID Proof :</td>
            <td colspan="3" class="tablecontent2" nowrap="nowrap">  <img src="' . base_url() . 'uploads/idproof/' . $user_info[0]['idproofphoto'] . '"  height="180" width="100"></td>
        </tr>
        <tr>
            <td class="tablecontent2">Signature :</td>
            <td colspan="3" class="tablecontent2" nowrap="nowrap"><img src="' . base_url() . 'uploads/scansignature/' . $user_info[0]['scannedsignaturephoto'] . '" height="100" width="100"></td>
        </tr>
        <tr>
            <td class="tablecontent2">Date :</td>
            <td colspan="3" class="tablecontent2" nowrap="nowrap">
                ' . date('d-m-Y h:i:s A', strtotime($user_info[0]['createdon'])) . '        </td>
        </tr>
        </tbody></table>
    </td>
</tr>

</tbody></table>';
        //this the the PDF filename that user will get to download
        $pdfFilePath = 'iibf' . '.pdf';
        //load mPDF library
        $this->load->library('m_pdf');
        //actually, you can pass mPDF parameter on this load() function
        $pdf = $this->m_pdf->load();
        //$pdf->SetHTMLHeader($header);
        $pdf->SetHTMLHeader('');
        $pdf->SetHTMLFooter('');
        $stylesheet = '/*Table with outline Classes*/
                                 .tablecontent2 {
                                  background-color: #ffffff;
                                  bottom: 5px;
                                  color: #000000;
                                  font-family: Tahoma;
                                  font-size: 11px;
                                  font-weight: normal;
                                  height: 10px;
                                  left: 5px;
                                  padding: 5px;
                                  right: 5px;
                                  top: 5px;
                                }
                                .img{ width:100%; height:auto; padding:15px;}';
        header('Content-Type: application/pdf');
        header('Content-Description: inline; filename.pdf');
        $pdf->WriteHTML($stylesheet, 1);
        $pdf->WriteHTML($html, 2);
        $pdf->Output($pdfFilePath, 'D');

    }

/*    public function appNo()
{
$last_id=$this->master_model->getRecords('personal_info','','id',array('id'=>'DESC'),'',1);
if(count($last_id) > 0)
{
$last_count = $last_id[0]['id'];
$last_count = str_pad($last_count, 7, '0', STR_PAD_LEFT);
$timeStr = date('Ymds');
echo $applicationId = $timeStr.'-'.$last_count;
}
}*/

    /*public function setsession()
    {
    $outputphoto1=$outputsign1=$outputsign1='';

    $scannedphoto_file = '';
    $scannedsignaturephoto_file = '';
    $idproof_file = '';

    // ajax response -
    $resp = array('success' => 0, 'error' => 0, 'msg' => '');

    //$this->form_validation->set_rules('sel_namesub', 'Name Prefix', 'trim|required|xss_clean');
    //        $this->form_validation->set_rules('firstname', 'First Name', 'trim|required|xss_clean');
    //        $this->form_validation->set_rules('nameoncard', 'Name as to appear on Card', 'trim|required|xss_clean');
    //        $this->form_validation->set_rules('addressline1', 'Address line1', 'trim|required|xss_clean');
    //        $this->form_validation->set_rules('district', 'District', 'trim|required|xss_clean');
    //        $this->form_validation->set_rules('city', 'City', 'trim|required|xss_clean');
    //        $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean');
    //        $this->form_validation->set_rules('pincode', 'Pincode/Zipcode', 'trim|required|xss_clean');
    //        $this->form_validation->set_rules('dob1', 'Date of Birth', 'trim|required|xss_clean');
    //        $this->form_validation->set_rules('gender', 'Gender', 'trim|required|xss_clean');
    //        $this->form_validation->set_rules('optedu', 'Qualification', 'trim|required|xss_clean');
    //        //$this->form_validation->set_rules('eduqual', 'Please specify', 'trim|required|xss_clean');
    //        $this->form_validation->set_rules('institutionworking', 'Bank/Institution working', 'trim|required|xss_clean');
    //        $this->form_validation->set_rules('office', 'Branch/Office', 'trim|required|xss_clean');
    //        $this->form_validation->set_rules('designation', 'Designation', 'trim|required|xss_clean');
    //        $this->form_validation->set_rules('doj1', 'Date of joining Bank/Institution', 'trim|required|xss_clean');
    //        $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
    //        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|xss_clean');
    //        $this->form_validation->set_rules('idproof', 'Select Id Proof', 'trim|required|xss_clean');
    //        $this->form_validation->set_rules('idNo', 'ID No.', 'trim|required|xss_clean');
    //        $this->form_validation->set_rules('declaration1', 'I Accept', 'trim|required|xss_clean');

    // check if form validation fail -
    if($this->form_validation->run() == FALSE)
    {
    $var_errors = validation_errors();
    $var_errors = str_replace("<p>", "<span>", $var_errors);
    $var_errors = str_replace("</p>", "</span><br>", $var_errors);

    $resp = array('success' => 0, 'error' => 1, 'msg' => $var_errors);
    print json_encode($resp);
    die;
    }

    $scannedphoto_file=$scannedsignaturephoto_file=$idproof_file=$outputphoto1=$outputsign1=$outputsign1='';
    $this->session->unset_userdata('enduserinfo');
    $eduqual1=$eduqual2=$eduqual3='';
    if($_POST['optedu']=='U')
    {
    $eduqual1=$_POST["eduqual1"];
    }
    else if($_POST['optedu']=='G')
    {
    $eduqual2=$_POST["eduqual2"];
    }
    else if($_POST['optedu']=='P')
    {
    $eduqual3=$_POST["eduqual3"];
    }

    //$text =  ($_POST["photo"]    );
    //        //$text = str_replace("data:image/jpeg;base64,","",$text);
    //        $tmp_nm = rand(0,100);
    //        $name = "/home/tgdemo/public_html/tgpublic/iibf/uploads/photo_".$tmp_nm.".jpg";
    //        $name1 = "http://demo.teamgrowth.net/tgpublic/iibf/uploads/photo_".$tmp_nm.".jpg";
    //        file_put_contents($name, $text);

    $date=date('Y-m-d h:i:s');

    //Generate dynamic photo
    $input = $_POST["hiddenphoto"];

    if(isset($_FILES['scannedphoto']['name']) && $_FILES['scannedphoto']['name']!='')
    {
    $tmp_nm = strtotime($date).rand(0,100);
    $new_filename = 'photo_'.$tmp_nm;
    $photopath = "./uploads/photograph";
    $uploadData = upload_file('scannedphoto', $photopath, $new_filename,'','',TRUE);
    if($uploadData)
    {
    $scannedphoto_file = $uploadData['file_name'];
    $outputphoto1 = base_url()."uploads/photograph/".$scannedphoto_file;
    }
    }

    //$tmp_nm = strtotime($date).rand(0,100);
    //        $outputphoto = getcwd()."/uploads/photograph/photo_".$tmp_nm.".jpg";
    //        $outputphoto1 = base_url()."uploads/photograph/photo_".$tmp_nm.".jpg";
    //        file_put_contents($outputphoto, file_get_contents($input));

    //file_get_contents() function required in staggin/tgpublic server do not remove from
    //file_put_contents($outputphoto, ($input));

    // generate dynamic scan signature
    $inputsignature = $_POST["hiddenscansignature"];

    if(isset($_FILES['scannedsignaturephoto']['name']) && $_FILES['scannedsignaturephoto']['name']!='')
    {
    $tmp_signnm = strtotime($date).rand(0,100);
    $signaturepath = "./uploads/scansignature";
    $new_filename = 'sign_'.$tmp_signnm;
    $uploadData = upload_file('scannedsignaturephoto', $signaturepath, $new_filename,'','',TRUE);
    if($uploadData)
    {
    $scannedsignaturephoto_file = $uploadData['file_name'];
    $outputsign1 = base_url()."uploads/scansignature/".$scannedsignaturephoto_file;
    }
    }

    //$tmp_signnm = strtotime($date).rand(0,100);
    //        $outputsign = getcwd()."/uploads/scansignature/sign_".$tmp_signnm.".jpg";
    //        $outputsign1 = base_url()."uploads/scansignature/sign_".$tmp_signnm.".jpg";
    //        file_put_contents($outputsign, file_get_contents($inputsignature));

    // generate dynamic id proof
    $inputidproofphoto = $_POST["hiddenidproofphoto"];
    if(isset($_FILES['idproofphoto']['name']) && $_FILES['idproofphoto']['name']!='')
    {
    $tmp_inputidproof = strtotime($date).rand(0,100);
    $idproofpath = "./uploads/idproof";
    $new_filename = 'idproof_'.$tmp_inputidproof;
    $uploadData = upload_file('idproofphoto', $idproofpath, $new_filename,'','',TRUE);
    if($uploadData)
    {
    $idproof_file = $uploadData['file_name'];
    $outputidproof1 = base_url()."uploads/idproof/".$idproof_file;
    }
    }

    //$tmp_inputidproof = strtotime($date).rand(0,100);
    //        $outputidproof = getcwd()."/uploads/idproof/idproof_".$tmp_inputidproof.".jpg";
    //        $outputidproof1 = base_url()."uploads/idproof/idproof_".$tmp_inputidproof.".jpg";
    //        file_put_contents($outputidproof, file_get_contents($inputidproofphoto));

    $dob1= $_POST["dob1"];
    $dob = str_replace('/','-',$dob1);
    $dateOfBirth = date('Y-m-d',strtotime($dob));

    $doj1= $_POST["doj1"];
    $doj = str_replace('/','-',$doj1);
    $dateOfJoin = date('Y-m-d',strtotime($doj));

    if($scannedphoto_file!='' && $idproof_file!='' && $scannedsignaturephoto_file!='')
    {
    $user_data=array('firstname'=>substr($_POST["firstname"],0,30),
    'sel_namesub'=>$_POST["sel_namesub"],
    'addressline1'=>substr($_POST["addressline1"],0,30),
    'addressline2'=>substr($_POST["addressline2"],0,30),
    'addressline3'=>substr($_POST["addressline3"],0,30),
    'addressline4'=>substr($_POST["addressline4"],0,30),
    'city'=>substr($_POST["city"],0,30),
    'code'=>trim($_POST["code"]),
    'designation'=>$_POST["designation"],
    'district'=>substr($_POST["district"],0,30),
    'dob'    =>$dateOfBirth,
    'doj'=>$dateOfJoin,
    'eduqual'=>$_POST["eduqual"],
    'eduqual1'=>$eduqual1,
    'eduqual2'=>$eduqual2,
    'eduqual3'=>$eduqual3,
    'email'=>$_POST["email"],
    'gender'=>$_POST["gender"],
    'idNo'=>$_POST["idNo"],
    'idproof'=>'',
    'institution'=>trim($_POST["institutionworking"]),
    'lastname'=>$_POST["lastname"],
    'middlename'=>$_POST["middlename"],
    'mobile'=>$_POST["mobile"],
    'nameoncard'=>substr($_POST["nameoncard"],0,35),
    'office'=>$_POST["office"],
    'optedu'=>$_POST["optedu"],
    'optnletter'=>$_POST["optnletter"],
    'phone'=>$_POST["phone"],
    'pincode'=>$_POST["pincode"],
    'state'=>$_POST["state"],
    'stdcode'=>$_POST["stdcode"],
    'scannedphoto'=>$outputphoto1,
    'scannedsignaturephoto'=>$outputsign1,
    'idproofphoto'=>$outputidproof1,
    'photoname'=>$scannedphoto_file ,
    'signname'=>$scannedsignaturephoto_file ,
    'idname'=>$idproof_file );
    $this->session->set_userdata('enduserinfo',$user_data);
    redirect(base_url().'register/preview');
    echo true;
    }
    else
    {
    echo false;

    //$resp = array('success' => 0, 'error' => 1, 'msg' => '<span>File(s) Upload Error.</span>');
    //print json_encode($resp);
    //die;
    }

    //return 'true';
    //$data=array('middle_content'=>'preview_register');
    //$this->load->view('common_view',$data);

    } */

    public function preview()
    {
        if (!$this->session->userdata('enduserinfo')) {
            redirect(base_url());
        }
        ////check temp file uploaded or not////
        $images_flag = 0;
        if (!file_exists("uploads/photograph/" . $this->session->userdata['enduserinfo']['photoname'])) {
            $images_flag = 1;
        }
        if (!file_exists("uploads/scansignature/" . $this->session->userdata['enduserinfo']['signname'])) {
            $images_flag = 1;
        }
        if (!file_exists("uploads/idproof/" . $this->session->userdata['enduserinfo']['idname'])) {
            $images_flag = 1;
        }

        if (!file_exists("uploads/declaration/" . $this->session->userdata['enduserinfo']['declaration'])) {
            $images_flag = 1;
        }

        if ($images_flag == 1) {
            $this->session->set_flashdata('error', 'Please upload valid image(s)');
            redirect(base_url() . 'Register/member/');
        }

        //check email,mobile duplication on the same time from different browser!!
        $endTime    = date("H:i:s");
        $start_time = date("H:i:s", strtotime("-20 minutes", strtotime($endTime)));
        $this->db->where('Time(createdon) BETWEEN "' . $start_time . '" and "' . $endTime . '"');
        $this->db->where('email', $this->session->userdata['enduserinfo']['email']);
        $this->db->or_where('email', $this->session->userdata['enduserinfo']['mobile']);
        $check_duplication = $this->master_model->getRecords('member_registration', array('isactive' => 0));
        if (count($check_duplication) > 0) {
            redirect(base_url() . 'Register/cookie_msg');
        }

        $undergraduate      = $this->master_model->getRecords('qualification', array('type' => 'UG'));
        $graduate           = $this->master_model->getRecords('qualification', array('type' => 'GR'));
        $postgraduate       = $this->master_model->getRecords('qualification', array('type' => 'PG'));
        $institution_master = $this->master_model->getRecords('institution_master');
        $states             = $this->master_model->getRecords('state_master');
        $designation        = $this->master_model->getRecords('designation_master');
        $idtype_master      = $this->master_model->getRecords('idtype_master');
        $data               = array('middle_content' => 'preview_register', 'states' => $states, 'undergraduate' => $undergraduate, 'graduate' => $graduate, 'postgraduate' => $postgraduate, 'institution_master' => $institution_master, 'designation' => $designation, 'idtype_master' => $idtype_master);
        $this->load->view('common_view_fullwidth', $data);

    }
    public function cookie_msg()
    {
        $data = array('middle_content' => 'cookie_msg');
        $this->load->view('common_view_fullwidth', $data);
    }

    //Genereate random password function
    public function generate_random_password($length = 8, $level = 2) // function to generate new password

    {
        list($usec, $sec) = explode(' ', microtime());
        srand((float) $sec + ((float) $usec * 100000));
        $validchars[1] = "0123456789abcdfghjkmnpqrstvwxyz";
        $validchars[2] = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $validchars[3] = "0123456789_!@#*()-=+abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_!@#*()-=+";
        $password      = "";
        $counter       = 0;
        while ($counter < $length) {
            $actChar = substr($validchars[$level], rand(0, strlen($validchars[$level]) - 1), 1);
            if (!strstr($password, $actChar)) {
                $password .= $actChar;
                $counter++;
            }
        }
        return $password;
    }

    ##---------check mail alredy exist or not (prafull)-----------##
    public function emailduplication()
    {
        $email = strtolower($_POST['email']);
        if ($email != "") {
            $where = "( registrationtype='O' OR registrationtype='A' OR registrationtype='F')";
            $this->db->where($where);
            $prev_count = $this->master_model->getRecordCount('member_registration', array('email' => $email, 'isactive' => '1'));
            //echo $this->db->last_query();
            if ($prev_count == 0) {
                $data_arr = array('ans' => 'ok');
                echo json_encode($data_arr);} else {
                $user_info        = $this->master_model->getRecords('member_registration', array('email' => $email), 'regnumber,firstname,middlename,lastname');
                $username         = $user_info[0]['firstname'] . ' ' . $user_info[0]['middlename'] . ' ' . $user_info[0]['lastname'];
                $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
                $str              = 'The entered email ID and mobile no already exist for membership / registration number ' . $user_info[0]['regnumber'] . ' , ' . $userfinalstrname . '';
                $data_arr         = array('ans' => 'exists', 'output' => $str);
                echo json_encode($data_arr);

            }
        } else {
            echo 'error';
        }
    }

    ##---------check pincode/zipcode alredy exist or not (prafull)-----------##
    public function checkpin()
    {
        $statecode = mysqli_real_escape_string($this->db->conn_id, $_POST['statecode']);
        $pincode   = mysqli_real_escape_string($this->db->conn_id, $_POST['pincode']);
        if ($statecode != "") {
            $this->db->where("$pincode BETWEEN start_pin AND end_pin");
            $prev_count = $this->master_model->getRecordCount('state_master', array('state_code' => $statecode));
            //echo $this->db->last_query();
            //exit;
            if ($prev_count == 0) {echo 'false';} else {echo 'true';}
        } else {
            echo 'false';
        }
    }

    ##---------check mobile nnnumber alredy exist or not (prafull)-----------##
    public function mobileduplication()
    {
        //$mobile=mysqli_real_escape_string($this->db->conn_id,$_POST['mobile']);
        if ($mobile != "") {
            $where = "(registrationtype='O' OR registrationtype='A' OR registrationtype='F')";
            $this->db->where($where);
            $prev_count = $this->master_model->getRecordCount('member_registration', array('mobile' => $mobile, 'isactive' => '1'));
            //echo $this->db->last_query();
            if ($prev_count == 0) {
                $data_arr = array('ans' => 'ok');
                echo json_encode($data_arr);
            } else {
                $user_info        = $this->master_model->getRecords('member_registration', array('mobile' => $mobile), 'regnumber,firstname,middlename,lastname');
                $username         = $user_info[0]['firstname'] . ' ' . $user_info[0]['middlename'] . ' ' . $user_info[0]['lastname'];
                $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
                $str              = 'The entered email ID and mobile no already exist for membership / registration number ' . $user_info[0]['regnumber'] . ' , ' . $userfinalstrname . '';
                $data_arr         = array('ans' => 'exists', 'output' => $str);
                echo json_encode($data_arr);
            }
        } else {
            echo 'error';
        }
    }

    public function make_payment()
    {

        ////check temp file uploaded or not////
        $images_flag = 0;
        if (!file_exists("uploads/photograph/" . $this->session->userdata['enduserinfo']['photoname'])) {
            $images_flag = 1;
        }
        if (!file_exists("uploads/scansignature/" . $this->session->userdata['enduserinfo']['signname'])) {
            $images_flag = 1;
        }
        if (!file_exists("uploads/idproof/" . $this->session->userdata['enduserinfo']['idname'])) {
            $images_flag = 1;
        }

        if (!file_exists("uploads/declaration/" . $this->session->userdata['enduserinfo']['declaration'])) {
            $images_flag = 1;
        }

        if ($images_flag == 1) {
            $this->session->set_flashdata('error', 'Please upload valid image(s)');
            redirect(base_url() . 'Register/member/');
        }

        $cgst_rate = $sgst_rate = $igst_rate = $tax_type = '';
        $cgst_amt  = $sgst_amt  = $igst_amt  = '';
        $cs_total  = $igst_total  = '';
        $getstate  = $getcenter  = $getfees  = array();
        $flag      = 1;
        // TO do:
        // Validate reg no in DB
        //$_REQUEST['regno'] = "ODExODU5OTE1";
        //$regno = base64_decode($_REQUEST['regno']);
        $regno = $this->session->userdata['memberdata']['regno'];
        if (!empty($regno)) {
            $member_data = $this->Master_model->getRecords('member_registration', array('regid' => $regno, 'isactive' => '0'), array('state_pr', 'fee'));
        }

        $valcookie = register_get_cookie();

        if ($valcookie) {
            $regid = $valcookie;
            //$regid= '57';
            $checkuser = $this->master_model->getRecords('member_registration', array('regid' => $regno, 'regnumber !=' => '', 'isactive !=' => '0'));
            if (count($checkuser) > 0) {
                delete_cookie('regid');
                redirect('http://iibf.org.in');
            } else {
                $checkpayment = $this->master_model->getRecords('payment_transaction', array('ref_id' => $regno, 'status' => '2'));
                if (count($checkpayment) > 0) {
                    ///$datearr=explode(' ',$checkpayment[0]['date']);
                    $endTime      = date("Y-m-d H:i:s", strtotime("+20 minutes", strtotime($checkpayment[0]['date'])));
                    $current_time = date("Y-m-d H:i:s");
                    if (strtotime($current_time) <= strtotime($endTime)) {
                        $flag = 0;
                    } else {
                        delete_cookie('regid');
                        redirect('http://iibf.org.in');
                    }
                } else {
                    $flag = 1;
                    delete_cookie('regid');
                    redirect('http://iibf.org.in');
                }
            }
        }

        if (isset($_POST['processPayment']) && $_POST['processPayment']) {
           
            $checkpayment = $this->master_model->getRecords('payment_transaction', array('ref_id' => $regno, 'status' => '2'));
            //echo $this->db->last_query();exit;
                if (count($checkpayment) > 0) {
                    delete_cookie('regid');
                    redirect(base_url().'/register/member');
                }
            // $pg_name = $this->input->post('pg_name');
            $pg_name = 'billdesk';
            if ($pg_name == 'sbi') {
                $gateway = 'sbiepay';
            } else {
                $gateway = 'billdesk';
            }

            //setting cookie for tracking multiple payment scenario
            register_set_cookie($regno);

            $state = $member_data[0]['state_pr'];
            $fee   = $member_data[0]['fee'];
            if (!empty($state)) {
                if ($state == 'MAH') {
                    $amount = $this->config->item('cs_total');
                }
                /*else if($state == 'JAM')
                {
                $amount = $this->config->item('fee_amt');
                }*/
                else {
                    $amount = $this->config->item('igst_total');
                }
            }
            //$MerchantOrderNo = generate_order_id("reg_sbi_order_id");

            // Create transaction
            $insert_data = array(
                'gateway'     => $gateway,
                'amount'      => $amount,
                'date'        => date('Y-m-d H:i:s'),
                'ref_id'      => $regno,
                'description' => "Membership Registration",
                'pay_type'    => 1,
                'status'      => 2,
                //'receipt_no'  => $MerchantOrderNo,
                'pg_flag'     => 'iibfregn',
                //'pg_other_details'=>$custom_field
            );

            $pt_id = $this->master_model->insertRecord('payment_transaction', $insert_data, true);

            $log_title   = "Ordinory member payment transaction insert:" . $pt_id;
            $log_message = serialize($insert_data);
            $rId         = $pt_id;
            $regNo       = $regno;
            storedUserActivity($log_title, $log_message, $rId, $regNo);

            $MerchantOrderNo = reg_sbi_order_id($pt_id);

            //Member registration
            //Ref1 = primary key of member registation table
            //Ref2 = iibfregn
            //Ref3 = primary key of member registation table
            //Ref4 = orderid  For below string
            $custom_field          = $regno . "^iibfregn^" . $regno . "^" . $MerchantOrderNo;
            $custom_field_billdesk = $regno . "-iibfregn-" . $regno . "-" . $MerchantOrderNo;

            // update receipt no. in payment transaction -
            $update_data = array('receipt_no' => $MerchantOrderNo, 'pg_other_details' => $custom_field);
            $this->master_model->updateRecord('payment_transaction', $update_data, array('id' => $pt_id));

            //get value for invoice details [Tejasvi]
            if (!empty($state)) {
                //get state code,state name,state number.
                $getstate = $this->master_model->getRecords('state_master', array('state_code' => $state, 'state_delete' => '0'));
            }
            if ($state == 'MAH') {
                //set a rate (e.g 9%,9% or 18%)
                $cgst_rate = $this->config->item('cgst_rate');
                $sgst_rate = $this->config->item('sgst_rate');
                //set an amount as per rate
                $cgst_amt = $this->config->item('cgst_amt');
                $sgst_amt = $this->config->item('sgst_amt');
                //set an total amount
                $cs_total = $amount;
                $tax_type = 'Intra';

            }
            /*else if($state == 'JAM')
            {
            //set a rate (e.g 9%,9% or 18%)
            $cgst_rate=$sgst_rate=$igst_rate='';
            $cgst_amt=$sgst_amt=$igst_amt='';
            $igst_total=$amount;
            $tax_type='Inter';
            }*/
            else {
                $igst_rate  = $this->config->item('igst_rate');
                $igst_amt   = $this->config->item('igst_amt');
                $igst_total = $amount;
                $tax_type   = 'Inter';
            }

            /*if($getstate[0]['exempt']=='E')
            {
            $cgst_rate=$sgst_rate=$igst_rate='';
            $cgst_amt=$sgst_amt=$igst_amt='';
            }*/

            $invoice_insert_array = array('pay_txn_id' => $pt_id,
                'receipt_no'                               => $MerchantOrderNo,
                'member_no'                                => $regno,
                'state_of_center'                          => $state,
                'app_type'                                 => 'R',
                'service_code'                             => $this->config->item('reg_service_code'),
                'qty'                                      => '1',
                'state_code'                               => $getstate[0]['state_no'],
                'state_name'                               => $getstate[0]['state_name'],
                'tax_type'                                 => $tax_type,
                'fee_amt'                                  => $this->config->item('fee_amt'),
                'cgst_rate'                                => $cgst_rate,
                'cgst_amt'                                 => $cgst_amt,
                'sgst_rate'                                => $sgst_rate,
                'sgst_amt'                                 => $sgst_amt,
                'igst_rate'                                => $igst_rate,
                'igst_amt'                                 => $igst_amt,
                'cs_total'                                 => $cs_total,
                'igst_total'                               => $igst_total,
                'gstin_no'                                 => '',
                'exempt'                                   => $getstate[0]['exempt'],
                'created_on'                               => date('Y-m-d H:i:s'));

            $inser_id = $this->master_model->insertRecord('exam_invoice', $invoice_insert_array);

            $log_title   = "Ordinory membership exam invoice insert" . $regno;
            $log_message = serialize($invoice_insert_array);
            $rId         = $regno;
            $regNo       = $regno;
            storedUserActivity($log_title, $log_message, $rId, $regNo);

            /* This changes made by Pratibha borse Start code 09Feb2022 */
            if ($pg_name == 'sbi') {

                include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                $key            = $this->config->item('sbi_m_key');
                $merchIdVal     = $this->config->item('sbi_merchIdVal');
                $AggregatorId   = $this->config->item('sbi_AggregatorId');
                $pg_success_url = base_url() . "Register/sbitranssuccess";
                $pg_fail_url    = base_url() . "Register/sbitransfail";

                $MerchantCustomerID  = $regno;
                $data["pg_form_url"] = $this->config->item('sbi_pg_form_url');
                $data["merchIdVal"]  = $merchIdVal;

                $EncryptTrans = $merchIdVal . "|DOM|IN|INR|" . $amount . "|" . $custom_field . "|" . $pg_success_url . "|" . $pg_fail_url . "|" . $AggregatorId . "|" . $MerchantOrderNo . "|" . $MerchantCustomerID . "|NB|ONLINE|ONLINE";

                $aes = new CryptAES();
                $aes->set_key(base64_decode($key));
                $aes->require_pkcs5();

                $EncryptTrans = $aes->encrypt($EncryptTrans);

                $data["EncryptTrans"] = $EncryptTrans; // SBI encrypted form field value
                $this->load->view('pg_sbi_form', $data);

            } elseif ($pg_name == 'billdesk') {

                $billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $regno, $regno, '', 'register/handle_billdesk_response', '', '', '', $custom_field_billdesk);

                if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE') {
                    $data['bdorderid']      = $billdesk_res['bdorderid'];
                    $data['token']          = $billdesk_res['token'];
                    $data['responseXHRUrl'] = $billdesk_res['responseXHRUrl'];
                    $data['returnUrl']      = $billdesk_res['returnUrl'];
                    $this->load->view('pg_billdesk/pg_billdesk_form', $data);
                } else {
                    $this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
                    redirect(base_url() . 'Register');
                }
            }
            /*  End code */

            /*
        requestparameter=
        MerchantId | OperatingMode | MerchantCountry | MerchantCurrency |
        PostingAmount | OtherDetails | SuccessURL | FailURL | AggregatorId | MerchantOrderNo |
        MerchantCustomerID | Paymode | Accesmedium | TransactionSource
        Ex.
        requestparameter
        =1000003|DOM|IN|INR|2|Other|https://test.sbiepay.coom/secure/fail.jsp|SBIEPAY|2|2|NB|ONLINE|ONLINE
         */

        } else {
            //$data["regno"] = $_REQUEST['regno'];
            $data['show_billdesk_option_flag'] = 1;
            $this->load->view('pg_sbi/make_payment_page', $data);
        }

    }

    public function sbitranssuccess()
    {
        exit();
        $this->load->helper('update_image_name_helper');
        delete_cookie('regid');
        $this->session->unset_userdata('enduserinfo');
        $this->session->unset_userdata('memberdata');
        if (isset($_REQUEST['encData'])) {
            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
            $key = $this->config->item('sbi_m_key');
            $aes = new CryptAES();
            $aes->set_key(base64_decode($key));
            $aes->require_pkcs5();
            $encData         = $aes->decrypt($_REQUEST['encData']);
            $responsedata    = explode("|", $encData);
            $MerchantOrderNo = $responsedata[0];
            $transaction_no  = $responsedata[1];
            $attachpath      = $invoiceNumber      = '';
            if (isset($_REQUEST['merchIdVal'])) {
                $merchIdVal = $_REQUEST['merchIdVal'];
            }
            if (isset($_REQUEST['Bank_Code'])) {
                $Bank_Code = $_REQUEST['Bank_Code'];
            }
            if (isset($_REQUEST['pushRespData'])) {
                $encData = $_REQUEST['pushRespData'];
            }
            //Sbi B2B callback
            //check sbi payment status with MerchantOrderNo
            $q_details = sbiqueryapi($MerchantOrderNo);
            if ($q_details) {
                if ($q_details[2] == "SUCCESS") {
                    $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,status,id');
                    //echo '<br>1'.$this->db->last_query();

                    //check user payment status is updated by s2s or not
                    if ($get_user_regnum_info[0]['status'] == 2) {
                        $update_data  = array('transaction_no' => $transaction_no, 'status' => 1, 'transaction_details' => $responsedata[2] . " - " . $responsedata[7], 'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5], 'callback' => 'B2B');
                        $update_query = $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo, 'status' => 2));
                        //echo '<br>2'.$this->db->last_query();

                        $log_title   = "Ordinory member payment paymentupdate";
                        $log_message = serialize($update_data);
                        $rId         = $get_user_regnum_info[0]['ref_id'];
                        $regNo       = $get_user_regnum_info[0]['ref_id'];
                        storedUserActivity($log_title, $log_message, $rId, $regNo);
                        if ($this->db->affected_rows()) {
                            //echo '<br>3 > '.$this->db->last_query();
                            $reg_id = $get_user_regnum_info[0]['ref_id'];
                            //$applicationNo = generate_mem_reg_num();
                            $applicationNo = generate_O_memreg($reg_id);
                            #####update member number in payment transaction####
                            $update_data = array('member_regnumber' => $applicationNo);
                            $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
                            //echo '<br>4'.$this->db->last_query();
                            if (count($get_user_regnum_info) > 0) {
                                $update_mem_data = array('isactive' => '1', 'regnumber' => $applicationNo);
                                $this->master_model->updateRecord('member_registration', $update_mem_data, array('regid' => $reg_id));

                                $user_info = $this->master_model->getRecords('member_registration', array('regid' => $reg_id), 'usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,mobile,vis_imp_cert_img,orth_han_cert_img,cer_palsy_cert_img,declaration');

                                ########get Old image Name############
                                $log_title   = "Ordinory member OLD Image :" . $reg_id;
                                $log_message = serialize($user_info);
                                $rId         = $reg_id;
                                $regNo       = $reg_id;
                                storedUserActivity($log_title, $log_message, $rId, $regNo);

                                $upd_files           = array();
                                $photo_file          = 'p_' . $applicationNo . '.jpg';
                                $sign_file           = 's_' . $applicationNo . '.jpg';
                                $proof_file          = 'pr_' . $applicationNo . '.jpg';
                                $declaration_file    = 'declaration_' . $applicationNo . '.jpg';
                                $visually_file       = 'v_' . $applicationNo . '.jpg';
                                $orthopedically_file = 'o_' . $applicationNo . '.jpg';
                                $cerebral_file       = 'c_' . $applicationNo . '.jpg';

                                /* if(@ rename("./uploads/photograph/".$user_info[0]['scannedphoto'],"./uploads/photograph/".$photo_file))
                                {    $upd_files['scannedphoto'] = $photo_file;    } */
                                $chk_photo = update_image_name("./uploads/photograph/", $user_info[0]['scannedphoto'], $photo_file); //update_image_name_helper.php
                                if ($chk_photo != "") {$upd_files['scannedphoto'] = $chk_photo;}

                                /* if(@ rename("./uploads/scansignature/".$user_info[0]['scannedsignaturephoto'],"./uploads/scansignature/".$sign_file))
                                {    $upd_files['scannedsignaturephoto'] = $sign_file;    } */
                                $chk_sign = update_image_name("./uploads/scansignature/", $user_info[0]['scannedsignaturephoto'], $sign_file); //update_image_name_helper.php
                                if ($chk_sign != "") {$upd_files['scannedsignaturephoto'] = $chk_sign;}

                                /* if(@ rename("./uploads/idproof/".$user_info[0]['idproofphoto'],"./uploads/idproof/".$proof_file))
                                {    $upd_files['idproofphoto'] = $proof_file;    } */
                                $chk_proof = update_image_name("./uploads/idproof/", $user_info[0]['idproofphoto'], $proof_file); //update_image_name_helper.php
                                if ($chk_proof != "") {$upd_files['idproofphoto'] = $chk_proof;}

                                $chk_declaration = update_image_name("./uploads/declaration/", $user_info[0]['declaration'], $declaration_file); //update_image_name_helper.php
                                if ($chk_declaration != "") {$upd_files['declaration'] = $chk_declaration;}

                                /* if(@ rename("./uploads/disability/".$user_info[0]['vis_imp_cert_img'], "./uploads/disability/".$visually_file))
                                {    $upd_files['vis_imp_cert_img'] = $visually_file;    } */
                                $chk_visually = update_image_name("./uploads/disability/", $user_info[0]['vis_imp_cert_img'], $visually_file); //update_image_name_helper.php
                                if ($chk_visually != "") {$upd_files['vis_imp_cert_img'] = $chk_visually;}

                                /* if(@ rename("./uploads/disability/".$user_info[0]['orth_han_cert_img'], "./uploads/disability/".$orthopedically_file))
                                {    $upd_files['orth_han_cert_img'] = $orthopedically_file;    } */
                                $chk_orthopedically = update_image_name("./uploads/disability/", $user_info[0]['orth_han_cert_img'], $orthopedically_file); //update_image_name_helper.php
                                if ($chk_orthopedically != "") {$upd_files['orth_han_cert_img'] = $chk_orthopedically;}

                                /* if(@ rename("./uploads/disability/".$user_info[0]['cer_palsy_cert_img'],"./uploads/disability/".$cerebral_file))
                                {    $upd_files['cer_palsy_cert_img'] = $cerebral_file;    } */
                                $chk_cerebral = update_image_name("./uploads/disability/", $user_info[0]['cer_palsy_cert_img'], $cerebral_file); //update_image_name_helper.php
                                if ($chk_cerebral != "") {$upd_files['cer_palsy_cert_img'] = $chk_cerebral;}

                                if (count($upd_files) > 0) {
                                    $this->master_model->updateRecord('member_registration', $upd_files, array('regid' => $reg_id));
                                    $log_title   = "Ordinory member PIC update :" . $reg_id;
                                    $log_message = serialize($this->db->last_query());
                                    $rId         = $reg_id;
                                    $regNo       = $reg_id;
                                    storedUserActivity($log_title, $log_message, $rId, $regNo);
                                } else {
                                    $upd_files['scannedphoto']          = $photo_file;
                                    $upd_files['scannedsignaturephoto'] = $sign_file;
                                    $upd_files['idproofphoto']          = $proof_file;
                                    $upd_files['vis_imp_cert_img']      = $visually_file;
                                    $upd_files['orth_han_cert_img']     = $orthopedically_file;
                                    $upd_files['cer_palsy_cert_img']    = $cerebral_file;
                                    $this->master_model->updateRecord('member_registration', $upd_files, array('regid' => $reg_id));
                                    $log_title   = "Member MANUAL PICS Update :" . $reg_id;
                                    $log_message = serialize($upd_files);
                                    $rId         = $reg_id;
                                    $regNo       = $reg_id;
                                    storedUserActivity($log_title, $log_message, $rId, $regNo);
                                }
                            }

                            //email to user
                            $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'user_register_email'));
                            if (count($emailerstr) > 0) {
                                include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                                $key = $this->config->item('pass_key');
                                $aes = new CryptAES();
                                $aes->set_key(base64_decode($key));
                                $aes->require_pkcs5();
                                //$encPass = $aes->encrypt(trim($user_info[0]['usrpassword']));
                                $decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));
                                //$decpass = $aes->decrypt($user_info[0]['usrpassword']);
                                $newstring = str_replace("#application_num#", "" . $applicationNo . "", $emailerstr[0]['emailer_text']);
                                $final_str = str_replace("#password#", "" . $decpass . "", $newstring);
                                $info_arr  = array('to' => $user_info[0]['email'],
                                    //'to'=>'kumartupe@gmail.com',
                                    'from'                  => $emailerstr[0]['from'],
                                    'subject'               => $emailerstr[0]['subject'] . ' ' . $applicationNo,
                                    'message'               => $final_str,
                                );

                                //get invoice
                                $getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo, 'pay_txn_id' => $get_user_regnum_info[0]['id']));
                                //echo $this->db->last_query();exit;
                                if (count($getinvoice_number) > 0) {
                                    /*if($getinvoice_number[0]['state_of_center']=='JAM')
                                    {
                                    $invoiceNumber = generate_registration_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
                                    if($invoiceNumber)
                                    {
                                    $invoiceNumber=$this->config->item('mem_invoice_no_prefix_jammu').$invoiceNumber;
                                    }
                                    }*/
                                    //else
                                    //{
                                    $invoiceNumber = generate_registration_invoice_number($getinvoice_number[0]['invoice_id']);
                                    if ($invoiceNumber) {
                                        $invoiceNumber = $this->config->item('mem_invoice_no_prefix') . $invoiceNumber;
                                    }
                                    //}

                                    $update_data = array('invoice_no' => $invoiceNumber, 'member_no' => $applicationNo, 'transaction_no' => $transaction_no, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'));
                                    $this->db->where('pay_txn_id', $get_user_regnum_info[0]['id']);
                                    $this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));
                                    $attachpath = genarate_reg_invoice($getinvoice_number[0]['invoice_id']);
                                }

                                if ($attachpath != '') {
                                    $sms_newstring = str_replace("#application_num#", "" . $applicationNo . "", $emailerstr[0]['sms_text']);
                                    $sms_final_str = str_replace("#password#", "" . $decpass . "", $sms_newstring);
                                    //$this->master_model->send_sms($user_info[0]['mobile'],$sms_final_str);
                                    //$this->master_model->send_sms_trustsignal(intval($user_info[0]['mobile']), $sms_final_str, 'DPDoOIwMR');
                                    $this->master_model->send_sms_common_all($user_info[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

                                    //if($this->Emailsending->mailsend($info_arr))
                                    if ($this->Emailsending->mailsend_attch($info_arr, $attachpath)) {
                                        redirect(base_url() . 'Register/acknowledge/');
                                    } else {
                                        redirect(base_url() . 'Register/acknowledge/');
                                    }
                                } else {
                                    redirect(base_url() . 'Register/acknowledge/');
                                }
                            }

                        }
                        //Manage Log
                        $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                        $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
                    }
                }
            }
            ///End of SBI B2B callback
            redirect(base_url() . 'Register/acknowledge/');
        } else {
            die("Please try again...");
        }
    }

    public function sbitransfail()
    {
        exit();
        delete_cookie('regid');
        if (isset($_REQUEST['encData'])) {
            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
            $key = $this->config->item('sbi_m_key');
            $aes = new CryptAES();
            $aes->set_key(base64_decode($key));
            $aes->require_pkcs5();
            $encData              = $aes->decrypt($_REQUEST['encData']);
            $responsedata         = explode("|", $encData);
            $MerchantOrderNo      = $responsedata[0];
            $transaction_no       = $responsedata[1];
            $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,status');

            if ($get_user_regnum_info[0]['status'] != 0 && $get_user_regnum_info[0]['status'] == 2) {
                if (isset($_REQUEST['merchIdVal'])) {
                    $merchIdVal = $_REQUEST['merchIdVal'];
                }
                if (isset($_REQUEST['Bank_Code'])) {
                    $Bank_Code = $_REQUEST['Bank_Code'];
                }
                if (isset($_REQUEST['pushRespData'])) {
                    $encData = $_REQUEST['pushRespData'];
                }

                $update_data = array('transaction_no' => $transaction_no, 'status' => 0, 'transaction_details' => $responsedata[2] . " - " . $responsedata[7], 'auth_code' => '0399', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5], 'callback' => 'B2B');
                $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
                //Manage Log
                $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
            }
            //Sbi fail code without callback
            echo "Transaction failed";

            echo "<script>
                (function (global) {

                    if(typeof (global) === 'undefined')
                    {
                        throw new Error('window is undefined');
                    }

                    var _hash = '!';
                    var noBackPlease = function () {
                        global.location.href += '#';

                        // making sure we have the fruit available for juice....
                        // 50 milliseconds for just once do not cost much (^__^)
                        global.setTimeout(function () {
                            global.location.href += '!';
                        }, 50);
                    };

                    // Earlier we had setInerval here....
                    global.onhashchange = function () {
                        if (global.location.hash !== _hash) {
                            global.location.hash = _hash;
                        }
                    };

                    global.onload = function () {

                        noBackPlease();

                        // disables backspace on page except on input fields and textarea..
                        document.body.onkeydown = function (e) {
                            var elm = e.target.nodeName.toLowerCase();
                            if (e.which === 8 && (elm !== 'input' && elm  !== 'textarea')) {
                                e.preventDefault();
                            }
                            // stopping event bubbling up the DOM tree..
                            e.stopPropagation();
                        };

                    };

                })(window);
                </script>";
            exit;
            /*    $this->load->model('log_model');
            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
            $key = $this->config->item('sbi_m_key');
            $aes = new CryptAES();
            $aes->set_key(base64_decode($key));
            $aes->require_pkcs5();
            $merchIdVal = $_REQUEST['merchIdVal'];
            $Bank_Code = $_REQUEST['Bank_Code'];
            $encData = $aes->decrypt($_REQUEST['encData']);
            $responsedata = explode("|",$encData);
            $MerchantOrderNo = $responsedata[0]; // To DO: temp testing changes please remove it and use valid recipt id
            $transaction_no  = $responsedata[1];*/

            //SBI Callback Code
            /*$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0399', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5]);
            $this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));*/
            //END of SBI Callback code

            //print_r($responsedata);  // Payment gateway response
            // TO DO : Redirect to user acknowledge page

        } else {
            die("Please try again...");
        }
    }
    /* Display list of exams for members */
    public function examlist()
    {
         //echo $this->get_client_ip();die;
         /*if($this->get_client_ip() == '115.124.115.77'){// && $this->get_client_ip() =='182.73.101.70'
            //die('No Exams Available...');
         }
         else if($this->get_client_ip() =='49.43.27.228'){

         }
         else{
            die('No Exams Available...');
         }*/


        $today_date = date('Y-m-d');
        $flag       = 1;
        $exam_list  = array();
        $exam_list  = array();
        //$examcodes=array('526','527','991');
        ##SPEL new exam codes change - added by Pratibha 
        
        $examcodes = array('528', '79', '529', '530', '531', '534', '991', '997', '1031', '1032', '1052', '1054', '1053', '1062', '1063', '1064', '1065', '1066', '1067', '1068', '1069'); // Exam code 1031 for SBI New Exam & 1032 for AML/KYC and Risk Management  
        //$examcodes = array('528', '529', '530', '531', '534', '991', '997', '1031', '1032', '1052', '1054', '1053', '1062', '1063', '1002', '1003', '1004', '1005', '1009', '1013', '1014'); // Exam code 1031 for SBI New Exam & 1032 for AML/KYC and Risk Management  
        
        // IP whitelist condition
        if($this->get_client_ip()!='115.124.115.75' && $this->get_client_ip()!='182.73.101.70')
        {
            $examcodes = array('528', '529', '530', '531', '534', '991', '997', '1031', '1032', '1052', '1054', '1053', '1062', '1063', '1064', '1065', '1066', '1067', '1068', '1069','8','11','19','78','79','119','151','153','154','156','157','158','163','165','166','220');
        }

        $Extype    = base64_decode($this->input->get('Extype'));
        $Mtype     = base64_decode($this->input->get('Mtype'));
       // echo $Mtype;


        if ($Mtype != 'O' && $Mtype != 'A' && $Mtype != 'F' && $Mtype != 'DB' && $Mtype != 'NM') {
            $flag = 0;
        }
        if ($flag == 1) {
            if ($Mtype == 'O') {
                $this->db->where('elg_mem_o', 'Y');
            }
            if ($Mtype == 'A') {
                $this->db->where('elg_mem_a', 'Y');
            }
            if ($Mtype == 'F') {
                $this->db->where('elg_mem_f', 'Y');
            }
            if ($Mtype == 'DB') {
                $this->db->where('elg_mem_db', 'Y');
            }
            if ($Mtype == 'NM') {
                $this->db->where('elg_mem_nm', 'Y');
            }
            $this->db->join('subject_master', 'subject_master.exam_code=exam_master.exam_code');
            $this->db->join('center_master', 'center_master.exam_name=exam_master.exam_code');
            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=exam_master.exam_code');
            $this->db->join('medium_master', 'medium_master.exam_code=exam_activation_master.exam_code AND medium_master.exam_period=exam_activation_master.    exam_period');
            $this->db->join('misc_master', 'misc_master.exam_code=exam_master.exam_code AND misc_master.exam_period=exam_activation_master.exam_period AND misc_master.exam_period=center_master.exam_period AND subject_master.exam_period=misc_master.exam_period');
            $this->db->where('medium_delete', '0');
            $this->db->where('exam_type', trim($Extype));
            $this->db->where("misc_master.misc_delete", '0');
            $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
            $this->db->where("exam_activation_master.exam_activation_delete", "0");
            $this->db->where_not_in('exam_master.exam_code', $examcodes);
            $this->db->group_by('medium_master.exam_code');
            //$this->db->order_by('exam_activation_master.id','DESC');
            $this->db->order_by('exam_master.description', 'ASC');
            $exam_list = $this->master_model->getRecords('exam_master');
            /*$this->db->join('exam_activation_master','exam_activation_master.exam_code=exam_master.exam_code');
            $this->db->join('medium_master','medium_master.exam_code=exam_activation_master.exam_code AND medium_master.exam_period=exam_activation_master.    exam_period');
            $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
            $this->db->where('medium_delete','0');
            $this->db->where('exam_type',trim($Extype));
            $this->db->where('exam_activation_master.exam_activation_delete','0');
            $this->db->group_by('medium_master.exam_code');
            $exam_list=$this->master_model->getRecords('exam_master');*/


            $this->db->join('iibfbcbf_exam_subject_master sm', 'sm.exam_code = em.exam_code');
            $this->db->join('iibfbcbf_exam_centre_master cm', 'cm.exam_name = em.exam_code');
            $this->db->join('iibfbcbf_exam_activation_master eam', 'eam.exam_code = em.exam_code');
            $this->db->join('iibfbcbf_exam_medium_master emm', 'emm.exam_code = eam.exam_code AND emm.exam_period= eam.exam_period');
            $this->db->join('iibfbcbf_exam_misc_master exmm', 'exmm.exam_code= em.exam_code AND exmm.exam_period= eam.exam_period AND exmm.exam_period= cm.exam_period AND sm.exam_period= exmm.exam_period');
            $this->db->where('emm.medium_delete', '0');
            //$this->db->where('exam_type', trim($Extype));
            $this->db->where("exmm.misc_delete", '0');
            $this->db->where("'$today_date' BETWEEN eam.exam_from_date AND eam.exam_to_date");
            $this->db->where("eam.exam_activation_delete", "0");
            $this->db->where_not_in('em.exam_code', $examcodes);
            $this->db->group_by('emm.exam_code');
            //$this->db->order_by('exam_activation_master.id','DESC');
            $this->db->order_by('em.exam_code', 'ASC');
            $this->db->where_in('em.exam_code', array(1037,1038));
            $exam_list_bcbf = $this->master_model->getRecords('iibfbcbf_exam_master em');
            
            $exam_type_name = $this->master_model->getRecords('exam_type', array('id' => trim($Extype)));
            //echo $this->db->last_query();exit;
        }

         $data = array('exam_list' => $exam_list, 'exam_list_bcbf'=>$exam_list_bcbf, 'Extype' => base64_encode($Extype), 'Mtype' => base64_encode($Mtype), 'exam_type_name' => $exam_type_name);
         
        /*$my_ip = $this->get_client_ip();
        //echo $my_ip;
        if($my_ip == "115.124.115.69" || $my_ip == "182.73.101.70"){
           $data = array('exam_list' => $exam_list, 'Extype' => base64_encode($Extype), 'Mtype' => base64_encode($Mtype), 'exam_type_name' => $exam_type_name);
        }else{
           $data = array('exam_list' => array(), 'Extype' => base64_encode($Extype), 'Mtype' => base64_encode($Mtype), 'exam_type_name' => $exam_type_name);
        }*/ 

        
        $this->load->view('examlist', $data);
    }

    public function examlist_test()
    {
        $today_date = date('Y-m-d');
        $flag       = 1;
        $exam_list  = array();
        $exam_list  = array();
        $examcodes  = array('526', '527', '991', '997');
        $Extype     = base64_decode($this->input->get('Extype'));
        $Mtype      = base64_decode($this->input->get('Mtype'));
        if ($Mtype != 'O' && $Mtype != 'A' && $Mtype != 'F' && $Mtype != 'DB' && $Mtype != 'NM') {
            $flag = 0;
        }
        if ($flag == 1) {
            if ($Mtype == 'O') {
                $this->db->where('elg_mem_o', 'Y');
            }
            if ($Mtype == 'A') {
                $this->db->where('elg_mem_a', 'Y');
            }
            if ($Mtype == 'F') {
                $this->db->where('elg_mem_f', 'Y');
            }
            if ($Mtype == 'DB') {
                $this->db->where('elg_mem_db', 'Y');
            }
            if ($Mtype == 'NM') {
                $this->db->where('elg_mem_nm', 'Y');
            }
            $this->db->join('subject_master', 'subject_master.exam_code=exam_master.exam_code');
            $this->db->join('center_master', 'center_master.exam_name=exam_master.exam_code');
            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=exam_master.exam_code');
            $this->db->join('medium_master', 'medium_master.exam_code=exam_activation_master.exam_code AND medium_master.exam_period=exam_activation_master.    exam_period');
            $this->db->join('misc_master', 'misc_master.exam_code=exam_master.exam_code AND misc_master.exam_period=exam_activation_master.exam_period AND misc_master.exam_period=center_master.exam_period AND subject_master.exam_period=misc_master.exam_period');
            $this->db->where('medium_delete', '0');
            $this->db->where('exam_type', trim($Extype));
            $this->db->where("misc_master.misc_delete", '0');
            $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
            $this->db->where("exam_activation_master.exam_activation_delete", "0");
            $this->db->where_not_in('exam_master.exam_code', $examcodes);
            $this->db->group_by('medium_master.exam_code');
            //$this->db->order_by('exam_activation_master.id','DESC');
            $this->db->order_by('exam_master.description', 'ASC');
            $exam_list = $this->master_model->getRecords('exam_master');
            //echo $this->db->last_query();exit;
            /*$this->db->join('exam_activation_master','exam_activation_master.exam_code=exam_master.exam_code');
            $this->db->join('medium_master','medium_master.exam_code=exam_activation_master.exam_code AND medium_master.exam_period=exam_activation_master.    exam_period');
            $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
            $this->db->where('medium_delete','0');
            $this->db->where('exam_type',trim($Extype));
            $this->db->where('exam_activation_master.exam_activation_delete','0');
            $this->db->group_by('medium_master.exam_code');
            $exam_list=$this->master_model->getRecords('exam_master');*/

            $exam_type_name = $this->master_model->getRecords('exam_type', array('id' => trim($Extype)));
            //echo $this->db->last_query();exit;
        }
        $data = array('exam_list' => $exam_list, 'Extype' => base64_encode($Extype), 'Mtype' => base64_encode($Mtype), 'exam_type_name' => $exam_type_name);
        $this->load->view('examlist', $data);
    }

    /* BILLDESK RESPONSE CODE BY PRATIBA BORSE 25 March 22 */
    public function handle_billdesk_response()
    {
        $this->load->helper('update_image_name_helper');
        delete_cookie('regid');
        $_SESSION['session_regid'] = $this->session->userdata['memberdata']['regno'];
        $this->session->unset_userdata('enduserinfo');
        $this->session->unset_userdata('memberdata');

        if (isset($_REQUEST['transaction_response'])) {
            $response_encode        = $_REQUEST['transaction_response'];
            $bd_response            = $this->billdesk_pg_model->verify_res($response_encode);
            $responsedata           = $bd_response['payload'];
            $attachpath             = $invoiceNumber             = '';
            $MerchantOrderNo        = $responsedata['orderid'];
            $transaction_no         = $responsedata['transactionid'];
            $transaction_error_type = $responsedata['transaction_error_type'];
            $transaction_error_desc = $responsedata['transaction_error_desc'];
            $bankid                 = $responsedata['bankid'];
            $txn_process_type       = $responsedata['txn_process_type'];
            $merchIdVal             = $responsedata['mercid'];
            $Bank_Code              = $responsedata['bankid'];
            $encData                = $_REQUEST['transaction_response'];
            $auth_status            = $responsedata['auth_status'];

            $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,status,id');

            $qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);
            if ($auth_status == '0300' && $qry_api_response['auth_status'] == '0300' && $get_user_regnum_info[0]['status'] == 2) {

                $update_data = array(
                    'transaction_no'      => $transaction_no,
                    'status'              => 1,
                    'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
                    'gateway'             => 'billdesk',
                    'auth_code'           => '0300',
                    'bankcode'            => $bankid,
                    'paymode'             => $txn_process_type,
                    'callback'            => 'B2B',
                );

                $update_query = $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo, 'status' => 2));
                //echo '<br>2'.$this->db->last_query();

                $log_title   = "Ordinory member payment paymentupdate";
                $log_message = serialize($update_data);
                $rId         = $get_user_regnum_info[0]['ref_id'];
                $regNo       = $get_user_regnum_info[0]['ref_id'];
                storedUserActivity($log_title, $log_message, $rId, $regNo);

                if ($this->db->affected_rows()) {

                    $reg_id = $get_user_regnum_info[0]['ref_id'];

                    $applicationNo = generate_O_memreg($reg_id);
                    /* update member number in payment transaction */
                    $update_data = array('member_regnumber' => $applicationNo);
                    $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
                    //echo '<br>4'.$this->db->last_query();
                    if (count($get_user_regnum_info) > 0) {
                        $update_mem_data = array('isactive' => '1', 'regnumber' => $applicationNo);
                        $this->master_model->updateRecord('member_registration', $update_mem_data, array('regid' => $reg_id));

                        $user_info = $this->master_model->getRecords('member_registration', array('regid' => $reg_id), 'usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,mobile,vis_imp_cert_img,orth_han_cert_img,cer_palsy_cert_img');

                        /* get Old image Name */
                        $log_title   = "Ordinory member OLD Image :" . $reg_id;
                        $log_message = serialize($user_info);
                        $rId         = $reg_id;
                        $regNo       = $reg_id;
                        storedUserActivity($log_title, $log_message, $rId, $regNo);

                        $upd_files           = array();
                        $photo_file          = 'p_' . $applicationNo . '.jpg';
                        $sign_file           = 's_' . $applicationNo . '.jpg';
                        $proof_file          = 'pr_' . $applicationNo . '.jpg';
                        $declaration_file    = 'declaration_' . $applicationNo . '.jpg';
                        $visually_file       = 'v_' . $applicationNo . '.jpg';
                        $orthopedically_file = 'o_' . $applicationNo . '.jpg';
                        $cerebral_file       = 'c_' . $applicationNo . '.jpg';

                        $chk_photo = update_image_name("./uploads/photograph/", $user_info[0]['scannedphoto'], $photo_file); //update_image_name_helper.php
                        if ($chk_photo != "") {$upd_files['scannedphoto'] = $chk_photo;}

                        $chk_sign = update_image_name("./uploads/scansignature/", $user_info[0]['scannedsignaturephoto'], $sign_file); //update_image_name_helper.php
                        if ($chk_sign != "") {$upd_files['scannedsignaturephoto'] = $chk_sign;}

                        $chk_proof = update_image_name("./uploads/idproof/", $user_info[0]['idproofphoto'], $proof_file); //update_image_name_helper.php
                        if ($chk_proof != "") {$upd_files['idproofphoto'] = $chk_proof;}

                        $chk_declaration = update_image_name("./uploads/declaration/", $user_info[0]['declaration'], $declaration_file); //update_image_name_helper.php
                        if ($chk_declaration != "") {$upd_files['declaration'] = $chk_declaration;}

                        $chk_visually = update_image_name("./uploads/disability/", $user_info[0]['vis_imp_cert_img'], $visually_file); //update_image_name_helper.php
                        if ($chk_visually != "") {$upd_files['vis_imp_cert_img'] = $chk_visually;}

                        $chk_orthopedically = update_image_name("./uploads/disability/", $user_info[0]['orth_han_cert_img'], $orthopedically_file); //update_image_name_helper.php
                        if ($chk_orthopedically != "") {$upd_files['orth_han_cert_img'] = $chk_orthopedically;}

                        $chk_cerebral = update_image_name("./uploads/disability/", $user_info[0]['cer_palsy_cert_img'], $cerebral_file); //update_image_name_helper.php
                        if ($chk_cerebral != "") {$upd_files['cer_palsy_cert_img'] = $chk_cerebral;}

                        if (count($upd_files) > 0) {
                            $this->master_model->updateRecord('member_registration', $upd_files, array('regid' => $reg_id));
                            $log_title   = "Ordinory member PIC update :" . $reg_id;
                            $log_message = serialize($this->db->last_query());
                            $rId         = $reg_id;
                            $regNo       = $reg_id;
                            storedUserActivity($log_title, $log_message, $rId, $regNo);
                        } else {
                            $upd_files['scannedphoto']          = $photo_file;
                            $upd_files['scannedsignaturephoto'] = $sign_file;
                            $upd_files['idproofphoto']          = $proof_file;
                            $upd_files['declaration']           = $declaration_file;
                            $upd_files['vis_imp_cert_img']      = $visually_file;
                            $upd_files['orth_han_cert_img']     = $orthopedically_file;
                            $upd_files['cer_palsy_cert_img']    = $cerebral_file;
                            $this->master_model->updateRecord('member_registration', $upd_files, array('regid' => $reg_id));
                            $log_title   = "Member MANUAL PICS Update :" . $reg_id;
                            $log_message = serialize($upd_files);
                            $rId         = $reg_id;
                            $regNo       = $reg_id;
                            storedUserActivity($log_title, $log_message, $rId, $regNo);
                        }
                    }

                    //email to user
                    $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'user_register_email'));
                    // email log by pratibha
                    $log_title   = "Member emailer count:" . $reg_id;
                    $log_message = serialize($emailerstr);
                    $rId         = $reg_id;
                    $regNo       = $reg_id;
                    storedUserActivity($log_title, $log_message, $rId, $regNo);

                    if (count($emailerstr) > 0) 
                    {                        
                        include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                        $key = $this->config->item('pass_key');
                        $aes = new CryptAES();
                        $aes->set_key(base64_decode($key));
                        $aes->require_pkcs5();
                        $decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));
                        //$decpass = $aes->decrypt($user_info[0]['usrpassword']);
                        $newstring = str_replace("#application_num#", "" . $applicationNo . "", $emailerstr[0]['emailer_text']);
                        $final_str = str_replace("#password#", "" . $decpass . "", $newstring);
                        $info_arr  = array('to' => $user_info[0]['email'],
                            //'to'=>'kumartupe@gmail.com',
                            'from'                  => $emailerstr[0]['from'],
                            'subject'               => $emailerstr[0]['subject'] . ' ' . $applicationNo,
                            'message'               => $final_str,
                        );
                        // member info array log pratibha
                        $log_title   = "Ordinory member info_arr  :" . $reg_id;
                        $log_message = serialize($info_arr);
                        $rId         = $reg_id;
                        $regNo       = $reg_id;
                        storedUserActivity($log_title, $log_message, $rId, $regNo);

                        //get invoice
                        $getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo, 'pay_txn_id' => $get_user_regnum_info[0]['id']));
                        //echo $this->db->last_query();exit;

                        // This log added by pratibha B
                        $log_title   = "Ordinory member before Invoice log update :" . $reg_id;
                        $log_message = serialize($this->db->last_query());
                        $rId         = $reg_id;
                        $regNo       = $reg_id;
                        storedUserActivity($log_title, $log_message, $rId, $regNo);

                        if (count($getinvoice_number) > 0) {

                            $invoiceNumber = generate_registration_invoice_number($getinvoice_number[0]['invoice_id']);
                            if ($invoiceNumber) {
                                $invoiceNumber = $this->config->item('mem_invoice_no_prefix') . $invoiceNumber;
                            }

                            $update_data = array('invoice_no' => $invoiceNumber, 'member_no' => $applicationNo, 'transaction_no' => $transaction_no, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'));
                            $this->db->where('pay_txn_id', $get_user_regnum_info[0]['id']);
                            $this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));
                            $attachpath = genarate_reg_invoice($getinvoice_number[0]['invoice_id']);
                            // This log added by pratibha B
                            $log_title   = "Ordinory member after Invoice log update  :" . $reg_id;
                            $log_message = serialize($this->db->last_query());
                            $rId         = $reg_id;
                            $regNo       = $reg_id;
                            storedUserActivity($log_title, $log_message, $rId, $regNo);
                        }

                        if ($attachpath != '') {
                            $sms_newstring = str_replace("#application_num#", "" . $applicationNo . "", $emailerstr[0]['sms_text']);
                            $sms_final_str = str_replace("#password#", "" . $decpass . "", $sms_newstring);
                            //$this->master_model->send_sms($user_info[0]['mobile'],$sms_final_str);
                            //$this->master_model->send_sms_trustsignal(intval($user_info[0]['mobile']), $sms_final_str, 'DPDoOIwMR');
                            $this->master_model->send_sms_common_all($user_info[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

                            //if($this->Emailsending->mailsend($info_arr))
                            if ($this->Emailsending->mailsend_attch($info_arr, $attachpath)) {
                                redirect(base_url() . 'Register/acknowledge/');
                            } else {
                                redirect(base_url() . 'Register/acknowledge/');
                            }
                        } else {
                            redirect(base_url() . 'Register/acknowledge/');
                        }
                    }
                }

                //Manage Log
                $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                $this->log_model->logtransaction("billdesk", $pg_response, $responsedata['transaction_error_type']);
                redirect(base_url() . 'Register/acknowledge/');
                exit();

            } 
            elseif ($auth_status == '0002'  && $get_user_regnum_info[0]['status'] == 2) {
               
                    $update_data = array(
                        'transaction_no'      => $transaction_no,
                        'status'              => 2,
                        'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
                        'auth_code'           => '0300',
                        'bankcode'            => $bankid,
                        'paymode'             => $txn_process_type,
                        'callback'            => 'B2B',
                    );

                    $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
                    $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                    $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);

                    $this->session->set_flashdata('flsh_msg', 'Transaction under process...!');
                    redirect(base_url('register/member'));
            }
            else /* if ($transaction_error_type == 'payment_authorization_error') */
            {
                if ($get_user_regnum_info[0]['status'] != 0 && $get_user_regnum_info[0]['status'] == 2) {
                    $update_data = array(
                        'transaction_no'      => $transaction_no,
                        'status'              => 0,
                        'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
                        'auth_code'           => '0399',
                        'bankcode'            => $bankid,
                        'paymode'             => $txn_process_type,
                        'callback'            => 'B2B',
                    );

                    $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
                    $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                    $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);

                    $this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
                    redirect(base_url('register/member'));
                }

            }

        } else {
            die("Please try again...");
        }

    }

    public function validate_full_name_total_length()
    {
      $firstname = $this->input->post('firstname');
      $middlename = $this->input->post('middlename');
      $lastname = $this->input->post('lastname');

      $total_length = strlen($firstname) + strlen($middlename) + strlen($lastname);

      if ($total_length > 50) {
          $this->form_validation->set_message('validate_full_name_total_length', 'The total length of First Name, Middle Name, and Last Name must not exceed 50 characters.');
          return false;
      }
      return true;
    }

    function chek_my_ip()
    {
      echo $this->master_model->get_client_ip_master();
    }

}
