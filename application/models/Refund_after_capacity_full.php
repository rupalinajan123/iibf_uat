<?php
/********************************************************************
 * Description: COMMON FUNCTION FOR IMMEDIATE REFUND WHEN CAPACITY IS FULL
 * Created BY: Vishal Phadol, 20-sept-2022
 * Update By:  Vishal Phadol, 20-sept-2022
 ********************************************************************/

defined('BASEPATH') or exit('No direct script access allowed');

class Refund_after_capacity_full extends CI_Model
{
    public function make_refund($receipt_no)
    {
        if ($receipt_no != '') {
           
            $this->load->model('master_model');
            $this->load->model('billdesk_pg_model');

            $this->db->join('member_registration r', 'r.regnumber = pt.member_regnumber', 'LEFT');
            $this->db->join('exam_master em', 'em.exam_code=pt.exam_code', 'LEFT');
            $payment_result = $this->master_model->getRecords('payment_transaction pt', array('receipt_no' => $receipt_no), 'status,ref_id,r.email,r.mobile,em.description');

            $invoice_result = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $receipt_no), 'invoice_no');

            $payment_status = $payment_result[0]['status'];
            $ref_id         = $payment_result[0]['ref_id'];
            $invoice_no     = $invoice_result[0]['invoice_no'];

            $member_email = '';
            if (isset($payment_result[0]['email'])) {
                $member_email = $payment_result[0]['email'];
            }

            $exam_name = '';
            if (isset($payment_result[0]['description'])) {
                $exam_name = $payment_result[0]['description'];
            }

            if ($payment_status == 1 && $invoice_no == '') {

                $responsedata = $this->billdesk_pg_model->billdeskqueryapi($receipt_no);

                if (isset($responsedata) && count($responsedata) > 0 && array_key_exists('auth_status', $responsedata)) {
                    $auth_status = $responsedata['auth_status'];
                    if ($auth_status == '0300') {
                   
                        $refund_result = $this->billdesk_pg_model->billdeskRefundApi($receipt_no);
                        
                        $refund_status = $this->billdesk_pg_model->billdeskRefundStatusApi($receipt_no); 
                        
                        if (count($refund_status) > 0 && array_key_exists('refund_status', $refund_status)) {
                            
                            if ($refund_status['refund_status'] == '0699') {

                                $this->master_model->updateRecord('payment_transaction',
                                    array('status' => 3, 'transaction_details' => 'refund', 'callback' => 'CFR'),
                                    array('receipt_no' => $receipt_no)
                                );

                                $this->master_model->updateRecord('admit_card_details',
                                    array('remark' => 3),
                                    array('mem_exam_id' => $ref_id)
                                );

                                $this->master_model->updateRecord('member_exam',
                                    array('pay_status' => 0),
                                    array('id' => $ref_id)
                                );

                                $this->master_model->updateRecord('exam_invoice',
                                    array('transaction_no' => ''),
                                    array('receipt_no' => $receipt_no)
                                );

                                $this->send_mem_mail_refund($member_email, $exam_name);

                            }
                        }

                    }
                }
            }

        }
    }

    public function send_mem_mail_refund($member_email, $exam_name)
    {
        $this->load->model('Emailsending');
        ini_set("memory_limit", "-1");

        if ($member_email != '') {
            $final_str = 'Hello Sir/Madam <br/><br/>';
            $final_str .= 'Your ' . $exam_name . ' exam Amount is refunded as there was not capacity for selected center,soon you will receive Amount in your account.';
            $final_str .= '<br/><br/>';
            $final_str .= 'Requesting you to again apply with the other center.';
            $final_str .= '<br/><br/>';
            $final_str .= 'Regards,';
            $final_str .= '<br/>';
            $final_str .= 'IIBF TEAM';
            $info_arr = array('to' => $member_email,
                'from'                 => 'noreply@iibf.org.in',
                'subject'              => 'IIBF: Exam Refund',
                'message'              => $final_str,
            );
            $this->Emailsending->mailsend_attch($info_arr);

        }

    }
}
