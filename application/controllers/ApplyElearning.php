<?php

/********************************************************************
 * Description: Controller for E-learning separate module 
 * Created BY: Sagar Matale
 * Created On: 10-02-2021
 * Update By: Sagar Matale
 * Updated on: 05-07-2021
 ********************************************************************/

defined('BASEPATH') or exit('No direct script access allowed');

class ApplyElearning extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->library('upload');
    $this->load->helper('upload_helper');
    $this->load->helper('master_helper');
    $this->load->helper('general_helper');
    $this->load->model('master_model');
    $this->load->model('Emailsending');
    $this->load->model('log_model');
    $this->load->model('chk_session');
    $this->load->helper('cookie');
    $this->load->model('log_model');
    $this->load->model('KYC_Log_model');
    $this->load->model('billdesk_pg_model');
    $this->chk_session->Check_mult_session();
    /*echo "<h4>Sorry for the inconvenience, we performing some maintenance at the moment</h4>";
			exit; */

    $this->valid_registrationtype_arr = array('O');

    $this->valid_ExamCode_InstituteCode_arr[] = array('exam_code_arr' => array(1032), 'inst_code_arr' => array(161)); //FOR SBI
    //$this->valid_ExamCode_InstituteCode_arr[]= array('exam_code_arr'=>array(53,54), 'inst_code_arr'=>array(1043,112)); //FOR AXIS

    header("Access-Control-Allow-Origin: *");
  }

  public function logout()
  {
    ## Clear all session values and redirect to default controller
    if ($this->session->flashdata('error') != "")
    {
      $this->session->set_flashdata('error', $this->session->flashdata('error'));
    }
    $this->session->unset_userdata('session_array');
    redirect(site_url('ApplyElearning'));
  }

  public function index($member_type = '')
  {
    $data['error'] = '';
    $data['member_type'] = $member_type;
    ## Clear session data
    $this->session->set_userdata('session_array', '');
    $this->session->unset_userdata('session_array');
    $this->session->set_userdata('session_array_global', '');
    $this->session->unset_userdata('session_array_global');

    if ($member_type == '')
    {
      $this->load->view('apply_elearning/index', $data);
    }
    else if ($member_type == 'ordinary' || $member_type == 'non_ordinary')
    {
      if (isset($_POST) && count($_POST) > 0)
      {
        $this->form_validation->set_rules('member_no', 'Registration/Membership No.', 'trim|required|xss_clean', array('required' => 'Please enter the %s'));
        /* $this->form_validation->set_rules('val1', 'Value', 'trim|xss_clean',array('required' => 'Please enter the %s'));
          $this->form_validation->set_rules('val2', 'Value', 'trim|xss_clean',array('required' => 'Please enter the %s'));
          $this->form_validation->set_rules('val3', 'Value', 'trim|callback_check_login_captcha|xss_clean',array('required' => 'Please enter the %s')); */
        $this->form_validation->set_rules('captcha_code', 'Security Code', 'trim|callback_check_login_captcha|xss_clean', array('required' => 'Please enter the %s'));

        if ($this->form_validation->run() == TRUE)
        {
          $user_info = $this->master_model->getRecords('spm_elearning_registration', array('regnumber' => $this->input->post('member_no'), 'isactive' => '1'), 'regid, regnumber,regnumber, namesub,firstname, middlename, lastname, registrationtype,email,mobile,state');

          if (empty($user_info) && count($user_info) == 0)
          {
            $user_info = $this->master_model->getRecords('member_registration', array('regnumber' => $this->input->post('member_no'), 'isactive' => '1'), 'regid, regnumber,regnumber, namesub,firstname, middlename, lastname, registrationtype,email,mobile,state');
          }

          //print_r($user_info);
          if (!empty($user_info) && count($user_info) > 0)
          {
            redirect(site_url('ApplyElearning/register/' . base64_encode($this->input->post('member_no')) . '?login_type=global'));
          }
          else
          {
            $data['error'] = "Invalid Membership/Registration No. Please register using following form. ";
          }
        }
      }

      $this->load->model('Captcha_model');
      $data['captcha_img'] = $this->Captcha_model->generate_captcha_img('ELEARNING_SPM');
      $data['login_type'] = 'global';
      $this->load->view('apply_elearning/apply_elearning', $data);
    }
  }

  public function applyExam()
  {
    $data['error'] = '';
    $data['member_type'] = 'ordinary';
    ## Clear session data
    $this->session->set_userdata('session_array', '');
    $this->session->unset_userdata('session_array');
    $this->session->set_userdata('session_array_sbi', '');
    $this->session->unset_userdata('session_array_sbi');

    if (isset($_POST) && count($_POST) > 0)
    {
      $this->form_validation->set_rules('member_no', 'Registration/Membership No.', 'trim|required|xss_clean', array('required' => 'Please enter the %s'));
      $this->form_validation->set_rules('captcha_code', 'Security Code', 'trim|callback_check_login_captcha_sbi|xss_clean', array('required' => 'Please enter the %s'));

      if ($this->form_validation->run() == TRUE)
      {
        $user_info = $this->master_model->getRecords('spm_elearning_registration', array('regnumber' => $this->input->post('member_no'), 'isactive' => '1'), 'regid, regnumber,regnumber, namesub,firstname, middlename, lastname, registrationtype,email,mobile,state');

        if (empty($user_info) && count($user_info) == 0)
        {
          $user_info = $this->master_model->getRecords('member_registration', array('regnumber' => $this->input->post('member_no'), 'isactive' => '1', 'associatedinstitute' => '161'), 'regid, regnumber,regnumber, namesub,firstname, middlename, lastname, registrationtype,email,mobile,state,associatedinstitute');
        }

        if (!empty($user_info) && count($user_info) > 0)
        {
          redirect(site_url('ApplyElearning/register/' . base64_encode($this->input->post('member_no')) . '?login_type=sbi'));
        }
        else
        {
          $data['error'] = "Invalid Membership/Registration No. Please register using following form. ";
        }
      }
    }

    $this->load->model('Captcha_model');
    $data['captcha_img'] = $this->Captcha_model->generate_captcha_img('ELEARNING_SBI');
    $data['login_type']  = 'sbi';
    $this->load->view('apply_elearning/apply_elearning', $data);
  }


  function generate_captcha_ajax()
  {
    $session_name = $_POST['session_name'];
    if (isset($_POST['session_name']) && $_POST['session_name'] != "")
    {
      $session_name = $this->security->xss_clean(trim($this->input->post('session_name')));
    }

    $this->load->model('Captcha_model');
    echo $captcha_img = $this->Captcha_model->generate_captcha_img($session_name);
  }

  public function check_captcha_code_ajax()
  {
    if (isset($_POST) && count($_POST) > 0)
    {
      $session_name = $_POST['session_name'];
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

    if (isset($_POST) && $_POST['member_no'] != "" && $_POST['member_type'] != "")
    {
      $member_no   = $this->security->xss_clean(trim($this->input->post('member_no')));
      $member_type = $this->security->xss_clean(trim($this->input->post('member_type')));
      $loginType   = $this->security->xss_clean(trim($this->input->post('login_type')));
      $member_info = array();
      if ($member_type == 'ordinary')
      {
        if ($loginType == 'global')
        {
          $member_info = $this->master_model->getRecords('member_registration', array('regnumber' => $member_no, 'isactive' => '1'), 'regid');
        }
        else
        {
          $member_info = $this->master_model->getRecords('member_registration', array('regnumber' => $member_no, 'isactive' => '1', 'associatedinstitute' => '161'), 'regid');
        }
      }
      else if ($member_type == 'non_ordinary')
      {
        $member_info = $this->master_model->getRecords('spm_elearning_registration', array('regnumber' => $member_no, 'isactive' => '1', 'registrationtype' => '1'), 'regid');
      }

      if (!empty($member_info) && count($member_info) > 0)
      {
        $result['flag'] = 'success';
      }
    }

    echo json_encode($result);
  }

  public function check_email_exist_ajax()
  {
    if (isset($_POST) && $_POST['email'] != "")
    {
      $email = strtolower($this->input->post('email'));
      $member_no = $this->input->post('member_no');

      if ($member_no != "" && $member_no != "0")
      {
        $this->db->where('regnumber != ', base64_decode($member_no));
      }

      $member_info = $this->master_model->getRecords('spm_elearning_registration', array('email' => $email, 'isactive' => '1'), 'regid');
      if (count($member_info) > 0)
      {
        echo "false";
      }
      else
      {
        echo "true";
      }
    }
    else
    {
      echo "false";
    }
  }

  public function check_mobile_exist_ajax()
  {
    if (isset($_POST) && $_POST['mobile'] != "")
    {
      $mobile = $this->input->post('mobile');
      $member_no = $this->input->post('member_no');

      if ($member_no != "" && $member_no != "0")
      {
        $this->db->where('regnumber != ', base64_decode($member_no));
      }

      $member_info = $this->master_model->getRecords('spm_elearning_registration', array('mobile' => $mobile, 'isactive' => '1'), 'regid');
      if (count($member_info) > 0)
      {
        echo "false";
      }
      else
      {
        echo "true";
      }
    }
    else
    {
      echo "false";
    }
  }

  function get_already_purchase_sub_data($member_no = 0)
  {
    $already_purchase_subjects = array();
    /* $already_purchase_sub_data = $this->master_model->getRecords('spm_elearning_member_subjects',array('regnumber'=>(string)$member_no, 'status'=>'1'),'el_sub_id, subject_code, subject_description');
      if(count($already_purchase_sub_data) > 0)
      {
        foreach($already_purchase_sub_data as $already_purchase_sub_res)
        {
          $already_purchase_subjects[$already_purchase_sub_res['subject_code']] = $already_purchase_sub_res;
        }
      } */
    return $already_purchase_subjects;
  }

  public function register($member_no = 0)
  {
    $data['error'] = '';
    $data['member_no'] = $member_no;
    $data['member_info'] = $already_purchase_subjects = array();

    $login_type = $_GET['login_type'];

    if ($member_no != '0')
    {
      $member_no = base64_decode($member_no);

      $member_info = $this->master_model->getRecords('spm_elearning_registration', array('regnumber' => $member_no, 'isactive' => '1'), 'regid, regnumber,regnumber, namesub,firstname, middlename, lastname, registrationtype,email,mobile,state');

      if (empty($member_info) || count($member_info) == 0)
      {
        $member_info = $this->master_model->getRecords('member_registration', array('regnumber' => $member_no, 'isactive' => '1'), 'regid, regnumber,regnumber, namesub,firstname, middlename, lastname,
					registrationtype,email,mobile,state');
      }

      $data['member_info'] = $member_info;

      if (empty($member_info) || count($member_info) == 0)
      {
        redirect(site_url('ApplyElearning/register'));
      }

      $already_purchase_subjects = $this->get_already_purchase_sub_data($member_no);
    }
    $data['already_purchase_subjects'] = $already_purchase_subjects;

    ## Posted data
    if (isset($_POST) && count($_POST) > 0)
    {
      ////Priyanka d - 01-feb-23 - diabled jaiib & caiib
      // if($_POST['exam_name']==$this->config->item('examCodeCaiib') || $_POST['exam_name']==$this->config->item('examCodeCaiib'))
      // 	return 1; 
      ## Check server side valiations 
      //$this->form_validation->set_rules('xxx', 'Sub Name', 'required');
      $this->form_validation->set_rules('namesub', 'Sub Name', 'trim|required|xss_clean', array('required' => 'Please enter the %s'));
      $this->form_validation->set_rules('firstname', 'First Name', 'trim|required|xss_clean', array('required' => 'Please enter the %s'));
      $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean', array('required' => 'Please enter the %s'));
      $this->form_validation->set_rules('mobile', 'Mobile Number', 'trim|required|xss_clean', array('required' => 'Please enter the %s'));

      $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean', array('required' => 'Please select %s'));
      $this->form_validation->set_rules('exam_name', 'Exam', 'trim|required|xss_clean', array('required' => 'Please select %s'));
      $this->form_validation->set_rules('el_subject[]', 'Elearning Subjects', 'trim|required|xss_clean', array('required' => 'Please select %s'));

      if ($this->form_validation->run() == TRUE)
      { //echo '<pre>working='; print_r($_POST); echo '</pre>'; exit;
        if ($this->input->post('member_no') == "")
          $member_no = 0;
        else
          $member_no = $this->input->post('member_no');

        ## Set session array for preview
        $session_array = array(
          'member_no'  => $member_no,
          'namesub'  => $this->input->post('namesub'),
          'firstname'  => $this->input->post('firstname'),
          'middlename'  => $this->input->post('middlename'),
          'lastname'  => $this->input->post('lastname'),
          'email'  => strtolower($this->input->post('email')),
          'mobile'  => $this->input->post('mobile'),
          'state'  => $this->input->post('state'),
          'exam_name' => $this->input->post('exam_name'),
          'el_subject'  => implode(",", $this->input->post('el_subject')),
          'total_fee'  => $this->input->post('total_fee'),
          'login_type' => $login_type
        );

        $this->session->set_userdata('session_array_' . $login_type, $session_array);

        redirect(site_url('ApplyElearning/preview?login_type=' . $login_type));
      }
    }

    if ($this->session->has_userdata('session_array_' . $login_type))
    {
      $data['session_data'] = $this->session->userdata('session_array_' . $login_type);
    }

    $data['exam_data'] = $this->get_active_exam_data($member_no, $login_type); ##START : Get Active Exams

    ## Get Subjects
    $data['subjects'] = $this->master_model->getRecords('spm_elearning_subject_master', array('subject_delete' => '0'));

    ## Get States
    $data['states'] = $this->master_model->getRecords('state_master', array('state_master.state_delete' => '0'));
    $this->load->view('apply_elearning/register', $data);
  }

  public function get_elearning_subjects()
  {
    $result['flag'] = "error";
    $already_purchase_subjects = array();
    if (isset($_POST) && count($_POST) > 0)
    {
      $member_no = base64_decode(trim($this->security->xss_clean($this->input->post('member_no'))));
      $exam_name = trim($this->security->xss_clean($this->input->post('exam_name')));
      //$exam_name_arr = explode("##", $exam_name);

      $result['flag'] = "success";
      $response = '';

      if ($exam_name != "") //count($exam_name_arr) == 2
      {
        //$exam_code = $exam_name_arr[0]; 
        //$exam_period = $exam_name_arr[1]; 
        $exam_code = $exam_name;

        $subject_data = $this->master_model->getRecords('spm_elearning_subject_master', array('exam_code' => $exam_code, /* 'exam_period' => $exam_period, */ 'subject_delete' => '0'));

        $availeble_subjects_cnt = 0;
        if (count($subject_data) > 0)
        {
          $already_purchase_subjects = $this->get_already_purchase_sub_data($member_no);

          foreach ($subject_data as $sub)
          {
            if (!array_key_exists($sub['subject_code'], $already_purchase_subjects))
            {
              $response .= '
                  <div>
                    <label>
                      <input type="checkbox" name="el_subject[]" value="' . $sub['subject_code'] . '" class="el_sub_prop" required id="el_subject_' . $sub['subject_code'] . '">' . $sub['subject_description'] . '
                    </label>
                  </div>';
              $availeble_subjects_cnt++;
            }
          }
          if ($availeble_subjects_cnt == 0)
          {
            $response .= '<label>You have already purchased all the elearning subjects</label>';
          }
        }
      }
      $result['response'] = $response;
      //$result['already_purchase_subjects'] = $already_purchase_subjects;
      $result['availeble_subjects_cnt'] = $availeble_subjects_cnt;
    }
    echo json_encode($result);
  }

  public function preview()
  {
    //echo '<pre>'; print_r($_SESSION); echo '</pre>'; 
    $member_no = 0;
    $already_purchase_subjects = array();

    $login_type = $_GET['login_type'];

    if ($this->session->has_userdata('session_array_' . $login_type))
    {
      $member_no = (isset($this->session->userdata['session_array_' . $login_type]['member_no'])) ? $this->session->userdata['session_array_' . $login_type]['member_no'] : '';

      if ($member_no != '0')
      {
        $already_purchase_subjects = $this->get_already_purchase_sub_data($member_no);
      }
    }

    $this->check_member_valid_exam_selection($login_type); //ADDITONAL CHECK - IF NON SBI MEMBER TRY TO GET SBI EXAMS

    $data['member_no'] = base64_encode($member_no);
    $data['member_info'] = array();
    if ($this->session->has_userdata('session_array_' . $login_type))
    {
      $data['session_data'] = $this->session->userdata('session_array_' . $login_type);
    }

    $data['already_purchase_subjects'] = $already_purchase_subjects;

    ## Get Active Exams
    //$data['exam_data'] = $this->master_model->getRecords('spm_elearning_exam_master', array('is_active' => '1'));
    $data['exam_data'] = $this->get_active_exam_data($member_no, $login_type);

    ## Get Subjects
    $data['subjects'] = $this->master_model->getRecords('spm_elearning_subject_master', array('subject_delete' => '0'));

    ## Get States
    $data['states'] = $this->master_model->getRecords('state_master', array('state_master.state_delete' => '0'));
    // echo "<pre>";
    // print_r($data); exit;
    $this->load->view('apply_elearning/register', $data);
  }

  public function get_active_exam_data($member_no = '', $login_type = '')
  {
    $exam_data = array();
    $valid_ExamCode_InstituteCode_arr = $this->valid_ExamCode_InstituteCode_arr;
    $exclude_exam_code_str = '';

    if (count($valid_ExamCode_InstituteCode_arr) > 0)
    {
      foreach ($valid_ExamCode_InstituteCode_arr as $res)
      {
        $exclude_exam_code_str .= implode(",", $res['exam_code_arr']) . ",";
      }
    }

    if ($login_type == '' || $login_type == 'global')
    {
      ##START :  Get Active Exams      
      //START : GET EXAM DATA WHICH IS NOT AVAILABLE IN $this->valid_ExamCode_InstituteCode_arr

      if ($exclude_exam_code_str != '')
      {
        $exclude_exam_code_str = rtrim($exclude_exam_code_str, ",");
        $this->db->where_not_in('exam_code', $exclude_exam_code_str, FALSE);
      }
      $exam_data = $this->master_model->getRecords('spm_elearning_exam_master', array('is_active' => '1'));
      //END : GET EXAM DATA WHICH IS NOT AVAILABLE IN $this->valid_ExamCode_InstituteCode_arr	
    }
    elseif ($login_type == 'sbi')
    {
      //START : GET EXAM DATA AS PER MEMBER INSTITUTE WHICH IS AVAILABLE IN $this->valid_ExamCode_InstituteCode_arr & MERGE IT WITH ABOVE MAIN ARRAY

      if ($exclude_exam_code_str != '')
      {
        if ($member_no != '')
        {
          $this->db->where_in('registrationtype', $this->valid_registrationtype_arr);
          $member_data = $this->master_model->getRecords('member_registration', array('regnumber' => $member_no, 'isactive' => '1'), 'regid, regnumber,namesub, firstname, middlename, lastname, registrationtype, email, mobile, associatedinstitute ');

          if (count($member_data) > 0)
          {
            if (count($valid_ExamCode_InstituteCode_arr) > 0)
            {
              foreach ($valid_ExamCode_InstituteCode_arr as $res)
              {
                if (in_array($member_data[0]['associatedinstitute'], $res['inst_code_arr']))
                {
                  $this->db->where_in('exam_code', $res['exam_code_arr']);
                  $exam_data_new = $this->master_model->getRecords('spm_elearning_exam_master', array('is_active' => '1'));
                  if (count($exam_data_new) > 0)
                  {
                    $exam_data = array_merge($exam_data, $exam_data_new);
                  }
                }
              }
            }
          }
        }
      }

      //END : GET EXAM DATA AS PER MEMBER INSTITUTE WHICH IS AVAILABLE IN $this->valid_ExamCode_InstituteCode_arr & MERGE IT WITH ABOVE MAIN ARRAY
    }

    return $exam_data;
  } ##END : Get Active Exams

  //START : ADDITONAL CHECK - IF NON SBI MEMBER TRY TO GET SBI EXAMS
  public function check_member_valid_exam_selection($login_type = '')
  {
    $member_no = (isset($this->session->userdata['session_array_' . $login_type]['member_no'])) ? $this->session->userdata['session_array_' . $login_type]['member_no'] : '';
    $exam_code = (isset($this->session->userdata['session_array_' . $login_type]['exam_name'])) ? $this->session->userdata['session_array_' . $login_type]['exam_name'] : '';
    $login_type = (isset($this->session->userdata['session_array_' . $login_type]['login_type'])) ? $this->session->userdata['session_array_' . $login_type]['login_type'] : '';

    $is_valid_flag = 0;
    if ($member_no != "" && $exam_code != '')
    {
      $active_exam_data = $this->get_active_exam_data($member_no, $login_type);

      if (count($active_exam_data) > 0)
      {
        foreach ($active_exam_data as $res)
        {
          if ($res['exam_code'] == $exam_code)
          {
            $is_valid_flag = 1;
          }
        }
      }
    }
    else
    {
      $is_valid_flag = 1;
    }

    if ($is_valid_flag == 0)
    {
      $this->session->set_flashdata('error', 'This certificate course is applicable for SBI staff only. In case you have changed your organisation to SBI, kindly update the bank name in your membership profile');
      redirect(site_url('ApplyElearning'));
    }
  } //END : ADDITONAL CHECK - IF NON SBI MEMBER TRY TO GET SBI EXAMS

  public function check_login_captcha()
  {
    /* $val1 = $this->input->post('val1');		  
			$val2 = $this->input->post('val2');		  
			$val3 = $this->input->post('val3');
			$add_val = ($val1+$val2);
			
			if($val1 == "" || $val2 == "" || $val3 == "" || $add_val != $val3)
			{
				$this->form_validation->set_message('check_login_captcha', 'Please enter correct answer');	
				return FALSE;
			}
			else
			{
				return TRUE;								
			} */

    $session_name = 'ELEARNING_SPM';
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

  public function check_login_captcha_sbi()
  {
    /* $val1 = $this->input->post('val1');		  
			$val2 = $this->input->post('val2');		  
			$val3 = $this->input->post('val3');
			$add_val = ($val1+$val2);
			
			if($val1 == "" || $val2 == "" || $val3 == "" || $add_val != $val3)
			{
				$this->form_validation->set_message('check_login_captcha', 'Please enter correct answer');	
				return FALSE;
			}
			else
			{
				return TRUE;								
			} */

    $session_name = 'ELEARNING_SBI';
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

  function get_all_subject_fee_arr($subject_code = '')
  {
    $this->db->join('spm_elearning_fee_master fm', 'fm.subject_code = sm.subject_code', 'INNER', FALSE);
    $this->db->where_in('sm.subject_code', $subject_code, FALSE);
    $all_subject_fee_data = $this->master_model->getRecords('spm_elearning_subject_master sm', array('sm.subject_delete' => 0), 'sm.subject_code, sm.subject_description, fm.fee_amount, fm.sgst_amt, fm.cgst_amt, fm.igst_amt, fm.cs_tot, fm.igst_tot');

    $all_subject_fee_arr = array();
    if (count($all_subject_fee_data) > 0)
    {
      foreach ($all_subject_fee_data as $all_subject_res)
      {
        $all_subject_fee_arr[$all_subject_res['subject_code']] = $all_subject_res;
      }
    }
    return $all_subject_fee_arr;
  }

  public function add_record()
  {
    $data = array();
    $login_type = $_GET['login_type'];
    $this->check_member_valid_exam_selection($login_type); //ADDITONAL CHECK - IF NON SBI MEMBER TRY TO GET SBI EXAMS

    $this->db->order_by('regid', 'DESC');
    $chkEmailExist = $this->master_model->getRecords('spm_elearning_registration', array('email' => strtolower($this->session->userdata['session_array_' . $login_type]['email'])), 'regid, email, regnumber, isactive'); //, 'isactive'=>1
    $exam_code = $this->session->userdata['session_array_' . $login_type]['exam_name'];

    $regnumber = '';
    if (count($chkEmailExist) > 0)
    {
      $regid = $chkEmailExist[0]['regid'];
      $regnumber = $chkEmailExist[0]['regnumber'];

      if ($chkEmailExist[0]['isactive'] == '0')
      {
        $up_createdon['createdon'] = date('Y-m-d H:i:s');
        $this->master_model->updateRecord('spm_elearning_registration', $up_createdon, array('regid' => $regid, 'regnumber' => $regnumber));
      }
    }
    else
    {
      /* echo '<pre>'; print_r($_POST); echo '</pre>'; 
        echo '<pre>'; print_r($_SESSION); echo '</pre>'; 
        exit; */

      //$exam_name_arr = explode("##", $this->session->userdata['session_array']['exam_name']);
      //$exam_code = $exam_name_arr[0]; 
      //$exam_period = $exam_name_arr[1];        

      ## Insert E-learning registration Record	 
      $insert_info['namesub'] = $this->session->userdata['session_array_' . $login_type]['namesub'];
      $insert_info['firstname'] = $this->session->userdata['session_array_' . $login_type]['firstname'];
      $insert_info['middlename'] = $this->session->userdata['session_array_' . $login_type]['middlename'];
      $insert_info['lastname'] = $this->session->userdata['session_array_' . $login_type]['lastname'];
      $insert_info['email'] = strtolower($this->session->userdata['session_array_' . $login_type]['email']);
      $insert_info['mobile'] = $this->session->userdata['session_array_' . $login_type]['mobile'];
      $insert_info['state'] = $this->session->userdata['session_array_' . $login_type]['state'];

      if ($this->session->userdata['session_array_' . $login_type]['member_no'] == '' || $this->session->userdata['session_array_' . $login_type]['member_no'] == '0')
      {
        $insert_info['isactive'] = '0';
      }
      else
      {
        $insert_info['isactive'] = '0';
        $insert_info['regnumber'] = $regnumber = $this->session->userdata['session_array_' . $login_type]['member_no'];
      }
      $insert_info['createdon'] = date('Y-m-d H:i:s');
      $regid = $this->master_model->insertRecord('spm_elearning_registration', $insert_info, true);

      $log_title = "E-learning member insert array :" . $regid;
      $log_message = serialize($insert_info);
      $rId = $regid;
      $regNo = $regid;
      storedUserActivity($log_title, $log_message, $rId, $regNo);

      ## Note: This is for only E-learning module
      /* $regnumber = generate_eLearning_memreg($regid);
					
					$reg_type = 1;
					$update_data['registrationtype'] = $reg_type; 
					$update_data['regnumber'] = $regnumber; 
				$this->master_model->updateRecord('spm_elearning_registration',$update_data,array('regid'=>$regid)); */

      /* $log_title ="E-learning member UPDATE array :".$regid;
					$log_message = serialize($update_data);
					$rId = $regid;
				storedUserActivity($log_title, $log_message, $rId, $regnumber); */
    }

    $this->session->userdata['session_array_' . $login_type]['regid'] = $regid;
    $this->session->userdata['session_array_' . $login_type]['member_no'] = $regnumber;

    //$ChkExist = $this->master_model->getRecords('spm_elearning_registration',array('regnumber'=>$regnumber));
    $ChkExist = $this->master_model->getRecords('spm_elearning_registration', array('regid' => $regid));
    if (count($ChkExist) == 0)
    {
      $this->session->set_flashdata('error', 'Invalid credential');
      redirect(site_url('ApplyElearning/logout'));
    }

    ## Add Subjects
    $sub_arr =  explode(",", $this->session->userdata['session_array_' . $login_type]['el_subject']);
    $el_subject_cnt = count($sub_arr);

    if (/* isset($regnumber)  &&  */isset($regid)  && $el_subject_cnt > 0)
    {
      $all_subject_fee_arr = $this->get_all_subject_fee_arr($this->session->userdata['session_array_' . $login_type]['el_subject']);

      $el_sub_id_str = '';
      foreach ($sub_arr as $subVal)
      {
        $inser_sub['regid'] = $regid;
        $inser_sub['regnumber'] = $regnumber;
        $inser_sub['pt_id'] = '';
        $inser_sub['exam_code'] = $exam_code;
        $inser_sub['subject_code'] = $subVal;

        $subject_description = $fee_amount = $sgst_amt = $cgst_amt = $igst_amt = $cs_tot = $igst_tot = '';
        if (array_key_exists($subVal, $all_subject_fee_arr))
        {
          $subject_description = $all_subject_fee_arr[$subVal]['subject_description'];

          $fee_amount = $sgst_amt = $cgst_amt = $igst_amt = $cs_tot = $igst_tot = 0;

          $state_code = $ChkExist[0]['state'];
          if ($state_code == 'MAH') //fee_amount, sgst_amt, cgst_amt, cs_tot
          {
            $cs_tot = $all_subject_fee_arr[$subVal]['cs_tot'];
            $cgst_amt = $all_subject_fee_arr[$subVal]['cgst_amt'];
            $sgst_amt = $all_subject_fee_arr[$subVal]['sgst_amt'];
          }
          else //fee_amount, igst_amt, igst_tot
          {
            $igst_amt = $all_subject_fee_arr[$subVal]['igst_amt'];
            $igst_tot = $all_subject_fee_arr[$subVal]['igst_tot'];
          }

          $fee_amount = $all_subject_fee_arr[$subVal]['fee_amount'];
          //$sgst_amt = $all_subject_fee_arr[$subVal]['sgst_amt'];
          //$cgst_amt = $all_subject_fee_arr[$subVal]['cgst_amt'];
          //$igst_amt = $all_subject_fee_arr[$subVal]['igst_amt'];
          //$cs_tot = $all_subject_fee_arr[$subVal]['cs_tot'];
          //$igst_tot = $all_subject_fee_arr[$subVal]['igst_tot'];
        }

        $inser_sub['subject_description'] = $subject_description;
        $inser_sub['fee_amount'] = $fee_amount;
        $inser_sub['sgst_amt'] = $sgst_amt;
        $inser_sub['cgst_amt'] = $cgst_amt;
        $inser_sub['igst_amt'] = $igst_amt;
        $inser_sub['cs_tot'] = $cs_tot;
        $inser_sub['igst_tot'] = $igst_tot;
        $inser_sub['status'] = 0;
        $inser_sub['created_on'] = date('y-m-d H:i:s');

        $el_sub_id = $this->master_model->insertRecord('spm_elearning_member_subjects', $inser_sub, true);

        $el_sub_id_str .= $el_sub_id . ',';
      } //froeach
    } //if

    $this->session->userdata['session_array_' . $login_type]['el_sub_id_str'] = rtrim($el_sub_id_str, ",");

    /*$this->db->where('regnumber',$this->session->userdata('member_no'));
				$this->db->where('exam_code',$this->session->userdata('memexcode'));
				$this->db->where('exam_period',$this->session->userdata('memexprd'));
				$this->db->where('pay_status',1);
				$this->db->where('elearning_flag','Y');
				$chk_already_apppy_member_exam = $this->master_model->getRecords('member_exam','','id,app_update');			
				if(count($chk_already_apppy_member_exam) > 0 )
				{ 
				$this->session->set_flashdata('error','You already updated your application');
				redirect(site_url('ApplyDbfElearning/logout')); 
			}*/

    if ($el_subject_cnt > 0)
    {
      if ($this->config->item('exam_apply_gateway') == 'sbi')
      {
        /*Payment Check Code - Bhushan */
        $check_payment_val = check_payment_status($this->session->userdata('member_no'));

        if ($check_payment_val == 1)
        {
          $this->session->set_flashdata('error', 'Your transaction is in process. Please wait for some time!');
          redirect(site_url('ApplyElearning/preview'));
        }
        else
        {
          redirect(site_url('ApplyElearning/sbi_make_payment?login_type=' . $login_type));
        }
      }
    }
    else
    {
      redirect(site_url('ApplyElearning/logout'));
    }
  }

  public function sbi_make_payment()
  {
    /* Payment Code : Bhushan */
    //$this->chk_session->Mem_checklogin_external_user();
    $cgst_rate = $sgst_rate = $igst_rate = $tax_type = '';
    $cgst_amt = $sgst_amt = $igst_amt = '';
    $cs_total = $igst_total = '';
    $getstate = $getcenter = $getfees = array();
    $valcookie = applyexam_get_cookie();
    $total_el_amount = 0;
    $el_subject_cnt = 0;
    $total_elearning_amt = 0;
    $login_type = $_GET['login_type'];
    if ($valcookie)
    {
      ////redirect(site_url('ApplyElearning/logout'));
    }
    $sub_arr = $this->session->userdata['session_array_' . $login_type]['el_subject'];
    $el_subject_cnt = count(explode(",", $sub_arr));

    if (count($el_subject_cnt) <= 0)
    {
      $this->session->set_flashdata('error', 'Error occurred');
      redirect(site_url('ApplyElearning/logout'));
    }

    //$ChkExist = $this->master_model->getRecords('spm_elearning_registration',array('regnumber'=>$this->session->userdata['session_array_'.$login_type]['member_no']));
    $ChkExist = $this->master_model->getRecords('spm_elearning_registration', array('regid' => $this->session->userdata['session_array_' . $login_type]['regid']));

    if (count($ChkExist) == 0)
    {
      $this->session->set_flashdata('error', 'Invalid credential');
      redirect(site_url('ApplyElearning/logout'));
    }

    if (isset($_POST['processPayment']) && $_POST['processPayment'])
    {
      //echo '<pre>'; print_r($_POST); echo '</pre>'; exit;

      $pg_name = 'billdesk';

      //$checkpayment=$this->master_model->getRecords('payment_transaction',array('member_regnumber'=>$this->session->userdata['session_array_'.$login_type]['member_no'],'status'=>'2','pay_type'=>'20'),'',array('id'=>'DESC'));
      $checkpayment = $this->master_model->getRecords('payment_transaction', array('ref_id' => $this->session->userdata['session_array_' . $login_type]['regid'], 'status' => '2', 'pay_type' => '20'), '', array('id' => 'DESC'));

      if (count($checkpayment) > 0)
      {
        $endTime = date("Y-m-d H:i:s", strtotime("+15 minutes", strtotime($checkpayment[0]['date'])));
        $current_time = date("Y-m-d H:i:s");
        if (strtotime($current_time) <= strtotime($endTime))
        {
          $this->session->set_flashdata('error', 'Wait your transaction is under process!.');
          redirect(site_url('ApplyElearning/preview'));
        }
      }

      $regid = $this->session->userdata['session_array_' . $login_type]['regid'];
      $regno = $this->session->userdata['session_array_' . $login_type]['member_no'];

      $amount = $total_elearning_amt = 0;
      $el_subject = $this->session->userdata['session_array_' . $login_type]['el_subject'];
      $el_subject_arr = explode(",", $el_subject);
      $el_subject_cnt = count($el_subject_arr);

      if (isset($el_subject) && $el_subject_cnt > 0)
      {
        /*$el_amount = get_el_ExamFeeFree($this->session->userdata['examinfo']['selCenterName'],$eprid,$excd,$memtype,$elearning_flag);
							$total_elearning_amt = $el_amount * $el_subject_cnt;
						$amount = $amount + $total_elearning_amt;*/
        $amount = $this->session->userdata['session_array_' . $login_type]['total_fee'];
      }

      if ($amount == 0 || $amount == '')
      {
        $this->session->set_flashdata('error', 'Fee can not be zero(0) or Blank!!');
        redirect(base_url() . 'ApplyElearning/logout');
      }
      
      $all_subject_fee_arr = $this->get_all_subject_fee_arr($this->session->userdata['session_array_' . $login_type]['el_subject']);
      $state_code = $this->session->userdata['session_array_' . $login_type]['state'];
      $calculated_total_amount = 0;
      if ($state_code == 'MAH') //fee_amount, sgst_amt, cgst_amt, cs_tot
      {
        //set a rate (e.g 9%,9% or 18%)
        $cgst_rate = $this->config->item('cgst_rate');
        $sgst_rate = $this->config->item('sgst_rate');

        //set an total amount
        if (isset($el_subject) && $el_subject_cnt > 0)
        {
          /* $amount_base = $amount * $el_subject_cnt;
						$cs_total = $amount; 
						$cgst_amt = $amount * $el_subject_cnt; 
						$sgst_amt = $amount * $el_subject_cnt;  */

          $amount_base = $cs_total = $cgst_amt = $sgst_amt = 0;
          foreach ($el_subject_arr as $el_subject_res)
          {
            if (array_key_exists($el_subject_res, $all_subject_fee_arr))
            {
              $amount_base = $amount_base + $all_subject_fee_arr[$el_subject_res]['fee_amount'];
              $cs_total = $cs_total + $all_subject_fee_arr[$el_subject_res]['cs_tot'];
              $cgst_amt = $cgst_amt + $all_subject_fee_arr[$el_subject_res]['cgst_amt'];
              $sgst_amt = $sgst_amt + $all_subject_fee_arr[$el_subject_res]['sgst_amt'];
            }
          }

          $calculated_total_amount = $cs_total;
        }

        $tax_type = 'Intra';
      }
      else //fee_amount, igst_amt, igst_tot
      {
        $igst_rate = $this->config->item('igst_rate');

        if (isset($el_subject_arr) && $el_subject_arr > 0)
        {
          /* $amount_base = $amount * $el_subject_cnt; 
						$igst_total = $amount; 
						$igst_amt = $amount * $el_subject_cnt; */

          $amount_base = $igst_total = $igst_amt = 0;
          foreach ($el_subject_arr as $el_subject_res)
          {
            if (array_key_exists($el_subject_res, $all_subject_fee_arr))
            {
              $amount_base = $amount_base + $all_subject_fee_arr[$el_subject_res]['fee_amount'];
              $igst_total = $igst_total + $all_subject_fee_arr[$el_subject_res]['igst_tot'];
              $igst_amt = $igst_amt + $all_subject_fee_arr[$el_subject_res]['igst_amt'];
            }
          }

          $calculated_total_amount = $igst_total;
        }

        $tax_type = 'Inter';
      }

      if ($amount != $calculated_total_amount)
      {
        $this->session->set_flashdata('error', 'Error occurred. Please try again after sometime.');
        redirect(base_url() . 'ApplyElearning/logout');
      }


      $pg_flag = "IIBFELS"; //E-learning Separate module
      //$ger_ref_id = $this->master_model->getRecords('spm_elearning_registration',array('regnumber'=>$regno),'regid');

      $insert_data = array(
        'member_regnumber' => $regno,
        'exam_code'        => $this->session->userdata['session_array_' . $login_type]['exam_name'],
        'amount'           => $amount,
        'gateway'          => "sbiepay",
        'date'             => date('Y-m-d H:i:s'),
        'pay_type'         => '20',
        //'ref_id'         => $mem_exam_id, // Primary Key of Member Exam
        'ref_id'           => $regid, //$ger_ref_id[0]['regid'], // Primary Key of Member Exam
        'description'      => 'E-learning Member',
        'status'           => '2',
        'pg_flag'          => $pg_flag
      );

      $pt_id = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
      $ref4 = ($pt_id) . date("Ymd");

      /* Get Order Id */
      $MerchantOrderNo = sbi_exam_order_id($pt_id);

      // payment gateway custom fields -
      $custom_field = $MerchantOrderNo . "^iibfexam^" . $pg_flag . "^" . $regno;
      if ($regno == '')
      {
        $custom_field_billdesk = $MerchantOrderNo . "-iibfexam-" . $pg_flag . "-" . $regid;
      }
      else
      {

        $custom_field_billdesk = $MerchantOrderNo . "-iibfexam-" . $pg_flag . "-" . $regno;
      }

      // update receipt no. in payment transaction -
      $update_data = array('receipt_no' => $MerchantOrderNo, 'pg_other_details' => $custom_field);
      $this->master_model->updateRecord('payment_transaction', $update_data, array('id' => $pt_id));

      //UPDATE pt_id IN spm_elearning_member_subjects table
      $el_sub_id_arr = explode(",", $this->session->userdata['session_array_' . $login_type]['el_sub_id_str']);
      if (count($el_sub_id_arr) > 0)
      {
        foreach ($el_sub_id_arr as $el_sub_id_res)
        {
          $this->master_model->updateRecord('spm_elearning_member_subjects', array('pt_id' => $pt_id), array('el_sub_id' => $el_sub_id_res, 'status!=' => '1'));
        }
      }

      /* Code For Invoice 
					$getcenter=$this->master_model->getRecords('center_master',array('exam_name'=>$excd,'center_code'=>$this->session->userdata['examinfo']['selCenterName'],'exam_period'=>$eprid,'center_delete'=>'0'));
					
					if(count($getcenter) > 0)
					{
					//get state code,state name,state number.
					$getstate=$this->master_model->getRecords('state_master',array('state_code'=>$getcenter[0]['state_code'],'state_delete'=>'0'));
					
					//call to helper (fee_helper)
					$getfees = getExamFeedetailsEL($this->session->userdata['examinfo']['selCenterName'],$eprid,$excd,$memtype,$elearning_flag);
				} */
      
        /*if($getstate[0]['exempt']=='E')
					{
					$cgst_rate=$sgst_rate=$igst_rate='';	
					$cgst_amt=$sgst_amt=$igst_amt='';	
				}*/

      $getstate = $this->master_model->getRecords('state_master', array('state_code' => $state_code, 'state_delete' => '0'));

      $ins_state_code = $ins_state_name = '';
      if (count($getstate) > 0)
      {
        $ins_state_code = $getstate[0]['state_no'];
        $ins_state_name = $getstate[0]['state_name'];
      }

      $gst_no = '0';
      $invoice_insert_array = array(
        'pay_txn_id' => $pt_id,
        'receipt_no' => $MerchantOrderNo,
        'exam_code' => $this->session->userdata['session_array_' . $login_type]['exam_name'],
        'state_of_center' => $state_code,
        'member_no' => $regno,
        'app_type' => 'EL',
        'service_code' => '999799', //$this->config->item('exam_service_code'),
        'qty' => $el_subject_cnt,
        'state_code' => $ins_state_code,
        'state_name' => $ins_state_name,
        'tax_type' => $tax_type,
        'fee_amt' => $amount_base,
        'cgst_rate' => $cgst_rate,
        'cgst_amt' => $cgst_amt,
        'sgst_rate' => $sgst_rate,
        'sgst_amt' => $sgst_amt,
        'igst_rate' => $igst_rate,
        'igst_amt' => $igst_amt,
        'cs_total' => $cs_total,
        'igst_total' => $igst_total,
        'created_on' => date('Y-m-d H:i:s')
      );
      //echo '<pre>'; print_r($invoice_insert_array); echo '</pre>'; exit;

      $inser_id = $this->master_model->insertRecord('exam_invoice', $invoice_insert_array, true);
      $log_title = "E-learning invoice data from ApplyElearning controller last id inser_id = '" . $inser_id . "'";
      $log_message =  serialize($invoice_insert_array);
      $rId = $regno;
      $regNo = $regno;
      storedUserActivity($log_title, $log_message, $rId, $regNo);

      if ($pg_name == 'sbi')
      {
        exit();
        include APPPATH . 'third_party/SBI_ePay/CryptAES.php';
        $key = $this->config->item('sbi_m_key');
        $merchIdVal = $this->config->item('sbi_merchIdVal');
        $AggregatorId = $this->config->item('sbi_AggregatorId');
        $pg_success_url = site_url("ApplyElearning/sbitranssuccess");
        $pg_fail_url    = site_url("ApplyElearning/sbitransfail");

        /* Payment Process Code */
        $MerchantCustomerID = $regid; //$regno;
        $data["pg_form_url"] = $this->config->item('sbi_pg_form_url'); // SBI ePay form URL
        $data["merchIdVal"]  = $merchIdVal;
        $EncryptTrans = $merchIdVal . "|DOM|IN|INR|" . $amount . "|" . $custom_field . "|" . $pg_success_url . "|" . $pg_fail_url . "|" . $AggregatorId . "|" . $MerchantOrderNo . "|" . $MerchantCustomerID . "|NB|ONLINE|ONLINE";
        $aes = new CryptAES();
        $aes->set_key(base64_decode($key));
        $aes->require_pkcs5();
        $EncryptTrans = $aes->encrypt($EncryptTrans);
        $data["EncryptTrans"] = $EncryptTrans; // SBI encrypted form field value
        $this->load->view('pg_sbi_form', $data);
      }
      elseif ($pg_name == 'billdesk')
      {
        $update_payment_data = array('gateway' => 'billdesk');
        $this->master_model->updateRecord('payment_transaction', $update_payment_data, array('id' => $pt_id));

        //echo '<br> MerchantOrderNo : '.$MerchantOrderNo;//xxx
        //echo '<br> amount : '.$amount;//xxx
        //echo '<br> regid : '.$regid;//xxx
        //echo '<br> URL : '.'ApplyElearning/handle_billdesk_response';//xxx
        //echo '<br> custom_field_billdesk : '.$custom_field_billdesk;//xxx

        $billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $regid, $regid, '', 'ApplyElearning/handle_billdesk_response?login_type=' . $login_type, '', '', '', $custom_field_billdesk);
        //echo '<pre>'; print_r($billdesk_res); echo '</pre>'; exit;						
        if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE')
        {
          $data['bdorderid'] = $billdesk_res['bdorderid'];
          $data['token'] = $billdesk_res['token'];
          $data['responseXHRUrl'] = $billdesk_res['responseXHRUrl'];
          $data['returnUrl'] = $billdesk_res['returnUrl'];
          $this->load->view('pg_billdesk/pg_billdesk_form', $data);
        }
        else
        {
          $this->session->set_flashdata('error', 'Transaction failed...!');
          redirect(base_url() . 'ApplyElearning/logout');
        }
      }
    }
    else
    {
      $data['show_billdesk_option_flag'] = 1;
      $this->load->view('pg_sbi/make_payment_page', $data);
    }
  }

  public function handle_billdesk_response() 
  {
    //ini_set('display_errors', 1);
    //ini_set('display_startup_errors', 1);
    //error_reporting(E_ALL);
    /* $selected_invoice_id = $attachpath = $invoiceNumber = '';
			$selected_invoice_id = $this->session->userdata['memberdata']['selected_invoice_id']; // Seleted Invoice Id */
    $login_type = $_REQUEST['login_type'];
    echo '<pre>'; print_r($_REQUEST);echo '</pre>';
    
    //exit;
    if (isset($_REQUEST['transaction_response']))
    { 
      $response_encode = $_REQUEST['transaction_response'];
      $bd_response = $this->billdesk_pg_model->verify_res($response_encode);
      $attachpath = $invoiceNumber = $admitcard_pdf = '';
      //echo '<pre>'; print_r($bd_response); echo '</pre>'; exit;

      $responsedata = $bd_response['payload'];

      $MerchantOrderNo = $responsedata['orderid']; // To DO: temp testing changes please remove it and use valid receipt id
      $transaction_no  = $responsedata['transactionid'];
      $merchIdVal = $responsedata['mercid'];
      $Bank_Code = $responsedata['bankid'];
      $encData = $_REQUEST['transaction_response'];

      $this->session->userdata['session_array_' . $login_type]['payment_receipt_no'] = $MerchantOrderNo;

      $elective_subject_name = '';

      $transaction_error_type = $responsedata['transaction_error_type'];

      $auth_status = $responsedata['auth_status'];

      $qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);
      if ($auth_status == "0300" && $qry_api_response['auth_status'] == '0300')
      {
        //START : CODE ADDED BY SAGAR ON 25-01-2022 : GENARATE REGNUMBER FOR NEWLY ADDED MEMBER ONLY WHEN PAYMENT IS SUCCESS
        $this->db->order_by('id', 'DESC');
        $get_regid = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'pay_type' => '20'), 'id, member_regnumber, ref_id, status');
        if (count($get_regid) > 0)
        {
          //$regid = $this->session->userdata['session_array_'.$login_type]['regid'];
          $regid = $get_regid[0]['ref_id'];
          $chk_member_no = $this->master_model->getRecords('spm_elearning_registration', array('regid' => $regid, 'regnumber' => ''), 'regid, regnumber');

          $log_title = "E-learning member Get registration details :" . $regid;
          $log_message = serialize($this->db->last_query());
          storedUserActivity($log_title, $log_message, $regid, $regid);

          if (count($chk_member_no) > 0)
          {
            ## Generate regnumber for newly added member. Note: This is for only E-learning module
            $regnumber = generate_eLearning_memreg($regid);
            $this->session->userdata['session_array_' . $login_type]['member_no'] = $regnumber;

            //UPDATE regnumber IN spm_elearning_registration
            $up_data1['registrationtype'] = 1;
            $up_data1['isactive'] = '1';
            $up_data1['regnumber'] = $regnumber;
            $this->master_model->updateRecord('spm_elearning_registration', $up_data1, array('regid' => $regid, 'regnumber' => ''));

            $log_title = "E-learning member UPDATE array spm_elearning_registration :" . $regid;
            $log_message = serialize($up_data1);
            storedUserActivity($log_title, $log_message, $regid, $regnumber);

            //UPDATE regnumber IN spm_elearning_member_subjects
            $up_data2['regnumber'] = $regnumber;
            $this->master_model->updateRecord('spm_elearning_member_subjects', $up_data2, array('regid' => $regid, 'regnumber' => ''));

            $log_title = "E-learning member UPDATE array spm_elearning_member_subjects :" . $regid;
            $log_message = serialize($up_data2);
            storedUserActivity($log_title, $log_message, $regid, $regnumber);

            //UPDATE regnumber IN payment_transaction
            $up_data3['member_regnumber'] = $regnumber;
            $this->master_model->updateRecord('payment_transaction', $up_data3, array('ref_id' => $regid, 'member_regnumber' => '', 'pay_type' => '20'));

            $log_title = "E-learning member UPDATE array payment_transaction :" . $regid;
            $log_message = serialize($up_data3);
            storedUserActivity($log_title, $log_message, $regid, $regnumber);

            //UPDATE regnumber IN exam_invoice
            $up_data4['member_no'] = $regnumber;
            $this->master_model->updateRecord('exam_invoice', $up_data4, array('receipt_no' => $MerchantOrderNo, 'member_no' => ''));

            $log_title = "E-learning member UPDATE array exam_invoice :" . $MerchantOrderNo;
            $log_message = serialize($up_data4);
            storedUserActivity($log_title, $log_message, $MerchantOrderNo, $regnumber);
          }
          else
          {
            //UPDATE status IN spm_elearning_registration
            $up_data1['isactive'] = '1';
            $this->master_model->updateRecord('spm_elearning_registration', $up_data1, array('regid' => $regid));
          }
        } //END : CODE ADDED BY SAGAR ON 25-01-2022 : GENARATE REGNUMBER FOR NEWLY ADDED MEMBER ONLY WHEN PAYMENT IS SUCCESS

        $get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'id, member_regnumber, ref_id, status');
        //check user payment status is updated by b2b or not
        if ($get_user_regnum[0]['status'] == 2)
        {
          ######### payment Transaction ############
          $update_data = array('transaction_no' => $transaction_no, 'status' => 1, 'transaction_details' => $responsedata['transaction_error_type'] . " - " . $responsedata['transaction_error_desc'], 'auth_code' => '0300', 'bankcode' => $responsedata['bankid'], 'paymode' => $responsedata['txn_process_type'], 'callback' => 'B2B');
          $update_query = $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo, 'status' => 2));

          $last_qry_payment_transaction = $this->db->last_query();
          $log_title = "E-learning payment_transaction update : receipt_no = '" . $MerchantOrderNo . "'";
          $log_message =  $last_qry_payment_transaction;
          $rId = $get_user_regnum[0]['member_regnumber'];
          $regNo = $get_user_regnum[0]['member_regnumber'];
          storedUserActivity($log_title, $log_message, $rId, $regNo);

          if ($this->db->affected_rows())
          {
            //$el_subject = explode(",",$this->session->userdata['session_array_'.$login_type]['el_subject']);
            //$el_subject_cnt = count($el_subject);

            //$el_sub_id_arr = explode(",",$this->session->userdata['session_array_'.$login_type]['el_sub_id_str']); 
            $el_sub_id_arr = array();
            $get_el_sub_ids = $this->master_model->getRecords('spm_elearning_member_subjects', array('pt_id' => $get_user_regnum[0]['id']), 'el_sub_id');
            if (count($get_el_sub_ids) > 0)
            {
              foreach ($get_el_sub_ids as $get_el_sub_res)
              {
                $el_sub_id_arr[] = $get_el_sub_res['el_sub_id'];
              }
            }

            if (count($el_sub_id_arr) > 0)
            {
              foreach ($el_sub_id_arr as $el_sub_id_res)
              {
                $this->master_model->updateRecord('spm_elearning_member_subjects', array('pt_id' => $get_user_regnum[0]['id'], 'transaction_no' => $transaction_no, 'receipt_no' => $MerchantOrderNo, 'status' => 1, 'updated_on' => date('Y-m-d H:i:s')), array('el_sub_id' => $el_sub_id_res));
              }
            }

            //Query to get Payment details	
            $payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $get_user_regnum[0]['member_regnumber']), 'exam_code, transaction_no, date, amount, id, status');

            //get invoice	
            $getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo, 'pay_txn_id' => $payment_info[0]['id']));

            if (count($getinvoice_number) > 0)
            {
              $invoiceNumber = generate_el_invoice_number($getinvoice_number[0]['invoice_id']);
              if ($invoiceNumber)
              {
                //$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
                /* $cyear = date("y");
                $nyear = $cyear + 1;
                $invoiceNumber = 'EL/' . $cyear . '-' . $nyear . '/' . $invoiceNumber; */

                //START : THIS CODE WAS UPDATED BY SAGAR M ON 2024-04-16. EARLIER THE INVOICE YEAR FOLLOW THE CALENDER YEAR INSTEAD OF FINANCIAL YEAR
                if(date("Y-m-d") >= date("Y").'-04-01') { $cyear = date("y"); } else { $cyear = date('y') - 1; }
                $nyear = $cyear + 1;
                if($cyear.'-'.$nyear == '24-25' && $invoiceNumber >= 6860) { $invoiceNumber = $invoiceNumber + 3056; }
                $invoiceNumber = 'EL/' . $cyear . '-' . $nyear . '/' . str_pad($invoiceNumber,6,0,STR_PAD_LEFT);
                //END : THIS CODE WAS UPDATED BY SAGAR M ON 2024-04-16. EARLIER THE INVOICE YEAR FOLLOW THE CALENDER YEAR INSTEAD OF FINANCIAL YEAR
              }

              $update_data = array('invoice_no' => $invoiceNumber, 'transaction_no' => $transaction_no, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'));
              $this->db->where('pay_txn_id', $payment_info[0]['id']);
              $this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));
              $attachpath = genarate_el_invoice($getinvoice_number[0]['invoice_id']);

              $last_qry_exam_invoice = $this->db->last_query();
              $log_title = "E-learning exam_invoice update : receipt_no = '" . $MerchantOrderNo . "'";
              $log_message = $last_qry_exam_invoice;
              $rId = $get_user_regnum[0]['member_regnumber'];
              $regNo = $get_user_regnum[0]['member_regnumber'];
              storedUserActivity($log_title, $log_message, $rId, $regNo);
            }

            //$this->db->where_in('sm.subject_code', $this->session->userdata['session_array_'.$login_type]['el_subject'], FALSE);
            $selected_sub_data = $this->master_model->getRecords('spm_elearning_member_subjects ms', array('ms.status' => '1', 'ms.pt_id' => $payment_info[0]['id']), 'ms.subject_code, ms.subject_description');

            $selected_sub_disp_str = '';
            if (count($selected_sub_data) > 0)
            {
              $sr_no = 1;
              foreach ($selected_sub_data as $selected_sub_res)
              {
                $selected_sub_disp_str .= '<span style="display: block;margin: 2px 0 5px 0px;">' . $sr_no . '. ' . $selected_sub_res['subject_description'] . '</span>';
                $sr_no++;
              }
            }

            $email_payment_status = '';
            if ($payment_info[0]['status'] == 0)
            {
              $email_payment_status = 'Fail';
            }
            else if ($payment_info[0]['status'] == 1)
            {
              $email_payment_status = 'Success';
            }
            else if ($payment_info[0]['status'] == 2)
            {
              $email_payment_status = 'Pending';
            }
            else if ($payment_info[0]['status'] == 3)
            {
              $email_payment_status = 'Refund';
            }

            //Query to get Exam Name	
            $email_exam_name = '';
            $this->db->limit(1);
            $this->db->order_by('exam_id', 'DESC');
            $exam_data = $this->master_model->getRecords('spm_elearning_exam_master', array('exam_code' => $payment_info[0]['exam_code']), 'exam_code, exam_name');
            if (count($exam_data) > 0)
            {
              $email_exam_name = $exam_data[0]['exam_name'];
            }

            /*$mail_content = "<div style='border: 1px solid #ccc;background: #f5f5f5;padding: 15px 20px;max-width: 700px;'>
              <p style='margin:0'>Dear Candidate,</p>
              <p style='margin:20px 0 0 0'>Greetings from IIBF !</p>
              <p style='margin:10px 0 15px 0'>This mail is to inform you that we have received your payment and you have successfully registered for E-learning option against below subject. ".$selected_sub_disp_str."<br>
              Please check below transaction details,<br>
              <strong>Transaction No. : ".$payment_info[0]['transaction_no']."</strong><br>
              <strong>Transaction Date : ".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."</strong></p>
              <p style='margin:0'>Regards,<br>IIBF Team</p>
              </div>";
              //echo $mail_content; exit;*/

            $Candidate = $this->master_model->getRecords('spm_elearning_registration', array('regnumber' => $get_user_regnum[0]['member_regnumber']));
            $mail_content = '
                <table cellspacing="5" cellpadding="0" align="center" style="border: 1px solid #ddd; font-family: Arial,Helvetica,sans-serif; font-size: 14px; margin: 0; max-width: 800px; width: 100%; background:#FFFFCC" border="1">
                  <thead>
                    <tr>
                      <td colspan="2" align="center"><h2 style="line-height:24px; margin:10px 0;">Transaction Details</h2></td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td colspan="2" style="padding:10px;" width="100%">
                        <p>Dear ' . ucfirst($Candidate[0]['firstname']) . '</p>
                        <p>We acknowledge with thanks the receipt of the payment for Enrolment in E-Learning as per the details given below.</p>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:5px 10px;" width="35%"><p><strong>Registration / Membership No : </strong></p></td>
                      <td style="padding:5px 10px;" width="64%">' . $get_user_regnum[0]['member_regnumber'] . '</td>
                    </tr>
                    <tr>
                      <td style="padding:5px 10px;;" width="35%"><p><strong>Member Name : </strong></p></td>
                      <td style="padding:5px 10px;" width="64%">' . ucfirst($Candidate[0]['firstname']) . ' ' . ucfirst($Candidate[0]['lastname']) . '</td>
                    </tr>
                    <tr>
                      <td style="padding:5px 10px;" width="35%"><p><strong>Email Id : </strong></p></td>
                      <td style="padding:5px 10px;" width="64%"><a href="mailto:' . $Candidate[0]['email'] . '">' . $Candidate[0]['email'] . '</a></td>
                    </tr>
                    <tr>
                      <td style="padding:5px 10px;" width="35%"><p><strong>Transaction ID:</strong></p></td>
                      <td style="padding:5px 10px;" width="64%">' . $payment_info[0]['transaction_no'] . '</td>
                    </tr>
                    <tr>
                      <td style="padding:5px 10px;" width="35%"><p><strong>Exam Name: </strong></p></td>
                      <td style="padding:5px 10px;" width="64%"><p>' . $email_exam_name . '</p></td>
                    </tr>
                    <tr>
                      <td style="padding:5px 10px;" width="35%"><p><strong>E-Learning Subject/s: </strong></p></td>
                      <td style="padding:5px 10px;" width="64%"><p>' . $selected_sub_disp_str . '</p></td>
                    </tr>
                    <tr>
                      <td style="padding:5px 10px;" width="35%"><p><strong>Transaction Status: </strong></p></td>
                      <td style="padding:5px 10px;" width="64%"><p>' . $email_payment_status . '</p></td>
                    </tr>
                    <tr>
                      <td style="padding:5px 10px;" width="35%"><p><strong>Amount : </strong></p></td>
                      <td style="padding:5px 10px;" width="64%">' . $payment_info[0]['amount'] . '</td>
                    </tr>
                    <tr>
                      <td style="padding:5px 10px;" width="35%"><p><strong>Transaction Date :</strong> </p></td>
                      <td style="padding:5px 10px;" width="64%">' . date('Y-m-d H:i:s A', strtotime($payment_info[0]['date'])) . '</td>
                    </tr>
                  </tbody>
                </table>';

            $sender_email = $this->master_model->getRecords('spm_elearning_registration', array('regnumber' => $get_user_regnum[0]['member_regnumber'], 'isactive' => "1"), "email", "", "", "1");

            $info_arr['to'] = $sender_email[0]['email'];
            $info_arr['from'] = "logs@iibf.esdsconnect.com";
            // $info_arr['cc'] = "iibfdevp@esds.co.in";
            $info_arr['subject'] = "E-Learning Payment Acknowledgment";
            $info_arr['message'] = $mail_content;

            if ($attachpath != '')
            {
              $files = array($attachpath);
              $this->Emailsending->mailsend_attch($info_arr, $files);

              /* $info_arr['to'] = 'sagar.matale@esds.co.in'; 
                $this->Emailsending->mailsend_attch($info_arr,$files); */
            }

            //Manage Log
            $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
            $this->log_model->logtransaction("billdesk", $pg_response, $responsedata['transaction_error_type']);
          }
        }
        redirect(site_url('ApplyElearning/mail_success/' . $MerchantOrderNo . '?login_type=' . $login_type));
      }
      elseif ($auth_status == "0002")
      {
        $get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id,status, id');

        if ($get_user_regnum[0]['status'] != 0 && $get_user_regnum[0]['status'] == 2)
        {
          $update_data22 = array('transaction_no' => $transaction_no, 'status' => 2, 'transaction_details' => $responsedata['transaction_error_type'] . " - " . $responsedata['transaction_error_desc'], 'auth_code' => '0002', 'bankcode' => $responsedata['bankid'], 'paymode' =>  $responsedata['txn_process_type'], 'callback' => 'B2B');
          $this->master_model->updateRecord('payment_transaction', $update_data22, array('receipt_no' => $MerchantOrderNo));

          //START : UPDATE FAIL STATUS IN spm_elearning_member_subjects
          $el_sub_id_arr = array();
          $get_el_sub_ids = $this->master_model->getRecords('spm_elearning_member_subjects', array('pt_id' => $get_user_regnum[0]['id']), 'el_sub_id');
          if (count($get_el_sub_ids) > 0)
          {
            foreach ($get_el_sub_ids as $get_el_sub_res)
            {
              $el_sub_id_arr[] = $get_el_sub_res['el_sub_id'];
            }
          }

          if (count($el_sub_id_arr) > 0)
          {
            foreach ($el_sub_id_arr as $el_sub_id_res)
            {
              $this->master_model->updateRecord('spm_elearning_member_subjects', array('pt_id' => $get_user_regnum[0]['id'], 'transaction_no' => $transaction_no, 'receipt_no' => $MerchantOrderNo, 'status' => 2, 'updated_on' => date('Y-m-d H:i:s')), array('el_sub_id' => $el_sub_id_res));
            }
          }
          //END : UPDATE FAIL STATUS IN spm_elearning_member_subjects

          //Query to get Payment details	
          $payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $get_user_regnum[0]['member_regnumber']), 'transaction_no,date,amount');

          //Query to get user details
          $result = $this->master_model->getRecords('spm_elearning_registration', array('regnumber' => $get_user_regnum[0]['member_regnumber']), 'firstname, middlename, lastname, email, mobile');

          $username = $result[0]['firstname'] . ' ' . $result[0]['middlename'] . ' ' . $result[0]['lastname'];
          $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
          $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'transaction_fail'));
          $newstring1 = str_replace("#application_num#", "" . $get_user_regnum[0]['member_regnumber'] . "",  $emailerstr[0]['emailer_text']);
          $newstring2 = str_replace("#username#", "" . $userfinalstrname . "",  $newstring1);
          $newstring3 = str_replace("#transaction_id#", "" . $payment_info[0]['transaction_no'] . "",  $newstring2);
          $final_str = str_replace("#transaction_date#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "",  $newstring3);

          $info_arr = array(
            'to' => $result[0]['email'],
            'from' => $emailerstr[0]['from'],
            'subject' => $emailerstr[0]['subject'],
            'message' => $final_str
          );
          /* $info_arr=array('to'=>'Akshay.Shirke@esds.co.in',
							'from'=>$emailerstr[0]['from'],
							'subject'=>$emailerstr[0]['subject'],
							'message'=>$final_str
						);	 */

          //send sms to Ordinary Member
          //$sms_final_str = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
          //$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
          //$this->master_model->send_sms('8793012005',$sms_final_str);
          //$this->Emailsending->mailsend($info_arr);
          //Manage Log
          $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
          $this->log_model->logtransaction("billdesk", $pg_response, $responsedata['transaction_error_type']);
        }
        //End Of SBICALL Back	

        redirect(base_url() . 'ApplyElearning/mail_fail/' . $MerchantOrderNo);
      }
      else
      {
        //SBICALL Back B2B
        $get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id,status, id');

        if ($get_user_regnum[0]['status'] != 0 && $get_user_regnum[0]['status'] == 2)
        {
          $update_data22 = array('transaction_no' => $transaction_no, 'status' => 0, 'transaction_details' => $responsedata['transaction_error_type'] . " - " . $responsedata['transaction_error_desc'], 'auth_code' => '0399', 'bankcode' => $responsedata['bankid'], 'paymode' =>  $responsedata['txn_process_type'], 'callback' => 'B2B');
          $this->master_model->updateRecord('payment_transaction', $update_data22, array('receipt_no' => $MerchantOrderNo));

          //START : UPDATE FAIL STATUS IN spm_elearning_member_subjects
          $el_sub_id_arr = array();
          $get_el_sub_ids = $this->master_model->getRecords('spm_elearning_member_subjects', array('pt_id' => $get_user_regnum[0]['id']), 'el_sub_id');
          if (count($get_el_sub_ids) > 0)
          {
            foreach ($get_el_sub_ids as $get_el_sub_res)
            {
              $el_sub_id_arr[] = $get_el_sub_res['el_sub_id'];
            }
          }

          if (count($el_sub_id_arr) > 0)
          {
            foreach ($el_sub_id_arr as $el_sub_id_res)
            {
              $this->master_model->updateRecord('spm_elearning_member_subjects', array('pt_id' => $get_user_regnum[0]['id'], 'transaction_no' => $transaction_no, 'receipt_no' => $MerchantOrderNo, 'status' => 2, 'updated_on' => date('Y-m-d H:i:s')), array('el_sub_id' => $el_sub_id_res));
            }
          }
          //END : UPDATE FAIL STATUS IN spm_elearning_member_subjects

          //Query to get Payment details	
          $payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $get_user_regnum[0]['member_regnumber']), 'transaction_no,date,amount');

          //Query to get user details
          $result = $this->master_model->getRecords('spm_elearning_registration', array('regnumber' => $get_user_regnum[0]['member_regnumber']), 'firstname, middlename, lastname, email, mobile');

          $username = $result[0]['firstname'] . ' ' . $result[0]['middlename'] . ' ' . $result[0]['lastname'];
          $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
          $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'transaction_fail'));
          $newstring1 = str_replace("#application_num#", "" . $get_user_regnum[0]['member_regnumber'] . "",  $emailerstr[0]['emailer_text']);
          $newstring2 = str_replace("#username#", "" . $userfinalstrname . "",  $newstring1);
          $newstring3 = str_replace("#transaction_id#", "" . $payment_info[0]['transaction_no'] . "",  $newstring2);
          $final_str = str_replace("#transaction_date#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "",  $newstring3);

          $info_arr = array(
            'to' => $result[0]['email'],
            'from' => $emailerstr[0]['from'],
            'subject' => $emailerstr[0]['subject'],
            'message' => $final_str
          );
          /* $info_arr=array('to'=>'Akshay.Shirke@esds.co.in',
							'from'=>$emailerstr[0]['from'],
							'subject'=>$emailerstr[0]['subject'],
							'message'=>$final_str
						);	 */

          //send sms to Ordinary Member
          //$sms_final_str = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
          //$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
          //$this->master_model->send_sms('8793012005',$sms_final_str);
          //$this->Emailsending->mailsend($info_arr);
          //Manage Log
          $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
          $this->log_model->logtransaction("billdesk", $pg_response, $responsedata['transaction_error_type']);
        }
        //End Of SBICALL Back	

        redirect(base_url() . 'ApplyElearning/mail_fail/' . $MerchantOrderNo);
      }
    }
    else
    {
      die("Please try again...");
    }
  }

  public function sbitranssuccess()
  {
    exit();
    include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
    $key = $this->config->item('sbi_m_key');
    $aes = new CryptAES();
    $aes->set_key(base64_decode($key));
    $aes->require_pkcs5();
    $encData = $aes->decrypt($_REQUEST['encData']);
    $attachpath = $invoiceNumber = $admitcard_pdf = '';
    $responsedata = explode("|", $encData);
    $MerchantOrderNo = $responsedata[0]; // To DO: temp testing changes please remove it and use valid receipt id
    $transaction_no  = $responsedata[1];
    if (isset($_REQUEST['merchIdVal']))
    {
      $merchIdVal = $_REQUEST['merchIdVal'];
    }
    if (isset($_REQUEST['Bank_Code']))
    {
      $Bank_Code = $_REQUEST['Bank_Code'];
    }
    if (isset($_REQUEST['pushRespData']))
    {
      $encData = $_REQUEST['pushRespData'];
    }

    $this->session->userdata['session_array_' . $login_type]['payment_receipt_no'] = $MerchantOrderNo;

    $elective_subject_name = '';
    //Sbi B2B callback
    //check sbi payment status with MerchantOrderNo 
    $q_details = sbiqueryapi($MerchantOrderNo);
    //print_r($q_details); exit;

    /* Temp Testing code start */
    if ($q_details && !empty($q_details))
    {
      if ($q_details[2] == "SUCCESS")
      {
        //START : CODE ADDED BY SAGAR ON 02-09-2021 : GENARATE REGNUMBER FOR NEWLY ADDED MEMBER ONLY WHEN PAYMENT IS SUCCESS
        $this->db->order_by('id', 'DESC');
        $get_regid = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'pay_type' => '20'), 'id, member_regnumber, ref_id, status');
        if (count($get_regid) > 0)
        {
          //$regid = $this->session->userdata['session_array_'.$login_type]['regid'];
          $regid = $get_regid[0]['ref_id'];
          $chk_member_no = $this->master_model->getRecords('spm_elearning_registration', array('regid' => $regid, 'regnumber' => ''), 'regid, regnumber');

          $log_title = "E-learning member Get registration details :" . $regid;
          $log_message = serialize($this->db->last_query());
          storedUserActivity($log_title, $log_message, $regid, $regid);

          if (count($chk_member_no) > 0)
          {
            ## Generate regnumber for newly added member. Note: This is for only E-learning module
            $regnumber = generate_eLearning_memreg($regid);
            $this->session->userdata['session_array_' . $login_type]['member_no'] = $regnumber;

            //UPDATE regnumber IN spm_elearning_registration
            $up_data1['registrationtype'] = 1;
            $up_data1['isactive'] = '1';
            $up_data1['regnumber'] = $regnumber;
            $this->master_model->updateRecord('spm_elearning_registration', $up_data1, array('regid' => $regid, 'regnumber' => ''));

            $log_title = "E-learning member UPDATE array spm_elearning_registration :" . $regid;
            $log_message = serialize($up_data1);
            storedUserActivity($log_title, $log_message, $regid, $regnumber);

            //UPDATE regnumber IN spm_elearning_member_subjects
            $up_data2['regnumber'] = $regnumber;
            $this->master_model->updateRecord('spm_elearning_member_subjects', $up_data2, array('regid' => $regid, 'regnumber' => ''));

            $log_title = "E-learning member UPDATE array spm_elearning_member_subjects :" . $regid;
            $log_message = serialize($up_data2);
            storedUserActivity($log_title, $log_message, $regid, $regnumber);

            //UPDATE regnumber IN payment_transaction
            $up_data3['member_regnumber'] = $regnumber;
            $this->master_model->updateRecord('payment_transaction', $up_data3, array('ref_id' => $regid, 'member_regnumber' => '', 'pay_type' => '20'));

            $log_title = "E-learning member UPDATE array payment_transaction :" . $regid;
            $log_message = serialize($up_data3);
            storedUserActivity($log_title, $log_message, $regid, $regnumber);

            //UPDATE regnumber IN exam_invoice
            $up_data4['member_no'] = $regnumber;
            $this->master_model->updateRecord('exam_invoice', $up_data4, array('receipt_no' => $MerchantOrderNo, 'member_no' => ''));

            $log_title = "E-learning member UPDATE array exam_invoice :" . $MerchantOrderNo;
            $log_message = serialize($up_data4);
            storedUserActivity($log_title, $log_message, $MerchantOrderNo, $regnumber);
          }
          else
          {
            //UPDATE status IN spm_elearning_registration
            $up_data1['isactive'] = '1';
            $this->master_model->updateRecord('spm_elearning_registration', $up_data1, array('regid' => $regid));
          }
        } //END : CODE ADDED BY SAGAR ON 02-09-2021 : GENARATE REGNUMBER FOR NEWLY ADDED MEMBER ONLY WHEN PAYMENT IS SUCCESS 

        $get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'id, member_regnumber, ref_id, status');
        //check user payment status is updated by b2b or not
        if ($get_user_regnum[0]['status'] == 2)
        {
          ######### payment Transaction ############
          $update_data = array('transaction_no' => $transaction_no, 'status' => 1, 'transaction_details' => $responsedata[2] . " - " . $responsedata[7], 'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5], 'callback' => 'B2B');
          $update_query = $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo, 'status' => 2));

          $last_qry_payment_transaction = $this->db->last_query();
          $log_title = "E-learning payment_transaction update : receipt_no = '" . $MerchantOrderNo . "'";
          $log_message =  $last_qry_payment_transaction;
          $rId = $get_user_regnum[0]['member_regnumber'];
          $regNo = $get_user_regnum[0]['member_regnumber'];
          storedUserActivity($log_title, $log_message, $rId, $regNo);

          if ($this->db->affected_rows())
          {
            $el_subject = explode(",", $this->session->userdata['session_array_' . $login_type]['el_subject']);
            $el_subject_cnt = count($el_subject);

            //$el_sub_id_arr = explode(",",$this->session->userdata['session_array_'.$login_type]['el_sub_id_str']); 
            $el_sub_id_arr = array();
            $get_el_sub_ids = $this->master_model->getRecords('spm_elearning_member_subjects', array('pt_id' => $get_user_regnum[0]['id']), 'el_sub_id');
            if (count($get_el_sub_ids) > 0)
            {
              foreach ($get_el_sub_ids as $get_el_sub_res)
              {
                $el_sub_id_arr[] = $get_el_sub_res['el_sub_id'];
              }
            }

            if (count($el_sub_id_arr) > 0)
            {
              foreach ($el_sub_id_arr as $el_sub_id_res)
              {
                $this->master_model->updateRecord('spm_elearning_member_subjects', array('pt_id' => $get_user_regnum[0]['id'], 'transaction_no' => $transaction_no, 'receipt_no' => $MerchantOrderNo, 'status' => 1, 'updated_on' => date('Y-m-d H:i:s')), array('el_sub_id' => $el_sub_id_res));
              }
            }

            //Query to get Payment details	
            $payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $get_user_regnum[0]['member_regnumber']), 'exam_code, transaction_no, date, amount, id, status');

            //get invoice	
            $getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo, 'pay_txn_id' => $payment_info[0]['id']));

            $last_qry_invoice_data = $this->db->last_query();
            $log_title = "E-learning exam_invoice data : receipt_no = '" . $MerchantOrderNo . "'";
            $log_message = $last_qry_invoice_data;
            $rId = $get_user_regnum[0]['member_regnumber'];
            $regNo = $get_user_regnum[0]['member_regnumber'];
            storedUserActivity($log_title, $log_message, $rId, $regNo);

            if (count($getinvoice_number) > 0)
            {
              $invoiceNumber = generate_el_invoice_number($getinvoice_number[0]['invoice_id']);
              if ($invoiceNumber)
              {
                //$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
                /* $cyear = date("y");
                $nyear = $cyear + 1;
                $invoiceNumber = 'EL/' . $cyear . '-' . $nyear . '/' . $invoiceNumber; */

                //START : THIS CODE WAS UPDATED BY SAGAR M ON 2024-04-16. EARLIER THE INVOICE YEAR FOLLOW THE CALENDER YEAR INSTEAD OF FINANCIAL YEAR
                if(date("Y-m-d") >= date("Y").'-04-01') { $cyear = date("y"); } else { $cyear = date('y') - 1; }
                $nyear = $cyear + 1;
                if($cyear.'-'.$nyear == '24-25' && $invoiceNumber >= 6860) { $invoiceNumber = $invoiceNumber + 3056; }
                $invoiceNumber = 'EL/' . $cyear . '-' . $nyear . '/' . str_pad($invoiceNumber,6,0,STR_PAD_LEFT);
                //END : THIS CODE WAS UPDATED BY SAGAR M ON 2024-04-16. EARLIER THE INVOICE YEAR FOLLOW THE CALENDER YEAR INSTEAD OF FINANCIAL YEAR
              }

              $update_data = array('invoice_no' => $invoiceNumber, 'transaction_no' => $transaction_no, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'));
              $this->db->where('pay_txn_id', $payment_info[0]['id']);
              $this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));
              $attachpath = genarate_el_invoice($getinvoice_number[0]['invoice_id']);

              $last_qry_exam_invoice = $this->db->last_query();
              $log_title = "E-learning exam_invoice update : receipt_no = '" . $MerchantOrderNo . "'";
              $log_message = $last_qry_exam_invoice;
              $rId = $get_user_regnum[0]['member_regnumber'];
              $regNo = $get_user_regnum[0]['member_regnumber'];
              storedUserActivity($log_title, $log_message, $rId, $regNo);
            }

            //$this->db->where_in('sm.subject_code', $this->session->userdata['session_array_'.$login_type]['el_subject'], FALSE);
            $selected_sub_data = $this->master_model->getRecords('spm_elearning_member_subjects ms', array('ms.status' => '1', 'ms.pt_id' => $payment_info[0]['id']), 'ms.subject_code, ms.subject_description');

            $selected_sub_disp_str = '';
            if (count($selected_sub_data) > 0)
            {
              $sr_no = 1;
              foreach ($selected_sub_data as $selected_sub_res)
              {
                $selected_sub_disp_str .= '<span style="display: block;margin: 2px 0 5px 0px;">' . $sr_no . '. ' . $selected_sub_res['subject_description'] . '</span>';
                $sr_no++;
              }
            }

            $email_payment_status = '';
            if ($payment_info[0]['status'] == 0)
            {
              $email_payment_status = 'Fail';
            }
            else if ($payment_info[0]['status'] == 1)
            {
              $email_payment_status = 'Success';
            }
            else if ($payment_info[0]['status'] == 2)
            {
              $email_payment_status = 'Pending';
            }
            else if ($payment_info[0]['status'] == 3)
            {
              $email_payment_status = 'Refund';
            }

            //Query to get Exam Name	
            $email_exam_name = '';
            $this->db->limit(1);
            $this->db->order_by('exam_id', 'DESC');
            $exam_data = $this->master_model->getRecords('spm_elearning_exam_master', array('exam_code' => $payment_info[0]['exam_code']), 'exam_code, exam_name');
            if (count($exam_data) > 0)
            {
              $email_exam_name = $exam_data[0]['exam_name'];
            }

            /*$mail_content = "<div style='border: 1px solid #ccc;background: #f5f5f5;padding: 15px 20px;max-width: 700px;'>
              <p style='margin:0'>Dear Candidate,</p>
              <p style='margin:20px 0 0 0'>Greetings from IIBF !</p>
              <p style='margin:10px 0 15px 0'>This mail is to inform you that we have received your payment and you have successfully registered for E-learning option against below subject. ".$selected_sub_disp_str."<br>
              Please check below transaction details,<br>
              <strong>Transaction No. : ".$payment_info[0]['transaction_no']."</strong><br>
              <strong>Transaction Date : ".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."</strong></p>
              <p style='margin:0'>Regards,<br>IIBF Team</p>
              </div>";
              //echo $mail_content; exit;*/

            $Candidate = $this->master_model->getRecords('spm_elearning_registration', array('regnumber' => $get_user_regnum[0]['member_regnumber']));
            $mail_content = '
                <table cellspacing="5" cellpadding="0" align="center" style="border: 1px solid #ddd; font-family: Arial,Helvetica,sans-serif; font-size: 14px; margin: 0; max-width: 800px; width: 100%; background:#FFFFCC" border="1">
                  <thead>
                    <tr>
                      <td colspan="2" align="center"><h2 style="line-height:24px; margin:10px 0;">Transaction Details</h2></td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td colspan="2" style="padding:10px;" width="100%">
                        <p>Dear ' . ucfirst($Candidate[0]['firstname']) . '</p>
                        <p>We acknowledge with thanks the receipt of the payment for Enrolment in E-Learning as per the details given below.</p>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:5px 10px;" width="35%"><p><strong>Registration / Membership No : </strong></p></td>
                      <td style="padding:5px 10px;" width="64%">' . $get_user_regnum[0]['member_regnumber'] . '</td>
                    </tr>
                    <tr>
                      <td style="padding:5px 10px;;" width="35%"><p><strong>Member Name : </strong></p></td>
                      <td style="padding:5px 10px;" width="64%">' . ucfirst($Candidate[0]['firstname']) . ' ' . ucfirst($Candidate[0]['lastname']) . '</td>
                    </tr>
                    <tr>
                      <td style="padding:5px 10px;" width="35%"><p><strong>Email Id : </strong></p></td>
                      <td style="padding:5px 10px;" width="64%"><a href="mailto:' . $Candidate[0]['email'] . '">' . $Candidate[0]['email'] . '</a></td>
                    </tr>
                    <tr>
                      <td style="padding:5px 10px;" width="35%"><p><strong>Transaction ID:</strong></p></td>
                      <td style="padding:5px 10px;" width="64%">' . $payment_info[0]['transaction_no'] . '</td>
                    </tr>
                    <tr>
                      <td style="padding:5px 10px;" width="35%"><p><strong>Exam Name: </strong></p></td>
                      <td style="padding:5px 10px;" width="64%"><p>' . $email_exam_name . '</p></td>
                    </tr>
                    <tr>
                      <td style="padding:5px 10px;" width="35%"><p><strong>E-Learning Subject/s: </strong></p></td>
                      <td style="padding:5px 10px;" width="64%"><p>' . $selected_sub_disp_str . '</p></td>
                    </tr>
                    <tr>
                      <td style="padding:5px 10px;" width="35%"><p><strong>Transaction Status: </strong></p></td>
                      <td style="padding:5px 10px;" width="64%"><p>' . $email_payment_status . '</p></td>
                    </tr>
                    <tr>
                      <td style="padding:5px 10px;" width="35%"><p><strong>Amount : </strong></p></td>
                      <td style="padding:5px 10px;" width="64%">' . $payment_info[0]['amount'] . '</td>
                    </tr>
                    <tr>
                      <td style="padding:5px 10px;" width="35%"><p><strong>Transaction Date :</strong> </p></td>
                      <td style="padding:5px 10px;" width="64%">' . date('Y-m-d H:i:s A', strtotime($payment_info[0]['date'])) . '</td>
                    </tr>
                  </tbody>
                </table>';

            $sender_email = $this->master_model->getRecords('spm_elearning_registration', array('regnumber' => $get_user_regnum[0]['member_regnumber'], 'isactive' => "1"), "email", "", "", "1");

            $info_arr['to'] = $sender_email[0]['email'];
            $info_arr['from'] = "logs@iibf.esdsconnect.com";
            //$info_arr['cc'] = "";
            //$info_arr['bcc'] = "sagar.matale@esds.co.in"; 
            $info_arr['subject'] = "E-Learning Payment Acknowledgment";
            $info_arr['message'] = $mail_content;

            if ($attachpath != '')
            {
              $files = array($attachpath);
              $this->Emailsending->mailsend_attch($info_arr, $files);

              /* $info_arr['to'] = 'sagar.matale@esds.co.in'; 
                $this->Emailsending->mailsend_attch($info_arr,$files); */
            }

            //Manage Log
            $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
            $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
          }
        }
      }
      redirect(site_url('ApplyElearning/mail_success/' . $MerchantOrderNo));
    }

    /* Temp Testing code end */
    /*if ($q_details)
			{
				if ($q_details[2] == "SUCCESS")
				{
					$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'id, member_regnumber, ref_id, status');
					//check user payment status is updated by b2b or not
					if($get_user_regnum[0]['status']==2)
					{
						######### payment Transaction ############
						$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'B2B');
						$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));
						
						$last_qry_payment_transaction = $this->db->last_query();
						$log_title = "E-learning payment_transaction update : receipt_no = '".$MerchantOrderNo."'";
						$log_message =  $last_qry_payment_transaction;
						$rId = $get_user_regnum[0]['member_regnumber'];
						$regNo = $get_user_regnum[0]['member_regnumber'];
						storedUserActivity($log_title, $log_message, $rId, $regNo);
						
						if($this->db->affected_rows())	
						{
							$el_subject = explode(",",$this->session->userdata['session_array_'.$login_type]['el_subject']);
							$el_subject_cnt = count($el_subject);
							
							$el_sub_id_arr = explode(",",$this->session->userdata['session_array_'.$login_type]['el_sub_id_str']);
							if(count($el_sub_id_arr) > 0)
							{
								foreach($el_sub_id_arr as $el_sub_id_res)
								{
									$this->master_model->updateRecord('spm_elearning_member_subjects',array('pt_id'=>$get_user_regnum[0]['id'], 'transaction_no'=>$transaction_no, 'receipt_no'=>$MerchantOrderNo, 'status'=>1, 'updated_on'=>date('Y-m-d H:i:s')),array('el_sub_id'=>$el_sub_id_res));
								}
							}
								
							//Query to get Payment details	
							$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount,id');
								
							//get invoice	
							$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
							
							if(count($getinvoice_number) > 0)
							{
								$invoiceNumber = generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
								if($invoiceNumber)
								{
									$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
								}
								
								$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
								$this->db->where('pay_txn_id',$payment_info[0]['id']);
								$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
								$attachpath=genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
								
								$last_qry_exam_invoice = $this->db->last_query();
								$log_title = "E-learning exam_invoice update : receipt_no = '".$MerchantOrderNo."'";
								$log_message = $last_qry_exam_invoice;
								$rId = $get_user_regnum[0]['member_regnumber'];
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);
							}	
							
							$this->db->where_in('sm.subject_code', $this->session->userdata['session_array_'.$login_type]['el_subject'], FALSE);
							$selected_sub_data = $this->master_model->getRecords('spm_elearning_subject_master sm',array('sm.subject_delete'=>0), 'sm.subject_code, sm.subject_description');
							
							$selected_sub_disp_str = '';
							if(count($selected_sub_data) > 0)
							{
								$sr_no = 1;
								foreach($selected_sub_data as $selected_sub_res)
								{
									$selected_sub_disp_str .= '<span style="display: block;margin: 2px 0 5px 20px;">'.$sr_no.'. '.$selected_sub_res['subject_description'].'</span>';
									$sr_no++;
								}								
							}
								
							$mail_content = "<div style='border: 1px solid #ccc;background: #f5f5f5;padding: 15px 20px;max-width: 700px;'>
							<p style='margin:0'>Dear Candidate,</p>
							<p style='margin:20px 0 0 0'>Greetings from IIBF !</p>
							<p style='margin:10px 0 15px 0'>This mail is to inform you that we have received your payment and you have successfully registered for E-learning option against below subject. ".$selected_sub_disp_str."<br>
							Please check below transaction details,<br>
							<strong>Transaction No. : ".$payment_info[0]['transaction_no']."</strong><br>
							<strong>Transaction Date : ".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."</strong></p>
							<p style='margin:0'>Regards,<br>IIBF Team</p>
							</div>";
							//echo $mail_content; exit;
							
							$sender_email = $this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'isactive'=>"1"),"email", "", "", "1");
							
							$info_arr=array('to'=>'sagar.matale@esds.co.in',// $sender_email[0]['email'] 
							'from'=>"logs@iibf.esdsconnect.com",
							'subject'=>"Elearning Enrolment Acknowledgement",
							'message'=>$mail_content
							);
							
							if($attachpath!='')
							{		
								$files=array($attachpath);
								$this->Emailsending->mailsend_attch($info_arr,$files);
								//$this->Emailsending->mailsend($info_arr);
							}
														
							//Manage Log
							$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
							$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
						}
					}
				}//End of check sbi payment status with MerchantOrderNo 
				///End of SBICALL Back	
				
				redirect(site_url('ApplyElearning/mail_success'));
			}*/
  }

  public function mail_success($receipt_no = 0)
  {
    $login_type = $_REQUEST['login_type'];
    if ($receipt_no != '0')
    {
      $payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $receipt_no));

      $user_info = array();
      $disp_member_no = '';
      if (count($payment_info) > 0)
      {
        $user_info = $this->master_model->getRecords('spm_elearning_registration', array('regid' => $payment_info[0]['ref_id'], 'regnumber' => $payment_info[0]['member_regnumber']));

        $disp_member_no = $payment_info[0]['member_regnumber'];
      }

      $data['disp_member_no'] = $disp_member_no;

      ## Clear session data
      $this->session->set_userdata('session_array_' . $login_type, '');
      $this->session->unset_userdata('session_array_' . $login_type);

      $data['transaction_status'] = 'success';
      $data['user_info'] = $user_info;
      $data['payment_info'] = $payment_info;
      $data['login_type']   = $login_type;
      $this->load->view('apply_elearning/elearning_payment_response', $data);
    }
    else
    {
      redirect(site_url('ApplyElearning'));
    }
  }

  public function sbitransfail()
  {
    exit();
    if (isset($_REQUEST['encData']))
    {
      include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
      $key = $this->config->item('sbi_m_key');
      $aes = new CryptAES();
      $aes->set_key(base64_decode($key));
      $aes->require_pkcs5();
      $encData = $aes->decrypt($_REQUEST['encData']);
      $responsedata = explode("|", $encData);
      $MerchantOrderNo = $responsedata[0]; // To DO: temp testing changes please remove it and use valid recipt id
      $transaction_no  = $responsedata[1];

      $this->session->userdata['session_array']['payment_receipt_no'] = $MerchantOrderNo;

      //SBICALL Back B2B
      $get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id,status, id');

      if ($get_user_regnum[0]['status'] != 0 && $get_user_regnum[0]['status'] == 2)
      {
        if (isset($_REQUEST['merchIdVal']))
        {
          $merchIdVal = $_REQUEST['merchIdVal'];
        }
        if (isset($_REQUEST['Bank_Code']))
        {
          $Bank_Code = $_REQUEST['Bank_Code'];
        }
        if (isset($_REQUEST['pushRespData']))
        {
          $encData = $_REQUEST['pushRespData'];
        }

        $update_data = array('transaction_no' => $transaction_no, 'status' => 0, 'transaction_details' => $responsedata[2] . " - " . $responsedata[7], 'auth_code' => '0399', 'bankcode' => $responsedata[8], 'paymode' =>  $responsedata[5], 'callback' => 'B2B');
        $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));

        //START : UPDATE FAIL STATUS IN spm_elearning_member_subjects
        $el_sub_id_arr = array();
        $get_el_sub_ids = $this->master_model->getRecords('spm_elearning_member_subjects', array('pt_id' => $get_user_regnum[0]['id']), 'el_sub_id');
        if (count($get_el_sub_ids) > 0)
        {
          foreach ($get_el_sub_ids as $get_el_sub_res)
          {
            $el_sub_id_arr[] = $get_el_sub_res['el_sub_id'];
          }
        }

        if (count($el_sub_id_arr) > 0)
        {
          foreach ($el_sub_id_arr as $el_sub_id_res)
          {
            $this->master_model->updateRecord('spm_elearning_member_subjects', array('pt_id' => $get_user_regnum[0]['id'], 'transaction_no' => $transaction_no, 'receipt_no' => $MerchantOrderNo, 'status' => 2, 'updated_on' => date('Y-m-d H:i:s')), array('el_sub_id' => $el_sub_id_res));
          }
        }
        //END : UPDATE FAIL STATUS IN spm_elearning_member_subjects

        //Query to get Payment details	
        $payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $get_user_regnum[0]['member_regnumber']), 'transaction_no,date,amount');

        //Query to get user details
        $result = $this->master_model->getRecords('spm_elearning_registration', array('regnumber' => $get_user_regnum[0]['member_regnumber']), 'firstname, middlename, lastname, email, mobile');

        $username = $result[0]['firstname'] . ' ' . $result[0]['middlename'] . ' ' . $result[0]['lastname'];
        $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
        $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'transaction_fail'));
        $newstring1 = str_replace("#application_num#", "" . $get_user_regnum[0]['member_regnumber'] . "",  $emailerstr[0]['emailer_text']);
        $newstring2 = str_replace("#username#", "" . $userfinalstrname . "",  $newstring1);
        $newstring3 = str_replace("#transaction_id#", "" . $payment_info[0]['transaction_no'] . "",  $newstring2);
        $final_str = str_replace("#transaction_date#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "",  $newstring3);

        $info_arr = array(
          'to' => $result[0]['email'],
          'from' => $emailerstr[0]['from'],
          'subject' => $emailerstr[0]['subject'],
          'message' => $final_str
        );
        /* $info_arr=array('to'=>'Akshay.Shirke@esds.co.in',
						'from'=>$emailerstr[0]['from'],
						'subject'=>$emailerstr[0]['subject'],
						'message'=>$final_str
					);	 */

        //send sms to Ordinary Member
        //$sms_final_str = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
        //$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
        //$this->master_model->send_sms('8793012005',$sms_final_str);
        //$this->Emailsending->mailsend($info_arr);
        //Manage Log
        $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
        $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
      }
      //End Of SBICALL Back	
      //redirect(base_url().'ApplyDbfElearning/fail/'.base64_encode($MerchantOrderNo));

      redirect(base_url() . 'ApplyElearning/mail_fail/' . $MerchantOrderNo);
    }
    else
    {
      die("Please try again...");
    }
  }

  public function mail_fail($receipt_no = 0)
  {
    if ($receipt_no != '0')
    {
      $payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $receipt_no));

      $user_info = array();
      $disp_member_no = '';
      if (count($payment_info) > 0)
      {
        $user_info = $this->master_model->getRecords('spm_elearning_registration', array('regid' => $payment_info[0]['ref_id']));

        $disp_member_no = $payment_info[0]['member_regnumber'];
      }

      $data['disp_member_no'] = $disp_member_no;

      ## Clear session data
      $this->session->set_userdata('session_array', '');
      $this->session->unset_userdata('session_array');

      $payment_status = 'Failed';
      if ($payment_info['status'] == 2)
      {
        $payment_status = 'Pending';
      }
      $data['transaction_status'] = $payment_status;
      $data['user_info'] = $user_info;
      $data['payment_info'] = $payment_info;
      $this->load->view('apply_elearning/elearning_payment_response', $data);
    }
    else
    {
      redirect(site_url('ApplyElearning'));
    }
  }

  //GENERATE ELEARNING INVOICE NUMBER & ELEARNING INVOICE
  public function custom_generate_el_invoice_no()
  {  //exit;
    $receipt_no = '903494336';

    $this->db->limit(1);
    $payment_data = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $receipt_no, 'status' => '1', 'pay_type' => '20'), '', array('id' => 'DESC'));

    if (count($payment_data) > 0)
    {
      $this->db->limit(1);
      $invoice_data = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $receipt_no), '', array('invoice_id' => 'DESC'));

      if (count($invoice_data) > 0)
      {
        $invoice_id = $invoice_data[0]['invoice_id'];
        $invoiceNumber = generate_el_invoice_number($invoice_id);
        if ($invoiceNumber)
        {
          //$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
          /* $cyear = date("y");
          $nyear = $cyear + 1;
          $invoiceNumber = 'EL/' . $cyear . '-' . $nyear . '/' . $invoiceNumber; */

          //START : THIS CODE WAS UPDATED BY SAGAR M ON 2024-04-16. EARLIER THE INVOICE YEAR FOLLOW THE CALENDER YEAR INSTEAD OF FINANCIAL YEAR
          if(date("Y-m-d") >= date("Y").'-04-01') { $cyear = date("y"); } else { $cyear = date('y') - 1; }
          $nyear = $cyear + 1;
          if($cyear.'-'.$nyear == '24-25' && $invoiceNumber >= 6860) { $invoiceNumber = $invoiceNumber + 3056; }
          $invoiceNumber = 'EL/' . $cyear . '-' . $nyear . '/' . str_pad($invoiceNumber,6,0,STR_PAD_LEFT);
          //END : THIS CODE WAS UPDATED BY SAGAR M ON 2024-04-16. EARLIER THE INVOICE YEAR FOLLOW THE CALENDER YEAR INSTEAD OF FINANCIAL YEAR
        }

        $update_data = array('invoice_no' => $invoiceNumber, 'transaction_no' => $payment_data[0]['transaction_no'], 'date_of_invoice' => $payment_data[0]['date'], 'modified_on' => date('Y-m-d H:i:s'));
        $this->db->where('pay_txn_id', $payment_data[0]['id']);
        $this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $receipt_no));
        echo $attachpath = genarate_el_invoice($invoice_id);
      }
      else
      {
        echo 'Invoice not found';
      }
    }
    else
    {
      echo 'Payment not found';
    }
  }

  public function custom_generate_elearning_invoice()
  {
    exit;
    $invoice_id_arr = array('4197142');
    foreach ($invoice_id_arr as $invoice_id)
    {
      echo '<br>' . $attachpath = genarate_el_invoice($invoice_id);
    }
  }

  /* SELECT ms.el_sub_id, ms.regid, ms.regnumber, ms.pt_id, ms.transaction_no, ms.receipt_no, ms.exam_code, ms.subject_code, pt.member_regnumber, pt.transaction_no AS p_transaction_no, pt.receipt_no AS p_receipt_no, pt.ref_id,  pt.transaction_details, pt.status AS p_status, pt.auth_code, pt.bankcode, pt.paymode, pt.callback

		FROM spm_elearning_member_subjects ms
		LEFT JOIN payment_transaction pt ON pt.id = ms.pt_id
		WHERE ms.status = '1' AND pt.pay_type = '20' AND pt.status = '1' AND DATE(ms.created_on) = '2021-07-09'
		AND pt.transaction_no IS NULL 
		GROUP BY pt.receipt_no */
  //https://115.124.123.26/ApplyElearning/temp_update_transaction_no

  /* public function update_payment_status()
		{	exit;
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			
			$receipt_no_arr = array(902855884, 902855894, 902855900, 902855902, 902855904, 902855919, 902855926, 902855935, 902855943, 902855960, 902855967, 902855968, 902855971, 902855978, 902855980, 902855994, 902856013, 902856017, 902856020, 902856025, 902856027, 902856045, 902856055, 902856060, 902856085, 902856088, 902856101, 902856113, 902856117, 902856118, 902856131, 902856134, 902856148, 902856165, 902856218, 902856241, 902856248, 902856286, 902856295, 902856301, 902856303, 902856331, 902856362, 902856365, 902856369, 902856378, 902856381, 902856401, 902856409, 902856418, 902856471, 902856526, 902856528, 902856533, 902856543, 902856544, 902856549, 902856556, 902856572, 902856616, 902856627, 902856633, 902856652, 902856657, 902856667);
			
			if(count($receipt_no_arr) > 0)
			{
				foreach($receipt_no_arr as $res)
				{
					$receipt_no = $res;
					
					$responsedata = sbiqueryapi($receipt_no);
					if(count($responsedata) > 0)
					{					
						$sbi_status = $responsedata[2];
						
						if($sbi_status == "FAIL" || $sbi_status == "ABORT")
						{
							$update_payment['status'] = '0'; 
							$this->master_model->updateRecord('payment_transaction',$update_payment,array('receipt_no'=>$receipt_no, 'callback'=>'C_S2S', 'status'=>'1'));
							echo "<br>".$this->db->last_query();
							
							$update_sub['status'] = '2'; 
							$this->master_model->updateRecord('spm_elearning_member_subjects',$update_sub,array('receipt_no'=>$receipt_no, 'status'=>'1'));
							echo "<br>".$this->db->last_query();
							
							echo "<br>".$receipt_no." >> updated";
							echo "<br>==========================================================================";
						}
						else
						{
							echo "<br>".$receipt_no." >> Not updated";
							echo "<br>==========================================================================";
						}
					}
				}
			}			
		} */

  public function check_transaction_status($date = '')
  {
    include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
    $key = $this->config->item('sbi_m_key');

    $aes = new CryptAES();
    $aes->set_key(base64_decode($key));
    $aes->require_pkcs5();

    if ($date == '')
    {
      $date = date("Y-m-d");
    }

    $select = 'ms.created_on, ms.regnumber, ms.pt_id, ms.transaction_no, ms.receipt_no, ms.exam_code, ms.subject_code, pt.transaction_no AS p_transaction_no, pt.receipt_no AS p_receipt_no, pt.transaction_details, pt.status AS p_status, pt.auth_code, pt.bankcode, pt.paymode, pt.callback, pt.date';
    $this->db->group_by('pt.receipt_no');
    $this->db->join('payment_transaction pt', 'pt.id = ms.pt_id', 'LEFT', FALSE);
    $qry = $this->master_model->getRecords('spm_elearning_member_subjects ms', array('ms.status' => '1', 'pt.status' => '1', 'pt.pay_type' => '20', 'DATE(ms.created_on)' => $date), $select); // 
    echo $this->db->last_query();
    if (count($qry) > 0)
    {
      echo '<style>table { border-collapse:collapse; }  td, th { border:1px solid #000; padding:4px 10px; }</style>
							<br><br>
							<table>
								<thead><th>Sr No</th><th>Date</th><th>Receipt Number</th><th>Payment Status</th><th>Payment Transaction No</th><th>SBI Status</th><th>SBI Transaction No</th><th>Description</th><th>Callback</th></thead>
								<tbody>';

      $i = 1;
      foreach ($qry as $res)
      {
        $responsedata = sbiqueryapi($res['receipt_no']);

        $sbi_status = $responsedata[2];
        if ($sbi_status != 'SUCCESS')
        {
          $bg_color = 'background:#ccc';
        }
        else
        {
          $bg_color = '';
        }
        echo '<tr style="' . $bg_color . '">';
        echo '<td>' . $i . '</td>';
        echo '<td>' . $res['created_on'] . '</td>';
        echo '<td>' . $res['receipt_no'] . '</td>';
        echo '<td>' . $res['p_status'] . '</td>';
        echo '<td>' . $res['transaction_no'] . '</td>';
        echo '<td>' . $sbi_status . '</td>';
        echo '<td>' . $responsedata[1] . '</td>';
        echo '<td>' . $responsedata[8] . '</td>';
        echo '<td>' . $res['callback'] . '</td>';
        echo '</tr>';

        $i++;
      }

      echo '	</tbody>
							</table>';
    }
  }

  public function test_payment()
  {
    /* echo '<br>IP Address : 115.124.108.36 whitelisted';

    $url = 'https://api.ipify.org';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $publicIP = curl_exec($ch);
    curl_close($ch);
    echo '<br>1. curl : '.$publicIP;

    if (getenv('HTTP_CLIENT_IP'))
      echo '<br>2. HTTP_CLIENT_IP : '.getenv('HTTP_CLIENT_IP');
    
    if(getenv('HTTP_X_FORWARDED_FOR'))
      echo '<br>3. HTTP_X_FORWARDED_FOR : '.getenv('HTTP_X_FORWARDED_FOR');
    
    if(getenv('HTTP_X_FORWARDED'))
      echo '<br>4. HTTP_X_FORWARDED : '.getenv('HTTP_X_FORWARDED');
    
    if(getenv('HTTP_FORWARDED_FOR'))
      echo '<br>5. HTTP_FORWARDED_FOR : '.getenv('HTTP_FORWARDED_FOR');
    
    if(getenv('HTTP_FORWARDED'))
      echo '<br>6. HTTP_FORWARDED : '.getenv('HTTP_FORWARDED');
    
    if(getenv('REMOTE_ADDR'))
      echo '<br>7. REMOTE_ADDR : '.getenv('REMOTE_ADDR');

    echo '<br>8. SERVER_ADDR : '.$_SERVER['SERVER_ADDR'];
    
    $app_server = explode('.',gethostname());
    echo '<br>'; print_r($app_server); */
    
    if($this->config->item('bd_payment_mode_sm') == 'production')
    {
      echo 'Current Billdesk Payment Mode is Production instead of Sandbox.'; exit;
    }

    $MerchantOrderNo ='9999999999999999991'.date("YmdHis");
    $amount = '101';
    $regid = '123456';
    $pg_flag = "IIBFELS";
    $custom_field_billdesk = $MerchantOrderNo."-iibfexam-".$pg_flag."-".$regid;
    $billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $regid, $regid, '', 'ApplyElearning/handle_billdesk_response', '', '', '', $custom_field_billdesk);
    
    echo '<pre>'; print_r($billdesk_res); echo '</pre>'; exit;      
  }

  public function update_state_code_in_exam_invoice_custom()
  {
    exit;
    /*$invoice_data = $this->master_model->getRecords('exam_invoice',array('app_type'=>'EL', 'state_code'=>'0', 'state_name'=>''), 'invoice_id, state_of_center'); 
      
			if(count($invoice_data) > 0)
			{
				foreach($invoice_data as $res)
				{
					$state_of_center = $res['state_of_center'];
          
          $getstate = $this->master_model->getRecords('state_master',array('state_code'=>$state_of_center,'state_delete'=>'0'));
        
          $up_state_code = $up_state_name = '';
          if(count($getstate) > 0)
          {
            $up_state_code = $getstate[0]['state_no'];
            $up_state_name = $getstate[0]['state_name'];
            
            $update_data['state_code'] = $up_state_code; 
            $update_data['state_name'] = $up_state_name; 
            $this->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$res['invoice_id']));
          }
				}
			}	*/
  }

  /* function update_fees_in_spm_subjects()
		{	exit;
			$this->db->join('spm_elearning_registration er', 'er.regnumber = ms.regnumber', 'LEFT');
			$this->db->where('ms.el_sub_id <= ', '9431');
			$mem_sub_data = $this->master_model->getRecords('spm_elearning_member_subjects ms',array(), 'ms.el_sub_id, ms.exam_code, ms.subject_code, ms.regnumber, ms.fee_amount, ms.sgst_amt, ms.cgst_amt, ms.igst_amt, ms.cs_tot, ms.igst_tot, er.state');
			echo $this->db->last_query();
			
			$this->db->join('spm_elearning_fee_master fm', 'fm.subject_code = sm.subject_code', 'INNER', FALSE);
      $all_subject_fee_data = $this->master_model->getRecords('spm_elearning_subject_master sm',array('sm.subject_delete'=>0), 'sm.subject_code, sm.subject_description, fm.fee_amount, fm.sgst_amt, fm.cgst_amt, fm.igst_amt, fm.cs_tot, fm.igst_tot');
      
      $all_subject_fee_arr = array();
      if(count($all_subject_fee_data) > 0)
      {
        foreach($all_subject_fee_data as $all_subject_res)
        {
          $all_subject_fee_arr[$all_subject_res['subject_code']] = $all_subject_res;
        }
      }
			//echo '<pre>'; print_r($all_subject_fee_arr);	echo '</pre>';exit;
			
			$cnt = 0;
			if(count($mem_sub_data) > 0)
			{
				foreach($mem_sub_data as $res)
				{
					$fee_amount = $sgst_amt = $cgst_amt = $igst_amt = $cs_tot = $igst_tot = 0;
					if(array_key_exists($res['subject_code'],$all_subject_fee_arr))
					{
						$state_code = $res['state'];
						if($state_code != "")
						{
							if($state_code=='MAH') //fee_amount, sgst_amt, cgst_amt, cs_tot
							{
								$cs_tot = $all_subject_fee_arr[$res['subject_code']]['cs_tot'];
								$cgst_amt = $all_subject_fee_arr[$res['subject_code']]['cgst_amt'];
								$sgst_amt = $all_subject_fee_arr[$res['subject_code']]['sgst_amt'];
							}
							else //fee_amount, igst_amt, igst_tot
							{
								$igst_amt = $all_subject_fee_arr[$res['subject_code']]['igst_amt'];
								$igst_tot = $all_subject_fee_arr[$res['subject_code']]['igst_tot'];
							}
							
							$fee_amount = $all_subject_fee_arr[$res['subject_code']]['fee_amount'];
							
							echo "<br>state_code : ".$state_code;
							echo "<br>fee_amount : ".$fee_amount;
							echo "<br>sgst_amt : ".$sgst_amt;
							echo "<br>cgst_amt : ".$cgst_amt;
							echo "<br>igst_amt : ".$igst_amt;
							echo "<br>cs_tot : ".$cs_tot;
							echo "<br>igst_tot : ".$igst_tot;
							echo '<br>================================================ <br>';
							
							$up_data['fee_amount'] = $fee_amount; 
							$up_data['sgst_amt'] = $sgst_amt; 
							$up_data['cgst_amt'] = $cgst_amt; 
							$up_data['igst_amt'] = $igst_amt; 
							$up_data['cs_tot'] = $cs_tot; 
							$up_data['igst_tot'] = $igst_tot; 
							//$this->master_model->updateRecord('spm_elearning_member_subjects',$up_data,array('el_sub_id'=>$res['el_sub_id']));  
							
							$cnt++;
						}
					}
				}
			}
			echo '<br> Count : '.$cnt;
		} */

  function test_param()
  {
    echo '<br>bd_traceid : '.$bd_traceid = time() .substr(md5(mt_rand()), 0, 7);
    echo '<br>bd_timestamp : '.$bd_timestamp =time();
  }

  function test_param2()
  {
    echo '<br>Current date time :'.$datetime = date('Y-m-d H:i:s');
    echo '<br>Current time :'.$time = time();
    echo '<br><br>Random String :'.$random_str = mt_rand();
    echo '<br>MD5 Random String :'.$random_str_md5 = md5($random_str);
    echo '<br>Substring of MD5 Random String :'.$substr_random_str_md5 = substr(md5($random_str), 0, 7);

    echo '<br><br>bd_traceid : '.$bd_traceid = $time .$substr_random_str_md5;
    echo '<br>bd_timestamp : '.$bd_timestamp = $time;
  }
}
