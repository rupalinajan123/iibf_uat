<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Pending_payment extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('master_helper');
        $this->load->model('master_model');
        $this->load->model('billdesk_pg_model');
        $this->load->model('refund_after_capacity_full');
    }

    public function billdeskcallback()
    {exit;
        $str        = '';
        $filehandle = fopen("payment_pending/lock.txt", "c+");
        if (flock($filehandle, LOCK_EX | LOCK_NB)) {
            $start_time  = date("Y-m-d H:i:s");
            $todays_date = date("d-m-Y");
            $dir         = 'payment_pending/' . $todays_date;
            if (!is_dir($dir)) {mkdir($dir, 0755);}

            $yesterday = date('Y-m-d', strtotime("-1 days"));
            //$sql       = "SELECT receipt_no, gateway,status FROM `payment_transaction` Where status IN (2,0) AND exam_code != 991 AND date LIKE '%" . $yesterday . "%'";
            $sql = "SELECT payment_transaction.receipt_no, gateway, status FROM `payment_transaction` JOIN exam_invoice ON payment_transaction.receipt_no = exam_invoice.receipt_no Where status IN (0,2) AND payment_transaction.exam_code != 991 AND date LIKE '%" . $yesterday . "%'  and exam_invoice.invoice_image=''";

            $record = $this->db->query($sql);

            if ($record->num_rows()) {
                foreach ($record->result_array() as $c_row) {
                    if ($c_row['gateway'] == 'billdesk') {
                        $responsedata   = $this->billdesk_pg_model->billdeskqueryapi($c_row['receipt_no']);
                        $payment_status = $responsedata['transaction_error_type'];
                        $auth_status    = $responsedata['auth_status'];
                    }

                    $encData    = implode('|', $responsedata);
                    $resp_data  = json_encode($responsedata);
                    $resp_array = array('receipt_no' => $c_row['receipt_no'],
                        'txn_status'                     => $payment_status,
                        'txn_data'                       => $encData . '&CALLBACK=C_S2S',
                        'response_data'                  => $resp_data,
                        'remark'                         => '',
                        'resp_date'                      => date('Y-m-d H:i:s'),
                    );
                    $this->master_model->insertRecord('pending_payment', $resp_array);
                    if ($auth_status == "0300") {
                        if ($c_row['gateway'] == 'billdesk') {
                            $headers = array(
                                'alg'      => $this->config->item('BD_ALG'), //'HS256',
                                'clientid' => $this->config->item('BD_CLIENTID'), //'uatiibfv2'
                            );
                            $key                = $this->config->item('BD_KEY'); //'dEjcgb0OQpLeC4gApFpL5msdPxHt0w79';
                            $refund_url         = $this->config->item('BD_REFUND_URL'); //'https://pguat.billdesk.io/payments/ve1_2/refunds/create';
                            $random_trace       = substr(md5(mt_rand()), 0, 7);
                            $mercid             = $this->config->item('BD_MERCID'); //'UATIIBFV2';
                            $orderId            = $c_row['receipt_no']; //'900485161';  Test number
                            $txn_id             = $responsedata['transactionid'];
                            $txn_date           = $responsedata['transaction_date'];
                            $txn_amount         = $responsedata['amount'];
                            $currency           = $responsedata['currency'];
                            $merc_refund_ref_no = 'REF_' . $orderId;
                            $payload            = [
                                "transactionid"      => $txn_id,
                                "orderid"            => $orderId,
                                "mercid"             => $mercid,
                                "transaction_date"   => $txn_date,
                                "txn_amount"         => $txn_amount,
                                "refund_amount"      => $txn_amount,
                                "currency"           => $currency,
                                "merc_refund_ref_no" => $merc_refund_ref_no,
                            ];
                            //echo '<br/> Payload : <pre>';    print_r($payload); echo '</pre>';
                            $jws       = new \Gamegos\JWS\JWS();
                            $jwsString = $jws->encode($headers, $payload, $key);
                            //echo "<br/>".$jwsString."<br/>";
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $refund_url);
                            // Set HTTP Header for POST request
                            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                'Content-Type: application/jose',
                                'accept: application/jose',
                                'bd-traceid: ' . time() . $random_trace, //'REF1K',
                                'bd-timestamp: ' . time())
                            );
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $jwsString);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            $info = curl_getinfo($ch);
                            //print_r($info);
                            $result = curl_exec($ch);
                            curl_close($ch);
                            $refund_result = $jws->verify($result, $key);
                            if ($refund_result['payload']['objectid'] == 'refund') {
                                $pr_status  = 3;
                                $update_arr = array('status' => $pr_status, 'callback' => 'PSS2S');
                                $where_arr  = array('receipt_no' => $c_row['receipt_no']);
                                $this->master_model->updateRecord('payment_transaction', $update_arr, $where_arr);
                                echo $str .= $this->db->last_query() . '\n';
                            }
                        }
                    } else {
                        if ($auth_status == "0399") {
                            $tra_status = 'FAIL';
                            $status     = 0;
                        } else if ($auth_status == "0799") {
                            $status     = 4;
                            $tra_status = "refund";
                        } else if ($auth_status == "0002") {
                            $status     = 7;
                            $tra_status = "trn_cancel_no_response_from_billdesk_t2Day";
                        } else {
                            $status     = 0;
                            $tra_status = "FAIL";
                        }
                        $update_arr = array('status' => $status, 'transaction_details' => $tra_status, 'callback' => 'PSS2S');
                        $where_arr  = array('receipt_no' => $c_row['receipt_no']);
                        $this->master_model->updateRecord('payment_transaction', $update_arr, $where_arr);
                        echo $str .= $this->db->last_query() . '\n';
                    }
                }
            }
            $fp = @fopen($dir . "/logs_" . date("dmY") . ".txt", "a") or die("Unable to open file!");
            echo $str .= date('Y-m-d H:i:s') . ' File execution start';
            fwrite($fp, $str);
            fclose($fp);

            flock($filehandle, LOCK_UN); // don't forget to release the lock
        } else {
            // throw an exception here to stop the next cron job
            echo "Payment pending is already running";
        }
        fclose($filehandle);
    }

    public function refund_after_capacity_full_cron()
    {
        $this->load->model('Emailsending');

        $yesterday = date('Y-m-d', strtotime("-1 days"));

        $this->db->where("res_date LIKE '%$yesterday%'");
        $result = $this->master_model->getRecords('S2S_direcrt_refund', array(), 'receipt_no');
        if (count($result) > 0) {

            foreach ($result as $key => $res) {
                $this->refund_after_capacity_full->make_refund($res['receipt_no']);
            }
        }

        $final_str = 'Hello Sir cron executed for refund_after_capacity_full_cron count: '.count($result).'<br/><br/>';
        $final_str .= 'Regards,';
        $final_str .= '<br/>';
        $final_str .= 'IIBF TEAM';
        $info_arr = array('to' => 'vishal.phadol@esds.co.in',
            'from'                 => 'noreply@iibf.org.in',
            'subject'              => 'IIBF: Exam Refund',
            'message'              => $final_str,
        );
        $this->Emailsending->mailsend_attch($info_arr);

    }

}
