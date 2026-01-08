<?php
///usr/local/bin/php /home/supp0rttest/public_html/index.php Pending_payment_billdesk billdeskcallback
defined('BASEPATH') or exit('No direct script access allowed');
class Pending_payment_billdesk extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->helper('master_helper');
    $this->load->model('master_model');
    $this->load->model('billdesk_pg_model');
  }

  public function billdeskcallback()
  {
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper');
      $this->load->helper('file'); 
      $this->load->helper('getregnumber_helper');

    $str       = '';
    $receipt_no_arr = array();
    $yesterday = date('Y-m-d', strtotime("-3 days"));

    //START : PAYMENT TRANSACTION QUERY
      $sql       = "SELECT id, receipt_no, gateway, pg_flag, status FROM `payment_transaction` Where status IN (2) AND  date LIKE '%" . $yesterday . "%'";
    $record = $this->db->query($sql);

    if ($record->num_rows())
    {
      $receipt_no_arr = array_merge($receipt_no_arr, $record->result_array());
    }//END : PAYMENT TRANSACTION QUERY

    //START : BCBF PAYMENT TRANSACTION QUERY
      $query_bcbf = "SELECT id, receipt_no, gateway, pg_flag, status FROM iibfbcbf_payment_transaction WHERE status IN (2) AND date LIKE '%" . $yesterday . "%' AND payment_mode = 'Individual' AND gateway = '2'";
    $record_bcbf = $this->db->query($query_bcbf);
    
    if ($record_bcbf->num_rows())
    {
      $receipt_no_arr = array_merge($receipt_no_arr, $record_bcbf->result_array());
    }//END : BCBF PAYMENT TRANSACTION QUERY

      /* echo "<pre>";
        print_r($receipt_no_arr);
        echo $this->db->last_query(); die;
      die; */
    if (count($receipt_no_arr) > 0)
    {
      foreach ($receipt_no_arr as $c_row)
      {
        if ($c_row['status'] == '2')
        {
          $pg_flag = $c_row['pg_flag'];
          if($pg_flag == 'BC')
          {
              $update_data = array();
              $update_data['status'] = '0';
              $update_data['transaction_details'] = 'cancelled as no response from billdesk';
              $update_data['callback'] = 'PSS2SBD';						
              $update_data['description'] = 'cancelled as no response from billdesk';
              $update_data['approve_reject_date'] = date('Y-m-d H:i:s');
              $update_data['updated_on'] = date('Y-m-d H:i:s');
              $this->master_model->updateRecord('iibfbcbf_payment_transaction', $update_data, array('receipt_no' => $c_row['receipt_no'], 'status' => '2', 'payment_mode'=>'Individual'));

              $this->Iibf_bcbf_model->insert_common_log('BILLDESK RESPONSE : payment status updated as fail - PSS2SBD', 'iibfbcbf_payment_transaction', $this->db->last_query(), $c_row['id'],'billdesk_action','The payment is fail - PSS2SBD', json_encode($update_data));
              // END : UPDATE PAYMENT FAIL STATUS IN PAYMENT TRANSACTION TABLE AND INSERT LOG
              
              // QUERY TO GET UPDATED PAYMENT DETAILS AFTER PAYMENT SUCCESS
              $payment_info = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('receipt_no' => $c_row['receipt_no'], 'payment_mode'=>'Individual'), 'transaction_no, date, amount, id, description, status, exam_ids, exam_code, exam_period, receipt_no');
              
              if(count($payment_info) > 0 && $payment_info[0]['status'] == '0')
              {
                $member_exam_id = $payment_info[0]['exam_ids'];
                
                $this->db->join('iibfbcbf_batch_candidates bc', 'bc.candidate_id = me.candidate_id', 'INNER');
                $member_data = $this->master_model->getRecords('iibfbcbf_member_exam me', array('me.member_exam_id' => $member_exam_id, 'payment_mode'=>'Individual'), 'bc.candidate_id, bc.regnumber, bc.candidate_photo, bc.candidate_sign, bc.id_proof_file, bc.qualification_certificate_file, bc.re_attempt');
                
                if(count($member_data) > 0)
                {
                  //START : UPDATE TRANSACTION NUMBER & PAYMENT STATUS IN iibfbcbf_member_exam
                  $up_exam_data = array();
                  $up_exam_data['ref_utr_no'] = '';
                  $up_exam_data['pay_status'] = '0';
                  $this->master_model->updateRecord('iibfbcbf_member_exam',$up_exam_data, array('member_exam_id' => $member_exam_id));
                  
                  $this->Iibf_bcbf_model->insert_common_log('BILLDESK RESPONSE : update transaction number and payment status in member exam - PSS2SBD', 'iibfbcbf_member_exam', $this->db->last_query(), $member_exam_id,'billdesk_action','The payment fail transaction number and payment status is updated in member exam - PSS2SBD', json_encode($up_exam_data));

                  $this->Iibf_bcbf_model->insert_common_log('Candidate : Candidate payment fail', 'iibfbcbf_batch_candidates', '', $member_data[0]['candidate_id'],'candidate_action','The payment fail for the exam ('.$payment_info[0]['exam_code'].' & '.$payment_info[0]['exam_period'].') by individual.', '');
                }
                
                // START : UPDATE TRANSACTION NUMBER IN INVOICE TABLE
                $exam_invoice = $this->master_model->getRecords('exam_invoice',array('pay_txn_id' => $payment_info[0]['id'], 'exam_code'=>$payment_info[0]['exam_code'], 'exam_period'=>$payment_info[0]['exam_period'], 'receipt_no'=>$payment_info[0]['receipt_no'], 'app_type'=>'BC'),'invoice_id');
                
                if(count($exam_invoice) > 0 && $payment_info[0]['amount'] > 0)
                {
                  $up_invoice_data['transaction_no'] = '';                
                  $up_invoice_data['modified_on'] = date('Y-m-d H:i:s');            
                  $this->master_model->updateRecord('exam_invoice',$up_invoice_data,array('invoice_id' => $exam_invoice[0]['invoice_id']));
                  
                  $this->Iibf_bcbf_model->insert_common_log('BILLDESK RESPONSE : update transaction number for fail payment in invoice - PSS2SBD', 'exam_invoice', $this->db->last_query(), $exam_invoice[0]['invoice_id'],'billdesk_action','The exam transaction number is updated in exam invoice table for fail payment - PSS2SBD', json_encode($up_invoice_data));
                }// END : UPDATE TRANSACTION NUMBER IN INVOICE TABLE
            }
          }
          else
          {
            $update_arr = array('status' => 7, 'transaction_details' => 'cancelled as no response from billdesk', 'callback' => 'PSS2SBD');
            $where_arr  = array('receipt_no' => $c_row['receipt_no']);
            $this->master_model->updateRecord('payment_transaction', $update_arr, $where_arr);
          }
        }
      }
    }
  }
  }    