<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bcbf_wrong_application_settlement_991_sm extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->library('upload');
    $this->load->model('Master_model');
    $this->load->model('log_model');
    $this->load->model('Emailsending');
    $this->load->library('email');
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set("memory_limit", "-1");

    //echo $_SERVER['HTTP_USER_AGENT'];

    $this->load->model('master_model');
    $this->load->helper('file');
    $this->load->helper('directory');
  }

  public function setting_smtp()
  {
    $permission = TRUE;

    if ($permission == TRUE)
    {
      $config['protocol']      = 'SMTP';
      //$config['smtp_host']    = 'iibf.esdsconnect.com';
      // local ip 10.11.38.100 instead of 115.124.108.41 can also be used
      $config['smtp_host']    = '115.124.108.41';
      $config['smtp_port']    = '25';
      $config['smtp_timeout'] = '10';
      $config['smtp_user']    = 'logs@iibf.esdsconnect.com';
      $config['smtp_pass']    = 'logs@IiBf!@#';
      $config['charset']      = 'utf-8';
      $config['newline']      = "\r\n";
      $config['mailtype']   = 'html'; // or html
      $config['validation']   = TRUE; // bool whether to validate email or not  
      $this->email->initialize($config);
    }
  }

  function send_email_to_candidates()
  {
    $this->db->limit(500);
    $candidate_data = $this->master_model->getRecords('bcbf_wrong_application_settlement_991_sm', array('email_sent_on2' => '0000-00-00 00:00:00'));
    echo 'Count : '.count($candidate_data).'<br>';
    if(count($candidate_data) > 0)
    {
      foreach($candidate_data as $res)
      {    
        $this->setting_smtp();
        $this->email->from('logs@iibf.esdsconnect.com', "IIBF");
        $this->email->reply_to('noreply@iibf.org.in', 'IIBF');
        $bcc = array('anil.s@esds.co.in', 'sagar.matale@esds.co.in');
        
        $subject = 'Important Notice on BCBF Exam Registration';
        $to_mail = strtolower($res['email']);
        
        $mail_content_sub = '<p style="margin:0px 0 12px 0; text-transform:capitalize;">Dear '.strtolower($res['name']).',</p>'; 
        $mail_content_sub .= '<p style="margin:12px 0 12px 0;">This is to inform you that you have registered for BCBF exam through an incorrect/invalid link. Hence the admit letter generated through the said link is not valid. The fees paid by you will be refunded in due course.</p>
        <p style="margin:12px 0 12px 0;">Please note that only BC Agents who have commenced operations before April 1, 2024, are eligible to apply for BCBF examination without undergoing the mandatory training.</p>
        <p style="margin:12px 0 12px 0;">If you have commenced BC operations before April 1, 2024, you may register for the BC examination using the correct link available at the designated CSC centres. In case you have commenced BC operations on or after April 1, 2024, you have to undergo the mandatory training to be eligible to apply for the examination.</p>
        <p style="margin:14px 0 0px 0;">We sincerely regret the inconvenience caused.<br>Team IIBF</p>'; 
        
        $email_content =  '
            <!DOCTYPE html>
            <html>
              <head>
                <meta charset="UTF-8">
                <title>Email</title>
                <style type="text/css">
                  body { font-family: Arial,Helvetica,sans-serif; font-size: 14px; color: #000; margin: 0; padding: 0; } 
                  p { font-size:15px; line-height:23px; text-align:justify; font-family: Arial,Helvetica,sans-serif; }                            
                </style>
              </head>
              <body>
                <br>
                <table cellspacing="0" cellpadding="0" width="600px" border="1" style="width: 100%; max-width:800px; border-collapse: collapse; font-size: 14px; line-height: 20px; border: 1px solid #041f38; margin: 0 auto; color:#000; font-family: Arial,Helvetica,sans-serif;">
                  <tbody>                    
                    <tr>
                      <td style="background-color: #009fdf;color: #fff; font-size: 20px; font-weight: bold; text-align: center; padding: 20px 5px 15px; border-bottom: 1px solid #000; line-height: 25px;">INDIAN INSTITUTE OF BANKING & FINANCE<br><span style="font-size: 16px; font-weight: 600; font-family: Arial,Helvetica,sans-serif;">(AN ISO 21001:2018 Certified)</span></td>
                    </tr>
                    <tr>
                      <td style="padding:25px 20px 20px; font-family: Arial,Helvetica,sans-serif;">                      
                        '.$mail_content_sub.'                      
                      </td>
                    </tr>
                    <tr>
                      <td style="background-color: #009fdf; color: #fff; font-weight: bold; text-align: center; padding: 0 8px; height: 38px; font-family: Arial,Helvetica,sans-serif;">&copy; '.date('Y').' IIBF. All rights reserved.</td>
                    </tr>
                  </tbody>
                </table>
                <br>
              </body>
            </html>';
        //echo $email_content; //exit;
        
        $this->email->to($to_mail);
        $this->email->bcc($bcc);
        $this->email->subject($subject);
        $this->email->message($email_content);
        if ($this->email->send())
        {
          $this->master_model->updateRecord('bcbf_wrong_application_settlement_991_sm', array('email_sent_on2'=>date('Y-m-d H:i:s')), array('id' => $res['id']));

          echo '<br>Email Sent : '.$to_mail;
        }
      }
    }
  }

  function send_sms_to_candidates()
  {
    $this->db->limit(500);
    $candidate_data = $this->master_model->getRecords('bcbf_wrong_application_settlement_991_sm', array('sms_sent_on2' => '0000-00-00 00:00:00'));
    if(count($candidate_data) > 0)
    {
      foreach($candidate_data as $res)
      {
        $mobile = $res['mobile'];
        $result = $this->master_model->send_sms_common_all($mobile, "You have registered for BCBF exam through an incorrect/invalid link. Please refer our e-mail for further details. Team IIBF", '1707172726254356815', 'IIBFCO');
        
        if(isset($result['status']) && $result['status'] == 'success')        
        {
          $this->master_model->updateRecord('bcbf_wrong_application_settlement_991_sm', array('sms_sent_on2'=>date('Y-m-d H:i:s')), array('id' => $res['id']));

          echo '<br>SMS Sent : '.$mobile;
        }
      }
    }

  }

  function wrong_transactions_payment_status_991()
  {
    $this->load->model('csc_pg_model');

    $start_limit = '900';
    $this->db->limit('100', $start_limit);

    $this->db->join('payment_transaction pt', 'pt.receipt_no = ei.receipt_no AND pt.gateway = "csc" AND pt.transaction_no = ei.transaction_no', 'INNER');
    $invoice_data = $this->master_model->getRecords('exam_invoice ei', array('ei.exam_code' => '991', 'ei.exam_period' => '998', 'ei.date_of_invoice >=' => '2024-09-01', 'pt.status'=>'1'), 'ei.invoice_id, ei.exam_code, ei.exam_period, ei.member_no, ei.pay_txn_id, ei.receipt_no, ei.transaction_no, ei.invoice_no, ei.invoice_image, pt.id, pt.date AS PaymentDate, pt.amount');

    echo '<br><strong>Qry : </strong>'.$this->db->last_query();
    echo '<br><br><strong>Total Count : </strong>'.count($invoice_data);

    if(count($invoice_data) > 0)
    {
      echo '<style>
              table {  border: 1px solid #ccc; width: 100%; border-collapse: collapse; margin: 20px auto; }
              th { border: 1px solid #ccc; text-align:center; padding:10px 5px; }
              td { border: 1px solid #ccc; padding:6px 5px; }
              .text_center  { text-align:center; } 
            </style>
            <table>
              <thead>
                <tr>
                  <th>Sr No</th>
                  <th>Member Number</th>
                  <th>Receipt Number</th>
                  <th>Transaction Number</th>
                  <th>CSC Payment Status</th>
                  <th>CSC Refund Status</th>
                </tr>                  
              </thead>
              
              <tbody>';
                $sr_no = 1;
                foreach($invoice_data as $res)
                {
                  //START : PAYMENT DETAILS USING QUERY API
                  $payment_details = $this->csc_pg_model->csc_qry_api_model($res['receipt_no']);  
                  
                  $csc_payment_status = '';
                  if(isset($payment_details['response_status']) && $payment_details['response_status'] == 'Success')
                  {
                    $csc_payment_status .= $payment_details['response_status'];
                    $csc_payment_status .= "<br>txn_status - ".$payment_details['txn_status'];
                  }
                  else if(isset($payment_details['response_status']) && $payment_details['response_status'] == 'Fail')
                  {
                    $csc_payment_status .= $payment_details['response_status'];
                    $csc_payment_status .= "<br>response_code - ".$payment_details['response_code'];
                  }

                  $csc_payment_status .= '<table border="1" cellspacing="5" cellpadding="5" style="border-collapse:collapse; margin-top:20px;display:none;"><tbody>';
                  foreach($payment_details as $key=>$val)
                  {
                    $csc_payment_status .= '<tr>
                            <td>'.$key.'</td>
                            <td>'.$val.'</td>
                          </tr>';
                  }
                  $csc_payment_status .= '</tbody></table>';
                  //END : PAYMENT DETAILS USING QUERY API

                  //START : REFUND DETAILS USING QUERY API
                  $candidate_data = $this->master_model->getRecords('csc_refund_log', array('merchant_txn' => $res['receipt_no'], 'refund_status'=>'Success'));
                  $refund_reference = '';
                  $csc_refund_status = '';
                  if(count($candidate_data) > 0) 
                  { 
                    $refund_reference = $candidate_data[0]['refund_reference']; 
                    $refund_details = $this->csc_pg_model->csc_refund_status_api_model($res['receipt_no'],$refund_reference);   
                    
                    if(isset($refund_details['refund_status']) && $refund_details['refund_status'] == 'S')
                    {
                      $csc_refund_status .= $refund_details['refund_status'].' - '.$refund_details['refund_message'];
                      $csc_refund_status .= "<br>refund_mode - ".$refund_details['refund_mode'];
                      $csc_refund_status .= "<br>refund_date - ".$refund_details['refund_date'];
                    }
                    else if(isset($refund_details['response_status']) && $refund_details['response_status'] == 'Fail')
                    {
                      $csc_refund_status .= $refund_details['response_status'];
                      $csc_refund_status .= "<br>response_code - ".$refund_details['response_code'];
                      $csc_refund_status .= "<br>response_message - ".$refund_details['response_message'];
                    }
  
                    $csc_refund_status .= '<table border="1" cellspacing="5" cellpadding="5" style="border-collapse:collapse; margin-top:20px;display:none;"><tbody>';
                    foreach($refund_details as $key=>$val)
                    {
                      $csc_refund_status .= '<tr>
                              <td>'.$key.'</td>
                              <td>'.$val.'</td>
                            </tr>';
                    }
                    $csc_refund_status .= '</tbody></table>';
                  }
                  else
                  {
                    $csc_refund_status .= 'Refund reference number not available';
                  }

                  //END : REFUND DETAILS USING QUERY API

                  echo '<tr>  
                          <td class="text_center">'.$sr_no.'</td>
                          <td>'.$res['member_no'].'</td>
                          <td>'.$res['receipt_no'].'</td>
                          <td>'.$res['transaction_no'].'</td>
                          <td>'.$csc_payment_status.'</td>
                          <td>'.$csc_refund_status.'</td>
                        </tr>';

                  $sr_no++;
                }
              echo 
              '</tbody>
            </table>';
    }
  }

  function wrong_transactions_initiate_refund_991()
  {
    $this->load->model('csc_pg_model');

    $start_limit = '0';
    $this->db->limit('1', $start_limit);
    $this->db->join('payment_transaction pt', 'pt.receipt_no = ei.receipt_no AND pt.gateway = "csc" AND pt.transaction_no = ei.transaction_no', 'INNER');
    $invoice_data = $this->master_model->getRecords('exam_invoice ei', array('ei.exam_code' => '991', 'ei.exam_period' => '998', 'ei.date_of_invoice >=' => '2024-09-01', 'pt.status'=>'1'), 'ei.invoice_id, ei.exam_code, ei.exam_period, ei.member_no, ei.pay_txn_id, ei.receipt_no, ei.transaction_no, ei.invoice_no, ei.invoice_image, pt.id, pt.date AS PaymentDate, pt.amount');

    echo '<br><strong>Qry : </strong>'.$this->db->last_query(); //exit;
    echo '<br><br><strong>Total Count : </strong>'.count($invoice_data);

    if(count($invoice_data) > 0)
    {
      echo '
      <style>
        table {  border: 1px solid #000; width: 100%; border-collapse: collapse; margin: 20px auto; }
        th { border: 1px solid #000; text-align:center; padding:10px 5px; }
        td { border: 1px solid #000; padding:6px 5px; }
        .text_center  { text-align:center; } 
      </style>
      <table>
        <thead>
          <tr>
            <th>Sr No</th>
            <th>Member Number</th>
            <th>Receipt Number</th>
            <th>Transaction Number</th>
            <th>CSC Refund Status</th>
          </tr>                  
        </thead>
        
        <tbody>';
          $sr_no = 1;
          foreach($invoice_data as $res)
          {
            $candidate_data = $this->master_model->getRecords('bcbf_wrong_application_settlement_991_sm', array('member_number' => $res['member_no']));
            if(count($candidate_data) > 0)
            {
              //START : REFUND THE TRANSACTION
              $refund_result = $this->csc_pg_model->csc_refund_api_model($res['receipt_no']); 
              
              $refund_status = '';
              if(isset($refund_result['refund_status']) && $refund_result['refund_status'] == 'Success')
              {
                $refund_status .= $refund_result['refund_status'];
                $refund_status .= "<br>refund_reference - ".$refund_result['refund_reference'];
              }
              else
              {
                $refund_status .= $refund_result['response_status'];
                $refund_status .= "<br>response_code - ".$refund_result['response_code'];
                $refund_status .= "<br>response_message - ".$refund_result['response_message'];
              }

              $refund_status .= '<table border="1" cellspacing="5" cellpadding="5" style="border-collapse:collapse; margin-top:20px;display:none;"><tbody>';
              foreach($refund_result as $key=>$val)
              {
                $refund_status .= '<tr>
                        <td>'.$key.'</td>
                        <td>'.$val.'</td>
                      </tr>';
              }
              $refund_status .= '</tbody></table>';
              //END : REFUND THE TRANSACTION

              echo '<tr>  
                      <td class="text_center">'.$sr_no.'</td>
                      <td>'.$res['member_no'].'</td>
                      <td>'.$res['receipt_no'].'</td>
                      <td>'.$res['transaction_no'].'</td>
                      <td>'.$refund_status.'</td>
                    </tr>';

              //START : INSERT LOG IN DATABASE TABLE
              $add_data = array();
              $add_data['txn_no'] = $res['transaction_no'];
              $add_data['order_id'] = $res['receipt_no'];
              $add_data['txn_date'] = $res['PaymentDate'];
              $add_data['txn_amt'] = $res['amount'];
              $add_data['refund_reason'] = 'Wrong application for bcbf csc 991 exam';
              $add_data['refund_date'] = date('Y-m-d H:i:s');

              if(isset($refund_result['refund_status']) && $refund_result['refund_status'] == 'Success')
              {
                $add_data['refund_status'] = $refund_result['refund_status'];
                $add_data['merchant_id'] = $refund_result['merchant_id'];
                $add_data['merchant_txn'] = $refund_result['merchant_txn'];
                $add_data['merchant_reference'] = $refund_result['merchant_reference'];
                $add_data['refund_reference'] = $refund_result['refund_reference'];
                $add_data['csc_txn'] = $refund_result['csc_txn'];
              }
              else
              {
                $add_data['refund_status'] = $refund_result['response_status'];
              }

              $add_data['response_data'] = json_encode($refund_result);                  
              $add_data['created_on'] = date('Y-m-d H:i:s');
              $this->master_model->insertRecord('csc_refund_details',$add_data);
              //END : INSERT LOG IN DATABASE TABLE

              //START : SEND MAL TO CANDIDATE
              if(isset($refund_result['refund_status']) && $refund_result['refund_status'] == 'Success')
              {
                $this->master_model->updateRecord('bcbf_wrong_application_settlement_991_sm', array('refund_done'=>'1', 'refunded_on'=>date('Y-m-d H:i:s')), array('id' => $candidate_data[0]['id']));

                $this->setting_smtp();
                $this->email->from('logs@iibf.esdsconnect.com', "IIBF");
                $this->email->reply_to('noreply@iibf.org.in', 'IIBF');
                $bcc = array('anil.s@esds.co.in', 'sagar.matale@esds.co.in');
                
                $subject = 'Refund Transaction Details';
                $to_mail = strtolower($candidate_data[0]['email']);
                
                $mail_content_sub = '<p style="margin:0px 0 12px 0; text-transform:capitalize;">Dear '.strtolower($candidate_data[0]['name']).',</p>'; 
                $mail_content_sub .= '<p style="margin:12px 0 12px 0;">This is to inform you that you have registered for BCBF exam through an incorrect/invalid link. Hence the admit letter generated through the said link is not valid. The fees paid by you will be refunded in due course.</p>
                <p style="margin:12px 0 12px 0;">Please note that only BC Agents who have commenced operations before April 1, 2024, are eligible to apply for BCBF examination without undergoing the mandatory training.</p>
                <p style="margin:12px 0 12px 0;">If you have commenced BC operations before April 1, 2024, you may register for the BC examination using the correct link available at the designated CSC centres. In case you have commenced BC operations on or after April 1, 2024, you have to undergo the mandatory training to be eligible to apply for the examination.</p>

                <p style="margin:12px 0 5px 0;">Please find the refund details below. (Note: The refund amount will be credited within 7 working days.)</p>';

                $mail_content_sub .= '<style>table, td { border: 1px solid #000; } td { padding:5px 10px; } </style>
                <table style="border-collapse: collapse; width: auto; margin: 5px 0 20px 0;"><tbody>';
                foreach($refund_result as $key=>$val)
                {
                  if($key == 'merchant_txn' || $key == 'refund_reference' || $key == 'csc_txn')
                  {
                    $display_key = $key;
                    if($key == 'refund_status') { $display_key = 'Refund Status'; }
                    else if($key == 'merchant_id') { $display_key = 'Merchant Id'; }
                    else if($key == 'merchant_txn') { $display_key = 'Merchant Transaction Number'; }
                    else if($key == 'merchant_reference') { $display_key = 'Merchant Reference Number'; }
                    else if($key == 'refund_reference') { $display_key = 'Refund Reference Number'; }
                    else if($key == 'csc_txn') { $display_key = 'CSC Transaction Number'; }

                    $mail_content_sub .= '<tr>
                            <td>'.$display_key.'</td>
                            <td>'.$val.'</td>
                          </tr>';
                  }
                }
                $mail_content_sub .= '<tr>
                          <td>Refund Date</td>
                          <td>'.date('Y-m-d H:i:s').'</td>
                        </tr>';
                $mail_content_sub .= '</tbody></table>';
                $mail_content_sub .= '<p style="margin:14px 0 0px 0;">We sincerely regret the inconvenience caused.<br>Team IIBF</p>'; 
                
                $email_content =  '
                    <!DOCTYPE html>
                    <html>
                      <head>
                        <meta charset="UTF-8">
                        <title>Email</title>
                        <style type="text/css">
                          body { font-family: Arial,Helvetica,sans-serif; font-size: 14px; color: #000; margin: 0; padding: 0; } 
                          p { font-size:15px; line-height:23px; text-align:justify; font-family: Arial,Helvetica,sans-serif; }                            
                        </style>
                      </head>
                      <body>
                        <br>
                        <table cellspacing="0" cellpadding="0" width="600px" border="1" style="width: 100%; max-width:800px; border-collapse: collapse; font-size: 14px; line-height: 20px; border: 1px solid #041f38; margin: 0 auto; color:#000; font-family: Arial,Helvetica,sans-serif;">
                          <tbody>                    
                            <tr>
                              <td style="background-color: #009fdf;color: #fff; font-size: 20px; font-weight: bold; text-align: center; padding: 20px 5px 15px; border-bottom: 1px solid #000; line-height: 25px;">INDIAN INSTITUTE OF BANKING & FINANCE<br><span style="font-size: 16px; font-weight: 600; font-family: Arial,Helvetica,sans-serif;">(AN ISO 21001:2018 Certified)</span></td>
                            </tr>
                            <tr>
                              <td style="padding:25px 20px 20px; font-family: Arial,Helvetica,sans-serif;">                      
                                '.$mail_content_sub.'                      
                              </td>
                            </tr>
                            <tr>
                              <td style="background-color: #009fdf; color: #fff; font-weight: bold; text-align: center; padding: 0 8px; height: 38px; font-family: Arial,Helvetica,sans-serif;">&copy; '.date('Y').' IIBF. All rights reserved.</td>
                            </tr>
                          </tbody>
                        </table>
                        <br>
                      </body>
                    </html>';
                //echo $email_content; //exit;
                
                $this->email->to($to_mail);
                $this->email->bcc($bcc);
                $this->email->subject($subject);
                $this->email->message($email_content);
                //$this->email->send();    
                
                if ($this->email->send())
                {
                  //echo '<br>Email Sent : '.$to_mail;
                }
              }//END : SEND MAL TO CANDIDATE
              $sr_no++;
            }
          }
          echo 
        '</tbody>
      </table>';
    }
  }
}
