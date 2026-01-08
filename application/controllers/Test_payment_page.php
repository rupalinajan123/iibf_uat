<?php
/* THE CONTROLLER IS CREATED BY SAGAR M ON 2024-12-10 TO TEST THE BILLDESK PAYMENT */
defined('BASEPATH') or exit('No direct script access allowed');
class Test_payment_page extends CI_Controller
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
    ////echo $this->config->item('bd_payment_mode_sm');
  }
  
  public function index()
  {
    if(isset($_POST) && count($_POST) > 0)
    {
      $insert_info = array();      
      $insert_info['first_name'] = $this->input->post('fullname');
      $insert_info['last_name'] = date('d-m-Y, H.i');
      $insert_info['registration_type'] = 'O';
      $insert_info['city'] = '609';
      $insert_info['state'] = 'MAH';
      $insert_info['pincode'] = '422008';
      $insert_info['created_on'] = date('Y-m-d H:i:s');
      
      if ($last_id = $this->master_model->insertRecord('iibfbcbf_batch_candidates', $insert_info, true))
      {
        $userarr = array('candidate_id' => $last_id);
        $this->session->set_userdata('SESSION_MEMBER_DATA', $userarr);
        redirect(base_url() . "test_payment_page/make_payment");
      }
    }
    $this->load->view('test_payment_page');
  }

  public function make_payment()
  {
    $cgst_rate = $sgst_rate = $igst_rate = $tax_type = '';
    $cgst_amt  = $sgst_amt  = $igst_amt  = '';
    $cs_total  = $igst_total  = '';
    $getstate  = array();
    
    $candidate_id = $this->session->userdata['SESSION_MEMBER_DATA']['candidate_id'];
    if (!empty($candidate_id))
    {
      $member_data = $this->Master_model->getRecords('iibfbcbf_batch_candidates', array('candidate_id' => $candidate_id), array('state'));
    }

    if (isset($_POST['processPayment']) && $_POST['processPayment'])
    {
      $checkpayment = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('exam_ids' => $candidate_id, 'status' => '2'));
      if (count($checkpayment) > 0)
      {
        $this->session->set_flashdata('error', 'Last payment is in process');
        redirect(base_url() . '/test_payment_page');
      }
      
      $pg_name = 'billdesk';
      $state = $member_data[0]['state'];
      if (!empty($state))
      {
        if ($state == 'MAH')
        {
          $amount = 1;
        }        
        else
        {
          $amount = 1;
        }
      }
      
      // Create transaction
      $insert_data = array(
        'exam_ids'      => $candidate_id,
        'gateway'     => '2',
        'amount'      => $amount,
        'date'        => date('Y-m-d H:i:s'),
        'description' => "Bulk test Payment",
        'payment_mode' => "Bulk",
        'status'      => 2,        
        'pg_flag'     => 'BC'
      );

      $pt_id = $this->master_model->insertRecord('iibfbcbf_payment_transaction', $insert_data, true);

      $MerchantOrderNo = reg_sbi_order_id($pt_id);
      
      $custom_field = $candidate_id . "^BC^IIBF_BULK_BCBF^" . $MerchantOrderNo;
      $custom_field_billdesk = $candidate_id . "-BC-IIBF_BULK_BCBF-" . $MerchantOrderNo;

      // update receipt no. in payment transaction -
      $update_data = array('receipt_no' => $MerchantOrderNo, 'pg_other_details' => $custom_field);
      $this->master_model->updateRecord('iibfbcbf_payment_transaction', $update_data, array('id' => $pt_id));

      //get value for invoice details [Tejasvi]
      if (!empty($state))
      {
        //get state code,state name,state number.
        $getstate = $this->master_model->getRecords('state_master', array('state_code' => $state, 'state_delete' => '0'));
      }

      if ($state == 'MAH')
      {
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
      else
      {
        $igst_rate  = $this->config->item('igst_rate');
        $igst_amt   = $this->config->item('igst_amt');
        $igst_total = $amount;
        $tax_type   = 'Inter';
      }

      $invoice_insert_array = array(
        'pay_txn_id' => $pt_id,
        'receipt_no'                               => $MerchantOrderNo,
        'member_no'                                => $candidate_id,
        'state_of_center'                          => $state,
        'app_type'                                 => 'R',
        'service_code'                             => $this->config->item('reg_service_code'),
        'qty'                                      => '1',
        'state_code'                               => $getstate[0]['state_no'],
        'state_name'                               => $getstate[0]['state_name'],
        'tax_type'                                 => $tax_type,
        'fee_amt'                                  => 1,
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
        'created_on'                               => date('Y-m-d H:i:s')
      );

      $inser_id = $this->master_model->insertRecord('exam_invoice', $invoice_insert_array);
      if ($pg_name == 'billdesk')
      {
        $billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $candidate_id, $candidate_id, '', 'test_payment_page/handle_billdesk_response', '', '', '', $custom_field_billdesk);

        if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE')
        {
          $data['bdorderid']      = $billdesk_res['bdorderid'];
          $data['token']          = $billdesk_res['token'];
          $data['responseXHRUrl'] = $billdesk_res['responseXHRUrl'];
          $data['returnUrl']      = $billdesk_res['returnUrl'];
          $data['bulk_payment_flag'] = 'IIBF_BULK_BCBF';////
          $this->load->view('pg_billdesk/pg_billdesk_form', $data);
        }
        else
        {
          echo '1'; exit;
          $this->session->set_flashdata('error', 'Transaction failed...!');
          redirect(base_url() . 'test_payment_page');
        }
      }      
    }
    else
    {
      $data['show_billdesk_option_flag'] = 1;
      $this->load->view('pg_sbi/make_payment_page', $data);
    }
  } 
  
  /* BILLDESK RESPONSE CODE BY PRATIBA BORSE 25 March 22 */
  public function handle_billdesk_response()
  {
    $_SESSION['session_regid'] = $this->session->userdata['SESSION_MEMBER_DATA']['candidate_id'];
    $this->session->unset_userdata('SESSION_MEMBER_DATA');

    if (isset($_REQUEST['transaction_response']))
    {
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

      $get_user_regnum_info = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('receipt_no' => $MerchantOrderNo), 'exam_ids, status, id');

      $qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);
      if ($auth_status == '0300' && $qry_api_response['auth_status'] == '0300' && $get_user_regnum_info[0]['status'] == 2)
      {
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

        $update_query = $this->master_model->updateRecord('iibfbcbf_payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo, 'status' => 2));      

        if ($this->db->affected_rows())
        {
          $candidate_id = $get_user_regnum_info[0]['exam_ids'];
          $applicationNo = generate_O_memreg($candidate_id);          
          
          if (count($get_user_regnum_info) > 0)
          {
            $update_mem_data = array('regnumber' => $applicationNo);
            $this->master_model->updateRecord('iibfbcbf_batch_candidates', $update_mem_data, array('candidate_id' => $candidate_id));

            $update_mem_data2 = array('member_no' => $applicationNo);
            $this->master_model->updateRecord('exam_invoice', $update_mem_data2, array('pay_txn_id' => $get_user_regnum_info[0]['id'], 'receipt_no' => $MerchantOrderNo));

            redirect(base_url() . 'test_payment_page/acknowledge/');
          }
        }
        exit();
      }
      elseif ($auth_status == '0002'  && $get_user_regnum_info[0]['status'] == 2)
      {
        $update_data = array(
          'transaction_no'      => $transaction_no,
          'status'              => 2,
          'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
          'auth_code'           => '0300',
          'bankcode'            => $bankid,
          'paymode'             => $txn_process_type,
          'callback'            => 'B2B',
        );

        $this->master_model->updateRecord('iibfbcbf_payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
        
        $this->session->set_flashdata('success', 'Transaction under process...!');
        redirect(base_url('test_payment_page'));
      }
      else
      {
        if ($get_user_regnum_info[0]['status'] != 0 && $get_user_regnum_info[0]['status'] == 2)
        {
          $update_data = array(
            'transaction_no'      => $transaction_no,
            'status'              => 0,
            'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
            'auth_code'           => '0399',
            'bankcode'            => $bankid,
            'paymode'             => $txn_process_type,
            'callback'            => 'B2B',
          );

          $this->master_model->updateRecord('iibfbcbf_payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
          
          echo '2'; exit;
          $this->session->set_flashdata('success', 'Transaction failed...!');
          redirect(base_url('test_payment_page'));
        }
      }
    }
    else
    {
      die("Please try again...");
    }
  }

  public function acknowledge()
  {    
    $data = array();
    if (isset($_SESSION['session_regid']) && $_SESSION['session_regid'] != '')
    {
      $user_info = $this->master_model->getRecords('iibfbcbf_batch_candidates', array('candidate_id' => $this->session->userdata('session_regid')), 'regnumber, created_on');

      $time_after_5min = date('Y-m-d H:i:s', strtotime("+5 min", strtotime($user_info[0]['created_on'])));
      if (date('Y-m-d H:i:s') > $time_after_5min)
      {
        $_SESSION['session_regid'] = '';

        $this->session->set_flashdata('error', 'timeout');
        redirect(base_url('test_payment_page'));
      }

      echo 'Thank You. your registration number is : '.$user_info[0]['regnumber']; exit;
    }
    else
    {
      $this->session->set_flashdata('error', 'timeout');
      redirect(base_url('test_payment_page'));
    }
  }
}
