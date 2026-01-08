<?php defined('BASEPATH') or exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");

class Cpd extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->library('upload');
    $this->load->helper('upload_helper');
    $this->load->helper('master_helper');
    $this->load->library('email');
    $this->load->model('Emailsending');
    $this->load->model('log_model');
    $this->load->model('UserModel');
    $this->load->model('billdesk_pg_model');
  }

  public function index()
  {
    //cpatcha generation
    /* $this->load->helper('captcha');
				$vals = array(
				'img_path' => './uploads/applications/',
				'img_url' => base_url().'uploads/applications/',
				);
				$cap = create_captcha($vals); 
			$_SESSION["regcaptcha"] = $cap['word']; */

    $this->load->model('Captcha_model');
    $captcha_img = $this->Captcha_model->generate_captcha_img('regcaptcha');

    $data = array('middle_content' => 'cpd/cpd_register', 'image' => $captcha_img);    //print_r($data); 
    $this->load->view('common_view_fullwidth', $data);
  }

  public function getdetails()
  {
    $msgflag = 1;
    if ($this->session->userdata('cpduserinfo'))
    {
      $this->session->unset_userdata('cpduserinfo');
    }
    $data['validation_errors'] = '';
    //getting existing data of member
    if (isset($_POST['btn_Submit']))
    {
      $msgflag = 0;
      if (!empty($_POST['member_no']))
      {
        $config = array(
          array(
            'field' => 'member_no',
            'label' => 'Registration/Membership No.',
            'rules' => 'trim|required'
          ),
          array(
            'field' => 'code',
            'label' => 'Code',
            'rules' => 'trim|required|callback_check_captcha_userreg',
          ),
        );
        $this->form_validation->set_rules($config);
        $dataarr = array(
          'regnumber' => mysqli_real_escape_string($this->db->conn_id, $this->security->xss_clean($this->input->post('member_no'))),
          'isactive' => '1',
          'isdeleted' => '0'
        );
        $request_cnt = mysqli_real_escape_string($this->db->conn_id, $_POST['request_cnt']) + 1;
        $member_no = ltrim(rtrim(mysqli_real_escape_string($this->db->conn_id, $_POST['member_no'])));
        if ($this->form_validation->run() == TRUE)
        {
          if (!empty($member_no) && $request_cnt == '1')
          {
            $mem_data = $this->master_model->getRecords('cpd_registration', array('member_no' => $member_no, 'pay_status' => '1', 'validate_upto!=' => ''), array('created_on', 'validate_upto', 'member_no'), array('id' => 'desc'));
            if (!empty($mem_data))
            { //print_r($mem_data);
              $created_on = $mem_data[0]['created_on'];
              $validate_upto = $mem_data[0]['validate_upto'];
              $todays_date = date('Y-m-d H:i:s');
              //echo $this->db->last_query();
              //echo '<pre>',print_r($mem_data),'</pre>';exit;

              if ($created_on <= $todays_date && $validate_upto >= $todays_date)
              {
                $this->session->set_flashdata('error', 'You have already applied for CPD, Your application is valid for two years');
                redirect(base_url() . 'Cpd');
              }
              else
              {
                $this->db->where_in('member_registration.registrationtype', array('O', 'F', 'A'));
                $result_data = $this->master_model->getRecords('member_registration', array('regnumber' => $member_no, 'isactive' => '1'));
              }
            }
            else
            {
              $this->db->where_in('member_registration.registrationtype', array('O', 'F', 'A'));
              $result_data = $this->master_model->getRecords('member_registration', array('regnumber' => $member_no, 'isactive' => '1'));
              //echo $this->db->last_query();
            }
          }
          else
          {
            $this->session->set_flashdata('error', 'Invalid Captcha');
            redirect(base_url() . 'Cpd');
          }
        }
        else
        {
          $this->session->set_flashdata('error', 'Invalid membership no. or captcha.');
          redirect(base_url() . 'Cpd');
        }
      }
    }
    if (isset($_POST['btnSubmit']))
    {
      $msgflag = 0;
      $date = date('Y-m-d H:i:s');

      $this->form_validation->set_rules('sel_namesub', 'Name Prefix', 'trim|required|xss_clean');
      $this->form_validation->set_rules('firstname', 'First Name', 'trim|max_length[30]|required|xss_clean');
      $this->form_validation->set_rules('middlename', 'Middle Name', 'trim|max_length[30]|xss_clean');
      $this->form_validation->set_rules('lastname', 'Last Name', 'trim|max_length[30]|xss_clean');
      $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
      $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
      $this->form_validation->set_rules('addressline1', 'Address line1', 'trim|max_length[80]|required|xss_clean');
      if (isset($_POST['addressline2']) && $_POST['addressline2'] != '')
      {
        $this->form_validation->set_rules('addressline2', 'Address line2', 'trim|max_length[40]|required|xss_clean');
      }
      if (isset($_POST['addressline3']) && $_POST['addressline3'] != '')
      {
        $this->form_validation->set_rules('addressline3', 'Address line3', 'trim|max_length[40]|required|xss_clean');
      }
      if (isset($_POST['addressline4']) && $_POST['addressline4'] != '')
      {
        $this->form_validation->set_rules('addressline4', 'Address line4', 'trim|max_length[40]|required|xss_clean');
      }
      $this->form_validation->set_rules('district', 'District', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
      $this->form_validation->set_rules('city', 'City', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
      $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean');
      if ($this->input->post('state') != '')
      {
        $state = $this->input->post('state');
      }
      $this->form_validation->set_rules('pincode', 'Pincode/Zipcode', 'trim|required|numeric|xss_clean|callback_check_checkpin[' . $state . ']');
      $this->form_validation->set_rules('optedu', 'Qualification', 'trim|required|xss_clean');
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

      $this->form_validation->set_rules('designation', 'Designation', 'trim|required|xss_clean');
      if (isset($_POST['experience']) && $_POST['experience'] == 'P')
      {
        $this->form_validation->set_rules('experience', 'Number of years experience', 'trim|required|xss_clean');
      }
      $this->form_validation->set_rules('associatedinstitute', 'Bank Name', 'trim|required|alpha_numeric_spaces|xss_clean');
      if (isset($_POST['bank_address']))
      {
        $this->form_validation->set_rules('office', 'Branch/Office address', 'trim|required|xss_clean');
      }
      $this->form_validation->set_rules('code', 'Security Code', 'trim|required|xss_clean|callback_check_captcha_userreg');

      if ($this->form_validation->run() == TRUE)
      {
        if ($_POST['optedu'] == 'U')
        {
          $specified_qualification = $_POST["eduqual1"];
        }
        else if ($_POST['optedu'] == 'G')
        {
          $specified_qualification = $_POST["eduqual2"];
        }
        else if ($_POST['optedu'] == 'P')
        {
          $specified_qualification = $_POST["eduqual3"];
        }

        if (!empty($_POST["member_no"]))
        {
          $data = array(
            'member_no' => $_POST["member_no"],
            'namesub' => $_POST["sel_namesub"],
            'firstname' => $_POST["firstname"],
            'middlename' => $_POST["middlename"],
            'lastname' => $_POST["lastname"],
            'email' => $_POST["email"],
            'mobile' => $_POST["mobile"],
            'addressline1' => $_POST["addressline1"],
            'addressline2' => $_POST["addressline2"],
            'addressline3' => $_POST["addressline3"],
            'addressline4' => $_POST["addressline4"],
            'district' => $_POST["district"],
            'city' => $_POST["city"],
            'state' => $_POST["state"],
            'pincode' => $_POST["pincode"],
            'qualification' => $_POST["optedu"],
            'specify_qualification' => $specified_qualification,
            'designation' => $_POST["designation"],
            'experience' => $_POST["experience"],
            'associatedinstitute' => $_POST["associatedinstitute"],
            'office' => trim($_POST["office"]),
            'created_on' => $date

          );

          $this->session->set_userdata('cpduserinfo', $data);
          $this->form_validation->set_message('error', "");
          redirect(base_url() . 'Cpd/preview');
        }
      }
    }

    //getting qualification
    $undergraduate = $this->master_model->getRecords('qualification', array('type' => 'UG'));
    $graduate = $this->master_model->getRecords('qualification', array('type' => 'GR'));
    $postgraduate = $this->master_model->getRecords('qualification', array('type' => 'PG'));

    //getting institute
    $this->db->where('institution_master.institution_delete', '0');
    $institution_master = $this->master_model->getRecords('institution_master', '', '', array('name' => 'asc'));

    //getting designations
    $this->db->where('designation_master.designation_delete', '0');
    $designation = $this->master_model->getRecords('designation_master');

    //getting states
    $this->db->where('state_master.state_delete', '0');
    $states = $this->master_model->getRecords('state_master');

    //cpatcha generation
    /* $this->load->helper('captcha');
				$this->session->set_userdata("regcaptcha", rand(1, 100000));
				$vals = array(
				'img_path' => './uploads/applications/',
				'img_url' => base_url().'uploads/applications/',
				);
				$cap = create_captcha($vals);
			$_SESSION["regcaptcha"] = $cap['word']; */
    $this->load->model('Captcha_model');
    $captcha_image = $this->Captcha_model->generate_captcha_img('regcaptcha');

    if (!empty($result_data))
    {
      $data = array('middle_content' => 'cpd/cpd_register', 'result' => $result_data, 'undergraduate' => $undergraduate, 'graduate' => $graduate, 'postgraduate' => $postgraduate, 'institution_master' => $institution_master, 'designations' => $designation, 'states' => $states, 'image' => $captcha_image);

      $this->load->view('common_view_fullwidth', $data);
    }
    else if ($msgflag == 1)
    {
      $this->session->set_flashdata('error', 'You are not eligible to register for CPD.');
      redirect(base_url() . 'Cpd');
    }
    else
    {
      redirect(base_url() . 'Cpd');
    }
  }

  // reload captcha functionality
  public function generatecaptchaajax()
  {
    /* $this->load->helper('captcha');
				$this->session->unset_userdata("regcaptcha");
				$this->session->set_userdata("regcaptcha", rand(1, 100000));
				$vals = array('img_path' => './uploads/applications/','img_url' => base_url().'uploads/applications/',
				);
				$cap = create_captcha($vals);
				$data = $cap['image'];
				$_SESSION["regcaptcha"] = $cap['word'];
			echo $data; */
    $this->load->model('Captcha_model');
    echo $captcha_image = $this->Captcha_model->generate_captcha_img('regcaptcha');
  }

  //call back for checkpin
  public function check_checkpin($pincode, $statecode)
  {
    if ($statecode != "" && $pincode != '')
    {
      $statecode = mysqli_real_escape_string($this->db->conn_id, $statecode);
      $pincode   = mysqli_real_escape_string($this->db->conn_id, $pincode);
      $this->db->where("$pincode BETWEEN start_pin AND end_pin");
      $prev_count = $this->master_model->getRecordCount('state_master', array('state_code' => $statecode));
      //echo $this->db->last_query();
      if ($prev_count == 0)
      {
        $str = 'Please enter Valid Pincode';
        $this->form_validation->set_message('check_checkpin', $str);
        return false;
      }
      else
        $this->form_validation->set_message('error', "");
      {
        return true;
      }
    }
    else
    {
      $str = 'Pincode/State field is required.';
      $this->form_validation->set_message('check_checkpin', $str);
      return false;
    }
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

  //parsely email duplication validation
  public function emailduplication()
  {
    $email = mysqli_real_escape_string($this->db->conn_id, $_POST['email']);
    if ($email != "")
    {
      //$where="( registrationtype='O' OR registrationtype='A' OR registrationtype='F')";
      //$this->db->where($where);
      $prev_count = $this->master_model->getRecordCount('cpd_registration', array('email' => $email, 'is_active' => '1'));
      //echo $this->db->last_query();
      if ($prev_count == 0)
      {
        $data_arr = array('ans' => 'ok');
        echo json_encode($data_arr);
      }
      else
      {
        $user_info = $this->master_model->getRecords('cpd_registration', array('email' => $email), 'id,firstname,middlename,lastname');
        $username = @$user_info[0]['firstname'] . ' ' . @$user_info[0]['middlename'] . ' ' . @$user_info[0]['lastname'];
        $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
        $str = 'The entered email ID already exist for registration number ' . $user_info[0]['id'] . ' , ' . $userfinalstrname . '';
        $data_arr = array('ans' => 'exists', 'output' => $str);
        echo json_encode($data_arr);
      }
    }
    else
    {
      echo 'error';
    }
  }

  //parsely mobile duplication validation
  public function mobileduplication()
  {
    $mobile = mysqli_real_escape_string($this->db->conn_id, $_POST['contact_no']);
    if ($mobile != "")
    {
      //$where="(registrationtype='O' OR registrationtype='A' OR registrationtype='F')";
      //$this->db->where($where);
      $prev_count = $this->master_model->getRecordCount('cpd_registration', array('contact_no' => $mobile, 'is_active' => '1'));
      //echo $this->db->last_query();
      if ($prev_count == 0)
      {
        $data_arr = array('ans' => 'ok');
        echo json_encode($data_arr);
      }
      else
      {

        $user_info = $this->master_model->getRecords('cpd_registration', array('contact_no' => $mobile), 'id,firstname,middlename,lastname');
        $username = $user_info[0]['firstname'] . ' ' . $user_info[0]['middlename'] . ' ' . $user_info[0]['lastname'];
        $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
        $str = 'The entered mobile no already exist for registration number - ' . $user_info[0]['id'] . ',' . $userfinalstrname . '';
        $data_arr = array('ans' => 'exists', 'output' => $str);
        echo json_encode($data_arr);
      }
    }
    else
    {
      echo 'error';
    }
  }

  //parsely pincode validation
  public function checkpin()
  {
    $statecode = $_POST['statecode'];
    $pincode = $_POST['pincode'];
    if ($statecode != "")
    {
      $this->db->where("$pincode BETWEEN start_pin AND end_pin");
      $prev_count = $this->master_model->getRecordCount('state_master', array('state_code' => $statecode));
      //echo $this->db->last_query();
      //exit;
      if ($prev_count == 0)
      {
        echo 'false';
      }
      else
      {
        echo 'true';
      }
    }
    else
    {
      echo 'false';
    }
  }

  public function preview()
  {
    if (!$this->session->userdata('cpduserinfo'))
    {
      redirect(base_url());
    }

    $user_details = $this->master_model->getRecords('member_registration', array('regnumber' => $this->session->userdata['cpduserinfo']['member_no'], 'isactive' => '1'));

    if (count($user_details) <= 0)
    {
      $this->session->set_flashdata('error', 'Invalid User!!');
      redirect(base_url() . 'Cpd');
    }

    $state = $user_details[0]['state'];
    if (!empty($state))
    {
      if ($state == 'MAH')
      {
        $amount = $this->config->item('CPD_cs_total');
      }
      else
      {
        $amount = $this->config->item('CPD_igst_tot');
      }
      $this->session->userdata['cpduserinfo']['fees'] = $amount;
    }
    $undergraduate = $this->master_model->getRecords('qualification', array('type' => 'UG'));
    $graduate = $this->master_model->getRecords('qualification', array('type' => 'GR'));
    $postgraduate = $this->master_model->getRecords('qualification', array('type' => 'PG'));
    $institution_master = $this->master_model->getRecords('institution_master');
    $states = $this->master_model->getRecords('state_master');
    $designation = $this->master_model->getRecords('designation_master');

    $data = array('middle_content' => 'cpd/preview_cpdregister', 'states' => $states, 'undergraduate' => $undergraduate, 'graduate' => $graduate, 'postgraduate' => $postgraduate, 'institution_master' => $institution_master, 'designation' => $designation, 'user_details' => $user_details);
    $this->load->view('common_view_fullwidth', $data);
  }

  public function register()
  {

    if (!$this->session->userdata['cpduserinfo'])
    {
      redirect(base_url());
    }

    $user_details = $this->master_model->getRecords('member_registration', array('regnumber' => $this->session->userdata['cpduserinfo']['member_no'], 'isactive' => '1'));

    if (count($user_details) <= 0)
    {
      $this->session->set_flashdata('error', 'Invalid User!!');
      redirect(base_url() . 'Cpd');
    }
    $member_no = $this->session->userdata['cpduserinfo']['member_no'];
    if (!empty($user_details))
    {
      //get branch if office is blank
      $office = '';
      if ($user_details[0]['office'] != '')
      {
        $office = $user_details[0]['office'];
      }
      elseif ($user_details[0]['branch'] != '')
      {
        $office = $user_details[0]['branch'];
      }
      else
      {
        $office = $user_details[0]['office'];
      }


      //$editedon = date('Y-m-d', strtotime($user_details[0]['createdon']));
      /*$editedon = date('Y-m-d', strtotime($user_details[0]['editedon']));
					if($editedon < "2016-12-29")
					{
					$office = $user_details[0]['branch'];
					}
					else if($editedon >= "2016-12-29")
					{
					if(is_numeric($user_details[0]['office']))
					{
					if($user_details[0]['branch']!='')
					$office = $user_details[0]['branch'];
					else
					$office = $user_details[0]['office'];
					}
					else
					{
					if($user_details[0]['branch']!='')
					$office = $user_details[0]['branch'];
					else
					$office = $user_details[0]['office'];
					}
				}*/

      $insert_arr = array(
        'member_no' => $this->session->userdata['cpduserinfo']['member_no'],
        'registrationtype' => $user_details[0]['registrationtype'],
        'namesub' => $user_details[0]['namesub'],
        'firstname' => strtoupper($user_details[0]['firstname']),
        'middlename' => strtoupper($user_details[0]['middlename']),
        'lastname' => strtoupper($user_details[0]['lastname']),
        'email' => $user_details[0]['email'],
        'mobile' => $user_details[0]['mobile'],
        'address1' => strtoupper($user_details[0]['address1']),
        'address2' => strtoupper($user_details[0]['address2']),
        'address3' => strtoupper($user_details[0]['address3']),
        'address4' => strtoupper($user_details[0]['address4']),
        'district' => strtoupper($user_details[0]['district']),
        'city' => strtoupper($user_details[0]['city']),
        'state' => $user_details[0]['state'],
        'pincode' => $user_details[0]['pincode'],
        'qualification' => $user_details[0]['qualification'],
        'specified_qualification' => $user_details[0]['specify_qualification'],
        'designation' => $user_details[0]['designation'],
        'experience' => $this->session->userdata['cpduserinfo']['experience'],
        'associatedinstitute' => $user_details[0]['associatedinstitute'],
        'office' => $office,
        'fee' => $this->session->userdata['cpduserinfo']['fees'],
        'created_on' => date('Y-m-d H:i:s')
      );
    }
    if ($last_id = $this->master_model->insertRecord('cpd_registration', $insert_arr, true))
    {

      $userarr = array(
        'regno' => $last_id,
        'member_no' => $member_no
      );
      $this->session->set_userdata('memberdata', $userarr);

      redirect(base_url() . "Cpd/make_payment");
    }
    else
    {
      $userarr = array(
        'regno' => '',
        'member_no' => ''
      );
      $this->session->set_userdata('memberdata', $userarr);
      //$this->make_payment();
      $this->session->set_flashdata('error', 'Error while during registration.please try again!');
      redirect(base_url());
    }
  }

  public function make_payment()
  {

    $cgst_rate = $sgst_rate = $igst_rate = $tax_type = '';
    $cgst_amt = $sgst_amt = $igst_amt = '';
    $cs_total = $igst_total = '';
    $getstate = $getcenter = $getfees = array();
    $flag = 1;

    $regno = $this->session->userdata['memberdata']['regno'];
    $member_no = $this->session->userdata['memberdata']['member_no'];

    if (!empty($regno))
    {
      //getting state
      $member_state = $this->master_model->getRecords('cpd_registration', array('id' => $regno, 'pay_status' => '0'), array('state'));
      $member = $this->master_model->getRecords('cpd_registration', array('id' => $regno, 'pay_status!=' => '0'));
      //echo $this->db->last_query(); exit;
      if (count($member) > 0)
      {
        redirect('http://iibf.org.in');
      }
    }
    if (isset($_POST['processPayment']) && $_POST['processPayment'])
    {
      $pg_name = $this->input->post('pg_name');
      /* include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
					$key = $this->config->item('sbi_m_key');
					$merchIdVal = $this->config->item('sbi_merchIdVal');
					$AggregatorId = $this->config->item('sbi_AggregatorId');
					
					$pg_success_url = base_url()."Cpd/sbitranssuccess";
				$pg_fail_url    = base_url()."Cpd/sbitransfail"; */

      $state = $member_state[0]['state'];
      if (!empty($state))
      {
        if ($state == 'MAH')
        {
          $amount = $this->config->item('CPD_cs_total');
        }
        else
        {
          $amount = $this->config->item('CPD_igst_tot');
        }
      }
      if (!empty($state))
      {
        //get state code,state name,state number.
        $getstate = $this->master_model->getRecords('state_master', array('state_code' => $state, 'state_delete' => '0'));
      }
      if ($state == 'MAH')
      {
        //set a rate (e.g 9%,9% or 18%)
        $cgst_rate = $this->config->item('CPD_cgst_rate');
        $sgst_rate = $this->config->item('CPD_sgst_rate');
        //set an amount as per rate
        $cgst_amt = $this->config->item('CPD_cgst_amt');
        $sgst_amt = $this->config->item('CPD_sgst_amt');
        //set an total amount
        $cs_total = $amount;
        $tax_type = 'Intra';
      }
      else
      {
        $igst_rate = $this->config->item('CPD_igst_rate');
        $igst_amt = $this->config->item('CPD_igst_amt');
        $igst_total = $amount;
        $tax_type = 'Inter';
      }

      /*if($getstate[0]['exempt']=='E')
					{
					$cgst_rate=$sgst_rate=$igst_rate='';	
					$cgst_amt=$sgst_amt=$igst_amt='';	
					$igst_total=$this->config->item('CPD_apply_fee');
					$amount = $this->config->item('CPD_apply_fee');	
				}*/
      // Create transaction
      $insert_data = array(
        'member_regnumber' => $member_no,
        'gateway'     => "sbiepay",
        'amount'      => $amount,
        'date'        => date('Y-m-d H:i:s'),
        'ref_id'    =>  $regno,
        'description' => "CPD Registration",
        'pay_type'    => 9,
        'status'      => 2,
        //'receipt_no'  => $MerchantOrderNo,
        'pg_flag' => 'iibfcpd',
        //'pg_other_details'=>$custom_field
      );

      $pt_id = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
      $MerchantOrderNo = reg_sbi_order_id($pt_id);
      $custom_field = $MerchantOrderNo . "^iibfregn^iibfcpd^" . $member_no;
      $custom_field_billdesk = $MerchantOrderNo . "-iibfregn-iibfcpd-" . $member_no;

      $payment_insert_query = $this->db->last_query();
      $log_message = serialize($payment_insert_query);
      $titlt = "CPD Payment inserton" . $this->session->userdata['memberdata']['member_no'];
      $logs = array(
        'title' => $titlt,
        'description' => $log_message
      );
      $this->master_model->insertRecord('cpd_logs', $logs, true);

      // update receipt no. in payment transaction -
      $update_data = array('receipt_no' => $MerchantOrderNo, 'pg_other_details' => $custom_field);
      $this->master_model->updateRecord('payment_transaction', $update_data, array('id' => $pt_id));

      $payment_receipt_no_update_query = $this->db->last_query();
      $log_message = serialize($payment_receipt_no_update_query);
      $titlt = "CPD receipt_no update" . $this->session->userdata['memberdata']['member_no'];
      $logs = array(
        'title' => $titlt,
        'description' => $log_message
      );
      $this->master_model->insertRecord('cpd_logs', $logs, true);

      $invoice_insert_array = array(
        'pay_txn_id' => $pt_id,
        'receipt_no' => $MerchantOrderNo,
        'exam_code' => '',
        'state_of_center' => $state,
        'member_no' => $member_no,
        'app_type' => 'P', //P for CPD app type
        'service_code' => $this->config->item('cpd_service_code'),
        'qty' => '1',
        'state_code' => $getstate[0]['state_no'],
        'state_name' => $getstate[0]['state_name'],
        'tax_type' => $tax_type,
        'fee_amt' => $this->config->item('CPD_apply_fee'),
        'cgst_rate' => $cgst_rate,
        'cgst_amt' => $cgst_amt,
        'sgst_rate' => $sgst_rate,
        'sgst_amt' => $sgst_amt,
        'igst_rate' => $igst_rate,
        'igst_amt' => $igst_amt,
        'cs_total' => $cs_total,
        'igst_total' => $igst_total,
        'exempt' => $getstate[0]['exempt'],
        'created_on' => date('Y-m-d H:i:s')
      );


      $inser_id = $this->master_model->insertRecord('exam_invoice', $invoice_insert_array);

      $invoice_insert_query = $this->db->last_query();
      $log_message = serialize($invoice_insert_query);
      $titlt = "CPD Invoice inserton" . $this->session->userdata['memberdata']['member_no'];
      $logs = array(
        'title' => $titlt,
        'description' => $log_message
      );
      $this->master_model->insertRecord('cpd_logs', $logs, true);

      /* $MerchantCustomerID = $regno;
					
					$data["pg_form_url"] = $this->config->item('sbi_pg_form_url'); // SBI ePay form URL
					$data["merchIdVal"]  = $merchIdVal;
					
					
					$EncryptTrans = $merchIdVal."|DOM|IN|INR|".$amount."|".$custom_field."|".$pg_success_url."|".$pg_fail_url."|".$AggregatorId."|".$MerchantOrderNo."|".$MerchantCustomerID."|NB|ONLINE|ONLINE";
					
					$aes = new CryptAES();
					$aes->set_key(base64_decode($key));
					$aes->require_pkcs5();
					
					$EncryptTrans = $aes->encrypt($EncryptTrans);
					
					$data["EncryptTrans"] = $EncryptTrans; // SBI encrypted form field value
				$this->load->view('pg_sbi_form',$data); */


      //added by chaitali 2022-02-10
      if ($pg_name == 'sbi')
      {
        include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
        $key = $this->config->item('sbi_m_key');
        $merchIdVal = $this->config->item('sbi_merchIdVal');
        $AggregatorId = $this->config->item('sbi_AggregatorId');

        $pg_success_url = base_url() . "Cpd/sbitranssuccess";
        $pg_fail_url    = base_url() . "Cpd/sbitransfail";
        //exit;
        $MerchantCustomerID  = $inser_id;
        $data["pg_form_url"] = $this->config->item('sbi_pg_form_url');
        $data["merchIdVal"]  = $merchIdVal;
        $EncryptTrans        = $merchIdVal . "|DOM|IN|INR|" . $amount . "|" . $custom_field . "|" . $pg_success_url . "|" . $pg_fail_url . "|" . $AggregatorId . "|" . $MerchantOrderNo . "|" . $MerchantCustomerID . "|NB|ONLINE|ONLINE";
        $aes                 = new CryptAES();
        $aes->set_key(base64_decode($key));
        $aes->require_pkcs5();
        $EncryptTrans         = $aes->encrypt($EncryptTrans);
        $data["EncryptTrans"] = $EncryptTrans;
        $this->load->view('pg_sbi_form', $data);
      }
      elseif ($pg_name == 'billdesk')
      {
        $update_payment_data = array('gateway' => 'billdesk');
        $this->master_model->updateRecord('payment_transaction', $update_payment_data, array('id' => $pt_id));

        $billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $new_invoice_id, $new_invoice_id, '', 'Cpd/handle_billdesk_response', '', '', '', $custom_field_billdesk);

        if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE')
        {
          $data['bdorderid'] = $billdesk_res['bdorderid'];
          $data['token']     = $billdesk_res['token'];
          $data['responseXHRUrl'] = $billdesk_res['responseXHRUrl'];
          $data['returnUrl'] = $billdesk_res['returnUrl'];
          $this->load->view('pg_billdesk/pg_billdesk_form', $data);
        }
        else
        {
          $this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
          redirect(base_url() . 'Cpd');
        }
      }
    }
    else
    {
      //$data["regno"] = $_REQUEST['regno'];
      $data['show_billdesk_option_flag'] = 1;
      $this->load->view('pg_sbi/make_payment_page', $data);
    }
  }

  public function handle_billdesk_response()
  {
    /* ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
			error_reporting(E_ALL); */
    $selected_invoice_id = $attachpath = $invoiceNumber = '';
    //$selected_invoice_id = $this->session->userdata['memberdata']['regno']; // Seleted Invoice Id


    if (isset($_REQUEST['transaction_response']))
    {
      $response_encode = $_REQUEST['transaction_response'];
      $bd_response     = $this->billdesk_pg_model->verify_res($response_encode);

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

      $qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);
      
      $get_user_regnum_info   = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,member_regnumber,status,id', '', '', '1');
      if (empty($get_user_regnum_info)) { redirect(base_url() . 'Cpd'); }

      if ($auth_status == "0300" && $qry_api_response['auth_status'] == '0300' && $get_user_regnum_info[0]['status'] == 2)
      {
        if ($get_user_regnum_info[0]['status'] == '2') //IF payment status is PENDING
        {
          $new_invoice_id   = $get_user_regnum_info[0]['ref_id'];
          $member_regnumber = $get_user_regnum_info[0]['member_regnumber'];
          //Query to get Payment details
          $payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $member_regnumber), 'transaction_no,date,amount,id');

          $update_data  = array(
            'transaction_no'      => $transaction_no,
            'status'              => 1,
            'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
            'gateway'             => 'billdesk',
            'auth_code'           => '0300',
            'bankcode'            => $bankid,
            'paymode'             => $txn_process_type,
            'callback'            => 'B2B',
          );
          $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
          /* Transaction Log */
          $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
          $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);
          /* Update Exam Invoice */


          /*  $exam_invoice_data = array('modified_on' => date('Y-m-d H:i:s'), 'transaction_no' => $transaction_no); 
						//'pay_txn_id' => $payment_info[0]['id'], 'receipt_no' => $MerchantOrderNo, 
						$this->db->where('pay_txn_id',$payment_info[0]['id']);
					$this->master_model->updateRecord('exam_invoice',$exam_invoice_data,array('receipt_no'=>$MerchantOrderNo)); */

          $user_info = $this->master_model->getRecords('member_registration', array('regnumber' => $get_user_regnum_info[0]['member_regnumber'], 'isactive' => '1'), 'regnumber,usrpassword,email');


          /* Update Pay Status */
          $created_on = date('Y-m-d H:i:s');
          $validate_upto  = date('Y-m-d H:i:s', strtotime('+2 years', strtotime($created_on)));
          $update_data22 = array('pay_status' => '1', 'validate_upto' => $validate_upto);

          $this->master_model->updateRecord('cpd_registration', $update_data22, array('id' => $new_invoice_id));


          /* Email */
          $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'cpd'));
          if (count($emailerstr) > 0 && (count($get_user_regnum_info) > 0))
          {
            //Query to get user details
            /*$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'namesub,firstname,middlename,lastname,email,usrpassword,mobile');
							$username=$user_info[0]['namesub'].' '.$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
							$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
							$newstring1 = str_replace("#DATE#", "".date('Y-m-d h:s:i')."",  $emailerstr[0]['emailer_text']);
							$newstring2 = str_replace("#MEM_NO#", "".$get_user_regnum[0]['member_regnumber']."", $newstring1 );
						$final_str= str_replace("#USERNAME#", "".$userfinalstrname."",  $newstring2);*/

            $final_str = $emailerstr[0]['emailer_text'];
            $info_arr = array('to' => $user_info[0]['email'], 'from' => $emailerstr[0]['from'], 'subject' => $emailerstr[0]['subject'], 'message' => $final_str);

            //genertate invoice and email send with invoice attach 8-7-2017					
            //get invoice	
            $getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo, 'pay_txn_id' => $get_user_regnum_info[0]['id']));


            if (count($getinvoice_number) > 0)
            {
              /*if($getinvoice_number[0]['state_of_center']=='JAM')
								{
								$invoiceNumber = generate_cpd_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
								if($invoiceNumber)
								{
								$invoiceNumber=$this->config->item('CPD_invoice_no_prefix_jammu').$invoiceNumber;
								}
								}
								else
							{}*/
              $invoiceNumber = generate_cpd_invoice_number($getinvoice_number[0]['invoice_id']);
              if ($invoiceNumber)
              {
                $invoiceNumber = $this->config->item('CPD_invoice_no_prefix') . $invoiceNumber;
              }

              $update_data33 = array('invoice_no' => $invoiceNumber, 'transaction_no' => $transaction_no, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'));
              $this->db->where('pay_txn_id', $get_user_regnum_info[0]['id']);
              $this->master_model->updateRecord('exam_invoice', $update_data33, array('receipt_no' => $MerchantOrderNo));

              $invoice_update_query = $this->db->last_query();
              $log_message = serialize($invoice_update_query);
              $titlt = "CPD invoice update" . $this->session->userdata['memberdata']['member_no'];
              $logs = array(
                'title' => $titlt,
                'description' => $log_message
              );
              $this->master_model->insertRecord('cpd_logs', $logs, true);

              $attachpath = genarate_cpd_invoice($getinvoice_number[0]['invoice_id']);
            }

            if ($attachpath != '')
            {
              if ($this->Emailsending->mailsend_attch_cpd($info_arr, $attachpath))
              {
                $this->session->set_flashdata('success', 'CPD registration has been done successfully !!');
                //$pay_status=array();
                //$regnumber = $this->session->userdata('member_no');

                //redirect(base_url('Cpd/acknowledge/'));
                //print_r($MerchantOrderNo);
                //print_r(base_url().'Cpd/acknowledge/'.base64_encode($MerchantOrderNo));
                //echo base_url().'Cpd/acknowledge/'.base64_encode($MerchantOrderNo);exit;
                redirect(base_url() . 'Cpd/acknowledge/' . base64_encode($MerchantOrderNo));
              }
              else
              {
                redirect(base_url('Cpd/acknowledge/'));
              }
            }
            else
            {
              redirect(base_url('Cpd/acknowledge/'));
            }
          }
        }
      }
      elseif ($auth_status == "0002")
      {
        $update_data44 = array('transaction_no' => $transaction_no, 'status' => 2, 'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc, 'auth_code' => '0002', 'bankcode' => $bankid, 'paymode' => $txn_process_type, 'callback' => 'B2B');
        $this->master_model->updateRecord('payment_transaction', $update_data44, array('receipt_no' => $MerchantOrderNo));

        //Manage Log
        $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
        $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);

        $this->session->set_flashdata('flsh_msg', 'Transaction in process...!');
        redirect(base_url() . 'Cpd');
      }
      else //if ($transaction_error_type == 'payment_authorization_error') 
      {
        $update_data44 = array('transaction_no' => $transaction_no, 'status' => 0, 'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc, 'auth_code' => '0300', 'bankcode' => $bankid, 'paymode' => $txn_process_type, 'callback' => 'B2B');
        $this->master_model->updateRecord('payment_transaction', $update_data44, array('receipt_no' => $MerchantOrderNo));

        //Manage Log
        $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
        $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);
      }

      $this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
      redirect(base_url() . 'Cpd');
    }
    else
    {
      die("Please try again...");
    }
  }

  //if sbi transaction success
  public function sbitranssuccess()
  {
    //delete_cookie('regid');
    if (isset($_REQUEST['encData']))
    {
      include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
      $key = $this->config->item('sbi_m_key');
      $aes = new CryptAES();
      $aes->set_key(base64_decode($key));
      $aes->require_pkcs5();
      $encData = $aes->decrypt($_REQUEST['encData']);
      $responsedata = explode("|", $encData);
      $MerchantOrderNo = $responsedata[0];
      $transaction_no  = $responsedata[1];
      $attachpath = $invoiceNumber = '';
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
      //Sbi B2B callback
      //check sbi payment status with MerchantOrderNo 
      $q_details = sbiqueryapi($MerchantOrderNo);
      if ($q_details)
      {
        if ($q_details[2] == "SUCCESS")
        {

          $get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id,status,id');
          if ($get_user_regnum[0]['status'] == 2)
          {

            if (count($get_user_regnum) > 0)
            {

              $user_info = $this->master_model->getRecords('member_registration', array('regnumber' => $get_user_regnum[0]['member_regnumber'], 'isactive' => '1'), 'regnumber,usrpassword,email');
            }

            $created_on = date('Y-m-d H:i:s');
            $validate_upto  = date('Y-m-d H:i:s', strtotime('+2 years', strtotime($created_on)));
            $update_data = array('pay_status' => '1', 'validate_upto' => $validate_upto);

            $this->master_model->updateRecord('cpd_registration', $update_data, array('id' => $get_user_regnum[0]['ref_id']));

            $cpd_status_update_query = $this->db->last_query();
            $log_message = serialize($cpd_status_update_query);
            $titlt = "CPD Reg status update" . $this->session->userdata['memberdata']['member_no'];
            $logs = array(
              'title' => $titlt,
              'description' => $log_message
            );
            $this->master_model->insertRecord('cpd_logs', $logs, true);

            $update_data = array('transaction_no' => $transaction_no, 'status' => 1, 'transaction_details' => $responsedata[2] . " - " . $responsedata[7], 'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5], 'callback' => 'B2B');

            $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));

            $payment_update_query = $this->db->last_query();
            $log_message = serialize($payment_update_query);
            $titlt = "CPD payment status update" . $this->session->userdata['memberdata']['member_no'];
            $logs = array(
              'title' => $titlt,
              'description' => $log_message
            );
            $this->master_model->insertRecord('cpd_logs', $logs, true);


            //Manage Log
            $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code . "&CALLBACK=B2B";
            $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
            //$this->db->last_query();exit;
            //$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;


            $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'cpd'));
            if (count($emailerstr) > 0 && (count($get_user_regnum) > 0))
            {
              //Query to get user details
              /*$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'namesub,firstname,middlename,lastname,email,usrpassword,mobile');
									$username=$user_info[0]['namesub'].' '.$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
									$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
									$newstring1 = str_replace("#DATE#", "".date('Y-m-d h:s:i')."",  $emailerstr[0]['emailer_text']);
									$newstring2 = str_replace("#MEM_NO#", "".$get_user_regnum[0]['member_regnumber']."", $newstring1 );
								$final_str= str_replace("#USERNAME#", "".$userfinalstrname."",  $newstring2);*/

              $final_str = $emailerstr[0]['emailer_text'];
              $info_arr = array('to' => $user_info[0]['email'], 'from' => $emailerstr[0]['from'], 'subject' => $emailerstr[0]['subject'], 'message' => $final_str);

              //genertate invoice and email send with invoice attach 8-7-2017					
              //get invoice	
              $getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo, 'pay_txn_id' => $get_user_regnum[0]['id']));


              if (count($getinvoice_number) > 0)
              {
                /*if($getinvoice_number[0]['state_of_center']=='JAM')
										{
										$invoiceNumber = generate_cpd_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
										if($invoiceNumber)
										{
										$invoiceNumber=$this->config->item('CPD_invoice_no_prefix_jammu').$invoiceNumber;
										}
										}
										else
									{}*/
                $invoiceNumber = generate_cpd_invoice_number($getinvoice_number[0]['invoice_id']);
                if ($invoiceNumber)
                {
                  $invoiceNumber = $this->config->item('CPD_invoice_no_prefix') . $invoiceNumber;
                }

                $update_data = array('invoice_no' => $invoiceNumber, 'transaction_no' => $transaction_no, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'));
                $this->db->where('pay_txn_id', $get_user_regnum[0]['id']);
                $this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));

                $invoice_update_query = $this->db->last_query();
                $log_message = serialize($invoice_update_query);
                $titlt = "CPD invoice update" . $this->session->userdata['memberdata']['member_no'];
                $logs = array(
                  'title' => $titlt,
                  'description' => $log_message
                );
                $this->master_model->insertRecord('cpd_logs', $logs, true);

                $attachpath = genarate_cpd_invoice($getinvoice_number[0]['invoice_id']);
              }

              if ($attachpath != '')
              {
                //if($this->Emailsending->mailsend($info_arr))
                //if($this->Emailsending->mailsend_attch($info_arr,$attachpath))
                if ($this->Emailsending->mailsend_attch_cpd($info_arr, $attachpath))
                {
                  $this->session->set_flashdata('success', 'CPD registration has been done successfully !!');
                  //$pay_status=array();
                  //$regnumber = $this->session->userdata('member_no');

                  //redirect(base_url('DupCert/acknowledge/'));
                  //print_r($MerchantOrderNo);
                  //print_r(base_url().'DupCert/acknowledge/'.base64_encode($MerchantOrderNo));
                  //echo base_url().'DupCert/acknowledge/'.base64_encode($MerchantOrderNo);exit;
                  redirect(base_url() . 'Cpd/acknowledge/' . base64_encode($MerchantOrderNo));
                }
                else
                {
                  redirect(base_url('Cpd/acknowledge/'));
                }
              }
              else
              {
                redirect(base_url('Cpd/acknowledge/'));
              }
            }
          }
        }
      }
      ///End of SBI B2B callback 
      redirect(base_url() . 'Cpd/acknowledge/');
    }
    else
    {
      die("Please try again...");
    }
  }

  //if sbi payment fail
  public function sbitransfail()
  {
    delete_cookie('regid');
    if (isset($_REQUEST['encData']))
    {
      include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
      $key = $this->config->item('sbi_m_key');
      $aes = new CryptAES();
      $aes->set_key(base64_decode($key));
      $aes->require_pkcs5();
      $encData = $aes->decrypt($_REQUEST['encData']);
      $responsedata = explode("|", $encData);
      $MerchantOrderNo = $responsedata[0];
      $transaction_no  = $responsedata[1];
      $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,status');

      if ($get_user_regnum_info[0]['status'] != 0 && $get_user_regnum_info[0]['status'] == 2)
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


        $update_data = array('transaction_no' => $transaction_no, 'status' => 0, 'transaction_details' => $responsedata[2] . " - " . $responsedata[7], 'auth_code' => '0399', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5], 'callback' => 'B2B');
        $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
        //Manage Log
        $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
        $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
      }

      $this->session->set_flashdata('error', 'Transaction has been fail, please try again!!');
      redirect(base_url('Cpd'));

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
    }
    else
    {
      die("Please try again...");
    }
  }

  //Thank you message to end user
  public function acknowledge($MerchantOrderNo = NULL)
  {
    if (!empty($MerchantOrderNo))
    {
      $payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => base64_decode($MerchantOrderNo)), 'member_regnumber,transaction_no,date,amount,status');
    }
    if (count(@$payment_info) <= 0)
    {
      redirect(base_url());
    }

    $data = array();
    if ($this->session->userdata('memberdata') == '')
    {
      redirect(base_url());
    }
    if ($this->session->userdata('cpduserinfo'))
    {
      $this->session->unset_userdata('cpduserinfo');
    }


    $user_info = $this->master_model->getRecords('cpd_registration', array('id' => $this->session->userdata['memberdata']['regno']));

    $data = array('middle_content' => 'cpd/cpd_thankyou', 'application_number' => $payment_info[0]['member_regnumber'], 'user_info' => $user_info, 'payment_info' => $payment_info);
    $this->load->view('common_view_fullwidth', $data);
    /*$data=array('middle_content'=>'dup_cert/duplicate_cert_thankyou','application_number','application_number'=>$user_info[0]['member_no']);
			$this->load->view('common_view',$data);*/
  }

  //custom invoice generation and mail seding
  public function send_custom_invoice_mail()
  {
    $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'cpd'));
    if (count($emailerstr) > 0)
    {
      $final_str = $emailerstr[0]['emailer_text'];
      $info_arr = array('to' => 'shashikumar_ssm@yahoo.co.in', 'from' => $emailerstr[0]['from'], 'subject' => $emailerstr[0]['subject'], 'message' => $final_str);
    }
    /*$invoiceNumber = generate_cpd_invoice_number('116013');
				if($invoiceNumber) 
				{
				$invoiceNumber=$this->config->item('CPD_invoice_no_prefix').$invoiceNumber;
				}
				print_r($invoiceNumber); echo '</br>';
				$update_data = array('invoice_no' =>$invoiceNumber,'transaction_no'=>'6664306655924','modified_on'=>date('Y-m-d H:i:s'));
				//'date_of_invoice'=>date('Y-m-d H:i:s'),
				$this->db->where('pay_txn_id','2038077');
				//$this->db->where('pay_txn_id',$get_user_regnum[0]['id']);
				$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>'811964325'));
			echo $this->db->last_query();*/
    $attachpath = custom_genarate_cpd_invoice('2185705');
    if ($attachpath != '')
    {
      if ($this->Emailsending->mailsend_attch($info_arr, $attachpath))
      {
        echo "mail send successfully";
      }
      else
      {
        echo "Error while mail sending";
      }
    }

    /*$query = $this->db->query("SELECT exam_invoice.invoice_id  FROM `exam_invoice` WHERE `transaction_no` != '' AND `app_type` LIKE 'P'");
				$result = $query->result_array();
				if(!empty($result))
				{
				foreach($result as $key)
				{
				print_r($key['invoice_id']);echo '</br>';
				$invoice_id = $key['invoice_id'];
				}
				}
			exit;*/
  }
}
