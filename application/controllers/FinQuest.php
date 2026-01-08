<?php
defined('BASEPATH') or exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
class FinQuest extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->library('upload');
    $this->load->helper('upload_helper');
    $this->load->helper('master_helper');
    $this->load->helper('general_helper');
    $this->load->helper('upload_helper');
    $this->load->helper('renewal_invoice_helper');
    $this->load->model('Master_model');
    $this->load->library('email');
    $this->load->helper('date');
    $this->load->library('email');
    $this->load->model('Emailsending');
    $this->load->model('log_model');
    $this->load->model('Captcha_model');
    $this->load->model('billdesk_pg_model');
    //accedd denied due to GST
    //$this->master_model->warning();

  }
  public function getfee()
  {

    $statecode = mysqli_real_escape_string($this->db->conn_id, $_POST['statecode']);
    $fees      = 0;

    $fees = $this->config->item('FinQuest_cs_total');

    $output['fees'] = $fees;

    $user_data = array(
      'statecode' => $statecode,
      'fees'      => $fees,

    );

    $this->session->set_userdata($user_data);
    echo $putput = json_encode($output);
  }

  public function index()
  {

    $data['validation_errors'] = '';
    $var_errors                = '';
    if (isset($_POST['btnSubmit']))
    {
      $state = '';
      $this->form_validation->set_rules('sel_namesub', 'Name Prefix', 'trim|required|xss_clean');
      $this->form_validation->set_rules('fname', 'Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
      $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean|callback_check_emailduplication');
      $this->form_validation->set_rules('gender', 'Gender', 'trim|required|xss_clean');
      $this->form_validation->set_rules('addressline1', 'Addressline1', 'trim|max_length[30]|required|xss_clean|callback_address1');
      $this->form_validation->set_rules('district', 'District', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
      $this->form_validation->set_rules('city', 'City', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
      $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean');
      if ($this->input->post('state') != '')
      {
        $state = mysqli_real_escape_string($this->db->conn_id, $this->input->post('state'));
      }

      $this->form_validation->set_rules('pincode', 'Pincode/Zipcode', 'trim|required|numeric|xss_clean|callback_check_checkpin[' . $state . ']');
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

      if (isset($_POST['stdcode']) && $_POST['stdcode'] != '')
      {
        $this->form_validation->set_rules('stdcode', 'STD Code', 'trim|max_length[4]|required|numeric|xss_clean');
      }

      $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|xss_clean|callback_check_mobileduplication');
      $this->form_validation->set_rules('code', 'Security Code ', 'trim|required|xss_clean|callback_check_captcha_userreg');
      $this->form_validation->set_rules('declaration1', 'I Accept', 'trim|required|xss_clean');

      if ($_POST["mem_no"] != '')
      {
        $mem_no = mysqli_real_escape_string($this->db->conn_id, $_POST["mem_no"]);

        /*       $this->db->where('subscription_to_date <',date('Y-m-d'));
                $this->db->where('pay_status','1');
                $member_exist=$this->master_model->getRecordCount('fin_quest',array('mem_no'=>$mem_no));
                if(empty($member_exist))
                {
                $this->session->set_flashdata('error','You have all ready apply for finquest');
                redirect(base_url().'FinQuest');
                }*/

        $member_no_exist = $this->master_model->getRecordCount('member_registration', array(
          'regnumber' => $mem_no,
        ));
        if (empty($member_no_exist))
        {
          $this->session->set_flashdata('error', 'Invalid Membership Number');
          redirect(base_url() . 'FinQuest');
        }
        else
        {
          $this->db->where('mobile', mysqli_real_escape_string($this->db->conn_id, $_POST["mobile"]));
          $this->db->where('email', mysqli_real_escape_string($this->db->conn_id, $_POST["email"]));
          $member_no_exist = $this->master_model->getRecordCount('member_registration', array(
            'regnumber' => $mem_no,
          ));
          if (empty($member_no_exist))
          {
            $this->session->set_flashdata('error', 'You have enter the invalid information for the Membership Number');
            redirect(base_url() . 'FinQuest');
          }
        }
        $mem_no_Finq = $gest_user = array();

        $this->db->where('subscription_to_date >', date('Y-m-d'));
        $this->db->where('email_id', mysqli_real_escape_string($this->db->conn_id, $_POST["email"]));
        $this->db->where('contact_no', mysqli_real_escape_string($this->db->conn_id, $_POST["mobile"]));
        $this->db->where('pay_status', '1');
        $mem_no_Finq = $this->master_model->getRecordCount('fin_quest', array(
          'mem_no' => $mem_no,
        ));
        if ($mem_no_Finq)
        {
          $this->session->set_flashdata('error', 'You have already applied using this  ' . $mem_no . '  Membership Number');
          redirect(base_url() . 'FinQuest');
        }
        else
        {

          $this->db->where('subscription_to_date >', date('Y-m-d'));
          $this->db->where('email_id', mysqli_real_escape_string($this->db->conn_id, $_POST["email"]));
          $this->db->where('contact_no', mysqli_real_escape_string($this->db->conn_id, $_POST["mobile"]));
          $mem_no_Finq = $this->master_model->getRecordCount('fin_quest', array(
            'pay_status' => '1',
          ));
          if ($mem_no_Finq)
          {
            $this->session->set_flashdata('error', 'You have already applied using this  Membership Number');
            redirect(base_url() . 'FinQuest');
          }
          else
          {

            $this->db->where('subscription_to_date <', date('Y-m-d'));
            $this->db->where('email_id', mysqli_real_escape_string($this->db->conn_id, $_POST["email"]));
            $this->db->where('contact_no', mysqli_real_escape_string($this->db->conn_id, $_POST["mobile"]));
            $mem_no_Finq = $this->master_model->getRecordCount('fin_quest', array(
              'pay_status' => '1',
            ));
            if ($mem_no_Finq)
            {

              $this->session->set_flashdata('error', 'The entered email ID and mobile no already exist  ');
              redirect(base_url() . 'FinQuest');
            }
          }
        }
      }

      $this->db->where('subscription_to_date >', date('Y-m-d'));
      $this->db->where('email_id', mysqli_real_escape_string($this->db->conn_id, $_POST["email"]));
      $this->db->where('contact_no', mysqli_real_escape_string($this->db->conn_id, $_POST["mobile"]));
      $gest_user = $this->master_model->getRecordCount('fin_quest', array(
        'pay_status' => '1',
      ));
      if ($gest_user)
      {
        $this->session->set_flashdata('error', 'The entered email ID and mobile no already exist  ');
        redirect(base_url() . 'FinQuest');
      }
    }
    else
    {
      $mem_no = '';
    }
    if ($this->form_validation->run() == true)
    {
      $user_data = array(
        'mem_no'            => $_POST["mem_no"],
        'sel_namesub'       => $_POST["sel_namesub"],
        'fname'             => $_POST["fname"],
        'mname'             => $_POST["mname"],
        'lname'             => $_POST["lname"],
        'gender'            => $_POST["gender"],
        'addressline1'      => $_POST["addressline1"],
        'addressline2'      => $_POST["addressline2"],
        'addressline3'      => $_POST["addressline3"],
        'addressline4'      => $_POST["addressline4"],
        'district'          => substr($_POST["district"], 0, 30),
        'city'              => $_POST["city"],
        'code'              => trim($_POST["code"]),
        'email'             => $_POST["email"],
        'mobile'            => $_POST["mobile"],
        'pincode'           => mysqli_real_escape_string($this->db->conn_id, $_POST["pincode"]),
        'state'             => $_POST["state"],
        'subscription_fees' => $_POST["subscription_fees"],
        'subscription_date' => $_POST["subscription_date"],

      );

      $this->session->set_userdata('enduserinfo', $user_data);
      $this->form_validation->set_message('error', "");
      redirect(base_url() . 'FinQuest/preview');
    }

    $this->db->where('state_master.state_delete', '0');
    $states = $this->master_model->getRecords('state_master');

    //echo $this->db->last_query();
    //exit;
    /*  $this->load->helper('captcha');
        //$this->session->unset_userdata("regcaptcha");
        $this->session->set_userdata("regcaptcha", rand(1, 100000));
        $vals                   = array(
        'img_path' => './uploads/applications/',
        'img_url' => base_url() . 'uploads/applications/'
        );
        $cap                    = create_captcha($vals);
        $_SESSION["regcaptcha"] = $cap['word']; */
    $this->load->model('Captcha_model');
    $captcha_image = $this->Captcha_model->generate_captcha_img('regcaptcha');

    $data = array(
      'middle_content' => 'fin_quest/Fin_quest',
      'states'         => $states,
      'image'          => $captcha_image,
      'var_errors'     => $var_errors,
    );
    $this->load->view('common_view_fullwidth', $data);
  }

  public function preview()
  {

    if (!$this->session->userdata('enduserinfo'))
    {
      redirect(base_url());
    }

    $data = array(
      'middle_content' => 'fin_quest/preview_fin_quest',
    );
    $this->load->view('common_view_fullwidth', $data);
  }

  public function addrecord()
  {
    if (!$this->session->userdata['enduserinfo'])
    {
      redirect(base_url());
    }
    $statecode = $this->session->userdata['enduserinfo']['state'];
    $fees      = 0;

    $fees = $this->config->item('FinQuest_cs_total');

    $to_date = date('Y-m-d', strtotime('+1 years'));
    //get member type
    $memtype          = array();
    $memberno         = '';
    $registrationtype = '';
    $this->db->where('regnumber', $this->session->userdata['enduserinfo']['mem_no']);
    $this->db->where('mobile', $this->session->userdata['enduserinfo']['mobile']);
    $this->db->where('email', $this->session->userdata['enduserinfo']['email']);
    $memtype = $this->master_model->getRecords('member_registration', '', 'regnumber,registrationtype');
    if (!empty($memtype))
    {
      $memberno         = $memtype[0]['regnumber'];
      $registrationtype = $memtype[0]['registrationtype'];
    }
    $insert_info = array(
      'mem_no '                => $memberno,
      'registrationtype '      => $registrationtype,
      'namesub'                => $this->session->userdata['enduserinfo']['sel_namesub'],
      'fname'                  => $this->session->userdata['enduserinfo']['fname'],
      'mname'                  => $this->session->userdata['enduserinfo']['mname'],
      'lname'                  => $this->session->userdata['enduserinfo']['lname'],
      'gender'                 => $this->session->userdata['enduserinfo']['gender'],
      'email_id'               => $this->session->userdata['enduserinfo']['email'],
      'contact_no'             => $this->session->userdata['enduserinfo']['mobile'],
      'address_1'              => $this->session->userdata['enduserinfo']['addressline1'],
      'address_2'              => $this->session->userdata['enduserinfo']['addressline2'],
      'address_3'              => $this->session->userdata['enduserinfo']['addressline3'],
      'address_4'              => $this->session->userdata['enduserinfo']['addressline4'],
      'district'               => $this->session->userdata['enduserinfo']['district'],
      'city'                   => $this->session->userdata['enduserinfo']['city'],
      'state'                  => $this->session->userdata['enduserinfo']['state'],
      'pincode'                => $this->session->userdata['enduserinfo']['pincode'],
      'subscription_fees'      => $fees,
      'subscription_from_date' => date("Y-m-d H:i:s"),
      'subscription_to_date'   => $to_date,
      'created_on'             => date("Y-m-d H:i:s"),
      'pay_status'             => 0,
    );

    if ($last_id = $this->master_model->insertRecord('fin_quest', $insert_info, true))
    {
      $upd_files = array();
      $pt_array  = array(
        'id' => $last_id,
      );
      $this->session->set_userdata('FinQuest_memberdata', $pt_array);
      redirect(base_url() . "FinQuest/make_payment");
    }
    else
    {
      $this->session->set_flashdata('error', 'Error while during subscription.please try again!');
      redirect(base_url() . 'FinQuest');
    }
  }

  public function make_payment()
  {
    $regno = $this->session->userdata['FinQuest_memberdata']['id'];
    $state = $this->session->userdata['enduserinfo']['state'];
    if (isset($_POST['processPayment']) && $_POST['processPayment'])
    {
      $pg_name = $this->input->post('pg_name');
      /* include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
            $key            = $this->config->item('sbi_m_key');
            $merchIdVal     = $this->config->item('sbi_merchIdVal');
            $AggregatorId   = $this->config->item('sbi_AggregatorId');
            $pg_success_url = base_url() . "FinQuest/sbitranssuccess";
            $pg_fail_url    = base_url() . "FinQuest/sbitransfail"; */

      if (!empty($state))
      {
        if ($state == 'MAH')
        {
          $amount = $this->config->item('FinQuest_cs_total');
        }
        else
        {
          $amount = $this->config->item('FinQuest_igst_tot');
        }
      }

      $insert_data = array(
        'member_regnumber' => $this->session->userdata['enduserinfo']['mem_no'],
        'gateway'          => "sbiepay",
        'amount'           => $amount,
        'date'             => date('Y-m-d H:i:s'),
        'ref_id'           => $regno,
        'description'      => "FinQuest Subscription",
        'pay_type'         => 8,
        'status'           => 2,
        'pg_flag'          => 'iibffq',
      );

      $pt_id                 = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
      $MerchantOrderNo       = reg_sbi_order_id($pt_id);
      $custom_field          = $regno . "^iibfregn^iibffq^" . $MerchantOrderNo;
      $custom_field_billdesk = $regno . "-iibfregn-iibffq-" . $MerchantOrderNo;

      $update_data = array(
        'receipt_no'       => $MerchantOrderNo,
        'pg_other_details' => $custom_field,
      );
      $this->master_model->updateRecord('payment_transaction', $update_data, array(
        'id' => $pt_id,
      ));

      $getstate = $this->master_model->getRecords('state_master', array(
        'state_code'   => $this->session->userdata['enduserinfo']['state'],
        'state_delete' => '0',
      ));

      if ($this->session->userdata['enduserinfo']['state'] == 'MAH')
      {

        $cgst_rate = $this->config->item('FinQuest_cgst_rate');
        $sgst_rate = $this->config->item('FinQuest_sgst_rate');
        //set an amount as per rate
        $cgst_amt = $this->config->item('FinQuest_cgst_amt');
        $sgst_amt = $this->config->item('FinQuest_sgst_amt');
        //set an total amount
        $cs_total  = $amount;
        $tax_type  = 'Intra';
        $igst_rate = '';
        $igst_amt  = $igst_total  = '';
      }
      else
      {
        //set a rate (e.g 9%,9% or 18%)
        $igst_rate = $this->config->item('FinQuest_igst_rate');
        $igst_amt  = $this->config->item('FinQuest_igst_amt');
        //set an total amount
        $igst_total = $this->config->item('FinQuest_igst_tot');
        $tax_type   = 'Inter';
        $cgst_rate  = $sgst_rate  = $cs_total  = '';
        $cgst_amt   = $sgst_amt   = '';
      }
      //get recode
      $mem_info = $this->master_model->getRecords('fin_quest', array(
        'id' => $regno,
      ));

      if (!empty($mem_info))
      {
        $regnumber = $mem_info[0]['mem_no'];
      }
      else
      {
        $regnumber = '';
      }
      $invoice_insert_array = array(
        'pay_txn_id'      => $pt_id,
        'receipt_no'      => $MerchantOrderNo,
        'member_no'       => $regnumber,
        'state_of_center' => $state,
        'app_type'        => 'F',
        'service_code'    => 999799,
        'qty'             => '1',
        'state_code'      => $getstate[0]['state_no'],
        'state_name'      => $getstate[0]['state_name'],
        'tax_type'        => $tax_type,
        'fee_amt'         => $this->config->item('FinQuest_apply_fee'),
        'cgst_rate'       => $cgst_rate,
        'cgst_amt'        => $cgst_amt,
        'sgst_rate'       => $sgst_rate,
        'sgst_amt'        => $sgst_amt,
        'igst_rate'       => $igst_rate,
        'igst_amt'        => $igst_amt,
        'cs_total'        => $cs_total,
        'igst_total'      => $igst_total,
        'gstin_no'        => '',
        'exempt'          => $getstate[0]['exempt'],
        'created_on'      => date('Y-m-d H:i:s'),
      );

      $inser_id = $this->master_model->insertRecord('exam_invoice', $invoice_insert_array);

      if ($pg_name == 'sbi')
      {
        include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
        $key            = $this->config->item('sbi_m_key');
        $merchIdVal     = $this->config->item('sbi_merchIdVal');
        $AggregatorId   = $this->config->item('sbi_AggregatorId');
        $pg_success_url = base_url() . "FinQuest/sbitranssuccess";
        $pg_fail_url    = base_url() . "FinQuest/sbitransfail";
        //exit;
        $MerchantCustomerID  = $new_invoice_id;
        $data["pg_form_url"] = $this->config->item('sbi_pg_form_url');
        $data["merchIdVal"]  = $merchIdVal;

        $EncryptTrans = $merchIdVal . "|DOM|IN|INR|" . $amount . "|" . $custom_field . "|" . $pg_success_url . "|" . $pg_fail_url . "|" . $AggregatorId . "|" . $MerchantOrderNo . "|" . $MerchantCustomerID . "|NB|ONLINE|ONLINE";

        $aes = new CryptAES();
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

        $billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $new_invoice_id, $new_invoice_id, '', 'FinQuest/handle_billdesk_response', '', '', '', $custom_field_billdesk);

        if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE')
        {
          $data['bdorderid']      = $billdesk_res['bdorderid'];
          $data['token']          = $billdesk_res['token'];
          $data['responseXHRUrl'] = $billdesk_res['responseXHRUrl'];
          $data['returnUrl']      = $billdesk_res['returnUrl'];
          $this->load->view('pg_billdesk/pg_billdesk_form', $data);
        }
        else
        {
          $this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
          redirect(base_url() . 'FinQuest');
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
    /*  ini_set('display_errors', 1);
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

      $get_user_regnum_info   = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,member_regnumber,status,id', '', '', '1');
      if (empty($get_user_regnum_info))
      {
        redirect(base_url() . 'FinQuest');
      }
      $fin_quest_id   = $get_user_regnum_info[0]['ref_id'];
      $member_regnumber = $get_user_regnum_info[0]['member_regnumber'];
      //Query to get Payment details
      $payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $member_regnumber), 'transaction_no,date,amount,id,ref_id');

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
      $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
      /* Transaction Log */
      $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
      $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);

      /* Update Exam Invoice */
      $qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);
      if ($auth_status == "0300" && $qry_api_response['auth_status'] == '0300' && $get_user_regnum_info[0]['status'] == 2)
      {
        //GET INVOICE DATA
        $get_invoice_data = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo, 'app_type' => 'F', 'pay_txn_id' => $get_user_regnum_info[0]['id']), 'invoice_id');
        if (count($get_invoice_data) > 0)
        {
          $new_invoice_id   = $get_invoice_data[0]['invoice_id'];

          $exam_invoice_data = array('modified_on' => date('Y-m-d H:i:s'), 'transaction_no' => $transaction_no);
          $this->master_model->updateRecord('exam_invoice', $exam_invoice_data, array('invoice_id' => $new_invoice_id, 'member_no' => $member_regnumber));

          // genarate subscription number
          $subscription_number = genarate_finquest_subscription_number($fin_quest_id, 'F');

          $update_bank_data = array(
            'pay_status'             => 1,
            'modified_on'            => date("Y-m-d H:i:s"),
            'subscription_from_date' => date('Y-m-d'),
            'subscription_to_date'   => date('Y-m-d', strtotime('+1 years')),
            'subscription_no'        => $subscription_number,
          );

          $this->master_model->updateRecord('fin_quest', $update_bank_data, array(
            'id' => $fin_quest_id
          ));

          /*log activity for genarate subscription number*/
          $log_title   = "Subscription number genarate successfully :" . $subscription_number . " for id  :" . $new_invoice_id . "";
          $log_message = serialize($update_bank_data);
          storedUserActivity($log_title, $log_message, '', '');
          /* Close User Log Actitives */

          /* Email */
          // email to user

          $start_date         = date('d/m/Y');
          $end_date           = date('d/m/Y', strtotime('+1 years'));
          $subscription_range = $start_date . " to " . $end_date;
          $emailerstr         = $this->master_model->getRecords('emailer', array(
            'emailer_name' => 'finquest',
          ));
          $newstring1 = str_replace("#NO#", "" . $subscription_number . "", $emailerstr[0]['emailer_text']);
          $final_str  = str_replace("#DATE#", "" . $subscription_range . "", $newstring1);
          $info_arr   = array(
            'to'      => $this->session->userdata['enduserinfo']['email'],
            'from'    => $emailerstr[0]['from'],
            'subject' => $emailerstr[0]['subject'],
            'message' => $final_str,
          );

          //to client

          // genarate invoice
          $getinvoice_number = $this->master_model->getRecords('exam_invoice', array(
            'receipt_no' => $MerchantOrderNo,
            'pay_txn_id' => $get_user_regnum_info[0]['id'],
          ));
          if (count($getinvoice_number) > 0)
          {
            $invoiceNumber = generate_finquest_invoice_number($getinvoice_number[0]['invoice_id']);
            if ($invoiceNumber)
            {
              $invoiceNumber = $this->config->item('FinQuest_invoice_no_prefix') . $invoiceNumber;
            }

            $update_data22 = array(
              'invoice_no'      => $invoiceNumber,
              'date_of_invoice' => date('Y-m-d H:i:s'),
              'modified_on'     => date('Y-m-d H:i:s'),
            );
            $this->db->where('pay_txn_id', $get_user_regnum_info[0]['id']);
            $this->master_model->updateRecord('exam_invoice', $update_data22, array(
              'receipt_no' => $MerchantOrderNo,
            ));

            $attachpath = genarate_finquest_invoice($getinvoice_number[0]['invoice_id'], $fin_quest_id);
          }

          // echo '<pre>'; print_r($info_arr); echo '</pre>'; print_r($attachpath);
          //Manage Log
          /*   $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[6]); */

          if ($attachpath != '')
          {
            if ($this->Emailsending->mailsend_attch($info_arr, $attachpath))
            {
              #------------send mail to chetan-----------#
              $message = "Hello Team,<br><br>

                    Please add following member to fin@quest mailing list.<br>

                    Member details :<br>
                    Email id:
                    " . $this->session->userdata['enduserinfo']['email'] . '<br><br>

                    -- <br>
             Thanks & Regards,<br>
                Chaitali jadhav | jr. Software Engineer
                ESDS Software Solution Ltd.
                Website : www.esds.co.in | Email: chaitali.jadhav@esds.co.in
                Address  : Plot No. B- 24 & 25, NICE Industrial Area, Satpur MIDC, Nashik 422 007
                Mobile    : +91 7744883663 | Toll Free : 1800 209 3006 | Landline : +91 (0253) 663 6500
                _<br>"

                    ';

              $info_arr_c = array(
                'to'      => 'iibfdevp@esds.co.in',
                'from'    => 'chaitali.jadhav@esds.co.in',
                'subject' => 'Add member to finquest subscription list. - Staging environment',
                'message' => $message,
              );

              $attachpath_c = '';

              if ($this->Emailsending->mailsend_attch_finquest($info_arr_c, $attachpath_c))
              {

                /*log activity for genarate subscription number*/
                $log_title   = "Email send sucessfully to chetan & member added in the finquest mail list :" . $subscription_number . " for id  :" . $this->session->userdata['FinQuest_memberdata']['id'] . "";
                $log_message = serialize($info_arr_c);
                storedUserActivity($log_title, $log_message, '', '');
                //chaitali added - 2022-02-10
                //redirect(base_url() . 'FinQuest/acknowledge/');
              }
              #------------end send mail to chetan-----------#

              /*log activity for genarate subscription number*/
              $log_title   = "Email send sucessfully :" . $subscription_number . " for id  :" . $this->session->userdata['FinQuest_memberdata']['id'] . "";
              $log_message = serialize($info_arr);
              storedUserActivity($log_title, $log_message, '', '');
              /* Close User Log Actitives */

              redirect(base_url() . 'FinQuest/acknowledge/');
            }
            else
            {

              /*log activity for genarate subscription number*/
              $log_title   = "subscription number genrated but Email not send after payment:" . $subscription_number . " for id  :" . $this->session->userdata['FinQuest_memberdata']['id'] . "";
              $log_message = serialize($info_arr);
              storedUserActivity($log_title, $log_message, '', '');
              /* Close User Log Actitives */

              redirect(base_url() . 'FinQuest/acknowledge/');
            }
          }
          else
          {
            redirect(base_url() . 'FinQuest/acknowledge/');
          }
        }
        else
        {
          redirect(base_url() . 'FinQuest/acknowledge/');
        }
      }
      elseif ($auth_status == "0002")
      {

        $update_data33 = array(
          'transaction_no'      => $transaction_no,
          'status'              => 2,
          'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
          'auth_code'           => '0002',
          'bankcode'            => $bankid,
          'paymode'             => $txn_process_type,
          'callback'            => 'B2B',
        );
        $this->master_model->updateRecord('payment_transaction', $update_data33, array(
          'receipt_no' => $MerchantOrderNo,
        ));

        $update_bank_data = array(
          'pay_status'           => 2,
          'modified_on'          => date("Y-m-d H:i:s"),
          'subscription_to_date' => date('Y-m-d', strtotime('+1 years')),
          'subscription_no'      => '',
        );

        $this->master_model->updateRecord('fin_quest', $update_bank_data, array(
          'id' => $fin_quest_id
        ));

        //Manage Log
        $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
        $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);

        $this->session->set_flashdata('flsh_msg', 'Transaction peinding...!');
        redirect(base_url() . 'FinQuest');
      }
      else //if ($transaction_error_type == 'payment_authorization_error')
      {

        $update_data33 = array(
          'transaction_no'      => $transaction_no,
          'status'              => 0,
          'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
          'auth_code'           => '0300',
          'bankcode'            => $bankid,
          'paymode'             => $txn_process_type,
          'callback'            => 'B2B',
        );
        $this->master_model->updateRecord('payment_transaction', $update_data33, array(
          'receipt_no' => $MerchantOrderNo,
        ));

        $update_bank_data = array(
          'pay_status'           => 0,
          'modified_on'          => date("Y-m-d H:i:s"),
          'subscription_to_date' => date('Y-m-d', strtotime('+1 years')),
          'subscription_no'      => '',
        );

        $this->master_model->updateRecord('fin_quest', $update_bank_data, array(
          'id' => $fin_quest_id
        ));

        //Manage Log
        $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
        $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);

        $this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
        redirect(base_url() . 'FinQuest');
      }
    }
    else
    {
      die("Please try again...");
    }
  }

  public function sbitranssuccess()
  {
    if (isset($_REQUEST['encData']))
    {
      include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
      $key = $this->config->item('sbi_m_key');
      $aes = new CryptAES();
      $aes->set_key(base64_decode($key));
      $aes->require_pkcs5();
      $encData      = $aes->decrypt($_REQUEST['encData']);
      $responsedata = explode("|", $encData);

      $MerchantOrderNo = $responsedata[0];
      $transaction_no  = $responsedata[1];
      $attachpath      = $invoiceNumber      = '';
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
      //Sbi B2B callback check sbi payment status with MerchantOrderNo
      $q_details = sbiqueryapi($MerchantOrderNo);

      if ($q_details)
      {
        if ($q_details[2] == "SUCCESS")
        {
          $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array(
            'receipt_no' => $MerchantOrderNo,
          ), 'ref_id,status,id');
          if ($get_user_regnum_info[0]['status'] == 2)
          {
            $reg_id = $get_user_regnum_info[0]['ref_id'];
            $this->db->trans_start();
            $update_data = array(
              'transaction_no'      => $transaction_no,
              'status'              => 1,
              'transaction_details' => $responsedata[2] . " - " . $responsedata[7],
              'auth_code'           => '0300',
              'bankcode'            => $responsedata[8],
              'paymode'             => $responsedata[5],
              'callback'            => 'B2B',
            );

            $this->master_model->updateRecord('payment_transaction', $update_data, array(
              'receipt_no' => $MerchantOrderNo,
            ));
            $this->db->trans_complete();

            // genarate subscription number
            $session_id          = $reg_id;
            $subscription_number = genarate_finquest_subscription_number($session_id, 'F');

            $update_bank_data = array(
              'pay_status'             => 1,
              'modified_on'            => date("Y-m-d H:i:s"),
              'subscription_from_date' => date('Y-m-d'),
              'subscription_to_date'   => date('Y-m-d', strtotime('+1 years')),
              'subscription_no'        => $subscription_number,
            );

            $this->master_model->updateRecord('fin_quest', $update_bank_data, array(
              'id' => $reg_id,
            ));
            /*log activity for genarate subscription number*/
            $log_title   = "Subscription number genarate successfully :" . $subscription_number . " for id  :" . $this->session->userdata['FinQuest_memberdata']['id'] . "";
            $log_message = serialize($update_bank_data);
            storedUserActivity($log_title, $log_message, '', '');
            /* Close User Log Actitives */

            // email to user
            /*$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'recommender_recommend_email'));
                        $final_str = str_replace("#MSG#", "".$msg."",  $emailerstr[0]['emailer_text']);
                        //$final_str= str_replace("#password#", "".$decpass."",  $newstring);
                         */
            $start_date         = date('d/m/Y');
            $end_date           = date('d/m/Y', strtotime('+1 years'));
            $subscription_range = $start_date . " to " . $end_date;
            $emailerstr         = $this->master_model->getRecords('emailer', array(
              'emailer_name' => 'finquest',
            ));
            $newstring1 = str_replace("#NO#", "" . $subscription_number . "", $emailerstr[0]['emailer_text']);
            $final_str  = str_replace("#DATE#", "" . $subscription_range . "", $newstring1);
            $info_arr   = array(
              'to'      => $this->session->userdata['enduserinfo']['email'],
              //'to'=>'kyciibf@gmail.com',
              'from'    => $emailerstr[0]['from'],
              'subject' => $emailerstr[0]['subject'],
              'message' => $final_str,
            );

            //to client

            // genarate invoice
            $getinvoice_number = $this->master_model->getRecords('exam_invoice', array(
              'receipt_no' => $MerchantOrderNo,
              'pay_txn_id' => $get_user_regnum_info[0]['id'],
            ));
            if (count($getinvoice_number) > 0)
            {

              $invoiceNumber = generate_finquest_invoice_number($getinvoice_number[0]['invoice_id']);
              if ($invoiceNumber)
              {
                $invoiceNumber = $this->config->item('FinQuest_invoice_no_prefix') . $invoiceNumber;
              }

              $update_data = array(
                'invoice_no'      => $invoiceNumber,
                'transaction_no'  => $transaction_no,
                'date_of_invoice' => date('Y-m-d H:i:s'),
                'modified_on'     => date('Y-m-d H:i:s'),
              );
              $this->db->where('pay_txn_id', $get_user_regnum_info[0]['id']);
              $this->master_model->updateRecord('exam_invoice', $update_data, array(
                'receipt_no' => $MerchantOrderNo,
              ));

              $attachpath = genarate_finquest_invoice($getinvoice_number[0]['invoice_id'], $session_id);
            }
            /*if(count($getinvoice_number) > 0){
                        $invoiceNumber = generate_bankquest_invoice_number($getinvoice_number[0]['invoice_id']);
                        if($invoiceNumber){
                        $invoiceNumber=$this->config->item('bankquest_no_prefix').$invoiceNumber;
                        }

                        }*/

            //Manage Log
            $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
            $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);

            if ($attachpath != '')
            {

              if ($this->Emailsending->mailsend_attch($info_arr, $attachpath))
              {

                #------------send mail to chetan-----------#
                $message = "Hello Team,<br><br>

                    Please add following member to fin@quest mailing list.<br>

                    Member details :<br>
                    Email id:
                    " . $this->session->userdata['enduserinfo']['email'] . '<br><br>

                    -- <br>
             REGARDS,<br>

            CHAITALI JADHAV | jr. Software Engineer<br>

            ESDS Software Solution Pvt. Ltd .<br>
            Website : WWW.ESDS.CO.IN | Email: CHAITALI.JADHAV@ESDS.CO.IN<br>
            Address : Plot No. B- 24 & 25, NICE Industrial Area, Satpur MIDC, <br>
            Nashik 422 007<br>
            Toll Free: 1800 209 3006 | Landline: +91 (0253) 663 6500<br>
            "_We are committed to creating Lifetime Customer Relationships by<br>
            delivering World Class Managed Data Center Services and Cloud enabled
            Solutions._<br>"

                    ';

                $info_arr_c = array(
                  'to'      => 'iibfdevp@esds.co.in',
                  'from'    => 'chaitali.jadhav@esds.co.in',
                  'subject' => 'Add member to finquest subscription list. - Staging environment',
                  'message' => $message,
                );

                $attachpath_c = '';

                if ($this->Emailsending->mailsend_attch_finquest($info_arr_c, $attachpath_c))
                {

                  /*log activity for genarate subscription number*/
                  $log_title   = "Email send sucessfully to chetan & member added in the finquest mail list :" . $subscription_number . " for id  :" . $this->session->userdata['FinQuest_memberdata']['id'] . "";
                  $log_message = serialize($info_arr_c);
                  storedUserActivity($log_title, $log_message, '', '');
                }
                #------------end send mail to chetan-----------#

                /*log activity for genarate subscription number*/
                $log_title   = "Email send sucessfully :" . $subscription_number . " for id  :" . $this->session->userdata['FinQuest_memberdata']['id'] . "";
                $log_message = serialize($info_arr);
                storedUserActivity($log_title, $log_message, '', '');
                /* Close User Log Actitives */

                redirect(base_url() . 'FinQuest/acknowledge/');
              }
              else
              {

                /*log activity for genarate subscription number*/
                $log_title   = "subscription number genrated but Email not send after payment:" . $subscription_number . " for id  :" . $this->session->userdata['FinQuest_memberdata']['id'] . "";
                $log_message = serialize($info_arr);
                storedUserActivity($log_title, $log_message, '', '');
                /* Close User Log Actitives */

                redirect(base_url() . 'FinQuest/acknowledge/');
              }
            }
            else
            {
              redirect(base_url() . 'FinQuest/acknowledge/');
            }
          }
        }
      }

      redirect(base_url() . 'FinQuest/acknowledge/');
    }
    else
    {
      die("Please try again...");
    }
  }

  public function sbitransfail()
  {
    if (isset($_REQUEST['encData']))
    {
      include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
      $key = $this->config->item('sbi_m_key');
      $aes = new CryptAES();
      $aes->set_key(base64_decode($key));
      $aes->require_pkcs5();
      $encData         = $aes->decrypt($_REQUEST['encData']);
      $responsedata    = explode("|", $encData);
      $MerchantOrderNo = $responsedata[0];
      $transaction_no  = $responsedata[1];

      $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array(
        'receipt_no' => $MerchantOrderNo,
      ), 'ref_id,status');

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

        $update_data = array(
          'transaction_no'      => $transaction_no,
          'status'              => 0,
          'transaction_details' => $responsedata[2] . " - " . $responsedata[7],
          'auth_code'           => '0399',
          'bankcode'            => $responsedata[8],
          'paymode'             => $responsedata[5],
          'callback'            => 'B2B',
        );
        $this->master_model->updateRecord('payment_transaction', $update_data, array(
          'receipt_no' => $MerchantOrderNo,
        ));

        $update_bank_data = array(
          'pay_status'           => 0,
          'modified_on'          => date("Y-m-d H:i:s"),
          'subscription_to_date' => date('Y-m-d', strtotime('+1 years')),
          'subscription_no'      => '',
        );

        $this->master_model->updateRecord('fin_quest', $update_bank_data, array(
          'id' => $this->session->userdata['FinQuest_memberdata']['id'],
        ));

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
    }
    else
    {
      die("Please try again...");
    }
  }
  public function acknowledge()
  {
    $data               = array();
    $start_date         = date('Y-m-d');
    $end_date           = date('Y-m-d', strtotime('+1 years'));
    $subscription_range = $start_date . " to " . $end_date;

    $valid_period = $subscription_range;
    if ($this->session->userdata('FinQuest_memberdata') == '')
    {
      redirect(base_url());
    }
    if ($this->session->userdata('enduserinfo'))
    {
      $this->session->unset_userdata('enduserinfo');
    }

    $user_info = $this->master_model->getRecords('fin_quest', array(
      'id' => $this->session->userdata['FinQuest_memberdata']['id'],
    ), 'subscription_no');

    $data = array(
      'middle_content'      => 'fin_quest/finquest_thankyou',
      'subscription_number' => $user_info[0]['subscription_no'],
      'valid_period'        => $valid_period,
    );

    $this->load->view('common_view_fullwidth', $data);
  }

  public function ajax_check_captcha()
  {
    $code = $_POST['code'];
    // check if captcha is set -
    //echo $code." == '' || ".$_SESSION["regcaptcha"];
    if ($code == '' || $_SESSION["regcaptcha"] != $code)
    {
      $this->form_validation->set_message('ajax_check_captcha', 'Invalid %s.');
      //$this->session->set_userdata("regcaptcha", rand(1, 100000));
      echo 'false';
    }
    else if ($_SESSION["regcaptcha"] == $code)
    {
      //$this->session->unset_userdata("regcaptcha");
      // $this->session->set_userdata("mycaptcha", rand(1,100000));
      echo 'true';
    }
  }

  // callback function to validate addressline 1
  public function address1($addressline1)
  {
    //( ! preg_match("/^(?:[A-Za-z0-9]+)(?:[A-Za-z0-9 \~\,\!\@\#\$\%\&\*\^\(\)\-\=\|\\\:\;\"\'\.\<\>\\?\/]*)$/", $addressline1)) ? FALSE : TRUE;
    if (!preg_match('/^[a-z0-9 .,-]+$/i', $addressline1))
    {
      $this->form_validation->set_message('address1', "Please enter valid addressline1");
      return false;
    }
    else
    {
      return true;
    }
  }

  //call back for checkpin
  public function check_checkpin($pincode, $statecode)
  {
    if ($statecode != "" && $pincode != '')
    {
      $statecode = mysqli_real_escape_string($this->db->conn_id, $statecode);
      $pincode   = mysqli_real_escape_string($this->db->conn_id, $pincode);
      $this->db->where("$pincode BETWEEN start_pin AND end_pin");
      $prev_count = $this->master_model->getRecordCount('state_master', array(
        'state_code' => $statecode,
      ));
      //echo $this->db->last_query();
      if ($prev_count == 0)
      {
        $str = 'Please enter Valid Pincode';
        $this->form_validation->set_message('check_checkpin', $str);
        return false;
      }
      else
      {
        $this->form_validation->set_message('error', "");
      }
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

  //call back for mobile duplication
  public function check_mobileduplication($mobile)
  {
    if ($mobile != "")
    {

      $prev_count = $this->master_model->getRecordCount('fin_quest', array(
        'contact_no' => $mobile,
        'pay_status' => '1',
      ));
      //echo $this->db->last_query();
      if ($prev_count == 1)
      {
        $this->db->where('subscription_to_date <', date('Y-m-d'));
        $prev_count1 = $this->master_model->getRecordCount('fin_quest', array(
          'contact_no' => $mobile,
          'pay_status' => '1',
        ));
        if ($prev_count1 == 1)
        {
          return true;
        }
        else
        {
          $str = 'The entered  mobile no already exist ';
          $this->form_validation->set_message('check_mobileduplication', $str);
          return false;
        }
      }
      else
      {

        return true;
      }
    }
    else
    {
      return false;
    }
  }

  //call back for e-mail duplication
  public function check_emailduplication($email)
  {
    if ($email != "")
    {
      $this->db->where('email_id', $email);
      $this->db->where('pay_status', '1');
      $prev_count = $this->master_model->getRecordCount('fin_quest');

      //echo $this->db->last_query();
      if ($prev_count == 1)
      {
        $this->db->where('email_id', $email);
        $this->db->where('subscription_to_date <', date('Y-m-d'));
        $this->db->where('pay_status', '1');
        $prev_count1 = $this->master_model->getRecordCount('fin_quest');
        if ($prev_count1 == 1)
        {

          return true;
        }
        else
        {
          $str = 'The entered email ID already exist ';
          $this->form_validation->set_message('check_emailduplication', $str);
          return false;
        }
      }
      else
      {
        return true;
      }
    }
    else
    {
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

  ##---------check mobile nnnumber alredy exist or not (prafull)-----------##
  public function mobileduplication()
  {
    $mobile = $_POST['mobile'];
    if ($mobile != "")
    {

      $this->db->where('contact_no ', $mobile);
      $this->db->where('pay_status', '1');
      $user_info = $this->master_model->getRecords('fin_quest');

      $this->db->where('contact_no ', $mobile);
      $prev_count = $this->master_model->getRecordCount('fin_quest', array(
        'pay_status' => '1',
      ));

      if ($prev_count == 1)
      {

        $this->db->where('subscription_to_date <', date('Y-m-d'));
        $this->db->where('contact_no ', $mobile);
        $prev_count1 = $this->master_model->getRecordCount('fin_quest', array(
          'pay_status' => '1',
        ));
        if ($prev_count1 == 1)
        {
          $data_arr = array(
            'ans' => 'ok',
          );
          echo json_encode($data_arr);
        }
        else
        {
          $newDate = date("d-m-Y", strtotime($user_info[0]['subscription_to_date']));
          $str     = 'Your subscription is valid till ' . $newDate . ',kindly subscribe once the current subscription is expired.';

          $data_arr = array(
            'ans'    => 'exists',
            'output' => $str,
          );
          echo json_encode($data_arr);
        }
      }
      else
      {

        $data_arr = array(
          'ans' => 'ok',
        );
        echo json_encode($data_arr);
      }
    }
    else
    {
      echo 'error';
    }
  }

  ##---------check mail alredy exist or not (prafull)-----------##
  public function emailduplication()
  {
    $email = $_POST['email'];
    if ($email != "")
    {
      $this->db->where('email_id', $email);
      $this->db->where('pay_status', '1');
      $user_info = $this->master_model->getRecords('fin_quest');

      $this->db->where('email_id', $email);
      $prev_count = $this->master_model->getRecordCount('fin_quest', array(
        'pay_status' => '1',
      ));

      if ($prev_count == 1)
      {
        $this->db->where('email_id', $email);
        $this->db->where('subscription_to_date <', date('Y-m-d'));
        $this->db->where('pay_status', '1');
        $prev_count2 = $this->master_model->getRecordCount('fin_quest', array(
          'pay_status' => '1',
        ));
        if ($prev_count2 == 1)
        {
          $data_arr = array(
            'ans' => 'ok',
          );
          echo json_encode($data_arr);
        }
        else
        {
          $newDate = date("d-m-Y", strtotime($user_info[0]['subscription_to_date']));
          $str     = 'Your subscription is valid till ' . $newDate . ',kindly subscribe once the current subscription is expired.';

          $data_arr = array(
            'ans'    => 'exists',
            'output' => $str,
          );
          echo json_encode($data_arr);
        }
      }
      else
      {

        $data_arr = array(
          'ans' => 'ok',
        );
        echo json_encode($data_arr);
      }
    }
    else
    {
      echo 'error';
    }
  }

  public function generatecaptchaajax()
  {
    /* $this->load->helper('captcha');
        $this->session->unset_userdata("regcaptcha");
        $this->session->set_userdata("regcaptcha", rand(1, 100000));
        $vals                   = array(
        'img_path' => './uploads/applications/',
        'img_url' => base_url() . 'uploads/applications/'
        );
        $cap                    = create_captcha($vals);
        $data                   = $cap['image'];
        $_SESSION["regcaptcha"] = $cap['word'];
        echo $data; */
    $this->load->model('Captcha_model');
    echo $captcha_image = $this->Captcha_model->generate_captcha_img('regcaptcha');
  }
}
