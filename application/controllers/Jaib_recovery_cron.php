<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Jaib_recovery_cron extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('master_helper');
        $this->load->model('master_model');
        $this->load->model('Emailsending');
        $this->load->model('log_model');
        $this->load->model('billdesk_pg_model');
        $this->load->model('jaib_recovery_model');

    }

    public function cron()
    {
        $this->db->where("exam_code in (".$this->config->item('examCodeJaiib').",".$this->config->item('examCodeDBF').",".$this->config->item('examCodeSOB').")");
        $recovery_data = $this->master_model->getRecords('jaiib_exam_recovery_master', array('pay_status' => 1, 'exam_period' => '123'));

        if (count($recovery_data)) {
            foreach ($recovery_data as $key => $value) {
                $MerchantOrderNo  = $value['new_receipt_no'];
                $qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);
                if (count($qry_api_response) > 0 && array_key_exists('auth_status', $qry_api_response) && $qry_api_response['auth_status'] == '0300') {
                    $this->jaib_recovery_model->settle($value['member_no']);
                }
            }
        }

        $final_str = 'Hello Vishal <br/><br/>';
        $final_str .= 'JAIIB recovery cron executed.<br/> Function name: Jaib_recovery_cron/cron1.<br/>Record count is- ' . count($recovery_data);
        $final_str .= '<br/><br/>';
        $final_str .= 'Regards,';
        $final_str .= '<br/>';
        $final_str .= 'ESDS TEAM';
        $info_arr = array('to' => 'vishal.phadol@esds.co.in',
            'from'                 => 'noreply@iibf.org.in',
            'subject'              => 'JAIIB recovery cron executed',
            'message'              => $final_str,
        );
        $this->Emailsending->mailsend_attch($info_arr);

    }

    public function fetch_billdesk()
    {

        $this->db->where("exam_code in (".$this->config->item('examCodeJaiib').",".$this->config->item('examCodeDBF').",".$this->config->item('examCodeSOB').")");
        $recovery_data = $this->master_model->getRecords('jaiib_exam_recovery_master', array('new_receipt_no >' => 0, 'pay_status' => 2, 'exam_period' => '123'));

        if (count($recovery_data)) {
            foreach ($recovery_data as $key => $value) {

                $MerchantOrderNo     = $value['new_receipt_no'];
                $member_regnumber    = $value['member_no'];
                $selected_invoice_id = $value['invoice_id'];

                $qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);

                if (count($qry_api_response) > 0 && array_key_exists('auth_status', $qry_api_response) && $qry_api_response['auth_status'] == '0300') {

                    $transaction_no         = $qry_api_response['transactionid'];
                    $merchIdVal             = $qry_api_response['mercid'];
                    $Bank_Code              = $qry_api_response['bankid'];
                    $encData                = json_encode($qry_api_response);
                    $transaction_error_type = $qry_api_response['transaction_error_type'];

                    $update_data = array('new_transaction_no' => $transaction_no, 'pay_status' => 1, 'modified_on' => date('Y-m-d H:i:s'));
                    $this->master_model->updateRecord('jaiib_exam_recovery_master', $update_data, array('invoice_id' => $selected_invoice_id, 'member_no' => $member_regnumber));
                    $this->jaib_recovery_model->settle($value['member_no']);

                    /* Transaction Log */
                    $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                    $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);

                    $log_title   = "JAAIB recovery through fetch_billdesk:" . $member_regnumber;
                    $log_message = serialize($recovery_data);
                    $rId         = 0;
                    $regNo       = $member_regnumber;
                    storedUserActivity($log_title, $log_message, $rId, $regNo);
                }

            }
        }

        $final_str = 'Hello Vishal <br/><br/>';
        $final_str .= 'JAIIB recovery cron executed.<br/> Function name: Jaib_recovery_cron/fetch_billdesk.<br/>Record count is- ' . count($recovery_data);
        $final_str .= '<br/><br/>';
        $final_str .= 'Regards,';
        $final_str .= '<br/>';
        $final_str .= 'ESDS TEAM';
        $info_arr = array('to' => 'vishal.phadol@esds.co.in',
            'from'                 => 'noreply@iibf.org.in',
            'subject'              => 'JAIIB recovery cron executed',
            'message'              => $final_str,
        );
        $this->Emailsending->mailsend_attch($info_arr);

    }

}
